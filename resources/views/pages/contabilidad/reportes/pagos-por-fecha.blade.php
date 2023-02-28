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

        campos = ['nro_recibo', 'fecha_hora', 'tipo', 'emisor', 'proveedor', 'monto_pago', 'monto_proveedor_base', 'monto_proveedor_iva', 'comentario', 'plan_cuentas', 'estado', 'conciliado', 'operador'];

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
                <input type="checkbox" onclick="mostrar_ocultar(this, 'nro_recibo')" name="nro_recibo" checked>
                # de recibo
            </div>

            <div class="form-group">
                <input type="checkbox" onclick="mostrar_ocultar(this, 'fecha_hora')" name="fecha_hora" checked>
                Fecha y hora
            </div>

            <div class="form-group">
                <input type="checkbox" onclick="mostrar_ocultar(this, 'tipo')" name="tipo" checked>
                Tipo
            </div>

            <div class="form-group">
                <input type="checkbox" onclick="mostrar_ocultar(this, 'emisor')" name="emisor" checked>
                Emisor
            </div>

            <div class="form-group">
                <input type="checkbox" onclick="mostrar_ocultar(this, 'proveedor')" name="proveedor" checked>
                Proveedor
            </div>

            <div class="form-group">
                <input type="checkbox" onclick="mostrar_ocultar(this, 'monto_pago')" name="monto_pago" checked>
                Monto del pago
            </div>

            <div class="form-group">
                <input type="checkbox" onclick="mostrar_ocultar(this, 'monto_proveedor_base')" name="monto_proveedor_base" checked>
                Monto proveedor base
            </div>

            <div class="form-group">
                <input type="checkbox" onclick="mostrar_ocultar(this, 'monto_proveedor_iva')" name="monto_proveedor_iva" checked>
                Monto proveedor IVA
            </div>

            <div class="form-group">
                <input type="checkbox" onclick="mostrar_ocultar(this, 'comentario')" name="comentario" checked>
                Comentario
            </div>

            <div class="form-group">
                <input type="checkbox" onclick="mostrar_ocultar(this, 'plan_cuentas')" name="plan_cuentas" checked>
                Plan de cuentas
            </div>

            <div class="form-group">
                <input type="checkbox" onclick="mostrar_ocultar(this, 'estado')" name="estado" checked>
                Estado
            </div>

            <div class="form-group">
                <input type="checkbox" onclick="mostrar_ocultar(this, 'conciliado')" name="conciliado" checked>
                Conciliado
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

        <h6 align="center"><a href="" data-toggle="modal" data-target="#ver_campos"><i class="fa fa-eye"></i> Mostrar u ocultar campos<a></h6>

        <table class="table table-striped table-bordered col-12 sortable" id="myTable">
            <thead class="thead-dark">
                <tr>
                    <th scope="col" class="CP-sticky">#</th>
                    <th scope="col" class="nro_recibo CP-sticky"># de recibo</th>
                    <th scope="col" class="fecha_hora CP-sticky">Fecha y hora</th>
                    <th scope="col" class="tipo CP-sticky">Tipo</th>
                    <th scope="col" class="emisor CP-sticky">Emisor</th>
                    <th scope="col" class="proveedor CP-sticky">Proveedor</th>
                    <th scope="col" class="monto_pago CP-sticky">Monto del pago</th>
                    <th scope="col" class="monto_proveedor_base CP-sticky">Monto proveedor base</th>
                    <th scope="col" class="monto_proveedor_iva CP-sticky">Monto proveedor IVA</th>
                    <th scope="col" class="comentario CP-sticky">Comentario</th>
                    <th scope="col" class="plan_cuentas CP-sticky">Plan de cuentas</th>
                    <th scope="col" class="estado CP-sticky">Estado</th>
                    <th scope="col" class="conciliado CP-sticky">Conciliado</th>
                    <th scope="col" class="operador CP-sticky">Operador</th>
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
                        <td class="text-center nro_recibo">{{ str_pad($pago->id, 5, 0, STR_PAD_LEFT) }}</td>
                        <td class="text-center fecha_hora">{{ date_format($pago->created_at, 'd/m/Y h:i A') }}</td>
                        <td class="text-center tipo">{{ (get_class($pago) == 'compras\ContPagoBancario') ? 'Banco' : 'Efectivo' }}</td>
                        <td class="text-center emisor">
                            @if(get_class($pago) == 'compras\ContPagoBancario' && $pago->banco)
                                {{ $pago->banco->alias_cuenta }}
                            @endif

                            @if(get_class($pago) == 'compras\ContPagoEfectivoGP')
                                Pago dólares efectivo GP
                                @php $sede = 'GP'; @endphp
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

                            @if(get_class($pago) == 'compras\ContPagoEfectivoFM')
                                Pago dólares efectivo FM
                                @php $sede = 'FM'; @endphp
                            @endif

                            @if(get_class($pago) == 'compras\ContPagoEfectivoFEC')
                                Pago dólares efectivo FM
                                @php $sede = 'FEC'; @endphp
                            @endif

                            @if(get_class($pago) == 'compras\ContPagoBolivaresGP')
                                Pago bolívares efectivo GP
                                @php $sede = 'GP'; @endphp
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

                            @if(get_class($pago) == 'compras\ContPagoBolivaresFM')
                                Pago bolívares efectivo FM
                                @php $sede = 'FM'; @endphp
                            @endif
                        </td>

                        <td align="center" class="CP-barrido proveedor">
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

                        <td class="text-center monto_pago">
                            @if($pago->monto)
                                @php
                                    $monto_banco = monto_banco($pago->monto, $pago->iva, $pago->retencion_deuda_1, $pago->retencion_deuda_2, $pago->retencion_iva);
                                    echo number_format($monto_banco, 2, ',', '.');
                                    $monto = ($pago->iva) ? $pago->monto + $pago->iva : $pago->monto;
                                @endphp
                            @endif

                            @if($pago->egresos)
                                @php
                                    $monto_banco = monto_banco($pago->egresos, $pago->iva, $pago->retencion_deuda_1, $pago->retencion_deuda_2, $pago->retencion_iva);
                                    echo number_format($monto_banco, 2, ',', '.');
                                    $monto = ($pago->iva) ? $pago->egresos + $pago->iva : $pago->egresos;
                                @endphp
                            @endif

                            @if($pago->diferido)
                                @php
                                    $monto_banco = monto_banco($pago->diferido, $pago->iva, $pago->retencion_deuda_1, $pago->retencion_deuda_2, $pago->retencion_iva);
                                    echo number_format($monto_banco, 2, ',', '.');
                                    $monto = ($pago->iva) ? $pago->diferido + $pago->iva : $pago->diferido;
                                @endphp
                            @endif
                        </td>

                        @php
                            $monto_proveedor_base = 0;

                            if (get_class($pago) == 'compras\ContPagoBancario') {
                                if ($pago->banco) {
                                    $monto_proveedor_base = $monto;

                                    $url = '/bancarios/soporte/' . $pago->id;
                                }
                            } else {
                                if ($pago->proveedor) {
                                    if ($pago->proveedor->moneda != 'Dólares') {
                                        $monto_proveedor_base = $monto * $pago->tasa;
                                    } else {
                                        $monto_proveedor_base = $monto;
                                    }
                                } else {
                                    $monto_proveedor_base = $monto;
                                }

                                $url = '/efectivo' . $sede . '/soporte/' . $pago->id;
                            }
                        @endphp

                        <td class="text-center monto_proveedor_base">{{ number_format($monto_proveedor_base, 2, ',', '.') }}</td>
                        <td class="text-center monto_proveedor_iva">{{ ($pago->iva) ? number_format($pago->iva, 2, ',', '.') : '' }}</td>
                        <td class="text-center comentario">{!! ($pago->comentario) ? $pago->comentario : $pago->concepto !!}</td>

                        @php
                            if ($pago->cuenta) {
                                $plan_cuentas = $pago->cuenta->nombre;
                            } else if ($pago->proveedor) {
                                $plan_cuentas = $pago->proveedor->plan_cuentas;
                            } else {
                                $plan_cuentas = '';
                            }
                        @endphp

                        <td class="text-center plan_cuentas">{{ $plan_cuentas }}</td>
                        <td class="text-center estado">{{ ($pago->estatus == 'Reversado') ? 'Reversado' : 'Pagado' }}</td>
                        <td class="text-center conciliado">{{ ($pago->fecha_conciliado) ? 'Si' : 'No' }}</td>
                        <td class="text-center operador">{{ ($pago->user) ? $pago->user : $pago->operador }}</td>
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
