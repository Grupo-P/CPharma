<?php
	use PhpOffice\PhpSpreadsheet\IOFactory;
	use PhpOffice\PhpSpreadsheet\Spreadsheet;
	use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
    use compras\Configuracion;

	include(app_path().'\functions\config.php');
	include(app_path().'\functions\functions.php');
	include(app_path().'\functions\querys_mysql.php');
	include(app_path().'\functions\querys_sqlserver.php');

	$spreadsheet = new Spreadsheet();
	$sheet = $spreadsheet->getActiveSheet();

	/* CPHARMA */
		$SedeConnection = FG_Mi_Ubicacion();
		$conn = FG_Conectar_Smartpharma($SedeConnection);
        $connCPharma = FG_Conectar_CPharma();
		$sql = RQ_Articulos_PaginaWEB();
		$result = sqlsrv_query($conn,$sql);
		$contador = 1;
        $TasaActual = FG_Tasa_Fecha_Venta($connCPharma,date('Y-m-d'));

        $configuracion =  Configuracion::select('valor')->where('variable','URL_externa')->get();

        $sheet->setCellValue('A'.$contador,"sku");
        $sheet->setCellValue('B'.$contador,"name");
        $sheet->setCellValue('C'.$contador,"description");
        $sheet->setCellValue('D'.$contador,"price");
        $sheet->setCellValue('E'.$contador,"stock");
        $sheet->setCellValue('F'.$contador,"taxonomy_1");
        $sheet->setCellValue('G'.$contador,"taxonomy_2");
        $sheet->setCellValue('H'.$contador,"image");

        $contador++;

    		while($row = sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC)) {

                $IdArticulo = $row["IdArticulo"];
        	    $CodigoBarra = $row["CodigoBarra"];
        	    $Descripcion = FG_Limpiar_Texto($row["Descripcion"]);
        	    $Existencia = $row["Existencia"];
        	    $ExistenciaAlmacen1 = $row["ExistenciaAlmacen1"];
        	    $ExistenciaAlmacen2 = $row["ExistenciaAlmacen2"];
        	    $IsTroquelado = $row["Troquelado"];
        	    $IsIVA = $row["Impuesto"];
        	    $UtilidadArticulo = $row["UtilidadArticulo"];
        	    $UtilidadCategoria = $row["UtilidadCategoria"];
        	    $TroquelAlmacen1 = $row["TroquelAlmacen1"];
        	    $PrecioCompraBrutoAlmacen1 = $row["PrecioCompraBrutoAlmacen1"];
        	    $TroquelAlmacen2 = $row["TroquelAlmacen2"];
        	    $PrecioCompraBrutoAlmacen2 = $row["PrecioCompraBrutoAlmacen2"];
        	    $PrecioCompraBruto = $row["PrecioCompraBruto"];
        	    $CondicionExistencia = 'CON_EXISTENCIA';

        	    $Precio = FG_Calculo_Precio_Alfa($Existencia,$ExistenciaAlmacen1,$ExistenciaAlmacen2,$IsTroquelado,$UtilidadArticulo,$UtilidadCategoria,$TroquelAlmacen1,$PrecioCompraBrutoAlmacen1,$TroquelAlmacen2,
            		$PrecioCompraBrutoAlmacen2,$PrecioCompraBruto,$IsIVA,$CondicionExistencia);

        	    //$Precio = number_format($Precio,2,"," ,"." );
        	    if($Existencia == ""){ $Existencia = '0'; }

                $sqlCategorizacion = "
                SELECT
                if(categorias.codigo_app is not null,categorias.codigo_app ,categorias.nombre) as categoria,
                if(subcategorias.codigo_app is not null,subcategorias.codigo_app ,subcategorias.nombre) as subcategoria
                FROM categorizacions
                INNER JOIN categorias ON categorias.codigo = codigo_categoria
                INNER JOIN subcategorias ON subcategorias.codigo = codigo_subcategoria
                WHERE id_articulo = '$IdArticulo';
               ";

                $ResultCategorizacion = mysqli_query($connCPharma,$sqlCategorizacion);
                $RowCategorizacion = mysqli_fetch_assoc($ResultCategorizacion);
                $categoria = ($RowCategorizacion['categoria']) ? $RowCategorizacion['categoria'] : "SIN CATEGORIA";
                $subcategoria = ($RowCategorizacion['subcategoria']) ? $RowCategorizacion['subcategoria'] : "SIN SUBCATEGORIA";
 	/* CPHARMA */

            /*PRECIO DOLAR*/
                $PrecioDolar = ($Precio/$TasaActual);
                $PrecioDolar = number_format($PrecioDolar,2,"," ,"." );
            /*PRECIO DOLAR*/

            /*EXCEL*/
                $sheet->setCellValue('A'.$contador,$CodigoBarra);
                $sheet->setCellValue('B'.$contador,$Descripcion);
                $sheet->setCellValue('C'.$contador,$Descripcion);
                $sheet->setCellValue('D'.$contador,$PrecioDolar);
                $sheet->setCellValue('E'.$contador,$Existencia);
                $sheet->setCellValue('F'.$contador,$categoria);
                $sheet->setCellValue('G'.$contador,$subcategoria);
                $sheet->setCellValue('H'.$contador,$configuracion[0]->valor.$CodigoBarra.".jpg");
            /*EXCEL*/

	/* CPHARMA */
                $contador++;
          	}
        mysqli_close($connCPharma);
        sqlsrv_close($conn);
    /* CPHARMA */

    $nombreDelDocumento = "APP_CPharma_".date('Ymd_h-i-A').".xlsx";

	/*EXCEL*/
		header('Content-Type: application/vnd.ms-excel');
		header('Content-Disposition: attachment;filename="' . $nombreDelDocumento . '"');

		$writer = IOFactory::createWriter($spreadsheet, 'Xlsx');
		$writer->save('php://output');
	/*EXCEL*/
