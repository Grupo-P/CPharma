@extends('layouts.etiquetas')

@section('title')
    Traslado
@endsection

@section('scriptsFoot')
    <script>
        function confirmarEliminacion(url) {
            respuesta = confirm('¿Está seguro que desea eliminar este artículo?');

            if (respuesta) {
                window.location.href = url;
            }
        }
    </script>
@endsection

@section('content')
<style>
    table{
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
        padding: 5px;
    }
    th{
        border: 1px solid black;
        border-radius: 0px;
        padding: 5px;
    }
    .alinear-der{
        text-align: right;
        padding-right: 20px;
    }
    .alinear-izq{
        text-align: left;
        padding-left: 20px;
    }
    .aumento{
        font-size: 1.2em;
        text-transform: uppercase;
    }
</style>

    <h1 class="h5 text-info" style="display: inline;">
        <i class="fas fa-print"></i>
        Traslado
    </h1>
    <form action="/traslado/" method="POST" style="display: inline; margin-left: 50px;">
        @csrf
        <button type="submit" name="Regresar" role="button" class="btn btn-outline-info btn-sm"data-placement="top"><i class="fa fa-reply">&nbsp;Regresar</i></button>
    </form>
    <input type="button" name="imprimir" value="Imprimir" class="btn btn-outline-success btn-sm" onclick="window.print();" style="display: inline; margin-left: 10px;">

    @if(session()->get('traslado'))
        @foreach(session()->get('traslado') as $clave => $valor)
            <a href="/trasladoRecibir/pdf/{{ $clave }}" class="btn btn-outline-danger btn-sm">
                <span class="fa fa-file-pdf"></span> Descargar PDF de {{ $clave }}
            </a>
        @endforeach
    @endif

    <hr class="row align-items-start col-12">

    @if(session()->get('traslado'))

        @foreach(session()->get('traslado') as $clave => $valor)
            <table class="mb-5">
                <thead>
                    <tr>
                            <th scope="row" colspan="3">
                                <span class="navbar-brand text-info CP-title-NavBar">
                                    <b><i class="fas fa-syringe text-success"></i>CPharma</b>
                                </span>
                            </th>
                            <th scope="row" colspan="5" class="aumento">Traslado</th>
                    </tr>
            </thead>
            <tbody>
                <tr>
                <td colspan="3" class="alinear-der">Fecha:</td>
                <td colspan="5" class="alinear-izq">{{ date_create()->format('d/m/Y') }}</td>
                </tr>
                <tr>
                <td colspan="3" class="alinear-der">Solicitado por:</td>
                <td colspan="5" class="alinear-izq">
                    @php
                        if ($_SERVER['HTTP_HOST'] == 'cpharmade.com') {
                            echo 'FARMACIA AVENIDA UNIVERSIDAD, C. A.';
                            $sede = 'FAU';
                        }

                        if ($_SERVER['HTTP_HOST'] == 'cpharmafau.com') {
                            echo 'FARMACIA AVENIDA UNIVERSIDAD, C. A.';
                            $sede = 'FAU';
                        }

                        if ($_SERVER['HTTP_HOST'] == 'cpharmaftn.com') {
                            echo 'FARMACIA TIERRA NIEGRA, C. A.';
                            $sede = 'FTN';
                        }

                        if ($_SERVER['HTTP_HOST'] == 'cpharmafll.com') {
                            echo 'FARMACIA LA LAGO, C. A.';
                            $sede = 'FLL';
                        }

                        if ($_SERVER['HTTP_HOST'] == 'cpharmafsm.com') {
                            echo 'FARMACIA MILLENNIUM 2000, C.A';
                            $sede = 'FSM';
                        }
                    @endphp
                </td>
                </tr>
                <tr>
                <td colspan="3" class="alinear-der">Desde:</td>
                <td colspan="5" class="alinear-izq">
                    @php
                        if ($clave == 'FTN') {
                            echo 'FARMACIA TIERRA NIEGRA, C. A.';
                        }

                        if ($clave == 'FAU') {
                            echo 'FARMACIA AVENIDA UNIVERSIDAD, C. A.';
                        }

                        if ($clave == 'FLL') {
                            echo 'FARMACIA LA LAGO, C. A.';
                        }

                        if ($clave == 'FSM') {
                            echo 'FARMACIA MILLENNIUM 2000, C.A';
                        }
                    @endphp
                </td>
                </tr>
                <td colspan="3" class="alinear-der">Operador:</td>
                <td colspan="5" class="alinear-izq">{{ Auth()->user()->name }}</td>
                </tr>
                <thead>
                <tr>
                        <th scope="row">#</th>
                        <th scope="row">Codigo interno</th>
                        <th scope="row">Codigo de barra</th>
                        <th scope="row">Descripcion</th>
                        <th scope="row">Precio Bs.</th>
                        <th scope="row">Precio $</th>
                        <th scope="row">Cantidad</th>
                        <th scope="row">Acciones</th>
                </tr>
                </thead>
                <tbody>
                    @foreach(session()->get('traslado')[$clave] as $item)
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
                            <td class="text-center">{{ $loop->iteration }}</td>
                            <td class="text-center">{{ $codigo_interno }}</td>
                            <td class="text-center">{{ $codigo_barra }}</td>
                            <td class="text-center">{{ $descripcion }}</td>
                            <td class="text-center">{{ number_format($precio, 2, ',', '.') }}</td>
                            <td class="text-center">{{ number_format($precio_usd, 2, ',', '.') }}</td>
                            <td class="text-center">{{ $cantidad }}</td>
                            <td class="text-center">
                                <a onclick="confirmarEliminacion('/trasladoRecibir/{{ $codigo_barra }}/{{ $clave }}')" href="#" class="btn btn-danger btn-sm">
                                    <i class="fa fa-trash"></i> Eliminar
                                </a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </tbody>
            </table>
        @endforeach
    @endif
@endsection
