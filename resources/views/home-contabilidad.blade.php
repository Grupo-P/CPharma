@extends('layouts.contabilidad')

@section('title')
  Dashboard
@endsection

@section('content')

<h1 class="h5 text-info">
    <i class="fas fa-columns"></i>
    Dashboard
</h1>

<hr class="row align-items-start col-12">

@if(Auth::user()->departamento == 'OPERACIONES')
    <div class="card-deck">
        <div class="card border-danger mb-3">
            <div class="card-body text-left bg-danger">
                <h2 class="card-title">
                    <span class="card-text text-white">
                        <i class="fa fa-ban"></i>
                        Última deuda
                    </span>
                </h2>
                <p class="card-text text-white">
                    @if($deuda)
                        <p class="text-white">
                            Proveedor: {{ $deuda->proveedor->nombre_proveedor }}</br>
                            Monto sin IVA: {{ $deuda->signo_moneda }} {{ number_format($deuda->monto, 2, ',', '.') }}</br>
                            Número de documento: {{ $deuda->numero_documento }}<br>
                            Fecha y hora: {{ $deuda->created_at->format('d/m/Y h:i A') }}</br>
                            Creado por: {{ $deuda->usuario_registro }}
                        </p>
                    @endif
                </p>
            </div>
            <div class="card-footer bg-transparent border-danger text-right">
                <a href="/deudas" class="btn btn-outline-danger btn-sm">Visualizar</a>
            </div>
        </div>

        <div class="card border-success mb-3">
            <div class="card-body text-left bg-success">
                <h2 class="card-title">
                    <span class="card-text text-white">
                        <i class="fa fa-ban"></i>
                        Última reclamo
                    </span>
                </h2>
                <p class="card-text text-white">
                    @if($reclamo)
                        <p class="text-white">
                            Proveedor: {{ $reclamo->proveedor->nombre_proveedor }}</br>
                            Monto sin IVA: {{ $reclamo->signo_moneda }} {{ number_format($reclamo->monto, 2) }}</br>
                            Número de documento: {{ $reclamo->numero_documento }}<br>
                            Fecha y hora: {{ $reclamo->created_at->format('d/m/Y h:i A') }}</br>
                            Creado por: {{ $reclamo->usuario_registro }}
                        </p>
                    @endif
                </p>
            </div>
            <div class="card-footer bg-transparent border-success text-right">
                <a href="/reclamos" class="btn btn-outline-success btn-sm">Visualizar</a>
            </div>
        </div>
    </div>
@endif

