@extends('layouts.contabilidad')


@section('title')
    Reporte
@endsection


@section('content')
    <h1 class="h5 text-info">
        <i class="fas fa-file-invoice">
        </i>
        Pagos por fecha
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

        <h6 align="center">Periodo desde el {{ $fechaInicio }} al {{ $fechaFin }}</h6>

        <table class="table table-striped table-bordered col-12 sortable" id="myTable">
            <thead class="thead-dark">
                <tr>
                    <th scope="col" class="CP-sticky">#</th>
                    <th scope="col" class="CP-sticky">Fecha y hora</th>
                    <th scope="col" class="CP-sticky">Tipo</th>
                    <th scope="col" class="CP-sticky">Emisor</th>
                    <th scope="col" class="CP-sticky">Proveedor</th>
                    <th scope="col" class="CP-sticky">Monto</th>
                    <th scope="col" class="CP-sticky">Monto al proveedor</th>
                    <th scope="col" class="CP-sticky">Comentario</th>
                    <th scope="col" class="CP-sticky">Plan de cuentas</th>
                    <th scope="col" class="CP-sticky">Conciliado</th>
                    <th scope="col" class="CP-sticky">Operador</th>
                </tr>
            </thead>

            <tbody>
                @php
                    $dolares = 0;
                    $bolivares = 0;
                    $total = 0;
                @endphp

                @foreach($pagos as $pago)
                    <tr>
                        <td class="text-center">{{ $loop->iteration }}</td>
                        <td class="text-center">{{ date_format($pago->created_at, 'd/m/Y h:i A') }}</td>
                        <td class="text-center">{{ (get_class($pago) == 'compras\ContPagoBancario') ? 'Banco' : 'Efectivo' }}</td>
                        <td class="text-center">{{ (get_class($pago) == 'compras\ContPagoBancario') ? $pago->banco->alias_cuenta : $pago->sede }}</td>
                        <td class="text-center">{{ $pago->proveedor->nombre_proveedor }}</td>
                        <td class="text-center">{{ $pago->monto ? number_format($pago->monto, 2, ',', '.') : number_format($pago->egresos, 2, ',', '.') }}</td>

                        @php
                            $monto_proveedor = 0;
                            if (get_class($pago) == 'compras\ContPagoBancario') {
                                if ($pago->banco->moneda != $pago->proveedor->moneda) {
                                    if ($pago->banco->moneda == 'Dólares' && $pago->proveedor->moneda == 'Bolívares') {
                                        $monto_proveedor = $pago->monto * $pago->tasa;
                                    }

                                    if ($pago->banco->moneda == 'Dólares' && $pago->proveedor->moneda == 'Pesos') {
                                        $monto_proveedor = $pago->monto * $pago->tasa;
                                    }

                                    if ($pago->banco->moneda == 'Bolívares' && $pago->proveedor->moneda == 'Dólares') {
                                        $monto_proveedor = $pago->monto / $pago->tasa;
                                    }

                                    if ($pago->banco->moneda == 'Bolívares' && $pago->proveedor->moneda == 'Pesos') {
                                        $monto_proveedor = $pago->monto * $pago->tasa;
                                    }

                                    if ($pago->banco->moneda == 'Pesos' && $pago->proveedor->moneda == 'Bolívares') {
                                        $monto_proveedor = $pago->monto / $pago->tasa;
                                    }

                                    if ($pago->banco->moneda == 'Pesos' && $pago->proveedor->moneda == 'Dólares') {
                                        $monto_proveedor = $pago->monto / $pago->tasa;
                                    }
                                } else {
                                    $monto_proveedor = $pago->monto;
                                }
                            } else {
                                $monto_proveedor = $pago->egresos;
                            }
                        @endphp

                        <td class="text-center">{{ number_format($monto_proveedor, 2, ',', '.') }}</td>
                        <td class="text-center">{{ $pago->comentario }}</td>
                        <td class="text-center">{{ $pago->cuentas ? $pago->cuenta->nombre : '' }}</td>
                        <td class="text-center">{{ $pago->conciliado ? 'Si' : 'No' }}</td>
                        <td class="text-center">{{ ($pago->user) ? $pago->user : $pago->operador }}</td>
                    </tr>

                    @php
                        if (get_class($pago) == 'compras\ContPagoBancario') {
                            if ($pago->banco->moneda == 'Dólares') {
                                $dolares = $dolares + $pago->monto;
                            }

                            if ($pago->banco->moneda == 'Bolívares') {
                                $bolivares = $bolivares + $pago->monto;
                            }
                        } else {
                            $dolares = $dolares + $pago->egresos;
                        }
                    @endphp
                @endforeach
            </tbody>

            <tfoot>
                <tr>
                    <th style="border: white 1px solid;"></th>
                    <th style="border: white 1px solid;"></th>
                    <th style="border: white 1px solid;"></th>
                    <th style="border-left: white 1px solid;border-bottom: white 1px solid;"></th>
                    <th class="text-center">Total en bolívares</th>
                    <th class="text-center">{{ number_format($bolivares, 2, ',', '.') }}</th>
                    <th style="border: white 1px solid;"></th>
                    <th style="border: white 1px solid;"></th>
                    <th style="border: white 1px solid;"></th>
                    <th style="border: white 1px solid;"></th>
                </tr>

                <tr>
                    <th style="border: white 1px solid;"></th>
                    <th style="border: white 1px solid;"></th>
                    <th style="border: white 1px solid;"></th>
                    <th style="border-left: white 1px solid;border-bottom: white 1px solid;"></th>
                    <th class="text-center">Total en dólares</th>
                    <th class="text-center">{{ number_format($dolares, 2, ',', '.') }}</th>
                    <th style="border: white 1px solid;"></th>
                    <th style="border: white 1px solid;"></th>
                    <th style="border: white 1px solid;"></th>
                    <th style="border: white 1px solid;"></th>
                </tr>
            </tfoot>
        </table>


    @else
        <form action="">
            <div class="row mb-5">
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
