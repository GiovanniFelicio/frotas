<?php

namespace App\Http\Controllers;

use App\GastosVeiculos;
use App\Logbook;
use App\Secretarias;
use App\Setores;
use App\Status;
use App\Tipos;
use App\User;
use App\UsersActions;
use App\Veiculos;
use Carbon\Carbon;
use DateTime;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\DataTables;

class RelatoriosController extends Controller
{

    public function registroLogBook(){
        $user = Auth::user();
        if($user->level >= Status::OPERADOR) {
            return view('relatorios.registroLogBook');
        }
        else{
            return abort(403, 'Você não tem permissão suficente');
        }
    }
    public function getdata(){
        $user = Auth::user();
        if($user->level <= Status::ADMINISTRADOR){
            $logBook = Logbook::where('sec_id', $user->sec_id)->latest()->get();
        }
        elseif($user->level >= Status::MASTER){
            $logBook = Logbook::latest()->get();
        }
        else{
            return abort(403, 'Você não tem permissão suficente');
        }
        $dados = array();
        foreach($logBook as $i => $log){
            $dados[$i]['reference'] = encrypt($log->id);
            $dados[$i]['dtSai'] = $log->dateTimeSai;
            $func = User::find($log->func_id);
            $dados[$i]['nome'] = $func->name ?? 'Error !!';
            $dados[$i]['dtCheg'] = ($log->dateTimeCheg == 'Em Curso') ? 'Em Curso' :$log->dateTimeCheg;
            $dados[$i]['origem'] = $log->origem;
            $dados[$i]['destino'] = $log->destino;
            $dados[$i]['kmI'] = $log->kmInicial;
            $dados[$i]['kmF'] = $log->Final;
            $dados[$i]['status'] = $log->status;
        }
        return DataTables::of($dados)->addColumn('action', function($data){
            $button = '<a href="'.route('viewDetailsRelatorio', $data['reference']).'"><button type="submit" class="btn btn-warning item" data-toggle="tooltip" data-placement="top" title="Detalhes"><i class="fas fa-newspaper"></i></button></a>&nbsp;&nbsp;';
            if($data['status'] != Status::FINALIZADO){
                $button .= '<button data-id="'.$data['reference'].'" class="btn btn-info item finalizar" data-toggle="tooltip" data-placement="top" title="Finish">Finalizar</i></button>&nbsp;&nbsp;';
            }
            return $button;
        })->make(true);
    }

    public function viewDetails($id){
        $user = Auth::user();
        if($user->level >= Status::ADMINISTRADOR){
            $logBook = Logbook::find(decrypt($id));
            $func = User::find($logBook->func_id);
            if ($func == null){
                $logBook->func = 'Error !!';
            }
            else{
                $logBook->func = $func->name;
            }
            $sec = Secretarias::find($logBook->sec_id);
            if($sec == null){
                $logBook->sec = 'Error !!';
            }
            else{
                $logBook->sec = $sec->name;
            }
            $setor = Setores::find($logBook->setor_id);
            if($setor == null){
                $logBook->setor = 'Error !!';
            }
            else{
                $logBook->setor = $setor->name;
            }
            $veiculo = Veiculos::find($logBook->veiculo);
            if ($veiculo == null){
                $logBook->veiculo = 'Veículo Inválido';
            }
            else{
                $logBook->veiculo = $veiculo->nameVei.' '.$veiculo->placa;
            }
            return view('relatorios.viewDetails', compact('logBook'));
        }
        else{
            return abort(403, 'Você não tem permissão suficente');
        }
    }

