<?php

namespace compras\Http\Controllers;

use Illuminate\Http\Request;
use compras\RH_Vacante;
use compras\Sede;
use compras\User;
use compras\Auditoria;

class RH_VacanteController extends Controller {
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
        $vacantes = RH_Vacante::all();

        return view('pages.RRHH.vacantes.index', compact('vacantes'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create() {
        $sedes = Sede::pluck('razon_social', 'siglas');
        return view('pages.RRHH.vacantes.create', compact('sedes'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request) {
        try {
            $fecha_solicitud = $request->input('fecha_solicitud');
            $fecha_limite = $request->input('fecha_limite');
            
            $vacantes = new RH_Vacante();

            $vacantes->nombre_vacante = $request->input('nombre_vacante');
            $vacantes->departamento = $request->input('departamento');
            $vacantes->turno = $request->input('turno');
            $vacantes->dias_libres = $request->input('dias_libres');
            $vacantes->sede = $request->input('sede');
            $vacantes->cantidad = intval($request->input('cantidad'));
            $vacantes->fecha_solicitud = date('Y-m-d',strtotime($fecha_solicitud));
            $vacantes->fecha_limite = date('Y-m-d',strtotime($fecha_limite));
            $vacantes->nivel_urgencia = $request->input('nivel_urgencia');
            $vacantes->solicitante = $request->input('solicitante');
            $vacantes->comentarios = $request->input('comentarios');
            $vacantes->estatus = 'ACTIVO';
            $vacantes->user = auth()->user()->name;
            $vacantes->save();

            $Auditoria = new Auditoria();
            $Auditoria->accion = 'CREAR';
            $Auditoria->tabla = 'RH_VACANTES';
            $Auditoria->registro = $request->input('nombre_vacante');
            $Auditoria->user = auth()->user()->name;
            $Auditoria->save();

            return redirect()
                ->route('vacantes.index')
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
        $vacantes = RH_Vacante::find($id);

        $Auditoria = new Auditoria();
        $Auditoria->accion = 'CONSULTAR';
        $Auditoria->tabla = 'RH_VACANTES';
        $Auditoria->registro = $vacantes->nombre_vacante;
        $Auditoria->user = auth()->user()->name;
        $Auditoria->save();

        return view('pages.RRHH.vacantes.show', compact('vacantes'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id) {
        $vacantes = RH_Vacante::find($id);
        $sedes = Sede::pluck('razon_social', 'siglas');

        return view('pages.RRHH.vacantes.edit', compact('vacantes', 'sedes'));
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
            $vacantes = RH_Vacante::find($id);
            
            $vacantes->fill($request->all());
            $vacantes->user = auth()->user()->name;

            $vacantes->save();

            $Auditoria = new Auditoria();
            $Auditoria->accion = 'EDITAR';
            $Auditoria->tabla = 'RH_VACANTES';
            $Auditoria->registro = $vacantes->nombre_vacante;
            $Auditoria->user = auth()->user()->name;
            $Auditoria->save();

            return redirect()
                ->route('vacantes.index')
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
        $vacantes = RH_Vacante::find($id);

        $Auditoria = new Auditoria();
        $Auditoria->tabla = 'RH_VACANTES';
        $Auditoria->registro = $vacantes->nombre_vacante;
        $Auditoria->user = auth()->user()->name;

        if($vacantes->estatus == 'ACTIVO') {
            $vacantes->estatus = 'INACTIVO';
            $Auditoria->accion = 'DESINCORPORAR';
        }
        else if($vacantes->estatus == 'INACTIVO') {
            $vacantes->estatus = 'ACTIVO';
            $Auditoria->accion = 'REINCORPORAR';
        }

        $vacantes->user = auth()->user()->name;
        $vacantes->save();

        $Auditoria->save();

        if($vacantes->estatus == 'ACTIVO') {
            return redirect()
                ->route('vacantes.index')
                ->with('Deleted1', ' Informacion');
        }

        return redirect()
            ->route('vacantes.index')
            ->with('Deleted', ' Informacion');
    }
}
