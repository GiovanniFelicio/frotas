@extends('layouts.default')
@section('content')
    <br><br>
    <style>
        .login-content{
            background: rgba(255, 255, 255, 0);
        }
        .solic{
            background-color: rgba(0, 0, 0, 0.5);
        }
    </style>
    <div class="row">
        <div class="login-wrap">
            <div class="login-content" id="contentLogin">
                <div class="au-card chart-percent-card">
                    <div class="au-card-inner">
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
                            <div class="login-logo">
                                <h1>Diário de Bordo</h1>
                            </div>
                            <div class="login-form">
                                <div class="col-12">
                                    <form method="POST" action="{{route('voltarorigem')}}">
                                        @csrf
                                        <input hidden name="reference" value="{{encrypt($logbook->id)}}">
                                        <div class="form-group row">
                                            <label for="produto" class="col-sm-2 col-form-label">Veículo:</label>
                                            <div class="input-group mb-3 col-sm-10">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text"><i class="fas fa-car"></i></span>
                                                </div>
                                            <input class="form-control" disabled value="{{$logbook->veiculo}}">
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label for="produto" class="col-sm-2 col-form-label">Origem:</label>
                                            <div class="input-group mb-3 col-sm-10">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text"><i class="fas fa-thumbtack"></i></span>
                                                </div>
                                            <input class="form-control" disabled value="{{$logbook->origem}}">
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label>Observação ou Irregularidade ?: </label>
                                            <div>
                                                <input type="radio" id="irregSim" value="1" > Sim
                                                <input type="radio" id="irregNao" value="0" checked> Não
                                            </div>
                                        </div>
                                        <div id="irregularidade" class="form-group">
                                        </div>
                                        <div class="form-group text-center">
                                            <input id="signup" onclick="" onkeydown="" type="submit" value="Adicionar" class="btn btn-info">
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script type="text/javascript">
        $(document).ready(function(){
            $('#irregSim').click(function () {
                document.getElementById('irregularidade').innerHTML = '<label>Qual(Quais) é(são) a(s) irreguralidade(s) apresentada ?</label>' +
                    '<input type="text" class="au-input au-input--full" name="irreguCheg">' +
                    '';
            });
            $('#irregNao').click(function () {
                document.getElementById('irregularidade').innerHTML = ' ';
            });
        });
    </script>
@endsection


