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
        <div class="card border-danger mb-3" style="width: 14rem;">
            <div class="card-body text-left bg-danger">
                <h2 class="card-title">
                    <span class="card-text text-white">
                        <i class="fa fa-ban"></i>
                        Última deuda
                    </span>
                </h2>
                <p class="card-text text-white">
                    <p class="text-white">
                        Proveedor: {{ $deuda->proveedor->nombre_proveedor }}</br>
                        Monto: {{ $deuda->signo_moneda }} {{ number_format($deuda->monto, 2) }}</br>
                        Número de documento: {{ $deuda->numero_documento }}<br>
                        Fecha y hora: {{ $deuda->created_at->format('d/m/Y h:i A') }}</br>
                        Creado por: {{ $deuda->usuario_registro }}
                    </p>
                </p>
            </div>
            <div class="card-footer bg-transparent border-danger text-right">
                <a href="" class="btn btn-outline-danger btn-sm">Visualizar</a>
            </div>
        </div>

        <div class="card border-success mb-3" style="width: 14rem;">
            <div class="card-body text-left bg-success">
                <h2 class="card-title">
                    <span class="card-text text-white">
                        <i class="fa fa-ban"></i>
                        Última reclamo
                    </span>
                </h2>
                <p class="card-text text-white">
                    <p class="text-white">
                        Proveedor: {{ $reclamo->proveedor->nombre_proveedor }}</br>
                        Monto: {{ $reclamo->signo_moneda }} {{ number_format($reclamo->monto, 2) }}</br>
                        Número de documento: {{ $reclamo->numero_documento }}<br>
                        Fecha y hora: {{ $reclamo->created_at->format('d/m/Y h:i A') }}</br>
                        Creado por: {{ $reclamo->usuario_registro }}
                    </p>
                </p>
            </div>
            <div class="card-footer bg-transparent border-success text-right">
                <a href="" class="btn btn-outline-success btn-sm">Visualizar</a>
            </div>
        </div>
    </div>
@endif

