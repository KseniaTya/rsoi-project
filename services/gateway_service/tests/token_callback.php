<?php
include "instruments/utils.php";
$token = curl("http://gateway_service:80/test?id_test=token_authorize.php");
if($token !== '{"message":"Access Denied"}') {
    echo curl("http://gateway_service:80/api/v1/callback?jwt=$token");
} else {
    echo $token;
}
