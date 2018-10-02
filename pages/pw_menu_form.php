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
    <?php
    if (!isset($_REQUEST['opc'])) die('Parametros sin Valor');
    $opc = $_REQUEST['opc'];

    require '../clases/cls_my_menu.php';
    $omenu = new cls_my_menu;

    if ($opc == 'M' or $opc == 'V') {
        if (!isset($_REQUEST['id'])) die('Parametro Id sin Valor');
        $id = (int)$_REQUEST['id'];
        $omenu->s_id($id);
        $omenu->consulta();
        $menu = $omenu->campos();
    }
    ?>
    <title>Documento sin t&iacute;tulo</title>
    <link type="text/css" rel="stylesheet" href="../css/all_formulario.css"/>
    <script type="text/javascript" src="../js/jquery.min.js"></script>
    <script src="../js/jquery.validate.js" type="text/javascript"></script>
    <script type="text/javascript">
        var pag = 'menu';
        $(document).ready(function () {
            var error = "<h3 class='error' >_desc</h3> ";
            $('#form').validate({
                event: "blur",
                messages: {
                    'txt_desc': error.replace('_desc', 'Campo Descripción Requerido'),
                    'cb_modulo': error.replace('_desc', 'Módulo Requerido'),
                    'txt_orden': error.replace('_desc', 'Orden Requerido')
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
                                        Acci&oacute;n :: <?php switch ($opc) {
                                            case'I':
                                                echo 'Nuevo Registro.';
                                                break;
                                            case'M':
                                                echo 'Modificar.';
                                                break;
                                            default:
                                                echo 'Ver Registro.';
                                        } ?></div>
                                </td>
                            </tr>
                            <tr>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                            </tr>
                            <tr>
                                <td width="166">
                                    <div align="right">M&oacute;dulo (*)
                                    </div>
                                </td>
                                <td width="332"><select name="cb_modulo" id="cb_modulo" style="width:250px"
                                                        class="required" <?php if ($opc == 'V') echo 'disabled="disabled"'; ?>>
                                        <option value="">Seleccione</option>
                                        <?php
                                        require '../clases/cls_my_modulo.php';
                                        $omod = new cls_my_modulo;
                                        if ($omod->cb_modulo()) $omod->combo($menu['modulo']);
                                        ?>
                                    </select></td>
                            </tr>
                            <tr>
                                <td>
                                    <div align="right">Men&uacute; (*)</div>
                                </td>
                                <td><input name="txt_desc" type="text" id="txt_desc"
                                           value="<?php echo $menu['descrip'] ?>" size="33" maxlength="30"
                                           class="required" <?php if ($opc == 'V') echo 'readonly="readonly"'; ?>/></td>
                            </tr>
                            <tr>
                                <td>
                                    <div align="right">Orden (*)</div>
                                </td>
                                <td><input name="txt_orden" type="text" id="txt_orden"
                                           onkeydown="javascript:if((event.keyCode&gt;64 &amp;&amp; event.keyCode&lt;90)){return false;}"
                                           size="4" maxlength="4" class="required"
                                           value="<?php echo $menu['orden'] ?>" <?php if ($opc == 'V') echo 'disabled="disabled"'; ?>/>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <div align="right">Estado</div>
                                </td>
                                <td><select name="cb_estado" id="cb_estado"
                                            style="width:100px" <?php if ($opc == 'V') echo 'disabled="disabled"'; ?>>
                                        <option value="A"<?php if ($menu['estado'] == 'A') echo "selected='selected'" ?>>
                                            Activo
                                        </option>
                                        <?php if (in_array($_SESSION['tipo'], $ArrTipUser)) { ?>
                                            <option value="I"<?php if ($menu['estado'] == 'I') echo "selected='selected'" ?>>
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
                                                <td><input name="btn_enviar" type="submit" class="btn_guardar"
                                                           id="btn_enviar" value="Guardar"/></td>
                                                <td><input name="btn_cancelar" class="btn_cancelar" type="button"
                                                           id="btn_cancelar" value="Cancelar"
                                                           onclick="javascript:window.location='pw_mantenimiento.php?id=<?php echo $_GET['idmenu'] . '&menu=' . $_GET['dtmenu'] . '&tit=' . $_SESSION['dtitle']; ?>'"/>
                                                </td>
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
    <script type="text/javascript" src="../js/ajax_form.js"></script>
</div>
</body>
</html>