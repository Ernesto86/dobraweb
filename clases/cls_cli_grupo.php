<?php 
require_once 'cls_acceso_datos.php';
class cls_cli_grupo extends cls_acceso_datos { 
function __construct(){ parent::__construct();}
protected $id;
public function s_id($value){ $this->id = $value;}
public function fn_cli_grupo_codigo(){
 $this->cn->SetFetchMode(ADODB_FETCH_NUM); 
 $this->sql='SELECT Código id FROM CLI_GRUPOS WHERE ID='."'".$this->id ."'";
 $this->ejecutar();
 if($this->exist_reg()){return'{"cod":"'.strval(trim($this->rs->fields[0])).'"}';}
 return'{"err":"0"}';  
}
public function fn_cli_grupo_treeview(){
  $this->cn->SetFetchMode(ADODB_FETCH_NUM);  
  $niv1 = $this->fn_cli_grupo_data();
  $niv2 = array(); 
  $niv3 = array(); 
  $niv4 = array(); 
  foreach($niv1 as $k => $v){
     echo'<li><span class="folder" id="'.$v.'">'.$k.'</span>';    
        if(!count($niv2)){
          $niv2 = $this->fn_cli_grupo_data(17,1);
	    }
		if($niv2[$k]){
		   echo'<ul class="url">';
		    foreach($niv2 [$k] as $k1 => $v1){
		      echo'<li><span class="folder" id="'.$v1.'" >'.$k1.'</span>'; 
			        if(!count($niv3)){
					  $niv3 = $this->fn_cli_grupo_data(28,2);
					}
					if($niv3[$k1]){
					  echo'<ul class="url">';
					    foreach($niv3 [$k1] as $k2 => $v2){
		                  echo'<li><span class="folder" id="'.$v2.'" >'.$k2.'</span>';
						      if(!count($niv4)){
								$niv4 = $this->fn_cli_grupo_data(39,3);
							  }
						      if($niv4[$k2]){
							     foreach($niv4 [$k2] as $k3 => $v3){
		                           echo'<li><span class="folder" id="'.$v3.'" >'.$k3.'</span></li>';
								 }	
							  }						  
						  echo'</li>'; 
					    }
					  echo'</ul>';
					}
			   echo'</li>';				   			
		    }	
		   echo'</ul>'; 	
		}  
	 echo'</li>';
   }	   
 }  
 function fn_cli_grupo_data($val=6 , $ord=0){
  $this->sql='WEB_CLI_SELECT_GRUPO_TREEVIEW '.$val ;
  $this->ejecutar(); $data = array();
  if(!$this->exist_reg())return false;
  while(!$this->rs->EOF){
   $str = explode('/',$this->rs->fields[0]);
   $k = trim($str[$ord]);
   if($ord){
     $ky = trim($str[$ord-1]);
     $data[$ky][$k] = strval($this->rs->fields[1]);
   }else{ 
     $data[$k] = strval($this->rs->fields[1]);  
   } $this->rs->MoveNext(); 
  }
  $this->rs->Close();
  return $data;
 } 
}		  
?>
