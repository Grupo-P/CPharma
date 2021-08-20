<?php

namespace compras\Http\Controllers;

use compras\Auditoria;
use compras\Configuracion;
use compras\ContCuenta;
use compras\ContProveedor;
use Illuminate\Http\Request;

class ContProveedorController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $proveedores = ContProveedor::orderByDesc('id')->get();
        return view('pages.contabilidad.proveedores.index', compact('proveedores'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $tasas = Configuracion::where('variable', 'Tasa')->first();
        $tasas = explode(',', $tasas->valor);

        $monedas = Configuracion::where('variable', 'Moneda')->first();
        $monedas = explode(',', $monedas->valor);

        $cuentas = ContCuenta::where('pertenece_a', '!=', 'Principal')
            ->orderBy('pertenece_a')
            ->get();

        return view('pages.contabilidad.proveedores.create', compact('tasas', 'monedas', 'cuentas'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $rif_ci = ($request->input('rif_ci')) ? $request->input('prefix_rif_ci') . '-' . $request->input('rif_ci') : '';

        $proveedor                       = new ContProveedor();
        $proveedor->nombre_proveedor     = $request->input('nombre_proveedor');
        $proveedor->nombre_representante = $request->input('nombre_representante');
        $proveedor->rif_ci               = $rif_ci;
        $proveedor->correo_electronico   = $request->input('correo_electronico');
        $proveedor->direccion            = $request->input('direccion');
        $proveedor->tasa                 = $request->input('tasa');
        $proveedor->plan_cuentas         = $request->input('plan_cuentas');
        $proveedor->moneda               = $request->input('moneda');
        $proveedor->saldo                = $request->input('saldo');
        $proveedor->usuario_creado       = auth()->user()->name;
        $proveedor->save();

        $auditoria           = new Auditoria();
        $auditoria->accion   = 'CREAR';
        $auditoria->tabla    = 'PROVEEDOR';
        $auditoria->registro = $proveedor->id;
        $auditoria->user     = auth()->user()->name;
        $auditoria->save();

        return redirect('/proveedores')->with('Saved', ' Informacion');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $proveedor = ContProveedor::find($id);
        return view('pages.contabilidad.proveedores.show', compact('proveedor'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $proveedor = ContProveedor::find($id);

        $tasas = Configuracion::where('variable', 'Tasa')->first();
        $tasas = explode(',', $tasas->valor);

        $monedas = Configuracion::where('variable', 'Moneda')->first();
        $monedas = explode(',', $monedas->valor);

        $cuentas = ContCuenta::where('pertenece_a', '!=', 'Principal')
            ->orderBy('pertenece_a')
            ->get();

        return view('pages.contabilidad.proveedores.edit', compact('cuentas', 'proveedor', 'tasas', 'monedas'));
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
        $rif_ci = ($request->input('rif_ci')) ? $request->input('prefix_rif_ci') . '-' . $request->input('rif_ci') : '';

        $proveedor                       = ContProveedor::find($id);
        $proveedor->nombre_proveedor     = $request->input('nombre_proveedor');
        $proveedor->nombre_representante = $request->input('nombre_representante');
        $proveedor->rif_ci               = $rif_ci;
        $proveedor->correo_electronico   = $request->input('correo_electronico');
        $proveedor->direccion            = $request->input('direccion');
        $proveedor->tasa                 = $request->input('tasa');
        $proveedor->plan_cuentas         = $request->input('plan_cuentas');
        $proveedor->moneda               = $request->input('moneda');
        $proveedor->save();

        $auditoria           = new Auditoria();
        $auditoria->accion   = 'EDITAR';
        $auditoria->tabla    = 'PROVEEDOR';
        $auditoria->registro = $proveedor->id;
        $auditoria->user     = auth()->user()->name;
        $auditoria->save();

        return redirect('/proveedores')->with('Updated', ' Informacion');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $proveedor = ContProveedor::find($id);

        if ($proveedor->deleted_at) {
            $auditoria           = new Auditoria();
            $auditoria->accion   = 'ACTIVAR';
            $auditoria->tabla    = 'PROVEEDOR';
            $auditoria->registro = $proveedor->id;
            $auditoria->user     = auth()->user()->name;
            $auditoria->save();

            $proveedor->deleted_at = null;
            $proveedor->save();

            return redirect('/proveedores')->with('Activar', ' Informacion');
        } else {
            $auditoria           = new Auditoria();
            $auditoria->accion   = 'DESACTIVAR';
            $auditoria->tabla    = 'PROVEEDOR';
            $auditoria->registro = $proveedor->id;
            $auditoria->user     = auth()->user()->name;
            $auditoria->save();

            $proveedor->deleted_at = date('Y-m-d H:i:s');
            $proveedor->save();

            return redirect('/proveedores')->with('Desactivar', ' Informacion');
        }
    }

    public function validar(Request $request)
    {
        $proveedor = ContProveedor::where('rif_ci', $request->get('rif'))
            ->get();

        if ($request->get('id')) {
            $proveedor = ContProveedor::where('rif_ci', $request->get('rif'))
                ->where('id', '!=', $request->get('id'))
                ->get();
        }

        if ($proveedor->count()) {
            return 'error';
        } else {
            return 'success';
        }
    }
}
