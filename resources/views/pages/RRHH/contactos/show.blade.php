@extends('layouts.model')

@section('title', 'Mostrar contacto')

@section('content')
  <h1 class="h5 text-info">
    <i class="far fa-eye"></i>
    Detalles del contacto
  </h1>
  <hr class="row align-items-start col-12">

  <form action="/contactos/" method="POST" style="display: inline;">
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
        <th scope="row">Nombre</th>
        <td>{{$contactos->nombre}}</td>
      </tr>

      <tr>
        <th scope="row">Apellido</th>
        <td>{{$contactos->apellido}}</td>
      </tr>

      <tr>
        <th scope="row">Teléfono</th>
        <td>{{$contactos->telefono}}</td>
      </tr>

      <tr>
        <th scope="row">Correo</th>
        <td>{{$contactos->correo}}</td>
      </tr>

      <tr>
        <th scope="row">Cargo</th>
        <td>{{$contactos->cargo}}</td>
      </tr>

      <tr>
        <th scope="row">Estatus</th>
        <td>{{$contactos->estatus}}</td>
      </tr>

      <tr>
        <th scope="row">Creado</th>
        <td>{{$contactos->created_at}}</td>
      </tr>

      <tr>
        <th scope="row">Ultima Actualización</th>
        <td>{{$contactos->updated_at}}</td>
      </tr>

      <tr>
        <th scope="row">Actualizado por</th>
        <td>{{$contactos->user}}</td>
      </tr>
    </tbody>
  </table>
@endsection