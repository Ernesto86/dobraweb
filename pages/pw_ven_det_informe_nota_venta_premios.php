<?php session_start();
if (!isset($_SESSION['us_id']) or !isset($_SESSION['us_cuenta']) or !isset($_SESSION['us_idtipo'])) {
    die('Inicie Session');
}
if ($_POST['rpt'] == 'PDF') ob_start();

require '../clases/cls_inv_producto.php';
$obj = new cls_inv_producto; ?>
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
            } ?><img src="../images/icon/234 PagesView4.png" height="14" width="14"/> VENTAS - INFORME DE PREMIOS.
        </div>
        <table width="100%" border="0" cellspacing="0" cellpadding="0" id="table_detcliente">
            <tr>
                <th colspan="6">Informe.</th>
            </tr>
            <tr>
                <td width="8%" align="right"><strong>Fecha : </strong></td>
                <td width="20%">
                    <div class="dtempleado"><?php echo $_POST['txt_fech_ini'] . ' a ' . $_POST['txt_fech_fin']; ?></div>
                </td>
                <td width="7%" align="right"><strong>Bodega :</strong></td>
                <td width="24%"><?= $_POST['dtbodega'] ?></td>
                <td width="8%" align="right"><strong>Producto :</strong></td>
                <td width="33%"><?= $_POST['dtprod'] ?></td>
            </tr>
            <tr>
                <td align="right"><strong>Categoria :</strong></td>
                <td>
                    <div class="dtempleado"><?php echo $_POST['dt_grupo'] ? $_POST['dt_grupo'] : 'General'; ?></div>
                </td>
                <td align="right"><strong>Usuario :</strong></td>
                <td><?= $_POST['dtuser'] ?></td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
            </tr>
            <tr>
                <th colspan="6">Detalle de Transacciones.</th>
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
                <th>Num.Doc</th>
                <th>Tipo</th>
                <th>Cod.Cli</th>
                <th>Cliente</th>
                <th>Folder</th>
                <th>Producto</th>
                <th>Unidades</th>
                <th>Saldo</th>
            </tr>
            </thead>
            <tbody><?php
            $obj->s_cod_grupo($_POST['txt_codigo']);
            $obj->s_id_bodega($_POST['cb_bodega']);
            $obj->s_producto_id($_POST['cb_producto']);
            $obj->s_usuario_id($_POST['cb_usuario']);
            $obj->s_fecha_inicial($_POST['txt_fech_ini']);
            $obj->s_fecha_final($_POST['txt_fech_fin']);

            $obj->fn_inv_nota_venta_premio(); ?>
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