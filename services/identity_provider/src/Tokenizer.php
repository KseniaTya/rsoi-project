<?php

require_once 'vendor/autoload.php';

use \Firebase\JWT\JWT;
use Firebase\JWT\Key;
 /*   "email" => $_POST['email'],
    "profile" => $_POST['profile'],*/

class Tokenizer{
    private $key = "my-secret-key";
    private $expirationMinutes = 1;

    public function generateToken($payload){
        $payload['iat'] = date('now');
        $payload['exp'] = time() + ($this->expirationMinutes * 60);
        $jwt = JWT::encode($payload, $this->key, 'HS256');
        return $jwt;
    }

    public function checkToken($jwt){
        try {
            $decoded = JWT::decode($jwt, new Key($this->key, 'HS256'));
            return json_encode($decoded);
        } catch (\Firebase\JWT\ExpiredException $e) {
            return 'Token expired';
        } catch (\Firebase\JWT\SignatureInvalidException $e) {
            return 'Invalid signature';
        }
    }
}