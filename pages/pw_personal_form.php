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
        require '../clases/cls_my_personal.php';
        $opers = new cls_my_personal;
        $id = intval($_GET['id']);
        $opers->s_id($id);
        $opers->consulta();
        $pers = $opers->campos();
} ?>
<!DOCTYPE html>
<html lang="es">
<head>
    <title>Título de la WEB</title>
    <meta charset="UTF-8">
    <title>Sistema MundoText</title>
    <link rel="stylesheet" type="text/css" href="../css/all_formulario.css">
    <?php if ($opc != 'V') { ?>
        <link rel="stylesheet" href="../css/jquery.ui.all.css">
        <script type="text/javascript" src="../js/jquery.min.js"></script>
        <script src="../js/jquery.validate.js" type="text/javascript"></script>
        <script src="../js/jquery.ui.core.js"></script>
        <script src="../js/jquery.ui.datepicker.js"></script>
        <script src="../js/jquery.ui.datepicker-es.js"></script>
        <script type="text/javascript">
            var pag = 'personal';
            var id =<?php echo intval($id);?> ;
            var opc =<?php echo "'$opc'"?> ;
            $.ajaxSetup({
                'beforeSend': function (xhr) {
                    if (xhr.overrideMimeType) {//Para Google Chrome y Firefox
                        xhr.overrideMimeType('text/html; charset=iso-8859-1');
                    } else {//Para IE8
                        xhr.setRequestHeader('Content-type', 'text/html; charset=iso-8859-1');
                    }
                }
            });
            $(document).ready(function () {
                var error = "<h3 class='error' >_desc</h3> ";
                $('#frm_personal').validate({
                    event: "blur",
                    messages: {
                        'txt_ape': error.replace('_desc', 'Apellidos Requerido'),
                        'txt_email': error.replace('_desc', 'Email Incorrecto'),
                        'txt_nom': error.replace('_desc', 'Nombres Requerido'),
                        'txt_doc': error.replace('_desc', 'Num. Documento Requerido'),
                        'cb_pais': error.replace('_desc', 'Pais Requerido'),
                        'cb_provincia': error.replace('_desc', 'Provincia Requerido'),
                        'cb_ciudad': error.replace('_desc', 'Ciudad Requerido'),
                        'cb_tipo_doc': error.replace('_desc', 'Tipo de Doc. Requerido')
                        <?php if($_SESSION['tipo'] == 'super administrador'){?>
                        , 'cb_periodo': error.replace('_desc', 'Campo Requerido')
                        <?php }?>
                    },
                    debug: true,
                    submitHandler: function (form) {
                        var tipo = $('#cb_tipo_doc option:selected').text().toLowerCase();
                        if (tipo == 'cédula' || tipo == 'cedula') {
                            if (!check_cedula($('#txt_doc').val(), '#txt_doc')) {
                                form.stopPropagation();
                            }
                        }
                        fn_guardar(1);
                    }
                });
                $('#btn_nuevo').hide();
            });
        </script>
        <script type="text/javascript" src="../js/valida_cedula.js"></script>
    <?php } ?>
</head>
<body>
<div id="buscar">
    <div id="title_form" class="title_form">
        <img src="../images/icon/list-view.png"/> Mantenimiento: <?php echo $_SESSION['dtitle']; ?>
    </div>
