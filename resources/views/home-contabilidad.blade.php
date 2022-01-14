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
    </div>

    <div class="card-deck">
        <div class="card border-warning mb-3">
            <div class="card-body text-left bg-warning">
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
            <div class="card-footer bg-transparent border-warning text-right">
                <a href="/bolivaresFTN" class="btn btn-outline-warning btn-sm">Visualizar</a>
            </div>
        </div>

        <div class="card border-secondary mb-3">
            <div class="card-body text-left bg-secondary">
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
            <div class="card-footer bg-transparent border-secondary text-right">
                <a href="/bolivaresFAU" class="btn btn-outline-secondary btn-sm">Visualizar</a>
            </div>
        </div>

        <div class="card border-dark mb-3">
            <div class="card-body text-left bg-dark">
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
            <div class="card-footer bg-transparent border-dark text-right">
                <a href="/bolivaresFLL" class="btn btn-outline-dark btn-sm">Visualizar</a>
            </div>
        </div>
    </div>

    <div class="card-deck">
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
    </div>

    <div class="card-deck">
        <div class="card border-warning mb-3">
            <div class="card-body text-left bg-warning">
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
            <div class="card-footer bg-transparent border-warning text-right">
                <a href="/contabilidad/diferidosBolivaresFTN" class="btn btn-outline-warning btn-sm">Visualizar</a>
            </div>
        </div>

        <div class="card border-secondary mb-3">
            <div class="card-body text-left bg-secondary">
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
            <div class="card-footer bg-transparent border-secondary text-right">
                <a href="/contabilidad/diferidosBolivaresFAU" class="btn btn-outline-secondary btn-sm">Visualizar</a>
            </div>
        </div>

        <div class="card border-dark mb-3">
            <div class="card-body text-left bg-dark">
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
            <div class="card-footer bg-transparent border-dark text-right">
                <a href="/contabilidad/diferidosBolivaresFLL" class="btn btn-outline-dark btn-sm">Visualizar</a>
            </div>
        </div>
    </div>

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
                                @if(get_class($pago) == 'compras\ContPagoEfectivoFTN')
                                    Pago dólares efectivo FTN
                                @endif

                                @if(get_class($pago) == 'compras\ContPagoEfectivoFAU')
                                    Pago dólares efectivo FAU
                                @endif

                                @if(get_class($pago) == 'compras\ContPagoEfectivoFLL')
                                    Pago dólares efectivo FLL
                                @endif

                                @if(get_class($pago) == 'compras\ContPagoBolivaresFTN')
                                    Pago efectivo bolívares FTN
                                @endif

                                @if(get_class($pago) == 'compras\ContPagoBolivaresFAU')
                                    Pago efectivo bolívares FAU
                                @endif

                                @if(get_class($pago) == 'compras\ContPagoBolivaresFLL')
                                    Pago efectivo bolívares FLL
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
                                @if(get_class($pago) == 'compras\ContPagoEfectivoFTN')
                                    Pago dólares efectivo FTN
                                @endif

                                @if(get_class($pago) == 'compras\ContPagoEfectivoFAU')
                                    Pago dólares efectivo FAU
                                @endif

                                @if(get_class($pago) == 'compras\ContPagoEfectivoFLL')
                                    Pago dólares efectivo FLL
                                @endif

                                @if(get_class($pago) == 'compras\ContPagoBolivaresFTN')
                                    Pago bolívares efectivo FTN
                                @endif

                                @if(get_class($pago) == 'compras\ContPagoBolivaresFAU')
                                    Pago bolívares efectivo FAU
                                @endif

                                @if(get_class($pago) == 'compras\ContPagoBolivaresFLL')
                                    Pago bolívares efectivo FLL
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
                            @if(get_class($conciliacion) == 'compras\ContPagoEfectivoFTN')
                                Pago dólares efectivo FTN
                            @endif

                            @if(get_class($conciliacion) == 'compras\ContPagoEfectivoFAU')
                                Pago dólares efectivo FAU
                            @endif

                            @if(get_class($conciliacion) == 'compras\ContPagoEfectivoFLL')
                                Pago dólares efectivo FLL
                            @endif

                            @if(get_class($conciliacion) == 'compras\ContPagoBolivaresFTN')
                                Pago bolívares efectivo FTN
                            @endif

                            @if(get_class($conciliacion) == 'compras\ContPagoBolivaresFAU')
                                Pago bolívares efectivo FAU
                            @endif

                            @if(get_class($conciliacion) == 'compras\ContPagoBolivaresFLL')
                                Pago bolívares efectivo FLL
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
                            @if(get_class($movimiento) == 'compras\ContPagoEfectivoFTN')
                                Ingreso dólares efectivo FTN
                            @endif

                            @if(get_class($movimiento) == 'compras\ContPagoEfectivoFAU')
                                Ingreso dólares efectivo FAU
                            @endif

                            @if(get_class($movimiento) == 'compras\ContPagoEfectivoFLL')
                                Ingreso dólares efectivo FLL
                            @endif

                            @if(get_class($movimiento) == 'compras\ContPagoBolivaresFTN')
                                Ingreso efectivo bolívares FTN
                            @endif

                            @if(get_class($movimiento) == 'compras\ContPagoBolivaresFAU')
                                Ingreso efectivo bolívares FAU
                            @endif

                            @if(get_class($movimiento) == 'compras\ContPagoBolivaresFLL')
                                Ingreso efectivo bolívares FLL
                            @endif
                        @else
                            @if($movimiento->banco)
                                {{ $movimiento->banco->alias_cuenta }}
                            @else
                                @if(get_class($movimiento) == 'compras\ContPagoEfectivoFTN')
                                    Pago dólares efectivo FTN
                                @endif

                                @if(get_class($movimiento) == 'compras\ContPagoEfectivoFAU')
                                    Pago dólares efectivo FAU
                                @endif

                                @if(get_class($movimiento) == 'compras\ContPagoEfectivoFLL')
                                    Pago dólares efectivo FLL
                                @endif

                                @if(get_class($movimiento) == 'compras\ContPagoBolivaresFTN')
                                    Pago efectivo bolívares FTN
                                @endif

                                @if(get_class($movimiento) == 'compras\ContPagoBolivaresFAU')
                                    Pago efectivo bolívares FAU
                                @endif

                                @if(get_class($movimiento) == 'compras\ContPagoBolivaresFLL')
                                    Pago efectivo bolívares FLL
                                @endif
                            @endif
                        @endif

                        <br>

                        Operador: {{ ($movimiento->user) ? $movimiento->user : $movimiento->operador }}<br>

                        Fecha y hora: {{ $movimiento->created_at->format('d/m/Y h:i A') }}</br>
                    </p>
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
