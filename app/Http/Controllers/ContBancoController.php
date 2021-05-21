<?php

namespace compras\Http\Controllers;

use compras\Auditoria;
use compras\ContBanco;
use Illuminate\Http\Request;

class ContBancoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $bancos = ContBanco::get();
        return view('pages.contabilidad.bancos.index', compact('bancos'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('pages.contabilidad.bancos.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $banco                 = new ContBanco();
        $banco->nombre_banco   = $request->input('nombre_banco');
        $banco->nombre_titular = $request->input('nombre_titular');
        $banco->alias_cuenta   = $request->input('alias_cuenta');
        $banco->save();

        $auditoria           = new Auditoria();
        $auditoria->accion   = 'CREAR';
        $auditoria->tabla    = 'BANCO';
        $auditoria->registro = $banco->id;
        $auditoria->user     = auth()->user()->name;
        $auditoria->save();

        return redirect('/bancos')->with('Saved', ' Informacion');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $banco = ContBanco::find($id);
        return view('pages.contabilidad.bancos.show', compact('banco'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $banco = ContBanco::find($id);
        return view('pages.contabilidad.bancos.edit', compact('banco'));
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
        $banco                 = ContBanco::find($id);
        $banco->nombre_banco   = $request->input('nombre_banco');
        $banco->nombre_titular = $request->input('nombre_titular');
        $banco->alias_cuenta   = $request->input('alias_cuenta');
        $banco->save();

        $auditoria           = new Auditoria();
        $auditoria->accion   = 'EDITAR';
        $auditoria->tabla    = 'BANCO';
        $auditoria->registro = $banco->id;
        $auditoria->user     = auth()->user()->name;
        $auditoria->save();

        return redirect('/bancos')->with('Updated', ' Informacion');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $banco = ContBanco::find($id);

        $auditoria           = new Auditoria();
        $auditoria->accion   = 'ELIMINAR';
        $auditoria->tabla    = 'BANCO';
        $auditoria->registro = $banco->id;
        $auditoria->user     = auth()->user()->name;
        $auditoria->save();

        $banco->delete();

        return redirect('/bancos')->with('Deleted', ' Informacion');
    }
}
