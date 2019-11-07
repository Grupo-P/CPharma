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

        $usuario = auth()->user()->name;
        $OrdenActiva = 
        OrdenCompra::orderBy('id','asc')
        ->where('user',$usuario)
        ->where('estatus','ACTIVO')
        ->get();

        if(!empty($OrdenActiva[0]->codigo)) {
          return redirect()->route('ordenCompra.index')->with('OrdenActiva', ''.$OrdenActiva[0]->codigo);
        }
        else if(empty($OrdenActiva[0]->codigo)) {
         
          $OrdenCompra = new OrdenCompra();

          $OrdenCompra->proveedor = $request->input('proveedor');
          $OrdenCompra->fecha_estimada_despacho = $request->input('fecha_estimada_despacho');
          $OrdenCompra->moneda = $request->input('moneda');
          $OrdenCompra->observacion = $request->input('observacion');
          $OrdenCompra->sede_origen = $request->input('SedeOrigen');
          $OrdenCompra->user = auth()->user()->name;
          $OrdenCompra->estatus = 'ACTIVO';
          $OrdenCompra->estado = 'EN PROCESO';

        /*INICIO DE SEDE DESTINO*/
          if(($request->input('CDD'))=='SI'){
            $OrdenCompra->sede_destino = 'CENTRO DE DISTRIBUCION';
          }
          else{
            $OrdenCompra->sede_destino = $request->input('SedeDestino');
          }
        /*FIN DE SEDE DESTINO*/

        /*INICIO DE CONDICION*/
          if(($request->input('condicion_crediticia'))=='CREDITO'){
            $dias_credito = intval($request->input('dias_credito'));
          }
          else{
            $dias_credito = 0;
          }
          $OrdenCompra->condicion_crediticia = $request->input('condicion_crediticia');
          $OrdenCompra->dias_credito = $dias_credito;
        /*FIN DE CONDICION*/

        /*INICIO DE ASIGNACION DE CODIGO*/
          $UltimaOrden = 
          OrdenCompra::orderBy('id','desc')
          ->select('id')
          ->take(1)->get();

          if( (!empty($UltimaOrden[0])) &&
              ((($UltimaOrden[0]['id'])!=0) 
              || (($UltimaOrden[0]['id'])!=NULL)
              || (($UltimaOrden[0]['id'])!=''))
            ) {
            $UltimoId = $UltimaOrden[0]['id'];
          }
          else{
            $UltimoId = 0;
          }
          $UltimoId++;
          $SiglasOrigen = $request->input('SiglasOrigen');
          $OrdenCompra->codigo =  ''.$SiglasOrigen.''.$UltimoId;
        /*FIN DE ASIGNACION DE CODIGO*/
          $OrdenCompra->save();
          return redirect()->route('ordenCompra.index')->with('Saved', ''.$OrdenCompra->codigo);
        }
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
        $OrdenCompra = OrdenCompra::find($id);
        return view('pages.ordenCompra.show', compact('OrdenCompra'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
      $OrdenCompra = OrdenCompra::find($id);
      return view('pages.ordenCompra.edit', compact('OrdenCompra'));
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
          $OrdenCompra = OrdenCompra::find($id);
          $OrdenCompra->fill($request->all());

        /*INICIO DE SEDE DESTINO*/
          if(($request->input('CDD'))=='SI'){
            $OrdenCompra->sede_destino = 'CENTRO DE DISTRIBUCION';
          }
          else{
            $OrdenCompra->sede_destino = $request->input('SedeDestino');
          }
        /*FIN DE SEDE DESTINO*/

        /*INICIO DE CONDICION*/
          if(($request->input('condicion_crediticia'))=='CREDITO'){
            $dias_credito = intval($request->input('dias_credito'));
          }
          else{
            $dias_credito = 0;
          }
          $OrdenCompra->condicion_crediticia = $request->input('condicion_crediticia');
          $OrdenCompra->dias_credito = $dias_credito;
        /*FIN DE CONDICION*/

          $OrdenCompra->save();
          return redirect()->route('ordenCompra.index')->with('Updated', ' Informacion');
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
    public function destroy(Request $request, $id)
    {
      $OrdenCompra = OrdenCompra::find($id);

      if($request->input('anular')=='valido'){
        $OrdenCompra->fill($request->all());
        $OrdenCompra->estado = 'ANULADA';
        $OrdenCompra->estatus = 'ANULADA';
        $OrdenCompra->save();
        return redirect()->route('ordenCompra.index')->with('Updated', ' Informacion');
      }
      else{

        if($OrdenCompra->estatus == 'ACTIVO'){
          $OrdenCompra->estatus = 'EN ESPERA';
        }   
        else if($OrdenCompra->estatus == 'EN ESPERA'){
          $usuario = auth()->user()->name;
          $OrdenActiva = 
          OrdenCompra::orderBy('id','asc')
          ->where('user',$usuario)
          ->where('estatus','ACTIVO')
          ->get();

          if(!empty($OrdenActiva[0]->codigo)) {
            return redirect()->route('ordenCompra.index')->with('OrdenActiva', ''.$OrdenActiva[0]->codigo);
          }
          else if(empty($OrdenActiva[0]->codigo)) {
            $OrdenCompra->estatus = 'ACTIVO';
          }
        }
        $OrdenCompra->save();
        return redirect()->route('ordenCompra.index')->with('Deleted', ' Informacion');
      }
    }
}