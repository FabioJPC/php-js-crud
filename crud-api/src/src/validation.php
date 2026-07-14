<?php

function validateRequiredFields(array $input, array $fields): ?string
{
    $missing = [];

    foreach ($fields as $field) {
        if (!isset($input[$field])) {
            $missing[] = $field;
        }
    }

    if (!empty($missing)) {
        return implode(', ', $missing) . ' are required';
    }

    return null;
}

function validateProductFields(array $input): ?string
{
    if (isset($input['name'])) {
        $name = trim($input['name']);

        if ($name === '') {
            return 'Name cannot be empty';
        }

        if (strlen($name) > 200) {
            return 'Name must be at most 200 characters';
        }
    }


    if (isset($input['price'])) {
        $price = str_replace(',', '.', $input['price']);

        if (!is_numeric($price)) {
            return 'Price must be a number';
        }

        $price = (float) $price;

        if ($price < 0) {
            return 'Price cannot be negative';
        }

        if (round($price, 2) != $price) {
           return 'Price can have maximum 2 decimal places';
        }   
    }

    if (isset($input['stock'])) {
        $stock = $input['stock'];
        if (!is_numeric($stock)) {
            return "Stock must be a number";
        }

        if (filter_var($stock, FILTER_VALIDATE_INT) === false) {
            return "Stock must be an integer";
        }

        if ($stock < 0) {
            return "Stock can't be negative";
        }
    }

    if (isset($input['category'])) {
        $category = trim($input['category']);

        if ($category == "") {
            return "Category cannot be empty";
        }

        if (strlen($category) > 100) {
            return "Category must be at most 100 characters";
        }
    }

    return null;
}