@extends('adminlte::page')

@section('title', 'Crear Permiso')

@section('footer')
    <!-- Footer theme | No Borrar -->
@stop

@section('css')
    
@stop

@section('js')
    
@stop

@section('content_header')
    <h1>Crear Permiso</h1>
    <nav style="--bs-breadcrumb-divider: '>';" aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="/dashboard">Dashboard</a></li>
            <li class="breadcrumb-item"><a href="{{route('core.permisos.index')}}">Permisos</a></li>
            <li class="breadcrumb-item active" aria-current="page">Crear Permiso</li>
        </ol>
    </nav>    
@stop

@section('content')
    <div class="card">
        <div class="card-body">
            {!! Form::open(['route' => 'core.permisos.store']) !!}
                @include('core.permisos.partials.form')
                <div class="form-group">
                    {!! Form::hidden('user_created_at', auth()->user()->id, null) !!}
                    {!! Form::submit('Crear', [ 'class' => 'btn btn-success']) !!}
                    {!! Form::reset('Borrar', [ 'class' => 'btn btn-danger']) !!}
                </div>
            {!! Form::close() !!}
        </div>
    </div>
@stop