<?php

namespace compras\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

use compras\User;
use compras\Auditoria;
use compras\RH_ExamenesM;
use compras\RH_Candidato;
use compras\RH_Entrevista;
use compras\RH_Vacante;
use compras\RH_Laboratorio;
use compras\RHI_Candidato_Fase;
use compras\RHI_Examen_Laboratorio;

class RH_ExamenesMController extends Controller {
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
        //$examenesm = RH_ExamenesM::all();
        $examenesm = RH_ExamenesM::join(
            'rh_candidatos',
            'rh_examenes.rh_candidatos_id', '=', 'rh_candidatos.id'
        )
        ->join(
            'rhi_examenes_lab',
            'rh_examenes.id', '=', 'rhi_examenes_lab.rh_examenes_id'
        )
        ->select(
            'rh_examenes.*',
            'rh_candidatos.nombres',
            'rh_candidatos.apellidos',
            'rh_candidatos.cedula',
            'rhi_examenes_lab.representante',
            'rhi_examenes_lab.cargo'
        )
        ->get();
        return view('pages.RRHH.examenesMedicos.index', compact('examenesm'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request) {
        $candidato = RH_Candidato::find($request->input("CandidatoId"));
        $candidato_fase = RHI_Candidato_Fase::find($request->input("CandidatoFaseId"));
        $laboratorios = RH_Laboratorio::where('estatus', 'ACTIVO')->get();

        return view('pages.RRHH.examenesMedicos.create', compact('candidato', 'candidato_fase', 'laboratorios'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request) {
        try {
            //-------------------- LABORATORIO --------------------//
            $laboratorio = RH_Laboratorio::find($request->input('empresa'));

            //-------------------- EXAMENES --------------------//
            $examenesm = new RH_ExamenesM();
            $examenesm->rh_candidatos_id = $request->input('CandidatoId');
            $examenesm->empresa = $laboratorio->nombre;
            $examenesm->estado = $request->input('estado');
            $examenesm->observaciones = $request->input('observaciones');
            $examenesm->estatus = 'ACTIVO';
            $examenesm->user = auth()->user()->name;
            $examenesm->save();

            //-------------------- CANDIDATO --------------------//
            $candidato = RH_Candidato::find($request->input('CandidatoId'));
            $candidato->estatus = 'CONTRATADO';
            $candidato->save();

            //-------------------- FASE ASOCIADA --------------------//
            $fase_asociada = RHI_Candidato_Fase::find($request->input('CandidatoFaseId'));
            $fase_asociada->rh_fases_id = 7;
            $fase_asociada->save();

            //-------------------- EXAMENES LAB --------------------//
            $examenes_lab = new RHI_Examen_Laboratorio();
            $examenes_lab->rh_examenes_id = $examenesm->id;
            $examenes_lab->rh_laboratorio_id = $laboratorio->id;
            $examenes_lab->representante = $request->input('representante');
            $examenes_lab->cargo = $request->input('cargo');
            $examenes_lab->user = auth()->user()->name;
            $examenes_lab->save();

            //-------------------- ENTREVISTA --------------------//
            // $entrevista = DB::table('rh_entrevistas')
            // ->where('rh_candidatos_id', $candidato->id)
            // ->orderBy('id', 'desc')
            // ->first();

            //-------------------- VACANTE --------------------//
            // $vacante = RH_Vacante::find($entrevista->rh_vacantes_id);
            // $vacante->cantidad = $vacante->cantidad - 1;
            // $vacante->save();

            //-------------------- AUDITORIA --------------------//
            $Auditoria = new Auditoria();
            $Auditoria->accion = 'CREAR';
            $Auditoria->tabla = 'RH_EXAMENESM';
            $Auditoria->registro = $request->input('representante');
            $Auditoria->user = auth()->user()->name;
            $Auditoria->save();

            return redirect()
            ->action('RH_CandidatoController@procesos')
            ->with('Saved6', ' Informacion');
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
        $examenesm = RH_ExamenesM::find($id);

        $Auditoria = new Auditoria();
        $Auditoria->accion = 'CONSULTAR';
        $Auditoria->tabla = 'RH_EXAMENESM';
        $Auditoria->registro = $examenesm->empresa;
        $Auditoria->user = auth()->user()->name;
        $Auditoria->save();

        return view('pages.RRHH.examenesMedicos.show', compact('examenesm'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id) {
        $examenesm = RH_ExamenesM::find($id);

        return view('pages.RRHH.examenesMedicos.edit', compact('examenesm'));

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
            //-------------------- LABORATORIO --------------------//
            $laboratorio = RH_Laboratorio::find($request->input('empresa'));

            //-------------------- EXAMENES --------------------//
            $examenesm = RH_ExamenesM::find($id);
            $examenesm->fill($request->all());
            $examenesm->rh_candidatos_id = $request->input('CandidatoId');
            $examenesm->empresa = $laboratorio->nombre;
            $examenesm->user = auth()->user()->name;
            $examenesm->save();

            //-------------------- AUDITORIA --------------------//
            $Auditoria = new Auditoria();
            $Auditoria->accion = 'EDITAR';
            $Auditoria->tabla = 'RH_EXAMENESM';
            $Auditoria->registro = $examenesm->empresa;
            $Auditoria->user = auth()->user()->name;
            $Auditoria->save();

            return redirect()
                ->route('examenesm.index')
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
        $examenesm = RH_ExamenesM::find($id);

        $Auditoria = new Auditoria();
        $Auditoria->tabla = 'RH_EXAMENESM';
        $Auditoria->registro = $examenesm->empresa;
        $Auditoria->user = auth()->user()->name;

        if($examenesm->estatus == 'ACTIVO'){
            $examenesm->estatus = 'INACTIVO';
            $Auditoria->accion = 'DESINCORPORAR';
        }
        else if($examenesm->estatus == 'INACTIVO'){
            $examenesm->estatus = 'ACTIVO';
            $Auditoria->accion = 'REINCORPORAR';
        }

        $examenesm->user = auth()->user()->name;        
        $examenesm->save();

        $Auditoria->save();

        if($examenesm->estatus == 'ACTIVO'){
            return redirect()
                ->route('examenesm.index')
                ->with('Deleted', ' Informacion');
        }

        return redirect()
            ->route('examenesm.index')
            ->with('Deleted1', ' Informacion');
    }  
    
}
