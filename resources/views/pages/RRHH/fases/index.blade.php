@extends('layouts.model')

@section('title', 'Fases')

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
            <h4 class="h6">Fase almacenada con éxito</h4>
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
            <h4 class="h6">Fase modificada con éxito</h4>
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
            <h4 class="h6">Fase desincorporada con éxito</h4>
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
            <h4 class="h6">Fase reincorporada con éxito</h4>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-outline-success" data-dismiss="modal">Aceptar</button>
          </div>
        </div>
      </div>
    </div>
  @endif

  <h1 class="h5 text-info">
    <i class="fas fa-sort-amount-up-alt"></i>&nbsp;Fases
  </h1>
  <hr class="row align-items-start col-12">

  <table style="width:100%;">
    <tr>
      <td style="width:10%;" align="center">
        <a href="{{ url('/fases/create') }}" role="button" class="btn btn-outline-info btn-sm" style="display: inline; text-align: left;">
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
        <th scope="col" class="stickyCP">Nombre de la fase</th>
        <th scope="col" class="stickyCP">Creada por</th>
        <th scope="col" class="stickyCP">Estatus</th>
        <th scope="col" class="stickyCP">Acciones</th>
      </tr>
    </thead>

    <tbody>
      @foreach($fases as $fase)
        <tr>
          <th>{{$fase->id}}</th>
          <td>{{$fase->nombre_fase}}</td>
          <td>{{$fase->user}}</td>
          <td>{{$fase->estatus}}</td>

          <!-- ***************** VALIDACION DE ROLES ***************** -->
          <td style="width:140px;">
          <?php
            if(Auth::user()->role == 'MASTER' || Auth::user()->role == 'DEVELOPER') {

              if($fase->estatus == 'ACTIVO') {
          ?>
            <a href="/fases/{{$fase->id}}" role="button" class="btn btn-outline-success btn-sm" data-toggle="tooltip" data-placement="top" title="Detalle">
              <i class="far fa-eye"></i>
            </a>

            <a href="/fases/{{$fase->id}}/edit" role="button" class="btn btn-outline-info btn-sm" data-toggle="tooltip" data-placement="top" title="Modificar">
              <i class="fas fa-edit"></i>
            </a>

            <form action="/fases/{{$fase->id}}" method="POST" style="display: inline;">
              @method('DELETE')
              @csrf
              <button type="submit" name="Eliminar" role="button" class="btn btn-outline-danger btn-sm" data-toggle="tooltip" data-placement="top" title="Desincorporar">
                <i class="fa fa-reply"></i>
              </button>
            </form>

          <?php
            } else if($fase->estatus == 'INACTIVO') {
          ?>
            <form action="/fases/{{$fase->id}}" method="POST" style="display: inline;">
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
            <a href="/fases/{{$fase->id}}" role="button" class="btn btn-outline-success btn-sm" data-toggle="tooltip" data-placement="top" title="Detalle">
              <i class="far fa-eye"></i>
            </a>

            <a href="/fases/{{$fase->id}}/edit" role="button" class="btn btn-outline-info btn-sm" data-toggle="tooltip" data-placement="top" title="Modificar">
              <i class="fas fa-edit"></i>
            </a>
          <?php
            } else if(Auth::user()->role == 'USUARIO') {
          ?>
            <a href="/fases/{{$fase->id}}" role="button" class="btn btn-outline-success btn-sm" data-toggle="tooltip" data-placement="top" title="Detalle">
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