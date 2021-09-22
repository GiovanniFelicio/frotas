<?php

namespace App\Http\Controllers;

use App\AuthFuncVehicle;
use App\GastosVeiculos;
use App\Secretarias;
use App\Setores;
use App\Status;
use App\User;
use App\UsersActions;
use App\Veiculos;
use DateTime;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\DataTables;
use Maatwebsite\Excel\Facades\Excel;

class VeiculosController extends Controller
{
    public function show(){
        $user = Auth::user();
        if($user->level >= Status::ADMINISTRADOR){
            $secretarias = Secretarias::all();
            return view('veiculos.veiculos', compact('secretarias'));
        }
        elseif($user->level >= Status::USUARIO){
            $secretarias = Secretarias::all();
            return view('veiculos.veiculosNorm');
        }
        else{
            return abort(403, 'Você não tem permissao suficente');
        }
    }
    public function anexarVeiculo(){

        $secretarias = Secretarias::all();
        return view('veiculos.anexar', compact('secretarias'));
    }

    public function create(Request $request){
        $user = Auth::user();
        if($user->level >= Status::ADMINISTRADOR){
            $secretaria = Secretarias::find($user->sec_id);
            
        }
        elseif($user->level >= Status::MASTER){
            $this->validate($request, [
                'secretaria' => 'required'
            ],[
                'secretaria.required' => 'Campo de secretaria é obrigatório'
            ]);
            try{
                $secretariaId = decrypt($request->secretaria);
            }
            catch(\Exception $e){
                return redirect()->route('anexarVeiculo')->withErrors('Codificação inválida da secretaria');
            }
            $secretaria = Secretarias::find($secretariaId);
        }
        else{
            return abort(403, 'Você não tem permissão !!');
        }
        $this->validate($request, [
            'nomeVeiculo' => 'required',
            'placa' => 'required',
        ],[
            'nomeVeiculo.required' => 'O Campo do nome do veículo é obrigatório',
            'placa.required' => 'Placa do veículo é obrigatória'
        ]);
        if ($secretaria == null){
            return redirect()->route('anexarVeiculo')->withErrors('Essa Sec/aut não existe !!.');
        }
        if(Veiculos::where('placa', $request->placa)->where('status', '!=', 0)->count() == 1){
            return redirect()->route('anexarVeiculo')->withErrors('Esse Veículo já existe !!.');
        }
        $veiculo = Veiculos::where('placa', $request->placa)->where('status', 0)->get()->last();
        if ($veiculo != null){
            $veiculo->status = 1;
            if($veiculo->update()){
                $logs = array('func' => $user->id, 'sec_id' => $user->sec_id, 'setor_id' => $user->setor_id, 'action' => 'Criou o Veiculo de Placa '. $veiculo->placa);
                UsersActions::create($logs);
                return redirect()->route('VeiculosShow')->with('success', 'Sucesso ao Anexar Veículo');
            }
        }
        else{
            $dados = array('name' => $request->nomeVeiculo, 'placa' => $request->placa, 'sec_id' => $secretaria->id);
            if(Veiculos::create($dados)){
                $logs = array('func' => $user->id, 'sec_id' => $user->sec_id, 'setor_id' => $user->setor_id, 'action' => 'Criou o Veiculo de Placa '. $request->placa);
                UsersActions::create($logs);
                return redirect()->route('VeiculosShow')->with('success', 'Sucesso ao Anexar Veículo');
            }
        }
        return redirect()->back()->with('error', 'Falha ao anexar veículo...');
    }

