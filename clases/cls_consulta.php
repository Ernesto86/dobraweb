<?php

require_once 'cls_acceso_datos.php';

class cls_consulta extends cls_acceso_datos {

    protected $descrip = '';
    protected $opc = '';
    protected $bodega = '';
    protected $producto = '';
    protected $usuario = '';
    protected $numfact = '';
    protected $idcliente = '';
    protected $idfactura = '';

    function s_id_factura($value) {
        $this->idfactura = trim($value);
    }

    function s_idcliente($value) {
        $this->idcliente = trim($value);
    }

    function s_descripcion($value) {
        $this->descrip = trim($value);
    }

    function s_opcion($value) {
        $this->opc = trim($value);
    }

    function s_bodega($value) {
        $this->bodega = trim($value);
    }

    function s_producto($value) {
        $this->producto = trim($value);
    }

    function s_usuario($value) {
        $this->usuario = trim($value);
    }

    function s_num_fact($value) {
        $this->numfact = trim($value);
    }

    public function fn_ban_bancos() {
        $this->cn->SetFetchMode(ADODB_FETCH_NUM);
        $this->sql = 'WEB_BAN_BANCOS_SELECT';
        $this->ejecutar();
        return $this->exist_reg();
    }

    public function fn_inv_bodega() {
        $this->cn->SetFetchMode(ADODB_FETCH_NUM);
        $this->sql = 'WEB_INV_BODEGA_SELECT';
        $this->ejecutar();
        return $this->exist_reg();
    }

    public function fn_inv_producto() {
        $this->cn->SetFetchMode(ADODB_FETCH_NUM);
        $this->sql = "WEB_INV_PRODUCTO_SELECT '" . $this->producto . "','" . $this->bodega . "'";
        $this->ejecutar();
        return $this->exist_reg();
    }

    public function fn_fecha_ingreso_dinero() {
        $this->cn->SetFetchMode(ADODB_FETCH_ASSOC);
        $this->sql = 'SELECT CONVERT(char(10),(DATEADD(DAY,-1,GETDATE())),103) AS FECHA';
        $this->ejecutar();
        if ($this->exist_reg()) {
            $cmp = $this->campos();
            return strval($cmp['FECHA']);
        }
        return false;
    }

    public function fn_inv_cod_producto() {
        $this->cn->SetFetchMode(ADODB_FETCH_ASSOC);
        $this->sql = "WEB_INV_PRODUCTO_COD_SELECT '" . $this->producto . "'";
        $this->ejecutar();
        if ($this->exist_reg()) {
            $cmp = $this->campos();
            return strval($cmp['ID']);
        }
        return false;
    }

    public function fn_usuario_web() {
        $this->cn->SetFetchMode(ADODB_FETCH_NUM);
        $this->sql = 'WEB_PROC_SEG_USUARIOS_WEB';
        $this->ejecutar();
        return $this->exist_reg();
    }

    public function fn_inv_buscar_producto() {
        $this->cn->SetFetchMode(ADODB_FETCH_NUM);
        $this->sql = "WEB_INV_PRODUCTO_STOCK_SELECT '" . $this->producto . "','" . $this->bodega . "'";
        $this->ejecutar();
        return $this->exist_reg();
    }

    public function fn_inv_usuario_bodega() {
        $this->cn->SetFetchMode(ADODB_FETCH_ASSOC);
        $this->sql = "SELECT TOP 1 UW.ID id,UW.CajaBancoID caja,UW.Inv_BodegaID bodega FROM dbo.SEG_USUARIO_WEB UW INNER JOIN dbo.SEG_USUARIOS U ON UW.ID=U.ID WHERE PremioCliente =1 AND Estado ='A' AND UW.ID='" . $this->usuario . "'";
        $this->ejecutar();
        return $this->exist_reg();
    }

    public function fn_ven_num_fact_select() {
        $this->cn->SetFetchMode(ADODB_FETCH_ASSOC);
        $this->sql = "WEB_VEN_FACTURA_SELECT_NUMERO '" . $this->numfact . "'";
        $this->ejecutar();
        return $this->exist_reg();
    }

    public function fn_cb_cli_facturas_det() {
        $this->sql = "WEB_VEN_FACT_SELECT_COD_CLI '" . $this->idcliente . "'";
        $this->ejecutar();
        return $this->exist_reg();
    }

    public function fn_cli_ven_detalle_factura() {
        $this->cn->SetFetchMode(ADODB_FETCH_NUM);
        $this->sql = "WEB_VEN_FACTURAS_SELECT_DETALLE '" . $this->idfactura . "'";
        $this->ejecutar();
        if ($this->exist_reg()) {
            $x = 1;
            while (!$this->rs->EOF) {
                echo'<tr ';
                if ($x % 2 == 0)
                    echo'class="bg_altercolor"';

                echo'><td align="center"><input type="hidden" name="txt_fact_det_id[]" class="datos" value="' . trim($this->rs->fields[0]) . '"/>' . $x . '</td>';
                echo'<td align="center"><input type="hidden" name="txt_producto_id[]" class="datos" value="' . trim($this->rs->fields[1]) . '"/>';
                echo'<strong>' . $this->rs->fields[10] . '</strong></td>';
                echo'<td align="center"><strong>' . $this->rs->fields[11] . '</strong></td>';
                echo'<td align="center">';

                $devuelt = intval($this->rs->fields[7]);
                $cant = intval($this->rs->fields[3]);

                if ($devuelt and $devuelt <= $cant) {
                    $cant = intval($cant - $devuelt);
                }

                echo'<input name="txt_cantidad[]" type="text" size="8" maxlength="8" class="cant datos" value="' . intval($cant) . '" readonly="readonly"/>';
                ;
                echo'</td>';

                echo'<td align="center">';
                echo'<input name="txt_devuelto[]" type="text" size="8" maxlength="3" class="devol datos" value=""/>';
                echo'</td>';

                echo'<td align="center"><input type="hidden" name="txt_bodega_id[]" class="datos" value="' . trim($this->rs->fields[2]) . '"/>';
                echo'<strong>' . $this->rs->fields[17] . '</strong></td>';

                echo'<td align="center">';
                echo'<input type="hidden" name="txt_costo_prod[]" class="datos" value="' . number_format($this->rs->fields[5], 4) . '"/>';
                echo'<input name="txt_pvp[]" type="text" size="8" maxlength="8" class="pvp datos" value="' . number_format($this->rs->fields[4], 2) . '" readonly="readonly"/>';
                echo'</td>';

                echo'<td align="center">';
                echo'<input name="txt_extend[]" type="text" size="8" maxlength="8" class="total datos" value="" readonly="readonly"/>';
                echo'</td>';
                echo'</tr>';

                $this->rs->MoveNext();
                $x++;
            }
            $this->rs->Close();
        } else
            echo'<tr><td colspan="10" align="center">No se Encontro Registros..</td></tr>';
    }
}
?>