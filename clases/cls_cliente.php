<?php
require_once 'cls_acceso_datos.php';
require_once 'cls_persona.php';

class cls_cliente extends cls_acceso_datos
{
    protected static $obj = null;
    protected $descrip = '';
    protected $idprod = '';

    function s_descripcion($value)
    {
        $this->descrip = trim($value);
    }

    function s_idproducto($value)
    {
        $this->idprod = trim($value);
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

    public function fn_cli_buscar()
    {
        $this->cn->SetFetchMode(ADODB_FETCH_NUM);
        $this->sql = 'SELECT TOP 350 C.Código,LOWER(C.Nombre),C.Folder,Z.nombre FROM dbo.CLI_CLIENTES C LEFT OUTER JOIN dbo.SIS_ZONAS Z ON C.ZonaID=Z.ID WHERE C.Anulado=0 AND ';
        switch ($this->g_opcion()) {
            case'N':
                $this->sql .= "C.Nombre LIKE '" . $this->descrip . "%'";
                break;
            case'C':
                $this->sql .= "C.Código='" . $this->descrip . "'";
                break;
            default:
                $this->sql .= "RTRIM(C.Folder)='" . $this->descrip . "'";
        }
        $this->sql .= ' ORDER BY C.Nombre;';
        $this->ejecutar();
        if ($this->exist_reg()) {
            $x = 1;
            while (!$this->rs->EOF) {
                echo '<tr>';
                echo '<td align="center">' . $x . '</td>';
                echo '<td align="center"><a href="javascript:fn_select_cod(' . "'" . $this->rs->fields[0] . "'" . ')">';
                echo $this->rs->fields[0] . '</a></td>';
                echo '<td>' . ucwords($this->rs->fields[1]) . '&nbsp</td>';
                echo '<td>' . $this->rs->fields[2] . '&nbsp</td>';
                echo '<td>' . $this->rs->fields[3] . '&nbsp</td>';
                echo '</tr>';
                $this->rs->MoveNext();
                $x++;
            }
            $this->rs->Close();
        } else echo 'No se Encontro Registros..';
    }

    public function fn_cli_consulta_codigo()
    {
        $this->cn->SetFetchMode(ADODB_FETCH_ASSOC);
        $this->sql = 'SELECT TOP 1 C.Código cod,C.Nombre nomb,GRP.Código as Grup ,C.Folder fold,C.Dirección direc,C.Teléfono1 telef,C.Teléfono2 comis,CASE C.Anulado WHEN 0 THEN ' . "'Activo'" . ' ELSE ' . "'Inactivo'" . ' END estd,LOWER(EM.Código + ' . "' - '" . ' + EM.Nombre)vend,GRP.Nombre grp FROM CLI_CLIENTES C LEFT JOIN VEN_FACTURAS VF ON C.ID = VF.ClienteID LEFT OUTER JOIN EMP_EMPLEADOS EM ON(VF.VendedorID=EM.ID)LEFT JOIN SIS_ZONAS Z ON(C.ZonaID = Z.ID)LEFT JOIN CLI_GRUPOS GRP ON(C.GrupoID =GRP.ID)WHERE C.Anulado=0 AND C.Código=' . "'" . $this->g_codigo() . "'";
        $this->ejecutar();
        if ($this->exist_reg()) {
            return $this->campos();
        }
        return false;
    }

    public function fn_cli_datos_codigo()
    {
        $this->cn->SetFetchMode(ADODB_FETCH_ASSOC);
        $this->sql = 'SELECT TOP 1 C.ID id,C.Código cod,C.ruc,LOWER(C.Nombre) cli,CASE C.Anulado WHEN 0 THEN ' . "'Activo'" . ' ELSE ' . "'Inactivo'" . ' END estd,EM.ID emp_id,EM.Código codemp,LOWER(EM.Nombre)vend FROM CLI_CLIENTES C LEFT OUTER JOIN VEN_FACTURAS VF ON(C.ID=VF.ClienteID)LEFT OUTER JOIN EMP_EMPLEADOS EM ON(VF.VendedorID=EM.ID)WHERE C.Anulado=0 AND C.Código=' . "'" . $this->g_codigo() . "'";
        $this->ejecutar();
        if ($this->exist_reg()) {
            return $this->campos();
        }
        return false;
    }

    public function fn_cli_saldo_total()
    {
        $this->cn->SetFetchMode(ADODB_FETCH_ASSOC);
        $this->sql = "WEB_CLI_SALDO_TOTAL '" . $this->g_id() . "'";
        $this->ejecutar();
        return $this->exist_reg();
    }

    public function fn_cli_estado_cuenta()
    {
        $this->cn->SetFetchMode(ADODB_FETCH_NUM);
        $this->sql = "WEB_CLIENTE_ESTADO_CUENTA '" . $this->g_codigo() . "','" . $this->g_fecha_1() . "','" . $this->g_fecha_2() . "'";
        $this->ejecutar();
        return $this->exist_reg();
    }

    public function fn_dt_estado_cuenta()
    {
        $saldo = floatval($this->rs->fields[6]);
        $x = 1;
        while (!$this->rs->EOF) {
            $orden = intval($this->rs->fields[7]);
            $tip = strtoupper(trim($this->rs->fields[1]));
            echo '<tr class="';
            if ($tip == 'VEN-DE' or $tip == 'CLI-CR') {
                echo 'tr_color_alert';
            } elseif (($x % 2) == 0) echo 'tr_bgalter';

            if ($orden == 1 or $orden == 3) {
                echo ' tr_negrita';
            }
            echo '"><td><strong>' . $x . '</strong></td>';
            echo '<td>' . $this->rs->fields[0] . '</td>';
            echo '<td>' . $tip . '</td>';
            echo '<td>' . $this->rs->fields[2] . '</td>';
            echo '<td>' . $this->rs->fields[3] . '</td>';

            $debe = floatval($this->rs->fields[4]);
            echo '<td align="right"><strong>';
            if ($debe > 0) {
                echo number_format($debe, 2);
                $saldo = ($saldo + $debe);
            } else echo '-';
            echo '</strong></td>';

            $haber = floatval($this->rs->fields[5]);
            echo '<td align="right"><strong>';
            if ($haber > 0) {
                echo number_format($haber, 2);
                $saldo = ($saldo - $haber);
            } else echo '-';
            echo '</strong></td><td align="right">';

            if ($orden == 3) {
                $saldo = floatval($this->rs->fields[6]);
            }
            if ($saldo > 0) {
                echo '<strong>' . number_format($saldo, 2) . '</strong>';
            } else echo '-';
            echo '</td>';
            $this->rs->MoveNext();
            echo '</tr>';
            $x++;
        }
        $this->rs->Close();
    }

    public function fn_cli_informe_saldo_grupo()
    {
        $this->cn->SetFetchMode(ADODB_FETCH_NUM);
        $this->sql = "WEB_CLI_INFORME_SALDOS_GRUPO '" . $this->g_codigo() . "','" . $this->g_fecha_2() . "'";
        $this->ejecutar();
        if ($this->exist_reg()) {
            $total = 0;
            $x = 1;
            $totalgrp = 0;
            $sald = 0;
            $data = array();
            while (!$this->rs->EOF) {
                $sald = floatval($this->rs->fields[3]);
                $total = floatval($total + ($sald));
                $idgrp = trim($this->rs->fields[4]);
                if (!in_array($idgrp, $data)) {
                    if ($x > 1) {
                        echo '<tr class="tr_totales"><td></td><td colspan="8">' . $descrip;
                        echo '</td><td align="right">TOTAL</td><td align="right">' . number_format($totalgrp, 2) . '</td></tr>';
                        echo '<tr><td colspan="11">&nbsp</td></tr>';
                    }
                    $descrip = trim($this->rs->fields[5]);
                    $data = array();
                    $data[] = $idgrp;
                    $totalgrp = 0;
                }
                if (in_array($idgrp, $data)) {
                    $totalgrp = floatval($totalgrp + ($sald));
                }

                echo '<tr class="';
                if (($x % 2) == 0) echo 'tr_bgalter';
                echo '"><td><strong>' . $x . '</strong></td>';
                $cod = strval(trim($this->rs->fields[0]));
                echo '<td><a href="' . $cod . '">' . $cod . '&nbsp</a></td>';
                echo '<td>' . $this->rs->fields[1] . '</td>';
                echo '<td>' . $this->rs->fields[2] . '</td>';
                echo '<td>' . $this->rs->fields[6] . '</td>';
                echo '<td>' . ucwords($this->rs->fields[7]) . '</td>';
                echo '<td>' . $this->rs->fields[8] . '</td>';
                echo '<td>' . ucwords($this->rs->fields[9]) . '</td>';
                echo '<td>' . $this->rs->fields[10] . '</td>';
                echo '<td>' . $this->rs->fields[11] . '</td>';

                echo '<td align="right"><strong>' . number_format($sald, 2) . '</strong></td>';
                echo '</tr>';
                $this->rs->MoveNext();
                $x++;
            }
            $this->rs->Close();
            echo '<tr class="tr_total_gral">';
            echo '<td colspan="10" align="right">TOTAL GENERAL : </td>';
            echo '<td align="right">' . number_format($total, 2) . '</td></tr>';
        } else echo 'No se Encontro Registros..';
    }

    public function fn_cli_informe_saldo_cartera()
    {
        $this->cn->SetFetchMode(ADODB_FETCH_NUM);
        $this->sql = "WEB_CLI_INFORME_SALDOS_GRUPO_CARTERA '" . $this->g_codigo() . "','" . $this->g_fecha_2() . "'";
        $this->ejecutar();
        if ($this->exist_reg()) {
            $x = 1;
            $data = array();
            while (!$this->rs->EOF) {
                $idgrp = trim($this->rs->fields[3]);
                if (!in_array($idgrp, $data)) {
                    if ($x > 1) {
                        echo '<tr class="tr_totales"><td></td><td colspan="2">' . $descrip;
                        echo '</td><td align="right"></td></tr>';
                    }
                    $descrip = trim($this->rs->fields[4]);
                    $data = array();
                    $data[] = $idgrp;
                }
                echo '<tr class="';
                if (($x % 2) == 0) echo 'tr_bgalter';
                echo '"><td width="30px" align="center"><strong>' . $x . '</strong></td>';
                echo '<td width="60px">' . trim($this->rs->fields[0]) . '</td>';
                echo '<td width="250px">' . ucwords($this->rs->fields[1]) . '</td>';
                echo '<td width="60px" align="center">' . $this->rs->fields[2] . '</td>';
                echo '</tr>';
                $this->rs->MoveNext();
                $x++;
            }
            $this->rs->Close();
        } else echo 'No se Encontro Registros..';
    }

    public function fn_cli_informe_dt_call_center()
    {
        $this->cn->SetFetchMode(ADODB_FETCH_NUM);
        $this->sql = "WEB_PROC_CLI_INFORME_CALL_CENTER '" . $this->g_codigo() . "','" . $this->idprod . "','" . $this->g_fecha_1() . "','" . $this->g_fecha_2() . "'";
        $this->ejecutar();
        $x = 1;
        if ($this->exist_reg()) {
            $recors = $this->rs;
            while (!$recors->EOF) {
                echo '<tr class="';
                if (($x % 2) == 0) echo 'tr_bgalter';
                echo '"><td width="5px"><strong>' . $x . '</strong></td>';
                echo '<td width="10px">' . $recors->fields[0] . '</td>';
                echo '<td width="10px">' . $recors->fields[1] . '</td>';
                echo '<td width="25px">' . $recors->fields[2] . '</td>';
                echo '<td width="150px">' . ucwords($recors->fields[3]) . '</td>';
                echo '<td width="200px">' . $recors->fields[4] . '</td>';
                echo '<td width="200px">' . $recors->fields[5] . '</td>';
                echo '<td width="8px">' . $recors->fields[6] . '</td>';
                echo '<td align="center">';
                $idcli = trim($recors->fields[14]);
                $this->fn_cli_dt_productos($idcli);
                echo '</td>';
                echo '<td width="8px" align="right"><strong>' . number_format($recors->fields[7], 2) . '</strong></td>';
                echo '<td width="8px" align="right"><strong>' . number_format($recors->fields[8], 2) . '</strong></td>';
                echo '<td width="8px" align="right"><strong>' . number_format($recors->fields[9], 2) . '</strong></td>';
                echo '<td width="20px">' . ucwords($recors->fields[10]) . '</td>';
                echo '<td width="20px">' . ucwords($recors->fields[11]) . '</td>';
                echo '<td width="20px">' . ucwords($recors->fields[12]) . '</td>';
                echo '<td width="20px">' . ucwords($recors->fields[13]) . '</td>';
                $recors->MoveNext();
                echo '</tr>';
                $x++;
            }
            $recors->Close();
        }
    }

    public function fn_inv_det_informe_productos_fact()
    {
        $this->cn->SetFetchMode(ADODB_FETCH_NUM);
        $this->sql = "WEB_PROC_CLI_INFORME_CALL_CENTER '" . $this->g_codigo() . "','" . $this->idprod . "','" . $this->g_fecha_1() . "','" . $this->g_fecha_2() . "'";
        $this->ejecutar();
        $x = 1;
        if ($this->exist_reg()) {
            $recors = $this->rs;
            while (!$recors->EOF) {
                echo '<tr class="';
                if (($x % 2) == 0) echo 'tr_bgalter';
                echo '"><td width="5px"><strong>' . $x . '</strong></td>';
                echo '<td width="10px">' . $recors->fields[0] . '</td>';
                echo '<td width="10px">' . $recors->fields[1] . '</td>';
                echo '<td width="25px"><strong>' . $recors->fields[2] . '</strong></td>';
                echo '<td width="150px">' . ucwords($recors->fields[3]) . '</td>';
                echo '<td align="center" width="200px">';
                $idcli = strval($recors->fields[12]);
                $this->fn_cli_dt_productos($idcli);
                echo '</td>';
                echo '<td width="8px" align="right"><strong>' . number_format($recors->fields[7], 2) . '</strong></td>';
                $recors->MoveNext();
                echo '</tr>';
                $x++;
            }
            $recors->Close();
            $this->sql = "WEB_PROC_INV_PRODUCTOS_FACTURADOS '" . $this->g_codigo() . "','" . $this->idprod . "','" . $this->g_fecha_1() . "','" . $this->g_fecha_2() . "'";
            $this->ejecutar();
            $x = 1;
            if ($this->exist_reg()) {
                echo '<tr><td colspan="7">&nbsp;</td></tr>';
                echo '<tr class="cab_title"><td colspan="6" align="center">Detalle de Premios Consolidado Productos.</td></tr>';
                echo '<tr>';
                echo '<th colspan="2">Codigo</th>';
                echo '<th colspan="2">Producto</th>';
                echo '<th>Cantidad</th>';
                echo '<th>Precio</th>';
                echo '</tr>';
                while (!$this->rs->EOF) {
                    echo '<tr';
                    if ($x % 2 == 0) echo ' class="tr_bgalter"';
                    echo '><td colspan="2"><strong>' . $this->rs->fields[0] . '</strong></td>';
                    echo '<td colspan="2">' . $this->rs->fields[1] . '</td>';
                    $cant += intval($this->rs->fields[2]);
                    echo '<td align="right"><strong>' . intval($this->rs->fields[2]) . '</strong></td>';
                    $total += floatval($this->rs->fields[3]);
                    echo '<td align="right"><strong>' . number_format($this->rs->fields[3], 2) . '</strong></td>';
                    echo '</tr>';
                    $this->rs->MoveNext();
                    $x++;
                }
                echo '<tr>';
                echo '<td colspan="4" align="right"><strong style="font-size:16px">TOTAL </strong></td>';
                echo '<td align="right"><strong style="font-size:16px">' . $cant . '</strong></td>';
                echo '<td align="right"><strong style="font-size:16px">' . number_format($total, 2) . '</strong></td>';
                echo '</tr>';
                $this->rs->Close();
            }
        }
    }

    public function fn_cli_dt_productos($id)
    {
        $this->sql = "WEB_PROC_CLIENTE_DET_PRODUCTOS '$id','" . $this->idprod . "','" . $this->g_fecha_1() . "','" . $this->g_fecha_2() . "'";
        $this->ejecutar();
        $x = 1;
        if ($this->exist_reg()) {
            echo '<table border="0" cellspacing="0" cellpadding="0" class="table_det_prod">';
            echo '<tr>';
            echo '<th>Prod.</th>';
            echo '<th>Cant.</th>';
            echo '<th>PVP</th>';
            echo '</tr>';
            while (!$this->rs->EOF) {
                echo '<tr>';
                echo '<td>' . $this->rs->fields[0] . '</td>';
                echo '<td align="center" width="5px"><strong>' . intval($this->rs->fields[1]) . '</strong></td>';
                echo '<td align="center" width="10px"><strong>' . number_format($this->rs->fields[2], 2) . '</strong></td>';
                echo '</tr>';
                $this->rs->MoveNext();
            }
            echo '</table>';
            $this->rs->Close();
        }
    }
}

?>