<?php

namespace compras\Http\Controllers;

use Auth;
use compras\Configuracion;
use compras\ContDeuda;
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

            if (Auth::user()->departamento == 'ADMINISTRACION') {
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

                $ftn = ContPagoEfectivoFTN::orderBy('id', 'DESC')->first();
                $fau = ContPagoEfectivoFAU::orderBy('id', 'DESC')->first();
                $fll = ContPagoEfectivoFLL::orderBy('id', 'DESC')->first();

                $pago = $ftn;

                if (isset($fau->created_at) && $pago->created_at > $fau->created_at) {
                    $pago = $fau;
                }

                if (isset($fll->created_at) && $pago->created_at > $fll->created_at) {
                    $pago = $fll;
                }

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
                    'pago'
                ));
            }
        }

        return view('home');
    }
}
