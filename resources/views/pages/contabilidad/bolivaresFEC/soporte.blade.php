@extends('layouts.etiquetas')

@section('title')
    Soporte de pago en efectivo bolívares FEC
@endsection

@section('content')
<style>
    table{
        display: inline;
        border-collapse: collapse;
        text-align: center;
    }
    thead{
        border: 1px solid black;
        border-radius: 0px;
        background-color: #e3e3e3;
    }
    tbody{
        border: 1px solid black;
        border-radius: 0px;
    }
    td{
        border: 1px solid black;
        border-radius: 0px;
        width: 12cm;
    }
    th{
        border: 1px solid black;
        border-radius: 0px;
    }
    .alinear-der{
        text-align: right;
        padding-right: 10px;
    }
    .alinear-izq{
        text-align: left;
        padding-left: 10px;
    }
    .aumento{
        font-size: 1em;
        text-transform: uppercase;
    }
    .aumentoT{
        font-size: 1.2em;
        text-transform: uppercase;
    }
    .espacioT{
        padding-top: 5px;
        padding-bottom: 5px;
    }

    @media print {
        .mt-2 {
            display:  none;
        }
    }
</style>

    <div class="mt-2">
        <button class="btn btn-outline-success btn-sm" onclick="window.print()">
            <span class="fa fa-print"></span>
            Imprimir
        </button>

        <hr class="row align-items-start col-12">
    </div>

    <table>
        <thead>
            <tr>
                <th scope="row" colspan="4">
                    <span class="navbar-brand text-info CP-title-NavBar">
                        <b><i class="fas fa-syringe text-success"></i>CPharma</b>
                    </span>
                </th>
                <th scope="row" colspan="4" class="aumento">Soporte de pago</th>
            </tr>
    </thead>
        <tbody>
            <tr>
            <td colspan="4" class="alinear-der"># de recibo:</td>
            <td colspan="4" class="alinear-izq">{{ str_pad($pago->id, 5, 0, STR_PAD_LEFT) }}</td>
            </tr>
            @if($pago->proveedor)
                <tr>
                <td colspan="4" class="alinear-der">Proveedor:</td>
                <td colspan="4" class="alinear-izq">{{ $pago->proveedor->nombre_proveedor }}</td>
                </tr>
            @endif

            @if($pago->cuentas)
                <tr>
                <td colspan="4" class="alinear-der">Plan de cuentas:</td>
                <td colspan="4" class="alinear-izq">{{ $pago->cuentas->nombre }}</td>
                </tr>
            @endif
            <tr>
            <td colspan="4" class="alinear-der">Fecha y hora:</td>
            <td colspan="4" class="alinear-izq">{{ date_format($pago->created_at, 'd/m/Y H:i A') }}</td>
            </tr>
            <tr>
            <td colspan="4" class="alinear-der">Monto pago:</td>
            <td colspan="4" class="alinear-izq">
                @php
                    if ($pago->ingresos) {
                        $monto = $pago->ingresos;
                    }

                    if ($pago->egresos) {
                        $monto = $pago->egresos;
                    }

                    if ($pago->diferido) {
                        $monto = $pago->diferido;
                    }

                    if ($pago->retencion_deuda_1) {
                        $retencion_deuda_1 = $monto * ($pago->retencion_deuda_1 / 100);

                        if ($pago->proveedor->moneda != 'Bolívares') {
                            if ($pago->proveedor->moneda == 'Dólares') {
                                $retencion_deuda_1 = $retencion_deuda_1 * $pago->tasa;
                            }

                            if ($pago->proveedor->moneda == 'Pesos') {
                                $retencion_deuda_1 = $retencion_deuda_1 * $pago->tasa;
                            }
                        }
                    }

                    if ($pago->retencion_deuda_2) {
                        $retencion_deuda_2 = $monto * ($pago->retencion_deuda_2 / 100);

                        if ($pago->proveedor->moneda != 'Bolívares') {
                            if ($pago->proveedor->moneda == 'Dólares') {
                                $retencion_deuda_2 = $retencion_deuda_2 * $pago->tasa;
                            }

                            if ($pago->proveedor->moneda == 'Pesos') {
                                $retencion_deuda_2 = $retencion_deuda_2 * $pago->tasa;
                            }
                        }
                    }

                    if ($pago->retencion_iva) {
                        $retencion_iva = $pago->iva * ($pago->retencion_iva / 100);

                        if ($pago->proveedor->moneda != 'Bolívares') {
                            if ($pago->proveedor->moneda == 'Dólares') {
                                $retencion_iva = $retencion_iva * $pago->tasa;
                            }

                            if ($pago->proveedor->moneda == 'Pesos') {
                                $retencion_iva = $retencion_iva * $pago->tasa;
                            }
                        }
                    }

                    $retencion_deuda_1 = isset($retencion_deuda_1) ? $retencion_deuda_1 : 0;
                    $retencion_deuda_2 = isset($retencion_deuda_2) ? $retencion_deuda_2 : 0;
                    $retencion_iva = isset($retencion_iva) ? $retencion_iva : 0;

                    $monto_base_real = $monto - $retencion_deuda_1 - $retencion_deuda_2;
                    $monto_pago = $monto - $retencion_deuda_1 - $retencion_deuda_2;

                    if ($pago->iva) {
                        $monto_iva = $pago->iva;
                        $monto_iva_real = $monto_iva - $retencion_iva;
                        $monto_pago = $monto_pago + $monto_iva_real;
                    }

                    echo $pago->monto_banco
                @endphp
            </td>

            @if($pago->retencion_deuda_1)
                <tr>
                    <td colspan="4" class="alinear-der">Retención deuda 1:</td>
                    <td colspan="4" class="alinear-izq">{{ number_format($retencion_deuda_1, 2, ',', '.') }}</td>
                </tr>
            @endif

            @if($pago->retencion_deuda_2)
                <tr>
                    <td colspan="4" class="alinear-der">Retención deuda 2:</td>
                    <td colspan="4" class="alinear-izq">{{ number_format($retencion_deuda_2, 2, ',', '.') }}</td>
                </tr>
            @endif

            @if($pago->retencion_iva)
                <tr>
                    <td colspan="4" class="alinear-der">Retención IVA:</td>
                    <td colspan="4" class="alinear-izq">{{ number_format($retencion_iva, 2, ',', '.') }}</td>
                </tr>
            @endif

            <tr>
                <td colspan="4" class="alinear-der">Monto base real:</td>
                <td colspan="4" class="alinear-izq">{{ number_format($monto_base_real, 2, ',', '.') }}</td>
            </tr>

            @if(isset($monto_iva_real))
                <tr>
                    <td colspan="4" class="alinear-der">Monto IVA real:</td>
                    <td colspan="4" class="alinear-izq">{{ number_format($monto_iva_real, 2, ',', '.') }}</td>
                </tr>
            @endif

            <tr>
            <td colspan="4" class="alinear-der">Sede:</td>
            <td colspan="4" class="alinear-izq">Pago en efectivo por tesoreria - {{ $pago->sede }}</td>
            </tr>
            @if($pago->tasa)
                <tr>
                <td colspan="4" class="alinear-der">Tasa:</td>
                <td colspan="4" class="alinear-izq">{{ number_format($pago->tasa,2,',','.') }}</td>
                </tr>
            @endif

            @if($pago->autorizado_por)
                <tr>
                <td colspan="4" class="alinear-der">Autorizado por:</td>
                <td colspan="4" class="alinear-izq">{{ $pago->autorizado_por }}</td>
                </tr>
            @endif

            @if($pago->titular_pago)
                <tr>
                <td colspan="4" class="alinear-der">Titular pago:</td>
                <td colspan="4" class="alinear-izq">{{ $pago->titular_pago }}</td>
                </tr>
            @endif

            <tr>
            <td colspan="4" class="alinear-der">Operador:</td>
            <td colspan="4" class="alinear-izq">{{ $pago->user }}</td>
            </tr>
            <tr>
            @if($pago->cuentas)
                <tr>
                <td colspan="4" class="alinear-der">Titular pago:</td>
                <td colspan="4" class="alinear-izq">{{ $pago->titular_pago }}</td>
                </tr>
            @endif
            <tr>
            <td colspan="4" class="alinear-der">Comentario:</td>
            <td colspan="4" class="alinear-izq">{!! $pago->concepto !!}</td>
            </tr>
            <td colspan="4" class="alinear-izq">
                <br/><br/><br/>
                <span>______________________________</span><br/>
                <span>Quien Recibe</span><br/>
                <span>Nombre:</span><br/>
                <span>Apellido:</span><br/>
                <span>Fecha:</span><br/>
                <span>Hora:</span><br/>
                </td>
                <td colspan="4" class="alinear-izq">
                <br/><br/>
                <span>______________________________</span><br/>
                <span>Quien Entrega</span><br/>
                <span>Nombre:</span><br/>
                <span>Apellido:</span><br/>
                <span>Fecha:</span><br/>
                <span>Hora:</span><br/>
                </td>
            </tr>
        </tbody>
    </table>
@endsection
