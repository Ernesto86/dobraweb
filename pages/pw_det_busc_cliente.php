<?php  
require'../clases/cls_cliente.php';
$obj = new cls_cliente;
$obj->s_opcion($_REQUEST['opc']);
$obj->s_descripcion($_REQUEST['desc']);
$obj->fn_cli_buscar();?>