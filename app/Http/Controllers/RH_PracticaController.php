<?php

namespace compras\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

use compras\User;
use compras\Auditoria;
use compras\RH_Candidato;
use compras\RH_Practica;
use compras\RHI_Candidato_Fase;

class RH_PracticaController extends Controller {
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
        $practicas = RH_Practica::all();
        return view('pages.RRHH.practicas.index', compact('practicas'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request) {
        $candidato = RH_Candidato::find($request->input("CandidatoId"));
        $candidato_fase = RHI_Candidato_Fase::find($request->input("CandidatoFaseId"));
        return view('pages.RRHH.practicas.create', compact('candidato', 'candidato_fase'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request) {
        try {
            $practicas = new RH_Practica();

            $practicas->rh_candidatos_id = $request->input('CandidatoId');
            $practicas->lider = $request->input('lider');
            $practicas->lugar = $request->input('lugar');
            $practicas->duracion = $request->input('duracion');
            $practicas->observaciones = $request->input('observaciones');
            $practicas->estatus = 'ACTIVO';
            $practicas->user = auth()->user()->name;
            $practicas->save();

            //-------------------- CANDIDATO --------------------//
            $candidato = RH_Candidato::find($request->input('CandidatoId'));
            $candidato->estatus = 'EN_PROCESO';
            $candidato->save();

            //-------------------- FASE ASOCIADA --------------------//
            $fase_asociada = RHI_Candidato_Fase::find($request->input('CandidatoFaseId'));
            $fase_asociada->rh_fases_id = 4;
            $fase_asociada->save();

            //-------------------- AUDITORIA --------------------//
            $Auditoria = new Auditoria();
            $Auditoria->accion = 'CREAR';
            $Auditoria->tabla = 'RH_PRACTICAS';
            $Auditoria->registro = $request->input('lider');
            $Auditoria->user = auth()->user()->name;
            $Auditoria->save();

            return redirect()
            ->action('RH_CandidatoController@procesos')
            ->with('Saved3', ' Informacion');
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
        $practicas = RH_Practica::find($id);
        $candidato = RH_Candidato::find($practicas->rh_candidatos_id);

        $Auditoria = new Auditoria();
        $Auditoria->accion = 'CONSULTAR';
        $Auditoria->tabla = 'RH_PRACTICAS';
        $Auditoria->registro = $practicas->lider;
        $Auditoria->user = auth()->user()->name;
        $Auditoria->save();

        return view('pages.RRHH.practicas.show', compact('practicas', 'candidato'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id) {
        $practicas = RH_Practica::find($id);
        $candidato = RH_Candidato::find($practicas->rh_candidatos_id);
        $candidato_fase = RHI_Candidato_Fase::where('rh_candidatos_id', $candidato->id)
        ->orderBy('id', 'desc')
        ->first();

        return view('pages.RRHH.practicas.edit', compact('practicas', 'candidato', 'candidato_fase'));
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
            $practicas = RH_Practica::find($id);
            $practicas->fill($request->all());

            $practicas->rh_candidatos_id = $request->input('CandidatoId');
            $practicas->user = auth()->user()->name;
            $practicas->save();

            //-------------------- CANDIDATO --------------------//
            $candidato = RH_Candidato::find($request->input('CandidatoId'));
            $candidato->estatus = 'EN_PROCESO';
            $candidato->save();

            //-------------------- FASE ASOCIADA --------------------//
            $fase_asociada = RHI_Candidato_Fase::find($request->input('CandidatoFaseId'));
            $fase_asociada->rh_fases_id = 4;
            $fase_asociada->save();

            //-------------------- AUDITORIA --------------------//
            $Auditoria = new Auditoria();
            $Auditoria->accion = 'EDITAR';
            $Auditoria->tabla = 'RH_PRACTICAS';
            $Auditoria->registro = $practicas->lider;
            $Auditoria->user = auth()->user()->name;
            $Auditoria->save();

            return redirect()
                ->route('practicas.index')
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
        //
    }
}
