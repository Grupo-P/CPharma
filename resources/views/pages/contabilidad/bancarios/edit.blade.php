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
        <i class="fas fa-edit"></i>
        Modificar pago bancario a proveedor
    </h1>

    <hr class="row align-items-start col-12">

    <a href="/bancarios" class="btn btn-outline-info btn-sm"><i class="fa fa-reply"></i> Regresar</a>

    <br>
    <br>

    <form method="POST" action="{{ route('bancarios.update', $pago) }}">
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
                        <th scope="row"><label for="nombre_proveedor">Nombre del proveedor</label></th>
                        <td>
                            <input value="{{ $pago->proveedor->nombre_proveedor . ' | ' . $pago->proveedor->rif_ci }}" autofocus class="form-control" type="text" id="proveedores">
                            <input value="{{ $pago->proveedor->id }}" type="hidden" name="id_proveedor">
                        </td>
                    </tr>

                    <tr>
                        <th scope="row"><label for="monto">Monto</label></th>
                        <td><input type="number" required value="{{ $pago->monto }}" class="form-control" name="monto" min="1"></td>
                    </tr>

                    <tr>
                        <th scope="row"><label for="id_banco">Banco</label></th>
                        <td>
                            <select name="id_banco" class="form-control" required>
                                <option value=""></option>
                                @foreach($bancos as $banco)
                                    <option {{ ($banco->id == $pago->banco->id) ? 'selected' : '' }} value="{{ $banco->id }}">{{ $banco->nombre_banco }}</option>
                                @endforeach
                            </select>
                        </td>
                    </tr>

                    <tr>
                        <th scope="row"><label for="comentario">Comentario</label></th>
                        <td><input type="text" minlength="5" maxlength="200" class="form-control" value="{{ $banco->comentario }}" name="comentario"></td>
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
        });
    </script>
@endsection
