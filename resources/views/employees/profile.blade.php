@extends('layouts.default')
@section('content')
    <br><br>
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
                    <h1>Atualizar Perfil</h1>
                </div>
                <div class="login-form">
                    <form id="myform" method="POST" action="{{route('upProfileFunc')}}">
                        @csrf
                        <div class="form-row">
                            <div class="form-group col-md-12">
                                <label>Nome do Funcionário</label>
                                <input class="au-input au-input--full" type="text" required name="nameFunc" placeholder="Ex: Lucas Silva" value="{{$func->name}}" autofocus>
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group col-md-9">
                                <label>E-mail</label>
                                <input class="au-input au-input--full" disabled value="{{$func->email}}">
                            </div>
                            <div class="form-group col-md-3">
                                <label>Matricula</label>
                                <input required class="au-input au-input--full" value="{{$func->matricula}}" name="matricula" placeholder="Ex: 11.111-1" maxlength="8" type="text" onkeypress="formatar('##.###-#', this)">
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group col-md-6">
                                <div class="form-group">
                                    <label>Password</label>
                                    <input class="au-input au-input--full" placeholder=" ********** " id="password" type="password" name="password" autocomplete="new-password">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <label>Confirm Password</label>
                                <input placeholder=" ********** " id="password-confirm" type="password" class="au-input au-input--full" name="password_confirmation" autocomplete="new-password">
                            </div>
                        </div>
                        <div class="text-center">
                            <button id="btnSubmit" type="submit" class="btn btn-primary">Adicionar</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        $(function () {
        $("#btnSubmit").click(function () {
            var password = $("#password").val();
            var confirmPassword = $("#password-confirm").val();
            if (password != confirmPassword) {
                alert("Passwords do not match.");
                return false;
            }
            return true;
        });
    });
    </script>
@endsection

