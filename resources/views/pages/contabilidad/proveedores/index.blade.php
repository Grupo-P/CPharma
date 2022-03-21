@extends('layouts.contabilidad')

@section('title')
    Proveedores
@endsection


@section('scriptsHead')
    <script>
        function mostrar_ocultar(that, elemento) {
            if (that.checked) {
                return $('.' + elemento).show();
            }

            return $('.' + elemento).hide();
        }

        campos = ['nombre', 'representante', 'rif_cedula', 'direccion', 'tasa', 'plan_cuentas', 'moneda_subtotal', 'saldo_subtotal', 'moneda_iva', 'saldo_iva', 'creado_por', 'estado'];

        function mostrar_todas(that) {
            if (that.checked) {
                for (var i = campos.length - 1; i >= 0; i--) {
                    $('.' + campos[i]).show();
                    $('[name='+campos[i]+']').prop('checked', true);
                }
            } else {
                for (var i = campos.length - 1; i >= 0; i--) {
                    $('.' + campos[i]).hide();
                    $('[name='+campos[i]+']').prop('checked', false);
                }
            }
        }
    </script>
@endsection


@section('content')
    <!-- Modal Guardar -->
    @if (session('Saved'))
        <div class="modal fade" id="exampleModalCenter" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
          <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
              <div class="modal-header">
                <h5 class="modal-title text-info" id="exampleModalCenterTitle"><i class="fas fa-info text-info"></i>{{ session('Saved') }}</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                </button>
              </div>
              <div class="modal-body">
                <h4 class="h6">Proveedor almacenado con exito</h4>
              </div>
              <div class="modal-footer">
                <button type="button" class="btn btn-outline-success" data-dismiss="modal">Aceptar</button>
              </div>
            </div>
          </div>
        </div>
    @endif

    <!-- Modal Editar -->
    @if (session('Updated'))
        <div class="modal fade" id="exampleModalCenter" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
          <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
              <div class="modal-header">
                <h5 class="modal-title text-info" id="exampleModalCenterTitle"><i class="fas fa-info text-info"></i>{{ session('Updated') }}</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                </button>
              </div>
              <div class="modal-body">
                <h4 class="h6">Proveedor modificado con exito</h4>
              </div>
              <div class="modal-footer">
                <button type="button" class="btn btn-outline-success" data-dismiss="modal">Aceptar</button>
              </div>
            </div>
          </div>
        </div>
    @endif

    <!-- Modal activar -->
    @if (session('Activar'))
        <div class="modal fade" id="exampleModalCenter" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
          <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
              <div class="modal-header">
                <h5 class="modal-title text-info" id="exampleModalCenterTitle"><i class="fas fa-info text-info"></i>{{ session('Activar') }}</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                </button>
              </div>
              <div class="modal-body">
                <h4 class="h6">Proveedor activado con exito</h4>
              </div>
              <div class="modal-footer">
                <button type="button" class="btn btn-outline-success" data-dismiss="modal">Aceptar</button>
              </div>
            </div>
          </div>
        </div>
    @endif

    <!-- Modal desactivar -->
    @if (session('Desactivar'))
        <div class="modal fade" id="exampleModalCenter" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
          <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
              <div class="modal-header">
                <h5 class="modal-title text-info" id="exampleModalCenterTitle"><i class="fas fa-info text-info"></i>{{ session('Desactivar') }}</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                </button>
              </div>
              <div class="modal-body">
                <h4 class="h6">Proveedor desactivado con exito</h4>
              </div>
              <div class="modal-footer">
                <button type="button" class="btn btn-outline-success" data-dismiss="modal">Aceptar</button>
              </div>
            </div>
          </div>
        </div>
    @endif

    <div class="modal fade" id="ver_campos" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel">Mostrar u ocultar columnas</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">
            <div class="form-group">
                <input type="checkbox" onclick="mostrar_ocultar(this, 'nombre')" name="nombre" checked>
                Nombre
            </div>

            <div class="form-group">
                <input type="checkbox" onclick="mostrar_ocultar(this, 'representante')" name="representante" checked>
                Tipo
            </div>

            <div class="form-group">
                <input type="checkbox" onclick="mostrar_ocultar(this, 'rif_cedula')" name="rif_cedula" checked>
                RIF/Cédula
            </div>

            <div class="form-group">
                <input type="checkbox" onclick="mostrar_ocultar(this, 'direccion')" name="direccion" checked>
                Dirección
            </div>

            <div class="form-group">
                <input type="checkbox" onclick="mostrar_ocultar(this, 'tasa')" name="tasa" checked>
                Tasa
            </div>

            <div class="form-group">
                <input type="checkbox" onclick="mostrar_ocultar(this, 'plan_cuentas')" name="plan_cuentas" checked>
                Plan de cuentas
            </div>

            <div class="form-group">
                <input type="checkbox" onclick="mostrar_ocultar(this, 'moneda_subtotal')" name="moneda_subtotal" checked>
                Moneda subtotal
            </div>

            <div class="form-group">
                <input type="checkbox" onclick="mostrar_ocultar(this, 'saldo_subtotal')" name="saldo_subtotal" checked>
                Saldo subtotal
            </div>

            <div class="form-group">
                <input type="checkbox" onclick="mostrar_ocultar(this, 'moneda_iva')" name="moneda_iva" checked>
                Moneda IVA
            </div>

            <div class="form-group">
                <input type="checkbox" onclick="mostrar_ocultar(this, 'saldo_iva')" name="saldo_iva" checked>
                Saldo IVA
            </div>

            <div class="form-group">
                <input type="checkbox" onclick="mostrar_ocultar(this, 'creado_por')" name="creado_por" checked>
                Creado por
            </div>

            <div class="form-group">
                <input type="checkbox" onclick="mostrar_ocultar(this, 'estado')" name="estado" checked>
                Estado
            </div>

            <div class="form-group">
                <input type="checkbox" onclick="mostrar_todas(this)" name="Marcar todas" checked>
                Marcar todas
            </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
          </div>
        </div>
      </div>
    </div>

    <h1 class="h5 text-info">
        <i class="fas fa-dolly"></i>
        Proveedor
    </h1>

    <hr class="row align-items-start col-12">
    <table style="width:100%;" class="CP-stickyBar">
        <tr>
            <td style="width:10%;" align="center">
                <a href="{{ url('/proveedores/create') }}" role="button" class="btn btn-outline-info btn-sm"
                style="display: inline; text-align: left;">
                <i class="fa fa-plus"></i>
                    Agregar
                </a>
            </td>

            <td style="width:90%;">
                <div class="input-group md-form form-sm form-1 pl-0 CP-stickyBar">
                    <div class="input-group-prepend">
                        <span class="input-group-text purple lighten-3" id="basic-text1"><i class="fas fa-search text-white"
                    aria-hidden="true"></i></span>
                    </div>
                <input class="form-control my-0 py-1 CP-stickyBar" type="text" placeholder="Buscar..." aria-label="Search" id="myInput" onkeyup="FilterAllTable()" autofocus="autofocus">
                </div>
            </td>
        </tr>
    </table>
    <br/>

    <h6 align="center"><a href="" data-toggle="modal" data-target="#ver_campos"><i class="fa fa-eye"></i> Mostrar u ocultar campos<a></h6>

    <table class="table table-striped table-borderless col-12 sortable" id="myTable">
        <thead class="thead-dark">
            <tr>
                <th nowrap scope="col" class="CP-sticky">#</th>
                <th nowrap scope="col" class="nombre CP-sticky">Nombre</th>
                <th nowrap scope="col" class="representante CP-sticky">Representante</th>
                <th nowrap scope="col" class="rif_cedula CP-sticky">RIF/Cédula</th>
                <th nowrap scope="col" class="direccion CP-sticky">Dirección</th>
                <th nowrap scope="col" class="tasa CP-sticky">Tasa</th>
                <th nowrap scope="col" class="plan_cuentas CP-sticky">Plan de cuentas</th>
                <th nowrap scope="col" class="moneda_subtotal CP-sticky">Moneda subtotal</th>
                <th nowrap scope="col" class="saldo_subtotal CP-sticky">Saldo subtotal (Exento + Base)</th>
                <th nowrap scope="col" class="moneda_iva CP-sticky">Moneda IVA</th>
                <th nowrap scope="col" class="saldo_iva CP-sticky">Saldo IVA</th>
                <th nowrap scope="col" class="creado_por CP-sticky">Creado por</th>
                <th nowrap scope="col" class="estado CP-sticky">Estado</th>
                <th nowrap scope="col" class="CP-sticky">Acciones</th>
            </tr>
        </thead>
        <tbody>
        @foreach($proveedores as $proveedor)
            <tr>
              <th class="text-center" nowrap>{{$proveedor->id}}</th>
              <td class="text-center nombre" nowrap>{{$proveedor->nombre_proveedor}}</td>
              <td class="text-center representante" nowrap>{{$proveedor->nombre_representante}}</td>
              <td class="text-center rif_cedula " nowrap>{{$proveedor->rif_ci}}</td>
              <td class="text-center direccion" nowrap>{{$proveedor->direccion}}</td>
              <td class="text-center tasa" nowrap>{{$proveedor->tasa}}</td>
              <td class="text-center plan_cuentas" nowrap>{{$proveedor->plan_cuentas}}</td>
              <td class="text-center moneda_subtotal" nowrap>{{$proveedor->moneda}}</td>
              <td class="text-center saldo_subtotal" nowrap>{{number_format($proveedor->saldo, 2, ',', '.')}}</td>
              <td class="text-center moneda_iva" nowrap>{{$proveedor->moneda_iva}}</td>
              <td class="text-center saldo_iva" nowrap>{{number_format($proveedor->saldo_iva, 2, ',', '.')}}</td>
              <td class="text-center creado_por" nowrap>{{$proveedor->usuario_creado}}</td>
              <td class="text-center estado" nowrap>{{($proveedor->deleted_at)?'Inactivo':'Activo'}}</td>
              <td class="text-center" nowrap>
                <a href="/proveedores/{{$proveedor->id}}" role="button" class="btn btn-outline-success btn-sm" data-toggle="tooltip" data-placement="top" title="Detalle">
                    <i class="far fa-eye"></i>
                </a>

                @if(Auth::user()->departamento != 'OPERACIONES')
                    <a href="/proveedores/{{$proveedor->id}}/edit" role="button" class="btn btn-outline-info btn-sm" data-toggle="tooltip" data-placement="top" title="Modificar">
                        <i class="fas fa-edit"></i>
                    </a>
                @endif

                @if(Auth::user()->departamento == 'TECNOLOGIA' || Auth::user()->departamento == 'GERENCIA')
                    @if($proveedor->deleted_at)
                        <form action="/proveedores/{{$proveedor->id}}" method="POST" style="display: inline;">
                            @method('DELETE')
                            @csrf
                            <button type="submit" name="Activar" role="button" class="btn btn-outline-info btn-sm" data-toggle="tooltip" data-placement="top" title="Activar"><i class="fa fa-check"></i></button>
                        </form>
                    @else
                        <form action="/proveedores/{{$proveedor->id}}" method="POST" style="display: inline;">
                            @method('DELETE')
                            @csrf
                            <button type="submit" name="Desactivar" role="button" class="btn btn-outline-danger btn-sm" data-toggle="tooltip" data-placement="top" title="Desactivar"><i class="fa fa-ban"></i></button>
                        </form>
                    @endif
                @endif

              </td>
            </tr>
        @endforeach
        </tbody>
    </table>

    <script>
        $(document).ready(function(){
            $('[data-toggle="tooltip"]').tooltip();
        });
        $('#exampleModalCenter').modal('show')
    </script>

@endsection
