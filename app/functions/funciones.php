//CONEXION CON XAMPP
function conectarXampp(){
	$conexion = mysqli_connect(serverCP,userCP,passCP);
	mysqli_select_db($conexion,nameCP);
    return $conexion;
}