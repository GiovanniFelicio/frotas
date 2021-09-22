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
        <div class="col-md-12 text-right">
            @if(Auth::user()->level >= App\Status::ADMINISTRADOR)
                <a href="{{route('adcSetor')}}" style="color: white" class="btn btn-info uppercase">
                    <i class="zmdi zmdi-plus"></i>add setor</a>
            @endif
        </div>
    </div>
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
            <div class="bgc-white bd bdrs-3 p-20 mB-20">
                <table style="width: 100%" id="myTable" class="tableStyleGio datatablesStyle func display nowrap">
                    <thead>
                    <tr>
                        <th>Nome</th>
                        @if(Auth::user()->level >= App\Status::ADMINISTRADOR)
                            <th>Secretaria/Autarquia</th>
                        @endif
                        <th>Opções</th>
                    </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div>
    <!-- Modal -->
    <div class="modal fade" id="myModal" tabindex="-1" role="dialog" data-backdrop="false" style="background-color: rgba(0, 0, 0, 0.5);">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"> DELETAR </h5>
                    <button type="button" class="close" data-dismiss="modal">
                        <span>X</span>
                    </button>
                </div>
                <div class="modal-body">
                    Deseja deletar este Funcionario mesmo ?
                </div>
                <div class="modal-footer">
                    <input type="hidden" value="" name="idFunc" class="idFunc">
                    <button type="button" class="btn btn-danger" data-dismiss="modal">NAO</button>
                    <button type="button" class="btn btn-primary yes">SIM</button>
                </div>
            </div>
        </div>
    </div>
    <script>
        $(document).ready(function () {
            let idEncrypt;

            $('.func').on('click', '.del', function () {

                idEncrypt = $(this).data('id');
                $('#myModal').modal('show');
            });

            $('.yes').on('click', function () {
                window.location.replace("{{url('/setores/delete')}}" + '/'+idEncrypt);
            });
            let table = $('#myTable').DataTable({
				"responsive": true,
                "processing": true,
                "serverSide": true,
                "ajax": '{{route('getdataSectors')}}',
                "columns":[
                    {"data": 'name'},
                    @if(Auth::user()->level >= App\Status::ADMINISTRADOR)
                        {"data": 'secretaria'},
                    @endif
                    {"data": 'action'}
                ],
                buttons: [
                    {
                        text: 'My button',
                        action: function ( e, dt, node, config ) {
                            alert( 'Button activated' );
                        }
                    }
                ],
                "scrollX": true,
                "scrollY": "500px",
                "scrollCollapse": true,
            });
        });
    </script>

@endsection

