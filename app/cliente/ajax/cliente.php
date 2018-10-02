<?php
/**
 * Created by PhpStorm.
 * User: Administrador
 * Date: 11/09/2018
 * Time: 22:07
 */
require '../../setting.php';
define('MOD',DROOT.'cliente/');
require MOD.'control/CtrCliente.php';
$ctrCliente= new CtrCliente();
$ctrCliente->view();