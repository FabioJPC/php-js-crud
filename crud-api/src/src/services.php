<?php

require_once __DIR__ . '/validation.php';
require_once __DIR__ . '/data.php';

function getAllProducts(): array
{
    $data = getProducts();
    return ['products' => $data['products']];
}

function createProduct(?array $input): array
{
    if (!is_array($input)) {
        return ['error' => 'Invalid JSON body', 'status' => 400];
    }

    $error = validateRequiredFields($input, ['name', 'age', 'email']);
    if ($error) {
        return ['error' => $error, 'status' => 400];
    }

    $error = validateProductFields($input);
    if ($error) {
        return ['error' => $error, 'status' => 400];
    }

    $user = insertProduct([
        'name' => trim($input['name']),
        'age' => (int) $input['age'],
        'email' => $input['email'],
    ]);

    if($user) {
        return ['data' => $user, 'status' => 201];
    }

    return ['data' => null, 'status' => 503];
}

function editProduct(?int $id, ?array $input, bool $partial = false): array
{
    if ($id === null) {
        return ['error' => 'Product id is required', 'status' => 400];
    }

    if (!is_array($input)) {
        return ['error' => 'Invalid JSON body', 'status' => 400];
    }

    if (!$partial) {
        $error = validateRequiredFields($input, ['name', 'age', 'email']);
        if ($error) {
            return ['error' => $error, 'status' => 400];
        }
    }

    $error = validateProductFields($input);
    if ($error) {
        return ['error' => $error, 'status' => 400];
    }

    $allowed = ['name', 'age', 'email'];
    $fields = array_intersect_key($input, array_flip($allowed));

    if (isset($fields['name'])) {
        $fields['name'] = trim($fields['name']);
    }

    if (isset($fields['age'])) {
        $fields['age'] = (int) $fields['age'];
    }

    $user = updateProduct($id, $fields);

    if ($user === null) {
        return ['error' => 'Product not found', 'status' => 404];
    }

    return ['data' => $user, 'status' => 200];
}

function removeProduct(?int $id): array
{
    if ($id === null) {
        return ['error' => 'Product id is required', 'status' => 400];
    }

    $user = deleteProduct($id);

    if ($user === null) {
        return ['error' => 'Product not found', 'status' => 404];
    }

    return ['data' => ['deleted' => $user], 'status' => 200];
}