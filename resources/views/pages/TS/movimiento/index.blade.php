@extends('layouts.model')

@section('title', 'Movimientos')

@section('scriptsHead')
  <style>
    th, td {text-align: center;}
  </style>
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
            <h4 class="h6">Movimiento almacenado con éxito</h4>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-outline-success" data-dismiss="modal">Aceptar</button>
          </div>
        </div>
      </div>
    </div>
  @endif

  <h1 class="h5 text-info">
    @if(isset($_GET["tasa_ventas_id"])) 
      @if($_GET["tasa_ventas_id"] == 1) 
        <i class="fas fa-balance-scale-left"></i>
        Movimientos en bolívares
      @elseif($_GET["tasa_ventas_id"] == 2)
        <i class="fas fa-balance-scale"></i>
        Movimientos en dolares 
      @endif
    @endif
  </h1>

  <hr class="row align-items-start col-12">
  <table style="width:100%;" class="CP-stickyBar">
    <tr>

      @if(auth()->user()->departamento == 'TESORERIA')
        <td style="width:10%;" align="center">
          <a href="{{ url('/movimientos/create?tasa_ventas_id=' . $_GET["tasa_ventas_id"]) }}" role="button" class="btn btn-outline-info btn-sm" 
          style="display: inline; text-align: left;">
          <i class="fa fa-plus"></i>
            Agregar
          </a>
        </td>
      @endif

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
        <th scope="col" class="CP-sticky">Ingresos</th>
        <th scope="col" class="CP-sticky">Egresos</th>
        <th scope="col" class="CP-sticky">Diferidos</th>
        <th scope="col" class="CP-sticky">Saldo anterior</th>
        <th scope="col" class="CP-sticky">Saldo posterior</th>
        <th scope="col" class="CP-sticky">Fecha y hora</th>
        <th scope="col" class="CP-sticky">Usuario</th>
      </tr>
    </thead>

    @php
      include(app_path().'\functions\functions.php');
      $cont = 0;
    @endphp

    <tbody>
    @foreach($movimientos as $movimiento)
      <tr>
        <th>{{intval(++$cont)}}</th>
        <td>
          <span class="d-inline-block " style="max-width: 250px;">
            {{FG_Limpiar_Texto($movimiento->concepto)}}
          </span>
        </td>
        <td>{{number_format($movimiento->ingresos, 2, ',', '.')}}</td>
        <td>{{number_format($movimiento->egresos, 2, ',', '.')}}</td>
        <td>{{number_format($movimiento->diferido, 2, ',', '.')}}</td>
        <td>{{number_format($movimiento->saldo_anterior, 2, ',', '.')}}</td>
        <td>{{number_format($movimiento->saldo_actual, 2, ',', '.')}}</td>
        <td>{{date("d-m-Y h:i:s a", strtotime($movimiento->created_at))}}</td>
        <td>{{$movimiento->user}}</td>
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