@if (Auth::user()->departamento == 'TECNOLOGIA' || Auth::user()->departamento == 'GERENCIA' || Auth::user()->departamento == 'ADMINISTRACION')
    {{-- FTN --}}
    <div class="card-deck">
        <div class="card border-danger mb-3">
            <div class="card-body text-left bg-danger">
                <h2 class="card-title">
                    <span class="card-text text-white">
                        <i class="fas fa-balance-scale"></i>
                        Saldo disponible FTN: ${{ number_format($dolaresFTN->saldo_actual, 2, ',', '.') }}
                    </span>
                </h2>
                <p class="card-text text-white">
                    Hora y fecha actual: {{ date_create()->format('d/m/Y h:i A') }} <br>
                    Último movimiento registrado: {{ ($dolaresFTN) ? $dolaresFTN->created_at->format('d/m/Y h:i A') : 'No hay movimientos' }}
                </p>
            </div>
            <div class="card-footer bg-transparent border-danger text-right">
                <a href="/efectivoFTN" class="btn btn-outline-danger btn-sm">Visualizar</a>
            </div>
        </div>

        <div class="card border-danger mb-3">
            <div class="card-body text-left bg-danger">
                <h2 class="card-title">
                    <span class="card-text text-white">
                        <i class="fas fa-balance-scale"></i>
                        Saldo disponible FTN: Bs.S. {{ number_format($bolivaresFTN->saldo_actual, 2, ',', '.') }}
                    </span>
                </h2>
                <p class="card-text text-white">
                    Hora y fecha actual: {{ date_create()->format('d/m/Y h:i A') }} <br>
                    Último movimiento registrado: {{ ($bolivaresFTN) ? $bolivaresFTN->created_at->format('d/m/Y h:i A') : 'No hay movimientos' }}
                </p>
            </div>
            <div class="card-footer bg-transparent border-danger text-right">
                <a href="/bolivaresFTN" class="btn btn-outline-danger btn-sm">Visualizar</a>
            </div>
        </div>

        <div class="card border-danger mb-3">
            <div class="card-body text-left bg-danger">
                <h2 class="card-title">
                    <span class="card-text text-white">
                        <i class="fas fa-balance-scale"></i>
                        Diferido en $ FTN: {{ number_format($diferidoDolaresFTN->diferido_actual, 2, ',', '.') }}
                    </span>
                </h2>
                <p class="card-text text-white">
                    Hora y fecha actual: {{ date_create()->format('d/m/Y h:i A') }} <br>
                    Último movimiento registrado: {{ ($diferidoDolaresFTN) ? $diferidoDolaresFTN->created_at->format('d/m/Y h:i A') : 'No hay movimientos' }}
                </p>
            </div>
            <div class="card-footer bg-transparent border-danger text-right">
                <a href="/contabilidad/diferidosFTN" class="btn btn-outline-danger btn-sm">Visualizar</a>
            </div>
        </div>

        <div class="card border-danger mb-3">
            <div class="card-body text-left bg-danger">
                <h2 class="card-title">
                    <span class="card-text text-white">
                        <i class="fas fa-balance-scale"></i>
                        Diferido en Bs.S. FTN: {{ number_format($diferidoBolivaresFTN->diferido_actual, 2, ',', '.') }}
                    </span>
                </h2>
                <p class="card-text text-white">
                    Hora y fecha actual: {{ date_create()->format('d/m/Y h:i A') }} <br>
                    Último movimiento registrado: {{ ($diferidoBolivaresFTN) ? $diferidoBolivaresFTN->created_at->format('d/m/Y h:i A') : 'No hay movimientos' }}
                </p>
            </div>
            <div class="card-footer bg-transparent border-danger text-right">
                <a href="/contabilidad/diferidosBolivaresFTN" class="btn btn-outline-danger btn-sm">Visualizar</a>
            </div>
        </div>
    </div>

    {{-- FAU --}}
    <div class="card-deck">
        <div class="card border-success mb-3">
            <div class="card-body text-left bg-success">
                <h2 class="card-title">
                    <span class="card-text text-white">
                        <i class="fas fa-balance-scale"></i>
                        Saldo disponible FAU: ${{ number_format($dolaresFAU->saldo_actual, 2, ',', '.') }}
                    </span>
                </h2>
                <p class="card-text text-white">
                    Hora y fecha actual: {{ date_create()->format('d/m/Y h:i A') }} <br>
                    Último movimiento registrado: {{ ($dolaresFAU) ? $dolaresFAU->created_at->format('d/m/Y h:i A') : 'No hay movimientos' }}
                </p>
            </div>
            <div class="card-footer bg-transparent border-success text-right">
                <a href="/efectivoFAU" class="btn btn-outline-success btn-sm">Visualizar</a>
            </div>
        </div>

        <div class="card border-success mb-3">
            <div class="card-body text-left bg-success">
                <h2 class="card-title">
                    <span class="card-text text-white">
                        <i class="fas fa-balance-scale"></i>
                        Saldo disponible FAU: Bs.S. {{ number_format($bolivaresFAU->saldo_actual, 2, ',', '.') }}
                    </span>
                </h2>
                <p class="card-text text-white">
                    Hora y fecha actual: {{ date_create()->format('d/m/Y h:i A') }} <br>
                    Último movimiento registrado: {{ ($bolivaresFAU) ? $bolivaresFAU->created_at->format('d/m/Y h:i A') : 'No hay movimientos' }}
                </p>
            </div>
            <div class="card-footer bg-transparent border-success text-right">
                <a href="/bolivaresFAU" class="btn btn-outline-success btn-sm">Visualizar</a>
            </div>
        </div>

        <div class="card border-success mb-3">
            <div class="card-body text-left bg-success">
                <h2 class="card-title">
                    <span class="card-text text-white">
                        <i class="fas fa-balance-scale"></i>
                        Diferido en $ FAU: {{ number_format($diferidoDolaresFAU->diferido_actual, 2, ',', '.') }}
                    </span>
                </h2>
                <p class="card-text text-white">
                    Hora y fecha actual: {{ date_create()->format('d/m/Y h:i A') }} <br>
                    Último movimiento registrado: {{ ($diferidoDolaresFAU) ? $diferidoDolaresFAU->created_at->format('d/m/Y h:i A') : 'No hay movimientos' }}
                </p>
            </div>
            <div class="card-footer bg-transparent border-success text-right">
                <a href="/contabilidad/diferidosFAU" class="btn btn-outline-success btn-sm">Visualizar</a>
            </div>
        </div>

        <div class="card border-success mb-3">
            <div class="card-body text-left bg-success">
                <h2 class="card-title">
                    <span class="card-text text-white">
                        <i class="fas fa-balance-scale"></i>
                        Diferido en Bs.S. FAU: {{ number_format($diferidoBolivaresFAU->diferido_actual, 2, ',', '.') }}
                    </span>
                </h2>
                <p class="card-text text-white">
                    Hora y fecha actual: {{ date_create()->format('d/m/Y h:i A') }} <br>
                    Último movimiento registrado: {{ ($diferidoBolivaresFAU) ? $diferidoBolivaresFAU->created_at->format('d/m/Y h:i A') : 'No hay movimientos' }}
                </p>
            </div>
            <div class="card-footer bg-transparent border-success text-right">
                <a href="/contabilidad/diferidosBolivaresFAU" class="btn btn-outline-success btn-sm">Visualizar</a>
            </div>
        </div>
    </div>

    {{-- FLL --}}
    <div class="card-deck">
        <div class="card border-info mb-3">
            <div class="card-body text-left bg-info">
                <h2 class="card-title">
                    <span class="card-text text-white">
                        <i class="fas fa-balance-scale"></i>
                        Saldo disponible FLL: ${{ number_format($dolaresFLL->saldo_actual, 2, ',', '.') }}
                    </span>
                </h2>
                <p class="card-text text-white">
                    Hora y fecha actual: {{ date_create()->format('d/m/Y h:i A') }} <br>
                    Último movimiento registrado: {{ ($dolaresFLL) ? $dolaresFLL->created_at->format('d/m/Y h:i A') : 'No hay movimientos' }}
                </p>
            </div>
            <div class="card-footer bg-transparent border-info text-right">
                <a href="/efectivoFLL" class="btn btn-outline-info btn-sm">Visualizar</a>
            </div>
        </div>

        <div class="card border-info mb-3">
            <div class="card-body text-left bg-info">
                <h2 class="card-title">
                    <span class="card-text text-white">
                        <i class="fas fa-balance-scale"></i>
                        Saldo disponible FLL: Bs.S. {{ number_format($bolivaresFLL->saldo_actual, 2, ',', '.') }}
                    </span>
                </h2>
                <p class="card-text text-white">
                    Hora y fecha actual: {{ date_create()->format('d/m/Y h:i A') }} <br>
                    Último movimiento registrado: {{ ($bolivaresFLL) ? $bolivaresFLL->created_at->format('d/m/Y h:i A') : 'No hay movimientos' }}
                </p>
            </div>
            <div class="card-footer bg-transparent border-info text-right">
                <a href="/bolivaresFLL" class="btn btn-outline-info btn-sm">Visualizar</a>
            </div>
        </div>

        <div class="card border-info mb-3">
            <div class="card-body text-left bg-info">
                <h2 class="card-title">
                    <span class="card-text text-white">
                        <i class="fas fa-balance-scale"></i>
                        Diferido en $ FLL: {{ number_format($diferidoDolaresFLL->diferido_actual, 2, ',', '.') }}
                    </span>
                </h2>
                <p class="card-text text-white">
                    Hora y fecha actual: {{ date_create()->format('d/m/Y h:i A') }} <br>
                    Último movimiento registrado: {{ ($diferidoDolaresFLL) ? $diferidoDolaresFLL->created_at->format('d/m/Y h:i A') : 'No hay movimientos' }}
                </p>
            </div>
            <div class="card-footer bg-transparent border-info text-right">
                <a href="/contabilidad/diferidosFLL" class="btn btn-outline-info btn-sm">Visualizar</a>
            </div>
        </div>

        <div class="card border-info mb-3">
            <div class="card-body text-left bg-info">
                <h2 class="card-title">
                    <span class="card-text text-white">
                        <i class="fas fa-balance-scale"></i>
                        Diferido en Bs.S. FLL: {{ number_format($diferidoBolivaresFLL->diferido_actual, 2, ',', '.') }}
                    </span>
                </h2>
                <p class="card-text text-white">
                    Hora y fecha actual: {{ date_create()->format('d/m/Y h:i A') }} <br>
                    Último movimiento registrado: {{ ($diferidoBolivaresFLL) ? $diferidoBolivaresFLL->created_at->format('d/m/Y h:i A') : 'No hay movimientos' }}
                </p>
            </div>
            <div class="card-footer bg-transparent border-info text-right">
                <a href="/contabilidad/diferidosBolivaresFLL" class="btn btn-outline-info btn-sm">Visualizar</a>
            </div>
        </div>
    </div>

    {{-- FM --}}
    <div class="card-deck">
        <div class="card border-warning mb-3">
            <div class="card-body text-left bg-warning">
                <h2 class="card-title">
                    <span class="card-text text-white">
                        <i class="fas fa-balance-scale"></i>
                        Saldo disponible FM: ${{ number_format($dolaresFM->saldo_actual, 2, ',', '.') }}
                    </span>
                </h2>
                <p class="card-text text-white">
                    Hora y fecha actual: {{ date_create()->format('d/m/Y h:i A') }} <br>
                    Último movimiento registrado: {{ ($dolaresFM) ? $dolaresFM->created_at->format('d/m/Y h:i A') : 'No hay movimientos' }}
                </p>
            </div>
            <div class="card-footer bg-transparent border-warning text-right">
                <a href="/efectivoFM" class="btn btn-outline-warning btn-sm">Visualizar</a>
            </div>
        </div>

        <div class="card border-warning mb-3">
            <div class="card-body text-left bg-warning">
                <h2 class="card-title">
                    <span class="card-text text-white">
                        <i class="fas fa-balance-scale"></i>
                        Saldo disponible FM: Bs.S. {{ number_format($bolivaresFM->saldo_actual, 2, ',', '.') }}
                    </span>
                </h2>
                <p class="card-text text-white">
                    Hora y fecha actual: {{ date_create()->format('d/m/Y h:i A') }} <br>
                    Último movimiento registrado: {{ ($bolivaresFM) ? $bolivaresFM->created_at->format('d/m/Y h:i A') : 'No hay movimientos' }}
                </p>
            </div>
            <div class="card-footer bg-transparent border-warning text-right">
                <a href="/bolivaresFM" class="btn btn-outline-warning btn-sm">Visualizar</a>
            </div>
        </div>

        <div class="card border-warning mb-3">
            <div class="card-body text-left bg-warning">
                <h2 class="card-title">
                    <span class="card-text text-white">
                        <i class="fas fa-balance-scale"></i>
                        Diferido en $ FM: {{ number_format($diferidoDolaresFM->diferido_actual, 2, ',', '.') }}
                    </span>
                </h2>
                <p class="card-text text-white">
                    Hora y fecha actual: {{ date_create()->format('d/m/Y h:i A') }} <br>
                    Último movimiento registrado: {{ ($diferidoDolaresFM) ? $diferidoDolaresFM->created_at->format('d/m/Y h:i A') : 'No hay movimientos' }}
                </p>
            </div>
            <div class="card-footer bg-transparent border-warning text-right">
                <a href="/contabilidad/diferidosFM" class="btn btn-outline-warning btn-sm">Visualizar</a>
            </div>
        </div>

        <div class="card border-warning mb-3">
            <div class="card-body text-left bg-warning">
                <h2 class="card-title">
                    <span class="card-text text-white">
                        <i class="fas fa-balance-scale"></i>
                        Diferido en Bs.S. FM: {{ number_format($diferidoBolivaresFM->diferido_actual, 2, ',', '.') }}
                    </span>
                </h2>
                <p class="card-text text-white">
                    Hora y fecha actual: {{ date_create()->format('d/m/Y h:i A') }} <br>
                    Último movimiento registrado: {{ ($diferidoBolivaresFM) ? $diferidoBolivaresFM->created_at->format('d/m/Y h:i A') : 'No hay movimientos' }}
                </p>
            </div>
            <div class="card-footer bg-transparent border-warning text-right">
                <a href="/contabilidad/diferidosBolivaresFM" class="btn btn-outline-warning btn-sm">Visualizar</a>
            </div>
        </div>
    </div>

    {{-- FEC --}}
    <div class="card-deck">
        <div class="card border-secondary mb-3">
            <div class="card-body text-left bg-secondary">
                <h2 class="card-title">
                    <span class="card-text text-white">
                        <i class="fas fa-balance-scale"></i>
                        Saldo disponible FEC: ${{ number_format($dolaresFEC->saldo_actual, 2, ',', '.') }}
                    </span>
                </h2>
                <p class="card-text text-white">
                    Hora y fecha actual: {{ date_create()->format('d/m/Y h:i A') }} <br>
                    Último movimiento registrado: {{ ($dolaresFEC) ? $dolaresFEC->created_at->format('d/m/Y h:i A') : 'No hay movimientos' }}
                </p>
            </div>
            <div class="card-footer bg-transparent border-secondary text-right">
                <a href="/efectivoFEC" class="btn btn-outline-secondary btn-sm">Visualizar</a>
            </div>
        </div>

        <div class="card border-secondary mb-3">
            <div class="card-body text-left bg-secondary">
                <h2 class="card-title">
                    <span class="card-text text-white">
                        <i class="fas fa-balance-scale"></i>
                        Saldo disponible FEC: Bs.S. {{ number_format($bolivaresFEC->saldo_actual, 2, ',', '.') }}
                    </span>
                </h2>
                <p class="card-text text-white">
                    Hora y fecha actual: {{ date_create()->format('d/m/Y h:i A') }} <br>
                    Último movimiento registrado: {{ ($bolivaresFEC) ? $bolivaresFEC->created_at->format('d/m/Y h:i A') : 'No hay movimientos' }}
                </p>
            </div>
            <div class="card-footer bg-transparent border-secondary text-right">
                <a href="/bolivaresFEC" class="btn btn-outline-secondary btn-sm">Visualizar</a>
            </div>
        </div>

        <div class="card border-secondary mb-3">
            <div class="card-body text-left bg-secondary">
                <h2 class="card-title">
                    <span class="card-text text-white">
                        <i class="fas fa-balance-scale"></i>
                        Diferido en $ FEC: {{ number_format($diferidoDolaresFEC->diferido_actual, 2, ',', '.') }}
                    </span>
                </h2>
                <p class="card-text text-white">
                    Hora y fecha actual: {{ date_create()->format('d/m/Y h:i A') }} <br>
                    Último movimiento registrado: {{ ($diferidoDolaresFEC) ? $diferidoDolaresFEC->created_at->format('d/m/Y h:i A') : 'No hay movimientos' }}
                </p>
            </div>
            <div class="card-footer bg-transparent border-secondary text-right">
                <a href="/contabilidad/diferidosFEC" class="btn btn-outline-secondary btn-sm">Visualizar</a>
            </div>
        </div>

        <div class="card border-secondary mb-3">
            <div class="card-body text-left bg-secondary">
                <h2 class="card-title">
                    <span class="card-text text-white">
                        <i class="fas fa-balance-scale"></i>
                        Diferido en Bs.S. FEC: {{ number_format($diferidoBolivaresFEC->diferido_actual, 2, ',', '.') }}
                    </span>
                </h2>
                <p class="card-text text-white">
                    Hora y fecha actual: {{ date_create()->format('d/m/Y h:i A') }} <br>
                    Último movimiento registrado: {{ ($diferidoBolivaresFEC) ? $diferidoBolivaresFEC->created_at->format('d/m/Y h:i A') : 'No hay movimientos' }}
                </p>
            </div>
            <div class="card-footer bg-transparent border-secondary text-right">
                <a href="/contabilidad/diferidosBolivaresFEC" class="btn btn-outline-secondary btn-sm">Visualizar</a>
            </div>
        </div>
    </div>

    {{-- FLF --}}
    <div class="card-deck">
        <div class="card border-primary mb-3">
            <div class="card-body text-left bg-primary">
                <h2 class="card-title">
                    <span class="card-text text-white">
                        <i class="fas fa-balance-scale"></i>
                        Saldo disponible FLF: ${{ number_format($dolaresFLF->saldo_actual, 2, ',', '.') }}
                    </span>
                </h2>
                <p class="card-text text-white">
                    Hora y fecha actual: {{ date_create()->format('d/m/Y h:i A') }} <br>
                    Último movimiento registrado: {{ ($dolaresFLF) ? $dolaresFLF->created_at->format('d/m/Y h:i A') : 'No hay movimientos' }}
                </p>
            </div>
            <div class="card-footer bg-transparent border-primary text-right">
                <a href="/efectivoFLF" class="btn btn-outline-primary btn-sm">Visualizar</a>
            </div>
        </div>

        <div class="card border-primary mb-3">
            <div class="card-body text-left bg-primary">
                <h2 class="card-title">
                    <span class="card-text text-white">
                        <i class="fas fa-balance-scale"></i>
                        Saldo disponible FLF: Bs.S. {{ number_format($bolivaresFLF->saldo_actual, 2, ',', '.') }}
                    </span>
                </h2>
                <p class="card-text text-white">
                    Hora y fecha actual: {{ date_create()->format('d/m/Y h:i A') }} <br>
                    Último movimiento registrado: {{ ($bolivaresFLF) ? $bolivaresFLF->created_at->format('d/m/Y h:i A') : 'No hay movimientos' }}
                </p>
            </div>
            <div class="card-footer bg-transparent border-primary text-right">
                <a href="/bolivaresFLF" class="btn btn-outline-primary btn-sm">Visualizar</a>
            </div>
        </div>

        <div class="card border-primary mb-3">
            <div class="card-body text-left bg-primary">
                <h2 class="card-title">
                    <span class="card-text text-white">
                        <i class="fas fa-balance-scale"></i>
                        Diferido en $ FLF: {{ number_format($diferidoDolaresFLF->diferido_actual, 2, ',', '.') }}
                    </span>
                </h2>
                <p class="card-text text-white">
                    Hora y fecha actual: {{ date_create()->format('d/m/Y h:i A') }} <br>
                    Último movimiento registrado: {{ ($diferidoDolaresFLF) ? $diferidoDolaresFLF->created_at->format('d/m/Y h:i A') : 'No hay movimientos' }}
                </p>
            </div>
            <div class="card-footer bg-transparent border-primary text-right">
                <a href="/contabilidad/diferidosFLF" class="btn btn-outline-primary btn-sm">Visualizar</a>
            </div>
        </div>

        <div class="card border-primary mb-3">
            <div class="card-body text-left bg-primary">
                <h2 class="card-title">
                    <span class="card-text text-white">
                        <i class="fas fa-balance-scale"></i>
                        Diferido en Bs.S. FLF: {{ number_format($diferidoBolivaresFLF->diferido_actual, 2, ',', '.') }}
                    </span>
                </h2>
                <p class="card-text text-white">
                    Hora y fecha actual: {{ date_create()->format('d/m/Y h:i A') }} <br>
                    Último movimiento registrado: {{ ($diferidoBolivaresFLF) ? $diferidoBolivaresFLF->created_at->format('d/m/Y h:i A') : 'No hay movimientos' }}
                </p>
            </div>
            <div class="card-footer bg-transparent border-primary text-right">
                <a href="/contabilidad/diferidosBolivaresFLF" class="btn btn-outline-primary btn-sm">Visualizar</a>
            </div>
        </div>
    </div>

    {{-- CDD --}}
    <div class="card-deck">
        <div class="card border-primary mb-3">
            <div class="card-body text-left bg-primary">
                <h2 class="card-title">
                    <span class="card-text text-white">
                        <i class="fas fa-balance-scale"></i>
                        Saldo disponible CDD: ${{ number_format($dolaresCDD->saldo_actual, 2, ',', '.') }}
                    </span>
                </h2>
                <p class="card-text text-white">
                    Hora y fecha actual: {{ date_create()->format('d/m/Y h:i A') }} <br>
                    Último movimiento registrado: {{ ($dolaresCDD) ? $dolaresCDD->created_at->format('d/m/Y h:i A') : 'No hay movimientos' }}
                </p>
            </div>
            <div class="card-footer bg-transparent border-primary text-right">
                <a href="/efectivoCDD" class="btn btn-outline-primary btn-sm">Visualizar</a>
            </div>
        </div>

        <div class="card border-primary mb-3">
            <div class="card-body text-left bg-primary">
                <h2 class="card-title">
                    <span class="card-text text-white">
                        <i class="fas fa-balance-scale"></i>
                        Saldo disponible CDD: Bs.S. {{ number_format($bolivaresCDD->saldo_actual, 2, ',', '.') }}
                    </span>
                </h2>
                <p class="card-text text-white">
                    Hora y fecha actual: {{ date_create()->format('d/m/Y h:i A') }} <br>
                    Último movimiento registrado: {{ ($bolivaresCDD) ? $bolivaresCDD->created_at->format('d/m/Y h:i A') : 'No hay movimientos' }}
                </p>
            </div>
            <div class="card-footer bg-transparent border-primary text-right">
                <a href="/bolivaresCDD" class="btn btn-outline-primary btn-sm">Visualizar</a>
            </div>
        </div>

        <div class="card border-primary mb-3">
            <div class="card-body text-left bg-primary">
                <h2 class="card-title">
                    <span class="card-text text-white">
                        <i class="fas fa-balance-scale"></i>
                        Diferido en $ CDD: {{ number_format($diferidoDolaresCDD->diferido_actual, 2, ',', '.') }}
                    </span>
                </h2>
                <p class="card-text text-white">
                    Hora y fecha actual: {{ date_create()->format('d/m/Y h:i A') }} <br>
                    Último movimiento registrado: {{ ($diferidoDolaresCDD) ? $diferidoDolaresCDD->created_at->format('d/m/Y h:i A') : 'No hay movimientos' }}
                </p>
            </div>
            <div class="card-footer bg-transparent border-primary text-right">
                <a href="/contabilidad/diferidosCDD" class="btn btn-outline-primary btn-sm">Visualizar</a>
            </div>
        </div>

        <div class="card border-primary mb-3">
            <div class="card-body text-left bg-primary">
                <h2 class="card-title">
                    <span class="card-text text-white">
                        <i class="fas fa-balance-scale"></i>
                        Diferido en Bs.S. CDD: {{ number_format($diferidoBolivaresCDD->diferido_actual, 2, ',', '.') }}
                    </span>
                </h2>
                <p class="card-text text-white">
                    Hora y fecha actual: {{ date_create()->format('d/m/Y h:i A') }} <br>
                    Último movimiento registrado: {{ ($diferidoBolivaresCDD) ? $diferidoBolivaresCDD->created_at->format('d/m/Y h:i A') : 'No hay movimientos' }}
                </p>
            </div>
            <div class="card-footer bg-transparent border-primary text-right">
                <a href="/contabilidad/diferidosBolivaresCDD" class="btn btn-outline-primary btn-sm">Visualizar</a>
            </div>
        </div>
    </div>

    {{-- PAG --}}
    <div class="card-deck">
        <div class="card border-danger mb-3">
            <div class="card-body text-left bg-danger">
                <h2 class="card-title">
                    <span class="card-text text-white">
                        <i class="fas fa-balance-scale"></i>
                        Saldo disponible PAG: ${{ number_format($dolaresPAG->saldo_actual, 2, ',', '.') }}
                    </span>
                </h2>
                <p class="card-text text-white">
                    Hora y fecha actual: {{ date_create()->format('d/m/Y h:i A') }} <br>
                    Último movimiento registrado: {{ ($dolaresPAG) ? $dolaresPAG->created_at->format('d/m/Y h:i A') : 'No hay movimientos' }}
                </p>
            </div>
            <div class="card-footer bg-transparent border-danger text-right">
                <a href="/efectivoPAG" class="btn btn-outline-danger btn-sm">Visualizar</a>
            </div>
        </div>

        <div class="card border-danger mb-3">
            <div class="card-body text-left bg-danger">
                <h2 class="card-title">
                    <span class="card-text text-white">
                        <i class="fas fa-balance-scale"></i>
                        Saldo disponible PAG: Bs.S. {{ number_format($bolivaresPAG->saldo_actual, 2, ',', '.') }}
                    </span>
                </h2>
                <p class="card-text text-white">
                    Hora y fecha actual: {{ date_create()->format('d/m/Y h:i A') }} <br>
                    Último movimiento registrado: {{ ($bolivaresPAG) ? $bolivaresPAG->created_at->format('d/m/Y h:i A') : 'No hay movimientos' }}
                </p>
            </div>
            <div class="card-footer bg-transparent border-danger text-right">
                <a href="/bolivaresPAG" class="btn btn-outline-danger btn-sm">Visualizar</a>
            </div>
        </div>

        <div class="card border-danger mb-3">
            <div class="card-body text-left bg-danger">
                <h2 class="card-title">
                    <span class="card-text text-white">
                        <i class="fas fa-balance-scale"></i>
                        Diferido en $ PAG: {{ number_format($diferidoDolaresPAG->diferido_actual, 2, ',', '.') }}
                    </span>
                </h2>
                <p class="card-text text-white">
                    Hora y fecha actual: {{ date_create()->format('d/m/Y h:i A') }} <br>
                    Último movimiento registrado: {{ ($diferidoDolaresPAG) ? $diferidoDolaresPAG->created_at->format('d/m/Y h:i A') : 'No hay movimientos' }}
                </p>
            </div>
            <div class="card-footer bg-transparent border-danger text-right">
                <a href="/contabilidad/diferidosPAG" class="btn btn-outline-danger btn-sm">Visualizar</a>
            </div>
        </div>

        <div class="card border-danger mb-3">
            <div class="card-body text-left bg-danger">
                <h2 class="card-title">
                    <span class="card-text text-white">
                        <i class="fas fa-balance-scale"></i>
                        Diferido en Bs.S. PAG: {{ number_format($diferidoBolivaresPAG->diferido_actual, 2, ',', '.') }}
                    </span>
                </h2>
                <p class="card-text text-white">
                    Hora y fecha actual: {{ date_create()->format('d/m/Y h:i A') }} <br>
                    Último movimiento registrado: {{ ($diferidoBolivaresPAG) ? $diferidoBolivaresPAG->created_at->format('d/m/Y h:i A') : 'No hay movimientos' }}
                </p>
            </div>
            <div class="card-footer bg-transparent border-danger text-right">
                <a href="/contabilidad/diferidosBolivaresPAG" class="btn btn-outline-danger btn-sm">Visualizar</a>
            </div>
        </div>
    </div>

    {{-- Ultimos --}}
    <div class="card-deck">
        <div class="card border-danger mb-3">
            <div class="card-body text-left bg-danger">
                <h2 class="card-title">
                    <span class="card-text text-white">
                        <i class="fas fa-money-bill"></i>
                        Último pago
                    </span>
                </h2>
                <p class="card-text text-white">
                    @if(isset($pago))
                        <p class="text-white">
                            @if($pago->proveedor)
                                Nombre del proveedor: {{ $pago->proveedor->nombre_proveedor }}

                            @elseif($pago->concepto)
                                Concepto: {{ $pago->concepto }}

                            @else($pago->proveedor)
                                Comentario: {{ $pago->comentario }}
                            @endif
                            <br>

                            Monto: {{ $pago->signo_moneda }}

                            @if($pago->diferido)
                                {{ number_format($pago->diferido, 2, ',', '.') }}
                            @endif

                            @if($pago->egresos)
                                {{ number_format($pago->egresos, 2, ',', '.') }}
                            @endif

                            @if($pago->monto)
                                {{ number_format($pago->monto, 2, ',', '.') }}
                            @endif

                            </br>

                            Emisor:

                            @if($pago->banco)
                                {{ $pago->banco->alias_cuenta }}
                            @else
                                {{-- Divisas --}}
                                @if(get_class($pago) == 'compras\ContPagoEfectivoFTN')
                                    Pago dólares efectivo FTN
                                @endif

                                @if(get_class($pago) == 'compras\ContPagoEfectivoFAU')
                                    Pago dólares efectivo FAU
                                @endif

                                @if(get_class($pago) == 'compras\ContPagoEfectivoFLL')
                                    Pago dólares efectivo FLL
                                @endif

                                @if(get_class($pago) == 'compras\ContPagoEfectivoFM')
                                    Pago dólares efectivo FM
                                @endif

                                @if(get_class($pago) == 'compras\ContPagoEfectivoFEC')
                                    Pago dólares efectivo FEC
                                @endif

                                @if(get_class($pago) == 'compras\ContPagoEfectivoFLF')
                                    Pago dólares efectivo FLF
                                @endif

                                @if(get_class($pago) == 'compras\ContPagoEfectivoCDD')
                                    Pago dólares efectivo CDD
                                @endif

                                @if(get_class($pago) == 'compras\ContPagoEfectivoPAG')
                                    Pago dólares efectivo PAG
                                @endif

                                {{-- Bolivares --}}
                                @if(get_class($pago) == 'compras\ContPagoBolivaresFTN')
                                    Pago efectivo bolívares FTN
                                @endif

                                @if(get_class($pago) == 'compras\ContPagoBolivaresFAU')
                                    Pago efectivo bolívares FAU
                                @endif

                                @if(get_class($pago) == 'compras\ContPagoBolivaresFLL')
                                    Pago efectivo bolívares FLL
                                @endif

                                @if(get_class($pago) == 'compras\ContPagoBolivaresFM')
                                    Pago efectivo bolívares FM
                                @endif

                                @if(get_class($pago) == 'compras\ContPagoBolivaresFEC')
                                    Pago efectivo bolívares FEC
                                @endif

                                @if(get_class($pago) == 'compras\ContPagoBolivaresFLF')
                                    Pago efectivo bolívares FLF
                                @endif

                                @if(get_class($pago) == 'compras\ContPagoBolivaresCDD')
                                    Pago efectivo bolívares CDD
                                @endif

                                @if(get_class($pago) == 'compras\ContPagoBolivaresPAG')
                                    Pago efectivo bolívares PAG
                                @endif
                            @endif

                            <br>

                            Operador: {{ ($pago->user) ? $pago->user : $pago->operador }}<br>

                            Fecha y hora: {{ $pago->created_at->format('d/m/Y h:i A') }}</br>
                        </p>
                    @endif
                </p>
            </div>
        </div>

        <div class="card border-success mb-3">
            <div class="card-body text-left bg-success">
                <h2 class="card-title">
                    <span class="card-text text-white">
                        <i class="fa fa-ban"></i>
                        Última deuda
                    </span>
                </h2>
                <p class="card-text text-white">
                    @if(isset($deuda))
                        <p class="text-white">
                            Proveedor: {{ $deuda->proveedor->nombre_proveedor }}</br>
                            Monto sin IVA: {{ $deuda->signo_moneda }} {{ number_format($deuda->monto, 2, ',', '.') }}</br>
                            Número de documento: {{ $deuda->numero_documento }}<br>
                            Fecha y hora: {{ $deuda->created_at->format('d/m/Y h:i A') }}</br>
                            Creado por: {{ $deuda->usuario_registro }}
                        </p>
                    @endif
                </p>
            </div>
            <div class="card-footer bg-transparent border-success text-right">
                <a href="/deudas" class="btn btn-outline-success btn-sm">Visualizar</a>
            </div>
        </div>
    </div>
