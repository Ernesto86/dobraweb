<?php

require_once 'cls_acceso_datos.php';

class cls_ven_devolucion extends cls_acceso_datos {

    protected $codgrupo = '';
    protected $user_id = '';
    protected $idbodega = '';
    protected $idproducto = '';
    protected $fecha_ini = '';
    protected $fecha_fin = '';

    function s_cod_grupo($value) {
        $this->codgrupo = trim($value);
    }

    function s_id_bodega($value) {
        $this->idbodega = trim($value);
    }

    function s_usuario_id($value) {
        $this->user_id = trim($value);
    }

    function s_producto_id($value) {
        $this->idproducto = trim($value);
    }

    function s_fecha_inicial($value) {
        $this->fecha_ini = trim($value);
    }

    function s_fecha_final($value) {
        $this->fecha_fin = trim($value);
    }

    public function fn_informe_ventas_devolucion() {
        $this->cn->SetFetchMode(ADODB_FETCH_NUM);
        $this->sql = "WEB_PROC_VEN_INF_CLIENTE_DEVOLUCION '" . $this->codgrupo . "','" . $this->user_id . "','" . $this->idbodega . "','" . $this->idproducto . "','" . $this->fecha_ini . "','" . $this->fecha_fin . "'";
        $this->ejecutar();
        if ($this->exist_reg()) {
            $x = 1;
            while (!$this->rs->EOF) {
                echo'<tr';
                if ($x % 2 == 0)
                    echo' class="tr_bgalter"';
                echo'><td width="20px" align="center">' . $x . '</td>';
                echo'<td width="50px" align="center">' . $this->rs->fields[0] . '</td>';
                echo'<td width="50px" align="center">' . $this->rs->fields[1] . '</td>';
                echo'<td width="60px" align="center">' . $this->rs->fields[2] . '</td>';
                echo'<td width="60px"><strong>' . $this->rs->fields[3] . '</strong></td>';
                echo'<td width="220px">' . ucwords($this->rs->fields[4]) . '</td>';
                echo'<td width="50px" align="right">' . $this->rs->fields[5] . '</td>';
                echo'<td width="160px"><strong>' . $this->rs->fields[6] . '</strong></td>';
                echo'<td align="right"><strong>' . number_format($this->rs->fields[7], 2) . '</strong></td>';
                $total_dev += floatval($this->rs->fields[8]);
                echo'<td align="right"><strong>' . number_format($this->rs->fields[8], 2) . '</strong></td>';
                $total_unds += intval($this->rs->fields[9]);
                echo'<td align="center"><strong>' . intval($this->rs->fields[9]) . '</strong></td>';
                echo'<td align="right"><strong>' . number_format($this->rs->fields[10], 2) . '</strong></td>';
                $this->rs->MoveNext();
                $x++;
                echo'</tr>';
            }
            $this->rs->Close();
            echo'<tr class="tr_total_gral">';
            echo'<td colspan="9" align="right">TOTAL GENERAL : </td>';
            echo'<td align="right">' . number_format($total_dev, 2) . '</td>';
            echo'<td align="center">' . intval($total_unds) . '</td><td></td></tr>';

            $this->sql = "WEB_PROC_VEN_INF_CLIENTE_DEVOLUCION_CONSOLIDADO '" . $this->codgrupo . "','" . $this->user_id . "','" . $this->idbodega . "','" . $this->idproducto . "','" . $this->fecha_ini . "','" . $this->fecha_fin . "'";
            $this->ejecutar();
            if ($this->exist_reg()) {
                echo'<tr><td colspan="12">&nbsp;</td></tr>';
                $x = 1;
                echo'<tr class="cab_title"><td colspan="8" align="center">Detalle Devolucion Consolidado Productos.</td></tr>';
                echo'<tr>';
                echo'<th colspan="2">Codigo</th>';
                echo'<th colspan="5">Producto</th>';
                echo'<th>Cantidad</th>';
                echo'</tr>';
                while (!$this->rs->EOF) {
                    echo'<tr';
                    if ($x % 2 == 0)
                        echo' class="tr_bgalter"';
                    echo'><td colspan="2"><strong>' . $this->rs->fields[1] . '</strong></td>';
                    echo'<td colspan="5">' . $this->rs->fields[2] . '</td>';
                    $total += intval($this->rs->fields[3]);
                    echo'<td align="center"><strong>' . intval($this->rs->fields[3]) . '</strong></td>';
                    echo'</tr>';
                    $this->rs->MoveNext();
                    $x++;
                }
                echo'<tr><td colspan="7" align="right"><strong style="font-size:16px">TOTAL DEVOLUCIONES :</strong></td><td align="center"><strong style="font-size:16px">' . $total . '</strong></td></tr>';
            }
            $this->rs->Close();
        }
    }

}

?>