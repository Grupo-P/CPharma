<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <!-- Cabecera: inclusion de librerias -->
    <head>    
        @include('includes.head')
    </head>

    <!-- Cuerpo: Contenido de la pagina -->    
    <body>
        <!-- Contenedor inicial para el cuerpo de la pagina -->
        <div class="container">
            <!-- Cabecera: Inicio de la pagina -->
            <header>
                @include('includes.header')
            </header>

            <!-- Cuerpo: Donde se edita y cambia todo el contenido -->
            <article class="mx-auto my-auto">
                @yield('content')
            </article> 

            <!-- Pie: Final de la pagina -->
            <footer class="fixed-bottom">
                @include('includes.footer')
            </footer>

        </div>
    </body>
</html>