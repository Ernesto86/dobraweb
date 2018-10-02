<?php
class UsuarioDao
{
    //put your code here
    public function __construct()
    {
        ;
    }

    public function loginSession($cuenta, $password)
    {
        $usuario = null;
        try {
            $cn = DataConection::getInstancia('mysql');
            $sql = 'SELECT U.usu_id id,U.tip_user_id idtip,TU.tip_user_desc tipuser,';
            $sql.='U.per_id idpers, P.per_dir_img img, U.grp_id caj ';
            $sql.='FROM tbl_usuario U INNER JOIN tbl_tipo_usuario TU ON U.tip_user_id = TU.tip_user_id ';
            $sql.='INNER JOIN tbl_transaccion TR ON(TU.tip_user_id = TR.tip_user_id AND tran_ingresar=1) ';
            $sql.='INNER JOIN tbl_personal P ON (U.per_id = P.per_id) ';
            $sql.="WHERE U.usu_estado = 'A' AND U.usu_cuenta =:cuenta AND usu_clave=:password LIMIT 1;";

            $stmp = $cn->prepare($sql);
            $password = hash('sha256', SAL.$password);
            $stmp->bindParam(':cuenta', $cuenta, 2);
            $stmp->bindParam(':password', $password, 2);
            $stmp->execute();
            if (($usuario = $stmp->fetch(PDO::FETCH_OBJ)) == true) {
                return $usuario;
            }
        } catch (Exception $exc) {
            throw $exc;
        }
        return $usuario;
    }

}