<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>    
        @include('includes.head')
        @yield('scriptsCabecera')
        <style type="text/css" media="print"> 
            .page 
            { 
                -webkit-transform: rotate(-90deg); 
                -moz-transform:rotate(-90deg); 
                filter:progid:DXImageTransform.Microsoft.BasicImage(rotation=3); 
                margin-top:90px;
            }             
        </style>
    </head>     
    <body class="page">
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