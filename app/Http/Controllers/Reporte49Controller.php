<?php

namespace compras\Http\Controllers;

use Illuminate\Http\Request;
use DateTime;
use DB;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class Reporte49Controller extends Controller
{
    /**
     * Create a new controller instance with auth.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function reporte49()
    {           
        $arrayArticulos = array();
        $InicioCarga = new DateTime("now");

        include(app_path().'\functions\config.php');
        include(app_path().'\functions\functions.php');
        include(app_path().'\functions\querys_mysql.php');
        include(app_path().'\functions\querys_sqlserver.php');
                         
        $RangoDias = 15;
        $LimiteDiasCero = 10;        
        $Hoy = date("Y-m-d",strtotime(date("2022-04-04")."+ 1 days"));
        //$Hoy = date("Y-m-d",strtotime(date("Y-m-d")."+ 1 days"));
        $FInicialSegundoRango = date("Y-m-d",strtotime($Hoy."-$RangoDias days"));
        $FInicialPrimerRango = date("Y-m-d",strtotime($FInicialSegundoRango."-$RangoDias days"));
        
        $SedeConnection = FG_Mi_Ubicacion();
        $connCPharma = FG_Conectar_CPharma();
        $conn = FG_Conectar_Smartpharma($SedeConnection);
        $sql = $this->Articulos_Existencia();
        $result = sqlsrv_query($conn,$sql);
                
        while($row = sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC)) {

            $IdArticulo = $row["IdArticulo"];
            $CodigoInterno = $row["CodigoInterno"];
            $CodigoBarra = $row["CodigoBarra"];
            $Descripcion = $row["Descripcion"];
            $Existencia = intval($row["Existencia"]);
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
            $Dolarizado = $row["Dolarizado"];
            $CondicionExistencia = 'CON_EXISTENCIA';
            $UltimaVenta = ($row["UltimaVenta"])?$row["UltimaVenta"]->format('d-m-Y'):'N/A';
            $UltimaCompra = ($row["UltimaCompra"])?$row["UltimaCompra"]->format('d-m-Y'):'N/A';

            $Gravado = FG_Producto_Gravado($IsIVA);
            $Dolarizado = FG_Producto_Dolarizado($Dolarizado);
            //$TasaActual = FG_Tasa_Fecha($connCPharma,date('Y-m-d'));
            $Precio = FG_Calculo_Precio_Alfa($Existencia,$ExistenciaAlmacen1,$ExistenciaAlmacen2,$IsTroquelado,$UtilidadArticulo,$UtilidadCategoria,$TroquelAlmacen1,$PrecioCompraBrutoAlmacen1,$TroquelAlmacen2,
            $PrecioCompraBrutoAlmacen2,$PrecioCompraBruto,$IsIVA,$CondicionExistencia);
                                                                                           
            $arraySegundoRango = $this->getEvaluarDiasCero($conn,$IdArticulo,$Existencia,$FInicialSegundoRango,$Hoy,$LimiteDiasCero);
            $arrayPrimerRango = $this->getEvaluarDiasCero($conn,$IdArticulo,$Existencia,$FInicialPrimerRango,$FInicialSegundoRango,$LimiteDiasCero);
                                                
            $Variacion = $this->getVariacion($arrayPrimerRango['DiasRestantesQuiebre'],$arraySegundoRango['DiasRestantesQuiebre']);
            $Status = $this->getStatus($arrayPrimerRango['DiasRestantesQuiebre'],$arraySegundoRango['DiasRestantesQuiebre']);
            $Comportamiento = $this->getComportamiento($Variacion,$arrayPrimerRango['DiasRestantesQuiebre'],$arraySegundoRango['DiasRestantesQuiebre']);

            $articulosDetalle =  array (
                "IdArticulo" => $IdArticulo,
                "CodigoInterno" => $CodigoInterno,
                "CodigoBarra" => $CodigoBarra,
                "Descripcion" => $Descripcion,
                "Existencia" => $Existencia,
                "Precio" => $Precio,
                "UltimaVenta" => $UltimaVenta,
                "UltimaCompra" => $UltimaCompra,
                "DiasRestantesSegundoRango" => $arraySegundoRango['DiasRestantesQuiebre'],
                "DiasRestantesPrimerRango" => $arrayPrimerRango['DiasRestantesQuiebre'],
                "Variacion" => $Variacion,
                "Status" => $Status,
                "Comportamiento" => $Comportamiento,
            );
    
            array_push($arrayArticulos,$articulosDetalle);
        }                        

        $arrayArticulos =  $this->orderArrayByRow($arrayArticulos,'DiasRestantesSegundoRango');

        $FinCarga = new DateTime("now");
        $IntervalCarga = $InicioCarga->diff($FinCarga);
        $Tiempo = $IntervalCarga->format("%Y-%M-%D %H:%I:%S");

        
		$Hoy = date("Y-m-d",strtotime($Hoy."- 1 days"));
		$FInicialSegundoRangoMenos = date("Y-m-d",strtotime($FInicialSegundoRango."- 1 days"));

        $arrayGlobal = array(
            "SedeConnection" => $SedeConnection,
            "Hoy" => $Hoy,
            "FInicialSegundoRango" => $FInicialSegundoRango,
            "FInicialSegundoRangoMenos" => $FInicialSegundoRangoMenos,
            "FInicialPrimerRango" => $FInicialPrimerRango,
            "Tiempo" => $Tiempo,
        );
                      
        if(isset($_GET['generarExcel'])){
            if($_GET['generarExcel']=="SI"){
                $this->generarExcel($arrayArticulos);                
            }
        }else{
            return view('pages.reporte.reporte49', compact('arrayGlobal', 'arrayArticulos'));
        }        
    } 
        
    public function Articulos_Existencia() {
        $sql = "SELECT TOP 10
        --Id Articulo
            InvArticulo.Id AS IdArticulo,        
        --Codigo Interno
            InvArticulo.CodigoArticulo AS CodigoInterno,
        --Codigo de Barra
            (SELECT CodigoBarra
            FROM InvCodigoBarra
            WHERE InvCodigoBarra.InvArticuloId = InvArticulo.Id
            AND InvCodigoBarra.EsPrincipal = 1) AS CodigoBarra,
        --Descripcion
            InvArticulo.Descripcion,
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
        -- Ultima Venta (Fecha)
            (SELECT TOP 1
            CONVERT(DATE,VenFactura.FechaDocumento)
            FROM VenFactura
            INNER JOIN VenFacturaDetalle ON VenFacturaDetalle.VenFacturaId = VenFactura.Id
            WHERE VenFacturaDetalle.InvArticuloId = InvArticulo.Id
            ORDER BY FechaDocumento DESC) AS UltimaVenta,
        -- Ultima Compra (Fecha de ultima compra)
            (SELECT TOP 1
            CONVERT(DATE,ComFactura.FechaRegistro)
            FROM ComFacturaDetalle
            INNER JOIN ComFactura ON ComFactura.Id = ComFacturaDetalle.ComFacturaId
            INNER JOIN ComProveedor ON ComProveedor.Id = ComFactura.ComProveedorId
            WHERE ComFacturaDetalle.InvArticuloId = InvArticulo.Id
            ORDER BY ComFactura.FechaRegistro DESC) AS  UltimaCompra
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
            AND (InvLoteAlmacen.InvArticuloId = InvArticulo.Id)) AS DECIMAL(38,0)),2,0)) > 0
            --AND InvArticulo.Id = '30'
        --Agrupamientos
            GROUP BY InvArticulo.Id, InvArticulo.CodigoArticulo, InvArticulo.Descripcion, InvArticulo.FinConceptoImptoIdCompra, InvArticulo.InvCategoriaId
        --Ordanamiento
            ORDER BY InvArticulo.Id ASC   
        ";
        return $sql;
    }

    public function Venta_Articulo($IdArticulo,$FInicial,$FFinal){
        $sql ="SELECT
            -- Id Articulo
            VenFacturaDetalle.InvArticuloId,
            --Unidades Vendidas (En Rango)
            (ROUND(CAST(SUM(VenFacturaDetalle.Cantidad) AS DECIMAL(38,0)),2,0)) as UnidadesVendidas,
            --Unidades Devueltas (En Rango)
            ISNULL((SELECT
            (ROUND(CAST(SUM(VenDevolucionDetalle.Cantidad) AS DECIMAL(38,0)),2,0))
            FROM VenDevolucionDetalle
            INNER JOIN VenDevolucion ON VenDevolucion.Id = VenDevolucionDetalle.VenDevolucionId
            WHERE VenDevolucionDetalle.InvArticuloId = VenFacturaDetalle.InvArticuloId
            AND(VenDevolucion.FechaDocumento > '$FInicial' AND VenDevolucion.FechaDocumento < '$FFinal')
            GROUP BY VenDevolucionDetalle.InvArticuloId
            ),CAST(0 AS INT)) AS UnidadesDevueltas,
            --Total Unidades Vendidas (En Rango)
            (((ROUND(CAST(SUM(VenFacturaDetalle.Cantidad) AS DECIMAL(38,0)),2,0)))
            -
            (ISNULL((SELECT
            (ROUND(CAST(SUM(VenDevolucionDetalle.Cantidad) AS DECIMAL(38,0)),2,0))
            FROM VenDevolucionDetalle
            INNER JOIN VenDevolucion ON VenDevolucion.Id = VenDevolucionDetalle.VenDevolucionId
            WHERE VenDevolucionDetalle.InvArticuloId = VenFacturaDetalle.InvArticuloId
            AND(VenDevolucion.FechaDocumento > '$FInicial' AND VenDevolucion.FechaDocumento < '$FFinal')
            GROUP BY VenDevolucionDetalle.InvArticuloId
            ),CAST(0 AS INT)))) AS TotalUnidadesVendidas 
            --Tabla Principal
            FROM VenFacturaDetalle
            --Joins
            INNER JOIN VenFactura ON VenFactura.Id = VenFacturaDetalle.VenFacturaId
            --Condicionales
            WHERE
            (VenFactura.FechaDocumento > '$FInicial' AND VenFactura.FechaDocumento < '$FFinal')
            AND VenFacturaDetalle.InvArticuloId = '$IdArticulo'
            --Agrupamientos
            GROUP BY VenFacturaDetalle.InvArticuloId    
        ";
        return $sql;
    }

    public function getEvaluarDiasCero($conn,$IdArticulo,$Existencia,$FInicial,$FFinal,$LimiteDiasCero){
    
        $UnidadesVendidas = $VentaDiariaQuiebre = $DiasRestantesQuiebre = "N/D";

        $RangoDiasQuiebre = DB::select("
            SELECT 
            COUNT(*) AS Cuenta 
            FROM dias_ceros 
            WHERE dias_ceros.id_articulo = '$IdArticulo' AND (fecha_captura >= '$FInicial' AND `fecha_captura` < '$FFinal')
        ");         
                
        if($RangoDiasQuiebre[0]->Cuenta >= $LimiteDiasCero ){
            $sql = $this->Venta_Articulo($IdArticulo,$FInicial,$FFinal);            
            $result = sqlsrv_query($conn,$sql);
            $UnidadesVendidas = sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC);
            
            if($UnidadesVendidas!=NULL){
                $UnidadesVendidas['UnidadesVendidas'] = intval($UnidadesVendidas['UnidadesVendidas']);
                $UnidadesVendidas['UnidadesDevueltas'] = intval($UnidadesVendidas['UnidadesDevueltas']);
                $UnidadesVendidas['TotalUnidadesVendidas'] = intval($UnidadesVendidas['TotalUnidadesVendidas']);

                $VentaDiariaQuiebre = FG_Venta_Diaria($UnidadesVendidas['TotalUnidadesVendidas'],$RangoDiasQuiebre[0]->Cuenta);
                $DiasRestantesQuiebre = FG_Dias_Restantes($Existencia,$VentaDiariaQuiebre);               
            }else{
                $UnidadesVendidas = $VentaDiariaQuiebre = $DiasRestantesQuiebre = 0.00;
            }                
        }                
        else{
            $UnidadesVendidas = $VentaDiariaQuiebre = $DiasRestantesQuiebre = "N/D";
        }

        $result = array(
            "UnidadesVendidas" => $UnidadesVendidas,
            "VentaDiariaQuiebre" => $VentaDiariaQuiebre,
            "DiasRestantesQuiebre" => $DiasRestantesQuiebre,
        );

        return $result;
    }

    public function orderArrayByRow($arrayToOrder, $rowName){
        // Ordenar el arrayToOrder por rowName
        $marks = array();
        foreach ($arrayToOrder as $key => $row)
        {                        
            $marks[$key] = $row[$rowName];            
        }

        array_multisort($marks, SORT_DESC, $arrayToOrder);
        
        return $arrayToOrder;
    }

    public function getVariacion($PrimerRango,$SegundoRango){
        
        if( ($PrimerRango==="N/D") || ($SegundoRango==="N/D") ){
            return "N/D";
        }else if($PrimerRango!=0){
            $variacion = (($PrimerRango - $SegundoRango) / $PrimerRango) * 100;
            return round($variacion,2,PHP_ROUND_HALF_UP);
        }else if($PrimerRango==0){
            return "N/D 0";
        }
    }

    public function getStatus($PrimerRango,$SegundoRango){
                        
        if($PrimerRango==="N/D" && $SegundoRango==="N/D"){
            return "N/D";
        }
        else if( ($SegundoRango==0 || $SegundoRango==="N/D") && ($PrimerRango>0) ){
            return "INDETERMINABLE";
        }        
        else if($SegundoRango>0 && $SegundoRango<20){
            return 'CRITICO';
        }
        else if($SegundoRango>=20 && $SegundoRango<45){
            return 'BIEN';
        }
        else if($SegundoRango>=45){
            return 'EXCEDIDO';
        }                                     
    }

    public function getComportamiento($Variacion,$PrimerRango,$SegundoRango){
    
        if( ($PrimerRango==="N/D") && ($SegundoRango==="N/D") ){
            return "N/D";
        }
        else if( ($PrimerRango!=="N/D") && ($SegundoRango!=="N/D") ){

            if( ($PrimerRango==0) && ($SegundoRango==0) ){
                return "PELIGRO";
            }
            else  if( ($PrimerRango>$SegundoRango) && ( abs($Variacion)>10) ){
                return "CRECIO";
            }
            else if( ($PrimerRango<$SegundoRango) && (abs($Variacion)>10) ){
                return "DECRECIO";
            }
            else if( ($PrimerRango>0) && ($SegundoRango==0) ){
                return "CAYO";
            }
            else if( ($Variacion>-10) && ($Variacion<10) && ($Variacion!=0) ){
                return "ESTABLE";
            } 
            else if( $Variacion==0 ){
                return "INTACTO";
            }        
        }
        else if( ($PrimerRango==="N/D") && ($SegundoRango>=0) && ($SegundoRango!=="N/D") ){
            return "LLEGANDO";
        }
        else if( ($SegundoRango==="N/D") && ($PrimerRango>=0) && ($PrimerRango!=="N/D") ){
            return "LLEGANDO";
        }
    }

    public function generarExcel($arrayArticulos){
        $spreadsheet = new Spreadsheet();
	    $sheet = $spreadsheet->getActiveSheet();

        $contador = 1;
        $sheet->setCellValue('A'.$contador,"#");
        $sheet->setCellValue('B'.$contador,"Codigo Interno");
        $sheet->setCellValue('C'.$contador,"Codigo Barra");
        $sheet->setCellValue('D'.$contador,"Descripcion");
        $sheet->setCellValue('E'.$contador,"Existencia");
        $sheet->setCellValue('F'.$contador,"Ultima Venta");        
        $sheet->setCellValue('G'.$contador,"Ultima Compra");
        $sheet->setCellValue('H'.$contador,"Dias Restantes / Rango Anterior");
        $sheet->setCellValue('I'.$contador,"Dias Restantes / Rango Ultimo");
        $sheet->setCellValue('J'.$contador,"Variacion");
        $sheet->setCellValue('K'.$contador,"Status");
        $sheet->setCellValue('L'.$contador,"Comportamiento");
        $contador++;

        foreach ($arrayArticulos as $articulo) {
            /*EXCEL*/
            $sheet->setCellValue('A'.$contador,$contador);
            $sheet->setCellValue('B'.$contador,$articulo['CodigoInterno']);
            $sheet->setCellValue('C'.$contador,$articulo['CodigoBarra']);
            $sheet->setCellValue('D'.$contador,FG_Limpiar_Texto($articulo['Descripcion']));
            $sheet->setCellValue('E'.$contador,intval($articulo['Existencia']));
            $sheet->setCellValue('F'.$contador,$articulo['UltimaVenta']);
            $sheet->setCellValue('G'.$contador,$articulo['UltimaCompra']);
            $sheet->setCellValue('H'.$contador,$articulo['DiasRestantesPrimerRango']);
            $sheet->setCellValue('I'.$contador,$articulo['DiasRestantesSegundoRango']);
            $sheet->setCellValue('J'.$contador,$articulo['Variacion']);
            $sheet->setCellValue('K'.$contador,$articulo['Status']);
            $sheet->setCellValue('L'.$contador,$articulo['Comportamiento']);
            /*EXCEL*/
            $contador++;
        }

        $nombreDelDocumento = "Reposicion_Inventario".date('Ymd_h-i-A').".xlsx";

        /*EXCEL*/
            header('Content-Type: application/vnd.ms-excel');
            header('Content-Disposition: attachment;filename="' . $nombreDelDocumento . '"');

            $writer = IOFactory::createWriter($spreadsheet, 'Xlsx');
            $writer->save('php://output');
        /*EXCEL*/        
    }
}
