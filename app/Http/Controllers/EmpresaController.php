<?php

namespace compras\Http\Controllers;

use Illuminate\Http\Request;
use compras\Empresa;
use compras\User;

class EmpresaController extends Controller
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
        $empresas =  Empresa::where('estatus','=','ACTIVO')->get();
        return view('pages.empresa.index', compact('empresas'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('pages.empresa.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $empresa = new Empresa();
        $empresa->nombre = $request->input('nombre');
        $empresa->rif = $request->input('rif');
        $empresa->telefono = $request->input('telefono');
        $empresa->direccion = $request->input('direccion');
        $empresa->Estatus = 'ACTIVO';
        $empresa->user = auth()->user()->name;
        $empresa->save();
        return ('Guardado');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $empresa = Empresa::find($id);        
        return view('pages.empresa.show', compact('empresa'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {   
        $empresa = Empresa::find($id);
        return view('pages.empresa.edit', compact('empresa'));
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
        $empresa = Empresa::find($id);
        $empresa->fill($request->all());
        $empresa->user = auth()->user()->name;
        $empresa->save();
        return ('Actualizado');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        // $empresa = Empresa::find($id);
        // $empresa->Estatus = 'INACTIVO';
        // $empresa->user = auth()->user()->name;
        // $empresa->save();
        // return ('Eliminado');
    }
}
