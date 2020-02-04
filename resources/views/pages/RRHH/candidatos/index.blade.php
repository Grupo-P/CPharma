@extends('layouts.model')

@section('title', 'Candidatos')

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
            <h4 class="h6">Candidato almacenado con éxito</h4>
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
            <h4 class="h6">Candidato modificado con éxito</h4>
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
            <h4 class="h6">Candidato desincorporado con éxito</h4>
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
            <h4 class="h6">Candidato reincorporado con éxito</h4>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-outline-success" data-dismiss="modal">Aceptar</button>
          </div>
        </div>
      </div>
    </div>
  @endif

  <h1 class="h5 text-info">
    <i class="fas fa-user-check"></i>&nbsp;Candidatos
  </h1>
  <hr class="row align-items-start col-12">

  <table style="width:100%;">
    <tr>
      <td style="width:10%;" align="center">
        <a href="{{ url('/candidatos/create') }}" role="button" class="btn btn-outline-info btn-sm" style="display: inline; text-align: left;">
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
        <th scope="col" class="stickyCP">#</th>
        <th scope="col" class="stickyCP">Nombres</th>
        <th scope="col" class="stickyCP">Apellidos</th>
        <th scope="col" class="stickyCP">Cédula</th>
        <th scope="col" class="stickyCP">Género</th>
        <th scope="col" class="stickyCP">Teléfono</th>
        <th scope="col" class="stickyCP">Relación laboral</th>
        <th scope="col" class="stickyCP">Estatus</th>
        <th scope="col" class="stickyCP">Fase actual</th>
        <th scope="col" class="stickyCP">Acciones</th>
        <th scope="col" class="stickyCP">Expediente</th>
      </tr>
    </thead>

    <tbody>
      @foreach($candidatos as $candidato)
        <tr>
          <th>{{$candidato->id}}</th>
          <td>{{$candidato->nombres}}</td>
          <td>{{$candidato->apellidos}}</td>
          <td>{{$candidato->cedula}}</td>
          @if(empty($candidato->genero))
          <td>-</td>
          @else
          <td>{{$candidato->genero}}</td>
          @endif
            
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
          ?>

          <td>{{$candidato->tipo_relacion}}</td>

          @if($candidato->estatus == "FUTURO")
            <td>ELEGIBLE</td>
          @else
            <td>{{$candidato->estatus}}</td>
          @endif

          <td>
            <?php
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
            {{$nombre_fase}}
          </td>

          <!-- ***************** VALIDACION DE ROLES ***************** -->
          <td style="width:140px;">
          <?php
            if(Auth::user()->role == 'MASTER' || Auth::user()->role == 'DEVELOPER') {

              if(
                ($candidato->estatus != 'RECHAZADO')
                && ($candidato->estatus != 'FUTURO')
              ) {
          ?>
            <a href="/candidatos/{{$candidato->id}}" role="button" class="btn btn-outline-success btn-sm" data-toggle="tooltip" data-placement="top" title="Detalle">
              <i class="far fa-eye"></i>
            </a>

            <a href="/candidatos/{{$candidato->id}}/edit" role="button" class="btn btn-outline-info btn-sm" data-toggle="tooltip" data-placement="top" title="Modificar">
              <i class="fas fa-edit"></i>
            </a>

            <form action="/motivo_rechazo" method="GET" style="display: inline;">
              
              <input type="hidden" id="CandidatoId" name="CandidatoId" value="{{$candidato->id}}">

              <button type="submit" role="button" class="btn btn-outline-danger btn-sm" data-toggle="tooltip" data-placement="top" title="Desincorporar">
                <i class="fa fa-reply"></i>
              </button>
            </form>

          <?php
            } else if(
                ($candidato->estatus == 'RECHAZADO')
                || ($candidato->estatus == 'FUTURO')
              ) {
          ?>
            <form action="/candidatos/{{$candidato->id}}" method="POST" style="display: inline;">
              @method('DELETE')
              @csrf
              <button type="submit" name="Eliminar" role="button" class="btn btn-outline-danger btn-sm" data-toggle="tooltip" data-placement="top" title="Reincorporar">
                <i class="fa fa-share"></i>
              </button>
            </form>
          <?php
            }
          } 
          else if(Auth::user()->role == 'ANALISTA') {
            if(
              ($candidato->estatus != 'RECHAZADO')
              && ($candidato->estatus != 'FUTURO')
            ) {
          ?>
            <a href="/candidatos/{{$candidato->id}}" role="button" class="btn btn-outline-success btn-sm" data-toggle="tooltip" data-placement="top" title="Detalle">
              <i class="far fa-eye"></i>
            </a>

            <a href="/candidatos/{{$candidato->id}}/edit" role="button" class="btn btn-outline-info btn-sm" data-toggle="tooltip" data-placement="top" title="Modificar">
              <i class="fas fa-edit"></i>
            </a>
          
          @if($candidato->estatus != "POSTULADO")
            <form action="/motivo_rechazo" method="GET" style="display: inline;">
              
              <input type="hidden" id="CandidatoId" name="CandidatoId" value="{{$candidato->id}}">

              <button type="submit" role="button" class="btn btn-outline-danger btn-sm" data-toggle="tooltip" data-placement="top" title="Desincorporar">
                <i class="fa fa-reply"></i>
              </button>
            </form>
          @endif

          <?php
            } else if(
                ($candidato->estatus == 'RECHAZADO')
                || ($candidato->estatus == 'FUTURO')
              ) {          ?>
            <form action="/candidatos/{{$candidato->id}}" method="POST" style="display: inline;">
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
            <a href="/candidatos/{{$candidato->id}}" role="button" class="btn btn-outline-success btn-sm" data-toggle="tooltip" data-placement="top" title="Detalle">
              <i class="far fa-eye"></i>
            </a>
          <?php
            }
          ?>
          </td>

          <td>
            <?php
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

            <form action="/expediente_candidatos" method="GET" style="display: inline-block;">
              <input type="hidden" name="CandidatoId" value="{{$candidato->id}}">

              <button type="submit" role="button" class="btn btn-outline-primary btn-sm" data-toggle="tooltip" data-placement="bottom" title="Ir al expediente">
                <i class="fas fa-search"></i>&nbsp;Detalle
              </button>
            </form>
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