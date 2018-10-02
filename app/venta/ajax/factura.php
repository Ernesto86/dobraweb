<?php
/**
 * Created by PhpStorm.
 * User: Administrador
 * Date: 11/09/2018
 * Time: 22:07
 */
require '../../setting.php';
define('MOD',DROOT.'venta/');
require MOD.'control/CtrFactura.php';
$ctrFactura= new CtrFactura();
$ctrFactura->view();