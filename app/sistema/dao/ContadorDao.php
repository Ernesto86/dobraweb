<?php

/**
 * Created by PhpStorm.
 * User: Administrador
 * Date: 23/09/2018
 * Time: 10:01
 */
class ContadorDao
{

    public function getContador($codigo, $secuencia, $sp = 'SIS_GetNextID')
    {
        $contador = null;
        $cod = $codigo . $secuencia;
        try {
            $cn = DataConection::getInstancia();
            $sql = $sp . ' :cod';
            $stmp = $cn->prepare($sql);
            $stmp->bindParam(':cod', $cod, 2);
            $stmp->execute();
            if (($contador = $stmp->fetch(PDO::FETCH_OBJ)) == TRUE) {
                return $secuencia . str_pad(trim($contador->NextID), 10 - strlen($secuencia), '0', STR_PAD_LEFT);
            }
        } catch (Exception $exc) {
            throw $exc;
        }
        return $contador;
    }

    public function getContadores($secuencia, $sp = '')
    {
        $contadores = null;
        try {
            $cn = DataConection::getInstancia();
            $sql = $sp . ' :sucdivision';
            $stmp = $cn->prepare($sql);

            $stmp->bindParam(':sucdivision', $secuencia, 2);
            $stmp->execute();
            if (($contadores = $stmp->fetch(PDO::FETCH_OBJ)) == TRUE) {
                return $contadores;
            }

        } catch (Exception $exc) {
            throw $exc;
        }
        return $contadores;
    }

}