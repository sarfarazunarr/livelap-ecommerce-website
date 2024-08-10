<?php
require 'config.php';

try {

    // Create users table
    $pdo->exec("CREATE TABLE IF NOT EXISTS users (
        id INT AUTO_INCREMENT PRIMARY KEY,
        username VARCHAR(50) NOT NULL,
        email VARCHAR(100) NOT NULL UNIQUE,
        password VARCHAR(255) NOT NULL,
        verified BOOLEAN DEFAULT FALSE,
        account_type ENUM('client', 'vendor') NOT NULL DEFAULT 'client',
        address VARCHAR(255),
        phone_number VARCHAR(20),
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    )");

    // Create otps table
    $pdo->exec("CREATE TABLE IF NOT EXISTS otps (
        id INT AUTO_INCREMENT PRIMARY KEY,
        user_id INT NOT NULL,
        otp VARCHAR(6) NOT NULL,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        expires_at TIMESTAMP NULL,
        FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
    )");
    // Drop the trigger if it already exists
    $pdo->exec("DROP TRIGGER IF EXISTS before_otps_insert");

    // Create a trigger to set expires_at
    $pdo->exec("CREATE TRIGGER before_otps_insert
        BEFORE INSERT ON otps
        FOR EACH ROW
        SET NEW.expires_at = DATE_ADD(NEW.created_at, INTERVAL 1 HOUR)");

    $pdo->exec("CREATE TABLE IF NOT EXISTS stores (
    id INT AUTO_INCREMENT PRIMARY KEY,
    store_name VARCHAR(100) NOT NULL,
    description TEXT,
    address VARCHAR(255) NOT NULL,
    business_phone VARCHAR(20) NOT NULL,
    website VARCHAR(255),
    vendor_id INT NOT NULL,
    FOREIGN KEY (vendor_id) REFERENCES users(id) ON DELETE CASCADE
)");

    // Create products table
    $pdo->exec("CREATE TABLE IF NOT EXISTS products (
        id INT AUTO_INCREMENT PRIMARY KEY,
        store_id INT NOT NULL,
        vendor_id INT NOT NULL,
        name VARCHAR(255) NOT NULL,
        image TEXT,
        description TEXT,
        price DECIMAL(10, 2) NOT NULL,
        stock_quantity INT NOT NULL,
        brand VARCHAR(100),
        model VARCHAR(100),
        processor VARCHAR(100),
        ram VARCHAR(50),
        storage VARCHAR(100),
        display_size VARCHAR(50),
        graphics_card VARCHAR(100),
        operating_system VARCHAR(50),
        weight DECIMAL(5, 2),
        dimensions VARCHAR(100),
        color VARCHAR(50),
        battery_life VARCHAR(50),
        connectivity VARCHAR(255),
        camera VARCHAR(100),
        additional_features TEXT,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
        FOREIGN KEY (store_id) REFERENCES stores(id) ON DELETE CASCADE,
        FOREIGN KEY (vendor_id) REFERENCES users(id) ON DELETE CASCADE
    )");

    // Create cart table
    $pdo->exec("CREATE TABLE IF NOT EXISTS cart (
        id INT AUTO_INCREMENT PRIMARY KEY,
        user_id INT NOT NULL,
        product_id INT NOT NULL,
        quantity INT NOT NULL,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
        FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
        FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE CASCADE
    )");

    // Create orders table
    $pdo->exec("CREATE TABLE IF NOT EXISTS orders (
        id INT AUTO_INCREMENT PRIMARY KEY,
        user_id INT NOT NULL,
        total_amount DECIMAL(10, 2) NOT NULL,
        status ENUM('pending', 'processing', 'shipped', 'delivered', 'cancelled') NOT NULL DEFAULT 'pending',
        shipping_address TEXT NOT NULL,
        billing_address TEXT NOT NULL,
        payment_method VARCHAR(50) NOT NULL,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
        FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
    )");

    // Create order_items table
    $pdo->exec("CREATE TABLE IF NOT EXISTS order_items (
        id INT AUTO_INCREMENT PRIMARY KEY,
        order_id INT NOT NULL,
        product_id INT NOT NULL,
        quantity INT NOT NULL,
        price DECIMAL(10, 2) NOT NULL,
        FOREIGN KEY (order_id) REFERENCES orders(id) ON DELETE CASCADE,
        FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE CASCADE
    )");

    // Create a trigger to update the total price in the orders table
    $pdo->exec("CREATE TRIGGER update_order_total AFTER INSERT ON order_items
    FOR EACH ROW
    BEGIN
        UPDATE orders
        SET total_amount = (
            SELECT SUM(quantity * price)
            FROM order_items
            WHERE order_id = NEW.order_id
        )
        WHERE id = NEW.order_id;
    END");




    echo "Tables and trigger created successfully.";
} catch (PDOException $e) {
    echo "Error creating tables: " . $e->getMessage();
}
?>