    public function update(Request $request){
        $user = Auth::user();
        if($user->level < Status::ADMINISTRADOR){
            return abort(403, 'Sem permissão');
        }
        $this->validate($request, [
            'nomevehi' => 'required',
            'placa' => 'required',
            'status' => 'required',
        ],[
            'nomevehi.required' => 'O Campo do nome do veículo é obrigatório',
            'placa.required' => 'Placa do veículo é obrigatória',
            'status.required' => 'Status do veículo é obrigatório'
        ]);
        try{
            $veiculo = Veiculos::find(decrypt($request->reference));
        }
        catch(Exception $e){
            return Status::VEHINOTFOUND;
        }
        try{
            $secretaria = Secretarias::find(decrypt($request->secretaria));
        }
        catch(Exception $e){
            return Status::SECNOTFOUND;
        }
        $dados = array('name' => $request->nomevehi,
                        'placa' => $request->placa,
                        'sec_id' => (($user->level == Status::MASTER)?$secretaria->id:$user->sec_id),
                        'status' => $request->status);
        if($veiculo->update($dados)){
            return 1;
        }
        else{
            return 0;
        }
    }

    public function view($idd){
        $user = Auth::user();
        $veiculo = Veiculos::find(decrypt($idd));
        if($user->level >= 2){
            return view('veiculos.view', compact('veiculo'));
        }
        else{
            return abort(403, 'Erro de permissão !!');
        }
    }
    public function delete($id){
        $user = Auth::user();
        if ($user->level < 3 ){
            abort(403, 'Você não é um Administrador');
        }
        $veiculo = Veiculos::find(decrypt($id));
        $veiculo->status = 0;
        if ($veiculo->update())
        {
            $logs = array('func' => $user->id, 'sec_id' => $user->sec_id, 'setor_id' => $user->setor_id, 'action' => 'Deletou o veiculo de placa '. $veiculo->placa);
            UsersActions::create($logs);
            return redirect()->route('VeiculosShow')->with('success', 'Deletado com Sucesso !!');
        }
        else{
            return redirect()->back()->with('error','Erro ao Deletar');
        }
    }
    public function getdata(){
        $user = Auth::user();
        $dados = array();
        if ($user->level >= Status::MASTER){
            $veiculos = Veiculos::where('status', '!=', 0)->get();
            foreach($veiculos as $key => $veiculo){
                $dados[$key]['secretaria'] = ($veiculo->scretaria)? $veiculo->secretaria->name:'Sem Secretaria/Autarquia';
            }
        }
        elseif ($user->level <= Status::ADMINISTRADOR){
            $veiculos = Veiculos::where('sec_id',$user->sec_id)->where('status', '!=', 0)->get();
        }
        else{
            return abort(403, 'Você não tem permissão suficiente');
        }
        
        foreach($veiculos as $key => $veiculo){
            $dados[$key]['reference'] = encrypt($veiculo->id); 
            switch($veiculo->status){
                case 0:
                    $dados[$key]['status'] = 'Desativado';
                break;
                case 1:
                    $dados[$key]['status'] = 'Não';
                break;
                case 2:
                    $dados[$key]['status'] = 'Sim';
                break;
                default:
                    $dados[$key]['status'] = 'Error !!';
                break;
            }
            $dados[$key]['nome'] = $veiculo->name;
            $dados[$key]['placa'] = $veiculo->placa;  
        }
        return DataTables::of($dados)
            ->addColumn('action', function($data) use ($user){
                if($user->level >= Status::ADMINISTRADOR){
                    $button = '<a href="'.route('viewVehi', $data['reference']).'"><button type="submit" class="btn btn-primary item" data-toggle="tooltip" data-placement="top" title="View"><i class="fas fa-eye"></i></button></a>&nbsp;&nbsp;';
                    $button .= '<button data-toggle="modal" data-target="#siteModal" data-id="'.$data['reference'].'" class="btn btn-danger item del" data-toggle="tooltip" data-placement="top" title="Delete"><i class="fas fa-trash-alt"></i></button>';
                }
                else{
                    $button = '';
                }
                return $button;
            })->make(true);
    }

