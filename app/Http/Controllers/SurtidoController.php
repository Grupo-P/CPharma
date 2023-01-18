<?php

namespace compras\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use compras\Surtido;
use compras\SurtidoDetalle;
use compras\Auditoria;

class SurtidoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $sql = "SELECT surtidos.id, surtidos.tipo_surtido, surtidos.control, surtidos.fecha_generado, surtidos.estatus, surtidos.operador_generado, surtidos.sku, surtidos.unidades, (SELECT surtido_detalles.descripcion FROM surtido_detalles WHERE surtido_detalles.control = surtidos.control ORDER BY descripcion ASC LIMIT 1) AS primero, (SELECT surtido_detalles.descripcion FROM surtido_detalles WHERE surtido_detalles.control = surtidos.control ORDER BY descripcion DESC LIMIT 1) AS ultimo FROM surtidos ";

        if (!isset($_GET['estatus']) || $_GET['estatus'] == 'TODO') {
            $sql = $sql . "ORDER BY id DESC";
        }

        if (isset($_GET['estatus']) && $_GET['estatus'] == 'GENERADO') {
            $sql = $sql . "WHERE status = 'GENERADO' ORDER BY id DESC";
        }

        if (isset($_GET['estatus']) && $_GET['estatus'] == 'PROCESADO') {
            $sql = $sql . "WHERE status = 'PROCESADO' ORDER BY id DESC";
        }

        if (isset($_GET['estatus']) && $_GET['estatus'] == 'ANULADO') {
            $sql = $sql . "WHERE status = 'ANULADO' ORDER BY id DESC";
        }

        $surtidos = DB::select($sql);

        return view('pages.surtido.index', compact('surtidos'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        include(app_path().'\functions\config.php');
        include(app_path().'\functions\functions.php');
        include(app_path().'\functions\querys_mysql.php');
        include(app_path().'\functions\querys_sqlserver.php');

        $ultimo_surtido = Surtido::orderBy('id', 'DESC')
            ->select('id')
            ->take(1)
            ->get();

        if( (!empty($ultimo_surtido[0])) &&
            ((($ultimo_surtido[0]['id'])!=0)
                || (($ultimo_surtido[0]['id'])!=NULL)
                || (($ultimo_surtido[0]['id'])!=''))) {
            $ultimo_id = $ultimo_surtido[0]['id'];
        } else{
            $ultimo_id = 0;
        }
        $ultimo_id++;
        $siglas = FG_Mi_Ubicacion();

        $surtido = new Surtido();
        $surtido->control = $siglas . $ultimo_id;
        $surtido->operador_generado = auth()->user()->name;
        $surtido->fecha_generado = date('Y-m-d H:i:s');
        $surtido->estatus = 'EN ESPERA';
        $surtido->save();

        return redirect()->route('surtido.edit', $surtido);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        include(app_path().'\functions\config.php');
        include(app_path().'\functions\functions.php');
        include(app_path().'\functions\querys_mysql.php');
        include(app_path().'\functions\querys_sqlserver.php');

        $surtido = Surtido::where('control', $request->control)->first();
        $surtido->operador_procesado = auth()->user()->name;
        $surtido->fecha_procesado = date('Y-m-d H:i:s');
        $surtido->estatus = 'GENERADO';
        $surtido->tipo_surtido = $request->tipo_surtido;
        $surtido->save();

        $auditoria = new Auditoria();
        $auditoria->accion = 'CREAR';
        $auditoria->tabla = 'SURTIDO';
        $auditoria->registro = $surtido->control;
        $auditoria->user = auth()->user()->name;
        $auditoria->save();

        $request->session()->flash('Saved', 'Informacion');
    }

    public function agregarArticulo(Request $request)
    {
        $surtido = Surtido::where('control', $request->control)->first();

        $detalle = new SurtidoDetalle();
        $detalle->control = $surtido->control;
        $detalle->id_articulo = $request->id_articulo;
        $detalle->codigo_articulo = $request->codigo_articulo;
        $detalle->codigo_barra = $request->codigo_barra;
        $detalle->descripcion = $request->descripcion;
        $detalle->existencia_actual = $request->existencia_actual;
        $detalle->cantidad = $request->cantidad;
        $detalle->save();

        $detalle = SurtidoDetalle::where('control', $request->control)->get();

        $surtido->sku = $detalle->count();
        $surtido->unidades = array_sum(array_column($detalle->toArray(), 'cantidad'));
        $surtido->save();
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        include app_path() . '/functions/functions.php';

        $surtido = Surtido::find($id);
        $detalles = SurtidoDetalle::where('control', $surtido->control)->orderBy('descripcion', 'ASC')->get();
        $sede = FG_Mi_Ubicacion();

        $auditoria = new Auditoria();
        $auditoria->accion = 'CONSULTAR';
        $auditoria->tabla = 'SURTIDO';
        $auditoria->registro = $surtido->control;
        $auditoria->user = auth()->user()->name;
        $auditoria->save();

        return view('pages.surtido.show', compact('surtido', 'sede', 'detalles'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Surtido $surtido)
    {
        include(app_path().'\functions\config.php');
        include(app_path().'\functions\functions.php');
        include(app_path().'\functions\querys_mysql.php');
        include(app_path().'\functions\querys_sqlserver.php');

        if (isset($_GET['Id'])) {
            $conn = FG_Conectar_Smartpharma($_GET['SEDE']);

            $sql = Detalle_Articulo($_GET['Id']);
            $result = sqlsrv_query($conn,$sql);
            $row = sqlsrv_fetch_array($result,SQLSRV_FETCH_ASSOC);

            $result = [];

            $result['Descripcion'] = \FG_Limpiar_Texto($row['Descripcion']);
            $result['CodigoBarra'] = $row['CodigoBarra'];
            $result['CodigoInterno'] = $row['CodigoInterno'];
            $result['IdArticulo'] = $row['IdArticulo'];
            $result['Existencia'] = ($row['Existencia']) ? $row['Existencia'] : 0;

            return $result;
        }

        $ArtJson = "";
        $CodJson = "";
        $CodIntJson = "";

        $_GET['SEDE'] = FG_Mi_Ubicacion();

        $sql1 = "
            SELECT
                InvArticulo.Descripcion,
                InvArticulo.Id
            FROM InvArticulo
            ORDER BY InvArticulo.Descripcion ASC
        ";

        $ArtJson = FG_Armar_Json($sql1,$_GET['SEDE']);

        $sql2 = "
            SELECT
                (SELECT CodigoBarra FROM InvCodigoBarra WHERE InvCodigoBarra.InvArticuloId = InvArticulo.Id AND InvCodigoBarra.EsPrincipal = 1) AS CodigoBarra,
                InvArticulo.Id
            FROM InvArticulo
            ORDER BY CodigoBarra ASC
        ";
        $CodJson = FG_Armar_Json($sql2,$_GET['SEDE']);

        $sql3 = "
            SELECT
                InvArticulo.CodigoArticulo,
                InvArticulo.Id
            FROM InvArticulo
            ORDER BY InvArticulo.CodigoArticulo ASC
        ";

        $CodIntJson = FG_Armar_Json($sql3,$_GET['SEDE']);

        return view('pages.surtido.create', compact('ArtJson', 'CodJson', 'CodIntJson', 'surtido'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function anular(Request $request, $id)
    {
        if ($request->isMethod('post')) {
            $surtido = Surtido::find($id);
            $surtido->operador_anulado = auth()->user()->name;
            $surtido->fecha_anulado = date('Y-m-d H:i:s');
            $surtido->motivo_anulado = $request->motivo_anulado;
            $surtido->estatus = 'ANULADO';
            $surtido->save();

            $auditoria = new Auditoria();
            $auditoria->accion = 'ANULADO';
            $auditoria->tabla = 'SURTIDO';
            $auditoria->registro = $surtido->control;
            $auditoria->user = auth()->user()->name;
            $auditoria->save();

            return redirect()->route('surtido.index')->with('Updated', ' Informacion');            
        }

        $surtido = Surtido::find($id);

        return view('pages.surtido.edit', compact('surtido'));
    }

    public function eliminar(Request $request)
    {
        SurtidoDetalle::where('control', $request->control)
            ->where('id_articulo', $request->id_articulo)
            ->delete();

        $detalle = SurtidoDetalle::where('control', $request->control)->get();

        $surtido = Surtido::where('control', $request->control)->first();
        $surtido->sku = $detalle->count();
        $surtido->unidades = array_sum(array_column($detalle->toArray(), 'cantidad'));
        $surtido->save();
    }
}
