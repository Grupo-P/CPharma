@extends('layouts.model')

@section('title')
  Dashboard
@endsection

@section('content')
  <?php
    use compras\Configuracion;
    include(app_path().'\functions\config.php');
    include(app_path().'\functions\functions.php');
    include(app_path().'\functions\querys_mysql.php');
    include(app_path().'\functions\querys_sqlserver.php');

    $CPharmaVersion = ' '.Version;

    //-------------------- VARIABLES COMPRAS --------------------//
    $empresas = DB::table('empresas')->count();
    $proveedores = DB::table('proveedors')->count();
    $usuarios = DB::table('users')->count();
    $dolar = DB::table('dolars')->count();

    //-------------------- VARIABLES RRHH --------------------//
    $candidatos = DB::table('rh_candidatos')->count();
    $vacantes = DB::table('rh_vacantes')->count();
    $entrevistas = DB::table('rh_entrevistas')->count();
    $pruebas = DB::table('rh_pruebas')->count();
    $entrevistas = DB::table('rh_entrevistas')->count();
    $examenesm = DB::table('rh_examenes')->count();
    $empresaReferencias = DB::table('rh_empresaRef')->count();
    $laboratorios = DB::table('rh_laboratorio')->count();
    $contactos = DB::table('rh_contactos_empresas')->count();
    $convocatoria = DB::table('rh_convocatoria')->count();
    $fases = DB::table('rh_fases')->count();
    $procesos_candidatos = DB::table('rh_candidatos')
      ->where('estatus', '=', 'EN_PROCESO')
        ->orWhere('estatus', '=', 'POSTULADO')
      ->count();
    $practicas = DB::table('rh_practicas')->count();

    //-------------------- VARIABLES TESORERIA --------------------//
    $movimientosBs = DB::table('ts_movimientos')
    ->where('tasa_ventas_id', 1)
    ->whereNull('diferido')
    ->count();

    $diferidosBs = DB::table('ts_movimientos')
    ->where('tasa_ventas_id', 1)
    ->whereNotNull('diferido')
    ->count();

    $movimientosDs = DB::table('ts_movimientos')
    ->where('tasa_ventas_id', 2)
    ->whereNull('diferido')
    ->count();

    $diferidosDs = DB::table('ts_movimientos')
    ->where('tasa_ventas_id', 2)
    ->whereNotNull('diferido')
    ->count();

  /*TASA DOLAR VENTA*/
    $Tasa = DB::table('tasa_ventas')->where('moneda', 'Dolar')->value('tasa');

    if( (!empty($Tasa)) ) {
      $TV = $Tasa;
      $Tasa = number_format($Tasa,2,"," ,"." );
    }
    else {
      $TV = 0.00;
      $Tasa = number_format(0.00,2,"," ,"." );
    }

    $tasaVenta = DB::table('tasa_ventas')->where('moneda', 'Dolar')->value('updated_at');

    if( (!empty($tasaVenta)) ) {
      $tasaVenta = new DateTime($tasaVenta);
      $tasaVenta = $tasaVenta->format("d-m-Y h:i:s a");
    }
    else {
      $tasaVenta = '';
    }
  /*TASA DOLAR VENTA*/

  /*TASA DOLAR MERCADO*/
    $FechaTasaMercado =
    DB::table('dolars')
    ->select('updated_at')
    ->orderBy('fecha','desc')
    ->take(1)->get();

    if( (!empty($FechaTasaMercado[0])) ) {
      $FechaTasaMercado = ($FechaTasaMercado[0]->updated_at);
      $FechaTasaMercado = new DateTime($FechaTasaMercado);
      $FechaTasaMercado = $FechaTasaMercado->format('d-m-Y h:i:s a');
    }
    else {
      $FechaTasaMercado = '';
    }

    $TasaMercado =
    DB::table('dolars')
    ->select('tasa')
    ->orderBy('fecha','desc')
    ->take(1)->get();

    if( (!empty($TasaMercado[0])) ) {
      $TM = $TasaMercado[0]->tasa;
      $TasaMercado = ($TasaMercado[0]->tasa);
      $TasaMercado = number_format($TasaMercado,2,"," ,"." );
    }
    else {
      $TM = 0.00;
      $TasaMercado = number_format(0.00,2,"," ,"." );
    }
  /*TASA DOLAR MERCADO*/

  /*REPORTES MAS USADOS Y REPORTES SUGERIDOS*/
    $FHoy = date("Y-m-d");
    $FAyer = date("Y-m-d",strtotime($FHoy."-1 days"));

    $auditorReporte =
    DB::table('auditorias')
    ->select('registro')
    ->groupBy('registro')
    ->orderBy(DB::raw('count(*)'),'desc')
    ->where('tabla','reporte')
    ->where('updated_at', '>',$FAyer)
    ->take(1)->get();

    $auditorUser =
    DB::table('auditorias')
    ->select('user')
    ->groupBy('user')
    ->orderBy(DB::raw('count(*)'),'desc')
    ->where('updated_at', '>',$FAyer)
    ->take(1)->get();

    $usuario = Auth::user()->name;
    $auditorReporteFavorito =
    DB::table('auditorias')
    ->select('registro')
    ->groupBy('registro')
    ->orderBy(DB::raw('count(*)'),'desc')
    ->where('tabla','reporte')
    ->where('user',$usuario)
    ->take(2)->get();
  /*REPORTES MAS USADOS Y REPORTES SUGERIDOS*/
  ?>

  <h1 class="h5 text-info">
    <i class="fas fa-columns"></i>
    Dashboard
  </h1>
  <hr class="row align-items-start col-12">

<?php
  use compras\Departamento;
  $reportes = 0;
  $departamento =
  DB::table('departamentos')
  ->select('descripcion')
  ->where('nombre', '=', Auth::user()->departamento )
    ->get();

    if( (!empty($departamento[0])) ) {
      $descripcion = ($departamento[0]->descripcion);
      $reportes = explode(",", $descripcion);
    }

  /*if((Auth::user()->departamento != 'VENTAS')&&(Auth::user()->departamento != 'RRHH')&&(Auth::user()->departamento != 'TESORERIA')){*/

  if(in_array(0,$reportes)!=true){
?>

<style>
    #sorttable_sortfwdind, #sorttable_sortrevind {
        display: none;
    }
</style>

<?php
  if(Auth::user()->departamento == 'COMPRAS' || Auth::user()->departamento == 'SURTIDO' || Auth::user()->departamento == 'GERENCIA' || Auth::user()->departamento == 'LÍDER DE TIENDA'){
?>
    <!-- Dashboard Articulo Estrella-->
    <div class="card-deck">
        <div class="card border-secondary mb-3" style="width: 14rem;">
        <div class="card-body text-left bg-light">
            <h3 class="card-title">
            <span class="card-text text-dark">
                <i class="fas fa-credit-card"></i>
                Articulos Estrella
            </span>
            </h3>
            <p class="card-text text-dark">
            <?php
                FG_Acticulos_Estrella_Top(FG_Mi_Ubicacion());
            ?>
            </p>
        </div>
        <div class="card-footer bg-transparent border-secondary text-right">
            <a href="/reporte16/" class="btn btn-outline-secondary btn-sm">Visualizar</a>
        </div>
        </div>
    </div>
    <hr class="row align-items-start col-12">
    <!-- Dashboard Articulo Estrella -->
<?php
    }
?>

<?php if(Auth::user()->departamento == 'RECEPCION'):  ?>
  <div class="card-deck">
      <div class="card border-danger mb-3" style="width: 14rem;">
      <div class="card-body text-center text-left bg-danger">
          <h4>
            <?php
              $diasParaRecibir = compras\Configuracion::where('variable', 'DiasParaRecibir')->first()->valor;

              $fechaHoy = new Datetime();
              $fechaHoy->modify('+' . $diasParaRecibir . 'day');
              $fechaLimiteRecibirMercancia = $fechaHoy->format('d/m/Y');
            ?>
            <span class="card-text text-white">
              <i class="fas fa-exclamation-triangle CP-Latir"></i>
              Fecha límite para recibir mercancía en general: <?php echo $fechaLimiteRecibirMercancia ?>
            </span>
          </h4>
      </div>
    </div>
  </div>
<?php endif; ?>


<?php if(Auth::user()->departamento == 'INVENTARIO'):  ?>
  <div class="card-deck">
      <div class="card border-danger mb-3" style="width: 14rem;">
      <div class="card-body text-center text-left bg-danger">
          <h4>
            <?php
              $diasParaRecibir = compras\Configuracion::where('variable', 'DiasParaVencer')->first()->valor;

              $fechaHoy = new Datetime();
              $fechaHoy->modify('+' . $diasParaRecibir . 'day');
              $fechaProximaVencimiento = $fechaHoy->format('d/m/Y');
            ?>
            <span class="card-text text-white">
              <i class="fas fa-exclamation-triangle CP-Latir"></i>
              Fecha próxima para gestión de vencimientos: <?php echo $fechaProximaVencimiento ?>
            </span>
          </h4>
      </div>
    </div>
  </div>
<?php endif; ?>


