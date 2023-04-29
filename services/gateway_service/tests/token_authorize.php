<?php
include "instruments/utils.php";

// admin
// echo curl("http://gateway_service:80/api/v1/authorize?profile=admin&email=admin@admin.ru");

// user, if exists
echo curl("http://gateway_service:80/api/v1/authorize?profile=ksenia&email=ksenia@gmail.com");