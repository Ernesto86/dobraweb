<?php

/**
 * Created by PhpStorm.
 * User: PC-Recaudacion19
 * Date: 24/09/2018
 * Time: 10:49
 */
class BodegaDao
{

    public function getListaBodega()
    {
        $bodegas = array();
        try {
            $cn = DataConection::getInstancia();
            $sql = "SELECT ID as id,(Código + ':' + Nombre) as nombre ";
            $sql.="FROM INV_BODEGAS WITH(NOLOCK) WHERE Anulado = 0 ORDER BY Nombre";
            $stmp = $cn->prepare($sql);
            $stmp->execute();
            $bodegas = $stmp->fetchAll(PDO::FETCH_OBJ);

        } catch (Exception $exc) {
            throw $exc;
        }
        return $bodegas;
    }

}