<?php if(Auth::user()->departamento == 'OPERACIONES'):  ?>
  <div class="card-deck">
      <div class="card border-info mb-3" style="width: 14rem;">
      <div class="card-body text-center text-left bg-info">
          <h4>
            <?php
              $diasParaRecibir = compras\Configuracion::where('variable', 'DiasParaIngresar')->first()->valor;

              $fechaHoy = new Datetime();
              $fechaHoy->modify('+' . $diasParaRecibir . 'day');
              $fechaMaximaDevoluciones = $fechaHoy->format('d/m/Y');
            ?>
            <span class="card-text text-white">
              <i class="fas fa-exclamation-triangle CP-Latir"></i>
              Fecha mínima para ingreso de mercancía (vida útil): <?php echo $fechaMaximaDevoluciones ?>
            </span>
          </h4>
      </div>
    </div>
  </div>

    <table class="bg-secondary text-white table table-striped table-bordered col-12 sortable">
        <tbody>
          <tr>
            <td>
                <ul class="nav nav-tabs" id="myTab" role="tablist">
                  <li class="nav-item">
                    <a class="nav-link active bg-dark text-white" id="medicamentos-tab" data-toggle="tab" href="#medicamentos" role="tab" aria-controls="medicamentos" aria-selected="true">DESCRIPCIÓN DE MEDICAMENTOS</a>
                  </li>
                  <li class="nav-item">
                    <a class="nav-link bg-dark text-white" id="miscelaneos-tab" data-toggle="tab" href="#miscelaneos" role="tab" aria-controls="miscelaneos" aria-selected="false">DESCRIPCIÓN DE MISCELÁNEOS</a>
                  </li>
                  <li class="nav-item">
                    <a class="nav-link bg-dark text-white" id="descartables-tab" data-toggle="tab" href="#descartables" role="tab" aria-controls="descartables" aria-selected="false">DESCRIPCIÓN MATERIAL DESCARTABLES</a>
                  </li>
                  <li class="nav-item">
                    <a class="nav-link bg-dark text-white" id="generales-tab" data-toggle="tab" href="#generales" role="tab" aria-controls="generales" aria-selected="false">NOTAS GENERALES</a>
                  </li>
                </ul>
                <div class="tab-content" id="myTabContent">
                  <div class="tab-pane fade show active p-3" id="medicamentos" role="tabpanel" aria-labelledby="medicamentos-tab">
                    Molécula o Nombre Comercial + (Competidor/Componente/Paciente) + Presentación (Blister o Caja) + Concentración + Letra X + Contenido + Tipo o Variante + Marca o laboratorio.<br>
                    Ejemplo: <b>LORATADINA 10MG X 10 TABLETAS COASPHARMA</b><br><br>
                    <b>NOTAS:</b><br>
                    <li>Añadir el (Competidor/Componente/Paciente) si la descripción lo amerita<br>Ejemplos: ACETAMINOFEN <b>PEDIATRICO</b> X 60 ML AG<br>CLAUSY (<b>ENTEROGERMINA</b>) 5ML X 1 VIAL LIVESPO</li>
                    <li>Las abreviaciones I.V. o I.M. no están permitidas. Se debe escribir INTRAVENOSO o INTRAMUSCULAR si el articulo lo amerita.</li>
                    <li>Añadir la presentación si el articulo lo amerita (Blister o Caja) para eliminar la referencia. <b>REF.01</b>.</li>
                    <li>Se debe añadir el componente del medicamento y el atributo correspondiente a su ubicación <b>(RX, NEVERA, PSICOTROPICO)</b>.</li>
                    <li>Si el medicamento lo amerita también se le debe añadir el atributo <b>RECIPE</b>.</li>
                  </div>
                  <div class="tab-pane fade p-3" id="miscelaneos" role="tabpanel" aria-labelledby="miscelaneos-tab">
                    Tipo de producto (Singular) + Nombre comercial + Tipo o Variante + Letra X + Peso o Contenido + Marca (Opcional)<br>
                     <b>Ejemplo:</b> GALLETA DELICIAS MARIA CHOCOLATE X 4 UNIDADES PUIG<br><br>
                    <b>NOTAS:</b><br>
                    <li>La forma en la que se presenta el articulo puede ser agregada también (envase, display, paquete, tubular, lata vidrio, etc)<br>
                    EJEMPLO: <b>JUGO NATULAC DE DURAZNO ENVASE VIDRIO X 250 ML</b></li>
                    <li>Para el caso de artículos líderes se puede obviar el tipo<br>
                    Ejemplo descripción errada: <b>BEBIDA ACHOCOLATADA TODDY POTE X 200 GR</b><br>
                    Ejemplo descripción correcta: <b>TODDY POTE X 200 GR</b></li>
                    <li>El contenido de los artículos será reflejado con la palabra <b>UNIDADES</b>.</li>
                    <li>Si el contenido del artículo se refleja en <b>ONZ</b>, se debe añadir también la abreviación <b>GR</b> de ser necesario <b>(ONZ/GR)</b>.</li>
                    <li>Si el contenido del artículo se refleja en <b>CM3</b>, se debe añadir también la abreviación <b>ML</b>. <b>(CM3/ML)</b>.</li>
                    <li>En caso de poseer algún tipo de referencia específica, esta debe ser colocada al final de la descripción. Ejemplo: ENCENDEDOR/YERQUERO LIGHTER X 1 UNIDAD <b>REF.268</b>.
                  </div>
                  <div class="tab-pane fade p-3" id="descartables" role="tabpanel" aria-labelledby="descartables-tab">
                    Tipo de producto (Singular) + Contenido (De ser necesario) + (Tipo, Variante o medida) + Letra X + Unidad + Marca o laboratorio.<br>
                    Ejemplo: <b>JERINGA 5ML/CC CON AGUJA 21G X 1 UNIDAD DYNAMICS</b><br><br>
                    <b>NOTAS:</b>
                    <li>Añadir el componente correspondiente a cada articulo</li>
                    <li>Las abreviaciones I.V. o I.M. no están permitidas. Se debe escribir INTRAVENOSO o INTRAMUSCULAR si el articulo lo amerita.</li>
                    <li>Si el contenido del artículo se refleja en <b>ML</b>, se debe añadir la abreviación CC o viceversa <b>(ML/CC)</b></li>
                    <li>En caso de poseer alguna referencia esta se debe colocar al final de la descripción. Ejemplo: BURETA CALIBRADA 100ML X 1 UNIDAD KEYDEX <b>REF.AP2085</b></li>
                  </div>
                  <div class="tab-pane fade p-3" id="generales" role="tabpanel" aria-labelledby="generales-tab">
                    <li>No abreviar palabras claves (TABLETAS, ADULTO, PEDIATRICO, GOTAS, CREMA, JERINGA, ADHESIVO, MASCARILLA, GALLETAS, JABON, LOCION, etc).</li>
                    <li>Obviar caracteres especiales y acentos exceptuando la letra Ñ</li>
                    <li>Cualquier información adicional es aceptada siempre y cuando cumpla con el objetivo de una descripción clara y completa.</li>
                    <li>La descripción física del articulo siempre predomina sobre cualquier criterio</li>
                    <li>Si el articulo contiene nombre en inglés se recomienda agregar a la descripción su traducción al español o referencia principal.</li>
                    <li>
                        Abreviaciones permitidas:
                        <ul>
                            <li>AMP: Ampolla</li>
                            <li>GR: Gramo</li>
                            <li>MG: Miligramos</li>
                            <li>MCG: Microgramos</li>
                            <li>U.I.: Unidades internacionales.</li>
                            <li>KG: Kilo</li>
                            <li>ML: Mililitro</li>
                            <li>LT: Litro</li>
                            <li>ONZ: Onza</li>
                            <li>MeQ: Miliequivalentes</li>
                            <li>CC o CM3: Centímetros cúbicos</li>
                            <li>MMHG: Unidad de presión usado solo para las medias de compresión.</li>
                            <li>N: Número</li>
                            <li>CM: Centímetro</li>
                            <li>M: Metro</li>
                        </ul>
                    </li>
                  </div>
                </div>
            </td>
          </tr>
        </tbody>
      </table>
<?php endif; ?>


<?php if(Auth::user()->departamento == 'VENTAS'):  ?>
  <div class="card-deck">
      <div class="card border-success mb-3" style="width: 14rem;">
      <div class="card-body text-center text-left bg-success">
          <h4>
            <?php
              $diasParaRecibir = compras\Configuracion::where('variable', 'DiasParaDevolucion')->first()->valor;

              $fechaHoy = new Datetime();
              $fechaHoy->modify('+' . $diasParaRecibir . 'day');
              $fechaMaximaDevoluciones = $fechaHoy->format('d/m/Y');
            ?>
            <span class="card-text text-white">
              <i class="fas fa-exclamation-triangle CP-Latir"></i>
              Fecha máxima para gestión de devoluciones a clientes: <?php echo $fechaMaximaDevoluciones ?>
            </span>
          </h4>
      </div>
    </div>
  </div>
<?php endif; ?>

<!-------------------------------------------------------------------------------->
<!-- DESTACADOS -->
<?php
  if( (!empty($auditorReporte[0])) && (!empty($auditorUser[0])) ){
    $auditorReporte = $auditorReporte[0];
    $auditoriasReporte = $auditorReporte->registro;
    $auditorUser = $auditorUser[0];
    $usuarioAct = $auditorUser->user;
    echo'
    <div class="card-deck">
      <div class="card CP-border-golden mb-3" style="width: 14rem;">
        <div class="card-body text-left CP-bg-golden">
          <h4>
            <span class="card-text text-white">
              <i class="fas fa-star CP-Latir"></i>
              '.$auditoriasReporte.'
            </span>
          </h4>
          <p class="card-text text-white">Reporte mas usado</p>
        </div>
      </div>
      <div class="card CP-border-golden mb-3" style="width: 14rem;">
        <div class="card-body text-left CP-bg-golden">
          <h4>
            <span class="card-text text-white">
              <i class="fas fa-star CP-Latir"></i>
              '.$usuarioAct.'
            </span>
          </h4>
          <p class="card-text text-white">Usuario mas activo</p>
        </div>
      </div>
    </div>
    ';
  }
  else if( (!empty($auditorReporte[0])) && (empty($auditorUser[0])) ){
    $auditorReporte = $auditorReporte[0];
    $auditoriasReporte = $auditorReporte->registro;
    echo'
    <div class="card-deck">
      <div class="card CP-border-golden mb-3" style="width: 14rem;">
        <div class="card-body text-left CP-bg-golden">
          <h4>
            <span class="card-text text-white">
              <i class="fas fa-star CP-Latir"></i>
              '.$auditoriasReporte.'
            </span>
          </h4>
          <p class="card-text text-white">Reporte mas usado</p>
        </div>
      </div>
    </div>
    ';
  }
  else if( (empty($auditorReporte[0])) && (!empty($auditorUser[0])) ){
    $auditorUser = $auditorUser[0];
    $usuarioAct = $auditorUser->user;
    echo'
    <div class="card-deck">
      <div class="card CP-border-golden mb-3" style="width: 14rem;">
        <div class="card-body text-left CP-bg-golden">
          <h4>
            <span class="card-text text-white">
              <i class="fas fa-star CP-Latir"></i>
              '.$usuarioAct.'
            </span>
          </h4>
          <p class="card-text text-white">Usuario mas activo</p>
        </div>
      </div>
    </div>
    ';
  }

  if( (!empty($auditorReporteFavorito[0])) && (!empty($auditorReporteFavorito[1])) ){
    $reporteFavorito1 = $auditorReporteFavorito[0];
    $reporteFavorito1 = $reporteFavorito1->registro;
    $Ruta1 = FG_Ruta_Reporte($reporteFavorito1);

    $reporteFavorito2 = $auditorReporteFavorito[1];
    $reporteFavorito2 = $reporteFavorito2->registro;
    $Ruta2 = FG_Ruta_Reporte($reporteFavorito2);

    echo'
    <div class="card-deck">
      <div class="card border-secondary mb-3" style="width: 14rem;">
        <div class="card-body text-left bg-secondary">
          <h4>
            <span class="card-text text-white">
              <i class="fa fa-thumbtack"></i>
              '.$reporteFavorito1.'
            </span>
          </h4>
          <p class="card-text text-white">Reporte sugerido</p>
        </div>
        <div class="card-footer bg-transparent border-secondary text-right">
          <form action="'.$Ruta1.'" style="display: inline;">
            <input id="SEDE" name="SEDE" type="hidden" value="'.FG_Mi_Ubicacion().'">
            <button type="submit" name="Reporte" role="button" class="btn btn-outline-secondary btn-sm"></i>Visualizar</button>
          </form>
        </div>
      </div>
      <div class="card border-secondary mb-3" style="width: 14rem;">
        <div class="card-body text-left bg-secondary">
          <h4>
            <span class="card-text text-white">
              <i class="fa fa-thumbtack"></i>
              '.$reporteFavorito2.'
            </span>
          </h4>
          <p class="card-text text-white">Reporte sugerido</p>
        </div>
        <div class="card-footer bg-transparent border-secondary text-right">
          <form action="'.$Ruta2.'" style="display: inline;">
            <input id="SEDE" name="SEDE" type="hidden" value="'.FG_Mi_Ubicacion().'">
            <button type="submit" name="Reporte" role="button" class="btn btn-outline-secondary btn-sm"></i>Visualizar</button>
          </form>
        </div>
      </div>
    </div>
    <hr class="row align-items-start col-12">
    ';
  }
  else  if( (!empty($auditorReporteFavorito[0])) && (empty($auditorReporteFavorito[1])) ){
    $reporteFavorito1 = $auditorReporteFavorito[0];
    $reporteFavorito1 = $reporteFavorito1->registro;
    $Ruta1 = FG_Ruta_Reporte($reporteFavorito1);

    echo'
    <div class="card-deck">
      <div class="card border-secondary mb-3" style="width: 14rem;">
        <div class="card-body text-left bg-secondary">
          <h4>
            <span class="card-text text-white">
              <i class="fa fa-thumbtack"></i>
              '.$reporteFavorito1.'
            </span>
          </h4>
          <p class="card-text text-white">Reporte sugerido</p>
        </div>
        <div class="card-footer bg-transparent border-secondary text-right">
          <form action="'.$Ruta1.'" style="display: inline;">
            <input id="SEDE" name="SEDE" type="hidden" value="'.FG_Mi_Ubicacion().'">
            <button type="submit" name="Reporte" role="button" class="btn btn-outline-secondary btn-sm"></i>Visualizar</button>
          </form>
        </div>
      </div>
    </div>
    <hr class="row align-items-start col-12">
    ';
  }
  else  if( (empty($auditorReporteFavorito[0])) && (!empty($auditorReporteFavorito[1])) ){
    $reporteFavorito2 = $auditorReporteFavorito[1];
    $reporteFavorito2 = $reporteFavorito2->registro;
    $Ruta2 = FG_Ruta_Reporte($reporteFavorito2);

    echo'
    <div class="card-deck">
      <div class="card border-secondary mb-3" style="width: 14rem;">
        <div class="card-body text-left bg-secondary">
          <h4>
            <span class="card-text text-white">
              <i class="fa fa-thumbtack"></i>
              '.$reporteFavorito2.'
            </span>
          </h4>
          <p class="card-text text-white">Reporte sugerido</p>
        </div>
        <div class="card-footer bg-transparent border-secondary text-right">
          <form action="'.$Ruta2.'" style="display: inline;">
            <input id="SEDE" name="SEDE" type="hidden" value="'.FG_Mi_Ubicacion().'">
            <button type="submit" name="Reporte" role="button" class="btn btn-outline-secondary btn-sm"></i>Visualizar</button>
          </form>
        </div>
      </div>
    </div>
    <hr class="row align-items-start col-12">
    ';
  }
