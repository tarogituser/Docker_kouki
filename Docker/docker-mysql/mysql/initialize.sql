CREATE USER IF NOT EXISTS 'data_user'@'%' IDENTIFIED BY 'data';
GRANT ALL PRIVILEGES ON * . * TO 'data_user'@'%';

CREATE DATABASE IF NOT EXISTS sosyage;
use sosyage;

CREATE TABLE IF NOT EXISTS items (
    item_id INT PRIMARY KEY,
    item_name VARCHAR(255) CHARACTER SET utf8mb4,
    item_count INT,
    weight INT DEFAULT 1
);

insert into items (item_id, item_name, item_count, weight) values
(1, '神アイテム', 1, 5),
(2, '激レアアイテム', 3, 10),
(3, 'レアアイテム', 6, 25),
(4, '普通のアイテム', 12, 30),
(5, 'ゴミアイテム', 20, 56);
