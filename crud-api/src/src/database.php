<?php
    function getDatabase() : PDO {
        $connection = new PDO('sqlite:/var/www/data/database.db');

        $connection->setAttribute(
            pdo::ATTR_ERRMODE, 
            pdo::ERRMODE_EXCEPTION
        );

        return $connection;
    }