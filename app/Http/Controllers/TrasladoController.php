<?php

namespace compras\Http\Controllers;

use Illuminate\Http\Request;
use compras\Auditoria;
use compras\Traslado;
use compras\Sede;
use compras\User;

class TrasladoController extends Controller
{
    /**
     * Create a new controller instance with auth.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $traslados =  Traslado::all();
        return view('pages.traslado.index', compact('traslados'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {   
       $sedes = Sede::pluck('razon_social','id');
       return view('pages.traslado.create', compact('sedes'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {   
        try{
            include(app_path().'\functions\config.php');
            include(app_path().'\functions\functions.php');
            include(app_path().'\functions\querys_mysql.php');
            include(app_path().'\functions\querys_sqlserver.php');

            $traslado = new Traslado();
            $IdAjuste = $request->input('IdAjuste'); 
        /*INICIO ENCABEZADO DEL TRASLADO*/
            $traslado->numero_ajuste = $request->input('numero_ajuste');
            $traslado->fecha_ajuste = $request->input('fecha_ajuste');
            $traslado->operador_ajuste = $request->input('operador_ajuste');
            $traslado->fecha_traslado = $request->input('fecha_traslado');
            $traslado->operador_traslado = $request->input('operador_traslado');
            $traslado->sede_emisora = $request->input('sede_emisora');
            $traslado->sede_destino = $request->input('sede_destino');
            $traslado->sede_destino = 'PROCESADO';
            //$traslado->save();
        /*FIN ENCABEZADO DEL TRASLADO*/
        /*INICIO DETALLE DEL TRASLADO*/
            $SedeConnection = $request->input('SEDE');
            $conn = FG_Conectar_Smartpharma($SedeConnection);

            $sql = QG_Articulos_Ajuste($IdAjuste);
            $result = sqlsrv_query($conn,$sql);
            $row = sqlsrv_fetch_array($result,SQLSRV_FETCH_ASSOC);

            while($row = sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC)) {
                $IdArticulo = $row["InvArticuloId"];

                $sql1 = QG_Detalle_Articulo($IdArticulo);
                $result1 = sqlsrv_query($conn,$sql1);
                $row1 = sqlsrv_fetch_array($result1,SQLSRV_FETCH_ASSOC);

                $CodigoArticulo = $row1["CodigoInterno"];
                $CodigoBarra = $row1["CodigoBarra"];
                $Descripcion = FG_Limpiar_Texto($row["Descripcion"]);
                $Cantidad = $row["Cantidad"];
                $Dolarizado = $row["Dolarizado"];
                $IsIVA = $row["Impuesto"];
                $Utilidad = $row["Utilidad"];
                $TroquelAlmacen1 = $row["TroquelAlmacen1"];
                $TroquelAlmacen2 = $row["TroquelAlmacen2"];
                $PrecioCompraBruto = $row["PrecioCompraBruto"];
                
                $Gravado = FG_Producto_Gravado($IsIVA);
                $Dolarizado = FG_Producto_Dolarizado($Dolarizado);
            }

        /*FIN DETALLE DEL TRASLADO*/
            

            /*$Auditoria = new Auditoria();
            $Auditoria->accion = 'CREAR';
            $Auditoria->tabla = 'TASA MERCADO';
            $Auditoria->registro = $request->input('tasa');
            $Auditoria->user = auth()->user()->name;
            $Auditoria->save();*/

            //return redirect()->route('dolar.index')->with('Saved', ' Informacion');
        }
        catch(\Illuminate\Database\QueryException $e){
            return back()->with('Error', ' Error');
        }
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
