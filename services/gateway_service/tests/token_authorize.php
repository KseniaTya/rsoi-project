<?php
include "instruments/utils.php";
echo curl("http://gateway_service:80/api/v1/authorize?profile=admin&email=admin@admin.ru");