<?php

if(isAdmin($_GET['email'])){
    http_response_code(200);
    echo json_encode(["message" => "kek"]);
} else {
    http_response_code(401);
    echo json_encode(["message" => "401 Unauthorized - you are not admin"]);
}

function isAdmin($email)
{
    include("instruments/postgress_connect.php");
    /** @var $connect - переменная из postgress_connect.php с текущим подключением к бд */
    try {
        $result = pg_fetch_all(pg_query($connect, "select * from users where email = '$email' and role = 'Admin'"));
    } catch (Exception $e) {
        return false;
    }
    return $result != array();
}