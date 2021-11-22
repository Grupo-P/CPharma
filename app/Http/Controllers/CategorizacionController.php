<?php

namespace compras\Http\Controllers;

use Illuminate\Http\Request;
use compras\Categorizacion;
use compras\User;
use compras\Auditoria;
use compras\Categoria;
use compras\Subcategoria;
use compras\Configuracion;

class CategorizacionController extends Controller
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
    public function index(Request $request)
    {
        if(isset($_GET['Tipo'])){
            $tipo = $_GET['Tipo'];
        }
        else{
            $tipo = 0;
        }

        switch ($tipo) {
            case 0:
                $categorizaciones = Categorizacion::orderBy('id', 'asc')
                    ->where('codigo_categoria', '1')
                    ->busqueda($request->get('clave_busqueda'), $request->get('valor_busqueda'))
                    ->paginate(50);

                return view('pages.categorizacion.index', compact('categorizaciones','tipo'));
          break;
          case 1:
                $categorizaciones = Categorizacion::orderBy('id', 'asc')
                    ->where('codigo_categoria','!=', '1')
                    ->busqueda($request->get('clave_busqueda'), $request->get('valor_busqueda'))
                    ->paginate(50);

                return view('pages.categorizacion.index', compact('categorizaciones','tipo'));
          break;
        }
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
        $tipo = 0;
        try{

            $articulosCategorizar = $request->input('articulosCategorizar');

            if(isset($articulosCategorizar)){
                foreach ($articulosCategorizar as $articulo) {

                    $partes = explode("/",$articulo);

                    $categorizacion = Categorizacion::find($partes[0]);
                    $categorizacion->codigo_categoria = $partes[1];
                    $categorizacion->codigo_subcategoria = $partes[2];
                    $categorizacion->save();

                    $Auditoria = new Auditoria();
                    $Auditoria->accion = 'CATEGORIZAR';
                    $Auditoria->tabla = 'CATEGORIZACION';
                    $Auditoria->registro = $articulo;
                    $Auditoria->user = auth()->user()->name;
                    $Auditoria->save();
                }

                return redirect()->back();
            }
            else{
                return redirect()->back();
            }
        }
        catch(\Illuminate\Database\QueryException $e){
            return redirect()->back();
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

    public function syncategorias(){
        include(app_path().'\functions\config.php');
        include(app_path().'\functions\functions.php');
        include(app_path().'\functions\querys_mysql.php');
        include(app_path().'\functions\querys_sqlserver.php');

        $SedeConnection = FG_Mi_Ubicacion();
        $conn = FG_Conectar_Smartpharma($SedeConnection);

        $configuracion =  Configuracion::select('valor')->where('variable','URL_interna')->get();
        $path = "".$configuracion[0]->valor;
        $filename = "categorizacions.json";
        $file = $path.'/'.$filename;

        $arrNuevos = $arrActualizados = $arrIguales =  $arrErrores = array();

        if(file_exists($file)){
            $dataFile = @file_get_contents($file);
            $dataArray = json_decode($dataFile, true);
            $dataCategories = $dataArray[2]["data"];

            foreach ($dataCategories as $dataCategory){

                $categorizacion = Categorizacion::orderBy('id', 'asc')
                    ->where('codigo_barra', $dataCategory["codigo_barra"])
                    ->get();

                if(empty($categorizacion[0]->codigo_barra)) {
                    //echo "<br><br>No existe en categorizacion";

                    $sql = SQL_articulo_codigoBarra($dataCategory["codigo_barra"]);
                    $result = sqlsrv_query($conn,$sql);
                    $row = sqlsrv_fetch_array($result,SQLSRV_FETCH_ASSOC);

                    if(!empty($row)) {
                        //echo "<br><br>Si Existe en el smart";

                        $categorizacion = new Categorizacion();
                        $categorizacion->id_articulo = $row["id_articulo"];
                        $categorizacion->codigo_interno = $row["codigo_interno"];
                        $categorizacion->codigo_barra = $row["codigo_barra"];
                        $categorizacion->descripcion = $row["descripcion"];
                        $categorizacion->marca = $row["marca"];
                        $categorizacion->codigo_categoria = $dataCategory["codigo_categoria"];
                        $categorizacion->codigo_subcategoria = $dataCategory["codigo_subcategoria"];
                        $categorizacion->estatus = "ACTIVO";
                        $categorizacion->user = "SYSTEM";
                        $categorizacion->save();

                        $nuevo = array(
                            "codigo_barra"=>$dataCategory["codigo_barra"],
                            "Categoria" => $dataCategory["codigo_categoria"],
                            "Subcategoria" => $dataCategory["codigo_subcategoria"],
                            "Mensaje" => "Categoriacion creada con exito"
                        );
                        array_push($arrNuevos,$nuevo);
                    }
                    else if(empty($row)) {
                        //echo "<br><br>No existe en el smart";
                        $error = array(
                            "codigo_barra"=>$dataCategory["codigo_barra"],
                            "Mensaje" => "No existe en el smart"
                        );
                        array_push($arrErrores,$error);
                    }

                }
                else if(!empty($categorizacion[0]->codigo_barra)) {
                    //echo "<br><br>Si Existe en categorizacion";

                    if(
                        ($categorizacion[0]->codigo_categoria != $dataCategory["codigo_categoria"])||
                        ($categorizacion[0]->codigo_subcategoria != $dataCategory["codigo_subcategoria"])
                    ){
                        //echo "<br><br>Categoria o Subcategoria diferentes";
                        //Si las categorias y subcategorias del json son diferentes a sin categorizacion
                        if($dataCategory["codigo_categoria"]!="1" && $dataCategory["codigo_subcategoria"]!="1.1"){
                            $categorizacion[0]->codigo_categoria = $dataCategory["codigo_categoria"];
                            $categorizacion[0]->codigo_subcategoria = $dataCategory["codigo_subcategoria"];
                            $categorizacion[0]->estatus = "ACTIVO";
                            $categorizacion[0]->user = "SYSTEM";
                            $categorizacion[0]->save();

                            $actualizado = array(
                                "codigo_barra"=>$dataCategory["codigo_barra"],
                                "Categoria" => $dataCategory["codigo_categoria"],
                                "Subcategoria" => $dataCategory["codigo_subcategoria"],
                                "Mensaje" => "Categoriacion creada con exito"
                            );
                            array_push($arrActualizados,$actualizado);
                        }
                    }
                    elseif(
                        ($categorizacion[0]->codigo_categoria == $dataCategory["codigo_categoria"])&&
                        ($categorizacion[0]->codigo_subcategoria == $dataCategory["codigo_subcategoria"])
                    ){
                        //echo "<br><br>Categoria o Subcategoria iguales";
                        $igual = array(
                            "codigo_barra"=>$dataCategory["codigo_barra"],
                            "Mensaje" => "Categorizacion igual a la existente"
                        );
                        array_push($arrIguales,$igual);
                    }
                }
            }

            echo"<pre>";
            echo"<br>* * * * * * * * * * Resumen * * * * * * * * * *<br>";
            echo"<br>* * * * * * * * * * Evaluados ".count($dataCategories)." * * * * * * * * * *<br>";
            echo"<br>* * * * * * * * * * Creados ".count($arrNuevos)." * * * * * * * * * *<br>";
            echo"<br>* * * * * * * * * * Actualizados ".count($arrActualizados)." * * * * * * * * * *<br>";
            echo"<br>* * * * * * * * * * Iguales ".count($arrIguales)." * * * * * * * * * *<br>";
            echo"<br>* * * * * * * * * * Errores ".count($arrErrores)." * * * * * * * * * *<br>";

            echo"<br>* * * * * * * * * * Detallado * * * * * * * * * *<br>";

            echo"<br>* * * * * * * * * * Creados ".count($arrNuevos)." * * * * * * * * * *<br>";
            print_r($arrNuevos);

            echo"<br><br>* * * * * * * * * * Actualizados ".count($arrActualizados)." * * * * * * * * * *<br>";
            print_r($arrActualizados);

            echo"<br><br>* * * * * * * * * * Iguales ".count($arrIguales)." * * * * * * * * * *<br>";
            print_r($arrIguales);

            echo"<br><br>* * * * * * * * * * Errores ".count($arrErrores)." * * * * * * * * * *<br>";
            print_r($arrErrores);
            echo "</pre>";

        }else{
            echo "El archivo no existe, valide la URL";
        }
    }
}
