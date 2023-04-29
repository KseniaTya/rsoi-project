<?php
include "instruments/utils.php";

$jwt = curl("http://gateway_service:80/test?id_test=token_authorize.php");
echo curl("http://gateway_service:80/api/v1/libraries/83575e12-7ce0-48ee-9931-51919ff3c9ee/books?page=1&size=25&showAll=true", ["token: $jwt"]);