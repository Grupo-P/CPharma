<?php

namespace compras\Http\Controllers;

use Auth;
use compras\Configuracion;
use compras\ContDeuda;
use compras\ContPagoBolivaresFAU;
use compras\ContPagoBolivaresFLL;
use compras\ContPagoBolivaresFTN;
use compras\ContPagoEfectivoFAU;
use compras\ContPagoEfectivoFLL;
use compras\ContPagoEfectivoFTN;
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
        if ($_SERVER['SERVER_NAME'] == 'cpharmagpde.com' || $_SERVER['SERVER_NAME'] == 'cpharmagp.com') {
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
                $saldoDolaresFTN = Configuracion::where('variable', 'SaldoEfectivoFTN')->first()->valor;
                $saldoDolaresFAU = Configuracion::where('variable', 'SaldoEfectivoFAU')->first()->valor;
                $saldoDolaresFLL = Configuracion::where('variable', 'SaldoEfectivoFLL')->first()->valor;

                $saldoBolivaresFTN = Configuracion::where('variable', 'SaldoBolivaresFTN')->first()->valor;
                $saldoBolivaresFAU = Configuracion::where('variable', 'SaldoBolivaresFAU')->first()->valor;
                $saldoBolivaresFLL = Configuracion::where('variable', 'SaldoBolivaresFLL')->first()->valor;

                $diferidoDolaresFTN = Configuracion::where('variable', 'DiferidoEfectivoFTN')->first()->valor;
                $diferidoDolaresFAU = Configuracion::where('variable', 'DiferidoEfectivoFAU')->first()->valor;
                $diferidoDolaresFLL = Configuracion::where('variable', 'DiferidoEfectivoFLL')->first()->valor;

                $diferidoBolivaresFTN = Configuracion::where('variable', 'DiferidoBolivaresFTN')->first()->valor;
                $diferidoBolivaresFAU = Configuracion::where('variable', 'DiferidoBolivaresFAU')->first()->valor;
                $diferidoBolivaresFLL = Configuracion::where('variable', 'DiferidoBolivaresFLL')->first()->valor;

                $ftnDs = ContPagoEfectivoFTN::orderBy('id', 'DESC')->whereNull('ingresos')->first();
                $fauDs = ContPagoEfectivoFAU::orderBy('id', 'DESC')->whereNull('ingresos')->first();
                $fllDs = ContPagoEfectivoFLL::orderBy('id', 'DESC')->whereNull('ingresos')->first();
                $ftnBs = ContPagoBolivaresFTN::orderBy('id', 'DESC')->whereNull('ingresos')->first();
                $fauBs = ContPagoBolivaresFAU::orderBy('id', 'DESC')->whereNull('ingresos')->first();
                $fllBs = ContPagoBolivaresFLL::orderBy('id', 'DESC')->whereNull('ingresos')->first();

                $pago = $ftnDs;

                if (isset($fauDs->created_at) && $pago->created_at > $fauDs->created_at) {
                    $pago = $fauDs;
                }

                if (isset($fllDs->created_at) && $pago->created_at > $fllDs->created_at) {
                    $pago = $fllDs;
                }

                if (isset($ftnBs->created_at) && $pago->created_at > $ftnBs->created_at) {
                    $pago = $ftnBs;
                }

                if (isset($fauBs->created_at) && $pago->created_at > $fauBs->created_at) {
                    $pago = $fauBs;
                }

                if (isset($fllBs->created_at) && $pago->created_at > $fllBs->created_at) {
                    $pago = $fllBs;
                }

                $deuda = ContDeuda::orderByDesc('id')->first();

                return view('home-contabilidad', compact(
                    'saldoDolaresFTN',
                    'saldoDolaresFAU',
                    'saldoDolaresFLL',
                    'saldoBolivaresFTN',
                    'saldoBolivaresFAU',
                    'saldoBolivaresFLL',
                    'diferidoDolaresFTN',
                    'diferidoDolaresFAU',
                    'diferidoDolaresFLL',
                    'diferidoBolivaresFTN',
                    'diferidoBolivaresFAU',
                    'diferidoBolivaresFLL',
                    'pago',
                    'deuda'
                ));
            }

            if (Auth::user()->departamento == 'CONTABILIDAD') {
                $ftnDs = ContPagoEfectivoFTN::orderBy('id', 'DESC')->whereNull('ingresos')->first();
                $fauDs = ContPagoEfectivoFAU::orderBy('id', 'DESC')->whereNull('ingresos')->first();
                $fllDs = ContPagoEfectivoFLL::orderBy('id', 'DESC')->whereNull('ingresos')->first();
                $ftnBs = ContPagoBolivaresFTN::orderBy('id', 'DESC')->whereNull('ingresos')->first();
                $fauBs = ContPagoBolivaresFAU::orderBy('id', 'DESC')->whereNull('ingresos')->first();
                $fllBs = ContPagoBolivaresFLL::orderBy('id', 'DESC')->whereNull('ingresos')->first();

                $pago = $ftnDs;

                if (isset($fauDs->created_at) && $pago->created_at > $fauDs->created_at) {
                    $pago = $fauDs;
                }

                if (isset($fllDs->created_at) && $pago->created_at > $fllDs->created_at) {
                    $pago = $fllDs;
                }

                if (isset($ftnBs->created_at) && $pago->created_at > $ftnBs->created_at) {
                    $pago = $ftnBs;
                }

                if (isset($fauBs->created_at) && $pago->created_at > $fauBs->created_at) {
                    $pago = $fauBs;
                }

                if (isset($fllBs->created_at) && $pago->created_at > $fllBs->created_at) {
                    $pago = $fllBs;
                }

                $deuda = ContDeuda::orderBy('id', 'DESC')->first();

                $ftnDs = ContPagoEfectivoFTN::orderBy('fecha_conciliado', 'DESC')->whereNotNull('fecha_conciliado')->first();
                $fauDs = ContPagoEfectivoFAU::orderBy('fecha_conciliado', 'DESC')->whereNotNull('fecha_conciliado')->first();
                $fllDs = ContPagoEfectivoFLL::orderBy('fecha_conciliado', 'DESC')->whereNotNull('fecha_conciliado')->first();
                $ftnBs = ContPagoBolivaresFTN::orderBy('fecha_conciliado', 'DESC')->whereNotNull('fecha_conciliado')->first();
                $fauBs = ContPagoBolivaresFAU::orderBy('fecha_conciliado', 'DESC')->whereNotNull('fecha_conciliado')->first();
                $fllBs = ContPagoBolivaresFLL::orderBy('fecha_conciliado', 'DESC')->whereNotNull('fecha_conciliado')->first();

                $conciliacion = $ftnDs;

                if (isset($fauDs->fecha_conciliado) && $conciliacion->fecha_conciliado > $fauDs->fecha_conciliado) {
                    $conciliacion = $fauDs;
                }

                if (isset($fllDs->fecha_conciliado) && $conciliacion->fecha_conciliado > $fllDs->fecha_conciliado) {
                    $conciliacion = $fllDs;
                }

                if (isset($ftnBs->fecha_conciliado) && $conciliacion->fecha_conciliado > $ftnBs->fecha_conciliado) {
                    $conciliacion = $ftnBs;
                }

                if (isset($fauBs->fecha_conciliado) && $conciliacion->fecha_conciliado > $fauBs->fecha_conciliado) {
                    $conciliacion = $fauBs;
                }

                if (isset($fllBs->fecha_conciliado) && $conciliacion->fecha_conciliado > $fllBs->fecha_conciliado) {
                    $conciliacion = $fllBs;
                }

                return view('home-contabilidad', compact(
                    'pago',
                    'deuda',
                    'conciliacion'
                ));
            }

            if (Auth::user()->departamento == 'TESORERIA') {
                if(Auth()->user()->sede == 'FARMACIA TIERRA NEGRA, C.A.') {
                    $sede = 'FTN';
                }

                if(Auth()->user()->sede == 'FARMACIA LA LAGO,C.A.') {
                    $sede = 'FLL';
                }

                if(Auth()->user()->sede == 'FARMACIA AVENIDA UNIVERSIDAD, C.A.') {
                    $sede = 'FAU';
                }

                $saldoDolares = Configuracion::where('variable', 'SaldoEfectivo' . $sede)->first()->valor;
                $saldoBolivares = Configuracion::where('variable', 'SaldoBolivares' . $sede)->first()->valor;
                $diferidoDolares = Configuracion::where('variable', 'DiferidoEfectivo' . $sede)->first()->valor;
                $diferidoBolivares = Configuracion::where('variable', 'DiferidoBolivares' . $sede)->first()->valor;

                $ftnDs = ContPagoEfectivoFTN::orderBy('id', 'DESC')->first();
                $fauDs = ContPagoEfectivoFAU::orderBy('id', 'DESC')->first();
                $fllDs = ContPagoEfectivoFLL::orderBy('id', 'DESC')->first();
                $ftnBs = ContPagoBolivaresFTN::orderBy('id', 'DESC')->first();
                $fauBs = ContPagoBolivaresFAU::orderBy('id', 'DESC')->first();
                $fllBs = ContPagoBolivaresFLL::orderBy('id', 'DESC')->first();

                $pago = $ftnDs;

                if (isset($fauDs->created_at) && $pago->created_at > $fauDs->created_at) {
                    $pago = $fauDs;
                }

                if (isset($fllDs->created_at) && $pago->created_at > $fllDs->created_at) {
                    $pago = $fllDs;
                }

                if (isset($ftnBs->created_at) && $pago->created_at > $ftnBs->created_at) {
                    $pago = $ftnBs;
                }

                if (isset($fauBs->created_at) && $pago->created_at > $fauBs->created_at) {
                    $pago = $fauBs;
                }

                if (isset($fllBs->created_at) && $pago->created_at > $fllBs->created_at) {
                    $pago = $fllBs;
                }

                return view('home-contabilidad', compact(
                    'sede',
                    'saldoDolares',
                    'saldoBolivares',
                    'diferidoDolares',
                    'diferidoBolivares',
                    'pago'
                ));
            }
        }

        return view('home');
    }
}
