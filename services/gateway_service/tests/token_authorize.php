<?php
include "instruments/utils.php";

// admin
echo curl("http://gateway_service:80/api/v1/authorize?profile=admin&email=admin@admin.ru");

// test user
// echo curl("http://gateway_service:80/api/v1/authorize?profile=ksenia&email=ksenia@gmail.com");