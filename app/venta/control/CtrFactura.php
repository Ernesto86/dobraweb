<?php

/**
 * Created by PhpStorm.
 * User: PC-Recaudacion19
 * Date: 15/9/2018
 * Time: 10:13
 */
class CtrFactura
{

    protected $facturaDao = null;
    private $userid;
    private $user;

    //put your code here
    public function __construct()
    {
        require CONEXION;
        require MOD . '/dao/facturaDao.php';
        $this->facturaDao = new FacturaDao();

        if (session_id() == '') {
            session_start();
        }
        $this->userid = $_SESSION['us_id'];
        $this->user = $_SESSION['us_cuenta'];
    }

    public function view()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {

            if (isset($_POST['action'])) {

                $action = $_POST['action'];
                $res = new stdClass;
                $res->resp = false;

                switch ($action) {
                    case 'venta_factura':

                        require DROOT . 'sistema/dao/ContadorDao.php';
                        require DROOT . 'acreedor/dao/AcreedorDao.php';
                        require DROOT . 'acreedor/dao/CuentaDao.php';
                        require DROOT . 'cliente/dao/ClienteDao.php';
                        require DROOT . 'inventario/dao/ProductoDao.php';


                        $pdo = DataConection::getInstancia();

                        try {

                            $factura = json_decode($_POST['factura']);
                            $totaldebe = 0;
                            $totalhaber = 0;

                            $divisionid = $factura->divisionid;
                            $secuencia = SUC . substr($divisionid, -1);

                            $contadorDao = new ContadorDao();
                            $acreedorDao = new AcreedorDao();
                            $cuentaDao = new CuentaDao();
                            $clienteDao = new ClienteDao();
                            $productoDao = new ProductoDao();

                            $pdo->beginTransaction();

                            $contador = $contadorDao->getContadores($secuencia, 'WEB_PROC_VEN_FACTURA_CONTADORES');
                            if ($contador == null)
                                throw new Exception('No se Genero contadores.');

                            $factura->id = $contador->venfacturaid;
                            $factura->numero = $contador->venfacturanum;
                            $factura->secuencia = $contador->venfacturasec;
                            $factura->asientoid = $contador->asientoid;
                            $factura->asientonum = $contador->asientonum;
                            $factura->docid = $contador->venfacturaid;

                            $factura->empleadoid = $this->userid;
                            $factura->divisaid = DIVISA;
                            $factura->cambio = CAMBIO;
                            $factura->creadopor = $this->user;
                            $factura->sucursalid = SUC;
                            $factura->valor_base = round(floatval($factura->total) * floatval(CAMBIO), 2);

                            $this->facturaDao->setVentaFactura($factura);
                            $res->factura = true;

                            $factura->nota = $factura->detalle;
                            $factura->detalle = 'Fact#: -' . $factura->secuencia . '-' . $factura->detalle;

                            foreach ($factura->items as $item) {

                                if ($item->cantidad > 0) {

                                    $item->id = $contadorDao->getContador('VEN_FACTURAS_DT-ID-', $secuencia);
                                    if ($item->id == null)
                                        throw new Exception('No se Genero contador Factura Detalle.');

                                    $item->asientoid = $contador->asientoid;
                                    $item->facturaid = $contador->venfacturaid;
                                    $item->docid = $contador->venfacturaid;
                                    $item->numero = $contador->venfacturanum;
                                    $item->fecha = $factura->fecha;
                                    $item->tipo = $factura->tipo;
                                    $item->detalle = $factura->detalle;

                                    $item->egreso = 1;
                                    $item->divisaid = DIVISA;
                                    $item->cambio = CAMBIO;
                                    $item->divisionid = $divisionid;
                                    $item->creadopor = $this->user;
                                    $item->sucursalid = SUC;

                                    $this->facturaDao->setVentaFacturaDetalle($item);
                                    $productoDao->setInvProductoKardex($item);
                                    $res->facturaDetalle = true;
                                    $res->productoKardex = true;
                                }

                            }

                            $acreedorDao->setAsiento($factura);
                            $res->asiento = true;

                            $rubro = $cuentaDao->getRubroFactura();
                            if ($rubro == null)
                                throw new Exception('No se Encontró Rubro Factura.');

                            $factura->cuentaid = $rubro->ctadebeid;
                            $factura->debito = 1;
                            $acreedorDao->setAccAsientoDetalle($factura);
                            $res->asientoDetalle = true;
                            $totaldebe += $factura->valor_base;

                            $cuentas = $this->facturaDao->getCuentaDescuentoFactura($factura->id);
                            foreach ($cuentas as $c) {
                                if (floatval($c->valor) > 0) {
                                    if (empty($c->ctadescuentoid))
                                        throw new Exception('No se Encontró Cuenta Descuento id.');

                                    $factura->cuentaid = $c->ctadescuentoid;
                                    $factura->valor = floatval($c->valor);
                                    $factura->valor_base = round(floatval($c->valor) * CAMBIO, 2);
                                    $totaldebe += $factura->valor_base;
                                    $acreedorDao->setAccAsientoDetalle($factura);
                                    $res->asientoDetalleDescuento = true;
                                }
                            }

                            $cuentas = $this->facturaDao->getCuentaCostoInvProducto($factura->id);
                            foreach ($cuentas as $c) {
                                if (floatval($c->valor) > 0) {
                                    if (empty($c->ctacostoid))
                                        throw new Exception('No se Encontró Cuenta Costo id.');

                                    $factura->cuentaid = $c->ctacostoid;
                                    $factura->valor = floatval($c->valor);
                                    $factura->valor_base = round(floatval($c->valor) * CAMBIO, 2);
                                    $totaldebe += $factura->valor_base;
                                    $acreedorDao->setAccAsientoDetalle($factura);
                                    $res->asientoDetalleCosto = true;
                                }
                            }

                            $factura->debito = 0;
                            $cuentas = $this->facturaDao->getCuentaMayorInvProducto($factura->id);
                            foreach ($cuentas as $c) {
                                if (floatval($c->valor) > 0) {
                                    if (empty($c->ctamayorid))
                                        throw new Exception('No se Encontró Cuenta Mayor id.');

                                    $factura->cuentaid = $c->ctamayorid;
                                    $factura->valor = floatval($c->valor);
                                    $factura->valor_base = round(floatval($c->valor) * CAMBIO, 2);
                                    $totalhaber += $factura->valor_base;
                                    $acreedorDao->setAccAsientoDetalle($factura);
                                    $res->asientoDetalleMayor = true;
                                }
                            }

                            $cuentas = $this->facturaDao->getCuentaVenFactura($factura->id);
                            foreach ($cuentas as $c) {
                                if (floatval($c->valor) > 0) {
                                    if (empty($c->ctaventasid))
                                        throw new Exception('No se Encontró Cuenta Mayor id.');

                                    $factura->cuentaid = $c->ctaventasid;
                                    $factura->valor = floatval($c->valor);
                                    $factura->valor_base = round(floatval($c->valor) * CAMBIO, 2);
                                    $totalhaber += $factura->valor_base;
                                    $acreedorDao->setAccAsientoDetalle($factura);
                                    $res->asientoDetalleVenta = true;
                                }
                            }

                            $cuentas = $this->facturaDao->getCuentaImpuestoFactura($factura->id);
                            foreach ($cuentas as $c) {
                                if (floatval($c->valor) > 0) {
                                    if (empty($c->impuestoid))
                                        throw new Exception('No se Encontró Cuenta Impuesto id.');

                                    $factura->cuentaid = $c->impuestoid;
                                    $factura->valor = floatval($c->valor);
                                    $factura->valor_base = round(floatval($c->valor) * CAMBIO, 2);
                                    $totalhaber += $factura->valor_base;
                                    $acreedorDao->setAccAsientoDetalle($factura);
                                    $res->asientoDetalleImpuesto = true;
                                }
                            }

                            if (round($totaldebe, 2) != round($totalhaber, 2)) {
                                $msm = 'Asiento desbalanceado. Total Debe: '.$totaldebe.' - Total Haber: '.$totalhaber;
                                throw new Exception($msm);
                            }
                            $res->totaldebe = $totaldebe;
                            $res->totalhaber = $totalhaber;

                            $factura->id = $contadorDao->getContador('CLI_CLIENTES_DEUDAS-ID-', $secuencia);
                            if ($factura->id == null)
                                throw new Exception('No se Genero contador Cliente Deuda.');

                            $factura->valor = round(floatval($factura->total),2);
                            $factura->valor_base = round(floatval($factura->total) * floatval(CAMBIO), 2);
                            $factura->credito = 0;
                            $factura->anula = 0;
                            $factura->deudaid = '';
                            $factura->rubroid = $rubro->id;
                            $factura->ctacxid = $rubro->ctadebeid;
                            $clienteDao->setClienteDeudas($factura);
                            $res->clienteDeuda= true;
                            $res->contadores = $contador;
                            $res->resp = true;
                            $pdo->commit();

                        } catch (Exception $ex) {
                            $pdo->rollBack();
                            $res->error = $ex->getMessage();
                        }
                        echo json_encode($res);
                        break;
                }
            }
        } else {

            if (isset($_GET['action'])) {

                $action = $_GET['action'];

            }
        }
    }
}