?>


<?php if (Auth::user()->departamento == 'CONTABILIDAD'): ?>
<!-- CONTABILIDAD -->
<div class="card-deck">
  <div class="card border-danger mb-3" style="width: 14rem;">
    <div class="card-body text-left bg-danger">
      <h3 class="card-title">
        <span class="card-text text-white">
          <i class="fas fa-balance-scale-left"></i>
          <?php
          $saldo_actualBs = DB::table('ts_movimientos')
            ->where('tasa_ventas_id', 1)
            ->whereNull('diferido')
            ->orderBy('id', 'desc')
            ->first();

          echo 'Saldo disponible: '. number_format(DB::table('configuracions')
          ->where('id', 7)
          ->value('valor'), 2, ',', '.') . " " . SigVe;
        ?>
        </span>
      </h3>
      <p class="card-text text-white">
      <?php
        if(empty($saldo_actualBs)) {
          $ultimoMovimientoBs = '';
        }
        else {
          $ultimoMovimientoBs = $saldo_actualBs->updated_at;
        }

        echo 'Movimientos en bolivares registrados: ' . $movimientosBs;
        echo '<br>Fecha y hora actual: ' . date("d-m-Y h:i:s a");
        echo '<br>Ultimo movimiento: ' . date("d-m-Y h:i:s a", strtotime($ultimoMovimientoBs));
      ?>
      </p>
    </div>
    <div class="card-footer bg-transparent border-danger text-right">
      <a href="/movimientos?tasa_ventas_id=1" class="btn btn-outline-danger btn-sm">Visualizar</a>
    </div>
  </div>

  <div class="card border-success mb-3" style="width: 14rem;">
      <div class="card-body text-left bg-success">
        <h3 class="card-title">
          <span class="card-text text-white">
            <i class="fas fa-balance-scale"></i>
            <?php
            $saldo_actualDs = DB::table('ts_movimientos')
              ->where('tasa_ventas_id', 2)
              ->whereNull('diferido')
              ->orderBy('id', 'desc')
              ->first();

              echo 'Saldo disponible: ' . number_format(DB::table('configuracions')
            ->where('id', 8)
            ->value('valor'), 2, ',', '.') . " " . SigDolar;
          ?>
          </span>
        </h3>
        <p class="card-text text-white">
        <?php
          if(empty($saldo_actualDs)) {
            $ultimoMovimientoDs = '';
          }
          else {
            $ultimoMovimientoDs = $saldo_actualDs->updated_at;
          }

          echo 'Movimientos en dolares registrados: ' . $movimientosDs;
          echo '<br>Fecha y hora actual: ' . date("d-m-Y h:i:s a");
          echo '<br>Ultimo movimiento: ' . date("d-m-Y h:i:s a", strtotime($ultimoMovimientoDs));
        ?>
        </p>
      </div>
      <div class="card-footer bg-transparent border-success text-right">
        <a href="/movimientos?tasa_ventas_id=2" class="btn btn-outline-success btn-sm">Visualizar</a>
      </div>
    </div>

    <div class="card border-info mb-3" style="width: 14rem;">
      <div class="card-body text-left bg-info">
        <h3 class="card-title">
          <span class="card-text text-white">
            <i class="fas fa-credit-card"></i>
            <?php
              echo 'Tasa Venta: '.$Tasa.' '.SigVe;
            ?>
          </span>
        </h3>
        <p class="card-text text-white">
        <?php
          echo 'Ultima Actualizacion: '.$tasaVenta;
        ?>
        </p>
      </div>
      <div class="card-footer bg-transparent border-info text-right">
        <a href="/tasaVenta/" class="btn btn-outline-info btn-sm">Visualizar</a>
      </div>
  </div>
</div>

<div class="card-deck">
    <div class="card border-warning mb-3" style="width: 14rem;">
      <div class="card-body text-left bg-warning">
        <h3 class="card-title">
          <span class="card-text text-white">
            <i class="fas fa-lock"></i>
            <?php
            $diferido_actualBs = DB::table('ts_movimientos')
              ->where('tasa_ventas_id', 1)
              ->whereNotNull('diferido')
              ->orderBy('updated_at', 'desc')
              ->first();

            if(empty($diferido_actualBs)) {
                echo 'Diferido actual: '. number_format(0, 2, ',', '.') . " " . SigVe;
              }
              else {
                echo 'Diferido actual: '. number_format($diferido_actualBs->diferido_actual, 2, ',', '.') . " " . SigVe;
              }
          ?>
          </span>
        </h3>
        <p class="card-text text-white">
        <?php
          if(empty($diferido_actualBs)) {
            $ultimoDiferidoBs = '';
          }
          else {
            $ultimoDiferidoBs = $diferido_actualBs->updated_at;
          }

          echo 'Diferidos en bolivares registrados: ' . $diferidosBs;
          echo '<br>Fecha y hora actual: ' . date("d-m-Y h:i:s a");
          echo '<br>Ultimo diferido: ' . date("d-m-Y h:i:s a", strtotime($ultimoDiferidoBs));
        ?>
        </p>
      </div>
      <div class="card-footer bg-transparent border-warning text-right">
        <a href="/diferidos?tasa_ventas_id=1" class="btn btn-outline-warning btn-sm">Visualizar</a>
      </div>
    </div>

    <div class="card border-secondary mb-3" style="width: 14rem;">
      <div class="card-body text-left bg-secondary">
        <h3 class="card-title">
          <span class="card-text text-white">
            <i class="fas fa-lock"></i>
            <?php
            $diferido_actualDs = DB::table('ts_movimientos')
              ->where('tasa_ventas_id', 2)
              ->whereNotNull('diferido')
              ->orderBy('updated_at', 'desc')
              ->first();

              if(empty($diferido_actualDs)) {
                echo 'Diferido actual: ' . number_format(0, 2, ',', '.') . " " . SigDolar;
              }
              else {
                echo 'Diferido actual: ' . number_format($diferido_actualDs->diferido_actual, 2, ',', '.') . " " . SigDolar;
              }
          ?>
          </span>
        </h3>
        <p class="card-text text-white">
        <?php
          if(empty($diferido_actualDs)) {
            $ultimoDiferidoDs = '';
          }
          else {
            $ultimoDiferidoDs = $diferido_actualDs->updated_at;
          }

          echo 'Diferidos en dolares registrados: ' . $diferidosDs;
          echo '<br>Fecha y hora actual: ' . date("d-m-Y h:i:s a");
          echo '<br>Ultimo diferido: ' . date("d-m-Y h:i:s a", strtotime($ultimoDiferidoDs));
        ?>
        </p>
      </div>
      <div class="card-footer bg-transparent border-secondary text-right">
        <a href="/diferidos?tasa_ventas_id=2" class="btn btn-outline-secondary btn-sm">Visualizar</a>
      </div>
    </div>
  </div>
<?php endif; ?>

<!-- DESTACADOS -->
<!-------------------------------------------------------------------------------->
<!-- AGENDA -->
<div class="card-deck">
    <!-- Empresas -->
    <div class="card border-danger mb-3" style="width: 14rem;">
      <div class="card-body text-left bg-danger">
        <h2 class="card-title">
          <span class="card-text text-white">
            <i class="fas fa-industry"></i>
            <?php
            echo ''.$empresas;
          ?>
          </span>
        </h2>
        <p class="card-text text-white">Empresas registradas</p>
      </div>
      <div class="card-footer bg-transparent border-danger text-right">
        <a href="/empresa/" class="btn btn-outline-danger btn-sm">Visualizar</a>
      </div>
    </div>
    <!-- Provedores -->
    <div class="card border-success mb-3" style="width: 14rem;">
      <div class="card-body text-left bg-success">
        <h2 class="card-title">
          <span class="card-text text-white">
            <i class="fas fa-dolly"></i>
            <?php
            echo ''.$proveedores;
          ?>
          </span>
        </h2>
        <p class="card-text text-white">Proveedores registrados</p>
      </div>
      <div class="card-footer bg-transparent border-success text-right">
        <a href="/proveedor/" class="btn btn-outline-success btn-sm">Visualizar</a>
      </div>
    </div>
    <!-- Reportes -->
    <div class="card border-info mb-3" style="width: 14rem;">
        <div class="card-body text-left bg-info">
          <h2 class="card-title">
            <span class="card-text text-white">
              <i class="fas fa-file-invoice"></i>
              <?php
                echo''.count($reportes);
              ?>
            </span>
          </h2>
          <p class="card-text text-white">Reportes disponibles</p>
        </div>
        <div class="card-footer bg-transparent border-info text-right">
          <a href="/sedes_reporte/" class="btn btn-outline-info btn-sm">Visualizar</a>
        </div>
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
  <!-- Modal COMPRAS -->
  <div class="modal fade" id="exampleModalCenter" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title text-info" id="exampleModalCenterTitle"><i class="fas fa-bell text-info CP-beep"></i> Novedades</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <label>Hola <b class="text-info">{{ Auth::user()->name }}</b>.</label>
          <br/>
          Estas usando<b class="text-info">{{$CPharmaVersion}}</b>, para el departamento de <b class="text-info">{{ Auth::user()->departamento }}</b>, esta version incluye las siguientes mejoras:<br/><br/></label>
        <ul style="list-style:none">
        <li class="card-text text-dark" style="display: inline;">
          <i class="far fa-check-circle text-info" style="display: inline;"></i>
          Desde ya esta disponible el modulo:
          <b class="text-info">Buscador de articulos</b>!!
          </li>
        </ul>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-outline-info" data-dismiss="modal">Aceptar</button>
        </div>
      </div>
    </div>
  </div>
  <!-- Modal COMPRAS -->

  <!-- Dashboard Articulo Estrella-->
  <!--
  <div class="card-deck">
    <div class="card border-secondary mb-3" style="width: 14rem;">
      <div class="card-body text-left bg-light">
        <h3 class="card-title">
          <span class="card-text text-dark">
            <i class="fas fa-credit-card"></i>
            Articulos Estrella
          </span>
        </h3>
        <p class="card-text text-dark">
          <?php
            //FG_Acticulos_Estrella_Top(FG_Mi_Ubicacion());
          ?>
        </p>
      </div>
      <div class="card-footer bg-transparent border-secondary text-right">
        <a href="/reporte16/" class="btn btn-outline-secondary btn-sm">Visualizar</a>
      </div>
    </div>
  </div>
  -->
  <!-- Dashboard Articulo Estrella -->

  <!-- Dashboard COMPRAS-->
  <div class="card-deck">
  <!-- Tasa Venta -->
    <div class="card border-dark mb-3" style="width: 14rem;">
      <div class="card-body text-left bg-dark">
        <h3 class="card-title">
          <span class="card-text text-white">
            <i class="fas fa-credit-card"></i>
            <?php
            echo 'Tasa Venta: '.$Tasa;
          ?>
          </span>
        </h3>
        <p class="card-text text-white">
        <?php
          echo 'Ultima Actualizacion: '.$tasaVenta;
        ?>
        </p>
      </div>
    </div>
  </div>
  <!-- Dashboard COMPRAS-->
<?php
  }
