<?php

namespace compras\Http\Controllers;

use compras\Auditoria;
use compras\ContCuenta;
use Illuminate\Http\Request;

class ContCuentasController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $cuentas = ContCuenta::where('pertenece_a', 'Principal')->get();
        return view('pages.contabilidad.cuentas.index', compact('cuentas'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $cuentas = ContCuenta::get();
        return view('pages.contabilidad.cuentas.create', compact('cuentas'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $cuenta              = new ContCuenta();
        $cuenta->nombre      = $request->input('nombre');
        $cuenta->pertenece_a = $request->input('pertenece_a');
        $cuenta->save();

        $auditoria           = new Auditoria();
        $auditoria->accion   = 'CREAR';
        $auditoria->tabla    = 'PLAN DE CUENTAS';
        $auditoria->registro = $cuenta->id;
        $auditoria->user     = auth()->user()->name;
        $auditoria->save();

        return redirect('/cuentas')->with('Saved', ' Informacion');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $cuenta = ContCuenta::find($id);

        if ($cuenta->pertenece_a != 'Principal') {
            $pertenece_a = ContCuenta::find($cuenta->pertenece_a)->nombre;
        } else {
            $pertenece_a = 'Principal';
        }

        return view('pages.contabilidad.cuentas.show', compact('cuenta', 'pertenece_a'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $cuenta  = ContCuenta::find($id);
        $cuentas = ContCuenta::get();

        return view('pages.contabilidad.cuentas.edit', compact('cuentas', 'cuenta'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $cuenta              = ContCuenta::find($id);
        $cuenta->nombre      = $request->input('nombre');
        $cuenta->pertenece_a = $request->input('pertenece_a');
        $cuenta->save();

        $auditoria           = new Auditoria();
        $auditoria->accion   = 'EDITAR';
        $auditoria->tabla    = 'PLAN DE CUENTAS';
        $auditoria->registro = $cuenta->id;
        $auditoria->user     = auth()->user()->name;
        $auditoria->save();

        return redirect('/cuentas')->with('Updated', ' Informacion');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function delete($id)
    {
        $cuenta = ContCuenta::find($id);

        $auditoria           = new Auditoria();
        $auditoria->accion   = 'CREAR';
        $auditoria->tabla    = 'PLAN DE CUENTAS';
        $auditoria->registro = $cuenta->id;
        $auditoria->user     = auth()->user()->name;
        $auditoria->save();

        $cuenta->delete();

        return redirect('/cuentas')->with('Deleted', ' Informacion');
    }
}
