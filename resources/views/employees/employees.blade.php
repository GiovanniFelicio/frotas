@extends('layouts.default')
@section('content')
    <style>
        .modal{
            background-color: rgba(0, 0, 0, 0.5);
        }
        .ametitle{
            position: absolute;
            left: 2%;
            top: 3%;
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
    <div class="row add">
        <div class="col-md-12 text-right">
            @if(Auth::user()->level >= 2)
                <a href="{{route('criaFunc')}}" style="color: white" class="btn btn-info uppercase">
                    <i class="zmdi zmdi-plus"></i>add Funcionario</a>
            @endif
        </div>
    </div>
    <br>
    <div class="row">
        <div class="col-md-12">
            <h1 class="display-4 text-center">Funcionários</h1>
        </div>
    </div>
    <br>
    <div class="row">
        <div class="col-lg-12">
            <br><br>
            <div>
                <table style="width: 100%" id="myTable" class="tableStyleGio datatablesStyle display nowrap func">
                    <thead>
                        <tr>
                            <th>Nome</th>
                            @if($user->level >= App\Status::MASTER)
                                <th>Sec/Aut</th>
                            @endif
                            <th>E-mail</th>
                            <th>Setor</th>
                            @if(App\Status::ADMINISTRADOR)
                                <th></th>
                            @endif
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div>
    <!-- Modal -->
    <div class="modal fade" id="myModal" tabindex="-1" role="dialog" data-backdrop="false">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"> DELETAR </h5>
                    <button type="button" class="close" data-dismiss="modal">
                        <span>X</span>
                    </button>
                </div>
                <div class="modal-body">
                    Deseja deletar este Funcionario mesmo ?
                </div>
                <div class="modal-footer">
                    <input type="hidden" value="" name="idFunc" class="idFunc">
                    <button type="button" class="btn btn-danger" data-dismiss="modal">NAO</button>
                    <button type="button" class="btn btn-primary yes">SIM</button>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="myModalEdit" tabindex="-1" role="dialog" data-backdrop="false">
        <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"> EDITAR </h5>
                    <button type="button" class="close" data-dismiss="modal">
                        <span>X</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form method="POST" action="" id="updateFunc">
                        @csrf
                        <input id="reference" hidden name="reference">
                        <div class="form-row">
                            <div class="form-group col-lg-6">
                                <label>Nome do Funcionário</label>
                                <input class="form-control" id="nameFunc" type="text" name="nameFunc" placeholder="Ex: Lucas Silva" autofocus>
                            </div>
                            <div class="form-group col-lg-6">
                                <label>Código de Barras</label>
                                <input class="form-control" id="barcode" type="text" name="barcode">
                            </div>
                        </div>
                        <div class="form-row">
                            @if($user->level >= App\Status::MASTER)
                                <div class="form-group col-md-6">
                                    <div class="form-group">
                                        <div class="form-group">
                                            <label>Secretaria/Autarquia</label>
                                            <select id="secretaria" name="secretaria" required  class="form-control" onchange="returnSectors()">
                                                <option selected>Selecione a Sec/Aut</option>
                                                @foreach($dados1 as $secretaria)
                                                    <option value="{{$secretaria['reference']}}">{{$secretaria['nome']}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            @endif
                            <div @if($user->level >= App\Status::MASTER) class="col-md-6" @else class="col-md-12" @endif>
                                <label>Setor</label>
                                @if($user->level >= App\Status::MASTER)
                                    <select required id="sector" name="setor" class="setor form-control">
                                        <option selected>Selecione o Setor</option>
                                        <option value="{{encrypt(1)}}">Sem Setor</option>
                                    </select>
                                @elseif($user->level == App\Status::ADMINISTRADOR)
                                    <select required id="setor" name="setor" class="setor form-control">
                                        <option selected>Selecione o Setor</option>
                                        <option value="{{encrypt(1)}}">Sem Setor</option>
                                        @foreach($dados as $setor)
                                            <option value="{{$setor['reference']}}">{{$setor['nome']}}</option>
                                        @endforeach
                                    </select>
                                @endif
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group col-md-5">
                                <label>E-mail</label>
                                <input class="au-input au-input--full" id="inputEmail" disabled placeholder="Ex: lucas@exemplo.com" type="email">
                            </div>
                            <div class="form-group col-md-3">
                                <label>Matricula</label>
                                <input required class="au-input au-input--full" id="inputMatricula" name="matricula" placeholder="Ex: 11.111-1" type="text">
                            </div>
                            <div class="form-group col-md-4">
                                <label>Permissão</label>
                                <select required id="level" name="level"  class="form-control">
                                    <option value="0">Usuario</option>
                                    <option value="1">Operador</option>
                                    <option value="2">Administrador</option>
                                    @if($user->level == App\Status::MASTER)
                                        <option value="3">Master</option>
                                    @endif
                                </select>
                            </div>
                        </div>
                        <div class="text-center">
                            <button type="submit" class="btn btn-primary">Atualizar</button>
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

            let idEncrypt;

            $('.func').on('click', '.del', function () {

                idEncrypt = $(this).data('reference');
            });

            $('.yes').on('click', function () {
                window.location.replace("{{url('/employees/delete')}}" + '/'+idEncrypt);
            });

            let table = $('#myTable').DataTable({
                "responsive": true,
                "processing": true,
                "serverSide": true,
                "ajax": '{{route('getdataFunc')}}',
                "columns":[
                    {"data": 'nome'},
                    @if($user->level >= App\Status::MASTER)
                        {"data": 'sec'},
                    @endif
                    {"data": 'email'},
                    {"data": 'sector'},
                    @if($user->level >= App\Status::ADMINISTRADOR)
                        {"data": 'action'}
                    @endif
                ],
                "lengthMenu": [[10, 25, 100, -1], [10, 25, 100, "All"]],
                "scrollX": true,
                "scrollY": "500px",
                "scrollCollapse": true
            });
            $('#myTable tbody').on('dblclick', 'tr', function () {
                let dato = table.row( this ).data();
                $.ajax({
                    type: "GET",
                    url: "{{url('')}}/employees/searchfunc/"+dato['reference'],
                    success: function( data )
                    {
                        $('#nameFunc').val(data['nome']);
                        $('#inputEmail').val(data['email']);
                        $('#inputMatricula').val(data['matricula']);
                        $('#level').val(data['level']);
                        $('#barcode').val(data['barcode']);
                        $('#setor option:contains(' + data['setor'] + ')').attr('selected', 'selected');
                        $('#secretaria option:contains(' + data['secretaria'] + ')').attr('selected', 'selected');
                        $('#reference').val(dato['reference']);
                        returnSectors(data['setor']);
                    }
                });
                $('#myModalEdit').modal('show');
            });

            $('#updateFunc').submit(function(){
                var dados = $( this ).serialize();
                $.ajax({
                    type: "POST",
                    url: "{{route('updateFunc')}}",
                    data: dados,
                    success: function(data){
                        if (data == 1){
                            $('#myModalEdit').modal('hide');
                            $('#sucesso').modal('show');
                            $('#myTable').DataTable().ajax.reload();
                        }
                        else if(data == 0){
                            $('#myModalEdit').modal('hide');
                            $('#erromsg').text('Erro Interno do Servidor');
                            $('#erro').modal('show');
                        }
                        else if(data == 101){
                            $('#myModalEdit').modal('hide');
                            $('#erromsg').text('Secretaria/Autarquia Não encontrada');
                            $('#erro').modal('show');
                        }
                        else if(data == 102){
                            $('#myModalEdit').modal('hide');
                            $('#erromsg').text('Verifique se todos o campos foram preenchidos');
                            $('#erro').modal('show');
                        }
                        else if(data == 103){
                            $('#myModalEdit').modal('hide');
                            $('#erromsg').text('Funcionário Não encontrado');
                            $('#erro').modal('show');
                        }
                        else if(data == 104){
                            $('#myModalEdit').modal('hide');
                            $('#erromsg').text('Setor não encontrado');
                            $('#erro').modal('show');
                        }
                    }
                });

                return false;
            });
        });
        let url = '{{url('')}}';
        function returnSectors(setor) {
            $('#sector').val('');
            let id = $('#secretaria').val();
            $('#sector').html('<option selected="selected" value="">Carregando...</option>');
            fullUrl = url + '/setores/pesquisaSector/' + id;
            $.ajax({
                url: fullUrl,
                type: "GET",
                dataType: "json",
                success:function(data) {
                    $('#sector').html('<option selected="selected">Selecione o Setor</option>');
                    $('#sector').append('<option value="{{encrypt(1)}}">Sem Setor</option>');
                    $.each(data, function(key, value) {
                        $('#sector').append('<option value="'+value['reference']+'">'+value['nome']+'</option>');
                    });
                    $('.setor option:contains(' + setor + ')').attr('selected', 'selected');
                }
            });
        }
    </script>
@endsection
