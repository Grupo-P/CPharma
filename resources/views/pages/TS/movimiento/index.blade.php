@extends('layouts.model')

@section('title', 'Movimientos')

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
    <i class="fas fa-balance-scale"></i>
    Movimientos
  </h1>

  <hr class="row align-items-start col-12">
  <table style="width:100%;" class="CP-stickyBar">
    <tr>
      <td style="width:10%;" align="center">
        <a href="{{ url('/movimientos/create') }}" role="button" class="btn btn-outline-info btn-sm" 
        style="display: inline; text-align: left;">
        <i class="fa fa-plus"></i>
          Agregar
        </a>
      </td>

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
  
  <table class="table table-striped table-borderless col-12 sortable" id="myTable">
    <thead class="thead-dark">
      <tr>
        <th scope="col" class="CP-sticky">#</th>
        <th scope="col" class="CP-sticky">Ingresos</th>
        <th scope="col" class="CP-sticky">Egresos</th>
        <th scope="col" class="CP-sticky">Saldo anterior</th>
        <th scope="col" class="CP-sticky">Saldo actual</th>
        <th scope="col" class="CP-sticky">Moneda</th>
        <th scope="col" class="CP-sticky">Fecha</th>
        <th scope="col" class="CP-sticky">Acciones</th>
      </tr>
    </thead>

    <tbody>
    @foreach($movimientos as $movimiento)
      <tr>
        <th>{{$movimiento->id}}</th>
        <td>{{$movimiento->ingresos}}</td>
        <td>{{$movimiento->egresos}}</td>
        <td>{{$movimiento->saldo_anterior}}</td>         
        <td>{{$movimiento->saldo_actual}}</td>
        <td>{{$movimiento->tasa_ventas_id}}</td>
        <td>{{$movimiento->fecha}}</td>
          
        <!-- Inicio Validacion de ROLES -->
        <td style="width:140px;">
        
        <?php
        if(Auth::user()->role == 'MASTER' || Auth::user()->role == 'DEVELOPER'){
        ?>
          <a href="/movimientos/{{$movimiento->id}}" role="button" class="btn btn-outline-success btn-sm" data-toggle="tooltip" data-placement="top" title="Detalle">
            <i class="far fa-eye"></i>
          </a>
        <?php
        } else if(Auth::user()->role == 'USUARIO'){
        ?>
          <a href="/movimientos/{{$movimiento->id}}" role="button" class="btn btn-outline-success btn-sm" data-toggle="tooltip" data-placement="top" title="Detalle">
            <i class="far fa-eye"></i>
          </a>
        <?php
        }
        ?>
                    
        </td>
        <!-- Fin Validacion de ROLES -->
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