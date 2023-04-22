<?php
// задание настроек ассиметричного шифрования
$private_key = openssl_pkey_new(
    array(
        "private_key_bits" => 2048,
        "private_key_type" => OPENSSL_KEYTYPE_RSA
    ));
// генерация открытого и закрытого ключа
openssl_pkey_export($private_key, $pkey);
// извлечение открытого ключа
$public_key = openssl_pkey_get_details($private_key);

// Формирование массива ключей jwks
$jwks = array(
    "keys" => array(
        array(
            "kty" => "RSA",
            "alg" => "RSA256",
            "x5c" => [$public_key['key']],
        )
    )
);

// создаем папку
if (!is_dir(".well-known")) {
    mkdir(".well-known");
}

// вывод jwks в файл
file_put_contents(".well-known/jwks.json", json_encode($jwks, JSON_PRETTY_PRINT));

// вывод приватного ключа в файл
file_put_contents(".well-known/private_key.txt", $pkey);
