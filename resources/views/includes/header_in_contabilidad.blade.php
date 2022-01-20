<!-- NavBar / Barra Navegacion -->
<nav class="navbar navbar-expand-lg bg-white text-info" style="position: relative;">
  <!-- Navbar brand -->
  <a class="navbar-brand text-info CP-title-NavBar bg-white" href="{{ url('/') }}" style="margin-right: 50px;"><b><i class="fas fa-syringe text-success"></i>CPharma</b>
  </a>
<div style="position: absolute; right:0%">
<!-------------------------------------------------------------------------------->
<!-- DASHBOARD -->
  <li class="navbar-brand">
    <a class="btn btn-outline-info textoN" href="{{ url('/home') }}" role="button">
      <i class="fas fa-columns"></i> Dashboard
    </a>
  </li>
<!-- DASHBOARD -->
<!-------------------------------------------------------------------------------->
<!-- AGENDA -->
  <div class="btn-group navbar-brand">
    <button type="button" class="btn btn-outline-info dropdown-toggle textoI" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
      <i class="fas fa-book"></i> Agenda
    </button>
    <div class="dropdown-menu">
      <li class="nav-item">
        <a class="nav-link CP-Links-Menu" href="{{ url('/empresa') }}">
          <span data-feather="home"></span>
          <i class="fas fa-industry"></i>
          Empresa <span class="sr-only">(current)</span>
        </a>
      </li>
      <li class="nav-item">
        <a class="nav-link CP-Links-Menu" href="{{ url('/proveedor') }}">
          <span data-feather="home"></span>
          <i class="fas fa-dolly"></i>
          Proveedor <span class="sr-only">(current)</span>
        </a>
      </li>
    </div>
  </div>
