<?php

namespace compras\Http\Controllers;

use compras\ContBanco;
use compras\ContCuenta;
use compras\ContPagoBancario;
use compras\ContPagoEfectivo;
use compras\ContProveedor;
use Datetime;
use DB;
use Illuminate\Http\Request;

class ContReporteController extends Controller
{
    public function index()
    {
        return view('pages.contabilidad.reportes.index');
    }

    public function pagos_por_fecha(Request $request)
    {
        if ($request->get('fechaInicio')) {
            $fechaInicio = new Datetime($request->get('fechaInicio'));
            $fechaInicio = $fechaInicio->format('d/m/Y');

            $fechaFin = new Datetime($request->get('fechaFin'));
            $fechaFin = $fechaFin->format('d/m/Y');

            $bancario = ContPagoBancario::whereDate('created_at', '>=', $request->get('fechaInicio'))
                ->whereDate('created_at', '<=', $request->get('fechaFin'))
                ->get();

            $efectivo = ContPagoEfectivo::whereDate('created_at', '>=', $request->get('fechaInicio'))
                ->whereDate('created_at', '<=', $request->get('fechaFin'))
                ->whereNull('ingresos')
                ->get();

            $pagos = [];

            foreach ($bancario as $item) {
                $pagos[] = $item;
            }

            foreach ($efectivo as $item) {
                $pagos[] = $item;
            }

            $pagos = collect($pagos)->sortBy('created_at');
        }

        return view('pages.contabilidad.reportes.pagos-por-fecha', compact('fechaInicio', 'fechaFin', 'request', 'pagos'));
    }

