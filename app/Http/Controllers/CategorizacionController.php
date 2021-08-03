<?php

namespace compras\Http\Controllers;

use Illuminate\Http\Request;
use compras\Categorizacion;
use compras\User;
use compras\Auditoria;
use compras\Categoria;
use compras\Subcategoria;

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
}
