<?php
    function getDatabase() : PDO {
        $connection = new PDO('sqlite:/var/www/html/data/database.db');

        $connection->setAttribute(
            pdo::ATTR_ERRMODE, 
            pdo::ERRMODE_EXCEPTION
        );

        return $connection;
    }