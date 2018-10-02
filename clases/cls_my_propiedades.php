<?php
class cls_my_propiedades { 
protected $opc       = '';
protected $id        = 0;
protected $dir_img   = '';
protected $tip       = 0;
protected $desc      = '';
protected $estd      = 'A';
protected $modulo    = 0;
protected $menu      = 0;
protected $abreviat  = '';
protected $fecha_1   = '';
protected $fecha_2   = '';
protected $limit     = 4;
protected $reg_emp   = 0;
protected $orden     =0;

public function s_opcion($value){$this->opc  =$value;}
public function g_opcion() { return $this->opc; }

public function s_id($value) {$this->id = intval($value);}
public function g_id(){return $this->id; }
  
public function s_dir_image($value) { $this->dir_image  = trim($value);}
public function g_dir_image() {return $this->dir_image;} 

public function s_tip($value) {$this->tip  = intval($value);}
public function g_tip() {return $this->tip;}

public function s_desc($value) {$this->desc  = trim($value);}
public function g_desc() {return $this->desc;}

public function s_estado($value) {$this->estd  = utf8_decode($value);}
public function g_estado() {return $this->estd;}

public function s_modulo($value) {$this->modulo = intval($value);}
public function g_modulo() {return $this->modulo;}

public function s_menu($value) {$this->menu  = intval($value);}
public function g_menu() {return $this->menu;}

public function s_abreviatura($value) {$this->abreviat = utf8_decode(trim($value));}
public function g_abreviatura() { return $this->abreviat;}

public function s_fecha_1($value) {$this->fecha_1 = $value;}
public function g_fecha_1() {return $this->fecha_1;}

public function s_fecha_2($value) {$this->fecha_2 = $value;}
public function g_fecha_2() {return $this->fecha_2;}

public function s_limite($value) {$this->limit = (int)$value;}
public function g_limite() {return $this->limit;}

public function s_orden($value){ $this->orden=intval($value); }
public function g_orden(){ return $this->orden; }

public function s_reg_empzar($value){$this->reg_emp =(int)$value;}
public function g_reg_empzar(){
if($this->reg_emp < 1) return 0;
return ($this->reg_emp-1)*$this->limit;
}
}?>		