@if (Auth::user()->departamento == 'ADMINISTRACION')
    <div class="card-deck">
        <div class="card border-danger mb-3" style="width: 14rem;">
            <div class="card-body text-left bg-danger">
                <h2 class="card-title">
                    <span class="card-text text-white">
                        <i class="fas fa-balance-scale"></i>
                        Saldo disponible FTN: ${{ number_format($saldoDolaresFTN, 2, ',', '.') }}
                    </span>
                </h2>
                <p class="card-text text-white"></p>
            </div>
            <div class="card-footer bg-transparent border-danger text-right">
                <a href="/efectivoFTN" class="btn btn-outline-danger btn-sm">Visualizar</a>
            </div>
        </div>

        <div class="card border-success mb-3" style="width: 14rem;">
            <div class="card-body text-left bg-success">
                <h2 class="card-title">
                    <span class="card-text text-white">
                        <i class="fas fa-balance-scale"></i>
                        Saldo disponible FAU: ${{ number_format($saldoDolaresFAU, 2, ',', '.') }}
                    </span>
                </h2>
                <p class="card-text text-white"></p>
            </div>
            <div class="card-footer bg-transparent border-success text-right">
                <a href="/efectivoFAU" class="btn btn-outline-success btn-sm">Visualizar</a>
            </div>
        </div>

        <div class="card border-info mb-3" style="width: 14rem;">
            <div class="card-body text-left bg-info">
                <h2 class="card-title">
                    <span class="card-text text-white">
                        <i class="fas fa-balance-scale"></i>
                        Saldo disponible FLL: ${{ number_format($saldoDolaresFLL, 2, ',', '.') }}
                    </span>
                </h2>
                <p class="card-text text-white"></p>
            </div>
            <div class="card-footer bg-transparent border-info text-right">
                <a href="/efectivoFLL" class="btn btn-outline-info btn-sm">Visualizar</a>
            </div>
        </div>
    </div>

    <div class="card-deck">
        <div class="card border-warning mb-3" style="width: 14rem;">
            <div class="card-body text-left bg-warning">
                <h2 class="card-title">
                    <span class="card-text text-white">
                        <i class="fas fa-balance-scale"></i>
                        Saldo disponible FTN: Bs.S. {{ number_format($saldoBolivaresFTN, 2, ',', '.') }}
                    </span>
                </h2>
                <p class="card-text text-white"></p>
            </div>
            <div class="card-footer bg-transparent border-warning text-right">
                <a href="/bolivaresFTN" class="btn btn-outline-warning btn-sm">Visualizar</a>
            </div>
        </div>

        <div class="card border-secondary mb-3" style="width: 14rem;">
            <div class="card-body text-left bg-secondary">
                <h2 class="card-title">
                    <span class="card-text text-white">
                        <i class="fas fa-balance-scale"></i>
                        Saldo disponible FAU: Bs.S. {{ number_format($saldoBolivaresFAU, 2, ',', '.') }}
                    </span>
                </h2>
                <p class="card-text text-white"></p>
            </div>
            <div class="card-footer bg-transparent border-secondary text-right">
                <a href="/bolivaresFAU" class="btn btn-outline-secondary btn-sm">Visualizar</a>
            </div>
        </div>

        <div class="card border-dark mb-3" style="width: 14rem;">
            <div class="card-body text-left bg-dark">
                <h2 class="card-title">
                    <span class="card-text text-white">
                        <i class="fas fa-balance-scale"></i>
                        Saldo disponible FLL: Bs.S. {{ number_format($saldoBolivaresFLL, 2, ',', '.') }}
                    </span>
                </h2>
                <p class="card-text text-white"></p>
            </div>
            <div class="card-footer bg-transparent border-dark text-right">
                <a href="/bolivaresFLL" class="btn btn-outline-dark btn-sm">Visualizar</a>
            </div>
        </div>
    </div>

    <div class="card-deck">
        <div class="card border-danger mb-3" style="width: 14rem;">
            <div class="card-body text-left bg-danger">
                <h2 class="card-title">
                    <span class="card-text text-white">
                        <i class="fas fa-balance-scale"></i>
                        Diferido en $ FTN: {{ number_format($diferidoDolaresFTN, 2, ',', '.') }}
                    </span>
                </h2>
                <p class="card-text text-white"></p>
            </div>
            <div class="card-footer bg-transparent border-danger text-right">
                <a href="/contabilidad/diferidosFTN" class="btn btn-outline-danger btn-sm">Visualizar</a>
            </div>
        </div>

        <div class="card border-success mb-3" style="width: 14rem;">
            <div class="card-body text-left bg-success">
                <h2 class="card-title">
                    <span class="card-text text-white">
                        <i class="fas fa-balance-scale"></i>
                        Diferido en $ FAU: {{ number_format($diferidoDolaresFAU, 2, ',', '.') }}
                    </span>
                </h2>
                <p class="card-text text-white"></p>
            </div>
            <div class="card-footer bg-transparent border-success text-right">
                <a href="/contabilidad/diferidosFAU" class="btn btn-outline-success btn-sm">Visualizar</a>
            </div>
        </div>

        <div class="card border-info mb-3" style="width: 14rem;">
            <div class="card-body text-left bg-info">
                <h2 class="card-title">
                    <span class="card-text text-white">
                        <i class="fas fa-balance-scale"></i>
                        Diferido en $ FLL: {{ number_format($diferidoDolaresFLL, 2, ',', '.') }}
                    </span>
                </h2>
                <p class="card-text text-white"></p>
            </div>
            <div class="card-footer bg-transparent border-info text-right">
                <a href="/contabilidad/diferidosFLL" class="btn btn-outline-info btn-sm">Visualizar</a>
            </div>
        </div>
    </div>

    <div class="card-deck">
        <div class="card border-warning mb-3" style="width: 14rem;">
            <div class="card-body text-left bg-warning">
                <h2 class="card-title">
                    <span class="card-text text-white">
                        <i class="fas fa-balance-scale"></i>
                        Diferido en Bs.S. FTN: {{ number_format($diferidoBolivaresFTN, 2, ',', '.') }}
                    </span>
                </h2>
                <p class="card-text text-white"></p>
            </div>
            <div class="card-footer bg-transparent border-warning text-right">
                <a href="/contabilidad/diferidosBolivaresFTN" class="btn btn-outline-warning btn-sm">Visualizar</a>
            </div>
        </div>

        <div class="card border-secondary mb-3" style="width: 14rem;">
            <div class="card-body text-left bg-secondary">
                <h2 class="card-title">
                    <span class="card-text text-white">
                        <i class="fas fa-balance-scale"></i>
                        Diferido en Bs.S. FAU: {{ number_format($diferidoBolivaresFAU, 2, ',', '.') }}
                    </span>
                </h2>
                <p class="card-text text-white"></p>
            </div>
            <div class="card-footer bg-transparent border-secondary text-right">
                <a href="/contabilidad/diferidosBolivaresFAU" class="btn btn-outline-secondary btn-sm">Visualizar</a>
            </div>
        </div>

        <div class="card border-dark mb-3" style="width: 14rem;">
            <div class="card-body text-left bg-dark">
                <h2 class="card-title">
                    <span class="card-text text-white">
                        <i class="fas fa-balance-scale"></i>
                        Diferido en Bs.S. FLL: {{ number_format($diferidoBolivaresFLL, 2, ',', '.') }}
                    </span>
                </h2>
                <p class="card-text text-white"></p>
            </div>
            <div class="card-footer bg-transparent border-dark text-right">
                <a href="/contabilidad/diferidosBolivaresFLL" class="btn btn-outline-dark btn-sm">Visualizar</a>
            </div>
        </div>
    </div>

    <div class="card-deck">
        <div class="card border-danger mb-3" style="width: 14rem;">
            <div class="card-body text-left bg-danger">
                <h2 class="card-title">
                    <span class="card-text text-white">
                        <i class="fas fa-money-bill"></i>
                        Último pago
                    </span>
                </h2>
                <p class="card-text text-white">
                    <p class="text-white">
                        @if($pago->proveedor)
                            Nombre del proveedor: {{ $pago->proveedor->nombre_proveedor }}

                        @elseif($pago->concepto)
                            Concepto: {{ $pago->concepto }}

                        @else($pago->proveedor)
                            Comentario: {{ $pago->comentario }}
                        @endif
                        <br>

                        Monto: {{ $pago->signo_moneda }}</br>

                        Emisor: BOD FLL<br>

                        Operador: Giordany Prieto<br>

                        Fecha y hora: 2021-06-30 09:41:58</br>
                    </p>
                </p>
            </div>
            <div class="card-footer bg-transparent border-danger text-right">
                <a href="" class="btn btn-outline-danger btn-sm">Visualizar</a>
            </div>
        </div>

        <div class="card border-success mb-3" style="width: 14rem;">
            <div class="card-body text-left bg-success">
                <h2 class="card-title">
                    <span class="card-text text-white">
                        <i class="fa fa-ban"></i>
                        Última deuda
                    </span>
                </h2>
                <p class="card-text text-white">
                    <p class="text-white">
                        Proveedor: miguel tovar</br>
                        Monto: Bs. 20.000.000,00</br>
                        Emisor: BOD FLL<br>
                        Conciliado por: Giordany Prieto<br>
                        Fecha y hora: 2021-06-30 09:41:58</br>
                    </p>
                </p>
            </div>
            <div class="card-footer bg-transparent border-success text-right">
                <a href="" class="btn btn-outline-success btn-sm">Visualizar</a>
            </div>
        </div>
    </div>
