@extends('layouts.model')

@section('title', 'Mostrar fase')

@section('content')
  <h1 class="h5 text-info">
    <i class="far fa-eye"></i>
    Detalles del fase
  </h1>
  <hr class="row align-items-start col-12">

  <form action="/fases/" method="POST" style="display: inline;">
    @csrf
    <button type="submit" name="Regresar" role="button" class="btn btn-outline-info btn-sm"data-placement="top">
      <i class="fa fa-reply">&nbsp;Regresar</i>
    </button>
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
        <th scope="row">Nombre de la fase</th>
        <td>{{$fases->nombre_fase}}</td>
      </tr>

      <tr>
        <th scope="row">Estatus</th>
        <td>{{$fases->estatus}}</td>
      </tr>

      <tr>
        <th scope="row">Creado</th>
        <td>{{$fases->created_at}}</td>
      </tr>

      <tr>
        <th scope="row">Ultima Actualización</th>
        <td>{{$fases->updated_at}}</td>
      </tr>

      <tr>
        <th scope="row">Actualizado por</th>
        <td>{{$fases->user}}</td>
      </tr>
    </tbody>
  </table>
@endsection