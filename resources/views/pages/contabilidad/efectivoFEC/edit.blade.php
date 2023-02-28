@extends('layouts.contabilidad')

@section('title', 'Modificar pago en efectivo dólares FEC')

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
    <i class="fas fa-edit"></i>
    Modificar pago en efectivo dólares FEC
  </h1>
  <hr class="row align-items-start col-12">

  <a href="/efectivoFEC" class="btn btn-outline-info btn-sm">
    <i class="fa fa-reply"></i> Regresar
  </a>

  <br/><br/>

  {!! Form::model($pago, ['route' => ['efectivoFEC.update', $pago], 'method' => 'PUT', 'class' => 'form-group']) !!}
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
            <th scope="row">{!! Form::label('diferido', 'Diferido') !!}</th>
            <td>
              <input type="text" class="form-control" value="{{number_format($pago->diferido, 2, ',', '.')}}" disabled>
              <input type="hidden" name="monto" value="{{$pago->diferido}}">
              <input type="hidden" name="tasa_ventas_id" value="{{$pago->tasa_ventas_id}}">
            </td>
          </tr>

          <tr>
            <th scope="row">{!! Form::label('concepto', 'Concepto *', ['title' => 'Este campo es requerido']) !!}</th>
            <td>{!! Form::text('concepto', null, [ 'class' => 'form-control', 'placeholder' => 'Pago de proveedores', 'required', 'title' => 'Debe colocar mínimo 10 caracteres y máximo 100', 'pattern' => '.{10,100}']) !!}</td>
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
