<?php

require_once 'cls_acceso_datos.php';

class cls_ven_devolucion_venta extends cls_acceso_datos {

    protected $id_fact = '';
    protected $id_cliente = '';
    protected $id_bodega = '';
    protected $tipo_fact = 'VEN-DE';
    protected $fecha = '';
    protected $nota = '';
    protected $concept_devol = '';
    protected $cuenta = '';
    protected $cliente = '';
    protected $idvendedor = '';
    protected $cost_tot_prod = 0;
    protected $id_devolucion = '';
    protected $num_devolucion = '';
    protected $sec_clicred = '';
    protected $id_asient = '';
    protected $num_asient = '';
    protected $id_cruce_valor = '';
    protected $num_cruce_valor = '';
    public $total_devol = 0;
    public $arr_val_devuelto;
    public $arr_fact_det_id;
    public $arr_val_cantidad;
    public $arr_id_producto;
    public $arr_precio_pvp;
    public $arr_costo_prod;
    public $arr_subtotal;
    public $arr_saldo_det;
    public $arr_total_devol;
    public $arr_deuda_id;
    public $arr_fact_num;
    public $arr_tip_fact;
    public $arr_det_fact;
    public $arr_cuenta_id;
    public $arr_fech_emis;
    public $arr_fech_venc;
    public $arr_rubro_id;
    public $arr_cred_id;

    public function s_id_factura($value) {
        $this->id_fact = trim($value);
    }

    public function s_id_cliente($value) {
        $this->id_cliente = trim($value);
    }

    public function s_cliente($value) {
        $this->cliente = trim($value);
    }

    public function s_id_bodega($value) {
        $this->id_bodega = trim($value);
    }

    public function s_tipo_factura($value) {
        $this->tipo_fact = trim($value);
    }

    public function s_fecha($value) {
        $this->fecha = trim($value);
    }

    public function s_nota($value) {
        $this->nota = trim($value);
    }

    public function s_det_concepto($value) {
        $this->concept_devol = trim($value);
    }

    public function s_id_usuario($value) {
        $this->cuenta = trim($value);
    }

    public function s_id_vendedor($value) {
        $this->idvendedor = trim($value);
    }

    function __construct() {

        parent::__construct();

        $this->arr_fact_det_id = array();
        $this->arr_val_devuelto = array();
        $this->arr_val_cantidad = array();
        $this->arr_id_producto = array();
        $this->arr_precio_pvp = array();
        $this->arr_costo_prod = array();
        $this->arr_subtotal = array();
        $this->arr_saldo_det = array();
        $this->arr_total_devol = array();
        $this->arr_deuda_id = array();
        $this->arr_fact_num = array();
        $this->arr_tip_fact = array();
        $this->arr_det_fact = array();
        $this->arr_cuenta_id = array();
        $this->arr_fech_emis = array();
        $this->arr_fech_venc = array();
        $this->arr_rubro_id = array();
        $this->arr_cred_id = array();
    }

    public function fn_cli_saldo_total() {
        $this->cn->SetFetchMode(ADODB_FETCH_ASSOC);
        $this->sql = "WEB_CLI_SALDO_TOTAL '" . $this->id_cliente . "'";
        $this->ejecutar();
        if ($this->exist_reg()) {
            $cmp = $this->campos();
            return floatval($cmp['saldo']);
        }return 0;
    }

    public function fn_ven_devolucion_contadores() {

        $this->cn->SetFetchMode(ADODB_FETCH_ASSOC);
        $this->sql = 'WEB_PROC_VEN_DEVOL_VENTA_CONTADORES';
        $this->ejecutar();

        if ($this->exist_reg()) {

            $cmp = $this->campos();

            $this->id_devolucion = trim($cmp['VenDevolID']);
            $this->num_devolucion = trim($cmp['VenDevolNum']);
            $this->sec_clicred = trim($cmp['CliCrdSec']);
            $this->id_asient = trim($cmp['AsientID']);
            $this->num_asient = trim($cmp['AsientNum']);

            if ($this->id_devolucion and $this->num_devolucion and $this->id_asient and $this->num_asient)
                return true;
        }
        return false;
    }

