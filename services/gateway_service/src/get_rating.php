<?php declare(strict_types=1);
/** @var LeoCarmo\CircuitBreaker\CircuitBreaker $circuit */
include "instruments/utils.php";

saveStatistic('Попытка получить рейтинг пользователя');

try {
    header('Content-Type: application/json; charset=utf-8');
    $token= getallheaders()['token'] ?? "";
    $token = urlencode($token);
    $numStars = curl("http://rating_system:80/num_stars", ["token: $token"]);
    $result = ["stars" => (int)$numStars];
    $circuit->success();
    saveStatistic('Успех получения рейтинга');

    echo json_encode($result);
} catch (RuntimeException $e){
    saveStatistic('Провал попытки получить рейтинг пользователя');

    $circuit->failure();
    http_response_code(503);
    echo json_encode(["message"=> $e->getMessage()]);
}
