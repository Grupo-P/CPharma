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
        <th scope="row">Nombres</th>
        <td>{{$practicas->nombres}}</td>
      </tr>

      <tr>
        <th scope="row">Apellidos</th>
        <td>{{$practicas->apellidos}}</td>
      </tr>

      <tr>
        <th scope="row">Cédula</th>
        <td>{{$practicas->cedula}}</td>
      </tr>

      <tr>
        <th scope="row">Teléfono celular</th>
        <td>{{$practicas->telefono_celular}}</td>
      </tr>

      <tr>
        <th scope="row">Teléfono de habitación</th>
        <td>{{$practicas->telefono_habitacion}}</td>
      </tr>

      <tr>
        <th scope="row">Correo</th>
        <td>{{$practicas->correo}}</td>
      </tr>

      <tr>
        <th scope="row">Como nos contactó</th>
        <td>{{$practicas->como_nos_contacto}}</td>
      </tr>

      <tr>
        <th scope="row">Tipo de relación</th>
        <td>{{$practicas->tipo_relacion}}</td>
      </tr>

      <tr>
        <th scope="row">Relaciones laborales</th>
        <td>{{$practicas->relaciones_laborales}}</td>
      </tr>

      <tr>
        <th scope="row">Dirección</th>
        <td>{{$practicas->direccion}}</td>
      </tr>

      <tr>
        <th scope="row">Experiencia laboral</th>
        <td>{{$practicas->experiencia_laboral}}</td>
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