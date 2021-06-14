<?php

namespace compras\Http\Controllers;

use compras\Auditoria;
use compras\Configuracion;
use compras\ContProveedor;
use compras\ContReclamo;
use Illuminate\Http\Request;

class ContReclamoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $reclamos = ContReclamo::get();
        return view('pages.contabilidad.reclamos.index', compact('reclamos'));
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

        return view('pages.contabilidad.reclamos.create', compact('documentos', 'proveedores'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $reclamo                            = new ContReclamo();
        $reclamo->id_proveedor              = $request->input('id_proveedor');
        $reclamo->monto                     = $request->input('monto');
        $reclamo->documento_soporte_reclamo = $request->input('documento_soporte_reclamo');
        $reclamo->numero_documento          = $request->input('numero_documento');
        $reclamo->save();

        $auditoria           = new Auditoria();
        $auditoria->accion   = 'CREAR';
        $auditoria->tabla    = 'RECLAMO';
        $auditoria->registro = $reclamo->id;
        $auditoria->user     = auth()->user()->name;
        $auditoria->save();

        return redirect('/reclamos')->with('Saved', ' Informacion');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $reclamo = ContReclamo::find($id);
        return view('pages.contabilidad.reclamos.show', compact('reclamo'));
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

        $reclamo = ContReclamo::find($id);

        return view('pages.contabilidad.reclamos.edit', compact('reclamo', 'documentos', 'proveedores'));
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
        $reclamo                            = ContReclamo::find($id);
        $reclamo->id_proveedor              = $request->input('id_proveedor');
        $reclamo->monto                     = $request->input('monto');
        $reclamo->documento_soporte_reclamo = $request->input('documento_soporte_reclamo');
        $reclamo->numero_documento          = $request->input('numero_documento');
        $reclamo->save();

        $auditoria           = new Auditoria();
        $auditoria->accion   = 'EDITAR';
        $auditoria->tabla    = 'RECLAMO';
        $auditoria->registro = $reclamo->id;
        $auditoria->user     = auth()->user()->name;
        $auditoria->save();

        return redirect('/reclamos')->with('Updated', ' Informacion');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $reclamo = ContReclamo::find($id);
        $reclamo->delete();

        $auditoria           = new Auditoria();
        $auditoria->accion   = 'ELIMINAR';
        $auditoria->tabla    = 'RECLAMO';
        $auditoria->registro = $reclamo->id;
        $auditoria->user     = auth()->user()->name;
        $auditoria->save();

        return redirect('/reclamos')->with('Deleted', ' Informacion');
    }
}
