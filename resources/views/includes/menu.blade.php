<style>
    a{
      text-decoration: none;
      color: green;
    }

    li > a {      
      /*border-style: solid;
      border-width: 1px;
      border-color: green; {{ url('dashboard') }}
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
  <a class="nav-link active" href="{{ url('dashboard') }}">
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

<!-- Inicio Empresa -->
{{-- <li class="dropright">
  <a class="nav-link active dropdown-toggle" data-toggle="dropdown" href="#">
    <span data-feather="home"></span>
    <i class="fas fa-industry"></i>
    Empresa <span class="sr-only">(current)</span>
  </a>

    <ul class="dropdown-menu dropdiv">

      <a class="nav-link active" href="{{ url('/empresa') }}">
        <span data-feather="home"></span>
        <i class="fas fa-list"></i>
        Ver lista <span class="sr-only">(current)</span>
      </a>

      <a class="nav-link active" href="{{ url('/empresa/create') }}">
        <span data-feather="home"></span>
        <i class="fas fa-plus"></i>
        Agregar <span class="sr-only">(current)</span>
      </a>
  </ul>
</li> --}}
<!-- Cierre Empresa -->

<li class="nav-item">
  <a class="nav-link active" href="#">
    <span data-feather="home"></span>
    <i class="fas fa-boxes"></i>
    Articulos<span class="sr-only">(current)</span>
  </a>
</li>
<li class="nav-item">
  <a class="nav-link active" href="#">
    <span data-feather="home"></span>
    <i class="fas fa-dolly"></i>
    Proveedores<span class="sr-only">(current)</span>
  </a>
</li>
<li class="nav-item">
  <a class="nav-link active" href="#">
    <span data-feather="home"></span>
    <i class="fas fa-shopping-cart"></i>
    Compras<span class="sr-only">(current)</span>
  </a>
</li>
<li class="nav-item">
  <a class="nav-link active" href="#">
    <span data-feather="home"></span>
    <i class="fas fa-money-bill-wave"></i>
    Ventas<span class="sr-only">(current)</span>
  </a>
</li>
<li class="nav-item">
  <a class="nav-link active" href="#">
    <span data-feather="home"></span>
    <i class="fas fa-cog"></i>
    Configuracion<span class="sr-only">(current)</span>
  </a>
</li>