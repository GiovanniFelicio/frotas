@extends('layouts.default')
@section('content')
<style>
    .modal{
        background-color: rgba(0, 0, 0, 0.5);
    }
</style>
<script src="{{asset('js/show-hide-password.js')}}"></script>
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
    <div class="row">
        <div class="col-md-12">
            <h2 class="display-4 text-center">Registros do Diário de Bordo</h2>
        </div>
    </div>
    <br><br>
    <div class="row">
        <div class="col-lg-12">
			<div class="bgc-white bd bdrs-3 p-20 mB-20">
                <table style="width: 100%" id="myTable" class="bordo tableStyleGio datatablesStyle display nowrap">
                    <thead>
                    <tr>
                        <th class="text-left">Saída</th>
                        <th class="text-left">Funcionário</th>
                        <th class="text-left">Origem</th>
                        <th class="text-left">Chegada</th>
                        <th class="text-left">Opções</th>
                    </tr>
                    </thead>
                </table>
			</div>
        </div>
    </div>
    <div class="modal fade" id="finalizar" tabindex="-1" role="dialog" data-backdrop="false">
        <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Finalizar Bordo</h5>
                    <button type="button" class="close" data-dismiss="modal">
                        <span>X</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form method="POST" id="finalizarbordo" autocomplete="off">
                        @csrf
                        <input hidden id="reference" name="reference">
                        <div class="form-group row">
                            <div class="col-lg-12">
                                <label for="placa" class="col-sm-2 col-form-label">MOTIVO:</label>
                                <div class="input-group mb-3 col-sm-12">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text" id="basic-addon1"><i class="fas fa-pager"></i></span>
                                    </div>
                                    <input id="motivo" placeholder="Qual o motivo ?" autocomplete="off" class="form-control" name="motivo">
                                </div>
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-lg-12">
                                <label for="placa" class="col-sm-2 col-form-label">SENHA:</label>
                                <div class="input-group mb-3 col-sm-12">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text" id="basic-addon1"><i class="fas fa-pager"></i></span>
                                    </div>
                                    <input type="password" id="senha" autocomplete="new-password" placeholder="Sua Senha" class="form-control" name="senha">
                                </div>
                            </div>
                        </div>
                        <div class="text-center">
                            <button type="submit" class="btn btn-primary">Finalizar</button>
                        </div>
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
                        <h1 class="text-success align-items-center">Sucesso !!</h1>
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
        
            $(function(){
                $('#senha').showHidePassword();
            });
            let idEncrypt;
            $('.bordo').on('click', '.finalizar', function () {
                idEncrypt = $(this).data('id');
                $('#reference').val(idEncrypt);
                $('#finalizar').modal('show');
            });
            $('#finalizarbordo').submit(function(){
                var dados = $( this ).serialize();
                $.ajax({
                    type: "POST",
                    url: "{{route('finalizar')}}",
                    data: dados,
                    success: function(data){
                        if (data == 1){
                            $('#finalizar').modal('hide');
                            $('#sucesso').modal('show');
                            $('#myTable').DataTable().ajax.reload();
                        }
                        else if(data == 0){
                            $('#finalizar').modal('hide');
                            $('#erromsg').text('Falha na validação');
                            $('#erro').modal('show');
                        }
                        else if(data == 100){
                            $('#finalizar').modal('hide');
                            $('#erromsg').text('Bordo Inválido');
                            $('#erro').modal('show');
                        }
                        else if(data == 500){
                            $('#finalizar').modal('hide');
                            $('#erromsg').text('Erro ao Finalizar');
                            $('#erro').modal('show');
                        }
                        else if(data == 403){
                            $('#finalizar').modal('hide');
                            $('#erromsg').text('Não autorizado');
                            $('#erro').modal('show');
                        }
                    }
                });

                return false;
            });
            
            $('#myTable').DataTable({
				"responsive": true,
                "processing": true,
                "serverSide": true,
                "ajax": '{{route('getdataRelatorio')}}',
                "columns":[
                    {"data": 'dtSai'},
                    {"data": 'nome'},
                    {"data": 'origem'},
                    {"data": 'dtCheg'},
                    {"data": 'action'}
                ],
                "lengthMenu": [[5, 10, 25, 100, -1], [5, 10, 25, 100, "All"]],
                "scrollX": true,
                "scrollY": "500px",
                "scrollCollapse": true,
            });
            $('<button class="btn btn-secondary" id="refresh"><i class="fas fa-redo"></i></button>').appendTo('div.dataTables_length');
            $('#refresh').css('margin-left', '10px');
            $('#refresh').on('click', function(){
                $('#myTable').DataTable().ajax.reload();
            });
        });
    </script>
@endsection

