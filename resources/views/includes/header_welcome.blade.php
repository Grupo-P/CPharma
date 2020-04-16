<!-- NavBar / Barra Navegacion -->
<nav class="navbar navbar-expand-lg bg-white text-info">
  <a class="navbar-brand CP-Menu-titleIcon" href="{{ url('/cuadreDivisa') }}"><b><i class="fas fa-hand-holding-usd" data-toggle="tooltip" data-placement="top" title="CÁLCULO DE DIVISA"></i></b>
  </a>
  &nbsp;&nbsp;
  <a class="navbar-brand CP-Menu-titleIcon" href="{{ url('/dosificacion') }}"><b><i class="fas fa-prescription" data-toggle="tooltip" data-placement="top" title="CÁLCULO DE DOSIFICACIONES"></i></b>
  </a>
  &nbsp;&nbsp;
  <a class="navbar-brand CP-Menu-titleIcon" href="{{ url('/conversionDosis') }}"><b><i class="fas fa-pills" data-toggle="tooltip" data-placement="top" title="CONVERSIÓN DE DOSIFICACIONES"></i></b>
  </a>
  &nbsp;&nbsp;
  <a class="navbar-brand CP-Menu-titleIcon" href="{{ url('/ConsultaPrecio') }}"><b><i class="fas fa-barcode" data-toggle="tooltip" data-placement="top" title="CONSULTOR DE PRECIOS"></i></b>
  </a>
  &nbsp;&nbsp;
  <a class="navbar-brand CP-Menu-titleIcon" href="{{ url('/falla/create') }}"><b><i class="fas fa-cart-arrow-down" data-toggle="tooltip" data-placement="top" title="REGISTRO DE FALLAS"></i></b>
  </a>
  &nbsp;&nbsp;
  <a class="navbar-brand" href="/ACI">
    <image src="{{ asset('assets/img/ACI.png') }}" data-toggle="tooltip" data-placement="top" title="ACADEMIA DE CAPACITACION INTEGRAL" width="60px"/>
  </a>
</nav>
<script>
  $(document).ready(function(){
      $('[data-toggle="tooltip"]').tooltip();   
  });
  $('#exampleModalCenter').modal('show');
</script>