<?php
error_reporting(E_ALL);
ini_set("display_errors", 0);

define('DROOT', $_SERVER['DOCUMENT_ROOT'].'/dobraweb/app/');
define('TEMPLATE', DROOT.'template/');
define('CONEXION', DROOT.'database/DataConection.php');
define('SAL', 'CrazyassLongSALTThatMakesYourUsersPasswordVeryLong123!!312567__asdSdas');

define('MYDB_HOST', 'localhost');
define('MYDB_BASE', 'dinastia');
define('MYDB_USER', 'root');
define('MYDB_PASS', '');

//define('DB_HOST', '192.168.30.99');
define('DB_HOST', 'localhost');
define('DB_BASE', 'dobraweb');
//define('DB_USER', 'contabilidad');
define('DB_USER', 'sa');
//define('DB_PASS', '@mundo@2017');
define('DB_PASS', '123456');

/*Datos de la empresa*/
define('NOMBRE_EMPRESA', 'INSTITUTO TECNOLÓGICO SUPERIOR JUAN BAUTISTA AGUIRRE');
define('DIRECCION_EMPRESA', 'Km 48 Vía a Santa Lucía. Daule-Guayas-Ecuador');
define('TELEFONO_EMPRESA', '(04) 2797899');
define('EMAIL_EMPRESA', 'secretaria@itsjba.edu.ec');

define('IVA',0.12);
define('DIVISA','0000000001');
define('SUC','00');
define('CAMBIO',1);