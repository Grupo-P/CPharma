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

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">

    <link rel="stylesheet" href="/css/pdf.css">

</head>
<body>
<table style="width: 100%;border: 1px solid black; border-collapse: collapse; padding: 10px">
    <thead style="background-color: #e3e3e3">
        <tr style="border: 1px solid black; border-collapse: collapse; padding: 10px">
            <th style="border: 1px solid black; border-collapse: collapse; padding: 10px" scope="row" colspan="4">
                <span style="font-weight: bold;font-size: 40px; color: #17a2b8 !important" class="navbar-brand text-info CP-title-NavBar">
                    <i class="fas fa-syringe text-success"></i>CPharma
                </span>
            </th>
            <th style="border: 1px solid black; border-collapse: collapse; padding: 10px" scope="row" colspan="4" class="aumento">Soporte de pago</th>
        </tr>
    </thead>
    <tbody>
        <tr>
        <td style="border: 1px solid black; border-collapse: collapse; padding: 10px; text-align: right" colspan="4" class="alinear-der"># de recibo:</td>
        <td style="border: 1px solid black; border-collapse: collapse; padding: 10px" colspan="4" class="alinear-izq">{{ str_pad($pago->id, 5, 0, STR_PAD_LEFT) }}</td>
        </tr>
        <tr>
        <td style="border: 1px solid black; border-collapse: collapse; padding: 10px; text-align: right" colspan="4" class="alinear-der">Proveedor:</td>
        <td style="border: 1px solid black; border-collapse: collapse; padding: 10px" colspan="4" class="alinear-izq">{{ $pago->proveedor->nombre_proveedor }}</td>
        </tr>
        <tr>
        <td style="border: 1px solid black; border-collapse: collapse; padding: 10px; text-align: right" colspan="4" class="alinear-der">Fecha y hora:</td>
        <td style="border: 1px solid black; border-collapse: collapse; padding: 10px" colspan="4" class="alinear-izq">{{ date_format($pago->created_at, 'd/m/Y H:i A') }}</td>
        </tr>
        <tr>
        <td style="border: 1px solid black; border-collapse: collapse; padding: 10px; text-align: right" colspan="4" class="alinear-der">Monto:</td>
        <td style="border: 1px solid black; border-collapse: collapse; padding: 10px" colspan="4" class="alinear-izq">{{ number_format($pago->monto, 2, ',', '.') }}</td>
        </tr>
        @if($pago->tasa)
            <tr>
            <td colspan="4" style="border: 1px solid black; border-collapse: collapse; padding: 10px; text-align: right" class="alinear-der">Tasa:</td>
            <td colspan="4" style="border: 1px solid black; border-collapse: collapse; padding: 10px" class="alinear-izq">{{ number_format($pago->tasa, 2, ',', '.') }}</td>
            </tr>
        @endif
        <tr>
        <td colspan="4" style="border: 1px solid black; border-collapse: collapse; padding: 10px; text-align: right" class="alinear-der">Estado:</td>
        <td colspan="4" style="border: 1px solid black; border-collapse: collapse; padding: 10px" class="alinear-izq">{{ $pago->estatus }}</td>
        </tr>
        <tr>
        <td colspan="4" style="border: 1px solid black; border-collapse: collapse; padding: 10px; text-align: right" class="alinear-der">Banco:</td>
        <td colspan="4" style="border: 1px solid black; border-collapse: collapse; padding: 10px" class="alinear-izq">{{ $pago->banco->nombre_banco }}</td>
        </tr>
        <tr>
        <td colspan="4" style="border: 1px solid black; border-collapse: collapse; padding: 10px; text-align: right" class="alinear-der">Operador:</td>
        <td colspan="4" style="border: 1px solid black; border-collapse: collapse; padding: 10px" class="alinear-izq">{{ $pago->operador }}</td>
        </tr>
    </tbody>
</table>
</body>
</html>
