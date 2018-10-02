<?php session_start();
require_once 'cls_my_propiedades.php';
require_once 'cls_my_acceso_datos.php';

class cls_my_menu extends cls_my_acceso_datos { 
 protected $objpro = null;
 
 function __construct(){
  parent::__construct();	
  $this->objpro = new cls_my_propiedades;  
 }
 function __call($method, $args){return call_user_func_array(array($this->objpro,$method),$args);}
 
public function modulo(){
$this->sql='SELECT mod_id id,mod_desc des FROM tbl_tipo_usuario TU INNER JOIN tbl_transaccion TR ON(TU.tip_user_id=TR.tip_user_id AND tran_ingresar=1)INNER JOIN tbl_menu USING(men_id)INNER JOIN tbl_modulo USING(mod_id)WHERE TU.tip_user_id='.$_SESSION['us_idtipo']." AND mod_estado='A' GROUP BY mod_id ORDER BY mod_orden ASC;";	
$this->ejecutar();
$this->num_registro();
}
public function menu($mod_id){
$this->sql='SELECT men_id id,men_desc descrip,men_archivo arch,men_carga carg FROM tbl_tipo_usuario INNER JOIN tbl_transaccion USING(tip_user_id)INNER JOIN tbl_menu USING(men_id)INNER JOIN tbl_modulo USING(mod_id)WHERE tip_user_id='.$_SESSION['us_idtipo'].' AND mod_id='.intval($mod_id)." AND tran_ingresar=1 AND men_estado='A' GROUP BY men_id ORDER BY men_orden ASC";	
$this->ejecutar();
}  
public function modulos_menus(){
  $this->modulo(); 
  $md = $this->rs;	
  while(($rmd =@mysqli_fetch_row($md))){
   $this->menu($rmd[0]);
   if($this->num_reg()>0){
     echo'<li><span><a href="javascript:void(0);" id="link-posts">'.$rmd[1].'</a></span>';
     echo'<ul>';
	 while(($rme = @mysqli_fetch_row($this->rs))){
	  echo'<li><a href="'."$rme[3]?id=$rme[0]&menu=$rme[2]&tit=$rme[1]".'" class="add" target="iframe_cont">'.$rme[1].'</a></li>'; 
	 }    
     echo'</ul>';
     echo'</li>';	 
   }
  } 
} 
public function consulta() {
 if($this->g_id()){ 
  $this->sql='SELECT men_id id,mod_id modulo,men_desc descrip,men_orden orden,men_estado estado FROM tbl_menu WHERE men_id='.$this->g_id();
 }else{
  $this->sql='SELECT men_id Id,men_desc Menu,mod_desc Modulo,men_orden Orden,men_estado Estado FROM tbl_menu ME JOIN tbl_modulo MO ON(ME.mod_id=MO.mod_id)WHERE men_estado ';
  
  if($_SESSION['us_tipuser']=='administrador'){ $this->sql.="IN('A','I')";
  }else $this->sql.="='A'";
  
  $this->sql.='ORDER BY mod_orden,men_orden;';   	 
 }	  
 $this->ejecutar();
 $this->num_registro();
}
public function transaccion (){
$opc=$this->g_opcion();
if($opc!='EL'){
  $this->sql='SELECT men_id id FROM tbl_menu WHERE mod_id='.$this->g_modulo().' AND men_orden='.$this->g_orden(); 
  if($opc=='M')$this->sql.=' AND men_id!='.$this->g_id();
  $this->ejecutar();		
  if($this->num_reg()>0)return'Ya Existe este Orden Cambielo'; 

  $this->sql="SELECT men_id id,men_estado estd FROM tbl_menu WHERE men_desc='".$this->g_desc()."'";
  if($opc=='M')$this->sql.=' AND men_id!='.$this->g_id();
		 $this->ejecutar();		
		 if ($this->num_reg()>0)  {
			   $cmp = $this->campos();
			   if(strtoupper($cmp['estd'])=='A')  {
				  return 'El Menu Ingresado ya Existe.';
				} 
			  $this->s_id($cmp['id']);			 
			  $this->sql="UPDATE tbl_menu SET men_estado='A',men_user_mod='".$_SESSION['us_cuenta']."',
						  men_fech_mod=NOW()WHERE men_id=".$this->g_id(); 
			  $this->ejecutar();	
			  return $this->g_id();
		  }
}	
$this->sql="CALL SEG_PRC_MANT_MENU('".$this->g_opcion()."',".$this->g_id().','.$this->g_modulo().",'".$this->g_desc()."','','',";
$this->sql.=$this->g_orden().",'".$this->g_estado()."','".$_SESSION['us_cuenta']."')";
return $this->ejecutar();    	 
} 	  
}  
?>