?>
<!-- COMPRAS -->
<!-------------------------------------------------------------------------------->
<!-- OPERACIONES -->
<?php
  if(Auth::user()->departamento == 'OPERACIONES'){
?>
  <!-- Modal OPERACIONES -->
  <div class="modal fade" id="exampleModalCenter" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title text-info" id="exampleModalCenterTitle"><i class="fas fa-bell text-info CP-beep"></i> Novedades</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <label>Hola <b class="text-info">{{ Auth::user()->name }}</b>.</label>
          <br/>
          Estas usando<b class="text-info">{{$CPharmaVersion}}</b>, para el departamento de <b class="text-info">{{ Auth::user()->departamento }}</b>, esta version incluye las siguientes mejoras:<br/><br/></label>
        <ul style="list-style:none">
          <li class="card-text text-dark" style="display: inline;">
          <i class="far fa-check-circle text-info" style="display: inline;"></i>
          Desde ya esta disponible el reporte:
          <b class="text-info">Articulos Devaluados</b>!!
          </li>
        </ul>
        <ul style="list-style:none">
          <li class="card-text text-dark" style="display: inline;">
          <i class="far fa-check-circle text-info" style="display: inline;"></i>
          Desde ya esta disponible el reporte:
          <b class="text-info">Catalogo de proveedor</b>!!
          </li>
        </ul>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-outline-info" data-dismiss="modal">Aceptar</button>
        </div>
      </div>
    </div>
  </div>
  <!-- Modal OPERACIONES -->
  <!-- Dashboard OPERACIONES-->
  <div class="card-deck">
    <!-- Tasa Mercado -->
    <?php
      $conn = FG_Conectar_Smartpharma(FG_Mi_Ubicacion());
      $sql = "SELECT TOP 1 InvArticulo.CodigoArticulo, InvArticulo.Auditoria_Usuario FROM InvArticulo ORDER BY InvArticulo.Auditoria_FechaCreacion DESC";
      $result = sqlsrv_query($conn,$sql);
      $row = sqlsrv_fetch_array($result,SQLSRV_FETCH_ASSOC);
      $codigoInterno = intval($row["CodigoArticulo"]);
      $operador = ($row["Auditoria_Usuario"]);
    ?>
    <div class="card border-warning mb-3" style="width: 14rem;">
      <div class="card-body text-left bg-warning">
          <h3 class="card-title">
            <span class="card-text text-white">
              <i class="fas fa-credit-card"></i>
              <?php
                echo 'Ultimo Codigo Interno Creado: '.$codigoInterno;
              ?>
            </span>
          </h3>
          <p class="card-text text-white">
          <?php
            echo 'Ultima Actualizacion: '.date('d/m/Y H:i:s A');
            echo '<br>Actualizado por: '.$operador;
          ?>
          </p>
      </div>
    </div>
  <!-- Tasa Mercado -->
    <div class="card border-secondary mb-3" style="width: 14rem;">
      <div class="card-body text-left bg-secondary">
          <h3 class="card-title">
            <span class="card-text text-white">
              <i class="fas fa-credit-card"></i>
              <?php
                echo 'Tasa Mercado: '.$TasaMercado.' '.SigVe;
              ?>
            </span>
          </h3>
          <p class="card-text text-white">
          <?php
            echo 'Ultima Actualizacion: '.$FechaTasaMercado;
          ?>
          </p>
      </div>
    </div>
  <!-- Tasa Venta -->
    <div class="card border-dark mb-3" style="width: 14rem;">
      <div class="card-body text-left bg-dark">
        <h3 class="card-title">
          <span class="card-text text-white">
            <i class="fas fa-credit-card"></i>
            <?php
            echo 'Tasa Venta: '.$Tasa;
          ?>
          </span>
        </h3>
        <p class="card-text text-white">
        <?php
          echo 'Ultima Actualizacion: '.$tasaVenta;
        ?>
        </p>
      </div>
    </div>
  </div>
  <!-- Dashboard OPERACIONES-->
<?php
  }
?>
<!-- OPERACIONES -->
<!-------------------------------------------------------------------------------->
<!-- ALMACEN -->
<?php
  if(Auth::user()->departamento == 'ALMACEN'){
?>
  <!-- Modal ALMACEN -->
  <div class="modal fade" id="exampleModalCenter" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title text-info" id="exampleModalCenterTitle"><i class="fas fa-bell text-info CP-beep"></i> Novedades</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <label>Hola <b class="text-info">{{ Auth::user()->name }}</b>.</label>
          <br/>
          Estas usando<b class="text-info">{{$CPharmaVersion}}</b>, para el departamento de <b class="text-info">{{ Auth::user()->departamento }}</b>, esta version incluye las siguientes mejoras:<br/><br/></label>
        <ul style="list-style:none">
          <li class="card-text text-dark" style="display: inline;">
          <i class="far fa-check-circle text-info" style="display: inline;"></i>
          Desde ya esta disponible el modulo:
          <b class="text-info">Traslado</b>!!
          </li>
        </ul>
        <ul style="list-style:none">
          <li class="card-text text-dark" style="display: inline;">
          <i class="far fa-check-circle text-info" style="display: inline;"></i>
          Se actualizaron las busquedas por
          <b class="text-info">Codigo de Barra</b>!!
          </li>
        </ul>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-outline-info" data-dismiss="modal">Aceptar</button>
        </div>
      </div>
    </div>
  </div>
  <!-- Modal ALMACEN -->
  <!-- Dashboard ALMACEN-->
  <div class="card-deck">
  <!-- Tasa Venta -->
    <div class="card border-dark mb-3" style="width: 14rem;">
      <div class="card-body text-left bg-dark">
        <h3 class="card-title">
          <span class="card-text text-white">
            <i class="fas fa-credit-card"></i>
            <?php
            echo 'Tasa Venta: '.$Tasa;
          ?>
          </span>
        </h3>
        <p class="card-text text-white">
        <?php
          echo 'Ultima Actualizacion: '.$tasaVenta;
        ?>
        </p>
      </div>
    </div>
  </div>
  <!-- Dashboard ALMACEN-->
<?php
  }
?>
<!-- ALMACEN -->
<!-------------------------------------------------------------------------------->
<!-- DEVOLUCIONES -->
<?php
  if(Auth::user()->departamento == 'DEVOLUCIONES'){
?>
  <!-- Modal DEVOLUCIONES -->
  <div class="modal fade" id="exampleModalCenter" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title text-info" id="exampleModalCenterTitle"><i class="fas fa-bell text-info CP-beep"></i> Novedades</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <label>Hola <b class="text-info">{{ Auth::user()->name }}</b>.</label>
          <br/>
          Estas usando<b class="text-info">{{$CPharmaVersion}}</b>, para el departamento de <b class="text-info">{{ Auth::user()->departamento }}</b>, esta version incluye las siguientes mejoras:<br/><br/></label>
        <ul style="list-style:none">
          <li class="card-text text-dark" style="display: inline;">
          <i class="far fa-check-circle text-info" style="display: inline;"></i>
          Desde ya esta disponible el modulo:
          <b class="text-info">Orden de compra</b>!!
          </li>
        </ul>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-outline-info" data-dismiss="modal">Aceptar</button>
        </div>
      </div>
    </div>
  </div>
  <!-- Modal DEVOLUCIONES -->
  <!-- Dashboard DEVOLUCIONES-->
  <div class="card-deck">
  <!-- Tasa Venta -->
    <div class="card border-dark mb-3" style="width: 14rem;">
      <div class="card-body text-left bg-dark">
        <h3 class="card-title">
          <span class="card-text text-white">
            <i class="fas fa-credit-card"></i>
            <?php
            echo 'Tasa Venta: '.$Tasa;
          ?>
          </span>
        </h3>
        <p class="card-text text-white">
        <?php
          echo 'Ultima Actualizacion: '.$tasaVenta;
        ?>
        </p>
      </div>
    </div>
  </div>
  <!-- Dashboard DEVOLUCIONES-->
<?php
  }
?>
<!-- DEVOLUCIONES -->
<!-------------------------------------------------------------------------------->
<!-- SURTIDO -->
<?php
  if(Auth::user()->departamento == 'SURTIDO'){
?>
  <!-- Modal SURTIDO -->
  <div class="modal fade" id="exampleModalCenter" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title text-info" id="exampleModalCenterTitle"><i class="fas fa-bell text-info CP-beep"></i> Novedades</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <label>Hola <b class="text-info">{{ Auth::user()->name }}</b>.</label>
          <br/>
          Estas usando<b class="text-info">{{$CPharmaVersion}}</b>, para el departamento de <b class="text-info">{{ Auth::user()->departamento }}</b>, esta version incluye las siguientes mejoras:<br/><br/></label>
        <ul style="list-style:none">
          <li class="card-text text-dark" style="display: inline;">
          <i class="far fa-check-circle text-info" style="display: inline;"></i>
          Desde ya esta disponible el reporte:
          <b class="text-info">Productos mas vendidos</b>!!
          </li>
        </ul>
        <ul style="list-style:none">
        <li class="card-text text-dark" style="display: inline;">
          <i class="far fa-check-circle text-info" style="display: inline;"></i>
          Desde ya esta disponible el reporte:
          <b class="text-info">Consultor de Precio</b>!!
          </li>
        </ul>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-outline-info" data-dismiss="modal">Aceptar</button>
        </div>
      </div>
    </div>
  </div>
  <!-- Modal SURTIDO -->
  <!-- Dashboard SURTIDO-->
  <div class="card-deck">
  <!-- Tasa Venta -->
    <div class="card border-dark mb-3" style="width: 14rem;">
      <div class="card-body text-left bg-dark">
        <h3 class="card-title">
          <span class="card-text text-white">
            <i class="fas fa-credit-card"></i>
            <?php
            echo 'Tasa Venta: '.$Tasa;
          ?>
          </span>
        </h3>
        <p class="card-text text-white">
        <?php
          echo 'Ultima Actualizacion: '.$tasaVenta;
        ?>
        </p>
      </div>
    </div>
  </div>
  <!-- Dashboard SURTIDO-->
<?php
  }
?>
<!-- SURTIDO -->
<!-------------------------------------------------------------------------------->
<!-- VENTAS -->
<?php
  if(Auth::user()->departamento == 'VENTAS'){
?>
  <!-- Modal VENTAS -->
  <div class="modal fade" id="exampleModalCenter" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title text-info" id="exampleModalCenterTitle"><i class="fas fa-bell text-info CP-beep"></i> Novedades</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <label>Hola <b class="text-info">{{ Auth::user()->name }}</b>.</label>
          <br/>
          Estas usando<b class="text-info">{{$CPharmaVersion}}</b>, para el departamento de <b class="text-info">{{ Auth::user()->departamento }}</b>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-outline-info" data-dismiss="modal">Aceptar</button>
        </div>
      </div>
    </div>
  </div>
  <!-- Modal VENTAS -->
  <!-- Dashboard VENTAS-->
  <div class="card-deck">
  <!-- Tasa Venta -->
    <div class="card border-success mb-3" style="width: 14rem;">
      <div class="card-body text-left bg-success">
        <h3 class="card-title">
          <span class="card-text text-white">
            <i class="fas fa-credit-card"></i>
            <?php
            echo 'Tasa Venta: '.$Tasa;
          ?>
          </span>
        </h3>
        <p class="card-text text-white">
        <?php
          echo 'Ultima Actualizacion: '.$tasaVenta;
        ?>
        </p>
      </div>
      <div class="card-footer bg-transparent border-success text-right">
        <a href="/tasaVenta/" class="btn btn-outline-success btn-sm">Visualizar</a>
      </div>
    </div>
  </div>
  <!-- Dashboard VENTAS-->
<?php
  }
