@extends('layouts.model')

@section('title', 'Vacante')

@section('content')
  <h1 class="h5 text-info">
    <i class="far fa-eye"></i>
    Detalles de las vacantes
  </h1>
  <hr class="row align-items-start col-12">

  <form action="/vacantes/" method="POST" style="display: inline;">
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
        <th scope="row">Fecha de vacantes</th>
        <td>{{date('d-m-Y',strtotime($vacantes->fecha_vacantes))}}</td>
      </tr>
      <tr>
        <th scope="row">Nombre de vacantesdores</th>
        <td>{{$vacantes->vacantesdores}}</td>
      </tr>
      <tr>
        <th scope="row">Lugar de vacantes</th>
        <td>{{$vacantes->lugar}}</td>
      </tr>
      <tr>
        <th scope="row">Observaciones</th>
        <td>{{$vacantes->observaciones}}</td>
      </tr>
      <tr>
        <th scope="row">Estatus</th>
        <td>{{$vacantes->estatus}}</td>
      </tr>
      <tr>
        <th scope="row">Creado</th>
        <td>{{$vacantes->created_at}}</td>
      </tr>
      <tr>
        <th scope="row">Ultima Actualización</th>
        <td>{{$vacantes->updated_at}}</td>
      </tr>
      <tr>
        <th scope="row">Actualizado por</th>
        <td>{{$vacantes->user}}</td>
      </tr>
    </tbody>
  </table>
@endsection