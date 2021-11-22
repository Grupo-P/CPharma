@extends('layouts.contabilidad')

@section('title')
    Registro de deudas
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
                <h4 class="h6">La deuda no fue almacenado, el correo ya esta registrado</h4>
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
        Editar deuda de proveedor
    </h1>

    <hr class="row align-items-start col-12">

    <a href="/deudas" class="btn btn-outline-info btn-sm"><i class="fa fa-reply"></i> Regresar</a>

    <br>
    <br>

    <form method="POST" action="{{ route('deudas.update', $deuda) }}">
        @csrf
        @method('PUT')
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
                            <input readonly required class="form-control" type="text" id="proveedores" value="{{ $deuda->proveedor->nombre_proveedor . ' | ' . $deuda->proveedor->rif_ci }}">
                            <input type="hidden" name="id_proveedor" value="{{ $deuda->proveedor->id }}">
                        </td>
                    </tr>

                    <tr>
                        <th scope="row"><label for="moneda">Moneda subtotal</label></th>
                        <td><input name="moneda" readonly class="form-control" value="{{ $deuda->proveedor->moneda }}"></td>
                    </tr>

                    <tr>
                        <th scope="row"><label for="moneda_iva">Moneda IVA</label></th>
                        <td><input name="moneda_iva" readonly class="form-control" value="{{ $deuda->proveedor->moneda_iva }}"></td>
                    </tr>

                    <tr>
                        <th scope="row"><label for="monto">Monto subtotal (Exento + base)</label></th>
                        <td>
                            <input type="text" value="{{ number_format($deuda->monto, 2, ',', '.') }}" readonly class="form-control" name="monto" step="0.01" min="1">
                        </td>
                    </tr>

                    <tr>
                        <th scope="row"><label for="monto_iva">Monto IVA</label></th>
                        <td>
                            <input type="text" value="{{ number_format($deuda->monto_iva, 2, ',', '.') }}" readonly class="form-control" name="monto_iva" step="0.01" min="1">
                        </td>
                    </tr>

                    <tr>
                        <th scope="row"><label for="documento_soporte_deuda">Documento soporte deuda *</label></th>
                        <td>
                            <select name="documento_soporte_deuda" required class="form-control">
                                <option value=""></option>
                                @foreach($documentos as $documento)
                                    <option {{ ($deuda->documento_soporte_deuda == $documento) ? 'selected' : '' }} value="{{ $documento }}">{{ $documento }}</option>
                                @endforeach
                            </select>
                        </td>
                    </tr>

                    <tr>
                        <th scope="row"><label for="numero_documento">Número documento *</label></th>
                        <td><input name="numero_documento" class="form-control" value="{{ $deuda->numero_documento }}" minlength="5" maxlength="20" required></td>
                    </tr>

                    <tr>
                        <th scope="row"><label for="sede">Sede *</label></th>
                        <td>
                            <select name="sede" required class="form-control" required>
                                <option value=""></option>
                                @foreach($sedes as $sede)
                                    <option {{ ($sede->razon_social == $deuda->sede) ? 'selected' : '' }} value="{{ $sede->razon_social }}">{{ $sede->razon_social }}</option>
                                @endforeach
                                <option {{ ($deuda->sede == 'DROGERÍA EDA, C.A') ? 'selected' : '' }} value="DROGERÍA EDA, C.A">DROGERÍA EDA, C.A</option>
                                <option {{ ($deuda->sede == 'DROGERÍA YAMAR, C.A') ? 'selected' : '' }} value="DROGERÍA EDA, C.A">DROGERÍA YAMAR, C.A</option>
                            </select>
                        </td>
                    </tr>

                    <tr>
                        <th scope="row"><label for="dias_credito">Días de crédito</label></th>
                        <td><input value="{{ $deuda->dias_credito }}" name="dias_credito" type="number" class="form-control" min="0" step="1"></td>
                    </tr>
                </tbody>
            </table>

            <p class="text-danger">* Campos obligatorios</p>

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

            $('#proveedores').autocomplete({
                source: proveedoresJson,
                autoFocus: true,
                select: function (event, ui) {
                    $('[name=id_proveedor]').val(ui.item.id);
                    $('[name=moneda]').val(ui.item.moneda);
                }
            });

            $('form').submit(function (event) {
                resultado = proveedoresJson.find(elemento => elemento.label == $('#proveedores').val());

                if (!resultado) {
                    alert('Debe seleccionar un proveedor válido');
                    event.preventDefault();
                }
            });

            $('[name=numero_documento]').keyup(function () {
                numero_documento = $('[name=numero_documento]').val();
                id_proveedor = $('[name=id_proveedor]').val();

                $.ajax({
                    url: '/deudas/validar',
                    type: 'POST',
                    data: {
                        numero_documento: numero_documento,
                        id_proveedor: id_proveedor,
                        _token: '{{ csrf_token() }}',
                        id: {{ $deuda->id }}
                    },
                    success: function (response) {
                        if (response == 'error') {
                            $('[name=numero_documento]').val('');
                            $('[name=numero_documento]').focus();
                            alert('Numero de soporte ya existe con este proveedor');
                        }
                    },
                    error: function (error) {
                        $('body').html(error.responseText);
                    }
                })
            });
        });
    </script>
@endsection
