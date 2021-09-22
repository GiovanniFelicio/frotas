<?php

namespace App\Http\Controllers;

use App\Secretarias;
use App\Setores;
use App\Status;
use App\User;
use App\UsersActions;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\DataTables;

class EmployeesController extends Controller
{
    public function show(){
        $user = Auth::user();
        if($user->level >= Status::ADMINISTRADOR){
            $setores = Setores::where('sec_id', $user->sec_id)->get(['id', 'name']);
            $secretarias = Secretarias::all();
            $dados = array(); 
            foreach($setores as $key => $setor){
                $dados[$key]['reference'] = encrypt($setor->id);
                $dados[$key]['nome'] = $setor->name;
            }
            $dados1 = array();
            foreach($secretarias as $key => $secretaria){
                $dados1[$key]['reference'] = encrypt($secretaria->id);
                $dados1[$key]['nome'] = $secretaria->name;
            }
            return view('employees.employees',compact('user','dados', 'dados1'));
        }
        elseif($user->level == Status::USUARIO){
            return view('employees.employeesNorm');
        }
        else{
            return abort(403, 'Você não é um Administrador');
        }

    }
    public function criaFunc(){
        $user = Auth::user();
        if($user->level >= Status::ADMINISTRADOR){
            $secretarias = Secretarias::all();
            $dados = array();$dados1 = array();
            foreach($secretarias as $key => $secretaria){
                $dados[$key]['reference'] = encrypt($secretaria->id);
                $dados[$key]['nome'] = $secretaria->name;
            }
            $setores = Setores::where('sec_id', $user->sec_id)->get(['id', 'name']);
            foreach($setores as $key => $setor){
                $dados1[$key]['reference'] = encrypt($setor->id);
                $dados1[$key]['nome'] = $setor->name;
            }
        }
        else{
            return abort(403, 'Você não é um Administrador');
        }
        return view('employees.criaFunc', compact('user', 'dados', 'dados1'));
    }

