<?php
session_start();
if (!isset($_SESSION['us_id']) or !isset($_SESSION['us_cuenta']) or !isset($_SESSION['us_idtipo'])) {
    die('Inicie Session');
} ?>
<!DOCTYPE html>
<html lang="es">
<head>
    <title>Factura de Venta</title>
    <meta charset="UTF-8">
    <title>Sistema MundoText</title>
    <link rel="stylesheet" href="../static/css/easy-autocomplete.min.css">
    <link rel="stylesheet" type="text/css" href="../css/formularios.css">
    <link rel="stylesheet" href="../static/dist/datepicker.min.css">
    <link rel="stylesheet" type="text/css" href="../css/loader.css"/>
    <script src="../js/loader.js"></script>
    <?php
    require '../app/setting.php';
    define('MOD', DROOT . 'inventario/');
    require MOD . 'control/CtrBodega.php';
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

        $bodegaid = trim($cmp['bodegaid']);
        $cajaid = trim($cmp['cajaid']);
        $divisionid = trim($cmp['divisionid']);

        if ($bodegaid == '' or $divisionid == '' or $cajaid == '')
            die('<div class="error">No se Encontró Bodega Predeterminada.</div>');

    }
    $ctrBodega = new CtrBodega();
    ?>
</head>
<body>
<div id="load-content" class="loader-wrapper">
    <div id="id-loading" class="loader-small"></div>
