@extends('layouts.contabilidad')

@section('title')
    Registro de bancarios
@endsection

@section('content')
<!-- Modal Guardar -->
@if (session('Error'))
<div aria-hidden="true" aria-labelledby="exampleModalCenterTitle" class="modal fade" id="exampleModalCenter" role="dialog" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title text-danger" id="exampleModalCenterTitle">
                    <i class="fas fa-exclamation-triangle text-danger">
                    </i>
                    {{ session('Error') }}
                </h5>

                <button aria-label="Close" class="close" data-dismiss="modal" type="button">
                    <span aria-hidden="true">
                        ×
                    </span>
                </button>
            </div>

            <div class="modal-body">
                <h4 class="h6">
                    El pago bancario no fue almacenado, ocurrió un error
                </h4>
            </div>

            <div class="modal-footer">
                <button class="btn btn-outline-success" data-dismiss="modal" type="button">
                    Aceptar
                </button>
            </div>
        </div>
    </div>
</div>
@endif

<h1 class="h5 text-info">
    <i class="fas fa-plus">
    </i>
    Cargar pago bancario a proveedor
</h1>

<hr class="row align-items-start col-12">

<a class="btn btn-outline-info btn-sm" href="/bancarios">
    <i class="fa fa-reply">
    </i>
    Regresar
</a>

<br>
<br>

<form action="/bancarios" method="POST">
    @csrf
    <fieldset>
        <table class="table table-borderless table-striped">
            <thead class="thead-dark">
                <tr>
                    <th scope="row">
                    </th>
                    <th scope="row">
                    </th>
                </tr>
            </thead>

            <tbody>
                <tr>
                    <th scope="row">
                        <label for="nombre_proveedor">
                            Nombre del proveedor *
                        </label>
                    </th>
                    <td>
                        <input autofocus class="form-control" id="proveedores" type="text">
                            <input name="id_proveedor" type="hidden">
                            </input>
                        </input>
                    </td>
                </tr>

                <tr>
                    <th scope="row">
                        <label for="moneda">
                            Moneda del proveedor
                        </label>
                    </th>
                    <th scope="row">
                        <input class="form-control" name="moneda" readonly type="text"/>
                    </th>
                </tr>

                <tr>
                    <th scope="row">
                        <label for="saldo">
                            Saldo del proveedor
                        </label>
                    </th>
                    <th scope="row">
                        <input class="form-control" name="saldo" readonly type="text"/>
                    </th>
                </tr>

                <tr>
                    <th scope="row">
                        <label for="tasa_proveedor">
                            Tasa del proveedor
                        </label>
                    </th>
                    <th scope="row">
                        <input class="form-control" name="tasa_proveedor" readonly type="text"/>
                    </th>
                </tr>

                <tr>
                    <th scope="row">
                        <label for="id_banco">
                            Banco *
                        </label>
                    </th>
                    <td>
                        <select class="form-control" name="id_banco" required>
                            <option>
                            </option>
                            @foreach($bancos as $banco)
                            <option data-banco-moneda="{{ $banco->moneda }}" value="{{ $banco->id }}">
                                {{ $banco->alias_cuenta }}
                            </option>
                            @endforeach
                        </select>
                    </td>
                </tr>

                <tr>
                    <th scope="row">
                        <label for="moneda_banco">
                            Moneda del banco
                        </label>
                    </th>
                    <th scope="row">
                        <input class="form-control" name="moneda_banco" readonly type="text"/>
                    </th>
                </tr>

                <tr>
                    <th scope="row">
                        <label for="monto_proveedor">
                            Monto al proveedor
                        </label>
                    </th>
                    <td>
                        <input class="form-control" lang="en" name="monto_proveedor" required step="0.01" type="number">
                    </td>
                </tr>

                <tr class="append">
                    <th scope="row">
                        <label for="tasa">
                            Tasa *
                        </label>
                    </th>
                    <td>
                        <input class="form-control" lang="en" disabled name="tasa" step="0.01" type="number">
                        </input>
                    </td>
                </tr>

                <tr>
                    <th scope="row">
                        <label for="monto">
                            Monto del banco *
                        </label>
                    </th>
                    <td>
                        <input class="form-control" lang="en" in="1" readonly name="monto" required step="0.01" type="text">
                    </td>
                </tr>

                <tr>
                    <th scope="row">
                        <label for="comentario">
                            Comentario
                        </label>
                    </th>
                    <td>
                        <input class="form-control" maxlength="200" minlength="5" name="comentario" type="text"/>
                    </td>
                </tr>
            </tbody>
        </table>

        <p class="text-danger font-weight-bold">
            * Campos obligatorios
        </p>

        <input class="btn btn-outline-success btn-md" type="submit" value="Guardar">
        </input>
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
<link href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css" rel="stylesheet">
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>

