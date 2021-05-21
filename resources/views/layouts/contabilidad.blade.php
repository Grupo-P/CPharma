<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
  <head>
      @include('includes.head')
  </head>
  <body>
    <header>
      @include('includes.header_in_contabilidad')
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
  </body>
</html>