<!-- AGENDA -->
<!-------------------------------------------------------------------------------->
  <div class="btn-group navbar-brand">
    <button type="button" class="btn btn-outline-info dropdown-toggle textoI" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
      <i class="fas fa-user-cog"></i> {{ ucfirst(strtolower(Auth()->user()->departamento)) }}
    </button>
    <div class="dropdown-menu">
      @if(Auth::user()->departamento == 'ADMINISTRACION' || Auth::user()->departamento == 'TECNOLOGIA' || Auth::user()->departamento == 'GERENCIA' || Auth::user()->departamento == 'OPERACIONES')
          <li class="nav-item">
            <a class="nav-link CP-Links-Menu" href="{{ url('/proveedores') }}">
              <span data-feather="home"></span>
              <i class="fa fa-users"></i>
              Proveedores<span class="sr-only">(current)</span>
            </a>
          </li>
      @endif

      @if(Auth::user()->departamento == 'TECNOLOGIA' || Auth::user()->departamento == 'GERENCIA')
          <li class="nav-item">
            <a class="nav-link CP-Links-Menu" href="{{ url('/bancos') }}">
              <span data-feather="home"></span>
              <i class="fas fa-university"></i>
              Bancos<span class="sr-only">(current)</span>
            </a>
          </li>
      @endif

      @if(Auth::user()->departamento == 'CONTABILIDAD' || Auth::user()->departamento == 'ADMINISTRACION' || Auth::user()->departamento == 'TECNOLOGIA' || Auth::user()->departamento == 'GERENCIA' || Auth::user()->departamento == 'OPERACIONES')
          <li class="nav-item">
            <a class="nav-link CP-Links-Menu" href="{{ url('/deudas') }}">
              <span data-feather="home"></span>
              <i class="fas fa-info-circle"></i>
              Deudas<span class="sr-only">(current)</span>
            </a>
          </li>
      @endif

      @if(Auth::user()->departamento == 'TECNOLOGIA' || Auth::user()->departamento == 'GERENCIA' || Auth::user()->departamento == 'ADMINISTRACION' || Auth::user()->departamento == 'CONTABILIDAD')
          <li class="nav-item">
            <a class="nav-link CP-Links-Menu" href="{{ url('/pizarra-deudas') }}">
              <span data-feather="home"></span>
              <i class="fas fa-info-circle"></i>
              Pizarra de deudas<span class="sr-only">(current)</span>
            </a>
          </li>
      @endif

      @if(Auth::user()->departamento == 'CONTABILIDAD' || Auth::user()->departamento == 'ADMINISTRACION' || Auth::user()->departamento == 'TECNOLOGIA' || Auth::user()->departamento == 'GERENCIA' || Auth::user()->departamento == 'OPERACIONES')
          <li class="nav-item">
            <a class="nav-link CP-Links-Menu" href="{{ url('/reclamos') }}">
              <span data-feather="home"></span>
              <i class="fas fa-exclamation-triangle"></i>
              Reclamos<span class="sr-only">(current)</span>
            </a>
          </li>
      @endif

      @if(Auth::user()->departamento == 'TECNOLOGIA' || Auth::user()->departamento == 'GERENCIA' || Auth::user()->departamento == 'CONTABILIDAD' || Auth::user()->departamento == 'ADMINISTRACION')
          <li class="nav-item">
            <a class="nav-link CP-Links-Menu" href="{{ url('/ajuste') }}">
              <span data-feather="home"></span>
              <i class="fas fa-sliders-h"></i>
              Ajustes<span class="sr-only">(current)</span>
            </a>
          </li>
      @endif

      @if(Auth::user()->departamento == 'TECNOLOGIA' || Auth::user()->departamento == 'GERENCIA' || Auth::user()->departamento == 'CONTABILIDAD' || Auth::user()->departamento == 'ADMINISTRACION')
          <li class="nav-item">
            <a class="nav-link CP-Links-Menu" href="{{ url('/prepagados') }}">
              <span data-feather="home"></span>
              <i class="fas fa-question"></i>
              Prepagados<span class="sr-only">(current)</span>
            </a>
          </li>

          <li class="nav-item">
            <a class="nav-link CP-Links-Menu" href="{{ url('/bancarios') }}">
              <span data-feather="home"></span>
              <i class="fas fa-credit-card"></i>
              Pagos bancarios<span class="sr-only">(current)</span>
            </a>
          </li>
      @endif

      @if(Auth::user()->departamento == 'TESORERIA' || Auth::user()->departamento == 'TECNOLOGIA' || Auth::user()->departamento == 'GERENCIA' || Auth::user()->departamento == 'CONTABILIDAD' || Auth::user()->departamento == 'ADMINISTRACION')
        @if(Auth::user()->sede == 'GRUPO P, C.A' || Auth::user()->sede == 'FARMACIA TIERRA NEGRA, C.A.')
          <li class="nav-item">
            <a class="nav-link CP-Links-Menu" href="{{ url('/efectivoFTN') }}">
              <span data-feather="home"></span>
              <i class="fas fa-money-bill-alt"></i>
              Pagos en efectivo dólares FTN<span class="sr-only">(current)</span>
            </a>
          </li>
        @endif

        @if(Auth::user()->sede == 'GRUPO P, C.A' || Auth::user()->sede == 'FARMACIA LA LAGO,C.A.')
          <li class="nav-item">
            <a class="nav-link CP-Links-Menu" href="{{ url('/efectivoFLL') }}">
              <span data-feather="home"></span>
              <i class="fas fa-money-bill-alt"></i>
              Pagos en efectivo dólares FLL<span class="sr-only">(current)</span>
            </a>
          </li>
        @endif

        @if(Auth::user()->sede == 'GRUPO P, C.A' || Auth::user()->sede == 'FARMACIA AVENIDA UNIVERSIDAD, C.A.')
          <li class="nav-item">
            <a class="nav-link CP-Links-Menu" href="{{ url('/efectivoFAU') }}">
              <span data-feather="home"></span>
              <i class="fas fa-money-bill-alt"></i>
              Pagos en efectivo dólares FAU<span class="sr-only">(current)</span>
            </a>
          </li>
        @endif

        @if(Auth::user()->sede == 'GRUPO P, C.A' || Auth::user()->sede == 'FARMACIA MILLENNIUM 2000, C.A')
          <li class="nav-item">
            <a class="nav-link CP-Links-Menu" href="{{ url('/efectivoFM') }}">
              <span data-feather="home"></span>
              <i class="fas fa-money-bill-alt"></i>
              Pagos en efectivo dólares FM<span class="sr-only">(current)</span>
            </a>
          </li>
        @endif


        @if(Auth::user()->sede == 'GRUPO P, C.A' || Auth::user()->sede == 'FARMACIA TIERRA NEGRA, C.A.')
          <li class="nav-item">
            <a class="nav-link CP-Links-Menu" href="{{ url('/bolivaresFTN') }}">
              <span data-feather="home"></span>
              <i class="fas fa-money-bill-alt"></i>
              Pagos en efectivo bolívares FTN<span class="sr-only">(current)</span>
            </a>
          </li>
        @endif

        @if(Auth::user()->sede == 'GRUPO P, C.A' || Auth::user()->sede == 'FARMACIA LA LAGO,C.A.')
          <li class="nav-item">
            <a class="nav-link CP-Links-Menu" href="{{ url('/bolivaresFLL') }}">
              <span data-feather="home"></span>
              <i class="fas fa-money-bill-alt"></i>
              Pagos en efectivo bolívares FLL<span class="sr-only">(current)</span>
            </a>
          </li>
        @endif

        @if(Auth::user()->sede == 'GRUPO P, C.A' || Auth::user()->sede == 'FARMACIA AVENIDA UNIVERSIDAD, C.A.')
          <li class="nav-item">
            <a class="nav-link CP-Links-Menu" href="{{ url('/bolivaresFAU') }}">
              <span data-feather="home"></span>
              <i class="fas fa-money-bill-alt"></i>
              Pagos en efectivo bolívares FAU<span class="sr-only">(current)</span>
            </a>
          </li>
        @endif

        @if(Auth::user()->sede == 'GRUPO P, C.A' || Auth::user()->sede == 'FARMACIA MILLENNIUM 2000, C.A')
          <li class="nav-item">
            <a class="nav-link CP-Links-Menu" href="{{ url('/bolivaresFM') }}">
              <span data-feather="home"></span>
              <i class="fas fa-money-bill-alt"></i>
              Pagos en efectivo bolívares FM<span class="sr-only">(current)</span>
            </a>
          </li>
        @endif
      @endif


      @if(Auth::user()->departamento != 'ADMINISTRACION' && Auth::user()->departamento != 'TESORERIA' && Auth::user()->departamento != 'OPERACIONES')
          <li class="nav-item">
            <a class="nav-link CP-Links-Menu" href="{{ url('/cuentas') }}">
              <span data-feather="home"></span>
              <i class="fas fa-network-wired"></i>
              Plan de cuentas<span class="sr-only">(current)</span>
            </a>
          </li>
      @endif

      @if(Auth::user()->departamento != 'ADMINISTRACION' && Auth::user()->departamento != 'TESORERIA' && Auth::user()->departamento != 'OPERACIONES')
          <li class="nav-item">
            <a class="nav-link CP-Links-Menu" href="{{ url('/conciliaciones') }}">
              <span data-feather="home"></span>
              <i class="fas fa-file-invoice-dollar"></i>
              Conciliaciones<span class="sr-only">(current)</span>
            </a>
          </li>
      @endif

      @if(Auth::user()->departamento != 'TESORERIA')
          <li class="nav-item">
            <a class="nav-link CP-Links-Menu" href="{{ url('/reportes') }}">
              <span data-feather="home"></span>
              <i class="fas fa-file-invoice"></i>
              Reportes<span class="sr-only">(current)</span>
            </a>
          </li>
      @endif

      @if(Auth::user()->departamento != 'OPERACIONES')
        @if(Auth::user()->sede == 'GRUPO P, C.A' || Auth::user()->sede == 'FARMACIA TIERRA NEGRA, C.A.')
          <li class="nav-item">
            <a class="nav-link CP-Links-Menu" href="{{ url('/contabilidad/diferidosFTN') }}">
              <span data-feather="home"></span>
              <i class="fas fa-lock"></i>
              Diferidos en dolares FTN<span class="sr-only">(current)</span>
            </a>
          </li>
        @endif

        @if(Auth::user()->sede == 'GRUPO P, C.A' || Auth::user()->sede == 'FARMACIA LA LAGO,C.A.')
          <li class="nav-item">
            <a class="nav-link CP-Links-Menu" href="{{ url('/contabilidad/diferidosFLL') }}">
              <span data-feather="home"></span>
              <i class="fas fa-lock"></i>
              Diferidos en dolares FLL<span class="sr-only">(current)</span>
            </a>
          </li>
        @endif

        @if(Auth::user()->sede == 'GRUPO P, C.A' || Auth::user()->sede == 'FARMACIA AVENIDA UNIVERSIDAD, C.A.')
          <li class="nav-item">
            <a class="nav-link CP-Links-Menu" href="{{ url('/contabilidad/diferidosFAU') }}">
              <span data-feather="home"></span>
              <i class="fas fa-lock"></i>
              Diferidos en dolares FAU<span class="sr-only">(current)</span>
            </a>
          </li>
        @endif

        @if(Auth::user()->sede == 'GRUPO P, C.A' || Auth::user()->sede == 'FARMACIA MILLENNIUM 2000, C.A')
          <li class="nav-item">
            <a class="nav-link CP-Links-Menu" href="{{ url('/contabilidad/diferidosFM') }}">
              <span data-feather="home"></span>
              <i class="fas fa-lock"></i>
              Diferidos en dolares FM<span class="sr-only">(current)</span>
            </a>
          </li>
        @endif


        @if(Auth::user()->sede == 'GRUPO P, C.A' || Auth::user()->sede == 'FARMACIA TIERRA NEGRA, C.A.')
          <li class="nav-item">
            <a class="nav-link CP-Links-Menu" href="{{ url('/contabilidad/diferidosBolivaresFTN') }}">
              <span data-feather="home"></span>
              <i class="fas fa-lock"></i>
              Diferidos en bolivares FTN<span class="sr-only">(current)</span>
            </a>
          </li>
        @endif

        @if(Auth::user()->sede == 'GRUPO P, C.A' || Auth::user()->sede == 'FARMACIA LA LAGO,C.A.')
          <li class="nav-item">
            <a class="nav-link CP-Links-Menu" href="{{ url('/contabilidad/diferidosBolivaresFLL') }}">
              <span data-feather="home"></span>
              <i class="fas fa-lock"></i>
              Diferidos en bolivares FLL<span class="sr-only">(current)</span>
            </a>
          </li>
        @endif

        @if(Auth::user()->sede == 'GRUPO P, C.A' || Auth::user()->sede == 'FARMACIA AVENIDA UNIVERSIDAD, C.A.')
          <li class="nav-item">
            <a class="nav-link CP-Links-Menu" href="{{ url('/contabilidad/diferidosBolivaresFAU') }}">
              <span data-feather="home"></span>
              <i class="fas fa-lock"></i>
              Diferidos en bolivares FAU<span class="sr-only">(current)</span>
            </a>
          </li>
        @endif

        @if(Auth::user()->sede == 'GRUPO P, C.A' || Auth::user()->sede == 'FARMACIA MILLENNIUM 2000, C.A')
          <li class="nav-item">
            <a class="nav-link CP-Links-Menu" href="{{ url('/contabilidad/diferidosBolivaresFM') }}">
              <span data-feather="home"></span>
              <i class="fas fa-lock"></i>
              Diferidos en bolivares FM<span class="sr-only">(current)</span>
            </a>
          </li>
        @endif
      @endif

      @if(Auth::user()->departamento != 'GERENCIA' && Auth::user()->departamento != 'ADMINISTRACION' && Auth::user()->departamento != 'TESORERIA' && Auth::user()->departamento != 'OPERACIONES' && Auth::user()->departamento != 'CONTABILIDAD')
          <li class="nav-item">
            <a class="nav-link CP-Links-Menu" href="{{ url('/configuracion') }}">
              <span data-feather="home"></span>
              <i class="fas fa-cogs"></i>
              Configuración<span class="sr-only">(current)</span>
            </a>
          </li>
      @endif
    </div>
  </div>
