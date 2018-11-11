/*
Navicat MySQL Data Transfer

Source Server         : localhost
Source Server Version : 50719
Source Host           : localhost:3306
Source Database       : laraframework

Target Server Type    : MYSQL
Target Server Version : 50719
File Encoding         : 65001

Date: 2018-06-04 12:27:03
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for brands
-- ----------------------------
DROP TABLE IF EXISTS `brands`;
CREATE TABLE `brands` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `categories_id` int(10) unsigned DEFAULT NULL,
  `brand_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `segment` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `ordering` int(10) DEFAULT NULL,
  `rfu1` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `rfu2` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `created_by` int(10) unsigned DEFAULT NULL,
  `updated_by` int(10) unsigned DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `is_active` tinyint(1) DEFAULT '1',
  PRIMARY KEY (`id`),
  KEY `products_product_categories_id_index` (`categories_id`),
  KEY `products_created_by_index` (`created_by`),
  KEY `products_updated_by_index` (`updated_by`)
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ----------------------------
-- Records of brands
-- ----------------------------
INSERT INTO `brands` VALUES ('1', '1', 'Tiger', null, null, '1', null, null, null, null, null, null, '1');
INSERT INTO `brands` VALUES ('2', '1', 'Black Horse', null, null, '4', null, null, null, null, null, null, '1');
INSERT INTO `brands` VALUES ('3', '2', 'Fizz up', null, null, '0', null, null, null, null, null, null, '1');
INSERT INTO `brands` VALUES ('4', '2', 'Uro Cola', null, null, '2', null, null, null, null, null, null, '1');
INSERT INTO `brands` VALUES ('5', '2', 'Uro Lemon', null, null, '3', null, null, null, null, null, null, '1');
INSERT INTO `brands` VALUES ('6', '2', 'Uro Orange', null, null, '5', null, null, null, null, null, null, '1');
INSERT INTO `brands` VALUES ('7', '2', 'Uro Zeera', null, null, '6', null, null, null, null, null, null, '1');
INSERT INTO `brands` VALUES ('8', '3', 'Oranjee', null, null, '7', null, null, null, null, null, null, '1');
INSERT INTO `brands` VALUES ('9', '3', 'Lemonjee', null, null, '8', null, null, null, null, null, null, '1');
INSERT INTO `brands` VALUES ('10', '4', 'Mangolee', null, null, '9', null, null, null, null, null, null, '1');
INSERT INTO `brands` VALUES ('11', '4', 'Mango King', null, null, '10', null, null, null, null, null, null, '1');
INSERT INTO `brands` VALUES ('12', '5', 'Lychena', null, null, '11', null, null, null, null, null, null, '1');
INSERT INTO `brands` VALUES ('13', '6', 'Alma', null, null, '12', null, null, null, null, null, null, '1');

-- ----------------------------
-- Table structure for menus
-- ----------------------------
DROP TABLE IF EXISTS `menus`;
CREATE TABLE `menus` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
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
) ENGINE=InnoDB AUTO_INCREMENT=17 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ----------------------------
-- Records of menus
-- ----------------------------
INSERT INTO `menus` VALUES ('2', 'Nationally', 'All National', 'Main', '0', '1', 'fa fa-address-card', '#', '1', '1', '0', null, null, '1');
INSERT INTO `menus` VALUES ('3', 'Zone', '#', 'Sub', '2', '1', 'fa fa-list', 'zones', '1', '1', '0', null, null, '1');
INSERT INTO `menus` VALUES ('4', 'Region', '#', 'Sub', '2', '1', 'fa fa-list', 'regions', '2', '1', '0', null, null, '1');
INSERT INTO `menus` VALUES ('5', 'Territories', '#', 'Sub', '2', '1', 'fa fa-list', 'territories', '2', '1', '0', null, null, '1');
INSERT INTO `menus` VALUES ('6', 'Distributor', '#', 'Sub', '2', '1', 'fa fa-list', 'distribution_houses', '2', '1', '0', null, null, '1');
INSERT INTO `menus` VALUES ('7', 'Product Info', 'All product info here', 'Main', '0', '1', 'fa fa-product-hunt', '#', '1', '1', '0', null, null, '1');
INSERT INTO `menus` VALUES ('8', 'Category', '#', 'Sub', '7', '1', 'fa fa-list', 'categories', '1', '1', '0', null, null, '1');
INSERT INTO `menus` VALUES ('9', 'Product Type', '#', 'Sub', '7', '1', 'fa fa-list', 'product_types', '1', '1', '0', null, null, '0');
INSERT INTO `menus` VALUES ('10', 'Product', '#', 'Sub', '7', '1', 'fa fa-list', 'products', '1', '1', '0', null, null, '1');
INSERT INTO `menus` VALUES ('11', 'Skue', '#', 'Sub', '7', '1', 'fa fa-list', 'skues', '1', '1', '0', null, null, '1');
INSERT INTO `menus` VALUES ('12', 'Brand', '#', 'Sub', '7', '1', 'fa fa-list', 'brands', '1', '1', '0', null, null, '1');
INSERT INTO `menus` VALUES ('13', 'SMS', 'All SMS Menu Here', 'Main', '0', '1', 'fa fa-comment', '#', '1', '1', '0', null, null, '1');
INSERT INTO `menus` VALUES ('14', 'SMS Inbox', '#', 'Sub', '13', '1', 'fa fa-list', 'sms_inboxes', '1', '1', '0', null, null, '1');
INSERT INTO `menus` VALUES ('15', 'Settings', '#', 'Main', '0', '1', 'fa fa-cog', '#', '1', '1', '0', null, null, '1');
INSERT INTO `menus` VALUES ('16', 'Ordering Brand&Skue', '#', 'Sub', '15', '1', 'fa fa-list-ol', 'settings/brand_skue', '1', '1', '0', null, null, '1');

-- ----------------------------
-- Table structure for products
-- ----------------------------
DROP TABLE IF EXISTS `products`;
CREATE TABLE `products` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `product_name` varchar(255) DEFAULT NULL,
  `brands_id` int(11) NOT NULL,
  `skues_id` int(11) NOT NULL,
  `price` decimal(11,2) DEFAULT NULL,
  `quantity` int(10) DEFAULT NULL,
  `description` text,
  `rfu1` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `rfu2` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `created_by` int(10) unsigned DEFAULT NULL,
  `updated_by` int(10) unsigned DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `is_active` tinyint(1) DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=latin1;

-- ----------------------------
-- Records of products
-- ----------------------------
INSERT INTO `products` VALUES ('1', 'safda', '2', '1', '9.00', '22', 'asdfasdf', null, null, '2018-05-27 10:20:29', '1', '1', '2018-05-27 10:30:26', '1');
INSERT INTO `products` VALUES ('2', '250ml pet', '5', '5', '5.00', '13', null, null, null, '2018-06-04 06:13:38', '1', null, '2018-06-04 06:21:20', '1');
INSERT INTO `products` VALUES ('3', null, '12', '5', '3.00', '3', null, null, null, '2018-06-04 06:24:45', '1', null, '2018-06-04 06:24:45', '0');

-- ----------------------------
-- Table structure for skues
-- ----------------------------
DROP TABLE IF EXISTS `skues`;
CREATE TABLE `skues` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `brands_id` int(10) DEFAULT NULL,
  `sku_name` varchar(255) NOT NULL,
  `short_name` varchar(255) DEFAULT NULL,
  `description` text,
  `ordering` int(10) DEFAULT NULL,
  `rfu1` varchar(255) DEFAULT NULL,
  `rfu2` varchar(255) DEFAULT NULL,
  `created_by` int(10) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_by` int(10) DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  `is_active` tinyint(1) DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=33 DEFAULT CHARSET=latin1;

-- ----------------------------
-- Records of skues
-- ----------------------------
INSERT INTO `skues` VALUES ('1', '1', '250ml pet', null, null, '1', null, null, null, '2018-06-04 11:22:51', null, '2018-06-04 11:59:45', '1');
INSERT INTO `skues` VALUES ('2', '1', '250ml can', null, null, '0', null, null, null, '2018-06-04 11:22:51', null, '2018-06-04 11:59:44', '1');
INSERT INTO `skues` VALUES ('3', '2', '250ml pet', null, null, '0', null, null, null, '2018-06-04 11:22:51', null, '2018-06-04 11:59:05', '1');
INSERT INTO `skues` VALUES ('4', '2', '250ml can', null, null, '0', null, null, null, '2018-06-04 11:22:51', null, '2018-06-04 11:59:03', '1');
INSERT INTO `skues` VALUES ('5', '3', '250ml pet', null, null, '0', null, null, null, '2018-06-04 11:22:51', null, '2018-06-04 11:25:46', '1');
INSERT INTO `skues` VALUES ('6', '3', '250ml can', null, null, '3', null, null, null, '2018-06-04 11:22:51', null, '2018-06-04 12:12:17', '1');
INSERT INTO `skues` VALUES ('7', '3', '500ml', null, null, '4', null, null, null, '2018-06-04 11:22:51', null, '2018-06-04 12:00:07', '1');
INSERT INTO `skues` VALUES ('8', '3', '1L', null, null, '1', null, null, null, '2018-06-04 11:22:51', null, '2018-06-04 12:12:17', '1');
INSERT INTO `skues` VALUES ('9', '3', '2L', null, null, '2', null, null, null, '2018-06-04 11:22:51', null, '2018-06-04 12:12:17', '1');
INSERT INTO `skues` VALUES ('10', '4', '250ml pet', null, null, '0', null, null, null, '2018-06-04 11:22:51', null, '2018-06-04 11:25:46', '1');
INSERT INTO `skues` VALUES ('11', '4', '250ml can', null, null, '0', null, null, null, '2018-06-04 11:22:51', null, '2018-06-04 11:25:46', '1');
INSERT INTO `skues` VALUES ('12', '4', '500ml', null, null, '0', null, null, null, '2018-06-04 11:22:51', null, '2018-06-04 11:25:46', '1');
INSERT INTO `skues` VALUES ('13', '5', '250ml pet', null, null, '0', null, null, null, '2018-06-04 11:22:51', null, '2018-06-04 11:25:46', '1');
INSERT INTO `skues` VALUES ('14', '5', '250ml can', null, null, '0', null, null, null, '2018-06-04 11:22:51', null, '2018-06-04 11:25:46', '1');
INSERT INTO `skues` VALUES ('15', '5', '500ml', null, null, '0', null, null, null, '2018-06-04 11:22:51', null, '2018-06-04 11:25:46', '1');
INSERT INTO `skues` VALUES ('16', '6', '250ml pet', null, null, '0', null, null, null, '2018-06-04 11:22:51', null, '2018-06-04 11:25:46', '1');
INSERT INTO `skues` VALUES ('17', '6', '250ml can', null, null, '0', null, null, null, '2018-06-04 11:22:51', null, '2018-06-04 11:25:46', '1');
INSERT INTO `skues` VALUES ('18', '6', '500ml', null, null, '0', null, null, null, '2018-06-04 11:22:51', null, '2018-06-04 11:25:46', '1');
INSERT INTO `skues` VALUES ('19', '6', '1 L', null, null, '0', null, null, null, '2018-06-04 11:22:51', null, '2018-06-04 11:25:46', '1');
INSERT INTO `skues` VALUES ('20', '7', '250ml pet', null, null, '0', null, null, null, '2018-06-04 11:22:51', null, '2018-06-04 11:25:46', '1');
INSERT INTO `skues` VALUES ('21', '8', '250ml pet', null, null, '0', null, null, null, '2018-06-04 11:22:51', null, '2018-06-04 11:25:46', '1');
INSERT INTO `skues` VALUES ('22', '9', '250ml pet', null, null, '0', null, null, null, '2018-06-04 11:22:51', null, '2018-06-04 11:25:46', '1');
INSERT INTO `skues` VALUES ('23', '10', '250ml', null, null, '0', null, null, null, '2018-06-04 11:22:51', null, '2018-06-04 11:25:46', '1');
INSERT INTO `skues` VALUES ('24', '10', '500ml', null, null, '0', null, null, null, '2018-06-04 11:22:51', null, '2018-06-04 11:25:46', '1');
INSERT INTO `skues` VALUES ('25', '10', '1 L', null, null, '0', null, null, null, '2018-06-04 11:22:51', null, '2018-06-04 11:25:47', '1');
INSERT INTO `skues` VALUES ('26', '11', '250ml pet', null, null, '0', null, null, null, '2018-06-04 11:22:51', null, '2018-06-04 11:25:47', '1');
INSERT INTO `skues` VALUES ('27', '11', '250ml Tetra', null, null, '0', null, null, null, '2018-06-04 11:22:51', null, '2018-06-04 11:25:47', '1');
INSERT INTO `skues` VALUES ('28', '12', '300ml pet', null, null, '0', null, null, null, '2018-06-04 11:22:51', null, '2018-06-04 11:25:47', '1');
INSERT INTO `skues` VALUES ('29', '12', '250ml can', null, null, '0', null, null, null, '2018-06-04 11:22:51', null, '2018-06-04 11:25:47', '1');
INSERT INTO `skues` VALUES ('30', '13', '500 ml', null, null, '0', null, null, null, '2018-06-04 11:22:51', null, '2018-06-04 11:25:47', '1');
INSERT INTO `skues` VALUES ('31', '13', '1.5 L', null, null, '0', null, null, null, '2018-06-04 11:22:51', null, '2018-06-04 11:25:47', '1');
