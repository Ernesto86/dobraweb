<?php
/**
 * Created by PhpStorm.
 * User: Administrador
 * Date: 14/09/2018
 * Time: 17:18
 */
require '../../setting.php';
define('MOD',DROOT.'inventario/');
require MOD.'control/CtrProducto.php';
$ctrProducto= new CtrProducto();
$ctrProducto->view();