<?php

/**
 * Created by PhpStorm.
 * User: PC-Recaudacion19
 * Date: 15/9/2018
 * Time: 10:13
 */
class FacturaDao
{

    public function setVentaFactura($factura)
    {
        try {
            $cn = DataConection::getInstancia();

            $sql = 'VEN_Facturas_Insert ';
            $sql .= ':id,:ordenid,:clienteid,:empleadoid,:detalle,';
            $sql .= ':ruc,:asientoid,:vendedorid,:cajaid,:secuencia,:terminoid,:divisionid,';
            $sql .= ':contado,:numero,:fecha,:entregado,:tipo,:divisaid,:cambio,:subtotal,:descuento,:impuesto,';
            $sql .= ':total,:efectivo,:cheque,:tarjeta,:credito,:cupones,:banco,:fecha_cheque,:nocuenta,';
            $sql .= ':nocheque,:nombre_tarjeta,:vence,:notarjeta,:autorizacion,:bodegaid,:transporte,';
            $sql .= ':nota,:nota2,:numero_guia,:numero_oc,:fecha_oc,:tipofactura,:fact_preimpresa,:creadopor,:sucursalid,:pc';

            $stmp = $cn->prepare($sql);
            $stmp->bindParam(':id', $factura->id, 2);
            $stmp->bindParam(':ordenid', $factura->ordenid, 2);
            $stmp->bindParam(':clienteid', $factura->cliente->id, 2);
            $stmp->bindParam(':empleadoid', $factura->empleadoid, 2);
            $stmp->bindParam(':detalle', $factura->detalle, 2);
            $stmp->bindParam(':ruc', $factura->cliente->ruc, 2);
            $stmp->bindParam(':asientoid', $factura->asientoid, 2);
            $stmp->bindParam(':vendedorid', $factura->cliente->vendid, 2);
            $stmp->bindParam(':cajaid', $factura->cajaid, 2);
            $stmp->bindParam(':secuencia', $factura->secuencia, 2);
            $stmp->bindParam(':terminoid', $factura->terminoid, 2);
            $stmp->bindParam(':divisionid', $factura->divisionid, 2);
            $stmp->bindParam(':contado', $factura->contado, 1);
            $stmp->bindParam(':numero', $factura->numero, 2);
            $stmp->bindParam(':fecha', $factura->fecha, 2);
            $stmp->bindParam(':entregado', $factura->fecha, 2);
            $stmp->bindParam(':tipo', $factura->tipo, 2);
            $stmp->bindParam(':divisaid', $factura->divisaid, 2);
            $stmp->bindParam(':cambio', $factura->cambio, 2);
            $stmp->bindParam(':subtotal', $factura->subtotal, 2);
            $stmp->bindParam(':descuento', $factura->descuento, 2);
            $stmp->bindParam(':impuesto', $factura->impuesto, 2);
            $stmp->bindParam(':total', $factura->total, 2);
            $stmp->bindParam(':efectivo', $factura->efectivo, 2);
            $stmp->bindParam(':cheque', $factura->cheque, 2);
            $stmp->bindParam(':tarjeta', $factura->tarjeta, 2);
            $stmp->bindParam(':credito', $factura->credito, 2);
            $stmp->bindParam(':cupones', $factura->cupones, 2);
            $stmp->bindParam(':banco', $factura->banco, 2);
            $stmp->bindParam(':fecha_cheque', $factura->fecha_cheque, 2);
            $stmp->bindParam(':nocuenta', $factura->nocuenta, 2);
            $stmp->bindParam(':nocheque', $factura->nocheque, 2);
            $stmp->bindParam(':nombre_tarjeta', $factura->nombre_tarjeta, 2);
            $stmp->bindParam(':vence', $factura->vence, 2);
            $stmp->bindParam(':notarjeta', $factura->notarjeta, 2);
            $stmp->bindParam(':autorizacion', $factura->autorizacion, 2);
            $stmp->bindParam(':bodegaid', $factura->bodegaid, 2);
            $stmp->bindParam(':transporte', $factura->transporte, 2);
            $stmp->bindParam(':nota', $factura->nota, 2);
            $stmp->bindParam(':nota2', $factura->nota2, 2);
            $stmp->bindParam(':numero_guia', $factura->numero_guia, 2);
            $stmp->bindParam(':numero_oc', $factura->numero_oc, 2);
            $stmp->bindParam(':fecha_oc', $factura->fecha_oc, 2);
            $stmp->bindParam(':tipofactura', $factura->tipofactura, 2);
            $stmp->bindParam(':fact_preimpresa', $factura->fact_preimpresa, 2);
            $stmp->bindParam(':creadopor', $factura->creadopor, 2);
            $stmp->bindParam(':sucursalid', $factura->sucursalid, 2);
            $stmp->bindParam(':pc', $factura->pc, 2);

            $stmp->execute();
            return $stmp->rowCount() > 0 ? true : false;

        } catch (Exception $exc) {
            throw $exc;
        }
        return false;
    }

