<?php

/**
 * Created by PhpStorm.
 * User: PC-Recaudacion19
 * Date: 19/9/2018
 * Time: 11:14
 */
class DivisaDao
{

    function __construct()
    {
    }

    public function getDivisa($cod='USD')
    {
        $divisa = null;
        try {

            $cn = DataConection::getInstancia();
            $sql = 'SELECT TOP 1 D.ID as id,D.Código as cod,D.Nombre as nombre,D.Cambio as cambio,';
            $sql.="D.Símbolo as simbolo FROM dbo.SIS_DIVISAS D WHERE D.Código =:cod";
            $stmp = $cn->prepare($sql);
            $stmp->bindParam(':cod', $cod, 2);
            $stmp->execute();
            if (($divisa = $stmp->fetch(PDO::FETCH_OBJ)) == TRUE) {
                return $divisa;
            }

        } catch (Exception $exc) {
            throw $exc;
        }
        return $divisa;
    }

}