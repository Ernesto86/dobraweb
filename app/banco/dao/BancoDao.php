<?php

/**
 * Created by PhpStorm.
 * User: Administrador
 * Date: 23/09/2018
 * Time: 10:20
 */
class BancoDao
{

    public function setBanco($banco)
    {
        try {
            $cn = DataConection::getInstancia();
            $sql = 'BAN_Ingresos_Insert :id,:numero,:asientoid,:bancoid,:deudorid,';
            $sql .= ':fecha,:tipo,:detalle,:valor,:descuento,:financiero,:rfir,:rfiva,';
            $sql .= ':faltante,:sobrante,:valor_base,:nota,:divisionid,:divisaid,:cambio,:creadopor,';
            $sql .= ':sucursalid,:cajaid,:pc';

            $stmp = $cn->prepare($sql);
            $stmp->bindParam(':id', $banco->bancopk, 2);
            $stmp->bindParam(':numero', $banco->banconum, 2);
            $stmp->bindParam(':asientoid', $banco->asientoid, 2);
            $stmp->bindParam(':bancoid', $banco->bancoid, 2);
            $stmp->bindParam(':deudorid', $banco->deudorid, 2);
            $stmp->bindParam(':fecha', $banco->fecha, 2);
            $stmp->bindParam(':tipo', $banco->tipo, 2);
            $stmp->bindParam(':detalle', $banco->detalle, 2);
            $stmp->bindParam(':valor', $banco->valor, 2);
            $stmp->bindParam(':descuento', $banco->descuento, 2);
            $stmp->bindParam(':financiero', $banco->financiero, 2);
            $stmp->bindParam(':rfir', $banco->rfir, 2);
            $stmp->bindParam(':rfiva', $banco->rfiva, 2);
            $stmp->bindParam(':faltante', $banco->faltante, 2);
            $stmp->bindParam(':sobrante', $banco->sobrante, 2);
            $stmp->bindParam(':valor_base', $banco->valor_base, 2);
            $stmp->bindParam(':nota', $banco->nota, 2);
            $stmp->bindParam(':divisionid', $banco->divisionid, 2);
            $stmp->bindParam(':divisaid', $banco->divisaid, 2);
            $stmp->bindParam(':cambio', $banco->cambio, 2);
            $stmp->bindParam(':creadopor', $banco->creadopor, 2);
            $stmp->bindParam(':sucursalid', $banco->sucursalid, 2);
            $stmp->bindParam(':cajaid', $banco->cajaid, 2);
            $stmp->bindParam(':pc', $banco->pc, 2);
            $stmp->execute();
            return $stmp->rowCount() > 0 ? true : false;
        } catch (Exception $exc) {
            throw $exc;
        }
        return false;
    }

    public function setBancoDetalle($bancodet)
    {
        try {
            $cn = DataConection::getInstancia();
            $sql = 'BAN_IngresosDT_Insert :id,:ingresoid,:tipo,:numero,:cuenta,';
            $sql .= ':banco,:girador,:fecha,:divisaid,:cambio,:valor,:valor_base,:creadopor,';
            $sql .= ':sucursalid,:pc';

            $stmp = $cn->prepare($sql);
            $stmp->bindParam(':id', $bancodet->bancodetid, 2);
            $stmp->bindParam(':ingresoid', $bancodet->ingresoid, 2);
            $stmp->bindParam(':tipo', $bancodet->tipo, 2);
            $stmp->bindParam(':numero', $bancodet->numero, 2);
            $stmp->bindParam(':cuenta', $bancodet->cuenta, 2);
            $stmp->bindParam(':banco', $bancodet->banco, 2);
            $stmp->bindParam(':girador', $bancodet->girador, 2);
            $stmp->bindParam(':fecha', $bancodet->fecha, 2);
            $stmp->bindParam(':divisaid', $bancodet->divisaid, 2);
            $stmp->bindParam(':cambio', $bancodet->cambio, 2);
            $stmp->bindParam(':valor', $bancodet->valor, 2);
            $stmp->bindParam(':valor_base', $bancodet->valor_base, 2);
            $stmp->bindParam(':creadopor', $bancodet->creadopor, 2);
            $stmp->bindParam(':sucursalid', $bancodet->sucursalid, 2);
            $stmp->bindParam(':pc', $bancodet->pc, 2);

            $stmp->execute();
            return $stmp->rowCount() > 0 ? true : false;
        } catch (Exception $exc) {
            throw $exc;
        }
        return false;
    }

