<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>    
        @include('includes.head')
    </head>
    <body>
        <div class="container">
            <article class="mx-auto my-auto">
                @yield('content')
            </article> 
        </div>

        @yield('scriptsFoot')
    </body>
</html>
