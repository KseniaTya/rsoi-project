<?php
    $host = "postgres";
    $login = "postgres";
    $password = "postgres";
    $db = "postgres";
    // подключение к бд
    $connect = pg_connect("host=$host port=5432 dbname=$db user=$login password=$password");
    create_tables($connect);

    function create_tables($connect){
        // получение списка таблиц из бд
        $result = pg_query($connect, "SELECT n.nspname, c.relname
            FROM pg_class c JOIN pg_namespace n ON n.oid = c.relnamespace
            WHERE c.relkind = 'r' AND n.nspname NOT IN('pg_catalog', 'information_schema');"
        );
        if (pg_fetch_assoc($result) == []) {
            pg_query($connect, "CREATE TABLE reservation
                (
                    id              SERIAL PRIMARY KEY,
                    reservation_uid uuid UNIQUE NOT NULL,
                    username        VARCHAR(80) NOT NULL,
                    book_uid        uuid        NOT NULL,
                    library_uid     uuid        NOT NULL,
                    status          VARCHAR(20) NOT NULL
                        CHECK (status IN ('RENTED', 'RETURNED', 'EXPIRED')),
                    start_date      TIMESTAMP   NOT NULL,
                    till_date       TIMESTAMP   NOT NULL
                )"
            );

            pg_query($connect, "CREATE TABLE library
                (
                    id          SERIAL PRIMARY KEY,
                    library_uid uuid UNIQUE  NOT NULL,
                    name        VARCHAR(80)  NOT NULL,
                    city        VARCHAR(255) NOT NULL,
                    address     VARCHAR(255) NOT NULL
                );"
            );
            pg_query($connect, "CREATE TABLE books
                (
                    id        SERIAL PRIMARY KEY,
                    book_uid  uuid UNIQUE  NOT NULL,
                    name      VARCHAR(255) NOT NULL,
                    author    VARCHAR(255),
                    genre     VARCHAR(255),
                    condition VARCHAR(20) DEFAULT 'EXCELLENT'
                        CHECK (condition IN ('EXCELLENT', 'GOOD', 'BAD'))
                );"
            );
            pg_query($connect, "CREATE TABLE library_books
                (
                    book_id         INT REFERENCES books (id),
                    library_id      INT REFERENCES library (id),
                    available_count INT NOT NULL
                );"
            );

            pg_query($connect, "CREATE TABLE rating
                (
                    id       SERIAL PRIMARY KEY,
                    username VARCHAR(80) NOT NULL,
                    stars    INT         NOT NULL
                        CHECK (stars BETWEEN 0 AND 100)
                );"
            );

            pg_query($connect, "CREATE TABLE users
                (
                    id       SERIAL PRIMARY KEY,
                    profile VARCHAR(80) NOT NULL,
                    email VARCHAR(80) NOT NULL,
                    role VARCHAR(20) DEFAULT 'User'
                        CHECK (role IN ('User', 'Admin'))
                );"
            );

            pg_query($connect, "
                INSERT INTO library(id, library_uid, name, city, address)
                VALUES(1, '83575e12-7ce0-48ee-9931-51919ff3c9ee', 'Библиотека имени 7 Непьющих', 'Москва', '2-я Бауманская ул., д.5, стр.1');
            ");
            pg_query($connect, "
                INSERT INTO books(id, book_uid, name, author, genre, condition)
                VALUES(1, 'f7cdc58f-2caf-4b15-9727-f89dcc629b27', 'Краткий курс C++ в 7 томах', 'Бьерн Страуструп', 'Научная фантастика', 'EXCELLENT');
            ");
            pg_query($connect, '
                INSERT INTO library_books(book_id, library_id, available_count)
                VALUES(1, 1, 1);
            ');
            pg_query($connect, "
                INSERT INTO users(profile, email, role)
                VALUES('admin', 'admin@admin.ru', 'Admin');
            ");
        }
    }
