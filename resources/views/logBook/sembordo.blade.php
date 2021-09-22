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
    <div class="container">
        <div class="row align-items-start">
            <div class="col"><hr></div>
          </div>
        <div class="row align-items-center">
            <div class="col">
                <h1 class="display-4 text-center text-danger"> {{$mensagem}}</h1>
            </div>
        </div>
        <div class="row align-items-end">
            <div class="col"><hr></div>
        </div>
    </div>
    
@endsection
