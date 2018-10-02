<?php session_start();
if (!isset($_SESSION['us_id']) or !isset($_SESSION['us_cuenta']) or !isset($_SESSION['us_idtipo'])) {
    die('<--Inicie Session ...');
}
if (!isset($_GET['opc']) or !isset($_GET['idmenu'])) die('Parametros sin Valor');

$opc = $_GET['opc'];
require '../clases/cls_my_transaccion.php';
$otrans = new cls_my_transaccion;
$otrans->transacciones($_GET['idmenu']);
$trans = $otrans->campos();

switch ($opc) {
    case'I':
        if (!$trans['agregar'] or !$trans['ing']) die('No Cuenta con Permiso');
        break;
    default:

        if (!isset($_GET['id'])) die('Parametro Id sin Valor');

        if ($opc == 'M' and !$trans['modificar']) {
            die('No Cuenta con Permiso');
        } else {
            if (!$trans['ing']) die('No Cuenta con Permiso');
        }
        require '../clases/cls_my_usuario.php';
        $ouser = new cls_my_usuario;
        $id = intval($_GET['id']);
        $ouser->s_id($id);
        $ouser->consulta();
        $user = $ouser->campos();
} ?>
<!DOCTYPE html>
<html lang="es">
<head>
    <title>Título de la WEB</title>
    <meta charset="UTF-8">
    <title>Sistema MundoText</title>
    <link rel="stylesheet" type="text/css" href="../css/all_formulario.css">
    <?php if ($opc != 'V') { ?>
        <script type="text/javascript" src="../js/jquery.min.js"></script>
        <script src="../js/jquery.validate.js" type="text/javascript"></script>
        <script type="text/javascript">
            var pag = 'usuario';
            $(document).ready(function () {
                var error = "<h3 class='error' >_desc</h3> ";
                $('#frm_usuario').validate({
                    event: "blur",
                    messages: {
                        'cb_tipo_usuario': error.replace('_desc', 'Tipo de Usuario Requerido'),
                        'cb_persona': error.replace('_desc', 'Requerido'),
                        'txt_passw': error.replace('_desc', 'Clave Requerido'),
                        'txt_cuenta': error.replace('_desc', 'Cuenta Requerido')
                    },
                    debug: true,
                    submitHandler: function (form) {
                        fn_guardar(0)
                    }
                });

                $('#btn_nuevo').hide();
                $('#cb_persona').change(function () {
                    $('#txt_cuenta').val('');
                    $('#txt_passw').val('');
                });
                $('#btn_generar').click(function () {
                    if ($('#cb_persona').val() == '') {
                        alert('Seleccione un Personal');
                        return;
                    }
                    $.ajax({
                        url: 'pw_generar_cuenta.php',
                        data: {id: $('#cb_persona').val(), pag: 'usuario'},
                        type: 'POST',
                        cache: false,
                        dataType: "json",
                        success: function (resp) {
                            if (resp.error == 0) {
                                alert('Error al Generar Cuenta');
                                resp.stopPropagation();
                            }
                            $('#txt_passw').val(resp.clave);
                            $('#txt_cuenta').val(resp.cuenta);
                        }
                    });
                });
            });

            function fn_cancelar() {
                window.location = $('#url').val();
            }
        </script>
    <?php } ?>
</head>
<body>
<div id="buscar">
    <div id="title_form" class="title_form">
        <img src="../images/icon/list-view.png"/> Mantenimiento: <?php echo $_SESSION['dtitle']; ?>
    </div>
</div>

