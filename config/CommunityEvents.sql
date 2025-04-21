CREATE TABLE `events` (
    `event_id` INT AUTO_INCREMENT PRIMARY KEY,
    `title` VARCHAR(255) NOT NULL,
    `date` DATETIME NOT NULL,
    `link` VARCHAR(255),
    `description` TEXT,
    `category` VARCHAR(100),
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

INSERT INTO `events` (`title`, `date`, `link`, `description`, `category`)
VALUES (
    'Yoga for Stress Relief',
    '2025-05-01 10:00:00',
    'https://zoom.us/xyz123',
    'Join us for a calming yoga session to relieve stress and improve flexibility.',
    'Yoga'
);
