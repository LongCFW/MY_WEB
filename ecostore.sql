-- phpMyAdmin SQL Dump
-- version 5.2.3
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Jan 27, 2026 at 05:41 AM
-- Server version: 8.0.44
-- PHP Version: 8.3.16

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `ecostore`
--

-- --------------------------------------------------------

--
-- Table structure for table `audit_logs`
--

DROP TABLE IF EXISTS `audit_logs`;
CREATE TABLE `audit_logs` (
  `id` bigint UNSIGNED NOT NULL,
  `actor_id` bigint UNSIGNED DEFAULT NULL,
  `action` varchar(255) DEFAULT NULL,
  `object_type` varchar(255) DEFAULT NULL,
  `object_id` bigint DEFAULT NULL,
  `diff` json DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- RELATIONSHIPS FOR TABLE `audit_logs`:
--   `actor_id`
--       `users` -> `id`
--

-- --------------------------------------------------------

--
-- Table structure for table `cart_items`
--

DROP TABLE IF EXISTS `cart_items`;
CREATE TABLE `cart_items` (
  `id` bigint UNSIGNED NOT NULL,
  `user_id` bigint UNSIGNED DEFAULT NULL,
  `variant_id` bigint UNSIGNED DEFAULT NULL,
  `quantity` int DEFAULT NULL,
  `added_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- RELATIONSHIPS FOR TABLE `cart_items`:
--   `user_id`
--       `users` -> `id`
--   `variant_id`
--       `product_variants` -> `id`
--

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

DROP TABLE IF EXISTS `categories`;
CREATE TABLE `categories` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(200) DEFAULT NULL,
  `slug` varchar(200) DEFAULT NULL,
  `description` text,
  `parent_id` bigint UNSIGNED DEFAULT NULL,
  `image_url` text,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- RELATIONSHIPS FOR TABLE `categories`:
--   `parent_id`
--       `categories` -> `id`
--

-- --------------------------------------------------------

--
-- Table structure for table `coupons`
--

DROP TABLE IF EXISTS `coupons`;
CREATE TABLE `coupons` (
  `id` bigint UNSIGNED NOT NULL,
  `code` varchar(100) DEFAULT NULL,
  `type` varchar(50) DEFAULT NULL,
  `value` bigint DEFAULT NULL,
  `min_order_cents` bigint DEFAULT NULL,
  `usage_limit` int DEFAULT NULL,
  `used_count` int DEFAULT '0',
  `starts_at` datetime DEFAULT NULL,
  `ends_at` datetime DEFAULT NULL,
  `applies_to_category_ids` json DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- RELATIONSHIPS FOR TABLE `coupons`:
--

-- --------------------------------------------------------

--
-- Table structure for table `email_queue`
--

DROP TABLE IF EXISTS `email_queue`;
CREATE TABLE `email_queue` (
  `id` bigint UNSIGNED NOT NULL,
  `to_email` varchar(255) DEFAULT NULL,
  `subject` varchar(255) DEFAULT NULL,
  `body` text,
  `status` varchar(50) DEFAULT NULL,
  `attempts` int DEFAULT '0',
  `last_attempt_at` datetime DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- RELATIONSHIPS FOR TABLE `email_queue`:
--

-- --------------------------------------------------------

--
-- Table structure for table `inventory_transactions`
--

DROP TABLE IF EXISTS `inventory_transactions`;
CREATE TABLE `inventory_transactions` (
  `id` bigint UNSIGNED NOT NULL,
  `variant_id` bigint UNSIGNED DEFAULT NULL,
  `change_qty` int NOT NULL,
  `reason` varchar(100) DEFAULT NULL,
  `ref_table` varchar(50) DEFAULT NULL,
  `ref_id` bigint UNSIGNED DEFAULT NULL,
  `created_by` bigint UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- RELATIONSHIPS FOR TABLE `inventory_transactions`:
--   `variant_id`
--       `product_variants` -> `id`
--   `created_by`
--       `users` -> `id`
--

-- --------------------------------------------------------

--
-- Table structure for table `migrations`
--

DROP TABLE IF EXISTS `migrations`;
CREATE TABLE `migrations` (
  `id` int UNSIGNED NOT NULL,
  `migration` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- RELATIONSHIPS FOR TABLE `migrations`:
--

-- --------------------------------------------------------

--
-- Table structure for table `notifications`
--

DROP TABLE IF EXISTS `notifications`;
CREATE TABLE `notifications` (
  `id` bigint UNSIGNED NOT NULL,
  `user_id` bigint UNSIGNED DEFAULT NULL,
  `type` varchar(50) DEFAULT NULL,
  `title` text,
  `message` text,
  `is_read` tinyint DEFAULT '0',
  `sent_via` varchar(20) DEFAULT NULL,
  `metadata` json DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- RELATIONSHIPS FOR TABLE `notifications`:
--   `user_id`
--       `users` -> `id`
--

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

DROP TABLE IF EXISTS `orders`;
CREATE TABLE `orders` (
  `id` bigint UNSIGNED NOT NULL,
  `user_id` bigint UNSIGNED DEFAULT NULL,
  `order_number` varchar(50) NOT NULL,
  `status` varchar(50) DEFAULT NULL,
  `total_cents` bigint NOT NULL,
  `subtotal_cents` bigint DEFAULT NULL,
  `shipping_fee_cents` bigint DEFAULT NULL,
  `tax_cents` bigint DEFAULT NULL,
  `shipping_address_id` bigint UNSIGNED DEFAULT NULL,
  `billing_address_id` bigint UNSIGNED DEFAULT NULL,
  `payment_status` varchar(50) DEFAULT NULL,
  `payment_method` varchar(50) DEFAULT 'cod',
  `placed_at` datetime DEFAULT NULL,
  `closed_at` datetime DEFAULT NULL,
  `notes` text,
  `coupon_code` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- RELATIONSHIPS FOR TABLE `orders`:
--   `user_id`
--       `users` -> `id`
--   `shipping_address_id`
--       `shipping_addresses` -> `id`
--   `billing_address_id`
--       `shipping_addresses` -> `id`
--

-- --------------------------------------------------------

--
-- Table structure for table `order_coupons`
--

DROP TABLE IF EXISTS `order_coupons`;
CREATE TABLE `order_coupons` (
  `id` bigint UNSIGNED NOT NULL,
  `order_id` bigint UNSIGNED DEFAULT NULL,
  `coupon_id` bigint UNSIGNED DEFAULT NULL,
  `applied_amount_cents` bigint DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- RELATIONSHIPS FOR TABLE `order_coupons`:
--   `order_id`
--       `orders` -> `id`
--   `coupon_id`
--       `coupons` -> `id`
--

-- --------------------------------------------------------

--
-- Table structure for table `order_items`
--

DROP TABLE IF EXISTS `order_items`;
CREATE TABLE `order_items` (
  `id` bigint UNSIGNED NOT NULL,
  `order_id` bigint UNSIGNED DEFAULT NULL,
  `variant_id` bigint UNSIGNED DEFAULT NULL,
  `product_id` bigint UNSIGNED DEFAULT NULL,
  `product_snapshot` json DEFAULT NULL,
  `quantity` int DEFAULT NULL,
  `unit_price_cents` bigint DEFAULT NULL,
  `total_price_cents` bigint DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- RELATIONSHIPS FOR TABLE `order_items`:
--   `order_id`
--       `orders` -> `id`
--   `variant_id`
--       `product_variants` -> `id`
--

-- --------------------------------------------------------

--
-- Table structure for table `order_status_history`
--

DROP TABLE IF EXISTS `order_status_history`;
CREATE TABLE `order_status_history` (
  `id` bigint UNSIGNED NOT NULL,
  `order_id` bigint UNSIGNED DEFAULT NULL,
  `status` varchar(50) DEFAULT NULL,
  `changed_by` bigint UNSIGNED DEFAULT NULL,
  `note` text,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- RELATIONSHIPS FOR TABLE `order_status_history`:
--   `order_id`
--       `orders` -> `id`
--   `changed_by`
--       `users` -> `id`
--

-- --------------------------------------------------------

--
-- Table structure for table `payments`
--

DROP TABLE IF EXISTS `payments`;
CREATE TABLE `payments` (
  `id` bigint UNSIGNED NOT NULL,
  `order_id` bigint UNSIGNED DEFAULT NULL,
  `payment_method` varchar(50) DEFAULT NULL,
  `transaction_id` varchar(255) DEFAULT NULL,
  `amount_cents` bigint DEFAULT NULL,
  `status` varchar(50) DEFAULT NULL,
  `paid_at` datetime DEFAULT NULL,
  `raw_response` json DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- RELATIONSHIPS FOR TABLE `payments`:
--   `order_id`
--       `orders` -> `id`
--

-- --------------------------------------------------------

--
-- Table structure for table `permissions`
--

DROP TABLE IF EXISTS `permissions`;
CREATE TABLE `permissions` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) DEFAULT NULL,
  `description` text
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- RELATIONSHIPS FOR TABLE `permissions`:
--

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

DROP TABLE IF EXISTS `products`;
CREATE TABLE `products` (
  `id` bigint UNSIGNED NOT NULL,
  `sku` varchar(100) DEFAULT NULL,
  `name` varchar(300) NOT NULL,
  `brand` varchar(255) DEFAULT NULL,
  `slug` varchar(300) DEFAULT NULL,
  `description` text,
  `short_description` text,
  `category_id` bigint UNSIGNED DEFAULT NULL,
  `is_active` tinyint DEFAULT '1',
  `is_featured` tinyint DEFAULT '0',
  `meta` json DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- RELATIONSHIPS FOR TABLE `products`:
--   `category_id`
--       `categories` -> `id`
--

-- --------------------------------------------------------

--
-- Table structure for table `product_images`
--

DROP TABLE IF EXISTS `product_images`;
CREATE TABLE `product_images` (
  `id` bigint UNSIGNED NOT NULL,
  `product_id` bigint UNSIGNED DEFAULT NULL,
  `variant_id` bigint UNSIGNED DEFAULT NULL,
  `image_url` text,
  `alt_text` varchar(255) DEFAULT NULL,
  `position` int DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- RELATIONSHIPS FOR TABLE `product_images`:
--   `product_id`
--       `products` -> `id`
--   `variant_id`
--       `product_variants` -> `id`
--

-- --------------------------------------------------------

--
-- Table structure for table `product_variants`
--

DROP TABLE IF EXISTS `product_variants`;
CREATE TABLE `product_variants` (
  `id` bigint UNSIGNED NOT NULL,
  `product_id` bigint UNSIGNED NOT NULL,
  `sku` varchar(100) DEFAULT NULL,
  `name` varchar(200) DEFAULT NULL,
  `price_cents` bigint DEFAULT NULL,
  `compare_at_price_cents` bigint DEFAULT NULL,
  `stock` int DEFAULT '0',
  `weight_grams` int DEFAULT NULL,
  `attributes` json DEFAULT NULL,
  `is_active` tinyint DEFAULT '1',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- RELATIONSHIPS FOR TABLE `product_variants`:
--   `product_id`
--       `products` -> `id`
--

-- --------------------------------------------------------

--
-- Table structure for table `reviews`
--

DROP TABLE IF EXISTS `reviews`;
CREATE TABLE `reviews` (
  `id` bigint UNSIGNED NOT NULL,
  `user_id` bigint UNSIGNED DEFAULT NULL,
  `product_id` bigint UNSIGNED DEFAULT NULL,
  `variant_id` bigint UNSIGNED DEFAULT NULL,
  `rating` smallint DEFAULT NULL,
  `title` varchar(255) DEFAULT NULL,
  `comment` text,
  `is_approved` tinyint DEFAULT '0',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- RELATIONSHIPS FOR TABLE `reviews`:
--   `user_id`
--       `users` -> `id`
--   `product_id`
--       `products` -> `id`
--   `variant_id`
--       `product_variants` -> `id`
--

-- --------------------------------------------------------

--
-- Table structure for table `roles`
--

DROP TABLE IF EXISTS `roles`;
CREATE TABLE `roles` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(100) DEFAULT NULL,
  `description` text,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- RELATIONSHIPS FOR TABLE `roles`:
--

-- --------------------------------------------------------

--
-- Table structure for table `role_permissions`
--

DROP TABLE IF EXISTS `role_permissions`;
CREATE TABLE `role_permissions` (
  `role_id` bigint UNSIGNED NOT NULL,
  `permission_id` bigint UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- RELATIONSHIPS FOR TABLE `role_permissions`:
--   `role_id`
--       `roles` -> `id`
--   `permission_id`
--       `permissions` -> `id`
--

-- --------------------------------------------------------

--
-- Table structure for table `shipping_addresses`
--

DROP TABLE IF EXISTS `shipping_addresses`;
CREATE TABLE `shipping_addresses` (
  `id` bigint UNSIGNED NOT NULL,
  `user_id` bigint UNSIGNED DEFAULT NULL,
  `full_name` varchar(255) DEFAULT NULL,
  `phone` varchar(50) DEFAULT NULL,
  `address_line` varchar(255) DEFAULT NULL,
  `city` varchar(100) DEFAULT NULL,
  `province` varchar(100) DEFAULT NULL,
  `postal_code` varchar(50) DEFAULT NULL,
  `country` varchar(100) DEFAULT NULL,
  `is_default` tinyint DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- RELATIONSHIPS FOR TABLE `shipping_addresses`:
--   `user_id`
--       `users` -> `id`
--

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
CREATE TABLE `users` (
  `id` bigint UNSIGNED NOT NULL,
  `email` varchar(255) NOT NULL,
  `password_hash` varchar(255) DEFAULT NULL,
  `name` varchar(200) DEFAULT NULL,
  `phone` varchar(50) DEFAULT NULL,
  `avatar_url` text,
  `status` tinyint DEFAULT '1',
  `role_id` bigint UNSIGNED DEFAULT NULL,
  `google_id` varchar(255) DEFAULT NULL,
  `email_verified` tinyint DEFAULT '0',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `last_login_at` datetime DEFAULT NULL,
  `metadata` json DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- RELATIONSHIPS FOR TABLE `users`:
--   `role_id`
--       `roles` -> `id`
--

-- --------------------------------------------------------

--
-- Table structure for table `wishlists`
--

DROP TABLE IF EXISTS `wishlists`;
CREATE TABLE `wishlists` (
  `id` bigint UNSIGNED NOT NULL,
  `user_id` bigint UNSIGNED DEFAULT NULL,
  `variant_id` bigint UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- RELATIONSHIPS FOR TABLE `wishlists`:
--   `user_id`
--       `users` -> `id`
--   `variant_id`
--       `product_variants` -> `id`
--

--
-- Indexes for dumped tables
--

--
-- Indexes for table `audit_logs`
--
ALTER TABLE `audit_logs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_audit_actor` (`actor_id`);

--
-- Indexes for table `cart_items`
--
ALTER TABLE `cart_items`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uniq_user_variant` (`user_id`,`variant_id`),
  ADD KEY `idx_ci_user` (`user_id`),
  ADD KEY `idx_ci_variant` (`variant_id`);

--
-- Indexes for table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `slug` (`slug`),
  ADD KEY `idx_categories_parent` (`parent_id`);

--
-- Indexes for table `coupons`
--
ALTER TABLE `coupons`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `code` (`code`);

--
-- Indexes for table `email_queue`
--
ALTER TABLE `email_queue`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `inventory_transactions`
--
ALTER TABLE `inventory_transactions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_it_variant` (`variant_id`),
  ADD KEY `idx_it_user` (`created_by`);

--
-- Indexes for table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `notifications`
--
ALTER TABLE `notifications`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_notify_user` (`user_id`);

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `order_number` (`order_number`),
  ADD KEY `idx_orders_user` (`user_id`),
  ADD KEY `idx_orders_ship` (`shipping_address_id`),
  ADD KEY `idx_orders_bill` (`billing_address_id`);

--
-- Indexes for table `order_coupons`
--
ALTER TABLE `order_coupons`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_oc_order` (`order_id`),
  ADD KEY `idx_oc_coupon` (`coupon_id`);

--
-- Indexes for table `order_items`
--
ALTER TABLE `order_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_oi_order` (`order_id`),
  ADD KEY `idx_oi_variant` (`variant_id`),
  ADD KEY `idx_oi_product` (`product_id`);

--
-- Indexes for table `order_status_history`
--
ALTER TABLE `order_status_history`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_osh_order` (`order_id`),
  ADD KEY `changed_by` (`changed_by`);

--
-- Indexes for table `payments`
--
ALTER TABLE `payments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_pay_order` (`order_id`);

--
-- Indexes for table `permissions`
--
ALTER TABLE `permissions`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `name` (`name`);

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `sku` (`sku`),
  ADD UNIQUE KEY `slug` (`slug`),
  ADD KEY `idx_products_category` (`category_id`);

--
-- Indexes for table `product_images`
--
ALTER TABLE `product_images`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_pi_product` (`product_id`),
  ADD KEY `idx_pi_variant` (`variant_id`);

--
-- Indexes for table `product_variants`
--
ALTER TABLE `product_variants`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `sku` (`sku`),
  ADD KEY `idx_variants_product` (`product_id`);

--
-- Indexes for table `reviews`
--
ALTER TABLE `reviews`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_reviews_product` (`product_id`),
  ADD KEY `idx_reviews_user` (`user_id`),
  ADD KEY `variant_id` (`variant_id`);

--
-- Indexes for table `roles`
--
ALTER TABLE `roles`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `name` (`name`);

--
-- Indexes for table `role_permissions`
--
ALTER TABLE `role_permissions`
  ADD PRIMARY KEY (`role_id`,`permission_id`),
  ADD KEY `idx_rp_role` (`role_id`),
  ADD KEY `idx_rp_perm` (`permission_id`);

--
-- Indexes for table `shipping_addresses`
--
ALTER TABLE `shipping_addresses`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_sa_user` (`user_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`),
  ADD KEY `idx_users_phone` (`phone`),
  ADD KEY `idx_users_role_id` (`role_id`);

--
-- Indexes for table `wishlists`
--
ALTER TABLE `wishlists`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uniq_wishlist` (`user_id`,`variant_id`),
  ADD KEY `idx_wl_user` (`user_id`),
  ADD KEY `idx_wl_variant` (`variant_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `audit_logs`
--
ALTER TABLE `audit_logs`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `cart_items`
--
ALTER TABLE `cart_items`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `coupons`
--
ALTER TABLE `coupons`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `email_queue`
--
ALTER TABLE `email_queue`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `inventory_transactions`
--
ALTER TABLE `inventory_transactions`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `notifications`
--
ALTER TABLE `notifications`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `order_coupons`
--
ALTER TABLE `order_coupons`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `order_items`
--
ALTER TABLE `order_items`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `order_status_history`
--
ALTER TABLE `order_status_history`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `payments`
--
ALTER TABLE `payments`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `permissions`
--
ALTER TABLE `permissions`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `product_images`
--
ALTER TABLE `product_images`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `product_variants`
--
ALTER TABLE `product_variants`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `reviews`
--
ALTER TABLE `reviews`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `roles`
--
ALTER TABLE `roles`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `shipping_addresses`
--
ALTER TABLE `shipping_addresses`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `wishlists`
--
ALTER TABLE `wishlists`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `audit_logs`
--
ALTER TABLE `audit_logs`
  ADD CONSTRAINT `audit_logs_ibfk_1` FOREIGN KEY (`actor_id`) REFERENCES `users` (`id`);

--
-- Constraints for table `cart_items`
--
ALTER TABLE `cart_items`
  ADD CONSTRAINT `cart_items_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `cart_items_ibfk_2` FOREIGN KEY (`variant_id`) REFERENCES `product_variants` (`id`);

--
-- Constraints for table `categories`
--
ALTER TABLE `categories`
  ADD CONSTRAINT `categories_ibfk_1` FOREIGN KEY (`parent_id`) REFERENCES `categories` (`id`);

--
-- Constraints for table `inventory_transactions`
--
ALTER TABLE `inventory_transactions`
  ADD CONSTRAINT `inventory_transactions_ibfk_1` FOREIGN KEY (`variant_id`) REFERENCES `product_variants` (`id`),
  ADD CONSTRAINT `inventory_transactions_ibfk_2` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`);

--
-- Constraints for table `notifications`
--
ALTER TABLE `notifications`
  ADD CONSTRAINT `notifications_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

--
-- Constraints for table `orders`
--
ALTER TABLE `orders`
  ADD CONSTRAINT `orders_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `orders_ibfk_2` FOREIGN KEY (`shipping_address_id`) REFERENCES `shipping_addresses` (`id`),
  ADD CONSTRAINT `orders_ibfk_3` FOREIGN KEY (`billing_address_id`) REFERENCES `shipping_addresses` (`id`);

--
-- Constraints for table `order_coupons`
--
ALTER TABLE `order_coupons`
  ADD CONSTRAINT `order_coupons_ibfk_1` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`),
  ADD CONSTRAINT `order_coupons_ibfk_2` FOREIGN KEY (`coupon_id`) REFERENCES `coupons` (`id`);

--
-- Constraints for table `order_items`
--
ALTER TABLE `order_items`
  ADD CONSTRAINT `order_items_ibfk_1` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `order_items_ibfk_2` FOREIGN KEY (`variant_id`) REFERENCES `product_variants` (`id`);

--
-- Constraints for table `order_status_history`
--
ALTER TABLE `order_status_history`
  ADD CONSTRAINT `order_status_history_ibfk_1` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`),
  ADD CONSTRAINT `order_status_history_ibfk_2` FOREIGN KEY (`changed_by`) REFERENCES `users` (`id`);

