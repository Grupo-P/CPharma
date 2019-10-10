<?php
/*Querys que son ejecutadas en el manejador de base de datos SQL Server*/
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
?>