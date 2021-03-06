<?php

namespace compras\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

use compras\User;
use compras\Auditoria;
use compras\RH_Candidato;
use compras\RH_Vacante;
use compras\RH_Entrevista;
use compras\RHI_Candidato_Fase;

class RH_EntrevistaController extends Controller {
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
        //$entrevistas = RH_Entrevista::all();
        $entrevistas = RH_Entrevista::join(
            'rh_candidatos',
            'rh_entrevistas.rh_candidatos_id', '=', 'rh_candidatos.id'
        )
        ->join(
            'rh_vacantes',
            'rh_entrevistas.rh_vacantes_id', '=', 'rh_vacantes.id'
        )
        ->select(
            'rh_entrevistas.*',
            'rh_candidatos.nombres',
            'rh_candidatos.apellidos',
            'rh_candidatos.cedula',
            'rh_vacantes.nombre_vacante',
            'rh_vacantes.departamento',
            'rh_vacantes.sede'
        )
        ->orderBy('rh_entrevistas.id', 'asc')
        ->get();
        return view('pages.RRHH.entrevistas.index', compact('entrevistas'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request) {

        $candidato = RH_Candidato::find($request->input("CandidatoId"));
        $candidato_fase = RHI_Candidato_Fase::find($request->input("CandidatoFaseId"));
        $vacantes = DB::table('rh_vacantes')
            ->orderByRaw('sede ASC, nombre_vacante ASC')
            ->get();

        return view('pages.RRHH.entrevistas.create', compact('candidato', 'candidato_fase', 'vacantes'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request) {
        try {
            $fecha_entrevista = $request->input('fecha_entrevista');

            if(
                RH_Entrevista::where('rh_candidatos_id', $request->CandidatoId)
                ->count() > 0
            ) {
                
                RH_Entrevista::where('rh_candidatos_id', $request->CandidatoId)
                ->update(['estatus' => 'INACTIVO']);
            }

            $entrevistas = new RH_Entrevista();

            $entrevistas->rh_candidatos_id = $request->input('CandidatoId');
            $entrevistas->rh_vacantes_id = $request->input('VacanteId');
            $entrevistas->fecha_entrevista = date('Y-m-d', strtotime($fecha_entrevista));
            $entrevistas->entrevistadores = $request->input('entrevistadores');
            $entrevistas->lugar = $request->input('lugar');
            $entrevistas->observaciones = $request->input('observaciones');
            $entrevistas->estatus = 'ACTIVO';
            $entrevistas->user = auth()->user()->name;
            $entrevistas->save();

            //-------------------- FASE ASOCIADA --------------------//
            $fase_asociada = RHI_Candidato_Fase::find($request->input('CandidatoFaseId'));

            switch($request->input('practica')) {
                case 'Si': $fase_asociada->rh_fases_id = 3; break;
                case 'No': $fase_asociada->rh_fases_id = 4; break;
            }
            
            $fase_asociada->save();

            //-------------------- CANDIDATO ELEGIBLE --------------------//
            if($request->input('elegible') == 'Si') {
                $candidato = RH_Candidato::find($request->input('CandidatoId'));
                $candidato->estatus = 'ELEGIBLE';
                $candidato->save();
            }
            // else {
            //     $candidato = RH_Candidato::find($request->input('CandidatoId'));
            //     $candidato->estatus = 'EN_PROCESO';
            //     $candidato->save();
            // }

            //-------------------- AUDITORIA --------------------//
            $Auditoria = new Auditoria();
            $Auditoria->accion = 'CREAR';
            $Auditoria->tabla = 'RH_ENTREVISTAS';
            $Auditoria->registro = $request->input('entrevistadores');
            $Auditoria->user = auth()->user()->name;
            $Auditoria->save();

            return redirect()
            ->action('RH_CandidatoController@procesos')
            ->with('Saved2', ' Informacion');
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

        $entrevistas = RH_Entrevista::find($id);
        $candidato = RH_Candidato::find($entrevistas->rh_candidatos_id);
        $vacante = RH_Vacante::find($entrevistas->rh_vacantes_id);

        $Auditoria = new Auditoria();
        $Auditoria->accion = 'CONSULTAR';
        $Auditoria->tabla = 'RH_ENTREVISTAS';
        $Auditoria->registro = $entrevistas->entrevistadores;
        $Auditoria->user = auth()->user()->name;
        $Auditoria->save();

        return view('pages.RRHH.entrevistas.show', compact('entrevistas', 'candidato', 'vacante'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id) {
        $entrevistas = RH_Entrevista::find($id);
        return view('pages.RRHH.entrevistas.edit', compact('entrevistas'));
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
            $fecha_entrevista = $request->input('fecha_entrevista');
            
            $entrevistas = RH_Entrevista::find($id);
            $entrevistas->fill($request->all());

            $entrevistas->rh_vacantes_id = $request->input('VacanteId');
            $entrevistas->fecha_entrevista = date('Y-m-d', strtotime($fecha_entrevista));

            $entrevistas->user = auth()->user()->name;
            $entrevistas->save();

            //-------------------- FASE ASOCIADA --------------------//
            $candidatos_fases = DB::table('rhi_candidatos_fases')
            ->where('rh_candidatos_id', $request->input('CandidatoId'))
            ->orderBy('id', 'desc')
            ->first();

            $fase_asociada = RHI_Candidato_Fase::find($candidatos_fases->id);

            switch($request->input('practica')) {
                case 'Si': $fase_asociada->rh_fases_id = 3; break;
                case 'No': $fase_asociada->rh_fases_id = 4; break;
            }
            
            $fase_asociada->save();

            //-------------------- CANDIDATO FUTURO --------------------//
            if($request->input('futuro') == 'Si') {
                $candidato = RH_Candidato::find($request->input('CandidatoId'));
                $candidato->estatus = 'FUTURO';
                $candidato->save();
            }
            else {
                $candidato = RH_Candidato::find($request->input('CandidatoId'));
                $candidato->estatus = 'EN_PROCESO';
                $candidato->save();
            }

            //-------------------- AUDITORIA --------------------//
            $Auditoria = new Auditoria();
            $Auditoria->accion = 'EDITAR';
            $Auditoria->tabla = 'RH_ENTREVISTAS';
            $Auditoria->registro = $entrevistas->entrevistadores;
            $Auditoria->user = auth()->user()->name;
            $Auditoria->save();

            return redirect()
                ->route('entrevistas.index')
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
        $entrevistas = RH_Entrevista::find($id);

        $Auditoria = new Auditoria();
        $Auditoria->tabla = 'RH_ENTREVISTAS';
        $Auditoria->registro = $entrevistas->entrevistadores;
        $Auditoria->user = auth()->user()->name;

        if($entrevistas->estatus == 'ACTIVO'){
            $entrevistas->estatus = 'INACTIVO';
            $Auditoria->accion = 'DESINCORPORAR';
        }
        else if($entrevistas->estatus == 'INACTIVO'){
            $entrevistas->estatus = 'ACTIVO';
            $Auditoria->accion = 'REINCORPORAR';
        }

        $entrevistas->user = auth()->user()->name;        
        $entrevistas->save();

        $Auditoria->save();

        if($entrevistas->estatus == 'ACTIVO'){
            return redirect()
                ->route('entrevistas.index')
                ->with('Deleted', ' Informacion');
        }

        return redirect()
            ->route('entrevistas.index')
            ->with('Deleted1', ' Informacion');
    }
}
