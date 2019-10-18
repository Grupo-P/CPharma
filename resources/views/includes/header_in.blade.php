<!-- NavBar / Barra Navegacion -->
<nav class="navbar navbar-expand-lg bg-dark text-info" style="height:100px;">
  <!-- Navbar brand -->
  <a class="navbar-brand text-info CP-title-NavBar bg-danger" href="{{ url('/') }}"><b><i class="fas fa-syringe text-success"></i>CPharma</b>
  </a>
  <!-- Collapse button -->
  <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#basicExampleNav"
    aria-controls="basicExampleNav" aria-expanded="false" aria-label="Toggle navigation">
    <i class="fas fa-bars text-success"></i>
  </button>
  <!-- Collapsible content -->
  
  <!-- Dashboard -->
  <li class="navbar-brand">
    <a class="navbar-brand CP-Links-Nav bg-danger" href="{{ url('/home') }}" role="button" data-toggle="tooltip" data-placement="top" title="Dashboard">
      <span data-feather="home"></span>
      <i class="fas fa-chart-pie"></i>
      <span class="sr-only">(current)</span>
    </a>
  </li>
  <!-- Dashboard -->

  <!-- Agenda -->
  <div class="btn-group">
    <button type="button" class="btn btn-danger iconoI"><i class="fas fa-book"></i></button>
    <button type="button" class="btn btn-danger dropdown-toggle dropdown-toggle-split" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
      <span class="sr-only">Toggle Dropdown</span>
    </button>
    <div class="dropdown-menu">
      <a class="dropdown-item nav-link CP-Links-Menu" href="{{ url('/empresa') }}">
        <i class="fas fa-industry"></i> Empresa
      </a>
      <a class="dropdown-item nav-link CP-Links-Menu" href="{{ url('/empresa') }}">
        <i class="fas fa-dolly"></i></i> Proveedor
      </a>
    </div>
  </div>
  <!-- Agenda -->

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
</nav>

<script>
  $(document).ready(function(){
      $('[data-toggle="tooltip"]').tooltip();   
  });
  $('#exampleModalCenter').modal('show')
</script>