@extends('layouts.contabilidad')

@section('title', 'Crear movimiento')

@section('content')
  <!-- Modal Guardar -->
  @if(session('Error'))
    <div class="modal fade" id="exampleModalCenter" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
      <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title text-danger" id="exampleModalCenterTitle">
              <i class="fas fa-exclamation-triangle text-danger"></i>
              {{ session('Error') }}
            </h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">
            <h4 class="h6">
              El movimiento no fue almacenado
            </h4>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-outline-success" data-dismiss="modal">Aceptar</button>
          </div>
        </div>
      </div>
    </div>
  @endif

  <h1 class="h5 text-info">
    <i class="fas fa-plus"></i>&nbsp;

    Agregar pago prepagado
  </h1>
  <hr class="row align-items-start col-12">

  <form action="/prepagados" method="GET" style="display: inline;">  
    <button type="submit" role="button" class="btn btn-outline-info btn-sm"data-placement="top">
      <i class="fa fa-reply"></i> Regresar
    </button>
  </form>

  <br/><br/>

  {!! Form::open(['route' => 'prepagados.store', 'method' => 'POST', 'class' => 'form-group']) !!}
    <fieldset>
      <table class="table table-borderless table-striped">
        <thead class="thead-dark">
          <tr>
            <th colspan="2" scope="row"></th>
            <th colspan="3" scope="row"></th>
          </tr>
        </thead>

        <tbody>
          <tr>
            <td width="40%">
                <label for="nombre_proveedor">
                    Nombre del proveedor *
                </label>

                <input autofocus class="form-control" id="proveedores" type="text" required>
                <input name="id_proveedor" type="hidden" required>
            </td>

            <td>
                <label for="moneda">Moneda proveedor</label>
                <input readonly class="form-control" name="moneda" type="text">
            </td>

            <td>
                <label for="saldo">Saldo</label>
                <input readonly class="form-control" name="saldo" type="text">
            </td>

            <td>
                <label for="saldo_iva">IVA</label>
                <input readonly class="form-control" name="saldo_iva" type="text">
            </td>

             <td>
                <label for="moneda_iva">Moneda IVA</label>
                <input readonly class="form-control" name="moneda_iva" type="text">
            </td>
          </tr>

          <tr>
            <th colspan="2" scope="row">{!! Form::label('monto', 'Monto') !!}</th>
            <td colspan="3">
              <input type="number" name="monto" min="1" value="0" step="0.01" class="form-control" required autofocus>
            </td>
          </tr>

          <tr>
            <th colspan="2" scope="row">{!! Form::label('monto_iva', 'Monto IVA') !!}</th>
            <td colspan="3">
              <input type="number" name="monto_iva" min="0" value="0" step="0.01" class="form-control" autofocus>
            </td>
          </tr>
        </tbody>
      </table>

      {!! Form::submit('Guardar', ['class' => 'btn btn-outline-success btn-md']) !!}
    </fieldset>
  {!! Form::close()!!}

  <script>
    $(document).ready(function() {
      $('[data-toggle="tooltip"]').tooltip();
    });

    $('#exampleModalCenter').modal('show');
  </script>
@endsection


@section('scriptsFoot')
    <link href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css" rel="stylesheet">
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>

    <script>
        $(document).ready(function () {
            var json = {!! json_encode($proveedores) !!};

            $('#proveedores').autocomplete({
                source: json,
                autoFocus: true,
                select: function (event, ui) {
                    $('[name=id_proveedor]').val(ui.item.id);
                    $('[name=moneda]').val(ui.item.moneda);
                    $('[name=moneda_iva]').val(ui.item.moneda_iva);

                    $.ajax({
                        type: 'GET',
                        url: '/bancarios/create?proveedor=1',
                        data: {
                            id_proveedor: $('[name=id_proveedor]').val()
                        },
                        success: function (response) {
                            $('[name=saldo]').val(response.saldo);
                            $('[name=saldo_iva]').val(response.saldo_iva);
                            $('[name=tasa_proveedor]').val(response.tasa);
                        },
                        error: function (error) {
                            $('body').html(error.responseText);
                        }
                    });
                }
            });
        });
    </script>
@endsection
