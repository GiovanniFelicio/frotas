@extends('layouts.default')
@section('content')
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
                    <h1 class="text-center">Setor/Departamento</h1>
                </div>
                <hr>
                <div class="login-form">
                    <div class="col-12">
                        <form method="POST" action="{{route('createSector')}}">
                            @csrf
                            <div class="form-group row">
                                <label for="produto" class="col-sm-2 col-form-label">NOME:</label>
                                <div class="col-sm-10">
                                    <div class="input-group mb-3">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text" id="basic-addon1"><i class="fas fa-chart-area"></i></span>
                                        </div>
                                        <input id="nameSector" placeholder="Ex: Fábrica de Inovação" class="form-control" name="nameSector">
                                    </div>
                                    <small class="form-text text-muted">Coloque o nome do setor</small>
                                </div>
                            </div>
                            @if(Auth::user()->level >= App\Status::MASTER)
                                <div class="form-group row">
                                    <label for="money" class="col-sm-2 col-form-label">Sec/Aut:</label>
                                    <div class="col-sm-10">
                                        <div class="input-group mb-3">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text" id="basic-addon1"><i class="fas fa-chart-bar"></i></span>
                                            </div>
                                            <select name="secretaria" class="form-control">
                                                <option selected disabled>Selecione a Secretaria</option>
                                                @foreach($secretarias as $secretaria)
                                                    <option value="{{encrypt($secretaria->id)}}">{{$secretaria->name}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <small class="form-text text-muted">Selecione a Secretaria que o setor irá pertencer</small>
                                    </div>
                                </div>
                            @endif
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

