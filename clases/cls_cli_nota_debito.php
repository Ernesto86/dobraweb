<?php 
require_once 'cls_acceso_datos.php';
class cls_cli_nota_credito_producto extends cls_acceso_datos{	
protected $id_cliente='';
protected $tipo      ='CLI-DB';
protected $fecha     ='';
protected $nota      ='';
protected $cuenta    ='';
protected $cliente   ='';
protected $dtconcept ='';

protected $id_notdebit  ='';
protected $num_notdebit ='';
protected $id_asient    ='';
protected $num_asient   ='';

public $subtotdebit = 0;
public $totdebito   = 0; 

public function s_id_cliente($value){ 
 $this->id_cliente = trim($value); 
}
public function s_cliente($value){ 
 $this->cliente = trim($value); 
}
public function s_tipo($value){ 
 $this->tipo = trim($value); 
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
public function s_total_debito($value){ 
 $this->totdebito= floatval($value); 
}

function __construct(){ parent::__construct(); }

public function fn_cli_saldo_total(){
  $this->cn->SetFetchMode(ADODB_FETCH_ASSOC); 
  $this->sql="WEB_CLI_SALDO_TOTAL '".$this->id_cliente."'";
  $this->ejecutar();
  if($this->exist_reg()){
   $cmp = $this->campos();
   return floatval($cmp['saldo']);	  
  }return 0;
} 
public function fn_cli_nota_debito_contadores(){
	
  $this->cn->SetFetchMode(ADODB_FETCH_ASSOC); 
  $this->sql='WEB_PROC_CLI_NOTA_DEBITO_CONTADORES';
  $this->ejecutar();
  
  if($this->exist_reg()){
	 
	 $cmp = $this->campos();
	 
	 $this->id_notdebit  = trim($cmp['DebitoID']);
	 $this->num_notdebit = trim($cmp['DebitoNum']);
     $this->id_asient    = trim($cmp['AsientID']);
	 $this->num_asient   = trim($cmp['AsientNum']);
	 
	 if($this->id_notdebit and $this->num_notdebit and $this->id_asient and $this->num_asient) return true;
	 	 
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

public function fn_cli_guardar_nota_debito(){	
		
  try{
	
	$pc_cliente = $_SERVER["REMOTE_ADDR"];	
	 	
	$this->cn->StartTrans();
	
    if(!$this->fn_cli_nota_debito_contadores()){ die('{"err":"6"}'); } 	 
	
	$this->sql="CLI_Debitos_Insert '".$this->id_notdebit."','".$this->id_cliente."','".$this->id_asient."','".$this->fecha."','".$this->num_notdebit."','','','";
	$this->sql.=$this->dtconcept."','".$this->tipo."',".$this->subtotdebit.",0,".$this->totdebito.",0,'".$this->nota."','0000000001','".$this->cuenta."','00','";
	$this->sql.="$pc_cliente'";
	
	if(!$this->ejecutar()) $this->cn->FailTrans();
		
	$this->sql="CLI_DebitosRubros_Insert '".$this->id_notdebit."','0000000002','0000000086','0000000037',0,";
	$this->sql.=$this->subtotdebit.",0,".$this->totdebito.",'0000000002',1,'".$this->fecha_ini."','".$this->fecha_fin."','";
	$this->sql.=$this->detalle."','".$this->cuenta."','00','$pc_cliente'";
	
	if(!$this->ejecutar()) $this->cn->FailTrans();
	
    $this->sql="ACC_Asientos_Insert '".$this->id_asient."','".$this->num_asient."','".$this->id_notdebit."','".$this->fecha."','".$this->tipo."','";
	$this->sql.=$this->dtconcept."','".$this->nota."','0000000001','".$this->cuenta."','00','$pc_cliente'";
	
	if(!$this->ejecutar()) $this->cn->FailTrans();
		 
	$this->sql="ACC_AsientosDT_Insert '".$this->id_asient."','0000000086','".$this->dtconcept."',1,'0000000002',1,";
	$this->sql.=$this->subtotdebit.",".$this->totdebito.",'".$this->cuenta."','00','$pc_cliente'"; 
	
	if(!$this->ejecutar()) $this->cn->FailTrans();
	
	$this->sql="ACC_AsientosDT_Insert '".$this->id_asient."','0000000037','".$this->dtconcept."',0,'0000000002',1,";
	$this->sql.=$this->subtotdebit.",".$this->totdebito.",'".$this->cuenta."','00','$pc_cliente'";
	
	if(!$this->ejecutar()) $this->cn->FailTrans();
	
	 
	$id_cli_deuda = $this->fn_contador('CLI_CLIENTES_DEUDAS-ID-00'); 
	
	if(!$id_cli_deuda) $this->cn->FailTrans();
	
    $this->sql="CLI_ClientesDeudas_Insert '$id_cli_deuda','".$this->id_cliente."','".$this->id_notdebit."','".$this->id_asient."','".$this->num_notdebit."','";
	$this->sql.=$this->dtconcept."',".$this->subtotdebit.",".$this->totdebito.",'".$this->fecha."','".$this->fecha_fin."','";
	$this->sql.="0000000002','0000000086','0000000002',1,".$this->totdebito.",'".$this->tipo."',0,'','',0,'0000000001','".$this->cuenta."','00','$pc_cliente'";
	
	if(!$this->ejecutar()) $this->cn->FailTrans();
	
		
	$this->cn->CompleteTrans();	
	echo'{"rp":"1","doc":"'.$this->id_notcred.'"}';
		  
   }catch(Exception $e){	  
    	   
	$this->cn->CompleteTrans(); 
    echo"Caught Exception('{$e->getMessage()}')\n{$e}\n";	 	
   } 
 } 
}		  
?>