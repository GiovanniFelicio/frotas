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
                    <h1>Gerador de Relatório</h1>
                </div>
                <hr><br>
                <div class="login-form">
                    <div class="col-12">
                        <form method="POST" action="{{route('gerador')}}">
                            @csrf
                            <div class="form-group row">
                                <label for="produto" class="col-sm-4 col-form-label">TIPO:</label>
                                <div class="input-group mb-3 col-sm-8">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><i class="zmdi zmdi-archive"></i></span>
                                    </div>
                                    <select id="tipo" name="tipo" class="form-control">
                                        <option selected disabled="disabled">Selecione: </option>
                                        <option value="1">Bordo(s)</option>
                                        <option value="3">Gasto(s) do(s) Veículo(s)</option>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="produto" class="col-sm-4 col-form-label">Filtrar Por:</label>
                                <div class="input-group mb-3 col-sm-8">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><i class="fas fa-clipboard-list"></i></span>
                                    </div>
                                    <select id="filtrarPor" name="filtroPor" class="form-control">
                                        <option selected disabled="disabled">Selecione: </option>
                                        <option value="1">Dia</option>
                                        <option value="2">Mês</option>
                                        <option value="3">Ano</option>
                                    </select>
                                </div>
                            </div>
                            <div id="tipofiltro" class="form-group row">

                            </div>
                            <div hidden id="divFunc" class="form-group row">
                                <label for="produto" class="col-sm-4 col-form-label">Funcionário:</label>
                                <div class="input-group mb-3 col-sm-8">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><i class="fas fa-user"></i></span>
                                    </div>
                                    <select id="func" class="form-control" name="func"></select>
                                </div>
                            </div>
                            <div hidden id="divVehi" class="form-group row">
                                <label for="vehi" class="col-sm-4 col-form-label">Veiculo:</label>
                                <div class="input-group mb-3 col-sm-8">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><i class="fas fa-car"></i></span>
                                    </div>
                                    <select id="vehi" class="form-control" name="vehi" ></select>
                                </div>
                            </div>
                            <br>
                            <div class="text-center">
                                <button type="submit" class="btn btn-primary">Gerar Relatório</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <br><br>
    <script>
        $(document).ready(function(){
        $('#tipo').change(function () {
            let value = $('#tipo').val();
            switch (value) {
                case '1':
                    document.getElementById('divFunc').hidden = false;
                    document.getElementById('divVehi').hidden = true;
                    break;
                case '2':
                    document.getElementById('divFunc').hidden = false;
                    document.getElementById('divVehi').hidden = true;
                    break;
                case '3':
                    document.getElementById('divFunc').hidden = true;
                    document.getElementById('divVehi').hidden = false;
                    break;
            }
        });
        $('#filtrarPor').change(function () {
            let value = $('#filtrarPor').val();
            switch (value) {
                case '1':
                    $('#tipofiltro').html(' ');
                    $('#tipofiltro').html('' +
                        '<label for="start" class="col-sm-4 col-form-label">Dia:</label>\n' +
                        '<div class="input-group mb-3 col-sm-8">'+
                            '<div class="input-group-prepend">'+
                                '<span class="input-group-text"><i class="fas fa-calendar-alt"></i></span></div>'+
                                '<input type="date" class="form-control" name="day">'+
                        '</div>');
                    break;
                case '2':
                    $('#tipofiltro').html(' ');
                    $('#tipofiltro').html('' +
                        '<label for="start" class="col-sm-4 col-form-label">Mês:</label>\n' +
                        '<div class="input-group mb-3 col-sm-8">'+
                            '<div class="input-group-prepend">'+
                                '<span class="input-group-text"><i class="fas fa-calendar-alt"></i></span></div>'+
                                '<input type="month" class="form-control" name="month">'+
                        '</div>');
                    break;
                case '3':
                    $('#tipofiltro').html(' ');
                    $('#tipofiltro').html('' +
                        '<label for="start" class="col-sm-4 col-form-label">Ano:</label>\n' +
                        '<div class="input-group mb-3 col-sm-8">'+
                            '<div class="input-group-prepend">'+
                                '<span class="input-group-text"><i class="fas fa-calendar-alt"></i></span></div>'+
                                '<input type="number" class="form-control" name="year" min="2000" max="2020" value="2019">'+
                        '</div>');
                    break;
            }
        });
            let url = '{{url('')}}';
            let fullUrl = url + '/employees/allfuncs/secretaria/' + '{{encrypt(Auth::user()->sec_id)}}';
            $.ajax({
                url: fullUrl,
                type: "GET",
                dataType: "json",
                success:function(data) {
                    console.log(data);
                    $('#func').html('<option value="{{encrypt(0)}}">Todos</option>');
                    $.each(data, function(idd, value) {
                        document.getElementById('func').innerHTML += '<option value="'+value['reference']+'">'+value['nome']+'</option>';
                    });
                }
            });
            let fullUrl1 = url + '/veiculos/allvehicles/secretaria/' + '{{encrypt(Auth::user()->sec_id)}}';
            $.ajax({
                url: fullUrl1,
                type: "GET",
                dataType: "json",
                success:function(data) {
                    $('#vehi').html('<option value="{{encrypt(0)}}">Todos</option>');
                    $.each(data, function(idd, veiculo) {
                        document.getElementById('vehi').innerHTML += '<option value="'+veiculo['reference']+'">'+veiculo['nome']+'</option>';
                    });
                }
            });
        });


    </script>
@endsection
