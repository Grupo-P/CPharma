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
        $empresas =  Empresa::all();
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
        $empresa->estatus = 'ACTIVO';
        $empresa->observacion = $request->input('observacion');
        $empresa->user = auth()->user()->name;
        $empresa->save();
        return redirect()->route('empresa.index')->with('Saved', ' Informacion');
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
        return redirect()->route('empresa.index')->with('Updated', ' Informacion');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
         $empresa = Empresa::find($id);

         if($empresa->estatus == 'ACTIVO'){
            $empresa->estatus = 'INACTIVO';
         }
         else if($empresa->estatus == 'INACTIVO'){
            $empresa->estatus = 'ACTIVO';
         }

         $empresa->user = auth()->user()->name;        
         $empresa->save();
         return redirect()->route('empresa.index')->with('Deleted', ' Informacion');
    }
}
