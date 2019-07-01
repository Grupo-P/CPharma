<!-- NavBar / Barra Navegacion -->
<!--Navbar-->
<style>
  .titleIcon {
      font-size: 50px;
      color:#5bc0de;
  }

	.titleIcon:hover {
		color:#5cb85c;
	}
</style>

<nav class="navbar navbar-expand-lg bg-white text-info">

  <!-- Navbar brand -->
  <a class="navbar-brand titleIcon" href="{{ url('/cuadreDivisa') }}"><b><i class="fas fa-hand-holding-usd" data-toggle="tooltip" data-placement="top" title="CÁLCULO DE DIVISA"></i></b>
  </a>
  &nbsp;&nbsp;
  <a class="navbar-brand titleIcon" href="{{ url('/dosificacion') }}"><b><i class="fas fa-prescription" data-toggle="tooltip" data-placement="top" title="CÁLCULO DE DOSIFICACIONES"></i></b>
  </a>
  &nbsp;&nbsp;
  <a class="navbar-brand titleIcon" href="{{ url('/conversionDosis') }}"><b><i class="fas fa-pills" data-toggle="tooltip" data-placement="top" title="CONVERSIÓN DE DOSIFICACIONES"></i></b>
  </a>
  &nbsp;&nbsp;
  <a class="navbar-brand" href="/ACI"><b><image src="{{ asset('assets/img/ACI.png') }}" data-toggle="tooltip" data-placement="top" title="ACADEMIA DE CAPACITACION INTEGRAL" width="10%"></i></b>
  </a>
  
  <!-- Collapsible content -->
</nav>
<!--/.Navbar-->

<script>
  $(document).ready(function(){
      $('[data-toggle="tooltip"]').tooltip();   
  });
  $('#exampleModalCenter').modal('show');
</script>