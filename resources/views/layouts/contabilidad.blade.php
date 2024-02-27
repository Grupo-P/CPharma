<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
  <head>
      @include('includes.head')
  </head>
  <body>
    <header>
      @if(Auth::user()->departamento == 'LÍDER DE TIENDA')
        @include('includes.header_in')
      @else
        @include('includes.header_in_contabilidad')
      @endif
      @yield('scriptsHead')
    </header>
    <div class="container-fluid">
      <div class="row">
        <main role="main" class="col-md-12 ml-sm-auto col-lg-12">
          @yield('content')
        </main>
      </div>
    </div>
    <footer>
        @include('includes.footer')
    </footer>
        @yield('scriptsFoot')
        <script>
            document.addEventListener('keydown', function (event) {
                if (event.keyCode == 13) {
                    event.preventDefault();
                }
            });
        </script>
  </body>
</html>
