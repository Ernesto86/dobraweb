<?php session_start();
if (!isset($_SESSION['us_id']) or !isset($_SESSION['us_cuenta']) or !isset($_SESSION['us_idtipo'])) {
    die('<--Inicie Session ...');
}
if (!isset($_POST['opc'])) die('Error al Recibir la Opcion');

$opc = $_POST['opc'];
require '../clases/cls_my_usuario.php';
$ouser = new cls_my_usuario;
$ouser->s_opcion($opc);
if ($opc != 'I') {
    if (!isset($_POST['id'])) die('Error al Recibir el Id');

    $ouser->s_id($_POST['id']);


}else{
    $ouser->s_password($_POST['txt_passw']);
}
$ouser->s_tipo_usuario($_POST['cb_tipo_usuario']);

if ($_SESSION['tipo'] == 'administrador' and $_SESSION['iduser'] == $_POST['id']) {
    $ouser->s_tipo_usuario(1);
}
$ouser->s_idpersona($_POST['cb_persona']);
$ouser->s_cuenta($_POST['txt_cuenta']);
$ouser->s_estado($_POST['cb_estado']);
echo $ouser->transaccion(); ?>