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

        $ArrayData = array();

        include(app_path().'\functions\config.php');
        include(app_path().'\functions\functions.php');
        include(app_path().'\functions\querys_mysql.php');
        include(app_path().'\functions\querys_sqlserver.php');
                          
        $RangoDias = 15;
        //$Hoy = "2022-04-01";
        $Hoy = date("Y-m-d"); 
        $FInicial_RangoUltimo = date("Y-m-d",strtotime($Hoy."-$RangoDias days"));
        $FInicial_RangoAnterior = date("Y-m-d",strtotime($FInicial_RangoUltimo."-$RangoDias days"));
        
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
            $UltimaCompra = ($row["UltimaVenta"])?$row["UltimaCompra"]->format('d-m-Y'):'N/A';
            
            // INICIO Gestion del rango: Desde Ultimos Disa Hasta Hoy
            $sqlUV_RangoUltimo = $this->Venta_Articulo($IdArticulo,$FInicial_RangoUltimo,$Hoy);            
            $resultUV_RangoUltimo = sqlsrv_query($conn,$sqlUV_RangoUltimo);
            $UnidadesVendidas_RangoUltimo = sqlsrv_fetch_array($resultUV_RangoUltimo, SQLSRV_FETCH_ASSOC);

            $RangoDiasQuiebre_RangoUltimo = DB::select("
                SELECT 
                COUNT(*) AS Cuenta 
                FROM dias_ceros 
                WHERE dias_ceros.id_articulo = '$IdArticulo' AND (fecha_captura >= '$FInicial_RangoUltimo' AND `fecha_captura` < '$Hoy')
            ");            

            $VentaDiariaQuiebre_RangoUltimo = FG_Venta_Diaria($UnidadesVendidas_RangoUltimo['TotalUnidadesVendidas'],$RangoDiasQuiebre_RangoUltimo[0]->Cuenta);
            $DiasRestantesQuiebre_RangoUltimo = FG_Dias_Restantes($Existencia,$VentaDiariaQuiebre_RangoUltimo);

            // FIN 

            // INICIO Gestion del rango: Desde Dias Anteriores Hasta Ultimos Dias

            $sqlUV_RangoAnterior = $this->Venta_Articulo($IdArticulo,$FInicial_RangoAnterior,$FInicial_RangoUltimo);            
            $resultUV_RangoAnterior = sqlsrv_query($conn,$sqlUV_RangoAnterior);
            $UnidadesVendidas_RangoAnterior = sqlsrv_fetch_array($resultUV_RangoAnterior, SQLSRV_FETCH_ASSOC);

            $RangoDiasQuiebre_RangoAnterior = DB::select("
                SELECT 
                COUNT(*) AS Cuenta 
                FROM dias_ceros 
                WHERE dias_ceros.id_articulo = '$IdArticulo' AND (fecha_captura >= '$FInicial_RangoAnterior' AND `fecha_captura` < '$FInicial_RangoUltimo')
            ");            

            $VentaDiariaQuiebre_RangoAnterior = FG_Venta_Diaria($UnidadesVendidas_RangoAnterior['TotalUnidadesVendidas'],$RangoDiasQuiebre_RangoAnterior[0]->Cuenta);
            $DiasRestantesQuiebre_RangoAnterior = FG_Dias_Restantes($Existencia,$VentaDiariaQuiebre_RangoAnterior);
            // FIN 

            $DetalleArticuloArray =  [
                "IdArticulo" => $IdArticulo,
                "CodigoInterno" => $CodigoInterno,
                "CodigoBarra" => $CodigoBarra,
                "Descripcion" => $Descripcion,
                "Existencia" => $Existencia,
                "UltimaVenta" => $UltimaVenta,
                "UltimaCompra" => $UltimaCompra,
    
                "Hoy" => $Hoy,
                "FInicial_RangoUltimo" => $FInicial_RangoUltimo,
                "FInicial_RangoAnterior" => $FInicial_RangoAnterior,
    
                "UnidadesVendidas_RangoUltimo" => $UnidadesVendidas_RangoUltimo['TotalUnidadesVendidas'],
                "RangoDiasQuiebre_RangoUltimo" => $RangoDiasQuiebre_RangoUltimo[0]->Cuenta,
                "VentaDiariaQuiebre_RangoUltimo" => $VentaDiariaQuiebre_RangoUltimo,
                "DiasRestantesQuiebre_RangoUltimo" => $DiasRestantesQuiebre_RangoUltimo,
    
                
                "UnidadesVendidas_RangoAnterior" => $UnidadesVendidas_RangoAnterior['TotalUnidadesVendidas'],
                "RangoDiasQuiebre_RangoAnterior" => $RangoDiasQuiebre_RangoAnterior[0]->Cuenta,
                "VentaDiariaQuiebre_RangoAnterior" => $VentaDiariaQuiebre_RangoAnterior,
                "DiasRestantesQuiebre_RangoAnterior" => $DiasRestantesQuiebre_RangoAnterior,                  
            ];
    
            array_push($ArrayData,$DetalleArticuloArray);            
        }                        

        // Ordenar el resultado por DiasRestantesQuiebre_RangoUltimo
        $marks = array();
        foreach ($ArrayData as $key => $row)
        {
            $marks[$key] = $row['DiasRestantesQuiebre_RangoUltimo'];            
        }

        array_multisort($marks, SORT_DESC, $ArrayData);
                      
        return view('pages.reporte.reporte49', compact('ArrayData'));
    }    

    public function Articulos_Existencia() {
        $sql = "SELECT
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
				--AND InvArticulo.Id = '26'            
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
}
