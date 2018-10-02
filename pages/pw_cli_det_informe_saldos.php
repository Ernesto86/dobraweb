<?php session_start();
if (!isset($_SESSION['us_id']) or !isset($_SESSION['us_cuenta']) or !isset($_SESSION['us_idtipo'])) {
    die('Inicie Session');
}
require '../clases/cls_cliente.php';
$obj = new cls_cliente; ?>
<!DOCTYPE html>
<html lang="es">
<head>
    <title>Título de la WEB</title>
    <meta charset="UTF-8">
    <title>Sistema MundoText</title>
    <script language="javascript">
        $(document).ready(function () {
            $('.dynamic').fixedtableheader();
        });
    </script>
</head>
<body>
<div class="content">
    <div id="det_datos_cliente">
        <div class="cab_title"><img src="../images/icon/234 PagesView4.png" height="14" width="14"/> CUENTAS POR COBRAR
            - INFORME DE SALDOS
        </div>
        <table width="100%" border="0" cellspacing="0" cellpadding="0" id="table_detcliente">
            <tr>
                <th colspan="2">Informe.</th>
            </tr>
            <tr>
                <td width="16%" align="right"><strong>Fecha de Cierre </strong></td>
                <td width="84%">
                    <div class="dtempleado"><?php echo $_POST['txt_fech_fin']; ?></div>
                </td>
            </tr>
            <tr>
                <td align="right"><strong>Categoria</strong></td>
                <td>
                    <div class="dtempleado"><?php echo $_POST['dt_grupo']; ?></div>
                </td>
            </tr>
            <tr>
                <th colspan="2">Detalle de Transacciones.</th>
            </tr>
        </table>
    </div>
    <div id="dt_data_table">
        <table border="0" cellpadding="0" cellspacing="0" class="dynamic styled with-prev-next">
            <thead>
            <tr>
                <th>N#</th>
                <th>C&oacute;digo</th>
                <th>Nombre</th>
                <th>Folder</th>
                <th>CI</th>
                <th>Direccion</th>
                <th>Telef</th>
                <th>Nota</th>
                <th>FechaFact.</th>
                <th>Ult.Pag</th>
                <th>SALDO</th>
            </tr>
            </thead>
            <tbody><?php
            $cod = strtoupper($_POST['txt_codigo']);
            if ($cod == 'GRAL') $cod = '';
            $obj->s_codigo($cod);
            $obj->s_fecha_2($_POST['txt_fech_fin']);
            $obj->fn_cli_informe_saldo_grupo(); ?>
            </tbody>
        </table>
    </div>
    <div id="piepag"></div>
</div>
</body>
</html>