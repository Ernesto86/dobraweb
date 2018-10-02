<?php

/**
 * Created by PhpStorm.
 * User: Administrador
 * Date: 11/09/2018
 * Time: 15:53
 */
class CtrUsuario
{

    protected $usuarioDao = null;
    //put your code here
    public function __construct()
    {
        require CONEXION;
        require MOD . '/dao/UsuarioDao.php';
        $this->usuarioDao = new UsuarioDao();
    }

    public function view()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {

            if (isset($_POST['action'])) {

                $action = $_POST['action'];
                $res = new stdClass;
                $res->resp = false;

                switch ($action) {

                    case 'login':
                        try {

                            if(empty($_POST['norobot']))
                                throw new Exception('Acceso denegado.');


                            $cuenta = strval($_POST['cuenta']);
                            $password = strval($_POST['password']);

                            $usuario = $this->usuarioDao->loginSession($cuenta, $password);

                            if (is_object($usuario) and $usuario != null) {

                                if (session_id() == '') {
                                    session_start();
                                }
                                $_SESSION['us_id'] = intval($usuario->id);
                                $_SESSION['us_idpers'] = intval($usuario->idpers);
                                $_SESSION['us_idtipo'] = intval($usuario->idtip);
                                $_SESSION['us_tipuser'] = strtolower($usuario->tipuser);
                                $_SESSION['us_cuenta'] = ucwords($cuenta);
                                $res->usuario = $usuario;
                                $res->resp = true;
                            }else{
                                $res->error = 'Las credenciales ingresadas son incorrectas.';
                            }
                        }catch (Exception $ex){
                            $res->error = $ex->getMessage();
                        }
                        echo json_encode($res);
                        break;
                }
            }
        }
    }
}