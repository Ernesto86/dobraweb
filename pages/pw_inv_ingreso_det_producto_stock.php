<?php
require '../clases/cls_consulta.php';
$obj = new cls_consulta;
$bodega = strval($_POST['bodega']);
if (!$bodega) die('Error: Bodega ID no esta Presente.'); ?>
<!DOCTYPE html>
<html lang="es">
<head>
    <title>Título de la WEB</title>
    <meta charset="UTF-8">
    <title>Sistema MundoText</title>
    <link rel="stylesheet" type="text/css" href="../css/flotant_busc.css">
    <script language="javascript">

        var nav4 = window.Event ? true : false;

        $.ajaxSetup({
            'beforeSend': function (xhr) {
                if (xhr.overrideMimeType) {//Para Google Chrome y Firefox
                    xhr.overrideMimeType('text/html; charset=iso-8859-1');
                } else {
                    xhr.setRequestHeader('Content-type', 'text/html; charset=iso-8859-1');
                }
            }
        });

        $(document).ready(function () {

            $('#txt_cod_prod').focus();
            document.getElementById('cb_cod_producto').selectedIndex = 0;

            $('#cb_cod_producto').change(function (e) {

                $('#txt_cod_prod').val('');
                $('#txt_stock').val('');
                $('#cod_prod').val('');
                $('#txt_precio_pvp').val('');
                $('#costo_prod').val('');

                if ($(this).val().trim() == '') e.stopPropagation();

                try {

                    $.ajax({
                        url: 'pw_inv_det_producto_stock.php',
                        data: {producto: $(this).val(), bodega: $('#cb_bodega').val()},
                        type: "POST",
                        async: true,
                        cache: false,
                        dataType: 'json',
                        ifModified: false,
                        beforeSend: function () {
                            $('#loading_prod').addClass('loading');
                        },
                        success: function (resp) {
                            $('#loading_prod').removeClass('loading');

                            $('#txt_stock').val(resp.stock);
                            $('#cod_prod').val(resp.cod);
                            $('#txt_cod_prod').val(resp.cod);
                            $('#txt_precio_pvp').val(resp.pvp);
                            $('#costo_prod').val(resp.cost)
                        },
                        error: function () {
                            $('#loading_prod').removeClass('loading');
                            alert('Error al Intentar Conectar con el Servidor.');
                        }
                    });

                } catch (e) {
                    alert("Error: " + e.description);
                }
            });

            $('#cb_cod_producto').keypress(function () {
                if ($('#cb_cod_producto').val() != '') {
                    $('#txt_cantidad').focus();
                }
            });

            $('#txt_cantidad').keypress(function (e) {
                if (e.keyCode == 13 && $(this).val().trim() != '') {
                    $('#btn_add').click();
                }
            });

            $('#txt_cod_prod').keypress(function (e) {
                if (e.keyCode == 13) {
                    if ($(this).val().trim() == '') {
                        $('#cb_cod_producto').focus();
                        e.stopPropagation();
                    }
                    fn_id_producto($(this).val());
                }
            });

            $('#txt_cod_prod').blur(function () {
                if ($(this).val().trim() != '') {
                    fn_id_producto($(this).val());
                }
            });

            function fn_id_producto(cod) {
                try {

                    $.ajax({
                        url: 'pw_inv_json_idproducto.php',
                        data: {producto: cod},
                        type: "POST",
                        async: true,
                        cache: false,
                        dataType: 'json',
                        ifModified: false,
                        beforeSend: function () {
                            $('#loading_prod').addClass('loading');
                        },
                        success: function (resp) {
                            $('#loading_prod').removeClass('loading');

                            if (resp.err == '0') {
                                $('#txt_cod_prod').val('');
                                alert('No se encontro el Producto.');
                                return false;
                            }

                            document.getElementById('cb_cod_producto').selectedIndex = 0;
                            $('#cb_cod_producto').val(resp.id);

                            if (resp.id) {
                                $('#cb_cod_producto').change();
                                $('#txt_cantidad').focus();
                            }
                            return false;
                        },
                        error: function () {
                            $('#loading_prod').removeClass('loading');
                            alert('Error al Intentar Conectar con el Servidor.');
                        }
                    });

                } catch (e) {
                    alert("Error: " + e.description);
                }
            }

            $('#btn_add').click(function () {

                if ($('#cb_cod_producto').val().trim() == '') {
                    alert('Seleccione el Producto.');
                    $('#cb_cod_producto').focus();
                    return;
                }

                if ($('#idcliente').val().trim() == '') {
                    alert('No ha Ingresado el Cliente');
                    $('#txt_cli_codigo').focus();
                    return;
                }

                var cod_prod = $('#cod_prod').val().trim().toUpperCase();

                if (cod_prod == '') {
                    alert('El Codigo del Producto no esta Presente.');
                    return;
                }

                if (isNaN(parseFloat($('#txt_stock').val().trim()))) {
                    $('#txt_stock').val('');
                    return;
                }

                if (isNaN(parseFloat($('#txt_cantidad').val().trim()))) {
                    $('#txt_cantidad').val('');
                    return;
                }

                var stock = $('#txt_stock').val().trim();
                var cant = $('#txt_cantidad').val().trim();

                if (stock == '' || parseInt(stock) < 1) {
                    alert('No tiene Stock.');
                    return;
                }

                if (cant == '') {
                    alert('Ingrese la Cantidad.');
                    $('#txt_cantidad').focus();
                    return;
                }

                if (parseInt(cant) < 1) {
                    alert('Cantidad debe ser Mayor a Cero.');
                    $('#txt_cantidad').val('');
                    return;
                }

                if (parseInt(cant) > parseInt(stock)) {
                    alert('Cantidad no debe ser Mayor al Stock.');
                    $('#txt_cantidad').val('');
                    return;
                }

                $('#table_detalle_producto_stock #det tr').each(function (e) {

                    var codigo = $(this).children('td').eq(0).children('input.codigo').val().toUpperCase();

                    if (cod_prod == codigo) {

                        var value = $(this).children('td').eq(5).children('input.cant').val();

                        if (isNaN(parseFloat(value))) {
                            alert('Error en el Ingreso de Cantidad. no es Valida');
                            e.stopPropagation();
                        }

                        var suma = parseInt(cant) + parseInt(value);

                        if (suma > parseInt(stock)) {
                            alert('Cantidad Supera el Stock del Producto.');
                            $('#txt_cantidad').val('');
                            e.stopPropagation();
                        }

                        var _pvp = parseFloat($(this).children('td').eq(6).children('input.pvp').val());

                        if (suma && _pvp) {

                            $(this).children('td').eq(5).children('input.cant').val(suma);

                            $("#btn_autollenar").click();
                            $('#cb_cod_producto').focus();

                            var _total = decimals((suma * _pvp), 2);

                            $(this).children('td').eq(7).children('input.total').val(_total);

                            fn_suma_total();
                        }
                        $(".cerrar").click();
                        //$('#cb_cod_producto').focus();
                        e.stopPropagation();
                    }
                });

                fn_agregar_filas();
                //fn_suma_total();
                $(".cerrar").click()

            });
        });

        function IsNumber(evt) {
            var key = nav4 ? evt.which : evt.keyCode;
            return (key <= 13 || (key >= 48 && key <= 57) || key == 46);
        }
    </script>
