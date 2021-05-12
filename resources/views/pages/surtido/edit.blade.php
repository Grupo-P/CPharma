@extends('layouts.model')

@section('title')
    Surtido de gavetas
@endsection

@section('content')
    <h1 class="h5 text-info">
        <i class="fas fa-edit"></i>
        Anular surtido
    </h1>

    <hr class="row align-items-start col-12">

    <a href="/surtido" class="btn btn-outline-info btn-sm">
        <i class="fa fa-reply"></i> Regresar
    </a>

    <br>
    <br>

    {!! Form::model($surtido, ['route' => ['surtido.update', $surtido], 'method' => 'PUT']) !!}
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
                    <th scope="row">{!! Form::label('control', 'Control') !!}</th>
                    <td>{!! Form::text('control', null, [ 'class' => 'form-control', 'placeholder' => '', 'autofocus','disabled']) !!}</td>
                </tr>
                <tr>
                <th scope="row">{!! Form::label('motivo_anulado', 'Motivo de anulacion') !!}</th>
                <td>{!! Form::textarea('motivo_anulado', null, [ 'class' => 'form-control', 'placeholder' => 'Detalles importantes', 'rows' => '3', 'value' => '', 'required' => 'required']) !!}</td>
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
