<?php
/*
IF OBJECT_ID ('TablaTemp', 'U') IS NOT NULL
    DROP TABLE TablaTemp;
IF OBJECT_ID ('TablaTemp1', 'U') IS NOT NULL
	DROP TABLE TablaTemp1;

SELECT
InvArticulo.Id,
InvArticulo.CodigoArticulo,
InvArticulo.Descripcion,
COUNT(*) AS VecesFacturado, 
SUM(VenFacturaDetalle.Cantidad) AS UnidadesVendidas
INTO TablaTemp
FROM VenFacturaDetalle
INNER JOIN InvArticulo ON InvArticulo.Id = VenFacturaDetalle.InvArticuloId
INNER JOIN VenFactura ON VenFactura.Id = VenFacturaDetalle.VenFacturaId
WHERE
(VenFactura.FechaDocumento > '2018-12-01' AND VenFactura.FechaDocumento < '2018-12-10')
GROUP BY InvArticulo.Id,InvArticulo.CodigoArticulo,InvArticulo.Descripcion
ORDER BY InvArticulo.CodigoArticulo ASC

SELECT
TablaTemp.Id, 
TablaTemp.CodigoArticulo,
TablaTemp.Descripcion,
TablaTemp.VecesFacturado,
TablaTemp.UnidadesVendidas
INTO TablaTemp1
FROM TablaTemp
INNER JOIN InvArticulo ON InvArticulo.CodigoArticulo = TablaTemp.CodigoArticulo
INNER JOIN InvLoteAlmacen ON InvLoteAlmacen.InvArticuloId = InvArticulo.Id
WHERE (InvLoteAlmacen.InvAlmacenId = 1 or InvLoteAlmacen.InvAlmacenId = 2) AND InvLoteAlmacen.Existencia = 0
GROUP BY TablaTemp.Id,TablaTemp.CodigoArticulo,TablaTemp.Descripcion, TablaTemp.VecesFacturado, TablaTemp.UnidadesVendidas
ORDER BY TablaTemp.UnidadesVendidas DESC

SELECT * FROM TablaTemp1
 */
?>