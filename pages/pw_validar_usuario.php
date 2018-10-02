<?php
$res = new stdClass;
$res->resp = false;
if (!isset($_POST['user']) or !isset($_POST['passw'])) {
    $res->error = 'Parametros Vacios';
}

try {

    require '../clases/cls_my_usuario.php';
    $ouser = new cls_my_usuario;
    $ouser->s_cuenta($_POST['user']);
    $ouser->s_password($_POST['passw']);

    if (!$ouser->validar_acceso()) $res->error = 'El usuario ingresaso es incorrecto.';

    $user = $ouser->campos();
    if (is_null($user['id'])) $res->error = 'El usuario ingresaso es incorrecto.';

    if(session_id() == ''){
        session_start();
    }
    $_SESSION['us_id'] = intval($user['id']);
    $_SESSION['us_idpers'] = intval($user['idpers']);
    $_SESSION['us_idtipo'] = intval($user['idtip']);
    $_SESSION['us_tipuser'] = strtolower($user['tipuser']);
    $_SESSION['us_cuenta'] = ucwords($_POST['user']);

    if ($_SESSION['us_id'] and $_SESSION['us_idpers'] and $_SESSION['us_cuenta'] and $_SESSION['us_idtipo']) {
        $res->resp = true;
    }
} catch (Exception $ex) {
    $res->error = 'Inicio se Sessión Fallida.';
}
echo json_encode($res);


