<?php session_start();
if(!isset($_SESSION['us_id'])or!isset($_SESSION['us_cuenta'])or!isset($_SESSION['us_idtipo'])){
die('{"err":"2"}');
}
if(!isset($_POST['txt_tipo'])or!isset($_POST['id_bodega'])or!isset($_POST['idcliente'])or!isset($_POST['txt_nota'])){
 die('{"err":"3"}');
}
if(!isset($_POST['txt_ing_fecha'])or!isset($_POST['idvendedor'])or!isset($_POST['txt_subtotal'])or!isset($_POST['txt_total_pagar'])){
 die('{"err":"3"}');	
}
if(!array_key_exists('prod_id',$_POST)or!array_key_exists('prod_pvp',$_POST)or!array_key_exists('prod_cant',$_POST)or!array_key_exists('prod_total',$_POST)){
 die('{"err":"3"}');
}
if(!array_key_exists('prod_costo',$_POST)){
 die('{"err":"3"}');
}

$sum_cant  = intval(array_sum($_POST['prod_cant']));
$sum_total = floatval(array_sum($_POST['prod_total']));

if($sum_cant < 1 or $sum_total <= 0) die('{"err":"4"}');

require'../clases/cls_inv_prod_cambio_ven_fa.php';
$obj = new cls_inv_prod_cambio_ven_fa;

$obj->s_id_cliente($_POST['idcliente']);
$obj->s_id_bodega($_POST['id_bodega']);
$obj->s_tipo_factura($_POST['txt_tipo']);
$obj->s_nota($_POST['txt_nota']);
$obj->s_fecha($_POST['txt_ing_fecha']);
$obj->s_cliente($_POST['txt_cli_nombre']);
$obj->s_id_usuario($_SESSION['us_cuenta']);
$obj->s_id_vendedor($_POST['idvendedor']);
$obj->s_ruc($_POST['cli_ruc']);

$obj->s_sub_total_fact($_POST['txt_subtotal']);
$obj->s_total_fact($_POST['txt_total_pagar']);

if($sum_total != $obj->sub_total_fact)die('{"err":"7"}');


$obj->arr_id_producto = $_POST['prod_id'];
$obj->arr_val_cant_fact_det = $_POST['prod_cant'];

foreach($obj->arr_id_producto as $key => $val){
	
 if(trim($val)){
	 	 
  $stock = $obj->fn_inv_stock_producto($val);	 
  if(intval($stock) < 1){
    die('{"err":"5","cod":"'.$obj->codprod.'"}');	
  }
  if($obj->arr_val_cant_fact_det[$key] > intval($stock)){
	die('{"err":"6","cod":"'.$obj->codprod.'"}'); 
  }     
 }   
}

$obj->arr_costo_prod = $_POST['prod_costo'];
$obj->arr_precio_pvp = $_POST['prod_pvp'];
$obj->arr_subtotal   = $_POST['prod_total'];
 
$obj->fn_ven_guardar_venta_factura(); ?>