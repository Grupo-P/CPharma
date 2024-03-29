<?php

namespace compras\Http\Controllers;

use compras\Auditoria;
use compras\Configuracion;
use compras\ContDeuda;
use compras\ContProveedor;
use compras\Sede;
use compras\User;
use DB;
use Illuminate\Http\Request;

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

        $sqlProveedores = ContProveedor::whereNull('deleted_at')->orderBy('nombre_proveedor', 'ASC')->get();
        $i              = 0;

        foreach ($sqlProveedores as $proveedor) {
            $proveedores[$i]['label']      = $proveedor->nombre_proveedor . ' | ' . $proveedor->rif_ci;
            $proveedores[$i]['value']      = $proveedor->nombre_proveedor . ' | ' . $proveedor->rif_ci;
            $proveedores[$i]['id']         = $proveedor->id;
            $proveedores[$i]['moneda']     = $proveedor->moneda;
            $proveedores[$i]['moneda_iva'] = $proveedor->moneda_iva;

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
        $deuda->monto_iva               = $request->input('monto_iva');
        $deuda->documento_soporte_deuda = $request->input('documento_soporte_deuda');
        $deuda->numero_documento        = $request->input('numero_documento');
        $deuda->usuario_registro        = auth()->user()->name;
        $deuda->sede                    = $request->input('sede');
        $deuda->dias_credito            = $request->input('dias_credito');
        $deuda->save();

        $proveedor            = ContProveedor::find($request->input('id_proveedor'));
        $proveedor->saldo     = (float) $proveedor->saldo + (float) $deuda->monto;
        $proveedor->saldo_iva = (float) $proveedor->saldo_iva + (float) $deuda->monto_iva;
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

        $sqlProveedores = ContProveedor::whereNull('deleted_at')->orderBy('nombre_proveedor', 'ASC')->get();
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

        $deuda->documento_soporte_deuda = $request->input('documento_soporte_deuda');
        $deuda->numero_documento        = $request->input('numero_documento');
        $deuda->sede                    = $request->input('sede');
        $deuda->dias_credito            = $request->input('dias_credito');
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
        $deuda             = ContDeuda::find($id);
        $deuda->deleted_at = date('Y-m-d h:i:s');
        $deuda->save();

        $proveedor            = ContProveedor::find($deuda->id_proveedor);
        $proveedor->saldo     = (float) $proveedor->saldo - (float) $deuda->monto;
        $proveedor->saldo_iva = (float) $proveedor->saldo_iva - (float) $deuda->monto_iva;
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
        $positivos = DB::select("
            SELECT
                cont_proveedores.id AS id_proveedor,
                cont_proveedores.nombre_proveedor AS proveedor,
                FORMAT(cont_proveedores.saldo, 2, 'en_US') AS saldo,
                FORMAT(cont_proveedores.saldo_iva, 2, 'en_US') AS saldo_iva,
                cont_proveedores.saldo AS saldoNoFormateado,
                cont_proveedores.saldo_iva AS saldoIvaNoFormateado,
                cont_proveedores.tasa AS tasa,
                cont_proveedores.moneda AS moneda,
                cont_proveedores.moneda_iva AS moneda_iva
            FROM
                cont_proveedores
            WHERE
                FORMAT(cont_proveedores.saldo + cont_proveedores.saldo_iva, 2, 'en_US') > 0
            GROUP BY proveedor
            ORDER BY CAST(saldo AS DOUBLE) DESC;
        ");

        $negativos = DB::select("
            SELECT
                cont_proveedores.id AS id_proveedor,
                cont_proveedores.nombre_proveedor AS proveedor,
                FORMAT(cont_proveedores.saldo, 2, 'en_US') AS saldo,
                FORMAT(cont_proveedores.saldo_iva, 2, 'en_US') AS saldo_iva,
                cont_proveedores.saldo AS saldoNoFormateado,
                cont_proveedores.saldo_iva AS saldoIvaNoFormateado,
                cont_proveedores.tasa AS tasa,
                cont_proveedores.moneda AS moneda,
                cont_proveedores.moneda_iva AS moneda_iva
            FROM
                cont_proveedores
            WHERE
                FORMAT(cont_proveedores.saldo + cont_proveedores.saldo_iva, 2, 'en_US') < 0
            GROUP BY proveedor
            ORDER BY CAST(saldo AS DOUBLE) ASC;
        ");

        $prepagados = DB::select("
            SELECT
                cont_proveedores.id AS id_proveedor,
                cont_proveedores.nombre_proveedor AS proveedor,
                FORMAT(cont_prepagados.monto, 2, 'en_US') AS monto,
                FORMAT(cont_prepagados.monto_iva, 2, 'en_US') AS monto_iva,
                cont_prepagados.monto AS montoNoFormateado,
                cont_prepagados.monto_iva AS montoIvaNoFormateado,
                cont_proveedores.moneda AS moneda,
                cont_proveedores.moneda_iva AS moneda_iva
            FROM
                cont_prepagados LEFT JOIN cont_proveedores ON cont_prepagados.id_proveedor = cont_proveedores.id
            WHERE
                cont_prepagados.status = 'Pendiente'
            GROUP BY
                cont_prepagados.id
        ");

        return view('pages.contabilidad.deudas.pizarra', compact('positivos', 'negativos', 'prepagados'));
    }
}
