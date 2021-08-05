@extends('layouts.etiquetas')

@section('title')
    Soporte de pago en efectivo dólares FAU
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
            <td colspan="4" class="alinear-der">Monto en dólares:</td>
            <td colspan="4" class="alinear-izq">
                @if($pago->monto)
                    {{ number_format($pago->monto, 2, ',', '.') }}
                @endif

                @if($pago->egresos)
                    {{ number_format($pago->egresos, 2, ',', '.') }}
                @endif

                @if($pago->diferido)
                    {{ number_format($pago->diferido, 2, ',', '.') }}
                @endif
            </td>
            </tr>
            @if($pago->proveedor)
                <tr>
                <td colspan="4" class="alinear-der">Monto al proveedor:</td>
                <td colspan="4" class="alinear-izq">
                    @php
                      if ($pago->proveedor) {
                        if ($pago->proveedor->moneda != 'Dólares') {
                            $monto = ($pago->egresos) ? $pago->egresos : $pago->diferido;

                            if ($pago->proveedor->moneda == 'Bolívares') {
                                $monto_proveedor = $monto * $pago->tasa;
                            }

                            if ($pago->proveedor->moneda == 'Pesos') {
                                $monto_proveedor = $monto * $pago->tasa;
                            }
                        } else {
                            $monto_proveedor = $pago->monto;
                        }
                      }
                    @endphp

                    {{(isset($monto_proveedor)) ? number_format($monto_proveedor,2,',','.') : ''}}
                </td>
                </tr>
            @endif
            <tr>
            <td colspan="4" class="alinear-der">Alias bancario:</td>
            <td colspan="4" class="alinear-izq">Pago en efectivo por tesoreria - {{ $pago->sede }}</td>
            </tr>
            @if($pago->tasa)
                <tr>
                <td colspan="4" class="alinear-der">Tasa:</td>
                <td colspan="4" class="alinear-izq">{{ number_format($pago->tasa,2,',','.') }}</td>
                </tr>
            @endif
            <tr>
            <td colspan="4" class="alinear-der">Operador:</td>
            <td colspan="4" class="alinear-izq">{{ $pago->user }}</td>
            </tr>
            <tr>
            @if($pago->cuentas)
                <tr>
                <td colspan="4" class="alinear-der">Autorizado por:</td>
                <td colspan="4" class="alinear-izq">{{ $pago->autorizado_por }}</td>
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
