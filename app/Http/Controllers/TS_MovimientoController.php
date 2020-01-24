<?php

namespace compras\Http\Controllers;

use Illuminate\Http\Request;

use compras\TS_Movimiento;
use compras\Configuracion;
use compras\Auditoria;
use compras\User;

class TS_MovimientoController extends Controller {
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request) {
        $movimientos = TS_Movimiento::where('tasa_ventas_id', $request->tasa_ventas_id)->orderBy('created_at', 'desc')->get();
        return view('pages.TS.movimiento.index', compact('movimientos'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create() {
        return view('pages.TS.movimiento.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request) {
        try {
            $movimiento = new TS_Movimiento();

            $movimiento->tasa_ventas_id = $request->input('tasa_ventas_id');

            switch($request->input('tasa_ventas_id')) {
                case 1:
                    $configuracion = Configuracion::find(7);
                    $configuracion2 = Configuracion::find(9);
                    break;
                case 2:
                    $configuracion = Configuracion::find(8);
                    $configuracion2 = Configuracion::find(10);
                    break;
            }

            $movimiento->saldo_anterior = $configuracion->valor;

            switch($request->movimiento) {
                case "Ingreso":
                    $movimiento->ingresos = $request->input('monto');
                    $configuracion->valor += $request->input('monto');
                    break;
                case "Egreso":
                    $movimiento->egresos = $request->input('monto');
                    $configuracion->valor -= $request->input('monto');
                    break;
                case "Diferido":
                    $movimiento->diferido_anterior = $configuracion2->valor;

                    $movimiento->diferido = $request->input('monto');
                    $configuracion->valor -= $request->input('monto');
                    $configuracion2->valor += $request->input('monto');
                    
                    $movimiento->diferido_actual = $configuracion2->valor;
                    break;
            }

            $movimiento->saldo_actual = $configuracion->valor;
            $movimiento->concepto = $request->input('concepto');
            $movimiento->user = auth()->user()->name;
            
            $movimiento->save();
            $configuracion->save();
            $configuracion2->save();

            //-------------------- AUDITORIA --------------------//
            $Auditoria = new Auditoria();
            $Auditoria->accion = 'CREAR';
            $Auditoria->tabla = 'TS_MOVIMIENTOS';
            $Auditoria->registro = $request->input('monto');
            $Auditoria->user = auth()->user()->name;
            $Auditoria->save();

            return redirect()
            ->route('movimientos.index', 'tasa_ventas_id='.$request->tasa_ventas_id)
            ->with('Saved', ' Informacion');
                
        }
        catch(\Illuminate\Database\QueryException $e) {
            return back()->with('Error', ' Error');
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id) {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id) {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id) {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id) {
        //
    }
}
