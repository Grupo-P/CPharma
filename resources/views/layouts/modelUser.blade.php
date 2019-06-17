<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
  <!-- Cabecera: inclusion de librerias -->
  <head>    
      @include('includes.head')
  </head>

  <body>
    <header>
      <h4 style="display:block; font-size:30px; margin:16px; cursor:default;" class="text-info">
        <b><i class="fas fa-syringe text-success"></i>CPharma</b>
      </h4>
    </header>

    <div class="container">
      <div class="row">
        @yield('content')
      </div>
    </div>
    
    <!-- Pie: Final de la pagina  class="fixed-bottom"-->
    <footer>
        @include('includes.footer')
    </footer>
        @yield('scriptsFoot')
  </body>
</html>