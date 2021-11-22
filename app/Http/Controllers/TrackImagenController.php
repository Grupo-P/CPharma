<?php

namespace compras\Http\Controllers;

use Illuminate\Http\Request;
use compras\TrackImagen;
use compras\User;
use compras\Auditoria;
use compras\Configuracion;

class TrackImagenController extends Controller
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
        $trackimagenes =  TrackImagen::all();
        return view('pages.trackimagen.index', compact('trackimagenes'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('pages.trackimagen.create');
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
            $trackimagen = new TrackImagen();
            $trackimagen->codigo_barra = $request->input('codigo_barra');
            $trackimagen->url_app = $request->input('url_app');
            $trackimagen->estatus = 'ACTIVO';
            $trackimagen->user = auth()->user()->name;
            $trackimagen->save();

            $Auditoria = new Auditoria();
            $Auditoria->accion = 'CREAR';
            $Auditoria->tabla = 'TRACKIMAGEN';
            $Auditoria->registro = $request->input('codigo_barra');
            $Auditoria->user = auth()->user()->name;
            $Auditoria->save();

            return redirect()->route('trackimagen.index')->with('Saved', ' Informacion');
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
        $trackimagen = TrackImagen::find($id);

        $Auditoria = new Auditoria();
        $Auditoria->accion = 'CONSULTAR';
        $Auditoria->tabla = 'TRACKIMAGEN';
        $Auditoria->registro = $trackimagen->codigo_barra;
        $Auditoria->user = auth()->user()->name;
        $Auditoria->save();

        return view('pages.trackimagen.show', compact('trackimagen'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $trackimagen = TrackImagen::find($id);
        return view('pages.trackimagen.edit', compact('trackimagen'));
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
        try{
            $trackimagen = TrackImagen::find($id);
            $trackimagen->fill($request->all());
            $trackimagen->user = auth()->user()->name;
            $trackimagen->save();

            $Auditoria = new Auditoria();
            $Auditoria->accion = 'EDITAR';
            $Auditoria->tabla = 'TRACKIMAGEN';
            $Auditoria->registro = $trackimagen->codigo_barra;
            $Auditoria->user = auth()->user()->name;
            $Auditoria->save();

            return redirect()->route('trackimagen.index')->with('Updated', ' Informacion');
        }
        catch(\Illuminate\Database\QueryException $e){
            return back()->with('Error', ' Error');
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $trackimagen = TrackImagen::find($id);

        $Auditoria = new Auditoria();
        $Auditoria->tabla = 'TRACKIMAGEN';
        $Auditoria->registro = $trackimagen->codigo_barra;
        $Auditoria->user = auth()->user()->name;

        if($trackimagen->estatus == 'ACTIVO'){
            $trackimagen->estatus = 'INACTIVO';
            $Auditoria->accion = 'DESINCORPORAR';
        }
        else if($trackimagen->estatus == 'INACTIVO'){
            $trackimagen->estatus = 'ACTIVO';
            $Auditoria->accion = 'REINCORPORAR';
        }

        $trackimagen->user = auth()->user()->name;
        $trackimagen->save();

        $Auditoria->save();

        return redirect()->route('trackimagen.index')->with('Deleted', ' Informacion');
    }

    public function procesarTxt() {
        $configuracion =  Configuracion::select('valor')->where('variable','URL_interna')->get();
        $path = "".$configuracion[0]->valor;
        $filename = "postimage.txt";
        $file = $path.'/'.$filename;

        $cuentaOK = $cuentaErrores = $cuentaRepetidos =  $cuentaLinea =0;

        if(file_exists($file)){
            $Error = "<br><br>OK:: URL valida: ".$file;

            $fp = fopen($file, "r");
            while (!feof($fp)){
                $linea = fgets($fp);

                $trackimagenes = explode(" ", $linea);

                foreach ($trackimagenes as $trackimagen){
                    $urlTrackimagen = explode("/", $trackimagen);

                    if(count($urlTrackimagen)>1){

                        $imagen = explode(".", $urlTrackimagen[4]);
                        $codigo_barra = $imagen[0];

                        $TrackImagen =
                        TrackImagen::orderBy('id','asc')
                        ->where('codigo_barra',$codigo_barra)
                        ->get();

                        if(empty($TrackImagen[0]->codigo_barra)) {

                            $Modeltrackimagen = new TrackImagen();
                            $Modeltrackimagen->codigo_barra = $codigo_barra;
                            $Modeltrackimagen->url_app = $trackimagen;
                            $Modeltrackimagen->estatus = 'ACTIVO';
                            $Modeltrackimagen->user = 'SYSTEM';
                            $Modeltrackimagen->save();

                            $Auditoria = new Auditoria();
                            $Auditoria->accion = 'CREAR';
                            $Auditoria->tabla = 'TRACKIMAGEN';
                            $Auditoria->registro = $codigo_barra;
                            $Auditoria->user = 'SYSTEM';
                            $Auditoria->save();

                            echo "<br>Linea $cuentaLinea. OK:: Se creo trackimgaen, codigo de barra (".$codigo_barra.")";
                            $cuentaOK++;
                        }else{

                            $Modeltrackimagen = TrackImagen::find($TrackImagen[0]->id);
                            $Modeltrackimagen->url_app = $trackimagen;
                            $Modeltrackimagen->estatus = 'ACTIVO';
                            $Modeltrackimagen->user = 'SYSTEM';
                            $Modeltrackimagen->save();

                            $Auditoria = new Auditoria();
                            $Auditoria->accion = 'REEMPLAZO';
                            $Auditoria->tabla = 'TRACKIMAGEN';
                            $Auditoria->registro = $codigo_barra;
                            $Auditoria->user = 'SYSTEM';
                            $Auditoria->save();

                            echo"<br><br>Linea $cuentaLinea. OK:: Se reemplazo la url del codigo de barra(".$codigo_barra.")";
                            $cuentaRepetidos++;
                        }

                    }else{
                        echo "<br><br>Linea $cuentaLinea. ERROR:: Link no valido: <pre>";
                        print_r($urlTrackimagen);
                        echo"</pre>";
                        $cuentaErrores++;
                    }
                    $cuentaLinea++;
                }
            }
            fclose($fp);
        }else{
            $Error = "<br><br>ERROR: valide la URL: ".$file."<br><br>";
            $cuentaErrores++;
        }

        echo"<br><br>Creados con exito: ".$cuentaOK;
        echo"<br><br>Reemplazados con exito: ".$cuentaRepetidos;
        echo"<br><br>Errores: ".$cuentaErrores;
        echo $Error;
    }
}
