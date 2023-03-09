<?php

namespace compras\Http\Controllers;

use Illuminate\Http\Request;
use compras\OrdenCompra;
use compras\OrdenCompraDetalle; 
use compras\User;
use compras\Auditoria;

class OrdenCompraDetalleController extends Controller
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
        $usuario = auth()->user()->name;
        $OrdenActiva = 
        OrdenCompra::where('user',$usuario)
        ->where('estatus','ACTIVO')
        ->get();

        if(!empty($OrdenActiva[0]->codigo)) {
           $ordenCompraDetalles =  OrdenCompraDetalle::all()
           ->where('codigo_orden',$OrdenActiva[0]->codigo);
           return view('pages.ordenCompraDetalle.index', compact('ordenCompraDetalles'));
        }
        else if(empty($OrdenActiva[0]->codigo)) {
          return back();
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
      $usuario = auth()->user()->name;
        $OrdenActiva = 
        OrdenCompra::where('user',$usuario)
        ->where('estatus','ACTIVO')
        ->get();

      if(!empty($OrdenActiva[0]->codigo)) {
        return view('pages.ordenCompraDetalle.create');
      }
      else if(empty($OrdenActiva[0]->codigo)) {
        return 'ordenNoActiva';
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
      try{
        $ordenCompraDetalles = new OrdenCompraDetalle();
        $ordenCompraDetalles->codigo_orden = $request->input('codigo_orden');
        $ordenCompraDetalles->id_articulo = $request->input('id_articulo');
        $ordenCompraDetalles->codigo_articulo = $request->input('codigo_articulo');
        $ordenCompraDetalles->codigo_barra = $request->input('codigo_barra');
        $ordenCompraDetalles->descripcion = $request->input('descripcion');
        $ordenCompraDetalles->sede1 = $request->input('sede1');
        $ordenCompraDetalles->sede2 = $request->input('sede2');
        $ordenCompraDetalles->sede3 = $request->input('sede3');
        $ordenCompraDetalles->sede4 = $request->input('sede4');
        $ordenCompraDetalles->sede5 = $request->input('sede5');
        $ordenCompraDetalles->total_unidades = $request->input('totalUnidades');
        $ordenCompraDetalles->costo_unitario = $request->input('costo_unitario');
        $ordenCompraDetalles->costo_total = ( 
            ($request->input('totalUnidades')) * ($request->input('costo_unitario')) 
          );
        $ordenCompraDetalles->existencia_rpt = $request->input('existencia_rpt');
        $ordenCompraDetalles->dias_restantes_rpt = $request->input('dias_restantes_rpt');
        $ordenCompraDetalles->origen_rpt = $request->input('origen_rpt');
        $ordenCompraDetalles->rango_rpt = $request->input('rango_rpt');
        $ordenCompraDetalles->estatus = 'ACTIVO';
        $ordenCompraDetalles->user = $request->input('usuario');
        $ordenCompraDetalles->unidades_vendidas = $request->input('unidades_vendidas');
        $ordenCompraDetalles->venta_diaria_real = $request->input('venta_diaria_real');
        $ordenCompraDetalles->pedir_real = $request->input('pedir_real');
        $ordenCompraDetalles->dias_pedir = $request->input('dias_pedir');

        $ordenCompraDetalles->save();
        
        $Auditoria = new Auditoria();
        $Auditoria->accion = 'AGREGAR';
        $Auditoria->tabla = 'ORDEN DE COMPRA DETALLE';
        $Auditoria->registro = "ORDEN: ".$ordenCompraDetalles->codigo_orden." ARTICULO: ".$ordenCompraDetalles->descripcion;
        $Auditoria->user = auth()->user()->name;
        $Auditoria->save();

      return redirect()->route('ordenCompraDetalle.index')->with('Saved', ' Informacion');
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
    public function show(Request $request, $id)
    {   
        $id_articulo = $request->input('id_articulo');
        $ordenCompraDetalles =
        OrdenCompraDetalle::where('id_articulo',$id_articulo)
        ->where('orden_compra_detalles.estatus','ACTIVO')
        ->join('orden_compras','orden_compras.codigo','=','orden_compra_detalles.codigo_orden')
        ->get();
      
        $Auditoria = new Auditoria();
        $Auditoria->accion = 'CONSULTAR';
        $Auditoria->tabla = 'ORDEN DE COMPRA DETALLE';
        $Auditoria->registro = "ORDEN: ".$ordenCompraDetalles[0]->codigo_orden." ARTICULO: ".$ordenCompraDetalles[0]->descripcion;
        $Auditoria->user = auth()->user()->name;
        $Auditoria->save();

        return view('pages.ordenCompraDetalle.show', compact('ordenCompraDetalles'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $ordenCompraDetalles = OrdenCompraDetalle::find($id);
        return view('pages.ordenCompraDetalle.edit', compact('ordenCompraDetalles'));
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
          $ordenCompraDetalles = OrdenCompraDetalle::find($id);
          $ordenCompraDetalles->fill($request->all());
          $ordenCompraDetalles->total_unidades = $request->input('totalUnidades');
          $ordenCompraDetalles->costo_unitario = $request->input('costo_unitario');
          $ordenCompraDetalles->costo_total = ( 
          ($request->input('totalUnidades')) * ($request->input('costo_unitario')) 
        );

        $ordenCompraDetalles->save();

        $Auditoria = new Auditoria();
        $Auditoria->accion = 'EDITAR';
        $Auditoria->tabla = 'ORDEN DE COMPRA DETALLE';
        $Auditoria->registro = 'ORDEN: '.$ordenCompraDetalles->codigo_orden.' ARTICULO: '.$ordenCompraDetalles->descripcion;
        $Auditoria->user = auth()->user()->name;
        $Auditoria->save();

        return redirect()->route('ordenCompraDetalle.index')->with('Updated', ' Informacion');
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
        $ordenCompraDetalles = OrdenCompraDetalle::find($id);

        $Auditoria = new Auditoria();        
        $Auditoria->tabla = 'ORDEN DE COMPRA DETALLE';
        $Auditoria->registro = "ORDEN: ".$ordenCompraDetalles->codigo_orden." ARTICULO: ".$ordenCompraDetalles->descripcion;
        $Auditoria->user = auth()->user()->name;
        
        if($ordenCompraDetalles->estatus == 'ACTIVO'){
            $ordenCompraDetalles->estatus = 'INACTIVO';
            $Auditoria->accion = 'DESINCORPORAR';
        }
        else if($ordenCompraDetalles->estatus == 'INACTIVO'){
            $ordenCompraDetalles->estatus = 'ACTIVO';
            $Auditoria->accion = 'REINCORPORAR';
        }

        $ordenCompraDetalles->user = auth()->user()->name;        
        $ordenCompraDetalles->save();

        $Auditoria->save();

        return redirect()->route('ordenCompraDetalle.index')->with('Deleted', ' Informacion');
    }
}
