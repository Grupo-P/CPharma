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
        <i class="fas fa-plus"></i>
        Agregar proveedor
    </h1>

    <hr class="row align-items-start col-12">

    <a href="/proveedores" class="btn btn-outline-info btn-sm"><i class="fa fa-reply"></i> Regresar</a>

    <br>
    <br>

    <form method="POST" action="/proveedores">
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
                        <td><input name="nombre_proveedor" class="form-control" autofocus required minlength="5"></td>
                    </tr>

                    <tr>
                        <th scope="row"><label for="nombre_representante">Nombre del representante</label></th>
                        <td><input name="nombre_representante" class="form-control" minlength="5"></td>
                    </tr>

                    <tr>
                        <th scope="row"><label for="rif_ci">RIF/Cédula</label></th>
                        <td>
                            <div class="input-group">
                                <select name="prefix_rif_ci" id="" class="form-control">
                                    <option value="V">V</option>
                                    <option value="E">E</option>
                                    <option value="J">J</option>
                                </select>
                                <input onkeypress="soloNumeros(event)" minlength="9" style="width: 80%" name="rif_ci" class="form-control">
                            </div>
                        </td>
                    </tr>

                    <tr>
                        <th scope="row"><label for="direccion">Dirección</label></th>
                        <td><input name="direccion" class="form-control" minlength="5" maxlength="50"></td>
                    </tr>

                    <tr>
                        <th scope="row"><label for="tasa">Tasa</label></th>
                        <td>
                            <select name="tasa" class="form-control">
                                <option value=""></option>
                                @foreach($tasas as $tasa)
                                    <option value="{{ $tasa }}">{{ $tasa }}</option>
                                @endforeach
                            </select>
                        </td>
                    </tr>

                    <tr>
                        <th scope="row"><label for="plan_cuenta">Plan de cuentas</label></th>
                        <td>
                            <select name="plan_cuentas" class="form-control">
                                <option value=""></option>
                                @foreach($cuentas as $cuentas)
                                    <option value="{{ $cuentas->nombre }}">{{ $cuentas->nombre }}</option>
                                @endforeach
                            </select>
                        </td>
                    </tr>

                    <tr>
                        <th scope="row"><label for="moneda">Moneda</label></th>
                        <td>
                            <select name="moneda" class="form-control">
                                <option value=""></option>
                                @foreach($monedas as $moneda)
                                    <option value="{{ $moneda }}">{{ $moneda }}</option>
                                @endforeach
                            </select>
                        </td>
                    </tr>

                    <tr>
                        <th scope="row"><label for="saldo">Saldo</label></th>
                        <td><input name="saldo" value="0" class="form-control" type="number" required></td>
                    </tr>
                </tbody>
            </table>

            <button type="button" class="btn btn-outline-success btn-md">Guardar</button>
        </fieldset>
    </form>

    <script>
        $(document).ready(function(){
            $('[data-toggle="tooltip"]').tooltip();
        });
        $('#exampleModalCenter').modal('show');

        $('[type=button]').click(function () {
            prefix = $('[name=prefix_rif_ci]').val();
            rif = $('[name=rif_ci]').val();

            if (rif != '') {
                rif = prefix + '-' + rif;

                $.ajax({
                    type: 'GET',
                    url: '/proveedores/validar',
                    data: {
                        rif: rif
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
            }
        });
    </script>
@endsection
