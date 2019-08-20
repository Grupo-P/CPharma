<style>
  a{
    text-decoration: none;
    color: green;
  }

  li > a {      
    /*border-style: solid;
    border-width: 1px;
    border-color: green;
    border-radius: 5px;*/
    margin-top: 2px;
  }

  li > a:hover {      
    border-style: solid;
    border-width: 1px;
    border-color: green;
    border-radius: 5px;
    background-color: green;
    color: white;
  }

  ul > a:hover {      
    border-style: solid;
    border-width: 1px;
    border-color: green;
    border-radius: 5px;
    background-color: green;
    color: white;
  }

  .dropdiv{
    border-style: solid;
    border-width: 1px;
    border-color: green;
    border-radius: 5px;
    background: rgba(255,255,255,0.92);
  }
</style>

<li class="nav-item">
  <a class="nav-link active" href="{{ url('/home') }}">
    <span data-feather="home"></span>
    <i class="fas fa-chart-pie"></i>
    Dashboard <span class="sr-only">(current)</span>
  </a>
</li>

<li class="nav-item">
  <a class="nav-link active" href="{{ url('/empresa') }}">
    <span data-feather="home"></span>
    <i class="fas fa-industry"></i>
    Empresa <span class="sr-only">(current)</span>
  </a>
</li>

<li class="nav-item">
  <a class="nav-link active" href="{{ url('/proveedor') }}">
    <span data-feather="home"></span>
    <i class="fas fa-dolly"></i>
    Proveedor <span class="sr-only">(current)</span>
  </a>
</li>

<li class="nav-item">
  <a class="nav-link" href="{{ url('/sede') }}">
    <span data-feather="home"></span>
    <i class="fas fa-file-invoice"></i>
    Reportes<span class="sr-only">(current)</span>
  </a>
</li>

<?php
  if(Auth::user()->role == 'ADMINISTRADOR'){
?>
    <hr class="row align-items-center">
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
  if(Auth::user()->role == 'SUPERVISOR CAJA'){
?>
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
  if(Auth::user()->role == 'MASTER'){
?>
    <hr class="row align-items-center">
    <li class="nav-item">
      <a class="nav-link" href="{{ url('/dolar') }}">
        <span data-feather="home"></span>
        <i class="fas fa-money-bill-alt"></i>
        Tasa de mercado<span class="sr-only">(current)</span>
      </a>
    </li>

    <li class="nav-item">
      <a class="nav-link" href="{{ url('/tasaVenta') }}">
        <span data-feather="home"></span>
        <i class="fas fa-credit-card"></i>
        Tasa de venta<span class="sr-only">(current)</span>
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
    <hr class="row align-items-center">
    <li class="nav-item">
      <a class="nav-link" href="{{ url('/dolar') }}">
        <span data-feather="home"></span>
        <i class="fas fa-money-bill-alt"></i>
        Tasa de mercado<span class="sr-only">(current)</span>
      </a>
    </li>

    <li class="nav-item">
      <a class="nav-link" href="{{ url('/tasaVenta') }}">
        <span data-feather="home"></span>
        <i class="fas fa-credit-card"></i>
        Tasa de venta<span class="sr-only">(current)</span>
      </a>
    </li>

    <li class="nav-item">
      <a class="nav-link" href="{{ url('/usuario') }}">
        <span data-feather="home"></span>
        <i class="fas fa-user"></i>
        Usuario<span class="sr-only">(current)</span>
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