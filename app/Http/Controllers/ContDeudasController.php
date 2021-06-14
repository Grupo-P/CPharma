<?php

namespace compras\Http\Controllers;

use compras\Auditoria;
use compras\Configuracion;
use compras\ContDeuda;
use compras\ContProveedor;
use Illuminate\Http\Request;

class ContDeudasController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $deudas = ContDeuda::with('proveedor')->get();

        return view('pages.contabilidad.deudas.index', compact('deudas'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $documentos = Configuracion::where('variable', 'Documento deuda')->first();
        $documentos = explode(',', $documentos->valor);

        $sqlProveedores = ContProveedor::whereNull('deleted_at')->get();
        $i              = 0;

        foreach ($sqlProveedores as $proveedor) {
            $proveedores[$i]['label']  = $proveedor->nombre_proveedor . ' | ' . $proveedor->rif_ci;
            $proveedores[$i]['value']  = $proveedor->nombre_proveedor . ' | ' . $proveedor->rif_ci;
            $proveedores[$i]['id']     = $proveedor->id;
            $proveedores[$i]['moneda'] = $proveedor->moneda;

            $i = $i + 1;
        }

        return view('pages.contabilidad.deudas.create', compact('documentos', 'proveedores'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $deuda                          = new ContDeuda();
        $deuda->id_proveedor            = $request->input('id_proveedor');
        $deuda->monto                   = $request->input('monto');
        $deuda->documento_soporte_deuda = $request->input('documento_soporte_deuda');
        $deuda->numero_documento        = $request->input('numero_documento');
        $deuda->save();

        $auditoria           = new Auditoria();
        $auditoria->accion   = 'CREAR';
        $auditoria->tabla    = 'DEUDA';
        $auditoria->registro = $deuda->id;
        $auditoria->user     = auth()->user()->name;
        $auditoria->save();

        return redirect('/deudas')->with('Saved', ' Informacion');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $deuda = ContDeuda::find($id);
        return view('pages.contabilidad.deudas.show', compact('deuda'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $documentos = Configuracion::where('variable', 'Documento deuda')->first();
        $documentos = explode(',', $documentos->valor);

        $sqlProveedores = ContProveedor::whereNull('deleted_at')->get();
        $i              = 0;

        foreach ($sqlProveedores as $proveedor) {
            $proveedores[$i]['label']  = $proveedor->nombre_proveedor . ' | ' . $proveedor->rif_ci;
            $proveedores[$i]['value']  = $proveedor->nombre_proveedor . ' | ' . $proveedor->rif_ci;
            $proveedores[$i]['id']     = $proveedor->id;
            $proveedores[$i]['moneda'] = $proveedor->moneda;

            $i = $i + 1;
        }

        $deuda = ContDeuda::find($id);

        return view('pages.contabilidad.deudas.edit', compact('deuda', 'documentos', 'proveedores'));
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
        $deuda                          = ContDeuda::find($id);
        $deuda->id_proveedor            = $request->input('id_proveedor');
        $deuda->monto                   = $request->input('monto');
        $deuda->documento_soporte_deuda = $request->input('documento_soporte_deuda');
        $deuda->numero_documento        = $request->input('numero_documento');
        $deuda->save();

        $auditoria           = new Auditoria();
        $auditoria->accion   = 'EDITAR';
        $auditoria->tabla    = 'DEUDA';
        $auditoria->registro = $deuda->id;
        $auditoria->user     = auth()->user()->name;
        $auditoria->save();

        return redirect('/deudas')->with('Updated', ' Informacion');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $deuda = ContDeuda::find($id);
        $deuda->delete();

        $auditoria           = new Auditoria();
        $auditoria->accion   = 'ELIMINAR';
        $auditoria->tabla    = 'DEUDA';
        $auditoria->registro = $deuda->id;
        $auditoria->user     = auth()->user()->name;
        $auditoria->save();

        return redirect('/deudas')->with('Deleted', ' Informacion');
    }
}
