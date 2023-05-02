<?php declare(strict_types=1);
/** @var LeoCarmo\CircuitBreaker\CircuitBreaker $circuit */
include "instruments/utils.php";

saveStatistic('Попытка вернуть книгу');

header('Content-Type: application/json; charset=utf-8');
$input = json_decode( file_get_contents('php://input'), TRUE );

$condition = $input['condition'] ?? null;
$date = $input['date'] ?? null;
$token = getallheaders()['token'] ?? null;

// сдать книгу и получить данные о книге из резервации
validate(compact('condition', 'date', 'token'), "validate_null", 400);
try{
    services_is_running(["reservation_system", "library_system", "rating_system"]);

    $token = urlencode($token);
    $date = urlencode($input['date']);

    // получаем старые данные резервации
    $old_reservations = json_decode(curl("http://gateway_service:80/api/v1/reservations", ['token: '.getallheaders()['token']]));
    $old_reservations = array_filter($old_reservations, fn($x) => $x->reservationUid == $reservationUid);
    $old_reservation = $old_reservations[array_key_first($old_reservations)]??null;
    if(!isset($old_reservation) || $old_reservation->status != "RENTED"){
        http_response_code(204);
    }else{

        $reservationData = curl("http://reservation_system:80/return_book?reservationUid=$reservationUid&date=$date", ["token: $token"]);

        if($reservationData == "[]"){
            http_response_code(404);
        }
        else{
            $arr = json_decode($reservationData);
            // увеличить счетчик доступных книг
            curl("http://library_system:80/count_book?book_uid=$arr->book_uid&library_uid=$arr->library_uid&count=1", ["token: $token"]);

            $book = json_decode(curl("http://library_system:80/get_book_by_uid?book_uid=$arr->book_uid", ["token: $token"]));
            $stars = 0;
            if($arr->status == 'EXPIRED'){
                $stars -= 10;
            }
            if($book->condition != $condition){
                $stars -= 10;
                curl("http://library_system:80/change_condition_book?book_uid=$arr->book_uid&condition=$condition", ["token: $token"]);
            }
            if($stars == 0){
                $stars+= 1;
            }
            curl("http://rating_system:80/change_rating?stars=$stars", ["token: $token"]);

            $circuit->success();
            saveStatistic('Книга возвращена');

            http_response_code(204);
            echo ("
                \"condition\": \"$condition\",
                \"date\": \"$arr->till_date\"
            ");
        }
    }
} catch (RuntimeException $e){
    saveStatistic('Провал вернуть книгу');

    $circuit->failure();

    $json = urlencode(json_encode([
        "token" => getallheaders()['token'],
        "date" => $input['date'],
        "condition" => $input['condition'],
        "reservationUid" => $reservationUid
    ]));
    http_response_code(204);
    echo "{}";
    exec("php /var/www/html/src/reconnect/reconnect_post_return_book.php $json > /dev/null &");
}
