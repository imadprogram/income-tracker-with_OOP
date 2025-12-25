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

SELECT * FROM expense

TRUNCATE income

SELECT 
        income.amount , 
        income.category ,
        expense.amount ,
        expense.category
FROM income
INNER JOIN expense
ON income.category = 'transport' 