-- Ning Nong Indonesia Database Setup
-- Run this SQL in phpMyAdmin or MySQL CLI

-- Create products table
CREATE TABLE IF NOT EXISTS `products` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `name_id` varchar(255) DEFAULT NULL COMMENT 'Indonesian name',
  `description` text DEFAULT NULL,
  `description_id` text DEFAULT NULL COMMENT 'Indonesian description',
  `price` decimal(10,2) NOT NULL DEFAULT 0.00,
  `image` varchar(255) DEFAULT NULL,
  `category` varchar(100) DEFAULT NULL,
  `is_featured` tinyint(1) DEFAULT 0,
  `is_active` tinyint(1) DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Add role column to user_tbl (if it doesn't exist)
ALTER TABLE `user_tbl` ADD COLUMN IF NOT EXISTS `role` enum('user','admin','owner') DEFAULT 'user';

-- Insert default products (if table is empty)
INSERT INTO `products` (`name`, `name_id`, `description`, `description_id`, `price`, `image`, `category`, `is_featured`, `is_active`) VALUES
('Original Flavor', 'Rasa Original', 'Classic authentic taste of traditional Kembang Goyang', 'Rasa klasik autentik dari Kembang Goyang tradisional', 25000.00, 'product2.jpg', 'Traditional', 1, 1),
('Coffee Flavor', 'Rasa Kopi', 'Rich coffee-infused Kembang Goyang for coffee lovers', 'Kembang Goyang dengan infus kopi yang kaya untuk pecinta kopi', 28000.00, 'product3.jpg', 'Premium', 1, 1),
('Chocolate Flavor', 'Rasa Coklat', 'Delicious chocolate variation of the traditional snack', 'Variasi coklat lezat dari camilan tradisional', 28000.00, 'product1.jpg', 'Premium', 1, 1);

-- Update a specific user to be admin (change the email to your admin email)
-- UPDATE `user_tbl` SET `role` = 'admin' WHERE `email` = 'admin@example.com';