@endif

@if (Auth::user()->departamento == 'CONTABILIDAD')
    <div class="card-deck">
        <div class="card border-warning mb-3">
            <div class="card-body text-left bg-warning">
                <h2 class="card-title">
                    <span class="card-text text-white">
                        <i class="fas fa-money-bill"></i>
                        Último pago
                    </span>
                </h2>
                <p class="card-text text-white">
                    @if(isset($pago))
                        <p class="text-white">
                            @if($pago->proveedor)
                                Nombre del proveedor: {{ $pago->proveedor->nombre_proveedor }}

                            @elseif($pago->concepto)
                                Concepto: {{ $pago->concepto }}

                            @else($pago->proveedor)
                                Comentario: {{ $pago->comentario }}
                            @endif
                            <br>

                            Monto: {{ $pago->signo_moneda }}

                            @if($pago->diferido)
                                {{ number_format($pago->diferido, 2, ',', '.') }}
                            @endif

                            @if($pago->egresos)
                                {{ number_format($pago->egresos, 2, ',', '.') }}
                            @endif

                            @if($pago->monto)
                                {{ number_format($pago->monto, 2, ',', '.') }}
                            @endif

                            </br>

                            Emisor:

                            @if($pago->banco)
                                {{ $pago->banco->alias_cuenta }}
                            @else
                                {{-- Divisas --}}
                                @if(get_class($pago) == 'compras\ContPagoEfectivoFTN')
                                    Pago dólares efectivo FTN
                                @endif

                                @if(get_class($pago) == 'compras\ContPagoEfectivoFAU')
                                    Pago dólares efectivo FAU
                                @endif

                                @if(get_class($pago) == 'compras\ContPagoEfectivoFLL')
                                    Pago dólares efectivo FLL
                                @endif

                                @if(get_class($pago) == 'compras\ContPagoEfectivoFM')
                                    Pago dólares efectivo FM
                                @endif

                                @if(get_class($pago) == 'compras\ContPagoEfectivoFEC')
                                    Pago dólares efectivo FEC
                                @endif

                                @if(get_class($pago) == 'compras\ContPagoEfectivoFLF')
                                    Pago dólares efectivo FLF
                                @endif

                                @if(get_class($pago) == 'compras\ContPagoEfectivoCDD')
                                    Pago dólares efectivo CDD
                                @endif

                                @if(get_class($pago) == 'compras\ContPagoEfectivoPAG')
                                    Pago dólares efectivo PAG
                                @endif

                                {{-- BOlivares --}}
                                @if(get_class($pago) == 'compras\ContPagoBolivaresFTN')
                                    Pago bolívares efectivo FTN
                                @endif

                                @if(get_class($pago) == 'compras\ContPagoBolivaresFAU')
                                    Pago bolívares efectivo FAU
                                @endif

                                @if(get_class($pago) == 'compras\ContPagoBolivaresFLL')
                                    Pago bolívares efectivo FLL
                                @endif

                                @if(get_class($pago) == 'compras\ContPagoBolivaresFM')
                                    Pago bolívares efectivo FM
                                @endif

                                @if(get_class($pago) == 'compras\ContPagoBolivaresFEC')
                                    Pago bolívares efectivo FEC
                                @endif

                                @if(get_class($pago) == 'compras\ContPagoBolivaresFLF')
                                    Pago bolívares efectivo FLF
                                @endif

                                @if(get_class($pago) == 'compras\ContPagoBolivaresCDD')
                                    Pago bolívares efectivo CDD
                                @endif

                                @if(get_class($pago) == 'compras\ContPagoBolivaresPAG')
                                    Pago bolívares efectivo PAG
                                @endif
                            @endif

                            <br>

                            Operador: {{ ($pago->user) ? $pago->user : $pago->operador }}<br>

                            Fecha y hora: {{ $pago->created_at->format('d/m/Y h:i A') }}</br>
                        </p>
                    @endif
                </p>
            </div>
        </div>

        <div class="card border-dark mb-3">
            <div class="card-body text-left bg-dark">
                <h2 class="card-title">
                    <span class="card-text text-white">
                        <i class="fas fa-check-double"></i>
                        Última conciliación
                    </span>
                </h2>
                <p class="card-text text-white">
                    <p class="text-white">
                        Proveedor: {{ $conciliacion->proveedor->nombre_proveedor }}</br>

                        Monto: {{ $conciliacion->signo_moneda }}

                        @if($conciliacion->diferido)
                            {{ number_format($conciliacion->diferido, 2, ',', '.') }}
                        @endif

                        @if($conciliacion->egresos)
                            {{ number_format($conciliacion->egresos, 2, ',', '.') }}
                        @endif

                        @if($conciliacion->monto)
                            {{ number_format($conciliacion->monto, 2, ',', '.') }}
                        @endif

                        <br>

                        Emisor:

                        @if($conciliacion->banco)
                            {{ $conciliacion->banco->alias_cuenta }}
                        @else
                            {{-- Divisas --}}
                            @if(get_class($conciliacion) == 'compras\ContPagoEfectivoFTN')
                                Pago dólares efectivo FTN
                            @endif

                            @if(get_class($conciliacion) == 'compras\ContPagoEfectivoFAU')
                                Pago dólares efectivo FAU
                            @endif

                            @if(get_class($conciliacion) == 'compras\ContPagoEfectivoFLL')
                                Pago dólares efectivo FLL
                            @endif

                            @if(get_class($conciliacion) == 'compras\ContPagoEfectivoFM')
                                Pago dólares efectivo FM
                            @endif

                            @if(get_class($conciliacion) == 'compras\ContPagoEfectivoFEC')
                                Pago dólares efectivo FEC
                            @endif

                            @if(get_class($conciliacion) == 'compras\ContPagoEfectivoFLF')
                                Pago dólares efectivo FLF
                            @endif

                            @if(get_class($conciliacion) == 'compras\ContPagoEfectivoCDD')
                                Pago dólares efectivo CDD
                            @endif

                            @if(get_class($conciliacion) == 'compras\ContPagoEfectivoPAG')
                                Pago dólares efectivo PAG
                            @endif

                            {{-- Bolivares --}}
                            @if(get_class($conciliacion) == 'compras\ContPagoBolivaresFTN')
                                Pago bolívares efectivo FTN
                            @endif

                            @if(get_class($conciliacion) == 'compras\ContPagoBolivaresFAU')
                                Pago bolívares efectivo FAU
                            @endif

                            @if(get_class($conciliacion) == 'compras\ContPagoBolivaresFLL')
                                Pago bolívares efectivo FLL
                            @endif

                            @if(get_class($conciliacion) == 'compras\ContPagoBolivaresFM')
                                Pago bolívares efectivo FM
                            @endif

                            @if(get_class($conciliacion) == 'compras\ContPagoBolivaresFEC')
                                Pago bolívares efectivo FEC
                            @endif

                            @if(get_class($conciliacion) == 'compras\ContPagoBolivaresFLF')
                                Pago bolívares efectivo FLF
                            @endif

                            @if(get_class($conciliacion) == 'compras\ContPagoBolivaresCDD')
                                Pago bolívares efectivo CDD
                            @endif

                            @if(get_class($conciliacion) == 'compras\ContPagoBolivaresPAG')
                                Pago bolívares efectivo PAG
                            @endif
                        @endif

                        <br>

                        Conciliado por: {{ $conciliacion->usuario_conciliado }}<br>
                        Fecha y hora: {{ ($conciliacion) ? date_create($conciliacion->fecha_conciliado)->format('d/m/Y h:i A') : '' }}</br>
                    </p>
                </p>
            </div>
        </div>

        <div class="card border-success mb-3">
            <div class="card-body text-left bg-success">
                <h2 class="card-title">
                    <span class="card-text text-white">
                        <i class="fa fa-ban"></i>
                        Última deuda
                    </span>
                </h2>
                <p class="card-text text-white">
                    @if(isset($deuda))
                        <p class="text-white">
                            Proveedor: {{ $deuda->proveedor->nombre_proveedor }}</br>
                            Monto sin IVA: {{ $deuda->signo_moneda }} {{ number_format($deuda->monto, 2, ',', '.') }}</br>
                            Número de documento: {{ $deuda->numero_documento }}<br>
                            Fecha y hora: {{ $deuda->created_at->format('d/m/Y h:i A') }}</br>
                            Creado por: {{ $deuda->usuario_registro }}
                        </p>
                    @endif
                </p>
            </div>
            <div class="card-footer bg-transparent border-success text-right">
                <a href="" class="btn btn-outline-success btn-sm">Visualizar</a>
            </div>
        </div>
    </div>
