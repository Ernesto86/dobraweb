<?php session_start();
require_once 'cls_my_usuario_persona.php';
require_once 'cls_my_acceso_datos.php';

class cls_my_usuario extends cls_my_acceso_datos  { 
 protected static $objpro = null;
 function __construct(){
  parent::__construct();	
  $this->objpro = new cls_my_usuario_persona; 
 }
 function __call($method,$args){ return call_user_func_array(array($this->objpro,$method),$args); }
 
public function consulta() {
 if($this->g_id()){ 
  
  $this->sql='SELECT usu_id id,tip_user_id tip,per_id pers,usu_cuenta cuenta,usu_clave clave,usu_estado estd ';
  $this->sql.='FROM tbl_usuario JOIN tbl_personal USING(per_id)WHERE usu_id='.$this->g_id().' LIMIT 1;';
  
 }else{
	 
  $this->sql='SELECT usu_id Id,per_dir_img Image,tip_user_desc TipoUsuario,usu_cuenta Cuenta,CONCAT(per_apellido,';
  $this->sql.="' '".',per_nombre)ApellidosNombres,usu_estado Estado,usu_mod UserMod,usu_fech_mod FechaMod FROM tbl_usuario ';
  $this->sql.='INNER JOIN tbl_personal USING(per_id)INNER JOIN tbl_tipo_usuario USING(tip_user_id)WHERE usu_estado ';
  
  if($_SESSION['us_tipuser']=='administrador'){ 
    $this->sql.="IN('A','I')";
  }else $this->sql.="='A'";
  
  $this->sql.=" GROUP BY usu_id ORDER BY CONCAT(per_apellido,' ',per_nombre);";							 
  }			 
 $this->ejecutar();
 $this->num_registro();
}

public function datos(){
 $this->sql='SELECT per_id id,per_apellido ape,per_nombre nom,per_estado estd FROM tbl_personal WHERE per_id='.$this->g_id();
 $this->ejecutar();
 return $this->num_reg();
}
public function validar_acceso(){
 $this->sql="CALL SEG_PRC_USUARIO_ACCESO('".$this->g_cuenta()."','".$this->g_password()."')"; 
 $this->ejecutar();					
 return $this->num_reg();
}

public function transaccion (){
 $opc = $this->g_opcion();
  if($opc!='EL'){
     $this->sql="SELECT usu_id FROM tbl_usuario WHERE usu_cuenta='".$this->g_cuenta()."'";	 
	 if($opc=='M')$this->sql.=' AND usu_id!='.$this->g_id();
	 $this->ejecutar();
	 if($this->num_reg()>0){die('La Cuenta Ingresada ya Existe');}	

	$this->sql='SELECT usu_id FROM tbl_usuario JOIN tbl_personal USING(per_id)WHERE per_id='.$this->g_idpersona();
	$this->sql.=' AND tip_user_id='.$this->g_tipo_usuario();  	
	if($opc=='M')$this->sql.=' AND usu_id!='.$this->g_id();
	
	$this->ejecutar();
	if($this->num_reg()>0)die('Ya Cuenta con este Tipo de Usuario');  
  }
  
  $this->sql="CALL SEG_PRC_MANT_USUARIO('".$this->g_opcion()."',".$this->g_id().','.$this->g_tipo_usuario().',';
  $this->sql.=$this->g_idpersona().",'".$this->g_cuenta()."','".$this->g_password()."','".$this->g_estado()."','";
  $this->sql.=$_SESSION['us_cuenta']."');";
  return $this->ejecutar();						 				
} 	
}		  
?>