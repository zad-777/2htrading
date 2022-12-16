CREATE TABLE messages (
    id int PRIMARY KEY AUTO_INCREMENT,
    room_id int NOT NULL,
    sender_id int NOT NULL,
    receiver_id int NOT NULL,
    message varchar(5000) NOT NULL,
    date_sent varchar(255) NOT NULL,
    type int NOT NULL,
    unique_key varchar(255) NOT NULL,
    is_archived int(10) NOT NULL
);