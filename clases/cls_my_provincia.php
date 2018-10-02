<?php session_start();
require_once 'cls_my_propiedades.php';
require_once 'cls_my_acceso_datos.php';

class cls_my_provincia extends cls_my_acceso_datos{ 

protected $objpro = null;
protected $pais = 0;

public function s_id_pais($value){ $this->pais=intval($value);}

function __construct(){
 parent::__construct();
 $this->objpro = new cls_my_propiedades;  
}

function __call($method, $args){return call_user_func_array(array($this->objpro,$method),$args);} 

public function consulta() {
  if ($this->g_id()){ 
   $this->sql='SELECT prv_id Id,pai_id Pais,prv_desc Descripcion,prv_estado Estado FROM tbl_provincia WHERE prv_id='.$this->g_id();
   
  }else{
	  
   $this->sql='SELECT prv_id Id,prv_desc Descripcion,pai_desc Pais,prv_estado Estado FROM tbl_provincia PV LEFT OUTER JOIN';
   $this->sql.=' tbl_pais P ON(PV.pai_id=P.pai_id)WHERE prv_estado ';	
   			 
  if($_SESSION['us_tipuser']=='administrador'){ $this->sql.="IN('A','I')";
  }else $this->sql.="='A'";	
  	
  $this->sql.=' ORDER BY pai_desc, prv_desc';    
  }	 		 
  $this->ejecutar();
  $this->num_registro();
 }
 public function relacion($pais) {
  $this->sql ='SELECT prv_id, prv_desc FROM tbl_provincia WHERE pai_id='.$pais.' AND prv_estado='."'A'".' ORDER BY prv_desc';
  $this->ejecutar();
  return $this->num_reg();
 }
public function transaccion (){
$opc = $this->g_opcion();
if($opc!='EL'){
$this->sql="SELECT prv_id id,prv_estado estd FROM tbl_provincia WHERE prv_desc='".$this->g_desc()."' AND pai_id=".$this->pais;
if($opc=='M')$this->sql.=' AND prv_id!='.$this->g_id();

			 $this->ejecutar();		
			 if ($this->num_reg()>0)  {
				   $cmp = $this->campos();
				   if(strtoupper($cmp['estd'])=='A')  {
					  return 'La Provincia Ingresada ya Existe.';
					} 
				  $this->s_id($cmp['id']);			 
				  $this->sql="UPDATE tbl_provincia SET prv_estado='A',prv_user_mod='".$_SESSION['us_cuenta']."',";
				  $this->sql.='prv_fech_mod=NOW()WHERE prv_id='.$this->g_id();
				  $this->ejecutar();
				  return $this->g_id();			 
			  }
}	   
 $this->sql="CALL MAN_PRC_PROVINCIA('".$this->g_opcion()."',".$this->g_id().','.$this->pais.",'".$this->g_desc()."','";
 $this->sql.=$this->g_estado()."','".$_SESSION['us_cuenta']."');";     
 return $this->ejecutar();				
} 	
}	  
?>