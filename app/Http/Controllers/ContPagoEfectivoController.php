<?php

namespace compras\Http\Controllers;

use compras\Auditoria;
use compras\Configuracion;
use compras\ContCuenta;
use compras\ContPagoEfectivo;
use compras\ContProveedor;
use compras\Sede;
use Illuminate\Http\Request;

class ContPagoEfectivoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $pagos = ContPagoEfectivo::sede($request->get('sede'))
            ->fecha($request->get('fecha_desde'), $request->get('fecha_hasta'))
            ->orderBy('created_at', 'desc')
            ->get();

        $sedes = Sede::get();

        return view('pages.contabilidad.efectivo.index', compact('pagos', 'sedes', 'request'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        if ($request->ajax()) {
            $proveedor = ContProveedor::find($request->id_proveedor);

            $resultado['saldo'] = number_format($proveedor->saldo, 2, ',', '.');

            if ($proveedor->moneda != 'Dólares') {
                if ($proveedor->moneda == 'Bolívares') {
                    $configuracion = Configuracion::where('variable', 'DolaresBolivares')->first();
                }

                if ($proveedor->moneda == 'Pesos') {
                    $configuracion = Configuracion::where('variable', 'DolaresPesos')->first();
                }

                $resultado['min']  = $configuracion->valor - ($configuracion->valor * 0.20);
                $resultado['max']  = $configuracion->valor + ($configuracion->valor * 0.20);
                $resultado['tasa'] = true;
            }

            return $resultado;
        }

        $cuentas = ContCuenta::get();

        if ($request->get('tipo') == 'proveedores') {
            $sqlProveedores = ContProveedor::whereNull('deleted_at')->get();
            $i              = 0;

            foreach ($sqlProveedores as $proveedor) {
                $proveedores[$i]['label']  = $proveedor->nombre_proveedor . ' | ' . $proveedor->rif_ci;
                $proveedores[$i]['value']  = $proveedor->nombre_proveedor . ' | ' . $proveedor->rif_ci;
                $proveedores[$i]['id']     = $proveedor->id;
                $proveedores[$i]['moneda'] = $proveedor->moneda;
                $proveedores[$i]['saldo']  = number_format($proveedor->saldo, 2, ',', '');

                $i = $i + 1;
            }
        } else {
            $proveedores = '';
        }

        return view('pages.contabilidad.efectivo.create', compact('cuentas', 'request', 'proveedores'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        try {
            $pago = new ContPagoEfectivo();

            $pago->sede = auth()->user()->sede;

            $configuracion  = Configuracion::where('variable', 'SaldoEfectivo')->first();
            $configuracion2 = Configuracion::where('variable', 'DiferidoEfectivo')->first();

            $pago->saldo_anterior = $configuracion->valor;

            switch ($request->input('movimiento')) {
                case "Ingreso":
                    $pago->ingresos = $request->input('monto');
                    $configuracion->valor += $request->input('monto');
                    $pago->concepto = $request->input('concepto');
                    break;
                case "Egreso":
                    $pago->egresos = $request->input('monto');
                    $configuracion->valor -= $request->input('monto');
                    $pago->concepto = $request->input('concepto');
                    break;
                case "Diferido":
                    $pago->diferido_anterior = $configuracion2->valor;

                    $pago->diferido = $request->input('monto');
                    $configuracion->valor -= $request->input('monto');
                    $configuracion2->valor += $request->input('monto');
                    $pago->estatus = 'DIFERIDO';
                    $pago->user_up = auth()->user()->name;

                    $pago->diferido_actual = $configuracion2->valor;
                    $pago->concepto        = $request->input('concepto') . " - DIFERIDO";
                    break;
            }

            if ($request->input('id_cuenta')) {
                $pago->id_cuenta      = $request->input('id_cuenta');
                $pago->autorizado_por = $request->input('autorizado_por');
            }

            if ($request->input('id_proveedor')) {
                $pago->id_proveedor = $request->input('id_proveedor');

                $proveedor = ContProveedor::find($request->input('id_proveedor'));

                if ($proveedor->moneda != 'Dólares') {
                    $monto = $request->input('monto') * $request->input('tasa');
                } else {
                    $monto = $request->input('monto');
                }

                $proveedor->saldo = (float) $proveedor->saldo - (float) $monto;
                $proveedor->save();

                $pago->tasa = $request->input('tasa');
            }

            $pago->saldo_actual = $configuracion->valor;
            $pago->user         = auth()->user()->name;

            $pago->save();
            $configuracion->save();
            $configuracion2->save();

            //-------------------- AUDITORIA --------------------//
            $Auditoria           = new Auditoria();
            $Auditoria->accion   = 'CREAR';
            $Auditoria->tabla    = 'PAGO EN EFECTIVO';
            $Auditoria->registro = $request->input('monto');
            $Auditoria->user     = auth()->user()->name;
            $Auditoria->save();

            return redirect('/efectivo')->with('Saved', ' Informacion');

        } catch (\Illuminate\Database\QueryException $e) {
            dd($e);
            return back()->with('Error', ' Error');
        }
    }

    public function soporte($id)
    {
        $pago = ContPagoEfectivo::find($id);
        return view('pages.contabilidad.efectivo.soporte', compact('pago'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $pago = ContPagoEfectivo::find($id);
        return view('pages.contabilidad.efectivo.edit', compact('pago'));
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
        try {
            /********************* PROCESO DE MOVIMIENTO *********************/
            $movimiento       = new ContPagoEfectivo();
            $movimiento->sede = auth()->user()->sede;

            $configuracion  = Configuracion::where('variable', 'SaldoEfectivo')->first();
            $configuracion2 = Configuracion::where('variable', 'DiferidoEfectivo')->first();

            $movimiento->saldo_anterior = $configuracion->valor;

            switch ($request->movimiento) {
                case "Ingreso":
                    $movimiento->ingresos = $request->input('monto');
                    $configuracion->valor += $request->input('monto');
                    break;
                case "Egreso":
                    $movimiento->egresos = $request->input('monto');
                    break;
            }

            $movimiento->saldo_actual = $configuracion->valor;
            $movimiento->concepto     = $request->input('concepto');
            $movimiento->user         = auth()->user()->name;

            /********************* ACTUALIZAR DIFERIDO *********************/
            $diferidos = ContPagoEfectivo::find($id);

            $diferidos->concepto          = $request->concepto;
            $diferidos->diferido_anterior = $configuracion2->valor;
            $configuracion2->valor -= $request->input('monto');
            $diferidos->user_up         = auth()->user()->name;
            $diferidos->estatus         = 'PAGADO';
            $diferidos->diferido_actual = $configuracion2->valor;

            /********************* GUARDAR CAMBIOS *********************/
            $movimiento->save();
            $configuracion->save();
            $configuracion2->save();
            $diferidos->save();

            /********************* AUDITORIA *********************/
            $Auditoria           = new Auditoria();
            $Auditoria->accion   = 'EDITAR';
            $Auditoria->tabla    = 'PAGO EN EFECTIVO';
            $Auditoria->registro = $request->input('monto');
            $Auditoria->user     = auth()->user()->name;
            $Auditoria->save();

            return redirect('/contabilidad/diferidos')->with('Updated', ' Informacion');
        } catch (\Illuminate\Database\QueryException $e) {
            dd($e);
            return back()->with('Error', ' Error');
        }
    }

    /********************* NO UTILIZADO *********************/

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    public function validar(Request $request)
    {
        $proveedor = ContProveedor::find($request->id_proveedor);
        $resultado = [];

        if ($proveedor->moneda != 'Dólares') {
            if ($proveedor->moneda == 'Bolívares') {
                $configuracion = Configuracion::where('variable', 'DolaresBolivares')->first();
            }

            if ($proveedor->moneda == 'Pesos') {
                $configuracion = Configuracion::where('variable', 'DolaresPesos')->first();
            }

            $resultado['min'] = $configuracion->valor - ($configuracion->valor * 0.20);
            $resultado['max'] = $configuracion->valor + ($configuracion->valor * 0.20)
;
            return $resultado;
        }
    }

    public function diferidos(Request $request)
    {
        $diferidos = ContPagoEfectivo::whereNotNull('diferido')
            ->orderByRaw('estatus ASC, id DESC')
            ->get();

        return view('pages.contabilidad.efectivo.diferidos', compact('diferidos'));
    }
}
