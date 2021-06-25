@extends('layouts.contabilidad')

@section('title')
    Registro de bancarios
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
                <h4 class="h6">El pago bancario no fue almacenado, ocurrió un error</h4>
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
        Cargar pago bancario a proveedor
    </h1>

    <hr class="row align-items-start col-12">

    <a href="/bancarios" class="btn btn-outline-info btn-sm"><i class="fa fa-reply"></i> Regresar</a>

    <br>
    <br>

    <form method="POST" action="/bancarios">
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
                        <td>
                            <input autofocus class="form-control" type="text" id="proveedores">
                            <input type="hidden" name="id_proveedor">
                        </td>
                    </tr>

                    <tr>
                        <th scope="row"><label for="moneda">Moneda</label></th>
                        <th scope="row"><input readonly type="text" name="moneda" class="form-control"></th>
                    </tr>

                    <tr>
                        <th scope="row"><label for="saldo">Saldo</label></th>
                        <th scope="row"><input readonly type="text" name="saldo" class="form-control"></th>
                    </tr>

                    <tr>
                        <th scope="row"><label for="monto">Monto *</label></th>
                        <td><input type="number" required class="form-control" name="monto" step="0.01" min="1"></td>
                    </tr>

                    <tr>
                        <th scope="row"><label for="id_banco">Banco *</label></th>
                        <td>
                            <select name="id_banco" class="form-control" required>
                                <option value=""></option>
                                @foreach($bancos as $banco)
                                    <option data-banco-moneda="{{ $banco->moneda }}" value="{{ $banco->id }}">{{ $banco->alias_cuenta }}</option>
                                @endforeach
                            </select>
                        </td>
                    </tr>

                    <tr class="append"></tr>

                    <tr>
                        <th scope="row"><label for="comentario">Comentario</label></th>
                        <td><input type="text" minlength="5" maxlength="200" class="form-control" name="comentario"></td>
                    </tr>
                </tbody>
            </table>

            <p class="text-danger font-weight-bold">* Campos obligatorios</p>

            <input type="submit" class="btn btn-outline-success btn-md" value="Guardar">
        </fieldset>
    </form>

    <script>
        $(document).ready(function(){
            $('[data-toggle="tooltip"]').tooltip();
        });

        $('#exampleModalCenter').modal('show');
    </script>
@endsection


@section('scriptsHead')
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
                    $('[name=saldo]').val(ui.item.saldo);
                }
            });

            $('form').submit(function (event) {
                resultado = proveedoresJson.find(elemento => elemento.label == $('#proveedores').val());

                if (!resultado) {
                    event.preventDefault();
                    alert('Debe seleccionar un proveedor válido');
                    bancario = false;
                }

                if (bancario) {
                    moneda_banco = $('[name=moneda]').val();
                    moneda_proveedor = $('option:selected').attr('data-banco-moneda');

                    if (moneda_proveedor != moneda_banco) {
                        respuesta = true;
                    } else {
                        respuesta = false;
                    }

                    if (respuesta) {
                        event.preventDefault();

                        id_proveedor = $('[name=id_proveedor]').val();

                        $.ajax({
                            type: 'POST',
                            url: '/efectivo/validar',
                            data: {
                                id_proveedor: id_proveedor,
                                _token: '{{ csrf_token() }}'
                            },
                            success: function (respuesta) {
                                $('.append').append(`
                                    <th scope="row"><label for="tasa">Tasa *</label></th>
                                    <td>
                                        <input class="form-control" step="0.01" min="${respuesta.min}" max="${respuesta.max}" type="number" name="tasa" required>
                                    </td>
                                `);

                                $('[name=tasa]').focus();
                            }
                        });
                    }

                    bancario = false;
                }
            });
        });
    </script>
@endsection
