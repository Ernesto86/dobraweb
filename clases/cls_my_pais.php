<?php session_start();
require_once 'cls_my_propiedades.php';
require_once 'cls_my_acceso_datos.php';

class cls_my_pais extends cls_my_acceso_datos{ 

protected $objpro = null;
function __construct(){
 parent::__construct();
 $this->objpro = new cls_my_propiedades;  
}
function __call($method, $args){return call_user_func_array(array($this->objpro,$method),$args);}

public function consulta() {
  $this->sql ='SELECT pai_id Id,pai_desc Descripcion,pai_estado Estado FROM tbl_pais WHERE pai_estado ';
  if($_SESSION['us_tipuser']=='administrador'){ $this->sql.="IN('A','I')";
  }else $this->sql.="='A'";		   		   
  
  if($this->g_id() >0)$this->sql.="AND pai_id=".$this->g_id();	 	
   
  $this->sql.=' ORDER BY pai_desc';  
  $this->ejecutar();
  $this->num_registro();
 }
 public function cb_pais() {
  $this->sql ='SELECT pai_id Id,pai_desc Descripcion,pai_estado Estado FROM tbl_pais WHERE pai_estado='."'A'".' ORDER BY pai_desc';
  $this->ejecutar();
  return $this->num_reg();
 }
 
 public function transaccion (){
	$opc = $this->g_opcion();
	if($opc!='EL'){
	  $this->sql="SELECT pai_id id,pai_estado estd FROM tbl_pais WHERE pai_desc='".$this->g_desc()."'";
	  if($opc=='M')$this->sql.=' AND pai_id!='.$this->g_id();
		 $this->ejecutar();		
		 if ($this->num_reg()>0)  {
			   $cmp = $this->campos();
			   if(strtoupper($cmp['estd'])=='A')  {
				  return 'El Pais Ingresado ya Existe.';
				} 
			  $this->s_id($cmp['id']);			 
			  $this->sql="UPDATE tbl_pais SET pai_estado='A',pai_user_mod='".$_SESSION['us_cuenta']."'";
			  $this->sql=',pai_fech_mod=NOW()WHERE pai_id='.$this->g_id();		  
			  $this->ejecutar();
			  return $this->g_id();			 
		  }
	}
	
	$this->sql="CALL MAN_PRC_PAIS('".$this->g_opcion()."',".$this->g_id().",'".$this->g_desc()."','".$this->g_estado()."','";
	$this->sql.=$_SESSION['us_cuenta']."');";
	return $this->ejecutar();				 
} 	
}
?>