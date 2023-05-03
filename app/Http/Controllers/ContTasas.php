<?php

namespace compras\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Exception;

class ContTasas extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        try {
            $ftn['tasa_venta'] = DB::connection('ftn')->select("SELECT * FROM tasa_ventas WHERE moneda = 'Dolar'")[0];
            $ftn['tasa_mercado'] = DB::connection('ftn')->select("SELECT * FROM dolars ORDER BY id DESC LIMIT 1")[0];
            $ftn['tasa_calculo'] = DB::connection('ftn')->select("SELECT * FROM configuracions WHERE variable = 'DolarCalculo'")[0];

        } catch (Exception $excepcion) {
            $ftn = false;
        }

        try {
            $fau['tasa_venta'] = DB::connection('fau')->select("SELECT * FROM tasa_ventas WHERE moneda = 'Dolar'")[0];
            $fau['tasa_mercado'] = DB::connection('fau')->select("SELECT * FROM dolars ORDER BY id DESC LIMIT 1")[0];
            $fau['tasa_calculo'] = DB::connection('fau')->select("SELECT * FROM configuracions WHERE variable = 'DolarCalculo'")[0];

        } catch (Exception $excepcion) {
            $fau = false;
        }

        try {
            $fm['tasa_venta'] = DB::connection('fm')->select("SELECT * FROM tasa_ventas WHERE moneda = 'Dolar'")[0];
            $fm['tasa_mercado'] = DB::connection('fm')->select("SELECT * FROM dolars ORDER BY id DESC LIMIT 1")[0];
            $fm['tasa_calculo'] = DB::connection('fm')->select("SELECT * FROM configuracions WHERE variable = 'DolarCalculo'")[0];

        } catch (Exception $excepcion) {
            $fm = false;
        }

        try {
            $fll['tasa_venta'] = DB::connection('fll')->select("SELECT * FROM tasa_ventas WHERE moneda = 'Dolar'")[0];
            $fll['tasa_mercado'] = DB::connection('fll')->select("SELECT * FROM dolars ORDER BY id DESC LIMIT 1")[0];
            $fll['tasa_calculo'] = DB::connection('fll')->select("SELECT * FROM configuracions WHERE variable = 'DolarCalculo'")[0];

        } catch (Exception $excepcion) {
            $fll = false;
        }

        try {
            $kdi['tasa_venta'] = DB::connection('kdi')->select("SELECT * FROM tasa_ventas WHERE moneda = 'Dolar'")[0];
            $kdi['tasa_mercado'] = DB::connection('kdi')->select("SELECT * FROM dolars ORDER BY id DESC LIMIT 1")[0];
            $kdi['tasa_calculo'] = DB::connection('kdi')->select("SELECT * FROM configuracions WHERE variable = 'DolarCalculo'")[0];

        } catch (Exception $excepcion) {
            $kdi = false;
        }

        try {
            $fec['tasa_venta'] = DB::connection('fec')->select("SELECT * FROM tasa_ventas WHERE moneda = 'Dolar'")[0];
            $fec['tasa_mercado'] = DB::connection('fec')->select("SELECT * FROM dolars ORDER BY id DESC LIMIT 1")[0];
            $fec['tasa_calculo'] = DB::connection('fec')->select("SELECT * FROM configuracions WHERE variable = 'DolarCalculo'")[0];

        } catch (Exception $excepcion) {
            $fec = false;
        }

        try {
            $kd73['tasa_venta'] = DB::connection('kd73')->select("SELECT * FROM tasa_ventas WHERE moneda = 'Dolar'")[0];
            $kd73['tasa_mercado'] = DB::connection('kd73')->select("SELECT * FROM dolars ORDER BY id DESC LIMIT 1")[0];
            $kd73['tasa_calculo'] = DB::connection('kd73')->select("SELECT * FROM configuracions WHERE variable = 'DolarCalculo'")[0];

        } catch (Exception $excepcion) {
            $kd73 = false;
        }

        $min = DB::select("SELECT * FROM configuracions WHERE variable = 'RangoMinDolar'")[0]->valor;
        $max = DB::select("SELECT * FROM configuracions WHERE variable = 'RangoMaxDolar'")[0]->valor;

        return view('pages.contabilidad.tasas.index', compact('ftn', 'fau', 'fm', 'fll', 'kdi', 'fec', 'kd73', 'min', 'max'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
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
            $venta = [$request['ftn']['tasa_venta'], auth()->user()->name, date('Y-m-d 00:00:00')];
            DB::connection('ftn')->update("UPDATE tasa_ventas SET tasa = ?, user = ?, fecha = ? WHERE moneda = 'Dolar'", $venta);

            $calculo = [$request['ftn']['tasa_calculo'], auth()->user()->name];
            DB::connection('ftn')->update("UPDATE configuracions SET valor = ?, user = ? WHERE variable = 'DolarCalculo'", $calculo);

            $mercado = [date('Y-m-d 00:00:00'), $request['ftn']['tasa_mercado'], auth()->user()->name];
            DB::connection('ftn')->insert("INSERT INTO dolars (fecha, tasa, user, estatus) VALUES (?, ?, ?, 'ACTIVO')", $mercado);

        } catch (Exception $excepcion) {
            $ftn = false;
        }

        try {
            $venta = [$request['fau']['tasa_venta'], auth()->user()->name, date('Y-m-d 00:00:00')];
            DB::connection('fau')->update("UPDATE tasa_ventas SET tasa = ?, user = ?, fecha = ? WHERE moneda = 'Dolar'", $venta);

            $calculo = [$request['fau']['tasa_calculo'], auth()->user()->name];
            DB::connection('fau')->update("UPDATE configuracions SET valor = ?, user = ? WHERE variable = 'DolarCalculo'", $calculo);

            $mercado = [date('Y-m-d 00:00:00'), $request['fau']['tasa_mercado'], auth()->user()->name];
            DB::connection('fau')->insert("INSERT INTO dolars (fecha, tasa, user, estatus) VALUES (?, ?, ?, 'ACTIVO')", $mercado);

        } catch (Exception $excepcion) {
            $fau = false;
        }

        try {
            $venta = [$request['fm']['tasa_venta'], auth()->user()->name, date('Y-m-d 00:00:00')];
            DB::connection('fm')->update("UPDATE tasa_ventas SET tasa = ?, user = ?, fecha = ? WHERE moneda = 'Dolar'", $venta);

            $calculo = [$request['fm']['tasa_calculo'], auth()->user()->name];
            DB::connection('fm')->update("UPDATE configuracions SET valor = ?, user = ? WHERE variable = 'DolarCalculo'", $calculo);

            $mercado = [date('Y-m-d 00:00:00'), $request['fm']['tasa_mercado'], auth()->user()->name];
            DB::connection('fm')->insert("INSERT INTO dolars (fecha, tasa, user, estatus) VALUES (?, ?, ?, 'ACTIVO')", $mercado);

        } catch (Exception $excepcion) {
            $fm = false;
        }

        try {
            $venta = [$request['fll']['tasa_venta'], auth()->user()->name, date('Y-m-d 00:00:00')];
            DB::connection('fll')->update("UPDATE tasa_ventas SET tasa = ?, user = ?, fecha = ? WHERE moneda = 'Dolar'", $venta);

            $calculo = [$request['fll']['tasa_calculo'], auth()->user()->name];
            DB::connection('fll')->update("UPDATE configuracions SET valor = ?, user = ? WHERE variable = 'DolarCalculo'", $calculo);

            $mercado = [date('Y-m-d 00:00:00'), $request['fll']['tasa_mercado'], auth()->user()->name];
            DB::connection('fll')->insert("INSERT INTO dolars (fecha, tasa, user, estatus) VALUES (?, ?, ?, 'ACTIVO')", $mercado);

        } catch (Exception $excepcion) {
            $fll = false;
        }

        return redirect('/tasas');
    }

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
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
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
}
