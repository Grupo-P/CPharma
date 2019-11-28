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
        <th scope="row">Nombres</th>
        <td>{{$fases->nombres}}</td>
      </tr>

      <tr>
        <th scope="row">Apellidos</th>
        <td>{{$fases->apellidos}}</td>
      </tr>

      <tr>
        <th scope="row">Cédula</th>
        <td>{{$fases->cedula}}</td>
      </tr>

      <tr>
        <th scope="row">Teléfono celular</th>
        <td>{{$fases->telefono_celular}}</td>
      </tr>

      <tr>
        <th scope="row">Teléfono de habitación</th>
        <td>{{$fases->telefono_habitacion}}</td>
      </tr>

      <tr>
        <th scope="row">Correo</th>
        <td>{{$fases->correo}}</td>
      </tr>

      <tr>
        <th scope="row">Como nos contactó</th>
        <td>{{$fases->como_nos_contacto}}</td>
      </tr>

      <tr>
        <th scope="row">Tipo de relación</th>
        <td>{{$fases->tipo_relacion}}</td>
      </tr>

      <tr>
        <th scope="row">Relaciones laborales</th>
        <td>{{$fases->relaciones_laborales}}</td>
      </tr>

      <tr>
        <th scope="row">Dirección</th>
        <td>{{$fases->direccion}}</td>
      </tr>

      <tr>
        <th scope="row">Experiencia laboral</th>
        <td>{{$fases->experiencia_laboral}}</td>
      </tr>

      <tr>
        <th scope="row">Observaciones</th>
        <td>{{$fases->observaciones}}</td>
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