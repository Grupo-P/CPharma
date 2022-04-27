<?php

namespace compras\Http\Controllers;

use Illuminate\Http\Request;
use compras\Auditoria;
use compras\Traslado;
use compras\TrasladoDetalle;
use compras\Sede;
use compras\User;
use compras\Configuracion;

class TrasladoController extends Controller
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
    public function index()
    {
        if(isset($_GET['Tipo'])){
            $Tipo = $_GET['Tipo'];
        }
        else{
            $Tipo = 3;
        }
        
        switch ($Tipo) {
            case 0:
                $traslados = Traslado::orderBy('id', 'desc')
                    ->where('estatus','PROCESADO')
                    ->limite(request('cantidad'))
                    ->get();
            break;

            case 1:
                $traslados = Traslado::orderBy('id', 'asc')
                    ->where('estatus','EMBALADO')
                    ->limite(request('cantidad'))
                    ->get();
            break;

            case 2:
                $traslados = Traslado::orderBy('id', 'asc')
                    ->where('estatus','ENTREGADO')
                    ->limite(request('cantidad'))
                    ->get();
            break;

            default:
                $traslados = Traslado::orderBy('fecha_traslado','desc')
                    ->limite(request('cantidad'))
                    ->get();
            break;
        }

        return view('pages.traslado.index', compact('traslados'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {   
       $sedes = Sede::pluck('razon_social','id');
       return view('pages.traslado.create', compact('sedes'));
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
            include(app_path().'\functions\config.php');
            include(app_path().'\functions\functions.php');
            include(app_path().'\functions\querys_mysql.php');
            include(app_path().'\functions\querys_sqlserver.php');

            $traslado = new Traslado();
            $IdAjuste = $request->input('IdAjuste');
            $SedeConnection = $request->input('SEDE');
            $NumeroAjuste = $request->input('numero_ajuste');

            $connCPharma = FG_Conectar_CPharma();
            $tasa = FG_Tasa_Fecha($connCPharma,date('Y-m-d'));
            mysqli_close($connCPharma);

            if(empty($tasa)){
                return back()->with('tasaNula','Error');
            }
        /*INICIO ENCABEZADO DEL TRASLADO*/
            $traslado->numero_ajuste = $NumeroAjuste;
            $traslado->fecha_ajuste = $request->input('fecha_ajuste');
            $traslado->operador_ajuste = $request->input('operador_ajuste');
            $traslado->fecha_traslado = $request->input('fecha_traslado');
            $traslado->operador_traslado = $request->input('operador_traslado');
            $traslado->sede_emisora = $request->input('sede_emisora');
            $traslado->sede_destino = $request->input('sede_destino');
            $traslado->estatus = 'PROCESADO';
            $traslado->bultos = '0';
            $traslado->fecha_tasa = date('d-m-Y');
            $traslado->tasa = $tasa;
            $traslado->save();
        /*FIN ENCABEZADO DEL TRASLADO*/                   
        /*INICIO DETALLE DEL TRASLADO*/
            FG_Traslado_Detalle($SedeConnection,$NumeroAjuste,$IdAjuste);
        /*FIN DETALLE DEL TRASLADO*/
        /*INICIO AUDITORIA*/
            $Auditoria = new Auditoria();
            $Auditoria->accion = 'CREAR';
            $Auditoria->tabla = 'TRASLADO';
            $Auditoria->registro = $request->input('numero_ajuste');
            $Auditoria->user = auth()->user()->name;
            $Auditoria->save();
        /*FIN AUDITORIA*/
            return redirect()->route('traslado.index')->with('Saved', ' Informacion');
        }
        catch(\Illuminate\Database\QueryException $e){
          return back()->with('Error', 'Error');
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
        $traslado = Traslado::find($id); 

        $Auditoria = new Auditoria();
        $Auditoria->accion = 'IMPRIMIR';
        $Auditoria->tabla = 'TRASLADO';
        $Auditoria->registro = $traslado->numero_ajuste;
        $Auditoria->user = auth()->user()->name;
        $Auditoria->save();

        return view('pages.traslado.show', compact('traslado'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $traslado = Traslado::find($id); 
        return view('pages.traslado.edit', compact('traslado'));
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
          $traslado = Traslado::find($id);
          $traslado->fill($request->all());
          $traslado->estatus = 'EMBALADO';
          $traslado->fecha_embalaje = date('Y-m-d');
          $traslado->operador_embalaje = auth()->user()->name;
          $traslado->fecha_envio = date('Y-m-d');
          $traslado->operador_envio = auth()->user()->name;
          $traslado->save();

          $Auditoria = new Auditoria();
          $Auditoria->accion = 'IMPRIMIR';
          $Auditoria->tabla = 'GUIA ENVIO Y ETIQUETAS';
          $Auditoria->registro = $traslado->numero_ajuste;
          $Auditoria->user = auth()->user()->name;
          $Auditoria->save();

          return redirect()->route('traslado.index')->with('Updated', ' Informacion');
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
        $traslado = Traslado::find($id);

        $Auditoria = new Auditoria();        
        $Auditoria->tabla = 'TRASLADO';
        $Auditoria->registro = $traslado->numero_ajuste;
        $Auditoria->user = auth()->user()->name;        

        if($traslado->estatus == 'EMBALADO' || $traslado->estatus == 'ENTREGADO'){
            $traslado->estatus = 'ENTREGADO';
            $Auditoria->accion = 'FINALIZADO';
         }   
         $traslado->save();

         $Auditoria->save();

         return redirect()->route('traslado.index')->with('Deleted', ' Informacion');
    }

    public function finalizarConReclamo()
    {
        if (request()->method() == 'POST') {
            include(app_path().'\functions\config.php');
            include(app_path().'\functions\functions.php');
            include(app_path().'\functions\querys_mysql.php');
            include(app_path().'\functions\querys_sqlserver.php');

            $old = Traslado::where('numero_ajuste', request()->ajuste)->first();
            $old->estatus = 'ENTREGADO CON RECLAMO';
            $old->save();

            $connCPharma = FG_Conectar_CPharma();
            $tasa = FG_Tasa_Fecha($connCPharma, date('Y-m-d', strtotime($old->fecha_tasa)));
            mysqli_close($connCPharma);

            if(empty($tasa)){
                return back()->with('tasaNula','Error');
            }

            $new = new Traslado;
            $new->numero_ajuste = 'R' . $old->numero_ajuste;
            $new->fecha_ajuste = $old->fecha_traslado;
            $new->fecha_traslado = $old->fecha_traslado;
            $new->sede_emisora = $old->sede_destino;
            $new->sede_destino = $old->sede_emisora;
            $new->operador_ajuste = $old->operador_ajuste;
            $new->operador_traslado = auth()->user()->name;
            $new->estatus = 'PROCESADO';
            $new->bultos = '0';
            $new->fecha_tasa = $old->fecha_tasa;
            $new->tasa = $old->tasa;
            $new->save();

            foreach (request()->reclamos as $reclamo) {

                if ($reclamo['cantidad']) {
                    $detalleOld = TrasladoDetalle::where('id_traslado', $old->numero_ajuste)
                        ->where('codigo_barra', $reclamo['codigo_barra'])
                        ->first();

                    $detalleNew = new TrasladoDetalle;
                    $detalleNew->id_traslado = $new->numero_ajuste;
                    $detalleNew->id_articulo = $detalleOld->id_articulo;
                    $detalleNew->codigo_interno = $detalleOld->codigo_interno;
                    $detalleNew->codigo_barra = $detalleOld->codigo_barra;
                    $detalleNew->descripcion = $detalleOld->descripcion;
                    $detalleNew->causa = $reclamo['causa'];
                    $detalleNew->gravado = $detalleOld->gravado;
                    $detalleNew->dolarizado = $detalleOld->dolarizado;
                    $detalleNew->cantidad = $reclamo['cantidad'];
                    $detalleNew->costo_unit_bs_sin_iva = $detalleOld->costo_unit_bs_sin_iva;
                    $detalleNew->costo_unit_usd_sin_iva = $detalleOld->costo_unit_usd_sin_iva;
                    $detalleNew->total_imp_bs = $detalleOld->total_imp_bs;
                    $detalleNew->total_imp_usd = $detalleOld->total_imp_usd;
                    $detalleNew->total_bs = $detalleOld->total_bs;
                    $detalleNew->total_usd = $detalleOld->total_usd;
                    $detalleNew->save();
                }
            }

            $auditoria = new Auditoria();
            $auditoria->accion = 'CREAR';
            $auditoria->tabla = 'TRASLADO';
            $auditoria->registro = $old->numero_ajuste;
            $auditoria->user = auth()->user()->name;
            $auditoria->save();

            return redirect()->route('traslado.index')->with('Saved', ' Informacion');
        }

        $traslado = Traslado::find(request()->traslado);
        return view('pages.traslado.finalizarConReclamo', compact('traslado'));
    }
}
