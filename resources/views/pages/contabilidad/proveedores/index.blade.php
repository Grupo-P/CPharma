@extends('layouts.contabilidad')

@section('title')
    Proveedores
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

    <table class="table table-striped table-borderless col-12 sortable" id="myTable">
        <thead class="thead-dark">
            <tr>
                <th scope="col" class="CP-sticky">#</th>
                <th scope="col" class="CP-sticky">Nombre</th>
                <th scope="col" class="CP-sticky">Representante</th>
                <th scope="col" class="CP-sticky">RIF/Cédula</th>
                <th scope="col" class="CP-sticky">Dirección</th>
                <th scope="col" class="CP-sticky">Tasa</th>
                <th scope="col" class="CP-sticky">Plan de cuentas</th>
                <th scope="col" class="CP-sticky">Moneda</th>
                <th scope="col" class="CP-sticky">Saldo</th>
                <th scope="col" class="CP-sticky">Creado por</th>
                <th scope="col" class="CP-sticky">Estado</th>
                <th scope="col" class="CP-sticky">Acciones</th>
            </tr>
        </thead>
        <tbody>
        @foreach($proveedores as $proveedor)
            <tr>
              <th>{{$proveedor->id}}</th>
              <td>{{$proveedor->nombre_proveedor}}</td>
              <td>{{$proveedor->nombre_representante}}</td>
              <td>{{$proveedor->rif_ci}}</td>
              <td>{{$proveedor->direccion}}</td>
              <td>{{$proveedor->tasa}}</td>
              <td>{{$proveedor->plan_cuentas}}</td>
              <td>{{$proveedor->moneda}}</td>
              <td>{{number_format($proveedor->saldo, 2, ',', '.')}}</td>
              <td>{{$proveedor->usuario_creado}}</td>
              <td>{{($proveedor->deleted_at)?'Inactivo':'Activo'}}</td>
              <td style="width:140px;">
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
