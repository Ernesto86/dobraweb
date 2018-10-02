<?php // 3 de Marzo 2013 Desarollador WEB:ERNESTO GUAMAN U. INGENIERO EN SISTEMAS.
require 'adodb5/adodb.inc.php';

abstract class cls_acceso_datos
{
    protected $cn = false;
    protected $sql = '';
    public $rs = NULL;

    public function __construct()
    {
        try {
            if (!$this->cn) {
                $this->cn =& NewADOConnection('odbc_mssql');
                //$this->cn->Connect('DRIVER={SQL Server};SERVER=192.168.30.99;DATABASE=MUNDOTEXT', 'contabilidad', '@mundo@2017');
                $this->cn->Connect('DRIVER={SQL Server};SERVER=(local);DATABASE=dobraweb', 'sa', '123456');
                $this->cn->Execute("set names 'utf8'");

            }
        } catch (Exception $e) {
            echo "Caught Exception('{$e->getMessage()}')\n{$e}\n";
        }
    }

    public function ejecutar()
    {
        try {
            $this->rs = $this->cn->Execute($this->sql);
            return $this->rs;
        } catch (Exception $e) {
            echo "Caught Exception('{$e->getMessage()}')\n{$e}\n";
        }
    }

    public function exist_reg()
    {
        if (!$this->rs->EOF) return true;
        return false;
    }

    public function campos()
    {
        $data = $this->rs->fields;
        $this->rs->Close();
        return $data;
    }

    public function combo($id = '')
    {
        try {
            while (!$this->rs->EOF) {
                echo '<option value="' . $this->rs->fields[0] . '"';
                echo $id == $this->rs->fields[0] ? ' selected="selected"' : '';
                echo '>' . $this->rs->fields[1] . '</option>';
                $this->rs->MoveNext();
            }
            $this->rs->Close();
        } catch (Exception $e) {
            echo "Caught Exception('{$e->getMessage()}')\n{$e}\n";
        }
    }

    public function __destruct()
    {
        try {
            if ($this->cn) {
                $this->cn->Close();
                $this->cn = NULL;
                $this->rs = NULL;
            }
        } catch (Exception $e) {
            echo "Caught Exception('{$e->getMessage()}')\n{$e}\n";
        }
    }
} ?>