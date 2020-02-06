@extends('layouts.model')

@section('title', 'Rechazar candidato')

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
              El motivo no fue almacenado
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
    <i class="fas fa-exclamation-triangle"></i>&nbsp;Rechazar candidato
  </h1>
  <hr class="row align-items-start col-12">

  <form action="/candidatos/" method="POST" style="display: inline;">  
    @csrf
    <button type="submit" name="Regresar" role="button" class="btn btn-outline-info btn-sm"data-placement="top">
      <i class="fa fa-reply">&nbsp;Regresar</i>
    </button>
  </form>

  <br/><br/>

  <form action="/candidatos/{{$candidatos->id}}" method="POST" style="display: inline;" class="form-group">
    @method('DELETE')
    @csrf
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
              {!! Form::text('nombres', $candidatos->nombres . " " . $candidatos->apellidos, [ 'class' => 'form-control', 'disabled']) !!}

              {!! Form::hidden('CandidatoId', $candidatos->id, ['id' => 'CandidatoId']) !!}
            </td>
          </tr>

          <tr>
            <th scope="row">{!! Form::label('causa', 'Causa *', ['title' => 'Este campo es requerido']) !!}</th>
            <td>
              <div class="custom-control custom-radio custom-control-inline">
                <input type="radio" class="custom-control-input" id="causa1" name="causa" value="Desertor" required>
                <label class="custom-control-label" for="causa1">Desertor</label>
              </div>

              <div class="custom-control custom-radio custom-control-inline">
                <input type="radio" class="custom-control-input" id="causa2" name="causa" value="Otras">
                <label class="custom-control-label" for="causa2">Otras</label>
              </div>
            </td>
          </tr>

          <tr>
            <th scope="row">{!! Form::label('motivo_rechazo', 'Motivo de rechazo *', ['title' => 'Este campo es requerido']) !!}</th>
            <td>{!! Form::textarea('motivo_rechazo', null, [ 'class' => 'form-control', 'placeholder' => 'Colocar detalles relevantes del rechazo', 'rows' => '3', 'required']) !!}</td>
          </tr>
        </tbody>
      </table>

      {!! Form::submit('Guardar', ['class' => 'btn btn-outline-success btn-md', 'id' => 'enviar']) !!}
    </fieldset>
  </form>

  <script>
    $(document).ready(function() {
      $('[data-toggle="tooltip"]').tooltip();
    });
    $('#exampleModalCenter').modal('show');
  </script>
@endsection