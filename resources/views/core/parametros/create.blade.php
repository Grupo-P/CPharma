@extends('adminlte::page')

@section('title', 'Crear Parámetro')

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
    <h1>Crear Parámetro</h1>
    <nav style="--bs-breadcrumb-divider: '>';" aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="/dashboard">Dashboard</a></li>
            <li class="breadcrumb-item"><a href="{{route('core.parametros.index')}}">Parámetros</a></li>
            <li class="breadcrumb-item active" aria-current="page">Crear Parámetro</li>
        </ol>
    </nav>    
@stop

@section('content')
    <div class="card">
        <div class="card-body">
            {!! Form::open(['route' => 'core.parametros.store']) !!}
                @include('core.parametros.partials.form')
                <div class="form-group">
                    {!! Form::hidden('user_created_at', auth()->user()->id, null) !!}
                    {!! Form::submit('Crear', [ 'class' => 'btn btn-success']) !!}
                    {!! Form::reset('Borrar', [ 'class' => 'btn btn-danger']) !!}
                </div>
            {!! Form::close() !!}
        </div>
    </div>
@stop