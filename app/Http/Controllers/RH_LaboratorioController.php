<?php

namespace compras\Http\Controllers;

use Illuminate\Http\Request;
use compras\RH_Laboratorio;
use compras\User;
use compras\Auditoria;

class RH_LaboratorioController extends Controller { 
	/**
     * Create a new controller instance with auth.
     *
     * 
     */
    public function __construct() {
        $this->middleware('auth');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() {
       $laboratorio = RH_Laboratorio::all();
       return view('pages.RRHH.laboratorio.index', compact('laboratorio'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create() {
          return view('pages.RRHH.laboratorio.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request) {

        $rif = $request->input('tipo') . "-" . $request->input('rif');

        if(RH_Laboratorio::where('rif', '=', $rif)->exists()) {
            return back()->with('Error1', ' Error');
        }

        try {
            $laboratorio = new RH_Laboratorio();

            $laboratorio->rif = $rif;
            $laboratorio->nombre = $request->input('nombre');
            $laboratorio->direccion = $request->input('direccion');
            $laboratorio->telefono_celular = $request->input('telefono_celular');
            $laboratorio->telefono_fijo = $request->input('telefono_fijo');
            $laboratorio->estatus = 'ACTIVO';
            $laboratorio->user = auth()->user()->name;
            $laboratorio->save();

            $Auditoria = new Auditoria();
            $Auditoria->accion = 'CREAR';
            $Auditoria->tabla = 'RH_LABORATORIO';
            $Auditoria->registro = $request->input('nombre');
            $Auditoria->user = auth()->user()->name;
            $Auditoria->save();

            return redirect()
                ->route('laboratorio.index')
                ->with('Saved', ' Informacion');
        }
        catch(\Illuminate\Database\QueryException $e) {
            return back()->with('Error', ' Error');
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id) {
        $laboratorio = RH_Laboratorio::find($id);

        $Auditoria = new Auditoria();
        $Auditoria->accion = 'CONSULTAR';
        $Auditoria->tabla = 'RH_LABORATORIO';
        $Auditoria->registro = $laboratorio->nombre;
        $Auditoria->user = auth()->user()->name;
        $Auditoria->save();

        return view('pages.RRHH.laboratorio.show', compact('laboratorio'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id) {
        $laboratorio = RH_Laboratorio::find($id);

        return view('pages.RRHH.laboratorio.edit', compact('laboratorio'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id) {

        $rif = $request->input('tipo') . "-" . $request->input('rif');

        try {
            $laboratorio = RH_Laboratorio::find($id);

            $laboratorio->fill($request->all());
            
            $laboratorio->rif = $rif;
            $laboratorio->user = auth()->user()->name;

            $laboratorio->save();

            $Auditoria = new Auditoria();
            $Auditoria->accion = 'EDITAR';
            $Auditoria->tabla = 'RH_LABORATORIO';
            $Auditoria->registro = $laboratorio->nombre;
            $Auditoria->user = auth()->user()->name;
            $Auditoria->save();

            return redirect()
                ->route('laboratorio.index')
                ->with('Updated', ' Informacion');
        }
        catch(\Illuminate\Database\QueryException $e) {
            return back()->with('Error', ' Error');
        }
            }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id) {
        $laboratorio = RH_Laboratorio::find($id);

        $Auditoria = new Auditoria();
        $Auditoria->tabla = 'RH_LABORATORIO';
        $Auditoria->registro = $laboratorio->nombre;
        $Auditoria->user = auth()->user()->name;

        if($laboratorio->estatus == 'ACTIVO'){
            $laboratorio->estatus = 'INACTIVO';
            $Auditoria->accion = 'DESINCORPORAR';
        }
        else if($laboratorio->estatus == 'INACTIVO'){
            $laboratorio->estatus = 'ACTIVO';
            $Auditoria->accion = 'REINCORPORAR';
        }

        $laboratorio->user = auth()->user()->name;        
        $laboratorio->save();

        $Auditoria->save();

        if($laboratorio->estatus == 'ACTIVO'){
            return redirect()
                ->route('laboratorio.index')
                ->with('Deleted', ' Informacion');
        }

        return redirect()
            ->route('laboratorio.index')
            ->with('Deleted1', ' Informacion');
    }
             

}
