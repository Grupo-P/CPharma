@extends('layouts.model')

@section('title', 'Crear candidato')

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
              Las pruebas no fueron asignadas
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
    <i class="fas fa-plus"></i>&nbsp;Agregar Fase #1
  </h1>
  <hr class="row align-items-start col-12">

  <form action="/candidatos/" method="POST" style="display: inline;">  
    @csrf
    <button type="submit" name="Regresar" role="button" class="btn btn-outline-info btn-sm"data-placement="top">
      <i class="fa fa-reply">&nbsp;Regresar</i>
    </button>
  </form>

  <br/><br/>

  {!! Form::open(['route' => 'candidatos.store', 'method' => 'POST', 'id' => 'crear_candidato']) !!}
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
            <th scope="row">{!! Form::label('nombres', 'Nombre del candidato') !!}</th>

            <td>
              {!! Form::text('nombres', $candidato->nombres . " " . $candidato->apellidos, [ 'class' => 'form-control', 'disabled']) !!}

              {!! Form::hidden('CandidatoId', $candidato->id, ['id' => 'CandidatoId']) !!}
            </td>
          </tr>

          <tr>
            <th scope="row">
              {!! Form::label('PruebaId', 'Prueba asociada *', ['title' => 'Éste campo es requerido']) !!}
            </th>
            <td>
              <select name="PruebaId" id="PruebaId" class="form-control" required>
                <option value="">Seleccione una opción</option>

                <?php
                  foreach ($pruebas as $prueba) {
                    if($prueba->estatus == "ACTIVO") {
                ?>

                <option value="{{$prueba->id}}">{{
                    $prueba->nombre_prueba 
                    . " - " . $prueba->tipo_prueba
                  }}
                </option>

                <?php
                    }//if
                  }//foreach
                ?>
              </select>
            </td>
          </tr>

          <tr>
            <th scope="row">{!! Form::label('experiencia_laboral', 'Experiencia laboral') !!}</th>
            <td>{!! Form::textarea('experiencia_laboral', null, [ 'class' => 'form-control', 'placeholder' => 'Experiencia laboral previa del candidato', 'rows' => '3']) !!}</td>
          </tr>

          <tr>
            <th scope="row">{!! Form::label('direccion', 'Dirección *', ['title' => 'Este campo es requerido']) !!}</th>
            <td>{!! Form::textarea('direccion', null, [ 'class' => 'form-control', 'placeholder' => 'Av. 15 Delicias con calle 72', 'rows' => '3', 'required']) !!}</td>
          </tr>

          <tr>
            <th scope="row">{!! Form::label('observaciones', 'Observaciones') !!}</th>
            <td>{!! Form::textarea('observaciones', null, [ 'class' => 'form-control', 'placeholder' => 'Detalles importantes del candidato', 'rows' => '3']) !!}</td>
          </tr>
        </tbody>
      </table>

      {!! Form::submit('Guardar', ['class' => 'btn btn-outline-success btn-md', 'id' => 'enviar']) !!}
    </fieldset>
  {!! Form::close()!!}

  <script>
    $(document).ready(function() {
      $('[data-toggle="tooltip"]').tooltip();
    });
    $('#exampleModalCenter').modal('show');
  </script>
@endsection