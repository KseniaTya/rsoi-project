<?php

require_once 'vendor/autoload.php';

use Firebase\JWT\JWT;
use Firebase\JWT\Key;
 /*   "email" => $_POST['email'],
    "profile" => $_POST['profile'],*/


class Tokenizer
{
    private $key;
    private $expirationMinutes = 120;

    public function __construct()
    {
        $this->key = file_get_contents(".well-known/private_key.txt");
    }

    public function generateToken($payload)
    {
        $payload['iat'] = date('now');
        $payload['exp'] = time() + ($this->expirationMinutes * 60);
        $jwt = JWT::encode($payload, $this->key, 'RS256');
        return $jwt;
    }

    public function checkToken($jwt)
    {
        try {
            $jwks = json_decode(file_get_contents(".well-known/jwks.json"));
            $jwk = $jwks->keys[0]->x5c[0];
            $decoded = JWT::decode($jwt, new Key($jwk, 'RS256'));

            return json_encode($decoded);
        } catch (\Firebase\JWT\ExpiredException $e) {
            return 'Token expired';
        } catch (\Firebase\JWT\SignatureInvalidException $e) {
            return 'Invalid signature';
        }
    }
}