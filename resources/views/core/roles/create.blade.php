@extends('adminlte::page')

@section('title', 'Crear Rol')

@section('footer')
    <!-- Footer theme | No Borrar -->
@stop

@section('css')
    
@stop

@section('js')
    
@stop

@section('content_header')
    <!-- Seccion de favoritos -->
        @include('favoritos')
    <!-- Seccion de favoritos -->
    <h1>Crear Rol</h1>
    <nav style="--bs-breadcrumb-divider: '>';" aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="/dashboard">Dashboard</a></li>
            <li class="breadcrumb-item"><a href="{{route('core.roles.index')}}">Roles</a></li>
            <li class="breadcrumb-item active" aria-current="page">Crear Rol</li>
        </ol>
    </nav>    
@stop

@section('content')
    <div class="card">
        <div class="card-body">
            {!! Form::open(['route' => 'core.roles.store']) !!}
                @include('core.roles.partials.form')
                <div class="form-group">
                    {!! Form::hidden('user_created_at', auth()->user()->id, null) !!}
                    {!! Form::submit('Crear', [ 'class' => 'btn btn-success']) !!}
                    {!! Form::reset('Borrar', [ 'class' => 'btn btn-danger']) !!}
                </div>
            {!! Form::close() !!}
        </div>
    </div>
@stop