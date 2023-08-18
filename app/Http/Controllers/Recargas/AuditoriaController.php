<?php

namespace compras\Http\Controllers\Recargas;

use Illuminate\Http\Request;
use compras\Http\Controllers\Controller;
use compras\Recargas\AuditoriaFau;
use compras\Recargas\AuditoriaFll;
use compras\Recargas\AuditoriaFtn;
use compras\Recargas\AuditoriaFec;
use compras\Recargas\AuditoriaFsm;
use compras\Recargas\AuditoriaKd73;
use compras\Recargas\AuditoriaKdi;
use compras\Recargas\OperacionFau;
use compras\Recargas\OperacionFec;
use compras\Recargas\OperacionFll;
use compras\Recargas\OperacionFsm;
use compras\Recargas\OperacionFtn;
use compras\Recargas\OperacionKd73;
use compras\Recargas\OperacionKdi;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class AuditoriaController extends Controller
{
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
                    //$RutaUrl = "DBs";
                    $RutaUrl = "FAU";
                }
                else{
                    $RutaUrl = "FAU";
                }                
            break;
            case "FARMACIA AVENIDA UNIVERSIDAD, C.A.":
                if ($_SERVER['SERVER_NAME'] == 'cpharmagpde.com' ) {
                    //$RutaUrl = "DBs";
                    $RutaUrl = "FAU";
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
                        //$RutaUrl='DBs';
                        $RutaUrl=$request->sede;
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
            $vueltos =  AuditoriaFau::whereBetween('fecha',[ $fini,$ffin])->OrderBy("id",'desc')->get();
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
                        //$sede='DBs';
                        $sede=$request->sede;
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
                        $vueltos =  AuditoriaFau::whereBetween('fecha',[ $fini,$ffin])->OrderBy("id",'desc')->get();
                        $mensaje=null;
                    }
                    else{
                        $vueltos=null;
                        $mensaje="No hay conexion con Farmacia Avenida Universidad (FAU)";
                    }
                break;
                case 'FAU':
                    if(FG_Validar_Conectividad('FAU')==1){
                        $vueltos =  AuditoriaFau::whereBetween('fecha',[ $fini,$ffin])->OrderBy("id",'desc')->get();
                        $mensaje=null;
                    }
                    else{
                        $vueltos=null;
                        $mensaje="No hay conexion con Farmacia Avenida Universidad (FAU)";
                    }
                break;
                case 'GP':
                    if(FG_Validar_Conectividad('FAU')==1){
                        $vueltos =  AuditoriaFau::whereBetween('fecha',[ $fini,$ffin])->OrderBy("id",'desc')->get();
                        $mensaje=null;
                    }
                    else{
                        $vueltos=null;
                        $mensaje="No hay conexion con Grupo P (GP)";
                    }
                break;
                case 'FTN':
                    if(FG_Validar_Conectividad('FTN')==1){
                        $vueltos =  AuditoriaFtn::whereBetween('fecha',[ $fini,$ffin])->OrderBy("id",'desc')->get();
                        $mensaje=null;
                    }
                    else{
                        $vueltos=null;
                        $mensaje="No hay conexion con Farmacia Tierra Negra (FTN)";
                    }
                break;
                case 'FLL':
                    if(FG_Validar_Conectividad('FLL')==1){
                        $vueltos =  AuditoriaFll::whereBetween('fecha',[ $fini,$ffin])->OrderBy("id",'desc')->get();
                        $mensaje=null;
                    }
                    else{
                        $vueltos=null;
                        $mensaje="No hay conexion con Farmacia La Lago (FLL)";
                    }
                break;
                case 'FSM':
                    if(FG_Validar_Conectividad('FSM')==1){
                        $vueltos =  AuditoriaFsm::whereBetween('fecha',[ $fini,$ffin])->OrderBy("id",'desc')->get();
                        $mensaje=null;
                    }
                    else{
                        $vueltos=null;
                        $mensaje="No hay conexion con Farmacia Millenium 2000 (FSM)";
                    }
                break;
                case 'KDI':
                    if(FG_Validar_Conectividad('KDI')==1){
                        $vueltos =  AuditoriaKdi::whereBetween('fecha',[ $fini,$ffin])->OrderBy("id",'desc')->get();
                        $mensaje=null;
                    }
                    else{
                        $vueltos=null;
                        $mensaje="No hay conexion con Farmacia KD Express (KDI)";
                    }
                break;
                case 'FEC':                    
                    if(FG_Validar_Conectividad('FEC')==1){
                        $vueltos =  AuditoriaFec::whereBetween('fecha',[ $fini,$ffin])->OrderBy("id",'desc')->get();
                        $mensaje=null;                       
                    }
                    else{
                        $vueltos=[];
                        $mensaje="No hay conexion con Farmacia El Callejon (FEC)";
                    }
                break;
                case 'KD73':
                    if(FG_Validar_Conectividad('KD73')==1){
                        $vueltos =  AuditoriaKd73::whereBetween('fecha',[ $fini,$ffin])->OrderBy("id",'desc')->get();
                        $mensaje=null;                        
                    }
                    else{
                        $vueltos=[];
                        $mensaje="No hay conexion con FARMACIAS KD EXPRESS, C.A. - KD73";
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
                    
                        $vueltos =  AuditoriaFau::whereBetween('fecha',[ $fini,$ffin])->OrderBy("id",'desc')->get();
                        $mensaje=null;                    
                break;
                case 'FAU':
                    if(FG_Validar_Conectividad('FAU')==1){
                        $vueltos =  AuditoriaFau::whereBetween('fecha',[ $fini,$ffin])->OrderBy("id",'desc')->get();
                        $mensaje=null;
                    }
                    else{
                        $vueltos=null;
                        $mensaje="No hay conexion con Farmacia Avenida Universidad (FAU)";
                    }
                break;
                case 'GP':
                    if(FG_Validar_Conectividad('FAU')==1){
                        $vueltos =  AuditoriaFau::whereBetween('fecha',[ $fini,$ffin])->OrderBy("id",'desc')->get();
                        $mensaje=null;
                    }
                    else{
                        $vueltos=null;
                        $mensaje="No hay conexion con Grupo P (GP)";
                    }
                break;
                case 'FTN':
                    if(FG_Validar_Conectividad('FTN')==1){
                        $vueltos =  AuditoriaFtn::whereBetween('fecha',[ $fini,$ffin])->OrderBy("id",'desc')->get();
                        $mensaje=null;
                    }
                    else{
                        $vueltos=null;
                        $mensaje="No hay conexion con Farmacia Tierra Negra (FTN)";
                    }
                break;
                case 'FLL':
                    if(FG_Validar_Conectividad('FLL')==1){
                        $vueltos =  AuditoriaFll::whereBetween('fecha',[ $fini,$ffin])->OrderBy("id",'desc')->get();
                        $mensaje=null;
                    }
                    else{
                        $vueltos=null;
                        $mensaje="No hay conexion con Farmacia La Lago (FLL)";
                    }
                break;
                case 'FSM':
                    if(FG_Validar_Conectividad('FSM')==1){
                        $vueltos =  AuditoriaFsm::whereBetween('fecha',[ $fini,$ffin])->OrderBy("id",'desc')->get();
                        $mensaje=null;
                    }
                    else{
                        $vueltos=null;
                        $mensaje="No hay conexion con Farmacia Millenium 2000 (FSM)";
                    }
                break;
                case 'KDI':
                    if(FG_Validar_Conectividad('KDI')==1){
                        $vueltos =  AuditoriaKdi::whereBetween('fecha',[ $fini,$ffin])->OrderBy("id",'desc')->get();
                        $mensaje=null;
                    }
                    else{
                        $vueltos=null;
                        $mensaje="No hay conexion con Farmacia KD Express (KDI)";
                    }
                break;
                case 'FEC':
                    if(FG_Validar_Conectividad('FEC')==1){
                        $vueltos =  AuditoriaFec::whereBetween('fecha',[ $fini,$ffin])->OrderBy("id",'desc')->get();
                        $mensaje=null;
                    }
                    else{
                        $vueltos=null;
                        $mensaje="No hay conexion con Farmacia El Callejon (FEC)";
                    }
                break;
                case 'KD73':
                    if(FG_Validar_Conectividad('KD73')==1){
                        $vueltos =  AuditoriaKd73::whereBetween('fecha',[ $fini,$ffin])->OrderBy("id",'desc')->get();
                        $mensaje=null;
                    }
                    else{
                        $vueltos=null;
                        $mensaje="No hay conexion con FARMACIAS KD EXPRESS, C.A. - KD73";
                    }
                break;
            }
            
        }           
        return view('pages.recargas.index', compact('mensaje','vueltos','fini','ffin','sedeUsuario','sede'));
    }

    public function cajerosTransaccionales(Request $request){
        include app_path() . '/functions/config.php';
        include app_path() . '/functions/functions.php';
        
        $sedeUsuario = Auth::user()->sede;
        switch($sedeUsuario){
            case "GRUPO P, C.A":
                if ($_SERVER['SERVER_NAME'] == 'cpharmagpde.com' ) {
                    //$RutaUrl = "DBs";
                    $RutaUrl = "FAU";
                }
                else{
                    $RutaUrl = "FAU";
                }                
            break;
            case "FARMACIA AVENIDA UNIVERSIDAD, C.A.":
                if ($_SERVER['SERVER_NAME'] == 'cpharmagpde.com' ) {
                    //$RutaUrl = "DBs";
                    $RutaUrl = "FAU";
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
                        //$RutaUrl='DBs';
                        $RutaUrl=$request->sede;
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
            $vueltos =  AuditoriaFau::whereBetween('fecha',[ $fini,$ffin])->select("black_boxes.*",DB::connection('fauRecargas')->raw('SUM(black_boxes.monto) as totalMonto'))->groupBy('id_usuario')->get();
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
                        //$sede='DBs';
                        $sede=$request->sede;
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
                        $vueltos =  AuditoriaFau::whereBetween('fecha',[ $fini,$ffin])->select("black_boxes.*",DB::connection('fauRecargas')->raw('SUM(black_boxes.monto) as totalMonto'))->groupBy('id_usuario')->get();
                        $mensaje=null;
                    }
                    else{
                        $vueltos=null;
                        $mensaje="No hay conexion con Farmacia Avenida Universidad (FAU)";
                    }
                break;
                case 'FAU':
                    if(FG_Validar_Conectividad('FAU')==1){
                        $vueltos =  AuditoriaFau::whereBetween('fecha',[ $fini,$ffin])->select("black_boxes.*",DB::connection('fauRecargas')->raw('SUM(black_boxes.monto) as totalMonto'))->groupBy('id_usuario')->get();
                        $mensaje=null;
                    }
                    else{
                        $vueltos=null;
                        $mensaje="No hay conexion con Farmacia Avenida Universidad (FAU)";
                    }
                break;
                case 'GP':
                    if(FG_Validar_Conectividad('FAU')==1){
                        $vueltos =  AuditoriaFau::whereBetween('fecha',[ $fini,$ffin])->select("black_boxes.*",DB::connection('fauRecargas')->raw('SUM(black_boxes.monto) as totalMonto'))->groupBy('id_usuario')->get();
                        $mensaje=null;
                    }
                    else{
                        $vueltos=null;
                        $mensaje="No hay conexion con Grupo P (GP)";
                    }
                break;
                case 'FTN':
                    if(FG_Validar_Conectividad('FTN')==1){
                        $vueltos =  AuditoriaFtn::whereBetween('fecha',[ $fini,$ffin])->select("black_boxes.*",DB::connection('ftnRecargas')->raw('SUM(black_boxes.monto) as totalMonto'))->groupBy('id_usuario')->get();
                        $mensaje=null;
                    }
                    else{
                        $vueltos=null;
                        $mensaje="No hay conexion con Farmacia Tierra Negra (FTN)";
                    }
                break;
                case 'FLL':
                    if(FG_Validar_Conectividad('FLL')==1){
                        $vueltos =  AuditoriaFll::whereBetween('fecha',[ $fini,$ffin])->select("black_boxes.*",DB::connection('fllRecargas')->raw('SUM(black_boxes.monto) as totalMonto'))->groupBy('id_usuario')->get();
                        $mensaje=null;
                    }
                    else{
                        $vueltos=null;
                        $mensaje="No hay conexion con Farmacia La Lago (FLL)";
                    }
                break;
                case 'FSM':
                    if(FG_Validar_Conectividad('FSM')==1){
                        $vueltos =  AuditoriaFsm::whereBetween('fecha',[ $fini,$ffin])->select("black_boxes.*",DB::connection('fmRecargas')->raw('SUM(black_boxes.monto) as totalMonto'))->groupBy('id_usuario')->get();
                        $mensaje=null;
                    }
                    else{
                        $vueltos=null;
                        $mensaje="No hay conexion con Farmacia Millenium 2000 (FSM)";
                    }
                break;
                case 'KDI':
                    if(FG_Validar_Conectividad('KDI')==1){
                        $vueltos =  AuditoriaKdi::whereBetween('fecha',[ $fini,$ffin])->select("black_boxes.*",DB::connection('kdiRecargas')->raw('SUM(black_boxes.monto) as totalMonto'))->groupBy('id_usuario')->get();
                        $mensaje=null;
                    }
                    else{
                        $vueltos=null;
                        $mensaje="No hay conexion con Farmacia KD Express (KDI)";
                    }
                break;
                case 'FEC':                    
                    if(FG_Validar_Conectividad('FEC')==1){
                        $vueltos =  AuditoriaFec::whereBetween('fecha',[ $fini,$ffin])->select("black_boxes.*",DB::connection('fecRecargas')->raw('SUM(black_boxes.monto) as totalMonto'))->groupBy('id_usuario')->get();
                        $mensaje=null;                       
                    }
                    else{
                        $vueltos=[];
                        $mensaje="No hay conexion con Farmacia El Callejon (FEC)";
                    }
                break;
                case 'KD73':
                    if(FG_Validar_Conectividad('KD73')==1){
                        $vueltos =  AuditoriaKd73::whereBetween('fecha',[ $fini,$ffin])->select("black_boxes.*",DB::connection('kd73Recargas')->raw('SUM(black_boxes.monto) as totalMonto'))->groupBy('id_usuario')->get();
                        $mensaje=null;                        
                    }
                    else{
                        $vueltos=[];
                        $mensaje="No hay conexion con FARMACIAS KD EXPRESS, C.A. - KD73";
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
                    
                        $vueltos =  AuditoriaFau::whereBetween('fecha',[ $fini,$ffin])->select("black_boxes.*",DB::connection('fauRecargas')->raw('SUM(black_boxes.monto) as totalMonto'))->groupBy('id_usuario')->get();
                        $mensaje=null;                    
                break;
                case 'FAU':
                    if(FG_Validar_Conectividad('FAU')==1){
                        $vueltos =  AuditoriaFau::whereBetween('fecha',[ $fini,$ffin])->select("black_boxes.*",DB::connection('fauRecargas')->raw('SUM(black_boxes.monto) as totalMonto'))->groupBy('id_usuario')->get();
                        $mensaje=null;
                    }
                    else{
                        $vueltos=null;
                        $mensaje="No hay conexion con Farmacia Avenida Universidad (FAU)";
                    }
                break;
                case 'GP':
                    if(FG_Validar_Conectividad('FAU')==1){
                        $vueltos =  AuditoriaFau::whereBetween('fecha',[ $fini,$ffin])->select("black_boxes.*",DB::connection('fauRecargas')->raw('SUM(black_boxes.monto) as totalMonto'))->groupBy('id_usuario')->get();
                        $mensaje=null;
                    }
                    else{
                        $vueltos=null;
                        $mensaje="No hay conexion con Grupo P (GP)";
                    }
                break;
                case 'FTN':
                    if(FG_Validar_Conectividad('FTN')==1){
                        $vueltos =  AuditoriaFtn::whereBetween('fecha',[ $fini,$ffin])->select("black_boxes.*",DB::connection('ftnRecargas')->raw('SUM(black_boxes.monto) as totalMonto'))->groupBy('id_usuario')->get();
                        $mensaje=null;
                    }
                    else{
                        $vueltos=null;
                        $mensaje="No hay conexion con Farmacia Tierra Negra (FTN)";
                    }
                break;
                case 'FLL':
                    if(FG_Validar_Conectividad('FLL')==1){
                        $vueltos =  AuditoriaFll::whereBetween('fecha',[ $fini,$ffin])->select("black_boxes.*",DB::connection('fllRecargas')->raw('SUM(black_boxes.monto) as totalMonto'))->groupBy('id_usuario')->get();
                        $mensaje=null;
                    }
                    else{
                        $vueltos=null;
                        $mensaje="No hay conexion con Farmacia La Lago (FLL)";
                    }
                break;
                case 'FSM':
                    if(FG_Validar_Conectividad('FSM')==1){
                        $vueltos =  AuditoriaFsm::whereBetween('fecha',[ $fini,$ffin])->select("black_boxes.*",DB::connection('fmRecargas')->raw('SUM(black_boxes.monto) as totalMonto'))->groupBy('id_usuario')->get();
                        $mensaje=null;
                    }
                    else{
                        $vueltos=null;
                        $mensaje="No hay conexion con Farmacia Millenium 2000 (FSM)";
                    }
                break;
                case 'KDI':
                    if(FG_Validar_Conectividad('KDI')==1){
                        $vueltos =  AuditoriaKdi::whereBetween('fecha',[ $fini,$ffin])->select("black_boxes.*",DB::connection('kdiRecargas')->raw('SUM(black_boxes.monto) as totalMonto'))->groupBy('id_usuario')->get();
                        $mensaje=null;
                    }
                    else{
                        $vueltos=null;
                        $mensaje="No hay conexion con Farmacia KD Express (KDI)";
                    }
                break;
                case 'FEC':
                    if(FG_Validar_Conectividad('FEC')==1){
                        $vueltos =  AuditoriaFec::whereBetween('fecha',[ $fini,$ffin])->select("black_boxes.*",DB::connection('fecRecargas')->raw('SUM(black_boxes.monto) as totalMonto'))->groupBy('id_usuario')->get();
                        $mensaje=null;
                    }
                    else{
                        $vueltos=null;
                        $mensaje="No hay conexion con Farmacia El Callejon (FEC)";
                    }
                break;
                case 'KD73':
                    if(FG_Validar_Conectividad('KD73')==1){
                        $vueltos =  AuditoriaKd73::whereBetween('fecha',[ $fini,$ffin])->select("black_boxes.*",DB::connection('kd73Recargas')->raw('SUM(black_boxes.monto) as totalMonto'))->groupBy('id_usuario')->get();
                        $mensaje=null;
                    }
                    else{
                        $vueltos=null;
                        $mensaje="No hay conexion con FARMACIAS KD EXPRESS, C.A. - KD73";
                    }
                break;
            }
            
        } 
        return view('pages.recargas.cajeros', compact('mensaje','vueltos','fini','ffin','sedeUsuario','sede'));   
    }

    public function serviciosTransaccion(Request $request){
        include app_path() . '/functions/config.php';
        include app_path() . '/functions/functions.php';
        
        $sedeUsuario = Auth::user()->sede;
        switch($sedeUsuario){
            case "GRUPO P, C.A":
                if ($_SERVER['SERVER_NAME'] == 'cpharmagpde.com' ) {
                    //$RutaUrl = "DBs";
                    $RutaUrl = "FAU";
                }
                else{
                    $RutaUrl = "FAU";
                }                
            break;
            case "FARMACIA AVENIDA UNIVERSIDAD, C.A.":
                if ($_SERVER['SERVER_NAME'] == 'cpharmagpde.com' ) {
                    //$RutaUrl = "DBs";
                    $RutaUrl = "FAU";
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
                        //$RutaUrl='DBs';
                        $RutaUrl=$request->sede;
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
            $vueltos =  AuditoriaFau::whereBetween('fecha',[ $fini,$ffin])->select("black_boxes.*",DB::connection('fauRecargas')->raw('SUM(black_boxes.monto) as totalMonto'),DB::connection('fauRecargas')->raw('COUNT(black_boxes.id) as totalOperaciones'))->groupBy('servicio')->get();
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
                        //$sede='DBs';
                        $sede=$request->sede;
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
                        $vueltos =  AuditoriaFau::whereBetween('fecha',[ $fini,$ffin])->select("black_boxes.*",DB::connection('fauRecargas')->raw('SUM(black_boxes.monto) as totalMonto'),DB::connection('fauRecargas')->raw('COUNT(black_boxes.id) as totalOperaciones'))->groupBy('servicio')->get();
                        $mensaje=null;
                    }
                    else{
                        $vueltos=null;
                        $mensaje="No hay conexion con Farmacia Avenida Universidad (FAU)";
                    }
                break;
                case 'FAU':
                    if(FG_Validar_Conectividad('FAU')==1){
                        $vueltos =  AuditoriaFau::whereBetween('fecha',[ $fini,$ffin])->select("black_boxes.*",DB::connection('fauRecargas')->raw('SUM(black_boxes.monto) as totalMonto'),DB::connection('fauRecargas')->raw('COUNT(black_boxes.id) as totalOperaciones'))->groupBy('servicio')->get();
                        $mensaje=null;
                    }
                    else{
                        $vueltos=null;
                        $mensaje="No hay conexion con Farmacia Avenida Universidad (FAU)";
                    }
                break;
                case 'GP':
                    if(FG_Validar_Conectividad('FAU')==1){
                        $vueltos =  AuditoriaFau::whereBetween('fecha',[ $fini,$ffin])->select("black_boxes.*",DB::connection('fauRecargas')->raw('SUM(black_boxes.monto) as totalMonto'),DB::connection('fauRecargas')->raw('COUNT(black_boxes.id) as totalOperaciones'))->groupBy('servicio')->get();
                        $mensaje=null;
                    }
                    else{
                        $vueltos=null;
                        $mensaje="No hay conexion con Grupo P (GP)";
                    }
                break;
                case 'FTN':
                    if(FG_Validar_Conectividad('FTN')==1){
                        $vueltos =  AuditoriaFtn::whereBetween('fecha',[ $fini,$ffin])->select("black_boxes.*",DB::connection('ftnRecargas')->raw('SUM(black_boxes.monto) as totalMonto'),DB::connection('ftnRecargas')->raw('COUNT(black_boxes.id) as totalOperaciones'))->groupBy('servicio')->get();
                        $mensaje=null;
                    }
                    else{
                        $vueltos=null;
                        $mensaje="No hay conexion con Farmacia Tierra Negra (FTN)";
                    }
                break;
                case 'FLL':
                    if(FG_Validar_Conectividad('FLL')==1){
                        $vueltos =  AuditoriaFll::whereBetween('fecha',[ $fini,$ffin])->select("black_boxes.*",DB::connection('fllRecargas')->raw('SUM(black_boxes.monto) as totalMonto'),DB::connection('fllRecargas')->raw('COUNT(black_boxes.id) as totalOperaciones'))->groupBy('servicio')->get();
                        $mensaje=null;
                    }
                    else{
                        $vueltos=null;
                        $mensaje="No hay conexion con Farmacia La Lago (FLL)";
                    }
                break;
                case 'FSM':
                    if(FG_Validar_Conectividad('FSM')==1){
                        $vueltos =  AuditoriaFsm::whereBetween('fecha',[ $fini,$ffin])->select("black_boxes.*",DB::connection('fmRecargas')->raw('SUM(black_boxes.monto) as totalMonto'),DB::connection('fmRecargas')->raw('COUNT(black_boxes.id) as totalOperaciones'))->groupBy('servicio')->get();
                        $mensaje=null;
                    }
                    else{
                        $vueltos=null;
                        $mensaje="No hay conexion con Farmacia Millenium 2000 (FSM)";
                    }
                break;
                case 'KDI':
                    if(FG_Validar_Conectividad('KDI')==1){
                        $vueltos =  AuditoriaKdi::whereBetween('fecha',[ $fini,$ffin])->select("black_boxes.*",DB::connection('kdiRecargas')->raw('SUM(black_boxes.monto) as totalMonto'),DB::connection('kdiRecargas')->raw('COUNT(black_boxes.id) as totalOperaciones'))->groupBy('id_usuario')->get();
                        $mensaje=null;
                    }
                    else{
                        $vueltos=null;
                        $mensaje="No hay conexion con Farmacia KD Express (KDI)";
                    }
                break;
                case 'FEC':                    
                    if(FG_Validar_Conectividad('FEC')==1){
                        $vueltos =  AuditoriaFec::whereBetween('fecha',[ $fini,$ffin])->select("black_boxes.*",DB::connection('fecRecargas')->raw('SUM(black_boxes.monto) as totalMonto'),DB::connection('fecRecargas')->raw('COUNT(black_boxes.id) as totalOperaciones'))->groupBy('servicio')->get();
                        $mensaje=null;                       
                    }
                    else{
                        $vueltos=[];
                        $mensaje="No hay conexion con Farmacia El Callejon (FEC)";
                    }
                break;
                case 'KD73':
                    if(FG_Validar_Conectividad('KD73')==1){
                        $vueltos =  AuditoriaKd73::whereBetween('fecha',[ $fini,$ffin])->select("black_boxes.*",DB::connection('kd73Recargas')->raw('SUM(black_boxes.monto) as totalMonto'),DB::connection('kd73Recargas')->raw('COUNT(black_boxes.id) as totalOperaciones'))->groupBy('servicio')->get();
                        $mensaje=null;                        
                    }
                    else{
                        $vueltos=[];
                        $mensaje="No hay conexion con FARMACIAS KD EXPRESS, C.A. - KD73";
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
                    
                        $vueltos =  AuditoriaFau::whereBetween('fecha',[ $fini,$ffin])->select("black_boxes.*",DB::connection('fauRecargas')->raw('SUM(black_boxes.monto) as totalMonto'),DB::connection('fauRecargas')->raw('COUNT(black_boxes.id) as totalOperaciones'))->groupBy('servicio')->get();
                        $mensaje=null;                    
                break;
                case 'FAU':
                    if(FG_Validar_Conectividad('FAU')==1){
                        $vueltos =  AuditoriaFau::whereBetween('fecha',[ $fini,$ffin])->select("black_boxes.*",DB::connection('fauRecargas')->raw('SUM(black_boxes.monto) as totalMonto'),DB::connection('fauRecargas')->raw('COUNT(black_boxes.id) as totalOperaciones'))->groupBy('servicio')->get();
                        $mensaje=null;
                    }
                    else{
                        $vueltos=null;
                        $mensaje="No hay conexion con Farmacia Avenida Universidad (FAU)";
                    }
                break;
                case 'GP':
                    if(FG_Validar_Conectividad('FAU')==1){
                        $vueltos =  AuditoriaFau::whereBetween('fecha',[ $fini,$ffin])->select("black_boxes.*",DB::connection('fauRecargas')->raw('SUM(black_boxes.monto) as totalMonto'),DB::connection('fauRecargas')->raw('COUNT(black_boxes.id) as totalOperaciones'))->groupBy('servicio')->get();
                        $mensaje=null;
                    }
                    else{
                        $vueltos=null;
                        $mensaje="No hay conexion con Grupo P (GP)";
                    }
                break;
                case 'FTN':
                    if(FG_Validar_Conectividad('FTN')==1){
                        $vueltos =  AuditoriaFtn::whereBetween('fecha',[ $fini,$ffin])->select("black_boxes.*",DB::connection('ftnRecargas')->raw('SUM(black_boxes.monto) as totalMonto'),DB::connection('ftnRecargas')->raw('COUNT(black_boxes.id) as totalOperaciones'))->groupBy('servicio')->get();
                        $mensaje=null;
                    }
                    else{
                        $vueltos=null;
                        $mensaje="No hay conexion con Farmacia Tierra Negra (FTN)";
                    }
                break;
                case 'FLL':
                    if(FG_Validar_Conectividad('FLL')==1){
                        $vueltos =  AuditoriaFll::whereBetween('fecha',[ $fini,$ffin])->select("black_boxes.*",DB::connection('fllRecargas')->raw('SUM(black_boxes.monto) as totalMonto'),DB::connection('fllRecargas')->raw('COUNT(black_boxes.id) as totalOperaciones'))->groupBy('servicio')->get();
                        $mensaje=null;
                    }
                    else{
                        $vueltos=null;
                        $mensaje="No hay conexion con Farmacia La Lago (FLL)";
                    }
                break;
                case 'FSM':
                    if(FG_Validar_Conectividad('FSM')==1){
                        $vueltos =  AuditoriaFsm::whereBetween('fecha',[ $fini,$ffin])->select("black_boxes.*",DB::connection('fmRecargas')->raw('SUM(black_boxes.monto) as totalMonto'),DB::connection('fmRecargas')->raw('COUNT(black_boxes.id) as totalOperaciones'))->groupBy('servicio')->get();
                        $mensaje=null;
                    }
                    else{
                        $vueltos=null;
                        $mensaje="No hay conexion con Farmacia Millenium 2000 (FSM)";
                    }
                break;
                case 'KDI':
                    if(FG_Validar_Conectividad('KDI')==1){
                        $vueltos =  AuditoriaKdi::whereBetween('fecha',[ $fini,$ffin])->select("black_boxes.*",DB::connection('kdiRecargas')->raw('SUM(black_boxes.monto) as totalMonto'),DB::connection('kdiRecargas')->raw('COUNT(black_boxes.id) as totalOperaciones'))->groupBy('servicio')->get();
                        $mensaje=null;
                    }
                    else{
                        $vueltos=null;
                        $mensaje="No hay conexion con Farmacia KD Express (KDI)";
                    }
                break;
                case 'FEC':
                    if(FG_Validar_Conectividad('FEC')==1){
                        $vueltos =  AuditoriaFec::whereBetween('fecha',[ $fini,$ffin])->select("black_boxes.*",DB::connection('fecRecargas')->raw('SUM(black_boxes.monto) as totalMonto'),DB::connection('fecRecargas')->raw('COUNT(black_boxes.id) as totalOperaciones'))->groupBy('servicio')->get();
                        $mensaje=null;
                    }
                    else{
                        $vueltos=null;
                        $mensaje="No hay conexion con Farmacia El Callejon (FEC)";
                    }
                break;
                case 'KD73':
                    if(FG_Validar_Conectividad('KD73')==1){
                        $vueltos =  AuditoriaKd73::whereBetween('fecha',[ $fini,$ffin])->select("black_boxes.*",DB::connection('kd73Recargas')->raw('SUM(black_boxes.monto) as totalMonto'),DB::connection('kd73Recargas')->raw('COUNT(black_boxes.id) as totalOperaciones'))->groupBy('servicio')->get();
                        $mensaje=null;
                    }
                    else{
                        $vueltos=null;
                        $mensaje="No hay conexion con FARMACIAS KD EXPRESS, C.A. - KD73";
                    }
                break;
            }
            
        } 
        return view('pages.recargas.servicios', compact('mensaje','vueltos','fini','ffin','sedeUsuario','sede'));   
    }


    public function detalleTransaccion(Request $request){
        include app_path() . '/functions/config.php';
        include app_path() . '/functions/functions.php';
        
        $sedeUsuario = Auth::user()->sede;
        switch($sedeUsuario){
            case "GRUPO P, C.A":
                if ($_SERVER['SERVER_NAME'] == 'cpharmagpde.com' ) {
                    //$RutaUrl = "DBs";
                    $RutaUrl = "FAU";
                }
                else{
                    $RutaUrl = "FAU";
                }                
            break;
            case "FARMACIA AVENIDA UNIVERSIDAD, C.A.":
                if ($_SERVER['SERVER_NAME'] == 'cpharmagpde.com' ) {
                    //$RutaUrl = "DBs";
                    $RutaUrl = "FAU";
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
                        //$RutaUrl='DBs';
                        $RutaUrl=$request->sede;
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
        $operacion=[];
        $historialvueltos=collect();

        if($request->fecha_ini>0){
            $fini=$request->fecha_ini;
            $ffin=$request->fecha_fin;
            $vueltos =  AuditoriaFau::where('id_operacion','=',$request->operacion)->get();
            $operacion = OperacionFau::find($request->operacion);
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
                        //$sede='DBs';
                        $sede=$request->sede;
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
                        $vueltos =  AuditoriaFau::where('id_operacion','=',$request->operacion)->get();
                        $operacion = OperacionFau::find($request->operacion);
                        $mensaje=null;
                    }
                    else{
                        $vueltos=[];
                        $operacion=[];
                        $mensaje="No hay conexion con Farmacia Avenida Universidad (FAU)";
                    }
                break;
                case 'FAU':
                    if(FG_Validar_Conectividad('FAU')==1){
                        $vueltos =  AuditoriaFau::where('id_operacion','=',$request->operacion)->get();
                        $operacion = OperacionFau::find($request->operacion);
                        $mensaje=null;
                    }
                    else{
                        $vueltos=[];
                        $operacion=[];
                        $mensaje="No hay conexion con Farmacia Avenida Universidad (FAU)";
                    }
                break;
                case 'GP':
                    if(FG_Validar_Conectividad('FAU')==1){
                        $vueltos =  AuditoriaFau::where('id_operacion','=',$request->operacion)->get();
                        $operacion = OperacionFau::find($request->operacion);
                        $mensaje=null;
                    }
                    else{
                        $vueltos=[];
                        $operacion=[];
                        $mensaje="No hay conexion con Grupo P (GP)";
                    }
                break;
                case 'FTN':
                    if(FG_Validar_Conectividad('FTN')==1){
                        $vueltos =  AuditoriaFtn::where('id_operacion','=',$request->operacion)->get();
                        $operacion = OperacionFtn::find($request->operacion);
                        $mensaje=null;
                    }
                    else{
                        $vueltos=[];
                        $operacion=[];
                        $mensaje="No hay conexion con Farmacia Tierra Negra (FTN)";
                    }
                break;
                case 'FLL':
                    if(FG_Validar_Conectividad('FLL')==1){
                        $vueltos =  AuditoriaFll::where('id_operacion','=',$request->operacion)->get();
                        $operacion = OperacionFll::find($request->operacion);
                        $mensaje=null;
                    }
                    else{
                        $vueltos=[];
                        $operacion=[];
                        $mensaje="No hay conexion con Farmacia La Lago (FLL)";
                    }
                break;
                case 'FSM':
                    if(FG_Validar_Conectividad('FSM')==1){
                        $vueltos =  AuditoriaFsm::where('id_operacion','=',$request->operacion)->get();
                        $operacion = OperacionFsm::find($request->operacion);
                        $mensaje=null;
                    }
                    else{
                        $vueltos=[];
                        $operacion=[];
                        $mensaje="No hay conexion con Farmacia Millenium 2000 (FSM)";
                    }
                break;
                case 'KDI':
                    if(FG_Validar_Conectividad('KDI')==1){
                        $vueltos =  AuditoriaKdi::where('id_operacion','=',$request->operacion)->get();
                        $operacion = OperacionKdi::find($request->operacion);
                        $mensaje=null;
                    }
                    else{
                        $vueltos=[];
                        $operacion=[];
                        $mensaje="No hay conexion con Farmacia KD Express (KDI)";
                    }
                break;
                case 'FEC':                    
                    if(FG_Validar_Conectividad('FEC')==1){
                        $vueltos =  AuditoriaFec::where('id_operacion','=',$request->operacion)->get();
                        $operacion = OperacionFec::find($request->operacion);
                        $mensaje=null;                       
                    }
                    else{
                        $vueltos=[];
                        $operacion=[];
                        $mensaje="No hay conexion con Farmacia El Callejon (FEC)";
                    }
                break;
                case 'KD73':
                    if(FG_Validar_Conectividad('KD73')==1){
                        $vueltos =  AuditoriaKd73::where('id_operacion','=',$request->operacion)->get();
                        $operacion = OperacionKd73::find($request->operacion);
                        $mensaje=null;                        
                    }
                    else{
                        $vueltos=[];
                        $operacion=[];
                        $mensaje="No hay conexion con FARMACIAS KD EXPRESS, C.A. - KD73";
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
                    
                        $vueltos =  AuditoriaFau::where('id_operacion','=',$request->operacion)->get();
                        $operacion = OperacionFau::find($request->operacion);
                        $mensaje=null;                    
                break;
                case 'FAU':
                    if(FG_Validar_Conectividad('FAU')==1){
                        $vueltos =  AuditoriaFau::where('id_operacion','=',$request->operacion)->get();
                        $operacion = OperacionFau::find($request->operacion);
                        $mensaje=null;
                    }
                    else{
                        $vueltos=[];
                        $operacion=[];
                        $mensaje="No hay conexion con Farmacia Avenida Universidad (FAU)";
                    }
                break;
                case 'GP':
                    if(FG_Validar_Conectividad('FAU')==1){
                        $vueltos =  AuditoriaFau::where('id_operacion','=',$request->operacion)->get();
                        $operacion = OperacionFau::find($request->operacion);
                        $mensaje=null;
                    }
                    else{
                        $vueltos=[];
                        $operacion=[];
                        $mensaje="No hay conexion con Grupo P (GP)";
                    }
                break;
                case 'FTN':
                    if(FG_Validar_Conectividad('FTN')==1){
                        $vueltos =  AuditoriaFtn::where('id_operacion','=',$request->operacion)->get();
                        $operacion = OperacionFtn::find($request->operacion);
                        $mensaje=null;
                    }
                    else{
                        $vueltos=[];
                        $operacion=[];
                        $mensaje="No hay conexion con Farmacia Tierra Negra (FTN)";
                    }
                break;
                case 'FLL':
                    if(FG_Validar_Conectividad('FLL')==1){
                        $vueltos =  AuditoriaFll::where('id_operacion','=',$request->operacion)->get();
                        $operacion = OperacionFll::find($request->operacion);
                        $mensaje=null;
                    }
                    else{
                        $vueltos=[];
                        $operacion=[];
                        $mensaje="No hay conexion con Farmacia La Lago (FLL)";
                    }
                break;
                case 'FSM':
                    if(FG_Validar_Conectividad('FSM')==1){
                        $vueltos =  AuditoriaFsm::where('id_operacion','=',$request->operacion)->get();
                        $operacion = OperacionFsm::find($request->operacion);
                        $mensaje=null;
                    }
                    else{
                        $vueltos=[];
                        $operacion=[];
                        $mensaje="No hay conexion con Farmacia Millenium 2000 (FSM)";
                    }
                break;
                case 'KDI':
                    if(FG_Validar_Conectividad('KDI')==1){
                        $vueltos =  AuditoriaKdi::where('id_operacion','=',$request->operacion)->get();
                        $operacion = OperacionKdi::find($request->operacion);
                        $mensaje=null;
                    }
                    else{
                        $vueltos=[];
                        $operacion=[];
                        $mensaje="No hay conexion con Farmacia KD Express (KDI)";
                    }
                break;
                case 'FEC':
                    if(FG_Validar_Conectividad('FEC')==1){
                        $vueltos =  AuditoriaFec::where('id_operacion','=',$request->operacion)->get();
                        $operacion = OperacionFec::find($request->operacion);
                        $mensaje=null;
                    }
                    else{
                        $vueltos=[];
                        $operacion=[];
                        $mensaje="No hay conexion con Farmacia El Callejon (FEC)";
                    }
                break;
                case 'KD73':
                    if(FG_Validar_Conectividad('KD73')==1){
                        $vueltos =  AuditoriaKd73::where('id_operacion','=',$request->operacion)->get();
                        $operacion = OperacionKd73::find($request->operacion);
                        $mensaje=null;
                    }
                    else{
                        $vueltos=[];
                        $operacion=[];
                        $mensaje="No hay conexion con FARMACIAS KD EXPRESS, C.A. - KD73";
                    }
                break;
            }
            
        } 
        return view('pages.recargas.detalleOperacion', compact('mensaje','vueltos','operacion','fini','ffin','sedeUsuario','sede'));   
    }

}
