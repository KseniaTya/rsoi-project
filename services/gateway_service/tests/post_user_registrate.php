<?php
include "instruments/utils.php";
$token = curl("http://gateway_service:80/test?id_test=token_authorize.php");

echo curl_post("http://gateway_service:80/api/v1/registration",
    json_encode(['profile' => 'ksenia', 'email' => 'ksenia@gmail.com']),
    ["token: $token"]
);