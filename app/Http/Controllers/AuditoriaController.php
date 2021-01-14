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
        $auditorias =  Auditoria::orderBy('updated_at', 'desc')->take(150)->get();
        $users = Auditoria::select('user')->groupBy('user')->get();
        $tablas = Auditoria::select('tabla')->groupBy('tabla')->get();
        $acciones = Auditoria::select('accion')->groupBy('accion')->get();
        $registros = Auditoria::select('registro')->groupBy('registro')->get();

        $departamentos = Auditoria::select('departamentos.nombre')
        ->leftJoin('users', 'users.name', '=', 'auditorias.user')
        ->leftJoin('departamentos', 'departamentos.nombre', '=', 'users.departamento')        
        ->where('departamentos.nombre','!=','')
        ->groupBy('departamentos.nombre')->get();

        return view('pages.auditoria.index', compact('auditorias','users','departamentos','tablas','acciones','registros'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $auditorias =  Auditoria::orderBy('updated_at', 'desc')->take(150)->get();
        $users = Auditoria::select('user')->groupBy('user')->get();
        $tablas = Auditoria::select('tabla')->groupBy('tabla')->get();
        $acciones = Auditoria::select('accion')->groupBy('accion')->get();
        $registros = Auditoria::select('registro')->groupBy('registro')->get();

        $departamentos = Auditoria::select('departamentos.nombre')
        ->leftJoin('users', 'users.name', '=', 'auditorias.user')
        ->leftJoin('departamentos', 'departamentos.nombre', '=', 'users.departamento')        
        ->where('departamentos.nombre','!=','')
        ->groupBy('departamentos.nombre')->get();

        return view('pages.auditoria.index', compact('auditorias','users','departamentos','tablas','acciones','registros'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {    
        $auditorias =  Auditoria::
        leftJoin('users', 'users.name', '=', 'auditorias.user')
        ->leftJoin('departamentos', 'departamentos.nombre', '=', 'users.departamento')
        ->where('auditorias.accion','=',$request->accion)
        ->where('auditorias.tabla','=',$request->tabla)
        ->where('auditorias.registro','=',$request->registro)
        ->where('auditorias.user','=',$request->user)
        ->where('departamentos.nombre','=',$request->departamento)
        ->whereBetween('auditorias.created_at', [$request->fechadesde, $request->fechahasta])
        ->orderBy('auditorias.updated_at', 'desc')->take(150)->get();

        $users = Auditoria::select('user')->groupBy('user')->get();
        $tablas = Auditoria::select('tabla')->groupBy('tabla')->get();
        $acciones = Auditoria::select('accion')->groupBy('accion')->get();
        $registros = Auditoria::select('registro')->groupBy('registro')->get();

        $departamentos = Auditoria::select('departamentos.nombre')
        ->leftJoin('users', 'users.name', '=', 'auditorias.user')
        ->leftJoin('departamentos', 'departamentos.nombre', '=', 'users.departamento')        
        ->where('departamentos.nombre','!=','')
        ->groupBy('departamentos.nombre')->get();

        return view('pages.auditoria.index', compact('auditorias','users','departamentos','tablas','acciones','registros'));
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $auditorias =  Auditoria::orderBy('updated_at', 'desc')->take(150)->get();
        $users = Auditoria::select('user')->groupBy('user')->get();
        $tablas = Auditoria::select('tabla')->groupBy('tabla')->get();
        $acciones = Auditoria::select('accion')->groupBy('accion')->get();
        $registros = Auditoria::select('registro')->groupBy('registro')->get();

        $departamentos = Auditoria::select('departamentos.nombre')
        ->leftJoin('users', 'users.name', '=', 'auditorias.user')
        ->leftJoin('departamentos', 'departamentos.nombre', '=', 'users.departamento')        
        ->where('departamentos.nombre','!=','')
        ->groupBy('departamentos.nombre')->get();

        return view('pages.auditoria.index', compact('auditorias','users','departamentos','tablas','acciones','registros'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $auditorias =  Auditoria::orderBy('updated_at', 'desc')->take(150)->get();
        $users = Auditoria::select('user')->groupBy('user')->get();
        $tablas = Auditoria::select('tabla')->groupBy('tabla')->get();
        $acciones = Auditoria::select('accion')->groupBy('accion')->get();
        $registros = Auditoria::select('registro')->groupBy('registro')->get();

        $departamentos = Auditoria::select('departamentos.nombre')
        ->leftJoin('users', 'users.name', '=', 'auditorias.user')
        ->leftJoin('departamentos', 'departamentos.nombre', '=', 'users.departamento')        
        ->where('departamentos.nombre','!=','')
        ->groupBy('departamentos.nombre')->get();

        return view('pages.auditoria.index', compact('auditorias','users','departamentos','tablas','acciones','registros'));
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
        $auditorias =  Auditoria::orderBy('updated_at', 'desc')->take(150)->get();
        $users = Auditoria::select('user')->groupBy('user')->get();
        $tablas = Auditoria::select('tabla')->groupBy('tabla')->get();
        $acciones = Auditoria::select('accion')->groupBy('accion')->get();
        $registros = Auditoria::select('registro')->groupBy('registro')->get();

        $departamentos = Auditoria::select('departamentos.nombre')
        ->leftJoin('users', 'users.name', '=', 'auditorias.user')
        ->leftJoin('departamentos', 'departamentos.nombre', '=', 'users.departamento')        
        ->where('departamentos.nombre','!=','')
        ->groupBy('departamentos.nombre')->get();

        return view('pages.auditoria.index', compact('auditorias','users','departamentos','tablas','acciones','registros'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $auditorias =  Auditoria::orderBy('updated_at', 'desc')->take(150)->get();
        $users = Auditoria::select('user')->groupBy('user')->get();
        $tablas = Auditoria::select('tabla')->groupBy('tabla')->get();
        $acciones = Auditoria::select('accion')->groupBy('accion')->get();
        $registros = Auditoria::select('registro')->groupBy('registro')->get();

        $departamentos = Auditoria::select('departamentos.nombre')
        ->leftJoin('users', 'users.name', '=', 'auditorias.user')
        ->leftJoin('departamentos', 'departamentos.nombre', '=', 'users.departamento')        
        ->where('departamentos.nombre','!=','')
        ->groupBy('departamentos.nombre')->get();

        return view('pages.auditoria.index', compact('auditorias','users','departamentos','tablas','acciones','registros'));
    }
}
