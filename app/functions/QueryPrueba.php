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
?>