<?php

namespace App\Http\Controllers;


use App\Secretarias;
use App\Setores;
use App\Status;
use App\User;
use App\UsersActions;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Facades\Excel;
use Yajra\DataTables\DataTables;

class SetoresController extends Controller
{
    public function getsectors($id){
        $user = Auth::user();
        if($user->level >= Status::ADMINISTRADOR){
            $setores = Setores::where('sec_id',decrypt($id))->get();
            $dados = array();
            foreach($setores as $key => $setor){
                $dados[$key] = ['reference' => encrypt($setor->id), 'nome' => $setor->name];
            }
            return $dados;
        }
        else{
            return 'Você não tem permissão suficente';
        }
    }
    public function show(){

        return view('setores.setores');
    }
    public function sectors($id){

        $level = Auth::user()->level;
        if ($level < Status::ADMINISTRADOR){
            abort(403, 'Você não é um Administrador');
        }
        $setores = Setores::where('sec_id', decrypt($id))->get(['id', 'name']);
        $dados = array();
        foreach($setores as $key => $setor){
            $dados[$key]['reference'] = encrypt($setor->id);
            $dados[$key]['nome'] = $setor->name;
        }
        return response()->json($dados);
    }
    public function adcSetor(){
        if (Auth::user()->level < Status::ADMINISTRADOR){
            abort(403, 'Você não é um Administrador');
        }
        $secretarias = Secretarias::all();
        return view('setores.adicionar', compact('secretarias'));
    }
    public function create(Request $request){

        $user = Auth::user();
        if ($user->level >= Status::MASTER){
            $validator = Validator::make($request->all(), [
                'nameSector' => ['required'],
                'secretaria' => ['required']
            ]);
            if ($validator->fails()) {
                return redirect()->back()->with('error', 'Erro !!, Verifique se foi digitado um nome valido.');
            }
            try{
                $secretaria = Secretarias::find(decrypt($request->secretaria));
            }
            catch(Exception $e){
                return redirect()->route('adcSetor')->with('error', 'Secretaria inválida !!');
            }
        }
        elseif($user->level == Status::ADMINISTRADOR){
            $validator = Validator::make($request->all(), [
                'nameSector' => ['required', 'string', 'max:191']
            ]);
            if ($validator->fails()) {
                return redirect()->back()->with('error', 'Erro !!, Verifique se foi digitado um nome valido.');
            }
            $secretaria = $user->secretaria;
        }
        else{
            return abort(403, 'Você não é um Administrador');
        }

        $setor = Setores::where('name', $request->name)->get();
        if ($setor->count() == 1){
            $sector = $setor->last();
            if ($sector->status == 1){
                return redirect()->route('setores')->with('error','Esse setor já existe !!');
            }
            else{
                $sector->status = 1;
                if ($sector->update()){
                    $logs = array('func' => $user->id, 'sec_id' => $user->sec_id, 'setor_id' => $user->setor_id, 'action' => 'Reativou o Setor '. $sector->name.' da Sec/Aut '.$secretaria->name);
                    UsersActions::create($logs);
                }
            }
        }
        $dados = array('name' => $request->nameSector, 'sec_id' => $secretaria->id);
        if (Setores::create($dados)){
            $logs = array('func' => $user->id, 'sec_id' => $user->sec_id, 'setor_id' => $user->setor_id, 'action' => 'Criou o Setor '. $request->nameSector.' da Sec/Aut '.$secretaria);
            UsersActions::create($logs);
            return redirect()->route('setores')->with('success','Setor Adicionado com sucesso !!');
        }
        else{
            return redirect()->back()->with('error','Error ao adicionar o Setor, caso persista comunique o setor de informática da FUNDETEC.');
        }
    }
    public function delete($idd){
        $user = Auth::user();
        $id = decrypt($idd);
        if ($user->level >= Status::ADMINISTRADOR){
            $funcs = Setores::find($id)->employees;
            for($i = 0; $i < count($funcs); $i ++){
                $funcs[$i]->setor_id = 0;
                $funcs[$i]->update();
            }
            $sectors = Setores::find($id);
            $sectors->status = 0;
            $secretaria = Secretarias::find($sectors->sec_id)->name;
            if ($sectors->update())
            {
                $logs = array('func' => $user->id, 'sec_id' => $user->sec_id, 'setor_id' => $user->setor_id, 'action' => 'Deletou o setor '. $sectors->name.' da Sec/Aut '.$secretaria);
                UsersActions::create($logs);
                return redirect()->back()->with('success', 'Deletado com Sucesso !!');
            }
            else{
                return redirect()->back()->with('error','Erro ao Deletar');
            }
        }
        else{
            return abort(403, 'Você não é um Administrador');
        }
    }