<!-- DEVELOPER -->
<?php
  if(
      (
        Auth::user()->departamento == 'TECNOLOGIA'
        ||Auth::user()->departamento == 'GERENCIA'
      )
    && Auth::user()->role == 'DEVELOPER'
  ){
?>
  <div class="btn-group navbar-brand">
    <button type="button" class="btn btn-outline-info dropdown-toggle textoI" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
      <i class="fas fa-user-secret"></i> Developer
    </button>
    <div class="dropdown-menu">
      <li class="nav-item">
        <!--
        <a class="nav-link CP-Links-Menu" href="{{ url('/CorridaPrecios') }}">
          <span data-feather="home"></span>
          <i class="fas fa-funnel-dollar"></i>
          Corrida de Precios<span class="sr-only">(current)</span>
        </a>
        <a class="nav-link CP-Links-Menu" href="{{ url('/AuditoriaCorridaPrecios') }}">
          <span data-feather="home"></span>
          <i class="fas fa-search-dollar"></i>
          Auditoria Corrida de Precios<span class="sr-only">(current)</span>
        </a>
        -->
      </li>
    </div>
  </div>
<?php
  }
?>
<!-- DEVELOPER -->
<!-------------------------------------------------------------------------------->
<!-- SALIR -->
  <li class="navbar-brand">
    <a class="navbar-brand textoN btn btn-outline-info" href="{{ route('logout') }}" role="button" onclick="event.preventDefault();
                       document.getElementById('logout-form').submit();">
      <span data-feather="home"></span>
      <i class="fas fa-power-off"></i>
      {{Auth::user()->name}}<span class="sr-only">(current)</span>
    </a>
    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
     @csrf
    </form>
  </li>
<!-- SALIR -->
<!-------------------------------------------------------------------------------->
</div>
</nav>
<hr class="row align-items-start col-12">
