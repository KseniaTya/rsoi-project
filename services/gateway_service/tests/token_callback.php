<?php
include "./utils.php";
$token = curl("http://gateway_service:80/api/v1/authorize?profile=admin&email=admin@admin.ru");
echo curl("http://gateway_service:80/api/v1/callback?jwt=$token");