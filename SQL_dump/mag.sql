/*
 Navicat Premium Data Transfer

 Source Server         : XAMPP
 Source Server Type    : MySQL
 Source Server Version : 100137
 Source Host           : localhost:3306
 Source Schema         : mag

 Target Server Type    : MySQL
 Target Server Version : 100137
 File Encoding         : 65001

 Date: 29/04/2019 16:42:27
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
  `created_at` timestamp(0) NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP(0),
  `updated_at` timestamp(0) NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 6 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = Compact;

-- ----------------------------
-- Records of academic_years
-- ----------------------------
INSERT INTO `academic_years` VALUES (1, '2022', '2022-04-01', '2022-04-15', '2022-05-01', '2019-04-28 13:23:09', '2019-04-28 07:23:09');
INSERT INTO `academic_years` VALUES (2, '2020', '2020-04-01', '2020-05-05', '2020-05-30', '2019-04-28 13:31:37', '2019-04-28 07:31:37');
INSERT INTO `academic_years` VALUES (3, '2021', '2021-02-01', '2021-02-28', '2021-03-15', '2019-03-07 21:19:06', '2019-03-07 15:19:06');
INSERT INTO `academic_years` VALUES (4, '2019', '2019-02-25', '2019-05-05', '2019-05-15', '2019-04-28 01:15:34', '2019-04-27 19:15:34');
INSERT INTO `academic_years` VALUES (5, '2023', '2019-04-28', '2019-05-05', '2019-05-11', '2019-04-29 10:54:43', '2019-04-29 04:54:43');

-- ----------------------------
-- Table structure for comments
-- ----------------------------
DROP TABLE IF EXISTS `comments`;
CREATE TABLE `comments`  (
  `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `con_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `comment` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `created_at` timestamp(0) NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP(0),
  `updated_at` timestamp(0) NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 18 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = Compact;

-- ----------------------------
-- Records of comments
-- ----------------------------
INSERT INTO `comments` VALUES (1, 2, 1, 'this is a test comment', '2019-03-07 21:03:45', '2019-03-07 21:03:45');
INSERT INTO `comments` VALUES (2, 2, 1, 'This is a new comment', '2019-03-07 21:10:55', '2019-03-07 21:10:55');
INSERT INTO `comments` VALUES (3, 2, 1, 'This is the last comment (For now)', '2019-03-07 21:15:27', '2019-03-07 21:15:27');
INSERT INTO `comments` VALUES (4, 3, 3, 'Press enter to post comment', '2019-03-15 21:55:25', '2019-03-15 21:55:25');
INSERT INTO `comments` VALUES (5, 2, 3, 'this is a comment from the student\'s end', '2019-03-15 22:08:39', '2019-03-15 22:08:39');
INSERT INTO `comments` VALUES (6, 2, 3, 'Okay I\'ll update accordingly', '2019-03-17 10:57:56', '2019-03-17 10:57:56');
INSERT INTO `comments` VALUES (7, 2, 1, 'Complete it with in time', '2019-03-17 10:58:36', '2019-03-17 10:58:36');
INSERT INTO `comments` VALUES (8, 2, 3, 'hi!', '2019-03-17 11:10:31', '2019-03-17 11:10:31');
INSERT INTO `comments` VALUES (9, 2, 1, 'hi!', '2019-03-17 11:11:49', '2019-03-17 11:11:49');
INSERT INTO `comments` VALUES (10, 2, 3, 'hello', '2019-03-20 12:30:59', '2019-03-20 12:30:59');
INSERT INTO `comments` VALUES (11, 35, 7, 'Do some work on your deliverable section.', '2019-04-28 03:34:29', '2019-04-28 03:34:29');
INSERT INTO `comments` VALUES (12, 35, 6, 'Thank you for your feedback I\'ll, get back to you on that', '2019-04-28 03:36:50', '2019-04-28 03:36:50');
INSERT INTO `comments` VALUES (13, 38, 7, 'good work', '2019-04-28 06:54:57', '2019-04-28 06:54:57');
INSERT INTO `comments` VALUES (14, 40, 7, 'You need to add more content.', '2019-04-28 08:37:51', '2019-04-28 08:37:51');
INSERT INTO `comments` VALUES (15, 40, 5, 'Yea sure..', '2019-04-28 08:38:14', '2019-04-28 08:38:14');
INSERT INTO `comments` VALUES (16, 41, 7, 'nice work.', '2019-04-29 05:01:17', '2019-04-29 05:01:17');
INSERT INTO `comments` VALUES (17, 41, 3, 'thanks you.', '2019-04-29 05:01:34', '2019-04-29 05:01:34');

-- ----------------------------
-- Table structure for con_imgs
-- ----------------------------
DROP TABLE IF EXISTS `con_imgs`;
CREATE TABLE `con_imgs`  (
  `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `con_id` int(11) NOT NULL,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `created_at` timestamp(0) NULL DEFAULT NULL,
  `updated_at` timestamp(0) NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 52 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = Compact;

-- ----------------------------
-- Records of con_imgs
-- ----------------------------
INSERT INTO `con_imgs` VALUES (25, 18, 'Con_18_Screenshot_16.png', '2019-03-20 00:00:58', '2019-03-20 00:00:58');
INSERT INTO `con_imgs` VALUES (26, 18, 'Con_18_Screenshot_17.png', '2019-03-20 00:00:58', '2019-03-20 00:00:58');
INSERT INTO `con_imgs` VALUES (27, 19, 'Con_19_Screenshot_15.png', '2019-03-20 00:08:05', '2019-03-20 00:08:05');
INSERT INTO `con_imgs` VALUES (28, 20, 'Con_20_Screenshot_16.png', '2019-03-20 00:21:53', '2019-03-20 00:21:53');
INSERT INTO `con_imgs` VALUES (29, 20, 'Con_20_Screenshot_17.png', '2019-03-20 00:21:53', '2019-03-20 00:21:53');
INSERT INTO `con_imgs` VALUES (30, 20, 'Con_20_Screenshot_18.png', '2019-03-20 00:21:53', '2019-03-20 00:21:53');
INSERT INTO `con_imgs` VALUES (31, 7, 'Con_7_Screenshot_16.png', '2019-03-20 01:47:45', '2019-03-20 01:47:45');
INSERT INTO `con_imgs` VALUES (33, 7, 'Con_7_Screenshot_18.png', '2019-03-20 01:47:45', '2019-03-20 01:47:45');
INSERT INTO `con_imgs` VALUES (34, 7, 'Con_7_Screenshot_7.png', '2019-03-20 02:22:52', '2019-03-20 02:22:52');
INSERT INTO `con_imgs` VALUES (35, 21, 'Con_21_Screenshot_15.png', '2019-04-27 19:20:31', '2019-04-27 19:20:31');
INSERT INTO `con_imgs` VALUES (42, 39, 'Con_39_Screenshot_16.png', '2019-04-28 07:09:41', '2019-04-28 07:09:41');
INSERT INTO `con_imgs` VALUES (43, 39, 'Con_39_Screenshot_17.png', '2019-04-28 07:09:41', '2019-04-28 07:09:41');
INSERT INTO `con_imgs` VALUES (44, 40, 'Con_40_Screenshot_16.png', '2019-04-28 08:34:49', '2019-04-28 08:34:49');
INSERT INTO `con_imgs` VALUES (46, 40, 'Con_40_Screenshot_18.png', '2019-04-28 08:39:22', '2019-04-28 08:39:22');
INSERT INTO `con_imgs` VALUES (47, 40, 'Con_40_Screenshot_10.png', '2019-04-28 08:39:38', '2019-04-28 08:39:38');
INSERT INTO `con_imgs` VALUES (48, 41, 'Con_41_Screenshot_24.png', '2019-04-29 04:57:47', '2019-04-29 04:57:47');
INSERT INTO `con_imgs` VALUES (50, 41, 'Con_41_Screenshot_31.png', '2019-04-29 04:57:47', '2019-04-29 04:57:47');
INSERT INTO `con_imgs` VALUES (51, 41, 'Con_41_Screenshot_15.png', '2019-04-29 04:59:06', '2019-04-29 04:59:06');

-- ----------------------------
-- Table structure for contributions
-- ----------------------------
DROP TABLE IF EXISTS `contributions`;
CREATE TABLE `contributions`  (
  `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `title` varchar(500) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `file_name` varchar(500) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `user_id` int(11) NOT NULL,
  `status` int(4) NOT NULL DEFAULT 1 COMMENT '1-> submitted, 2-> commented, 3-> accepted,4->Commented + Accepted, 0-> Rejected',
  `academic_year` varchar(11) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `dep_id` int(11) NULL DEFAULT NULL,
  `created_at` timestamp(0) NULL DEFAULT NULL,
  `updated_at` timestamp(0) NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 42 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = Compact;

-- ----------------------------
-- Records of contributions
-- ----------------------------
INSERT INTO `contributions` VALUES (1, 'Save the tree part 1', '', 4, 3, '4', 2, '2019-03-07 01:24:31', '2019-04-27 21:09:26');
INSERT INTO `contributions` VALUES (2, 'Saving the World', 'Con_2_task_c.docx', 3, 4, '4', 1, '2019-03-08 00:17:36', '2019-03-20 00:59:33');
INSERT INTO `contributions` VALUES (3, 'Save the tree', NULL, 3, 4, '3', 1, '2019-03-05 00:17:32', '2019-02-26 10:33:07');
INSERT INTO `contributions` VALUES (4, 'Save the tree', '', 3, 3, '3', 1, '2019-03-04 00:17:31', '2019-04-27 18:36:09');
INSERT INTO `contributions` VALUES (5, 'Some Awesome Title', '', 4, 2, '4', 2, '2019-03-04 00:17:30', '2019-03-15 20:10:20');
INSERT INTO `contributions` VALUES (6, 'Some Awesome Title', 'Con_6_coursework_2018_DSDM_Atern-_task_B.docx', 3, 3, '4', 1, '2019-02-25 19:19:18', '2019-03-20 01:00:32');
INSERT INTO `contributions` VALUES (7, 'Some awesome Title', 'Con_7_coursework_2018_DSDM_Atern-_task_B.docx', 3, 3, '4', 1, '2019-03-17 10:55:55', '2019-03-20 01:18:20');
INSERT INTO `contributions` VALUES (8, 'Some Other Awesome Title', NULL, 3, 3, '4', 1, '2019-03-19 19:57:36', '2019-03-20 13:20:47');
INSERT INTO `contributions` VALUES (12, 'A Really Formidable Title', 'Con_12_BBIT-Project-Proposal-Template.docx', 3, 3, '4', 1, '2019-03-19 20:14:50', '2019-04-27 18:36:19');
INSERT INTO `contributions` VALUES (13, 'A Magnificent Title', 'Con_13_AgileEstimatingandPlanning.pdf', 3, 3, '4', 1, '2019-03-19 20:16:30', '2019-04-27 18:36:19');
INSERT INTO `contributions` VALUES (14, 'Another Magnificent Title', NULL, 3, 3, '4', 1, '2019-03-19 20:24:07', '2019-03-20 13:21:13');
INSERT INTO `contributions` VALUES (15, 'Another Magnificent Title', NULL, 3, 3, '4', 1, '2019-03-19 20:25:34', '2019-03-20 13:21:02');
INSERT INTO `contributions` VALUES (16, 'Another Magnificent Title', NULL, 3, 1, '4', 1, '2019-03-19 20:26:51', '2019-03-19 20:26:51');
INSERT INTO `contributions` VALUES (17, 'Another Magnificent Title', 'Con_17_coursework_2018_DSDM_Atern-_task_A.pdf', 3, 3, '4', 1, '2019-03-19 20:27:47', '2019-03-20 02:39:38');
INSERT INTO `contributions` VALUES (18, 'A Really Formidable Title', NULL, 3, 3, '4', 1, '2019-03-20 00:00:58', '2019-03-20 02:42:59');
INSERT INTO `contributions` VALUES (19, 'Another Formidable Title', NULL, 3, 3, '4', 1, '2019-03-20 00:08:05', '2019-03-20 02:42:59');
INSERT INTO `contributions` VALUES (20, 'Just a title', NULL, 3, 3, '4', 1, '2019-03-20 00:21:53', '2019-03-20 02:42:59');
INSERT INTO `contributions` VALUES (21, 'Some other Formidable Title', NULL, 3, 3, '4', 1, '2019-04-27 19:20:31', '2019-04-27 19:58:51');
INSERT INTO `contributions` VALUES (28, 'A test title', 'Con_28_DMFR---Setup-and-Services-Only---Quote-#25.pdf', 3, 3, '4', 1, '2019-04-27 19:56:56', '2019-04-27 19:58:20');
INSERT INTO `contributions` VALUES (29, 'c', 'Con_29_My-BD-Host---Quote-#25.pdf', 3, 3, '4', 1, '2019-04-27 20:16:17', '2019-04-28 06:22:29');
INSERT INTO `contributions` VALUES (30, 'c', 'Con_30_My-BD-Host---Quote-#25.pdf', 3, 3, '4', 1, '2019-04-27 20:17:10', '2019-04-27 21:10:38');
INSERT INTO `contributions` VALUES (31, 'some random Title', 'Con_31_Web-Site-Correction-process.docx', 6, 3, '4', 1, '2019-04-27 20:24:54', '2019-04-27 22:48:28');
INSERT INTO `contributions` VALUES (32, 'jgfgh jhgh', 'Con_32_Web-Site-Correction-process.docx', 6, 3, '4', 1, '2019-04-27 20:44:41', '2019-04-27 21:13:56');
INSERT INTO `contributions` VALUES (33, 'jgfgh jhgh hvhg', 'Con_33_Web-Site-Correction-process.docx', 6, 3, '4', 1, '2019-04-27 20:45:09', '2019-04-28 06:15:46');
INSERT INTO `contributions` VALUES (34, 'ghjghhjg', 'Con_34_Web-Site-Correction-process.docx', 6, 3, '4', 1, '2019-04-27 20:46:04', '2019-04-27 21:00:04');
INSERT INTO `contributions` VALUES (35, 'dfsdfsdf', 'Con_35_Web-Site-Correction-process.docx', 6, 4, '4', 1, '2019-04-27 20:49:58', '2019-04-28 03:34:29');
INSERT INTO `contributions` VALUES (37, 'A Really Formidable Title', 'Con_37_Web-Site-Correction-process.docx', 6, 3, '4', 1, '2019-04-28 06:51:45', '2019-04-28 07:08:16');
INSERT INTO `contributions` VALUES (38, 'Some Other Awesome Title', 'Con_38_Web-Site-Correction-process.docx', 6, 4, '2', 1, '2019-04-28 06:52:03', '2019-04-28 07:21:02');
INSERT INTO `contributions` VALUES (39, 'Save the tree', NULL, 6, 3, '1', 1, '2019-04-28 07:09:41', '2019-04-28 07:18:47');
INSERT INTO `contributions` VALUES (40, 'some awesome title', NULL, 5, 4, '5', 1, '2019-04-28 08:34:49', '2019-04-29 05:02:22');
INSERT INTO `contributions` VALUES (41, 'Some other Awesome title', NULL, 3, 4, '5', 1, '2019-04-29 04:57:47', '2019-04-29 05:02:22');

-- ----------------------------
-- Table structure for departments
-- ----------------------------
DROP TABLE IF EXISTS `departments`;
CREATE TABLE `departments`  (
  `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `created_at` timestamp(0) NULL DEFAULT NULL,
  `updated_at` timestamp(0) NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 3 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = Compact;

-- ----------------------------
-- Records of departments
-- ----------------------------
INSERT INTO `departments` VALUES (1, 'EEE', '2019-02-26 05:42:49', '2019-04-28 06:20:18');
INSERT INTO `departments` VALUES (2, 'CSE', '2019-03-07 17:11:42', '2019-03-07 17:11:42');

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
  `created_at` timestamp(0) NULL DEFAULT NULL,
  `updated_at` timestamp(0) NULL DEFAULT NULL,
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
) ENGINE = InnoDB AUTO_INCREMENT = 9 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Compact;

-- ----------------------------
-- Records of users
-- ----------------------------
INSERT INTO `users` VALUES (1, 'shakib mir', 'shakib.mir@gmail.com', NULL, '$2y$10$ghQFdLn.frTZSXe5l.LpPOQgQ80/CCaET5L1pqw1eYM1VPt04dKPm', '5', 1, 'jdocJ9Ltt7lqIwJPuVQwE58UiTjaXYDWep1ESxnXrMyV2Hjfz5VTcR08S0QY', '2019-02-17 10:44:48', '2019-03-16 22:27:36');
INSERT INTO `users` VALUES (2, 'Mir', 'mir@mspagol.com', NULL, '$2y$10$FJlwuB/ubY1ju61rvt/YauPdmowzZJ1jwovA9DtrMQn0JZ8ezRvv2', '4', 1, 'sJrccuHciQH7bnRoii26WxTfSBUxwlgTF1Vgx2dqvskPcd7E37BfRbSHh1vY', '2019-02-17 11:47:57', '2019-02-17 11:47:57');
INSERT INTO `users` VALUES (3, 'Kamran Hossain', '1000035@daffodil.ac', NULL, '$2y$10$jOfIOeuuEVCFOytEgmujPOeuttNzV3GDZP02iTyQioqtSU/lgon/a', '2', 1, 'jyspRmpUy5n7fNp9pkIV1UOHy0KSzwnEotkNomOoAS6r5GPwOiviMSBL6oc7', '2019-02-26 11:21:00', '2019-04-28 06:01:33');
INSERT INTO `users` VALUES (4, 'Porag Modak', 'poragmodak10@gmail.com', NULL, '$2y$10$MPT2b9cdhYZyNi6dcDiHtuUXBgl2KTBDgAD4rS2VNRMk7JXb2v/du', '2', 2, 'ZcgYz09GElz4RXTOx2sW1ibqIO9UDoleJIdzBKeWytSEWea1MnIKWfiGqONn', '2019-02-26 11:27:54', '2019-02-26 11:27:54');
INSERT INTO `users` VALUES (5, 'Ruhul Amin', 'ruhul2084@mag.test', NULL, '$2y$10$d4YNerTwOvYYaoikstdO3eb/mONR1FXW5FN1TGYQDVWK9/BbzOHUi', '2', 1, NULL, '2019-03-16 23:13:30', '2019-03-16 23:13:30');
INSERT INTO `users` VALUES (6, 'Ruhul Ahmed', 'ruhul20@mag.test', NULL, '$2y$10$SAl2LOi40SvyzxPE460hXe5M2MmI/g.i4Ak3KZh2weoaTR9JCS36W', '2', 1, 'r1ziMyIRQpdzjrdJUn0OqOmKPreo3cvqLevV93pI3ii4FHGzYcsgaBtRyNIm', '2019-03-16 23:14:15', '2019-03-16 23:14:15');
INSERT INTO `users` VALUES (7, 'Mrs. Anderson', 'mominulislamrubel@gmail.com', NULL, '$2y$10$gxHmMpT89rBmImTGRO5O3uWGSRFwSUv2X9m12MKAzrjR5VBsm5Fpm', '3', 1, '0cs0kiOYPTtmtcJzZa7mAro8FeqJx194Pa8YhLmqJmSlLZpJFHD425xPgx5b', '2019-04-27 20:10:45', '2019-04-28 03:50:45');
INSERT INTO `users` VALUES (8, 'Mominul Islam Rubel', 'mominul@tunedesk.com', NULL, '$2y$10$bRh1We4D5I9TLzOvbxCVHO71rTUdAJZdcoP4jKmmm3WpudltMj9xm', '1', 1, 'KdD0KqEn1waaX6vVFOcEBCzos21ZceccdwnAyAUV2zLPnZIIgyEsIemvQeUg', '2019-04-28 04:33:42', '2019-04-28 04:33:42');

SET FOREIGN_KEY_CHECKS = 1;
