<?php declare(strict_types=1);
/** @var LeoCarmo\CircuitBreaker\CircuitBreaker $circuit */
include "instruments/utils.php";

try {
    header('Content-Type: application/json; charset=utf-8');
    $token= getallheaders()['token'] ?? "";
    $token = urlencode($token);

    $reservations = json_decode(curl("http://reservation_system:80/get_reservations", ["token: $token"]));
    $result = array_map(function ($reservation) use ($token) {
        $book = json_decode(curl("http://library_system:80/get_book_by_uid?book_uid=".$reservation->book_uid, ["token: $token"]));
        $library = json_decode(curl("http://library_system:80/get_library_by_uid?library_uid=".$reservation->library_uid, ["token: $token"]));
        return [
            "reservationUid" => $reservation -> reservation_uid,
            "status" => $reservation -> status,
            "startDate" => explode(" ",$reservation -> start_date)[0],
            "tillDate" => explode(" ",$reservation -> till_date)[0],
            "book" => [
                "bookUid" => $book -> book_uid,
                "name" => $book -> name,
                "author" => $book -> author,
                "genre" => $book -> genre
            ],
            "library" => [
                "libraryUid" => $library -> library_uid,
                "name" => $library -> name,
                "address" => $library -> address,
                "city" => $library -> city
            ]
        ];
    }, $reservations);
    $circuit->success();

    echo json_encode($result);
}
catch (RuntimeException $e){
    $circuit->failure();
    http_response_code(503);
    echo "[]";
}


