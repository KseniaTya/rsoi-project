<?php
include "instruments/utils.php";

saveStatistic('Попытка создания книги');

header('Content-Type: application/json; charset=utf-8');
$token= getallheaders()['token'] ?? "";
$token = urlencode($token);
$postdata = file_get_contents('php://input');
try{
    $result = curl_post("http://library_system:80/new_book", $postdata, ["token: $token"]);
    if(strlen($result) > 0){
        throw new Exception($result);
    }

    saveStatistic("Книга ($postdata) создана успешно");
    http_response_code(200);
} catch (Exception $e) {
    saveStatistic("Провал создания книги ($postdata)");
    http_response_code(400);
    echo $e;
}
