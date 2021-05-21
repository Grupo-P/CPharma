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
      <i class="fas fa-user-cog"></i> Tecnología
    </button>
    <div class="dropdown-menu">
      <li class="nav-item">
        <a class="nav-link CP-Links-Menu" href="{{ url('/proveedores') }}">
          <span data-feather="home"></span>
          <i class="fa fa-users"></i>
          Proveedores<span class="sr-only">(current)</span>
        </a>
      </li>
      <li class="nav-item">
        <a class="nav-link CP-Links-Menu" href="{{ url('/bancos') }}">
          <span data-feather="home"></span>
          <i class="fas fa-university"></i>
          Bancos<span class="sr-only">(current)</span>
        </a>
      </li>
      <li class="nav-item">
        <a class="nav-link CP-Links-Menu" href="{{ url('/pagos') }}">
          <span data-feather="home"></span>
          <i class="fas fa-money-bill-alt"></i>
          Pagos<span class="sr-only">(current)</span>
        </a>
      </li>
      <li class="nav-item">
        <a class="nav-link CP-Links-Menu" href="{{ url('/deudas') }}">
          <span data-feather="home"></span>
          <i class="fas fa-info-circle"></i>
          Deudas<span class="sr-only">(current)</span>
        </a>
      </li>
      <li class="nav-item">
        <a class="nav-link CP-Links-Menu" href="{{ url('/reclamos') }}">
          <span data-feather="home"></span>
          <i class="fas fa-exclamation-triangle"></i>
          Reclamos<span class="sr-only">(current)</span>
        </a>
      </li>
      <li class="nav-item">
        <a class="nav-link CP-Links-Menu" href="{{ url('/cuentas') }}">
          <span data-feather="home"></span>
          <i class="fas fa-network-wired"></i>
          Plan de cuentas<span class="sr-only">(current)</span>
        </a>
      </li>
      <li class="nav-item">
        <a class="nav-link CP-Links-Menu" href="{{ url('/configuracion') }}">
          <span data-feather="home"></span>
          <i class="fas fa-cogs"></i>
          Configuración<span class="sr-only">(current)</span>
        </a>
      </li>
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
