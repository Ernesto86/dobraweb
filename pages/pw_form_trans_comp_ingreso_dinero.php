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
    <link rel="stylesheet" href="../static/css/easy-autocomplete.min.css">
    <link rel="stylesheet" type="text/css" href="../css/formularios.css">
    <link rel="stylesheet" href="../static/dist/datepicker.min.css">
    <link rel="stylesheet" type="text/css" href="../css/loader.css"/>
    <script src="../js/loader.js"></script>
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
        $cajabancoid = trim($cmp['cajabancoid']);
        $cajaid = trim($cmp['cajaid']);
        $divisionid = trim($cmp['divisionid']);

        if ($cajabancoid == '' or $divisionid == '' or $cajaid == '')
            die('<div class="error">No se Encontro Caja Banco Predeterminada.</div>');
    } ?>
</head>
<body>
<div id="load-content" class="loader-wrapper">
    <div id="id-loading" class="loader-small"></div>
</div>
<div id="buscar">
    <div id="title_form"><img src="../images/icon/list-view.png"/> <?= $_GET['tit'] ?></div>
    <div id="btns_accion">
        <div style="display:none">
            <input type="hidden" name="id_menu" id="id_menu" value="<?= intval($_GET['id']) ?>"/>
            <input type="hidden" name="dt_menu" id="dt_menu" value="<?= $pag ?>"/>
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
                <td class="bg_cab_title"><img src="../images/icon/Grid.png"/> Datos del Cliente.
                    <div class="rigth"></div>
                </td>
            </tr>
            <tr>
                <td>
                    <table width="100%" border="0" cellpadding="0" cellspacing="0">
                        <tr>
                            <td align="center">
                                <table border="0" cellpadding="0" cellspacing="0" class="styletable">
                                    <tr>
                                        <td width="59"></td>
                                        <td width="61">Estado</td>
                                        <td width="41">&nbsp;</td>
                                        <td width="84">N&uacute;mero</td>
                                        <td width="84">Asiento</td>
                                        <td width="84">Fecha</td>
                                        <td width="84">Tipo</td>
                                        <td colspan="2">Caja</td>
                                    </tr>
                                    <tr>
                                        <td>&nbsp;</td>
                                        <td>
                                            <input name="txt_cli_estado" type="text" id="txt_cli_estado" size="11"
                                                   maxlength="10" readonly>
                                        </td>
                                        <td>&nbsp;</td>
                                        <td>
                                            <input name="txt_cli_numero" type="text" id="txt_cli_numero" size="10"
                                                   maxlength="10" readonly>
                                        </td>
                                        <td>
                                            <input name="txt_cli_asiento" type="text" id="txt_cli_asiento" size="10"
                                                   maxlength="10" readonly>
                                        </td>
                                        <td>
                                            <input name="txt_ing_fecha" type="text" id="txt_ing_fecha"
                                                   value="<?php echo $_SESSION['us_fecha'] ? $_SESSION['us_fecha'] : date("d/m/Y"); ?>"
                                                   data-toggle="datepicker" readonly>
                                        </td>
                                        <td>
                                            <input name="txt_tipo" type="text" id="txt_tipo" value="CLI-IN" size="11"
                                                   maxlength="10" readonly>
                                        </td>
                                        <td colspan="2">
                                            <select name="cb_caja_banco" id="cb_caja_banco" style="width:16rem"
                                                    class="datos negrita" <?php if ($_SESSION['us_tipuser'] != 'administrador' and $_SESSION['us_tipuser'] != 'semiadministrador') { ?> disabled="disabled"<?php } ?>>
                                                <option value="" selected="selected">Seleccione</option>
                                                <?php
                                                require '../clases/cls_consulta.php';
                                                $obj = new cls_consulta;
                                                if ($obj->fn_ban_bancos()) $obj->combo($cajabancoid); ?>
                                            </select>
                                            <input type="hidden" id="id-caja-banco" value="<?= $cajabancoid ?>">

                                        </td>
                                    </tr>
                                    <tr>
                                        <td align="right">Cliente</td>
                                        <td>
                                            <form id="id-form-buscar-cli">
                                                <input type="hidden" name="action" value="detalle_deuda">
                                                <input name="cli_codigo" type="text" id="cli_codigo" size="10"
                                                       maxlength="10" class="txt-codigo" required autofocus
                                                       placeholder="Código">
                                            </form>
                                        </td>
                                        <td align="center">
                                            <input name="btn_cli_enviar" type="button" class="btn_enviar_cli_cod"
                                                   id="btn_cli_enviar" title="Enviar Codigo Cliente" value=" "
                                                   onclick="$('#id-form-buscar-cli').submit();">
                                        </td>
                                        <td colspan="4">
                                            <input name="txt_cli_nombre" type="text" id="txt_cli_nombre"
                                                   size="62" maxlength="58" class="negrita"
                                                   placeholder="Buscar Cliente.">
                                        </td>
                                        <td width="63">
                                        </td>
                                        <td width="111">
                                            <div id="id_cliente" style="display:none" align="left"></div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td align="right">Vendedor</td>
                                        <td>
                                            <input name="txt_vend_codigo" type="text" id="txt_vend_codigo" size="10"
                                                   maxlength="10" readonly class="negrita" placeholder="Código"/>
                                        </td>
                                        <td align="center">
                                            <div id="load_img"></div>
                                        </td>
                                        <td colspan="4">
                                            <input name="txt_vend_nombre" type="text" id="txt_vend_nombre"
                                                   size="62" maxlength="58" readonly class="negrita"
                                                   placeholder="Nombres del Vendedor"/>
                                        </td>
                                        <td colspan="2">&nbsp;</td>
                                    </tr>
                                </table>
                            </td>
                            <td>

                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
            <tr>
                <td class="bg_cab_title"><img src="../images/icon/Grid.png"/> Detalle de Valores Recibidos.</td>
            </tr>
            <tr>
                <td>
                    <table width="100%" border="0" cellspacing="0" cellpadding="0">
                        <tr>
                            <td>
                                <div class="tabledetalle">
                                    <table border="0" cellpadding="0" cellspacing="0" class="styletable_detalle"
                                           id="cli_table_cab_deuda">
                                        <thead>
                                        <tr>
                                            <th width="13px">N&deg;</th>
                                            <th width="110px">Tipo</th>
                                            <th width="110px">Fecha</th>
                                            <th width="260px">Cliente</th>
                                            <th width="30px">Valor</th>
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
                                        <td>
                                            <!--input name="btn_agregar" type="submit" class="btn" id="btn_agregar"
                                                   value="Nuevo"-->
                                        </td>
                                        <td>
                                            <!--input name="btn_quitar" type="submit" class="btn" id="btn_quitar"
                                                   value="Quitar"-->
                                        </td>
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
                                <th width="8px">N#</th>
                                <th width="35px">Emisi&oacute;n</th>
                                <th width="35px">Vence</th>
                                <th width="35px">Tipo</th>
                                <th width="50px">N&uacute;mero</th>
                                <th width="180px">Concepto</th>
                                <th width="35px">Saldo</th>
                                <th width="35px">Abono</th>
                                <th width="35px">NuevoSaldo</th>
                            </tr>
                            </thead>
                            <tbody id="det" style="font-size: 12px">
                        </table>
                    </div>
                </td>
            </tr>
            <tr>
                <td>
                    <table border="0" align="right" cellpadding="0" cellspacing="0" id="table_det_totales">
                        <tr>
                            <th align="center" width="120">TOTAL DEUDA</th>
                            <th align="center" width="120">T.CREDITO</th>
                            <th align="center" width="120">t.ABONO</th>
                            <th align="center" width="120">nUEVO SALDO</th>
                        </tr>
                        <tr>
                            <td align="center">
                                <input name="txt_total_deuda" type="text" class="totales"
                                       id="txt_total_deuda" value="$0.00" size="10" readonly>
                            </td>
                            <td align="center">
                                <input name="txt_total_cred" type="text" id="txt_total_cred" value="$0.00"
                                       size="10" readonly>
                            </td>
                            <td align="center">
                                <input name="txt_total_abono" type="text" id="txt_total_abono"
                                       value="$0.00" size="10" readonly>
                            </td>
                            <td align="center">
                                <input name="txt_nuevo_saldo" type="text" id="txt_nuevo_saldo"
                                       value="$0.00" size="10" readonly>
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
            <tr>
                <td class="bg_cab_title"><img src="../images/icon/Grid.png"/> Nota.</td>
            </tr>
            <tr>
                <td>
                    <table width="100%" border="0" cellspacing="0" cellpadding="0" class="styletable">
                        <tr>
                            <td width="9%">
                                <input name="btn_autollenar" type="button" class="green" id="btn_autollenar"
                                       value="F1: Autollenar &gt;&gt;"/></td>
                            <td width="91%">
                                <input name="txt_nota" type="text" class="datos" id="txt_nota" size="120"
                                       maxlength="80">
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
            <tr>
                <td class="bg_cab_title"><img src="../images/icon/Grid.png"/> Observaciones.</td>
            </tr>
            <tr>
                <td>
                    <div class="relleno">
                        <textarea name="textarea" cols="130" rows="2"></textarea>
                    </div>
                </td>
            </tr>
        </table>
    </div>
