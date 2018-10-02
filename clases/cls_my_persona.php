<?php
define('SAL', 'CrazyassLongSALTThatMakesYourUsersPasswordVeryLong123!!312567__asdSdas');

class cls_my_persona
{
    protected $opc = 'I';
    protected $id = 0;
    protected $tip_doc = '';
    protected $cuenta = '';
    protected $passw = '';
    protected $dir_img = '';
    protected $docu = '';
    protected $nomb = '';
    protected $ape = '';
    protected $sex = '';
    protected $telf = '';
    protected $movil = '';
    protected $trat = '';
    protected $fech_nace = '';
    protected $edad = '';
    protected $direc = '';
    protected $estd_civ = '';
    protected $email = '';
    protected $estd = 'A';
    protected $pais = 0;
    protected $provincia = 0;
    protected $ciudad = 0;
    protected $observ = '';
    protected $limit = 6;
    protected $reg_emp = 0;
    protected $fecha_1 = '';
    protected $fecha_2 = '';

    public function s_opcion($value)
    {
        $this->opc = trim($value);
    }

    public function g_opcion()
    {
        return $this->opc;
    }

    public function s_id($value)
    {
        $this->id = intval($value);
    }

    public function g_id()
    {
        return $this->id;
    }

    public function s_tipo_doc($value)
    {
        $this->tip_doc = utf8_decode(trim($value));
    }

    public function g_tipo_doc()
    {
        return $this->tip_doc;
    }

    public function s_cuenta($value)
    {
        $this->cuenta = utf8_decode(trim($value));
    }

    public function g_cuenta()
    {
        return $this->cuenta;
    }

    /*
    public function s_password($value) {
       $cad = utf8_decode(trim($value));
       $this->passw='';
       for($i=0; $i<strlen($cad); $i++) {
          $char = substr($cad, $i, 1);
          $keychar = substr('password', ($i % strlen('password'))-1, 1);
          $char = chr(ord($char)+ord($keychar));
          $this->passw.=$char;
       }
    }
    public function g_password(){return base64_encode($this->passw);}*/

    public function s_password($value)

    {
        $this->passw = hash('sha256', SAL.$value);

    }

    public function g_password()
    {
        return $this->passw;
    }


    public function s_documento($value)
    {
        $this->docu = utf8_decode(trim($value));
    }

    public function g_documento()
    {
        return $this->docu;
    }

    public function s_dir_image($value)
    {
        $this->dir_img = $value;
    }

    public function g_dir_image()
    {
        return $this->dir_img;
    }

    public function s_nombre($value)
    {
        $this->nomb = ucwords(strtolower(utf8_decode(trim($value))));
    }

    public function g_nombre()
    {
        return $this->nomb;
    }

    public function s_apellido($value)
    {
        $this->ape = ucwords(strtolower(utf8_decode(trim($value))));
    }

    public function g_apellido()
    {
        return $this->ape;
    }

    public function s_sexo($value)
    {
        $this->sex = trim($value);
    }

    public function g_sexo()
    {
        return $this->sex;
    }

    public function s_telefono($value)
    {
        $this->telf = trim($value);
    }

    public function g_telefono()
    {
        return $this->telf;
    }

    public function s_movil($value)
    {
        $this->movil = trim($value);
    }

    public function g_movil()
    {
        return $this->movil;
    }

    public function s_tratamiento($value)
    {
        $this->trat = trim($value);
    }

    public function g_tratamiento()
    {
        return $this->trat;
    }

    public function s_fecha_nace($value)
    {
        $this->fech_nace = trim($value);
    }

    public function g_fecha_nace()
    {
        return $this->fech_nace;
    }

    public function s_direccion($value)
    {
        $this->direc = utf8_decode(trim($value));
    }

    public function g_direccion()
    {
        return $this->direc;
    }

    public function s_estdo_civil($value)
    {
        $this->estd_civ = trim($value);
    }

    public function g_estdo_civil()
    {
        return $this->estd_civ;
    }

    public function s_email($value)
    {
        $this->email = utf8_decode(trim($value));
    }

    public function g_email()
    {
        return strtolower($this->email);
    }

    public function s_estado($value)
    {
        $this->estd = trim($value);
    }

    public function g_estado()
    {
        return $this->estd;
    }

    public function s_pais($value)
    {
        $this->pais = intval($value);
    }

    public function g_pais()
    {
        return $this->pais;
    }

    public function s_provincia($value)
    {
        $this->provincia = intval($value);
    }

    public function g_provincia()
    {
        return $this->provincia;
    }

    public function s_ciudad($value)
    {
        $this->ciudad = intval($value);
    }

    public function g_ciudad()
    {
        return $this->ciudad;
    }

    public function s_observacion($value)
    {
        $this->observ = utf8_decode(trim($value));
    }

    public function g_observacion()
    {
        return $this->observ;
    }

    public function s_limite($value)
    {
        $this->limit = (int)$value;
    }

    public function g_limite()
    {
        return $this->limit;
    }

    public function s_reg_empzar($value)
    {
        $this->reg_emp = (int)$value;
    }

    public function g_reg_empzar()
    {
        if ($this->reg_emp < 1) return 0;
        return ($this->reg_emp - 1) * $this->limit;
    }

    public function decodificar($value)
    {
        $string = base64_decode(utf8_decode(trim($value)));
        $this->passw = '';
        for ($i = 0; $i < strlen($string); $i++) {
            $char = substr($string, $i, 1);
            $keychar = substr('password', ($i % strlen('password')) - 1, 1);
            $char = chr(ord($char) - ord($keychar));
            $this->passw .= $char;
        }
        return $this->passw;
    }

    public function s_fecha_1($value)
    {
        $this->fecha_1 = trim($value);
    }

    public function g_fecha_1()
    {
        return $this->fecha_1;
    }

    public function s_fecha_2($value)
    {
        $this->fecha_2 = trim($value);
    }

    public function g_fecha_2()
    {
        return $this->fecha_2;
    }

    public function eliminar_acentos($str)
    {
        $a = array('�', '�', '�', '�', '�', '�', '�', '�', '�', '�', '�', '�', '�', '�', '�', '�', '�', '�', '�', '�', '�', '�', '�', '�', '�', '�', '�', '�', '�', '�', '�', '�', '�', '�', '�', '�', '�', '�', '�', '�', '�', '�', '�');
        $b = array('A', 'A', 'E', 'E', 'E', 'E', 'I', 'I', 'I', 'I', 'N', 'O', 'O', 'O', 'O', 'O', 'O', 'U', 'U', 'U', 'U', 'a', 'a', 'a', 'a', 'a', 'e', 'e', 'e', 'e', 'i', 'i', 'i', 'i', 'n', 'o', 'o', 'o', 'o', 'o', 'u', 'u', 'u');
        return str_replace($a, $b, $str);
    }
} ?>