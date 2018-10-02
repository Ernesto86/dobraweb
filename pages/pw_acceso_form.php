<?php session_start();
if (!isset($_SESSION['us_id']) or !isset($_SESSION['us_cuenta']) or !isset($_SESSION['us_idtipo'])) {
    die('<--Inicie Session ...');
}
if (!isset($_REQUEST['id'])) {
    die('Parametros sin Valor');
}
require '../clases/cls_my_transaccion.php';
$otrans = new cls_my_transaccion;
$otrans->transacciones($_REQUEST['id']);
$trans = $otrans->campos();
if (!$trans['agregar'] or !$trans['ing']) die('No Cuenta con Permiso para este Menu'); ?>

<!DOCTYPE html>
<html lang="es">
<head>
    <title>Título de la WEB</title>
    <meta charset="UTF-8">
    <title>Sistema MundoText</title>
    <link rel="stylesheet" type="text/css" href="../css/all_formulario.css">
    <script type="text/javascript" src="../js/jquery.min.js"></script>
    <script src="../js/jquery.validate.js" type="text/javascript"></script>
    <script type="text/javascript">
        $.ajaxSetup({
            'beforeSend': function (xhr) {
                if (xhr.overrideMimeType) {//Para Google Chrome y Firefox
                    xhr.overrideMimeType('text/html; charset=iso-8859-1');
                } else {//Para IE8
                    xhr.setRequestHeader('Content-type', 'text/html; charset=utf-8');
                }
            }
        });

        $(document).ready(function () {
            var error = "<h3 class='error' >_desc</h3> ";
            $('#frm_acceso').validate({
                event: "blur",
                messages: {
                    'cb_tipo_usuario': error.replace('_desc', 'Requerido'),
                    'cb_modulo': error.replace('_desc', 'Requerido')
                },
                debug: true,
                submitHandler: function (form) {
                    var i, str;
                    var value = 'tip=' + $('#cb_tipo_usuario').val() + '&mod=' + $('#cb_modulo').val();

                    $("#detalle tbody tr").each(function (index) {
                        i = 1;
                        $(this).children("td").each(function (index) {
                            $(this).children("input[checkbox,hidden]").each(function () {
                                if (i == 1) {
                                    str = i + '=' + $(this).val();
                                } else {
                                    var val = 0;
                                    if ($(this).attr('checked')) {
                                        val = 1;
                                    }
                                    str = str + '&' + i + '=' + val
                                }
                                i++;
                            });
                        });
                        if (str.length > 0) {
                            $.ajax({
                                url: 'pw_guardar_acceso.php',
                                data: str + '&' + value,
                                type: 'POST',
                                cache: false,
                                success: function (resp) {
                                    if (parseInt(resp) <= 0) {
                                        alert('Error:' + resp);
                                    }
                                }
                            });
                        }
                    });
                    alert('Acceso guardado Correctamente');
                }
            });

            $('#table_datos .cbox').change(function () {
                $('#menu_transaccion').html('');
                if ($('#cb_modulo').val() == '' || $('#cb_tipo_usuario').val() == '') {
                    return;
                }

                $.ajax({
                    url: 'pw_detalle_acceso.php',
                    data: {tipo: $('#cb_tipo_usuario').val(), id: $('#cb_modulo').val()},
                    type: "POST",
                    async: true,
                    cache: false,
                    contentType: "application/x-www-form-urlencoded",
                    ifModified: false,
                    beforeSend: function (objeto) {
                        $('#menu_transaccion').addClass('loading');
                    },
                    success: function (data) {
                        $("#menu_transaccion").removeClass('loading');
                        $('#menu_transaccion').html(data);
                        resp.stopPropagation();
                    },
                    error: function () {
                        $("#menu_transaccion").removeClass('loading');
                        alert('Fallo conectando con el servidor');
                    }
                });

            });
        });
    </script>
</head>
<body>
<div id="buscar">
    <div id="title_form" class="title_form">
        <img src="../images/icon/list-view.png"/> Mantenimiento: <?php echo $_GET['tit']; ?>
    </div>
</div>
<div id="contenedor" align="center">
    <form id="frm_acceso" name="frm_acceso" method="post" action="">
        <div id="form_transaccion">
            <table border="0" align="center" cellpadding="0" cellspacing="0" class="" id="table_datos">
                <tr>
                    <td colspan="4" class="bg_cab_title"><img src="../images/icon/grid-view.png" class="icon_trans"/>
                        <div <?php if ($opc == 'V') echo 'style="display:none"'; ?>>
                            Permisos de Acceso al Sistema.
                        </div>
                    </td>
                </tr>
                <tr>
                    <td colspan="4">&nbsp;</td>
                </tr>
                <tr>
                    <td width="55" class="bg_foto">
                        <div align="right">Perfil</div>
                    </td>
                    <td width="316" class="bg_foto"><select name="cb_tipo_usuario" id="cb_tipo_usuario"
                                                            style="width:260px" class="cbox required">
                            <option value="">Seleccione</option>
                            <?php
                            require '../clases/cls_my_tipo_usuario.php';
                            $otip = new cls_my_tipo_usuario;
                            if ($otip->cb_tipo_usuario()) $otip->combo();
                            ?>
                        </select></td>
                    <td width="82" class="bg_foto">
                        <div align="right">M&oacute;dulos</div>
                    </td>
                    <td width="288" class="bg_foto"><select name="cb_modulo" id="cb_modulo" style="width:260px"
                                                            class="cbox required">
                            <option value="">Seleccione</option>
                            <?php
                            require '../clases/cls_my_modulo.php';
                            $omod = new cls_my_modulo;
                            if ($omod->cb_modulo()) $omod->combo(); ?>
                        </select></td>
                </tr>
                <tr>
                    <td colspan="4">&nbsp;</td>
                </tr>
                <tr>
                    <td colspan="4">
                        <div id="menu_transaccion">
                        </div>
                    </td>
                </tr>
                <tr>
                    <td colspan="4" class="">&nbsp;</td>
                </tr>
                <tr>
                    <td colspan="4" class="bg_btn">
                        <table width="100" border="0" align="center" cellpadding="0" cellspacing="0">
                            <tr>
                                <td><input name="btn_enviar" type="submit" class="btn_guardar" id="btn_enviar"
                                           value="Guardar"/></td>
                            </tr>
                        </table>
                    </td>
                </tr>
            </table>
        </div>
    </form>
</div>
</body>
</html>