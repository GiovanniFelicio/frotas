@extends('layouts.default')
@section('content')
    <style>
        .modal{
            background-color: rgba(0, 0, 0, 0.5);
        }
    </style>
    <div class="row">
        <div class="login-wrap">
            <div class="login-content" id="contentLogin">
                <div class="login-logo">
                    <h1 class="text-center">Contato</h1>
                </div>
                <hr>
                @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
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
                <br>
                <div class="login-form">
                    <div class="col-12">
                        <form method="POST" action="{{route('createcontact')}}">
                            @csrf
                            <div class="form-group row">
                                <label for="motivo" class="col-sm-3 col-form-label">Motivo:</label>
                                <div class="col-sm-9">
                                    <div class="input-group mb-3">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text" id="basic-addon1"><i class="fas fa-chart-bar"></i></span>
                                        </div>
                                        <select id="motivo" name="motivo" required class="form-control">
                                            <option value="0">Sugestão</option>
                                            <option value="1">Reportar Problema</option>
                                        </select>
                                    </div>
                                    <small class="f5orm-text text-muted">Selecione o motivo do seu contato</small>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="detalhes" class="col-sm-3 col-form-label">Mensagem:</label>
                                <div class="col-sm-9">
                                    <div class="input-group mb-3">
                                        <textarea onkeyup="textCounter(this,'counter',501);" maxlength="500" class="form-control" id="detalhes" name="mensagem" rows="3"></textarea>
                                    </div>
                                    <small class="form-text">Detalhes do seu contato com máximo de 500 caracteres</small>
                                </div>
							</div>
                            <br>
                            <div class="text-center">
                                <button type="submit" class="btn btn-primary">Adicionar</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <script>
            $(document).ready(function () {
                
                $(".alert").fadeTo(3800, 800).slideUp(1000, function(){
                    $(".alert").slideUp(600);
                });
            });
            function textCounter(field,field2,maxlimit)
            {
                var countfield = document.getElementById(field2);
                if ( field.value.length > maxlimit ) {
                    field.value = field.value.substring( 0, maxlimit );
                    return false;
                } 
                else {
                    countfield.value = maxlimit - field.value.length;
                }
            }
        </script>
@endsection
