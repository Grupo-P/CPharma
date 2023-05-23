<?php

namespace compras\Http\Controllers\Pagomovil;

use Illuminate\Http\Request;
use compras\Http\Controllers\Controller;
use compras\VueltoVDC;
use compras\conexion\VueltosFEC;
use compras\conexion\VueltosFLL;
use compras\conexion\VueltosFM;
use compras\conexion\VueltosFTN;
use compras\conexion\VueltosKDI;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;


class VueltoController extends Controller
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
     * @return \Illuminate\Http\ResponseDBs
     */
    public function index(Request $request)
    {
        include app_path() . '/functions/config.php';
        include app_path() . '/functions/functions.php';
        
        $sedeUsuario = Auth::user()->sede;
        switch($sedeUsuario){
            case "GRUPO P, C.A":
                if ($_SERVER['SERVER_NAME'] == 'cpharmagpde.com' ) {
                    $RutaUrl = "DBs";
                }
                else{
                    $RutaUrl = "FAU";
                }                
            break;
            case "FARMACIA AVENIDA UNIVERSIDAD, C.A.":
                if ($_SERVER['SERVER_NAME'] == 'cpharmagpde.com' ) {
                    $RutaUrl = "DBs";
                }
                else{
                    $RutaUrl = "FAU";
                }    
            break;
            case "FARMACIA TIERRA NEGRA, C.A.":
                $RutaUrl = "FTN";
            break;
            case "FARMACIA MILLENNIUM 2000, C.A":
                $RutaUrl = "FSM";
            break;
            case "FARMACIA LA LAGO,C.A.":
                $RutaUrl = "FLL";
            break;
            case "FARMACIAS KD EXPRESS, C.A.":
                $RutaUrl = "KDI";
            break;
            case "FARMACIAS KD EXPRESS, C.A. - KD73":
                $RutaUrl = "KD73";
            break;
            case "FARMACIA EL CALLEJON, C.A.":
                $RutaUrl = "FEC";
            break;

        }
        //$RutaUrl = 'FAU';
        //si se selecciono otra sede consultarla
        if($request->sede!=null){
            if($request->sede!="Seleccione una sede"){
                if ($_SERVER['SERVER_NAME'] == 'cpharmagpde.com' ) {
                    if($request->sede=="FAU"){
                        $RutaUrl='DBs';
                    }
                    else{
                        $RutaUrl=$request->sede;
                    }
                }
                else{
                    $RutaUrl=$request->sede;
                }
            }
            else{
                $RutaUrl=$RutaUrl;                
            }
        }
        $SedeConnection = $RutaUrl;
        $conn = FG_Conectar_Smartpharma($SedeConnection);
        

        $registro=[];       
        $historialvueltos=collect();

        if($request->fecha_ini>0){
            $fini=$request->fecha_ini;
            $ffin=$request->fecha_fin;
            $vueltos =  VueltoVDC::fecha($fini, $ffin)->OrderBy("id",'desc')->get();
        }
        else{
            $fini=date("Y-m-d");
            $ffin=date("Y-m-d");            
        }
        
        if($request->sede!=null){
            if($request->sede=="Seleccione una sede"){
                $sede=$RutaUrl;
            }
            else{
                if ($_SERVER['SERVER_NAME'] == 'cpharmagpde.com') {
                    if($request->sede=="FAU"){
                        $sede='DBs';
                    }
                    else{
                        $sede=$request->sede;
                    }
                }                
                else{
                    $sede=$request->sede;
                }
                
            }

            switch($sede)
            {
                case 'DBs':
                    if(FG_Validar_Conectividad('FAU')==1){
                        $vueltos =  VueltoVDC::fecha($fini, $ffin)->OrderBy("id",'desc')->get();
                        $mensaje=null;
                    }
                    else{
                        $vueltos=null;
                        $mensaje="No hay conexion con Farmacia Avenida Universidad (FAU)";
                    }
                break;
                case 'FAU':
                    if(FG_Validar_Conectividad('FAU')==1){
                        $vueltos =  VueltoVDC::fecha($fini, $ffin)->OrderBy("id",'desc')->get();
                        $mensaje=null;
                    }
                    else{
                        $vueltos=null;
                        $mensaje="No hay conexion con Farmacia Avenida Universidad (FAU)";
                    }
                break;
                case 'GP':
                    if(FG_Validar_Conectividad('FAU')==1){
                        $vueltos =  VueltoVDC::fecha($fini, $ffin)->OrderBy("id",'desc')->get();
                        $mensaje=null;
                    }
                    else{
                        $vueltos=null;
                        $mensaje="No hay conexion con Grupo P (GP)";
                    }
                break;
                case 'FTN':
                    if(FG_Validar_Conectividad('FTN')==1){
                        $vueltos =  VueltosFTN::fecha($fini, $ffin)->OrderBy("id",'desc')->get();
                        $mensaje=null;
                    }
                    else{
                        $vueltos=null;
                        $mensaje="No hay conexion con Farmacia Tierra Negra (FTN)";
                    }
                break;
                case 'FLL':
                    if(FG_Validar_Conectividad('FLL')==1){
                        $vueltos =  VueltosFLL::fecha($fini, $ffin)->OrderBy("id",'desc')->get();
                        $mensaje=null;
                    }
                    else{
                        $vueltos=null;
                        $mensaje="No hay conexion con Farmacia La Lago (FLL)";
                    }
                break;
                case 'FSM':
                    if(FG_Validar_Conectividad('FSM')==1){
                        $vueltos =  VueltosFM::fecha($fini, $ffin)->OrderBy("id",'desc')->get();
                        $mensaje=null;
                    }
                    else{
                        $vueltos=null;
                        $mensaje="No hay conexion con Farmacia Millenium 2000 (FSM)";
                    }
                break;
                case 'KDI':
                    if(FG_Validar_Conectividad('KDI')==1){
                        $vueltos =  VueltosKDI::fecha($fini, $ffin)->OrderBy("id",'desc')->get();
                        $mensaje=null;
                    }
                    else{
                        $vueltos=null;
                        $mensaje="No hay conexion con Farmacia KD Express (KDI)";
                    }
                break;
                case 'FEC':
                    if(FG_Validar_Conectividad('FEC')==1){
                        $vueltos =  VueltosFEC::fecha($fini, $ffin)->OrderBy("id",'desc')->get();
                        $mensaje=null;
                    }
                    else{
                        $vueltos=null;
                        $mensaje="No hay conexion con Farmacia El Callejon (FEC)";
                    }
                break;
            }
            
        }
        else{
            if($SedeConnection=="GP"){
                $sede="FAU";
            }
            else{
                $sede=$SedeConnection;
            }
            switch($sede)
            {
                case 'DBs':
                    
                        $vueltos =  VueltoVDC::fecha($fini, $ffin)->OrderBy("id",'desc')->get();
                        $mensaje=null;                    
                break;
                case 'FAU':
                    if(FG_Validar_Conectividad('FAU')==1){
                        $vueltos =  VueltoVDC::fecha($fini, $ffin)->OrderBy("id",'desc')->get();
                        $mensaje=null;
                    }
                    else{
                        $vueltos=null;
                        $mensaje="No hay conexion con Farmacia Avenida Universidad (FAU)";
                    }
                break;
                case 'GP':
                    if(FG_Validar_Conectividad('FAU')==1){
                        $vueltos =  VueltoVDC::fecha($fini, $ffin)->OrderBy("id",'desc')->get();
                        $mensaje=null;
                    }
                    else{
                        $vueltos=null;
                        $mensaje="No hay conexion con Grupo P (GP)";
                    }
                break;
                case 'FTN':
                    if(FG_Validar_Conectividad('FTN')==1){
                        $vueltos =  VueltosFTN::fecha($fini, $ffin)->OrderBy("id",'desc')->get();
                        $mensaje=null;
                    }
                    else{
                        $vueltos=null;
                        $mensaje="No hay conexion con Farmacia Tierra Negra (FTN)";
                    }
                break;
                case 'FLL':
                    if(FG_Validar_Conectividad('FLL')==1){
                        $vueltos =  VueltosFLL::fecha($fini, $ffin)->OrderBy("id",'desc')->get();
                        $mensaje=null;
                    }
                    else{
                        $vueltos=null;
                        $mensaje="No hay conexion con Farmacia La Lago (FLL)";
                    }
                break;
                case 'FSM':
                    if(FG_Validar_Conectividad('FSM')==1){
                        $vueltos =  VueltosFM::fecha($fini, $ffin)->OrderBy("id",'desc')->get();
                        $mensaje=null;
                    }
                    else{
                        $vueltos=null;
                        $mensaje="No hay conexion con Farmacia Millenium 2000 (FSM)";
                    }
                break;
                case 'KDI':
                    if(FG_Validar_Conectividad('KDI')==1){
                        $vueltos =  VueltosKDI::fecha($fini, $ffin)->OrderBy("id",'desc')->get();
                        $mensaje=null;
                    }
                    else{
                        $vueltos=null;
                        $mensaje="No hay conexion con Farmacia KD Express (KDI)";
                    }
                break;
                case 'FEC':
                    if(FG_Validar_Conectividad('FEC')==1){
                        $vueltos =  VueltosFEC::fecha($fini, $ffin)->OrderBy("id",'desc')->get();
                        $mensaje=null;
                    }
                    else{
                        $vueltos=null;
                        $mensaje="No hay conexion con Farmacia El Callejon (FEC)";
                    }
                break;
            }
            
        }
        if($vueltos!=null){
            foreach($vueltos as $vuelto){
                
                $idFactura=$vuelto->id_factura;
                $montoPagadoFacturaDolar=0;
                $totalFacturaDolar=0;
                $montoPagadoFactura=0;
                $montoPagadoFacturaDolar=0;
                $montoPagoMovilDolar=0;
                //Consulta de la factura
                $sql = "
                        SELECT
                            TOP 1 vvp.VenVentaId,
                            f.NumeroFactura,
                            f.Auditoria_Usuario,
                            f.FechaDocumento,
                            ca.EstacionTrabajo,
                            cl.CodigoCliente,
                            CONCAT(g.Nombre, ' ', g.Apellido) AS nombre,
                            g.Telefono,
                            f.M_MontoTotalFactura as totalFactura,
                            vvp.MontoPagado,
                            vvp.MontoRecibido
                        FROM
                            VenFactura f
                            LEFT JOIN VenCaja vca ON f.VenCajaId =  vca.Id
                            LEFT JOIN VenCaja ca ON f.VenCajaId = ca.Id
                            LEFT JOIN VenCliente cl ON f.VenClienteId = cl.Id
                            LEFT JOIN GenPersona g ON g.Id = cl.GenPersonaId
                            LEFT JOIN VenVentaPago vvp ON vvp.VenVentaId = f.VenVentaId
                        WHERE
                            f.Id = ".$idFactura."
                        ORDER BY
                            f.Id DESC
                    ";

                $result = sqlsrv_query($conn, $sql);
                $row = sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC);

                //Busqueda de devoluciones de la factura
                $sql2="
                    SELECT TOP 1
                        vd.Id,
                        vd.VenFacturaId,
                        vd.ConsecutivoDevolucionSistema,
                        vd.Auditoria_FechaCreacion,
                        vd.FechaDocumento,
                        vca.EstacionTrabajo,
                        vd.Auditoria_Usuario
                    FROM VenDevolucion vd
                    LEFT JOIN VenCaja vca ON vca.Id =  vd.VenCajaId
                    WHERE
                                    vd.VenFacturaId = ".$idFactura."
                ";
                $result2 = sqlsrv_query($conn, $sql2);
                $devolucion = sqlsrv_fetch_array($result2, SQLSRV_FETCH_ASSOC);

                if($devolucion["ConsecutivoDevolucionSistema"]>0){
                    $fechaDevolucion=$devolucion["FechaDocumento"]->format('Y-m-d H:i:s');
                }
                else{
                    $fechaDevolucion="";
                }
                if($vuelto->montoPagado>0){
                    $montoPagadoFactura=$vuelto->montoPagado;
                }
                else{
                    $montoPagadoFactura=0;
                }

                if($vuelto->tasaVenta>0){
                    
                    $totalFacturaDolar=number_format($row["totalFactura"]/$vuelto->tasaVenta,2);
                    $montoPagoMovilDolar=number_format($vuelto->monto/$vuelto->tasaVenta,2);
                    if($montoPagadoFactura>0){
                        $montoPagadoFacturaDolar=number_format($montoPagadoFactura/$vuelto->tasaVenta,2);
                    }
                    else{
                        $montoPagadoFactura=0;
                    }
                }   
                else{
                    $totalFacturaDolar=0;
                    $montoPagoMovilDolar=0;
                    $montoPagadoFactura=0;
                }

                $registro = [
                    'sede'=>$vuelto->sede,
                    'cedula_cliente_factura' =>$row["CodigoCliente"],
                    'telefono_cliente_factura' =>$row["Telefono"],
                    'fecha_hora_factura'=>$row['FechaDocumento']->format('Y-m-d H:i:s'),                                   
                    'numero_factura' => $row["NumeroFactura"] ,
                    'nombre_cliente'=>$row["nombre"] ,
                    'total_factura'=>$row["totalFactura"] ,
                    'total_factura_dolar' => $totalFacturaDolar ,
                    'monto_pagado_factura' => $montoPagadoFactura,
                    'monto_pagado_factura_dolar' => $montoPagadoFacturaDolar,
                    'id'=>$vuelto->id,  
                    'id_factura'=> $vuelto->id_factura,  
                    'fecha_hora'=> $vuelto->fecha_hora,  
                    'banco_cliente'=> $vuelto->banco_cliente,  
                    'cedula_cliente'=> $vuelto->cedula_cliente,  
                    'telefono_pago_movil'=> $vuelto->telefono_cliente,  
                    'monto' => $vuelto->monto,
                    'monto_dolar' => $montoPagoMovilDolar,
                    'sede'=> $vuelto->sede,  
                    'caja'=> $vuelto->caja,  
                    'cajero_venta'  => $vuelto->nombreCajeroFactura,
                    'estatus'=> $vuelto->estatus,  
                    'confirmacion_banco'=> $vuelto->confirmacion_banco,  
                    'motivo_error'=> $vuelto->motivo_error,
                    'nro_devolucion' =>$devolucion["ConsecutivoDevolucionSistema"],
                    'fecha_devolucion' => $fechaDevolucion,
                    'caja_devolucion' =>$devolucion["EstacionTrabajo"],
                    'cajero_devolucion' =>$devolucion["Auditoria_Usuario"]  
                ];           
                
                $coleccion=collect($registro);
                $historialvueltos->push($coleccion);
            }    
        } 
        else{
            $historialvueltos=null;
        }      
        return view('pages.vuelto.index', compact('mensaje','historialvueltos','fini','ffin','sedeUsuario','sede'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function clientesTransaccionales(Request $request)
    {        
        include app_path() . '/functions/config.php';
        include app_path() . '/functions/functions.php';

        if($request->fecha_ini>0){
            $fini=$request->fecha_ini;
            $ffin=$request->fecha_fin;            
        }

        else{
            $fini=date("Y-m-d");
            $ffin=date("Y-m-d");            
        }

        $sedeUsuario = Auth::user()->sede;
        switch($sedeUsuario){
            case "GRUPO P, C.A":
                if ($_SERVER['SERVER_NAME'] == 'cpharmagpde.com' ) {
                    $RutaUrl = "DBs";
                }
                else{
                    $RutaUrl = "FAU";
                }    
            break;
            case "FARMACIA AVENIDA UNIVERSIDAD, C.A.":
                if ($_SERVER['SERVER_NAME'] == 'cpharmagpde.com' ) {
                    $RutaUrl = "DBs";
                }
                else{
                    $RutaUrl = "FAU";
                }    
            break;
            case "FARMACIA TIERRA NEGRA, C.A.":
                $RutaUrl = "FTN";
            break;
            case "FARMACIA MILLENNIUM 2000, C.A":
                $RutaUrl = "FSM";
            break;
            case "FARMACIA LA LAGO,C.A.":
                $RutaUrl = "FLL";
            break;
            case "FARMACIAS KD EXPRESS, C.A.":
                $RutaUrl = "KDI";
            break;
            case "FARMACIAS KD EXPRESS, C.A. - KD73":
                $RutaUrl = "KD73";
            break;
            case "FARMACIA EL CALLEJON, C.A.":
                $RutaUrl = "FEC";
            break;

        }
        
        
        if($request->sede!=null){
            if($request->sede!="Seleccione una sede"){
                if ($_SERVER['SERVER_NAME'] == 'cpharmagpde.com' ) {
                    if($request->sede=="FAU"){
                        $RutaUrl='DBs';
                    }
                    else{
                        $RutaUrl=$request->sede;
                    }
                }
                else{
                    $RutaUrl=$request->sede;
                }
            }
            else{
                $RutaUrl=$RutaUrl;
            }
        }
        
        $SedeConnection = $RutaUrl;        
        $sede=$RutaUrl;

        switch($sede)
        {
            case 'DBs':                                          
                $clientes=VueltoVDC::where('sede','=','DBs')->fecha($fini, $ffin)
                        ->where('estatus','=','Aprobado')                    
                        ->select('cedulaClienteFactura','sede','nombreClienteFactura','id',DB::raw('COUNT(vuelto_vdc.id) as vueltos'))
                        ->groupBy('cedulaClienteFactura')
                        ->orderBy('vueltos', 'DESC')
                        ->get();
                $mensaje=null;                 
            break;
            case 'FAU':
                if(FG_Validar_Conectividad('FAU')==1){                        
                    $clientes=VueltoVDC::where('sede','=',$sede)->fecha($fini, $ffin)
                            ->where('estatus','=','Aprobado')                    
                            ->select('cedulaClienteFactura','sede','nombreClienteFactura','id',DB::raw('COUNT(vuelto_vdc.id) as vueltos'))
                            ->groupBy('cedulaClienteFactura')
                            ->orderBy('vueltos', 'DESC')
                            ->get();
                    $mensaje=null;
                    
                }
                else{
                    $clientes=null;
                    $mensaje="No hay conexion con Farmacia Avenida Universidad (FAU)";
                }
            break;
            case 'GP':
                if(FG_Validar_Conectividad('FAU')==1){
                    $clientes=VueltoVDC::where('sede','=',$sede)->fecha($fini, $ffin)
                            ->where('estatus','=','Aprobado')                    
                            ->select('cedulaClienteFactura','sede','nombreClienteFactura','id',DB::raw('COUNT(vuelto_vdc.id) as vueltos'))
                            ->groupBy('cedulaClienteFactura')
                            ->orderBy('vueltos', 'DESC')
                            ->get();
                    $mensaje=null;
                }
                else{
                    $clientes=null;
                    $mensaje="No hay conexion con Grupo P (GP)";
                }
            break;
            case 'FTN':
                if(FG_Validar_Conectividad('FTN')==1){
                    $clientes=VueltosFTN::where('sede','=',$sede)->fecha($fini, $ffin)
                            ->where('estatus','=','Aprobado')                    
                            ->select('cedulaClienteFactura','sede','nombreClienteFactura','id',DB::raw('COUNT(vuelto_vdc.id) as vueltos'))
                            ->groupBy('cedulaClienteFactura')
                            ->orderBy('vueltos', 'DESC')
                            ->get();
                    $mensaje=null;
                }
                else{
                    $clientes=null;
                    $mensaje="No hay conexion con Farmacia Tierra Negra (FTN)";
                }
            break;
            case 'FLL':
                if(FG_Validar_Conectividad('FLL')==1){                                               
                    $clientes=VueltosFLL::where('sede','=',$sede)->fecha($fini, $ffin)
                            ->where('estatus','=','Aprobado')                    
                            ->select('cedulaClienteFactura','sede','nombreClienteFactura','id',DB::raw('COUNT(vuelto_vdc.id) as vueltos'))
                            ->groupBy('cedulaClienteFactura')
                            ->orderBy('vueltos', 'DESC')
                            ->get();
                    $mensaje=null;
                }
                else{
                    $clientes=null;
                    $mensaje="No hay conexion con Farmacia La Lago (FLL)";
                }
            break;
            case 'FSM':
                if(FG_Validar_Conectividad('FSM')==1){                        
                    $clientes=VueltosFM::where('sede','=',$sede)->fecha($fini, $ffin)
                            ->where('estatus','=','Aprobado')                    
                            ->select('cedulaClienteFactura','sede','nombreClienteFactura','id',DB::raw('COUNT(vuelto_vdc.id) as vueltos'))
                            ->groupBy('cedulaClienteFactura')
                            ->orderBy('vueltos', 'DESC')
                            ->get(); 
                    $mensaje=null;
                }
                else{
                    $clientes=null;
                    $mensaje="No hay conexion con Farmacia Millenium 2000 (FSM)";
                }
            break;
            case 'KDI':
                if(FG_Validar_Conectividad('KDI')==1){                                                
                    $clientes=VueltosKDI::where('sede','=',$sede)->fecha($fini, $ffin)
                            ->where('estatus','=','Aprobado')                    
                            ->select('cedulaClienteFactura','sede','nombreClienteFactura','id',DB::raw('COUNT(vuelto_vdc.id) as vueltos'))
                            ->groupBy('cedulaClienteFactura')
                            ->orderBy('vueltos', 'DESC')
                            ->get(); 
                    $mensaje=null;
                }
                else{
                    $clientes=null;
                    $mensaje="No hay conexion con Farmacia KD Express (KDI)";
                }
            break;
            case 'FEC':
                if(FG_Validar_Conectividad('FEC')==1){   
                    $clientes=VueltosFEC::where('sede','=',$sede)->fecha($fini, $ffin)
                                    ->where('estatus','=','Aprobado')                    
                                    ->select('cedulaClienteFactura','sede','nombreClienteFactura','id',DB::raw('COUNT(vuelto_vdc.id) as vueltos'))
                                    ->groupBy('cedulaClienteFactura')
                                    ->orderBy('vueltos', 'DESC')
                                    ->get();                             
                    $mensaje=null;
                }
                else{
                    $clientes=null;
                    $mensaje="No hay conexion con Farmacia El Callejon (FEC)";
                }
            break;
        }                   
        
        $arreglo=[];
        $i=0;
        
        foreach ($clientes as $cliente) {
            if($cliente->cedulaClienteFactura!=null){
                
                switch($sede)
                {
                    case 'DBs':                                          
                            $total=VueltoVDC::where('sede','=','DBs')->fecha($fini, $ffin)
                                            ->where('cedulaClienteFactura','=',$cliente->cedulaClienteFactura)
                                            ->where('estatus','=','Aprobado')
                                            ->get();
                            $mensaje=null;                    
                    break;
                    case 'FAU':
                        if(FG_Validar_Conectividad('FAU')==1){                        
                            $total=VueltoVDC::where('sede','=','FAU')->fecha($fini, $ffin)
                                            ->where('cedulaClienteFactura','=',$cliente->cedulaClienteFactura)
                                            ->where('estatus','=','Aprobado')
                                            ->get();
                            $mensaje=null;
                        }
                        else{
                            $total=null;
                            $mensaje="No hay conexion con Farmacia Avenida Universidad (FAU)";
                        }
                    break;
                    case 'GP':
                        if(FG_Validar_Conectividad('FAU')==1){
                            
                            $total=VueltoVDC::where('sede','=','FAU')->fecha($fini, $ffin)
                                                ->where('cedulaClienteFactura','=',$cliente->cedulaClienteFactura)
                                                ->where('estatus','=','Aprobado')
                                                ->get();
                            $mensaje=null;
                        }
                        else{
                            $total=null;
                            $mensaje="No hay conexion con Grupo P (GP)";
                        }
                    break;
                    case 'FTN':
                        if(FG_Validar_Conectividad('FTN')==1){                        
                            $total=VueltosFTN::where('sede','=','FTN')->fecha($fini, $ffin)
                                            ->where('cedulaClienteFactura','=',$cliente->cedulaClienteFactura)
                                            ->where('estatus','=','Aprobado')
                                            ->get();
                            $mensaje=null;
                        }
                        else{
                            $total=null;
                            $mensaje="No hay conexion con Farmacia Tierra Negra (FTN)";
                        }
                    break;
                    case 'FLL':
                        if(FG_Validar_Conectividad('FLL')==1){                        
                            $total=VueltosFLL::where('sede','=','FLL')->fecha($fini, $ffin)
                                            ->where('cedulaClienteFactura','=',$cliente->cedulaClienteFactura)
                                            ->where('estatus','=','Aprobado')
                                            ->get();
                            $mensaje=null;
                        }
                        else{
                            $total=null;
                            $mensaje="No hay conexion con Farmacia La Lago (FLL)";
                        }
                    break;
                    case 'FSM':
                        if(FG_Validar_Conectividad('FSM')==1){                        
                            $total=VueltosFM::where('sede','=','FSM')->fecha($fini, $ffin)
                                                ->where('cedulaClienteFactura','=',$cliente->cedulaClienteFactura)
                                                ->where('estatus','=','Aprobado')
                                                ->get();
                            $mensaje=null;
                        }
                        else{
                            $total=null;
                            $mensaje="No hay conexion con Farmacia Millenium 2000 (FSM)";
                        }
                    break;
                    case 'KDI':
                        if(FG_Validar_Conectividad('KDI')==1){                        
                            $total=VueltosKDI::where('sede','=','KDI')->fecha($fini, $ffin)
                                                ->where('cedulaClienteFactura','=',$cliente->cedulaClienteFactura)
                                                ->where('estatus','=','Aprobado')
                                                ->get();
                            $mensaje=null;
                        }
                        else{
                            $total=null;
                            $mensaje="No hay conexion con Farmacia KD Express (KDI)";
                        }
                    break;
                    case 'FEC':
                        if(FG_Validar_Conectividad('FEC')==1){                        
                            $total=VueltosFEC::where('sede','=','FEC')->fecha($fini, $ffin)
                                            ->where('cedulaClienteFactura','=',$cliente->cedulaClienteFactura)
                                            ->where('estatus','=','Aprobado')
                                            ->get();
                            $mensaje=null;
                        }
                        else{
                            $total=null;
                            $mensaje="No hay conexion con Farmacia El Callejon (FEC)";
                        }
                    break;
                }
                
                                
                $totalBsFactura=$total->sum('totalFacturaBs');
                $totalDolarFactura=$total->sum('totalFacturaDolar'); 
                $totalPagadoBs=$total->sum('monto');
                $totalPagadoDolar=0;
                foreach ($total as $registro) {
                    $totalPagadoDolar=$totalPagadoDolar+($registro->monto/$registro->tasaVenta);
                }               
                $i++;
                array_push( $arreglo,[
                    'registro' => $i,
                    'Sede' => $cliente->sede,
                    'cedulaClienteFactura' => $cliente->cedulaClienteFactura,
                    'nombreClienteFactura' => $cliente->nombreClienteFactura,
                    'TotalAcumuladoBs' => $totalBsFactura,
                    'TotalAcumuladoDolar' => $totalDolarFactura,
                    'TotalPagadoBs' => number_format($totalPagadoBs,2),
                    'TotalPagadoDolar' => number_format($totalPagadoDolar,2),
                    'cantidadPagoMovil' => $cliente->vueltos                    
                ]);
            }
        }    
        
        return view('pages.vuelto.clientes', compact('arreglo','sedeUsuario','sede','mensaje','fini','ffin'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function cajerosTransaccionales(Request $request)
    {       
        include app_path() . '/functions/config.php';
        include app_path() . '/functions/functions.php';

        if($request->fecha_ini>0){
            $fini=$request->fecha_ini;
            $ffin=$request->fecha_fin;
            
        }
        else{
            $fini=date("Y-m-d");
            $ffin=date("Y-m-d");            
        }    

        $sedeUsuario = Auth::user()->sede;

        switch($sedeUsuario){
            case "GRUPO P, C.A":
                if ($_SERVER['SERVER_NAME'] == 'cpharmagpde.com' ) {
                    $RutaUrl = "DBs";
                }
                else{
                    $RutaUrl = "FAU";
                }    
            break;
            case "FARMACIA AVENIDA UNIVERSIDAD, C.A.":
                if ($_SERVER['SERVER_NAME'] == 'cpharmagpde.com' ) {
                    $RutaUrl = "DBs";
                }
                else{
                    $RutaUrl = "FAU";
                }    
            break;
            case "FARMACIA TIERRA NEGRA, C.A.":
                $RutaUrl = "FTN";
            break;
            case "FARMACIA MILLENNIUM 2000, C.A":
                $RutaUrl = "FSM";
            break;
            case "FARMACIA LA LAGO,C.A.":
                $RutaUrl = "FLL";
            break;
            case "FARMACIAS KD EXPRESS, C.A.":
                $RutaUrl = "KDI";
            break;
            case "FARMACIAS KD EXPRESS, C.A. - KD73":
                $RutaUrl = "KD73";
            break;
            case "FARMACIA EL CALLEJON, C.A.":
                $RutaUrl = "FEC";
            break;

        }        

        if($request->sede!=null){
            if($request->sede!="Seleccione una sede"){
                if ($_SERVER['SERVER_NAME'] == 'cpharmagpde.com' ) {
                    if($request->sede=="FAU"){
                        $RutaUrl='DBs';
                    }
                    else{
                        $RutaUrl=$request->sede;
                    }
                }
                else{
                    $RutaUrl=$request->sede;
                }
            }
            else{
                $RutaUrl=$RutaUrl;
            }
        }

        $SedeConnection = $RutaUrl;
        $sede = $RutaUrl;
        
       
        switch($sede)
        {
            case 'DBs':                                          
                $cajeros=VueltoVDC::where('sede','=',$sede)->fecha($fini, $ffin)
                            ->where('estatus','=','Aprobado')
                            ->select('nombreCajeroFactura','sede','id',DB::raw('COUNT(vuelto_vdc.id) as vueltos'))
                            ->groupBy('nombreCajeroFactura')
                            ->orderBy('vueltos', 'DESC')
                            ->get();
                    $mensaje=null;                    
            break;
            case 'FAU':
                if(FG_Validar_Conectividad('FAU')==1){                        
                    $cajeros=VueltoVDC::where('sede','=',$sede)->fecha($fini, $ffin)
                            ->where('estatus','=','Aprobado')
                            ->select('nombreCajeroFactura','sede','id',DB::raw('COUNT(vuelto_vdc.id) as vueltos'))
                            ->groupBy('nombreCajeroFactura')
                            ->orderBy('vueltos', 'DESC')
                            ->get();
                    $mensaje=null;
                }
                else{
                    $cajeros=null;
                    $mensaje="No hay conexion con Farmacia Avenida Universidad (FAU)";
                }
            break;
            case 'GP':
                if(FG_Validar_Conectividad('FAU')==1){
                    
                    $cajeros=VueltoVDC::where('sede','=',$sede)->fecha($fini, $ffin)
                            ->where('estatus','=','Aprobado')
                            ->select('nombreCajeroFactura','sede','id',DB::raw('COUNT(vuelto_vdc.id) as vueltos'))
                            ->groupBy('nombreCajeroFactura')
                            ->orderBy('vueltos', 'DESC')
                            ->get();
                    $mensaje=null;
                }
                else{
                    $cajeros=null;
                    $mensaje="No hay conexion con Grupo P (GP)";
                }
            break;
            case 'FTN':
                if(FG_Validar_Conectividad('FTN')==1){                        
                    $cajeros=VueltosFTN::where('sede','=',$sede)->fecha($fini, $ffin)
                            ->where('estatus','=','Aprobado')
                            ->select('nombreCajeroFactura','sede','id',DB::raw('COUNT(vuelto_vdc.id) as vueltos'))
                            ->groupBy('nombreCajeroFactura')
                            ->orderBy('vueltos', 'DESC')
                            ->get();
                    $mensaje=null;
                }
                else{
                    $cajeros=null;
                    $mensaje="No hay conexion con Farmacia Tierra Negra (FTN)";
                }
            break;
            case 'FLL':
                if(FG_Validar_Conectividad('FLL')==1){                        
                    $cajeros=VueltosFLL::where('sede','=',$sede)->fecha($fini, $ffin)
                            ->where('estatus','=','Aprobado')
                            ->select('nombreCajeroFactura','sede','id',DB::raw('COUNT(vuelto_vdc.id) as vueltos'))
                            ->groupBy('nombreCajeroFactura')
                            ->orderBy('vueltos', 'DESC')
                            ->get();
                    $mensaje=null;
                }
                else{
                    $cajeros=null;
                    $mensaje="No hay conexion con Farmacia La Lago (FLL)";
                }
            break;
            case 'FSM':
                if(FG_Validar_Conectividad('FSM')==1){                        
                    $cajeros=VueltosFM::where('sede','=',$sede)->fecha($fini, $ffin)
                            ->where('estatus','=','Aprobado')
                            ->select('nombreCajeroFactura','sede','id',DB::raw('COUNT(vuelto_vdc.id) as vueltos'))
                            ->groupBy('nombreCajeroFactura')
                            ->orderBy('vueltos', 'DESC')
                            ->get(); 
                    $mensaje=null;
                }
                else{
                    $cajeros=null;
                    $mensaje="No hay conexion con Farmacia Millenium 2000 (FSM)";
                }
            break;
            case 'KDI':
                if(FG_Validar_Conectividad('KDI')==1){                        
                    $cajeros=VueltosKDI::where('sede','=',$sede)->fecha($fini, $ffin)
                            ->where('estatus','=','Aprobado')
                            ->select('nombreCajeroFactura','sede','id',DB::raw('COUNT(vuelto_vdc.id) as vueltos'))
                            ->groupBy('nombreCajeroFactura')
                            ->orderBy('vueltos', 'DESC')
                            ->get(); 
                    $mensaje=null;
                }
                else{
                    $cajeros=null;
                    $mensaje="No hay conexion con Farmacia KD Express (KDI)";
                }
            break;
            case 'FEC':
                if(FG_Validar_Conectividad('FEC')==1){                        
                    $cajeros=VueltosFEC::where('sede','=',$sede)->fecha($fini, $ffin)
                            ->where('estatus','=','Aprobado')
                            ->select('nombreCajeroFactura','sede','id',DB::raw('COUNT(vuelto_vdc.id) as vueltos'))
                            ->groupBy('nombreCajeroFactura')
                            ->orderBy('vueltos', 'DESC')
                            ->get(); 
                    $mensaje=null;
                }
                else{
                    $cajeros=null;
                    $mensaje="No hay conexion con Farmacia El Callejon (FEC)";
                }
            break;
        }
               
        $arreglo=[];
        $i=0;
        foreach ($cajeros as $cajero) {
            if($cajero->nombreCajeroFactura!=null){
                
                switch($sede)
                {
                    case 'DBs':                                          
                            $total=VueltoVDC::where('sede','=',$sede)->fecha($fini, $ffin)
                                ->where('nombreCajeroFactura','=',$cajero->nombreCajeroFactura)
                                ->where('estatus','=','Aprobado')
                                ->get();
                            $mensaje=null;                    
                    break;
                    case 'FAU':
                        if(FG_Validar_Conectividad('FAU')==1){                        
                            $total=VueltoVDC::where('sede','=','FAU')->fecha($fini, $ffin)
                                            ->where('nombreCajeroFactura','=',$cajero->nombreCajeroFactura)
                                            ->where('estatus','=','Aprobado')
                                            ->get();
                            $mensaje=null;
                        }
                        else{
                            $total=null;
                            $mensaje="No hay conexion con Farmacia Avenida Universidad (FAU)";
                        }
                    break;
                    case 'GP':
                        if(FG_Validar_Conectividad('FAU')==1){
                            
                            $total=VueltoVDC::where('sede','=','FAU')->fecha($fini, $ffin)
                                            ->where('nombreCajeroFactura','=',$cajero->nombreCajeroFactura)
                                            ->where('estatus','=','Aprobado')
                                            ->get();
                            $mensaje=null;
                        }
                        else{
                            $total=null;
                            $mensaje="No hay conexion con Grupo P (GP)";
                        }
                    break;
                    case 'FTN':
                        if(FG_Validar_Conectividad('FTN')==1){                        
                            $total=VueltosFTN::where('sede','=','FTN')->fecha($fini, $ffin)
                                                ->where('nombreCajeroFactura','=',$cajero->nombreCajeroFactura)
                                                ->where('estatus','=','Aprobado')
                                                ->get();
                            $mensaje=null;
                        }
                        else{
                            $total=null;
                            $mensaje="No hay conexion con Farmacia Tierra Negra (FTN)";
                        }
                    break;
                    case 'FLL':
                        if(FG_Validar_Conectividad('FLL')==1){                        
                            $total=VueltosFLL::where('sede','=','FLL')->fecha($fini, $ffin)
                                            ->where('nombreCajeroFactura','=',$cajero->nombreCajeroFactura)
                                            ->where('estatus','=','Aprobado')
                                            ->get();
                            $mensaje=null;
                        }
                        else{
                            $total=null;
                            $mensaje="No hay conexion con Farmacia La Lago (FLL)";
                        }
                    break;
                    case 'FSM':
                        if(FG_Validar_Conectividad('FSM')==1){                        
                            $total=VueltosFM::where('sede','=','FSM')->fecha($fini, $ffin)
                                    ->where('nombreCajeroFactura','=',$cajero->nombreCajeroFactura)
                                    ->where('estatus','=','Aprobado')
                                    ->get();
                            $mensaje=null;
                        }
                        else{
                            $total=null;
                            $mensaje="No hay conexion con Farmacia Millenium 2000 (FSM)";
                        }
                    break;
                    case 'KDI':
                        if(FG_Validar_Conectividad('KDI')==1){                        
                            $total=VueltosKDI::where('sede','=','KDI')->fecha($fini, $ffin)
                                    ->where('nombreCajeroFactura','=',$cajero->nombreCajeroFactura)
                                    ->where('estatus','=','Aprobado')
                                    ->get();
                            $mensaje=null;
                        }
                        else{
                            $total=null;
                            $mensaje="No hay conexion con Farmacia KD Express (KDI)";
                        }
                    break;
                    case 'FEC':
                        if(FG_Validar_Conectividad('FEC')==1){                        
                            $total=VueltosFEC::where('sede','=','FEC')->fecha($fini, $ffin)
                                    ->where('nombreCajeroFactura','=',$cajero->nombreCajeroFactura)
                                    ->where('estatus','=','Aprobado')
                                    ->get();
                            $mensaje=null;
                        }
                        else{
                            $total=null;
                            $mensaje="No hay conexion con Farmacia El Callejon (FEC)";
                        }
                    break;
                }                
                $totalBsFactura=$total->sum('totalFacturaBs');
                $totalDolarFactura=$total->sum('totalFacturaDolar');                
                $totalPagadoBs=$total->sum('monto');
                $totalPagadoDolar=0;
                foreach ($total as $registro) {
                    $totalPagadoDolar=$totalPagadoDolar+($registro->monto/$registro->tasaVenta);
                }
               
                $i++;
                array_push( $arreglo,[
                    'registro' => $i,
                    'Sede' => $cajero->sede,                    
                    'nombreCajeroFactura' => $cajero->nombreCajeroFactura,
                    'TotalAcumuladoBs' => $totalBsFactura,
                    'TotalAcumuladoDolar' => $totalDolarFactura,
                    'TotalPagadoBs' => number_format($totalPagadoBs,2),
                    'TotalPagadoDolar' => number_format($totalPagadoDolar,2),
                    'cantidadPagoMovil' => $cajero->vueltos,                    
                ]);
            }
        }    
        
        return view('pages.vuelto.cajeros', compact('arreglo','sedeUsuario','sede','mensaje','fini','ffin'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function cajerosTransaccionalesError(Request $request)
    {       
        include app_path() . '/functions/config.php';
        include app_path() . '/functions/functions.php';

        if($request->fecha_ini>0){
            $fini=$request->fecha_ini;
            $ffin=$request->fecha_fin;
            
        }
        else{
            $fini=date("Y-m-d");
            $ffin=date("Y-m-d");            
        }    

        $sedeUsuario = Auth::user()->sede;

        switch($sedeUsuario){
            case "GRUPO P, C.A":
                if ($_SERVER['SERVER_NAME'] == 'cpharmagpde.com' ) {
                    $RutaUrl = "DBs";
                }
                else{
                    $RutaUrl = "FAU";
                }    
            break;
            case "FARMACIA AVENIDA UNIVERSIDAD, C.A.":
                if ($_SERVER['SERVER_NAME'] == 'cpharmagpde.com' ) {
                    $RutaUrl = "DBs";
                }
                else{
                    $RutaUrl = "FAU";
                }    
            break;
            case "FARMACIA TIERRA NEGRA, C.A.":
                $RutaUrl = "FTN";
            break;
            case "FARMACIA MILLENNIUM 2000, C.A":
                $RutaUrl = "FSM";
            break;
            case "FARMACIA LA LAGO,C.A.":
                $RutaUrl = "FLL";
            break;
            case "FARMACIAS KD EXPRESS, C.A.":
                $RutaUrl = "KDI";
            break;
            case "FARMACIAS KD EXPRESS, C.A. - KD73":
                $RutaUrl = "KD73";
            break;
            case "FARMACIA EL CALLEJON, C.A.":
                $RutaUrl = "FEC";
            break;

        }        

        if($request->sede!=null){
            if($request->sede!="Seleccione una sede"){
                if ($_SERVER['SERVER_NAME'] == 'cpharmagpde.com' ) {
                    if($request->sede=="FAU"){
                        $RutaUrl='DBs';
                    }
                    else{
                        $RutaUrl=$request->sede;
                    }
                }
                else{
                    $RutaUrl=$request->sede;
                }
            }
            else{
                $RutaUrl=$RutaUrl;
            }
        }

        $SedeConnection = $RutaUrl;
        $sede = $RutaUrl;
        
       
        switch($sede)
        {
            case 'DBs':                                          
                $cajeros=VueltoVDC::where('sede','=',$sede)->fecha($fini, $ffin)
                            ->where('estatus','=','Error')
                            ->select('nombreCajeroFactura','sede','id',DB::raw('COUNT(vuelto_vdc.id) as vueltos'))
                            ->groupBy('nombreCajeroFactura')
                            ->orderBy('vueltos', 'DESC')
                            ->get();
                    $mensaje=null;                    
            break;
            case 'FAU':
                if(FG_Validar_Conectividad('FAU')==1){                        
                    $cajeros=VueltoVDC::where('sede','=',$sede)->fecha($fini, $ffin)
                            ->where('estatus','=','Error')
                            ->select('nombreCajeroFactura','sede','id',DB::raw('COUNT(vuelto_vdc.id) as vueltos'))
                            ->groupBy('nombreCajeroFactura')
                            ->orderBy('vueltos', 'DESC')
                            ->get();
                    $mensaje=null;
                }
                else{
                    $cajeros=null;
                    $mensaje="No hay conexion con Farmacia Avenida Universidad (FAU)";
                }
            break;
            case 'GP':
                if(FG_Validar_Conectividad('FAU')==1){
                    
                    $cajeros=VueltoVDC::where('sede','=',$sede)->fecha($fini, $ffin)
                            ->where('estatus','=','Error')
                            ->select('nombreCajeroFactura','sede','id',DB::raw('COUNT(vuelto_vdc.id) as vueltos'))
                            ->groupBy('nombreCajeroFactura')
                            ->orderBy('vueltos', 'DESC')
                            ->get();
                    $mensaje=null;
                }
                else{
                    $cajeros=null;
                    $mensaje="No hay conexion con Grupo P (GP)";
                }
            break;
            case 'FTN':
                if(FG_Validar_Conectividad('FTN')==1){                        
                    $cajeros=VueltosFTN::where('sede','=',$sede)->fecha($fini, $ffin)
                            ->where('estatus','=','Error')
                            ->select('nombreCajeroFactura','sede','id',DB::raw('COUNT(vuelto_vdc.id) as vueltos'))
                            ->groupBy('nombreCajeroFactura')
                            ->orderBy('vueltos', 'DESC')
                            ->get();
                    $mensaje=null;
                }
                else{
                    $cajeros=null;
                    $mensaje="No hay conexion con Farmacia Tierra Negra (FTN)";
                }
            break;
            case 'FLL':
                if(FG_Validar_Conectividad('FLL')==1){                        
                    $cajeros=VueltosFLL::where('sede','=',$sede)->fecha($fini, $ffin)
                            ->where('estatus','=','Error')
                            ->select('nombreCajeroFactura','sede','id',DB::raw('COUNT(vuelto_vdc.id) as vueltos'))
                            ->groupBy('nombreCajeroFactura')
                            ->orderBy('vueltos', 'DESC')
                            ->get();
                    $mensaje=null;
                }
                else{
                    $cajeros=null;
                    $mensaje="No hay conexion con Farmacia La Lago (FLL)";
                }
            break;
            case 'FSM':
                if(FG_Validar_Conectividad('FSM')==1){                        
                    $cajeros=VueltosFM::where('sede','=',$sede)->fecha($fini, $ffin)
                            ->where('estatus','=','Error')
                            ->select('nombreCajeroFactura','sede','id',DB::raw('COUNT(vuelto_vdc.id) as vueltos'))
                            ->groupBy('nombreCajeroFactura')
                            ->orderBy('vueltos', 'DESC')
                            ->get(); 
                    $mensaje=null;
                }
                else{
                    $cajeros=null;
                    $mensaje="No hay conexion con Farmacia Millenium 2000 (FSM)";
                }
            break;
            case 'KDI':
                if(FG_Validar_Conectividad('KDI')==1){                        
                    $cajeros=VueltosKDI::where('sede','=',$sede)->fecha($fini, $ffin)
                            ->where('estatus','=','Error')
                            ->select('nombreCajeroFactura','sede','id',DB::raw('COUNT(vuelto_vdc.id) as vueltos'))
                            ->groupBy('nombreCajeroFactura')
                            ->orderBy('vueltos', 'DESC')
                            ->get(); 
                    $mensaje=null;
                }
                else{
                    $cajeros=null;
                    $mensaje="No hay conexion con Farmacia KD Express (KDI)";
                }
            break;
            case 'FEC':
                if(FG_Validar_Conectividad('FEC')==1){                        
                    $cajeros=VueltosFEC::where('sede','=',$sede)->fecha($fini, $ffin)
                            ->where('estatus','=','Error')
                            ->select('nombreCajeroFactura','sede','id',DB::raw('COUNT(vuelto_vdc.id) as vueltos'))
                            ->groupBy('nombreCajeroFactura')
                            ->orderBy('vueltos', 'DESC')
                            ->get(); 
                    $mensaje=null;
                }
                else{
                    $cajeros=null;
                    $mensaje="No hay conexion con Farmacia El Callejon (FEC)";
                }
            break;
        }
               
        $arreglo=[];
        $i=0;
        foreach ($cajeros as $cajero) {
            if($cajero->nombreCajeroFactura!=null){
                
                switch($sede)
                {
                    case 'DBs':                                          
                            $total=VueltoVDC::where('sede','=',$sede)->fecha($fini, $ffin)
                                ->where('nombreCajeroFactura','=',$cajero->nombreCajeroFactura)
                                ->where('estatus','=','Error')
                                ->get();
                            $mensaje=null;                    
                    break;
                    case 'FAU':
                        if(FG_Validar_Conectividad('FAU')==1){                        
                            $total=VueltoVDC::where('sede','=','FAU')->fecha($fini, $ffin)
                                            ->where('nombreCajeroFactura','=',$cajero->nombreCajeroFactura)
                                            ->where('estatus','=','Error')
                                            ->get();
                            $mensaje=null;
                        }
                        else{
                            $total=null;
                            $mensaje="No hay conexion con Farmacia Avenida Universidad (FAU)";
                        }
                    break;
                    case 'GP':
                        if(FG_Validar_Conectividad('FAU')==1){
                            
                            $total=VueltoVDC::where('sede','=','FAU')->fecha($fini, $ffin)
                                            ->where('nombreCajeroFactura','=',$cajero->nombreCajeroFactura)
                                            ->where('estatus','=','Error')
                                            ->get();
                            $mensaje=null;
                        }
                        else{
                            $total=null;
                            $mensaje="No hay conexion con Grupo P (GP)";
                        }
                    break;
                    case 'FTN':
                        if(FG_Validar_Conectividad('FTN')==1){                        
                            $total=VueltosFTN::where('sede','=','FTN')->fecha($fini, $ffin)
                                                ->where('nombreCajeroFactura','=',$cajero->nombreCajeroFactura)
                                                ->where('estatus','=','Error')
                                                ->get();
                            $mensaje=null;
                        }
                        else{
                            $total=null;
                            $mensaje="No hay conexion con Farmacia Tierra Negra (FTN)";
                        }
                    break;
                    case 'FLL':
                        if(FG_Validar_Conectividad('FLL')==1){                        
                            $total=VueltosFLL::where('sede','=','FLL')->fecha($fini, $ffin)
                                            ->where('nombreCajeroFactura','=',$cajero->nombreCajeroFactura)
                                            ->where('estatus','=','Error')
                                            ->get();
                            $mensaje=null;
                        }
                        else{
                            $total=null;
                            $mensaje="No hay conexion con Farmacia La Lago (FLL)";
                        }
                    break;
                    case 'FSM':
                        if(FG_Validar_Conectividad('FSM')==1){                        
                            $total=VueltosFM::where('sede','=','FSM')->fecha($fini, $ffin)
                                    ->where('nombreCajeroFactura','=',$cajero->nombreCajeroFactura)
                                    ->where('estatus','=','Error')
                                    ->get();
                            $mensaje=null;
                        }
                        else{
                            $total=null;
                            $mensaje="No hay conexion con Farmacia Millenium 2000 (FSM)";
                        }
                    break;
                    case 'KDI':
                        if(FG_Validar_Conectividad('KDI')==1){                        
                            $total=VueltosKDI::where('sede','=','KDI')->fecha($fini, $ffin)
                                    ->where('nombreCajeroFactura','=',$cajero->nombreCajeroFactura)
                                    ->where('estatus','=','Error')
                                    ->get();
                            $mensaje=null;
                        }
                        else{
                            $total=null;
                            $mensaje="No hay conexion con Farmacia KD Express (KDI)";
                        }
                    break;
                    case 'FEC':
                        if(FG_Validar_Conectividad('FEC')==1){                        
                            $total=VueltosFEC::where('sede','=','FEC')->fecha($fini, $ffin)
                                    ->where('nombreCajeroFactura','=',$cajero->nombreCajeroFactura)
                                    ->where('estatus','=','Error')
                                    ->get();
                            $mensaje=null;
                        }
                        else{
                            $total=null;
                            $mensaje="No hay conexion con Farmacia El Callejon (FEC)";
                        }
                    break;
                }                
                $totalBsFactura=$total->sum('totalFacturaBs');
                $totalDolarFactura=$total->sum('totalFacturaDolar');                
                $totalPagadoBs=$total->sum('monto');
                $totalPagadoDolar=0;
                foreach ($total as $registro) {
                    $totalPagadoDolar=$totalPagadoDolar+($registro->monto/$registro->tasaVenta);
                }
               
                $i++;
                array_push( $arreglo,[
                    'registro' => $i,
                    'Sede' => $cajero->sede,                    
                    'nombreCajeroFactura' => $cajero->nombreCajeroFactura,
                    'TotalAcumuladoBs' => $totalBsFactura,
                    'TotalAcumuladoDolar' => $totalDolarFactura,
                    'TotalPagadoBs' => number_format($totalPagadoBs,2),
                    'TotalPagadoDolar' => number_format($totalPagadoDolar,2),
                    'cantidadPagoMovil' => $cajero->vueltos,                    
                ]);
            }
        }    
        
        return view('pages.vuelto.cajerosError', compact('arreglo','sedeUsuario','sede','mensaje','fini','ffin'));
    }

    /**
     * Detalles de pago movil de clientes
     *
     * @return \Illuminate\Http\Response
     */
    public function detalleClientes(Request $request)
    {
        include app_path() . '/functions/config.php';
        include app_path() . '/functions/functions.php';

        if($request->fecha_ini>0){
            $fini=$request->fecha_ini;
            $ffin=$request->fecha_fin;
            
        }
        else{
            $fini=date("Y-m-d");
            $ffin=date("Y-m-d");            
        }
        //detectar la sede del usuario
        $sedeUsuario = Auth::user()->sede;
        
        //switch para retornar las siglas de conexion en base a la sede del usuario
        switch($sedeUsuario){                
            case "GRUPO P, C.A":
                if ($_SERVER['SERVER_NAME'] == 'cpharmagpde.com' ) {
                    $RutaUrl = "DBs";
                }
                else{
                    $RutaUrl = "FAU";
                }    
            break;
            case "FARMACIA AVENIDA UNIVERSIDAD, C.A.":
                if ($_SERVER['SERVER_NAME'] == 'cpharmagpde.com' ) {
                    $RutaUrl = "DBs";
                }
                else{
                    $RutaUrl = "FAU";
                }    
            break;
            case "FARMACIA TIERRA NEGRA, C.A.":
                $RutaUrl = "FTN";
            break;
            case "FARMACIA MILLENNIUM 2000, C.A":
                $RutaUrl = "FSM";
            break;
            case "FARMACIA LA LAGO,C.A.":
                $RutaUrl = "FLL";
            break;
            case "FARMACIAS KD EXPRESS, C.A.":
                $RutaUrl = "KDI";
            break;
            case "FARMACIAS KD EXPRESS, C.A. - KD73":
                $RutaUrl = "KD73";
            break;
            case "FARMACIA EL CALLEJON, C.A.":
                $RutaUrl = "FEC";
            break;
        }  

        //si se selecciono otra sede consultarla
        if($request->sede!=null){
            if($request->sede!="Seleccione una sede"){
                if ($_SERVER['SERVER_NAME'] == 'cpharmagpde.com' ) {
                    if($request->sede=="FAU"){
                        $RutaUrl='DBs';
                    }
                    else{
                        $RutaUrl=$request->sede;
                    }
                }
                else{
                    $RutaUrl=$request->sede;
                }
            }
            else{
                $RutaUrl=$RutaUrl;
            }
        }
        //$RutaUrl = 'DBs';
        $SedeConnection = $RutaUrl;
        $sede = $RutaUrl;       
              
        $conn = FG_Conectar_Smartpharma($SedeConnection);        
        $registro=[];       
        $historialvueltos=collect();        
            
        switch($sede)
        {
            case 'DBs':                                          
                $clientes=VueltoVDC::where('sede','=',$sede)->fecha($fini, $ffin)
                        ->where('estatus','=','Aprobado')                    
                        ->where('cedulaClienteFactura','=',$request->cliente)
                        ->OrderBy("id",'desc')                                                                            
                        ->get();
                $mensaje=null;                 
            break;
            case 'FAU':
                if(FG_Validar_Conectividad('FAU')==1){                        
                    $clientes=VueltoVDC::where('sede','=',$sede)->fecha($fini, $ffin)
                                        ->where('estatus','=','Aprobado')                    
                                        ->where('cedulaClienteFactura','=',$request->cliente) 
                                        ->OrderBy("id",'desc')                                                                           
                                        ->get();
                    $mensaje=null;
                    
                }
                else{
                    $clientes=null;
                    $mensaje="No hay conexion con Farmacia Avenida Universidad (FAU)";
                }
            break;
            case 'GP':
                if(FG_Validar_Conectividad('FAU')==1){
                    $clientes=VueltoVDC::where('sede','=',$sede)->fecha($fini, $ffin)
                                        ->where('estatus','=','Aprobado')                    
                                        ->where('cedulaClienteFactura','=',$request->cliente) 
                                        ->OrderBy("id",'desc')                                                                           
                                        ->get();
                    $mensaje=null;
                }
                else{
                    $clientes=null;
                    $mensaje="No hay conexion con Grupo P (GP)";
                }
            break;
            case 'FTN':
                if(FG_Validar_Conectividad('FTN')==1){
                    $clientes=VueltosFTN::where('sede','=',$sede)->fecha($fini, $ffin)
                                        ->where('estatus','=','Aprobado')                    
                                        ->where('cedulaClienteFactura','=',$request->cliente)  
                                        ->OrderBy("id",'desc')                                                                          
                                        ->get();
                    $mensaje=null;
                }
                else{
                    $clientes=null;
                    $mensaje="No hay conexion con Farmacia Tierra Negra (FTN)";
                }
            break;
            case 'FLL':
                if(FG_Validar_Conectividad('FLL')==1){                                               
                    $clientes=VueltosFLL::where('sede','=',$sede)->fecha($fini, $ffin)
                                        ->where('estatus','=','Aprobado')                    
                                        ->where('cedulaClienteFactura','=',$request->cliente)
                                        ->OrderBy("id",'desc')                                                                            
                                        ->get();
                    $mensaje=null;
                }
                else{
                    $clientes=null;
                    $mensaje="No hay conexion con Farmacia La Lago (FLL)";
                }
            break;
            case 'FSM':
                if(FG_Validar_Conectividad('FSM')==1){                        
                    $clientes=VueltosFM::where('sede','=',$sede)->fecha($fini, $ffin)
                                        ->where('estatus','=','Aprobado')                    
                                        ->where('cedulaClienteFactura','=',$request->cliente)  
                                        ->OrderBy("id",'desc')                                                                          
                                        ->get();
                    $mensaje=null;
                }
                else{
                    $clientes=null;
                    $mensaje="No hay conexion con Farmacia Millenium 2000 (FSM)";
                }
            break;
            case 'KDI':
                if(FG_Validar_Conectividad('KDI')==1){                                                
                    $clientes=VueltosKDI::where('sede','=',$sede)->fecha($fini, $ffin)
                                        ->where('estatus','=','Aprobado')                    
                                        ->where('cedulaClienteFactura','=',$request->cliente) 
                                        ->OrderBy("id",'desc')                                                                           
                                        ->get();
                    $mensaje=null;
                }
                else{
                    $clientes=null;
                    $mensaje="No hay conexion con Farmacia KD Express (KDI)";
                }
            break;
            case 'FEC':
                if(FG_Validar_Conectividad('FEC')==1){   
                    $clientes=VueltosFEC::where('sede','=',$sede)->fecha($fini, $ffin)
                                        ->where('estatus','=','Aprobado')                    
                                        ->where('cedulaClienteFactura','=',$request->cliente) 
                                        ->OrderBy("id",'desc')                                                                           
                                        ->get();                             
                    $mensaje=null;
                }
                else{
                    $clientes=null;
                    $mensaje="No hay conexion con Farmacia El Callejon (FEC)";
                }
            break;
        }            
        
        if($clientes!=null){
            foreach($clientes as $cliente){
                
                $idFactura=$cliente->id_factura;

                //Consulta de la factura
                $sql = "
                        SELECT
                            TOP 1 vvp.VenVentaId,
                            f.NumeroFactura,
                            f.Auditoria_Usuario,
                            f.FechaDocumento,
                            ca.EstacionTrabajo,
                            cl.CodigoCliente,
                            CONCAT(g.Nombre, ' ', g.Apellido) AS nombre,
                            g.Telefono,
                            f.M_MontoTotalFactura as totalFactura,
                            vvp.MontoPagado,
                            vvp.MontoRecibido
                        FROM
                            VenFactura f
                            LEFT JOIN VenCaja vca ON f.VenCajaId =  vca.Id
                            LEFT JOIN VenCaja ca ON f.VenCajaId = ca.Id
                            LEFT JOIN VenCliente cl ON f.VenClienteId = cl.Id
                            LEFT JOIN GenPersona g ON g.Id = cl.GenPersonaId
                            LEFT JOIN VenVentaPago vvp ON vvp.VenVentaId = f.VenVentaId
                        WHERE
                            f.Id = ".$idFactura."
                        ORDER BY
                            f.Id DESC
                    ";

                $result = sqlsrv_query($conn, $sql);
                $row = sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC);

                //Busqueda de devoluciones de la factura
                $sql2="
                    SELECT TOP 1
                        vd.Id,
                        vd.VenFacturaId,
                        vd.ConsecutivoDevolucionSistema,
                        vd.Auditoria_FechaCreacion,
                        vd.FechaDocumento,
                        vca.EstacionTrabajo,
                        vd.Auditoria_Usuario
                    FROM VenDevolucion vd
                    LEFT JOIN VenCaja vca ON vca.Id =  vd.VenCajaId
                    WHERE
                                    vd.VenFacturaId = ".$idFactura."
                ";
                $result2 = sqlsrv_query($conn, $sql2);
                $devolucion = sqlsrv_fetch_array($result2, SQLSRV_FETCH_ASSOC);

                if($devolucion["ConsecutivoDevolucionSistema"]>0){
                    $fechaDevolucion=$devolucion["FechaDocumento"]->format('Y-m-d H:i:s');
                }
                else{
                    $fechaDevolucion="";
                }
                if($cliente->montoPagado>0){
                    $montoPagadoFactura=$cliente->montoPagado;
                }
                else{
                    $montoPagadoFactura=0;
                }

                if($cliente->tasaVenta>0){
                    
                    $totalFacturaDolar=number_format($row["totalFactura"]/$cliente->tasaVenta,2);
                    $montoPagoMovilDolar=number_format($cliente->monto/$cliente->tasaVenta,2);
                    if($montoPagadoFactura>0){
                        $montoPagadoFacturaDolar=number_format($montoPagadoFactura/$cliente->tasaVenta,2);
                    }
                    else{
                        $montoPagadoFactura=0;
                    }
                }   
                else{
                    $totalFacturaDolar=0;
                    $montoPagoMovilDolar=0;
                    $montoPagadoFactura=0;
                }

                $registro = [
                    'sede'=>$cliente->sede,
                    'cedula_cliente_factura' =>$row["CodigoCliente"],
                    'telefono_cliente_factura' =>$row["Telefono"],
                    'fecha_hora_factura'=>$row['FechaDocumento']->format('Y-m-d H:i:s'),
                    'cajero_venta' =>$row["Auditoria_Usuario"],                
                    'numero_factura' => $row["NumeroFactura"] ,
                    'nombre_cliente'=>$row["nombre"] ,
                    'total_factura'=>$row["totalFactura"] ,
                    'total_factura_dolar' => $totalFacturaDolar ,
                    'monto_pagado_factura' => $montoPagadoFactura,
                    'monto_pagado_factura_dolar' => $montoPagadoFacturaDolar,
                    'id'=>$cliente->id,  
                    'id_factura'=> $cliente->id_factura,  
                    'fecha_hora'=> $cliente->fecha_hora,  
                    'banco_cliente'=> $cliente->banco_cliente,  
                    'cedula_cliente'=> $cliente->cedula_cliente,  
                    'telefono_pago_movil'=> $cliente->telefono_cliente,  
                    'monto' => $cliente->monto,
                    'monto_dolar' => $montoPagoMovilDolar,
                    'sede'=> $cliente->sede,  
                    'caja'=> $cliente->caja,  
                    'estatus'=> $cliente->estatus,  
                    'confirmacion_banco'=> $cliente->confirmacion_banco,  
                    'motivo_error'=> $cliente->motivo_error,
                    'nro_devolucion' =>$devolucion["ConsecutivoDevolucionSistema"],
                    'fecha_devolucion' => $fechaDevolucion,
                    'caja_devolucion' =>$devolucion["EstacionTrabajo"],
                    'cajero_devolucion' =>$devolucion["Auditoria_Usuario"]  
                ];           
                
                $coleccion=collect($registro);
                $historialvueltos->push($coleccion);
            }    
        } 
        else{
            $historialvueltos=null;
        }            
                
        $cliente=$request->cliente;
        
        return view('pages.vuelto.detalleCliente',compact('cliente','historialvueltos','mensaje','sedeUsuario','sede','fini','ffin'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function detalleCajeros(Request $request)
    { 
        include app_path() . '/functions/config.php';
        include app_path() . '/functions/functions.php';

        if($request->fecha_ini>0){
            $fini=$request->fecha_ini;
            $ffin=$request->fecha_fin;            
        }
        else{
            $fini=date("Y-m-d");
            $ffin=date("Y-m-d");            
        }

        //detectar la sede del usuario
        $sedeUsuario = Auth::user()->sede;
        
        //switch para retornar las siglas de conexion en base a la sede del usuario
        switch($sedeUsuario){
            case "GRUPO P, C.A":
                if ($_SERVER['SERVER_NAME'] == 'cpharmagpde.com' ) {
                    $RutaUrl = "DBs";
                }
                else{
                    $RutaUrl = "FAU";
                }    
            break;
            case "FARMACIA AVENIDA UNIVERSIDAD, C.A.":
                if ($_SERVER['SERVER_NAME'] == 'cpharmagpde.com' ) {
                    $RutaUrl = "DBs";
                }
                else{
                    $RutaUrl = "FAU";
                }    
            break;
            case "FARMACIA TIERRA NEGRA, C.A.":
                $RutaUrl = "FTN";
            break;
            case "FARMACIA MILLENNIUM 2000, C.A":
                $RutaUrl = "FSM";
            break;
            case "FARMACIA LA LAGO,C.A.":
                $RutaUrl = "FLL";
            break;
            case "FARMACIAS KD EXPRESS, C.A.":
                $RutaUrl = "KDI";
            break;
            case "FARMACIAS KD EXPRESS, C.A. - KD73":
                $RutaUrl = "KD73";
            break;
            case "FARMACIA EL CALLEJON, C.A.":
                $RutaUrl = "FEC";
            break;

        }  

        //si se selecciono otra sede consultarla
        if($request->sede!=null){
            if($request->sede!="Seleccione una sede"){
                if ($_SERVER['SERVER_NAME'] == 'cpharmagpde.com' ) {
                    if($request->sede=="FAU"){
                        $RutaUrl='DBs';
                    }
                    else{
                        $RutaUrl=$request->sede;
                    }
                }
                else{
                    $RutaUrl=$request->sede;
                }
            }
            else{
                $RutaUrl=$RutaUrl;
            }
        }

        //$RutaUrl = 'DBs';
        $SedeConnection = $RutaUrl;
        $sede = $RutaUrl;
                      
        $conn = FG_Conectar_Smartpharma($SedeConnection);        
        $registro=[];       
        $historialvueltos=collect();
        
        switch($sede)
        {
            case 'DBs':                                          
                $cajeros=VueltoVDC::where('sede','=',$sede)->fecha($fini, $ffin)
                        ->where('estatus','=','Aprobado')                    
                        ->where('nombreCajeroFactura','=',$request->cajero)  
                        ->OrderBy("id",'desc')                                                                          
                        ->get();
                $mensaje=null;                 
            break;
            case 'FAU':
                if(FG_Validar_Conectividad('FAU')==1){                        
                    $cajeros=VueltoVDC::where('sede','=',$sede)->fecha($fini, $ffin)
                            ->where('estatus','=','Aprobado')                    
                            ->where('nombreCajeroFactura','=',$request->cajero)   
                            ->OrderBy("id",'desc')                                                                         
                            ->get();
                    $mensaje=null;
                    
                }
                else{
                    $cajeros=null;
                    $mensaje="No hay conexion con Farmacia Avenida Universidad (FAU)";
                }
            break;
            case 'GP':
                if(FG_Validar_Conectividad('FAU')==1){
                    $cajeros=VueltoVDC::where('sede','=',$sede)->fecha($fini, $ffin)
                            ->where('estatus','=','Aprobado')                    
                            ->where('nombreCajeroFactura','=',$request->cajero)  
                            ->OrderBy("id",'desc')                                                                          
                            ->get();
                    $mensaje=null;
                }
                else{
                    $cajeros=null;
                    $mensaje="No hay conexion con Grupo P (GP)";
                }
            break;
            case 'FTN':
                if(FG_Validar_Conectividad('FTN')==1){
                    $cajeros=VueltosFTN::where('sede','=',$sede)->fecha($fini, $ffin)
                            ->where('estatus','=','Aprobado')                    
                            ->where('nombreCajeroFactura','=',$request->cajero)  
                            ->OrderBy("id",'desc')                                                                          
                            ->get();
                    $mensaje=null;
                }
                else{
                    $cajeros=null;
                    $mensaje="No hay conexion con Farmacia Tierra Negra (FTN)";
                }
            break;
            case 'FLL':
                if(FG_Validar_Conectividad('FLL')==1){                                               
                    $cajeros=VueltosFLL::where('sede','=',$sede)->fecha($fini, $ffin)
                            ->where('estatus','=','Aprobado')                    
                            ->where('nombreCajeroFactura','=',$request->cajero)  
                            ->OrderBy("id",'desc')                                                                          
                            ->get();
                    $mensaje=null;
                }
                else{
                    $cajeros=null;
                    $mensaje="No hay conexion con Farmacia La Lago (FLL)";
                }
            break;
            case 'FSM':
                if(FG_Validar_Conectividad('FSM')==1){                        
                    $cajeros=VueltosFM::where('sede','=',$sede)->fecha($fini, $ffin)
                            ->where('estatus','=','Aprobado')                    
                            ->where('nombreCajeroFactura','=',$request->cajero)  
                            ->OrderBy("id",'desc')                                                                          
                            ->get();
                    $mensaje=null;
                }
                else{
                    $cajeros=null;
                    $mensaje="No hay conexion con Farmacia Millenium 2000 (FSM)";
                }
            break;
            case 'KDI':
                if(FG_Validar_Conectividad('KDI')==1){                                                
                    $cajeros=VueltosKDI::where('sede','=',$sede)->fecha($fini, $ffin)
                            ->where('estatus','=','Aprobado')                    
                            ->where('nombreCajeroFactura','=',$request->cajero)    
                            ->OrderBy("id",'desc')                                                                        
                            ->get();
                    $mensaje=null;
                }
                else{
                    $cajeros=null;
                    $mensaje="No hay conexion con Farmacia KD Express (KDI)";
                }
            break;
            case 'FEC':
                if(FG_Validar_Conectividad('FEC')==1){   
                    $cajeros=VueltosFEC::where('sede','=',$sede)->fecha($fini, $ffin)
                            ->where('estatus','=','Aprobado')                    
                            ->where('nombreCajeroFactura','=',$request->cajero) 
                            ->OrderBy("id",'desc')                                                                           
                            ->get();                            
                    $mensaje=null;
                }
                else{
                    $cajeros=null;
                    $mensaje="No hay conexion con Farmacia El Callejon (FEC)";
                }
            break;
        } 
            
        if($cajeros!=null){
            foreach($cajeros as $cajero){
                
                $idFactura=$cajero->id_factura;

                //Consulta de la factura
                $sql = "
                        SELECT
                            TOP 1 vvp.VenVentaId,
                            f.NumeroFactura,
                            f.Auditoria_Usuario,
                            f.FechaDocumento,
                            ca.EstacionTrabajo,
                            cl.CodigoCliente,
                            CONCAT(g.Nombre, ' ', g.Apellido) AS nombre,
                            g.Telefono,
                            f.M_MontoTotalFactura as totalFactura,
                            vvp.MontoPagado,
                            vvp.MontoRecibido
                        FROM
                            VenFactura f
                            LEFT JOIN VenCaja vca ON f.VenCajaId =  vca.Id
                            LEFT JOIN VenCaja ca ON f.VenCajaId = ca.Id
                            LEFT JOIN VenCliente cl ON f.VenClienteId = cl.Id
                            LEFT JOIN GenPersona g ON g.Id = cl.GenPersonaId
                            LEFT JOIN VenVentaPago vvp ON vvp.VenVentaId = f.VenVentaId
                        WHERE
                            f.Id = ".$idFactura."
                        ORDER BY
                            f.Id DESC
                    ";

                $result = sqlsrv_query($conn, $sql);
                $row = sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC);

                //Busqueda de devoluciones de la factura
                $sql2="
                    SELECT TOP 1
                        vd.Id,
                        vd.VenFacturaId,
                        vd.ConsecutivoDevolucionSistema,
                        vd.Auditoria_FechaCreacion,
                        vd.FechaDocumento,
                        vca.EstacionTrabajo,
                        vd.Auditoria_Usuario
                    FROM VenDevolucion vd
                    LEFT JOIN VenCaja vca ON vca.Id =  vd.VenCajaId
                    WHERE
                                    vd.VenFacturaId = ".$idFactura."
                ";
                $result2 = sqlsrv_query($conn, $sql2);
                $devolucion = sqlsrv_fetch_array($result2, SQLSRV_FETCH_ASSOC);

                if($devolucion["ConsecutivoDevolucionSistema"]>0){
                    $fechaDevolucion=$devolucion["FechaDocumento"]->format('Y-m-d H:i:s');
                }
                else{
                    $fechaDevolucion="";
                }
                if($cajero->montoPagado>0){
                    $montoPagadoFactura=$cajero->montoPagado;
                }
                else{
                    $montoPagadoFactura=0;
                }

                if($cajero->tasaVenta>0){
                    
                    $totalFacturaDolar=number_format($row["totalFactura"]/$cajero->tasaVenta,2);
                    $montoPagoMovilDolar=number_format($cajero->monto/$cajero->tasaVenta,2);
                    if($montoPagadoFactura>0){
                        $montoPagadoFacturaDolar=number_format($montoPagadoFactura/$cajero->tasaVenta,2);
                    }
                    else{
                        $montoPagadoFactura=0;
                    }
                }   
                else{
                    $totalFacturaDolar=0;
                    $montoPagoMovilDolar=0;
                    $montoPagadoFactura=0;
                }

                $registro = [
                    'sede'=>$cajero->sede,
                    'cedula_cliente_factura' =>$row["CodigoCliente"],
                    'telefono_cliente_factura' =>$row["Telefono"],
                    'fecha_hora_factura'=>$row['FechaDocumento']->format('Y-m-d H:i:s'),
                    'cajero_venta' =>$row["Auditoria_Usuario"],                
                    'numero_factura' => $row["NumeroFactura"] ,
                    'nombre_cliente'=>$row["nombre"] ,
                    'total_factura'=>$row["totalFactura"] ,
                    'total_factura_dolar' => $totalFacturaDolar ,
                    'monto_pagado_factura' => $montoPagadoFactura,
                    'monto_pagado_factura_dolar' => $montoPagadoFacturaDolar,
                    'id'=>$cajero->id,  
                    'id_factura'=> $cajero->id_factura,  
                    'fecha_hora'=> $cajero->fecha_hora,  
                    'banco_cliente'=> $cajero->banco_cliente,  
                    'cedula_cliente'=> $cajero->cedula_cliente,  
                    'telefono_pago_movil'=> $cajero->telefono_cliente,  
                    'monto' => $cajero->monto,
                    'monto_dolar' => $montoPagoMovilDolar,
                    'sede'=> $cajero->sede,  
                    'caja'=> $cajero->caja,  
                    'estatus'=> $cajero->estatus,  
                    'confirmacion_banco'=> $cajero->confirmacion_banco,  
                    'motivo_error'=> $cajero->motivo_error,
                    'nro_devolucion' =>$devolucion["ConsecutivoDevolucionSistema"],
                    'fecha_devolucion' => $fechaDevolucion,
                    'caja_devolucion' =>$devolucion["EstacionTrabajo"],
                    'cajero_devolucion' =>$devolucion["Auditoria_Usuario"]  
                ];           
                
                $coleccion=collect($registro);
                $historialvueltos->push($coleccion);
            }    
        } 
        else{
            $historialvueltos=null;
        }            
                
        $cajero=$request->cajero;
        

        return view('pages.vuelto.detalleCajero',compact('cajero','historialvueltos','mensaje','sedeUsuario','sede','fini','ffin'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function detalleCajerosErrores(Request $request)
    { 
        include app_path() . '/functions/config.php';
        include app_path() . '/functions/functions.php';

        if($request->fecha_ini>0){
            $fini=$request->fecha_ini;
            $ffin=$request->fecha_fin;            
        }
        else{
            $fini=date("Y-m-d");
            $ffin=date("Y-m-d");            
        }

        //detectar la sede del usuario
        $sedeUsuario = Auth::user()->sede;
        
        //switch para retornar las siglas de conexion en base a la sede del usuario
        switch($sedeUsuario){
            case "GRUPO P, C.A":
                if ($_SERVER['SERVER_NAME'] == 'cpharmagpde.com' ) {
                    $RutaUrl = "DBs";
                }
                else{
                    $RutaUrl = "FAU";
                }    
            break;
            case "FARMACIA AVENIDA UNIVERSIDAD, C.A.":
                if ($_SERVER['SERVER_NAME'] == 'cpharmagpde.com' ) {
                    $RutaUrl = "DBs";
                }
                else{
                    $RutaUrl = "FAU";
                }    
            break;
            case "FARMACIA TIERRA NEGRA, C.A.":
                $RutaUrl = "FTN";
            break;
            case "FARMACIA MILLENNIUM 2000, C.A":
                $RutaUrl = "FSM";
            break;
            case "FARMACIA LA LAGO,C.A.":
                $RutaUrl = "FLL";
            break;
            case "FARMACIAS KD EXPRESS, C.A.":
                $RutaUrl = "KDI";
            break;
            case "FARMACIAS KD EXPRESS, C.A. - KD73":
                $RutaUrl = "KD73";
            break;
            case "FARMACIA EL CALLEJON, C.A.":
                $RutaUrl = "FEC";
            break;

        }  

        //si se selecciono otra sede consultarla
        if($request->sede!=null){
            if($request->sede!="Seleccione una sede"){
                if ($_SERVER['SERVER_NAME'] == 'cpharmagpde.com' ) {
                    if($request->sede=="FAU"){
                        $RutaUrl='DBs';
                    }
                    else{
                        $RutaUrl=$request->sede;
                    }
                }
                else{
                    $RutaUrl=$request->sede;
                }
            }
            else{
                $RutaUrl=$RutaUrl;
            }
        }

        //$RutaUrl = 'DBs';
        $SedeConnection = $RutaUrl;
        $sede = $RutaUrl;
                      
        $conn = FG_Conectar_Smartpharma($SedeConnection);        
        $registro=[];       
        $historialvueltos=collect();
        
        switch($sede)
        {
            case 'DBs':                                          
                $cajeros=VueltoVDC::where('sede','=',$sede)->fecha($fini, $ffin)
                        ->where('estatus','=','Error')                    
                        ->where('nombreCajeroFactura','=',$request->cajero)  
                        ->OrderBy("id",'desc')                                                                          
                        ->get();
                $mensaje=null;                 
            break;
            case 'FAU':
                if(FG_Validar_Conectividad('FAU')==1){                        
                    $cajeros=VueltoVDC::where('sede','=',$sede)->fecha($fini, $ffin)
                            ->where('estatus','=','Error')                    
                            ->where('nombreCajeroFactura','=',$request->cajero)   
                            ->OrderBy("id",'desc')                                                                         
                            ->get();
                    $mensaje=null;
                    
                }
                else{
                    $cajeros=null;
                    $mensaje="No hay conexion con Farmacia Avenida Universidad (FAU)";
                }
            break;
            case 'GP':
                if(FG_Validar_Conectividad('FAU')==1){
                    $cajeros=VueltoVDC::where('sede','=',$sede)->fecha($fini, $ffin)
                            ->where('estatus','=','Error')                    
                            ->where('nombreCajeroFactura','=',$request->cajero)  
                            ->OrderBy("id",'desc')                                                                          
                            ->get();
                    $mensaje=null;
                }
                else{
                    $cajeros=null;
                    $mensaje="No hay conexion con Grupo P (GP)";
                }
            break;
            case 'FTN':
                if(FG_Validar_Conectividad('FTN')==1){
                    $cajeros=VueltosFTN::where('sede','=',$sede)->fecha($fini, $ffin)
                            ->where('estatus','=','Error')                    
                            ->where('nombreCajeroFactura','=',$request->cajero)  
                            ->OrderBy("id",'desc')                                                                          
                            ->get();
                    $mensaje=null;
                }
                else{
                    $cajeros=null;
                    $mensaje="No hay conexion con Farmacia Tierra Negra (FTN)";
                }
            break;
            case 'FLL':
                if(FG_Validar_Conectividad('FLL')==1){                                               
                    $cajeros=VueltosFLL::where('sede','=',$sede)->fecha($fini, $ffin)
                            ->where('estatus','=','Error')                    
                            ->where('nombreCajeroFactura','=',$request->cajero)  
                            ->OrderBy("id",'desc')                                                                          
                            ->get();
                    $mensaje=null;
                }
                else{
                    $cajeros=null;
                    $mensaje="No hay conexion con Farmacia La Lago (FLL)";
                }
            break;
            case 'FSM':
                if(FG_Validar_Conectividad('FSM')==1){                        
                    $cajeros=VueltosFM::where('sede','=',$sede)->fecha($fini, $ffin)
                            ->where('estatus','=','Error')                    
                            ->where('nombreCajeroFactura','=',$request->cajero)  
                            ->OrderBy("id",'desc')                                                                          
                            ->get();
                    $mensaje=null;
                }
                else{
                    $cajeros=null;
                    $mensaje="No hay conexion con Farmacia Millenium 2000 (FSM)";
                }
            break;
            case 'KDI':
                if(FG_Validar_Conectividad('KDI')==1){                                                
                    $cajeros=VueltosKDI::where('sede','=',$sede)->fecha($fini, $ffin)
                            ->where('estatus','=','Error')                    
                            ->where('nombreCajeroFactura','=',$request->cajero)    
                            ->OrderBy("id",'desc')                                                                        
                            ->get();
                    $mensaje=null;
                }
                else{
                    $cajeros=null;
                    $mensaje="No hay conexion con Farmacia KD Express (KDI)";
                }
            break;
            case 'FEC':
                if(FG_Validar_Conectividad('FEC')==1){   
                    $cajeros=VueltosFEC::where('sede','=',$sede)->fecha($fini, $ffin)
                            ->where('estatus','=','Error')                    
                            ->where('nombreCajeroFactura','=',$request->cajero) 
                            ->OrderBy("id",'desc')                                                                           
                            ->get();                            
                    $mensaje=null;
                }
                else{
                    $cajeros=null;
                    $mensaje="No hay conexion con Farmacia El Callejon (FEC)";
                }
            break;
        } 
            
        if($cajeros!=null){
            foreach($cajeros as $cajero){
                
                $idFactura=$cajero->id_factura;

                //Consulta de la factura
                $sql = "
                        SELECT
                            TOP 1 vvp.VenVentaId,
                            f.NumeroFactura,
                            f.Auditoria_Usuario,
                            f.FechaDocumento,
                            ca.EstacionTrabajo,
                            cl.CodigoCliente,
                            CONCAT(g.Nombre, ' ', g.Apellido) AS nombre,
                            g.Telefono,
                            f.M_MontoTotalFactura as totalFactura,
                            vvp.MontoPagado,
                            vvp.MontoRecibido
                        FROM
                            VenFactura f
                            LEFT JOIN VenCaja vca ON f.VenCajaId =  vca.Id
                            LEFT JOIN VenCaja ca ON f.VenCajaId = ca.Id
                            LEFT JOIN VenCliente cl ON f.VenClienteId = cl.Id
                            LEFT JOIN GenPersona g ON g.Id = cl.GenPersonaId
                            LEFT JOIN VenVentaPago vvp ON vvp.VenVentaId = f.VenVentaId
                        WHERE
                            f.Id = ".$idFactura."
                        ORDER BY
                            f.Id DESC
                    ";

                $result = sqlsrv_query($conn, $sql);
                $row = sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC);

                //Busqueda de devoluciones de la factura
                $sql2="
                    SELECT TOP 1
                        vd.Id,
                        vd.VenFacturaId,
                        vd.ConsecutivoDevolucionSistema,
                        vd.Auditoria_FechaCreacion,
                        vd.FechaDocumento,
                        vca.EstacionTrabajo,
                        vd.Auditoria_Usuario
                    FROM VenDevolucion vd
                    LEFT JOIN VenCaja vca ON vca.Id =  vd.VenCajaId
                    WHERE
                                    vd.VenFacturaId = ".$idFactura."
                ";
                $result2 = sqlsrv_query($conn, $sql2);
                $devolucion = sqlsrv_fetch_array($result2, SQLSRV_FETCH_ASSOC);

                if($devolucion["ConsecutivoDevolucionSistema"]>0){
                    $fechaDevolucion=$devolucion["FechaDocumento"]->format('Y-m-d H:i:s');
                }
                else{
                    $fechaDevolucion="";
                }
                if($cajero->montoPagado>0){
                    $montoPagadoFactura=$cajero->montoPagado;
                }
                else{
                    $montoPagadoFactura=0;
                }

                if($cajero->tasaVenta>0){
                    
                    $totalFacturaDolar=number_format($row["totalFactura"]/$cajero->tasaVenta,2);
                    $montoPagoMovilDolar=number_format($cajero->monto/$cajero->tasaVenta,2);
                    if($montoPagadoFactura>0){
                        $montoPagadoFacturaDolar=number_format($montoPagadoFactura/$cajero->tasaVenta,2);
                    }
                    else{
                        $montoPagadoFactura=0;
                        $montoPagadoFacturaDolar=0;
                    }
                }   
                else{
                    $totalFacturaDolar=0;
                    $montoPagoMovilDolar=0;
                    $montoPagadoFactura=0;
                }

                $registro = [
                    'sede'=>$cajero->sede,
                    'cedula_cliente_factura' =>$row["CodigoCliente"],
                    'telefono_cliente_factura' =>$row["Telefono"],
                    'fecha_hora_factura'=>$row['FechaDocumento']->format('Y-m-d H:i:s'),
                    'cajero_venta' =>$row["Auditoria_Usuario"],                
                    'numero_factura' => $row["NumeroFactura"] ,
                    'nombre_cliente'=>$row["nombre"] ,
                    'total_factura'=>$row["totalFactura"] ,
                    'total_factura_dolar' => $totalFacturaDolar ,
                    'monto_pagado_factura' => $montoPagadoFactura,
                    'monto_pagado_factura_dolar' => $montoPagadoFacturaDolar,
                    'id'=>$cajero->id,  
                    'id_factura'=> $cajero->id_factura,  
                    'fecha_hora'=> $cajero->fecha_hora,  
                    'banco_cliente'=> $cajero->banco_cliente,  
                    'cedula_cliente'=> $cajero->cedula_cliente,  
                    'telefono_pago_movil'=> $cajero->telefono_cliente,  
                    'monto' => $cajero->monto,
                    'monto_dolar' => $montoPagoMovilDolar,
                    'sede'=> $cajero->sede,  
                    'caja'=> $cajero->caja,  
                    'estatus'=> $cajero->estatus,  
                    'confirmacion_banco'=> $cajero->confirmacion_banco,  
                    'motivo_error'=> $cajero->motivo_error,
                    'nro_devolucion' =>$devolucion["ConsecutivoDevolucionSistema"],
                    'fecha_devolucion' => $fechaDevolucion,
                    'caja_devolucion' =>$devolucion["EstacionTrabajo"],
                    'cajero_devolucion' =>$devolucion["Auditoria_Usuario"]  
                ];           
                
                $coleccion=collect($registro);
                $historialvueltos->push($coleccion);
            }    
        } 
        else{
            $historialvueltos=null;
        }            
                
        $cajero=$request->cajero;
        

        return view('pages.vuelto.detalleCajeroError',compact('cajero','historialvueltos','mensaje','sedeUsuario','sede','fini','ffin'));
    }

   
}
