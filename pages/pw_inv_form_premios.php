<?php session_start();
if (!isset($_SESSION['us_id']) or !isset($_SESSION['us_cuenta']) or !isset($_SESSION['us_idtipo'])) {
    die('Inicie Session');
} ?>
<!DOCTYPE html>
<html lang="es">
<head>
    <title>Título de la WEB</title>
    <meta charset="UTF-8">
    <title>Sistema MundoText</title>
    <link rel="stylesheet" type="text/css" href="../css/formularios.css">
    <?php
    require '../clases/cls_my_transaccion.php';
    $otrans = new cls_my_transaccion;
    $otrans->transacciones($_GET['id']);
    $trans = $otrans->campos();

    if (!$trans['ing'])
        die('<div class="error">No cuenta con Permisos.</div>');

    if ($_SESSION['us_tipuser'] != 'administrador' and $_SESSION['us_tipuser'] != 'semiadministrador') {

        if (!$otrans->fn_usuario_caja_bodega()) {
            die('<div class="error">No cuenta con Permisos.</div>');
        }
        $cmp = $otrans->campos();
        unset($otrans);

        $bodega_id = trim($cmp['bod']);

        if (!$bodega_id) die('<div class="error">No se Encontro Bodega Predeterminada.</div>');

    } ?>
    <link rel="stylesheet" href="../css/jquery.ui.all.css">
    <script src="../js/jquery-1.5.2.min.js"></script>
    <script src="../js/jquery.ui.core.js"></script>
    <script src="../js/jquery.ui.datepicker.js"></script>
    <script src="../js/jquery.ui.datepicker-es.js"></script>
    <script src="../js/shortcut.js"></script>
    <script language="javascript">

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

            $('#txt_cli_codigo').focus();
            shortcut.add("F1", function () {
                $("#btn_autollenar").click();
            });
            shortcut.add("Ctrl+b", function () {
                $('#btn_cli_buscar').click();
            });
            shortcut.add("Ctrl+g", function () {
                $("#btn_guardar").click();
            });
            shortcut.add("Esc", function () {
                $(".cerrar").click();
            });
            shortcut.add("F2", function () {
                $("#btn_agregar").click();
            });
            shortcut.add("F3", function () {
                $("#btn_quitar").click();
            });

            $('#cb_bodega').keypress(function (e) {
                if (e.keyCode == 13 && $(this).val().trim() != '') {
                    $('#txt_cli_codigo').focus();
                }
            });

            $('#cb_bodega').change(function () {
                $(".cerrar").click();
                var id = $(this).val();
                $('#id_bodega').val(id);

                var count = $('#table_detalle_producto_stock #det tr').length;
                if (count) {
                    $('#table_detalle_producto_stock #det').html('');
                    $('#txt_cli_codigo').val('');
                }
            });

            $('#btn_cli_buscar').click(function () {
                $("#cont_buscar").html('');
                $(".float-content").show();
                $("#cont_buscar").load('pw_buscar_cliente.php');
            });

            $(".cerrar").click(function () {
                $("#cont_buscar").html('');
                $(".float-content").hide("fat");
            });

            $("#btn_autollenar").click(function () {

                if ($('#idcliente').val().trim() == '') {
                    alert('No ha Ingresado Cliente ID Cliente no esta Presente.');
                    $('#txt_cli_codigo').focus();
                    return;
                }

                if ($('#id_bodega').val().trim() == '') {
                    alert('No Se Encontro Id de Bedega. Vuelva a Intentarlo.');
                    return;
                }

                var cod = $('#txt_cli_codigo').val();

                if (!cod) return;

                var strbod = $('#cb_bodega option:selected').text().split('::');
                var str = 'Premio-' + cod.toUpperCase() + ' ' + strbod[0];

                /*$('#table_detalle_producto_stock #det tr').each(function(){
                 var codigo = $(this).children('td').eq(0).children('input.codigo').val().toUpperCase();
                 var value  = $(this).children('td').eq(5).children('input.cant').val();
                 if(isNaN(parseFloat(value))){
                 alert('Error en el Ingreso de Cantidad. Valor no Valido');
                 stopPropagation();
                 }
                 str +='Prd:'+codigo+' Cnt:'+value;
                 });*/
                $('#txt_nota').focus();
                $('#txt_nota').val(str);
            });

            $("#btn_guardar").click(function () {

                if ($('#idcliente').val().trim() == '') {
                    alert('No ha Ingresado Cliente ID Cliente no esta Presente.');
                    $('#txt_cli_codigo').focus();
                    return;
                }

                if ($('#id_bodega').val().trim() == '') {
                    alert('No Se Encontro Id de Bedega. Vuelva a Intentarlo.');
                    return;
                }

                if ($('#txt_tipo').val().trim() == '') {
                    alert('No Se Encontro Tipo de Documento.');
                    $('#txt_tipo').focus();
                    return;
                }

                if ($('#cb_bodega').val().trim() == '') {
                    alert('Seleccione la Bodega');
                    $('#cb_bodega').focus();
                    return;
                }

                if ($('#txt_nota').val().trim() == '') {
                    $("#btn_autollenar").click();
                }

                var resp = confirm('Confirma Guardar.');

                if (!resp) return;

                try {

                    $.ajax({
                        url: 'pw_guardar_premio_producto_cliente.php',
                        data: $('#form_transaccion .datos').serialize(),
                        type: "POST",
                        async: true,
                        cache: false,
                        dataType: 'json',
                        ifModified: false,
                        beforeSend: function () {
                            $('#load_img').addClass('loading');
                            $('#btn_guardar').attr("disabled", true);
                        },
                        success: function (resp) {
                            $('#load_img').removeClass('loading');
                            $('#btn_guardar').attr("disabled", false);

                            if (resp.rp == '1') {
                                alert('Guardado Correctamente. Documento N°: ' + resp.doc);
                                location.reload();
                                return false;
                            }

                            switch (resp.err) {

                                case'2':
                                    alert('Error: La Session a Finalizado.');
                                    break;

                                case'3':
                                    alert('Error: Parametros no Validos o estan Vacios.');
                                    break;

                                case'4':
                                    alert('Error: La Cantidad debe ser mayor a Cero.');
                                    break;

                                case'5':
                                    alert('Error: EL Producto:' + resp.cod + ' No tiene Stock. Vuelva a Intentarlo.');
                                    break;

                                case'6':
                                    alert('Error: No se Pudo Crear los ID de Ingreso. Contadores');

                            }
                            return false;
                        },
                        error: function () {

                            $('#load_img').removeClass('loading');
                            $('#btn_guardar').attr("disabled", false);
                            alert('Error al Intentar Guardar el Registro.');

                        }
                    });
                } catch (e) {
                    alert("Error: " + e.description);
                }
            });

            $("#btn_quitar").click(function () {
                $('#table_detalle_producto_stock #det tr:last').remove();
                $('#txt_nota').val('');
            });

            $("#btn_agregar").click(function () {

                if ($('#cb_bodega').val().trim() == '') {
                    alert('Seleccione la Bodega.');
                    $('#cb_bodega').focus();
                    return;
                }
                if ($('#id_bodega').val().trim() == '') {
                    alert('No Se Encontro Id de Bedega. Vuelva a Intentarlo.');
                    return;
                }

                var cod = $('#txt_cli_codigo').val();
                var cli = $('#txt_cli_nombre').val();
                var idclient = $('#idcliente').val();

                if (cod && cli && idclient) {
                    $("#cont_buscar").html('');
                    $(".float-content").css('width', '750px');
                    $(".float-content").show();
                    $("#cont_buscar").load('pw_inv_ingreso_det_producto_stock.php', {bodega: $('#cb_bodega').val()});
                }
            });

            $('#table_detalle_producto_stock #det').delegate(':input:text', 'keypress', function (e) {

                if (e.keyCode == 13) {
                    e.preventDefault();
                    if ($(this).val().length < 1 || $(this).val().trim() == '') {
                        alert('Ingrese Cantidad');
                        $(this).focus();
                        e.stopPropagation();
                    }

                    var value = parseFloat($(this).val());

                    if (value < 1) {
                        alert('El valor ingresado debe ser mayor a Cero');
                        $(this).val('');
                        $(this).focus();
                        e.stopPropagation();
                    }
                    fn_validar_stock();
                    e.stopPropagation();

                }
                if (e.keyCode == 8) {
                    return;
                }
                if (e.which && (e.which < 48 || e.which > 57) && e.keyCode != 8) {
                    e.preventDefault();
                }
            });

            $('#table_detalle_producto_stock #det').delegate(':input:text', 'blur', function (e) {

                if ($(this).val().length < 1 || $(this).val().trim() == '') {
                    e.stopPropagation();
                }
                var value = parseFloat($(this).val());
                if (value < 1) {
                    alert('El valor ingresado debe ser mayor a Cero');
                    $(this).val('');
                    $(this).focus();
                    e.preventDefault();
                    e.stopPropagation();
                }
                fn_validar_stock();
                e.stopPropagation();
            });
        });

        function fn_select_cod(codigo) {
            $('#txt_cli_codigo').val(codigo);
            $(".float-content").hide("slow");
            $('#txt_cli_codigo').focus();
        }

        function fn_buscar_cliente() {

            $('#txt_nota').val('');
            if ($('#txt_cli_codigo').val().length < 1 || $('#txt_cli_codigo').val().trim() == '') {
                alert('Ingrese el Código del Cliente.');
                $('#txt_cli_codigo').val('');
                $('#txt_cli_codigo').focus();
                return;
            }

            $('#idcliente').val('');
            $('#txt_cli_nombre').val('');
            $('#txt_vend_codigo').val('');
            $('#txt_vend_nombre').val('');
            $('#txt_nota').val('');

            try {

                $.ajax({
                    url: 'pw_cli_datos_cliente.php',
                    data: {cod: $('#txt_cli_codigo').val(), sald: true},
                    type: "POST",
                    async: true,
                    cache: false,
                    dataType: 'json',
                    ifModified: false,
                    beforeSend: function () {
                        $('#load_img').addClass('loading');
                        $('#btn_cli_enviar').attr("disabled", true);
                    },
                    success: function (resp) {

                        $('#load_img').removeClass('loading');
                        $('#btn_cli_enviar').attr("disabled", false);

                        if (resp.err == '0') {
                            alert('Error: Parametros no Validos');
                            return false;
                        }

                        var idcli = resp.id.toString();
                        if (!idcli) {
                            alert('Error: No se Encontro Cliente.');
                            return false;
                        }

                        $('#idcliente').val(idcli);
                        $('#txt_cli_nombre').val(resp.cli);
                        $('#txt_vend_codigo').val(resp.cod);
                        $('#txt_vend_nombre').val(resp.vend);
                        $('#txt_saldo').val(resp.sald);

                        $("#btn_agregar").click();
                        return false;
                    },
                    error: function () {
                        $('#load_img').removeClass('loading');
                        $('#btn_cli_enviar').attr("disabled", false);
                        alert('Fallo Conectando con el servidor');
                    }
                });

            } catch (e) {
                alert("Error: " + e.description);
            }
        }
        function fn_agregar_filas() {

            if ($('#id_bodega').val().trim() == '') {
                alert('No Se Encontro Id de Bedega. Vuelva a Intentarlo.');
                return;
            }
            var count = ($('#table_detalle_producto_stock #det tr').length + 1);

            if (count > 10) return;

            var cls = '';
            if (count % 2 == 0) cls = ' class="bg_altercolor"';

            var bodega = $('#cb_bodega option:selected').text();
            var cod = $('#cod_prod').val();
            var idproducto = $('#cb_cod_producto').val();
            var producto = $('#cb_cod_producto option:selected').text();
            var stock = parseInt($('#txt_stock').val());
            var cant = parseInt($('#txt_cantidad').val());
            var pvp = $('#txt_precio_pvp').val();

            var str = '<tr' + cls + '>';
            str += '<td align="center">' + count + '<input type="hidden" name="prod_id[]" class="datos" value="' + idproducto + '"/>';
            str += '<input type="hidden" name="prod_cod[]" class="codigo" value="' + cod + '"/></td>';
            str += '<td align="center"><strong>' + cod + '</strong></td><td align="center">';
            str += producto + '</td><td align="center">' + bodega + '</td><td align="center">';
            str += '<input type="text" name="prod_stock[]" size="6" maxlength="6" class="stock" value="' + stock + '" readonly=""/></td><td align="center">';
            str += '<input type="text" name="prod_cant[]" size="6" maxlength="6" class="datos cant"  value="' + cant + '"/></td>';
            str += '<td align="center"><input type="text" name="prod_pvp[]" size="6" maxlength="6" class="datos pvp" value="' + pvp + '" readonly=""/></td>';
            str += '</tr>';

            $('#table_detalle_producto_stock #det').append(str);
            $("#btn_autollenar").click();
            $(".cerrar").click();
            $('#txt_nota').focus();

        }

        function fn_validar_stock() {

            $('#table_detalle_producto_stock #det tr').each(function (e) {

                var stock_ = parseInt($(this).children('td').eq(4).children('input.stock').val());
                var cant_ = parseInt($(this).children('td').eq(5).children('input.cant').val());

                if (isNaN(parseFloat(cant_)) || isNaN(parseFloat(stock_))) {
                    alert('La Cantidad o Stock no son Validos');
                    $(this).children('td').eq(5).children('input.cant').val('');
                    e.stopPropagation();
                }
                if (cant_ > stock_) {
                    alert('Cantidad no debe ser Mayor al Stock.');
                    $(this).children('td').eq(5).children('input.cant').val('');
                    e.stopPropagation();
                }
            });

            $("#btn_autollenar").click();
        }
    </script>
