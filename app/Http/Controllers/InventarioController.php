<?php

namespace compras\Http\Controllers;

use Illuminate\Http\Request;
use compras\Inventario;
use compras\InventarioDetalle;
use compras\User;
use compras\Auditoria;

class InventarioController extends Controller
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
        $inventarios =  Inventario::all();
        return view('pages.inventario.index', compact('inventarios'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        include(app_path().'\functions\config.php');
        include(app_path().'\functions\functions.php');
        include(app_path().'\functions\querys_mysql.php');
        include(app_path().'\functions\querys_sqlserver.php');

        try{
            $conn = FG_Conectar_Smartpharma(FG_Mi_Ubicacion());
        
            $cont_articulo = 0;
            $cont_unidades = 0;

            /*INICIO DE ASIGNACION DE CODIGO*/
              $UltimaInventario = 
              Inventario::orderBy('id','desc')
              ->select('id')
              ->take(1)->get();

              if( (!empty($UltimaInventario[0])) &&
                  ((($UltimaInventario[0]['id'])!=0) 
                  || (($UltimaInventario[0]['id'])!=NULL)
                  || (($UltimaInventario[0]['id'])!=''))
                ) {
                $UltimoId = $UltimaInventario[0]['id'];
              }
              else{
                $UltimoId = 0;
              }
              $UltimoId++;
              $SiglasOrigen = FG_Mi_Ubicacion();
            /*FIN DE ASIGNACION DE CODIGO*/

            $inventario = new Inventario();
            $inventario->codigo = ''.$SiglasOrigen.''.$UltimoId;
            $inventario->origen_conteo = $request->input('origen');
            $inventario->motivo_conteo = $request->input('motivo');
            $inventario->estatus = 'GENERADO';
            $inventario->operador_generado = auth()->user()->name;
            $inventario->fecha_generado = date('y-m-d H:i:s');
            $inventario->save();
            
            $articulosContar = $request->input('articulosContar');
            print_r($articulosContar);
        
            foreach ($articulosContar as $articulo) {
                $sql = SQG_Detalle_Articulo($articulo);
                $result = sqlsrv_query($conn,$sql);
                $row = sqlsrv_fetch_array($result,SQLSRV_FETCH_ASSOC);                

                $inventario_detalle = new InventarioDetalle();
                $inventario_detalle->codigo_conteo = $inventario->codigo;
                $inventario_detalle->id_articulo = $row["IdArticulo"];
                $inventario_detalle->codigo_articulo = $row["CodigoInterno"];
                $inventario_detalle->codigo_barra = $row["CodigoBarra"];
                $inventario_detalle->descripcion = $row["Descripcion"];
                $inventario_detalle->existencia_actual = $row["Existencia"];
                $inventario_detalle->save();

                $cont_articulo++;
                $cont_unidades = $cont_unidades + $inventario_detalle->existencia_actual;
            } 

            $inventario->cantidades_conteo = $cont_articulo;
            $inventario->unidades_conteo = $cont_unidades;
            $inventario->save();

            sqlsrv_close($conn);

            return redirect()->route('inventario.index')->with('Saved', ' Informacion');
        }
        catch(\Illuminate\Database\QueryException $e){
            return back()->with('Error', ' Error');
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // No lo necesita se guarda desde el create y no se edita
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
        $inventarioDetalle =  InventarioDetalle::all();
        return view('pages.inventarioDetalle.index', compact('inventarioDetalle'));
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
