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

class LogBookController extends Controller
{
    public function home(){
        $user = Auth::user();
        if($user->status == Status::EMCURSO){
            $logbook = Logbook::where('func_id', $user->id)->get(['id', 'origem', 'veiculo'])->last();
            try{;
                $logbook->veiculo = $logbook->vehicle->name;
                $logbook['km'] = $logbook->vehicle->km;
            }
            catch(\Exception $e){
                $mensagem = 'Erro no sistema, Veículo Inválido';
                return view('logBook.sembordo', compact('mensagem'));
            }
            return view('logBook.logbook', compact('logbook'));
        }
        else{
            $mensagem = 'Nenhum Bordo Disponível';
            return view('logBook.sembordo', compact('mensagem'));
        }
    }
    public function create(Request $request){
        $user = Auth::user();
        $validator = Validator::make($request->all(), [
            'reference' => ['required'],
            'irreguCheck' => ['required']
        ]);
        if ($validator->fails()) {
            return redirect()->back()->with('error', 'Verifique se os dados foram preenchidos corretamente');
        }
        try{
            $logbook = Logbook::find(decrypt($request->reference));
        }
        catch(\Exception $e){
            return redirect()->route('home')->with('error', 'Bordo Inválido !!');
        }
        try{
            $func = User::find($logbook->func_id);
            if($func->id != $user->id){
                return redirect()->route('home')->with('error', 'Você não é o motorista desta viagem');
            }
        }
        catch(\Exception $e){
            return redirect()->route('home')->with('error', 'Funcionário inválido');
        }
        $dados = array('irreguSai' => $request->irreguSai ?? null);
        if($logbook->update($dados) and $func->update(['status' => Status::CHEGADAVIAGEM])){
            return redirect()->route('home')->with('success', 'Sucesso !!');
        }
        else{
            return redirect()->route('home')->with('error', 'Não foi possível atualizar o bordo');
        }
        
    }

    public function check(Request $request){
        $tokens = array('Fundetec' => 'f&u$n!d&e#t!e$c//frotas');
        $validator = Validator::make($request->all(), [
            'token' => ['required'],
            'vehicle' => ['required'],
            'employee' => ['required'],
        ]);
        if ($validator->fails()) {
            return [0, 'Falha na Validação']; /* Falha na validação */
        }
        $local = array_search($request->token, $tokens);
        if($local == false){
            return 2; /* Falha na autenticação */
        }
        date_default_timezone_set('America/Bahia');
        $date = new DateTime();
        $datetime = $date->format('Y-m-d H:i:s');

        try{
            $veiculo = Veiculos::where('chaverfid', $request->vehicle)
                                ->whereIn('status', [1,2])->get()->last();
            if($veiculo == null){
                return 3; /* Veículo não encontrado */
            }
            else if($veiculo->status == 1){
                try{
                    $employee = User::where('rfid', $request->employee)
                                    ->orWhere('barcode', $request->employee)
                                    ->whereIn('status', [1,2])->get()->last();
                    if($employee == null){
                        return 4; /* Motorista não encontrado */
                    }
                    else if($employee->status == 2){
                        return 5; /* Motorista em curso */
                    }
                }
                catch(\Exception $e){
                    return 4; /* Motorista não encontrado */
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
                                   'origem' => $local,
                                    'actions' => 'Iniciado pelo Motorista');
                    
                    if(Logbook::create($dados) and $employee->update(['status' => 2]) and $veiculo->update(['status' => 2])){
                        return 6; /** Autorizado */
                    }
                    else{
                        return 7; /** Erro ao salvar */
                    }
                }
                else{
                    return 8; /** Não autorizado */
                }
            }
            else if($veiculo->status == 2){
                try{
                    $employee = User::where('rfid', $request->employee)
                                    ->orWhere('barcode', $request->employee)
                                    ->whereIn('status', [1,2])->get()->last();
                    if($employee == null){
                        return 4; /* Motorista não encontrado */
                    }
                }
                catch(\Exception $e){
                    return 4; /* Motorista não encontrado */
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
                        return 6; /** Autorizado */
                    }
                    else{
                        return 7; /** Erro ao salvar */
                    }
                }
                else{
                    return 9; /**Bordo inexistnte */
                }
            }
            else{
                return 3; /* Veículo não encontrado */
            }
        }
        catch(\Exception $e){
            return 3; /* Veículo não encontrado */
        }
        
    }
    public function finalizar(Request $request){
        $user = Auth::user();
        $validator = Validator::make($request->all(), [
            'reference' => ['required'],
            'motivo' => ['required'],
            'senha' => ['required'],
        ]);
        if ($validator->fails()) {
            return 0; /* Falha na validação */
        }
        if(Hash::check($request->senha, $user->password)){
            try{
                $logbook = Logbook::find(decrypt($request->reference));
            }
            catch(Exception $e){
                return 100; // Bordo Inválido
            }
            date_default_timezone_set('America/Bahia');
            $date = new DateTime();
            $datetime = $date->format('Y-m-d H:i:s');
            $dados = array('dateTimeCheg' => $datetime, 
                           'status' => Status::FINALIZADO,
                           'actions' => 'Finalizado pelo Operador '.$user->name.' motivo: '.$request->motivo);
            $func = $logbook->employee;
            $func->status = 1;
            $veiculo = $logbook->vehicle;
            $veiculo->status = 1;
            if($logbook->update($dados) and $func->update() and $veiculo->update()){
                $logs = array('func' => $user->id, 'sec_id' => $user->sec_id, 'setor_id' => $user->setor_id, 'action' => 'Finalizou o bordo '.$logbook->id);
                UsersActions::create($logs);
                return 1; //Sucesso
            }
            else{
                return 500; //Erro ao salvar
            }
        }
        else{
            return 403; //Não Autorizado
        }
    }
}
