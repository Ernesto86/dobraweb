<?php session_start();

require_once'cls_my_acceso_propiedades.php';
require_once'cls_my_acceso_datos.php';
class cls_my_acceso extends cls_my_acceso_datos { 

  protected $objpro = null;
  function __construct(){
   parent::__construct();	
   $this->objpro = new cls_my_acceso_propiedades;  
  }
  function __call($method, $args){ return call_user_func_array(array($this->objpro,$method),$args); }  
 
 public function consulta(){
 $this->sql='SELECT ME.men_id id,ME.men_orden ord,men_desc descrip,tran_ingresar ingre,tran_agregar agreg,tran_modificar modif,';
 $this->sql.='tran_eliminar elim,mod_desc modulo FROM tbl_tipo_usuario TU INNER JOIN tbl_transaccion TR ON(TU.tip_user_id=TR.tip_user_id';
 $this->sql.=' AND TU.tip_user_id='.$this->g_tipo_usuario().')RIGHT OUTER JOIN tbl_menu ME ON(ME.men_id=TR.men_id)INNER JOIN tbl_modulo MO';
 $this->sql.=' ON(MO.mod_id=ME.mod_id)WHERE MO.mod_id='.$this->g_modulo()." AND(mod_estado='A' AND men_estado='A')";
 $this->sql.='GROUP BY ME.men_id ORDER BY men_orden ASC;';
 $this->ejecutar();
 $this->num_registro();
 }	 

public function menu_transaccion(){
  $this->consulta();
  $mt = $this->rs; $i=1;
  while(($rmt = @mysqli_fetch_assoc($mt))){
   echo'<tr ';       
	    echo $i%2==0?' class="alternar">':'>';   
   	    echo'<td align="center">'.$rmt['ord'].'</td>';
		echo'<td>'.$rmt['descrip'].'<input type="hidden" value="'.$rmt['id'].'"/></td>';
		
		echo'<td><input type="checkbox" class="ing"';
		if($rmt['ingre'])echo' checked="checked"';
		echo'/></td>';					
		
		echo'<td><input type="checkbox" class="agr"';
		if($rmt['agreg'])echo' checked="checked"';
		echo'/></td>';
		
		echo'<td><input type="checkbox" class="mod"';
		if($rmt['modif'])echo' checked="checked"';
		echo'/></td>';
		
		echo'<td><input type="checkbox" class="eli"';
		if($rmt['elim'])echo' checked="checked"';
		echo'/></td>';										
  echo'</tr>';  $i++;
 }
}	 
public function transaccion (){  
 $this->sql='CALL SEG_PRC_MANT_TRANSACCION(0,'.$this->g_tipo_usuario().','.$this->g_modulo().','.$this->g_menu().',';
 $this->sql.=$this->g_ingreso().','.$this->g_agregar().','.$this->g_modificar().','.$this->g_eliminar().",'";
 $this->sql.=$this->g_estado()."','".$_SESSION['us_cuenta']."');";
 return $this->ejecutar(); 
} 	
}	
?>