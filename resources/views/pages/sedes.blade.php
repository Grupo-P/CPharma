@extends('layouts.model')

@section('title')
  Reportes
@endsection

@section('content')

    <?php
        include(app_path().'\functions\config.php');
        include(app_path().'\functions\functions.php');
        include(app_path().'\functions\querys_mysql.php');
        include(app_path().'\functions\querys_sqlserver.php');
        $SedeConnection = FG_Mi_Ubicacion();
    ?>

    <h1 class="h5 text-info">
        <i class="fas fa-file-invoice"></i>
        Reportes
    </h1>
    <hr class="row align-items-start col-12">

<!-------------------------------------------------------------------------------->
<!-- CPHARMA ON LINE -->
    <h1 class="h5 text-info text-center">
        <i class="fas fa-store-alt"></i>
        ON LINE
    </h1>
    <hr class="row align-items-start col-12">
<!-------------------------------------------------------------------------------->
<!-- CASO GP -->
<?php
    if($SedeConnection == 'GP' || $SedeConnection == 'ARG'){
?>
    <?php
        if(Auth::user()->sede == 'FARMACIA TIERRA NEGRA, C.A.'){
    ?>
    <div class="card-deck">
        <div class="card border-danger mb-3" style="width: 14rem;">
        <div class="card-body text-left bg-danger">
            <h5 class="card-title">
                <span class="card-text text-white">
                    <?php echo "".SedeFTN; ?>
                </span>
            </h5>
        </div>
        <div class="card-footer bg-transparent border-danger text-right">
            <a href="http://cpharmaftn.com/" role="button" class="btn btn-outline-danger btn-sm" target="_blank"></i>Ver reportes</a>
        </div>
        </div>
    </div>
    <?php
    }
        if(Auth::user()->sede == 'FARMACIA LA LAGO,C.A.'){
    ?>
    <div class="card-deck">
        <div class="card border-success mb-3" style="width: 14rem;">
        <div class="card-body text-left bg-success">
            <h5 class="card-title">
                <span class="card-text text-white">
                    <?php echo "".SedeFLL; ?>
                </span>
            </h5>
        </div>
        <div class="card-footer bg-transparent border-success text-right">
              <a href="http://cpharmafll.com/" role="button" class="btn btn-outline-success btn-sm" target="_blank"></i>Ver reportes</a>
        </div>
        </div>
    </div>
    <?php
    }
        if(Auth::user()->sede == 'FARMACIA AVENIDA UNIVERSIDAD, C.A.'){
    ?>
    <div class="card-deck">
        <div class="card border-info mb-3" style="width: 14rem;">
        <div class="card-body text-left bg-info">
            <h5 class="card-title">
                <span class="card-text text-white">
                    <?php echo "".SedeFAU; ?>
                </span>
            </h5>
        </div>
        <div class="card-footer bg-transparent border-info text-right">
            <a href="http://cpharmafau.com/" role="button" class="btn btn-outline-info btn-sm" target="_blank"></i>Ver reportes</a>
        </div>
    </div>
  </div>
  <?php
    }
        if(Auth::user()->sede == 'GRUPO P, C.A'){
    ?>
        <div class="card-deck">
            <div class="card border-danger mb-3" style="width: 14rem;">
                <div class="card-body text-left bg-danger">
                <h5 class="card-title">
                    <span class="card-text text-white">
                        <?php echo "".SedeFTN; ?>
                    </span>
                </h5>
                </div>
            <div class="card-footer bg-transparent border-danger text-right">
                <a href="http://cpharmaftn.com/" role="button" class="btn btn-outline-danger btn-sm" target="_blank"></i>Ver reportes</a>
            </div>
            </div>
            <div class="card border-success mb-3" style="width: 14rem;">
            <div class="card-body text-left bg-success">
                <h5 class="card-title">
                    <span class="card-text text-white">
                        <?php echo "".SedeFLL; ?>
                    </span>
                </h5>
            </div>
            <div class="card-footer bg-transparent border-success text-right">
                  <a href="http://cpharmafll.com/" role="button" class="btn btn-outline-success btn-sm" target="_blank"></i>Ver reportes</a>
            </div>
            </div>
            <div class="card border-info mb-3" style="width: 14rem;">
            <div class="card-body text-left bg-info">
                <h5 class="card-title">
                    <span class="card-text text-white">
                        <?php echo "".SedeFAU; ?>
                    </span>
                </h5>
            </div>
            <div class="card-footer bg-transparent border-info text-right">
                <a href="http://cpharmafau.com/" role="button" class="btn btn-outline-info btn-sm" target="_blank"></i>Ver reportes</a>
            </div>
        </div>
        </div>
        <div class="card-deck">
            <div class="card border-warning mb-3" style="width: 14rem;">
                <div class="card-body text-left bg-warning">
                <h5 class="card-title">
                    <span class="card-text text-white">
                        <?php echo "".SedeKDI; ?>
                    </span>
                </h5>
                </div>
            <div class="card-footer bg-transparent border-warning text-right">
                <a href="http://cpharmakdi.com/" role="button" class="btn btn-outline-warning btn-sm" target="_blank"></i>Ver reportes</a>
            </div>
            </div>
            <div class="card border-secondary mb-3" style="width: 14rem;">
            <div class="card-body text-left bg-secondary">
                <h5 class="card-title">
                    <span class="card-text text-white">
                        <?php echo "".SedeFSM; ?>
                    </span>
                </h5>
            </div>
            <div class="card-footer bg-transparent border-secondary text-right">
                  <a href="http://cpharmafsm.com/" role="button" class="btn btn-outline-secondary btn-sm" target="_blank"></i>Ver reportes</a>
            </div>
            </div>
            <div class="card border-dark mb-3" style="width: 14rem;">
                <div class="card-body text-left bg-dark">
                    <h5 class="card-title">
                        <span class="card-text text-white">
                            <?php echo "".SedeFEC; ?>
                        </span>
                    </h5>
                </div>
                <div class="card-footer bg-transparent border-dark text-right">
                      <a href="http://cpharmafec.com/" role="button" class="btn btn-outline-dark btn-sm" target="_blank"></i>Ver reportes</a>
                </div>
            </div>
            <div class="card border-danger mb-3" style="width: 14rem;">
                <div class="card-body text-left bg-danger">
                    <h5 class="card-title">
                        <span class="card-text text-white">
                            <?php echo "".SedeKD73; ?>
                        </span>
                    </h5>
                </div>
                <div class="card-footer bg-transparent border-danger text-right">
                      <a href="http://cpharmakd73.com/" role="button" class="btn btn-outline-danger btn-sm" target="_blank"></i>Ver reportes</a>
                </div>
            </div>
        </div>
    <?php
    }
        if(Auth::user()->sede == 'FARMACIAS KD EXPRESS, C.A.'){
    ?>
        <div class="card-deck">
            <div class="card border-warning mb-3" style="width: 14rem;">
            <div class="card-body text-left bg-warning">
                <h5 class="card-title">
                    <span class="card-text text-white">
                        <?php echo "".SedeKDI; ?>
                    </span>
                </h5>
            </div>
            <div class="card-footer bg-transparent border-warning text-right">
                <a href="http://cpharmakdi.com/" role="button" class="btn btn-outline-warning btn-sm" target="_blank"></i>Ver reportes</a>
            </div>
        </div>
    </div>
    <?php
    }
        if(Auth::user()->sede == 'FARMACIA MILLENNIUM 2000, C.A'){
    ?>
        <div class="card-deck">
            <div class="card border-secondary mb-3" style="width: 14rem;">
            <div class="card-body text-left bg-secondary">
                <h5 class="card-title">
                    <span class="card-text text-white">
                        <?php echo "".SedeFSM; ?>
                    </span>
                </h5>
            </div>
            <div class="card-footer bg-transparent border-secondary text-right">
                <a href="http://cpharmafsm.com/" role="button" class="btn btn-outline-secondary btn-sm" target="_blank"></i>Ver reportes</a>
            </div>
        </div>
    </div>
    <?php
    }
        if(Auth::user()->sede == 'FARMACIA EL CALLEJON, C.A.'){
    ?>
        <div class="card-deck">
            <div class="card border-dark mb-3" style="width: 14rem;">
            <div class="card-body text-left bg-dark">
                <h5 class="card-title">
                    <span class="card-text text-white">
                        <?php echo "".SedeFEC; ?>
                    </span>
                </h5>
            </div>
            <div class="card-footer bg-transparent border-dark text-right">
                <a href="http://cpharmafec.com/" role="button" class="btn btn-outline-dark btn-sm" target="_blank"></i>Ver reportes</a>
            </div>
        </div>
    </div>
    <?php
    }
        if(Auth::user()->sede == 'FARMACIAS KD EXPRESS, C.A. - KD73'){
    ?>
        <div class="card-deck">
            <div class="card border-danger mb-3" style="width: 14rem;">
            <div class="card-body text-left bg-danger">
                <h5 class="card-title">
                    <span class="card-text text-white">
                        <?php echo "".SedeKD73; ?>
                    </span>
                </h5>
            </div>
            <div class="card-footer bg-transparent border-danger text-right">
                <a href="http://cpharmakd73.com/" role="button" class="btn btn-outline-danger btn-sm" target="_blank"></i>Ver reportes</a>
            </div>
        </div>
    </div>
    <?php
    }
    ?>
<!-- CASO GP -->
<!-------------------------------------------------------------------------------->
<!-- CASO FTN -->
<?php
}
    if($SedeConnection == 'FTN'){
?>
    <?php
        if(Auth::user()->sede == 'FARMACIA TIERRA NEGRA, C.A.'){
    ?>
    <div class="card-deck">
        <div class="card border-danger mb-3" style="width: 14rem;">
        <div class="card-body text-left bg-danger">
            <h5 class="card-title">
                <span class="card-text text-white">
                    <?php echo "".SedeFTN; ?>
                </span>
            </h5>
        </div>
        <div class="card-footer bg-transparent border-danger text-right">
                <form action="/reporte/" style="display: inline;">
                @csrf
                <input id="SEDE" name="SEDE" type="hidden" value="FTN">
                <button type="submit" name="Reporte" role="button" class="btn btn-outline-danger btn-sm"></i>Ver reportes</button>
                </form>
        </div>
        </div>
    </div>
    <?php
    }
        if(Auth::user()->sede == 'FARMACIA LA LAGO,C.A.'){
    ?>
    <div class="card-deck">
        <div class="card border-success mb-3" style="width: 14rem;">
        <div class="card-body text-left bg-success">
            <h5 class="card-title">
                <span class="card-text text-white">
                    <?php echo "".SedeFLL; ?>
                </span>
            </h5>
        </div>
        <div class="card-footer bg-transparent border-success text-right">
              <a href="http://cpharmafll.com/" role="button" class="btn btn-outline-success btn-sm" target="_blank"></i>Ver reportes</a>
        </div>
        </div>
    </div>
    <?php
    }
        if(Auth::user()->sede == 'FARMACIA AVENIDA UNIVERSIDAD, C.A.'){
    ?>
    <div class="card-deck">
        <div class="card border-info mb-3" style="width: 14rem;">
        <div class="card-body text-left bg-info">
            <h5 class="card-title">
                <span class="card-text text-white">
                    <?php echo "".SedeFAU; ?>
                </span>
            </h5>
        </div>
        <div class="card-footer bg-transparent border-info text-right">
            <a href="http://cpharmafau.com/" role="button" class="btn btn-outline-info btn-sm" target="_blank"></i>Ver reportes</a>
        </div>
    </div>
  </div>
  <?php
    }
        if(Auth::user()->sede == 'GRUPO P, C.A'){
    ?>
        <div class="card-deck">
            <div class="card border-danger mb-3" style="width: 14rem;">
                <div class="card-body text-left bg-danger">
                <h5 class="card-title">
                    <span class="card-text text-white">
                        <?php echo "".SedeFTN; ?>
                    </span>
                </h5>
                </div>
            <div class="card-footer bg-transparent border-danger text-right">
                    <form action="/reporte/" style="display: inline;">
                    @csrf
                    <input id="SEDE" name="SEDE" type="hidden" value="FTN">
                    <button type="submit" name="Reporte" role="button" class="btn btn-outline-danger btn-sm"></i>Ver reportes</button>
                    </form>
            </div>
            </div>
            <div class="card border-success mb-3" style="width: 14rem;">
            <div class="card-body text-left bg-success">
                <h5 class="card-title">
                    <span class="card-text text-white">
                        <?php echo "".SedeFLL; ?>
                    </span>
                </h5>
            </div>
            <div class="card-footer bg-transparent border-success text-right">
                  <a href="http://cpharmafll.com/" role="button" class="btn btn-outline-success btn-sm" target="_blank"></i>Ver reportes</a>
            </div>
            </div>
            <div class="card border-info mb-3" style="width: 14rem;">
            <div class="card-body text-left bg-info">
                <h5 class="card-title">
                    <span class="card-text text-white">
                        <?php echo "".SedeFAU; ?>
                    </span>
                </h5>
            </div>
            <div class="card-footer bg-transparent border-info text-right">
                <a href="http://cpharmafau.com/" role="button" class="btn btn-outline-info btn-sm" target="_blank"></i>Ver reportes</a>
            </div>
        </div>
      </div>
      <div class="card-deck">
            <div class="card border-warning mb-3" style="width: 14rem;">
                <div class="card-body text-left bg-warning">
                <h5 class="card-title">
                    <span class="card-text text-white">
                        <?php echo "".SedeKDI; ?>
                    </span>
                </h5>
                </div>
            <div class="card-footer bg-transparent border-warning text-right">
                <a href="http://cpharmakdi.com/" role="button" class="btn btn-outline-warning btn-sm" target="_blank"></i>Ver reportes</a>
            </div>
            </div>
            <div class="card border-secondary mb-3" style="width: 14rem;">
            <div class="card-body text-left bg-secondary">
                <h5 class="card-title">
                    <span class="card-text text-white">
                        <?php echo "".SedeFSM; ?>
                    </span>
                </h5>
            </div>
            <div class="card-footer bg-transparent border-secondary text-right">
                  <a href="http://cpharmafsm.com/" role="button" class="btn btn-outline-secondary btn-sm" target="_blank"></i>Ver reportes</a>
            </div>
            </div>
            <div class="card border-dark mb-3" style="width: 14rem;">
                <div class="card-body text-left bg-dark">
                    <h5 class="card-title">
                        <span class="card-text text-white">
                            <?php echo "".SedeFEC; ?>
                        </span>
                    </h5>
                </div>
                <div class="card-footer bg-transparent border-dark text-right">
                      <a href="http://cpharmafec.com/" role="button" class="btn btn-outline-dark btn-sm" target="_blank"></i>Ver reportes</a>
                </div>
            </div>
            <div class="card border-danger mb-3" style="width: 14rem;">
                <div class="card-body text-left bg-danger">
                    <h5 class="card-title">
                        <span class="card-text text-white">
                            <?php echo "".SedeKD73; ?>
                        </span>
                    </h5>
                </div>
                <div class="card-footer bg-transparent border-danger text-right">
                      <a href="http://cpharmakd73.com/" role="button" class="btn btn-outline-danger btn-sm" target="_blank"></i>Ver reportes</a>
                </div>
            </div>
        </div>
      <?php
    }
        if(Auth::user()->sede == 'FARMACIAS KD EXPRESS, C.A.'){
    ?>
        <div class="card-deck">
            <div class="card border-warning mb-3" style="width: 14rem;">
            <div class="card-body text-left bg-warning">
                <h5 class="card-title">
                    <span class="card-text text-white">
                        <?php echo "".SedeKDI; ?>
                    </span>
                </h5>
            </div>
            <div class="card-footer bg-transparent border-warning text-right">
                <a href="http://cpharmakdi.com/" role="button" class="btn btn-outline-warning btn-sm" target="_blank"></i>Ver reportes</a>
            </div>
        </div>
    </div>
    <?php
    }
        if(Auth::user()->sede == 'FARMACIA MILLENNIUM 2000, C.A'){
    ?>
        <div class="card-deck">
            <div class="card border-secondary mb-3" style="width: 14rem;">
            <div class="card-body text-left bg-secondary">
                <h5 class="card-title">
                    <span class="card-text text-white">
                        <?php echo "".SedeFSM; ?>
                    </span>
                </h5>
            </div>
            <div class="card-footer bg-transparent border-secondary text-right">
                <a href="http://cpharmafsm.com/" role="button" class="btn btn-outline-secondary btn-sm" target="_blank"></i>Ver reportes</a>
            </div>
        </div>
    </div>
    <?php
    }
        if(Auth::user()->sede == 'FARMACIA EL CALLEJON, C.A.'){
    ?>
        <div class="card-deck">
            <div class="card border-dark mb-3" style="width: 14rem;">
            <div class="card-body text-left bg-dark">
                <h5 class="card-title">
                    <span class="card-text text-white">
                        <?php echo "".SedeFEC; ?>
                    </span>
                </h5>
            </div>
            <div class="card-footer bg-transparent border-dark text-right">
                <a href="http://cpharmafec.com/" role="button" class="btn btn-outline-dark btn-sm" target="_blank"></i>Ver reportes</a>
            </div>
        </div>
    </div>
    <?php
    }
        if(Auth::user()->sede == 'FARMACIAS KD EXPRESS, C.A. - KD73'){
    ?>
        <div class="card-deck">
            <div class="card border-danger mb-3" style="width: 14rem;">
            <div class="card-body text-left bg-danger">
                <h5 class="card-title">
                    <span class="card-text text-white">
                        <?php echo "".SedeKD73; ?>
                    </span>
                </h5>
            </div>
            <div class="card-footer bg-transparent border-danger text-right">
                <a href="http://cpharmakd73.com/" role="button" class="btn btn-outline-danger btn-sm" target="_blank"></i>Ver reportes</a>
            </div>
        </div>
    </div>
    <?php
    }
    ?>
<!-- CASO FTN -->
<!-------------------------------------------------------------------------------->
<!-- CASO FLL -->
<?php
}
    if($SedeConnection == 'FLL'){
?>
    <?php
        if(Auth::user()->sede == 'FARMACIA TIERRA NEGRA, C.A.'){
    ?>
    <div class="card-deck">
        <div class="card border-danger mb-3" style="width: 14rem;">
        <div class="card-body text-left bg-danger">
            <h5 class="card-title">
                <span class="card-text text-white">
                    <?php echo "".SedeFTN; ?>
                </span>
            </h5>
        </div>
        <div class="card-footer bg-transparent border-danger text-right">
            <a href="http://cpharmaftn.com/" role="button" class="btn btn-outline-danger btn-sm" target="_blank"></i>Ver reportes</a>
        </div>
        </div>
    </div>
    <?php
    }
        if(Auth::user()->sede == 'FARMACIA LA LAGO,C.A.'){
    ?>
    <div class="card-deck">
        <div class="card border-success mb-3" style="width: 14rem;">
        <div class="card-body text-left bg-success">
            <h5 class="card-title">
                <span class="card-text text-white">
                    <?php echo "".SedeFLL; ?>
                </span>
            </h5>
        </div>
        <div class="card-footer bg-transparent border-success text-right">
                <form action="/reporte/" style="display: inline;">
                @csrf
                <input id="SEDE" name="SEDE" type="hidden" value="FLL">
                <button type="submit" name="Reporte" role="button" class="btn btn-outline-success btn-sm"></i>Ver reportes</button>
                </form>
        </div>
        </div>
    </div>
    <?php
    }
        if(Auth::user()->sede == 'FARMACIA AVENIDA UNIVERSIDAD, C.A.'){
    ?>
    <div class="card-deck">
        <div class="card border-info mb-3" style="width: 14rem;">
        <div class="card-body text-left bg-info">
            <h5 class="card-title">
                <span class="card-text text-white">
                    <?php echo "".SedeFAU; ?>
                </span>
            </h5>
        </div>
        <div class="card-footer bg-transparent border-info text-right">
            <a href="http://cpharmafau.com/" role="button" class="btn btn-outline-info btn-sm" target="_blank"></i>Ver reportes</a>
        </div>
    </div>
  </div>
  <?php
    }
        if(Auth::user()->sede == 'GRUPO P, C.A'){
    ?>
        <div class="card-deck">
            <div class="card border-danger mb-3" style="width: 14rem;">
                <div class="card-body text-left bg-danger">
                <h5 class="card-title">
                    <span class="card-text text-white">
                        <?php echo "".SedeFTN; ?>
                    </span>
                </h5>
                </div>
            <div class="card-footer bg-transparent border-danger text-right">
                <a href="http://cpharmaftn.com/" role="button" class="btn btn-outline-danger btn-sm" target="_blank"></i>Ver reportes</a>
            </div>
            </div>
            <div class="card border-success mb-3" style="width: 14rem;">
            <div class="card-body text-left bg-success">
                <h5 class="card-title">
                    <span class="card-text text-white">
                        <?php echo "".SedeFLL; ?>
                    </span>
                </h5>
            </div>
            <div class="card-footer bg-transparent border-success text-right">
                    <form action="/reporte/" style="display: inline;">
                    @csrf
                    <input id="SEDE" name="SEDE" type="hidden" value="FLL">
                    <button type="submit" name="Reporte" role="button" class="btn btn-outline-success btn-sm"></i>Ver reportes</button>
                    </form>
            </div>
            </div>
            <div class="card border-info mb-3" style="width: 14rem;">
            <div class="card-body text-left bg-info">
                <h5 class="card-title">
                    <span class="card-text text-white">
                        <?php echo "".SedeFAU; ?>
                    </span>
                </h5>
            </div>
            <div class="card-footer bg-transparent border-info text-right">
                <a href="http://cpharmafau.com/" role="button" class="btn btn-outline-info btn-sm" target="_blank"></i>Ver reportes</a>
            </div>
        </div>
      </div>
      <div class="card-deck">
            <div class="card border-warning mb-3" style="width: 14rem;">
                <div class="card-body text-left bg-warning">
                <h5 class="card-title">
                    <span class="card-text text-white">
                        <?php echo "".SedeKDI; ?>
                    </span>
                </h5>
                </div>
            <div class="card-footer bg-transparent border-warning text-right">
                <a href="http://cpharmakdi.com/" role="button" class="btn btn-outline-warning btn-sm" target="_blank"></i>Ver reportes</a>
            </div>
            </div>
            <div class="card border-secondary mb-3" style="width: 14rem;">
            <div class="card-body text-left bg-secondary">
                <h5 class="card-title">
                    <span class="card-text text-white">
                        <?php echo "".SedeFSM; ?>
                    </span>
                </h5>
            </div>
            <div class="card-footer bg-transparent border-secondary text-right">
                  <a href="http://cpharmafsm.com/" role="button" class="btn btn-outline-secondary btn-sm" target="_blank"></i>Ver reportes</a>
            </div>
            </div>
            <div class="card border-dark mb-3" style="width: 14rem;">
                <div class="card-body text-left bg-dark">
                    <h5 class="card-title">
                        <span class="card-text text-white">
                            <?php echo "".SedeFEC; ?>
                        </span>
                    </h5>
                </div>
                <div class="card-footer bg-transparent border-dark text-right">
                      <a href="http://cpharmafec.com/" role="button" class="btn btn-outline-dark btn-sm" target="_blank"></i>Ver reportes</a>
                </div>
            </div>
            <div class="card border-danger mb-3" style="width: 14rem;">
                <div class="card-body text-left bg-danger">
                    <h5 class="card-title">
                        <span class="card-text text-white">
                            <?php echo "".SedeKD73; ?>
                        </span>
                    </h5>
                </div>
                <div class="card-footer bg-transparent border-danger text-right">
                      <a href="http://cpharmakd73.com/" role="button" class="btn btn-outline-danger btn-sm" target="_blank"></i>Ver reportes</a>
                </div>
            </div>
        </div>
      <?php
    }
        if(Auth::user()->sede == 'FARMACIAS KD EXPRESS, C.A.'){
    ?>
        <div class="card-deck">
            <div class="card border-warning mb-3" style="width: 14rem;">
            <div class="card-body text-left bg-warning">
                <h5 class="card-title">
                    <span class="card-text text-white">
                        <?php echo "".SedeKDI; ?>
                    </span>
                </h5>
            </div>
            <div class="card-footer bg-transparent border-warning text-right">
                <a href="http://cpharmakdi.com/" role="button" class="btn btn-outline-warning btn-sm" target="_blank"></i>Ver reportes</a>
            </div>
        </div>
    </div>
    <?php
    }
        if(Auth::user()->sede == 'FARMACIA MILLENNIUM 2000, C.A'){
    ?>
        <div class="card-deck">
            <div class="card border-secondary mb-3" style="width: 14rem;">
            <div class="card-body text-left bg-secondary">
                <h5 class="card-title">
                    <span class="card-text text-white">
                        <?php echo "".SedeFSM; ?>
                    </span>
                </h5>
            </div>
            <div class="card-footer bg-transparent border-secondary text-right">
                <a href="http://cpharmafsm.com/" role="button" class="btn btn-outline-secondary btn-sm" target="_blank"></i>Ver reportes</a>
            </div>
        </div>
    </div>
    <?php
    }
        if(Auth::user()->sede == 'FARMACIA EL CALLEJON, C.A.'){
    ?>
        <div class="card-deck">
            <div class="card border-dark mb-3" style="width: 14rem;">
            <div class="card-body text-left bg-dark">
                <h5 class="card-title">
                    <span class="card-text text-white">
                        <?php echo "".SedeFEC; ?>
                    </span>
                </h5>
            </div>
            <div class="card-footer bg-transparent border-dark text-right">
                <a href="http://cpharmafec.com/" role="button" class="btn btn-outline-dark btn-sm" target="_blank"></i>Ver reportes</a>
            </div>
        </div>
    </div>
    <?php
    }
        if(Auth::user()->sede == 'FARMACIAS KD EXPRESS, C.A. - KD73'){
    ?>
        <div class="card-deck">
            <div class="card border-danger mb-3" style="width: 14rem;">
            <div class="card-body text-left bg-danger">
                <h5 class="card-title">
                    <span class="card-text text-white">
                        <?php echo "".SedeKD73; ?>
                    </span>
                </h5>
            </div>
            <div class="card-footer bg-transparent border-danger text-right">
                <a href="http://cpharmakd73.com/" role="button" class="btn btn-outline-danger btn-sm" target="_blank"></i>Ver reportes</a>
            </div>
        </div>
    </div>
    <?php
    }
    ?>
<!-- CASO FLL -->
<!-------------------------------------------------------------------------------->
<!-- CASO FAU -->
<?php
}
    if($SedeConnection == 'FAU'){
?>
    <?php
        if(Auth::user()->sede == 'FARMACIA TIERRA NEGRA, C.A.'){
    ?>
    <div class="card-deck">
        <div class="card border-danger mb-3" style="width: 14rem;">
        <div class="card-body text-left bg-danger">
            <h5 class="card-title">
                <span class="card-text text-white">
                    <?php echo "".SedeFTN; ?>
                </span>
            </h5>
        </div>
        <div class="card-footer bg-transparent border-danger text-right">
            <a href="http://cpharmaftn.com/" role="button" class="btn btn-outline-danger btn-sm" target="_blank"></i>Ver reportes</a>
        </div>
        </div>
    </div>
    <?php
    }
        if(Auth::user()->sede == 'FARMACIA LA LAGO,C.A.'){
    ?>
    <div class="card-deck">
        <div class="card border-success mb-3" style="width: 14rem;">
        <div class="card-body text-left bg-success">
            <h5 class="card-title">
                <span class="card-text text-white">
                    <?php echo "".SedeFLL; ?>
                </span>
            </h5>
        </div>
        <div class="card-footer bg-transparent border-success text-right">
              <a href="http://cpharmafll.com/" role="button" class="btn btn-outline-success btn-sm" target="_blank"></i>Ver reportes</a>
        </div>
        </div>
    </div>
    <?php
    }
        if(Auth::user()->sede == 'FARMACIA AVENIDA UNIVERSIDAD, C.A.'){
    ?>
    <div class="card-deck">
        <div class="card border-info mb-3" style="width: 14rem;">
        <div class="card-body text-left bg-info">
            <h5 class="card-title">
                <span class="card-text text-white">
                    <?php echo "".SedeFAU; ?>
                </span>
            </h5>
        </div>
        <div class="card-footer bg-transparent border-info text-right">
                <form action="/reporte/" style="display: inline;">
                @csrf
                <input id="SEDE" name="SEDE" type="hidden" value="FAU">
                <button type="submit" name="Reporte" role="button" class="btn btn-outline-info btn-sm"></i>Ver reportes</button>
                </form>
        </div>
    </div>
  </div>
  <?php
    }
        if(Auth::user()->sede == 'GRUPO P, C.A'){
    ?>
        <div class="card-deck">
            <div class="card border-danger mb-3" style="width: 14rem;">
                <div class="card-body text-left bg-danger">
                <h5 class="card-title">
                    <span class="card-text text-white">
                        <?php echo "".SedeFTN; ?>
                    </span>
                </h5>
                </div>
            <div class="card-footer bg-transparent border-danger text-right">
                <a href="http://cpharmaftn.com/" role="button" class="btn btn-outline-danger btn-sm" target="_blank"></i>Ver reportes</a>
            </div>
            </div>
            <div class="card border-success mb-3" style="width: 14rem;">
            <div class="card-body text-left bg-success">
                <h5 class="card-title">
                    <span class="card-text text-white">
                        <?php echo "".SedeFLL; ?>
                    </span>
                </h5>
            </div>
            <div class="card-footer bg-transparent border-success text-right">
                  <a href="http://cpharmafll.com/" role="button" class="btn btn-outline-success btn-sm" target="_blank"></i>Ver reportes</a>
            </div>
            </div>
            <div class="card border-info mb-3" style="width: 14rem;">
            <div class="card-body text-left bg-info">
                <h5 class="card-title">
                    <span class="card-text text-white">
                        <?php echo "".SedeFAU; ?>
                    </span>
                </h5>
            </div>
            <div class="card-footer bg-transparent border-info text-right">
                    <form action="/reporte/" style="display: inline;">
                    @csrf
                    <input id="SEDE" name="SEDE" type="hidden" value="FAU">
                    <button type="submit" name="Reporte" role="button" class="btn btn-outline-info btn-sm"></i>Ver reportes</button>
                    </form>
            </div>
        </div>
      </div>
      <div class="card-deck">
            <div class="card border-warning mb-3" style="width: 14rem;">
                <div class="card-body text-left bg-warning">
                <h5 class="card-title">
                    <span class="card-text text-white">
                        <?php echo "".SedeKDI; ?>
                    </span>
                </h5>
                </div>
            <div class="card-footer bg-transparent border-warning text-right">
                <a href="http://cpharmakdi.com/" role="button" class="btn btn-outline-warning btn-sm" target="_blank"></i>Ver reportes</a>
            </div>
            </div>
            <div class="card border-secondary mb-3" style="width: 14rem;">
            <div class="card-body text-left bg-secondary">
                <h5 class="card-title">
                    <span class="card-text text-white">
                        <?php echo "".SedeFSM; ?>
                    </span>
                </h5>
            </div>
            <div class="card-footer bg-transparent border-secondary text-right">
                  <a href="http://cpharmafsm.com/" role="button" class="btn btn-outline-secondary btn-sm" target="_blank"></i>Ver reportes</a>
            </div>
            </div>
            <div class="card border-dark mb-3" style="width: 14rem;">
                <div class="card-body text-left bg-dark">
                    <h5 class="card-title">
                        <span class="card-text text-white">
                            <?php echo "".SedeFEC; ?>
                        </span>
                    </h5>
                </div>
                <div class="card-footer bg-transparent border-dark text-right">
                      <a href="http://cpharmafec.com/" role="button" class="btn btn-outline-dark btn-sm" target="_blank"></i>Ver reportes</a>
                </div>
            </div>
            <div class="card border-danger mb-3" style="width: 14rem;">
                <div class="card-body text-left bg-danger">
                    <h5 class="card-title">
                        <span class="card-text text-white">
                            <?php echo "".SedeKD73; ?>
                        </span>
                    </h5>
                </div>
                <div class="card-footer bg-transparent border-danger text-right">
                      <a href="http://cpharmakd73.com/" role="button" class="btn btn-outline-danger btn-sm" target="_blank"></i>Ver reportes</a>
                </div>
            </div>
        </div>
      <?php
    }
        if(Auth::user()->sede == 'FARMACIAS KD EXPRESS, C.A.'){
    ?>
        <div class="card-deck">
            <div class="card border-warning mb-3" style="width: 14rem;">
            <div class="card-body text-left bg-warning">
                <h5 class="card-title">
                    <span class="card-text text-white">
                        <?php echo "".SedeKDI; ?>
                    </span>
                </h5>
            </div>
            <div class="card-footer bg-transparent border-warning text-right">
                <a href="http://cpharmakdi.com/" role="button" class="btn btn-outline-warning btn-sm" target="_blank"></i>Ver reportes</a>
            </div>
        </div>
    </div>
    <?php
    }
        if(Auth::user()->sede == 'FARMACIA MILLENNIUM 2000, C.A'){
    ?>
        <div class="card-deck">
            <div class="card border-secondary mb-3" style="width: 14rem;">
            <div class="card-body text-left bg-secondary">
                <h5 class="card-title">
                    <span class="card-text text-white">
                        <?php echo "".SedeFSM; ?>
                    </span>
                </h5>
            </div>
            <div class="card-footer bg-transparent border-secondary text-right">
                <a href="http://cpharmafsm.com/" role="button" class="btn btn-outline-secondary btn-sm" target="_blank"></i>Ver reportes</a>
            </div>
        </div>
    </div>
    <?php
    }
        if(Auth::user()->sede == 'FARMACIA EL CALLEJON, C.A.'){
    ?>
        <div class="card-deck">
            <div class="card border-dark mb-3" style="width: 14rem;">
            <div class="card-body text-left bg-dark">
                <h5 class="card-title">
                    <span class="card-text text-white">
                        <?php echo "".SedeFEC; ?>
                    </span>
                </h5>
            </div>
            <div class="card-footer bg-transparent border-dark text-right">
                <a href="http://cpharmafec.com/" role="button" class="btn btn-outline-dark btn-sm" target="_blank"></i>Ver reportes</a>
            </div>
        </div>
    </div>
    <?php
    }
        if(Auth::user()->sede == 'FARMACIAS KD EXPRESS, C.A. - KD73'){
    ?>
        <div class="card-deck">
            <div class="card border-danger mb-3" style="width: 14rem;">
            <div class="card-body text-left bg-danger">
                <h5 class="card-title">
                    <span class="card-text text-white">
                        <?php echo "".SedeKD73; ?>
                    </span>
                </h5>
            </div>
            <div class="card-footer bg-transparent border-danger text-right">
                <a href="http://cpharmakd73.com/" role="button" class="btn btn-outline-danger btn-sm" target="_blank"></i>Ver reportes</a>
            </div>
        </div>
    </div>
    <?php
    }
    ?>
<!-- CASO FAU -->
<!-------------------------------------------------------------------------------->
<!-- CASO KDI-->
<?php
}
    if($SedeConnection == 'KDI'){
?>
    <?php
        if(Auth::user()->sede == 'FARMACIAS KD EXPRESS, C.A.' || Auth::user()->sede == 'GRUPO P, C.A'){
    ?>
    <div class="card-deck">
        <div class="card border-warning mb-3" style="width: 14rem;">
        <div class="card-body text-left bg-warning">
            <h5 class="card-title">
                <span class="card-text text-white">
                    <?php echo "".SedeKDI; ?>
                </span>
            </h5>
        </div>
        <div class="card-footer bg-transparent border-warning text-right">
                <form action="/reporte/" style="display: inline;">
                @csrf
                <input id="SEDE" name="SEDE" type="hidden" value="KDI">
                <button type="submit" name="Reporte" role="button" class="btn btn-outline-warning btn-sm"></i>Ver reportes</button>
                </form>
        </div>
        </div>
    </div>
    <?php
    }
?>
<!-- CASO KDI-->
<!-------------------------------------------------------------------------------->
<!-- CASO FLF-->
<?php
}
    if($SedeConnection == 'FLF'){
?>
    <?php
        if(Auth::user()->sede == 'FARMACIA TIERRA NEGRA, C.A.'){
    ?>
    <div class="card-deck">
        <div class="card border-danger mb-3" style="width: 14rem;">
        <div class="card-body text-left bg-danger">
            <h5 class="card-title">
                <span class="card-text text-white">
                    <?php echo "".SedeFTN; ?>
                </span>
            </h5>
        </div>
        <div class="card-footer bg-transparent border-danger text-right">
            <a href="http://cpharmaftn.com/" role="button" class="btn btn-outline-danger btn-sm" target="_blank"></i>Ver reportes</a>
        </div>
        </div>
    </div>
    <?php
    }
        if(Auth::user()->sede == 'FARMACIA LA LAGO,C.A.'){
    ?>
    <div class="card-deck">
        <div class="card border-success mb-3" style="width: 14rem;">
        <div class="card-body text-left bg-success">
            <h5 class="card-title">
                <span class="card-text text-white">
                    <?php echo "".SedeFLL; ?>
                </span>
            </h5>
        </div>
        <div class="card-footer bg-transparent border-success text-right">
              <a href="http://cpharmafll.com/" role="button" class="btn btn-outline-success btn-sm" target="_blank"></i>Ver reportes</a>
        </div>
        </div>
    </div>
    <?php
    }
        if(Auth::user()->sede == 'FARMACIA AVENIDA UNIVERSIDAD, C.A.'){
    ?>
    <div class="card-deck">
        <div class="card border-info mb-3" style="width: 14rem;">
        <div class="card-body text-left bg-info">
            <h5 class="card-title">
                <span class="card-text text-white">
                    <?php echo "".SedeFAU; ?>
                </span>
            </h5>
        </div>
        <div class="card-footer bg-transparent border-info text-right">
            <a href="http://cpharmafau.com/" role="button" class="btn btn-outline-info btn-sm" target="_blank"></i>Ver reportes</a>
        </div>
    </div>
  </div>
  <?php
    }
        if(Auth::user()->sede == 'GRUPO P, C.A'){
    ?>
        <div class="card-deck">
            <div class="card border-danger mb-3" style="width: 14rem;">
                <div class="card-body text-left bg-danger">
                <h5 class="card-title">
                    <span class="card-text text-white">
                        <?php echo "".SedeFTN; ?>
                    </span>
                </h5>
                </div>
            <div class="card-footer bg-transparent border-danger text-right">
                <a href="http://cpharmaftn.com/" role="button" class="btn btn-outline-danger btn-sm" target="_blank"></i>Ver reportes</a>
            </div>
            </div>
            <div class="card border-success mb-3" style="width: 14rem;">
            <div class="card-body text-left bg-success">
                <h5 class="card-title">
                    <span class="card-text text-white">
                        <?php echo "".SedeFLL; ?>
                    </span>
                </h5>
            </div>
            <div class="card-footer bg-transparent border-success text-right">
                <a href="http://cpharmafll.com/" role="button" class="btn btn-outline-success btn-sm" target="_blank"></i>Ver reportes</a>
            </div>
            </div>
            <div class="card border-info mb-3" style="width: 14rem;">
            <div class="card-body text-left bg-info">
                <h5 class="card-title">
                    <span class="card-text text-white">
                        <?php echo "".SedeFAU; ?>
                    </span>
                </h5>
            </div>
            <div class="card-footer bg-transparent border-info text-right">
                <a href="http://cpharmafau.com/" role="button" class="btn btn-outline-info btn-sm" target="_blank"></i>Ver reportes</a>
            </div>
        </div>
      </div>
      <div class="card-deck">
            <div class="card border-warning mb-3" style="width: 14rem;">
                <div class="card-body text-left bg-warning">
                <h5 class="card-title">
                    <span class="card-text text-white">
                        <?php echo "".SedeKDI; ?>
                    </span>
                </h5>
                </div>
            <div class="card-footer bg-transparent border-warning text-right">
                <a href="http://cpharmakdi.com/" role="button" class="btn btn-outline-warning btn-sm" target="_blank"></i>Ver reportes</a>
            </div>
            </div>
            <div class="card border-secondary mb-3" style="width: 14rem;">
            <div class="card-body text-left bg-secondary">
                <h5 class="card-title">
                    <span class="card-text text-white">
                        <?php echo "".SedeFSM; ?>
                    </span>
                </h5>
            </div>
            <div class="card-footer bg-transparent border-secondary text-right">
                <a href="http://cpharmafsm.com/" role="button" class="btn btn-outline-secondary btn-sm" target="_blank"></i>Ver reportes</a>
            </div>
            </div>
            <div class="card border-dark mb-3" style="width: 14rem;">
                <div class="card-body text-left bg-dark">
                    <h5 class="card-title">
                        <span class="card-text text-white">
                            <?php echo "".SedeFEC; ?>
                        </span>
                    </h5>
                </div>
                <div class="card-footer bg-transparent border-dark text-right">
                    <form action="/reporte/" style="display: inline;">
                    @csrf
                    <input id="SEDE" name="SEDE" type="hidden" value="FEC">
                    <button type="submit" name="Reporte" role="button" class="btn btn-outline-dark btn-sm"></i>Ver reportes</button>
                    </form>
                </div>
            </div>
            <div class="card border-danger mb-3" style="width: 14rem;">
                <div class="card-body text-left bg-danger">
                    <h5 class="card-title">
                        <span class="card-text text-white">
                            <?php echo "".SedeKD73; ?>
                        </span>
                    </h5>
                </div>
                <div class="card-footer bg-transparent border-danger text-right">
                      <a href="http://cpharmakd73.com/" role="button" class="btn btn-outline-danger btn-sm" target="_blank"></i>Ver reportes</a>
                </div>
            </div>
        </div>
      <?php
    }
        if(Auth::user()->sede == 'FARMACIAS KD EXPRESS, C.A.'){
    ?>
        <div class="card-deck">
            <div class="card border-warning mb-3" style="width: 14rem;">
            <div class="card-body text-left bg-warning">
                <h5 class="card-title">
                    <span class="card-text text-white">
                        <?php echo "".SedeKDI; ?>
                    </span>
                </h5>
            </div>
            <div class="card-footer bg-transparent border-warning text-right">
                <a href="http://cpharmakdi.com/" role="button" class="btn btn-outline-warning btn-sm" target="_blank"></i>Ver reportes</a>
            </div>
        </div>
    </div>
    <?php
    }
        if(Auth::user()->sede == 'FARMACIA MILLENNIUM 2000, C.A'){
    ?>
        <div class="card-deck">
            <div class="card border-secondary mb-3" style="width: 14rem;">
            <div class="card-body text-left bg-secondary">
                <h5 class="card-title">
                    <span class="card-text text-white">
                        <?php echo "".SedeFSM; ?>
                    </span>
                </h5>
            </div>
            <div class="card-footer bg-transparent border-success text-right">
                <a href="http://cpharmafsm.com/" role="button" class="btn btn-outline-success btn-sm" target="_blank"></i>Ver reportes</a>
          </div>
        </div>
    </div>
    <?php
    }
        if(Auth::user()->sede == 'FARMACIA EL CALLEJON, C.A.'){
    ?>
        <div class="card-deck">
            <div class="card border-secondary mb-3" style="width: 14rem;">
            <div class="card-body text-left bg-secondary">
                <h5 class="card-title">
                    <span class="card-text text-white">
                        <?php echo "".SedeFEC; ?>
                    </span>
                </h5>
            </div>
            <div class="card-footer bg-transparent border-success text-right">
                <a href="http://cpharmafec.com/" role="button" class="btn btn-outline-success btn-sm" target="_blank"></i>Ver reportes</a>
          </div>
        </div>
    </div>
    <?php
    }
        if(Auth::user()->sede == 'FARMACIA LA FUSTA'){
    ?>
        <div class="card-deck">
            <div class="card border-dark mb-3" style="width: 14rem;">
            <div class="card-body text-left bg-dark">
                <h5 class="card-title">
                    <span class="card-text text-white">
                        <?php echo "".SedeFLF; ?>
                    </span>
                </h5>
            </div>
            <div class="card-footer bg-transparent border-dark text-right">
                <form action="/reporte/" style="display: inline;">
                @csrf
                <input id="SEDE" name="SEDE" type="hidden" value="FLF">
                <button type="submit" name="Reporte" role="button" class="btn btn-outline-dark btn-sm"></i>Ver reportes</button>
                </form>
            </div>
        </div>
    </div>
    <?php
    }
        if(Auth::user()->sede == 'FARMACIAS KD EXPRESS, C.A. - KD73'){
    ?>
        <div class="card-deck">
            <div class="card border-danger mb-3" style="width: 14rem;">
            <div class="card-body text-left bg-danger">
                <h5 class="card-title">
                    <span class="card-text text-white">
                        <?php echo "".SedeKD73; ?>
                    </span>
                </h5>
            </div>
            <div class="card-footer bg-transparent border-danger text-right">
                <a href="http://cpharmakd73.com/" role="button" class="btn btn-outline-danger btn-sm" target="_blank"></i>Ver reportes</a>
            </div>
        </div>
    </div>
    <?php
    }
    ?>
<!-- CASO FLF -->
<!-------------------------------------------------------------------------------->
<!-- CASO FEC-->
<?php
}
    if($SedeConnection == 'FEC'){
?>
    <?php
        if(Auth::user()->sede == 'FARMACIA TIERRA NEGRA, C.A.'){
    ?>
    <div class="card-deck">
        <div class="card border-danger mb-3" style="width: 14rem;">
        <div class="card-body text-left bg-danger">
            <h5 class="card-title">
                <span class="card-text text-white">
                    <?php echo "".SedeFTN; ?>
                </span>
            </h5>
        </div>
        <div class="card-footer bg-transparent border-danger text-right">
            <a href="http://cpharmaftn.com/" role="button" class="btn btn-outline-danger btn-sm" target="_blank"></i>Ver reportes</a>
        </div>
        </div>
    </div>
    <?php
    }
        if(Auth::user()->sede == 'FARMACIA LA LAGO,C.A.'){
    ?>
    <div class="card-deck">
        <div class="card border-success mb-3" style="width: 14rem;">
        <div class="card-body text-left bg-success">
            <h5 class="card-title">
                <span class="card-text text-white">
                    <?php echo "".SedeFLL; ?>
                </span>
            </h5>
        </div>
        <div class="card-footer bg-transparent border-success text-right">
              <a href="http://cpharmafll.com/" role="button" class="btn btn-outline-success btn-sm" target="_blank"></i>Ver reportes</a>
        </div>
        </div>
    </div>
    <?php
    }
        if(Auth::user()->sede == 'FARMACIA AVENIDA UNIVERSIDAD, C.A.'){
    ?>
    <div class="card-deck">
        <div class="card border-info mb-3" style="width: 14rem;">
        <div class="card-body text-left bg-info">
            <h5 class="card-title">
                <span class="card-text text-white">
                    <?php echo "".SedeFAU; ?>
                </span>
            </h5>
        </div>
        <div class="card-footer bg-transparent border-info text-right">
            <a href="http://cpharmafau.com/" role="button" class="btn btn-outline-info btn-sm" target="_blank"></i>Ver reportes</a>
        </div>
    </div>
  </div>
  <?php
    }
        if(Auth::user()->sede == 'GRUPO P, C.A'){
    ?>
        <div class="card-deck">
            <div class="card border-danger mb-3" style="width: 14rem;">
                <div class="card-body text-left bg-danger">
                <h5 class="card-title">
                    <span class="card-text text-white">
                        <?php echo "".SedeFTN; ?>
                    </span>
                </h5>
                </div>
            <div class="card-footer bg-transparent border-danger text-right">
                <a href="http://cpharmaftn.com/" role="button" class="btn btn-outline-danger btn-sm" target="_blank"></i>Ver reportes</a>
            </div>
            </div>
            <div class="card border-success mb-3" style="width: 14rem;">
            <div class="card-body text-left bg-success">
                <h5 class="card-title">
                    <span class="card-text text-white">
                        <?php echo "".SedeFLL; ?>
                    </span>
                </h5>
            </div>
            <div class="card-footer bg-transparent border-success text-right">
                <a href="http://cpharmafll.com/" role="button" class="btn btn-outline-success btn-sm" target="_blank"></i>Ver reportes</a>
            </div>
            </div>
            <div class="card border-info mb-3" style="width: 14rem;">
            <div class="card-body text-left bg-info">
                <h5 class="card-title">
                    <span class="card-text text-white">
                        <?php echo "".SedeFAU; ?>
                    </span>
                </h5>
            </div>
            <div class="card-footer bg-transparent border-info text-right">
                <a href="http://cpharmafau.com/" role="button" class="btn btn-outline-info btn-sm" target="_blank"></i>Ver reportes</a>
            </div>
        </div>
      </div>
      <div class="card-deck">
            <div class="card border-warning mb-3" style="width: 14rem;">
                <div class="card-body text-left bg-warning">
                <h5 class="card-title">
                    <span class="card-text text-white">
                        <?php echo "".SedeKDI; ?>
                    </span>
                </h5>
                </div>
            <div class="card-footer bg-transparent border-warning text-right">
                <a href="http://cpharmakdi.com/" role="button" class="btn btn-outline-warning btn-sm" target="_blank"></i>Ver reportes</a>
            </div>
            </div>
            <div class="card border-secondary mb-3" style="width: 14rem;">
            <div class="card-body text-left bg-secondary">
                <h5 class="card-title">
                    <span class="card-text text-white">
                        <?php echo "".SedeFSM; ?>
                    </span>
                </h5>
            </div>
            <div class="card-footer bg-transparent border-secondary text-right">
                <a href="http://cpharmafsm.com/" role="button" class="btn btn-outline-secondary btn-sm" target="_blank"></i>Ver reportes</a>
            </div>
            </div>
            <div class="card border-dark mb-3" style="width: 14rem;">
                <div class="card-body text-left bg-dark">
                    <h5 class="card-title">
                        <span class="card-text text-white">
                            <?php echo "".SedeFEC; ?>
                        </span>
                    </h5>
                </div>
                <div class="card-footer bg-transparent border-dark text-right">
                    <form action="/reporte/" style="display: inline;">
                    @csrf
                    <input id="SEDE" name="SEDE" type="hidden" value="FEC">
                    <button type="submit" name="Reporte" role="button" class="btn btn-outline-dark btn-sm"></i>Ver reportes</button>
                    </form>
                </div>
            </div>
            <div class="card border-danger mb-3" style="width: 14rem;">
                <div class="card-body text-left bg-danger">
                    <h5 class="card-title">
                        <span class="card-text text-white">
                            <?php echo "".SedeKD73; ?>
                        </span>
                    </h5>
                </div>
                <div class="card-footer bg-transparent border-danger text-right">
                      <a href="http://cpharmakd73.com/" role="button" class="btn btn-outline-danger btn-sm" target="_blank"></i>Ver reportes</a>
                </div>
            </div>
        </div>
      <?php
    }
        if(Auth::user()->sede == 'FARMACIAS KD EXPRESS, C.A.'){
    ?>
        <div class="card-deck">
            <div class="card border-warning mb-3" style="width: 14rem;">
            <div class="card-body text-left bg-warning">
                <h5 class="card-title">
                    <span class="card-text text-white">
                        <?php echo "".SedeKDI; ?>
                    </span>
                </h5>
            </div>
            <div class="card-footer bg-transparent border-warning text-right">
                <a href="http://cpharmakdi.com/" role="button" class="btn btn-outline-warning btn-sm" target="_blank"></i>Ver reportes</a>
            </div>
        </div>
    </div>
    <?php
    }
        if(Auth::user()->sede == 'FARMACIA MILLENNIUM 2000, C.A'){
    ?>
        <div class="card-deck">
            <div class="card border-secondary mb-3" style="width: 14rem;">
            <div class="card-body text-left bg-secondary">
                <h5 class="card-title">
                    <span class="card-text text-white">
                        <?php echo "".SedeFSM; ?>
                    </span>
                </h5>
            </div>
            <div class="card-footer bg-transparent border-success text-right">
                <a href="http://cpharmafsm.com/" role="button" class="btn btn-outline-success btn-sm" target="_blank"></i>Ver reportes</a>
          </div>
        </div>
    </div>
    <?php
    }
        if(Auth::user()->sede == 'FARMACIA EL CALLEJON, C.A.'){
    ?>
        <div class="card-deck">
            <div class="card border-dark mb-3" style="width: 14rem;">
            <div class="card-body text-left bg-dark">
                <h5 class="card-title">
                    <span class="card-text text-white">
                        <?php echo "".SedeFEC; ?>
                    </span>
                </h5>
            </div>
            <div class="card-footer bg-transparent border-dark text-right">
                <form action="/reporte/" style="display: inline;">
                @csrf
                <input id="SEDE" name="SEDE" type="hidden" value="FEC">
                <button type="submit" name="Reporte" role="button" class="btn btn-outline-dark btn-sm"></i>Ver reportes</button>
                </form>
            </div>
        </div>
    </div>
    <?php
    }
        if(Auth::user()->sede == 'FARMACIAS KD EXPRESS, C.A. - KD73'){
    ?>
        <div class="card-deck">
            <div class="card border-danger mb-3" style="width: 14rem;">
            <div class="card-body text-left bg-danger">
                <h5 class="card-title">
                    <span class="card-text text-white">
                        <?php echo "".SedeKD73; ?>
                    </span>
                </h5>
            </div>
            <div class="card-footer bg-transparent border-danger text-right">
                <a href="http://cpharmakd73.com/" role="button" class="btn btn-outline-danger btn-sm" target="_blank"></i>Ver reportes</a>
            </div>
        </div>
    </div>
    <?php
    }
    ?>
<!-- CASO FEC -->
<!-------------------------------------------------------------------------------->
<!-- CASO KD73-->
<?php
}
    if($SedeConnection == 'KD73'){
?>
    <?php
        if(Auth::user()->sede == 'FARMACIAS KD EXPRESS, C.A. - KD73' || Auth::user()->sede == 'GRUPO P, C.A'){
    ?>
    <div class="card-deck">
        <div class="card border-danger mb-3" style="width: 14rem;">
        <div class="card-body text-left bg-danger">
            <h5 class="card-title">
                <span class="card-text text-white">
                    <?php echo "".SedeKD73; ?>
                </span>
            </h5>
        </div>
        <div class="card-footer bg-transparent border-danger text-right">
                <form action="/reporte/" style="display: inline;">
                @csrf
                <input id="SEDE" name="SEDE" type="hidden" value="KD73">
                <button type="submit" name="Reporte" role="button" class="btn btn-outline-danger btn-sm"></i>Ver reportes</button>
                </form>
        </div>
        </div>
    </div>
    <?php
    }
?>
<!-- CASO KD73-->
<!-------------------------------------------------------------------------------->
<!-------------------------------------------------------------------------------->
<!-- CASO FSM -->
<?php
}
    if($SedeConnection == 'FSM'){
?>
    <?php
        if(Auth::user()->sede == 'FARMACIA TIERRA NEGRA, C.A.'){
    ?>
    <div class="card-deck">
        <div class="card border-danger mb-3" style="width: 14rem;">
        <div class="card-body text-left bg-danger">
            <h5 class="card-title">
                <span class="card-text text-white">
                    <?php echo "".SedeFTN; ?>
                </span>
            </h5>
        </div>
        <div class="card-footer bg-transparent border-danger text-right">
            <a href="http://cpharmaftn.com/" role="button" class="btn btn-outline-danger btn-sm" target="_blank"></i>Ver reportes</a>
        </div>
        </div>
    </div>
    <?php
    }
        if(Auth::user()->sede == 'FARMACIA LA LAGO,C.A.'){
    ?>
    <div class="card-deck">
        <div class="card border-success mb-3" style="width: 14rem;">
        <div class="card-body text-left bg-success">
            <h5 class="card-title">
                <span class="card-text text-white">
                    <?php echo "".SedeFLL; ?>
                </span>
            </h5>
        </div>
        <div class="card-footer bg-transparent border-success text-right">
              <a href="http://cpharmafll.com/" role="button" class="btn btn-outline-success btn-sm" target="_blank"></i>Ver reportes</a>
        </div>
        </div>
    </div>
    <?php
    }
        if(Auth::user()->sede == 'FARMACIA AVENIDA UNIVERSIDAD, C.A.'){
    ?>
    <div class="card-deck">
        <div class="card border-info mb-3" style="width: 14rem;">
        <div class="card-body text-left bg-info">
            <h5 class="card-title">
                <span class="card-text text-white">
                    <?php echo "".SedeFAU; ?>
                </span>
            </h5>
        </div>
        <div class="card-footer bg-transparent border-info text-right">
            <a href="http://cpharmafau.com/" role="button" class="btn btn-outline-info btn-sm" target="_blank"></i>Ver reportes</a>
        </div>
    </div>
  </div>
  <?php
    }
        if(Auth::user()->sede == 'GRUPO P, C.A'){
    ?>
        <div class="card-deck">
            <div class="card border-danger mb-3" style="width: 14rem;">
                <div class="card-body text-left bg-danger">
                <h5 class="card-title">
                    <span class="card-text text-white">
                        <?php echo "".SedeFTN; ?>
                    </span>
                </h5>
                </div>
            <div class="card-footer bg-transparent border-danger text-right">
                <a href="http://cpharmaftn.com/" role="button" class="btn btn-outline-danger btn-sm" target="_blank"></i>Ver reportes</a>
            </div>
            </div>
            <div class="card border-success mb-3" style="width: 14rem;">
            <div class="card-body text-left bg-success">
                <h5 class="card-title">
                    <span class="card-text text-white">
                        <?php echo "".SedeFLL; ?>
                    </span>
                </h5>
            </div>
            <div class="card-footer bg-transparent border-success text-right">
                <a href="http://cpharmafll.com/" role="button" class="btn btn-outline-success btn-sm" target="_blank"></i>Ver reportes</a>
            </div>
            </div>
            <div class="card border-info mb-3" style="width: 14rem;">
            <div class="card-body text-left bg-info">
                <h5 class="card-title">
                    <span class="card-text text-white">
                        <?php echo "".SedeFAU; ?>
                    </span>
                </h5>
            </div>
            <div class="card-footer bg-transparent border-info text-right">
                <a href="http://cpharmafau.com/" role="button" class="btn btn-outline-info btn-sm" target="_blank"></i>Ver reportes</a>
            </div>
        </div>
      </div>
      <div class="card-deck">
            <div class="card border-warning mb-3" style="width: 14rem;">
                <div class="card-body text-left bg-warning">
                <h5 class="card-title">
                    <span class="card-text text-white">
                        <?php echo "".SedeKDI; ?>
                    </span>
                </h5>
                </div>
            <div class="card-footer bg-transparent border-warning text-right">
                <a href="http://cpharmakdi.com/" role="button" class="btn btn-outline-warning btn-sm" target="_blank"></i>Ver reportes</a>
            </div>
            </div>
            <div class="card border-secondary mb-3" style="width: 14rem;">
            <div class="card-body text-left bg-secondary">
                <h5 class="card-title">
                    <span class="card-text text-white">
                        <?php echo "".SedeFSM; ?>
                    </span>
                </h5>
            </div>
            <div class="card-footer bg-transparent border-secondary text-right">
                <form action="/reporte/" style="display: inline;">
                @csrf
                <input id="SEDE" name="SEDE" type="hidden" value="FSM">
                <button type="submit" name="Reporte" role="button" class="btn btn-outline-secondary btn-sm"></i>Ver reportes</button>
                </form>
            </div>
            </div>
            <div class="card border-dark mb-3" style="width: 14rem;">
                <div class="card-body text-left bg-dark">
                    <h5 class="card-title">
                        <span class="card-text text-white">
                            <?php echo "".SedeFEC; ?>
                        </span>
                    </h5>
                </div>
                <div class="card-footer bg-transparent border-dark text-right">
                      <a href="http://cpharmafec.com/" role="button" class="btn btn-outline-dark btn-sm" target="_blank"></i>Ver reportes</a>
                </div>
            </div>
            <div class="card border-danger mb-3" style="width: 14rem;">
                <div class="card-body text-left bg-danger">
                    <h5 class="card-title">
                        <span class="card-text text-white">
                            <?php echo "".SedeKD73; ?>
                        </span>
                    </h5>
                </div>
                <div class="card-footer bg-transparent border-danger text-right">
                      <a href="http://cpharmakd73.com/" role="button" class="btn btn-outline-danger btn-sm" target="_blank"></i>Ver reportes</a>
                </div>
            </div>
        </div>
      <?php
    }
        if(Auth::user()->sede == 'FARMACIAS KD EXPRESS, C.A.'){
    ?>
        <div class="card-deck">
            <div class="card border-warning mb-3" style="width: 14rem;">
            <div class="card-body text-left bg-warning">
                <h5 class="card-title">
                    <span class="card-text text-white">
                        <?php echo "".SedeKDI; ?>
                    </span>
                </h5>
            </div>
            <div class="card-footer bg-transparent border-warning text-right">
                <a href="http://cpharmakdi.com/" role="button" class="btn btn-outline-warning btn-sm" target="_blank"></i>Ver reportes</a>
            </div>
        </div>
    </div>
    <?php
    }
        if(Auth::user()->sede == 'FARMACIA MILLENNIUM 2000, C.A'){
    ?>
        <div class="card-deck">
            <div class="card border-secondary mb-3" style="width: 14rem;">
            <div class="card-body text-left bg-secondary">
                <h5 class="card-title">
                    <span class="card-text text-white">
                        <?php echo "".SedeFSM; ?>
                    </span>
                </h5>
            </div>
            <div class="card-footer bg-transparent border-secondary text-right">
                <form action="/reporte/" style="display: inline;">
                @csrf
                <input id="SEDE" name="SEDE" type="hidden" value="FSM">
                <button type="submit" name="Reporte" role="button" class="btn btn-outline-secondary btn-sm"></i>Ver reportes</button>
                </form>
            </div>
        </div>
    </div>
    <?php
    }
        if(Auth::user()->sede == 'FARMACIA EL CALLEJON, C.A.'){
    ?>
        <div class="card-deck">
            <div class="card border-dark mb-3" style="width: 14rem;">
            <div class="card-body text-left bg-dark">
                <h5 class="card-title">
                    <span class="card-text text-white">
                        <?php echo "".SedeFEC; ?>
                    </span>
                </h5>
            </div>
            <div class="card-footer bg-transparent border-dark text-right">
                <a href="http://cpharmafec.com/" role="button" class="btn btn-outline-dark btn-sm" target="_blank"></i>Ver reportes</a>
            </div>
        </div>
    </div>
    <?php
    }
        if(Auth::user()->sede == 'FARMACIAS KD EXPRESS, C.A. - KD73'){
    ?>
        <div class="card-deck">
            <div class="card border-danger mb-3" style="width: 14rem;">
            <div class="card-body text-left bg-danger">
                <h5 class="card-title">
                    <span class="card-text text-white">
                        <?php echo "".SedeKD73; ?>
                    </span>
                </h5>
            </div>
            <div class="card-footer bg-transparent border-danger text-right">
                <a href="http://cpharmakd73.com/" role="button" class="btn btn-outline-danger btn-sm" target="_blank"></i>Ver reportes</a>
            </div>
        </div>
    </div>
    <?php
    }
    ?>
<!-- CASO FSM -->
<!-------------------------------------------------------------------------------->
<!-- CPHARMA ON LINE -->
<!-------------------------------------------------------------------------------->
<!-- CPHARMA OFF LINE -->
<!-------------------------------------------------------------------------------->
<?php
}
    if(Auth::user()->sede == 'GRUPO P, C.A') {
?>
    <?php
        if($SedeConnection == 'GP' || $SedeConnection == 'ARG' || $SedeConnection == 'DBs') {
    ?>
        <hr class="row align-items-start col-12">
        <h1 class="h5 text-info text-center">
            <i class="fa fa-sync"></i>
            OFF-LINE
        </h1>
        <hr class="row align-items-start col-12">
    <!-- CASO GP -->
        <div class="card-deck">
            <div class="card border-danger mb-3" style="width: 14rem;">
            <div class="card-body text-left bg-danger">
                <h5 class="card-title">
                    <span class="card-text text-white">
                        <?php echo "".SedeFTNOFF."<br/>".FG_LastRestoreDB(nameFTNOFF,$SedeConnection);?>
                    </span>
                </h5>
            </div>
            <div class="card-footer bg-transparent border-danger text-right">
                <form action="/reporte/" style="display: inline;">
                    @csrf
                    <input id="SEDE" name="SEDE" type="hidden" value="GPFTN">
                    <button type="submit" name="Reporte" role="button" class="btn btn-outline-danger btn-sm"></i>Ver reportes</button>
                    </form>
            </div>
            </div>
            <div class="card border-success mb-3" style="width: 14rem;">
            <div class="card-body text-left bg-success">
                <h5 class="card-title">
                    <span class="card-text text-white">
                        <?php echo "".SedeFLLOFF."<br/>".FG_LastRestoreDB(nameFLLOFF,$SedeConnection);?>
                    </span>
                </h5>
            </div>
            <div class="card-footer bg-transparent border-success text-right">
                <form action="/reporte/" style="display: inline;">
                    @csrf
                    <input id="SEDE" name="SEDE" type="hidden" value="GPFLL">
                    <button type="submit" name="Reporte" role="button" class="btn btn-outline-success btn-sm"></i>Ver reportes</button>
                    </form>
            </div>
            </div>
            <div class="card border-info mb-3" style="width: 14rem;">
            <div class="card-body text-left bg-info">
                <h5 class="card-title">
                    <span class="card-text text-white">
                        <?php echo "".SedeFAUOFF."<br/>".FG_LastRestoreDB(nameFAUOFF,$SedeConnection);?>
                    </span>
                </h5>
            </div>
            <div class="card-footer bg-transparent border-info text-right">
                <form action="/reporte/" style="display: inline;">
                    @csrf
                    <input id="SEDE" name="SEDE" type="hidden" value="GPFAU">
                    <button type="submit" name="Reporte" role="button" class="btn btn-outline-info btn-sm"></i>Ver reportes</button>
                    </form>
            </div>
            </div>
        </div>
        <div class="card-deck">
            <div class="card border-warning mb-3" style="width: 14rem;">
                <div class="card-body text-left bg-warning">
                    <h5 class="card-title">
                        <span class="card-text text-white">
                            <?php echo "".SedeKDIOFF."<br/>".FG_LastRestoreDB(nameKDIOFF,$SedeConnection);?>
                        </span>
                    </h5>
                </div>
                <div class="card-footer bg-transparent border-warning text-right">
                    <form action="/reporte/" style="display: inline;">
                        @csrf
                        <input id="SEDE" name="SEDE" type="hidden" value="GPKDI">
                        <button type="submit" name="Reporte" role="button" class="btn btn-outline-warning btn-sm"></i>Ver reportes</button>
                        </form>
                </div>
            </div>
            <div class="card border-secondary mb-3" style="width: 14rem;">
                <div class="card-body text-left bg-secondary">
                    <h5 class="card-title">
                        <span class="card-text text-white">
                            <?php echo "".SedeFSMOFF."<br/>".FG_LastRestoreDB(nameFSMOFF,$SedeConnection);?>
                        </span>
                    </h5>
                </div>
                <div class="card-footer bg-transparent border-secondary text-right">
                    <form action="/reporte/" style="display: inline;">
                        @csrf
                        <input id="SEDE" name="SEDE" type="hidden" value="GPFSM">
                        <button type="submit" name="Reporte" role="button" class="btn btn-outline-secondary btn-sm"></i>Ver reportes</button>
                        </form>
                </div>
            </div>
        </div>
    <!-- CASO GP -->
    <?php
        }
        if($SedeConnection == 'FTN') {
    ?>
        <hr class="row align-items-start col-12">
        <h1 class="h5 text-info text-center">
            <i class="fa fa-sync"></i>
            OFF-LINE
        </h1>
        <hr class="row align-items-start col-12">
    <!-- CASO FTN -->
        <div class="card-deck">
            <div class="card border-success mb-3" style="width: 14rem;">
            <div class="card-body text-left bg-success">
                <h5 class="card-title">
                    <span class="card-text text-white">
                        <?php echo "".SedeFLLOFF."<br/>".FG_LastRestoreDB(nameFLLOFF,$SedeConnection);?>
                    </span>
                </h5>
            </div>
            <div class="card-footer bg-transparent border-success text-right">
                <form action="/reporte/" style="display: inline;">
                    @csrf
                    <input id="SEDE" name="SEDE" type="hidden" value="FTNFLL">
                    <button type="submit" name="Reporte" role="button" class="btn btn-outline-success btn-sm"></i>Ver reportes</button>
                    </form>
            </div>
            </div>
            <div class="card border-info mb-3" style="width: 14rem;">
            <div class="card-body text-left bg-info">
                <h5 class="card-title">
                    <span class="card-text text-white">
                        <?php echo "".SedeFAUOFF."<br/>".FG_LastRestoreDB(nameFAUOFF,$SedeConnection);?>
                    </span>
                </h5>
            </div>
            <div class="card-footer bg-transparent border-info text-right">
                <form action="/reporte/" style="display: inline;">
                    @csrf
                    <input id="SEDE" name="SEDE" type="hidden" value="FTNFAU">
                    <button type="submit" name="Reporte" role="button" class="btn btn-outline-info btn-sm"></i>Ver reportes</button>
                    </form>
            </div>
            </div>
        </div>
        <!--
        <div class="card-deck">
            <div class="card border-secondary mb-3" style="width: 14rem;">
                <div class="card-body text-left bg-secondary">
                    <h5 class="card-title">
                        <span class="card-text text-white">
                            <?php //echo "".SedeKDIOFF."<br/>".FG_LastRestoreDB(nameKDIOFF,$SedeConnection);?>
                        </span>
                    </h5>
                </div>
                <div class="card-footer bg-transparent border-secondary text-right">
                    <form action="/reporte/" style="display: inline;">
                        @csrf
                        <input id="SEDE" name="SEDE" type="hidden" value="FTNKDI">
                        <button type="submit" name="Reporte" role="button" class="btn btn-outline-secondary btn-sm"></i>Ver reportes</button>
                        </form>
                </div>
            </div>
            <div class="card border-dark mb-3" style="width: 14rem;">
                <div class="card-body text-left bg-dark">
                    <h5 class="card-title">
                        <span class="card-text text-white">
                            <?php //echo "".SedeFSMOFF."<br/>".FG_LastRestoreDB(nameFSMOFF,$SedeConnection);?>
                        </span>
                    </h5>
                </div>
                <div class="card-footer bg-transparent border-dark text-right">
                    <form action="/reporte/" style="display: inline;">
                        @csrf
                        <input id="SEDE" name="SEDE" type="hidden" value="FTNFSM">
                        <button type="submit" name="Reporte" role="button" class="btn btn-outline-dark btn-sm"></i>Ver reportes</button>
                        </form>
                </div>
            </div>
        </div>
        -->
    <!-- CASO FTN -->
    <?php
        }
        if($SedeConnection == 'FAU') {
    ?>
        <hr class="row align-items-start col-12">
        <h1 class="h5 text-info text-center">
            <i class="fa fa-sync"></i>
            OFF-LINE
        </h1>
        <hr class="row align-items-start col-12">
    <!-- CASO FAU -->
        <div class="card-deck">
            <div class="card border-danger mb-3" style="width: 14rem;">
            <div class="card-body text-left bg-danger">
                <h5 class="card-title">
                    <span class="card-text text-white">
                        <?php echo "".SedeFTNOFF."<br/>".FG_LastRestoreDB(nameFTNOFF,$SedeConnection);?>
                    </span>
                </h5>
            </div>
            <div class="card-footer bg-transparent border-danger text-right">
                <form action="/reporte/" style="display: inline;">
                    @csrf
                    <input id="SEDE" name="SEDE" type="hidden" value="FAUFTN">
                    <button type="submit" name="Reporte" role="button" class="btn btn-outline-danger btn-sm"></i>Ver reportes</button>
                    </form>
            </div>
            </div>
            <div class="card border-success mb-3" style="width: 14rem;">
            <div class="card-body text-left bg-success">
                <h5 class="card-title">
                    <span class="card-text text-white">
                        <?php echo "".SedeFLLOFF."<br/>".FG_LastRestoreDB(nameFLLOFF,$SedeConnection);?>
                    </span>
                </h5>
            </div>
            <div class="card-footer bg-transparent border-success text-right">
                <form action="/reporte/" style="display: inline;">
                    @csrf
                    <input id="SEDE" name="SEDE" type="hidden" value="FAUFLL">
                    <button type="submit" name="Reporte" role="button" class="btn btn-outline-success btn-sm"></i>Ver reportes</button>
                    </form>
            </div>
            </div>
        </div>
        <!--
        <div class="card-deck">
            <div class="card border-secondary mb-3" style="width: 14rem;">
                <div class="card-body text-left bg-secondary">
                    <h5 class="card-title">
                        <span class="card-text text-white">
                            <?php //echo "".SedeKDIOFF."<br/>".FG_LastRestoreDB(nameKDIOFF,$SedeConnection);?>
                        </span>
                    </h5>
                </div>
                <div class="card-footer bg-transparent border-secondary text-right">
                    <form action="/reporte/" style="display: inline;">
                        @csrf
                        <input id="SEDE" name="SEDE" type="hidden" value="FAUKDI">
                        <button type="submit" name="Reporte" role="button" class="btn btn-outline-secondary btn-sm"></i>Ver reportes</button>
                        </form>
                </div>
            </div>
            <div class="card border-dark mb-3" style="width: 14rem;">
                <div class="card-body text-left bg-dark">
                    <h5 class="card-title">
                        <span class="card-text text-white">
                            <?php //echo "".SedeFSMOFF."<br/>".FG_LastRestoreDB(nameFSMOFF,$SedeConnection);?>
                        </span>
                    </h5>
                </div>
                <div class="card-footer bg-transparent border-dark text-right">
                    <form action="/reporte/" style="display: inline;">
                        @csrf
                        <input id="SEDE" name="SEDE" type="hidden" value="FAUFSM">
                        <button type="submit" name="Reporte" role="button" class="btn btn-outline-dark btn-sm"></i>Ver reportes</button>
                        </form>
                </div>
            </div>
        </div>
        -->
    <!-- CASO FAU -->
    <?php
        }
      if(Auth::user()->role == 'DEVELOPER'){
    ?>
    <!-- CASO USER DEVELOPER -->
    <hr class="row align-items-start col-12">
    <h1 class="h5 text-info text-center">
        <i class="fas fa-user-secret"></i>
        DEVELOPER
    </h1>
    <hr class="row align-items-start col-12">

    <div class="card-deck">
        <div class="card border-warning mb-3" style="width: 14rem;">
        <div class="card-body text-left bg-warning">
            <h5 class="card-title">
                <span class="card-text text-white">
                    <?php echo "".SedeGP; ?>
                </span>
            </h5>
        </div>
        <div class="card-footer bg-transparent border-warning text-right">
            <form action="/reporte/" style="display: inline;">
                @csrf
                <input id="SEDE" name="SEDE" type="hidden" value="GP">
                <button type="submit" name="Reporte" role="button" class="btn btn-outline-warning btn-sm"></i>Ver reportes</button>
                </form>
        </div>
        </div>

        <div class="card border-warning mb-3" style="width: 14rem;">
        <div class="card-body text-left bg-warning">
            <h5 class="card-title">
                <span class="card-text text-white">
                    <?php echo "".SedeDBs; ?>
                </span>
            </h5>
        </div>
        <div class="card-footer bg-transparent border-warning text-right">
            <form action="/reporte/" style="display: inline;">
                @csrf
                <input id="SEDE" name="SEDE" type="hidden" value="DBs">
                <button type="submit" name="Reporte" role="button" class="btn btn-outline-warning btn-sm"></i>Ver reportes</button>
                </form>
        </div>
        </div>

        <div class="card border-warning mb-3" style="width: 14rem;">
        <div class="card-body text-left bg-warning">
            <h5 class="card-title">
                <span class="card-text text-white">
                    <?php echo "".SedeDBsa; ?>
                </span>
            </h5>
        </div>
        <div class="card-footer bg-transparent border-warning text-right">
            <form action="/reporte/" style="display: inline;">
                @csrf
                <input id="SEDE" name="SEDE" type="hidden" value="ARG">
                <button type="submit" name="Reporte" role="button" class="btn btn-outline-warning btn-sm"></i>Ver reportes</button>
                </form>
        </div>
        </div>
    </div>
    <!-- CASO USER DEVELOPER -->
    <?php
    }
}
?>
<!-------------------------------------------------------------------------------->
<!-- CPHARMA OFF LINE -->
<!-------------------------------------------------------------------------------->
@endsection
