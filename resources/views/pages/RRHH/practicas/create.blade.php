@extends('layouts.model')

@section('title', 'Crear fase #3')

@section('content')
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
             La práctica no fue almacenada
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
    <i class="fas fa-plus"></i>&nbsp;Agregar práctica
  </h1>
  <hr class="row align-items-start col-12">

  <form action="/procesos_candidatos/" method="POST" style="display: inline;">  
    @csrf
    <button type="submit" name="Regresar" role="button" class="btn btn-outline-info btn-sm"data-placement="top">
      <i class="fa fa-reply">&nbsp;Regresar</i>
    </button>
  </form>
  <br/><br/>

  {!! Form::open(['route' => 'practicas.store', 'method' => 'POST', 'class' => 'form-group']) !!}
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
              <label for="nombres">Nombre del candidato</label>
            </th>

            <td>
              <input type="text" id="nombres" name="nombres" class="form-control" value="{{$candidato->nombres . ' ' . $candidato->apellidos}}" disabled>
              
              <input type="hidden" name="CandidatoId" id="CandidatoId" value="{{$candidato->id}}">

              <input type="hidden" name="CandidatoFaseId" id="CandidatoFaseId" value="{{$candidato_fase->id}}">
            </td>
          </tr>

          <tr>
            <th scope="row">
              {!! Form::label('lider', 'Líder de práctica *', ['title' => 'Este campo es requerido']) !!}
            </th>
            <td>
              {!! Form::text('lider', null, [ 'class' => 'form-control', 'placeholder' => 'Maria Raquel', 'pattern' => '^[a-zA-ZáéíóúÁÉÍÓÚñÑ\s\']+$', 'autofocus', 'required']) !!}
            </td>
          </tr>

          <tr>
            <?php
              $sedes = compras\Sede::where('siglas', '<>', 'GP')
              ->where('siglas', '<>', 'MC')
              ->get();
            ?>

            <th scope="row">
              {!! Form::label('lugar', 'Lugar *', ['title' => 'Éste campo es requerido']) !!}
            </th>
            <td>
              <select name="lugar" id="lugar" class="form-control" required>
                <option value="">Seleccione una opción</option>

                <?php
                  foreach ($sedes as $sede) {
                ?>

                <option value="{{$sede->siglas}}">{{$sede->siglas}}</option>

                <?php
                  }//foreach
                ?>
              </select>
            </td>
          </tr>

          <tr>
            <th scope="row">
              {!! Form::label('duracion', 'Duración *', ['title' => 'Este campo es requerido']) !!}
            </th>
            <td>
              <input type="number" name="duracion" id="duracion" placeholder="0,5" min="0" max="14" step="0.1" class="form-control" required>
            </td>
          </tr>

          <tr>
            <th scope="row">{!! Form::label('observaciones', 'Observaciones') !!}</th>
            <td>
              {!! Form::textarea('observaciones', null, [ 'class' => 'form-control', 'placeholder' => 'Detalles del entrevistado', 'rows' => '3']) !!}
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