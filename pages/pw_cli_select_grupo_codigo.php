<?php
require'../clases/cls_cli_grupo.php';
$obj= new cls_cli_grupo;
$obj->s_id($_POST['id']);
echo $obj->fn_cli_grupo_codigo();?>