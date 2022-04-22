<?php

namespace compras\Http\Controllers;

use Illuminate\Http\Request;
use DateTime;
use DB;

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
        //$Hoy = date("Y-m-d",strtotime(date("Y-m-d")."+ 1 days"));
        $Hoy = date("Y-m-d",strtotime(date("2022-04-04")."+ 1 days"));
        $FInicialRangoUltimo = date("Y-m-d",strtotime($Hoy."-$RangoDias days"));
        $FInicialRangoAnterior = date("Y-m-d",strtotime($FInicialRangoUltimo."-$RangoDias days"));
        
        $SedeConnection = FG_Mi_Ubicacion();
        $conn = FG_Conectar_Smartpharma($SedeConnection);
        $sql = $this->Articulos_Existencia();
        $result = sqlsrv_query($conn,$sql);
                
        while($row = sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC)) {

            $IdArticulo = $row["IdArticulo"];
            $CodigoInterno = $row["CodigoInterno"];
            $CodigoBarra = $row["CodigoBarra"];
            $Descripcion = $row["Descripcion"];
            $Existencia = $row["Existencia"];
            $UltimaVenta = ($row["UltimaVenta"])?$row["UltimaVenta"]->format('d-m-Y'):'N/A';
            $UltimaCompra = ($row["UltimaCompra"])?$row["UltimaCompra"]->format('d-m-Y'):'N/A';
            
            $arrayRangoUltimo = $this->getEvaluarDiasCero($conn,$IdArticulo,$Existencia,$FInicialRangoUltimo,$Hoy,$LimiteDiasCero);
            $arrayRangoAnterior = $this->getEvaluarDiasCero($conn,$IdArticulo,$Existencia,$FInicialRangoAnterior,$FInicialRangoUltimo,$LimiteDiasCero);
            
            /*
            if( ($arrayRangoUltimo['DiasRestantesQuiebre']=="N/D") || ($arrayRangoAnterior['DiasRestantesQuiebre']=="N/D") ){
                $arrayRangoUltimo['DiasRestantesQuiebre']="N/D";
                $arrayRangoAnterior['DiasRestantesQuiebre']="N/D";
            }
            */

            $Variacion = $this->getVariacion($arrayRangoUltimo['DiasRestantesQuiebre'], $arrayRangoAnterior['DiasRestantesQuiebre']);
            $Status = $this->getStatus($arrayRangoUltimo['DiasRestantesQuiebre'], $arrayRangoAnterior['DiasRestantesQuiebre']);
            $Comportamiento = $this->getComportamiento($Variacion,$arrayRangoUltimo['DiasRestantesQuiebre'], $arrayRangoAnterior['DiasRestantesQuiebre']);

            $articulosDetalle =  array (
                "IdArticulo" => $IdArticulo,
                "CodigoInterno" => $CodigoInterno,
                "CodigoBarra" => $CodigoBarra,
                "Descripcion" => $Descripcion,
                "Existencia" => $Existencia,
                "UltimaVenta" => $UltimaVenta,
                "UltimaCompra" => $UltimaCompra,
                "DiasRestantesUltimo" => $arrayRangoUltimo['DiasRestantesQuiebre'],
                "DiasRestantesAnterior" => $arrayRangoAnterior['DiasRestantesQuiebre'],
                "Variacion" => $Variacion,
                "Status" => $Status,
                "Comportamiento" => $Comportamiento,
            );
    
            array_push($arrayArticulos,$articulosDetalle);
        }                        

        $arrayArticulos =  $this->orderArrayByRow($arrayArticulos,'DiasRestantesUltimo');

        $FinCarga = new DateTime("now");
        $IntervalCarga = $InicioCarga->diff($FinCarga);
        $Tiempo = $IntervalCarga->format("%Y-%M-%D %H:%I:%S");

        
		$Hoy = date("Y-m-d",strtotime($Hoy."- 1 days"));
		$FInicialRangoUltimoMenos = date("Y-m-d",strtotime($FInicialRangoUltimo."- 1 days"));

        $arrayGlobal = array(
            "SedeConnection" => $SedeConnection,
            "Hoy" => $Hoy,
            "FInicialRangoUltimo" => $FInicialRangoUltimo,
            "FInicialRangoUltimoMenos" => $FInicialRangoUltimoMenos,
            "FInicialRangoAnterior" => $FInicialRangoAnterior,
            "Tiempo" => $Tiempo,
        );
                      
        return view('pages.reporte.reporte49', compact('arrayGlobal', 'arrayArticulos'));
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
            --Existencia (Segun el almacen del filtro)
                (ROUND(CAST((SELECT SUM (InvLoteAlmacen.Existencia) As Existencia
                            FROM InvLoteAlmacen
                            WHERE(InvLoteAlmacen.InvAlmacenId = 1 OR InvLoteAlmacen.InvAlmacenId = 2)
                            AND (InvLoteAlmacen.InvArticuloId = InvArticulo.Id)) AS DECIMAL(38,0)),2,0))  AS Existencia,
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
            --Condicionales                
				WHERE 
				 (ROUND(CAST((SELECT SUM (InvLoteAlmacen.Existencia) As Existencia
                            FROM InvLoteAlmacen
                            WHERE(InvLoteAlmacen.InvAlmacenId = 1 OR InvLoteAlmacen.InvAlmacenId = 2)
                            AND (InvLoteAlmacen.InvArticuloId = InvArticulo.Id)) AS DECIMAL(38,0)),2,0)) > 0
				--AND InvArticulo.Id = '30'            
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
    
        $UnidadesVendidas = $VentaDiariaQuiebre = $DiasRestantesQuiebre = "";

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
                   
            $VentaDiariaQuiebre = FG_Venta_Diaria($UnidadesVendidas['TotalUnidadesVendidas'],$RangoDiasQuiebre[0]->Cuenta);
            $DiasRestantesQuiebre = FG_Dias_Restantes($Existencia,$VentaDiariaQuiebre);               
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

    public function getVariacion($RangoUltimo, $RangoAnterior){
        
        if( ($RangoUltimo=="N/D") || ($RangoAnterior=="N/D") ){
            return "-";
        }else{
            //Formula : ((segundo rango - primer rango) / primer rango) * 100
            $variacion = (($RangoUltimo - $RangoAnterior) / $RangoAnterior) * 100;
            return round($variacion,2,PHP_ROUND_HALF_UP);
        }
    }

    public function getStatus($RangoUltimo, $RangoAnterior){
             
        if($RangoUltimo===0.00){
            return "INDETERMINABLE";
        }  
        else if($RangoAnterior=="N/D"){
            return "-";
        }            
        else if($RangoUltimo>0 && $RangoUltimo<30){
            return 'CRITICO';
        }
        else if($RangoUltimo>=30 && $RangoUltimo<90){
            return 'BIEN';
        }
        else if($RangoUltimo>=90){
            return 'EXCEDIDO';
        }                               
    }

    public function getComportamiento($Variacion,$RangoUltimo, $RangoAnterior){

        if( $RangoUltimo=="N/D" || $RangoAnterior=="N/D" ){
            return "INDETERMINABLE";
        }
        else if( $RangoUltimo==0 && $RangoAnterior==0 ){
            return "PELIGRO";
        }
        else if( abs($Variacion) < 10 ){
            return "ESTABLE";
        }
        else if( $RangoAnterior==0 && $RangoUltimo!=0 ){
            return "LLEGANDO";
        }
        else if( $RangoAnterior!=0 && $RangoUltimo==0 ){
            return "CAYÓ";
        }
        else if( $RangoAnterior < ($RangoUltimo+($RangoAnterior*0.10)) ){
            return "CRECIO";
        }
        else if( $RangoAnterior > ($RangoUltimo-($RangoAnterior*0.10)) ){
            return "DECRECIO";
        }
    }
}
