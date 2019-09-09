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
        return view('pages.etiqueta.create');
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
            $etiqueta = new Etiqueta();
            $etiqueta->id_articulo = $request->input('id_articulo');
            $etiqueta->codigo_articulo = $request->input('codigo_articulo');
            $etiqueta->descripcion = $request->input('descripcion');
            $etiqueta->condicion = 'ACTIVO';
            $etiqueta->clasificacion = 'ACTIVO';
            $etiqueta->estatus = 'ACTIVO';
            $etiqueta->user = auth()->user()->name;
            $etiqueta->save();

            $Auditoria = new Auditoria();
            $Auditoria->accion = 'CREAR';
            $Auditoria->tabla = 'ETIQUETA';
            $Auditoria->registro = $request->input('descripcion');
            $Auditoria->user = auth()->user()->name;
            $Auditoria->save();

            return redirect()->route('etiqueta.index')->with('Saved', ' Informacion');
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
        try{
            $etiqueta = Etiqueta::find($id);
            $etiqueta->fill($request->all());
            $etiqueta->user = auth()->user()->name;
            $etiqueta->save();

            $Auditoria = new Auditoria();
            $Auditoria->accion = 'EDITAR';
            $Auditoria->tabla = 'ETIQUETA';
            $Auditoria->registro = $etiqueta->descripcion;
            $Auditoria->user = auth()->user()->name;
            $Auditoria->save();

            return redirect()->route('etiqueta.index')->with('Updated', ' Informacion');
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
