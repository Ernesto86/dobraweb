<?php

require_once 'cls_acceso_datos.php';

class cls_cli_cliente_premios extends cls_acceso_datos {

    protected $id_cliente = '';
    protected $id_bodega = '';
    protected $tipo_fact = 'VEN-NV';
    protected $fecha = '';
    protected $nota = '';
    protected $cuenta = '';
    protected $cliente = '';
    protected $id_notaventa = '';
    protected $num_notaventa = '';
    protected $id_asient = '';
    protected $num_asient = '';
    protected $id_vendedor = '';
    protected $det_tipo = 'P';
    public $codprod = '';
    public $id_producto;
    public $costo;
    public $producto;
    public $cantidad;
    public $sum_costo = 0;

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

    public function s_id_vendedor($value) {
        $this->id_vendedor = trim($value);
    }

    public function s_id_usuario($value) {
        $this->cuenta = trim($value);
    }

    function __construct() {
        parent::__construct();
        $this->id_producto = array();
        $this->costo = array();
        $this->producto = array();
        $this->cantidad = array();
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

    public function fn_ven_ingreso_contadores() {
        $this->cn->SetFetchMode(ADODB_FETCH_ASSOC);
        $this->sql = 'WEB_PROC_VEN_NOTA_VENTA_PREMIO_CONTADORES';
        $this->ejecutar();
        if ($this->exist_reg()) {
            $cmp = $this->campos();
            $this->id_notaventa = trim($cmp['NotaVentID']);
            $this->num_notaventa = trim($cmp['NotaVentNum']);
            $this->id_asient = trim($cmp['AsientID']);
            $this->num_asient = trim($cmp['AsientNum']);

            if ($this->id_notaventa and $this->num_notaventa and $this->id_asient and $this->num_asient)
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

    public function fn_ven_guardar_nota_venta_premio() {

        try {

            $pc_cliente = $_SERVER["REMOTE_ADDR"];
            $this->cn->StartTrans();

            if (!$this->fn_ven_ingreso_contadores()) {
                die('{"err":"6"}');
            }

            $this->sql = "VEN_NotasDeVenta_Insert '" . $this->id_notaventa . "','','" . $this->id_cliente . "','','','" . $this->cliente . "','','" . $this->id_asient . "','";
            $this->sql.=$this->id_vendedor . "','" . $this->det_tipo . "','','','','0000000001',0,'" . $this->num_notaventa . "','" . $this->fecha . "','" . $this->fecha . "','";
            $this->sql.=$this->tipo_fact . "','0000000002',1,0,0,0,0,0,'',0,'" . $this->nota . "','','','','','','','" . $this->cuenta . "','00','$pc_cliente'";
            //                                            pvp     pvp  

            if (!$this->ejecutar())
                $this->cn->FailTrans();

            foreach ($this->cantidad as $key => $val) {

                if (intval($val) > 0) {

                    $id_notaventa_dt = $this->fn_contador('VEN_NOTASDEVENTA_DT-ID-00');

                    if (!$id_notaventa_dt)
                        $this->cn->FailTrans();

                    $this->sql = "VEN_NotasDeVentaDT_Insert '" . $id_notaventa_dt . "','" . $this->id_notaventa . "','','" . $this->id_producto[$key] . "','" . $this->id_bodega . "',";
                    $this->sql.=intval($val) . ',0,0.0089,' . number_format($this->costo[$key], 4) . ",0,0,0,0,0,0,0,0,'01','','EDITABLE',1,'','" . $this->cuenta . "','00','$pc_cliente'";

                    if (!$this->ejecutar())
                        $this->cn->FailTrans();
                }
            }

            $this->sql = "ACC_Asientos_Insert '" . $this->id_asient . "','" . $this->num_asient . "','" . $this->id_notaventa . "','" . $this->fecha . "','" . $this->tipo_fact . "','";
            $this->sql.=$this->cliente . "','" . $this->nota . "','0000000001','" . $this->cuenta . "','00','$pc_cliente'";

            if (!$this->ejecutar())
                $this->cn->FailTrans();

            $this->sum_costo = floatval(array_sum($this->costo));

            $this->sql = "ACC_AsientosDT_Insert '" . $this->id_asient . "','0000000086','" . $this->cliente . "',1,'0000000002',1,0,0,'" . $this->cuenta . "','00','$pc_cliente'";

            if (!$this->ejecutar())
                $this->cn->FailTrans();

            $this->sql = "ACC_AsientosDT_Insert '" . $this->id_asient . "','0000000142','" . $this->cliente . "',1,'0000000002',1,";
            $this->sql.=number_format($this->sum_costo, 2) . ',' . number_format($this->sum_costo, 4) . ",'" . $this->cuenta . "','00','$pc_cliente'";

            if (!$this->ejecutar())
                $this->cn->FailTrans();


            $this->sql = "ACC_AsientosDT_Insert '" . $this->id_asient . "','0000000200','" . $this->cliente . "',0,'0000000002',1,";
            $this->sql.=number_format($this->sum_costo, 2) . ',' . number_format($this->sum_costo, 4) . ",'" . $this->cuenta . "','00','$pc_cliente'";

            if (!$this->ejecutar())
                $this->cn->FailTrans();


            $this->sql = "ACC_AsientosDT_Insert '" . $this->id_asient . "','0000000043','" . $this->cliente . "',0,'0000000002',1,0,0,'";
            $this->sql.=$this->cuenta . "','00','$pc_cliente'";

            if (!$this->ejecutar())
                $this->cn->FailTrans();

            foreach ($this->id_producto as $key => $val) {

                if (trim($val)) {
                    $this->sql = "INV_ProductosCardex_Insert '$val','" . $this->id_bodega . "','" . $this->id_asient . "','" . $this->id_notaventa . "','";
                    $this->sql.=$this->num_notaventa . "','" . $this->fecha . "','" . $this->tipo_fact . "','" . $this->nota . "',1," . intval($this->cantidad[$key]) . ',';
                    $this->sql.=number_format($this->costo[$key], 4) . ",'0000000002',1,'0000000001','" . $this->cuenta . "','00','$pc_cliente'";

                    if (!$this->ejecutar())
                        $this->cn->FailTrans();
                }
            }

            $id_cli_deuda = $this->fn_contador('CLI_CLIENTES_DEUDAS-ID-00');

            if (!$id_cli_deuda)
                $this->cn->FailTrans();

            $this->sql = "CLI_ClientesDeudas_Insert '" . $id_cli_deuda . "','" . $this->id_cliente . "','" . $this->id_notaventa . "','" . $this->id_asient . "','" . $this->num_notaventa . "','";
            $this->sql.=$this->nota . "',0,0,'" . $this->fecha . "','" . $this->fecha . "','0000000001','0000000086','0000000002',1,0,'" . $this->tipo_fact . "',0,'','" . $this->id_vendedor . "',";
            $this->sql.="'0','0000000001','" . $this->cuenta . "','00','$pc_cliente'";

            if (!$this->ejecutar())
                $this->cn->FailTrans();

            $this->cn->CompleteTrans();

            echo'{"rp":"1","doc":"' . $this->id_notaventa . '"}';
        } catch (Exception $e) {

            $this->cn->CompleteTrans();
            echo"Caught Exception('{$e->getMessage()}')\n{$e}\n";
        }
    }

}

?>