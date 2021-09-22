@extends('layouts.default')
@section('content')
    <style>
        .modal{
            background-color: rgba(0, 0, 0, 0.5);
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
        $("#menuButton").on('click', function () {
            $(".button").css('z-index', '-1');
            $(".header-desktop").css('z-index', '2');
        });
    </script>
    <br>
    <div class="row">
        <div class="col-md-12">
            <h1 class="display-4 text-center">Funcionários</h1>
        </div>
    </div>
    <br>
    <div class="row">
        <div class="col-lg-12">
            <br><br>
            <div>
                <table style="width: 100%" id="myTable" class="tableStyleGio datatablesStyle display nowrap func">
                    <thead>
                        <tr>
                            <th>Nome</th>
                            <th>E-mail</th>
                            <th>Setor</th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div>
    <script>
        $(document).ready(function () {

            let table = $('#myTable').DataTable({
                "responsive": true,
                "processing": true,
                "serverSide": true,
                "ajax": '{{route('getdataFunc')}}',
                "columns":[
                    {"data": 'nome'},
                    {"data": 'email'},
                    {"data": 'sector'}
                ],
                "lengthMenu": [[10, 25, 100, -1], [10, 25, 100, "All"]],
                "scrollX": true,
                "scrollY": "500px",
                "scrollCollapse": true
            });
        });
    </script>
@endsection
