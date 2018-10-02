<?php session_start();
if(!$_POST['fecha']){die('{"err":"0"}');}
@header('Content-type: text/html; charset=iso-8859-1');

$_SESSION['us_fecha'] = trim($_POST['fecha']);

if($_SESSION['us_fecha']){die('{"rp":"1"}');}

die('{"err":"1"}');?>