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
                    <h1 class="text-center">Veículo</h1>
                </div>
                <hr>
                @if(isset($errors) && count($errors) > 0)
                    <div class="alert alert-danger">
                        @foreach ($errors->all() as $error)
                            <p>{{$error}}</p>   
                        @endforeach
                    </div>
                @endif
                <div class="login-form">
                    <div class="col-12">
                        <form method="POST" action="{{route('createVeiculo')}}">
                            @csrf
                            <div class="form-group row">
                                <label for="produto" class="col-sm-2 col-form-label">NOME:</label>
                                <div class="col-sm-10">
                                    <div class="input-group mb-3">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text" id="basic-addon1"><i class="fas fa-car"></i></span>
                                        </div>
                                        <input id="produto" placeholder="Ex: Ford Ka" class="form-control" name="nomeVeiculo">
                                    </div>
                                    <small class="form-text text-muted">Coloque o nome do veículo</small>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="money" class="col-sm-2 col-form-label">PLACA:</label>
                                <div class="col-sm-10">
                                    <div class="input-group mb-3">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text" id="basic-addon1"><i class="fas fa-pager"></i></span>
                                        </div>
                                        <input id="money" placeholder="Ex: ABC1234" class="form-control" name="placa">
                                    </div>
                                    <small class="form-text text-muted">Coloque a placa do Veículo</small>
                                </div>
                            </div>
							@if(Auth::user()->level >= 3)
								<div class="form-group row">
									<label for="money" class="col-sm-2 col-form-label">SEC/AUT:</label>
									<div class="col-sm-10">
										<div class="input-group mb-3">
											<div class="input-group-prepend">
												<span class="input-group-text" id="basic-addon1"><i class="fas fa-chart-bar"></i></span>
											</div>
											<select id="secretaria" name="secretaria" required class="form-control" onchange="ajax()">
												<option selected>Selecione a Sec/Aut</option>
												@foreach($secretarias as $secretaria)
													<option value="{{encrypt($secretaria->id)}}">{{$secretaria->name}}</option>
												@endforeach
											</select>
										</div>
										<small class="form-text text-muted">Selecione a Secretaria</small>
									</div>
								</div>
                            @endif
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
