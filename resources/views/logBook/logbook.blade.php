@extends('layouts.default')
@section('content')
    <style>
        b{
            color: black;
        }
        h2{
            color: black;
        }
        p{
            padding-left: 50px;
            padding-top: 20px;
            padding-bottom: 30px;
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
    @if(session('warning'))
        <div class="alert alert-warning">
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
                    <h1>Diário de Bordo</h1>
                </div>
                <div class="login-form">
                    <form method="POST" action="{{route('LogBookCreate')}}">
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
                        {{--<div class="form-group row">
                            <label for="produto" class="col-sm-2 col-form-label">Km Inicial:</label>
                            <div class="input-group mb-3 col-sm-10">
                                <div class="input-group-prepend">
                                    <span class="input-group-text"><i class="fas fa-tachometer-alt"></i></span>
                                </div>
                            <input class="form-control" name="km" value="{{$logbook->km}}">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="produto" class="col-sm-2 col-form-label">Destino:</label>
                            <div class="input-group mb-3 col-sm-10">
                                <div class="input-group-prepend">
                                    <span class="input-group-text"><i class="fas fa-map-marker-alt"></i></span>
                                </div>
                                <input class="form-control" name="destino">
                            </div>
                        </div>--}}
                        <div class="form-group">
                            <label>Alguma Irregularidade ?: </label>
                            <div>
                                <input type="radio" id="irregSim" name="irreguCheck" value="1" > Sim
                                <input type="radio" id="irregNao" name="irreguCheck" value="0" checked> Não
                            </div>
                        </div>
                        <div id="irregularidade" class="form-group">
                        </div>
                        <div class="text-center">
                            <button type="submit" class="btn btn-primary">Atualizar</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <br><br>
    <script>
        $(document).ready(function(){
            $('#irregSim').click(function () {
                document.getElementById('irregularidade').innerHTML = '<label>Qual(Quais) é(são) a(s) irreguralidade(s) apresentada ?</label>' +
                    '<input type="text" class="form-control" name="irreguSai">' +
                    '';
            });
            $('#irregNao').click(function () {
                document.getElementById('irregularidade').innerHTML = ' ';
            });
        });
    </script>
@endsection
