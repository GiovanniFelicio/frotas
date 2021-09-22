@extends('layouts.default')
@section('content')
    <style>
        p{
            padding-left: 50px;
            padding-top: 20px;
            padding-bottom: 30px;
        }
        .modal{
            background-color: rgba(0, 0, 0, 0.5);
        }
        .section__content--p30 {
            padding: 0 0px;
        }
    </style>
    @if(session('success'))
        <div class="alert alert-success">
            {{session('success')}}
        </div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger">
            {{session('error')}}
        </div>
    @endif
    @if(session('warning'))
        <div class="alert alert-warning">
            {{session('error')}}
        </div>
    @endif
    @if($user->driver == 1)
        <div class="row">
            <div class="col-md-12 text-right">
                <button id="button"  data-toggle="modal" data-target="#myModal" class="openModal au-btn au-btn-icon au-btn--blue">
                    <i class="zmdi zmdi-plus"></i>Nova Solicitação</button>
            </div>
        </div>
    @endif    
    <br>
    <div class="row">
        <div class="col-md-12">
            <h2 class="text-dark display-4 text-center">Suas solicitações</h2>
        </div>
    </div>
    <br><br>
    <div class="row">
        <div class="col-xl-12">
            <div class="au-card chart-percent-card">
                <div class="au-card-inner">
                    <div class="row">
                        <div class="col">
                            <form action="{{route('filterSolicit')}}" method="post">
                                @csrf
                                <label>Data</label>
                                <div class="input-group md-form form-sm form-2 pl-0">
                                    <input class="form-control my-0 py-1 red-border" type="date" id="date" required name="date"><span></span>
                                    <div class="input-group-append">
                                        <span class="input-group-text red lighten-3" id="basic-text1"><button class="btn btn-success" type="submit">Filtrar</button></span>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                    <br>
                    
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-12">
            <br><br>
            <div>
                <table style="width: 100%" id="myTable" class="tableStyleGio datatablesStyle display nowrap solict">
                    <thead>
                    <tr>
                        <th>Número</th>
                        <th>Nome</th>
                        @if(Auth::user()->level >= 4)
                            <th>Sec/Aut</th>
                        @endif
                        <th>Tipo</th>
                        <th>Destino</th>
                        <th>Solicitação</th>
                        <th></th>
                    </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div>
    <!-- Modal -->
    @switch($user)
        @case($user->wait == 1)
            <div class="solic modal fade" id="myModal" tabindex="-1" role="dialog" data-backdrop="false">
                <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title"> DELETAR </h5>
                            <button type="button" class="close" data-dismiss="modal">
                                <span>X</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <p>Aguarde 30 (trinta) minutos para fazer uma nova solicitação</p>
                        </div>
                    </div>
                </div>
            </div>
        @break
        @case($user->wait == 0)
            <div class="solic modal fade" id="myModal" tabindex="-1" role="dialog" data-backdrop="false">
                <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title"> DELETAR </h5>
                            <button type="button" class="close" data-dismiss="modal">
                                <span>X</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <form method="post"  action="{{route('solicCreate')}}">
                                @csrf
                                <select id="tipo" name="tipo" class="au-input au-input--full">
                                    <option selected disabled>Selecione:</option>
                                    <option value="2">Agendar</option>
                                    <option value="0">Dentro do Paço</option>
                                    <option value="1">Sair agora</option>
                                </select>
                                <hr>
                                <p id="paco">Solicitação para dirigir dentro do paço.</p>
                                <div id="campos">
                                    <label class="agend">Agendar para:</label>
                                    <input class="au-input au-input--full agend" name="date" type="datetime-local">
                                    <label>Informe a Origem</label>
                                    <input class="au-input au-input--full now" name="origem" type="text">
                                    <label>Informe o destino</label>
                                    <input class="au-input au-input--full now" name="destino" type="text">
                                    <label>Informe qual a necessidade de sua saida/Demanda</label>
                                    <input class="au-input au-input--full now" name="mensagem" type="text">
                                    <hr>
                                </div>
                                <div id="loadButton">
                                    <button class="btn btn-danger" data-dismiss="modal">Cancelar</button>
                                    <button id="sumbitSolic" type="submit" class="btn btn-primary">Solicitar</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        @break
        @default
            <div class="solic modal fade" id="myModal" tabindex="-1" role="dialog" data-backdrop="false">
                <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title"> DELETAR </h5>
                            <button type="button" class="close" data-dismiss="modal">
                                <span>X</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <p>Error!!</p>
                        </div>
                    </div>
                </div>
            </div>
    @endswitch

    <script>
        // <select id="select-tools"></select>
        $(".alert").fadeTo(3200, 800).slideUp(1000, function(){
            $(".alert").slideUp(500);
        });
        $(document).ready(function(){

            $('#myTable').DataTable({
                "responsive": true,
                "processing": true,
                "serverSide": true,
                "ajax": '{{url("/solicitacoes/gettest")}}',
                "columns":[
                    {"data": 'id'},
                    {"data": 'nameFunc'},
                    @if(Auth::user()->level >= 4)
                        {"data": 'secretaria'},
                    @endif
                    {"data": 'tipo'},
                    {"data": 'destino'},
                    {"data": 'mensagem'},
                    {"data": 'action'}
                ],
                "scrollX": true,
                "scrollY": "500px",
                "scrollCollapse": true
            });

            $('#paco').hide();
            $('.agend').hide();
            $('#campos').hide();
            $('#tipo').change(function () {
                let value = $('#tipo').val();
                if (value == 0){
                    $('#campos').hide();
                    $('.agend').hide();
                    $('#paco').show();
                }
               else if (value == 1){
                    $('#campos').show();
                    $('.agend').hide();
                    $('#paco').hide();
                }
                else if(value == 2){
                    $('#campos').show();
                    $('.agend').show();
                    $('#paco').hide();
                }
                else{
                    $('#campos').hide();
                    $('.agend').hide();
                    $('#paco').hide();
                }
            });
            $('#irregSim').click(function () {
                document.getElementById('irregularidade').innerHTML = '<label>Qual(Quais) é(são) a(s) irreguralidade(s) apresentada ?</label>' +
                    '<input type="text" class="au-input au-input--full" name="irreguSai">' +
                    '';
            });
            $('#irregNao').click(function () {
                document.getElementById('irregularidade').innerHTML = ' ';
            });

            
        });
    </script>
@endsection

