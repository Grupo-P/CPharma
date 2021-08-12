@extends('layouts.contabilidad')

@section('title', 'Diferidos en dolares FLL')

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

    Diferido en dolares FLL
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
        <th scope="col" class="CP-sticky"># de registro</th>
        <th scope="col" class="CP-sticky">Proveedor</th>
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
        <th>{{ str_pad($diferido->id, 5, 0, STR_PAD_LEFT) }}</th>
        <td>{{ ($diferido->proveedor) ? $diferido->proveedor->nombre_proveedor : '' }}</td>
        <td>
          <span class="d-inline-block " style="max-width: 250px;">
            {!! $diferido->concepto !!}
          </span>
        </td>
        <td>{{number_format($diferido->diferido, 2, ',', '.')}}</td>
        <td>{{date("d-m-Y h:i:s a", strtotime($diferido->updated_at))}}</td>
        <td>{{$diferido->user_up}}</td>
        <td>{{$diferido->estatus}}</td>
        <td>
          @if((auth()->user()->departamento == 'TESORERIA' || auth()->user()->departamento == 'TECNOLOGIA' || auth()->user()->departamento == 'GERENCIA') && ($diferido->estatus == 'DIFERIDO'))
            <a href="" data-id="{{ $diferido->id }}" data-monto="{{ $diferido->diferido }}" data-concepto="{{ $diferido->concepto }}" data-movimiento="Egreso" class="btn btn-sm btn-outline-success">
                <span class="fa fa-check"></span>
                Confirmar
            </a>

            <a href="" data-id="{{ $diferido->id }}" data-monto="{{ $diferido->diferido }}" data-concepto="{{ $diferido->concepto }}" data-movimiento="Ingreso" class="btn btn-sm btn-outline-danger">
                <span class="fa fa-ban"></span>
                Reversar
            </a>
          @else
            -
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

    $('[data-movimiento]').click(function (event) {
        event.preventDefault();

        id = $(this).attr('data-id');
        movimiento = $(this).attr('data-movimiento');
        concepto = $(this).attr('data-concepto');
        monto = $(this).attr('data-monto');

        concepto = prompt('Concepto');

        if (concepto === null) {
            return;
        }

        if (concepto != '') {
            $.ajax({
                method: 'POST',
                url: '/efectivoFLL/' + id,
                data: {
                    _token: '{{ csrf_token() }}',
                    _method: 'PUT',
                    movimiento: movimiento,
                    monto: monto,
                    concepto: concepto
                },
                success: function (response) {
                    console.log(response);
                    window.location.href = '/contabilidad/diferidosFLL';
                },
                error: function (error) {
                    $('body').html(error.responseText);
                }
            });
        }

    });
  </script>
@endsection
