@extends("layouts.layoutAuth")
@section("header")
    <!-- Required meta tags-->
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="au theme template">
    <meta name="author" content="Hau Nguyen">
    <meta name="keywords" content="au theme template">
    <title>LogIn</title>
    <script src="{{asset('js/jquery-3.4.1.js')}}" type="text/javascript"></script>
    <style>
        #contentLogin{
            background: rgba(255, 255, 255, 0.3); border-radius: 10px
        }
        label{
            color: #fff;
            font-family: "Arial Black";
        }
        #buttonLogin{
            color: #fff;
            font-family: "Arial Black";
        }
        #forgotPassword{
            background: none;
            font-family: "Arial Black";
        }
    </style>

@endsection
@section("content")
<div class="page-wrapper">
    <div class="container">
        @if (session()->has('message'))
            <div class="alert alert-warning alert-dismissible">
                {{ session('message') }}
            </div>
        @endif
            <script>
                $(".alert").fadeTo(3200, 800).slideUp(1000, function(){
                    $(".alert").slideUp(500);
                });
            </script>
        <div class="login-wrap">
            <div class="login-content" id="contentLogin">
                <div class="login-logo">
                    <a href="#">
                        <img src="{{asset("images/fundetecLogo2.png")}}" alt="logoFundetec">
                    </a>
                </div>
                <div class="login-form">
                    <form method="POST" action="{{ route('login') }}">
                        @csrf
                        <div class="form-group">
                            <label>Email Address</label>
                            <input class="au-input au-input--full @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus>
                            @error('email')
                                <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label>Password</label>
                            <input type="password" class="au-input au-input--full @error('password') is-invalid @enderror" name="password" required autocomplete="current-password">
                            @error('password')
                                <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                            @enderror
                        </div>
                        <div hidden class="login-checkbox remember">
                            <label>
                                <input type="checkbox" name="remember" {{ old('remember') ? 'checked' : '' }}>Remember Me
                            </label>
                        </div>
                        <button id="buttonLogin" type="submit" class="au-btn au-btn--block au-btn--green m-b-20">{{ __('Login') }}</button>
                    </form>
                    <div class="card-footer">
                        <div class="d-flex justify-content-center links">
                            @if (Route::has('password.request'))
                                <a id="forgotPassword" class="btn btn-link" href="{{ route('password.request') }}">
                                    {{ __('Forgot Your Password?') }}
                                </a>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
@section("js")
<script>
    $(document).ready(function () {

        $('#sidebarCollapse').on('click', function () {
            $('#sidebar').toggleClass('active');
        });

    });

</script>
@endsection
