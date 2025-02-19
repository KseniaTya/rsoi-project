<?php declare(strict_types=1);
/** @var LeoCarmo\CircuitBreaker\CircuitBreaker $circuit */
include "instruments/utils.php";

saveStatistic('Попытка получить список книг в библиотеке');
try{
    header('Content-Type: application/json; charset=utf-8');

    $page = $_GET['page'] ?? 1;
    $size = $_GET['size'] ?? 50;
    $token = getallheaders()['token'] ?? "";
    $token = urlencode($token);

    if($size < 0 || $page < 0){
        echo "incorrect values!";
    }
    else {
        $_GET['showAll'] = $_GET['showAll'] ?? "false";


        $array = json_decode(curl("http://library_system:80/get_books&page=$page&size=$size&libraryUid=$libraryUid".($_GET['showAll']=="true" ?"&showAll=true" :"&showAll=false"), ["token: $token"]));
        $items = array_map(fn($item) => [
            "bookUid" => $item -> book_uid,
            "name"=> $item -> name,
            "author"=> $item -> author,
            "genre"=> $item -> genre,
            "condition" => $item -> condition,
            "availableCount" => $item -> available_count
        ], $array);
        $result = [
            "page" => $page,
            "pageSize" => count($items) < $size ? count($items):$size,
            "totalElements" => count($items),
            "items" => $items
        ];
        $circuit->success();
        saveStatistic('Успех получения списка книг в библиотеке');

        //check_health("http://pstgu.yss.su/1/MorozIvan/test/index.php?data=".urlencode(json_encode($result,JSON_PRETTY_PRINT)));
        echo normJsonStr(json_encode($result,JSON_PRETTY_PRINT));
    }
} catch (RuntimeException $e){
    saveStatistic('Провал попытки получить список книг в библиотеке');
    $circuit->failure();
    http_response_code(503);
    echo "{}";
}
