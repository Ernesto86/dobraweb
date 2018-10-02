<?php session_start();
if (!isset($_SESSION['us_id']) or !isset($_SESSION['us_cuenta']) or !isset($_SESSION['us_idtipo'])) {
    die('Inicie Session');
}
if (!isset($_REQUEST['tipo']) or !isset($_REQUEST['id'])) die('Parametro Id sin Valor');

require '../clases/cls_my_acceso.php';
$oacc = new cls_my_acceso;
$oacc->s_modulo($_REQUEST['id']);
$oacc->s_tipo_usuario($_REQUEST['tipo']); ?>
<!DOCTYPE html>
<html lang="es">
<head>
    <title>Título de la WEB</title>
    <meta charset="UTF-8">
    <title>Sistema MundoText</title>
    <link rel="stylesheet" type="text/css" href="../css/detalle_acceso.css">
    <script type="text/javascript">
        $(document).ready(function () {
            $('#cab .th').click(function () {
                cls = this.name.substring(0, 3);
                $('input[type=checkbox].' + cls).attr("checked", $(this).attr('checked'));
            });
        });
    </script>
</head>
<body>
<table border="0" align="center" cellpadding="0" cellspacing="0" id="detalle">
    <thead id="cab">
    <tr>
        <th>Num.</th>
        <th>Menu</th>
        <th><input name="ingresar" type="checkbox" class="th"/>
            Mostrar Menu
        </th>
        <th><input name="agregar" type="checkbox" class="th"/>
            Ingresar
        </th>
        <th><input name="modificar" type="checkbox" class="th"/>
            Modificar
        </th>
        <th><input name="eliminar" type="checkbox" class="th"/>
            Eliminar
        </th>
    </tr>
    </thead>
    <tbody>
    <?php $oacc->menu_transaccion(); ?>
    </tbody>
</table>
</body>
</html>