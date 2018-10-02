<?php

/**
 * Created by PhpStorm.
 * User: Administrador
 * Date: 30/09/2018
 * Time: 20:25
 */
class ZonaDao
{

    function __construct()
    {
    }

    public function getListZonas($tipo)
    {
        $zonas = null;
        try {
            $cn = DataConection::getInstancia();
            $sql = "SELECT ID as id,Código as nombre ";
            $sql.='FROM dbo.SIS_ZONAS WHERE Anulado=0 AND Tipo=:tipo';
            $stmp = $cn->prepare($sql);
            $stmp->bindParam(':tipo', $tipo, 2);
            $stmp->execute();
            $zonas = $stmp->fetchAll(PDO::FETCH_OBJ);

        } catch (Exception $exc) {
            throw $exc;
        }
        return $zonas;
    }
}