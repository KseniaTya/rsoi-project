<?php

if(isAdmin($_GET['email'])){

    $db = new SQLite3($_SERVER['DOCUMENT_ROOT'].'/db/statistic.db');

    $db->exec("CREATE TABLE IF NOT EXISTS statistic(id INTEGER PRIMARY KEY, message TEXT, service TEXT, username TEXT, datetime TEXT)");
    $res = $db->query('SELECT * FROM statistic');

    $result = [];
    while ($row = $res->fetchArray(SQLITE3_ASSOC)) {
        $result[] = $row;
    }
    http_response_code(200);
    echo json_encode(["message" => json_encode($result)]);
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