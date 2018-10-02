<?php

class ClienteDao
{

    public function getListaGrupo()
    {
        $grupos = array();
        try {
            $cn = DataConection::getInstancia();
            $sql = "SELECT ID as id,CONCAT(Código,' ',Nombre)as nombre ";
            $sql .= 'FROM dbo.CLI_GRUPOS WHERE Anulado = 0';
            $stmp = $cn->prepare($sql);
            $stmp->execute();
            $grupos = $stmp->fetchAll(PDO::FETCH_OBJ);

        } catch (Exception $exc) {
            throw $exc;
        }
        return $grupos;
    }

    public function getCliente($codigo, $sp = 'WEB_VEN_FACT_SELECT_CLIENTE_COD')
    {
        $cliente = null;
        try {
            $cn = DataConection::getInstancia();
            $sql = $sp . ' :cod';
            $stmp = $cn->prepare($sql);
            $stmp->bindParam(':cod', $codigo, 2);
            $stmp->execute();
            if (($cliente = $stmp->fetch(PDO::FETCH_OBJ)) == TRUE) {
                return $cliente;
            }
        } catch (Exception $exc) {
            throw $exc;
        }
        return $cliente;
    }

    public function getBuscar($criterio, $sp = 'WEB_CLI_FACTURA_BUSCAR_CLIENTES')
    {
        $clientes = array();
        try {
            $cn = DataConection::getInstancia();
            $sql = $sp . ' :criterio';
            $stmp = $cn->prepare($sql);
            $stmp->bindParam(':criterio', $criterio, 2);
            $stmp->execute();
            $clientes = $stmp->fetchAll(PDO::FETCH_OBJ);

        } catch (Exception $exc) {
            throw $exc;
        }
        return $clientes;
    }

    public function getEstadoCuenta($cliente)
    {
        $deuda = null;
        try {
            $cn = DataConection::getInstancia();
            $sql = 'WEB_CLIENTE_ESTADO_CUENTA :cod,:inicio,:fin';
            $stmp = $cn->prepare($sql);
            $stmp->bindParam(':cod', $cliente->cod, 2);
            $stmp->bindParam(':inicio', $cliente->inicio, 2);
            $stmp->bindParam(':fin', $cliente->fin, 2);
            $stmp->execute();
            $deuda = $stmp->fetchAll(PDO::FETCH_OBJ);
        } catch (Exception $exc) {
            throw $exc;
        }
        return $deuda;
    }

    public function getDetalleDeuda($id)
    {
        $detalle = array();
        try {
            $cn = DataConection::getInstancia();
            $sql = 'WEB_CLI_CLIENTES_SELECT_DEUDAS :id';
            $stmp = $cn->prepare($sql);
            $stmp->bindParam(':id', $id, 2);
            $stmp->execute();
            $detalle = $stmp->fetchAll(PDO::FETCH_OBJ);
        } catch (Exception $exc) {
            throw $exc;
        }
        return $detalle;
    }

    public function setClienteDeudas($deuda)
    {
        try {
            $cn = DataConection::getInstancia();
            $sql = 'CLI_ClientesDeudas_Insert :id,:clienteid,:docid,:asientoid,:numero,:detalle,:valor,:valor_base,:fecha,';
            $sql .= ':vencimiento,:rubroid,:ctacxcid,:divisaid,:cambio,:saldo,';
            $sql .= ':tipo,:credito,:deudaid,:vendedorid,:anula,:divisionid,:creadopor,:sucursalid,:pc';

            $stmp = $cn->prepare($sql);
            $stmp->bindParam(':id', $deuda->id, 2);
            $stmp->bindParam(':clienteid', $deuda->clienteid, 2);
            $stmp->bindParam(':docid', $deuda->docid, 2);
            $stmp->bindParam(':asientoid', $deuda->asientoid, 2);
            $stmp->bindParam(':numero', $deuda->numero, 2);
            $stmp->bindParam(':detalle', $deuda->detalle, 2);
            $stmp->bindParam(':valor', $deuda->valor, 2);
            $stmp->bindParam(':valor_base', $deuda->valor_base, 2);
            $stmp->bindParam(':fecha', $deuda->fecha, 2);
            $stmp->bindParam(':vencimiento', $deuda->vencimiento, 2);
            $stmp->bindParam(':rubroid', $deuda->rubroid, 2);
            $stmp->bindParam(':ctacxcid', $deuda->ctacxid, 2);
            $stmp->bindParam(':divisaid', $deuda->divisaid, 2);
            $stmp->bindParam(':cambio', $deuda->cambio, 2);
            $stmp->bindParam(':saldo', $deuda->saldo, 2);
            $stmp->bindParam(':tipo', $deuda->tipo, 2);
            $stmp->bindParam(':credito', $deuda->credito, 2);
            $stmp->bindParam(':deudaid', $deuda->deudaid, 2);
            $stmp->bindParam(':vendedorid', $deuda->vendedorid, 2);
            $stmp->bindParam(':anula', $deuda->anula, 2);
            $stmp->bindParam(':divisionid', $deuda->divisionid, 2);
            $stmp->bindParam(':creadopor', $deuda->creadopor, 2);
            $stmp->bindParam(':sucursalid', $deuda->sucursalid, 2);
            $stmp->bindParam(':pc', $deuda->pc, 2);

            $stmp->execute();
            return $stmp->rowCount() > 0 ? true : false;
        } catch (Exception $exc) {
            throw $exc;
        }
        return false;
    }

