CREATE TABLE orders (
    id int PRIMARY KEY AUTO_INCREMENT,
    user_id int NOT NULL,
    product_id int NOT NULL,
    quantity int NOT NULL,
    total varchar(255) NOT NULL,
    date_ordered varchar(255) NOT NULL,
    date_arrived varchar(255) NOT NULL,
    image_location varchar(255) NOT NULL,
    status int(10) NOT NULL,
    payment_method int(10) NOT NULL,
    unique_key varchar(255) NOT NULL,
    is_archived int(10) NOT NULL
);