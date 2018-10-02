<?php session_start();
if(!isset($_SESSION['us_id'])or!isset($_SESSION['us_cuenta'])or!isset($_SESSION['us_idtipo'])){
 die('{"err":"2"}');
}
if(!isset($_POST['txt_tipo'])or!isset($_POST['id_cliente'])or!isset($_POST['idfactura'])or!isset($_POST['txt_total_cred'])or!isset($_POST['txt_ing_fecha'])){
 die('{"err":"3"}');
}
if(!array_key_exists('txt_fact_det_id',$_POST)or!array_key_exists('txt_producto_id',$_POST)or!array_key_exists('txt_cantidad',$_POST)or!array_key_exists('txt_devuelto',$_POST)){
 die('{"err":"3"}');
}
if(!array_key_exists('txt_bodega_id', $_POST)or!array_key_exists('txt_pvp',$_POST)or!array_key_exists('txt_extend',$_POST)){
 die('{"err":"3"}');		
}
if(!array_key_exists('txt_saldo', $_POST)or !array_key_exists('txt_deuda_id', $_POST)){
 die('{"err":"3"}');		
}

require'../clases/cls_cli_nota_credito_producto.php';
$obj = new cls_cli_nota_credito_producto;

$obj->s_id_cliente($_POST['id_cliente']);

$saldo_client = $obj->fn_cli_saldo_total();

if($saldo_client <= 0) die('{"err":"4"}');

$obj->arr_saldo_det = $_POST['txt_saldo'];

$sald_det = floatval(array_sum($obj->arr_saldo_det));

if($sald_det <= 0) die('{"err":"5"}');

//if($saldo_client != $sald_det){ die('{"err":"5"}'); }

$obj->arr_val_devuelto = $_POST['txt_devuelto'];
$obj->arr_val_credito  = $_POST['txt_abono'];
$obj->arr_val_cantidad = $_POST['txt_cantidad'];

$ingr_cab = floatval(array_sum($obj->arr_val_devuelto));
$ingr_det = floatval(array_sum($obj->arr_val_credito));
$ingt_cant= floatval(array_sum($obj->arr_val_cantidad));

$obj->s_total_credito($_POST['txt_total_cred']);

if($ingr_cab <= 0 or $ingr_det <= 0 or $obj->totcredito <= 0 or $ingt_cant <= 0) die('{"err":"5"}');
 
if($obj->totcredito != $ingr_det) die('{"err":"5"}');

$obj->s_id_factura($_POST['idfactura']);
$obj->s_tipo_factura($_POST['txt_tipo']);
$obj->s_id_usuario($_SESSION['us_cuenta']);
$obj->s_fecha($_POST['txt_ing_fecha']);

$obj->s_nota($_POST['txt_nota']);
$obj->s_dtconcept($_POST['txt_cli_concepto']);
$obj->s_id_bodega($_POST['id_bodega']);

$obj->arr_fact_det_id  = $_POST['txt_fact_det_id'];
$obj->arr_id_producto  = $_POST['txt_producto_id'];
//$obj->arr_id_bodega    = $_POST['txt_bodega_id'];

$obj->arr_precio_pvp = $_POST['txt_pvp'];
$obj->arr_costo_prod = $_POST['txt_costo_prod'];
$obj->arr_subtotal   = $_POST['txt_extend'];
$obj->arr_deuda_id   = $_POST['txt_deuda_id'];
$obj->arr_fact_num   = $_POST['txt_num_fact'];
$obj->arr_tip_fact   = $_POST['txt_tip_fact'];
$obj->arr_det_fact   = $_POST['txt_det_fact'];
$obj->arr_cuenta_id  = $_POST['txt_cxc'];

$obj->arr_fech_emis = $_POST['txt_femis_fact'];
$obj->arr_fech_venc = $_POST['txt_fvenc_fact'];

$obj->arr_rubro_id = $_POST['txt_rubro'];
$obj->arr_cred_id  = $_POST['txt_cred'];

$obj->fn_ven_guardar_nota_credito_producto();?>