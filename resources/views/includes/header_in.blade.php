<!-- NavBar / Barra Navegacion -->
<nav class="navbar navbar-expand-lg bg-white text-info" style="height:110px;">
  <!-- Navbar brand -->
  <a class="navbar-brand text-info CP-title-NavBar bg-white" href="{{ url('/') }}"><b><i class="fas fa-syringe text-success"></i>CPharma</b>
  </a>
<!-------------------------------------------------------------------------------->
  <!-- DASHBOARD -->
  <li class="navbar-brand">
    <a class="navbar-brand btn btn-outline-info textoN" href="{{ url('/home') }}" role="button" data-toggle="tooltip" data-placement="top" title="DASHBOARD"> 
      <span data-feather="home"></span>
      <i class="fas fa-columns"></i>
      Dashboard<span class="sr-only">(current)</span>
    </a>
  </li>
  <!-- DASHBOARD -->
<!-------------------------------------------------------------------------------->
  <!-- AGENDA -->
  <div class="btn-group" data-toggle="tooltip" data-placement="top" title="AGENDA">
    <button type="button" class="btn btn-outline-info dropdown-toggle textoI" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
      <i class="fas fa-book"></i>
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
    <div class="dropdown-menu">
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
    Auth::user()->departamento == 'OPERACIONES'
    || Auth::user()->departamento == 'GERENCIA'
    || Auth::user()->departamento == 'TECNOLOGIA'
  ){
?>
  <!-- OPERACIONES -->
  <div class="btn-group" data-toggle="tooltip" data-placement="top" title="OPERACIONES">
    <button type="button" class="btn btn-outline-info dropdown-toggle textoI" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
      <i class="fas fa-tasks"></i>
    </button>
    <div class="dropdown-menu">
      <li class="nav-item">
        <a class="nav-link CP-Links-Menu" href="{{ url('/sedes_reporte') }}">
          <span data-feather="home"></span>
          <i class="fas fa-file-invoice"></i>
          Reportes<span class="sr-only">(current)</span>
        </a>
      </li>
    </div>
  </div>
  <!-- OPERACIONES -->
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
    <div class="dropdown-menu">
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
    Auth::user()->departamento == 'DEVOLUCIONES'
    || Auth::user()->departamento == 'GERENCIA'
    || Auth::user()->departamento == 'TECNOLOGIA'
  ){
?>
  <!-- DEVOLUCIONES -->
  <div class="btn-group" data-toggle="tooltip" data-placement="top" title="DEVOLUCIONES">
    <button type="button" class="btn btn-outline-info dropdown-toggle textoI" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
      <i class="fas fa-history"></i>
    </button>
    <div class="dropdown-menu">
      <li class="nav-item">
        <a class="nav-link CP-Links-Menu" href="{{ url('/sedes_reporte') }}">
          <span data-feather="home"></span>
          <i class="fas fa-file-invoice"></i>
          Reportes<span class="sr-only">(current)</span>
        </a>
      </li>
    </div>
  </div>
  <!-- DEVOLUCIONES -->
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
    <div class="dropdown-menu">
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
    Auth::user()->departamento == 'VENTAS'
    || Auth::user()->departamento == 'GERENCIA'
    || Auth::user()->departamento == 'TECNOLOGIA'
  ){
?>
  <!-- VENTAS -->
  <div class="btn-group" data-toggle="tooltip" data-placement="top" title="VENTAS">
    <button type="button" class="btn btn-outline-info dropdown-toggle textoI" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
      <i class="fas fa-cash-register"></i>
    </button>
    <div class="dropdown-menu">
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
    <div class="dropdown-menu" style="width:200px;">
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
<!-------------------------------------------------------------------------------->
<?php
  if(
    Auth::user()->departamento == 'LÍDER DE TIENDA'
    || Auth::user()->departamento == 'GERENCIA'
    || Auth::user()->departamento == 'TECNOLOGIA'
  ){
?>
  <!-- LÍDER DE TIENDA -->
  <div class="btn-group" data-toggle="tooltip" data-placement="top" title="LÍDER DE TIENDA">
    <button type="button" class="btn btn-outline-info dropdown-toggle textoI" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
      <i class="fas fa-store"></i>
    </button>
    <div class="dropdown-menu">
      <li class="nav-item">
        <a class="nav-link CP-Links-Menu" href="{{ url('/sedes_reporte') }}">
          <span data-feather="home"></span>
          <i class="fas fa-file-invoice"></i>
          Reportes<span class="sr-only">(current)</span>
        </a>
      </li>
    </div>
  </div>
  <!-- LÍDER DE TIENDA -->
<?php
  }
?>
<!-------------------------------------------------------------------------------->
<?php
  if(
    Auth::user()->departamento == 'GERENCIA'
    || Auth::user()->departamento == 'TECNOLOGIA'
  ){
?>
  <!-- GERENCIA -->
  <div class="btn-group" data-toggle="tooltip" data-placement="top" title="GERENCIA">
    <button type="button" class="btn btn-outline-info dropdown-toggle textoI" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
      <i class="fas fa-user-tie"></i>
    </button>
    <div class="dropdown-menu">
      <li class="nav-item">
        <a class="nav-link CP-Links-Menu" href="{{ url('/sedes_reporte') }}">
          <span data-feather="home"></span>
          <i class="fas fa-file-invoice"></i>
          Reportes<span class="sr-only">(current)</span>
        </a>
        <a class="nav-link CP-Links-Menu" href="{{ url('/usuario') }}">
          <span data-feather="home"></span>
          <i class="fas fa-user"></i>
          Usuario<span class="sr-only">(current)</span>
        </a>
      </li>
    </div>
  </div>
  <!-- GERENCIA -->
<?php
  }
?>
<!------------------------------------------------------------------------------->
<?php
  if(
    Auth::user()->departamento == 'TECNOLOGIA'
  ){
?>
  <!-- TECNOLOGIA -->
  <div class="btn-group" data-toggle="tooltip" data-placement="top" title="TECNOLOGIA">
    <button type="button" class="btn btn-outline-info dropdown-toggle textoI" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
      <i class="fas fa-user-cog"></i>
    </button>
    <div class="dropdown-menu" style="width:200px;">
      <li class="nav-item">
        <a class="nav-link CP-Links-Menu" href="{{ url('/sedes_reporte') }}">
          <span data-feather="home"></span>
          <i class="fas fa-file-invoice"></i>
          Reportes<span class="sr-only">(current)</span>
        </a>
      </li>
      <li class="nav-item">
        <a class="nav-link CP-Links-Menu" href="{{ url('/usuario') }}">
          <span data-feather="home"></span>
          <i class="fas fa-user"></i>
          Usuario<span class="sr-only">(current)</span>
        </a>
      </li>
      <li class="nav-item">
        <a class="nav-link CP-Links-Menu" href="{{ url('/rol') }}">
          <span data-feather="home"></span>
          <i class="fas fa-user-circle"></i>
          Roles<span class="sr-only">(current)</span>
        </a>
      </li>
      <li class="nav-item">
        <a class="nav-link CP-Links-Menu" href="{{ url('/departamento') }}">
          <span data-feather="home"></span>
          <i class="fab fa-buffer"></i>
          Departamento<span class="sr-only">(current)</span>
        </a>
      </li>
      <li class="nav-item">
        <a class="nav-link CP-Links-Menu" href="{{ url('/sede') }}">
          <span data-feather="home"></span>
          <i class="fas fa-store-alt"></i>
          Sede<span class="sr-only">(current)</span>
        </a>
      </li>
      <li class="nav-item">
        <a class="nav-link CP-Links-Menu" href="{{ url('/conexion') }}">
          <span data-feather="home"></span>
          <i class="fas fa-network-wired"></i>
          Conexion<span class="sr-only">(current)</span>
        </a>
      </li>
      <li class="nav-item">
        <a class="nav-link CP-Links-Menu" href="{{ url('/configuracion') }}">      
          <span data-feather="home"></span>
          <i class="fas fa-cogs"></i>
          Configuracion<span class="sr-only">(current)</span>
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
      <li class="nav-item">
        <a class="nav-link CP-Links-Menu" href="{{ url('/auditoria') }}">      
          <span data-feather="home"></span>
          <i class="fas fa-search"></i>
          Auditoria<span class="sr-only">(current)</span>
        </a>
      </li>
    </div>
  </div>
  <!-- TECNOLOGIA -->
<?php
  }
?>
<!-------------------------------------------------------------------------------->
<?php
  if(
    Auth::user()->departamento == 'TECNOLOGIA'
    || Auth::user()->role == 'DEVELOPER'
  ){
?>
  <!-- DEVELOPER -->
  <div class="btn-group" data-toggle="tooltip" data-placement="top" title="DEVELOPER">
    <button type="button" class="btn btn-outline-info dropdown-toggle textoI" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
      <i class="fas fa-user-secret"></i>
    </button>
    <div class="dropdown-menu">
      <li class="nav-item">
        <a class="nav-link CP-Links-Menu" href="{{ url('/testS') }}">     
          <span data-feather="home"></span>
          <i class="fas fa-box"></i>
          Test Sergio<span class="sr-only">(current)</span>
        </a>
      </li>
      <li class="nav-item">
        <a class="nav-link CP-Links-Menu" href="{{ url('/testM') }}">
          <span data-feather="home"></span>
          <i class="fas fa-box"></i>
          Test Manuel<span class="sr-only">(current)</span>
        </a>
      </li>
      <li class="nav-item">
        <a class="nav-link CP-Links-Menu" href="{{ url('/testR') }}">
          <span data-feather="home"></span>
          <i class="fas fa-box"></i>
          Test Rubmary<span class="sr-only">(current)</span>
        </a>
      </li>
    </div>
  </div>
  <!-- DEVELOPER -->
<?php
  }
?>
<!-------------------------------------------------------------------------------->
  <!-- SALIR -->
  <li class="navbar-brand">
    <a class="navbar-brand textoN btn btn-outline-info" href="{{ route('logout') }}" role="button" data-toggle="tooltip" data-placement="top" title="SALIR"onclick="event.preventDefault();
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
</nav>
<hr class="row align-items-start col-12"> 

<script>
  $(document).ready(function(){
      $('[data-toggle="tooltip"]').tooltip();   
  });
  $('#exampleModalCenter').modal('show')
</script>