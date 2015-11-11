# SQL Manager Lite for MySQL 5.5.3.46192
# ---------------------------------------
# Host     : localhost
# Port     : 3306
# Database : cognitive


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

SET FOREIGN_KEY_CHECKS=0;

CREATE DATABASE `cognitive`
    CHARACTER SET 'utf8'
    COLLATE 'utf8_general_ci';

USE `cognitive`;

#
# Структура для таблицы `cities`: 
#

CREATE TABLE `cities` (
  `id` INTEGER(11) NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(45) COLLATE utf8_general_ci NOT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE=InnoDB
AUTO_INCREMENT=11 CHARACTER SET 'utf8' COLLATE 'utf8_general_ci'
;

#
# Структура для таблицы `educations`: 
#

CREATE TABLE `educations` (
  `id` INTEGER(11) NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(45) COLLATE utf8_general_ci NOT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE=InnoDB
AUTO_INCREMENT=5 CHARACTER SET 'utf8' COLLATE 'utf8_general_ci'
;

#
# Структура для таблицы `user_cities`: 
#

CREATE TABLE `user_cities` (
  `user_id` INTEGER(11) NOT NULL,
  `city_id` VARCHAR(45) COLLATE utf8_general_ci NOT NULL,
  UNIQUE KEY `user_cities` (`user_id`, `city_id`) USING BTREE,
  KEY `city_id` (`city_id`) USING BTREE,
  KEY `user_id` (`user_id`) USING BTREE
) ENGINE=InnoDB
CHARACTER SET 'utf8' COLLATE 'utf8_general_ci'
;

#
# Структура для таблицы `users`: 
#

CREATE TABLE `users` (
  `id` INTEGER(11) NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(45) COLLATE utf8_general_ci NOT NULL,
  `education_id` INTEGER(11) DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  KEY `users_FK_education_id_idx` (`education_id`) USING BTREE,
  CONSTRAINT `users_FK_education_id` FOREIGN KEY (`education_id`) REFERENCES `educations` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB
AUTO_INCREMENT=13 CHARACTER SET 'utf8' COLLATE 'utf8_general_ci'
;

#
# Data for the `cities` table  (LIMIT 0,500)
#

INSERT INTO `cities` (`id`, `name`) VALUES
  (1,'Москва'),
  (2,'Санкт-Петербург'),
  (3,'Нижний Новгород'),
  (4,'Екатеринбург'),
  (5,'Казань'),
  (6,'Смоленск'),
  (7,'Челябинск'),
  (8,'Курск'),
  (9,'Тюмень'),
  (10,'Архангельск');
COMMIT;

#
# Data for the `educations` table  (LIMIT 0,500)
#

INSERT INTO `educations` (`id`, `name`) VALUES
  (1,'Среднее'),
  (2,'Бакалавр'),
  (3,'Магистр'),
  (4,'Профессор');
COMMIT;

#
# Data for the `user_cities` table  (LIMIT 0,500)
#

INSERT INTO `user_cities` (`user_id`, `city_id`) VALUES
  (1,'1'),
  (1,'3'),
  (2,'2'),
  (2,'5'),
  (3,'1'),
  (4,'10'),
  (4,'4'),
  (4,'8'),
  (5,'6'),
  (5,'7'),
  (6,'2'),
  (6,'6'),
  (7,'7'),
  (7,'9'),
  (8,'1'),
  (8,'4'),
  (9,'10'),
  (9,'9'),
  (10,'10'),
  (10,'5'),
  (11,'3'),
  (11,'7'),
  (11,'8'),
  (12,'4'),
  (12,'7');
COMMIT;

#
# Data for the `users` table  (LIMIT 0,500)
#

INSERT INTO `users` (`id`, `name`, `education_id`) VALUES
  (1,'Иван Петров',1),
  (2,'Олег Сергеев',2),
  (3,'Виктор Семенов',3),
  (4,'Ольга Дмитриева',4),
  (5,'Татьяна Соколова',1),
  (6,'Наталья Кузнецова',2),
  (7,'Кирилл Смирнов',3),
  (8,'Анна Макарова',4),
  (9,'Степан Куликов',4),
  (10,'Юрий Гончаров',3),
  (11,'Екатерина Никитина',2),
  (12,'Светлана Иванова',4);
COMMIT;



/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;