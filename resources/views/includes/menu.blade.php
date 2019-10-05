<!-- USUARIOS -->
<li class="nav-item">
  <a class="nav-link CP-Links-Menu" href="{{ url('/home') }}">
    <span data-feather="home"></span>
    <i class="fas fa-chart-pie"></i>
    Dashboard <span class="sr-only">(current)</span>
  </a>
</li>

<?php
  if(
    Auth::user()->departamento == 'COMPRAS'
    || Auth::user()->departamento == 'DEVOLUCIONES'
    || Auth::user()->departamento == 'OPERACIONES' 
    || Auth::user()->departamento == 'GERENCIA'
    || Auth::user()->departamento == 'TECNOLOGIA'
  ){
?>
  <li class="nav-item">
    <a class="nav-link CP-Links-Menu" href="{{ url('/empresa') }}">
      <span data-feather="home"></span>
      <i class="fas fa-industry"></i>
      Empresa <span class="sr-only">(current)</span>
    </a>
  </li>
<?php
  }
?>

<?php
  if(
    Auth::user()->departamento == 'COMPRAS'
    || Auth::user()->departamento == 'DEVOLUCIONES'
    || Auth::user()->departamento == 'OPERACIONES' 
    || Auth::user()->departamento == 'GERENCIA'
    || Auth::user()->departamento == 'TECNOLOGIA'
  ){
?>
  <li class="nav-item">
    <a class="nav-link CP-Links-Menu" href="{{ url('/proveedor') }}">
      <span data-feather="home"></span>
      <i class="fas fa-dolly"></i>
      Proveedor <span class="sr-only">(current)</span>
    </a>
  </li>
<?php
  }
?>

<li class="nav-item">
  <a class="nav-link CP-Links-Menu" href="{{ url('/sedes_reporte') }}">
    <span data-feather="home"></span>
    <i class="fas fa-file-invoice"></i>
    Reportes<span class="sr-only">(current)</span>
  </a>
</li>

<?php
  if(
    Auth::user()->departamento == 'SURTIDO'
    || Auth::user()->departamento == 'LÍDER DE TIENDA'        
    || Auth::user()->departamento == 'GERENCIA'
    || Auth::user()->departamento == 'TECNOLOGIA'
  ){
?>
  <li class="nav-item">
    <a class="nav-link CP-Links-Menu" href="{{ url('/etiqueta') }}">     
      <span data-feather="home"></span>
      <i class="fas fa-tag"></i>
      Etiquetas<span class="sr-only">(current)</span>
    </a>
  </li>
<?php
  }
?>

<?php
  if(
    Auth::user()->role == 'SUPERVISOR CAJA' 
    || Auth::user()->departamento == 'LÍDER DE TIENDA'){
?>
    <!-- SUPERVISOR CAJA -->
    <hr class="row align-items-center bg-success">
    <li class="nav-item">
      <a class="nav-link CP-Links-Menu" href="{{ url('/tasaVenta') }}">
        <span data-feather="home"></span>
        <i class="fas fa-credit-card"></i>
        Tasa de venta<span class="sr-only">(current)</span>
      </a>
    </li>
<?php
  }
?>

<?php
  if(Auth::user()->role == 'ADMINISTRADOR'){
?>
    <!-- ADMINISTRADOR -->
    <hr class="row align-items-center bg-success">
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
<?php
  }
?>

<?php
  if(Auth::user()->role == 'MASTER'){
?>
    <!-- MASTER -->
    <hr class="row align-items-center bg-success">
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
<?php
  }
?>

<?php
  if(Auth::user()->role == 'DEVELOPER'){
?>
    <!-- DEVELOPER -->
    <hr class="row align-items-center bg-success">
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
    
    <hr class="row align-items-center bg-warning">
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

<!-- TODO LO QUE ESTE DEBAJO DE ESTA LINEA ESTA EN FASE DE DESARROLLO -->
<hr class="row align-items-center bg-danger">
    
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
<?php
  }
?>