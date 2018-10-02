<?php

/**
 * Created by PhpStorm.
 * User: PC-Recaudacion19
 * Date: 22/09/2018
 * Time: 10:49
 */
class CuentaDao
{
    public function getRubroFactura($bancoid)
    {
        $cuenta = null;
        try {

            $cn = DataConection::getInstancia();
            $sql = 'WEB_CLI_SELECT_RUBRO_FACT_ID';
            $stmp = $cn->prepare($sql);

            $stmp->execute();
            if (($cuenta = $stmp->fetch(PDO::FETCH_OBJ)) == TRUE) {
                return $cuenta;
            }

        } catch (Exception $exc) {
            throw $exc;
        }
        return $cuenta;
    }
}