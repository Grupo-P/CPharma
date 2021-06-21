<?php

namespace compras\Http\Controllers;

use compras\ContPagoBancario;
use compras\ContPagoEfectivo;
use compras\ContProveedor;
use compras\ContDeuda;
use compras\ContReclamo;
use Datetime;
use Illuminate\Http\Request;

use DB;

class ContReporteController extends Controller
{
    public function index()
    {
        return view('pages.contabilidad.reportes.index');
    }

    public function pagos_emitidos(Request $request)
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

        return view('pages.contabilidad.reportes.pagos-emitidos', compact('fechaInicio', 'fechaFin', 'request', 'pagos'));
    }

    public function movimientos_por_proveedor(Request $request)
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

        if ($request->get('id_proveedor')) {
            $fechaInicio = new Datetime($request->get('fechaInicio'));
            $fechaInicio = $fechaInicio->format('d/m/Y');

            $fechaFin = new Datetime($request->get('fechaFin'));
            $fechaFin = $fechaFin->format('d/m/Y');

            $pagos = [];

            $proveedor = ContProveedor::find($request->get('id_proveedor'));
        }

        return view('pages.contabilidad.reportes.movimientos-por-proveedor', compact('pagos', 'fechaInicio', 'fechaFin', 'request', 'proveedores', 'proveedor'));
    }

    public function movimientos_bancarios()
    {
        return view('pages.contabilidad.reportes.movimientos-bancarios');
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
                    cont_deudas.created_at,
                    'Deuda' AS tipo,
                    (SELECT cont_proveedores.nombre_proveedor FROM cont_proveedores WHERE cont_proveedores.id = cont_deudas.id_proveedor) AS proveedor,
                    COUNT(1) AS registros,
                    cont_deudas.monto,
                    cont_deudas.sede,
                    cont_deudas.usuario_registro AS operador
                FROM cont_deudas
                WHERE
                    DATE(cont_deudas.created_at) >= '{$request->get('fechaInicio')}' AND DATE(cont_deudas.created_at) <= '{$request->get('fechaFin')}'
                GROUP BY DATE(cont_deudas.created_at), cont_deudas.id_proveedor;
            ");

            $reclamo = DB::select("
                SELECT
                    cont_reclamos.created_at,
                    'Reclamo' AS tipo,
                    (SELECT cont_proveedores.nombre_proveedor FROM cont_proveedores WHERE cont_proveedores.id = cont_reclamos.id_proveedor) AS proveedor,
                    COUNT(1) AS registros,
                    cont_reclamos.monto,
                    cont_reclamos.sede,
                    cont_reclamos.usuario_registro AS operador
                FROM cont_reclamos
                WHERE
                    DATE(cont_reclamos.created_at) >= '{$request->get('fechaInicio')}' AND DATE(cont_reclamos.created_at) <= '{$request->get('fechaFin')}'
                GROUP BY DATE(cont_reclamos.created_at), cont_reclamos.id_proveedor;
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

    public function pagos_por_fecha(Request $request)
    {
        if ($request->get('fechaInicio')) {
        }

        return view('pages.contabilidad.reportes.pagos-por-fecha', compact('request'));
    }
}
