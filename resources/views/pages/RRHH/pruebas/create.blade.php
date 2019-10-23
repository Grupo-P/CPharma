@extends('layouts.model')

@section('title')
  Pruebas
@endsection

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
             La pruebas no fueron almacenada
            </h4>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-outline-success" data-dismiss="modal">Aceptar</button>
          </div>
        </div>
      </div>
    </div>
  @endif

  <h1 class="h5 text-info"><i class="fas fa-plus"></i>&nbsp;Agregar pruebas</h1>
  <hr class="row align-items-start col-12">

  <form action="/pruebas/" method="POST" style="display: inline;">  
    @csrf
    <button type="submit" name="Regresar" role="button" class="btn btn-outline-info btn-sm"data-placement="top">
      <i class="fa fa-reply">&nbsp;Regresar</i>
    </button>
  </form>

  <br/><br/>

  {!! Form::open(['route' => 'pruebas.store', 'method' => 'POST']) !!}
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
            <th scope="row">{!! Form::label('tipo_prueba', 'Tipo de Prueba') !!}</th>
            <td>
              {!! Form::select('tipo_prueba', [
                'Proyectiva' => 'Proyectiva', 
                'Psicométrica' => 'Psicométrica',
              
              ], null, ['class' => 'form-control']) !!}
            </td>
          </tr>

          <tr>
            <th scope="row">{!! Form::label('nombre_prueba', 'Nombre de Prueba') !!}</th>
            <td>
              {!! Form::select('nombre_prueba', [
                'Arbol/Casa/Persona' => 'Arbol/Casa/Persona',
                'Figura humana' => 'Figura humana', 
                'Figura humana bajo la luvia' => 'Figura humana bajo la lluvia',
                'Wartegg' => 'Wartegg',
                'Xando y Vels' => 'Xando y Vels',
                'Kostick' => 'Kostick',
                'Therman Merrill' => 'Therman Merrill',
                '16PF' => '16PF',
                'Purdue' => 'Purdue',
                'Test Domino48' => 'Test Domino48',
                'Zavic' => 'Zavic',
              ], null, ['class' => 'form-control']) !!}
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