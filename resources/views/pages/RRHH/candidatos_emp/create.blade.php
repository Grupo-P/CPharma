@extends('layouts.model')

@section('title', 'Crear fase #4')

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
              La empresa no fue asignada
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
    <i class="fas fa-plus"></i>&nbsp;Agregar Fase #4
  </h1>
  <hr class="row align-items-start col-12">

  <form action="/procesos_referencias/" method="GET" style="display: inline;">
    <input type="hidden" name="CandidatoId" value="{{$candidato->id}}">
    <input type="hidden" name="CandidatoFaseId" value="{{$candidato_fase->id}}">
    <button type="submit" role="button" class="btn btn-outline-info btn-sm"data-placement="top">
      <i class="fa fa-reply">&nbsp;Regresar</i>
    </button>
  </form>

  <br/><br/>

  {!! Form::open(['route' => 'procesos_referencias.store', 'method' => 'POST', 'id' => 'crear_candidato', 'class' => 'form-group']) !!}
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

              {!! Form::hidden('CandidatoFaseId', $candidato_fase->id, ['id' => 'CandidatoFaseId']) !!}
            </td>
          </tr>

          <tr>
            <th scope="row">
              {!! Form::label('EmpresaId', 'Empresa asociada *', ['title' => 'Éste campo es requerido']) !!}
            </th>
            <td>
              <select name="EmpresaId" id="EmpresaId" class="form-control" required autofocus>
                <option value="">Seleccione una opción</option>

                <?php
                  foreach ($empresa_ref as $emp) {
                    if($emp->estatus == "ACTIVO") {
                ?>

                <option value="{{$emp->id}}">{{$emp->nombre_empresa}}</option>

                <?php
                    }//if
                  }//foreach
                ?>
              </select>
            </td>
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