?>
<!-- VENTAS -->
<!-------------------------------------------------------------------------------->
<!-- TESORERIA -->
<?php
  if(Auth::user()->departamento == 'TESORERIA'){
?>
  <!-- Modal TESORERIA -->
  <div class="modal fade" id="exampleModalCenter" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title text-info" id="exampleModalCenterTitle"><i class="fas fa-bell text-info CP-beep"></i> Novedades</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <label>Hola <b class="text-info">{{ Auth::user()->name }}</b>.</label>
          <br/>
          Estas usando<b class="text-info">{{$CPharmaVersion}}</b>, para el departamento de <b class="text-info">{{ Auth::user()->departamento }}</b>, esta version incluye las siguientes mejoras:<br/><br/></label>
        <ul style="list-style:none">
          <li class="card-text text-dark" style="display: inline;">
          <i class="far fa-check-circle text-info" style="display: inline;"></i>
          Desde ya estan disponibles los modulos:
          <b class="text-info">
            <ul>
              <li>Movimientos en bolivares</li>
              <li>Movimientos en dolares</li>
              <li>Diferidos en bolivares</li>
              <li>Diferidos en dolares</li>
            </ul>
          </b>
          </li>
        </ul>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-outline-info" data-dismiss="modal">Aceptar</button>
        </div>
      </div>
    </div>
  </div>
  <!-- Modal TESORERIA -->

  <!-- Dashboard TESORERIA-->
  <div class="card-deck">
    <div class="card border-danger mb-3" style="width: 14rem;">
      <div class="card-body text-left bg-danger">
        <h3 class="card-title">
          <span class="card-text text-white">
            <i class="fas fa-balance-scale-left"></i>
            <?php
            $saldo_actualBs = DB::table('ts_movimientos')
              ->where('tasa_ventas_id', 1)
              ->whereNull('diferido')
              ->orderBy('id', 'desc')
              ->first();

            echo 'Saldo disponible: '. number_format(DB::table('configuracions')
            ->where('id', 7)
            ->value('valor'), 2, ',', '.') . " " . SigVe;
          ?>
          </span>
        </h3>
        <p class="card-text text-white">
        <?php
          if(empty($saldo_actualBs)) {
            $ultimoMovimientoBs = '';
          }
          else {
            $ultimoMovimientoBs = $saldo_actualBs->updated_at;
          }

          echo 'Movimientos en bolivares registrados: ' . $movimientosBs;
          echo '<br>Fecha y hora actual: ' . date("d-m-Y h:i:s a");
          echo '<br>Ultimo movimiento: ' . date("d-m-Y h:i:s a", strtotime($ultimoMovimientoBs));
        ?>
        </p>
      </div>
      <div class="card-footer bg-transparent border-danger text-right">
        <a href="/movimientos?tasa_ventas_id=1" class="btn btn-outline-danger btn-sm">Visualizar</a>
      </div>
    </div>

    <div class="card border-success mb-3" style="width: 14rem;">
      <div class="card-body text-left bg-success">
        <h3 class="card-title">
          <span class="card-text text-white">
            <i class="fas fa-balance-scale"></i>
            <?php
            $saldo_actualDs = DB::table('ts_movimientos')
              ->where('tasa_ventas_id', 2)
              ->whereNull('diferido')
              ->orderBy('id', 'desc')
              ->first();

              echo 'Saldo disponible: ' . number_format(DB::table('configuracions')
            ->where('id', 8)
            ->value('valor'), 2, ',', '.') . " " . SigDolar;
          ?>
          </span>
        </h3>
        <p class="card-text text-white">
        <?php
          if(empty($saldo_actualDs)) {
            $ultimoMovimientoDs = '';
          }
          else {
            $ultimoMovimientoDs = $saldo_actualDs->updated_at;
          }

          echo 'Movimientos en dolares registrados: ' . $movimientosDs;
          echo '<br>Fecha y hora actual: ' . date("d-m-Y h:i:s a");
          echo '<br>Ultimo movimiento: ' . date("d-m-Y h:i:s a", strtotime($ultimoMovimientoDs));
        ?>
        </p>
      </div>
      <div class="card-footer bg-transparent border-success text-right">
        <a href="/movimientos?tasa_ventas_id=2" class="btn btn-outline-success btn-sm">Visualizar</a>
      </div>
    </div>

    <!-- Tasa Venta -->
    <div class="card border-info mb-3" style="width: 14rem;">
      <div class="card-body text-left bg-info">
        <h3 class="card-title">
          <span class="card-text text-white">
            <i class="fas fa-credit-card"></i>
            <?php
              echo 'Tasa Venta: '.$Tasa.' '.SigVe;
            ?>
          </span>
        </h3>
        <p class="card-text text-white">
        <?php
          echo 'Ultima Actualizacion: '.$tasaVenta;
        ?>
        </p>
      </div>
      <div class="card-footer bg-transparent border-info text-right">
        <a href="/tasaVenta/" class="btn btn-outline-info btn-sm">Visualizar</a>
      </div>
    </div>
  </div>

  <!-- DIFERIDOS -->
  <div class="card-deck">
    <div class="card border-warning mb-3" style="width: 14rem;">
      <div class="card-body text-left bg-warning">
        <h3 class="card-title">
          <span class="card-text text-white">
            <i class="fas fa-lock"></i>
            <?php
            $diferido_actualBs = DB::table('ts_movimientos')
              ->where('tasa_ventas_id', 1)
              ->whereNotNull('diferido')
              ->orderBy('updated_at', 'desc')
              ->first();

            if(empty($diferido_actualBs)) {
                echo 'Diferido actual: '. number_format(0, 2, ',', '.') . " " . SigVe;
              }
              else {
                echo 'Diferido actual: '. number_format($diferido_actualBs->diferido_actual, 2, ',', '.') . " " . SigVe;
              }
          ?>
          </span>
        </h3>
        <p class="card-text text-white">
        <?php
          if(empty($diferido_actualBs)) {
            $ultimoDiferidoBs = '';
          }
          else {
            $ultimoDiferidoBs = $diferido_actualBs->updated_at;
          }

          echo 'Diferidos en bolivares registrados: ' . $diferidosBs;
          echo '<br>Fecha y hora actual: ' . date("d-m-Y h:i:s a");
          echo '<br>Ultimo diferido: ' . date("d-m-Y h:i:s a", strtotime($ultimoDiferidoBs));
        ?>
        </p>
      </div>
      <div class="card-footer bg-transparent border-warning text-right">
        <a href="/diferidos?tasa_ventas_id=1" class="btn btn-outline-warning btn-sm">Visualizar</a>
      </div>
    </div>

    <div class="card border-secondary mb-3" style="width: 14rem;">
      <div class="card-body text-left bg-secondary">
        <h3 class="card-title">
          <span class="card-text text-white">
            <i class="fas fa-lock"></i>
            <?php
            $diferido_actualDs = DB::table('ts_movimientos')
              ->where('tasa_ventas_id', 2)
              ->whereNotNull('diferido')
              ->orderBy('updated_at', 'desc')
              ->first();

              if(empty($diferido_actualDs)) {
                echo 'Diferido actual: ' . number_format(0, 2, ',', '.') . " " . SigDolar;
              }
              else {
                echo 'Diferido actual: ' . number_format($diferido_actualDs->diferido_actual, 2, ',', '.') . " " . SigDolar;
              }
          ?>
          </span>
        </h3>
        <p class="card-text text-white">
        <?php
          if(empty($diferido_actualDs)) {
            $ultimoDiferidoDs = '';
          }
          else {
            $ultimoDiferidoDs = $diferido_actualDs->updated_at;
          }

          echo 'Diferidos en dolares registrados: ' . $diferidosDs;
          echo '<br>Fecha y hora actual: ' . date("d-m-Y h:i:s a");
          echo '<br>Ultimo diferido: ' . date("d-m-Y h:i:s a", strtotime($ultimoDiferidoDs));
        ?>
        </p>
      </div>
      <div class="card-footer bg-transparent border-secondary text-right">
        <a href="/diferidos?tasa_ventas_id=2" class="btn btn-outline-secondary btn-sm">Visualizar</a>
      </div>
    </div>
  </div>
  <!-- Dashboard TESORERIA-->
<?php
  }
