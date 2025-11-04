-- Update your existing users table for registration system
-- This will add missing columns without deleting existing data

USE quanlynguoidung;

-- Add username column (required for registration)
ALTER TABLE `users` 
ADD COLUMN `username` VARCHAR(50) NOT NULL UNIQUE AFTER `id`;

-- Add password column (required for registration) 
ALTER TABLE `users` 
ADD COLUMN `password` VARCHAR(255) NOT NULL AFTER `email`;

-- Rename fullName to full_name (optional - for consistency)
-- Or keep fullName and we'll map it in the code
-- ALTER TABLE `users` CHANGE `fullName` `full_name` VARCHAR(100);

-- Rename phoneNumber to phone (optional - for consistency)
-- Or keep phoneNumber and we'll map it in the code
-- ALTER TABLE `users` CHANGE `phoneNumber` `phone` VARCHAR(20);

-- Add role column (user/admin)
ALTER TABLE `users` 
ADD COLUMN `role` ENUM('user', 'admin') DEFAULT 'user' AFTER `gender`;

-- Add status column (active/inactive/pending)
ALTER TABLE `users` 
ADD COLUMN `status` ENUM('active', 'inactive', 'pending') DEFAULT 'active' AFTER `role`;

-- Add created_at timestamp
ALTER TABLE `users` 
ADD COLUMN `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP AFTER `status`;

-- Add updated_at timestamp
ALTER TABLE `users` 
ADD COLUMN `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP AFTER `created_at`;

-- Make sure username is unique (in case it wasn't added with UNIQUE)
-- ALTER TABLE `users` ADD UNIQUE INDEX `unique_username` (`username`);

