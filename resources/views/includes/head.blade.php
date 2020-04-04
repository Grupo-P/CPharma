<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
<!-- CSRF Token -->
<meta name="csrf-token" content="{{ csrf_token() }}">
<link rel="icon" type="image/png" href="{{ asset('assets/img/icono.png') }}" />
<title>CPharma - @yield('title')</title>
<link rel="stylesheet" type="text/css" href="{{asset('assets/DataTables/datatables.min.css')}}">
<!-- CSS Style -->
<!-- Fonts Awesaome CSS -->
<link rel="stylesheet" href="{{asset('assets/fonts/fontawesome/css/all.css')}}">
<!-- Fonts: Nunito -->
<link rel="stylesheet" href="{{asset('assets/fonts/Nunito.css')}}" type="text/css">
<!-- Scritps Bootstrap 4 CSS -->
<link rel="stylesheet" href="{{asset('assets/bootstrap/css/bootstrap.css')}}">
<link rel="stylesheet" href="{{asset('assets/bootstrap/css/bootstrap-grid.css')}}">
<link rel="stylesheet" href="{{asset('assets/bootstrap/css/bootstrap-reboot.css')}}">
<!-- CPharma Style -->
<link rel="stylesheet" href="{{asset('assets/cpharma/cpharmastyle.css')}}">
@yield('estilosInternos')
<!-- JS Scritps -->
<!--Filtros, Ordenados y Funciones Internas-->
<script type="text/javascript" src="{{ asset('assets/js/sortTable.js') }}"></script>
<script type="text/javascript" src="{{ asset('assets/js/filter.js') }}">  </script>
<script type="text/javascript" src="{{ asset('assets/js/functions.js') }}"> </script>
<!-- Jquery -->
<script src="{{asset('assets/jquery/jquery-3.3.1.slim.min.js')}}"></script>
<!-- Popper -->
<script src="{{asset('assets/popper/popper.min.js')}}"></script>
<!-- Bootstrap -->
<script src="{{asset('assets/bootstrap/js/bootstrap.js')}}"></script>
<script src="{{asset('assets/bootstrap/js/bootstrap.bundle.js')}}"></script>