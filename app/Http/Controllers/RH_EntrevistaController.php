<?php

namespace compras\Http\Controllers;


use Illuminate\Http\Request;
use compras\RH_Entrevista;
use compras\User;

class RH_EntrevistaController extends Controller
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
    public function index(){

        $entrevistas = RH_Entrevista::all();
        return view('pages.RRHH.entrevistas.index', compact('entrevistas'));
    }



    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
     return view('pages.RRHH.entrevistas.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $entrevistas = new RH_Entrevista();
        $entrevistas->fecha_entrevista = $request->input('fecha_entrevista');
        $entrevistas->entrevistadores = $request->input('entrevistadores');
        $entrevistas->lugar = $request->input('lugar');
        $entrevistas->observaciones = $request->input('observaciones');
        $entrevistas->estatus = 'ACTIVO';
        $entrevistas->user = auth()->user()->name;
        $entrevistas->save();
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
