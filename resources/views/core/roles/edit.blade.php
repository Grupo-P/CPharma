@extends('adminlte::page')

@section('title', 'Editar Rol')

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
    <h1>Editar Rol</h1>
    <nav style="--bs-breadcrumb-divider: '>';" aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="/dashboard">Dashboard</a></li>
            <li class="breadcrumb-item"><a href="{{route('core.roles.index')}}">Roles</a></li>
            <li class="breadcrumb-item active" aria-current="page">Editar Rol</li>
        </ol>
    </nav>
@stop

@section('content')
    <div class="card">
        <div class="card-body">
            {!! Form::model($role ,['route' => ['core.roles.update', $role], 'method' => 'PUT']) !!}
                @include('core.roles.partials.form')            
                <div class="form-group">
                    {!! Form::hidden('user_updated_at', auth()->user()->id, null) !!}
                    {!! Form::submit('Editar', [ 'class' => 'btn btn-success']) !!}
                    {!! Form::reset('Borrar', [ 'class' => 'btn btn-danger']) !!}
                </div>
            {!! Form::close() !!}
        </div>
    </div>
@stop