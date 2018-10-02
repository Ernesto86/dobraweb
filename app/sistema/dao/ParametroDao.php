<?php

/**
 * Created by PhpStorm.
 * User: Administrador
 * Date: 30/09/2018
 * Time: 21:03
 */
class ParametroDao
{
    public function getListaCodTipo($criterio)
    {
        $lista = array();
        try {
            $cn = DataConection::getInstancia();
            $sql = 'SELECT ID as id,Código as cod,Nombre as nombre,';
            $sql.='Valor as valor FROM dbo.SIS_PARAMETROS WHERE Anulado = 0 AND ';
            $sql.='PadreID=(SELECT ID FROM dbo.SIS_PARAMETROS WHERE Código=:cod)';
            /*$sql.="AND (CASE :tipo WHEN '' THEN '' ELSE Tipo END)=:tipo ";*/
            $stmp = $cn->prepare($sql);
            $stmp->bindParam(':cod', $criterio->cod, 2);
            /*$stmp->bindParam(':tipo1', $criterio->tipo, 2);*/
            $stmp->execute();
            $lista = $stmp->fetchAll(PDO::FETCH_OBJ);

        } catch (Exception $exc) {
            throw $exc;
        }
        return $lista;
    }

}