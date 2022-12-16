CREATE TABLE rooms (
    id int PRIMARY KEY AUTO_INCREMENT,
    sender_id int NOT NULL,
    receiver_id int NOT NULL
    unique_key varchar(255) NOT NULL,
    is_archived int(10) NOT NULL
);