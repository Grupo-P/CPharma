@extends('layouts.model')

@section('title', 'Mostrar práctica')

@section('content')
  <h1 class="h5 text-info">
    <i class="far fa-eye"></i>
    Detalles del práctica
  </h1>
  <hr class="row align-items-start col-12">

  <form action="/practicas/" method="POST" style="display: inline;">
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
        <th scope="row">Candidato</th>
        <td>{{$candidato->nombres . " " . $candidato->apellidos}}</td>
      </tr>

      <tr>
        <th scope="row">Líder de práctica</th>
        <td>{{$practicas->lider}}</td>
      </tr>

      <tr>
        <th scope="row">Lugar</th>
        <td>{{$practicas->lugar}}</td>
      </tr>

      <tr>
        <th scope="row">Tiempo de horas</th>
        <td>{{$practicas->duracion}}</td>
      </tr>

      <tr>
        <th scope="row">Observaciones</th>
        <td>{{$practicas->observaciones}}</td>
      </tr>

      <tr>
        <th scope="row">Estatus</th>
        <td>{{$practicas->estatus}}</td>
      </tr>

      <tr>
        <th scope="row">Creado</th>
        <td>{{$practicas->created_at}}</td>
      </tr>

      <tr>
        <th scope="row">Ultima Actualización</th>
        <td>{{$practicas->updated_at}}</td>
      </tr>

      <tr>
        <th scope="row">Actualizado por</th>
        <td>{{$practicas->user}}</td>
      </tr>
    </tbody>
  </table>
@endsection