<?php

/**
 * Created by PhpStorm.
 * User: PC-Recaudacion19
 * Date: 24/09/2018
 * Time: 10:56
 */
class CtrBodega
{
    protected $bodegaDao = null;
    private $userid;
    private $user;

    //put your code here
    public function __construct()
    {
        require CONEXION;
        require MOD . '/dao/bodegaDao.php';
        $this->bodegaDao = new BodegaDao();

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

            }
        }
    }

    public function getLista()
    {
        return $this->bodegaDao->getListaBodega();
    }
}