    public function movimientos_por_proveedor(Request $request)
    {
        $sqlProveedores = ContProveedor::get();
        $i              = 0;
        $proveedores    = [];

        foreach ($sqlProveedores as $proveedor) {
            $proveedores[$i]['label']  = $proveedor->nombre_proveedor . ' | ' . $proveedor->rif_ci;
            $proveedores[$i]['value']  = $proveedor->nombre_proveedor . ' | ' . $proveedor->rif_ci;
            $proveedores[$i]['id']     = $proveedor->id;
            $proveedores[$i]['moneda'] = $proveedor->moneda;

            $i = $i + 1;
        }

        if ($request->get('id_proveedor')) {
            $fechaInicio = new Datetime($request->get('fechaInicio'));
            $fechaInicio = $fechaInicio->format('d/m/Y');

            $fechaFin = new Datetime($request->get('fechaFin'));
            $fechaFin = $fechaFin->format('d/m/Y');

            $movimientos = [];

            $efectivo = DB::select("
                SELECT
                    cont_pagos_efectivo.created_at AS fecha,
                    CONCAT('Pago en efectivo en ', (SELECT sedes.siglas FROM sedes WHERE sedes.razon_social = cont_pagos_efectivo.sede)) AS tipo,
                    LPAD(cont_pagos_efectivo.id, 5, '0') AS nro_movimiento,
                    cont_pagos_efectivo.egresos AS monto,
                    cont_pagos_efectivo.concepto AS comentario,
                    IF(cont_pagos_efectivo.estatus_conciliaciones, 'Si', 'No') AS conciliacion,
                    cont_pagos_efectivo.user AS operador,
                    IF(cont_pagos_efectivo.deleted_at, 'Desincorporado', 'Activo') AS estado,
                    (SELECT cont_proveedores.moneda FROM cont_proveedores WHERE cont_proveedores.id = cont_pagos_efectivo.id_proveedor) AS moneda_proveedor,
                    cont_pagos_efectivo.tasa AS tasa
                FROM
                    cont_pagos_efectivo
                WHERE
                    cont_pagos_efectivo.egresos IS NOT NULL AND
                    cont_pagos_efectivo.id_proveedor = '{$request->get('id_proveedor')}' AND
                    DATE(cont_pagos_efectivo.created_at) >= '{$request->fechaInicio}' AND
                    DATE(cont_pagos_efectivo.created_at) <= '{$request->fechaFin}';
            ");

            foreach ($efectivo as $item) {
                $movimientos[] = $item;
            }

            $bancarios = DB::select("
                SELECT
                    cont_pagos_bancarios.created_at AS fecha,
                    CONCAT('Pago bancario por ', (SELECT cont_bancos.alias_cuenta FROM cont_bancos WHERE cont_bancos.id = cont_pagos_bancarios.id_banco)) AS tipo,
                    '-' AS nro_movimiento,
                    cont_pagos_bancarios.monto,
                    '-' AS comentario,
                    IF(cont_pagos_bancarios.estatus = 'Conciliado', 'Si', 'No') AS conciliacion,
                    cont_pagos_bancarios.operador AS operador,
                    IF(cont_pagos_bancarios.deleted_at, 'Desincorporado', 'Activo') AS estado,
                    (SELECT cont_bancos.moneda FROM cont_bancos WHERE cont_bancos.id = cont_pagos_bancarios.id_banco) AS moneda_banco,
                    (SELECT cont_proveedores.moneda FROM cont_proveedores WHERE cont_proveedores.id = cont_pagos_bancarios.id_proveedor) AS moneda_proveedor,
                    cont_pagos_bancarios.tasa AS tasa
                FROM
                    cont_pagos_bancarios
                WHERE
                    cont_pagos_bancarios.id_proveedor = '{$request->get('id_proveedor')}' AND
                    DATE(cont_pagos_bancarios.created_at) >= '{$request->fechaInicio}' AND
                    DATE(cont_pagos_bancarios.created_at) <= '{$request->fechaFin}';
            ");

            foreach ($bancarios as $item) {
                $movimientos[] = $item;
            }

            $deudas = DB::select("
                SELECT
                    cont_deudas.created_at AS fecha,
                    'Deudas' AS tipo,
                    cont_deudas.numero_documento AS nro_movimiento,
                    cont_deudas.monto,
                    '-' AS comentario,
                    '-' AS conciliacion,
                    cont_deudas.usuario_registro AS operador,
                    IF(cont_deudas.deleted_at, 'Desincorporado', 'Activo') AS estado
                FROM
                    cont_deudas
                WHERE
                    cont_deudas.id_proveedor = '{$request->get('id_proveedor')}' AND
                    DATE(cont_deudas.created_at) >= '{$request->fechaInicio}' AND
                    DATE(cont_deudas.created_at) <= '{$request->fechaFin}';
            ");

            foreach ($deudas as $item) {
                $movimientos[] = $item;
            }

            $reclamos = DB::select("
                SELECT
                    cont_reclamos.created_at AS fecha,
                    'Reclamo' AS tipo,
                    cont_reclamos.numero_documento AS nro_movimiento,
                    cont_reclamos.monto,
                    cont_reclamos.comentario AS comentario,
                    '-' AS conciliacion,
                    cont_reclamos.usuario_registro AS operador,
                    IF(cont_reclamos.deleted_at, 'Desincorporado', 'Activo') AS estado

                FROM
                    cont_reclamos
                WHERE
                    cont_reclamos.id_proveedor = '{$request->get('id_proveedor')}' AND
                    DATE(cont_reclamos.created_at) >= '{$request->fechaInicio}' AND
                    DATE(cont_reclamos.created_at) <= '{$request->fechaFin}';
            ");

            foreach ($reclamos as $item) {
                $movimientos[] = $item;
            }

            $ajustes = DB::select("
               SELECT
                    cont_ajustes.created_at AS fecha,
                    'Ajuste' AS tipo,
                    '-' AS nro_movimiento,
                    cont_ajustes.monto,
                    cont_ajustes.comentario,
                    '-' AS conciliacion,
                    cont_ajustes.usuario_registro AS operador,
                    IF(cont_ajustes.deleted_at, 'Desincorporado', 'Activo') AS estado
                FROM
                    cont_ajustes
                WHERE
                    cont_ajustes.id_proveedor = '{$request->get('id_proveedor')}' AND
                    DATE(cont_ajustes.created_at) >= '{$request->fechaInicio}' AND
                    DATE(cont_ajustes.created_at) <= '{$request->fechaFin}';
            ");

            foreach ($ajustes as $item) {
                $movimientos[] = $item;
            }

            $movimientos = collect($movimientos)->sortByDesc('fecha');

            $proveedor = ContProveedor::find($request->get('id_proveedor'));
        }

        return view('pages.contabilidad.reportes.movimientos-por-proveedor', compact('movimientos', 'fechaInicio', 'fechaFin', 'request', 'proveedores', 'proveedor'));
    }

    public function movimientos_bancarios(Request $request)
    {
        $bancos = ContBanco::orderBy('alias_cuenta', 'ASC')->get();

        if ($request->get('id_banco')) {
            $fechaInicio = new Datetime($request->get('fechaInicio'));
            $fechaInicio = $fechaInicio->format('d/m/Y');

            $fechaFin = new Datetime($request->get('fechaFin'));
            $fechaFin = $fechaFin->format('d/m/Y');

            $banco = ContBanco::find($request->id_banco);

            $pagos = ContPagoBancario::where('id_banco', $request->get('id_banco'))
                ->whereDate('created_at', '>=', $request->get('fechaInicio'))
                ->whereDate('created_at', '<=', $request->get('fechaFin'))
                ->orderByDesc('id')
                ->get();
        }

        return view('pages.contabilidad.reportes.movimientos-bancarios', compact('pagos', 'fechaInicio', 'fechaFin', 'banco', 'bancos', 'request'));
    }

    public function deudas_por_fecha(Request $request)
    {
        if ($request->get('fechaInicio')) {
            $fechaInicio = new Datetime($request->get('fechaInicio'));
            $fechaInicio = $fechaInicio->format('d/m/Y');

            $fechaFin = new Datetime($request->get('fechaFin'));
            $fechaFin = $fechaFin->format('d/m/Y');

            $deuda = DB::select("
                SELECT
                    cont_deudas.id,
                    cont_deudas.created_at,
                    'Deuda' AS tipo,
                    (SELECT cont_proveedores.nombre_proveedor FROM cont_proveedores WHERE cont_proveedores.id = cont_deudas.id_proveedor) AS proveedor,
                    (SELECT cont_proveedores.moneda FROM cont_proveedores WHERE cont_proveedores.id = cont_deudas.id_proveedor) AS moneda_proveedor,
                    (SELECT cont_proveedores.moneda FROM cont_proveedores WHERE cont_proveedores.id = cont_deudas.id_proveedor) AS moneda,
                    cont_deudas.monto,
                    cont_deudas.sede,
                    cont_deudas.usuario_registro AS operador,
                    IF(cont_deudas.deleted_at, 'Activo', 'Desincorporado') AS estado
                FROM cont_deudas
                WHERE
                    DATE(cont_deudas.created_at) >= '{$request->get('fechaInicio')}' AND DATE(cont_deudas.created_at) <= '{$request->get('fechaFin')}';
            ");

            $reclamo = DB::select("
                SELECT
                    cont_reclamos.id,
                    cont_reclamos.created_at,
                    'Reclamo' AS tipo,
                    (SELECT cont_proveedores.nombre_proveedor FROM cont_proveedores WHERE cont_proveedores.id = cont_reclamos.id_proveedor) AS proveedor,
                    (SELECT cont_proveedores.moneda FROM cont_proveedores WHERE cont_proveedores.id = cont_reclamos.id_proveedor) AS moneda_proveedor,
                    (SELECT cont_proveedores.moneda FROM cont_proveedores WHERE cont_proveedores.id = cont_reclamos.id_proveedor) AS moneda,
                    cont_reclamos.monto,
                    cont_reclamos.sede,
                    cont_reclamos.usuario_registro AS operador,
                    IF(cont_reclamos.deleted_at, 'Activo', 'Desincorporado') AS estado
                FROM cont_reclamos
                WHERE
                    DATE(cont_reclamos.created_at) >= '{$request->get('fechaInicio')}' AND DATE(cont_reclamos.created_at) <= '{$request->get('fechaFin')}';
            ");

            $items = [];

            foreach ($deuda as $item) {
                $items[] = $item;
            }

            foreach ($reclamo as $item) {
                $items[] = $item;
            }

            $items = collect($items)->sortBy('created_at');
        }

        return view('pages.contabilidad.reportes.deudas-por-fecha', compact('request', 'fechaInicio', 'fechaFin', 'items'));
    }

    public function reporte_por_cuentas(Request $request)
    {
        error_reporting(0);

        $sqlCuentas = ContCuenta::get();
        $i          = 0;
        $cuentas    = [];

        foreach ($sqlCuentas as $cuenta) {
            $cuentas[$i]['label']  = $cuenta->nombre;
            $cuentas[$i]['value']  = $cuenta->nombre;
            $cuentas[$i]['id']     = $cuenta->id;
            $cuentas[$i]['nombre'] = $cuenta->nombre;

            $i = $i + 1;
        }

        if ($request->get('fechaInicio')) {
            $fechaInicio = new Datetime($request->get('fechaInicio'));
            $fechaInicio = $fechaInicio->format('d/m/Y');

            $fechaFin = new Datetime($request->get('fechaFin'));
            $fechaFin = $fechaFin->format('d/m/Y');

            $items = [];

            if ($request->get('id_cuenta')) {
                $efectivo = DB::select("
                    SELECT
                        cont_pagos_efectivo.created_at AS fecha,
                        CONCAT('Pago en efectivo en ', (SELECT sedes.siglas FROM sedes WHERE sedes.razon_social = cont_pagos_efectivo.sede)) AS tipo,
                        IF (cont_pagos_efectivo.egresos, cont_pagos_efectivo.egresos, cont_pagos_efectivo.diferido) AS monto,
                        cont_pagos_efectivo.user AS operador
                    FROM
                        cont_pagos_efectivo
                    WHERE
                        cont_pagos_efectivo.id_proveedor IS NOT NULL AND
                        (SELECT cont_proveedores.plan_cuentas FROM cont_proveedores WHERE cont_proveedores.id = cont_pagos_efectivo.id_proveedor) = '{$request->cuenta}' AND
                        DATE(cont_pagos_efectivo.created_at) >= '{$request->fechaInicio}' AND
                        DATE(cont_pagos_efectivo.created_at) <= '{$request->fechaFin}';
                ");

                foreach ($efectivo as $item) {
                    $items[] = $item;
                }

                $bancario = DB::select("
                    SELECT
                        cont_pagos_bancarios.created_at AS fecha,
                        CONCAT('Pago bancario por ', (SELECT cont_bancos.alias_cuenta FROM cont_bancos WHERE cont_bancos.id = cont_pagos_bancarios.id_banco)) AS tipo,
                        cont_pagos_bancarios.monto,
                        cont_pagos_bancarios.operador AS operador
                    FROM
                        cont_pagos_bancarios
                    WHERE
                        (SELECT cont_proveedores.plan_cuentas FROM cont_proveedores WHERE cont_proveedores.id = cont_pagos_bancarios.id_proveedor) = '{$request->cuenta}' AND
                        DATE(cont_pagos_bancarios.created_at) >= '{$request->fechaInicio}' AND
                        DATE(cont_pagos_bancarios.created_at) <= '{$request->fechaFin}';
                ");

                foreach ($bancario as $item) {
                    $items[] = $item;
                }

                $efectivo = DB::select("
                    SELECT
                        cont_pagos_efectivo.created_at AS fecha,
                        CONCAT('Ingreso en efectivo en ', (SELECT sedes.siglas FROM sedes WHERE sedes.razon_social = cont_pagos_efectivo.sede)) AS tipo,
                        cont_pagos_efectivo.ingresos AS monto,
                        cont_pagos_efectivo.user AS operador
                    FROM
                        cont_pagos_efectivo
                    WHERE
                        (cont_pagos_efectivo.ingresos IS NOT NULL) AND
                        cont_pagos_efectivo.id_cuenta = '{$request->id_cuenta}' AND
                        DATE(cont_pagos_efectivo.created_at) >= '{$request->fechaInicio}' AND
                        DATE(cont_pagos_efectivo.created_at) <= '{$request->fechaFin}';
                ");

                foreach ($efectivo as $item) {
                    $items[] = $item;
                }
            }

            $items = collect($items)->sortBy('created_at');
        }

        return view('pages.contabilidad.reportes.reporte-por-cuentas', compact('items', 'cuentas', 'fechaInicio', 'fechaFin', 'request'));
    }
}