</div>
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
        <input type="hidden" id="id-divisionid" value="<?= $divisionid ?>">
        <input type="hidden" id="id-cajaid" value="<?= $cajaid ?>">

        <table width="100%" border="0" cellspacing="0" cellpadding="0" id="table_contenedor">
            <tr>
                <td class="bg_cab_title"><img src="../images/icon/Grid.png"/> Datos del Cliente.</td>
            </tr>
            <tr>
                <td align="center">
                    <table border="0" cellpadding="0" cellspacing="0" class="styletable">
                        <tr>
                            <td></td>
                            <td>Tipo</td>
                            <td>&nbsp;</td>
                            <td>N&uacute;mero</td>
                            <td>Asiento</td>
                            <td>Fecha</td>
                            <td>Tipo Doc.</td>
                            <td colspan="2">Bodega</td>
                        </tr>
                        <tr>
                            <td>&nbsp;</td>
                            <td>
                                <select id="id-contado" style="width: 100%">
                                    <option value="0">CREDITO</option>
                                    <option value="1">CONTADO</option>
                                </select>
                            </td>
                            <td>&nbsp;</td>
                            <td>
                                <input name="txt_cli_numero" type="text" id="txt_cli_numero" size="10" maxlength="10"
                                       readonly>
                            </td>
                            <td>
                                <input name="txt_cli_asiento" type="text" id="txt_cli_asiento" size="10" maxlength="10"
                                       readonly>
                            </td>
                            <td>
                                <input name="fac_fecha" type="text" id="fac_fecha" size="10" maxlength="10"
                                       value="<?php echo $_SESSION['us_fecha'] ? $_SESSION['us_fecha'] : date("d/m/Y"); ?>"
                                       data-toggle="datepicker" readonly>
                            </td>
                            <td>
                                <input name="txt_tipo" type="text" id="txt_tipo" value="VEN-FA" size="13" maxlength="10"
                                       readonly></td>
                            <td colspan="2">
                                <select name="cb_bodega" id="cb_bodega" style="width: 17rem;"
                                        class="datos negrita" <?php if ($_SESSION['us_tipuser'] != 'administrador' and $_SESSION['us_tipuser'] != 'semiadministrador') { ?> disabled="disabled"<?php } ?>>
                                    <option value="">Seleccione</option>
                                    <?php foreach ($ctrBodega->getLista() as $b): ?>
                                        <option value="<?= $b->id ?>" <?php if ($b->id == $bodegaid): ?> selected="selected" <?php endif; ?>><?= $b->nombre ?></option>
                                    <?php endforeach; ?>
                                </select>
                                <input type="hidden" name="id_bodega" id="id_bodega" value="<?= $bodegaid ?>">

                            </td>
                        </tr>
                        <tr>
                            <td align="right">Cliente</td>
                            <td>
                                <form id="id-form-buscar-cli">
                                    <input type="hidden" name="action" value="cliente">
                                    <input name="cli_codigo" type="text" id="cli_codigo" size="10"
                                           maxlength="10" class="txt-codigo datos" required autofocus
                                           placeholder="Código">
                                </form>
                            </td>
                            <td>
                                <input name="btn_cli_enviar" type="button" class="btn_enviar_cli_cod"
                                       id="btn_cli_enviar" title="Enviar Codigo Cliente" value=" "
                                       onclick="$('#id-form-buscar-cli').submit();">
                            </td>
                            <td colspan="4">
                                <input name="txt_cli_nombre" type="text" id="txt_cli_nombre"
                                       size="62" maxlength="60" class="datos negrita" placeholder="Nombres del Cliente">
                            </td>
                            <td>
                                <input name="cli_cedula" type="text" id="cli_cedula"
                                       size="14" maxlength="14" class="negrita" placeholder="Ruc" readonly>

                            </td>
                            <td>
                                <input type="text" id="id-zona" size="12" maxlength="12" class="negrita"
                                       placeholder="Zona" readonly>
                            </td>
                        </tr>
                        <tr>
                            <td align="right">Vendedor</td>
                            <td>
                                <input name="txt_vend_codigo" type="text" id="txt_vend_codigo" size="10"
                                       maxlength="10" readonly="" class="negrita" placeholder="Código">
                            </td>
                            <td align="center">
                                <div id="load_img"></div>
                            </td>
                            <td colspan="4">
                                <input name="txt_vend_nombre" type="text" id="txt_vend_nombre"
                                       size="62" maxlength="58" readonly="" class="negrita"
                                       placeholder="Nombres del Vendedor">
                            </td>
                            <td>
                                <input type="text" id="id-clase" placeholder="Clase" maxlength="14" size="14"
                                       class="negrita" readonly> &nbsp;
                            </td>
                            <td>
                                <input type="text" id="id-cupo" placeholder="Cupo" maxlength="12" size="12"
                                       class="negrita" readonly> &nbsp;
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
            <tr>
                <td class="bg_cab_title">
                    <img src="../images/icon/Grid.png"/> Seleccione Producto.
                </td>
            </tr>
            <tr>
                <td align="center">
                    <div style="margin: 4px">
                        <table border="0" cellpadding="0" cellspacing="0" class="styletable">
                            <tr>
                                <td>Cod.</td>
                                <td>
                                    <form id="id-form-buscar-prod">
                                        <input type="text" id="id-prod-codigo" placeholder="Codigo" style="width: 7rem"
                                               required>
                                        <input type="hidden" id="id-producto-id">
                                    </form>
                                </td>
                                <td>Desc.</td>
                                <td>
                                    <input type="text" id="id-prod-descripcion" placeholder="Descripcion de Producto"
                                           style="width: 24rem">
                                </td>
                                <td>Stock</td>
                                <td>
                                    <input type="number" id="id-prod-stock" placeholder="Stock" readonly
                                           style="width: 5rem">
                                </td>
                                <td>Cant.</td>
                                <td>
                                    <input type="number" id="id-prod-cantidad" placeholder="Cant." style="width: 4rem"
                                           min="1" max="100" required>
                                </td>
                                <td>PVP.</td>
                                <td>
                                    <input type="number" id="id-prod-pvp" placeholder="PVP." style="width: 5rem">
                                </td>
                                <td>
                                    <button type="button" id="id-btn-agregar" class="green">Agregar</button>
                                </td>
                            </tr>
                        </table>
                    </div>

                </td>
            </tr>

            <tr>
                <td class="bg_cab_title"><img src="../images/icon/Grid.png"/> Detalle de la Factura.</td>
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
                                            <th width="8px">N#</th>
                                            <th width="50px">C&oacute;digo</th>
                                            <th width="190px">Descripción del producto</th>
                                            <th width="70px">Bodega</th>
                                            <th width="28px">Stock</th>
                                            <th width="28px">Emp.</th>
                                            <th width="28px">Cant.</th>
                                            <th width="28px">PVP</th>
                                            <th width="28px">Iva.</th>
                                            <th width="30px">Total</th>
                                            <th width="5px">..</th>
                                        </tr>
                                        </thead>
                                        <tbody id="det">
                                        </tbody>
                                    </table>
                                </div>
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
            <tr>
                <td>
                    <div>
                        <table border="0" align="right" cellpadding="0" cellspacing="0" id="table_det_totales">
                            <tr>
                                <th align="center" width="120">SUBTOTAL</th>
                                <th align="center" width="120">DESCUENTO</th>
                                <th align="center" width="120">IMPUESTO</th>
                                <th align="center" width="120">TOTAL A PAGAR</th>
                            </tr>
                            <tr>
                                <td align="center"><input name="txt_subtotal" type="text" class="datos"
                                                          id="txt_subtotal" value="0.00" size="8" readonly="readonly"/>
                                </td>
                                <td align="center"><input name="txt_descuento" type="text" id="txt_descuento"
                                                          value="0.00" size="8" readonly="readonly"/></td>
                                <td align="center"><input name="txt_impuesto" type="text" id="txt_impuesto" value="0.00"
                                                          size="8" readonly="readonly"/></td>
                                <td align="center"><input name="txt_total_pagar" type="text" id="txt_total_pagar"
                                                          value="0.00" size="8" readonly="readonly" class="datos"/></td>
                            </tr>
                        </table>
                    </div>
                </td>
            </tr>
            <tr>
                <td class="bg_cab_title"><img src="../images/icon/Grid.png"/> Detalle de Nota.</td>
            </tr>
            <tr>
                <td>
                    <div class="relleno"></div>
                </td>
            </tr>
            <tr>
                <td>
                    <div class="relleno">
                        <input name="txt_nota" type="text" class="datos" id="txt_nota" value="" size="140"
                               maxlength="70"/>
                    </div>
                </td>
            </tr>
        </table>
    </div>
