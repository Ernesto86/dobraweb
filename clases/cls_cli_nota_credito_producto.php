<?php 
require_once 'cls_acceso_datos.php';
class cls_cli_nota_credito_producto extends cls_acceso_datos{	
protected $id_fact    =''; 
protected $id_cliente ='';
protected $id_bodega  ='';
protected $tipo_fact  ='CLI-CR-PD';
protected $fecha   ='';
protected $nota    ='';
protected $cuenta  ='';
protected $cliente  ='';
protected $dtconcept ='';
protected $cost_tot_prod = 0;

protected $id_notcred  ='';
protected $num_notcred ='';
protected $sec_notcred ='';
protected $id_asient   ='';
protected $num_asient  ='';

public $totcredito = 0; 

public $arr_fact_det_id ;
public $arr_saldo_det   ;
public $arr_val_devuelto;
public $arr_val_cantidad;
public $arr_val_credito ;
public $arr_id_producto ;

public $arr_id_bodega  ;
public $arr_precio_pvp ;
public $arr_costo_prod ; 
public $arr_subtotal   ; 
public $arr_deuda_id   ; 
public $arr_fact_num   ;
public $arr_tip_fact   ; 
public $arr_det_fact   ; 
public $arr_cuenta_id  ;

public $arr_fech_emis  ;
public $arr_fech_venc  ;

public $arr_rubro_id   ;
public $arr_cred_id    ; 

public function s_id_factura($value){ 
 $this->id_fact = trim($value); 
}
public function s_id_cliente($value){ 
 $this->id_cliente = trim($value); 
}
public function s_cliente($value){ 
 $this->cliente = trim($value); 
}
public function s_id_bodega($value){ 
 $this->id_bodega = trim($value); 
}
public function s_tipo_factura($value){ 
 $this->tipo_fact = trim($value); 
}
public function s_fecha($value){ 
 $this->fecha = trim($value); 
}
public function s_dtconcept($value){ 
 $this->dtconcept = trim($value); 
}
public function s_nota($value){ 
 $this->nota = trim($value); 
}
public function s_id_usuario($value){ 
 $this->cuenta = trim($value); 
} 
public function s_total_credito($value){ 
 $this->totcredito = floatval($value); 
}

function __construct(){ 

  parent::__construct();
  
  $this->arr_fact_det_id  = array();
  $this->arr_saldo_det    = array();
  $this->arr_val_devuelto = array();
  $this->arr_val_cantidad = array();
  $this->arr_val_credito  = array();
  $this->arr_id_producto  = array();
  //$this->arr_id_bodega    = array();
  
  $this->arr_precio_pvp  = array();
  $this->arr_costo_prod  = array(); 
  $this->arr_subtotal    = array(); 
  $this->arr_deuda_id    = array();
  $this->arr_fact_num    = array();
  $this->arr_tip_fact    = array();
  $this->arr_det_fact    = array();
  $this->arr_cuenta_id   = array();
    
  $this->arr_fech_emis   = array();
  $this->arr_fech_venc   = array();
    
  $this->arr_rubro_id    = array();
  $this->arr_cred_id     = array();
}

public function fn_cli_saldo_total(){
  $this->cn->SetFetchMode(ADODB_FETCH_ASSOC); 
  $this->sql="WEB_CLI_SALDO_TOTAL '".$this->id_cliente."'";
  $this->ejecutar();
  if($this->exist_reg()){
   $cmp = $this->campos();
   return floatval($cmp['saldo']);	  
  }return 0;
} 
public function fn_cli_nota_cred_prod_contadores(){
	
  $this->cn->SetFetchMode(ADODB_FETCH_ASSOC); 
  $this->sql='WEB_PROC_CLI_NOTA_CRED_PROD_CONTADORES';
  $this->ejecutar();
  
  if($this->exist_reg()){
	 
	 $cmp = $this->campos();
	 
	 $this->id_notcred  = trim($cmp['NotCrdID']);
	 $this->num_notcred = trim($cmp['NotCrdNum']);
	 $this->sec_notcred = trim($cmp['NotCrdSec']);	  
	 $this->id_asient   = trim($cmp['AsientID']);
	 $this->num_asient  = trim($cmp['AsientNum']);
	 
	 if($this->id_notcred and $this->num_notcred and $this->id_asient and $this->num_asient) return true;
	 	 
  }
  return false;
}  

public function fn_contador($contador,$sp='SIS_GetNextID'){
  $this->cn->SetFetchMode(ADODB_FETCH_NUM); 	
  $this->sql="$sp '$contador'";
  $this->ejecutar();
  if($this->exist_reg()){	    
    $cmp = $this->campos();
    $num = strval($cmp[0]);	  	  
    return str_pad($num,10,'0',STR_PAD_LEFT); 
  }
  return false;
} 

public function fn_ven_guardar_nota_credito_producto(){	
		
  try{
	
	$pc_cliente = $_SERVER["REMOTE_ADDR"];	
	 	
	$this->cn->StartTrans();
	
    if(!$this->fn_cli_nota_cred_prod_contadores()){ die('{"err":"6"}'); } 	 
   	   
	$this->sql ="CLI_Creditos_Insert1 '".$this->id_notcred."','".$this->id_cliente."','".$this->id_asient."','".$this->id_fact."','','";
	$this->sql.=$this->sec_notcred."','','".$this->fecha."','".$this->num_notcred."','".$this->dtconcept."','".$this->tipo_fact."',";
	$this->sql.=number_format($this->totcredito,2).",'".$this->nota."','0000000001','".$this->cuenta."','00','$pc_cliente'";
	
	if(!$this->ejecutar()) $this->cn->FailTrans();
	

	foreach($this->arr_val_devuelto as $key => $val){
		
	 if(intval($val) > 0){			
	 	   
	  $this->sql="CLI_Creditos_Productos_Insert '".$this->id_fact."','".$this->arr_fact_det_id[$key]."','".$this->id_notcred."','";
	  $this->sql.=$this->arr_id_producto[$key]."','".$this->id_bodega."',".$this->arr_val_cantidad[$key].','.$val.',';
	  $this->sql.=$this->arr_precio_pvp[$key].','.$this->arr_costo_prod[$key].','.number_format($this->arr_subtotal[$key],2).',';
	  $this->sql.='0,0,0,0,0,0,0,0,0,0,'.number_format($this->arr_subtotal[$key],2).',0,';
	  $this->sql.="'','EDITABLE',1,'','".$this->cuenta."','00','$pc_cliente'";      
      
      if(!$this->ejecutar()) $this->cn->FailTrans(); 
	      	   
	  $this->sql="VEN_FacturasDT_Update_Devuelto '".$this->arr_fact_det_id[$key]."',".$val;
	  
	  if(!$this->ejecutar()) $this->cn->FailTrans();
	 	   
	  $cost_prod  = floatval($this->arr_costo_prod[$key] * intval($val));
	  	   
	  $this->cost_tot_prod += $cost_prod;
	   	   
	 }	
	 
	}	
				
	foreach($this->arr_val_credito as $key => $val){
		
	  if(floatval($val) > 0){	
	   	
	   $this->sql ="CLI_CreditosDeudas_Insert '".$this->id_notcred."','".$this->arr_deuda_id[$key]."','".$this->fecha."','".$this->arr_tip_fact[$key]."','";
	   $this->sql.=$this->arr_fact_num[$key]."','".$this->arr_det_fact[$key]."',".$this->arr_saldo_det[$key].','.number_format($val,2).',';
	   $this->sql.="0,'0000000002','1',1,'0','0000000001','".$this->arr_cuenta_id[$key]."','".$this->cuenta."','00','$pc_cliente'"; 
	   
	   if(!$this->ejecutar()) $this->cn->FailTrans();
	  	  
	  }
		
	 }
		 
	$this->sql ="ACC_Asientos_Insert '".$this->id_asient."','".$this->num_asient."','".$this->id_notcred."','".$this->fecha."','".$this->tipo_fact."','";
	$this->sql.=$this->dtconcept."','".$this->nota."','0000000001','".$this->cuenta."','00','$pc_cliente'"; 
	
	if(!$this->ejecutar()) $this->cn->FailTrans();
	
	$this->sql ="ACC_AsientosDT_Insert '".$this->id_asient."','0000000710','".$this->dtconcept."',1,'0000000002',1,".number_format($this->totcredito,2).',';
	$this->sql.=number_format($this->totcredito,2).",'".$this->cuenta."','00','$pc_cliente'";
	
	if(!$this->ejecutar()) $this->cn->FailTrans();
	
	$this->sql ="ACC_AsientosDT_Insert '".$this->id_asient."','0000000200','".$this->dtconcept."',1,'0000000002',1,".number_format($this->cost_tot_prod,2).',';
	$this->sql.=number_format($this->cost_tot_prod,2).",'".$this->cuenta."','00','$pc_cliente'"; 
	
	if(!$this->ejecutar()) $this->cn->FailTrans();
	
	$this->sql ="ACC_AsientosDT_Insert '".$this->id_asient."','0000000142','".$this->dtconcept."',0,'0000000002',1,".number_format($this->cost_tot_prod,2).',';
	$this->sql.=number_format($this->cost_tot_prod,2).",'".$this->cuenta."','00','$pc_cliente'";
	
	if(!$this->ejecutar()) $this->cn->FailTrans();
	

	foreach($this->arr_val_credito as $key => $val){
		
	  if(floatval($val) > 0){
		   
		$this->sql ="ACC_AsientosDT_Insert '".$this->id_asient."','".$this->arr_cuenta_id[$key]."','".$this->dtconcept."',0,'0000000002',1,";
		$this->sql.=number_format($val,2).','.number_format($val,2).",'".$this->cuenta."','00','$pc_cliente'"; 
		
		if(!$this->ejecutar()) $this->cn->FailTrans();   
			
	  }
	
	}
	
	foreach($this->arr_val_devuelto as $key => $val){
		
	 if(intval($val) > 0){
		 
	  $this->sql ="INV_ProductosCardex_Insert '".$this->arr_id_producto[$key]."','".$this->id_bodega."','".$this->id_asient."','";
	  $this->sql.=$this->id_notcred."','".$this->num_notcred."','".$this->fecha."','".$this->tipo_fact."','".$this->dtconcept."',0,$val,";
	  $this->sql.=$this->arr_costo_prod[$key].",'0000000002',1,'0000000001','".$this->cuenta."','00','$pc_cliente'";
	 
	  if(!$this->ejecutar()) $this->cn->FailTrans();
	  	  	 
	 }
	 
	}
			
	foreach($this->arr_val_credito as $key => $val){
		
	  if(floatval($val) > 0){
		  	
	   $id_cli_deuda = $this->fn_contador('CLI_CLIENTES_DEUDAS-ID-00'); 
		
	   if(!$id_cli_deuda) $this->cn->FailTrans();	
		
	   $this->sql="CLI_ClientesDeudas_Insert '$id_cli_deuda','".$this->id_cliente."','".$this->id_notcred."','".$this->id_asient."','".$this->num_notcred."','";
	   $this->sql.=$this->dtconcept."',".number_format($val,2).",".number_format($val,2).",'".$this->fecha."','".$this->arr_fech_venc[$key]."','";
	   $this->sql.=$this->arr_rubro_id[$key]."','".$this->arr_cuenta_id[$key]."','0000000002',1,0,'".$this->tipo_fact."',1,'".$this->arr_deuda_id[$key]."','','0',";
	   $this->sql.="'0000000001','".$this->cuenta."','00','$pc_cliente'";
	
	   if(!$this->ejecutar()) $this->cn->FailTrans();
		
	  }	  
	
	}	 		 
	
	$this->cn->CompleteTrans();	
	echo'{"rp":"1","doc":"'.$this->id_notcred.'"}';
		  
   }catch(Exception $e){	  
    	   
	$this->cn->CompleteTrans(); 
    echo"Caught Exception('{$e->getMessage()}')\n{$e}\n";	 	
   } 
 } 
}		  
?>