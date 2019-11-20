@extends('layouts.model')

@section('title', 'Crear Convocatoria')

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
              La convocatoria no fue almacenada
            </h4>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-outline-success" data-dismiss="modal">Aceptar</button>
          </div>
        </div>
      </div>
    </div>
  @endif

  <!-- Modal convocatoria duplicada -->
  @if(session('Error1'))
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
              La convocatoria no fue almacenada
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
    <i class="fas fa-plus"></i>&nbsp;Agregar Convocatoria
  </h1>
  <hr class="row align-items-start col-12">

  <form action="/convocatoria/" method="POST" style="display: inline;">  
    @csrf
    <button type="submit" name="Regresar" role="button" class="btn btn-outline-info btn-sm"data-placement="top">
      <i class="fa fa-reply">&nbsp;Regresar</i>
    </button>
  </form>

  <br/><br/>

  {!! Form::open(['route' => 'convocatoria.store', 'method' => 'POST', 'id' => 'crear_candidato']) !!}
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
              {!! Form::label('fecha', 'Fecha de la convocatoria *', ['title' => 'Este campo es requerido']) !!}
            </th>
            <td>
              {!! Form::date('fecha', null, [ 'class' => 'form-control', 'autofocus', 'required']) !!}
            </td>
          </tr>

           <tr>
            <th scope="row">{!! Form::label('lugar', 'Lugar de convocatoria *', ['title' => 'Este campo es requerido']) !!}</th>
            <td>
              {!! Form::text('lugar', null, [ 'class' => 'form-control', 'placeholder' => 'Delicias', 'required']) !!}
            </td>
          </tr>
           <tr>
            <th scope="row">
              {!! Form::label('cargo_reclutar', 'Cargos a Reclutar *', ['title' => 'Este campo es requerido']) !!}
            </th>
            <td>
              {!! Form::text('cargo_reclutar', null, [ 'class' => 'form-control', 'placeholder' => 'Aprendiz de Farmacia', 'required']) !!}
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