    public function getCuentaBanco($bancoid)
    {
        $cuenta = null;
        try {

            $cn = DataConection::getInstancia();
            $sql = 'SELECT CtaMayorID as mayorid,Nombre as nombre,Clase as clase,DivisaID as divisaid FROM dbo.BAN_BANCOS WHERE ID=:id';
            $stmp = $cn->prepare($sql);
            $stmp->bindParam(':id', $bancoid, 2);

            $stmp->execute();
            if (($cuenta = $stmp->fetch(PDO::FETCH_OBJ)) == TRUE) {
                return $cuenta;
            }

        } catch (Exception $exc) {
            throw $exc;
        }
        return $cuenta;
    }

    public function setBancoKardex($kerdex)
    {
        try {
            $cn = DataConection::getInstancia();
            $sql = 'BAN_BancosCardex_Insert :id,:bancoid,:docid,:numero,:fecha,';
            $sql .= ':tipo,:cheque,:fechacheque,:beneficiario,:detalle,:divisaid,';
            $sql .= ':cambio,:debito,:valor,:valor_base,:divisionid,:creadopor,:sucursalid,:pc';

            $stmp = $cn->prepare($sql);
            $stmp->bindParam(':id', $kerdex->kardexid, 2);
            $stmp->bindParam(':bancoid', $kerdex->bancoid, 2);
            $stmp->bindParam(':docid', $kerdex->docid, 2);
            $stmp->bindParam(':numero', $kerdex->banconum, 2);
            $stmp->bindParam(':fecha', $kerdex->fecha, 2);
            $stmp->bindParam(':tipo', $kerdex->tipo, 2);
            $stmp->bindParam(':cheque', $kerdex->cheque, 2);
            $stmp->bindParam(':fechacheque', $kerdex->fechacheque, 2);
            $stmp->bindParam(':beneficiario', $kerdex->beneficiario, 2);
            $stmp->bindParam(':detalle', $kerdex->detalle, 2);
            $stmp->bindParam(':divisaid', $kerdex->divisaid, 2);
            $stmp->bindParam(':cambio', $kerdex->cambio, 2);
            $stmp->bindParam(':debito', $kerdex->debito, 2);
            $stmp->bindParam(':valor', $kerdex->valor, 2);
            $stmp->bindParam(':valor_base', $kerdex->valor_base, 2);
            $stmp->bindParam(':divisionid', $kerdex->divisionid, 2);
            $stmp->bindParam(':creadopor', $kerdex->creadopor, 2);
            $stmp->bindParam(':sucursalid', $kerdex->sucursalid, 2);
            $stmp->bindParam(':pc', $kerdex->pc, 2);

            $stmp->execute();
            return $stmp->rowCount() > 0 ? true : false;
        } catch (Exception $exc) {
            throw $exc;
        }
        return false;
    }

    public function setBancoIngresoDeudas($bandeuda)
    {
        try {
            $cn = DataConection::getInstancia();
            $sql = 'BAN_IngresosDeudas_Insert :ingresoid,:deudaid,:divisaid,:cambiodia,';
            $sql .= ':saldo,:valor,:difcambio,:fecha,:vencimiento,:tipo,:numero,:detalle,';
            $sql .= ':credito,:rubroid,:ctacxid,:cambio,:divisionid,:creadopor,:sucursalid,:pc';

            $stmp = $cn->prepare($sql);
            $stmp->bindParam(':ingresoid', $bandeuda->ingresoid, 2);
            $stmp->bindParam(':deudaid', $bandeuda->deudaid, 2);
            $stmp->bindParam(':divisaid', $bandeuda->divisaid, 2);
            $stmp->bindParam(':cambiodia', $bandeuda->cambiodia, 2);
            $stmp->bindParam(':saldo', $bandeuda->saldo, 2);
            $stmp->bindParam(':valor', $bandeuda->abono, 2);
            $stmp->bindParam(':difcambio', $bandeuda->difcambio, 2);
            $stmp->bindParam(':fecha', $bandeuda->fecha, 2);
            $stmp->bindParam(':vencimiento', $bandeuda->vencimiento, 2);
            $stmp->bindParam(':tipo', $bandeuda->tipo, 2);
            $stmp->bindParam(':numero', $bandeuda->numero, 2);
            $stmp->bindParam(':detalle', $bandeuda->detalle, 2);
            $stmp->bindParam(':credito', $bandeuda->credito, 2);
            $stmp->bindParam(':rubroid', $bandeuda->rubroid, 2);
            $stmp->bindParam(':ctacxid', $bandeuda->ctacxid, 2);
            $stmp->bindParam(':cambio', $bandeuda->cambio, 2);
            $stmp->bindParam(':divisionid', $bandeuda->divisionid, 2);
            $stmp->bindParam(':creadopor', $bandeuda->creadopor, 2);
            $stmp->bindParam(':sucursalid', $bandeuda->sucursalid, 2);
            $stmp->bindParam(':pc', $bandeuda->pc, 2);
            $stmp->execute();
            return $stmp->rowCount() > 0 ? true : false;
        } catch (Exception $exc) {
            throw $exc;
        }
        return false;
    }

}