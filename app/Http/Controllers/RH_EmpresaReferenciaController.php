<?php

namespace compras\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

use compras\User;
use compras\Auditoria;
use compras\RH_EmpresaReferencia;

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
        $correo = $request->input('correo');
        $nombre_empresa = $request->input('nombre_empresa');

        if($correo != '') {
            if(RH_EmpresaReferencia::where('correo', '=', $correo)->exists()) {
                return back()->with('Error1', ' Error');
            }
        }

        if(RH_EmpresaReferencia::where('nombre_empresa', '=', $nombre_empresa)->exists()) {
            return back()->with('Error2', ' Error');
        }

        try {
            $empresaReferencias = new RH_EmpresaReferencia();
            $empresaReferencias->nombre_empresa = $nombre_empresa;
            $empresaReferencias->telefono = $request->input('telefono');
            $empresaReferencias->correo = $correo;
            $empresaReferencias->direccion = $request->input('direccion');
            $empresaReferencias->estatus = 'ACTIVO';
            $empresaReferencias->user = auth()->user()->name;
            $empresaReferencias->save();

            $Auditoria = new Auditoria();
            $Auditoria->accion = 'CREAR';
            $Auditoria->tabla = 'RH_EMPRESAREF';
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
        $Auditoria->tabla = 'RH_EMPRESAREF';
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
        try {
            $empresaReferencias = RH_EmpresaReferencia::find($id);
            $empresaReferencias->fill($request->all());

            $empresaReferencias->user = auth()->user()->name;
            $empresaReferencias->save();

            $Auditoria = new Auditoria();
            $Auditoria->accion = 'EDITAR';
            $Auditoria->tabla = 'RH_EMPRESAREF';
            $Auditoria->registro = $empresaReferencias->nombre_empresa;
            $Auditoria->user = auth()->user()->name;
            $Auditoria->save();

            return redirect()
                ->route('empresaReferencias.index')
                ->with('Updated', ' Informacion');
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
    public function destroy($id) {
        $empresaReferencias = RH_EmpresaReferencia::find($id);

        $Auditoria = new Auditoria();
        $Auditoria->tabla = 'RH_EMPRESAREF';
        $Auditoria->registro = $empresaReferencias->nombre_empresa;
        $Auditoria->user = auth()->user()->name;

        if($empresaReferencias->estatus == 'ACTIVO'){
            $empresaReferencias->estatus = 'INACTIVO';
            $Auditoria->accion = 'DESINCORPORAR';
        }
        else if($empresaReferencias->estatus == 'INACTIVO'){
            $empresaReferencias->estatus = 'ACTIVO';
            $Auditoria->accion = 'REINCORPORAR';
        }

        $empresaReferencias->user = auth()->user()->name;        
        $empresaReferencias->save();

        $Auditoria->save();

        if($empresaReferencias->estatus == 'ACTIVO'){
            return redirect()
                ->route('empresaReferencias.index')
                ->with('Deleted1', ' Informacion');
        }

        return redirect()
            ->route('empresaReferencias.index')
            ->with('Deleted', ' Informacion');
    }
}
