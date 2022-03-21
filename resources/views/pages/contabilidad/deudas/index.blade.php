@extends('layouts.contabilidad')

@section('title')
    Registro de deudas
@endsection


@section('scriptsHead')
    <script>
        function mostrar_ocultar(that, elemento) {
            if (that.checked) {
                return $('.' + elemento).show();
            }

            return $('.' + elemento).hide();
        }

        campos = ['nombre_proveedor', 'rif_proveedor', 'fecha_registro', 'moneda_subtotal', 'monto_subtotal', 'moneda_iva', 'monto_iva', 'dias_credito', 'documento_soporte_deuda', 'numero_documento', 'creado_por', 'sede', 'estado'];

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
                <h4 class="h6">Deudas almacenado con exito</h4>
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
                <h4 class="h6">Deudas modificado con exito</h4>
              </div>
              <div class="modal-footer">
                <button type="button" class="btn btn-outline-success" data-dismiss="modal">Aceptar</button>
              </div>
            </div>
          </div>
        </div>
    @endif

    <!-- Modal Eliminar -->
    @if (session('Deleted'))
        <div class="modal fade" id="exampleModalCenter" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
          <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
              <div class="modal-header">
                <h5 class="modal-title text-info" id="exampleModalCenterTitle"><i class="fas fa-info text-info"></i>{{ session('Deleted') }}</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                </button>
              </div>
              <div class="modal-body">
                <h4 class="h6">Deudas eliminado con exito</h4>
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
                <input type="checkbox" onclick="mostrar_ocultar(this, 'nombre_proveedor')" name="nombre_proveedor" checked>
                Nombre del proveedor
            </div>

            <div class="form-group">
                <input type="checkbox" onclick="mostrar_ocultar(this, 'rif_proveedor')" name="rif_proveedor" checked>
                RIF/CI del proveedor
            </div>

            <div class="form-group">
                <input type="checkbox" onclick="mostrar_ocultar(this, 'fecha_registro')" name="fecha_registro" checked>
                Fecha de registro
            </div>

            <div class="form-group">
                <input type="checkbox" onclick="mostrar_ocultar(this, 'moneda_subtotal')" name="moneda_subtotal" checked>
                Moneda subtotal
            </div>

            <div class="form-group">
                <input type="checkbox" onclick="mostrar_ocultar(this, 'monto_subtotal')" name="monto_subtotal" checked>
                Monto subtotal
            </div>

            <div class="form-group">
                <input type="checkbox" onclick="mostrar_ocultar(this, 'moneda_iva')" name="moneda_iva" checked>
                Moneda IVA
            </div>

            <div class="form-group">
                <input type="checkbox" onclick="mostrar_ocultar(this, 'monto_iva')" name="monto_iva" checked>
                Monto IVA
            </div>

            <div class="form-group">
                <input type="checkbox" onclick="mostrar_ocultar(this, 'documento_soporte_deuda')" name="documento_soporte_deuda" checked>
                Documento soporte deuda
            </div>

            <div class="form-group">
                <input type="checkbox" onclick="mostrar_ocultar(this, 'numero_documento')" name="numero_documento" checked>
                Número de documento
            </div>

            <div class="form-group">
                <input type="checkbox" onclick="mostrar_ocultar(this, 'creado_por')" name="creado_por" checked>
                Creado por
            </div>

            <div class="form-group">
                <input type="checkbox" onclick="mostrar_ocultar(this, 'sede')" name="sede" checked>
                Sede
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
        <i class="fas fa-info-circle"></i>
        Registro de deudas
    </h1>

    <hr class="row align-items-start col-12">

    <form action="">
        <div class="row">
            <div class="col">
                <div class="form-group">
                    <label for="numero_documento">Numero de documento</label>
                    <input value="{{ isset($_GET['numero_documento']) ? $_GET['numero_documento'] : '' }}" type="text" name="numero_documento" class="form-control">
                </div>
            </div>

            <div class="col">
                <div class="form-group">
                    <label for="id_proveedor">Proveedor</label>
                    <select name="id_proveedor" class="form-control">
                        <option value="Todos">Todos</option>
                        @foreach($proveedores as $proveedor)
                            <option {{ (isset($_GET['id_proveedor']) && $_GET['id_proveedor'] == $proveedor->id) ? 'selected' : '' }} value="{{ $proveedor->id }}">{{ $proveedor->nombre_proveedor . ' | ' . $proveedor->rif_ci }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="col">
                <div class="form-group">
                    <label for="registrado_por">Registrado por</label>
                    <select name="registrado_por" class="form-control">
                        <option value="Todos">Todos</option>
                        @foreach($users as $user)
                            <option {{ (isset($_GET['registrado_por']) && $_GET['registrado_por'] == $user->name) ? 'selected' : '' }} value="{{ $user->name }}">{{ $user->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="col">
                <div class="form-group">
                    <label for="cantidad_registros">Cantidad de registros</label>
                    <select name="cantidad_registros" class="form-control">
                        <option {{ (isset($_GET['cantidad_registros']) && $_GET['cantidad_registros'] == '50') }} value="50">50</option>
                        <option {{ (isset($_GET['cantidad_registros']) && $_GET['cantidad_registros'] == '100') }} value="100">100</option>
                        <option {{ (isset($_GET['cantidad_registros']) && $_GET['cantidad_registros'] == '200') }} value="200">200</option>
                        <option {{ (isset($_GET['cantidad_registros']) && $_GET['cantidad_registros'] == '500') }} value="500">500</option>
                        <option {{ (isset($_GET['cantidad_registros']) && $_GET['cantidad_registros'] == '1000') }} value="1000">1000</option>
                        <option {{ (isset($_GET['cantidad_registros']) && $_GET['cantidad_registros'] == 'Todos') }} value="Todos">Todos</option>
                    </select>
                </div>
            </div>
        </div>

        <div class="row">
            @if(Auth::user()->departamento == 'TECNOLOGIA' && Auth::user()->departamento == 'GERENCIA')
                <div class="col">
                    <div class="form-group">
                        <label for="sede">Sede</label>
                        <select name="sede" class="form-control">
                            <option value="Todos">Todos</option>
                            @foreach($sedes as $sede)
                                <option {{ (isset($_GET['sede']) && $_GET['sede'] == $sede->razon_social) ? 'selected' : '' }} value="{{ $sede->razon_social }}">{{ $sede->razon_social }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            @endif

            <div class="col">
                <div class="form-group">
                    <label for="fecha_desde">Fecha desde</label>
                    <input value="{{ isset($_GET['fecha_desde']) ? $_GET['fecha_desde'] : '' }}" type="date" class="form-control" name="fecha_desde">
                </div>
            </div>

            <div class="col">
                <div class="form-group">
                    <label for="fecha_hasta">Fecha hasta</label>
                    <input value="{{ isset($_GET['fecha_hasta']) ? $_GET['fecha_hasta'] : '' }}" type="date" class="form-control" name="fecha_hasta">
                </div>
            </div>

            <div class="col">
                <div class="form-group">
                    <label for="">&nbsp;</label>
                    <button class="btn btn-outline-success btn-block" type="submit">Buscar</button>
                </div>
            </div>
        </div>
    </form>

    <hr class="row align-items-start col-12">

    <table style="width:100%;" class="CP-stickyBar">
        <tr>
            @if(Auth::user()->departamento == 'ADMINISTRACION' || Auth::user()->departamento == 'TECNOLOGIA' || Auth::user()->departamento == 'GERENCIA' || Auth::user()->departamento == 'OPERACIONES' || Auth::user()->departamento == 'TESORERIA')
                <td style="width:15%;" align="center">
                    <a href="{{ url('/deudas/create') }}" role="button" class="btn btn-outline-info btn-sm"
                    style="display: inline; text-align: left;">
                    <i class="fa fa-plus"></i>
                        Cargar deuda a proveedor
                    </a>
                </td>
            @endif

            <td style="width:60%;">
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
                <th nowrap scope="col" class="nombre_proveedor CP-sticky">Nombre del proveedor</th>
                <th nowrap scope="col" class="rif_proveedor CP-sticky">RIF/CI del proveedor</th>
                <th nowrap scope="col" class="fecha_registro CP-sticky">Fecha de registro</th>
                <th nowrap scope="col" class="moneda_subtotal CP-sticky">Moneda subtotal</th>
                <th nowrap scope="col" class="monto_subtotal CP-sticky">Monto subtotal (Exento + base)</th>
                <th nowrap scope="col" class="moneda_iva CP-sticky">Moneda IVA</th>
                <th nowrap scope="col" class="monto_iva CP-sticky">Monto IVA</th>
                <th nowrap scope="col" class="dias_credito CP-sticky">Días de crédito</th>
                <th nowrap scope="col" class="documento_soporte_deuda CP-sticky">Documento soporte deuda</th>
                <th nowrap scope="col" class="numero_documento CP-sticky">Numero de documento</th>
                <th nowrap scope="col" class="creado_por CP-sticky">Creado por</th>
                <th nowrap scope="col" class="sede CP-sticky">Sede</th>
                <th nowrap scope="col" class="estado CP-sticky">Estado</th>
                <th nowrap scope="col" class="CP-sticky">Acciones</th>
            </tr>
        </thead>
        <tbody>
        @foreach($deudas as $deuda)
            @php
                $fechaInicio = date_create();
                $fechaInicio = date_modify($fechaInicio, '-30day');
                $fechaInicio = $fechaInicio->format('Y-m-d');

                $fechaFinal = date('Y-m-d');

                $url = '/reportes/movimientos-por-proveedor?id_proveedor=' . $deuda->id_proveedor . '&fechaInicio=' . $fechaInicio . '&fechaFin=' . $fechaFinal;
            @endphp

            <tr class="{{ $deuda->deleted_at ? 'bg-warning' : '' }}">
              <th nowrap>{{$deuda->id}}</th>
              <td nowrap align="center" class="CP-barrido nombre_proveedor">
                <a href="{{ $url }}" style="text-decoration: none; color: black;" target="_blank">{{ ($deuda->proveedor) ? $deuda->proveedor->nombre_proveedor : '' }}</a>
              </td>
              <td nowrap class="rif_proveedor">{{ ($deuda->proveedor) ? $deuda->proveedor->rif_ci : '' }}</td>
              <td nowrap class="fecha_registro">{{$deuda->created_at}}</td>
              <td nowrap class="moneda_subtotal">{{($deuda->proveedor) ? $deuda->proveedor->moneda : ''}}</td>
              <td nowrap class="monto_subtotal">{{number_format($deuda->monto, 2, ',', '.')}}</td>
              <td nowrap class="moneda_iva">{{($deuda->proveedor) ? $deuda->proveedor->moneda_iva : ''}}</td>
              <td nowrap class="monto_iva">{{number_format($deuda->monto_iva, 2, ',', '.')}}</td>
              <td nowrap class="dias_credito">{{$deuda->dias_credito}}</td>
              <td nowrap class="documento_soporte_deuda">{{$deuda->documento_soporte_deuda}}</td>
              <td nowrap class="numero_documento">{{$deuda->numero_documento}}</td>
              <td nowrap class="usuario_registro">{{$deuda->usuario_registro}}</td>
              <td nowrap class="sede">{{$deuda->sede}}</td>
              <td nowrap class="estado">{{($deuda->deleted_at)?'Desincorporado':'Activo'}}</td>
              <td nowrap>
                <a href="/deudas/{{$deuda->id}}" role="button" class="btn btn-outline-success btn-sm" data-toggle="tooltip" data-placement="top" title="Detalle">
                    <i class="far fa-eye"></i>
                </a>

                @if(Auth::user()->departamento == 'TECNOLOGIA' || Auth::user()->departamento == 'GERENCIA')
                    <a href="/deudas/{{$deuda->id}}/edit" role="button" class="btn btn-outline-info btn-sm" data-toggle="tooltip" data-placement="top" title="Modificar">
                        <i class="fas fa-edit"></i>
                    </a>

                    @if(!$deuda->deleted_at)
                        <form action="/deudas/{{$deuda->id}}" method="POST" style="display: inline;">
                            @method('DELETE')
                            @csrf
                            <button type="submit" name="Eliminar" role="button" class="btn btn-outline-danger btn-sm" data-toggle="tooltip" data-placement="top" title="Desincorporar"><i class="fa fa-reply"></i></button>
                        </form>
                    @endif
                @endif

              </td>
            </tr>
        @endforeach
        </tbody>
    </table>

    {{ $deudas->appends($_GET)->links() }}

    <script>
        $(document).ready(function(){
            $('[data-toggle="tooltip"]').tooltip();
        });
        $('#exampleModalCenter').modal('show')
    </script>

@endsection
