@extends('layouts.contabilidad')

@section('title', 'Registro de pagos en efectivo dólares FEC')

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
      Agregar movimiento dólares FEC
    @endif

    @if($request->get('tipo') == 'proveedores')
      Agregar pago a proveedores dólares FEC
    @endif
  </h1>
  <hr class="row align-items-start col-12">

  <a href="/efectivoFEC" class="btn btn-outline-info btn-sm">
    <i class="fa fa-reply"></i> Regresar
  </a>

  @if($_GET['tipo'] == 'movimiento')
    <br><br>

    <div class="alert alert-warning text-center">Utilice esta opción solo para cargar egresos o gastos, si desea cargar un pago a algún proveedor <a style="color: #856404" href="?tipo=proveedores">haga click aquí</a>.</div>
  @else
    <br><br>
  @endif


  {!! Form::open(['route' => 'efectivoFEC.store', 'method' => 'POST', 'id' => 'crear_movimientos', 'class' => 'form-group']) !!}
    @if($request->get('tipo') == 'movimiento')
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
                    <input onchange="test(this)" type="radio" class="movimiento-radio custom-control-input" id="movimiento1" name="movimiento" value="Ingreso" required>
                    <label class="custom-control-label" for="movimiento1">Ingreso</label>
                  </div>

                  <div class="custom-control custom-radio custom-control-inline">
                    <input onchange="test(this)" type="radio" class="movimiento-radio custom-control-input" id="movimiento2" name="movimiento" value="Egreso">
                    <label class="custom-control-label" for="movimiento2">Egreso</label>
                  </div>

                  <div class="custom-control custom-radio custom-control-inline">
                    <input onchange="test(this)" type="radio" class="movimiento-radio custom-control-input" id="movimiento3" name="movimiento" value="Diferido">
                    <label class="custom-control-label" for="movimiento3">Diferido</label>
                  </div>
                </td>
              </tr>

              <tr>
                <th scope="row">{!! Form::label('monto', 'Monto *', ['title' => 'Este campo es requerido']) !!}</th>
                <td>
                  <input type="number" name="monto" min="1" step="0.01" class="form-control" placeholder="2500,55" required autofocus>
                </td>
              </tr>

              <tr>
                <th scope="row">{!! Form::label('id_cuenta', 'Plan de cuentas *', ['title' => 'Este campo es requerido']) !!}</th>
                <td>
                  <select required name="id_cuenta" class="form-control">
                    <option value=""></option>
                      @foreach($cuentas as $cuenta)
                        @php $padre = compras\ContCuenta::find($cuenta->pertenece_a); @endphp
                        <option value="{{ $cuenta->id }}">{{ $padre->nombre . ': ' . $cuenta->nombre }}</option>
                      @endforeach
                  </select>
                </td>
              </tr>

              <tr>
                  <th scope="row">{!! Form::label('titular_pago', 'Titular del pago *', ['title' => 'Este campo es requerido']) !!}</th>
                  <td>
                      <input type="text" required class="form-control" name="titular_pago">
                  </td>
              </tr>

              <tr>
                  <th scope="row">{!! Form::label('autorizado_por', 'Autorizado por *', ['title' => 'Este campo es requerido']) !!}</th>
                  <td>
                      <input type="text" class="form-control" name="autorizado_por">
                  </td>
              </tr>

              <tr>
                <th scope="row">{!! Form::label('concepto', 'Concepto *', ['title' => 'Este campo es requerido']) !!}</th>
                <td>{!! Form::text('concepto', null, [ 'class' => 'form-control', 'placeholder' => '', 'required', 'title' => 'Debe colocar mínimo 10 caracteres y máximo 100', 'pattern' => '.{10,100}']) !!}</td>
              </tr>
          </tbody>
        </table>

        <p class="text-danger font-weight-bold">* Campos obligatorios</p>

        {!! Form::submit('Guardar', ['class' => 'btn btn-outline-success btn-md', 'id' => 'enviar']) !!}
      </fieldset>
    @endif

    @if($request->get('tipo') == 'proveedores')
      <fieldset>
        <table class="table table-borderless table-striped">
            <thead class="thead-dark">
                <tr>
                    <th scope="row" colspan="5">Proveedor</th>
                </tr>
            </thead>

            <tbody>
                <tr>
                    <td width="40%">
                        <label for="nombre_proveedor">
                            Nombre del proveedor *
                        </label>

                        <input autofocus class="form-control" id="proveedores" type="text" required value="{{ ($prepagado != '') ? $prepagado->proveedor->nombre_proveedor . ' | ' . $prepagado->proveedor->rif_ci : '' }}">
                        <input name="id_proveedor" type="hidden" required value="{{ ($prepagado != '') ? $prepagado->proveedor->id : '' }}">
                    </td>

                    <td>
                        <label for="moneda">Moneda proveedor</label>
                        <input readonly class="form-control" name="moneda" type="text" value="{{ ($prepagado != '') ? $prepagado->proveedor->moneda : '' }}">
                    </td>

                    <td>
                        <label for="saldo">Saldo</label>
                        <input readonly class="form-control" name="saldo" type="text" value="{{ ($prepagado != '') ? $prepagado->proveedor->saldo : '' }}">
                    </td>

                    <td>
                        <label for="saldo_iva">IVA</label>
                        <input readonly class="form-control" name="saldo_iva" type="text" value="{{ ($prepagado != '') ? $prepagado->proveedor->saldo_iva : '' }}">
                    </td>

                     <td>
                        <label for="moneda_iva">Moneda IVA</label>
                        <input readonly class="form-control" name="moneda_iva" type="text" value="{{ ($prepagado != '') ? $prepagado->proveedor->moneda_iva : '' }}">
                    </td>
                </tr>
            </tbody>
        </table>

        <table class="table table-borderless table-striped">
            <thead class="thead-dark">
                <tr>
                    <th scope="row" colspan="5">Pago</th>
                </tr>
            </thead>

            <tbody>
                <tr>
                    <td colspan="2">
                        <label for="monto">Pago deuda</label>
                        <input class="form-control" name="monto" type="number" step="0.01" min="0.01" value="{{ ($prepagado != '') ? $prepagado->monto : '' }}"required>
                    </td>

                    <td>
                        <label for="monto_iva">IVA</label>
                        <input class="form-control" name="monto_iva" type="number" step="0.01" min="0.01" value="{{ ($prepagado != '') ? $prepagado->monto_iva : '' }}">
                    </td>

                    <td>
                        <label for="tasa">Tasa</label>
                        <input class="form-control" name="tasa" type="number" lang="en" disabled step="0.01" min="0.01">
                    </td>
                </tr>

                <tr style="background-color: rgba(0, 0, 0, 0.05);">
                    <td>
                        <label style="display: block" for="movimiento">Diferido?</label>

                        <div class="custom-control custom-radio custom-control-inline">
                            <input type="radio" class="movimiento-radio custom-control-input" id="movimiento1" name="movimiento" value="Diferido" required>
                            <label class="custom-control-label" for="movimiento1">Si</label>
                        </div>

                        <div class="custom-control custom-radio custom-control-inline">
                            <input type="radio" class="movimiento-radio custom-control-input" id="movimiento2" name="movimiento" value="Egreso" checked required>
                            <label class="custom-control-label" for="movimiento2">No</label>
                        </div>
                    </td>

                    <td colspan="4">
                        <label for="comentario">Comentario *</label>
                        <input class="form-control" maxlength="200" minlength="5" name="comentario" type="text" required>
                    </td>
                </tr>
            </tbody>
        </table>

        <table class="table table-borderless table-striped">
            <thead class="thead-dark">
                <tr>
                    <th scope="row" colspan="4">Retenciones</th>
                </tr>
            </thead>

            <tbody>
                <tr>
                    <td>
                        <label for="retencion_deuda_1">Retención deuda (%)</label>
                        <input class="form-control" name="retencion_deuda_1" step="0.01" min="0.01" type="number">
                    </td>

                    <td>
                        <label for="retencion_deuda_2">Retención deuda (%)</label>
                        <input class="form-control" name="retencion_deuda_2" step="0.01" min="0.01" type="number">
                    </td>

                    <td>
                        <label for="retencion_iva">Retención IVA (%)</label>
                        <input class="form-control" name="retencion_iva" step="0.01" min="0.01" type="number">
                    </td>
                </tr>
            </tbody>
        </table>

        <table class="table table-borderless table-striped">
            <thead class="thead-dark">
                <tr>
                    <th scope="row" colspan="4">Resultados</th>
                </tr>
            </thead>

            <tbody>
                <tr>
                    <td>
                        <label for="pago_deuda_real">Pago real deuda</label>
                        <input class="form-control" readonly name="pago_deuda_real" type="text" value="{{ ($prepagado != '') ? number_format($prepagado->monto, 2) : '' }}">
                    </td>

                    <td>
                        <label for="pago_iva_real">Pago real IVA</label>
                        <input class="form-control" readonly name="pago_iva_real" type="text" value="{{ ($prepagado != '') ? number_format($prepagado->monto_iva, 2) : '' }}">
                    </td>

                    <td>
                        <label for="monto_banco">Monto pago</label>
                        <input class="form-control" readonly name="monto_banco" type="text" value="{{ ($prepagado != '') ? number_format($prepagado->monto + $prepagado->monto_iva, 2) : '' }}">
                    </td>
                </tr>
            </tbody>
        </table>

        <p class="text-danger font-weight-bold">
            * Campos obligatorios
        </p>

        @if($prepagado != '')
          <input type="hidden" name="id_prepagado" value="{{ $prepagado->id }}">
        @endif

        <input class="btn btn-outline-success btn-md" type="submit" value="Guardar">
    </fieldset>
    @endif
  {!! Form::close()!!}

  <script>
    $(document).ready(function() {
      $('[data-toggle="tooltip"]').tooltip();
    });
    $('#exampleModalCenter').modal('show');
  </script>
