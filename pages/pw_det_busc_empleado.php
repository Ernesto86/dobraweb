<?php   
require'../clases/cls_empleado.php';
$obj = new cls_empleado;
$obj->s_descripcion($_REQUEST['desc']);
$obj->fn_emp_buscar();?>
