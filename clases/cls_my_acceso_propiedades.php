<?php
require_once 'cls_my_propiedades.php';
class cls_my_acceso_propiedades extends cls_my_propiedades{ 
protected $tipusuario =0;
protected $ingre =0;
protected $agreg =0;
protected $mod   =0;
protected $elim  =0;
public function s_tipo_usuario($value){$this->tipusuario=(int)$value;}
public function g_tipo_usuario(){ return $this->tipusuario; }

public function s_ingreso($value) {$this->ingre =intval($value);}
public function g_ingreso(){return $this->ingre; }

public function s_agregar($value) {$this->agreg =intval($value);}
public function g_agregar(){return $this->agreg; }  

public function s_modificar($value) {$this->mod =intval($value);}
public function g_modificar(){return $this->mod; } 

public function s_eliminar($value) {$this->elim =intval($value);}
public function g_eliminar(){return $this->elim; } 
}?>	