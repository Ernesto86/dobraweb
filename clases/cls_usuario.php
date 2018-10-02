<?php
require_once 'cls_acceso_datos.php';
require_once 'cls_persona.php';

class cls_usuario extends cls_acceso_datos
{
    protected static $objpro = null;

    function __construct()
    {
        parent::__construct();
        $this->objpro = new cls_persona;
    }

    function __call($method, $args)
    {
        return call_user_func_array(array($this->objpro, $method), $args);
    }

    public function fn_seg_validar_acceso()
    {
        $this->cn->SetFetchMode(ADODB_FETCH_ASSOC);
        $this->sql = "WEB_SEG_ACCESO_USUARIO '" . $this->g_cuenta() . "','" . $this->g_password() . "';";
        $this->ejecutar();
        return $this->exist_reg();
    }

    public function fn_seg_guardar_password()
    {
        $this->sql = "WEB_SEG_ACTUALIZAR_PASSWORD '" . $this->g_id() . "','" . $this->g_password() . "';";
        $this->ejecutar();
        return true;
    }
}

?>