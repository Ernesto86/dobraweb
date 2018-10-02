<?php 
require'../clases/cls_usuario_mysql.php';
$obj = new cls_usuario_mysql;
$obj->set_usuario_online($_POST['user']);
$obj->fn_usuarios_online();
?>