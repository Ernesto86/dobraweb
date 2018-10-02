<?php session_start();
require_once 'cls_my_propiedades.php';
require_once 'cls_my_acceso_datos.php';

class cls_my_tipo_usuario extends cls_my_acceso_datos{ 

protected $objpro = null;
 function __construct(){
  parent::__construct();	
  $this->objpro = new cls_my_propiedades; }
 function __call($method, $args){return call_user_func_array(array($this->objpro,$method),$args);} 
 
public function consulta() {
  $this->sql='SELECT tip_user_id Id,tip_user_desc Descripcion,tip_user_estado Estado FROM tbl_tipo_usuario WHERE tip_user_estado ';
  if($_SESSION['us_tipuser']=='administrador'){ $this->sql.="IN('A','I')";
  }else $this->sql.="='A' AND tip_user_desc NOT IN('administrador')";		
  
  if($this->g_id()>0) $this->sql.=" AND tip_user_id=".$this->g_id();  
  $this->ejecutar();
  $this->num_registro();
}

public function cb_tipo_usuario(){
 $this->sql='SELECT tip_user_id Id,tip_user_desc Descripcion,tip_user_estado Estado FROM tbl_tipo_usuario WHERE tip_user_estado='."'A'";
 if($_SESSION['us_tipuser']!='administrador'){$this->sql.=" AND tip_user_desc NOT IN('administrador')"; }	
 $this->sql.=' ORDER BY tip_user_desc;';
 $this->ejecutar();
 return $this->num_reg();
}
public function transaccion (){
 $opc = $this->g_opcion();
if($opc!='EL'){
   $this->sql="SELECT tip_user_id id,tip_user_estado estd FROM tbl_tipo_usuario WHERE tip_user_desc='".$this->g_desc()."'";
   if($opc=='M')$this->sql.=' AND tip_user_id!='.$this->g_id();	  						 
	 $this->ejecutar();		
	 if ($this->num_reg()>0)  {
		   $cmp = $this->campos();
		   if(strtoupper($cmp['estd'])=='A'){
			  return 'El Tipo de Usuario Ingresado ya Existe.';
			} 
		  $this->s_id($cmp['id']);			 
		  $this->sql="UPDATE tbl_tipo_usuario SET tip_user_estado='A',tip_user_mod='".$_SESSION['us_cuenta']."',
					  tip_fech_mod=NOW()WHERE tip_user_id =".$this->g_id(); 
		  $this->ejecutar();	 
		  return $this->g_id();
	  }
}
$this->sql="CALL SEG_PRC_MANT_TIPO_USUARIO('".$this->g_opcion()."',".$this->g_id().",'".$this->g_desc()."','";
$this->sql.=$this->g_estado()."','".$_SESSION['us_cuenta']."');";
return $this->ejecutar();				
} 	
}	  
?>
