<?php

namespace compras\Http\Controllers;

use Illuminate\Http\Request;
use compras\RH_Candidato_Prueba;
use compras\User;
use compras\Auditoria;
use compras\RH_Candidato;
use compras\RH_Prueba;

class RH_CandidatoPruebaController extends Controller {
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request) {
        $id_candidato = $request->input("CandidatoId");
        $candidato = RH_Candidato::find($id_candidato);

        $pruebas = RH_Prueba::all();

        return view('pages.RRHH.candidatos_pruebas.create', compact('candidato', 'pruebas'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request) {
        try {
            $fecha = $request->input('fecha');

            $candidatos_pruebas = new RH_Candidato_Prueba();

            $candidatos_pruebas->rh_candidatos_id = $request->input('CandidatoId');
            $candidatos_pruebas->rh_pruebas_id = $request->input('PruebaId');
            $candidatos_pruebas->fecha = date('Y-m-d', strtotime($fecha));
            $candidatos_pruebas->facilitador = $request->input('facilitador');
            $candidatos_pruebas->puntuacion = floatval($request->input('puntuacion'));
            $candidatos_pruebas->user = auth()->user()->name;
            $candidatos_pruebas->save();

            $candidato = RH_Candidato::find($request->input('CandidatoId'));
            $candidato->estatus = 'EN_PROCESO';
            $candidato->save();

            $Auditoria = new Auditoria();
            $Auditoria->accion = 'CREAR';
            $Auditoria->tabla = 'RHI_CANDIDATOS_PRUEBAS';
            $Auditoria->registro = $request->input('facilitador');
            $Auditoria->user = auth()->user()->name;
            $Auditoria->save();

            return redirect()
                ->route('candidatos.index')
                ->with('Saved1', ' Informacion');
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
