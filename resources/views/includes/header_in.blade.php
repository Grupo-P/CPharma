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
<?php
  if((Auth::user()->departamento != 'VENTAS')&&(Auth::user()->departamento != 'RRHH')){
?>
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
<?php
  }
?>
  <!-- AGENDA -->
<!-------------------------------------------------------------------------------->
<?php
  if(Auth::user()->departamento == 'COMPRAS'){
?>
  <!-- COMPRAS -->
  <div class="btn-group navbar-brand">
    <button type="button" class="btn btn-outline-info dropdown-toggle textoI" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
      <i class="fas fa-chart-bar"></i> Compras
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
  if(Auth::user()->departamento == 'OPERACIONES'){
?>
  <!-- OPERACIONES -->
  <div class="btn-group navbar-brand">
    <button type="button" class="btn btn-outline-info dropdown-toggle textoI" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
      <i class="fas fa-tasks"></i> Operaciones
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
      <li class="nav-item">
        <a class="nav-link CP-Links-Menu" href="{{ url('/tasaVenta') }}">
          <span data-feather="home"></span>
          <i class="fas fa-credit-card"></i>
          Tasa de venta<span class="sr-only">(current)</span>
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
  if(Auth::user()->departamento == 'ALMACEN'){
?>
  <!-- ALMACEN -->
  <div class="btn-group navbar-brand">
    <button type="button" class="btn btn-outline-info dropdown-toggle textoI" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
      <i class="fas fa-warehouse"></i> Almacen
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
  if(Auth::user()->departamento == 'DEVOLUCIONES'){
?>
  <!-- DEVOLUCIONES -->
  <div class="btn-group navbar-brand">
    <button type="button" class="btn btn-outline-info dropdown-toggle textoI" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
      <i class="fas fa-history"></i> Devoluciones
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
  <!-- DEVOLUCIONES -->
<?php
  }
?>
<!-------------------------------------------------------------------------------->
<?php
  if(Auth::user()->departamento == 'SURTIDO'){
?>
  <!-- SURTIDO -->
  <div class="btn-group navbar-brand">
    <button type="button" class="btn btn-outline-info dropdown-toggle textoI" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
      <i class="fas fa-dolly-flatbed"></i> Surtido
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
  if(Auth::user()->departamento == 'VENTAS'){
?>
  <!-- VENTAS -->
  <div class="btn-group navbar-brand">
    <button type="button" class="btn btn-outline-info dropdown-toggle textoI" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
      <i class="fas fa-cash-register"></i> Ventas
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
  if(Auth::user()->departamento == 'RRHH'){
?>
  <!-- RRHH -->
  <div class="btn-group navbar-brand">
    <button type="button" class="btn btn-outline-info dropdown-toggle textoI" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
      <i class="fas fa-user-circle"></i> RRHH
    </button>
    <div class="dropdown-menu">
      <li class="nav-item">
        <a class="nav-link CP-Links-Menu" href="#">
          <span data-feather="home"></span>
          <i class="fas fa-user"></i>
          Candidatos<span class="sr-only">(current)</span>
        </a>
      </li>
    </div>
  </div>
  <!-- RRHH -->
<?php
  }
?>
<!-------------------------------------------------------------------------------->
<?php
  if(Auth::user()->departamento == 'ADMINISTRACION'){
?>
  <!-- ADMINISTRACION -->
  <div class="btn-group navbar-brand">
    <button type="button" class="btn btn-outline-info dropdown-toggle textoI" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
      <i class="fas fa-calculator"></i> Administracion
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
        <a class="nav-link CP-Links-Menu" href="{{ url('/traslado') }}">     
          <span data-feather="home"></span>
          <i class="fas fa-people-carry"></i>
          Traslado<span class="sr-only">(current)</span>
        </a>
      </li>
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
  if(Auth::user()->departamento == 'LÍDER DE TIENDA'){
?>
  <!-- LÍDER DE TIENDA -->
  <div class="btn-group navbar-brand">
    <button type="button" class="btn btn-outline-info dropdown-toggle textoI" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
      <i class="fas fa-store"></i> Lider de tienda
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
      <li class="nav-item">
        <a class="nav-link CP-Links-Menu" href="{{ url('/traslado') }}">     
          <span data-feather="home"></span>
          <i class="fas fa-people-carry"></i>
          Traslado<span class="sr-only">(current)</span>
        </a>
      </li>
      <li class="nav-item">
        <a class="nav-link CP-Links-Menu" href="{{ url('/tasaVenta') }}">
          <span data-feather="home"></span>
          <i class="fas fa-credit-card"></i>
          Tasa de venta<span class="sr-only">(current)</span>
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
  if(Auth::user()->departamento == 'GERENCIA'){
?>
  <!-- GERENCIA -->
  <div class="btn-group navbar-brand">
    <button type="button" class="btn btn-outline-info dropdown-toggle textoI" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
      <i class="fas fa-user-tie"></i> Gerencia
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
      <li class="nav-item">
        <a class="nav-link CP-Links-Menu" href="{{ url('/traslado') }}">     
          <span data-feather="home"></span>
          <i class="fas fa-people-carry"></i>
          Traslado<span class="sr-only">(current)</span>
        </a>
      </li>
      <li class="nav-item">
        <a class="nav-link CP-Links-Menu" href="{{ url('/tasaVenta') }}">
          <span data-feather="home"></span>
          <i class="fas fa-credit-card"></i>
          Tasa de venta<span class="sr-only">(current)</span>
        </a>
      </li>
      <li class="nav-item">
        <a class="nav-link CP-Links-Menu" href="{{ url('/dolar') }}">
          <span data-feather="home"></span>
          <i class="fas fa-money-bill-alt"></i>
          Tasa de mercado<span class="sr-only">(current)</span>
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
  <!-- GERENCIA -->
<?php
  }
?>
<!------------------------------------------------------------------------------->
<?php
  if(Auth::user()->departamento == 'TECNOLOGIA'){
?>
  <!-- TECNOLOGIA -->
  <div class="btn-group navbar-brand">
    <button type="button" class="btn btn-outline-info dropdown-toggle textoI" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
      <i class="fas fa-user-cog"></i> Tecnologia
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
        <a class="nav-link CP-Links-Menu" href="{{ url('/etiqueta') }}">     
          <span data-feather="home"></span>
          <i class="fas fa-tag"></i>
          Etiquetas<span class="sr-only">(current)</span>
        </a>
      </li>
      <li class="nav-item">
        <a class="nav-link CP-Links-Menu" href="{{ url('/traslado') }}">     
          <span data-feather="home"></span>
          <i class="fas fa-people-carry"></i>
          Traslado<span class="sr-only">(current)</span>
        </a>
      </li>
      <li class="nav-item">
        <a class="nav-link CP-Links-Menu" href="{{ url('/tasaVenta') }}">
          <span data-feather="home"></span>
          <i class="fas fa-credit-card"></i>
          Tasa de venta<span class="sr-only">(current)</span>
        </a>
      </li>
      <li class="nav-item">
        <a class="nav-link CP-Links-Menu" href="{{ url('/dolar') }}">
          <span data-feather="home"></span>
          <i class="fas fa-money-bill-alt"></i>
          Tasa de mercado<span class="sr-only">(current)</span>
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
  if(Auth::user()->departamento == 'TECNOLOGIA'
    || Auth::user()->role == 'DEVELOPER'){
?>
  <!-- DEVELOPER -->
  <div class="btn-group navbar-brand">
    <button type="button" class="btn btn-outline-info dropdown-toggle textoI" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
      <i class="fas fa-user-secret"></i> Developer
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