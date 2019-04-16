<?php
/*Productos por proveedor
******* SCRIPT 1 *****
SELECT 
GenPersona.Nombre,
ComProveedor.Id
FROM ComProveedor
INNER JOIN GenPersona ON ComProveedor.GenPersonaId=GenPersona.Id
INNER JOIN ComFactura ON ComFactura.ComProveedorId=ComProveedor.Id
GROUP by ComProveedor.Id, GenPersona.Nombre
ORDER BY ComProveedor.Id ASC

SELECT 
GenPersona.Nombre,
ComProveedor.Id
FROM ComProveedor
INNER JOIN GenPersona ON ComProveedor.GenPersonaId=GenPersona.Id
INNER JOIN ComFactura ON ComFactura.ComProveedorId=ComProveedor.Id 
GROUP by ComProveedor.Id, GenPersona.Nombre
ORDER BY GenPersona.Nombre ASC

******* SCRIPT 2 *****
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

--FACTURAS DONDE APARECE EL PROVEEDOR **OPTIMIZADO**
SELECT
ComFactura.Id AS FacturaId,
ComFactura.ComProveedorId AS FacturaProveedorId
INTO TablaTemp
FROM ComFactura
WHERE ComFactura.ComProveedorId = 33
ORDER BY FacturaId ASC

SELECT * FROM TablaTemp

--DETALLES DE FACTURAS DONDE APARECE EL PROVEEDOR **OPTIMIZADO**
SELECT 
ComFacturaDetalle.ComFacturaId AS FacturaIdDetalle,
ComFacturaDetalle.InvArticuloId AS ArtiuloId
INTO TablaTemp1
FROM ComFacturaDetalle
INNER JOIN TablaTemp ON TablaTemp.FacturaId = ComFacturaDetalle.ComFacturaId
ORDER BY FacturaIdDetalle ASC

SELECT * FROM TablaTemp1

--ARTICULOS QUE APARECEN EN LOS DETALLES DE LAS FACTURAS 21123 13913 28933 2736
SELECT
InvArticulo.Id AS ArticuloIdPro,
InvArticulo.Descripcion 
INTO TablaTemp2
FROM InvArticulo
INNER JOIN TablaTemp1 ON TablaTemp1.ArtiuloId = InvArticulo.Id
GROUP BY InvArticulo.Id,InvArticulo.Descripcion
ORDER BY InvArticulo.Descripcion ASC

SELECT * FROM TablaTemp2

******* SCRIPT 3 *****
IF OBJECT_ID ('TablaTemp', 'U') IS NOT NULL
	DROP TABLE TablaTemp;
IF OBJECT_ID ('TablaTemp1', 'U') IS NOT NULL
	DROP TABLE TablaTemp1;

SELECT
ComFacturaDetalle.ComFacturaId AS FacturaId
INTO TablaTemp
FROM ComFacturaDetalle
WHERE ComFacturaDetalle.InvArticuloId = 8840

SELECT * FROM TablaTemp

SELECT
ComFactura.ComProveedorId
INTO TablaTemp1
FROM TablaTemp 
INNER JOIN ComFactura ON ComFactura.Id = TablaTemp.FacturaId
WHERE ComFactura.ComProveedorId <> 33
GROUP BY ComFactura.ComProveedorId

SELECT * FROM TablaTemp1
 */

/* Reporte 6 prueba 27/03/2019

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

SELECT 
InvArticulo.Id, 
InvArticulo.CodigoArticulo,
InvArticulo.Descripcion,
InvArticulo.FinConceptoImptoIdCompra AS ConceptoImpuesto
INTO TablaTemp
FROM InvArticulo
WHERE 
InvArticulo.Descripcion LIKE '%torondoy%'
ORDER BY InvArticulo.Descripcion ASC
	
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
(VenFactura.FechaDocumento > '2018-10-09' AND VenFactura.FechaDocumento < '2019-03-27')
GROUP BY TablaTemp.Id,TablaTemp.CodigoArticulo,TablaTemp.Descripcion
ORDER BY TablaTemp.Descripcion ASC
	
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
(VenDevolucion.FechaDocumento > '2018-10-09' AND VenDevolucion.FechaDocumento < '2019-03-27')
GROUP BY TablaTemp.Id,TablaTemp.CodigoArticulo,TablaTemp.Descripcion
ORDER BY TablaTemp.Descripcion ASC
	
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
(ComFactura.FechaDocumento > '2018-10-09' AND ComFactura.FechaDocumento < '2019-03-27')
GROUP BY TablaTemp.Id,TablaTemp.CodigoArticulo,TablaTemp.Descripcion
ORDER BY TablaTemp.Descripcion ASC
	
SELECT
TablaTemp.Id,
TablaTemp.CodigoArticulo,
TablaTemp.Descripcion,
COUNT(*) AS VecesReclamoProveedor,
SUM(ComReclamoDetalle.Cantidad) AS CantidadReclamoProveedor
INTO TablaTemp4
FROM ComReclamoDetalle
INNER JOIN TablaTemp ON TablaTemp.Id =ComReclamoDetalle.InvArticuloId
INNER JOIN ComReclamo ON ComReclamo.Id = ComReclamoDetalle.ComReclamoId
WHERE
(ComReclamo.FechaRegistro > '2018-10-09' AND ComReclamo.FechaRegistro < '2019-03-27')
GROUP BY TablaTemp.Id,TablaTemp.CodigoArticulo,TablaTemp.Descripcion
ORDER BY TablaTemp.Descripcion ASC
	
SELECT
TablaTemp.Id, 
TablaTemp.CodigoArticulo, 
TablaTemp.Descripcion,  
SUM(InvLoteAlmacen.Existencia) AS Existencia
INTO TablaTemp5
FROM TablaTemp
INNER JOIN InvArticulo ON InvArticulo.CodigoArticulo = TablaTemp.CodigoArticulo
INNER JOIN InvLoteAlmacen ON InvLoteAlmacen.InvArticuloId = InvArticulo.Id
WHERE (InvLoteAlmacen.InvAlmacenId = 1 or InvLoteAlmacen.InvAlmacenId = 2)
GROUP BY TablaTemp.Id,TablaTemp.CodigoArticulo, TablaTemp.Descripcion
	
SELECT 
TablaTemp.Id,
TablaTemp.CodigoArticulo,
TablaTemp.Descripcion,
TablaTemp.ConceptoImpuesto,
TablaTemp5.Existencia,
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

SELECT * FROM TablaTemp6

 */
?>