-- phpMyAdmin SQL Dump
-- version 4.7.9
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Generation Time: Jun 23, 2018 at 12:12 PM
-- Server version: 5.7.21
-- PHP Version: 7.1.16

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `larafremworktestdb`
--

-- --------------------------------------------------------

--
-- Table structure for table `abcs`
--

DROP TABLE IF EXISTS `abcs`;
CREATE TABLE IF NOT EXISTS `abcs` (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `description` varchar(1000) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `is_active` tinyint(1) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `abcs`
--

INSERT INTO `abcs` (`id`, `created_at`, `updated_at`, `name`, `description`, `is_active`) VALUES
(1, '2018-05-26 02:38:38', '2018-05-26 02:38:38', 'sdsda', 'asdasda', 1);

-- --------------------------------------------------------

--
-- Table structure for table `branches`
--

DROP TABLE IF EXISTS `branches`;
CREATE TABLE IF NOT EXISTS `branches` (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `created_by` int(10) NOT NULL,
  `updated_by` int(10) DEFAULT NULL,
  `details` text COLLATE utf8mb4_unicode_ci COMMENT 'Sometime we need to describe the value of this master data',
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `brands`
--

DROP TABLE IF EXISTS `brands`;
CREATE TABLE IF NOT EXISTS `brands` (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `categories_id` int(10) UNSIGNED DEFAULT NULL,
  `brand_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `segment` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `rfu1` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `rfu2` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `created_by` int(10) UNSIGNED DEFAULT NULL,
  `updated_by` int(10) UNSIGNED DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `is_active` tinyint(1) DEFAULT '1',
  PRIMARY KEY (`id`),
  KEY `products_product_categories_id_index` (`categories_id`),
  KEY `products_created_by_index` (`created_by`),
  KEY `products_updated_by_index` (`updated_by`)
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `brands`
--

INSERT INTO `brands` (`id`, `categories_id`, `brand_name`, `segment`, `description`, `rfu1`, `rfu2`, `created_at`, `created_by`, `updated_by`, `updated_at`, `is_active`) VALUES
(1, 1, 'Tiger', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1),
(2, 1, 'Black Horse', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1),
(3, 2, 'Fizz up', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1),
(4, 2, 'Uro Cola', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1),
(5, 2, 'Uro Lemon', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1),
(6, 2, 'Uro Orange', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1),
(7, 2, 'Uro Zeera', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1),
(8, 3, 'Oranjee', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1),
(9, 3, 'Lemonjee', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1),
(10, 4, 'Mangolee', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1),
(11, 4, 'Mango King', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1),
(12, 5, 'Lychena', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1),
(13, 6, 'Alma', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1);

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

DROP TABLE IF EXISTS `categories`;
CREATE TABLE IF NOT EXISTS `categories` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `category_name` varchar(255) NOT NULL,
  `category_description` text,
  `ordering` int(11) DEFAULT NULL,
  `created_by` int(11) NOT NULL,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  `updated_by` int(11) DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  `status` int(1) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`id`, `category_name`, `category_description`, `ordering`, `created_by`, `created_at`, `updated_by`, `updated_at`, `status`) VALUES
(1, 'Energy Drink', NULL, 1, 1, '2018-05-30 15:02:28', NULL, NULL, NULL),
(2, 'Carbonated Soft Drinks', NULL, 2, 1, '2018-05-30 15:02:28', NULL, NULL, NULL),
(3, 'Pulpy Drink', NULL, 3, 1, '2018-05-30 15:02:28', NULL, NULL, NULL),
(4, 'Mango Fruit Drink', NULL, 4, 1, '2018-05-30 15:02:28', NULL, NULL, NULL),
(5, 'Flavor Drink', NULL, 5, 1, '2018-05-30 15:02:28', NULL, NULL, NULL),
(6, 'Drinking Water', NULL, 6, 1, '2018-05-30 15:02:28', NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `countries`
--

DROP TABLE IF EXISTS `countries`;
CREATE TABLE IF NOT EXISTS `countries` (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `description` varchar(1000) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_by` int(10) UNSIGNED DEFAULT NULL,
  `updated_by` int(10) UNSIGNED DEFAULT NULL,
  `is_active` tinyint(1) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `countries_created_by_index` (`created_by`),
  KEY `countries_updated_by_index` (`updated_by`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `countries`
--

INSERT INTO `countries` (`id`, `created_at`, `updated_at`, `name`, `description`, `created_by`, `updated_by`, `is_active`) VALUES
(1, '2018-05-13 06:06:13', '2018-05-13 06:06:13', 'Bangladesh', 'NA', 2, 1, 1);

-- --------------------------------------------------------

--
-- Table structure for table `departments`
--

DROP TABLE IF EXISTS `departments`;
CREATE TABLE IF NOT EXISTS `departments` (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `created_by` int(10) NOT NULL,
  `updated_by` int(10) DEFAULT NULL,
  `details` text COLLATE utf8mb4_unicode_ci COMMENT 'Sometime we need to describe the value of this master data',
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `departments`
--

INSERT INTO `departments` (`id`, `name`, `created_at`, `updated_at`, `created_by`, `updated_by`, `details`, `is_active`) VALUES
(1, 'Jakir', '2018-05-26 03:07:46', '2018-05-26 03:07:46', 1, 1, 'adf', 1);

-- --------------------------------------------------------

--
-- Table structure for table `designations`
--

DROP TABLE IF EXISTS `designations`;
CREATE TABLE IF NOT EXISTS `designations` (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `created_by` int(10) NOT NULL,
  `updated_by` int(10) DEFAULT NULL,
  `details` text COLLATE utf8mb4_unicode_ci COMMENT 'Sometime we need to describe the value of this master data',
  `status` tinyint(1) NOT NULL DEFAULT '1' COMMENT 'This is basically the row status. whether this record is active or not. 1 = Active, 0 = Inactive ',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `designationss`
--

DROP TABLE IF EXISTS `designationss`;
CREATE TABLE IF NOT EXISTS `designationss` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `designation` varchar(255) COLLATE utf8_bin NOT NULL,
  `designation_short` varchar(50) COLLATE utf8_bin NOT NULL,
  `created_by` int(11) NOT NULL,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `rfu1` varchar(255) COLLATE utf8_bin NOT NULL,
  `rfu2` varchar(255) COLLATE utf8_bin NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- --------------------------------------------------------

--
-- Table structure for table `distribution_houses`
--

DROP TABLE IF EXISTS `distribution_houses`;
CREATE TABLE IF NOT EXISTS `distribution_houses` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `zones_id` int(11) DEFAULT NULL,
  `regions_id` int(11) DEFAULT NULL,
  `territories_id` int(11) DEFAULT NULL,
  `market_name` varchar(255) DEFAULT NULL,
  `code` varchar(255) DEFAULT NULL,
  `point_name` varchar(255) DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  `updated_by` int(11) DEFAULT NULL,
  `updated_at` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `rfu1` varchar(255) DEFAULT NULL,
  `rfu2` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=340 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `distribution_houses`
--

INSERT INTO `distribution_houses` (`id`, `zones_id`, `regions_id`, `territories_id`, `market_name`, `code`, `point_name`, `created_by`, `created_at`, `updated_by`, `updated_at`, `rfu1`, `rfu2`) VALUES
(1, 1, 1, 1, 'Dinajpur', '10110', 'Enamul Store', NULL, '2018-05-30 14:46:17', NULL, '2018-05-30 14:46:17', NULL, NULL),
(2, 1, 1, 1, 'Fulbari', '10115', 'Alim Store', NULL, '2018-05-30 14:46:17', NULL, '2018-05-30 14:46:17', NULL, NULL),
(3, 1, 1, 1, 'Saidpur', '10120', 'Monaf Siddique', NULL, '2018-05-30 14:46:17', NULL, '2018-05-30 14:46:17', NULL, NULL),
(4, 1, 1, 1, 'Nilphamari', '10125', 'Maruf Chira & Rice Mill', NULL, '2018-05-30 14:46:17', NULL, '2018-05-30 14:46:17', NULL, NULL),
(5, 1, 1, 1, 'Birgonj ', '10130', 'Motalib & Sons', NULL, '2018-05-30 14:46:17', NULL, '2018-05-30 14:46:17', NULL, NULL),
(6, 1, 1, 2, 'Thakurgaon', '10135', 'Yeakub Ali & Brothers', NULL, '2018-05-30 14:46:17', NULL, '2018-05-30 14:46:17', NULL, NULL),
(7, 1, 1, 2, 'Panchagar', '10140', 'Dipu Enterprise', NULL, '2018-05-30 14:46:17', NULL, '2018-05-30 14:46:17', NULL, NULL),
(8, 1, 1, 2, 'Dimla', '10145', 'Plabon Gift Korner', NULL, '2018-05-30 14:46:17', NULL, '2018-05-30 14:46:17', NULL, NULL),
(9, 1, 2, 3, 'Rangpur-1', '10150', 'Nowshad Enterprise', NULL, '2018-05-30 14:46:17', NULL, '2018-05-30 14:46:17', NULL, NULL),
(10, 1, 2, 3, 'Rangpur-2', '10155', 'Fatema Traders', NULL, '2018-05-30 14:46:17', NULL, '2018-05-30 14:46:17', NULL, NULL),
(11, 1, 2, 3, 'Pirgonj', '10160', 'Mithila Traders', NULL, '2018-05-30 14:46:17', NULL, '2018-05-30 14:46:17', NULL, NULL),
(12, 1, 2, 3, 'Pirgacha', '10165', 'Milon Enterprise', NULL, '2018-05-30 14:46:17', NULL, '2018-05-30 14:46:17', NULL, NULL),
(13, 1, 2, 4, 'Lalmonirhat', '10180', 'Rafi Enterprise', NULL, '2018-05-30 14:46:17', NULL, '2018-05-30 14:46:17', NULL, NULL),
(14, 1, 2, 4, 'Kurigram', '10170', 'Nurain Enterprise', NULL, '2018-05-30 14:46:17', NULL, '2018-05-30 14:46:17', NULL, NULL),
(15, 1, 2, 4, 'Ulipur', '10175', 'Ayon Traders', NULL, '2018-05-30 14:46:17', NULL, '2018-05-30 14:46:17', NULL, NULL),
(16, 1, 2, 4, 'Patgram', '10185', 'Madina Bekary', NULL, '2018-05-30 14:46:17', NULL, '2018-05-30 14:46:17', NULL, NULL),
(17, 1, 3, 5, 'Joypurhat', '10190', ' Shahidul Traders', NULL, '2018-05-30 14:46:17', NULL, '2018-05-30 14:46:17', NULL, NULL),
(18, 1, 3, 5, 'Panchbibi', '10210', 'Tojammel Hossain', NULL, '2018-05-30 14:46:17', NULL, '2018-05-30 14:46:17', NULL, NULL),
(19, 1, 3, 6, 'Gaibandah-2', '10200', 'Ava Enterprise', NULL, '2018-05-30 14:46:17', NULL, '2018-05-30 14:46:17', NULL, NULL),
(20, 1, 3, 6, 'Gaibandah-1', '10195', 'Bismillah Store', NULL, '2018-05-30 14:46:17', NULL, '2018-05-30 14:46:17', NULL, NULL),
(21, 1, 3, 6, 'Gobindogonj', '10205', 'Afrid Enterprise', NULL, '2018-05-30 14:46:17', NULL, '2018-05-30 14:46:17', NULL, NULL),
(22, 2, 4, 7, 'Bogra Sadar ', '15110', 'Sarker Traders', NULL, '2018-05-30 14:46:17', NULL, '2018-05-30 14:46:17', NULL, NULL),
(23, 2, 4, 7, 'Gabtoli', '15115', 'Siddik Enterprise', NULL, '2018-05-30 14:46:17', NULL, '2018-05-30 14:46:17', NULL, NULL),
(24, 2, 4, 7, 'Sherpur ', '15125', 'Sarker Enterprise', NULL, '2018-05-30 14:46:17', NULL, '2018-05-30 14:46:17', NULL, NULL),
(25, 2, 4, 8, 'Mohasthan ', '15120', 'Sharif Enterprise', NULL, '2018-05-30 14:46:17', NULL, '2018-05-30 14:46:17', NULL, NULL),
(26, 2, 4, 8, 'Dupchachia ', '15130', 'Vai Vai Rice & Boilar Mills', NULL, '2018-05-30 14:46:17', NULL, '2018-05-30 14:46:17', NULL, NULL),
(27, 2, 4, 8, 'Nondigram', '15135', 'Chacha Vatiga Traders', NULL, '2018-05-30 14:46:17', NULL, '2018-05-30 14:46:17', NULL, NULL),
(28, 2, 5, 9, 'Rajsahi-1 ', '15145', 'Soma Construction', NULL, '2018-05-30 14:46:17', NULL, '2018-05-30 14:46:17', NULL, NULL),
(29, 2, 5, 9, 'Kessorhat ', '15150', 'Sadia fall Vander', NULL, '2018-05-30 14:46:17', NULL, '2018-05-30 14:46:17', NULL, NULL),
(30, 2, 5, 9, 'Bagha ', '15180', 'No Dist.', NULL, '2018-05-30 14:46:17', NULL, '2018-05-30 14:46:17', NULL, NULL),
(31, 2, 5, 10, 'Godagari ', '15140', 'Akhey Enterprise', NULL, '2018-05-30 14:46:17', NULL, '2018-05-30 14:46:17', NULL, NULL),
(32, 2, 5, 10, 'Chapai Nawabgonj ', '15155', 'Zaman Enterprise', NULL, '2018-05-30 14:46:17', NULL, '2018-05-30 14:46:17', NULL, NULL),
(33, 2, 5, 10, 'Kanset', '15160', 'Chapai Agro Food ', NULL, '2018-05-30 14:46:17', NULL, '2018-05-30 14:46:17', NULL, NULL),
(34, 2, 6, 11, 'Naogoan ', '15190', 'Sufi Traders', NULL, '2018-05-30 14:46:17', NULL, '2018-05-30 14:46:17', NULL, NULL),
(35, 2, 6, 11, 'Nazipur ', '15195', 'Fatema Enterprise', NULL, '2018-05-30 14:46:17', NULL, '2018-05-30 14:46:17', NULL, NULL),
(36, 2, 6, 11, 'Nowhata ', '15200', 'Kona Store', NULL, '2018-05-30 14:46:17', NULL, '2018-05-30 14:46:17', NULL, NULL),
(37, 2, 6, 12, 'Natore', '15165', 'Sorna Store', NULL, '2018-05-30 14:46:17', NULL, '2018-05-30 14:46:17', NULL, NULL),
(38, 2, 6, 12, 'Singra ', '15170', 'S.K Traders', NULL, '2018-05-30 14:46:17', NULL, '2018-05-30 14:46:17', NULL, NULL),
(39, 2, 6, 12, 'Atrai ', '15205', 'Sarwer Verity Store', NULL, '2018-05-30 14:46:17', NULL, '2018-05-30 14:46:17', NULL, NULL),
(40, 2, 7, 13, 'Pabna-1 ', '15210', 'S.S Trading', NULL, '2018-05-30 14:46:17', NULL, '2018-05-30 14:46:17', NULL, NULL),
(41, 2, 7, 13, 'Bera', '15220', 'Naim Traders', NULL, '2018-05-30 14:46:17', NULL, '2018-05-30 14:46:17', NULL, NULL),
(42, 2, 7, 13, 'Nazirgonj', '15225', 'Fatema Traders', NULL, '2018-05-30 14:46:17', NULL, '2018-05-30 14:46:17', NULL, NULL),
(43, 2, 7, 14, 'Pabna-2 ', '15215', 'New Haque Traders', NULL, '2018-05-30 14:46:17', NULL, '2018-05-30 14:46:17', NULL, NULL),
(44, 2, 7, 14, 'Bonpara', '15175', 'Reza Traders', NULL, '2018-05-30 14:46:17', NULL, '2018-05-30 14:46:17', NULL, NULL),
(45, 2, 7, 14, 'Ishwardi', '15185', 'BNF Trading', NULL, '2018-05-30 14:46:17', NULL, '2018-05-30 14:46:17', NULL, NULL),
(46, 2, 8, 15, 'Sirajgonj ', '15230', 'Faruk Traders', NULL, '2018-05-30 14:46:17', NULL, '2018-05-30 14:46:17', NULL, NULL),
(47, 2, 8, 15, 'Sirajgonj Road ', '15235', 'Green Chillis Resturant', NULL, '2018-05-30 14:46:17', NULL, '2018-05-30 14:46:17', NULL, NULL),
(48, 2, 8, 16, 'Ullapara ', '15240', 'Fardin Trade Center', NULL, '2018-05-30 14:46:17', NULL, '2018-05-30 14:46:17', NULL, NULL),
(49, 2, 8, 16, 'Shajadpur', '15245', 'Eva Enterprise', NULL, '2018-05-30 14:46:17', NULL, '2018-05-30 14:46:17', NULL, NULL),
(50, 2, 8, 16, 'Belkhuchi ', '15250', 'Azad Traders', NULL, '2018-05-30 14:46:17', NULL, '2018-05-30 14:46:17', NULL, NULL),
(51, 3, 9, 17, 'Kushtia', '20110', 'Monoronjon Kumar sen', NULL, '2018-05-30 14:46:17', NULL, '2018-05-30 14:46:17', NULL, NULL),
(52, 3, 9, 18, 'Mirpur', '20115', 'S.K Traders', NULL, '2018-05-30 14:46:17', NULL, '2018-05-30 14:46:17', NULL, NULL),
(53, 3, 9, 18, 'Bheramara', '20125', 'Kajol enterprise', NULL, '2018-05-30 14:46:17', NULL, '2018-05-30 14:46:17', NULL, NULL),
(54, 3, 9, 19, 'Gangni', '20155', 'Shyamoli Book Land', NULL, '2018-05-30 14:46:17', NULL, '2018-05-30 14:46:17', NULL, NULL),
(55, 3, 9, 19, 'Meherpur', '20160', 'N.R Trading', NULL, '2018-05-30 14:46:17', NULL, '2018-05-30 14:46:17', NULL, NULL),
(56, 3, 9, 19, 'Alamdanga', '20150', ' Sainik Kollan Shonghostha ', NULL, '2018-05-30 14:46:17', NULL, '2018-05-30 14:46:17', NULL, NULL),
(57, 3, 10, 20, 'Jhenaidah', '20130', 'AR Traders', NULL, '2018-05-30 14:46:17', NULL, '2018-05-30 14:46:17', NULL, NULL),
(58, 3, 10, 21, 'Kumarkhali', '20120', 'Shabu Cycle Store', NULL, '2018-05-30 14:46:17', NULL, '2018-05-30 14:46:17', NULL, NULL),
(59, 3, 10, 21, 'Shailakupa', '20140', 'Bhai Bhai Traders', NULL, '2018-05-30 14:46:17', NULL, '2018-05-30 14:46:17', NULL, NULL),
(60, 3, 10, 22, 'Cotchadpur', '20135', 'Raihan Traders', NULL, '2018-05-30 14:46:17', NULL, '2018-05-30 14:46:17', NULL, NULL),
(61, 3, 10, 22, 'Chuadanga', '20145', 'Iqbal Brothers', NULL, '2018-05-30 14:46:17', NULL, '2018-05-30 14:46:17', NULL, NULL),
(62, 3, 11, 23, 'Jessore', '20165', 'Falguni Enterprise', NULL, '2018-05-30 14:46:17', NULL, '2018-05-30 14:46:17', NULL, NULL),
(63, 3, 11, 24, 'Jhikorgacha', '20170', 'Golder Traders', NULL, '2018-05-30 14:46:17', NULL, '2018-05-30 14:46:17', NULL, NULL),
(64, 3, 11, 24, 'Sharsha', '20175', 'No Distributor', NULL, '2018-05-30 14:46:17', NULL, '2018-05-30 14:46:17', NULL, NULL),
(65, 3, 11, 24, 'Monirampur', '20190', 'Sawpna store', NULL, '2018-05-30 14:46:17', NULL, '2018-05-30 14:46:17', NULL, NULL),
(66, 3, 11, 25, 'Magura', '20210', 'Sharif Cosmetics', NULL, '2018-05-30 14:46:17', NULL, '2018-05-30 14:46:17', NULL, NULL),
(67, 3, 11, 25, 'AR Para', '20180', 'Synthia Traders', NULL, '2018-05-30 14:46:17', NULL, '2018-05-30 14:46:17', NULL, NULL),
(68, 3, 11, 25, 'Mohammadpur', '20185', 'No Distributor', NULL, '2018-05-30 14:46:17', NULL, '2018-05-30 14:46:17', NULL, NULL),
(69, 3, 11, 26, 'Narail', '20195', 'Sheikh Enterprise', NULL, '2018-05-30 14:46:17', NULL, '2018-05-30 14:46:17', NULL, NULL),
(70, 3, 11, 26, 'Nowapara', '20200', 'Binimoy store', NULL, '2018-05-30 14:46:17', NULL, '2018-05-30 14:46:17', NULL, NULL),
(71, 3, 11, 26, 'Kalia', '20205', 'Kalia Kundu Store', NULL, '2018-05-30 14:46:17', NULL, '2018-05-30 14:46:17', NULL, NULL),
(72, 3, 12, 27, 'Khulna', '20215', 'Marine Beverage', NULL, '2018-05-30 14:46:17', NULL, '2018-05-30 14:46:17', NULL, NULL),
(73, 3, 12, 28, 'Lockpur/ East Rupsha', '20225', 'Allahr Daan Traders', NULL, '2018-05-30 14:46:17', NULL, '2018-05-30 14:46:17', NULL, NULL),
(74, 3, 12, 28, 'Mongla', '20235', 'N.J Store', NULL, '2018-05-30 14:46:17', NULL, '2018-05-30 14:46:17', NULL, NULL),
(75, 3, 12, 28, 'Terokhada', '20230', 'B.M Traders', NULL, '2018-05-30 14:46:17', NULL, '2018-05-30 14:46:17', NULL, NULL),
(76, 3, 12, 29, 'Dawlatpur', '20220', 'Mostafa Traders', NULL, '2018-05-30 14:46:17', NULL, '2018-05-30 14:46:17', NULL, NULL),
(77, 3, 13, 30, 'Satkhira', '20240', 'Aqua Enterprise', NULL, '2018-05-30 14:46:17', NULL, '2018-05-30 14:46:17', NULL, NULL),
(78, 3, 13, 30, 'Kaligonj', '20245', 'Fatah Enterprise', NULL, '2018-05-30 14:46:17', NULL, '2018-05-30 14:46:17', NULL, NULL),
(79, 3, 13, 31, 'Chuknagar', '20245', 'Vai Vai Store', NULL, '2018-05-30 14:46:17', NULL, '2018-05-30 14:46:17', NULL, NULL),
(80, 3, 13, 31, 'Kapilmani', '20245', 'Toha Enterprise', NULL, '2018-05-30 14:46:17', NULL, '2018-05-30 14:46:17', NULL, NULL),
(81, 3, 13, 31, 'Patkelghata', '20260', 'Bismillah Enterprise', NULL, '2018-05-30 14:46:17', NULL, '2018-05-30 14:46:17', NULL, NULL),
(82, 3, 14, 32, 'Bagerhat', '20265', 'Siam Enterprise', NULL, '2018-05-30 14:46:17', NULL, '2018-05-30 14:46:17', NULL, NULL),
(83, 3, 14, 32, 'Morrelgonj', '20285', 'Shaha Enterprise', NULL, '2018-05-30 14:46:17', NULL, '2018-05-30 14:46:17', NULL, NULL),
(84, 3, 14, 33, 'Pirozpur', '20280', 'Jakir Enterprise', NULL, '2018-05-30 14:46:17', NULL, '2018-05-30 14:46:17', NULL, NULL),
(85, 3, 14, 33, 'Shoronkhola', '20270', 'Saroar Enterprise', NULL, '2018-05-30 14:46:17', NULL, '2018-05-30 14:46:17', NULL, NULL),
(86, 3, 14, 33, 'Chitalmari', '20275', 'Joi oil & Agency', NULL, '2018-05-30 14:46:17', NULL, '2018-05-30 14:46:17', NULL, NULL),
(87, 3, 14, 34, 'Gopalgonj', '20290', 'Maa Varity Store', NULL, '2018-05-30 14:46:17', NULL, '2018-05-30 14:46:17', NULL, NULL),
(88, 3, 14, 34, 'Kotalipara', '20295', 'Joy Guru Vandar', NULL, '2018-05-30 14:46:17', NULL, '2018-05-30 14:46:17', NULL, NULL),
(89, 3, 14, 34, 'Tungipara', '20300', 'Khondakar Traders', NULL, '2018-05-30 14:46:17', NULL, '2018-05-30 14:46:17', NULL, NULL),
(90, 3, 14, 34, 'Muksudpur', '20305', 'Tanvir Enterprise', NULL, '2018-05-30 14:46:17', NULL, '2018-05-30 14:46:17', NULL, NULL),
(91, 3, 14, 34, 'Kashiani', '20310', ' Taz Enterprise', NULL, '2018-05-30 14:46:17', NULL, '2018-05-30 14:46:17', NULL, NULL),
(92, 4, 15, 35, 'Mymensingh', '25110', 'S.M. Enterprise ', NULL, '2018-05-30 14:46:17', NULL, '2018-05-30 14:46:17', NULL, NULL),
(93, 4, 15, 35, 'Muktagachha', '25115', 'Lokanath Traders', NULL, '2018-05-30 14:46:17', NULL, '2018-05-30 14:46:17', NULL, NULL),
(94, 4, 15, 35, 'Shambugonj', '25120', 'Rimjim Enterprise', NULL, '2018-05-30 14:46:17', NULL, '2018-05-30 14:46:17', NULL, NULL),
(95, 4, 15, 35, 'Shaymgonj', '25125', 'Pondit Store', NULL, '2018-05-30 14:46:17', NULL, '2018-05-30 14:46:17', NULL, NULL),
(96, 4, 15, 36, 'Bhaluka', '25155', 'Talukder Traders', NULL, '2018-05-30 14:46:17', NULL, '2018-05-30 14:46:17', NULL, NULL),
(97, 4, 15, 36, 'Gaforgaon', '25160', 'Razzak & Sons', NULL, '2018-05-30 14:46:17', NULL, '2018-05-30 14:46:17', NULL, NULL),
(98, 4, 16, 37, 'Jamalpur', '25130', 'Siddique Porag Enterprise', NULL, '2018-05-30 14:46:17', NULL, '2018-05-30 14:46:17', NULL, NULL),
(99, 4, 16, 37, 'Sarisabari', '25135', 'Saha Store', NULL, '2018-05-30 14:46:17', NULL, '2018-05-30 14:46:17', NULL, NULL),
(100, 4, 16, 37, 'Dewangonj', '25140', 'Satter & Brothers', NULL, '2018-05-30 14:46:17', NULL, '2018-05-30 14:46:17', NULL, NULL),
(101, 4, 16, 37, 'Bakshigonj ', '25145', 'Jahid Enterprise', NULL, '2018-05-30 14:46:17', NULL, '2018-05-30 14:46:17', NULL, NULL),
(102, 4, 16, 37, 'Rawmari', '25150', 'Rezaul Traders', NULL, '2018-05-30 14:46:17', NULL, '2018-05-30 14:46:17', NULL, NULL),
(103, 4, 16, 38, 'Sherpur', '25165', 'Janani Traders', NULL, '2018-05-30 14:46:17', NULL, '2018-05-30 14:46:17', NULL, NULL),
(104, 4, 16, 38, 'Fulpur', '25170', '.Sohag Enterprise', NULL, '2018-05-30 14:46:17', NULL, '2018-05-30 14:46:17', NULL, NULL),
(105, 4, 17, 39, 'Kotiadi', '25175', 'Juwel Traders.', NULL, '2018-05-30 14:46:17', NULL, '2018-05-30 14:46:17', NULL, NULL),
(106, 4, 17, 39, ' Kuliarchar ', '25180', 'Haque Enterprise ', NULL, '2018-05-30 14:46:17', NULL, '2018-05-30 14:46:17', NULL, NULL),
(107, 4, 17, 39, ' Nikle ', '25185', 'Nur Shahin Business Center', NULL, '2018-05-30 14:46:17', NULL, '2018-05-30 14:46:17', NULL, NULL),
(108, 4, 17, 39, ' Bazitpur ', '25190', 'Bonik Store ', NULL, '2018-05-30 14:46:17', NULL, '2018-05-30 14:46:17', NULL, NULL),
(109, 4, 17, 40, 'Netrokona ', '25195', 'Shah Sultan Enterprise ', NULL, '2018-05-30 14:46:17', NULL, '2018-05-30 14:46:17', NULL, NULL),
(110, 4, 17, 40, ' Kalmakanda ', '25200', 'Moon Traders ', NULL, '2018-05-30 14:46:17', NULL, '2018-05-30 14:46:17', NULL, NULL),
(111, 4, 17, 40, ' Mohangonj ', '25205', 'Vai VaiI Enterprise ', NULL, '2018-05-30 14:46:17', NULL, '2018-05-30 14:46:17', NULL, NULL),
(112, 4, 17, 40, 'Madon ', '25210', ' Jubeda Enterprise.', NULL, '2018-05-30 14:46:17', NULL, '2018-05-30 14:46:17', NULL, NULL),
(113, 4, 17, 41, 'Nandail', '25215', 'Mayer doa Store  ', NULL, '2018-05-30 14:46:17', NULL, '2018-05-30 14:46:17', NULL, NULL),
(114, 4, 17, 41, 'Korimgonj', '25220', 'Rashel Traders ', NULL, '2018-05-30 14:46:17', NULL, '2018-05-30 14:46:17', NULL, NULL),
(115, 4, 17, 41, ' Hossainpur ', '25225', 'Jishan Store ', NULL, '2018-05-30 14:46:17', NULL, '2018-05-30 14:46:17', NULL, NULL),
(116, 4, 17, 41, ' Kishorgonj ', '25230', 'Hazi Safiruddin & Co. ', NULL, '2018-05-30 14:46:17', NULL, '2018-05-30 14:46:17', NULL, NULL),
(117, 5, 18, 42, 'Tongi', '30110', 'Reazul Karim Enterprise', NULL, '2018-05-30 14:46:17', NULL, '2018-05-30 14:46:17', NULL, NULL),
(118, 5, 18, 42, 'Mirerbazar', '30125', 'Majukhan Enterprise', NULL, '2018-05-30 14:46:17', NULL, '2018-05-30 14:46:17', NULL, NULL),
(119, 5, 18, 43, 'Uttara', '30115', 'Saiful & Brothers', NULL, '2018-05-30 14:46:17', NULL, '2018-05-30 14:46:17', NULL, NULL),
(120, 5, 18, 44, 'Dakhin Khan', '30120', 'Saad Traders', NULL, '2018-05-30 14:46:17', NULL, '2018-05-30 14:46:17', NULL, NULL),
(121, 5, 18, 45, 'Board Bazar', '30130', 'Tijara Corporation', NULL, '2018-05-30 14:46:17', NULL, '2018-05-30 14:46:17', NULL, NULL),
(122, 5, 19, 46, 'Gazipur', '30135', ' Talukder Traders', NULL, '2018-05-30 14:46:17', NULL, '2018-05-30 14:46:17', NULL, NULL),
(123, 5, 19, 47, 'Kapasia', '30140', ' Khan Brothers', NULL, '2018-05-30 14:46:17', NULL, '2018-05-30 14:46:17', NULL, NULL),
(124, 5, 19, 48, 'Hotapara', '30145', 'Shawon Traders', NULL, '2018-05-30 14:46:17', NULL, '2018-05-30 14:46:17', NULL, NULL),
(125, 5, 19, 49, 'Mawna', '30150', 'Rudro Bristy Enterprise', NULL, '2018-05-30 14:46:17', NULL, '2018-05-30 14:46:17', NULL, NULL),
(126, 5, 19, 50, 'Konabari', '40150', 'Bismillah Traders', NULL, '2018-05-30 14:46:17', NULL, '2018-05-30 14:46:17', NULL, NULL),
(127, 5, 19, 51, 'Kaliakoir', '40155', 'Ela Enterprise', NULL, '2018-05-30 14:46:17', NULL, '2018-05-30 14:46:17', NULL, NULL),
(128, 5, 19, 51, 'Shakipur', '40160', 'Rafi Sumi Ent', NULL, '2018-05-30 14:46:17', NULL, '2018-05-30 14:46:17', NULL, NULL),
(129, 5, 20, 52, 'Narsingdi', '30155', 'Lisan Enterprise', NULL, '2018-05-30 14:46:17', NULL, '2018-05-30 14:46:17', NULL, NULL),
(130, 5, 20, 53, 'Raipura', '30160', 'Bondu Traders', NULL, '2018-05-30 14:46:17', NULL, '2018-05-30 14:46:17', NULL, NULL),
(131, 5, 20, 53, 'Bhairab', '30165', 'Nazma Enterprise', NULL, '2018-05-30 14:46:17', NULL, '2018-05-30 14:46:17', NULL, NULL),
(132, 5, 20, 54, 'Rupgonj', '30170', '  S. Bhuiyan & Co.', NULL, '2018-05-30 14:46:17', NULL, '2018-05-30 14:46:17', NULL, NULL),
(133, 5, 20, 54, 'Araihazar', '30175', 'Labina Traders', NULL, '2018-05-30 14:46:17', NULL, '2018-05-30 14:46:17', NULL, NULL),
(134, 5, 21, 55, 'Gulshan', '30180', 'M.M Enterprise', NULL, '2018-05-30 14:46:17', NULL, '2018-05-30 14:46:17', NULL, NULL),
(135, 5, 21, 55, 'Khilgoan', '30185', 'J.K Traders', NULL, '2018-05-30 14:46:17', NULL, '2018-05-30 14:46:17', NULL, NULL),
(136, 5, 21, 56, 'Tejgoan', '30190', 'Wafa Enterprise', NULL, '2018-05-30 14:46:17', NULL, '2018-05-30 14:46:17', NULL, NULL),
(137, 5, 21, 56, 'Rampura', '30195', 'Islam Trading', NULL, '2018-05-30 14:46:17', NULL, '2018-05-30 14:46:17', NULL, NULL),
(138, 5, 21, 57, 'Badda', '30200', 'Razzak Traders', NULL, '2018-05-30 14:46:17', NULL, '2018-05-30 14:46:17', NULL, NULL),
(139, 5, 21, 57, 'Dumni', '30205', 'Raisa Enterprise 2', NULL, '2018-05-30 14:46:17', NULL, '2018-05-30 14:46:17', NULL, NULL),
(140, 6, 22, 58, 'Lalbag', '35110', 'Rafsan Traders', NULL, '2018-05-30 14:46:17', NULL, '2018-05-30 14:46:17', NULL, NULL),
(141, 6, 22, 58, 'Kamrangichar', '35115', 'Al Amin Enterprise', NULL, '2018-05-30 14:46:17', NULL, '2018-05-30 14:46:17', NULL, NULL),
(142, 6, 22, 59, 'Mohammadpur', '35120', 'Mannan Enterprise', NULL, '2018-05-30 14:46:17', NULL, '2018-05-30 14:46:17', NULL, NULL),
(143, 6, 22, 59, 'Zigatola', '35125', 'Hossain Trading Corporation', NULL, '2018-05-30 14:46:17', NULL, '2018-05-30 14:46:17', NULL, NULL),
(144, 6, 22, 60, 'Hatirpool', '35130', 'Dhaka Confectionery', NULL, '2018-05-30 14:46:17', NULL, '2018-05-30 14:46:17', NULL, NULL),
(145, 6, 22, 60, 'Farmgate', '35135', 'Fahim Enterprise', NULL, '2018-05-30 14:46:17', NULL, '2018-05-30 14:46:17', NULL, NULL),
(146, 6, 23, 61, 'Jatrabari', '35140', 'Maa Enterprise', NULL, '2018-05-30 14:46:17', NULL, '2018-05-30 14:46:17', NULL, NULL),
(147, 6, 23, 62, 'Demra', '35145', 'Nuha Trade Center', NULL, '2018-05-30 14:46:17', NULL, '2018-05-30 14:46:17', NULL, NULL),
(148, 6, 23, 63, 'Sutrapur', '35150', 'Al Madina G.Store', NULL, '2018-05-30 14:46:17', NULL, '2018-05-30 14:46:17', NULL, NULL),
(149, 6, 23, 63, 'Shyampur', '35155', 'Bismillah Traders', NULL, '2018-05-30 14:46:17', NULL, '2018-05-30 14:46:17', NULL, NULL),
(150, 6, 23, 64, 'Sabujbagh', '35160', 'Bhai Bhai Traders', NULL, '2018-05-30 14:46:17', NULL, '2018-05-30 14:46:17', NULL, NULL),
(151, 6, 23, 64, 'Bashabo,Nandipara', '35165', 'Al Madina Enterprise', NULL, '2018-05-30 14:46:17', NULL, '2018-05-30 14:46:17', NULL, NULL),
(152, 6, 24, 65, 'Narayangonj', '35170', 'Takwa Enterprise', NULL, '2018-05-30 14:46:17', NULL, '2018-05-30 14:46:17', NULL, NULL),
(153, 6, 24, 66, 'Bhuygor', '35175', 'Maa Enterprise', NULL, '2018-05-30 14:46:17', NULL, '2018-05-30 14:46:17', NULL, NULL),
(154, 6, 24, 66, 'Adamjee', '35180', 'Khokon Enterprise', NULL, '2018-05-30 14:46:17', NULL, '2018-05-30 14:46:17', NULL, NULL),
(155, 6, 24, 67, 'Sonargaon', '35185', 'Mohammadia Enterprise', NULL, '2018-05-30 14:46:17', NULL, '2018-05-30 14:46:17', NULL, NULL),
(156, 6, 24, 67, 'Bandar', '35190', 'Jonaki Enterprise', NULL, '2018-05-30 14:46:17', NULL, '2018-05-30 14:46:17', NULL, NULL),
(157, 6, 24, 67, 'Gozariya ', '35195', 'M.M Traders', NULL, '2018-05-30 14:46:17', NULL, '2018-05-30 14:46:17', NULL, NULL),
(158, 6, 25, 68, 'Zinzira', '35200', 'Nafi & Brothers', NULL, '2018-05-30 14:46:17', NULL, '2018-05-30 14:46:17', NULL, NULL),
(159, 6, 25, 68, 'Sreenagar', '35205', 'Partho Traders', NULL, '2018-05-30 14:46:17', NULL, '2018-05-30 14:46:17', NULL, NULL),
(160, 6, 25, 69, 'Ruhitpur', '35210', 'Anowar & Brothers', NULL, '2018-05-30 14:46:17', NULL, '2018-05-30 14:46:17', NULL, NULL),
(161, 6, 25, 69, 'Dohar', '35215', 'Kabiraz Store', NULL, '2018-05-30 14:46:17', NULL, '2018-05-30 14:46:17', NULL, NULL),
(162, 6, 25, 69, 'Nawabgonj', '35220', 'Sinha Food Products', NULL, '2018-05-30 14:46:17', NULL, '2018-05-30 14:46:17', NULL, NULL),
(163, 6, 25, 70, 'Munshigonj', '35225', 'Samanta Traders', NULL, '2018-05-30 14:46:17', NULL, '2018-05-30 14:46:17', NULL, NULL),
(164, 7, 26, 71, 'Tangail', '40110', 'Friends Enterprise', NULL, '2018-05-30 14:46:17', NULL, '2018-05-30 14:46:17', NULL, NULL),
(165, 7, 26, 71, 'Nagorpur', '40115', 'Rajib Sajib Enterprise', NULL, '2018-05-30 14:46:17', NULL, '2018-05-30 14:46:17', NULL, NULL),
(166, 7, 26, 71, 'Mirzapur', '40120', 'Sollan Cosmatics', NULL, '2018-05-30 14:46:17', NULL, '2018-05-30 14:46:17', NULL, NULL),
(167, 7, 26, 71, 'Pakulla', '40125', 'S.G Traders', NULL, '2018-05-30 14:46:17', NULL, '2018-05-30 14:46:17', NULL, NULL),
(168, 7, 26, 72, 'Elenga', '40130', 'N. K. Datta & Brothers', NULL, '2018-05-30 14:46:17', NULL, '2018-05-30 14:46:17', NULL, NULL),
(169, 7, 26, 72, 'Ghatail', '40135', 'Mostafa Enterprise', NULL, '2018-05-30 14:46:17', NULL, '2018-05-30 14:46:17', NULL, NULL),
(170, 7, 26, 72, 'Bhuapur', '40140', 'Ibrahim Enterprise', NULL, '2018-05-30 14:46:17', NULL, '2018-05-30 14:46:17', NULL, NULL),
(171, 7, 26, 72, 'Madhupur', '40145', 'Sarker Traders', NULL, '2018-05-30 14:46:17', NULL, '2018-05-30 14:46:17', NULL, NULL),
(172, 7, 27, 73, 'Savar', '40165', 'Seam Enterprise', NULL, '2018-05-30 14:46:17', NULL, '2018-05-30 14:46:17', NULL, NULL),
(173, 7, 27, 73, 'Ashulia', '40170', 'Ratul Enterprise', NULL, '2018-05-30 14:46:17', NULL, '2018-05-30 14:46:17', NULL, NULL),
(174, 7, 27, 74, 'Nobinagar', '40175', 'Afroza Enterprise', NULL, '2018-05-30 14:46:17', NULL, '2018-05-30 14:46:17', NULL, NULL),
(175, 7, 27, 75, 'Kalampur', '40180', ' Rima Enterprise', NULL, '2018-05-30 14:46:17', NULL, '2018-05-30 14:46:17', NULL, NULL),
(176, 7, 27, 75, 'Aricha', '40185', 'Abdul Latif Traders', NULL, '2018-05-30 14:46:17', NULL, '2018-05-30 14:46:17', NULL, NULL),
(177, 7, 27, 75, 'Manikgonj', '40190', 'S.K. Enterprise', NULL, '2018-05-30 14:46:17', NULL, '2018-05-30 14:46:17', NULL, NULL),
(178, 7, 28, 76, 'Mirpur-1', '40195', 'Janata Store', NULL, '2018-05-30 14:46:17', NULL, '2018-05-30 14:46:17', NULL, NULL),
(179, 7, 28, 77, 'Mirpur-13', '40200', 'Abdullah Trading', NULL, '2018-05-30 14:46:17', NULL, '2018-05-30 14:46:17', NULL, NULL),
(180, 7, 28, 77, 'Pallabi', '40205', 'Sayma Sweets & Bakery', NULL, '2018-05-30 14:46:17', NULL, '2018-05-30 14:46:17', NULL, NULL),
(181, 7, 28, 78, 'Hemayetpur', '40210', 'Bismillah Store', NULL, '2018-05-30 14:46:17', NULL, '2018-05-30 14:46:17', NULL, NULL),
(182, 7, 28, 78, 'Shingair', '40215', 'Bhai Bhai Traders', NULL, '2018-05-30 14:46:17', NULL, '2018-05-30 14:46:17', NULL, NULL),
(183, 8, 29, 80, 'Faridpur', '45110', 'Shohag Enterprise', NULL, '2018-05-30 14:46:17', NULL, '2018-05-30 14:46:17', NULL, NULL),
(184, 8, 29, 80, 'Shibchar', '45145', 'Rajib Store', NULL, '2018-05-30 14:46:17', NULL, '2018-05-30 14:46:17', NULL, NULL),
(185, 8, 29, 80, 'Vanga', '45155', 'Mithu Enterprise', NULL, '2018-05-30 14:46:17', NULL, '2018-05-30 14:46:17', NULL, NULL),
(186, 8, 29, 81, 'Boalmari', '45115', 'Saha Store', NULL, '2018-05-30 14:46:17', NULL, '2018-05-30 14:46:17', NULL, NULL),
(187, 8, 29, 81, 'Rajbari', '45120', 'Datta Enterprise', NULL, '2018-05-30 14:46:17', NULL, '2018-05-30 14:46:17', NULL, NULL),
(188, 8, 29, 81, 'Pangsha', '45125', 'Showkhin Enterprise', NULL, '2018-05-30 14:46:17', NULL, '2018-05-30 14:46:17', NULL, NULL),
(189, 8, 30, 82, 'Shariatpur', '45130', 'Chitta Enterprise', NULL, '2018-05-30 14:46:17', NULL, '2018-05-30 14:46:17', NULL, NULL),
(190, 8, 30, 82, 'Kazirhat', '45135', 'Kamal Store', NULL, '2018-05-30 14:46:17', NULL, '2018-05-30 14:46:17', NULL, NULL),
(191, 8, 30, 82, 'Vedorgonj', '45140', 'Parvin Enterprise', NULL, '2018-05-30 14:46:17', NULL, '2018-05-30 14:46:17', NULL, NULL),
(192, 8, 30, 83, 'Madaripur ', '45150', 'Bepari Enterprise', NULL, '2018-05-30 14:46:17', NULL, '2018-05-30 14:46:17', NULL, NULL),
(193, 8, 30, 83, 'Tekerhat', '45160', 'Shahid Enterprise', NULL, '2018-05-30 14:46:17', NULL, '2018-05-30 14:46:17', NULL, NULL),
(194, 8, 30, 83, 'Torky', '45205', 'Nowshin Enterprise', NULL, '2018-05-30 14:46:17', NULL, '2018-05-30 14:46:17', NULL, NULL),
(195, 8, 31, 84, 'Barisal -1', '45165', 'Hasan Enterprise', NULL, '2018-05-30 14:46:17', NULL, '2018-05-30 14:46:17', NULL, NULL),
(196, 8, 31, 84, 'Barisal -2', '45170', 'Faruk Store', NULL, '2018-05-30 14:46:17', NULL, '2018-05-30 14:46:17', NULL, NULL),
(197, 8, 31, 84, 'Sharupkathi', '45175', 'Popular store', NULL, '2018-05-30 14:46:17', NULL, '2018-05-30 14:46:17', NULL, NULL),
(198, 8, 31, 85, 'Muladi', '45200', ' Topader Enterprise', NULL, '2018-05-30 14:46:17', NULL, '2018-05-30 14:46:17', NULL, NULL),
(199, 8, 31, 85, 'Uzirpur', '45210', 'Maa Enterprise', NULL, '2018-05-30 14:46:17', NULL, '2018-05-30 14:46:17', NULL, NULL),
(200, 8, 31, 86, 'Jhalakathi', '45215', 'Khondoker Traders', NULL, '2018-05-30 14:46:17', NULL, '2018-05-30 14:46:17', NULL, NULL),
(201, 8, 31, 86, 'Rajapur', '45220', 'Rubel & Baten Enterprise', NULL, '2018-05-30 14:46:17', NULL, '2018-05-30 14:46:17', NULL, NULL),
(202, 8, 31, 86, 'Bhandaria', '45225', ' Tasnim Enterprise', NULL, '2018-05-30 14:46:17', NULL, '2018-05-30 14:46:17', NULL, NULL),
(203, 8, 32, 87, 'Bhola', '45180', 'Bandhan Traders', NULL, '2018-05-30 14:46:17', NULL, '2018-05-30 14:46:17', NULL, NULL),
(204, 8, 32, 87, 'Borhanuddin', '45185', 'Sabiha Enterprise', NULL, '2018-05-30 14:46:17', NULL, '2018-05-30 14:46:17', NULL, NULL),
(205, 8, 32, 87, 'Charfashion', '45195', 'Belayet Motors', NULL, '2018-05-30 14:46:17', NULL, '2018-05-30 14:46:17', NULL, NULL),
(206, 8, 32, 88, 'Lalmohon', '45190', ' Sayera Traders', NULL, '2018-05-30 14:46:17', NULL, '2018-05-30 14:46:17', NULL, NULL),
(207, 8, 32, 88, 'Golachipa', '45240', 'Gawtom & Bros.', NULL, '2018-05-30 14:46:17', NULL, '2018-05-30 14:46:17', NULL, NULL),
(208, 8, 32, 88, 'Bawfal', '45250', 'Akota Enterprise', NULL, '2018-05-30 14:46:17', NULL, '2018-05-30 14:46:17', NULL, NULL),
(209, 8, 33, 89, 'Patuakhali', '45230', 'R K Enterprise', NULL, '2018-05-30 14:46:17', NULL, '2018-05-30 14:46:17', NULL, NULL),
(210, 8, 33, 89, 'Mirzagonj', '45260', 'Keshob Devnath', NULL, '2018-05-30 14:46:17', NULL, '2018-05-30 14:46:17', NULL, NULL),
(211, 8, 33, 90, 'Amtoli', '45235', 'Thakur & Bros.', NULL, '2018-05-30 14:46:17', NULL, '2018-05-30 14:46:17', NULL, NULL),
(212, 8, 33, 90, 'Khepupara', '45245', 'Das Store', NULL, '2018-05-30 14:46:17', NULL, '2018-05-30 14:46:17', NULL, NULL),
(213, 8, 33, 91, 'Barguna', '45255', 'Samira Traders', NULL, '2018-05-30 14:46:17', NULL, '2018-05-30 14:46:17', NULL, NULL),
(214, 8, 33, 91, 'Patharghata', '45265', 'Rabby Store', NULL, '2018-05-30 14:46:17', NULL, '2018-05-30 14:46:17', NULL, NULL),
(215, 8, 33, 91, 'Mathbaria', '45270', 'New Jomadder Enterprise', NULL, '2018-05-30 14:46:17', NULL, '2018-05-30 14:46:17', NULL, NULL),
(216, 9, 34, 92, 'Eidgah', '50110', 'SM Enterprise', NULL, '2018-05-30 14:46:17', NULL, '2018-05-30 14:46:17', NULL, NULL),
(217, 9, 34, 93, 'Laldighi', '50115', 'Hazi Md. Mokhlesh Mia & Sons', NULL, '2018-05-30 14:46:17', NULL, '2018-05-30 14:46:17', NULL, NULL),
(218, 9, 34, 93, 'Companygonj', '50120', 'Rafa Iron', NULL, '2018-05-30 14:46:17', NULL, '2018-05-30 14:46:17', NULL, NULL),
(219, 9, 34, 94, 'Kanaighat', '50125', 'Ajmal & Brothers', NULL, '2018-05-30 14:46:17', NULL, '2018-05-30 14:46:17', NULL, NULL),
(220, 9, 34, 94, 'Goainghat', '50130', 'Azad  Enterprise', NULL, '2018-05-30 14:46:17', NULL, '2018-05-30 14:46:17', NULL, NULL),
(221, 9, 35, 95, 'South Surma', '50155', 'Shohag Trading', NULL, '2018-05-30 14:46:17', NULL, '2018-05-30 14:46:17', NULL, NULL),
(222, 9, 35, 95, 'Fenchugonj', '50185', 'Mahmud Store', NULL, '2018-05-30 14:46:17', NULL, '2018-05-30 14:46:17', NULL, NULL),
(223, 9, 35, 95, 'Goalabazar', '50170', 'Mahima Enterprise', NULL, '2018-05-30 14:46:17', NULL, '2018-05-30 14:46:17', NULL, NULL),
(224, 9, 35, 96, 'Golapgonj', '50175', 'Jaman Enterprise', NULL, '2018-05-30 14:46:17', NULL, '2018-05-30 14:46:17', NULL, NULL),
(225, 9, 35, 96, 'Bianibazar', '50180', 'Jamil Enterprise', NULL, '2018-05-30 14:46:17', NULL, '2018-05-30 14:46:17', NULL, NULL),
(226, 9, 35, 96, 'Jokigonj', '50190', 'Satata Distribution', NULL, '2018-05-30 14:46:17', NULL, '2018-05-30 14:46:17', NULL, NULL),
(227, 9, 36, 97, 'Sunamgonj', '50135', 'Nikesh Ranjon Dey', NULL, '2018-05-30 14:46:17', NULL, '2018-05-30 14:46:17', NULL, NULL),
(228, 9, 36, 97, 'Derai', '50150', 'Marif Eterprise', NULL, '2018-05-30 14:46:17', NULL, '2018-05-30 14:46:17', NULL, NULL),
(229, 9, 36, 97, 'Taherpur', '50145', 'Roman Shah Enterprise', NULL, '2018-05-30 14:46:17', NULL, '2018-05-30 14:46:17', NULL, NULL),
(230, 9, 36, 98, 'Chattak', '50140', 'Juthi Trading', NULL, '2018-05-30 14:46:17', NULL, '2018-05-30 14:46:17', NULL, NULL),
(231, 9, 36, 98, 'Jogonathpur', '50160', 'Islam & Brothers', NULL, '2018-05-30 14:46:17', NULL, '2018-05-30 14:46:17', NULL, NULL),
(232, 9, 36, 98, 'Bisawnath', '50165', 'Shahid Entterprise', NULL, '2018-05-30 14:46:17', NULL, '2018-05-30 14:46:17', NULL, NULL),
(233, 9, 37, 99, 'Moulivibazar', '50195', 'South Sylhet Enterprise', NULL, '2018-05-30 14:46:17', NULL, '2018-05-30 14:46:17', NULL, NULL),
(234, 9, 37, 99, 'Sremongol', '50210', 'Kanika Enterprise', NULL, '2018-05-30 14:46:17', NULL, '2018-05-30 14:46:17', NULL, NULL),
(235, 9, 37, 100, 'Kulaura', '50200', 'M.J Enterprise', NULL, '2018-05-30 14:46:17', NULL, '2018-05-30 14:46:17', NULL, NULL),
(236, 9, 37, 100, 'Borolekha', '50205', 'Alam Brothers', NULL, '2018-05-30 14:46:17', NULL, '2018-05-30 14:46:17', NULL, NULL),
(237, 9, 37, 101, 'Hobigonj', '50215', 'Ishaq Mia & sons.', NULL, '2018-05-30 14:46:17', NULL, '2018-05-30 14:46:17', NULL, NULL),
(238, 9, 37, 101, 'Nobigonj', '50225', 'Dulal Chandra Roy', NULL, '2018-05-30 14:46:17', NULL, '2018-05-30 14:46:17', NULL, NULL),
(239, 9, 37, 101, 'Ranigonj', '50235', 'Hazi Selim & Sons.', NULL, '2018-05-30 14:46:17', NULL, '2018-05-30 14:46:17', NULL, NULL),
(240, 9, 37, 102, 'Madhabpur', '50220', 'Mohanam Electrics', NULL, '2018-05-30 14:46:17', NULL, '2018-05-30 14:46:17', NULL, NULL),
(241, 9, 37, 102, 'Sayestagonj', '50230', 'R.P Enterprise', NULL, '2018-05-30 14:46:17', NULL, '2018-05-30 14:46:17', NULL, NULL),
(242, 10, 38, 103, 'B.Baria', '55110', 'Mamun Traders', NULL, '2018-05-30 14:46:17', NULL, '2018-05-30 14:46:17', NULL, NULL),
(243, 10, 38, 103, 'Akhaura', '55115', 'Parimol Store', NULL, '2018-05-30 14:46:17', NULL, '2018-05-30 14:46:17', NULL, NULL),
(244, 10, 38, 103, 'Kosba', '55120', 'Maa Enterprise', NULL, '2018-05-30 14:46:17', NULL, '2018-05-30 14:46:17', NULL, NULL),
(245, 10, 38, 104, 'Sorail', '55125', 'Tamim Enterprise', NULL, '2018-05-30 14:46:17', NULL, '2018-05-30 14:46:17', NULL, NULL),
(246, 10, 38, 104, 'Nasirnagar', '55130', 'Rahmania Traders', NULL, '2018-05-30 14:46:17', NULL, '2018-05-30 14:46:17', NULL, NULL),
(247, 10, 38, 104, 'Ashugonj', '55135', 'Sheba Stationary', NULL, '2018-05-30 14:46:17', NULL, '2018-05-30 14:46:17', NULL, NULL),
(248, 10, 38, 105, 'Companygonj', '55140', 'Rahman Traders', NULL, '2018-05-30 14:46:17', NULL, '2018-05-30 14:46:17', NULL, NULL),
(249, 10, 38, 105, 'Nabinagar', '55145', 'Nasir Enterprise', NULL, '2018-05-30 14:46:17', NULL, '2018-05-30 14:46:17', NULL, NULL),
(250, 10, 38, 105, 'Bancharampur', '55150', 'Al Rifat Enterprise', NULL, '2018-05-30 14:46:17', NULL, '2018-05-30 14:46:17', NULL, NULL),
(251, 10, 38, 105, 'Dabidar', '55155', 'Tanvir Enterprise', NULL, '2018-05-30 14:46:17', NULL, '2018-05-30 14:46:17', NULL, NULL),
(252, 10, 39, 106, 'Comilla Sador', '55160', 'Ovi Enterprise', NULL, '2018-05-30 14:46:17', NULL, '2018-05-30 14:46:17', NULL, NULL),
(253, 10, 39, 106, 'Burichang', '55170', 'Maa Enterprise', NULL, '2018-05-30 14:46:17', NULL, '2018-05-30 14:46:17', NULL, NULL),
(254, 10, 39, 107, 'Gouripur', '55180', 'Rakib Store', NULL, '2018-05-30 14:46:17', NULL, '2018-05-30 14:46:17', NULL, NULL),
(255, 10, 39, 107, 'Chandina', '55175', 'Janani Enterprise', NULL, '2018-05-30 14:46:17', NULL, '2018-05-30 14:46:17', NULL, NULL),
(256, 10, 39, 107, 'Homna', '55185', 'Humayun Traders', NULL, '2018-05-30 14:46:17', NULL, '2018-05-30 14:46:17', NULL, NULL),
(257, 10, 40, 108, 'Poduar bazar', '55165', 'Brothers Trade Link', NULL, '2018-05-30 14:46:17', NULL, '2018-05-30 14:46:17', NULL, NULL),
(258, 10, 40, 108, 'Barura', '55205', 'Ahsan Traders', NULL, '2018-05-30 14:46:17', NULL, '2018-05-30 14:46:17', NULL, NULL),
(259, 10, 40, 109, 'Laksham', '55190', 'Satota Traders', NULL, '2018-05-30 14:46:17', NULL, '2018-05-30 14:46:17', NULL, NULL),
(260, 10, 40, 109, 'Monohorgonj', '55195', 'Sraboni Enterprise', NULL, '2018-05-30 14:46:17', NULL, '2018-05-30 14:46:17', NULL, NULL),
(261, 10, 40, 109, 'Naglocourt', '55200', 'Shahjalal Rice Mill', NULL, '2018-05-30 14:46:17', NULL, '2018-05-30 14:46:17', NULL, NULL),
(262, 10, 41, 110, 'Chandpur', '55210', 'Jalak Confectionary', NULL, '2018-05-30 14:46:17', NULL, '2018-05-30 14:46:17', NULL, NULL),
(263, 10, 41, 110, 'Motlob', '55215', 'Bismilla Store', NULL, '2018-05-30 14:46:17', NULL, '2018-05-30 14:46:17', NULL, NULL),
(264, 10, 41, 110, 'Chhangarchar', '55220', 'Bismilla Enterprise', NULL, '2018-05-30 14:46:17', NULL, '2018-05-30 14:46:17', NULL, NULL),
(265, 10, 41, 111, 'Hajigonj', '55225', 'Titon shaha', NULL, '2018-05-30 14:46:17', NULL, '2018-05-30 14:46:17', NULL, NULL),
(266, 10, 41, 111, 'Kachua', '55230', 'Bhai Bhai Traders.', NULL, '2018-05-30 14:46:17', NULL, '2018-05-30 14:46:17', NULL, NULL),
(267, 10, 41, 111, 'Shahrasti', '55235', 'Abdur Rahman Traders', NULL, '2018-05-30 14:46:17', NULL, '2018-05-30 14:46:17', NULL, NULL),
(268, 11, 42, 112, 'Chowmuhani', '60155', 'Unique Conf.', NULL, '2018-05-30 14:46:17', NULL, '2018-05-30 14:46:17', NULL, NULL),
(269, 11, 42, 112, 'Senbagh', '60160', 'Yasin Traders', NULL, '2018-05-30 14:46:17', NULL, '2018-05-30 14:46:17', NULL, NULL),
(270, 11, 42, 113, 'Sonaimuri', '60165', 'Romana Trdaers', NULL, '2018-05-30 14:46:17', NULL, '2018-05-30 14:46:17', NULL, NULL),
(271, 11, 42, 113, 'Chatkhil', '60170', 'Sahed Traders', NULL, '2018-05-30 14:46:17', NULL, '2018-05-30 14:46:17', NULL, NULL),
(272, 11, 42, 113, 'Ramgonj', '60175', 'Orchede & Bro.', NULL, '2018-05-30 14:46:17', NULL, '2018-05-30 14:46:17', NULL, NULL),
(273, 11, 42, 114, 'Luxmipur', '60220', 'M.K.H Trad.', NULL, '2018-05-30 14:46:17', NULL, '2018-05-30 14:46:17', NULL, NULL),
(274, 11, 42, 114, 'Chandragonj', '60225', 'Purobi Enter.', NULL, '2018-05-30 14:46:17', NULL, '2018-05-30 14:46:17', NULL, NULL),
(275, 11, 42, 114, 'Raipur', '60230', 'Fathama Enterprise', NULL, '2018-05-30 14:46:17', NULL, '2018-05-30 14:46:17', NULL, NULL),
(276, 11, 43, 115, 'Maijdee', '60180', 'A.B center', NULL, '2018-05-30 14:46:17', NULL, '2018-05-30 14:46:17', NULL, NULL),
(277, 11, 43, 115, 'Datterhat', '60185', 'Rabbi & Bro.', NULL, '2018-05-30 14:46:17', NULL, '2018-05-30 14:46:17', NULL, NULL),
(278, 11, 43, 115, 'Karmullah', '60190', 'Anawara Traders', NULL, '2018-05-30 14:46:17', NULL, '2018-05-30 14:46:17', NULL, NULL),
(279, 11, 43, 116, 'Subarnachar', '60195', 'Samol Watch & Elec.', NULL, '2018-05-30 14:46:17', NULL, '2018-05-30 14:46:17', NULL, NULL),
(280, 11, 43, 116, 'Hatia-1 ', '60200', 'MA Traders', NULL, '2018-05-30 14:46:17', NULL, '2018-05-30 14:46:17', NULL, NULL),
(281, 11, 43, 116, 'Hatia-2', '60205', 'M.A Hossain', NULL, '2018-05-30 14:46:17', NULL, '2018-05-30 14:46:17', NULL, NULL),
(282, 11, 43, 116, 'Alexander', '60210', ' Horipod Kundu & Sons', NULL, '2018-05-30 14:46:17', NULL, '2018-05-30 14:46:17', NULL, NULL),
(283, 11, 43, 116, 'Ramgoti', '60215', 'Ramgoty Traders', NULL, '2018-05-30 14:46:17', NULL, '2018-05-30 14:46:17', NULL, NULL),
(284, 11, 44, 117, 'Feni Sadar', '60110', 'Dubai Traders', NULL, '2018-05-30 14:46:17', NULL, '2018-05-30 14:46:17', NULL, NULL),
(285, 11, 44, 117, 'Chagolnaiya', '60115', ' Dhaka Bekary & Sweets', NULL, '2018-05-30 14:46:17', NULL, '2018-05-30 14:46:17', NULL, NULL),
(286, 11, 44, 117, 'Parsuram', '60120', 'Alam Traders', NULL, '2018-05-30 14:46:17', NULL, '2018-05-30 14:46:17', NULL, NULL),
(287, 11, 44, 118, 'Chowddagram', '60130', 'Rabbi Dept. Store', NULL, '2018-05-30 14:46:17', NULL, '2018-05-30 14:46:17', NULL, NULL),
(288, 11, 44, 118, 'Bariyerhat', '60135', 'M.M Trdaers', NULL, '2018-05-30 14:46:17', NULL, '2018-05-30 14:46:17', NULL, NULL),
(289, 11, 44, 119, 'Dagonbuiyan', '60140', 'Amanat Enterprise', NULL, '2018-05-30 14:46:17', NULL, '2018-05-30 14:46:17', NULL, NULL),
(290, 11, 44, 119, 'Basurhat', '60145', 'Chowdury Enterprise', NULL, '2018-05-30 14:46:17', NULL, '2018-05-30 14:46:17', NULL, NULL),
(291, 11, 44, 119, 'Sonagazi', '60150', 'Hossain Store', NULL, '2018-05-30 14:46:17', NULL, '2018-05-30 14:46:17', NULL, NULL),
(292, 11, 45, 120, 'Nazirhat', '60375', 'Chowdhury Enterprise', NULL, '2018-05-30 14:46:17', NULL, '2018-05-30 14:46:17', NULL, NULL),
(293, 11, 45, 120, 'Manikchari', '60380', 'Riya Enterprise', NULL, '2018-05-30 14:46:17', NULL, '2018-05-30 14:46:17', NULL, NULL),
(294, 11, 45, 120, 'Ramgor', '60395', 'Khan Traders', NULL, '2018-05-30 14:46:17', NULL, '2018-05-30 14:46:17', NULL, NULL),
(295, 11, 45, 120, 'Matiranga', '60405', 'Hazi Momin store', NULL, '2018-05-30 14:46:17', NULL, '2018-05-30 14:46:17', NULL, NULL),
(296, 11, 45, 121, 'Khagrachari', '60385', 'Ayesha Traders', NULL, '2018-05-30 14:46:17', NULL, '2018-05-30 14:46:17', NULL, NULL),
(297, 11, 45, 121, 'Dighinala', '60390', 'Shaha Store', NULL, '2018-05-30 14:46:17', NULL, '2018-05-30 14:46:17', NULL, NULL),
(298, 11, 45, 121, 'Bagaichari', '60400', 'Jashim Enterprise', NULL, '2018-05-30 14:46:17', NULL, '2018-05-30 14:46:17', NULL, NULL),
(299, 12, 46, 122, 'Bondor', '60290', 'Minhaj Enterprise', NULL, '2018-05-30 14:46:17', NULL, '2018-05-30 14:46:17', NULL, NULL),
(300, 12, 46, 122, 'EPZ', '60300', 'Jain Enterprise', NULL, '2018-05-30 14:46:17', NULL, '2018-05-30 14:46:17', NULL, NULL),
(301, 12, 46, 123, 'Potenga', '60295', 'Anas Enterprise', NULL, '2018-05-30 14:46:17', NULL, '2018-05-30 14:46:17', NULL, NULL),
(302, 12, 46, 124, 'Halishahar', '60255', 'Kashem & Sons', NULL, '2018-05-30 14:46:17', NULL, '2018-05-30 14:46:17', NULL, NULL),
(303, 12, 46, 124, 'Madar Bari', '60315', 'Nasim Store', NULL, '2018-05-30 14:46:17', NULL, '2018-05-30 14:46:17', NULL, NULL),
(304, 12, 46, 125, 'R.Bazar', '60305', 'Tanver Store', NULL, '2018-05-30 14:46:17', NULL, '2018-05-30 14:46:17', NULL, NULL),
(305, 12, 46, 125, 'Asadgonj', '60320', 'Hazi A.Razzak Sawdagor', NULL, '2018-05-30 14:46:17', NULL, '2018-05-30 14:46:17', NULL, NULL),
(306, 12, 46, 126, 'Bakulia', '60250', 'Tahsin Enterprise', NULL, '2018-05-30 14:46:17', NULL, '2018-05-30 14:46:17', NULL, NULL),
(307, 12, 46, 126, 'Chaktai', '60310', 'New Harun Store', NULL, '2018-05-30 14:46:17', NULL, '2018-05-30 14:46:17', NULL, NULL),
(308, 12, 47, 127, 'Panchlish', '60240', 'Sultan Store', NULL, '2018-05-30 14:46:17', NULL, '2018-05-30 14:46:17', NULL, NULL),
(309, 12, 47, 127, 'Khulshi', '60270', 'Nusaiba Enterprise', NULL, '2018-05-30 14:46:17', NULL, '2018-05-30 14:46:17', NULL, NULL),
(310, 12, 47, 128, 'Alangker', '60260', 'Nobojagoron Enterprise', NULL, '2018-05-30 14:46:17', NULL, '2018-05-30 14:46:17', NULL, NULL),
(311, 12, 47, 128, 'Pahartoli', '60265', 'Filter Collection', NULL, '2018-05-30 14:46:17', NULL, '2018-05-30 14:46:17', NULL, NULL),
(312, 12, 47, 129, 'Sitakunda', '60235', 'Nuhash Enterprise', NULL, '2018-05-30 14:46:17', NULL, '2018-05-30 14:46:17', NULL, NULL),
(313, 12, 47, 129, 'Bhatiari', '60275', 'Salauddin store', NULL, '2018-05-30 14:46:17', NULL, '2018-05-30 14:46:17', NULL, NULL),
(314, 12, 47, 129, 'Sandip-1', '60280', 'S.A Enter', NULL, '2018-05-30 14:46:17', NULL, '2018-05-30 14:46:17', NULL, NULL),
(315, 12, 47, 129, 'Sandip-2', '60285', 'Rifat Ent', NULL, '2018-05-30 14:46:17', NULL, '2018-05-30 14:46:17', NULL, NULL),
(316, 12, 48, 130, 'Hathazari', '60370', 'Alompur Enterprise', NULL, '2018-05-30 14:46:17', NULL, '2018-05-30 14:46:17', NULL, NULL),
(317, 12, 48, 130, 'Ranirhat', '60365', 'Adiba Chy.Enterprise', NULL, '2018-05-30 14:46:17', NULL, '2018-05-30 14:46:17', NULL, NULL),
(318, 12, 48, 131, 'Oxyzen', '60345', 'A P Enterprise', NULL, '2018-05-30 14:46:17', NULL, '2018-05-30 14:46:17', NULL, NULL),
(319, 12, 48, 132, 'Chandragona', '60350', 'S A Chy.Enterprise', NULL, '2018-05-30 14:46:17', NULL, '2018-05-30 14:46:17', NULL, NULL),
(320, 12, 48, 132, 'Rangamati', '60360', 'S F Traders', NULL, '2018-05-30 14:46:17', NULL, '2018-05-30 14:46:17', NULL, NULL),
(321, 12, 48, 133, 'Noapara', '60355', 'Bagdad Enterprise', NULL, '2018-05-30 14:46:17', NULL, '2018-05-30 14:46:17', NULL, NULL),
(322, 12, 48, 133, 'Mohora', '60245', 'Modina Traders', NULL, '2018-05-30 14:46:17', NULL, '2018-05-30 14:46:17', NULL, NULL),
(323, 12, 49, 134, 'Cox\'s Bazar', '60410', 'Hazi Kashem & Son\'s', NULL, '2018-05-30 14:46:17', NULL, '2018-05-30 14:46:17', NULL, NULL),
(324, 12, 49, 135, 'Taknaf', '60425', 'Giash Uddin & Brother\'s', NULL, '2018-05-30 14:46:17', NULL, '2018-05-30 14:46:17', NULL, NULL),
(325, 12, 49, 136, 'Ramu', '60415', 'Nazir Store', NULL, '2018-05-30 14:46:17', NULL, '2018-05-30 14:46:17', NULL, NULL),
(326, 12, 49, 136, 'Eidgaon', '60420', 'Anwar & Brother\'s', NULL, '2018-05-30 14:46:17', NULL, '2018-05-30 14:46:17', NULL, NULL),
(327, 12, 49, 136, 'Lama', '60460', 'Juwel & Brother\'s', NULL, '2018-05-30 14:46:17', NULL, '2018-05-30 14:46:17', NULL, NULL),
(328, 12, 49, 136, 'Alikodom', '60465', 'Nasir Store', NULL, '2018-05-30 14:46:17', NULL, '2018-05-30 14:46:17', NULL, NULL),
(329, 12, 49, 137, 'Chokoria', '60450', 'Modina Store', NULL, '2018-05-30 14:46:17', NULL, '2018-05-30 14:46:17', NULL, NULL),
(330, 12, 49, 138, 'Moheskhali', '60455', 'Progoti Store', NULL, '2018-05-30 14:46:17', NULL, '2018-05-30 14:46:17', NULL, NULL),
(331, 12, 49, 138, 'Kutubdia', '60470', 'Kashem Store', NULL, '2018-05-30 14:46:17', NULL, '2018-05-30 14:46:17', NULL, NULL),
(332, 12, 50, 139, 'Amirabad', '60430', 'Alomgir & Brother\'s', NULL, '2018-05-30 14:46:17', NULL, '2018-05-30 14:46:17', NULL, NULL),
(333, 12, 50, 139, 'Bandorban', '60445', 'Abu Taher & Brother\'s', NULL, '2018-05-30 14:46:17', NULL, '2018-05-30 14:46:17', NULL, NULL),
(334, 12, 50, 140, 'Banskhali', '60440', 'Nasir Enterprise', NULL, '2018-05-30 14:46:17', NULL, '2018-05-30 14:46:17', NULL, NULL),
(335, 12, 50, 140, 'Satkania/ Keranir Hat', '60435', 'J M Traders', NULL, '2018-05-30 14:46:17', NULL, '2018-05-30 14:46:17', NULL, NULL),
(336, 12, 50, 141, 'Patiya', '60330', 'Shahi Trader\'s', NULL, '2018-05-30 14:46:17', NULL, '2018-05-30 14:46:17', NULL, NULL),
(337, 12, 50, 141, 'Chandanish', '60335', 'Mostofa Trader\'s', NULL, '2018-05-30 14:46:17', NULL, '2018-05-30 14:46:17', NULL, NULL),
(338, 12, 50, 142, 'Anowara', '60325', 'Muhin Enterprise', NULL, '2018-05-30 14:46:17', NULL, '2018-05-30 14:46:17', NULL, NULL),
(339, 12, 50, 142, 'Boalkhali', '60340', 'Hazi Monir Trading', NULL, '2018-05-30 14:46:17', NULL, '2018-05-30 14:46:17', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `districts`
--

DROP TABLE IF EXISTS `districts`;
CREATE TABLE IF NOT EXISTS `districts` (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `divisions_id` int(10) UNSIGNED DEFAULT NULL,
  `description` varchar(1000) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_by` int(10) UNSIGNED DEFAULT NULL,
  `updated_by` int(10) UNSIGNED DEFAULT NULL,
  `is_active` tinyint(1) DEFAULT NULL,
  `countries_id` int(10) UNSIGNED DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `districts_divisions_id_index` (`divisions_id`),
  KEY `districts_created_by_index` (`created_by`),
  KEY `districts_updated_by_index` (`updated_by`),
  KEY `districts_countries_id_index` (`countries_id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `districts`
--

INSERT INTO `districts` (`id`, `created_at`, `updated_at`, `name`, `divisions_id`, `description`, `created_by`, `updated_by`, `is_active`, `countries_id`) VALUES
(1, '2018-05-13 06:10:55', '2018-05-13 06:10:55', 'Sherpur', 1, 'NA', 2, 1, 1, 1),
(2, '2018-05-26 02:20:14', '2018-05-26 02:20:14', 'Jakir', 1, '474', NULL, 1, 1, 1),
(3, '2018-05-26 02:22:27', '2018-05-26 02:22:27', 'Jakir', 1, 'test', NULL, 1, 1, 1);

-- --------------------------------------------------------

--
-- Table structure for table `divisions`
--

DROP TABLE IF EXISTS `divisions`;
CREATE TABLE IF NOT EXISTS `divisions` (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `countries_id` int(10) UNSIGNED DEFAULT NULL,
  `description` varchar(1000) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_by` int(10) UNSIGNED DEFAULT NULL,
  `updated_by` int(10) UNSIGNED DEFAULT NULL,
  `is_active` tinyint(1) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `divisions_countries_id_index` (`countries_id`),
  KEY `divisions_created_by_index` (`created_by`),
  KEY `divisions_updated_by_index` (`updated_by`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `divisions`
--

INSERT INTO `divisions` (`id`, `created_at`, `updated_at`, `name`, `countries_id`, `description`, `created_by`, `updated_by`, `is_active`) VALUES
(1, '2018-05-13 06:10:32', '2018-05-13 06:10:32', 'Dhaka', 1, 'NA', 2, 1, 1);

-- --------------------------------------------------------

--
-- Table structure for table `file_uploads`
--

DROP TABLE IF EXISTS `file_uploads`;
CREATE TABLE IF NOT EXISTS `file_uploads` (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `images` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `file_uploads`
--

INSERT INTO `file_uploads` (`id`, `created_at`, `updated_at`, `images`) VALUES
(1, '2018-05-27 21:25:51', '2018-05-27 21:25:51', 'uploads/qAS6ySN3COIWlRjOYuiDLjnoYJB1O7P9VU5LTyGA.png');

-- --------------------------------------------------------

--
-- Table structure for table `flights`
--

DROP TABLE IF EXISTS `flights`;
CREATE TABLE IF NOT EXISTS `flights` (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `created_by` int(10) NOT NULL,
  `updated_by` int(10) DEFAULT NULL,
  `details` text COLLATE utf8mb4_unicode_ci COMMENT 'Sometime we need to describe the value of this master data',
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `forms`
--

DROP TABLE IF EXISTS `forms`;
CREATE TABLE IF NOT EXISTS `forms` (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Value should not contain any space',
  `form_title` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Title of the Form that will apear at the top of that form',
  `form_subtitle` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `form_target` varchar(40) COLLATE utf8mb4_unicode_ci DEFAULT '_self',
  `encType` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `sub_form_of` int(10) DEFAULT NULL COMMENT 'NULL is define that this is the parent form',
  `is_innerForm` tinyint(1) NOT NULL DEFAULT '0' COMMENT 'if 1 then all the items may append using ADD MORE Button',
  `method` varchar(10) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'POST' COMMENT 'POST or GET',
  `number_of_col` int(2) NOT NULL DEFAULT '1',
  `form_id` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `form_class` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `additional_attr` text COLLATE utf8mb4_unicode_ci COMMENT 'Additional attribute like data-type , style etc',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `created_by` int(10) NOT NULL,
  `updated_by` int(10) DEFAULT NULL,
  `details` text COLLATE utf8mb4_unicode_ci COMMENT 'Sometime we need to describe the value of this master data',
  `submit_for` varchar(10) COLLATE utf8mb4_unicode_ci DEFAULT 'Save' COMMENT 'Save = submit to store data in datastore; Search = Submit to search something from the datastore',
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `form_items`
--

DROP TABLE IF EXISTS `form_items`;
CREATE TABLE IF NOT EXISTS `form_items` (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `forms_id` int(10) UNSIGNED NOT NULL,
  `tag` varchar(10) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'input' COMMENT 'Input, Select or Textarea',
  `type` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'text' COMMENT 'date, number, email, password and so on',
  `label` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `placeholder` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `required` tinyint(1) NOT NULL DEFAULT '0',
  `readonly` tinyint(1) DEFAULT '0',
  `tagClassName` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `tagName` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `tagId` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `additional_attr` text COLLATE utf8mb4_unicode_ci,
  `datasource` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'In case of SELECT Tag. it will carry comma separated value. Otherwise data grid information',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `created_by` int(10) NOT NULL,
  `updated_by` int(10) DEFAULT NULL,
  `details` text COLLATE utf8mb4_unicode_ci COMMENT 'Sometime we need to describe the value of this master data',
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`),
  KEY `fk_fi_forms_id` (`forms_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `grids`
--

DROP TABLE IF EXISTS `grids`;
CREATE TABLE IF NOT EXISTS `grids` (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL,
  `grid_title` varchar(100) DEFAULT NULL,
  `description` text,
  `theads` text NOT NULL COMMENT 'JSON Format data with necessary information relate with column. Every column information will able to config by this data.\r\n\r\n-> field_name [as it is in query]\r\n-> searchable\r\n-> clickable\r\n-> clickable_url\r\n-> click_target [blank, self, popup]',
  `data_query` text NOT NULL COMMENT 'A DB Query',
  `query_groupby` text,
  `query_orderby` text,
  `query_limit` text,
  `query_having` text,
  `pagination` tinyint(1) NOT NULL DEFAULT '1' COMMENT '1 = TRUE, 0 = FALSE',
  `pagination_rules` varchar(50) NOT NULL DEFAULT '10,25,50,100,All' COMMENT 'Comma Separated Value to define Pagination Rules',
  `excel_export` tinyint(1) NOT NULL DEFAULT '0',
  `pdf_export` tinyint(1) NOT NULL DEFAULT '0',
  `search_panel` tinyint(1) NOT NULL DEFAULT '1' COMMENT '1 = TRUE, 0 = FALSE',
  `search_panel_id` int(10) DEFAULT NULL,
  `search_panel_position` enum('Left','Top') DEFAULT 'Top',
  `created_by` int(10) NOT NULL,
  `grid_template_id` int(10) UNSIGNED NOT NULL,
  `created` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_by` int(10) DEFAULT NULL,
  `updated` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `grids`
--

INSERT INTO `grids` (`id`, `name`, `grid_title`, `description`, `theads`, `data_query`, `query_groupby`, `query_orderby`, `query_limit`, `query_having`, `pagination`, `pagination_rules`, `excel_export`, `pdf_export`, `search_panel`, `search_panel_id`, `search_panel_position`, `created_by`, `grid_template_id`, `created`, `updated_by`, `updated`, `is_active`) VALUES
(1, 'Purchase List', 'Purchase List', 'All Purchase will show in this grid', 'SL,Purchase Code,Date,Amount,Vendor', 'SELECT purchase_order.purchase_code, purchase_order.order_date, purchase_order.order_value, vendor.vendor_name FROM purchase_order LEFT JOIN vendor ON purchase_order.vendor_id = vendor.vendor_id where 1', NULL, NULL, NULL, NULL, 1, '10,25,50,100,All', 1, 0, 1, 1, 'Top', 1, 0, '2017-01-11 15:59:36', 1, '2017-01-16 19:21:12', 1),
(2, 'LC Settlement', 'LC Settlement', '', 'SL#,LC Number, Indent Number, Account Head,Date, Amount', 'SELECT\r\nproforma_invoice.lc_number,\r\nproforma_invoice.indent_number,\r\nacc_head.acc_head_name,\r\nvoucherdetails.TransactionDate,\r\nvoucherdetails.Debit\r\nFROM\r\nvoucherdetails\r\nINNER JOIN proforma_invoice ON voucherdetails.LcID = proforma_invoice.proforma_invoice_id\r\nLEFT JOIN acc_head ON voucherdetails.AccountName = acc_head.acc_head_id\r\nWHERE 1 HAVING voucherdetails.Debit > 0', NULL, NULL, NULL, NULL, 1, '10,25,50,100,All', 1, 1, 1, 7, 'Top', 1, 0, '2017-02-01 18:51:58', 1, '2017-06-07 08:44:36', 1),
(3, 'Trial Balence', 'Trial Balance', '', 'SL#,Date,Account Head, Debit,Credit', 'SELECT voucherdetails.TransactionDate,acc_head.acc_head_name,voucherdetails.Debit,voucherdetails.Credit FROM voucherdetails LEFT JOIN acc_head ON voucherdetails.AccountName = acc_head.acc_head_id WHERE 1', NULL, NULL, NULL, NULL, 1, '10,25,50,100,All', 1, 1, 1, 7, 'Top', 1, 0, '2017-02-01 19:12:44', 1, '2017-02-02 11:11:11', 1),
(4, 'Accounts Statement', 'Accounts Statement', '', 'SL#,Date,Accounts Head,Debit,Credit', 'SELECT voucherdetails.TransactionDate, acc_head.acc_head_name, voucherdetails.Debit, voucherdetails.Credit FROM voucherdetails LEFT JOIN acc_head ON voucherdetails.AccountName = acc_head.acc_head_id WHERE 1', NULL, NULL, NULL, NULL, 1, '10,25,50,100,All', 1, 1, 1, 6, 'Top', 1, 0, '2017-02-01 19:16:24', 1, '2017-02-02 11:38:25', 1),
(5, 'Purchase History', 'Purchase History', '', 'Purchase Code, Order Date, Order Value,Status', 'SELECT purchase_order.purchase_id,purchase_order.purchase_code, purchase_order.order_date, purchase_order.order_value,status.status_name FROM purchase_order LEFT JOIN status ON purchase_order.status=status.status_id WHERE 1', NULL, NULL, NULL, NULL, 1, '10,25,50,100,All', 1, 1, 1, 8, 'Top', 1, 0, '2017-06-08 06:15:42', 1, '2017-06-10 09:20:42', 1),
(6, '', NULL, NULL, '', '', NULL, NULL, NULL, NULL, 1, '10,25,50,100,All', 0, 0, 1, NULL, 'Top', 0, 0, '2017-06-10 04:16:18', NULL, NULL, 1),
(7, 'Warranty List', 'Warranty List', '', 'Ticket Code,Sales Code,Product Code,Created,Customer Name,Customer Mobile,Problems,Status,Product Receive,Action', 'SELECT ticket.ticket_id,ticket.ticket_code, ticket.sales_code, product.product_code, ticket.created, ticket.customer_name, ticket.customer_mobile, SUBSTR(ticket.problem_details,1,50) as problems, status.status_name,\'receive\',\'edit\',\'view\',\'delete\' FROM ticket LEFT JOIN status ON ticket.service_status = status.status_id LEFT JOIN product ON product.product_id=ticket.product_id', '', '', NULL, NULL, 1, '10,25,50,100,All', 1, 1, 1, 9, 'Top', 1, 1, '2017-06-18 08:40:46', 1, '2018-01-08 06:57:24', 1),
(8, 'Warranty Archive', 'Warranty Archive', '', 'Code, Customer Name, Customer Mobile, Product Problem, Receive Date, Delivery Date, Action', 'SELECT ticket.ticket_id, ticket.ticket_code, ticket.customer_name, ticket.customer_mobile, ticket.problem_details, ticket.created, ticket.updated, \'view\' FROM ticket WHERE ticket.service_status = 74', '', NULL, NULL, NULL, 1, '10,25,50,100,All', 1, 1, 1, 11, 'Top', 1, 0, '2017-07-26 07:00:53', 1, '2017-07-26 08:29:31', 1),
(9, 'Dealer Target List', 'Dealer Target List', '', 'Branch,Target Month,Base Period,Average By,Based On,Action', 'SELECT dealer_target_set.dealer_target_set_id,location.location_name,dealer_target_set.year_month,dealer_target_set.base_period,dealer_target_set.base_by,dealer_target_set.base_on,\'edit\' FROM dealer_target_set LEFT JOIN location ON dealer_target_set.location_id = location.location_id OR dealer_target_set.dealer_id = \'All\'', 'dealer_target_set.location_id', NULL, NULL, NULL, 1, '10,25,50,100,All', 1, 0, 1, 12, 'Top', 1, 0, '2017-08-03 11:02:24', 1, '2017-08-03 11:41:59', 1),
(10, 'Current Stock', 'Current Stock', '', 'Origin,Product Name,Product_code,Available Qty,On Process Qty, Reorder Qty', 'select product.product_details_json,product.product_id,region.region_name,product.product_name,product.product_code,IFNULL(sum(purchase_good_receive.available_quantity),0) as avQty,(SELECT ( Sum(	purchase_order_details.confirm_quantity ) - Sum( purchase_order_details.total_received ) ) AS optotal from (purchase_order_details) INNER JOIN proforma_invoice ON purchase_order_details.proforma_invoice_id = proforma_invoice.proforma_invoice_id WHERE purchase_order_details.product_id = product.product_id AND proforma_invoice.pi_status IN (48, 50)) as onProcessing,product.reorder_qty FROM product left join region on region.region_id=product.region_id left join purchase_good_receive on product.product_id=purchase_good_receive.product_id AND purchase_good_receive.good_receive_status_id=45 where product.status=\'Active\'', 'product.product_id', 'product.product_id', NULL, NULL, 1, '10,25,50,100,All', 1, 1, 1, 13, 'Top', 1, 1, '2017-12-21 12:13:18', 1, '2018-01-21 06:49:22', 1),
(11, 'Receive History', 'Receive History', '', 'Product Name,product Code,Purchase Code,Indent Number,Warehouse,Receive Qty,Available Qty,Receive Date', 'SELECT product.product_code, product.product_name, purchase_order.purchase_code, purchase_good_receive.indent_number, warehouse.warehouse_name,purchase_good_receive.receive_quantity, purchase_good_receive.available_quantity,purchase_good_receive.created FROM purchase_good_receive LEFT JOIN product ON purchase_good_receive.product_id = product.product_id LEFT JOIN purchase_order ON purchase_good_receive.purchase_id = purchase_order.purchase_id LEFT JOIN warehouse ON purchase_good_receive.warehouse_id = warehouse.warehouse_id WHERE purchase_good_receive.good_receive_status_id=45', '', '', NULL, NULL, 1, '10,25,50,100,All', 1, 1, 0, 13, 'Top', 1, 1, '2017-12-23 06:24:31', 1, '2017-12-23 06:45:14', 1),
(12, 'FOB Ordering', 'FOB Ordering', '', 'Fob Name, Details,Status', 'select \'fob\' as table_name, fob.fob_id as primary_id, fob.fob_name, fob.details,fob.status FROM fob order by ordering', '', '', NULL, NULL, 0, '10,25,50,100,All', 1, 1, 0, 14, 'Top', 1, 1, '2017-12-26 08:14:51', 1, '2017-12-27 05:32:32', 1);

-- --------------------------------------------------------

--
-- Table structure for table `grid_templates`
--

DROP TABLE IF EXISTS `grid_templates`;
CREATE TABLE IF NOT EXISTS `grid_templates` (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `nb_of_column` int(2) NOT NULL DEFAULT '1' COMMENT 'Bootstrap Column. value must be 1-12',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `created_by` int(10) NOT NULL,
  `updated_by` int(10) DEFAULT NULL,
  `details` text COLLATE utf8mb4_unicode_ci COMMENT 'Sometime we need to describe the value of this master data',
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `locations`
--

DROP TABLE IF EXISTS `locations`;
CREATE TABLE IF NOT EXISTS `locations` (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `created_by` int(10) NOT NULL,
  `updated_by` int(10) DEFAULT NULL,
  `details` text COLLATE utf8mb4_unicode_ci COMMENT 'Sometime we need to describe the value of this master data',
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `menus`
--

DROP TABLE IF EXISTS `menus`;
CREATE TABLE IF NOT EXISTS `menus` (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `menus_description` text COLLATE utf8mb4_unicode_ci,
  `menus_type` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `parent_menus_id` int(11) NOT NULL,
  `modules_id` int(11) NOT NULL,
  `icon_class` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `menu_url` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'value after the base url only',
  `sort_number` int(11) NOT NULL,
  `created_by` int(11) NOT NULL DEFAULT '0',
  `updated_by` int(11) NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=15 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `menus`
--

INSERT INTO `menus` (`id`, `name`, `menus_description`, `menus_type`, `parent_menus_id`, `modules_id`, `icon_class`, `menu_url`, `sort_number`, `created_by`, `updated_by`, `created_at`, `updated_at`, `is_active`) VALUES
(2, 'Nationally', 'All National', 'Main', 0, 1, 'fa fa-pencil', '#', 1, 1, 0, NULL, NULL, 1),
(3, 'Zone', '#', 'Sub', 2, 1, 'fa fa-list', 'zones', 1, 1, 0, NULL, NULL, 1),
(4, 'Region', '#', 'Sub', 2, 1, 'fa fa-list', 'regions', 2, 1, 0, NULL, NULL, 1),
(5, 'Territories', '#', 'Sub', 2, 1, 'fa fa-list', 'territories', 2, 1, 0, NULL, NULL, 1),
(6, 'Distributor', '#', 'Sub', 2, 1, 'fa fa-list', 'distribution_houses', 2, 1, 0, NULL, NULL, 1),
(7, 'Product Info', 'All product info here', 'Main', 0, 1, 'fa fa-list', '#', 1, 1, 0, NULL, NULL, 1),
(8, 'Category', '#', 'Sub', 7, 1, 'fa fa-list', 'categories', 1, 1, 0, NULL, NULL, 1),
(9, 'Product Type', '#', 'Sub', 7, 1, 'fa fa-list', 'product_types', 1, 1, 0, NULL, NULL, 0),
(10, 'Product', '#', 'Sub', 7, 1, 'fa fa-list', 'products', 1, 1, 0, NULL, NULL, 1),
(11, 'Skue', '#', 'Sub', 7, 1, 'fa fa-list', 'skues', 1, 1, 0, NULL, NULL, 1),
(12, 'Brand', '#', 'Sub', 7, 1, 'fa fa-list', 'brands', 1, 1, 0, NULL, NULL, 1),
(13, 'SMS', 'All SMS Menu Here', 'Main', 0, 1, 'fa fa-list', '#', 1, 1, 0, NULL, NULL, 1),
(14, 'SMS Inbox', '#', 'Sub', 13, 1, 'fa fa-list', 'sms_inboxes', 1, 1, 0, NULL, NULL, 1);

-- --------------------------------------------------------

--
-- Table structure for table `migrations`
--

DROP TABLE IF EXISTS `migrations`;
CREATE TABLE IF NOT EXISTS `migrations` (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `migration` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=33 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '2014_10_12_000000_create_users_table', 1),
(2, '2014_10_12_100000_create_password_resets_table', 1),
(3, '2018_05_13_085233_create_product_brands_table', 2),
(4, '2018_05_13_101626_create_product_categories_table', 3),
(5, '2018_05_13_104138_alter_product_brands_table_1', 4),
(6, '2018_05_13_110008_alter_product_categories_table_1', 5),
(7, '2018_05_13_113810_create_products_table', 6),
(8, '2018_05_13_115234_alter_products_table_1', 7),
(9, '2018_05_13_115545_alter_products_table_2', 8),
(10, '2018_05_13_120250_create_countries_table', 9),
(11, '2018_05_13_120339_alter_countries_table_1', 10),
(12, '2018_05_13_120454_create_divisions_table', 11),
(13, '2018_05_13_120828_create_districts_table', 12),
(14, '2018_05_13_120937_alter_districts_table_1', 13),
(15, '2018_05_13_121148_create_upazilas_table', 14),
(16, '2018_05_13_121326_create_unions_table', 15),
(17, '2018_05_06_110955_create_user_levels_table', 16),
(18, '2018_05_06_115449_create_privilege_levels_table', 16),
(19, '2018_05_06_120343_create_privilege_menus_table', 16),
(20, '2018_05_06_120810_create_privilege_modules_table', 16),
(21, '2018_05_07_062039_create_module_table', 16),
(22, '2018_05_07_073353_create_menus_table', 16),
(23, '2018_05_07_100639_create_user_settings_table', 16),
(24, '2018_05_14_052659_alter_products_table_3', 17),
(25, '2018_05_14_053227_alter_products_table_4', 18),
(26, '2018_05_14_092034_rename_user_levels_id_to_user_levels', 19),
(27, '2018_05_22_094815_create_posts_table', 19),
(28, '2018_05_26_083641_create_abcs_table', 20),
(29, '2018_05_26_083034_create_zones_table', 21),
(30, '2018_05_26_084100_alter_zones_table_1', 22),
(31, '2018_05_26_090458_alter_zones_table_1', 23),
(32, '2018_05_28_032246_create_file_uploads_table', 24);

-- --------------------------------------------------------

--
-- Table structure for table `modules`
--

DROP TABLE IF EXISTS `modules`;
CREATE TABLE IF NOT EXISTS `modules` (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `modules_icon` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `home_url` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'value only after base url. Should not use the full URL',
  `created_by` int(11) NOT NULL DEFAULT '0',
  `updated_by` int(11) NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `modules`
--

INSERT INTO `modules` (`id`, `name`, `modules_icon`, `description`, `home_url`, `created_by`, `updated_by`, `created_at`, `updated_at`, `is_active`) VALUES
(1, 'Admin', 'fff', 'na', '/', 0, 0, NULL, NULL, 1);

-- --------------------------------------------------------

--
-- Table structure for table `occupations`
--

DROP TABLE IF EXISTS `occupations`;
CREATE TABLE IF NOT EXISTS `occupations` (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `created_by` int(10) NOT NULL,
  `updated_by` int(10) DEFAULT NULL,
  `details` text COLLATE utf8mb4_unicode_ci COMMENT 'Sometime we need to describe the value of this master data',
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

DROP TABLE IF EXISTS `orders`;
CREATE TABLE IF NOT EXISTS `orders` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `aso_id` int(11) NOT NULL DEFAULT '0',
  `asm_rsm_id` int(11) NOT NULL DEFAULT '0',
  `dbid` int(11) NOT NULL DEFAULT '0',
  `order_date` date DEFAULT NULL,
  `requester_name` varchar(255) COLLATE utf8_bin DEFAULT NULL,
  `requester_phone` varchar(255) COLLATE utf8_bin DEFAULT NULL,
  `dh_name` varchar(255) COLLATE utf8_bin DEFAULT NULL,
  `dh_phone` varchar(255) COLLATE utf8_bin DEFAULT NULL,
  `tso_name` varchar(255) COLLATE utf8_bin DEFAULT NULL,
  `tso_phone` varchar(255) COLLATE utf8_bin DEFAULT NULL,
  `route_name` varchar(255) COLLATE utf8_bin DEFAULT NULL,
  `total_outlet` int(11) DEFAULT NULL,
  `visited_outlet` int(11) NOT NULL DEFAULT '0',
  `order_type` enum('Primary','Secondary','Promotion','') COLLATE utf8_bin DEFAULT NULL,
  `total_no_of_memo` int(11) DEFAULT NULL,
  `order_total` int(11) NOT NULL,
  `order_da` decimal(10,2) DEFAULT '0.00',
  `created_by` int(11) NOT NULL,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `rfu1` varchar(255) COLLATE utf8_bin DEFAULT NULL,
  `rfu2` varchar(255) COLLATE utf8_bin DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=8 DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`id`, `aso_id`, `asm_rsm_id`, `dbid`, `order_date`, `requester_name`, `requester_phone`, `dh_name`, `dh_phone`, `tso_name`, `tso_phone`, `route_name`, `total_outlet`, `visited_outlet`, `order_type`, `total_no_of_memo`, `order_total`, `order_da`, `created_by`, `created_at`, `updated_at`, `rfu1`, `rfu2`) VALUES
(1, 11, 0, 0, '2018-02-17', 'test', 'testss', 'adsadsad', 'asdsd', 'asdasd', 'asdsadsd', 'banani', 10, 8, 'Secondary', 33, 1, '0.00', 1, '2018-06-11 09:02:45', '2018-06-11 09:02:45', NULL, NULL),
(2, 0, 11, 121, '2018-02-17', 'test', 'testss', NULL, NULL, NULL, NULL, NULL, NULL, 0, 'Primary', NULL, 16, '0.00', 1, '2018-06-11 09:03:48', '2018-06-11 09:03:48', NULL, NULL),
(3, 11, 0, 0, '2018-02-17', 'test', 'testss', 'adsadsad', 'asdsd', 'asdasd', 'asdsadsd', 'banani', 10, 8, 'Secondary', 33, 1, '0.00', 1, '2018-06-11 09:23:46', '2018-06-11 09:23:46', NULL, NULL),
(4, 0, 11, 121, '2018-02-17', 'test', 'testss', NULL, NULL, NULL, NULL, NULL, NULL, 0, 'Primary', NULL, 16, '0.00', 1, '2018-06-11 09:27:07', '2018-06-11 09:27:07', NULL, NULL),
(5, 11, 0, 0, '2018-02-17', 'test', 'testss', 'adsadsad', 'asdsd', 'asdasd', 'asdsadsd', 'banani', 10, 8, 'Secondary', 33, 1, '0.00', 1, '2018-06-23 06:14:57', '2018-06-23 06:14:57', NULL, NULL),
(6, 0, 11, 121, '2018-02-17', 'test', 'testss', NULL, NULL, NULL, NULL, NULL, NULL, 0, 'Primary', NULL, 16, '0.00', 1, '2018-06-23 06:23:13', '2018-06-23 06:23:13', NULL, NULL),
(7, 11, 0, 0, '2018-02-17', 'test', 'testss', 'adsadsad', 'asdsd', 'asdasd', 'asdsadsd', 'banani', 10, 8, 'Secondary', 33, 1, '0.00', 1, '2018-06-23 09:38:28', '2018-06-23 09:38:28', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `order_details`
--

DROP TABLE IF EXISTS `order_details`;
CREATE TABLE IF NOT EXISTS `order_details` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `orders_id` int(11) NOT NULL,
  `short_name` varchar(255) COLLATE utf8_bin NOT NULL,
  `quantity` decimal(6,2) NOT NULL,
  `created_by` int(11) NOT NULL,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `rfu2` varchar(255) COLLATE utf8_bin DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `order_details_fk0` (`orders_id`)
) ENGINE=MyISAM AUTO_INCREMENT=225 DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

--
-- Dumping data for table `order_details`
--

INSERT INTO `order_details` (`id`, `orders_id`, `short_name`, `quantity`, `created_by`, `created_at`, `updated_at`, `rfu2`) VALUES
(1, 1, 'tp', '0.00', 1, '2018-06-11 15:02:45', '2018-06-11 15:02:45', NULL),
(2, 1, 'tc', '1.00', 1, '2018-06-11 15:02:45', '2018-06-11 15:02:45', NULL),
(3, 1, 'bhp', '0.00', 1, '2018-06-11 15:02:45', '2018-06-11 15:02:45', NULL),
(4, 1, 'bhc', '0.00', 1, '2018-06-11 15:02:45', '2018-06-11 15:02:45', NULL),
(5, 1, 'fp', '0.00', 1, '2018-06-11 15:02:45', '2018-06-11 15:02:45', NULL),
(6, 1, 'fc', '0.00', 1, '2018-06-11 15:02:45', '2018-06-11 15:02:45', NULL),
(7, 1, 'f(h)', '0.00', 1, '2018-06-11 15:02:45', '2018-06-11 15:02:45', NULL),
(8, 1, 'f(1)', '0.00', 1, '2018-06-11 15:02:45', '2018-06-11 15:02:45', NULL),
(9, 1, 'f(2)', '0.00', 1, '2018-06-11 15:02:45', '2018-06-11 15:02:45', NULL),
(10, 1, 'ucp', '0.00', 1, '2018-06-11 15:02:45', '2018-06-11 15:02:45', NULL),
(11, 1, 'ucc', '0.00', 1, '2018-06-11 15:02:45', '2018-06-11 15:02:45', NULL),
(12, 1, 'uc(h)', '0.00', 1, '2018-06-11 15:02:45', '2018-06-11 15:02:45', NULL),
(13, 1, 'ulp', '0.00', 1, '2018-06-11 15:02:45', '2018-06-11 15:02:45', NULL),
(14, 1, 'ulc', '0.00', 1, '2018-06-11 15:02:45', '2018-06-11 15:02:45', NULL),
(15, 1, 'ul(h)', '0.00', 1, '2018-06-11 15:02:45', '2018-06-11 15:02:45', NULL),
(16, 1, 'uop', '0.00', 1, '2018-06-11 15:02:45', '2018-06-11 15:02:45', NULL),
(17, 1, 'uoc', '0.00', 1, '2018-06-11 15:02:45', '2018-06-11 15:02:45', NULL),
(18, 1, 'uo(h)', '0.00', 1, '2018-06-11 15:02:45', '2018-06-11 15:02:45', NULL),
(19, 1, 'uo(1)', '0.00', 1, '2018-06-11 15:02:45', '2018-06-11 15:02:45', NULL),
(20, 1, 'uz', '0.00', 1, '2018-06-11 15:02:45', '2018-06-11 15:02:45', NULL),
(21, 1, 'ornj', '0.00', 1, '2018-06-11 15:02:45', '2018-06-11 15:02:45', NULL),
(22, 1, 'lmnj', '0.00', 1, '2018-06-11 15:02:45', '2018-06-11 15:02:45', NULL),
(23, 1, 'm', '0.00', 1, '2018-06-11 15:02:45', '2018-06-11 15:02:45', NULL),
(24, 1, 'm(h)', '0.00', 1, '2018-06-11 15:02:45', '2018-06-11 15:02:45', NULL),
(25, 1, 'm(1)', '0.00', 1, '2018-06-11 15:02:45', '2018-06-11 15:02:45', NULL),
(26, 1, 'mkp', '0.00', 1, '2018-06-11 15:02:45', '2018-06-11 15:02:45', NULL),
(27, 1, 'mkt', '0.00', 1, '2018-06-11 15:02:45', '2018-06-11 15:02:45', NULL),
(28, 1, 'mk(h)', '0.00', 1, '2018-06-11 15:02:45', '2018-06-11 15:02:45', NULL),
(29, 1, 'lyp', '0.00', 1, '2018-06-11 15:02:45', '2018-06-11 15:02:45', NULL),
(30, 1, 'lyc', '0.00', 1, '2018-06-11 15:02:45', '2018-06-11 15:02:45', NULL),
(31, 1, 'a(h)', '0.00', 1, '2018-06-11 15:02:45', '2018-06-11 15:02:45', NULL),
(32, 1, 'a(1.5)', '0.00', 1, '2018-06-11 15:02:45', '2018-06-11 15:02:45', NULL),
(33, 2, 'tp', '1.00', 1, '2018-06-11 15:03:48', '2018-06-11 15:03:48', NULL),
(34, 2, 'tc', '0.00', 1, '2018-06-11 15:03:48', '2018-06-11 15:03:48', NULL),
(35, 2, 'bhp', '0.00', 1, '2018-06-11 15:03:48', '2018-06-11 15:03:48', NULL),
(36, 2, 'bhc', '0.00', 1, '2018-06-11 15:03:48', '2018-06-11 15:03:48', NULL),
(37, 2, 'fp', '0.00', 1, '2018-06-11 15:03:48', '2018-06-11 15:03:48', NULL),
(38, 2, 'fc', '0.00', 1, '2018-06-11 15:03:48', '2018-06-11 15:03:48', NULL),
(39, 2, 'f(h)', '3.00', 1, '2018-06-11 15:03:48', '2018-06-11 15:03:48', NULL),
(40, 2, 'f(1)', '0.00', 1, '2018-06-11 15:03:48', '2018-06-11 15:03:48', NULL),
(41, 2, 'f(2)', '0.00', 1, '2018-06-11 15:03:48', '2018-06-11 15:03:48', NULL),
(42, 2, 'ucp', '0.00', 1, '2018-06-11 15:03:48', '2018-06-11 15:03:48', NULL),
(43, 2, 'ucc', '12.00', 1, '2018-06-11 15:03:48', '2018-06-11 15:03:48', NULL),
(44, 2, 'uc(h)', '0.00', 1, '2018-06-11 15:03:48', '2018-06-11 15:03:48', NULL),
(45, 2, 'ulp', '0.00', 1, '2018-06-11 15:03:48', '2018-06-11 15:03:48', NULL),
(46, 2, 'ulc', '0.00', 1, '2018-06-11 15:03:48', '2018-06-11 15:03:48', NULL),
(47, 2, 'ul(h)', '0.00', 1, '2018-06-11 15:03:48', '2018-06-11 15:03:48', NULL),
(48, 2, 'uop', '0.00', 1, '2018-06-11 15:03:48', '2018-06-11 15:03:48', NULL),
(49, 2, 'uoc', '0.00', 1, '2018-06-11 15:03:48', '2018-06-11 15:03:48', NULL),
(50, 2, 'uo(h)', '0.00', 1, '2018-06-11 15:03:48', '2018-06-11 15:03:48', NULL),
(51, 2, 'uo(1)', '0.00', 1, '2018-06-11 15:03:48', '2018-06-11 15:03:48', NULL),
(52, 2, 'uz', '0.00', 1, '2018-06-11 15:03:48', '2018-06-11 15:03:48', NULL),
(53, 2, 'ornj', '0.00', 1, '2018-06-11 15:03:48', '2018-06-11 15:03:48', NULL),
(54, 2, 'lmnj', '0.00', 1, '2018-06-11 15:03:48', '2018-06-11 15:03:48', NULL),
(55, 2, 'm', '0.00', 1, '2018-06-11 15:03:48', '2018-06-11 15:03:48', NULL),
(56, 2, 'm(h)', '0.00', 1, '2018-06-11 15:03:48', '2018-06-11 15:03:48', NULL),
(57, 2, 'm(1)', '0.00', 1, '2018-06-11 15:03:48', '2018-06-11 15:03:48', NULL),
(58, 2, 'mkp', '0.00', 1, '2018-06-11 15:03:48', '2018-06-11 15:03:48', NULL),
(59, 2, 'mkt', '0.00', 1, '2018-06-11 15:03:48', '2018-06-11 15:03:48', NULL),
(60, 2, 'mk(h)', '0.00', 1, '2018-06-11 15:03:48', '2018-06-11 15:03:48', NULL),
(61, 2, 'lyp', '0.00', 1, '2018-06-11 15:03:48', '2018-06-11 15:03:48', NULL),
(62, 2, 'lyc', '0.00', 1, '2018-06-11 15:03:48', '2018-06-11 15:03:48', NULL),
(63, 2, 'a(h)', '0.00', 1, '2018-06-11 15:03:48', '2018-06-11 15:03:48', NULL),
(64, 2, 'a(1.5)', '0.00', 1, '2018-06-11 15:03:48', '2018-06-11 15:03:48', NULL),
(65, 3, 'tp', '0.00', 1, '2018-06-11 15:23:46', '2018-06-11 15:23:46', NULL),
(66, 3, 'tc', '1.00', 1, '2018-06-11 15:23:46', '2018-06-11 15:23:46', NULL),
(67, 3, 'bhp', '0.00', 1, '2018-06-11 15:23:46', '2018-06-11 15:23:46', NULL),
(68, 3, 'bhc', '0.00', 1, '2018-06-11 15:23:46', '2018-06-11 15:23:46', NULL),
(69, 3, 'fp', '0.00', 1, '2018-06-11 15:23:46', '2018-06-11 15:23:46', NULL),
(70, 3, 'fc', '0.00', 1, '2018-06-11 15:23:46', '2018-06-11 15:23:46', NULL),
(71, 3, 'f(h)', '0.00', 1, '2018-06-11 15:23:46', '2018-06-11 15:23:46', NULL),
(72, 3, 'f(1)', '0.00', 1, '2018-06-11 15:23:46', '2018-06-11 15:23:46', NULL),
(73, 3, 'f(2)', '0.00', 1, '2018-06-11 15:23:46', '2018-06-11 15:23:46', NULL),
(74, 3, 'ucp', '0.00', 1, '2018-06-11 15:23:46', '2018-06-11 15:23:46', NULL),
(75, 3, 'ucc', '0.00', 1, '2018-06-11 15:23:46', '2018-06-11 15:23:46', NULL),
(76, 3, 'uc(h)', '0.00', 1, '2018-06-11 15:23:46', '2018-06-11 15:23:46', NULL),
(77, 3, 'ulp', '0.00', 1, '2018-06-11 15:23:46', '2018-06-11 15:23:46', NULL),
(78, 3, 'ulc', '0.00', 1, '2018-06-11 15:23:46', '2018-06-11 15:23:46', NULL),
(79, 3, 'ul(h)', '0.00', 1, '2018-06-11 15:23:46', '2018-06-11 15:23:46', NULL),
(80, 3, 'uop', '0.00', 1, '2018-06-11 15:23:46', '2018-06-11 15:23:46', NULL),
(81, 3, 'uoc', '0.00', 1, '2018-06-11 15:23:46', '2018-06-11 15:23:46', NULL),
(82, 3, 'uo(h)', '0.00', 1, '2018-06-11 15:23:46', '2018-06-11 15:23:46', NULL),
(83, 3, 'uo(1)', '0.00', 1, '2018-06-11 15:23:46', '2018-06-11 15:23:46', NULL),
(84, 3, 'uz', '0.00', 1, '2018-06-11 15:23:46', '2018-06-11 15:23:46', NULL),
(85, 3, 'ornj', '0.00', 1, '2018-06-11 15:23:46', '2018-06-11 15:23:46', NULL),
(86, 3, 'lmnj', '0.00', 1, '2018-06-11 15:23:46', '2018-06-11 15:23:46', NULL),
(87, 3, 'm', '0.00', 1, '2018-06-11 15:23:46', '2018-06-11 15:23:46', NULL),
(88, 3, 'm(h)', '0.00', 1, '2018-06-11 15:23:46', '2018-06-11 15:23:46', NULL),
(89, 3, 'm(1)', '0.00', 1, '2018-06-11 15:23:46', '2018-06-11 15:23:46', NULL),
(90, 3, 'mkp', '0.00', 1, '2018-06-11 15:23:46', '2018-06-11 15:23:46', NULL),
(91, 3, 'mkt', '0.00', 1, '2018-06-11 15:23:46', '2018-06-11 15:23:46', NULL),
(92, 3, 'mk(h)', '0.00', 1, '2018-06-11 15:23:46', '2018-06-11 15:23:46', NULL),
(93, 3, 'lyp', '0.00', 1, '2018-06-11 15:23:46', '2018-06-11 15:23:46', NULL),
(94, 3, 'lyc', '0.00', 1, '2018-06-11 15:23:46', '2018-06-11 15:23:46', NULL),
(95, 3, 'a(h)', '0.00', 1, '2018-06-11 15:23:46', '2018-06-11 15:23:46', NULL),
(96, 3, 'a(1.5)', '0.00', 1, '2018-06-11 15:23:46', '2018-06-11 15:23:46', NULL),
(97, 4, 'tp', '1.00', 1, '2018-06-11 15:27:07', '2018-06-11 15:27:07', NULL),
(98, 4, 'tc', '0.00', 1, '2018-06-11 15:27:07', '2018-06-11 15:27:07', NULL),
(99, 4, 'bhp', '0.00', 1, '2018-06-11 15:27:07', '2018-06-11 15:27:07', NULL),
(100, 4, 'bhc', '0.00', 1, '2018-06-11 15:27:07', '2018-06-11 15:27:07', NULL),
(101, 4, 'fp', '0.00', 1, '2018-06-11 15:27:07', '2018-06-11 15:27:07', NULL),
(102, 4, 'fc', '0.00', 1, '2018-06-11 15:27:07', '2018-06-11 15:27:07', NULL),
(103, 4, 'f(h)', '3.00', 1, '2018-06-11 15:27:07', '2018-06-11 15:27:07', NULL),
(104, 4, 'f(1)', '0.00', 1, '2018-06-11 15:27:07', '2018-06-11 15:27:07', NULL),
(105, 4, 'f(2)', '0.00', 1, '2018-06-11 15:27:07', '2018-06-11 15:27:07', NULL),
(106, 4, 'ucp', '0.00', 1, '2018-06-11 15:27:07', '2018-06-11 15:27:07', NULL),
(107, 4, 'ucc', '12.00', 1, '2018-06-11 15:27:07', '2018-06-11 15:27:07', NULL),
(108, 4, 'uc(h)', '0.00', 1, '2018-06-11 15:27:07', '2018-06-11 15:27:07', NULL),
(109, 4, 'ulp', '0.00', 1, '2018-06-11 15:27:07', '2018-06-11 15:27:07', NULL),
(110, 4, 'ulc', '0.00', 1, '2018-06-11 15:27:07', '2018-06-11 15:27:07', NULL),
(111, 4, 'ul(h)', '0.00', 1, '2018-06-11 15:27:07', '2018-06-11 15:27:07', NULL),
(112, 4, 'uop', '0.00', 1, '2018-06-11 15:27:07', '2018-06-11 15:27:07', NULL),
(113, 4, 'uoc', '0.00', 1, '2018-06-11 15:27:07', '2018-06-11 15:27:07', NULL),
(114, 4, 'uo(h)', '0.00', 1, '2018-06-11 15:27:07', '2018-06-11 15:27:07', NULL),
(115, 4, 'uo(1)', '0.00', 1, '2018-06-11 15:27:07', '2018-06-11 15:27:07', NULL),
(116, 4, 'uz', '0.00', 1, '2018-06-11 15:27:07', '2018-06-11 15:27:07', NULL),
(117, 4, 'ornj', '0.00', 1, '2018-06-11 15:27:07', '2018-06-11 15:27:07', NULL),
(118, 4, 'lmnj', '0.00', 1, '2018-06-11 15:27:07', '2018-06-11 15:27:07', NULL),
(119, 4, 'm', '0.00', 1, '2018-06-11 15:27:07', '2018-06-11 15:27:07', NULL),
(120, 4, 'm(h)', '0.00', 1, '2018-06-11 15:27:07', '2018-06-11 15:27:07', NULL),
(121, 4, 'm(1)', '0.00', 1, '2018-06-11 15:27:07', '2018-06-11 15:27:07', NULL),
(122, 4, 'mkp', '0.00', 1, '2018-06-11 15:27:07', '2018-06-11 15:27:07', NULL),
(123, 4, 'mkt', '0.00', 1, '2018-06-11 15:27:07', '2018-06-11 15:27:07', NULL),
(124, 4, 'mk(h)', '0.00', 1, '2018-06-11 15:27:07', '2018-06-11 15:27:07', NULL),
(125, 4, 'lyp', '0.00', 1, '2018-06-11 15:27:07', '2018-06-11 15:27:07', NULL),
(126, 4, 'lyc', '0.00', 1, '2018-06-11 15:27:07', '2018-06-11 15:27:07', NULL),
(127, 4, 'a(h)', '0.00', 1, '2018-06-11 15:27:07', '2018-06-11 15:27:07', NULL),
(128, 4, 'a(1.5)', '0.00', 1, '2018-06-11 15:27:07', '2018-06-11 15:27:07', NULL),
(129, 5, 'tp', '0.00', 1, '2018-06-23 12:14:57', '2018-06-23 12:14:57', NULL),
(130, 5, 'tc', '1.00', 1, '2018-06-23 12:14:57', '2018-06-23 12:14:57', NULL),
(131, 5, 'bhp', '0.00', 1, '2018-06-23 12:14:57', '2018-06-23 12:14:57', NULL),
(132, 5, 'bhc', '0.00', 1, '2018-06-23 12:14:57', '2018-06-23 12:14:57', NULL),
(133, 5, 'fp', '0.00', 1, '2018-06-23 12:14:57', '2018-06-23 12:14:57', NULL),
(134, 5, 'fc', '0.00', 1, '2018-06-23 12:14:57', '2018-06-23 12:14:57', NULL),
(135, 5, 'f(h)', '0.00', 1, '2018-06-23 12:14:57', '2018-06-23 12:14:57', NULL),
(136, 5, 'f(1)', '0.00', 1, '2018-06-23 12:14:57', '2018-06-23 12:14:57', NULL),
(137, 5, 'f(2)', '0.00', 1, '2018-06-23 12:14:57', '2018-06-23 12:14:57', NULL),
(138, 5, 'ucp', '0.00', 1, '2018-06-23 12:14:57', '2018-06-23 12:14:57', NULL),
(139, 5, 'ucc', '0.00', 1, '2018-06-23 12:14:57', '2018-06-23 12:14:57', NULL),
(140, 5, 'uc(h)', '0.00', 1, '2018-06-23 12:14:57', '2018-06-23 12:14:57', NULL),
(141, 5, 'ulp', '0.00', 1, '2018-06-23 12:14:57', '2018-06-23 12:14:57', NULL),
(142, 5, 'ulc', '0.00', 1, '2018-06-23 12:14:57', '2018-06-23 12:14:57', NULL),
(143, 5, 'ul(h)', '0.00', 1, '2018-06-23 12:14:57', '2018-06-23 12:14:57', NULL),
(144, 5, 'uop', '0.00', 1, '2018-06-23 12:14:57', '2018-06-23 12:14:57', NULL),
(145, 5, 'uoc', '0.00', 1, '2018-06-23 12:14:57', '2018-06-23 12:14:57', NULL),
(146, 5, 'uo(h)', '0.00', 1, '2018-06-23 12:14:57', '2018-06-23 12:14:57', NULL),
(147, 5, 'uo(1)', '0.00', 1, '2018-06-23 12:14:57', '2018-06-23 12:14:57', NULL),
(148, 5, 'uz', '0.00', 1, '2018-06-23 12:14:57', '2018-06-23 12:14:57', NULL),
(149, 5, 'ornj', '0.00', 1, '2018-06-23 12:14:57', '2018-06-23 12:14:57', NULL),
(150, 5, 'lmnj', '0.00', 1, '2018-06-23 12:14:57', '2018-06-23 12:14:57', NULL),
(151, 5, 'm', '0.00', 1, '2018-06-23 12:14:57', '2018-06-23 12:14:57', NULL),
(152, 5, 'm(h)', '0.00', 1, '2018-06-23 12:14:57', '2018-06-23 12:14:57', NULL),
(153, 5, 'm(1)', '0.00', 1, '2018-06-23 12:14:57', '2018-06-23 12:14:57', NULL),
(154, 5, 'mkp', '0.00', 1, '2018-06-23 12:14:57', '2018-06-23 12:14:57', NULL),
(155, 5, 'mkt', '0.00', 1, '2018-06-23 12:14:57', '2018-06-23 12:14:57', NULL),
(156, 5, 'mk(h)', '0.00', 1, '2018-06-23 12:14:57', '2018-06-23 12:14:57', NULL),
(157, 5, 'lyp', '0.00', 1, '2018-06-23 12:14:57', '2018-06-23 12:14:57', NULL),
(158, 5, 'lyc', '0.00', 1, '2018-06-23 12:14:57', '2018-06-23 12:14:57', NULL),
(159, 5, 'a(h)', '0.00', 1, '2018-06-23 12:14:57', '2018-06-23 12:14:57', NULL),
(160, 5, 'a(1.5)', '0.00', 1, '2018-06-23 12:14:57', '2018-06-23 12:14:57', NULL),
(161, 6, 'tp', '1.00', 1, '2018-06-23 12:23:13', '2018-06-23 12:23:13', NULL),
(162, 6, 'tc', '0.00', 1, '2018-06-23 12:23:13', '2018-06-23 12:23:13', NULL),
(163, 6, 'bhp', '0.00', 1, '2018-06-23 12:23:13', '2018-06-23 12:23:13', NULL),
(164, 6, 'bhc', '0.00', 1, '2018-06-23 12:23:13', '2018-06-23 12:23:13', NULL),
(165, 6, 'fp', '0.00', 1, '2018-06-23 12:23:13', '2018-06-23 12:23:13', NULL),
(166, 6, 'fc', '0.00', 1, '2018-06-23 12:23:13', '2018-06-23 12:23:13', NULL),
(167, 6, 'f(h)', '3.00', 1, '2018-06-23 12:23:13', '2018-06-23 12:23:13', NULL),
(168, 6, 'f(1)', '0.00', 1, '2018-06-23 12:23:13', '2018-06-23 12:23:13', NULL),
(169, 6, 'f(2)', '0.00', 1, '2018-06-23 12:23:13', '2018-06-23 12:23:13', NULL),
(170, 6, 'ucp', '0.00', 1, '2018-06-23 12:23:13', '2018-06-23 12:23:13', NULL),
(171, 6, 'ucc', '12.00', 1, '2018-06-23 12:23:13', '2018-06-23 12:23:13', NULL),
(172, 6, 'uc(h)', '0.00', 1, '2018-06-23 12:23:13', '2018-06-23 12:23:13', NULL),
(173, 6, 'ulp', '0.00', 1, '2018-06-23 12:23:13', '2018-06-23 12:23:13', NULL),
(174, 6, 'ulc', '0.00', 1, '2018-06-23 12:23:13', '2018-06-23 12:23:13', NULL),
(175, 6, 'ul(h)', '0.00', 1, '2018-06-23 12:23:13', '2018-06-23 12:23:13', NULL),
(176, 6, 'uop', '0.00', 1, '2018-06-23 12:23:13', '2018-06-23 12:23:13', NULL),
(177, 6, 'uoc', '0.00', 1, '2018-06-23 12:23:13', '2018-06-23 12:23:13', NULL),
(178, 6, 'uo(h)', '0.00', 1, '2018-06-23 12:23:13', '2018-06-23 12:23:13', NULL),
(179, 6, 'uo(1)', '0.00', 1, '2018-06-23 12:23:13', '2018-06-23 12:23:13', NULL),
(180, 6, 'uz', '0.00', 1, '2018-06-23 12:23:13', '2018-06-23 12:23:13', NULL),
(181, 6, 'ornj', '0.00', 1, '2018-06-23 12:23:13', '2018-06-23 12:23:13', NULL),
(182, 6, 'lmnj', '0.00', 1, '2018-06-23 12:23:13', '2018-06-23 12:23:13', NULL),
(183, 6, 'm', '0.00', 1, '2018-06-23 12:23:13', '2018-06-23 12:23:13', NULL),
(184, 6, 'm(h)', '0.00', 1, '2018-06-23 12:23:13', '2018-06-23 12:23:13', NULL),
(185, 6, 'm(1)', '0.00', 1, '2018-06-23 12:23:13', '2018-06-23 12:23:13', NULL),
(186, 6, 'mkp', '0.00', 1, '2018-06-23 12:23:13', '2018-06-23 12:23:13', NULL),
(187, 6, 'mkt', '0.00', 1, '2018-06-23 12:23:13', '2018-06-23 12:23:13', NULL),
(188, 6, 'mk(h)', '0.00', 1, '2018-06-23 12:23:13', '2018-06-23 12:23:13', NULL),
(189, 6, 'lyp', '0.00', 1, '2018-06-23 12:23:13', '2018-06-23 12:23:13', NULL),
(190, 6, 'lyc', '0.00', 1, '2018-06-23 12:23:13', '2018-06-23 12:23:13', NULL),
(191, 6, 'a(h)', '0.00', 1, '2018-06-23 12:23:13', '2018-06-23 12:23:13', NULL),
(192, 6, 'a(1.5)', '0.00', 1, '2018-06-23 12:23:13', '2018-06-23 12:23:13', NULL),
(193, 7, 'tp', '0.00', 1, '2018-06-23 15:38:28', '2018-06-23 15:38:28', NULL),
(194, 7, 'tc', '1.00', 1, '2018-06-23 15:38:28', '2018-06-23 15:38:28', NULL),
(195, 7, 'bhp', '0.00', 1, '2018-06-23 15:38:28', '2018-06-23 15:38:28', NULL),
(196, 7, 'bhc', '0.00', 1, '2018-06-23 15:38:28', '2018-06-23 15:38:28', NULL),
(197, 7, 'fp', '0.00', 1, '2018-06-23 15:38:28', '2018-06-23 15:38:28', NULL),
(198, 7, 'fc', '0.00', 1, '2018-06-23 15:38:28', '2018-06-23 15:38:28', NULL),
(199, 7, 'f(h)', '0.00', 1, '2018-06-23 15:38:28', '2018-06-23 15:38:28', NULL),
(200, 7, 'f(1)', '0.00', 1, '2018-06-23 15:38:28', '2018-06-23 15:38:28', NULL),
(201, 7, 'f(2)', '0.00', 1, '2018-06-23 15:38:28', '2018-06-23 15:38:28', NULL),
(202, 7, 'ucp', '0.00', 1, '2018-06-23 15:38:28', '2018-06-23 15:38:28', NULL),
(203, 7, 'ucc', '0.00', 1, '2018-06-23 15:38:28', '2018-06-23 15:38:28', NULL),
(204, 7, 'uc(h)', '0.00', 1, '2018-06-23 15:38:28', '2018-06-23 15:38:28', NULL),
(205, 7, 'ulp', '0.00', 1, '2018-06-23 15:38:28', '2018-06-23 15:38:28', NULL),
(206, 7, 'ulc', '0.00', 1, '2018-06-23 15:38:28', '2018-06-23 15:38:28', NULL),
(207, 7, 'ul(h)', '0.00', 1, '2018-06-23 15:38:28', '2018-06-23 15:38:28', NULL),
(208, 7, 'uop', '0.00', 1, '2018-06-23 15:38:28', '2018-06-23 15:38:28', NULL),
(209, 7, 'uoc', '0.00', 1, '2018-06-23 15:38:28', '2018-06-23 15:38:28', NULL),
(210, 7, 'uo(h)', '0.00', 1, '2018-06-23 15:38:28', '2018-06-23 15:38:28', NULL),
(211, 7, 'uo(1)', '0.00', 1, '2018-06-23 15:38:28', '2018-06-23 15:38:28', NULL),
(212, 7, 'uz', '0.00', 1, '2018-06-23 15:38:28', '2018-06-23 15:38:28', NULL),
(213, 7, 'ornj', '0.00', 1, '2018-06-23 15:38:28', '2018-06-23 15:38:28', NULL),
(214, 7, 'lmnj', '0.00', 1, '2018-06-23 15:38:28', '2018-06-23 15:38:28', NULL),
(215, 7, 'm', '0.00', 1, '2018-06-23 15:38:28', '2018-06-23 15:38:28', NULL),
(216, 7, 'm(h)', '0.00', 1, '2018-06-23 15:38:28', '2018-06-23 15:38:28', NULL),
(217, 7, 'm(1)', '0.00', 1, '2018-06-23 15:38:28', '2018-06-23 15:38:28', NULL),
(218, 7, 'mkp', '0.00', 1, '2018-06-23 15:38:28', '2018-06-23 15:38:28', NULL),
(219, 7, 'mkt', '0.00', 1, '2018-06-23 15:38:28', '2018-06-23 15:38:28', NULL),
(220, 7, 'mk(h)', '0.00', 1, '2018-06-23 15:38:28', '2018-06-23 15:38:28', NULL),
(221, 7, 'lyp', '0.00', 1, '2018-06-23 15:38:28', '2018-06-23 15:38:28', NULL),
(222, 7, 'lyc', '0.00', 1, '2018-06-23 15:38:28', '2018-06-23 15:38:28', NULL),
(223, 7, 'a(h)', '0.00', 1, '2018-06-23 15:38:28', '2018-06-23 15:38:28', NULL),
(224, 7, 'a(1.5)', '0.00', 1, '2018-06-23 15:38:28', '2018-06-23 15:38:28', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `password_resets`
--

DROP TABLE IF EXISTS `password_resets`;
CREATE TABLE IF NOT EXISTS `password_resets` (
  `email` varchar(255) NOT NULL,
  `token` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  KEY `password_resets_email_index` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `post1s`
--

DROP TABLE IF EXISTS `post1s`;
CREATE TABLE IF NOT EXISTS `post1s` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `sms_inbox_name` varchar(100) DEFAULT NULL,
  `created_by` int(10) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `updated_by` int(10) DEFAULT NULL,
  `is_active` tinyint(1) DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `post1s`
--

INSERT INTO `post1s` (`id`, `sms_inbox_name`, `created_by`, `created_at`, `updated_at`, `updated_by`, `is_active`) VALUES
(4, 'asdf', 1, '2018-05-28 02:39:16', '2018-05-28 02:39:16', 1, 1);

-- --------------------------------------------------------

--
-- Table structure for table `posts`
--

DROP TABLE IF EXISTS `posts`;
CREATE TABLE IF NOT EXISTS `posts` (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `description` varchar(1000) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `is_active` tinyint(1) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `posts`
--

INSERT INTO `posts` (`id`, `created_at`, `updated_at`, `name`, `description`, `is_active`) VALUES
(1, '2018-05-24 04:31:51', '2018-05-24 04:31:51', 'dfs', 'sdf', 1),
(2, '2018-05-26 02:17:02', '2018-05-26 02:17:19', 'Jakir', 'sdf', 1);

-- --------------------------------------------------------

--
-- Table structure for table `privilege_levels`
--

DROP TABLE IF EXISTS `privilege_levels`;
CREATE TABLE IF NOT EXISTS `privilege_levels` (
  `users_id` int(11) DEFAULT NULL,
  `user_levels_id` int(11) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  UNIQUE KEY `privilege_levels_user_id_user_level_id_unique` (`users_id`,`user_levels_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `privilege_levels`
--

INSERT INTO `privilege_levels` (`users_id`, `user_levels_id`, `created_at`, `updated_at`) VALUES
(1, 1, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `privilege_menus`
--

DROP TABLE IF EXISTS `privilege_menus`;
CREATE TABLE IF NOT EXISTS `privilege_menus` (
  `menus_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_levels_id` int(11) DEFAULT NULL,
  `users_id` int(11) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`menus_id`),
  UNIQUE KEY `privilege_menus_menu_id_user_level_id_unique` (`menus_id`,`user_levels_id`)
) ENGINE=InnoDB AUTO_INCREMENT=15 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `privilege_menus`
--

INSERT INTO `privilege_menus` (`menus_id`, `user_levels_id`, `users_id`, `created_at`, `updated_at`) VALUES
(1, 1, NULL, NULL, NULL),
(2, 1, NULL, NULL, NULL),
(3, 1, NULL, NULL, NULL),
(4, 1, NULL, NULL, NULL),
(5, 1, NULL, NULL, NULL),
(6, 1, NULL, NULL, NULL),
(7, 1, NULL, NULL, NULL),
(8, 1, NULL, NULL, NULL),
(9, 1, NULL, NULL, NULL),
(10, 1, NULL, NULL, NULL),
(11, 1, NULL, NULL, NULL),
(12, 1, NULL, NULL, NULL),
(13, 1, NULL, NULL, NULL),
(14, 1, NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `privilege_modules`
--

DROP TABLE IF EXISTS `privilege_modules`;
CREATE TABLE IF NOT EXISTS `privilege_modules` (
  `users_id` int(11) NOT NULL DEFAULT '0',
  `modules_id` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `user_levels_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `privilege_modules`
--

INSERT INTO `privilege_modules` (`users_id`, `modules_id`, `created_at`, `updated_at`, `user_levels_id`) VALUES
(1, 1, NULL, NULL, 1);

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

DROP TABLE IF EXISTS `products`;
CREATE TABLE IF NOT EXISTS `products` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `product_name` varchar(255) NOT NULL,
  `brands_id` int(11) NOT NULL,
  `skues_id` int(11) NOT NULL,
  `price` decimal(11,2) DEFAULT NULL,
  `quantity` int(10) DEFAULT NULL,
  `description` text,
  `rfu1` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `rfu2` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `created_by` int(10) UNSIGNED DEFAULT NULL,
  `updated_by` int(10) UNSIGNED DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `is_active` tinyint(1) DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`id`, `product_name`, `brands_id`, `skues_id`, `price`, `quantity`, `description`, `rfu1`, `rfu2`, `created_at`, `created_by`, `updated_by`, `updated_at`, `is_active`) VALUES
(1, 'safda', 2, 1, '9.00', 22, 'asdfasdf', NULL, NULL, '2018-05-27 04:20:29', 1, 1, '2018-05-27 04:30:26', 1);

-- --------------------------------------------------------

--
-- Table structure for table `product_brands`
--

DROP TABLE IF EXISTS `product_brands`;
CREATE TABLE IF NOT EXISTS `product_brands` (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `description` varchar(1000) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_by` int(10) UNSIGNED DEFAULT NULL,
  `updated_by` int(10) UNSIGNED DEFAULT NULL,
  `is_active` tinyint(1) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `product_brands_created_by_index` (`created_by`),
  KEY `product_brands_updated_by_index` (`updated_by`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `product_brands`
--

INSERT INTO `product_brands` (`id`, `created_at`, `updated_at`, `name`, `description`, `created_by`, `updated_by`, `is_active`) VALUES
(1, '2018-05-13 03:26:11', '2018-05-13 03:26:11', 'Sony Rangs', 'sdfsdfsdf', NULL, 1, 1),
(2, '2018-05-13 04:01:40', '2018-05-13 04:10:09', 'Pran RFL', 'sdfsdfs', NULL, NULL, 1),
(3, '2018-05-13 04:40:32', '2018-05-13 04:40:32', 'fsdfsdf', 'sdfsfsdf', NULL, 1, 1),
(4, '2018-05-13 05:25:00', '2018-05-13 05:25:00', 'New Brand', 'NA', NULL, NULL, 1),
(5, '2018-05-13 05:30:11', '2018-05-13 05:30:11', 'mukul', 'sdfsdfsdf', NULL, NULL, 1);

-- --------------------------------------------------------

--
-- Table structure for table `product_categories`
--

DROP TABLE IF EXISTS `product_categories`;
CREATE TABLE IF NOT EXISTS `product_categories` (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `product_brands_id` int(10) UNSIGNED DEFAULT NULL,
  `description` varchar(1000) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_by` int(10) UNSIGNED DEFAULT NULL,
  `updated_by` int(10) UNSIGNED DEFAULT NULL,
  `is_active` tinyint(1) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `product_categories_product_brands_id_index` (`product_brands_id`),
  KEY `product_categories_created_by_index` (`created_by`),
  KEY `product_categories_updated_by_index` (`updated_by`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `product_categories`
--

INSERT INTO `product_categories` (`id`, `created_at`, `updated_at`, `name`, `product_brands_id`, `description`, `created_by`, `updated_by`, `is_active`) VALUES
(1, '2018-05-13 04:59:14', '2018-05-13 05:01:04', 'Electronics', 1, 'dfdfdfdf', 2, 2, 1);

-- --------------------------------------------------------

--
-- Table structure for table `product_types`
--

DROP TABLE IF EXISTS `product_types`;
CREATE TABLE IF NOT EXISTS `product_types` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `category_name` varchar(255) NOT NULL,
  `rfu1` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `rfu2` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `created_by` int(10) UNSIGNED DEFAULT NULL,
  `updated_by` int(10) UNSIGNED DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `is_active` tinyint(1) DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `product_types`
--

INSERT INTO `product_types` (`id`, `category_name`, `rfu1`, `rfu2`, `created_at`, `created_by`, `updated_by`, `updated_at`, `is_active`) VALUES
(1, 'test', NULL, NULL, '2018-05-27 02:29:16', 1, NULL, '2018-05-27 02:29:16', 1);

-- --------------------------------------------------------

--
-- Table structure for table `regions`
--

DROP TABLE IF EXISTS `regions`;
CREATE TABLE IF NOT EXISTS `regions` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `region_name` varchar(255) NOT NULL,
  `ordering` int(11) DEFAULT NULL,
  `created_by` int(11) NOT NULL,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  `updated_by` int(11) DEFAULT NULL,
  `updated_at` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `rfu1` varchar(255) DEFAULT NULL,
  `rfu2` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=51 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `regions`
--

INSERT INTO `regions` (`id`, `region_name`, `ordering`, `created_by`, `created_at`, `updated_by`, `updated_at`, `rfu1`, `rfu2`) VALUES
(1, 'Dinajpur', 1, 1, '2018-05-30 14:08:38', NULL, '2018-05-30 14:08:38', NULL, NULL),
(2, 'Rangpur', 2, 1, '2018-05-30 14:08:38', NULL, '2018-05-30 14:08:38', NULL, NULL),
(3, 'Gobindogonj', 3, 1, '2018-05-30 14:08:38', NULL, '2018-05-30 14:08:38', NULL, NULL),
(4, 'Bogura', 4, 1, '2018-05-30 14:08:38', NULL, '2018-05-30 14:08:38', NULL, NULL),
(5, 'Rajshahi', 5, 1, '2018-05-30 14:08:38', NULL, '2018-05-30 14:08:38', NULL, NULL),
(6, 'Naogoan ', 6, 1, '2018-05-30 14:08:38', NULL, '2018-05-30 14:08:38', NULL, NULL),
(7, 'Pabna', 7, 1, '2018-05-30 14:08:38', NULL, '2018-05-30 14:08:38', NULL, NULL),
(8, 'Sirajgonj ', 8, 1, '2018-05-30 14:08:38', NULL, '2018-05-30 14:08:38', NULL, NULL),
(9, 'Kushtia', 9, 1, '2018-05-30 14:08:38', NULL, '2018-05-30 14:08:38', NULL, NULL),
(10, 'Jhenaidah', 10, 1, '2018-05-30 14:08:38', NULL, '2018-05-30 14:08:38', NULL, NULL),
(11, 'Jessore', 11, 1, '2018-05-30 14:08:38', NULL, '2018-05-30 14:08:38', NULL, NULL),
(12, 'Khulna', 12, 1, '2018-05-30 14:08:38', NULL, '2018-05-30 14:08:38', NULL, NULL),
(13, 'Satkhira', 13, 1, '2018-05-30 14:08:38', NULL, '2018-05-30 14:08:38', NULL, NULL),
(14, 'Bagerhat', 14, 1, '2018-05-30 14:08:38', NULL, '2018-05-30 14:08:38', NULL, NULL),
(15, 'Mymensingh', 15, 1, '2018-05-30 14:08:38', NULL, '2018-05-30 14:08:38', NULL, NULL),
(16, 'Jamalpur', 16, 1, '2018-05-30 14:08:38', NULL, '2018-05-30 14:08:38', NULL, NULL),
(17, ' Kishorgonj ', 17, 1, '2018-05-30 14:08:38', NULL, '2018-05-30 14:08:38', NULL, NULL),
(18, 'Dhaka- (Tongi)', 18, 1, '2018-05-30 14:08:38', NULL, '2018-05-30 14:08:38', NULL, NULL),
(19, 'Dhaka-  (Gazipur)', 19, 1, '2018-05-30 14:08:38', NULL, '2018-05-30 14:08:38', NULL, NULL),
(20, 'Dhaka- (Narshingdi)', 20, 1, '2018-05-30 14:08:38', NULL, '2018-05-30 14:08:38', NULL, NULL),
(21, 'Dhaka- (Gulshan)', 21, 1, '2018-05-30 14:08:38', NULL, '2018-05-30 14:08:38', NULL, NULL),
(22, 'Dhaka - (Lalbag)', 22, 1, '2018-05-30 14:08:38', NULL, '2018-05-30 14:08:38', NULL, NULL),
(23, 'Dhaka -(Jatrabari)', 23, 1, '2018-05-30 14:08:38', NULL, '2018-05-30 14:08:38', NULL, NULL),
(24, 'Dhaka - (Narayangonj)', 24, 1, '2018-05-30 14:08:38', NULL, '2018-05-30 14:08:38', NULL, NULL),
(25, 'Dhaka - (Zinzira)', 25, 1, '2018-05-30 14:08:38', NULL, '2018-05-30 14:08:38', NULL, NULL),
(26, 'Dhaka Tangail', 26, 1, '2018-05-30 14:08:38', NULL, '2018-05-30 14:08:38', NULL, NULL),
(27, 'Dhaka Savar', 27, 1, '2018-05-30 14:08:38', NULL, '2018-05-30 14:08:38', NULL, NULL),
(28, 'Dhaka Mirpur', 28, 1, '2018-05-30 14:08:38', NULL, '2018-05-30 14:08:38', NULL, NULL),
(29, 'Faridpur', 29, 1, '2018-05-30 14:08:38', NULL, '2018-05-30 14:08:38', NULL, NULL),
(30, 'Madaripur', 30, 1, '2018-05-30 14:08:38', NULL, '2018-05-30 14:08:38', NULL, NULL),
(31, 'Barishal', 31, 1, '2018-05-30 14:08:38', NULL, '2018-05-30 14:08:38', NULL, NULL),
(32, 'Bhola', 32, 1, '2018-05-30 14:08:38', NULL, '2018-05-30 14:08:38', NULL, NULL),
(33, 'Patuakhali', 33, 1, '2018-05-30 14:08:38', NULL, '2018-05-30 14:08:38', NULL, NULL),
(34, 'Sylhet -1', 34, 1, '2018-05-30 14:08:38', NULL, '2018-05-30 14:08:38', NULL, NULL),
(35, 'Sylhet -2', 35, 1, '2018-05-30 14:08:38', NULL, '2018-05-30 14:08:38', NULL, NULL),
(36, 'Sylhet -3', 36, 1, '2018-05-30 14:08:38', NULL, '2018-05-30 14:08:38', NULL, NULL),
(37, 'Moulovibazar', 37, 1, '2018-05-30 14:08:38', NULL, '2018-05-30 14:08:38', NULL, NULL),
(38, 'B-Baria', 38, 1, '2018-05-30 14:08:38', NULL, '2018-05-30 14:08:38', NULL, NULL),
(39, 'Comilla-1', 39, 1, '2018-05-30 14:08:38', NULL, '2018-05-30 14:08:38', NULL, NULL),
(40, 'Comilla-2', 40, 1, '2018-05-30 14:08:38', NULL, '2018-05-30 14:08:38', NULL, NULL),
(41, 'Chandpur', 41, 1, '2018-05-30 14:08:38', NULL, '2018-05-30 14:08:38', NULL, NULL),
(42, 'Noakhali - 1', 42, 1, '2018-05-30 14:08:38', NULL, '2018-05-30 14:08:38', NULL, NULL),
(43, 'Noakhali - 2', 43, 1, '2018-05-30 14:08:38', NULL, '2018-05-30 14:08:38', NULL, NULL),
(44, 'Feni', 44, 1, '2018-05-30 14:08:38', NULL, '2018-05-30 14:08:38', NULL, NULL),
(45, 'Khagrachari', 45, 1, '2018-05-30 14:08:38', NULL, '2018-05-30 14:08:38', NULL, NULL),
(46, 'CTG-1', 46, 1, '2018-05-30 14:08:38', NULL, '2018-05-30 14:08:38', NULL, NULL),
(47, 'CTG-2', 47, 1, '2018-05-30 14:08:38', NULL, '2018-05-30 14:08:38', NULL, NULL),
(48, 'Ctg-3', 48, 1, '2018-05-30 14:08:38', NULL, '2018-05-30 14:08:38', NULL, NULL),
(49, 'Cox\'s Bazar-1', 49, 1, '2018-05-30 14:08:38', NULL, '2018-05-30 14:08:38', NULL, NULL),
(50, 'Cox\'s Bazar-2', 50, 1, '2018-05-30 14:08:38', NULL, '2018-05-30 14:08:38', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `sales`
--

DROP TABLE IF EXISTS `sales`;
CREATE TABLE IF NOT EXISTS `sales` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `aso_id` int(11) NOT NULL,
  `asm_rsm_id` int(11) NOT NULL DEFAULT '0',
  `sale_date` date NOT NULL,
  `sender_name` varchar(255) COLLATE utf8_bin NOT NULL,
  `sender_phone` varchar(255) COLLATE utf8_bin NOT NULL,
  `dh_name` varchar(255) COLLATE utf8_bin NOT NULL,
  `dh_phone` varchar(50) COLLATE utf8_bin NOT NULL,
  `tso_name` varchar(255) COLLATE utf8_bin NOT NULL,
  `tso_phone` varchar(50) COLLATE utf8_bin NOT NULL,
  `sale_type` enum('Primary','Secondary','Promotional','') COLLATE utf8_bin NOT NULL,
  `sale_total` int(11) NOT NULL DEFAULT '0',
  `sale_route` varchar(255) COLLATE utf8_bin DEFAULT NULL,
  `created_by` int(11) NOT NULL,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `rfu1` varchar(255) COLLATE utf8_bin DEFAULT NULL,
  `rfu2` varchar(255) COLLATE utf8_bin DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=9 DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

--
-- Dumping data for table `sales`
--

INSERT INTO `sales` (`id`, `aso_id`, `asm_rsm_id`, `sale_date`, `sender_name`, `sender_phone`, `dh_name`, `dh_phone`, `tso_name`, `tso_phone`, `sale_type`, `sale_total`, `sale_route`, `created_by`, `created_at`, `updated_at`, `rfu1`, `rfu2`) VALUES
(1, 11, 0, '2018-02-17', 'test', 'testss', 'adsadsad', 'asdsd', 'asdasd', 'asdsadsd', 'Promotional', 16, NULL, 1, '2018-06-07 10:20:09', '2018-06-12 13:08:43', NULL, NULL),
(2, 11, 0, '2018-02-17', 'test', 'testss', 'adsadsad', 'asdsd', 'asdasd', 'asdsadsd', 'Secondary', 16, NULL, 1, '2018-06-11 09:03:44', '2018-06-11 09:03:44', NULL, NULL),
(3, 11, 0, '2018-02-17', 'test', 'testss', 'adsadsad', 'asdsd', 'asdasd', 'asdsadsd', 'Secondary', 16, NULL, 1, '2018-06-11 09:22:14', '2018-06-11 09:22:14', NULL, NULL),
(4, 11, 0, '2018-02-17', 'test', 'testss', 'adsadsad', 'asdsd', 'asdasd', 'asdsadsd', 'Secondary', 16, NULL, 1, '2018-06-11 09:23:49', '2018-06-11 09:23:49', NULL, NULL),
(5, 0, 0, '2019-01-01', 'test', 'testss', 'adsadsad', 'asdsd', 'asdasd', 'asdsadsd', 'Promotional', 0, 'banani', 1, '2018-06-12 07:09:26', '2018-06-12 07:09:26', NULL, NULL),
(6, 11, 0, '2018-02-17', 'test', 'testss', 'adsadsad', 'asdsd', 'asdasd', 'asdsadsd', 'Secondary', 16, NULL, 1, '2018-06-23 06:23:15', '2018-06-23 06:23:15', NULL, NULL),
(7, 0, 0, '2019-01-01', 'test', 'testss', 'adsadsad', 'asdsd', 'asdasd', 'asdsadsd', 'Promotional', 0, 'banani', 1, '2018-06-23 06:23:18', '2018-06-23 06:23:18', NULL, NULL),
(8, 11, 0, '2018-02-17', 'test', 'testss', 'adsadsad', 'asdsd', 'asdasd', 'asdsadsd', 'Secondary', 16, NULL, 1, '2018-06-23 10:17:09', '2018-06-23 10:17:09', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `sale_details`
--

DROP TABLE IF EXISTS `sale_details`;
CREATE TABLE IF NOT EXISTS `sale_details` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `sales_id` int(11) NOT NULL,
  `short_name` varchar(255) COLLATE utf8_bin DEFAULT NULL,
  `quantity` decimal(6,2) NOT NULL,
  `no_of_memo` int(11) NOT NULL DEFAULT '0',
  `created_by` int(11) NOT NULL,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `delivery_details_fk0` (`sales_id`)
) ENGINE=MyISAM AUTO_INCREMENT=197 DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

--
-- Dumping data for table `sale_details`
--

INSERT INTO `sale_details` (`id`, `sales_id`, `short_name`, `quantity`, `no_of_memo`, `created_by`, `created_at`, `updated_at`) VALUES
(1, 1, 'tp', '1.00', 0, 1, '2018-06-07 16:20:09', '2018-06-07 16:20:09'),
(2, 1, 'tc', '0.00', 0, 1, '2018-06-07 16:20:09', '2018-06-07 16:20:09'),
(3, 1, 'bhp', '0.00', 0, 1, '2018-06-07 16:20:09', '2018-06-07 16:20:09'),
(4, 1, 'bhc', '0.00', 0, 1, '2018-06-07 16:20:09', '2018-06-07 16:20:09'),
(5, 1, 'fp', '0.00', 0, 1, '2018-06-07 16:20:09', '2018-06-07 16:20:09'),
(6, 1, 'fc', '0.00', 0, 1, '2018-06-07 16:20:09', '2018-06-07 16:20:09'),
(7, 1, 'f(h)', '3.00', 0, 1, '2018-06-07 16:20:09', '2018-06-07 16:20:09'),
(8, 1, 'f(1)', '0.00', 0, 1, '2018-06-07 16:20:09', '2018-06-07 16:20:09'),
(9, 1, 'f(2)', '0.00', 0, 1, '2018-06-07 16:20:09', '2018-06-07 16:20:09'),
(10, 1, 'ucp', '0.00', 0, 1, '2018-06-07 16:20:09', '2018-06-07 16:20:09'),
(11, 1, 'ucc', '12.00', 0, 1, '2018-06-07 16:20:09', '2018-06-07 16:20:09'),
(12, 1, 'uc(h)', '0.00', 0, 1, '2018-06-07 16:20:09', '2018-06-07 16:20:09'),
(13, 1, 'ulp', '0.00', 0, 1, '2018-06-07 16:20:09', '2018-06-07 16:20:09'),
(14, 1, 'ulc', '0.00', 0, 1, '2018-06-07 16:20:09', '2018-06-07 16:20:09'),
(15, 1, 'ul(h)', '0.00', 0, 1, '2018-06-07 16:20:09', '2018-06-07 16:20:09'),
(16, 1, 'uop', '0.00', 0, 1, '2018-06-07 16:20:09', '2018-06-07 16:20:09'),
(17, 1, 'uoc', '0.00', 0, 1, '2018-06-07 16:20:09', '2018-06-07 16:20:09'),
(18, 1, 'uo(h)', '0.00', 0, 1, '2018-06-07 16:20:09', '2018-06-07 16:20:09'),
(19, 1, 'uo(1)', '0.00', 0, 1, '2018-06-07 16:20:09', '2018-06-07 16:20:09'),
(20, 1, 'uz', '0.00', 0, 1, '2018-06-07 16:20:09', '2018-06-07 16:20:09'),
(21, 1, 'ornj', '0.00', 0, 1, '2018-06-07 16:20:09', '2018-06-07 16:20:09'),
(22, 1, 'lmnj', '0.00', 0, 1, '2018-06-07 16:20:09', '2018-06-07 16:20:09'),
(23, 1, 'm', '0.00', 0, 1, '2018-06-07 16:20:09', '2018-06-07 16:20:09'),
(24, 1, 'm(h)', '0.00', 0, 1, '2018-06-07 16:20:09', '2018-06-07 16:20:09'),
(25, 1, 'm(1)', '0.00', 0, 1, '2018-06-07 16:20:09', '2018-06-07 16:20:09'),
(26, 1, 'mkp', '0.00', 0, 1, '2018-06-07 16:20:09', '2018-06-07 16:20:09'),
(27, 1, 'mkt', '0.00', 0, 1, '2018-06-07 16:20:09', '2018-06-07 16:20:09'),
(28, 1, 'mk(h)', '0.00', 0, 1, '2018-06-07 16:20:09', '2018-06-07 16:20:09'),
(29, 1, 'lyp', '0.00', 0, 1, '2018-06-07 16:20:09', '2018-06-07 16:20:09'),
(30, 1, 'lyc', '0.00', 0, 1, '2018-06-07 16:20:09', '2018-06-07 16:20:09'),
(31, 1, 'a(h)', '0.00', 0, 1, '2018-06-07 16:20:09', '2018-06-07 16:20:09'),
(32, 1, 'a(1.5)', '0.00', 0, 1, '2018-06-07 16:20:09', '2018-06-07 16:20:09'),
(33, 2, 'tp', '1.00', 0, 1, '2018-06-11 15:03:44', '2018-06-11 15:03:44'),
(34, 2, 'tc', '0.00', 0, 1, '2018-06-11 15:03:44', '2018-06-11 15:03:44'),
(35, 2, 'bhp', '0.00', 0, 1, '2018-06-11 15:03:44', '2018-06-11 15:03:44'),
(36, 2, 'bhc', '0.00', 0, 1, '2018-06-11 15:03:44', '2018-06-11 15:03:44'),
(37, 2, 'fp', '0.00', 0, 1, '2018-06-11 15:03:44', '2018-06-11 15:03:44'),
(38, 2, 'fc', '0.00', 0, 1, '2018-06-11 15:03:44', '2018-06-11 15:03:44'),
(39, 2, 'f(h)', '3.00', 0, 1, '2018-06-11 15:03:44', '2018-06-11 15:03:44'),
(40, 2, 'f(1)', '0.00', 0, 1, '2018-06-11 15:03:44', '2018-06-11 15:03:44'),
(41, 2, 'f(2)', '0.00', 0, 1, '2018-06-11 15:03:44', '2018-06-11 15:03:44'),
(42, 2, 'ucp', '0.00', 0, 1, '2018-06-11 15:03:44', '2018-06-11 15:03:44'),
(43, 2, 'ucc', '12.00', 0, 1, '2018-06-11 15:03:44', '2018-06-11 15:03:44'),
(44, 2, 'uc(h)', '0.00', 0, 1, '2018-06-11 15:03:44', '2018-06-11 15:03:44'),
(45, 2, 'ulp', '0.00', 0, 1, '2018-06-11 15:03:44', '2018-06-11 15:03:44'),
(46, 2, 'ulc', '0.00', 0, 1, '2018-06-11 15:03:44', '2018-06-11 15:03:44'),
(47, 2, 'ul(h)', '0.00', 0, 1, '2018-06-11 15:03:44', '2018-06-11 15:03:44'),
(48, 2, 'uop', '0.00', 0, 1, '2018-06-11 15:03:44', '2018-06-11 15:03:44'),
(49, 2, 'uoc', '0.00', 0, 1, '2018-06-11 15:03:44', '2018-06-11 15:03:44'),
(50, 2, 'uo(h)', '0.00', 0, 1, '2018-06-11 15:03:44', '2018-06-11 15:03:44'),
(51, 2, 'uo(1)', '0.00', 0, 1, '2018-06-11 15:03:44', '2018-06-11 15:03:44'),
(52, 2, 'uz', '0.00', 0, 1, '2018-06-11 15:03:44', '2018-06-11 15:03:44'),
(53, 2, 'ornj', '0.00', 0, 1, '2018-06-11 15:03:44', '2018-06-11 15:03:44'),
(54, 2, 'lmnj', '0.00', 0, 1, '2018-06-11 15:03:44', '2018-06-11 15:03:44'),
(55, 2, 'm', '0.00', 0, 1, '2018-06-11 15:03:44', '2018-06-11 15:03:44'),
(56, 2, 'm(h)', '0.00', 0, 1, '2018-06-11 15:03:44', '2018-06-11 15:03:44'),
(57, 2, 'm(1)', '0.00', 0, 1, '2018-06-11 15:03:44', '2018-06-11 15:03:44'),
(58, 2, 'mkp', '0.00', 0, 1, '2018-06-11 15:03:44', '2018-06-11 15:03:44'),
(59, 2, 'mkt', '0.00', 0, 1, '2018-06-11 15:03:44', '2018-06-11 15:03:44'),
(60, 2, 'mk(h)', '0.00', 0, 1, '2018-06-11 15:03:44', '2018-06-11 15:03:44'),
(61, 2, 'lyp', '0.00', 0, 1, '2018-06-11 15:03:44', '2018-06-11 15:03:44'),
(62, 2, 'lyc', '0.00', 0, 1, '2018-06-11 15:03:44', '2018-06-11 15:03:44'),
(63, 2, 'a(h)', '0.00', 0, 1, '2018-06-11 15:03:44', '2018-06-11 15:03:44'),
(64, 2, 'a(1.5)', '0.00', 0, 1, '2018-06-11 15:03:44', '2018-06-11 15:03:44'),
(65, 3, 'tp', '1.00', 0, 1, '2018-06-11 15:22:14', '2018-06-11 15:22:14'),
(66, 3, 'tc', '0.00', 0, 1, '2018-06-11 15:22:14', '2018-06-11 15:22:14'),
(67, 3, 'bhp', '0.00', 0, 1, '2018-06-11 15:22:14', '2018-06-11 15:22:14'),
(68, 3, 'bhc', '0.00', 0, 1, '2018-06-11 15:22:14', '2018-06-11 15:22:14'),
(69, 3, 'fp', '0.00', 0, 1, '2018-06-11 15:22:14', '2018-06-11 15:22:14'),
(70, 3, 'fc', '0.00', 0, 1, '2018-06-11 15:22:14', '2018-06-11 15:22:14'),
(71, 3, 'f(h)', '3.00', 0, 1, '2018-06-11 15:22:14', '2018-06-11 15:22:14'),
(72, 3, 'f(1)', '0.00', 0, 1, '2018-06-11 15:22:14', '2018-06-11 15:22:14'),
(73, 3, 'f(2)', '0.00', 0, 1, '2018-06-11 15:22:14', '2018-06-11 15:22:14'),
(74, 3, 'ucp', '0.00', 0, 1, '2018-06-11 15:22:14', '2018-06-11 15:22:14'),
(75, 3, 'ucc', '12.00', 0, 1, '2018-06-11 15:22:14', '2018-06-11 15:22:14'),
(76, 3, 'uc(h)', '0.00', 0, 1, '2018-06-11 15:22:14', '2018-06-11 15:22:14'),
(77, 3, 'ulp', '0.00', 0, 1, '2018-06-11 15:22:14', '2018-06-11 15:22:14'),
(78, 3, 'ulc', '0.00', 0, 1, '2018-06-11 15:22:14', '2018-06-11 15:22:14'),
(79, 3, 'ul(h)', '0.00', 0, 1, '2018-06-11 15:22:14', '2018-06-11 15:22:14'),
(80, 3, 'uop', '0.00', 0, 1, '2018-06-11 15:22:14', '2018-06-11 15:22:14'),
(81, 3, 'uoc', '0.00', 0, 1, '2018-06-11 15:22:14', '2018-06-11 15:22:14'),
(82, 3, 'uo(h)', '0.00', 0, 1, '2018-06-11 15:22:14', '2018-06-11 15:22:14'),
(83, 3, 'uo(1)', '0.00', 0, 1, '2018-06-11 15:22:14', '2018-06-11 15:22:14'),
(84, 3, 'uz', '0.00', 0, 1, '2018-06-11 15:22:14', '2018-06-11 15:22:14'),
(85, 3, 'ornj', '0.00', 0, 1, '2018-06-11 15:22:14', '2018-06-11 15:22:14'),
(86, 3, 'lmnj', '0.00', 0, 1, '2018-06-11 15:22:14', '2018-06-11 15:22:14'),
(87, 3, 'm', '0.00', 0, 1, '2018-06-11 15:22:14', '2018-06-11 15:22:14'),
(88, 3, 'm(h)', '0.00', 0, 1, '2018-06-11 15:22:14', '2018-06-11 15:22:14'),
(89, 3, 'm(1)', '0.00', 0, 1, '2018-06-11 15:22:14', '2018-06-11 15:22:14'),
(90, 3, 'mkp', '0.00', 0, 1, '2018-06-11 15:22:14', '2018-06-11 15:22:14'),
(91, 3, 'mkt', '0.00', 0, 1, '2018-06-11 15:22:14', '2018-06-11 15:22:14'),
(92, 3, 'mk(h)', '0.00', 0, 1, '2018-06-11 15:22:14', '2018-06-11 15:22:14'),
(93, 3, 'lyp', '0.00', 0, 1, '2018-06-11 15:22:14', '2018-06-11 15:22:14'),
(94, 3, 'lyc', '0.00', 0, 1, '2018-06-11 15:22:14', '2018-06-11 15:22:14'),
(95, 3, 'a(h)', '0.00', 0, 1, '2018-06-11 15:22:14', '2018-06-11 15:22:14'),
(96, 3, 'a(1.5)', '0.00', 0, 1, '2018-06-11 15:22:14', '2018-06-11 15:22:14'),
(97, 4, 'tp', '1.00', 0, 1, '2018-06-11 15:23:49', '2018-06-11 15:23:49'),
(98, 4, 'tc', '0.00', 0, 1, '2018-06-11 15:23:49', '2018-06-11 15:23:49'),
(99, 4, 'bhp', '0.00', 0, 1, '2018-06-11 15:23:49', '2018-06-11 15:23:49'),
(100, 4, 'bhc', '0.00', 0, 1, '2018-06-11 15:23:49', '2018-06-11 15:23:49'),
(101, 4, 'fp', '0.00', 0, 1, '2018-06-11 15:23:49', '2018-06-11 15:23:49'),
(102, 4, 'fc', '0.00', 0, 1, '2018-06-11 15:23:49', '2018-06-11 15:23:49'),
(103, 4, 'f(h)', '3.00', 0, 1, '2018-06-11 15:23:49', '2018-06-11 15:23:49'),
(104, 4, 'f(1)', '0.00', 0, 1, '2018-06-11 15:23:49', '2018-06-11 15:23:49'),
(105, 4, 'f(2)', '0.00', 0, 1, '2018-06-11 15:23:49', '2018-06-11 15:23:49'),
(106, 4, 'ucp', '0.00', 0, 1, '2018-06-11 15:23:49', '2018-06-11 15:23:49'),
(107, 4, 'ucc', '12.00', 0, 1, '2018-06-11 15:23:49', '2018-06-11 15:23:49'),
(108, 4, 'uc(h)', '0.00', 0, 1, '2018-06-11 15:23:49', '2018-06-11 15:23:49'),
(109, 4, 'ulp', '0.00', 0, 1, '2018-06-11 15:23:49', '2018-06-11 15:23:49'),
(110, 4, 'ulc', '0.00', 0, 1, '2018-06-11 15:23:49', '2018-06-11 15:23:49'),
(111, 4, 'ul(h)', '0.00', 0, 1, '2018-06-11 15:23:49', '2018-06-11 15:23:49'),
(112, 4, 'uop', '0.00', 0, 1, '2018-06-11 15:23:49', '2018-06-11 15:23:49'),
(113, 4, 'uoc', '0.00', 0, 1, '2018-06-11 15:23:49', '2018-06-11 15:23:49'),
(114, 4, 'uo(h)', '0.00', 0, 1, '2018-06-11 15:23:49', '2018-06-11 15:23:49'),
(115, 4, 'uo(1)', '0.00', 0, 1, '2018-06-11 15:23:49', '2018-06-11 15:23:49'),
(116, 4, 'uz', '0.00', 0, 1, '2018-06-11 15:23:49', '2018-06-11 15:23:49'),
(117, 4, 'ornj', '0.00', 0, 1, '2018-06-11 15:23:49', '2018-06-11 15:23:49'),
(118, 4, 'lmnj', '0.00', 0, 1, '2018-06-11 15:23:49', '2018-06-11 15:23:49'),
(119, 4, 'm', '0.00', 0, 1, '2018-06-11 15:23:49', '2018-06-11 15:23:49'),
(120, 4, 'm(h)', '0.00', 0, 1, '2018-06-11 15:23:49', '2018-06-11 15:23:49'),
(121, 4, 'm(1)', '0.00', 0, 1, '2018-06-11 15:23:49', '2018-06-11 15:23:49'),
(122, 4, 'mkp', '0.00', 0, 1, '2018-06-11 15:23:49', '2018-06-11 15:23:49'),
(123, 4, 'mkt', '0.00', 0, 1, '2018-06-11 15:23:49', '2018-06-11 15:23:49'),
(124, 4, 'mk(h)', '0.00', 0, 1, '2018-06-11 15:23:49', '2018-06-11 15:23:49'),
(125, 4, 'lyp', '0.00', 0, 1, '2018-06-11 15:23:49', '2018-06-11 15:23:49'),
(126, 4, 'lyc', '0.00', 0, 1, '2018-06-11 15:23:49', '2018-06-11 15:23:49'),
(127, 4, 'a(h)', '0.00', 0, 1, '2018-06-11 15:23:49', '2018-06-11 15:23:49'),
(128, 4, 'a(1.5)', '0.00', 0, 1, '2018-06-11 15:23:49', '2018-06-11 15:23:49'),
(129, 5, 'package1', '1.00', 0, 1, '2018-06-12 13:09:26', '2018-06-12 13:09:26'),
(130, 5, 'pakcage2', '12.00', 13, 1, '2018-06-12 13:09:26', '2018-06-12 13:09:26'),
(131, 6, 'tp', '1.00', 0, 1, '2018-06-23 12:23:15', '2018-06-23 12:23:15'),
(132, 6, 'tc', '0.00', 0, 1, '2018-06-23 12:23:15', '2018-06-23 12:23:15'),
(133, 6, 'bhp', '0.00', 0, 1, '2018-06-23 12:23:15', '2018-06-23 12:23:15'),
(134, 6, 'bhc', '0.00', 0, 1, '2018-06-23 12:23:15', '2018-06-23 12:23:15'),
(135, 6, 'fp', '0.00', 0, 1, '2018-06-23 12:23:15', '2018-06-23 12:23:15'),
(136, 6, 'fc', '0.00', 0, 1, '2018-06-23 12:23:15', '2018-06-23 12:23:15'),
(137, 6, 'f(h)', '3.00', 0, 1, '2018-06-23 12:23:15', '2018-06-23 12:23:15'),
(138, 6, 'f(1)', '0.00', 0, 1, '2018-06-23 12:23:15', '2018-06-23 12:23:15'),
(139, 6, 'f(2)', '0.00', 0, 1, '2018-06-23 12:23:15', '2018-06-23 12:23:15'),
(140, 6, 'ucp', '0.00', 0, 1, '2018-06-23 12:23:15', '2018-06-23 12:23:15'),
(141, 6, 'ucc', '12.00', 0, 1, '2018-06-23 12:23:15', '2018-06-23 12:23:15'),
(142, 6, 'uc(h)', '0.00', 0, 1, '2018-06-23 12:23:15', '2018-06-23 12:23:15'),
(143, 6, 'ulp', '0.00', 0, 1, '2018-06-23 12:23:15', '2018-06-23 12:23:15'),
(144, 6, 'ulc', '0.00', 0, 1, '2018-06-23 12:23:15', '2018-06-23 12:23:15'),
(145, 6, 'ul(h)', '0.00', 0, 1, '2018-06-23 12:23:15', '2018-06-23 12:23:15'),
(146, 6, 'uop', '0.00', 0, 1, '2018-06-23 12:23:15', '2018-06-23 12:23:15'),
(147, 6, 'uoc', '0.00', 0, 1, '2018-06-23 12:23:15', '2018-06-23 12:23:15'),
(148, 6, 'uo(h)', '0.00', 0, 1, '2018-06-23 12:23:15', '2018-06-23 12:23:15'),
(149, 6, 'uo(1)', '0.00', 0, 1, '2018-06-23 12:23:15', '2018-06-23 12:23:15'),
(150, 6, 'uz', '0.00', 0, 1, '2018-06-23 12:23:15', '2018-06-23 12:23:15'),
(151, 6, 'ornj', '0.00', 0, 1, '2018-06-23 12:23:15', '2018-06-23 12:23:15'),
(152, 6, 'lmnj', '0.00', 0, 1, '2018-06-23 12:23:15', '2018-06-23 12:23:15'),
(153, 6, 'm', '0.00', 0, 1, '2018-06-23 12:23:15', '2018-06-23 12:23:15'),
(154, 6, 'm(h)', '0.00', 0, 1, '2018-06-23 12:23:15', '2018-06-23 12:23:15'),
(155, 6, 'm(1)', '0.00', 0, 1, '2018-06-23 12:23:15', '2018-06-23 12:23:15'),
(156, 6, 'mkp', '0.00', 0, 1, '2018-06-23 12:23:15', '2018-06-23 12:23:15'),
(157, 6, 'mkt', '0.00', 0, 1, '2018-06-23 12:23:15', '2018-06-23 12:23:15'),
(158, 6, 'mk(h)', '0.00', 0, 1, '2018-06-23 12:23:15', '2018-06-23 12:23:15'),
(159, 6, 'lyp', '0.00', 0, 1, '2018-06-23 12:23:15', '2018-06-23 12:23:15'),
(160, 6, 'lyc', '0.00', 0, 1, '2018-06-23 12:23:15', '2018-06-23 12:23:15'),
(161, 6, 'a(h)', '0.00', 0, 1, '2018-06-23 12:23:15', '2018-06-23 12:23:15'),
(162, 6, 'a(1.5)', '0.00', 0, 1, '2018-06-23 12:23:15', '2018-06-23 12:23:15'),
(163, 7, 'package1', '1.00', 0, 1, '2018-06-23 12:23:18', '2018-06-23 12:23:18'),
(164, 7, 'pakcage2', '12.00', 13, 1, '2018-06-23 12:23:18', '2018-06-23 12:23:18'),
(165, 8, 'tp', '1.00', 0, 1, '2018-06-23 16:17:09', '2018-06-23 16:17:09'),
(166, 8, 'tc', '0.00', 0, 1, '2018-06-23 16:17:09', '2018-06-23 16:17:09'),
(167, 8, 'bhp', '0.00', 0, 1, '2018-06-23 16:17:09', '2018-06-23 16:17:09'),
(168, 8, 'bhc', '0.00', 0, 1, '2018-06-23 16:17:09', '2018-06-23 16:17:09'),
(169, 8, 'fp', '0.00', 0, 1, '2018-06-23 16:17:09', '2018-06-23 16:17:09'),
(170, 8, 'fc', '0.00', 0, 1, '2018-06-23 16:17:09', '2018-06-23 16:17:09'),
(171, 8, 'f(h)', '3.00', 0, 1, '2018-06-23 16:17:09', '2018-06-23 16:17:09'),
(172, 8, 'f(1)', '0.00', 0, 1, '2018-06-23 16:17:09', '2018-06-23 16:17:09'),
(173, 8, 'f(2)', '0.00', 0, 1, '2018-06-23 16:17:09', '2018-06-23 16:17:09'),
(174, 8, 'ucp', '0.00', 0, 1, '2018-06-23 16:17:09', '2018-06-23 16:17:09'),
(175, 8, 'ucc', '12.00', 0, 1, '2018-06-23 16:17:09', '2018-06-23 16:17:09'),
(176, 8, 'uc(h)', '0.00', 0, 1, '2018-06-23 16:17:09', '2018-06-23 16:17:09'),
(177, 8, 'ulp', '0.00', 0, 1, '2018-06-23 16:17:09', '2018-06-23 16:17:09'),
(178, 8, 'ulc', '0.00', 0, 1, '2018-06-23 16:17:09', '2018-06-23 16:17:09'),
(179, 8, 'ul(h)', '0.00', 0, 1, '2018-06-23 16:17:09', '2018-06-23 16:17:09'),
(180, 8, 'uop', '0.00', 0, 1, '2018-06-23 16:17:09', '2018-06-23 16:17:09'),
(181, 8, 'uoc', '0.00', 0, 1, '2018-06-23 16:17:09', '2018-06-23 16:17:09'),
(182, 8, 'uo(h)', '0.00', 0, 1, '2018-06-23 16:17:09', '2018-06-23 16:17:09'),
(183, 8, 'uo(1)', '0.00', 0, 1, '2018-06-23 16:17:09', '2018-06-23 16:17:09'),
(184, 8, 'uz', '0.00', 0, 1, '2018-06-23 16:17:09', '2018-06-23 16:17:09'),
(185, 8, 'ornj', '0.00', 0, 1, '2018-06-23 16:17:09', '2018-06-23 16:17:09'),
(186, 8, 'lmnj', '0.00', 0, 1, '2018-06-23 16:17:09', '2018-06-23 16:17:09'),
(187, 8, 'm', '0.00', 0, 1, '2018-06-23 16:17:09', '2018-06-23 16:17:09'),
(188, 8, 'm(h)', '0.00', 0, 1, '2018-06-23 16:17:09', '2018-06-23 16:17:09'),
(189, 8, 'm(1)', '0.00', 0, 1, '2018-06-23 16:17:09', '2018-06-23 16:17:09'),
(190, 8, 'mkp', '0.00', 0, 1, '2018-06-23 16:17:09', '2018-06-23 16:17:09'),
(191, 8, 'mkt', '0.00', 0, 1, '2018-06-23 16:17:09', '2018-06-23 16:17:09'),
(192, 8, 'mk(h)', '0.00', 0, 1, '2018-06-23 16:17:09', '2018-06-23 16:17:09'),
(193, 8, 'lyp', '0.00', 0, 1, '2018-06-23 16:17:09', '2018-06-23 16:17:09'),
(194, 8, 'lyc', '0.00', 0, 1, '2018-06-23 16:17:09', '2018-06-23 16:17:09'),
(195, 8, 'a(h)', '0.00', 0, 1, '2018-06-23 16:17:09', '2018-06-23 16:17:09'),
(196, 8, 'a(1.5)', '0.00', 0, 1, '2018-06-23 16:17:09', '2018-06-23 16:17:09');

-- --------------------------------------------------------

--
-- Table structure for table `skues`
--

DROP TABLE IF EXISTS `skues`;
CREATE TABLE IF NOT EXISTS `skues` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `sku_name` varchar(255) NOT NULL,
  `short_name` varchar(255) DEFAULT NULL,
  `description` text,
  `rfu1` varchar(255) DEFAULT NULL,
  `rfu2` varchar(255) DEFAULT NULL,
  `created_by` int(10) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_by` int(10) DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  `is_active` tinyint(1) DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=32 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `skues`
--

INSERT INTO `skues` (`id`, `sku_name`, `short_name`, `description`, `rfu1`, `rfu2`, `created_by`, `created_at`, `updated_by`, `updated_at`, `is_active`) VALUES
(1, '250ml pet', NULL, NULL, NULL, NULL, NULL, '2018-05-30 09:11:52', NULL, NULL, 1),
(2, '250ml can', NULL, NULL, NULL, NULL, NULL, '2018-05-30 09:11:53', NULL, NULL, 1),
(3, '250ml pet', NULL, NULL, NULL, NULL, NULL, '2018-05-30 09:11:53', NULL, NULL, 1),
(4, '250ml can', NULL, NULL, NULL, NULL, NULL, '2018-05-30 09:11:53', NULL, NULL, 1),
(5, '250ml pet', NULL, NULL, NULL, NULL, NULL, '2018-05-30 09:11:53', NULL, NULL, 1),
(6, '250ml can', NULL, NULL, NULL, NULL, NULL, '2018-05-30 09:11:53', NULL, NULL, 1),
(7, '500ml', NULL, NULL, NULL, NULL, NULL, '2018-05-30 09:11:53', NULL, NULL, 1),
(8, '1L', NULL, NULL, NULL, NULL, NULL, '2018-05-30 09:11:53', NULL, NULL, 1),
(9, '2L', NULL, NULL, NULL, NULL, NULL, '2018-05-30 09:11:53', NULL, NULL, 1),
(10, '250ml pet', NULL, NULL, NULL, NULL, NULL, '2018-05-30 09:11:53', NULL, NULL, 1),
(11, '250ml can', NULL, NULL, NULL, NULL, NULL, '2018-05-30 09:11:53', NULL, NULL, 1),
(12, '500ml', NULL, NULL, NULL, NULL, NULL, '2018-05-30 09:11:53', NULL, NULL, 1),
(13, '250ml pet', NULL, NULL, NULL, NULL, NULL, '2018-05-30 09:11:53', NULL, NULL, 1),
(14, '250ml can', NULL, NULL, NULL, NULL, NULL, '2018-05-30 09:11:53', NULL, NULL, 1),
(15, '500ml', NULL, NULL, NULL, NULL, NULL, '2018-05-30 09:11:53', NULL, NULL, 1),
(16, '250ml pet', NULL, NULL, NULL, NULL, NULL, '2018-05-30 09:11:53', NULL, NULL, 1),
(17, '250ml can', NULL, NULL, NULL, NULL, NULL, '2018-05-30 09:11:53', NULL, NULL, 1),
(18, '500ml', NULL, NULL, NULL, NULL, NULL, '2018-05-30 09:11:53', NULL, NULL, 1),
(19, '1 L', NULL, NULL, NULL, NULL, NULL, '2018-05-30 09:11:53', NULL, NULL, 1),
(20, '250ml pet', NULL, NULL, NULL, NULL, NULL, '2018-05-30 09:11:53', NULL, NULL, 1),
(21, '250ml pet', NULL, NULL, NULL, NULL, NULL, '2018-05-30 09:11:53', NULL, NULL, 1),
(22, '250ml pet', NULL, NULL, NULL, NULL, NULL, '2018-05-30 09:11:53', NULL, NULL, 1),
(23, '250ml', NULL, NULL, NULL, NULL, NULL, '2018-05-30 09:11:53', NULL, NULL, 1),
(24, '500ml', NULL, NULL, NULL, NULL, NULL, '2018-05-30 09:11:53', NULL, NULL, 1),
(25, '1 L', NULL, NULL, NULL, NULL, NULL, '2018-05-30 09:11:53', NULL, NULL, 1),
(26, '250ml pet', NULL, NULL, NULL, NULL, NULL, '2018-05-30 09:11:53', NULL, NULL, 1),
(27, '250ml Tetra', NULL, NULL, NULL, NULL, NULL, '2018-05-30 09:11:53', NULL, NULL, 1),
(28, '300ml pet', NULL, NULL, NULL, NULL, NULL, '2018-05-30 09:11:53', NULL, NULL, 1),
(29, '250ml can', NULL, NULL, NULL, NULL, NULL, '2018-05-30 09:11:53', NULL, NULL, 1),
(30, '500 ml', NULL, NULL, NULL, NULL, NULL, '2018-05-30 09:11:53', NULL, NULL, 1),
(31, '1.5 L', NULL, NULL, NULL, NULL, NULL, '2018-05-30 09:11:53', NULL, NULL, 1);

-- --------------------------------------------------------

--
-- Table structure for table `smsinboxes`
--

DROP TABLE IF EXISTS `smsinboxes`;
CREATE TABLE IF NOT EXISTS `smsinboxes` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `sms_inbox_name` varchar(100) DEFAULT NULL,
  `transactionId` varchar(30) DEFAULT NULL,
  `sender` varchar(255) DEFAULT NULL,
  `sms_status` varchar(100) DEFAULT NULL,
  `sms_received` datetime DEFAULT NULL,
  `replied_at` datetime DEFAULT NULL,
  `sms_content` text NOT NULL,
  `created_by` int(10) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `updated_by` int(10) DEFAULT NULL,
  `is_active` tinyint(1) DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=15 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `smsinboxes`
--

INSERT INTO `smsinboxes` (`id`, `sms_inbox_name`, `transactionId`, `sender`, `sms_status`, `sms_received`, `replied_at`, `sms_content`, `created_by`, `created_at`, `updated_at`, `updated_by`, `is_active`) VALUES
(3, 'asdf', NULL, '01915117181', NULL, NULL, NULL, 'sdfsdfsdf', NULL, NULL, '2018-05-28 02:54:18', 1, 1),
(4, 'sdf', NULL, '', NULL, NULL, NULL, '', 1, '2018-05-28 02:44:36', '2018-05-28 02:44:36', 1, 1),
(5, 'sdf', 'asdfsf', '', NULL, NULL, NULL, '', 1, '2018-05-28 02:45:28', '2018-05-28 02:45:28', 1, 1),
(6, NULL, NULL, NULL, NULL, NULL, NULL, 'sdfsdf', 1, '2018-05-28 02:47:16', '2018-05-28 02:52:21', 1, 0),
(7, 'ffffff', 'ffffff', NULL, NULL, NULL, NULL, '', 1, '2018-05-28 02:47:42', '2018-05-28 02:47:42', 1, 1),
(8, 'asdf', 'asdf', NULL, NULL, NULL, NULL, '', 1, '2018-05-28 02:49:39', '2018-05-28 02:49:39', 1, 1),
(9, 'asdf', 'asdf', NULL, NULL, NULL, NULL, '', 1, '2018-05-28 02:50:41', '2018-05-28 02:50:41', 1, 0),
(10, NULL, NULL, NULL, NULL, NULL, NULL, 'sdfs', 1, '2018-05-28 02:52:07', '2018-05-28 02:52:07', NULL, 0),
(11, 'sdf', 'sdfasd', '01915117181', 'asdf', NULL, NULL, 'saf', NULL, '2018-05-28 02:57:38', '2018-05-28 02:57:38', NULL, 0),
(12, NULL, NULL, NULL, NULL, NULL, NULL, 'sdfsdf', NULL, '2018-05-28 02:59:06', '2018-05-28 02:59:06', NULL, 0),
(13, NULL, NULL, NULL, NULL, NULL, NULL, 'sdfasdf', NULL, '2018-05-28 03:07:29', '2018-05-28 03:07:29', NULL, 0),
(14, NULL, NULL, NULL, NULL, NULL, NULL, 'sdf', 1, '2018-05-29 03:27:23', '2018-05-29 03:27:23', NULL, 0);

-- --------------------------------------------------------

--
-- Table structure for table `sms_inboxes`
--

DROP TABLE IF EXISTS `sms_inboxes`;
CREATE TABLE IF NOT EXISTS `sms_inboxes` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `sms_inbox_name` varchar(100) DEFAULT NULL,
  `transactionId` varchar(30) DEFAULT NULL,
  `sender` varchar(15) NOT NULL,
  `sms_content` text NOT NULL,
  `sms_received` datetime DEFAULT NULL,
  `created_by` int(10) DEFAULT NULL,
  `created` timestamp NULL DEFAULT NULL,
  `replied_datetime` datetime DEFAULT NULL,
  `sms_status` enum('Active','Inactive','Pending','Replied','Unread','Processed') DEFAULT NULL,
  `updated` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  `updated_by` int(10) DEFAULT NULL,
  `is_active` tinyint(1) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `U_sms_inbox` (`sender`,`sms_received`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `sms_inboxes`
--

INSERT INTO `sms_inboxes` (`id`, `sms_inbox_name`, `transactionId`, `sender`, `sms_content`, `sms_received`, `created_by`, `created`, `replied_datetime`, `sms_status`, `updated`, `updated_by`, `is_active`) VALUES
(3, NULL, NULL, '01915117181', 'Sale/ASOID-0011/dt-2018-02-17/Tp-001/Tc-000/BHp-000/BHc-000/Fp-000/Fc-000/F(h)-003/F(1)-000/F(2)-000/UCp-000/UCc-012/UC(h)-000/ULp-000/ULc-000/UL(h)-000/UOp-000/UOc-000/UO(h)-000/UO(1)-000/UZ-000/Ornj-000/Lmnj-000/M-000/M(h)-000/M(1)-000/MKp-000/MKt-000/MK(h)-000/LYp-000/Lyc-000/A(h)-000/A(1.5)-000/Total-16', NULL, 0, NULL, NULL, 'Processed', '2018-06-23 10:17:09', NULL, 0),
(4, NULL, NULL, 'sdf', 'sadf', NULL, 0, NULL, NULL, 'Active', '2018-06-03 08:54:38', NULL, 1),
(5, NULL, NULL, '01915117181', 'Primary/asm_rsm_id-0011/dbid-00121/dt-2018-02-17/Tp-001/Tc-000/BHp-000/BHc-000/Fp-000/Fc-000/F(h)-003/F(1)-000/F(2)-000/UCp-000/UCc-012/UC(h)-000/ULp-000/ULc-000/UL(h)-000/UOp-000/UOc-000/UO(h)-000/UO(1)-000/UZ-000/Ornj-000/Lmnj-000/M-000/M(h)-000/M(1)-000/MKp-000/MKt-000/MK(h)-000/LYp-000/Lyc-000/A(h)-000/A(1.5)-000/Total-16/DA-1212', NULL, NULL, NULL, NULL, 'Active', '2018-06-23 11:38:11', NULL, 1),
(6, NULL, NULL, '0171941574', 'Order/ASOID-0011/Dt-2018-02-17/Rt-Banani/OU-10/VO-8/Tp-000,001/Tc-001,01/BHp-000,0/BHc-000,1/Fp-000,1/Fc-000,1/F(h)-000,1/F(1)-000,1/F(2)-000,1/UCp-000,1/UCc-000,1/UC(h)-000,1/ULp-000,1/ULc-000,1/UL(h)-000,1/UOp-000,1/UOc-000,2/UO(h)-000,1/UO(1)-000,2/UZ-000,1/Ornj-000,1/Lmnj-000,1/M-000,1/M(h)-000,1/M(1)-000,1/MKp-000,1/MKt-000,1/MK(h)-000,1/LYp-000,1/Lyc-000,1/A(h)-000,1/A(1.5)-000,1/Total-0001', NULL, NULL, NULL, NULL, 'Active', '2018-06-23 11:37:39', NULL, 1),
(7, NULL, NULL, '01915117181', 'Promotion/ASOID-0000/Dt-2019-01-01/Rt-Banani/Pdn-Package1-01-00,Pakcage2-12-13', NULL, NULL, NULL, NULL, 'Active', '2018-06-23 06:24:03', NULL, 1),
(8, NULL, NULL, '01915117181', 'final test', NULL, NULL, NULL, NULL, 'Active', NULL, NULL, 1);

-- --------------------------------------------------------

--
-- Table structure for table `sms_inboxes28052018`
--

DROP TABLE IF EXISTS `sms_inboxes28052018`;
CREATE TABLE IF NOT EXISTS `sms_inboxes28052018` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `sms_inbox_name` varchar(100) DEFAULT NULL,
  `transactionId` varchar(30) DEFAULT NULL,
  `sms_sender_number` varchar(255) NOT NULL,
  `sms_content` text NOT NULL,
  `sms_received` datetime DEFAULT NULL,
  `replied_datetime` datetime DEFAULT NULL,
  `sms_status` varchar(100) DEFAULT NULL,
  `created_by` int(10) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `updated_by` int(10) DEFAULT NULL,
  `is_active` tinyint(1) DEFAULT '1',
  PRIMARY KEY (`id`),
  UNIQUE KEY `U_sms_inbox` (`sms_sender_number`,`sms_received`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `sms_inboxes28052018`
--

INSERT INTO `sms_inboxes28052018` (`id`, `sms_inbox_name`, `transactionId`, `sms_sender_number`, `sms_content`, `sms_received`, `replied_datetime`, `sms_status`, `created_by`, `created_at`, `updated_at`, `updated_by`, `is_active`) VALUES
(3, '', NULL, '01915117181', 'sdfas', NULL, NULL, NULL, NULL, NULL, '2018-05-28 07:47:19', NULL, 1);

-- --------------------------------------------------------

--
-- Table structure for table `sms_outboxes`
--

DROP TABLE IF EXISTS `sms_outboxes`;
CREATE TABLE IF NOT EXISTS `sms_outboxes` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `sms_reciever_number` varchar(15) NOT NULL,
  `sms_content` text NOT NULL,
  `order_type` enum('order','sale','promotion','primary') DEFAULT NULL,
  `created` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `sent_datetime` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP COMMENT 'When updated',
  `request_from` enum('Mobile App','Broadcast','Web') DEFAULT 'Web',
  `status` enum('Draft','Sent','Pending','Invalid Receiver','Canceled') DEFAULT 'Draft',
  `ref_no` varchar(100) DEFAULT NULL COMMENT 'parcel code',
  `priority` int(2) NOT NULL DEFAULT '1' COMMENT '0 = Very Urgent, 1 = high, 2 = medium, 3 = low, 4 = very low',
  `sms_for` varchar(100) DEFAULT NULL COMMENT 'whom will be receive this sms. this field can be edited only required for sr parcel project',
  `sms_trans_id` varchar(100) DEFAULT NULL,
  `delivery_status` int(5) DEFAULT NULL,
  `reply_from_sms_api` text,
  `retry_count` int(3) NOT NULL DEFAULT '0',
  `next_retry_datetime` datetime DEFAULT NULL,
  `is_delivery_status_updated` int(1) NOT NULL DEFAULT '0' COMMENT '1 = updated; 0 = not update yet',
  `final_reply_from_sms_api` text,
  `final_delivery_status` varchar(100) DEFAULT NULL,
  `final_delivery_datetime` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `del_st` (`delivery_status`),
  KEY `mobile` (`sms_reciever_number`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `sms_outboxes`
--

INSERT INTO `sms_outboxes` (`id`, `sms_reciever_number`, `sms_content`, `order_type`, `created`, `sent_datetime`, `request_from`, `status`, `ref_no`, `priority`, `sms_for`, `sms_trans_id`, `delivery_status`, `reply_from_sms_api`, `retry_count`, `next_retry_datetime`, `is_delivery_status_updated`, `final_reply_from_sms_api`, `final_delivery_status`, `final_delivery_datetime`) VALUES
(1, '0171941574', 'Invalid SMS Format !!', NULL, '2018-06-23 11:30:49', NULL, 'Web', 'Draft', NULL, 3, NULL, NULL, NULL, NULL, 0, NULL, 0, NULL, NULL, NULL),
(2, '0171941574', 'Invalid SMS Format !!', 'order', '2018-06-23 11:34:59', NULL, 'Web', 'Draft', NULL, 3, NULL, NULL, NULL, NULL, 0, NULL, 0, NULL, NULL, NULL),
(3, '01915117181', 'Invalid SMS Format !!', 'primary', '2018-06-23 11:37:50', NULL, 'Web', 'Draft', NULL, 3, NULL, NULL, NULL, NULL, 0, NULL, 0, NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `territories`
--

DROP TABLE IF EXISTS `territories`;
CREATE TABLE IF NOT EXISTS `territories` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `territory_name` varchar(255) NOT NULL,
  `ordering` int(11) DEFAULT NULL,
  `created_by` int(11) NOT NULL,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  `updated_by` int(11) DEFAULT NULL,
  `updated_at` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `rfu1` varchar(255) DEFAULT NULL,
  `rfu2` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=143 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `territories`
--

INSERT INTO `territories` (`id`, `territory_name`, `ordering`, `created_by`, `created_at`, `updated_by`, `updated_at`, `rfu1`, `rfu2`) VALUES
(1, 'Dinajpur', 1, 1, '2018-05-30 14:10:56', NULL, '2018-05-30 14:10:56', NULL, NULL),
(2, 'Thakurgaon', 2, 1, '2018-05-30 14:10:56', NULL, '2018-05-30 14:10:56', NULL, NULL),
(3, 'Rangpur', 3, 1, '2018-05-30 14:10:56', NULL, '2018-05-30 14:10:56', NULL, NULL),
(4, 'Lalmonirhat', 4, 1, '2018-05-30 14:10:56', NULL, '2018-05-30 14:10:56', NULL, NULL),
(5, 'Joypurhat', 5, 1, '2018-05-30 14:10:56', NULL, '2018-05-30 14:10:56', NULL, NULL),
(6, 'Gaibandah', 6, 1, '2018-05-30 14:10:56', NULL, '2018-05-30 14:10:56', NULL, NULL),
(7, 'Bogura Sadar ', 7, 1, '2018-05-30 14:10:56', NULL, '2018-05-30 14:10:56', NULL, NULL),
(8, 'Mohastangar', 8, 1, '2018-05-30 14:10:56', NULL, '2018-05-30 14:10:56', NULL, NULL),
(9, 'Rajshahi Sadar', 9, 1, '2018-05-30 14:10:56', NULL, '2018-05-30 14:10:56', NULL, NULL),
(10, 'Chapai Nawabgonj', 10, 1, '2018-05-30 14:10:56', NULL, '2018-05-30 14:10:56', NULL, NULL),
(11, 'Naogoan Sadar', 11, 1, '2018-05-30 14:10:56', NULL, '2018-05-30 14:10:56', NULL, NULL),
(12, 'Natore Sadar', 12, 1, '2018-05-30 14:10:56', NULL, '2018-05-30 14:10:56', NULL, NULL),
(13, 'Pabna 1', 13, 1, '2018-05-30 14:10:56', NULL, '2018-05-30 14:10:56', NULL, NULL),
(14, 'Pabna 2', 14, 1, '2018-05-30 14:10:56', NULL, '2018-05-30 14:10:56', NULL, NULL),
(15, 'Sirajgonj Road (Solonga )', 15, 1, '2018-05-30 14:10:56', NULL, '2018-05-30 14:10:56', NULL, NULL),
(16, 'Ullapara', 16, 1, '2018-05-30 14:10:56', NULL, '2018-05-30 14:10:56', NULL, NULL),
(17, 'Kushtia', 17, 1, '2018-05-30 14:10:56', NULL, '2018-05-30 14:10:56', NULL, NULL),
(18, 'Mirpur', 18, 1, '2018-05-30 14:10:56', NULL, '2018-05-30 14:10:56', NULL, NULL),
(19, 'Gangni', 19, 1, '2018-05-30 14:10:56', NULL, '2018-05-30 14:10:56', NULL, NULL),
(20, 'Jhenaidah', 20, 1, '2018-05-30 14:10:56', NULL, '2018-05-30 14:10:56', NULL, NULL),
(21, 'Kumarkhali', 21, 1, '2018-05-30 14:10:56', NULL, '2018-05-30 14:10:56', NULL, NULL),
(22, 'Chuadanga', 22, 1, '2018-05-30 14:10:56', NULL, '2018-05-30 14:10:56', NULL, NULL),
(23, 'Jessore', 23, 1, '2018-05-30 14:10:56', NULL, '2018-05-30 14:10:56', NULL, NULL),
(24, 'Jhikorgacha', 24, 1, '2018-05-30 14:10:56', NULL, '2018-05-30 14:10:56', NULL, NULL),
(25, 'Magura', 25, 1, '2018-05-30 14:10:56', NULL, '2018-05-30 14:10:56', NULL, NULL),
(26, 'Narail', 26, 1, '2018-05-30 14:10:56', NULL, '2018-05-30 14:10:56', NULL, NULL),
(27, 'Khulna', 27, 1, '2018-05-30 14:10:56', NULL, '2018-05-30 14:10:56', NULL, NULL),
(28, 'Lockpur/ East Rupsha', 28, 1, '2018-05-30 14:10:56', NULL, '2018-05-30 14:10:56', NULL, NULL),
(29, 'Dawlatpur', 29, 1, '2018-05-30 14:10:56', NULL, '2018-05-30 14:10:56', NULL, NULL),
(30, 'Satkhira', 30, 1, '2018-05-30 14:10:56', NULL, '2018-05-30 14:10:56', NULL, NULL),
(31, 'Chuknagar', 31, 1, '2018-05-30 14:10:56', NULL, '2018-05-30 14:10:56', NULL, NULL),
(32, 'Bagerhat', 32, 1, '2018-05-30 14:10:56', NULL, '2018-05-30 14:10:56', NULL, NULL),
(33, 'Pirozpur', 33, 1, '2018-05-30 14:10:56', NULL, '2018-05-30 14:10:56', NULL, NULL),
(34, 'Gopalgonj', 34, 1, '2018-05-30 14:10:56', NULL, '2018-05-30 14:10:56', NULL, NULL),
(35, 'Mymensingh Sadar', 35, 1, '2018-05-30 14:10:56', NULL, '2018-05-30 14:10:56', NULL, NULL),
(36, 'Bhaluka', 36, 1, '2018-05-30 14:10:56', NULL, '2018-05-30 14:10:56', NULL, NULL),
(37, 'Jamalpur', 37, 1, '2018-05-30 14:10:56', NULL, '2018-05-30 14:10:56', NULL, NULL),
(38, 'Sherpur', 38, 1, '2018-05-30 14:10:56', NULL, '2018-05-30 14:10:56', NULL, NULL),
(39, 'Kotiadi', 39, 1, '2018-05-30 14:10:56', NULL, '2018-05-30 14:10:56', NULL, NULL),
(40, 'Netrokona ', 40, 1, '2018-05-30 14:10:56', NULL, '2018-05-30 14:10:56', NULL, NULL),
(41, ' Kishorgonj ', 41, 1, '2018-05-30 14:10:56', NULL, '2018-05-30 14:10:56', NULL, NULL),
(42, 'Tongi', 42, 1, '2018-05-30 14:10:56', NULL, '2018-05-30 14:10:56', NULL, NULL),
(43, 'Uttara', 43, 1, '2018-05-30 14:10:56', NULL, '2018-05-30 14:10:56', NULL, NULL),
(44, 'Dakhin Khan', 44, 1, '2018-05-30 14:10:56', NULL, '2018-05-30 14:10:56', NULL, NULL),
(45, 'Board Bazar', 45, 1, '2018-05-30 14:10:56', NULL, '2018-05-30 14:10:56', NULL, NULL),
(46, 'Gazipur', 46, 1, '2018-05-30 14:10:56', NULL, '2018-05-30 14:10:56', NULL, NULL),
(47, 'Kapasia', 47, 1, '2018-05-30 14:10:56', NULL, '2018-05-30 14:10:56', NULL, NULL),
(48, 'Hotapara', 48, 1, '2018-05-30 14:10:56', NULL, '2018-05-30 14:10:56', NULL, NULL),
(49, 'Mawna', 49, 1, '2018-05-30 14:10:56', NULL, '2018-05-30 14:10:56', NULL, NULL),
(50, 'Konabari', 50, 1, '2018-05-30 14:10:56', NULL, '2018-05-30 14:10:56', NULL, NULL),
(51, 'Kaliakoir', 51, 1, '2018-05-30 14:10:56', NULL, '2018-05-30 14:10:56', NULL, NULL),
(52, 'Narshingdi', 52, 1, '2018-05-30 14:10:56', NULL, '2018-05-30 14:10:56', NULL, NULL),
(53, 'Bhairob', 53, 1, '2018-05-30 14:10:56', NULL, '2018-05-30 14:10:56', NULL, NULL),
(54, 'Rupgonj', 54, 1, '2018-05-30 14:10:56', NULL, '2018-05-30 14:10:56', NULL, NULL),
(55, 'Gulshan', 55, 1, '2018-05-30 14:10:56', NULL, '2018-05-30 14:10:56', NULL, NULL),
(56, 'Tejgaon', 56, 1, '2018-05-30 14:10:56', NULL, '2018-05-30 14:10:56', NULL, NULL),
(57, 'Badda', 57, 1, '2018-05-30 14:10:56', NULL, '2018-05-30 14:10:56', NULL, NULL),
(58, 'Lalbag', 58, 1, '2018-05-30 14:10:56', NULL, '2018-05-30 14:10:56', NULL, NULL),
(59, 'Mohammadpur', 59, 1, '2018-05-30 14:10:56', NULL, '2018-05-30 14:10:56', NULL, NULL),
(60, 'Farmgate', 60, 1, '2018-05-30 14:10:56', NULL, '2018-05-30 14:10:56', NULL, NULL),
(61, 'Jatrabari', 61, 1, '2018-05-30 14:10:56', NULL, '2018-05-30 14:10:56', NULL, NULL),
(62, 'Demra', 62, 1, '2018-05-30 14:10:56', NULL, '2018-05-30 14:10:56', NULL, NULL),
(63, 'Sutrapur', 63, 1, '2018-05-30 14:10:56', NULL, '2018-05-30 14:10:56', NULL, NULL),
(64, 'Sabujbagh', 64, 1, '2018-05-30 14:10:56', NULL, '2018-05-30 14:10:56', NULL, NULL),
(65, 'Narayangonj', 65, 1, '2018-05-30 14:10:56', NULL, '2018-05-30 14:10:56', NULL, NULL),
(66, 'Bhuygor', 66, 1, '2018-05-30 14:10:56', NULL, '2018-05-30 14:10:56', NULL, NULL),
(67, 'Sonargaon', 67, 1, '2018-05-30 14:10:56', NULL, '2018-05-30 14:10:56', NULL, NULL),
(68, 'Zinzira', 68, 1, '2018-05-30 14:10:56', NULL, '2018-05-30 14:10:56', NULL, NULL),
(69, 'Dohar', 69, 1, '2018-05-30 14:10:56', NULL, '2018-05-30 14:10:56', NULL, NULL),
(70, 'Munsigonj', 70, 1, '2018-05-30 14:10:56', NULL, '2018-05-30 14:10:56', NULL, NULL),
(71, 'Tangail', 71, 1, '2018-05-30 14:10:56', NULL, '2018-05-30 14:10:56', NULL, NULL),
(72, 'Elenga', 72, 1, '2018-05-30 14:10:56', NULL, '2018-05-30 14:10:56', NULL, NULL),
(73, 'Savar', 73, 1, '2018-05-30 14:10:56', NULL, '2018-05-30 14:10:56', NULL, NULL),
(74, 'Nobinagar', 74, 1, '2018-05-30 14:10:56', NULL, '2018-05-30 14:10:56', NULL, NULL),
(75, 'Manikgonj', 75, 1, '2018-05-30 14:10:56', NULL, '2018-05-30 14:10:56', NULL, NULL),
(76, 'Mirpur-1', 76, 1, '2018-05-30 14:10:56', NULL, '2018-05-30 14:10:56', NULL, NULL),
(77, 'Mirpur-13', 77, 1, '2018-05-30 14:10:56', NULL, '2018-05-30 14:10:56', NULL, NULL),
(78, 'Hemayetpur', 78, 1, '2018-05-30 14:10:56', NULL, '2018-05-30 14:10:56', NULL, NULL),
(79, 'CSD- Cantonment', 79, 1, '2018-05-30 14:10:56', NULL, '2018-05-30 14:10:56', NULL, NULL),
(80, 'Faridpur Sadar', 80, 1, '2018-05-30 14:10:56', NULL, '2018-05-30 14:10:56', NULL, NULL),
(81, 'Rajbari', 81, 1, '2018-05-30 14:10:56', NULL, '2018-05-30 14:10:56', NULL, NULL),
(82, 'Shariatpur', 82, 1, '2018-05-30 14:10:56', NULL, '2018-05-30 14:10:56', NULL, NULL),
(83, 'Madaripur ', 83, 1, '2018-05-30 14:10:56', NULL, '2018-05-30 14:10:56', NULL, NULL),
(84, 'Barishal Sadar', 84, 1, '2018-05-30 14:10:56', NULL, '2018-05-30 14:10:56', NULL, NULL),
(85, 'Uzirpur', 85, 1, '2018-05-30 14:10:56', NULL, '2018-05-30 14:10:56', NULL, NULL),
(86, 'Jhalakathi', 86, 1, '2018-05-30 14:10:56', NULL, '2018-05-30 14:10:56', NULL, NULL),
(87, 'Bhola Sadar', 87, 1, '2018-05-30 14:10:56', NULL, '2018-05-30 14:10:56', NULL, NULL),
(88, 'Bawfal', 88, 1, '2018-05-30 14:10:56', NULL, '2018-05-30 14:10:56', NULL, NULL),
(89, 'Patuakhali', 89, 1, '2018-05-30 14:10:56', NULL, '2018-05-30 14:10:56', NULL, NULL),
(90, 'Amtoli', 90, 1, '2018-05-30 14:10:56', NULL, '2018-05-30 14:10:56', NULL, NULL),
(91, 'Barguna', 91, 1, '2018-05-30 14:10:56', NULL, '2018-05-30 14:10:56', NULL, NULL),
(92, 'Eidgah', 92, 1, '2018-05-30 14:10:56', NULL, '2018-05-30 14:10:56', NULL, NULL),
(93, 'Laldighi', 93, 1, '2018-05-30 14:10:56', NULL, '2018-05-30 14:10:56', NULL, NULL),
(94, 'Goainghat', 94, 1, '2018-05-30 14:10:56', NULL, '2018-05-30 14:10:56', NULL, NULL),
(95, 'South Surma', 95, 1, '2018-05-30 14:10:56', NULL, '2018-05-30 14:10:56', NULL, NULL),
(96, 'Bianibazar', 96, 1, '2018-05-30 14:10:56', NULL, '2018-05-30 14:10:56', NULL, NULL),
(97, 'Sunamgonj', 97, 1, '2018-05-30 14:10:56', NULL, '2018-05-30 14:10:56', NULL, NULL),
(98, 'Bisawnath', 98, 1, '2018-05-30 14:10:56', NULL, '2018-05-30 14:10:56', NULL, NULL),
(99, 'Moulovibazar', 99, 1, '2018-05-30 14:10:56', NULL, '2018-05-30 14:10:56', NULL, NULL),
(100, 'Kulaura', 100, 1, '2018-05-30 14:10:56', NULL, '2018-05-30 14:10:56', NULL, NULL),
(101, 'Hobigonj', 101, 1, '2018-05-30 14:10:56', NULL, '2018-05-30 14:10:56', NULL, NULL),
(102, 'Sayestagonj', 102, 1, '2018-05-30 14:10:56', NULL, '2018-05-30 14:10:56', NULL, NULL),
(103, 'B-Baria Sador', 103, 1, '2018-05-30 14:10:56', NULL, '2018-05-30 14:10:56', NULL, NULL),
(104, 'Sarail', 104, 1, '2018-05-30 14:10:56', NULL, '2018-05-30 14:10:56', NULL, NULL),
(105, 'Companygonj', 105, 1, '2018-05-30 14:10:56', NULL, '2018-05-30 14:10:56', NULL, NULL),
(106, 'Comilla', 106, 1, '2018-05-30 14:10:56', NULL, '2018-05-30 14:10:56', NULL, NULL),
(107, 'Gauripur', 107, 1, '2018-05-30 14:10:56', NULL, '2018-05-30 14:10:56', NULL, NULL),
(108, 'Paduar Bazar', 108, 1, '2018-05-30 14:10:56', NULL, '2018-05-30 14:10:56', NULL, NULL),
(109, 'Laksham', 109, 1, '2018-05-30 14:10:56', NULL, '2018-05-30 14:10:56', NULL, NULL),
(110, 'Chandpur', 110, 1, '2018-05-30 14:10:56', NULL, '2018-05-30 14:10:56', NULL, NULL),
(111, 'Hajigonj', 111, 1, '2018-05-30 14:10:56', NULL, '2018-05-30 14:10:56', NULL, NULL),
(112, 'Chowmuhani', 112, 1, '2018-05-30 14:10:56', NULL, '2018-05-30 14:10:56', NULL, NULL),
(113, 'Sonaimuri', 113, 1, '2018-05-30 14:10:56', NULL, '2018-05-30 14:10:56', NULL, NULL),
(114, 'Chandragonj', 114, 1, '2018-05-30 14:10:56', NULL, '2018-05-30 14:10:56', NULL, NULL),
(115, 'Maijdee', 115, 1, '2018-05-30 14:10:56', NULL, '2018-05-30 14:10:56', NULL, NULL),
(116, 'Subarnachar', 116, 1, '2018-05-30 14:10:56', NULL, '2018-05-30 14:10:56', NULL, NULL),
(117, 'Feni Sadar', 117, 1, '2018-05-30 14:10:56', NULL, '2018-05-30 14:10:56', NULL, NULL),
(118, 'Chowddagram', 118, 1, '2018-05-30 14:10:56', NULL, '2018-05-30 14:10:56', NULL, NULL),
(119, 'Dagonbuiyan', 119, 1, '2018-05-30 14:10:56', NULL, '2018-05-30 14:10:56', NULL, NULL),
(120, 'Nazirhat', 120, 1, '2018-05-30 14:10:56', NULL, '2018-05-30 14:10:56', NULL, NULL),
(121, 'Khagrachari', 121, 1, '2018-05-30 14:10:56', NULL, '2018-05-30 14:10:56', NULL, NULL),
(122, 'Bondar', 122, 1, '2018-05-30 14:10:56', NULL, '2018-05-30 14:10:56', NULL, NULL),
(123, 'Patenga', 123, 1, '2018-05-30 14:10:56', NULL, '2018-05-30 14:10:56', NULL, NULL),
(124, 'Halishahar', 124, 1, '2018-05-30 14:10:56', NULL, '2018-05-30 14:10:56', NULL, NULL),
(125, 'Asadgonj', 125, 1, '2018-05-30 14:10:56', NULL, '2018-05-30 14:10:56', NULL, NULL),
(126, 'Bakulia', 126, 1, '2018-05-30 14:10:56', NULL, '2018-05-30 14:10:56', NULL, NULL),
(127, 'Khulshi', 127, 1, '2018-05-30 14:10:56', NULL, '2018-05-30 14:10:56', NULL, NULL),
(128, 'Alangkar', 128, 1, '2018-05-30 14:10:56', NULL, '2018-05-30 14:10:56', NULL, NULL),
(129, 'Sitakundu', 129, 1, '2018-05-30 14:10:56', NULL, '2018-05-30 14:10:56', NULL, NULL),
(130, 'Hathazari', 130, 1, '2018-05-30 14:10:56', NULL, '2018-05-30 14:10:56', NULL, NULL),
(131, 'Oxyzen', 131, 1, '2018-05-30 14:10:56', NULL, '2018-05-30 14:10:56', NULL, NULL),
(132, 'Rangamati', 132, 1, '2018-05-30 14:10:56', NULL, '2018-05-30 14:10:56', NULL, NULL),
(133, 'Mohora', 133, 1, '2018-05-30 14:10:56', NULL, '2018-05-30 14:10:56', NULL, NULL),
(134, 'Cox\'s Bazar Sadar', 134, 1, '2018-05-30 14:10:56', NULL, '2018-05-30 14:10:56', NULL, NULL),
(135, 'Ukhiya', 135, 1, '2018-05-30 14:10:56', NULL, '2018-05-30 14:10:56', NULL, NULL),
(136, 'Eidgaon', 136, 1, '2018-05-30 14:10:56', NULL, '2018-05-30 14:10:56', NULL, NULL),
(137, 'Chokoria', 137, 1, '2018-05-30 14:10:56', NULL, '2018-05-30 14:10:56', NULL, NULL),
(138, 'Moheskhali', 138, 1, '2018-05-30 14:10:56', NULL, '2018-05-30 14:10:56', NULL, NULL),
(139, 'Amirabad', 139, 1, '2018-05-30 14:10:56', NULL, '2018-05-30 14:10:56', NULL, NULL),
(140, 'Keranir Hat', 140, 1, '2018-05-30 14:10:56', NULL, '2018-05-30 14:10:56', NULL, NULL),
(141, 'Patiya', 141, 1, '2018-05-30 14:10:56', NULL, '2018-05-30 14:10:56', NULL, NULL),
(142, 'Anowara', 142, 1, '2018-05-30 14:10:56', NULL, '2018-05-30 14:10:56', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `unions`
--

DROP TABLE IF EXISTS `unions`;
CREATE TABLE IF NOT EXISTS `unions` (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `countries_id` int(10) UNSIGNED DEFAULT NULL,
  `divisions_id` int(10) UNSIGNED DEFAULT NULL,
  `districts_id` int(10) UNSIGNED DEFAULT NULL,
  `upazilas_id` int(10) UNSIGNED DEFAULT NULL,
  `description` varchar(1000) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_by` int(10) UNSIGNED DEFAULT NULL,
  `updated_by` int(10) UNSIGNED DEFAULT NULL,
  `is_active` tinyint(1) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `unions_countries_id_index` (`countries_id`),
  KEY `unions_divisions_id_index` (`divisions_id`),
  KEY `unions_districts_id_index` (`districts_id`),
  KEY `unions_upazilas_id_index` (`upazilas_id`),
  KEY `unions_created_by_index` (`created_by`),
  KEY `unions_updated_by_index` (`updated_by`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `unions`
--

INSERT INTO `unions` (`id`, `created_at`, `updated_at`, `name`, `countries_id`, `divisions_id`, `districts_id`, `upazilas_id`, `description`, `created_by`, `updated_by`, `is_active`) VALUES
(1, '2018-05-13 06:14:05', '2018-05-13 06:14:05', 'Gazir Khamar', 1, 1, 1, 1, 'NA', 2, 1, 1),
(2, '2018-05-13 06:18:07', '2018-05-13 06:18:07', 'Kalshi', 1, 1, 1, 1, 'NA', 2, 1, 1);

-- --------------------------------------------------------

--
-- Table structure for table `upazilas`
--

DROP TABLE IF EXISTS `upazilas`;
CREATE TABLE IF NOT EXISTS `upazilas` (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `countries_id` int(10) UNSIGNED DEFAULT NULL,
  `divisions_id` int(10) UNSIGNED DEFAULT NULL,
  `districts_id` int(10) UNSIGNED DEFAULT NULL,
  `description` varchar(1000) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_by` int(10) UNSIGNED DEFAULT NULL,
  `updated_by` int(10) UNSIGNED DEFAULT NULL,
  `is_active` tinyint(1) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `upazilas_countries_id_index` (`countries_id`),
  KEY `upazilas_divisions_id_index` (`divisions_id`),
  KEY `upazilas_districts_id_index` (`districts_id`),
  KEY `upazilas_created_by_index` (`created_by`),
  KEY `upazilas_updated_by_index` (`updated_by`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `upazilas`
--

INSERT INTO `upazilas` (`id`, `created_at`, `updated_at`, `name`, `countries_id`, `divisions_id`, `districts_id`, `description`, `created_by`, `updated_by`, `is_active`) VALUES
(1, '2018-05-13 06:12:24', '2018-05-13 06:12:24', 'Sherpur Sadar', 1, 1, 1, 'NA', 2, 1, 1);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
CREATE TABLE IF NOT EXISTS `users` (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(255) DEFAULT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) DEFAULT NULL,
  `password_key` varchar(200) DEFAULT NULL,
  `password_expire_days` int(3) DEFAULT NULL,
  `last_name` varchar(100) DEFAULT NULL,
  `mobile` varchar(11) DEFAULT NULL,
  `home_telephone` varchar(20) DEFAULT NULL,
  `username` varchar(15) DEFAULT NULL,
  `secret_question_1` varchar(100) DEFAULT NULL,
  `secret_question_2` varchar(100) DEFAULT NULL,
  `secret_question_ans_1` varchar(100) DEFAULT NULL,
  `secret_question_ans_2` varchar(100) DEFAULT NULL,
  `identification_type_id` int(11) DEFAULT '0',
  `identification_number` varchar(30) DEFAULT NULL,
  `identification_expire_date` date DEFAULT NULL,
  `date_of_birth` date DEFAULT NULL,
  `gender` varchar(500) DEFAULT NULL,
  `religion_id` int(11) DEFAULT '0',
  `father_name` varchar(100) DEFAULT NULL,
  `father_occupation_id` int(11) DEFAULT '0',
  `mother_name` varchar(100) DEFAULT NULL,
  `mother_occupation_id` int(11) DEFAULT '0',
  `bank_account_number` varchar(30) DEFAULT NULL,
  `bank_id` int(11) DEFAULT '0',
  `bank_branch` varchar(100) DEFAULT NULL,
  `last_login_date_time` timestamp NULL DEFAULT NULL,
  `default_module_id` int(11) DEFAULT '0',
  `user_image` varchar(100) DEFAULT 'images/default/avatar.jpg',
  `address` varchar(100) DEFAULT NULL,
  `is_reliever` tinyint(4) DEFAULT '0',
  `reliever_to` int(11) DEFAULT '0',
  `reliever_start_datetime` timestamp NULL DEFAULT NULL,
  `reliever_end_datetime` timestamp NULL DEFAULT NULL,
  `line_manager_id` int(11) DEFAULT '0',
  `designation_id` int(11) DEFAULT '0',
  `department_id` int(11) DEFAULT '0',
  `location_id` int(11) DEFAULT '0',
  `default_vehicle_id` int(11) DEFAULT '0' COMMENT 'If this employee or user is a driver',
  `default_url` varchar(500) DEFAULT NULL,
  `default_language_id` int(11) DEFAULT '0',
  `joining_date` date DEFAULT NULL,
  `emergency_contact_person_name` varchar(100) DEFAULT NULL,
  `emergency_contact_relation` varchar(30) DEFAULT NULL,
  `emergency_contact_number` varchar(20) DEFAULT NULL,
  `remember_token` varchar(100) DEFAULT NULL,
  `territories_id` int(10) NOT NULL DEFAULT '0',
  `distribution_houses_id` int(10) NOT NULL DEFAULT '0',
  `rfu1` varchar(255) DEFAULT NULL,
  `rfu2` varchar(255) DEFAULT NULL,
  `zones_id` int(10) NOT NULL DEFAULT '0',
  `created_by` int(11) DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_by` int(11) DEFAULT '0',
  `updated_at` timestamp NULL DEFAULT NULL,
  `status` varchar(500) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_email_unique` (`email`),
  UNIQUE KEY `users_mobile_unique` (`mobile`),
  UNIQUE KEY `users_username_unique` (`username`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `password`, `password_key`, `password_expire_days`, `last_name`, `mobile`, `home_telephone`, `username`, `secret_question_1`, `secret_question_2`, `secret_question_ans_1`, `secret_question_ans_2`, `identification_type_id`, `identification_number`, `identification_expire_date`, `date_of_birth`, `gender`, `religion_id`, `father_name`, `father_occupation_id`, `mother_name`, `mother_occupation_id`, `bank_account_number`, `bank_id`, `bank_branch`, `last_login_date_time`, `default_module_id`, `user_image`, `address`, `is_reliever`, `reliever_to`, `reliever_start_datetime`, `reliever_end_datetime`, `line_manager_id`, `designation_id`, `department_id`, `location_id`, `default_vehicle_id`, `default_url`, `default_language_id`, `joining_date`, `emergency_contact_person_name`, `emergency_contact_relation`, `emergency_contact_number`, `remember_token`, `territories_id`, `distribution_houses_id`, `rfu1`, `rfu2`, `zones_id`, `created_by`, `created_at`, `updated_by`, `updated_at`, `status`) VALUES
(1, 'Apsis', 'apsis@gmail.com', '$2y$10$2nkgX.Wgvs4q3wCgICad/uz2g90yakp5Tb5y9UYGyYSslArBSJXvK', NULL, NULL, NULL, NULL, NULL, 'apsis', NULL, NULL, NULL, NULL, 0, NULL, NULL, NULL, NULL, 0, NULL, 0, NULL, 0, NULL, 0, NULL, NULL, 0, 'images/default/avatar.jpg', NULL, 0, 0, NULL, NULL, 0, 0, 0, 0, 0, NULL, 0, NULL, NULL, NULL, NULL, 'Pc3DlVzUks53124A1y1GYdFmj5p360neJsvfNJSTHGiO3dbeNnS63DGGJUu1', 0, 0, NULL, NULL, 0, 0, NULL, 0, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `userss`
--

DROP TABLE IF EXISTS `userss`;
CREATE TABLE IF NOT EXISTS `userss` (
  `id` int(11) NOT NULL,
  `name` varchar(255) COLLATE utf8_bin NOT NULL,
  `email` varchar(255) COLLATE utf8_bin NOT NULL,
  `phone` varchar(50) COLLATE utf8_bin NOT NULL,
  `username` varchar(255) COLLATE utf8_bin NOT NULL,
  `password` varchar(255) COLLATE utf8_bin NOT NULL,
  `zones_id` int(11) NOT NULL,
  `regions_id` int(11) NOT NULL,
  `territories_id` int(11) NOT NULL,
  `designations_id` varchar(255) COLLATE utf8_bin NOT NULL,
  `created_by` int(11) NOT NULL,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `rfu1` varchar(255) COLLATE utf8_bin NOT NULL,
  `rfu2` varchar(255) COLLATE utf8_bin NOT NULL,
  `distribution_houses_id` int(11) NOT NULL,
  KEY `users_fk0` (`zones_id`),
  KEY `users_fk1` (`regions_id`),
  KEY `users_fk2` (`territories_id`),
  KEY `users_fk3` (`designations_id`),
  KEY `users_fk4` (`distribution_houses_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- --------------------------------------------------------

--
-- Table structure for table `user_levels`
--

DROP TABLE IF EXISTS `user_levels`;
CREATE TABLE IF NOT EXISTS `user_levels` (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `created_by` int(11) NOT NULL DEFAULT '0',
  `created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `updated_by` int(11) NOT NULL DEFAULT '0',
  `updated` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `status` enum('Active','Inactive') NOT NULL,
  `privilege_edit` tinyint(4) NOT NULL DEFAULT '0',
  `privilege_delete` tinyint(4) NOT NULL DEFAULT '0',
  `privilege_add` tinyint(4) NOT NULL DEFAULT '0',
  `privilege_view_all` tinyint(4) NOT NULL DEFAULT '0',
  `all_privilege` tinyint(4) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `user_levels_user_level_name_unique` (`name`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `user_levels`
--

INSERT INTO `user_levels` (`id`, `name`, `description`, `created_by`, `created`, `updated_by`, `updated`, `status`, `privilege_edit`, `privilege_delete`, `privilege_add`, `privilege_view_all`, `all_privilege`, `created_at`, `updated_at`) VALUES
(1, 'Admin', 'dd', 0, '2018-05-14 10:24:52', 0, '2018-05-14 10:25:03', 'Active', 0, 0, 0, 0, 0, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `user_settings`
--

DROP TABLE IF EXISTS `user_settings`;
CREATE TABLE IF NOT EXISTS `user_settings` (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `login_by` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'email' COMMENT 'login by username/email/mobile',
  `username_minimum_length` int(11) NOT NULL DEFAULT '5',
  `username_maximum_length` int(11) NOT NULL DEFAULT '25',
  `username_have_special_character` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '0' COMMENT '0=no and 1=yes',
  `maximum_login_same_user` tinyint(4) NOT NULL DEFAULT '1',
  `maximum_login_perday` tinyint(4) NOT NULL DEFAULT '10',
  `maximum_login_permonth` int(11) NOT NULL DEFAULT '1000',
  `maximum_login_attempt` tinyint(4) NOT NULL DEFAULT '5',
  `maximum_login_attempt_reset_time` tinyint(4) NOT NULL DEFAULT '15' COMMENT 'minimum 15 minutes',
  `have_login_security_code` tinyint(4) NOT NULL DEFAULT '0' COMMENT '0=no , 1= yes',
  `security_code_length` tinyint(4) NOT NULL DEFAULT '6',
  `session_time` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '30' COMMENT 'default 30 minutes',
  `password_minimum_length` int(11) NOT NULL DEFAULT '6',
  `password_maximum_length` int(11) NOT NULL DEFAULT '12',
  `password_have_special_character` tinyint(4) NOT NULL DEFAULT '0' COMMENT '0=no and 1=yes',
  `password_special_character` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `password_have_expire_day` tinyint(4) NOT NULL DEFAULT '0' COMMENT '0=no, 1=yes',
  `password_expire_days` int(11) NOT NULL DEFAULT '0',
  `last_used_password` tinyint(4) NOT NULL DEFAULT '5' COMMENT 'do not use last 5 used password',
  `have_default_password` tinyint(4) NOT NULL DEFAULT '0' COMMENT '0=no, 1=yes',
  `default_password_length` int(11) NOT NULL DEFAULT '8',
  `default_password` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `have_password_plaintext` tinyint(4) NOT NULL DEFAULT '1' COMMENT '0=no, 1=yes',
  `username_email_phone_as_password` tinyint(4) NOT NULL DEFAULT '1' COMMENT '0=no, 1=yes',
  `password_prohibited_words` text COLLATE utf8mb4_unicode_ci COMMENT 'this words are not as a password',
  `user_activation_policy` tinyint(4) NOT NULL DEFAULT '1' COMMENT '0=no, 1=yes',
  `created_by` int(11) NOT NULL,
  `updated_by` int(11) NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `zones`
--

DROP TABLE IF EXISTS `zones`;
CREATE TABLE IF NOT EXISTS `zones` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `zone_name` varchar(255) NOT NULL,
  `ordering` int(11) DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_by` int(11) DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `rfu1` int(11) DEFAULT NULL,
  `rfu2` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `zones`
--

INSERT INTO `zones` (`id`, `zone_name`, `ordering`, `created_by`, `created_at`, `updated_by`, `updated_at`, `rfu1`, `rfu2`) VALUES
(1, 'North Bengal - 01', 1, 1, '2018-05-30 08:01:46', NULL, NULL, NULL, NULL),
(2, 'North Bengal-2', 2, 1, '2018-05-30 08:01:46', NULL, NULL, NULL, NULL),
(3, 'Jessore Zone', 3, 1, '2018-05-30 08:01:46', NULL, NULL, NULL, NULL),
(4, 'Mymensingh Zone', 4, 1, '2018-05-30 08:01:46', NULL, NULL, NULL, NULL),
(5, 'Dhaka North Zone', 5, 1, '2018-05-30 08:01:46', NULL, NULL, NULL, NULL),
(6, 'Dhaka South Zone', 6, 1, '2018-05-30 08:01:46', NULL, NULL, NULL, NULL),
(7, 'Dhaka West Zone', 7, 1, '2018-05-30 08:01:46', NULL, NULL, NULL, NULL),
(8, 'Barishal Zone', 8, 1, '2018-05-30 08:01:46', 1, '2018-05-30 02:01:22', NULL, NULL),
(9, 'Sylhet Zone', 9, 1, '2018-05-30 08:01:46', NULL, NULL, NULL, NULL),
(10, 'Comilla Zone ', 10, 1, '2018-05-30 08:01:46', NULL, NULL, NULL, NULL),
(11, 'Noakhali Zone', 11, 1, '2018-05-30 08:01:46', NULL, NULL, NULL, NULL),
(12, 'Chottrogram Zone', 12, 1, '2018-05-30 08:01:46', NULL, NULL, NULL, NULL);

--
-- Constraints for dumped tables
--

--
-- Constraints for table `form_items`
--
ALTER TABLE `form_items`
  ADD CONSTRAINT `form_items_ibfk_1` FOREIGN KEY (`forms_id`) REFERENCES `forms` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
