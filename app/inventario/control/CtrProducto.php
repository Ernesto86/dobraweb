<?php
/**
 * Created by PhpStorm.
 * User: Administrador
 * Date: 14/09/2018
 * Time: 17:19
 */

class CtrProducto
{

    protected $productoDao = null;
    private $userid;
    private $user;

    //put your code here
    public function __construct()
    {
        require CONEXION;
        require MOD . '/dao/ProductoDao.php';
        $this->productoDao = new ProductoDao();

        if (session_id() == '') {
            session_start();
        }
        $this->userid = $_SESSION['us_id'];
        $this->user = $_SESSION['us_cuenta'];
    }

    public function view()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {


        }else {

            if (isset($_GET['action'])) {

                $action = $_GET['action'];
                $res = new stdClass;
                $res->resp = false;

                switch ($action) {
                    case 'buscar':

                        $productos = array();
                        try {

                            $criterio = new stdClass;
                            $criterio->q = $_GET['criterio'];
                            $criterio->bodega = $_GET['bodega'];

                            $productos = $this->productoDao->getBuscar($criterio);

                        } catch (Exception $ex) {}
                        echo json_encode($productos);
                        break;

                    case 'producto':

                        try {

                            $critprod = new stdClass;
                            $critprod->codigo = $_GET['codigo'];
                            $critprod->bodega = $_GET['bodega'];

                            $producto = $this->productoDao->getProducto($critprod);

                            if ($producto != null) {
                                $res->resp = true;
                                $res->producto = $producto;
                            } else {
                                $res->error = 'No existe el Código del Ingresado';
                            }
                        } catch (Exception $ex) {
                            $res->error = $ex->getMessage();
                        }
                        echo json_encode($res);
                        break;

                }
            }
        }
    }
}