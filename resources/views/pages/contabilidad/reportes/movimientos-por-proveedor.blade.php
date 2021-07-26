@extends('layouts.contabilidad')


@section('title')
    Reporte
@endsection


@section('content')
    <h1 class="h5 text-info">
        <i class="fas fa-file-invoice">
        </i>
        Movimientos por proveedor
    </h1>

    <hr class="row align-items-start col-12">

    @if($request->get('fechaInicio'))
        <div class="input-group md-form form-sm form-1 pl-0 CP-stickyBar mb-4">
            <div class="input-group-prepend">
                <span class="input-group-text purple lighten-3" id="basic-text1">
                    <i class="fas fa-search text-white" aria-hidden="true"></i>
                </span>
            </div>

            <input class="form-control my-0 py-1" type="text" placeholder="Buscar..." aria-label="Search" id="myInput" onkeyup="FilterAllTable()" autofocus="autofocus">
        </div>

        <h6 align="center">Periodo desde el <b>{{ $fechaInicio }}</b> al <b>{{ $fechaFin }}</b> para el proveedor <b>{{ $proveedor->nombre_proveedor }}</b></h6>

        <table class="table table-striped table-bordered col-12 sortable" id="myTable">
            <thead class="thead-dark">
                <tr>
                    <th scope="col" class="CP-sticky">#</th>
                    <th scope="col" class="CP-sticky">Fecha y hora</th>
                    <th scope="col" class="CP-sticky">Tipo</th>
                    <th scope="col" class="CP-sticky">Nro. movimiento</th>
                    <th scope="col" class="CP-sticky">Monto al proveedor</th>
                    <th scope="col" class="CP-sticky">Monto al banco</th>
                    <th scope="col" class="CP-sticky">Comentario</th>
                    <th scope="col" class="CP-sticky">Conciliado</th>
                    <th scope="col" class="CP-sticky">Operador</th>
                    <th scope="col" class="CP-sticky">Estado</th>
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
                        }

                        if ($movimiento->tipo == 'Deudas') {
                            $cantidad_deudas = $cantidad_deudas + 1;
                            $monto_deudas = $monto_deudas + $movimiento->monto;
                            $monto_total = $monto_total + $movimiento->monto;
                            $monto = $movimiento->monto;
                        }

                        if (strpos($movimiento->tipo, 'bancario')) {
                            if ($movimiento->moneda_banco != $movimiento->moneda_proveedor) {
                                if ($movimiento->moneda_banco == 'Dólares' && $movimiento->moneda_proveedor == 'Bolívares') {
                                    $monto = $movimiento->monto * $movimiento->tasa;
                                }

                                if ($movimiento->moneda_banco == 'Dólares' && $movimiento->moneda_proveedor == 'Pesos') {
                                    $monto = $movimiento->monto * $movimiento->tasa;
                                }

                                if ($movimiento->moneda_banco == 'Bolívares' && $movimiento->moneda_proveedor == 'Dólares') {
                                    $monto = $movimiento->monto / $movimiento->tasa;
                                }

                                if ($movimiento->moneda_banco == 'Bolívares' && $movimiento->moneda_proveedor == 'Pesos') {
                                    $monto = $movimiento->monto * $movimiento->tasa;
                                }

                                if ($movimiento->moneda_banco == 'Pesos' && $movimiento->moneda_proveedor == 'Bolívares') {
                                    $monto = $movimiento->monto / $movimiento->tasa;
                                }

                                if ($movimiento->moneda_banco == 'Pesos' && $movimiento->moneda_proveedor == 'Dólares') {
                                    $monto = $movimiento->monto / $movimiento->tasa;
                                }
                            } else {
                                $monto = $movimiento->monto;
                            }

                            $cantidad_bancarios = $cantidad_bancarios + 1;
                            $monto_bancarios = $monto_bancarios + $monto;
                            $monto_total = $monto_total - $monto;
                        }

                        if (strpos($movimiento->tipo, 'efectivo')) {
                            if ($movimiento->moneda_proveedor != 'Dólares') {
                                $monto = $movimiento->monto * $movimiento->tasa;
                            } else {
                                $monto = $movimiento->monto;
                            }

                            $cantidad_efectivo = $cantidad_efectivo + 1;
                            $monto_efectivo = $monto_efectivo + $monto;
                            $monto_total = $monto_total - $monto;
                        }

                        if ($movimiento->tipo == 'Reclamo') {
                            $cantidad_reclamos = $cantidad_reclamos + 1;
                            $monto_reclamos = $monto_reclamos + $movimiento->monto;
                            $monto_total = $monto_total + $movimiento->monto;
                            $monto = $movimiento->monto;
                        }

                        $cantidad_total = $cantidad_total + 1;
                    @endphp
                    <tr>
                        <td class="text-center">{{ $loop->iteration }}</td>
                        <td class="text-center">{{ date_format($fecha, 'd/m/Y h:i A') }}</td>
                        <td class="text-center">{{ $movimiento->tipo }}</td>
                        <td class="text-center">{{ $movimiento->nro_movimiento }}</td>
                        <td class="text-center">{{ number_format($monto, 2, ',', '.') }}</td>
                        <td class="text-center">{{ number_format($movimiento->monto, 2, ',', '.') }}</td>
                        <td class="text-center">{{ $movimiento->comentario }}</td>
                        <td class="text-center">{{ $movimiento->conciliacion }}</td>
                        <td class="text-center">{{ $movimiento->operador }}</td>
                        <td class="text-center">{{ $movimiento->estado }}</td>
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
