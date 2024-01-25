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
                    <img src="http://cpharmade.com/assets/img/icono.png" width="40px">
                    CPharma
                </span>
            </th>
            <th style="font-size:  12px; border: 1px solid black; border-collapse: collapse; padding: 10px" scope="row" colspan="4" class="aumento">Traslado</th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td style="font-size:  12px; border: 1px solid black; border-collapse: collapse; padding: 10px; text-align: right" colspan="4" class="alinear-der">Fecha</td>
            <td style="font-size:  12px; border: 1px solid black; border-collapse: collapse; padding: 10px" colspan="4" class="alinear-izq">{{ date_create()->format('d/m/Y') }}</td>
        </tr>

        <tr>
            <td style="font-size:  12px; border: 1px solid black; border-collapse: collapse; padding: 10px; text-align: right" colspan="4" class="alinear-der">Solicitado por:</td>
            <td style="font-size:  12px; border: 1px solid black; border-collapse: collapse; padding: 10px" colspan="4" class="alinear-izq">
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
                        echo 'FARMACIA MILLENNIUM 2000, C.A';
                    }

                    if ($_SERVER['HTTP_HOST'] == 'cpharmafec.com') {
                        echo 'FARMACIA EL CALLEJON, C.A.';
                    }
                @endphp
            </td>
        </tr>

        <tr>
            <td style="font-size:  12px; border: 1px solid black; border-collapse: collapse; padding: 10px; text-align: right" colspan="4" class="alinear-der">Desde:</td>
            <td style="font-size:  12px; border: 1px solid black; border-collapse: collapse; padding: 10px" colspan="4" class="alinear-izq">
                @php
                    if ($sede == 'FTN') {
                        echo 'FARMACIA TIERRA NEGRA, C. A.';
                    }

                    if ($sede == 'FAU') {
                        echo 'FARMACIA AVENIDA UNIVERSIDAD, C. A.';
                    }

                    if ($sede == 'FLL') {
                        echo 'FARMACIA LA LAGO, C. A.';
                    }

                    if ($sede == 'FSM') {
                        echo 'FARMACIA MILLENNIUM 2000, C.A';
                    }

                    if ($sede == 'FEC') {
                        echo 'FARMACIA EL CALLEJON, C.A.';
                    }
                @endphp
            </td>
        </tr>

        <tr>
            <td style="font-size:  12px; border: 1px solid black; border-collapse: collapse; padding: 10px; text-align: right" colspan="4" class="alinear-der">Operador:</td>
            <td style="font-size:  12px; border: 1px solid black; border-collapse: collapse; padding: 10px" colspan="4" class="alinear-izq">{{ Auth()->user()->name }}</td>
        </tr>
    </tbody>
</table>

