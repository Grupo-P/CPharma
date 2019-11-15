<?php

namespace compras\Http\Controllers;

use Illuminate\Http\Request;
use compras\RH_ContactoEmp;
use compras\User;
use compras\Auditoria;

class RH_ContactoEmpresaController extends Controller {
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
        $contactos = RH_ContactoEmp::all();
        return view('pages.RRHH.contactos.index', compact('contactos'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create() {
        return view('pages.RRHH.contactos.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request) {
        try {
            $contactos = new RH_ContactoEmp();
            $contactos->nombre = $request->input('nombres');
            $contactos->apellido = $request->input('apellidos');
            $contactos->telefono = $request->input('telefono');
            $contactos->correo = $request->input('correo');
            $contactos->cargo = $request->input('cargo');
            $contactos->estatus = 'ACTIVO';
            $contactos->user = auth()->user()->name;
            $contactos->save();

            $Auditoria = new Auditoria();
            $Auditoria->accion = 'CREAR';
            $Auditoria->tabla = 'RH_CONTACTOS_EMPRESAS';
            $Auditoria->registro = $request->input('nombres') . " " . $request->input('apellidos');
            $Auditoria->user = auth()->user()->name;
            $Auditoria->save();

            return redirect()
                ->route('contactos.index')
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
        $contactos = RH_ContactoEmp::find($id);

        $Auditoria = new Auditoria();
        $Auditoria->accion = 'CONSULTAR';
        $Auditoria->tabla = 'RH_CONTACTOS_EMPRESAS';
        $Auditoria->registro = $contactos->nombre . " " . $contactos->apellido;
        $Auditoria->user = auth()->user()->name;
        $Auditoria->save();

        return view('pages.RRHH.contactos.show', compact('contactos'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id) {
        $contactos = RH_ContactoEmp::find($id);

        return view('pages.RRHH.contactos.edit', compact('contactos'));
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
            $contactos = RH_ContactoEmp::find($id);
            $contactos->fill($request->all());
            $contactos->user = auth()->user()->name;
            $contactos->save();

            $Auditoria = new Auditoria();
            $Auditoria->accion = 'EDITAR';
            $Auditoria->tabla = 'RH_CONTACTOS_EMPRESAS';
            $Auditoria->registro = $contactos->nombre . " " . $contactos->apellido;
            $Auditoria->user = auth()->user()->name;
            $Auditoria->save();

            return redirect()
                ->route('contactos.index')
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
        $contactos = RH_ContactoEmp::find($id);

        $Auditoria = new Auditoria();
        $Auditoria->tabla = 'RH_CONTACTOS_EMPRESAS';
        $Auditoria->registro = $contactos->nombre . " " . $contactos->apellido;
        $Auditoria->user = auth()->user()->name;

        if($contactos->estatus == 'ACTIVO') {
            $contactos->estatus = 'INACTIVO';
            $Auditoria->accion = 'DESINCORPORAR';
        }
        else if($contactos->estatus == 'INACTIVO') {
            $contactos->estatus = 'ACTIVO';
            $Auditoria->accion = 'REINCORPORAR';
        }

        $contactos->user = auth()->user()->name;
        $contactos->save();

        $Auditoria->save();

        if($contactos->estatus == 'ACTIVO') {
            return redirect()
                ->route('contactos.index')
                ->with('Deleted1', ' Informacion');
        }

        return redirect()
            ->route('contactos.index')
            ->with('Deleted', ' Informacion');
    }
}
