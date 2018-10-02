<?php
if(!$_POST['producto']){die('{"err":"0"}');}

require'../clases/cls_consulta.php';
$obj = new cls_consulta;
$obj->s_producto($_POST['producto']);
$idprod = $obj->fn_inv_cod_producto();
echo $idprod ? '{"id":"'.$idprod.'"}' : '{"err":"0"}';
?>