?>
<!-- TESORERIA -->
<!-------------------------------------------------------------------------------->
<!-- RRHH -->
<?php
  if(Auth::user()->departamento == 'RRHH'){
?>
  <!-- Modal RRHH -->
  <div class="modal fade" id="exampleModalCenter" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title text-info" id="exampleModalCenterTitle"><i class="fas fa-bell text-info CP-beep"></i> Novedades</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <label>Hola <b class="text-info">{{ Auth::user()->name }}</b>.</label>
          <br/>
          Estas usando<b class="text-info">{{$CPharmaVersion}}</b>, para el departamento de <b class="text-info">{{ Auth::user()->departamento }}</b><br/><br/>
          <ul style="list-style:none">
            <li class="card-text text-dark" style="display: inline;">
              <i class="far fa-check-circle text-info" style="display: inline;"></i>
              Desde ya esta disponible el modulo:
              <b class="text-info">Candidatos</b>!!
            </li>
          </ul>
          <ul style="list-style:none">
            <li class="card-text text-dark" style="display: inline;">
              <i class="far fa-check-circle text-info" style="display: inline;"></i>
              Desde ya esta disponible el modulo:
              <b class="text-info">Pruebas</b>!!
            </li>
          </ul>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-outline-info" data-dismiss="modal">Aceptar</button>
        </div>
      </div>
    </div>
  </div>
  <!-- Modal RRHH -->
  <!-- Dashboard Metricas-->
  <div class="card-deck">
    <!-- Pie Chart FTN-->
    <div class="col-xl-4 col-lg-4">
      <div class="card shadow mb-4">
        <!-- Card Header - Dropdown -->
        <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
          <h6 class="m-0 font-weight-bold text-info">FARMACIA TIERRA NEGRA, C.A</h6>
        </div>
        <!-- Card Body -->
        <div class="card-body">
          <div class="chart-pie pt-4 pb-2">
            <canvas id="ChartFTN"></canvas>
          </div>
          <div class="mt-4 text-center small">
            <span class="mr-2">
              <i class="fas fa-circle text-primary"></i> Activos:
              <label id="ActFTN"></label>
            </span>
            <span class="mr-2">
              <i class="fas fa-circle text-success"></i> Ingresos:
              <label id="IngFTN"></label>
            </span>
            <span class="mr-2">
              <i class="fas fa-circle text-danger"></i> Egresos:
              <label id="EgrFTN"></label>
            </span>
          </div>
          <span class="mt-4 text-center small">Desde el <label id="FInicioFTN"></label> hasta el <label id="FFinFTN"></label></span>
        </div>
      </div>
    </div>
    <!-- Pie Chart FLL-->
    <div class="col-xl-4 col-lg-4">
      <div class="card shadow mb-4">
        <!-- Card Header - Dropdown -->
        <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
          <h6 class="m-0 font-weight-bold text-info">FARMACIA LA LAGO, C.A</h6>
        </div>
        <!-- Card Body -->
        <div class="card-body">
          <div class="chart-pie pt-4 pb-2">
            <canvas id="ChartFLL"></canvas>
          </div>
          <div class="mt-4 text-center small">
            <span class="mr-2">
              <i class="fas fa-circle text-primary"></i> Activos:
              <label id="ActFLL"></label>
            </span>
            <span class="mr-2">
              <i class="fas fa-circle text-success"></i> Ingresos:
              <label id="IngFLL"></label>
            </span>
            <span class="mr-2">
              <i class="fas fa-circle text-danger"></i> Egresos:
              <label id="EgrFLL"></label>
            </span>
          </div>
          <span class="mt-4 text-center small">Desde el <label id="FInicioFLL"></label> hasta el <label id="FFinFLL"></label></span>
        </div>
      </div>
    </div>
    <!-- Pie Chart FAU-->
    <div class="col-xl-4 col-lg-4">
      <div class="card shadow mb-4">
        <!-- Card Header - Dropdown -->
        <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
          <h6 class="m-0 font-weight-bold text-info">FARMACIA AV. UNIVERSIDAD, C.A</h6>
        </div>
        <!-- Card Body -->
        <div class="card-body">
          <div class="chart-pie pt-4 pb-2">
            <canvas id="ChartFAU"></canvas>
          </div>
          <div class="mt-4 text-center small">
            <span class="mr-2">
              <i class="fas fa-circle text-primary"></i> Activos:
              <label id="ActFAU"></label>
            </span>
            <span class="mr-2">
              <i class="fas fa-circle text-success"></i> Ingresos:
              <label id="IngFAU"></label>
            </span>
            <span class="mr-2">
              <i class="fas fa-circle text-danger"></i> Egresos:
              <label id="EgrFAU"></label>
            </span>
          </div>
          <span class="mt-4 text-center small">Desde el <label id="FInicioFAU"></label> hasta el <label id="FFinFAU"></label></span>
        </div>
      </div>
    </div>
  </div>
  <!-- Dashboard Metricas-->
  <!-- Dashboard RRHH-->
  <div class="card-deck">
    <div class="card border-dark mb-3" style="width: 14rem;">
      <div class="card-body text-left bg-dark">
        <h2 class="card-title">
          <span class="card-text text-white">
            <i class="fas fa-cogs"></i>
            <?php
            echo ''.$procesos_candidatos;
          ?>
          </span>
        </h2>
        <p class="card-text text-white">
        <?php
          echo 'Fases y procesos en tránsito';
        ?>
        </p>
      </div>
      <div class="card-footer bg-transparent border-dark text-right">
        <a href="/procesos_candidatos" class="btn btn-outline-dark btn-sm">Visualizar</a>
      </div>
    </div>
  </div>

  <div class="card-deck">
    <div class="card border-danger mb-3" style="width: 14rem;">
      <div class="card-body text-left bg-danger">
        <h2 class="card-title">
          <span class="card-text text-white">
            <i class="fas fa-sort-amount-up-alt"></i>
            <?php
            echo ''.$fases;
          ?>
          </span>
        </h2>
        <p class="card-text text-white">
        <?php
          echo 'Fases registradas';
        ?>
        </p>
      </div>
      <div class="card-footer bg-transparent border-danger text-right">
        <a href="/fases" class="btn btn-outline-danger btn-sm">Visualizar</a>
      </div>
    </div>

    <div class="card border-success mb-3" style="width: 14rem;">
      <div class="card-body text-left bg-success">
        <h3 class="card-title">
          <span class="card-text text-white">
            <i class="fas fa-user-plus"></i>
            <?php
            echo ''.$vacantes;
          ?>
          </span>
        </h3>
        <p class="card-text text-white">
        <?php
          echo 'Vacantes registradas';
        ?>
        </p>
      </div>
      <div class="card-footer bg-transparent border-success text-right">
        <a href="/vacantes" class="btn btn-outline-success btn-sm">Visualizar</a>
      </div>
    </div>

    <div class="card border-info mb-3" style="width: 14rem;">
      <div class="card-body text-left bg-info">
        <h3 class="card-title">
          <span class="card-text text-white">
            <i class="fas fa-user-edit"></i>
            <?php
            echo ''.$convocatoria;
          ?>
          </span>
        </h3>
        <p class="card-text text-white">
        <?php
          echo 'Convocatorias registradas';
        ?>
        </p>
      </div>
      <div class="card-footer bg-transparent border-info text-right">
        <a href="/convocatoria" class="btn btn-outline-info btn-sm">Visualizar</a>
      </div>
    </div>
  </div>

  <div class="card-deck">
    <div class="card border-warning mb-3" style="width: 14rem;">
      <div class="card-body text-left bg-warning">
        <h2 class="card-title">
          <span class="card-text text-white">
            <i class="fas fa-user-check"></i>
            <?php
            echo ''.$candidatos;
          ?>
          </span>
        </h2>
        <p class="card-text text-white">
        <?php
          echo 'Candidatos registrados';
        ?>
        </p>
      </div>
      <div class="card-footer bg-transparent border-warning text-right">
        <a href="/candidatos" class="btn btn-outline-warning btn-sm">Visualizar</a>
      </div>
    </div>

    <div class="card border-secondary mb-3" style="width: 14rem;">
      <div class="card-body text-left bg-secondary">
        <h2 class="card-title">
          <span class="card-text text-white">
            <i class="fas fa-tasks"></i>
            <?php
            echo ''.$pruebas;
          ?>
          </span>
        </h2>
        <p class="card-text text-white">
        <?php
          echo 'Pruebas registradas';
        ?>
        </p>
      </div>
      <div class="card-footer bg-transparent border-secondary text-right">
        <a href="/pruebas" class="btn btn-outline-secondary btn-sm">Visualizar</a>
      </div>
    </div>

    <div class="card border-dark mb-3" style="width: 14rem;">
      <div class="card-body text-left bg-dark">
        <h3 class="card-title">
          <span class="card-text text-white">
            <i class="fas fa-users"></i>
            <?php
            echo ''.$entrevistas;
          ?>
          </span>
        </h3>
        <p class="card-text text-white">
        <?php
          echo 'Entrevistas registradas';
        ?>
        </p>
      </div>
      <div class="card-footer bg-transparent border-dark text-right">
        <a href="/entrevistas" class="btn btn-outline-dark btn-sm">Visualizar</a>
      </div>
    </div>
  </div>

  <div class="card-deck">
    <div class="card border-danger mb-3" style="width: 14rem;">
      <div class="card-body text-left bg-danger">
        <h2 class="card-title">
          <span class="card-text text-white">
            <i class="fas fa-users-cog"></i>
            <?php
            echo ''.$practicas;
          ?>
          </span>
        </h2>
        <p class="card-text text-white">
        <?php
          echo 'Prácticas registradas';
        ?>
        </p>
      </div>
      <div class="card-footer bg-transparent border-danger text-right">
        <a href="/practicas" class="btn btn-outline-danger btn-sm">Visualizar</a>
      </div>
    </div>

    <div class="card border-success mb-3" style="width: 14rem;">
      <div class="card-body text-left bg-success">
        <h2 class="card-title">
          <span class="card-text text-white">
            <i class="fas fa-phone"></i>
            <?php
            echo ''.$contactos;
          ?>
          </span>
        </h2>
        <p class="card-text text-white">
        <?php
          echo 'Contactos de empresas registrados';
        ?>
        </p>
      </div>
      <div class="card-footer bg-transparent border-success text-right">
        <a href="/contactos" class="btn btn-outline-success btn-sm">Visualizar</a>
      </div>
    </div>

    <div class="card border-info mb-3" style="width: 14rem;">
      <div class="card-body text-left bg-info">
        <h2 class="card-title">
          <span class="card-text text-white">
            <i class="fas fa-user-md"></i>
            <?php
            echo ''.$examenesm;
          ?>
          </span>
        </h2>
        <p class="card-text text-white">
        <?php
          echo 'Examenes médicos registrados';
        ?>
        </p>
      </div>
      <div class="card-footer bg-transparent border-info text-right">
        <a href="/examenesm" class="btn btn-outline-info btn-sm">Visualizar</a>
      </div>
    </div>
  </div>

  <div class="card-deck">
    <div class="card border-warning mb-3" style="width: 14rem;">
      <div class="card-body text-left bg-warning">
        <h2 class="card-title">
          <span class="card-text text-white">
            <i class="far fa-address-card"></i>
            <?php
            echo ''.$empresaReferencias;
          ?>
          </span>
        </h2>
        <p class="card-text text-white">
        <?php
          echo 'Empresas de referencias laborales registradas';
        ?>
        </p>
      </div>
      <div class="card-footer bg-transparent border-warning text-right">
        <a href="/empresaReferencias" class="btn btn-outline-warning btn-sm">Visualizar</a>
      </div>
    </div>

    <div class="card border-secondary mb-3" style="width: 14rem;">
      <div class="card-body text-left bg-secondary">
        <h3 class="card-title">
          <span class="card-text text-white">
            <i class="fas fa-vials"></i>
            <?php
            echo ''.$laboratorios;
          ?>
          </span>
        </h3>
        <p class="card-text text-white">
        <?php
          echo 'Laboratorios registrados';
        ?>
        </p>
      </div>
      <div class="card-footer bg-transparent border-secondary text-right">
        <a href="/laboratorio" class="btn btn-outline-secondary btn-sm">Visualizar</a>
      </div>
    </div>
  </div>
  <!-- Dashboard RRHH-->
<?php
  }
?>
<!-- RRHH -->
<!-------------------------------------------------------------------------------->
<!-- RECEPCION -->
<?php
  if(Auth::user()->departamento == 'RECEPCION'){
?>
  <!-- Modal RECEPCION -->
  <div class="modal fade" id="exampleModalCenter" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title text-info" id="exampleModalCenterTitle"><i class="fas fa-bell text-info CP-beep"></i> Novedades</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <label>Hola <b class="text-info">{{ Auth::user()->name }}</b>.</label>
          <br/>
          Estas usando<b class="text-info">{{$CPharmaVersion}}</b>, para el departamento de <b class="text-info">{{ Auth::user()->departamento }}</b>, esta version incluye las siguientes mejoras:<br/><br/></label>
        <ul style="list-style:none">
          <li class="card-text text-dark" style="display: inline;">
          <i class="far fa-check-circle text-info" style="display: inline;"></i>
          Desde ya esta disponible el modulo:
          <b class="text-info">Orden de compra</b>!!
          </li>
        </ul>
        <ul style="list-style:none">
          <li class="card-text text-dark" style="display: inline;">
          <i class="far fa-check-circle text-info" style="display: inline;"></i>
          Desde ya esta disponible el reporte:
          <b class="text-info">Historico de productos</b>!!
          </li>
        </ul>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-outline-info" data-dismiss="modal">Aceptar</button>
        </div>
      </div>
    </div>
  </div>
  <!-- Modal RECEPCION -->
  <!-- Dashboard RECEPCION-->
  <!-- Dashboard RECEPCION-->

<?php
  }
?>
<!-- RECEPCION -->
<!-------------------------------------------------------------------------------->
<!-------------------------------------------------------------------------------->
<!-- COSTOS -->
<?php
  if(Auth::user()->departamento == 'COSTOS'){
?>
  <!-- Modal COSTOS -->
  <div class="modal fade" id="exampleModalCenter" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title text-info" id="exampleModalCenterTitle"><i class="fas fa-bell text-info CP-beep"></i> Novedades</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <label>Hola <b class="text-info">{{ Auth::user()->name }}</b>.</label>
          <br/>
          Estas usando<b class="text-info">{{$CPharmaVersion}}</b>, para el departamento de <b class="text-info">{{ Auth::user()->departamento }}</b>, esta version incluye las siguientes mejoras:<br/><br/></label>
        <ul style="list-style:none">
          <li class="card-text text-dark" style="display: inline;">
          <i class="far fa-check-circle text-info" style="display: inline;"></i>
          Desde ya esta disponible el modulo:
          <b class="text-info">Tasa Mercado</b>!!
          </li>
        </ul>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-outline-info" data-dismiss="modal">Aceptar</button>
        </div>
      </div>
    </div>
  </div>
  <!-- Modal COSTOS -->
  <!-- Dashboard COSTOS-->
  <!-- Dolar -->
  <div class="card-deck">
    <div class="card border-secondary mb-3" style="width: 10rem;">
      <div class="card-body text-left bg-secondary">
          <h3 class="card-title">
            <span class="card-text text-white">
              <i class="fas fa-money-bill-alt"></i>
              <?php
                echo 'Tasa Mercado: '.$TasaMercado.' '.SigVe;
              ?>
            </span>
          </h3>
          <p class="card-text text-white">
          <?php
            echo 'Ultima Actualizacion: '.$FechaTasaMercado;
          ?>
          </p>
      </div>
      <div class="card-footer bg-transparent border-secondary text-right">
        <a href="/dolar/" class="btn btn-outline-secondary btn-sm">Visualizar</a>
      </div>
    </div>
  </div>
  <!-- Dashboard COSTOS-->
<?php
  }
