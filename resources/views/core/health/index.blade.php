@php
    namespace App\Models;
    use App\Models\Core\Favoritos;
@endphp

@extends('adminlte::page')

@section('title', 'Histórico de estados')

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
            $('#resultadosTable tbody').on('click', 'tr td.isSelectable', function () {
                $(this).parent().toggleClass('isSelected');
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

                //Ordenado DataTable
                order: [[0, 'desc']],

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
                    {
                        extend: 'excel',
                        text: '<i class="fa fa-file-excel" data-toggle="tooltip" data-placement="right" title="Excel"></i>',
                        title: 'healthHistory',
                        exportOptions: {
                            columns: [ 0, 1, 2, 3 ]
                        },
                        className : 'btn btn-success mr-1 rounded'
                    },
                    {
                        extend: 'csv',
                        text: '<i class="fa fa-file-csv" data-toggle="tooltip" data-placement="right" title="CSV"></i>',
                        title: 'healthHistory',
                        exportOptions: {
                            columns: [ 0, 1, 2, 3, 4 ]
                        },
                        className : 'btn btn-info mr-1 rounded'
                    },
                    {
                        extend: 'print',
                        text: '<i class="fa fa-print text-white" data-toggle="tooltip" data-placement="right" title="Imprimir"></i>',
                        exportOptions: {
                            columns: [ 0, 1, 2, 3, 4 ]
                        },
                        className : 'btn btn-warning mr-1 rounded'
                    },
                    {
                        extend: 'pdf',
                        text: '<i class="fa fa-file-pdf" data-toggle="tooltip" data-placement="right" title="PDF"></i>',
                        title: 'healthHistory',
                        exportOptions: {
                            columns: [ 0, 1, 2, 3, 4 ]
                        },
                        className : 'btn btn-danger mr-1 rounded'
                    },
                    {
                        extend: 'copy',
                        text: '<i class="fa fa-copy" data-toggle="tooltip" data-placement="right" title="Copiar"></i>',
                        exportOptions: {
                            columns: [ 0, 1, 2, 3, 4 ]
                        },
                        className : 'btn btn-secondary mr-1 rounded'
                    },
                    {
                        extend: 'createState',
                        text: '<i class="fa fa-save" data-toggle="tooltip" data-placement="right" title="Guardar estado"></i>',
                        className : 'btn btn-dark mr-1 rounded',
                    },
                    {
                        extend: 'savedStates',
                        className : 'btn btn-dark mr-1 rounded',
                    },
                    {
                        extend: 'pageLength',
                        className : 'btn btn-dark mr-1 rounded',
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
                            _: '<i class="fa fa-th-list" data-toggle="tooltip" data-placement="right" title="Cantidad de filas"></i> (%d)',
                            '-1': "Ver todo"
                        },
                        copyTitle: 'Añadido al portapapeles',
                        copySuccess: {
                            _: '%d filas copiadas',
                            1: '1 linea copiada'
                        },
                        createState:"Crear estado",
                        savedStates: {
                            0: '<i class="fa fa-folder-open" data-toggle="tooltip" data-placement="right" title="Estados"></i>',
                            _: '<i class="fa fa-folder-open" data-toggle="tooltip" data-placement="right" title="Estados"></i> (%d)'
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
                var count = table.rows('.isSelected').data().length;
                if(count>0){
                    Swal.fire(
                        '¡Buen trabajo!',
                        ''+count+' filas seleccionadas',
                        'success'
                    );
                }else{
                    Swal.fire(
                        '¡Uups!',
                        'No hay filas seleccionadas',
                        'error'
                    );
                }
            });

            //Ocultar filas seleccionadas
            $('#deleteRows').click(function () {
                var count = table.rows('.isSelected').data().length;
                if(count>0){
                    Swal.fire({
                    title: 'Estas seguro?',
                    text: "Quieres ocultar "+count+" filas",
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonColor: '#28a745',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Si, ocultarlas!',
                    cancelButtonText: 'Cancelar',
                    backdrop: `rgba(0,0,0,0.4)`,
                    }).then((result) => {
                        if (result.isConfirmed) {
                            table.rows('.isSelected').remove().draw(false);
                            Swal.fire(
                                '¡Buen trabajo!',
                                ''+ count +' filas ocultadas',
                                'success'
                            );
                        }
                    });
                }else{
                    Swal.fire(
                        '¡Uups!',
                        'No hay filas seleccionadas',
                        'error'
                    );
                }
            });

            //Recargar pagina
            $('#reloadPage').click(function () {
                Swal.fire({
                    title: 'Estas seguro?',
                    text: "Se descartaran todos los filtros",
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonColor: '#28a745',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Si, recargar!',
                    cancelButtonText: 'Cancelar',
                    backdrop: `rgba(0,0,0,0.4)`,
                    }).then((result) => {
                        if (result.isConfirmed) {
                            window.location.reload();
                        }
                    });                
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
    <!-- Seccion de favoritos -->
        @include('favoritos')
    <!-- Seccion de favoritos -->

    <!-- Icono de favorito en titulo-->
    @php
        $id_favorito = null;
        $icono_favorito = 'far fa-star';
        $favorito = Favoritos::validar_favorito('core.healthHistory',auth()->user()->id);
        if($favorito){
            $id_favorito = $favorito[0]['id'];
            $icono_favorito = 'fas fa-star';
        }
    @endphp
    <h1>
        <form action="{{route('core.favoritos.gestionar')}}" method="POST">
            <input type="hidden" name="id" value="{{$id_favorito}}">
            <input type="hidden" name="nombre" value="Histórico de estados">
            <input type="hidden" name="ruta" value="core.healthHistory">
            <input type="hidden" name="user_favoritos" value="{{auth()->user()->id}}">
            @csrf
            <button type="submit" style="display:inline-block; border:0px; background-color: transparent;">
                <i class="{{$icono_favorito}} text-warning"></i>
            </button>
            Histórico de estados
        </form>
    </h1>    
    <nav style="--bs-breadcrumb-divider: '>';" aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="/dashboard">Dashboard</a></li>
            <li class="breadcrumb-item active" aria-current="page">Histórico de estados</li>
        </ol>
    </nav>
@stop

@section('content')

    @if(session()->has('message'))
        <div class="alert alert-light alert-dismissible fade show text-dark shadow" role="alert">            
            <strong><i class="fa fa-exclamation"></i>&nbsp;&nbsp;{{session('message')}}</strong>
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif

    <div class="card shadow">
        <div class="card-header">            
            <button type="button" id="countRows" class="btn btn-info" data-toggle="tooltip" data-placement="right" title="Contar Filas seleccionadas"><i class="fas fa-layer-group"></i></button>
            <button type="button" id="reloadPage" class="btn btn-warning text-white" data-toggle="tooltip" data-placement="right" title="Recargar"><i class="fas fa-sync"></i></button>
            <button type="button" id="deleteRows" class="btn btn-danger" data-toggle="tooltip" data-placement="right" title="Ocultar Filas seleccionadas"><i class="fas fa-eye-slash"></i></button>

            <div style="display:inline-block;" class="float-right">
                <label>Ver columnas:</label>
                <input class="toggle-vis" type="checkbox" checked="checked" data-column="0"> Nro</input>
                <input class="toggle-vis" type="checkbox" checked="checked" data-column="1"> Nombre</input>
                <input class="toggle-vis" type="checkbox" checked="checked" data-column="2"> Estatus</input>
                <input class="toggle-vis" type="checkbox" checked="checked" data-column="3"> Mensaje</input>
                <input class="toggle-vis" type="checkbox" checked="checked" data-column="4"> Fecha</input>                
            </div>
        </div>
        <div class="card-body table-responsive">
            <table id="resultadosTable" class="table table-striped">
                <thead class="table-dark">
                    <tr class="text-center">
                        <th>Nro</th>
                        <th>Nombre</th>
                        <th>Estatus</th>
                        <th>Mensaje</th>
                        <th>Fecha</th>                        
                    </tr>
                </thead>
                <tbody>
                    @foreach($HealthHistories as $HealthHistory)
                        <tr>
                            <td class="isSelectable">{{ $HealthHistory['id'] }}</td>
                            <td>{{ $HealthHistory['check_label'] }}</td>

                            <td class="text-center">
                                @if($HealthHistory['status']=='ok')
                                    <span class="text-success" data-toggle="tooltip" data-placement="left" title="Activa">Ok</span>
                                @elseif($HealthHistory['status']=='failed')
                                    <span class="text-danger" data-toggle="tooltip" data-placement="left" title="Inactiva">Error</span>
                                @endif
                            </td>

                            <td>{{ $HealthHistory['notification_message'] }}</td>                            
                            <td>{{ $HealthHistory['updated_at'] }}</td>
                        </tr>
                    @endforeach
                </tbody>
                <tfoot>
                    <tr>
                        <th class="isSearchable">nro</th>
                        <th class="isSearchable">Nombre</th>
                        <th class="isSearchable">Estatus</th>
                        <th class="isSearchable">Mensaje</th>
                        <th class="isSearchable">Fecha</th>                        
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
@stop