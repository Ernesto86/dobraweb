<?php

/**
 * Created by PhpStorm.
 * User: Administrador
 * Date: 14/09/2018
 * Time: 17:18
 */
class ProductoDao
{


    public function getBuscar($criterio)
    {
        $productos = array();
        try {
            $cn = DataConection::getInstancia();
            $sql = 'WEB_INV_PRODUCTO_STOCK_BUSCAR :criterio,:bodega';
            $stmp = $cn->prepare($sql);
            $stmp->bindParam(':criterio', $criterio->q, 2);
            $stmp->bindParam(':bodega', $criterio->bodega, 2);
            $stmp->execute();
            $productos = $stmp->fetchAll(PDO::FETCH_OBJ);

        } catch (Exception $exc) {
            throw $exc;
        }
        return $productos;
    }

    public function getProducto($prod)
    {
        $producto = null;
        try {
            $cn = DataConection::getInstancia();
            $sql = 'WEB_INV_PRODUCTO_STOCK_SELECT_COD :cod,:bodega';
            $stmp = $cn->prepare($sql);
            $stmp->bindParam(':cod', $prod->codigo, 2);
            $stmp->bindParam(':bodega', $prod->bodega, 2);
            $stmp->execute();
            if (($producto = $stmp->fetch(PDO::FETCH_OBJ)) == TRUE) {
                return $producto;
            }

        } catch (Exception $exc) {
            throw $exc;
        }
        return $producto;
    }

    public function setInvProductoKardex($item)
    {
        try {
            $cn = DataConection::getInstancia();
            $sql = 'INV_ProductosCardex_Insert ';
            $sql .= ':productoid,:bodegaid,:asientoid,:docid,:numero,:fecha,:tipo,:detalle,:egreso,:cantidad,';
            $sql .= ':costo,:divisaid,:cambio,:divisionid,:creadopor,:sucursalid,:pc';

            $stmp = $cn->prepare($sql);
            $stmp->bindParam(':productoid',$item->productoid,2);
            $stmp->bindParam(':bodegaid',$item->bodegaid,2);
            $stmp->bindParam(':asientoid',$item->asientoid,2);
            $stmp->bindParam(':docid',$item->docid,2);
            $stmp->bindParam(':numero',$item->numero,2);
            $stmp->bindParam(':fecha',$item->fecha,2);
            $stmp->bindParam(':tipo',$item->tipo,2);
            $stmp->bindParam(':detalle',$item->detalle,2);
            $stmp->bindParam(':egreso',$item->egreso,2);
            $stmp->bindParam(':cantidad',$item->cantidad,2);
            $stmp->bindParam(':costo',$item->costo,2);
            $stmp->bindParam(':divisaid',$item->divisaid,2);
            $stmp->bindParam(':cambio',$item->cambio,2);
            $stmp->bindParam(':divisionid',$item->divisionid,2);
            $stmp->bindParam(':creadopor',$item->creadopor,2);
            $stmp->bindParam(':sucursalid',$item->sucursalid,2);
            $stmp->bindParam(':pc',$item->pc,2);
            $stmp->execute();

            return $stmp->rowCount() > 0 ? true : false;

        } catch (Exception $exc) {
            throw $exc;
        }
        return false;
    }
}