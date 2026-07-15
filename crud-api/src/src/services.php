<?php

require_once __DIR__ . '/validation.php';
require_once __DIR__ . '/data.php';

function getProducts(?string $search = null): array
{
    if ($search === null || $search === '') {
        $data = fetchProducts();
    } else {
        $data = searchProducts($search);
    }
    return ['products' => $data['products']];
}

function createProduct(?array $input): array
{
    if (!is_array($input)) {
        return ['error' => 'Invalid JSON body', 'status' => 400];
    }

    $error = validateRequiredFields($input, ['name', 'category', 'price', 'stock']);
    if ($error) {
        return ['error' => $error, 'status' => 400];
    }

    $error = validateProductFields($input);
    if ($error) {
        return ['error' => $error, 'status' => 400];
    }

    if (isset($input['price'])) {
        $price['price'] = str_replace(',', '.', $input['price']);
    }

    $product = insertProduct([
        'name' => trim($input['name']),
        'category' => trim($input['category']),
        'price' => (float) $input['price'],
        'stock' => (int) $input['stock']
    ]);

    if($product) {
        return ['data' => $product, 'status' => 201];
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
        $error = validateRequiredFields($input, ['name', 'category', 'price', 'stock']);
        if ($error) {
            return ['error' => $error, 'status' => 400];
        }
    }

    $error = validateProductFields($input);
    if ($error) {
        return ['error' => $error, 'status' => 400];
    }

    $allowed = ['name', 'category', 'price', 'stock'];
    $fields = array_intersect_key($input, array_flip($allowed));

    if (isset($fields['name'])) {
        $fields['name'] = trim($fields['name']);
    }

    if (isset($fields['category'])) {
        $fields['category'] = trim($fields['category']);
    }

    if (isset($fields['price'])) {
        $fields['price'] = (float) $fields['price'];
    }

    if (isset($fields['stock'])) {
        $fields['stock'] = (int) $fields['stock'];
    }

    $product = updateProduct($id, $fields);

    if ($product === null) {
        return ['error' => 'Product not found', 'status' => 404];
    }

    return ['data' => $product, 'status' => 200];
}

function removeProduct(?int $id): array
{
    if ($id === null) {
        return ['error' => 'Product id is required', 'status' => 400];
    }

    $product = deleteProduct($id);

    if ($product === null) {
        return ['error' => 'Product not found', 'status' => 404];
    }

    return ['data' => ['deleted' => $product], 'status' => 200];
}