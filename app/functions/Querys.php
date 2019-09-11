<?php
	/*
		Tipadado para tablas: CP_NombreQuery
	 */
	/*
		TITULO: QLastRestoreDB
		PARAMETROS: [$nameDataBase] Nombre de la base de datos a buscar
		FUNCION: Busca la fecha de la ultima restauracion de la base de datos
		RETORNO: Fecha de ultima restauracion
	 */
	function QLastRestoreDB($nameDataBase){
		$sql = "
			SELECT 
			CONVERT (smalldatetime,sys.sysdatabases.crdate) As FechaRestauracion
			FROM sys.sysdatabases
			WHERE sys.sysdatabases.name LIKE '%$nameDataBase%'
		";
		return $sql;
	}
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
	/*SELECT tasa FROM tasa_ventas where fecha='2019-06-25' AND moneda='USD$'
		TITULO: QTasaConversion
		PARAMETROS: [$QFecha] Fecha de la que se quiere la tasa
		FUNCION: Buscar el valor de la tasa en un dia especifico
		RETORNO: Valor de la tasa al dia que se solicito
	 */
	function QTasaConversion($Fecha,$Moneda) {
		$conCP = ConectarXampp();
		$consulta = "SELECT tasa FROM tasa_ventas where fecha='$Fecha' 
						AND moneda='$Moneda'";
		$resultado = mysqli_query($conCP,$consulta);
		return $resultado;
	}
	/*
		TITULO: QTasa
		PARAMETROS: [$QFecha] Fecha de la que se quiere la tasa
		FUNCION: Buscar el valor de la tasa en un dia especifico
		RETORNO: Valor de la tasa al dia que se solicito
	 */
	function QTasa($Fecha) {
		$conCP = ConectarXampp();
		$consulta = "SELECT tasa FROM dolars where fecha='$Fecha'";
		$resultado = mysqli_query($conCP,$consulta);
		//Cerrar conexion con Cpharma
		mysqli_close($conCP);
		return $resultado;
	}
	/*
		TITULO: QListaProveedores
		PARAMETROS: No aplica
		FUNCION: Armar una lista de proveedores
		RETORNO: Lista de proveedores
	 */
	function QListaProveedores() {
		$sql = "
			SELECT
			GenPersona.Nombre,
			ComProveedor.Id
			FROM ComProveedor
			INNER JOIN GenPersona ON ComProveedor.GenPersonaId=GenPersona.Id
			INNER JOIN ComFactura ON ComFactura.ComProveedorId=ComProveedor.Id
			GROUP BY ComProveedor.Id, GenPersona.Nombre
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
	function QFRegProveedor() {
		$sql = "
			SELECT
			ComProveedor.Id,
			GenPersona.Nombre,
				(SELECT TOP 1
				CONVERT(DATE,ComFactura.FechaRegistro)
				FROM ComFactura
				WHERE ComFactura.ComProveedorId= ComProveedor.Id
				ORDER BY FechaRegistro DESC) AS FechaRegistro
			INTO CP_QFRegProveedor
			FROM ComProveedor
			INNER JOIN GenPersona ON ComProveedor.GenPersonaId=GenPersona.Id
			INNER JOIN ComFactura ON ComFactura.ComProveedorId=ComProveedor.Id
			GROUP BY ComProveedor.Id, GenPersona.Nombre
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
	function QFRegRangoProveedor() {
		$sql = "
			SELECT
			CP_QFRegProveedor.Id,
			CP_QFRegProveedor.Nombre,
			CP_QFRegProveedor.FechaRegistro,
			DATEDIFF(DAY,CONVERT(DATE,CP_QFRegProveedor.FechaRegistro),GETDATE()) As RangoDias
			FROM CP_QFRegProveedor
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
	function QListaArticulos() {
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
	function QArticulo($IdArticulo) {
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
	function QExistenciaArticulo($IdArticulo,$CantAlmacen) {
		switch($CantAlmacen) {
			case '0':
				$sql = "
				SELECT
				SUM (InvLoteAlmacen.Existencia) As Existencia
				FROM InvLoteAlmacen
				WHERE(InvLoteAlmacen.InvAlmacenId = 1 OR InvLoteAlmacen.InvAlmacenId = 2)
				AND (InvLoteAlmacen.InvArticuloId = '$IdArticulo')
			";
			return $sql;
			break;
			
			case '1':
			$sql = "
				SELECT
				SUM(InvLoteAlmacen.Existencia) As Existencia
				FROM InvLoteAlmacen
				WHERE(InvLoteAlmacen.InvArticuloId = '$IdArticulo')
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
	function QLoteArticulo($IdArticulo,$CantAlmacen) {
		switch($CantAlmacen) {
			case '0':
			$sql = "
				SELECT
				InvLoteAlmacen.InvLoteId
				INTO CP_QLoteArticulo
				FROM InvLoteAlmacen
				WHERE(InvLoteAlmacen.InvAlmacenId = 1 OR InvLoteAlmacen.InvAlmacenId = 2)
				AND (InvLoteAlmacen.InvArticuloId = '$IdArticulo')
				AND (InvLoteAlmacen.Existencia>0)
			";
			return $sql;
			break;
			
			case '1':
			$sql = "
				SELECT
				InvLoteAlmacen.InvLoteId
				INTO CP_QLoteArticulo
				FROM InvLoteAlmacen
				WHERE(InvLoteAlmacen.InvArticuloId = '$IdArticulo')
				AND (InvLoteAlmacen.Existencia>0)
			";
			return $sql;
			break;

			case '2':
			$sql = "
				SELECT
				InvLoteAlmacen.InvLoteId
				INTO CP_QLoteArticulo
				FROM InvLoteAlmacen
				WHERE(InvLoteAlmacen.InvArticuloId = '$IdArticulo')
			";
			return $sql;
			break;
		}
	}
	/*
		TITULO: QLoteArticuloDiasCero
		PARAMETROS: [$IdArticulo] Id del articulo
					[$CantAlmacen] determina el los almacenes donde se buscara el lote
					0: para los almacenes [1,2]
					1: para todos los almacenes
		FUNCION: Busca el lote correspondiente al articulo especificado
		RETORNO: Regresa el Id del lote correspondiente
	 */
	function QLoteArticuloDiasCero($IdArticulo,$CantAlmacen) {
		switch($CantAlmacen) {
			case '0':
			$sql = "
				SELECT
				InvLoteAlmacen.InvLoteId
				INTO CP_QLoteArticuloDiasCero
				FROM InvLoteAlmacen
				WHERE(InvLoteAlmacen.InvAlmacenId = 1 OR InvLoteAlmacen.InvAlmacenId = 2)
				AND (InvLoteAlmacen.InvArticuloId = '$IdArticulo')
				AND (InvLoteAlmacen.Existencia>0)
			";
			return $sql;
			break;
			
			case '1':
			$sql = "
				SELECT
				InvLoteAlmacen.InvLoteId
				INTO CP_QLoteArticuloDiasCero
				FROM InvLoteAlmacen
				WHERE(InvLoteAlmacen.InvArticuloId = '$IdArticulo')
				AND (InvLoteAlmacen.Existencia>0)
			";
			return $sql;
			break;

			case '2':
			$sql = "
				SELECT
				InvLoteAlmacen.InvLoteId
				INTO CP_QLoteArticuloDiasCero
				FROM InvLoteAlmacen
				WHERE(InvLoteAlmacen.InvArticuloId = '$IdArticulo')
			";
			return $sql;
			break;
		}
	}

	/*
		TITULO: QLoteArticuloDevaluado
		PARAMETROS: [$IdArticulo] Id del articulo
					[$CantAlmacen] determina el los almacenes donde se buscara el lote
					0: para los almacenes [1,2]
					1: para todos los almacenes
		FUNCION: Busca el lote correspondiente al articulo especificado
		RETORNO: Regresa el Id del lote correspondiente
	 */
	function QLoteArticuloDevaluado($IdArticulo,$CantAlmacen) {
		switch($CantAlmacen) {
			case '0':
			$sql = "
				SELECT
				InvLoteAlmacen.InvLoteId
				INTO CP_QLoteArticuloDevaluado
				FROM InvLoteAlmacen
				WHERE(InvLoteAlmacen.InvAlmacenId = 1 OR InvLoteAlmacen.InvAlmacenId = 2)
				AND (InvLoteAlmacen.InvArticuloId = '$IdArticulo')
				AND (InvLoteAlmacen.Existencia>0)
			";
			return $sql;
			break;
			
			case '1':
			$sql = "
				SELECT
				InvLoteAlmacen.InvLoteId
				INTO CP_QLoteArticuloDevaluado
				FROM InvLoteAlmacen
				WHERE(InvLoteAlmacen.InvArticuloId = '$IdArticulo')
				AND (InvLoteAlmacen.Existencia>0)
			";
			return $sql;
			break;

			case '2':
			$sql = "
				SELECT
				InvLoteAlmacen.InvLoteId
				INTO CP_QLoteArticuloDevaluado
				FROM InvLoteAlmacen
				WHERE(InvLoteAlmacen.InvArticuloId = '$IdArticulo')
			";
			return $sql;
			break;
		}
	}

	/*
		TITULO: QLote
		PARAMETROS: Funciona en conjunto con QLoteArticulo
		FUNCION: Busca el precio de compra bruto y el precio troquelado
				 (Funciona en conjunto con QLoteArticulo)
		RETORNO: Retorna el precio de compra bruto y el precio troquelado
	 */
	function QLote() {
		$sql = "
			SELECT TOP 1
			InvLote.Id,
			InvLote.M_PrecioCompraBruto,
			InvLote.M_PrecioTroquelado
			FROM InvLote
			INNER JOIN CP_QLoteArticulo ON CP_QLoteArticulo.InvLoteId = InvLote.Id
			ORDER BY invlote.M_PrecioTroquelado, invlote.M_PrecioCompraBruto DESC
		";
		return $sql;
	}
	/*
		TITULO: QLoteDiasCero
		PARAMETROS: Funciona en conjunto con QLoteArticulo
		FUNCION: Busca el precio de compra bruto y el precio troquelado
				 (Funciona en conjunto con QLoteArticulo)
		RETORNO: Retorna el precio de compra bruto y el precio troquelado
	 */
	function QLoteDiasCero() {
		$sql = "
			SELECT TOP 1
			InvLote.Id,
			InvLote.M_PrecioCompraBruto,
			InvLote.M_PrecioTroquelado
			FROM InvLote
			INNER JOIN CP_QLoteArticuloDiasCero ON CP_QLoteArticuloDiasCero.InvLoteId = InvLote.Id
			ORDER BY invlote.M_PrecioTroquelado, invlote.M_PrecioCompraBruto DESC
		";
		return $sql;
	}

	/*
		TITULO: QLoteDevaluado
		PARAMETROS: Funciona en conjunto con QLoteArticuloDevaluado
		FUNCION: Busca el precio de compra bruto y el precio troquelado
				 (Funciona en conjunto con QLoteArticulo)
		RETORNO: Retorna el precio de compra bruto y el precio troquelado
	 */
	function QLoteDevaluado() {
		$sql = "
			SELECT TOP 1
			InvLote.Id,
			InvLote.M_PrecioCompraBruto,
			InvLote.M_PrecioTroquelado
			FROM InvLote
			INNER JOIN CP_QLoteArticuloDevaluado ON CP_QLoteArticulo.InvLoteId = InvLote.Id
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
	function QHistoricoArticulo($IdArticulo) {
		$sql = "
			SELECT
			GenPersona.Nombre,
			CONVERT(DATE,ComFactura.FechaRegistro) As FechaRegistro,
			CONVERT(DATE,ComFactura.FechaDocumento) As FechaDocumento,
			ComFacturaDetalle.CantidadRecibidaFactura,
			ComFacturaDetalle.M_PrecioCompraBruto
			FROM InvArticulo
			INNER JOIN ComFacturaDetalle ON InvArticulo.Id = ComFacturaDetalle.InvArticuloId
			INNER JOIN ComFactura ON ComFactura.Id = ComFacturaDetalle.ComFacturaId
			INNER JOIN ComProveedor ON ComProveedor.Id = ComFactura.ComProveedorId
			INNER JOIN GenPersona ON GenPersona.Id = ComProveedor.GenPersonaId
			WHERE InvArticulo.Id = '$IdArticulo'
			ORDER BY ComFactura.FechaDocumento DESC
		";
		return $sql;
	}
	/*
		TITULO: QDolarizados
		PARAMETROS: No Aplica
		FUNCION: Busca el Id del atributo si la categoriza es dolarizado
		RETORNO: Retorna el id del atributo dolarizado
	 */
	function QDolarizados() {
		$sql = "
		SELECT * 
		FROM InvAtributo 
		WHERE 
		InvAtributo.Descripcion = 'Dolarizados'
		OR  InvAtributo.Descripcion = 'Giordany'
		OR  InvAtributo.Descripcion = 'giordany'
		";
		return $sql;
	}
	/*
		TITULO: QArticuloDolarizado
		PARAMETROS: [$IdArticulo] id del articulo a buscar
					[$IdDolarizado] id del atributo dolarizado
		FUNCION: Busca detro de la tabla dolarizados el articulo en cuestion
		RETORNO: Retorna la dupla de valores de ser conseguido
	 */
	function QArticuloDolarizado($IdArticulo,$IdDolarizado) {
		$sql = "
		SELECT * 
		FROM InvArticuloAtributo 
		WHERE InvArticuloAtributo.InvAtributoId = '$IdDolarizado' 
		AND InvArticuloAtributo.InvArticuloId = '$IdArticulo'
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
	function QUnidadesVendidasCliente($FInicial,$FFinal) {
		$sql = "
			SELECT
			InvArticulo.Id,
			InvArticulo.CodigoArticulo,
			InvArticulo.Descripcion,
			COUNT(*) AS VecesVendidasCliente,
			SUM(VenFacturaDetalle.Cantidad) AS UnidadesVendidasCliente
			INTO CP_QUnidadesVendidasCliente
			--TablaTemp
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
	function QUnidadesDevueltaCliente($FInicial,$FFinal) {
		$sql = "
			SELECT
			InvArticulo.Id,
			InvArticulo.CodigoArticulo,
			InvArticulo.Descripcion,
			COUNT(*) AS VecesDevueltaCliente,
			SUM(VenDevolucionDetalle.Cantidad) AS UnidadesDevueltaCliente
			INTO CP_QUnidadesDevueltaCliente
			--TablaTemp1
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
	function QUnidadesCompradasProveedor($FInicial,$FFinal) {
		$sql = "
			SELECT
			InvArticulo.Id,
			InvArticulo.CodigoArticulo,
			InvArticulo.Descripcion,
			COUNT(*) AS VecesCompradasProveedor,
			SUM(ComFacturaDetalle.CantidadFacturada) AS UnidadesCompradasProveedor,
			CONVERT(DATE,ComFactura.FechaRegistro) AS FechaRegistro
			INTO CP_QUnidadesCompradasProveedor
			--TablaTemp2
			FROM ComFacturaDetalle
			INNER JOIN InvArticulo ON InvArticulo.Id = ComFacturaDetalle.InvArticuloId
			INNER JOIN ComFactura ON  ComFactura.Id = ComFacturaDetalle.ComFacturaId
			WHERE
			(ComFactura.FechaRegistro > '$FInicial' AND ComFactura.FechaRegistro < '$FFinal')
			GROUP BY InvArticulo.Id,InvArticulo.CodigoArticulo,InvArticulo.Descripcion,FechaRegistro
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
	function QUnidadesReclamoProveedor($FInicial,$FFinal) {
		$sql = "
			SELECT
			InvArticulo.Id,
			InvArticulo.CodigoArticulo,
			InvArticulo.Descripcion,
			COUNT(*) AS VecesReclamoProveedor,
			SUM(ComReclamoDetalle.Cantidad) AS UnidadesReclamoProveedor
			INTO CP_QUnidadesReclamoProveedor
			--TablaTemp3
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
	function QIntegracionProductosVendidos() {
		$sql = " 
			SELECT
			CP_QUnidadesVendidasCliente.Id,
			CP_QUnidadesVendidasCliente.CodigoArticulo,
			CP_QUnidadesVendidasCliente.Descripcion,
			ISNULL(CP_QUnidadesVendidasCliente.VecesVendidasCliente,CAST(0 AS INT)) AS VecesVendidasCliente,
			ISNULL(CP_QUnidadesDevueltaCliente.VecesDevueltaCliente,CAST(0 AS INT)) AS VecesDevueltaCliente,
			ISNULL(CP_QUnidadesVendidasCliente.UnidadesVendidasCliente,CAST(0 AS INT)) AS UnidadesVendidasCliente,
			ISNULL(CP_QUnidadesDevueltaCliente.UnidadesDevueltaCliente,CAST(0 AS INT)) AS UnidadesDevueltaCliente,
			ISNULL(CP_QUnidadesCompradasProveedor.VecesCompradasProveedor,CAST(0 AS INT)) AS VecesCompradasProveedor,
			ISNULL(CP_QUnidadesReclamoProveedor.VecesReclamoProveedor,CAST(0 AS INT)) AS VecesReclamoProveedor,
			ISNULL(CP_QUnidadesCompradasProveedor.UnidadesCompradasProveedor,CAST(0 AS INT)) AS UnidadesCompradasProveedor,
			ISNULL(CP_QUnidadesReclamoProveedor.UnidadesReclamoProveedor,CAST(0 AS INT)) AS UnidadesReclamoProveedor
			INTO CP_QIntegracionProductosVendidos
			--TablaTemp4
			FROM CP_QUnidadesVendidasCliente
			LEFT JOIN CP_QUnidadesDevueltaCliente ON CP_QUnidadesDevueltaCliente.Id = CP_QUnidadesVendidasCliente.Id
			LEFT JOIN CP_QUnidadesCompradasProveedor ON CP_QUnidadesCompradasProveedor.Id = CP_QUnidadesVendidasCliente.Id
			LEFT JOIN CP_QUnidadesReclamoProveedor ON CP_QUnidadesReclamoProveedor.Id = CP_QUnidadesVendidasCliente.Id
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
	function QProductoMasVendido($Top) {
		$sql = "
			SELECT TOP $Top
			CP_QIntegracionProductosVendidos.Id,
			CP_QIntegracionProductosVendidos.CodigoArticulo,
			CP_QIntegracionProductosVendidos.Descripcion,
			CP_QIntegracionProductosVendidos.VecesVendidasCliente,
			CP_QIntegracionProductosVendidos.VecesDevueltaCliente,
			ISNULL((VecesVendidasCliente-VecesDevueltaCliente),0) AS TotalVecesVendidasCliente,
			CP_QIntegracionProductosVendidos.UnidadesVendidasCliente,
			CP_QIntegracionProductosVendidos.UnidadesDevueltaCliente,
			ISNULL((UnidadesVendidasCliente-UnidadesDevueltaCliente),0) AS TotalUnidadesVendidasCliente,
			CP_QIntegracionProductosVendidos.VecesCompradasProveedor,
			CP_QIntegracionProductosVendidos.VecesReclamoProveedor,
			ISNULL((VecesCompradasProveedor-VecesReclamoProveedor),0) AS TotalVecesCompradasProveedor,
			CP_QIntegracionProductosVendidos.UnidadesCompradasProveedor,
			CP_QIntegracionProductosVendidos.UnidadesReclamoProveedor,
			ISNULL((UnidadesCompradasProveedor-UnidadesReclamoProveedor),0) AS TotalUnidadesCompradasProveedor
			FROM CP_QIntegracionProductosVendidos
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
	function QProductoMenosVendido($Top) {
		$sql = "
			SELECT TOP $Top
			CP_QIntegracionProductosVendidos.Id,
			CP_QIntegracionProductosVendidos.CodigoArticulo,
			CP_QIntegracionProductosVendidos.Descripcion,
			CP_QIntegracionProductosVendidos.VecesVendidasCliente,
			CP_QIntegracionProductosVendidos.VecesDevueltaCliente,
			ISNULL((VecesVendidasCliente-VecesDevueltaCliente),0) AS TotalVecesVendidasCliente,
			CP_QIntegracionProductosVendidos.UnidadesVendidasCliente,
			CP_QIntegracionProductosVendidos.UnidadesDevueltaCliente,
			ISNULL((UnidadesVendidasCliente-UnidadesDevueltaCliente),0) AS TotalUnidadesVendidasCliente,
			CP_QIntegracionProductosVendidos.VecesCompradasProveedor,
			CP_QIntegracionProductosVendidos.VecesReclamoProveedor,
			ISNULL((VecesCompradasProveedor-VecesReclamoProveedor),0) AS TotalVecesCompradasProveedor,
			CP_QIntegracionProductosVendidos.UnidadesCompradasProveedor,
			CP_QIntegracionProductosVendidos.UnidadesReclamoProveedor,
			ISNULL((UnidadesCompradasProveedor-UnidadesReclamoProveedor),0) AS TotalUnidadesCompradasProveedor
			FROM CP_QIntegracionProductosVendidos
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
	function QIntegracionProductosFalla() {
		$sql="
			SELECT
			CP_QIntegracionProductosVendidos.Id,
			CP_QIntegracionProductosVendidos.CodigoArticulo,
			CP_QIntegracionProductosVendidos.Descripcion,
			CP_QIntegracionProductosVendidos.VecesVendidasCliente,
			CP_QIntegracionProductosVendidos.VecesDevueltaCliente,
			ISNULL((VecesVendidasCliente-VecesDevueltaCliente),0) AS TotalVecesVendidasCliente,
			CP_QIntegracionProductosVendidos.UnidadesVendidasCliente,
			CP_QIntegracionProductosVendidos.UnidadesDevueltaCliente,
			ISNULL((UnidadesVendidasCliente-UnidadesDevueltaCliente),0) AS TotalUnidadesVendidasCliente,
			CP_QIntegracionProductosVendidos.VecesCompradasProveedor,
			CP_QIntegracionProductosVendidos.VecesReclamoProveedor,
			ISNULL((VecesCompradasProveedor-VecesReclamoProveedor),0) AS TotalVecesCompradasProveedor,
			CP_QIntegracionProductosVendidos.UnidadesCompradasProveedor,
			CP_QIntegracionProductosVendidos.UnidadesReclamoProveedor,
			ISNULL((UnidadesCompradasProveedor-UnidadesReclamoProveedor),0) AS TotalUnidadesCompradasProveedor,
			SUM(InvLoteAlmacen.Existencia) AS Existencia
			INTO CP_QIntegracionProductosFalla
			--TablaTemp5		
			FROM CP_QIntegracionProductosVendidos
			INNER JOIN InvLoteAlmacen ON InvLoteAlmacen.InvArticuloId = CP_QIntegracionProductosVendidos.Id
			WHERE(InvLoteAlmacen.InvAlmacenId = 1 or InvLoteAlmacen.InvAlmacenId = 2)
			GROUP BY
			CP_QIntegracionProductosVendidos.Id,
			CP_QIntegracionProductosVendidos.CodigoArticulo,
			CP_QIntegracionProductosVendidos.Descripcion,
			CP_QIntegracionProductosVendidos.VecesVendidasCliente,
			CP_QIntegracionProductosVendidos.VecesDevueltaCliente,
			CP_QIntegracionProductosVendidos.UnidadesVendidasCliente,
			CP_QIntegracionProductosVendidos.UnidadesDevueltaCliente,
			CP_QIntegracionProductosVendidos.VecesCompradasProveedor,
			CP_QIntegracionProductosVendidos.VecesReclamoProveedor,
			CP_QIntegracionProductosVendidos.UnidadesCompradasProveedor,
			CP_QIntegracionProductosVendidos.UnidadesReclamoProveedor
			ORDER BY CP_QIntegracionProductosVendidos.UnidadesVendidasCliente DESC
		";
		return $sql;
	}
	/*
		TITULO: QProductosFalla
		PARAMETROS: Funciona en conjunto con QIntegracionProductosFalla
		FUNCION: Arma una lista de productos vendido con existencia en 0
		RETORNO: Lista de productos mas vendidos con existencia en 0
	 */
	function QProductosFalla() {
		$sql = "
			SELECT * FROM CP_QIntegracionProductosFalla
			WHERE CP_QIntegracionProductosFalla.Existencia = 0
			ORDER BY CP_QIntegracionProductosFalla.TotalUnidadesVendidasCliente DESC
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
	function QArticuloDescLike($DescripLike,$IntoTabla) {
		switch($IntoTabla) {
			case '0':
			$sql = "
				SELECT
				InvArticulo.Id,
				InvArticulo.CodigoArticulo,
				InvArticulo.Descripcion,
				InvArticulo.FinConceptoImptoIdCompra AS ConceptoImpuesto
				INTO CP_QArticuloDescLike
				--TablaTemp6
				FROM InvArticulo
				WHERE InvArticulo.Descripcion LIKE '%$DescripLike%'
				ORDER BY InvArticulo.Descripcion ASC
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
				ORDER BY InvArticulo.Descripcion ASC
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
	function QUltimoLote($IdArticulo) {
		$sql="
			SELECT TOP 1
			InvLote.Id,
			InvLote.M_PrecioCompraBruto,
			InvLote.M_PrecioTroquelado,
			CONVERT(DATE,InvLote.FechaEntrada) AS UltimoLote
			FROM InvLote
			WHERE InvLote.InvArticuloId  = '$IdArticulo'
			ORDER BY UltimoLote DESC
		";
		return $sql;
	}
	/*
		TITULO: QUltimaVentaCR
		PARAMETROS: [$IdArticulo] Id del articulo a buscar
		FUNCION: Buscar la fecha de la ultima venta del articulo dentro del rango 
		RETORNO: La fecha de la ultima venta
	 */
	function QUltimaVentaCR($IdArticulo,$FInicial,$FFinal) {
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
	function QPedidoProductos() {
		$sql="
			SELECT
			CP_QArticuloDescLike.Id,
			CP_QArticuloDescLike.CodigoArticulo,
			CP_QArticuloDescLike.Descripcion,
			CP_QArticuloDescLike.ConceptoImpuesto,
			ISNULL((CP_QIntegracionProductosFalla.TotalVecesVendidasCliente),0) AS TotalVecesVendidasCliente,
			ISNULL((CP_QIntegracionProductosFalla.TotalUnidadesVendidasCliente),0) AS TotalUnidadesVendidasCliente,
			ISNULL((CP_QIntegracionProductosFalla.TotalVecesCompradasProveedor),0) AS TotalVecesCompradasProveedor,
			ISNULL((CP_QIntegracionProductosFalla.TotalUnidadesCompradasProveedor),0) AS TotalUnidadesCompradasProveedor
			FROM CP_QArticuloDescLike
			LEFT JOIN CP_QIntegracionProductosFalla ON CP_QIntegracionProductosFalla.Id = CP_QArticuloDescLike.Id
			ORDER BY CP_QArticuloDescLike.Descripcion ASC
		";
		return $sql;
	}
	/*
		TITULO: QfacturaProveedor
		PARAMETROS: [$IdProveedor] Id del proveedor a buscar
		FUNCION: Buscar la lista de facturas donde interviene el proveedor
		RETORNO: lista de facturas
	 */
	function QfacturaProveedor($IdProveedor) {
		$sql = "
		SELECT
		ComFactura.Id AS FacturaId
		INTO CP_QfacturaProveedor
		--TablaTemp
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
	function QDetalleFactura() {
		$sql = '
		SELECT
		ComFacturaDetalle.ComFacturaId AS FacturaId,
		ComFacturaDetalle.InvArticuloId AS ArtiuloId
		INTO CP_QDetalleFactura
		--TablaTemp1
		FROM ComFacturaDetalle
		INNER JOIN CP_QfacturaProveedor ON CP_QfacturaProveedor.FacturaId = ComFacturaDetalle.ComFacturaId
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
	function QCatalogoProductosProveedor() {
		$sql = '
		SELECT
		InvArticulo.Id,
		InvArticulo.CodigoArticulo,
		InvArticulo.Descripcion,
		InvArticulo.FinConceptoImptoIdCompra AS ConceptoImpuesto
		FROM InvArticulo
		INNER JOIN CP_QDetalleFactura ON CP_QDetalleFactura.ArtiuloId = InvArticulo.Id
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
	function QFacturasProducto($IdArticulo) {
		$sql = "
		SELECT
		ComFacturaDetalle.ComFacturaId AS FacturaId
		INTO CP_QFacturasProducto
		--TablaTemp2
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
	function QProvedorUnico($IdProveedor) {
		$sql = "
		SELECT
		ComFactura.ComProveedorId
		INTO CP_QProvedorUnico
		--TablaTemp3
		FROM CP_QFacturasProducto
		INNER JOIN ComFactura ON ComFactura.Id = CP_QFacturasProducto.FacturaId
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
	function QProvedorUnicoLista() {
		$sql = "
		SELECT
		ComProveedor.Id,
		GenPersona.Nombre
		FROM ComProveedor
		INNER JOIN GenPersona ON ComProveedor.GenPersonaId=GenPersona.Id
		INNER JOIN ComFactura ON ComFactura.ComProveedorId=ComProveedor.Id
		INNER JOIN CP_QProvedorUnico ON CP_QProvedorUnico.ComProveedorId=ComProveedor.Id
		GROUP BY ComProveedor.Id, GenPersona.Nombre
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
	function QUnidadesVendidasClienteId($FInicial,$FFinal,$Id) {
		$sql = "
			SELECT
			InvArticulo.Id,
			InvArticulo.CodigoArticulo,
			InvArticulo.Descripcion,
			COUNT(*) AS VecesVendidasCliente,
			SUM(VenFacturaDetalle.Cantidad) AS UnidadesVendidasCliente
			INTO CP_QUnidadesVendidasClienteId
			--TablaTemp
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
	function QUnidadesDevueltaClienteId($FInicial,$FFinal,$Id) {
		$sql = "
			SELECT
			InvArticulo.Id,
			InvArticulo.CodigoArticulo,
			InvArticulo.Descripcion,
			COUNT(*) AS VecesDevueltaCliente,
			SUM(VenDevolucionDetalle.Cantidad) AS UnidadesDevueltaCliente
			INTO CP_QUnidadesDevueltaClienteId
			--TablaTemp1
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
	function QUnidadesCompradasProveedorId($FInicial,$FFinal,$Id) {
		$sql = "
			SELECT
			InvArticulo.Id,
			InvArticulo.CodigoArticulo,
			InvArticulo.Descripcion,
			COUNT(*) AS VecesCompradasProveedor,
			SUM(ComFacturaDetalle.CantidadFacturada) AS UnidadesCompradasProveedor
			INTO CP_QUnidadesCompradasProveedorId
			--TablaTemp2
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
	function QUnidadesReclamoProveedorId($FInicial,$FFinal,$Id) {
		$sql = "
			SELECT
			InvArticulo.Id,
			InvArticulo.CodigoArticulo,
			InvArticulo.Descripcion,
			COUNT(*) AS VecesReclamoProveedor,
			SUM(ComReclamoDetalle.Cantidad) AS UnidadesReclamoProveedor
			INTO CP_QUnidadesReclamoProveedorId
			--TablaTemp3
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
		TITULO: QIntegracionProductosVendidos
		PARAMETROS: No aplica
		FUNCION: Integrar la informacion de las consultas
				[QUnidadesVendidasCliente,QUnidadesDevueltaCliente,QUnidadesCompradasProveedor,QUnidadesReclamoProveedor]
		RETORNO: Tabla con datos integrados con valores 0 para campos NULL
	 */
	function QIntegracionProductosVendidosId() {
		$sql = " 
			SELECT
			CP_QUnidadesVendidasClienteId.Id,
			CP_QUnidadesVendidasClienteId.CodigoArticulo,
			CP_QUnidadesVendidasClienteId.Descripcion,
			ISNULL(CP_QUnidadesVendidasClienteId.VecesVendidasCliente,CAST(0 AS INT)) AS VecesVendidasCliente,
			ISNULL(CP_QUnidadesDevueltaClienteId.VecesDevueltaCliente,CAST(0 AS INT)) AS VecesDevueltaCliente,
			ISNULL(CP_QUnidadesVendidasClienteId.UnidadesVendidasCliente,CAST(0 AS INT)) AS UnidadesVendidasCliente,
			ISNULL(CP_QUnidadesDevueltaClienteId.UnidadesDevueltaCliente,CAST(0 AS INT)) AS UnidadesDevueltaCliente,
			ISNULL(CP_QUnidadesCompradasProveedorId.VecesCompradasProveedor,CAST(0 AS INT)) AS VecesCompradasProveedor,
			ISNULL(CP_QUnidadesReclamoProveedorId.VecesReclamoProveedor,CAST(0 AS INT)) AS VecesReclamoProveedor,
			ISNULL(CP_QUnidadesCompradasProveedorId.UnidadesCompradasProveedor,CAST(0 AS INT)) AS UnidadesCompradasProveedor,
			ISNULL(CP_QUnidadesReclamoProveedorId.UnidadesReclamoProveedor,CAST(0 AS INT)) AS UnidadesReclamoProveedor
			INTO CP_QIntegracionProductosVendidosId
			--TablaTemp4
			FROM CP_QUnidadesVendidasClienteId
			LEFT JOIN CP_QUnidadesDevueltaClienteId ON CP_QUnidadesDevueltaClienteId.Id = CP_QUnidadesVendidasClienteId.Id
			LEFT JOIN CP_QUnidadesCompradasProveedorId ON CP_QUnidadesCompradasProveedorId.Id = CP_QUnidadesVendidasClienteId.Id
			LEFT JOIN CP_QUnidadesReclamoProveedorId ON CP_QUnidadesReclamoProveedorId.Id = CP_QUnidadesVendidasClienteId.Id
			ORDER BY UnidadesVendidasCliente DESC
		";
		return $sql;
	}
	/*
		TITULO: QfacturaProveedorToquel
		PARAMETROS: [$IdProveedor] Id del proveedor a buscar
		FUNCION: Buscar la lista de facturas donde interviene el proveedor
		RETORNO: lista de facturas
	 */
	function QfacturaProveedorToquel($IdProveedor) {
		$sql = "
		SELECT
		ComFactura.Id AS FacturaId,
		ComFactura.NumeroFactura,
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
	function QNumeroFactura($IdFactura) {
		$sql = "
			SELECT ComFactura.NumeroFactura AS NumeroFactura
			FROM ComFactura 
			WHERE ComFactura.Id = '$IdFactura'
		";
		return $sql;
	}
	/*
		TITULO: QFacturaArticulo
		PARAMETROS:[$IdFatura] Id de la factura a buscar
		FUNCION: busca los articulos que intervienen en una factura
		RETORNO: lista de articulos
	 */
	function QFacturaArticulo($IdFatura) {
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
		TITULO: QTroquelArticuloFactura
		PARAMETROS: [$IdFatura] id de la factura
					[$IdArticulo] id del articulo
		FUNCION: busca el troquel del articulo de la factura
		RETORNO: retora en valor del troquel del articulo
	 */
	function QTroquelArticuloFactura($IdFatura,$IdArticulo) {
		$sql = "
		SELECT
		ComFacturaDetalle.ComFacturaId,
		ComFacturaDetalle.InvArticuloId,
		ISNULL(ComFacturaDetalle.M_PrecioTroquelado,CAST(0 AS INT)) AS M_PrecioTroquelado
		FROM ComFacturaDetalle
		WHERE ComFacturaDetalle.ComFacturaId = '$IdFatura' AND ComFacturaDetalle.InvArticuloId = '$IdArticulo'
		";
		return $sql;
	}
	/*
		TITULO: QActualizarTroquel
		PARAMETROS: [$IdFatura] id de la factura
					[$IdArticulo] id del articulo
					[$PrecioTroquel] precio troquelado del aticulo
		FUNCION: actualiza el precio del troquel del articulo
		RETORNO: precio actualizado del troquel
	 */
	function QActualizarTroquel($IdFatura,$IdArticulo,$PrecioTroquel) {
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
		TITULO: QTiempoEnTienda
		PARAMETROS: [$IdArticulo] Id del articulo actual
		FUNCION: Construir la consulta para saber la fecha en dias
		RETORNO: Un String con el query
	 */
	function QTiempoEnTienda($IdArticulo) {
		$sql = "
		SELECT TOP 1
		CONVERT(DATE,InvLoteAlmacen.Auditoria_FechaCreacion) AS FechaTienda,
		DATEDIFF(DAY,CONVERT(DATE,InvLoteAlmacen.Auditoria_FechaCreacion),GETDATE())
		AS TiempoTienda
		FROM InvLoteAlmacen
		WHERE
		InvLoteAlmacen.InvArticuloId = '$IdArticulo' 
		AND (InvLoteAlmacen.InvAlmacenId = 1 OR InvLoteAlmacen.InvAlmacenId = 2) 
		AND InvLoteAlmacen.Existencia > 0
		ORDER BY InvLoteAlmacen.Auditoria_FechaCreacion DESC
		";
		return $sql;
	}

	/*
		TITULO: QloteAlmacenAnalitico
		PARAMETROS: [$IdArticulo] Id del articulo actual
		FUNCION: Construir la consulta para el despliegue del reporte AnaliticoDePrecio
		RETORNO: Un String con las instrucciones de la consulta
	 */
	function QLoteAlmacenAnalitico($IdArticulo) {
		$sql = "
			SELECT 
				InvLoteAlmacen.InvLoteId,
				CONVERT(DATE,InvLoteAlmacen.Auditoria_FechaCreacion) AS FechaLote,
				InvLoteAlmacen.Existencia,
				InvAlmacen.CodigoAlmacen,
				InvLote.Auditoria_Usuario AS Responsable,
				InvLote.M_PrecioCompraBruto,
				InvMovimiento.InvCausaId,
				InvCausa.Descripcion
			FROM InvLoteAlmacen
			INNER JOIN InvAlmacen ON InvAlmacen.Id=InvLoteAlmacen.InvAlmacenId
			INNER JOIN InvLote ON InvLote.Id=InvLoteAlmacen.InvLoteId
			INNER JOIN InvMovimiento ON InvMovimiento.InvLoteId = InvLoteAlmacen.InvLoteId
			INNER JOIN InvCausa ON InvMovimiento.InvCausaId=InvCausa.Id
			WHERE(
				(InvLoteAlmacen.InvArticuloId='$IdArticulo')
				AND (InvLoteAlmacen.Existencia>0)
				AND (CONVERT(DATE,InvMovimiento.Auditoria_FechaCreacion)=CONVERT(DATE,InvLoteAlmacen.Auditoria_FechaCreacion)) 
				AND (
					InvMovimiento.InvCausaId=1 
					OR InvMovimiento.InvCausaId=5
					OR InvMovimiento.InvCausaId=7
					OR InvMovimiento.InvCausaId=9
					OR InvMovimiento.InvCausaId=11
					OR InvMovimiento.InvCausaId=14
				)
			)
			GROUP BY InvLoteAlmacen.InvLoteId,InvLoteAlmacen.Auditoria_FechaCreacion,Existencia,CodigoAlmacen,InvLote.Auditoria_Usuario,M_PrecioCompraBruto,InvMovimiento.InvCausaId,InvCausa.Descripcion
			ORDER BY FechaLote DESC
		";
		return $sql;
	}

	/*
		TITULO: QProveedorYCantidadComprada
		PARAMETROS: [$IdArticulo] Id del articulo actual, [$FechaLote] fecha de creacion del lote
		FUNCION: Arma las filas correspondientes a los proveedores y el despacho
		RETORNO: Un String con las instrucciones de la query
	 */
	function QProveedorYCantidadComprada($IdArticulo,$FechaLote) {
		$sql = "
			SELECT
				GenPersona.Nombre,
				ComFacturaDetalle.CantidadRecibidaFactura
			FROM InvArticulo
			INNER JOIN ComFacturaDetalle ON InvArticulo.Id=ComFacturaDetalle.InvArticuloId
			INNER JOIN ComFactura ON ComFactura.Id=ComFacturaDetalle.ComFacturaId
			INNER JOIN ComProveedor ON ComProveedor.Id=ComFactura.ComProveedorId
			INNER JOIN GenPersona ON GenPersona.Id=ComProveedor.GenPersonaId
			WHERE(
				(InvArticulo.Id = '$IdArticulo') 
				AND (CONVERT(DATE,ComFactura.FechaRegistro)='$FechaLote')
			)
			ORDER BY ComFactura.FechaRegistro DESC
		";
		return $sql;
	}

	function QProveedorYCantidadComprada2($IdArticulo,$FechaLote,$PrecioBruto) {
		$sql = "
			SELECT
				GenPersona.Nombre,
				ComFacturaDetalle.CantidadRecibidaFactura,
				ComFacturaDetalle.M_PrecioCompraBruto
			FROM InvArticulo
			INNER JOIN ComFacturaDetalle ON InvArticulo.Id=ComFacturaDetalle.InvArticuloId
			INNER JOIN ComFactura ON ComFactura.Id=ComFacturaDetalle.ComFacturaId
			INNER JOIN ComProveedor ON ComProveedor.Id=ComFactura.ComProveedorId
			INNER JOIN GenPersona ON GenPersona.Id=ComProveedor.GenPersonaId
			WHERE(
				(InvArticulo.Id = '$IdArticulo') 
				AND (CONVERT(DATE,ComFactura.FechaRegistro)='$FechaLote')
				AND ComFacturaDetalle.M_PrecioCompraBruto=$PrecioBruto
			)
			ORDER BY ComFactura.FechaRegistro DESC
		";
		return $sql;
	}

	/*
		TITULO: QCartaDeCompromiso
		PARAMETROS: [$IdProveedor] Id del provedor solicitado, 
					[$IdFatura] Id de la factura actual
					[$IdArticulo] Id del articulo solicitado
		FUNCION: Construir las columnas correspondientes al reporte
		RETORNO: Un String con la query
	 */
	function QCartaDeCompromiso($IdProveedor,$IdFatura,$IdArticulo) {
		$sql = "
		SELECT ComFactura.ComProveedorId,
		InvLote.Numero AS NumeroLote,
		InvLote.LoteFabricante,
		ComFactura.NumeroFactura, 
		CONVERT(DATE,ComFactura.FechaDocumento) AS FechaDocumento,
		CONVERT(DATE,ComFactura.FechaRegistro) AS FechaRecepcion,
		CONVERT(DATE,InvLote.FechaVencimiento) AS FechaVencimiento,
		InvArticulo.CodigoArticulo,
		InvArticulo.Descripcion
		FROM ComFactura
		INNER JOIN ComFacturaDetalle ON ComFactura.Id = ComFacturaDetalle.ComFacturaId
		INNER JOIN InvArticulo ON ComFacturaDetalle.InvArticuloId = InvArticulo.Id
		INNER JOIN InvLote ON InvLote.InvArticuloId = InvArticulo.Id
		WHERE ComFactura.ComProveedorId='$IdProveedor' 
		AND ComFactura.Id='$IdFatura'
		AND InvArticulo.Id='$IdArticulo'
		AND CONVERT(DATE,ComFactura.FechaRegistro) = CONVERT(DATE,InvLote.FechaEntrada)
		GROUP BY ComFactura.ComProveedorId, InvLote.Numero, InvLote.LoteFabricante, ComFactura.NumeroFactura, CONVERT(DATE,ComFactura.FechaDocumento), CONVERT(DATE,ComFactura.FechaRegistro), CONVERT(DATE,InvLote.FechaVencimiento), InvArticulo.CodigoArticulo, InvArticulo.Descripcion
		ORDER BY InvLote.Numero ASC
		";
		return $sql;
	}
	/*
		TITULO: QDiasEnCero
		PARAMETROS: No aplica
		FUNCION: Construir las columnas correspondientes al reporte
		RETORNO: Un String con la query
	 */
	function QDiasEnCero() {
		$sql = "
		SELECT 
		InvArticulo.Id AS IdArticulo,
		InvArticulo.CodigoArticulo AS CodigoInterno,
		InvArticulo.Descripcion,
		SUM(InvLoteAlmacen.Existencia) AS Existencia,
		InvArticulo.FinConceptoImptoIdCompra AS ConceptoImpuesto
		FROM InvArticulo
		INNER JOIN InvLoteAlmacen ON InvArticulo.Id=InvLoteAlmacen.InvArticuloId
		WHERE InvLoteAlmacen.Existencia > 0
		AND (InvLoteAlmacen.InvAlmacenId = 1 OR InvLoteAlmacen.InvAlmacenId = 2)
		GROUP BY InvArticulo.Id, InvArticulo.CodigoArticulo, InvArticulo.Descripcion, InvArticulo.FinConceptoImptoIdCompra
		ORDER BY InvArticulo.Id ASC
		";
		return $sql;
	}
	/*
		TITULO: QGuardarDiasEnCero
		PARAMETROS: [$proveedor] Id del provedor solicitado
					[$articulo] Descripcion del articulo
					[$lote] Lote al que pertenece el articulo
					[$fecha_documento] Fecha de la factura
					[$fecha_recepcion] Fecha de recepcion del articulo
					[$fecha_vencimiento] Fecha de vencimiento del articulo
					[$fecha_tope] Fecha tope del compromiso
					[$causa] Causa del compromiso
					[$nota] Notas adicionales
					[$user] Usuario responsable
					[$date] Fecha de creacion del compromiso
		FUNCION: Construir las columnas correspondientes al reporte
		RETORNO: Un String con la query
	 */
	function QGuardarDiasEnCero($IdArticulo,$CodigoInterno,$Descripcion,$Existencia,$Precio,$FechaCaptura,$user,$date) {
		$sql = "
		INSERT INTO dias_ceros 
		(id_articulo,codigo_articulo,descripcion,existencia,precio,fecha_captura,user,created_at,updated_at)
		VALUES 
		('$IdArticulo','$CodigoInterno','$Descripcion','$Existencia','$Precio','$FechaCaptura','$user','$date','$date')
		";
		return $sql;
	}
	/*
		TITULO: QGuardarCapturaDiaria
		PARAMETROS: [$FechaCaptura] El dia de hoy
					[$date] valor para creacion y actualizacion
		FUNCION: crea una conexion con la base de datos cpharma e ingresa datos
		RETORNO: no aplica
	 */

	function QGuardarCapturaDiaria($TotalRegistros,$FechaCaptura,$date) {
		$sql = "
		INSERT INTO capturas_diarias 
		(total_registros,fecha_captura,created_at,updated_at)
		VALUES 
		('$TotalRegistros','$FechaCaptura','$date','$date')
		";
		return $sql;
	}
	/*
		TITULO: QCapturaDiaria
		PARAMETROS: [$FechaCaptura] El dia de hoy
		FUNCION: cuenta el total de registos de dias en cero
		RETORNO: no aplica
	 */

	function QCapturaDiaria($FechaCaptura) {
		$sql = "SELECT COUNT(*) AS TotalRegistros
		FROM dias_ceros 
		WHERE dias_ceros.fecha_captura = '$FechaCaptura'
		";
		return $sql;
	}
	/*
		TITULO: QVerCapturaDiaria
		PARAMETROS: [$FechaCaptura] El dia de hoy
		FUNCION: consulta todos los datos de la captura diaria
		RETORNO: no aplica
	 */
	function QVerCapturaDiaria() {
		$sql = "SELECT * FROM capturas_diarias";
		return $sql;
	}
	/*
		TITULO: QVerCapturaDiaria
		PARAMETROS: [$FechaCaptura] El dia de hoy
		FUNCION: valida que la fecha exista en la tabla captura diaria
		RETORNO: no aplica
	 */
	function QValidarCapturaDiaria($FechaCaptura) {
		$sql = "SELECT count(*) AS CuentaCaptura 
		FROM capturas_diarias WHERE fecha_captura = '$FechaCaptura'";
		return $sql;
	}
	/*
		TITULO: QBorrarDiasCero
		PARAMETROS: [$FechaCaptura] fecha de la captura
		FUNCION: borra los registros de dias en cero de la fecha seleccionada
		RETORNO: no aplica
	 */
	function QBorrarDiasCero($FechaCaptura) {
		$sql = "DELETE FROM dias_ceros WHERE fecha_captura = '$FechaCaptura'";
		return $sql;
	}
	/*
		TITULO: QGuardarAuditoria
		PARAMETROS: $accion,$tabla,$registro,$user
		FUNCION: capturar y guardar el evento en la auditoria
		RETORNO: no aplica
	 */
	function QGuardarAuditoria($accion,$tabla,$registro,$user,$date) {
		$sql = "
		INSERT INTO auditorias
		(accion,tabla,registro,user,created_at,updated_at)
		VALUES 
		('$accion','$tabla','$registro','$user','$date','$date')
		";
		return $sql;
	}
	/*
		TITULO: QCodigoBarra
		PARAMETROS: [$IdArticulo] Id del articulo que se va a buscar
		FUNCION: Buscar el codigo de barra del articulo
		RETORNO: Codigo de barra del articulo
	 */
	function QCodigoBarra($IdArticulo) {
		$sql = "
			SELECT * 
			FROM InvCodigoBarra 
			WHERE InvCodigoBarra.InvArticuloId = '$IdArticulo'
			AND InvCodigoBarra.EsPrincipal = 1
		";
		return $sql;
	}

	/*
		TITULO: QDetalleDeMovimiento
		PARAMETROS: [$IdArticulo] Id del articulo actual
		FUNCION: Construir la consulta para el despliegue del reporte DetalleDeMovimiento
		RETORNO: Un String con las instrucciones de la consulta
	 */
	function QDetalleDeMovimiento($IdArticulo,$FInicial,$FFinal) {
		$sql = "
			SELECT 
				InvMovimiento.InvLoteId,
				InvMovimiento.FechaMovimiento,
				InvMovimiento.InvCausaId,
				InvCausa.Descripcion AS Movimiento,
				InvMovimiento.Cantidad
			FROM InvMovimiento
			INNER JOIN InvCausa ON InvMovimiento.InvCausaId=InvCausa.Id
			WHERE InvMovimiento.InvArticuloId='$IdArticulo'
			AND (CONVERT(DATE,InvMovimiento.FechaMovimiento) >= '$FInicial' AND CONVERT(DATE,InvMovimiento.FechaMovimiento) <= '$FFinal')
			GROUP BY InvMovimiento.InvLoteId,InvMovimiento.FechaMovimiento,InvMovimiento.InvCausaId,InvCausa.Descripcion,InvMovimiento.Cantidad
			ORDER BY InvMovimiento.FechaMovimiento ASC
		";
		return $sql;
	}

	/*
		TITULO: QUltimaVentaSR
		PARAMETROS: [$IdArticulo] Id del articulo a buscar
		FUNCION: Buscar la fecha de la ultima venta del articulo sin rango 
		RETORNO: La fecha de la ultima venta
	 */
	function QUltimaVentaSR($IdArticulo) {
		$sql="
			SELECT TOP 1
			CONVERT(DATE,VenFactura.FechaDocumento) AS UltimaVenta
			FROM VenFactura
			INNER JOIN VenFacturaDetalle ON VenFacturaDetalle.VenFacturaId = VenFactura.Id
			WHERE VenFacturaDetalle.InvArticuloId = '$IdArticulo'
			ORDER BY FechaDocumento DESC
		";
		return $sql;
	}
	/*
		TITULO: QMedicinas
		PARAMETROS: No Aplica
		FUNCION: Busca el Id del atributo si la categoriza es medicinas
		RETORNO: Retorna el id del atributo medicinas
	 */
	function QMedicinas() {
		$sql = "
		SELECT * 
		FROM InvAtributo 
		WHERE 
		InvAtributo.Descripcion = 'Medicina'
		";
		return $sql;
	}
	/*
		TITULO: QArticuloMedicina
		PARAMETROS: [$IdArticulo] id del articulo a buscar
					[$IdMedicina] id del atributo dolarizado
		FUNCION: Busca detro de la tabla medicinas el articulo en cuestion
		RETORNO: Retorna la dupla de valores de ser conseguido
	 */
	function QArticuloMedicina($IdArticulo,$IdMedicina) {
		$sql = "
		SELECT * 
		FROM InvArticuloAtributo 
		WHERE InvArticuloAtributo.InvAtributoId = '$IdMedicina' 
		AND InvArticuloAtributo.InvArticuloId = '$IdArticulo'
		";
		return $sql;
	}
	/*
		TITULO: QVentasParcial
		PARAMETROS: [$FInicial] Fecha inicial del rango a consultar
					[$FFinal] Fecha final del rango a consutar
					[$IdArticulo] Id del articulo a consultar
		FUNCION: Busca las unidades vendidas y el total en bs de esas unidades
		RETORNO: retorna las unidades vendidas y el tota de esas ventas
	 */
	function QVentasParcial($FInicial,$FFinal,$IdArticulo) {
		$sql = "
			SELECT
			VenVentaDetalle.InvArticuloId, 
			VenVentaDetalle.PrecioBruto,
			VenVentaDetalle.Cantidad,
			(VenVentaDetalle.PrecioBruto * VenVentaDetalle.Cantidad) As SubTotalVenta
			INTO CP_QVentasParcial
			FROM VenVentaDetalle
			INNER JOIN VenVenta ON VenVenta.Id = VenVentaDetalle.VenVentaId 
			WHERE (VenVenta.FechaDocumentoVenta > '$FInicial' AND VenVenta.FechaDocumentoVenta < '$FFinal')
			AND VenVentaDetalle.InvArticuloId = '$IdArticulo'
		";
		return $sql;
	}
	/*
		TITULO: QIntegracionVentasParcial
		PARAMETROS: no aplica
		FUNCION: integra las ventas parciales
		RETORNO: retorna la integracion de las ventas parciales
	 */
	function QIntegracionVentasParcial() {
		$sql = "
			SELECT
			(ROUND(CAST(SUM (CP_QVentasParcial.Cantidad) AS DECIMAL(38,2)),2,0)) as UnidadesVendidas,
			(ROUND(CAST(SUM (CP_QVentasParcial.SubTotalVenta) AS DECIMAL(38,2)),2,0)) as TotalVenta
			FROM CP_QVentasParcial
		";
		return $sql;
	}
	/*
		TITULO: QDevolucionParcial
		PARAMETROS: [$FInicial] Fecha inicial del rango a consultar
					[$FFinal] Fecha final del rango a consutar
					[$IdArticulo] Id del articulo a consultar
		FUNCION: Busca las unidades devueltas y el total en bs de esas unidades
		RETORNO: retorna las unidades devueltas y el tota de esas ventas
	 */
	function QDevolucionParcial($FInicial,$FFinal,$IdArticulo) {
		$sql = "
			SELECT
			VenDevolucionDetalle.InvArticuloId, 
			VenDevolucionDetalle.PrecioBruto,
			VenDevolucionDetalle.Cantidad As Cantidad,
			(VenDevolucionDetalle.PrecioBruto * VenDevolucionDetalle.Cantidad) As SubTotalDevolucion
			INTO CP_QDevolucionParcial
			FROM VenDevolucionDetalle
			INNER JOIN VenDevolucion ON VenDevolucion.Id = VenDevolucionDetalle.VenDevolucionId 
			WHERE (VenDevolucion.FechaDocumento > '$FInicial' AND VenDevolucion.FechaDocumento < '$FFinal')
			AND VenDevolucionDetalle.InvArticuloId = '$IdArticulo'
		";
		return $sql;
	}
	/*
		TITULO: QIntegracionVentasParcial
		PARAMETROS: no aplica
		FUNCION: integra las ventas parciales
		RETORNO: retorna la integracion de las ventas parciales
	 */
	function QIntegracionDevolucionParcial() {
		$sql = "
			SELECT
			(ROUND(CAST(SUM (CP_QDevolucionParcial.Cantidad) AS DECIMAL(38,2)),2,0)) as UnidadesDevueltas,
			(ROUND(CAST(SUM (CP_QDevolucionParcial.SubTotalDevolucion) AS DECIMAL(38,2)),2,0)) as TotalDevolucion
			FROM CP_QDevolucionParcial
		";
		return $sql;
	}
	/*
		TITULO: QProductosPorFallar
		PARAMETROS: Funciona en conjunto con QIntegracionProductosFalla
		FUNCION: Arma una lista de productos vendido con existencia mayor a 0 y menor que la existencia
		RETORNO: Lista de productos mas vendidos con existencia en 0
	 */
	function QProductosPorFallar($Existencia) {
		$sql = "
			SELECT * FROM CP_QIntegracionProductosFalla
			WHERE 
			(CP_QIntegracionProductosFalla.Existencia > 0)
			AND (CP_QIntegracionProductosFalla.Existencia < '$Existencia')
			ORDER BY CP_QIntegracionProductosFalla.TotalUnidadesVendidasCliente DESC
		";
		return $sql;
	}
	/*
		TITULO: QUltimoProveedor
		PARAMETROS: [$IdArticuloQ] Id del articulo
		FUNCION: busca el ultimo proveedor en despachar el articulo
		RETORNO: ultimo proveedor en despachar el articulo
	 */
	function QUltimoProveedor($IdArticulo) {
		$sql = "
			SELECT TOP 1
			ComProveedor.Id,
			GenPersona.Nombre,
			CONVERT(DATE,ComFactura.FechaRegistro) As FechaRegistro,
			CONVERT(DATE,ComFactura.FechaDocumento) As FechaDocumento
			FROM InvArticulo
			INNER JOIN ComFacturaDetalle ON InvArticulo.Id = ComFacturaDetalle.InvArticuloId
			INNER JOIN ComFactura ON ComFactura.Id = ComFacturaDetalle.ComFacturaId
			INNER JOIN ComProveedor ON ComProveedor.Id = ComFactura.ComProveedorId
			INNER JOIN GenPersona ON GenPersona.Id = ComProveedor.GenPersonaId
			WHERE InvArticulo.Id = '$IdArticulo'
			ORDER BY ComFactura.FechaDocumento DESC
		";
		return $sql;
	}
	/*
		TITULO: QIntegracionResumenDeMovimientos
		PARAMETROS: No aplica
		FUNCION: Arma la consulta para cargar la tabla temporal del resumen de los detalles de movimientos
		RETORNO: Un String con la query resuelta
	 */
	function QIntegracionResumenDeMovimientos($IdArticulo,$FInicial,$FFinal) {
		$sql = "
			SELECT 
				InvMovimiento.InvLoteId,
				InvMovimiento.FechaMovimiento,
				InvMovimiento.InvCausaId,
				InvCausa.Descripcion AS Movimiento,
				InvMovimiento.Cantidad
			INTO CP_QResumenDeMovimientos
			FROM InvMovimiento
			INNER JOIN InvCausa ON InvMovimiento.InvCausaId=InvCausa.Id
			WHERE InvMovimiento.InvArticuloId='$IdArticulo'
			AND (CONVERT(DATE,InvMovimiento.FechaMovimiento) >= '$FInicial' AND CONVERT(DATE,InvMovimiento.FechaMovimiento) <= '$FFinal')
			GROUP BY InvMovimiento.InvLoteId,InvMovimiento.FechaMovimiento,InvMovimiento.InvCausaId,InvCausa.Descripcion,InvMovimiento.Cantidad
			ORDER BY InvMovimiento.FechaMovimiento ASC
		";
		return $sql;
	}
	/*
		TITULO: QAgruparDetalleDeMovimientos
		PARAMETROS: No aplica
		FUNCION: Arma la consulta para agrupar por tipo de movimiento y sumar las cantidades
		RETORNO: Un String con la query resuelta
	 */
	function QAgruparDetalleDeMovimientos() {
		$sql = "
			SELECT
			CONVERT(VARCHAR(10), CP_QResumenDeMovimientos.FechaMovimiento, 103) AS FechaMovimiento,
			CP_QResumenDeMovimientos.Movimiento,
			SUM(CP_QResumenDeMovimientos.Cantidad) AS Cantidad
			FROM CP_QResumenDeMovimientos
			GROUP BY CONVERT(VARCHAR(10), CP_QResumenDeMovimientos.FechaMovimiento, 103), CP_QResumenDeMovimientos.Movimiento
			ORDER BY CONVERT(VARCHAR(10), CP_QResumenDeMovimientos.FechaMovimiento, 103) ASC
		";
		return $sql;
	}
	/****************** QUERYS DEL REPORTE DETALLE DE MOVIMIENTOS ******************/
	/*
		TITULO: QUnidadesVendidasCliente2
		PARAMETROS: [$FInicial] Fecha inicial del rango a consultar
					[$FFinal] Fecha final del rango a consutar
		FUNCION: Consulta las Veces vendidas a clientes y las unidades vendidas de un producto
		RETORNO: Tabla con los articulos, las veces vendidas y las unidades vendidas
	 */
	function QUnidadesVendidasCliente2($FInicial,$FFinal) {
		$sql = "
			SELECT
			InvArticulo.Id,
			InvArticulo.CodigoArticulo,
			InvArticulo.Descripcion,
			COUNT(*) AS VecesVendidasCliente,
			SUM(VenFacturaDetalle.Cantidad) AS UnidadesVendidasCliente
			INTO CP_QUnidadesVendidasCliente2
			--TablaTemp
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
		TITULO: QUnidadesDevueltaCliente2
		PARAMETROS: [$FInicial] Fecha inicial del rango a consultar
					[$FFinal] Fecha final del rango a consutar
		FUNCION: Consulta las Veces devuelta a clientes y las unidades devuelta de un producto
		RETORNO: Tabla con los articulos, las veces devuelta y las unidades devuelta
	 */
	function QUnidadesDevueltaCliente2($FInicial,$FFinal) {
		$sql = "
			SELECT
			InvArticulo.Id,
			InvArticulo.CodigoArticulo,
			InvArticulo.Descripcion,
			COUNT(*) AS VecesDevueltaCliente,
			SUM(VenDevolucionDetalle.Cantidad) AS UnidadesDevueltaCliente
			INTO CP_QUnidadesDevueltaCliente2
			--TablaTemp1
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
		TITULO: QUnidadesCompradasProveedor2
		PARAMETROS: [$FInicial] Fecha inicial del rango a consultar
					[$FFinal] Fecha final del rango a consutar
		FUNCION: Consulta las Veces Compradas a proveedores y las unidades compradas
		RETORNO: Tabla con los articulos, las veces compradas y las unidades compradas
	 */
	function QUnidadesCompradasProveedor2($FInicial,$FFinal) {
		$sql = "
			SELECT
			InvArticulo.Id,
			InvArticulo.CodigoArticulo,
			InvArticulo.Descripcion,
			COUNT(*) AS VecesCompradasProveedor,
			SUM(ComFacturaDetalle.CantidadFacturada) AS UnidadesCompradasProveedor,
			CONVERT(DATE,ComFactura.FechaRegistro) AS FechaRegistro
			INTO CP_QUnidadesCompradasProveedor2
			--TablaTemp2
			FROM ComFacturaDetalle
			INNER JOIN InvArticulo ON InvArticulo.Id = ComFacturaDetalle.InvArticuloId
			INNER JOIN ComFactura ON  ComFactura.Id = ComFacturaDetalle.ComFacturaId
			WHERE
			(ComFactura.FechaRegistro > '$FInicial' AND ComFactura.FechaRegistro < '$FFinal')
			GROUP BY InvArticulo.Id,InvArticulo.CodigoArticulo,InvArticulo.Descripcion,FechaRegistro
			ORDER BY UnidadesCompradasProveedor DESC
		";
		return $sql;
	}

	/*
		TITULO: QUnidadesReclamoProveedor2
		PARAMETROS: [$FInicial] Fecha inicial del rango a consultar
					[$FFinal] Fecha final del rango a consutar
		FUNCION: Consulta las Veces reclamo a proveedores y las unidades reclamo
		RETORNO: Tabla con los articulos, las veces reclamo y las unidades reclamo
	 */
	function QUnidadesReclamoProveedor2($FInicial,$FFinal) {
		$sql = "
			SELECT
			InvArticulo.Id,
			InvArticulo.CodigoArticulo,
			InvArticulo.Descripcion,
			COUNT(*) AS VecesReclamoProveedor,
			SUM(ComReclamoDetalle.Cantidad) AS UnidadesReclamoProveedor
			INTO CP_QUnidadesReclamoProveedor2
			--TablaTemp3
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
		TITULO: QIntegracionProductosVendidos2
		PARAMETROS: No aplica
		FUNCION: Integrar la informacion de las consultas
				[QUnidadesVendidasCliente,QUnidadesDevueltaCliente,QUnidadesCompradasProveedor,QUnidadesReclamoProveedor]
		RETORNO: Tabla con datos integrados con valores 0 para campos NULL
	 */
	function QIntegracionProductosVendidos2() {
		$sql = " 
			SELECT
			CP_QUnidadesVendidasCliente2.Id,
			CP_QUnidadesVendidasCliente2.CodigoArticulo,
			CP_QUnidadesVendidasCliente2.Descripcion,
			ISNULL(CP_QUnidadesVendidasCliente2.VecesVendidasCliente,CAST(0 AS INT)) AS VecesVendidasCliente,
			ISNULL(CP_QUnidadesDevueltaCliente2.VecesDevueltaCliente,CAST(0 AS INT)) AS VecesDevueltaCliente,
			ISNULL(CP_QUnidadesVendidasCliente2.UnidadesVendidasCliente,CAST(0 AS INT)) AS UnidadesVendidasCliente,
			ISNULL(CP_QUnidadesDevueltaCliente2.UnidadesDevueltaCliente,CAST(0 AS INT)) AS UnidadesDevueltaCliente,
			ISNULL(CP_QUnidadesCompradasProveedor2.VecesCompradasProveedor,CAST(0 AS INT)) AS VecesCompradasProveedor,
			ISNULL(CP_QUnidadesReclamoProveedor2.VecesReclamoProveedor,CAST(0 AS INT)) AS VecesReclamoProveedor,
			ISNULL(CP_QUnidadesCompradasProveedor2.UnidadesCompradasProveedor,CAST(0 AS INT)) AS UnidadesCompradasProveedor,
			ISNULL(CP_QUnidadesReclamoProveedor2.UnidadesReclamoProveedor,CAST(0 AS INT)) AS UnidadesReclamoProveedor
			INTO CP_QIntegracionProductosVendidos2
			--TablaTemp4
			FROM CP_QUnidadesVendidasCliente2
			LEFT JOIN CP_QUnidadesDevueltaCliente2 ON CP_QUnidadesDevueltaCliente2.Id = CP_QUnidadesVendidasCliente2.Id
			LEFT JOIN CP_QUnidadesCompradasProveedor2 ON CP_QUnidadesCompradasProveedor2.Id = CP_QUnidadesVendidasCliente2.Id
			LEFT JOIN CP_QUnidadesReclamoProveedor2 ON CP_QUnidadesReclamoProveedor2.Id = CP_QUnidadesVendidasCliente2.Id
			ORDER BY UnidadesVendidasCliente DESC
		";
		return $sql;
	}

	/*
		TITULO: QIntegracionProductosFalla2
		PARAMETROS: No aplica
		FUNCION: Integrar la informacion de las consultas
				[QUnidadesVendidasCliente,QUnidadesDevueltaCliente,QUnidadesCompradasProveedor,QUnidadesReclamoProveedor]
		RETORNO: Tabla con datos integrados con valores 0 para campos NULL
	 */
	function QIntegracionProductosFalla2() {
		$sql="
			SELECT
			CP_QIntegracionProductosVendidos2.Id,
			CP_QIntegracionProductosVendidos2.CodigoArticulo,
			CP_QIntegracionProductosVendidos2.Descripcion,
			CP_QIntegracionProductosVendidos2.VecesVendidasCliente,
			CP_QIntegracionProductosVendidos2.VecesDevueltaCliente,
			ISNULL((VecesVendidasCliente-VecesDevueltaCliente),0) AS TotalVecesVendidasCliente,
			CP_QIntegracionProductosVendidos2.UnidadesVendidasCliente,
			CP_QIntegracionProductosVendidos2.UnidadesDevueltaCliente,
			ISNULL((UnidadesVendidasCliente-UnidadesDevueltaCliente),0) AS TotalUnidadesVendidasCliente,
			CP_QIntegracionProductosVendidos2.VecesCompradasProveedor,
			CP_QIntegracionProductosVendidos2.VecesReclamoProveedor,
			ISNULL((VecesCompradasProveedor-VecesReclamoProveedor),0) AS TotalVecesCompradasProveedor,
			CP_QIntegracionProductosVendidos2.UnidadesCompradasProveedor,
			CP_QIntegracionProductosVendidos2.UnidadesReclamoProveedor,
			ISNULL((UnidadesCompradasProveedor-UnidadesReclamoProveedor),0) AS TotalUnidadesCompradasProveedor,
			SUM(InvLoteAlmacen.Existencia) AS Existencia
			INTO CP_QIntegracionProductosFalla2
			--TablaTemp5		
			FROM CP_QIntegracionProductosVendidos2
			INNER JOIN InvLoteAlmacen ON InvLoteAlmacen.InvArticuloId = CP_QIntegracionProductosVendidos2.Id
			WHERE(InvLoteAlmacen.InvAlmacenId = 1 or InvLoteAlmacen.InvAlmacenId = 2)
			GROUP BY
			CP_QIntegracionProductosVendidos2.Id,
			CP_QIntegracionProductosVendidos2.CodigoArticulo,
			CP_QIntegracionProductosVendidos2.Descripcion,
			CP_QIntegracionProductosVendidos2.VecesVendidasCliente,
			CP_QIntegracionProductosVendidos2.VecesDevueltaCliente,
			CP_QIntegracionProductosVendidos2.UnidadesVendidasCliente,
			CP_QIntegracionProductosVendidos2.UnidadesDevueltaCliente,
			CP_QIntegracionProductosVendidos2.VecesCompradasProveedor,
			CP_QIntegracionProductosVendidos2.VecesReclamoProveedor,
			CP_QIntegracionProductosVendidos2.UnidadesCompradasProveedor,
			CP_QIntegracionProductosVendidos2.UnidadesReclamoProveedor
			ORDER BY CP_QIntegracionProductosVendidos2.UnidadesVendidasCliente DESC
		";
		return $sql;
	}

	/*
		TITULO: QArticuloDescLike2
		PARAMETROS: [$DescripLike] Descripcion para buscar
					[$TablaTemp] Variable para indicar si la busqueda sera almacenada en una tabla temporal
		FUNCION: Buscar un articulo segun su descripcion
		RETORNO: Caracteristicas del articulo
	 */
	function QArticuloDescLike2($DescripLike,$IntoTabla) {
		switch($IntoTabla) {
			case '0':
			$sql = "
				SELECT
				InvArticulo.Id,
				InvArticulo.CodigoArticulo,
				InvArticulo.Descripcion,
				InvArticulo.FinConceptoImptoIdCompra AS ConceptoImpuesto
				INTO CP_QArticuloDescLike2
				--TablaTemp6
				FROM InvArticulo
				WHERE InvArticulo.Descripcion LIKE '%$DescripLike%'
				ORDER BY InvArticulo.Descripcion ASC
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
				ORDER BY InvArticulo.Descripcion ASC
			";
			return $sql;
			break;
		}
	}

	/*
		TITULO: QPedidoProductos2
		PARAMETROS: 
		FUNCION: Arma la lista de pedido de productos
		RETORNO: Lista prodcutos para pedir
	 */
	function QPedidoProductos2() {
		$sql="
			SELECT
			CP_QArticuloDescLike2.Id,
			CP_QArticuloDescLike2.CodigoArticulo,
			CP_QArticuloDescLike2.Descripcion,
			CP_QArticuloDescLike2.ConceptoImpuesto,
			ISNULL((CP_QIntegracionProductosFalla2.TotalVecesVendidasCliente),0) AS TotalVecesVendidasCliente,
			ISNULL((CP_QIntegracionProductosFalla2.TotalUnidadesVendidasCliente),0) AS TotalUnidadesVendidasCliente,
			ISNULL((CP_QIntegracionProductosFalla2.TotalVecesCompradasProveedor),0) AS TotalVecesCompradasProveedor,
			ISNULL((CP_QIntegracionProductosFalla2.TotalUnidadesCompradasProveedor),0) AS TotalUnidadesCompradasProveedor
			FROM CP_QArticuloDescLike2
			LEFT JOIN CP_QIntegracionProductosFalla2 ON CP_QIntegracionProductosFalla2.Id = CP_QArticuloDescLike2.Id
			ORDER BY CP_QArticuloDescLike2.Descripcion ASC
		";
		return $sql;
	}
	/****************** QUERYS DEL REPORTE DETALLE DE MOVIMIENTOS ******************/

///////////////////////////////////////////////////////////////////////////
///				QUERYS EN DESARROLLO PARA PRODUCTOS EN CAIDA        ///
//////////////////////////////////////////////////////////////////////////
	/*
		TITULO: QCuentaExistencia
		PARAMETROS: [$IdArticulo] Id del articulo a buscar
		FUNCION: cuenta el total de repeticiones de un articulo en dias en cero
		RETORNO: no aplica
		//
	 */
	function QCuentaExistencia($IdArticulo) {
		$sql = "
			SELECT 
			COUNT(*) AS Cuenta 
			FROM dias_ceros 
			WHERE dias_ceros.id_articulo = '$IdArticulo'
		";
		return $sql;
	}
	/*
		TITULO: QCuentaVenta
		PARAMETROS: $IdArticulo,$FInicial,$FFinal
		FUNCION: cuenta la cantidad de veces que se vendio un producto en una fecha
		RETORNO: no aplica
		//
	 */
	function QCuentaVenta($IdArticulo,$FInicial,$FFinal) {
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
	/*
		TITULO: QFiltradoCaida
		PARAMETROS: $FInicial,$FFinal
		FUNCION: hace un filtrado de la data antes de entrar a dias en cero
		RETORNO: no aplica
		//
	 */
	function QFiltradoCaida($FInicial,$FFinal) {
		$sql = "
			SELECT
			InvArticulo.Id AS IdArticulo,
			InvArticulo.CodigoArticulo AS CodigoArticulo,
			InvArticulo.Descripcion,
			SUM(InvLoteAlmacen.Existencia) AS Existencia,
			InvArticulo.FinConceptoImptoIdCompra AS ConceptoImpuesto,
			COUNT(*) AS VecesVendidasCliente,
			(SELECT 
				COUNT(*) AS VecesCompradasProveedor
				FROM ComFacturaDetalle
				INNER JOIN ComFactura ON  ComFactura.Id = ComFacturaDetalle.ComFacturaId
				WHERE
				(ComFactura.FechaRegistro > '$FInicial' AND ComFactura.FechaRegistro < '$FFinal') 
				AND (InvArticulo.Id = ComFacturaDetalle.InvArticuloId)
			)AS VecesCompradasProveedor
			INTO CP_FiltradoCaida
			FROM VenFacturaDetalle
			INNER JOIN InvArticulo ON InvArticulo.Id = VenFacturaDetalle.InvArticuloId
			INNER JOIN InvLoteAlmacen ON InvArticulo.Id=InvLoteAlmacen.InvArticuloId
			INNER JOIN VenFactura ON VenFactura.Id = VenFacturaDetalle.VenFacturaId
			WHERE InvLoteAlmacen.Existencia > 0
			AND (InvLoteAlmacen.InvAlmacenId = 1 OR InvLoteAlmacen.InvAlmacenId = 2)
			AND (VenFactura.FechaDocumento > '$FInicial' AND VenFactura.FechaDocumento < '$FFinal')
			GROUP BY InvArticulo.Id, InvArticulo.CodigoArticulo, InvArticulo.Descripcion, InvArticulo.FinConceptoImptoIdCompra
			ORDER BY InvArticulo.Id ASC
		";
		return $sql;
	}
	/*
		TITULO: QIntegracionFiltradoCaida
		PARAMETROS: $RangoDias
		FUNCION: Integra y filtra la la tabla CP_FiltradoCaida 
		RETORNO: la lista filtrada
	 */
	function QIntegracionFiltradoCaida($RangoDias) {
		$sql = "
			SELECT * FROM CP_FiltradoCaida 
			WHERE (CP_FiltradoCaida.VecesVendidasCliente >= '$RangoDias') 
			AND (CP_FiltradoCaida.VecesCompradasProveedor = 0)
			ORDER BY CP_FiltradoCaida.Existencia ASC
		";
		return $sql;
	}
	/*
		TITULO: QExistenciaDiasCero
		PARAMETROS: [$IdArticulo] Id del articulo a buscar
		FUNCION: cuenta el total de repeticiones de un articulo en dias en cero
		RETORNO: no aplica
		//
	 */
	function QExistenciaDiasCero($IdArticulo,$FechaCaptura) {
		$sql = "
			SELECT existencia 
			FROM dias_ceros 
			WHERE dias_ceros.id_articulo = '$IdArticulo' 
			AND `fecha_captura` = '$FechaCaptura'
		";
		return $sql;
	}
///////////////////////////////////////////////////////////////////////////
///				QUERYS EN DESARROLLO PARA PRODUCTOS EN CAIDA        ///
//////////////////////////////////////////////////////////////////////////
	/*
		TITULO: QExistenciaActual
		PARAMETROS: no aplica
		FUNCION: busca los articulos en existencia hoy
		RETORNO: no aplica
		//
	 */
	function QExistenciaActual() {
		$sql = "
			SELECT
			InvArticulo.Id AS IdArticulo,
			InvArticulo.CodigoArticulo AS CodigoInterno,
			InvArticulo.Descripcion,
			SUM(InvLoteAlmacen.Existencia) AS Existencia,
			InvArticulo.FinConceptoImptoIdCompra AS ConceptoImpuesto
			FROM InvArticulo
			INNER JOIN InvLoteAlmacen ON InvArticulo.Id=InvLoteAlmacen.InvArticuloId
			WHERE InvLoteAlmacen.Existencia > 0
			AND (InvLoteAlmacen.InvAlmacenId = 1 OR InvLoteAlmacen.InvAlmacenId = 2)
			GROUP BY InvArticulo.Id, InvArticulo.CodigoArticulo, InvArticulo.Descripcion, InvArticulo.FinConceptoImptoIdCompra
			ORDER BY InvArticulo.Id ASC
		";
		return $sql;
	}
	/*
		TITULO: QEtiquetaArticulo
		PARAMETROS: [$IdArticulo] Id del articulo a buscar
		FUNCION: regresa los datos del articulo
		RETORNO: no aplica
		//
	 */
	function QEtiquetaArticulo($IdArticulo) {
		$sql = "
			SELECT * FROM etiquetas
			WHERE etiquetas.id_articulo = '$IdArticulo'
		";
		return $sql;
	}
	/*
		TITULO: QGuardarEtiquetaArticulo
		PARAMETROS: $id_articulo,$codigo_articulo,$descripcion,$condicion,$clasificacion,$estatus,$user,$date
		FUNCION: guarda la informacion en la tabla etiquetas
		RETORNO: no aplica
	 */
	function QGuardarEtiquetaArticulo($id_articulo,$codigo_articulo,$descripcion,$condicion,$clasificacion,$estatus,$user,$date) {
		$sql = "
		INSERT INTO etiquetas
		(id_articulo,codigo_articulo,descripcion,condicion,clasificacion,estatus,user,created_at,updated_at)
		VALUES 
		('$id_articulo','$codigo_articulo','$descripcion','$condicion','$clasificacion','$estatus','$user','$date','$date')
		";
		return $sql;
	}

	/*
		TITULO: QGuardarProductosCaida
		PARAMETROS: Todos los campos
		FUNCION: guardar en la tabla productos en caida
		RETORNO: no aplica
	 */
	function QGuardarProductosCaida($IdArticulo,$CodigoArticulo,$Descripcion,$Precio,$Existencia,$Dia10,$Dia9,$Dia8,$Dia7,$Dia6,$Dia5,$Dia4,$Dia3,$Dia2,$Dia1,$UnidadesVendidas,$DiasRestantes,$fecha_captura,$user,$date) {
		$sql = "
		INSERT INTO productos_caida
		(IdArticulo,CodigoArticulo,Descripcion,Precio,Existencia,Dia10,Dia9,Dia8,Dia7,Dia6,Dia5,Dia4,Dia3,Dia2,Dia1,UnidadesVendidas,DiasRestantes,fecha_captura,user,created_at,updated_at)
		VALUES 
		('$IdArticulo','$CodigoArticulo','$Descripcion','$Precio','$Existencia','$Dia10','$Dia9','$Dia8','$Dia7','$Dia6','$Dia5','$Dia4','$Dia3','$Dia2','$Dia1','$UnidadesVendidas','$DiasRestantes','$fecha_captura','$user','$date','$date')
		";
		return $sql;
	}
	/*
		TITULO: QCapturaCaida
		PARAMETROS: [$FechaCaptura] El dia de hoy
		FUNCION: cuenta el total de registos de productos en caida
		RETORNO: no aplica
	 */

	function QCapturaCaida($FechaCaptura) {
		$sql = "SELECT COUNT(*) AS TotalRegistros
		FROM productos_caida 
		WHERE productos_caida.fecha_captura = '$FechaCaptura'
		";
		return $sql;
	}
	/*
		TITULO: QGuardarCapturaCaida
		PARAMETROS: [$FechaCaptura] El dia de hoy
					[$date] valor para creacion y actualizacion
		FUNCION: crea una conexion con la base de datos cpharma e ingresa datos
		RETORNO: no aplica
	 */

	function QGuardarCapturaCaida($TotalRegistros,$FechaCaptura,$date) {
		$sql = "
		INSERT INTO captura_caida 
		(total_registros,fecha_captura,created_at,updated_at)
		VALUES 
		('$TotalRegistros','$FechaCaptura','$date','$date')
		";
		return $sql;
	}
	/*
		TITULO: QValidarCapturaCaida
		PARAMETROS: [$FechaCaptura] El dia de hoy
		FUNCION: valida que la fecha exista en la tabla captura diaria
		RETORNO: no aplica
	 */
	function QValidarCapturaCaida($FechaCaptura) {
		$sql = "SELECT count(*) AS CuentaCaptura
		FROM captura_caida WHERE fecha_captura = '$FechaCaptura'";
		return $sql;
	}
		/*
		TITULO: QBorrarProductosCaida
		PARAMETROS: [$FechaCaptura] fecha de la captura
		FUNCION: borra los registros de dias en cero de la fecha seleccionada
		RETORNO: no aplica
	 */
	function QBorrarProductosCaida() {
		$sql = "DELETE FROM productos_caida";
		return $sql;
	}
	/*
	TITULO: QArticulosDevaluados
	PARAMETROS: [$FechaBandera] Fecha minima de dias en la tienda
	FUNCION: Armar una tabla temporal con los registros solicitados 
	RETORNO: Un String con la query pertinente
	 */
	function QArticulosDevaluados($FechaBandera) {
		$sql = "
		SELECT 
		InvLote.Id,  
		InvLote.InvArticuloId,
		InvArticulo.CodigoArticulo,
		CONVERT(DATE,InvLoteAlmacen.Auditoria_FechaCreacion) AS FechaLote,  
		InvArticulo.Descripcion,
		InvArticulo.FinConceptoImptoIdCompra AS ConceptoImpuesto
		INTO CP_ArticulosDevaluados
		FROM InvLote
		INNER JOIN InvLoteAlmacen ON InvLote.Id = InvLoteAlmacen.InvLoteId
		INNER JOIN InvArticulo ON InvArticulo.Id = InvLote.InvArticuloId
		WHERE InvLoteAlmacen.Existencia>0 
		AND (InvLoteAlmacen.InvAlmacenId = 1 OR InvLoteAlmacen.InvAlmacenId = 2)
		AND InvLoteAlmacen.Auditoria_FechaCreacion < '$FechaBandera'
		ORDER BY InvLoteAlmacen.Auditoria_FechaCreacion, InvArticulo.Descripcion DESC
		";
		return $sql;
	}

	/*
		TITULO: QFiltrarArticulosDevaluados
		PARAMETROS: No aplica
		FUNCION: Filtar los articulos eliminando los duplicados
		RETORNO: String con la query
	 */
	function QFiltrarArticulosDevaluados() {
		$sql = "
		SELECT 
		CP_ArticulosDevaluados.InvArticuloId,
		CP_ArticulosDevaluados.CodigoArticulo,
		CP_ArticulosDevaluados.Descripcion,
		CP_ArticulosDevaluados.ConceptoImpuesto
		FROM CP_ArticulosDevaluados
		GROUP BY CP_ArticulosDevaluados.InvArticuloId, CP_ArticulosDevaluados.CodigoArticulo, CP_ArticulosDevaluados.Descripcion, CP_ArticulosDevaluados.ConceptoImpuesto
		";
		return $sql;
	}
	/*
		TITULO: 
		PARAMETROS: 
		FUNCION:
		RETORNO:
	 */
	function QModelo() {
		$sql = "
		";
		return $sql;
	}
	
?>


