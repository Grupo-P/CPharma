@extends('layouts.contabilidad')

@section('title')
    Prepagados
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
                <h4 class="h6">Pago prepagado almacenado con exito</h4>
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
                <h4 class="h6">Pago prepagado modificado con exito</h4>
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
                <h4 class="h6">Pago prepagado cancelado con exito</h4>
              </div>
              <div class="modal-footer">
                <button type="button" class="btn btn-outline-success" data-dismiss="modal">Aceptar</button>
              </div>
            </div>
          </div>
        </div>
    @endif

    <h1 class="h5 text-info">
        <i class="fas fa-info-circle"></i>
        Prepagados
    </h1>

    <hr class="row align-items-start col-12">
    <table style="width:100%;" class="CP-stickyBar">
        <tr>
            @if(Auth::user()->departamento == 'TECNOLOGIA' || Auth::user()->departamento == 'GERENCIA' || Auth::user()->departamento == 'ADMINISTRACION')
                <td style="width:15%;" align="center">
                    <a href="{{ url('/prepagados/create') }}" role="button" class="btn btn-outline-info btn-sm"
                    style="display: inline; text-align: left;">
                    <i class="fa fa-plus"></i>
                        Cargar pago prepagado
                    </a>
                </td>
            @endif

            <td style="width:60%;">
                <div class="input-group md-form form-sm form-1 pl-0 CP-stickyBar">
                    <div class="input-group-prepend">
                        <span class="input-group-text purple lighten-3" id="basic-text1"><i class="fas fa-search text-white"
                    aria-hidden="true"></i></span>
                    </div>
                <input class="form-control my-0 py-1 CP-stickyBar" type="text" placeholder="Buscar..." aria-label="Search" id="myInput" onkeyup="FilterAllTable()" autofocus="autofocus">
                </div>
            </td>
        </tr>
    </table>

    <br/>

    <table class="table table-striped table-borderless col-12 sortable" id="myTable">
        <thead class="thead-dark">
            <tr>
                <th scope="col" class="CP-sticky" nowrap>#</th>
                <th scope="col" class="CP-sticky" nowrap>Proveedor</th>
                <th scope="col" class="CP-sticky" nowrap>Monto base</th>
                <th scope="col" class="CP-sticky" nowrap>Monto IVA</th>
                <th scope="col" class="CP-sticky" nowrap>Fecha</th>
                <th scope="col" class="CP-sticky" nowrap>Estado</th>
                <th scope="col" class="CP-sticky" nowrap>Acciones</th>
            </tr>
        </thead>
        <tbody>
        @foreach($prepagados as $prepagado)
            <tr>
              <td class="text-center" nowrap>{{ $prepagado->id }}</td>
              <td class="text-center" nowrap>{{ $prepagado->proveedor->nombre_proveedor }}</td>
              <td class="text-center" nowrap>{{ number_format($prepagado->monto, 2, ',', '.') }}</td>
              <td class="text-center" nowrap>{{ number_format($prepagado->monto_iva, 2, ',', '.') }}</td>
              <td class="text-center" nowrap>{{ $prepagado->created_at->format('d/m/Y h:i A') }}</td>
              <td class="text-center" nowrap>{{ $prepagado->status }}</td>
              <td class="text-center" nowrap>
                  <a href="{{ route('prepagados.edit', $prepagado) }}" class="btn btn-info btn-sm">
                      <i class="fa fa-edit"></i> Editar
                  </a>

                  @if($prepagado->status == 'Pendiente')
                    <div style="display: inline" class="dropdown show">
                      <a class="btn btn-success btn-sm dropdown-toggle" href="#" role="button" id="payLinkMenu" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <i class="fa fa-money-bill"></i> Pagar
                      </a>

                      <div class="dropdown-menu" aria-labelledby="payLinkMenu">
                        <a target="_target" class="dropdown-item" href="{{ '/bancarios/create?prepagado=' . $prepagado->id }}">Pagos bancarios</a>

                        <a target="_target" class="dropdown-item" href="{{ '/efectivoFTN/create?prepagado=' . $prepagado->id }}">Efectivo dólares FTN</a>
                        <a target="_target" class="dropdown-item" href="{{ '/efectivoFAU/create?prepagado=' . $prepagado->id }}">Efectivo dólares FAU</a>
                        <a target="_target" class="dropdown-item" href="{{ '/efectivoFLL/create?prepagado=' . $prepagado->id }}">Efectivo dólares FLL</a>

                        <a target="_target" class="dropdown-item" href="{{ '/bolivaresFTN/create?prepagado=' . $prepagado->id }}">Efectivo bolívares FTN</a>
                        <a target="_target" class="dropdown-item" href="{{ '/bolivaresFAU/create?prepagado=' . $prepagado->id }}">Efectivo bolívares FAU</a>
                        <a target="_target" class="dropdown-item" href="{{ '/bolivaresFLL/create?prepagado=' . $prepagado->id }}">Efectivo bolívares FLL</a>
                      </div>
                    </div>
                  @endif


                  @if($prepagado->status == 'Pendiente')
                    {!! Form::open(['route' => ['prepagados.destroy', $prepagado], 'method' => 'DELETE', 'style' => 'display: inline']) !!}
                      <button type="submit" class="btn btn-danger btn-sm">
                        <i class="fa fa-times"></i> Cancelar
                      </button>
                    {!! Form::close()!!}
                  @endif
              </td>
            </tr>
        @endforeach
        </tbody>
    </table>

@endsection
