<?php

namespace compras\Http\Controllers;

use compras\Auditoria;
use compras\ContPrepagado;
use compras\ContProveedor;
use Illuminate\Http\Request;

class ContPrepagadosController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $prepagados = ContPrepagado::get();
        return view('pages.contabilidad.prepagados.index', compact('prepagados'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $select = "
            CONCAT(nombre_proveedor, ' | ', rif_ci) AS label,
            CONCAT(nombre_proveedor, ' | ', rif_ci) AS value,
            id,
            moneda,
            moneda_iva,
            saldo,
            saldo_iva
        ";

        $proveedores = ContProveedor::selectRaw($select)
            ->whereNull('deleted_at')
            ->orderBy('nombre_proveedor')
            ->get();

        return view('pages.contabilidad.prepagados.create', compact('proveedores'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $prepagado = new ContPrepagado();
        $prepagado->id_proveedor    = $request->id_proveedor;
        $prepagado->monto           = $request->monto;
        $prepagado->monto_iva       = $request->monto_iva;
        $prepagado->status          = 'Pendiente';
        $prepagado->save();

        $auditoria           = new Auditoria();
        $auditoria->accion   = 'CREAR';
        $auditoria->tabla    = 'PAGO PREPAGADO';
        $auditoria->registro = $prepagado->id;
        $auditoria->user     = auth()->user()->name;
        $auditoria->save();

        return redirect('/prepagados')->with('Saved', 'Informacion');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(ContPrepagado $prepagado)
    {
        $select = "
            CONCAT(nombre_proveedor, ' | ', rif_ci) AS label,
            CONCAT(nombre_proveedor, ' | ', rif_ci) AS value,
            id,
            moneda,
            moneda_iva,
            saldo,
            saldo_iva
        ";

        $proveedores = ContProveedor::selectRaw($select)
            ->whereNull('deleted_at')
            ->orderBy('nombre_proveedor')
            ->get();

        return view('pages.contabilidad.prepagados.edit', compact('prepagado', 'proveedores'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, ContPrepagado $prepagado)
    {
        $prepagado->id_proveedor    = $request->id_proveedor;
        $prepagado->monto           = $request->monto;
        $prepagado->monto_iva       = $request->monto_iva;
        $prepagado->save();

        $auditoria           = new Auditoria();
        $auditoria->accion   = 'EDITAR';
        $auditoria->tabla    = 'PAGO PREPAGADO';
        $auditoria->registro = $prepagado->id;
        $auditoria->user     = auth()->user()->name;
        $auditoria->save();

        return redirect('/prepagados')->with('Updated', 'Informacion');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(ContPrepagado $prepagado)
    {
        $prepagado->status          = 'Cancelado';
        $prepagado->save();

        $auditoria           = new Auditoria();
        $auditoria->accion   = 'CANCELADO';
        $auditoria->tabla    = 'PAGO PREPAGADO';
        $auditoria->registro = $prepagado->id;
        $auditoria->user     = auth()->user()->name;
        $auditoria->save();

        return redirect('/prepagados')->with('Deleted', 'Informacion');
    }
}
