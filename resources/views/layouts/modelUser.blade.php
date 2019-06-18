<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <!-- Cabecera: inclusion de librerias -->
    <head>    
        @include('includes.head')
    </head>

    <!-- Cuerpo: Contenido de la pagina -->    
    <body>
        <!-- Cabecera: Inicio de la pagina -->
        <header>
            @include('includes.header')
        </header>

        <!-- Contenedor inicial para el cuerpo de la pagina -->
        <div class="container">
            <!-- Cuerpo: Donde se edita y cambia todo el contenido -->
            @yield('content')
        </div>

        <!-- Pie: Final de la pagina -->
        <footer>
            @include('includes.footer')
        </footer>
    </body>
</html>