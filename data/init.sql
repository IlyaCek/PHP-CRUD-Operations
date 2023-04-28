CREATE DATABASE IF NOT EXISTS my_database;
USE my_database;


CREATE TABLE IF NOT EXISTS users (

    id INT NOT NULL AUTO_INCREMENT,
    username VARCHAR(50) NOT NULL,
    password VARCHAR(255) NOT NULL,
    name VARCHAR(255) DEFAULT NULL,
    surname VARCHAR(255) DEFAULT NULL,
    age INT DEFAULT NULL,
    email VARCHAR(255) DEFAULT NULL,
    date_added TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    user_role ENUM('user', 'admin') NOT NULL DEFAULT 'user',
    PRIMARY KEY (id)
    );


INSERT INTO users (username, password, user_role)
VALUES ('admin', '$2y$10$7YGYfXiiLhbQySfv8G.lf.nb4ZAd6jtskMf1K1d.ZTJ1f3xbOUzFW', 'admin');


CREATE TABLE IF NOT EXISTS products (
    id INT NOT NULL AUTO_INCREMENT,
    name VARCHAR(255) NOT NULL,
    description TEXT DEFAULT NULL,
    price DECIMAL(10, 2) NOT NULL,
    quantity INT DEFAULT 0,
    image VARCHAR(255) DEFAULT NULL,
    date_added TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (id)
    );
INSERT INTO products (name, description, price, quantity, image)
VALUES
    ('Samsung s22', 'The Samsung Galaxy S22 specs are top-notch including a Snapdragon 8 Gen 1 chipset, 8GB RAM coupled with 128/256GB storage, and a 3700mAh battery with 25W charging speed. The phone sports a 6.1-inch Dynamic AMOLED display with an adaptive 120Hz refresh rate.', 599.99, 100, '../public/images/1682632867-Samsung-Galaxy-S22-Ultra.jpg'),
    ('iPhone 14', 'Apple''s iPhone 14 models are identical in design to the iPhone 13 models, featuring flat edges, an aerospace-grade aluminum enclosure, and a glass back that enables wireless charging. The iPhone 14 models come in blue, purple, midnight, starlight, and (PRODUCT)RED, plus Apple added a new yellow color in March.', 799.99, 50, '../public/images/1682453973-iphone-14-pro-model-unselect-gallery-2-202209_GEO_EMEA.jpg'),
    ('iPad', 'The iPad runs iOS and ships with several popular Apple and third-party apps preinstalled, including Messages, Mail, FaceTime, Music, Photos and the Safari web browser. Users can also download additional free and paid apps through the Apple App Store.', 499.99, 20, '../public/images/1682633015-ipad-pro.jpg');



