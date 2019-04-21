<?php
/*
	TITULO: QTablaTemp
	PARAMETROS: [$Cant] Cantidad de tablas temporales a preparar
	FUNCION: Borra el contenido de las tablas temporales si estan en uso
	RETORNO: Tablas temporales vacias
 */
function QTablaTemp($Cant){
	for($i = 0; $i<$Cant; $i++){
		if($i == 0){
			$sql = "
				IF OBJECT_ID ('TablaTemp', 'U') IS NOT NULL
					DROP TABLE TablaTemp;
			";
		}
		else {
			$flag = "
				IF OBJECT_ID ('TablaTemp".$i."', 'U') IS NOT NULL
					DROP TABLE TablaTemp".$i.";
			";
			$sql = $sql.$flag;
		}
	}
	return $sql;
}
/*
	TITULO: QTasa
	PARAMETROS: [$QFecha] Fecha de la que se quiere la tasa
	FUNCION: Buscar el valor de la tasa en un dia especifico
	RETORNO: Valor de la tasa al dia que se solicito
 */
function QTasa($Fecha){
	$conCP = ConectarXampp();
	$consulta = "SELECT tasa FROM dolars where fecha='$Fecha'";
	$resultado = mysqli_query($conCP,$consulta);
	return $resultado;
}
/*
	TITULO: QListaProveedores
	PARAMETROS: No aplica
	FUNCION: Armar una lista de proveedores
	RETORNO: Lista de proveedores
 */
function QListaProveedores(){
	$sql = "
		SELECT
		ComProveedor.Id,
		GenPersona.Nombre
		FROM ComProveedor
		INNER JOIN GenPersona ON ComProveedor.GenPersonaId=GenPersona.Id
		INNER JOIN ComFactura ON ComFactura.ComProveedorId=ComProveedor.Id
		GROUP by ComProveedor.Id, GenPersona.Nombre
		ORDER BY ComProveedor.Id ASC
	";
	return $sql;
}
/*
	TITULO: QFRegProveedor
	PARAMETROS: No aplica
	FUNCION: Armar una lista de provedores con su ultima fecha de registro
	RETORNO: Lista de proveedores con su ultima fecha de registro
 */
function QFRegProveedor(){
	$sql = "
		SELECT
		ComProveedor.Id,
		GenPersona.Nombre,
			(SELECT TOP 1
			CONVERT(DATE,ComFactura.FechaRegistro) 
			FROM ComFactura
			WHERE ComFactura.ComProveedorId= ComProveedor.Id
			ORDER BY FechaRegistro DESC) AS FechaRegistro
		INTO TablaTemp 
		FROM ComProveedor
		INNER JOIN GenPersona ON ComProveedor.GenPersonaId=GenPersona.Id
		INNER JOIN ComFactura ON ComFactura.ComProveedorId=ComProveedor.Id
		GROUP by ComProveedor.Id, GenPersona.Nombre
		ORDER BY ComProveedor.Id ASC
	";
	return $sql;
}
/*
	TITULO: QFRegRangoProveedor
	PARAMETROS: Funciona en conjunto con QFRegProveedor(Ejecutar primero QFRegProveedor)
	FUNCION: Toma el resultado de QFRegProveedor y le anexa el campo de rango de dia, 
	RETORNO: Regresa una lista de proveedores con la ultima fecha de registro y la diferencia en dias
 */
function QFRegRangoProveedor(){
	$sql = "
		SELECT
		TablaTemp.Id,
		TablaTemp.Nombre,
		TablaTemp.FechaRegistro,
		DATEDIFF(DAY,CONVERT(DATE,TablaTemp.FechaRegistro),GETDATE()) As RangoDias
		FROM TablaTemp
		ORDER BY RangoDias ASC
	";
	return $sql;
}
/*
	TITULO: QDescIdArticulos
	PARAMETROS: No aplica
	FUNCION: Armar una lista de articulos
	RETORNO: Lista de articulos
 */
function QListaArticulos(){
	$sql = "
		SELECT
		InvArticulo.Descripcion, 
		InvArticulo.Id 
		FROM InvArticulo
		ORDER BY InvArticulo.Descripcion ASC
	";
	return $sql;
}
/*
	TITULO: QArticulo
	PARAMETROS: [$IdArticulo] Id del articulo que se va a buscar
	FUNCION: Buscar informacion del articulo
	RETORNO: Datos del articulo
 */
function QArticulo($IdArticulo){
	$sql = "
		SELECT
		InvArticulo.Id, 
		InvArticulo.CodigoArticulo,
		InvArticulo.Descripcion,
		InvArticulo.FinConceptoImptoIdCompra AS ConceptoImpuesto
		FROM InvArticulo
		WHERE InvArticulo.Id = '$IdArticulo'
	";
	return $sql;
}
/*
	TITULO: QExistenciaArticulo
	PARAMETROS: [$IdArticulo] Id del articulo que se va a buscar
				[$CantAlmacen] determina el los almacenes donde se buscara el lote
				0: para los almacenes [1,2]
				1: para todos los almacenes
	FUNCION: Buscar la existencia de del articulos
	RETORNO: Existencia
 */