@endif

@if (Auth::user()->departamento == 'TESORERIA')
    <div class="card-deck">
        <div class="card border-danger mb-3">
            <div class="card-body text-left bg-danger">
                <h2 class="card-title">
                    <span class="card-text text-white">
                        <i class="fas fa-balance-scale"></i>
                        Saldo disponible: ${{ number_format($dolares->saldo_actual, 2, ',', '.') }}
                    </span>
                </h2>
                <p class="card-text text-white">
                    Hora y fecha actual: {{ date_create()->format('d/m/Y h:i A') }} <br>
                    Último movimiento registrado: {{ ($dolares) ? $dolares->created_at->format('d/m/Y h:i A') : 'No hay movimientos' }}
                </p>
            </div>
            <div class="card-footer bg-transparent border-danger text-right">
                <a href="{{ '/efectivo' . $sede }}" class="btn btn-outline-danger btn-sm">Visualizar</a>
            </div>
        </div>

        <div class="card border-success mb-3">
            <div class="card-body text-left bg-success">
                <h2 class="card-title">
                    <span class="card-text text-white">
                        <i class="fas fa-balance-scale"></i>
                        Saldo disponible: Bs.S. {{ number_format($bolivares->saldo_actual, 2, ',', '.') }}
                    </span>
                </h2>
                <p class="card-text text-white">
                    Hora y fecha actual: {{ date_create()->format('d/m/Y h:i A') }} <br>
                    Último movimiento registrado: {{ ($bolivares) ? $bolivares->created_at->format('d/m/Y h:i A') : 'No hay movimientos' }}
                </p>
            </div>
            <div class="card-footer bg-transparent border-success text-right">
                <a href="{{ '/bolivares' . $sede }}" class="btn btn-outline-success btn-sm">Visualizar</a>
            </div>
        </div>
    </div>

    <div class="card-deck">
        <div class="card border-info mb-3">
            <div class="card-body text-left bg-info">
                <h2 class="card-title">
                    <span class="card-text text-white">
                        <i class="fas fa-balance-scale"></i>
                        Diferido: ${{ number_format($diferidoDolares->diferido_actual, 2, ',', '.') }}
                    </span>
                </h2>
                <p class="card-text text-white">
                    Hora y fecha actual: {{ date_create()->format('d/m/Y h:i A') }} <br>
                    Último movimiento registrado: {{ ($diferidoDolares) ? $diferidoDolares->created_at->format('d/m/Y h:i A') : 'No hay movimientos' }}
                </p>
            </div>
            <div class="card-footer bg-transparent border-info text-right">
                <a href="{{ '/contabilidad/diferidos' . $sede }}" class="btn btn-outline-info btn-sm">Visualizar</a>
            </div>
        </div>

        <div class="card border-warning mb-3">
            <div class="card-body text-left bg-warning">
                <h2 class="card-title">
                    <span class="card-text text-white">
                        <i class="fas fa-balance-scale"></i>
                        Diferido: Bs.S. {{ number_format($diferidoBolivares->diferido_actual, 2, ',', '.') }}
                    </span>
                </h2>
                <p class="card-text text-white">
                    Hora y fecha actual: {{ date_create()->format('d/m/Y h:i A') }} <br>
                    Último movimiento registrado: {{ ($diferidoBolivares) ? $diferidoBolivares->created_at->format('d/m/Y h:i A') : 'No hay movimientos' }}
                </p>
            </div>
            <div class="card-footer bg-transparent border-warning text-right">
                <a href="{{ '/contabilidad/diferidosBolivares' . $sede }}" class="btn btn-outline-warning btn-sm">Visualizar</a>
            </div>
        </div>
    </div>

    <div class="card-deck">
        <div class="card border-secondary mb-3">
            <div class="card-body text-left bg-secondary">
                <h2 class="card-title">
                    <span class="card-text text-white">
                        <i class="fas fa-money-bill"></i>
                        Último movimiento
                    </span>
                </h2>
                <p class="card-text text-white">
                    @if($movimiento)
                        <p class="text-white">
                            @if($movimiento->proveedor)
                                Nombre del proveedor: {{ $movimiento->proveedor->nombre_proveedor }}

                            @elseif($movimiento->concepto)
                                Concepto: {{ $movimiento->concepto }}

                            @else($movimiento->proveedor)
                                Comentario: {{ $movimiento->comentario }}
                            @endif
                            <br>

                            Monto: {{ $movimiento->signo_moneda }}

                            @if($movimiento->diferido)
                                {{ number_format($movimiento->diferido, 2, ',', '.') }}
                            @endif

                            @if($movimiento->egresos)
                                {{ number_format($movimiento->egresos, 2, ',', '.') }}
                            @endif

                            @if($movimiento->monto)
                                {{ number_format($movimiento->monto, 2, ',', '.') }}
                            @endif

                            @if($movimiento->ingresos)
                                {{ number_format($movimiento->ingresos, 2, ',', '.') }}
                            @endif

                            </br>

                            Emisor:

                            @if($movimiento->ingresos)
                                {{-- Divisas --}}
                                @if(get_class($movimiento) == 'compras\ContPagoEfectivoFTN')
                                    Ingreso dólares efectivo FTN
                                @endif

                                @if(get_class($movimiento) == 'compras\ContPagoEfectivoFAU')
                                    Ingreso dólares efectivo FAU
                                @endif

                                @if(get_class($movimiento) == 'compras\ContPagoEfectivoFLL')
                                    Ingreso dólares efectivo FLL
                                @endif

                                @if(get_class($movimiento) == 'compras\ContPagoEfectivoFM')
                                    Ingreso dólares efectivo FM
                                @endif

                                @if(get_class($movimiento) == 'compras\ContPagoEfectivoFEC')
                                    Ingreso dólares efectivo FEC
                                @endif

                                @if(get_class($movimiento) == 'compras\ContPagoEfectivoFLF')
                                    Ingreso dólares efectivo FLF
                                @endif

                                @if(get_class($movimiento) == 'compras\ContPagoEfectivoCDD')
                                    Ingreso dólares efectivo CDD
                                @endif

                                @if(get_class($movimiento) == 'compras\ContPagoEfectivoPAG')
                                    Ingreso dólares efectivo PAG
                                @endif

                                {{-- Bolivares --}}
                                @if(get_class($movimiento) == 'compras\ContPagoBolivaresFTN')
                                    Ingreso efectivo bolívares FTN
                                @endif

                                @if(get_class($movimiento) == 'compras\ContPagoBolivaresFAU')
                                    Ingreso efectivo bolívares FAU
                                @endif

                                @if(get_class($movimiento) == 'compras\ContPagoBolivaresFLL')
                                    Ingreso efectivo bolívares FLL
                                @endif

                                @if(get_class($movimiento) == 'compras\ContPagoBolivaresFM')
                                    Ingreso efectivo bolívares FM
                                @endif

                                @if(get_class($movimiento) == 'compras\ContPagoBolivaresFEC')
                                    Ingreso efectivo bolívares FEC
                                @endif

                                @if(get_class($movimiento) == 'compras\ContPagoBolivaresFLF')
                                    Ingreso efectivo bolívares FLF
                                @endif

                                @if(get_class($movimiento) == 'compras\ContPagoBolivaresCDD')
                                    Ingreso efectivo bolívares CDD
                                @endif

                                @if(get_class($movimiento) == 'compras\ContPagoBolivaresPAG')
                                    Ingreso efectivo bolívares PAG
                                @endif
                            @else
                                @if($movimiento->banco)
                                    {{ $movimiento->banco->alias_cuenta }}
                                @else
                                    {{-- Divisas --}}
                                    @if(get_class($movimiento) == 'compras\ContPagoEfectivoFTN')
                                        Pago dólares efectivo FTN
                                    @endif

                                    @if(get_class($movimiento) == 'compras\ContPagoEfectivoFAU')
                                        Pago dólares efectivo FAU
                                    @endif

                                    @if(get_class($movimiento) == 'compras\ContPagoEfectivoFLL')
                                        Pago dólares efectivo FLL
                                    @endif

                                    @if(get_class($movimiento) == 'compras\ContPagoEfectivoFM')
                                        Pago dólares efectivo FM
                                    @endif

                                    @if(get_class($movimiento) == 'compras\ContPagoEfectivoFEC')
                                        Pago dólares efectivo FEC
                                    @endif

                                    @if(get_class($movimiento) == 'compras\ContPagoEfectivoFLF')
                                        Pago dólares efectivo FLF
                                    @endif

                                    @if(get_class($movimiento) == 'compras\ContPagoEfectivoCDD')
                                        Pago dólares efectivo CDD
                                    @endif

                                    @if(get_class($movimiento) == 'compras\ContPagoEfectivoPAG')
                                        Pago dólares efectivo PAG
                                    @endif

                                    {{-- Bolivares --}}
                                    @if(get_class($movimiento) == 'compras\ContPagoBolivaresFTN')
                                        Pago efectivo bolívares FTN
                                    @endif

                                    @if(get_class($movimiento) == 'compras\ContPagoBolivaresFAU')
                                        Pago efectivo bolívares FAU
                                    @endif

                                    @if(get_class($movimiento) == 'compras\ContPagoBolivaresFLL')
                                        Pago efectivo bolívares FLL
                                    @endif

                                    @if(get_class($movimiento) == 'compras\ContPagoBolivaresFM')
                                        Pago efectivo bolívares FM
                                    @endif

                                    @if(get_class($movimiento) == 'compras\ContPagoBolivaresFEC')
                                        Pago efectivo bolívares FEC
                                    @endif

                                    @if(get_class($movimiento) == 'compras\ContPagoBolivaresFLF')
                                        Pago efectivo bolívares FLF
                                    @endif

                                    @if(get_class($movimiento) == 'compras\ContPagoBolivaresCDD')
                                        Pago efectivo bolívares CDD
                                    @endif

                                    @if(get_class($movimiento) == 'compras\ContPagoBolivaresPAG')
                                        Pago efectivo bolívares PAG
                                    @endif
                                @endif
                            @endif

                            <br>

                            Operador: {{ ($movimiento->user) ? $movimiento->user : $movimiento->operador }}<br>

                            Fecha y hora: {{ $movimiento->created_at->format('d/m/Y h:i A') }}</br>
                        </p>
                    @else
                        <p class="text-white">No hay movimientos</p>
                    @endif
                </p>
            </div>
        </div>
    </div>
