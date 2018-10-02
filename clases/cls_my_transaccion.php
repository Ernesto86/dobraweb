<?php session_start();
require_once'cls_my_acceso_datos.php';
class cls_my_transaccion extends cls_my_acceso_datos{ 
public function transacciones($id_menu){
 $this->sql='SELECT tran_id id,tran_ingresar ing,tran_agregar agregar,tran_modificar modificar,tran_eliminar eliminar FROM tbl_tipo_usuario JOIN tbl_transaccion USING(tip_user_id)JOIN tbl_menu USING(men_id)WHERE tip_user_id='.intval($_SESSION['us_idtipo']).' AND men_id='.intval($id_menu)." AND tran_estado='A' AND men_estado='A' GROUP BY tran_id;";
 $this->ejecutar();
 return $this->num_reg();
 }
 public function fn_usuario_caja_bodega(){
  $this->sql='SELECT grp_id caj,bod_id bodegaid,cajabancoid,cajaid,divisionid FROM tbl_usuario WHERE usu_id='.intval($_SESSION['us_id']).' LIMIT 1;';
  $this->ejecutar(); 
  return $this->num_reg();         
 }   
}
?>