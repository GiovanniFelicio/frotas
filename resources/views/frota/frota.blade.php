@extends('layouts.default')
@section('content')
<style>
    .modal{
        background-color: rgba(0, 0, 0, 0.5);
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
    <script>
        $(".alert").fadeTo(3200, 800).slideUp(1000, function(){
            $(".alert").slideUp(500);
        });
    </script>
    <div class="col-lg-12">
        <div class="au-card chart-percent-card">
            <div class="au-card-inner">
                <div class="login-logo">
                    <h1>Controle de Frota</h1>
                </div>
                <div class="login-form">
                    <form method="POST" id="searchvehicle">
                        @csrf
                        <div class="form-group row">
                            <div class="col-sm-6">
                                <label for="codigo" class="col-sm-6 col-form-label">CÓDIGO DO VEÍCULO:</label>
                                <div class="input-group mb-3 col-sm-12">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text" id="basic-addon1"><i class="fas fa-car"></i></span>
                                    </div>
                                    <input id="codigo" class="form-control" type="text" required name="codigo" autofocus>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <label for="codigo" class="col-sm-6 col-form-label">DADOS DO VEÍCULO:</label>
                                <div class="input-group mb-3 col-sm-12">
                                    <table class="table table-bordered">
                                          <tr>
                                            <td><b>NOME</b></td>
                                            <td id="nome"></td>
                                          </tr>
                                          <tr>
                                            <td><b>PLACA</b></td>
                                            <td id="placa"></td>
                                          </tr>
                                          <tr>
                                            <td><b>SECRETARIA</b></td>
                                            <td id="secretaria"></td>
                                          </tr>
                                          <tr>
                                            <td><b>SITUAÇÃO</b></td>
                                            <td id="situacao"></td>
                                          </tr>
                                      </table>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="login-form func">
                    <form method="POST" id="searchdriver">
                        @csrf
                        <div class="form-group row">
                            <div class="col-sm-6">
                            <label for="nome" class="col-sm-6 col-form-label">CÓDIGO DO MOTORISTA:</label>
                                <div class="input-group mb-3 col-sm-12">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text" id="basic-addon1"><i class="fas fa-user"></i></span>
                                    </div>
                                    <input id="motorista" class="form-control" type="text" required name="codigo">
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <label for="codigo" class="col-sm-6 col-form-label">DADOS DO MOTORISTA:</label>
                                <div class="input-group mb-3 col-sm-12">
                                    <table class="table table-bordered">
                                          <tr>
                                            <td><b>NOME</b></td>
                                            <td id="nomeFunc"></td>
                                          </tr>
                                          <tr>
                                            <td><b>MATRÍCULA</b></td>
                                            <td id="matricula"></td>
                                          </tr>
                                          <tr>
                                            <td><b>SECRETARIA</b></td>
                                            <td id="secretariaFunc"></td>
                                          </tr>
                                          <tr>
                                            <td><b>EMAIL</b></td>
                                            <td id="email"></td>
                                          </tr>
                                          <tr>
                                            <td><b>STATUS</b></td>
                                            <td id="status"></td>
                                          </tr>
                                      </table>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="login-form text-center">
                    <form method="POST" id="verify">
                        @csrf
                        <input id="referencecar" name="car" hidden>
                        <input id="referencedriver" name="driver" hidden>
                        <button class="btn btn-info" type="submit"><i class="fas fa-send"></i>&nbsp;ENVIAR</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="sucesso" tabindex="-1" role="dialog" data-backdrop="false">
        <div class="modal-dialog modal-dialog-centered modal-md" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"></h5>
                    <button type="button" class="close" data-dismiss="modal">
                        <span>X</span>
                    </button>
                </div>
                <div class="modal-body row">
                    <div class="col align-self-center">
                        <img src="{{asset('images/sucesso.png')}}" alt="Sucesso">
                    </div>
                    <div class="col align-self-center">
                        <h1 class="text-success align-items-center" id="successmsg"></h1>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="erro" tabindex="-1" role="dialog" data-backdrop="false">
        <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"></h5>
                    <button type="button" class="close" data-dismiss="modal">
                        <span>X</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="col align-self-center">
                        <img src="{{asset('images/error.png')}}" alt="Erro">
                    </div>
                    <div class="col align-self-center">
                        <h1 class="text-danger align-items-center" id="erromsg"></h1>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script>
        $(document).ready(function () {
            
            $('#searchvehicle').submit(function(){
                var dados = $(this).serialize();
                let car = $('#codigo').val();
                $.ajax({
                    type: "POST",
                    url: "{{route('searchvehicle')}}",
                    data: dados,
                    success: function(data){
                        if (data == 0){
                            $('#nome').text('Erro de Validação');
                            $('#matricula').text('-');
                            $('#secretaria').text('-');
                            $('#situacao').text('-');
                            $('#referencecar').val(car);
                        }
                        else if(data == 2){
                            $('#nome').text('Não encontrado');
                            $('#placa').text('-');
                            $('#secretaria').text('-');
                            $('#situacao').text('-');
                            $('#referencecar').val(car);
                        }
                        else if(data == 3){
                            $('#nome').text('Erro de permissão');
                            $('#placa').text('-');
                            $('#secretaria').text('-');
                            $('#situacao').text('-');
                            $('#referencecar').val(car);
                        }
                        else{
                            $('#nome').text(data['nome']);
                            $('#placa').text(data['placa']);
                            $('#secretaria').text(data['secretaria']);
                            $('#situacao').text(data['situacao']);
                            $('#referencecar').val(data['reference']);
                            $('#motorista').focus();
                        }
                    }
                });

                return false;
            });
            $('#searchdriver').submit(function(){
                var dados = $(this).serialize();
                let driver = $('#motorista').val();
                $.ajax({
                    type: "POST",
                    url: "{{route('searchdriver')}}",
                    data: dados,
                    success: function(data){
                        if (data == 0){
                            $('#nomeFunc').text('Erro de Validação');
                            $('#matricula').text('-');
                            $('#secretariaFunc').text('-');
                            $('#status').text('-');
                            $('#email').text('-');
                            $('#referencedriver').val(driver);
                        }
                        else if(data == 2){
                            $('#nomeFunc').text('Não encontrado');
                            $('#matricula').text('-');
                            $('#secretariaFunc').text('-');
                            $('#status').text('-');
                            $('#email').text('-');
                            $('#referencedriver').val(driver);
                        }
                        else if(data == 3){
                            $('#nomeFunc').text('Erro de permissão');
                            $('#matricula').text('-');
                            $('#secretariaFunc').text('-');
                            $('#status').text('-');
                            $('#email').text('-');
                            $('#referencedriver').val(driver);
                        }
                        else{
                            $('#nomeFunc').text(data['nome']);
                            $('#matricula').text(data['matricula']);
                            $('#secretariaFunc').text(data['secretaria']);
                            $('#email').text(data['email']);
                            $('#status').text(data['status']);
                            $('#referencedriver').val(data['reference']);
                        }
                    }
                });
                return false;
            });
            $('#verify').submit(function(){
                var dados = $(this).serialize();
                $.ajax({
                    type: "POST",
                    url: "{{route('verifyauth')}}",
                    data: dados,
                    success: function(data){
                        if (data[0] == 0){
                            $('#erromsg').text(data[1]);
                            $('#erro').modal('show');
                        }
                        else if (data[0] == 1){
                            $('#successmsg').text(data[1]);
                            $('#sucesso').modal('show');
                        }
                    }
                });
                return false;
            });
        });
    </script>
@endsection

