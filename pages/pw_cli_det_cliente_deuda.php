<?php
if (!$_POST['idcli']) {
    die('Parametro no Valido');
}
//@header('Content-type: text/html; charset=iso-8859-1');
require '../clases/cls_cli_cliente_deuda.php';
$obj = new cls_cli_cliente_deuda;
$obj->s_id($_POST['idcli']);
$obj->fn_cli_cliente_deuda_id(); ?>
<script language="javascript">
    $(document).ready(function () {
        var saldototal = $('#saldtotal').val();
        if (saldototal) {
            $('#txt_total_deuda').val(saldototal);
            $('#txt_nuevo_saldo').val(saldototal);
        }
    });
</script>

