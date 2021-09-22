@extends('layouts.default')
@section('content')
    <div class="col-lg-12">
        <div class="au-card chart-percent-card">
            <div class="au-card-inner">
                <h2 class="text-center">Dados da Sec/Aut</h2>
                <div class="row">
                    <div class="col-sm-12 text-center">
                        <br><br>
                        <div class="table-responsive m-b-30">
                            <table class="table table-bordered">
                                <tbody>
                                    <tr>
                                        <th>Nome da Sec/Aut:</th>
                                        <td>{{$secretarias->name}}</td>
                                    </tr>
                                    <tr>
                                        <th>E-mail da Sec/Aut</th>
                                        <td>{{$secretarias->email}}</td>
                                    </tr>
                                    <tr>
                                        <th>Data de Criação</th>
                                        <td>{{\Carbon\Carbon::parse($secretarias->created_at)->format('d/m/Y')}}</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        <br>
                    </div>
                </div>
                <br>
            </div>
        </div>
    </div>
    <div class="col-lg-12">
        <div class="au-card chart-percent-card">
            <h2 class="text-center">Gerenciamento de Permissões</h2>
            <br>
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
    <script type="text/javascript">
        $(document).ready(function() {

            $('#tableAuths').on('change', '.selectStaffs', function () {
                let val = this.value;
                let idEncrypt = $(this).data('id');
                let CSRF_TOKEN = '{{csrf_token()}}';
                $.ajax({
                    url: '{{route("changerole")}}',
                    type: 'POST',
                    data: {_token: CSRF_TOKEN, func:idEncrypt, role:val},
                    dataType: 'JSON',
                    success: function (data) { 
                        if(data == 0){
                            alert('Erro ao fazer a atualização de permissão !!');
                        }
                        else if(data != 1){
                            alert(data);
                        }
                    }
                }); 
            });
            let CSRF_TOKEN = '{{csrf_token()}}';
            $('#tableAuths').DataTable({
                "responsive": true,
                "processing": true,
                "serverSide": true,
                "ajax": '{{route("getdataAdms", encrypt($secretarias->id))}}',
                "columns":[
                    {"data": 'name'},
                    {"data": 'secretaria'},
                    {"data": 'sector'},
                    {"data": 'action'}

                ],
                "scrollX": true,
                "scrollY": "500px",
                "scrollCollapse": true
            });
        });

    </script>

@endsection

