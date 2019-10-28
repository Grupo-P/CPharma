<?php

namespace compras\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use compras\User; 
use compras\Role;
use compras\Sede;
use compras\Departamento;
use compras\Auditoria;

class UserController extends Controller
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
        if(auth()->user()->role == 'DEVELOPER'){
            $usuarios =  User::all();
            return view('pages.usuario.index', compact('usuarios'));
        }
        else{
            $usuarios =
            User::all()
            ->where('role', '<>','DEVELOPER');
            return view('pages.usuario.index', compact('usuarios'));
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $roles = Role::pluck('nombre','id');
        $sedes = Sede::pluck('razon_social','id');
        $departamentos = Departamento::pluck('nombre','id');
        return view('pages.usuario.create', compact('roles','sedes','departamentos'));
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
            $usuario = new User();
            $usuario->name = $request->input('name');
            $usuario->email = $request->input('email');
            $usuario->role = $request->input('role');
            $usuario->password = Hash::make($request->input('password'));
            $usuario->estatus = 'ACTIVO';
            $usuario->sede = $request->input('sede');
            $usuario->departamento = $request->input('departamento');
            $usuario->save();

            $Auditoria = new Auditoria();
            $Auditoria->accion = 'CREAR';
            $Auditoria->tabla = 'USUARIO';
            $Auditoria->registro = $request->input('name');
            $Auditoria->user = auth()->user()->name;
            $Auditoria->save();

            return redirect()->route('usuario.index')->with('Saved', ' Informacion');
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
        $usuario = User::find($id);

        $Auditoria = new Auditoria();
        $Auditoria->accion = 'CONSULTAR';
        $Auditoria->tabla = 'USUARIO';
        $Auditoria->registro = $usuario->name;
        $Auditoria->user = auth()->user()->name;
        $Auditoria->save();

        return view('pages.usuario.show', compact('usuario'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $usuario = User::find($id);
        $roles = Role::pluck('nombre','id');                
        $sedes = Sede::pluck('razon_social','id');
        $departamentos = Departamento::pluck('nombre','id');
        return view('pages.usuario.edit', compact('usuario','roles','sedes','departamentos'));
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
            $usuario = User::find($id);
            $contrasena = $usuario->password;
            $usuario->fill($request->all());

            $Auditoria = new Auditoria();
            $Auditoria->accion = 'EDITAR';
            $Auditoria->tabla = 'USUARIO';
            $Auditoria->registro = $usuario->name;
            $Auditoria->user = auth()->user()->name;
            $Auditoria->save();

        if($usuario->password != $contrasena){
            $usuario->password = Hash::make($request->input('password'));
        }
        
        $usuario->save();
        return redirect()->route('usuario.index')->with('Updated', ' Informacion');
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
        $usuario = User::find($id);

        $Auditoria = new Auditoria();        
        $Auditoria->tabla = 'USUARIO';
        $Auditoria->registro = $usuario->name;
        $Auditoria->user = auth()->user()->name;

         if($usuario->estatus == 'ACTIVO'){
            $usuario->estatus = 'INACTIVO';
            $Auditoria->accion = 'DESINCORPORAR';
         }
         else if($usuario->estatus == 'INACTIVO'){
            $usuario->estatus = 'ACTIVO';
            $Auditoria->accion = 'REINCORPORAR';
         }       
         $usuario->save();

         $Auditoria->save();
         
         return redirect()->route('usuario.index')->with('Deleted', ' Informacion');
    }
}
