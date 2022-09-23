<?php

namespace App\Http\Controllers\Core;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use App\Models\User;

class RoleController extends Controller
{
    public function __construct()
    {        
        $this->middleware('can:core.roles.index')->only('index');
        $this->middleware('can:core.roles.show')->only('show');
        $this->middleware('can:core.roles.create')->only('create');
        $this->middleware('can:core.roles.edit')->only('edit');
        $this->middleware('can:core.roles.active')->only('active');
        $this->middleware('can:core.roles.inactive')->only('inactive');
        $this->middleware('can:core.roles.destroy')->only('destroy');
        $this->middleware('can:core.roles.restore')->only('restore');
    }
    
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $roles = Role::all();        
        return view('core.roles.index', compact('roles'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('core.roles.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',            
        ]);

        Role::create($request->all());
        session()->flash('message', 'Rol creado con éxito');

        $roles = Role::all();
        return redirect()->route('core.roles.index', compact('roles'));
    }

    /**
     * Display the specified resource.
     *
     * @param  Role $role
     * @return \Illuminate\Http\Response
     */
    public function show(Role $role)
    {
        $creadoPor = User::find($role->user_created_at);
        $actualizadoPor = User::find($role->user_updated_at);
        $borradoPor = User::find($role->user_deleted_at);
        return view('core.roles.show', compact('role', 'creadoPor', 'actualizadoPor', 'borradoPor'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  Role $role
     * @return \Illuminate\Http\Response
     */
    public function edit(Role $role)
    {
        return view('core.roles.edit', compact('role'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  Role $role
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Role $role)
    {
        $request->validate([            
            'name' => 'required',
        ]);

        $role->update($request->all());
        session()->flash('message', 'Rol actualizado con éxito');

        $roles = Role::all();
        return redirect()->route('core.roles.index', compact('roles'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  Role $role
     * @return \Illuminate\Http\Response
     */
    public function destroy(Role $role)
    {
        $role->borrado = 1;
        $role->deleted_at = date('Y-m-d H:i:s');
        $role->user_deleted_at = auth()->user()->id;
        $role->save();
        session()->flash('message', 'Rol borrado con éxito');

        $roles = Role::all();
        return redirect()->route('core.roles.index', compact('roles'));
    }

    /**
     * Restore the specified resource from storage.
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function restore(Request $request)
    {
        $role = Role::find($request->id);
        $role->borrado = 0;
        $role->deleted_at = NULL;
        $role->user_deleted_at = NULL;
        $role->save();
        session()->flash('message', 'Rol restaurado con éxito');

        $roles = Role::all();
        return redirect()->route('core.roles.index', compact('roles'));
    }

    /**
     * Active the specified resource from storage.
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function active(Request $request)
    {
        $role = Role::find($request->id);
        $role->activo = 1;
        $role->save();
        session()->flash('message', 'Rol activado con éxito');

        $roles = Role::all();
        return redirect()->route('core.roles.index', compact('roles'));
    }

    /**
     * Inactive the specified resource from storage.
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function inactive(Request $request)
    {
        $role = Role::find($request->id);
        $role->activo = 0;
        $role->save();
        session()->flash('message', 'Rol inactivado con éxito');

        $roles = Role::all();
        return redirect()->route('core.roles.index', compact('roles'));
    }
}
