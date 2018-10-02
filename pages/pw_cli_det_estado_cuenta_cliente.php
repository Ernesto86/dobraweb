<?php session_start();
if (!isset($_SESSION['us_id']) or !isset($_SESSION['us_cuenta']) or !isset($_SESSION['us_idtipo'])) {
    die('Inicie Session');
}
if ($_POST['rpt'] == 'PDF') ob_start();

require '../clases/cls_cliente.php';
$obj = new cls_cliente;
$obj->s_codigo($_POST['txt_codigo']);

$datos = $obj->fn_cli_consulta_codigo();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Sistema MundoText</title>
    <?php if ($_POST['rpt'] == 'HTML' or $_POST['rpt'] == 'PDF') { ?>
        <link rel="stylesheet" type="text/css" href="../css/table_reporte.css"/>
        <script src="../js/jquery.min.js"></script>
    <?php } ?>
</head>
<body>
<div class="content" id="data">
    <div id="det_datos_cliente">
        <div class="cab_title"><?php if ($_POST['rpt'] == 'HTML' or $_POST['rpt'] == 'PDF') { ?>
                <div><img src="../images/logo_150px.png" width="75" height="72"/></div>
                <?php if ($_POST['rpt'] == 'HTML') { ?><img src="../images/accion/print.png" title="Imprimir"
                                                            onclick="window.print();" class="imprimir"/><?php }
            } ?>
            <img src="../images/icon/234 PagesView4.png" height="14" width="14"/> CUENTAS POR COBRAR - ESTADO DE CUENTA
        </div>
        <table width="100%" border="0" cellspacing="0" cellpadding="0" id="table_detcliente">
            <tr>
                <th colspan="10">Datos del Cliente.</th>
            </tr>
            <tr>
                <td width="7%" align="right"><strong>Cliente :</strong></td>
                <td width="32%"><?php echo $datos['cod'] . ' - ' . $datos['nomb']; ?>&nbsp;</td>
                <td width="7%" align="right"><strong>Folder :</strong></td>
                <td width="11%"><?php echo $datos['fold']; ?></td>
                <td width="7%" align="right"><strong>Vendedor :</strong></td>
                <td colspan="3"><?php echo $datos['vend']; ?> </td>
                <td width="5%" align="right"><strong>Estado :</strong></td>
                <td width="6%"><?php $std = substr($datos['estd'], 0, 1);
                    if ($std == 'I') echo '<script language="javascript">alert("Este Cliente esta Anulado..");</script>';
                    echo $datos['estd']; ?></td>
            </tr>
            <tr>
                <td align="right"><strong>Periodos :</strong></td>
                <td><?php echo $_POST['txt_fech_ini'] . ' - ' . $_POST['txt_fech_fin'] ?></td>
                <td align="right"><strong>Comisi&oacute;n :</strong></td>
                <td><?php
                    $comision = trim($datos['comis']);
                    if ($comision) {
                        echo "<div class='comision_pagada'>$comision</div>";
                    }
                    ?></td>
                <td align="right"><strong>Grupo :</strong></td>
                <td width="15%"><?php echo $datos['Grup']; ?></td>
                <td width="4%"><strong>Telef.</strong></td>
                <td width="6%"><?php echo $datos['telef']; ?></td>
                <td align="right"><strong>Dia :</strong></td>
                <td><?php echo $datos['grp']; ?></td>
            </tr>
            <tr>
                <td align="right"><strong>Direcci&oacute;n :</strong></td>
                <td colspan="9"><?php echo $datos['direc']; ?></td>
            </tr>
            <tr>
                <th colspan="10">Detalle de Transacciones.</th>
            </tr>
        </table>
    </div>
    <div id="dt_data_table" style="display: block">
        <table border="0" cellpadding="0" cellspacing="1" class="dynamic styled with-prev-next">
            <thead>
            <tr>
                <th>N#</th>
                <th>Fecha</th>
                <th>Tipo</th>
                <th>Numero</th>
                <th>Detalle</th>
                <th>DEBE</th>
                <th>HABER</th>
                <th>SALDO</th>
            </tr>
            </thead>
            <tbody><?php
            $obj->s_fecha_1($_POST['txt_fech_ini']);
            $obj->s_fecha_2($_POST['txt_fech_fin']);
            if ($obj->fn_cli_estado_cuenta()) {
                $obj->fn_dt_estado_cuenta();
            } else 'No se Encontraron Registros..'; ?>
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