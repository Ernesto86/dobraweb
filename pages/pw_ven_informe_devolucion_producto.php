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

    require '../clases/cls_consulta.php';
    $obj = new cls_consulta; ?>

    <link rel="stylesheet" href="../css/jquery.ui.all.css">
    <link rel="stylesheet" href="../css/elements.css">
    <script src="../js/jquery.min.js"></script>
    <script src="../js/jquery.treeview.js" type="text/javascript"></script>
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
            $('#txt_codigo').focus();
            $("#cont_grupos").load('pw_grupo_treeview.php');
            $('#btn_grupos').click(function () {
                $(".float-content").css('top', '30%');
                $(".float-content").css('left', '155px');
                $(".float-content").css('width', '235px');
                $("#cont_grupos").css('height', '480px');
                $("#cont_grupos").css('overflow', 'auto');
                $(".float-content").show();
            });
            $(".cerrar").click(function () {
                $(".float-content").hide("fat");
                event.preventDefault();
            });

            $("#cb_bodega").change(function () {
                var str = $('#cb_bodega option:selected').text();
                $("#dtbodega").val(str);
            });

            $("#cb_usuario").change(function () {
                var str = $('#cb_usuario option:selected').text();
                $("#dtuser").val(str);
            });

            $("#cb_producto").change(function () {
                var str = $('#cb_producto option:selected').text();
                $("#dtprod").val(str);
            });

        });
        function fn_cli_inf_nota_venta_premio() {
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
                url: 'pw_ven_informe_det_devolucion_producto.php',
                data: $('#frm_buscar .dats').serialize(),
                type: "POST",
                async: true,
                cache: false,
                contentType: "application/x-www-form-urlencoded",
                ifModified: false,
                beforeSend: function () {
                    $('#load_img').addClass('loading');
                },
                success: function (resp) {
                    $('#load_img').removeClass('loading');
                    $('#contenedor').html(resp);
                    resp.stopPropagation();
                },
                error: function () {
                    $('#load_img').removeClass('loading');
                    alert('Fallo Conectando con el servidor');
                }
            });

            stopPropagation();
        }
        function fn_export_excel() {
            if ($('#dt_data_table').length == 0) {
                return;
            }
            window.open('data:application/vnd.ms-excel,' + encodeURIComponent($('#dt_data_table').html()));
            event.preventDefault();
        }
        function fn_reporte(tip_rpt) {
            if ($('#txt_fech_fin').val() == '') {
                alert('Ingrese Fecha Final');
                $('#txt_fech_fin').focus();
                return;
            }
            $('#rpt').val(tip_rpt);
            $('#frm_buscar').attr('action', 'pw_ven_informe_det_devolucion_producto.php');
            $('#frm_buscar').submit();
        }
    </script>
</head>
<body>
<div id="buscar">
    <div class="relleno">
        <form id="frm_buscar" name="frm_buscar" method="post" action="javascript:fn_cli_inf_nota_venta_premio();">
            <input type="hidden" name="rpt" id="rpt" value=""/>
            <input type="hidden" name="dtbodega" id="dtbodega" value="" class="dats"/>
            <input type="hidden" name="dtuser" id="dtuser" value="" class="dats"/>
            <input type="hidden" name="dtprod" id="dtprod" value="" class="dats"/>
            <table border="0" cellpadding="0" cellspacing="0" id="table_buscar">
                <tr>
                    <td><img src="../images/icon/list-view.png"/></td>
                    <td>
                        <div align="right">Grupo<span></span></div>
                    </td>
                    <td><input name="txt_codigo" type="text" class="txt-codigo dats" id="txt_codigo" size="5"
                               maxlength="7"/></td>
                    <td>
                        <input name="btn_grupos" type="button" class="btn" id="btn_grupos" value="Buscar"
                               title="Buscar Grupos"/>
                        <input type="hidden" id="dt_grupo" name="dt_grupo" value="" class="dats"/></td>
                    <td class="separador">&nbsp;</td>
                    <td>
                        <div align="right">Fecha</div>
                    </td>
                    <td><input name="txt_fech_ini" type="text" id="txt_fech_ini" value="<?= date('d/m/Y'); ?>" size="7"
                               class="fecha dats"/></td>
                    <td>
                        <div align="right">Hasta</div>
                    </td>
                    <td><input name="txt_fech_fin" type="text" id="txt_fech_fin" value="<?php echo date('d/m/Y'); ?>"
                               size="7" class="fecha dats"/></td>
                    <td class="separador">&nbsp;</td>
                    <td><select name="cb_bodega" id="cb_bodega" style="width:130px" class="dats" title="Bodega">
                            <option value="" selected="selected">Selec.Bodega</option>
                            <?php if ($obj->fn_inv_bodega()) $obj->combo(); ?>
                        </select></td>
                    <td><select name="cb_usuario" id="cb_usuario" style="width:100px" class="dats" title="Usuario">
                            <option value="" selected="selected">Selec.Usuario</option>
                            <?php
                            if ($obj->fn_usuario_web()) $obj->combo();
                            ?>
                        </select></td>
                    <td><select name="cb_producto" id="cb_producto" style="width:150px" class="dats">
                            <option value="" selected="selected">Seleccione Producto.</option>
                            <?php if ($obj->fn_inv_producto()) $obj->combo(); ?>
                        </select></td>
                    <td><input type="submit" name="btn_premios" id="btn_premios" value="Devoluciones&gt;&gt;"
                               class="green"/></td>
                    <td>
                        <div id="load_img"></div>
                    </td>
                    <td>&nbsp;</td>
                    <td><input type="button" name="btn_report_html" value="Imprimir" class="report_html"
                               id="btn_report_html" onclick="fn_reporte('HTML');"/></td>
                    <td><input type="button" name="Submit" value="Excel" class="report_excel"
                               onclick="fn_export_excel();"/></td>
                    <td><input type="button" name="btn_pdf" id="btn_pdf" value="PDF" class="btn"
                               onclick="fn_reporte('PDF');"/></td>
                </tr>
            </table>
        </form>
    </div>
</div>
<div id="contenedor">
    <p align="center">Detalle Informe Cartera Vencida..</p></div>
<div class="float-content" style="display:none">
    <div class="cerrar"><a href="#" title="Cerrar"></a></div>
    <div id="cont_grupos"></div>
</div>
</body>
</html>