<?php declare(strict_types=1);
/** @var LeoCarmo\CircuitBreaker\CircuitBreaker $circuit */
include "instruments/utils.php";

try {
    header('Content-Type: application/json; charset=utf-8');
    $token= getallheaders()['token'] ?? "";
    $token = urlencode($token);
    $numStars = curl("http://rating_system:80/num_stars", ["token: $token"]);
    $result = ["stars" => (int)$numStars];
    $circuit->success();

echo json_encode($result);
} catch (RuntimeException $e){
    $circuit->failure();
    http_response_code(503);
    echo json_encode(["message"=> $e->getMessage()]);
}
