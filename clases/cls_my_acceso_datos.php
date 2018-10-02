<?php 
abstract class cls_my_acceso_datos{ 
protected $server = 'localhost';
protected $user   = 'root';
protected $clave  = '';
protected $bd     = 'dinastia';

public    $rs = NULL;
protected $cn = NULL;

protected $sql     = '';
protected $numreg  = 0;
protected $idcb    = 0;
protected $enlace  = array();
protected $titulo  = array(); 
protected $perfil  = array();   
protected $enlace_vist = array(); 

public function s_enlace($value)     { $this->enlace[] = trim($value);  }
public function s_enlace_vita($value){ $this->enlace_vist[] = trim($value);}
public function s_titulo($value)     { $this->titulo[] = trim($value);  }	 
public function s_perfil($value)     { $this->perfil[] = trim($value);  }
public function s_cb($value)         { $this->idcb=intval($value);      }
public function g_cb()               { return $this->idcb;              }

 public function __construct(){
  try {
    if(!$this->cn){
      $this->cn = @mysqli_connect($this->server,$this->user,$this->clave)or trigger_error(mysqli_error(),E_USER_ERROR); 
      @mysqli_select_db($this->cn,$this->bd)or trigger_error(mysqli_error(),E_USER_ERROR);
     } 
  }catch(Exception $e){
   echo"Caught Exception('{$e->getMessage()}')\n{$e}\n";	
  }
 }

 public function ejecutar(){
  try{
   return $this->rs = @mysqli_query($this->cn,$this->sql);
  }catch(Exception $e){
   echo"Caught Exception('{$e->getMessage()}')\n{$e}\n";
  }		
 } 

 public function num_registro($msg ='No se Encontraron Registros'){ 
  $this->numreg = @mysqli_num_rows($this->rs)or die("<div class='error'>$msg</div>");
  return intval($this->numreg);
 } 	
  
 public function num_reg(){ 
  try {
    $this->numreg = @mysqli_num_rows($this->rs);
    return intval($this->numreg);
   }catch(Exception $e){
    echo"Caught Exception('{$e->getMessage()}')\n{$e}\n";	
   }
 }
  
 public function combo($id=0){
  while(($r=@mysqli_fetch_row($this->rs))){
   echo'<option value="'.$r[0].'"';
   if($id==$r[0]){ 
     $this->idcb=$r[0];
	 echo' selected="selected"';
    }
   echo'>'.$r[1].'</option>';
  } 
  @mysqli_free_result($this->rs);
 }
 
 public function campos(){ 
  return @mysqli_fetch_assoc($this->rs);
 } 
 
 public function dt_tabla(){
  echo'<table cellpadding="0" cellspacing="0" id="table" class="tinytable">';
  echo'<thead><tr><th class="nosort"><h3><img src="../images/icon/Grid.png"/></h3></th>';
  while(($r=@mysqli_fetch_field($this->rs))){
    if(strtolower($r->name)!='id'){
     echo'<th class="nosort"><h3>'.$r->name.'</h3></th>';     
    }
  }
  foreach($this->perfil as $key=> $val){
   echo'<th class="nosort"><h3>'.$val.'</h3></th>';
  }	   
  echo'</tr></thead><tbody>'; $x=1; 
   
  while(($r=@mysqli_fetch_row($this->rs))){
   echo'<tr>';
   echo'<td align="center">'.$x.'</td>';   
   foreach($r as $key => $val){
	if($key !=0)echo'<td>'.$val.'</td>';
   }				 
   foreach($this->perfil as $key => $val){
    echo'<td><a class="';
    if(strtolower($val)=='vista')echo'vista';	
	echo'" href="'.str_replace('*',$r[0],$this->enlace[$key]).'">'.$this->titulo[$key ].'</a>';
	echo'</td>';			
   }
   echo'</tr>';$x++;
  }
  echo'</tbody></table>'; 
  @mysqli_free_result($this->rs);
}
  
 function __destruct(){
   try{	 
	  if($this->cn){
		@mysqli_close($this->cn);
		$this->rs = NULL;
		$this->cn = NULL;
	  }
   }catch(Exception $e){
     echo"Caught Exception('{$e->getMessage()}')\n{$e}\n";	
   }   
 }
}?>