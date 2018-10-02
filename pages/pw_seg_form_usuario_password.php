<?php session_start();
if (!isset($_SESSION['us_id']) or !isset($_SESSION['us_cuenta'])) {
    header("location: ../index.php");
}
?>
<link rel="stylesheet" type="text/css" href="../css/style_login_password.css">
<link type="text/css" rel="stylesheet" href="../css/uploadify.css"/>
<script language="javascript">
    $(document).ready(function () {
        $('#images').fileUpload({
            'uploader': '../flahs/uploader.swf',
            'cancelImg': '../images/cancel.png',
            'buttonText': 'Seleccione Foto',
            'folder': '../galeria/',
            'script': 'pw_subir_image.php',
            //  'checkScript' : 'check.php',
            'scriptData': {'user': user_online, 'dir': 'usuarios'},
            'fileDataName': 'image',
            'fileExt': '*.jpg;*.png;*.jpeg;*.gif',
            'sizeLimit': 800 * 1024,
            'multi': false,
            'simUploadLimit': 1,
            'onComplete': function (event, queueID, fileObj, response, data) {
                $('#foto_online').attr('src', String(response));
                $('#todo_online_index').attr('src', String(response))
            }
        });
    });

    function fn_guardar_password() {
        var act_passw = $('#txt_passw_act').val();
        var new_passw = $('#txt_passw_new').val();
        var conf_passw = $('#txt_passw_conf').val();
        if (!act_passw) {
            alert('Ingrese Password Actual.');
            $('#txt_passw_act').focus();
            return;
        }
        if (!new_passw) {
            alert('Ingrese Password Nuevo.');
            $('#txt_passw_new').focus();
            return;
        }
        if (!conf_passw) {
            alert('Ingrese Password Confirmaci�n.');
            $('#txt_passw_conf').focus();
            return;
        }
        if (new_passw != conf_passw) {
            alert('El Password de Confirmaci�n no coincide con el Nuevo Password.');
            $('#txt_passw_conf').focus();
            return;
        }
        $.ajax({
            url: 'pw_seg_guardar_password.php',
            data: {pwact: act_passw, pwnew: new_passw},
            type: "POST",
            async: true,
            cache: false,
            dataType: "json",
            contentType: "application/x-www-form-urlencoded",
            ifModified: false,
            beforeSend: function () {
                $('#loading').addClass('loading');
            },
            success: function (data) {
                $('#loading').removeClass('loading');
                switch (data.resp) {
                    case '1':
                        alert('El Password Guardado Correctamente.');
                        $(".float-content").hide("fat");
                        break;
                    case '2':
                        alert('El Password Actual no es el Correcto..');
                        $('#txt_passw_act').focus();
                        break;
                    default :
                        alert('Error al Guardar Password Intentelo de Nuevo.');
                }
                data.stopPropagation();
            },
            error: function () {
                $('#loading').removeClass('loading');
                alert('Fallo Conectando con el servidor');
            }
        });
    }
</script>
<div align="center" class="cmb_passw_title">Cambio de Password</div>
<div>
    <form id="form_login" name="form_login" action="javascript:fn_guardar_password();">
        <table width="400" border="0" align="center" cellpadding="0" cellspacing="0" id="table_login">
            <tr>
                <td width="128" rowspan="4" align="center">
                    <div id="user_foto"><img src="<?= $_SESSION['us_ruta_img']; ?>" class="foto" id="foto_online"/>
                    </div>
                </td>
                <td width="71" align="right">&nbsp;</td>
                <td width="151">&nbsp;</td>
            </tr>
            <tr>
                <td align="right"><strong>Usuario:</strong></td>
                <td><?= $_SESSION['us_cuenta']; ?></td>
            </tr>
            <tr>
                <td align="right"><strong>Nombres:</strong></td>
                <td><?= $_SESSION['us_nomb']; ?></td>
            </tr>
            <tr>
                <td align="right"><strong>Tipo:</strong></td>
                <td>Usuario</td>
            </tr>
            <tr>
                <td colspan="3" class="linea" align="center">
                    <table border="0" align="center" cellpadding="0" cellspacing="0" class="relleno">
                        <tr>
                            <th align="center" scope="col">
                                <div align="center">
                                    <input name="images" type="file" id="images"/>
                                </div>
                            </th>
                        </tr>
                        <tr>
                            <td>
                                <div align="center">
                                    <input name="btn_enviar" class="green" type="button" id="btn_enviar" value=" Subir "
                                           onclick="javascript: $('#images').fileUploadStart()"/>
                                </div>
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
            <tr>
                <td>&nbsp;</td>
                <td colspan="2">&nbsp;</td>
            </tr>
            <tr>
                <td align="right"><strong>Password Actual</strong></td>
                <td colspan="2">
                    <input name="txt_passw_act" type="password" id="txt_passw_act" size="26"/></td>
            </tr>
            <tr>
                <td align="right"><strong>Password Nuevo</strong></td>
                <td colspan="2"><input name="txt_passw_new" type="password" id="txt_passw_new" size="26"/></td>
            </tr>
            <tr>
                <td align="right"><strong>Confirmar</strong></td>
                <td colspan="2"><input name="txt_passw_conf" type="password" id="txt_passw_conf" size="26"/></td>
            </tr>
            <tr>
                <td>&nbsp;</td>
                <td colspan="2">
                    <div id="loading"></div>
                </td>
            </tr>
            <tr>
                <td colspan="3" align="center">
                    <table width="100" border="0" align="center" cellpadding="0" cellspacing="0">
                        <tr>
                            <td>
                                <input type="submit" name="Submit" value="Guardar Password" class="green"/></td>
                            <td><input type="reset" name="Submit2" value="Restablecer" class="btn"/></td>
                        </tr>
                    </table>
                </td>
            </tr>
        </table>
    </form>
</div>

