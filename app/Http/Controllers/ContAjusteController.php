<?php

namespace compras\Http\Controllers;

use compras\Auditoria;
use compras\ContAjuste;
use compras\ContProveedor;
use Illuminate\Http\Request;

class ContAjusteController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $ajustes = ContAjuste::orderByDesc('id')->get();
        return view('pages.contabilidad.ajuste.index', compact('ajustes'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $sqlProveedores = ContProveedor::whereNull('deleted_at')->orderBy('nombre_proveedor', 'ASC')->get();
        $i              = 0;

        foreach ($sqlProveedores as $proveedor) {
            $proveedores[$i]['label']      = $proveedor->nombre_proveedor . ' | ' . $proveedor->rif_ci;
            $proveedores[$i]['value']      = $proveedor->nombre_proveedor . ' | ' . $proveedor->rif_ci;
            $proveedores[$i]['id']         = $proveedor->id;
            $proveedores[$i]['moneda']     = $proveedor->moneda;
            $proveedores[$i]['moneda_iva'] = $proveedor->moneda_iva;
            $proveedores[$i]['saldo']      = number_format($proveedor->saldo, 2, ',', '.');
            $proveedores[$i]['saldo_iva']  = number_format($proveedor->saldo_iva, 2, ',', '.');

            $i = $i + 1;
        }

        return view('pages.contabilidad.ajuste.create', compact('proveedores'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $ajuste                   = new ContAjuste();
        $ajuste->id_proveedor     = $request->input('id_proveedor');
        $ajuste->monto            = $request->input('monto');
        $ajuste->monto_iva        = $request->input('monto_iva');
        $ajuste->comentario       = $request->input('comentario');
        $ajuste->usuario_registro = auth()->user()->name;
        $ajuste->save();

        $proveedor            = ContProveedor::find($request->input('id_proveedor'));
        $proveedor->saldo     = (float) $proveedor->saldo + (float) $request->input('monto');
        $proveedor->saldo_iva = (float) $proveedor->saldo_iva + (float) $request->input('monto_iva');
        $proveedor->save();

        $auditoria           = new Auditoria();
        $auditoria->accion   = 'CREAR';
        $auditoria->tabla    = 'AJUSTE';
        $auditoria->registro = $ajuste->id;
        $auditoria->user     = auth()->user()->name;
        $auditoria->save();

        return redirect('/ajuste')->with('Saved', ' Informacion');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $ajuste = ContAjuste::find($id);
        return view('pages.contabilidad.ajuste.show', compact('ajuste'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $ajuste = ContAjuste::find($id);

        $sqlProveedores = ContProveedor::whereNull('deleted_at')->orderBy('nombre_proveedor', 'ASC')->get();
        $i              = 0;

        foreach ($sqlProveedores as $proveedor) {
            $proveedores[$i]['label']  = $proveedor->nombre_proveedor . ' | ' . $proveedor->rif_ci;
            $proveedores[$i]['value']  = $proveedor->nombre_proveedor . ' | ' . $proveedor->rif_ci;
            $proveedores[$i]['id']     = $proveedor->id;
            $proveedores[$i]['moneda'] = $proveedor->moneda;
            $proveedores[$i]['saldo']  = $proveedor->saldo;

            $i = $i + 1;
        }

        return view('pages.contabilidad.ajuste.edit', compact('ajuste', 'proveedores'));
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
        $ajuste = ContAjuste::find($id);

        $proveedor        = ContProveedor::find($ajuste->id_proveedor);
        $proveedor->saldo = (float) $proveedor->saldo - (float) $ajuste->monto;
        $proveedor->save();

        $proveedor        = ContProveedor::find($request->input('id_proveedor'));
        $proveedor->saldo = (float) $proveedor->saldo + (float) $request->input('monto');
        $proveedor->save();

        $ajuste->id_proveedor = $request->input('id_proveedor');
        $ajuste->monto        = $request->input('monto');
        $ajuste->comentario   = $request->input('comentario');
        $ajuste->save();

        $auditoria           = new Auditoria();
        $auditoria->accion   = 'EDITAR';
        $auditoria->tabla    = 'AJUSTE';
        $auditoria->registro = $ajuste->id;
        $auditoria->user     = auth()->user()->name;
        $auditoria->save();

        return redirect('/ajuste')->with('Updated', ' Informacion');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $ajuste          = ContAjuste::find($id);
        $ajuste->reverso = 1;
        $ajuste->save();

        $monto     = ($ajuste->monto > 0) ? -$ajuste->monto : abs($ajuste->monto);
        $monto_iva = ($ajuste->monto_iva > 0) ? -$ajuste->monto_iva : abs($ajuste->monto_iva);

        $nuevoAjuste                   = new ContAjuste();
        $nuevoAjuste->id_proveedor     = $ajuste->id_proveedor;
        $nuevoAjuste->monto            = $monto;
        $nuevoAjuste->monto_iva        = $monto_iva;
        $nuevoAjuste->comentario       = 'Reverso del ajuste #' . $ajuste->id;
        $nuevoAjuste->usuario_registro = auth()->user()->name;
        $nuevoAjuste->reverso          = 1;
        $nuevoAjuste->save();

        $proveedor            = ContProveedor::find($ajuste->id_proveedor);
        $proveedor->saldo     = (float) $proveedor->saldo + (float) $monto;
        $proveedor->saldo_iva = (float) $proveedor->saldo_iva + (float) $monto_iva;
        $proveedor->save();

        return redirect('/ajuste')->with('Deleted', ' Informacion');
    }
}
