<!-- NavBar / Barra Navegacion -->
<nav class="navbar navbar-expand-lg bg-white text-info" style="position: relative;">
  <!-- Navbar brand -->
  <a class="navbar-brand text-info CP-title-NavBar bg-white" href="{{ url('/') }}" style="margin-right: 50px;"><b><i class="fas fa-syringe text-success"></i>CPharma</b>
  </a>
<div style="position: absolute; right:0%">
<!-------------------------------------------------------------------------------->
<!-- DASHBOARD -->
  <li class="navbar-brand">
    <a class="btn btn-outline-info textoN" href="{{ url('/home') }}" role="button"> 
      <i class="fas fa-columns"></i> Dashboard
    </a>
  </li>
<!-- DASHBOARD -->
<!-------------------------------------------------------------------------------->
<!-- AGENDA -->
<?php
  if((Auth::user()->departamento != 'VENTAS')&&(Auth::user()->departamento != 'RRHH')&&(Auth::user()->departamento != 'TESORERIA')){
?>
  <div class="btn-group navbar-brand">
    <button type="button" class="btn btn-outline-info dropdown-toggle textoI" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
      <i class="fas fa-book"></i> Agenda
    </button>
    <div class="dropdown-menu">
      <li class="nav-item">
        <a class="nav-link CP-Links-Menu" href="{{ url('/empresa') }}">
          <span data-feather="home"></span>
          <i class="fas fa-industry"></i>
          Empresa <span class="sr-only">(current)</span>
        </a>
      </li>
      <li class="nav-item">
        <a class="nav-link CP-Links-Menu" href="{{ url('/proveedor') }}">
          <span data-feather="home"></span>
          <i class="fas fa-dolly"></i>
          Proveedor <span class="sr-only">(current)</span>
        </a>
      </li>
    </div>
  </div>
<?php
  }
?>
<!-- AGENDA -->
<!-------------------------------------------------------------------------------->
<!-- COMPRAS -->
<?php
  if(Auth::user()->departamento == 'COMPRAS'){
?>
  <div class="btn-group navbar-brand">
    <button type="button" class="btn btn-outline-info dropdown-toggle textoI" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
      <i class="fas fa-chart-bar"></i> Compras
    </button>
    <div class="dropdown-menu">
      <li class="nav-item">
        <a class="nav-link CP-Links-Menu" href="{{ url('/sedes_reporte') }}">
          <span data-feather="home"></span>
          <i class="fas fa-file-invoice"></i>
          Reportes<span class="sr-only">(current)</span>
        </a>
      </li>
      <li class="nav-item">
        <a class="nav-link CP-Links-Menu" href="{{ url('/cartaCompromiso') }}">
          <span data-feather="home"></span>
          <i class="fas fa-list"></i>
          Compromisos<span class="sr-only">(current)</span>
        </a>
      </li>
      <li class="nav-item">
        <a class="nav-link CP-Links-Menu" href="{{ url('/ordenCompra') }}">     
          <span data-feather="home"></span>
          <i class="far fa-file-alt"></i>
          Orden de compra<span class="sr-only">(current)</span>
        </a>
      </li>
      <li class="nav-item">
        <a class="nav-link CP-Links-Menu" href="{{ url('/ConsultorCompra') }}" target="_blank">     
          <span data-feather="home"></span>
          <i class="fas fa-search"></i>
          Buscador de articulos<span class="sr-only">(current)</span>
        </a>
      </li>
    </div>
  </div>
<?php
  }
?>
<!-- COMPRAS -->
<!-------------------------------------------------------------------------------->
<!-- OPERACIONES -->
<?php
  if(Auth::user()->departamento == 'OPERACIONES'){
?>
  <div class="btn-group navbar-brand">
    <button type="button" class="btn btn-outline-info dropdown-toggle textoI" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
      <i class="fas fa-tasks"></i> Operaciones
    </button>
    <div class="dropdown-menu">
      <li class="nav-item">
        <a class="nav-link CP-Links-Menu" href="{{ url('/sedes_reporte') }}">
          <span data-feather="home"></span>
          <i class="fas fa-file-invoice"></i>
          Reportes<span class="sr-only">(current)</span>
        </a>
      </li>
     <li class="nav-item">
        <a class="nav-link CP-Links-Menu" href="{{ url('/reporte8/') }}">     
          <span data-feather="home"></span>
          <i class="fas fa-stamp"></i>
          Troquel (Proveedor)<span class="sr-only">(current)</span>
        </a>
      </li>
      <li class="nav-item">
        <a class="nav-link CP-Links-Menu" href="{{ url('/seccion1/') }}">     
          <span data-feather="home"></span>
          <i class="fas fa-stamp"></i>
          Troquel (Cliente)<span class="sr-only">(current)</span>
        </a>
      </li>
      <li class="nav-item">
        <a class="nav-link CP-Links-Menu" href="{{ url('/reporte23/') }}">    
          <span data-feather="home"></span>
          <i class="fas fa-calendar-alt"></i>
          Fecha de Vencimiento<span class="sr-only">(current)</span>
        </a>
      </li>
      <li class="nav-item">
        <a class="nav-link CP-Links-Menu" href="{{ url('/cartaCompromiso') }}">     
          <span data-feather="home"></span>
          <i class="fas fa-list"></i>
          Compromisos<span class="sr-only">(current)</span>
        </a>
      </li>
      <li class="nav-item">
        <a class="nav-link CP-Links-Menu" href="{{ url('/traslado') }}">     
          <span data-feather="home"></span>
          <i class="fas fa-people-carry"></i>
          Traslado<span class="sr-only">(current)</span>
        </a>
      </li>
      <li class="nav-item">
        <a class="nav-link CP-Links-Menu" href="{{ url('/ordenCompra') }}">     
          <span data-feather="home"></span>
          <i class="far fa-file-alt"></i>
          Orden de compra<span class="sr-only">(current)</span>
        </a>
      </li>
      <li class="nav-item">
        <a class="nav-link CP-Links-Menu" href="{{ url('/tasaVenta') }}">
          <span data-feather="home"></span>
          <i class="fas fa-credit-card"></i>
          Tasa de venta<span class="sr-only">(current)</span>
        </a>
      </li>
      <li class="nav-item">
        <a class="nav-link CP-Links-Menu" href="{{ url('/ConsultorCompra') }}" target="_blank">     
          <span data-feather="home"></span>
          <i class="fas fa-search"></i>
          Buscador de articulos<span class="sr-only">(current)</span>
        </a>
      </li>
      <li class="nav-item">
        <a class="nav-link CP-Links-Menu" href="{{ url('/unidad') }}">
          <span data-feather="home"></span>
          <i class="fas fa-less-than-equal"></i>
          Unidad Minima<span class="sr-only">(current)</span>
        </a>
      </li>
    </div>
  </div>
<?php
  }
