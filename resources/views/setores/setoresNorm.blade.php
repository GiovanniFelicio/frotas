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
    <br>
    <div class="row">
        <div class="col-md-12">
            <h2 class="text-center">Setores/Departamentos</h2>
        </div>
    </div>

    <br><br>
    <div class="row">
        <div class="col-lg-12">
            <br><br>
            <table style="width: 100%" id="myTable" class="table100 ver1 datatablesStyle func display nowrap table table-borderless table-striped table-earning">
                <thead>
                <tr>
                    <th>Nome</th>
                    <th>Opções</th>
                </tr>
                </thead>
            </table>
        </div>
    </div>
    <script>
        $(document).ready(function () {
            let table = $('#myTable').DataTable({
				"responsive": true,
                "processing": true,
                "serverSide": true,
                "ajax": '{{route('getdataSectors')}}',
                "columns":[
                    {"data": 'nameSector'},
                    {"data": 'action'}
                ],
                "scrollX": true,
                "scrollY": "500px",
                "scrollCollapse": true
            });
        });
    </script>

@endsection

