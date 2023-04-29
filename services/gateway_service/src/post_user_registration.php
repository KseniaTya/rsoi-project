<?php
include "instruments/utils.php";

header('Content-Type: application/json; charset=utf-8');
$token = getallheaders()['token'] ?? "";
$input = file_get_contents('php://input');
echo curl_post("http://identity_provider:80/registration", $input, ["token: $token"]);