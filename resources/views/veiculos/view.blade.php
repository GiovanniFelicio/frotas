@extends('layouts.default')
@section('content')
    <div class="col-lg-12">
        <div class="au-card chart-percent-card">
            <div class="au-card-inner">
                <div class="row">
                    <div class="col-sm-12 text-center">
                        <br><br>
                        <div class="table-responsive m-b-30">
                            <table class="table table-bordered">
                                <tbody>
                                <tr>
                                    <th>Nome</th>
                                    <td>{{$veiculo->name}}</td>
                                </tr>
                                <tr>
                                    <th>Secretaria</th>
                                    <td>{{$veiculo->secretaria->name}}</td>
                                </tr>
                                <tr>
                                    <th>Placa</th>
                                    <td>{{$veiculo->placa}}</td>
                                </tr>
                                <tr>
                                    <th>Data de Anexo</th>
                                    <td>{{\Carbon\Carbon::parse($veiculo->created_at)->format('d/m/Y').' às '.\Carbon\Carbon::parse($veiculo->created_at)->format('H:i')}}</td>
                                </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-12">
        <div class="au-card chart-percent-card">
            <div class="au-card-inner">
                <table style="width: 100%" id="tableAuths" class="tableStyleGio datatablesStyle display nowrap">
                    <thead>
                    <tr>
                        <th>Nome</th>
                        <th>Secretaria/Autarquia</th>
                        <th>Setor</th>
                        <th>Opções</th>
                    </tr>
                    </thead>
                </table>
                <br><br><br>
            </div>
        </div>
    </div>

    <div class="col-lg-12">
        <div class="au-card chart-percent-card">
            <div class="au-card-inner">
                @if(Auth::user()->level >= App\Status::ADMINISTRADOR)
                    <div class="row">
                        <div class="col text-right">
                            <a href="{{route('lancamentogastos', encrypt($veiculo->id))}}" class="au-btn au-btn-icon au-btn--blue">
                                <i class="zmdi zmdi-plus"></i>Lançar gastos</a>
                            <br><br>
                        </div>
                    </div>
                @endif
                <div class="row">
                    <div class="col-sm-12 text-center">
                        <br><br>
                        <div class="bgc-white bd bdrs-3 p-20 mB-20">
                            <table style="width: 100%" class="tableStyleGio datatablesStyle display nowrap gastos" id="gastos">
                                <thead>
                                <tr>
                                    <th class="">ID</th>
                                    <th class="text-center">Item</th>
                                    <th class="text-center">Valor</th>
                                    <th class="text-center">Data</th>
                                    <th class="text-center">Opções</th>
                                </tr>
                                </thead>
                            </table>
                        </div>
                    </div>
                </div>
                <br><br><br>
            </div>
        </div>
    </div>

    <!-- Modal -->
    <div class="modal fade" id="myModal" tabindex="-1" role="dialog" data-backdrop="false" style="background-color: rgba(0, 0, 0, 0.5);">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"> DELETAR </h5>
                    <button type="button" class="buttonModal close" data-dismiss="modal">
                        <span>X</span>
                    </button>
                </div>
                <div class="modal-body">
                    Deseja deletar este veiculo mesmo ?
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" data-dismiss="modal">NAO</button>
                    <button type="button" class="btn btn-primary yes">SIM</button>
                </div>
            </div>
        </div>
    </div>
    <script type="text/javascript">
        $(document).ready(function() {
            $('#tableAuths').on('click', '.toggleAuth', function () {
                let val = this.checked ? this.value : 'off';
                let idEncrypt = $(this).data('id');
                let CSRF_TOKEN = '{{csrf_token()}}';
                let Veiculo = '{{encrypt($veiculo->id)}}';
                if(val == 'on'){
                    $.ajax({
                        url: '{{route("authVehicle")}}',
                        type: 'POST',
                        data: {_token: CSRF_TOKEN, func:idEncrypt, veiculo:Veiculo},
                        dataType: 'JSON',
                        success: function (data) { 
                            if(data == 2){
                                alert('Não foi possível autorizar !!');
                            }
                            else if(data == 1){
                                $('#tableAuths').DataTable().ajax.reload();
                            }
                            else{
                                alert(data);
                            }
                        }
                    }); 
                }
                else if(val == 'off'){
                    $.ajax({
                        url: '{{route("disallowanceVehicle")}}',
                        type: 'POST',
                        data: {_token: CSRF_TOKEN, func:idEncrypt, veiculo:Veiculo},
                        dataType: 'JSON',
                        success: function (data) { 
                            console.log(data);
                            if(data == 2){
                                alert('Não foi possível desautorizar !!');
                            }
                            else if(data == 1){
                                $('#tableAuths').DataTable().ajax.reload();
                            }
                            else{
                                alert(data);
                            }
                        }
                    }); 
                }
            });

            let table = $('#gastos').DataTable({
                "responsive": true,
                "processing": true,
                "serverSide": true,
                "ajax": '{{route("gastos", encrypt($veiculo->id))}}',
                "columns":[
                    {"data": 'id'},
                    {"data": 'item'},
                    {"data": 'valor'},
                    {"data": 'data'},
                    {"data": 'action'}
                ],
                "scrollX": true,
                "scrollY": "500px",
                "scrollCollapse": true
            });
            let idEncrypt2;
            $('.gastos').on('click', '.del', function () {
                idEncrypt2 = $(this).data('id');
                $('#myModal').modal('show');
            });
            $('.yes').on('click', function () {
                window.location.replace("{{url('/veiculos/delete')}}" + '/'+idEncrypt2);
            });

            $('#tableAuths').DataTable({
				"responsive": true,
                "processing": true,
                "serverSide": true,
                "ajax": '{{route("getdataAuthsFunc", encrypt($veiculo->id))}}',
                "columns":[
                    {"data": 'name'},
                    {"data": 'secretaria'},
                    {"data": 'setor'},
                    {"data": 'action'}

                ],
                "scrollX": true,
                "scrollY": "500px",
                "scrollCollapse": true
            });
        });
    
    </script>
@endsection
