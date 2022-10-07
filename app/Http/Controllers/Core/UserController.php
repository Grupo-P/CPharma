<?php

namespace App\Http\Controllers\Core;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Spatie\Permission\Models\Role;

class UserController extends Controller
{
    public function __construct()
    {
        $this->middleware('can:core.usuarios.index')->only('index');
        $this->middleware('can:core.usuarios.show')->only('show');
        $this->middleware('can:core.usuarios.create')->only('create');
        $this->middleware('can:core.usuarios.edit')->only('edit');
        $this->middleware('can:core.usuarios.active')->only('active');
        $this->middleware('can:core.usuarios.inactive')->only('inactive');
        $this->middleware('can:core.usuarios.destroy')->only('destroy');
        $this->middleware('can:core.usuarios.restore')->only('restore');
        $this->middleware('can:core.usuarios.lock')->only('lock');
        $this->middleware('can:core.usuarios.profile')->only('profile');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $usuarios = User::all();
        return view('core.usuarios.index', compact('usuarios'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $roles = Role::all();
        $url_imagen = '/storage/default.jpg';
        return view('core.usuarios.create', compact('roles', 'url_imagen'));
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
            'email' => 'required|unique:users',
            'password' => 'required',
            'documento' => 'required|unique:users',
            'file' => 'image',
        ]);

        $usuario = User::create($request->all());
        $usuario->password = Hash::make($usuario->password);
        $usuario->save();

        $usuario->roles()->sync($request->roles);

        if($request->file('file')){
            $url = Storage::put('public/usuarios', $request->file('file'));
            $usuario->imagenes()->create([
                'url' => $url,
                'user_created_at' => $usuario->id,
                'activo' => 1,
                'borrado' => 0,
            ]);
        }
        
        session()->flash('message', 'Usuario creado con éxito');

        $usuarios = User::all();
        return redirect()->route('core.usuarios.index', compact('usuarios'));
    }

    /**
     * Display the specified resource.
     *
     * @param  User $usuario
     * @return \Illuminate\Http\Response
     */
    public function show(User $usuario)
    {
        $creadoPor = User::find($usuario->user_created_at);
        $actualizadoPor = User::find($usuario->user_updated_at);
        $borradoPor = User::find($usuario->user_deleted_at);
        $url_imagen = $usuario->adminlte_image();
        return view('core.usuarios.show', compact('usuario', 'creadoPor', 'actualizadoPor', 'borradoPor', 'url_imagen'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  User $usuario
     * @return \Illuminate\Http\Response
     */
    public function edit(User $usuario)
    {
        $roles = Role::all();
        $url_imagen = $usuario->adminlte_image();
        return view('core.usuarios.edit', compact('usuario', 'roles', 'url_imagen'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  User $usuario
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, User $usuario)
    {        
        $request->validate([
            'name' => 'required',
            'email' => "required|unique:users,email,$usuario->id",
            'password' => "required|unique:users,email,$usuario->password",
            'documento' => "required|unique:users,email,$usuario->documento",
            'file' => 'image',
        ]);        

        $contraseña = $usuario->password;

        $usuario->update($request->all());

        if($request->file('file')){
            $url = Storage::put('public/usuarios', $request->file('file'));
            if($usuario->imagenes()){
                $usuario->imagenes()->delete();
            }
            $usuario->imagenes()->create([
                'url' => $url,
                'user_created_at' => $usuario->id,
                'activo' => 1,
                'borrado' => 0,
            ]);
        }

        if($usuario->password != $contraseña){
            $usuario->password = Hash::make($usuario->password);
            $usuario->cambio_clave = 0;
            $usuario->save();
        }

        if(isset($request->roles)){
            $usuario->roles()->sync($request->roles);
        }

        session()->flash('message', 'Usuario actualizado con éxito');        
        
        $usuarios = User::all();

        if(isset($request->roles)){
            return redirect()->route('core.usuarios.index', compact('usuarios'));
        }else{
            return redirect()->route('dashboard');
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  User $usuario
     * @return \Illuminate\Http\Response
     */
    public function destroy(User $usuario)
    {
        $usuario->borrado = 1;
        $usuario->deleted_at = date('Y-m-d H:i:s');
        $usuario->user_deleted_at = auth()->user()->id;
        $usuario->save();
        session()->flash('message', 'Usuario borrado con éxito');

        $usuarios = User::all();
        return redirect()->route('core.usuarios.index', compact('usuarios'));
    }

    /**
     * Restore the specified resource from storage.
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function restore(Request $request)
    {
        $usuario = User::find($request->id);
        $usuario->borrado = 0;
        $usuario->deleted_at = NULL;
        $usuario->user_deleted_at = NULL;
        $usuario->updated_at = date('Y-m-d H:i:s');
        $usuario->user_updated_at = auth()->user()->id;
        $usuario->save();
        session()->flash('message', 'Usuario restaurado con éxito');

        $usuarios = User::all();
        return redirect()->route('core.usuarios.index', compact('usuarios'));
    }

    /**
     * Active the specified resource from storage.
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function active(Request $request)
    {
        $usuario = User::find($request->id);
        $usuario->activo = 1;
        $usuario->updated_at = date('Y-m-d H:i:s');
        $usuario->user_updated_at = auth()->user()->id;
        $usuario->save();
        session()->flash('message', 'Usuario activado con éxito');

        $usuarios = User::all();
        return redirect()->route('core.usuarios.index', compact('usuarios'));
    }

    /**
     * Inactive the specified resource from storage.
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function inactive(Request $request)
    {
        $usuario = User::find($request->id);
        $usuario->activo = 0;
        $usuario->updated_at = date('Y-m-d H:i:s');
        $usuario->user_updated_at = auth()->user()->id;
        $usuario->save();
        session()->flash('message', 'Usuario inactivado con éxito');

        $usuarios = User::all();
        return redirect()->route('core.usuarios.index', compact('usuarios'));
    }

    /**
     * Notifies the user to change the password.
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function lock(Request $request)
    {
        $usuario = User::find($request->id);
        $usuario->cambio_clave = 1;
        $usuario->updated_at = date('Y-m-d H:i:s');
        $usuario->user_updated_at = auth()->user()->id;
        $usuario->save();
        session()->flash('message', 'Usuario notificado con éxito');

        $usuarios = User::all();
        return redirect()->route('core.usuarios.index', compact('usuarios'));
    }

    /**
     * Show the form for editing the specified resource.
     *     
     * @return \Illuminate\Http\Response
     */
    public function profile()
    {        
        $usuario = User::find(auth()->user()->id);
        $url_imagen = $usuario->adminlte_image();
        return view('core.usuarios.profile', compact('usuario', 'url_imagen'));
    }
}
