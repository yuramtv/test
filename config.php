<?php
Error_Reporting(E_ALL & ~E_NOTICE);//не выводить предупреждения

define('LOCAL_NAME', 'beejee');

if ($_SERVER['SERVER_NAME'] == LOCAL_NAME) {

    define('MYSQL_HOST', 'localhost');
    define('MYSQL_USER', 'root');
    define('MYSQL_PASS', '');
    define('MYSQL_NAME', 'beejee');

} else {

    define('MYSQL_HOST', 'mysql.zzz.com.ua');
    define('MYSQL_USER', 'bjtest73');
    define('MYSQL_PASS', 'db98a77fE4');
    define('MYSQL_NAME', 'yura_mtv');
}

# Функция: объект класса для работы с базой данных.
function & DB() {
    static $oClass = null;
    if( is_null( $oClass ) ) {
        try {
            $oClass = new PDO( 'mysql:host='.MYSQL_HOST.';dbname='.MYSQL_NAME.';charset=utf8', MYSQL_USER, MYSQL_PASS, array( PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8' ) );
            $oClass->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );
        }
        catch( Exception $e ) {
            die( 'Trade error '.$e->getMessage() );
        }
    }
    return $oClass;
}

# Функция: автоматическое подключение рабочих классов.
function class_autoloader( $sClassName ) {

    $path = strtolower (str_replace("_", "/", $sClassName));//разбивает имя класска получая из него путь

    if( is_file( $path.".php" ) ) {

        include_once($path .".php");//подключает php файл по полученному пути

    } else {
        echo "Не найден файл по адресу:<br/>".$path.".php"."<br/>";
    }

}

spl_autoload_register( 'class_autoloader' );

?>