?>
<!-- COSTOS -->
<!-------------------------------------------------------------------------------->
<!-- ADMINISTRACION -->
<?php
  if(Auth::user()->departamento == 'ADMINISTRACION'){
    $configuracion = Configuracion::where('variable','DolarCalculo')->get();
    $TC = $configuracion[0]->valor;
?>
  <!-- Modal ADMINISTRACION -->
  <div class="modal fade" id="exampleModalCenter" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title text-info" id="exampleModalCenterTitle"><i class="fas fa-bell text-info CP-beep"></i> Novedades</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <label>Hola <b class="text-info">{{ Auth::user()->name }}</b>.</label>
          <br/>
          Estas usando<b class="text-info">{{$CPharmaVersion}}</b>, para el departamento de <b class="text-info">{{ Auth::user()->departamento }}</b>, esta version incluye las siguientes mejoras:<br/><br/></label>
        <ul style="list-style:none">
          <li class="card-text text-dark" style="display: inline;">
          <i class="far fa-check-circle text-info" style="display: inline;"></i>
          Desde ya esta disponible el modulo:
          <b class="text-info">Orden de compra</b>!!
          </li>
        </ul>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-outline-info" data-dismiss="modal">Aceptar</button>
        </div>
      </div>
    </div>
  </div>
  <!-- Modal ADMINISTRACION -->
  <!-- Dashboard ADMINISTRACION-->
  <div class="card-deck">
    <!-- Tasa Venta -->
    <div class="card border-dark mb-3" style="width: 14rem;">
      <div class="card-body text-left bg-dark">
        <h3 class="card-title">
          <span class="card-text text-white">
            <i class="fas fa-credit-card"></i>
            <?php
              echo 'Tasa Venta: '.$Tasa.' '.SigVe;
            ?>
          </span>
        </h3>
        <p class="card-text text-white">
        <?php
          echo 'Ultima Actualizacion: '.$tasaVenta;
        ?>
        </p>
      </div>
      <div class="card-footer bg-transparent border-dark text-right">
        <table style="width:100%;">
          <tr>
            <td style="float: left;">
              <p class="text-left">
                <strong>Tasa Mercado:</strong>
                <?php echo " ".number_format(((($TM/$TV)-1)*100),2,"," ,"." )." %"; ?>
                <br><strong>Tasa Calculo:</strong>
                <?php echo " ".number_format(((($TC/$TV)-1)*100),2,"," ,"." )." %"; ?>
              </p>
            </td>
            <td style="float: right;">
              <a href="/tasaVenta/" class="btn btn-outline-dark btn-sm">Visualizar</a>
            </td>
          </tr>
        </table>
      </div>
    </div>

    <!-- Dolar -->
    <div class="card border-secondary mb-3" style="width: 14rem;">
      <div class="card-body text-left bg-secondary">
          <h3 class="card-title">
            <span class="card-text text-white">
              <i class="fas fa-credit-card"></i>
              <?php
                echo 'Tasa Mercado: '.$TasaMercado.' '.SigVe;
              ?>
            </span>
          </h3>
          <p class="card-text text-white">
          <?php
            echo 'Ultima Actualizacion: '.$FechaTasaMercado;
          ?>
          </p>
      </div>
      <div class="card-footer bg-transparent border-secondary text-right">
        <table style="width:100%;">
          <tr>
            <td style="float: left;">
              <p class="text-left">
                <strong>Tasa Venta:</strong>
                <?php echo " ".number_format(((($TM/$TV)-1)*100),2,"," ,"." )." %"; ?>
                <br><strong>Tasa Calculo:</strong>
                <?php echo " ".number_format(((($TC/$TM)-1)*100),2,"," ,"." )." %"; ?>
              </p>
            </td>
            <td style="float: right;">
              <a href="/dolar/" class="btn btn-outline-secondary btn-sm">Visualizar</a>
            </td>
          </tr>
        </table>
      </div>
    </div>

    <!-- Tasa Calculo -->
    <div class="card border-dark mb-3" style="width: 10rem;">
        <div class="card-body text-left" style="background: #000;">
          <h3 class="card-title">
            <span class="card-text text-white">
              <i class="fas fa-calculator"></i>
              <?php
              echo 'Tasa Calculo: '.number_format($configuracion[0]->valor,2,"," ,"." );
            ?>
            </span>
          </h3>
          <p class="card-text text-white">
          <?php
            echo 'Ultima Actualizacion: '.$configuracion[0]->updated_at->format("d-m-Y h:i:s a");
          ?>
          </p>
        </div>
        <div class="card-footer bg-transparent border-dark text-right">
          <table style="width:100%;">
          <tr>
            <td style="float: left;">
              <p class="text-left">
                <strong>Tasa Venta:</strong>
                <?php echo " ".number_format(((($TC/$TV)-1)*100),2,"," ,"." )." %"; ?>
                <br><strong>Tasa Mercado:</strong>
                <?php echo " ".number_format(((($TC/$TM)-1)*100),2,"," ,"." )." %"; ?>
              </p>
            </td>
            <td style="float: right;">
              <a href="/configuracion/" class="btn btn-outline-dark btn-sm">Visualizar</a>
            </td>
          </tr>
        </table>
        </div>
    </div>
  </div>
  <!-- Dashboard ADMINISTRACION-->
<?php
  }
?>
<!-- ADMINISTRACION -->
<!-------------------------------------------------------------------------------->
<!-- LÍDER DE TIENDA -->
<?php
  if(Auth::user()->departamento == 'LÍDER DE TIENDA'){
?>
  <!-- Modal LÍDER DE TIENDA -->
  <div class="modal fade" id="exampleModalCenter" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title text-info" id="exampleModalCenterTitle"><i class="fas fa-bell text-info CP-beep"></i> Novedades</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <label>Hola <b class="text-info">{{ Auth::user()->name }}</b>.</label>
          <br/>
          Estas usando<b class="text-info">{{$CPharmaVersion}}</b>, para el departamento de <b class="text-info">{{ Auth::user()->departamento }}</b>, esta version incluye las siguientes mejoras:<br/><br/></label>
        <ul style="list-style:none">
          <li class="card-text text-dark" style="display: inline;">
          <i class="far fa-check-circle text-info" style="display: inline;"></i>
          Desde ya esta disponible el reporte:
          <b class="text-info">Articulos Estrella</b>!!
          </li>
        </ul>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-outline-info" data-dismiss="modal">Aceptar</button>
        </div>
      </div>
    </div>
  </div>
  <!-- Modal LÍDER DE TIENDA -->
  <!-- Dashboard LÍDER DE TIENDA-->
  <div class="card-deck">
    <div class="card border-dark mb-3" style="width: 14rem;">
      <div class="card-body text-left bg-dark">
        <h3 class="card-title">
          <span class="card-text text-white">
            <i class="fas fa-credit-card"></i>
            <?php
            echo 'Tasa Venta: '.$Tasa;
          ?>
          </span>
        </h3>
        <p class="card-text text-white">
        <?php
          echo 'Ultima Actualizacion: '.$tasaVenta;
        ?>
        </p>
      </div>
    </div>
  </div>
  <!-- Dashboard LÍDER DE TIENDA-->
<?php
  }
?>
<!-- LÍDER DE TIENDA -->
<!-------------------------------------------------------------------------------->
<!-- GERENCIA -->
<?php
  if(Auth::user()->departamento == 'GERENCIA'){
    $configuracion = Configuracion::where('variable','DolarCalculo')->get();
    $TC = $configuracion[0]->valor;
?>
  <!-- Modal GERENCIA -->
  <div class="modal fade" id="exampleModalCenter" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title text-info" id="exampleModalCenterTitle"><i class="fas fa-bell text-info CP-beep"></i> Novedades</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <label>Hola <b class="text-info">{{ Auth::user()->name }}</b>.</label>
          <br/>
          Estas usando<b class="text-info">{{$CPharmaVersion}}</b>, para el departamento de <b class="text-info">{{ Auth::user()->departamento }}</b>, esta version incluye las siguientes mejoras:<br/><br/></label>
        <ul style="list-style:none">
        <li class="card-text text-dark" style="display: inline;">
          <i class="far fa-check-circle text-info" style="display: inline;"></i>
          Desde ya esta disponible el modulo:
          <b class="text-info">Buscador de articulos</b>!!
          </li>
        </ul>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-outline-info" data-dismiss="modal">Aceptar</button>
        </div>
      </div>
    </div>
  </div>
  <!-- Modal GERENCIA -->
  <!-- Dashboard GERENCIA-->
  <div class="card-deck">
    <!-- Usuario -->
    <!--
    <div class="card border-warning mb-3" style="width: 10rem;">
      <div class="card-body text-left bg-warning">
        <h2 class="card-title">
          <span class="card-text text-white">
          <i class="fas fa-user"></i>
            <?php
            //echo ''.$usuarios;
          ?>
          </span>
        </h2>
        <p class="card-text text-white">Usuarios registrados</p>
      </div>
      <div class="card-footer bg-transparent border-warning text-right">
        <a href="/usuario/" class="btn btn-outline-warning btn-sm">Visualizar</a>
      </div>
    </div>
    -->

    <!-- Tasa Venta -->
    <div class="card border-dark mb-3" style="width: 14rem;">
      <div class="card-body text-left bg-dark">
        <h3 class="card-title">
          <span class="card-text text-white">
            <i class="fas fa-credit-card"></i>
            <?php
              echo 'Tasa Venta: '.$Tasa.' '.SigVe;
            ?>
          </span>
        </h3>
        <p class="card-text text-white">
        <?php
          echo 'Ultima Actualizacion: '.$tasaVenta;
        ?>
        </p>
      </div>
      <div class="card-footer bg-transparent border-dark text-right">
        <table style="width:100%;">
          <tr>
            <td style="float: left;">
              <p class="text-left">
                <strong>Tasa Mercado:</strong>
                <?php echo " ".number_format(((($TM/$TV)-1)*100),2,"," ,"." )." %"; ?>
                <br><strong>Tasa Calculo:</strong>
                <?php echo " ".number_format(((($TC/$TV)-1)*100),2,"," ,"." )." %"; ?>
              </p>
            </td>
            <td style="float: right;">
              <a href="/tasaVenta/" class="btn btn-outline-dark btn-sm">Visualizar</a>
            </td>
          </tr>
        </table>
      </div>
    </div>

    <!-- Dolar -->
    <div class="card border-secondary mb-3" style="width: 14rem;">
      <div class="card-body text-left bg-secondary">
          <h3 class="card-title">
            <span class="card-text text-white">
              <i class="fas fa-credit-card"></i>
              <?php
                echo 'Tasa Mercado: '.$TasaMercado.' '.SigVe;
              ?>
            </span>
          </h3>
          <p class="card-text text-white">
          <?php
            echo 'Ultima Actualizacion: '.$FechaTasaMercado;
          ?>
          </p>
      </div>
      <div class="card-footer bg-transparent border-secondary text-right">
        <table style="width:100%;">
          <tr>
            <td style="float: left;">
              <p class="text-left">
                <strong>Tasa Venta:</strong>
                <?php echo " ".number_format(((($TM/$TV)-1)*100),2,"," ,"." )." %"; ?>
                <br><strong>Tasa Calculo:</strong>
                <?php echo " ".number_format(((($TC/$TM)-1)*100),2,"," ,"." )." %"; ?>
              </p>
            </td>
            <td style="float: right;">
              <a href="/dolar/" class="btn btn-outline-secondary btn-sm">Visualizar</a>
            </td>
          </tr>
        </table>
      </div>
    </div>

    <!-- Tasa Calculo -->
    <div class="card border-dark mb-3" style="width: 10rem;">
        <div class="card-body text-left" style="background: #000;">
          <h3 class="card-title">
            <span class="card-text text-white">
              <i class="fas fa-calculator"></i>
              <?php
              echo 'Tasa Calculo: '.number_format($configuracion[0]->valor,2,"," ,"." );
            ?>
            </span>
          </h3>
          <p class="card-text text-white">
          <?php
            echo 'Ultima Actualizacion: '.$configuracion[0]->updated_at->format("d-m-Y h:i:s a");
          ?>
          </p>
        </div>
        <div class="card-footer bg-transparent border-dark text-right">
          <table style="width:100%;">
          <tr>
            <td style="float: left;">
              <p class="text-left">
                <strong>Tasa Venta:</strong>
                <?php echo " ".number_format(((($TC/$TV)-1)*100),2,"," ,"." )." %"; ?>
                <br><strong>Tasa Mercado:</strong>
                <?php echo " ".number_format(((($TC/$TM)-1)*100),2,"," ,"." )." %"; ?>
              </p>
            </td>
            <td style="float: right;">
              <a href="/configuracion/" class="btn btn-outline-dark btn-sm">Visualizar</a>
            </td>
          </tr>
        </table>
        </div>
    </div>

    </div>
  </div>

  <!-- MOVIMIENTOS -->
  <div class="card-deck">
    <div class="card border-danger mb-3" style="width: 14rem;">
      <div class="card-body text-left bg-danger">
        <h3 class="card-title">
          <span class="card-text text-white">
            <i class="fas fa-balance-scale-left"></i>
            <?php
            $saldo_actualBs = DB::table('ts_movimientos')
              ->where('tasa_ventas_id', 1)
              ->whereNull('diferido')
              ->orderBy('id', 'desc')
              ->first();

            echo 'Saldo disponible: '. number_format(DB::table('configuracions')
            ->where('id', 7)
            ->value('valor'), 2, ',', '.') . " " . SigVe;
          ?>
          </span>
        </h3>
        <p class="card-text text-white">
        <?php
          if(empty($saldo_actualBs)) {
            $ultimoMovimientoBs = '';
          }
          else {
            $ultimoMovimientoBs = $saldo_actualBs->updated_at;
          }

          echo 'Movimientos en bolivares registrados: ' . $movimientosBs;
          echo '<br>Fecha y hora actual: ' . date("d-m-Y h:i:s a");
          echo '<br>Ultimo movimiento: ' . date("d-m-Y h:i:s a", strtotime($ultimoMovimientoBs));
        ?>
        </p>
      </div>
      <div class="card-footer bg-transparent border-danger text-right">
        <a href="/movimientos?tasa_ventas_id=1" class="btn btn-outline-danger btn-sm">Visualizar</a>
      </div>
    </div>

    <div class="card border-success mb-3" style="width: 14rem;">
      <div class="card-body text-left bg-success">
        <h3 class="card-title">
          <span class="card-text text-white">
            <i class="fas fa-balance-scale"></i>
            <?php
            $saldo_actualDs = DB::table('ts_movimientos')
              ->where('tasa_ventas_id', 2)
              ->whereNull('diferido')
              ->orderBy('id', 'desc')
              ->first();

              echo 'Saldo disponible: ' . number_format(DB::table('configuracions')
            ->where('id', 8)
            ->value('valor'), 2, ',', '.') . " " . SigDolar;
          ?>
          </span>
        </h3>
        <p class="card-text text-white">
        <?php
          if(empty($saldo_actualDs)) {
            $ultimoMovimientoDs = '';
          }
          else {
            $ultimoMovimientoDs = $saldo_actualDs->updated_at;
          }

          echo 'Movimientos en dolares registrados: ' . $movimientosDs;
          echo '<br>Fecha y hora actual: ' . date("d-m-Y h:i:s a");
          echo '<br>Ultimo movimiento: ' . date("d-m-Y h:i:s a", strtotime($ultimoMovimientoDs));
        ?>
        </p>
      </div>
      <div class="card-footer bg-transparent border-success text-right">
        <a href="/movimientos?tasa_ventas_id=2" class="btn btn-outline-success btn-sm">Visualizar</a>
      </div>
    </div>
  </div>
  <!-- DIFERIDOS -->
    <div class="card-deck">
    <div class="card border-info mb-3" style="width: 14rem;">
      <div class="card-body text-left bg-info">
        <h3 class="card-title">
          <span class="card-text text-white">
            <i class="fas fa-lock"></i>
            <?php
            $diferido_actualBs = DB::table('ts_movimientos')
              ->where('tasa_ventas_id', 1)
              ->whereNotNull('diferido')
              ->orderBy('updated_at', 'desc')
              ->first();

            if(empty($diferido_actualBs)) {
                echo 'Diferido actual: '. number_format(0, 2, ',', '.') . " " . SigVe;
              }
              else {
                echo 'Diferido actual: '. number_format($diferido_actualBs->diferido_actual, 2, ',', '.') . " " . SigVe;
              }
          ?>
          </span>
        </h3>
        <p class="card-text text-white">
        <?php
          if(empty($diferido_actualBs)) {
            $ultimoDiferidoBs = '';
          }
          else {
            $ultimoDiferidoBs = $diferido_actualBs->updated_at;
          }

          echo 'Diferidos en bolivares registrados: ' . $diferidosBs;
          echo '<br>Fecha y hora actual: ' . date("d-m-Y h:i:s a");
          echo '<br>Ultimo diferido: ' . date("d-m-Y h:i:s a", strtotime($ultimoDiferidoBs));
        ?>
        </p>
      </div>
      <div class="card-footer bg-transparent border-info text-right">
        <a href="/diferidos?tasa_ventas_id=1" class="btn btn-outline-info btn-sm">Visualizar</a>
      </div>
    </div>

    <div class="card border-warning mb-3" style="width: 14rem;">
      <div class="card-body text-left bg-warning">
        <h3 class="card-title">
          <span class="card-text text-white">
            <i class="fas fa-lock"></i>
            <?php
            $diferido_actualDs = DB::table('ts_movimientos')
              ->where('tasa_ventas_id', 2)
              ->whereNotNull('diferido')
              ->orderBy('updated_at', 'desc')
              ->first();

              if(empty($diferido_actualDs)) {
                echo 'Diferido actual: ' . number_format(0, 2, ',', '.') . " " . SigDolar;
              }
              else {
                echo 'Diferido actual: ' . number_format($diferido_actualDs->diferido_actual, 2, ',', '.') . " " . SigDolar;
              }
          ?>
          </span>
        </h3>
        <p class="card-text text-white">
        <?php
          if(empty($diferido_actualDs)) {
            $ultimoDiferidoDs = '';
          }
          else {
            $ultimoDiferidoDs = $diferido_actualDs->updated_at;
          }

          echo 'Diferidos en dolares registrados: ' . $diferidosDs;
          echo '<br>Fecha y hora actual: ' . date("d-m-Y h:i:s a");
          echo '<br>Ultimo diferido: ' . date("d-m-Y h:i:s a", strtotime($ultimoDiferidoDs));
        ?>
        </p>
      </div>
      <div class="card-footer bg-transparent border-warning text-right">
        <a href="/diferidos?tasa_ventas_id=2" class="btn btn-outline-warning btn-sm">Visualizar</a>
      </div>
    </div>
  </div>
  <!-- Dashboard GERENCIA-->
<?php
  }
