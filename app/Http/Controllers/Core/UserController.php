<?php

namespace App\Http\Controllers\Core;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $usuarios = User::all();
        return view('core.usuario.index', compact('usuarios'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('core.usuario.create');
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
        ]);

        $usuario = User::create($request->all());
        $usuario->password = Hash::make($usuario->password);
        $usuario->save();
        session()->flash('message', 'Usuario creado con éxito');

        $usuarios = User::all();
        return view('core.usuario.index', compact('usuarios'));
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
        return view('core.usuario.show', compact('usuario', 'creadoPor', 'actualizadoPor', 'borradoPor'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  User $usuario
     * @return \Illuminate\Http\Response
     */
    public function edit(User $usuario)
    {
        return view('core.usuario.edit', compact('usuario'));
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
            'documento' => "required|unique:users,email,$usuario->documento",
        ]);

        $contraseña = $usuario->password;

        $usuario->update($request->all());

        if($usuario->password != $contraseña){
            $usuario->password = Hash::make($usuario->password);
            $usuario->cambio_clave = 0;
            $usuario->save();            
        }
        session()->flash('message', 'Usuario actualizado con éxito');        
        
        $usuarios = User::all();
        return view('core.usuario.index', compact('usuarios'));
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
        return view('core.usuario.index', compact('usuarios'));
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
        $usuario->save();
        session()->flash('message', 'Usuario restaurado con éxito');

        $usuarios = User::all();
        return view('core.usuario.index', compact('usuarios'));
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
        $usuario->save();
        session()->flash('message', 'Usuario activado con éxito');

        $usuarios = User::all();
        return view('core.usuario.index', compact('usuarios'));
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
        $usuario->save();
        session()->flash('message', 'Usuario inactivado con éxito');

        $usuarios = User::all();
        return view('core.usuario.index', compact('usuarios'));
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
        $usuario->save();
        session()->flash('message', 'Usuario notificado con éxito');

        $usuarios = User::all();
        return view('core.usuario.index', compact('usuarios'));
    }

    /**
     * Show the form for editing the specified resource.
     *     
     * @return \Illuminate\Http\Response
     */
    public function profile()
    {        
        $usuario = User::find(auth()->user()->id);
        return view('core.usuario.profile', compact('usuario'));
    }
}