    public function save($cliente, $insert = true)
    {

        try {
            $cn = DataConection::getInstancia();
            if ($insert) {
                $sql = 'CLI_Clientes_Insert ';
            } else {
                $sql = 'UPDATE TABLE SET param=:param WHERE id=:id;';
            }
            $sql.=':id,:codigo,:grupoid,:zonaid,:secuenciaid,:vendedorid,:divisionid,';
            $sql.=':clase,:terminoid,:formapago,:banco,:cuenta,:tasadescuento,:tasaincremento,';
            $sql.=':direccion,:telefono1,:telefono2,:telefono4,:ruc,:ciudad,:cupo,:cupofactura,';
            $sql.=':preciocobertura,:nombre,:foto,:fecha,:fechacredito,:firma1,:firma2,:email,';
            $sql.=':folder,:calificacion,:gnombre,:gcedula,:gempresa,:gzonaid,:gdirección,:gtelefono1,';
            $sql.=':gtelefono2,:gnota,:clienteid,:cuentaid,:diacorte,:ganaclub,:preciolista,:clasemodificable,';
            $sql.=':anulado,:cupones,:www,:creadopor,:nota,:sucursalid,:pcid,:relacionado';

            $stmp = $cn->prepare($sql);

            $stmp->bindParam(':id', $cliente->id, 2);
            $stmp->bindParam(':codigo', $cliente->codigo, 2);
            $stmp->bindParam(':grupoid', $cliente->grupoid, 2);
            $stmp->bindParam(':zonaid', $cliente->zonaid, 2);
            $stmp->bindParam(':secuenciaid', $cliente->secuenciaid, 2);
            $stmp->bindParam(':vendedorid', $cliente->vendedorid, 2);
            $stmp->bindParam(':divisionid', $cliente->divisionid, 2);
            $stmp->bindParam(':clase', $cliente->clase, 2);
            $stmp->bindParam(':terminoid', $cliente->terminoid, 2);
            $stmp->bindParam(':formapago', $cliente->formapago, 2);
            $stmp->bindParam(':banco', $cliente->banco, 2);
            $stmp->bindParam(':cuenta', $cliente->cuenta, 2);
            $stmp->bindParam(':tasadescuento', $cliente->tasadescuento, 2);
            $stmp->bindParam(':tasaincremento', $cliente->tasaincremento, 2);
            $stmp->bindParam(':direccion', $cliente->direccion, 2);
            $stmp->bindParam(':telefono1', $cliente->telefono1, 2);
            $stmp->bindParam(':telefono2', $cliente->telefono2, 2);
            $stmp->bindParam(':telefono4', $cliente->telefono4, 2);
            $stmp->bindParam(':ruc', $cliente->ruc, 2);
            $stmp->bindParam(':ciudad', $cliente->ciudad, 2);
            $stmp->bindParam(':cupo', $cliente->cupo, 2);
            $stmp->bindParam(':cupofactura', $cliente->cupofactura, 2);
            $stmp->bindParam(':preciocobertura', $cliente->preciocobertura, 2);
            $stmp->bindParam(':nombre', $cliente->nombre, 2);
            $stmp->bindParam(':foto', $cliente->foto, 2);
            $stmp->bindParam(':fecha', $cliente->fecha, 2);
            $stmp->bindParam(':fechacredito', $cliente->fechacredito, 2);
            $stmp->bindParam(':firma1', $cliente->firma1, 2);
            $stmp->bindParam(':firma2', $cliente->firma2, 2);
            $stmp->bindParam(':email', $cliente->email, 2);
            $stmp->bindParam(':folder', $cliente->folder, 2);
            $stmp->bindParam(':calificacion', $cliente->calificacion, 2);
            $stmp->bindParam(':gnombre', $cliente->gnombre, 2);
            $stmp->bindParam(':gcedula', $cliente->gcedula, 2);
            $stmp->bindParam(':gempresa', $cliente->gempresa, 2);
            $stmp->bindParam(':gzonaid', $cliente->gzonaid, 2);
            $stmp->bindParam(':gdireccion', $cliente->gdirección, 2);
            $stmp->bindParam(':gtelefono1', $cliente->gtelefono1, 2);
            $stmp->bindParam(':gtelefono2', $cliente->gtelefono2, 2);
            $stmp->bindParam(':gnota', $cliente->gnota, 2);
            $stmp->bindParam(':clienteid', $cliente->clienteid, 2);
            $stmp->bindParam(':cuentaid', $cliente->cuentaid, 2);
            $stmp->bindParam(':diacorte', $cliente->diacorte, 2);
            $stmp->bindParam(':ganaclub', $cliente->ganaclub, 2);
            $stmp->bindParam(':preciolista', $cliente->preciolista, 2);
            $stmp->bindParam(':clasemodificable', $cliente->clasemodificable, 2);
            $stmp->bindParam(':anulado', $cliente->anulado, 2);
            $stmp->bindParam(':cupones', $cliente->cupones, 2);
            $stmp->bindParam(':www', $cliente->www, 2);
            $stmp->bindParam(':creadopor', $cliente->creadopor, 2);
            $stmp->bindParam(':nota', $cliente->nota, 2);
            $stmp->bindParam(':sucursalid', $cliente->sucursalid, 2);
            $stmp->bindParam(':pcid', $cliente->pcid, 2);
            $stmp->bindParam(':relacionado', $cliente->relacionado, 2);

            $stmp->execute();

            return $stmp->rowCount() > 0 ? true : false;

        } catch (Exception $exc) {
            throw $exc;
        }
        return false;
    }

}