</div>

<script id="js-template-det-factura" type="text/x-jsrender">
{{* window.x = 1}}
{{for items}}
   <tr>
        <td align="center">{{*: x++}}</td>
        <td align="center"><b>{{:cod}}</b></td>
        <td>{{:producto}}</td>
        <td align="center">{{:bodega}}</td>
        <td align="center">{{:stock}}</td>
        <td align="center">{{:empaque}}</td>
        <td align="center">
           <input type="number" value="{{:cantidad}}" data-json='{"id":"{{:productoid}}","stock":"{{:stock}}"}'  class="cantidad" min="1" max="100">
        </td>
        <td align="right"><b>$ {{:precio.toFixed(2)}}<b></td>
        <td align="center">
          {{if coniva == 1}}
           <img title="Eliminar Item" src="../images/icon/tick.png">
          {{/if}}
        </td>
        <td align="right"><b>$ {{:total.toFixed(2)}}</b></td>
        <td align="center">
            <a href="javascript:venta.eliminar('{{:productoid}}')">
                <img title="Eliminar Item" src="../images/accion/eliminar.png">
            </a>
        </td>
    </tr>
  {{/for}}







</script>

<script src="../static/lib/jquery.min2.0.js"></script>
<script src="../static/dist/datepicker.min.js"></script>
<script src="../static/dist/datepicker.es-ES.js"></script>

<script src="../static/lib/jquery.easy-autocomplete.min.js"></script>
<script src="../static/lib/jsrender.min.js"></script>
<script src="../static/js/config.js"></script>
<script src="../static/js/iplocal.js"></script>
<script src="../static/js/clienteinfo.js"></script>
<script src="../js/shortcut.js"></script>

