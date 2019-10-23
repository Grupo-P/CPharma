<?php

namespace compras\Http\Controllers;

use Illuminate\Http\Request;
use compras\RH_Candidato;
use compras\User;

class RH_CandidatoController extends Controller {
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
        $candidatos = RH_Candidato::all();
        return view('pages.RRHH.candidatos.index', compact('candidatos'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create() {
        return view('pages.RRHH.candidatos.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request) {
        //Concatenacion de la cedula
        $cedula = $request->input('tipo') . '-' . $request->input('cedula');
        $correo = $request->input('correo');

        if(RH_Candidato::where('cedula', '=', $cedula)->exists()) {
            return back()->with('Error1', ' Error');
        }
        else if(RH_Candidato::where('correo', '=', $correo)->exists()) {
            return back()->with('Error2', ' Error');
        }
        else {
            try {
                $candidatos = new RH_Candidato();
                $candidatos->nombres = $request->input('nombres');
                $candidatos->apellidos = $request->input('apellidos');
                $candidatos->cedula = $cedula;
                $candidatos->direccion = $request->input('direccion');
                $candidatos->telefono_celular = $request->input('telefono_celular');
                $candidatos->telefono_habitacion = $request->input('telefono_habitacion');
                $candidatos->correo = $correo;
                $candidatos->como_nos_contacto = $request->input('como_nos_contacto');
                $candidatos->experiencia_laboral = $request->input('experiencia_laboral');
                $candidatos->observaciones = $request->input('observaciones');
                $candidatos->tipo_relacion = $request->input('tipo_relacion');
                $candidatos->relaciones_laborales = $request->input('relaciones_laborales');
                $candidatos->estatus = 'POSTULADO';
                $candidatos->user = auth()->user()->name;
                $candidatos->save();

                return redirect()
                    ->route('candidatos.index')
                    ->with('Saved', ' Informacion');
            }
            catch(\Illuminate\Database\QueryException $e) {
                return back()->with('Error', ' Error');
            }
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
