<?php
session_start();
if(!isset($_POST['pwact'])or!isset($_POST['pwnew'])){ die('{"resp":"0"}');}  
require '../clases/cls_usuario.php';
$ouser = new cls_usuario;
$ouser->s_cuenta($_SESSION['us_cuenta']);
$ouser->s_password($_POST['pwact']);
if(!$ouser->fn_seg_validar_acceso()){die('{"resp":"2"}');}
 $ouser->s_id($_SESSION['us_id']);
 $ouser->s_password($_POST['pwnew']);
 if($ouser->fn_seg_guardar_password()){
  die('{"resp":"1"}');
 }
die('{"resp":"0"}');
?>