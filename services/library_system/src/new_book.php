<?php

include("instruments/postgress_connect.php");
/** @var $connect - переменная из postgress_connect.php с текцщим подключением к бд */

$input = json_decode(file_get_contents('php://input'), TRUE );
$bookUid = $input['bookUid'] ?? null;
$name = $input['name'] ?? null;
$author = $input['author'] ?? null;
$genre = $input['genre'] ?? null;

$id = pg_fetch_all(pg_query($connect, "SELECT max(id) FROM books"))[0]['max'] + 1;

pg_query($connect, "
                INSERT INTO books(id, book_uid, name, author, genre, condition)
                VALUES('$id', '$bookUid', '$name', '$author', '$genre', 'EXCELLENT');
            ");

pg_query($connect, "
                INSERT INTO library_books(book_id, library_id, available_count)
                VALUES($id, 1, 1);
            ");


