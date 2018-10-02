<?php session_start();
if (!isset($_SESSION['us_id']) or !isset($_SESSION['us_cuenta']) or !isset($_SESSION['us_idtipo'])) {
    die('Inicie Session');
}

if ($_POST['rpt'] == 'PDF') ob_start();

require '../clases/cls_cliente.php';
$obj = new cls_cliente;
?>
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
<div class="content" id="data">
    <div id="det_datos_cliente">
        <div class="cab_title"><?php if ($_POST['rpt'] == 'HTML' or $_POST['rpt'] == 'PDF') { ?>
                <div><img src="../images/logo_150px.png" width="75" height="72"/></div>
                <?php if ($_POST['rpt'] == 'HTML') { ?><img src="../images/accion/print.png" title="Imprimir"
                                                            onclick="window.print();" class="imprimir"/><?php }
            } ?><img src="../images/icon/234 PagesView4.png" height="14" width="14"/> INFORME GENERAL DE PRODUCTOS
            FACTURADOS
        </div>
        <table width="100%" border="0" cellspacing="0" cellpadding="0" id="table_detcliente">
            <tr>
                <th colspan="10">Detalle de Parametros .</th>
            </tr>
            <tr>
                <td width="9%" align="right"><strong>Codigo :</strong></td>
                <td width="18%">
                    <div class="dtempleado"><?php echo $datos['cod']; ?></div>
                </td>
                <td width="9%" align="right">&nbsp;</td>
                <td colspan="7">&nbsp;</td>
            </tr>
            <tr>
                <td align="right"><strong>Periodos :</strong></td>
                <td><?php echo $_POST['txt_fech_ini'] . ' - ' . $_POST['txt_fech_fin'] ?></td>
                <td align="right">&nbsp;</td>
                <td width="8%" align="left">&nbsp;</td>
                <td width="12%" align="right">&nbsp;</td>
                <td width="8%" align="left">&nbsp;</td>
                <td width="7%" align="right">&nbsp;</td>
                <td width="8%" align="left">&nbsp;</td>
                <td width="11%" align="right">&nbsp;</td>
                <td width="10%" align="left">&nbsp;</td>
            </tr>
            <tr>
                <th colspan="10">Detalle de Transacciones.</th>
            </tr>
        </table>
    </div>
    <div id="dt_data_table">
        <table width="100%" border="0" cellpadding="0" cellspacing="1" class="dynamic styled with-prev-next"
               style="overflow:scroll;">
            <thead>
            <tr>
                <th>N&deg;</th>
                <th>Fecha</th>
                <th>Numero</th>
                <th>Cod.Cli</th>
                <th>Cliente</th>
                <th>Detalle</th>
                <th>Total</th>
            </tr>
            </thead>
            <tbody><?php
            $obj->s_codigo($_POST['txt_codigo']);
            $obj->s_idproducto($_POST['cb_producto']);
            $obj->s_fecha_1($_POST['txt_fech_ini']);
            $obj->s_fecha_2($_POST['txt_fech_fin']);
            $obj->fn_inv_det_informe_productos_fact(); ?>
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