<link rel="stylesheet" href="/assets/sweetalert2/sweetalert2.css">
<script src="/assets/sweetalert2/sweetalert2.js"></script>

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

                $.ajax({
                    type: 'GET',
                    url: '/bancarios/create?proveedor=1',
                    data: {
                        id_proveedor: $('[name=id_proveedor]').val()
                    },
                    success: function (response) {
                        $('[name=saldo]').val(response.saldo);
                        $('[name=tasa_proveedor]').val(response.tasa);
                    }
                })
            }
        });

        $('[name=moneda]').change(function () {
            activar_tasa();
        });

        $('[name=id_banco]').change(function () {
            activar_tasa();

            moneda_banco = $('option:selected').attr('data-banco-moneda');
            $('[name=moneda_banco]').val(moneda_banco);
        });

        $('[name=monto_proveedor]').keyup(function () {
            calcular_conversion();
        });

        $('[name=tasa]').keyup(function () {
            calcular_conversion();
        });

        function calcular_conversion() {
            banco = $('option:selected').attr('data-banco-moneda');
            proveedor = $('[name=moneda]').val();
            monto = $('[name=monto_proveedor]').val();
            tasa = $('[name=tasa]').val();

            console.log(banco, proveedor, monto, tasa);

            if (banco != '' && proveedor != '') {
                if (banco != proveedor) {
                    if (monto != '' && tasa != '') {
                        $.ajax({
                            method: 'GET',
                            url: '/bancarios/create?conversion=1',
                            data: {
                                banco: banco,
                                proveedor: proveedor,
                                monto: monto,
                                tasa: tasa,
                            },
                            success: function (response) {
                                console.log(response);
                                monto_proveedor = new Intl.NumberFormat('de-DE').format(response);
                                $('[name=monto]').val(monto_proveedor);
                            }
                        });
                    }
                } else {
                    if (monto != '') {
                        monto = new Intl.NumberFormat('de-DE').format(monto);
                        $('[name=monto]').val(monto);
                    }
                }
            }
        }

        function activar_tasa() {
            banco = $('option:selected').attr('data-banco-moneda');
            proveedor = $('[name=moneda]').val();

            if (banco == '' || proveedor == '') {
                return false;
            }

            if (banco != proveedor) {
                $.ajax({
                    method: 'POST',
                    url: '/bancarios/validar',
                    data: {
                        id_proveedor: $('[name=id_proveedor]').val(),
                        id_banco: $('[name=id_banco]').val(),
                        _token: '{{ csrf_token() }}'
                    },
                    success: function (response) {
                        $('[name=tasa]').attr('min', response.min);
                        $('[name=tasa]').attr('max', response.max);
                    }
                });

                $('[name=tasa]').attr('disabled', false);
                $('[name=tasa]').attr('required', true);
            } else {
                $('[name=tasa]').val('');
                $('[name=tasa]').attr('disabled', true);
                $('[name=tasa]').attr('required', false);
            }
        }

        $('form').submit(function (event) {
            resultado = proveedoresJson.find(elemento => elemento.label == $('#proveedores').val());

            if (!resultado) {
                event.preventDefault();
                alert('Debe seleccionar un proveedor válido');
                bancario = false;
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
