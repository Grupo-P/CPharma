<?php

namespace compras\Http\Controllers;

use Illuminate\Http\Request;
use compras\Auditoria;
use compras\User;

class AuditoriaController extends Controller
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
        $auditorias =  Auditoria::orderBy('updated_at', 'desc')->get();
        return view('pages.auditoria.index', compact('auditorias'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $auditorias =  Auditoria::all();
        return view('pages.auditoria.index', compact('auditorias'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $auditorias =  Auditoria::all();
        return view('pages.auditoria.index', compact('auditorias'));
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $auditorias =  Auditoria::all();
        return view('pages.auditoria.index', compact('auditorias'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $auditorias =  Auditoria::all();
        return view('pages.auditoria.index', compact('auditorias'));
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
        $auditorias =  Auditoria::all();
        return view('pages.auditoria.index', compact('auditorias'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $auditorias =  Auditoria::all();
        return view('pages.auditoria.index', compact('auditorias'));
    }
}
