@extends('layouts.contabilidad')

@section('title')
    Plan de cuentas
@endsection

@section('content')
    <style>
        .float-right {
            display: none;
        }
    </style>

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
                <h4 class="h6">Plan de cuenta almacenado con exito</h4>
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
                <h4 class="h6">Plan de cuenta modificado con exito</h4>
              </div>
              <div class="modal-footer">
                <button type="button" class="btn btn-outline-success" data-dismiss="modal">Aceptar</button>
              </div>
            </div>
          </div>
        </div>
    @endif

    <!-- Modal Eliminar -->
    @if (session('Deleted'))
        <div class="modal fade" id="exampleModalCenter" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
          <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
              <div class="modal-header">
                <h5 class="modal-title text-info" id="exampleModalCenterTitle"><i class="fas fa-info text-info"></i>{{ session('Deleted') }}</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                </button>
              </div>
              <div class="modal-body">
                <h4 class="h6">Plan de cuenta eliminado con exito</h4>
              </div>
              <div class="modal-footer">
                <button type="button" class="btn btn-outline-success" data-dismiss="modal">Aceptar</button>
              </div>
            </div>
          </div>
        </div>
    @endif

    <h1 class="h5 text-info">
        <i class="fas fa-network-wired"></i>
        Plan de cuentas
    </h1>

    <hr class="row align-items-start col-12">
    <table style="width:100%;" class="CP-stickyBar">
        <tr>
            <td style="width:10%;" align="center">
                <a href="{{ url('/cuentas/create') }}" role="button" class="btn btn-outline-info btn-sm"
                style="display: inline; text-align: left;">
                <i class="fa fa-plus"></i>
                    Agregar
                </a>
            </td>
            <td style="width:90%;">
            </td>
        </tr>
    </table>

    <br/>

    <div class="row">
        <div class="col-2"></div>

        <table class="table table-striped table-bordered sortable col-8" id="myTable">
            @foreach($cuentas as $cuenta)
                @php $variable = $loop->depth @endphp
                <thead class="thead-dark">
                    <tr>
                        <td class="bg-dark text-white text-center" style="vertical-align: middle; width: 120px">
                            {{ $cuenta->nombre }} <br>

                            <a href="/cuentas/{{$cuenta->id}}" style="margin-left: 10px" role="button" class="text-success" data-toggle="tooltip" data-placement="top" title="Detalle">
                                <i class="far fa-eye"></i>
                            </a>

                            <a href="/cuentas/{{$cuenta->id}}/edit" role="button" class="text-info" data-toggle="tooltip" data-placement="top" title="Modificar">
                                <i class="fas fa-edit"></i>
                            </a>

                            @if(Auth::user()->departamento == 'TECNOLOGIA' || Auth::user()->departamento == 'GERENCIA')
                                <a href="/cuentas/{{$cuenta->id}}/delete" role="button" class="text-danger" data-toggle="tooltip" data-placement="top" title="Desincorporar">
                                    <i class="fa fa-reply"></i>
                                </a>
                            @endif
                        </td>

                        @php $hijos = compras\ContCuenta::where('pertenece_a', $cuenta->id)->get() @endphp

                        <td style="background-color: #cecece">
                            @foreach($hijos as $hijo)
                                @include('pages.contabilidad.cuentas.recursivo')
                            @endforeach
                        </td>
                    </tr>
                </thead>
            @endforeach
        </table>
    </div>
@endsection

@section('scriptsFoot')
    <script>
        $(document).ready(function(){
            $('[data-toggle="tooltip"]').tooltip();

            $('.list-group-item').on('hover', function () {
                $(this).find('.float-right').show();
            })
        });
        $('#exampleModalCenter').modal('show')
    </script>
@endsection
