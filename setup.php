<?php
require 'config.php';

try {

    // Create users table
    $pdo->exec("CREATE TABLE IF NOT EXISTS users (
        id INT AUTO_INCREMENT PRIMARY KEY,
        rollNumber INT,
        username VARCHAR(50) NOT NULL,
        email VARCHAR(100) NOT NULL UNIQUE,
        password VARCHAR(255) NOT NULL,
        verified BOOLEAN DEFAULT FALSE,
        phone_number VARCHAR(20),
        isActive BOOLEAN DEFAULT FALSE,
        active_till TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
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

    // Create transactions table
    $pdo->exec("CREATE TABLE IF NOT EXISTS transactions (
        id INT AUTO_INCREMENT PRIMARY KEY,
        tid INT,
        amount DECIMAL(10, 2) NOT NULL,
        user_id INT NOT NULL,
        payment_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        subscription_type VARCHAR(50) NOT NULL,
        next_payment_date TIMESTAMP NULL,
        FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
    )");

    // Create cards table
    $pdo->exec("CREATE TABLE IF NOT EXISTS cards (
        id INT AUTO_INCREMENT PRIMARY KEY,
        user_id INT NOT NULL,
        card_number VARCHAR(16) NOT NULL,
        expiry_date TEXT NOT NULL,
        cvc VARCHAR(4) NOT NULL,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
    )");

    echo "Tables and trigger created successfully.";
} catch (PDOException $e) {
    echo "Error creating tables: " . $e->getMessage();
}
?>