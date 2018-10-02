<?php
require_once 'cls_acceso_datos.php';
require_once 'cls_persona.php';

class cls_cli_cliente_deuda extends cls_acceso_datos
{
    protected static $obj = null;
    protected $tip_ingr = 'EFECTIVO';
    protected $id_banco = '';

    public $totabono = 0;
    public $ingr_abono_cab;
    public $ingr_abono_det;
    public $ingr_saldo_det;

    public $ingr_femis;
    public $ingr_fvenc;

    public $ingr_num;
    public $ingr_tip;
    public $ingr_det_fact;

    public $ingr_deud_id;
    public $ingr_cxc;
    public $ingr_rubro;
    public $ingr_cred;

    protected $id_ingrban = '';
    protected $num_ingrban = '';
    protected $id_asient = '';
    protected $num_asient = '';

    public function s_tipo_ingreso($value)
    {
        $this->tip_ingr = trim($value);
    }

    public function s_id_banco($value)
    {
        $this->id_banco = trim($value);
    }

    public function s_total_abono($value)
    {
        $this->totabono = floatval($value);
    }

    function __construct()
    {

        parent::__construct();
        $this->obj = new cls_persona;

        $this->ingr_abono_cab = array();
        $this->ingr_abono_det = array();
        $this->ingr_saldo_det = array();

        $this->ingr_femis = array();
        $this->ingr_fvenc = array();

        $this->ingr_num = array();
        $this->ingr_tip = array();
        $this->ingr_det_fact = array();

        $this->ingr_deud_id = array();
        $this->ingr_cxc = array();
        $this->ingr_rubro = array();
        $this->ingr_cred = array();

    }

    function __call($method, $args)
    {
        return call_user_func_array(array($this->obj, $method), $args);
    }

    public function fn_cli_cliente_deuda_id()
    {
        $this->cn->SetFetchMode(ADODB_FETCH_NUM);
        $this->sql = "WEB_CLI_CLIENTES_SELECT_DEUDAS '" . $this->g_id() . "'";
        $this->ejecutar();
        if ($this->exist_reg()) {
            $x = 1;
            while (!$this->rs->EOF) {
                echo '<tr ';
                if ($x % 2 == 0) echo 'class="bg_altercolor"';
                echo '><td align="center">' . $x . '&nbsp</td>';
                echo '<td align="center"><input type="hidden" name="txt_femis_fact[]" class="datos" value="' . trim($this->rs->fields[1]) . '"/>' . $this->rs->fields[1] . '</td>';
                echo '<td align="center"><input type="hidden" name="txt_fvenc_fact[]" class="datos" value="' . trim($this->rs->fields[4]) . '"/>' . $this->rs->fields[4] . '</td>';
                echo '<td align="center"><input type="hidden" name="txt_tip_fact[]" class="datos" value="' . trim($this->rs->fields[5]) . '"/>' . $this->rs->fields[5] . '</td>';
                echo '<td align="center"><input type="hidden" name="txt_num_fact[]" class="datos" value="' . $this->rs->fields[0] . '"/>' . $this->rs->fields[0] . '</td>';
                echo '<td align="center"><input type="hidden" name="txt_det_fact[]" class="datos" value="' . ucwords(trim($this->rs->fields[2])) . '"/>' . ucwords($this->rs->fields[2]) . '</td>';
                $sald = floatval($this->rs->fields[3]);
                $saldtotal = ($saldtotal + $sald);
                echo '<td align="center"><input name="txt_saldo[]" type="text" size="8" maxlength="8" class="valor datos" readonly="" value="' . number_format($sald, 2) . '"/></td>';
                echo '<td align="center"><input name="txt_abono[]" type="text" size="8" maxlength="8" class="valor datos" readonly=""/></td>';
                echo '<td align="center"><input name="txt_nsald[]" type="text" size="8" maxlength="8" class="valor datos" readonly="" value="' . number_format($sald, 2) . '"/></td>';
                echo '<td style="display:none">';
                echo '<input type="hidden" name="txt_deuda_id[]" class="datos" value="' . trim($this->rs->fields[8]) . '"/>';
                echo '<input type="hidden" name="txt_cxc[]" class="datos" value="' . trim($this->rs->fields[7]) . '"/>';
                echo '<input type="hidden" name="txt_rubro[]" class="datos" value="' . trim($this->rs->fields[9]) . '"/>';
                echo '<input type="hidden" name="txt_cred[]" class="datos" value="' . $this->rs->fields[15] . '"/>';
                echo '</td>';
                echo '</tr>';
                $this->rs->MoveNext();
                $x++;
            }
            echo '<tr style="display:none"><th colspan="10"><input type="hidden" name="saldtotal" id="saldtotal" value="' . number_format($saldtotal, 2) . '"/></th></tr>';
            $this->rs->Close();
        } else echo '<tr><td colspan="10" align="center">No se Encontro Registros..</td></tr>';
    }