?>
<!-- OPERACIONES -->
<!-------------------------------------------------------------------------------->
<!-- ALMACEN -->
<?php
  if(Auth::user()->departamento == 'ALMACEN'){
?>
  <div class="btn-group navbar-brand">
    <button type="button" class="btn btn-outline-info dropdown-toggle textoI" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
      <i class="fas fa-warehouse"></i> Almacen
    </button>
    <div class="dropdown-menu">
      <li class="nav-item">
        <a class="nav-link CP-Links-Menu" href="{{ url('/sedes_reporte') }}">
          <span data-feather="home"></span>
          <i class="fas fa-file-invoice"></i>
          Reportes<span class="sr-only">(current)</span>
        </a>
      </li>
      <li class="nav-item">
        <a class="nav-link CP-Links-Menu" href="{{ url('/traslado') }}">     
          <span data-feather="home"></span>
          <i class="fas fa-people-carry"></i>
          Traslado<span class="sr-only">(current)</span>
        </a>
      </li>
    </div>
  </div>
<?php
  }
?>
<!-- ALMACEN -->
<!-------------------------------------------------------------------------------->
<!-- DEVOLUCIONES -->
<?php
  if(Auth::user()->departamento == 'DEVOLUCIONES'){
?>
  <div class="btn-group navbar-brand">
    <button type="button" class="btn btn-outline-info dropdown-toggle textoI" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
      <i class="fas fa-history"></i> Devoluciones
    </button>
    <div class="dropdown-menu">
      <li class="nav-item">
        <a class="nav-link CP-Links-Menu" href="{{ url('/sedes_reporte') }}">
          <span data-feather="home"></span>
          <i class="fas fa-file-invoice"></i>
          Reportes<span class="sr-only">(current)</span>
        </a>
      </li>
      <li class="nav-item">
        <a class="nav-link CP-Links-Menu" href="{{ url('/cartaCompromiso') }}">   
          <span data-feather="home"></span>
          <i class="fas fa-list"></i>
          Compromisos<span class="sr-only">(current)</span>
        </a>
      </li>
      <li class="nav-item">
        <a class="nav-link CP-Links-Menu" href="{{ url('/traslado') }}">     
          <span data-feather="home"></span>
          <i class="fas fa-people-carry"></i>
          Traslado<span class="sr-only">(current)</span>
        </a>
      </li>
      <li class="nav-item">
        <a class="nav-link CP-Links-Menu" href="{{ url('/ordenCompra') }}">     
          <span data-feather="home"></span>
          <i class="far fa-file-alt"></i>
          Orden de compra<span class="sr-only">(current)</span>
        </a>
      </li>
    </div>
  </div>
<?php
  }
