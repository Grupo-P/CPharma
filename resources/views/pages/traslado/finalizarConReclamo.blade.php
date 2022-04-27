@extends('layouts.model')

@section('title')
    Finalizar traslado con reclamo
@endsection

@section('content')
    <!-- Modal Guardar -->
    @if (session('Error'))
        <div aria-hidden="true" aria-labelledby="exampleModalCenterTitle" class="modal fade" id="exampleModalCenter" role="dialog" tabindex="-1">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title text-danger" id="exampleModalCenterTitle">
                            <i class="fas fa-exclamation-triangle text-danger">
                            </i>
                            {{ session('Error') }}
                        </h5>

                        <button aria-label="Close" class="close" data-dismiss="modal" type="button">
                            <span aria-hidden="true">
                                ×
                            </span>
                        </button>
                    </div>

                    <div class="modal-body">
                        <h4 class="h6">
                            El pago bancario no fue almacenado, ocurrió un error
                        </h4>
                    </div>

                    <div class="modal-footer">
                        <button class="btn btn-outline-success" data-dismiss="modal" type="button">
                            Aceptar
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @endif

    <h1 class="h5 text-info">
        <i class="fas fa-people-carry"></i>
        Finalizar traslado con reclamo
    </h1>

    <hr class="row align-items-start col-12">

    <a class="btn btn-outline-info btn-sm" href="/traslado">
        <i class="fa fa-reply"></i>
        Regresar
    </a>

    <form action="/traslado/finalizarConReclamo" class="mt-3" method="POST">
        @csrf

        <fieldset>
            <table class="table table-borderless table-striped">
                <thead class="thead-dark">
                    <tr>
                        <th>#</th>
                        <th>Ajuste</th>
                        <th>Fecha ajuste</th>
                        <th>Fecha traslado</th>
                        <th>Sede destino</th>
                        <th>Unidades</th>
                        <th>Bultos</th>
                        <th>SKU</th>
                        <th>Total Bs.S</th>
                        <th>Total $</th>
                        <th>Estado</th>
                        <th>Días en traslado</th>
                    </tr>
                </thead>

                <tbody>
                    <tr>
                        <?php
                            include(app_path().'\functions\config.php');
                            include(app_path().'\functions\functions.php');
                            include(app_path().'\functions\querys_mysql.php');
                            include(app_path().'\functions\querys_sqlserver.php');

                            $sede = FG_Mi_Ubicacion();

                            $conn = FG_Conectar_Smartpharma($sede);

                            $totalUnidades = 0;

                            if($traslado->estatus=='ENTREGADO'){
                                $Dias = FG_Rango_Dias($traslado->fecha_traslado,$traslado->updated_at);
                            }
                            else{
                                $Dias = FG_Rango_Dias($traslado->fecha_traslado,date('Y-m-d H:i:s'));
                            }

                            if ($traslado->estatus == 'PROCESADO') {
                                $abiertas = $abiertas + 1;
                            }

                            if ($traslado->estatus == 'PROCESADO' && $Dias < 3) {
                                $tres = $tres + 1;
                            }

                            $connCPharma = FG_Conectar_CPharma();
                            $sql = MySQL_Buscar_Traslado_Detalle($traslado->numero_ajuste);
                            $result = mysqli_query($connCPharma,$sql);

                            $Total_Cantidad = 0;
                            $Total_Impuesto_Bs = 0;
                            $Total_Impuesto_Usd = 0;
                            $Total_Bs = 0;
                            $Total_Usd = 0;

                            while($row = $result->fetch_assoc()) {
                                $Total_Cantidad += floatval($row['cantidad']);
                                $Total_Impuesto_Bs += floatval($row['total_imp_bs']);
                                $Total_Impuesto_Usd += floatval($row['total_imp_usd']);
                                $Total_Bs += floatval($row['total_bs']);
                                $Total_Usd += floatval($row['total_usd']);
                            }
                            mysqli_close($connCPharma);

                            $Total_Bs = number_format ($Total_Bs,2,"," ,"." );
                            $Total_Usd = number_format ($Total_Usd,2,"," ,"." );

                            $totalUnidades = $totalUnidades + $Total_Cantidad;

                            $primero = $traslado->detalle[0]->descripcion;
                            $ultimo = $traslado->detalle[count($traslado->detalle)-1]->descripcion;

                            $sql = "SELECT ComCausaReclamo.Id, ComCausaReclamo.Descripcion FROM ComCausaReclamo WHERE ComCausaReclamo.accion = 2";

                            $result = sqlsrv_query($conn, $sql);

                            while ($row = sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC)) {
                                $causas[] = $row;
                            }
                        ?>

                        <td>{{ $traslado->id }}</td>
                        <td>{{ $traslado->numero_ajuste }}</td>
                        <td>{{ $traslado->fecha_ajuste }}</td>
                        <td>{{ $traslado->fecha_traslado }}</td>
                        <td>{{ $traslado->sede_destino }}</td>
                        <td>{{ $Total_Cantidad }}</td>
                        <td>{{ $traslado->bultos+$traslado->bultos_refrigerados+$traslado->bultos_fragiles }}</td>
                        <td>{{ $traslado->detalle->count() }}</td>
                        <td>{{ $Total_Bs }}</td>
                        <td>{{ $Total_Usd }}</td>
                        <td>{{ $traslado->estatus }}</td>
                        <td>{{ $Dias }}</td>

                        <input type="hidden" name="ajuste" value="{{ $traslado->numero_ajuste }}">
                        <input type="hidden" name="traslado" value="{{ $traslado->id }}">
                        <input type="hidden" name="finalizarConReclamo" value="1">
                    </tr>
                </tbody>
            </table>

            <table class="mt-5 table table-borderless table-striped">
                <thead class="thead-dark">
                    <tr>
                        <th>Código interno</th>
                        <th>Código barra</th>
                        <th>Descripción</th>
                        <th>Costo Bs.S</th>
                        <th>Costo $</th>
                        <th>Reclamo</th>
                        <th>Cantidad</th>
                    </tr>
                </thead>

                <tbody>
                    @foreach($traslado->detalle as $detalle)
                        <input type="hidden" name="reclamos[{{ $loop->index }}][codigo_barra]" value="{{ $detalle->codigo_barra }}">

                        <tr>
                            <td class="text-center">{{ $detalle->codigo_interno }}</td>
                            <td class="text-center">{{ $detalle->codigo_barra }}</td>
                            <td class="text-center">{{ $detalle->descripcion }}</td>
                            <td class="text-center">{{ $detalle->costo_unit_bs_sin_iva }}</td>
                            <td class="text-center">{{ $detalle->costo_unit_usd_sin_iva }}</td>

                            <td class="text-center">
                                <select name="reclamos[{{ $loop->index }}][causa]" class="form-control">
                                    <option value=""></option>

                                    @foreach($causas as $causa)
                                        <option value="{{ $causa['Descripcion'] }}">{{ FG_Limpiar_Texto($causa['Descripcion']) }}</option>
                                    @endforeach
                                </select>
                            </td>

                            <td class="text-center">
                                <input type="number" name="reclamos[{{ $loop->index }}][cantidad]" max="{{ $detalle->cantidad }}" class="form-control">
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

            <input class="btn btn-outline-success btn-md" type="submit" value="Guardar">
        </fieldset>
    </form>

    <script>
        $(document).ready(function(){
            $('[data-toggle="tooltip"]').tooltip();
        });
    </script>
@endsection
