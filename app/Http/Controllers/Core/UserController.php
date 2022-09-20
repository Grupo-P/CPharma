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
        ]);

        $contraseña = $usuario->password;

        $usuario->update($request->all());

        if($usuario->password != $contraseña){
            $usuario->password = Hash::make($usuario->password);
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
}
