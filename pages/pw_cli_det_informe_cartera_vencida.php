<?php session_start();
if (!isset($_SESSION['us_id']) or !isset($_SESSION['us_cuenta']) or !isset($_SESSION['us_idtipo'])) {
    die('Inicie Session');
}
if ($_POST['rpt'] == 'PDF') ob_start();

require '../clases/cls_cli_informe_deuda.php';
$obj = new cls_cli_informe_deuda; ?>
<!DOCTYPE html>
<html lang="es">
<head>
    <title>Título de la WEB</title>
    <meta charset="UTF-8">
    <title>Sistema MundoText</title>
    <?php if ($_POST['rpt'] == 'HTML' or $_POST['rpt'] == 'PDF') { ?>
        <link rel="stylesheet" type="text/css" href="../css/table_reporte.css"/>
        <script src="../js/jquery.min.js"></script>
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
                <?php if ($_POST['rpt'] == 'HTML') { ?><img src="../images/accion/print.png" title="Imprimir"
                                                            onclick="window.print();" class="imprimir"/><?php }
            } ?><img src="../images/icon/234 PagesView4.png" height="14" width="14"/> CUENTAS POR COBRAR - INFORME DE
            CARTERA VENCIDA
        </div>
        <table width="100%" border="0" cellspacing="0" cellpadding="0" id="table_detcliente">
            <tr>
                <th colspan="2">Informe.</th>
            </tr>
            <tr>
                <td width="9%" align="right"><strong>Fecha : </strong></td>
                <td width="91%">
                    <div class="dtempleado"><?php echo $_POST['txt_fech_ini'] . ' a ' . $_POST['txt_fech_fin']; ?></div>
                </td>
            </tr>
            <tr>
                <td align="right"><strong>Categoria</strong></td>
                <td>
                    <div class="dtempleado"><?php echo $_POST['dt_grupo']; ?></div>
                </td>
            </tr>
            <tr>
                <td colspan="2">
                    <div class="relleno"></div>
                </td>
            </tr>
            <tr>
                <th colspan="2">Detalle de Transacciones.</th>
            </tr>
        </table>
    </div>
    <div id="dt_data_table">
        <table border="0" cellpadding="0" cellspacing="0" class="dynamic styled with-prev-next"
               style="overflow:scroll;">
            <thead>
            <tr>
                <th>N#</th>
                <th>Fecha</th>
                <th>Num.Factura</th>
                <th>Tipo</th>
                <th>Cod.Cli</th>
                <th>Cliente</th>
                <th>Telefono</th>
                <th>Total</th>
                <th>Pagos</th>
                <th>Abono</th>
                <th>Devuelto</th>
                <th>Saldo</th>
            </tr>
            </thead>
            <tbody><?php
            $obj->s_cod_grupo($_POST['txt_codigo']);
            $obj->s_rango_inic($_POST['txt_valor_ini']);
            $obj->s_rango_final($_POST['txt_valor_fin']);
            $obj->s_fecha_1($_POST['txt_fech_ini']);
            $obj->s_fecha_2($_POST['txt_fech_fin']);
            $obj->fn_detalle_cartera_vencida(); ?>
            </tbody>
        </table>
    </div>
    <div id="piepag"></div>
    <?php
    if ($_POST['rpt'] == 'PDF') {
        require_once("dompdf/dompdf_config.inc.php");
        $dompdf = new DOMPDF();
        $dompdf->load_html(ob_get_clean());
        $dompdf->set_paper("", $orientation = "landscape");
        $dompdf->render();
        $dompdf->output();
        header('Content-type: application/pdf');
        echo $dompdf->output();
    }
    ?>
</div>
</body>
</html>