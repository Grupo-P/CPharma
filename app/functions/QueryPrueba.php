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
IF OBJECT_ID ('TablaTemp6', 'U') IS NOT NULL
	DROP TABLE TablaTemp6;

--BUSQUEDA DE PROUCTOS POR DESCRIPCION
SELECT 
InvArticulo.Id, 
InvArticulo.CodigoArticulo,
InvArticulo.Descripcion,
InvArticulo.FinConceptoImptoIdCompra AS ConceptoImpuesto
INTO TablaTemp
FROM InvArticulo
WHERE 
InvArticulo.Descripcion LIKE '%atamel%'
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

--INTEGRACION DE TABLAS
SELECT 
TablaTemp.Id,
TablaTemp.CodigoArticulo,
TablaTemp.Descripcion,
TablaTemp.ConceptoImpuesto,
TablaTemp5.Existencia,
TablaTemp5.RangoDias,
TablaTemp1.VecesFacturadoCliente,
TablaTemp1.UnidadesVendidasCliente,
TablaTemp2.VecesDevueltaCliente,
TablaTemp2.UnidadesDevueltaCliente,
TablaTemp3.VecesFacturadaProveedor,
TablaTemp3.CantidadFacturadaProveedor,
TablaTemp4.VecesReclamoProveedor,
TablaTemp4.CantidadReclamoProveedor
INTO TablaTemp6
FROM TablaTemp
LEFT JOIN TablaTemp1 ON TablaTemp1.Id = TablaTemp.Id
LEFT JOIN TablaTemp2 ON TablaTemp2.Id = TablaTemp.Id
LEFT JOIN TablaTemp3 ON TablaTemp3.Id = TablaTemp.Id
LEFT JOIN TablaTemp4 ON TablaTemp4.Id = TablaTemp.Id
LEFT JOIN TablaTemp5 ON TablaTemp5.Id = TablaTemp.Id

--SELECT * FROM TablaTemp
--SELECT * FROM TablaTemp1
--SELECT * FROM TablaTemp2
--SELECT * FROM TablaTemp3
--SELECT * FROM TablaTemp4
--SELECT * FROM TablaTemp5
SELECT * FROM TablaTemp6
 */

/*
IF OBJECT_ID ('TablaTemp7', 'U') IS NOT NULL
	DROP TABLE TablaTemp7;
IF OBJECT_ID ('TablaTemp8', 'U') IS NOT NULL
	DROP TABLE TablaTemp8;
IF OBJECT_ID ('TablaTemp9', 'U') IS NOT NULL
	DROP TABLE TablaTemp9;
IF OBJECT_ID ('TablaTemp10', 'U') IS NOT NULL
	DROP TABLE TablaTemp10;
IF OBJECT_ID ('TablaTemp11', 'U') IS NOT NULL
	DROP TABLE TablaTemp11;
IF OBJECT_ID ('TablaTemp12', 'U') IS NOT NULL
	DROP TABLE TablaTemp12;


--INICIO PRECIO TROQUELADO
SELECT
InvLoteAlmacen.InvLoteId
into TablaTemp7
FROM InvLoteAlmacen 
WHERE (InvLoteAlmacen.InvAlmacenId = 1 OR InvLoteAlmacen.InvAlmacenId = 2) 
AND (InvLoteAlmacen.InvArticuloId = '25531')
AND (InvLoteAlmacen.Existencia>0)

SELECT
InvLote.Id,
invlote.M_PrecioCompraBruto,
invlote.M_PrecioTroquelado,
invlote.FechaEntrada
INTO TablaTemp8
FROM InvLote
INNER JOIN TablaTemp7 ON TablaTemp7.InvLoteId = InvLote.Id
ORDER BY invlote.M_PrecioTroquelado, invlote.M_PrecioCompraBruto DESC

SELECT TOP 1
TablaTemp8.M_PrecioTroquelado
INTO TablaTemp9
FROM TablaTemp8

--FIN PRECIO TROQUELADO

SELECT * FROM TablaTemp7
SELECT * FROM TablaTemp8
SELECT * FROM TablaTemp9

--INICIO PRECIO CALCULADO
SELECT
InvLoteAlmacen.InvLoteId
INTO TablaTemp10
FROM InvLoteAlmacen 
WHERE(InvLoteAlmacen.InvArticuloId = '25531')
AND (InvLoteAlmacen.Existencia>0)

SELECT
InvLote.Id,
invlote.M_PrecioCompraBruto,
invlote.M_PrecioTroquelado,
invlote.FechaEntrada
INTO TablaTemp11
from InvLote
INNER JOIN TablaTemp10 ON TablaTemp10.InvLoteId = InvLote.Id
ORDER BY invlote.M_PrecioTroquelado, invlote.M_PrecioCompraBruto DESC

SELECT TOP 1
TablaTemp11.M_PrecioCompraBruto
INTO TablaTemp12 
FROM TablaTemp11

--FIN PRECIO CALCULADO

SELECT * FROM TablaTemp10
SELECT * FROM TablaTemp11
SELECT * FROM TablaTemp12


--FECHA ULTIMO LOTE
SELECT TOP 1 
CONVERT(DATE,TablaTemp8.FechaEntrada) AS UltimoLote 
FROM TablaTemp8 
ORDER BY TablaTemp8.FechaEntrada DESC

--FECHA ULTIMO LOTE
SELECT TOP 1 
CONVERT(DATE,TablaTemp11.FechaEntrada) AS UltimoLote 
FROM TablaTemp11 
ORDER BY TablaTemp11.FechaEntrada DESC
 */
?>