@endif

@if (Auth::user()->departamento == 'CONTABILIDAD')
    <div class="card-deck">
        <div class="card border-warning mb-3" style="width: 14rem;">
            <div class="card-body text-left bg-warning">
                <h2 class="card-title">
                    <span class="card-text text-white">
                        <i class="fas fa-money-bill"></i>
                        Último pago
                    </span>
                </h2>
                <p class="card-text text-white">
                    <p class="text-white">
                        Proveedor: miguel tovar</br>
                        Monto: Bs. 20.000.000,00</br>
                        Emisor: BOD FLL<br>
                        Operador: Giordany Prieto<br>
                        Fecha y hora: 2021-06-30 09:41:58</br>
                    </p>
                </p>
            </div>
            <div class="card-footer bg-transparent border-warning text-right">
                <a href="" class="btn btn-outline-warning btn-sm">Visualizar</a>
            </div>
        </div>

        <div class="card border-dark mb-3" style="width: 14rem;">
            <div class="card-body text-left bg-dark">
                <h2 class="card-title">
                    <span class="card-text text-white">
                        <i class="fas fa-check-double"></i>
                        Última conciliación
                    </span>
                </h2>
                <p class="card-text text-white">
                    <p class="text-white">
                        Proveedor: miguel tovar</br>
                        Monto: Bs. 20.000.000,00</br>
                        Emisor: BOD FLL<br>
                        Conciliado por: Giordany Prieto<br>
                        Fecha y hora: 2021-06-30 09:41:58</br>
                    </p>
                </p>
            </div>
            <div class="card-footer bg-transparent border-dark text-right">
                <a href="" class="btn btn-outline-dark btn-sm">Visualizar</a>
            </div>
        </div>

        <div class="card border-success mb-3" style="width: 14rem;">
            <div class="card-body text-left bg-success">
                <h2 class="card-title">
                    <span class="card-text text-white">
                        <i class="fa fa-ban"></i>
                        Última deuda
                    </span>
                </h2>
                <p class="card-text text-white">
                    <p class="text-white">
                        Proveedor: miguel tovar</br>
                        Monto: Bs. 20.000.000,00</br>
                        Emisor: BOD FLL<br>
                        Conciliado por: Giordany Prieto<br>
                        Fecha y hora: 2021-06-30 09:41:58</br>
                    </p>
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
        <div class="card border-danger mb-3" style="width: 14rem;">
            <div class="card-body text-left bg-danger">
                <h2 class="card-title">
                    <span class="card-text text-white">
                        <i class="fas fa-balance-scale"></i>
                        Saldo disponible: $8.148,83
                    </span>
                </h2>
                <p class="card-text text-white"></p>
            </div>
            <div class="card-footer bg-transparent border-danger text-right">
                <a href="/efectivo" class="btn btn-outline-danger btn-sm">Visualizar</a>
            </div>
        </div>

        <div class="card border-success mb-3" style="width: 14rem;">
            <div class="card-body text-left bg-success">
                <h2 class="card-title">
                    <span class="card-text text-white">
                        <i class="fas fa-balance-scale"></i>
                        Saldo disponible: Bs.S. 8.148,83
                    </span>
                </h2>
                <p class="card-text text-white"></p>
            </div>
            <div class="card-footer bg-transparent border-success text-right">
                <a href="/efectivo" class="btn btn-outline-success btn-sm">Visualizar</a>
            </div>
        </div>
    </div>

    <div class="card-deck">
        <div class="card border-info mb-3" style="width: 14rem;">
            <div class="card-body text-left bg-info">
                <h2 class="card-title">
                    <span class="card-text text-white">
                        <i class="fas fa-balance-scale"></i>
                        Diferido: $8.148,83
                    </span>
                </h2>
                <p class="card-text text-white"></p>
            </div>
            <div class="card-footer bg-transparent border-info text-right">
                <a href="/contabilidad/diferidos" class="btn btn-outline-info btn-sm">Visualizar</a>
            </div>
        </div>

        <div class="card border-warning mb-3" style="width: 14rem;">
            <div class="card-body text-left bg-warning">
                <h2 class="card-title">
                    <span class="card-text text-white">
                        <i class="fas fa-balance-scale"></i>
                        Diferido: Bs.S. 8.148,83
                    </span>
                </h2>
                <p class="card-text text-white"></p>
            </div>
            <div class="card-footer bg-transparent border-warning text-right">
                <a href="/contabilidad/diferidos" class="btn btn-outline-warning btn-sm">Visualizar</a>
            </div>
        </div>
    </div>

    <div class="card-deck">
        <div class="card border-secondary mb-3" style="width: 14rem;">
            <div class="card-body text-left bg-secondary">
                <h2 class="card-title">
                    <span class="card-text text-white">
                        <i class="fas fa-money-bill"></i>
                        Último movimiento
                    </span>
                </h2>
                <p class="card-text text-white">
                    <p class="text-white">
                        Tipo: Pago</br>
                        Proveedor: miguel tovar</br>
                        Monto: Bs. 20.000.000,00</br>
                        Emisor: BOD FLL<br>
                        Operador: Giordany Prieto<br>
                        Fecha y hora: 2021-06-30 09:41:58</br>
                    </p>
                </p>
            </div>
            <div class="card-footer bg-transparent border-secondary text-right">
                <a href="" class="btn btn-outline-secondary btn-sm">Visualizar</a>
            </div>
        </div>
    </div>
