<?php
/**
 * Created by PhpStorm.
 * User: Administrador
 * Date: 30/09/2018
 * Time: 19:31
 */

class CtrGrupo
{
    protected $grupoDao = null;

    //put your code here
    public function __construct()
    {

        require CONEXION;
        require MOD . '/dao/ClienteDao.php';
        $this->grupoDao = new ClienteDao();
    }

    public function view()
    {

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {

            if (isset($_POST['action'])) {

                $action = $_POST['action'];
                $data = new stdClass;
                $data->resp = false;


                switch ($action) {

                    case '':

                        try {

                            $generic = new stdClass;


                        } catch (Exception $ex) {
                            $data->error = $ex->getMessage();
                        }
                        echo json_encode($data);
                        break;

                    default:
                        break;

                }
            }


        } else {

            if (isset($_GET['action'])) {

                $action = $_GET['action'];
                $data = new stdClass;
                $data->resp = false;

                switch ($action) {

                    case '':

                        try {

                            $generic = new stdClass;


                        } catch (Exception $ex) {
                            $data->error = $ex->getMessage();
                        }
                        echo json_encode($data);
                        break;

                    default:
                        break;

                }
            }
        }
    }

    public function listaGrupos()
    {
        return $this->grupoDao->getListaGrupo();
    }

    public function listaZonas($tipo)
    {
        require DROOT . 'sistema/dao/ZonaDao.php';
        $zonaDao = new ZonaDao();
        return $zonaDao->getListZonas($tipo);
    }

    public function listaParametros($criterio)
    {
        require DROOT . 'sistema/dao/ParametroDao.php';
        $parametrosDao = new ParametroDao();
        return $parametrosDao->getListaCodTipo($criterio);
    }
}