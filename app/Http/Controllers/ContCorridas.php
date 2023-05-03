<?php

namespace compras\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Exception;

class ContCorridas extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        try {
            $ftn['tasa_calculo'] = DB::connection('ftn')->select("SELECT * FROM configuracions WHERE variable = 'DolarCalculo'")[0];

        } catch (Exception $excepcion) {
            $ftn = false;
        }

        try {
            $fau['tasa_calculo'] = DB::connection('fau')->select("SELECT * FROM configuracions WHERE variable = 'DolarCalculo'")[0];

        } catch (Exception $excepcion) {
            $fau = false;
        }

        try {
            $fm['tasa_calculo'] = DB::connection('fm')->select("SELECT * FROM configuracions WHERE variable = 'DolarCalculo'")[0];

        } catch (Exception $excepcion) {
            $fm = false;
        }

        try {
            $fll['tasa_calculo'] = DB::connection('fll')->select("SELECT * FROM configuracions WHERE variable = 'DolarCalculo'")[0];

        } catch (Exception $excepcion) {
            $fll = false;
        }

        try {
            $kdi['tasa_calculo'] = DB::connection('kdi')->select("SELECT * FROM configuracions WHERE variable = 'DolarCalculo'")[0];

        } catch (Exception $excepcion) {
            $kdi = false;
        }

        try {
            $fec['tasa_calculo'] = DB::connection('fec')->select("SELECT * FROM configuracions WHERE variable = 'DolarCalculo'")[0];

        } catch (Exception $excepcion) {
            $fec = false;
        }

        try {
            $kd73['tasa_calculo'] = DB::connection('kd73')->select("SELECT * FROM configuracions WHERE variable = 'DolarCalculo'")[0];

        } catch (Exception $excepcion) {
            $kd73 = false;
        }

        return view('pages.contabilidad.corridas.index', compact('kdi', 'fm', 'fll', 'fau', 'ftn', 'fec', 'kd73'));
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
            include app_path() . '/functions/config.php';
            include app_path() . '/functions/querys_mysql.php';
            include app_path() . '/functions/querys_sqlserver.php';
            include app_path() . '/functions/functions.php';

            $sede = $request->sede;

            $configuracion = DB::connection(strtolower($request->sede))
                ->select("SELECT * FROM configuracions WHERE variable = 'DolarCalculo'");

            $tasa_caculo = $configuracion[0]->valor;

            $tipo_corrida = $request->tipo_corrida;
            $user = auth()->user()->name;

            FG_Corrida_Precio_Sede($sede, $tipo_corrida, $tasa_caculo, $user);
        }

        catch (Exception $excepcion) {
            return redirect('/corrida')->with('error', "No hay conexión con $sede");
        }

        return redirect('/corrida');

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
