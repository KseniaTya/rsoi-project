<?php
include "instruments/utils.php";
$token = getallheaders()['token'] ?? "";

saveStatistic('Попытка получения статистики');

header('Content-Type: application/json; charset=utf-8');
echo curl("http://statistic_service:80/statistic", ["token: $token"]);