@endsection

@section('scriptsHead')
    @if($request->get('tipo') == 'movimiento')
      <script>
        function test(elemento) {
          titular = $('[name=titular_pago]');
          autorizado_por = $('[name=autorizado_por]');

          if (elemento.value == 'Ingreso') {
            titular.attr('disabled', true);
            titular.attr('required', true);
            autorizado_por.attr('disabled', true);
            autorizado_por.attr('required', true);
          }

          if (elemento.value == 'Egreso') {
            titular.attr('disabled', false);
            titular.attr('required', false);
            autorizado_por.attr('disabled', false);
            autorizado_por.attr('required', false);
          }

          if (elemento.value == 'Diferido') {
            titular.attr('disabled', false);
            titular.attr('required', false);
            autorizado_por.attr('disabled', false);
            autorizado_por.attr('required', false);
          }
        }
      </script>
    @endif

    @if($request->get('tipo') == 'proveedores')
      <link href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css" rel="stylesheet">
      <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>

      <link rel="stylesheet" href="/assets/sweetalert2/sweetalert2.css">
      <script src="/assets/sweetalert2/sweetalert2.js"></script>

      <script>
          $(document).ready(function() {
              var json = {!! json_encode($proveedores) !!};

              bancario = true;
              reintentar = 0;

              $('#proveedores').autocomplete({
                  source: json,
                  autoFocus: true,
                  select: function (event, ui) {
                      $('[name=id_proveedor]').val(ui.item.id);
                      $('[name=moneda]').val(ui.item.moneda);
                      $('[name=moneda_iva]').val(ui.item.moneda_iva);

                      moneda_banco = $('[name=moneda_banco]').val();
                      moneda_iva = $('[name=moneda_iva]').val();
                      moneda = $('[name=moneda]').val();

                      activar_tasa();
                      activar_tasa_iva();

                      reintentar = 0;

                      $.ajax({
                          type: 'GET',
                          url: '/bancarios/create?proveedor=1',
                          data: {
                              id_proveedor: $('[name=id_proveedor]').val()
                          },
                          success: function (response) {
                              $('[name=saldo]').val(response.saldo);
                              $('[name=saldo_iva]').val(response.saldo_iva);
                              $('[name=tasa_proveedor]').val(response.tasa);
                          },
                          error: function (error) {
                              $('body').html(error.responseText);
                          }
                      })
                  }
              });

              $('[name=moneda]').change(function () {
                  activar_tasa();
                  activar_tasa_iva();

                  reintentar = 0;
              });

              function activar_tasa() {
                  proveedor = $('[name=moneda]').val();

                  console.log(proveedor);

                  if (proveedor == '') {
                      return false;
                  }

                  if (proveedor != 'Dólares') {
                      $('[name=tasa]').attr('disabled', false);
                      $('[name=tasa]').attr('required', true);

                      reintentar = 1;
                  } else {
                      if (reintentar == 0) {
                          $('[name=tasa]').val('');
                          $('[name=tasa]').attr('disabled', true);
                          $('[name=tasa]').attr('required', false);
                      }
                  }
              }

              function activar_tasa_iva() {
                  moneda_iva = $('[name=moneda_iva]').val();

                  if (moneda_iva == '') {
                      return false;
                  }

                  if (moneda_iva != 'Dólares') {
                      $.ajax({
                          method: 'POST',
                          url: '/efectivoFEC/validar',
                          data: {
                              moneda_iva: $('[name=moneda_iva]').val(),
                              _token: '{{ csrf_token() }}'
                          },
                          success: function (response) {
                              console.log(response);

                              $('[name=tasa]').attr('min', response.min);
                              $('[name=tasa]').attr('max', response.max);
                          },
                          error: function (error) {
                              console.log(error.responseText);
                          }
                      });

                      $('[name=tasa]').attr('disabled', false);
                      $('[name=tasa]').attr('required', true);

                      reintentar = 1;
                  } else {
                      if (reintentar == 0) {
                          $('[name=tasa]').val('');
                          $('[name=tasa]').attr('disabled', true);
                          $('[name=tasa]').attr('required', false);
                      }
                  }
              }

              $('form').submit(function (event) {
                  resultado = json.find(elemento => elemento.label == $('#proveedores').val());

                  if (!resultado) {
                      event.preventDefault();
                      alert('Debe seleccionar un proveedor válido');
                      bancario = false;
                      return false;
                  }

                  monto = $('[name=monto]').val();
                  iva = $('[name=monto_iva]').val();

                  if (parseFloat(iva) >= parseFloat(monto)) {
                      alert('El monto del IVA debe ser menor al monto base');
                      event.preventDefault();
                      return false;
                  }

                  Swal.fire({
                      title: 'Cargando...',
                      allowEscapeKey: false,
                      allowOutsideClick: false,
                      onOpen: () => {
                          Swal.showLoading();
                      }
                  });
              });

              $('[name=monto], [name=tasa], [name=retencion_deuda_1], [name=retencion_deuda_2]').keyup(function () {
                  calcula_deuda_real();
              });

              $('[name=monto], [name=tasa], [name=retencion_deuda_1], [name=retencion_deuda_2]').change(function () {
                  calcula_deuda_real();
              });

              function calcula_deuda_real() {
                  moneda_banco = $('[name=moneda_banco]').val();
                  moneda_proveedor = $('[name=moneda]').val();
                  tasa = $('[name=tasa]').val();

                  monto = $('[name=monto]').val();
                  monto = conversion(moneda_proveedor, moneda_banco, monto, tasa);

                  retencion_deuda_1 = $('[name=retencion_deuda_1]').val();
                  retencion_deuda_2 = $('[name=retencion_deuda_2]').val();

                  if (monto != '') {
                      if (retencion_deuda_1 != '') {
                          total_retencion_1 = ((parseFloat(monto) * retencion_deuda_1) / 100);
                      } else {
                          total_retencion_1 = 0
                      }

                      if (retencion_deuda_2 != '') {
                          total_retencion_2 = ((parseFloat(monto) * retencion_deuda_2) / 100);
                      } else {
                          total_retencion_2 = 0
                      }

                      pago_deuda_real = (parseFloat(monto) - total_retencion_1 - total_retencion_2).toFixed(2);

                      $('[name=pago_deuda_real]').val(pago_deuda_real);
                  }

                  calcula_monto_banco();
              }

              $('[name=monto_iva], [name=retencion_iva], [name=tasa]').keyup(function () {
                  calcula_iva_real();
              });

              $('[name=monto_iva], [name=retencion_iva], [name=tasa]').change(function () {
                  calcula_iva_real();
              });

              function calcula_iva_real() {
                  moneda_banco = $('[name=moneda_banco]').val();
                  moneda_iva = $('[name=moneda_iva]').val();
                  tasa = $('[name=tasa]').val();

                  monto_iva = $('[name=monto_iva]').val();
                  monto_iva = conversion(moneda_iva, moneda_banco, monto_iva, tasa);

                  retencion_iva = $('[name=retencion_iva]').val();

                  if (monto_iva != '') {
                      if (retencion_iva != '') {
                          total_iva_real = ((parseFloat(monto_iva) * retencion_iva) / 100);
                      } else {
                          total_iva_real = 0
                      }

                      pago_iva_real = (parseFloat(monto_iva) - total_iva_real).toFixed(2);

                      $('[name=pago_iva_real]').val(pago_iva_real);

                      calcula_monto_banco();
                  }
              }

              function calcula_monto_banco()
              {
                  pago_deuda_real = $('[name=pago_deuda_real]').val();
                  pago_iva_real = $('[name=pago_iva_real]').val();

                  if (pago_deuda_real != '') {
                      monto_banco = parseFloat(pago_deuda_real);
                      monto_banco = monto_banco.toFixed(2);
                      $('[name=monto_banco]').val(monto_banco);
                  }

                  if (pago_deuda_real != '' && pago_iva_real != '') {
                      pago_deuda_real = parseFloat(pago_deuda_real);
                      pago_iva_real = parseFloat(pago_iva_real);

                      monto_banco = pago_deuda_real + pago_iva_real;
                      monto_banco = parseFloat(monto_banco);
                      monto_banco = monto_banco.toFixed(2);
                      $('[name=monto_banco]').val(monto_banco);
                  }
              }

              function conversion(moneda_banco, moneda_proveedor, monto, tasa) {

                  if (moneda_proveedor != 'Dólares') {
                      if (moneda_banco == 'Dólares' && moneda_proveedor == 'Bolívares') {
                          monto = parseFloat(monto) * parseFloat(tasa);
                      }

                      if (moneda_banco == 'Dólares' && moneda_proveedor == 'Pesos') {
                          monto = parseFloat(monto) * parseFloat(tasa);
                      }

                      if (moneda_banco == 'Bolívares' && moneda_proveedor == 'Dólares') {
                          monto = parseFloat(monto) / parseFloat(tasa);
                      }

                      if (moneda_banco == 'Bolívares' && moneda_proveedor == 'Pesos') {
                          monto = parseFloat(monto) * parseFloat(tasa);
                      }

                      if (moneda_banco == 'Pesos' && moneda_proveedor == 'Bolívares') {
                          monto = parseFloat(monto) / parseFloat(tasa);
                      }

                      if (moneda_banco == 'Pesos' && moneda_proveedor == 'Dólares') {
                          monto = parseFloat(monto) / parseFloat(tasa);
                      }
                  } else {
                      monto = parseFloat(monto);
                  }

                  return monto;
              }
          });
      </script>
    @endif
@endsection

