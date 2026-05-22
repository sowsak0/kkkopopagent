/*
 Navicat Premium Data Transfer

 Source Server         : 127.0.0.1
 Source Server Type    : MariaDB
 Source Server Version : 110806
 Source Host           : 127.0.0.1:3306
 Source Schema         : opod_agent

 Target Server Type    : MariaDB
 Target Server Version : 110806
 File Encoding         : 65001

 Date: 11/05/2026 23:03:04
*/

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

-- ----------------------------
-- Table structure for cache
-- ----------------------------
DROP TABLE IF EXISTS `cache`;
CREATE TABLE `cache`  (
  `key` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `value` mediumtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` int(11) NOT NULL,
  PRIMARY KEY (`key`) USING BTREE,
  INDEX `cache_expiration_index`(`expiration`) USING BTREE
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for cache_locks
-- ----------------------------
DROP TABLE IF EXISTS `cache_locks`;
CREATE TABLE `cache_locks`  (
  `key` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `owner` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` int(11) NOT NULL,
  PRIMARY KEY (`key`) USING BTREE,
  INDEX `cache_locks_expiration_index`(`expiration`) USING BTREE
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for failed_jobs
-- ----------------------------
DROP TABLE IF EXISTS `failed_jobs`;
CREATE TABLE `failed_jobs`  (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `uuid` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `connection` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `queue` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `exception` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `failed_at` timestamp(0) NOT NULL DEFAULT current_timestamp,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `failed_jobs_uuid_unique`(`uuid`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for job_batches
-- ----------------------------
DROP TABLE IF EXISTS `job_batches`;
CREATE TABLE `job_batches`  (
  `id` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `total_jobs` int(11) NOT NULL,
  `pending_jobs` int(11) NOT NULL,
  `failed_jobs` int(11) NOT NULL,
  `failed_job_ids` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `options` mediumtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `cancelled_at` int(11) NULL DEFAULT NULL,
  `created_at` int(11) NOT NULL,
  `finished_at` int(11) NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for jobs
-- ----------------------------
DROP TABLE IF EXISTS `jobs`;
CREATE TABLE `jobs`  (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `queue` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `attempts` tinyint(3) UNSIGNED NOT NULL,
  `reserved_at` int(10) UNSIGNED NULL DEFAULT NULL,
  `available_at` int(10) UNSIGNED NOT NULL,
  `created_at` int(10) UNSIGNED NOT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `jobs_queue_index`(`queue`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for lookup_hospcode
-- ----------------------------
DROP TABLE IF EXISTS `lookup_hospcode`;
CREATE TABLE `lookup_hospcode`  (
  `hospcode` varchar(9) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NOT NULL,
  `hospcode_name` varchar(100) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NULL DEFAULT NULL,
  `hmain_ucs` varchar(1) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `hmain_sss` varchar(1) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `in_province` varchar(1) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `created_at` timestamp(0) NULL DEFAULT NULL,
  `updated_at` timestamp(0) NULL DEFAULT NULL,
  PRIMARY KEY (`hospcode`) USING BTREE,
  INDEX `hospcode`(`hospcode`) USING BTREE
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for lookup_icd10
-- ----------------------------
DROP TABLE IF EXISTS `lookup_icd10`;
CREATE TABLE `lookup_icd10`  (
  `icd10` varchar(100) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NOT NULL,
  `pp` varchar(1) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `ods` varchar(1) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  PRIMARY KEY (`icd10`) USING BTREE,
  INDEX `icd10`(`icd10`) USING BTREE
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of lookup_icd10
-- ----------------------------
INSERT INTO `lookup_icd10` VALUES ('C15', '', 'Y');
INSERT INTO `lookup_icd10` VALUES ('C16', '', 'Y');
INSERT INTO `lookup_icd10` VALUES ('C221', '', 'Y');
INSERT INTO `lookup_icd10` VALUES ('C23', '', 'Y');
INSERT INTO `lookup_icd10` VALUES ('C24', '', 'Y');
INSERT INTO `lookup_icd10` VALUES ('C25', '', 'Y');
INSERT INTO `lookup_icd10` VALUES ('D126', '', 'Y');
INSERT INTO `lookup_icd10` VALUES ('H110', '', 'Y');
INSERT INTO `lookup_icd10` VALUES ('K600', NULL, 'Y');
INSERT INTO `lookup_icd10` VALUES ('K601', NULL, 'Y');
INSERT INTO `lookup_icd10` VALUES ('K602', NULL, 'Y');
INSERT INTO `lookup_icd10` VALUES ('K603', NULL, 'Y');
INSERT INTO `lookup_icd10` VALUES ('K610', NULL, 'Y');
INSERT INTO `lookup_icd10` VALUES ('K611', NULL, 'Y');
INSERT INTO `lookup_icd10` VALUES ('K612', NULL, 'Y');
INSERT INTO `lookup_icd10` VALUES ('K613', NULL, 'Y');
INSERT INTO `lookup_icd10` VALUES ('K614', NULL, 'Y');
INSERT INTO `lookup_icd10` VALUES ('K620', NULL, 'Y');
INSERT INTO `lookup_icd10` VALUES ('K621', NULL, 'Y');
INSERT INTO `lookup_icd10` VALUES ('K635', NULL, 'Y');
INSERT INTO `lookup_icd10` VALUES ('K800', NULL, 'Y');
INSERT INTO `lookup_icd10` VALUES ('K801', NULL, 'Y');
INSERT INTO `lookup_icd10` VALUES ('K802', NULL, 'Y');
INSERT INTO `lookup_icd10` VALUES ('K803', NULL, 'Y');
INSERT INTO `lookup_icd10` VALUES ('K804', NULL, 'Y');
INSERT INTO `lookup_icd10` VALUES ('K805', NULL, 'Y');
INSERT INTO `lookup_icd10` VALUES ('K820', NULL, 'Y');
INSERT INTO `lookup_icd10` VALUES ('K828', NULL, 'Y');
INSERT INTO `lookup_icd10` VALUES ('K831', NULL, 'Y');
INSERT INTO `lookup_icd10` VALUES ('K838', NULL, 'Y');
INSERT INTO `lookup_icd10` VALUES ('K860', NULL, 'Y');
INSERT INTO `lookup_icd10` VALUES ('K861', NULL, 'Y');
INSERT INTO `lookup_icd10` VALUES ('K868', NULL, 'Y');
INSERT INTO `lookup_icd10` VALUES ('K918', NULL, 'Y');
INSERT INTO `lookup_icd10` VALUES ('l850', NULL, 'Y');
INSERT INTO `lookup_icd10` VALUES ('l859', NULL, 'Y');
INSERT INTO `lookup_icd10` VALUES ('l864', NULL, 'Y');
INSERT INTO `lookup_icd10` VALUES ('l982', NULL, 'Y');
INSERT INTO `lookup_icd10` VALUES ('l983', NULL, 'Y');
INSERT INTO `lookup_icd10` VALUES ('N211', NULL, 'Y');
INSERT INTO `lookup_icd10` VALUES ('N350', NULL, 'Y');
INSERT INTO `lookup_icd10` VALUES ('N351', NULL, 'Y');
INSERT INTO `lookup_icd10` VALUES ('N358', NULL, 'Y');
INSERT INTO `lookup_icd10` VALUES ('N359', NULL, 'Y');
INSERT INTO `lookup_icd10` VALUES ('N61', NULL, 'Y');
INSERT INTO `lookup_icd10` VALUES ('S421', NULL, 'Y');
INSERT INTO `lookup_icd10` VALUES ('S422', NULL, 'Y');
INSERT INTO `lookup_icd10` VALUES ('S423', NULL, 'Y');
INSERT INTO `lookup_icd10` VALUES ('S424', NULL, 'Y');
INSERT INTO `lookup_icd10` VALUES ('S427', NULL, 'Y');
INSERT INTO `lookup_icd10` VALUES ('S428', NULL, 'Y');
INSERT INTO `lookup_icd10` VALUES ('S429', NULL, 'Y');
INSERT INTO `lookup_icd10` VALUES ('S520', NULL, 'Y');
INSERT INTO `lookup_icd10` VALUES ('S521', NULL, 'Y');
INSERT INTO `lookup_icd10` VALUES ('S522', NULL, 'Y');
INSERT INTO `lookup_icd10` VALUES ('S523', NULL, 'Y');
INSERT INTO `lookup_icd10` VALUES ('S524', NULL, 'Y');
INSERT INTO `lookup_icd10` VALUES ('S525', NULL, 'Y');
INSERT INTO `lookup_icd10` VALUES ('S526', NULL, 'Y');
INSERT INTO `lookup_icd10` VALUES ('S527', NULL, 'Y');
INSERT INTO `lookup_icd10` VALUES ('S528', NULL, 'Y');
INSERT INTO `lookup_icd10` VALUES ('S529', NULL, 'Y');
INSERT INTO `lookup_icd10` VALUES ('S620', NULL, 'Y');
INSERT INTO `lookup_icd10` VALUES ('S621', NULL, 'Y');
INSERT INTO `lookup_icd10` VALUES ('S624', NULL, 'Y');
INSERT INTO `lookup_icd10` VALUES ('S627', NULL, 'Y');
INSERT INTO `lookup_icd10` VALUES ('S820', NULL, 'Y');
INSERT INTO `lookup_icd10` VALUES ('S821', NULL, 'Y');
INSERT INTO `lookup_icd10` VALUES ('S822', NULL, 'Y');
INSERT INTO `lookup_icd10` VALUES ('S823', NULL, 'Y');
INSERT INTO `lookup_icd10` VALUES ('S824', NULL, 'Y');
INSERT INTO `lookup_icd10` VALUES ('S825', NULL, 'Y');
INSERT INTO `lookup_icd10` VALUES ('S826', NULL, 'Y');
INSERT INTO `lookup_icd10` VALUES ('S827', NULL, 'Y');
INSERT INTO `lookup_icd10` VALUES ('S828', NULL, 'Y');
INSERT INTO `lookup_icd10` VALUES ('S829', NULL, 'Y');
INSERT INTO `lookup_icd10` VALUES ('S920', NULL, 'Y');
INSERT INTO `lookup_icd10` VALUES ('S921', NULL, 'Y');
INSERT INTO `lookup_icd10` VALUES ('S922', NULL, 'Y');
INSERT INTO `lookup_icd10` VALUES ('S927', NULL, 'Y');
INSERT INTO `lookup_icd10` VALUES ('T181', NULL, 'Y');
INSERT INTO `lookup_icd10` VALUES ('T182', NULL, 'Y');
INSERT INTO `lookup_icd10` VALUES ('Z00', 'Y', NULL);
INSERT INTO `lookup_icd10` VALUES ('Z000', 'Y', NULL);
INSERT INTO `lookup_icd10` VALUES ('Z001', 'Y', NULL);
INSERT INTO `lookup_icd10` VALUES ('Z002', 'Y', NULL);
INSERT INTO `lookup_icd10` VALUES ('Z003', 'Y', NULL);
INSERT INTO `lookup_icd10` VALUES ('Z004', 'Y', NULL);
INSERT INTO `lookup_icd10` VALUES ('Z005', 'Y', NULL);
INSERT INTO `lookup_icd10` VALUES ('Z006', 'Y', NULL);
INSERT INTO `lookup_icd10` VALUES ('Z008', 'Y', NULL);
INSERT INTO `lookup_icd10` VALUES ('Z01', 'Y', NULL);
INSERT INTO `lookup_icd10` VALUES ('Z010', 'Y', NULL);
INSERT INTO `lookup_icd10` VALUES ('Z011', 'Y', NULL);
INSERT INTO `lookup_icd10` VALUES ('Z013', 'Y', NULL);
INSERT INTO `lookup_icd10` VALUES ('Z014', 'Y', NULL);
INSERT INTO `lookup_icd10` VALUES ('Z015', 'Y', NULL);
INSERT INTO `lookup_icd10` VALUES ('Z016', 'Y', NULL);
INSERT INTO `lookup_icd10` VALUES ('Z017', 'Y', NULL);
INSERT INTO `lookup_icd10` VALUES ('Z018', 'Y', NULL);
INSERT INTO `lookup_icd10` VALUES ('Z019', 'Y', NULL);
INSERT INTO `lookup_icd10` VALUES ('Z02', 'Y', NULL);
INSERT INTO `lookup_icd10` VALUES ('Z020', 'Y', NULL);
INSERT INTO `lookup_icd10` VALUES ('Z021', 'Y', NULL);
INSERT INTO `lookup_icd10` VALUES ('Z022', 'Y', NULL);
INSERT INTO `lookup_icd10` VALUES ('Z023', 'Y', NULL);
INSERT INTO `lookup_icd10` VALUES ('Z024', 'Y', NULL);
INSERT INTO `lookup_icd10` VALUES ('Z025', 'Y', NULL);
INSERT INTO `lookup_icd10` VALUES ('Z026', 'Y', NULL);
INSERT INTO `lookup_icd10` VALUES ('Z027', 'Y', NULL);
INSERT INTO `lookup_icd10` VALUES ('Z028', 'Y', NULL);
INSERT INTO `lookup_icd10` VALUES ('Z029', 'Y', NULL);
INSERT INTO `lookup_icd10` VALUES ('Z03', 'Y', NULL);
INSERT INTO `lookup_icd10` VALUES ('Z030', 'Y', NULL);
INSERT INTO `lookup_icd10` VALUES ('Z031', 'Y', NULL);
INSERT INTO `lookup_icd10` VALUES ('Z032', 'Y', NULL);
INSERT INTO `lookup_icd10` VALUES ('Z033', 'Y', NULL);
INSERT INTO `lookup_icd10` VALUES ('Z034', 'Y', NULL);
INSERT INTO `lookup_icd10` VALUES ('Z035', 'Y', NULL);
INSERT INTO `lookup_icd10` VALUES ('Z036', 'Y', NULL);
INSERT INTO `lookup_icd10` VALUES ('Z038', 'Y', NULL);
INSERT INTO `lookup_icd10` VALUES ('Z039', 'Y', NULL);
INSERT INTO `lookup_icd10` VALUES ('Z10', 'Y', NULL);
INSERT INTO `lookup_icd10` VALUES ('Z100', 'Y', NULL);
INSERT INTO `lookup_icd10` VALUES ('Z101', 'Y', NULL);
INSERT INTO `lookup_icd10` VALUES ('Z102', 'Y', NULL);
INSERT INTO `lookup_icd10` VALUES ('Z103', 'Y', NULL);
INSERT INTO `lookup_icd10` VALUES ('Z108', 'Y', NULL);
INSERT INTO `lookup_icd10` VALUES ('Z11', 'Y', NULL);
INSERT INTO `lookup_icd10` VALUES ('Z110', 'Y', NULL);
INSERT INTO `lookup_icd10` VALUES ('Z111', 'Y', NULL);
INSERT INTO `lookup_icd10` VALUES ('Z112', 'Y', NULL);
INSERT INTO `lookup_icd10` VALUES ('Z113', 'Y', NULL);
INSERT INTO `lookup_icd10` VALUES ('Z114', 'Y', NULL);
INSERT INTO `lookup_icd10` VALUES ('Z115', 'Y', NULL);
INSERT INTO `lookup_icd10` VALUES ('Z116', 'Y', NULL);
INSERT INTO `lookup_icd10` VALUES ('Z118', 'Y', NULL);
INSERT INTO `lookup_icd10` VALUES ('Z119', 'Y', NULL);
INSERT INTO `lookup_icd10` VALUES ('Z12', 'Y', NULL);
INSERT INTO `lookup_icd10` VALUES ('Z120', 'Y', NULL);
INSERT INTO `lookup_icd10` VALUES ('Z121', 'Y', NULL);
INSERT INTO `lookup_icd10` VALUES ('Z122', 'Y', NULL);
INSERT INTO `lookup_icd10` VALUES ('Z123', 'Y', NULL);
INSERT INTO `lookup_icd10` VALUES ('Z124', 'Y', NULL);
INSERT INTO `lookup_icd10` VALUES ('Z125', 'Y', NULL);
INSERT INTO `lookup_icd10` VALUES ('Z126', 'Y', NULL);
INSERT INTO `lookup_icd10` VALUES ('Z128', 'Y', NULL);
INSERT INTO `lookup_icd10` VALUES ('Z129', 'Y', NULL);
INSERT INTO `lookup_icd10` VALUES ('Z13', 'Y', NULL);
INSERT INTO `lookup_icd10` VALUES ('Z130', 'Y', NULL);
INSERT INTO `lookup_icd10` VALUES ('Z131', 'Y', NULL);
INSERT INTO `lookup_icd10` VALUES ('Z132', 'Y', NULL);
INSERT INTO `lookup_icd10` VALUES ('Z133', 'Y', NULL);
INSERT INTO `lookup_icd10` VALUES ('Z134', 'Y', NULL);
INSERT INTO `lookup_icd10` VALUES ('Z135', 'Y', NULL);
INSERT INTO `lookup_icd10` VALUES ('Z136', 'Y', NULL);
INSERT INTO `lookup_icd10` VALUES ('Z137', 'Y', NULL);
INSERT INTO `lookup_icd10` VALUES ('Z138', 'Y', NULL);
INSERT INTO `lookup_icd10` VALUES ('Z139', 'Y', NULL);
INSERT INTO `lookup_icd10` VALUES ('Z20', 'Y', NULL);
INSERT INTO `lookup_icd10` VALUES ('Z200', 'Y', NULL);
INSERT INTO `lookup_icd10` VALUES ('Z201', 'Y', NULL);
INSERT INTO `lookup_icd10` VALUES ('Z202', 'Y', NULL);
INSERT INTO `lookup_icd10` VALUES ('Z204', 'Y', NULL);
INSERT INTO `lookup_icd10` VALUES ('Z205', 'Y', NULL);
INSERT INTO `lookup_icd10` VALUES ('Z206', 'Y', NULL);
INSERT INTO `lookup_icd10` VALUES ('Z207', 'Y', NULL);
INSERT INTO `lookup_icd10` VALUES ('Z208', 'Y', NULL);
INSERT INTO `lookup_icd10` VALUES ('Z209', 'Y', NULL);
INSERT INTO `lookup_icd10` VALUES ('Z23', 'Y', NULL);
INSERT INTO `lookup_icd10` VALUES ('Z230', 'Y', NULL);
INSERT INTO `lookup_icd10` VALUES ('Z231', 'Y', NULL);
INSERT INTO `lookup_icd10` VALUES ('Z232', 'Y', NULL);
INSERT INTO `lookup_icd10` VALUES ('Z233', 'Y', NULL);
INSERT INTO `lookup_icd10` VALUES ('Z234', 'Y', NULL);
INSERT INTO `lookup_icd10` VALUES ('Z235', 'Y', NULL);
INSERT INTO `lookup_icd10` VALUES ('Z236', 'Y', NULL);
INSERT INTO `lookup_icd10` VALUES ('Z237', 'Y', NULL);
INSERT INTO `lookup_icd10` VALUES ('Z238', 'Y', NULL);
INSERT INTO `lookup_icd10` VALUES ('Z24', 'Y', NULL);
INSERT INTO `lookup_icd10` VALUES ('Z240', 'Y', NULL);
INSERT INTO `lookup_icd10` VALUES ('Z241', 'Y', NULL);
INSERT INTO `lookup_icd10` VALUES ('Z243', 'Y', NULL);
INSERT INTO `lookup_icd10` VALUES ('Z244', 'Y', NULL);
INSERT INTO `lookup_icd10` VALUES ('Z245', 'Y', NULL);
INSERT INTO `lookup_icd10` VALUES ('Z246', 'Y', NULL);
INSERT INTO `lookup_icd10` VALUES ('Z25', 'Y', NULL);
INSERT INTO `lookup_icd10` VALUES ('Z250', 'Y', NULL);
INSERT INTO `lookup_icd10` VALUES ('Z251', 'Y', NULL);
INSERT INTO `lookup_icd10` VALUES ('Z258', 'Y', NULL);
INSERT INTO `lookup_icd10` VALUES ('Z26', 'Y', NULL);
INSERT INTO `lookup_icd10` VALUES ('Z260', 'Y', NULL);
INSERT INTO `lookup_icd10` VALUES ('Z268', 'Y', NULL);
INSERT INTO `lookup_icd10` VALUES ('Z269', 'Y', NULL);
INSERT INTO `lookup_icd10` VALUES ('Z27', 'Y', NULL);
INSERT INTO `lookup_icd10` VALUES ('Z270', 'Y', NULL);
INSERT INTO `lookup_icd10` VALUES ('Z271', 'Y', NULL);
INSERT INTO `lookup_icd10` VALUES ('Z272', 'Y', NULL);
INSERT INTO `lookup_icd10` VALUES ('Z273', 'Y', NULL);
INSERT INTO `lookup_icd10` VALUES ('Z274', 'Y', NULL);
INSERT INTO `lookup_icd10` VALUES ('Z278', 'Y', NULL);
INSERT INTO `lookup_icd10` VALUES ('Z279', 'Y', NULL);
INSERT INTO `lookup_icd10` VALUES ('Z28', 'Y', NULL);
INSERT INTO `lookup_icd10` VALUES ('Z280', 'Y', NULL);
INSERT INTO `lookup_icd10` VALUES ('Z281', 'Y', NULL);
INSERT INTO `lookup_icd10` VALUES ('Z282', 'Y', NULL);
INSERT INTO `lookup_icd10` VALUES ('Z288', 'Y', NULL);
INSERT INTO `lookup_icd10` VALUES ('Z289', 'Y', NULL);
INSERT INTO `lookup_icd10` VALUES ('Z29', 'Y', NULL);
INSERT INTO `lookup_icd10` VALUES ('Z291', 'Y', NULL);
INSERT INTO `lookup_icd10` VALUES ('Z292', 'Y', NULL);
INSERT INTO `lookup_icd10` VALUES ('Z298', 'Y', NULL);
INSERT INTO `lookup_icd10` VALUES ('Z299', 'Y', NULL);
INSERT INTO `lookup_icd10` VALUES ('Z30', 'Y', NULL);
INSERT INTO `lookup_icd10` VALUES ('Z300', 'Y', NULL);
INSERT INTO `lookup_icd10` VALUES ('Z301', 'Y', NULL);
INSERT INTO `lookup_icd10` VALUES ('Z302', 'Y', 'Y');
INSERT INTO `lookup_icd10` VALUES ('Z303', 'Y', NULL);
INSERT INTO `lookup_icd10` VALUES ('Z304', 'Y', NULL);
INSERT INTO `lookup_icd10` VALUES ('Z305', 'Y', NULL);
INSERT INTO `lookup_icd10` VALUES ('Z308', 'Y', NULL);
INSERT INTO `lookup_icd10` VALUES ('Z309', 'Y', NULL);
INSERT INTO `lookup_icd10` VALUES ('Z32', 'Y', NULL);
INSERT INTO `lookup_icd10` VALUES ('Z320', 'Y', NULL);
INSERT INTO `lookup_icd10` VALUES ('Z321', 'Y', NULL);
INSERT INTO `lookup_icd10` VALUES ('Z34', 'Y', NULL);
INSERT INTO `lookup_icd10` VALUES ('Z340', 'Y', NULL);
INSERT INTO `lookup_icd10` VALUES ('Z348', 'Y', NULL);
INSERT INTO `lookup_icd10` VALUES ('Z349', 'Y', NULL);
INSERT INTO `lookup_icd10` VALUES ('Z35', 'Y', NULL);
INSERT INTO `lookup_icd10` VALUES ('Z350', 'Y', NULL);
INSERT INTO `lookup_icd10` VALUES ('Z351', 'Y', NULL);
INSERT INTO `lookup_icd10` VALUES ('Z352', 'Y', NULL);
INSERT INTO `lookup_icd10` VALUES ('Z353', 'Y', NULL);
INSERT INTO `lookup_icd10` VALUES ('Z354', 'Y', NULL);
INSERT INTO `lookup_icd10` VALUES ('Z355', 'Y', NULL);
INSERT INTO `lookup_icd10` VALUES ('Z356', 'Y', NULL);
INSERT INTO `lookup_icd10` VALUES ('Z357', 'Y', NULL);
INSERT INTO `lookup_icd10` VALUES ('Z358', 'Y', NULL);
INSERT INTO `lookup_icd10` VALUES ('Z359', 'Y', NULL);
INSERT INTO `lookup_icd10` VALUES ('Z36', 'Y', NULL);
INSERT INTO `lookup_icd10` VALUES ('Z360', 'Y', NULL);
INSERT INTO `lookup_icd10` VALUES ('Z361', 'Y', NULL);
INSERT INTO `lookup_icd10` VALUES ('Z362', 'Y', NULL);
INSERT INTO `lookup_icd10` VALUES ('Z363', 'Y', NULL);
INSERT INTO `lookup_icd10` VALUES ('Z364', 'Y', NULL);
INSERT INTO `lookup_icd10` VALUES ('Z365', 'Y', NULL);
INSERT INTO `lookup_icd10` VALUES ('Z368', 'Y', NULL);
INSERT INTO `lookup_icd10` VALUES ('Z369', 'Y', NULL);
INSERT INTO `lookup_icd10` VALUES ('Z39', 'Y', NULL);
INSERT INTO `lookup_icd10` VALUES ('Z390', 'Y', NULL);
INSERT INTO `lookup_icd10` VALUES ('Z391', 'Y', NULL);
INSERT INTO `lookup_icd10` VALUES ('Z392', 'Y', NULL);
INSERT INTO `lookup_icd10` VALUES ('Z55', 'Y', NULL);
INSERT INTO `lookup_icd10` VALUES ('Z550', 'Y', NULL);
INSERT INTO `lookup_icd10` VALUES ('Z551', 'Y', NULL);
INSERT INTO `lookup_icd10` VALUES ('Z552', 'Y', NULL);
INSERT INTO `lookup_icd10` VALUES ('Z553', 'Y', NULL);
INSERT INTO `lookup_icd10` VALUES ('Z554', 'Y', NULL);
INSERT INTO `lookup_icd10` VALUES ('Z558', 'Y', NULL);
INSERT INTO `lookup_icd10` VALUES ('Z559', 'Y', NULL);
INSERT INTO `lookup_icd10` VALUES ('Z56', 'Y', NULL);
INSERT INTO `lookup_icd10` VALUES ('Z560', 'Y', NULL);
INSERT INTO `lookup_icd10` VALUES ('Z561', 'Y', NULL);
INSERT INTO `lookup_icd10` VALUES ('Z562', 'Y', NULL);
INSERT INTO `lookup_icd10` VALUES ('Z563', 'Y', NULL);
INSERT INTO `lookup_icd10` VALUES ('Z564', 'Y', NULL);
INSERT INTO `lookup_icd10` VALUES ('Z565', 'Y', NULL);
INSERT INTO `lookup_icd10` VALUES ('Z566', 'Y', NULL);
INSERT INTO `lookup_icd10` VALUES ('Z567', 'Y', NULL);
INSERT INTO `lookup_icd10` VALUES ('Z57', 'Y', NULL);
INSERT INTO `lookup_icd10` VALUES ('Z570', 'Y', NULL);
INSERT INTO `lookup_icd10` VALUES ('Z571', 'Y', NULL);
INSERT INTO `lookup_icd10` VALUES ('Z572', 'Y', NULL);
INSERT INTO `lookup_icd10` VALUES ('Z573', 'Y', NULL);
INSERT INTO `lookup_icd10` VALUES ('Z574', 'Y', NULL);
INSERT INTO `lookup_icd10` VALUES ('Z575', 'Y', NULL);
INSERT INTO `lookup_icd10` VALUES ('Z576', 'Y', NULL);
INSERT INTO `lookup_icd10` VALUES ('Z577', 'Y', NULL);
INSERT INTO `lookup_icd10` VALUES ('Z578', 'Y', NULL);
INSERT INTO `lookup_icd10` VALUES ('Z579', 'Y', NULL);
INSERT INTO `lookup_icd10` VALUES ('Z58', 'Y', NULL);
INSERT INTO `lookup_icd10` VALUES ('Z580', 'Y', NULL);
INSERT INTO `lookup_icd10` VALUES ('Z581', 'Y', NULL);
INSERT INTO `lookup_icd10` VALUES ('Z582', 'Y', NULL);
INSERT INTO `lookup_icd10` VALUES ('Z583', 'Y', NULL);
INSERT INTO `lookup_icd10` VALUES ('Z584', 'Y', NULL);
INSERT INTO `lookup_icd10` VALUES ('Z585', 'Y', NULL);
INSERT INTO `lookup_icd10` VALUES ('Z586', 'Y', NULL);
INSERT INTO `lookup_icd10` VALUES ('Z587', 'Y', NULL);
INSERT INTO `lookup_icd10` VALUES ('Z588', 'Y', NULL);
INSERT INTO `lookup_icd10` VALUES ('Z589', 'Y', NULL);
INSERT INTO `lookup_icd10` VALUES ('Z59', 'Y', NULL);
INSERT INTO `lookup_icd10` VALUES ('Z590', 'Y', NULL);
INSERT INTO `lookup_icd10` VALUES ('Z591', 'Y', NULL);
INSERT INTO `lookup_icd10` VALUES ('Z592', 'Y', NULL);
INSERT INTO `lookup_icd10` VALUES ('Z593', 'Y', NULL);
INSERT INTO `lookup_icd10` VALUES ('Z594', 'Y', NULL);
INSERT INTO `lookup_icd10` VALUES ('Z595', 'Y', NULL);
INSERT INTO `lookup_icd10` VALUES ('Z596', 'Y', NULL);
INSERT INTO `lookup_icd10` VALUES ('Z597', 'Y', NULL);
INSERT INTO `lookup_icd10` VALUES ('Z598', 'Y', NULL);
INSERT INTO `lookup_icd10` VALUES ('Z599', 'Y', NULL);
INSERT INTO `lookup_icd10` VALUES ('Z60', 'Y', NULL);
INSERT INTO `lookup_icd10` VALUES ('Z600', 'Y', NULL);
INSERT INTO `lookup_icd10` VALUES ('Z601', 'Y', NULL);
INSERT INTO `lookup_icd10` VALUES ('Z602', 'Y', NULL);
INSERT INTO `lookup_icd10` VALUES ('Z603', 'Y', NULL);
INSERT INTO `lookup_icd10` VALUES ('Z604', 'Y', NULL);
INSERT INTO `lookup_icd10` VALUES ('Z605', 'Y', NULL);
INSERT INTO `lookup_icd10` VALUES ('Z608', 'Y', NULL);
INSERT INTO `lookup_icd10` VALUES ('Z609', 'Y', NULL);
INSERT INTO `lookup_icd10` VALUES ('Z61', 'Y', NULL);
INSERT INTO `lookup_icd10` VALUES ('Z610', 'Y', NULL);
INSERT INTO `lookup_icd10` VALUES ('Z611', 'Y', NULL);
INSERT INTO `lookup_icd10` VALUES ('Z612', 'Y', NULL);
INSERT INTO `lookup_icd10` VALUES ('Z613', 'Y', NULL);
INSERT INTO `lookup_icd10` VALUES ('Z614', 'Y', NULL);
INSERT INTO `lookup_icd10` VALUES ('Z615', 'Y', NULL);
INSERT INTO `lookup_icd10` VALUES ('Z616', 'Y', NULL);
INSERT INTO `lookup_icd10` VALUES ('Z617', 'Y', NULL);
INSERT INTO `lookup_icd10` VALUES ('Z618', 'Y', NULL);
INSERT INTO `lookup_icd10` VALUES ('Z619', 'Y', NULL);
INSERT INTO `lookup_icd10` VALUES ('Z62', 'Y', NULL);
INSERT INTO `lookup_icd10` VALUES ('Z620', 'Y', NULL);
INSERT INTO `lookup_icd10` VALUES ('Z621', 'Y', NULL);
INSERT INTO `lookup_icd10` VALUES ('Z622', 'Y', NULL);
INSERT INTO `lookup_icd10` VALUES ('Z623', 'Y', NULL);
INSERT INTO `lookup_icd10` VALUES ('Z624', 'Y', NULL);
INSERT INTO `lookup_icd10` VALUES ('Z625', 'Y', NULL);
INSERT INTO `lookup_icd10` VALUES ('Z626', 'Y', NULL);
INSERT INTO `lookup_icd10` VALUES ('Z628', 'Y', NULL);
INSERT INTO `lookup_icd10` VALUES ('Z629', 'Y', NULL);
INSERT INTO `lookup_icd10` VALUES ('Z63', 'Y', NULL);
INSERT INTO `lookup_icd10` VALUES ('Z630', 'Y', NULL);
INSERT INTO `lookup_icd10` VALUES ('Z631', 'Y', NULL);
INSERT INTO `lookup_icd10` VALUES ('Z632', 'Y', NULL);
INSERT INTO `lookup_icd10` VALUES ('Z633', 'Y', NULL);
INSERT INTO `lookup_icd10` VALUES ('Z634', 'Y', NULL);
INSERT INTO `lookup_icd10` VALUES ('Z635', 'Y', NULL);
INSERT INTO `lookup_icd10` VALUES ('Z636', 'Y', NULL);
INSERT INTO `lookup_icd10` VALUES ('Z637', 'Y', NULL);
INSERT INTO `lookup_icd10` VALUES ('Z638', 'Y', NULL);
INSERT INTO `lookup_icd10` VALUES ('Z639', 'Y', NULL);
INSERT INTO `lookup_icd10` VALUES ('Z64', 'Y', NULL);
INSERT INTO `lookup_icd10` VALUES ('Z640', 'Y', NULL);
INSERT INTO `lookup_icd10` VALUES ('Z641', 'Y', NULL);
INSERT INTO `lookup_icd10` VALUES ('Z642', 'Y', NULL);
INSERT INTO `lookup_icd10` VALUES ('Z643', 'Y', NULL);
INSERT INTO `lookup_icd10` VALUES ('Z644', 'Y', NULL);
INSERT INTO `lookup_icd10` VALUES ('Z65', 'Y', NULL);
INSERT INTO `lookup_icd10` VALUES ('Z650', 'Y', NULL);
INSERT INTO `lookup_icd10` VALUES ('Z651', 'Y', NULL);
INSERT INTO `lookup_icd10` VALUES ('Z652', 'Y', NULL);
INSERT INTO `lookup_icd10` VALUES ('Z653', 'Y', NULL);
INSERT INTO `lookup_icd10` VALUES ('Z654', 'Y', NULL);
INSERT INTO `lookup_icd10` VALUES ('Z655', 'Y', NULL);
INSERT INTO `lookup_icd10` VALUES ('Z658', 'Y', NULL);
INSERT INTO `lookup_icd10` VALUES ('Z659', 'Y', NULL);
INSERT INTO `lookup_icd10` VALUES ('Z70', 'Y', NULL);
INSERT INTO `lookup_icd10` VALUES ('Z700', 'Y', NULL);
INSERT INTO `lookup_icd10` VALUES ('Z701', 'Y', NULL);
INSERT INTO `lookup_icd10` VALUES ('Z702', 'Y', NULL);
INSERT INTO `lookup_icd10` VALUES ('Z703', 'Y', NULL);
INSERT INTO `lookup_icd10` VALUES ('Z708', 'Y', NULL);
INSERT INTO `lookup_icd10` VALUES ('Z709', 'Y', NULL);
INSERT INTO `lookup_icd10` VALUES ('Z71', 'Y', NULL);
INSERT INTO `lookup_icd10` VALUES ('Z710', 'Y', NULL);
INSERT INTO `lookup_icd10` VALUES ('Z711', 'Y', NULL);
INSERT INTO `lookup_icd10` VALUES ('Z712', 'Y', NULL);
INSERT INTO `lookup_icd10` VALUES ('Z713', 'Y', NULL);
INSERT INTO `lookup_icd10` VALUES ('Z714', 'Y', NULL);
INSERT INTO `lookup_icd10` VALUES ('Z715', 'Y', NULL);
INSERT INTO `lookup_icd10` VALUES ('Z716', 'Y', NULL);
INSERT INTO `lookup_icd10` VALUES ('Z717', 'Y', NULL);
INSERT INTO `lookup_icd10` VALUES ('Z718', 'Y', NULL);
INSERT INTO `lookup_icd10` VALUES ('Z719', 'Y', NULL);
INSERT INTO `lookup_icd10` VALUES ('Z72', 'Y', NULL);
INSERT INTO `lookup_icd10` VALUES ('Z720', 'Y', NULL);
INSERT INTO `lookup_icd10` VALUES ('Z721', 'Y', NULL);
INSERT INTO `lookup_icd10` VALUES ('Z722', 'Y', NULL);
INSERT INTO `lookup_icd10` VALUES ('Z723', 'Y', NULL);
INSERT INTO `lookup_icd10` VALUES ('Z724', 'Y', NULL);
INSERT INTO `lookup_icd10` VALUES ('Z725', 'Y', NULL);
INSERT INTO `lookup_icd10` VALUES ('Z726', 'Y', NULL);
INSERT INTO `lookup_icd10` VALUES ('Z728', 'Y', NULL);
INSERT INTO `lookup_icd10` VALUES ('Z729', 'Y', NULL);
INSERT INTO `lookup_icd10` VALUES ('Z73', 'Y', NULL);
INSERT INTO `lookup_icd10` VALUES ('Z730', 'Y', NULL);
INSERT INTO `lookup_icd10` VALUES ('Z731', 'Y', NULL);
INSERT INTO `lookup_icd10` VALUES ('Z732', 'Y', NULL);
INSERT INTO `lookup_icd10` VALUES ('Z733', 'Y', NULL);
INSERT INTO `lookup_icd10` VALUES ('Z734', 'Y', NULL);
INSERT INTO `lookup_icd10` VALUES ('Z735', 'Y', NULL);
INSERT INTO `lookup_icd10` VALUES ('Z736', 'Y', NULL);
INSERT INTO `lookup_icd10` VALUES ('Z738', 'Y', NULL);
INSERT INTO `lookup_icd10` VALUES ('Z739', 'Y', NULL);
INSERT INTO `lookup_icd10` VALUES ('Z75', 'Y', NULL);
INSERT INTO `lookup_icd10` VALUES ('Z750', 'Y', NULL);
INSERT INTO `lookup_icd10` VALUES ('Z751', 'Y', NULL);
INSERT INTO `lookup_icd10` VALUES ('Z752', 'Y', NULL);
INSERT INTO `lookup_icd10` VALUES ('Z753', 'Y', NULL);
INSERT INTO `lookup_icd10` VALUES ('Z754', 'Y', NULL);
INSERT INTO `lookup_icd10` VALUES ('Z755', 'Y', NULL);
INSERT INTO `lookup_icd10` VALUES ('Z758', 'Y', NULL);
INSERT INTO `lookup_icd10` VALUES ('Z759', 'Y', NULL);

-- ----------------------------
-- Table structure for lookup_ward
-- ----------------------------
DROP TABLE IF EXISTS `lookup_ward`;
CREATE TABLE `lookup_ward`  (
  `ward` varchar(2) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `ward_name` varchar(100) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NULL DEFAULT NULL,
  `ward_normal` varchar(1) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `ward_m` varchar(1) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `ward_f` varchar(1) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NULL DEFAULT NULL,
  `ward_vip` varchar(1) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `ward_lr` varchar(1) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `ward_homeward` varchar(1) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `bed_qty` int(10) UNSIGNED NULL DEFAULT NULL,
  `created_at` timestamp(0) NULL DEFAULT NULL,
  `updated_at` timestamp(0) NULL DEFAULT NULL,
  PRIMARY KEY (`ward`) USING BTREE,
  INDEX `ward`(`ward`) USING BTREE
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for main_setting
-- ----------------------------
DROP TABLE IF EXISTS `main_setting`;
CREATE TABLE `main_setting`  (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `value` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `created_at` timestamp(0) NULL DEFAULT NULL,
  `updated_at` timestamp(0) NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `main_setting_name_unique`(`name`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 4 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of main_setting
-- ----------------------------
INSERT INTO `main_setting` VALUES (1, 'opoh_token', '', NULL, '2026-05-11 14:30:17');
INSERT INTO `main_setting` VALUES (2, 'opoh_url', 'http://127.0.0.1:8881/api', NULL, '2026-05-11 14:29:16');
INSERT INTO `main_setting` VALUES (3, 'bed_qty', '30', NULL, '2026-05-11 14:30:21');

-- ----------------------------
-- Table structure for migrations
-- ----------------------------
DROP TABLE IF EXISTS `migrations`;
CREATE TABLE `migrations`  (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `migration` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int(11) NOT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 12 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of migrations
-- ----------------------------
INSERT INTO `migrations` VALUES (6, '0001_01_01_000000_create_users_table', 1);
INSERT INTO `migrations` VALUES (7, '0001_01_01_000001_create_cache_table', 1);
INSERT INTO `migrations` VALUES (8, '0001_01_01_000002_create_jobs_table', 1);
INSERT INTO `migrations` VALUES (9, '2026_05_11_133840_create_main_setting_table', 1);
INSERT INTO `migrations` VALUES (10, '2026_05_11_134159_create_lookup_tables', 1);
INSERT INTO `migrations` VALUES (11, '2026_05_11_143148_create_personal_access_tokens_table', 2);

-- ----------------------------
-- Table structure for password_reset_tokens
-- ----------------------------
DROP TABLE IF EXISTS `password_reset_tokens`;
CREATE TABLE `password_reset_tokens`  (
  `email` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp(0) NULL DEFAULT NULL,
  PRIMARY KEY (`email`) USING BTREE
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for personal_access_tokens
-- ----------------------------
DROP TABLE IF EXISTS `personal_access_tokens`;
CREATE TABLE `personal_access_tokens`  (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `tokenable_type` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `tokenable_id` bigint(20) UNSIGNED NOT NULL,
  `name` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(64) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `abilities` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `last_used_at` timestamp(0) NULL DEFAULT NULL,
  `expires_at` timestamp(0) NULL DEFAULT NULL,
  `created_at` timestamp(0) NULL DEFAULT NULL,
  `updated_at` timestamp(0) NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `personal_access_tokens_token_unique`(`token`) USING BTREE,
  INDEX `personal_access_tokens_tokenable_type_tokenable_id_index`(`tokenable_type`, `tokenable_id`) USING BTREE,
  INDEX `personal_access_tokens_expires_at_index`(`expires_at`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for sessions
-- ----------------------------
DROP TABLE IF EXISTS `sessions`;
CREATE TABLE `sessions`  (
  `id` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` bigint(20) UNSIGNED NULL DEFAULT NULL,
  `ip_address` varchar(45) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `user_agent` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `payload` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `last_activity` int(11) NOT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `sessions_user_id_index`(`user_id`) USING BTREE,
  INDEX `sessions_last_activity_index`(`last_activity`) USING BTREE
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of sessions
-- ----------------------------
INSERT INTO `sessions` VALUES ('CrHX9EFtFf9g96Xlk4vZGa097I3NVgLND0GOL6M7', 1, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', 'YTo0OntzOjY6Il90b2tlbiI7czo0MDoidzhTNXpCeVRxYWpKZUxFSURIRm5LbHVyVlQ5UTRWQjRuZGhBZnMxUiI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MzY6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9hZG1pbi9zZXR0aW5ncyI7czo1OiJyb3V0ZSI7czoxNDoic2V0dGluZ3MuaW5kZXgiO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX1zOjUwOiJsb2dpbl93ZWJfNTliYTM2YWRkYzJiMmY5NDAxNTgwZjAxNGM3ZjU4ZWE0ZTMwOTg5ZCI7aToxO30=', 1778513323);
INSERT INTO `sessions` VALUES ('yXuPytP8yi9Tl7d1Y2WUxGKXfPfrxVfUL2HS40H3', 1, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', 'YTo0OntzOjY6Il90b2tlbiI7czo0MDoiWG1xclc4SUFXaXFiTm1abWVyekNZYVFGRmR6OTVUSlVPVVV4OFdYRCI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MzY6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9hZG1pbi9zZXR0aW5ncyI7czo1OiJyb3V0ZSI7czoxNDoic2V0dGluZ3MuaW5kZXgiO31zOjUwOiJsb2dpbl93ZWJfNTliYTM2YWRkYzJiMmY5NDAxNTgwZjAxNGM3ZjU4ZWE0ZTMwOTg5ZCI7aToxO30=', 1778515022);

-- ----------------------------
-- Table structure for users
-- ----------------------------
DROP TABLE IF EXISTS `users`;
CREATE TABLE `users`  (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `email_verified_at` timestamp(0) NULL DEFAULT NULL,
  `password` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `remember_token` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `created_at` timestamp(0) NULL DEFAULT NULL,
  `updated_at` timestamp(0) NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `users_email_unique`(`email`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 2 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of users
-- ----------------------------
INSERT INTO `users` VALUES (1, 'Admin', 'admin@gmail.com', NULL, '$2y$12$NBDT0W1IuJ6lC.RYIN1HvuAKJSI9CO9xx8KVRj5Y3Mokto2S8e1yq', NULL, '2026-05-11 13:47:11', '2026-05-11 13:47:11');

SET FOREIGN_KEY_CHECKS = 1;
