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
                <h4 class="h6">El reclamo no fue almacenado, el correo ya esta registrado</h4>
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
        Cargar reclamo a proveedor
    </h1>

    <hr class="row align-items-start col-12">

    <a href="/reclamos" class="btn btn-outline-info btn-sm"><i class="fa fa-reply"></i> Regresar</a>

    <br>
    <br>

    <form method="POST" action="/reclamos">
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
                            <input required autofocus class="form-control" type="text" id="proveedores">
                            <input type="hidden" name="id_proveedor">
                        </td>
                    </tr>

                    <tr>
                        <th scope="row"><label for="moneda">Moneda *</label></th>
                        <td><input name="moneda" readonly class="form-control" required></td>
                    </tr>

                    <tr>
                        <th scope="row"><label for="monto">Monto*</label></th>
                        <td>
                            <input type="number" required class="form-control" name="monto" step="0.01">
                        </td>
                    </tr>

                    <tr>
                        <th scope="row"><label for="documento_soporte_reclamo">Documento soporte reclamo *</label></th>
                        <td>
                            <select name="documento_soporte_reclamo" required class="form-control">
                                <option value=""></option>
                                @foreach($documentos as $documento)
                                    <option value="{{ $documento }}">{{ $documento }}</option>
                                @endforeach
                            </select>
                        </td>
                    </tr>

                    <tr>
                        <th scope="row"><label for="numero_documento">Número documento*</label></th>
                        <td><input name="numero_documento" required class="form-control" minlength="5" maxlength="20"></td>
                    </tr>

                    <tr>
                        <th scope="row"><label for="sede">Sede *</label></th>
                        <td>
                            <select name="sede" required class="form-control">
                                <option value=""></option>
                                @foreach($sedes as $sede)
                                    <option value="{{ $sede->razon_social }}">{{ $sede->razon_social }}</option>
                                @endforeach
                            </select>
                        </td>
                    </tr>

                    <tr>
                        <th scope="row"><label for="comentario">Comentario</label></th>
                        <td>
                            <input type="text" class="form-control" name="comentario" minlength="10" maxlength="200">
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

                monto = $('[name=monto]').val();
                if (monto == 0) {
                    alert('El monto debe ser distinto a cero');
                    event.preventDefault();
                }
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
                        _token: '{{ csrf_token() }}'
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
