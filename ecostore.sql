-- phpMyAdmin SQL Dump
-- version 5.2.3
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Mar 06, 2026 at 03:41 AM
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

CREATE TABLE `audit_logs` (
  `id` bigint UNSIGNED NOT NULL,
  `actor_id` bigint UNSIGNED DEFAULT NULL,
  `action` varchar(255) DEFAULT NULL,
  `object_type` varchar(255) DEFAULT NULL,
  `object_id` bigint DEFAULT NULL,
  `diff` json DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `cart_items`
--

CREATE TABLE `cart_items` (
  `id` bigint UNSIGNED NOT NULL,
  `user_id` bigint UNSIGNED DEFAULT NULL,
  `variant_id` bigint UNSIGNED DEFAULT NULL,
  `quantity` int DEFAULT NULL,
  `added_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `cart_items`
--

INSERT INTO `cart_items` (`id`, `user_id`, `variant_id`, `quantity`, `added_at`) VALUES
(115, 37, 86, 3, '2026-03-05 14:10:57'),
(117, 37, 44, 1, '2026-03-05 14:44:01');

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

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
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`id`, `name`, `slug`, `description`, `parent_id`, `image_url`, `created_at`, `updated_at`) VALUES
(2, 'Rau Củ ', 'rau-cu', 'demo', NULL, 'assets/uploads/categories/1769241470_Rau-cu.jpg', '2026-01-18 16:37:07', '2026-01-27 07:36:06'),
(3, 'Tươi sống', 'tuoi-song', 'Đây là demo', NULL, 'assets/uploads/categories/1769241425_quy-dinh-van-chuyen-do-tuoi-song.jpg', '2026-01-19 09:20:55', '2026-01-24 07:57:05'),
(4, 'Thực Phẩm Khô', 'thuc-pham-kho', 'Demo', NULL, 'assets/uploads/categories/1769241524_thuc-pham-chay-kho.jpg', '2026-01-23 15:26:14', '2026-01-24 07:58:44'),
(5, 'Gia Vị & Phụ Liệu', 'gia-vi-phu-lieu', 'demo', NULL, 'assets/uploads/categories/1769241555_gia-vi.webp', '2026-01-23 15:26:40', '2026-02-05 08:23:02'),
(12, 'Rau lá hữu cơ', 'rau-la-huu-co', '', 2, 'assets/uploads/categories/1772762357_rau-la-huu-co.jpg', '2026-03-02 08:37:16', '2026-03-06 01:59:17'),
(13, 'Củ quả hữu cơ', 'cu-qua-huu-co', '', 2, 'assets/uploads/categories/1772762384_cu-qua-huu-co.jpg', '2026-03-02 08:37:35', '2026-03-06 01:59:44'),
(14, 'Thịt hữu cơ', 'thit-huu-co', '', 3, 'assets/uploads/categories/1772762414_thit-huu-co.jpg', '2026-03-02 08:38:40', '2026-03-06 02:00:14'),
(15, 'Hải sản tươi sống', 'hai-san-tuoi-song', '', 3, 'assets/uploads/categories/1772762456_hai-san-tuoi-song.jpg', '2026-03-02 08:38:57', '2026-03-06 02:00:56'),
(16, 'Mì và nui hữu cơ', 'mi-nui-huu-co', '', 4, 'assets/uploads/categories/1772762498_myvanuoihuuco.jpg', '2026-03-02 08:41:04', '2026-03-06 02:01:38'),
(17, 'Đồ khô khác', 'do-kho-khac', '', 4, 'assets/uploads/categories/1772762524_do-kho-khac.jpg', '2026-03-02 08:41:17', '2026-03-06 02:02:04'),
(18, 'Gia vị', 'gia-vi', '', 5, 'assets/uploads/categories/1772762549_gia-vi-vi.jpg', '2026-03-02 08:42:18', '2026-03-06 02:02:29'),
(19, 'Phụ liệu', 'phu-lieu', '', 5, 'assets/uploads/categories/1772762585_phulieu.jpg', '2026-03-02 08:42:29', '2026-03-06 02:03:05');

-- --------------------------------------------------------

--
-- Table structure for table `coupons`
--

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
-- Dumping data for table `coupons`
--

INSERT INTO `coupons` (`id`, `code`, `type`, `value`, `min_order_cents`, `usage_limit`, `used_count`, `starts_at`, `ends_at`, `applies_to_category_ids`, `created_at`) VALUES
(4, 'BUON_MAY_BAN_DAT', 'percent', 50, 500000, 50, 9, '2026-03-03 16:02:00', '2026-08-20 20:05:00', NULL, '2026-03-03 09:02:27'),
(5, 'VINH_XUAN_QUYEN', 'percent', 20, 500000, 50, 0, '2026-03-04 10:23:00', '2026-03-31 10:23:00', NULL, '2026-03-04 03:23:37'),
(6, 'HELL_NAH', 'percent', 30, 300000, 50, 0, '2026-03-04 10:25:00', '2026-03-31 10:25:00', NULL, '2026-03-04 03:25:13'),
(7, 'TEST', 'percent', 10, 123, 123, 0, '2026-03-04 20:34:00', '2026-03-07 20:34:00', NULL, '2026-03-04 13:34:17'),
(8, 'TEST2', 'percent', 12, 123, 123, 0, '2026-03-04 20:41:00', '2026-03-05 20:41:00', NULL, '2026-03-04 13:41:46'),
(9, 'TEST3', 'percent', 13, 123, 123, 0, '2026-03-04 20:44:00', '2026-03-05 20:44:00', NULL, '2026-03-04 13:44:37'),
(10, 'TEST4', 'percent', 15, 1231231, 123, 0, '2026-03-04 20:52:00', '2026-03-05 20:52:00', NULL, '2026-03-04 13:52:53'),
(11, '123', 'percent', 123, 123, 123, 0, '2026-03-05 13:23:00', '2026-03-11 13:23:00', NULL, '2026-03-05 06:23:45');

-- --------------------------------------------------------

--
-- Table structure for table `email_queue`
--

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

-- --------------------------------------------------------

--
-- Table structure for table `inventory_transactions`
--

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

-- --------------------------------------------------------

--
-- Table structure for table `migrations`
--

CREATE TABLE `migrations` (
  `id` int UNSIGNED NOT NULL,
  `migration` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `notifications`
--

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
-- Dumping data for table `notifications`
--

INSERT INTO `notifications` (`id`, `user_id`, `type`, `title`, `message`, `is_read`, `sent_via`, `metadata`, `created_at`) VALUES
(6, 6, 'new_coupon', '🎁 Mã giảm giá mới: TEST2', 'Shop vừa tung mã TEST2 giảm ngay 12% cho đơn từ 123đ. Nhanh tay lưu mã kẻo lỡ!', 1, NULL, '{\"coupon_code\": \"TEST2\"}', '2026-03-04 13:41:46'),
(7, 7, 'new_coupon', '🎁 Mã giảm giá mới: TEST2', 'Shop vừa tung mã TEST2 giảm ngay 12% cho đơn từ 123đ. Nhanh tay lưu mã kẻo lỡ!', 0, NULL, '{\"coupon_code\": \"TEST2\"}', '2026-03-04 13:41:46'),
(8, 8, 'new_coupon', '🎁 Mã giảm giá mới: TEST2', 'Shop vừa tung mã TEST2 giảm ngay 12% cho đơn từ 123đ. Nhanh tay lưu mã kẻo lỡ!', 0, NULL, '{\"coupon_code\": \"TEST2\"}', '2026-03-04 13:41:46'),
(9, 9, 'new_coupon', '🎁 Mã giảm giá mới: TEST2', 'Shop vừa tung mã TEST2 giảm ngay 12% cho đơn từ 123đ. Nhanh tay lưu mã kẻo lỡ!', 0, NULL, '{\"coupon_code\": \"TEST2\"}', '2026-03-04 13:41:46'),
(10, 13, 'new_coupon', '🎁 Mã giảm giá mới: TEST2', 'Shop vừa tung mã TEST2 giảm ngay 12% cho đơn từ 123đ. Nhanh tay lưu mã kẻo lỡ!', 0, NULL, '{\"coupon_code\": \"TEST2\"}', '2026-03-04 13:41:46'),
(11, 14, 'new_coupon', '🎁 Mã giảm giá mới: TEST2', 'Shop vừa tung mã TEST2 giảm ngay 12% cho đơn từ 123đ. Nhanh tay lưu mã kẻo lỡ!', 0, NULL, '{\"coupon_code\": \"TEST2\"}', '2026-03-04 13:41:46'),
(12, 16, 'new_coupon', '🎁 Mã giảm giá mới: TEST2', 'Shop vừa tung mã TEST2 giảm ngay 12% cho đơn từ 123đ. Nhanh tay lưu mã kẻo lỡ!', 0, NULL, '{\"coupon_code\": \"TEST2\"}', '2026-03-04 13:41:46'),
(13, 22, 'new_coupon', '🎁 Mã giảm giá mới: TEST2', 'Shop vừa tung mã TEST2 giảm ngay 12% cho đơn từ 123đ. Nhanh tay lưu mã kẻo lỡ!', 0, NULL, '{\"coupon_code\": \"TEST2\"}', '2026-03-04 13:41:46'),
(14, 23, 'new_coupon', '🎁 Mã giảm giá mới: TEST2', 'Shop vừa tung mã TEST2 giảm ngay 12% cho đơn từ 123đ. Nhanh tay lưu mã kẻo lỡ!', 0, NULL, '{\"coupon_code\": \"TEST2\"}', '2026-03-04 13:41:46'),
(15, 26, 'new_coupon', '🎁 Mã giảm giá mới: TEST2', 'Shop vừa tung mã TEST2 giảm ngay 12% cho đơn từ 123đ. Nhanh tay lưu mã kẻo lỡ!', 0, NULL, '{\"coupon_code\": \"TEST2\"}', '2026-03-04 13:41:46'),
(23, 6, 'new_coupon', '🎁 Mã giảm giá mới: TEST3', 'Shop vừa tung mã TEST3 giảm ngay 13% cho đơn từ 123đ. Nhanh tay lưu mã kẻo lỡ!', 1, NULL, '{\"coupon_code\": \"TEST3\"}', '2026-03-04 13:44:37'),
(24, 7, 'new_coupon', '🎁 Mã giảm giá mới: TEST3', 'Shop vừa tung mã TEST3 giảm ngay 13% cho đơn từ 123đ. Nhanh tay lưu mã kẻo lỡ!', 0, NULL, '{\"coupon_code\": \"TEST3\"}', '2026-03-04 13:44:37'),
(25, 8, 'new_coupon', '🎁 Mã giảm giá mới: TEST3', 'Shop vừa tung mã TEST3 giảm ngay 13% cho đơn từ 123đ. Nhanh tay lưu mã kẻo lỡ!', 0, NULL, '{\"coupon_code\": \"TEST3\"}', '2026-03-04 13:44:37'),
(26, 9, 'new_coupon', '🎁 Mã giảm giá mới: TEST3', 'Shop vừa tung mã TEST3 giảm ngay 13% cho đơn từ 123đ. Nhanh tay lưu mã kẻo lỡ!', 0, NULL, '{\"coupon_code\": \"TEST3\"}', '2026-03-04 13:44:37'),
(27, 13, 'new_coupon', '🎁 Mã giảm giá mới: TEST3', 'Shop vừa tung mã TEST3 giảm ngay 13% cho đơn từ 123đ. Nhanh tay lưu mã kẻo lỡ!', 0, NULL, '{\"coupon_code\": \"TEST3\"}', '2026-03-04 13:44:37'),
(28, 14, 'new_coupon', '🎁 Mã giảm giá mới: TEST3', 'Shop vừa tung mã TEST3 giảm ngay 13% cho đơn từ 123đ. Nhanh tay lưu mã kẻo lỡ!', 0, NULL, '{\"coupon_code\": \"TEST3\"}', '2026-03-04 13:44:37'),
(29, 16, 'new_coupon', '🎁 Mã giảm giá mới: TEST3', 'Shop vừa tung mã TEST3 giảm ngay 13% cho đơn từ 123đ. Nhanh tay lưu mã kẻo lỡ!', 0, NULL, '{\"coupon_code\": \"TEST3\"}', '2026-03-04 13:44:37'),
(30, 22, 'new_coupon', '🎁 Mã giảm giá mới: TEST3', 'Shop vừa tung mã TEST3 giảm ngay 13% cho đơn từ 123đ. Nhanh tay lưu mã kẻo lỡ!', 0, NULL, '{\"coupon_code\": \"TEST3\"}', '2026-03-04 13:44:37'),
(31, 23, 'new_coupon', '🎁 Mã giảm giá mới: TEST3', 'Shop vừa tung mã TEST3 giảm ngay 13% cho đơn từ 123đ. Nhanh tay lưu mã kẻo lỡ!', 0, NULL, '{\"coupon_code\": \"TEST3\"}', '2026-03-04 13:44:37'),
(32, 26, 'new_coupon', '🎁 Mã giảm giá mới: TEST3', 'Shop vừa tung mã TEST3 giảm ngay 13% cho đơn từ 123đ. Nhanh tay lưu mã kẻo lỡ!', 0, NULL, '{\"coupon_code\": \"TEST3\"}', '2026-03-04 13:44:37'),
(43, 6, 'new_coupon', '🎁 Mã giảm giá mới: TEST4', 'Shop vừa tung mã TEST4 giảm ngay 15% cho đơn từ 1,231,231đ. Nhanh tay lưu mã kẻo lỡ!', 1, NULL, '{\"coupon_code\": \"TEST4\"}', '2026-03-04 13:52:53'),
(44, 7, 'new_coupon', '🎁 Mã giảm giá mới: TEST4', 'Shop vừa tung mã TEST4 giảm ngay 15% cho đơn từ 1,231,231đ. Nhanh tay lưu mã kẻo lỡ!', 0, NULL, '{\"coupon_code\": \"TEST4\"}', '2026-03-04 13:52:53'),
(45, 8, 'new_coupon', '🎁 Mã giảm giá mới: TEST4', 'Shop vừa tung mã TEST4 giảm ngay 15% cho đơn từ 1,231,231đ. Nhanh tay lưu mã kẻo lỡ!', 0, NULL, '{\"coupon_code\": \"TEST4\"}', '2026-03-04 13:52:53'),
(46, 9, 'new_coupon', '🎁 Mã giảm giá mới: TEST4', 'Shop vừa tung mã TEST4 giảm ngay 15% cho đơn từ 1,231,231đ. Nhanh tay lưu mã kẻo lỡ!', 0, NULL, '{\"coupon_code\": \"TEST4\"}', '2026-03-04 13:52:53'),
(47, 13, 'new_coupon', '🎁 Mã giảm giá mới: TEST4', 'Shop vừa tung mã TEST4 giảm ngay 15% cho đơn từ 1,231,231đ. Nhanh tay lưu mã kẻo lỡ!', 0, NULL, '{\"coupon_code\": \"TEST4\"}', '2026-03-04 13:52:53'),
(48, 14, 'new_coupon', '🎁 Mã giảm giá mới: TEST4', 'Shop vừa tung mã TEST4 giảm ngay 15% cho đơn từ 1,231,231đ. Nhanh tay lưu mã kẻo lỡ!', 0, NULL, '{\"coupon_code\": \"TEST4\"}', '2026-03-04 13:52:53'),
(49, 16, 'new_coupon', '🎁 Mã giảm giá mới: TEST4', 'Shop vừa tung mã TEST4 giảm ngay 15% cho đơn từ 1,231,231đ. Nhanh tay lưu mã kẻo lỡ!', 0, NULL, '{\"coupon_code\": \"TEST4\"}', '2026-03-04 13:52:53'),
(50, 22, 'new_coupon', '🎁 Mã giảm giá mới: TEST4', 'Shop vừa tung mã TEST4 giảm ngay 15% cho đơn từ 1,231,231đ. Nhanh tay lưu mã kẻo lỡ!', 0, NULL, '{\"coupon_code\": \"TEST4\"}', '2026-03-04 13:52:53'),
(51, 23, 'new_coupon', '🎁 Mã giảm giá mới: TEST4', 'Shop vừa tung mã TEST4 giảm ngay 15% cho đơn từ 1,231,231đ. Nhanh tay lưu mã kẻo lỡ!', 0, NULL, '{\"coupon_code\": \"TEST4\"}', '2026-03-04 13:52:53'),
(52, 26, 'new_coupon', '🎁 Mã giảm giá mới: TEST4', 'Shop vừa tung mã TEST4 giảm ngay 15% cho đơn từ 1,231,231đ. Nhanh tay lưu mã kẻo lỡ!', 0, NULL, '{\"coupon_code\": \"TEST4\"}', '2026-03-04 13:52:53'),
(65, 27, 'system', 'Thanh toán thành công!', 'Chúng tôi đã nhận được khoản thanh toán cho đơn hàng #ORD-69A92144A8ECD. Đơn hàng của bạn đang được xử lý.', 1, NULL, '{\"order_id\": \"81\"}', '2026-03-05 06:23:10'),
(66, 6, 'new_coupon', '🎁 Mã giảm giá mới: 123', 'Shop vừa tung mã 123 giảm ngay 123% cho đơn từ 123đ. Nhanh tay lưu mã kẻo lỡ!', 1, NULL, '{\"coupon_code\": \"123\"}', '2026-03-05 06:23:45'),
(67, 7, 'new_coupon', '🎁 Mã giảm giá mới: 123', 'Shop vừa tung mã 123 giảm ngay 123% cho đơn từ 123đ. Nhanh tay lưu mã kẻo lỡ!', 0, NULL, '{\"coupon_code\": \"123\"}', '2026-03-05 06:23:45'),
(68, 8, 'new_coupon', '🎁 Mã giảm giá mới: 123', 'Shop vừa tung mã 123 giảm ngay 123% cho đơn từ 123đ. Nhanh tay lưu mã kẻo lỡ!', 0, NULL, '{\"coupon_code\": \"123\"}', '2026-03-05 06:23:45'),
(69, 9, 'new_coupon', '🎁 Mã giảm giá mới: 123', 'Shop vừa tung mã 123 giảm ngay 123% cho đơn từ 123đ. Nhanh tay lưu mã kẻo lỡ!', 0, NULL, '{\"coupon_code\": \"123\"}', '2026-03-05 06:23:45'),
(70, 13, 'new_coupon', '🎁 Mã giảm giá mới: 123', 'Shop vừa tung mã 123 giảm ngay 123% cho đơn từ 123đ. Nhanh tay lưu mã kẻo lỡ!', 0, NULL, '{\"coupon_code\": \"123\"}', '2026-03-05 06:23:45'),
(71, 14, 'new_coupon', '🎁 Mã giảm giá mới: 123', 'Shop vừa tung mã 123 giảm ngay 123% cho đơn từ 123đ. Nhanh tay lưu mã kẻo lỡ!', 0, NULL, '{\"coupon_code\": \"123\"}', '2026-03-05 06:23:45'),
(72, 16, 'new_coupon', '🎁 Mã giảm giá mới: 123', 'Shop vừa tung mã 123 giảm ngay 123% cho đơn từ 123đ. Nhanh tay lưu mã kẻo lỡ!', 0, NULL, '{\"coupon_code\": \"123\"}', '2026-03-05 06:23:45'),
(73, 22, 'new_coupon', '🎁 Mã giảm giá mới: 123', 'Shop vừa tung mã 123 giảm ngay 123% cho đơn từ 123đ. Nhanh tay lưu mã kẻo lỡ!', 0, NULL, '{\"coupon_code\": \"123\"}', '2026-03-05 06:23:45'),
(74, 23, 'new_coupon', '🎁 Mã giảm giá mới: 123', 'Shop vừa tung mã 123 giảm ngay 123% cho đơn từ 123đ. Nhanh tay lưu mã kẻo lỡ!', 0, NULL, '{\"coupon_code\": \"123\"}', '2026-03-05 06:23:45'),
(75, 26, 'new_coupon', '🎁 Mã giảm giá mới: 123', 'Shop vừa tung mã 123 giảm ngay 123% cho đơn từ 123đ. Nhanh tay lưu mã kẻo lỡ!', 0, NULL, '{\"coupon_code\": \"123\"}', '2026-03-05 06:23:45'),
(76, 27, 'new_coupon', '🎁 Mã giảm giá mới: 123', 'Shop vừa tung mã 123 giảm ngay 123% cho đơn từ 123đ. Nhanh tay lưu mã kẻo lỡ!', 1, NULL, '{\"coupon_code\": \"123\"}', '2026-03-05 06:23:45'),
(81, 27, 'system', 'Đặt hàng thành công!', 'Đơn hàng #ORD-69A92556AB18E đã được đặt thành công bằng hình thức COD. Cảm ơn bạn đã mua sắm!', 0, NULL, '{\"order_id\": \"82\"}', '2026-03-05 06:40:28'),
(84, 37, 'system', 'Thanh toán thành công!', 'Chúng tôi đã nhận được khoản thanh toán cho đơn hàng #ORD-69A984641844D. Đơn hàng của bạn đang được xử lý.', 0, NULL, '{\"order_id\": \"85\"}', '2026-03-05 13:26:07'),
(85, 37, 'system', 'Đặt hàng thành công!', 'Đơn hàng #ORD-69A984A4BD009 đã được đặt thành công bằng hình thức COD. Cảm ơn bạn đã mua sắm!', 0, NULL, '{\"order_id\": \"86\"}', '2026-03-05 13:27:05'),
(86, 37, 'system', 'Đặt hàng thành công!', 'Đơn hàng #ORD-69A984B0C9E42 đã được đặt thành công bằng hình thức COD. Cảm ơn bạn đã mua sắm!', 0, NULL, '{\"order_id\": \"87\"}', '2026-03-05 13:27:16'),
(87, 37, 'system', 'Đặt hàng thành công!', 'Đơn hàng #ORD-69A984BB752D5 đã được đặt thành công bằng hình thức COD. Cảm ơn bạn đã mua sắm!', 0, NULL, '{\"order_id\": \"88\"}', '2026-03-05 13:27:27'),
(88, 37, 'system', 'Đặt hàng thành công!', 'Đơn hàng #ORD-69A984C72E0A1 đã được đặt thành công bằng hình thức COD. Cảm ơn bạn đã mua sắm!', 0, NULL, '{\"order_id\": \"89\"}', '2026-03-05 13:27:38'),
(89, 37, 'system', 'Đặt hàng thành công!', 'Đơn hàng #ORD-69A984D16E78D đã được đặt thành công bằng hình thức COD. Cảm ơn bạn đã mua sắm!', 0, NULL, '{\"order_id\": \"90\"}', '2026-03-05 13:27:49'),
(90, 37, 'system', 'Đặt hàng thành công!', 'Đơn hàng #ORD-69A984DC2B391 đã được đặt thành công bằng hình thức COD. Cảm ơn bạn đã mua sắm!', 0, NULL, '{\"order_id\": \"91\"}', '2026-03-05 13:28:00'),
(91, 37, 'system', 'Đặt hàng thành công!', 'Đơn hàng #ORD-69A984E706B98 đã được đặt thành công bằng hình thức COD. Cảm ơn bạn đã mua sắm!', 0, NULL, '{\"order_id\": \"92\"}', '2026-03-05 13:28:11'),
(92, 37, 'system', 'Đặt hàng thành công!', 'Đơn hàng #ORD-69A984F3ADD78 đã được đặt thành công bằng hình thức COD. Cảm ơn bạn đã mua sắm!', 0, NULL, '{\"order_id\": \"93\"}', '2026-03-05 13:28:23'),
(93, 37, 'system', 'Thanh toán thành công!', 'Chúng tôi đã nhận được khoản thanh toán cho đơn hàng #ORD-69A98DB5DA3B6. Đơn hàng của bạn đang được xử lý.', 0, NULL, '{\"order_id\": \"94\"}', '2026-03-05 14:05:52'),
(94, 37, 'system', 'Đặt hàng thành công!', 'Đơn hàng #ORD-69A98EE7465CB đã được đặt thành công bằng hình thức COD. Cảm ơn bạn đã mua sắm!', 0, NULL, '{\"order_id\": \"95\"}', '2026-03-05 14:10:51'),
(95, 37, 'system', 'Đặt hàng thành công!', 'Đơn hàng #ORD-69A98F0C6A04D đã được đặt thành công bằng hình thức COD. Cảm ơn bạn đã mua sắm!', 0, NULL, '{\"order_id\": \"96\"}', '2026-03-05 14:11:28');

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

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
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`id`, `user_id`, `order_number`, `status`, `total_cents`, `subtotal_cents`, `shipping_fee_cents`, `tax_cents`, `shipping_address_id`, `billing_address_id`, `payment_status`, `payment_method`, `placed_at`, `closed_at`, `notes`, `coupon_code`, `created_at`, `updated_at`) VALUES
(24, 13, 'ORD-69748EA0F420C', 'completed', 120000, 90000, 30000, 0, 21, 21, 'unpaid', 'cod', '2026-01-24 16:19:28', NULL, NULL, NULL, '2026-01-24 09:19:28', '2026-01-24 09:19:45'),
(25, 13, 'ORD-69749D24B8430', 'pending', 120000, 90000, 30000, 0, 21, 21, 'unpaid', 'cod', '2026-01-24 17:21:24', NULL, NULL, NULL, '2026-01-24 10:21:24', '2026-01-24 10:21:24'),
(26, 13, 'ORD-69749D6F9BD3F', 'pending', 120000, 90000, 30000, 0, 21, 21, 'unpaid', 'cod', '2026-01-24 17:22:39', NULL, NULL, NULL, '2026-01-24 10:22:39', '2026-01-24 10:22:39'),
(27, 12, 'ORD-697840A378D56', 'pending', 230000, 200000, 30000, 0, 20, 20, 'unpaid', 'cod', '2026-01-27 11:35:47', NULL, NULL, NULL, '2026-01-27 04:35:47', '2026-01-27 04:35:47'),
(28, 12, 'ORD-697841910A401', 'pending', 2030000, 2000000, 30000, 0, 20, 20, 'unpaid', 'cod', '2026-01-27 11:39:45', NULL, NULL, NULL, '2026-01-27 04:39:45', '2026-01-27 04:39:45'),
(29, 12, 'ORD-697841B25B0B8', 'completed', 2030000, 2000000, 30000, 0, 20, 20, 'unpaid', 'cod', '2026-01-27 11:40:18', NULL, NULL, NULL, '2026-01-27 04:40:18', '2026-01-27 04:41:07'),
(30, 12, 'ORD-6978420F9DA9E', 'pending', 230000, 200000, 30000, 0, 20, 20, 'unpaid', 'cod', '2026-01-27 11:41:51', NULL, NULL, NULL, '2026-01-27 04:41:51', '2026-01-27 04:41:51'),
(31, 14, 'ORD-69786A534A2EF', 'pending', 830000, 800000, 30000, 0, 23, 23, 'unpaid', 'cod', '2026-01-27 14:33:39', NULL, NULL, NULL, '2026-01-27 07:33:39', '2026-01-27 07:33:39'),
(32, 14, 'ORD-69786B51E864E', 'completed', 120000, 90000, 30000, 0, 23, 23, 'unpaid', 'cod', '2026-01-27 14:37:53', NULL, NULL, NULL, '2026-01-27 07:37:53', '2026-01-27 07:38:17'),
(33, 14, 'ORD-69845421CA87B', 'shipping', 2230000, 2200000, 30000, 0, 23, 23, 'unpaid', 'cod', '2026-02-05 15:26:09', NULL, NULL, NULL, '2026-02-05 08:26:09', '2026-02-05 08:26:58'),
(34, 14, 'ORD-69A64149E83CC', 'completed', 3780000, 3750000, 30000, 0, 23, 23, 'unpaid', 'cod', '2026-03-03 09:02:49', NULL, NULL, NULL, '2026-03-03 02:02:49', '2026-03-03 02:03:58'),
(35, 14, 'ORD-69A641E185D2F', 'pending', 930000, 900000, 30000, 0, 23, 23, 'unpaid', 'cod', '2026-03-03 09:05:21', NULL, NULL, NULL, '2026-03-03 02:05:21', '2026-03-03 02:05:21'),
(36, 14, 'ORD-69A6425118F47', 'pending', 3890000, 3860000, 30000, 0, 23, 23, 'unpaid', 'cod', '2026-03-03 09:07:13', NULL, NULL, NULL, '2026-03-03 02:07:13', '2026-03-03 02:07:13'),
(37, 14, 'ORD-69A645EFE5B84', 'pending', 1610000, 1580000, 30000, 0, 23, 23, 'unpaid', 'cod', '2026-03-03 09:22:39', NULL, NULL, NULL, '2026-03-03 02:22:39', '2026-03-03 02:22:39'),
(38, 14, 'ORD-69A6463924BAC', 'pending', 1585000, 1555000, 30000, 0, 23, 23, 'unpaid', 'cod', '2026-03-03 09:23:53', NULL, NULL, NULL, '2026-03-03 02:23:53', '2026-03-03 02:23:53'),
(39, 14, 'ORD-69A646A0A348C', 'pending', 4020000, 3990000, 30000, 0, 23, 23, 'unpaid', 'cod', '2026-03-03 09:25:36', NULL, NULL, NULL, '2026-03-03 02:25:36', '2026-03-03 02:25:36'),
(40, 24, 'ORD-69A65E3964BF5', 'cancelled', 90000, 60000, 30000, 0, 24, 24, 'unpaid', 'cod', '2026-03-03 11:06:17', NULL, NULL, NULL, '2026-03-03 04:06:17', '2026-03-03 04:38:07'),
(41, 27, 'ORD-69A67F5963BC8', 'pending', 1610000, 1580000, 30000, 0, 25, 25, 'unpaid', 'cod', '2026-03-03 13:27:37', NULL, NULL, NULL, '2026-03-03 06:27:37', '2026-03-03 06:27:37'),
(42, 27, 'ORD-69A6867FEB8D6', 'pending', 75000, 45000, 30000, 0, 25, 25, 'unpaid', 'cod', '2026-03-03 13:58:07', NULL, NULL, NULL, '2026-03-03 06:58:07', '2026-03-03 06:58:07'),
(43, 27, 'ORD-69A68F06C3515', 'processing', 1590000, 1560000, 30000, 0, 25, 25, 'paid', 'banking', '2026-03-03 14:34:30', NULL, NULL, NULL, '2026-03-03 07:34:30', '2026-03-03 07:35:50'),
(44, 27, 'ORD-69A68F41F3C0C', 'processing', 2660000, 2630000, 30000, 0, 25, 25, 'paid', 'banking', '2026-03-03 14:35:29', NULL, NULL, NULL, '2026-03-03 07:35:29', '2026-03-03 07:36:05'),
(45, 27, 'ORD-69A68F73EBBFB', 'pending', 90000, 60000, 30000, 0, 25, 25, 'unpaid', 'cod', '2026-03-03 14:36:19', NULL, NULL, NULL, '2026-03-03 07:36:19', '2026-03-03 07:36:19'),
(46, 27, 'ORD-69A68F83BF224', 'processing', 90000, 60000, 30000, 0, 25, 25, 'paid', 'banking', '2026-03-03 14:36:35', NULL, NULL, NULL, '2026-03-03 07:36:35', '2026-03-03 07:36:40'),
(47, 27, 'ORD-69A690C498535', 'pending', 75000, 45000, 30000, 0, 25, 25, 'paid', 'banking', '2026-03-03 14:41:56', NULL, NULL, NULL, '2026-03-03 07:41:56', '2026-03-03 07:42:40'),
(48, 27, 'ORD-69A6A15D88089', 'pending', 1045000, 1015000, 30000, 0, 25, 25, 'unpaid', 'cod', '2026-03-03 15:52:45', NULL, NULL, NULL, '2026-03-03 08:52:45', '2026-03-03 08:52:45'),
(49, 27, 'ORD-69A6A3E26EF5F', 'pending', 1045000, 1015000, 30000, 0, 25, 25, 'unpaid', 'cod', '2026-03-03 16:03:30', NULL, NULL, NULL, '2026-03-03 09:03:30', '2026-03-03 09:03:30'),
(50, 27, 'ORD-69A6ABA9E64D0', 'pending', 780000, 1500000, 30000, 0, 25, 25, 'unpaid', 'cod', '2026-03-03 16:36:41', NULL, NULL, NULL, '2026-03-03 09:36:41', '2026-03-03 09:36:41'),
(51, 27, 'ORD-69A6ABAEB206F', 'pending', 780000, 1500000, 30000, 0, 25, 25, 'unpaid', 'cod', '2026-03-03 16:36:46', NULL, NULL, NULL, '2026-03-03 09:36:46', '2026-03-03 09:36:46'),
(52, 27, 'ORD-69A6ABBA40171', 'pending', 780000, 1500000, 30000, 0, 25, 25, 'paid', 'banking', '2026-03-03 16:36:58', NULL, NULL, NULL, '2026-03-03 09:36:58', '2026-03-04 03:52:44'),
(53, 27, 'ORD-69A6ABCDDC078', 'pending', 780000, 1500000, 30000, 0, 25, 25, 'unpaid', 'cod', '2026-03-03 16:37:17', NULL, NULL, NULL, '2026-03-03 09:37:17', '2026-03-03 09:37:17'),
(54, 27, 'ORD-69A6AC02CD6DB', 'pending', 780000, 1500000, 30000, 0, 25, 25, 'unpaid', 'cod', '2026-03-03 16:38:10', NULL, NULL, NULL, '2026-03-03 09:38:10', '2026-03-03 09:38:10'),
(55, 27, 'ORD-69A6AC2DB8F1F', 'pending', 780000, 1500000, 30000, 0, 25, 25, 'unpaid', 'cod', '2026-03-03 16:38:53', NULL, NULL, NULL, '2026-03-03 09:38:53', '2026-03-03 09:38:53'),
(56, 27, 'ORD-69A6AE20769D2', 'pending', 780000, 1500000, 30000, 0, 25, 25, 'unpaid', 'cod', '2026-03-03 16:47:12', NULL, NULL, NULL, '2026-03-03 09:47:12', '2026-03-03 09:47:12'),
(57, 27, 'ORD-69A6AFD11A599', 'pending', 530000, 1000000, 30000, 0, 25, 25, 'unpaid', 'cod', '2026-03-03 16:54:25', NULL, NULL, NULL, '2026-03-03 09:54:25', '2026-03-03 09:54:25'),
(58, 27, 'ORD-69A6AFF422C69', 'pending', 1030000, 2000000, 30000, 0, 25, 25, 'unpaid', 'cod', '2026-03-03 16:55:00', NULL, NULL, NULL, '2026-03-03 09:55:00', '2026-03-03 09:55:00'),
(59, 27, 'ORD-69A6B0FBE546A', 'pending', 1030000, 2000000, 30000, 0, 25, 25, 'unpaid', 'cod', '2026-03-03 16:59:23', NULL, NULL, NULL, '2026-03-03 09:59:23', '2026-03-03 09:59:23'),
(60, 27, 'ORD-69A7A08B575C4', 'pending', 1090000, 1060000, 30000, 0, 25, 25, 'paid', 'banking', '2026-03-04 10:01:31', NULL, NULL, NULL, '2026-03-04 03:01:31', '2026-03-04 03:01:39'),
(61, 27, 'ORD-69A821C250449', 'pending', 75000, 45000, 30000, 0, 25, 25, 'unpaid', 'cod', '2026-03-04 19:12:50', NULL, NULL, NULL, '2026-03-04 12:12:50', '2026-03-04 12:12:50'),
(62, 27, 'ORD-69A821F3D684E', 'pending', 530000, 1000000, 30000, 0, 25, 25, 'unpaid', 'cod', '2026-03-04 19:13:39', NULL, NULL, NULL, '2026-03-04 12:13:39', '2026-03-04 12:13:39'),
(63, 27, 'ORD-69A822051F9C4', 'processing', 530000, 1000000, 30000, 0, 25, 25, 'paid', 'banking', '2026-03-04 19:13:57', NULL, NULL, NULL, '2026-03-04 12:13:57', '2026-03-04 12:14:07'),
(64, 27, 'ORD-69A822BE6C7D6', 'processing', 530000, 1000000, 30000, 0, 25, 25, 'paid', 'banking', '2026-03-04 19:17:02', NULL, NULL, NULL, '2026-03-04 12:17:02', '2026-03-04 12:17:09'),
(65, 27, 'ORD-69A822E1F02C9', 'pending', 1030000, 1000000, 30000, 0, 25, 25, 'unpaid', 'cod', '2026-03-04 19:17:37', NULL, NULL, NULL, '2026-03-04 12:17:37', '2026-03-04 12:17:37'),
(66, 27, 'ORD-69A82415AA4FD', 'pending', 75000, 45000, 30000, 0, 25, 25, 'unpaid', 'cod', '2026-03-04 19:22:45', NULL, NULL, NULL, '2026-03-04 12:22:45', '2026-03-04 12:22:45'),
(67, 27, 'ORD-69A8349BC3428', 'pending', 2210000, 2180000, 30000, 0, 25, 25, 'unpaid', 'cod', '2026-03-04 20:33:15', NULL, NULL, NULL, '2026-03-04 13:33:15', '2026-03-04 13:33:15'),
(68, 27, 'ORD-69A834BBC0291', 'pending', 530000, 500000, 30000, 0, 25, 25, 'unpaid', 'cod', '2026-03-04 20:33:47', NULL, NULL, NULL, '2026-03-04 13:33:47', '2026-03-04 13:33:47'),
(69, 27, 'ORD-69A835080B4A0', 'pending', 45000, 15000, 30000, 0, 25, 25, 'unpaid', 'cod', '2026-03-04 20:35:04', NULL, NULL, NULL, '2026-03-04 13:35:04', '2026-03-04 13:35:04'),
(70, 27, 'ORD-69A835713078B', 'pending', 1030000, 1000000, 30000, 0, 25, 25, 'unpaid', 'cod', '2026-03-04 20:36:49', NULL, NULL, NULL, '2026-03-04 13:36:49', '2026-03-04 13:36:49'),
(71, 27, 'ORD-69A8358A7D7EA', 'cancelled', 45000, 15000, 30000, 0, 25, 25, 'paid', 'banking', '2026-03-04 20:37:14', NULL, NULL, NULL, '2026-03-04 13:37:14', '2026-03-04 13:45:15'),
(72, 27, 'ORD-69A837172E9A9', 'completed', 45000, 15000, 30000, 0, 25, 25, 'unpaid', 'cod', '2026-03-04 20:43:51', NULL, NULL, NULL, '2026-03-04 13:43:51', '2026-03-04 13:45:05'),
(73, 27, 'ORD-69A83722C83D9', 'shipping', 1030000, 1000000, 30000, 0, 25, 25, 'paid', 'banking', '2026-03-04 20:44:02', NULL, NULL, NULL, '2026-03-04 13:44:02', '2026-03-04 13:44:57'),
(74, 27, 'ORD-69A838FE9DCC1', 'completed', 1530000, 1500000, 30000, 0, 25, 25, 'unpaid', 'cod', '2026-03-04 20:51:58', NULL, NULL, NULL, '2026-03-04 13:51:58', '2026-03-04 13:53:05'),
(75, 27, 'ORD-69A83910619AC', 'shipping', 1030000, 1000000, 30000, 0, 25, 25, 'paid', 'banking', '2026-03-04 20:52:16', NULL, NULL, NULL, '2026-03-04 13:52:16', '2026-03-04 13:53:01'),
(76, 27, 'ORD-69A8397A6B4BA', 'pending', 45000, 15000, 30000, 0, 25, 25, 'unpaid', 'cod', '2026-03-04 20:54:02', NULL, NULL, NULL, '2026-03-04 13:54:02', '2026-03-04 13:54:02'),
(77, 27, 'ORD-69A8398F9C77F', 'pending', 530000, 500000, 30000, 0, 25, 25, 'unpaid', 'cod', '2026-03-04 20:54:23', NULL, NULL, NULL, '2026-03-04 13:54:23', '2026-03-04 13:54:23'),
(78, 27, 'ORD-69A839B936DC6', 'pending', 1030000, 1000000, 30000, 0, 25, 25, 'unpaid', 'cod', '2026-03-04 20:55:05', NULL, NULL, NULL, '2026-03-04 13:55:05', '2026-03-04 13:55:05'),
(79, 27, 'ORD-69A839CAE08BA', 'pending', 1030000, 1000000, 30000, 0, 25, 25, 'unpaid', 'cod', '2026-03-04 20:55:22', NULL, NULL, NULL, '2026-03-04 13:55:22', '2026-03-04 13:55:22'),
(80, 27, 'ORD-69A83A2058072', 'pending', 530000, 1000000, 30000, 0, 25, 25, 'unpaid', 'cod', '2026-03-04 20:56:48', NULL, NULL, NULL, '2026-03-04 13:56:48', '2026-03-04 13:56:48'),
(81, 27, 'ORD-69A92144A8ECD', 'processing', 1545000, 1515000, 30000, 0, 25, 25, 'paid', 'banking', '2026-03-05 13:23:00', NULL, NULL, NULL, '2026-03-05 06:23:00', '2026-03-05 06:23:06'),
(82, 27, 'ORD-69A92556AB18E', 'pending', 45000, 15000, 30000, 0, 25, 25, 'unpaid', 'cod', '2026-03-05 13:40:22', NULL, NULL, NULL, '2026-03-05 06:40:22', '2026-03-05 06:40:22'),
(83, 37, 'ORD-69A94761E03AF', 'pending', 1045000, 1515000, 30000, 0, 26, 26, 'unpaid', 'cod', '2026-03-05 16:05:37', NULL, NULL, NULL, '2026-03-05 09:05:37', '2026-03-05 09:05:37'),
(84, 37, 'ORD-69A94784C224A', 'pending', 1545000, 1515000, 30000, 0, 26, 26, 'unpaid', 'cod', '2026-03-05 16:06:12', NULL, NULL, NULL, '2026-03-05 09:06:12', '2026-03-05 09:06:12'),
(85, 37, 'ORD-69A984641844D', 'processing', 3072000, 3042000, 30000, 0, 27, 27, 'paid', 'banking', '2026-03-05 20:25:56', NULL, NULL, NULL, '2026-03-05 13:25:56', '2026-03-05 13:26:02'),
(86, 37, 'ORD-69A984A4BD009', 'pending', 45000, 15000, 30000, 0, 27, 27, 'unpaid', 'cod', '2026-03-05 20:27:00', NULL, NULL, NULL, '2026-03-05 13:27:00', '2026-03-05 13:27:00'),
(87, 37, 'ORD-69A984B0C9E42', 'pending', 1030000, 1000000, 30000, 0, 27, 27, 'unpaid', 'cod', '2026-03-05 20:27:12', NULL, NULL, NULL, '2026-03-05 13:27:12', '2026-03-05 13:27:12'),
(88, 37, 'ORD-69A984BB752D5', 'pending', 530000, 500000, 30000, 0, 27, 27, 'unpaid', 'cod', '2026-03-05 20:27:23', NULL, NULL, NULL, '2026-03-05 13:27:23', '2026-03-05 13:27:23'),
(89, 37, 'ORD-69A984C72E0A1', 'pending', 330000, 300000, 30000, 0, 27, 27, 'unpaid', 'cod', '2026-03-05 20:27:35', NULL, NULL, NULL, '2026-03-05 13:27:35', '2026-03-05 13:27:35'),
(90, 37, 'ORD-69A984D16E78D', 'pending', 50000, 20000, 30000, 0, 27, 27, 'unpaid', 'cod', '2026-03-05 20:27:45', NULL, NULL, NULL, '2026-03-05 13:27:45', '2026-03-05 13:27:45'),
(91, 37, 'ORD-69A984DC2B391', 'pending', 50000, 20000, 30000, 0, 27, 27, 'unpaid', 'cod', '2026-03-05 20:27:56', NULL, NULL, NULL, '2026-03-05 13:27:56', '2026-03-05 13:27:56'),
(92, 37, 'ORD-69A984E706B98', 'pending', 330000, 300000, 30000, 0, 27, 27, 'unpaid', 'cod', '2026-03-05 20:28:07', NULL, NULL, NULL, '2026-03-05 13:28:07', '2026-03-05 13:28:07'),
(93, 37, 'ORD-69A984F3ADD78', 'pending', 50000, 20000, 30000, 0, 27, 27, 'unpaid', 'cod', '2026-03-05 20:28:19', NULL, NULL, NULL, '2026-03-05 13:28:19', '2026-03-05 13:28:19'),
(94, 37, 'ORD-69A98DB5DA3B6', 'processing', 80000, 50000, 30000, 0, 27, 27, 'paid', 'banking', '2026-03-05 21:05:41', NULL, NULL, NULL, '2026-03-05 14:05:41', '2026-03-05 14:05:47'),
(95, 37, 'ORD-69A98EE7465CB', 'pending', 1030000, 1000000, 30000, 0, 27, 27, 'unpaid', 'cod', '2026-03-05 21:10:47', NULL, NULL, NULL, '2026-03-05 14:10:47', '2026-03-05 14:10:47'),
(96, 37, 'ORD-69A98F0C6A04D', 'pending', 1030000, 1000000, 30000, 0, 27, 27, 'unpaid', 'cod', '2026-03-05 21:11:24', NULL, NULL, NULL, '2026-03-05 14:11:24', '2026-03-05 14:11:24');

-- --------------------------------------------------------

--
-- Table structure for table `order_coupons`
--

CREATE TABLE `order_coupons` (
  `id` bigint UNSIGNED NOT NULL,
  `order_id` bigint UNSIGNED DEFAULT NULL,
  `coupon_id` bigint UNSIGNED DEFAULT NULL,
  `applied_amount_cents` bigint DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `order_coupons`
--

INSERT INTO `order_coupons` (`id`, `order_id`, `coupon_id`, `applied_amount_cents`) VALUES
(1, 50, 4, 750000),
(2, 51, 4, 750000),
(3, 52, 4, 750000),
(4, 53, 4, 750000),
(5, 54, 4, 750000),
(6, 55, 4, 750000),
(7, 56, 4, 750000),
(8, 57, 4, 500000),
(9, 58, 4, 1000000),
(10, 59, 4, 1000000),
(11, 62, 4, 500000),
(12, 63, 4, 500000),
(13, 64, 4, 500000),
(14, 80, 4, 500000),
(15, 83, 4, 500000);

-- --------------------------------------------------------

--
-- Table structure for table `order_items`
--

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
-- Dumping data for table `order_items`
--

INSERT INTO `order_items` (`id`, `order_id`, `variant_id`, `product_id`, `product_snapshot`, `quantity`, `unit_price_cents`, `total_price_cents`) VALUES
(42, 24, 36, NULL, '{\"name\": \"Bắp ngô ngọt hữu cơ \", \"image\": \"assets/uploads/1769191604_tai_xuong_152d8a679915478187edbb2f6291a8b5_grande.jpg\"}', 1, 90000, 90000),
(43, 25, 36, NULL, '{\"name\": \"Bắp ngô ngọt hữu cơ \", \"image\": \"assets/uploads/1769191604_tai_xuong_152d8a679915478187edbb2f6291a8b5_grande.jpg\"}', 1, 90000, 90000),
(44, 26, 36, NULL, '{\"name\": \"Bắp ngô ngọt hữu cơ \", \"image\": \"assets/uploads/1769191604_tai_xuong_152d8a679915478187edbb2f6291a8b5_grande.jpg\"}', 1, 90000, 90000),
(45, 27, 35, NULL, '{\"name\": \"Cà chua xay nhuyễn hữu cơ Passata\", \"image\": \"assets/uploads/1769191565_ca_chua_xay_nhuyen_huu_co_passata_hung_que_luce_680g_beb71dda50b34667b2955e1d555de6bb_grande.png\"}', 1, 200000, 200000),
(46, 28, 34, NULL, '{\"name\": \"Mật ong pure origins leatherwood\", \"image\": \"assets/uploads/1769191521_mat_ong_pure_origins_leatherwood_250g.png\"}', 1, 2000000, 2000000),
(47, 29, 34, NULL, '{\"name\": \"Mật ong pure origins leatherwood\", \"image\": \"assets/uploads/1769191521_mat_ong_pure_origins_leatherwood_250g.png\"}', 1, 2000000, 2000000),
(48, 30, 35, NULL, '{\"name\": \"Cà chua xay nhuyễn hữu cơ Passata\", \"image\": \"assets/uploads/1769191565_ca_chua_xay_nhuyen_huu_co_passata_hung_que_luce_680g_beb71dda50b34667b2955e1d555de6bb_grande.png\"}', 1, 200000, 200000),
(49, 31, 35, NULL, '{\"name\": \"Cà chua xay nhuyễn hữu cơ Passata\", \"image\": \"assets/uploads/1769191565_ca_chua_xay_nhuyen_huu_co_passata_hung_que_luce_680g_beb71dda50b34667b2955e1d555de6bb_grande.png\"}', 4, 200000, 800000),
(50, 32, 36, NULL, '{\"name\": \"Bắp ngô ngọt hữu cơ \", \"image\": \"assets/uploads/1769191604_tai_xuong_152d8a679915478187edbb2f6291a8b5_grande.jpg\"}', 1, 90000, 90000),
(51, 33, 34, NULL, '{\"name\": \"Mật ong pure origins leatherwood\", \"image\": \"assets/uploads/products/1769191521_mat_ong_pure_origins_leatherwood_250g.png\"}', 1, 2000000, 2000000),
(52, 33, 35, NULL, '{\"name\": \"Cà chua xay nhuyễn hữu cơ Passata\", \"image\": \"assets/uploads/products/1769191565_ca_chua_xay_nhuyen_huu_co_passata_hung_que_luce_680g_beb71dda50b34667b2955e1d555de6bb_grande.png\"}', 1, 200000, 200000),
(53, 34, 33, NULL, '{\"name\": \"Mật Ong Manuka 400+ Pure Origins\", \"image\": \"assets/uploads/products/1769191474_mat-ong.jpg\"}', 1, 1500000, 1500000),
(54, 34, 34, NULL, '{\"name\": \"Mật ong pure origins leatherwood\", \"image\": \"assets/uploads/products/1769191521_mat_ong_pure_origins_leatherwood_250g.png\"}', 1, 2000000, 2000000),
(55, 34, 35, NULL, '{\"name\": \"Cà chua xay nhuyễn hữu cơ Passata\", \"image\": \"assets/uploads/products/1769191565_ca_chua_xay_nhuyen_huu_co_passata_hung_que_luce_680g_beb71dda50b34667b2955e1d555de6bb_grande.png\"}', 1, 250000, 250000),
(56, 35, 77, NULL, '{\"name\": \"Nui nơ pastina hữu cơ\", \"image\": \"assets/uploads/products/1769191252_nui-no-pastina-huu-co.png\"}', 1, 400000, 400000),
(57, 35, 79, NULL, '{\"name\": \"Nui Rau Củ Hữu Cơ Cho Bé Hình Ô Tô\", \"image\": \"assets/uploads/products/1769191304_nui_rau_cu_huu_co_cho_be_hinh_o_to_300g_dalla_costa.jpeg\"}', 1, 500000, 500000),
(58, 36, 62, NULL, '{\"name\": \"Cải thìa hữu cơ Sunny Harvest\", \"image\": \"assets/uploads/products/1769190872_c_i_th_a_.png\"}', 1, 20000, 20000),
(59, 36, 36, NULL, '{\"name\": \"Bắp ngô ngọt hữu cơ \", \"image\": \"assets/uploads/products/1769191604_tai_xuong_152d8a679915478187edbb2f6291a8b5_grande.jpg\"}', 1, 90000, 90000),
(60, 36, 33, NULL, '{\"name\": \"Mật Ong Manuka 400+ Pure Origins\", \"image\": \"assets/uploads/products/1769191474_mat-ong.jpg\"}', 1, 1500000, 1500000),
(61, 36, 34, NULL, '{\"name\": \"Mật ong pure origins leatherwood\", \"image\": \"assets/uploads/products/1769191521_mat_ong_pure_origins_leatherwood_250g.png\"}', 1, 2000000, 2000000),
(62, 36, 35, NULL, '{\"name\": \"Cà chua xay nhuyễn hữu cơ Passata\", \"image\": \"assets/uploads/products/1769191565_ca_chua_xay_nhuyen_huu_co_passata_hung_que_luce_680g_beb71dda50b34667b2955e1d555de6bb_grande.png\"}', 1, 250000, 250000),
(63, 37, 62, NULL, '{\"name\": \"Cải thìa hữu cơ Sunny Harvest\", \"image\": \"assets/uploads/products/1769190872_c_i_th_a_.png\"}', 1, 20000, 20000),
(64, 37, 90, NULL, '{\"name\": \"Bắp ngô ngọt hữu cơ \", \"image\": \"assets/uploads/products/1769191604_tai_xuong_152d8a679915478187edbb2f6291a8b5_grande.jpg\"}', 1, 45000, 45000),
(65, 37, 86, NULL, '{\"name\": \"Mật Ong Manuka 400+ Pure Origins\", \"image\": \"assets/uploads/products/1769191474_mat-ong.jpg\"}', 1, 500000, 500000),
(66, 37, 88, NULL, '{\"name\": \"Mật ong pure origins leatherwood\", \"image\": \"assets/uploads/products/1769191521_mat_ong_pure_origins_leatherwood_250g.png\"}', 1, 1000000, 1000000),
(67, 37, 44, NULL, '{\"name\": \"Cà chua xay nhuyễn hữu cơ Passata\", \"image\": \"assets/uploads/products/1769191565_ca_chua_xay_nhuyen_huu_co_passata_hung_que_luce_680g_beb71dda50b34667b2955e1d555de6bb_grande.png\"}', 1, 15000, 15000),
(68, 38, 61, NULL, '{\"name\": \"Cải thìa hữu cơ Sunny Harvest\", \"image\": \"assets/uploads/products/1769190872_c_i_th_a_.png\"}', 1, 40000, 40000),
(69, 38, 86, NULL, '{\"name\": \"Mật Ong Manuka 400+ Pure Origins\", \"image\": \"assets/uploads/products/1769191474_mat-ong.jpg\"}', 1, 500000, 500000),
(70, 38, 88, NULL, '{\"name\": \"Mật ong pure origins leatherwood\", \"image\": \"assets/uploads/products/1769191521_mat_ong_pure_origins_leatherwood_250g.png\"}', 1, 1000000, 1000000),
(71, 38, 44, NULL, '{\"name\": \"Cà chua xay nhuyễn hữu cơ Passata\", \"image\": \"assets/uploads/products/1769191565_ca_chua_xay_nhuyen_huu_co_passata_hung_que_luce_680g_beb71dda50b34667b2955e1d555de6bb_grande.png\"}', 1, 15000, 15000),
(72, 39, 85, NULL, '{\"name\": \"Mật Ong Manuka 400+ Pure Origins\", \"image\": \"assets/uploads/products/1769191474_mat-ong.jpg\"}', 1, 1500000, 1500000),
(73, 39, 87, NULL, '{\"name\": \"Mật ong pure origins leatherwood\", \"image\": \"assets/uploads/products/1769191521_mat_ong_pure_origins_leatherwood_250g.png\"}', 1, 2000000, 2000000),
(74, 39, 43, NULL, '{\"name\": \"Cà chua xay nhuyễn hữu cơ Passata\", \"image\": \"assets/uploads/products/1769191565_ca_chua_xay_nhuyen_huu_co_passata_hung_que_luce_680g_beb71dda50b34667b2955e1d555de6bb_grande.png\"}', 1, 400000, 400000),
(75, 39, 89, NULL, '{\"name\": \"Bắp ngô ngọt hữu cơ \", \"image\": \"assets/uploads/products/1769191604_tai_xuong_152d8a679915478187edbb2f6291a8b5_grande.jpg\"}', 1, 90000, 90000),
(76, 40, 44, NULL, '{\"name\": \"Cà chua xay nhuyễn hữu cơ Passata\", \"image\": \"assets/uploads/products/1769191565_ca_chua_xay_nhuyen_huu_co_passata_hung_que_luce_680g_beb71dda50b34667b2955e1d555de6bb_grande.png\"}', 1, 15000, 15000),
(77, 40, 90, NULL, '{\"name\": \"Bắp ngô ngọt hữu cơ \", \"image\": \"assets/uploads/products/1769191604_tai_xuong_152d8a679915478187edbb2f6291a8b5_grande.jpg\"}', 1, 45000, 45000),
(78, 41, 62, NULL, '{\"name\": \"Cải thìa hữu cơ Sunny Harvest\", \"image\": \"assets/uploads/products/1769190872_c_i_th_a_.png\"}', 1, 20000, 20000),
(79, 41, 86, NULL, '{\"name\": \"Mật Ong Manuka 400+ Pure Origins\", \"image\": \"assets/uploads/products/1769191474_mat-ong.jpg\"}', 1, 500000, 500000),
(80, 41, 88, NULL, '{\"name\": \"Mật ong pure origins leatherwood\", \"image\": \"assets/uploads/products/1769191521_mat_ong_pure_origins_leatherwood_250g.png\"}', 1, 1000000, 1000000),
(81, 41, 44, NULL, '{\"name\": \"Cà chua xay nhuyễn hữu cơ Passata\", \"image\": \"assets/uploads/products/1769191565_ca_chua_xay_nhuyen_huu_co_passata_hung_que_luce_680g_beb71dda50b34667b2955e1d555de6bb_grande.png\"}', 1, 15000, 15000),
(82, 41, 90, NULL, '{\"name\": \"Bắp ngô ngọt hữu cơ \", \"image\": \"assets/uploads/products/1769191604_tai_xuong_152d8a679915478187edbb2f6291a8b5_grande.jpg\"}', 1, 45000, 45000),
(83, 42, 90, NULL, '{\"name\": \"Bắp ngô ngọt hữu cơ \", \"image\": \"assets/uploads/products/1769191604_tai_xuong_152d8a679915478187edbb2f6291a8b5_grande.jpg\"}', 1, 45000, 45000),
(84, 43, 86, NULL, '{\"name\": \"Mật Ong Manuka 400+ Pure Origins\", \"image\": \"assets/uploads/products/1769191474_mat-ong.jpg\"}', 1, 500000, 500000),
(85, 43, 88, NULL, '{\"name\": \"Mật ong pure origins leatherwood\", \"image\": \"assets/uploads/products/1769191521_mat_ong_pure_origins_leatherwood_250g.png\"}', 1, 1000000, 1000000),
(86, 43, 44, NULL, '{\"name\": \"Cà chua xay nhuyễn hữu cơ Passata\", \"image\": \"assets/uploads/products/1769191565_ca_chua_xay_nhuyen_huu_co_passata_hung_que_luce_680g_beb71dda50b34667b2955e1d555de6bb_grande.png\"}', 1, 15000, 15000),
(87, 43, 90, NULL, '{\"name\": \"Bắp ngô ngọt hữu cơ \", \"image\": \"assets/uploads/products/1769191604_tai_xuong_152d8a679915478187edbb2f6291a8b5_grande.jpg\"}', 1, 45000, 45000),
(88, 44, 78, NULL, '{\"name\": \"Nui nơ pastina hữu cơ\", \"image\": \"assets/uploads/products/1769191252_nui-no-pastina-huu-co.png\"}', 1, 20000, 20000),
(89, 44, 80, NULL, '{\"name\": \"Nui Rau Củ Hữu Cơ Cho Bé Hình Ô Tô\", \"image\": \"assets/uploads/products/1769191304_nui_rau_cu_huu_co_cho_be_hinh_o_to_300g_dalla_costa.jpeg\"}', 1, 250000, 250000),
(90, 44, 82, NULL, '{\"name\": \"Dầu oliu ép lạnh hữu cơ\", \"image\": \"assets/uploads/products/1769191359_dau-oliu-huu-co-ep-lanh-bioitalia-.jpg\"}', 1, 300000, 300000),
(91, 44, 84, NULL, '{\"name\": \"Mì spaghetti rau củ quả hữu cơ\", \"image\": \"assets/uploads/products/1769191403_mi_spaghetti_rau_cu_qua_huu_co_.jpg\"}', 1, 500000, 500000),
(92, 44, 86, NULL, '{\"name\": \"Mật Ong Manuka 400+ Pure Origins\", \"image\": \"assets/uploads/products/1769191474_mat-ong.jpg\"}', 1, 500000, 500000),
(93, 44, 88, NULL, '{\"name\": \"Mật ong pure origins leatherwood\", \"image\": \"assets/uploads/products/1769191521_mat_ong_pure_origins_leatherwood_250g.png\"}', 1, 1000000, 1000000),
(94, 44, 44, NULL, '{\"name\": \"Cà chua xay nhuyễn hữu cơ Passata\", \"image\": \"assets/uploads/products/1769191565_ca_chua_xay_nhuyen_huu_co_passata_hung_que_luce_680g_beb71dda50b34667b2955e1d555de6bb_grande.png\"}', 1, 15000, 15000),
(95, 44, 90, NULL, '{\"name\": \"Bắp ngô ngọt hữu cơ \", \"image\": \"assets/uploads/products/1769191604_tai_xuong_152d8a679915478187edbb2f6291a8b5_grande.jpg\"}', 1, 45000, 45000),
(96, 45, 44, NULL, '{\"name\": \"Cà chua xay nhuyễn hữu cơ Passata\", \"image\": \"assets/uploads/products/1769191565_ca_chua_xay_nhuyen_huu_co_passata_hung_que_luce_680g_beb71dda50b34667b2955e1d555de6bb_grande.png\"}', 1, 15000, 15000),
(97, 45, 90, NULL, '{\"name\": \"Bắp ngô ngọt hữu cơ \", \"image\": \"assets/uploads/products/1769191604_tai_xuong_152d8a679915478187edbb2f6291a8b5_grande.jpg\"}', 1, 45000, 45000),
(98, 46, 44, NULL, '{\"name\": \"Cà chua xay nhuyễn hữu cơ Passata\", \"image\": \"assets/uploads/products/1769191565_ca_chua_xay_nhuyen_huu_co_passata_hung_que_luce_680g_beb71dda50b34667b2955e1d555de6bb_grande.png\"}', 1, 15000, 15000),
(99, 46, 90, NULL, '{\"name\": \"Bắp ngô ngọt hữu cơ \", \"image\": \"assets/uploads/products/1769191604_tai_xuong_152d8a679915478187edbb2f6291a8b5_grande.jpg\"}', 1, 45000, 45000),
(100, 47, 90, NULL, '{\"name\": \"Bắp ngô ngọt hữu cơ \", \"image\": \"assets/uploads/products/1769191604_tai_xuong_152d8a679915478187edbb2f6291a8b5_grande.jpg\"}', 1, 45000, 45000),
(101, 48, 88, NULL, '{\"name\": \"Mật ong pure origins leatherwood\", \"image\": \"assets/uploads/products/1769191521_mat_ong_pure_origins_leatherwood_250g.png\"}', 1, 1000000, 1000000),
(102, 48, 44, NULL, '{\"name\": \"Cà chua xay nhuyễn hữu cơ Passata\", \"image\": \"assets/uploads/products/1769191565_ca_chua_xay_nhuyen_huu_co_passata_hung_que_luce_680g_beb71dda50b34667b2955e1d555de6bb_grande.png\"}', 1, 15000, 15000),
(103, 49, 88, NULL, '{\"name\": \"Mật ong pure origins leatherwood\", \"image\": \"assets/uploads/products/1769191521_mat_ong_pure_origins_leatherwood_250g.png\"}', 1, 1000000, 1000000),
(104, 49, 44, NULL, '{\"name\": \"Cà chua xay nhuyễn hữu cơ Passata\", \"image\": \"assets/uploads/products/1769191565_ca_chua_xay_nhuyen_huu_co_passata_hung_que_luce_680g_beb71dda50b34667b2955e1d555de6bb_grande.png\"}', 1, 15000, 15000),
(105, 50, 86, NULL, '{\"name\": \"Mật Ong Manuka 400+ Pure Origins\", \"image\": \"assets/uploads/products/1769191474_mat-ong.jpg\"}', 1, 500000, 500000),
(106, 50, 88, NULL, '{\"name\": \"Mật ong pure origins leatherwood\", \"image\": \"assets/uploads/products/1769191521_mat_ong_pure_origins_leatherwood_250g.png\"}', 1, 1000000, 1000000),
(107, 51, 86, NULL, '{\"name\": \"Mật Ong Manuka 400+ Pure Origins\", \"image\": \"assets/uploads/products/1769191474_mat-ong.jpg\"}', 1, 500000, 500000),
(108, 51, 88, NULL, '{\"name\": \"Mật ong pure origins leatherwood\", \"image\": \"assets/uploads/products/1769191521_mat_ong_pure_origins_leatherwood_250g.png\"}', 1, 1000000, 1000000),
(109, 52, 86, NULL, '{\"name\": \"Mật Ong Manuka 400+ Pure Origins\", \"image\": \"assets/uploads/products/1769191474_mat-ong.jpg\"}', 1, 500000, 500000),
(110, 52, 88, NULL, '{\"name\": \"Mật ong pure origins leatherwood\", \"image\": \"assets/uploads/products/1769191521_mat_ong_pure_origins_leatherwood_250g.png\"}', 1, 1000000, 1000000),
(111, 53, 86, NULL, '{\"name\": \"Mật Ong Manuka 400+ Pure Origins\", \"image\": \"assets/uploads/products/1769191474_mat-ong.jpg\"}', 1, 500000, 500000),
(112, 53, 88, NULL, '{\"name\": \"Mật ong pure origins leatherwood\", \"image\": \"assets/uploads/products/1769191521_mat_ong_pure_origins_leatherwood_250g.png\"}', 1, 1000000, 1000000),
(113, 54, 86, NULL, '{\"name\": \"Mật Ong Manuka 400+ Pure Origins\", \"image\": \"assets/uploads/products/1769191474_mat-ong.jpg\"}', 1, 500000, 500000),
(114, 54, 88, NULL, '{\"name\": \"Mật ong pure origins leatherwood\", \"image\": \"assets/uploads/products/1769191521_mat_ong_pure_origins_leatherwood_250g.png\"}', 1, 1000000, 1000000),
(115, 55, 86, NULL, '{\"name\": \"Mật Ong Manuka 400+ Pure Origins\", \"image\": \"assets/uploads/products/1769191474_mat-ong.jpg\"}', 1, 500000, 500000),
(116, 55, 88, NULL, '{\"name\": \"Mật ong pure origins leatherwood\", \"image\": \"assets/uploads/products/1769191521_mat_ong_pure_origins_leatherwood_250g.png\"}', 1, 1000000, 1000000),
(117, 56, 86, NULL, '{\"name\": \"Mật Ong Manuka 400+ Pure Origins\", \"image\": \"assets/uploads/products/1769191474_mat-ong.jpg\"}', 1, 500000, 500000),
(118, 56, 88, NULL, '{\"name\": \"Mật ong pure origins leatherwood\", \"image\": \"assets/uploads/products/1769191521_mat_ong_pure_origins_leatherwood_250g.png\"}', 1, 1000000, 1000000),
(119, 57, 88, NULL, '{\"name\": \"Mật ong pure origins leatherwood\", \"image\": \"assets/uploads/products/1769191521_mat_ong_pure_origins_leatherwood_250g.png\"}', 1, 1000000, 1000000),
(120, 58, 88, NULL, '{\"name\": \"Mật ong pure origins leatherwood\", \"image\": \"assets/uploads/products/1769191521_mat_ong_pure_origins_leatherwood_250g.png\"}', 2, 1000000, 2000000),
(121, 59, 88, NULL, '{\"name\": \"Mật ong pure origins leatherwood\", \"image\": \"assets/uploads/products/1769191521_mat_ong_pure_origins_leatherwood_250g.png\"}', 2, 1000000, 2000000),
(122, 60, 88, NULL, '{\"name\": \"Mật ong pure origins leatherwood\", \"image\": \"assets/uploads/products/1769191521_mat_ong_pure_origins_leatherwood_250g.png\"}', 1, 1000000, 1000000),
(123, 60, 44, NULL, '{\"name\": \"Cà chua xay nhuyễn hữu cơ Passata\", \"image\": \"assets/uploads/products/1769191565_ca_chua_xay_nhuyen_huu_co_passata_hung_que_luce_680g_beb71dda50b34667b2955e1d555de6bb_grande.png\"}', 1, 15000, 15000),
(124, 60, 90, NULL, '{\"name\": \"Bắp ngô ngọt hữu cơ \", \"image\": \"assets/uploads/products/1769191604_tai_xuong_152d8a679915478187edbb2f6291a8b5_grande.jpg\"}', 1, 45000, 45000),
(125, 61, 90, NULL, '{\"name\": \"Bắp ngô ngọt hữu cơ \", \"image\": \"assets/uploads/products/1769191604_tai_xuong_152d8a679915478187edbb2f6291a8b5_grande.jpg\"}', 1, 45000, 45000),
(126, 62, 88, NULL, '{\"name\": \"Mật ong pure origins leatherwood\", \"image\": \"assets/uploads/products/1769191521_mat_ong_pure_origins_leatherwood_250g.png\"}', 1, 1000000, 1000000),
(127, 63, 88, NULL, '{\"name\": \"Mật ong pure origins leatherwood\", \"image\": \"assets/uploads/products/1769191521_mat_ong_pure_origins_leatherwood_250g.png\"}', 1, 1000000, 1000000),
(128, 64, 88, NULL, '{\"name\": \"Mật ong pure origins leatherwood\", \"image\": \"assets/uploads/products/1769191521_mat_ong_pure_origins_leatherwood_250g.png\"}', 1, 1000000, 1000000),
(129, 65, 88, NULL, '{\"name\": \"Mật ong pure origins leatherwood\", \"image\": \"assets/uploads/products/1769191521_mat_ong_pure_origins_leatherwood_250g.png\"}', 1, 1000000, 1000000),
(130, 66, 90, NULL, '{\"name\": \"Bắp ngô ngọt hữu cơ \", \"image\": \"assets/uploads/products/1769191604_tai_xuong_152d8a679915478187edbb2f6291a8b5_grande.jpg\"}', 1, 45000, 45000),
(131, 67, 80, NULL, '{\"name\": \"Nui Rau Củ Hữu Cơ Cho Bé Hình Ô Tô\", \"image\": \"assets/uploads/products/1769191304_nui_rau_cu_huu_co_cho_be_hinh_o_to_300g_dalla_costa.jpeg\"}', 1, 250000, 250000),
(132, 67, 43, NULL, '{\"name\": \"Cà chua xay nhuyễn hữu cơ Passata\", \"image\": \"assets/uploads/products/1769191565_ca_chua_xay_nhuyen_huu_co_passata_hung_que_luce_680g_beb71dda50b34667b2955e1d555de6bb_grande.png\"}', 1, 400000, 400000),
(133, 67, 86, NULL, '{\"name\": \"Mật Ong Manuka 400+ Pure Origins\", \"image\": \"assets/uploads/products/1769191474_mat-ong.jpg\"}', 3, 500000, 1500000),
(134, 67, 44, NULL, '{\"name\": \"Cà chua xay nhuyễn hữu cơ Passata\", \"image\": \"assets/uploads/products/1769191565_ca_chua_xay_nhuyen_huu_co_passata_hung_que_luce_680g_beb71dda50b34667b2955e1d555de6bb_grande.png\"}', 2, 15000, 30000),
(135, 68, 86, NULL, '{\"name\": \"Mật Ong Manuka 400+ Pure Origins\", \"image\": \"assets/uploads/products/1769191474_mat-ong.jpg\"}', 1, 500000, 500000),
(136, 69, 44, NULL, '{\"name\": \"Cà chua xay nhuyễn hữu cơ Passata\", \"image\": \"assets/uploads/products/1769191565_ca_chua_xay_nhuyen_huu_co_passata_hung_que_luce_680g_beb71dda50b34667b2955e1d555de6bb_grande.png\"}', 1, 15000, 15000),
(137, 70, 88, NULL, '{\"name\": \"Mật ong pure origins leatherwood\", \"image\": \"assets/uploads/products/1769191521_mat_ong_pure_origins_leatherwood_250g.png\"}', 1, 1000000, 1000000),
(138, 71, 44, NULL, '{\"name\": \"Cà chua xay nhuyễn hữu cơ Passata\", \"image\": \"assets/uploads/products/1769191565_ca_chua_xay_nhuyen_huu_co_passata_hung_que_luce_680g_beb71dda50b34667b2955e1d555de6bb_grande.png\"}', 1, 15000, 15000),
(139, 72, 44, NULL, '{\"name\": \"Cà chua xay nhuyễn hữu cơ Passata\", \"image\": \"assets/uploads/products/1769191565_ca_chua_xay_nhuyen_huu_co_passata_hung_que_luce_680g_beb71dda50b34667b2955e1d555de6bb_grande.png\"}', 1, 15000, 15000),
(140, 73, 88, NULL, '{\"name\": \"Mật ong pure origins leatherwood\", \"image\": \"assets/uploads/products/1769191521_mat_ong_pure_origins_leatherwood_250g.png\"}', 1, 1000000, 1000000),
(141, 74, 86, NULL, '{\"name\": \"Mật Ong Manuka 400+ Pure Origins\", \"image\": \"assets/uploads/products/1769191474_mat-ong.jpg\"}', 1, 500000, 500000),
(142, 74, 88, NULL, '{\"name\": \"Mật ong pure origins leatherwood\", \"image\": \"assets/uploads/products/1769191521_mat_ong_pure_origins_leatherwood_250g.png\"}', 1, 1000000, 1000000),
(143, 75, 88, NULL, '{\"name\": \"Mật ong pure origins leatherwood\", \"image\": \"assets/uploads/products/1769191521_mat_ong_pure_origins_leatherwood_250g.png\"}', 1, 1000000, 1000000),
(144, 76, 44, NULL, '{\"name\": \"Cà chua xay nhuyễn hữu cơ Passata\", \"image\": \"assets/uploads/products/1769191565_ca_chua_xay_nhuyen_huu_co_passata_hung_que_luce_680g_beb71dda50b34667b2955e1d555de6bb_grande.png\"}', 1, 15000, 15000),
(145, 77, 86, NULL, '{\"name\": \"Mật Ong Manuka 400+ Pure Origins\", \"image\": \"assets/uploads/products/1769191474_mat-ong.jpg\"}', 1, 500000, 500000),
(146, 78, 88, NULL, '{\"name\": \"Mật ong pure origins leatherwood\", \"image\": \"assets/uploads/products/1769191521_mat_ong_pure_origins_leatherwood_250g.png\"}', 1, 1000000, 1000000),
(147, 79, 88, NULL, '{\"name\": \"Mật ong pure origins leatherwood\", \"image\": \"assets/uploads/products/1769191521_mat_ong_pure_origins_leatherwood_250g.png\"}', 1, 1000000, 1000000),
(148, 80, 88, NULL, '{\"name\": \"Mật ong pure origins leatherwood\", \"image\": \"assets/uploads/products/1769191521_mat_ong_pure_origins_leatherwood_250g.png\"}', 1, 1000000, 1000000),
(149, 81, 86, NULL, '{\"name\": \"Mật Ong Manuka 400+ Pure Origins\", \"image\": \"assets/uploads/products/1769191474_mat-ong.jpg\"}', 1, 500000, 500000),
(150, 81, 88, NULL, '{\"name\": \"Mật ong pure origins leatherwood\", \"image\": \"assets/uploads/products/1769191521_mat_ong_pure_origins_leatherwood_250g.png\"}', 1, 1000000, 1000000),
(151, 81, 44, NULL, '{\"name\": \"Cà chua xay nhuyễn hữu cơ Passata\", \"image\": \"assets/uploads/products/1769191565_ca_chua_xay_nhuyen_huu_co_passata_hung_que_luce_680g_beb71dda50b34667b2955e1d555de6bb_grande.png\"}', 1, 15000, 15000),
(152, 82, 44, NULL, '{\"name\": \"Cà chua xay nhuyễn hữu cơ Passata\", \"image\": \"assets/uploads/products/1769191565_ca_chua_xay_nhuyen_huu_co_passata_hung_que_luce_680g_beb71dda50b34667b2955e1d555de6bb_grande.png\"}', 1, 15000, 15000),
(153, 83, 86, NULL, '{\"name\": \"Mật Ong Manuka 400+ Pure Origins\", \"image\": \"assets/uploads/products/1769191474_mat-ong.jpg\"}', 1, 500000, 500000),
(154, 83, 88, NULL, '{\"name\": \"Mật ong pure origins leatherwood\", \"image\": \"assets/uploads/products/1769191521_mat_ong_pure_origins_leatherwood_250g.png\"}', 1, 1000000, 1000000),
(155, 83, 44, NULL, '{\"name\": \"Cà chua xay nhuyễn hữu cơ Passata\", \"image\": \"assets/uploads/products/1769191565_ca_chua_xay_nhuyen_huu_co_passata_hung_que_luce_680g_beb71dda50b34667b2955e1d555de6bb_grande.png\"}', 1, 15000, 15000),
(156, 84, 86, NULL, '{\"name\": \"Mật Ong Manuka 400+ Pure Origins\", \"image\": \"assets/uploads/products/1769191474_mat-ong.jpg\"}', 1, 500000, 500000),
(157, 84, 88, NULL, '{\"name\": \"Mật ong pure origins leatherwood\", \"image\": \"assets/uploads/products/1769191521_mat_ong_pure_origins_leatherwood_250g.png\"}', 1, 1000000, 1000000),
(158, 84, 44, NULL, '{\"name\": \"Cà chua xay nhuyễn hữu cơ Passata\", \"image\": \"assets/uploads/products/1769191565_ca_chua_xay_nhuyen_huu_co_passata_hung_que_luce_680g_beb71dda50b34667b2955e1d555de6bb_grande.png\"}', 1, 15000, 15000),
(159, 85, 68, NULL, '{\"name\": \"Bông Atiso Tươi Hữu\", \"image\": \"assets/uploads/products/1769191020_dia-chi-mua-bong-atiso.png\"}', 1, 40000, 40000),
(160, 85, 66, NULL, '{\"name\": \"Cải ngồng hữu cơ\", \"image\": \"assets/uploads/products/1769190953_c_i_ng_ng.png\"}', 1, 25000, 25000),
(161, 85, 64, NULL, '{\"name\": \"Cải xanh hữu cơ Sunny Harvest\", \"image\": \"assets/uploads/products/1769190920_c_i_b__xanh.png\"}', 1, 22000, 22000),
(162, 85, 62, NULL, '{\"name\": \"Cải thìa hữu cơ Sunny Harvest\", \"image\": \"assets/uploads/products/1769190872_c_i_th_a_.png\"}', 1, 20000, 20000),
(163, 85, 70, NULL, '{\"name\": \"Bông cải xanh baby\", \"image\": \"assets/uploads/products/1769191061_bong_cai_xanh_baby_huu_co.jpeg\"}', 1, 60000, 60000),
(164, 85, 72, NULL, '{\"name\": \"Rau ngót ta hữu cơ\", \"image\": \"assets/uploads/products/1769191099_rau_ngot_ta_huu_co.jpeg\"}', 1, 50000, 50000),
(165, 85, 74, NULL, '{\"name\": \"Mì ăn liền hữu cơ có trứng\", \"image\": \"assets/uploads/products/1769191168_mi-an-lien.jpeg\"}', 1, 100000, 100000),
(166, 85, 76, NULL, '{\"name\": \"Mì ăn liền hữu cơ không trứng\", \"image\": \"assets/uploads/products/1769191208_mi-an-lien-2.jpeg\"}', 1, 80000, 80000),
(167, 85, 86, NULL, '{\"name\": \"Mật Ong Manuka 400+ Pure Origins\", \"image\": \"assets/uploads/products/1769191474_mat-ong.jpg\"}', 1, 500000, 500000),
(168, 85, 88, NULL, '{\"name\": \"Mật ong pure origins leatherwood\", \"image\": \"assets/uploads/products/1769191521_mat_ong_pure_origins_leatherwood_250g.png\"}', 1, 1000000, 1000000),
(169, 85, 90, NULL, '{\"name\": \"Bắp ngô ngọt hữu cơ \", \"image\": \"assets/uploads/products/1769191604_tai_xuong_152d8a679915478187edbb2f6291a8b5_grande.jpg\"}', 1, 45000, 45000),
(170, 85, 84, NULL, '{\"name\": \"Mì spaghetti rau củ quả hữu cơ\", \"image\": \"assets/uploads/products/1769191403_mi_spaghetti_rau_cu_qua_huu_co_.jpg\"}', 1, 500000, 500000),
(171, 85, 78, NULL, '{\"name\": \"Nui nơ pastina hữu cơ\", \"image\": \"assets/uploads/products/1769191252_nui-no-pastina-huu-co.png\"}', 1, 20000, 20000),
(172, 85, 80, NULL, '{\"name\": \"Nui Rau Củ Hữu Cơ Cho Bé Hình Ô Tô\", \"image\": \"assets/uploads/products/1769191304_nui_rau_cu_huu_co_cho_be_hinh_o_to_300g_dalla_costa.jpeg\"}', 1, 250000, 250000),
(173, 85, 82, NULL, '{\"name\": \"Dầu oliu ép lạnh hữu cơ\", \"image\": \"assets/uploads/products/1769191359_dau-oliu-huu-co-ep-lanh-bioitalia-.jpg\"}', 1, 300000, 300000),
(174, 85, 44, NULL, '{\"name\": \"Cà chua xay nhuyễn hữu cơ Passata\", \"image\": \"assets/uploads/products/1769191565_ca_chua_xay_nhuyen_huu_co_passata_hung_que_luce_680g_beb71dda50b34667b2955e1d555de6bb_grande.png\"}', 2, 15000, 30000),
(175, 86, 44, NULL, '{\"name\": \"Cà chua xay nhuyễn hữu cơ Passata\", \"image\": \"assets/uploads/products/1769191565_ca_chua_xay_nhuyen_huu_co_passata_hung_que_luce_680g_beb71dda50b34667b2955e1d555de6bb_grande.png\"}', 1, 15000, 15000),
(176, 87, 88, NULL, '{\"name\": \"Mật ong pure origins leatherwood\", \"image\": \"assets/uploads/products/1769191521_mat_ong_pure_origins_leatherwood_250g.png\"}', 1, 1000000, 1000000),
(177, 88, 86, NULL, '{\"name\": \"Mật Ong Manuka 400+ Pure Origins\", \"image\": \"assets/uploads/products/1769191474_mat-ong.jpg\"}', 1, 500000, 500000),
(178, 89, 82, NULL, '{\"name\": \"Dầu oliu ép lạnh hữu cơ\", \"image\": \"assets/uploads/products/1769191359_dau-oliu-huu-co-ep-lanh-bioitalia-.jpg\"}', 1, 300000, 300000),
(179, 90, 78, NULL, '{\"name\": \"Nui nơ pastina hữu cơ\", \"image\": \"assets/uploads/products/1769191252_nui-no-pastina-huu-co.png\"}', 1, 20000, 20000),
(180, 91, 78, NULL, '{\"name\": \"Nui nơ pastina hữu cơ\", \"image\": \"assets/uploads/products/1769191252_nui-no-pastina-huu-co.png\"}', 1, 20000, 20000),
(181, 92, 82, NULL, '{\"name\": \"Dầu oliu ép lạnh hữu cơ\", \"image\": \"assets/uploads/products/1769191359_dau-oliu-huu-co-ep-lanh-bioitalia-.jpg\"}', 1, 300000, 300000),
(182, 93, 78, NULL, '{\"name\": \"Nui nơ pastina hữu cơ\", \"image\": \"assets/uploads/products/1769191252_nui-no-pastina-huu-co.png\"}', 1, 20000, 20000),
(183, 94, 72, NULL, '{\"name\": \"Rau ngót ta hữu cơ\", \"image\": \"assets/uploads/products/1769191099_rau_ngot_ta_huu_co.jpeg\"}', 1, 50000, 50000),
(184, 95, 88, NULL, '{\"name\": \"Mật ong pure origins leatherwood\", \"image\": \"assets/uploads/products/1769191521_mat_ong_pure_origins_leatherwood_250g.png\"}', 1, 1000000, 1000000),
(185, 96, 88, NULL, '{\"name\": \"Mật ong pure origins leatherwood\", \"image\": \"assets/uploads/products/1769191521_mat_ong_pure_origins_leatherwood_250g.png\"}', 1, 1000000, 1000000);

-- --------------------------------------------------------

--
-- Table structure for table `order_status_history`
--

CREATE TABLE `order_status_history` (
  `id` bigint UNSIGNED NOT NULL,
  `order_id` bigint UNSIGNED DEFAULT NULL,
  `status` varchar(50) DEFAULT NULL,
  `changed_by` bigint UNSIGNED DEFAULT NULL,
  `note` text,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `order_status_history`
--

INSERT INTO `order_status_history` (`id`, `order_id`, `status`, `changed_by`, `note`, `created_at`) VALUES
(29, 24, 'pending', 13, 'Đơn hàng mới được tạo', '2026-01-24 09:19:29'),
(30, 24, 'completed', 4, '', '2026-01-24 09:19:45'),
(31, 25, 'pending', 13, 'Đơn hàng mới được tạo', '2026-01-24 10:21:24'),
(32, 26, 'pending', 13, 'Đơn hàng mới được tạo', '2026-01-24 10:22:39'),
(33, 27, 'pending', 12, 'Đơn hàng mới được tạo', '2026-01-27 04:35:47'),
(34, 28, 'pending', 12, 'Đơn hàng mới được tạo', '2026-01-27 04:39:45'),
(35, 29, 'pending', 12, 'Đơn hàng mới được tạo', '2026-01-27 04:40:18'),
(36, 29, 'processing', 4, '', '2026-01-27 04:40:37'),
(37, 29, 'shipping', 4, '', '2026-01-27 04:40:58'),
(38, 29, 'completed', 4, '', '2026-01-27 04:41:07'),
(39, 30, 'pending', 12, 'Đơn hàng mới được tạo', '2026-01-27 04:41:51'),
(40, 31, 'pending', 14, 'Đơn hàng mới được tạo', '2026-01-27 07:33:39'),
(41, 32, 'pending', 14, 'Đơn hàng mới được tạo', '2026-01-27 07:37:53'),
(42, 32, 'completed', 4, '', '2026-01-27 07:38:17'),
(43, 33, 'pending', 14, 'Đơn hàng mới được tạo', '2026-02-05 08:26:09'),
(44, 33, 'shipping', 4, 'ok', '2026-02-05 08:26:55'),
(45, 33, 'shipping', 4, '', '2026-02-05 08:26:56'),
(46, 33, 'shipping', 4, '', '2026-02-05 08:26:57'),
(47, 33, 'shipping', 4, '', '2026-02-05 08:26:58'),
(48, 34, 'pending', 14, 'Đơn hàng mới được tạo', '2026-03-03 02:02:49'),
(49, 34, 'completed', 4, '', '2026-03-03 02:03:58'),
(50, 35, 'pending', 14, 'Đơn hàng mới được tạo', '2026-03-03 02:05:21'),
(51, 36, 'pending', 14, 'Đơn hàng mới được tạo', '2026-03-03 02:07:13'),
(52, 37, 'pending', 14, 'Đơn hàng mới được tạo', '2026-03-03 02:22:39'),
(53, 38, 'pending', 14, 'Đơn hàng mới được tạo', '2026-03-03 02:23:53'),
(54, 39, 'pending', 14, 'Đơn hàng mới được tạo', '2026-03-03 02:25:36'),
(55, 40, 'pending', 24, 'Đơn hàng mới được tạo', '2026-03-03 04:06:17'),
(56, 40, 'cancelled', 4, '', '2026-03-03 04:38:07'),
(57, 41, 'pending', 27, 'Đơn hàng mới được tạo', '2026-03-03 06:27:37'),
(58, 42, 'pending', 27, 'Đơn hàng mới được tạo', '2026-03-03 06:58:07'),
(59, 43, 'pending', 27, 'Đơn hàng mới được tạo', '2026-03-03 07:34:30'),
(60, 43, 'processing', 4, 'Admin đã xác nhận nhận được tiền chuyển khoản VietQR.', '2026-03-03 07:34:44'),
(61, 44, 'pending', 27, 'Đơn hàng mới được tạo', '2026-03-03 07:35:30'),
(62, 43, 'processing', 4, 'Admin đã xác nhận nhận được tiền chuyển khoản VietQR.', '2026-03-03 07:35:43'),
(63, 43, 'processing', 4, 'Admin đã xác nhận nhận được tiền chuyển khoản VietQR.', '2026-03-03 07:35:50'),
(64, 44, 'processing', 4, 'Admin đã xác nhận nhận được tiền chuyển khoản VietQR.', '2026-03-03 07:36:05'),
(65, 45, 'pending', 27, 'Đơn hàng mới được tạo', '2026-03-03 07:36:19'),
(66, 46, 'pending', 27, 'Đơn hàng mới được tạo', '2026-03-03 07:36:35'),
(67, 46, 'processing', 4, 'Admin đã xác nhận nhận được tiền chuyển khoản VietQR.', '2026-03-03 07:36:40'),
(68, 47, 'pending', 27, 'Đơn hàng mới được tạo', '2026-03-03 07:41:56'),
(69, 47, 'processing', 4, 'Admin đã xác nhận nhận được tiền chuyển khoản VietQR.', '2026-03-03 07:42:40'),
(70, 48, 'pending', 27, 'Đơn hàng mới được tạo', '2026-03-03 08:52:45'),
(71, 49, 'pending', 27, 'Đơn hàng mới được tạo', '2026-03-03 09:03:30'),
(72, 56, 'pending', 27, 'Đơn hàng mới được tạo', '2026-03-03 09:47:12'),
(73, 57, 'pending', 27, 'Đơn hàng mới được tạo', '2026-03-03 09:54:25'),
(74, 58, 'pending', 27, 'Đơn hàng mới được tạo', '2026-03-03 09:55:00'),
(75, 59, 'pending', 27, 'Đơn hàng mới được tạo', '2026-03-03 09:59:23'),
(76, 60, 'pending', 27, 'Đơn hàng mới được tạo', '2026-03-04 03:01:31'),
(77, 60, 'processing', 4, 'Admin đã xác nhận nhận được tiền chuyển khoản VietQR.', '2026-03-04 03:01:39'),
(78, 52, 'processing', 4, 'Admin đã xác nhận nhận được tiền chuyển khoản VietQR.', '2026-03-04 03:52:40'),
(79, 52, 'processing', 4, 'Admin đã xác nhận nhận được tiền chuyển khoản VietQR.', '2026-03-04 03:52:44'),
(80, 61, 'pending', 27, 'Đơn hàng mới được tạo', '2026-03-04 12:12:50'),
(81, 62, 'pending', 27, 'Đơn hàng mới được tạo', '2026-03-04 12:13:39'),
(82, 63, 'pending', 27, 'Đơn hàng mới được tạo', '2026-03-04 12:13:57'),
(83, 63, 'processing', 4, 'Admin đã xác nhận nhận được tiền chuyển khoản VietQR.', '2026-03-04 12:14:07'),
(84, 64, 'pending', 27, 'Đơn hàng mới được tạo', '2026-03-04 12:17:02'),
(85, 64, 'processing', 4, 'Admin đã xác nhận nhận được tiền chuyển khoản VietQR.', '2026-03-04 12:17:09'),
(86, 65, 'pending', 27, 'Đơn hàng mới được tạo', '2026-03-04 12:17:37'),
(87, 66, 'pending', 27, 'Đơn hàng mới được tạo', '2026-03-04 12:22:45'),
(88, 67, 'pending', 27, 'Đơn hàng mới được tạo', '2026-03-04 13:33:15'),
(89, 68, 'pending', 27, 'Đơn hàng mới được tạo', '2026-03-04 13:33:47'),
(90, 69, 'pending', 27, 'Đơn hàng mới được tạo', '2026-03-04 13:35:04'),
(91, 70, 'pending', 27, 'Đơn hàng mới được tạo', '2026-03-04 13:36:49'),
(92, 71, 'pending', 27, 'Đơn hàng mới được tạo', '2026-03-04 13:37:14'),
(93, 71, 'processing', 4, 'Admin đã xác nhận nhận được tiền chuyển khoản VietQR.', '2026-03-04 13:37:47'),
(94, 72, 'pending', 27, 'Đơn hàng mới được tạo', '2026-03-04 13:43:51'),
(95, 73, 'pending', 27, 'Đơn hàng mới được tạo', '2026-03-04 13:44:02'),
(96, 73, 'processing', 4, 'Admin đã xác nhận nhận được tiền chuyển khoản VietQR.', '2026-03-04 13:44:09'),
(97, 73, 'shipping', 4, '', '2026-03-04 13:44:57'),
(98, 72, 'completed', 4, '', '2026-03-04 13:45:05'),
(99, 71, 'cancelled', 4, '', '2026-03-04 13:45:15'),
(100, 74, 'pending', 27, 'Đơn hàng mới được tạo', '2026-03-04 13:51:58'),
(101, 75, 'pending', 27, 'Đơn hàng mới được tạo', '2026-03-04 13:52:16'),
(102, 75, 'processing', 4, 'Admin đã xác nhận nhận được tiền chuyển khoản VietQR.', '2026-03-04 13:52:21'),
(103, 75, 'shipping', 4, '', '2026-03-04 13:53:01'),
(104, 74, 'completed', 4, '', '2026-03-04 13:53:05'),
(105, 76, 'pending', 27, 'Đơn hàng mới được tạo', '2026-03-04 13:54:02'),
(106, 77, 'pending', 27, 'Đơn hàng mới được tạo', '2026-03-04 13:54:23'),
(107, 78, 'pending', 27, 'Đơn hàng mới được tạo', '2026-03-04 13:55:05'),
(108, 79, 'pending', 27, 'Đơn hàng mới được tạo', '2026-03-04 13:55:22'),
(109, 80, 'pending', 27, 'Đơn hàng mới được tạo', '2026-03-04 13:56:48'),
(110, 81, 'pending', 27, 'Đơn hàng mới được tạo', '2026-03-05 06:23:00'),
(111, 81, 'processing', 4, 'Admin đã xác nhận nhận được tiền chuyển khoản VietQR.', '2026-03-05 06:23:06'),
(112, 82, 'pending', 27, 'Đơn hàng mới được tạo', '2026-03-05 06:40:22'),
(113, 83, 'pending', 37, 'Đơn hàng mới được tạo', '2026-03-05 09:05:38'),
(114, 84, 'pending', 37, 'Đơn hàng mới được tạo', '2026-03-05 09:06:12'),
(115, 85, 'pending', 37, 'Đơn hàng mới được tạo', '2026-03-05 13:25:56'),
(116, 85, 'processing', 4, 'Admin đã xác nhận nhận được tiền chuyển khoản VietQR.', '2026-03-05 13:26:02'),
(117, 86, 'pending', 37, 'Đơn hàng mới được tạo', '2026-03-05 13:27:00'),
(118, 87, 'pending', 37, 'Đơn hàng mới được tạo', '2026-03-05 13:27:12'),
(119, 88, 'pending', 37, 'Đơn hàng mới được tạo', '2026-03-05 13:27:23'),
(120, 89, 'pending', 37, 'Đơn hàng mới được tạo', '2026-03-05 13:27:35'),
(121, 90, 'pending', 37, 'Đơn hàng mới được tạo', '2026-03-05 13:27:45'),
(122, 91, 'pending', 37, 'Đơn hàng mới được tạo', '2026-03-05 13:27:56'),
(123, 92, 'pending', 37, 'Đơn hàng mới được tạo', '2026-03-05 13:28:07'),
(124, 93, 'pending', 37, 'Đơn hàng mới được tạo', '2026-03-05 13:28:19'),
(125, 94, 'pending', 37, 'Đơn hàng mới được tạo', '2026-03-05 14:05:41'),
(126, 94, 'processing', 4, 'Admin đã xác nhận nhận được tiền chuyển khoản VietQR.', '2026-03-05 14:05:47'),
(127, 95, 'pending', 37, 'Đơn hàng mới được tạo', '2026-03-05 14:10:47'),
(128, 96, 'pending', 37, 'Đơn hàng mới được tạo', '2026-03-05 14:11:24');

-- --------------------------------------------------------

--
-- Table structure for table `payments`
--

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

-- --------------------------------------------------------

--
-- Table structure for table `permissions`
--

CREATE TABLE `permissions` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) DEFAULT NULL,
  `description` text
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

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
-- Dumping data for table `products`
--

INSERT INTO `products` (`id`, `sku`, `name`, `brand`, `slug`, `description`, `short_description`, `category_id`, `is_active`, `is_featured`, `meta`, `created_at`, `updated_at`) VALUES
(13, 'P1769190075', 'Ba chỉ bò Obe hữu cơ', 'Obe', 'ba-ch-b-obe-h-u-c--1769190075', 'Nếu cuối tuần muốn đổi gió hoặc bạn là một người sành ăn thì không nên bỏ qua thịt bò hữu cơ OBE nhé ', 'Nếu cuối tuần muốn đổi gió hoặc bạn là một người sành ăn thì không nên bỏ qua thịt bò hữu cơ OBE nhé ', 14, 1, 0, NULL, '2026-01-23 17:41:15', '2026-03-02 10:23:39'),
(14, 'P1769190179', 'Thịt bò xay obe organic', 'Obe', 'th-t-b-xay-obe-organic-1769190179', 'Nếu cuối tuần muốn đổi gió hoặc bạn là một người sành ăn thì không nên bỏ qua thịt bò xay hữu cơ OBE nhé: \r\n- 100% Bò OBE không sử dụng thuốc kháng sinh, hóc môn tăng trưởng. \r\n- Giống bò chất lượng ngon nhất, không biến đổi gene, không sử dụng các chất kích thích. - Bò ăn mềm, ngọt, thơm, ngậy béo....ĐẬM ĐÀ một cách tự nhiên. \r\n- Nhập khẩu chính thức, có giấy tờ, chứng nhận ORGANIC MỸ, ÚC. - 100% Bò được vận chuyển theo đường AIR (Máy bay) Món ăn: Burger bò, Thịt bò viên,....', 'Nếu cuối tuần muốn đổi gió hoặc bạn là một người sành ăn thì không nên bỏ qua thịt bò xay hữu cơ OBE nhé: \r\n- 100% Bò OBE không sử dụng thuốc kháng si', 14, 1, 0, NULL, '2026-01-23 17:42:59', '2026-03-02 10:24:18'),
(15, 'P1769190246', 'Thăn nội bò phile hữu cơ obe', 'Obe', 'th-n-n-i-b-phile-h-u-c-obe-1769190246', 'Nếu cuối tuần muốn đổi gió hoặc bạn là một người sành ăn thì không nên bỏ qua thịt bò hữu cơ OBE nhé ', 'Nếu cuối tuần muốn đổi gió hoặc bạn là một người sành ăn thì không nên bỏ qua thịt bò hữu cơ OBE nhé ', 14, 1, 0, NULL, '2026-01-23 17:44:06', '2026-03-02 10:25:40'),
(16, 'P1769190544', 'Thăn lưng bò obe hữu cơ', 'Obe', 'th-n-l-ng-b-obe-h-u-c--1769190544', 'Thăn Lưng Bò Obe Hữu Cơ hay còn gọi là Thăn nạc vai, tiếng Anh gọi là Beef Ribeyes, đây là phần thịt nhằm trên sườn của Bò, sở dĩ nó gọi là Eyes vì nó có 2 mắt mỡ xen giữa cây thăn. Thịt thăn lưng có đặc điểm rất chắc vì là phần bù cơ giúp cho bò vận động phần lưng, mỡ xen giữa phần thịt thăn làm cho nó vô cùng thích hợp với món Steak huyền thoại, chưa bao giờ làm món Steak lại đậm đà, dễ dàng đến vậy với thăn lưng. Nếu cuối tuần muốn đổi gió hoặc bạn là một người sành ăn thì không nên bỏ qua thịt bò hữu cơ OBE nhé ', 'Thăn Lưng Bò Obe Hữu Cơ hay còn gọi là Thăn nạc vai, tiếng Anh gọi là Beef Ribeyes, đây là phần thịt nhằm trên sườn của Bò, sở dĩ nó gọi là Eyes vì nó', 14, 1, 0, NULL, '2026-01-23 17:49:04', '2026-03-02 10:26:18'),
(17, 'P1769190628', 'Xương ống heo hữu cơ', 'FAU', 'x-ng-ng-heo-h-u-c--1769190628', 'Xương ống heo là phần xương của bắp chân con heo, thành phần chủ yếu là tủy sống, chứa nhiều canxi, vitamin A và chất béo. ', 'Xương ống heo là phần xương của bắp chân con heo, thành phần chủ yếu là tủy sống, chứa nhiều canxi, vitamin A và chất béo. ', 14, 1, 0, NULL, '2026-01-23 17:50:28', '2026-03-02 10:26:50'),
(18, 'P1769190687', 'Xương lưng heo hữu cơ', 'FAU', 'x-ng-l-ng-heo-h-u-c--1769190687', 'Xương lưng heo hữu cơ là đoạn xương dài, mỏng được kết đối xứng với các đoạn đốt xương sống của con heo. Đây là phần xương khi nấu chín nước rất ngọt, thích hợp để chế biến các món canh xương heo hầm, chế nước dùng... món ăn có vị đậm đà hơn.', 'Xương lưng heo hữu cơ là đoạn xương dài, mỏng được kết đối xứng với các đoạn đốt xương sống của con heo. Đây là phần xương khi nấu chín nước rất ngọt,', 14, 1, 0, NULL, '2026-01-23 17:51:27', '2026-03-02 10:27:23'),
(19, 'P1769190740', 'Đuôi heo hữu cơ ', 'FAU', '-u-i-heo-h-u-c--1769190740', 'Đuôi heo có chứa nhiều chất dinh dưỡng có ích như: protein 26,4%, lipid 22,7%, glucid 4%, nhiều chất khoáng vi lượng như can-xi, photpho, sắt... Chất protein của đuôi động vật (chủ yếu là ở da) gồm nhiều chất hợp thành như: collagen, elastin, keratin, albumin, globulin... ', 'Đuôi heo có chứa nhiều chất dinh dưỡng có ích như: protein 26,4%, lipid 22,7%, glucid 4%, nhiều chất khoáng vi lượng như can-xi, photpho, sắt... Chất ', 14, 1, 0, NULL, '2026-01-23 17:52:20', '2026-03-02 10:27:55'),
(20, 'P1769190781', 'Thăn đầu rồng hữu cơ', 'FAU', 'th-n-u-r-ng-h-u-c--1769190781', 'Thăn đầu rồng heo hữu cơ là phần thịt nối giữa thịt mông và thịt thăn. Thịt đầu rồng rất mềm, không quá mỡ và không quá khô, do có cả lớp da và nạc đi kèm.', 'Thăn đầu rồng heo hữu cơ là phần thịt nối giữa thịt mông và thịt thăn. Thịt đầu rồng rất mềm, không quá mỡ và không quá khô, do có cả lớp da và nạc đi', 14, 1, 0, NULL, '2026-01-23 17:53:01', '2026-03-02 10:28:25'),
(21, 'P1769190872', 'Cải thìa hữu cơ Sunny Harvest', 'Sunny Harvest ', 'c-i-th-a-h-u-c-sunny-harvest-1769190872', 'Cải thìa hữu cơ Sunny Harvest 250g là loại rau xanh organic đóng gói tiện lợi, giàu vitamin (A, C, B), khoáng chất (canxi, sắt) và chất xơ, có vị hơi đắng đặc trưng giúp kích thích tiêu hóa, làm mát cơ thể, tốt cho tim mạch, da và mắt; thường được dùng để xào, luộc, nấu canh, thích hợp cho chế độ ăn lành mạnh. ', 'Cải thìa hữu cơ Sunny Harvest 250g là loại rau xanh organic đóng gói tiện lợi, giàu vitamin (A, C, B), khoáng chất (canxi, sắt) và chất xơ, có vị hơi ', 12, 1, 0, NULL, '2026-01-23 17:54:32', '2026-03-02 10:22:50'),
(22, 'P1769190920', 'Cải xanh hữu cơ Sunny Harvest', 'Sunny Harvest ', 'c-i-xanh-h-u-c-sunny-harvest-1769190920', 'Cải xanh hữu cơ Sunny Harvest 250g là loại rau xanh organic đóng gói tiện lợi, giàu vitamin (A, C, B), khoáng chất (canxi, sắt) và chất xơ, có vị hơi đắng đặc trưng giúp kích thích tiêu hóa, làm mát cơ thể, tốt cho tim mạch, da và mắt; thường được dùng để xào, luộc, nấu canh, thích hợp cho chế độ ăn lành mạnh. ', 'Cải xanh hữu cơ Sunny Harvest 250g là loại rau xanh organic đóng gói tiện lợi, giàu vitamin (A, C, B), khoáng chất (canxi, sắt) và chất xơ, có vị hơi ', 12, 1, 0, NULL, '2026-01-23 17:55:20', '2026-03-02 10:22:41'),
(23, 'P1769190953', 'Cải ngồng hữu cơ', 'Sunny Harvest ', 'c-i-ng-ng-h-u-c--1769190953', 'Cải ngồng hữu cơ Sunny Harvest 250g là loại rau xanh organic đóng gói tiện lợi, giàu vitamin (A, C, B), khoáng chất (canxi, sắt) và chất xơ, có vị hơi đắng đặc trưng giúp kích thích tiêu hóa, làm mát cơ thể, tốt cho tim mạch, da và mắt; thường được dùng để xào, luộc, nấu canh, thích hợp cho chế độ ăn lành mạnh. ', 'Cải ngồng hữu cơ Sunny Harvest 250g là loại rau xanh organic đóng gói tiện lợi, giàu vitamin (A, C, B), khoáng chất (canxi, sắt) và chất xơ, có vị hơi', 12, 1, 0, NULL, '2026-01-23 17:55:53', '2026-03-02 10:22:31'),
(24, 'P1769191020', 'Bông Atiso Tươi Hữu', 'Natural', 'b-ng-atiso-t-i-h-u-1769191020', 'Atiso có nhiều công dụng y học và gần như không có tác dụng phụ. Nó thường được dùng để kích thích sự tiết dịch của gan.', 'Atiso có nhiều công dụng y học và gần như không có tác dụng phụ. Nó thường được dùng để kích thích sự tiết dịch của gan.', 12, 1, 0, NULL, '2026-01-23 17:57:00', '2026-03-02 10:22:22'),
(25, 'P1769191061', 'Bông cải xanh baby', 'Natural', 'b-ng-c-i-xanh-baby-1769191061', 'Bông cải xanh hoặc súp lơ xanh, là một loại cây thuộc họ cải, có hoa lớn ở đầu, thường được dùng như rau. Bông cải xanh thường được chế biến bằng cách luộc hoặc hấp, nhưng cũng có thể được ăn sống như là rau sống trong những đĩa đồ nguội khai vị. ', 'Bông cải xanh hoặc súp lơ xanh, là một loại cây thuộc họ cải, có hoa lớn ở đầu, thường được dùng như rau. Bông cải xanh thường được chế biến bằng cách', 12, 1, 0, NULL, '2026-01-23 17:57:41', '2026-03-02 10:22:12'),
(26, 'P1769191099', 'Rau ngót ta hữu cơ', 'Natural', 'rau-ng-t-ta-h-u-c--1769191099', 'Rau ngót tính mát lạnh (nấu chín sẽ bớt lạnh), vị ngọt. Có công năng thanh nhiệt, giải độc, lợi tiểu, tăng tiết nước bọt, hoạt huyết hoá ứ, bổ huyết, cầm huyết, nhuận tràng, sát khuẩn, tiêu viêm, sinh cơ, có nhiều tác dụng chữa bệnh.', 'Rau ngót tính mát lạnh (nấu chín sẽ bớt lạnh), vị ngọt. Có công năng thanh nhiệt, giải độc, lợi tiểu, tăng tiết nước bọt, hoạt huyết hoá ứ, bổ huyết, ', 12, 1, 0, NULL, '2026-01-23 17:58:19', '2026-03-02 10:22:00'),
(27, 'P1769191168', 'Mì ăn liền hữu cơ có trứng', 'Alb Gold', 'm-n-li-n-h-u-c-c-tr-ng-1769191168', 'Mì ăn liền hữu cơ Alb Gold được sản xuất theo công nghệ Đức, nguyên liệu tự nhiên hữu cơ, không chứa hương liệu hay phụ phẩm tổng hợp hóa học, quy trình sản xuất sấy chín từ hơi nước, bảo toàn chất dinh dưỡng.  ', 'Mì ăn liền hữu cơ Alb Gold được sản xuất theo công nghệ Đức, nguyên liệu tự nhiên hữu cơ, không chứa hương liệu hay phụ phẩm tổng hợp hóa học, quy trì', 16, 1, 0, NULL, '2026-01-23 17:59:28', '2026-03-02 10:32:37'),
(28, 'P1769191208', 'Mì ăn liền hữu cơ không trứng', 'Alb Gold', 'm-n-li-n-h-u-c-kh-ng-tr-ng-1769191208', 'Mì ăn liền hữu cơ Alb Gold được sản xuất theo công nghệ Đức, nguyên liệu tự nhiên hữu cơ, không chứa hương liệu hay phụ phẩm tổng hợp hóa học, quy trình sản xuất sấy chín từ hơi nước, bảo toàn chất dinh dưỡng.', 'Mì ăn liền hữu cơ Alb Gold được sản xuất theo công nghệ Đức, nguyên liệu tự nhiên hữu cơ, không chứa hương liệu hay phụ phẩm tổng hợp hóa học, quy trì', 16, 1, 0, NULL, '2026-01-23 18:00:08', '2026-03-02 10:33:13'),
(29, 'P1769191252', 'Nui nơ pastina hữu cơ', 'Dalla Costa', 'nui-n-pastina-h-u-c--1769191252', 'Nui Nơ Pastina Hữu Cơ Cho Bé 400g Dalla Costa Organic Disney Pastina Bio ', 'Nui Nơ Pastina Hữu Cơ Cho Bé 400g Dalla Costa Organic Disney Pastina Bio ', 16, 1, 0, NULL, '2026-01-23 18:00:52', '2026-03-02 10:33:51'),
(30, 'P1769191304', 'Nui Rau Củ Hữu Cơ Cho Bé Hình Ô Tô', 'Dalla Costa', 'nui-rau-c-h-u-c-cho-b-h-nh-t--1769191304', 'Nui Rau Củ Hữu Cơ Cho Bé Hình Ô Tô 300g Dalla Costa Organic Pasta Bio Disney Pixar Cars', 'Nui Rau Củ Hữu Cơ Cho Bé Hình Ô Tô 300g Dalla Costa Organic Pasta Bio Disney Pixar Cars', 16, 1, 0, NULL, '2026-01-23 18:01:44', '2026-03-02 10:34:25'),
(31, 'P1769191359', 'Dầu oliu ép lạnh hữu cơ', 'Bioitalia   ', 'd-u-oliu-p-l-nh-h-u-c--1769191359', 'Uống rất ngon', 'Uống rất ngon', 16, 1, 0, NULL, '2026-01-23 18:02:39', '2026-03-02 10:35:00'),
(32, 'P1769191403', 'Mì spaghetti rau củ quả hữu cơ', 'Bioitalia', 'm-spaghetti-rau-c-qu-h-u-c--1769191403', 'Mì Spaghetti Rau Củ Quả Hữu Cơ BioItalia (500g) thương hiệu Bioitalia với thành phần 100% Organic nhập khẩu từ Ý an toàn khi chế biến món ăn cho gia đình bạn.', 'Mì Spaghetti Rau Củ Quả Hữu Cơ BioItalia (500g) thương hiệu Bioitalia với thành phần 100% Organic nhập khẩu từ Ý an toàn khi chế biến món ăn cho gia đ', 16, 1, 0, NULL, '2026-01-23 18:03:23', '2026-03-02 10:35:39'),
(33, 'P1769191474', 'Mật Ong Manuka 400+ Pure Origins', 'Pure Origins ', 'm-t-ong-manuka-400-pure-origins-1769191474', 'Manuka là mật ong đắt đỏ nhất trên thế giới vì độ khan hiếm cũng như tác dụng tuyệt vời của nó.', 'Manuka là mật ong đắt đỏ nhất trên thế giới vì độ khan hiếm cũng như tác dụng tuyệt vời của nó.', 18, 1, 0, NULL, '2026-01-23 18:04:34', '2026-03-02 10:36:24'),
(34, 'P1769191521', 'Mật ong pure origins leatherwood', 'Pure Origins', 'm-t-ong-pure-origins-leatherwood-1769191521', 'Là loại mật ong đặc biệt được chiết xuất từ mật hoa của cây Leatherwood Chỉ số chống ôxy hóa cao hơn các loại mật ong\r\nThành phần: 100% mật ong hữu cơ Úc nguyên chất.', 'Là loại mật ong đặc biệt được chiết xuất từ mật hoa của cây Leatherwood Chỉ số chống ôxy hóa cao hơn các loại mật ong\r\nThành phần: 100% mật ong hữu ', 18, 1, 0, NULL, '2026-01-23 18:05:21', '2026-03-02 10:51:30'),
(35, 'P1769191565', 'Cà chua xay nhuyễn hữu cơ Passata', 'Luce   ', 'c-chua-xay-nhuy-n-h-u-c-passata-1769191565', 'Thành phần:  Cà chua * (99,7%), húng quế * (0,3%).  \r\n\r\n* Sản phẩm từ canh tác hữu cơ.\r\n\r\nXuất xứ: Ý', 'Thành phần:  Cà chua * (99,7%), húng quế * (0,3%).  \r\n\r\n* Sản phẩm từ canh tác hữu cơ.\r\n\r\nXuất xứ: Ý', 18, 1, 0, NULL, '2026-01-23 18:06:05', '2026-03-02 09:15:39'),
(36, 'P1769191604', 'Bắp ngô ngọt hữu cơ ', 'Luce', 'b-p-ng-ng-t-h-u-c--1769191604', 'Ngô ngọt là một loại ngũ cốc lâu đời xuất hiện ở Mexico hơn 9000 năm trước. Giá trị dinh dưỡng của nó cao giải thích sự phổ biến của nó trên khắp thế giới, đây cũng là lý do khiến nó trở thành một trong những cây trồng thông dụng trên toàn thế giới.\r\n', 'Ngô ngọt là một loại ngũ cốc lâu đời xuất hiện ở Mexico hơn 9000 năm trước. Giá trị dinh dưỡng của nó cao giải thích sự phổ biến của nó trên khắp thế ', 19, 1, 0, NULL, '2026-01-23 18:06:44', '2026-03-02 10:52:53'),
(43, 'P1772762814', 'Bắp cải tím hữu cơ', 'OMG', 'b-p-c-i-t-m-h-u-c--1772762814', 'Bắp cải tím: tên khoa học là Brassica oleracea var capitata ruba là cây bắp cải có màu tím. Xuất xứ từ Địa Trung Hải, hiện nay được trồng rộng rãi khắp thế giới, thích hợp với khí hậu ôn đới và tại Việt Nam bắp cải tím được trồng nhiều ở Đà Lạt. \r\n• Sở dĩ bắp cải tím có màu như vậy là vì nó có hàm lượng cao polyphenol anthocyanin, chất này có tính kháng viêm, bảo vệ tế bào khỏi những tổn hại của tia cực tím và có thể giúp giảm nguy cơ mắc một số bệnh ung thư.', 'Bắp cải tím: tên khoa học là Brassica oleracea var capitata ruba là cây bắp cải có màu tím. Xuất xứ từ Địa Trung Hải, hiện nay được trồng rộng rãi khắ', 13, 1, 0, NULL, '2026-03-06 02:06:54', '2026-03-06 02:06:54'),
(44, 'P1772762886', 'Bắp cải trái tim hữu cơ', 'OMG', 'b-p-c-i-tr-i-tim-h-u-c--1772762886', 'Bắp cải, Cải bắp - Brassica oleracea L. var. capitata L., là một loại rau chủ lực trong họ Cải - Brassicaceae. Người Pháp gọi nó là Su (Chon) nên từ đó còn có tên là Sú. • Bắp cải là loài rau ôn đới gốc ở Địa Trung Hải được nhập vào trồng ở nước ta. Bắp Cải có vị ngọt, tính mát, ngoài là món ăn ngon ra còn có tác dụng chữa được nhiều bệnh. Bắp Cải đã được sử dụng làm thuốc ở châu Âu từ thời Thượng cổ, người ta đã gọi nó là \"Thầy thuốc của người nghèo\". CÔNG DỤNG • Trong bắp cải có chứa Vitamin A, C, U, canxi, kali, phốt pho, các bon hydrat, protein, calo và một số khoáng chất cần thiết cho sức khỏe. Đặc biệt, lượng vitamin C trong bắp cải chỉ thua Cà Chua, còn nhiều gấp 4,5 lần so với cà rốt, gấp 3,6 lần so với Khoai tây, Hành tây. 100g cải bắp cung cấp cho cơ thể 50 calo.', 'Bắp cải, Cải bắp - Brassica oleracea L. var. capitata L., là một loại rau chủ lực trong họ Cải - Brassicaceae. Người Pháp gọi nó là Su (Chon) nên từ đ', 13, 1, 0, NULL, '2026-03-06 02:08:06', '2026-03-06 02:08:06'),
(45, 'P1772762996', 'Bào ngư tươi nhập khẩu', 'TBD', 'b-o-ng-t-i-nh-p-kh-u-1772762996', 'ĐỈNH NÓC KỊCH TRẦN', 'ĐỈNH NÓC KỊCH TRẦN', 15, 1, 0, NULL, '2026-03-06 02:09:56', '2026-03-06 02:09:56'),
(46, 'P1772763090', 'Cá bống đục', 'TBD', 'c-b-ng-c-1772763090', 'CÁ BỐNG ĐỤC\r\nCá bống đục vừa ngon ngọt, ít xương, dù là cá nhỏ nhưng không có xương dăm, xương chử Y.\r\n\r\nThịt cá lành tính, không chứa histamin, không gây dị ứng cho bé, người bệnh hoặc người mẩn cảm với cá biển.\r\nCá đục hay còn gọi là cá bống vàng. Cá đục ngon phải là cá sống ven cửa sông vùng giáp nước ngọt mặn (Gọi là nước lợ). Vì nơi đó dồi dào thức ăn, ít kẻ thù, môi trường sống tối ưu. Nên cá vừa béo, thịt vừa ngọt lại tươi sống nhảy soi sói.', 'CÁ BỐNG ĐỤC\r\nCá bống đục vừa ngon ngọt, ít xương, dù là cá nhỏ nhưng không có xương dăm, xương chử Y.\r\n\r\nThịt cá lành tính, không chứa histamin, không', 15, 1, 0, NULL, '2026-03-06 02:11:30', '2026-03-06 02:11:30'),
(47, 'P1772763171', 'Bánh phồng tôm', 'KHO', 'b-nh-ph-ng-t-m-1772763171', 'Nguyên liệu chính của bánh vẫn là tôm rừng.\r\nTôm được xay nhuyễn với bột và trứng vịt. Sau đó nêm thêm gia vị gồm đường, tỏi, ớt và tiêu. Đặc biệt không sử dụng bột ngọt.\r\n\r\nSau đó đem bánh đi chán bằng nồi hấp trên bếp củi, rồi mang đi phơi nắng trên giàn phơi cho khô. Một cách thủ công thuyền thống của người dân xứ này.\r\n\r\nBánh này khi ăn mình sẽ chiên với dầu nóng, ăn không bị ngán và ngấy như ở ngoài. Bánh có tiêu và ớt nhưng không cay, bé nhà vẫn ăn được.', 'Nguyên liệu chính của bánh vẫn là tôm rừng.\r\nTôm được xay nhuyễn với bột và trứng vịt. Sau đó nêm thêm gia vị gồm đường, tỏi, ớt và tiêu. Đặc biệt khô', 17, 1, 0, NULL, '2026-03-06 02:12:51', '2026-03-06 02:12:51'),
(48, 'P1772763233', 'Bánh tráng gạo lứt hữu cơ hoa sữa', 'KHO', 'b-nh-tr-ng-g-o-l-t-h-u-c-hoa-s-a-1772763233', 'Sản phẩm bánh tráng gạo lứt hữu cơ Hoa Sữa Foods được sản xuất từ gạo lứt đạt chứng nhận hữu cơ USDA & EU, đảm bảo an toàn cho sức khỏe, là nguyên liệu cho nhiều món ăn, tiện lợi và dễ cuốn. Bánh tráng gạo lứt hoàn toàn không sử dụng hóa chất, chất bảo quản trong quá trình chế biến.\r\n\r\nĐược làm từ những hạt gạo chất lượng cao, đã được tuyển chọn cùng công nghệ hiện đại, nguyên liệu gạo làm nên sản phẩm đạt chuẩn các tiêu chí hữu cơ quốc tế với ưu điểm nội trội', 'Sản phẩm bánh tráng gạo lứt hữu cơ Hoa Sữa Foods được sản xuất từ gạo lứt đạt chứng nhận hữu cơ USDA & EU, đảm bảo an toàn cho sức khỏe, là nguyên liệ', 17, 1, 0, NULL, '2026-03-06 02:13:53', '2026-03-06 02:13:53'),
(49, 'P1772763325', 'Bột gạo tẻ hữu cơ', 'OMG', 'b-t-g-o-t-h-u-c--1772763325', 'Chứng nhận USDA\r\n\r\nChứng nhận EU ORGANIC BIO LOGO (EU) quy trình sản xuất hữu cơ và phương thức canh tác hữu cơ\r\n\r\nBột hữu cơ Lotus Floating với thành phần 100% các loại gạo được gieo trồng, thu hoạch và xay thành bột đều theo đúng tiêu chuẩn hữu cơ. Bột gạo tẻ Jasmine (gạo hương lài) được sử dụng để làm các bánh như: bánh tráng, bánh cuốn, bánh gạo, bánh cake, bánh xèo, banha canh, bánh giò,...Hay làm các loại bún, phở.... Bột gạo cũng có thể dùng nấu bột ăn dặm cho bé.', 'Chứng nhận USDA\r\n\r\nChứng nhận EU ORGANIC BIO LOGO (EU) quy trình sản xuất hữu cơ và phương thức canh tác hữu cơ\r\n\r\nBột hữu cơ Lotus Floating với thành', 19, 1, 0, NULL, '2026-03-06 02:15:25', '2026-03-06 02:15:25');

-- --------------------------------------------------------

--
-- Table structure for table `product_images`
--

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
-- Dumping data for table `product_images`
--

INSERT INTO `product_images` (`id`, `product_id`, `variant_id`, `image_url`, `alt_text`, `position`, `created_at`) VALUES
(2, 13, NULL, 'assets/uploads/products/1769190075_ba-chi-bo-obe.png', 'Ba chỉ bò Obe hữu cơ', 1, '2026-01-23 17:41:15'),
(3, 14, NULL, 'assets/uploads/products/1769190179_thit-bo-obe-huu-co-xay.jpg', 'Thịt bò xay obe organic', 1, '2026-01-23 17:42:59'),
(4, 15, NULL, 'assets/uploads/products/1769190246_than-noi-bo-uc-huu-co-obe.jpg', 'Thăn nội bò phile hữu cơ obe', 1, '2026-01-23 17:44:06'),
(5, 16, NULL, 'assets/uploads/products/1769190544_than-lung-bo-huu-co-obe.jpg', 'Thăn lưng bò obe hữu cơ', 1, '2026-01-23 17:49:04'),
(6, 17, NULL, 'assets/uploads/products/1769190628_xuong-ong-huu-co.jpg', 'Xương ống heo hữu cơ', 1, '2026-01-23 17:50:28'),
(7, 18, NULL, 'assets/uploads/products/1769190687_v_xuong_lung_heo_huu_co.jpg', 'Xương lưng heo hữu cơ', 1, '2026-01-23 17:51:27'),
(8, 19, NULL, 'assets/uploads/products/1769190740_duoi-heo-huu-co.jpg', 'Đuôi heo hữu cơ ', 1, '2026-01-23 17:52:20'),
(9, 20, NULL, 'assets/uploads/products/1769190781_than_dau_rong_huu_co.jpg', 'Thăn đầu rồng hữu cơ', 1, '2026-01-23 17:53:01'),
(10, 21, NULL, 'assets/uploads/products/1769190872_c_i_th_a_.png', 'Cải thìa hữu cơ Sunny Harvest', 1, '2026-01-23 17:54:32'),
(11, 22, NULL, 'assets/uploads/products/1769190920_c_i_b__xanh.png', 'Cải xanh hữu cơ Sunny Harvest', 1, '2026-01-23 17:55:20'),
(12, 23, NULL, 'assets/uploads/products/1769190953_c_i_ng_ng.png', 'Cải ngồng hữu cơ', 1, '2026-01-23 17:55:53'),
(13, 24, NULL, 'assets/uploads/products/1769191020_dia-chi-mua-bong-atiso.png', 'Bông Atiso Tươi Hữu', 1, '2026-01-23 17:57:00'),
(14, 25, NULL, 'assets/uploads/products/1769191061_bong_cai_xanh_baby_huu_co.jpeg', 'Bông cải xanh baby', 1, '2026-01-23 17:57:41'),
(15, 26, NULL, 'assets/uploads/products/1769191099_rau_ngot_ta_huu_co.jpeg', 'Rau ngót ta hữu cơ', 1, '2026-01-23 17:58:19'),
(16, 27, NULL, 'assets/uploads/products/1769191168_mi-an-lien.jpeg', 'Mì ăn liền hữu cơ có trứng', 1, '2026-01-23 17:59:28'),
(17, 28, NULL, 'assets/uploads/products/1769191208_mi-an-lien-2.jpeg', 'Mì ăn liền hữu cơ không trứng', 1, '2026-01-23 18:00:08'),
(18, 29, NULL, 'assets/uploads/products/1769191252_nui-no-pastina-huu-co.png', 'Nui nơ pastina hữu cơ', 1, '2026-01-23 18:00:52'),
(19, 30, NULL, 'assets/uploads/products/1769191304_nui_rau_cu_huu_co_cho_be_hinh_o_to_300g_dalla_costa.jpeg', 'Nui Rau Củ Hữu Cơ Cho Bé Hình Ô Tô', 1, '2026-01-23 18:01:44'),
(20, 31, NULL, 'assets/uploads/products/1769191359_dau-oliu-huu-co-ep-lanh-bioitalia-.jpg', 'Dầu oliu ép lạnh hữu cơ', 1, '2026-01-23 18:02:39'),
(21, 32, NULL, 'assets/uploads/products/1769191403_mi_spaghetti_rau_cu_qua_huu_co_.jpg', 'Mì spaghetti rau củ quả hữu cơ', 1, '2026-01-23 18:03:23'),
(22, 33, NULL, 'assets/uploads/products/1769191474_mat-ong.jpg', 'Mật Ong Manuka 400+ Pure Origins', 1, '2026-01-23 18:04:34'),
(23, 34, NULL, 'assets/uploads/products/1769191521_mat_ong_pure_origins_leatherwood_250g.png', 'Mật ong pure origins leatherwood', 1, '2026-01-23 18:05:21'),
(24, 35, NULL, 'assets/uploads/products/1769191565_ca_chua_xay_nhuyen_huu_co_passata_hung_que_luce_680g_beb71dda50b34667b2955e1d555de6bb_grande.png', 'Cà chua xay nhuyễn hữu cơ Passata', 1, '2026-01-23 18:06:05'),
(25, 36, NULL, 'assets/uploads/products/1769191604_tai_xuong_152d8a679915478187edbb2f6291a8b5_grande.jpg', 'Bắp ngô ngọt hữu cơ ', 1, '2026-01-23 18:06:44'),
(28, 43, NULL, 'assets/uploads/products/1772762814_bap-cai.jpg', 'Bắp cải tím hữu cơ', 1, '2026-03-06 02:06:54'),
(29, 44, NULL, 'assets/uploads/products/1772762886_bap-cai-trang.jpg', 'Bắp cải trái tim hữu cơ', 1, '2026-03-06 02:08:06'),
(30, 45, NULL, 'assets/uploads/products/1772762996_bao-ngu.png', 'Bào ngư tươi nhập khẩu', 1, '2026-03-06 02:09:56'),
(31, 46, NULL, 'assets/uploads/products/1772763090_ca-bong.jpg', 'Cá bống đục', 1, '2026-03-06 02:11:30'),
(32, 47, NULL, 'assets/uploads/products/1772763171_banh-phong-tom.jpg', 'Bánh phồng tôm', 1, '2026-03-06 02:12:52'),
(33, 48, NULL, 'assets/uploads/products/1772763233_banh-trang.png', 'Bánh tráng gạo lứt hữu cơ hoa sữa', 1, '2026-03-06 02:13:53'),
(34, 49, NULL, 'assets/uploads/products/1772763325_bot-gao.jpeg', 'Bột gạo tẻ hữu cơ', 1, '2026-03-06 02:15:25');

-- --------------------------------------------------------

--
-- Table structure for table `product_variants`
--

CREATE TABLE `product_variants` (
  `id` bigint UNSIGNED NOT NULL,
  `product_id` bigint UNSIGNED NOT NULL,
  `sku` varchar(100) DEFAULT NULL,
  `name` varchar(255) DEFAULT NULL,
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
-- Dumping data for table `product_variants`
--

INSERT INTO `product_variants` (`id`, `product_id`, `sku`, `name`, `price_cents`, `compare_at_price_cents`, `stock`, `weight_grams`, `attributes`, `is_active`, `created_at`, `updated_at`) VALUES
(21, 21, 'V1769190872', '50g', 20000, NULL, 0, NULL, NULL, 0, '2026-01-23 17:54:32', '2026-03-03 02:02:20'),
(33, 33, 'V1769191474', '250g', 1500000, NULL, 98, NULL, NULL, 0, '2026-01-23 18:04:34', '2026-03-03 02:07:13'),
(34, 34, 'V1769191521', '250g', 2000000, NULL, 98, NULL, NULL, 0, '2026-01-23 18:05:21', '2026-03-03 02:07:13'),
(35, 35, 'V1769191565', '250g', 250000, NULL, 98, NULL, NULL, 0, '2026-01-23 18:06:05', '2026-03-03 02:07:13'),
(36, 36, 'V1769191604', '250g', 90000, NULL, 99, NULL, NULL, 0, '2026-01-23 18:06:44', '2026-03-03 02:07:13'),
(43, 35, 'P1770285626-N2', '250g', 400000, NULL, 98, NULL, NULL, 1, '2026-02-05 10:00:26', '2026-03-04 13:33:15'),
(44, 35, 'P1772440369-N3', '50g', 15000, NULL, 76, NULL, NULL, 1, '2026-03-02 08:32:49', '2026-03-05 13:27:00'),
(45, 14, 'P1772447058-N2', '250g', 300000, NULL, 100, NULL, NULL, 1, '2026-03-02 10:24:18', '2026-03-02 10:24:18'),
(46, 14, 'P1772447079-N2', '50g', 150000, NULL, 100, NULL, NULL, 1, '2026-03-02 10:24:39', '2026-03-02 10:24:39'),
(47, 13, 'P1772447105-N2', '250g', 225000, NULL, 100, NULL, NULL, 1, '2026-03-02 10:25:05', '2026-03-02 10:25:05'),
(48, 13, 'P1772447105-N3', '50g', 125000, NULL, 100, NULL, NULL, 1, '2026-03-02 10:25:05', '2026-03-02 10:25:05'),
(49, 15, 'P1772447140-N2', '250g', 495000, NULL, 100, NULL, NULL, 1, '2026-03-02 10:25:40', '2026-03-02 10:25:40'),
(50, 15, 'P1772447140-N3', '50g', 295000, NULL, 100, NULL, NULL, 1, '2026-03-02 10:25:40', '2026-03-02 10:25:40'),
(51, 16, 'P1772447178-N2', '250g', 180000, NULL, 100, NULL, NULL, 1, '2026-03-02 10:26:18', '2026-03-02 10:26:18'),
(52, 16, 'P1772447178-N3', '50g', 80000, NULL, 100, NULL, NULL, 1, '2026-03-02 10:26:18', '2026-03-02 10:26:18'),
(53, 17, 'P1772447210-N2', '250g', 140000, NULL, 100, NULL, NULL, 1, '2026-03-02 10:26:50', '2026-03-02 10:26:50'),
(54, 17, 'P1772447210-N3', '50g', 40000, NULL, 100, NULL, NULL, 1, '2026-03-02 10:26:50', '2026-03-02 10:26:50'),
(55, 18, 'P1772447243-N2', '250g', 155000, NULL, 100, NULL, NULL, 1, '2026-03-02 10:27:23', '2026-03-02 10:27:23'),
(56, 18, 'P1772447243-N3', '50g', 55000, NULL, 100, NULL, NULL, 1, '2026-03-02 10:27:23', '2026-03-02 10:27:23'),
(57, 19, 'P1772447275-N2', '250g', 120000, NULL, 100, NULL, NULL, 1, '2026-03-02 10:27:55', '2026-03-02 10:27:55'),
(58, 19, 'P1772447275-N3', '50g', 20000, NULL, 100, NULL, NULL, 1, '2026-03-02 10:27:55', '2026-03-02 10:27:55'),
(59, 20, 'P1772447305-N2', '250g', 190000, NULL, 100, NULL, NULL, 1, '2026-03-02 10:28:25', '2026-03-02 10:28:25'),
(60, 20, 'P1772447305-N3', '50g', 90000, NULL, 100, NULL, NULL, 1, '2026-03-02 10:28:25', '2026-03-02 10:28:25'),
(61, 21, 'P1772447342-N2', '250g', 40000, NULL, 99, NULL, NULL, 1, '2026-03-02 10:29:02', '2026-03-03 02:23:53'),
(62, 21, 'P1772447342-N3', '50g', 20000, NULL, 96, NULL, NULL, 1, '2026-03-02 10:29:02', '2026-03-05 13:25:56'),
(63, 22, 'P1772447374-N2', '250g', 44000, NULL, 100, NULL, NULL, 1, '2026-03-02 10:29:34', '2026-03-02 10:29:34'),
(64, 22, 'P1772447374-N3', '50g', 22000, NULL, 99, NULL, NULL, 1, '2026-03-02 10:29:34', '2026-03-05 13:25:56'),
(65, 23, 'P1772447406-N2', '250g', 50000, NULL, 100, NULL, NULL, 1, '2026-03-02 10:30:06', '2026-03-02 10:30:06'),
(66, 23, 'P1772447406-N3', '50g', 25000, NULL, 99, NULL, NULL, 1, '2026-03-02 10:30:06', '2026-03-05 13:25:56'),
(67, 24, 'P1772447436-N2', '250g', 80000, NULL, 100, NULL, NULL, 1, '2026-03-02 10:30:36', '2026-03-02 10:30:36'),
(68, 24, 'P1772447436-N3', '50g', 40000, NULL, 99, NULL, NULL, 1, '2026-03-02 10:30:36', '2026-03-05 13:25:56'),
(69, 25, 'P1772447477-N2', '250g', 120000, NULL, 100, NULL, NULL, 1, '2026-03-02 10:31:17', '2026-03-02 10:31:17'),
(70, 25, 'P1772447477-N3', '50g', 60000, NULL, 99, NULL, NULL, 1, '2026-03-02 10:31:17', '2026-03-05 13:25:56'),
(71, 26, 'P1772447514-N2', '250g', 100000, NULL, 100, NULL, NULL, 1, '2026-03-02 10:31:54', '2026-03-02 10:31:54'),
(72, 26, 'P1772447514-N3', '50g', 50000, NULL, 98, NULL, NULL, 1, '2026-03-02 10:31:54', '2026-03-05 14:05:41'),
(73, 27, 'P1772447557-N2', '250g', 200000, NULL, 100, NULL, NULL, 1, '2026-03-02 10:32:37', '2026-03-02 10:32:37'),
(74, 27, 'P1772447557-N3', '50g', 100000, NULL, 99, NULL, NULL, 1, '2026-03-02 10:32:37', '2026-03-05 13:25:56'),
(75, 28, 'P1772447593-N2', '250g', 160000, NULL, 100, NULL, NULL, 1, '2026-03-02 10:33:13', '2026-03-02 10:33:13'),
(76, 28, 'P1772447593-N3', '50g', 80000, NULL, 99, NULL, NULL, 1, '2026-03-02 10:33:13', '2026-03-05 13:25:56'),
(77, 29, 'P1772447631-N2', '250g', 400000, NULL, 99, NULL, NULL, 1, '2026-03-02 10:33:51', '2026-03-03 02:05:21'),
(78, 29, 'P1772447631-N3', '50g', 20000, NULL, 95, NULL, NULL, 1, '2026-03-02 10:33:51', '2026-03-05 13:28:19'),
(79, 30, 'P1772447665-N2', '250g', 500000, NULL, 99, NULL, NULL, 1, '2026-03-02 10:34:25', '2026-03-03 02:05:21'),
(80, 30, 'P1772447665-N3', '50g', 250000, NULL, 97, NULL, NULL, 1, '2026-03-02 10:34:25', '2026-03-05 13:25:56'),
(81, 31, 'P1772447700-N2', '250g', 900000, NULL, 100, NULL, NULL, 1, '2026-03-02 10:35:00', '2026-03-02 10:35:00'),
(82, 31, 'P1772447700-N3', '50g', 300000, NULL, 96, NULL, NULL, 1, '2026-03-02 10:35:00', '2026-03-05 13:28:07'),
(83, 32, 'P1772447739-N2', '250g', 1000000, NULL, 100, NULL, NULL, 1, '2026-03-02 10:35:39', '2026-03-02 10:35:39'),
(84, 32, 'P1772447739-N3', '50g', 500000, NULL, 98, NULL, NULL, 1, '2026-03-02 10:35:39', '2026-03-05 13:25:56'),
(85, 33, 'P1772447784-N2', '250g', 1500000, NULL, 99, NULL, NULL, 1, '2026-03-02 10:36:24', '2026-03-03 02:25:36'),
(86, 33, 'P1772447784-N3', '50g', 500000, NULL, 77, NULL, NULL, 1, '2026-03-02 10:36:24', '2026-03-05 13:27:23'),
(87, 34, 'P1772448690-N2', '250g', 2000000, NULL, 99, NULL, NULL, 1, '2026-03-02 10:51:30', '2026-03-03 02:25:36'),
(88, 34, 'P1772448712-N3', '50g', 1000000, NULL, 62, NULL, NULL, 1, '2026-03-02 10:51:52', '2026-03-05 14:11:24'),
(89, 36, 'P1772448773-N2', '250g', 90000, NULL, 0, NULL, NULL, 1, '2026-03-02 10:52:53', '2026-03-03 02:25:36'),
(90, 36, 'P1772448773-N3', '50g', 45000, NULL, 85, NULL, NULL, 1, '2026-03-02 10:52:53', '2026-03-05 13:25:56'),
(92, 43, 'P1772762814-1', '250g', 100000, NULL, 100, NULL, NULL, 1, '2026-03-06 02:06:54', '2026-03-06 02:06:54'),
(93, 43, 'P1772762814-2', '50g', 50000, NULL, 100, NULL, NULL, 1, '2026-03-06 02:06:54', '2026-03-06 02:06:54'),
(94, 44, 'P1772762886-1', '250g', 150000, NULL, 100, NULL, NULL, 1, '2026-03-06 02:08:06', '2026-03-06 02:08:06'),
(95, 44, 'P1772762886-2', '50g', 50000, NULL, 100, NULL, NULL, 1, '2026-03-06 02:08:06', '2026-03-06 02:08:06'),
(96, 45, 'P1772762996-1', '250g', 500000, NULL, 100, NULL, NULL, 1, '2026-03-06 02:09:56', '2026-03-06 02:09:56'),
(97, 45, 'P1772762996-2', '50g', 250000, NULL, 100, NULL, NULL, 1, '2026-03-06 02:09:56', '2026-03-06 02:09:56'),
(98, 46, 'P1772763090-1', '250g', 100000, NULL, 100, NULL, NULL, 1, '2026-03-06 02:11:30', '2026-03-06 02:11:30'),
(99, 46, 'P1772763090-2', '50g', 50000, NULL, 100, NULL, NULL, 1, '2026-03-06 02:11:30', '2026-03-06 02:11:30'),
(100, 47, 'P1772763171-1', '250g', 150000, NULL, 100, NULL, NULL, 1, '2026-03-06 02:12:51', '2026-03-06 02:12:51'),
(101, 47, 'P1772763171-2', '50g', 50000, NULL, 100, NULL, NULL, 1, '2026-03-06 02:12:51', '2026-03-06 02:12:51'),
(102, 48, 'P1772763233-1', '250g', 100000, NULL, 0, NULL, NULL, 1, '2026-03-06 02:13:53', '2026-03-06 02:13:53'),
(103, 48, 'P1772763233-2', '50g', 100000, NULL, 0, NULL, NULL, 1, '2026-03-06 02:13:53', '2026-03-06 02:13:53'),
(104, 49, 'P1772763325-1', '250g', 50000, NULL, 0, NULL, NULL, 1, '2026-03-06 02:15:25', '2026-03-06 02:15:25'),
(105, 49, 'P1772763325-2', '50g', 25000, NULL, 0, NULL, NULL, 1, '2026-03-06 02:15:25', '2026-03-06 02:15:25');

-- --------------------------------------------------------

--
-- Table structure for table `reviews`
--

CREATE TABLE `reviews` (
  `id` bigint UNSIGNED NOT NULL,
  `user_id` bigint UNSIGNED DEFAULT NULL,
  `product_id` bigint UNSIGNED DEFAULT NULL,
  `order_id` bigint UNSIGNED DEFAULT NULL,
  `variant_id` bigint UNSIGNED DEFAULT NULL,
  `rating` smallint DEFAULT NULL,
  `title` varchar(255) DEFAULT NULL,
  `comment` text,
  `is_approved` tinyint DEFAULT '0',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `reviews`
--

INSERT INTO `reviews` (`id`, `user_id`, `product_id`, `order_id`, `variant_id`, `rating`, `title`, `comment`, `is_approved`, `created_at`) VALUES
(1, 27, 35, 72, NULL, 5, NULL, 'Được của nó', 1, '2026-03-05 06:43:50'),
(3, 29, 34, NULL, NULL, 4, NULL, 'I WANT THIS', 1, '2026-03-05 07:39:03'),
(4, 30, 35, NULL, NULL, 5, NULL, 'Nice !', 1, '2026-03-05 07:41:57'),
(5, 31, 32, NULL, NULL, 5, NULL, 'Hmmm...', 1, '2026-03-05 07:42:15'),
(6, 32, 35, NULL, NULL, 5, NULL, 'Quá đỉnh', 1, '2026-03-05 07:42:33'),
(7, 33, 33, NULL, NULL, 5, NULL, 'OMG!!!', 1, '2026-03-05 07:42:50'),
(8, 29, 49, NULL, NULL, 1, NULL, 'Lỏ', 1, '2026-03-06 03:04:13'),
(9, 30, 48, NULL, NULL, 2, NULL, 'Tạm', 1, '2026-03-06 03:04:28'),
(10, 31, 47, NULL, NULL, 5, NULL, 'ok', 1, '2026-03-06 03:04:37'),
(11, 31, 46, NULL, NULL, 3, NULL, 'uwu', 1, '2026-03-06 03:04:55'),
(12, 29, 45, NULL, NULL, 5, NULL, 'Được', 1, '2026-03-06 03:05:13'),
(13, 33, 44, NULL, NULL, 4, NULL, 'Ok', 1, '2026-03-06 03:05:32'),
(14, 30, 43, NULL, NULL, 2, NULL, 'ad', 1, '2026-03-06 03:05:52'),
(15, 32, 36, NULL, NULL, 5, NULL, 'dc', 1, '2026-03-06 03:06:06'),
(16, 33, 35, NULL, NULL, 5, NULL, '12', 1, '2026-03-06 03:06:16'),
(17, 33, 34, NULL, NULL, 5, NULL, '123', 1, '2026-03-06 03:06:24'),
(18, 31, 34, NULL, NULL, 5, NULL, '123', 1, '2026-03-06 03:06:35'),
(19, 33, 33, NULL, NULL, 5, NULL, '123', 1, '2026-03-06 03:06:45'),
(20, 33, 32, NULL, NULL, 5, NULL, '123', 1, '2026-03-06 03:06:54'),
(21, 33, 31, NULL, NULL, 5, NULL, '132', 1, '2026-03-06 03:07:07'),
(22, 31, 29, NULL, NULL, 2, NULL, '123', 1, '2026-03-06 03:07:20'),
(23, 32, 18, NULL, NULL, 5, NULL, '123', 1, '2026-03-06 03:07:28'),
(24, 30, 21, NULL, NULL, 5, NULL, '123', 1, '2026-03-06 03:07:37');

-- --------------------------------------------------------

--
-- Table structure for table `roles`
--

CREATE TABLE `roles` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(100) DEFAULT NULL,
  `description` text,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `roles`
--

INSERT INTO `roles` (`id`, `name`, `description`, `created_at`) VALUES
(1, 'Admin', 'Sếp Tổng', '2026-01-18 13:53:44'),
(2, 'Manager', 'Quản trị viên', '2026-01-22 04:36:24'),
(3, 'Staff', 'Nhân viên', '2026-01-23 15:05:40'),
(4, 'Customer', 'Khách hàng mua sắm', '2026-01-23 15:05:40'),
(5, 'Seeding', 'Tài khoản ảo', '2026-03-05 07:37:32');

-- --------------------------------------------------------

--
-- Table structure for table `role_permissions`
--

CREATE TABLE `role_permissions` (
  `role_id` bigint UNSIGNED NOT NULL,
  `permission_id` bigint UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `shipping_addresses`
--

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
-- Dumping data for table `shipping_addresses`
--

INSERT INTO `shipping_addresses` (`id`, `user_id`, `full_name`, `phone`, `address_line`, `city`, `province`, `postal_code`, `country`, `is_default`, `created_at`) VALUES
(1, 6, 'demo', '0987654321', 'demo', 'Hcm', '', '', 'Vietnam', 0, '2026-01-22 07:44:20'),
(2, 6, 'demo', '0987654321', 'demo', 'Hn', '', '', 'Vietnam', 1, '2026-01-22 07:45:33'),
(3, 7, NULL, NULL, 'demo2', 'Hcm', '', '', 'Vietnam', 1, '2026-01-22 07:53:06'),
(4, 8, 'demo3', '0987789789', 'demo', 'Hcm', '', '', 'Vietnam', 0, '2026-01-22 08:22:28'),
(5, 8, 'demo3', '0987789789', 'demo12', 'Hn', '', '', 'Vietnam', 0, '2026-01-22 08:23:13'),
(7, 8, 'demo3', '0987789789', 'Hello', 'Vũng Tàu', '', '', 'Vietnam', 0, '2026-01-23 12:37:07'),
(8, 8, 'demo3', '0987789789', 'Xin Chào', 'Bà Rịa', '', '', 'Vietnam', 0, '2026-01-23 12:37:49'),
(9, 8, 'demo3', '0987789789', 'Hello', 'Nha Trang', '', '', 'Vietnam', 0, '2026-01-23 12:37:57'),
(10, 8, 'demo3', '0987789789', 'Hế hề', 'Phú Quốc', '', '', 'Vietnam', 0, '2026-01-23 12:38:07'),
(11, 8, 'demo3', '0987789789', 'Khu Phố 1', 'Tây Ninh', '', '', 'Vietnam', 0, '2026-01-23 12:41:48'),
(12, 8, 'demo3', '0987789789', 'Khu Phố 1', 'Tây Ninh', '', '', 'Vietnam', 0, '2026-01-23 12:42:04'),
(13, 8, 'demo3', '0987789789', 'Khu Phố 1', 'Tây Ninh', '', '', 'Vietnam', 0, '2026-01-23 12:42:05'),
(14, 8, 'demo3', '0987789789', 'Quy Nhơn', 'Gia Lai', '', '', 'Vietnam', 0, '2026-01-23 13:14:45'),
(15, 8, 'demo3', '0987789789', 'Quy Nhơn', 'Gia Lai', '', '', 'Vietnam', 0, '2026-01-23 13:15:41'),
(16, 8, 'demo3', '0987789789', '78/2', 'HCM', '', '', 'Vietnam', 1, '2026-01-23 13:18:10'),
(17, 9, 'Bảo Long', '0978857457', 'Lê Cao Lãng', 'HCM', '', '', 'Vietnam', 0, '2026-01-23 13:23:40'),
(19, 9, 'Bảo Long', '0978857457', 'Thành phố', 'Hcm', '', '', 'Vietnam', 1, '2026-01-23 13:25:30'),
(20, 12, 'demo1', '0988886789', '78/2', 'TPHCM', '', '', 'Vietnam', 1, '2026-01-24 06:14:32'),
(21, 13, 'Nguyễn Văn A', '0999888777', '78/2', 'Hcm', '', '', 'Vietnam', 1, '2026-01-24 09:19:23'),
(22, 14, 'demo10', '0987576567', 'hcm', '78/2', '', '', 'Vietnam', 0, '2026-01-27 07:31:52'),
(23, 14, 'demo10', '0987576567', '78/10', 'tây ninh', '', '', 'Vietnam', 1, '2026-01-27 07:32:15'),
(24, 24, 'Anh LOng Test', '1231233211', 'demo', '123', '', '', 'Vietnam', 1, '2026-03-03 04:06:13'),
(25, 27, 'Long Le', '', '123123', '123', '', '', 'Vietnam', 1, '2026-03-03 06:27:31'),
(26, 37, 'Long Le', '0980980981', '1231231', '312', '', '', 'Vietnam', 0, '2026-03-05 09:05:32'),
(27, 37, 'Long Le', '0980980981', '123', 'Hn', '', '', 'Vietnam', 1, '2026-03-05 09:10:17'),
(28, 37, 'Long Le', '0980980981', '111', '123', '', '', 'Vietnam', 0, '2026-03-05 13:28:45'),
(29, 37, 'Long Le', '0980980981', '11', '111', '', '', 'Vietnam', 0, '2026-03-05 13:28:49'),
(30, 37, 'Long Le', '0980980981', '111', '111', '', '', 'Vietnam', 0, '2026-03-05 13:28:52'),
(31, 37, 'Long Le', '0980980981', '111', '111', '', '', 'Vietnam', 0, '2026-03-05 13:28:56'),
(32, 37, 'Long Le', '0980980981', '11', '111', '', '', 'Vietnam', 0, '2026-03-05 13:29:00');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

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
  `verification_token` varchar(255) DEFAULT NULL COMMENT 'Mã gửi qua mail',
  `reset_token` varchar(255) DEFAULT NULL COMMENT 'Mã quên mật khẩu (OTP)',
  `reset_token_expire` datetime DEFAULT NULL COMMENT 'Hạn sử dụng mã OTP',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `last_login_at` datetime DEFAULT NULL,
  `metadata` json DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `email`, `password_hash`, `name`, `phone`, `avatar_url`, `status`, `role_id`, `google_id`, `email_verified`, `verification_token`, `reset_token`, `reset_token_expire`, `created_at`, `updated_at`, `last_login_at`, `metadata`) VALUES
(4, 'admin@ecostore.com', '$2y$10$SnwXxKqZ0Qy2Tz5Zv4V08OIMyZ5eSPMQFLAgRKbX0TB1mj1wYJxjW', 'Admin', '0987654321', 'assets/uploads/avatars/avatar_4_1770105949.jpg', 1, 1, NULL, 1, NULL, NULL, NULL, '2026-01-18 13:54:40', '2026-03-03 03:49:34', NULL, NULL),
(6, 'demo@gmail.com', '$2y$10$k/5vdbanH6CoRby745Kfue/AYSeVe1J09CytHHs3jYMa5EG.Q3vYK', 'demo', '0912345678', NULL, 1, 4, NULL, 1, NULL, NULL, NULL, '2026-01-22 04:36:44', '2026-03-03 03:49:34', NULL, NULL),
(7, 'demo2@gmail.com', '$2y$10$tqTvf6t1WB78DxA2IezMZOlcrbTNMuODMHnYGce6tCMfeiAzpFSNS', 'demo2', '0123456789', NULL, 1, 4, NULL, 1, NULL, NULL, NULL, '2026-01-22 14:49:57', '2026-03-03 03:49:34', NULL, NULL),
(8, 'demo3@gmail.com', '$2y$10$sP0WZi.BrJgzZKllnXC43uO9u8Ud7S7chEpAzjZ57yuk.U0aTh/OW', 'demo3', '0987789789', NULL, 1, 4, NULL, 1, NULL, NULL, NULL, '2026-01-22 15:03:18', '2026-03-03 03:49:34', NULL, NULL),
(9, 'baolong@gmail.com', '$2y$10$EYdcQdXZl3KHl6Jw/rlAwem9uq3it/on1ZZVppslTRoiF45iJi8XC', 'Brian Le', '0984948779', 'assests/uploads/avatarsavatar_9_1769177859.png', 1, 4, NULL, 1, NULL, NULL, NULL, '2026-01-23 13:21:15', '2026-03-03 03:49:34', NULL, NULL),
(10, 'manager@ecostore.com', '$2y$10$SnwXxKqZ0Qy2Tz5Zv4V08OIMyZ5eSPMQFLAgRKbX0TB1mj1wYJxjW', 'Trưởng Phòng', '0988888888', NULL, 1, 2, NULL, 1, NULL, NULL, NULL, '2026-01-23 15:09:51', '2026-03-03 03:49:34', NULL, NULL),
(11, 'staff@ecostore.com', '$2y$10$SnwXxKqZ0Qy2Tz5Zv4V08OIMyZ5eSPMQFLAgRKbX0TB1mj1wYJxjW', 'Nhân Viên', '0977777777', NULL, 1, 3, NULL, 1, NULL, NULL, NULL, '2026-01-23 15:09:51', '2026-03-03 03:49:34', NULL, NULL),
(12, 'demo1@gmail.com', '$2y$10$2WaklO0QQdm.Jfqe37o4perllRry335mP0jTVevGYhWeFHy5rcdw6', 'demo', '0988886789', NULL, 0, 2, NULL, 1, NULL, NULL, NULL, '2026-01-23 18:31:46', '2026-03-03 03:49:34', NULL, NULL),
(13, 'nguyenvana@gmail.com', '$2y$10$kJxtXz6jJvuMZLSY9lYov.T7wBHN4g65aJDW.6ATA4xHmPY8yxEFm', 'Nguyễn Văn A', '0999888777', NULL, 1, 4, NULL, 1, NULL, NULL, NULL, '2026-01-24 09:18:47', '2026-03-03 03:49:34', NULL, NULL),
(14, 'demo10@gmail.com', '$2y$10$EdPwL0QZ5zgTwnx.OHgz0eqiytd5u.gb9HzdPlRD7p9.qyvMeQ9T6', 'demo10', '0987576567', NULL, 1, 4, NULL, 1, NULL, NULL, NULL, '2026-01-27 07:27:45', '2026-03-03 03:49:34', NULL, NULL),
(16, 'hi@gmail.com', '$2y$10$mA3gHmN8jgkcgbiAct/Tt.2fxX5Iq.2qN61FjYx4TKQjGRHzcBLLO', 'demo1', '123456789', NULL, 1, 4, NULL, 1, NULL, NULL, NULL, '2026-02-03 09:32:43', '2026-03-03 03:49:34', NULL, NULL),
(22, 'Tqhuy10042001@gmail.com', '$2y$10$aZ8CpvebO9661jko3vZqG.x.EBU1o8dyBrPIbldnQeUnOQj5n4odS', 'Khách Hàng Huy', '1231232131', NULL, 1, 4, NULL, 1, NULL, NULL, NULL, '2026-03-03 03:43:14', '2026-03-03 03:43:57', NULL, NULL),
(23, 'Kaiogenzo1412@gmail.com', '$2y$10$k8na3MI8llKwZUo/yfEQ1um14lHqtXHX8v6PBb6zRbsomnidaXYMS', 'Khách Hàng Hoho', '3213211231', NULL, 1, 4, NULL, 1, NULL, NULL, NULL, '2026-03-03 03:45:06', '2026-03-03 03:45:56', NULL, NULL),
(24, 'imlongmanhme_old@gmail.com', '$2y$10$dIovGdMYLj/UjAts6s6/aenrZksq4OPw2POFj.6Qtl1vSofG9hiR6', 'Anh LOng Test', '1231233211', 'https://lh3.googleusercontent.com/a/ACg8ocKWE3axMSc26C9GWIoDL_GyVALqslc5QelmYzTMvKC2vDZ07w=s96-c', 1, 2, '107490554611304066542', 1, NULL, NULL, NULL, '2026-03-03 03:52:21', '2026-03-03 05:40:37', NULL, NULL),
(26, 'imlongmanhme_old2@gmail.com', '$2y$10$RQdCZ5ok0vgXodqcwiR5gOPrA37gVYWxSVn9HGf38ePiOMas2GGCK', 'demo1ty', '1231232132', NULL, 1, 4, NULL, 1, NULL, NULL, NULL, '2026-03-03 06:25:37', '2026-03-03 06:26:15', NULL, NULL),
(27, 'imlongmanhme_olde1@gmail.com', '$2y$10$D6jL11x.wyi76tXXa9scEOiN16RT7V.U9ELp6gnsHwhXuwYwTFsPa', 'Long Le', '', 'https://lh3.googleusercontent.com/a/ACg8ocKWE3axMSc26C9GWIoDL_GyVALqslc5QelmYzTMvKC2vDZ07w=s96-c', 1, 4, '107490554611304066542', 1, NULL, NULL, NULL, '2026-03-03 06:26:35', '2026-03-05 08:54:22', NULL, NULL),
(29, 'iwantdaumo@gmail.com', '$2y$10$WXPzSL9AKdGg9CgihKBqm.JXJgkAYoJ4Duj5FcZkEj1OqlkTKh4SO', 'Donal Trump', '1231231131', NULL, 1, 5, NULL, 0, NULL, NULL, NULL, '2026-03-05 07:38:04', '2026-03-05 07:38:04', NULL, NULL),
(30, 'elon@gmail.com', '$2y$10$LtBZ7DnmPnK3yPeSdgRE5unQtgbm67xDFQUcVvMBkvz1mfzqJsAym', 'Elon Musk', '1112221111', NULL, 1, 5, NULL, 0, NULL, NULL, NULL, '2026-03-05 07:39:53', '2026-03-05 07:39:53', NULL, NULL),
(31, 'bill@gmail.com', '$2y$10$z73Db9UOxZUgYwgqNKXZSuBAJihiZ9H2LakJ1xRdAQZGtGM6xblMS', 'Bill Gates', '1211211211', NULL, 1, 5, NULL, 0, NULL, NULL, NULL, '2026-03-05 07:40:25', '2026-03-05 07:40:25', NULL, NULL),
(32, 'thanh@gmail.com', '$2y$10$KVX3Y/e2aY6vyvAqWcXaFOKueOs86VaOxIVjNg3x5.1Tf/2M.c37S', 'Trấn Thành', '1011011011', NULL, 1, 5, NULL, 0, NULL, NULL, NULL, '2026-03-05 07:41:02', '2026-03-05 07:41:02', NULL, NULL),
(33, 'phat@gmail.com', '$2y$10$5wdtOhtRePyItI6V0CeRV.1/S01SEzr7fZxJk7zhWDQKIxnjVIhky', 'Châu Nhuận Phát', '1110001110', NULL, 1, 5, NULL, 0, NULL, NULL, NULL, '2026-03-05 07:41:40', '2026-03-05 07:41:40', NULL, NULL),
(34, 'imlongmanhmmme@gmail.com', '$2y$10$c/T.Z6ubPpj4MPeD7bWvHOT8eF1v7YTtR/6iQ0Id0dEmKCniNDYcK', 'donaltrump', '123321321123', NULL, 1, 4, NULL, 1, NULL, NULL, NULL, '2026-03-05 08:55:02', '2026-03-05 09:00:45', NULL, NULL),
(35, '123@gmail.com', '$2y$10$k/c1NUURnUTBvwM.7KxNJ.fTz.7sy07d5ne2QAUv1hGL4dlxbR7GK', '123', '2131231231', NULL, 1, 4, NULL, 0, '330819', NULL, NULL, '2026-03-05 09:00:08', '2026-03-05 09:00:08', NULL, NULL),
(36, 'ongmanhme@gmail.com', '$2y$10$WUz1dXTIDgAoEh9711f5deR2RLVDFlK83NL6PigoYx5rI9CHLt/K.', 'Bảo Long', '1212122121', NULL, 1, 4, NULL, 1, NULL, NULL, NULL, '2026-03-05 09:02:00', '2026-03-05 09:03:01', NULL, NULL),
(37, 'imlongmanhme@gmail.com', '$2y$10$.adbIu6i008KOs365Rw9re5tu9L5RMZFfc2Bzr.pUc.GPNeYhwgq6', 'Long Le', '0980980981', 'https://lh3.googleusercontent.com/a/ACg8ocKWE3axMSc26C9GWIoDL_GyVALqslc5QelmYzTMvKC2vDZ07w=s96-c', 1, 4, '107490554611304066542', 1, NULL, NULL, NULL, '2026-03-05 09:03:08', '2026-03-05 09:05:00', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `user_coupons`
--

CREATE TABLE `user_coupons` (
  `id` int NOT NULL,
  `user_id` int NOT NULL,
  `coupon_id` int NOT NULL,
  `is_used` tinyint(1) DEFAULT '0',
  `saved_at` datetime DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `user_coupons`
--

INSERT INTO `user_coupons` (`id`, `user_id`, `coupon_id`, `is_used`, `saved_at`) VALUES
(1, 27, 3, 0, '2026-03-03 15:35:24'),
(2, 27, 4, 0, '2026-03-03 16:02:53'),
(4, 37, 5, 0, '2026-03-05 16:07:10'),
(5, 37, 4, 0, '2026-03-05 20:29:12'),
(6, 37, 6, 0, '2026-03-05 20:29:14'),
(7, 37, 9, 0, '2026-03-05 20:29:16'),
(8, 37, 8, 0, '2026-03-05 20:29:17'),
(9, 37, 7, 0, '2026-03-05 20:29:19'),
(10, 37, 11, 0, '2026-03-05 20:29:21'),
(11, 37, 10, 0, '2026-03-05 20:29:23');

-- --------------------------------------------------------

--
-- Table structure for table `wishlists`
--

CREATE TABLE `wishlists` (
  `id` bigint UNSIGNED NOT NULL,
  `user_id` bigint UNSIGNED DEFAULT NULL,
  `variant_id` bigint UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `wishlists`
--

INSERT INTO `wishlists` (`id`, `user_id`, `variant_id`, `created_at`) VALUES
(36, 37, 35, '2026-03-05 13:25:04'),
(37, 37, 36, '2026-03-05 13:25:04'),
(38, 37, 33, '2026-03-05 13:25:06'),
(39, 37, 34, '2026-03-05 13:25:07'),
(40, 37, 83, '2026-03-05 13:25:09'),
(41, 37, 81, '2026-03-05 13:25:10'),
(42, 37, 79, '2026-03-05 13:25:11'),
(43, 37, 77, '2026-03-05 13:25:12'),
(44, 37, 75, '2026-03-05 13:25:14');

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
  ADD UNIQUE KEY `unique_review` (`user_id`,`product_id`,`order_id`),
  ADD KEY `idx_reviews_product` (`product_id`),
  ADD KEY `idx_reviews_user` (`user_id`),
  ADD KEY `variant_id` (`variant_id`),
  ADD KEY `fk_review_order` (`order_id`);

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
-- Indexes for table `user_coupons`
--
ALTER TABLE `user_coupons`
  ADD PRIMARY KEY (`id`);

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
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=118;

--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT for table `coupons`
--
ALTER TABLE `coupons`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

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
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=96;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=97;

--
-- AUTO_INCREMENT for table `order_coupons`
--
ALTER TABLE `order_coupons`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `order_items`
--
ALTER TABLE `order_items`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=186;

--
-- AUTO_INCREMENT for table `order_status_history`
--
ALTER TABLE `order_status_history`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=129;

--
-- AUTO_INCREMENT for table `payments`
--
ALTER TABLE `payments`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `permissions`
--
ALTER TABLE `permissions`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=50;

--
-- AUTO_INCREMENT for table `product_images`
--
ALTER TABLE `product_images`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=35;

--
-- AUTO_INCREMENT for table `product_variants`
--
ALTER TABLE `product_variants`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=106;

--
-- AUTO_INCREMENT for table `reviews`
--
ALTER TABLE `reviews`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

--
-- AUTO_INCREMENT for table `roles`
--
ALTER TABLE `roles`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `shipping_addresses`
--
ALTER TABLE `shipping_addresses`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=33;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=38;

--
-- AUTO_INCREMENT for table `user_coupons`
--
ALTER TABLE `user_coupons`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `wishlists`
--
ALTER TABLE `wishlists`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=48;

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
  ADD CONSTRAINT `fk_review_order` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE SET NULL,
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
