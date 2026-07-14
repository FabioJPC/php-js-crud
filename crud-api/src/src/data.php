<?php

require_once __DIR__ . '/database.php';

function findProduct(int $id): ?array 
{

    $db = getdatabase();

    $stmt = $db->prepare(
        "SELECT * FROM products
        WHERE id = :id"
    );

    $stmt->bindParam(":id", $id);

    $stmt->execute();

    $product = $stmt->fetch(PDO::FETCH_ASSOC);

    return $product ?: null;

}

function getProducts(): array 
{
    $db = getdatabase();

    $stmt = $db->query("SELECT * FROM products");

    return ['products' => $stmt->fetchAll(PDO::FETCH_ASSOC)];

}

function insertProduct(array $input): ?array
{
    $db = getDatabase();

    $stmt = $db->prepare(
        "INSERT INTO users (name, age, email)
        values (:name, :age, :email)"
    );

    $stmt->bindParam(":name", $input['name'], PDO::PARAM_STR);
    $stmt->bindParam(":age", $input['age'], PDO::PARAM_INT);
    $stmt->bindParam(":email", $input['email'], PDO::PARAM_STR);

    $stmt->execute();

    $id = $db->lastInsertId();

    $user = findProduct($id);

    return $user;
}

function updateProduct(int $id, array $fields): ?array
{

    $db = getDatabase();

    $user = findProduct($id);

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
    
    return findProduct($id);
}

function deleteProduct(int $id): ?array
{
    $db = getdatabase();
    
    $user = findProduct($id);

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

