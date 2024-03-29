<?php

namespace compras\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

use compras\User;
use compras\Auditoria;
use compras\RH_Candidato;
use compras\RH_Entrevista;

class RH_CandidatoController extends Controller {
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
    public function index(Request $request) {
        $candidatos = RH_Candidato::busquedaPor($request->get('metodo'), $request->get('valor'))
            ->orderByDesc('id')
            ->paginate(50);

        return view('pages.RRHH.candidatos.index', compact('candidatos', 'request'));
    }

    public function procesos() {
        $candidatos = RH_Candidato::where('estatus', '=', 'EN_PROCESO')
        ->orWhere('estatus', '=', 'POSTULADO')
        ->get();
        return view('pages.RRHH.candidatos.procesos', compact('candidatos'));
    }

    public function expediente(Request $request) {
        $candidatos = RH_Candidato::find($request->input("CandidatoId"));
        return view('pages.RRHH.candidatos.expediente', compact('candidatos'));
    }

    public function motivo_rechazo(Request $request) {
        $candidatos = RH_Candidato::find($request->input("CandidatoId"));
        return view('pages.RRHH.candidatos.motivo_rechazo', compact('candidatos'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create() {
        if (session('data')) {
            $data = session('data');
        } else {
            $data = '';
        }

        return view('pages.RRHH.candidatos.create', compact('data'));
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
            return back()->with(['Error1' => ' Error', 'data' => $request->all()]);
        }

        if($correo != '') {
            if(RH_Candidato::where('correo', '=', $correo)->exists()) {
                return back()->with(['Error2' =>' Error', 'data' => $request->all()]);
            }
        }

        try {
            $candidatos = new RH_Candidato();
            $candidatos->nombres = $request->input('nombres');
            $candidatos->apellidos = $request->input('apellidos');
            $candidatos->cedula = $cedula;
            $candidatos->genero = $request->input('genero');
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

            $Auditoria = new Auditoria();
            $Auditoria->accion = 'CREAR';
            $Auditoria->tabla = 'RH_CANDIDATOS';
            $Auditoria->registro = $request->input('nombres') . " " . $request->input('apellidos');
            $Auditoria->user = auth()->user()->name;
            $Auditoria->save();

            return redirect()
                ->route('candidatos.index')
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

        $candidatos = RH_Candidato::find($id);

        $Auditoria = new Auditoria();
        $Auditoria->accion = 'CONSULTAR';
        $Auditoria->tabla = 'RH_CANDIDATOS';
        $Auditoria->registro = $candidatos->nombres . " " . $candidatos->apellidos;
        $Auditoria->user = auth()->user()->name;
        $Auditoria->save();

        return view('pages.RRHH.candidatos.show', compact('candidatos'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id) {
        if (session('data')) {
            $data = session('data');
        } else {
            $data = '';
        }

        $candidatos = RH_Candidato::find($id);

        return view('pages.RRHH.candidatos.edit', compact('candidatos', 'data'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id) {
        
        $cedula = $request->input('tipo') . '-' . $request->input('cedula');

        try {
            $candidatos = RH_Candidato::find($id);
            $candidatos->fill($request->all());
            
            $candidatos->cedula = $cedula;
            $candidatos->user = auth()->user()->name;
            $candidatos->save();

            $Auditoria = new Auditoria();
            $Auditoria->accion = 'EDITAR';
            $Auditoria->tabla = 'RH_CANDIDATOS';
            $Auditoria->registro = $candidatos->nombres . " " . $candidatos->apellidos;
            $Auditoria->user = auth()->user()->name;
            $Auditoria->save();

            return redirect()
                ->route('candidatos.index')
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
    public function destroy(Request $request, $id) {
        $candidatos = RH_Candidato::find($id);
        $candidatos->motivo_rechazo = $request->input('motivo_rechazo');
        $candidatos->save();

        $Auditoria = new Auditoria();
        $Auditoria->tabla = 'RH_CANDIDATOS';
        $Auditoria->registro = $candidatos->nombres . " " . $candidatos->apellidos;
        $Auditoria->user = auth()->user()->name;

        if(
            ($candidatos->estatus == 'RECHAZADO') 
            || ($candidatos->estatus == 'ELEGIBLE')
            || ($candidatos->estatus == 'DESERTOR')
        ) {
            $candidatos->estatus = 'POSTULADO';
            $Auditoria->accion = 'REINCORPORAR';
        }
        else {
            if($request->causa == 'Desertor') {
                $candidatos->estatus = 'DESERTOR';
            }
            else {
                $candidatos->estatus = 'RECHAZADO';
            }
            $Auditoria->accion = 'DESINCORPORAR';

            if(
                RH_Entrevista::where('rh_candidatos_id', $request->CandidatoId)
                ->count() > 0
            ) {
                
                RH_Entrevista::where('rh_candidatos_id', $request->CandidatoId)
                ->update(['estatus' => 'INACTIVO']);
            }
        }

        $candidatos->user = auth()->user()->name;
        $candidatos->save();

        $Auditoria->save();

        if($candidatos->estatus == 'POSTULADO') {
            return redirect()
                ->route('candidatos.index')
                ->with('Deleted1', ' Informacion');
        }

        return redirect()
            ->route('candidatos.index')
            ->with('Deleted', ' Informacion');
    }
}
