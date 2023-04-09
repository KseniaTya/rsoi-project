<?php

class Database{
    static function isset($params){
        include("./db_connect/postgress_connect.php");
        /** @var $connect - переменная из postgress_connect.php с текцщим подключением к бд*/

        $values = [];
        foreach($params as $k => $v){
            array_push($values, "$k='$v'");
        }
        $result = pg_fetch_all(pg_query($connect, "select * from users where ".implode(" and ", $values)));
        return $result != Array();
    }
}
