<?php

include_once('cDB.php');

class cMainModel
{
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

//    ******************************************************************
    private static $users_data, $education_data, $cities_data;

    public static function getUsersData()  {
        if (!self::$users_data) {
            $query = '
    SELECT u.id
          , u.name
          , e.name education
          , e.id education_id
          , group_concat(c.name separator ", ") cities
          , concat(",",group_concat(c.id),",") cities_ids
    from users u
        left join educations e on e.id = u.education_id
        left join user_cities uc on uc.user_id = u.id
        left join cities c on uc.city_id = c.id
    group by u.id;
    ';
            self::$users_data = cDB::fetch($query, 'id');
        }
        return self::$users_data;
    }

    public static function setUserEducation($user_id, $education_id) {
//        Тут должна быть адекватная защита от SQL Injection, но см. комментарий на этот счет в cDB.php
        if (cDB::query('update users u set u.education_id = '.$education_id.' where u.id = '.$user_id))
            self::$users_data[$user_id]['education_id'] = $education_id;
    }

    public static function getEducationData()   {
        if (!self::$education_data) {
            $query = "select 0 selected, e.id, e.name from educations e";
            self::$education_data = cDB::fetch($query);
        }
        return self::$education_data;
    }

    public static function getCitiesData()   {
        if (!self::$cities_data) {
            $query = "select 0 selected, c.id, c.name from cities c";
            self::$cities_data = cDB::fetch($query);
        }
        return self::$cities_data;
    }
}
