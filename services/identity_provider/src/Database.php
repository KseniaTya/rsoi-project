<?php

class Database{
    static function isset($params){
        include("instruments/postgress_connect.php");
        /** @var $connect - переменная из postgress_connect.php с текцщим подключением к бд*/

        $values = [];
        foreach($params as $k => $v){
            array_push($values, "$k='$v'");
        }
        try{
            $result = pg_fetch_all(pg_query($connect, "select * from users where ".implode(" and ", $values)));
        } catch (Exception $e){
            return false;
        }
        return $result != Array();
    }
}
