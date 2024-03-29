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

  <table style="width:100%;" class="CP-stickyBar">
    <tr>
        <td>
            <form action="">
                <div class="row ml-2 mb-4">
                    <div class="col-2">
                        <a href="{{ url('/candidatos/create') }}" role="button" class="btn btn-outline-info">
                            <i class="fa fa-plus"></i>&nbsp;Agregar
                        </a>
                    </div>

                    <div class="col-4">
                        <select required name="metodo" class="form-control">
                            <option value="">Seleccione un método de búsqueda...</option>
                            <option {{ ($request->get('metodo') == 'nombres') ? 'selected' : '' }} value="nombres">Nombres</option>
                            <option {{ ($request->get('metodo') == 'apellidos') ? 'selected' : '' }} value="apellidos">Apellidos</option>
                            <option {{ ($request->get('metodo') == 'cedula') ? 'selected' : '' }} value="cedula">Cédula</option>
                            <option {{ ($request->get('metodo') == 'genero') ? 'selected' : '' }} value="genero">Género</option>
                            <option {{ ($request->get('metodo') == 'telefono_celular') ? 'selected' : '' }} value="telefono_celular">Teléfono</option>
                            <option {{ ($request->get('metodo') == 'tipo_relacion') ? 'selected' : '' }} value="tipo_relacion">Relación laboral</option>
                            <option {{ ($request->get('metodo') == 'estatus') ? 'selected' : '' }} value="estatus">Estatus</option>
                        </select>
                    </div>

                    <div class="col-4">
                        <input type="text" required placeholder="Valor a buscar..." name="valor" class="form-control" value="{{ $request->get('valor') }}">
                    </div>

                    <div class="col-2">
                        <button class="btn btn-block btn-outline-info">
                            <i class="fa fa-search"></i> Buscar
                        </button>
                    </div>
                </div>
            </form>
        </td>
    </tr>
  </table>

  <br/>

  <table class="table table-striped table-borderless col-12 sortable" id="myTable">
    <thead class="thead-dark">
      <tr>
        <th scope="col" class="CP-sticky">#</th>
        <th scope="col" class="CP-sticky">Nombres</th>
        <th scope="col" class="CP-sticky">Apellidos</th>
        <th scope="col" class="CP-sticky">Cédula</th>
        <th scope="col" class="CP-sticky">Género</th>
        <th scope="col" class="CP-sticky">Teléfono</th>
        <th scope="col" class="CP-sticky">Relación laboral</th>
        <th scope="col" class="CP-sticky">Estatus</th>
        <th scope="col" class="CP-sticky">Fase actual</th>
        <th scope="col" class="CP-sticky">Acciones</th>
        <th scope="col" class="CP-sticky">Expediente</th>
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
          <td>{{$candidato->estatus}}</td>

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
                && ($candidato->estatus != 'ELEGIBLE')
                && ($candidato->estatus != 'DESERTOR')
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
                || ($candidato->estatus == 'ELEGIBLE')
                || ($candidato->estatus == 'DESERTOR')
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
              && ($candidato->estatus != 'ELEGIBLE')
              && ($candidato->estatus != 'DESERTOR')
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
                || ($candidato->estatus == 'ELEGIBLE')
                || ($candidato->estatus == 'DESERTOR')
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

  <div class="d-flex justify-content-center">
      {{ $candidatos->appends($_GET)->links() }}
  </div>

  <script>
    $(document).ready(function() {
      $('[data-toggle="tooltip"]').tooltip();
    });
    $('#exampleModalCenter').modal('show');
  </script>
@endsection
