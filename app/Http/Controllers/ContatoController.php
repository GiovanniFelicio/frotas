<?php

namespace App\Http\Controllers;

use App\AuthFuncVehicle;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Contato;
use App\Logbook;
use App\Status;
use App\Veiculos;
use App\User;
use DateTime;

class ContatoController extends Controller
{
    public function contato(){
        $user = Auth::user();
        if($user->level >= 0){
            return view('contato.contato');
        }
        else {
            return abort(403, 'Sem Permissão');
        }
    }
    public function contact(Request $request){
        $user = Auth::user();
        date_default_timezone_set('America/Bahia');
        $date = new DateTime();
        $this->validate($request,[
            'motivo' => 'required|string',
            'mensagem' => 'required|max:500'
        ],[
            'motivo.required' => 'Valor inválido para o motivo',
            'mensagem.required' => 'Não deixe o campo de mensagem em branco',
            'mensagem.max' => 'Máximo permitido é de 500 caracteres'
        ]);
        $lastcont = Contato::where('func_id', $user->id)->get()->last();
        if($lastcont != null){
            $horasF = date('H:i:s',strtotime('+30 minutes',strtotime($lastcont->created_at)));
            if($date->format('H:i:s') <= $horasF){
                return redirect()->route('contato')->with('error', 'Espere no mínimo 30 minutos para fazer um novo contato');
            }
        }
        
        $conteudo = ($request->mensagem).'De: '.$user->name.' '.$user->email;
        $dados = array('motivo' => $request->motivo, 'mensagem' => $conteudo, 'func_id' => $user->id);
        if(Contato::create($dados)){
            return redirect()->back()->with('success', 'Obrigado por contatar nossa equipe !!');
        }
        else{
            return redirect()->back()->with('error', 'Não foi possível enviar sua mensagem neste momento, tente novamente mais tarde');
        }
    }
    
    public function check($vehicle,$func,$token){
        $tokens = array('Fundetec' => 'f&u$n!d&e%t!e$c$frotas');
        
        $local = array_search($token, $tokens);  
        if($local == false){
            return 2; /* Falha na autenticação */
        }
        date_default_timezone_set('America/Bahia');
        $date = new DateTime();
        $datetime = $date->format('Y-m-d H:i:s');

        $veiculo = Veiculos::where('chaverfid', $vehicle)
                                ->whereIn('status', [1,2])->get()->last();

            if($veiculo == null){
                return 3; /* Veículo não encontrado */
            }
            else if($veiculo->status == 1){
                try{
                    $employee = User::where('rfid', $func)
                                    ->orWhere('barcode', $func)
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
                                   'actions' => 'Usuário iniciou o bordo');
                    $employee->status = 2;
                    $veiculo->status = 2;
                    if(Logbook::create($dados) and $employee->update() and $veiculo->update()){
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
                dd($veiculo);
                try{
                    $employee = User::where('rfid', $func)
                                    ->orWhere('barcode', $func)
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
                                    'actions' => 'O Motorista Finalizou o bordo');
                    
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
}
