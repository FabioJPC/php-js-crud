<?php
    function getDatabase() : PDO {
        $connection = new PDO('sqlite:/var/www/data/database.sqlite');

        $connection->setAttribute(
            pdo::ATTR_ERRMODE, 
            pdo::ERRMODE_EXCEPTION
        );

        return $connection;
    }