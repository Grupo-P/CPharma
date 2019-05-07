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
	TITULO: QTablaTemp
	PARAMETROS: [$inicio,$fin] inicio y fin del rango a preparar
	FUNCION: Borra el contenido de las tablas temporales si estan en uso
	RETORNO: Tablas temporales vacias
 */
function QTablaTempR($inicio,$fin){
	$sql='';
	for($i = $inicio; $i<$fin; $i++){
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
		GenPersona.Nombre,
		ComProveedor.Id
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
		CONVERT(date,ComFactura.FechaDocumento) As FechaDocumento,
		ComFacturaDetalle.CantidadRecibidaFactura,
		ComFacturaDetalle.M_PrecioCompraBruto
		FROM InvArticulo
		inner join ComFacturaDetalle ON InvArticulo.Id = ComFacturaDetalle.InvArticuloId
		inner join ComFactura ON ComFactura.Id = ComFacturaDetalle.ComFacturaId
		inner join ComProveedor ON ComProveedor.Id = ComFactura.ComProveedorId
		inner join GenPersona ON GenPersona.Id = ComProveedor.GenPersonaId
		WHERE InvArticulo.Id = '$IdArticulo'
		ORDER BY ComFactura.FechaDocumento DESC
	";
	return $sql;
}
/*
	TITULO: QUnidadesVendidasCliente
	PARAMETROS: [$FInicial] Fecha inicial del rango a consultar
				[$FFinal] Fecha final del rango a consutar
	FUNCION: Consulta las Veces vendidas a clientes y las unidades vendidas de un producto
	RETORNO: Tabla con los articulos, las veces vendidas y las unidades vendidas
 */
function QUnidadesVendidasCliente($FInicial,$FFinal){
	$sql = "
		SELECT
		InvArticulo.Id,
		InvArticulo.CodigoArticulo,
		InvArticulo.Descripcion, 
		COUNT(*) AS VecesVendidasCliente, 
		SUM(VenFacturaDetalle.Cantidad) AS UnidadesVendidasCliente
		INTO TablaTemp
		FROM VenFacturaDetalle
		INNER JOIN InvArticulo ON InvArticulo.Id = VenFacturaDetalle.InvArticuloId
		INNER JOIN VenFactura ON VenFactura.Id = VenFacturaDetalle.VenFacturaId
		WHERE
		(VenFactura.FechaDocumento > '$FInicial' AND VenFactura.FechaDocumento < '$FFinal')
		GROUP BY InvArticulo.Id,InvArticulo.CodigoArticulo,InvArticulo.Descripcion
		ORDER BY UnidadesVendidasCliente DESC
	";
	return $sql;
}
/*
	TITULO: QUnidadesDevueltaCliente
	PARAMETROS: [$FInicial] Fecha inicial del rango a consultar
				[$FFinal] Fecha final del rango a consutar
	FUNCION: Consulta las Veces devuelta a clientes y las unidades devuelta de un producto
	RETORNO: Tabla con los articulos, las veces devuelta y las unidades devuelta
 */
function QUnidadesDevueltaCliente($FInicial,$FFinal){
	$sql = "
		SELECT
		InvArticulo.Id,
		InvArticulo.CodigoArticulo,
		InvArticulo.Descripcion,
		COUNT(*) AS VecesDevueltaCliente,
		SUM(VenDevolucionDetalle.Cantidad) AS UnidadesDevueltaCliente
		INTO TablaTemp1
		FROM VenDevolucionDetalle
		INNER JOIN InvArticulo ON InvArticulo.Id = VenDevolucionDetalle.InvArticuloId
		INNER JOIN VenDevolucion ON VenDevolucion.Id = VenDevolucionDetalle.VenDevolucionId
		WHERE
		(VenDevolucion.FechaDocumento > '$FInicial' AND VenDevolucion.FechaDocumento < '$FFinal')
		GROUP BY InvArticulo.Id,InvArticulo.CodigoArticulo,InvArticulo.Descripcion
		ORDER BY UnidadesDevueltaCliente DESC
	";
	return $sql;
}
/*
	TITULO: QUnidadesCompradasProveedor
	PARAMETROS: [$FInicial] Fecha inicial del rango a consultar
				[$FFinal] Fecha final del rango a consutar
	FUNCION: Consulta las Veces Compradas a proveedores y las unidades compradas
	RETORNO: Tabla con los articulos, las veces compradas y las unidades compradas
 */
function QUnidadesCompradasProveedor($FInicial,$FFinal){
	$sql = "
		SELECT
		InvArticulo.Id,
		InvArticulo.CodigoArticulo,
		InvArticulo.Descripcion,
		COUNT(*) AS VecesCompradasProveedor,
		SUM(ComFacturaDetalle.CantidadFacturada) AS UnidadesCompradasProveedor
		INTO TablaTemp2
		FROM ComFacturaDetalle
		INNER JOIN InvArticulo ON InvArticulo.Id = ComFacturaDetalle.InvArticuloId
		INNER JOIN ComFactura ON  ComFactura.Id = ComFacturaDetalle.ComFacturaId
		WHERE
		(ComFactura.FechaRegistro > '$FInicial' AND ComFactura.FechaRegistro < '$FFinal')
		GROUP BY InvArticulo.Id,InvArticulo.CodigoArticulo,InvArticulo.Descripcion
		ORDER BY UnidadesCompradasProveedor DESC
	";
	return $sql;
}
/*
	TITULO: QUnidadesReclamoProveedor
	PARAMETROS: [$FInicial] Fecha inicial del rango a consultar
				[$FFinal] Fecha final del rango a consutar
	FUNCION: Consulta las Veces reclamo a proveedores y las unidades reclamo
	RETORNO: Tabla con los articulos, las veces reclamo y las unidades reclamo
 */
function QUnidadesReclamoProveedor($FInicial,$FFinal){
	$sql = "
		SELECT
		InvArticulo.Id,
		InvArticulo.CodigoArticulo,
		InvArticulo.Descripcion,
		COUNT(*) AS VecesReclamoProveedor,
		SUM(ComReclamoDetalle.Cantidad) AS UnidadesReclamoProveedor
		INTO TablaTemp3
		FROM ComReclamoDetalle
		INNER JOIN InvArticulo ON InvArticulo.Id = ComReclamoDetalle.InvArticuloId
		INNER JOIN ComReclamo ON ComReclamo.Id = ComReclamoDetalle.ComReclamoId
		WHERE
		(ComReclamo.FechaRegistro > '$FInicial' AND ComReclamo.FechaRegistro < '$FFinal')
		GROUP BY InvArticulo.Id,InvArticulo.CodigoArticulo,InvArticulo.Descripcion
		ORDER BY UnidadesReclamoProveedor DESC
	";
	return $sql;
}
/*
	TITULO: QIntegracionProductosVendidos
	PARAMETROS: No aplica
	FUNCION: Integrar la informacion de las consultas
			[QUnidadesVendidasCliente,QUnidadesDevueltaCliente,QUnidadesCompradasProveedor,QUnidadesReclamoProveedor]
	RETORNO: Tabla con datos integrados con valores 0 para campos NULL
 */
function QIntegracionProductosVendidos(){
	$sql = "
		SELECT 
		TablaTemp.Id,
		TablaTemp.CodigoArticulo,
		TablaTemp.Descripcion,
		ISNULL(TablaTemp.VecesVendidasCliente,CAST(0 AS INT)) AS VecesVendidasCliente,
		ISNULL(TablaTemp1.VecesDevueltaCliente,CAST(0 AS INT)) AS VecesDevueltaCliente,
		ISNULL(TablaTemp.UnidadesVendidasCliente,CAST(0 AS INT)) AS UnidadesVendidasCliente,
		ISNULL(TablaTemp1.UnidadesDevueltaCliente,CAST(0 AS INT)) AS UnidadesDevueltaCliente,
		ISNULL(TablaTemp2.VecesCompradasProveedor,CAST(0 AS INT)) AS VecesCompradasProveedor,
		ISNULL(TablaTemp3.VecesReclamoProveedor,CAST(0 AS INT)) AS VecesReclamoProveedor,
		ISNULL(TablaTemp2.UnidadesCompradasProveedor,CAST(0 AS INT)) AS UnidadesCompradasProveedor,
		ISNULL(TablaTemp3.UnidadesReclamoProveedor,CAST(0 AS INT)) AS UnidadesReclamoProveedor
		INTO TablaTemp4
		FROM TablaTemp
		LEFT JOIN TablaTemp1 ON TablaTemp1.Id = TablaTemp.Id
		LEFT JOIN TablaTemp2 ON TablaTemp2.Id = TablaTemp.Id
		LEFT JOIN TablaTemp3 ON TablaTemp3.Id = TablaTemp.Id
		ORDER BY UnidadesVendidasCliente DESC
	";
	return $sql;
}
/*
	TITULO: QProductoMasVendido
	PARAMETROS: [$Top] Varaible para indicar el top a buscar
	FUNCION: Ubicar el top de productos mas vendidos
	RETORNO: Lista de productos mas vendidos
 */
function QProductoMasVendido($Top){
	$sql = "
		SELECT TOP $Top
		TablaTemp4.Id,
		TablaTemp4.CodigoArticulo,
		TablaTemp4.Descripcion,
		TablaTemp4.VecesVendidasCliente,
		TablaTemp4.VecesDevueltaCliente,
		ISNULL((VecesVendidasCliente-VecesDevueltaCliente),0) AS TotalVecesVendidasCliente,
		TablaTemp4.UnidadesVendidasCliente,
		TablaTemp4.UnidadesDevueltaCliente,
		ISNULL((UnidadesVendidasCliente-UnidadesDevueltaCliente),0) AS TotalUnidadesVendidasCliente,
		TablaTemp4.VecesCompradasProveedor,
		TablaTemp4.VecesReclamoProveedor,
		ISNULL((VecesCompradasProveedor-VecesReclamoProveedor),0) AS TotalVecesCompradasProveedor,
		TablaTemp4.UnidadesCompradasProveedor,
		TablaTemp4.UnidadesReclamoProveedor,
		ISNULL((UnidadesCompradasProveedor-UnidadesReclamoProveedor),0) AS TotalUnidadesCompradasProveedor		
		FROM TablaTemp4
		ORDER BY TotalUnidadesVendidasCliente DESC
	";
	return $sql;
}
/*
	TITULO: QProductoMenosVendido
	PARAMETROS: [$Top] Varaible para indicar el top a buscar
	FUNCION: Ubicar el top de productos menos vendidos
	RETORNO: Lista de productos menos vendidos
 */
function QProductoMenosVendido($Top){
	$sql = "
		SELECT TOP $Top
		TablaTemp4.Id,
		TablaTemp4.CodigoArticulo,
		TablaTemp4.Descripcion,
		TablaTemp4.VecesVendidasCliente,
		TablaTemp4.VecesDevueltaCliente,
		ISNULL((VecesVendidasCliente-VecesDevueltaCliente),0) AS TotalVecesVendidasCliente,
		TablaTemp4.UnidadesVendidasCliente,
		TablaTemp4.UnidadesDevueltaCliente,
		ISNULL((UnidadesVendidasCliente-UnidadesDevueltaCliente),0) AS TotalUnidadesVendidasCliente,
		TablaTemp4.VecesCompradasProveedor,
		TablaTemp4.VecesReclamoProveedor,
		ISNULL((VecesCompradasProveedor-VecesReclamoProveedor),0) AS TotalVecesCompradasProveedor,
		TablaTemp4.UnidadesCompradasProveedor,
		TablaTemp4.UnidadesReclamoProveedor,
		ISNULL((UnidadesCompradasProveedor-UnidadesReclamoProveedor),0) AS TotalUnidadesCompradasProveedor		
		FROM TablaTemp4
		ORDER BY TotalUnidadesVendidasCliente ASC
	";
	return $sql;
}
/*
	TITULO: QIntegracionProductosFalla
	PARAMETROS: No aplica
	FUNCION: Integrar la informacion de las consultas
			[QUnidadesVendidasCliente,QUnidadesDevueltaCliente,QUnidadesCompradasProveedor,QUnidadesReclamoProveedor]
	RETORNO: Tabla con datos integrados con valores 0 para campos NULL
 */
function QIntegracionProductosFalla(){
	$sql="
		SELECT
		TablaTemp4.Id,
		TablaTemp4.CodigoArticulo,
		TablaTemp4.Descripcion,
		TablaTemp4.VecesVendidasCliente,
		TablaTemp4.VecesDevueltaCliente,
		ISNULL((VecesVendidasCliente-VecesDevueltaCliente),0) AS TotalVecesVendidasCliente,
		TablaTemp4.UnidadesVendidasCliente,
		TablaTemp4.UnidadesDevueltaCliente,
		ISNULL((UnidadesVendidasCliente-UnidadesDevueltaCliente),0) AS TotalUnidadesVendidasCliente,
		TablaTemp4.VecesCompradasProveedor,
		TablaTemp4.VecesReclamoProveedor,
		ISNULL((VecesCompradasProveedor-VecesReclamoProveedor),0) AS TotalVecesCompradasProveedor,
		TablaTemp4.UnidadesCompradasProveedor,
		TablaTemp4.UnidadesReclamoProveedor,
		ISNULL((UnidadesCompradasProveedor-UnidadesReclamoProveedor),0) AS TotalUnidadesCompradasProveedor,
		SUM(InvLoteAlmacen.Existencia) AS Existencia
		INTO TablaTemp5
		FROM TablaTemp4
		INNER JOIN InvLoteAlmacen ON InvLoteAlmacen.InvArticuloId = TablaTemp4.Id
		WHERE (InvLoteAlmacen.InvAlmacenId = 1 or InvLoteAlmacen.InvAlmacenId = 2)
		GROUP BY 
		TablaTemp4.Id,
		TablaTemp4.CodigoArticulo, 
		TablaTemp4.Descripcion,
		TablaTemp4.VecesVendidasCliente,
		TablaTemp4.VecesDevueltaCliente,
		TablaTemp4.UnidadesVendidasCliente,
		TablaTemp4.UnidadesDevueltaCliente,
		TablaTemp4.VecesCompradasProveedor,
		TablaTemp4.VecesReclamoProveedor,
		TablaTemp4.UnidadesCompradasProveedor,
		TablaTemp4.UnidadesReclamoProveedor
		ORDER BY TablaTemp4.UnidadesVendidasCliente DESC
	";
	return $sql;
}
/*
	TITULO: QProductosFalla
	PARAMETROS: Funciona en conjunto con QIntegracionProductosFalla
	FUNCION: Arma una lista de productos vendido con existencia en 0
	RETORNO: Lista de productos mas vendidos con existencia en 0
 */
function QProductosFalla(){
	$sql = "
		SELECT * FROM TablaTemp5 
		WHERE TablaTemp5.Existencia = 0 
		ORDER BY TablaTemp5.TotalUnidadesVendidasCliente DESC
	";
	return $sql;
}
/*
	TITULO: QArticuloDescLike
	PARAMETROS: [$DescripLike] Descripcion para buscar
				[$TablaTemp] Variable para indicar si la busqueda sera almacenada en una tabla temporal
	FUNCION: Buscar un articulo segun su descripcion
	RETORNO: Caracteristicas del articulo
 */
function QArticuloDescLike($DescripLike,$TablaTemp){
	switch ($TablaTemp) {
		case '0':
		$sql = "
			SELECT 
			InvArticulo.Id,
			InvArticulo.CodigoArticulo,
			InvArticulo.Descripcion,
			InvArticulo.FinConceptoImptoIdCompra AS ConceptoImpuesto
			INTO TablaTemp6
			FROM InvArticulo
			WHERE InvArticulo.Descripcion LIKE '%$DescripLike%'
			order by InvArticulo.Descripcion ASC
		";
		return $sql;
		break;
		
		case '1':
		$sql = "
			SELECT 
			InvArticulo.Id,
			InvArticulo.CodigoArticulo,
			InvArticulo.Descripcion,
			InvArticulo.FinConceptoImptoIdCompra AS ConceptoImpuesto
			FROM InvArticulo
			WHERE InvArticulo.Descripcion LIKE '%$DescripLike%'
			order by InvArticulo.Descripcion ASC
		";
		return $sql;
		break;
	}	
}
/*
	TITULO: QUltimoLote
	PARAMETROS: [$IdArticulo] Id del articulo a buscar
	FUNCION: Buscar la fecha del ultimo lote del articulo en cuestion
	RETORNO: La fecha del ultimo lote
 */
function QUltimoLote($IdArticulo){
	$sql="
		SELECT TOP 1
		InvLote.Id,
		invlote.M_PrecioCompraBruto,
		invlote.M_PrecioTroquelado,
		CONVERT(DATE,invlote.FechaEntrada) AS UltimoLote
		FROM InvLote
		WHERE InvLote.InvArticuloId  = '$IdArticulo'
		ORDER BY UltimoLote DESC
	";
	return $sql;
}
/*
	TITULO: QUltimaVenta
	PARAMETROS: [$IdArticulo] Id del articulo a buscar
	FUNCION: Buscar la fecha de la ultima venta del articulo dentro del rango 
	RETORNO: La fecha de la ultima venta
 */
function QUltimaVenta($IdArticulo,$FInicial,$FFinal){
	$sql="
		SELECT TOP 1
		CONVERT(DATE,VenFactura.FechaDocumento) AS UltimaVenta
		FROM VenFactura
		INNER JOIN VenFacturaDetalle ON VenFacturaDetalle.VenFacturaId = VenFactura.Id
		WHERE
		(VenFactura.FechaDocumento > '$FInicial' AND VenFactura.FechaDocumento < '$FFinal') 
		AND (VenFacturaDetalle.InvArticuloId = '$IdArticulo')
		ORDER BY FechaDocumento DESC
	";
	return $sql;
}
/*
	TITULO: QPedidoProductos
	PARAMETROS: 
	FUNCION: Arma la lista de pedido de productos
	RETORNO: Lista prodcutos para pedir
 */
function QPedidoProductos(){
	$sql="
		SELECT 
		TablaTemp6.Id,
		TablaTemp6.CodigoArticulo,
		TablaTemp6.Descripcion,
		TablaTemp6.ConceptoImpuesto,
		ISNULL((TablaTemp5.TotalVecesVendidasCliente),0) AS TotalVecesVendidasCliente,
		ISNULL((TablaTemp5.TotalUnidadesVendidasCliente),0) AS TotalUnidadesVendidasCliente,
		ISNULL((TablaTemp5.TotalVecesCompradasProveedor),0) AS TotalVecesCompradasProveedor,
		ISNULL((TablaTemp5.TotalUnidadesCompradasProveedor),0) AS TotalUnidadesCompradasProveedor
		FROM TablaTemp6
		LEFT JOIN TablaTemp5 ON TablaTemp5.Id = TablaTemp6.Id
		ORDER BY TablaTemp6.Descripcion ASC
	";
	return $sql;
}
/*
	TITULO: QfacturaProveedor
	PARAMETROS: [$IdProveedor] Id del proveedor a buscar
	FUNCION: Buscar la lista de facturas donde interviene el proveedor
	RETORNO: lista de facturas
 */
function QfacturaProveedor($IdProveedor){
	$sql = "
	SELECT
	ComFactura.Id AS FacturaId
	INTO TablaTemp
	FROM ComFactura
	WHERE ComFactura.ComProveedorId = '$IdProveedor'
	ORDER BY FacturaId ASC
	";
	return $sql;
}
/*
	TITULO: QDetalleFactura
	PARAMETROS: No aplica [Funciona en conjunto con QfacturaProveedor]
	FUNCION: Busca el detalle de las facturas previas
	RETORNO: Lista de detalle con los articulos que intervienen en la factura
 */
function QDetalleFactura(){
	$sql = '
	SELECT 
	ComFacturaDetalle.ComFacturaId AS FacturaId,
	ComFacturaDetalle.InvArticuloId AS ArtiuloId
	INTO TablaTemp1
	FROM ComFacturaDetalle
	INNER JOIN TablaTemp ON TablaTemp.FacturaId = ComFacturaDetalle.ComFacturaId
	ORDER BY FacturaId,ArtiuloId ASC
	';
	return $sql;
}
/*
	TITULO: QCatalogoProductosProveedor
	PARAMETROS: No aplica [Funciona en conjunto con QCatalogoProductosProveedor]
	FUNCION: Armar a tabla con la lista del catalogo del proveedor
	RETORNO: Catalogo de productos del proveedor
 */
function QCatalogoProductosProveedor(){
	$sql = '
	SELECT
	InvArticulo.Id,
	InvArticulo.CodigoArticulo,
	InvArticulo.Descripcion,
	InvArticulo.FinConceptoImptoIdCompra AS ConceptoImpuesto 
	FROM InvArticulo
	INNER JOIN TablaTemp1 ON TablaTemp1.ArtiuloId = InvArticulo.Id
	GROUP BY InvArticulo.Id,InvArticulo.Descripcion,InvArticulo.CodigoArticulo,InvArticulo.FinConceptoImptoIdCompra
	ORDER BY InvArticulo.Descripcion ASC
	';
	return $sql;
}
/*
	TITULO: QFacturasProducto
	PARAMETROS: [$IdArticulo] Id del articulo a buscar las facturas
	FUNCION: Buscar las facturas donde interviene el articulo
	RETORNO: Lista de las facturas donde aparece el articulo
 */
function QFacturasProducto($IdArticulo){
	$sql = "
	SELECT
	ComFacturaDetalle.ComFacturaId AS FacturaId
	INTO TablaTemp2
	FROM ComFacturaDetalle
	WHERE ComFacturaDetalle.InvArticuloId = '$IdArticulo'
	";
	return $sql;
}
/*
	TITULO: QProvedorUnico
	PARAMETROS: [$IdProveedor] Id del proveedor a buscar
	FUNCION: Buscar en las facturas si hay otro proveedor que surta el producto
	RETORNO: Lista de proveedores que surten el producto 
	[Funciona en conjunto con QFacturasProducto]
 */
function QProvedorUnico($IdProveedor){
	$sql = "
	SELECT
	ComFactura.ComProveedorId
	INTO TablaTemp3
	FROM TablaTemp2 
	INNER JOIN ComFactura ON ComFactura.Id = TablaTemp2.FacturaId
	WHERE ComFactura.ComProveedorId <> '$IdProveedor'
	GROUP BY ComFactura.ComProveedorId
	";
	return $sql;
}
/*
	TITULO: QProvedorUnicoLista
	PARAMETROS: No aplica [Funciona en conunto con QProvedorUnico]
	FUNCION: Armar la lista de los proveedores que dispensan el articulo
	RETORNO: Lista de proveedores que dispensan el articulo
 */
function QProvedorUnicoLista(){
	$sql = "
	SELECT
	ComProveedor.Id,
	GenPersona.Nombre
	FROM ComProveedor
	INNER JOIN GenPersona ON ComProveedor.GenPersonaId=GenPersona.Id
	INNER JOIN ComFactura ON ComFactura.ComProveedorId=ComProveedor.Id
	INNER JOIN TablaTemp3 ON TablaTemp3.ComProveedorId=ComProveedor.Id
	GROUP by ComProveedor.Id, GenPersona.Nombre
	ORDER BY ComProveedor.Id ASC
	";
	return $sql;
}
/*
	TITULO: QUnidadesVendidasClienteId
	PARAMETROS: [$FInicial,$FFinal,$Id] Fecha inicial y final del rango y id del articulo
	FUNCION: bucar las unidades vendidas en el rango del articulo determindo
	RETORNO: unidades vendidas del articulo
 */
function QUnidadesVendidasClienteId($FInicial,$FFinal,$Id){
	$sql = "
		SELECT
		InvArticulo.Id,
		InvArticulo.CodigoArticulo,
		InvArticulo.Descripcion, 
		COUNT(*) AS VecesVendidasCliente, 
		SUM(VenFacturaDetalle.Cantidad) AS UnidadesVendidasCliente
		INTO TablaTemp
		FROM VenFacturaDetalle
		INNER JOIN InvArticulo ON InvArticulo.Id = VenFacturaDetalle.InvArticuloId
		INNER JOIN VenFactura ON VenFactura.Id = VenFacturaDetalle.VenFacturaId
		WHERE
		(VenFactura.FechaDocumento > '$FInicial' AND VenFactura.FechaDocumento < '$FFinal')
		AND (InvArticulo.Id = '$Id')
		GROUP BY InvArticulo.Id,InvArticulo.CodigoArticulo,InvArticulo.Descripcion
		ORDER BY InvArticulo.Id DESC
	";
	return $sql;
}
/*
	TITULO: QUnidadesDevueltaClienteId
	PARAMETROS: [$FInicial,$FFinal,$Id] Fecha inicial y final del rango y id del articulo
	FUNCION: buscar las unidades devueltas por los clientes
	RETORNO: unidades devueltas
 */
function QUnidadesDevueltaClienteId($FInicial,$FFinal,$Id){
	$sql = "
		SELECT
		InvArticulo.Id,
		InvArticulo.CodigoArticulo,
		InvArticulo.Descripcion,
		COUNT(*) AS VecesDevueltaCliente,
		SUM(VenDevolucionDetalle.Cantidad) AS UnidadesDevueltaCliente
		INTO TablaTemp1
		FROM VenDevolucionDetalle
		INNER JOIN InvArticulo ON InvArticulo.Id = VenDevolucionDetalle.InvArticuloId
		INNER JOIN VenDevolucion ON VenDevolucion.Id = VenDevolucionDetalle.VenDevolucionId
		WHERE
		(VenDevolucion.FechaDocumento > '$FInicial' AND VenDevolucion.FechaDocumento < '$FFinal')
		AND (InvArticulo.Id = '$Id')
		GROUP BY InvArticulo.Id,InvArticulo.CodigoArticulo,InvArticulo.Descripcion
		ORDER BY UnidadesDevueltaCliente DESC
	";
	return $sql;
}
/*
	TITULO: QUnidadesCompradasProveedorId
	PARAMETROS: [$FInicial,$FFinal,$Id] Fecha inicial y final del rango y id del articulo
	FUNCION: buscar las unidades compradas a los proveedores
	RETORNO: unidades compradas
 */
function QUnidadesCompradasProveedorId($FInicial,$FFinal,$Id){
	$sql = "
		SELECT
		InvArticulo.Id,
		InvArticulo.CodigoArticulo,
		InvArticulo.Descripcion,
		COUNT(*) AS VecesCompradasProveedor,
		SUM(ComFacturaDetalle.CantidadFacturada) AS UnidadesCompradasProveedor
		INTO TablaTemp2
		FROM ComFacturaDetalle
		INNER JOIN InvArticulo ON InvArticulo.Id = ComFacturaDetalle.InvArticuloId
		INNER JOIN ComFactura ON  ComFactura.Id = ComFacturaDetalle.ComFacturaId
		WHERE
		(ComFactura.FechaRegistro > '$FInicial' AND ComFactura.FechaRegistro < '$FFinal')
		AND (InvArticulo.Id = '$Id')
		GROUP BY InvArticulo.Id,InvArticulo.CodigoArticulo,InvArticulo.Descripcion
		ORDER BY UnidadesCompradasProveedor DESC
	";
	return $sql;
}
/*
	TITULO: QUnidadesReclamoProveedorId
	PARAMETROS: [$FInicial,$FFinal,$Id] Fecha inicial y final del rango y id del articulo
	FUNCION: buscar las unidades reclamadas a proveedores
	RETORNO: unidades reclamadas
 */
function QUnidadesReclamoProveedorId($FInicial,$FFinal,$Id){
	$sql = "
		SELECT
		InvArticulo.Id,
		InvArticulo.CodigoArticulo,
		InvArticulo.Descripcion,
		COUNT(*) AS VecesReclamoProveedor,
		SUM(ComReclamoDetalle.Cantidad) AS UnidadesReclamoProveedor
		INTO TablaTemp3
		FROM ComReclamoDetalle
		INNER JOIN InvArticulo ON InvArticulo.Id = ComReclamoDetalle.InvArticuloId
		INNER JOIN ComReclamo ON ComReclamo.Id = ComReclamoDetalle.ComReclamoId
		WHERE
		(ComReclamo.FechaRegistro > '$FInicial' AND ComReclamo.FechaRegistro < '$FFinal')
		AND (InvArticulo.Id = '$Id')
		GROUP BY InvArticulo.Id,InvArticulo.CodigoArticulo,InvArticulo.Descripcion
		ORDER BY UnidadesReclamoProveedor DESC
	";
	return $sql;
}
/*
	TITULO: QfacturaProveedorToquel
	PARAMETROS: [$IdProveedor] Id del proveedor a buscar
	FUNCION: Buscar la lista de facturas donde interviene el proveedor
	RETORNO: lista de facturas
 */
function QfacturaProveedorToquel($IdProveedor){
	$sql = "
	SELECT
	ComFactura.Id AS FacturaId,
	CONVERT(DATE,ComFactura.FechaDocumento) AS FechaDocumento,
	ComFactura.Auditoria_Usuario
	FROM ComFactura
	WHERE ComFactura.ComProveedorId = '$IdProveedor'
	ORDER BY FacturaId ASC
	";
	return $sql;
}
/*
	TITULO: 
	PARAMETROS: 
	FUNCION: 
	RETORNO: 
 */
function QFacturaArticulo($IdFatura){
	$sql = "
	SELECT 
	ComFacturaDetalle.ComFacturaId AS FacturaId,
	InvArticulo.Id,
	InvArticulo.CodigoArticulo,
	InvArticulo.Descripcion,
	InvArticulo.FinConceptoImptoIdCompra AS ConceptoImpuesto
	FROM ComFacturaDetalle
	INNER JOIN InvArticulo ON InvArticulo.Id = ComFacturaDetalle.InvArticuloId
	WHERE ComFacturaDetalle.ComFacturaId = '$IdFatura'
	ORDER BY FacturaId,InvArticulo.Id ASC
	";
	return $sql;
}
/*
	TITULO: 
	PARAMETROS: 
	FUNCION:
	RETORNO:
 */
function QTroquelArticuloFactura($IdFatura,$IdArticulo){
	$sql = "
	SELECT
	ComFacturaDetalle.ComFacturaId,
	ComFacturaDetalle.InvArticuloId,
	ISNULL(ComFacturaDetalle.M_PrecioTroquelado,CAST(0 AS INT)) AS M_PrecioTroquelado
	FROM ComFacturaDetalle
	WHERE ComFacturaDetalle.ComFacturaId = '$IdFatura' and ComFacturaDetalle.InvArticuloId = '$IdArticulo'
	";
	return $sql;
}
/*
	TITULO: 
	PARAMETROS: 
	FUNCION:
	RETORNO:
 */
function QActualizarTroquel($IdFatura,$IdArticulo,$PrecioTroquel){
	$sql = "
	UPDATE ComFacturaDetalle 
	SET M_PrecioTroquelado = '$PrecioTroquel'
	FROM ComFacturaDetalle 
	WHERE ComFacturaDetalle.ComFacturaId = '$IdFatura' 
	AND ComFacturaDetalle.InvArticuloId = '$IdArticulo'
	";
	return $sql;
}


/*
	TITULO: 
	PARAMETROS: 
	FUNCION:
	RETORNO:
 */
function QModelo(){
	$sql = "
	";
	return $sql;
}

/*
SELECT
GenPersona.Nombre,
ComProveedor.Id
FROM ComProveedor
INNER JOIN GenPersona ON ComProveedor.GenPersonaId=GenPersona.Id
INNER JOIN ComFactura ON ComFactura.ComProveedorId=ComProveedor.Id
WHERE ComProveedor.Id = 10
GROUP by ComProveedor.Id, GenPersona.Nombre
ORDER BY ComProveedor.Id ASC

SELECT
ComFactura.Id AS FacturaId
FROM ComFactura
WHERE ComFactura.ComProveedorId = 10
ORDER BY FacturaId ASC

SELECT 
ComFacturaDetalle.ComFacturaId AS FacturaId,
InvArticulo.Id,
InvArticulo.CodigoArticulo,
InvArticulo.Descripcion,
InvArticulo.FinConceptoImptoIdCompra AS ConceptoImpuesto
FROM ComFacturaDetalle
INNER JOIN InvArticulo ON InvArticulo.Id = ComFacturaDetalle.InvArticuloId
WHERE ComFacturaDetalle.ComFacturaId = 29950
ORDER BY FacturaId,InvArticulo.Id ASC

SELECT
ComFacturaDetalle.ComFacturaId,
ComFacturaDetalle.InvArticuloId,
ComFacturaDetalle.M_PrecioTroquelado 
FROM ComFacturaDetalle
WHERE ComFacturaDetalle.ComFacturaId = 29950 and ComFacturaDetalle.InvArticuloId = 741

UPDATE ComFacturaDetalle 
SET M_PrecioTroquelado = 0
FROM ComFacturaDetalle 
WHERE ComFacturaDetalle.ComFacturaId = 29950 and ComFacturaDetalle.InvArticuloId = 741

SELECT
ComFacturaDetalle.ComFacturaId,
ComFacturaDetalle.InvArticuloId,
ComFacturaDetalle.M_PrecioTroquelado 
FROM ComFacturaDetalle
WHERE ComFacturaDetalle.ComFacturaId = 29950 and ComFacturaDetalle.InvArticuloId = 741
 */
?>