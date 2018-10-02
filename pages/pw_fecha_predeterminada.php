<?php session_start();
if (!isset($_SESSION['us_id']) or !isset($_SESSION['us_cuenta']) or !isset($_SESSION['us_idtipo'])) {
    die('<--Inicie Session ...');
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <title>Título de la WEB</title>
    <meta charset="UTF-8">
    <title>Sistema MundoText</title>
    <title>Documento sin t&iacute;tulo</title>
    <link type="text/css" rel="stylesheet" href="../css/all_formulario.css"/>
    <link rel="stylesheet" href="../css/jquery.ui.all.css">
    <script type="text/javascript" src="../js/jquery.min.js"></script>
    <script src="../js/jquery.validate.js" type="text/javascript"></script>
    <script src="../js/jquery.ui.core.js"></script>
    <script src="../js/jquery.ui.datepicker.js"></script>
    <script src="../js/jquery.ui.datepicker-es.js"></script>
    <script type="text/javascript">
        $(document).ready(function () {
            var error = "<h3 class='error' >_desc</h3> ";
            $('#form').validate({
                event: "blur",
                messages: {'txt_ing_fecha': error.replace('_desc', 'Campo Fecha Requerido')},
                submitHandler: function (form) {

                    try {

                        $.ajax({
                            url: 'pw_data_fecha_predeterminada.php',
                            data: {fecha: $('#txt_ing_fecha').val()},
                            type: "POST",
                            async: true,
                            cache: false,
                            dataType: 'json',
                            ifModified: false,
                            beforeSend: function () {
                                $('#loading').addClass('loading');
                                $('#btn_enviar').attr("disabled", true);
                            },
                            success: function (resp) {

                                $('#loading').removeClass('loading');
                                $('#btn_enviar').attr("disabled", false);

                                if (resp.rp == '1') {

                                    alert('La Fecha se Establecio Correctamente..');
                                    resp.stopPropagation();

                                }

                                switch (resp.err) {

                                    case'1':
                                        alert('Error: La Fecha no se Establecio.');
                                        break;

                                    case'0':
                                        alert('Error: Parametros no Validos o estan Vacios.');

                                }

                                resp.stopPropagation();
                            },
                            error: function () {
                                $('#loading').removeClass('loading');
                                $('#btn_enviar').attr("disabled", false);
                                alert('Error al Intentar Guardar el Registro.');
                            }
                        });
                    } catch (e) {
                        alert("Error: " + e.description);
                        stopPropagation();
                    }


                }
            });
        });
    </script>
</head>
<body>
<div id="buscar">
    <div id="title_form" class="title_form">
        <img src="../images/icon/list-view.png"/> Mantenimiento: <?php echo $_SESSION['dtitle']; ?>
    </div>
</div>

<div id="contenedor" class="centrar">
    <form id="form" name="form" method="post" action="">
        <fieldset style="display:none;">
            <input type="hidden" name="opc" id="opc" value="<?php echo $opc ?>"/>
            <input type="hidden" name="id" id="id" value="<?php echo $id ?>"/>
            <input type="hidden" name="url" id="url"
                   value="<?php echo $_GET['idmenu'] . '&menu=' . $_GET['dtmenu'] . '&tit=' . $_SESSION['dtitle'] ?>"/>
        </fieldset>
        <div id="datos">
            <table width="100%" border="0" cellspacing="0" cellpadding="0">
                <tr>
                    <td>
                        <table width="500" border="0" align="center" cellpadding="0" cellspacing="0" id="table_datos">
                            <tr>
                                <td colspan="2" class="bg_cab_title">
                                    <img src="../images/icon/grid-view.png" class="icon_trans"/>
                                    <div class="title_datos">
                                        Acci&oacute;n :: Fecha Predeterminada
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td width="166">&nbsp;</td>
                                <td width="332">&nbsp;</td>
                            </tr>
                            <tr>
                                <td>
                                    <div align="right">Establecer Fecha:</div>
                                </td>
                                <td><input name="txt_ing_fecha" type="text" id="txt_ing_fecha" size="12" maxlength="10"
                                           value="<?php echo date("d/m/Y"); ?>" class="fecha datos"
                                           readonly="readonly"/></td>
                            </tr>
                            <tr>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                            </tr>
                            <tr>
                                <td colspan="2">
                                    <div id="loading"></div>
                                </td>
                            </tr>
                            <tr>
                                <td colspan="2" class="bg_btn">
                                    <?php if ($opc != 'V') { ?>
                                        <table width="100" border="0" align="center" cellpadding="0" cellspacing="0">
                                            <tr>
                                                <td><input name="btn_enviar" type="submit" class="btn_guardar"
                                                           id="btn_enviar" value="Guardar"/></td>
                                                <td>&nbsp;</td>
                                                <td><input name="btn_rest" type="reset" class="btn_actualizar"
                                                           id="btn_rest" value="Restablecer"/></td>
                                            </tr>
                                        </table>
                                    <?php } ?>                </td>
                            </tr>
                        </table>
                    </td>
                </tr>
                <tr>
                    <td>&nbsp;</td>
                </tr>
            </table>
        </div>
    </form>
</div>
</body>
</html>