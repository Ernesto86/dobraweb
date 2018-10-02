<?php
require_once 'cls_acceso_datos.php';
require_once 'cls_persona.php';

class cls_empleado extends cls_acceso_datos
{
    protected static $obj = null;
    protected $descrip = '';

    function s_descripcion($val)
    {
        $this->descrip = trim($val);
    }

    function __construct()
    {
        parent::__construct();
        $this->obj = new cls_persona;
    }

    function __call($method, $args)
    {
        return call_user_func_array(array($this->obj, $method), $args);
    }

    public function fn_emp_buscar()
    {
        $this->cn->SetFetchMode(ADODB_FETCH_NUM);
        $this->sql = 'SELECT TOP 100 Código,LOWER(Nombre)FROM EMP_EMPLEADOS WHERE Anulado=0 AND Nombre LIKE ' . "'" . $this->descrip . "%'" . ' ORDER BY Nombre;';
        $this->ejecutar();
        if ($this->exist_reg()) {
            $x = 1;
            while (!$this->rs->EOF) {
                echo '<tr>';
                echo '<td align="center">' . $x . '</td>';
                echo '<td align="center"><a href="javascript:fn_select_cod(' . "'" . $this->rs->fields[0] . "'" . ')">';
                echo $this->rs->fields[0] . '</a></td>';
                echo '<td>' . ucwords($this->rs->fields[1]) . '&nbsp</td>';
                $this->rs->MoveNext();
                $x++;
                echo '</tr>';
            }
            $this->rs->Close();
        } else echo 'No se Encontro Registros..';
    }
}

?>