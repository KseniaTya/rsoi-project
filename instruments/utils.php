<?php
// проверка здоровья сервиса
function check_health($url){
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    $html = curl_exec($ch);
    curl_close($ch);
    return $html;
}
// проверка здоровья списка сервисов
function services_is_running($arr){
    foreach ($arr as $domain) {
        if (check_health("http://$domain:80/manage/health") != "200 ОК") {
            throw new RuntimeException("kekw");
        }
    }
}

// get запрос
function curl($url, $head_vars = []){
    $domain = explode(":",
        explode("://", $url)[1]
    )[0];
    if(check_health("http://$domain:80/manage/health") != "200 ОК"){
        $error = "";
        switch ($domain){
            case "rating_system":
                $error = "Bonus Service unavailable";
                break;
        }
        throw new RuntimeException($error);
    }
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $head_vars);
    $html = curl_exec($ch);
    curl_close($ch);
    return $html;
}
// post запрос
function curl_post($url, $post_vars = "", $head_vars = [], $timeout = 0){
    $curl = curl_init($url);

    curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "POST");
    curl_setopt($curl, CURLOPT_POSTFIELDS, $post_vars);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($curl, CURLOPT_HTTPHEADER, [
            'Content-Type: application/json',
            'Content-Length: ' . strlen($post_vars)
        ]);
    curl_setopt($curl, CURLOPT_HTTPHEADER, $head_vars);
    $html = curl_exec($curl);
    curl_close($curl);
    return $html;
}
// проверить массив на наличие null элементов
function validate($array, $func, $err_code){
    $result = [];
    foreach ($array as $k => $v){
        $result += $func($k, $v, "variable isnt set");
    }
    if($result != []){
        http_response_code($err_code);
        echo json_encode($result);
        exit;
    }
}
function validate_null($k, $v, $message):array{
    return $v != null ? [] : ["$k" => "$message"];
}
// преобразование json в utf-8 (для того, чтобы убрать кракозябры вместо кириллицы в браузере)
function normJsonStr($str){
    $str = preg_replace_callback('/\\\\u([a-f0-9]{4})/i', fn($m) => chr(hexdec($m[1])-1072+224), $str);
    return iconv('cp1251', 'utf-8', $str);
}

require_once __DIR__ . '/../vendor/autoload.php';
use LeoCarmo\CircuitBreaker\CircuitBreaker;
use LeoCarmo\CircuitBreaker\Adapters\RedisAdapter;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;

function saveStatistic($message){
    $conf = new RdKafka\Conf();
    $conf->set('metadata.broker.list', 'kafka:9092');
    $producer = new RdKafka\Producer($conf);
    $topic = $producer->newTopic('logs');

    try {
        $jwt= getallheaders()['token'] ?? "";
        $jwks = json_decode(file_get_contents(".well-known/jwks.json"));
        $jwk = $jwks->keys[0]->x5c[0];
        $decoded = JWT::decode($jwt, new Key($jwk, 'RS256'));
        $username = $decoded->profile;
    } catch (Exception $e) {
        $username = '__Unauthorized';
    }

    $data = json_encode([
        'message' => $message,
        'service' => $_SERVER['HTTP_HOST'],
        'token' => $username
    ]);
    $topic->produce(RD_KAFKA_PARTITION_UA, 0, $data);

    $producer->flush(1000);
}


// возьмем название файла как название сервиса CircuitBreaker'а
$stack = debug_backtrace();

if($stack[0]['file'] !== "/var/www/html/instruments/load_jwks.php"){

// Connect to redis
    $redis = new \Redis();

    $redis->connect('redis', 6379);

    $adapter = new RedisAdapter($redis, 'my-product');


// Set redis adapter for CB
    $circuit = new CircuitBreaker($adapter, end($stack)['args'][1]);

// Configure settings for CB
    $circuit->setSettings([
        'timeWindow' => 30, // Time for an open circuit (seconds)
        'failureRateThreshold' => 3, // Fail rate for open the circuit
        'intervalToHalfOpen' => 10,  // Half open time (seconds)
    ]);

// Check circuit status for service
    if (! $circuit->isAvailable()) {
        die('Circuit is not available!');
    }
}
