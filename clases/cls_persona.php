<?php 
class cls_persona{ 
protected $opc     ='I';
protected $id      ='';
protected $cod     ='';
protected $tipo    ='';
protected $cuenta  ='';
protected $passw   ='';
protected $dir_image = '';
protected $docu = '';
protected $nomb = '';
protected $ape  = '';
protected $tip_sang  = '';
protected $sex       = '';
protected $telf      = '';
protected $mobil     = '';
protected $trat      = '';
protected $fech_nace = '';
protected $edad      = '';
protected $direc     = '';
protected $estd_civ  = '';
protected $ocupac    = '';
protected $email     = '';
protected $estd      = 0;
protected $nomb_abrev = '';
protected $pais = 0;
protected $provincia = 0;
protected $ciudad    = 0;
protected $canton    = '';
protected $observ    = '';
protected $limit = 6;
protected $reg_emp = 0;
protected $fecha_1 = '';
protected $fecha_2 = '';

public function s_opcion($value){$this->opc  = trim($value); }
public function g_opcion(){return $this->opc;}

public function s_id($value){$this->id  = trim($value); }
public function g_id(){ return $this->id; }

public function s_tipo($value){$this->tipo  = trim($value); }
public function g_tipo(){ return $this->tipo; }

public function s_codigo($value){$this->cod  = trim($value); }
public function g_codigo(){ return $this->cod; }

public function s_cuenta($value){$this->cuenta = utf8_decode(trim($value));}
public function g_cuenta(){return strtolower($this->cuenta);}

public function s_password($value){ $this->passw = utf8_decode(trim($value));}
public function g_password(){ return $this->passw; }

public function s_documento($value){$this->docu  = utf8_decode(trim($value)); }
public function s_dir_image($value){$this->dir_image = $value;}

public function s_nombre($value){$this->nomb = utf8_decode(trim($value));}
public function g_nombre(){return $this->nomb;}

public function s_apellido($value){$this->ape = utf8_decode(trim($value));}
public function s_nomb_abrev($value){$this->nomb_abrev = utf8_decode(trim($value));}
public function s_tip_sangre($value){$this->tip_sang = utf8_decode(trim($value)); }
public function s_sexo($value){$this->sex= trim($value);}
public function s_telefono($value){$this->telf = trim($value); }
public function s_mobil($value){$this->mobil = trim($value); }
public function s_tratamiento($value){$this->trat = trim($value); }
public function s_fecha_nace($value){$this->fech_nace = trim($value);}
public function s_edad($value){$this->edad = utf8_decode(trim($value));}
public function s_direccion($value){$this->direc = utf8_decode(trim($value));}
public function s_estdo_civil($value){$this->estd_civ = trim($value);}
public function s_ocupacion($value){$this->ocupac= utf8_decode(trim($value));}
public function s_email($value){$this->email= utf8_decode(trim($value));}
public function s_estado($value){$this->estd  = trim($value);}
public function s_pais($value){$this->pais  = (int)$value;}
public function s_provincia($value){$this->provincia  = (int) $value;}
public function s_ciudad($value){$this->ciudad  = (int) $value;}
public function s_canton($value){$this->canton  = utf8_decode(trim($value));}
public function s_observacion($value){$this->observ = utf8_decode(trim($value));}
public function g_observacion(){return $this->observ;}
public function s_limite($value){$this->limit = (int)$value;}
public function s_reg_empzar($value){$this->reg_emp = (int)$value;}
public function g_reg_empzar(){
 if($this->reg_emp < 1) return 0;
 return($this->reg_emp-1)*$this->limit;
}
public function s_fecha_1($value){$this->fecha_1 = trim($value);}
public function g_fecha_1(){ return $this->fecha_1;}

public function s_fecha_2($value){$this->fecha_2 = trim($value);}
public function g_fecha_2(){ return $this->fecha_2;}
}?>		