@extends('layouts.model')

@section('title')
    Inventario detalle
@endsection

@section('content')
    <h1 class="h5 text-info">
        <i class="fas fa-edit"></i>
        Modificar Inventario detalle
    </h1>

    <hr class="row align-items-start col-12">

    <form action="/inventario/" method="POST" style="display: inline;">
        @csrf                       
        <button type="submit" name="Regresar" role="button" class="btn btn-outline-info btn-sm"data-placement="top"><i class="fa fa-reply">&nbsp;Regresar</i></button>
    </form>

    <br>
    <br>

    {!! Form::model($inventarioDetalle, ['route' => ['inventarioDetalle.update', $inventarioDetalle], 'method' => 'PUT']) !!}
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
                <th scope="row">{!! Form::label('inventarioDetalle', 'Codigo de Inventario') !!}</th>
                <td>{!! Form::text('codigo_conteo', null, [ 'class' => 'form-control', 'placeholder' => '', 'autofocus', 'required','disabled']) !!}</td>
            </tr>
            <tr>
                <th scope="row">{!! Form::label('codigo_articulo', 'Codigo Interno') !!}</th>
                <td>{!! Form::text('codigo_articulo', null, [ 'class' => 'form-control', 'placeholder' => '', 'autofocus', 'required','disabled']) !!}</td>
            </tr>
            <tr>
                <th scope="row">{!! Form::label('codigo_barra', 'Codigo de Barra') !!}</th>
                <td>{!! Form::text('codigo_barra', null, [ 'class' => 'form-control', 'placeholder' => '', 'autofocus', 'required','disabled']) !!}</td>
            </tr>
            <tr>
                <th scope="row">{!! Form::label('descripcion', 'Descripcion') !!}</th>
                <td>{!! Form::text('descripcion', null, [ 'class' => 'form-control', 'placeholder' => '', 'autofocus', 'required','disabled']) !!}</td>
            </tr>
            <tr>
                <th scope="row">{!! Form::label('existencia_actual', 'Existencia Sistema') !!}</th>
                <td>{!! Form::text('existencia_actual', null, [ 'class' => 'form-control', 'placeholder' => '', 'autofocus', 'required','disabled']) !!}</td>
            </tr>
            <tr>
                <th scope="row">{!! Form::label('conteo', 'Conteo') !!}</th>
                <td>{!! Form::number('conteo', null, [ 'class' => 'form-control', 'placeholder' => '', 'autofocus']) !!}</td>
            </tr>
            <tr>
                <th scope="row">{!! Form::label('re_conteo', 'Reconteo') !!}</th>
                <td>{!! Form::number('re_conteo', null, [ 'class' => 'form-control', 'placeholder' => '', 'autofocus']) !!}</td>
            </tr>
        </tbody>
        </table>
        {!! Form::submit('Guardar', ['class' => 'btn btn-outline-success btn-md']) !!}
    </fieldset>
    {!! Form::close()!!} 

    <script>
        $(document).ready(function(){
            $('[data-toggle="tooltip"]').tooltip();   
        });
        $('#exampleModalCenter').modal('show')
    </script>
@endsection