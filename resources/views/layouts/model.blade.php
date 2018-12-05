<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
  <!-- Cabecera: inclusion de librerias -->
  <head>    
      @include('includes.head')
  </head>

  <body>
    <header>
      @include('includes.header_in')
    </header>

    <div class="container-fluid">
      <div class="row">
        <nav class="col-md-2 d-none d-md-block sidebar">
          <div class="sidebar-sticky">
            <ul class="nav flex-column">
              @include('includes.menu')
            </ul>
          </div>
        </nav>

        <main role="main" class="col-md-9 ml-sm-auto col-lg-10">
          @yield('content')
        </main>
      </div>
    </div>
    
    <!-- Pie: Final de la pagina  class="fixed-bottom"-->
    <footer>
        @include('includes.footer')
    </footer>
  </body>
</html>