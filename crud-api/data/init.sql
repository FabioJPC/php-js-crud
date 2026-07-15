CREATE TABLE products (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    name VARCHAR(200) NOT NULL,
    price DECIMAL(8, 2) NOT NULL,
    stock INTEGER NOT NULL,
    category VARCHAR(100) NOT NULL
);

INSERT INTO products (name, price, stock, category) VALUES
('Wireless Keyboard', 89.90, 25, 'Electronics'),
('Gaming Mouse', 149.99, 40, 'Electronics'),
('USB-C Charger 65W', 119.50, 30, 'Accessories'),
('Office Chair', 799.90, 12, 'Furniture'),
('Notebook Stand', 59.90, 50, 'Accessories'),
('Mechanical Keyboard', 349.99, 15, 'Electronics');