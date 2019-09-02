<?php

namespace compras\Http\Controllers;

use Illuminate\Http\Request;
use compras\Sede;
use compras\User;
use compras\Auditoria;

class SedeController extends Controller
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
        $sedes =  Sede::all();
        return view('pages.sede.index', compact('sedes'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('pages.sede.create');
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
            $sedes = new Sede();
            $sedes->rif = $request->input('rif');
            $sedes->razon_social = $request->input('razon_social');
            $sedes->siglas = $request->input('siglas');   
            $sedes->direccion = $request->input('direccion');       
            $sedes->user = auth()->user()->name;
            $sedes->estatus = 'ACTIVO';
            $sedes->save();

            $Auditoria = new Auditoria();
            $Auditoria->accion = 'CREAR';
            $Auditoria->tabla = 'SEDE';
            $Auditoria->registro = $request->input('razon_social');
            $Auditoria->user = auth()->user()->name;
            $Auditoria->save();

            return redirect()->route('sede.index')->with('Saved', ' Informacion');
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
        $sedes = Sede::find($id); 

        $Auditoria = new Auditoria();
        $Auditoria->accion = 'CONSULTAR';
        $Auditoria->tabla = 'SEDE';
        $Auditoria->registro = $sedes->razon_social;
        $Auditoria->user = auth()->user()->name;
        $Auditoria->save();

        return view('pages.sede.show', compact('sedes'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $sedes = Sede::find($id);    
        return view('pages.sede.edit', compact('sedes'));
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
            $sedes = Sede::find($id);
            $sedes->fill($request->all());
            $sedes->user = auth()->user()->name;
            $sedes->estatus = 'ACTIVO';
            $sedes->save();

            $Auditoria = new Auditoria();
            $Auditoria->accion = 'EDITAR';
            $Auditoria->tabla = 'SEDE';
            $Auditoria->registro = $sedes->razon_social;
            $Auditoria->user = auth()->user()->name;
            $Auditoria->save();

            return redirect()->route('sede.index')->with('Updated', ' Informacion');
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
        $sedes = Sede::find($id);

        $Auditoria = new Auditoria();
        $Auditoria->tabla = 'SEDE';
        $Auditoria->registro = $sedes->razon_social;
        $Auditoria->user = auth()->user()->name;

         if($sedes->estatus == 'ACTIVO'){
            $sedes->estatus = 'INACTIVO';
            $Auditoria->accion = 'DESINCORPORAR';
         }
         else if($sedes->estatus == 'INACTIVO'){
            $sedes->estatus = 'ACTIVO';
            $Auditoria->accion = 'REINCORPORAR';
         }

         $sedes->user = auth()->user()->name;      
         $sedes->save();

         $Auditoria->save();

         return redirect()->route('sede.index')->with('Deleted', ' Informacion');
    }
}
