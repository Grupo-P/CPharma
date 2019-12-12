@extends('layouts.model')

@section('title', 'Fases y procesos')

@section('scriptsHead')
  <style>
    th, td {text-align: center;}
  </style>
@endsection

@section('content')
  <!-- Modal Guardar -->
  @if(session('Saved0'))
    <div class="modal fade" id="exampleModalCenter" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
      <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title text-info" id="exampleModalCenterTitle">
              <i class="fas fa-info text-info"></i>{{ session('Saved0') }}
            </h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">
            <h4 class="h6">Inicio del proceso exitosamente</h4>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-outline-success" data-dismiss="modal">Aceptar</button>
          </div>
        </div>
      </div>
    </div>
  @endif

  @if(session('Saved1'))
    <div class="modal fade" id="exampleModalCenter" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
      <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title text-info" id="exampleModalCenterTitle">
              <i class="fas fa-info text-info"></i>{{ session('Saved1') }}
            </h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">
            <h4 class="h6">Fase #1 agregada con éxito</h4>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-outline-success" data-dismiss="modal">Aceptar</button>
          </div>
        </div>
      </div>
    </div>
  @endif

  @if(session('Saved2'))
    <div class="modal fade" id="exampleModalCenter" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
      <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title text-info" id="exampleModalCenterTitle">
              <i class="fas fa-info text-info"></i>{{ session('Saved2') }}
            </h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">
            <h4 class="h6">Fase #2 agregada con éxito</h4>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-outline-success" data-dismiss="modal">Aceptar</button>
          </div>
        </div>
      </div>
    </div>
  @endif

  @if(session('Saved3'))
    <div class="modal fade" id="exampleModalCenter" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
      <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title text-info" id="exampleModalCenterTitle">
              <i class="fas fa-info text-info"></i>{{ session('Saved3') }}
            </h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">
            <h4 class="h6">Fase #3 agregada con éxito</h4>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-outline-success" data-dismiss="modal">Aceptar</button>
          </div>
        </div>
      </div>
    </div>
  @endif

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
             Error al iniciar el proceso
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
    <i class="fas fa-cogs"></i>&nbsp;Fases y procesos
  </h1>
  <hr class="row align-items-start col-12">

  <table style="width:100%;">
    <tr>
      <td>
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
        <th scope="col" class="stickyCP">#</th>
        <th scope="col" class="stickyCP">Nombres</th>
        <th scope="col" class="stickyCP">Apellidos</th>
        <th scope="col" class="stickyCP">Cédula</th>
        <th scope="col" class="stickyCP">Teléfono</th>
        <th scope="col" class="stickyCP">Fase actual</th>
        <th scope="col" class="stickyCP">Próxima Fase</th>
      </tr>
    </thead>

    <tbody>
      @foreach($candidatos as $candidato)
        <tr>
          <th>{{$candidato->id}}</th>
          <td>{{$candidato->nombres}}</td>
          <td>{{$candidato->apellidos}}</td>
          <td>{{$candidato->cedula}}</td>
            
          <?php if($candidato->telefono_celular == '') { ?>
            <td>{{$candidato->telefono_habitacion}}</td>
          <?php 
            } else if($candidato->telefono_habitacion == '') { 
          ?>
            <td>{{$candidato->telefono_celular}}</td>
          <?php 
            } else {
          ?>
            <td>{{$candidato->telefono_celular}}</td>
          <?php
            }

            $candidatos_fases = DB::table('rhi_candidatos_fases')
            ->where('rh_candidatos_id', $candidato->id)
            ->orderBy('id', 'desc')
            ->first();

            $nombre_fase = '-';

            if(!is_null($candidatos_fases)) {
              $nombre_fase = compras\RH_Fase::find($candidatos_fases->rh_fases_id)
              ->nombre_fase;
            }
            
          ?>

          <td>{{$nombre_fase}}</td>

          <td>
          <?php 
          if($candidato->estatus == 'POSTULADO') {
          ?>
            <form action="/gestor_fases" method="POST">
              @csrf
              <input type="hidden" name="CandidatoId" value="{{$candidato->id}}">
              <input type="hidden" name="FaseId" value="1">

              <button type="submit" role="button" class="btn btn-outline-success btn-sm" data-toggle="tooltip" data-placement="bottom" title="Ir a la fase">
                <i class="far fa-play-circle"></i>&nbsp;Iniciar proceso
              </button>
            </form>

          <?php
          } else if(
              ($candidato->estatus == 'RECHAZADO' )
              || ($candidato->estatus == 'FUTURO')
            ) { 
            echo '-';
          }
          else if($candidato->estatus == 'EN_PROCESO') {

            switch($nombre_fase) {
              case 'Pruebas Psicológicas':
          ?>
            <form action="/candidatos_pruebas/create" method="GET">
              <input type="hidden" name="CandidatoId" value="{{$candidato->id}}">
              <input type="hidden" name="CandidatoFaseId" value="{{$candidatos_fases->id}}">

              <button type="submit" role="button" class="btn btn-outline-warning btn-sm" data-toggle="tooltip" data-placement="bottom" title="Ir a la fase">
                <i class="fas fa-tasks"></i>&nbsp;Pruebas
              </button>
            </form>
          <?php
              break;

              case 'Entrevista':
          ?>
            <form action="/entrevistas/create" method="GET" style="display: inline-block;">
              <input type="hidden" name="CandidatoId" value="{{$candidato->id}}">
              <input type="hidden" name="CandidatoFaseId" value="{{$candidatos_fases->id}}">

              <button type="submit" role="button" class="btn btn-outline-primary btn-sm" data-toggle="tooltip" data-placement="bottom" title="Ir a la fase">
                <i class="fas fa-users"></i>&nbsp;Entrevista
              </button>
            </form>

            <form action="/candidatos_pruebas/create" method="GET" style="display: inline-block;">
              <input type="hidden" name="CandidatoId" value="{{$candidato->id}}">
              <input type="hidden" name="CandidatoFaseId" value="{{$candidatos_fases->id}}">

              <button type="submit" role="button" class="btn btn-outline-warning btn-sm" data-toggle="tooltip" data-placement="bottom" title="Ir a la fase">
                <i class="fas fa-tasks"></i>&nbsp;Pruebas
              </button>
            </form>
          <?php
              break;

              case 'Práctica':
          ?>
            <form action="/practicas/create" method="GET" style="display: inline-block;">
              <input type="hidden" name="CandidatoId" value="{{$candidato->id}}">
              <input type="hidden" name="CandidatoFaseId" value="{{$candidatos_fases->id}}">

              <button type="submit" role="button" class="btn btn-outline-danger btn-sm" data-toggle="tooltip" data-placement="bottom" title="Ir a la fase">
                <i class="fas fa-users-cog"></i>&nbsp;Práctica
              </button>
            </form>

            <form action="/entrevistas/create" method="GET" style="display: inline-block;">
              <input type="hidden" name="CandidatoId" value="{{$candidato->id}}">
              <input type="hidden" name="CandidatoFaseId" value="{{$candidatos_fases->id}}">

              <button type="submit" role="button" class="btn btn-outline-primary btn-sm" data-toggle="tooltip" data-placement="bottom" title="Ir a la fase">
                <i class="fas fa-users"></i>&nbsp;Entrevista
              </button>
            </form>
          <?php
              break;

              case 'Referencias laborales':
          ?>
            <form action="#" method="GET" style="display: inline-block;">
              <input type="hidden" name="CandidatoId" value="{{$candidato->id}}">
              <input type="hidden" name="CandidatoFaseId" value="{{$candidatos_fases->id}}">

              <button type="button" role="button" class="btn btn-outline-info btn-sm" data-toggle="tooltip" data-placement="bottom" title="Ir a la fase">
                <i class="far fa-address-card"></i>&nbsp;Empresa ref.
              </button>
            </form>

            <form action="/entrevistas/create" method="GET" style="display: inline-block;">
              <input type="hidden" name="CandidatoId" value="{{$candidato->id}}">
              <input type="hidden" name="CandidatoFaseId" value="{{$candidatos_fases->id}}">

              <button type="submit" role="button" class="btn btn-outline-primary btn-sm" data-toggle="tooltip" data-placement="bottom" title="Ir a la fase">
                <i class="fas fa-users"></i>&nbsp;Entrevista
              </button>
            </form>
          <?php
              break;

              default: echo $nombre_fase;
            }//switch
          }//else
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