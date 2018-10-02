<?php

require_once'cls_acceso_datos.php';
require_once'cls_persona.php';

class cls_cli_informe_deuda extends cls_acceso_datos {

    protected static $obj = null;
    protected $ban_id = '';
    protected $user_id = '';
    protected $rang_ini = 0;
    protected $rang_fin = 0;
    protected $codgrupo = '';

    function s_cod_grupo($value) {
        $this->codgrupo = trim($value);
    }

    function s_rango_inic($value) {
        $this->rang_ini = floatval($value);
    }

    function s_rango_final($value) {
        $this->rang_fin = floatval($value);
    }

    function s_banco_id($value) {
        $this->ban_id = trim($value);
    }

    function s_usuario_id($value) {
        $this->user_id = trim($value);
    }

    function __construct() {
        parent::__construct();
        $this->obj = new cls_persona;
    }

    function __call($method, $args) {
        return call_user_func_array(array($this->obj, $method), $args);
    }

    public function fn_cli_arqueo_banco_caja() {
        $this->cn->SetFetchMode(ADODB_FETCH_NUM);
        $this->sql = "WEB_BAN_INFORME_ARQUEO_CAJAS '" . $this->ban_id . "','" . $this->g_tipo() . "','" . $this->user_id . "','" . $this->g_fecha_1() . "','" . $this->g_fecha_2() . "'";
        $this->ejecutar();
        if ($this->exist_reg()) {
            $x = 1;
            $total =0;
            while (!$this->rs->EOF) {
                echo'<tr';
                if ($x % 2 == 0)
                    echo' class="tr_bgalter"';
                echo'><td width="20px" align="center">' . $x . '</td>';
                echo'<td width="50px">' . $this->rs->fields[0] . '</td>';
                echo'<td width="50px">' . $this->rs->fields[1] . '</td>';
                echo'<td width="60px">' . $this->rs->fields[2] . '</td>';
                echo'<td width="60px"><strong>' . $this->rs->fields[3] . '</strong></td>';
                echo'<td width="250px">' . ucwords($this->rs->fields[4]) . '</td>';
                echo'<td width="50px">' . $this->rs->fields[5] . '</td>';
                $value = floatval($this->rs->fields[6]);
                $total +=$value;
                echo'<td width="60px" align="right"><strong>' . number_format($value, 2) . '</strong></td>';
                echo'<td width="60px" align="right"><strong>' . number_format($this->rs->fields[7], 2) . '</strong></td>';
                $this->rs->MoveNext();
                $x++;
                echo'</tr>';
            }
            echo'<tr><td colspan="7" align="right"><strong style="font-size:14px">TOTAL EFECTIVO : </strong></td>';
            echo'<td align="right"><strong style="font-size:16px">' . number_format($total, 2) . '</strong></td>';
            echo'<td align="right"> </td>';
            echo'</tr>';
            $this->rs->Close();
        } else
            echo'No se Encontro Registros..';
    }

    public function fn_detalle_cartera_vencida() {
        $this->cn->SetFetchMode(ADODB_FETCH_NUM);
        $this->sql = "WEB_PROC_CLI_CARTERA_VENCIDA '" . $this->codgrupo . "','" . $this->g_fecha_1() . "','" . $this->g_fecha_2() . "'," . $this->rang_ini . ',' . $this->rang_fin;
        $this->ejecutar();
        if ($this->exist_reg()) {
            $x = 1;
            while (!$this->rs->EOF) {
                echo'<tr ';
                if ($x % 2 == 0)
                    echo'class="tr_bgalter"';
                echo'><td width="20px" align="center"><strong>' . $x . '</strong></td>';
                echo'<td width="60px">' . $this->rs->fields[0] . '</td>';
                echo'<td width="60px"><strong>' . $this->rs->fields[1] . '</strong></td>';
                echo'<td width="60px">' . $this->rs->fields[2] . '</td>';
                echo'<td width="60px">' . $this->rs->fields[3] . '</td>';
                echo'<td width="250px">' . ucwords($this->rs->fields[4]) . '</td>';
                echo'<td width="50px">' . $this->rs->fields[10] . '</td>';
                echo'<td width="50px" align="right"><strong>' . number_format($this->rs->fields[5], 2) . '</strong></td>';
                echo'<td width="50px" align="center"><strong>';
                $cant = intval($this->rs->fields[6]);
                echo $cant ? $cant : '-';
                echo'</strong></td>';
                $abono = floatval($this->rs->fields[7]);
                $devol = floatval($this->rs->fields[8]);
                echo'<td width="50px" align="right"><strong>';
                echo $abono ? number_format($abono, 2) : '-';
                echo'</strong></td>';
                echo'<td width="50px" align="right"><strong>';
                echo $devol ? number_format($devol, 2) : '-';
                echo'</strong></td>';
                echo'<td width="50px" align="right"><strong>' . number_format($this->rs->fields[9], 2) . '</strong></td>';
                $this->rs->MoveNext();
                $x++;
                echo'</tr>';
            }
            $this->rs->Close();
        } else
            echo'No se Encontro Registros..';
    }

}

?>