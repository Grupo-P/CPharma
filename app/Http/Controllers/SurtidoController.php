<?php

namespace compras\Http\Controllers;

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
        if (!isset($_GET['estatus']) || $_GET['estatus'] == 'TODO') {
            $surtidos = Surtido::orderBy('id', 'DESC')->get();
        }

        if (isset($_GET['estatus']) && $_GET['estatus'] == 'GENERADO') {
            $surtidos = Surtido::where('estatus', 'GENERADO')->orderBy('id', 'DESC')->get();
        }

        if (isset($_GET['estatus']) && $_GET['estatus'] == 'PROCESADO') {
            $surtidos = Surtido::where('estatus', 'PROCESADO')->orderBy('id', 'DESC')->get();
        }

        if (isset($_GET['estatus']) && $_GET['estatus'] == 'ANULADO') {
            $surtidos = Surtido::where('estatus', 'ANULADO')->orderBy('id', 'DESC')->get();
        }

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
        $surtido->sku = count($request->articulos);
        $surtido->unidades = array_sum(array_column($request->articulos, 'cantidad'));
        $surtido->operador_generado = auth()->user()->name;
        $surtido->operador_procesado = auth()->user()->name;
        $surtido->fecha_generado = date('Y-m-d H:i:s');
        $surtido->fecha_procesado = date('Y-m-d H:i:s');
        $surtido->estatus = 'GENERADO';
        $surtido->save();

        foreach ($request->articulos as $articulo) {
            $detalle = new SurtidoDetalle();
            $detalle->control = $surtido->control;
            $detalle->id_articulo = $articulo['id_articulo'];
            $detalle->codigo_articulo = $articulo['codigo_articulo'];
            $detalle->codigo_barra = $articulo['codigo_barra'];
            $detalle->descripcion = $articulo['descripcion'];
            $detalle->existencia_actual = $articulo['existencia_actual'];
            $detalle->cantidad = $articulo['cantidad'];
            $detalle->save();
        }

        $auditoria = new Auditoria();
        $auditoria->accion = 'CREAR';
        $auditoria->tabla = 'SURTIDO';
        $auditoria->registro = $surtido->control;
        $auditoria->user = auth()->user()->name;
        $auditoria->save();

        $request->session()->flash('Saved', 'Informacion');
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
        $detalles = SurtidoDetalle::where('control', $surtido->control)->get();
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
        return view('pages.surtido.edit', compact('surtido'));
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
}