?>
<!-- DEVOLUCIONES -->
<!-------------------------------------------------------------------------------->
<!-- SURTIDO -->
<?php
  if(Auth::user()->departamento == 'SURTIDO'){
?>
  <div class="btn-group navbar-brand">
    <button type="button" class="btn btn-outline-info dropdown-toggle textoI" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
      <i class="fas fa-dolly-flatbed"></i> Surtido
    </button>
    <div class="dropdown-menu">
      <li class="nav-item">
        <a class="nav-link CP-Links-Menu" href="{{ url('/sedes_reporte') }}">
          <span data-feather="home"></span>
          <i class="fas fa-file-invoice"></i>
          Reportes<span class="sr-only">(current)</span>
        </a>
      </li>
      <li class="nav-item">
        <a class="nav-link CP-Links-Menu" href="{{ url('/etiqueta') }}">     
          <span data-feather="home"></span>
          <i class="fas fa-tag"></i>
          Etiquetas<span class="sr-only">(current)</span>
        </a>
      </li>
      <li class="nav-item">
        <a class="nav-link CP-Links-Menu" href="{{ url('/ConsultorCompra') }}" target="_blank">     
          <span data-feather="home"></span>
          <i class="fas fa-search"></i>
          Buscador de articulos<span class="sr-only">(current)</span>
        </a>
      </li>
      <li class="nav-item">
        <a class="nav-link CP-Links-Menu" href="{{ url('/unidad') }}">
          <span data-feather="home"></span>
          <i class="fas fa-less-than-equal"></i>
          Unidad Minima<span class="sr-only">(current)</span>
        </a>
      </li>
      <?php
        if(Auth::user()->role == 'SUPERVISOR'){
      ?>
        <li class="nav-item">
          <a class="nav-link CP-Links-Menu" href="{{ url('/ordenCompra') }}">     
            <span data-feather="home"></span>
            <i class="far fa-file-alt"></i>
            Orden de compra<span class="sr-only">(current)</span>
          </a>
        </li>
        <li class="nav-item">
          <a class="nav-link CP-Links-Menu" href="{{ url('/traslado') }}">     
            <span data-feather="home"></span>
            <i class="fas fa-people-carry"></i>
            Traslado<span class="sr-only">(current)</span>
          </a>
        </li>
        <li class="nav-item">
          <a class="nav-link CP-Links-Menu" href="{{ url('/auditoria') }}">
            <span data-feather="home"></span>
            <i class="fas fa-search"></i>
            Auditoria<span class="sr-only">(current)</span>
          </a>
        </li>
      <?php
        }
      ?>
    </div>
  </div>
<?php
  }
?>
<!-- SURTIDO -->
<!-------------------------------------------------------------------------------->
<!-- VENTAS -->
<?php
  if(Auth::user()->departamento == 'VENTAS'){
?>
  <div class="btn-group navbar-brand">
    <button type="button" class="btn btn-outline-info dropdown-toggle textoI" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
      <i class="fas fa-cash-register"></i> Ventas
    </button>
    <div class="dropdown-menu">
      <li class="nav-item">
        <a class="nav-link CP-Links-Menu" href="{{ url('/tasaVenta') }}">
          <span data-feather="home"></span>
          <i class="fas fa-credit-card"></i>
          Tasa de venta<span class="sr-only">(current)</span>
        </a>
      </li>
    </div>
  </div>
<?php
  }
?>
<!-- VENTAS -->
<!-------------------------------------------------------------------------------->
<!-- RECEPCION -->
<?php
  if(Auth::user()->departamento == 'RECEPCION'){
?>
  <div class="btn-group navbar-brand">
    <button type="button" class="btn btn-outline-info dropdown-toggle textoI" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
      <i class="fas fa-dolly-flatbed"></i> Recepcion
    </button>
    <div class="dropdown-menu">
      <li class="nav-item">
        <a class="nav-link CP-Links-Menu" href="{{ url('/ordenCompra') }}">     
          <span data-feather="home"></span>
          <i class="far fa-file-alt"></i>
          Orden de compra<span class="sr-only">(current)</span>
        </a>
      </li>
    </div>
  </div>
<?php
  }
?>
<!-- RECEPCION -->
<!-------------------------------------------------------------------------------->
<!-- RRHH -->
<?php
  if(Auth::user()->departamento == 'RRHH'){
?>
  <div class="btn-group navbar-brand">
    <button type="button" class="btn btn-outline-info dropdown-toggle textoI" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
      <i class="fas fa-user-circle"></i> RRHH
    </button>
    <div class="dropdown-menu">
      <li class="nav-item">
        <a class="nav-link CP-Links-Menu" href="/procesos_candidatos">
          <span data-feather="home"></span>
          <i class="fas fa-cogs"></i>
          Fases y procesos<span class="sr-only">(current)</span>
        </a>
      </li>

      <li class="nav-item">
        <a class="nav-link CP-Links-Menu" href="/fases">
          <span data-feather="home"></span>
          <i class="fas fa-sort-amount-up-alt"></i>
          Fases<span class="sr-only">(current)</span>
        </a>
      </li>

      <li class="nav-item">
        <a class="nav-link CP-Links-Menu" href="/vacantes">
          <span data-feather="home"></span>
          <i class="fas fa-user-plus"></i>
          Vacantes<span class="sr-only">(current)</span>
        </a>
      </li>

      <li class="nav-item">
        <a class="nav-link CP-Links-Menu" href="/convocatoria">
          <span data-feather="home"></span>
        <i class="fas fa-user-edit"></i>
          Convocatoria<span class="sr-only">(current)</span>
        </a>
      </li>

      <li class="nav-item">
        <a class="nav-link CP-Links-Menu" href="/candidatos">
          <span data-feather="home"></span>
          <i class="fas fa-user-check"></i>
          Candidatos<span class="sr-only">(current)</span>
        </a>
      </li>

      <li class="nav-item">
        <a class="nav-link CP-Links-Menu" href="/pruebas">
          <span data-feather="home"></span>
          <i class="fas fa-tasks"></i>
          Pruebas<span class="sr-only">(current)</span>
        </a>
      </li>

      <li class="nav-item">
        <a class="nav-link CP-Links-Menu" href="/entrevistas">
          <span data-feather="home"></span>
          <i class="fas fa-users"></i>
          Entrevistas<span class="sr-only">(current)</span>
        </a>
      </li>

      <li class="nav-item">
        <a class="nav-link CP-Links-Menu" href="/practicas">
          <span data-feather="home"></span>
          <i class="fas fa-users-cog"></i>
          Prácticas<span class="sr-only">(current)</span>
        </a>
      </li>
      
      <li class="nav-item">
        <a class="nav-link CP-Links-Menu" href="/contactos">
          <span data-feather="home"></span>
         <i class="fas fa-phone"></i>
          Contactos de empresas<span class="sr-only">(current)</span>
        </a>
      </li>

      <li class="nav-item">
        <a class="nav-link CP-Links-Menu" href="/examenesm">
          <span data-feather="home"></span>
          <i class="fas fa-user-md"></i>
          Examenes médicos<span class="sr-only">(current)</span>
        </a>
      </li>

      <li class="nav-item">
        <a class="nav-link CP-Links-Menu" href="/empresaReferencias">
          <span data-feather="home"></span>
          <i class="far fa-address-card"></i>
          Empresas de referencias<span class="sr-only">(current)</span>
        </a>
      </li>
      
      <li class="nav-item">
        <a class="nav-link CP-Links-Menu" href="/laboratorio">
          <span data-feather="home"></span>
         <i class="fas fa-vials"></i>
          Laboratorios<span class="sr-only">(current)</span>
        </a>
      </li>
    </div>
  </div>
<?php
  }
