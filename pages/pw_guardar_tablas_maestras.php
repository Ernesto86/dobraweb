<?php session_start();
if(!isset($_SESSION['us_id'])or!isset($_SESSION['us_cuenta'])or!isset($_SESSION['us_idtipo'])){
die('Inicie Session');
}
if(!isset($_POST['opc'])or!isset($_POST['pag']))die('Error al Recibir la Opcion');

$opc = $_POST['opc'];
$class ='cls_my_'.$_POST['pag'];
require"../clases/$class.php";
$objtb = new $class;
$objtb->s_opcion($opc);
if($opc!='I'){
  if (!isset($_POST['id']))die('Error al Recibir el Id');
	  
  if($_POST['pag']=='tipo_usuario' and $_POST['id']==1){
   die('No puede Modificar este Registro');
  }
  $objtb->s_id($_POST['id']);
}
$objtb->s_desc($_POST['txt_desc']);

switch($_POST['pag']){
	
  case 'provincia': $objtb->s_id_pais($_POST['cb_pais']); break;
  
  case 'ciudad': $objtb->s_id_pais($_POST['cb_pais']);
				 $objtb->s_id_provincia($_POST['cb_provincia']); 
				 break;
  				
  case'modulo': $objtb->s_orden($_POST['txt_orden']);	
				break;								 			 			  
				 
  case'menu':$objtb->s_modulo($_POST['cb_modulo']);	
             $objtb->s_orden($_POST['txt_orden']);   
			 break;		
}
$objtb->s_estado($_POST['cb_estado']);
echo $objtb->transaccion();?>