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
    <?php if (!isset($_GET['opc'])) die('Parametros sin Valor');
    $opc = $_GET['opc'];
    require '../clases/cls_my_pais.php';
    $opais = new cls_my_pais;

    if ($opc == 'M' or $opc == 'V') {

        if (!isset($_GET['id'])) die('Parametro Id sin Valor');

        $id = intval($_GET['id']);
        $opais->s_id($id);
        $opais->consulta();
        $pais = $opais->campos();
    }
    ?>
    <title>Documento sin t&iacute;tulo</title>
    <link type="text/css" rel="stylesheet" href="../css/all_formulario.css"/>
    <script type="text/javascript" src="../js/jquery.min.js"></script>
    <script src="../js/jquery.validate.js" type="text/javascript"></script>
    <script type="text/javascript">
        var pag = 'pais';
        $(document).ready(function () {
            var error = "<h3 class='error' >_desc</h3> ";
            $('#form').validate({
                event: "blur",
                messages: {
                    'txt_desc': error.replace('_desc', 'Campo Requerido'),
                    'txt_abrev': error.replace('_desc', ' Requerido')
                },
                submitHandler: function (form) {
                    fn_guardar()
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

<div id="contenedor" align="center">
    <form id="form" name="form" method="post" action="">
        <fieldset style="display:none;">
            <input type="hidden" name="opc" id="opc" value="<?php echo $opc ?>"/>
            <input type="hidden" name="id" id="id" value="<?php echo $id ?>"/>
            <input type="hidden" name="url" id="url"
                   value="<?php echo $_GET['idmenu'] . '&menu=' . $_GET['dtmenu'] . '&tit=' . $_SESSION['dtitle']; ?>"/>
        </fieldset>
        <div id="datos">
            <table width="100%" border="0" cellspacing="0" cellpadding="0">
                <tr>
                    <td>
                        <table width="500" border="0" align="center" cellpadding="0" cellspacing="0" id="table_datos">
                            <tr>
                                <td colspan="2" class="bg_cab_title">
                                    <div class="title_datos">
                                        Acci&oacute;n ::
                                        <?php switch ($opc) {
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
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                            </tr>
                            <tr>
                                <td width="138">
                                    <div align="right">
                                        Descripci&oacute;n(*)
                                    </div>
                                </td>
                                <td width="360"><input name="txt_desc" type="text" id="txt_desc"
                                                       value="<?php echo $pais['Descripcion'] ?>" size="35"
                                                       maxlength="30"
                                                       class="required" <?php if ($opc == 'V') echo 'readonly="readonly"'; ?>/>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <div align="right">Estado</div>
                                </td>
                                <td><select name="cb_estado" id="cb_estado"
                                            style="width:100px" <?php if ($opc == 'V') echo 'disabled="disabled"'; ?>>
                                        <option value="A"<?php if ($pais['Estado'] == 'A') echo "selected='selected'" ?>>
                                            Activo
                                        </option>
                                        <?php if (in_array($_SESSION['tipo'], $ArrTipUser)) { ?>
                                            <option value="I"<?php if ($pais['Estado'] == 'I') echo "selected='selected'" ?>>
                                                Inactivo
                                            </option>
                                        <?php } ?>
                                    </select></td>
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
                                                <td><input name="btn_enviar" type="submit" title="Guardar"
                                                           class="btn_guardar" id="btn_enviar" value="Guardar"/></td>
                                                <td><input name="btn_cancelar" class="btn_cancelar" type="button"
                                                           id="btn_cancelar" value="Retornar"
                                                           onclick="javascript:window.location='pw_mantenimiento.php?id=<?php echo $_GET['idmenu'] . '&menu=' . $_GET['dtmenu'] . '&tit=' . $_SESSION['dtitle']; ?>'"
                                                           title="Retornar"/></td>
                                                <td><input name="btn_rest" type="reset" class="btn_actualizar"
                                                           id="btn_rest" value="Restablecer" title="Restablecer"/></td>
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
    <script type="text/javascript" src="../js/ajax_form.js"></script>
</div>
</body>
</html>
