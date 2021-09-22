@extends('layouts.default')
@section('content')
    <style>
        .modal{
            background-color: rgba(0, 0, 0, 0.5);
            z-index: 15;
        }
        @media(max-width: 794px){
            .add{
                z-index: -1;
            }
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
    <div id="conteudo">
        <div class="row">
            <div class="col-md-12 text-right add">
                @if(Auth::user()->level >= 2)
                    <a href="{{route('anexarVeiculo')}}" style="color: white" class="btn btn-info uppercase">
                        <i class="zmdi zmdi-plus"></i>add veículo</a>
                @endif
            </div>
        </div>
        <br>
        <div class="row">
            <div class="col-md-12">
                <h2 class="display-4 text-center">Gerenciador de Veículos</h2>
            </div>
        </div>
        <br><br><br>
        <div class="row">
            <div class="col-lg-12">
                <div class="bgc-white bd bdrs-3 p-20 mB-20">
                    <table style="width: 100%" id="myTable" class="tableStyleGio datatablesStyle display nowrap vehi">
                        <thead>
                        <tr>
                            <th>Nome</th>
                            @if(Auth::user()->level >= App\Status::MASTER)
                                <th>Secretaria/Autarquia</th>
                            @endif
                            <th>Placa</th>
                            <th>Sendo Usado</th>
                            @if(Auth::user()->level >= App\Status::ADMINISTRADOR)
                                <th>Opções</th>
                            @endif
                        </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <!-- Modal -->
    <div class="modal fade" id="myModal" tabindex="-1" role="dialog" data-backdrop="false" style="background-color: rgba(0, 0, 0, 0.5);">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"> DELETAR </h5>
                    <button type="button" class="close" data-dismiss="modal">
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
    <div class="modal fade" id="myModalEdit" role="dialog" data-backdrop="false">
        <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"> EDITAR </h5>
                    <button type="button" class="close" data-dismiss="modal">
                        <span>X</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form method="POST" id="updatevehi">
                        @csrf
                        <input hidden id="reference" name="reference">
                        <div class="form-group row">
                            <div class="col-sm-6">
                            <label for="nome" class="col-sm-2 col-form-label">NOME:</label>
                                <div class="input-group mb-3 col-sm-12">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text" id="basic-addon1"><i class="fas fa-car"></i></span>
                                    </div>
                                    <input id="nome" placeholder="Ex: Ford Ka" class="form-control" name="nomevehi">
                                </div>
                            </div>
                            @if(Auth::user()->level >= App\Status::ADMINISTRADOR)
                                <div class="col-lg-6">
                                    <label for="secretaria" class="col-sm-2 col-form-label">SECRETARIA:</label>
                                    <div class="input-group mb-3 col-sm-12">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text"><i class="fas fa-chart-bar"></i></span>
                                        </div>
                                        <select id="secretaria" name="secretaria" required  class="form-control">
                                            <option selected>Selecione a Sec/Aut</option>
                                            @foreach($secretarias as $secretaria)
                                                <option value="{{encrypt($secretaria->id)}}">{{$secretaria->name}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            @endif
                        </div>
                        <div class="form-group row">
                            <div class="col-lg-5">
                                <label for="placa" class="col-sm-2 col-form-label">PLACA:</label>
                                <div class="input-group mb-3 col-sm-12">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text" id="basic-addon1"><i class="fas fa-pager"></i></span>
                                    </div>
                                    <input id="placa" placeholder="Ex: ABC1234" class="form-control" name="placa">
                                </div>
                            </div>
                            <div class="col-lg-4">
                                <label for="status" class="col-sm-6 col-form-label">EM USO:</label>
                                <div class="input-group mb-3 col-sm-12">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><i class="fas fa-lock-open"></i></span>
                                    </div>
                                    <select required id="status" name="status"  class="form-control">
                                        <option value="1">NÃO</option>
                                        <option value="2">SIM</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="text-center">
                            <button type="submit" class="btn btn-primary">Atualizar</button>
                        </div>
                    </form>
                    <br>
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

            $('.vehi').on('click', '.del', function () {

                idEncrypt = $(this).data('reference');
                $('#myModal').modal('show');
            });

            $('.yes').on('click', function () {
                window.location.replace("{{url('/veiculos/delete')}}" + '/'+idEncrypt);
            });


           let table = $('#myTable').DataTable({
				"responsive": true,
                "processing": true,
                "serverSide": true,
                "ajax": '{{route("getdataVehicles")}}',
                "columns":[
                    {"data": 'nome'},
                    @if(Auth::user()->level >= App\Status::MASTER)
                        {"data": 'secretaria'},
                    @endif
                    {"data": 'placa'},
                    {"data": 'status'},
                    @if(Auth::user()->level >= App\Status::ADMINISTRADOR)
                        {"data": 'action'}
                    @endif

                ],
                "scrollX": true,
                "scrollY": "500px",
                "scrollCollapse": true
            });
            $('#myTable tbody').on('dblclick', 'tr', function () {
                let dato = table.row( this ).data();
                $.ajax({
                    type: "GET",
                    url: "{{url('')}}/veiculos/search/"+dato['reference'],
                    success: function( data )
                    {
                        $('#nome').val(data['nome']);
                        $('#placa').val(data['placa']);
                        $('#km').val(data['km']);
                        $('#codigo').val(data['codigo']);
                        $('#status').val(data['status']);
                        $('#secretaria option:contains(' + data['secretaria'] + ')').attr('selected', 'selected');
                        $('#reference').val(dato['reference']);
                    }
                });
                $('#myModalEdit').modal('show');
            });
            $('#updatevehi').submit(function(){
                var dados = $( this ).serialize();
                $.ajax({
                    type: "POST",
                    url: "{{route('updatevehi')}}",
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
                        else if(data == 100){
                            $('#myModalEdit').modal('hide');
                            $('#erromsg').text('Veículo não encontrado');
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
                    }
                });

                return false;
            });
            /*
            $("#importExcelVeiculo").on('submit', function(e){
                e.preventDefault();
                $.ajax({
                    xhr: function() {
                        var xhr = new window.XMLHttpRequest();
                        xhr.upload.addEventListener("progress", function(evt) {
                            if (evt.lengthComputable) {
                                var percentComplete = ((evt.loaded / evt.total) * 100);
                                $(".progress-bar").width(percentComplete + '%');
                                $(".progress-bar").html(percentComplete+'%');
                            }
                        }, false);
                        return xhr;
                    },
                    type: 'POST',
                    url: '{{ route("importExcelVeiculo") }}',
                    data: new FormData(this),
                    contentType: false,
                    cache: false,
                    processData:false,
                    beforeSend: function(){
                        $(".progress-bar").width('0%');
                    },
                    error:function(erro){
                        $('#uploadStatus').html('<p style="color:#EA4335;">'+erro+'</p>');
                        setInterval(function(){ 
                                $("#uploadStatus").html(' ');
                                $(".progress-bar").width('0%');
                            }, 4000);
                    },
                    success: function(resp){
                        if(resp[0] == 'ok'){
                            $('#importExcelVeiculo')[0].reset();
                            $('#myTable').DataTable().ajax.reload();
                            $('#uploadStatus').html('<p style="color:#28A74B;">'+resp[1]+' criados com Sucesso e '+resp[2]+' já existente(s)</p>');
                            setInterval(function(){ 
                                $("#uploadStatus").html(' ');
                                $(".progress-bar").width('0%');
                            }, 4000);
                        }else {
                            $('#uploadStatus').html('<p style="color:#EA4335;">'+resp+'</p>');
                            setInterval(function(){ 
                                $("#uploadStatus").html(' ');
                                $(".progress-bar").width('0%');
                            }, 4000);
                        }
                    }
                });
            });
            
            // File type validation
            $("#importFileVeiculo").change(function(){
                var allowedTypes = ['application/excel', 'application/vnd.ms-excel', 'application/msexcel'];
                var file = this.files[0];
                var fileType = file.type;
                if(!allowedTypes.includes(fileType)){
                    alert('Please select a valid file (XLS).');
                    $("#importFileVeiculo").val('');
                    return false;
                }
            });*/

        });
        function deleta() {
            let url = ($('#delVehi').attr('data-href'));
            window.location.replace(url);
        }
    </script>
@endsection
