<?php
require_once 'cls_acceso_datos.php';
require_once 'cls_persona.php';

class cls_venta_factura extends cls_acceso_datos
{
    protected static $obj = null;
    protected $vend = array();
    protected $codgrupo = '';
    var $cantdv = 0;

    public function s_cod_grupo($value)
    {
        $this->codgrupo = trim($value);
    }

    function __construct()
    {
        parent::__construct();
        $this->obj = new cls_persona;
    }

    function __call($method, $args)
    {
        return call_user_func_array(array($this->obj, $method), $args);
    }

    public function fn_emp_codigo()
    {
        $this->cn->SetFetchMode(ADODB_FETCH_ASSOC);
        $this->sql = 'SELECT TOP 1 ID id,Código cod,GrupoID grp,ZonaID zon,Nombre nomb FROM EMP_EMPLEADOS WHERE Código=' . "'" . $this->g_codigo() . "'";

        $this->ejecutar();
        if ($this->exist_reg()) return $this->campos();
        return false;
    }

    public function fn_emp_vendedor()
    {
        $this->cn->SetFetchMode(ADODB_FETCH_NUM);
        $this->sql = "SELECT HD.VendedorID FROM VEN_FACTURAS HD INNER JOIN VEN_FACTURAS_DT DT WITH(NOLOCK) ON(HD.ID = DT.FacturaID)INNER JOIN CLI_CLIENTES CL WITH(NOLOCK) ON(HD.ClienteID = CL.ID)INNER JOIN EMP_EMPLEADOS EMP ON(HD.VendedorID = EMP.ID)WHERE HD.Anulado =0 AND (HD.Fecha BETWEEN '" . $this->g_fecha_1() . "' AND '" . $this->g_fecha_2() . "') AND EMP.Clase ='02' AND EMP.LibretaMilitar='100'";
        if ($this->g_codigo()) {
            $this->sql .= " AND EMP.Código='" . $this->g_codigo() . "'";
        }
        $this->sql .= " GROUP BY HD.VendedorID ORDER BY MAX(EMP.Nombre);";
        $this->ejecutar();
        if ($this->exist_reg()) {
            while (!$this->rs->EOF) {
                $this->vend[] = $this->rs->fields[0];
                $this->rs->MoveNext();
            }
        }
        if (count($this->vend)) return true;
        return false;
    }

