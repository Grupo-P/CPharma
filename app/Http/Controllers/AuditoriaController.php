<?php

namespace compras\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
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
        $registros = Auditoria::select('registro')->where('tabla','=','REPORTE')->groupBy('registro')->get();

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
        $accion = ($request->accion!="TODOS")?" AND auditorias.accion = '".$request->accion."'":"";
        $tabla = ($request->tabla!="TODOS")?" AND auditorias.tabla = '".$request->tabla."'":"";
        $registro = ($request->registro!="TODOS")?" AND auditorias.registro = '".$request->registro."'":"";
        $user = ($request->user!="TODOS")?" AND auditorias.user = '".$request->user."'":"";
        $departamento = ($request->departamento!="TODOS")?" AND departamentos.nombre = '".$request->departamento."'":"";

        if( !empty($request->fechadesde) && !empty($request->fechahasta)){            
            $fecha = " AND ( CONVERT(auditorias.created_at, DATE) >= '".$request->fechadesde."' AND CONVERT(auditorias.created_at, DATE) <= '".$request->fechahasta."')";
        }else{
            $fecha = "";
        }

        $sql = "
            SELECT auditorias.id, auditorias.accion, auditorias.tabla, auditorias.registro, auditorias.user, auditorias.created_at, auditorias.updated_at FROM auditorias
            LEFT JOIN users ON users.name = auditorias.user
            LEFT JOIN departamentos ON departamentos.nombre = users.departamento
            WHERE 1 = 1 ".$accion."".$tabla."".$registro."".$user."".$departamento."".$fecha."
            GROUP BY auditorias.id, auditorias.accion, auditorias.tabla, auditorias.registro, auditorias.user, auditorias.created_at, auditorias.updated_at
            ORDER BY auditorias.updated_at DESC
            LIMIT 150
            ";        

        /*
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
        */

        $auditorias = DB::select($sql);

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
