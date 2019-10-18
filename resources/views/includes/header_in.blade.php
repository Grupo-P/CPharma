<!-- NavBar / Barra Navegacion -->
<nav class="navbar navbar-expand-lg bg-white text-info" style="height:105px;">
  <!-- Navbar brand -->
  <a class="navbar-brand text-info CP-title-NavBar bg-white" href="{{ url('/') }}"><b><i class="fas fa-syringe text-success"></i>CPharma</b>
  </a>
<!-------------------------------------------------------------------------------->
  <!-- DASHBOARD -->
  <li class="navbar-brand">
    <a class="navbar-brand textoN btn btn-outline-info" href="{{ url('/home') }}" role="button" data-toggle="tooltip" data-placement="top" title="DASHBOARD"> 
      <span data-feather="home"></span>
      <i class="fas fa-columns"></i>
      <span class="sr-only">(current)</span>
    </a>
  </li>
  <!-- DASHBOARD -->
<!-------------------------------------------------------------------------------->
  <!-- AGENDA -->
  <div class="btn-group" data-toggle="tooltip" data-placement="top" title="AGENDA">
    <button type="button" class="btn btn-outline-info dropdown-toggle textoI" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
      <i class="fas fa-book"></i>
    </button>
    <div class="dropdown-menu dropdown-menu">
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
<?php
  if(
    Auth::user()->departamento == 'COMPRAS'
    || Auth::user()->departamento == 'GERENCIA'
    || Auth::user()->departamento == 'TECNOLOGIA'
  ){
?>
  <!-- COMPRAS -->
  <div class="btn-group" data-toggle="tooltip" data-placement="top" title="COMPRAS">
    <button type="button" class="btn btn-outline-info dropdown-toggle textoI" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
      <i class="fas fa-chart-bar"></i>
    </button>
    <div class="dropdown-menu dropdown-menu">
      <li class="nav-item">
        <a class="nav-link CP-Links-Menu" href="{{ url('/sedes_reporte') }}">
          <span data-feather="home"></span>
          <i class="fas fa-file-invoice"></i>
          Reportes<span class="sr-only">(current)</span>
        </a>
      </li>
    </div>
  </div>
  <!-- COMPRAS -->
<?php
  }
?>
<!-------------------------------------------------------------------------------->
<?php
  if(
    Auth::user()->departamento == 'SURTIDO'
    || Auth::user()->departamento == 'GERENCIA'
    || Auth::user()->departamento == 'TECNOLOGIA'
  ){
?>
  <!-- SURTIDO -->
  <div class="btn-group" data-toggle="tooltip" data-placement="top" title="SURTIDO">
    <button type="button" class="btn btn-outline-info dropdown-toggle textoI" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
      <i class="fas fa-dolly-flatbed"></i>
    </button>
    <div class="dropdown-menu dropdown-menu">
      <li class="nav-item">
        <a class="nav-link CP-Links-Menu" href="{{ url('/sedes_reporte') }}">
          <span data-feather="home"></span>
          <i class="fas fa-file-invoice"></i>
          Reportes<span class="sr-only">(current)</span>
        </a>
      </li>
      <li class="nav-item">
        <a class="nav-link CP-Links-Menu" href="{{ url('/etiqueta') }}">     
          <span data-feather="home"></span>
          <i class="fas fa-tag"></i>
          Etiquetas<span class="sr-only">(current)</span>
        </a>
      </li>
    </div>
  </div>
  <!-- SURTIDO -->
<?php
  }
?>
<!-------------------------------------------------------------------------------->
<?php
  if(
    Auth::user()->departamento == 'ALMACEN'
    || Auth::user()->departamento == 'GERENCIA'
    || Auth::user()->departamento == 'TECNOLOGIA'
  ){
?>
  <!-- ALMACEN -->
  <div class="btn-group" data-toggle="tooltip" data-placement="top" title="ALMACEN">
    <button type="button" class="btn btn-outline-info dropdown-toggle textoI" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
      <i class="fas fa-warehouse"></i>
    </button>
    <div class="dropdown-menu dropdown-menu">
      <li class="nav-item">
        <a class="nav-link CP-Links-Menu" href="{{ url('/sedes_reporte') }}">
          <span data-feather="home"></span>
          <i class="fas fa-file-invoice"></i>
          Reportes<span class="sr-only">(current)</span>
        </a>
      </li>
      <li class="nav-item">
        <a class="nav-link CP-Links-Menu" href="{{ url('/traslado') }}">     
          <span data-feather="home"></span>
          <i class="fas fa-people-carry"></i>
          Traslado<span class="sr-only">(current)</span>
        </a>
      </li>
    </div>
  </div>
  <!-- ALMACEN -->
<?php
  }
