@extends('adminlte::page')

@section('title', 'Parametros')

@section('footer')
    <!-- Footer theme | No Borrar -->
@stop

@section('css')
    <!-- Estilos para la DataTable -->
    <link rel="stylesheet" href="https://cdn.datatables.net/staterestore/1.1.1/css/stateRestore.dataTables.min.css"/>
    <style>
        tfoot input {
            width: 100% !important;
            padding: 3px !important;
            box-sizing: border-box !important;
            border: none !important;
        }
        .isSearchableInput{
            width: 100% !important;
            border-radius: 0.2rem !important;
            border: 1px solid #ced4da !important;
            background-color: #fff !important;
        }        
        .isSearchable, .isSpace {
            text-align: center !important;
            background-color: white !important;
            border: none !important
        }
        .isSelected{
            background-color: #28a74547 !important;
        }
        #resultadosTable_filter{
            display: inline-block;
            float: right;
        }
    </style>
@stop

@section('js')
    <!-- Scripts para la DataTable -->
    <script type="text/javascript" src="https://cdn.datatables.net/fixedheader/3.2.4/js/dataTables.fixedHeader.min.js"></script>
    <script type="text/javascript" src="https://cdn.datatables.net/colreorder/1.5.6/js/dataTables.colReorder.min.js"></script>
    <script type="text/javascript" src="https://cdn.datatables.net/staterestore/1.1.1/js/dataTables.stateRestore.min.js"></script>
    <script>
        $(document).ready(function(){
            //Footer DataTable
            $('#resultadosTable tfoot th.isSearchable').each(function () {
                var title = $(this).text();
                $(this).html('<input type="text" class="isSearchableInput" placeholder="Buscar..." />');
            });

            //Marca de seleccion en las filas
            $('#resultadosTable tbody').on('click', 'tr', function () {
                $(this).toggleClass('isSelected');
            });

            //Composicion de la DataTable
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

                    //Colocar las busquedas por columnas al inicio de la DataTable
                    var thfootTable = $('#resultadosTable tfoot tr');
                    thfootTable.find('th').each(function(){
                        $(this).css('padding', 8);
                    });
                    $('#resultadosTable thead').prepend(thfootTable);
                },
                //Encabezado Fijo
                fixedHeader: true,

                //Ordenado DataTable
                order: [[0, 'asc']],

                //Reordenar columnas (Drag and Drop)
                colReorder: true,

                //Responsive
                responisve: true,
                autoWidth: false,

                //Botones DataTable
                dom: 'Bfrtip',
                lengthMenu: [
                    [ 10, 25, 50, 100,-1 ],
                    [ '10', '25', '50', '100', 'Todo' ]
                ],
                buttons: [
                    'pageLength',
                    'createState',
                    'savedStates',
                    {
                        extend: 'copy',
                        text: 'Copiar',
                        exportOptions: {
                            columns: [ 0, 1, 2, 3, 4 ]
                        }
                    },
                    {
                        extend: 'csv',
                        text: 'CSV',
                        title: 'parametros',
                        exportOptions: {
                            columns: [ 0, 1, 2, 3, 4 ]
                        }
                    },
                    {
                        extend: 'excel',
                        text: 'Excel',
                        title: 'parametros',
                        exportOptions: {
                            columns: [ 0, 1, 2, 3, 4 ]
                        }
                    },
                    {
                        extend: 'pdf',
                        text: 'PDF',
                        title: 'parametros',
                        exportOptions: {
                            columns: [ 0, 1, 2, 3, 4 ]
                        }
                    },
                    {
                        extend: 'print',
                        text: 'Imprimir',
                        exportOptions: {
                            columns: [ 0, 1, 2, 3, 4 ]
                        }
                    },
                ], 

                //Traduccion DataTable
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
                        },
                        createState:"Crear estado",
                        savedStates: {
                            0: 'Estados',
                            _: 'Estados (%d)'
                        },
                        updateState: 'Actualizar',
                        stateRestore: 'Nuevo estado %d',
                        removeState: 'Eliminar',
                        renameState: 'Cambiar nombre',
                    },
                    stateRestore: {
                        removeSubmit: 'Confirmar',
                        removeConfirm: 'Confirma que quieres eliminar %s.',
                        emptyStates: 'Sin estados',
                        renameButton: 'Cambiar nombre',
                        renameLabel: 'Renombrar a:',
                        renameTitle: 'Cambiar nombre de estado',
                    },
                },
            });

            //Contar filas seleccionadas
            $('#countRows').click(function () {
                Swal.fire(
                    '¡Buen trabajo!',
                    ''+ table.rows('.isSelected').data().length +' filas seleccionadas',
                    'success'
                );
            });

            //Ocultar filas seleccionadas
            $('#deleteRows').click(function () {
                var count = table.rows('.isSelected').data().length;
                table.rows('.isSelected').remove().draw(false);
                Swal.fire(
                    '¡Buen trabajo!',
                    ''+ count +' filas ocultadas',
                    'success'
                );
            });

            //Recargar pagina
            $('#reloadPage').click(function () {
                window.location.reload();
            });

            //Visualizar Columnas
            $('input.toggle-vis').on('change', function (e) {
                var column = table.column($(this).attr('data-column'));
                column.visible(!column.visible());
            });

            //Tooltip
            $(function () {
                $('[data-toggle="tooltip"]').tooltip()
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
            <button type="button" id="countRows" class="btn btn-info" data-toggle="tooltip" data-placement="right" title="Contar Filas seleccionadas"><i class="fas fa-layer-group"></i></button>
            <button type="button" id="deleteRows" class="btn btn-danger" data-toggle="tooltip" data-placement="right" title="Ocultar Filas seleccionadas"><i class="fas fa-layer-group"></i></button>
            <button type="button" id="reloadPage" class="btn btn-warning text-white" data-toggle="tooltip" data-placement="right" title="Recargar"><i class="fas fa-sync"></i></button>

            <div style="display:inline-block;" class="float-right">
                <label>Ver columnas:</label>
                <input class="toggle-vis" type="checkbox" checked="checked" data-column="0"> Nro</input>
                <input class="toggle-vis" type="checkbox" checked="checked" data-column="1"> Variable</input>
                <input class="toggle-vis" type="checkbox" checked="checked" data-column="2"> Descripción</input>
                <input class="toggle-vis" type="checkbox" checked="checked" data-column="3"> Valor</input>
                <input class="toggle-vis" type="checkbox" checked="checked" data-column="4"> Auxiliar</input>
                <input class="toggle-vis" type="checkbox" checked="checked" data-column="5"> Estado</input>
                <input class="toggle-vis" type="checkbox" checked="checked" data-column="6"> Status</input>
                <input class="toggle-vis" type="checkbox" checked="checked" data-column="7"> Acciones</input>
            </div>
        </div>
        <div class="card-body table-responsive">
            <table id="resultadosTable" class="table table-striped">
                <thead class="table-dark">
                    <tr class="text-center">
                        <th>Nro</th>
                        <th>Variable</th>
                        <th>Descripción</th>
                        <th>Valor</th>
                        <th>Auxiliar</th>
                        <th>Estado</th>
                        <th>Status</th>
                        <th class="actionsSize">Acciones</th>
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

                            <td class="text-center">
                                @if($parametro->activa==1)
                                    <span class="text-success" data-toggle="tooltip" data-placement="left" title="Activa"><i class="fas fa-check"></i></span>
                                @else
                                    <span class="text-warning" data-toggle="tooltip" data-placement="left" title="Inactiva"><i class="fas fa-ban"></i></span>
                                @endif
                            </td>

                            <td class="text-center">
                                @if($parametro->deleted_at=='')
                                    <span class="text-success" data-toggle="tooltip" data-placement="left" title="OK"><i class="fas fa-check"></i></span>
                                @else
                                    <span class="text-danger" data-toggle="tooltip" data-placement="left" title="Borrada"><i class="fas fa-trash-alt"></i></span>
                                @endif
                            </td>

                            <td class="text-center">
                                <div class="btn-group">
                                    <button type="button" class="btn btn-success" data-toggle="tooltip" data-placement="left" title="Ver"><i class="fas fa-eye"></i></button>

                                    <button type="button" class="btn btn-info" data-toggle="tooltip" data-placement="left" title="Editar"><i class="fas fa-edit"></i></button>

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
                                </div>
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
                        <th colspan="3" class="isSpace"></th>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
@stop