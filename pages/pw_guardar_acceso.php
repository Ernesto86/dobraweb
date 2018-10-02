<?php session_start();
if(!isset($_SESSION['us_id'])or!isset($_SESSION['us_cuenta'])or!isset($_SESSION['us_idtipo'])){
die('Inicie Session');
}
if(!isset($_POST['tip'])or!isset($_POST['mod']))die('Error al Recibir Parametros');

require '../clases/cls_my_acceso.php';  
$oacc = new cls_my_acceso;
$oacc->s_tipo_usuario($_POST['tip']);
$oacc->s_modulo($_POST['mod']);
$oacc->s_menu($_POST['1']);		
$oacc->s_ingreso($_POST['2']);
$oacc->s_agregar($_POST['3']);
$oacc->s_modificar($_POST['4']);
$oacc->s_eliminar($_POST['5']);
echo $oacc->transaccion();?>