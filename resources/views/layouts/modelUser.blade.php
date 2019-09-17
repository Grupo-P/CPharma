<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>    
        @include('includes.head')
        @yield('scriptsHead')
    </head>   
    <body>
        <header>
            @include('includes.header')
        </header>

        <div class="container">
            @yield('content')
        </div>

        <footer>
            @include('includes.footer')
            @yield('scriptsFoot')
        </footer>
    </body>
</html>