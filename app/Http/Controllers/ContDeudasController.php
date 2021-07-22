<?php

namespace compras\Http\Controllers;

use compras\Auditoria;
use compras\Configuracion;
use compras\ContDeuda;
use compras\ContProveedor;
use compras\Sede;
use compras\User;
use Illuminate\Http\Request;
use DB;

class ContDeudasController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $cantidad_registros = isset($_GET['cantidad_registros']) ? $_GET['cantidad_registros'] : 50;

        $deudas = ContDeuda::with('proveedor')
            ->numeroDocumento($request->get('numero_documento'))
            ->proveedor($request->get('id_proveedor'))
            ->rangoFecha($request->get('fecha_desde'), $request->get('fecha_hasta'))
            ->registradoPor($request->get('registrado_por'))
            ->sede($request->get('sede'))
            ->orderByDesc('id')
            ->paginate($cantidad_registros);

        $proveedores = ContProveedor::whereNull('deleted_at')
            ->orderBy('nombre_proveedor')
            ->get();

        $users = User::whereIn('departamento', ['TECNOLOGIA', 'GERENCIA', 'ADMINISTRACION', 'CONTABILIDAD'])
            ->get();

        $sedes = Sede::orderBy('razon_social')->get();

        return view('pages.contabilidad.deudas.index', compact('deudas', 'proveedores', 'users', 'sedes'));
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

        return view('pages.contabilidad.deudas.create', compact('documentos', 'proveedores', 'sedes'));
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
        $deuda->usuario_registro        = auth()->user()->name;
        $deuda->sede                    = $request->input('sede');
        $deuda->dias_credito            = $request->input('dias_credito');
        $deuda->save();

        $proveedor        = ContProveedor::find($request->input('id_proveedor'));
        $proveedor->saldo = (float) $proveedor->saldo + (float) $deuda->monto;
        $proveedor->save();

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

        $sedes = Sede::orderBy('razon_social')->get();

        return view('pages.contabilidad.deudas.edit', compact('deuda', 'documentos', 'proveedores', 'sedes'));
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
        $deuda = ContDeuda::find($id);

        $proveedor        = ContProveedor::find($request->input('id_proveedor'));
        $proveedor->saldo = (float) $proveedor->saldo - (float) $deuda->monto;
        $proveedor->save();

        $deuda->id_proveedor            = $request->input('id_proveedor');
        $deuda->documento_soporte_deuda = $request->input('documento_soporte_deuda');
        $deuda->numero_documento        = $request->input('numero_documento');
        $deuda->sede                    = $request->input('sede');
        $deuda->dias_credito            = $request->input('dias_credito');
        $deuda->save();

        $proveedor->saldo = (float) $proveedor->saldo + (float) $deuda->monto;
        $proveedor->save();

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
        $deuda             = ContDeuda::find($id);
        $deuda->deleted_at = date('Y-m-d h:i:s');
        $deuda->save();

        $proveedor        = ContProveedor::find($deuda->id_proveedor);
        $proveedor->saldo = (float) $proveedor->saldo - (float) $deuda->monto;
        $proveedor->save();

        $auditoria           = new Auditoria();
        $auditoria->accion   = 'ELIMINAR';
        $auditoria->tabla    = 'DEUDA';
        $auditoria->registro = $deuda->id;
        $auditoria->user     = auth()->user()->name;
        $auditoria->save();

        return redirect('/deudas')->with('Deleted', ' Informacion');
    }

    public function validar(Request $request)
    {
        $deuda = ContDeuda::where('id_proveedor', $request->id_proveedor)
            ->where('numero_documento', $request->numero_documento)
            ->get();

        if ($request->id) {
            $deuda = ContDeuda::where('id_proveedor', $request->id_proveedor)
                ->where('numero_documento', $request->numero_documento)
                ->where('id', '!=', $request->id)
                ->get();
        }

        if ($deuda->count()) {
            return 'error';
        }

        return 'exito';
    }

    public function pizarra()
    {
        if (!isset($_GET['tipo']) || $_GET['tipo'] == 'dolares') {
            $positivos = DB::select("
                SELECT
                    cont_proveedores.id AS id_proveedor,
                    cont_proveedores.nombre_proveedor AS proveedor,
                    FORMAT(cont_proveedores.saldo, 2, 'de_DE') AS saldo,
                    cont_proveedores.saldo AS saldoNoFormateado
                FROM
                    cont_proveedores
                    LEFT JOIN cont_pagos_efectivo ON cont_proveedores.id = cont_pagos_efectivo.id_proveedor
                    LEFT JOIN cont_pagos_bancarios ON cont_proveedores.id = cont_pagos_bancarios.id_proveedor
                    LEFT JOIN cont_deudas ON cont_proveedores.id = cont_deudas.id_proveedor
                WHERE
                    cont_proveedores.saldo > 0 AND cont_proveedores.moneda = 'Dólares'
                GROUP BY proveedor
                ORDER BY CAST(saldo AS UNSIGNED) DESC;
            ");

            $negativos = DB::select("
                SELECT
                    cont_proveedores.id AS id_proveedor,
                    cont_proveedores.nombre_proveedor AS proveedor,
                    FORMAT(cont_proveedores.saldo, 2, 'de_DE') AS saldo,
                    cont_proveedores.saldo AS saldoNoFormateado
                FROM
                    cont_proveedores
                    LEFT JOIN cont_pagos_efectivo ON cont_proveedores.id = cont_pagos_efectivo.id_proveedor
                    LEFT JOIN cont_pagos_bancarios ON cont_proveedores.id = cont_pagos_bancarios.id_proveedor
                    LEFT JOIN cont_deudas ON cont_proveedores.id = cont_deudas.id_proveedor
                WHERE
                    cont_proveedores.saldo < 0 AND cont_proveedores.moneda = 'Dólares'
                GROUP BY proveedor
                ORDER BY CAST(saldo AS UNSIGNED) ASC;
            ");
        } else {
            $positivos = DB::select("
                SELECT
                    cont_proveedores.id AS id_proveedor,
                    cont_proveedores.nombre_proveedor AS proveedor,
                    FORMAT(cont_proveedores.saldo, 2, 'de_DE') AS saldo
                FROM
                    cont_proveedores
                    LEFT JOIN cont_pagos_efectivo ON cont_proveedores.id = cont_pagos_efectivo.id_proveedor
                    LEFT JOIN cont_pagos_bancarios ON cont_proveedores.id = cont_pagos_bancarios.id_proveedor
                    LEFT JOIN cont_deudas ON cont_proveedores.id = cont_deudas.id_proveedor
                WHERE
                    cont_proveedores.saldo > 0 AND cont_proveedores.moneda = 'Bolívares'
                GROUP BY proveedor
                ORDER BY CAST(saldo AS UNSIGNED) DESC;
            ");

            $negativos = DB::select("
                SELECT
                    cont_proveedores.id AS id_proveedor,
                    cont_proveedores.nombre_proveedor AS proveedor,
                    FORMAT(cont_proveedores.saldo, 2, 'de_DE') AS saldo
                FROM
                    cont_proveedores
                    LEFT JOIN cont_pagos_efectivo ON cont_proveedores.id = cont_pagos_efectivo.id_proveedor
                    LEFT JOIN cont_pagos_bancarios ON cont_proveedores.id = cont_pagos_bancarios.id_proveedor
                    LEFT JOIN cont_deudas ON cont_proveedores.id = cont_deudas.id_proveedor
                WHERE
                    cont_proveedores.saldo < 0 AND cont_proveedores.moneda = 'Bolívares'
                GROUP BY proveedor
                ORDER BY CAST(saldo AS UNSIGNED) ASC;
            ");
        }

        return view('pages.contabilidad.deudas.pizarra', compact('positivos', 'negativos'));
    }
}
