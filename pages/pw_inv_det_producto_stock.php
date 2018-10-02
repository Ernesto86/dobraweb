<?php
if(!$_POST['bodega'] or !$_POST['producto']){die('{"err":"0"}');}

require'../clases/cls_consulta.php';
$obj = new cls_consulta;
$obj->s_bodega($_POST['bodega']); 
$obj->s_producto($_POST['producto']);

if(!$obj->fn_inv_buscar_producto())die('{"err":"0"}'); 

$data  = $obj->campos();
$stock = intval($data[2]);
$cod   = strval($data[3]);
$pvp   = number_format($data[4],2); 
$cost  = number_format($data[5],4); 

if($stock < 1) die('{"err":"0"}');

echo'{"stock":"'.$stock.'","cod":"'.$cod.'","pvp":"'.$pvp.'","cost":"'.$cost.'"}';?>
