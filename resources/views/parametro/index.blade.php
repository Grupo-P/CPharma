@extends('adminlte::page')

@section('title', 'Parametros')

@section('footer')
    <!-- Footer theme -->
@stop

@section('css') 
@stop

@section('js')   
    <script>
        $(function () {
            $('[data-toggle="tooltip"]').tooltip()
        })

        $(document).ready(function(){
            $('#parametros').DataTable({
                dom: 'Bfrtip',
                responisve: true,
                autoWidth: false,                
                lengthMenu: [
                    [ 10, 25, 50, 100,-1 ],
                    [ '10', '25', '50', '100', 'Todo' ]
                ],                
                buttons: [  
                    'pageLength',
                    {
                        extend: 'copy',
                        text: 'Copiar',
                    },
                    {
                        extend: 'csv',
                        text: 'CSV',
                        title: 'parametros'
                    },
                    {
                        extend: 'excel',
                        text: 'Excel',
                        title: 'parametros'
                    },
                    {
                        extend: 'pdf',
                        text: 'PDF',
                        title: 'parametros'
                    },
                    {
                        extend: 'print',
                        text: 'Imprimir',
                    },
                ], 
                language:{
                    "search":"Buscar:",
                    "lengthMenu": "Mostrar _MENU_ registros por página",
                    "zeroRecords": "No se encontró nada, lo siento",
                    "info": "Mostrando la página _PAGE_ de _PAGES_",
                    "infoEmpty": "No hay registros disponibles",
                    "infoFiltered": "(Filtrado de _MAX_ registros totales)",
                    "paginate":{
                        "previous":"Anterior",
                        "next":"Siguiente",
                    },
                    buttons: {
                        pageLength: {
                            _: "Ver %d filas",
                            '-1': "Ver todo"
                        },
                        copyTitle: 'Añadido al portapapeles',                        
                        copySuccess: {
                            _: '%d filas copiadas',
                            1: '1 linea copiada'
                        }
                    }                    
                },              
            });
        });
    </script>
@stop

@section('content_header')
    <h1>Parametros</h1>
    <nav style="--bs-breadcrumb-divider: '>';" aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="/dashboard">Dashboard</a></li>
            <li class="breadcrumb-item active" aria-current="page">Parametros</li>
        </ol>
    </nav>
@stop

@section('content')
    <div class="card">
        <div class="card-header">            
            <button type="button" class="btn btn-success" data-toggle="tooltip" data-placement="right" title="Crear Parametro"><i class="fas fa-plus"></i></button>
        </div>
        <div class="card-body table-responsive">
            <table id="parametros" class="table table-striped table-bordered">
                <thead class="table-dark">
                    <tr class="text-center">
                        <th>Nro</th>
                        <th>Variable</th>
                        <th>Descripción</th>
                        <th>Valor</th>
                        <th>Auxiliar</th>                        
                        <th>Estado</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($parametros as $parametro)
                        <tr>
                            <td>{{ $parametro->id }}</td>
                            <td>{{ $parametro->nombre }}</td>
                            <td>{{ $parametro->descripcion }}</td>
                            <td>{{ $parametro->valor }}</td>
                            <td>{{ $parametro->auxiliar }}</td>                            

                            <td class="text-center text-lg">
                                @if($parametro->activa==1)
                                    <span class="text-success" data-toggle="tooltip" data-placement="left" title="Activa"><i class="fas fa-check"></i></span>
                                @else
                                    <span class="text-warning" data-toggle="tooltip" data-placement="left" title="Inactiva"><i class="fas fa-ban"></i></span>
                                @endif
                            </td>

                            <td class="text-center text-lg">
                                @if($parametro->deleted_at=='')
                                    <span class="text-success" data-toggle="tooltip" data-placement="left" title="OK"><i class="fas fa-check"></i></span>
                                @else
                                    <span class="text-danger" data-toggle="tooltip" data-placement="left" title="Borrada"><i class="fas fa-ban"></i></span>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@stop