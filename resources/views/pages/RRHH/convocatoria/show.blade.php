@extends('layouts.model')

@section('title', 'Mostrar Convocatoria')

@section('content')
  <h1 class="h5 text-info">
    <i class="far fa-eye"></i>
    Detalles de la convocatoria
  </h1>
  <hr class="row align-items-start col-12">

  <form action="/convocatoria/" method="POST" style="display: inline;">
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
        <th scope="row">Fecha de la convocatoria</th>
        <td>{{date('d-m-Y', strtotime($convocatoria->fecha))}}</td>
      </tr>

      <tr>
        <th scope="row">Lugar de convocatoria</th>
        <td>{{$convocatoria->lugar}}</td>
      </tr>

      <tr>
        <th scope="row">Cargos a Reclutar</th>
        <td>{{$convocatoria->cargo_reclutar}}</td>
      </tr>

      <tr>
        <th scope="row">Estatus</th>
        <td>{{$convocatoria->estatus}}</td>
      </tr>

      <tr>
        <th scope="row">Creado</th>
        <td>{{$convocatoria->created_at}}</td>
      </tr>

      <tr>
        <th scope="row">Ultima Actualización</th>
        <td>{{$convocatoria->updated_at}}</td>
      </tr>

      <tr>
        <th scope="row">Actualizado por</th>
        <td>{{$convocatoria->user}}</td>
      </tr>
    </tbody>
  </table>
@endsection