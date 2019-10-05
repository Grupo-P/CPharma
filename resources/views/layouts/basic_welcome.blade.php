<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>    
        @include('includes.head')
    </head>    
    <body>
        <div class="container">
            <header>
                @include('includes.header_welcome')
            </header>
            <article class="mx-auto my-auto">
                @yield('content')
            </article> 
            <footer class="fixed-bottom">
                @include('includes.footer')
            </footer>
        </div>
    </body>
</html>