    public function view($id){

        $setor = Setores::find(decrypt($id));

        return view('setores.view', compact('setor'));
    }

    public function funcSetor($id){
        $user = Auth::user();
        if($user->level < Status::MASTER){
            return abort(403, 'Você não tem permissao suficente');
        }
        $setor = Setores::find(decrypt($id));
        $funcsSetores = User::where('setor_id', $setor->id)->where('status',1)->get();

        return response()->json($funcsSetores);
    }

    public function adcFuncSector(Request $request){

        $count = count($request->Func);
        $all = $request;
        $authUser = Auth::user();
        $setor = Setores::find(decrypt($request->setor));
        if (Secretarias::find($setor->sec_id) == null){
            return 'Não foi possível adicionar funcionário a este setor pois ele não pertence a nenhuma Sec/Aut';
        }
        if ($authUser->level < Status::ADMINISTRADOR){
            return abort(403, 'Você não é um Administrador');
        }
        for ($j = 0; $j < $count; $j++){
            if(User::where('id', $all->Funcs[$j])->where('setor_id',$request->setor)->count() == 1){
                return 'Esse Funcionário já faz parte desse setor';
            }
            else{
                $user = User::find($all->Func[$j]);
                $user->setor_id = $request->setor;
                $user->token_access = null;
                if($user->update()){
                    $logs = array('func' => $user->id, 'sec_id' => $user->sec_id, 'setor_id' => $user->setor_id, 'action' => 'Adicionou o Funcionario '. $user->name.' ao setor '.$setor->name);
                    UsersActions::create($logs);

                }
            }
        }
        return 'Sucesso';
    }
    public function delFuncSetor($id){
        $user = Auth::user();
        if ($user->level >= Status::MASTER){
            $func = User::find($id);
            $setor = Setores::find($func->setor_id)->name;
            $func->setor_id = 0;
            if ($func->update()){
                $logs = array('func' => $user->id, 'sec_id' => $user->sec_id, 'setor_id' => $user->setor_id, 'action' => 'Deletou o Funcionário '. $func->name.' do setor '.$setor);
                UsersActions::create($logs);
                return 'Sucesso';
            }
            else{
                return 'Erro ao Deletar';
            }
        }
        else{
            return abort(403, 'Você não é um Administrador');
        }
    }
    public function getdata(){
        $user = Auth::user();
        if ($user->level <= Status::ADMINISTRADOR){
            $sector = Setores::where('sec_id', $user->sec_id)->where('id', '!=', 1)->where('status', 1)->get();
            for ($i = 0; $i < count($sector); $i++){
                if ($sector[$i]->sec_id == 0 or $sector[$i]->sec_id == null) {
                    $sector[$i]->secretaria = 'Sem Secretaria/Autarquia';
                }
                else{
                    $sector[$i]->secretaria = Secretarias::find($sector[$i]->sec_id)->name;
                }
            }
        }
        elseif($user->level >= Status::MASTER){
            $sector = Setores::where('status', 1)->where('id', '!=', 1)->get();
            for ($i = 0; $i < count($sector); $i++){
                if ($sector[$i]->sec_id == 0 or $sector[$i]->sec_id == null) {
                    $sector[$i]->secretaria = 'Sem Secretaria/Autarquia';
                }
                else{
                    $sector[$i]->secretaria = Secretarias::find($sector[$i]->sec_id)->name;
                }
            }
        }
        else{
            return abort(403, 'Você não tem permissão suficente');
        }
        return DataTables::of($sector)
            ->addColumn('action', function($data){
                $id = encrypt($data->id);
                if(Auth::user()->level >= Status::MASTER){
                    if(Secretarias::find($data->sec_id) == null){
                        $button = '<button data-id="'.$id.'" class="btn btn-danger  item del" data-toggle="tooltip" data-placement="top" title="Delete"><i class="fas fa-trash-alt"></i></button>';
                    }
                    else{
                        $button = '--';
                    }
                }
                else{
                    $button = '--';
                }
                return $button;
            })->make(true);
    }
}
