SET time_zone = "+08:00";
-- DATABASE hub
CREATE DATABASE IF NOT EXISTS `hub`;
USE `hub`;

CREATE TABLE IF NOT EXISTS `hub`.`users` (
    `user_id` INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    `user_name` VARCHAR(100) NOT NULL UNIQUE,
    `user_password` VARCHAR(255) NOT NULL
) ENGINE = InnoDB DEFAULT CHARSET=latin1;
-- Sample user and data
INSERT INTO `users` (`user_id`, `user_name`,`user_password`) VALUES
(1,'test','$2y$10$eWDPZ1uMa/c9D5o4b.N5aOLzyRzyN64ejOixVFWbrgKDF.daMg2rK'),
(2,'User1','$2y$10$eWDPZ1uMa/c9D5o4b.N5aOLzyRzyN64ejOixVFWbrgKDF.daMg2rK'),
(3,'abc','$2y$10$eWDPZ1uMa/c9D5o4b.N5aOLzyRzyN64ejOixVFWbrgKDF.daMg2rK');

-- Create database users
CREATE USER 
    'read_data'@'%' IDENTIFIED BY 'password',
    'crud'@'%' IDENTIFIED BY 'password',
    'super'@'localhost' IDENTIFIED BY 'password',
    'account'@'%' IDENTIFIED BY 'password';
	

grant all privileges on *.* to 'super'@'localhost' with grant option;
grant SELECT on hub.* to 'read_data'@'%';
grant select, insert, update, delete on hub.* to 'account'@'%';
grant select, insert, update, delete on hub.* to 'crud'@'%';

-- table todo_items
CREATE TABLE IF NOT EXISTS `hub`.`todo_items` (
    `id` INT NOT NULL AUTO_INCREMENT,
    `task_name` VARCHAR(255) NOT NULL,
    `task_details` VARCHAR(255) NOT NULL,
    `completed` BOOLEAN NOT NULL DEFAULT 0,
    `created_by` VARCHAR(100),
    PRIMARY KEY (`id`),
    FOREIGN KEY (`created_by`) REFERENCES `users`(`user_name`)
) ENGINE = InnoDB DEFAULT CHARSET=latin1;

-- add one example database:
INSERT INTO todo_items (`id`, `task_name`,`task_details`,`created_by`) VALUES
(1,'Task 1', 'Details for Task 1','User1'),
(2,'Task 2', 'Details for Task 2','User1');

-- important: 
-- there still some problem to fix on the db!!!!!!
-- with the related php codepost_content

CREATE TABLE IF NOT EXISTS `hub`.`posts` (
    `post_id` INT(8) NOT NULL AUTO_INCREMENT,
    `todo_item_id` INT,
    `post_content` TEXT NOT NULL,
    `post_date` DATETIME NOT NULL,
    `post_by` INT NOT NULL,
    PRIMARY KEY (`post_id`),
    FOREIGN KEY (`todo_item_id`) REFERENCES `todo_items`(`id`),
    FOREIGN KEY (`post_by`) REFERENCES `users`(`user_id`)
) ENGINE = InnoDB DEFAULT CHARSET=latin1;


INSERT INTO posts (`todo_item_id`,`post_content`,`post_date`,`post_by`) VALUES
(1,'This is about the work','2023-1-4 23:59:59',2),
(1,'This is about the work2','2023-1-5 23:59:59',2),
(1,'This is about the work3','2023-1-6 23:59:59',3);

INSERT INTO posts (`todo_item_id`,`post_content`,`post_date`,`post_by`) VALUES
(2,'This is about the work','2023-1-4 23:59:59',1),
(2,'This is about the work2','2023-1-5 23:59:59',3),
(2,'This is about the work3','2023-1-6 23:59:59',1);
