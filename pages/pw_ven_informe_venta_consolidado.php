<?php session_start();
if (!isset($_SESSION['us_id']) or !isset($_SESSION['us_cuenta']) or !isset($_SESSION['us_idtipo'])) {
    die('Inicie Session');
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <title>Título de la WEB</title>
    <meta charset="UTF-8">
    <title>Sistema MundoText</title>
    <link rel="stylesheet" type="text/css" href="../css/parametros_buscar.css">
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
    <script src="../js/thead_table.js"></script>
    <script language="javascript">
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
            $(".float-content").hide();
            $('#txt_codigo').focus();
            $('#btn_cliente').click(function () {
                $(".float-content").show();
                $("#cont_buscar").load('pw_buscar_empleado.php');
            });
            $(".cerrar").click(function () {
                $(".float-content").hide("fat");
            });
        });
        function fn_select_cod(codigo) {
            $('#txt_codigo').val(codigo);
            $(".float-content").hide("slow");
            $('#txt_codigo').focus();
        }
        function fn_cli_inf_saldos() {
            if ($('#txt_fech_fin').val() == '') {
                alert('Ingrese Fecha Final');
                $('#txt_fech_fin').focus();
                return;
            }
            $('#contenedor').html('');
            $.ajax({
                url: 'pw_ven_det_informe_consolidado.php',
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
        }
        function fn_export_excel() {
            if ($('#dt_data_table_consolidado').length == 0) return;

            window.open('data:application/vnd.ms-excel,' + encodeURIComponent($('#dt_data_table_consolidado').html()));

        }
        function fn_report_html(tip_rpt) {
            if ($('#txt_fech_fin').val() == '') {
                alert('Ingrese Fecha Final');
                $('#txt_fech_fin').focus();
                return;
            }
            $('#rpt').val(tip_rpt);
            $('#frm_buscar').submit();
        }
    </script>
</head>
<body>
<div id="buscar">
    <div class="relleno">
        <form id="frm_buscar" name="frm_buscar" method="post" action="pw_ven_det_informe_consolidado.php">
            <input type="hidden" name="rpt" id="rpt" value=""/>
            <table border="0" cellpadding="0" cellspacing="0" id="table_buscar">
                <tr>
                    <td><img src="../images/icon/list-view.png"/></td>
                    <td>
                        <div align="right">C&oacute;digo</div>
                    </td>
                    <td><input name="txt_codigo" type="text" id="txt_codigo" size="8" class="dats txt-codigo"/></td>
                    <td><input name="btn_cliente" type="button" class="btn" id="btn_cliente" value="Buscar"
                               title="Buscar Vendedor"/></td>
                    <td class="separador">&nbsp;</td>
                    <td>
                        <div align="right">Fecha</div>
                    </td>
                    <td><input name="txt_fech_ini" type="text" id="txt_fech_ini" value="<?php $fecha = date('Y-m-j');
                        $nuevafecha = strtotime('-1 months', strtotime($fecha));
                        $nuevafecha = date('j/m/Y', $nuevafecha);
                        echo $nuevafecha; ?>" size="11" class="fecha dats"/></td>
                    <td>
                        <div align="right">Hasta</div>
                    </td>
                    <td><input name="txt_fech_fin" type="text" id="txt_fech_fin" value="<?php echo date('d/m/Y'); ?>"
                               size="11" class="fecha dats"/></td>
                    <td class="separador">&nbsp;</td>
                    <td><input type="button" name="btn_inf_saldos" id="btn_inf_saldos"
                               value="Consolidado Ventas&gt;&gt;" class="green" onclick="fn_cli_inf_saldos();"/></td>
                    <td>&nbsp;</td>
                    <td>
                        <div id="load_img"></div>
                    </td>
                    <td>&nbsp;</td>
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
<div class="float-content">
    <div class="cerrar"><a href="#" title="Cerrar"></a></div>
    <div id="cont_buscar"></div>
</div>
</body>
</html>