    public function veiculo($id){
        $veiculo = Veiculos::find(decrypt($id))->km;
        return response()->json($veiculo);
    }
    public function lancamentogastos($id){
        $user = Auth::user();
        if($user->level >= Status::ADMINISTRADOR){
            return view('veiculos.gastos', compact('id'));
        }
        else{
            return abort(403, 'Sem Permissão');
        }
        
    }
    public function lancargastos(Request $request){
        $user = Auth::user();
        $validator = Validator::make($request->all(), [
            'reference' => ['required'],
            'produto' => ['required'],
            'money' => ['required'],
            'date' => ['required']
        ]);
        if ($validator->fails()) {
            return redirect()->back()->with('error', 'Não deixe campos em branco');
        }
        $veiculo = Veiculos::find(decrypt($request->reference));
        if($veiculo == null){
            return redirect()->back()->with('error', 'Veículo Inválido !!');
        }
        try {
            $date = new DateTime($request->date);
        }
        catch (\Exception $e) {
            return redirect()->back()->with('error', $e);
        }
        $request['vehicle_id'] = $veiculo->id;
        $request['item'] = $request->produto;
        $request['valor'] = $request->money;
        $gasto = array('sec_id' => $user->sec_id, 'vehicle_id' => $request->vehicle_id, 'item' => $request->item, 'valor' => $request->valor, 'data' => $date->format('Y-m-d'));
        if(GastosVeiculos::create($gasto)){
            $logs = array('func' => $user->id, 'sec_id' => $user->sec_id, 'setor_id' => $user->setor_id, 'action' => 'Fez lançamento de gasto do veículo '.$veiculo->placa);
            UsersActions::create($logs);
            return redirect()->route('viewVehi', $request->reference)->with('success', 'Sucesso !! ');
        }
        else{
            return redirect()->back()->with('error', 'Não foi possível lançar o Gasto !! ');
        }
    }
    public function delgastos($id){
        $user = Auth::user();
        $gasto = GastosVeiculos::find(decrypt($id));
        $veiculo = Veiculos::find($gasto->vehicle_id);
        $gasto->status = 0;
        if($gasto->update()){
            $logs = array('func' => $user->id, 'sec_id' => $user->sec_id, 'setor_id' => $user->setor_id, 'action' => 'Deletou lançamento de gasto do veículo '.$veiculo->placa);
            UsersActions::create($logs);
            return redirect()->route('view', $id)->with('success', 'Sucesso !! ');
        }
        else{
            return redirect()->back()->with('error', 'Não foi possível lançar o Gasto !! ');
        }
    }
    public function gastos($id){
        $user = Auth::user();
        if($user->level >= Status::ADMINISTRADOR){
            $gastos = GastosVeiculos::where('vehicle_id', decrypt($id))->where('status',1)->get();
            return DataTables::of($gastos)
            ->addColumn('action', function($data){
                $id = encrypt($data->id);
                if(Auth::user()->level >= Status::ADMINISTRADOR){
                    $button = '<button data-toggle="modal" data-target="#siteModal" data-id="'.$id.'" class="item btn btn-danger del" data-toggle="tooltip" data-placement="top" title="Delete"><i class="fas fa-trash-alt"></i></button>';
                    return $button;
                }
                else{
                    $button = '<b class="text-danger">Sem Opções !!</b>';
                    return $button;
                }
            })->make(true);
        }
        else{
            return abort(403, 'Sem Permissão');
        }
    }
    public function vehiclesSec($idd){
        $user = Auth::user()->level;
        $id = decrypt($idd);
        if($user >= Status::ADMINISTRADOR){
            $dados = array();
            $vehicles = Secretarias::find($id)->veiculos;
            foreach($vehicles as $key => $veiculo){
                $dados[$key]['reference'] = encrypt($veiculo->id);
                $dados[$key]['nome'] = $veiculo->name;
            }
        }
        else{
            abort(403, 'Você não é um Administrador');
        }

        return response()->json($dados);
    }

   
    public function getdataAuthsFunc($idVeiculoCrypt){
        $user = Auth::user();
        if($user->level >= 4){
            $funcionarios = User::where('status','!=', 0)->get();
            $count = count($funcionarios);
            for ($i = 0; $i < $count; $i++) {
                if ($funcionarios[$i]->setor_id == 0 or $funcionarios[$i]->setor_id == null) {
                    $funcionarios[$i]->setor = 'Sem Setor';
                } else {
                    $funcionarios[$i]->setor = Setores::find($funcionarios[$i]->setor_id)->name;
                }
                if ($funcionarios[$i]->sec_id == 0 or $funcionarios[$i]->sec_id == null) {
                    $funcionarios[$i]->secretaria = 'Sem Sec/Aut';
                } else {
                    $funcionarios[$i]->secretaria = Secretarias::find($funcionarios[$i]->sec_id)->name;
                }
                $funcionarios[$i]['idd'] = encrypt($funcionarios[$i]->id);
            }
        }
        elseif($user->level <= 3){
            $funcionarios = User::where('sec_id', $user->sec_id)->where('status','!=', 0)->get();
            $count = count($funcionarios);
            for ($i = 0; $i < $count; $i++) {
                if ($funcionarios[$i]->setor_id == 0 or $funcionarios[$i]->setor_id == null) {
                    $funcionarios[$i]->setor = 'Sem Setor';
                } else {
                    $funcionarios[$i]->setor = Setores::find($funcionarios[$i]->setor_id)->name;
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
            return abort(403, 'Você não tem permissao suficente');
        }
        return DataTables::of($funcionarios)
            ->addColumn('action', function($data) use ($idVeiculoCrypt){
                if(Auth::user()->level >= Status::ADMINISTRADOR){
                    $id = encrypt($data->id);
                    if(AuthFuncVehicle::where('func_id', $data->id)->where('vehicle_id', decrypt($idVeiculoCrypt))->where('status', 1)->get()->count() == 1){
                        $button = '<label class="switch"><input data-id="'.$id.'" class="toggleAuth" type="checkbox" checked><span class="slider round"></span></label>';
                    }
                    else{
                        $button = '<label class="switch"><input data-id="'.$id.'" class="toggleAuth" type="checkbox"><span class="slider round"></span></label>';
                    }
                    return $button;
                }
                else{
                    $button = '<b class="text-danger">Sem Opções !!</b>';
                    return $button;
                }
            })->make(true);
    }
    public function auth(Request $request){
        $user = Auth::user();
        if($user->level >= Status::ADMINISTRADOR){
            $validator = Validator::make($request->all(), [
                'func' => ['required', 'string'],
                'veiculo' => ['required', 'string'],
            ]);
            if ($validator->fails()) {
                return 'Dados Inválidos !!';
            }
            else{
                try{
                    $employee = decrypt($request->func);
                    $veiculo = decrypt($request->veiculo);
                }
                catch(\Exception $e){
                    return 'Valores Inválidos';
                }
                try{
                   $vehicle = Veiculos::find($veiculo); 
                }
                catch(\Exception $f){
                    return 'Esse Veículo não existe';
                }
                if(AuthFuncVehicle::where('vehicle_id', $veiculo)->where('func_id', $employee)->where('status', 1)->get()->count() == 1){
                    return 'Este funcionário já foi autorizado';
                }
                elseif(AuthFuncVehicle::where('vehicle_id', $veiculo)->where('func_id', $employee)->where('status', 0)->get()->count() == 1){
                    $up = AuthFuncVehicle::where('vehicle_id', $veiculo)->where('func_id', $employee)->where('status', 0)->get()->last();
                    $up->status = 1;
                    if($up->update()){
                        return 1;
                    }
                    else{
                        return 0;
                    }
                }
                else{
                    $dados = array('sec_id' => $vehicle->sec_id, 'func_id' => $employee, 'vehicle_id' => $veiculo);
                    if(AuthFuncVehicle::create($dados)){
                        return 1;
                    }
                    else{
                        return 0;
                    }
                }
            }
        }
        else{
            return abort(403, "Você não tem permissão suficiente");
        }
    }
    public function disallowance(Request $request){
        $user = Auth::user();
        if($user->level >= Status::ADMINISTRADOR){
            $validator = Validator::make($request->all(), [
                'func' => ['required', 'string'],
                'veiculo' => ['required', 'string'],
            ]);
            if ($validator->fails()) {
                return 'Dados Inválidos !!';
            }
            else{
                try{
                    $employee = decrypt($request->func);
                    $veiculo = decrypt($request->veiculo);
                }
                catch(\Exception $e){
                    return 'Valores Inválidos';
                }
                try{
                   $vehicle = Veiculos::find($veiculo); 
                }
                catch(\Exception $f){
                    return 'Esse Veículo não existe';
                }
                if(AuthFuncVehicle::where('vehicle_id', $veiculo)->where('func_id', $employee)->where('status', 0)->get()->count() == '1'){
                    return 'Este funcionário já foi desautorizado';
                }
                elseif(AuthFuncVehicle::where('vehicle_id', $veiculo)->where('func_id', $employee)->where('status', 1)->get()->count() == 1){
                    $up = AuthFuncVehicle::where('vehicle_id', $veiculo)->where('func_id', $employee)->where('status', 1)->get()->last();
                    $up->status = 0;
                    if($up->update()){
                        return 1;
                    }
                    else{
                        return 0;
                    }
                }
                else{
                    return 'Error!!';
                }
            }
        }
        else{
            return abort(403, "Você não tem permissão suficiente");
        }
    } 

    public function search($id){
        $user = Auth::user();
        if($user->level >= Status::ADMINISTRADOR){
            try{
                $veiculo = Veiculos::find(decrypt($id));
                $dados = array('nome' => $veiculo->name, 
                                'secretaria' => $veiculo->secretaria->name ?? 'Secretaria Inválida',
                                'placa' => $veiculo->placa, 
                                'km' => $veiculo->km, 
                                'status' => $veiculo->status);
                return response()->json($dados);
            }
            catch(Exception $e){
                return abort(500);
            }
        }
        else{
            return abort(403, 'Sem permissão !!!');
        }
    }
    
    /*public function import(Request $request){
        $user = Auth::user();
        if($user->level >= 2){
            ini_set('memory_limit', '4092M');
            $request->validate([
                'import_file' => 'required'
            ]);
            if($request->file('import_file')->extension() != 'xls'){
                return 'Extensões permitidas: xls';
            }
            if($request->file('import_file')->getSize() >= 700000){
                return 'Tamanho máximo permitido é de: 700KB';
            }
            $path = $request->file('import_file')->getRealPath();
            $data = Excel::load($path)->get();
            if($data->count()){
                foreach ($data as $key => $value) {
                    try{
                        $arr[] = ['sec_id' => $user->sec_id, 'name' => $value->nome, 'placa' => $value->placa, 'number' => $value->numero, 'km' => $value->km, 'utilizacao' => $value->utilizacao];
                    }
                    catch(\Exception $e){
                        return 'Error !! Verifique se os nomes das colunas segue o padrão correto';
                    }
                }
                if(!empty($arr)){
                    $quantCreate = 0;
                    $quantNotCreate = 0;
                    for($i = 0; $i < count($arr); $i++){
                        if(Veiculos::where('sec_id', $user->sec_id)->where('placa', $arr[$i]['placa'])->get()->count() == 0){
                            Veiculos::create($arr[$i]);
                            $quantCreate = $quantCreate + 1;
                        }
                        else{
                            $quantNotCreate = $quantNotCreate + 1;
                        }
                    }
                }
            }
    
            return ['ok', $quantCreate, $quantNotCreate];
        }
        else{
            return 'Error!!';
        }
    }*/
}
