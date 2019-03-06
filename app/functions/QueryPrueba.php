<?php
/*
IF OBJECT_ID ('TablaTemp', 'U') IS NOT NULL
	DROP TABLE TablaTemp;
IF OBJECT_ID ('TablaTemp1', 'U') IS NOT NULL
	DROP TABLE TablaTemp1;
IF OBJECT_ID ('TablaTemp2', 'U') IS NOT NULL
	DROP TABLE TablaTemp2;
IF OBJECT_ID ('TablaTemp3', 'U') IS NOT NULL
	DROP TABLE TablaTemp3;
IF OBJECT_ID ('TablaTemp4', 'U') IS NOT NULL
	DROP TABLE TablaTemp4;
IF OBJECT_ID ('TablaTemp5', 'U') IS NOT NULL
	DROP TABLE TablaTemp5;

--BUSQUEDA DE PROUCTOS POR DESCRIPCION
SELECT 
InvArticulo.Id, 
InvArticulo.CodigoArticulo,
InvArticulo.Descripcion,
InvArticulo.FinConceptoImptoIdCompra AS ConceptoImpuesto
INTO TablaTemp
FROM InvArticulo
WHERE 
InvArticulo.Descripcion LIKE '%omeprazol%'
ORDER BY InvArticulo.Descripcion ASC

--UNIDADES VENDIDAS Y VECES FACTURADO
SELECT
TablaTemp.Id,
TablaTemp.CodigoArticulo,
TablaTemp.Descripcion, 
COUNT(*) AS VecesFacturadoCliente, 
SUM(VenFacturaDetalle.Cantidad) AS UnidadesVendidasCliente 
INTO TablaTemp1
FROM VenFacturaDetalle
INNER JOIN TablaTemp ON TablaTemp.Id = VenFacturaDetalle.InvArticuloId
INNER JOIN VenFactura ON VenFactura.Id = VenFacturaDetalle.VenFacturaId
WHERE
(VenFactura.FechaDocumento > '2018-01-01' AND VenFactura.FechaDocumento < '2018-12-05')
GROUP BY TablaTemp.Id,TablaTemp.CodigoArticulo,TablaTemp.Descripcion
ORDER BY TablaTemp.Descripcion ASC

--UNIDADES DEVULTAS POR CLIENTES Y VECES DEVUELTAS
SELECT
TablaTemp.Id,
TablaTemp.CodigoArticulo,
TablaTemp.Descripcion,
COUNT(*) AS VecesDevueltaCliente,
SUM(VenDevolucionDetalle.Cantidad) AS UnidadesDevueltaCliente
INTO TablaTemp2
FROM VenDevolucionDetalle
INNER JOIN TablaTemp ON TablaTemp.Id = VenDevolucionDetalle.InvArticuloId
INNER JOIN VenDevolucion ON VenDevolucion.Id = VenDevolucionDetalle.VenDevolucionId
WHERE
(VenDevolucion.FechaDocumento > '2018-01-01' AND VenDevolucion.FechaDocumento < '2018-12-05')
GROUP BY TablaTemp.Id,TablaTemp.CodigoArticulo,TablaTemp.Descripcion
ORDER BY TablaTemp.Descripcion ASC

--CANTIDAD COMPRADA A PROVEEDORES
SELECT
TablaTemp.Id,
TablaTemp.CodigoArticulo,
TablaTemp.Descripcion, 
COUNT(*) AS VecesFacturadaProveedor,
SUM(ComFacturaDetalle.CantidadFacturada) AS CantidadFacturadaProveedor
INTO TablaTemp3
FROM ComFactura
INNER JOIN ComFacturaDetalle ON ComFacturaDetalle.ComFacturaId = ComFactura.Id
INNER JOIN TablaTemp ON TablaTemp.Id = ComFacturaDetalle.InvArticuloId
WHERE
(ComFactura.FechaDocumento > '2018-01-01' AND ComFactura.FechaDocumento < '2018-12-05')
GROUP BY TablaTemp.Id,TablaTemp.CodigoArticulo,TablaTemp.Descripcion
ORDER BY TablaTemp.Descripcion ASC

--CANTIDAD DEVUELTA PROVEEDOR
SELECT
TablaTemp.Id,
TablaTemp.CodigoArticulo,
TablaTemp.Descripcion,
COUNT(*) AS VecesReclamoProveedor,
SUM(ComReclamoDetalle.Cantidad) AS CantidadReclamoProveedor
INTO TablaTemp4
FROM ComReclamoDetalle
INNER JOIN TablaTemp ON TablaTemp.Id = ComReclamoDetalle.InvArticuloId
INNER JOIN ComReclamo ON ComReclamo.Id = ComReclamoDetalle.ComReclamoId
WHERE
(ComReclamo.FechaRegistro > '2018-01-01' AND ComReclamo.FechaRegistro < '2018-12-05')
GROUP BY TablaTemp.Id,TablaTemp.CodigoArticulo,TablaTemp.Descripcion
ORDER BY TablaTemp.Descripcion ASC

--EXIETENCIA Y RANGO DIAS
SELECT
TablaTemp.Id, 
TablaTemp.CodigoArticulo, 
TablaTemp.Descripcion,  
SUM(InvLoteAlmacen.Existencia) AS Existencia,
DATEDIFF(day,'2018-01-01','2018-12-05') As RangoDias
INTO TablaTemp5
FROM TablaTemp
INNER JOIN InvArticulo ON InvArticulo.CodigoArticulo = TablaTemp.CodigoArticulo
INNER JOIN InvLoteAlmacen ON InvLoteAlmacen.InvArticuloId = InvArticulo.Id
WHERE (InvLoteAlmacen.InvAlmacenId = 1 or InvLoteAlmacen.InvAlmacenId = 2)
GROUP BY TablaTemp.Id,TablaTemp.CodigoArticulo, TablaTemp.Descripcion

SELECT * FROM TablaTemp
--SELECT * FROM TablaTemp1
--SELECT * FROM TablaTemp2
--SELECT * FROM TablaTemp3
--SELECT * FROM TablaTemp4
SELECT * FROM TablaTemp5
 */
?>