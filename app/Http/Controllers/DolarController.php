<?php

namespace compras\Http\Controllers;

use Illuminate\Http\Request;
use compras\Dolar;
use compras\User;

class DolarController extends Controller
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
        $dolars =  Dolar::all();
        return view('pages.dolar.index', compact('dolars'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('pages.dolar.create');
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
            $dolar = new Dolar();
            $dolar->tasa = $request->input('tasa');
            $dolar->fecha = $request->input('fecha');
            $dolar->estatus = 'ACTIVO';
            $dolar->user = auth()->user()->name;
            $dolar->save();
            return redirect()->route('dolar.index')->with('Saved', ' Informacion');
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
        $dolar = Dolar::find($id);        
        return view('pages.dolar.show', compact('dolar'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $dolar = Dolar::find($id);
        return view('pages.dolar.edit', compact('dolar'));
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
            $dolar = Dolar::find($id);
            $dolar->fill($request->all());
            $dolar->user = auth()->user()->name;
            $dolar->save();
            return redirect()->route('dolar.index')->with('Updated', ' Informacion');
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
        $dolar = Dolar::find($id);

         if($dolar->estatus == 'ACTIVO'){
            $dolar->estatus = 'INACTIVO';
         }
         else if($dolar->estatus == 'INACTIVO'){
            $dolar->estatus = 'ACTIVO';
         }

         $dolar->user = auth()->user()->name;        
         $dolar->save();
         return redirect()->route('dolar.index')->with('Deleted', ' Informacion');
    }
}
