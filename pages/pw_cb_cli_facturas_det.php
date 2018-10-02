<?php if (!$_POST['id']) return false;
@header('Content-type: text/html; charset=iso-8859-1');
require '../clases/cls_consulta.php';
$obj = new cls_consulta;
$obj->s_idcliente($_POST['id']);
if (!$obj->fn_cb_cli_facturas_det()) return false;
$obj->combo();
?>
