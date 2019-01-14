<!-- Correccion Orotografica: Completa 12/12/2018 -->
@extends('layouts.model')

@section('title')
    Empresa
@endsection

@section('content')
    <h1 class="h5 text-info">
        <i class="fas fa-plus"></i>
        Agregar empresa
    </h1>

    <hr class="row align-items-start col-12">

    <form action="/empresa/" method="POST" style="display: inline;">  
        @csrf                       
        <button type="submit" name="Regresar" role="button" class="btn btn-outline-info btn-sm"data-placement="top"><i class="fa fa-reply">&nbsp;Regresar</i></button>
    </form>

    <br>
    <br>

    {!! Form::open(['route' => 'empresa.store', 'method' => 'POST']) !!}
    <fieldset>

        <table class="table table-borderless table-striped">
        <thead class="thead-dark">
            <tr>
                <th scope="row"></th>
                <th scope="row"></th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <th scope="row">{!! Form::label('nombre', 'Nombre') !!}</th>
                <td>{!! Form::text('nombre', null, [ 'class' => 'form-control', 'placeholder' => 'Farmacia Tierra Negra C.A.', 'autofocus']) !!}</td>
            </tr>
            <tr>
                <th scope="row">{!! Form::label('rif', 'RIF') !!}</th>
                <td>{!! Form::text('rif', null, [ 'class' => 'form-control', 'placeholder' => 'J-400145717']) !!}</td>
            </tr>
            <tr>
                <th scope="row">{!! Form::label('telefono', 'Telefono') !!}</th>
                <td>{!! Form::text('telefono', null, [ 'class' => 'form-control', 'placeholder' => '0261-7988326']) !!}</td>
            </tr>
            <tr>
                <th scope="row">{!! Form::label('direccion', 'Direccion') !!}</th>
                <td>{!! Form::textarea('direccion', null, [ 'class' => 'form-control', 'placeholder' => 'Calle 72 esquina av. 14A, local nro. 13a-99 sector tierra negra Maracaibo Zulia zona postal 4002', 'rows' => '2']) !!}</td>
            </tr>
        </tbody>
        </table>
        {!! Form::submit('Guardar', ['class' => 'btn btn-outline-success btn-md']) !!}
    </fieldset>
    {!! Form::close()!!} 
@endsection