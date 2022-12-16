CREATE TABLE products (
    id int PRIMARY KEY AUTO_INCREMENT,
    name varchar(255) NOT NULL,
    description varchar(255) NOT NULL,
    brand varchar(255) NOT NULL,
    price varchar(255) NOT NULL,
    date_added varchar(255) NOT NULL,
    stocks int NOT NULL,
    image_location varchar(255) NOT NULL,
    unique_key varchar(255) NOT NULL,
    is_archived int(10) NOT NULL
);