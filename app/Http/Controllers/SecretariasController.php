<?php

namespace App\Http\Controllers;

use App\Secretarias;
use App\User;
use App\Status;
use App\UsersActions;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\DataTables;

class SecretariasController extends Controller
{
    public function show(){
        $user = Auth::user();
        if ($user->level >= Status::MASTER){
            return view('secretarias.secretarias');
        }
        else{
            return abort(403, 'Você não é um Administrador');
        }
    }

    public function getdata(){
        $user = Auth::user();
        if($user->level >= Status::MASTER){
            $secretarias = Secretarias::latest()->get();
            for ($i = 0; $i< count($secretarias); $i++){
                $secretarias[$i]->data = Carbon::parse($secretarias[$i]->created_at)->format('d/m/Y').' às '.Carbon::parse($secretarias[$i]->created_at)->format('H:i:s');
            }
            return DataTables::of($secretarias)
                ->addColumn('action', function($data){
                    $button = '<a href="'.route('viewSec',encrypt($data->id)).'"><button type="submit" class="btn btn-primary item" data-toggle="tooltip" data-placement="top" title="View"><i class="fas fa-eye"></i></button></a>&nbsp;&nbsp;';
                    return $button;
                })->make(true);
        }
        else{
            return abort(403);
        }
    }

    public function adicionar(){
        $user = Auth::user();
        if (!$user->level >= 4){
            abort(403, 'Você não é um Administrador');
        }
        return view('secretarias.adicionar');
    }
    public function create(Request $request){
        $user = Auth::user();
        if($user->level >= Status::MASTER){
            if(Secretarias::where('email', $request->email)->count() >= 1){
                return redirect()->back()->with('error', 'Essa Secretaria Já existe');
            }
            $validator = Validator::make($request->all(), [
                'nameSec' => ['required', 'string', 'max:60'],
                'emailSec' => ['required', 'string', 'max:60']
            ]);
            if ($validator->fails()) {
                return redirect()->back()->with('error', 'Verifique se os dados foram preenchidos corretamente');
            }
            else{
            $dados = array('name' => $request->nameSec, 'email' => $request->emailSec);
                if (Secretarias::create($dados)){
                    $logs = array('func' => $user->id, 'sec_id' => $user->sec_id, 'setor_id' => $user->setor_id, 'action' => 'Criou a Secretaria/Autarquia: '. $request->name);
                    UsersActions::create($logs);
                    return redirect()->route('SecretariasShow')->with('success', 'Secretaria/Autarquia adicionada com Sucesso !!');
                }
                else{
                    return redirect()->route('SecretariasShow')->with('error', 'Erro ao tentar adicionar a Secretaria/Autarquia !!');
                }
            }
        }
        else{
            return abort(403, 'Você não tem permissão');
        }
    }
    public function view($id){
        $user = Auth::user();
        if ($user->level >= Status::MASTER){
            $allUsers = array();
            $secretarias = Secretarias::find(decrypt($id));
        }
        else{
            abort(403, 'Você não é um Administrador');
        }

        return view('secretarias.view', compact('secretarias', 'allUsers'));
    }
    public function getdataAdms($idSec){
        $user = Auth::user();
        if($user->level >= Status::MASTER){
            $funcionarios = User::where('sec_id', decrypt($idSec))->where('status','!=', 0)->get();
            $count = count($funcionarios);
            for ($i = 0; $i < $count; $i++) {
                if ($funcionarios[$i]->setor_id == 0 or $funcionarios[$i]->setor_id == null) {
                    $funcionarios[$i]['sector'] = 'Sem Setor';
                } 
                else {
                    $sector = $funcionarios[$i]->setor;
                    if($sector != null){
                        $funcionarios[$i]['sector'] = $sector->name;
                    }
                    else{
                        $funcionarios[$i]['sector'] = 'Setor Inválido';
                    }
                }
                if ($funcionarios[$i]->sec_id == 0 or $funcionarios[$i]->sec_id == null) {
                    $funcionarios[$i]->secretaria = 'Sem Sec/Aut';
                } else {
                    $funcionarios[$i]->secretaria = Secretarias::find($funcionarios[$i]->sec_id)->name;
                }
                $funcionarios[$i]['idd'] = encrypt($funcionarios[$i]->id);
            }
        }
        else{
            return abort(403, 'Você não tem permissão suficente');
        }
        return DataTables::of($funcionarios)
            ->addColumn('action', function($data) use ($user){
                if(Auth::user()->level >= Status::MASTER){
                    $id = encrypt($data->id);
                    if($user->level == 5){
                        $button = $this->selectLevel($id, $data->level, 5);
                    }
                    else{
                        $button = $this->selectLevel($id, $data->level, 3);
                    }
                    return $button;
                }
                else{
                    return '-';
                }
            })->make(true);
    }
    public function changerole(Request $request){
        $user = Auth::user();
        if($user->level >= Status::MASTER){
            $validator = Validator::make($request->all(), [
                'func' => ['required', 'string'],
                'role' => ['required', 'string'],
            ]);
            if ($validator->fails()) {
                return 'Dados Inválidos !!';
            }
            else{
                try{
                    $employee = decrypt($request->func);
                    $level = decrypt($request->role);
                }
                catch(\Exception $e){
                    return 'Valores Inválidos';
                }
                try{
                    $funcionario = User::find($employee); 
                }
                catch(\Exception $f){
                    return 'Esse Funcionário não existe';
                }
                switch($level){
                     case 1 || 2:
                        if($user->level >= Status::ADMINISTRADOR){
                            $funcionario->level = $level;
                        }
                        else{
                            return 'Você não tem permissão para esse nível de autorização';
                        }
                        break;
                     case 3:
                        if($user->level == Status::MASTER){
                            $funcionario->level = $level;
                        }
                        else{
                            return 'Você não tem permissão para esse nível de autorização';
                        }
                        break;
                    default:
                        return 'Nível de autorização inválido';
                        break; 
                }
                
                if($funcionario->update()){
                    return 1;
                }
                else{
                    return 0;
                }
            }
        }
        else{
            return abort(403, "Você não tem permissão suficiente");
        }
    }
    protected function selectLevel($id, $level, $quantia){
        $button = '<select data-id="'.$id.'" class="selectStaffs form-control">';
        $roles = [1 => 'Usuário', 2 => 'Moderador', 3 => 'Administrador', 4 => 'Super Admin', 5 => 'Master'];
        for($i = 1;$i <= $quantia;$i++){
            if($level == $i){
                $button .= '<option selected value="'.encrypt($i).'">'.$roles[$i].'</option>';
            }
            else{
                $button .= '<option value="'.encrypt($i).'">'.$roles[$i].'</option>';
            }
        }
        $selectClose = $button.'</select>';
        return $selectClose;
    }
}
