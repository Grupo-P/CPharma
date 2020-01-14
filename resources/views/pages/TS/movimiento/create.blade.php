@extends('layouts.model')

@section('title', 'Crear movimiento')

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
              El movimiento no fue almacenado
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
    <i class="fas fa-plus"></i>&nbsp;Agregar movimiento
  </h1>
  <hr class="row align-items-start col-12">

  <form action="/movimientos/" method="GET" style="display: inline;">  
    <input type="hidden" name="tasa_ventas_id" value="{{$_GET["tasa_ventas_id"]}}">
    <button type="submit" role="button" class="btn btn-outline-info btn-sm"data-placement="top">
      <i class="fa fa-reply">&nbsp;Regresar</i>
    </button>
  </form>

  <br/><br/>

  {!! Form::open(['route' => 'movimientos.store', 'method' => 'POST', 'id' => 'crear_movimientos', 'class' => 'form-group']) !!}
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
            <th scope="row">{!! Form::label('movimiento', 'Tipo de movimiento *', ['title' => 'Este campo es requerido']) !!}</th>
            <td>
              <div class="custom-control custom-radio custom-control-inline">
                <input type="radio" class="custom-control-input" id="movimiento1" name="movimiento" value="Ingreso" required>
                <label class="custom-control-label" for="movimiento1">Ingreso</label>
              </div>

              <div class="custom-control custom-radio custom-control-inline">
                <input type="radio" class="custom-control-input" id="movimiento2" name="movimiento" value="Egreso">
                <label class="custom-control-label" for="movimiento2">Egreso</label>
              </div>
            </td>
          </tr>

          <tr>
            <th scope="row">{!! Form::label('monto', 'Monto *', ['title' => 'Este campo es requerido']) !!}</th>
            <td>
              <input type="number" name="monto" min="1" step="0.01" class="form-control" placeholder="2500.55" required autofocus>
              <input type="hidden" name="tasa_ventas_id" value="{{$_GET["tasa_ventas_id"]}}">
            </td>
          </tr>

          <tr>
            <th scope="row">{!! Form::label('concepto', 'Concepto *', ['title' => 'Este campo es requerido']) !!}</th>
            <td>{!! Form::text('concepto', null, [ 'class' => 'form-control', 'placeholder' => 'Pago de proveedores', 'required']) !!}</td>
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