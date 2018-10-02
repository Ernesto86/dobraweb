<?php
require_once 'cls_my_persona.php';

class cls_my_usuario_persona extends cls_my_persona
{
    protected $tipo_usuario = 0;
    protected $idpersona = 0;

    public function s_tipo_usuario($value)
    {
        $this->tipo_usuario = (int)$value;
    }

    public function g_tipo_usuario()
    {
        return $this->tipo_usuario;
    }

    public function s_idpersona($value)
    {
        $this->idpersona = (int)$value;
    }

    public function g_idpersona()
    {
        return $this->idpersona;
    }
} ?>