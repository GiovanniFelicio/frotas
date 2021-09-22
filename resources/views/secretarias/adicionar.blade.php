@extends('layouts.default')
@section('content')
    <script src="{{asset('js/jquery-3.4.1.js')}}" type="text/javascript"></script>
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
            <div class="login-wrap">
                <div class="login-content" id="contentLogin">
                    <div class="login-logo">
                        <h1 class="text-center">Adicionar</h1>
                        <h1 class="text-center">Secretaria/Autarquia</h1>
                    </div>
                    <hr>
                    <div class="login-form">
                        <div class="col-12">
                            <form method="POST" action="{{route('createSec')}}">
                                @csrf
                                <div class="form-group row">
                                    <label for="produto" class="col-sm-2 col-form-label">NOME:</label>
                                    <div class="col-sm-10">
                                        <div class="input-group mb-3">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text" id="basic-addon1"><i class="fas fa-building"></i></span>
                                            </div>
                                            <input id="produto" class="form-control" name="nameSec">
                                        </div>
                                        <small class="form-text text-muted">Coloque o nome da Secretaria/Autarquia</small>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="money" class="col-sm-2 col-form-label">EMAIL:</label>
                                    <div class="col-sm-10">
                                        <div class="input-group mb-3">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text" id="basic-addon1">@</span>
                                            </div>
                                            <input id="money" class="form-control" name="emailSec">
                                        </div>
                                        <small class="form-text text-muted">Coloque e-mail da Secretaria/Autarquia</small>
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
        </div>
@endsection

<script>
    function verify() {
        val1 = document.getElementById("password").value;
        val2 = document.getElementById("password-confirm").value;
        if (val1 != val2) {
            document.getElementById('signup').disabled = true;
            document.getElementById("password").style.borderColor = "#f00";
            document.getElementById("password-confirm").style.borderColor = "#f00";
        } else {
            document.getElementById('signup').disabled = false;
            document.getElementById("password").style.borderColor = "#009e12";
            document.getElementById("password-confirm").style.borderColor = "#009e12";

        }
    }
    function formatar(mascara, documento){
        var i = documento.value.length;
        var saida = mascara.substring(0,1);
        var texto = mascara.substring(i);

        if (texto.substring(0,1) != saida){
            documento.value += texto.substring(0,1);
        }

    }
</script>

