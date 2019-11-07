@extends('layouts.model')

@section('title', 'Mostrar referencia lab.')

@section('content')
  <h1 class="h5 text-info">
    <i class="far fa-eye"></i>
    Detalles de la empresa
  </h1>
  <hr class="row align-items-start col-12">

  <form action="/empresaReferencias/" method="POST" style="display: inline;">
    @csrf
    <button type="submit" name="Regresar" role="button" class="btn btn-outline-info btn-sm"data-placement="top"><i class="fa fa-reply">&nbsp;Regresar</i></button>
  </form>

  <br/><br/>

  <table class="table table-borderless table-striped">
    <thead class="thead-dark">
      <tr>
        <th scope="row"></th>
        <th scope="row"></th>
      </tr>
    </thead>

    <tbody>
      <tr>
        <th scope="row">Nombre de la empresa</th>
        <td>{{$empresaReferencias->nombre_empresa}}</td>
      </tr>

      <tr>
        <th scope="row">Teléfono</th>
        <td>{{$empresaReferencias->telefono}}</td>
      </tr>

      <tr>
        <th scope="row">Correo</th>
        <td>{{$empresaReferencias->correo}}</td>
      </tr>

      <tr>
        <th scope="row">Dirección</th>
        <td>{{$empresaReferencias->direccion}}</td>
      </tr>

      <tr>
        <th scope="row">Estatus</th>
        <td>{{$empresaReferencias->estatus}}</td>
      </tr>

      <tr>
        <th scope="row">Creado</th>
        <td>{{$empresaReferencias->created_at}}</td>
      </tr>

      <tr>
        <th scope="row">Ultima Actualización</th>
        <td>{{$empresaReferencias->updated_at}}</td>
      </tr>

      <tr>
        <th scope="row">Actualizado por</th>
        <td>{{$empresaReferencias->user}}</td>
      </tr>
    </tbody>
  </table>
@endsection