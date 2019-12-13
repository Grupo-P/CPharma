<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>    
        @include('includes.head')
        @yield('scriptsCabecera')
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
            @yield('scriptsPie')
        </footer>
        @yield('scriptsFoot')
    </body>
</html>