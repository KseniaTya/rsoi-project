<?php declare(strict_types=1);
/** @var LeoCarmo\CircuitBreaker\CircuitBreaker $circuit */
include "instruments/utils.php";

saveStatistic('Попытка получить список библиотек');

try{
header('Content-Type: application/json; charset=utf-8');
    $page = $_GET['page'] ?? 1;
    $size = $_GET['size'] ?? 50;
    $city = $_GET['city'] ?? "null";
    $token= getallheaders()['token'] ?? "";
    $token = urlencode($token);
    if($size < 0 || $page < 0){
        echo "incorrect values!";
    }
    else {
        $array = json_decode(curl("http://library_system:80/get_libraries?city=$city&page=$page&size=$size", ["token: $token"]));
        $items = array_map(fn($item) => [
              "libraryUid"=> $item -> library_uid,
              "name"=> $item -> name,
              "address"=> $item -> address,
              "city"=> $item -> city
            ], $array);
        $result = [
            "page" => $page,
            "pageSize" => count($items) < $size ? count($items):$size,
            "totalElements" => count($items),
            "items" => $items
            ];
        $json = json_encode($result, JSON_PRETTY_PRINT);
        $circuit->success();
        saveStatistic('Успех получения списка библиотек');

        echo normJsonStr($json);

    }
} catch (RuntimeException $e){
    saveStatistic('Провал попытки получить список библиотек');
    $circuit->failure();
    http_response_code(503);
    echo "{}";
}