    public function fn_informe_venta_factura()
    {
        $this->cn->SetFetchMode(ADODB_FETCH_NUM);
        switch ($this->g_opcion()) {
            case'PEND':
                $this->sql = "WEB_VEN_INFORME_PENDIENTES '" . $this->g_codigo() . "',0,'" . $this->g_fecha_1() . "','" . $this->g_fecha_2() . "'";
                break;
            case'PAGA':
                $this->sql = "WEB_VEN_INFORME_PENDIENTES '" . $this->g_codigo() . "',1,'" . $this->g_fecha_1() . "','" . $this->g_fecha_2() . "'";
                break;
            default   :
                $this->sql = "WEB_VEN_INFORME_FACTURAS '" . $this->g_codigo() . "','" . $this->g_fecha_1() . "','" . $this->g_fecha_2() . "'";
        }
        $this->ejecutar();
        if ($this->exist_reg()) {
            $x = 1;
            while (!$this->rs->EOF) {
                echo '<tr class="';
                $devol = floatval($this->rs->fields[6]);
                $ntcred = floatval($this->rs->fields[8]);
                if ($devol or $ntcred) {
                    if ($devol) {
                        echo 'tr_color_devol';
                    } else {
                        echo 'tr_color_cred';
                    }
                } elseif (($x % 2) == 0) echo 'tr_bgalter';

                echo '"><td width="25px">' . $x . '</td>';
                echo '<td width="35px">' . $this->rs->fields[0] . '</td>';//fecha
                echo '<td width="50px">' . $this->rs->fields[1] . '</td>';//tipo
                echo '<td width="65px">' . $this->rs->fields[2] . '</td>';//codigo
                echo '<td width="155px">' . ucwords($this->rs->fields[3]) . '</td>';//cliente
                echo '<td width="38px">' . $this->rs->fields[4] . '</td>';//zona

                $vp = trim($this->rs->fields[12]);
                echo '<td align="center" width="20px" ';
                echo $vp ? 'class="comision_pagada">VP' : '>';
                echo '</td>';

                echo '<td align="right" width="40px"><strong>';
                $total = floatval($this->rs->fields[5]);//total
                if ($total) {
                    echo number_format($total, 2);
                    $cantvf++;
                } else echo '-';
                echo '</strong></td>';

                echo '<td align="right" width="40px"><strong>';
                if ($devol) {
                    echo number_format($devol, 2);
                    $cantdev++;
                } else echo '-';
                echo '</strong></td>';
                echo '<td width="40px">' . $this->rs->fields[13] . '</td>';
                $dtdev = trim($this->rs->fields[7]);
                echo '<td width="100px">';
                echo $dtdev ? ucwords($dtdev) : ''; //det devolucion
                echo '</td>';

                echo '<td align="right" width="40px"><strong>';
                if ($ntcred) {
                    echo number_format($ntcred, 2);
                } else echo '-';
                echo '</strong></td>';

                echo '<td width="100px">';
                $dtcrd = trim($this->rs->fields[9]);//det Credito
                echo $dtcrd ? ucwords($dtcrd) : '';
                echo '</td>';

                echo '<td align="right" width="35px"><strong>';
                $abono = floatval($this->rs->fields[10]);
                if ($abono) {
                    echo number_format($abono, 2);
                    if (!$devol) $cantabono++;
                } else echo '-';
                echo '</strong></td>';

                echo '<td align="right" width="35px"><strong>';
                $sald = floatval($this->rs->fields[11]);
                echo $sald > 0 ? number_format($sald, 2) : '-';
                echo '</strong></td>';
                echo '</tr>';
                $this->rs->MoveNext();
                $x++;
            }
            $this->rs->Close();
            echo '<tr style="display:none"><td colspan="14" align="right">';
            echo '<input type="hidden" name="cantvf" id="cantvf" value="' . floatval($cantvf) . '"/>';
            echo '<input type="hidden" name="cantdev" id="cantdev" value="' . floatval($cantdev) . '"/>';
            echo '<input type="hidden" name="cantabono" id="cantabono" value="' . floatval($cantabono) . '"/>';
            echo '<input type="hidden" name="cantreg" id="cantreg" value="' . floatval($x - 1) . '"/>';
            echo '</td></tr>';
        }
    }

    public function fn_informe_venta_factura_porcentaje()
    {
        $this->cn->SetFetchMode(ADODB_FETCH_NUM);
        $this->sql = "WEB_VEN_PORCENTAJE_VENTAS '" . $this->codgrupo . "','" . $this->g_codigo() . "','" . $this->g_fecha_1() . "','" . $this->g_fecha_2() . "'";
        $this->ejecutar();
        if ($this->exist_reg()) {
            $x = 1;
            while (!$this->rs->EOF) {
                echo '<tr class="';
                $devol = floatval($this->rs->fields[7]);
                if ($devol or $ntcred) {
                    if ($devol) {
                        echo 'tr_color_devol';
                    } else {
                        echo 'tr_color_cred';
                    }
                } elseif (($x % 2) == 0) echo 'tr_bgalter';

                echo '"><td width="25px">' . $x . '</td>';
                echo '<td width="35px">' . $this->rs->fields[0] . '</td>';//fecha
                echo '<td width="50px">' . $this->rs->fields[1] . '</td>';//tipo
                echo '<td width="65px">' . $this->rs->fields[2] . '</td>';//codigo
                echo '<td width="155px">' . ucwords($this->rs->fields[3]) . '</td>';//cliente
                echo '<td width="38px">' . $this->rs->fields[4] . '</td>';//zona

                echo '<td align="right" width="40px"><strong>';
                $total = floatval($this->rs->fields[5]);//total
                if ($total) {
                    echo number_format($total, 2);
                    $cantvf++;
                } else echo '-';
                echo '</strong></td>';

                echo '<td align="right" width="35px"><strong>';
                $abono = floatval($this->rs->fields[6]);
                if ($abono) {
                    echo number_format($abono, 2);
                    if (!$devol) $cantabono++;
                } else echo '-';
                echo '</strong></td>';


                echo '<td align="right" width="40px"><strong>';
                if ($devol) {
                    echo number_format($devol, 2);
                    $cantdev++;
                } else echo '-';
                echo '</strong></td>';

                echo '<td align="right" width="35px"><strong>';
                $sald = floatval($this->rs->fields[8]);
                echo $sald > 0 ? number_format($sald, 2) : '-';
                echo '</strong></td>';
                echo '</tr>';
                $this->rs->MoveNext();
                $x++;
            }
            $this->rs->Close();
            echo '<tr style="display:none"><td colspan="14" align="right">';
            echo '<input type="hidden" name="cantvf" id="cantvf" value="' . intval($cantvf) . '"/>';
            echo '<input type="hidden" name="cantdev" id="cantdev" value="' . intval($cantdev) . '"/>';
            echo '<input type="hidden" name="cantabono" id="cantabono" value="' . intval($cantabono) . '"/>';
            echo '<input type="hidden" name="cantreg" id="cantreg" value="' . intval($x - 1) . '"/>';
            echo '</td></tr>';
        }
    }

