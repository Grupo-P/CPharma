@extends('layouts.model')

@section('title')
  Reporte
@endsection

@section('scriptsHead')
  <style>
  * {
    box-sizing: border-box;
  }
  .autocomplete {
    position: relative;
    display: inline-block;
  }
  input {
    border: 1px solid transparent;
    background-color: #f1f1f1;
    border-radius: 5px;
    padding: 10px;
    font-size: 16px;
  }
  input[type=text] {
    background-color: #f1f1f1;
    width: 100%;
  }
  .autocomplete-items {
    position: absolute;
    border: 1px solid #d4d4d4;
    border-bottom: none;
    border-top: none;
    z-index: 99;
    top: 100%;
    left: 0;
    right: 0;
  }
  .autocomplete-items div {
    padding: 10px;
    cursor: pointer;
    background-color: #fff;
    border-bottom: 1px solid #d4d4d4;
  }
  .autocomplete-items div:hover {
    background-color: #e9e9e9;
  }
  .autocomplete-active {
    background-color: DodgerBlue !important;
    color: #ffffff;
  }
  </style>
@endsection

@section('content')
    <?php
        use PhpOffice\PhpSpreadsheet\IOFactory;
        use PhpOffice\PhpSpreadsheet\Spreadsheet;
        use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
        use compras\Configuracion;

        include(app_path().'\functions\config.php');
        include(app_path().'\functions\functions.php');
        include(app_path().'\functions\querys_mysql.php');
        include(app_path().'\functions\querys_sqlserver.php');
    ?>

	<h1 class="h5 text-info">
		<i class="fas fa-file-excel"></i>
		Articulos Excel
	</h1>
	<hr class="row align-items-start col-12">

	<?php
        if (isset($_GET['condicionArticulo']) && isset($_GET['condicionExcel']) && isset($_GET['condicionExistencia'])){

            echo $_GET['condicionArticulo'];
            echo "<br>";
            echo $_GET['condicionExcel'];
            echo "<br>";
            echo $_GET['condicionExistencia'];
            echo "<br>";

            if($_GET['condicionExcel']=="WEB"){
                echo "soy un excel web";

                generarExcelWeb($_GET['condicionArticulo'],$_GET['condicionExistencia']);
            }
            else if($_GET['condicionExcel']=="APP"){
                echo "soy un excel app";

                generarExcelApp($_GET['condicionArticulo'],$_GET['condicionExistencia']);
            }
        }
	?>

    <form autocomplete="off" action="">
        <table style="width:100%;">
          <tr>
            <td align="center">
              Condicion excel:
            </td>
            <td align="right" style="width:20%;">
              <select name="condicionExcel" class="form-control" required="required">
                <option value="">Seleccione...</option>
                <option value="WEB">Excel Web</option>
                <option value="APP">Excel App</option>
              </select>
            </td>

            <td align="center">
              Condicion articulo:
            </td>
            <td align="right" style="width:20%;">
              <select name="condicionArticulo" class="form-control" required="required">
                <option value="">Seleccione...</option>
                <option value="TODOS">Todos</option>
                <option value="DOLARIZADO">Dolarizado</option>
                <option value="NODOLARIZADO">No dolarizado</option>
              </select>
            </td>

            <td align="center">
              Condicion existencia:
            </td>
            <td align="right" style="width:20%;">
                <input type="number" min="0" step="1" name="condicionExistencia" class="form-control" required="required">
            </td>

            <td align="right">
              <input type="submit" value="Generar" class="btn btn-outline-success">
            </td>
          </tr>
        </table>
    </form>
@endsection

