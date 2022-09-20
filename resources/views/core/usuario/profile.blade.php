@extends('adminlte::page')

@section('title', 'Perfil')

@section('footer')
    <!-- Footer theme | No Borrar -->
@stop

@section('css')
    
@stop

@section('js')
    
@stop

@section('content_header')
    <h1>Perfil</h1>    
@stop

@section('content')
    <div class="card">
        <div class="card-body">
            {!! Form::model($usuario ,['route' => ['core.usuarios.update', $usuario], 'method' => 'PUT']) !!}
                @include('core.usuario.partials.form')            
                <div class="form-group">
                    {!! Form::hidden('user_updated_at', auth()->user()->id, null) !!}
                    {!! Form::submit('Editar', [ 'class' => 'btn btn-success']) !!}
                    {!! Form::reset('Borrar', [ 'class' => 'btn btn-danger']) !!}
                </div>
            {!! Form::close() !!}
        </div>
    </div>
@stop