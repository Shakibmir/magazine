/*
 Navicat Premium Data Transfer

 Source Server         : XAMPP
 Source Server Type    : MySQL
 Source Server Version : 100137
 Source Host           : localhost:3307
 Source Schema         : mag

 Target Server Type    : MySQL
 Target Server Version : 100137
 File Encoding         : 65001

 Date: 07/03/2019 20:49:21
*/

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

-- ----------------------------
-- Table structure for academic_years
-- ----------------------------
DROP TABLE IF EXISTS `academic_years`;
CREATE TABLE `academic_years`  (
  `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `year` varchar(11) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `opening_date` date NOT NULL,
  `closing_date` date NOT NULL,
  `final_date` date NOT NULL,
  `created_at` timestamp(0) NOT NULL DEFAULT CURRENT_TIMESTAMP(0) ON UPDATE CURRENT_TIMESTAMP(0),
  `updated_at` timestamp(0) NOT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 5 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = Compact;

-- ----------------------------
-- Records of academic_years
-- ----------------------------
INSERT INTO `academic_years` VALUES (1, '2020', '2019-02-26', '2019-02-27', '2019-03-01', '2019-02-26 19:36:11', '2019-02-26 13:36:11');
INSERT INTO `academic_years` VALUES (2, '2020', '2019-02-20', '2019-01-29', '2019-02-15', '2019-02-25 21:30:39', '2019-02-25 21:30:39');
INSERT INTO `academic_years` VALUES (3, '2021', '2019-02-27', '2019-02-28', '2019-02-28', '2019-02-25 21:30:52', '2019-02-25 21:30:52');
INSERT INTO `academic_years` VALUES (4, '2019', '2019-02-25', '2019-02-25', '2019-02-27', '2019-02-26 17:05:26', '2019-02-26 11:05:26');

-- ----------------------------
-- Table structure for comments
-- ----------------------------
DROP TABLE IF EXISTS `comments`;
CREATE TABLE `comments`  (
  `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `con_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `comment` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `created_at` timestamp(0) NOT NULL DEFAULT CURRENT_TIMESTAMP(0) ON UPDATE CURRENT_TIMESTAMP(0),
  `updated_at` timestamp(0) NOT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = Compact;

-- ----------------------------
-- Table structure for con_imgs
-- ----------------------------
DROP TABLE IF EXISTS `con_imgs`;
CREATE TABLE `con_imgs`  (
  `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `con_id` int(11) NOT NULL,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `created_at` timestamp(0) NOT NULL DEFAULT CURRENT_TIMESTAMP(0) ON UPDATE CURRENT_TIMESTAMP(0),
  `updated_at` timestamp(0) NOT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 9 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = Compact;

-- ----------------------------
-- Records of con_imgs
-- ----------------------------
INSERT INTO `con_imgs` VALUES (1, 1, 'IMG-7850.JPG', '2019-02-26 10:33:07', '2019-02-26 10:33:07');
INSERT INTO `con_imgs` VALUES (2, 1, 'IMG-7851.JPG', '2019-02-26 10:33:07', '2019-02-26 10:33:07');
INSERT INTO `con_imgs` VALUES (3, 4, 'Screenshot_6.png', '2019-02-26 10:55:13', '2019-02-26 10:55:13');
INSERT INTO `con_imgs` VALUES (4, 4, 'Screenshot_7.png', '2019-02-26 10:55:13', '2019-02-26 10:55:13');
INSERT INTO `con_imgs` VALUES (5, 4, 'Screenshot_8.png', '2019-02-26 10:55:13', '2019-02-26 10:55:13');
INSERT INTO `con_imgs` VALUES (6, 4, 'Screenshot_11.png', '2019-02-26 10:55:13', '2019-02-26 10:55:13');
INSERT INTO `con_imgs` VALUES (7, 5, '20216241_10155079643087800_1177099112_o.png', '2019-03-07 10:14:42', '2019-03-07 10:14:42');
INSERT INTO `con_imgs` VALUES (8, 5, '20257472_10155079643142800_2008625939_o.png', '2019-03-07 10:14:42', '2019-03-07 10:14:42');

-- ----------------------------
-- Table structure for contributions
-- ----------------------------
DROP TABLE IF EXISTS `contributions`;
CREATE TABLE `contributions`  (
  `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `title` varchar(500) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `file_name` varchar(500) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `user_id` int(11) NOT NULL,
  `status` int(4) NOT NULL DEFAULT 1 COMMENT '1-> submitted, 2-> commented, 3-> accepted, 0-> Rejected',
  `academic_year` varchar(11) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `created_at` timestamp(0) NOT NULL DEFAULT CURRENT_TIMESTAMP(0) ON UPDATE CURRENT_TIMESTAMP(0),
  `updated_at` timestamp(0) NOT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 6 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = Compact;

-- ----------------------------
-- Records of contributions
-- ----------------------------
INSERT INTO `contributions` VALUES (1, 'Save the tree', NULL, 3, 1, '2019', '2019-02-26 17:28:19', '2019-02-26 10:32:23');
INSERT INTO `contributions` VALUES (2, 'Save the tree', NULL, 3, 1, '2019', '2019-02-26 17:28:21', '2019-02-26 10:32:52');
INSERT INTO `contributions` VALUES (3, 'Save the tree', NULL, 3, 1, '2019', '2019-02-26 17:28:23', '2019-02-26 10:33:07');
INSERT INTO `contributions` VALUES (4, 'Save the tree', 'courseworks-coursework_2017_2018-Term_1-Level_6-CW_COMP1640_37_ver1_1819.docx', 3, 1, '2019', '2019-02-26 17:28:28', '2019-02-26 10:55:13');
INSERT INTO `contributions` VALUES (5, 'Some Awesome Title', 'courseworks-coursework_2017_2018-Term_1-Level_6-CW_COMP1649_51_ver1_1819.docx', 3, 1, '2019', '2019-03-07 10:14:42', '2019-03-07 10:14:42');

-- ----------------------------
-- Table structure for departments
-- ----------------------------
DROP TABLE IF EXISTS `departments`;
CREATE TABLE `departments`  (
  `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `created_at` timestamp(0) NOT NULL DEFAULT CURRENT_TIMESTAMP(0) ON UPDATE CURRENT_TIMESTAMP(0),
  `updated_at` timestamp(0) NOT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 2 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = Compact;

-- ----------------------------
-- Records of departments
-- ----------------------------
INSERT INTO `departments` VALUES (1, 'EEEE', '2019-02-26 05:42:49', '2019-02-25 23:42:49');

-- ----------------------------
-- Table structure for migrations
-- ----------------------------
DROP TABLE IF EXISTS `migrations`;
CREATE TABLE `migrations`  (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `migration` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int(11) NOT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 3 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Compact;

-- ----------------------------
-- Records of migrations
-- ----------------------------
INSERT INTO `migrations` VALUES (1, '2014_10_12_000000_create_users_table', 1);
INSERT INTO `migrations` VALUES (2, '2014_10_12_100000_create_password_resets_table', 1);

-- ----------------------------
-- Table structure for password_resets
-- ----------------------------
DROP TABLE IF EXISTS `password_resets`;
CREATE TABLE `password_resets`  (
  `email` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp(0) NULL DEFAULT NULL,
  INDEX `password_resets_email_index`(`email`) USING BTREE
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Compact;

-- ----------------------------
-- Table structure for user_metas
-- ----------------------------
DROP TABLE IF EXISTS `user_metas`;
CREATE TABLE `user_metas`  (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `key` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `value` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL,
  `created_at` timestamp(0) NOT NULL DEFAULT CURRENT_TIMESTAMP(0) ON UPDATE CURRENT_TIMESTAMP(0),
  `updated_at` timestamp(0) NOT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = Compact;

-- ----------------------------
-- Table structure for users
-- ----------------------------
DROP TABLE IF EXISTS `users`;
CREATE TABLE `users`  (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `email_verified_at` timestamp(0) NULL DEFAULT NULL,
  `password` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `role` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '1 -> faculty, 2 -> student, 3-> coordinator, 4-> manager, 5-> admin',
  `department_id` int(11) NULL DEFAULT NULL,
  `remember_token` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `created_at` timestamp(0) NULL DEFAULT NULL,
  `updated_at` timestamp(0) NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `users_email_unique`(`email`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 5 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Compact;

-- ----------------------------
-- Records of users
-- ----------------------------
INSERT INTO `users` VALUES (1, 'shakib mir', 'shakib.mir@gmail.com', NULL, '$2y$10$L9k3Gvw8EZH0PoKZI3gJoeIS096G8T5byU4a8cTTvJSKs1XMVR.Mq', '5', 1, 'gESI1tdNqc0flqhVgCerLvKKNgDNXsiBfxTuR1HcAEGeKvbeKtFTBqXjmVNr', '2019-02-17 10:44:48', '2019-02-17 10:44:48');
INSERT INTO `users` VALUES (2, 'Mir', 'mir@mspagol.com', NULL, '$2y$10$FJlwuB/ubY1ju61rvt/YauPdmowzZJ1jwovA9DtrMQn0JZ8ezRvv2', '4', 1, 'KRB9jwfqFcSA29AkjTnOI7KxtH0xSnDBgnF2ugwb817K5XViOy0Rm3bQtyMt', '2019-02-17 11:47:57', '2019-02-17 11:47:57');
INSERT INTO `users` VALUES (3, 'Kamran Hossain', 'eglab.tech@yahoo.com', NULL, '$2y$10$jOfIOeuuEVCFOytEgmujPOeuttNzV3GDZP02iTyQioqtSU/lgon/a', '2', 1, 'fZkQb1SmGSCwPaNlsPKyKAAjkpZkVC78c70mx7nnxmf3sWNf1WdSNhGqqBny', '2019-02-26 11:21:00', '2019-02-26 11:21:00');
INSERT INTO `users` VALUES (4, 'Porag Modak', 'poragmodak10@gmail.com', NULL, '$2y$10$MPT2b9cdhYZyNi6dcDiHtuUXBgl2KTBDgAD4rS2VNRMk7JXb2v/du', '2', 1, 'ZcgYz09GElz4RXTOx2sW1ibqIO9UDoleJIdzBKeWytSEWea1MnIKWfiGqONn', '2019-02-26 11:27:54', '2019-02-26 11:27:54');

SET FOREIGN_KEY_CHECKS = 1;
