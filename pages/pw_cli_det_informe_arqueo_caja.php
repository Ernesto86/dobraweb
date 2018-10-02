<?php session_start();
if (!isset($_SESSION['us_id']) or !isset($_SESSION['us_cuenta']) or !isset($_SESSION['us_idtipo'])) {
    die('Inicie Session');
}
if ($_POST['rpt'] == 'PDF') ob_start();

require '../clases/cls_cli_informe_deuda.php';
$obj = new cls_cli_informe_deuda;
$obj->s_banco_id($_POST['cb_caja_banco']);
$obj->s_usuario_id($_POST['cb_usuario']);
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
            } ?><img src="../images/icon/234 PagesView4.png" height="14" width="14"/> Bancos - Arqueo de Caja
        </div>
        <table width="100%" border="0" cellspacing="0" cellpadding="0" id="table_detcliente">
            <tr>
                <th colspan="10">Detalle de Parametros.</th>
            </tr>
            <tr>
                <td width="9%" align="right"><strong>Banco :</strong></td>
                <td width="23%">
                    <div class="dtempleado"><?php echo $_POST['dt_caja']; ?></div>
                </td>
                <td width="13%" align="right"><strong>Usuario :</strong></td>
                <td colspan="7">
                    <div class="dtempleado"><?php echo $_POST['cb_usuario']; ?></div>
                </td>
            </tr>
            <tr>
                <td align="right"><strong>Periodos :</strong></td>
                <td><?php echo $_POST['txt_fech_ini'] . ' - ' . $_POST['txt_fech_fin'] ?></td>
                <td align="right">&nbsp;</td>
                <td width="5%" align="left">&nbsp;</td>
                <td width="6%" align="right">&nbsp;</td>
                <td width="8%" align="left">&nbsp;</td>
                <td width="7%" align="right">&nbsp;</td>
                <td width="8%" align="left">&nbsp;</td>
                <td width="11%" align="right">&nbsp;</td>
                <td width="10%" align="left">&nbsp;</td>
            </tr>
            <tr>
                <td colspan="10">
                    <div class="relleno"></div>
                </td>
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
                <th>Tipo</th>
                <th>Numero</th>
                <th>Cod.Cli</th>
                <th>Cliente</th>
                <th>Folder</th>
                <!--th>Zona</th-->
                <th>Pago</th>
                <th>Saldo</th>
            </tr>
            </thead>
            <tbody><?php
            $obj->s_fecha_1($_POST['txt_fech_ini']);
            $obj->s_fecha_2($_POST['txt_fech_fin']);
            $obj->fn_cli_arqueo_banco_caja(); ?>
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
//$filename = "ejemplo".time().'.pdf';
//file_put_contents($filename, $pdf);
//$dompdf->stream($filename);
    }
    ?>
</div>
</body>
</html>