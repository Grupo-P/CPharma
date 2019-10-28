@extends('layouts.model')

@section('title')
  Candidato
@endsection

<!-- Modal Guardar -->
@if(session('Error'))
  <div class="modal fade" id="exampleModalCenter" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title text-danger" id="exampleModalCenterTitle"><i class="fas fa-exclamation-triangle text-danger"></i>{{ session('Error') }}</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <h4 class="h6">El candidato no fue almacenado</h4>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-outline-success" data-dismiss="modal">Aceptar</button>
        </div>
      </div>
    </div>
  </div>
@endif

@section('content')
  <h1 class="h5 text-info">
    <i class="fas fa-edit"></i>
    Modificar candidato
  </h1>

  <hr class="row align-items-start col-12">

  <form action="/candidatos/" method="POST" style="display: inline;">  
    @csrf
    <button type="submit" name="Regresar" role="button" class="btn btn-outline-info btn-sm"data-placement="top"><i class="fa fa-reply">&nbsp;Regresar</i></button>
  </form>

  <br/><br/>

  {!! Form::model($candidatos, ['route' => ['candidatos.update', $candidatos], 'method' => 'PUT']) !!}
    <fieldset>
      <table class="table table-borderless table-striped">
        <thead class="thead-dark">
          <tr>
            <th scope="row"></th>
            <th scope="row"></th>
          </tr>
        </thead>

        <tbody>
          <tr>
            <th scope="row">{!! Form::label('nombres', 'Nombres') !!}</th>
            <td>{!! Form::text('nombres', null, [ 'class' => 'form-control', 'placeholder' => 'Maria Raquel', 'autofocus']) !!}</td>
          </tr>
          <tr>
              <th scope="row">{!! Form::label('apellidos', 'Apellidos') !!}</th>
              <td>{!! Form::text('apellidos', null, [ 'class' => 'form-control', 'placeholder' => 'Herrera Perez']) !!}</td>
          </tr>
          <tr>
              <th scope="row">{!! Form::label('cedula', 'Cédula') !!}</th>
              <td>{!! Form::text('cedula', null, [ 'class' => 'form-control', 'placeholder' => 'V-22476796']) !!}</td>
          </tr>
          <tr>
              <th scope="row">{!! Form::label('telefono_celular', 'Teléfono celular') !!}</th>
              <td>{!! Form::text('telefono_celular', null, [ 'class' => 'form-control', 'placeholder' => '0414-1234567']) !!}</td>
          </tr>
          <tr>
              <th scope="row">{!! Form::label('telefono_habitacion', 'Teléfono de habitación') !!}</th>
              <td>{!! Form::text('telefono_habitacion', null, [ 'class' => 'form-control', 'placeholder' => '0261-1234567']) !!}</td>
          </tr>
          <tr>
              <th scope="row">{!! Form::label('correo', 'Correo') !!}</th>
              <td>{!! Form::text('correo', null, [ 'class' => 'form-control', 'placeholder' => 'mherrera@farmacia72.com.ve']) !!}</td>
          </tr>
          <tr>
              <th scope="row">{!! Form::label('observacion', 'Observaciones') !!}</th>
              <td>{!! Form::textarea('observacion', null, [ 'class' => 'form-control', 'placeholder' => 'Detalles importantes de la empresa', 'rows' => '3']) !!}</td>
          </tr>
        </tbody>
      </table>
      {!! Form::submit('Guardar', ['class' => 'btn btn-outline-success btn-md']) !!}
    </fieldset>
  {!! Form::close()!!}

  <script>
      $(document).ready(function(){
          $('[data-toggle="tooltip"]').tooltip();   
      });
      $('#exampleModalCenter').modal('show')
  </script>
@endsection