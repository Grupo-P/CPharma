<?php

/*
Constantes Globales
 */
define("SigDolar","$");
define("SigVe","Bs.S");
define("Utilidad",0.77);
define("Impuesto",1.16);

//Cadena de conexion con la base de datos CPharma (desarrollo)

 define ("serverCP","localhost:3366");	
 define ("userCP","root");
 define ("passCP","");
 define ("nameCP","cpharma");

//Cadena de conexion con el servidor local (desarrollo)
/*
 define ("serverDB" , "SERGIO-PC\CSERVER");	
 define ("userDB" , "admin");
 define ("passDB" , "soporte123");
 define ("nameDB" , "SMART");	
*/
//Cadena de conexion con el servidor remoto (produccion)

define ("serverDB" , "10.100.0.2\SMARTPHARMA,1450");
define ("userDB" , "sa");
define ("passDB" , "Soporte123");
define ("nameDB" , "SMART");

?>