    public function fn_cli_cruce_valor_contadores() {

        $this->cn->SetFetchMode(ADODB_FETCH_ASSOC);
        $this->sql = 'WEB_PROC_CLI_CRUCE_VALOR_CONTADORES';
        $this->ejecutar();

        if ($this->exist_reg()) {

            $cmp = $this->campos();

            $this->id_cruce_valor = trim($cmp['CruceValID']);
            $this->num_cruce_valor = trim($cmp['CruceValNum']);
            $this->id_asient = trim($cmp['AsientID']);
            $this->num_asient = trim($cmp['AsientNum']);

            if ($this->id_cruce_valor and $this->num_cruce_valor and $this->id_asient and $this->num_asient)
                return true;
        }
        return false;
    }

    public function fn_contador($contador, $sp = 'SIS_GetNextID') {
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

    public function fn_ven_guardar_devolucion_venta() {

        try {

            $pc_cliente = $_SERVER["REMOTE_ADDR"];

            $this->cn->StartTrans();

            if (!$this->fn_ven_devolucion_contadores()) {
                die('{"err":"6"}');
            }

            $this->sql = "VEN_Devoluciones_Insert '" . $this->id_devolucion . "','" . $this->id_cliente . "','" . $this->idvendedor . "','" . $this->id_fact . "','";
            $this->sql.=$this->sec_clicred . "','','" . $this->cliente . "','" . $this->id_asient . "','0000000001','" . $this->num_devolucion . "','" . $this->fecha . "','";
            $this->sql.=$this->tipo_fact . "','0000000002',1," . $this->subtotal_devol . ",0,0,0," . $this->total_devol . ",'" . $this->concept_devol . "','" . $this->cuenta . "','00','$pc_cliente'";

            if (!$this->ejecutar())
                $this->cn->FailTrans();

            foreach ($this->arr_val_devuelto as $key => $val) {

                if (intval($val) > 0) {

                    $id_det_devolucion = $this->fn_contador('VEN_DEVOLUCIONES_DT-ID-00');

                    if (!$id_det_devolucion)
                        $this->cn->FailTrans();

                    $this->sql = "VEN_DevolucionesDT_Insert '$id_det_devolucion','" . $this->id_devolucion . "','" . $this->id_fact . "','" . $this->arr_fact_det_id[$key];
                    $this->sql.="','" . $this->arr_id_producto[$key] . "','" . $this->id_bodega . "'," . $this->arr_val_cantidad[$key] . ',';
                    $this->sql.=$val . ',' . $this->arr_precio_pvp[$key] . ',' . $this->arr_costo_prod[$key] . ',' . $this->arr_subtotal[$key] . ',';
                    $this->sql.="0,0,0,0,0,0," . $this->arr_subtotal[$key] . ",'','EDITABLE',1,'','" . $this->cuenta . "','00','$pc_cliente' ";

                    if (!$this->ejecutar())
                        $this->cn->FailTrans();

                    $this->sql = "VEN_FacturasDT_Update_Devuelto '" . $this->arr_fact_det_id[$key] . "'," . $val;

                    if (!$this->ejecutar())
                        $this->cn->FailTrans();

                    $cost_prod = floatval($this->arr_costo_prod[$key] * intval($val));
                    $this->cost_tot_prod += $cost_prod;
                }
            }

            $this->sql = "ACC_Asientos_Insert '" . $this->id_asient . "','" . $this->num_asient . "','" . $this->id_devolucion . "','" . $this->fecha . "','" . $this->tipo_fact . "','";
            $this->sql.=$this->cliente . "','" . $this->concept_devol . "','0000000001','" . $this->cuenta . "','00','$pc_cliente'";

            if (!$this->ejecutar())
                $this->cn->FailTrans();


            $this->sql = "ACC_AsientosDT_Insert '" . $this->id_asient . "','0000000710','" . $this->cliente . "',1,'0000000002',1," . $this->total_devol . ',';
            $this->sql.=$this->total_devol . ",'" . $this->cuenta . "','00','$pc_cliente'";

            if (!$this->ejecutar())
                $this->cn->FailTrans();


            $this->sql = "ACC_AsientosDT_Insert '" . $this->id_asient . "','0000000200','" . $this->cliente . "',1,'0000000002',1," . $this->cost_tot_prod . ',';
            $this->sql.=$this->cost_tot_prod . ",'" . $this->cuenta . "','00','$pc_cliente'";

            if (!$this->ejecutar())
                $this->cn->FailTrans();


            $this->sql = "ACC_AsientosDT_Insert '" . $this->id_asient . "','0000000086','" . $this->cliente . "',0,'0000000002',1,";
            $this->sql.=$this->total_devol . ',' . $this->total_devol . ",'" . $this->cuenta . "','00','$pc_cliente'";

            if (!$this->ejecutar())
                $this->cn->FailTrans();


            $this->sql = "ACC_AsientosDT_Insert '" . $this->id_asient . "','0000000142','" . $this->cliente . "',0,'0000000002',1,";
            $this->sql.=$this->cost_tot_prod . ',' . $this->cost_tot_prod . ",'" . $this->cuenta . "','00','$pc_cliente'";

            if (!$this->ejecutar())
                $this->cn->FailTrans();


            foreach ($this->arr_val_devuelto as $key => $val) {

                if (intval($val) > 0) {

                    $this->sql = "INV_ProductosCardex_Insert '" . $this->arr_id_producto[$key] . "','" . $this->id_bodega . "','" . $this->id_asient . "','";
                    $this->sql.=$this->id_devolucion . "','" . $this->num_devolucion . "','" . $this->fecha . "','" . $this->tipo_fact . "','" . $this->cliente . "',0,$val,";
                    $this->sql.=$this->arr_costo_prod[$key] . ",'0000000002',1,'0000000001','" . $this->cuenta . "','00','$pc_cliente'";

                    if (!$this->ejecutar())
                        $this->cn->FailTrans();
                }
            }

            $id_cli_deuda = $this->fn_contador('CLI_CLIENTES_DEUDAS-ID-00');

            if (!$id_cli_deuda)
                $this->cn->FailTrans();

            $this->sql = "CLI_ClientesDeudas_Insert '$id_cli_deuda','" . $this->id_cliente . "','" . $this->id_devolucion . "','" . $this->id_asient . "','" . $this->num_devolucion . "','";
            $this->sql.=$this->cliente . "'," . $this->subtotal_devol . "," . $this->total_devol . ",'" . $this->fecha . "','" . $this->fecha . "','0000000001','0000000086','0000000002',";
            $this->sql.="1," . $this->total_devol . ",'" . $this->tipo_fact . "',1,'','','0','0000000001','" . $this->cuenta . "','00','$pc_cliente'";

            if (!$this->ejecutar())
                $this->cn->FailTrans();

            $this->cn->CompleteTrans();


            ////////CRUCE DE VALORES A FAVOR CLIENTE.. ///////////

            $this->cn->StartTrans();

            if (!$this->fn_cli_cruce_valor_contadores()) {
                die('{"err":"7"}');
            }

            $this->sql = "CLI_CruceClientes_Insert '" . $this->id_cruce_valor . "','" . $this->id_cliente . "','" . $this->id_asient . "','" . $this->fecha . "','";
            $this->sql.=$this->num_cruce_valor . "','" . $this->nota . "','CLI-CR-AF'," . $this->total_devol . ",'" . $this->nota . "','0000000001','" . $this->cuenta . "','00','$pc_cliente'";
            if (!$this->ejecutar())
                $this->cn->FailTrans();

            foreach ($this->arr_total_devol as $key => $val) {

                if (floatval($val) > 0) {

                    $this->sql = "CLI_CruceDeudas_Insert '" . $this->id_cruce_valor . "','" . $this->arr_deuda_id[$key] . "','0000000002',1,";
                    $this->sql.=$this->arr_saldo_det[$key] . ',' . $val . ",0,'" . $this->arr_fech_emis[$key] . "','" . $this->arr_fech_venc[$key] . "','1','";
                    $this->sql.=$this->arr_tip_fact[$key] . "','" . $this->arr_fact_num[$key] . "','" . $this->arr_det_fact[$key] . "','0','" . $this->arr_rubro_id[$key] . "','";
                    $this->sql.=$this->arr_cuenta_id[$key] . "','1','" . $this->cuenta . "','00','$pc_cliente'";

                    if (!$this->ejecutar())
                        $this->cn->FailTrans();
                }
            }

            $id_deuda_devol = $id_cli_deuda;

            if (!$id_deuda_devol)
                $this->cn->FailTrans();

            $this->sql = "CLI_CruceDeudas_Insert '" . $this->id_cruce_valor . "','$id_deuda_devol','0000000002',1," . $this->total_devol . "," . $this->total_devol . ",0,'";
            $this->sql.=$this->fecha . "','','2','" . $this->tipo_fact . "','" . $this->num_devolucion . "','" . $this->cliente . "','1','0000000001','";
            $this->sql.="0000000086','1','" . $this->cuenta . "','00','$pc_cliente'";

            if (!$this->ejecutar())
                $this->cn->FailTrans();

            $this->sql = "ACC_Asientos_Insert '" . $this->id_asient . "','" . $this->num_asient . "','" . $this->id_cruce_valor . "','" . $this->fecha . "','CLI-CR-AF','";
            $this->sql.=$this->nota . "','" . $this->nota . "','0000000001','" . $this->cuenta . "','00','$pc_cliente'";

            if (!$this->ejecutar())
                $this->cn->FailTrans();

            $this->sql = "ACC_AsientosDT_Insert '" . $this->id_asient . "','0000000086','" . $this->nota . "',1,'0000000002',1," . $this->total_devol . ",";
            $this->sql.=$this->total_devol . ",'" . $this->cuenta . "','00','$pc_cliente'";

            if (!$this->ejecutar())
                $this->cn->FailTrans();

            $this->sql = "ACC_AsientosDT_Insert '" . $this->id_asient . "','0000000086','" . $this->nota . "',0,'0000000002',1," . $this->total_devol . ",";
            $this->sql.=$this->total_devol . ",'" . $this->cuenta . "','00','$pc_cliente'";

            if (!$this->ejecutar())
                $this->cn->FailTrans();


            foreach ($this->arr_total_devol as $key => $val) {

                if (floatval($val) > 0) {

                    $id_cli_deuda = $this->fn_contador('CLI_CLIENTES_DEUDAS-ID-00');

                    if (!$id_cli_deuda)
                        $this->cn->FailTrans();

                    $tip_fact = $this->arr_tip_fact[$key];
                    $num_doc = $this->arr_fact_num[$key];
                    $det_nota = $this->nota . "-$tip_fact:$num_doc";

                    $this->sql = "CLI_ClientesDeudas_Insert '$id_cli_deuda','" . $this->id_cliente . "','" . $this->id_cruce_valor . "','" . $this->id_asient . "','";
                    $this->sql.=$this->num_cruce_valor . "','$det_nota'," . $val . "," . $val . ",'" . $this->fecha . "','" . $this->arr_fech_venc[$key] . "','";
                    $this->sql.=$this->arr_rubro_id[$key] . "','" . $this->arr_cuenta_id[$key] . "','0000000002',1,0,'CLI-CR-AF',1,'" . $this->arr_deuda_id[$key] . "','','0',";
                    $this->sql.="'0000000001','" . $this->cuenta . "','00','$pc_cliente'";

                    if (!$this->ejecutar())
                        $this->cn->FailTrans();
                }
            }

            $id_cli_deuda = $this->fn_contador('CLI_CLIENTES_DEUDAS-ID-00');

            if (!$id_cli_deuda)
                $this->cn->FailTrans();

            $this->sql = "CLI_ClientesDeudas_Insert '$id_cli_deuda','" . $this->id_cliente . "','" . $this->id_cruce_valor . "','" . $this->id_asient . "','";
            $this->sql.=$this->num_cruce_valor . "','" . $this->nota . "'," . $this->total_devol . "," . $this->total_devol . ",'" . $this->fecha . "','" . $this->fecha . "','";
            $this->sql.="0000000001','0000000086','0000000002',1,0,'CLI-CR-AF',0,'$id_deuda_devol','','0','0000000001','" . $this->cuenta . "','00','$pc_cliente'";

            if (!$this->ejecutar())
                $this->cn->FailTrans();

            $this->cn->CompleteTrans();
            echo'{"rp":"1","doc":"' . $this->id_devolucion . '"}';
        } catch (Exception $e) {
            $this->cn->CompleteTrans();
            echo"Caught Exception('{$e->getMessage()}')\n{$e}\n";
        }
    }

}

?>