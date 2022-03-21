@extends('layouts.contabilidad')


@section('title')
    Reporte
@endsection


@section('scriptsHead')
    <script>
        function mostrar_ocultar(that, elemento) {
            if (that.checked) {
                return $('.' + elemento).show();
            }

            return $('.' + elemento).hide();
        }

        campos = ['fecha_hora', 'tipo', 'nro_movimiento', 'moneda_base', 'moneda_iva', 'monto_base', 'monto_iva', 'monto_retencion_deuda_1', 'monto_retencion_deuda_2', 'monto_retencion_iva', 'comentario', 'conciliado', 'operador', 'estado'];

        function mostrar_todas(that) {
            if (that.checked) {
                for (var i = campos.length - 1; i >= 0; i--) {
                    $('.' + campos[i]).show();
                    $('[name='+campos[i]+']').prop('checked', true);
                }
            } else {
                for (var i = campos.length - 1; i >= 0; i--) {
                    $('.' + campos[i]).hide();
                    $('[name='+campos[i]+']').prop('checked', false);
                }
            }
        }
    </script>
@endsection


@section('content')
    <h1 class="h5 text-info">
        <i class="fas fa-file-invoice">
        </i>
        Movimientos por proveedor
    </h1>

    <hr class="row align-items-start col-12">

    @if($request->get('fechaInicio'))
        <div class="modal fade" id="ver_campos" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
          <div class="modal-dialog" role="document">
            <div class="modal-content">
              <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Mostrar u ocultar columnas</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                </button>
              </div>
              <div class="modal-body">
                <div class="form-group">
                    <input type="checkbox" onclick="mostrar_ocultar(this, 'fecha_hora')" name="fecha_hora" checked>
                    Fecha y hora
                </div>

                <div class="form-group">
                    <input type="checkbox" onclick="mostrar_ocultar(this, 'tipo')" name="tipo" checked>
                    Tipo
                </div>

                <div class="form-group">
                    <input type="checkbox" onclick="mostrar_ocultar(this, 'nro_movimiento')" name="nro_movimiento" checked>
                    Nro. movimiento
                </div>

                <div class="form-group">
                    <input type="checkbox" onclick="mostrar_ocultar(this, 'moneda_base')" name="moneda_base" checked>
                    Moneda base
                </div>

                <div class="form-group">
                    <input type="checkbox" onclick="mostrar_ocultar(this, 'moneda_iva')" name="moneda_iva" checked>
                    Moneda IVA
                </div>

                <div class="form-group">
                    <input type="checkbox" onclick="mostrar_ocultar(this, 'monto_base')" name="monto_base" checked>
                    Monto base
                </div>

                <div class="form-group">
                    <input type="checkbox" onclick="mostrar_ocultar(this, 'monto_iva')" name="monto_iva" checked>
                    Monto IVA
                </div>

                <div class="form-group">
                    <input type="checkbox" onclick="mostrar_ocultar(this, 'monto_retencion_deuda_1')" name="monto_retencion_deuda_1" checked>
                    Monto retención deuda 1
                </div>

                <div class="form-group">
                    <input type="checkbox" onclick="mostrar_ocultar(this, 'monto_retencion_deuda_2')" name="monto_retencion_deuda_2" checked>
                    Monto retención deuda 2
                </div>

                <div class="form-group">
                    <input type="checkbox" onclick="mostrar_ocultar(this, 'monto_retencion_iva')" name="monto_retencion_iva" checked>
                    Monto retención IVA
                </div>

                <div class="form-group">
                    <input type="checkbox" onclick="mostrar_ocultar(this, 'comentario')" name="comentario" checked>
                    Comentario
                </div>

                <div class="form-group">
                    <input type="checkbox" onclick="mostrar_ocultar(this, 'conciliado')" name="conciliado" checked>
                    Conciliado
                </div>

                <div class="form-group">
                    <input type="checkbox" onclick="mostrar_ocultar(this, 'operador')" name="operador" checked>
                    Operador
                </div>

                <div class="form-group">
                    <input type="checkbox" onclick="mostrar_ocultar(this, 'estado')" name="estado" checked>
                    Estado
                </div>

                <div class="form-group">
                    <input type="checkbox" onclick="mostrar_todas(this)" name="Marcar todas" checked>
                    Marcar todas
                </div>
              </div>
              <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
              </div>
            </div>
          </div>
        </div>

        <div class="input-group md-form form-sm form-1 pl-0 CP-stickyBar mb-4">
            <div class="input-group-prepend">
                <span class="input-group-text purple lighten-3" id="basic-text1">
                    <i class="fas fa-search text-white" aria-hidden="true"></i>
                </span>
            </div>

            <input class="form-control my-0 py-1" type="text" placeholder="Buscar..." aria-label="Search" id="myInput" onkeyup="FilterAllTable()" autofocus="autofocus">
        </div>

        <h6 align="center"><a href="" data-toggle="modal" data-target="#ver_campos"><i class="fa fa-eye"></i> Mostrar u ocultar campos<a></h6>

        <h6 align="center">Periodo desde el <b>{{ $fechaInicio }}</b> al <b>{{ $fechaFin }}</b> para el proveedor <b>{{ $proveedor->nombre_proveedor }}</b></h6>

        <table class="table table-striped table-bordered col-12 sortable" id="myTable">
            <thead class="thead-dark">
                <tr>
                    <th nowrap scope="col" class="CP-sticky">#</th>
                    <th nowrap scope="col" class="fecha_hora CP-sticky">Fecha y hora</th>
                    <th nowrap scope="col" class="tipo CP-sticky">Tipo</th>
                    <th nowrap scope="col" class="nro_movimiento CP-sticky">Nro. movimiento</th>
                    <th nowrap scope="col" class="moneda_base CP-sticky">Moneda base</th>
                    <th nowrap scope="col" class="moneda_iva CP-sticky">Moneda IVA</th>
                    <th nowrap scope="col" class="monto_base CP-sticky">Monto base</th>
                    <th nowrap scope="col" class="monto_iva CP-sticky">Monto IVA</th>
                    <th nowrap scope="col" class="monto_retencion_deuda_1 CP-sticky">Monto retención deuda 1</th>
                    <th nowrap scope="col" class="monto_retencion_deuda_2 CP-sticky">Monto retención deuda 2</th>
                    <th nowrap scope="col" class="monto_retencion_iva CP-sticky">Monto retención IVA</th>
                    <th nowrap scope="col" class="comentario CP-sticky">Comentario</th>
                    <th nowrap scope="col" class="conciliado CP-sticky">Conciliado</th>
                    <th nowrap scope="col" class="operador CP-sticky">Operador</th>
                    <th nowrap scope="col" class="estado CP-sticky">Estado</th>
                </tr>
            </thead>

            <tbody>
                @php
                    $cantidad_ajustes = 0;
                    $monto_ajustes = 0;

                    $cantidad_deudas = 0;
                    $monto_deudas = 0;

                    $cantidad_bancarios = 0;
                    $monto_bancarios = 0;

                    $cantidad_efectivo = 0;
                    $monto_efectivo = 0;

                    $cantidad_reclamos = 0;
                    $monto_reclamos = 0;

                    $cantidad_total = 0;
                    $monto_total = 0;
                @endphp

                @foreach($movimientos as $movimiento)
                    @php
                        $fecha = date_create($movimiento->fecha);

                        if ($movimiento->tipo == 'Ajuste') {
                            $cantidad_ajustes = $cantidad_ajustes + 1;
                            $monto_ajustes = $monto_ajustes + $movimiento->monto;
                            $monto_total = $monto_total + $movimiento->monto;
                            $monto = $movimiento->monto;
                            $monto_iva = isset($movimiento->iva) ? $movimiento->iva : '';
                            $monto_retencion_deuda_1 = isset($movimiento->retencion_deuda_1) ? $monto * ($movimiento->retencion_deuda_1 / 100) : '';
                            $monto_retencion_deuda_2 = isset($movimiento->retencion_deuda_2) ? $monto * ($movimiento->retencion_deuda_2 / 100) : '';
                            $monto_retencion_iva = isset($movimiento->retencion_iva) ? $monto_iva * ($movimiento->retencion_iva / 100) : '';
                        }

                        if ($movimiento->tipo == 'Deudas') {
                            $cantidad_deudas = $cantidad_deudas + 1;
                            $monto_deudas = $monto_deudas + $movimiento->monto;
                            $monto_total = $monto_total + $movimiento->monto;
                            $monto = $movimiento->monto;
                            $monto_iva = isset($movimiento->iva) ? $movimiento->iva : '';
                            $monto_retencion_deuda_1 = isset($movimiento->retencion_deuda_1) ? $monto * ($movimiento->retencion_deuda_1 / 100) : '';
                            $monto_retencion_deuda_2 = isset($movimiento->retencion_deuda_2) ? $monto * ($movimiento->retencion_deuda_2 / 100) : '';
                            $monto_retencion_iva = isset($movimiento->retencion_iva) ? $monto_iva * ($movimiento->retencion_iva / 100) : '';
                        }

                        if (strpos($movimiento->tipo, 'bancario')) {

                            $monto = $movimiento->monto;

                            $cantidad_bancarios = $cantidad_bancarios + 1;
                            $monto_bancarios = $monto_bancarios + $monto;
                            $monto_total = $monto_total - $monto;
                            $monto_iva = isset($movimiento->iva) ? $movimiento->iva : '';

                            $monto_retencion_deuda_1 = isset($movimiento->retencion_deuda_1) ? $monto * ($movimiento->retencion_deuda_1 / 100) : 0;
                            $monto_retencion_deuda_2 = isset($movimiento->retencion_deuda_2) ? $monto * ($movimiento->retencion_deuda_2 / 100) : 0;
                            $monto_retencion_iva = (isset($movimiento->retencion_iva) && isset($movimiento->iva)) ? $movimiento->iva * ($movimiento->retencion_iva / 100) : 0;

                            $monto = $movimiento->monto - $monto_retencion_deuda_1 - $monto_retencion_deuda_2;
                            $monto_iva = isset($movimiento->iva) ? $movimiento->iva - $monto_retencion_iva : '';
                        }

                        if (strpos($movimiento->tipo, 'efectivo')) {
                            $moneda = (strpos($movimiento->tipo, 'dolares')) ? 'Dólares' : 'Bolívares';

                            if ($moneda != $movimiento->moneda_proveedor) {
                                if ($moneda == 'Dólares' && $movimiento->moneda_proveedor == 'Bolívares') {
                                    $monto = $movimiento->monto * $movimiento->tasa;
                                }

                                if ($moneda == 'Dólares' && $movimiento->moneda_proveedor == 'Pesos') {
                                    $monto = $movimiento->monto * $movimiento->tasa;
                                }

                                if ($moneda == 'Bolívares' && $movimiento->moneda_proveedor == 'Dólares') {
                                    $monto = $movimiento->monto / $movimiento->tasa;
                                }

                                if ($moneda == 'Bolívares' && $movimiento->moneda_proveedor == 'Pesos') {
                                    $monto = $movimiento->monto * $movimiento->tasa;
                                }

                                if ($moneda == 'Pesos' && $movimiento->moneda_proveedor == 'Bolívares') {
                                    $monto = $movimiento->monto / $movimiento->tasa;
                                }

                                if ($moneda == 'Pesos' && $movimiento->moneda_proveedor == 'Dólares') {
                                    $monto = $movimiento->monto / $movimiento->tasa;
                                }
                            } else {
                                $monto = $movimiento->monto;
                            }

                            $cantidad_efectivo = $cantidad_efectivo + 1;
                            $monto_efectivo = $monto_efectivo + $monto;
                            $monto_total = $monto_total - $monto;
                            $monto_iva = isset($movimiento->iva) ? $movimiento->iva : '';

                            $monto_retencion_deuda_1 = isset($movimiento->retencion_deuda_1) ? $monto * ($movimiento->retencion_deuda_1 / 100) : '';
                            $monto_retencion_deuda_2 = isset($movimiento->retencion_deuda_2) ? $monto * ($movimiento->retencion_deuda_2 / 100) : '';
                            $monto_retencion_iva = isset($movimiento->retencion_iva) ? $monto_iva * ($movimiento->retencion_iva / 100) : '';
                        }

                        if ($movimiento->tipo == 'Reclamo') {
                            $cantidad_reclamos = $cantidad_reclamos + 1;
                            $monto_reclamos = $monto_reclamos + $movimiento->monto;
                            $monto_total = $monto_total + $movimiento->monto;

                            $monto_retencion_deuda_1 = isset($movimiento->retencion_deuda_1) ? $monto * ($movimiento->retencion_deuda_1 / 100) : '';
                            $monto_retencion_deuda_2 = isset($movimiento->retencion_deuda_2) ? $monto * ($movimiento->retencion_deuda_2 / 100) : '';
                            $monto_retencion_iva = isset($movimiento->retencion_iva) ? $monto_iva * ($movimiento->retencion_iva / 100) : '';
                        }

                        $cantidad_total = $cantidad_total + 1;
                    @endphp
                    <tr>
                        <td nowrap class="text-center">{{ $loop->iteration }}</td>
                        <td nowrap class="text-center fecha_hora ">{{ date_format($fecha, 'd/m/Y h:i A') }}</td>
                        <td nowrap class="text-center tipo">{{ $movimiento->tipo }}</td>
                        <td nowrap class="text-center nro_movimiento">{{ $movimiento->nro_movimiento }}</td>
                        <td nowrap class="text-center moneda_base">{{ $movimiento->moneda_base }}</td>
                        <td nowrap class="text-center moneda_iva">{{ $movimiento->moneda_iva }}</td>
                        <td nowrap class="text-center monto_base">{{ number_format($monto, 2, ',', '.') }}</td>
                        <td nowrap class="text-center monto_iva">{{ ($monto_iva) ? number_format($monto_iva, 2, ',', '.') : '' }}</td>
                        <td nowrap class="text-center monto_retencion_deuda_1">{{ ($monto_retencion_deuda_1) ? number_format($monto_retencion_deuda_1, 2, ',', '.') : '' }}</td>
                        <td nowrap class="text-center monto_retencion_deuda_2">{{ ($monto_retencion_deuda_2) ? number_format($monto_retencion_deuda_2, 2, ',', '.') : '' }}</td>
                        <td nowrap class="text-center monto_retencion_iva">{{ ($monto_retencion_iva) ? number_format($monto_retencion_iva, 2, ',', '.') : '' }}</td>
                        <td nowrap class="text-center comentario">{!! $movimiento->comentario !!}</td>
                        <td nowrap class="text-center conciliado">{{ $movimiento->conciliacion }}</td>
                        <td nowrap class="text-center operador">{{ $movimiento->operador }}</td>
                        <td nowrap class="text-center estado">{{ $movimiento->estado }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <table class="table table-striped table-bordered col-12 sortable" id="myTable">
            <thead class="thead-dark">
                <tr>
                    <th scope="col" colspan="3" class="CP-sticky">Totales</th>
                </tr>
                <tr>
                    <th scope="col" class="CP-sticky">Tipo de movimiento</th>
                    <th scope="col" class="CP-sticky">Cantidad</th>
                    <th scope="col" class="CP-sticky">Monto</th>
                </tr>
            </thead>

            <tbody>
                <tr>
                    <td class="text-center">Ajustes</td>
                    <td class="text-center">{{ $cantidad_ajustes }}</td>
                    <td class="text-center">{{ number_format($monto_ajustes, 2, ',', '.') }}</td>
                </tr>

                <tr>
                    <td class="text-center">Deudas</td>
                    <td class="text-center">{{ $cantidad_deudas }}</td>
                    <td class="text-center">{{ number_format($monto_deudas, 2, ',', '.') }}</td>
                </tr>

                <tr>
                    <td class="text-center">Pago bancario</td>
                    <td class="text-center">{{ $cantidad_bancarios }}</td>
                    <td class="text-center">{{ number_format($monto_bancarios, 2, ',', '.') }}</td>
                </tr>

                <tr>
                    <td class="text-center">Pago en efectivo</td>
                    <td class="text-center">{{ $cantidad_efectivo }}</td>
                    <td class="text-center">{{ number_format($monto_efectivo, 2, ',', '.') }}</td>
                </tr>

                <tr>
                    <td class="text-center">Reclamos</td>
                    <td class="text-center">{{ $cantidad_reclamos }}</td>
                    <td class="text-center">{{ number_format($monto_reclamos, 2, ',', '.') }}</td>
                </tr>

                <tr>
                    <td class="text-center font-weight-bold">Totales</td>
                    <td class="text-center font-weight-bold">{{ $cantidad_total }}</td>
                    <td class="text-center font-weight-bold">{{ number_format($monto_total, 2, ',', '.') }}</td>
                </tr>
            </tbody>
        </table>

        <p class="text-center"><b>Nota:</b> Los pagos en diferido solo aparecerán en este reporte una vez se hayan procesado.</p>

    @else
        <form action="">
            <div class="row mt-5 mb-5">
                <div class="col"></div>

                <div class="col">
                    Proveedor:
                </div>

                <div class="col">
                    <input type="text" required class="form-control" name="proveedor">
                    <input type="hidden" required name="id_proveedor">
                </div>

                <div class="col"></div>
            </div>

            <div class="row mb-5 mt-5">
                <div class="col"></div>

                <div class="col">
                    Fecha inicio:
                </div>

                <div class="col">
                    <input type="date" required class="form-control" name="fechaInicio">
                </div>

                <div class="col"></div>

                <div class="col">
                    Fecha fin:
                </div>

                <div class="col">
                    <input type="date" required class="form-control" name="fechaFin">
                </div>

                <div class="col">
                    <input type="submit" class="btn btn-outline-success" value="Buscar">
                </div>

                <div class="col"></div>
            </div>
        </form>
    @endif
@endsection



@section('scriptsHead')
    <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">

    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>

    <script>
        $(document).ready(function() {
            var proveedoresJson = {!! json_encode($proveedores) !!};

            $('[name=proveedor]').autocomplete({
                source: proveedoresJson,
                autoFocus: true,
                select: function (event, ui) {
                    $('[name=id_proveedor]').val(ui.item.id);
                }
            });

            $('form').submit(function (event) {
                resultado = proveedoresJson.find(elemento => elemento.label == $('[name=proveedor]').val());

                if (!resultado) {
                    event.preventDefault();
                    alert('Debe seleccionar un proveedor válido');
                    bancario = false;
                }
            });
        });
    </script>
@endsection