?>
<!-------------------------------------------------------------------------------->
<?php
  if(
    Auth::user()->departamento == 'VENTAS'
    || Auth::user()->departamento == 'GERENCIA'
    || Auth::user()->departamento == 'TECNOLOGIA'
  ){
?>
  <!-- VENTAS -->
  <div class="btn-group" data-toggle="tooltip" data-placement="top" title="VENTAS">
    <button type="button" class="btn btn-outline-info dropdown-toggle textoI" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
      <i class="fas fa-dollar-sign"></i>
    </button>
    <div class="dropdown-menu dropdown-menu">
      <li class="nav-item">
        <a class="nav-link CP-Links-Menu" href="{{ url('/tasaVenta') }}">
          <span data-feather="home"></span>
          <i class="fas fa-credit-card"></i>
          Tasa de venta<span class="sr-only">(current)</span>
        </a>
      </li>
    </div>
  </div>
  <!-- VENTAS -->
<?php
  }
?>
<!-------------------------------------------------------------------------------->
<?php
  if(
    Auth::user()->departamento == 'ADMINISTRACION'
    || Auth::user()->departamento == 'GERENCIA'
    || Auth::user()->departamento == 'TECNOLOGIA'
  ){
?>
  <!-- ADMINISTRACION -->
  <div class="btn-group" data-toggle="tooltip" data-placement="top" title="ADMINISTRACION">
    <button type="button" class="btn btn-outline-info dropdown-toggle textoI" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
      <i class="fas fa-calculator"></i>
    </button>
    <div class="dropdown-menu dropdown-menu" style="width:200px;">
      <li class="nav-item">
        <a class="nav-link CP-Links-Menu" href="{{ url('/dolar') }}">
          <span data-feather="home"></span>
          <i class="fas fa-money-bill-alt"></i>
          Tasa de mercado<span class="sr-only">(current)</span>
        </a>
      </li>
      <li class="nav-item">
        <a class="nav-link CP-Links-Menu" href="{{ url('/diascero') }}">      
          <span data-feather="home"></span>
          <i class="far fa-calendar"></i>
          Dias en cero<span class="sr-only">(current)</span>
        </a>
      </li>
      <li class="nav-item">
        <a class="nav-link CP-Links-Menu" href="{{ url('/productoscaida') }}">      
          <span data-feather="home"></span>
          <i class="fas fa-chart-line"></i>
          Productos en Caida<span class="sr-only">(current)</span>
        </a>
      </li>
      <li class="nav-item">
        <a class="nav-link CP-Links-Menu" href="{{ url('/CapturaEtiqueta') }}">      
          <span data-feather="home"></span>
          <i class="fas fa-tag"></i>
          Captura Etiquetas<span class="sr-only">(current)</span>
        </a>
      </li>
    </div>
  </div>
  <!-- ADMINISTRACION -->
<?php
  }
?>









  <div class="collapse navbar-collapse" id="basicExampleNav">
    <ul class="navbar-nav ml-auto">
      @guest
        
      @else
        <li class="nav-item dropdown">
          <a id="navbarDropdown" class="nav-link dropdown-toggle text-succees CP-Links-Menu" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
          <i class="fas fa-user text-succees"></i>
          {{ Auth::user()->name }} <span class="caret text-succees"></span>
          </a>
          <div class="dropdown-menu dropdown-menu-right CP-divborder" aria-labelledby="navbarDropdown">
            <a class="dropdown-item CP-aborder" href="{{ route('logout') }}"
            onclick="event.preventDefault();
                       document.getElementById('logout-form').submit();">
            <i class="fas fa-sign-out-alt"></i>
            {{ __('Cerrar Sesión') }}
            </a>
            <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
            @csrf
            </form>
          </div>
        </li>
      @endguest
    </ul>               
  </div>

  <!-- Collapse button -->
  <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#basicExampleNav"
    aria-controls="basicExampleNav" aria-expanded="false" aria-label="Toggle navigation">
    <i class="fas fa-bars text-success"></i>
  </button>
  <!-- Collapsible content -->

</nav>
<hr class="row align-items-start col-12"> 
<script>
  $(document).ready(function(){
      $('[data-toggle="tooltip"]').tooltip();   
  });
  $('#exampleModalCenter').modal('show')
</script>