<div id="contenedor" align="center">
    <form id="frm_usuario" name="frm_usuario" method="post" action="">
        <?php if ($opc != 'V') { ?>
            <fieldset style="display:none;">
                <input type="hidden" name="opc" id="opc" value="<?php echo $opc ?>"/>
                <input type="hidden" name="id" id="id" value="<?php echo $id ?>"/>
                <input type="hidden" name="url" id="url"
                       value="<?php echo 'pw_mantenimiento.php?id=' . $_GET['idmenu'] . '&menu=' . $_GET['dtmenu'] . '&tit=' . $_SESSION['dtitle']; ?>"/>
            </fieldset>
        <?php } ?>
        <div id="cabecera" <?php if ($opc == 'V') echo 'style="display:none"'; ?>>
            <div class="left"><input name="btn_nuevo" type="button" id="btn_nuevo" value="Nuevo Registro" class="btn"
                                     onclick="javascript:location.reload();"/>
            </div>
        </div>
        <div id="datos">
            <table width="100%" border="0" cellspacing="0" cellpadding="0">
                <tr>
                    <td>
                        <table border="0" align="center" cellpadding="0" cellspacing="0" class="" id="table_datos">
                            <tr>
                                <td colspan="4" class="bg_cab_title">
                                    <div> Acci&oacute;n : : <?php switch ($opc) {
                                            case'I':
                                                echo 'Nuevo Registro.';
                                                break;
                                            case'M':
                                                echo 'Modificar.';
                                                break;
                                            default:
                                                echo 'Ver Registro.';
                                        } ?>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td width="18">&nbsp;</td>
                                <td width="190">&nbsp;</td>
                                <td width="470">&nbsp;</td>
                                <td width="20">&nbsp;</td>
                            </tr>
                            <tr>
                                <td>&nbsp;</td>
                                <td>
                                    <div align="right">Tipo de Usuario (*)</div>
                                </td>
                                <td><select name="cb_tipo_usuario" id="cb_tipo_usuario"
                                            style="width:250px" <?php if (($opc == 'M' and $_SESSION['iduser'] == $id) or $opc == 'V') echo 'disabled="disabled"' ?>
                                            class="required">
                                        <option value="">Seleccione</option>
                                        <?php
                                        require '../clases/cls_my_tipo_usuario.php';
                                        $otip = new cls_my_tipo_usuario;
                                        if ($otip->cb_tipo_usuario()) $otip->combo($user['tip']);
                                        ?>
                                    </select></td>
                                <td>&nbsp;</td>
                            </tr>
                            <tr>
                                <td>&nbsp;</td>
                                <td>
                                    <div align="right">Personal (*)</div>
                                </td>
                                <td><select name="cb_persona" id="cb_persona"
                                            style="width:350px"<?php if ($opc == 'V') echo 'disabled="disabled"'; ?>
                                            class="required" <?php if (($opc == 'M' and $_SESSION['iduser'] == $id) or $opc == 'V') echo 'disabled="disabled"' ?>>
                                        <option value="">Seleccione</option>
                                        <?php
                                        require '../clases/cls_my_personal.php';
                                        $opers = new cls_my_personal;
                                        if ($opers->cb_personal()) $opers->combo($user['pers']);
                                        ?>
                                    </select></td>
                                <td>&nbsp;</td>
                            </tr>

                            <tr>
                                <td>&nbsp;</td>
                                <td>
                                    <div align="right">Cuenta (*)</div>
                                </td>
                                <td><input name="txt_cuenta" type="text" class="required" id="txt_cuenta"
                                           value="<?php echo $user['cuenta'] ?>" size="20"
                                           maxlength="20" <?php if ($opc == 'V') echo 'readonly="readonly"'; ?>/>
                                    <label>
                                        <input name="btn_generar" type="button" id="btn_generar" value="Generar Cuenta"
                                               class="btn_gcuenta"<?php if ($opc == 'V') echo 'style="display:none"'; ?>/>
                                    </label></td>
                                <td>&nbsp;</td>
                            </tr>

                            <?php if($opc == 'I'):?>
                            <tr>
                                <td>&nbsp;</td>
                                <td>
                                    <div align="right">clave (*)</div>
                                </td>
                                <td><input name="txt_passw" type="text" id="txt_passw"
                                           value="<?php if (is_object($ouser)) {
                                               echo $ouser->decodificar($user['clave']);
                                           } ?>" size="20" maxlength="20"
                                           class="required" <?php if ($opc == 'V') echo 'readonly="readonly"'; ?>/></td>
                                <td>&nbsp;</td>
                            </tr>
                            <?php endif;?>

                            <tr>
                                <td>&nbsp;</td>
                                <td>
                                    <div align="right">Estado</div>
                                </td>
                                <td><select name="cb_estado" id="cb_estado"
                                            style="width:100px"<?php if ($opc == 'V') echo 'disabled="disabled"'; ?>>
                                        <option value="A"<?php if ($user['estd'] == 'A') echo "selected='selected'" ?>>
                                            Activo
                                        </option>
                                        <?php if (in_array($_SESSION['tipo'], $ArrTipUser)) { ?>
                                            <option value="I"<?php if ($user['estd'] == 'I') echo "selected='selected'" ?>>
                                                Inactivo
                                            </option>
                                        <?php } ?>
                                    </select></td>
                                <td>&nbsp;</td>
                            </tr>
                            <tr>
                                <td colspan="4">
                                    <div id="loading"></div>
                                </td>
                            </tr>
                            <tr>
                                <td colspan="4" class="bg_btn">
                                    <?php if ($opc != 'V') { ?>
                                        <table width="100" border="0" align="center" cellpadding="0" cellspacing="0">
                                            <tr>
                                                <td><input name="btn_enviar" type="submit" class="btn_guardar"
                                                           id="btn_enviar"
                                                           value="Guardar" <?php if (($opc == 'M' and $_SESSION['iduser'] == $id) or $opc == 'V') echo 'disabled="disabled"' ?>/>
                                                </td>
                                                <td><input name="btn_cancelar" class="btn_cancelar" type="button"
                                                           id="btn_cancelar" value="Cancelar" onclick="fn_cancelar();"/>
                                                </td>
                                                <td><input name="btn_rest" type="reset" class="btn_actualizar"
                                                           id="btn_rest" value="Restablecer"/></td>
                                            </tr>
                                        </table>
                                    <?php } ?></td>
                            </tr>
                        </table>
                    </td>
                </tr>
            </table>
        </div>
    </form>
</div>
<?php if ($opc != 'V') { ?>
    <script type="text/javascript" src="../js/ajax_form_img.js"></script>
<?php } ?>
</body>
</html>