</div>
<div id="contenedor" align="center">
    <form id="frm_personal" name="frm_personal" method="post" action="">
        <?php if ($opc != 'V') { ?>
            <fieldset style="display:none;">
                <input type="hidden" name="opc" id="opc" value="<?php echo $opc ?>"/>
                <input type="hidden" name="id" id="id" value="<?php echo $id ?>"/>
                <input type="hidden" name="url" id="url"
                       value="<?php echo 'pw_mantenimiento.php?id=' . $_GET['idmenu'] . '&menu=' . $_GET['dtmenu'] . '&tit=' . $_SESSION['dtitle']; ?>"/>
                <input type="hidden" name="id_provincia" id="id_provincia" value="<?php echo $pers['prv'] ?>"/>
                <input type="hidden" name="id_ciudad" id="id_ciudad" value="<?php echo $pers['ciu'] ?>"/>
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
                        <table width="889" border="0" align="center" cellpadding="0" cellspacing="0" class=""
                               id="table_datos">
                            <tr>
                                <td colspan="6" class="bg_cab_title">
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
                                <td colspan="3" class="bg_foto">
                                    <div align="center"><img src="<?php if (strlen($pers['img']) > 0) {
                                            echo $pers['img'];
                                        } else {
                                            echo '../images/user/usu.gif';
                                        } ?>" id="foto" class="foto"/></div>
                                </td>
                                <td colspan="3" class="bg_foto">
                                    <div class="" id="subir_image">
                                        <div align="center">
                                            <?php if ($opc == 'M') echo "<a href='javascript:fn_image()' class='btn' >Editar Foto del Perfil</a>" ?>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td width="12">&nbsp;</td>
                                <td width="87">&nbsp;</td>
                                <td width="296">&nbsp;</td>
                                <td width="85">&nbsp;</td>
                                <td width="396">&nbsp;</td>
                                <td width="11">&nbsp;</td>
                            </tr>
                            <?php
                            if ($_SESSION['tipo'] == 'super administrador') { ?>
                                <tr>
                                    <td>&nbsp;</td>
                                    <td>
                                        <div align="right">Instituci&oacute;n(*)</div>
                                    </td>
                                    <td colspan="3">&nbsp;</td>
                                    <td>&nbsp;</td>
                                </tr>
                            <?php } ?>
                            <tr>
                                <td>&nbsp;</td>
                                <td>
                                    <div align="right">Tipo Doc.(*)</div>
                                </td>
                                <td>
                                    <select name="cb_tipo_doc" id="cb_tipo_doc" style="width:250px"
                                            class="required" <?php if ($opc == 'V') echo 'disabled="disabled"'; ?>>
                                        <option value="">Seleccione</option>
                                    </select></td>
                                <td>
                                    <div align="right">N&uacute;m. Doc.(*)</div>
                                </td>
                                <td><input name="txt_doc" type="text" class="required" id="txt_doc"
                                           value="<?php echo $pers['num_doc'] ?>" size="15"
                                           maxlength="10"<?php if ($opc == 'V') echo 'readonly="readonly"'; ?>
                                           onkeydown="javascript:if((event.keyCode>64 && event.keyCode<90)){return false;}"/>
                                </td>
                                <td>&nbsp;</td>
                            </tr>
                            <tr>
                                <td>&nbsp;</td>
                                <td>
                                    <div align="right">Nombres(*)</div>
                                </td>
                                <td><input name="txt_nom" type="text" class="required" id="txt_nom"
                                           value="<?php echo $pers['nom'] ?>" size="35"
                                           maxlength="30" <?php if ($opc == 'V') echo 'readonly="readonly"'; ?>/></td>
                                <td>
                                    <div align="right">Apellidos(*)</div>
                                </td>
                                <td><input name="txt_ape" type="text" class="required" id="txt_ape"
                                           value="<?php echo $pers['ape'] ?>" size="35"
                                           maxlength="30"<?php if ($opc == 'V') echo 'readonly="readonly"'; ?>/></td>
                                <td>&nbsp;</td>
                            </tr>
                            <tr>
                                <td>&nbsp;</td>
                                <td>
                                    <div align="right">Titulo</div>
                                </td>
                                <td><label>
                                        <select name="cb_trat" id="cb_trat"
                                                style="width:65px"<?php if ($opc == 'V') echo 'disabled="disabled"'; ?>>
                                            <option value="Prof"<?php if ($pers['trat'] == 'Prof') echo "selected='selected'" ?>>
                                                Prof.
                                            </option>
                                            <option value="Lcd"<?php if ($pers['trat'] == 'Lcd') echo "selected='selected'" ?>>
                                                Lcd.
                                            </option>
                                            <option value="Abg"<?php if ($pers['trat'] == 'Abg') echo "selected='selected'" ?>>
                                                Abg.
                                            </option>
                                            <option value="Ing"<?php if ($pers['trat'] == 'Ing') echo "selected='selected'" ?>>
                                                Ing.
                                            </option>
                                        </select>
                                    </label></td>
                                <td>
                                    <div align="right">Estado Civil</div>
                                </td>
                                <td><select name="cb_civil" id="cb_civil"
                                            style="width:120px"<?php if ($opc == 'V') echo 'disabled="disabled"'; ?>>
                                        <option value="S"<?php if ($pers['civil'] == 'S') echo "selected='selected'" ?>>
                                            Soltero(a)
                                        </option>
                                        <option value="C"<?php if ($pers['civil'] == 'C') echo "selected='selected'" ?>>
                                            Casado(a)
                                        </option>
                                        <option value="D"<?php if ($pers['civil'] == 'D') echo "selected='selected'" ?>>
                                            Divorciado(a)
                                        </option>
                                        <option value="U"<?php if ($pers['civil'] == 'U') echo "selected='selected'" ?>>
                                            Union Libre
                                        </option>
                                        <option value="V"<?php if ($pers['civil'] == 'V') echo "selected='selected'" ?>>
                                            Viudo(a)
                                        </option>
                                    </select></td>
                                <td>&nbsp;</td>
                            </tr>
                            <tr>
                                <td>&nbsp;</td>
                                <td>
                                    <div align="right">Sexo</div>
                                </td>
                                <td><select name="cb_sexo" id="cb_sexo"
                                            style="width:120px"<?php if ($opc == 'V') echo 'disabled="disabled"'; ?>>
                                        <option value="M"<?php if ($pers['sex'] == 'M') echo "selected='selected'" ?>>
                                            Masculino
                                        </option>
                                        <option value="F"<?php if ($pers['sex'] == 'F') echo "selected='selected'" ?>>
                                            Femenino
                                        </option>
                                    </select></td>
                                <td>
                                    <div align="right">Email</div>
                                </td>
                                <td><input name="txt_email" type="text" class="email" id="txt_email"
                                           value="<?php echo $pers['email'] ?>" size="45"
                                           maxlength="50"<?php if ($opc == 'V') echo 'readonly="readonly"'; ?>/></td>
                                <td>&nbsp;</td>
                            </tr>
                            <tr>
                                <td>&nbsp;</td>
                                <td>
                                    <div align="right">F.Nacimiento</div>
                                </td>
                                <td><input name="txt_fech_nace" type="text" id="txt_fech_nace"
                                           value="<?php echo $pers['fnace'] ?>" size="7" class="fecha"
                                           readonly="readonly" <?php if ($opc == 'V') echo 'disabled="disabled"'; ?>/>
                                    AAAA-MM-DD
                                </td>
                                <td>
                                    <div align="right">Direcci&oacute;n</div>
                                </td>
                                <td><input name="txt_dir" type="text" id="txt_dir" value="<?php echo $pers['direc'] ?>"
                                           size="60"
                                           maxlength="50"<?php if ($opc == 'V') echo 'readonly="readonly"'; ?>/></td>
                                <td>&nbsp;</td>
                            </tr>
                            <tr>
                                <td>&nbsp;</td>
                                <td>
                                    <div align="right">Pa&iacute;s(*)</div>
                                </td>
                                <td><select name="cb_pais" id="cb_pais" style="width:270px"
                                            class="cblistmenu required cb" <?php if ($opc == 'V') echo 'disabled="disabled"'; ?>>
                                        <option value="">Seleccione</option>
                                        <?php
                                        require '../clases/cls_my_pais.php';
                                        $opais = new cls_my_pais;
                                        if ($opais->cb_pais()) $opais->combo($pers['pais']);
                                        ?>
                                    </select></td>
                                <td>
                                    <div align="right">Provincia(*)</div>
                                </td>
                                <td><select name="cb_provincia" id="cb_provincia" style="width:273px"
                                            class="cblistmenu required cb" <?php if ($opc == 'V') echo 'disabled="disabled"'; ?>>
                                        <option value="">Seleccione</option>
                                        <?php
                                        require '../clases/cls_my_provincia.php';
                                        $oprv = new cls_my_provincia;
                                        $oprv->relacion($opais->g_cb());
                                        $oprv->combo($pers['prv']);
                                        ?>
                                    </select></td>
                                <td>&nbsp;</td>
                            </tr>
                            <tr>
                                <td>&nbsp;</td>
                                <td>
                                    <div align="right">Ciudad(*)</div>
                                </td>
                                <td><select name="cb_ciudad" id="cb_ciudad" style="width:273px"
                                            class="required cb" <?php if ($opc == 'V') echo 'disabled="disabled"'; ?>>
                                        <option value="">Seleccione</option>
                                        <?php
                                        require '../clases/cls_my_ciudad.php';
                                        $ociu = new cls_my_ciudad;
                                        $ociu->relacion($oprv->g_cb());
                                        $ociu->combo($pers['ciu']);
                                        ?>
                                    </select></td>
                                <td>
                                    <div align="right">M&oacute;vil</div>
                                </td>
                                <td><input name="txt_mobil" type="text" id="txt_mobil"
                                           value="<?php echo $pers['mobil'] ?>" size="20"
                                           maxlength="10"<?php if ($opc == 'V') echo 'readonly="readonly"'; ?>
                                           onkeydown="javascript:if((event.keyCode>64 && event.keyCode<90)){return false;}"/>
                                </td>
                                <td>&nbsp;</td>
                            </tr>
                            <tr>
                                <td>&nbsp;</td>
                                <td>
                                    <div align="right">Tel&eacute;fono</div>
                                </td>
                                <td><input name="txt_telef" type="text" id="txt_telef"
                                           value="<?php echo $pers['telef'] ?>" size="20"
                                           maxlength="7"<?php if ($opc == 'V') echo 'readonly="readonly"'; ?>
                                           onkeydown="javascript:if((event.keyCode>64 && event.keyCode<90)){return false;}"/>
                                </td>
                                <td>
                                    <div align="right">Observaci&oacute;n</div>
                                </td>
                                <td rowspan="2"><label>
                                        <textarea name="txt_observ" cols="40" rows="2"
                                                  id="txt_observ" <?php if ($opc == 'V') echo 'readonly="readonly"'; ?>><?php echo $pers['observ'] ?> </textarea>
                                    </label></td>
                                <td>&nbsp;</td>
                            </tr>
                            <tr>
                                <td>&nbsp;</td>
                                <td>
                                    <div align="right">Estado</div>
                                </td>
                                <td><select name="cb_estado" id="cb_estado"
                                            style="width:100px"<?php if ($opc == 'V') echo 'disabled="disabled"'; ?>>
                                        <option value="A"<?php if ($pers['estd'] == 'A') echo "selected='selected'" ?>>
                                            Activo
                                        </option>
                                        <?php if (in_array($_SESSION['tipo'], $ArrTipUser)) { ?>
                                            <option value="I"<?php if ($pers['estd'] == 'I') echo "selected='selected'" ?>>
                                                Inactivo
                                            </option>
                                        <?php } ?>
                                    </select></td>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                            </tr>
                            <tr>
                                <td colspan="6">
                                    <div id="loading"></div>
                                </td>
                            </tr>
                            <tr>
                                <td colspan="6" class="bg_btn">
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
    <script type="text/javascript" src="../js/ajax_combo.js"></script>
<?php } ?>
</body>
</html>