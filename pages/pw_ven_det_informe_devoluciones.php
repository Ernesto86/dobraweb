<?php session_start();
if (!isset($_SESSION['us_id']) or !isset($_SESSION['us_cuenta']) or !isset($_SESSION['us_idtipo'])) {
    die('Inicie Session');
}
require '../clases/cls_venta_factura.php';
$obj = new cls_venta_factura;
$obj->s_codigo($_POST['txt_codigo']);
$datos = $obj->fn_emp_codigo();
$obj->s_fecha_1($_POST['txt_fech_ini']);
$obj->s_fecha_2($_POST['txt_fech_fin']); ?>
<!DOCTYPE html>
<html lang="es">
<head>
    <title>Título de la WEB</title>
    <meta charset="UTF-8">
    <title>Sistema MundoText</title>
    <script language="javascript">
        $(document).ready(function () {
            var cantdevrango = parseFloat($('#cantdvrango').val());
            if (cantdevrango) $('#numdevrango').html(cantdevrango);
            var cantdvnotrango = parseFloat($('#cantdvnotrango').val());
            if (cantdvnotrango) $('#numdevnotrango').html(cantdvnotrango);
        });
    </script>
</head>
<body>
<div class="content" id="data">
    <div id="det_datos_cliente">
        <div class="cab_title"><img src="../images/icon/234 PagesView4.png" height="14" width="14"/> VENTAS - INFORME
            DEVOLUCIONES
        </div>
        <table width="100%" border="0" cellspacing="0" cellpadding="0" id="table_detcliente">
            <tr>
                <th colspan="9">Detalle Empleado .</th>
            </tr>
            <tr>
                <td width="6%" align="right"><strong>Codigo:</strong></td>
                <td width="15%">
                    <div class="dtempleado"><?php echo $datos['cod']; ?></div>
                </td>
                <td width="13%" align="right"><strong>Vendedor:</strong></td>
                <td colspan="6">
                    <div class="dtempleado"><?php echo $datos['nomb']; ?></div>
                </td>
            </tr>
            <tr>
                <td align="right"><strong>Periodos:</strong></td>
                <td><?php echo $_POST['txt_fech_ini'] . ' - ' . $_POST['txt_fech_fin'] ?></td>
                <td align="right"><strong>Devoluciones:</strong></td>
                <td width="7%" align="right"><strong>Del Rango : </strong></td>
                <td width="16%" align="left">
                    <div id="numdevrango" class="cantidad"></div>
                </td>
                <td width="5%" align="left"></td>
                <td width="14%" align="right"><strong>No estan en el Rango :</strong></td>
                <td>
                    <div id="numdevnotrango" class="cantidad"></div>
                </td>
                <td width="13%" align="right">&nbsp;</td>
            </tr>
            <tr>
                <th colspan="9">&nbsp;</th>
            </tr>
        </table>
    </div>
    <div id="dt_data_table">
        <div class="cab_title margen_15"><img src="../images/icon/234 PagesView4.png" height="14" width="14"/>
            DEVOLUCIONES POR VENTAS DEL CORTE
        </div>
        <div class="relleno_left">
            <table border="0" cellpadding="0" cellspacing="0" class="dynamic styled with-prev-next">
                <thead>
                <tr>
                    <th>N&deg;</th>
                    <th>F.Devol</th>
                    <th>Cod.Cli</th>
                    <th>Cliente</th>
                    <th>CM</th>
                    <th>Total</th>
                    <th>Devol.</th>
                    <th>Detalle/DV</th>
                    <th>N&ordm;.Factura</th>
                    <th>F.Factura</th>
                </tr>
                </thead>
                <tbody><?php $obj->fn_informe_venta_devolucion(); ?>
                </tbody>
            </table>
            <input type="hidden" name="cantdvrango" id="cantdvrango" value="<?php echo $obj->cantdv; ?>"/>
        </div>
        <div class="cab_title margen_15 top10"><img src="../images/icon/234 PagesView4.png" height="14" width="14"/>
            DEVOLUCIONES POR VENTAS ANTERIORES
        </div>
        <div class="relleno_left">
            <table border="0" cellpadding="0" cellspacing="0" class="dynamic styled with-prev-next">
                <thead>
                <tr>
                    <th>N&deg;</th>
                    <th>F.Devol</th>
                    <th>Cod.Cli</th>
                    <th>Cliente</th>
                    <th>CM</th>
                    <th>Total</th>
                    <th>Devol.</th>
                    <th>Detalle/DV</th>
                    <th>N&ordm;.Factura</th>
                    <th>F.Factura</th>
                </tr>
                </thead>
                <tbody><?php
                $obj->s_opcion('NOTRANG');
                $obj->fn_informe_venta_devolucion(); ?>
                </tbody>
            </table>
            <input type="hidden" name="cantdvnotrango" id="cantdvnotrango" value="<?php echo $obj->cantdv; ?>"/>
        </div>
    </div>
    <div id="piepag"></div>
</div>
</body>
</html>
