<?php

/**
 * Description of DataConection
 *
 * @author Administrador
 */
class DataConection extends PDO
{

    protected static $instancia;
    private static $dbtipo;

    public static function getInstancia($dbtipo = 'sqlserver')
    {
        if (empty(self::$instancia)) {
            try {
                self::$dbtipo = $dbtipo;
                self::$instancia = new self();
                self::$instancia->setAttribute(PDO::SQLSRV_ATTR_ENCODING, PDO::SQLSRV_ENCODING_UTF8);
            } catch (PDOException $ex) {
                self::$instancia = null;
                throw $ex;
            }
        }
        return self::$instancia;
    }

    public function __construct()
    {
        switch (self::$dbtipo) {
            case 'sqlserver':
                $dns = 'sqlsrv:server=' . DB_HOST . ';Database=' . DB_BASE . ';MultipleActiveResultSets=false';
                parent::__construct($dns, DB_USER, DB_PASS, array(
                        PDO::ATTR_PERSISTENT => false,
                        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
                    )
                );
                break;

            case 'postgres':
                $dns = 'pgsql:host=' . DB_HOST . ' dbname=' . DB_BASE . ' charset=UTF-8';
                break;

            default:
                $dns = 'mysql:host=' . MYDB_HOST . ';dbname=' . MYDB_BASE;
                parent::__construct($dns, MYDB_USER, MYDB_PASS, array(
                        PDO::ATTR_PERSISTENT => false,
                        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
                    )
                );
        }

    }

    public function __destruct()
    {
        self::$instancia = null;
    }

}