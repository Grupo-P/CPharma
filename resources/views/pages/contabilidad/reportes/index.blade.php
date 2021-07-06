@extends('layouts.contabilidad')

@section('title')
  Reportes
@endsection

@section('content')

    <h1 class="h5 text-info">
        <i class="fas fa-file-invoice"></i>
        Reportes
    </h1>

    <hr class="row align-items-start col-12">

    <div class="card-deck">
        <div class="card border-danger mb-3" style="width: 14rem;">
            <div class="card-body text-left bg-danger">
                <h5 class="card-title">
                    <span class="card-text text-white">
                        Pagos por fecha
                    </span>
                </h5>
            </div>
            <div class="card-footer bg-transparent border-danger text-right">
                <a class="btn btn-outline-danger btn-sm" href="/reportes/pagos-por-fecha">Visualizar</a>
            </div>
        </div>

        <div class="card border-success mb-3" style="width: 14rem;">
            <div class="card-body text-left bg-success">
                <h5 class="card-title">
                    <span class="card-text text-white">
                        Movimientos por proveedor
                    </span>
                </h5>
            </div>
            <div class="card-footer bg-transparent border-success text-right">
                <a class="btn btn-outline-success btn-sm" href="/reportes/movimientos-por-proveedor">Visualizar</a>
            </div>
        </div>

        <div class="card border-info mb-3" style="width: 14rem;">
            <div class="card-body text-left bg-info">
                <h5 class="card-title">
                    <span class="card-text text-white">
                        Deudas por fecha
                    </span>
                </h5>
            </div>
            <div class="card-footer bg-transparent border-info text-right">
                <a class="btn btn-outline-info btn-sm" href="/reportes/deudas-por-fecha">Visualizar</a>
            </div>
        </div>
    </div>

    <div class="card-deck">
        <div class="card border-warning mb-3" style="width: 14rem;">
            <div class="card-body text-left bg-warning">
                <h5 class="card-title">
                    <span class="card-text text-white">
                        Movimientos bancarios
                    </span>
                </h5>
            </div>
            <div class="card-footer bg-transparent border-warning text-right">
                <a class="btn btn-outline-warning btn-sm" href="/reportes/movimientos-bancarios">Visualizar</a>
            </div>
        </div>
    </div>
@endsection
