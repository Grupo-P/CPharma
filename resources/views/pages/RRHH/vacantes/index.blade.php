@extends('layouts.model')

@section('title')
  Vacantes
@endsection

@section('scriptsHead')
  <style>
    th, td {text-align: center;}
  </style>
@endsection

@section('content')
  <!-- Modal Guardar -->
  @if(session('Saved'))
    <div class="modal fade" id="exampleModalCenter" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
      <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title text-info" id="exampleModalCenterTitle">
              <i class="fas fa-info text-info"></i>{{ session('Saved') }}
            </h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">
            <h4 class="h6">Vacante almacenada con éxito</h4>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-outline-success" data-dismiss="modal">Aceptar</button>
          </div>
        </div>
      </div>
    </div>
  @endif

  <!-- Modal Editar -->
  @if(session('Updated'))
    <div class="modal fade" id="exampleModalCenter" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
      <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title text-info" id="exampleModalCenterTitle">
              <i class="fas fa-info text-info"></i>{{ session('Updated') }}
            </h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">
            <h4 class="h6">Vacante modificada con éxito</h4>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-outline-success" data-dismiss="modal">Aceptar</button>
          </div>
        </div>
      </div>
    </div>
  @endif

  <!-- Modal Eliminar -->
  @if(session('Deleted'))
    <div class="modal fade" id="exampleModalCenter" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
      <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title text-info" id="exampleModalCenterTitle">
              <i class="fas fa-info text-info"></i>{{ session('Deleted') }}
            </h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">
            <h4 class="h6">Vacante desincorporada con éxito</h4>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-outline-success" data-dismiss="modal">Aceptar</button>
          </div>
        </div>
      </div>
    </div>
  @endif

  @if(session('Deleted1'))
    <div class="modal fade" id="exampleModalCenter" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
      <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title text-info" id="exampleModalCenterTitle">
              <i class="fas fa-info text-info"></i>{{ session('Deleted1') }}
            </h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">
            <h4 class="h6">Vacante reincorporada con éxito</h4>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-outline-success" data-dismiss="modal">Aceptar</button>
          </div>
        </div>
      </div>
    </div>
  @endif

  <h1 class="h5 text-info">
    <i class="fas fa-user-plus"></i>&nbsp;Vacantes
  </h1>

  <hr class="row align-items-start col-12">

  <table style="width:100%;" class="CP-stickyBar">
    <tr>
      <td style="width:10%;" align="center">
        <a href="{{ url('/vacantes/create') }}" role="button" class="btn btn-outline-info btn-sm" style="display: inline; text-align: left;">
          <i class="fa fa-plus"></i>&nbsp;Agregar
        </a>
      </td>

      <td style="width:90%;">
        <div class="input-group md-form form-sm form-1 pl-0">
          <div class="input-group-prepend">
            <span class="input-group-text purple lighten-3" id="basic-text1">
              <i class="fas fa-search text-white" aria-hidden="true"></i>
            </span>
          </div>
          <input class="form-control my-0 py-1" type="text" placeholder="Buscar..." aria-label="Search" id="myInput" onkeyup="FilterAllTable()">
        </div>
      </td>
    </tr>
  </table>

  <br/>

  <table class="table table-striped table-borderless col-12 sortable" id="myTable">
    <thead class="thead-dark">
      <tr>
        <th scope="col" class="CP-sticky">#</th>
        <th scope="col" class="CP-sticky">Sede</th>
        <th scope="col" class="CP-sticky">Nombre de la vacante</th>
        <th scope="col" class="CP-sticky">Departamento</th>
        <th scope="col" class="CP-sticky">Turno</th>
        <th scope="col" class="CP-sticky">Dias libres</th>
        <th scope="col" class="CP-sticky">Nivel de urgencia</th>
        <th scope="col" class="CP-sticky">Solicitante</th>
        <th scope="col" class="CP-sticky">Cantidad requerida</th>
        <th scope="col" class="CP-sticky">Cantidad en tránsito</th>
        <th scope="col" class="CP-sticky">Fecha inicio solicitud</th>
        <th scope="col" class="CP-sticky">Fecha tope solicitud</th>
        <th scope="col" class="CP-sticky">Estatus</th>
        <th scope="col" class="CP-sticky">Acciones</th>
      </tr>
    </thead>

    <tbody>
      @foreach($vacantes as $vacante)
        <tr>
          <th>{{$vacante->id}}</th>
          <td>{{$vacante->sede}}</td>
          <td>{{$vacante->nombre_vacante}}</td>
          <td>{{$vacante->departamento}}</td>
          <td>{{$vacante->turno}}</td>
          <td>{{$vacante->dias_libres}}</td>
          <td>{{$vacante->nivel_urgencia}}</td>
          <td>{{$vacante->solicitante}}</td>
          <td>{{$vacante->cantidad}}</td>
          <td>
            {{
              compras\RH_Entrevista::join(
                'rh_candidatos', 
                'rh_entrevistas.rh_candidatos_id', '=', 'rh_candidatos.id'
              )
              ->where('rh_vacantes_id', $vacante->id)
              ->where('rh_candidatos.estatus', '<>', 'RECHAZADO')
              ->where('rh_candidatos.estatus', '<>', 'ELEGIBLE')
              ->where('rh_candidatos.estatus', '<>', 'DESERTOR')
              ->where('rh_candidatos.estatus', '<>', 'CONTRATADO')
              ->where('rh_entrevistas.estatus', '=', 'ACTIVO')
              ->count()
            }}
          </td>
          <td>{{date('d-m-Y', strtotime($vacante->fecha_solicitud))}}</td>
          <td>{{date('d-m-Y', strtotime($vacante->fecha_limite))}}</td>
          <td>{{$vacante->estatus}}</td>

          <!-- ***************** VALIDACION DE ROLES ***************** -->
          <td style="width:140px;">
          <?php
            if(Auth::user()->role == 'MASTER' || Auth::user()->role == 'DEVELOPER') {

              if($vacante->estatus == 'ACTIVO') {
          ?>
            <a href="/vacantes/{{$vacante->id}}" role="button" class="btn btn-outline-success btn-sm" data-toggle="tooltip" data-placement="top" title="Detalle">
              <i class="far fa-eye"></i>
            </a>

            <a href="/vacantes/{{$vacante->id}}/edit" role="button" class="btn btn-outline-info btn-sm" data-toggle="tooltip" data-placement="top" title="Modificar">
              <i class="fas fa-edit"></i>
            </a>

            <form action="/vacantes/{{$vacante->id}}" method="POST" style="display: inline;">
              @method('DELETE')
              @csrf
              <button type="submit" name="Eliminar" role="button" class="btn btn-outline-danger btn-sm" data-toggle="tooltip" data-placement="top" title="Desincorporar">
                <i class="fa fa-reply"></i>
              </button>
            </form>

          <?php
            } else if($vacante->estatus == 'INACTIVO') {
          ?>
            <form action="/vacantes/{{$vacante->id}}" method="POST" style="display: inline;">
              @method('DELETE')
              @csrf
              <button type="submit" name="Eliminar" role="button" class="btn btn-outline-danger btn-sm" data-toggle="tooltip" data-placement="top" title="Reincorporar">
                <i class="fa fa-share"></i>
              </button>
            </form>
          <?php
            }
          } else if(Auth::user()->role == 'ANALISTA') {
            if($vacante->estatus == 'ACTIVO') {
          ?>
            <a href="/vacantes/{{$vacante->id}}" role="button" class="btn btn-outline-success btn-sm" data-toggle="tooltip" data-placement="top" title="Detalle">
              <i class="far fa-eye"></i>
            </a>

            <a href="/vacantes/{{$vacante->id}}/edit" role="button" class="btn btn-outline-info btn-sm" data-toggle="tooltip" data-placement="top" title="Modificar">
              <i class="fas fa-edit"></i>
            </a>

            <form action="/vacantes/{{$vacante->id}}" method="POST" style="display: inline;">
              @method('DELETE')
              @csrf
              <button type="submit" name="Eliminar" role="button" class="btn btn-outline-danger btn-sm" data-toggle="tooltip" data-placement="top" title="Desincorporar">
                <i class="fa fa-reply"></i>
              </button>
            </form>

          <?php
            } else if($vacante->estatus == 'INACTIVO') {
          ?>
            <form action="/vacantes/{{$vacante->id}}" method="POST" style="display: inline;">
              @method('DELETE')
              @csrf
              <button type="submit" name="Eliminar" role="button" class="btn btn-outline-danger btn-sm" data-toggle="tooltip" data-placement="top" title="Reincorporar">
                <i class="fa fa-share"></i>
              </button>
            </form>
          <?php
            }
          } else if(Auth::user()->role == 'USUARIO') {
          ?>
            <a href="/vacantes/{{$vacante->id}}" role="button" class="btn btn-outline-success btn-sm" data-toggle="tooltip" data-placement="top" title="Detalle">
              <i class="far fa-eye"></i>
            </a>
          <?php
            }
          ?>
          </td>
        </tr>
      @endforeach
    </tbody>
  </table>

  <script>
    $(document).ready(function() {
      $('[data-toggle="tooltip"]').tooltip();
    });
    $('#exampleModalCenter').modal('show');
  </script>
@endsection