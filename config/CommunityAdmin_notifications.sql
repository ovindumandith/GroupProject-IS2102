CREATE TABLE `notifications` (
  `notification_id` INT NOT NULL AUTO_INCREMENT,
  `post_id` INT NOT NULL,
  `user_id` INT NOT NULL,
  `title` VARCHAR(255) NOT NULL,
  `reason` TEXT NOT NULL,
  `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`notification_id`)
);

INSERT INTO `notifications` (`post_id`, `user_id`, `title`, `reason`)
VALUES (2, 1, 'Inappropriate Content', 'Your post was removed due to inappropriate language.');
