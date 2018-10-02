<?php session_start();
require_once 'cls_my_propiedades.php';
require_once 'cls_my_acceso_datos.php';

class cls_my_ciudad extends cls_my_acceso_datos{ 

protected $objpro = null;
protected $prv  = 0;
protected $pais = 0;		 

public function s_id_provincia($value){$this->prv  = intval($value);}
public function s_id_pais($value)     {$this->pais = intval($value);}
 	
function __construct(){
 parent::__construct();
 $this->objpro = new cls_my_propiedades;  
}

function __call($method, $args){
 return call_user_func_array(array($this->objpro,$method),$args);
}
 
public function consulta() {
 if ($this->g_id()){ 
  $this->sql='SELECT ciu_id Id,pai_id Pais,prv_id Provincia,ciu_desc Descripcion,ciu_estado Estado FROM tbl_ciudad WHERE ciu_id='.$this->g_id();
}else{
 $this->sql='SELECT ciu_id Id,ciu_desc Descripcion,prv_desc Provincia,pai_desc Pais,ciu_estado Estado FROM tbl_ciudad CIU LEFT OUTER JOIN tbl_provincia PRV ON(CIU.prv_id=PRV.prv_id)LEFT OUTER JOIN tbl_pais P	ON(CIU.pai_id=P.pai_id)WHERE ciu_estado ';
 
 if($_SESSION['us_tipuser']=='administrador'){ $this->sql.="IN('A','I')";
 }else $this->sql.="='A'";	 
 
 $this->sql.=' ORDER BY pai_desc,prv_desc,ciu_desc';  
}	
$this->ejecutar();
$this->num_registro();
}
public function relacion($prv){
 $this->sql='SELECT ciu_id, ciu_desc FROM tbl_ciudad WHERE prv_id='.$prv.' AND ciu_estado='."'A'".' ORDER BY ciu_desc';
 $this->ejecutar(); 
 return $this->num_reg();
}
public function transaccion(){
	
 $opc = $this->g_opcion();
 if($opc!='EL'){
  $this->sql="SELECT ciu_id id,ciu_estado estd FROM tbl_ciudad WHERE ciu_desc='".$this->g_desc()."' AND prv_id=".$this->prv;
 if($opc=='M')$this->sql.=' AND ciu_id!='.$this->g_id();
 
 $this->ejecutar();		
 if($this->num_reg()>0)  {
	 $cmp = $this->campos();
	 if(strtoupper($cmp['estd'])=='A'){return 'La Ciudad Ingresada ya Existe.';} 
	 
	 $this->s_id($cmp['id']);			 
	 $this->sql="UPDATE tbl_ciudad SET ciu_estado='A',ciu_user_mod='".$_SESSION['us_cuenta']."',";
	 $this->sql.='ciu_fech_mod=NOW()WHERE ciu_id='.$this->g_id(); 
	 return $this->ejecutar();		 
 } 	
}
 $this->sql="CALL MAN_PRC_CIUDAD('".$this->g_opcion()."',".$this->g_id().','.$this->prv.','.$this->pais.",'";
 $this->sql.=$this->g_desc()."','".$this->g_estado()."','".$_SESSION['us_cuenta']."');";
 return $this->ejecutar();					
} 	
}
?>