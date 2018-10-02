<?php session_start();
if(!isset($_SESSION['us_id'])or!isset($_SESSION['us_cuenta'])or!isset($_SESSION['us_idtipo'])){
 die('{"err":"2"}');
}
if(!isset($_POST['txt_tipo'])or!isset($_POST['id_caja_banco'])or!isset($_POST['idcliente'])or!isset($_POST['txt_nota'])or!isset($_POST['txt_ing_fecha'])){
 die('{"err":"3"}');
}
if(!array_key_exists('txt_val',$_POST)or!array_key_exists('txt_abono',$_POST)or!array_key_exists('txt_deuda_id',$_POST)or!array_key_exists('txt_num_fact', $_POST)){
 die('{"err":"3"}');
}

$fecha = $_POST['txt_ing_fecha'];

require'../clases/cls_cli_cliente_deuda.php';
$obj = new cls_cli_cliente_deuda;
$obj->s_id($_POST['idcliente']);

$saldo_client = $obj->fn_cli_saldo_total();

if($saldo_client <= 0) die('{"err":"4"}');

$obj->ingr_saldo_det = $_POST['txt_saldo'];

$ingr_sald_det = floatval(array_sum($obj->ingr_saldo_det));

if($ingr_sald_det <= 0 )die('{"err":"5"}');

/*if($saldo_client != $ingr_sald_det){
  die('{"err":"5"}');
}*/
$obj->s_nombre($_POST['txt_cli_nombre']);
$obj->s_id_banco($_POST['id_caja_banco']);
$obj->s_tipo($_POST['txt_tipo']);
$obj->s_observacion($_POST['txt_nota']);
$obj->s_total_abono($_POST['txt_total_abono']);
$obj->s_cuenta($_SESSION['us_cuenta']);
$obj->s_fecha_1($fecha);

$obj->ingr_abono_cab = $_POST['txt_val'];
$obj->ingr_abono_det = $_POST['txt_abono'];

$ingr_cab = floatval(array_sum($obj->ingr_abono_cab));
$ingr_det = floatval(array_sum($obj->ingr_abono_det));

if($ingr_cab <= 0 or $ingr_det <= 0 or $obj->totabono <= 0) die('{"err":"5"}');
 
if($ingr_cab != $ingr_det or $obj->totabono != $ingr_det) die('{"err":"5"}');

$obj->ingr_femis = $_POST['txt_femis_fact'];
$obj->ingr_fvenc = $_POST['txt_fvenc_fact'];

$obj->ingr_num = $_POST['txt_num_fact'];
$obj->ingr_tip = $_POST['txt_tip_fact'];
$obj->ingr_det_fact = $_POST['txt_det_fact'];

$obj->ingr_deud_id  = $_POST['txt_deuda_id'];
$obj->ingr_cxc  = $_POST['txt_cxc'];
$obj->ingr_rubro= $_POST['txt_rubro'];
$obj->ingr_cred = $_POST['txt_cred'];

$obj->fn_cli_guardar_ingreso_dinero();?>