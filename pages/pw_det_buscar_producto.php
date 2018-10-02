<?php 
require'../clases/cls_consulta.php';
$obj = new cls_consulta;
$obj->s_opcion($_REQUEST['opc']);
$obj->s_descripcion($_REQUEST['desc']);
$obj->fn_inv_buscar_producto(); ?>