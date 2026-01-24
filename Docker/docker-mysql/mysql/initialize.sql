CREATE USER IF NOT EXISTS 'data_user'@'%' IDENTIFIED BY 'data';
GRANT ALL PRIVILEGES ON * . * TO 'data_user'@'%';

#認証がうまくいかなかった時に実行
ALTER USER 'data_user'@'%'
IDENTIFIED WITH mysql_native_password
BY 'data';

CREATE DATABASE IF NOT EXISTS sosyage;
use sosyage;

DROP TABLE users;
DELETE FROM users;
CREATE TABLE IF NOT EXISTS users (
    user_id INT AUTO_INCREMENT PRIMARY KEY,
    user_name VARCHAR(255) NOT NULL UNIQUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

DROP TABLE items;
DELETE FROM items;
CREATE TABLE IF NOT EXISTS items (
    item_id INT PRIMARY KEY,
    item_name VARCHAR(255) CHARACTER SET utf8mb4,
    attack INT DEFAULT 1,
    defense INT DEFAULT 1,
    weight INT DEFAULT 1
);

insert into items (item_id, item_name, attack, defense, weight) values
(1, '神アイテム', 300, 210, 5),
(2, '激レアアイテム', 240, 100, 8),
(3, 'レアアイテム', 205, 60, 15),
(4, '普通のアイテム', 80, 35, 30),
(5, 'ゴミアイテム', 30, 10, 42);

CREATE TABLE items_user_0 (
            item_id INT PRIMARY KEY,
            item_name VARCHAR(255) CHARACTER SET utf8mb4,
            attack INT DEFAULT 1,
            defense INT DEFAULT 1,
            item_count INT DEFAULT 0,
        );
DELETE FROM items_user_0;
DROP TABLE IF EXISTS items_user_0;
