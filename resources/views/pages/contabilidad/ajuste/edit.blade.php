@extends('layouts.contabilidad')

@section('title')
    Registro de ajuste
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
                <h4 class="h6">El ajuste no fue almacenado, el correo ya esta registrado</h4>
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
        Editar ajuste de proveedor
    </h1>

    <hr class="row align-items-start col-12">

    <a href="/ajuste" class="btn btn-outline-info btn-sm"><i class="fa fa-reply"></i> Regresar</a>

    <br>
    <br>

    <form method="POST" action="{{ route('ajuste.update', $ajuste) }}">
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
                            <input readonly class="form-control" type="text" id="proveedores" value="{{ $ajuste->proveedor->nombre_proveedor . ' | ' . $ajuste->proveedor->rif_ci }}">
                            <input type="hidden" value="{{ $ajuste->proveedor->id }}" name="id_proveedor">
                        </td>
                    </tr>

                    <tr>
                        <th scope="row"><label for="monto">Monto subtotal (Exento + base) *</label></th>
                        <td>
                            <input type="number" step="0.01" required class="form-control" readonly value="{{ $ajuste->monto }}" name="monto">
                        </td>
                    </tr>

                    <tr>
                        <th scope="row"><label for="saldo">Saldo del proveedor</label></th>
                        <td>
                            <input value="{{ number_format($ajuste->proveedor->saldo, 2, '.', '.') }}" type="text" step="0.01" readonly class="form-control" name="saldo">
                        </td>
                    </tr>

                    <tr>
                        <th scope="row"><label for="moneda">Moneda IVA</label></th>
                        <td>
                            <input value="{{ $ajuste->proveedor->moneda_iva }}" type="text" readonly class="form-control" name="moneda">
                        </td>
                    </tr>

                    <tr>
                        <th scope="row"><label for="monto">Monto IVA</label></th>
                        <td>
                            <input type="number" step="0.01" required class="form-control" readonly value="{{ $ajuste->monto_iva }}" name="monto_iva">
                        </td>
                    </tr>

                    <tr>
                        <th scope="row"><label for="comentario">Comentario *</label></th>
                        <td>
                            <input type="text" required class="form-control" value="{{ $ajuste->comentario }}" name="comentario" minlength="10" maxlength="200">
                        </td>
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
                    $('[name=saldo]').val(ui.item.saldo);
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
        });
    </script>
@endsection
