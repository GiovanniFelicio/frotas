<?php

namespace App\Http\Controllers;

use App\AuthFuncVehicle;
use App\Logbook;
use App\Status;
use App\Veiculos;
use App\User;
use App\UsersActions;
use Auth as GlobalAuth;
use DateTime;
use Exception;
use Hash;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;

class FrotaController extends Controller
{
    public function show(){
        $user = Auth::user();
        if($user->level >= Status::OPERADOR){
            return view('frota.frota');
        }
        else{
            return abort(403, 'Não autorizado');
        }
    }
    public function searchvehicle(Request $request){
        $user = Auth::user();
        if($user->level >= Status::OPERADOR){
            $validator = Validator::make($request->all(), [
                'codigo' => ['required'],
            ]);
            if ($validator->fails()) {
                return 0; /* Falha na validação */
            }
            $veiculo = Veiculos::where('placa', $request->codigo)->get()->last();
            if($veiculo != null){
                switch($veiculo->status){
                    case 0:
                        $status = 'Destivado';
                    break;
                    case 1:
                        $status = 'Fora de Curso';
                    break;
                    case 2:
                        $status = 'Em Curso';
                    break;
                    default:
                        $status = 'Error';
                    break;
                }
                $dados = array('reference' => encrypt($veiculo->id), 
                            'nome' => $veiculo->name, 
                            'placa' => $veiculo->placa, 
                            'secretaria' => $veiculo->secretaria->name, 
                            'situacao' => $status);
            }
            else{
                return 2; /** Não Encontrado 0 */
            }
            return response()->json($dados);
        }
        else{
            return 3; 
        }
    }
    public function searchdriver(Request $request){
        $user = Auth::user();
        if($user->level >= Status::OPERADOR){
            $validator = Validator::make($request->all(), [
                'codigo' => ['required'],
            ]);
            if ($validator->fails()) {
                return 0; /* Falha na validação */
            }
            $driver = User::where('matricula', $request->codigo)->orWhere('barcode', $request->codigo)->get()->last();
            if($driver != null){
                switch($driver->status){
                    case 0:
                        $status = 'Desativado';
                    break;
                    case 1:
                        $status = 'Fora de Curso';
                    break;
                    case 2:
                        $status = 'Em Curso';
                    break;
                    default:
                        $status = 'Error';
                    break;
                }
                $dados = array('reference' => encrypt($driver->id), 
                            'nome' => $driver->name, 
                            'matricula' => $driver->matricula, 
                            'secretaria' => $driver->secretaria->name, 
                            'email' => $driver->email,
                            'status' => $status);
            }
            else{
                return 2; /** Não Encontrado 0 */
            }
            return response()->json($dados);
        }
        else{
            return 3; 
        }
    }
    public function verifyauth(Request $request){
        $user = Auth::user();
        if($user->level >= Status::OPERADOR){
            $validator = Validator::make($request->all(), [
                'car' => ['required'],
                'driver' => ['required'],
            ]);
            if ($validator->fails()) {
                return [0, 'Falha na Validação']; /* Falha na validação */
            }
            date_default_timezone_set('America/Bahia');
            $date = new DateTime();
            $datetime = $date->format('Y-m-d H:i:s');
    
            try{
                $carId = decrypt($request->car);
            }
            catch(Exception $e){
                return [0, 'Código inválido do veículo'];
            }
            try{
                $driverId = decrypt($request->driver);
            }
            catch(Exception $e){
                return [0, 'Código inválido do motorista'];
            }
    
            try{
                $veiculo = Veiculos::find($carId);
                if($veiculo == null){
                    return [0, 'Veículo não Encontrado']; /* Veículo não encontrado */
                }
                else if($veiculo->status == 1){
                    try{
                        $employee = User::find($driverId);
                        if($employee == null){
                            return [0, 'Motorista não Encontrado']; /* Motorista não encontrado */
                        }
                        else if($employee->status == 2){
                            return [0, 'Motorista em curso']; /* Motorista em curso */
                        }
                    }
                    catch(\Exception $e){
                        return [0, 'Motorista não Encontrado']; /* Motorista não encontrado */
                    }
                    $auth = AuthFuncVehicle::where('func_id', $employee->id)
                                            ->where('vehicle_id', $veiculo->id)
                                            ->where('status',1)
                                            ->get()->count();
                    if($auth == 1){
            
                        $dados = array('dateTimeSai' => $datetime, 
                                       'veiculo' => $veiculo->id, 
                                       'func_id' => $employee->id, 
                                       'setor_id' => $employee->setor_id, 
                                       'sec_id' => $employee->sec_id,
                                       'origem' => $user->secretaria->name,
                                       'actions' => 'Iniciado pelo Motorista');
                        
                        if(Logbook::create($dados) and $employee->update(['status' => 2]) and $veiculo->update(['status' => 2])){
                            return [1, 'Autorizado !!']; /** Autorizado */
                        }
                        else{
                            return [0, 'Erro ao criar Bordo']; /** Erro ao salvar */
                        }
                    }
                    else{
                        return [0, 'Não Autorizado'];; /** Não autorizado */
                    }
                }
                else if($veiculo->status == 2){
                    try{
                        $employee = User::find($driverId);
                        if($employee == null){
                            return [0, 'Motorista não Encontrado']; /* Motorista não encontrado */
                        }
                    }
                    catch(\Exception $e){
                        return [0, 'Motorista não Encontrado']; /* Motorista não encontrado */
                    }
                    $logbook = Logbook::where('func_id', $employee->id)
                                        ->where('veiculo', $veiculo->id)
                                        ->where('status', Status::SAIDAVIAGEM)
                                        ->get()->last();
                    if($logbook != null){
            
                        $dados = array('dateTimeCheg' => $datetime, 
                                        'status' => Status::FINALIZADO,
                                        'actions' => 'Finalizado pelo motorista');
                        
                        if($logbook->update($dados) and $employee->update(['status' => 1]) and $veiculo->update(['status' => 1])){
                            return [1, 'Autorizado !!']; /** Autorizado */
                        }
                        else{
                            return [0, 'Erro ao atualizar Bordo']; /** Erro ao salvar */
                        }
                    }
                    else{
                        return [0, 'Bordo Inexistente']; /**Bordo inexistnte */
                    }
                }
                else{
                    return [0, 'Veículo não Encontrado']; /* Veículo não encontrado */
                }
            }
            catch(\Exception $e){
                return [0, 'Veículo não Encontrado']; /* Veículo não encontrado */
            }
        }
        else{
            return [0, 'Sem Permissão'];
        }
    }
}