<table style="width: 100%;border: 1px solid black; border-collapse: collapse; padding: 10px">
    <thead style="background-color: #e3e3e3">
        <tr>
            <th style="font-size:  12px; border: 1px solid black; border-collapse: collapse; padding: 5px" scope="row" class="aumento">#</th>
            <th style="font-size:  12px; border: 1px solid black; border-collapse: collapse; padding: 5px" scope="row" class="aumento">Codigo interno</th>
            <th style="font-size:  12px; border: 1px solid black; border-collapse: collapse; padding: 5px" scope="row" class="aumento">Codigo barra</th>
            <th style="font-size:  12px; border: 1px solid black; border-collapse: collapse; padding: 5px" scope="row" class="aumento">Descripcion</th>
            <th style="font-size:  12px; border: 1px solid black; border-collapse: collapse; padding: 5px" scope="row" class="aumento">Precio Bs.</th>
            <th style="font-size:  12px; border: 1px solid black; border-collapse: collapse; padding: 5px" scope="row" class="aumento">Precio $</th>
            <th style="font-size:  12px; border: 1px solid black; border-collapse: collapse; padding: 5px" scope="row" class="aumento">Cantidad</th>
        </tr>
    </thead>

    <tbody>
        @foreach(session()->get('traslado')[$sede] as $item)
            @php
                $codigo_barra = $item['codigo_barra'];
                $cantidad = $item['cantidad'];

                $conn = FG_Conectar_Smartpharma($sede);

                $sql = "
                    SELECT TOP 1
                        InvArticulo.CodigoArticulo AS codigo_interno,
                        InvArticulo.DescripcionLarga AS descripcion,
                        (ROUND(CAST((SELECT SUM (InvLoteAlmacen.Existencia) As Existencia FROM InvLoteAlmacen WHERE(InvLoteAlmacen.InvAlmacenId = 1 OR InvLoteAlmacen.InvAlmacenId = 2) AND (InvLoteAlmacen.InvArticuloId = InvArticulo.Id)) AS DECIMAL(38,0)),2,0))  AS existencia,
                        (ROUND(CAST((SELECT SUM (InvLoteAlmacen.Existencia) As Existencia FROM InvLoteAlmacen WHERE(InvLoteAlmacen.InvAlmacenId = 1) AND (InvLoteAlmacen.InvArticuloId = InvArticulo.Id)) AS DECIMAL(38,0)),2,0))  AS existencia_almacen_1,
                        (ROUND(CAST((SELECT SUM (InvLoteAlmacen.Existencia) As Existencia FROM InvLoteAlmacen WHERE(InvLoteAlmacen.InvAlmacenId = 2) AND (InvLoteAlmacen.InvArticuloId = InvArticulo.Id)) AS DECIMAL(38,0)),2,0))  AS existencia_almacen_2,
                        (ISNULL((SELECT InvArticuloAtributo.InvArticuloId FROM InvArticuloAtributo WHERE InvArticuloAtributo.InvAtributoId = (SELECT InvAtributo.Id FROM InvAtributo WHERE InvAtributo.Descripcion = 'Troquelados' OR  InvAtributo.Descripcion = 'troquelados') AND InvArticuloAtributo.InvArticuloId = InvArticulo.Id),CAST(0 AS INT))) AS troquelado,
                        (ISNULL(InvArticulo.FinConceptoImptoIdCompra,CAST(0 AS INT))) AS impuesto,
                        ROUND(CAST(1-((ISNULL(ROUND(CAST((SELECT VenCondicionVenta.PorcentajeUtilidad FROM VenCondicionVenta WHERE VenCondicionVenta.Id = (SELECT VenCondicionVenta_VenCondicionVentaArticulo.Id FROM VenCondicionVenta_VenCondicionVentaArticulo WHERE VenCondicionVenta_VenCondicionVentaArticulo.InvArticuloId = InvArticulo.Id)) AS DECIMAL(38,4)),2,0),CAST(0 AS INT)))/100)AS DECIMAL(38,4)), 2, 0) AS utilidad_articulo,
                        ROUND(CAST(1-((ISNULL(ROUND(CAST((SELECT VenCondicionVenta.PorcentajeUtilidad FROM VenCondicionVenta WHERE VenCondicionVenta.id = (SELECT VenCondicionVenta_VenCondicionVentaCategoria.Id FROM VenCondicionVenta_VenCondicionVentaCategoria WHERE VenCondicionVenta_VenCondicionVentaCategoria.InvCategoriaId = InvArticulo.InvCategoriaId)) AS DECIMAL(38,4)),2,0),CAST(0 AS INT)))/100)AS DECIMAL(38,4)),2,0) AS utilidad_categoria,
                        (ROUND(CAST((SELECT TOP 1 InvLote.M_PrecioTroquelado FROM InvLoteAlmacen INNER JOIN InvLote ON InvLote.Id = InvLoteAlmacen.InvLoteId WHERE(InvLoteAlmacen.InvAlmacenId = '1') AND (InvLoteAlmacen.InvArticuloId = InvArticulo.Id) AND (InvLoteAlmacen.Existencia>0) ORDER BY invlote.M_PrecioTroquelado DESC)AS DECIMAL(38,2)),2,0)) AS troquel_almacen_1,
                        (ROUND(CAST((SELECT TOP 1 InvLote.M_PrecioCompraBruto FROM InvLoteAlmacen INNER JOIN InvLote ON InvLote.Id = InvLoteAlmacen.InvLoteId WHERE (InvLoteAlmacen.InvArticuloId = InvArticulo.Id) AND (InvLoteAlmacen.Existencia>0) AND (InvLoteAlmacen.InvAlmacenId = '1') ORDER BY invlote.M_PrecioCompraBruto DESC)AS DECIMAL(38,2)),2,0)) AS precio_compra_almacen_1,
                        (ROUND(CAST((SELECT TOP 1 InvLote.M_PrecioTroquelado FROM InvLoteAlmacen INNER JOIN InvLote ON InvLote.Id = InvLoteAlmacen.InvLoteId WHERE(InvLoteAlmacen.InvAlmacenId = '2') AND (InvLoteAlmacen.InvArticuloId = InvArticulo.Id) AND (InvLoteAlmacen.Existencia>0) ORDER BY invlote.M_PrecioTroquelado DESC)AS DECIMAL(38,2)),2,0)) AS troquel_almacen_2,
                        (ROUND(CAST((SELECT TOP 1 InvLote.M_PrecioCompraBruto FROM InvLoteAlmacen INNER JOIN InvLote ON InvLote.Id = InvLoteAlmacen.InvLoteId WHERE (InvLoteAlmacen.InvArticuloId = InvArticulo.Id) AND (InvLoteAlmacen.Existencia>0) AND (InvLoteAlmacen.InvAlmacenId = '2') ORDER BY invlote.M_PrecioCompraBruto DESC)AS DECIMAL(38,2)),2,0)) AS precio_compra_bruto_almacen_2,
                        (ROUND(CAST((SELECT TOP 1 InvLote.M_PrecioCompraBruto FROM InvLoteAlmacen INNER JOIN InvLote ON InvLote.Id = InvLoteAlmacen.InvLoteId WHERE (InvLoteAlmacen.InvArticuloId = InvArticulo.Id) AND (InvLoteAlmacen.Existencia>0) ORDER BY invlote.M_PrecioCompraBruto DESC)AS DECIMAL(38,2)),2,0)) AS precio_compra_bruto
                    FROM
                        InvArticulo
                    WHERE
                        InvArticulo.Id = (SELECT InvCodigoBarra.InvArticuloId FROM InvCodigoBarra WHERE InvCodigoBarra.CodigoBarra = '$codigo_barra');
                ";

                $result = sqlsrv_query($conn, $sql);

                $row = sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC);

                $descripcion = FG_Limpiar_Texto($row['descripcion']);
                $codigo_interno = $row['codigo_interno'];

                $existencia = $row['existencia'];
                $existencia_almacen_1 = $row['existencia_almacen_1'];
                $existencia_almacen_2 = $row['existencia_almacen_2'];
                $troquelado = $row['troquelado'];
                $impuesto = $row['impuesto'];
                $utilidad_articulo = $row['utilidad_articulo'];
                $utilidad_categoria = $row['utilidad_categoria'];
                $troquel_almacen_1 = $row['troquel_almacen_1'];
                $precio_compra_almacen_1 = $row['precio_compra_almacen_1'];
                $troquel_almacen_2 = $row['troquel_almacen_2'];
                $precio_compra_bruto_almacen_2 = $row['precio_compra_bruto_almacen_2'];
                $precio_compra_bruto = $row['precio_compra_bruto'];
                $condicion_existencia = 'CON_EXISTENCIA';

                $precio = FG_Calculo_Precio_Alfa($existencia, $existencia_almacen_1, $existencia_almacen_2, $troquelado, $utilidad_articulo, $utilidad_categoria, $troquel_almacen_1, $precio_compra_almacen_1, $troquel_almacen_2, $precio_compra_bruto_almacen_2, $precio_compra_bruto, $impuesto, $condicion_existencia);

                $tasa = DB::table('tasa_ventas')->where('moneda', 'Dolar')->value('tasa');
                $precio_usd = isset($tasa) ? $precio / $tasa : 0;
            @endphp

            <tr>
                <td style="font-size:  12px;  text-align: center; border: 1px solid black; border-collapse: collapse">{{ $loop->iteration }}</td>
                <td style="font-size:  12px; text-align: center; border: 1px solid black; border-collapse: collapse">{{ $codigo_interno }}</td>
                <td style="font-size:  12px; text-align: center; border: 1px solid black; border-collapse: collapse">{{ $codigo_barra }}</td>
                <td style="font-size:  12px; text-align: center; border: 1px solid black; border-collapse: collapse">{{ $descripcion }}</td>
                <td style="font-size:  12px; text-align: center; border: 1px solid black; border-collapse: collapse">{{ number_format($precio, 2, ',', '.') }}</td>
                <td style="font-size:  12px; text-align: center; border: 1px solid black; border-collapse: collapse">{{ number_format($precio_usd, 2, ',', '.') }}</td>
                <td style="font-size:  12px; text-align: center; border: 1px solid black; border-collapse: collapse">{{ $cantidad }}</td>
            </tr>
        @endforeach
    </tbody>
</table>
</body>
</html>
