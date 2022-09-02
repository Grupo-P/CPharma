@extends('adminlte::page')

@section('title', 'Parametros')

@section('footer')
    <!-- Footer theme | No Borrar -->
@stop

@section('css')
    <style>        
        tfoot input {
            width: 100% !important;
            padding: 3px !important;
            box-sizing: border-box !important;
            border: none !important;
        }
        .isSearchableInput{
            width: 100% !important;
            border-radius: 5px !important;
        }        
        .isSearchable{
            text-align: center !important;            
        }
    </style>
@stop

@section('js')   
    <script>
        //Tooltip
        $(function () {
            $('[data-toggle="tooltip"]').tooltip()
        })

        $(document).ready(function(){
            //Footer tabla
            $('#resultadosTable tfoot th.isSearchable').each(function () {
                var title = $(this).text();
                $(this).html('<input type="text" class="isSearchableInput" placeholder="Buscar ' + title + '..." />');
            });

            //Busqueda por columnas
            var table = $('#resultadosTable').DataTable({
                initComplete: function () {
                this.api()
                    .columns()
                    .every(function () {
                        var that = this;
                        $('input', this.footer()).on('keyup change clear', function () {                            
                            if (that.search() !== this.value) {
                                that.search(this.value).draw();
                            }
                        });
                    });

                    var thfootTable = $('#resultadosTable tfoot tr');
                    thfootTable.find('th').each(function(){
                        $(this).css('padding', 8);
                    });
                    $('#resultadosTable thead').append(thfootTable);                    
                },
                //Ordenado Tabla
                order: [[0, 'asc']],  
                //Buscar en la tabla - Enter
                /*search: {
                    return: true,
                },*/
                //Responsive
                responisve: true,
                autoWidth: false,                                         
                //Botones tabla
                dom: 'Bfrtip',
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
                //Traduccion Tabla
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
                    decimal: ',',
                    thousands: '.',
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
            <table id="resultadosTable" class="table table-striped table-bordered">
                <thead class="table-dark">
                    <tr class="text-center">
                        <th>Nro</th>
                        <th>Variable</th>
                        <th>Descripción</th>
                        <th>Valor</th>
                        <th>Auxiliar</th>
                        <th>Estado</th>
                        <th>Status</th>
                        <th>Acciones</th>
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
                                    <span class="text-danger" data-toggle="tooltip" data-placement="left" title="Borrada"><i class="fas fa-trash-alt"></i></span>
                                @endif
                            </td>

                            <td class="text-center">                                
                                <button type="button" class="btn btn-primary" data-toggle="tooltip" data-placement="left" title="Editar"><i class="fas fa-edit"></i></button>                                

                                @if($parametro->activa==1)
                                    <button type="button" class="btn btn-warning text-white" data-toggle="tooltip" data-placement="left" title="Inactivar"><i class="fas fa-ban"></i></button>
                                @elseif($parametro->activa==0)
                                    <button type="button" class="btn btn-success" data-toggle="tooltip" data-placement="left" title="Activar"><i class="fas fa-undo"></i></button>
                                @endif

                                @if($parametro->deleted_at=='')
                                    <button type="button" class="btn btn-danger" data-toggle="tooltip" data-placement="left" title="Borrar"><i class="fas fa-trash-alt"></i></button>
                                @elseif($parametro->deleted_at!='')
                                    <button type="button" class="btn btn-success" data-toggle="tooltip" data-placement="left" title="Restaurar"><i class="fas fa-trash-restore-alt"></i></button>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
                <tfoot>
                    <tr>
                        <th class="isSearchable">nro</th>
                        <th class="isSearchable">variable</th>
                        <th class="isSearchable">descripción</th>
                        <th class="isSearchable">valor</th>
                        <th class="isSearchable">auxiliar</th>
                        <th colspan="3"></th>                    
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
@stop