<?php

namespace compras\Http\Controllers;

use Illuminate\Http\Request;
use compras\Unidad;
use compras\Auditoria;

class UnidadController extends Controller
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
        $unidades =  Unidad::all();
        return view('pages.unidad.index', compact('unidades'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('pages.unidad.create');
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
            $unidad = new Unidad();
            $unidad->id_articulo = $request->input('id_articulo');
            $unidad->codigo_interno= $request->input('codigo_interno');
            $unidad->codigo_barra = $request->input('codigo_barra');
            $unidad->divisor = $request->input('divisor');
            $unidad->unidad_minima = $request->input('unidad_minima');
            $unidad->estatus = 'ACTIVO';
            $unidad->user = auth()->user()->name;
            $unidad->save();

            $Auditoria = new Auditoria();
            $Auditoria->accion = 'CREAR';
            $Auditoria->tabla = 'UNIDAD';
            $Auditoria->registro = "".$request->input('divisor')." ".$request->input('unidad_minima');
            $Auditoria->user = auth()->user()->name;
            $Auditoria->save();

            return redirect()->route('unidad.index')->with('Saved', ' Informacion');
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
