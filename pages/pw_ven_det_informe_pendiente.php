<?php session_start();
if (!isset($_SESSION['us_id']) or !isset($_SESSION['us_cuenta']) or !isset($_SESSION['us_idtipo'])) {
    die('Inicie Session');
}

require '../clases/cls_venta_factura.php';
$obj = new cls_venta_factura;
$obj->s_codigo($_POST['txt_codigo']);
$datos = $obj->fn_emp_codigo(); ?>
<!DOCTYPE html>
<html lang="es">
<head>
    <title>Título de la WEB</title>
    <meta charset="UTF-8">
    <title>Sistema MundoText</title>
    <script language="javascript">
        $(document).ready(function () {
            $('.dynamic').fixedtableheader();
            var cantpend = parseFloat($('#cantreg').val());
            var cantdev = parseFloat($('#cantdev').val());
            if (cantpend) $('#numpend').html(cantpend);
            if (cantdev) $('#numdevol').html(cantdev);
        });
    </script>
</head>
<body>
<div class="content" id="data">
    <div id="det_datos_cliente">
        <div class="cab_title"><img src="../images/icon/234 PagesView4.png" height="14" width="14"/> VENTAS - INFORME DE
            PENDIENTES
        </div>
        <table width="100%" border="0" cellspacing="0" cellpadding="0" id="table_detcliente">
            <tr>
                <th colspan="6">Detalle Empleado .</th>
            </tr>
            <tr>
                <td width="9%" align="right"><strong>Codigo:</strong></td>
                <td width="17%">
                    <div class="dtempleado"><?php echo $datos['cod']; ?></div>
                </td>
                <td width="16%" align="right"><strong>Vendedor:</strong></td>
                <td colspan="3">
                    <div class="dtempleado"><?php echo $datos['nomb']; ?></div>
                </td>
            </tr>
            <tr>
                <td align="right"><strong>Periodos:</strong></td>
                <td><?php echo $_POST['txt_fech_ini'] . ' - ' . $_POST['txt_fech_fin'] ?></td>
                <td align="right"><strong>Total Pendientes: </strong></td>
                <td width="13%" align="left">
                    <div id="numpend" class="cantidad"></div>
                </td>
                <td width="23%" align="right"><strong>Total Devoluciones:</strong></td>
                <td width="22%" align="left">
                    <div id="numdevol" class="cantidad"></div>
                </td>
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
                <th>N&deg;</th>
                <th>Fecha</th>
                <th>Tipo</th>
                <th>Cod.Cli</th>
                <th>Cliente</th>
                <th>Zona</th>
                <th>CM</th>
                <th>Total</th>
                <th>Devol.</th>
                <th>F.Devol</th>
                <th>Detalle/DV</th>
                <th>N/CR</th>
                <th>Detalle/CR</th>
                <th>Abono</th>
                <th>Saldo</th>
            </tr>
            </thead>
            <tbody><?php
            $obj->s_opcion('PEND');
            $obj->s_fecha_1($_POST['txt_fech_ini']);
            $obj->s_fecha_2($_POST['txt_fech_fin']);
            $obj->fn_informe_venta_factura(); ?>
            </tbody>
        </table>
    </div>
    <div id="piepag"></div>
</div>
</body>
</html>