@endif

@if (Auth::user()->departamento == 'TECNOLOGIA' || Auth::user()->departamento == 'GERENCIA')
    <div class="card-deck">
        <div class="card border-danger mb-3" style="width: 14rem;">
            <div class="card-body text-left bg-danger">
                <h2 class="card-title">
                    <span class="card-text text-white">
                        <i class="fas fa-balance-scale"></i>
                        Saldo disponible FTN: $1.485,38
                    </span>
                </h2>
                <p class="card-text text-white"></p>
            </div>
            <div class="card-footer bg-transparent border-danger text-right">
                <a href="/efectivo?sede=FTN&moneda=$" class="btn btn-outline-danger btn-sm">Visualizar</a>
            </div>
        </div>

        <div class="card border-success mb-3" style="width: 14rem;">
            <div class="card-body text-left bg-success">
                <h2 class="card-title">
                    <span class="card-text text-white">
                        <i class="fas fa-balance-scale"></i>
                        Saldo disponible FAU: $8.148,83
                    </span>
                </h2>
                <p class="card-text text-white"></p>
            </div>
            <div class="card-footer bg-transparent border-success text-right">
                <a href="/efectivo?sede=FAU&moneda=$" class="btn btn-outline-success btn-sm">Visualizar</a>
            </div>
        </div>

        <div class="card border-info mb-3" style="width: 14rem;">
            <div class="card-body text-left bg-info">
                <h2 class="card-title">
                    <span class="card-text text-white">
                        <i class="fas fa-balance-scale"></i>
                        Saldo disponible FLL: $2.541,45
                    </span>
                </h2>
                <p class="card-text text-white"></p>
            </div>
            <div class="card-footer bg-transparent border-info text-right">
                <a href="/efectivo?sede=FLL&moneda=$" class="btn btn-outline-info btn-sm">Visualizar</a>
            </div>
        </div>
    </div>

    <div class="card-deck">
        <div class="card border-warning mb-3" style="width: 14rem;">
            <div class="card-body text-left bg-warning">
                <h2 class="card-title">
                    <span class="card-text text-white">
                        <i class="fas fa-balance-scale"></i>
                        Saldo disponible FTN: Bs.S. 1.485,38
                    </span>
                </h2>
                <p class="card-text text-white"></p>
            </div>
            <div class="card-footer bg-transparent border-warning text-right">
                <a href="/efectivo?sede=FTN&moneda=bs" class="btn btn-outline-warning btn-sm">Visualizar</a>
            </div>
        </div>

        <div class="card border-secondary mb-3" style="width: 14rem;">
            <div class="card-body text-left bg-secondary">
                <h2 class="card-title">
                    <span class="card-text text-white">
                        <i class="fas fa-balance-scale"></i>
                        Saldo disponible FAU: Bs.S. 8.148,83
                    </span>
                </h2>
                <p class="card-text text-white"></p>
            </div>
            <div class="card-footer bg-transparent border-secondary text-right">
                <a href="/efectivo?sede=FAU&moneda=bs" class="btn btn-outline-secondary btn-sm">Visualizar</a>
            </div>
        </div>

        <div class="card border-dark mb-3" style="width: 14rem;">
            <div class="card-body text-left bg-dark">
                <h2 class="card-title">
                    <span class="card-text text-white">
                        <i class="fas fa-balance-scale"></i>
                        Saldo disponible FLL: Bs.S. 2.541,45
                    </span>
                </h2>
                <p class="card-text text-white"></p>
            </div>
            <div class="card-footer bg-transparent border-dark text-right">
                <a href="/efectivo?sede=FLL&moneda=bs" class="btn btn-outline-dark btn-sm">Visualizar</a>
            </div>
        </div>
    </div>

    <div class="card-deck">
        <div class="card border-danger mb-3" style="width: 14rem;">
            <div class="card-body text-left bg-danger">
                <h2 class="card-title">
                    <span class="card-text text-white">
                        <i class="fas fa-balance-scale"></i>
                        Diferido en $ FTN: 1.485,38
                    </span>
                </h2>
                <p class="card-text text-white"></p>
            </div>
            <div class="card-footer bg-transparent border-danger text-right">
                <a href="/contabilidad/diferidos?sede=FTN&moneda=$" class="btn btn-outline-danger btn-sm">Visualizar</a>
            </div>
        </div>

        <div class="card border-success mb-3" style="width: 14rem;">
            <div class="card-body text-left bg-success">
                <h2 class="card-title">
                    <span class="card-text text-white">
                        <i class="fas fa-balance-scale"></i>
                        Diferido en $ FAU: 8.148,83
                    </span>
                </h2>
                <p class="card-text text-white"></p>
            </div>
            <div class="card-footer bg-transparent border-success text-right">
                <a href="/contabilidad/diferidos?sede=FAU&moneda=$" class="btn btn-outline-success btn-sm">Visualizar</a>
            </div>
        </div>

        <div class="card border-info mb-3" style="width: 14rem;">
            <div class="card-body text-left bg-info">
                <h2 class="card-title">
                    <span class="card-text text-white">
                        <i class="fas fa-balance-scale"></i>
                        Diferido en $ FLL: 2.541,45
                    </span>
                </h2>
                <p class="card-text text-white"></p>
            </div>
            <div class="card-footer bg-transparent border-info text-right">
                <a href="/contabilidad/diferidos?sede=FLL&moneda=$" class="btn btn-outline-info btn-sm">Visualizar</a>
            </div>
        </div>
    </div>

    <div class="card-deck">
        <div class="card border-warning mb-3" style="width: 14rem;">
            <div class="card-body text-left bg-warning">
                <h2 class="card-title">
                    <span class="card-text text-white">
                        <i class="fas fa-balance-scale"></i>
                        Diferido en Bs.S. FTN: 1.485,38
                    </span>
                </h2>
                <p class="card-text text-white"></p>
            </div>
            <div class="card-footer bg-transparent border-warning text-right">
                <a href="/diferidos?sede=FTN&moneda=bs" class="btn btn-outline-warning btn-sm">Visualizar</a>
            </div>
        </div>

        <div class="card border-secondary mb-3" style="width: 14rem;">
            <div class="card-body text-left bg-secondary">
                <h2 class="card-title">
                    <span class="card-text text-white">
                        <i class="fas fa-balance-scale"></i>
                        Diferido en Bs.S. FAU: 8.148,83
                    </span>
                </h2>
                <p class="card-text text-white"></p>
            </div>
            <div class="card-footer bg-transparent border-secondary text-right">
                <a href="/diferidos?sede=FAU&moneda=bs" class="btn btn-outline-secondary btn-sm">Visualizar</a>
            </div>
        </div>

        <div class="card border-dark mb-3" style="width: 14rem;">
            <div class="card-body text-left bg-dark">
                <h2 class="card-title">
                    <span class="card-text text-white">
                        <i class="fas fa-balance-scale"></i>
                        Diferido en Bs.S. FLL: 2.541,45
                    </span>
                </h2>
                <p class="card-text text-white"></p>
            </div>
            <div class="card-footer bg-transparent border-dark text-right">
                <a href="/diferidos?sede=FLL&moneda=bs" class="btn btn-outline-dark btn-sm">Visualizar</a>
            </div>
        </div>
    </div>

    <div class="card-deck">
        <div class="card border-warning mb-3" style="width: 14rem;">
            <div class="card-body text-left bg-warning">
                <h2 class="card-title">
                    <span class="card-text text-white">
                        <i class="fas fa-money-bill"></i>
                        Último pago
                    </span>
                </h2>
                <p class="card-text text-white">
                    <p class="text-white">
                        Proveedor: miguel tovar</br>
                        Monto: Bs. 20.000.000,00</br>
                        Emisor: BOD FLL<br>
                        Operador: Giordany Prieto<br>
                        Fecha y hora: 2021-06-30 09:41:58</br>
                    </p>
                </p>
            </div>
            <div class="card-footer bg-transparent border-warning text-right">
                <a href="" class="btn btn-outline-warning btn-sm">Visualizar</a>
            </div>
        </div>

        <div class="card border-dark mb-3" style="width: 14rem;">
            <div class="card-body text-left bg-dark">
                <h2 class="card-title">
                    <span class="card-text text-white">
                        <i class="fas fa-check-double"></i>
                        Última conciliación
                    </span>
                </h2>
                <p class="card-text text-white">
                    <p class="text-white">
                        Proveedor: miguel tovar</br>
                        Monto: Bs. 20.000.000,00</br>
                        Emisor: BOD FLL<br>
                        Conciliado por: Giordany Prieto<br>
                        Fecha y hora: 2021-06-30 09:41:58</br>
                    </p>
                </p>
            </div>
            <div class="card-footer bg-transparent border-dark text-right">
                <a href="" class="btn btn-outline-dark btn-sm">Visualizar</a>
            </div>
        </div>

        <div class="card border-secondary mb-3" style="width: 14rem;">
            <div class="card-body text-left bg-secondary">
                <h2 class="card-title">
                    <span class="card-text text-white">
                        <i class="fa fa-ban"></i>
                        Última deuda
                    </span>
                </h2>
                <p class="card-text text-white">
                    <p class="text-white">
                        Proveedor: miguel tovar</br>
                        Monto: Bs. 20.000.000,00</br>
                        Emisor: BOD FLL<br>
                        Conciliado por: Giordany Prieto<br>
                        Fecha y hora: 2021-06-30 09:41:58</br>
                    </p>
                </p>
            </div>
            <div class="card-footer bg-transparent border-secondary text-right">
                <a href="" class="btn btn-outline-secondary btn-sm">Visualizar</a>
            </div>
        </div>
    </div>
@endif

<!-- CONTACTO -->
<hr class="row align-items-start col-12">
    <div class="card-deck">
        <div class="card border-info" style="width: 14rem;">
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
