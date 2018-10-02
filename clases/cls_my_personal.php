<?php

session_start();
require_once 'cls_my_persona.php';
require_once 'cls_my_acceso_datos.php';

class cls_my_personal extends cls_my_acceso_datos {

    protected static $obj = null;

    function __construct() {
        parent::__construct();
        $this->obj = new cls_my_persona;
    }

    function __call($method, $args) {
        return call_user_func_array(array($this->obj, $method), $args);
    }

    public function consulta() {
        if ($this->g_id() > 0) {
            $this->sql = 'SELECT per_id id,tip_doc_id tip_doc,per_num_doc num_doc,per_tratamiento trat,per_apellido ape,per_nombre nom,per_dir_img img,per_sexo sex,per_est_civil civil,per_direccion direc,ciu_id ciu,prv_id prv,pai_id pais,ins_id inst,jor_id jorn,per_telefono telef,per_mobil mobil,per_email email,per_fech_nacimient fnace,per_observ observ,per_docente docent,per_estado estd FROM tbl_personal WHERE per_id=' . $this->g_id();
            $this->sql.=' LIMIT 1;';
        } else {
            $this->sql = 'SELECT per_id Id,per_codigo Codigo,per_dir_img Image,per_num_doc Document,CONCAT(per_apellido,' . "' '" . ',per_nombre)ApellidosNombres,per_telefono Telefono,per_email Email,per_estado Estado,per_user_mod UserMod,per_fech_mod FechaMod FROM tbl_personal P WHERE per_estado ';
            if ($_SESSION['us_tipuser'] == 'administrador') {
                $this->sql.="IN('A','I')";
            } else
                $this->sql.="='A'";

            $this->sql.=' GROUP BY per_id ORDER BY per_apellido;';
        }
        $this->ejecutar();
        $this->num_registro();
    }

    public function image() {
        $this->sql = "UPDATE tbl_personal SET per_dir_img ='" . $this->g_dir_image() . "',per_user_mod='" . $_SESSION['cuenta'] . "',per_fech_mod=NOW()WHERE per_id=" . $this->g_id();
        return $this->ejecutar();
    }

    public function cb_personal() {
        $this->sql = 'SELECT per_id id,CONCAT(per_apellido,' . "' '" . ',per_nombre)datos FROM tbl_personal WHERE per_estado=' . "'A'";
        $this->sql.=' GROUP BY per_id ORDER BY per_apellido;';
        $this->ejecutar();
        return $this->num_reg();
    }

    public function cb_docente() {
        $this->sql = 'SELECT per_id id,CONCAT(per_apellido,' . "' '" . ',per_nombre)datos FROM tbl_personal JOIN tbl_institucion USING(ins_id)JOIN tbl_jornada USING(jor_id)
   WHERE per_docente=1 AND ins_id=' . $_SESSION['idinst'] . '  AND jor_id=' . $_SESSION['idjorn'] . ' AND per_estado=' . "'A'" . ' ORDER BY per_apellido;';
        $this->ejecutar();
        return $this->num_reg();
    }

