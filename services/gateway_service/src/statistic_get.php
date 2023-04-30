<?php
include "instruments/utils.php";
$token = getallheaders()['token'] ?? "";
echo curl("http://statistic_service:80/statistic", ["token: $token"]);