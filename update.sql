ALTER TABLE `tokens` DROP `token`;
ALTER TABLE `tokens` ADD `id` INT NOT NULL AUTO_INCREMENT FIRST, ADD PRIMARY KEY (`id`);
ALTER TABLE `tokens` ADD `token` VARCHAR(100) NULL DEFAULT NULL AFTER `user_id`;
ALTER TABLE `users` CHANGE `age` `age` VARCHAR(20) NULL DEFAULT NULL;

