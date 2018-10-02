<?php session_start();
require_once 'cls_my_propiedades.php';
require_once 'cls_my_acceso_datos.php';
class cls_my_modulo extends cls_my_acceso_datos  { 
protected $objpro = null;

function __construct(){
  parent::__construct(); 
  $this->objpro = new cls_my_propiedades; 
}
function __call($method, $args){return call_user_func_array(array($this->objpro,$method),$args);}

public function consulta() {
 if($this->g_id()){
  $this->sql ='SELECT mod_id Id,mod_desc Descripcion,mod_orden orden,mod_estado Estado FROM tbl_modulo WHERE mod_id='.$this->g_id().' LIMIT 1;'; }else{
   $this->sql ='SELECT mod_id Id,mod_desc Descripcion,mod_estado Estado FROM tbl_modulo WHERE mod_estado ';
  if($_SESSION['us_tipuser']=='administrador'){ $this->sql.="IN('A','I')";
  }else $this->sql.="='A'";
  $this->sql.=' ORDER BY mod_orden;'; 
 }
 $this->ejecutar();
 $this->num_registro();
}
public function cb_modulo(){
 $this->sql='SELECT mod_id Id,mod_desc Descripcion,mod_estado Estado FROM tbl_modulo WHERE mod_estado='."'A'".' ORDER BY mod_orden ASC;';
 $this->ejecutar();
 return $this->num_reg();
}
public function transaccion (){
	$opc=$this->g_opcion();
	if($opc!='EL'){
	    $this->sql='SELECT mod_id id FROM tbl_modulo WHERE mod_orden='.$this->g_orden();
		if($opc=='M')$this->sql.=' AND mod_id!='.$this->g_id();
		$this->ejecutar();		
		if($this->num_reg()>0)return'Ya Existe este Orden Cambielo';	
	
		$this->sql="SELECT mod_id id,mod_estado estd FROM tbl_modulo WHERE mod_desc='".$this->g_desc()."'";
		if($opc=='M')$this->sql.=' AND mod_id!='.$this->g_id();
		
			 $this->ejecutar();		
			 if ($this->num_reg()>0){
				   $cmp = $this->campos();
				   if(strtoupper($cmp['estd'])=='A')  {
					  return 'El Modulo Ingresado ya Existe.';
					} 
				  $this->s_id($cmp['id']);			 
				  $this->sql="UPDATE tbl_modulo SET mod_estado='A',mod_user_mod='".$_SESSION['us_cuenta']."',
							  mod_fech_mod=NOW()WHERE mod_id=".$this->g_id();  
				  return $this->ejecutar();					  
			  }		
	}		
	
  $this->sql="CALL SEG_PRC_MANT_MODULO('".$this->g_opcion()."',".$this->g_id().",'".$this->g_desc()."',";
  $this->sql.=$this->g_orden().",'".$this->g_estado()."','".$_SESSION['us_cuenta']."');";  
  return $this->ejecutar();		 
} 	
}
?>
