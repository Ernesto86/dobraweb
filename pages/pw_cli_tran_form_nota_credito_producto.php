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

            shortcut.add("Ctrl+g", function () {
                $("#btn_guardar").click();
            });

            shortcut.add("Ctrl+b", function () {
                $('#btn_cli_buscar').click();
            });

            $('#btn_cli_buscar').click(function () {
                $(".float-content").show();
                $("#cont_buscar").load('pw_buscar_cliente.php');
            });

            $(".cerrar").click(function () {
                $(".float-content").hide("fat");
                event.preventDefault();
            });

            $('#cb_bodega').change(function () {
                var id = $(this).val();
                $('#id_bodega').val(id);
            });

            $('#cb_bodega').keypress(function (e) {
                if (e.keyCode == 13 && $(this).val().trim() != '') {
                    $('#txt_cli_codigo').focus();
                }
            });

            $("#btn_autollenar").click(function () {

                var value = $('#txt_cli_concepto').val();
                if (value) $('#txt_nota').val(value);

            });

            $('#cb_det_factura').change(function () {

                var num_fact = $(this).val().trim();

                if (num_fact == '' || num_fact.length < 1) return;

                $('#txt_num_factura').val(num_fact);

                $('#form_trans').submit();

            });

            $("#btn_guardar").click(function () {

                if ($('#cb_bodega').val().trim() == '') {
                    alert('Seleccione la Bodega');
                    $('#cb_bodega').focus();
                    return;
                }

                if ($('#id_bodega').val().trim() == '') {
                    alert('No Se Encontro Id de Bedega. Vuelva a Intentarlo.');
                    return;
                }

                $('#btn_aplicar_deuda').click();

                if ($('#txt_tipo').val().trim() == '') {
                    alert('No Se Encontro Tipo');
                    $('#txt_tipo').focus();
                    return;
                }

                if ($('#txt_nota').val().trim() == '') {
                    $("#btn_autollenar").click();
                }

                if ($('#txt_ing_fecha').val().trim() == '') {
                    alert('La Fecha no esta Presente.');
                    $('#txt_ing_fecha').focus();
                    return;
                }

                if ($('#txt_cli_concepto').val().trim() == '') {
                    alert('Ingrese el Concepto .');
                    $('#txt_cli_concepto').focus();
                    return;
                }

                var contador = 0;
                var ban_cant = false;

                $('#table_detalle_producto_stock #det tr').each(function (e) {

                    var cant_ = parseInt($(this).children('td').eq(3).children('input.cant').val());

                    if (cant_ > 0) {

                        var value = $(this).children('td').eq(4).children('input.devol').val().trim();

                        if (value.length > 0 && value != '') {

                            if (isNaN(parseFloat(value))) {
                                alert('El valor ingresado no es Correcto');
                                $(this).children('td').eq(4).children('input.devol').val('');
                                $(this).children('td').eq(4).children('input.devol').focus();
                                e.stopPropagation();
                            }

                            if (parseFloat(value) < 1) {
                                alert('El valor ingresado debe ser Mayor a 0');
                                $(this).children('td').eq(4).children('input.devol').val('');
                                $(this).children('td').eq(4).children('input.devol').focus();
                                e.stopPropagation();
                            }

                            if (parseFloat(value)) {
                                contador++;
                            }
                        }

                        ban_cant = true;
                    }

                });

                if (!ban_cant) {
                    alert('La Cantidades en el Detalle estan en Cero..');
                    return;
                }

                if (contador < 1) {
                    alert('No ha Ingresado Cantidad a devolver');
                    $('#table_detalle_producto_stock #det tr:first input.devol').focus();
                    return;
                }

                if (parseFloat($('#txt_total_cred').val()) <= 0) {
                    alert('El valor Total Credito debe ser Mayor a 0');
                    return;
                }

                var resp = confirm('Confirma Guardar.');

                if (!resp) return;

                try {

                    $.ajax({
                        url: 'pw_guardar_nota_credito_producto.php',
                        data: $('#form_transaccion .datos').serialize(),
                        type: "POST",
                        async: true,
                        cache: false,
                        dataType: 'json',
                        ifModified: false,
                        beforeSend: function (xhr) {
                            if (xhr.overrideMimeType) {//Para Google Chrome y Firefox
                                xhr.overrideMimeType('text/html; charset=iso-8859-1');
                            } else {
                                xhr.setRequestHeader('Content-type', 'text/html; charset=iso-8859-1');
                            }
                            $('#load_img').addClass('loading');
                            $('#btn_guardar').attr("disabled", true);
                        },
                        success: function (resp) {

                            $('#load_img').removeClass('loading');
                            $('#btn_guardar').attr("disabled", false);

                            if (resp.rp == '1') {
                                alert('Guardado Correctamente. Documento N#: ' + resp.doc);
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
                                    alert('Error: El Cliente no Tiene Deuda.');
                                    break;

                                case'5':
                                    alert('Error: No se Encontro Registro de Ingresos de Pagos.');
                                    break;

                                case'6':
                                    alert('Error: No se Pudo Crear los ID de Ingreso.');


                            }
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


            $('#btn_aplicar_deuda').click(function () {

                if ($('#txt_num_factura').val().trim() == '') {
                    alert('No ha Ingresado Numero de Factura');
                    $('#txt_num_factura').focus();
                    return;
                }

                if ($('#id_cliente').val().trim() == '' || $('#idfactura').val().trim() == '') {
                    alert('El Numero de Factura no esta Presente..');
                    $('#txt_num_factura').focus();
                    return;
                }

                var strsald = $('#txt_total_deuda').val().trim();

                if (strsald == '' || parseFloat(strsald) < 1) {
                    alert('El Cliente no Tiene Deuda');
                    return;
                }

                var tbcab = parseFloat($('#table_detalle_producto_stock #det input').length);
                var tbdet = parseFloat($('#cli_table_det_deuda #det input').length);

                if (tbcab < 1 || tbdet < 1) {
                    alert('No se ha Registrado Ingreso Alguno.');
                    return;
                }

                fn_actualizar_total();
                fn_suma_total();

                var saldo = parseFloat($('#txt_total_deuda').val());
                var total_cred = parseFloat($('#txt_total_cred').val());

                if (total_cred && saldo) {

                    if (total_cred > saldo) {
                        alert('El Valor del Credito es Mayor a la Deuda.');
                        return;
                    }

                    $('#txt_total_abono').val(number_format(total_cred));

                    var nuevosald = parseFloat(saldo - total_cred);

                    $('#txt_nuevo_saldo').val(number_format(nuevosald));

                    fn_aplicar_deuda(total_cred);
                }
            });

            function fn_aplicar_deuda(abono) {

                $('#cli_table_det_deuda #det tr').each(function () {

                    var sald = parseFloat($(this).children('td').eq(6).children('input.valor').val());
                    $(this).children('td').eq(7).children('input.valor').val('');
                    $(this).children('td').eq(8).children('input.valor').val(sald);

                });

                $('#cli_table_det_deuda #det tr').each(function () {

                    if (abono) {

                        var sald = parseFloat($(this).children('td').eq(6).children('input.valor').val());
                        var newsald = parseFloat($(this).children('td').eq(8).children('input.valor').val());

                        if (!isNaN(parseFloat(sald)) && !isNaN(parseFloat(newsald))) {

                            var new_abono = 0;

                            if (abono > sald) {
                                new_abono = decimals(sald, 2);
                                abono = parseFloat(abono - sald);
                                newsald = 0;
                            } else {
                                if (abono > 0) {
                                    newsald = decimals((sald - abono), 2);
                                    new_abono = decimals(abono, 2);
                                    abono -= abono;
                                } else {
                                    new_abono = decimals(abono, 2);
                                    newsald = decimals(sald, 2);
                                }
                            }

                            new_abono = number_format(new_abono);
                            newsald = number_format(newsald);

                            $(this).children('td').eq(7).children('input.valor').val(new_abono);
                            $(this).children('td').eq(8).children('input.valor').val(newsald);
                        }
                    }

                });
            }

            $('#table_detalle_producto_stock #det').delegate(':input.devol:text', 'keypress', function (e) {

                if (e.keyCode == 13) {

                    e.preventDefault();
                    if ($(this).val().length < 1 || $(this).val().trim() == '') {
                        alert('Ingrese el Valor');
                        $(this).focus();
                        e.stopPropagation();
                    }

                    var value = parseFloat($(this).val());

                    if (isNaN(parseFloat(value))) {
                        alert('El valor ingresado no es Correcto');
                        $(this).val('');
                        $(this).focus();
                        e.stopPropagation();
                    }

                    if (value < 1) {
                        alert('El valor ingresado debe ser mayor a Cero');
                        $(this).val('');
                        $(this).focus();
                        e.stopPropagation();
                    }
                    $('#btn_aplicar_deuda').click();
                    e.stopPropagation();

                }

                if (e.keyCode == 8) {
                    return;
                }
                if (e.which && (e.which < 48 || e.which > 57) && e.keyCode != 8) {
                    e.preventDefault();
                }

            });

            $('#table_detalle_producto_stock #det').delegate(':input.devol:text', 'blur', function (e) {

                if ($(this).val().length < 1 || $(this).val().trim() == '') {
                    $('#btn_aplicar_deuda').click();
                    e.stopPropagation();
                }

                var value = parseFloat($(this).val());

                if (isNaN(parseFloat(value))) {
                    alert('El valor ingresado no es Correcto');
                    $(this).val('');
                    $(this).focus();
                    e.stopPropagation();
                }

                if (value < 1) {
                    alert('El valor ingresado debe ser mayor a Cero');
                    $(this).val('');
                    $(this).focus();
                    e.stopPropagation();
                }
                $('#btn_aplicar_deuda').click();

            });

        });

        function fn_buscar_num_factura() {

            if ($('#txt_num_factura').val().length < 1 || $('#txt_num_factura').val().trim() == '') {
                alert('Ingrese el Numero de Factura.');
                $('#txt_num_factura').val('');
                return;
            }

            $('#idfactura').val('');
            $('#id_cliente').val('');

            $('#table_detalle_producto_stock #det').html('');
            $('#cli_table_det_deuda #det').html('');
            $('#txt_cli_nombre').val('');

            $('#txt_nota').val('');
            $('#txt_total_deuda').val('0.00');
            $('#txt_total_cred').val('0.00');
            $('#txt_total_abono').val('0.00');
            $('#txt_nuevo_saldo').val('0.00');

            try {

                $.ajax({
                    url: 'pw_ven_det_datos_num_factura.php',
                    data: {num_fact: $('#txt_num_factura').val()},
                    type: "POST",
                    async: true,
                    cache: false,
                    dataType: 'json',
                    ifModified: false,
                    beforeSend: function (xhr) {
                        if (xhr.overrideMimeType) {//Para Google Chrome y Firefox
                            xhr.overrideMimeType('text/html; charset=iso-8859-1');
                        } else {
                            xhr.setRequestHeader('Content-type', 'text/html; charset=iso-8859-1');
                        }
                        $('#load_img').addClass('loading');
                        $('#btn_cli_enviar').attr("disabled", true);
                    },
                    success: function (resp) {

                        $('#load_img').removeClass('loading');
                        $('#btn_cli_enviar').attr("disabled", false);

                        if (resp.err == '1') {
                            alert('Error: No se Encontro Numero de Factura.');
                            return;
                        }

                        if (resp.err == '0') {
                            alert('Error: Parametros no Validos');
                            return;
                        }

                        var fact_id = resp.fact_id.toString();

                        if (!fact_id) {
                            alert('Error: No se Encontro Numero de Factura.');
                            return;
                        }

                        $('#idfactura').val(fact_id);
                        $('#id_cliente').val(resp.cli_id);
                        $('#txt_cli_codigo').val(resp.cli_cod);
                        $('#txt_cli_nombre').val(resp.cli_nomb);
                        $('#txt_asiento').val(resp.asient_id);
                        $('#txt_cli_ruc').val(resp.cli_ruc);
                        $('#id_empleado').val(resp.emp_id);
                        $('#txt_cod_vendedor').val(resp.emp_cod);
                        $('#txt_vendedor').val(resp.emp_nom);

                        fn_detalle_fact_prod(fact_id);
                        fn_detalle_deuda(resp.cli_id);
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

        function fn_buscar_cliente() {

            if ($('#txt_cli_codigo').val().length < 1 || $('#txt_cli_codigo').val().trim() == '') {
                alert('Ingrese el Código del Cliente.');
                $('#txt_cli_codigo').val('');
                $('#txt_cli_codigo').focus();
            }

            $('#idcliente').val('');
            $('#txt_cli_nombre').val('');

            try {

                $.ajax({
                    url: 'pw_cli_datos_cliente.php',
                    data: {cod: $('#txt_cli_codigo').val()},
                    type: "POST",
                    async: true,
                    cache: false,
                    dataType: 'json',
                    ifModified: false,
                    beforeSend: function (xhr) {
                        if (xhr.overrideMimeType) {//Para Google Chrome y Firefox
                            xhr.overrideMimeType('text/html; charset=iso-8859-1');
                        } else {
                            xhr.setRequestHeader('Content-type', 'text/html; charset=iso-8859-1');
                        }
                        $('#load_img').addClass('loading');
                        $('#btn_cli_enviar').attr("disabled", true);
                    },
                    success: function (resp) {

                        $('#load_img').removeClass('loading');
                        $('#btn_cli_enviar').attr("disabled", false);

                        if (resp.err == '0') {
                            alert('Error: Parametros no Validos');
                            return;
                        }

                        var idcli = resp.id.toString();
                        if (!idcli) {
                            alert('Error: No se Encontro Cliente.');
                            return;
                        }

                        $('#idcliente').val(idcli);
                        $('#txt_cli_nombre').val(resp.cli);

                        fn_cli_facturas_det(idcli);
                        $("#cb_det_factura").focus();

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

        function fn_detalle_fact_prod(id) {
            try {

                $.ajax({
                    url: 'pw_ven_det_producto_factura.php',
                    data: {idfact: id},
                    type: "POST",
                    async: true,
                    cache: false,
                    ifModified: false,
                    beforeSend: function (xhr) {
                        if (xhr.overrideMimeType) {//Para Google Chrome y Firefox
                            xhr.overrideMimeType('text/html; charset=iso-8859-1');
                        } else {
                            xhr.setRequestHeader('Content-type', 'text/html; charset=iso-8859-1');
                        }
                        $('#load_img').addClass('loading');
                    },
                    success: function (data) {
                        $('#load_img').removeClass('loading');
                        $('#table_detalle_producto_stock #det').html(data);
                        $('#table_detalle_producto_stock #det tr:first .devol').focus();

                    },
                    error: function () {
                        $('#load_img').removeClass('loading');
                        alert('Fallo Conectando con el servidor');
                    }
                });

            } catch (e) {
                alert("Error: " + e.description);
            }
        }

        function fn_detalle_deuda(id) {
            try {

                $.ajax({
                    url: 'pw_cli_det_cliente_deuda.php',
                    data: {idcli: id},
                    type: "POST",
                    async: true,
                    cache: false,
                    ifModified: false,
                    beforeSend: function (xhr) {
                        if (xhr.overrideMimeType) {//Para Google Chrome y Firefox
                            xhr.overrideMimeType('text/html; charset=iso-8859-1');
                        } else {
                            xhr.setRequestHeader('Content-type', 'text/html; charset=iso-8859-1');
                        }
                        $('#load_img').addClass('loading');
                    },
                    success: function (data) {
                        $('#load_img').removeClass('loading');
                        $('#cli_table_det_deuda #det').html(data);
                    },
                    error: function () {
                        $('#load_img').removeClass('loading');
                        alert('Fallo Conectando con el servidor');
                    }
                });

            } catch (e) {
                alert("Error: " + e.description);
            }
        }

        function fn_suma_total() {

            var value = 0;

            $('#table_detalle_producto_stock #det tr').each(function () {

                var str = $(this).children('td').eq(7).children('input.total').val().trim();

                if (str.length > 0 && str != '') {
                    value = parseFloat(value) + parseFloat($(this).children('td').eq(7).children('input.total').val());
                }

            });

            var total = number_format(value);

            $('#txt_total_cred').val(total);
        }

        function fn_actualizar_total() {

            var saldo = parseFloat($('#txt_total_deuda').val());

            $('#table_detalle_producto_stock #det tr').each(function (e) {

                var value = $(this).children('td').eq(4).children('input.devol').val().trim();

                if (value.length > 0 && value != '') {

                    var cant_ = parseInt($(this).children('td').eq(3).children('input.cant').val());
                    var devol_ = parseInt($(this).children('td').eq(4).children('input.devol').val());

                    if (devol_ > cant_) {

                        alert('No Puede Devolver mas de lo que Facturo.');
                        $(this).children('td').eq(4).children('input.devol').val('');
                        $(this).children('td').eq(7).children('input.total').val('');
                        $(this).children('td').eq(4).children('input.devol').focus();
                        e.stopPropagation();

                    }

                    var pvp_ = parseFloat($(this).children('td').eq(6).children('input.pvp').val());

                    var total_ = decimals((devol_ * pvp_), 2);

                    if (total_ > saldo) {

                        alert('El Subtotal Supera la Deuda.');
                        $(this).children('td').eq(4).children('input.devol').val('');
                        $(this).children('td').eq(7).children('input.total').val('');

                    } else {

                        $(this).children('td').eq(7).children('input.total').val(total_);

                    }

                } else {

                    $(this).children('td').eq(4).children('input.devol').val('');
                    $(this).children('td').eq(7).children('input.total').val('');
                }
            });
        }

        function fn_cli_facturas_det(idcli) {
            document.getElementById('cb_det_factura').options.length = 1;
            $.post("pw_cb_cli_facturas_det.php", {id: idcli},
                function (data) {
                    $("#cb_det_factura").append(data);
                });
        }

        function fn_select_cod(codigo) {
            $('#txt_cli_codigo').val(codigo);
            $(".float-content").hide("slow");
            $('#txt_cli_codigo').focus();
            event.preventDefault();
        }

        function decimals(n, d) {
            return Math.round(n * Math.pow(10, d)) / Math.pow(10, d)
        }
        function number_format(num) {
            var p = num.toFixed(2).split(".");
            return p[0].split("").reverse().reduce(function (acc, num, i, orig) {
                    return num + (i && !(i % 3) ? "," : "") + acc;
                }, "") + "." + p[1];
        }
    </script>
</head>
<body>
<div id="buscar">
    <div id="title_form"><img src="../images/icon/list-view.png"/> <?php echo $_GET['tit']; ?></div>
    <div id="btns_accion">
        <div style="display:none">
            <input type="hidden" name="id_menu" id="id_menu" value="<?php echo intval($_GET['id']); ?>"/>
        </div>
        <table border="0" cellpadding="0" cellspacing="0">
            <tr>
                <td><input name="btn_nuevo" type="button" class="btn_nuevo" id="btn_nuevo" value="Nuevo"
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
                <td class="bg_cab_title"><img src="../images/icon/Grid.png"/> Datos de Nota Credito Producto.
                    <div class="rigth"></div>
                </td>
            </tr>
            <tr>
                <td>
                    <table width="100%" border="0" cellpadding="0" cellspacing="0">
                        <tr>
                            <td width="78%">
                                <table border="0" cellpadding="0" cellspacing="0" class="styletable">
                                    <tr>
                                        <td></td>
                                        <td></td>
                                        <td>Estado</td>
                                        <td>&nbsp;</td>
                                        <td>N&uacute;mero</td>
                                        <td>Asiento</td>
                                        <td>Fecha</td>
                                        <td>Tipo</td>
                                        <td>&nbsp;</td>
                                        <td>&nbsp;</td>
                                    </tr>
                                    <tr>
                                        <td>&nbsp;</td>
                                        <td>&nbsp;</td>
                                        <td><input name="txt_cli_estado" type="text" id="txt_cli_estado" size="12"
                                                   maxlength="10" readonly=""/></td>
                                        <td>&nbsp;</td>
                                        <td><input name="txt_num_fact_aux" type="text" id="txt_num_fact_aux" size="10"
                                                   maxlength="10" readonly=""/></td>
                                        <td><input name="txt_asiento" type="text" id="txt_asiento" size="10"
                                                   maxlength="10" readonly=""/></td>
                                        <td><input name="txt_ing_fecha" type="text" id="txt_ing_fecha" size="10"
                                                   maxlength="10"
                                                   value="<?php echo $_SESSION['us_fecha'] ? $_SESSION['us_fecha'] : date("d/m/Y"); ?>"
                                                   class="fecha datos" readonly=""/></td>
                                        <td><input name="txt_tipo" type="text" id="txt_tipo" value="CLI-CR-PD" size="10"
                                                   maxlength="10" readonly="" class="datos"/></td>
                                        <td><select name="cb_bodega" id="cb_bodega" style="width:230px"
                                                    class="datos negrita" <?php if ($_SESSION['us_tipuser'] != 'administrador' and $_SESSION['us_tipuser'] != 'semiadministrador') { ?> disabled="disabled"<?php } ?>>
                                                <option value="" selected="selected">Seleccione</option>
                                                <?php
                                                require '../clases/cls_consulta.php';
                                                $obj = new cls_consulta;
                                                if ($obj->fn_inv_bodega()) $obj->combo($bodega_id); ?>
                                            </select>
                                            <input type="hidden" name="id_bodega" id="id_bodega"
                                                   value="<?php echo $bodega_id; ?>" class="datos"/></td>
                                        <td>&nbsp;</td>
                                    </tr>
                                    <tr>
                                        <td align="right">&nbsp;</td>
                                        <td align="right">Concepto</td>
                                        <td colspan="7"><input name="txt_cli_concepto" type="text" id="txt_cli_concepto"
                                                               size="92" maxlength="70" class="datos"/></td>
                                        <td>&nbsp;</td>
                                    </tr>
                                    <tr>
                                        <td align="right">&nbsp;</td>
                                        <td align="right">N&ordm;.Factura</td>
                                        <td>
                                            <form id="form_trans" name="form_trans" method="post"
                                                  action="javascript:fn_buscar_num_factura();">
                                                <input type="hidden" name="idfactura" id="idfactura" value=""
                                                       class="datos"/>
                                                <input name="txt_num_factura" type="text" id="txt_num_factura" size="10"
                                                       class="txt-codigo datos" maxlength="10"/>
                                            </form>
                                        </td>
                                        <td><input name="btn_cli_enviar" type="button" class="btn_enviar_num_fact"
                                                   id="btn_cli_enviar" title="Enviar Num. Factura" value=" "
                                                   onclick="fn_buscar_num_factura();"/></td>
                                        <td colspan="4">
                                            <select name="cb_det_factura" id="cb_det_factura" style="width:356px"
                                                    class="negrita">
                                                <option value="">Seleccione</option>
                                            </select></td>
                                        <td>&nbsp;</td>
                                        <td>&nbsp;</td>
                                    </tr>
                                    <tr>
                                        <td align="right">&nbsp;</td>
                                        <td align="right">Cliente</td>
                                        <td>
                                            <form id="form_trans_cli" name="form_trans_cli" method="post"
                                                  action="javascript:fn_buscar_cliente();">
                                                <input name="txt_cli_codigo" type="text" id="txt_cli_codigo" size="10"
                                                       maxlength="10" class="txt-codigo"/>
                                                <input type="hidden" name="id_cliente" id="id_cliente" value=""
                                                       class="datos"/>
                                            </form>
                                        </td>
                                        <td align="center">
                                            <div id="load_img"></div>
                                        </td>
                                        <td colspan="6"><input name="txt_cli_nombre" type="text" id="txt_cli_nombre"
                                                               size="54" maxlength="50" readonly="" class="negrita"/>
                                            <input name="btn_cli_buscar" type="button" class="btnbuscar"
                                                   id="btn_cli_buscar" title="Buscar Clientes Ctrl+B" value="..."/></td>
                                    </tr>
                                    <tr>
                                        <td align="right">&nbsp;</td>
                                        <td align="right">Ruc</td>
                                        <td><input name="txt_cli_ruc" type="text" id="txt_cli_ruc" size="10"
                                                   maxlength="15" class="txt-codigo datos" readonly="readonly"/></td>
                                        <td align="center"><input type="hidden" name="id_empleado" id="id_empleado"
                                                                  value="" class="datos"/>
                                            Vend.
                                        </td>
                                        <td colspan="6"><input name="txt_cod_vendedor" type="text" id="txt_cod_vendedor"
                                                               size="10" maxlength="10" readonly="" class="negrita"/>
                                            <input name="txt_vendedor" type="text" id="txt_vendedor" size="38"
                                                   maxlength="40" readonly="" class="negrita datos"/></td>
                                    </tr>
                                </table>
                            </td>
                            <td width="22%" align="right" valign="bottom">
                                <div align="center">
                                    <img src="../images/logo.png" width="90" height="81"/>
                                </div>
                                <div class="aplicar" align="center">
                                    <input name="btn_aplicar_deuda" type="button" class="green" id="btn_aplicar_deuda"
                                           value=" Aplicar Credito &gt;&gt; "/>
                                </div>
                            </td>
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
                                            <th width="40px">Cant.</th>
                                            <th width="40px">Dev.</th>
                                            <th width="75px">Bodega</th>
                                            <th width="28px">Valor</th>
                                            <th width="28px">Extend</th>
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
                                <table border="0" align="right" cellpadding="0" cellspacing="0" class="styletable">
                                    <tr>
                                        <td>TOTAL CREDITO :</td>
                                        <td><input name="txt_total_cred" type="text" class="total_fact datos"
                                                   id="txt_total_cred" value="0.00" size="8"/></td>
                                        <td>&nbsp;</td>
                                    </tr>
                                </table>
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
            <tr>
                <td class="bg_cab_title"><img src="../images/icon/Grid.png"/> Detalle de Deudas del Cliente.</td>
            </tr>
            <tr>
                <td>
                    <div class="tabledetalle_dt">
                        <table border="0" cellpadding="0" cellspacing="0" class="styletable_detalle"
                               id="cli_table_det_deuda">
                            <thead>
                            <tr>
                                <th width="10px">N#</th>
                                <th width="35px">Emisi&oacute;n</th>
                                <th width="35px">Vence</th>
                                <th width="40px">Tipo</th>
                                <th width="50px">N&uacute;mero</th>
                                <th width="180px">Concepto</th>
                                <th width="35px">Saldo</th>
                                <th width="35px">Abono</th>
                                <th width="35px">NuevoSaldo</th>
                            </tr>
                            </thead>
                            <tbody id="det">
                        </table>
                    </div>
                </td>
            </tr>
            <tr>
                <td>
                    <table border="0" align="right" cellpadding="0" cellspacing="0" id="table_det_totales">
                        <tr>
                            <th align="center" width="120">TOTAL DEUDA</th>
                            <th align="center" width="120">t.ABONO</th>
                            <th align="center" width="120">nUEVO SALDO</th>
                        </tr>
                        <tr>
                            <td align="center"><input name="txt_total_deuda" type="text" class="totales"
                                                      id="txt_total_deuda" value="0.00" size="8" readonly="readonly"/>
                            </td>
                            <td align="center"><input name="txt_total_abono" type="text" id="txt_total_abono"
                                                      value="0.00" size="8" readonly="readonly" class="datos"/></td>
                            <td align="center"><input name="txt_nuevo_saldo" type="text" id="txt_nuevo_saldo"
                                                      value="0.00" size="8" readonly="readonly"/></td>
                        </tr>
                    </table>
                </td>
            </tr>
            <tr>
                <td></td>
            </tr>
            <tr>
                <td class="bg_cab_title"><img src="../images/icon/Grid.png"/> Observaciones.</td>
            </tr>
            <tr>
                <td>
                    <div class="relleno">
                        <table width="100%" border="0" cellspacing="0" cellpadding="0" class="styletable">
                            <tr>
                                <td width="9%">
                                    <input name="btn_autollenar" type="button" class="green" id="btn_autollenar"
                                           value="F1: Autollenar &gt;&gt;"/></td>
                                <td width="91%">
                                    <input name="txt_nota" type="text" class="datos" id="txt_nota" size="120"
                                           maxlength="70"/>
                                </td>
                            </tr>
                        </table>
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