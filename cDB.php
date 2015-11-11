<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Хыиуду
 * Date: 10.11.15
 * Time: 16:09
 * To change this template use File | Settings | File Templates.
 */
class cDB
{

    public static $db_host = "localhost";
    public static $db_login = "root";
    public static $db_password = "";
    public static $db_name = "cognitive";
    
    protected static $_instance;

    private function __construct() {
    }

    public static function getInstance() {
        if (self::$_instance === null) {
            self::$_instance = new self;
        }
        return self::$_instance;
    }

    private function __clone() {
    }

    private function __wakeup() {
    }

    /*
    Естественно, в любом рабочем проекте есть куда более навороченный класс для работы с БД - с логированием всех ошибок,
    выводом при ошибке красивых сообщений куда надо, а не куда попало, и т.д. И нативные процедуры работы с БД уже давно
    никто не использует. Да и вообще, функции mysql_* скоро будут deprecated, и рекомендовано использовать класс mysqli. Но!
    В рамках тестового задания у меня нет цели написать что-то подобное тому, что есть, например, в Kohana.
    По той же причине я тут не делаю никакой защиты от SQL Injection и проверки, с какого хоста пришел запрос. Все равно,
    в enterprise-проекте все это уже есть в составе фреймворка, а я и так слишком много времени за этим заданием сижу.
    Поэтому обойдусь простейшей оберткой - абы работало..
    */

    public static function init()   {
        try {
            mysql_connect(self::$db_host, self::$db_login, self::$db_password);
        }
        catch (Exception $e)    {
            throw new Exception("Error connecting to SQL server ".self::$db_host.": ".$e->getMessage());
        }
        try {
            mysql_select_db(self::$db_name);
        }
        catch (Exception $e)    {
            throw new Exception("Error connecting to database on ".self::$db_host.": ".$e->getMessage());
        }
    }

    public static function query($query)    {
        $res = mysql_query($query) or die("Cant execute query $query - ".mysql_error());
        return $res;
    }

    public static function fetch($query, $associate_field = '')   {
        $res = self::query($query);
        $output = array();
        while ($row = mysql_fetch_assoc($res))  {
            if ($associate_field)
                $output[$row[$associate_field]] = $row;
            else
                $output[]=$row;
        }
        return $output;
    }
}