@endif

<!-- CONTACTO -->
<hr class="row align-items-start col-12">
    <div class="card-deck">
        <div class="card border-info">
        <div class="card-body text-left bg-info">
            <h2 class="card-title">
                <span class="card-text text-warning">
                    <i class="far fa-lightbulb CP-beep"></i>
                </span>
                <span class="card-text text-white">
                    Tienes una idea.?
                </span>
            </h2>
            <div class="text-center">
                <div class="text-center" style="display: inline-block; vertical-align: middle;">
                    <h3 class="card-text text-white"><i class="far fa-keyboard"></i></h3>
                    <h5 class="card-text text-white">Redacta tu idea</h5>
                </div>
                <div class="text-center" style="display: inline-block; vertical-align: middle;">
                    <h3 class="card-text text-white"><i class="fas fa-angle-double-right"><br/><br/></i>
                    </h3>
                </div>
                <div class="text-center" style="display: inline-block; vertical-align: middle;">
                    <h3 class="card-text text-white"><i class="far fa-envelope"></i></h3>
                    <h5 class="card-text text-white">Enviala a scova@farmacia72.com.ve</h5>
                </div>
                <div class="text-center" style="display: inline-block; vertical-align: middle;">
                    <h3 class="card-text text-white"><i class="fas fa-angle-double-right"><br/><br/></i></h3>
                </div>
                    <div class="text-center" style="display: inline-block; vertical-align: middle;">
                    <h3 class="card-text text-white"><i class="far fa-clock"></i></h3>
                    <h5 class="card-text text-white">Espera nuestro contacto</h5>
                </div>
            </div>
            </div>
        </div>
    </div>
<!-- CONTACTO -->
@endsection