    public function setVentaFacturaDetalle($item)
    {
        try {
            $cn = DataConection::getInstancia();
            $sql = 'VEN_FacturasDT_Insert ';
            $sql .= ':id,:facturaid,:ordendtid,:productoid,:bodegaid,:cantidad,:diferencia,';
            $sql .= ':precio,:costo,:subtotal,:tasadescuento,:descuento,:tasaimpuesto,:impuesto,';
            $sql .= ':total,:cupones,:valorcupon,:clase,:empaque,:precioname,:factor,:embarque,';
            $sql .= ':detalle_ex,:creadopor,:sucursalid,:pc';

            $stmp = $cn->prepare($sql);
            $stmp->bindParam(':id',$item->id,2);
            $stmp->bindParam(':facturaid',$item->facturaid,2);
            $stmp->bindParam(':ordendtid',$item->ordendtid,2);
            $stmp->bindParam(':productoid',$item->productoid,2);
            $stmp->bindParam(':bodegaid',$item->bodegaid,2);
            $stmp->bindParam(':cantidad',$item->cantidad,2);
            $stmp->bindParam(':diferencia',$item->diferencia,2);
            $stmp->bindParam(':precio',$item->precio,2);
            $stmp->bindParam(':costo',$item->costo,2);
            $stmp->bindParam(':subtotal',$item->subtotal,2);
            $stmp->bindParam(':tasadescuento',$item->tasadescuento,2);
            $stmp->bindParam(':descuento',$item->descuento,2);
            $stmp->bindParam(':tasaimpuesto',$item->tasaimpuesto,2);
            $stmp->bindParam(':impuesto',$item->impuesto,2);
            $stmp->bindParam(':total',$item->total,2);
            $stmp->bindParam(':cupones',$item->cupones,2);
            $stmp->bindParam(':valorcupon',$item->valorcupon,2);
            $stmp->bindParam(':clase',$item->clase,2);
            $stmp->bindParam(':empaque',$item->empaque,2);
            $stmp->bindParam(':precioname',$item->precioname,2);
            $stmp->bindParam(':factor',$item->factor,2);
            $stmp->bindParam(':embarque',$item->embarque,2);
            $stmp->bindParam(':detalle_ex',$item->detalle_ex,2);
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



    public function getCuentaDescuentoFactura($id)
    {
        try {
            $cn = DataConection::getInstancia();
            $sql = 'SELECT PRD.CtaDescuentoID as ctadescuentoid,SUM(VFD.Cantidad) as cantidad,';
            $sql.='SUM(VFD.Descuento) as valor ';
            $sql.='FROM dbo.VEN_FACTURAS_DT VFD INNER JOIN dbo.VEN_FACTURAS VF ON VFD.FacturaID = VF.ID ';
            $sql.='INNER JOIN dbo.INV_PRODUCTOS PRD ON VFD.ProductoID = PRD.ID ';
            $sql.='WHERE VF.ID =:docid AND VFD.Cantidad > 0 GROUP BY PRD.CtaDescuentoID,VFD.Cantidad,VFD.Descuento';

            $stmp = $cn->prepare($sql);
            $stmp->bindParam(':docid', $id, 2);

            $stmp->execute();
            $cuentas = $stmp->fetchAll(PDO::FETCH_OBJ);

        } catch (Exception $exc) {
            throw $exc;
        }
        return $cuentas;
    }

    public function getCuentaCostoInvProducto($id)
    {
        try {
            $cn = DataConection::getInstancia();
            $sql = 'SELECT PRD.CtaCostosID as ctacostoid,SUM(VFD.Cantidad) as cantidad,';
            $sql.='SUM(VFD.Cantidad * PRD.CostoCompra) as valor,';
            $sql.='PRD.CostoCompra as costocompra ';
            $sql.='FROM dbo.VEN_FACTURAS_DT VFD INNER JOIN dbo.VEN_FACTURAS VF ON VFD.FacturaID = VF.ID ';
            $sql.='INNER JOIN dbo.INV_PRODUCTOS PRD ON VFD.ProductoID = PRD.ID ';
            $sql.="WHERE VF.ID =:docid 	AND VFD.Cantidad > 0 ";
            $sql.='GROUP BY PRD.CtaCostosID,VFD.Cantidad,PRD.CostoCompra';

            $stmp = $cn->prepare($sql);
            $stmp->bindParam(':docid', $id, 2);

            $stmp->execute();
            $cuentas = $stmp->fetchAll(PDO::FETCH_OBJ);

        } catch (Exception $exc) {
            throw $exc;
        }
        return $cuentas;
    }

    public function getCuentaMayorInvProducto($id)
    {
        try {
            $cn = DataConection::getInstancia();
            $sql = 'SELECT PRD.CtaMayorID as ctamayorid,SUM(VFD.Cantidad) as cantidad,';
            $sql.='SUM(VFD.Cantidad * PRD.CostoCompra) as valor ';
            $sql.='FROM dbo.VEN_FACTURAS_DT VFD INNER JOIN dbo.VEN_FACTURAS VF ON VFD.FacturaID = VF.ID ';
            $sql.='INNER JOIN dbo.INV_PRODUCTOS PRD ON VFD.ProductoID = PRD.ID ';
            $sql.='WHERE VF.ID =:docid AND VFD.Cantidad > 0 ';
            $sql.='GROUP BY PRD.CtaMayorID,VFD.Cantidad,PRD.CostoCompra';
            $stmp = $cn->prepare($sql);
            $stmp->bindParam(':docid', $id, 2);
            $stmp->execute();
            $cuentas = $stmp->fetchAll(PDO::FETCH_OBJ);

        } catch (Exception $exc) {
            throw $exc;
        }
        return $cuentas;
    }

    public function getCuentaVenFactura($id)
    {
        try {
            $cn = DataConection::getInstancia();
            $sql = 'SELECT PRD.CtaVentasID as ctaventasid,SUM(VFD.Subtotal) as valor ';
            $sql.='FROM dbo.VEN_FACTURAS_DT VFD INNER JOIN dbo.VEN_FACTURAS VF ON VFD.FacturaID = VF.ID ';
            $sql.='INNER JOIN dbo.INV_PRODUCTOS PRD ON VFD.ProductoID = PRD.ID ';
            $sql.='WHERE VF.ID =:docid	AND VFD.Cantidad > 0 ';
            $sql.='GROUP BY PRD.CtaVentasID';
            $stmp = $cn->prepare($sql);
            $stmp->bindParam(':docid', $id, 2);
            $stmp->execute();
            $cuentas = $stmp->fetchAll(PDO::FETCH_OBJ);

        } catch (Exception $exc) {
            throw $exc;
        }
        return $cuentas;
    }

    public function getCuentaImpuestoFactura($id)
    {
        try {
            $cn = DataConection::getInstancia();
            $sql = 'SELECT PRD.ImpuestoID as impuestoid,SUM(VFD.Impuesto) as valor ';
            $sql.='FROM dbo.VEN_FACTURAS_DT VFD INNER JOIN dbo.VEN_FACTURAS VF ON VFD.FacturaID = VF.ID ';
            $sql.='INNER JOIN dbo.INV_PRODUCTOS PRD ON VFD.ProductoID = PRD.ID ';
            $sql.='WHERE VF.ID =:docid AND VFD.Impuesto > 0 ';
            $sql.='GROUP BY PRD.ImpuestoID,VFD.Impuesto';
            $stmp = $cn->prepare($sql);
            $stmp->bindParam(':docid', $id, 2);
            $stmp->execute();
            $cuentas = $stmp->fetchAll(PDO::FETCH_OBJ);

        } catch (Exception $exc) {
            throw $exc;
        }
        return $cuentas;
    }

}