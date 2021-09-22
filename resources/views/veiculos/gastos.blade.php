@extends('layouts.default')
@section('content')
    <script src="{{asset('js/jquery.maskMoney.js')}}" type="text/javascript"></script>
    <style>
        ul li {list-style: none; cursor: pointer;}
        li.smart_autocomplete_highlight {background-color: #C1CE84;}
        .smart_autocomplete_container { margin: 10px 0; padding: 5px; background-color: #E3EBBC; }
    </style>
    <div class="row">
        <div class="login-wrap">
            <div class="login-content" id="contentLogin">
                <div class="login-logo">
                    <h1 class="text-center">Lançamentos</h1>
                    <h1 class="text-center">de Gastos</h1>
                </div>
                <hr>
                <div class="login-form">
                    <div class="col-12">
                        <form method="POST" action="{{route('lancargastos')}}">
                            @csrf
                            <input hidden name="reference" value="{{$id}}">
                            <div class="form-group row">
                                <label for="produto" class="col-sm-2 col-form-label">ITEM:</label>
                                <div class="col-sm-10">
                                    <div class="input-group mb-3">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text" id="basic-addon1"><i class="fas fa-box"></i></span>
                                        </div>
                                        <input id="produto" class="form-control" name="produto">
                                    </div>
                                    <small class="form-text text-muted">Coloque o nome para referência do gasto, Ex: Combustível</small>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="money" class="col-sm-2 col-form-label">VALOR:</label>
                                <div class="col-sm-10">
                                    <div class="input-group mb-3">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text" id="basic-addon1">R$</span>
                                        </div>
                                        <input id="money" class="form-control" name="money">
                                    </div>
                                    <small class="form-text text-muted">Coloque o valor do gasto, Ex: 152.80</small>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="money" class="col-sm-2 col-form-label">DATA:</label>
                                <div class="col-sm-10">
                                    <div class="input-group mb-3">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text" id="basic-addon1"><i class="fas fa-calendar-alt"></i></span>
                                        </div>
                                        <input type="date" class="form-control" name="date">
                                    </div>
                                    <small class="form-text">Coloque a data do gasto, Ex: 16/01/2020</small>
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
    <script>
        $(function() {
            $('#money').maskMoney({prefix:'R$ ', allowNegative: true, thousands:'', affixesStay: false});
        })
    </script>
@endsection
