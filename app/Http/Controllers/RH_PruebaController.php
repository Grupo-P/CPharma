<?php

namespace compras\Http\Controllers;

use Illuminate\Http\Request;
use compras\RH_Prueba;
use compras\User;
use compras\Auditoria;

class RH_PruebaController extends Controller {
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
        $pruebas =  RH_Prueba::all();
        return view('pages.RRHH.pruebas.index', compact('pruebas'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create() {
        return view('pages.RRHH.pruebas.create');
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
            $pruebas = new RH_Prueba();
            $pruebas->tipo_prueba = $request->input('tipo_prueba');
            $pruebas->nombre_prueba = $request->input('nombre_prueba');
            $pruebas->estatus = 'ACTIVO';
            $pruebas->user = auth()->user()->name;
            $pruebas->save();

            return redirect()->route('pruebas.index')->with('Saved', ' Informacion');
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
        $pruebas = RH_Prueba::find($id);

        return view('pages.RRHH.pruebas.show', compact('pruebas'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $pruebas = RH_Prueba::find($id);
        return view('pages.RRHH.pruebas.edit', compact('pruebas'));
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
            $pruebas = RH_Prueba::find($id);
            $pruebas->fill($request->all());

            $pruebas->tipo_prueba = $request->input('tipo_prueba');
            $pruebas->nombre_prueba = $request->input('nombre_prueba');
            $pruebas->user = auth()->user()->name;
            $pruebas->save();

            return redirect()->route('pruebas.index')->with('Updated', ' Informacion');
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
        $pruebas = RH_Prueba::find($id);

        if($pruebas->estatus == 'ACTIVO'){
            $pruebas->estatus = 'INACTIVO';
        }
        else if($pruebas->estatus == 'INACTIVO'){
            $pruebas->estatus = 'ACTIVO';
        }

        $pruebas->user = auth()->user()->name;        
        $pruebas->save();

        if($pruebas->estatus == 'ACTIVO'){
            return redirect()->route('pruebas.index')->with('Deleted', ' Informacion');
        }

        return redirect()->route('pruebas.index')->with('Deleted1', ' Informacion');
    }
}
