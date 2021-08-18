<?php 
  include('C:\xampp\htdocs\CPharma\app\functions\config.php');
  include('C:\xampp\htdocs\CPharma\app\functions\functions.php');
  include('C:\xampp\htdocs\CPharma\app\functions\querys_mysql.php');
  include('C:\xampp\htdocs\CPharma\app\functions\querys_sqlserver.php');

  $sede = $_POST['sede'];
  $fechaFin = date("Y-d-m");
  $fechaInicio = new DateTime();
  $fechaInicio->modify('-30day');
  $fechaInicio = $fechaInicio->format('Y-d-m');

  $resultado = array();
  $resultado = FG_Conteo_Sedes($sede,$fechaInicio,$fechaFin);
  echo json_encode($resultado);
?>
<?php
	function FG_Sede_Galac_Nomina($sede){
		switch ($sede) {
			case 'FTN':
				return '1';
			break;
			case 'MC':
				return '2';
			break;
			case 'FLL':
				return '3';
			break;
			case 'FAU':
				return '4';
			break;
		}
	}
/****************************************************************/
	function FG_Conteo_Sedes($sede,$fechaInicio,$fechaFin){
 		$arrayIteracion = array();
		$conn = FG_Conectar_GALAC('NOMINA');
		$compania = FG_Sede_Galac_Nomina($sede);

		$sql = SQL_Conteo_Ingresos($compania,$fechaInicio,$fechaFin);
    $result = sqlsrv_query($conn,$sql);
    $row = sqlsrv_fetch_array($result,SQLSRV_FETCH_ASSOC);
    $CuentaIngreso = $row['CuentaIngreso'];

    if($CuentaIngreso==NULL){
    	$CuentaIngreso=0;
    }
    $arrayIteracion["CuentaIngreso"]=$CuentaIngreso;

    $sql1 = SQL_Conteo_Egresos($compania,$fechaInicio,$fechaFin);
    $result1 = sqlsrv_query($conn,$sql1);
    $row1 = sqlsrv_fetch_array($result1,SQLSRV_FETCH_ASSOC);
    $CuentaEgreso = $row1['CuentaEgreso'];

    if($CuentaEgreso==NULL){
    	$CuentaEgreso=0;
    }
    $arrayIteracion["CuentaEgreso"]=$CuentaEgreso;

    $sql2 = SQL_Conteo_Activos($compania,$fechaInicio);
    $result2 = sqlsrv_query($conn,$sql2);
    $row2 = sqlsrv_fetch_array($result2,SQLSRV_FETCH_ASSOC);
    $CuentaActivo = $row2['CuentaActivo'];

    if($CuentaActivo==NULL){
    	$CuentaActivo=0;
    }
    $arrayIteracion["CuentaActivo"]=$CuentaActivo;

    $arrayIteracion["fechaInicio"]=$fechaInicio;
    $arrayIteracion["fechaFin"]=$fechaFin;

    return $arrayIteracion;
	}
/****************************************************************/
	function SQL_Conteo_Ingresos($sede,$fechaInicio,$fechaFin){
    $sql = " 
	  	SELECT 
			COUNT(*) AS CuentaIngreso
			FROM IGNom_Trabajador
			INNER JOIN IGNom_TipoDeNomina ON IGNom_TipoDeNomina.ConsecutivoNomina = IGNom_Trabajador.ConsecutivoNomina
			INNER JOIN IGNom_Compania ON IGNom_Compania.ConsecutivoCompania = IGNom_TipoDeNomina.ConsecutivoCompania
			WHERE 
			(
				(IGNom_Compania.ConsecutivoCompania = '$sede')
				AND ((IGNom_Trabajador.FechaIngreso > '$fechaInicio') AND (IGNom_Trabajador.FechaIngreso < '$fechaFin'))
			)
    ";
  return $sql;
  }
/****************************************************************/
	function SQL_Conteo_Egresos($sede,$fechaInicio,$fechaFin){
    $sql = " 
	  	SELECT 
			COUNT(*) AS CuentaEgreso
			FROM IGNom_Trabajador
			INNER JOIN IGNom_TipoDeNomina ON IGNom_TipoDeNomina.ConsecutivoNomina = IGNom_Trabajador.ConsecutivoNomina
			INNER JOIN IGNom_Compania ON IGNom_Compania.ConsecutivoCompania = IGNom_TipoDeNomina.ConsecutivoCompania
			WHERE 
			(
				(IGNom_Compania.ConsecutivoCompania = '$sede')
				AND ((IGNom_Trabajador.FechaDeRetiro > '$fechaInicio') AND (IGNom_Trabajador.FechaDeRetiro < '$fechaFin'))
			)
    ";
  return $sql;
  }
 /****************************************************************/
	function SQL_Conteo_Activos($sede,$fechaInicio){
    $sql = " 
	  	SELECT 
			COUNT(*) AS CuentaActivo
			FROM IGNom_Trabajador
			INNER JOIN IGNom_TipoDeNomina ON IGNom_TipoDeNomina.ConsecutivoNomina = IGNom_Trabajador.ConsecutivoNomina
			INNER JOIN IGNom_Compania ON IGNom_Compania.ConsecutivoCompania = IGNom_TipoDeNomina.ConsecutivoCompania
			WHERE 
			(
				(IGNom_Compania.ConsecutivoCompania = '$sede')
				AND (IGNom_Trabajador.FechaIngreso < '$fechaInicio')
				AND (IGNom_Trabajador.FechaDeRetiro = '1900-01-01 00:00:00')
			)
    ";
  return $sql;
  }
?>
