CREATE DATABASE IF NOT EXISTS teste2;

USE teste2;

DROP TABLE IF EXISTS `subscriptions`;
DROP TABLE IF EXISTS `users`;
DROP TABLE IF EXISTS `events`;
DROP TABLE IF EXISTS `roles`;

CREATE TABLE `roles` (
                         `id` INT(10) NOT NULL AUTO_INCREMENT,
                         `no_role` VARCHAR(50) NOT NULL,
                         `created_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
                         PRIMARY KEY (`id`) USING BTREE
);

CREATE TABLE `events` (
                           `id` INT(10) NOT NULL AUTO_INCREMENT,
                           `no_event` VARCHAR(150) unique,
                           `created_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
                           PRIMARY KEY (`id`) USING BTREE
);

CREATE TABLE `users` (
                         `id` INT(10) NOT NULL AUTO_INCREMENT,
                         `password` VARCHAR(150) NOT NULL,
                         `no_user` VARCHAR(150) unique,
                         `role_id` INT(10) NULL DEFAULT NULL,
                         `created_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
                         PRIMARY KEY (`id`) USING BTREE,
                         INDEX `FK_users_roles` (`role_id`) USING BTREE,
                         CONSTRAINT `FK_users_roles` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON UPDATE CASCADE ON DELETE SET NULL
);

CREATE TABLE `subscriptions` (
                                 `id` INT(10) NOT NULL AUTO_INCREMENT,
                                 `user_id` INT(10) NOT NULL,
                                 `event_id` INT(10) NOT NULL,
                                 `created_at` TIMESTAMP NULL DEFAULT NULL,
                                 PRIMARY KEY (`id`) USING BTREE,
                                 INDEX `FK_subscriptions_users` (`user_id`) USING BTREE,
                                 INDEX `FK_subscriptions_events` (`event_id`) USING BTREE,
                                 CONSTRAINT `FK_subscriptions_events` FOREIGN KEY (`event_id`) REFERENCES `events` (`id`) ON UPDATE CASCADE ON DELETE CASCADE,
                                 CONSTRAINT `FK_subscriptions_users` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON UPDATE CASCADE ON DELETE CASCADE
);

-- Inserir roles
INSERT INTO `roles` (`no_role`) VALUES ('Admin'), ('Comum');

-- Inserir usuário administrador
INSERT INTO `users` (`password`, `no_user`, `role_id`) VALUES ('teste', 'Administrador', 1);

-- Gerar mais 10 usuários aleatórios
INSERT INTO `users` (`password`, `no_user`, `role_id`) VALUES
                                                           ('password1', 'User1', 2),
                                                           ('password2', 'User2', 2),
                                                           ('password3', 'User3', 2),
                                                           ('password4', 'User4', 2),
                                                           ('password5', 'User5', 2),
                                                           ('password6', 'User6', 2),
                                                           ('password7', 'User7', 2),
                                                           ('password8', 'User8', 2),
                                                           ('password9', 'User9', 2),
                                                           ('password10', 'User10', 2);
