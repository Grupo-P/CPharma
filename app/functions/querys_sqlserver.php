<?php
/*Querys que son ejecutadas en el manejador de base de datos SQL Server*/
	/**********************************************************************************/
	/*
		TITULO: QG_Provedor_Unico
		FUNCION: Buscar en las facturas si hay otro proveedor que surta el producto
		RETORNO: Lista de proveedores que surten el producto 
		DESARROLLADO POR: SERGIO COVA
	 */
	function QG_Provedor_Unico($IdProveedor,$IdArticulo) {
		$sql = "
		SELECT
		ComProveedor.Id,
		GenPersona.Nombre
		FROM ComProveedor
		INNER JOIN GenPersona ON ComProveedor.GenPersonaId=GenPersona.Id
		INNER JOIN ComFactura ON ComFactura.ComProveedorId=ComProveedor.Id
		INNER JOIN ComFacturaDetalle ON ComFacturaDetalle.ComFacturaId = ComFactura.Id
		WHERE ComFactura.ComProveedorId <> '$IdProveedor' and ComFacturaDetalle.InvArticuloId = '$IdArticulo'
		GROUP BY ComProveedor.Id, GenPersona.Nombre
		ORDER BY ComProveedor.Id ASC
		";
		return $sql;
	}
	/**********************************************************************************/
	/*
		TITULO: QG_Articulos_Ajuste
		FUNCION: busca la lista de articulos que corresponden a un ajuste
		RETORNO: Lista de articulos del ajuste 
		DESARROLLADO POR: SERGIO COVA
	 */
	function QG_Articulos_Ajuste($IdAjuste) {
		$sql = "
		SELECT
		InvAjusteDetalle.InvArticuloId,
		(ROUND(CAST((InvAjusteDetalle.Cantidad) AS DECIMAL(38,0)),2,0))  AS Cantidad
		FROM InvAjusteDetalle 
		WHERE ((InvAjusteDetalle.InvAjusteId = '$IdAjuste') 
		AND (InvAjusteDetalle.M_TotalCostoDetalle < 0))
		";
		return $sql;
	}
	/**********************************************************************************/
  /*
    TITULO: QG_Detalle_Articulo
    FUNCION: Query que genera el detalle del articulo solicitado
    RETORNO: Detalle del articulo
    DESAROLLADO POR: SERGIO COVA
  */
  function QG_Detalle_Articulo($IdArticulo) {
    $sql = " 
      SELECT
    --Id Articulo
      InvArticulo.Id AS IdArticulo,
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
    --Utilidad (Utilidad del articulo, Utilidad es 1.00 NO considerar la utilidad para el calculo de precio)
      ROUND(CAST(1-((ISNULL(ROUND(CAST((SELECT VenCondicionVenta.PorcentajeUtilidad
          FROM VenCondicionVenta 
          WHERE VenCondicionVenta.Id = (
            SELECT VenCondicionVenta_VenCondicionVentaArticulo.Id
            FROM VenCondicionVenta_VenCondicionVentaArticulo 
            WHERE VenCondicionVenta_VenCondicionVentaArticulo.InvArticuloId = InvArticulo.Id)) AS DECIMAL(38,4)),2,0),CAST(0 AS INT)))/100)AS DECIMAL(38,2)),2,0) AS Utilidad,
    --Precio Troquel Almacen 1
      (ROUND(CAST((SELECT TOP 1
      InvLote.M_PrecioTroquelado
      FROM InvLoteAlmacen
      INNER JOIN InvLote ON InvLote.Id = InvLoteAlmacen.InvLoteId
      WHERE(InvLoteAlmacen.InvAlmacenId = '1')
      AND (InvLoteAlmacen.InvArticuloId = InvArticulo.Id)
      AND (InvLoteAlmacen.Existencia>0)
      ORDER BY invlote.M_PrecioTroquelado DESC)AS DECIMAL(38,2)),2,0)) AS TroquelAlmacen1,
    --Precio Troquel Almacen 2
      (ROUND(CAST((SELECT TOP 1
      InvLote.M_PrecioTroquelado
      FROM InvLoteAlmacen
      INNER JOIN InvLote ON InvLote.Id = InvLoteAlmacen.InvLoteId
      WHERE(InvLoteAlmacen.InvAlmacenId = '2')
      AND (InvLoteAlmacen.InvArticuloId = InvArticulo.Id)
      AND (InvLoteAlmacen.Existencia>0)
      ORDER BY invlote.M_PrecioTroquelado DESC)AS DECIMAL(38,2)),2,0)) AS TroquelAlmacen2,
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
    --Condicionales
      WHERE InvArticulo.Id = '$IdArticulo'
    --Agrupamientos
      GROUP BY InvArticulo.Id, InvArticulo.CodigoArticulo, InvArticulo.Descripcion, InvArticulo.FinConceptoImptoIdCompra
    --Ordanamiento
      ORDER BY InvArticulo.Id ASC 
    ";
    return $sql;
  }
?>