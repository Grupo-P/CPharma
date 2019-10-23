@extends('layouts.model')

@section('title')
  Candidatos
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
            <h4 class="h6">Candidato almacenado con exito</h4>
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
            <h4 class="h6">Candidato modificado con exito</h4>
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
            <h4 class="h6">Candidato actualizado con exito</h4>
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
            <th scope="col" class="stickyCP">Teléfono</th>
            <th scope="col" class="stickyCP">Estatus</th>
            <th scope="col" class="stickyCP">Acciones</th>
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
            } 
          ?>

          <td>{{$candidato->estatus}}</td>

          <!-- ***************** VALIDACION DE ROLES ***************** -->
          <td style="width:140px;">
          <?php
            if(Auth::user()->role == 'MASTER' || Auth::user()->role == 'DEVELOPER') {

              if($candidato->estatus != 'RECHAZADO') {
          ?>
            <a href="/candidatos/{{$candidato->id}}" role="button" class="btn btn-outline-success btn-sm" data-toggle="tooltip" data-placement="top" title="Detalle">
              <i class="far fa-eye"></i>
            </a>

            <a href="/candidatos/{{$candidato->id}}/edit" role="button" class="btn btn-outline-info btn-sm" data-toggle="tooltip" data-placement="top" title="Modificar">
              <i class="fas fa-edit"></i>
            </a>

            <form action="/candidatos/{{$candidato->id}}" method="POST" style="display: inline;">
              @method('DELETE')
              @csrf
              <button type="submit" name="Eliminar" role="button" class="btn btn-outline-danger btn-sm" data-toggle="tooltip" data-placement="top" title="Desincorporar">
                <i class="fa fa-reply"></i>
              </button>
            </form>

          <?php
            } else if($candidato->estatus == 'RECHAZADO') {
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
          } else if(Auth::user()->role == 'ANALISTA') {
          ?>
            <a href="/candidatos/{{$candidato->id}}" role="button" class="btn btn-outline-success btn-sm" data-toggle="tooltip" data-placement="top" title="Detalle">
              <i class="far fa-eye"></i>
            </a>

            <a href="/candidatos/{{$candidato->id}}/edit" role="button" class="btn btn-outline-info btn-sm" data-toggle="tooltip" data-placement="top" title="Modificar">
              <i class="fas fa-edit"></i>
            </a>
          <?php
            } else if(Auth::user()->role == 'USUARIO') {
          ?>
            <a href="/candidatos/{{$candidato->id}}" role="button" class="btn btn-outline-success btn-sm" data-toggle="tooltip" data-placement="top" title="Detalle">
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