    public function transaccion($periodo = 0) {
        if ($_SESSION['tipo'] == 'super administrador' and $periodo) {
            $this->sql = 'SELECT per_lec_id id,ins_id inst,jor_id jor FROM tbl_periodo_lectivo WHERE per_lec_id=' . $periodo . ' LIMIT 1;';
            $this->ejecutar();
            if (!$this->num_reg())
                die('No se Encontr� Instituci�n');

            $cmp = $this->campos();
            $_SESSION['idinst'] = intval($cmp['inst']);
            $_SESSION['idjorn'] = intval($cmp['jor']);
        }
        $opc = $this->g_opcion();
        if ($opc != 'EL') {
            $this->sql = "SELECT per_id FROM tbl_personal WHERE per_num_doc!='' AND per_num_doc='" . $this->g_documento() . "' AND ins_id=" . $_SESSION['idinst']
                    . ' AND jor_id=' . $_SESSION['idjorn'];
            if ($opc == 'M')
                $this->sql.=' AND per_id!=' . $this->g_id();

            $this->ejecutar();
            if ($this->num_reg() > 0)
                die('El N�mero de Documento ya Existe');
        }
        switch ($this->g_opcion()) {
            case 'I' : $this->sql = 'SELECT IFNULL(MAX(per_id)+1,1)id FROM tbl_personal';
                $this->s_id($this->max_id());
                $this->sql = 'INSERT INTO tbl_personal
								( per_id,tip_doc_id,per_num_doc,per_tratamiento,per_apellido,per_nombre,per_sexo,
								  per_est_civil,per_direccion,ciu_id,prv_id,pai_id,ins_id,jor_id,per_telefono,
								  per_mobil,per_email,per_fech_nacimient,per_observ,per_docente,per_estado,
								  per_user_crea,per_fech_crea,per_user_mod,per_fech_mod
									) VALUE (' . $this->g_id() . ',' . $this->g_tipo_doc() . ",'" . $this->g_documento() . "','"
                        . $this->g_tratamiento() . "','" . $this->g_apellido() . "','" . $this->g_nombre() . "','"
                        . $this->g_sexo() . "','" . $this->g_estdo_civil() . "','" . $this->g_direccion() . "',"
                        . $this->g_ciudad() . ',' . $this->g_provincia() . ',' . $this->g_pais() . ','
                        . $_SESSION['idinst'] . ',' . $_SESSION['idjorn'] . ",'"
                        . $this->g_telefono() . "','" . $this->g_mobil() . "','" . $this->g_email() . "','"
                        . $this->g_fecha_nace() . "','" . $this->g_observacion() . "',"
                        . $this->g_docente() . ",'" . $this->g_estado() . "','" . $_SESSION['cuenta'] . "',NOW(),'"
                        . $_SESSION['cuenta'] . "',NOW());";

                break;

            case 'M' : $this->sql = 'UPDATE tbl_personal SET 
									  tip_doc_id=' . $this->g_tipo_doc() . ",	
									  per_num_doc='" . $this->g_documento() . "',
									  per_tratamiento='" . $this->g_tratamiento() . "',
									  per_apellido='" . $this->g_apellido() . "',
									  per_nombre ='" . $this->g_nombre() . "',
									  per_sexo='" . $this->g_sexo() . "',
									  per_est_civil='" . $this->g_estdo_civil() . "',
									  per_direccion='" . $this->g_direccion() . "',
									  ciu_id=" . $this->g_ciudad() . ',
									  prv_id=' . $this->g_provincia() . ',
									  pai_id=' . $this->g_pais() . ',
									  ins_id=' . $_SESSION['idinst'] . ',
									  jor_id=' . $_SESSION['idjorn'] . ",
									  per_telefono='" . $this->g_telefono() . "',
									  per_mobil='" . $this->g_mobil() . "',
									  per_email ='" . $this->g_email() . "',
									  per_fech_nacimient ='" . $this->g_fecha_nace() . "',
									  per_observ='" . $this->g_observacion() . "',
									  per_docente=" . $this->g_docente() . ",
									  per_estado ='" . $this->g_estado() . "',
									  per_user_mod ='" . $_SESSION['cuenta'] . "',
									  per_fech_mod = Now()	 
									  WHERE per_id =" . $this->g_id();
                break;

            case 'EL' : $this->sql = 'SELECT per_id id FROM tbl_personal JOIN tbl_usuario USING(per_id)JOIN tbl_tipo_usuario
					 USING(tip_user_id)WHERE tip_user_desc' . " IN('Super Administrador')AND per_id=" . $this->g_id() . " GROUP BY per_id;";
                $this->ejecutar();
                if ($this->num_reg() > 0)
                    die('No Puede Eliminar un Super Administrador');

                $this->sql = 'SELECT per_id id FROM tbl_personal JOIN tbl_usuario USING(per_id)WHERE per_id=' . $this->g_id()
                        . ' AND usu_id=' . $_SESSION['iduser'] . ' GROUP BY per_id ;';
                $this->ejecutar();
                if ($this->num_reg() > 0)
                    die('No Puede Eliminar tiene Sesi�n Activa');


                $this->sql = "UPDATE tbl_personal SET per_estado='I',per_user_mod='" . $_SESSION['cuenta'] . "',per_fech_mod=Now()
					WHERE per_id=" . $this->g_id();
        }
        $this->ejecutar();
        return $this->g_id();
    }

}

?>