<?php

namespace compras\Http\Controllers;

use Illuminate\Http\Request;
use compras\RH_Vacante;
use compras\Sede;
use compras\User;

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
        $sedes = Sede::pluck('razon_social');

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

            return redirect()->route('vacantes.index')->with('Saved', ' Informacion');
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
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id) {
        //
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