</div>

<script id="js-template-det-deuda" type="text/x-jsrender">
{{* window.x = 1}}
{{for deuda}}
   <tr>
        <td align="center">{{*: x++}}</td>
        <td align="center">{{:fecha}}</td>
        <td align="center">{{:vencimiento}}</td>
        <td align="center">{{:tipo}}</td>
        <td align="center"><b>{{:numero}}</b></td>
        <td>{{:detalle}}</td>
        <td align="right"><b>$ {{:saldo.toFixed(2)}}<b></td>
        <td align="right"><b>
           {{if abono > 0}}
            $ {{:abono.toFixed(2)}}
           {{else}}
            -
           {{/if}}
         </b></td>
        <td align="right"><b>$ {{:nuevosaldo.toFixed(2)}}<b></td>
   </tr>
{{/for}}




</script>

<script id="js-template-det-abono" type="text/x-jsrender">
{{* window.x = 1}}
{{for pagos}}
   <tr>
        <td align="center">{{*: x++}}</td>
        <td align="center"><b>{{:tipo}}</b></td>
        <td align="center"><b>{{:fecha}}</b></td>
        <td align="center"><b>{{:cliente}}</b></td>
        <td align="center">
          <input type="number" class="pago" value="{{:valor}}">
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
                return "../app/cliente/ajax/cliente.php?action=deuda_clibuscar&criterio=" + criterio;
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
                    $('#btn_cli_enviar').click();
                }
            },
            ajaxSettings: {
                dataType: "json",
                beforeSend: function () {
                }
            },
        };

        $("#txt_cli_nombre").easyAutocomplete(options);

        shortcut.add("F1", function () {
            $("#btn_autollenar").click();
        });
        shortcut.add("Ctrl+g", function () {
            $("#btn_guardar").click();
        });
        shortcut.add("Esc", function () {
            $(".cerrar").click();
        });

        $('#cb_caja_banco').change(function () {
            var cajabancoid = $(this).val();
            $('#id-caja-banco').val(cajabancoid);
            $('#cli_codigo').focus();
        });

        $('#cb_caja_banco').keypress(function (e) {
            if (e.keyCode == 13 && $(this).val().trim() != '') {
                $('#cli_codigo').focus();
            }
        });

        $('#id-form-buscar-cli').on({
            submit: function (e) {
                e.preventDefault();

                pago.reset();

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

                        pago.factura.cliente = cliente;
                        pago.factura.deuda = data.deuda;
                        pago.actualizar();

                        var item = {
                            tipo: 'EFECTIVO',
                            fecha: $('#txt_ing_fecha').val(),
                            cliente: cliente.nombre,
                            girador: cliente.nombre,
                            numero: '',
                            cuenta: '',
                            banco: '',
                            valor: '',
                            valor_base: 0
                        }
                        pago.add(item);
                        $('#cli_table_cab_deuda #det input:first').focus();

                    } else {
                        alert(data.error);
                    }
                }).fail(function (jqXHR, textStatus) {
                    alert(textStatus);
                });
            }
        });

        $("#btn_guardar").on('click', function (e) {
            e.preventDefault();

            if (Object.keys(pago.factura.cliente).length <= 0) {
                alert('No ha Seleccionado Ningun Cliente.');
                $('#cli_codigo').focus();
                return;
            }

            /*if (pago.factura.cliente.id === undefined) {
                alert('No ha Seleccionado ningun Cliente.');
                $('#cli_codigo').focus();
                return;
            }*/

            var division = $('#id-divisionid');
            var banco = $('#id-caja-banco');
            var fecha = $('#txt_ing_fecha');
            var tipo = $('#txt_tipo');
            var nota = $('#txt_nota');

            if (division.val() == '') {
                alert('No se Encontró ID división.');
                return;
            }

            if (banco.val() == '') {
                alert('No se Encontró ID Banco.');
                banco.focus();
                return;
            }

            var inputabono = $('#cli_table_cab_deuda #det input:first');
            if (inputabono.length <= 0) {
                alert('No ha Realizado Ningun Abono.');
                return;
            }

            if (inputabono.val() <= 0 || inputabono.val() == '') {
                alert('Corrija el Valor del Abono');
                inputabono.focus();
                return;
            }

            pago.actualizar();

            if (pago.factura.totsaldo <= 0) {
                alert('El cliente no registra Deuda');
                return;
            }

            if (pago.factura.totabono <= 0) {
                alert('Ingrese el Valor del Abono');
                return;
            }

            var resp = confirm('Confirme realizar el Pago.');
            if (!resp) return;

            pago.factura.divisionid = division.val();
            pago.factura.bancoid = banco.val();
            pago.factura.fecha = fecha.val();
            pago.factura.fechacheque = fecha.val();
            pago.factura.tipo = tipo.val();
            pago.factura.detalle = nota.val();
            pago.factura.cajaid = $('#id-cajaid').val();
            pago.factura.valor = pago.factura.totabono;
            pago.factura.valor_base = pago.factura.totabono;
            pago.factura.deudorid = pago.factura.cliente.id;

            $.ajax({
                url: '../app/cliente/ajax/cliente.php',
                data: {action: 'ingreso_dinero', factura: JSON.stringify(pago.factura)},
                method: 'POST',
                dataType: 'json',
            }).done(function (data) {
                console.log(data);
                if (data.resp == true) {
                    alert('Pago se guardo correctamente.');
                    pago.reset();
                    $('#cli_codigo').val('');
                    $('#cli_codigo').focus();
                } else {
                    alert(data.error);
                }
            }).fail(function (jqXHR, textStatus) {
                alert(textStatus);
            });
        });

        $("#cli_table_cab_deuda #det").on('change', '.pago', function (e) {
            e.preventDefault();
            var abono = parseFloat($(this).val());
            var totdeuda = pago.factura.totdeuda;

            if (abono > totdeuda) {
                alert('El Abono no debe ser mayor a la Deuda');
                $(this).focus();
                return;
            }
            if (abono > 0) {
                pago.pagar(abono);
                pago.autonota(abono);
            } else {
                alert('El valor del Abono debe ser mayor a Cero');
                $(this).focus();
            }
        });

        $("#btn_autollenar").on('click', function (e) {
            e.preventDefault();
            var abono = pago.factura.pagos[0].valor;
            pago.autonota(abono);
        });

        clienteinfo(window);

        getUserIP(function (ip) {
            var pc = 'IP:' + ip + '-SO: ' + jscd.os + ' ' + jscd.osVersion;
            pc += '- Navegador: ' + jscd.browser + ' ' + jscd.browserMajorVersion;
            pago.factura.pc = pc;
        });

    });

    var pago = {
        factura: {
            divisionid: '',
            fecha: '',
            fechacheque: '',
            cheque: '',
            tipo: '',
            bancoid: '',
            cajaid: '',
            ctacxid: '',
            cliente: new Object,
            beneficiario: '',
            deudorid: '',
            totdeuda: 0,
            totcredito: 0,
            totabono: 0,
            totsaldo: 0,
            descuento: 0,
            financiero: 0,
            rfir: 0,
            rfiva: 0,
            faltante: 0,
            sobrante: 0,
            valor: 0,
            valor_base: 0,
            detalle: '',
            nota: '',
            observacion: '',
            pagos: [],
            deuda: [],
            pc: ''
        },

        add: function (item) {
            this.factura.pagos.push(item);
            this.render_abono();
        },

        pagar: function (abono) {
            this.factura.pagos[0].valor = abono;
            this.factura.pagos[0].valor_base = abono;

            for (var i in this.factura.deuda) {
                var nuevo_abono = 0;
                var nuevo_saldo = 0;
                var saldo = this.factura.deuda[i].saldo

                if (abono > saldo) {
                    nuevo_abono = saldo;
                    abono = Math.round((abono - saldo) * 100) / 100;

                } else {
                    if (abono > 0) {
                        nuevo_saldo = Math.round((saldo - abono) * 100) / 100;
                        nuevo_abono = abono;
                        abono -= abono;
                    } else {
                        nuevo_abono = abono;
                        nuevo_saldo = saldo;
                    }
                }
                this.factura.deuda[i].abono = nuevo_abono;
                this.factura.deuda[i].nuevosaldo = nuevo_saldo;
            }
            this.actualizar();
        },

        autonota: function (abono) {
            var cliente = this.factura.cliente;
            var nota = cliente.cod + ' : ' + cliente.nombre + ' Cancela : $' + abono;
            $('#txt_nota').val(nota);
            $('#txt_nota').focus();
        },
        actualizar: function () {

            this.factura.totdeuda = 0;
            this.factura.totcredito = 0;
            this.factura.totabono = 0;
            this.factura.totsaldo = 0;

            for (var i in this.factura.deuda) {
                if (typeof this.factura.deuda[i].saldo === 'string') {
                    this.factura.deuda[i].saldo = parseFloat(this.factura.deuda[i].saldo);
                }
                if (typeof this.factura.deuda[i].nuevosaldo === 'string') {
                    this.factura.deuda[i].nuevosaldo = parseFloat(this.factura.deuda[i].nuevosaldo);
                }
                if (typeof this.factura.deuda[i].abono === 'undefined' || this.factura.deuda[i].abono === null) {
                    this.factura.deuda[i].abono = 0
                }

                if (this.factura.ctacxid == '') {
                    this.factura.ctacxid = this.factura.deuda[i].ctacxid;
                }
                this.factura.totdeuda += this.factura.deuda[i].saldo;
                this.factura.totcredito += 0;
                this.factura.totabono += this.factura.deuda[i].abono;
                this.factura.totsaldo += this.factura.deuda[i].nuevosaldo;
            }

            this.render_deuda();

            $('#txt_total_deuda').val('$' + this.factura.totdeuda.toFixed(2));
            $('#txt_total_cred').val('$' + this.factura.totcredito.toFixed(2));
            $('#txt_total_abono').val('$' + this.factura.totabono.toFixed(2));
            $('#txt_nuevo_saldo').val('$' + this.factura.totsaldo.toFixed(2));

        },
        render_abono: function () {
            $.views.settings.allowCode(true);
            var tmpl = $.templates("#js-template-det-abono"); // Get compiled template
            var html = tmpl.render(this.factura);      // Render template using data - as HTML string
            $("#cli_table_cab_deuda #det").html(html);
        },
        render_deuda: function () {
            $.views.settings.allowCode(true);
            var tmpl = $.templates("#js-template-det-deuda"); // Get compiled template
            var html = tmpl.render(this.factura);      // Render template using data - as HTML string
            $("#cli_table_det_deuda #det").html(html);
        },
        reset: function () {
            this.factura.fecha = '';
            this.factura.fechacheque = '';
            this.factura.cheque = '';
            this.factura.ctacxid = '';
            this.factura.cliente = new Object;
            this.factura.deudorid = '';
            this.factura.bancoid = '';
            this.factura.descuento = 0;
            this.factura.totdeuda = 0;
            this.factura.totcredito = 0;
            this.factura.totabono = 0;
            this.factura.totsaldo = 0;
            this.factura.valor = 0;
            this.factura.valor_base = 0;
            this.factura.detalle = '';
            this.factura.pagos = [];
            this.factura.deuda = [];

            $('#txt_cli_nombre').val('');
            $('#txt_vend_codigo').val('');
            $('#txt_vend_nombre').val('');
            $('#txt_nota').val('');

            $("#cli_table_cab_deuda #det").html('');
            $("#cli_table_det_deuda #det").html('');
        }
    };

</script>
</body>
</html>