?>
<!-- RRHH -->
<!-------------------------------------------------------------------------------->
<!-- ADMINISTRACION -->
<?php
  if(Auth::user()->departamento == 'ADMINISTRACION'){
?>
  <div class="btn-group navbar-brand">
    <button type="button" class="btn btn-outline-info dropdown-toggle textoI" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
      <i class="fas fa-calculator"></i> Administracion
    </button>
    <div class="dropdown-menu">
      <li class="nav-item">
        <a class="nav-link CP-Links-Menu" href="{{ url('/sedes_reporte') }}">
          <span data-feather="home"></span>
          <i class="fas fa-file-invoice"></i>
          Reportes<span class="sr-only">(current)</span>
        </a>
      </li>
      <li class="nav-item">
        <a class="nav-link CP-Links-Menu" href="{{ url('/cartaCompromiso') }}">  
          <span data-feather="home"></span>
          <i class="fas fa-list"></i>
          Compromisos<span class="sr-only">(current)</span>
        </a>
      </li>
      <li class="nav-item">
        <a class="nav-link CP-Links-Menu" href="{{ url('/traslado') }}">     
          <span data-feather="home"></span>
          <i class="fas fa-people-carry"></i>
          Traslado<span class="sr-only">(current)</span>
        </a>
      </li>
      <li class="nav-item">
        <a class="nav-link CP-Links-Menu" href="{{ url('/ordenCompra') }}">     
          <span data-feather="home"></span>
          <i class="far fa-file-alt"></i>
          Orden de compra<span class="sr-only">(current)</span>
        </a>
      </li>

      <li class="nav-item">
        <a class="nav-link CP-Links-Menu" href="{{ url('/movimientos?tasa_ventas_id=1') }}">     
          <span data-feather="home"></span>
          <i class="fas fa-balance-scale-left"></i>
          Movimientos en bolívares<span class="sr-only">(current)</span>
        </a>
      </li>

      <li class="nav-item">
        <a class="nav-link CP-Links-Menu" href="{{ url('/movimientos?tasa_ventas_id=2') }}">     
          <span data-feather="home"></span>
          <i class="fas fa-balance-scale"></i>
          Movimientos en dolares<span class="sr-only">(current)</span>
        </a>
      </li>

      <li class="nav-item">
        <a class="nav-link CP-Links-Menu" href="{{ url('/dolar') }}">
          <span data-feather="home"></span>
          <i class="fas fa-money-bill-alt"></i>
          Tasa de mercado<span class="sr-only">(current)</span>
        </a>
      </li>
      <li class="nav-item">
        <a class="nav-link CP-Links-Menu" href="{{ url('/diascero') }}">      
          <span data-feather="home"></span>
          <i class="far fa-calendar"></i>
          Dias en cero<span class="sr-only">(current)</span>
        </a>
      </li>
      <li class="nav-item">
        <a class="nav-link CP-Links-Menu" href="{{ url('/productoscaida') }}">      
          <span data-feather="home"></span>
          <i class="fas fa-chart-line"></i>
          Productos en Caida<span class="sr-only">(current)</span>
        </a>
      </li>
      <li class="nav-item">
        <a class="nav-link CP-Links-Menu" href="{{ url('/CapturaEtiqueta') }}">     
          <span data-feather="home"></span>
          <i class="fas fa-tag"></i>
          Captura Etiquetas<span class="sr-only">(current)</span>
        </a>
      </li>
    </div>
  </div>
<?php
  }