?>

<?php
	/**********************************************************************************/
	/*
		TITULO: R1Q_Activacion_Proveedores
		FUNCION: Query que genera la lista de los proveedores con la diferencia en dias desde el ultimo despacho de mercancia.
		RETORNO: Lista de proveedores con diferencia en dias respecto al dia actual
		DESAROLLADO POR: SERGIO COVA
	 */
	function RQ_Articulos_PaginaWEB() {
		$sql = "
			SELECT
--Id Articulo
    InvArticulo.Id AS IdArticulo,
--Codigo de Barra
    (SELECT CodigoBarra
    FROM InvCodigoBarra
    WHERE InvCodigoBarra.InvArticuloId = InvArticulo.Id
    AND InvCodigoBarra.EsPrincipal = 1) AS CodigoBarra,
--Descripcion
    InvArticulo.Descripcion,
--Tipo Producto (0 Miscelaneos, Id Articulo Medicinas)
    (ISNULL((SELECT
    InvArticuloAtributo.InvArticuloId
    FROM InvArticuloAtributo
    WHERE InvArticuloAtributo.InvAtributoId =
    (SELECT InvAtributo.Id
    FROM InvAtributo
    WHERE
    InvAtributo.Descripcion = 'Medicina')
    AND InvArticuloAtributo.InvArticuloId = InvArticulo.Id),CAST(0 AS INT))) AS Tipo,
--Impuesto (1 SI aplica impuesto, 0 NO aplica impuesto)
    (ISNULL(InvArticulo.FinConceptoImptoIdCompra,CAST(0 AS INT))) AS Impuesto,
--Troquelado (0 NO es Troquelado, Id Articulo SI es Troquelado)
    (ISNULL((SELECT
    InvArticuloAtributo.InvArticuloId
    FROM InvArticuloAtributo
    WHERE InvArticuloAtributo.InvAtributoId =
    (SELECT InvAtributo.Id
    FROM InvAtributo
    WHERE
    InvAtributo.Descripcion = 'Troquelados'
    OR  InvAtributo.Descripcion = 'troquelados')
    AND InvArticuloAtributo.InvArticuloId = InvArticulo.Id),CAST(0 AS INT))) AS Troquelado,
--UtilidadArticulo (Utilidad del articulo, Utilidad es 1.00 NO considerar la utilidad para el calculo de precio)
    ROUND(CAST(1-((ISNULL(ROUND(CAST((SELECT VenCondicionVenta.PorcentajeUtilidad
        FROM VenCondicionVenta
        WHERE VenCondicionVenta.Id = (
        SELECT VenCondicionVenta_VenCondicionVentaArticulo.Id
        FROM VenCondicionVenta_VenCondicionVentaArticulo
        WHERE VenCondicionVenta_VenCondicionVentaArticulo.InvArticuloId = InvArticulo.Id)) AS DECIMAL(38,4)),2,0),CAST(0 AS INT)))/100)AS DECIMAL(38,4)),2,0) AS UtilidadArticulo,
--UtilidadCategoria (Utilidad de la categoria, Utilidad es 1.00 NO considerar la utilidad para el calculo de precio)
    ROUND(CAST(1-((ISNULL(ROUND(CAST((SELECT VenCondicionVenta.PorcentajeUtilidad
  FROM VenCondicionVenta
  WHERE VenCondicionVenta.id = (
    SELECT VenCondicionVenta_VenCondicionVentaCategoria.Id
    FROM VenCondicionVenta_VenCondicionVentaCategoria
    WHERE VenCondicionVenta_VenCondicionVentaCategoria.InvCategoriaId = InvArticulo.InvCategoriaId)) AS DECIMAL(38,4)),2,0),CAST(0 AS INT)))/100)AS DECIMAL(38,4)),2,0) AS UtilidadCategoria,
--Precio Troquel Almacen 1
    (ROUND(CAST((SELECT TOP 1
    InvLote.M_PrecioTroquelado
    FROM InvLoteAlmacen
    INNER JOIN InvLote ON InvLote.Id = InvLoteAlmacen.InvLoteId
    WHERE(InvLoteAlmacen.InvAlmacenId = '1')
    AND (InvLoteAlmacen.InvArticuloId = InvArticulo.Id)
    AND (InvLoteAlmacen.Existencia>0)
    ORDER BY invlote.M_PrecioTroquelado DESC)AS DECIMAL(38,2)),2,0)) AS TroquelAlmacen1,
--Precio Compra Bruto Almacen 1
    (ROUND(CAST((SELECT TOP 1
    InvLote.M_PrecioCompraBruto
    FROM InvLoteAlmacen
    INNER JOIN InvLote ON InvLote.Id = InvLoteAlmacen.InvLoteId
    WHERE (InvLoteAlmacen.InvArticuloId = InvArticulo.Id)
    AND (InvLoteAlmacen.Existencia>0)
  AND (InvLoteAlmacen.InvAlmacenId = '1')
    ORDER BY invlote.M_PrecioCompraBruto DESC)AS DECIMAL(38,2)),2,0)) AS PrecioCompraBrutoAlmacen1,
--Precio Troquel Almacen 2
    (ROUND(CAST((SELECT TOP 1
    InvLote.M_PrecioTroquelado
    FROM InvLoteAlmacen
    INNER JOIN InvLote ON InvLote.Id = InvLoteAlmacen.InvLoteId
    WHERE(InvLoteAlmacen.InvAlmacenId = '2')
    AND (InvLoteAlmacen.InvArticuloId = InvArticulo.Id)
    AND (InvLoteAlmacen.Existencia>0)
    ORDER BY invlote.M_PrecioTroquelado DESC)AS DECIMAL(38,2)),2,0)) AS TroquelAlmacen2,
--Precio Compra Bruto Almacen 2
    (ROUND(CAST((SELECT TOP 1
    InvLote.M_PrecioCompraBruto
    FROM InvLoteAlmacen
    INNER JOIN InvLote ON InvLote.Id = InvLoteAlmacen.InvLoteId
    WHERE (InvLoteAlmacen.InvArticuloId = InvArticulo.Id)
    AND (InvLoteAlmacen.Existencia>0)
  AND (InvLoteAlmacen.InvAlmacenId = '2')
    ORDER BY invlote.M_PrecioCompraBruto DESC)AS DECIMAL(38,2)),2,0)) AS PrecioCompraBrutoAlmacen2,
--Precio Compra Bruto
    (ROUND(CAST((SELECT TOP 1
    InvLote.M_PrecioCompraBruto
    FROM InvLoteAlmacen
    INNER JOIN InvLote ON InvLote.Id = InvLoteAlmacen.InvLoteId
    WHERE (InvLoteAlmacen.InvArticuloId = InvArticulo.Id)
    AND (InvLoteAlmacen.Existencia>0)
    ORDER BY invlote.M_PrecioCompraBruto DESC)AS DECIMAL(38,2)),2,0)) AS PrecioCompraBruto,
--Existencia (Segun el almacen del filtro)
    (ROUND(CAST((SELECT SUM (InvLoteAlmacen.Existencia) As Existencia
                FROM InvLoteAlmacen
                WHERE(InvLoteAlmacen.InvAlmacenId = 1 OR InvLoteAlmacen.InvAlmacenId = 2)
                AND (InvLoteAlmacen.InvArticuloId = InvArticulo.Id)) AS DECIMAL(38,0)),2,0))  AS Existencia,
--ExistenciaAlmacen1 (Segun el almacen del filtro)
    (ROUND(CAST((SELECT SUM (InvLoteAlmacen.Existencia) As Existencia
                FROM InvLoteAlmacen
                WHERE(InvLoteAlmacen.InvAlmacenId = 1)
                AND (InvLoteAlmacen.InvArticuloId = InvArticulo.Id)) AS DECIMAL(38,0)),2,0))  AS ExistenciaAlmacen1,
--ExistenciaAlmacen2 (Segun el almacen del filtro)
    (ROUND(CAST((SELECT SUM (InvLoteAlmacen.Existencia) As Existencia
                FROM InvLoteAlmacen
                WHERE(InvLoteAlmacen.InvAlmacenId = 2)
                AND (InvLoteAlmacen.InvArticuloId = InvArticulo.Id)) AS DECIMAL(38,0)),2,0))  AS ExistenciaAlmacen2,
--PaginaWEB  (0 NO es PaginaWEB , Id Articulo SI es PaginaWEB )
    (ISNULL((SELECT
    InvArticuloAtributo.InvArticuloId
    FROM InvArticuloAtributo
    WHERE InvArticuloAtributo.InvAtributoId =
    (SELECT InvAtributo.Id
    FROM InvAtributo
    WHERE
    InvAtributo.Descripcion = 'PaginaWEB '
    OR  InvAtributo.Descripcion = 'PAGINAWEB '
    OR  InvAtributo.Descripcion = 'paginaweb ')
    AND InvArticuloAtributo.InvArticuloId = InvArticulo.Id),CAST(0 AS INT))) AS PaginaWEB
--Tabla principal
    FROM InvArticulo
--Joins
    LEFT JOIN InvLoteAlmacen ON InvLoteAlmacen.InvArticuloId = InvArticulo.Id
    LEFT JOIN InvArticuloAtributo ON InvArticuloAtributo.InvArticuloId = InvArticulo.Id
    LEFT JOIN InvAtributo ON InvAtributo.Id = InvArticuloAtributo.InvAtributoId
--Condicionales
    WHERE
	((ISNULL((SELECT
    InvArticuloAtributo.InvArticuloId
    FROM InvArticuloAtributo
    WHERE InvArticuloAtributo.InvAtributoId =
    (SELECT InvAtributo.Id
    FROM InvAtributo
    WHERE
    InvAtributo.Descripcion = 'PaginaWEB '
    OR  InvAtributo.Descripcion = 'PAGINAWEB '
    OR  InvAtributo.Descripcion = 'paginaweb ')
    AND InvArticuloAtributo.InvArticuloId = InvArticulo.Id),CAST(0 AS INT)))) <> 0
    AND (ROUND(CAST((SELECT SUM (InvLoteAlmacen.Existencia) As Existencia
    FROM InvLoteAlmacen
    WHERE(InvLoteAlmacen.InvAlmacenId = 1 OR InvLoteAlmacen.InvAlmacenId = 2)
    AND (InvLoteAlmacen.InvArticuloId = InvArticulo.Id)) AS DECIMAL(38,0)),2,0)) > 20
--Agrupamientos
    GROUP BY InvArticulo.Id, InvArticulo.CodigoArticulo, InvArticulo.Descripcion, InvArticulo.FinConceptoImptoIdCompra, InvArticulo.InvCategoriaId
--Ordanamiento
    ORDER BY InvArticulo.Id ASC
		";
		return $sql;
	}
?>
