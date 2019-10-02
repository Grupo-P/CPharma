<!-- NavBar / Barra Navegacion -->
<nav class="navbar navbar-expand-lg bg-white text-info">
  <!-- Navbar brand -->
  <a class="navbar-brand text-info CP-title-NavBar" href="{{ url('/') }}"><b><i class="fas fa-syringe text-success"></i>CPharma</b>
  </a>
  <!-- Collapse button -->
  <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#basicExampleNav"
    aria-controls="basicExampleNav" aria-expanded="false" aria-label="Toggle navigation">
    <i class="fas fa-bars text-success"></i>
  </button>
  <!-- Collapsible content -->
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