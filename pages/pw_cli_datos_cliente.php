<?php if (!$_POST['cod']) {
    die('{"err":"0"}');
}

//@header('Content-type: text/html; charset=iso-8859-1');

require '../clases/cls_cliente.php';
$obj = new cls_cliente;
$obj->s_codigo($_POST['cod']);
$data = $obj->fn_cli_datos_codigo();

if (strval($data['id']) and $_POST['sald']) {
    if ($obj->fn_cli_saldo_total()) {
        $sald = $obj->campos();
    }
}
echo '{"id":"' . strval($data['id']) . '","cli":"' . ucwords($data['cli']) . '","estd":"';
echo $data['estd'] . '","emp_id":"' . $data['emp_id'] . '","cod":"' . $data['codemp'];
echo '","vend":"' . ucwords($data['vend']) . '","sald":"' . number_format($sald['saldo'], 2) . '","ruc":"' . $data['ruc'] . '"}'; ?>
