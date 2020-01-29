@extends('layouts.model')

@section('title', 'Movimientos')

@section('scriptsHead')
  <style>
    th, td {text-align: center;}
  </style>
@endsection

@section('content')
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
            <h4 class="h6">Movimiento modificado con éxito</h4>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-outline-success" data-dismiss="modal">Aceptar</button>
          </div>
        </div>
      </div>
    </div>
  @endif

  <h1 class="h5 text-info">
    <i class="fas fa-lock"></i>

    @if(isset($_GET["tasa_ventas_id"])) 
      @if($_GET["tasa_ventas_id"] == 1)
        Diferidos en bolívares
      @elseif($_GET["tasa_ventas_id"] == 2)
        Diferidos en dolares 
      @endif
    @endif
  </h1>

  <hr class="row align-items-start col-12">
  <table style="width:100%;" class="CP-stickyBar">
    <tr>
      <td style="width:90%;">
        <div class="input-group md-form form-sm form-1 pl-0 CP-stickyBar">
          <div class="input-group-prepend">
            <span class="input-group-text purple lighten-3" id="basic-text1">
              <i class="fas fa-search text-white" aria-hidden="true"></i>
            </span>
          </div>
          <input class="form-control my-0 py-1" type="text" placeholder="Buscar..." aria-label="Search" id="myInput" onkeyup="FilterAllTable()" autofocus="autofocus">
        </div>
      </td>
    </tr>
  </table>
  <br/>
  
  <table class="table table-striped table-borderless col-12" id="myTable">
    <thead class="thead-dark">
      <tr>
        <th scope="col" class="CP-sticky">#</th>
        <th scope="col" class="CP-sticky">Concepto</th>
        <th scope="col" class="CP-sticky">Diferido</th>
        <th scope="col" class="CP-sticky">Fecha y hora</th>
        <th scope="col" class="CP-sticky">Usuario</th>
        <th scope="col" class="CP-sticky">Estatus</th>
        <th scope="col" class="CP-sticky">Acciones</th>
      </tr>
    </thead>

    @php
      include(app_path().'\functions\functions.php');
      $cont = 0;
    @endphp

    <tbody>
    @foreach($diferidos as $diferido)
      <tr>
        <th>{{intval(++$cont)}}</th>
        <td>
          <span class="d-inline-block " style="max-width: 250px;">
            {{FG_Limpiar_Texto($diferido->concepto)}}
          </span>
        </td>
        <td>{{number_format($diferido->diferido, 2, ',', '.')}}</td>
        <td>{{date("d-m-Y h:i:s a", strtotime($diferido->updated_at))}}</td>
        <td>{{$diferido->user_up}}</td>
        <td>{{$diferido->estatus}}</td>
        <td>
          @if((auth()->user()->departamento == 'TESORERIA') && ($diferido->estatus == 'DIFERIDO'))
            <a href="/movimientos/{{$diferido->id}}/edit" role="button" class="btn btn-outline-info btn-sm" data-toggle="tooltip" data-placement="top" title="Modificar">
              <i class="fas fa-edit"></i>
            </a>
          @else
            {{'-'}}
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
    $('#exampleModalCenter').modal('show');
  </script>
@endsection