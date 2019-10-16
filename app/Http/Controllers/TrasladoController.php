<?php

namespace compras\Http\Controllers;

use Illuminate\Http\Request;
use compras\Auditoria;
use compras\Traslado;
use compras\Sede;
use compras\User;

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
        $traslados =  Traslado::all();
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
        /*INICIO ENCABEZADO DEL TRASLADO*/
            $traslado->numero_ajuste = $NumeroAjuste;
            $traslado->fecha_ajuste = $request->input('fecha_ajuste');
            $traslado->operador_ajuste = $request->input('operador_ajuste');
            $traslado->fecha_traslado = $request->input('fecha_traslado');
            $traslado->operador_traslado = $request->input('operador_traslado');
            $traslado->sede_emisora = $request->input('sede_emisora');
            $traslado->sede_destino = $request->input('sede_destino');
            $traslado->estatus = 'PROCESADO';
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
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
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
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
