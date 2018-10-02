<?php if(!$_POST['idfact']){die('{"err":"0"}');}
@header('Content-type: text/html; charset=iso-8859-1');
require'../clases/cls_consulta.php';
$obj = new cls_consulta;
$obj->s_id_factura($_POST['idfact']); 
$obj->fn_cli_ven_detalle_factura();?>