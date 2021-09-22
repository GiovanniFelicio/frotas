@extends('layouts.default')
@section('content')
    <link href="{{asset('vendor/select2/dist/css/select2.min.css')}}" rel="stylesheet" />
    <script src="{{asset('vendor/select2/dist/js/select2.min.js')}}"></script>
    <link href="{{asset('/css/bootstrap.min.css')}}" rel="stylesheet" >
    <script src="{{asset('js/jquery.min.js')}}"></script>
    <div class="col-lg-12">
        <div class="au-card chart-percent-card">
            <div class="au-card-inner">
                <div class="row">
                    <div class="col-sm-12 text-center">
                        <br><br>
                        <br>
                        <div class="panel panel-default">
                            <div class="panel-heading">
                                <h3 class="text-center">
                                    Funcionarios do Setor
                                </h3>
                            </div>
                            <div class="panel-body">
                                <div class="table-responsive m-b-30">
                                    <table class="table" id="auth">
                                        <thead>
                                            <tr>
                                                <th class="text-center">Nome do Usuário</th>
                                                <th class="text-center">E-mail do Usuário</th>
                                            </tr>
                                        </thead>
                                        <tbody id="funcs">
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                @if(Auth::user()->level >= 3)
                    <div class="row">
                        <div class="col"></div>
                        <div class="col-4">
                            <form action="" method="POST" id="adcFuncSector">
                                @csrf
                                <h3>Adicionar Usuário</h3>
                                <div class="form-group">
                                    <input name="setor" hidden value="{{encrypt($setor->id)}}">
                                    <select id="user" class="multipleSelect" multiple name="Func[]"></select>
                                </div>
                                <small>Selecione os funcionarios que deseja adicionar ao setor.</small>
                                <br><br>
                                <div class="form-group">
                                    <input id="signup" type="submit" value="Adicionar" class="au-btn au-btn--block au-btn--green m-b-20">
                                </div>
                            </form>
                        </div>
                        <div class="col"></div>
                    </div>
                @endif
                <br><br><br>
            </div>
        </div>
    </div>
    <script>

        @if(Auth::user()->level >= 3)
            $(document).ready(function() {
                $('.multipleSelect').select2();
            });
            jQuery(document).ready(function(){
                jQuery('#adcFuncSector').submit(function(){
                    var dados = jQuery( this ).serialize();

                    jQuery.ajax({
                        type: "POST",
                        url: "{{route('adcFuncSector')}}",
                        data: dados,
                        success: function( data )
                        {
                            alert( data );
                            funcSetor();
                            funcsForAdmin();
                            $('.multipleSelect').empty();
                        }
                    });

                    return false;
                });
            });
            $(document).ready(function(){
                $("#myInput").on("keyup", function() {
                    var value = $(this).val().toLowerCase();
                    $("#myTable tr").filter(function() {
                        $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
                    });
                });
            });

            function deleta(id){
                let resposta = confirm('Deseja remover este funcionario do setor mesmo ?');
                if(resposta){
                    var dados = jQuery( this ).serialize();
                    let idd = id;
                    let url = '{{url('')}}';
                    jQuery.ajax({
                        type: "GET",
                        url: url + '/setores/delFuncSetor/' + idd,
                        data: dados,
                        success: function( data )
                        {
                            alert( data );
                            funcSetor();
                            funcsForAdmin();
                            $('.multipleSelect').empty();
                        }
                    });
                }
                return false;
            }
            function funcsForAdmin() {
                let url = '{{url('')}}';
                document.getElementById('user').innerHTML = '<option selected="selected" value="">Carregando...</option>';
                $.ajax({
                    url: '{{route('allFuncsSec', $setor->sec_id)}}',
                    type: "GET",
                    dataType: "json",
                    success:function(data) {
                        $('.multipleSelect').empty();
                        $.each(data, function(idd, value) {
                            document.getElementById('user').innerHTML += '<option value="'+value['id']+'">'+value['name']+'</option>';
                        });
                    }
                });
            }
            funcsForAdmin();
        @endif

        function funcSetor() {
            let url = '{{url('')}}';
            document.getElementById('funcs').innerHTML = '<td class="col-xs-3">Loading...</td>';
            fullUrl = url + '/setores/funcSetor/' + '{{encrypt($setor->id)}}';
            $.ajax({
                url: fullUrl,
                type: "GET",
                dataType: "json",
                success:function(data) {
                    document.getElementById('funcs').innerHTML='';
                    $.each(data, function(idd, usuario) {
                        console.log(idd,usuario);
                        document.getElementById('funcs').innerHTML +=
                            '<tr>\n' +
                            '<td class="col-xs-5">'+usuario['name']+'</td>\n' +
                            '<td class="col-xs-2">'+usuario['email']+'</td>\n' +
                            '<td class="col-xs-2">\n' +
                            @if(Auth::user()->level >= 3)
                                '<button onclick="deleta('+usuario['id']+')" onkeypress="deleta('+usuario['id']+')" class="btn btn-danger delete">X</button>\n' +
                            @endif
                            '</td>\n' +
                            '</tr>';
                        //sectorId.append('<option value="'+key+'">'+value+'</option>');
                    });
                }
            });
        }
        funcSetor();
    </script>

@endsection

