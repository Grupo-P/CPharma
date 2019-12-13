<?php

namespace compras\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

use compras\User;
use compras\Auditoria;
use compras\RH_Candidato;
use compras\RHI_Candidato_Fase;
use compras\RH_EmpresaReferencia;
use compras\RH_Candidato_EmpresaReferencia;

class RH_CandidatoEmpresaController extends Controller {
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
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request) {
        $candidato = RH_Candidato::find($request->input("CandidatoId"));
        $candidato_fase = RHI_Candidato_Fase::find($request->input("CandidatoFaseId"));
        $empresa_ref = RH_EmpresaReferencia::orderBy('nombre_empresa', 'asc')
        ->get();

        return view('pages.RRHH.candidatos_emp.create', compact('candidato', 'candidato_fase', 'empresa_ref'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request) {
        try {
            $candidatos_emp = new RH_Candidato_EmpresaReferencia();
            $candidatos_emp->rh_candidatos_id = $request->input('CandidatoId');
            $candidatos_emp->rh_empresaref_id = $request->input('EmpresaId');
            $candidatos_emp->user = auth()->user()->name;
            $candidatos_emp->save();

            //-------------------- FASE ASOCIADA --------------------//
            $fase_asociada = RHI_Candidato_Fase::find($request->input('CandidatoFaseId'));
            $fase_asociada->rh_fases_id = 5;
            $fase_asociada->save();

            //-------------------- AUDITORIA --------------------//
            $Auditoria = new Auditoria();
            $Auditoria->accion = 'CREAR';
            $Auditoria->tabla = 'RHI_CANDIDATOS_EMPRESAREF';
            $Auditoria->registro = RH_EmpresaReferencia::where('id', $request->input('EmpresaId'))
            ->value('nombre_empresa');
            $Auditoria->user = auth()->user()->name;
            $Auditoria->save();

            return redirect()
            ->action('RH_CandidatoController@procesos')
            ->with('Saved4', ' Informacion');
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
