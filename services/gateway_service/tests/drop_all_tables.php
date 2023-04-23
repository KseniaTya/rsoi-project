<?php
include("instruments/postgress_connect.php");
function drop_tables($connect){
    pg_query($connect, "DROP TABLE reservation CASCADE");
    pg_query($connect, "DROP TABLE library CASCADE");
    pg_query($connect, "DROP TABLE books CASCADE");
    pg_query($connect, "DROP TABLE library_books CASCADE");
    pg_query($connect, "DROP TABLE rating CASCADE");
    pg_query($connect, "DROP TABLE users CASCADE");
}

drop_tables($connect);

?> all tables cleared!