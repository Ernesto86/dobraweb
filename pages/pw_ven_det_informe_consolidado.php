<?php session_start();
if (!isset($_SESSION['us_id']) or !isset($_SESSION['us_cuenta']) or !isset($_SESSION['us_idtipo'])) {
    die('Inicie Session');
}
if ($_POST['rpt'] == 'PDF') ob_start();

require '../clases/cls_venta_factura.php';
$obj = new cls_venta_factura;
$obj->s_codigo($_POST['txt_codigo']); ?>
<!DOCTYPE html>
<html lang="es">
<head>
    <title>Título de la WEB</title>
    <meta charset="UTF-8">
    <title>Sistema MundoText</title>
    <?php if ($_POST['rpt'] == 'HTML' or $_POST['rpt'] == 'PDF') { ?>
        <link rel="stylesheet" type="text/css" href="../css/table_reporte.css"/>
    <?php } ?>
    <script language="javascript">
        $(document).ready(function () {
            $('.dynamic').fixedtableheader();
        });
    </script>
</head>
<body>
<div class="content">
    <div id="det_datos_cliente">
        <div class="cab_title"><?php if ($_POST['rpt'] == 'HTML' or $_POST['rpt'] == 'PDF') { ?>
                <div><img src="../images/logo_150px.png" width="75" height="72"/></div>
                <img src="../images/accion/print.png" title="Imprimir" onclick="window.print();"
                     class="imprimir"/><?php } ?><img src="../images/icon/234 PagesView4.png" height="14" width="14"/>
            VENTAS - INFORME CONSOLIDADO DE VENTAS.
        </div>
        <?php if ($_POST['rpt'] == 'HTML' or $_POST['rpt'] == 'PDF') { ?>
            <p class="cab_title">Fecha del Corte
                : <?PHP echo $_POST['txt_fech_ini'] . ' - ' . $_POST['txt_fech_fin']; ?></p>
        <?php } else { ?>
            <table width="100%" border="0" cellspacing="0" cellpadding="0" id="table_detcliente">
                <tr>
                    <th colspan="9">Detalle Consolidado de Ventas</th>
                </tr>
                <tr>
                    <td width="6%" align="right"><strong>Codigo:</strong></td>
                    <td width="15%">
                        <div class="dtempleado"><?php echo $_POST['txt_codigo']; ?></div>
                    </td>
                    <td width="13%" align="right">&nbsp;</td>
                    <td colspan="6">&nbsp;</td>
                </tr>
                <tr>
                    <td align="right"><strong>Periodos:</strong></td>
                    <td><?php echo $_POST['txt_fech_ini'] . ' - ' . $_POST['txt_fech_fin'] ?></td>
                    <td align="right">&nbsp;</td>
                    <td width="7%" align="right">&nbsp;</td>
                    <td width="16%" align="left">&nbsp;</td>
                    <td width="5%" align="left"></td>
                    <td width="14%" align="right">&nbsp;</td>
                    <td>&nbsp;</td>
                    <td width="13%" align="right">&nbsp;</td>
                </tr>
                <tr>
                    <td colspan="9">
                        <div class="relleno"></div>
                    </td>
                </tr>
                <tr>
                    <th colspan="9">Transacciones.</th>
                </tr>
            </table>

        <?php } ?>
    </div>
    <div id="dt_data_table_consolidado">
        <table cellpadding="0" class="dynamic styled with-prev-next" style="overflow:scroll;">
            <thead>
            <tr>
                <th>N&deg;</th>
                <th>C&oacute;digo</th>
                <th>Vendedor</th>
                <th>Ventas</th>
            </tr>
            </thead>
            <tbody><?php
            $obj->s_fecha_1($_POST['txt_fech_ini']);
            $obj->s_fecha_2($_POST['txt_fech_fin']);
            if ($obj->fn_emp_vendedor()) {
                $obj->fn_informe_venta_consolidado();
            }
            ?>
            </tbody>
        </table>
    </div>
    <div id="piepag"></div>
</div>
<?php
if ($_POST['rpt'] == 'PDF') {
    require_once("dompdf/dompdf_config.inc.php");
    $dompdf = new DOMPDF();
    $dompdf->load_html(ob_get_clean());
    $dompdf->render();
    $dompdf->output();
    header('Content-type: application/pdf'); //ponemos la cabecera para PDF
    echo $dompdf->output();
}
?>
</body>
</html>