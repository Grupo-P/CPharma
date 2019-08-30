<?php

namespace compras\Http\Controllers;

use Illuminate\Http\Request;
use compras\Departamento;
use compras\User;
use compras\Auditoria;

class DepartamentoController extends Controller
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
        $departamentos =  Departamento::all();
        return view('pages.departamento.index', compact('departamentos'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('pages.departamento.create');
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
            $departamentos = new Departamento();
            $departamentos->nombre = $request->input('nombre');
            $departamentos->descripcion = $request->input('descripcion');            
            $departamentos->user = auth()->user()->name;
            $departamentos->estatus = 'ACTIVO';
            $departamentos->save();

            $Auditoria = new Auditoria();
            $Auditoria->accion = 'CREAR';
            $Auditoria->tabla = 'DEPARTAMENTO';
            $Auditoria->registro = $request->input('nombre');
            $Auditoria->user = auth()->user()->name;
            $Auditoria->save();

            return redirect()->route('departamento.index')->with('Saved', ' Informacion');
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
        $departamentos = Departamento::find($id);

        $Auditoria = new Auditoria();
        $Auditoria->accion = 'CONSULTAR';
        $Auditoria->tabla = 'DEPARTAMENTO';
        $Auditoria->registro = $departamentos->nombre;
        $Auditoria->user = auth()->user()->name;
        $Auditoria->save();

        return view('pages.departamento.show', compact('departamentos'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $departamentos = Departamento::find($id);    
        return view('pages.departamento.edit', compact('departamentos'));
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
            $departamentos = Departamento::find($id);
            $departamentos->fill($request->all());
            $departamentos->user = auth()->user()->name;
            $departamentos->estatus = 'ACTIVO';
            $departamentos->save();

            $Auditoria = new Auditoria();
            $Auditoria->accion = 'EDITAR';
            $Auditoria->tabla = 'DEPARTAMENTO';
            $Auditoria->registro = $departamentos->nombre;
            $Auditoria->user = auth()->user()->name;
            $Auditoria->save();

            return redirect()->route('departamento.index')->with('Updated', ' Informacion');
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
        $departamentos = Departamento::find($id);

        $Auditoria = new Auditoria();        
        $Auditoria->tabla = 'DEPARTAMENTO';
        $Auditoria->registro = $departamentos->nombre;
        $Auditoria->user = auth()->user()->name;        

         if($departamentos->estatus == 'ACTIVO'){
            $departamentos->estatus = 'INACTIVO';
            $Auditoria->accion = 'DESINCORPORAR';
         }
         else if($departamentos->estatus == 'INACTIVO'){
            $departamentos->estatus = 'ACTIVO';
            $Auditoria->accion = 'REINCORPORAR';
         }

         $departamentos->user = auth()->user()->name;      
         $departamentos->save();

         $Auditoria->save();

         return redirect()->route('departamento.index')->with('Deleted', ' Informacion');
    }
}
