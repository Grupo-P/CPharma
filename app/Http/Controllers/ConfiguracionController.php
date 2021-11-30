<?php

namespace compras\Http\Controllers;

use Illuminate\Http\Request;
use compras\Configuracion;
use compras\User;
use compras\Auditoria;

class ConfiguracionController extends Controller
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
        $configuraciones =  Configuracion::type()->get();

        if (auth()->user()->departamento == 'ADMINISTRACION') {
            $configuraciones =  Configuracion::where('variable', 'DolarCalculo')->get();
        }

        return view('pages.configuracion.index', compact('configuraciones'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('pages.configuracion.create');
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
            $configuraciones = new Configuracion();
            $configuraciones->variable = $request->input('variable');
            $configuraciones->descripcion = $request->input('descripcion');
            $configuraciones->valor = $request->input('valor');
            $configuraciones->user = auth()->user()->name;
            $configuraciones->estatus = 'ACTIVO';

            if ($_SERVER['SERVER_NAME'] == 'cpharmagpde.com' || $_SERVER['SERVER_NAME'] == 'cpharmagp.com') {
                $configuraciones->contabilidad = 1;
            }

            $configuraciones->save();

            $Auditoria = new Auditoria();
            $Auditoria->accion = 'CREAR';
            $Auditoria->tabla = 'CONFIGURACION';
            $Auditoria->registro = $request->input('variable');
            $Auditoria->user = auth()->user()->name;
            $Auditoria->save();

            return redirect()->route('configuracion.index')->with('Saved', ' Informacion');
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
        $configuraciones = Configuracion::find($id);

        $Auditoria = new Auditoria();
        $Auditoria->accion = 'CONSULTAR';
        $Auditoria->tabla = 'CONFIGURACION';
        $Auditoria->registro = $configuraciones->variable;
        $Auditoria->user = auth()->user()->name;
        $Auditoria->save();

        return view('pages.configuracion.show', compact('configuraciones'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $configuraciones = Configuracion::find($id);
        return view('pages.configuracion.edit', compact('configuraciones'));
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
            $configuraciones = Configuracion::find($id);
            $configuraciones->fill($request->all());
            $configuraciones->user = auth()->user()->name;
            $configuraciones->estatus = 'ACTIVO';
            $configuraciones->save();

            $Auditoria = new Auditoria();
            $Auditoria->accion = 'EDITAR';
            $Auditoria->tabla = 'CONFIGURACION';
            $Auditoria->registro = $configuraciones->variable;
            $Auditoria->user = auth()->user()->name;
            $Auditoria->save();

            return redirect()->route('configuracion.index')->with('Updated', ' Informacion');
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
        $configuraciones = Configuracion::find($id);

        $Auditoria = new Auditoria();
        $Auditoria->tabla = 'CONFIGURACION';
        $Auditoria->registro = $configuraciones->variable;
        $Auditoria->user = auth()->user()->name;

         if($configuraciones->estatus == 'ACTIVO'){
            $configuraciones->estatus = 'INACTIVO';
            $Auditoria->accion = 'DESINCORPORAR';
         }
         else if($configuraciones->estatus == 'INACTIVO'){
            $configuraciones->estatus = 'ACTIVO';
            $Auditoria->accion = 'REINCORPORAR';
         }

         $configuraciones->user = auth()->user()->name;
         $configuraciones->save();

         $Auditoria->save();

         return redirect()->route('configuracion.index')->with('Deleted', ' Informacion');
    }
}