    public function create(Request $request){
        $user = Auth::user();
        if($user->level >= Status::MASTER){
            $this->validate($request, [
                'secretaria' => 'required'
            ],[
                'secretaria.required' => 'O campo de Secretaria é obrigatório',
            ]);
            $sec = decrypt($request->secretaria);
        }
        else if($user->level == Status::ADMINISTRADOR){
            $sec = $user->sec_id;
        }
        else{
            return abort(403, 'Você não é um Administrador');
        }
        $this->validate($request, [
            'name' => 'required',
            'setor' => 'required',
            'email' => 'required|email',
            'matricula' => 'required',
        ],[
            'name.required' => 'O Nome do usuário é obrigatório',
            'setor.required' => 'O campo do setor é obrigatório',
            'email.required' => 'O email do usuário é obrigatório',
            'email.email' => 'Formato de email inválido',
            'matricula.required' => 'O campo de matrícula é obrigatório'
        ]);
        try{
            $setorId = decrypt($request->setor);
        }
        catch(\Exception $e){
            return redirect()->route('criaFunc')->withErrors('Erro de codificação do setor');
        }
        if(Setores::find($setorId) == null){
            return redirect()->back()->with('error', 'Setor Inválido');
        }
        $request['password'] = bcrypt(123456789);
        $func = User::where('email', $request['email'])->get();
        if($func->count() == 1){
            $emplo = $func->last();
            if ($emplo->status == 1){
                return redirect()->back()->with('error', 'E-mail já cadastrado');
            }
            else{
                $emplo->status = 1;
                if($emplo->update()){
                    return redirect()->route('showEmployees')->with('success','Funcionário adicionado com sucesso');
                }
                else{
                    return redirect()->back()->with('error','Error ao adicionar o Funcionário, caso persista comunique o setor de informática da FUNDETEC.');
                }
            }
        }
        else if(User::where('matricula', $request->matricula)->get()->count() == 1){
            return redirect()->back()->with('error', 'Matricula já cadastrada');
        }
        else {
            $dados = array('sec_id' => $sec,
                            'setor_id' => $setorId, 
                            'name' => $request->name,
                            'email' => $request->email, 
                            'password' => $request->password, 
                            'level' => $request->level, 
                            'matricula' => $request->matricula,
                            'barcode' => $request->barcode ?? null);
            if ( User::create($dados)){
                $logs = array('func' => $user->id, 'sec_id' => $user->sec_id, 'setor_id' => $user->setor_id, 'action' => 'Criou o funcionário '.$request->name);
                UsersActions::create($logs);
                return redirect()->route('showEmployees')->with('success','Funcionario adicionado com sucesso');
            }
            else{
                return redirect()->back()->with('error','Error ao adicionar o Funcionario, caso persista comunique o setor de informatica da FUNDETEC.');
            }
        }
    }
    public function update(Request $request){
        $user = Auth::user();
        if($user->level >= Status::MASTER){
            $validator = Validator::make($request->all(), [
                'secretaria' => ['required']
            ]);
            if ($validator->fails()) {
                return redirect()->back()->with('error', 'Verifique se os dados foram preenchidos corretamente');
            }
            $sec = decrypt($request->secretaria);
        }
        else if($user->level == Status::ADMINISTRADOR){
            $sec = $user->sec_id;
        }
        else{
            return abort(403, 'Você não é um Administrador');
        }
        $this->validate($request, [
            'nameFunc' => 'required',
            'setor' => 'required',
            'matricula' => 'required',
        ],[
            'nameFunc.required' => 'O Nome do usuário é obrigatório',
            'setor.required' => 'O campo do setor é obrigatório',
            'matricula.required' => 'O campo de matrícula é obrigatório'
        ]);
        try{
            $idFunc = decrypt($request->reference);
            $func = User::find($idFunc);
            if($func == null){
                throw new Exception('Func Null');
            }
        }
        catch(Exception $e){
            return Status::FUNCNOTFOUND;
        }
        try{
            $setorId = decrypt($request->setor);
            $setor = Setores::find($setorId);
            if($setor == null){
                throw new Exception('Setor Null');
            }
        }
        catch(Exception $e){
            return Status::SETORNOTFOUND;
        }
        try{
            $secretaria = Secretarias::find($sec);
            if($secretaria == null){
                throw new Exception('Secretaria Null');
            }
        }
        catch(Exception $e){
            return Status::SECNOTFOUND;
        }
        $dados = array('name' => $request->nameFunc, 
                        'sec_id' => $sec, 
                        'setor_id' => $setorId,
                        'matricula' => $request->matricula,
                        'level' => $request->level,
                        'barcode' => $request->barcode ?? null);
        if ($func->update($dados)){
            $logs = array('func' => $user->id, 'sec_id' => $user->sec_id, 'setor_id' => $user->setor_id, 'action' => 'Atualizou o Funcionário '.$func->name);
            UsersActions::create($logs);
            return 1;
        }
        else{
            return 0;
        }
    }
    public function delete($id){
        $user = Auth::user();
        if($user->level >= Status::ADMINISTRADOR) {
            $employee = User::find(decrypt($id));
            $employee->status = 0;
            if ($employee->update())
            {
                $logs = array('func' => $user->id, 'sec_id' => $user->sec_id, 'setor_id' => $user->setor_id, 'action' => 'Desativou o funcionário '.$employee->name);
                UsersActions::create($logs);
                return redirect()->back()->with('error','Deletado com Sucesso !!');
            }
            else{
                return redirect()->back()->with('error','Erro ao Deletar');
            }
        }
        else{
            return abort(403, 'Você não é um Administrador');
        }
    }
    public function funcsSec($idd){
        $user = Auth::user()->level;
        $id = decrypt($idd);
        if($user >= Status::ADMINISTRADOR){
            $funcs = User::where('sec_id', $id)->get();
            $employees = array();
            foreach($funcs as $i => $f){
                $employees[$i]['reference'] = encrypt($f->id);
                $employees[$i]['nome'] = $f->name;
            }
        }
        else{
            abort(403, 'Você não é um Administrador');
        }

        return response()->json($employees);
    }
    public function profileFunc(){
        $func = Auth::user();

        return view('employees.profile', compact('func'));
    }
    public function upProfileFunc(Request $request){
        $user = Auth::user();
        if($user->level >= Status::ADMINISTRADOR){
            $func = User::find($user->id);
            if($request->name != ' ' or $request->nameFunc != null){
                $func->name = $request->nameFunc;
            }
            if($request->matricula != ' ' or $request->matricula != null){
                $func->matricula = $request->matricula;
            }
            if($request->password != ' ' or $request->password != null){
                $request->merge(['password' => bcrypt($request->password)]);
                $func->password = $request->password;
            }
            if($func->update()){
                $logs = array('func' => $user->id, 'sec_id' => $user->sec_id, 'setor_id' => $user->setor_id, 'action' => 'Atualizou o próprio Perfil');
                UsersActions::create($logs);
                return redirect()->back()->with('success', 'Usuário Atualizado com Sucesso !!');
            }
            else{
                return redirect()->back()->with('error', 'Erro ao atualizar usuário, tente novamente. Caso persista, contate  o departamento de iformática da FUNDETEC.');
            }
        }
        else{
            return abort(403, 'Você não é Administrador !!');
        }
        
    }
    public function getdata(){
        $user = Auth::user();
        $dados = array();
        if($user->level >= Status::MASTER){
            $funcionarios = User::where('id', '!=', $user->id)->get();
        }
        elseif($user->level <= Status::ADMINISTRADOR){
            $funcionarios = User::where('id', '!=', $user->id)->where('sec_id', $user->sec_id)->where('level', '<=', Status::ADMINISTRADOR)->get();
        }
        else{
            return abort(403, 'Você não tem permissão suficente');
        }
        foreach($funcionarios as $key => $func){
            $dados[$key]['nome'] = $func->name;
            $dados[$key]['email'] = $func->email;
            $dados[$key]['reference'] = encrypt($func->id);
            $dados[$key]['sector'] = $func->setor->name ?? 'Sem Setor';
            $dados[$key]['sec'] = $func->secretaria->name ?? 'Sem Sec/Aut';
        }
        return DataTables::of($dados)
            ->addColumn('action', function($data) use ($user){
                if($user->level >= Status::ADMINISTRADOR){
                    $button = '<button data-id="'.$data['reference'].'" class="btn btn-danger item del" data-toggle="tooltip" data-placement="top" title="Delete"><i class="fas fa-trash-alt"></i></button>';
                    return $button;
                }
                return '-';
            })->make(true);
    }
    public function searchfunc($idd){
        $user = Auth::user();
        if($user->level >= Status::ADMINISTRADOR){
            try{
                $func = User::find(decrypt($idd));
                $dados = array('nome' => $func->name, 
                                'secretaria' => $func->secretaria->name, 
                                'setor' => $func->setor->name,
                                'matricula' => $func->matricula, 
                                'barcode' => $func->barcode,
                                'email' => $func->email, 
                                'level' => $func->level);
                return response()->json($dados);
            }
            catch(Exception $e){
                return abort(500);
            }
        }
        else{
            return abort(403, 'Você não tem permissão suficente');
        }
    }
}