</head>
<body>
<div id="buscar">
    <div id="title_form"><img src="../images/icon/list-view.png"/> <?php echo $_GET['tit']; ?></div>
    <div id="btns_accion">
        <div style="display:none">
            <input type="hidden" name="id_menu" id="id_menu" value="<?php echo intval($_GET['id']); ?>"/>
            <input type="hidden" name="dt_menu" id="dt_menu" value="<?php echo $pag; ?>"/>
        </div>
        <table border="0" cellpadding="0" cellspacing="0">
            <tr>
                <td><input name="btn_nuevo2" type="button" class="btn_nuevo" id="btn_nuevo2" value="Nuevo"
                           title="Nuevo Registros" onclick="location.reload();"/></td>
                <td><input name="btn_guardar" type="button" id="btn_guardar" value="Guardar" class="btn_guardar"
                           title="Ctrl+G: Guardar"/></td>
                <td><input name="btn_salir" type="button" class="btn_salir" id="btn_salir" value="Salir"
                           title="Cerrar Ventana" onclick="location='digital-clock/index.html'"/></td>
            </tr>
        </table>
    </div>
</div>
<div id="contenedor" align="center">
    <div id="form_transaccion">
        <table width="100%" border="0" cellspacing="0" cellpadding="0" id="table_contenedor">
            <tr>
                <td class="bg_cab_title"><img src="../images/icon/Grid.png"/> Datos del Cliente.</td>
            </tr>
            <tr>
                <td>
                    <table border="0" cellpadding="0" cellspacing="0" class="styletable">
                        <tr>
                            <td></td>
                            <td>Estado</td>
                            <td>&nbsp;</td>
                            <td>N&uacute;mero</td>
                            <td>Asiento</td>
                            <td>Fecha</td>
                            <td>Tipo</td>
                            <td colspan="2">Bodega</td>
                            <td rowspan="4" width="170px">
                                <div align="center">
                                    <img src="../images/logo.png" width="90" height="81"/>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td>&nbsp;</td>
                            <td><input name="txt_cli_estado" type="text" id="txt_cli_estado" size="12" maxlength="10"
                                       readonly=""/></td>
                            <td>&nbsp;</td>
                            <td><input name="txt_cli_numero" type="text" id="txt_cli_numero" size="10" maxlength="10"
                                       readonly=""/></td>
                            <td><input name="txt_cli_asiento" type="text" id="txt_cli_asiento" size="10" maxlength="10"
                                       readonly=""/></td>
                            <td><input name="txt_ing_fecha" type="text" id="txt_ing_fecha" size="10" maxlength="10"
                                       value="<?php echo $_SESSION['us_fecha'] ? $_SESSION['us_fecha'] : date("d/m/Y"); ?>"
                                       class="fecha datos" readonly="readonly"/></td>
                            <td><input name="txt_tipo" type="text" id="txt_tipo" value="VEN-NV" size="10" maxlength="10"
                                       readonly="" class="datos"/></td>
                            <td colspan="2"><select name="cb_bodega" id="cb_bodega" style="width:270px"
                                                    class="datos" <?php if ($_SESSION['us_tipuser'] != 'administrador' and $_SESSION['us_tipuser'] != 'semiadministrador') { ?> disabled="disabled"<?php } ?>>
                                    <option value="" selected="selected">Seleccione</option>
                                    <?php
                                    require '../clases/cls_consulta.php';
                                    $obj = new cls_consulta;
                                    if ($obj->fn_inv_bodega()) $obj->combo($bodega_id); ?>
                                </select>
                                <input type="hidden" name="id_bodega" id="id_bodega" value="<?php echo $bodega_id; ?>"
                                       class="datos"/>
                            </td>
                        </tr>
                        <tr>
                            <td align="right">Cliente</td>
                            <td>
                                <form id="form_trans" name="form_trans" method="post"
                                      action="javascript:fn_buscar_cliente();">
                                    <input type="hidden" name="idcliente" id="idcliente" value="" class="datos"/>
                                    <input name="txt_cli_codigo" type="text" id="txt_cli_codigo" size="10"
                                           maxlength="10" class="txt-codigo"/>
                                </form>
                            </td>
                            <td><input name="btn_cli_enviar" type="button" class="btn_enviar_cli_cod"
                                       id="btn_cli_enviar" title="Enviar Codigo Cliente" value=" "
                                       onclick="fn_buscar_cliente();"/></td>
                            <td colspan="4"><input name="txt_cli_nombre" type="text" id="txt_cli_nombre" size="46"
                                                   maxlength="45" readonly="readonly" class="datos negrita"/>
                                <input name="btn_cli_buscar" type="button" class="btnbuscar" id="btn_cli_buscar"
                                       title="Buscar Clientes Ctrl+B" value="..."/></td>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                        </tr>
                        <tr>
                            <td align="right">Vendedor</td>
                            <td><input name="txt_vend_codigo" type="text" id="txt_vend_codigo" size="10" maxlength="10"
                                       readonly="" class="negrita"/></td>
                            <td align="center">
                                <div id="load_img"></div>
                            </td>
                            <td colspan="4"><input name="txt_vend_nombre" type="text" id="txt_vend_nombre" size="46"
                                                   maxlength="45" readonly="" class="negrita"/></td>
                            <td colspan="2">&nbsp;</td>
                        </tr>
                    </table>
                </td>
            </tr>
            <tr>
                <td class="bg_cab_title"><img src="../images/icon/Grid.png"/> Detalle de Productos.</td>
            </tr>
            <tr>
                <td>
                    <table width="100%" border="0" cellspacing="0" cellpadding="0">
                        <tr>
                            <td>
                                <div class="inv_tabledetalle">
                                    <table border="0" cellpadding="0" cellspacing="0" class="styletable_detalle"
                                           id="table_detalle_producto_stock">
                                        <thead>
                                        <tr>
                                            <th width="10px">N#</th>
                                            <th width="80px">C&oacute;digo</th>
                                            <th width="200px">Nombre Producto</th>
                                            <th width="80px">Bodega</th>
                                            <th width="28px">Stock</th>
                                            <th width="28px">Cant.</th>
                                            <th width="28px">PVP</th>
                                        </tr>
                                        </thead>
                                        <tbody id="det">
                                        </tbody>
                                    </table>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <table border="0" cellspacing="0" cellpadding="0" class="styletable">
                                    <tr>
                                        <td><input name="btn_agregar" type="submit" class="green" id="btn_agregar"
                                                   value="F2: Nuevo &gt;&gt;"/></td>
                                        <td><input name="btn_quitar" type="submit" class="btn" id="btn_quitar"
                                                   value="F3: Quitar &gt;&gt;"/></td>
                                    </tr>
                                </table>
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
            <tr>
                <td>
                    <table border="0" align="right" cellpadding="0" cellspacing="0" id="table_det_totales">
                        <tr>
                            <th align="center" width="120">SUBTOTAL</th>
                            <th align="center" width="120">DESCUENTO</th>
                            <th align="center" width="120">IMPUESTO</th>
                            <th align="center" width="120">SALDO</th>
                        </tr>
                        <tr>
                            <td align="center"><input name="txt_subtotal" type="text" class="totales" id="txt_subtotal"
                                                      value="0.00" size="8" readonly="readonly"/></td>
                            <td align="center"><input name="textfield2" type="text" id="textfield2" value="0.00"
                                                      size="8" readonly="readonly"/></td>
                            <td align="center"><input name="textfield3" type="text" id="textfield3" value="0.00"
                                                      size="8" readonly="readonly"/></td>
                            <td align="center"><input name="txt_saldo" type="text" id="txt_saldo" value="0.00" size="8"
                                                      readonly="readonly"/></td>
                        </tr>
                    </table>
                </td>
            </tr>
            <tr>
                <td class="bg_cab_title"><img src="../images/icon/Grid.png"/> Detalle de Nota.</td>
            </tr>
            <tr>
                <td>&nbsp;</td>
            </tr>
            <tr>
                <td>
                    <div class="relleno">
                        <input name="btn_autollenar" type="button" class="btn" id="btn_autollenar"
                               value="F1: Autollenar &gt;&gt;"/>
                    </div>

                </td>
            </tr>
            <tr>
                <td>
                    <div class="relleno">
                        <input name="txt_nota" type="text" class="datos" id="txt_nota" value="" size="120"
                               maxlength="70"/>
                    </div>
                </td>
            </tr>
        </table>
    </div>
</div>
<div class="float-content">
    <div class="cerrar"><a href="#" title="Cerrar"></a></div>
    <div id="cont_buscar"></div>
</div>
</body>
</html>
