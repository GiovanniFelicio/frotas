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
        <div class="col-md-12">
            <h2 class="display-4 text-center">Logs</h2>
        </div>
    </div>
    <br><br>
    <div class="row">
        <div class="col-lg-12">
			<div class="bgc-white bd bdrs-3 p-20 mB-20">
                <table style="width: 100%" id="myTable" class="tableStyleGio datatablesStyle nowrap display">
                    <thead>
                        <tr>
                            <th>Funcionário</th>
                            <th>Secretaria/Autarquia</th>
                            <th>Setor</th>
                            <th>Ação</th>
                            <th>Data</th>
                        </tr>
                    </thead>
                    <tfoot></tfoot>
                </table>
			</div>
        </div>
    </div>
    <style>
        .filters input {
            width: 100%;
            padding: 3px;
            box-sizing: border-box;
        }
    </style>
    <script>
        $(document).ready(function () {
            let table = $('#myTable').DataTable({
				"responsive": true,
                "processing": true,
                "serverSide": true,
                "ajax": '{{route('getdataUsersActions')}}',
                "columns":[
                    {"data": 'func'},
                    {"data": 'secretaria'},
                    {"data": 'setor'},
                    {"data": 'action'},
                    {"data": 'data'}
                ],
                "lengthMenu": [[5, 10, 25, 100, -1], [5, 10, 25, 100, "All"]],
                "scrollX":true,
                "scrollY": "800px",
                "scrollCollapse": true
            });
        });
    </script>
@endsection