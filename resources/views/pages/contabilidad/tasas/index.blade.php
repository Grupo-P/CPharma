@extends('layouts.contabilidad')

@section('title', 'Registro de pagos en efectivo dólares FTN')

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
    <i class="fas fa-dollar-sign"></i>&nbsp;
    Tasas
  </h1>
  <hr class="row align-items-start col-12">

  <a href="/efectivoFTN" class="btn btn-outline-info btn-sm">
    <i class="fa fa-reply"></i> Regresar
  </a>


  {!! Form::open(['route' => 'tasas.store', 'method' => 'POST', 'id' => 'crear_movimientos', 'class' => 'form-group mt-3']) !!}
      <fieldset>
        <table class="table table-borderless table-striped">
            <thead class="thead-dark">
                <tr>
                    <th scope="row" colspan="5">FTN</th>
                </tr>
            </thead>

            <tbody>                
                <tr>
                    @if($ftn)
                        <td>
                            <label for="tasa_venta">Tasa venta (Última actualización: {{ date_format(date_create($ftn['tasa_venta']->fecha), 'd/m/Y') }})</label>
                            <input class="form-control" min="{{ $min }}" max="{{ $max }}" required name="ftn[tasa_venta]" type="number" step="0.0001" value="{{ $ftn['tasa_venta']->tasa }}">
                        </td>

                        <td>
                            <label for="tasa_mercado">Tasa mercado (Última actualización: {{ date_format(date_create($ftn['tasa_mercado']->fecha), 'd/m/Y') }})</label>
                            <input class="form-control" required name="ftn[tasa_mercado]" type="number" step="0.0001" value="{{ $ftn['tasa_mercado']->tasa }}">
                        </td>

                        <td>
                            <label for="tasa_calculo">Tasa cálculo (Última actualización: {{ date_format(date_create($ftn['tasa_calculo']->updated_at), 'd/m/Y') }})</label>
                            <input class="form-control" required name="ftn[tasa_calculo]" type="number" step="0.0001" value="{{ $ftn['tasa_calculo']->valor }}">
                        </td>
                    @else
                        <td colspan="3" class="text-center">
                            <label>No hay conexión</label>
                        </td>
                    @endif
                </tr>                
            </tbody>
        </table>

        <table class="table table-borderless table-striped">
            <thead class="thead-dark">
                <tr>
                    <th scope="row" colspan="5">FAU</th>
                </tr>
            </thead>

            <tbody>
                <tr>
                    @if($fau)
                        <td>
                            <label for="tasa_venta">Tasa venta (Última actualización: {{ date_format(date_create($fau['tasa_venta']->fecha), 'd/m/Y') }})</label>
                            <input class="form-control" min="{{ $min }}" max="{{ $max }}" required name="fau[tasa_venta]" type="number" step="0.0001" value="{{ $fau['tasa_venta']->tasa }}">
                        </td>

                        <td>
                            <label for="tasa_mercado">Tasa mercado (Última actualización: {{ date_format(date_create($fau['tasa_mercado']->fecha), 'd/m/Y') }})</label>
                            <input class="form-control" required name="fau[tasa_mercado]" type="number" step="0.0001" value="{{ $fau['tasa_mercado']->tasa }}">
                        </td>

                        <td>
                            <label for="tasa_calculo">Tasa cálculo (Última actualización: {{ date_format(date_create($fau['tasa_calculo']->updated_at), 'd/m/Y') }})</label>
                            <input class="form-control" required name="fau[tasa_calculo]" type="number" step="0.0001" value="{{ $fau['tasa_calculo']->valor }}">
                        </td>
                    @else
                        <td colspan="3" class="text-center">
                            <label>No hay conexión</label>
                        </td>
                    @endif
                </tr>
            </tbody>
        </table>

        <table class="table table-borderless table-striped">
            <thead class="thead-dark">
                <tr>
                    <th scope="row" colspan="5">FSM</th>
                </tr>
            </thead>

            <tbody>
                <tr>
                    @if($fm)
                        <td>
                            <label for="tasa_venta">Tasa venta (Última actualización: {{ date_format(date_create($fm['tasa_venta']->fecha), 'd/m/Y') }})</label>
                            <input class="form-control" min="{{ $min }}" max="{{ $max }}" required name="fm[tasa_venta]" type="number" step="0.0001" value="{{ $fm['tasa_venta']->tasa }}">
                        </td>

                        <td>
                            <label for="tasa_mercado">Tasa mercado (Última actualización: {{ date_format(date_create($fm['tasa_mercado']->fecha), 'd/m/Y') }})</label>
                            <input class="form-control" required name="fm[tasa_mercado]" type="number" step="0.0001" value="{{ $fm['tasa_mercado']->tasa }}">
                        </td>

                        <td>
                            <label for="tasa_calculo">Tasa cálculo (Última actualización: {{ date_format(date_create($fm['tasa_calculo']->updated_at), 'd/m/Y') }})</label>
                            <input class="form-control" required name="fm[tasa_calculo]" type="number" step="0.0001" value="{{ $fm['tasa_calculo']->valor }}">
                        </td>
                    @else
                        <td colspan="3" class="text-center">
                            <label>No hay conexión</label>
                        </td>
                    @endif
                </tr>
            </tbody>
        </table>

        <table class="table table-borderless table-striped">
            <thead class="thead-dark">
                <tr>
                    <th scope="row" colspan="5">FLL</th>
                </tr>
            </thead>

            <tbody>
                <tr>
                    @if($fll)
                        <td>
                            <label for="tasa_venta">Tasa venta (Última actualización: {{ date_format(date_create($fll['tasa_venta']->fecha), 'd/m/Y') }})</label>
                            <input class="form-control" min="{{ $min }}" max="{{ $max }}" required name="fll[tasa_venta]" type="number" step="0.0001" value="{{ $fll['tasa_venta']->tasa }}">
                        </td>

                        <td>
                            <label for="tasa_mercado">Tasa mercado (Última actualización: {{ date_format(date_create($fll['tasa_mercado']->fecha), 'd/m/Y') }})</label>
                            <input class="form-control" required name="fll[tasa_mercado]" type="number" step="0.0001" value="{{ $fll['tasa_mercado']->tasa }}">
                        </td>

                        <td>
                            <label for="tasa_calculo">Tasa cálculo (Última actualización: {{ date_format(date_create($fll['tasa_calculo']->updated_at), 'd/m/Y') }})</label>
                            <input class="form-control" required name="fll[tasa_calculo]" type="number" step="0.0001" value="{{ $fll['tasa_calculo']->valor }}">
                        </td>
                    @else
                        <td colspan="3" class="text-center">
                            <label>No hay conexión</label>
                        </td>
                    @endif
                </tr>
            </tbody>
        </table>

        <table class="table table-borderless table-striped">
            <thead class="thead-dark">
                <tr>
                    <th scope="row" colspan="5">KDI</th>
                </tr>
            </thead>

            <tbody>
                <tr>
                    @if($kdi)
                        <td>
                            <label for="tasa_venta">Tasa venta (Última actualización: {{ date_format(date_create($kdi['tasa_venta']->fecha), 'd/m/Y') }})</label>
                            <input class="form-control" min="{{ $min }}" max="{{ $max }}" required name="kdi[tasa_venta]" type="number" step="0.0001" value="{{ $kdi['tasa_venta']->tasa }}">
                        </td>

                        <td>
                            <label for="tasa_mercado">Tasa mercado (Última actualización: {{ date_format(date_create($kdi['tasa_mercado']->fecha), 'd/m/Y') }})</label>
                            <input class="form-control" required name="kdi[tasa_mercado]" type="number" step="0.0001" value="{{ $kdi['tasa_mercado']->tasa }}">
                        </td>

                        <td>
                            <label for="tasa_calculo">Tasa cálculo (Última actualización: {{ date_format(date_create($kdi['tasa_calculo']->updated_at), 'd/m/Y') }})</label>
                            <input class="form-control" required name="kdi[tasa_calculo]" type="number" step="0.0001" value="{{ $kdi['tasa_calculo']->valor }}">
                        </td>
                    @else
                        <td colspan="3" class="text-center">
                            <label>No hay conexión</label>
                        </td>
                    @endif
                </tr>
            </tbody>
        </table>

        <input class="btn btn-outline-success btn-md" type="submit" value="Guardar">
    </fieldset>
  {!! Form::close()!!}
@endsection

