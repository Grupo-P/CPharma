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
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        include app_path() . '/functions/config.php';
        include app_path() . '/functions/functions.php';

        $RutaUrl = FG_Mi_Ubicacion();
        //$RutaUrl = 'FAU';
        $SedeConnection = $RutaUrl;
        $conn = FG_Conectar_Smartpharma($SedeConnection);
        $sedeUsuario = Auth::user()->sede;

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
            switch($request->sede)
            {
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
            $sede=$request->sede;
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
                $registro = [
                    'sede'=>$vuelto->sede,
                    'cedula_cliente_factura' =>$row["CodigoCliente"],
                    'telefono_cliente_factura' =>$row["Telefono"],
                    'fecha_hora_factura'=>$row['FechaDocumento']->format('Y-m-d H:i:s'),
                    'cajero_venta' =>$row["Auditoria_Usuario"],                
                    'numero_factura' => $row["NumeroFactura"] ,
                    'nombre_cliente'=>$row["nombre"] ,
                    'total_factura'=>$row["totalFactura"] ,
                    'id'=>$vuelto->id,  
                    'id_factura'=> $vuelto->id_factura,  
                    'fecha_hora'=> $vuelto->fecha_hora,  
                    'banco_cliente'=> $vuelto->banco_cliente,  
                    'cedula_cliente'=> $vuelto->cedula_cliente,  
                    'telefono_pago_movil'=> $vuelto->telefono_cliente,  
                    'monto' => $vuelto->monto,
                    'sede'=> $vuelto->sede,  
                    'caja'=> $vuelto->caja,  
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
    public function create()
    {
        return view('pages.configuracion.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        try{
            $configuraciones = new Configuracion();
            $configuraciones->variable = $request->input('variable');
            $configuraciones->descripcion = $request->input('descripcion');
            $configuraciones->valor = $request->input('valor');
            $configuraciones->user = auth()->user()->name;
            $configuraciones->estatus = 'ACTIVO';

            if ($_SERVER['SERVER_NAME'] == 'cpharmagpde.com' || $_SERVER['SERVER_NAME'] == 'cpharmagp.com') {
                $configuraciones->contabilidad = 1;
            }

            $configuraciones->save();

            $Auditoria = new Auditoria();
            $Auditoria->accion = 'CREAR';
            $Auditoria->tabla = 'CONFIGURACION';
            $Auditoria->registro = $request->input('variable');
            $Auditoria->user = auth()->user()->name;
            $Auditoria->save();

            return redirect()->route('configuracion.index')->with('Saved', ' Informacion');
        }
        catch(\Illuminate\Database\QueryException $e){
            return back()->with('Error', ' Error');
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $configuraciones = Configuracion::find($id);

        $Auditoria = new Auditoria();
        $Auditoria->accion = 'CONSULTAR';
        $Auditoria->tabla = 'CONFIGURACION';
        $Auditoria->registro = $configuraciones->variable;
        $Auditoria->user = auth()->user()->name;
        $Auditoria->save();

        return view('pages.configuracion.show', compact('configuraciones'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $configuraciones = Configuracion::find($id);
        return view('pages.configuracion.edit', compact('configuraciones'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        try{
            $configuraciones = Configuracion::find($id);
            $configuraciones->fill($request->all());
            $configuraciones->user = auth()->user()->name;
            $configuraciones->estatus = 'ACTIVO';
            $configuraciones->save();

            $Auditoria = new Auditoria();
            $Auditoria->accion = 'EDITAR';
            $Auditoria->tabla = 'CONFIGURACION';
            $Auditoria->registro = $configuraciones->variable;
            $Auditoria->user = auth()->user()->name;
            $Auditoria->save();

            return redirect()->route('configuracion.index')->with('Updated', ' Informacion');
        }
        catch(\Illuminate\Database\QueryException $e){
            return back()->with('Error', ' Error');
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $configuraciones = Configuracion::find($id);

        $Auditoria = new Auditoria();
        $Auditoria->tabla = 'CONFIGURACION';
        $Auditoria->registro = $configuraciones->variable;
        $Auditoria->user = auth()->user()->name;

         if($configuraciones->estatus == 'ACTIVO'){
            $configuraciones->estatus = 'INACTIVO';
            $Auditoria->accion = 'DESINCORPORAR';
         }
         else if($configuraciones->estatus == 'INACTIVO'){
            $configuraciones->estatus = 'ACTIVO';
            $Auditoria->accion = 'REINCORPORAR';
         }

         $configuraciones->user = auth()->user()->name;
         $configuraciones->save();

         $Auditoria->save();

         return redirect()->route('configuracion.index')->with('Deleted', ' Informacion');
    }
}
