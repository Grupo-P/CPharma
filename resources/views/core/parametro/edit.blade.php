@extends('adminlte::page')

@section('title', 'Editar Parámetro')

@section('footer')
    <!-- Footer theme | No Borrar -->
@stop

@section('css')
    
@stop

@section('js')
    
@stop

@section('content_header')
    <h1>Editar Parámetro</h1>
    <nav style="--bs-breadcrumb-divider: '>';" aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="/dashboard">Dashboard</a></li>
            <li class="breadcrumb-item"><a href="{{route('core.parametros.index')}}">Parámetros</a></li>
            <li class="breadcrumb-item active" aria-current="page">Editar Parámetro</li>
        </ol>
    </nav>
@stop

@section('content')
    <div class="card">
        <div class="card-body">
            {!! Form::model($parametro ,['route' => ['core.parametros.update', $parametro], 'method' => 'PUT']) !!}
                @include('core.parametro.partials.form')            
                <div class="form-group">
                    {!! Form::hidden('user_updated_at', auth()->user()->id, null) !!}
                    {!! Form::submit('Editar', [ 'class' => 'btn btn-success']) !!}
                    {!! Form::reset('Borrar', [ 'class' => 'btn btn-danger']) !!}
                </div>
            {!! Form::close() !!}
        </div>
    </div>
@stop