<?php

namespace compras\Http\Controllers;

use Illuminate\Http\Request;
use compras\RH_EmpresaReferencia;
use compras\User;
use compras\Auditoria;

class RH_EmpresaReferenciaController extends Controller {
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
        $empresaReferencias = RH_EmpresaReferencia::all();

        return view(
            'pages.RRHH.empresaReferencias.index', 
            compact('empresaReferencias')
        );
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create() {
        return view('pages.RRHH.empresaReferencias.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request) {
        try {
            $empresaReferencias = new RH_EmpresaReferencia();
            $empresaReferencias->nombre_empresa = $request->input('nombre_empresa');
            $empresaReferencias->telefono = $request->input('telefono');
            $empresaReferencias->correo = $request->input('correo');
            $empresaReferencias->direccion = $request->input('direccion');
            $empresaReferencias->estatus = 'ACTIVO';
            $empresaReferencias->user = auth()->user()->name;
            $empresaReferencias->save();

            $Auditoria = new Auditoria();
            $Auditoria->accion = 'CREAR';
            $Auditoria->tabla = 'RH_EMPRESA_REFERENCIAS';
            $Auditoria->registro = $request->input('nombre_empresa');
            $Auditoria->user = auth()->user()->name;
            $Auditoria->save();

            return redirect()
                ->route('empresaReferencias.index')
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
        $empresaReferencias = RH_EmpresaReferencia::find($id);

        $Auditoria = new Auditoria();
        $Auditoria->accion = 'CONSULTAR';
        $Auditoria->tabla = 'RH_EMPRESA_REFERENCIAS';
        $Auditoria->registro = $empresaReferencias->nombre_empresa;
        $Auditoria->user = auth()->user()->name;
        $Auditoria->save();

        return view(
            'pages.RRHH.empresaReferencias.show', 
            compact('empresaReferencias')
        );
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id) {
        $empresaReferencias = RH_EmpresaReferencia::find($id);

        return view(
            'pages.RRHH.empresaReferencias.edit', 
            compact('empresaReferencias')
        );
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id) {
        //
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
