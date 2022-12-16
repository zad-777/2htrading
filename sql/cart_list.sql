CREATE TABLE cart_list (
    id int PRIMARY KEY AUTO_INCREMENT,
    user_id int NOT NULL,
    product_id int NOT NULL,
    quantity int NOT NULL,
    unique_key varchar(255) NOT NULL,
    is_archived int(10) NOT NULL
);