<?php
    function generarExcelWeb($condicionArticulo,$condicionExistencia){

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

        /* EXCEL WEB */
            $spreadsheet = new Spreadsheet();
	        $sheet = $spreadsheet->getActiveSheet();
        /* EXCEL WEB */

        /* CPHARMA WEB */
            $SedeConnection = FG_Mi_Ubicacion();
            $conn = FG_Conectar_Smartpharma($SedeConnection);
            $connCPharma = FG_Conectar_CPharma();
            $sql = RQ_Articulos_PaginaWEB($condicionArticulo,$condicionExistencia);
            $result = sqlsrv_query($conn,$sql);
		    $contador = 1;

            $configuracion =  Configuracion::select('valor')->where('variable','URL_externa')->get();
        /* CPHARMA WEB */

        /* EXCEL WEB */
            $sheet->setCellValue('A'.$contador,"ID");
            $sheet->setCellValue('B'.$contador,"Tipo");
            $sheet->setCellValue('C'.$contador,"SKU");
            $sheet->setCellValue('D'.$contador,"Nombre");
            $sheet->setCellValue('E'.$contador,"Publicado");
            $sheet->setCellValue('F'.$contador,"¿Está destacado?");
            $sheet->setCellValue('G'.$contador,"Visibilidad en el catálogo");
            $sheet->setCellValue('H'.$contador,"Descripción corta");
            $sheet->setCellValue('I'.$contador,"Descripción");
            $sheet->setCellValue('J'.$contador,"Día en que empieza el precio rebajado");
            $sheet->setCellValue('K'.$contador,"Día en que termina el precio rebajado");
            $sheet->setCellValue('L'.$contador,"Estado del impuesto");
            $sheet->setCellValue('M'.$contador,"Clase de impuesto");
            $sheet->setCellValue('N'.$contador,"¿En inventario?");
            $sheet->setCellValue('O'.$contador,"Inventario");
            $sheet->setCellValue('P'.$contador,"Cantidad de bajo inventario");
            $sheet->setCellValue('Q'.$contador,"¿Permitir reservas de productos agotados?");
            $sheet->setCellValue('R'.$contador,"¿Vendido individualmente?");
            $sheet->setCellValue('S'.$contador,"Peso (kg)");
            $sheet->setCellValue('T'.$contador,"Longitud (cm)");
            $sheet->setCellValue('U'.$contador,"Ancho (cm)");
            $sheet->setCellValue('V'.$contador,"Altura (cm)");
            $sheet->setCellValue('W'.$contador,"¿Permitir valoraciones de clientes?");
            $sheet->setCellValue('X'.$contador,"Nota de compra");
            $sheet->setCellValue('Y'.$contador,"Precio rebajado");
            $sheet->setCellValue('Z'.$contador,"Precio normal");
            $sheet->setCellValue('AA'.$contador,"Categorías");
            $sheet->setCellValue('AB'.$contador,"Etiquetas");
            $sheet->setCellValue('AC'.$contador,"Clase de envío");
            $sheet->setCellValue('AD'.$contador,"Imágenes");
            $sheet->setCellValue('AE'.$contador,"Límite de descargas");
            $sheet->setCellValue('AF'.$contador,"Días de caducidad de la descarga");
            $sheet->setCellValue('AG'.$contador,"Superior");
            $sheet->setCellValue('AH'.$contador,"Productos agrupados");
            $sheet->setCellValue('AI'.$contador,"Ventas dirigidas");
            $sheet->setCellValue('AJ'.$contador,"Ventas cruzadas");
            $sheet->setCellValue('AK'.$contador,"URL externa");
            $sheet->setCellValue('AL'.$contador,"Texto del botón");
            $sheet->setCellValue('AM'.$contador,"Posición");
        /* EXCEL WEB */

        /* CPHARMA WEB */

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

        	    $Precio = number_format($Precio,2,"," ,"." );
        	    if($Existencia == ""){ $Existencia = '0'; }

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
        /* CPHARMA */

        /*EXCEL*/
            $sheet->setCellValue('A'.$contador,$IdArticulo);
            $sheet->setCellValue('B'.$contador,"simple");
            $sheet->setCellValue('C'.$contador,$CodigoBarra);
            $sheet->setCellValue('D'.$contador,$Descripcion);
            $sheet->setCellValue('E'.$contador,"1");
            $sheet->setCellValue('F'.$contador,"0");
            $sheet->setCellValue('G'.$contador,"visible");
            $sheet->setCellValue('H'.$contador,"");
            $sheet->setCellValue('I'.$contador,$Descripcion);
            $sheet->setCellValue('J'.$contador,"");
            $sheet->setCellValue('K'.$contador,"");
            $sheet->setCellValue('L'.$contador,"taxable");
            $sheet->setCellValue('M'.$contador,"");
            $sheet->setCellValue('N'.$contador,"1");
            $sheet->setCellValue('O'.$contador,$Existencia);
            $sheet->setCellValue('P'.$contador,"24");
            $sheet->setCellValue('Q'.$contador,"0");
            $sheet->setCellValue('R'.$contador,"0");
            $sheet->setCellValue('S'.$contador,"");
            $sheet->setCellValue('T'.$contador,"");
            $sheet->setCellValue('U'.$contador,"");
            $sheet->setCellValue('V'.$contador,"");
            $sheet->setCellValue('W'.$contador,"0");
            $sheet->setCellValue('X'.$contador,"");
            $sheet->setCellValue('Y'.$contador,"");
            $sheet->setCellValue('Z'.$contador,$Precio);
            $sheet->setCellValue('AA'.$contador,$categoria);
            $sheet->setCellValue('AB'.$contador,"");
            $sheet->setCellValue('AC'.$contador,"");
            $sheet->setCellValue('AD'.$contador,"");
            $sheet->setCellValue('AE'.$contador,"");
            $sheet->setCellValue('AF'.$contador,"");
            $sheet->setCellValue('AG'.$contador,"");
            $sheet->setCellValue('AH'.$contador,"");
            $sheet->setCellValue('AI'.$contador,"");
            $sheet->setCellValue('AJ'.$contador,"");
            $sheet->setCellValue('AK'.$contador,$configuracion[0]->valor.$CodigoBarra.".jpg");
            $sheet->setCellValue('AL'.$contador,"");
            $sheet->setCellValue('AM'.$contador,"0");

            $sheet->setCellValue('A5','Hola');
        /*EXCEL*/

        /* CPHARMA */
                $contador++;
            }
            mysqli_close($connCPharma);
            sqlsrv_close($conn);
        /* CPHARMA */

        $nombreDelDocumento = "MenuWEB_CPharma_".date('Ymd_h-i-A').".xlsx";

        /*EXCEL*/
            header('Content-Type: application/vnd.ms-excel');
            header('Content-Disposition: attachment;filename="' . $nombreDelDocumento . '"');

            $writer = IOFactory::createWriter($spreadsheet, 'Xlsx');
            $writer->save('php://output');
        /*EXCEL*/
    }

	/**********************************************************************************/
	/*
		TITULO: RQ_Articulos_PaginaWEB
	 */
	function RQ_Articulos_PaginaWEB($condicionArticulo,$condicionExistencia) {
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
        --Dolarizado (0 NO es dolarizado, Id Articulo SI es dolarizado)
            (ISNULL((SELECT
            InvArticuloAtributo.InvArticuloId
            FROM InvArticuloAtributo
            WHERE InvArticuloAtributo.InvAtributoId =
            (SELECT InvAtributo.Id
            FROM InvAtributo
            WHERE
            InvAtributo.Descripcion = 'Dolarizados'
            OR  InvAtributo.Descripcion = 'Giordany'
            OR  InvAtributo.Descripcion = 'giordany')
            AND InvArticuloAtributo.InvArticuloId = InvArticulo.Id),CAST(0 AS INT))) AS Dolarizado,
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
                AND (InvLoteAlmacen.InvArticuloId = InvArticulo.Id)) AS DECIMAL(38,0)),2,0)) > '$condicionExistencia'
            $condicionArticulo
        --Agrupamientos
            GROUP BY InvArticulo.Id, InvArticulo.CodigoArticulo, InvArticulo.Descripcion, InvArticulo.FinConceptoImptoIdCompra, InvArticulo.InvCategoriaId
        --Ordanamiento
            ORDER BY InvArticulo.Id ASC";
        return $sql;
	}

    /*########################################################################################################################################*/

    function generarExcelApp($condicionArticulo,$condicionExistencia){
        echo " ".$condicionArticulo." ".$condicionExistencia;
    }
?>