    public function fn_cli_saldo_total()
    {
        $this->cn->SetFetchMode(ADODB_FETCH_ASSOC);
        $this->sql = "WEB_CLI_SALDO_TOTAL '" . $this->g_id() . "'";
        $this->ejecutar();
        if ($this->exist_reg()) {
            $cmp = $this->campos();
            return floatval($cmp['saldo']);
        }
        return 0;
    }

    public function fn_cli_ingreso_contadores()
    {
        $this->cn->SetFetchMode(ADODB_FETCH_ASSOC);
        $this->sql = 'WEB_PROC_CLI_INGR_CONTADORES';
        $this->ejecutar();
        if ($this->exist_reg()) {
            $cmp = $this->campos();
            $this->id_ingrban = trim($cmp['BanID']);
            $this->num_ingrban = trim($cmp['BanNum']);
            $this->id_asient = trim($cmp['AsientID']);
            $this->num_asient = trim($cmp['AsientNum']);
            if ($this->id_ingrban and $this->num_ingrban and $this->id_asient and $this->num_asient) return true;
        }
        return false;
    }

    public function fn_contador($contador, $sp = 'SIS_GetNextID')
    {
        $this->cn->SetFetchMode(ADODB_FETCH_NUM);
        $this->sql = "$sp '$contador'";
        $this->ejecutar();
        if ($this->exist_reg()) {
            $cmp = $this->campos();
            $num = strval($cmp[0]);
            return str_pad($num, 10, '0', STR_PAD_LEFT);
        }
        return false;
    }

    public function fn_caja_bancos_id()
    {
        $this->cn->SetFetchMode(ADODB_FETCH_ASSOC);
        $this->sql = "SELECT CtaMayorID,Nombre,Clase,DivisaID FROM dbo.BAN_BANCOS WHERE ID='" . $this->id_banco . "'";
        $this->ejecutar();
        if ($this->exist_reg()) {
            return $this->campos();
        }
        return false;
    }