?>
<!-- ADMINISTRACION -->
<!-------------------------------------------------------------------------------->
<!-- LÍDER DE TIENDA -->
<?php
  if(Auth::user()->departamento == 'LÍDER DE TIENDA'){
?>
  <div class="btn-group navbar-brand">
    <button type="button" class="btn btn-outline-info dropdown-toggle textoI" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
      <i class="fas fa-store"></i> Lider de tienda
    </button>
    <div class="dropdown-menu">
      <li class="nav-item">
        <a class="nav-link CP-Links-Menu" href="{{ url('/sedes_reporte') }}">
          <span data-feather="home"></span>
          <i class="fas fa-file-invoice"></i>
          Reportes<span class="sr-only">(current)</span>
        </a>
      </li>
      <li class="nav-item">
        <a class="nav-link CP-Links-Menu" href="{{ url('/reporte8/') }}">     
          <span data-feather="home"></span>
          <i class="fas fa-stamp"></i>
          Troquel (Proveedor)<span class="sr-only">(current)</span>
        </a>
      </li>
      <li class="nav-item">
        <a class="nav-link CP-Links-Menu" href="{{ url('/seccion1/') }}">     
          <span data-feather="home"></span>
          <i class="fas fa-stamp"></i>
          Troquel (Cliente)<span class="sr-only">(current)</span>
        </a>
      </li>
      <li class="nav-item">
        <a class="nav-link CP-Links-Menu" href="{{ url('/cartaCompromiso') }}">     
          <span data-feather="home"></span>
          <i class="fas fa-list"></i>
          Compromisos<span class="sr-only">(current)</span>
        </a>
      </li>
      <li class="nav-item">
        <a class="nav-link CP-Links-Menu" href="{{ url('/etiqueta') }}">     
          <span data-feather="home"></span>
          <i class="fas fa-tag"></i>
          Etiquetas<span class="sr-only">(current)</span>
        </a>
      </li>
      <li class="nav-item">
        <a class="nav-link CP-Links-Menu" href="{{ url('/traslado') }}">     
          <span data-feather="home"></span>
          <i class="fas fa-people-carry"></i>
          Traslado<span class="sr-only">(current)</span>
        </a>
      </li>
      <li class="nav-item">
        <a class="nav-link CP-Links-Menu" href="{{ url('/tasaVenta') }}">
          <span data-feather="home"></span>
          <i class="fas fa-credit-card"></i>
          Tasa de venta<span class="sr-only">(current)</span>
        </a>
      </li>
    </div>
  </div>
<?php
  }
?>
<!-- LÍDER DE TIENDA -->

<!-------------------------------------------------------------------------------->
<!-- GERENCIA -->
<?php
  if(Auth::user()->departamento == 'GERENCIA'){
?>
  <div class="btn-group navbar-brand">
    <button type="button" class="btn btn-outline-info dropdown-toggle textoI" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
      <i class="fas fa-user-tie"></i> Gerencia
    </button>
    <div class="dropdown-menu">
      <li class="nav-item">
        <a class="nav-link CP-Links-Menu" href="{{ url('/ArticulosExcel') }}">
          <span data-feather="home"></span>
          <i class="fas fa-file-excel"></i>
          Articulos Pagina Web<span class="sr-only">(current)</span>
        </a>
      </li>
      <li class="nav-item">
        <a class="nav-link CP-Links-Menu" href="{{ url('/sedes_reporte') }}">
          <span data-feather="home"></span>
          <i class="fas fa-file-invoice"></i>
          Reportes<span class="sr-only">(current)</span>
        </a>
      </li>
      <li class="nav-item">
        <a class="nav-link CP-Links-Menu" href="{{ url('/reporte8/') }}">     
          <span data-feather="home"></span>
          <i class="fas fa-stamp"></i>
          Troquel (Proveedor)<span class="sr-only">(current)</span>
        </a>
      </li>
      <li class="nav-item">
        <a class="nav-link CP-Links-Menu" href="{{ url('/seccion1/') }}">     
          <span data-feather="home"></span>
          <i class="fas fa-stamp"></i>
          Troquel (Cliente)<span class="sr-only">(current)</span>
        </a>
      </li>
      <li class="nav-item">
        <a class="nav-link CP-Links-Menu" href="{{ url('/reporte23/') }}">    
          <span data-feather="home"></span>
          <i class="fas fa-calendar-alt"></i>
          Fecha de Vencimiento<span class="sr-only">(current)</span>
        </a>
      </li>
      <li class="nav-item">
        <a class="nav-link CP-Links-Menu" href="{{ url('/cartaCompromiso') }}">     
          <span data-feather="home"></span>
          <i class="fas fa-list"></i>
          Compromisos<span class="sr-only">(current)</span>
        </a>
      </li>
      <li class="nav-item">
        <a class="nav-link CP-Links-Menu" href="{{ url('/etiqueta') }}">     
          <span data-feather="home"></span>
          <i class="fas fa-tag"></i>
          Etiquetas<span class="sr-only">(current)</span>
        </a>
      </li>
      <li class="nav-item">
        <a class="nav-link CP-Links-Menu" href="{{ url('/traslado') }}">     
          <span data-feather="home"></span>
          <i class="fas fa-people-carry"></i>
          Traslado<span class="sr-only">(current)</span>
        </a>
      </li>
      <li class="nav-item">
        <a class="nav-link CP-Links-Menu" href="{{ url('/inventario') }}">  
          <span data-feather="home"></span>
          <i class="fa fa-boxes"></i>
          Inventario<span class="sr-only">(current)</span>
        </a>
      </li>
      <li class="nav-item">
        <a class="nav-link CP-Links-Menu" href="{{ url('/ordenCompra') }}">     
          <span data-feather="home"></span>
          <i class="far fa-file-alt"></i>
          Orden de compra<span class="sr-only">(current)</span>
        </a>
      </li>
      <li class="nav-item">
        <a class="nav-link CP-Links-Menu" href="{{ url('/ConsultorCompra') }}" target="_blank">     
          <span data-feather="home"></span>
          <i class="fas fa-search"></i>
          Buscador de articulos<span class="sr-only">(current)</span>
        </a>
      </li>
      <li class="nav-item">
        <a class="nav-link CP-Links-Menu" href="{{ url('/movimientos?tasa_ventas_id=1') }}">     
          <span data-feather="home"></span>
          <i class="fas fa-balance-scale-left"></i>
          Movimientos en bolívares<span class="sr-only">(current)</span>
        </a>
      </li>

      <li class="nav-item">
        <a class="nav-link CP-Links-Menu" href="{{ url('/movimientos?tasa_ventas_id=2') }}">     
          <span data-feather="home"></span>
          <i class="fas fa-balance-scale"></i>
          Movimientos en dolares<span class="sr-only">(current)</span>
        </a>
      </li>

      <li class="nav-item">
        <a class="nav-link CP-Links-Menu" href="{{ url('/diferidos?tasa_ventas_id=1') }}">     
          <span data-feather="home"></span>
          <i class="fas fa-lock"></i>
          Diferidos en bolívares<span class="sr-only">(current)</span>
        </a>
      </li>

      <li class="nav-item">
        <a class="nav-link CP-Links-Menu" href="{{ url('/diferidos?tasa_ventas_id=2') }}">     
          <span data-feather="home"></span>
          <i class="fas fa-lock"></i>
          Diferidos en dolares<span class="sr-only">(current)</span>
        </a>
      </li>

      <li class="nav-item">
        <a class="nav-link CP-Links-Menu" href="{{ url('/tasaVenta') }}">
          <span data-feather="home"></span>
          <i class="fas fa-credit-card"></i>
          Tasa de venta<span class="sr-only">(current)</span>
        </a>
      </li>
      <li class="nav-item">
        <a class="nav-link CP-Links-Menu" href="{{ url('/dolar') }}">
          <span data-feather="home"></span>
          <i class="fas fa-money-bill-alt"></i>
          Tasa de mercado<span class="sr-only">(current)</span>
        </a>
      </li>
      <li class="nav-item">
        <a class="nav-link CP-Links-Menu" href="{{ url('/usuario') }}">
          <span data-feather="home"></span>
          <i class="fas fa-user"></i>
          Usuario<span class="sr-only">(current)</span>
        </a>
      </li>
      <li class="nav-item">
        <a class="nav-link CP-Links-Menu" href="{{ url('/diascero') }}">      
          <span data-feather="home"></span>
          <i class="far fa-calendar"></i>
          Dias en cero<span class="sr-only">(current)</span>
        </a>
      </li>
      <li class="nav-item">
        <a class="nav-link CP-Links-Menu" href="{{ url('/productoscaida') }}">      
          <span data-feather="home"></span>
          <i class="fas fa-chart-line"></i>
          Productos en Caida<span class="sr-only">(current)</span>
        </a>
      </li>
      <li class="nav-item">
        <a class="nav-link CP-Links-Menu" href="{{ url('/CapturaEtiqueta') }}">     
          <span data-feather="home"></span>
          <i class="fas fa-tag"></i>
          Captura Etiquetas<span class="sr-only">(current)</span>
        </a>
      </li>
      <li class="nav-item">
        <a class="nav-link CP-Links-Menu" href="{{ url('/auditoria') }}">      
          <span data-feather="home"></span>
          <i class="fas fa-search"></i>
          Auditoria<span class="sr-only">(current)</span>
        </a>
      </li>
    </div>
  </div>
<?php
  }
