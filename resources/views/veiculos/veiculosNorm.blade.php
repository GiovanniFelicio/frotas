@extends('layouts.default')
@section('content')
    <style>
        .modal{
            background-color: rgba(0, 0, 0, 0.5);
            z-index: 15;
        }
        @media(max-width: 794px){
            .add{
                z-index: -1;
            }
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
    <script>
        $(".alert").fadeTo(3200, 800).slideUp(1000, function(){
            $(".alert").slideUp(500);
        });
    </script>
    <div id="conteudo">
        <div class="row">
            <div class="col-md-12 text-right add">
                @if(Auth::user()->level >= 2)
                    <a href="{{route('anexarVeiculo')}}" style="color: white" class="btn btn-info uppercase">
                        <i class="zmdi zmdi-plus"></i>add veículo</a>
                @endif
            </div>
        </div>
        <br>
        <div class="row">
            <div class="col-md-12">
                <h2 class="display-4 text-center">Gerenciador de Veículos</h2>
            </div>
        </div>
        <br><br><br>
        <div class="row">
            <div class="col-lg-12">
                <div class="bgc-white bd bdrs-3 p-20 mB-20">
                    <table style="width: 100%" id="myTable" class="tableStyleGio datatablesStyle display nowrap vehi">
                        <thead>
                        <tr>
                            <th>Nome</th>
                            <th>Placa</th>
                            <th>Sendo Usado</th>
                        </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <script>
        $(document).ready(function () {

           let table = $('#myTable').DataTable({
				"responsive": true,
                "processing": true,
                "serverSide": true,
                "ajax": '{{route("getdataVehicles")}}',
                "columns":[
                    {"data": 'nome'},
                    {"data": 'placa'},
                    {"data": 'status'}

                ],
                "scrollX": true,
                "scrollY": "500px",
                "scrollCollapse": true
            });
        });
    </script>
@endsection
