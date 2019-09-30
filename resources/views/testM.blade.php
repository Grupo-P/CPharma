@extends('layouts.model')

@section('title')
    Reporte
@endsection

@section('scriptsHead')
    <script type="text/javascript" src="{{ asset('assets/js/sortTable.js') }}">
    </script>
    <script type="text/javascript" src="{{ asset('assets/js/filter.js') }}">  
    </script>
    <script type="text/javascript" src="{{ asset('assets/js/functions.js') }}"> 
    </script>

    <style>
    	* {
	    	box-sizing: border-box;
	    }

        input {
			border: 1px solid transparent;
			background-color: #f1f1f1;
			border-radius: 5px;
			padding: 10px;
			font-size: 16px;
        }

        input[type=text] {
			background-color: #f1f1f1;
			width: 100%;
        }

        .barrido {
            text-decoration: none;
            transition: width 1s, height 1s, transform 1s;
        }

        .barrido:hover {
            text-decoration: none;
            transition: width 1s, height 1s, transform 1s;
            transform: translate(20px,0px);
        }
    </style>
@endsection

@section('content')
    <h1 class="h5 text-info">
        <i class="fas fa-file-invoice"></i>
        Productos para surtir
    </h1>
    <hr class="row align-items-start col-12">
    
    <?php
        include(app_path().'\functions\config.php');
        include(app_path().'\functions\querys.php');
        include(app_path().'\functions\funciones.php');

        //--------- Borrar esta linea ---------//
        $_GET['SEDE'] = 'FTN';
        //--------- Borrar esta linea ---------//

        if(isset($_GET['fechaInicio'])) {

            $InicioCarga = new DateTime("now");

            if(isset($_GET['SEDE'])) {
                
                echo '
                	<h1 class="h5 text-success" align="left">
                		<i class="fas fa-prescription"></i> '.NombreSede($_GET['SEDE'])
                	.'</h1>
                ';
            }

            echo '<hr class="row align-items-start col-12">';

            R9_Productos_Surtir($_GET['SEDE'], $_GET['fechaInicio'], $_GET['fechaFin']);
        	GuardarAuditoria('CONSULTAR', 'REPORTE', 'Productos para surtir');

            $FinCarga = new DateTime("now");
            $IntervalCarga = $InicioCarga->diff($FinCarga);
            echo'Tiempo de carga: '.$IntervalCarga->format("%Y-%M-%D %H:%I:%S");
        }
        else {
            if(isset($_GET['SEDE'])) {

                echo '
                	<h1 class="h5 text-success" align="left">
                		<i class="fas fa-prescription"></i> '.NombreSede($_GET['SEDE'])
                	.'</h1>
                ';
            }
            echo '<hr class="row align-items-start col-12">';

            echo '
                <form autocomplete="off" action="" target="_blank">
                    <table style="width:100%;">
                        <tr>
                            <td align="center">Fecha Inicio:</td>
                            <td>
                            	<input id="fechaInicio" type="date" name="fechaInicio" required style="width:100%;">
                            </td>
                            <td align="center">Fecha Fin:</td>
                            <td align="right">
                            	<input id="fechaFin" name="fechaFin" type="date" required style="width:100%;">
                            </td>
                            <td align="right">
                            	<input id="SEDE" name="SEDE" type="hidden" value="';
	                            	print_r($_GET['SEDE']);
	                            echo'">
                            	<input type="submit" value="Buscar" class="btn btn-outline-success">
                            </td>
                        </tr>
                    </table>
                </form>
            ';
        }
    ?>
@endsection