    public function filtro(){
        $user = Auth::user();
        if ($user->level >= Status::ADMINISTRADOR){
            return view('relatorios.filtro');
        }
        else{
            return abort(403, 'Error de permissão !!');
        }
    }
    public function gerador(Request $request){
        $user = Auth::user();
        if ($user->level >= Status::ADMINISTRADOR){
            $validator = Validator::make($request->all(), [
                'tipo' => ['required'],
                'filtroPor' => ['required']
            ]);
            if ($validator->fails()) {
                return redirect()->back()->with('error', 'Erro na validação dos dados !!');
            }
            if($request->func != null){
                try{
                    $employeeId = decrypt($request->func);
                }
                catch(\Exception $e){
                    return $e;
                }
            }
            $relatorio = array();
            switch ($request->tipo) {
                case Tipos::BORDO:
                    if ($employeeId == 0) {
                        $logbook = Logbook::where('sec_id', $user->sec_id)->get()->toArray();
                        switch ($request->filtroPor) {
                            case Tipos::DAY:
                                $day = Carbon::parse($request->day)->format('Y-m-d');
                                $datafilterI = $day.' '.'00:00:00';
                                $datafilterF = $day.' '.'23:59:59';
                                $relatorio = $this->logbook($employeeId, $user, $datafilterI, $datafilterF);
                                break;
                            case Tipos::MONTH:
                                $month = Carbon::parse($request->month)->format('Y-m');
                                $datafilterI = $month.'-01'.' '.'00:00:00';
                                $datafilterF = $month.'-31'.' '.'23:59:59';
                                $relatorio = $this->logbook($employeeId, $user, $datafilterI, $datafilterF);
                                break;
                            case Tipos::YEAR:
                                $year = Carbon::parse($request->year)->format('Y');
                                $datafilterI = $year.'-01-01'.' '.'00:00:00';
                                $datafilterF = $year.'-12-31'.' '.'23:59:59';
                                $relatorio = $this->logbook($employeeId, $user, $datafilterI, $datafilterF);
                                break;
                            default:
                                return redirect()->back()->with('error', 'Erro validação de dados !!');
                                break;
                        }
                    } else {
                        
                        switch ($request->filtroPor) {
                            case Tipos::DAY:
                                $day = Carbon::parse($request->day)->format('Y-m-d');
                                $datafilterI = $day.' '.'00:00:00';
                                $datafilterF = $day.' '.'23:59:59';
                                $relatorio = $this->logbook($employeeId, $user, $datafilterI, $datafilterF);
                                break;
                            case Tipos::MONTH:
                                $month = Carbon::parse($request->month)->format('Y-m');
                                $datafilterI = $month.'-01'.' '.'00:00:00';
                                $datafilterF = $month.'-31'.' '.'23:59:59';
                                $relatorio = $this->logbook($employeeId, $user, $datafilterI, $datafilterF);
                                break;
                            case Tipos::YEAR:
                                $year = Carbon::parse($request->year)->format('Y');
                                $datafilterI = $year.'-01-01'.' '.'00:00:00';
                                $datafilterF = $year.'-12-31'.' '.'23:59:59';
                                $relatorio = $this->logbook($employeeId, $user, $datafilterI, $datafilterF);
                                break;
                            default:
                                return redirect()->back()->with('error', 'Erro validação de dados !!');
                                break;
                        }
                    }
                    break;
                    /*###############################################################*/
                case Tipos::GASTOVEICULO:
                    try{
                        $vehicleId = decrypt($request->vehi);
                    }
                    catch(\Exception $e){
                        return redirect()->route('filtroRela')->withErrors('Codificação do veículo inválida');
                    }
                    if ($vehicleId == 0) {
                        $gastos = GastosVeiculos::where('sec_id', $user->sec_id)->get();
                        
                        switch ($request->filtroPor) {
                            case Tipos::DAY:
                                $day = Carbon::parse($request->day)->format('Y-m-d');
                                $datafilterI = $day.' '.'00:00:00';
                                $datafilterF = $day.' '.'23:59:59';
                                $relatorio = $this->gastos($vehicleId, $user, $datafilterI, $datafilterF);
                                break;
                            case Tipos::MONTH:
                                $month = Carbon::parse($request->month)->format('Y-m');
                                $datafilterI = $month.'-01'.' '.'00:00:00';
                                $datafilterF = $month.'-31'.' '.'23:59:59';
                                $relatorio = $this->gastos($vehicleId, $user, $datafilterI, $datafilterF);
                                break;
                            case Tipos::YEAR:
                                $year = Carbon::parse($request->year)->format('Y');
                                $datafilterI = $year.'-01-01'.' '.'00:00:00';
                                $datafilterF = $year.'-12-31'.' '.'23:59:59';
                                $relatorio = $this->gastos($vehicleId, $user, $datafilterI, $datafilterF);
                                break;
                            default:
                                return redirect()->back()->with('error', 'Erro validação de filtro de data !!');
                                break;
                        }
                    }
                    else{
                        try{
                            $veiculo = decrypt($request->vehi);
                        }
                        catch(\Exception $e){
                            return redirect()->route('filtroRela')->withErrors('Codificação do veículo inválida');
                        }
                        $gastos = GastosVeiculos::where('sec_id', $user->sec_id)->whereIn('vehicle_id', $veiculo)->get();
                        switch ($request->filtroPor) {
                            case Tipos::DAY:
                                $day = Carbon::parse($request->day)->format('Y-m-d');
                                $datafilterI = $day.' '.'00:00:00';
                                $datafilterF = $day.' '.'23:59:59';
                                $relatorio = $this->gastos($vehicleId, $user, $datafilterI, $datafilterF);
                                break;
                            case Tipos::MONTH:
                                $month = Carbon::parse($request->month)->format('Y-m');
                                $datafilterI = $month.'-01'.' '.'00:00:00';
                                $datafilterF = $month.'-31'.' '.'23:59:59';
                                $relatorio = $this->gastos($vehicleId, $user, $datafilterI, $datafilterF);
                                break;
                            case Tipos::YEAR:
                                $year = Carbon::parse($request->year)->format('Y');
                                $datafilterI = $year.'-01-01'.' '.'00:00:00';
                                $datafilterF = $year.'-12-31'.' '.'23:59:59';
                                $relatorio = $this->gastos($vehicleId, $user, $datafilterI, $datafilterF);
                                break;
                            default:
                                return redirect()->back()->with('error', 'Erro validação de filtro de data !!');
                                break;
                        }
                    }

                    break;
                default:
                    return redirect()->back()->with('error', 'Erro validação dp tipo para o relatório !!');
                    break;
            }
            date_default_timezone_set('America/Bahia');
            $date = new DateTime();
            $data = $date->format('Y-m-d-H:i:s');
            if ($request->tipo == Tipos::BORDO){
                $tipo = 'Bordo';
                foreach($relatorio as $i => $relato){
                    try{
                        $relato->func_id = User::find($relato['func_id'])->name;
                    }
                    catch (\Exception $e){
                        $relato->func_id= 'Error !!';
                    }
                    try{
                        $relato->sec_id = Secretarias::find($relato['sec_id'])->name;
                    }
                    catch (\Exception $e){
                        $relato->sec_id = 'Error !!';
                    }
                    try{
                        $relato->veiculo = Veiculos::find($relato['veiculo'])->placa;
                    }
                    catch (\Exception $e){
                        $relato->veiculo = 'Error !!';
                    }
                }
                $rows = '';
                foreach($relatorio as $relato){
                    $rows .= '
                            <tr>
                              <th>'.$relato->id.'</th>
                              <td>'.$relato->dateTimeSai.'</td>
                              <td>'.$relato->dateTimeChe.'</td>
                              <td>'.$relato->veiculo.'</td>
                              <td>'.$relato->func_id.'</td>
                              <td>'.$relato->sec_id.'</td>
                              <td>'.$relato->origem.'</td>
                              <td>'.$relato->kmInicial.'</td>
                              <td>'.$relato->destino.'</td>
                              <td>'.$relato->kmFinal.'</td>
                              <td>'.$relato->irreguSai.'</td>
                              <td>'.$relato->irreguCheg.'</td>
                            </tr>';
                }
                $html = '<table>';
                $html .=
                    '<thead>
                    <tr>
                      <td>ID</td>
                      <td>Saída</td>
                      <td>Chegada</td>
                      <td>Veículo</td>
                      <td>Servidor</td>
                      <td>Sec/Aut</td>
                      <td>Origem</td>
                      <td>KmInicial</td>
                      <td>Destino</td>
                      <td>KmFinal</td>
                      <td>Obs Saída</td>
                      <td>Obs Chegada</td>
                    </tr>
                </thead>';
                $html .=
                    '<tbody>'.$rows.'</tbody>';
                $html .= '</table>';
            }
            elseif ($request->tipo == Tipos::GASTOVEICULO){
                $tipo = 'GastoVeiculo';
                foreach($relatorio as $key => $relato){
                    try{
                        $relato->vehicle_id = Veiculos::find($relato->vehicle_id)->placa;
                    }
                    catch (\Exception $e){
                        $relato->vehicle_id = 'Error !!';
                    }
                    try{
                        $relato->sec_id = Secretarias::find($relato->sec_id)->name;
                    }
                    catch (\Exception $e){
                        $relato->sec_id = 'Error !!';
                    }
                }
                $rows = '';
                $valorTotal = $this->sumColumn($relatorio, 'valor');
                $totalItens = count($relatorio);
                foreach($relatorio as $relato){
                    $rows .= '
                            <tr>
                              <th>'.$relato->id.'</th>
                              <td>'.$relato->vehicle_id.'</td>
                              <td>'.$relato->sec_id.'</td>
                              <td>'.$relato->item.'</td>
                              <td>'.$relato->valor.'</td>
                              <td>'.Carbon::parse($relato->data)->format('d-m-Y').'</td>
                            </tr>';
                }
                $html = '<table>';
                $html .=
                    '<thead>
                    <tr>
                      <td>ID</td>
                      <td>Veiculo</td>
                      <td>Sec/Aut</td>
                      <td>Item</td>
                      <td>Valor</td>
                      <td>Data</td>
                    </tr>
                </thead>';
                $html .=
                    '<tbody>'.$rows.'</tbody>';
                $html .=
                    '<tr>
                          <td></td>
                          <td></td>
                          <td></td>
                          <td></td>
                          <td>Total de Itens: '.$totalItens.'</td>
                          <td></td>
                    </tr>';
                $html .=
                    '<tr>
                          <td></td>
                          <td></td>
                          <td></td>
                          <td></td>
                          <td>Valor Total: R$'.$valorTotal.'</td>
                          <td></td>
                    </tr>';
                $html .= '</table>';
            }
            header('Content-Transfer-Encoding: binary');
            header("Content-Type: application/octet-stream");
            header("Content-Disposition: attachment; filename=Relatorio".$tipo.$data.".xls");

            echo chr(255).chr(254).iconv("UTF-8", "UTF-16LE//IGNORE", $html);

        }
        else{
            return abort(403, 'Error de permissão !!');
        }
    }
    protected function logbook($employeeId, $user, $datafilterI, $datafilterF){
        if($employeeId == 0){
            $logbook = Logbook::where('sec_id', $user->sec_id)->where('created_at', '>=', $datafilterI)->where('created_at', '<=', $datafilterF)->get();
        }
        else{
            $logbook = Logbook::where('sec_id', $user->sec_id)
                                ->where('func_id', $employeeId)
                                ->where('created_at', '>=', $datafilterI)
                                ->where('created_at', '<=', $datafilterF)->get();
        }
        return $logbook;
    }
    protected function gastos($vehicleId, $user, $datafilterI, $datafilterF){
        if($vehicleId == 0){
            $gastos = GastosVeiculos::where('sec_id', $user->sec_id)
                                    ->where('created_at', '>=', $datafilterI)
                                    ->where('created_at', '<=', $datafilterF)->get();
        }
        else{
            $gastos = GastosVeiculos::where('sec_id', $user->sec_id)
                                    ->where('func_id', $vehicleId)
                                    ->where('created_at', '>=', $datafilterI)
                                    ->where('created_at', '<=', $datafilterF)->get();
        }
        return $gastos;
    }
    protected function sumColumn($array, $column){
        $soma = 0;
        foreach($array as $a){
            $soma = $soma+$a->$column;
        }
        return $soma;
    }
}
