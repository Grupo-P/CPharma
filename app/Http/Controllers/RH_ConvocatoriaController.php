<?php

namespace compras\Http\Controllers;

use Illuminate\Http\Request;
use compras\RH_Convocatoria;
use compras\User;
use compras\Auditoria;


class RH_ConvocatoriaController extends Controller {
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
        $convocatoria = RH_Convocatoria::all();
        return view('pages.RRHH.convocatoria.index', compact('convocatoria'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create() {
        return view('pages.RRHH.convocatoria.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request) {
         try {
            $convocatoria = new RH_Convocatoria();
            $convocatoria->fecha = $request->input('fecha');
            $convocatoria->lugar = $request->input('lugar');
            $convocatoria->cargo_reclutar = $request->input('cargo_reclutar');
            $convocatoria->estatus = 'ACTIVO';
            $convocatoria->user = auth()->user()->name;
            $convocatoria->save();

            $Auditoria = new Auditoria();
            $Auditoria->accion = 'CREAR';
            $Auditoria->tabla = 'RH_CONVOCATORIA';
            $Auditoria->registro = $request->input('cargo_reclutar');
            $Auditoria->user = auth()->user()->name;
            $Auditoria->save();

            return redirect()
                ->route('convocatoria.index')
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
         $convocatoria = RH_CONVOCATORIA::find($id);

        $Auditoria = new Auditoria();
        $Auditoria->accion = 'CONSULTAR';
        $Auditoria->tabla = 'RH_CONVOCATORIA';
        $Auditoria->registro = $convocatoria->lugar;
        $Auditoria->user = auth()->user()->name;
        $Auditoria->save();

        return view('pages.RRHH.convocatoria.show', compact('convocatoria'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id) {
         $convocatoria = RH_Convocatoria::find($id);

        return view('pages.RRHH.convocatoria.edit', compact('convocatoria'));
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
            $convocatoria = RH_Convocatoria::find($id);

            $convocatoria->fill($request->all());
            $convocatoria->user = auth()->user()->name;

            $convocatoria->save();

            $Auditoria = new Auditoria();
            $Auditoria->accion = 'EDITAR';
            $Auditoria->tabla = 'RH_CONVOCATORIA';
            $Auditoria->registro = $convocatoria->lugar;
            $Auditoria->user = auth()->user()->name;
            $Auditoria->save();

            return redirect()
                ->route('convocatoria.index')
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
        $convocatoria = RH_Convocatoria::find($id);

        $Auditoria = new Auditoria();
        $Auditoria->tabla = 'RH_CONVOCATORIA';
        $Auditoria->registro = $convocatoria->lugar;
        $Auditoria->user = auth()->user()->name;

        if($convocatoria->estatus == 'ACTIVO'){
            $convocatoria->estatus = 'INACTIVO';
            $Auditoria->accion = 'DESINCORPORAR';
        }
        else if($convocatoria->estatus == 'INACTIVO'){
            $convocatoria->estatus = 'ACTIVO';
            $Auditoria->accion = 'REINCORPORAR';
        }

        $convocatoria->user = auth()->user()->name;        
        $convocatoria->save();

        $Auditoria->save();

        if($convocatoria->estatus == 'ACTIVO'){
            return redirect()
                ->route('convocatoria.index')
                ->with('Deleted1', ' Informacion');
        }

        return redirect()
            ->route('convocatoria.index')
            ->with('Deleted', ' Informacion');
    }
}


