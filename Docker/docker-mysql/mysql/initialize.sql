CREATE USER IF NOT EXISTS 'data_user'@'%' IDENTIFIED BY 'data';
GRANT ALL PRIVILEGES ON * . * TO 'data_user'@'%';

select * from users where id = 5;
select * from users order by level desc limit 3;
select create_date, count(id) from battle_log where create_date = '2025-01-05' group by create_date;
select u.id, u.name, u.job_id, j.name from users u left outer join jobs j on u.job_id = j.id order by u.id;
select user_id, count(user_id) as result_win from battle_log where result_win = TRUE group by user_id;
select u.job_id, count(b.userid) as result_win from battle_log b left outer join users u where result_win = TRUE group by u.job_id;

CREATE TABLE IF NOT EXISTS cards (
    id INT PRIMARY KEY,
    name VARCHAR(255)
);

CREATE TABLE IF NOT EXISTS user_cards (
    id INT PRIMARY KEY,
    user_id INT,
    card_id INT
);

CREATE TABLE IF NOT EXISTS user_decks (
    user_id INT,
    deck_id INT,
    user_card_id INT PRIMARY KEY
);

insert into cards (id, name) values
(1, N'normal_taro'),
(2, N'super_taro'),
(3, N'hyper_taro'),
(4, N'mega_taro'),
(5, N'giga_taro'),
(6, N'ultimate_taro');

insert into user_cards (id, user_id, card_id) values
(1, 1, 1),
(2, 1, 2),
(3, 1, 3),
(4, 1, 4),
(5, 1, 5),
(6, 1, 6);

insert into user_decks (user_id, deck_id, user_card_id) values
(1, 1, 1),
(1, 1, 2),
(1, 1, 3),
(1, 2, 4),
(1, 2, 5),
(1, 2, 6);

select * from cards;
select * from user_cards;
select * from user_decks;
select ud.user_id, ud.deck_id, ud.user_card_id, uc.card_id, c.name from user_decks ud
left outer join user_cards uc on ud.user_card_id = uc.id
left outer join cards c on uc.card_id = c.id;

CREATE DATABASE sosyage;
use sosyage;

CREATE TABLE IF NOT EXISTS items (
    item_id INT PRIMARY KEY,
    item_name VARCHAR(255) CHARACTER SET utf8mb4,
    item_count INT,
    weight INT DEFAULT 1
);

insert into items (item_id, item_name, item_count, weight) values
(1, '神アイテム', 1, 1),
(2, '激レアアイテム', 3, 3),
(3, 'レアアイテム', 6, 10),
(4, '普通のアイテム', 12, 30),
(5, 'ゴミアイテム', 20, 56);
