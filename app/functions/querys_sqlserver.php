<?php
/*Querys que son ejecutadas en el manejador de base de datos SQL Server*/
/**********************************************************************************/
/*
    TITULO: QCleanTable
    PARAMETROS: [$NombreTabla] Nombre de la tabla para preparar
    FUNCION: Prepara la tabla para su uso
    RETORNO: Tabla preparada para usar
   */
  function QCleanTable($NombreTabla) {    
    $sql = "
      IF OBJECT_ID ('".$NombreTabla."', 'U') IS NOT NULL
        DROP TABLE ".$NombreTabla.";
    ";          
    return $sql;
  }
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
		(ROUND(CAST((InvAjusteDetalle.Cantidad) AS DECIMAL(38,0)),2,0))  AS Cantidad,
    InvAjusteDetalle.InvLoteId AS lote
		FROM InvAjusteDetalle 
		WHERE ((InvAjusteDetalle.InvAjusteId = '$IdAjuste') 
		AND (InvAjusteDetalle.M_TotalCostoDetalle < 0))
		";
		return $sql;
	}
  /**********************************************************************************/
  /*
    TITULO: SQG_Detalle_Articulo (Super Query Articulo)
    FUNCION: Query que genera el detalle del articulo solicitado
    RETORNO: Detalle del articulo
    DESAROLLADO POR: SERGIO COVA
  */
  function SQG_Detalle_Articulo($IdArticulo) {
    $sql = " 
    SELECT
  --Id Articulo
    InvArticulo.Id AS IdArticulo,
  --Categoria Articulo
    InvArticulo.InvCategoriaId,
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
    WHERE VenCondicionVenta_VenCondicionVentaArticulo.InvArticuloId = InvArticulo.Id)) AS DECIMAL(38,4)),2,0),CAST(0 AS INT)))/100)AS DECIMAL(38,2)),2,0) AS UtilidadArticulo,
  --UtilidadCategoria (Utilidad de la categoria, Utilidad es 1.00 NO considerar la utilidad para el calculo de precio)
    ROUND(CAST(1-((ISNULL(ROUND(CAST((SELECT VenCondicionVenta.PorcentajeUtilidad 
    FROM VenCondicionVenta 
    WHERE VenCondicionVenta.id = (
    SELECT VenCondicionVenta_VenCondicionVentaCategoria.Id 
    FROM VenCondicionVenta_VenCondicionVentaCategoria 
    WHERE VenCondicionVenta_VenCondicionVentaCategoria.InvCategoriaId = InvArticulo.InvCategoriaId)) AS DECIMAL(38,4)),2,0),CAST(0 AS INT)))/100)AS DECIMAL(38,2)),2,0) AS UtilidadCategoria,
  --Precio Troquel Almacen 1
    (ROUND(CAST((SELECT TOP 1
    InvLote.M_PrecioTroquelado
    FROM InvLoteAlmacen
    INNER JOIN InvLote ON InvLote.Id = InvLoteAlmacen.InvLoteId
    WHERE(InvLoteAlmacen.InvAlmacenId = '1')
    AND (InvLoteAlmacen.InvArticuloId = InvArticulo.Id)
    AND (InvLoteAlmacen.Existencia>0)
    ORDER BY invlote.M_PrecioTroquelado DESC)AS DECIMAL(38,2)),2,0)) AS TroquelAlmacen1,
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
  --Ultimo Proveedor (Id Proveedor)
    (SELECT TOP 1
    ComProveedor.Id
    FROM ComFacturaDetalle
    INNER JOIN ComFactura ON ComFactura.Id = ComFacturaDetalle.ComFacturaId
    INNER JOIN ComProveedor ON ComProveedor.Id = ComFactura.ComProveedorId
    INNER JOIN GenPersona ON GenPersona.Id = ComProveedor.GenPersonaId
    WHERE ComFacturaDetalle.InvArticuloId = InvArticulo.Id
    ORDER BY ComFactura.FechaDocumento DESC) AS  UltimoProveedorID,
  --Ultimo Proveedor (Nombre Proveedor)
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
    GROUP BY InvArticulo.Id, InvArticulo.CodigoArticulo, InvArticulo.Descripcion, InvArticulo.FinConceptoImptoIdCompra, InvArticulo.InvCategoriaId
  --Ordanamiento
    ORDER BY InvArticulo.Id ASC 
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
  /**********************************************************************************/
  /*
    TITULO: SQG_Detalle_Articulo_Lote (Super Query Articulo segun el lote) (Admite existencia 0)
    FUNCION: Query que genera el detalle del articulo solicitado a partir del lote
    RETORNO: Detalle del articulo
    DESAROLLADO POR: SERGIO COVA
  */
  function SQG_Detalle_Articulo_Lote($Idlote,$IdArticulo) {
    $sql = " 
    SELECT
  --Id Articulo
    InvArticulo.Id AS IdArticulo,
  --Categoria Articulo
    InvArticulo.InvCategoriaId,
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
    WHERE VenCondicionVenta_VenCondicionVentaArticulo.InvArticuloId = InvArticulo.Id)) AS DECIMAL(38,4)),2,0),CAST(0 AS INT)))/100)AS DECIMAL(38,2)),2,0) AS UtilidadArticulo,
  --UtilidadCategoria (Utilidad de la categoria, Utilidad es 1.00 NO considerar la utilidad para el calculo de precio)
    ROUND(CAST(1-((ISNULL(ROUND(CAST((SELECT VenCondicionVenta.PorcentajeUtilidad 
    FROM VenCondicionVenta 
    WHERE VenCondicionVenta.id = (
    SELECT VenCondicionVenta_VenCondicionVentaCategoria.Id 
    FROM VenCondicionVenta_VenCondicionVentaCategoria 
    WHERE VenCondicionVenta_VenCondicionVentaCategoria.InvCategoriaId = InvArticulo.InvCategoriaId)) AS DECIMAL(38,4)),2,0),CAST(0 AS INT)))/100)AS DECIMAL(38,2)),2,0) AS UtilidadCategoria,
  --Precio Troquel Almacen 1
    (ROUND(CAST((SELECT TOP 1
    InvLote.M_PrecioTroquelado
    FROM InvLoteAlmacen
    INNER JOIN InvLote ON InvLote.Id = InvLoteAlmacen.InvLoteId
    WHERE(InvLoteAlmacen.InvAlmacenId = '1')
    AND (InvLoteAlmacen.InvArticuloId = InvArticulo.Id)
    AND (InvLote.id = '$Idlote')
    ORDER BY invlote.M_PrecioTroquelado DESC)AS DECIMAL(38,2)),2,0)) AS TroquelAlmacen1,
  --Precio Compra Bruto Almacen 1
    (ROUND(CAST((SELECT TOP 1
    InvLote.M_PrecioCompraBruto
    FROM InvLoteAlmacen
    INNER JOIN InvLote ON InvLote.Id = InvLoteAlmacen.InvLoteId
    WHERE (InvLoteAlmacen.InvArticuloId = InvArticulo.Id)
    AND (InvLoteAlmacen.InvAlmacenId = '1')
    AND (InvLote.id = '$Idlote')
    ORDER BY invlote.M_PrecioCompraBruto DESC)AS DECIMAL(38,2)),2,0)) AS PrecioCompraBrutoAlmacen1,
  --Precio Troquel Almacen 2
    (ROUND(CAST((SELECT TOP 1
    InvLote.M_PrecioTroquelado
    FROM InvLoteAlmacen
    INNER JOIN InvLote ON InvLote.Id = InvLoteAlmacen.InvLoteId
    WHERE(InvLoteAlmacen.InvAlmacenId = '2')
    AND (InvLoteAlmacen.InvArticuloId = InvArticulo.Id)
    AND (InvLote.id = '$Idlote')
    ORDER BY invlote.M_PrecioTroquelado DESC)AS DECIMAL(38,2)),2,0)) AS TroquelAlmacen2,
  --Precio Compra Bruto Almacen 2
    (ROUND(CAST((SELECT TOP 1
    InvLote.M_PrecioCompraBruto
    FROM InvLoteAlmacen
    INNER JOIN InvLote ON InvLote.Id = InvLoteAlmacen.InvLoteId
    WHERE (InvLoteAlmacen.InvArticuloId = InvArticulo.Id)
    AND (InvLoteAlmacen.InvAlmacenId = '2')
    AND (InvLote.id = '$Idlote')
    ORDER BY invlote.M_PrecioCompraBruto DESC)AS DECIMAL(38,2)),2,0)) AS PrecioCompraBrutoAlmacen2,
  --Precio Compra Bruto
    (ROUND(CAST((SELECT TOP 1
    InvLote.M_PrecioCompraBruto
    FROM InvLoteAlmacen
    INNER JOIN InvLote ON InvLote.Id = InvLoteAlmacen.InvLoteId
    WHERE (InvLoteAlmacen.InvArticuloId = InvArticulo.Id)
    AND (InvLote.id = '$Idlote')
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
  --Ultimo Proveedor (Id Proveedor)
    (SELECT TOP 1
    ComProveedor.Id
    FROM ComFacturaDetalle
    INNER JOIN ComFactura ON ComFactura.Id = ComFacturaDetalle.ComFacturaId
    INNER JOIN ComProveedor ON ComProveedor.Id = ComFactura.ComProveedorId
    INNER JOIN GenPersona ON GenPersona.Id = ComProveedor.GenPersonaId
    WHERE ComFacturaDetalle.InvArticuloId = InvArticulo.Id
    ORDER BY ComFactura.FechaDocumento DESC) AS  UltimoProveedorID,
  --Ultimo Proveedor (Nombre Proveedor)
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
    GROUP BY InvArticulo.Id, InvArticulo.CodigoArticulo, InvArticulo.Descripcion, InvArticulo.FinConceptoImptoIdCompra, InvArticulo.InvCategoriaId
  --Ordanamiento
    ORDER BY InvArticulo.Id ASC 
    ";
    return $sql;
  }
  /********************************************************************************/
  /*
    TITULO: QG_Dias_EnCero
    PARAMETROS: No aplica
    FUNCION: Construir las columnas correspondientes al reporte
    RETORNO: Un String con la query
  */
  function QG_Dias_EnCero() {
    $sql = "
    SELECT
  --Id Articulo
    InvArticulo.Id AS IdArticulo,
  --Categoria Articulo
    InvArticulo.InvCategoriaId,
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
    WHERE VenCondicionVenta_VenCondicionVentaArticulo.InvArticuloId = InvArticulo.Id)) AS DECIMAL(38,4)),2,0),CAST(0 AS INT)))/100)AS DECIMAL(38,2)),2,0) AS UtilidadArticulo,
  --UtilidadCategoria (Utilidad de la categoria, Utilidad es 1.00 NO considerar la utilidad para el calculo de precio)
    ROUND(CAST(1-((ISNULL(ROUND(CAST((SELECT VenCondicionVenta.PorcentajeUtilidad 
    FROM VenCondicionVenta 
    WHERE VenCondicionVenta.id = (
    SELECT VenCondicionVenta_VenCondicionVentaCategoria.Id 
    FROM VenCondicionVenta_VenCondicionVentaCategoria 
    WHERE VenCondicionVenta_VenCondicionVentaCategoria.InvCategoriaId = InvArticulo.InvCategoriaId)) AS DECIMAL(38,4)),2,0),CAST(0 AS INT)))/100)AS DECIMAL(38,2)),2,0) AS UtilidadCategoria,
  --Precio Troquel Almacen 1
    (ROUND(CAST((SELECT TOP 1
    InvLote.M_PrecioTroquelado
    FROM InvLoteAlmacen
    INNER JOIN InvLote ON InvLote.Id = InvLoteAlmacen.InvLoteId
    WHERE(InvLoteAlmacen.InvAlmacenId = '1')
    AND (InvLoteAlmacen.InvArticuloId = InvArticulo.Id)
    AND (InvLoteAlmacen.Existencia>0)
    ORDER BY invlote.M_PrecioTroquelado DESC)AS DECIMAL(38,2)),2,0)) AS TroquelAlmacen1,
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
  --Ultimo Proveedor (Id Proveedor)
    (SELECT TOP 1
    ComProveedor.Id
    FROM ComFacturaDetalle
    INNER JOIN ComFactura ON ComFactura.Id = ComFacturaDetalle.ComFacturaId
    INNER JOIN ComProveedor ON ComProveedor.Id = ComFactura.ComProveedorId
    INNER JOIN GenPersona ON GenPersona.Id = ComProveedor.GenPersonaId
    WHERE ComFacturaDetalle.InvArticuloId = InvArticulo.Id
    ORDER BY ComFactura.FechaDocumento DESC) AS  UltimoProveedorID,
  --Ultimo Proveedor (Nombre Proveedor)
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
    WHERE InvLoteAlmacen.Existencia > 0
    AND (InvLoteAlmacen.InvAlmacenId = 1 OR InvLoteAlmacen.InvAlmacenId = 2)
  --Agrupamientos
    GROUP BY InvArticulo.Id, InvArticulo.CodigoArticulo, InvArticulo.Descripcion, InvArticulo.FinConceptoImptoIdCompra, InvArticulo.InvCategoriaId
  --Ordanamiento
    ORDER BY InvArticulo.Id ASC
    ";
    return $sql;
  }
  /**********************************************************************************/
  /*
    TITULO: QG_VecesVendida_ProductoCaida
    PARAMETROS: [$FInicial] Fecha inicial del rango a consultar
          [$FFinal] Fecha final del rango a consutar
    FUNCION: Consulta las Veces reclamo a proveedores y las unidades reclamo
    RETORNO: Tabla con los articulos, las veces reclamo y las unidades reclamo
   */
  function QG_ArtVendidos_ProductoCaida($FInicial,$FFinal) {
    $sql = "
    SELECT
    VenFacturaDetalle.InvArticuloId,
    ISNULL(COUNT(*),CAST(0 AS INT)) AS VecesVendidas,
    (ROUND(CAST(SUM(VenFacturaDetalle.Cantidad) AS DECIMAL(38,0)),2,0)) as UnidadesVendidas
    INTO CP_QG_Unidades_Vendidas
    FROM VenFacturaDetalle
    INNER JOIN VenFactura ON VenFactura.Id = VenFacturaDetalle.VenFacturaId
    WHERE
    (VenFactura.FechaDocumento > '$FInicial' AND VenFactura.FechaDocumento < '$FFinal')
    GROUP BY VenFacturaDetalle.InvArticuloId
    ORDER BY UnidadesVendidas DESC
    ";
    return $sql;
  }
  /**********************************************************************************/
  /*
    TITULO: QG_ArtDevueltos_ProductoCaida
    PARAMETROS: [$FInicial] Fecha inicial del rango a consultar
          [$FFinal] Fecha final del rango a consutar
    FUNCION: Consulta las Veces reclamo a proveedores y las unidades reclamo
    RETORNO: Tabla con los articulos, las veces reclamo y las unidades reclamo
   */
  function QG_ArtDevueltos_ProductoCaida($FInicial,$FFinal) {
    $sql = "
    SELECT
    VenDevolucionDetalle.InvArticuloId,
    ISNULL(COUNT(*),CAST(0 AS INT)) AS VecesDevueltas,
    (ROUND(CAST(SUM(VenDevolucionDetalle.Cantidad) AS DECIMAL(38,0)),2,0)) as UnidadesDevueltas
    INTO CP_QG_Unidades_Devueltas
    FROM VenDevolucionDetalle
    INNER JOIN VenDevolucion ON VenDevolucion.Id = VenDevolucionDetalle.VenDevolucionId
    WHERE
    (VenDevolucion.FechaDocumento > '$FInicial' AND VenDevolucion.FechaDocumento < '$FFinal')
    GROUP BY VenDevolucionDetalle.InvArticuloId
    ORDER BY UnidadesDevueltas DESC
    ";
    return $sql;
  }
  /**********************************************************************************/
  /*
    TITULO: QG_ArtComprados_ProductoCaida
    PARAMETROS: [$FInicial] Fecha inicial del rango a consultar
          [$FFinal] Fecha final del rango a consutar
    FUNCION: Consulta las Veces reclamo a proveedores y las unidades reclamo
    RETORNO: Tabla con los articulos, las veces reclamo y las unidades reclamo
   */
  function QG_ArtComprados_ProductoCaida($FInicial,$FFinal) {
    $sql = "
    SELECT
    ComFacturaDetalle.InvArticuloId,
    ISNULL(COUNT(*),CAST(0 AS INT)) AS VecesCompradas,
    (ROUND(CAST(SUM(ComFacturaDetalle.CantidadFacturada) AS DECIMAL(38,0)),2,0)) as UnidadesCompradas
    INTO CP_QG_Unidades_Compradas
    FROM ComFacturaDetalle      
    INNER JOIN ComFactura ON  ComFactura.Id = ComFacturaDetalle.ComFacturaId
    WHERE
    (ComFactura.FechaRegistro > '$FInicial' AND ComFactura.FechaRegistro < '$FFinal')
    GROUP BY ComFacturaDetalle.InvArticuloId
    ORDER BY UnidadesCompradas DESC
    ";
    return $sql;
  }
  /**********************************************************************************/
  /*
    TITULO: QG_ArtReclamados_ProductoCaida
    PARAMETROS: [$FInicial] Fecha inicial del rango a consultar
          [$FFinal] Fecha final del rango a consutar
    FUNCION: Consulta las Veces reclamo a proveedores y las unidades reclamo
    RETORNO: Tabla con los articulos, las veces reclamo y las unidades reclamo
   */
  function QG_ArtReclamados_ProductoCaida($FInicial,$FFinal) {
    $sql = "
    SELECT
    ComReclamoDetalle.InvArticuloId,
    ISNULL(COUNT(*),CAST(0 AS INT)) AS VecesReclamadas,
    (ROUND(CAST(SUM(ComReclamoDetalle.Cantidad) AS DECIMAL(38,0)),2,0)) as UnidadesReclamadas
    INTO CP_QG_Unidades_Reclamadas
    FROM ComReclamoDetalle
    INNER JOIN ComReclamo ON ComReclamo.Id = ComReclamoDetalle.ComReclamoId
    WHERE
    (ComReclamo.FechaRegistro > '$FInicial' AND ComReclamo.FechaRegistro < '$FFinal')
    GROUP BY ComReclamoDetalle.InvArticuloId
    ORDER BY UnidadesReclamadas DESC
    ";
    return $sql;
  }
  /**********************************************************************************/
  /*
    TITULO: QG_Integracion_ProductoCaida
    PARAMETROS: [$RangoDias] rango del dia para el pre filtrado
    FUNCION: Ubicar el top de productos mas vendidos
    RETORNO: Lista de productos mas vendidos
   */
  function QG_Integracion_ProductoCaida($RangoDias) {
      $sql = "
        SELECT
    CP_QG_Unidades_Vendidas.InvArticuloId,
    ((ISNULL(CP_QG_Unidades_Vendidas.VecesVendidas,CAST(0 AS INT))) - 
    (ISNULL(CP_QG_Unidades_Devueltas.VecesDevueltas,CAST(0 AS INT))) 
    ) AS VecesVendidas,
    ((ISNULL(CP_QG_Unidades_Vendidas.UnidadesVendidas,CAST(0 AS INT))) -
    (ISNULL(CP_QG_Unidades_Devueltas.UnidadesDevueltas,CAST(0 AS INT))) 
    ) AS UnidadesVendidas,
    ((ISNULL(CP_QG_Unidades_Compradas.VecesCompradas,CAST(0 AS INT))) -
    (ISNULL(CP_QG_Unidades_Reclamadas.VecesReclamadas,CAST(0 AS INT))) 
    ) AS VecesCompradas,
    ((ISNULL(CP_QG_Unidades_Compradas.UnidadesCompradas,CAST(0 AS INT))) -
    (ISNULL(CP_QG_Unidades_Reclamadas.UnidadesReclamadas,CAST(0 AS INT))) 
    ) AS UnidadesCompradas,
    (SELECT (ROUND(CAST(SUM (InvLoteAlmacen.Existencia) AS DECIMAL(38,0)),2,0)) As Existencia
      FROM InvLoteAlmacen
      WHERE(InvLoteAlmacen.InvAlmacenId = 1 OR InvLoteAlmacen.InvAlmacenId = 2)
      AND (InvLoteAlmacen.InvArticuloId = CP_QG_Unidades_Vendidas.InvArticuloId)) AS Existencia
    FROM CP_QG_Unidades_Vendidas
    LEFT JOIN CP_QG_Unidades_Devueltas ON CP_QG_Unidades_Devueltas.InvArticuloId = CP_QG_Unidades_Vendidas.InvArticuloId
    LEFT JOIN CP_QG_Unidades_Compradas ON CP_QG_Unidades_Compradas.InvArticuloId = CP_QG_Unidades_Vendidas.InvArticuloId
    LEFT JOIN CP_QG_Unidades_Reclamadas ON CP_QG_Unidades_Reclamadas.InvArticuloId = CP_QG_Unidades_Vendidas.InvArticuloId
    WHERE (VecesVendidas >= '$RangoDias') AND  (ISNULL(VecesCompradas,CAST(0 AS INT)) = 0) 
    AND ((SELECT (ROUND(CAST(SUM (InvLoteAlmacen.Existencia) AS DECIMAL(38,0)),2,0)) As Existencia
      FROM InvLoteAlmacen
      WHERE(InvLoteAlmacen.InvAlmacenId = 1 OR InvLoteAlmacen.InvAlmacenId = 2)
      AND (InvLoteAlmacen.InvArticuloId = CP_QG_Unidades_Vendidas.InvArticuloId))>0)
    ORDER BY VecesVendidas DESC
    ";
    return $sql;
  }
  /**********************************************************************************/
  /*
    TITULO: QG_Existencia_Actual
    PARAMETROS: no aplica
    FUNCION: busca los articulos en existencia hoy
    RETORNO: no aplica
  */
  function QG_Existencia_Actual() {
    $sql = "
      SELECT
      InvArticulo.Id AS IdArticulo,
      InvArticulo.CodigoArticulo AS CodigoInterno,
      InvArticulo.Descripcion
      FROM InvArticulo
      INNER JOIN InvLoteAlmacen ON InvArticulo.Id=InvLoteAlmacen.InvArticuloId
      WHERE InvLoteAlmacen.Existencia > 0
      AND (InvLoteAlmacen.InvAlmacenId = 1 OR InvLoteAlmacen.InvAlmacenId = 2)
      GROUP BY InvArticulo.Id, InvArticulo.CodigoArticulo, InvArticulo.Descripcion, InvArticulo.FinConceptoImptoIdCompra
      ORDER BY InvArticulo.Id ASC
    ";
    return $sql;
  }
  /**********************************************************************************/
  /*
    TITULO: QG_Etiqueta_Articulo
    PARAMETROS: [$IdArticulo] Id del articulo a buscar
    FUNCION: regresa los datos del articulo
    RETORNO: no aplica
  */
  function QG_Etiqueta_Articulo($IdArticulo) {
    $sql = "
      SELECT * FROM etiquetas
      WHERE etiquetas.id_articulo = '$IdArticulo'
    ";
    return $sql;
  }
  /**********************************************************************************/
  /*
    TITULO: QG_CuentaVenta
    PARAMETROS: $IdArticulo,$FInicial,$FFinal
    FUNCION: cuenta la cantidad de veces que se vendio un producto en una fecha
    RETORNO: no aplica
    //
   */
  function QG_CuentaVenta($IdArticulo,$FInicial,$FFinal) {
    $sql = "
      SELECT
      COUNT(*) AS Cuenta
      FROM VenFacturaDetalle
      INNER JOIN InvArticulo ON InvArticulo.Id = VenFacturaDetalle.InvArticuloId
      INNER JOIN VenFactura ON VenFactura.Id = VenFacturaDetalle.VenFacturaId
      WHERE
      (VenFactura.FechaDocumento > '$FInicial' AND VenFactura.FechaDocumento < '$FFinal')
      AND (InvArticulo.Id = '$IdArticulo')
    ";
    return $sql;
  }
  /**********************************************************************************/
  /*
    TITULO: QG_CP_Etiqueta_C1
    PARAMETROS: 
    FUNCION: 
    RETORNO: 
    */
    function QG_CP_Etiqueta_C1($FHoy,$FManana){
      $sql = " 
      SELECT 
      InvMovimiento.InvArticuloId AS IdArticulo,
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
            AND InvArticuloAtributo.InvArticuloId = InvMovimiento.InvArticuloId),CAST(0 AS INT))) AS Dolarizado
      --Tabla Principal and Tabla Temporal
      INTO CP_Etiqueta_C1
      FROM InvMovimiento
      --Condicionales
      WHERE(
        (InvMovimiento.InvCausaId<>3)
        AND (InvMovimiento.Auditoria_FechaActualizacion > '$FHoy')
        AND (InvMovimiento.Auditoria_FechaActualizacion < '$FManana')
        AND((SELECT (ROUND(CAST(SUM (InvLoteAlmacen.Existencia) AS DECIMAL(38,0)),2,0))
            FROM InvLoteAlmacen
            WHERE(InvLoteAlmacen.InvAlmacenId = 1 OR InvLoteAlmacen.InvAlmacenId = 2)
            AND (InvLoteAlmacen.InvArticuloId = InvMovimiento.InvArticuloId)) > 0)
      )
      --Ordenamiento
      GROUP BY InvMovimiento.InvArticuloId
      ORDER BY InvMovimiento.InvArticuloId ASC
      ";
    return $sql;
    }
     /**********************************************************************************/
    /*
    TITULO: QG_CP_Etiqueta_C2
    PARAMETROS: 
    FUNCION: 
    RETORNO: 
    */
    function QG_CP_Etiqueta_C2($FHoy,$FManana){
      $sql = " 
      SELECT
      VenCondicionVenta_VenCondicionVentaArticulo.InvArticuloId AS IdArticulo,
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
            AND InvArticuloAtributo.InvArticuloId = VenCondicionVenta_VenCondicionVentaArticulo.InvArticuloId),CAST(0 AS INT))) AS Dolarizado
      --Tabla Principal and Tabla Temporal
      INTO CP_Etiqueta_C2
      FROM VenCondicionVenta
      --Joins
      INNER JOIN VenCondicionVenta_VenCondicionVentaArticulo ON VenCondicionVenta_VenCondicionVentaArticulo.Id = VenCondicionVenta.Id
      --Condicionales
      WHERE(
        (VenCondicionVenta.Auditoria_FechaActualizacion > '$FHoy')
        AND (VenCondicionVenta.Auditoria_FechaActualizacion < '$FManana')
        AND((SELECT (ROUND(CAST(SUM (InvLoteAlmacen.Existencia) AS DECIMAL(38,0)),2,0))
            FROM InvLoteAlmacen
            WHERE(InvLoteAlmacen.InvAlmacenId = 1 OR InvLoteAlmacen.InvAlmacenId = 2)
            AND (InvLoteAlmacen.InvArticuloId = VenCondicionVenta_VenCondicionVentaArticulo.InvArticuloId)) > 0)
      )
      UNION
      SELECT CP_Etiqueta_C1.IdArticulo,CP_Etiqueta_C1.Dolarizado
      FROM CP_Etiqueta_C1
      ORDER BY IdArticulo
      ";
    return $sql;
    }
    /**********************************************************************************/
    /*
    TITULO: QG_CP_Etiqueta_C3
    PARAMETROS: 
    FUNCION: 
    RETORNO: 
    */
    function QG_CP_Etiqueta_C3($FHoy,$FManana){
      $sql = " 
      SELECT
      InvLoteAlmacen.InvArticuloId AS IdArticulo,
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
            AND InvArticuloAtributo.InvArticuloId = InvLoteAlmacen.InvArticuloId),CAST(0 AS INT))) AS Dolarizado
      --Tabla Principal and Tabla Temporal
      INTO CP_Etiqueta_C3
      FROM InvLoteAlmacen
      --Condicionales
      WHERE(
        (InvLoteAlmacen.Auditoria_FechaActualizacion > '$FHoy')
        AND (InvLoteAlmacen.Auditoria_FechaActualizacion < '$FManana')
        AND(InvLoteAlmacen.InvAlmacenId = 1 OR InvLoteAlmacen.InvAlmacenId = 2)
        AND (InvLoteAlmacen.Existencia = 0) 
      )
      UNION
      SELECT CP_Etiqueta_C2.IdArticulo,CP_Etiqueta_C2.Dolarizado
      FROM CP_Etiqueta_C2
      ORDER BY IdArticulo
      ";
    return $sql;
    }
    /**********************************************************************************/
    /*
    TITULO: QG_CP_Etiqueta_C4
    PARAMETROS: 
    FUNCION: 
    RETORNO: 
    */
    function QG_CP_Etiqueta_C4($FHoy,$FManana){
      $sql = " 
      SELECT
      InvLote.InvArticuloId AS IdArticulo,
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
            AND InvArticuloAtributo.InvArticuloId = InvLote.InvArticuloId),CAST(0 AS INT))) AS Dolarizado
      --Tabla Prinipal and Tabla Temporal
      INTO CP_Etiqueta_C4
      FROM InvLote 
      --Condicionales
      WHERE(
        (InvLote.Auditoria_FechaActualizacion > '$FHoy')
        AND (InvLote.Auditoria_FechaActualizacion < '$FManana')
        AND((SELECT (ROUND(CAST(SUM (InvLoteAlmacen.Existencia) AS DECIMAL(38,0)),2,0))
            FROM InvLoteAlmacen
            WHERE(InvLoteAlmacen.InvAlmacenId = 1 OR InvLoteAlmacen.InvAlmacenId = 2)
            AND (InvLoteAlmacen.InvArticuloId = InvLote.InvArticuloId)) > 0)
      )
      UNION
      SELECT CP_Etiqueta_C3.IdArticulo,CP_Etiqueta_C3.Dolarizado
      FROM CP_Etiqueta_C3
      ORDER BY IdArticulo
      ";
    return $sql;
    }
    /**********************************************************************************/
?>