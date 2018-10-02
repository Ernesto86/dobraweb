<?php

/**
 * Created by PhpStorm.
 * User: PC-Recaudacion19
 * Date: 19/9/2018
 * Time: 11:18
 */
class DivisionDao
{
    function __construct()
    {
    }

    public function getDivision($cod = '01')
    {
        $division = null;
        try {

            $cn = DataConection::getInstancia();
            $sql = 'SELECT TOP 1 D.ID as id,D.Código as cod,D.Nombre as nombre ';
            $sql.="FROM dbo.SIS_DIVISIONES D WHERE Código =:cod";
            $stmp = $cn->prepare($sql);
            $stmp->bindParam(':cod', $cod, 2);
            $stmp->execute();
            if (($division = $stmp->fetch(PDO::FETCH_OBJ)) == TRUE) {
                return $division;
            }

        } catch (Exception $exc) {
            throw $exc;
        }
        return $division;
    }

}