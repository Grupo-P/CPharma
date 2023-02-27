<?php

namespace compras\Http\Controllers;

use DB;

class EtiquetaMovilController extends Controller
{
    public function index()
    {
        include(app_path().'\functions\config.php');
        include(app_path().'\functions\functions.php');
        include(app_path().'\functions\querys_mysql.php');
        include(app_path().'\functions\querys_sqlserver.php');

        $codigo_barra = request()->codigo_barra;

        $sql = "SELECT
            --Id Articulo
                InvArticulo.Id AS IdArticulo,
            --Categoria Articulo
              InvArticulo.InvCategoriaId ,
            --Codigo Interno
                InvArticulo.CodigoArticulo AS CodigoInterno,
            --Codigo de Barra
                (SELECT CodigoBarra
                FROM InvCodigoBarra
                WHERE InvCodigoBarra.InvArticuloId = InvArticulo.Id
                AND InvCodigoBarra.EsPrincipal = 1) AS CodigoBarra,
            --Descripcion
                InvArticulo.Descripcion,
            --Impuesto (1 SI aplica impuesto, 0 NO aplica impuesto)
                (ISNULL(InvArticulo.FinConceptoImptoIdCompra,CAST(0 AS INT))) AS Impuesto,
            --Troquelado (0 NO es Troquelado, Id Articulo SI es Troquelado)
                (ISNULL((SELECT
                InvArticuloAtributo.InvArticuloId
                FROM InvArticuloAtributo
                WHERE InvArticuloAtributo.InvAtributoId =
                (SELECT InvAtributo.Id
                FROM InvAtributo
                WHERE
                InvAtributo.Descripcion = 'Troquelados'
                OR  InvAtributo.Descripcion = 'troquelados')
                AND InvArticuloAtributo.InvArticuloId = InvArticulo.Id),CAST(0 AS INT))) AS Troquelado,
            --UtilidadArticulo (Utilidad del articulo, Utilidad es 1.00 NO considerar la utilidad para el calculo de precio)
                ROUND(CAST(1-((ISNULL(ROUND(CAST((SELECT VenCondicionVenta.PorcentajeUtilidad
                    FROM VenCondicionVenta
                    WHERE VenCondicionVenta.Id = (
                    SELECT VenCondicionVenta_VenCondicionVentaArticulo.Id
                    FROM VenCondicionVenta_VenCondicionVentaArticulo
                    WHERE VenCondicionVenta_VenCondicionVentaArticulo.InvArticuloId = InvArticulo.Id)) AS DECIMAL(38,4)),2,0),CAST(0 AS INT)))/100)AS DECIMAL(38,4)),2,0) AS UtilidadArticulo,
            --UtilidadCategoria (Utilidad de la categoria, Utilidad es 1.00 NO considerar la utilidad para el calculo de precio)
                ROUND(CAST(1-((ISNULL(ROUND(CAST((SELECT VenCondicionVenta.PorcentajeUtilidad
              FROM VenCondicionVenta
              WHERE VenCondicionVenta.id = (
                SELECT VenCondicionVenta_VenCondicionVentaCategoria.Id
                FROM VenCondicionVenta_VenCondicionVentaCategoria
                WHERE VenCondicionVenta_VenCondicionVentaCategoria.InvCategoriaId = InvArticulo.InvCategoriaId)) AS DECIMAL(38,4)),2,0),CAST(0 AS INT)))/100)AS DECIMAL(38,4)),2,0) AS UtilidadCategoria,
            --Precio Troquel Almacen 1
                (ROUND(CAST((SELECT TOP 1
                InvLote.M_PrecioTroquelado
                FROM InvLoteAlmacen
                INNER JOIN InvLote ON InvLote.Id = InvLoteAlmacen.InvLoteId
                WHERE(InvLoteAlmacen.InvAlmacenId = '1')
                AND (InvLoteAlmacen.InvArticuloId = InvArticulo.Id)
                AND (InvLoteAlmacen.Existencia>0)
                ORDER BY invlote.M_PrecioTroquelado DESC)AS DECIMAL(38,4)),4,0)) AS TroquelAlmacen1,
            --Precio Compra Bruto Almacen 1
                (ROUND(CAST((SELECT TOP 1
                InvLote.M_PrecioCompraBruto
                FROM InvLoteAlmacen
                INNER JOIN InvLote ON InvLote.Id = InvLoteAlmacen.InvLoteId
                WHERE (InvLoteAlmacen.InvArticuloId = InvArticulo.Id)
                AND (InvLoteAlmacen.Existencia>0)
              AND (InvLoteAlmacen.InvAlmacenId = '1')
                ORDER BY invlote.M_PrecioCompraBruto DESC)AS DECIMAL(38,2)),2,0)) AS PrecioCompraBrutoAlmacen1,
            --Precio Troquel Almacen 2
                (ROUND(CAST((SELECT TOP 1
                InvLote.M_PrecioTroquelado
                FROM InvLoteAlmacen
                INNER JOIN InvLote ON InvLote.Id = InvLoteAlmacen.InvLoteId
                WHERE(InvLoteAlmacen.InvAlmacenId = '2')
                AND (InvLoteAlmacen.InvArticuloId = InvArticulo.Id)
                AND (InvLoteAlmacen.Existencia>0)
                ORDER BY invlote.M_PrecioTroquelado DESC)AS DECIMAL(38,2)),2,0)) AS TroquelAlmacen2,
            --Precio Compra Bruto Almacen 2
                (ROUND(CAST((SELECT TOP 1
                InvLote.M_PrecioCompraBruto
                FROM InvLoteAlmacen
                INNER JOIN InvLote ON InvLote.Id = InvLoteAlmacen.InvLoteId
                WHERE (InvLoteAlmacen.InvArticuloId = InvArticulo.Id)
                AND (InvLoteAlmacen.Existencia>0)
              AND (InvLoteAlmacen.InvAlmacenId = '2')
                ORDER BY invlote.M_PrecioCompraBruto DESC)AS DECIMAL(38,2)),2,0)) AS PrecioCompraBrutoAlmacen2,
            --Precio Compra Bruto
                (ROUND(CAST((SELECT TOP 1
                InvLote.M_PrecioCompraBruto
                FROM InvLoteAlmacen
                INNER JOIN InvLote ON InvLote.Id = InvLoteAlmacen.InvLoteId
                WHERE (InvLoteAlmacen.InvArticuloId = InvArticulo.Id)
                AND (InvLoteAlmacen.Existencia>0)
                ORDER BY invlote.M_PrecioCompraBruto DESC)AS DECIMAL(38,2)),2,0)) AS PrecioCompraBruto,
            --Existencia (Segun el almacen del filtro)
                (ROUND(CAST((SELECT SUM (InvLoteAlmacen.Existencia) As Existencia
                            FROM InvLoteAlmacen
                            WHERE(InvLoteAlmacen.InvAlmacenId = 1 OR InvLoteAlmacen.InvAlmacenId = 2)
                            AND (InvLoteAlmacen.InvArticuloId = InvArticulo.Id)) AS DECIMAL(38,0)),2,0))  AS Existencia,
            --ExistenciaAlmacen1 (Segun el almacen del filtro)
                (ROUND(CAST((SELECT SUM (InvLoteAlmacen.Existencia) As Existencia
                            FROM InvLoteAlmacen
                            WHERE(InvLoteAlmacen.InvAlmacenId = 1)
                            AND (InvLoteAlmacen.InvArticuloId = InvArticulo.Id)) AS DECIMAL(38,0)),2,0))  AS ExistenciaAlmacen1,
            --ExistenciaAlmacen2 (Segun el almacen del filtro)
                (ROUND(CAST((SELECT SUM (InvLoteAlmacen.Existencia) As Existencia
                            FROM InvLoteAlmacen
                            WHERE(InvLoteAlmacen.InvAlmacenId = 2)
                            AND (InvLoteAlmacen.InvArticuloId = InvArticulo.Id)) AS DECIMAL(38,0)),2,0))  AS ExistenciaAlmacen2,
            --Dolarizado (0 NO es dolarizado, Id Articulo SI es dolarizado)
                (ISNULL((SELECT
                InvArticuloAtributo.InvArticuloId
                FROM InvArticuloAtributo
                WHERE InvArticuloAtributo.InvAtributoId =
                (SELECT InvAtributo.Id
                FROM InvAtributo
                WHERE
                InvAtributo.Descripcion = 'Dolarizados'
                OR  InvAtributo.Descripcion = 'Giordany'
                OR  InvAtributo.Descripcion = 'giordany')
                AND InvArticuloAtributo.InvArticuloId = InvArticulo.Id),CAST(0 AS INT))) AS Dolarizado,
            --Tipo Producto (0 Miscelaneos, Id Articulo Medicinas)
                (ISNULL((SELECT
                InvArticuloAtributo.InvArticuloId
                FROM InvArticuloAtributo
                WHERE InvArticuloAtributo.InvAtributoId =
                (SELECT InvAtributo.Id
                FROM InvAtributo
                WHERE
                InvAtributo.Descripcion = 'Medicina')
                AND InvArticuloAtributo.InvArticuloId = InvArticulo.Id),CAST(0 AS INT))) AS Tipo,
            --Articulo Estrella (0 NO es Articulo Estrella , Id SI es Articulo Estrella)
                (ISNULL((SELECT
                InvArticuloAtributo.InvArticuloId
                FROM InvArticuloAtributo
                WHERE InvArticuloAtributo.InvAtributoId =
                (SELECT InvAtributo.Id
                FROM InvAtributo
                WHERE
                InvAtributo.Descripcion = 'Articulo Estrella')
                AND InvArticuloAtributo.InvArticuloId = InvArticulo.Id),CAST(0 AS INT))) AS ArticuloEstrella,
            --Marca
                InvMarca.Nombre as Marca,
            -- Ultima Venta (Fecha)
                (SELECT TOP 1
                CONVERT(DATE,VenFactura.FechaDocumento)
                FROM VenFactura
                INNER JOIN VenFacturaDetalle ON VenFacturaDetalle.VenFacturaId = VenFactura.Id
                WHERE VenFacturaDetalle.InvArticuloId = InvArticulo.Id
                ORDER BY FechaDocumento DESC) AS UltimaVenta,
            --Tiempo sin Venta (En dias)
                (SELECT TOP 1
                DATEDIFF(DAY,CONVERT(DATE,VenFactura.FechaDocumento),GETDATE())
                FROM VenFactura
                INNER JOIN VenFacturaDetalle ON VenFacturaDetalle.VenFacturaId = VenFactura.Id
                WHERE VenFacturaDetalle.InvArticuloId = InvArticulo.Id
                ORDER BY FechaDocumento DESC) AS TiempoSinVenta,
            --Ultimo Lote (Fecha)
                (SELECT TOP 1
                CONVERT(DATE,InvLote.FechaEntrada) AS UltimoLote
                FROM InvLote
                WHERE InvLote.InvArticuloId  = InvArticulo.Id
                ORDER BY UltimoLote DESC) AS UltimoLote,
            --Tiempo Tienda (En dias)
                (SELECT TOP 1
                DATEDIFF(DAY,CONVERT(DATE,InvLote.FechaEntrada),GETDATE())
                FROM InvLoteAlmacen
                INNER JOIN invlote on invlote.id = InvLoteAlmacen.InvLoteId
                WHERE InvLotealmacen.InvArticuloId = InvArticulo.Id
                ORDER BY InvLote.Auditoria_FechaCreacion DESC) AS TiempoTienda,
            --Ultimo Precio Sin Iva
                (SELECT TOP 1
                (ROUND(CAST((VenVentaDetalle.M_PrecioNeto) AS DECIMAL(38,2)),2,0))
                FROM VenVenta
                INNER JOIN VenVentaDetalle ON VenVentaDetalle.VenVentaId = VenVenta.Id
                WHERE VenVentaDetalle.InvArticuloId = InvArticulo.Id
                ORDER BY VenVenta.FechaDocumentoVenta DESC) AS UltimoPrecio,
            -- Ultimo Proveedor (Id Proveedor)
                (SELECT TOP 1
                ComProveedor.Id
                FROM ComFacturaDetalle
                INNER JOIN ComFactura ON ComFactura.Id = ComFacturaDetalle.ComFacturaId
                INNER JOIN ComProveedor ON ComProveedor.Id = ComFactura.ComProveedorId
                INNER JOIN GenPersona ON GenPersona.Id = ComProveedor.GenPersonaId
                WHERE ComFacturaDetalle.InvArticuloId = InvArticulo.Id
                ORDER BY ComFactura.FechaDocumento DESC) AS  UltimoProveedorID,
            -- Ultimo Proveedor (Nombre Proveedor)
                (SELECT TOP 1
                GenPersona.Nombre
                FROM ComFacturaDetalle
                INNER JOIN ComFactura ON ComFactura.Id = ComFacturaDetalle.ComFacturaId
                INNER JOIN ComProveedor ON ComProveedor.Id = ComFactura.ComProveedorId
                INNER JOIN GenPersona ON GenPersona.Id = ComProveedor.GenPersonaId
                WHERE ComFacturaDetalle.InvArticuloId = InvArticulo.Id
                ORDER BY ComFactura.FechaDocumento DESC) AS  UltimoProveedorNombre
            --Tabla principal
                FROM InvArticulo
            --Joins
                LEFT JOIN InvLoteAlmacen ON InvLoteAlmacen.InvArticuloId = InvArticulo.Id
                LEFT JOIN InvArticuloAtributo ON InvArticuloAtributo.InvArticuloId = InvArticulo.Id
                LEFT JOIN InvAtributo ON InvAtributo.Id = InvArticuloAtributo.InvAtributoId
                LEFT JOIN InvMarca ON InvMarca.Id = InvArticulo.InvMarcaId
            --Condicionales
                WHERE (SELECT CodigoBarra
                FROM InvCodigoBarra
                WHERE InvCodigoBarra.InvArticuloId = InvArticulo.Id
                AND InvCodigoBarra.EsPrincipal = 1) = '$codigo_barra'
            --Agrupamientos
                GROUP BY InvArticulo.Id, InvArticulo.CodigoArticulo, InvArticulo.Descripcion, InvArticulo.FinConceptoImptoIdCompra, InvArticulo.InvCategoriaId, InvMarca.Nombre
            --Ordanamiento
                ORDER BY InvArticulo.Id ASC
        ";

        $SedeConnection = FG_Mi_Ubicacion();

        $conn = FG_Conectar_Smartpharma($SedeConnection);

        $result = sqlsrv_query($conn,$sql);
        
        $row = sqlsrv_fetch_array($result,SQLSRV_FETCH_ASSOC);

        $Dolarizado = $row["Dolarizado"];
        $dolarizado = FG_Producto_Dolarizado($Dolarizado);

        $IdArticulo = $row["IdArticulo"];
        $Existencia = $row["Existencia"];
        $ExistenciaAlmacen1 = $row["ExistenciaAlmacen1"];
        $ExistenciaAlmacen2 = $row["ExistenciaAlmacen2"];
        $IsTroquelado = $row["Troquelado"];
        $UtilidadArticulo = $row["UtilidadArticulo"];
        $UtilidadCategoria = $row["UtilidadCategoria"];
        $TroquelAlmacen1 = $row["TroquelAlmacen1"];
        $PrecioCompraBrutoAlmacen1 = $row["PrecioCompraBrutoAlmacen1"];
        $TroquelAlmacen2 = $row["TroquelAlmacen2"];
        $PrecioCompraBrutoAlmacen2 = $row["PrecioCompraBrutoAlmacen2"];
        $PrecioCompraBruto = $row["PrecioCompraBruto"];
        $IsIVA = $row["Impuesto"];
        $CondicionExistencia = 'CON_EXISTENCIA';

        $precio = FG_Calculo_Precio_Alfa($Existencia, $ExistenciaAlmacen1, $ExistenciaAlmacen2, $IsTroquelado, $UtilidadArticulo, $UtilidadCategoria, $TroquelAlmacen1, $PrecioCompraBrutoAlmacen1, $TroquelAlmacen2, $PrecioCompraBrutoAlmacen2, $PrecioCompraBruto, $IsIVA, $CondicionExistencia);

        $connCPharma = FG_Conectar_CPharma();

        $tasa = FG_Tasa_Fecha($connCPharma,date('Y-m-d'));

        $ResultCPharma = mysqli_query($connCPharma,"SELECT * FROM unidads WHERE codigo_barra = '$codigo_barra'");
        $RowCPharma = mysqli_fetch_assoc($ResultCPharma);

        $fecha_ayer = (date_modify(date_create(), '-1day'))->format('Y-m-d');

        $query1 = mysqli_query($connCPharma, "SELECT * FROM dias_ceros WHERE id_articulo = '$IdArticulo' AND fecha_captura = '$fecha_ayer'");
        $row1 = mysqli_fetch_assoc($query1);

        $response['codigo_barra'] = $row['CodigoBarra'];
        $response['descripcion'] = $row['Descripcion'];
        $response['dolarizado'] = $dolarizado;
        $response['precio'] = number_format($precio, 2);
        $response['precio_ayer'] = null;

        if ($row1['precio']) {
            $response['precio_ayer'] = number_format($row1['precio'], 2);
        }

        if ($dolarizado == 'SI') {
            $response['precio'] = number_format($precio/$tasa, 2);
        }

        if ($dolarizado == 'SI' && $row1['precio']) {
            $response['precio_ayer'] = number_format($row1['precio_dolar'], 2);
        }

        $response['divisor'] = $RowCPharma['divisor'];
        $response['unidad'] = $RowCPharma['unidad_minima'];        

        return response()->json($response, 200);
    }
}
