<!-- Correccion Orotografica: Completa 12/12/2018 -->
@extends('layouts.model')

@section('title')
    Usuario
@endsection

@section('content')
    <h1 class="h5 text-info">
        <i class="fas fa-edit"></i>
        Modificar usuario
    </h1>

    <hr class="row align-items-start col-12">

    <form action="/usuario/" method="POST" style="display: inline;">  
        @csrf                       
        <button type="submit" name="Regresar" role="button" class="btn btn-outline-info btn-sm"data-placement="top"><i class="fa fa-reply">&nbsp;Regresar</i></button>
    </form>

    <br>
    <br>

    {!! Form::model($usuario, ['route' => ['usuario.update', $usuario], 'method' => 'PUT']) !!}
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
                <th scope="row">{!! Form::label('name', 'Nombre') !!}</th>
                <td>{!! Form::text('name', null, [ 'class' => 'form-control', 'placeholder' => 'Pedro Perez', 'autofocus']) !!}</td>
            </tr>
            <tr>
                <th scope="row">{!! Form::label('email', 'Correo') !!}</th>
                <td>{!! Form::text('email', null, [ 'class' => 'form-control', 'placeholder' => 'pperez@empresa.com']) !!}</td>
            </tr>
            <tr>
                <th scope="row">{!! Form::label('role', 'Rol') !!}</th>
                <td>
                    <select name="role" class="form-control">
                        <option>USUARIO</option>
                        <option>SUPERVISOR</option>
                        <option>MASTER</option>
                    </select>
                </td>
            </tr>
            <tr>
                <th scope="row">{!! Form::label('password', 'Contraseña') !!}</th>
                <td>{!! Form::text('password', null, [ 'class' => 'form-control', 'placeholder' => '******', 'rows' => '2']) !!}</td>
            </tr>
        </tbody>
        </table>
        {!! Form::submit('Guardar', ['class' => 'btn btn-outline-success btn-md']) !!}
    </fieldset>
    {!! Form::close()!!} 
@endsection