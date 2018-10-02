<?php session_start();
if(!isset($_SESSION['us_id'])or!isset($_SESSION['us_cuenta'])or!isset($_SESSION['us_idtipo'])){
die('{"err":"2"}');
}
if(!isset($_POST['txt_tipo'])or!isset($_POST['id_bodega'])or!isset($_POST['idcliente'])or!isset($_POST['txt_nota'])){
 die('{"err":"3"}');
}
if(!array_key_exists('prod_id',$_POST)or!array_key_exists('prod_pvp',$_POST)or!array_key_exists('prod_cant',$_POST)){
 die('{"err":"3"}');
}

$sum_cant = floatval(array_sum($_POST['prod_cant']));

if($sum_cant < 1) die('{"err":"4"}');

$fecha = $_POST['txt_ing_fecha'];

if(!$fecha)$fecha =date("d/m/Y");

require'../clases/cls_cli_cliente_premios.php';
$obj = new cls_cli_cliente_premios;

$obj->s_id_cliente($_POST['idcliente']);
$obj->s_id_bodega($_POST['id_bodega']);
$obj->s_tipo_factura($_POST['txt_tipo']);
$obj->s_nota($_POST['txt_nota']);
$obj->s_fecha($fecha);
$obj->s_cliente($_POST['txt_cli_nombre']);
$obj->s_id_usuario($_SESSION['us_cuenta']);

$obj->id_producto = $_POST['prod_id'];

foreach($obj->id_producto as $key => $val){
 if(trim($val)){
  if($obj->fn_inv_stock_producto($val) < 1){
   die('{"err":"5","cod":"'.$obj->codprod.'"}');	
  }
 }
}
$obj->costo    = $_POST['prod_pvp'];
$obj->cantidad = $_POST['prod_cant']; 
$obj->fn_ven_guardar_nota_venta_premio();?>