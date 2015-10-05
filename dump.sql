CREATE TABLE `employee` (
  `id`            INT(11)      NOT NULL AUTO_INCREMENT,
  `name`          VARCHAR(255) NOT NULL,
  `title`         VARCHAR(255) NOT NULL,
  `email`         VARCHAR(255) NOT NULL,
  `supervisor_id` INT(11)      NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`),
  UNIQUE KEY `idx_unique_email` (`email`),
  KEY `idx_supervisor_id` (`supervisor_id`)
)
  ENGINE = InnoDB
  DEFAULT CHARSET = UTF8;

CREATE TABLE `user` (
  `id`       INT(11)      NOT NULL AUTO_INCREMENT,
  `username` VARCHAR(255) NOT NULL,
  `password` VARCHAR(255) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `idx_unique_username` (`username`)
)
  ENGINE = InnoDB
  DEFAULT CHARSET = UTF8;

INSERT INTO `user` VALUES (1,'admin','~0vrzSg43HfWI01fa163f5e05c9ec304f9833c32f3f0a'); /* admin 123456 */
