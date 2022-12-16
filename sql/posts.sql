CREATE TABLE posts (
    id int PRIMARY KEY AUTO_INCREMENT,
    user_id int NOT NULL,
    description varchar(5000) NOT NULL,
    image_location varchar(255) NOT NULL,
    date_posted varchar(255) NOT NULL,
    unique_key varchar(255) NOT NULL,
    is_archived int(10) NOT NULL
);