--
-- Constraints for table `payments`
--
ALTER TABLE `payments`
  ADD CONSTRAINT `payments_ibfk_1` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`);

--
-- Constraints for table `products`
--
ALTER TABLE `products`
  ADD CONSTRAINT `products_ibfk_1` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`);

--
-- Constraints for table `product_images`
--
ALTER TABLE `product_images`
  ADD CONSTRAINT `product_images_ibfk_1` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`),
  ADD CONSTRAINT `product_images_ibfk_2` FOREIGN KEY (`variant_id`) REFERENCES `product_variants` (`id`);

--
-- Constraints for table `product_variants`
--
ALTER TABLE `product_variants`
  ADD CONSTRAINT `product_variants_ibfk_1` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `reviews`
--
ALTER TABLE `reviews`
  ADD CONSTRAINT `reviews_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `reviews_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`),
  ADD CONSTRAINT `reviews_ibfk_3` FOREIGN KEY (`variant_id`) REFERENCES `product_variants` (`id`);

--
-- Constraints for table `role_permissions`
--
ALTER TABLE `role_permissions`
  ADD CONSTRAINT `role_permissions_ibfk_1` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`),
  ADD CONSTRAINT `role_permissions_ibfk_2` FOREIGN KEY (`permission_id`) REFERENCES `permissions` (`id`);

--
-- Constraints for table `shipping_addresses`
--
ALTER TABLE `shipping_addresses`
  ADD CONSTRAINT `shipping_addresses_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

--
-- Constraints for table `users`
--
ALTER TABLE `users`
  ADD CONSTRAINT `fk_users_role` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`);

--
-- Constraints for table `wishlists`
--
ALTER TABLE `wishlists`
  ADD CONSTRAINT `wishlists_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `wishlists_ibfk_2` FOREIGN KEY (`variant_id`) REFERENCES `product_variants` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
