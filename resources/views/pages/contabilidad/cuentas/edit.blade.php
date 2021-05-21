@extends('layouts.contabilidad')

@section('title')
    Plan de cuentas
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
                <h4 class="h6">El plan de cuenta no fue almacenado, el correo ya esta registrado</h4>
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
        Editar plan de cuenta
    </h1>

    <hr class="row align-items-start col-12">

    <a href="/cuentas" class="btn btn-outline-info btn-sm"><i class="fa fa-reply"></i> Regresar</a>

    <br>
    <br>

    <form method="POST" action="/cuentas/{{$cuenta->id}}">
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
                        <th scope="row"><label for="nombre">Nombre del plan de cuentas</label></th>
                        <td><input name="nombre" class="form-control" value="{{$cuenta->nombre}}" required></td>
                    </tr>

                    <tr>
                        <th scope="row"><label for="pertenece_a">Pertenece a</label></th>
                        <td>
                            <select name="pertenece_a" class="form-control" required>
                                <option  {{ ($cuenta->pertenece_a == 'Principal') ? 'selected' : '' }} value="Principal">Principal</option>
                                @foreach($cuentas as $item)
                                    <option {{ ($item->id == $cuenta->pertenece_a) ? 'selected' : '' }} value="{{ $item->id }}">{{ $item->nombre }}</option>
                                @endforeach
                            </select>
                        </td>
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
        $('#exampleModalCenter').modal('show')
    </script>
@endsection
