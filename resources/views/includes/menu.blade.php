<style>
  a{
    text-decoration: none;
    color: #28A745;
  }

  li > a {      
    margin-top: 2px;
  }

  li > a:hover {      
    border-style: solid;
    border-width: 1px;
    border-color: #28A745;
    border-radius: 5px;
    background-color: #28A745;
    color: white;
  }

  ul > a:hover {      
    border-style: solid;
    border-width: 1px;
    border-color: #28A745;
    border-radius: 5px;
    background-color: #28A745;
    color: white;
  }

  .dropdiv{
    border-style: solid;
    border-width: 1px;
    border-color: #28A745;
    border-radius: 5px;
    background: rgba(255,255,255,0.92);
  }
</style>

<!-- USUARIOS -->
<li class="nav-item">
  <a class="nav-link active" href="{{ url('/home') }}">
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
    <a class="nav-link active" href="{{ url('/empresa') }}">
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
    <a class="nav-link active" href="{{ url('/proveedor') }}">
      <span data-feather="home"></span>
      <i class="fas fa-dolly"></i>
      Proveedor <span class="sr-only">(current)</span>
    </a>
  </li>
<?php
  }
?>

<li class="nav-item">
  <a class="nav-link" href="{{ url('/sedes_reporte') }}">
    <span data-feather="home"></span>
    <i class="fas fa-file-invoice"></i>
    Reportes<span class="sr-only">(current)</span>
  </a>
</li>

<?php
  if(
    Auth::user()->role == 'SUPERVISOR CAJA' 
    || Auth::user()->departamento == 'LÍDER DE TIENDA'){
?>
    <!-- SUPERVISOR CAJA -->
    <hr class="row align-items-center bg-success">
    <li class="nav-item">
      <a class="nav-link" href="{{ url('/tasaVenta') }}">
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
      <a class="nav-link" href="{{ url('/dolar') }}">
        <span data-feather="home"></span>
        <i class="fas fa-money-bill-alt"></i>
        Tasa de mercado<span class="sr-only">(current)</span>
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
      <a class="nav-link" href="{{ url('/tasaVenta') }}">
        <span data-feather="home"></span>
        <i class="fas fa-credit-card"></i>
        Tasa de venta<span class="sr-only">(current)</span>
      </a>
    </li>

    <li class="nav-item">
      <a class="nav-link" href="{{ url('/dolar') }}">
        <span data-feather="home"></span>
        <i class="fas fa-money-bill-alt"></i>
        Tasa de mercado<span class="sr-only">(current)</span>
      </a>
    </li>

    <li class="nav-item">
      <a class="nav-link" href="{{ url('/usuario') }}">
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
      <a class="nav-link" href="{{ url('/tasaVenta') }}">
        <span data-feather="home"></span>
        <i class="fas fa-credit-card"></i>
        Tasa de venta<span class="sr-only">(current)</span>
      </a>
    </li>
    
    <li class="nav-item">
      <a class="nav-link" href="{{ url('/dolar') }}">
        <span data-feather="home"></span>
        <i class="fas fa-money-bill-alt"></i>
        Tasa de mercado<span class="sr-only">(current)</span>
      </a>
    </li>

    <li class="nav-item">
      <a class="nav-link" href="{{ url('/usuario') }}">
        <span data-feather="home"></span>
        <i class="fas fa-user"></i>
        Usuario<span class="sr-only">(current)</span>
      </a>
    </li>
    
    <hr class="row align-items-center bg-warning">

    <li class="nav-item">
      <a class="nav-link" href="{{ url('/rol') }}">
        <span data-feather="home"></span>
        <i class="fas fa-user-circle"></i>
        Roles<span class="sr-only">(current)</span>
      </a>
    </li>

    <li class="nav-item">
      <a class="nav-link" href="{{ url('/departamento') }}">
        <span data-feather="home"></span>
        <i class="fab fa-buffer"></i>
        Departamento<span class="sr-only">(current)</span>
      </a>
    </li>

    <li class="nav-item">
      <a class="nav-link" href="{{ url('/sede') }}">
        <span data-feather="home"></span>
        <i class="fas fa-store-alt"></i>
        Sede<span class="sr-only">(current)</span>
      </a>
    </li>

    <li class="nav-item">
      <a class="nav-link" href="{{ url('/conexion') }}">
        <span data-feather="home"></span>
        <i class="fas fa-network-wired"></i>
        Conexion<span class="sr-only">(current)</span>
      </a>
    </li>

    <li class="nav-item">
      <a class="nav-link" href="{{ url('/configuracion') }}">      
        <span data-feather="home"></span>
        <i class="fas fa-cogs"></i>
        Configuracion<span class="sr-only">(current)</span>
      </a>
    </li>

    <li class="nav-item">
      <a class="nav-link" href="{{ url('/auditoria') }}">      
        <span data-feather="home"></span>
        <i class="fas fa-search"></i>
        Auditoria<span class="sr-only">(current)</span>
      </a>
    </li>

    <li class="nav-item">
      <a class="nav-link" href="{{ url('/diascero') }}">      
        <span data-feather="home"></span>
        <i class="fas fa-search"></i>
        Dias en cero<span class="sr-only">(current)</span>
      </a>
    </li>

<!-- TODO LO QUE ESTE DEBAJO DE ESTA LINEA ESTA EN FASE DE DESARROLLO -->
<hr class="row align-items-center bg-danger">
    
    <li class="nav-item">
      <a class="nav-link" href="{{ url('/etiqueta') }}">     
        <span data-feather="home"></span>
        <i class="fas fa-tag"></i>
        Test Etiqueta<span class="sr-only">(current)</span>
      </a>
    </li>

    <li class="nav-item">
      <a class="nav-link" href="{{ url('/testS') }}">     
        <span data-feather="home"></span>
        <i class="fas fa-box"></i>
        Test Sergio<span class="sr-only">(current)</span>
      </a>
    </li>

    <li class="nav-item">
      <a class="nav-link" href="{{ url('/testM') }}">
        <span data-feather="home"></span>
        <i class="fas fa-box"></i>
        Test Manuel<span class="sr-only">(current)</span>
      </a>
    </li>
<?php
  }
?>