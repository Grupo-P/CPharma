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
        <th scope="row">Sede</th>
        <td>{{$vacantes->sede}}</td>
      </tr>

      <tr>
        <th scope="row">Nombre de la vacante</th>
        <td>{{$vacantes->nombre_vacante}}</td>
      </tr>

      <tr>
        <th scope="row">Departamento</th>
        <td>{{$vacantes->departamento}}</td>
      </tr>

      <tr>
        <th scope="row">Turno</th>
        <td>{{$vacantes->turno}}</td>
      </tr>

      <tr>
        <th scope="row">Dias libres</th>
        <td>{{$vacantes->dias_libres}}</td>
      </tr>

      <tr>
        <th scope="row">Nivel de urgencia</th>
        <td>{{$vacantes->nivel_urgencia}}</td>
      </tr>

      <tr>
        <th scope="row">Fecha de solicitud</th>
        <td>{{date('d-m-Y',strtotime($vacantes->fecha_solicitud))}}</td>
      </tr>

      <tr>
        <th scope="row">Fecha límite</th>
        <td>{{date('d-m-Y',strtotime($vacantes->fecha_limite))}}</td>
      </tr>

      <tr>
        <th scope="row">Cantidad requerida</th>
        <td>{{$vacantes->cantidad}}</td>
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