<script language="javascript">

    $(function () {
        $('#cli_codigo').focus();

        $('[data-toggle="datepicker"]').datepicker({
            format: 'dd/mm/yyyy',
            autoHide: true,
            language: 'es-ES',
            zIndex: 2048,
        });

        var options = {
            url: function (criterio) {
                return "../app/cliente/ajax/cliente.php?action=fact_clibuscar&criterio=" + criterio;
            },
            getValue: function (e) {
                return e.nombre;
            },
            list: {
                onClickEvent: function () {
                    var cliente = $("#txt_cli_nombre").getSelectedItemData();
                    $('#cli_codigo').val(cliente.cod);
                    $('#txt_vend_codigo').val(cliente.vendcod);
                    $('#txt_vend_nombre').val(cliente.vend);
                    $('#cli_cedula').val('Ruc: ' + cliente.ruc);
                    $('#id-zona').val('Zona: ' + cliente.zona);
                    $('#id-clase').val('Clase: ' + cliente.clase);
                    $('#id-cupo').val('Cupo: ' + parseFloat(cliente.cupo).toFixed(2));
                    venta.factura.cliente = cliente;
                }
            },
            ajaxSettings: {
                dataType: "json",
                beforeSend: function () {
                }
            },
        };

        $("#txt_cli_nombre").easyAutocomplete(options);

        options = {
            url: function (criterio) {
                var bodega = $('#cb_bodega').val();
                if (bodega == '') {
                    alert('Seleccione la Bodega.')
                    criterio.stopPropagation();
                }
                return "../app/inventario/ajax/producto.php?action=buscar&criterio=" + criterio + '&bodega=' + bodega;
            },
            getValue: function (e) {
                return e.producto;
            },
            list: {
                onClickEvent: function () {
                    var producto = $("#id-prod-descripcion").getSelectedItemData();
                    var precio = Math.round((parseFloat(producto.precio)) * 100) / 100;

                    $('#id-prod-codigo').val(producto.cod);
                    $('#id-prod-stock').val(producto.stock);
                    $('#id-prod-pvp').val(precio);
                    $('#id-producto-id').val(producto.id);
                    $('#id-prod-cantidad').val(1);
                    $('#id-prod-cantidad').focus();
                }
            },
            ajaxSettings: {
                dataType: "json",
                beforeSend: function () {
                }
            },
        };

        $("#id-prod-descripcion").easyAutocomplete(options);

        shortcut.add("Ctrl+g", function () {
            $("#btn_guardar").click();
        });
        shortcut.add("F2", function () {
            $("#id-btn-agregar").click();
        });

        $("#btn_guardar").click(function (e) {


            /* if ($('#idcliente').val().trim() == '') {
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
             alert('Ingrese la Nota..');
             $('#txt_nota').focus();
             return;
             }

             var tbdet = parseFloat($('#table_detalle_producto_stock #det .cant').length);

             if (tbdet < 1) {
             alert('No se ha Registrado Productos Facturado.');
             return;
             }

             $('#table_detalle_producto_stock #det tr').each(function (e) {

             var value = $(this).children('td').eq(5).children('input.cant').val().trim();

             if (value.length <= 0 || value == '') {
             alert('Ingrese la Cantidad');
             $(this).children('td').eq(5).children('input.cant').focus();
             e.stopPropagation();
             }

             if (isNaN(parseFloat(value))) {
             alert('El valor ingresado no es Correcto');
             $(this).children('td').eq(5).children('input.cant').val('');
             $(this).children('td').eq(5).children('input.cant').focus();
             e.stopPropagation();
             }

             if (parseFloat(value) < 1) {
             alert('El valor ingresado debe ser Mayor a 0');
             $(this).children('td').eq(5).children('input.cant').val('');
             $(this).children('td').eq(5).children('input.cant').focus();
             e.stopPropagation();
             }

             });

             fn_actualizar_total();
             fn_suma_total();

             if (parseFloat($('#txt_subtotal').val()) <= 0 || parseFloat($('#txt_total_pagar').val()) <= 0) {
             alert('Error: El Total de la Factura esta en Cero.');
             return;
             }

             var resp = confirm('Confirma Guardar.');

             if (!resp) return;

             try {

             $.ajax({
             url: 'pw_guardar_inv_prod_cambio_ven_fa.php',
             data: $('#form_transaccion .datos').serialize(),
             type: "POST",
             async: true,
             cache: false,
             dataType: 'json',
             ifModified: false,
             beforeSend: function (xhr) {
             $('#load_img').addClass('loading');
             $('#btn_guardar').attr("disabled", true);
             },
             success: function (resp) {
             $('#load_img').removeClass('loading');
             $('#btn_guardar').attr("disabled", false);

             if (resp.rp == '1') {
             alert('Guardado Correctamente. Documento Nº: ' + resp.doc);
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
             alert('Error: La Cantidad o Total Factura debe ser mayor a Cero.');
             break;

             case'5':
             alert('Error: EL Producto:' + resp.cod + ' No tiene Stock. Vuelva a Intentarlo.');
             break;

             case'6':
             alert('Error: EL Producto:' + resp.cod + ' Cantidad Mayor al Stock. Vuelva a Intentarlo.');
             break;

             case'7':
             alert('Error: Total Facturado es Diferente a la Suma total Detalle..');
             break;

             case'8':
             alert('Error: No se Pudo Crear los ID de Factura. Contadores');

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
             }*/

            e.preventDefault();

            if (Object.keys(venta.factura.cliente).length <= 0) {
                alert('No ha Seleccionado Ningun Cliente.');
                $('#cli_codigo').focus();
                return;
            }

            venta.actualizar();

            if (venta.factura.items.length <= 0) {
                alert('No se ha ingresado ningun Items al Detalle.');
                return;
            }

            if (venta.factura.total <= 0) {
                alert('El total del Factura esta en Cero.');
                return;
            }

            var division = $('#id-divisionid');
            var bodega = $('#cb_bodega');
            var fecha = $('#fac_fecha');
            var tipo = $('#txt_tipo');
            var nota = $('#txt_nota');
            var contado = $('#id-contado');
            var cajaid = $('#id-cajaid');

            var resp = confirm('Confirme realizar Factura.');
            if (!resp) return;

            venta.factura.divisionid = division.val();
            venta.factura.bodegaid = bodega.val();
            venta.factura.fecha = fecha.val();
            venta.factura.vencimiento = fecha.val();
            venta.factura.tipo = tipo.val();
            venta.factura.detalle = nota.val();
            venta.factura.contado = contado.val();
            venta.factura.vence = fecha.val();
            venta.factura.valor = venta.factura.total;
            venta.factura.saldo = venta.factura.total;
            venta.factura.cajaid = cajaid.val();
            venta.factura.clienteid = venta.factura.cliente.id;
            venta.factura.vendedorid = venta.factura.cliente.vendid;
            venta.factura.terminoid = venta.factura.cliente.terminoid;

            if (venta.factura.contado == '0') {
                venta.factura.credito = venta.factura.total;
            }
            console.log(venta.factura);

            $.ajax({
                url: '../app/venta/ajax/factura.php',
                data: {action: 'venta_factura', factura: JSON.stringify(venta.factura)},
                method: 'POST',
                dataType: 'json',
            }).done(function (data) {
                console.log(data);
                return false;

                if (data.resp == true) {
                    alert('Factura se guardo correctamente.');
                    $('#cli_codigo').val('');
                    $('#cli_codigo').focus();
                } else {
                    alert(data.error);
                }
            }).fail(function (jqXHR, textStatus) {
                alert(textStatus);
            });
        });

        $('#id-form-buscar-cli').on({
            submit: function (e) {
                e.preventDefault();

                if ($('#cli_codigo').val().trim() == '') {
                    alert('Ingrese el Código del Cliente');
                    $('#cli_codigo').focus();
                    return;
                }
                var frmData = new FormData($(this)[0]);
                $.ajax({
                    url: '../app/cliente/ajax/cliente.php',
                    data: frmData,
                    method: 'POST',
                    dataType: 'json',
                    cache: false,
                    contentType: false,
                    processData: false
                }).done(function (data) {
                    if (data.resp == true) {
                        var cliente = data.cliente;
                        $('#txt_cli_nombre').val(cliente.nombre);
                        $('#txt_vend_codigo').val(cliente.vendcod);
                        $('#txt_vend_nombre').val(cliente.vend);
                        $('#cli_cedula').val('Ruc: ' + cliente.ruc);
                        $('#id-zona').val('Zona: ' + cliente.zona);
                        $('#id-clase').val('Clase: ' + cliente.clase);
                        $('#id-cupo').val('Cupo: ' + parseFloat(cliente.cupo).toFixed(2));

                        venta.factura.cliente = cliente;
                        $('#id-prod-codigo').focus();

                    } else {
                        alert(data.error);
                    }
                }).fail(function (jqXHR, textStatus) {
                    alert(textStatus);
                });
            }
        });

        $('#id-form-buscar-prod').on({
            submit: function (e) {
                e.preventDefault();
                var codigo = $('#id-prod-codigo').val();
                var bodega = $('#cb_bodega').val();
                if (bodega == '') {
                    alert('Seleccione la Bodega.');
                    $('#cb_bodega').focus();
                    return;
                }

                $.ajax({
                    url: '../app/inventario/ajax/producto.php',
                    data: {action: 'producto', codigo: codigo, bodega: bodega},
                    method: 'GET',
                    dataType: 'json',
                }).done(function (data) {

                    if (data.resp == true) {
                        var producto = data.producto;
                        var precio = Math.round((parseFloat(producto.precio)) * 100) / 100;
                        $('#id-prod-descripcion').val(producto.producto);
                        $('#id-prod-codigo').val(producto.cod);
                        $('#id-prod-stock').val(Number(producto.stock));
                        $('#id-prod-pvp').val(precio);
                        $('#id-producto-id').val(producto.id);
                        $('#id-prod-cantidad').val(1);
                        $('#id-prod-cantidad').focus();

                    } else {
                        alert(data.error);
                    }
                }).fail(function (jqXHR, textStatus) {
                    alert(textStatus);
                });
            }
        });

        $('#id-btn-agregar').on('click', function (e) {
            e.preventDefault();

            var codigo = $('#id-prod-codigo').val();
            var bodega = $('#cb_bodega').val();
            var cantidad = parseFloat($('#id-prod-cantidad').val());
            var precio = Math.round((parseFloat($('#id-prod-pvp').val())) * 100) / 100;

            if (bodega == '') {
                alert('Seleccione la Bodega.');
                $('#cb_bodega').focus();
                return false;
            }

            if (codigo == '') {
                alert('No ha Seleccionado ningun Producto.');
                $('#id-prod-codigo').focus();
                return false;
            }

            if (cantidad <= 0) {
                alert('La Cantidad ingresada debe ser mayor a Cero.');
                $('#cb_bodega').focus();
                return false;
            }

            if (precio <= 0) {
                alert('El Precio del Producto debe ser Mayor a Cero.');
                $('#id-prod-pvp').focus();
                return false;
            }

            $.ajax({
                url: '../app/inventario/ajax/producto.php',
                data: {action: 'producto', codigo: codigo, bodega: bodega},
                method: 'GET',
                dataType: 'json',
            }).done(function (data) {

                if (data.resp == true) {

                    var producto = data.producto;
                    var stock = parseFloat(producto.stock);

                    if (cantidad > stock) {
                        alert('La canidad ingresada no puede ser Mayor que el Stock');
                        $('#id-prod-cantidad').focus();
                        return false;
                    }
                    producto.productoid = producto.id;
                    producto.stock = stock;
                    producto.cantidad = cantidad;
                    producto.precio = precio;
                    producto.costo = parseFloat(producto.costo);
                    producto.bodegaid = bodega;
                    producto.bodega = $('#cb_bodega option:selected').text();
                    producto.cupones = 0;
                    producto.valorcupon = 0;
                    producto.precioname = producto.empaque;
                    producto.factor = 1;
                    producto.detalle_ex = '';
                    producto.tasaimpuesto = parseFloat(producto.tasaimpuesto);

                    console.log(producto);

                    venta.add(producto);
                    venta.actualizar();

                } else {
                    alert(data.error);
                }
            }).fail(function (jqXHR, textStatus) {
                alert(textStatus);
            });
        });

        $('#table_detalle_producto_stock > tbody').on('change', '.cantidad', function (e) {
            var cantidad = parseFloat($(this).val());
            var data = $(this).data('json');
            var stock = parseFloat(data.stock);
            if (cantidad > stock) {
                cantidad = stock;
            } else {
                if (cantidad <= 0) {
                    cantidad = 1;
                }
            }
            venta.editar({productoid: data.id, cantidad: cantidad});
            venta.actualizar();
        });

        $('#id-prod-cantidad').keypress(function (e) {
            var keycode = event.keyCode || event.which;
            if (keycode == '13') {
                $('#id-btn-agregar').click();
            }
        });

        $('#id-prod-pvp').keypress(function (e) {
            var keycode = event.keyCode || event.which;
            if (keycode == '13') {
                $('#id-btn-agregar').click();
            }
        });

        clienteinfo(window);

        getUserIP(function (ip) {
            var pc = 'IP:' + ip + '-SO: ' + jscd.os + ' ' + jscd.osVersion;
            pc += '- Navegador: ' + jscd.browser + ' ' + jscd.browserMajorVersion;
            venta.factura.pc = pc;
        });
    });


    var venta = {
        factura: {
            divisionid: '',
            fecha: '',
            vencimiento: '',
            tipo: 'VEN-FA',
            bodegaid: '',
            cliente: new Object,
            clienteid : '',
            vendedorid : '',
            terminoid : '',
            subtotal: 0,
            descuento: 0,
            impuesto: 0,
            total: 0,
            valor: 0,
            saldo: 0,
            costototal: 0,
            items: [],
            detalle: '',
            contado: '0',
            efectivo: 0,
            cheque: 0,
            tarjeta: 0,
            credito: 0,
            cupones: 0,
            vence: '',
            banco: '',
            transporte: '',
            cajaid: '',
            pc: ''
        },
        add: function (item) {

            if (!this.existe(item)) {

                item.subtotal = Math.round((item.cantidad * item.precio) * 100) / 100;

                if (item.coniva) {
                    item.impuesto = Math.round((item.subtotal * (item.tasaimpuesto / 100)) * 100) / 100;
                } else {
                    item.impuesto = 0;
                }
                item.tasadescuento = 0;
                item.descuento = 0;

                item.total = Math.round((item.subtotal + item.impuesto) * 100) / 100;
                this.factura.items.push(item);
            }
            return true;
        },

        existe: function (item) {

            for (var i in this.factura.items) {

                if (item.productoid == this.factura.items[i].productoid) {
                    var stock = this.factura.items[i].stock;
                    var cantidad = this.factura.items[i].cantidad;
                    if ((cantidad + item.cantidad) > stock) {
                        return true;
                    }

                    this.factura.items[i].cantidad += item.cantidad;
                    this.factura.items[i].precio = item.precio;
                    this.factura.items[i].subtotal = Math.round((this.factura.items[i].cantidad * item.precio) * 100) / 100;

                    if (item.coniva) {
                        this.factura.items[i].impuesto = Math.round((this.factura.items[i].subtotal * (item.tasaimpuesto / 100)) * 100) / 100;
                    } else {
                        this.factura.items[i].impuesto = 0;
                    }
                    this.factura.items[i].tasadescuento = 0;
                    this.factura.items[i].descuento = 0;

                    this.factura.items[i].total = Math.round((this.factura.items[i].subtotal + this.factura.items[i].impuesto) * 100) / 100;
                    return true;
                }
            }
            return false;
        },
        editar: function (item) {

            for (var i in this.factura.items) {
                if (item.productoid == this.factura.items[i].productoid) {

                    this.factura.items[i].cantidad = item.cantidad;
                    this.factura.items[i].subtotal = Math.round((item.cantidad * this.factura.items[i].precio) * 100) / 100;

                    if (this.factura.items[i].coniva) {
                        this.factura.items[i].impuesto = Math.round((this.factura.items[i].subtotal * (this.factura.items[i].tasaimpuesto / 100)) * 100) / 100;
                    } else {
                        this.factura.items[i].impuesto = 0;
                    }

                    this.factura.items[i].tasadescuento = 0;
                    this.factura.items[i].descuento = 0;

                    this.factura.items[i].total = Math.round((this.factura.items[i].subtotal + this.factura.items[i].impuesto) * 100) / 100;
                    return true;
                }
            }
            return false;
        },
        eliminar: function (id) {
            for (var i in this.factura.items) {
                if (this.factura.items[i].productoid == id) {
                    this.factura.items.splice(i, 1);
                    this.actualizar();
                    return true;
                }
            }
            return false;
        },
        actualizar: function () {

            this.factura.subtotal = 0;
            this.factura.descuento = 0;
            this.factura.impuesto = 0;
            this.factura.total = 0;
            this.factura.costototal = 0;

            for (var i in this.factura.items) {
                this.factura.subtotal += this.factura.items[i].subtotal;
                this.factura.impuesto += this.factura.items[i].impuesto;
                this.factura.descuento += this.factura.items[i].descuento;
                this.factura.total += this.factura.items[i].total;
                this.factura.costototal += this.factura.items[i].costo;
            }

            this.render();

            $('#txt_subtotal').val('$' + this.factura.subtotal.toFixed(2));
            $('#txt_descuento').val('$' + this.factura.descuento.toFixed(2));
            $('#txt_impuesto').val('$' + this.factura.impuesto.toFixed(2));
            $('#txt_total_pagar').val('$' + this.factura.total.toFixed(2));

            //console.log(this.factura.items);
        },
        render: function () {
            $.views.settings.allowCode(true);
            var tmpl = $.templates("#js-template-det-factura"); // Get compiled template
            var html = tmpl.render(this.factura);      // Render template using data - as HTML string
            $("#table_detalle_producto_stock #det").html(html);
        },

    };

</script>
</body>
</html>