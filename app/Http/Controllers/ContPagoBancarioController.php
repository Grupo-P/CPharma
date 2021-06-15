<?php

namespace compras\Http\Controllers;

use compras\Auditoria;
use compras\ContBanco;
use compras\ContPagoBancario;
use compras\ContProveedor;
use Illuminate\Http\Request;

class ContPagoBancarioController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $pagos = ContPagoBancario::orderByDesc('id')->get();
        return view('pages.contabilidad.bancarios.index', compact('pagos'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $sqlProveedores = ContProveedor::whereNull('deleted_at')->get();
        $i              = 0;
        $proveedores    = [];

        foreach ($sqlProveedores as $proveedor) {
            $proveedores[$i]['label']  = $proveedor->nombre_proveedor . ' | ' . $proveedor->rif_ci;
            $proveedores[$i]['value']  = $proveedor->nombre_proveedor . ' | ' . $proveedor->rif_ci;
            $proveedores[$i]['id']     = $proveedor->id;
            $proveedores[$i]['moneda'] = $proveedor->moneda;

            $i = $i + 1;
        }

        $bancos = ContBanco::whereNull('deleted_at')->orderBy('alias_cuenta')->get();

        return view('pages.contabilidad.bancarios.create', compact('bancos', 'proveedores'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $pago               = new ContPagoBancario();
        $pago->id_proveedor = $request->input('id_proveedor');
        $pago->id_banco     = $request->input('id_banco');
        $pago->monto        = $request->input('monto');
        $pago->comentario   = $request->input('comentario');
        $pago->operador     = auth()->user()->name;
        $pago->estatus      = 'Procesado';
        $pago->save();

        $auditoria           = new Auditoria();
        $auditoria->accion   = 'CREAR';
        $auditoria->tabla    = 'PAGO BANCARIO';
        $auditoria->registro = $pago->id;
        $auditoria->user     = auth()->user()->name;
        $auditoria->save();

        return redirect('/bancarios')->with('Saved', ' Informacion');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $pago = ContPagoBancario::find($id);
        return view('pages.contabilidad.bancarios.show', compact('pago'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $bancos = ContBanco::whereNull('deleted_at')->orderBy('alias_cuenta')->get();

        $pago = ContPagoBancario::find($id);

        $sqlProveedores = ContProveedor::whereNull('deleted_at')->get();
        $i              = 0;
        $proveedores    = [];

        foreach ($sqlProveedores as $proveedor) {
            $proveedores[$i]['label']  = $proveedor->nombre_proveedor . ' | ' . $proveedor->rif_ci;
            $proveedores[$i]['value']  = $proveedor->nombre_proveedor . ' | ' . $proveedor->rif_ci;
            $proveedores[$i]['id']     = $proveedor->id;
            $proveedores[$i]['moneda'] = $proveedor->moneda;

            $i = $i + 1;
        }

        return view('pages.contabilidad.bancarios.edit', compact('bancos', 'pago', 'proveedores'));
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
        $pago               = ContPagoBancario::find($id);
        $pago->id_proveedor = $request->input('id_proveedor');
        $pago->id_banco     = $request->input('id_banco');
        $pago->monto        = $request->input('monto');
        $pago->comentario   = $request->input('comentario');
        $pago->save();

        $auditoria           = new Auditoria();
        $auditoria->accion   = 'EDITAR';
        $auditoria->tabla    = 'PAGO BANCARIO';
        $auditoria->registro = $pago->id;
        $auditoria->user     = auth()->user()->name;
        $auditoria->save();

        return redirect('/bancarios')->with('Updated', ' Informacion');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $pago          = ContPagoBancario::find($id);
        $pago->estatus = 'Reversado';
        $pago->save();

        $monto = ($pago->monto > 0) ? -$pago->monto : abs($pago->monto);

        $nuevoPago               = new ContPagoBancario();
        $nuevoPago->id_proveedor = $pago->id_proveedor;
        $nuevoPago->id_banco     = $pago->id_banco;
        $nuevoPago->monto        = $monto;
        $nuevoPago->comentario   = 'Reverso del pago bancario #' . $pago->id;
        $nuevoPago->operador     = $pago->operador;
        $nuevoPago->estatus      = 'Reversado';
        $nuevoPago->save();

        $proveedor        = ContProveedor::find($pago->id_proveedor);
        $proveedor->saldo = (float) $proveedor->saldo + (float) $monto;
        $proveedor->save();

        return redirect('/bancarios')->with('Deleted', ' Informacion');
    }

    public function soporte($id)
    {
        $pago = ContPagoBancario::find($id);
        return view('pages.contabilidad.bancarios.soporte', compact('pago'));
    }
}
