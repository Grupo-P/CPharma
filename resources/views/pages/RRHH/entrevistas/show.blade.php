@extends('layouts.model')

@section('title', 'Entrevista')

@section('content')
  <h1 class="h5 text-info">
    <i class="far fa-eye"></i>
    Detalles de las entrevistas
  </h1>
  <hr class="row align-items-start col-12">

  <form action="/entrevistas/" method="POST" style="display: inline;">  
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
        <th scope="row">Nombre del candidato</th>
        <td>{{$candidato->nombres . " " . $candidato->apellidos}}</td>
      </tr>

      <tr>
        <th scope="row">Vacante asociada</th>
        <td>{{$vacante->nombre_vacante}}</td>
      </tr>

      <tr>
        <th scope="row">Fecha de Entrevista</th>
        <td>{{date('d-m-Y',strtotime($entrevistas->fecha_entrevista))}}</td>
      </tr>

      <tr>
        <th scope="row">Nombre de Entrevistadores</th>
        <td>{{$entrevistas->entrevistadores}}</td>
      </tr>

      <tr>
        <th scope="row">Lugar de Entrevista</th>
        <td>{{$entrevistas->lugar}}</td>
      </tr>

      <tr>
        <th scope="row">Observaciones</th>
        <td>{{$entrevistas->observaciones}}</td>
      </tr>

      <tr>
        <th scope="row">Estatus</th>
        <td>{{$entrevistas->estatus}}</td>
      </tr>

      <tr>
        <th scope="row">Creado</th>
        <td>{{$entrevistas->created_at}}</td>
      </tr>

      <tr>
        <th scope="row">Ultima Actualización</th>
        <td>{{$entrevistas->updated_at}}</td>
      </tr>

      <tr>
        <th scope="row">Actualizado por</th>
        <td>{{$entrevistas->user}}</td>
      </tr>
    </tbody>
  </table>
@endsection