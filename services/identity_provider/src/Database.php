<?php

class Database
{
    private $connect;

    public function __construct(){
        include("instruments/postgress_connect.php");
        /** @var $connect - переменная из postgress_connect.php с текущим подключением к бд */
        $this->connect = $connect;
    }
    public function isset($params)
    {
        $values = [];
        foreach ($params as $k => $v) {
            array_push($values, "$k='$v'");
        }
        try {
            $result = pg_fetch_all(pg_query($this->connect, "select * from users where " . implode(" and ", $values)));
        } catch (Exception $e) {
            return false;
        }
        return $result != array();
    }

    function isAdmin($email)
    {
        try {
            $result = pg_fetch_all(pg_query($this->connect, "select * from users where email = '$email' and role = 'Admin'"));
        } catch (Exception $e) {
            return false;
        }
        return $result != array();
    }

    function save(array $params)
    {
        try {
            list($profile, $email, $role) = [$params['profile'], $params['email'], $params['role'] ?? 'User'];
            pg_query($this->connect, "
                INSERT INTO users(profile, email, role)
                VALUES('$profile', '$email', '$role');
            ");
            return "done.";
        } catch (Exception $e) {
            return false;
        }
    }
}
