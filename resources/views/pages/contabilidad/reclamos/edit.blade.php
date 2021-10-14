@extends('layouts.contabilidad')

@section('title')
    Registro de reclamos
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
                <h4 class="h6">La reclamo no fue almacenado, el correo ya esta registrado</h4>
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
        Editar reclamo de proveedor
    </h1>

    <hr class="row align-items-start col-12">

    <a href="/reclamos" class="btn btn-outline-info btn-sm"><i class="fa fa-reply"></i> Regresar</a>

    <br>
    <br>

    <form method="POST" action="{{ route('reclamos.update', $reclamo) }}">
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
                            <input readonly class="form-control" required type="text" id="proveedores" value="{{ ($reclamo->proveedor) ? $reclamo->proveedor->nombre_proveedor . ' | ' . $reclamo->proveedor->rif_ci : '' }}">
                            <input type="hidden" name="id_proveedor" value="{{ ($reclamo->proveedor) ? $reclamo->proveedor->id : '' }}">
                        </td>
                    </tr>

                    <tr>
                        <th scope="row"><label for="moneda">Moneda reclamo</label></th>
                        <td><input name="moneda" readonly class="form-control" value="{{ ($reclamo->proveedor) ? $reclamo->proveedor->moneda : '' }}" required></td>
                    </tr>

                    <tr>
                        <th scope="row"><label for="monto">Monto sin IVA</label></th>
                        <td>
                            <input type="text" readonly value="{{ number_format($reclamo->monto, 2, ',', '.') }}" step="0.01" required class="form-control" name="monto">
                        </td>
                    </tr>

                    <tr>
                        <th scope="row"><label for="monto_iva">Monto IVA</label></th>
                        <td>
                            <input type="text" readonly value="{{ number_format($reclamo->monto_iva, 2, ',', '.') }}" class="form-control" name="monto_iva">
                        </td>
                    </tr>

                    <tr>
                        <th scope="row"><label for="monto_total">Monto total</label></th>
                        <td>
                            <input type="text" readonly value="{{ number_format($reclamo->monto + $reclamo->monto_iva, 2, ',', '.') }}" class="form-control" name="monto_total">
                        </td>
                    </tr>

                    <tr>
                        <th scope="row"><label for="documento_soporte_reclamo">Documento soporte reclamo *</label></th>
                        <td>
                            <select name="documento_soporte_reclamo" required class="form-control">
                                <option value=""></option>
                                @foreach($documentos as $documento)
                                    <option {{ ($reclamo->documento_soporte_reclamo == $documento) ? 'selected' : '' }} value="{{ $documento }}">{{ $documento }}</option>
                                @endforeach
                                <option {{ ($reclamo->documento_soporte_reclamo == 'Nota de crédito') ? 'selected' : '' }} value="Nota de crédito">Nota de crédito</option>
                            </select>
                        </td>
                    </tr>

                    <tr>
                        <th scope="row"><label for="numero_documento">Número documento *</label></th>
                        <td><input name="numero_documento" class="form-control" value="{{ $reclamo->numero_documento }}" minlength="5" maxlength="20" required></td>
                    </tr>

                    <tr>
                        <th scope="row"><label for="sede">Sede *</label></th>
                        <td>
                            <select name="sede" required class="form-control" required>
                                <option value=""></option>
                                @foreach($sedes as $sede)
                                    <option {{ ($sede->razon_social == $reclamo->sede) ? 'selected' : '' }} value="{{ $sede->razon_social }}">{{ $sede->razon_social }}</option>
                                @endforeach
                                <option {{ ($reclamo->sede == 'DROGERÍA EDA, C.A') ? 'selected' : '' }} value="DROGERÍA EDA, C.A">DROGERÍA EDA, C.A</option>
                                <option {{ ($reclamo->sede == 'DROGERÍA YAMAR, C.A') ? 'selected' : '' }} value="DROGERÍA EDA, C.A">DROGERÍA YAMAR, C.A</option>
                            </select>
                        </td>
                    </tr>

                    <tr>
                        <th scope="row"><label for="comentario">Comentario</label></th>
                        <td>
                            <input type="text" class="form-control" value="{{ $reclamo->comentario }}" name="comentario" minlength="10" maxlength="200">
                        </td>
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

    <link rel="stylesheet" href="/assets/sweetalert2/sweetalert2.css">
    <script src="/assets/sweetalert2/sweetalert2.js"></script>

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
                    return false;
                }

                monto = $('[name=monto]').val();
                if (monto == 0) {
                    alert('El monto debe ser distinto a cero');
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

            $('[name=numero_documento]').keyup(function () {
                numero_documento = $('[name=numero_documento]').val();
                id_proveedor = $('[name=id_proveedor]').val();

                $.ajax({
                    url: '/reclamos/validar',
                    type: 'POST',
                    data: {
                        numero_documento: numero_documento,
                        id_proveedor: id_proveedor,
                        _token: '{{ csrf_token() }}',
                        id: {{ $reclamo->id }}
                    },
                    success: function (response) {
                        if (response == 'error') {
                            $('[name=numero_documento]').val('');
                            $('[name=numero_documento]').focus();
                            alert('Numero de soporte ya existe con este proveedor');
                        }
                    }
                })
            });
        });
    </script>
@endsection
