<?php session_start();
if (!isset($_SESSION['us_id']) or !isset($_SESSION['us_cuenta']) or !isset($_SESSION['us_idtipo'])) {
    die('<--Inicie Session ...');
}

@header('Content-type: text/html; charset=iso-8859-1');

if (!isset($_POST['id']) or !isset($_POST['pag'])) die('Error al Recibir la Opcion');

$class = 'cls_my_' . $_POST['pag'];
require "../clases/$class.php";
$obj = new $class;
$obj->s_id($_POST['id']);
if ($obj->datos()) {
    $cmp = $obj->campos();
    $ape = split(' ', $cmp['ape']);
    $cuenta = '';
    if (trim($cmp['nom'])) {
        $cuenta = substr($cmp['nom'], 0, 1);
    }
    if (trim($ape[0])) {
        $cuenta .= '.' . $ape[0];
    }
    if (trim($ape[1])) {
        $cuenta .= '.' . substr($ape[1], 0, 1);
    }
    if (!strlen($cuenta)) echo '{"error":"0"}';

    $cuenta = $obj->eliminar_acentos(strtolower(str_replace(' ', '.', $cuenta)));
    $clave = substr(md5(uniqid(rand())), 0, 7);
    die("{'cuenta':'$cuenta','clave':'$clave'}");
}
echo '{"error":"0"}';
?>