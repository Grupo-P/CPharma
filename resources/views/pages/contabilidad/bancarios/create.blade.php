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
        <i class="fas fa-plus"></i>
        Cargar pago bancario a proveedor
    </h1>

    <hr class="row align-items-start col-12">

    <a class="btn btn-outline-info btn-sm" href="/bancarios">
        <i class="fa fa-reply"></i>
        Regresar
    </a>

    <form action="/bancarios" class="mt-3" method="POST">
        @csrf

        <fieldset>
            <table class="table table-borderless table-striped">
                <thead class="thead-dark">
                    <tr>
                        <th scope="row" colspan="5">Proveedor</th>
                    </tr>
                </thead>

                <tbody>
                    <tr>
                        <td width="40%">
                            <label for="nombre_proveedor">
                                Nombre del proveedor *
                            </label>

                            <input autofocus class="form-control" id="proveedores" type="text" required>
                            <input name="id_proveedor" type="hidden">
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
                </tbody>
            </table>

            <table class="table table-borderless table-striped">
                <thead class="thead-dark">
                    <tr>
                        <th scope="row" colspan="5">Pago</th>
                    </tr>
                </thead>

                <tbody>
                    <tr>
                        <td>
                            <label for="retencion_deuda_1">Banco</label>
                            <select class="form-control" name="id_banco" required>
                                <option></option>
                                @foreach($bancos as $banco)
                                    <option data-banco-moneda="{{ $banco->moneda }}" value="{{ $banco->id }}">
                                        {{ $banco->alias_cuenta }}
                                    </option>
                                @endforeach
                            </select>
                        </td>

                        <td>
                            <label for="moneda_banco">Moneda del banco</label>
                            <input class="form-control" name="moneda_banco" readonly type="text">
                        </td>

                        <td>
                            <label for="monto">Pago deuda</label>
                            <input class="form-control" name="monto" type="number" min="0.01" required>
                        </td>

                        <td>
                            <label for="monto_iva">IVA</label>
                            <input class="form-control" name="monto_iva" type="number" min="0.01">
                        </td>

                        <td>
                            <label for="tasa">Tasa</label>
                            <input class="form-control" name="tasa" type="number" lang="en" disabled min="0.01">
                        </td>
                    </tr>
                </tbody>
            </table>

            <table class="table table-borderless table-striped">
                <thead class="thead-dark">
                    <tr>
                        <th scope="row" colspan="4">Retenciones</th>
                    </tr>
                </thead>

                <tbody>
                    <tr>
                        <td>
                            <label for="retencion_deuda_1">Retención deuda (%)</label>
                            <input class="form-control" name="retencion_deuda_1" min="0.01" type="number">
                        </td>

                        <td>
                            <label for="retencion_deuda_2">Retención deuda (%)</label>
                            <input class="form-control" name="retencion_deuda_2" min="0.01" type="number">
                        </td>

                        <td>
                            <label for="retencion_iva">Retención IVA (%)</label>
                            <input class="form-control" name="retencion_iva" min="0.01" type="number">
                        </td>
                    </tr>
                </tbody>
            </table>

            <table class="table table-borderless table-striped">
                <thead class="thead-dark">
                    <tr>
                        <th scope="row" colspan="4">Resultados</th>
                    </tr>
                </thead>

                <tbody>
                    <tr>
                        <td>
                            <label for="pago_deuda_real">Pago real deuda</label>
                            <input class="form-control" readonly name="pago_deuda_real" type="text">
                        </td>

                        <td>
                            <label for="pago_iva_real">Pago real IVA</label>
                            <input class="form-control" readonly name="pago_iva_real" type="text">
                        </td>

                        <td>
                            <label for="monto_banco">Monto banco</label>
                            <input class="form-control" readonly name="monto_banco" type="text">
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
@endsection


@section('scriptsFoot')
    <link href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css" rel="stylesheet">
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>

    <link rel="stylesheet" href="/assets/sweetalert2/sweetalert2.css">
    <script src="/assets/sweetalert2/sweetalert2.js"></script>

    <script src="//unpkg.com/alpinejs" defer></script>

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
                            $('[name=saldo_iva]').val(response.saldo_iva);
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

                calcular_conversion();
            });

            $('[name=monto_proveedor]').keyup(function () {
                calcular_conversion();
            });

            $('[name=monto_proveedor]').blur(function () {
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

            $('[name=monto], [name=retencion_deuda_1], [name=retencion_deuda_2]').keyup(function () {
                calcula_deuda_real();
            });

            $('[name=monto], [name=retencion_deuda_1], [name=retencion_deuda_2]').change(function () {
                calcula_deuda_real();
            });

            function calcula_deuda_real() {
                monto = $('[name=monto]').val();
                retencion_deuda_1 = $('[name=retencion_deuda_1]').val();
                retencion_deuda_2 = $('[name=retencion_deuda_2]').val();

                if (monto != '') {
                    if (retencion_deuda_1 != '') {
                        total_retencion_1 = ((parseFloat(monto) * retencion_deuda_1) / 100);
                    } else {
                        total_retencion_1 = 0
                    }

                    if (retencion_deuda_2 != '') {
                        total_retencion_2 = ((parseFloat(monto) * retencion_deuda_2) / 100);
                    } else {
                        total_retencion_2 = 0
                    }

                    pago_deuda_real = (parseFloat(monto) - total_retencion_1 - total_retencion_2).toFixed(2);

                    $('[name=pago_deuda_real]').val(pago_deuda_real);
                }

                calcula_monto_banco();
            }

            $('[name=monto_iva], [name=retencion_iva]').keyup(function () {
                calcula_iva_real();
            });

            $('[name=monto_iva], [name=retencion_iva]').change(function () {
                calcula_iva_real();
            });

            function calcula_iva_real() {
                monto_iva = $('[name=monto_iva]').val();
                retencion_iva = $('[name=retencion_iva]').val();

                if (monto_iva != '') {
                    if (retencion_iva != '') {
                        total_iva_real = ((parseFloat(monto_iva) * retencion_iva) / 100);
                    } else {
                        total_iva_real = 0
                    }

                    pago_iva_real = (parseFloat(monto_iva) - total_iva_real).toFixed(2);

                    $('[name=pago_iva_real]').val(pago_iva_real);

                    calcula_monto_banco();
                }
            }

            function calcula_monto_banco()
            {
                pago_deuda_real = $('[name=pago_deuda_real]').val();
                pago_iva_real = $('[name=pago_iva_real]').val();

                if (pago_deuda_real != '' && pago_iva_real != '') {
                    pago_deuda_real = parseFloat(pago_deuda_real);
                    pago_iva_real = parseFloat(pago_iva_real);

                    monto_banco = pago_deuda_real + pago_iva_real;
                } else {
                    monto_banco = parseFloat(pago_deuda_real);
                }

                monto_banco = parseFloat(monto_banco);
                monto_banco = monto_banco.toFixed(2);

                $('[name=monto_banco]').val(monto_banco);
            }
        });
    </script>
@endsection
