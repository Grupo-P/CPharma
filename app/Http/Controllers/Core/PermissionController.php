<?php

namespace App\Http\Controllers\Core;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Permission;
use App\Models\User;

class PermissionController extends Controller
{
    public function __construct()
    {        
        $this->middleware('can:core.permisos.index')->only('index');
        $this->middleware('can:core.permisos.show')->only('show');
        $this->middleware('can:core.permisos.create')->only('create');
        $this->middleware('can:core.permisos.edit')->only('edit');
        $this->middleware('can:core.permisos.active')->only('active');
        $this->middleware('can:core.permisos.inactive')->only('inactive');
        $this->middleware('can:core.permisos.destroy')->only('destroy');
        $this->middleware('can:core.permisos.restore')->only('restore');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $permisos = Permission::all();        
        return view('core.permisos.index', compact('permisos'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('core.permisos.create');
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
            'name' => 'required|unique:permissions',
            'description' => 'required|unique:permissions',
        ]);

        $permiso = Permission::create($request->all());
        $permiso->permissions()->sync($request->permissions);
        session()->flash('message', 'Permiso creado con éxito');

        $permisos = Permission::all();
        return redirect()->route('core.permisos.index', compact('permisos'));
    }

    /**
     * Display the specified resource.
     *
     * @param  Permission  $permiso
     * @return \Illuminate\Http\Response
     */
    public function show(Permission  $permiso)
    {
        $creadoPor = User::find($permiso->user_created_at);
        $actualizadoPor = User::find($permiso->user_updated_at);
        $borradoPor = User::find($permiso->user_deleted_at);        
        return view('core.permisos.show', compact('permiso', 'creadoPor', 'actualizadoPor', 'borradoPor'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  Permission  $permiso
     * @return \Illuminate\Http\Response
     */
    public function edit(Permission  $permiso)
    {
        return view('core.permisos.edit', compact('permiso'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  Permission  $permiso
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Permission  $permiso)
    {
        $request->validate([
            'name' => "required|unique:permissions,name,$permiso->name",
            'description' => "required|unique:permissions,description,$permiso->description",
        ]);

        $permiso->update($request->all());
        session()->flash('message', 'Permiso actualizado con éxito');

        $permisos = Permission::all();
        return redirect()->route('core.permisos.index', compact('permisos'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  Permission  $permiso
     * @return \Illuminate\Http\Response
     */
    public function destroy(Permission  $permiso)
    {
        $permiso->borrado = 1;
        $permiso->deleted_at = date('Y-m-d H:i:s');
        $permiso->user_deleted_at = auth()->user()->id;
        $permiso->save();
        session()->flash('message', 'Permiso borrado con éxito');

        $permisos = Permission::all();
        return redirect()->route('core.permisos.index', compact('permisos'));
    }

    /**
     * Restore the specified resource from storage.
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function restore(Request $request)
    {
        $permiso = Permission::find($request->id);
        $permiso->borrado = 0;
        $permiso->deleted_at = NULL;
        $permiso->user_deleted_at = NULL;
        $permiso->save();
        session()->flash('message', 'Permiso restaurado con éxito');

        $permisos = Permission::all();
        return redirect()->route('core.permisos.index', compact('permisos'));
    }

    /**
     * Active the specified resource from storage.
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function active(Request $request)
    {
        $permiso = Permission::find($request->id);
        $permiso->activo = 1;
        $permiso->save();
        session()->flash('message', 'Permiso activado con éxito');

        $permisos = Permission::all();
        return redirect()->route('core.permisos.index', compact('permisos'));
    }

    /**
     * Inactive the specified resource from storage.
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function inactive(Request $request)
    {
        $permiso = Permission::find($request->id);
        $permiso->activo = 0;
        $permiso->save();
        session()->flash('message', 'Permiso inactivado con éxito');

        $permisos = Permission::all();
        return redirect()->route('core.permisos.index', compact('permisos'));
    }
}
