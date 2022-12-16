CREATE TABLE users (
    id int PRIMARY KEY AUTO_INCREMENT, 
    first_name varchar(255) NOT NULL,
    middle_name varchar(255) NOT NULL,
    last_name varchar(255) NOT NULL,
    contact_number varchar(255) NOT NULL,
    home_address varchar(255) NOT NULL,
    email_address varchar(255) NOT NULL,
    password varchar(255) NOT NULL,
    type int(10) NOT NULL,
    unique_key varchar(255) NOT NULL,
    is_archived int(10) NOT NULL
);