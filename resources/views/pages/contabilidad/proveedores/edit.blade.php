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
                        <th scope="row"><label for="nombre_proveedor">Nombre del proveedor *</label></th>
                        <td><input name="nombre_proveedor" class="form-control" autofocus required minlength="5" value="{{ $proveedor->nombre_proveedor }}"></td>
                    </tr>

                    <tr>
                        <th scope="row"><label for="nombre_representante">Nombre del representante</label></th>
                        <td><input name="nombre_representante" class="form-control" minlength="5" value="{{ $proveedor->nombre_representante }}"></td>
                    </tr>

                    <tr>
                        <th scope="row"><label for="rif_ci">RIF/Cédula del proveedor *</label></th>
                        <td>
                            <div class="input-group">
                                <select name="prefix_rif_ci" id="" required class="form-control">
                                    <option {{ (substr($proveedor->rif_ci, 0, 1) == 'V') ? 'selected' : '' }} value="V">V</option>
                                    <option {{ (substr($proveedor->rif_ci, 0, 1) == 'E') ? 'selected' : '' }} value="E">E</option>
                                    <option {{ (substr($proveedor->rif_ci, 0, 1) == 'J') ? 'selected' : '' }} value="J">J</option>
                                </select>
                                <input required onkeypress="soloNumeros(event)" minlength="9" maxlength="10" value="{{ substr($proveedor->rif_ci, 2) }}" style="width: 80%" name="rif_ci" class="form-control">
                            </div>
                        </td>
                    </tr>

                    <tr>
                        <th scope="row"><label for="correo_electronico">Correo electrónico del proveedor</label></th>
                        <td><input name="correo_electronico" class="form-control" value="{{ $proveedor->correo_electronico }}"></td>
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
                        <td>
                            <select name="plan_cuentas" class="form-control">
                                <option value=""></option>
                                @foreach($cuentas as $cuenta)
                                    <option {{ ($cuenta->nombre == $proveedor->plan_cuentas) ? 'selected' : '' }} value="{{ $cuenta->nombre }}">{{ $cuenta->nombre }}</option>
                                @endforeach
                            </select>
                        </td>
                    </tr>

                    <tr>
                        <th scope="row"><label for="moneda">Moneda *</label></th>
                        <td>
                            <select required name="moneda" class="form-control">
                                <option value=""></option>
                                @foreach($monedas as $moneda)
                                    <option {{ ($moneda == $proveedor->moneda) ? 'selected' : '' }} value="{{ $moneda }}">{{ $moneda }}</option>
                                @endforeach
                            </select>
                        </td>
                    </tr>

                    <tr>
                        <th scope="row"><label for="saldo">Saldo</label></th>
                        <td><input name="saldo" value="{{ number_format($proveedor->saldo, 2, '.', '') }}" class="form-control" type="number" step="0.01" readonly></td>
                    </tr>
                </tbody>
            </table>

            <p class="text-danger">* Campos obligatorios</p>

            <input type="button" class="submit btn btn-outline-success btn-md" value="Guardar">
        </fieldset>
    </form>

    <script>
        $(document).ready(function(){
            $('[data-toggle="tooltip"]').tooltip();
        });

        $('#exampleModalCenter').modal('show');

        $('.submit').click(function () {
            prefix = $('[name=prefix_rif_ci]').val();
            rif = $('[name=rif_ci]').val();

            if (rif != '') {
                rif = prefix + '-' + rif;

                $.ajax({
                    type: 'GET',
                    url: '/proveedores/validar',
                    data: {
                        rif: rif,
                        id: {{ $proveedor->id }}
                    },
                    success: function (response) {
                        if (response == 'error') {
                            alert('El RIF que intenta registrar ya existe!');
                            $('[name=rif_ci]').focus();
                        }

                        if (response == 'success') {
                            $('form').submit();
                        }
                    }
                });
            } else {
                alert('El campo RIF es obligatorio');
            }
        });
    </script>
@endsection
