<?php
include "./utils.php";
echo curl("http://identity_provider:80/autorize?profile={$_GET['profile']}&email={$_GET['email']}");