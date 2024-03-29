<?php
	use PhpOffice\PhpSpreadsheet\IOFactory;
	use PhpOffice\PhpSpreadsheet\Spreadsheet;
	use PhpOffice\PhpSpreadsheet\Writer\Csv;
    use compras\TrackImagen;

	include(app_path().'\functions\config.php');
	include(app_path().'\functions\functions.php');
	include(app_path().'\functions\querys_mysql.php');
	include(app_path().'\functions\querys_sqlserver.php');

    (isset($_GET['condicionExcel']))?$condicionExcel = $_GET['condicionExcel']:$condicionExcel = "APP";
    (isset($_GET['condicionArticulo']))?$condicionArticulo = $_GET['condicionArticulo']:$condicionArticulo = "TODOS";
    (isset($_GET['condicionExistencia']))?$condicionExistencia = $_GET['condicionExistencia']:$condicionExistencia = "20";
    (isset($_GET['condicionAtributo']))?$condicionAtributo = $_GET['condicionAtributo']:$condicionAtributo = "TODOS";
    (isset($_GET['condicionUtilidad']))?$condicionUtilidad = $_GET['condicionUtilidad']:$condicionUtilidad = "TODOS";

    //Condicion Articulo
    if($condicionArticulo=="DOLARIZADO"){
        $condicionArticulo = " AND (ISNULL((SELECT
        InvArticuloAtributo.InvArticuloId
        FROM InvArticuloAtributo
        WHERE InvArticuloAtributo.InvAtributoId =
        (SELECT InvAtributo.Id
        FROM InvAtributo
        WHERE
        InvAtributo.Descripcion = 'Dolarizados'
        OR  InvAtributo.Descripcion = 'Giordany'
        OR  InvAtributo.Descripcion = 'giordany')
        AND InvArticuloAtributo.InvArticuloId = InvArticulo.Id),CAST(0 AS INT))) <> 0";
    }
    else if($condicionArticulo=="NODOLARIZADO"){
        $condicionArticulo = " AND (ISNULL((SELECT
        InvArticuloAtributo.InvArticuloId
        FROM InvArticuloAtributo
        WHERE InvArticuloAtributo.InvAtributoId =
        (SELECT InvAtributo.Id
        FROM InvAtributo
        WHERE
        InvAtributo.Descripcion = 'Dolarizados'
        OR  InvAtributo.Descripcion = 'Giordany'
        OR  InvAtributo.Descripcion = 'giordany')
        AND InvArticuloAtributo.InvArticuloId = InvArticulo.Id),CAST(0 AS INT))) = 0";
    }
    else if($condicionArticulo=="TODOS"){
        $condicionArticulo = "";
    }

    //Condicion Atributo
    if($condicionAtributo=="PWEB"){
        $condicionAtributo = " AND ((ISNULL((SELECT
        InvArticuloAtributo.InvArticuloId
        FROM InvArticuloAtributo
        WHERE InvArticuloAtributo.InvAtributoId =
        (SELECT InvAtributo.Id
        FROM InvAtributo
        WHERE
        InvAtributo.Descripcion = 'PaginaWEB '
        OR  InvAtributo.Descripcion = 'PAGINAWEB '
        OR  InvAtributo.Descripcion = 'paginaweb ')
        AND InvArticuloAtributo.InvArticuloId = InvArticulo.Id),CAST(0 AS INT))) <> 0)
        ";
    }
    else if($condicionAtributo=="ExcluirExcel"){
        $condicionAtributo = " AND ((ISNULL((SELECT
        InvArticuloAtributo.InvArticuloId
        FROM InvArticuloAtributo
        WHERE InvArticuloAtributo.InvAtributoId =
        (SELECT InvAtributo.Id
        FROM InvAtributo
        WHERE
        InvAtributo.Descripcion = 'ExcluirExcel')
        AND InvArticuloAtributo.InvArticuloId = InvArticulo.Id),CAST(0 AS INT))) = 0)
        ";
    }
    else if($condicionAtributo=="TODOS"){
        $condicionAtributo = "";
    }

     //Condicion Utilidad
     if($condicionUtilidad!="TODOS"){
        //$utilidad = round(($condicionUtilidad/100),2);
        $utilidad = round(( (100-$condicionUtilidad) /100),2);
        $condicionUtilidad = " AND (ROUND(CAST(1-((ISNULL(ROUND(CAST((SELECT VenCondicionVenta.PorcentajeUtilidad
        FROM VenCondicionVenta
        WHERE VenCondicionVenta.id = (
          SELECT VenCondicionVenta_VenCondicionVentaCategoria.Id
          FROM VenCondicionVenta_VenCondicionVentaCategoria
          WHERE VenCondicionVenta_VenCondicionVentaCategoria.InvCategoriaId = InvArticulo.InvCategoriaId)) AS DECIMAL(38,4)),2,0),CAST(0 AS INT)))/100)AS DECIMAL(38,4)),2,0) <= '$utilidad')";
    }
    else if($condicionUtilidad=="TODOS"){
        $condicionUtilidad = "";
    }

	$spreadsheet = new Spreadsheet();
	$sheet = $spreadsheet->getActiveSheet();

		$SedeConnection = FG_Mi_Ubicacion();
		$conn = FG_Conectar_Smartpharma($SedeConnection);
        $connCPharma = FG_Conectar_CPharma();

        $sql = RQ_Articulos_PaginaWEB($condicionExistencia,$condicionArticulo,$condicionAtributo,$condicionUtilidad);
        $result = sqlsrv_query($conn,$sql);

        $sqlInv = RQ_Articulos_PaginaWEB_Existencia__Invertida($condicionExistencia,$condicionArticulo,$condicionAtributo,$condicionUtilidad);
        $resultInv = sqlsrv_query($conn,$sqlInv);

		$contador = 1;
        $TasaActual = FG_Tasa_Fecha_Venta($connCPharma,date('Y-m-d'));

        $sheet->setCellValue('A'.$contador,"SKU");
        $sheet->setCellValue('B'.$contador,"Precio");
        $sheet->setCellValue('C'.$contador,"Stock");

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

            /*
            $sqlCategorizacion = "
            SELECT
            categorias.nombre as categoria,
            subcategorias.nombre as subcategoria
            FROM categorizacions
            INNER JOIN categorias ON categorias.codigo = codigo_categoria
            INNER JOIN subcategorias ON subcategorias.codigo = codigo_subcategoria
            WHERE id_articulo = '$IdArticulo';
            ";

            $ResultCategorizacion = mysqli_query($connCPharma,$sqlCategorizacion);
            $RowCategorizacion = mysqli_fetch_assoc($ResultCategorizacion);
            $categoria = ($RowCategorizacion['categoria']) ? $RowCategorizacion['categoria'] : "SIN CATEGORIA";
            $subcategoria = ($RowCategorizacion['subcategoria']) ? $RowCategorizacion['subcategoria'] : "SIN SUBCATEGORIA";
            */

            /*PRECIO DOLAR*/
                $PrecioDolar = ($Precio/$TasaActual);
                $PrecioDolar = number_format($PrecioDolar,2,"." ,"," );
            /*PRECIO DOLAR*/

            /*IMAGEN*/
            /*
                $url_app = $CodigoBarra;

                $TrackImagen =
                TrackImagen::orderBy('id','asc')
                ->where('codigo_barra',$CodigoBarra)
                ->get();

                if(!empty($TrackImagen[0]->codigo_barra)) {
                    $url_app = $TrackImagen[0]->url_app;
                }
            */
            /*IMAGEN*/


            /*EXCEL*/
                $sheet->setCellValue('A'.$contador,$CodigoBarra);
                $sheet->setCellValue('B'.$contador,$PrecioDolar);
                $sheet->setCellValue('C'.$contador,$Existencia);
            /*EXCEL*/
            $contador++;
        }

        //Existencia__Invertida
        while($row = sqlsrv_fetch_array($resultInv, SQLSRV_FETCH_ASSOC)) {

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

                $PrecioDolar = ($Precio/$TasaActual);
                $PrecioDolar = number_format($PrecioDolar,2,"." ,"," );

            /*EXCEL*/
                $sheet->setCellValue('A'.$contador,$CodigoBarra);
                $sheet->setCellValue('B'.$contador,$PrecioDolar);
                $sheet->setCellValue('C'.$contador,'0');
            /*EXCEL*/
            $contador++;
        }
        mysqli_close($connCPharma);
        sqlsrv_close($conn);

    $nombreDelDocumento = "Articulos_PedidosYa_Corridas_CPharma_".date('Ymd_h-i-A').".csv";

	/*EXCEL*/
		header('Content-Type: application/vnd.ms-excel');
		header('Content-Disposition: attachment;filename="' . $nombreDelDocumento . '"');

        $writer = new Csv($spreadsheet);
        $writer->setDelimiter(',');
        $writer->setEnclosure('');
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
	function RQ_Articulos_PaginaWEB($condicionExistencia,$condicionArticulo,$condicionAtributo,$condicionUtilidad) {
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
            ORDER BY invlote.M_PrecioTroquelado DESC)AS DECIMAL(38,4)),4,0)) AS TroquelAlmacen1,
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
            (ROUND(CAST((SELECT SUM (InvLoteAlmacen.Existencia) As Existencia
            FROM InvLoteAlmacen
            WHERE(InvLoteAlmacen.InvAlmacenId = 1 OR InvLoteAlmacen.InvAlmacenId = 2)
            AND (InvLoteAlmacen.InvArticuloId = InvArticulo.Id)) AS DECIMAL(38,0)),2,0)) > '$condicionExistencia'
            $condicionArticulo
            $condicionAtributo
            $condicionUtilidad
        --Agrupamientos
            GROUP BY InvArticulo.Id, InvArticulo.CodigoArticulo, InvArticulo.Descripcion, InvArticulo.FinConceptoImptoIdCompra, InvArticulo.InvCategoriaId
        --Ordanamiento
            ORDER BY InvArticulo.Id ASC
		";
		return $sql;
	}

    /**********************************************************************************/
	/*
		TITULO: R1Q_Activacion_Proveedores
		FUNCION: Query que genera la lista de los proveedores con la diferencia en dias desde el ultimo despacho de mercancia.
		RETORNO: Lista de proveedores con diferencia en dias respecto al dia actual
		DESAROLLADO POR: SERGIO COVA
	 */
	function RQ_Articulos_PaginaWEB_Existencia__Invertida($condicionExistencia,$condicionArticulo,$condicionAtributo,$condicionUtilidad) {
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
            ORDER BY invlote.M_PrecioTroquelado DESC)AS DECIMAL(38,4)),4,0)) AS TroquelAlmacen1,
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
            (ROUND(CAST((SELECT SUM (InvLoteAlmacen.Existencia) As Existencia
            FROM InvLoteAlmacen
            WHERE(InvLoteAlmacen.InvAlmacenId = 1 OR InvLoteAlmacen.InvAlmacenId = 2)
            AND (InvLoteAlmacen.InvArticuloId = InvArticulo.Id)) AS DECIMAL(38,0)),2,0)) <= '$condicionExistencia'
            $condicionArticulo
            $condicionAtributo
            $condicionUtilidad
        --Agrupamientos
            GROUP BY InvArticulo.Id, InvArticulo.CodigoArticulo, InvArticulo.Descripcion, InvArticulo.FinConceptoImptoIdCompra, InvArticulo.InvCategoriaId
        --Ordanamiento
            ORDER BY InvArticulo.Id ASC
		";
		return $sql;
	}
?>
