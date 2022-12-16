CREATE TABLE admins (
    id int PRIMARY KEY AUTO_INCREMENT, 
    full_name varchar(255) NOT NULL,
    username varchar(255) NOT NULL,
    password varchar(255) NOT NULL,
    type int(10) NOT NULL,
    is_archived int(10) NOT NULL
);

INSERT INTO admins (full_name, username, password, type, is_archived) VALUES ('Administrator', 'root', '$2y$10$0CJlEbw22UnsX71MkFKvDeLntl77tutPfMKwd/DrmqBVUdeQI7SKu', 1, 0);

INSERT INTO admins (full_name, username, password, type, is_archived) VALUES ('zadrach caunca', 'zach', '$2y$10$9Pc1ntpFSCjLMf7eZsPCQOmHtpaUM9orPJ9BSI/z25cisr8/qKpPm', 1, 0);

INSERT INTO admins (full_name, username, password, type, is_archived) VALUES ('carmelita colobong', 'mamita', '$2y$10$.dH/VVM4niI9C24vsvocwO1qCPS9fxjRsEfeW2TPKKq.UnPpvc4vi', 1, 0);

INSERT INTO admins (full_name, username, password, type, is_archived) VALUES ('Lorena Tumbali ', 'lorena', '$2y$10$KDYUCi0CVP5KBzx/TXc/AuAYidf4BtonVcT6xB5tedmOucGd7wd7O', 1, 0);