?>
<!-- GERENCIA -->

<!-- TESORERIA -->
<?php
  if(Auth::user()->departamento == 'TESORERIA') {
?>
  <div class="btn-group navbar-brand">
    <button type="button" class="btn btn-outline-info dropdown-toggle textoI" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
      <i class="fas fa-donate"></i> Tesorería
    </button>
    <div class="dropdown-menu">
      <li class="nav-item">
        <a class="nav-link CP-Links-Menu" href="{{ url('/movimientos?tasa_ventas_id=1') }}">     
          <span data-feather="home"></span>
          <i class="fas fa-balance-scale-left"></i>
          Movimientos en bolívares<span class="sr-only">(current)</span>
        </a>
      </li>

      <li class="nav-item">
        <a class="nav-link CP-Links-Menu" href="{{ url('/movimientos?tasa_ventas_id=2') }}">     
          <span data-feather="home"></span>
          <i class="fas fa-balance-scale"></i>
          Movimientos en dolares<span class="sr-only">(current)</span>
        </a>
      </li>

      <li class="nav-item">
        <a class="nav-link CP-Links-Menu" href="{{ url('/diferidos?tasa_ventas_id=1') }}">     
          <span data-feather="home"></span>
          <i class="fas fa-lock"></i>
          Diferidos en bolívares<span class="sr-only">(current)</span>
        </a>
      </li>

      <li class="nav-item">
        <a class="nav-link CP-Links-Menu" href="{{ url('/diferidos?tasa_ventas_id=2') }}">     
          <span data-feather="home"></span>
          <i class="fas fa-lock"></i>
          Diferidos en dolares<span class="sr-only">(current)</span>
        </a>
      </li>

      <li class="nav-item">
        <a class="nav-link CP-Links-Menu" href="{{ url('/tasaVenta') }}">
          <span data-feather="home"></span>
          <i class="fas fa-credit-card"></i>
          Tasa de venta<span class="sr-only">(current)</span>
        </a>
      </li>
    </div>
  </div>
<?php
  }
