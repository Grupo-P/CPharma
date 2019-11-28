<?php

namespace compras\Http\Controllers;

use Illuminate\Http\Request;
use compras\RH_Fase;
use compras\User;
use compras\Auditoria;

class RH_FaseController extends Controller {
    /**
     * Create a new controller instance with auth.
     *
     * @return void
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
        $fases = RH_Fase::all();
        return view('pages.RRHH.fases.index', compact('fases'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create() {
        return view('pages.RRHH.fases.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request) {
        try {
            $fases = new RH_Fase();
            $fases->nombre_fase = $request->input('nombre_fase');
            $fases->estatus = 'ACTIVO';
            $fases->user = auth()->user()->name;
            $fases->save();

            $Auditoria = new Auditoria();
            $Auditoria->accion = 'CREAR';
            $Auditoria->tabla = 'RH_FASES';
            $Auditoria->registro = $request->input('nombre_fase');
            $Auditoria->user = auth()->user()->name;
            $Auditoria->save();

            return redirect()
                ->route('fases.index')
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
        $fases = RH_Fase::find($id);

        $Auditoria = new Auditoria();
        $Auditoria->accion = 'CONSULTAR';
        $Auditoria->tabla = 'RH_FASES';
        $Auditoria->registro = $fases->nombre_fase;
        $Auditoria->user = auth()->user()->name;
        $Auditoria->save();

        return view('pages.RRHH.fases.show', compact('fases'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id) {
        $fases = RH_Fase::find($id);
        return view('pages.RRHH.fases.edit', compact('fases'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id) {
        
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id) {
        //
    }
}
