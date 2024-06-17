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
                    <img src="{{ asset('assets/img/icono.png') }}" width="40px">
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
                        echo 'FARMACIA TIERRA NEGRA, C. A.';
                    }

                    if ($_SERVER['HTTP_HOST'] == 'cpharmafll.com') {
                        echo 'FARMACIA LA LAGO, C. A.';
                    }

                    if ($_SERVER['HTTP_HOST'] == 'cpharmafsm.com') {
                        echo 'FARMACIA MILLENNIUM 2000, C.A.';
                    }

                    if ($_SERVER['HTTP_HOST'] == 'cpharmafec.com') {
                        echo 'FARMACIA EL CALLEJON, C.A.';
                    }

                    if ($_SERVER['HTTP_HOST'] == 'cpharmaflf.com') {
                        echo 'FARMACIA LA FUSTA';
                    }

                    if ($_SERVER['HTTP_HOST'] == 'cpharmakdi.com') {
                        echo 'FARMACIAS KD EXPRESS, C.A.';
                    }

                    if ($_SERVER['HTTP_HOST'] == 'cpharmakd73.com') {
                        echo 'FARMACIAS KD EXPRESS, C.A. - KD73';
                    }

                    $total_bs = 0;
                    $total_ds = 0;
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
            <th style="font-size:  10px; border: 1px solid black; border-collapse: collapse; padding: 5px" scope="row" class="aumento">Código interno</th>
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
            @php
                $precio_bs =  str_replace('.', '', $item['precio_bs']);
                $precio_ds =  str_replace('.', '', $item['precio_ds']);

                $precio_bs =  str_replace(',', '.', $precio_bs);
                $precio_ds =  str_replace(',', '.', $precio_ds);

                $precio_bs = (float) $precio_bs;
                $precio_ds = (float) $precio_ds;

                $total_ds = $total_ds + ($precio_ds * $item['cantidad']);
                $total_bs = $total_bs + ($precio_bs * $item['cantidad']);
            @endphp

            <tr>
                <td style="font-size:  10px; text-align: center; border: 1px solid black; border-collapse: collapse">{{ $item['codigo_interno'] }}</td>
                <td style="font-size:  10px; text-align: center; border: 1px solid black; border-collapse: collapse">{{ $item['descripcion'] }}</td>
                <td style="font-size:  10px; text-align: center; border: 1px solid black; border-collapse: collapse">{{ $item['precio_bs'] }}</td>
                <td style="font-size:  10px; text-align: center; border: 1px solid black; border-collapse: collapse">{{ $item['precio_ds'] }}</td>
                <td style="font-size:  10px; text-align: center; border: 1px solid black; border-collapse: collapse">{{ $item['cantidad'] }}</td>
                <td style="font-size:  10px; text-align: center; border: 1px solid black; border-collapse: collapse">{{ number_format($item['cantidad'] * $precio_bs, 2, ',', '.') }}</td>
                <td style="font-size:  10px; text-align: center; border: 1px solid black; border-collapse: collapse">{{ number_format($item['cantidad'] * $precio_ds, 2, ',', '.') }}</td>
            </tr>
        @endforeach
    </tbody>

    <tfoot>
        <tr>
            <th colspan="5"></th>
            <th style="font-size:  10px; text-align: center; border: 1px solid black; border-collapse: collapse">Total {{ SigVe }}</th>
            <th style="font-size:  10px; text-align: center; border: 1px solid black; border-collapse: collapse">{{ number_format($total_bs, 2, ',', '.') }}</th>
        </tr>

        <tr>
            <th colspan="5"></th>
            <th style="font-size:  10px; text-align: center; border: 1px solid black; border-collapse: collapse">Total {{ SigDolar }}</th>
            <th style="font-size:  10px; text-align: center; border: 1px solid black; border-collapse: collapse">{{ number_format($total_ds, 2, ',', '.') }}</th>
        </tr>
    </tfoot>
</table>

<p><b>Nota: Los precios pueden variar sin previo aviso.</b></p>

</body>
</html>