?>
<!-- TESORERIA -->

<!-------------------------------------------------------------------------------->
<!-- INVENTARIO -->
<?php
  if(Auth::user()->departamento == 'INVENTARIO'){
?>
  <div class="btn-group navbar-brand">
    <button type="button" class="btn btn-outline-info dropdown-toggle textoI" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
      <i class="fas fa-dolly-flatbed"></i> Inventario
    </button>
    <div class="dropdown-menu">
      <li class="nav-item">
        <a class="nav-link CP-Links-Menu" href="{{ url('/inventario') }}">  
          <span data-feather="home"></span>
          <i class="fa fa-boxes"></i>
          Inventario<span class="sr-only">(current)</span>
        </a>
      </li>
      <li class="nav-item">
        <a class="nav-link CP-Links-Menu" href="{{ url('/ConsultorCompra') }}" target="_blank">
          <span data-feather="home"></span>
          <i class="fas fa-search"></i>
          Buscador de articulos<span class="sr-only">(current)</span>
        </a>
      </li>
      <li class="nav-item">
        <a class="nav-link CP-Links-Menu" href="{{ url('/traslado') }}">     
          <span data-feather="home"></span>
          <i class="fas fa-people-carry"></i>
          Traslado<span class="sr-only">(current)</span>
        </a>
      </li> 
      <li class="nav-item">
        <a class="nav-link CP-Links-Menu" href="{{ url('/ordenCompra') }}">     
          <span data-feather="home"></span>
          <i class="far fa-file-alt"></i>
          Orden de compra<span class="sr-only">(current)</span>
        </a>
      </li> 
    </div>
  </div>
<?php
  }
?>
<!-- INVENTARIO -->
<!-------------------------------------------------------------------------------->

