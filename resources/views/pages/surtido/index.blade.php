@extends('layouts.model')

@section('title')
    Surtido de gavetas
@endsection

<?php
    include(app_path().'\functions\config.php');
  include(app_path().'\functions\functions.php');
  include(app_path().'\functions\querys_mysql.php');
  include(app_path().'\functions\querys_sqlserver.php');
?>

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
                <h4 class="h6">Surtido almacenado con exito</h4>
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
                <h4 class="h6">Surtido modificado con exito</h4>
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
                <h4 class="h6">Surtido actualizado con exito</h4>
              </div>
              <div class="modal-footer">
                <button type="button" class="btn btn-outline-success" data-dismiss="modal">Aceptar</button>
              </div>
            </div>
          </div>
        </div>
    @endif

    <h1 class="h5 text-info">
        <i class="fas fa-people-carry"></i>
        Surtido de gavetas
    </h1>

    <hr class="row align-items-start col-12">
    <br/>
    <table style="width:100%;" class="CP-stickyBar">
        <tr>
            <td style="width:10%;" align="center">
                <a href="{{ url('/surtido/create') }}" role="button" class="btn btn-outline-info btn-sm"
                style="display: inline; text-align: left;">
                <i class="fas fa-plus"></i>
                    Agregar
                </a>
            </td>
            <td style="width:90%;">
                <div class="input-group md-form form-sm form-1 pl-0">
                  <div class="input-group-prepend">
                    <span class="input-group-text purple lighten-3" id="basic-text1"><i class="fas fa-search text-white"
                        aria-hidden="true"></i></span>
                  </div>
                  <input class="form-control my-0 py-1" type="text" placeholder="Buscar..." aria-label="Search" id="myInput" onkeyup="FilterAllTable()" autofocus="autofocus">
                </div>
            </td>
        </tr>
    </table>
    <br/>
    <table class="table table-striped table-borderless col-12 sortable">
    <thead class="thead-dark">
        <tr>
            <th scope="col" colspan="5" style="text-align: center;">CLASIFICACION</th>
        </tr>
        </thead>
        <tbody>
            <tr>
                <td style="width:30%;" align="center">
                    <a href="/surtido?estatus=TODO" class="btn btn-outline-info btn-sm">TODO</a>
                </td>

                <td style="width:30%;" align="center">
                    <a href="/surtido?estatus=PROCESADO" class="btn btn-outline-success btn-sm">PROCESADO</a>
                </td>

                <td style="width:30%;" align="center">
                    <a href="/surtido?estatus=ANULADO" class="btn btn-outline-danger btn-sm">ANULADO</a>
                </td>
            </tr>
        </tbody>
    </table>
    <br/>
    <table class="table table-striped table-borderless col-12 sortable" id="myTable">
        <thead class="thead-dark">
            <tr>
                <th scope="col" class="CP-sticky">#</th>
                <th scope="col" class="CP-sticky">Control</th>
                <th scope="col" class="CP-sticky">Fecha</th>
                <th scope="col" class="CP-sticky">Estatus</th>
                <th scope="col" class="CP-sticky">Operador</th>
                <th scope="col" class="CP-sticky">SKU</th>
                <th scope="col" class="CP-sticky">Unidades</th>
                <th scope="col" class="CP-sticky">Primer artículo</th>
                <th scope="col" class="CP-sticky">Último artículo</th>
                <th scope="col" class="CP-sticky">Acciones</th>
            </tr>
        </thead>
        <tbody>
            @foreach($surtidos as $surtido)
                <tr>
                    <td class="text-center">{{ $surtido->id }}</td>
                    <td class="text-center">{{ $surtido->control }}</td>
                    <td class="text-center">{{ date_create($surtido->fecha_generado)->format('d/m/Y h:i A') }}</td>
                    <td class="text-center">{{ $surtido->estatus }}</td>
                    <td class="text-center">{{ $surtido->operador_generado }}</td>
                    <td class="text-center">{{ $surtido->sku }}</td>
                    <td class="text-center">{{ $surtido->unidades }}</td>
                    <td class="text-center">{{ $surtido->primero }}</td>
                    <td class="text-center">{{ $surtido->ultimo }}</td>
                    <td class="text-center">
                        @if($surtido->estatus == 'GENERADO')
                            <a href="{{ '/surtido/' . $surtido->id }}" target="_blank" class="btn btn-outline-dark btn-sm" data-toggle="tooltip" data-placement="top"data-original-title="Ver soporte">
                                <i class="fa fa-eye"></i>
                            </a>
                        @endif

                        @if($surtido->estatus == 'EN ESPERA')
                            <a href="{{ '/surtido/' . $surtido->id . '/edit' }}" target="_blank" class="btn btn-outline-info btn-sm" data-toggle="tooltip" data-placement="top"data-original-title="Continuar procesando">
                                <i class="fa fa-edit"></i>
                            </a>
                        @endif

                        <a href="{{ '/surtido/' . $surtido->id . '/anular' }}" class="btn btn-outline-danger btn-sm" data-toggle="tooltip" data-placement="top"data-original-title="Anular">
                            <i class="fa fa-ban"></i>
                        </a>
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
