<?php

namespace compras\Http\Controllers;

use Illuminate\Http\Request;
use compras\Empresa;
use compras\User;
use compras\Auditoria;

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
        try{
            $empresa = new Empresa();
            $empresa->nombre = $request->input('nombre');
            $empresa->rif = $request->input('rif');
            $empresa->telefono = $request->input('telefono');
            $empresa->direccion = $request->input('direccion');
            $empresa->estatus = 'ACTIVO';
            $empresa->observacion = $request->input('observacion');
            $empresa->user = auth()->user()->name;
            $empresa->save();

            $Auditoria = new Auditoria();
            $Auditoria->accion = 'CREAR';
            $Auditoria->tabla = 'EMPRESA';
            $Auditoria->registro = $request->input('nombre');
            $Auditoria->user = auth()->user()->name;
            $Auditoria->save();

            return redirect()->route('empresa.index')->with('Saved', ' Informacion');
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
        $empresa = Empresa::find($id); 

        $Auditoria = new Auditoria();
        $Auditoria->accion = 'CONSULTAR';
        $Auditoria->tabla = 'EMPRESA';
        $Auditoria->registro = $empresa->nombre;
        $Auditoria->user = auth()->user()->name;
        $Auditoria->save();

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
        try{
            $empresa = Empresa::find($id);
            $empresa->fill($request->all());
            $empresa->user = auth()->user()->name;
            $empresa->observacion = $request->input('observacion');
            $empresa->save();

            $Auditoria = new Auditoria();
            $Auditoria->accion = 'EDITAR';
            $Auditoria->tabla = 'EMPRESA';
            $Auditoria->registro = $empresa->nombre;
            $Auditoria->user = auth()->user()->name;
            $Auditoria->save();

            return redirect()->route('empresa.index')->with('Updated', ' Informacion');
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
        $empresa = Empresa::find($id);

        $Auditoria = new Auditoria();
        $Auditoria->tabla = 'EMPRESA';
        $Auditoria->registro = $empresa->nombre;
        $Auditoria->user = auth()->user()->name;

        if($empresa->estatus == 'ACTIVO'){
            $empresa->estatus = 'INACTIVO';
            $Auditoria->accion = 'DESINCORPORAR';
        }
        else if($empresa->estatus == 'INACTIVO'){
            $empresa->estatus = 'ACTIVO';
            $Auditoria->accion = 'REINCORPORAR';
        }

        $empresa->user = auth()->user()->name;        
        $empresa->save();

        $Auditoria->save();

        return redirect()->route('empresa.index')->with('Deleted', ' Informacion');
    }                       
}