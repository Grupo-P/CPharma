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
                    <th scope="col" class="CP-sticky"># de recibo</th>
                    <th scope="col" class="CP-sticky">Fecha y hora</th>
                    <th scope="col" class="CP-sticky">Tipo</th>
                    <th scope="col" class="CP-sticky">Emisor</th>
                    <th scope="col" class="CP-sticky">Proveedor</th>
                    <th scope="col" class="CP-sticky">Monto del pago</th>
                    <th scope="col" class="CP-sticky">Monto proveedor base</th>
                    <th scope="col" class="CP-sticky">Monto proveedor IVA</th>
                    <th scope="col" class="CP-sticky">Comentario</th>
                    <th scope="col" class="CP-sticky">Plan de cuentas</th>
                    <th scope="col" class="CP-sticky">Estado</th>
                    <th scope="col" class="CP-sticky">Conciliado</th>
                    <th scope="col" class="CP-sticky">Operador</th>
                    <th scope="col" class="CP-sticky"></th>
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
                        <td class="text-center">{{ str_pad($pago->id, 5, 0, STR_PAD_LEFT) }}</td>
                        <td class="text-center">{{ date_format($pago->created_at, 'd/m/Y h:i A') }}</td>
                        <td class="text-center">{{ (get_class($pago) == 'compras\ContPagoBancario') ? 'Banco' : 'Efectivo' }}</td>
                        <td class="text-center">
                            @if(get_class($pago) == 'compras\ContPagoBancario' && $pago->banco)
                                {{ $pago->banco->alias_cuenta }}
                            @endif

                            @if(get_class($pago) == 'compras\ContPagoEfectivoFTN')
                                Pago dólares efectivo FTN
                                @php $sede = 'FTN'; @endphp
                            @endif

                            @if(get_class($pago) == 'compras\ContPagoEfectivoFAU')
                                Pago dólares efectivo FAU
                                @php $sede = 'FAU'; @endphp
                            @endif

                            @if(get_class($pago) == 'compras\ContPagoEfectivoFLL')
                                Pago dólares efectivo FLL
                                @php $sede = 'FLL'; @endphp
                            @endif

                            @if(get_class($pago) == 'compras\ContPagoBolivaresFTN')
                                Pago bolívares efectivo FTN
                                @php $sede = 'FTN'; @endphp
                            @endif

                            @if(get_class($pago) == 'compras\ContPagoBolivaresFAU')
                                Pago bolívares efectivo FAU
                                @php $sede = 'FAU'; @endphp
                            @endif

                            @if(get_class($pago) == 'compras\ContPagoBolivaresFLL')
                                Pago bolívares efectivo FLL
                                @php $sede = 'FLL'; @endphp
                            @endif
                        </td>

                        <td align="center" class="CP-barrido">
                            @if(isset($pago->proveedor))
                                @php
                                    $fechaInicio = date_create();
                                    $fechaInicio = date_modify($fechaInicio, '-30day');
                                    $fechaInicio = $fechaInicio->format('Y-m-d');

                                    $fechaFinal = date('Y-m-d');

                                    $url = '/reportes/movimientos-por-proveedor?id_proveedor=' . $pago->id_proveedor . '&fechaInicio=' . $fechaInicio . '&fechaFin=' . $fechaFinal;
                                @endphp

                                <a href="{{ $url }}" style="text-decoration: none; color: black;" target="_blank">{{ $pago->proveedor->nombre_proveedor }}</a>
                            @endif
                        </td>

                        <td class="text-center">
                            @if($pago->monto)
                                {{ number_format($pago->monto, 2, ',', '.') }}

                                @php $monto = $pago->monto @endphp
                            @endif

                            @if($pago->egresos)
                                {{ number_format($pago->egresos, 2, ',', '.') }}

                                @php $monto = $pago->egresos @endphp
                            @endif

                            @if($pago->diferido)
                                {{ number_format($pago->diferido, 2, ',', '.') }}

                                @php $monto = $pago->diferido @endphp
                            @endif
                        </td>

                        @php
                            $monto_proveedor = 0;

                            if (get_class($pago) == 'compras\ContPagoBancario') {
                                if ($pago->banco) {
                                    $monto_proveedor_base = $monto;

                                    $url = '/bancarios/soporte/' . $pago->id;
                                }
                            } else {
                                if ($pago->proveedor) {
                                    if ($pago->proveedor->moneda != 'Dólares') {
                                        $monto_proveedor = $monto * $pago->tasa;
                                    } else {
                                        $monto_proveedor = $monto;
                                    }
                                } else {
                                    $monto_proveedor = $monto;
                                }

                                $url = '/efectivo' . $sede . '/soporte/' . $pago->id;
                            }
                        @endphp

                        <td class="text-center">{{ number_format($monto_proveedor_base, 2, ',', '.') }}</td>
                        <td class="text-center">{{ ($pago->iva) ? number_format($pago->iva, 2, ',', '.') : '' }}</td>
                        <td class="text-center">{!! ($pago->comentario) ? $pago->comentario : $pago->concepto !!}</td>

                        @php
                            if ($pago->cuenta) {
                                $plan_cuentas = $pago->cuenta->nombre;
                            } else if ($pago->proveedor) {
                                $plan_cuentas = $pago->proveedor->plan_cuentas;
                            } else {
                                $plan_cuentas = '';
                            }
                        @endphp

                        <td class="text-center">{{ $plan_cuentas }}</td>
                        <td class="text-center">{{ ($pago->deleted_at) ? 'Reversado' : 'Pagado' }}</td>
                        <td class="text-center">{{ ($pago->fecha_conciliado) ? 'Si' : 'No' }}</td>
                        <td class="text-center">{{ ($pago->user) ? $pago->user : $pago->operador }}</td>
                        <td class="text-center">
                            <a target="_blank" href="{{ $url }}" role="button" class="btn btn-outline-success btn-sm" data-toggle="tooltip" data-placement="top" title="Ver soporte">
                                <i class="fas fa-eye"></i>
                            </a>
                        </td>
                    </tr>

                    @php
                        if (get_class($pago) == 'compras\ContPagoBancario' && $pago->banco) {
                            if ($pago->banco->moneda == 'Dólares') {
                                $dolares = $dolares + $monto;
                            }

                            if ($pago->banco->moneda == 'Bolívares') {
                                $bolivares = $bolivares + $monto;
                            }
                        } else {
                            $dolares = $dolares + $monto;
                        }
                    @endphp
                @endforeach
            </tbody>

            <tfoot>
                <tr>
                    <th style="border: white 1px solid;"></th>
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

    <script>
        $(document).ready(function(){
            $('[data-toggle="tooltip"]').tooltip();
        });

        $('#exampleModalCenter').modal('show');
  </script>
@endsection