    public function fn_cli_guardar_ingreso_dinero()
    {
        try {

            $pc_cliente = $_SERVER["REMOTE_ADDR"] . ' : PC ';
            $pc_cliente .= gethostname();
            $this->cn->StartTrans();

            if (!$this->fn_cli_ingreso_contadores()) {
                die('{"rp":"6"}');
            }

            $user = $this->g_cuenta();
            $nomb = $this->g_nombre();
            $detalle = $this->g_observacion();

            //$this->id_ingrban  = trim($cmp['BanID']);
            //  $this->num_ingrban = trim($cmp['BanNum']);
            //  $this->id_asient   = trim($cmp['AsientID']);
            //  $this->num_asient  = trim($cmp['AsientNum']);

            $this->sql = "BAN_Ingresos_InsertC '" . $this->id_ingrban . "','" . $this->num_ingrban . "','" . $this->id_asient . "','" . $this->id_banco;
            $this->sql .= "','" . $this->g_id() . "','" . $this->g_fecha_1() . "','" . $this->g_tipo() . "','','$detalle'," . number_format($this->totabono, 2);
            $this->sql .= ",0,0,0,0,0,0," . number_format($this->totabono, 2) . ",'','0000000001','0000000002',1,'','$user','00','$pc_cliente'";

            if (!$this->ejecutar()) $this->cn->FailTrans();

            foreach ($this->ingr_abono_cab as $key => $val) {

                if (floatval($val) > 0) {

                    $id_ingrban_dt = $this->fn_contador('BAN_INGRESOS_DT-ID-00');

                    if (!$id_ingrban_dt) $this->cn->FailTrans();

                    $this->sql = "BAN_IngresosDTFactura_Insert '" . $id_ingrban_dt . "','" . $this->id_ingrban . "','" . $this->tip_ingr . "','','','','','$nomb','";
                    $this->sql .= $this->g_fecha_1() . "','0000000002',1," . number_format($val, 2) . ',' . number_format($val, 2) . ",'$user','00','$pc_cliente'";
                    if (!$this->ejecutar()) $this->cn->FailTrans();

                }

            }

            foreach ($this->ingr_abono_det as $key => $val) {

                if (floatval($val) > 0) {

                    $this->sql = "BAN_IngresosDeudas_Insert '" . $this->id_ingrban . "','" . $this->ingr_deud_id[$key] . "','0000000002',1," . number_format($this->ingr_saldo_det[$key], 2) . ",";
                    $this->sql .= number_format($val, 2) . ",0,'" . $this->g_fecha_1() . "','" . $this->ingr_fvenc[$key] . "','" . $this->ingr_tip[$key] . "','";
                    $this->sql .= $this->ingr_num[$key] . "','" . $this->ingr_det_fact[$key] . "','" . $this->ingr_cred[$key] . "','" . $this->ingr_rubro[$key] . "','" . $this->ingr_cxc[$key] . "',";
                    $this->sql .= "1,'0000000001','$user','00','$pc_cliente'";
                    if (!$this->ejecutar()) $this->cn->FailTrans();

                }

            }

            $this->sql = "ACC_Asientos_Insert '" . $this->id_asient . "','" . $this->num_asient . "','" . $this->id_ingrban . "','" . $this->g_fecha_1() . "','" . $this->g_tipo() . "','";
            $this->sql .= "$detalle','','0000000001','$user','00','$pc_cliente'";
            if (!$this->ejecutar()) $this->cn->FailTrans();

            $ctabanco = $this->fn_caja_bancos_id();

            if (!count($ctabanco)) $this->cn->FailTrans();

            $this->sql = "ACC_AsientosDT_Insert '" . $this->id_asient . "','" . $ctabanco['CtaMayorID'] . "','$detalle',1,'0000000002',1,";
            $this->sql .= number_format($this->totabono, 2) . ',' . number_format($this->totabono, 2) . ",'$user','00','$pc_cliente'";

            if (!$this->ejecutar()) $this->cn->FailTrans();

            $this->sql = "ACC_AsientosDT_Insert '" . $this->id_asient . "','" . $this->ingr_cxc[0] . "','$detalle',0,'0000000002',1,";
            $this->sql .= number_format($this->totabono, 2) . ',' . number_format($this->totabono, 2) . ",'$user','00','$pc_cliente'";

            if (!$this->ejecutar()) $this->cn->FailTrans();

            foreach ($this->ingr_abono_det as $key => $val) {

                if (floatval($val) > 0) {

                    $id_cli_deuda = $this->fn_contador('CLI_CLIENTES_DEUDAS-ID-00');

                    if (!$id_cli_deuda) $this->cn->FailTrans();

                    $this->sql = "CLI_ClientesDeudas_Insert '" . $id_cli_deuda . "','" . $this->g_id() . "','" . $this->id_ingrban . "','" . $this->id_asient . "','" . $this->num_ingrban . "','";
                    $this->sql .= "$detalle'," . number_format($val, 2) . "," . number_format($val, 2) . ",'" . $this->g_fecha_1() . "','" . $this->ingr_fvenc[$key] . "','";
                    $this->sql .= $this->ingr_rubro[$key] . "','" . $this->ingr_cxc[$key] . "','0000000002',1,0,'" . $this->g_tipo() . "',1,'" . $this->ingr_deud_id[$key] . "','','0',";
                    $this->sql .= "'0000000001','$user','00','$pc_cliente'";
                    if (!$this->ejecutar()) $this->cn->FailTrans();

                }
            }

            $id_ban_kardex = $this->fn_contador('BAN_BANCOS_CARDEX-ID-00');

            if (!$id_ban_kardex) $this->cn->FailTrans();

            $this->sql = "BAN_BancosCardex_Insert '" . $id_ban_kardex . "','" . $this->id_banco . "','" . $this->id_ingrban . "','" . $this->num_ingrban . "','";
            $this->sql .= $this->g_fecha_1() . "','" . $this->g_tipo() . "','','" . $this->g_fecha_1() . "','','$detalle','0000000002',1,1," . number_format($this->totabono, 2) . ',';
            $this->sql .= number_format($this->totabono, 2) . ",'0000000001','$user','00','$pc_cliente'";

            if (!$this->ejecutar()) $this->cn->FailTrans();

            $this->cn->CompleteTrans();
            echo '{"rp":"1","doc":"' . $this->id_ingrban . '"}';

        } catch (Exception $e) {
            $this->cn->CompleteTrans();
            echo "Caught Exception('{$e->getMessage()}')\n{$e}\n";
        }
    }
}

?>