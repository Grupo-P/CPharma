@extends('layouts.etiquetas')

@section('title')
    Soporte de pago bancario
@endsection

@section('content')
@php
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
@endphp

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
            <tr>
            <td colspan="4" class="alinear-der">Proveedor:</td>
            <td colspan="4" class="alinear-izq">{{ $pago->proveedor->nombre_proveedor }}</td>
            </tr>
            <tr>
            <td colspan="4" class="alinear-der">Fecha y hora:</td>
            <td colspan="4" class="alinear-izq">{{ date_format($pago->created_at, 'd/m/Y H:i A') }}</td>
            </tr>
            <tr>
            <td colspan="4" class="alinear-der">Monto al banco:</td>
            <td colspan="4" class="alinear-izq">{{ number_format($pago->monto, 2, ',', '.') }}</td>
            </tr>
            <tr>
            <td colspan="4" class="alinear-der">Monto al proveedor:</td>
            <td colspan="4" class="alinear-izq">{{ number_format($monto_proveedor, 2, ',', '.') }}</td>
            </tr>
            @if($pago->tasa)
                <tr>
                <td colspan="4" class="alinear-der">Tasa:</td>
                <td colspan="4" class="alinear-izq">{{ number_format($pago->tasa, 2, ',', '.') }}</td>
                </tr>
            @endif
            <tr>
            <td colspan="4" class="alinear-der">Alias bancario:</td>
            <td colspan="4" class="alinear-izq">{{ $pago->banco->alias_cuenta }}</td>
            </tr>
            <tr>
            <td colspan="4" class="alinear-der">Operador:</td>
            <td colspan="4" class="alinear-izq">{{ $pago->operador }}</td>
            </tr>
            <tr>
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