<!------------------------------------------------------------------------------->
<!-- TECNOLOGIA -->
<?php
  if(Auth::user()->departamento == 'TECNOLOGIA'){
?>
  <div class="btn-group navbar-brand">
    <button type="button" class="btn btn-outline-info dropdown-toggle textoI" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
      <i class="fas fa-user-cog"></i> Tecnologia
    </button>
    <div class="dropdown-menu" style="width:200px;">
      <li class="nav-item">
        <a class="nav-link CP-Links-Menu" href="{{ url('/ArticulosExcel') }}">
          <span data-feather="home"></span>
          <i class="fas fa-file-excel"></i>
          Articulos Pagina Web<span class="sr-only">(current)</span>
        </a>
      </li>
      <li class="nav-item">
        <a class="nav-link CP-Links-Menu" href="{{ url('/sedes_reporte') }}">
          <span data-feather="home"></span>
          <i class="fas fa-file-invoice"></i>
          Reportes<span class="sr-only">(current)</span>
        </a>
      </li>
      <li class="nav-item">
        <a class="nav-link CP-Links-Menu" href="{{ url('/reporte8/') }}">     
          <span data-feather="home"></span>
          <i class="fas fa-stamp"></i>
          Troquel (Proveedor)<span class="sr-only">(current)</span>
        </a>
      </li>
      <li class="nav-item">
        <a class="nav-link CP-Links-Menu" href="{{ url('/seccion1/') }}">     
          <span data-feather="home"></span>
          <i class="fas fa-stamp"></i>
          Troquel (Cliente)<span class="sr-only">(current)</span>
        </a>
      </li>
      <li class="nav-item">
        <a class="nav-link CP-Links-Menu" href="{{ url('/unidad') }}">
          <span data-feather="home"></span>
          <i class="fas fa-less-than-equal"></i>
          Unidad Minima<span class="sr-only">(current)</span>
        </a>
      </li>
      <li class="nav-item">
        <a class="nav-link CP-Links-Menu" href="{{ url('/reporte23/') }}">    
          <span data-feather="home"></span>
          <i class="fas fa-calendar-alt"></i>
          Fecha de Vencimiento<span class="sr-only">(current)</span>
        </a>
      </li>
      <li class="nav-item">
        <a class="nav-link CP-Links-Menu" href="{{ url('/cartaCompromiso') }}">     
          <span data-feather="home"></span>
          <i class="fas fa-list"></i>
          Compromisos<span class="sr-only">(current)</span>
        </a>
      </li>
      <li class="nav-item">
        <a class="nav-link CP-Links-Menu" href="{{ url('/etiqueta') }}">     
          <span data-feather="home"></span>
          <i class="fas fa-tag"></i>
          Etiquetas<span class="sr-only">(current)</span>
        </a>
      </li>
      <li class="nav-item">
        <a class="nav-link CP-Links-Menu" href="{{ url('/traslado') }}">     
          <span data-feather="home"></span>
          <i class="fas fa-people-carry"></i>
          Traslado<span class="sr-only">(current)</span>
        </a>
      </li>
      <li class="nav-item">
        <a class="nav-link CP-Links-Menu" href="{{ url('/inventario') }}">  
          <span data-feather="home"></span>
          <i class="fa fa-boxes"></i>
          Inventario<span class="sr-only">(current)</span>
        </a>
      </li>
      <li class="nav-item">
        <a class="nav-link CP-Links-Menu" href="{{ url('/ordenCompra') }}">     
          <span data-feather="home"></span>
          <i class="far fa-file-alt"></i>
          Orden de compra<span class="sr-only">(current)</span>
        </a>
      </li>
      <li class="nav-item">
        <a class="nav-link CP-Links-Menu" href="{{ url('/ConsultorCompra') }}" target="_blank">     
          <span data-feather="home"></span>
          <i class="fas fa-search"></i>
          Buscador de articulos<span class="sr-only">(current)</span>
        </a>
      </li>
      <li class="nav-item">
        <a class="nav-link CP-Links-Menu" href="{{ url('/tasaVenta') }}">
          <span data-feather="home"></span>
          <i class="fas fa-credit-card"></i>
          Tasa de venta<span class="sr-only">(current)</span>
        </a>
      </li>
      <li class="nav-item">
        <a class="nav-link CP-Links-Menu" href="{{ url('/dolar') }}">
          <span data-feather="home"></span>
          <i class="fas fa-money-bill-alt"></i>
          Tasa de mercado<span class="sr-only">(current)</span>
        </a>
      </li>
      <li class="nav-item">
        <a class="nav-link CP-Links-Menu" href="{{ url('/usuario') }}">
          <span data-feather="home"></span>
          <i class="fas fa-user"></i>
          Usuario<span class="sr-only">(current)</span>
        </a>
      </li>
      <li class="nav-item">
        <a class="nav-link CP-Links-Menu" href="{{ url('/rol') }}">
          <span data-feather="home"></span>
          <i class="fas fa-user-circle"></i>
          Roles<span class="sr-only">(current)</span>
        </a>
      </li>
      <li class="nav-item">
        <a class="nav-link CP-Links-Menu" href="{{ url('/departamento') }}">
          <span data-feather="home"></span>
          <i class="fab fa-buffer"></i>
          Departamento<span class="sr-only">(current)</span>
        </a>
      </li>
      <li class="nav-item">
        <a class="nav-link CP-Links-Menu" href="{{ url('/sede') }}">
          <span data-feather="home"></span>
          <i class="fas fa-store-alt"></i>
          Sede<span class="sr-only">(current)</span>
        </a>
      </li>
      <li class="nav-item">
        <a class="nav-link CP-Links-Menu" href="{{ url('/conexion') }}">
          <span data-feather="home"></span>
          <i class="fas fa-network-wired"></i>
          Conexion<span class="sr-only">(current)</span>
        </a>
      </li>
      <li class="nav-item">
        <a class="nav-link CP-Links-Menu" href="{{ url('/configuracion') }}">      
          <span data-feather="home"></span>
          <i class="fas fa-cogs"></i>
          Configuracion<span class="sr-only">(current)</span>
        </a>
      </li>
      <li class="nav-item">
        <a class="nav-link CP-Links-Menu" href="{{ url('/diascero') }}">      
          <span data-feather="home"></span>
          <i class="far fa-calendar"></i>
          Dias en cero<span class="sr-only">(current)</span>
        </a>
      </li>
      <li class="nav-item">
        <a class="nav-link CP-Links-Menu" href="{{ url('/productoscaida') }}">      
          <span data-feather="home"></span>
          <i class="fas fa-chart-line"></i>
          Productos en Caida<span class="sr-only">(current)</span>
        </a>
      </li>
      <li class="nav-item">
        <a class="nav-link CP-Links-Menu" href="{{ url('/CapturaEtiqueta') }}">     
          <span data-feather="home"></span>
          <i class="fas fa-tag"></i>
          Captura Etiquetas<span class="sr-only">(current)</span>
        </a>
      </li>
      <li class="nav-item">
        <a class="nav-link CP-Links-Menu" href="{{ url('/auditoria') }}">      
          <span data-feather="home"></span>
          <i class="fas fa-search"></i>
          Auditoria<span class="sr-only">(current)</span>
        </a>
      </li>
    </div>
  </div>
<?php
  }
?>
<!-- TECNOLOGIA -->
<!-------------------------------------------------------------------------------->
<!-- DEVELOPER -->
<?php
  if( 
      (
        Auth::user()->departamento == 'TECNOLOGIA' 
        ||Auth::user()->departamento == 'GERENCIA'
      )
    && Auth::user()->role == 'DEVELOPER'
  ){
?>
  <div class="btn-group navbar-brand">
    <button type="button" class="btn btn-outline-info dropdown-toggle textoI" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
      <i class="fas fa-user-secret"></i> Developer
    </button>   
    <div class="dropdown-menu">      
      <li class="nav-item">
        <a class="nav-link CP-Links-Menu" href="{{ url('reporte30') }}">  
          <span data-feather="home"></span>
          <i class="fa fa-file-alt"></i>
          Registro de Compras<span class="sr-only">(current)</span>
        </a>
      </li>     
    </div>
  </div>
<?php
  }
?>
<!-- DEVELOPER -->
<!-------------------------------------------------------------------------------->
<!-- SALIR -->
  <li class="navbar-brand">
    <a class="navbar-brand textoN btn btn-outline-info" href="{{ route('logout') }}" role="button" onclick="event.preventDefault();
                       document.getElementById('logout-form').submit();">
      <span data-feather="home"></span>
      <i class="fas fa-power-off"></i>
      {{Auth::user()->name}}<span class="sr-only">(current)</span>
    </a>
    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
     @csrf
    </form>
  </li>
<!-- SALIR -->
<!-------------------------------------------------------------------------------->
</div>
</nav>
<hr class="row align-items-start col-12">