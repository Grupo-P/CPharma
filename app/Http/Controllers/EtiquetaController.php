<?php

namespace compras\Http\Controllers;

use Illuminate\Http\Request;
use compras\Etiqueta;
use compras\User;
use compras\Auditoria;

class EtiquetaController extends Controller
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
        $etiquetas =  Etiqueta::all();
        return view('pages.etiqueta.index', compact('etiquetas'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        include(app_path().'\functions\config.php');
        include(app_path().'\functions\Querys.php');
        include(app_path().'\functions\funciones.php');
        include(app_path().'\functions\reportes.php');

        ValidarEtiquetas();

        return redirect()->action('EtiquetaController@index');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        return redirect()->action('EtiquetaController@index');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $etiqueta = Etiqueta::find($id); 

        $Auditoria = new Auditoria();
        $Auditoria->accion = 'CONSULTAR';
        $Auditoria->tabla = 'ETIQUETA';
        $Auditoria->registro = $etiqueta->descripcion;
        $Auditoria->user = auth()->user()->name;
        $Auditoria->save();

        return view('pages.etiqueta.show', compact('etiqueta'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $etiqueta = Etiqueta::find($id);
        return view('pages.etiqueta.edit', compact('etiqueta'));
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
        return redirect()->route('etiqueta.index')->with('Deleted', ' Informacion');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $etiqueta = Etiqueta::find($id);

        $Auditoria = new Auditoria();
        $Auditoria->tabla = 'ETIQUETA';
        $Auditoria->registro = $etiqueta->descripcion;
        $Auditoria->user = auth()->user()->name;

        if($etiqueta->estatus == 'ACTIVO'){
            $etiqueta->estatus = 'INACTIVO';
            $Auditoria->accion = 'DESINCORPORAR';
        }
        else if($etiqueta->estatus == 'INACTIVO'){
            $etiqueta->estatus = 'ACTIVO';
            $Auditoria->accion = 'REINCORPORAR';
        }

        $etiqueta->user = auth()->user()->name;        
        $etiqueta->save();

        $Auditoria->save();

        return redirect()->route('etiqueta.index')->with('Deleted', ' Informacion');
    }
}
