@extends('layouts.contabilidad')

@section('title')
    Bancos
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
                <h4 class="h6">El banco no fue almacenado, el correo ya esta registrado</h4>
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
        Modificar banco
    </h1>

    <hr class="row align-items-start col-12">

    <a href="/bancos" class="btn btn-outline-info btn-sm"><i class="fa fa-reply"></i> Regresar</a>

    <br>
    <br>

    <form method="POST" action="{{ '/bancos/' . $banco->id }}">
        @method('PUT')
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
                        <th scope="row"><label for="nombre_banco">Nombre del banco *</label></th>
                        <td><input name="nombre_banco" class="form-control" required minlength="5" maxlength="50" value="{{ $banco->nombre_banco }}"></td>
                    </tr>

                    <tr>
                        <th scope="row"><label for="nombre_titular">Nombre del titular *</label></th>
                        <td><input name="nombre_titular" class="form-control" required minlength="5" maxlength="50" value="{{ $banco->nombre_titular }}"></td>
                    </tr>

                    <tr>
                        <th scope="row"><label for="alias_cuenta">Alias de la cuenta *</label></th>
                        <td><input name="alias_cuenta" class="form-control" required minlength="3" maxlength="10" value="{{ $banco->alias_cuenta }}"></td>
                    </tr>

                    <tr>
                        <th scope="row"><label for="moneda">Moneda *</label></th>
                        <td>
                            <select name="moneda" required class="form-control">
                                <option value=""></option>
                                @foreach($monedas as $moneda)
                                    <option {{ ($moneda == $banco->moneda) ? 'selected' : '' }} value="{{ $moneda }}">{{ $moneda }}</option>
                                @endforeach
                            </select>
                        </td>
                    </tr>
                </tbody>
            </table>

            <p class="text-danger font-weight-bold">* Campos obligatorios</p>

            <input type="button" class="btn btn-outline-success btn-md" value="Guardar">
        </fieldset>
    </form>

    <script>
        $(document).ready(function(){
            $('[data-toggle="tooltip"]').tooltip();
        });

        $('#exampleModalCenter').modal('show');

        $('[type=button]').click(function () {
            alias_cuenta = $('[name=alias_cuenta]').val();

            if (alias_cuenta != '') {
                $.ajax({
                    type: 'GET',
                    url: '/bancos/validar',
                    data: {
                        alias_cuenta: alias_cuenta,
                        id: {{ $banco->id }}
                    },
                    success: function (response) {
                        if (response == 'error') {
                            alert('El alias de la cuenta que intenta registrar ya existe!');
                            $('[name=alias_cuenta]').focus();
                        }

                        if (response == 'success') {
                            $('form').submit();
                        }
                    }
                });
            }
        });

        $(document).ready(function() {
          $(window).keydown(function(event){
            if(event.keyCode == 13) {
              event.preventDefault();
              return false;
            }
          });
        });
    </script>
@endsection
