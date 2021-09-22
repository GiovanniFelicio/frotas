@extends("layouts.default")
@section("content")
    <link href="{{asset('css/dataTable/jquery.dataTables.min.css')}}" rel="stylesheet">
    @include('flash::message')
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
    <style>
        #email{
            font-family: "Arial", cursive;
        }
    </style>
    <div class="page-wrapper">
        <div class="container">
            <div class="login-wrap">
                <div class="login-content">
                    <div class="login-form">
                        <form method="POST" action="{{ route('updateAccount') }}">
                            @csrf
                            <div class="form-group">
                                <label>Username</label>
                                <input class="au-input au-input--full" type="text" name="name" placeholder="Username" value="{{$name}}" required autofocus>
                            </div>
                            <div class="form-group">
                                <label>Email Address</label>
                                <input class="au-input au-input--full" type="email" placeholder="E-mail" disabled="disabled" value="{{$email}}">
                            </div>
                            <div class="form-group">
                                <label>Nascimento</label>
                                <input id="dtnasc" value="{{$nasc}}" name="nascimento" placeholder="DD/MM/AAAA" class="form-control input-md" required="" type="text" maxlength="10" OnKeyPress="formatar('##/##/####', this)" onBlur="showhide()">
                            </div>
                            <div class="form-group">
                                @if($level == 3)
                                    <div class="form-group">
                                        <label>User Type</label>
                                        <select name="level" class="form-control">
                                            <option value="1">Supervisor</option>
                                            <option value="2">Admin</option>
                                            <option value="3">Super Admin</option>
                                        </select>
                                    </div>
                                @endif
                                <div class="form-group">
                                    <label>Password</label>
                                    <input class="au-input au-input--full" onKeyPress="verify()" onchange="verify()" onkeydown="verify()" placeholder=" ********** " id="password" type="password" name="password" autocomplete="new-password">
                                </div>
                                <div class=" form-group">
                                    <label>Confirm Password</label>
                                    <input onKeyPress="verify()" onchange="verify()" onkeydown="verify()" placeholder=" ********** " id="password-confirm" type="password" class="au-input au-input--full" name="password_confirmation" autocomplete="new-password">
                                </div>
                                <div class="form-group">
                                    <input id="signup" onclick="" onkeydown="" type="submit" value="Atualizar" class="au-btn au-btn--block au-btn--green m-b-20">
                                </div>
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

