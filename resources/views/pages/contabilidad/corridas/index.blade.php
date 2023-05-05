@extends('layouts.contabilidad')

@section('title', 'Corridas de precios')

@section('content')

  <h1 class="h5 text-info">
    <i class="fas fa-funnel-dollar"></i>&nbsp;
    Corridas de precios
  </h1>
  <hr class="row align-items-start col-12">

  <a href="/efectivoFTN" class="btn btn-outline-info btn-sm">
    <i class="fa fa-reply"></i> Regresar
  </a>



  <fieldset class="mt-3">
    <table class="table table-borderless table-striped">
        <thead class="thead-dark">
            <tr>
                <th scope="row" colspan="5">FTN</th>
            </tr>
        </thead>

        <tbody>
            <tr>
                @if($fm)
                    {!! Form::open(['route' => 'corrida.store', 'method' => 'POST', 'class' => 'form-group mt-3']) !!}
                        <td>
                            <label for="tasa_calculo">Tasa cálculo</label>
                            <input class="form-control" readonly name="tasa_calculo" type="number" step="0.0001" value="{{ $ftn['tasa_calculo']->valor }}">
                        </td>

                        <td>
                            <label for="fecha">Fecha</label>
                            <input class="form-control" readonly name="fecha" type="date" value="{{ date_format(date_create($ftn['tasa_calculo']->updated_at), 'Y-m-d') }}">
                        </td>

                        <td>
                            <label for="tipo">Tipo de corrida</label>

                            <div class="mt-2">
                                <input type="radio" id="subida" name="tipo_corrida" value="subida" checked>
                                <label for="subida">Subida</label>

                                <input class="ml-3" type="radio" id="subida/bajada" name="tipo_corrida" value="bajada">
                                <label for="subida/bajada">Subida/Bajada</label>
                            </div>
                        </td>

                        <td>
                            <input type="hidden" name="sede" value="FTN">
                            <button type="submit" class="btn btn-outline-success mt-3">Ejecutar</button>
                        </td>
                    {!! Form::close()!!}
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
                    {!! Form::open(['route' => 'corrida.store', 'method' => 'POST', 'class' => 'form-group mt-3']) !!}
                        <td>
                            <label for="tasa_calculo">Tasa cálculo</label>
                            <input class="form-control" readonly name="tasa_calculo" type="number" step="0.0001" value="{{ $fau['tasa_calculo']->valor }}">
                        </td>

                        <td>
                            <label for="fecha">Fecha</label>
                            <input class="form-control" readonly name="fecha" type="date" value="{{ date_format(date_create($fau['tasa_calculo']->updated_at), 'Y-m-d') }}">
                        </td>

                        <td>
                            <label for="tipo">Tipo de corrida</label>

                            <div class="mt-2">
                                <input type="radio" id="subida" name="tipo_corrida" value="subida" checked>
                                <label for="subida">Subida</label>

                                <input class="ml-3" type="radio" id="subida/bajada" name="tipo_corrida" value="bajada">
                                <label for="subida/bajada">Subida/Bajada</label>
                            </div>
                        </td>

                        <td>
                            <input type="hidden" name="sede" value="FAU">
                            <button type="submit" class="btn btn-outline-success mt-3">Ejecutar</button>
                        </td>
                    {!! Form::close()!!}
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
                <th scope="row" colspan="5">FM</th>
            </tr>
        </thead>

        <tbody>
            <tr>
                @if($fm)
                    {!! Form::open(['route' => 'corrida.store', 'method' => 'POST', 'class' => 'form-group mt-3']) !!}
                        <td>
                            <label for="tasa_calculo">Tasa cálculo</label>
                            <input class="form-control" readonly name="tasa_calculo" type="number" step="0.0001" value="{{ $fm['tasa_calculo']->valor }}">
                        </td>

                        <td>
                            <label for="fecha">Fecha</label>
                            <input class="form-control" readonly name="fecha" type="date" value="{{ date_format(date_create($fm['tasa_calculo']->updated_at), 'Y-m-d') }}">
                        </td>

                        <td>
                            <label for="tipo">Tipo de corrida</label>

                            <div class="mt-2">
                                <input type="radio" id="subida" name="tipo_corrida" value="subida" checked>
                                <label for="subida">Subida</label>

                                <input class="ml-3" type="radio" id="subida/bajada" name="tipo_corrida" value="bajada">
                                <label for="subida/bajada">Subida/Bajada</label>
                            </div>
                        </td>

                        <td>
                            <input type="hidden" name="sede" value="FM">
                            <button type="submit" class="btn btn-outline-success mt-3">Ejecutar</button>
                        </td>
                    {!! Form::close()!!}
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
                    {!! Form::open(['route' => 'corrida.store', 'method' => 'POST', 'class' => 'form-group mt-3']) !!}
                        <td>
                            <label for="tasa_calculo">Tasa cálculo</label>
                            <input class="form-control" readonly name="tasa_calculo" type="number" step="0.0001" value="{{ $fll['tasa_calculo']->valor }}">
                        </td>

                        <td>
                            <label for="fecha">Fecha</label>
                            <input class="form-control" readonly name="fecha" type="date" value="{{ date_format(date_create($fll['tasa_calculo']->updated_at), 'Y-m-d') }}">
                        </td>

                        <td>
                            <label for="tipo">Tipo de corrida</label>

                            <div class="mt-2">
                                <input type="radio" id="subida" name="tipo_corrida" value="subida" checked>
                                <label for="subida">Subida</label>

                                <input class="ml-3" type="radio" id="subida/bajada" name="tipo_corrida" value="bajada">
                                <label for="subida/bajada">Subida/Bajada</label>
                            </div>
                        </td>

                        <td>
                            <input type="hidden" name="sede" value="FLL">
                            <button type="submit" class="btn btn-outline-success mt-3">Ejecutar</button>
                        </td>
                    {!! Form::close()!!}
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
                    {!! Form::open(['route' => 'corrida.store', 'method' => 'POST', 'class' => 'form-group mt-3']) !!}
                        <td>
                            <label for="tasa_calculo">Tasa cálculo</label>
                            <input class="form-control" readonly name="tasa_calculo" type="number" step="0.0001" value="{{ $kdi['tasa_calculo']->valor }}">
                        </td>

                        <td>
                            <label for="fecha">Fecha</label>
                            <input class="form-control" readonly name="fecha" type="date" value="{{ date_format(date_create($kdi['tasa_calculo']->updated_at), 'Y-m-d') }}">
                        </td>

                        <td>
                            <label for="tipo">Tipo de corrida</label>

                            <div class="mt-2">
                                <input type="radio" id="subida" name="tipo_corrida" value="subida" checked>
                                <label for="subida">Subida</label>

                                <input class="ml-3" type="radio" id="subida/bajada" name="tipo_corrida" value="bajada">
                                <label for="subida/bajada">Subida/Bajada</label>
                            </div>
                        </td>

                        <td>
                            <input type="hidden" name="sede" value="KDI">
                            <button type="submit" class="btn btn-outline-success mt-3">Ejecutar</button>
                        </td>
                    {!! Form::close()!!}
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
                <th scope="row" colspan="5">FEC</th>
            </tr>
        </thead>

        <tbody>
            <tr>
                @if($fec)
                    {!! Form::open(['route' => 'corrida.store', 'method' => 'POST', 'class' => 'form-group mt-3']) !!}
                        <td>
                            <label for="tasa_calculo">Tasa cálculo</label>
                            <input class="form-control" readonly name="tasa_calculo" type="number" step="0.0001" value="{{ $fec['tasa_calculo']->valor }}">
                        </td>

                        <td>
                            <label for="fecha">Fecha</label>
                            <input class="form-control" readonly name="fecha" type="date" value="{{ date_format(date_create($fec['tasa_calculo']->updated_at), 'Y-m-d') }}">
                        </td>

                        <td>
                            <label for="tipo">Tipo de corrida</label>

                            <div class="mt-2">
                                <input type="radio" id="subida" name="tipo_corrida" value="subida" checked>
                                <label for="subida">Subida</label>

                                <input class="ml-3" type="radio" id="subida/bajada" name="tipo_corrida" value="bajada">
                                <label for="subida/bajada">Subida/Bajada</label>
                            </div>
                        </td>

                        <td>
                            <input type="hidden" name="sede" value="FEC">
                            <button type="submit" class="btn btn-outline-success mt-3">Ejecutar</button>
                        </td>
                    {!! Form::close()!!}
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
                <th scope="row" colspan="5">KD73</th>
            </tr>
        </thead>

        <tbody>
            <tr>
                @if($kd73)
                    {!! Form::open(['route' => 'corrida.store', 'method' => 'POST', 'class' => 'form-group mt-3']) !!}
                        <td>
                            <label for="tasa_calculo">Tasa cálculo</label>
                            <input class="form-control" readonly name="tasa_calculo" type="number" step="0.0001" value="{{ $kd73['tasa_calculo']->valor }}">
                        </td>

                        <td>
                            <label for="fecha">Fecha</label>
                            <input class="form-control" readonly name="fecha" type="date" value="{{ date_format(date_create($kd73['tasa_calculo']->updated_at), 'Y-m-d') }}">
                        </td>

                        <td>
                            <label for="tipo">Tipo de corrida</label>

                            <div class="mt-2">
                                <input type="radio" id="subida" name="tipo_corrida" value="subida" checked>
                                <label for="subida">Subida</label>

                                <input class="ml-3" type="radio" id="subida/bajada" name="tipo_corrida" value="bajada">
                                <label for="subida/bajada">Subida/Bajada</label>
                            </div>
                        </td>

                        <td>
                            <input type="hidden" name="sede" value="KD73">
                            <button type="submit" class="btn btn-outline-success mt-3">Ejecutar</button>
                        </td>
                    {!! Form::close()!!}
                @else
                    <td colspan="3" class="text-center">
                        <label>No hay conexión</label>
                    </td>
                @endif
            </tr>
        </tbody>
    </table>
  </fieldset>
@endsection

