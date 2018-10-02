<?php
require_once 'cls_acceso_datos.php';

class cls_inv_producto extends cls_acceso_datos
{
    protected $codgrupo = '';
    protected $user_id = '';
    protected $idbodega = '';
    protected $idproducto = '';
    protected $fecha_ini = '';
    protected $fecha_fin = '';

    function s_cod_grupo($value)
    {
        $this->codgrupo = trim($value);
    }

    function s_id_bodega($value)
    {
        $this->idbodega = trim($value);
    }

    function s_usuario_id($value)
    {
        $this->user_id = trim($value);
    }

    function s_producto_id($value)
    {
        $this->idproducto = trim($value);
    }

    function s_fecha_inicial($value)
    {
        $this->fecha_ini = trim($value);
    }

    function s_fecha_final($value)
    {
        $this->fecha_fin = trim($value);
    }

    public function fn_inv_nota_venta_premio()
    {
        $this->cn->SetFetchMode(ADODB_FETCH_NUM);
        $this->sql = "WEB_PROC_VEN_INFORME_NOTA_VENTA_PREMIOS '" . $this->codgrupo . "','" . $this->user_id . "','" . $this->idbodega . "','" . $this->idproducto . "','" . $this->fecha_ini . "','" . $this->fecha_fin . "'";
        $this->ejecutar();
        if ($this->exist_reg()) {
            $x = 1;$total =0;
            while (!$this->rs->EOF) {
                echo '<tr';
                if ($x % 2 == 0) echo ' class="tr_bgalter"';

                echo '><td width="20px" align="center">' . $x . '</td>';
                echo '<td width="50px" align="center">' . $this->rs->fields[0] . '</td>';
                echo '<td width="50px" align="center">' . $this->rs->fields[1] . '</td>';
                echo '<td width="60px" align="center">' . $this->rs->fields[2] . '</td>';
                echo '<td width="60px"><strong>' . $this->rs->fields[3] . '</strong></td>';
                echo '<td width="220px">' . ucwords($this->rs->fields[4]) . '</td>';
                echo '<td width="50px" align="right">' . $this->rs->fields[5] . '</td>';
                echo '<td width="160px"><strong>' . $this->rs->fields[6] . '</strong></td>';
                $total += intval($this->rs->fields[7]);
                echo '<td width="60px" align="center"><strong>' . intval($this->rs->fields[7]) . '</strong></td>';
                echo '<td width="60px" align="right"><strong>' . number_format($this->rs->fields[8], 2) . '</strong></td>';
                echo '</tr>';
                $x++;
                $this->rs->MoveNext();
            }
            echo '<tr><td colspan="8" align="right"><strong style="font-size:16px">Total Premios:</strong></td><td align="center"><strong style="font-size:16px">' . $total . '</strong></td><td></td></tr>';
            $this->rs->Close();

            $this->sql = "WEB_PROC_VEN_INF_NOTA_VENTA_PREMIO_CONSOLIDADO '" . $this->codgrupo . "','" . $this->user_id . "','" . $this->idbodega . "','" . $this->idproducto . "','" . $this->fecha_ini . "','" . $this->fecha_fin . "'";
            $this->ejecutar();
            if ($this->exist_reg()) {
                echo '<tr><td colspan="10">&nbsp;</td></tr>';
                $x = 1;
                $total = 0;
                echo '<tr class="cab_title"><td colspan="8" align="center">Detalle de Premios Consolidado Productos.</td></tr>';
                echo '<tr>';
                echo '<th colspan="2">Codigo</th>';
                echo '<th colspan="5">Producto</th>';
                echo '<th>Cantidad</th>';
                echo '</tr>';
                while (!$this->rs->EOF) {
                    echo '<tr';
                    if ($x % 2 == 0) echo ' class="tr_bgalter"';
                    echo '><td colspan="2"><strong>' . $this->rs->fields[1] . '</strong></td>';
                    echo '<td colspan="5">' . $this->rs->fields[2] . '</td>';
                    $total += intval($this->rs->fields[3]);
                    echo '<td align="center"><strong>' . intval($this->rs->fields[3]) . '</strong></td>';
                    echo '</tr>';
                    $this->rs->MoveNext();
                    $x++;
                }
                echo '<tr><td colspan="7" align="right"><strong style="font-size:16px">Total Premios:</strong></td><td align="center"><strong style="font-size:16px">' . $total . '</strong></td></tr>';
            }
            $this->rs->Close();
        } else echo 'No se Encontro Registros..';
    }
}

?>