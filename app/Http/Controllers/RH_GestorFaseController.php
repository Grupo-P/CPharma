<?php

namespace compras\Http\Controllers;

use Illuminate\Http\Request;
use compras\RHI_Candidato_Fase;
use compras\User;
use compras\Auditoria;
use compras\RH_Candidato;
use compras\RH_Fase;

class RH_GestorFaseController extends Controller {
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
    public function create() {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request) {
        try {
            $candidatos_fases = new RHI_Candidato_Fase();

            $candidatos_fases->rh_candidatos_id = $request->input('CandidatoId');
            $candidatos_fases->rh_fases_id = $request->input('FaseId');
            $candidatos_fases->user = auth()->user()->name;
            $candidatos_fases->save();

            $candidato = RH_Candidato::find($request->input('CandidatoId'));
            $candidato->estatus = 'EN_PROCESO';
            $candidato->save();

            $Auditoria = new Auditoria();
            $Auditoria->accion = 'CREAR';
            $Auditoria->tabla = 'RHI_CANDIDATOS_FASES';
            $Auditoria->registro = RH_Fase::find($request->input('FaseId'))->nombre_fase;
            $Auditoria->user = auth()->user()->name;
            $Auditoria->save();

            return redirect()
                ->route('candidatos.index')
                ->with('Saved0', ' Informacion');
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
