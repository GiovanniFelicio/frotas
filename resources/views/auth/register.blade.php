@extends("layouts.layoutAuth")
@section("header")
    <!-- Required meta tags-->
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="au theme template">
    <meta name="author" content="Hau Nguyen">
    <meta name="keywords" content="au theme template">

    <title>SignUp</title>

@endsection
@section("content")
<div class="page-wrapper">
    <div class="container">
        <div class="login-wrap">
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
            <div class="login-content">
                <div class="login-form">
                    <form method="POST" action="{{ route('saveUser') }}">
                        @csrf
                        <div class="form-group">
                            <label>Username</label>
                            <input class="au-input au-input--full @error('name') is-invalid @enderror" type="text" name="name" placeholder="Username" value="{{ old('name') }}" required autofocus>
                            @error('name')
                                <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label>Email Address</label>
                            <input class="au-input au-input--full @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required type="email" placeholder="E-mail">
                            @error('email')
                            <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label>User Type</label>
                            <select name="level" class="form-control">
                                <option value="1">Supervisor</option>
                                <option value="2">Admin</option>
                                <option value="3">Super Admin</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label>Password</label>
                            <input class="au-input au-input--full" onKeyPress="verify()" onchange="verify()" onkeydown="verify()" placeholder=" ********** " id="password" type="password" name="password" required autocomplete="new-password">
                        </div>
                        <div class=" form-group">
                            <label>Confirm Password</label>
                            <input onKeyPress="verify()" onchange="verify()" onkeydown="verify()" placeholder=" ********** " id="password-confirm" type="password" class="au-input au-input--full" name="password_confirmation" required autocomplete="new-password">
                        </div>
                        <div class="form-group">
                            <input id="signup" type="submit" value="Sign Up" class="au-btn au-btn--block au-btn--green m-b-20">
                        </div>
                        <div>
                            <a class="redirect" href="{{route('home')}}"><b>Início</b></a>
                        </div>
                    </form>
                    <div class="register-link">
                        <p>
                            Already have account?
                            <a href="{{route("logout")}}">Sign In</a>
                        </p>
                    </div>
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
