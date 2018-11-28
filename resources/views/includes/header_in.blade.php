<!-- NavBar / Barra Navegacion -->
<!--Navbar-->
<nav class="navbar navbar-expand-lg bg-white text-info">

  <!-- Navbar brand -->
  <a class="navbar-brand text-info" href="{{ url('/') }}"><b><i class="fas fa-syringe text-success"></i>CPharma</b>
  </a>

  <!-- Collapse button -->
  <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#basicExampleNav"
    aria-controls="basicExampleNav" aria-expanded="false" aria-label="Toggle navigation">
    <span class="navbar-toggler-icon"></span>
  </button>

  <!-- Collapsible content -->
  <div class="collapse navbar-collapse" id="basicExampleNav">

    <!-- Links -->
      <ul class="navbar-nav ml-auto">
        <!-- Authentication Links -->
        @guest
          <!-- <li class="nav-item">
          <a class="nav-link" href="{{ route('login') }}">{{ __('Login') }}</a>
          </li>
          <li class="nav-item">
          @if (Route::has('register'))
          <a class="nav-link" href="{{ route('register') }}">{{ __('Register') }}</a>
          @endif
          </li> -->
        @else
          <li class="nav-item dropdown">
            <a id="navbarDropdown" class="nav-link dropdown-toggle text-info" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
            {{ Auth::user()->name }} <span class="caret"></span>
            </a>

            <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
              <a class="dropdown-item" href="{{ route('logout') }}"
              onclick="event.preventDefault();
                         document.getElementById('logout-form').submit();">
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
  <!-- Collapsible content -->
</nav>
<!--/.Navbar-->