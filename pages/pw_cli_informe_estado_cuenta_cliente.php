<?php
session_start();
if (!isset($_SESSION['us_id']) or !isset($_SESSION['us_cuenta']) or !isset($_SESSION['us_idtipo'])) {
    die('Inicie Session');
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <title>Titulo de la WEB</title>
    <meta charset="UTF-8">
    <title>Sistema MundoText</title>
    <title>Documento sin t&iacute;tulo</title>
    <link rel="stylesheet" type="text/css" href="../css/parametros_buscar.css">
    <link rel="stylesheet" type="text/css" href="../css/flotant_busc.css">
    <?php
    require '../clases/cls_my_transaccion.php';
    $otrans = new cls_my_transaccion;
    $otrans->transacciones($_GET['id']);
    $trans = $otrans->campos();
    unset($otrans);
    if (!$trans['ing'])
        die('<div class="error">No cuenta con Permisos.</div>');
    ?>

    <link rel="stylesheet" href="../css/jquery.ui.all.css">
    <link rel="stylesheet" href="../css/elements.css">
    <!--script src="//ajax.googleapis.com/ajax/libs/jquery/1.6.1/jquery.min.js"></script-->
    <script src="../js/jquery.min.js"></script>
    <script src="../js/jquery.ui.core.js"></script>
    <script src="../js/jquery.ui.datepicker.js"></script>
    <script src="../js/jquery.ui.datepicker-es.js"></script>
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
        $(function () {
            $("#txt_codigo").focus();           

            $('#txt_buscar').keyup(function (e) {
                e.preventDefault();
                $('#dt_clientes #detalle').load('pw_det_busc_cliente.php', {
                    desc: $(this).val(),
                    opc: $("input[name=radiobutton]:checked").val()
                });
            });

            $("#btn_cliente").click(function () {
                $(".float-content").show();
                $('#txt_buscar').focus();
            });

            $(".cerrar").click(function () {
                $(".float-content").hide();
            });
        });
        function fn_select_cod(codigo) {
            $('#txt_codigo').val(codigo);
            $(".float-content").hide();
            $('#txt_codigo').focus();
        }
        function fn_cli_estado_cuenta() {
            if ($('#txt_codigo').val() == '') {
                alert('Ingrese Codigo del Cliente');
                $('#txt_codigo').focus();
                return;
            }
            if ($('#txt_fech_ini').val() == '') {
                alert('Ingrese Fecha Inicial');
                $('#txt_fech_ini').focus();
                return;
            }
            if ($('#txt_fech_fin').val() == '') {
                alert('Ingrese Fecha Final');
                $('#txt_fech_fin').focus();
                return;
            }
            $('#contenedor').html('');
            $.ajax({
                url: 'pw_cli_det_estado_cuenta_cliente.php',
                data: $('#frm_buscar .dats').serialize(),
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
                success: function (resp) {
                    $('#load_img').removeClass('loading');
                    $('#contenedor').html(resp);
                },
                error: function () {
                    $('#load_img').removeClass('loading');
                    alert('Fallo Conectando con el servidor');
                }
            });
            return;
        }
        function fn_export_excel() {
            if ($('#dt_data_table').length == 0) {
                return;
            }
            window.open('data:application/vnd.ms-excel,' + encodeURIComponent($('#data').html()));

        }
        function fn_report_html(tip_rpt) {
            if ($('#txt_codigo').val() == '') {
                alert('Ingrese Codigo.');
                $('#txt_codigo').focus();
                return;
            }
            if ($('#txt_fech_fin').val() == '') {
                alert('Ingrese Fecha Final');
                $('#txt_fech_fin').focus();
                return;
            }
            $('#rpt').val(tip_rpt);
            $('#frm_buscar').attr('action', 'pw_cli_det_estado_cuenta_cliente.php');
            $('#frm_buscar').submit();
        }
    </script>
</head>
<body>
<div id="buscar">
    <div class="relleno">
        <form id="frm_buscar" name="frm_buscar" method="post" action="javascript:fn_cli_estado_cuenta();">
            <input type="hidden" name="rpt" id="rpt" value=""/>
            <table border="0" cellpadding="0" cellspacing="0" id="table_buscar">
                <tr>
                    <td><img src="../images/icon/list-view.png"/></td>
                    <td>
                        <div align="right">C&oacute;d.Cli</div>
                    </td>
                    <td><input name="txt_codigo" type="text" id="txt_codigo" size="8" class="dats txt-codigo"/></td>
                    <td>
                        <input name="btn_cliente" type="button" class="btn" id="btn_cliente" value="Buscar"
                               title="Buscar Clientes"/></td>
                    <td class="separador">&nbsp;</td>
                    <td>Periodo:</td>
                    <td><input name="txt_fech_ini" type="text" id="txt_fech_ini" value="<?php
                        $fecha = date('Y-m-j');
                        $fech_ini = strtotime('-4 months', strtotime($fecha));
                        $fech_ini = date('j/m/Y', $fech_ini);

                        $fech_fin = strtotime('1 months', strtotime($fecha));
                        $fech_fin = date('j/m/Y', $fech_fin);

                        echo $fech_ini;
                        ?>" size="11" class="fecha dats"/></td>
                    <td>
                        <div align="right">Hasta</div>
                    </td>
                    <td><input name="txt_fech_fin" type="text" id="txt_fech_fin" value="<?php echo $fech_fin; ?>"
                               size="11" class="fecha dats"/></td>
                    <td class="separador">&nbsp;</td>
                    <td><input type="submit" name="btn_estado_cuenta" id="btn_estado_cuenta"
                               value="Estado de Cuenta&gt;&gt;" class="green" title="Estado de Cuenta Cliente"/></td>
                    <td>
                        <div id="load_img"></div>
                    </td>
                    <td>
                        <div id="load_dt">
                            <div id="load_carg" style="display:none">Cargando...</div>
                        </div>
                    </td>
                    <td><input type="button" name="btn_report_html" value="Imprimir" class="report_html"
                               id="btn_report_html" onclick="fn_report_html('HTML');"/></td>
                    <td><input type="button" name="Submit" value="Excel" class="report_excel"
                               onclick="fn_export_excel();"/></td>
                    <td><input type="button" name="btn_pdf" id="btn_pdf" value="PDF" class="btn"
                               onclick="fn_report_html('PDF');"/></td>
                </tr>
            </table>
        </form>
    </div>
</div>
<div id="contenedor"><p align="center">Detalle de Estado de Cuenta..</p></div>
<div class="float-content" style="display: none;">
    <div class="cerrar"><a href="#" title="Cerrar"></a></div>
    <div id="cont_buscar">
        <?php include 'pw_buscar_cliente.php';?>
    </div>
</div>
</body>
</html>