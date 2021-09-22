@extends('layouts.default')
@section('content')
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
    <div class="col-lg-12">
        <div class="au-card chart-percent-card">
            <div class="au-card-inner">
                <div class="login-logo">
                    <h1>Adicionar Funcionário</h1>
                </div>
                <div class="login-form">
                    @if (count($errors) > 0)
                        <div class = "alert alert-danger">
                            <ul>
                                @foreach($errors->all() as $error)
                                    <li>{{$error}}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                    <form method="POST" action="{{route('createFunc')}}">
                        @csrf
                        <div class="form-group row">
                            <div class="col-lg-4">
                            <label for="nome" class="col-sm-2 col-form-label">NOME:</label>
                                <div class="input-group mb-3 col-sm-12">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text" id="basic-addon1"><i class="fas fa-user"></i></span>
                                    </div>
                                    <input id="nome" class="form-control" type="text" required name="name" placeholder="Ex: Lucas Silva" autofocus>
                                </div>
                            </div>
                            <div class="col-lg-4">
                            <label for="barcode" class="col-sm-12 col-form-label">CÓDIGO DE BARRAS:</label>
                                <div class="input-group mb-3 col-sm-12">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text" id="basic-addon1"><i class="fas fa-barcode"></i></span>
                                    </div>
                                    <input id="barcode" class="form-control" type="text" required name="barcode" autofocus>
                                </div>
                            </div>
                            <div class="col-lg-4">
                                <label for="matricula" class="col-sm-2 col-form-label">MATRÍCULA:</label>
                                <div class="input-group mb-3 col-sm-12">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><i class="fas fa-registered"></i></span>
                                    </div>
                                    <input required class="form-control" id="matricula" name="matricula" placeholder="Ex: 11.111-1" type="text">
                                </div>
                            </div>
                        </div>
                        <div class="form-group row">
                            @if($user->level >= App\Status::MASTER)
                                <div class="col-lg-6">
                                    <label for="secretaria" class="col-sm-2 col-form-label">SECRETARIA:</label>
                                    <div class="input-group mb-3 col-sm-12">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text"><i class="fas fa-chart-bar"></i></span>
                                        </div>
                                        <select id="secretaria" name="secretaria" required  class="form-control" onchange="returnSectors()">
                                            <option selected>Selecione a Sec/Aut</option>
                                            @foreach($dados as $dado)
                                                <option value="{{$dado['reference']}}">{{$dado['nome']}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            @endif
                            <div @if($user->level >= App\Status::MASTER) class="col-lg-6" @else class="col-lg-12" @endif>
                                <label for="sector" class="col-sm-2 col-form-label">SETOR:</label>
                                <div class="input-group mb-3 col-sm-12">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><i class="fas fa-box"></i></span>
                                    </div>
                                    @if($user->level >= App\Status::MASTER)
                                        <select required  id="sector" name="setor" class="form-control">
                                            <option value="{{encrypt(1)}}">Sem Setor</option>
                                        </select>
                                    @elseif($user->level == App\Status::ADMINISTRADOR)
                                        <select required  name="setor" class="form-control">
                                            <option selected>Selecione o Setor</option>
                                            <option value="{{encrypt(1)}}">Sem Setor</option>
                                            @foreach($dados1 as $dado1)
                                                <option value="{{$dado1['reference']}}">{{$dado1['nome']}}</option>
                                            @endforeach
                                        </select>
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-lg-6">
                                <label for="email" class="col-sm-2 col-form-label">EMAIL:</label>
                                <div class="input-group mb-3 col-sm-12">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text">@</span>
                                    </div>
                                    <input required class="form-control" id="email" name="email" placeholder="Ex: lucas@exemplo.com" type="email">
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <label for="level" class="col-sm-2 col-form-label">PERMISSÃO:</label>
                                <div class="input-group mb-3 col-sm-12">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><i class="fas fa-lock-open"></i></span>
                                    </div>
                                    <select required id="level" name="level"  class="form-control">
                                        <option value="0">Usuario</option>
                                        <option value="1">Operador</option>
                                        <option value="2">Administrador</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="text-center">
                            <button type="submit" class="btn btn-primary">Adicionar</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <script>
        $(".alert").fadeTo(3200, 800).slideUp(1000, function(){
            $(".alert").slideUp(500);
        });
        function returnSectors(setor) {
            $('#sector').val('');
            let reference = $('#secretaria').val();
            $('#sector').html('<option selected="selected" value="">Carregando...</option>');
            fullUrl = "{{url('')}}" + '/setores/pesquisaSector/' + reference;
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