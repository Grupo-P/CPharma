<!-- Correccion Orotografica: Completa 12/12/2018 -->
@extends('layouts.model')

@section('title')
    Proveedor
@endsection

@section('content')
    <h1 class="h5 text-info">
        <i class="fas fa-plus"></i>
        Agregar proveedor
    </h1>

    <hr class="row align-items-start col-12">

    <form action="/proveedor/" method="POST" style="display: inline;">  
        @csrf                       
        <button type="submit" name="Regresar" role="button" class="btn btn-outline-info btn-sm"data-placement="top"><i class="fa fa-reply">&nbsp;Regresar</i></button>
    </form>

    <br>
    <br>

    {!! Form::open(['route' => 'proveedor.store', 'method' => 'POST']) !!}
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
                <td>{!! Form::text('nombre', null, [ 'class' => 'form-control', 'placeholder' => 'Pedro', 'autofocus']) !!}</td>
            </tr>
            <tr>
                <th scope="row">{!! Form::label('apellido', 'Apellido') !!}</th>
                <td>{!! Form::text('apellido', null, [ 'class' => 'form-control', 'placeholder' => 'Perez']) !!}</td>
            </tr>
            <tr>
                <th scope="row">{!! Form::label('telefono', 'Telefono') !!}</th>
                <td>{!! Form::text('telefono', null, [ 'class' => 'form-control', 'placeholder' => '04XX-7988326']) !!}</td>
            </tr>
            <tr>
                <th scope="row">{!! Form::label('correo', 'Correo') !!}</th>
                <td>{!! Form::text('correo', null, [ 'class' => 'form-control', 'placeholder' => 'pperez@empresa.com']) !!}</td>
            </tr>
            <tr>
                <th scope="row">{!! Form::label('cargo', 'Cargo') !!}</th>
                <td>{!! Form::text('cargo', null, [ 'class' => 'form-control', 'placeholder' => 'Proveedor']) !!}</td>
            </tr>
            <tr>
                <th scope="row">{!! Form::label('empresa', 'Empresa') !!}</th>
                <td>{!! Form::text('empresa', null, [ 'class' => 'form-control', 'placeholder' => 'La Empresa C.A']) !!}</td>
            </tr>
        </tbody>
        </table>
        {!! Form::submit('Guardar', ['class' => 'btn btn-outline-success btn-md']) !!}
    </fieldset>
    {!! Form::close()!!}
@endsection