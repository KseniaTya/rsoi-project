<?php
include "./utils.php";

if (!is_dir(".well-known")) {
    mkdir(".well-known");
}

$jwks = file_get_contents(curl("http://identity_provider:80/.well-known/jwks.json"));
file_put_contents(".well-known/jwks.json", $jwks);