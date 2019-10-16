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
            $traslado->estatus = 'PROCESADO';
        /*FIN ENCABEZADO DEL TRASLADO*/
        /*INICIO DETALLE DEL TRASLADO*/
            $SedeConnection = $request->input('SEDE');
            $conn = FG_Conectar_Smartpharma($SedeConnection);
            $connCPharma = FG_Conectar_CPharma();

            $sql = QG_Articulos_Ajuste($IdAjuste);
            $result = sqlsrv_query($conn,$sql);
            $row = sqlsrv_fetch_array($result,SQLSRV_FETCH_ASSOC);

            while($row = sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC)) {
              $IdArticulo = $row["InvArticuloId"];
              $Cantidad = $row["Cantidad"];
              $IdTraslado = $request->input('numero_ajuste');

              $sql1 = QG_Detalle_Articulo($IdArticulo);
              $result1 = sqlsrv_query($conn,$sql1);
              $row1 = sqlsrv_fetch_array($result1,SQLSRV_FETCH_ASSOC);

              $CodigoArticulo = $row1["CodigoInterno"];
              $CodigoBarra = $row1["CodigoBarra"];
              $Descripcion = FG_Limpiar_Texto($row1["Descripcion"]);
              $Existencia = $row1["Existencia"];
              $Dolarizado = $row1["Dolarizado"];
              $IsIVA = $row1["Impuesto"];
              $Utilidad = $row1["Utilidad"];
              $TroquelAlmacen1 = $row1["TroquelAlmacen1"];
              $TroquelAlmacen2 = $row1["TroquelAlmacen2"];
              $PrecioCompraBruto = $row1["PrecioCompraBruto"];
              $Gravado = FG_Producto_Gravado($IsIVA);
              $Dolarizado = FG_Producto_Dolarizado($Dolarizado);
              
              if($Dolarizado=='SI') {
                $TasaActual = FG_Tasa_Fecha($connCPharma,date('Y-m-d'));
                $Precio = FG_Calculo_Precio($Existencia,$TroquelAlmacen1,$PrecioCompraBruto,$Utilidad,$IsIVA,$TroquelAlmacen2);

                if($Gravado=='SI' && $Utilidad!= 1){
                  $costo_unit_bs_sin_iva = ($Precio/Impuesto)*$Utilidad;
                  $costo_unit_usd_sin_iva = ($costo_unit_bs_sin_iva/$TasaActual);
                  $total_imp_usd = ($costo_unit_usd_sin_iva*$Cantidad)*(1-Impuesto);
                }
                else if($Gravado== 'NO' && $Utilidad!= 1){
                  $costo_unit_bs_sin_iva = ($Precio)*$Utilidad;
                  $costo_unit_usd_sin_iva = ($costo_unit_bs_sin_iva/$TasaActual);
                  $total_imp_usd = 0.00;
                }
                $total_usd = ($costo_unit_usd_sin_iva*$Cantidad)+$total_imp_usd;
                $costo_unit_bs_sin_iva = '-';
                $total_imp_bs = '-';
                $total_bs = '-';
              }
              else if($Dolarizado=='NO') {
                $costo_unit_bs_sin_iva = $PrecioCompraBruto;

                if($Gravado== 'SI' && $Utilidad!= 1){
                  $total_imp_bs = ($costo_unit_bs_sin_iva*$Cantidad)*(1-Impuesto);
                }
                else if($Gravado== 'NO' && $Utilidad!= 1){
                  $total_imp_bs = 0.00; 
                }
                $total_bs = ($costo_unit_bs_sin_iva*$Cantidad)+$total_imp_bs;
                $costo_unit_usd_sin_iva = '-';
                $total_imp_usd = '-';
                $total_usd = '-';
              }
              $traslado->save();

              $date = new DateTime('now');
              $date = $date->format("Y-m-d H:i:s");

              FG_Guardar_Traslado_Detalle($connCPharma,$IdTraslado,$IdArticulo,$CodigoArticulo,$CodigoBarra,$Descripcion,$Gravado,$Dolarizado,$Cantidad,$costo_unit_bs_sin_iva,$costo_unit_usd_sin_iva,$total_imp_bs,$total_imp_usd,$total_bs,$total_usd,$date);
            }
        /*FIN DETALLE DEL TRASLADO*/
            $Auditoria = new Auditoria();
            $Auditoria->accion = 'CREAR';
            $Auditoria->tabla = 'TRASLADO';
            $Auditoria->registro = $request->input('numero_ajuste');
            $Auditoria->user = auth()->user()->name;
            $Auditoria->save();

            return redirect()->route('traslado.index')->with('Saved', ' Informacion');
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
