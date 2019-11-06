<?php

namespace compras\Http\Controllers;

use Illuminate\Http\Request;
use compras\RH_ExamenesM;
use compras\User;
use compras\Auditoria;

class RH_ExamenesMController extends Controller
{
    /**
     * Create a new controller instance with auth.
     *
     * 
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
        $examenesm = RH_ExamenesM::all();
        return view('pages.RRHH.examenes_medicos.index', compact('examenesm'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create() {
        return view('pages.RRHH.examenes_medicos.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request) {
         try {
            $examenesm = new RH_ExamenesM();
            $examenesm->empresa = $request->input('empresa');
            $examenesm->representante = $request->input('representante');
            $examenesm->estado = $request->input('estado');
            $examenesm->observaciones = $request->input('observaciones');
            $examenesm->estatus = 'ACTIVO';
            $examenesm->user = auth()->user()->name;
            $examenesm->save();

            $Auditoria = new Auditoria();
            $Auditoria->accion = 'CREAR';
            $Auditoria->tabla = 'RH_ExamenesM';
            $Auditoria->registro = $request->input('empresa');
            $Auditoria->user = auth()->user()->name;
            $Auditoria->save();

            return redirect()
                ->route('examenesm.index')
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
         $examenesm = RH_ExamenesM::find($id);

        $Auditoria = new Auditoria();
        $Auditoria->accion = 'CONSULTAR';
        $Auditoria->tabla = 'RH_ExamenesM';
        $Auditoria->registro = $examenesm->nombres . " " . $examenesm->apellidos;
        $Auditoria->user = auth()->user()->name;
        $Auditoria->save();

        return view('pages.RRHH.examenes_medicos.show', compact('examenesm'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id) {
        $examenesm = RH_ExamenesM::find($id);

        return view('pages.RRHH.examenes_medicos.edit', compact('examenesm'));

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
            $examenesm = RH_ExamenesM::find($id);

            $examenesm->fill($request->all());
            $examenesm->user = auth()->user()->name;

            $examenesm->save();

            $Auditoria = new Auditoria();
            $Auditoria->accion = 'EDITAR';
            $Auditoria->tabla = 'RH_PRUEBAS';
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
        $Auditoria->tabla = 'RH_ExamenesM';
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
