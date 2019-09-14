<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
  <head>    
      @include('includes.head')
  </head>
  <body>
    <header>
      @include('includes.header_in')
      @yield('scriptsHead')
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
    <footer>
        @include('includes.footer')
    </footer>
        @yield('scriptsFoot')
  </body>
</html>