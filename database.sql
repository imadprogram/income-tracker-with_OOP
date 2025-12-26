CREATE DATABASE smart_wallet_oop

use smart_wallet_oop


CREATE TABLE income(
    id INT PRIMARY KEY AUTO_INCREMENT,
    amount DECIMAL(10,2) NOT NULL,
    description VARCHAR(255) ,
    date DATE DEFAULT (CURRENT_DATE),
    category VARCHAR(255) NOT NULL,
    user_id INT NOT NULL,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

CREATE TABLE expense(
    id INT PRIMARY KEY AUTO_INCREMENT,
    amount DECIMAL(10,2) NOT NULL,
    description VARCHAR(255) ,
    date DATE DEFAULT (CURRENT_DATE),
    category VARCHAR(255) NOT NULL,
    user_id INT NOT NULL,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);


CREATE TABLE users(
    id INT PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(50) NOT NULL,
    email VARCHAR(250) NOT NULL UNIQUE,
    password VARCHAR(250) NOT NULL
)


SELECT * FROM users

SELECT * FROM income

TRUNCATE expense

CREATE TABLE category(
    id INT PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(50) NOT NULL,
    type VARCHAR(50) NOT NULL
)

ALTER TABLE expense DROP COLUMN category

ALTER TABLE expense ADD COLUMN category_id INT

ALTER TABLE expense ADD FOREIGN KEY (category_id) REFERENCES category(id)

SELECT * FROM category

SELECT * FROM income

INSERT INTO income(amount , description , date , user_id , category_id) VALUES(23 ,'test', '23/02/2025', 1 , 2)

INSERT INTO category(name , type) VALUES('Salary', 'income'), 
('Freelance', 'income'), 
('Business', 'income'),
('Food', 'expense'), 
('Transport', 'expense'), 
('Shopping', 'expense'), 
('Entertainment', 'expense'), 
('Bills', 'expense'), 
('Other', 'expense');

SELECT income.* , category.name as category_name
        FROM income 
        LEFT JOIN category ON income.category_id = category.id
        WHERE income.user_id = 1
        ORDER BY date DESC