<?php

/**
 * Created by PhpStorm.
 * User: PC-Recaudacion19
 * Date: 19/9/2018
 * Time: 8:15
 */
class AcreedorDao
{

    public function setAsiento($asiento)
    {
        try {
            $cn = DataConection::getInstancia();
            $sql = 'ACC_Asientos_Insert :id,:numero,:docid,:fecha,';
            $sql .= ':tipo,:detalle,:nota,:divisionid,:creadopor,:sucursalid,:pc';

            $stmp = $cn->prepare($sql);
            $stmp->bindParam(':id', $asiento->asientoid, 2);
            $stmp->bindParam(':numero', $asiento->asientonum, 2);
            $stmp->bindParam(':docid', $asiento->docid, 2);
            $stmp->bindParam(':fecha', $asiento->fecha, 2);
            $stmp->bindParam(':tipo', $asiento->tipo, 2);
            $stmp->bindParam(':detalle', $asiento->detalle, 2);
            $stmp->bindParam(':nota', $asiento->nota, 2);
            $stmp->bindParam(':divisionid', $asiento->divisionid, 2);
            $stmp->bindParam(':creadopor', $asiento->creadopor, 2);
            $stmp->bindParam(':sucursalid', $asiento->sucursalid, 2);
            $stmp->bindParam(':pc', $asiento->pc, 2);

            $stmp->execute();
            return $stmp->rowCount() > 0 ? true : false;

        } catch (Exception $exc) {
            throw $exc;
        }
        return false;
    }

    public function setAccAsientoDetalle($asientodet)
    {
        try {
            $cn = DataConection::getInstancia();
            $sql = 'ACC_AsientosDT_Insert :asientoid,:cuentaid,:detalle,:debito,:divisaid,:cambio,';
            $sql .= ':valor,:valor_base,:creadopor,:sucursalid,:pc';

            $stmp = $cn->prepare($sql);
            $stmp->bindParam(':asientoid', $asientodet->asientoid, 2);
            $stmp->bindParam(':cuentaid', $asientodet->cuentaid, 2);
            $stmp->bindParam(':detalle', $asientodet->detalle, 2);
            $stmp->bindParam(':debito', $asientodet->debito, 2);
            $stmp->bindParam(':divisaid', $asientodet->divisaid, 2);
            $stmp->bindParam(':cambio', $asientodet->cambio, 2);
            $stmp->bindParam(':valor', $asientodet->valor, 2);
            $stmp->bindParam(':valor_base', $asientodet->valor_base, 2);
            $stmp->bindParam(':creadopor', $asientodet->creadopor, 2);
            $stmp->bindParam(':sucursalid', $asientodet->sucursalid, 2);
            $stmp->bindParam(':pc', $asientodet->pc, 2);

            $stmp->execute();
            return $stmp->rowCount() > 0 ? true : false;
        } catch (Exception $exc) {
            throw $exc;
        }
        return false;
    }

}