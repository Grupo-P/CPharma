@extends('layouts.contabilidad')

@section('title')
    Proveedor
@endsection

@section('content')
<!-- Modal Guardar -->
    @if (session('Error'))
        <div class="modal fade" id="exampleModalCenter" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
          <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
              <div class="modal-header">
                <h5 class="modal-title text-danger" id="exampleModalCenterTitle"><i class="fas fa-exclamation-triangle text-danger"></i>{{ session('Error') }}</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                </button>
              </div>
              <div class="modal-body">
                <h4 class="h6">El proveedor no fue almacenado, el correo ya esta registrado</h4>
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
        Modificar proveedor
    </h1>

    <hr class="row align-items-start col-12">

    <a href="/proveedores" class="btn btn-outline-info btn-sm"><i class="fa fa-reply"></i> Regresar</a>

    <br>
    <br>

    <form method="POST" action="{{ '/proveedores/' . $proveedor->id }}">
        @method('PUT')
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
                        <th scope="row"><label for="nombre_proveedor">Nombre del proveedor</label></th>
                        <td><input name="nombre_proveedor" class="form-control" autofocus required minlength="5" value="{{ $proveedor->nombre_proveedor }}"></td>
                    </tr>

                    <tr>
                        <th scope="row"><label for="nombre_representante">Nombre del representante</label></th>
                        <td><input name="nombre_representante" class="form-control" minlength="5" value="{{ $proveedor->nombre_representante }}"></td>
                    </tr>

                    <tr>
                        <th scope="row"><label for="rif_ci">RIF/Cédula</label></th>
                        <td>
                            <div class="input-group">
                                <select name="prefix_rif_ci" id="" class="form-control">
                                    <option {{ (substr($proveedor->rif_ci, 0, 1) == 'V') ? 'selected' : '' }} value="V">V</option>
                                    <option {{ (substr($proveedor->rif_ci, 0, 1) == 'E') ? 'selected' : '' }} value="E">E</option>
                                    <option {{ (substr($proveedor->rif_ci, 0, 1) == 'J') ? 'selected' : '' }} value="J">J</option>
                                </select>
                                <input onkeypress="soloNumeros(event)" minlength="10" style="width: 80%" name="rif_ci" class="form-control">
                            </div>
                        </td>
                    </tr>

                    <tr>
                        <th scope="row"><label for="direccion">Dirección</label></th>
                        <td><input name="direccion" class="form-control" minlength="5" maxlength="50" value="{{ $proveedor->direccion }}"></td>
                    </tr>

                    <tr>
                        <th scope="row"><label for="tasa">Tasa</label></th>
                        <td>
                            <select name="tasa" class="form-control">
                                <option value=""></option>
                                @foreach($tasas as $tasa)
                                    <option {{ ($tasa == $proveedor->tasa) ? 'selected' : '' }} value="{{ $tasa }}">{{ $tasa }}</option>
                                @endforeach
                            </select>
                        </td>
                    </tr>

                    <tr>
                        <th scope="row"><label for="plan_cuenta">Plan de cuentas</label></th>
                        <td><input name="plan_cuenta" class="form-control" value="{{ $proveedor->plan_cuenta }}"></td>
                    </tr>

                    <tr>
                        <th scope="row"><label for="moneda">Moneda</label></th>
                        <td>
                            <select name="moneda" class="form-control">
                                <option value=""></option>
                                @foreach($monedas as $moneda)
                                    <option {{ ($moneda == $proveedor->moneda) ? 'selected' : '' }} value="{{ $moneda }}">{{ $moneda }}</option>
                                @endforeach
                            </select>
                        </td>
                    </tr>

                    <tr>
                        <th scope="row"><label for="saldo">Saldo</label></th>
                        <td><input name="saldo" value="0" class="form-control" type="number" required value="{{ $proveedor->saldo }}"></td>
                    </tr>
                </tbody>
            </table>

            <input type="submit" class="btn btn-outline-success btn-md" value="Guardar">
        </fieldset>
    </form>

    <script>
        $(document).ready(function(){
            $('[data-toggle="tooltip"]').tooltip();
        });
        $('#exampleModalCenter').modal('show')
    </script>
@endsection