    public function fn_informe_venta_devolucion()
    {
        $this->cn->SetFetchMode(ADODB_FETCH_NUM);
        switch ($this->g_opcion()) {
            case'NOTRANG':
                $this->sql = "WEB_VEN_INFORME_DEVOLUCIONES_NOTRANG '" . $this->g_codigo() . "','" . $this->g_fecha_1() . "','" . $this->g_fecha_2() . "'";
                break;
            default :
                $this->sql = "WEB_VEN_INFORME_DEVOLUCIONES '" . $this->g_codigo() . "','" . $this->g_fecha_1() . "','" . $this->g_fecha_2() . "'";
        }
        $this->ejecutar();
        if ($this->exist_reg()) {
            $x = 1;
            while (!$this->rs->EOF) {
                echo '<tr class="';
                $devol = floatval($this->rs->fields[5]);
                $vp = trim($this->rs->fields[3]);
                if ($vp) {
                    echo 'tr_color_devol';
                } elseif (($x % 2) == 0) echo 'tr_bgalter';

                echo '"><td width="30px">' . $x . '</td>';
                echo '<td width="50px">' . $this->rs->fields[0] . '</td>';//fecha devol
                echo '<td width="80px" align="center">' . $this->rs->fields[1] . '</td>';//codigo
                echo '<td width="180px">' . ucwords($this->rs->fields[2]) . '</td>';//cliente

                echo '<td align="center" width="30px" ';
                echo $vp ? 'class="comision_pagada">VP' : '>';
                echo '</td>';

                echo '<td align="right" width="50px"><strong>';
                $total = floatval($this->rs->fields[4]);//total
                if ($total) {
                    echo number_format($total, 2);
                    $cantvf++;
                } else echo '-';
                echo '</strong></td>';

                echo '<td align="right" width="50px"><strong>';
                if ($devol) {
                    echo number_format($devol, 2);
                    $cantdev++;
                } else echo '-';
                echo '</strong></td>';

                $dtdev = trim($this->rs->fields[6]);
                echo '<td width="170px">';
                echo $dtdev ? ucwords($dtdev) : ''; //det devolucion
                echo '</td>';

                echo '<td width="50px"><strong>' . $this->rs->fields[7] . '</strong></td>';
                echo '<td width="50px"><strong>' . $this->rs->fields[8] . '</strong></td>';

                echo '</tr>';
                $this->rs->MoveNext();
                $x++;
            }
            $this->rs->Close();
            $this->cantdv = floatval($x - 1);
        }
    }

    public function fn_informe_venta_consolidado()
    {
        $x = 1;
        $this->cn->SetFetchMode(ADODB_FETCH_NUM);
        foreach ($this->vend as $key => $val) {
            $this->sql = "WEB_VEN_INFORME_CONSOLIDADO '$val','" . $this->g_fecha_1() . "','" . $this->g_fecha_2() . "'";
            $this->ejecutar();
            if ($this->exist_reg()) {
                echo '<tr class="';
                if (($x % 2) == 0) echo 'tr_bgalter';
                echo '"><td width="30px" align="center"><strong>' . $x . '</strong></td>';
                echo '<td width="60px">' . $this->rs->fields[0] . '</td>';//codigo
                echo '<td width="300px"><strong>' . ucwords(trim($this->rs->fields[1])) . '</strong></td>';//vendedor
                $ventas = intval($this->rs->fields[2]);
                $totalventas = intval($totalventas + $ventas);
                echo '<td width="35px" align="center"><strong>' . $ventas . '</strong></td>';//ventas
                echo '</tr>';
                $x++;
            }
        }
        echo '<tr>';
        echo '<td colspan="3" align="right"><strong>TOTAL VENTAS : </strong></td>';
        echo '<td align="center"><strong style="font-size:16px">' . number_format($totalventas) . '</strong></td>';
        echo '</tr>';
    }
}

?>