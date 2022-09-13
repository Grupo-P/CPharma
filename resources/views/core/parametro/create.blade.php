@extends('adminlte::page')

@section('title', 'Crear Parametro')

@section('footer')
    <!-- Footer theme | No Borrar -->
@stop

@section('css')
    
@stop

@section('js')
    
@stop

@section('content_header')
    <h1>Crear Parametro</h1>
    <nav style="--bs-breadcrumb-divider: '>';" aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="/dashboard">Dashboard</a></li>
            <li class="breadcrumb-item"><a href="{{route('core.parametros.index')}}">Parametros</a></li>
            <li class="breadcrumb-item active" aria-current="page">Crear Parametro</li>
        </ol>
    </nav>    
@stop

@section('content')
    <div class="card">
        <div class="card-body">
            {!! Form::open(['route' => 'core.parametros.store']) !!}
                <div class="form-group">
                    {!! Form::label('variable', 'Variable') !!}
                    {!! Form::text('variable', null, [ 'class' => 'form-control', 'placeholder' => 'Ingrese el nombre de la variable...']) !!}

                    @error('variable')
                        <small class="text-danger">{{$message}}</small>
                    @enderror
                </div>

                <div class="form-group">
                    {!! Form::label('valor', 'Valor') !!}
                    {!! Form::text('valor', null, [ 'class' => 'form-control', 'placeholder' => 'Ingrese el valor de la variable...']) !!}

                    @error('valor')
                        <small class="text-danger">{{$message}}</small>
                    @enderror
                </div>

                <div class="form-group">
                    {!! Form::hidden('user_created_at', auth()->user()->id, null) !!}
                    {!! Form::label('descripcion', 'Descripcion') !!}
                    {!! Form::textarea('descripcion', null, [ 'class' => 'form-control', 'placeholder' => 'Ingrese la descripcion de la variable...']) !!}
                </div>

                <div class="form-group">
                    {!! Form::submit('Crear', [ 'class' => 'btn btn-success']) !!}
                    {!! Form::reset('Borrar', [ 'class' => 'btn btn-danger']) !!}
                </div>
            {!! Form::close() !!}
        </div>
    </div>
@stop