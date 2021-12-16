<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">

    <link rel="stylesheet" href="/css/pdf.css">

</head>
<body>
<table style="width: 100%;border: 1px solid black; border-collapse: collapse; margin-bottom: 10px">
    <thead style="background-color: #e3e3e3">
        <tr style="border: 1px solid black; border-collapse: collapse; padding: 10px">
            <th style="border: 1px solid black; border-collapse: collapse; padding: 10px" scope="row" colspan="4">
                <span style="font-weight: bold;font-size: 40px; color: #17a2b8 !important" class="navbar-brand text-info CP-title-NavBar">
                    <img src="http://cpharmade.com/assets/img/icono.png" width="40px">
                    CPharma
                </span>
            </th>
            <th style="font-size:  10px; border: 1px solid black; border-collapse: collapse; padding: 10px" scope="row" colspan="4" class="aumento">Cotización</th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td style="font-size:  10px; border: 1px solid black; border-collapse: collapse; padding: 10px; text-align: right" colspan="4" class="alinear-der">Fecha:</td>
            <td style="font-size:  10px; border: 1px solid black; border-collapse: collapse; padding: 10px" colspan="4" class="alinear-izq">{{ date_create()->format('d/m/Y') }}</td>
        </tr>

        <tr>
            <td style="font-size:  10px; border: 1px solid black; border-collapse: collapse; padding: 10px; text-align: right" colspan="4" class="alinear-der">Sede:</td>
            <td style="font-size:  10px; border: 1px solid black; border-collapse: collapse; padding: 10px" colspan="4" class="alinear-izq">
                @php
                    if ($_SERVER['HTTP_HOST'] == 'cpharmade.com') {
                        echo 'FARMACIA AVENIDA UNIVERSIDAD, C. A.';
                    }

                    if ($_SERVER['HTTP_HOST'] == 'cpharmafau.com') {
                        echo 'FARMACIA AVENIDA UNIVERSIDAD, C. A.';
                    }

                    if ($_SERVER['HTTP_HOST'] == 'cpharmaftn.com') {
                        echo 'FARMACIA TIERRA NIEGRA, C. A.';
                    }

                    if ($_SERVER['HTTP_HOST'] == 'cpharmafll.com') {
                        echo 'FARMACIA LA LAGO, C. A.';
                    }
                @endphp
            </td>
        </tr>

        <tr>
            <td style="font-size:  10px; border: 1px solid black; border-collapse: collapse; padding: 10px; text-align: right" colspan="4" class="alinear-der">Operador:</td>
            <td style="font-size:  10px; border: 1px solid black; border-collapse: collapse; padding: 10px" colspan="4" class="alinear-izq">{{ Auth()->user()->name }}</td>
        </tr>

        <tr>
            <td style="font-size:  10px; border: 1px solid black; border-collapse: collapse; padding: 10px; text-align: right" colspan="4" class="alinear-der">Cliente:</td>
            <td style="font-size:  10px; border: 1px solid black; border-collapse: collapse; padding: 10px" colspan="4" class="alinear-izq">
                {{ $data['nombre_cliente'] }} <br>
                {{ $data['ci_cliente'] }} <br>
                {{ $data['direccion_cliente'] }}
            </td>
        </tr>
    </tbody>
</table>

<table style="width: 100%;border: 1px solid black; border-collapse: collapse; margin-bottom: 10px">
    <thead style="background-color: #e3e3e3">
        <tr>
            <th style="font-size:  10px; border: 1px solid black; border-collapse: collapse; padding: 5px" scope="row" class="aumento">Descripcion</th>
            <th style="font-size:  10px; border: 1px solid black; border-collapse: collapse; padding: 5px" scope="row" class="aumento">Precio unitario {{ SigVe }}</th>
            <th style="font-size:  10px; border: 1px solid black; border-collapse: collapse; padding: 5px" scope="row" class="aumento">Precio unitario {{ SigDolar }}</th>
            <th style="font-size:  10px; border: 1px solid black; border-collapse: collapse; padding: 5px" scope="row" class="aumento">Cantidad</th>
            <th style="font-size:  10px; border: 1px solid black; border-collapse: collapse; padding: 5px" scope="row" class="aumento">Precio total {{ SigVe }}</th>
            <th style="font-size:  10px; border: 1px solid black; border-collapse: collapse; padding: 5px" scope="row" class="aumento">Precio total {{ SigDolar }}</th>
        </tr>
    </thead>

    <tbody>
        @foreach($data['articulos'] as $item)
            <tr>
                <td style="font-size:  10px; text-align: center; border: 1px solid black; border-collapse: collapse">{{ $item['descripcion'] }}</td>
                <td style="font-size:  10px; text-align: center; border: 1px solid black; border-collapse: collapse">{{ $item['precio_bs'] }}</td>
                <td style="font-size:  10px; text-align: center; border: 1px solid black; border-collapse: collapse">{{ $item['precio_ds'] }}</td>
                <td style="font-size:  10px; text-align: center; border: 1px solid black; border-collapse: collapse">{{ $item['precio_bs'] }}</td>
                <td style="font-size:  10px; text-align: center; border: 1px solid black; border-collapse: collapse">{{ $item['precio_ds'] }}</td>
                <td style="font-size:  10px; text-align: center; border: 1px solid black; border-collapse: collapse">{{ $item['precio_ds'] }}</td>
            </tr>
        @endforeach
    </tbody>
</table>


<div style="text-align: right;">
    <table style="border: 1px solid back; border-collapse: collapse">
        <tr>
            <td style="padding: 4px; font-size:  10px; text-align: center; border: 1px solid back; border-collapse: collapse">Total {{ SigVe }}</td>
            <td style="padding: 4px; font-size:  10px; text-align: center; border: 1px solid back; border-collapse: collapse">0,00</td>
        </tr>

        <tr>
            <td style="padding: 4px; font-size:  10px; text-align: center; border: 1px solid back; border-collapse: collapse">Total {{ SigDolar }}</td>
            <td style="padding: 4px; font-size:  10px; text-align: center; border: 1px solid back; border-collapse: collapse">0,00</td>
        </tr>
    </table>
</div>

</body>
</html>