</head>
<body>
<table width="100%" border="0" cellpadding="0" cellspacing="0" id="table_cont_busq">
    <tr>
        <th> Detalle de Producto.</th>
    </tr>
    <tr>
        <td>
            <div id="cont_parametros" class="bg_grise">
                <table border="0" align="center" cellpadding="0" cellspacing="0">
                    <tr>
                        <td><input type="hidden" name="cod_prod" id="cod_prod" value=""/></td>
                        <td>Cod.</td>
                        <td>Descripcion - Producto</td>
                        <td><input type="hidden" name="costo_prod" id="costo_prod" value=""/></td>
                        <td>Stock</td>
                        <td>Cant.</td>
                        <td>PVP</td>
                        <td>&nbsp;</td>
                    </tr>
                    <tr>
                        <td></td>
                        <td><input name="txt_cod_prod" type="text" id="txt_cod_prod" size="14" class="cantidades"
                                   align="middle"/></td>
                        <td><select name="cb_cod_producto" id="cb_cod_producto" style="width:270px" class="negrita">
                                <option value="" selected="selected">Seleccione Producto.</option>
                                <?php
                                $obj->s_bodega($bodega);
                                if ($obj->fn_inv_buscar_producto()) $obj->combo();
                                ?>
                            </select></td>
                        <td>
                            <div id="loading_prod"></div>
                        </td>
                        <td><input name="txt_stock" type="text" class="cantidades" id="txt_stock" size="4" maxlength="4"
                                   readonly="" align="middle"/></td>
                        <td><input name="txt_cantidad" type="text" class="cantidades" id="txt_cantidad"
                                   onkeypress="return IsNumber(event);" value="1" size="3" maxlength="4"
                                   align="middle"/></td>
                        <td><input name="txt_precio_pvp" type="text" class="cantidades" id="txt_precio_pvp"
                                   onkeypress="return IsNumber(event);" value="0.00" size="4" maxlength="4"
                                   align="middle"/></td>
                        <td><input name="btn_add" type="submit" class="green" id="btn_add" value="Agregar"
                                   title="Agregar Registro."/></td>
                    </tr>
                    <tr>
                        <td>&nbsp;</td>
                        <td>&nbsp;</td>
                        <td>&nbsp;</td>
                        <td>&nbsp;</td>
                        <td>&nbsp;</td>
                        <td>&nbsp;</td>
                        <td>&nbsp;</td>
                        <td>&nbsp;</td>
                    </tr>
                </table>
            </div>
        </td>
    </tr>
</table>
</body>
</html>