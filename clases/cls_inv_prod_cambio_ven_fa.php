<?php

require_once 'cls_acceso_datos.php';

class cls_inv_prod_cambio_ven_fa extends cls_acceso_datos {

    protected $id_cliente = '';
    protected $dtcliente = '';
    protected $cli_ruc = '';
    protected $id_vendedor = '';
    protected $id_bodega = '';
    protected $tipo_fact = 'VEN-FA';
    protected $fecha = '';
    protected $nota = '';
    protected $cuenta = '';
    protected $id_ven_fact = '';
    protected $num_ven_fact = '';
    protected $sec_ven_fact = '';
    protected $id_asient = '';
    protected $num_asient = '';
    public $sub_total_fact = 0;
    public $total_fact = 0;
    protected $cost_tot_prod = 0;
    public $arr_id_producto;
    public $arr_precio_pvp;
    public $arr_costo_prod;
    public $arr_subtotal;
    public $arr_val_cant_fact_det;
    public $codprod = '';

    public function s_id_cliente($value) {
        $this->id_cliente = trim($value);
    }

    public function s_cliente($value) {
        $this->dtcliente = trim($value);
    }

    public function s_ruc($value) {
        $this->cli_ruc = trim($value);
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

    public function s_id_vendedor($value) {
        $this->id_vendedor = trim($value);
    }

    public function s_id_usuario($value) {
        $this->cuenta = trim($value);
    }

    public function s_sub_total_fact($value) {
        $this->sub_total_fact = floatval($value);
    }

    public function s_total_fact($value) {
        $this->total_fact = floatval($value);
    }

    function __construct() {
        parent::__construct();

        $this->arr_id_producto = array();
        $this->arr_precio_pvp = array();
        $this->arr_costo_prod = array();
        $this->arr_subtotal = array();
        $this->arr_val_cant_fact_det = array();
    }

    public function fn_inv_stock_producto($idprod) {
        $this->cn->SetFetchMode(ADODB_FETCH_ASSOC);
        $this->sql = "WEB_INV_PRODUCTO_STOCK_SELECT '$idprod','" . $this->id_bodega . "'";
        $this->ejecutar();
        if ($this->exist_reg()) {
            $cmp = $this->campos();
            $this->codprod = strtoupper(trim($cmp['Cod']));
            return intval($cmp['Stock']);
        }
        return 0;
    }

    public function fn_ven_factura_contadores() {

        $this->cn->SetFetchMode(ADODB_FETCH_ASSOC);
        $this->sql = 'WEB_PROC_VEN_FACTURA_CONTADORES';
        $this->ejecutar();
        if ($this->exist_reg()) {

            $cmp = $this->campos();
            $this->id_ven_fact = trim($cmp['VenFactID']);
            $this->num_ven_fact = trim($cmp['VenFactNum']);
            $this->sec_ven_fact = trim($cmp['VenFactSec']);
            $this->id_asient = trim($cmp['AsientID']);
            $this->num_asient = trim($cmp['AsientNum']);

            if ($this->id_ven_fact and $this->num_ven_fact and $this->id_asient and $this->num_asient)
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

    public function fn_ven_guardar_venta_factura() {

        try {

            $pc_cliente = $_SERVER["REMOTE_ADDR"];
            $this->cn->StartTrans();

            if (!$this->fn_ven_factura_contadores()) {
                die('{"err":"8"}');
            }

            $this->sql = "VEN_Facturas_Insert1 '" . $this->id_ven_fact . "','','" . $this->id_cliente . "','','','" . $this->dtcliente . "','" . $this->cli_ruc . "','";
            $this->sql.=$this->id_asient . "','" . $this->id_vendedor . "','','','','" . $this->sec_ven_fact . "','0000000001','','0000000001',0,'";
            $this->sql.=$this->num_ven_fact . "','" . $this->fecha . "','" . $this->fecha . "','" . $this->tipo_fact . "','0000000002',1," . number_format($this->sub_total_fact, 2) . ',';
            $this->sql.="0,0,0," . number_format($this->total_fact, 2) . ",'',0,'" . $this->nota . "','','','','','','','" . $this->cuenta . "','00','$pc_cliente'";

            if (!$this->ejecutar())
                $this->cn->FailTrans();

            foreach ($this->arr_val_cant_fact_det as $key => $val) {

                if (intval($val) > 0) {

                    $id_fact_det = $this->fn_contador('VEN_FACTURAS_DT-ID-00');

                    if (!$id_fact_det)
                        $this->cn->FailTrans();

                    $this->sql = "VEN_FacturasDT_Insert '$id_fact_det','" . $this->id_ven_fact . "','','" . $this->arr_id_producto[$key] . "','" . $this->id_bodega . "',$val,0,";
                    $this->sql.=$this->arr_precio_pvp[$key] . ',' . $this->arr_costo_prod[$key] . ',' . number_format($this->arr_subtotal[$key], 2) . ",0,0,0,0,0,0,";
                    $this->sql.=number_format($this->arr_subtotal[$key], 2) . ",'01','','EDITABLE',1,'','" . $this->cuenta . "','00','$pc_cliente'";

                    if (!$this->ejecutar())
                        $this->cn->FailTrans();

                    $cost_prod = floatval($this->arr_costo_prod[$key] * intval($val));
                    $this->cost_tot_prod += $cost_prod;
                }
            }

            $detnota = 'Fact#: -' . $this->sec_ven_fact . '-' . $this->nota;

            $this->sql = "ACC_Asientos_Insert '" . $this->id_asient . "','" . $this->num_asient . "','" . $this->id_ven_fact . "','" . $this->fecha . "','" . $this->tipo_fact . "','";
            $this->sql.="$detnota','" . $this->nota . "','0000000001','" . $this->cuenta . "','00','$pc_cliente'";

            if (!$this->ejecutar())
                $this->cn->FailTrans();

            $this->sql = "ACC_AsientosDT_Insert '" . $this->id_asient . "','0000000086','$detnota',1,'0000000002',1,";
            $this->sql.=number_format($this->sub_total_fact, 2) . ',' . number_format($this->total_fact, 2) . ",'" . $this->cuenta . "','00','$pc_cliente'";

            if (!$this->ejecutar())
                $this->cn->FailTrans();

            $this->sql = "ACC_AsientosDT_Insert '" . $this->id_asient . "','0000000142','$detnota',1,'0000000002',1,";
            $this->sql.=number_format($this->cost_tot_prod, 2) . ',' . number_format($this->cost_tot_prod, 2) . ",'" . $this->cuenta . "','00','$pc_cliente'";

            if (!$this->ejecutar())
                $this->cn->FailTrans();

            $this->sql = "ACC_AsientosDT_Insert '" . $this->id_asient . "','0000000200','$detnota',0,'0000000002',1,";
            $this->sql.=number_format($this->cost_tot_prod, 2) . ',' . number_format($this->cost_tot_prod, 2) . ",'" . $this->cuenta . "','00','$pc_cliente'";

            if (!$this->ejecutar())
                $this->cn->FailTrans();

            $this->sql = "ACC_AsientosDT_Insert '" . $this->id_asient . "','0000000043','$detnota',0,'0000000002',1,";
            $this->sql.=number_format($this->sub_total_fact, 2) . ',' . number_format($this->total_fact, 2) . ",'" . $this->cuenta . "','00','$pc_cliente'";

            if (!$this->ejecutar())
                $this->cn->FailTrans();


            foreach ($this->arr_val_cant_fact_det as $key => $val) {

                if (intval($val) > 0) {

                    $this->sql = "INV_ProductosCardex_Insert '" . $this->arr_id_producto[$key] . "','" . $this->id_bodega . "','" . $this->id_asient . "','";
                    $this->sql.=$this->id_ven_fact . "','" . $this->num_ven_fact . "','" . $this->fecha . "','" . $this->tipo_fact . "','$detnota',1,$val,";
                    $this->sql.=$this->arr_costo_prod[$key] . ",'0000000002',1,'0000000001','" . $this->cuenta . "','00','$pc_cliente'";

                    if (!$this->ejecutar())
                        $this->cn->FailTrans();
                }
            }

            $id_cli_deuda = $this->fn_contador('CLI_CLIENTES_DEUDAS-ID-00');

            if (!$id_cli_deuda)
                $this->cn->FailTrans();

            $this->sql = "CLI_ClientesDeudas_Insert '$id_cli_deuda','" . $this->id_cliente . "','" . $this->id_ven_fact . "','" . $this->id_asient . "','" . $this->num_ven_fact . "','";
            $this->sql.="$detnota'," . number_format($this->total_fact, 2) . "," . number_format($this->total_fact, 2) . ",'" . $this->fecha . "','" . $this->fecha . "','0000000001','";
            $this->sql.="0000000086','0000000002',1," . number_format($this->total_fact, 2) . ",'" . $this->tipo_fact . "',0,'','" . $this->id_vendedor . "','0',";
            $this->sql.="'0000000001','" . $this->cuenta . "','00','$pc_cliente'";

            if (!$this->ejecutar())
                $this->cn->FailTrans();

            $this->cn->CompleteTrans();

            echo'{"rp":"1","doc":"' . $this->id_ven_fact . '"}';
        } catch (Exception $e) {

            $this->cn->CompleteTrans();
            echo"Caught Exception('{$e->getMessage()}')\n{$e}\n";
        }
    }

}

?>