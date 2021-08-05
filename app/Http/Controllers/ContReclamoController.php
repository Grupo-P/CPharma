<?php

namespace compras\Http\Controllers;

use Auth;
use compras\Auditoria;
use compras\Configuracion;
use compras\ContProveedor;
use compras\ContReclamo;
use compras\Sede;
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
        $reclamos = ContReclamo::sede(Auth::user()->sede)->orderByDesc('id')->get();
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

        $sedes = Sede::orderBy('razon_social')->get();

        return view('pages.contabilidad.reclamos.create', compact('documentos', 'sedes', 'proveedores'));
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
        $reclamo->comentario                = $request->input('comentario');
        $reclamo->sede                      = $request->input('sede');
        $reclamo->usuario_registro          = auth()->user()->name;
        $reclamo->save();

        $proveedor        = ContProveedor::find($request->input('id_proveedor'));
        $proveedor->saldo = (float) $proveedor->saldo + (float) $reclamo->monto;
        $proveedor->save();

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

        $sedes = Sede::orderBy('razon_social')->get();

        return view('pages.contabilidad.reclamos.edit', compact('sedes', 'reclamo', 'documentos', 'proveedores'));
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
        $reclamo = ContReclamo::find($id);

        $proveedor        = ContProveedor::find($request->input('id_proveedor'));
        $proveedor->saldo = (float) $proveedor->saldo - (float) $reclamo->monto;
        $proveedor->save();

        $reclamo->id_proveedor              = $request->input('id_proveedor');
        $reclamo->monto                     = $request->input('monto');
        $reclamo->documento_soporte_reclamo = $request->input('documento_soporte_reclamo');
        $reclamo->numero_documento          = $request->input('numero_documento');
        $reclamo->comentario                = $request->input('comentario');
        $reclamo->sede                      = $request->input('sede');
        $reclamo->save();

        $proveedor->saldo = (float) $proveedor->saldo + (float) $reclamo->monto;
        $proveedor->save();

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
        $reclamo             = ContReclamo::find($id);
        $reclamo->deleted_at = date('Y-m-d h:i:s');
        $reclamo->save();

        $proveedor        = ContProveedor::find($reclamo->id_proveedor);
        $proveedor->saldo = (float) $proveedor->saldo - (float) $reclamo->monto;
        $proveedor->save();

        $auditoria           = new Auditoria();
        $auditoria->accion   = 'ELIMINAR';
        $auditoria->tabla    = 'RECLAMO';
        $auditoria->registro = $reclamo->id;
        $auditoria->user     = auth()->user()->name;
        $auditoria->save();

        return redirect('/reclamos')->with('Deleted', ' Informacion');
    }

    public function validar(Request $request)
    {
        $deuda = ContReclamo::where('id_proveedor', $request->id_proveedor)
            ->where('numero_documento', $request->numero_documento)
            ->get();

        if ($request->id) {
            $deuda = ContReclamo::where('id_proveedor', $request->id_proveedor)
                ->where('numero_documento', $request->numero_documento)
                ->where('id', '!=', $request->id)
                ->get();
        }

        if ($deuda->count()) {
            return 'error';
        }

        return 'exito';
    }
}