function QExistenciaArticulo($IdArticulo,$CantAlmacen){
	switch ($CantAlmacen) {
		case '0':
			$sql = "
			SELECT
			SUM (InvLoteAlmacen.Existencia) As Existencia
			FROM InvLoteAlmacen
			WHERE (InvLoteAlmacen.InvAlmacenId = 1 OR InvLoteAlmacen.InvAlmacenId = 2) 
			AND (InvLoteAlmacen.InvArticuloId = '$IdArticulo')
		";
		return $sql;
		break;
		
		case '1':
		$sql = "
			SELECT
			SUM (InvLoteAlmacen.Existencia) As Existencia
			FROM InvLoteAlmacen
			WHERE (InvLoteAlmacen.InvArticuloId = '$IdArticulo')
		";
		return $sql;
		break;
	}	
}
/*
	TITULO: QLoteArticulo
	PARAMETROS: [$IdArticulo] Id del articulo
				[$CantAlmacen] determina el los almacenes donde se buscara el lote
				0: para los almacenes [1,2]
				1: para todos los almacenes
	FUNCION: Busca el lote correspondiente al articulo especificado
	RETORNO: Regresa el Id del lote correspondiente
 */
function QLoteArticulo($IdArticulo,$CantAlmacen){
	switch ($CantAlmacen) {
		case '0':
		$sql = "
			SELECT
			InvLoteAlmacen.InvLoteId
			INTO TablaTemp
			FROM InvLoteAlmacen 
			WHERE (InvLoteAlmacen.InvAlmacenId = 1 OR InvLoteAlmacen.InvAlmacenId = 2) 
			AND (InvLoteAlmacen.InvArticuloId = '$IdArticulo')
			AND (InvLoteAlmacen.Existencia>0)
		";
		return $sql;
		break;
		
		case '1':
		$sql = "
			SELECT
			InvLoteAlmacen.InvLoteId
			INTO TablaTemp
			FROM InvLoteAlmacen 
			WHERE(InvLoteAlmacen.InvArticuloId = '$IdArticulo')
			AND (InvLoteAlmacen.Existencia>0)
		";
		return $sql;
		break;
	}	
}
/*
	TITULO: QLote
	PARAMETROS: 
	FUNCION: Busca el precio de compra bruto y el precio troquelado
			 (Funciona en conjunto con QLoteArticulo)
	RETORNO: Retorna el precio de compra bruto y el precio troquelado
 */
function QLote(){
	$sql = "
		SELECT TOP 1
		InvLote.Id,
		invlote.M_PrecioCompraBruto,
		invlote.M_PrecioTroquelado
		FROM InvLote
		INNER JOIN TablaTemp ON TablaTemp.InvLoteId = InvLote.Id
		ORDER BY invlote.M_PrecioTroquelado, invlote.M_PrecioCompraBruto DESC
	";
	return $sql;
}
/*
	TITULO: QHistoricoArticulo
	PARAMETROS: [$IdArticuloQ] Id del articulo
	FUNCION: Armar la tabla del historico de articulos
	RETORNO: La tabla de historico del articulo
 */
function QHistoricoArticulo($IdArticulo){
	$sql = "
		SELECT
		GenPersona.Nombre,
		CONVERT(date,ComFactura.FechaRegistro) As FechaRegistro,
		ComFacturaDetalle.CantidadRecibidaFactura,
		ComFacturaDetalle.M_PrecioCompraBruto
		FROM InvArticulo
		inner join ComFacturaDetalle ON InvArticulo.Id = ComFacturaDetalle.InvArticuloId
		inner join ComFactura ON ComFactura.Id = ComFacturaDetalle.ComFacturaId
		inner join ComProveedor ON ComProveedor.Id = ComFactura.ComProveedorId
		inner join GenPersona ON GenPersona.Id = ComProveedor.GenPersonaId
		WHERE InvArticulo.Id = '$IdArticulo'
		ORDER BY ComFactura.FechaRegistro DESC
	";
	return $sql;
}


/*
	TITULO: 
	PARAMETROS: 
	FUNCION:
	RETORNO:
 */

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


//QUEDE AQUI
/*
IF OBJECT_ID ('TablaTemp', 'U') IS NOT NULL
	DROP TABLE TablaTemp;
IF OBJECT_ID ('TablaTemp1', 'U') IS NOT NULL
	DROP TABLE TablaTemp1;

SELECT
InvArticulo.Id, 
InvArticulo.CodigoArticulo,
InvArticulo.Descripcion,
InvArticulo.FinConceptoImptoIdCompra AS ConceptoImpuesto
FROM InvArticulo
WHERE InvArticulo.CodigoArticulo = '5185'

SELECT
InvLoteAlmacen.InvLoteId
INTO TablaTemp
FROM InvLoteAlmacen
WHERE (InvLoteAlmacen.InvAlmacenId = 1 OR InvLoteAlmacen.InvAlmacenId = 2) 
AND (InvLoteAlmacen.InvArticuloId = '13199')
AND (InvLoteAlmacen.Existencia>0)

SELECT
InvLoteAlmacen.InvLoteId
INTO TablaTemp1
FROM InvLoteAlmacen
WHERE (InvLoteAlmacen.InvArticuloId = '13199')
AND (InvLoteAlmacen.Existencia>0)

select * from TablaTemp
select * from TablaTemp1

SELECT
InvLote.Id,
invlote.M_PrecioCompraBruto,
invlote.M_PrecioTroquelado
FROM InvLote
INNER JOIN TablaTemp ON TablaTemp.InvLoteId = InvLote.Id
ORDER BY invlote.M_PrecioTroquelado, invlote.M_PrecioCompraBruto DESC

SELECT
InvLote.Id,
invlote.M_PrecioCompraBruto,
invlote.M_PrecioTroquelado
FROM InvLote
INNER JOIN TablaTemp1 ON TablaTemp1.InvLoteId = InvLote.Id
ORDER BY invlote.M_PrecioTroquelado, invlote.M_PrecioCompraBruto DESC
 */
?>