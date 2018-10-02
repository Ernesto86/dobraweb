<?php
/**
 * Created by PhpStorm.
 * User: Administrador
 * Date: 11/09/2018
 * Time: 15:56
 */
require '../../setting.php';
define('MOD',DROOT.'seguridad/');
require MOD.'control/CtrUsuario.php';
$ctrUsuario = new CtrUsuario();
$ctrUsuario->view();