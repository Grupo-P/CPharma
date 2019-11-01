@extends('layouts.model')

@section('title', 'Crear vacante')

@section('content')
  <?php include(app_path().'\functions\config.php'); ?>

  <!-- Modal Guardar -->
  @if(session('Error'))
    <div class="modal fade" id="exampleModalCenter" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
      <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title text-danger" id="exampleModalCenterTitle">
              <i class="fas fa-exclamation-triangle text-danger"></i>
              {{ session('Error') }}
            </h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">
            <h4 class="h6">
             La vacante no pudo ser almacenada
            </h4>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-outline-success" data-dismiss="modal">Aceptar</button>
          </div>
        </div>
      </div>
    </div>
  @endif

  <h1 class="h5 text-info">
    <i class="fas fa-user-plus"></i>&nbsp;Crear vacante
  </h1>
  <hr class="row align-items-start col-12">

  <form action="/vacantes/" method="POST" style="display: inline;">  
    @csrf
    <button type="submit" name="Regresar" role="button" class="btn btn-outline-info btn-sm"data-placement="top">
      <i class="fa fa-reply">&nbsp;Regresar</i>
    </button>
  </form>
  <br/><br/>

  {!! Form::open(['route' => 'vacantes.store', 'method' => 'POST']) !!}
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
            <th scope="row">
              {!! Form::label('nombre_vacante', 'Nombre de vacante *', ['title' => 'Éste campo es requerido']) !!}
            </th>
            <td>
              {!! Form::text('nombre_vacante', null, ['class' => 'form-control', 'placeholder' => 'Analista de desarrollo I', 'autofocus', 'pattern' => '^[a-zA-ZáéíóúÁÉÍÓÚñÑ\s]+$', 'required']) !!}
            </td>
          </tr>

          <tr>
            <th scope="row">
              {!! Form::label('departamento', 'Departamento *', ['title' => 'Éste campo es requerido']) !!}
            </th>
            <td>
              {!! Form::text('departamento', null, ['class' => 'form-control', 'placeholder' => 'Tecnología', 'pattern' => '^[a-zA-ZáéíóúÁÉÍÓÚñÑ\s]+$', 'required']) !!}
            </td>
          </tr>

          <tr>
            <th scope="row">
              {!! Form::label('turno', 'Turno *', ['title' => 'Éste campo es requerido']) !!}
            </th>
            <td>
              {!! Form::text('turno', null, ['class' => 'form-control', 'placeholder' => 'Nocturno', 'pattern' => '^[a-zA-Z]{6,10}$', 'required']) !!}
            </td>
          </tr>

          <tr>
            <th scope="row">
              {!! Form::label('dias_libres', 'Dias libres *', ['title' => 'Éste campo es requerido']) !!}
            </th>
            <td>
              {!! Form::text('dias_libres', null, ['class' => 'form-control', 'placeholder' => 'Domingo y lunes', 'pattern' => '^[a-zA-ZáéíóúÁÉÍÓÚñÑ\s,]+$', 'required']) !!}
            </td>
          </tr>

          <tr>
            <th scope="row">
              {!! Form::label('sede', 'Sede *', ['title' => 'Éste campo es requerido']) !!}
            </th>
            <td>
              {!! Form::select('sede', ['FTN' => SedeFTN, 'FLL' => SedeFLL, 'FAU' => SedeFAU], null, ['class' => 'form-control', 'placeholder' => 'Seleccione una sede', 'required']) !!}
            </td>
          </tr>

          <tr>
            <th scope="row">
              {!! Form::label('cantidad', 'Cantidad de vacantes *', ['title' => 'Éste campo es requerido']) !!}
            </th>
            <td>
              {!! Form::number('cantidad', null, ['class' => 'form-control', 'placeholder' => '1', 'min' => '1', 'required']) !!}
            </td>
          </tr>

          <tr>
            <th scope="row">
              {!! Form::label('fecha_solicitud', 'Fecha de solicitud *', ['title' => 'Éste campo es requerido']) !!}
            </th>
            <td>
              {!! Form::date('fecha_solicitud', null, ['class' => 'form-control', 'required']) !!}
            </td>
          </tr>

          <tr>
            <th scope="row">
              {!! Form::label('fecha_limite', 'Fecha límite *', ['title' => 'Éste campo es requerido']) !!}
            </th>
            <td>
              {!! Form::date('fecha_limite', null, ['class' => 'form-control', 'required']) !!}
            </td>
          </tr>

          <tr>
            <th scope="row">
              {!! Form::label('comentarios', 'Comentarios *', ['title' => 'Éste campo es requerido']) !!}
            </th>
            <td>
              {!! Form::textarea('comentarios', null, [ 'class' => 'form-control', 'placeholder' => 'Detalles de la vacante', 'rows' => '3', 'required']) !!}
            </td>
          </tr>
        </tbody>
      </table>

      {!! Form::submit('Guardar', ['class' => 'btn btn-outline-success btn-md']) !!}
    </fieldset>
  {!! Form::close()!!}

  <script>
    $(document).ready(function() {
      $('[data-toggle="tooltip"]').tooltip();
    });
    $('#exampleModalCenter').modal('show');
  </script>
@endsection