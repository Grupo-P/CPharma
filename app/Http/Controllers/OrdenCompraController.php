<?php

namespace compras\Http\Controllers;

use Illuminate\Http\Request;
use compras\OrdenCompra;
use compras\Sede;
use compras\User;
use compras\Auditoria;

class OrdenCompraController extends Controller
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
        $OrdenCompra =  OrdenCompra::all();
        return view('pages.ordenCompra.index', compact('OrdenCompra'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
       $sedes = Sede::pluck('razon_social','id');
       return view('pages.ordenCompra.create', compact('sedes'));
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
        $OrdenCompra = new OrdenCompra();

        $sede_origen = $request->input('SedeOrigen');

      /*INICIO DE SEDE DESTINO*/
        if(($request->input('CDD'))=='SI'){
          $sede_destino = 'CENTRO DE DISTRIBUCION';
        }
        else{
          $sede_destino = $request->input('SedeDestino');
        }
      /*FIN DE SEDE DESTINO*/

      /*INICIO DE CONDICION*/
        if(($request->input('condicion'))=='CREDITO'){
          $dias_credito = intval($request->input('dias_credito'));
        }
        else{
          $dias_credito = 0;
        }
      /*FIN DE CONDICION*/

      /*INICIO DE ASIGNACION DE CODIGO*/
        $UltimaOrden = 
        OrdenCompra::orderBy('id','desc')
        ->take(1)->get();
        if(!empty($UltimaOrden[0])){
          $UltimoId = $UltimaOrden->id;
        }
        else{
          $UltimoId = 0;
        }
        $UltimoId++;
        $SiglasOrigen = $request->input('SiglasOrigen');
        $CodigoOrden =  ''.$SiglasOrigen.''.$UltimoId;
      /*FIN DE ASIGNACION DE CODIGO*/
          
        //return redirect()->route('ordenCompra.index')->with('Saved', ' Informacion');
      }
      catch(\Illuminate\Database\QueryException $e){
        return back()->with('Error', 'Error');
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
