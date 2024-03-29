@extends('layouts.contabilidad')

@section('title')
    Conciliaciones
@endsection

@section('content')

<!-- Modal Guardar -->
@if (session('Saved'))
<div aria-hidden="true" aria-labelledby="exampleModalCenterTitle" class="modal fade" id="exampleModalCenter" role="dialog" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title text-info" id="exampleModalCenterTitle">
                    <i class="fas fa-info text-info">
                    </i>
                    {{ session('Saved') }}
                </h5>

                <button aria-label="Close" class="close" data-dismiss="modal" type="button">
                    <span aria-hidden="true">
                        ×
                    </span>
                </button>
            </div>

            <div class="modal-body">
                <h4 class="h6">
                    Pago conciciliado exitosamente
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
    <i class="fas fa-file-invoice-dollar">
    </i>
    Conciliaciones
</h1>

<hr class="row align-items-start col-12">
    <table class="CP-stickyBar" style="width:100%;">
        <tr>
            <td class="pr-3">
                <div class="input-group md-form form-sm form-1 pl-0 CP-stickyBar">
                    <div class="input-group-prepend">
                        <span class="input-group-text purple lighten-3" id="basic-text1">
                            <i aria-hidden="true" class="fas fa-search text-white">
                            </i>
                        </span>
                    </div>
                    <input aria-label="Search" autofocus="autofocus" class="form-control my-0 py-1 CP-stickyBar" id="myInput" onkeyup="FilterAllTable()" placeholder="Buscar..." type="text">
                    </input>
                </div>
            </td>

            <form action="">

                <td  class="pr-2" width="35%">
                    <select name="tipo" class="form-control">
                        <option value="">Seleccione banco o sede</option>

                        @foreach($bancos as $banco)
                            <option {{ ($banco->alias_cuenta.'bpb' == $request->tipo) ? 'selected' : '' }} value="{{ $banco->alias_cuenta }}bpb">{{ $banco->alias_cuenta }}</option>
                        @endforeach

                        <option {{ ($request->tipo == 'Efectivo dolares GP') ? 'selected' : '' }} value="Efectivo dolares GP">Efectivo dolares GP</option>
                        <option {{ ($request->tipo == 'Efectivo dolares FM') ? 'selected' : '' }} value="Efectivo dolares FM">Efectivo dolares FM</option>
                        <option {{ ($request->tipo == 'Efectivo dolares FTN') ? 'selected' : '' }} value="Efectivo dolares FTN">Efectivo dolares FTN</option>
                        <option {{ ($request->tipo == 'Efectivo dolares FAU') ? 'selected' : '' }} value="Efectivo dolares FAU">Efectivo dolares FAU</option>
                        <option {{ ($request->tipo == 'Efectivo dolares FLL') ? 'selected' : '' }} value="Efectivo dolares FLL">Efectivo dolares FLL</option>

                        <option {{ ($request->tipo == 'Efectivo bolivares GP') ? 'selected' : '' }} value="Efectivo bolivares GP">Efectivo bolivares GP</option>
                        <option {{ ($request->tipo == 'Efectivo bolivares FM') ? 'selected' : '' }} value="Efectivo bolivares FM">Efectivo bolivares FM</option>
                        <option {{ ($request->tipo == 'Efectivo bolivares FTN') ? 'selected' : '' }} value="Efectivo bolivares FTN">Efectivo bolivares FTN</option>
                        <option {{ ($request->tipo == 'Efectivo bolivares FAU') ? 'selected' : '' }} value="Efectivo bolivares FAU">Efectivo bolivares FAU</option>
                        <option {{ ($request->tipo == 'Efectivo bolivares FLL') ? 'selected' : '' }} value="Efectivo bolivares FLL">fectivo bolivares FLL</option>
                    </select>
                </td>

                <td width="10%" class="pl-2 pr-2">
                    <button class="btn btn-outline-success btn-block">Buscar</button>
                </td>

            </form>
        </tr>
    </table>

    <br/>
    <table class="table table-striped table-borderless col-12 sortable" id="myTable">
        <thead class="thead-dark">
            <tr>
                <th class="CP-sticky" scope="col">
                    #
                </th>
                <th class="CP-sticky" scope="col">
                    Tipo
                </th>
                <th class="CP-sticky" scope="col">
                    Emisor
                </th>
                <th class="CP-sticky" scope="col">
                    Proveedor / Concepto
                </th>
                <th class="CP-sticky" scope="col">
                    Monto pago
                </th>
                <th class="CP-sticky" scope="col">
                    Monto base
                </th>
                <th class="CP-sticky" scope="col">
                    Monto IVA
                </th>
                <th class="CP-sticky" scope="col">
                    Operador
                </th>
                <th class="CP-sticky" scope="col">
                    Fecha
                </th>
                <th class="CP-sticky" scope="col">
                    Estado
                </th>
                <th class="CP-sticky" scope="col">
                    Acciones
                </th>
            </tr>
        </thead>

        <tbody>
            @foreach($pagos as $pago)
            <tr>
                <td>
                    {{$pago['id']}}
                </td>
                <td>
                    {{($pago['tipo'])}}
                </td>
                <td>
                    {{$pago['emisor']}}
                </td>
                <td>
                    @if($pago['nombre_proveedor'])
                        {{ $pago['nombre_proveedor'] . ' | ' . $pago['ci_proveedor'] }}
                    @else
                        {!! $pago['concepto'] !!}
                    @endif
                </td>
                <td>
                    @php
                        $monto = $pago['monto'];
                        $monto_iva_real = 0;
                        $monto_base_real = 0;
                        $retencion_deuda_1 = 0;
                        $retencion_deuda_2 = 0;
                        $retencion_iva = 0;

                        if ($pago['retencion_deuda_1']) {
                            $retencion_deuda_1 = $monto * ($pago['retencion_deuda_1'] / 100);

                            if ($pago['moneda_proveedor'] != 'Dólares') {
                                if ($pago['moneda_proveedor'] == 'Bolívares') {
                                    $retencion_deuda_1 = $retencion_deuda_1 * $pago['tasa'];
                                }

                                if ($pago['moneda_proveedor'] == 'Pesos') {
                                    $retencion_deuda_1 = $retencion_deuda_1 * $pago['tasa'];
                                }
                            }
                        }

                        if ($pago['retencion_deuda_2']) {
                            $retencion_deuda_2 = $monto * ($pago['retencion_deuda_2'] / 100);

                            if ($pago['moneda_proveedor'] != 'Dólares') {
                                if ($pago['moneda_proveedor'] == 'Bolívares') {
                                    $retencion_deuda_2 = $retencion_deuda_2 * $pago['tasa'];
                                }

                                if ($pago['moneda_proveedor'] == 'Pesos') {
                                    $retencion_deuda_2 = $retencion_deuda_2 * $pago['tasa'];
                                }
                            }
                        }

                        if ($pago['retencion_iva']) {
                            $retencion_iva = $pago['iva'] * ($pago['retencion_iva'] / 100);

                            if ($pago['moneda_iva_proveedor'] != 'Dólares') {
                                if ($pago['moneda_iva_proveedor'] == 'Bolívares') {
                                    $retencion_iva = $retencion_iva * $pago['tasa'];
                                }

                                if ($pago['moneda_iva_proveedor'] == 'Pesos') {
                                    $retencion_iva = $retencion_iva * $pago['tasa'];
                                }
                            }
                        }

                        $retencion_deuda_1 = isset($retencion_deuda_1) ? $retencion_deuda_1 : 0;
                        $retencion_deuda_2 = isset($retencion_deuda_2) ? $retencion_deuda_2 : 0;
                        $retencion_iva = isset($retencion_iva) ? $retencion_iva : 0;

                        $monto_base_real = (float) $monto - (float) $retencion_deuda_1 - (float) $retencion_deuda_2;
                        $monto_pago = (float) $monto - (float) $retencion_deuda_1 - (float) $retencion_deuda_2;

                        if ($pago['iva']) {
                            $monto_iva = $pago['iva'];
                            $monto_iva_real = $monto_iva - $retencion_iva;
                            $monto_pago = $monto_pago + $monto_iva_real;
                        }

                        if ($pago['monto_banco'] == 0) {
                            echo number_format($monto_base_real + $monto_iva_real, 2, ',', '.');
                        } else {
                            echo number_format($pago['monto_banco'], 2, ',', '.');
                        }
                    @endphp
                </td>
                <td>
                    {{number_format($monto_base_real, 2, ',', '.')}}
                </td>
                <td>
                    {{number_format($monto_iva_real, 2, ',', '.')}}
                </td>
                <td>
                    {{$pago['operador']}}
                </td>
                <td>
                    {{$pago['fecha']}}
                </td>
                <td>
                    {{$pago['estado']}}
                </td>
                <td class="text-center">
                    <input data-id="{{$pago['id']}}" data-clase="{{$pago['clase']}}" type="checkbox">
                </td>
            </tr>
            @endforeach
        </tbody>

        <tfoot>
            <tr>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td>
                    <button type="button" class="conciliar btn btn-outline-success btn-sm">Conciliar<br>seleccionados</button>
                </td>
            </tr>
        </tfoot>
    </table>

    <script>
        $(document).ready(function(){
            $('[data-toggle="tooltip"]').tooltip();
        });

        $('#exampleModalCenter').modal('show');

        $('.conciliar').click(function () {
            checkeds = $('[type=checkbox]:checked');
            pagos = [];

            for (var i = 0; i < checkeds.length; i++) {
                id = $(checkeds[i]).attr('data-id');
                clase = $(checkeds[i]).attr('data-clase');

                pagos.push({ clase: clase, id: id });
            }

            if (pagos.length == 0) {
                return false;
            }

            $.ajax({
                url: '/conciliaciones',
                type: 'POST',
                data: {
                    pagos: pagos,
                    _token: '{{ csrf_token() }}'
                },
                success: function (response) {
                    window.location.href = window.location.href;
                },
                error: function (error) {
                    $('body').html(error.responseText);
                }
            });
        });
    </script>
    @endsection
</hr>

