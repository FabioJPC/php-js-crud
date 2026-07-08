<?php

require_once __DIR__ . '/database.php';

function loadData(string $dataFile): array
{
    return json_decode(file_get_contents($dataFile), true);
}

function saveData(string $dataFile, array $data): void
{
    file_put_contents($dataFile, json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
}

function findUser(int $id): ?array {
    $db = getdatabase();

    $stmt = $db->prepare(
        "SELECT * FROM users
        WHERE id = :id"
    );

    $stmt->bindParam(":id", $id);

    $stmt->execute();

    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    return $user ?: null;
}

function getUsers(): array {
    try {
        $db = getdatabase();

        $stmt = $db->query("SELECT * FROM users");

        return ['users' => $stmt->fetchAll(PDO::FETCH_ASSOC)];
    }
    catch (Throwable $e) {
        logError("A database error has ocurred.", $e);
        return [];
    }
}

function insertUser(array $input): ?array
{
    try {
        $db = getDatabase();
    
        $stmt = $db->prepare(
            "INSERT INTO user (name, age, email)
            values (:name, :age, :email)"
        );

        $stmt->bindParam(":name", $input['name'], PDO::PARAM_STR);
        $stmt->bindParam(":age", $input['age'], PDO::PARAM_INT);
        $stmt->bindParam(":email", $input['email'], PDO::PARAM_STR);

        $stmt->execute();

        $id = $db->lastInsertId();

        $user = findUser($id);

        return $user;
    }
    catch(PDOException $e) {
        //TODO: log
        return null;
    }
}

function updateUser(int $id, array $fields): ?array
{
    $db = getDatabase();

    $user = findUser($id);

    if ($user === null) {
        return null;
    }

    if (empty($fields)) {
        return $user;
    }

    $sets = [];

    foreach ($fields as $key => $value) {
        $sets[] = "{$key} = :{$key}";
    }

    $setString = implode(",", $sets);

    $stmt = $db->prepare(
        "UPDATE users SET
        {$setString}
        WHERE id = :id"
    );

    foreach ($fields as $key => $value) {
        $stmt->bindParam("{$key}", $value);
    }

    $stmt->bindParam(":id", $user['id'], PDO::PARAM_INT);

    $stmt->execute();

    return findUser($id);
}

function deleteUser(int $id): ?array
{
    try {

        $db = getdatabase();
        
        $user = findUser($id);

        if ($user === null) {
            return null;
        }
        
        $stmt = $db->prepare(
            "DELETE FROM users
            WHERE id = :id"
        );

        $stmt->bindParam(":id", $user['id']);

        $stmt->execute();

        return $user;
    }
    catch(PDOException $e) {
        //TODO: log
        return null;
    }
}

function logError(string $message, ?Throwable $exception = null) {
    $logFile = __DIR__ . "/../logs/databaseLog.log";

    if (!is_dir($logFile)) {
        mkdir($logFile, 0777, true);
    }

    $log = sprintf("[%s]: %s: ", date('d-m-Y H:i:s'), $message);

    if ($exception) {
        $log .= sprintf(
            " |%s: %s| Arquivo: %s:%d",
            get_class($exception),
            $exception->getMessage(),
            $exception->getFile(),
            $exception->getLine(),
        ); 
    }

    error_log($log . PHP_EOL, 3, $logFile);
}