@extends('layouts.model')

@section('title', 'Mostrar Laboratorio')

@section('content')
  <h1 class="h5 text-info">
    <i class="far fa-eye"></i>
    Detalles del Laboratorio
  </h1>
  <hr class="row align-items-start col-12">

  <form action="/laboratorio/" method="POST" style="display: inline;">
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
        <th scope="row">RIF</th>
        <td>{{$laboratorio->rif}}</td>
      </tr>

      <tr>
        <th scope="row">Nombre del Laboratorio</th>
        <td>{{$laboratorio->nombre}}</td>
      </tr>

      <tr>
        <th scope="row">Dirección</th>
        <td>{{$laboratorio->direccion}}</td>
      </tr>

      <tr>
        <th scope="row">Teléfono Celular</th>
        <td>{{$laboratorio->Telefono_celular}}</td>
      </tr>
      <tr>
        <th scope="row">Teléfono Fijo</th>
        <td>{{$laboratorio->Telefono_Fijo}}</td>
      </tr>

      <tr>
        <th scope="row">Fecha de la Valoración</th>
        <td>{{$laboratorio->fecha}}</td>
      </tr>

      <tr>
        <th scope="row">Estatus</th>
        <td>{{$laboratorio->estatus}}</td>
      </tr>

      <tr>
        <th scope="row">Creado</th>
        <td>{{$laboratorio->created_at}}</td>
      </tr>

      <tr>
        <th scope="row">Ultima Actualización</th>
        <td>{{$laboratorio->updated_at}}</td>
      </tr>

      <tr>
        <th scope="row">Actualizado por</th>
        <td>{{$laboratorio->user}}</td>
      </tr>
    </tbody>
  </table>
@endsection