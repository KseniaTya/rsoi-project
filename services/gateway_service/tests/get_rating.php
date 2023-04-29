<?php
include "instruments/utils.php";
$jwt = curl("http://gateway_service:80/test?id_test=token_authorize.php");
echo curl("http://gateway_service:80/api/v1/rating", ["token: $jwt"]);