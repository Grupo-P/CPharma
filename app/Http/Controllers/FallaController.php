<?php

namespace compras\Http\Controllers;

use Illuminate\Http\Request;
use compras\Falla;

class FallaController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $FInicio =  $request->input('fechaInicio');
        $FFin =  $request->input('fechaFin');
        $FFin = date("Y-m-d",strtotime($FFin."+ 1 days"));
        $fallas =  
        Falla::orderBy('created_at', 'desc')->
        whereBetween('created_at',[$FInicio,$FFin])->get();

        $Auditoria = new Auditoria();
        $Auditoria->accion = 'CONSULTAR';
        $Auditoria->tabla = 'REPORTE';
        $Auditoria->registro = 'Registro de Fallas';
        $Auditoria->user = auth()->user()->name;
        $Auditoria->save();

        return view('pages.falla.index', compact('fallas'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('pages.falla.create');
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
            $falla = new Falla();
            $falla->falla = $request->input('falla');
            $falla->usuario = $request->input('usuario');
            $falla->estacion = $request->input('estacion');
            $falla->cliente = $request->input('cliente');
            $falla->telefono = $request->input('telefono');
            $falla->save();

            return back()->with('Saved', ' Informacion');
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
