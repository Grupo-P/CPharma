<?php

namespace compras\Http\Controllers;

use compras\Auditoria;
use compras\Configuracion;
use compras\ContBanco;
use compras\ContPagoBancario;
use compras\ContProveedor;
use compras\Mail\NotificarPagoProveedor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use PDF;

class ContPagoBancarioController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $pagos = ContPagoBancario::orderByDesc('id')->get();
        return view('pages.contabilidad.bancarios.index', compact('pagos'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        if ($request->ajax()) {
            if ($request->proveedor == 1) {
                $proveedores          = ContProveedor::find($request->id_proveedor);
                $proveedores['saldo'] = number_format($proveedores->saldo, 2, ',', '.');
                return $proveedores;
            }

            if ($request->conversion == 1) {
                if ($request->banco != $request->proveedor) {
                    if ($request->banco == 'Dólares' && $request->proveedor == 'Bolívares') {
                        $monto = $request->monto / $request->tasa;
                    }

                    if ($request->banco == 'Dólares' && $request->proveedor == 'Pesos') {
                        $monto = $request->monto / $request->tasa;
                    }

                    if ($request->banco == 'Bolívares' && $request->proveedor == 'Dólares') {
                        $monto = $request->monto * $request->tasa;
                    }

                    if ($request->banco == 'Bolívares' && $request->proveedor == 'Pesos') {
                        $monto = $request->monto / $request->tasa;
                    }

                    if ($request->banco == 'Pesos' && $request->proveedor == 'Bolívares') {
                        $monto = $request->monto * $request->tasa;
                    }

                    if ($request->banco == 'Pesos' && $request->proveedor == 'Dólares') {
                        $monto = $request->monto * $request->tasa;
                    }
                } else {
                    $monto = $request->monto;
                }

                return $monto;
            }
        }

        $sqlProveedores = ContProveedor::whereNull('deleted_at')->orderBy('nombre_proveedor', 'ASC')->get();
        $i              = 0;
        $proveedores    = [];

        foreach ($sqlProveedores as $proveedor) {
            $proveedores[$i]['label']  = $proveedor->nombre_proveedor . ' | ' . $proveedor->rif_ci;
            $proveedores[$i]['value']  = $proveedor->nombre_proveedor . ' | ' . $proveedor->rif_ci;
            $proveedores[$i]['id']     = $proveedor->id;
            $proveedores[$i]['moneda'] = $proveedor->moneda;
            $proveedores[$i]['tasa']   = $proveedor->tasa;
            $proveedores[$i]['saldo']  = number_format($proveedor->saldo, 2, ',', '');

            $i = $i + 1;
        }

        $bancos = ContBanco::whereNull('deleted_at')->orderBy('alias_cuenta')->get();

        return view('pages.contabilidad.bancarios.create', compact('bancos', 'proveedores'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if ($request->prepagado == 'Si') {
            $monto_proveedor = str_replace('.', '', $request->input('monto_proveedor'));
            $monto_proveedor = str_replace(',', '.', $monto_proveedor);

            $pago               = new ContPagoBancario();
            $pago->id_proveedor = $request->input('id_proveedor');
            $pago->monto        = $monto_proveedor;
            $pago->estatus      = 'Prepagado';
            $pago->operador     = auth()->user()->name;
            $pago->save();

            return redirect('/bancarios')->with('Saved', ' Informacion');
        }

        $monto = str_replace('.', '', $request->input('monto'));
        $monto = str_replace(',', '.', $monto);

        $pago                  = new ContPagoBancario();
        $pago->id_proveedor    = $request->input('id_proveedor');
        $pago->id_banco        = $request->input('id_banco');
        $pago->monto           = $monto;
        $pago->monto_proveedor = $request->input('monto_proveedor');
        $pago->comentario      = $request->input('comentario');
        $pago->tasa            = $request->input('tasa');

        $pago->operador = auth()->user()->name;
        $pago->estatus  = 'Pagado';
        $pago->save();

        $banco = ContBanco::find($pago->id_banco);

        $proveedor = ContProveedor::find($pago->id_proveedor);

        if ($banco->moneda != $proveedor->moneda) {
            if ($banco->moneda == 'Dólares' && $proveedor->moneda == 'Bolívares') {
                $monto = $pago->monto * $pago->tasa;
            }

            if ($banco->moneda == 'Dólares' && $proveedor->moneda == 'Pesos') {
                $monto = $pago->monto * $pago->tasa;
            }

            if ($banco->moneda == 'Bolívares' && $proveedor->moneda == 'Dólares') {
                $monto = $pago->monto / $pago->tasa;
            }

            if ($banco->moneda == 'Bolívares' && $proveedor->moneda == 'Pesos') {
                $monto = $pago->monto * $pago->tasa;
            }

            if ($banco->moneda == 'Pesos' && $proveedor->moneda == 'Bolívares') {
                $monto = $pago->monto / $pago->tasa;
            }

            if ($banco->moneda == 'Pesos' && $proveedor->moneda == 'Dólares') {
                $monto = $pago->monto / $pago->tasa;
            }
        } else {
            $monto = $pago->monto;
        }

        $proveedor->saldo = (float) $proveedor->saldo - (float) $monto;
        $proveedor->save();

        $auditoria           = new Auditoria();
        $auditoria->accion   = 'CREAR';
        $auditoria->tabla    = 'PAGO BANCARIO';
        $auditoria->registro = $pago->id;
        $auditoria->user     = auth()->user()->name;
        $auditoria->save();

        return redirect('/bancarios')->with('Saved', ' Informacion');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $pago = ContPagoBancario::find($id);
        return view('pages.contabilidad.bancarios.show', compact('pago'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $pago = ContPagoBancario::find($id);

        $bancos = ContBanco::whereNull('deleted_at')->orderBy('alias_cuenta')->get();

        return view('pages.contabilidad.bancarios.edit', compact('bancos', 'pago'));
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
        if ($request->prepagado == 'Si') {
            $monto_proveedor = str_replace('.', '', $request->input('monto_proveedor'));
            $monto_proveedor = str_replace(',', '.', $monto_proveedor);

            $pago               = ContPagoBancario::find($id);
            $pago->id_proveedor = $request->input('id_proveedor');
            $pago->monto        = $monto_proveedor;
            $pago->estatus      = 'Prepagado';
            $pago->operador     = auth()->user()->name;
            $pago->save();

            return redirect('/bancarios')->with('Saved', ' Informacion');
        }

        $monto = str_replace('.', '', $request->input('monto'));
        $monto = str_replace(',', '.', $monto);

        $pago               = ContPagoBancario::find($id);
        $pago->id_proveedor = $request->input('id_proveedor');
        $pago->id_banco     = $request->input('id_banco');
        $pago->monto        = $monto;
        $pago->comentario   = $request->input('comentario');
        $pago->tasa         = $request->input('tasa');

        $pago->operador = auth()->user()->name;
        $pago->estatus  = 'Pagado';
        $pago->save();

        $banco = ContBanco::find($pago->id_banco);

        $proveedor = ContProveedor::find($pago->id_proveedor);

        if ($banco->moneda != $proveedor->moneda) {
            if ($banco->moneda == 'Dólares' && $proveedor->moneda == 'Bolívares') {
                $monto = $pago->monto * $pago->tasa;
            }

            if ($banco->moneda == 'Dólares' && $proveedor->moneda == 'Pesos') {
                $monto = $pago->monto * $pago->tasa;
            }

            if ($banco->moneda == 'Bolívares' && $proveedor->moneda == 'Dólares') {
                $monto = $pago->monto / $pago->tasa;
            }

            if ($banco->moneda == 'Bolívares' && $proveedor->moneda == 'Pesos') {
                $monto = $pago->monto * $pago->tasa;
            }

            if ($banco->moneda == 'Pesos' && $proveedor->moneda == 'Bolívares') {
                $monto = $pago->monto / $pago->tasa;
            }

            if ($banco->moneda == 'Pesos' && $proveedor->moneda == 'Dólares') {
                $monto = $pago->monto / $pago->tasa;
            }
        } else {
            $monto = $pago->monto;
        }

        $proveedor->saldo = (float) $proveedor->saldo - (float) $monto;
        $proveedor->save();

        $auditoria           = new Auditoria();
        $auditoria->accion   = 'EDITAR';
        $auditoria->tabla    = 'PAGO BANCARIO';
        $auditoria->registro = $pago->id;
        $auditoria->user     = auth()->user()->name;
        $auditoria->save();

        return redirect('/bancarios')->with('Updated', ' Informacion');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $pago          = ContPagoBancario::find($id);
        $pago->estatus = 'Reversado';
        $pago->save();

        $banco     = ContBanco::find($pago->id_banco);
        $proveedor = ContProveedor::find($pago->id_proveedor);

        if ($banco->moneda != $proveedor->moneda) {
            if ($banco->moneda == 'Dólares' && $proveedor->moneda == 'Bolívares') {
                $monto_proveedor = $pago->monto * $pago->tasa;
            }

            if ($banco->moneda == 'Dólares' && $proveedor->moneda == 'Pesos') {
                $monto_proveedor = $pago->monto * $pago->tasa;
            }

            if ($banco->moneda == 'Bolívares' && $proveedor->moneda == 'Dólares') {
                $monto_proveedor = $pago->monto / $pago->tasa;
            }

            if ($banco->moneda == 'Bolívares' && $proveedor->moneda == 'Pesos') {
                $monto_proveedor = $pago->monto * $pago->tasa;
            }

            if ($banco->moneda == 'Pesos' && $proveedor->moneda == 'Bolívares') {
                $monto_proveedor = $pago->monto / $pago->tasa;
            }

            if ($banco->moneda == 'Pesos' && $proveedor->moneda == 'Dólares') {
                $monto_proveedor = $pago->monto / $pago->tasa;
            }
        } else {
            $monto_proveedor = $pago->monto;
        }

        $monto_proveedor = ($monto_proveedor > 0) ? -$monto_proveedor : abs($monto_proveedor);
        $monto           = ($pago->monto > 0) ? -$pago->monto : abs($pago->monto);

        $nuevoPago               = new ContPagoBancario();
        $nuevoPago->id_proveedor = $pago->id_proveedor;
        $nuevoPago->id_banco     = $pago->id_banco;
        $nuevoPago->tasa         = $pago->tasa;
        $nuevoPago->monto        = $monto;
        $nuevoPago->comentario   = 'Reverso del pago bancario #' . $pago->id;
        $nuevoPago->operador     = $pago->operador;
        $nuevoPago->estatus      = 'Reversado';
        $nuevoPago->save();

        $proveedor        = ContProveedor::find($pago->id_proveedor);
        $proveedor->saldo = (float) $proveedor->saldo - (float) $monto_proveedor;
        $proveedor->save();

        return redirect('/bancarios')->with('Deleted', ' Informacion');
    }

    public function soporte($id)
    {
        $pago = ContPagoBancario::find($id);

        return view('pages.contabilidad.bancarios.soporte', compact('pago'));
    }

    public function notificar($id)
    {
        $pago = ContPagoBancario::find($id);

        $filename = storage_path('app/public/') . 'pago-' . $pago->id . '.pdf';

        $pdf = PDF::loadView('pages.contabilidad.bancarios.pdf', compact('pago'))->save($filename);

        $emails = explode(',', $pago->proveedor->correo_electronico);

        foreach ($emails as $email) {
            Mail::to($email)->send(new NotificarPagoProveedor($pago, $filename));
        }

        return redirect('/bancarios')->with('Send', ' Informacion');
    }

    public function validar(Request $request)
    {
        $banco     = ContBanco::find($request->id_banco);
        $proveedor = ContProveedor::find($request->id_proveedor);
        $resultado = [];

        if ($banco->moneda != $proveedor->moneda) {
            if ($banco->moneda == 'Dólares' && $proveedor->moneda == 'Bolívares') {
                $configuracion = Configuracion::where('variable', 'DolaresBolivares')->first();
            }

            if ($banco->moneda == 'Dólares' && $proveedor->moneda == 'Pesos') {
                $configuracion = Configuracion::where('variable', 'DolaresPesos')->first();
            }

            if ($banco->moneda == 'Bolívares' && $proveedor->moneda == 'Dólares') {
                $configuracion = Configuracion::where('variable', 'BolivaresDolares')->first();
            }

            if ($banco->moneda == 'Bolívares' && $proveedor->moneda == 'Pesos') {
                $configuracion = Configuracion::where('variable', 'BolivaresPesos')->first();
            }

            if ($banco->moneda == 'Pesos' && $proveedor->moneda == 'Bolívares') {
                $configuracion = Configuracion::where('variable', 'PesosBolivares')->first();
            }

            if ($banco->moneda == 'Pesos' && $proveedor->moneda == 'Dólares') {
                $configuracion = Configuracion::where('variable', 'PesosDolares')->first();
            }

            $resultado['min'] = $configuracion->valor - ($configuracion->valor * 0.20);
            $resultado['max'] = $configuracion->valor + ($configuracion->valor * 0.20);

            return $resultado;
        }
    }
}
