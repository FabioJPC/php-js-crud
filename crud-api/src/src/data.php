<?php

require_once __DIR__ . '/database.php';

function findProduct(int $id): ?array 
{
    $db = getdatabase();

    $stmt = $db->prepare(
        "SELECT * FROM products
        WHERE id = :id"
    );

    $stmt->bindValue(":id", $id);

    $stmt->execute();

    $product = $stmt->fetch(PDO::FETCH_ASSOC);

    return $product ?: null;

}

function fetchProducts(): array 
{
    $db = getdatabase();

    $stmt = $db->query("SELECT * FROM products");

    return ['products' => $stmt->fetchAll(PDO::FETCH_ASSOC)];

}

function searchProducts(string $search): array 
{
    $db = getdatabase();

    $stmt = $db->prepare(
        "SELECT * 
        FROM products
        WHERE name LIKE :search
        OR category LIKE :search
        ORDER BY name"
        );
    $stmt->bindValue(":search", "%{$search}%", PDO::PARAM_STR);
    $stmt->execute();

    return ['products' => $stmt->fetchAll(PDO::FETCH_ASSOC)];
}

function insertProduct(array $input): ?array
{
    $db = getDatabase();

    $stmt = $db->prepare(
        "INSERT INTO products (name, category, price, stock)
        values (:name, :category, :price, :stock)"
    );

    $stmt->bindValue(":name", $input['name'], PDO::PARAM_STR);
    $stmt->bindValue(":category", $input['category'], PDO::PARAM_STR);
    $stmt->bindValue(":price", $input['price'], PDO::PARAM_STR);
    $stmt->bindValue(":stock", $input['stock'], PDO::PARAM_INT);

    $stmt->execute();

    $id = $db->lastInsertId();

    $product = findProduct($id);

    return $product;
}

function updateProduct(int $id, array $fields): ?array
{

    $db = getDatabase();

    $product = findProduct($id);

    if ($product === null) {
        return null;
    }

    if (empty($fields)) {
        return $product;
    }

    $sets = [];

    foreach ($fields as $key => $value) {
        $sets[] = "{$key} = :{$key}";
    }

    $setString = implode(",", $sets);

    $stmt = $db->prepare(
        "UPDATE products SET
        {$setString}
        WHERE id = :id"
    );

    foreach ($fields as $key => $value) {
        $stmt->bindValue(":{$key}", $value);
    }

    $stmt->bindValue(":id", $product['id'], PDO::PARAM_INT);

    $stmt->execute();
    
    return findProduct($id);
}

function deleteProduct(int $id): ?array
{
    $db = getdatabase();
    
    $product = findProduct($id);

    if ($product === null) {
        return null;
    }
    
    $stmt = $db->prepare(
        "DELETE FROM products
        WHERE id = :id"
    );

    $stmt->bindValue(":id", $product['id']);

    $stmt->execute();

    return $product;
}

