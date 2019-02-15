<!-- Correccion Orotografica: Completa 12/12/2018 -->
@extends('layouts.model')

@section('title')
    Dolar
@endsection

@section('content')
    <h1 class="h5 text-info">
        <i class="fas fa-plus"></i>
        Agregar tasa
    </h1>

    <hr class="row align-items-start col-12">

    <form action="/dolar/" method="POST" style="display: inline;">  
        @csrf                       
        <button type="submit" name="Regresar" role="button" class="btn btn-outline-info btn-sm"data-placement="top"><i class="fa fa-reply">&nbsp;Regresar</i></button>
    </form>

    <br>
    <br>

    {!! Form::open(['route' => 'dolar.store', 'method' => 'POST']) !!}
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
                <th scope="row">{!! Form::label('tasa', 'Tasa') !!}</th>
                <td>{!! Form::text('tasa', null, [ 'class' => 'form-control', 'placeholder' => 'xx.xx', 'autofocus', 'required']) !!}</td>
            </tr>
            <tr>
                <th>
                  Fecha
                </th>
                <td>
                  <input id="fecha" type="date" name="fecha" required style="width:100%; class="form-control">
                </td>
            </tr>
        </tbody>
        </table>
        {!! Form::submit('Guardar', ['class' => 'btn btn-outline-success btn-md']) !!}
    </fieldset>
    {!! Form::close()!!} 
@endsection

<style>
    * {
      box-sizing: border-box;
    }
    /*the container must be positioned relative:*/
    input {
      border: 1px solid transparent;
      background-color: #f1f1f1;
      border-radius: 5px;
      padding: 10px;
      font-size: 16px;
    }

    input[type=date] {
      background-color: #f1f1f1;
      width: 100%;
    }
</style>