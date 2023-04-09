<?php
include "./utils.php";
 echo curl("http://identity_provider:80/callback?jwt={$_GET['jwt']}");