@extends('layouts.model')

@section('title')
    Inventario
@endsection

@section('content')
    <h1 class="h5 text-info">
        <i class="fas fa-edit"></i>
        Anular Inventario
    </h1>

    <hr class="row align-items-start col-12">

    <form action="/inventario/" method="POST" style="display: inline;">
        @csrf                       
        <button type="submit" name="Regresar" role="button" class="btn btn-outline-info btn-sm"data-placement="top"><i class="fa fa-reply">&nbsp;Regresar</i></button>
    </form>

    <br>
    <br>

    {!! Form::model($inventario, ['route' => ['inventario.update', $inventario], 'method' => 'PUT']) !!}
    <fieldset>
        {!! Form::hidden('action','Anular') !!}
        <table class="table table-borderless table-striped">
        <thead class="thead-dark">
            <tr>
                <th scope="row"></th>
                <th scope="row"></th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <th scope="row">{!! Form::label('codigo', 'Codigo de Inventario') !!}</th>
                <td>{!! Form::text('codigo', null, [ 'class' => 'form-control', 'placeholder' => '', 'autofocus','disabled']) !!}</td>
            </tr>
            <tr>
                <th scope="row">{!! Form::label('origen_conteo', 'Origen del conteo') !!}</th>
                <td>{!! Form::text('origen_conteo', null, [ 'class' => 'form-control', 'placeholder' => '', 'autofocus','disabled']) !!}</td>
            </tr>
            <tr>
                <th scope="row">{!! Form::label('motivo_conteo', 'Motivo del conteo') !!}</th>
                <td>{!! Form::text('motivo_conteo', null, [ 'class' => 'form-control', 'placeholder' => '', 'autofocus','disabled']) !!}</td>
            </tr>
            <tr>
            <th scope="row">{!! Form::label('comentario', 'Motivo de anulacion') !!}</th>
            <td>{!! Form::textarea('comentario', null, [ 'class' => 'form-control', 'placeholder' => 'Detalles importantes', 'rows' => '3', 'value' => '', 'required' => 'required']) !!}</td>
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