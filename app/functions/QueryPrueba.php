<?php
/*Productos por proveedor
--LIMPIEZA DE TABLAS
IF OBJECT_ID ('TablaTemp', 'U') IS NOT NULL
	DROP TABLE TablaTemp;
IF OBJECT_ID ('TablaTemp1', 'U') IS NOT NULL
	DROP TABLE TablaTemp1;
IF OBJECT_ID ('TablaTemp2', 'U') IS NOT NULL
	DROP TABLE TablaTemp2;
IF OBJECT_ID ('TablaTemp3', 'U') IS NOT NULL
	DROP TABLE TablaTemp3;

--FACTURAS DONDE APARECE EL PROVEEDOR
SELECT
ComFactura.Id,
ComFactura.ComProveedorId
INTO TablaTemp
FROM ComFactura
WHERE ComFactura.ComProveedorId = 4
ORDER BY ComFactura.Id ASC

--DETALLES DE FACTURAS DONDE APARECE EL PROVEEDOR
SELECT 
ComFacturaDetalle.InvArticuloId,
ComFacturaDetalle.ComFacturaId
INTO TablaTemp1
FROM ComFacturaDetalle
INNER JOIN TablaTemp ON TablaTemp.Id = ComFacturaDetalle.ComFacturaId
ORDER BY ComFacturaDetalle.ComFacturaId ASC

--ARTICULOS QUE APARECEN EN LOS DETALLES DE LAS FACTURAS
SELECT
InvArticulo.Id,
InvArticulo.Descripcion 
INTO TablaTemp2
FROM InvArticulo
INNER JOIN TablaTemp1 ON TablaTemp1.InvArticuloId = InvArticulo.Id
ORDER BY InvArticulo.Descripcion ASC

SELECT * FROM TablaTemp
SELECT * FROM TablaTemp1
SELECT * FROM TablaTemp2
 */




/*
--LIMPIEZA DE TABLAS
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

--FACTURAS DONDE APARECE EL PROVEEDOR
SELECT
ComFactura.Id,
ComFactura.ComProveedorId
INTO TablaTemp
FROM ComFactura
WHERE ComFactura.ComProveedorId = 20
ORDER BY ComFactura.Id ASC

--DETALLES DE FACTURAS DONDE APARECE EL PROVEEDOR
SELECT 
ComFacturaDetalle.InvArticuloId,
ComFacturaDetalle.ComFacturaId
INTO TablaTemp1
FROM ComFacturaDetalle
INNER JOIN TablaTemp ON TablaTemp.Id = ComFacturaDetalle.ComFacturaId
ORDER BY ComFacturaDetalle.InvArticuloId ASC

--ARTICULOS QUE APARECEN EN LOS DETALLES DE LAS FACTURAS
SELECT
InvArticulo.Id,
InvArticulo.Descripcion 
INTO TablaTemp2
FROM InvArticulo
INNER JOIN TablaTemp1 ON TablaTemp1.InvArticuloId = InvArticulo.Id
ORDER BY InvArticulo.Descripcion ASC

--SELECT * FROM TablaTemp
--SELECT * FROM TablaTemp1
SELECT * FROM TablaTemp2

--REGRESION PARA PRODUCTO UNICO
SELECT
TablaTemp2.Id AS IdArt,
TablaTemp2.Descripcion,
ComFacturaDetalle.ComFacturaId,
ComFacturaDetalle.InvArticuloId
INTO Tablatemp3
FROM ComFacturaDetalle
INNER JOIN TablaTemp2 ON TablaTemp2.Id = ComFacturaDetalle.InvArticuloId

SELECT
TablaTemp3.IdArt,
TablaTemp3.Descripcion,
ComFactura.Id,
ComFactura.ComProveedorId
INTO TablaTemp4
FROM ComFactura
INNER JOIN Tablatemp3 ON Tablatemp3.ComFacturaId = ComFactura.Id
GROUP BY TablaTemp3.IdArt,TablaTemp3.Descripcion,ComFactura.Id,ComFactura.ComProveedorId
ORDER BY ComFactura.Id ASC

SELECT
TablaTemp3.IdArt,
TablaTemp3.Descripcion,
ComFactura.Id,
ComFactura.ComProveedorId
INTO TablaTemp5
FROM ComFactura
INNER JOIN Tablatemp3 ON Tablatemp3.ComFacturaId = ComFactura.Id
WHERE ComFactura.ComProveedorId = 20
GROUP BY TablaTemp3.IdArt,TablaTemp3.Descripcion,ComFactura.Id,ComFactura.ComProveedorId
ORDER BY ComFactura.Id ASC


--SELECT * FROM TablaTemp3
--SELECT * FROM TablaTemp4
--SELECT * FROM TablaTemp5

SELECT
TablaTemp5.IdArt,
TablaTemp5.Descripcion,
TablaTemp5.Id,
TablaTemp5.ComProveedorId
FROM TablaTemp5 INNER JOIN TablaTemp4 ON TablaTemp4.IdArt = TablaTemp5.IdArt
GROUP BY TablaTemp5.IdArt,TablaTemp5.Descripcion,TablaTemp5.Id,TablaTemp5.ComProveedorId
 */
?>