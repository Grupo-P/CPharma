@extends('layouts.contabilidad')

@section('title', 'Registro de pagos en efectivo')

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
              El pago en efectivo no fue almacenado
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
    <i class="fas fa-plus"></i>&nbsp;

    @if($request->get('tipo') == 'movimiento')
      Agregar movimiento
    @endif

    @if($request->get('tipo') == 'proveedores')
      Agregar pago a proveedores
    @endif
  </h1>
  <hr class="row align-items-start col-12">

  <a href="/efectivo" class="btn btn-outline-info btn-sm">
    <i class="fa fa-reply"></i> Regresar
  </a>

  <br/><br/>

  {!! Form::open(['route' => 'efectivo.store', 'method' => 'POST', 'id' => 'crear_movimientos', 'class' => 'form-group']) !!}
    <fieldset>
      <table class="table table-borderless table-striped">
        <thead class="thead-dark">
          <tr>
            <th scope="row"></th>
            <th scope="row"></th>
          </tr>
        </thead>

        <tbody>
          @if($request->get('tipo') == 'movimiento')
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

                <div class="custom-control custom-radio custom-control-inline">
                  <input type="radio" class="custom-control-input" id="movimiento3" name="movimiento" value="Diferido">
                  <label class="custom-control-label" for="movimiento3">Diferido</label>
                </div>
              </td>
            </tr>
          @endif

          @if($request->get('tipo') == 'proveedores')
            <input type="hidden" name="movimiento" value="Egreso">
          @endif

          <tr>
            <th scope="row">{!! Form::label('monto', 'Monto *', ['title' => 'Este campo es requerido']) !!}</th>
            <td>
              <input type="number" name="monto" min="1" step="0.01" class="form-control" placeholder="2500,55" required autofocus>
            </td>
          </tr>

          @if($request->get('tipo') == 'movimiento')
            <tr>
              <th scope="row">{!! Form::label('id_cuenta', 'Plan de cuentas *', ['title' => 'Este campo es requerido']) !!}</th>
              <td>
                <select required name="id_cuenta" class="form-control">
                  <option value=""></option>
                    @foreach($cuentas as $cuenta)
                      <option value="{{ $cuenta->id }}">{{ $cuenta->nombre }}</option>
                    @endforeach
                </select>
              </td>
            </tr>

            <tr>
                <th scope="row">{!! Form::label('autorizado_por', 'Autorizado por *', ['title' => 'Este campo es requerido']) !!}</th>
                <td>
                    <input type="text" required class="form-control" name="autorizado_por">
                </td>
            </tr>
          @endif

          @if($request->get('tipo') == 'proveedores')
            <tr>
                <th scope="row"><label for="nombre_proveedor">Nombre del proveedor *</label></th>
                <td>
                    <input autofocus class="form-control" type="text" id="proveedores">
                    <input type="hidden" name="id_proveedor">
                </td>
            </tr>

            <tr>
                <th scope="row"><label for="moneda">Moneda</label></th>
                <td>
                    <input id="moneda" readonly class="form-control" type="text" name="moneda">
                </td>
            </tr>

            <tr>
                <th scope="row"><label for="saldo">Saldo</label></th>
                <td>
                    <input readonly class="form-control" type="text" name="saldo">
                </td>
            </tr>

            <tr class="append">
                <th scope="row"><label for="tasa">Tasa *</label></th>
                <td>
                    <input class="form-control" step="0.01" disabled type="number" name="tasa">
                </td>
            </tr>
          @endif

          <tr>
            <th scope="row">{!! Form::label('concepto', 'Concepto *', ['title' => 'Este campo es requerido']) !!}</th>
            <td>{!! Form::text('concepto', null, [ 'class' => 'form-control', 'placeholder' => '', 'required', 'title' => 'Debe colocar mínimo 10 caracteres y máximo 100', 'pattern' => '.{10,100}']) !!}</td>
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

@section('scriptsHead')
    @if($request->get('tipo') == 'proveedores')
        <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
        <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>

        <script>
            $(document).ready(function() {
                var proveedoresJson = {!! json_encode($proveedores) !!};

                bancario = true;

                $('#proveedores').autocomplete({
                    source: proveedoresJson,
                    autoFocus: true,
                    select: function (event, ui) {
                        $('[name=id_proveedor]').val(ui.item.id);
                        $('[name=moneda]').val(ui.item.moneda);

                        $.ajax({
                            type: 'GET',
                            url: '/efectivo/create',
                            data: {
                                id_proveedor: $('[name=id_proveedor]').val()
                            },
                            success: function (response) {
                                $('[name=saldo]').val(response.saldo);

                                $('[name=tasa]').attr('min', response.min);
                                $('[name=tasa]').attr('max', response.max);

                                if (response.tasa) {
                                    $('[name=tasa]').attr('disabled', false);
                                    $('[name=tasa]').attr('required', true);
                                } else {
                                    $('[name=tasa]').attr('disabled', true);
                                    $('[name=tasa]').attr('required', false);
                                }
                            }
                        })
                    }
                });

                $('form').submit(function (event) {
                    resultado = proveedoresJson.find(elemento => elemento.label == $('#proveedores').val());

                    if (!resultado) {
                        event.preventDefault();
                        alert('Debe seleccionar un proveedor válido');
                        bancario = false;
                    }
                });
            });
        </script>
    @endif
@endsection

