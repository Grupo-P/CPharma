<?php

namespace compras\Http\Controllers;

use Auth;
use compras\Configuracion;
use compras\ContDeuda;
use compras\ContPagoBancario;
use compras\ContPagoBolivaresFAU;
use compras\ContPagoBolivaresFLL;
use compras\ContPagoBolivaresFTN;
use compras\ContPagoBolivaresFM;
use compras\ContPagoBolivaresFEC;
use compras\ContPagoEfectivoFAU;
use compras\ContPagoEfectivoFLL;
use compras\ContPagoEfectivoFTN;
use compras\ContPagoEfectivoFM;
use compras\ContPagoEfectivoFEC;
use compras\ContReclamo;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        error_reporting(0);

        if ($_SERVER['SERVER_NAME'] == 'cpharmagptest.com' || $_SERVER['SERVER_NAME'] == 'cpharmagpde.com' || $_SERVER['SERVER_NAME'] == 'cpharmagp.com') {
            if (Auth::user()->departamento == 'OPERACIONES') {
                $deuda = ContDeuda::orderBy('id', 'DESC')
                    ->where('sede', Auth::user()->sede)
                    ->first();

                $reclamo = ContReclamo::orderBy('id', 'DESC')
                    ->where('sede', Auth::user()->sede)
                    ->first();

                return view('home-contabilidad', compact('deuda', 'reclamo'));
            }

            if (Auth::user()->departamento == 'TECNOLOGIA' || Auth::user()->departamento == 'GERENCIA' || Auth::user()->departamento == 'ADMINISTRACION') {
                $dolaresFTN = ContPagoEfectivoFTN::orderByDesc('id')->first();
                $dolaresFAU = ContPagoEfectivoFAU::orderByDesc('id')->first();
                $dolaresFLL = ContPagoEfectivoFLL::orderByDesc('id')->first();
                $dolaresFM = ContPagoEfectivoFM::orderByDesc('id')->first();
                $dolaresFEC = ContPagoEfectivoFEC::orderByDesc('id')->first();

                $bolivaresFTN = ContPagoBolivaresFTN::orderByDesc('id')->first();
                $bolivaresFAU = ContPagoBolivaresFAU::orderByDesc('id')->first();
                $bolivaresFLL = ContPagoBolivaresFLL::orderByDesc('id')->first();
                $bolivaresFM = ContPagoBolivaresFM::orderByDesc('id')->first();
                $bolivaresFEC = ContPagoBolivaresFEC::orderByDesc('id')->first();

                $diferidoDolaresFTN = ContPagoEfectivoFTN::whereNotNull('diferido')->orderByDesc('id')->first();
                $diferidoDolaresFAU = ContPagoEfectivoFAU::whereNotNull('diferido')->orderByDesc('id')->first();
                $diferidoDolaresFLL = ContPagoEfectivoFLL::whereNotNull('diferido')->orderByDesc('id')->first();
                $diferidoDolaresFM = ContPagoEfectivoFM::whereNotNull('diferido')->orderByDesc('id')->first();
                $diferidoDolaresFEC = ContPagoEfectivoFEC::whereNotNull('diferido')->orderByDesc('id')->first();

                $diferidoBolivaresFTN = ContPagoBolivaresFTN::whereNotNull('diferido')->orderByDesc('id')->first();
                $diferidoBolivaresFAU = ContPagoBolivaresFAU::whereNotNull('diferido')->orderByDesc('id')->first();
                $diferidoBolivaresFLL = ContPagoBolivaresFLL::whereNotNull('diferido')->orderByDesc('id')->first();
                $diferidoBolivaresFM = ContPagoBolivaresFM::whereNotNull('diferido')->orderByDesc('id')->first();
                $diferidoBolivaresFEC = ContPagoBolivaresFEC::whereNotNull('diferido')->orderByDesc('id')->first();

                $ftnDs = ContPagoEfectivoFTN::orderBy('id', 'DESC')->whereNull('ingresos')->first();
                $fauDs = ContPagoEfectivoFAU::orderBy('id', 'DESC')->whereNull('ingresos')->first();
                $fllDs = ContPagoEfectivoFLL::orderBy('id', 'DESC')->whereNull('ingresos')->first();
                $fmDs = ContPagoEfectivoFM::orderBy('id', 'DESC')->whereNull('ingresos')->first();
                $fecDs = ContPagoEfectivoFEC::orderBy('id', 'DESC')->whereNull('ingresos')->first();

                $ftnBs = ContPagoBolivaresFTN::orderBy('id', 'DESC')->whereNull('ingresos')->first();
                $fauBs = ContPagoBolivaresFAU::orderBy('id', 'DESC')->whereNull('ingresos')->first();
                $fllBs = ContPagoBolivaresFLL::orderBy('id', 'DESC')->whereNull('ingresos')->first();
                $fmBs = ContPagoBolivaresFM::orderBy('id', 'DESC')->whereNull('ingresos')->first();
                $fecBs = ContPagoBolivaresFEC::orderBy('id', 'DESC')->whereNull('ingresos')->first();

                $bancario = ContPagoBancario::orderBy('id', 'DESC')->whereNull('deleted_at')->first();

                $pago = null;

                if (isset($ftnDs)) {
                    $pago = $ftnDs;
                }

                if (isset($fauDs->created_at) && $pago->created_at < $fauDs->created_at) {
                    $pago = $fauDs;
                }

                if (isset($fllDs->created_at) && $pago->created_at < $fllDs->created_at) {
                    $pago = $fllDs;
                }

                if (isset($fmDs->created_at) && $pago->created_at < $fmDs->created_at) {
                    $pago = $fmDs;
                }

                if (isset($fecDs->created_at) && $pago->created_at < $fecDs->created_at) {
                    $pago = $fecDs;
                }

                if (isset($ftnBs->created_at) && $pago->created_at < $ftnBs->created_at) {
                    $pago = $ftnBs;
                }

                if (isset($fauBs->created_at) && $pago->created_at < $fauBs->created_at) {
                    $pago = $fauBs;
                }

                if (isset($fllBs->created_at) && $pago->created_at < $fllBs->created_at) {
                    $pago = $fllBs;
                }

                if (isset($fmBs->created_at) && $pago->created_at < $fmBs->created_at) {
                    $pago = $fmBs;
                }

                if (isset($fecBs->created_at) && $pago->created_at < $fecBs->created_at) {
                    $pago = $fecBs;
                }

                if (isset($bancario)) {
                    if (isset($bancario->created_at) && $pago->created_at < $bancario->created_at) {
                        $pago = $bancario;
                    }
                }

                $deuda = ContDeuda::orderByDesc('id')->first();

                return view('home-contabilidad', compact(
                    'dolaresFTN',
                    'dolaresFAU',
                    'dolaresFLL',
                    'dolaresFM',
                    'dolaresFEC',
                    'bolivaresFTN',
                    'bolivaresFAU',
                    'bolivaresFLL',
                    'bolivaresFM',
                    'bolivaresFEC',
                    'diferidoDolaresFTN',
                    'diferidoDolaresFAU',
                    'diferidoDolaresFLL',
                    'diferidoDolaresFM',
                    'diferidoDolaresFEC',
                    'diferidoBolivaresFTN',
                    'diferidoBolivaresFAU',
                    'diferidoBolivaresFLL',
                    'diferidoBolivaresFM',
                    'diferidoBolivaresFEC',
                    'pago',
                    'deuda'
                ));
            }

            if (Auth::user()->departamento == 'CONTABILIDAD') {
                $ftnDs = ContPagoEfectivoFTN::orderBy('id', 'DESC')->whereNull('ingresos')->first();
                $fauDs = ContPagoEfectivoFAU::orderBy('id', 'DESC')->whereNull('ingresos')->first();
                $fllDs = ContPagoEfectivoFLL::orderBy('id', 'DESC')->whereNull('ingresos')->first();
                $fmDs = ContPagoEfectivoFM::orderBy('id', 'DESC')->whereNull('ingresos')->first();
                $fecDs = ContPagoEfectivoFEC::orderBy('id', 'DESC')->whereNull('ingresos')->first();

                $ftnBs = ContPagoBolivaresFTN::orderBy('id', 'DESC')->whereNull('ingresos')->first();
                $fauBs = ContPagoBolivaresFAU::orderBy('id', 'DESC')->whereNull('ingresos')->first();
                $fllBs = ContPagoBolivaresFLL::orderBy('id', 'DESC')->whereNull('ingresos')->first();
                $fmBs = ContPagoBolivaresFM::orderBy('id', 'DESC')->whereNull('ingresos')->first();
                $fecBs = ContPagoBolivaresFEC::orderBy('id', 'DESC')->whereNull('ingresos')->first();

                $bancario = ContPagoBancario::orderBy('id', 'DESC')->whereNull('deleted_at')->first();

                $pago = $ftnDs;

                if (isset($fauDs->created_at) && $pago->created_at < $fauDs->created_at) {
                    $pago = $fauDs;
                }

                if (isset($fllDs->created_at) && $pago->created_at < $fllDs->created_at) {
                    $pago = $fllDs;
                }

                if (isset($fmDs->created_at) && $pago->created_at < $fmDs->created_at) {
                    $pago = $fmDs;
                }

                if (isset($fecDs->created_at) && $pago->created_at < $fecDs->created_at) {
                    $pago = $fecDs;
                }

                if (isset($ftnBs->created_at) && $pago->created_at < $ftnBs->created_at) {
                    $pago = $ftnBs;
                }

                if (isset($fauBs->created_at) && $pago->created_at < $fauBs->created_at) {
                    $pago = $fauBs;
                }

                if (isset($fllBs->created_at) && $pago->created_at < $fllBs->created_at) {
                    $pago = $fllBs;
                }

                if (isset($fmBs->created_at) && $pago->created_at < $fmBs->created_at) {
                    $pago = $fmBs;
                }

                if (isset($fecBs->created_at) && $pago->created_at < $fecBs->created_at) {
                    $pago = $fecBs;
                }

                if (isset($bancario->created_at) && $pago->created_at < $bancario->created_at) {
                    $pago = $bancario;
                }

                $deuda = ContDeuda::orderBy('id', 'DESC')->first();

                $ftnDs = ContPagoEfectivoFTN::orderBy('fecha_conciliado', 'DESC')->whereNotNull('fecha_conciliado')->first();
                $fauDs = ContPagoEfectivoFAU::orderBy('fecha_conciliado', 'DESC')->whereNotNull('fecha_conciliado')->first();
                $fllDs = ContPagoEfectivoFLL::orderBy('fecha_conciliado', 'DESC')->whereNotNull('fecha_conciliado')->first();
                $fmDs = ContPagoEfectivoFM::orderBy('fecha_conciliado', 'DESC')->whereNotNull('fecha_conciliado')->first();
                $fecDs = ContPagoEfectivoFEC::orderBy('fecha_conciliado', 'DESC')->whereNotNull('fecha_conciliado')->first();

                $ftnBs = ContPagoBolivaresFTN::orderBy('fecha_conciliado', 'DESC')->whereNotNull('fecha_conciliado')->first();
                $fauBs = ContPagoBolivaresFAU::orderBy('fecha_conciliado', 'DESC')->whereNotNull('fecha_conciliado')->first();
                $fllBs = ContPagoBolivaresFLL::orderBy('fecha_conciliado', 'DESC')->whereNotNull('fecha_conciliado')->first();
                $fmBs = ContPagoBolivaresFM::orderBy('fecha_conciliado', 'DESC')->whereNotNull('fecha_conciliado')->first();
                $fecBs = ContPagoBolivaresFEC::orderBy('fecha_conciliado', 'DESC')->whereNotNull('fecha_conciliado')->first();

                $conciliacion = collect([$ftnDs, $fauDs, $fllDs, $fmDs, $fecDs, $ftnBs, $fauBs, $fllBs, $fmBs, $fecBs])
                    ->sortByDesc('fecha_conciliado');

                $conciliacion = $conciliacion[0];

                return view('home-contabilidad', compact(
                    'pago',
                    'deuda',
                    'conciliacion'
                ));
            }

            if (Auth::user()->departamento == 'TESORERIA') {
                if(Auth()->user()->sede == 'FARMACIA TIERRA NEGRA, C.A.') {
                    $dolares = ContPagoEfectivoFTN::orderByDesc('id')->first();
                    $bolivares = ContPagoBolivaresFTN::orderByDesc('id')->first();

                    $movimiento = ($dolares) ? $dolares : null;

                    if (isset($bolivares)) {
                        if ($bolivares->created_at > $movimiento->created_at) {
                            $movimiento = $bolivares;
                        }
                    }

                    $diferidoDolares = ContPagoEfectivoFTN::whereNotNull('diferido')->orderByDesc('id')->first();
                    $diferidoBolivares = ContPagoBolivaresFTN::whereNotNull('diferido')->orderByDesc('id')->first();

                    $sede = 'FTN';
                }

                if(Auth()->user()->sede == 'FARMACIA AVENIDA UNIVERSIDAD, C.A.') {
                    $dolares = ContPagoEfectivoFAU::orderByDesc('id')->first();
                    $bolivares = ContPagoBolivaresFAU::orderByDesc('id')->first();

                    $movimiento = ($dolares) ? $dolares : null;

                    if (isset($bolivares)) {
                        if ($bolivares->created_at > $movimiento->created_at) {
                            $movimiento = $bolivares;
                        }
                    }

                    $diferidoDolares = ContPagoEfectivoFAU::whereNotNull('diferido')->orderByDesc('id')->first();
                    $diferidoBolivares = ContPagoBolivaresFAU::whereNotNull('diferido')->orderByDesc('id')->first();

                    $sede = 'FAU';
                }

                if(Auth()->user()->sede == 'FARMACIA LA LAGO,C.A.') {
                    $dolares = ContPagoEfectivoFLL::orderByDesc('id')->first();
                    $bolivares = ContPagoBolivaresFLL::orderByDesc('id')->first();

                    $movimiento = ($dolares) ? $dolares : null;

                    if (isset($bolivares)) {
                        if ($bolivares->created_at > $movimiento->created_at) {
                            $movimiento = $bolivares;
                        }
                    }

                    $diferidoDolares = ContPagoEfectivoFLL::whereNotNull('diferido')->orderByDesc('id')->first();
                    $diferidoBolivares = ContPagoBolivaresFLL::whereNotNull('diferido')->orderByDesc('id')->first();

                    $sede = 'FLL';
                }

                if(Auth()->user()->sede == 'FARMACIA MILLENNIUM 2000, C.A') {
                    $dolares = ContPagoEfectivoFM::orderByDesc('id')->first();
                    $bolivares = ContPagoBolivaresFM::orderByDesc('id')->first();

                    $movimiento = ($dolares) ? $dolares : null;

                    if (isset($bolivares)) {
                        if ($bolivares->created_at > $movimiento->created_at) {
                            $movimiento = $bolivares;
                        }
                    }

                    $diferidoDolares = ContPagoEfectivoFM::whereNotNull('diferido')->orderByDesc('id')->first();
                    $diferidoBolivares = ContPagoBolivaresFM::whereNotNull('diferido')->orderByDesc('id')->first();

                    $sede = 'FM';
                }

                if(Auth()->user()->sede == 'FARMACIA EL CALLEJON, C.A.') {
                    $dolares = ContPagoEfectivoFEC::orderByDesc('id')->first();
                    $bolivares = ContPagoBolivaresFEC::orderByDesc('id')->first();

                    $movimiento = ($dolares) ? $dolares : null;

                    if (isset($bolivares)) {
                        if ($bolivares->created_at > $movimiento->created_at) {
                            $movimiento = $bolivares;
                        }
                    }

                    $diferidoDolares = ContPagoEfectivoFEC::whereNotNull('diferido')->orderByDesc('id')->first();
                    $diferidoBolivares = ContPagoBolivaresFEC::whereNotNull('diferido')->orderByDesc('id')->first();

                    $sede = 'FEC';
                }

                return view('home-contabilidad', compact(
                    'dolares',
                    'bolivares',
                    'diferidoDolares',
                    'diferidoBolivares',
                    'sede',
                    'movimiento'
                ));
            }
        }

        return view('home');
    }
}
