<?php if (!$_POST['num_fact']) {
    die('{"err":"0"}');
}

@header('Content-type: text/html; charset=iso-8859-1');
require '../clases/cls_consulta.php';
$obj = new cls_consulta;
$obj->s_num_fact($_POST['num_fact']);

if (!$obj->fn_ven_num_fact_select()) die('{"err":"1"}');

$data = $obj->campos();
echo '{"fact_id":"' . strval($data['fa_id']) . '","cli_id":"' . strval($data['cli_id']) . '","asient_id":"';
echo strval($data['asient_id']) . '","cli_cod":"' . strval($data['cli_cod']) . '","cli_nomb":"' . ucwords($data['cli_nomb']);
echo '","cli_ruc":"' . strval($data['cli_ruc']) . '","emp_id":"' . $data['emp_id'] . '","emp_cod":"' . $data['emp_cod'];
echo '","emp_nom":"' . ucwords($data['emp_nom']) . '"}'; ?>