<?php

namespace App\Http\Controllers\Core;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
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
        $grupos_permisos = $this->getPermissionsGroup();
        return view('core.roles.create', compact('grupos_permisos'));
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

        $role = Role::create($request->all());
        $role->permissions()->sync($request->permissions);
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
        $permissions = $this->getPermissionsName($role);
        return view('core.roles.show', compact('role', 'creadoPor', 'actualizadoPor', 'borradoPor', 'permissions'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  Role $role
     * @return \Illuminate\Http\Response
     */
    public function edit(Role $role)
    {
        $grupos_permisos = $this->getPermissionsGroup();        
        return view('core.roles.edit', compact('role', 'grupos_permisos'));
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
        $role->permissions()->sync($request->permissions);
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
        $role->updated_at = date('Y-m-d H:i:s');
        $role->user_updated_at = auth()->user()->id;
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
        $role->updated_at = date('Y-m-d H:i:s');
        $role->user_updated_at = auth()->user()->id;
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
        $role->updated_at = date('Y-m-d H:i:s');
        $role->user_updated_at = auth()->user()->id;
        $role->save();
        session()->flash('message', 'Rol inactivado con éxito');

        $roles = Role::all();
        return redirect()->route('core.roles.index', compact('roles'));
    }

    private function getPermissionsGroup(){
        $permisos_general[0] = 'Generales';
        $permisos_menus[0] = 'Menús';
        $permisos_usuarios[0] = 'Usuarios';
        $permisos_parametros[0] = 'Parametros';
        $permisos_roles[0] = 'Roles';
        $permisos_licencias[0] = 'Licencias';
        $permisos_conexiones[0] = 'Conexiones';
        $permisos_auditorias[0] = 'Auditorías';
        $permisos_permisos[0] = 'Permisos';

        $permisos_general[1] = Permission::where('name','LIKE', '%dashboard%')
            ->orWhere('name','LIKE', '%health%')
            ->orWhere('name','LIKE', '%demo%')
            ->get();
        
        $permisos_menus[1] = Permission::where('name','LIKE', '%menu%')->get();
        $permisos_usuarios[1] = Permission::where('name','LIKE', '%usuarios%')->get();
        $permisos_parametros[1] = Permission::where('name','LIKE', '%parametros%')->get();
        $permisos_roles[1] = Permission::where('name','LIKE', '%roles%')->get();
        $permisos_licencias[1] = Permission::where('name','LIKE', '%licencias%')->get();
        $permisos_conexiones[1] = Permission::where('name','LIKE', '%conexiones%')->get();
        $permisos_auditorias[1] = Permission::where('name','LIKE', '%auditorias%')->get();
        $permisos_permisos[1] = Permission::where('name','LIKE', '%permisos%')->get();

        //Ordenar alfabeticamente
        $grupos_permisos = [
            $permisos_general,
            $permisos_menus,
            $permisos_auditorias,
            $permisos_conexiones,
            $permisos_licencias,
            $permisos_parametros,
            $permisos_permisos,
            $permisos_roles,
            $permisos_usuarios,
        ];
        return $grupos_permisos;
    }

    private function getPermissionsName(Role $role)
    {
        $label = "";
        $permissions = $role->permissions;
        foreach ($permissions as $permission){
            $label .= $permission->description.', ';
        }
        $label = substr($label, 0, -2);
        return $label;
    }
}
