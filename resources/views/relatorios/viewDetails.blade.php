@extends('layouts.default')
@section('content')
    <link href="{{asset('vendor/select2/dist/css/select2.min.css')}}" rel="stylesheet" />
    <script src="{{asset('vendor/select2/dist/js/select2.min.js')}}"></script>
    <div class="col-lg-12">
        <div class="au-card chart-percent-card">
            <div class="au-card-inner">
                <div class="row">
                    <div class="col-sm-12 text-center">
                        <h2>Detalhes do Bordo</h2>
                        <br><br>
                        <div class="table-responsive m-b-30">
                            <table class="table table-bordered">
                                <tbody>
                                <tr>
                                    <th>ID</th>
                                    <td>{{$logBook->id}}</td>
                                </tr>
                                <tr>
                                    <th>Veiculo</th>
                                    <td>{{$logBook->veiculo}}</td>
                                </tr>
                                <tr>
                                    <th>Data e Hora da Saida</th>
                                    <td>{{$logBook->dateTimeSai}}</td>
                                </tr>
                                <tr>
                                    <th>Nome do Funcionario</th>
                                    <td>{{$logBook->func}}</td>
                                </tr>
                                <tr>
                                    <th>Setor</th>
                                    <td>{{$logBook->setor}}</td>
                                </tr>
                                <tr>
                                    <th>Secretaria/Autarquia</th>
                                    <td>{{$logBook->sec}}</td>
                                </tr>
                                <tr>
                                    <th>Origem</th>
                                    <td>{{$logBook->origem}}</td>
                                </tr>
                                <tr>
                                    <th>Destino</th>
                                    <td>{{$logBook->destino}}</td>
                                </tr>
                                <tr>
                                    <th>KmInicial</th>
                                    <td>{{$logBook->kmInicial}}</td>
                                </tr>
                                <tr>
                                    <th>Data e Horas da Chegada</th>
                                    <td>{{$logBook->dateTimeCheg}}</td>
                                </tr>
                                <tr>
                                    <th>KmFinal</th>
                                    <td>{{$logBook->kmFinal}}</td>
                                </tr>
                                <tr>
                                    <th>Irregularidades/Observações na Saida</th>
                                    <td>{{$logBook->irreguSai}}</td>
                                </tr>
                                <tr>
                                    <th>Irregularidades/Observações na Chegada</th>
                                    <td>{{$logBook->irreguCheg}}</td>
                                </tr>
                                </tbody>
                            </table>
                        </div>
                        <br>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
