<?php

/**
 * Created by PhpStorm.
 * User: Administrador
 * Date: 11/09/2018
 * Time: 15:53
 */
class CtrCliente
{

    protected $clienteDao = null;
    private $userid;
    private $user;

    //put your code here
    public function __construct()
    {
        require CONEXION;
        require MOD . '/dao/ClienteDao.php';
        $this->clienteDao = new ClienteDao();

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
                    case 'save':

                        require DROOT . 'sistema/dao/ContadorDao.php';
                        $pdo = DataConection::getInstancia();

                        try {
                            $insert = $_POST['insert'];
                            $divisionid = $_POST['divisionid'];
                            $secuencia = SUC . substr($divisionid, -1);
                            $cliente =(object) $_POST;

                            $contadorDao = new ContadorDao();

                            if($insert == 1){
                                $cliente->id = $contadorDao->getContador('CLI_CLIENTES-ID-', $secuencia);
                            }else{
                                if(empty($_POST['id']))
                                    throw new Exception('Invalido parametro Id');
                                $cliente->id =strval($_POST['id']);
                            }

                            $pdo->beginTransaction();
                            $this->clienteDao->save($cliente);
                            $res->resp = true;
                            $pdo->commit();

                        } catch (Exception $ex) {
                            $pdo->rollBack();
                            $res->error = $ex->getMessage();
                        }
                        echo json_encode($res);
                        break;

                    case 'cliente':

                        try {
                            $codigo = $_POST['cli_codigo'];
                            $cliente = $this->clienteDao->getCliente($codigo);
                            if ($cliente != null) {
                                $res->resp = true;
                                $res->cliente = $cliente;
                            } else {
                                $res->error = 'No existe el Código Ingresado';
                            }
                        } catch (Exception $ex) {
                            $res->error = $ex->getMessage();
                        }
                        echo json_encode($res);
                        break;

                    case 'estado_cuenta':
                        try {

                            $codigo = $_POST['codigo'];
                            $cliente = $this->clienteDao->getCliente($codigo);
                            if ($cliente != null) {
                                $res->cliente = $cliente;
                                $res->resp = true;
                                $cli = new stdClass;
                                $cli->cod = $codigo;
                                $cli->inicio = $_POST['inicio'];
                                $cli->fin = $_POST['fin'];
                                $res->deuda = $this->clienteDao->getEstadoCuenta($cli);
                            }

                        } catch (Exception $ex) {
                            $res->error = $ex->getMessage();
                        }
                        echo json_encode($res);
                        break;

                    case 'detalle_deuda':

                        try {
                            $codigo = $_POST['cli_codigo'];
                            $cliente = $this->clienteDao->getCliente($codigo, 'WEB_CLI_DEUDA_SELECT_CLIENTE_COD');
                            if ($cliente != null) {
                                $res->resp = true;
                                $res->cliente = $cliente;
                                $res->deuda = $this->clienteDao->getDetalleDeuda($cliente->id);
                            } else {
                                $res->error = 'No existe el Código Ingresado';
                            }
                        } catch (Exception $ex) {
                            $res->error = $ex->getMessage();
                        }
                        echo json_encode($res);
                        break;

                    case 'ingreso_dinero':

                        require DROOT . 'sistema/dao/ContadorDao.php';
                        require DROOT . 'banco/dao/BancoDao.php';
                        require DROOT . 'acreedor/dao/AcreedorDao.php';

                        $pdo = DataConection::getInstancia();

                        try {

                            $factura = json_decode($_POST['factura']);
                            $divisionid = $factura->divisionid;
                            $secuencia = SUC . substr($divisionid, -1);

                            $contadorDao = new ContadorDao();
                            $bancoDao = new BancoDao();
                            $acreedorDao = new AcreedorDao();

                            $pdo->beginTransaction();

                            $contador = $contadorDao->getContadores($secuencia, 'WEB_PROC_CLI_INGR_CONTADORES');
                            if ($contador == null)
                                throw new Exception('No se Genero contadores.');

                            $factura->bancopk = $contador->bancoid;
                            $factura->banconum = $contador->banconum;
                            $factura->asientoid = $contador->asientoid;
                            $factura->asientonum = $contador->asientonum;
                            $factura->docid = $contador->bancoid;

                            $factura->divisaid = DIVISA;
                            $factura->cambio = CAMBIO;
                            $factura->creadopor = $this->user;
                            $factura->sucursalid = SUC;

                            $bancoDao->setBanco($factura);
                            $res->bancoingre = true;

                            foreach ($factura->pagos as $p) {

                                if (floatval($p->valor) > 0) {

                                    $bancodetid = $contadorDao->getContador('BAN_INGRESOS_DT-ID-', $secuencia);
                                    if ($bancodetid == null)
                                        throw new Exception('No se Genero contador Banco Ingreso.');

                                    if ($bancodetid != null) {
                                        $p->bancodetid = $bancodetid;
                                        $p->ingresoid = $contador->bancoid;
                                        $p->divisaid = DIVISA;
                                        $p->cambio = CAMBIO;
                                        $p->creadopor = $this->user;
                                        $p->sucursalid = SUC;
                                        $p->pc = $factura->pc;
                                        $bancoDao->setBancoDetalle($p);
                                        $res->bancoingredet = true;
                                    }
                                }
                            }

                            foreach ($factura->deuda as $d) {

                                if (floatval($d->abono) > 0) {
                                    $d->ingresoid = $contador->bancoid;
                                    $d->divisaid = DIVISA;
                                    $d->cambiodia = 1;
                                    $d->difcambio = 0;
                                    $d->creadopor = $this->user;
                                    $d->sucursalid = SUC;
                                    $d->pc = $factura->pc;
                                    $bancoDao->setBancoIngresoDeudas($d);
                                    $res->bancoingredeuda = true;
                                }
                            }

                            $acreedorDao->setAsiento($factura);
                            $res->asiento = true;

                            $cuenta = $bancoDao->getCuentaBanco($factura->bancoid);
                            if ($cuenta == null)
                                throw new Exception('No se Encontró Cuenta Bancos.');

                            $factura->cuentaid = $cuenta->mayorid;
                            $factura->debito = 1;
                            $acreedorDao->setAccAsientoDetalle($factura);
                            $res->asientodetdebe = true;

                            $factura->debito = 0;
                            $factura->cuentaid = $factura->ctacxid;
                            $acreedorDao->setAccAsientoDetalle($factura);
                            $res->asientodethaber = true;

                            foreach ($factura->deuda as $d) {

                                if (floatval($d->abono) > 0) {

                                    $clideudaid = $contadorDao->getContador('CLI_CLIENTES_DEUDAS-ID-', $secuencia);

                                    if ($clideudaid == null)
                                        throw new Exception('No se Genero contador Cliente Deuda.');

                                    $d->id = $clideudaid;
                                    $d->docid = $contador->bancoid;
                                    $d->asientoid = $contador->asientoid;
                                    $d->numero = $contador->banconum;
                                    $d->detalle = $factura->detalle;
                                    $d->fecha = $factura->fecha;
                                    $d->tipo = $factura->tipo;
                                    $d->valor = round(floatval($d->abono), 2);
                                    $d->valor_base = round(floatval($d->abono) * floatval(CAMBIO), 2);
                                    $d->credito = 1;
                                    $d->vendedorid = $factura->cliente->vendid;
                                    $d->anula = 0;
                                    $d->saldo = 0;
                                    $d->creadopor = $this->user;
                                    $d->sucursalid = SUC;
                                    $d->pc = $factura->pc;
                                    $this->clienteDao->setClienteDeudas($d);
                                    $res->clientedeuda = true;
                                }

                            }

                            $kardexid = $contadorDao->getContador('BAN_BANCOS_CARDEX-ID-', $secuencia);
                            if ($kardexid == null)
                                throw new Exception('No se Genero contador Banco Kardex.');

                            $factura->kardexid = $kardexid;
                            $factura->debito = 1;
                            $bancoDao->setBancoKardex($factura);
                            $res->kardex = true;
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

                switch ($action) {
                    case 'deuda_clibuscar':
                        try {
                            $criterio = $_GET['criterio'];
                            $clientes = $this->clienteDao->getBuscar($criterio, 'WEB_CLI_DEUDA_BUSCAR_CLIENTES');

                        } catch (Exception $ex) {
                            die('{"error":' . $ex->getMessage() . '}');
                        }
                        echo json_encode($clientes);
                        break;

                    case 'fact_clibuscar':
                        try {

                            $criterio = $_GET['criterio'];
                            $clientes = $this->clienteDao->getBuscar($criterio);

                        } catch (Exception $ex) {
                            die('{"error":' . $ex->getMessage() . '}');
                        }
                        echo json_encode($clientes);
                        break;

                }
            }
        }
    }
}