?>
<!-- GERENCIA -->
<!-------------------------------------------------------------------------------->
<!-- TECNOLOGIA -->
<?php
  if(Auth::user()->departamento == 'TECNOLOGIA'){
    $configuracion = Configuracion::where('variable','DolarCalculo')->get();
    $TC = $configuracion[0]->valor;
?>
  <!-- Modal TECNOLOGIA -->
  <div class="modal fade" id="exampleModalCenter" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title text-info" id="exampleModalCenterTitle"><i class="fas fa-bell text-info CP-beep"></i> Novedades</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <label>Hola <b class="text-info">{{ Auth::user()->name }}</b>.</label>
          <br/>
          Estas usando<b class="text-info">{{$CPharmaVersion}}</b>, para el departamento de <b class="text-info">{{ Auth::user()->departamento }}</b>, esta version incluye las siguientes mejoras:<br/><br/></label>
        <ul style="list-style:none">
        <li class="card-text text-dark" style="display: inline;">
          <i class="far fa-check-circle text-info" style="display: inline;"></i>
          Desde ya esta disponible el modulo:
          <b class="text-info">Buscador de articulos</b>!!
          </li>
        </ul>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-outline-info" data-dismiss="modal">Aceptar</button>
        </div>
      </div>
    </div>
  </div>

  <div class="card-deck">
    <!-- Usuario -->
    <!--
    <div class="card border-warning mb-3" style="width: 14rem;">
      <div class="card-body text-left bg-warning">
        <h2 class="card-title">
          <span class="card-text text-white">
          <i class="fas fa-user"></i>
            <?php
            //echo ''.$usuarios;
          ?>
          </span>
        </h2>
        <p class="card-text text-white">Usuarios registrados</p>
      </div>
      <div class="card-footer bg-transparent border-warning text-right">
        <a href="/usuario/" class="btn btn-outline-warning btn-sm">Visualizar</a>
      </div>
    </div>
    -->

    <!-- Tasa Venta -->
    <div class="card border-dark mb-3" style="width: 14rem;">
      <div class="card-body text-left bg-dark">
        <h3 class="card-title">
          <span class="card-text text-white">
            <i class="fas fa-credit-card"></i>
            <?php
              echo 'Tasa Venta: '.$Tasa.' '.SigVe;
            ?>
          </span>
        </h3>
        <p class="card-text text-white">
        <?php
          echo 'Ultima Actualizacion: '.$tasaVenta;
        ?>
        </p>
      </div>
      <div class="card-footer bg-transparent border-dark text-right">
        <table style="width:100%;">
          <tr>
            <td style="float: left;">
              <p class="text-left">
                <strong>Tasa Mercado:</strong>
                <?php echo " ".number_format(((($TM/$TV)-1)*100),2,"," ,"." )." %"; ?>
                <br><strong>Tasa Calculo:</strong>
                <?php echo " ".number_format(((($TC/$TV)-1)*100),2,"," ,"." )." %"; ?>
              </p>
            </td>
            <td style="float: right;">
              <a href="/tasaVenta/" class="btn btn-outline-dark btn-sm">Visualizar</a>
            </td>
          </tr>
        </table>
      </div>
    </div>

    <!-- Dolar -->
    <div class="card border-secondary mb-3" style="width: 14rem;">
      <div class="card-body text-left bg-secondary">
          <h3 class="card-title">
            <span class="card-text text-white">
              <i class="fas fa-credit-card"></i>
              <?php
                echo 'Tasa Mercado: '.$TasaMercado.' '.SigVe;
              ?>
            </span>
          </h3>
          <p class="card-text text-white">
          <?php
            echo 'Ultima Actualizacion: '.$FechaTasaMercado;
          ?>
          </p>
      </div>
      <div class="card-footer bg-transparent border-secondary text-right">
        <table style="width:100%;">
          <tr>
            <td style="float: left;">
              <p class="text-left">
                <strong>Tasa Venta:</strong>
                <?php echo " ".number_format(((($TM/$TV)-1)*100),2,"," ,"." )." %"; ?>
                <br><strong>Tasa Calculo:</strong>
                <?php echo " ".number_format(((($TC/$TM)-1)*100),2,"," ,"." )." %"; ?>
              </p>
            </td>
            <td style="float: right;">
              <a href="/dolar/" class="btn btn-outline-secondary btn-sm">Visualizar</a>
            </td>
          </tr>
        </table>
      </div>
    </div>

    <!-- Tasa Calculo -->
    <div class="card border-dark mb-3" style="width: 10rem;">
        <div class="card-body text-left" style="background: #000;">
          <h3 class="card-title">
            <span class="card-text text-white">
              <i class="fas fa-calculator"></i>
              <?php
              echo 'Tasa Calculo: '.number_format($configuracion[0]->valor,2,"," ,"." );
            ?>
            </span>
          </h3>
          <p class="card-text text-white">
          <?php
            echo 'Ultima Actualizacion: '.$configuracion[0]->updated_at->format("d-m-Y h:i:s a");
          ?>
          </p>
        </div>
        <div class="card-footer bg-transparent border-dark text-right">
          <table style="width:100%;">
          <tr>
            <td style="float: left;">
              <p class="text-left">
                <strong>Tasa Venta:</strong>
                <?php echo " ".number_format(((($TC/$TV)-1)*100),2,"," ,"." )." %"; ?>
                <br><strong>Tasa Mercado:</strong>
                <?php echo " ".number_format(((($TC/$TM)-1)*100),2,"," ,"." )." %"; ?>
              </p>
            </td>
            <td style="float: right;">
              <a href="/configuracion/" class="btn btn-outline-dark btn-sm">Visualizar</a>
            </td>
          </tr>
        </table>
        </div>
    </div>
  </div>
  <!-- Dashboard TECNOLOGIA-->
<?php
  }
?>
<!-- TECNOLOGIA -->
<!-------------------------------------------------------------------------------->
<!-- CONTACTO -->
<hr class="row align-items-start col-12">
  <div class="card-deck">
    <div class="card border-info" style="width: 14rem;">
      <div class="card-body text-left bg-info">
        <h2 class="card-title">
          <span class="card-text text-warning">
            <i class="far fa-lightbulb CP-beep"></i>
          </span>
          <span class="card-text text-white">
            Tienes una idea.?
          </span>
        </h2>
        <div class="text-center">
          <div class="text-center" style="display: inline-block; vertical-align: middle;">
            <h3 class="card-text text-white"><i class="far fa-keyboard"></i></h3>
            <h5 class="card-text text-white">Redacta tu idea</h5>
          </div>
          <div class="text-center" style="display: inline-block; vertical-align: middle;">
            <h3 class="card-text text-white"><i class="fas fa-angle-double-right"><br/><br/></i>
            </h3>
          </div>
          <div class="text-center" style="display: inline-block; vertical-align: middle;">
            <h3 class="card-text text-white"><i class="far fa-envelope"></i></h3>
            <h5 class="card-text text-white">Enviala a scova@farmacia72.com.ve</h5>
          </div>
          <div class="text-center" style="display: inline-block; vertical-align: middle;">
            <h3 class="card-text text-white"><i class="fas fa-angle-double-right"><br/><br/></i></h3>
          </div>
          <div class="text-center" style="display: inline-block; vertical-align: middle;">
            <h3 class="card-text text-white"><i class="far fa-clock"></i></h3>
            <h5 class="card-text text-white">Espera nuestro contacto</h5>
          </div>
        </div>
      </div>
    </div>
  </div>
<!-- CONTACTO -->
<!-------------------------------------------------------------------------------->
  <script>
    $(document).ready(function(){
        $('[data-toggle="tooltip"]').tooltip();
    });
    $('#exampleModalCenter').modal('show')
  </script>
@endsection
