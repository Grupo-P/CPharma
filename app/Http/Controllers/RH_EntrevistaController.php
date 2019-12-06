<?php

namespace compras\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

use compras\User;
use compras\Auditoria;
use compras\RH_Candidato;
use compras\RH_Vacante;
use compras\RH_Entrevista;

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
        $entrevistas = RH_Entrevista::all();
        return view('pages.RRHH.entrevistas.index', compact('entrevistas'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request) {

        $candidato = RH_Candidato::find($request->input("CandidatoId"));
        $vacantes = DB::table('rh_vacantes')
            ->orderBy('sede', 'asc')
            ->get();

        return view('pages.RRHH.entrevistas.create', compact('candidato', 'vacantes'));
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

            $candidato = RH_Candidato::find($request->input('CandidatoId'));
            $candidato->estatus = 'EN_PROCESO';
            $candidato->save();

            $Auditoria = new Auditoria();
            $Auditoria->accion = 'CREAR';
            $Auditoria->tabla = 'RH_ENTREVISTAS';
            $Auditoria->registro = $request->input('entrevistadores');
            $Auditoria->user = auth()->user()->name;
            $Auditoria->save();

            return redirect()
                ->route('candidatos.index')
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

            $candidato = RH_Candidato::find($request->input('CandidatoId'));
            $candidato->estatus = 'EN_PROCESO';
            $candidato->save();

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
