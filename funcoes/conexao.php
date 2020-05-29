<?php


//define('SGBD', 'mysql');
//define('HOST', 'mysql.hostinger.com.br');
//define('DBNAME', 'u309778760_gcoop');
//define('CHARSET', 'utf8');
//define('USER', 'u309778760_gcoop');
//define('PASSWORD', 'G.coop*2018');
//define('SERVER', 'HOSTINGER');

define('SGBD', 'mysql');
define('HOST', '127.0.0.1');
define('DBNAME', 'db_guiche');
define('CHARSET', 'utf8');
define('USER', 'root');
define('PASSWORD', '');
define('SERVER', 'local');

class conexao {   
    
    private static $pdo;    
    private function __construct() {}

    private static function verificaExtensao() {
        switch(SGBD):
            case 'mysql':
                $extensao = 'pdo_mysql';
                break;
            case 'mssql':{
                if(SERVER == 'linux'):
                    $extensao = 'pdo_dblib';
                else:
                    $extensao = 'pdo_sqlsrv';
                endif;
                break;
            }
            case 'postgre':
                $extensao = 'pdo_pgsql';
                break;
        endswitch;
        if(!extension_loaded($extensao)):
            echo "<h1>Extensão {$extensao} não habilitada!</h1>";
            exit();
        endif;
    }

    public static function getInstance() {
        self::verificaExtensao();
        if (!isset(self::$pdo)) {
            try {
                $opcoes = array(\PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES UTF8');
                switch (SGBD) :
                    case 'mysql':
                        self::$pdo = new \PDO("mysql:host=" . HOST . "; dbname=" . DBNAME . ";", USER, PASSWORD, $opcoes);
                        break;
                    case 'mssql':{
                        if(SERVER == 'linux'):
                            self::$pdo = new \PDO("dblib:host=" . HOST . "; database=" . DBNAME . ";", USER, PASSWORD, $opcoes);
                        else:
                            self::$pdo = new \PDO("sqlsrv:server=" . HOST . "; database=" . DBNAME . ";", USER, PASSWORD, $opcoes);
                        endif;
                        break;
                    }
                    case 'postgre':
                        self::$pdo = new \PDO("pgsql:host=" . HOST . "; dbname=" . DBNAME . ";", USER, PASSWORD, $opcoes);
                        break;
                endswitch;
                self::$pdo->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
            } catch (PDOException $e) {
                print "Erro: " . $e->getMessage();
            }
        }        
        return self::$pdo;
    }

    public static function isConectado(){           
        if(self::$pdo):
            return true;
        else:
            return false;
        endif;
    }
}



