<?php
include_once "instruments/utils.php";

$jwt = curl("http://gateway_service:80/test?id_test=token_authorize.php");
if($jwt !== '{"message":"Access Denied"}'){
    $reservations = json_decode(curl("http://reservation_system:80/get_reservations", ["token: $jwt"]));
    $reservations = array_filter($reservations, fn($x) => $x->status == "RENTED");
    $reservation = reset($reservations);
    echo curl_post("http://gateway_service:80/api/v1/reservations/$reservation->reservation_uid/return",
        json_encode(['condition' => 'EXCELLENT', 'date' => '2021-10-11']),
        ["token: $jwt"]);
} else {
    echo $jwt;
}



