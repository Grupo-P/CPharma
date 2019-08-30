<?php

namespace compras\Http\Controllers;

use Illuminate\Http\Request;
use compras\Role;
use compras\User;
use compras\Auditoria;

class RoleController extends Controller
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
        $roles =  Role::all();
        return view('pages.role.index', compact('roles'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('pages.role.create');
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
            $rol = new Role();
            $rol->nombre = $request->input('nombre');
            $rol->descripcion = $request->input('descripcion');
            $rol->create = $request->input('create');
            $rol->read = $request->input('read');
            $rol->update = $request->input('update');
            $rol->delete = $request->input('delete');
            $rol->user = auth()->user()->name;
            $rol->estatus = 'ACTIVO';
            $rol->save();

            $Auditoria = new Auditoria();
            $Auditoria->accion = 'CREAR';
            $Auditoria->tabla = 'ROL';
            $Auditoria->registro = $request->input('nombre');
            $Auditoria->user = auth()->user()->name;
            $Auditoria->save();

            return redirect()->route('rol.index')->with('Saved', ' Informacion');
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
        $rol = Role::find($id);

        $Auditoria = new Auditoria();
        $Auditoria->accion = 'CONSULTAR';
        $Auditoria->tabla = 'ROL';
        $Auditoria->registro = $rol->nombre;
        $Auditoria->user = auth()->user()->name;
        $Auditoria->save();

        return view('pages.role.show', compact('rol'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $rol = Role::find($id);
        return view('pages.role.edit', compact('rol'));
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
            $rol = Role::find($id);
            $rol->fill($request->all());
            $rol->user = auth()->user()->name;
            $rol->estatus = 'ACTIVO';
            $rol->save();

            $Auditoria = new Auditoria();
            $Auditoria->accion = 'EDITAR';
            $Auditoria->tabla = 'ROL';
            $Auditoria->registro = $rol->nombre;
            $Auditoria->user = auth()->user()->name;
            $Auditoria->save();

            return redirect()->route('rol.index')->with('Updated', ' Informacion');
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
        $rol = Role::find($id);

        $Auditoria = new Auditoria();
        $Auditoria->tabla = 'ROL';
        $Auditoria->registro = $rol->nombre;
        $Auditoria->user = auth()->user()->name;       

         if($rol->estatus == 'ACTIVO'){
            $rol->estatus = 'INACTIVO';
            $Auditoria->accion = 'DESINCORPORAR';
         }
         else if($rol->estatus == 'INACTIVO'){
            $rol->estatus = 'ACTIVO';
            $Auditoria->accion = 'REINCORPORAR';
         }

        $rol->user = auth()->user()->name;      
        $rol->save();

        $Auditoria->save();

        return redirect()->route('rol.index')->with('Deleted', ' Informacion');
    }
}
