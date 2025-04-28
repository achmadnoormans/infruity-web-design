/*
 Navicat Premium Data Transfer

 Source Server         : localhost
 Source Server Type    : MySQL
 Source Server Version : 80030
 Source Host           : localhost:3306
 Source Schema         : pemkot

 Target Server Type    : MySQL
 Target Server Version : 80030
 File Encoding         : 65001

 Date: 16/10/2024 10:15:11
*/

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

-- ----------------------------
-- Table structure for m_layanan
-- ----------------------------
DROP TABLE IF EXISTS `m_layanan`;
CREATE TABLE `m_layanan`  (
  `id_layanan` int NOT NULL AUTO_INCREMENT COMMENT 'TRIAL',
  `nm_layanan` varchar(255) CHARACTER SET ascii COLLATE ascii_general_ci NULL DEFAULT NULL COMMENT 'TRIAL',
  `id_user` varchar(32) CHARACTER SET ascii COLLATE ascii_general_ci NOT NULL COMMENT 'TRIAL',
  `created_at` datetime NULL DEFAULT NULL COMMENT 'TRIAL',
  `updated_at` datetime NULL DEFAULT NULL COMMENT 'TRIAL',
  PRIMARY KEY (`id_layanan`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 8 CHARACTER SET = ascii COLLATE = ascii_general_ci COMMENT = 'TRIAL' ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of m_layanan
-- ----------------------------
INSERT INTO `m_layanan` VALUES (1, 'FOTO COPY IPT', '1', '2022-02-14 15:55:11', '2023-03-01 10:30:36');
INSERT INTO `m_layanan` VALUES (2, 'REKOM IKLAN MANDIRI', '1', '2022-02-14 15:55:49', '2023-03-01 10:30:48');
INSERT INTO `m_layanan` VALUES (3, 'BALIK NAMA MANDIRI', '1', '2022-02-14 15:56:01', '2023-03-01 10:30:54');
INSERT INTO `m_layanan` VALUES (4, 'REKOM IKLAN KOLEKTIF', '1', '2024-04-23 16:10:12', '2024-04-23 16:10:12');
INSERT INTO `m_layanan` VALUES (5, 'BALIK NAMA KOLEKTIF', '1', '2024-10-04 12:24:38', '2024-10-04 12:24:41');
INSERT INTO `m_layanan` VALUES (6, 'PERMOHONAN PENCABUTAN', '1', '2024-10-04 12:24:44', '2024-10-04 12:24:47');
INSERT INTO `m_layanan` VALUES (7, 'PERMOHONAN STATUS TANAH', '1', '2024-10-04 12:24:59', '2024-10-04 12:25:02');

-- ----------------------------
-- Table structure for m_layanan_doc
-- ----------------------------
DROP TABLE IF EXISTS `m_layanan_doc`;
CREATE TABLE `m_layanan_doc`  (
  `id` int NOT NULL AUTO_INCREMENT,
  `id_layanan` int NOT NULL,
  `nama_document` varchar(255) CHARACTER SET ascii COLLATE ascii_general_ci NOT NULL,
  `status` varchar(255) CHARACTER SET ascii COLLATE ascii_general_ci NULL DEFAULT NULL,
  `id_user` varchar(32) CHARACTER SET ascii COLLATE ascii_general_ci NOT NULL,
  `created_at` datetime NULL DEFAULT NULL,
  `updated_at` datetime NULL DEFAULT NULL,
  `keterangan` varchar(255) CHARACTER SET ascii COLLATE ascii_general_ci NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `layanan_doc_layanan`(`id_layanan`) USING BTREE,
  CONSTRAINT `layanan_doc_layanan` FOREIGN KEY (`id_layanan`) REFERENCES `m_layanan` (`id_layanan`) ON DELETE RESTRICT ON UPDATE CASCADE
) ENGINE = InnoDB AUTO_INCREMENT = 27 CHARACTER SET = ascii COLLATE = ascii_general_ci COMMENT = 'TRIAL' ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of m_layanan_doc
-- ----------------------------
INSERT INTO `m_layanan_doc` VALUES (8, 1, 'Fotocopy KTP', 'required', '1', NULL, NULL, NULL);
INSERT INTO `m_layanan_doc` VALUES (9, 1, 'Fotocopy KK', 'required', '1', NULL, NULL, NULL);
INSERT INTO `m_layanan_doc` VALUES (10, 1, 'Fotocopy legalisir Akta Pendirian (jika badan hukum)', 'required', '1', NULL, NULL, NULL);
INSERT INTO `m_layanan_doc` VALUES (11, 1, 'Fotocopy dokumen kepemilikan jika IPT sudah beralih', 'required', '1', NULL, NULL, NULL);
INSERT INTO `m_layanan_doc` VALUES (12, 2, 'Fotocopy KTP', 'required', '1', NULL, NULL, NULL);
INSERT INTO `m_layanan_doc` VALUES (13, 2, 'Fotocopy KK', 'required', '1', NULL, NULL, NULL);
INSERT INTO `m_layanan_doc` VALUES (14, 2, 'Fotocopy Surat Keterangan Kehilangan dari Kepolisian', 'required', '1', NULL, NULL, NULL);
INSERT INTO `m_layanan_doc` VALUES (15, 2, 'Fotocopy dokumen kepemilikan jika IPT sudah beralih', 'required', '1', NULL, NULL, NULL);
INSERT INTO `m_layanan_doc` VALUES (16, 3, 'Fotocopy KTP', 'required', '1', NULL, NULL, NULL);
INSERT INTO `m_layanan_doc` VALUES (17, 3, 'Fotocopy KK', 'required', '1', NULL, NULL, NULL);
INSERT INTO `m_layanan_doc` VALUES (18, 3, 'Fotocopy legalisir Akta Pendirian (jika badan hukum)', 'required', '1', NULL, NULL, NULL);
INSERT INTO `m_layanan_doc` VALUES (19, 3, 'Fotocopy SKRK', 'required', '1', NULL, NULL, NULL);
INSERT INTO `m_layanan_doc` VALUES (20, 3, 'Fotocopy IPT', 'required', '1', NULL, NULL, NULL);
INSERT INTO `m_layanan_doc` VALUES (21, 3, 'Fotocopy SSRD atau Tanda bukti lunas retribusi IPT', 'required', '1', NULL, NULL, NULL);
INSERT INTO `m_layanan_doc` VALUES (22, 3, 'Fotocopy dokumen peralihan', 'required', '1', NULL, NULL, NULL);
INSERT INTO `m_layanan_doc` VALUES (23, 3, 'Alasan peralihan IPT (kronologi)', 'required', '1', NULL, NULL, NULL);
INSERT INTO `m_layanan_doc` VALUES (24, 3, 'Pengumuman di Surat Kabar (Iklan)', 'required', '1', NULL, NULL, NULL);
INSERT INTO `m_layanan_doc` VALUES (25, 1, 'Formulir', NULL, '1', NULL, NULL, 'Diisi setelah input form ini');
INSERT INTO `m_layanan_doc` VALUES (26, 2, 'Formulir', NULL, '1', NULL, NULL, 'Diisi setelah input form ini');
INSERT INTO `m_layanan_doc` VALUES (27, 3, 'Formulir', NULL, '1', NULL, NULL, 'Diisi setelah input form ini');

-- ----------------------------
-- Table structure for m_layanan_form
-- ----------------------------
DROP TABLE IF EXISTS `m_layanan_form`;
CREATE TABLE `m_layanan_form`  (
  `id` int NOT NULL AUTO_INCREMENT COMMENT 'TRIAL',
  `id_layanan` int NOT NULL COMMENT 'TRIAL',
  `nama_form` varchar(255) CHARACTER SET ascii COLLATE ascii_general_ci NOT NULL,
  `type` varchar(255) CHARACTER SET ascii COLLATE ascii_general_ci NULL DEFAULT NULL,
  `id_user` varchar(32) CHARACTER SET ascii COLLATE ascii_general_ci NOT NULL COMMENT 'TRIAL',
  `created_at` datetime NULL DEFAULT NULL COMMENT 'TRIAL',
  `updated_at` datetime NULL DEFAULT NULL COMMENT 'TRIAL',
  `status` varchar(255) CHARACTER SET ascii COLLATE ascii_general_ci NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `layanan_doc_layanan`(`id_layanan`) USING BTREE,
  CONSTRAINT `m_layanan_form_ibfk_1` FOREIGN KEY (`id_layanan`) REFERENCES `m_layanan` (`id_layanan`) ON DELETE RESTRICT ON UPDATE CASCADE
) ENGINE = InnoDB AUTO_INCREMENT = 24 CHARACTER SET = ascii COLLATE = ascii_general_ci COMMENT = 'TRIAL' ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of m_layanan_form
-- ----------------------------
INSERT INTO `m_layanan_form` VALUES (8, 1, 'Nama Pemohon', 'text', '1', NULL, NULL, 'required');
INSERT INTO `m_layanan_form` VALUES (9, 1, 'Alamat Pemohon', 'text', '1', NULL, NULL, 'required');
INSERT INTO `m_layanan_form` VALUES (10, 1, 'Telepon Pemohon', 'number', '1', NULL, NULL, 'required');
INSERT INTO `m_layanan_form` VALUES (11, 1, 'Nama Pemegang IPT', 'text', '1', NULL, NULL, 'required');
INSERT INTO `m_layanan_form` VALUES (12, 2, 'Nama Pemohon', 'text', '1', NULL, NULL, 'required');
INSERT INTO `m_layanan_form` VALUES (13, 2, 'Alamat Pemohon', 'text', '1', NULL, NULL, 'required');
INSERT INTO `m_layanan_form` VALUES (14, 2, 'Telepon Pemohon', 'number', '1', NULL, NULL, 'required');
INSERT INTO `m_layanan_form` VALUES (15, 2, 'Nomor Kehilangan dari Kepolisian', 'text', '1', NULL, NULL, 'required');
INSERT INTO `m_layanan_form` VALUES (16, 3, 'Nama Pemohon', 'text', '1', NULL, NULL, 'required');
INSERT INTO `m_layanan_form` VALUES (17, 3, 'Alamat Pemohon', 'text', '1', NULL, NULL, 'required');
INSERT INTO `m_layanan_form` VALUES (18, 3, 'Telepon Pemohon', 'text', '1', NULL, NULL, 'required');
INSERT INTO `m_layanan_form` VALUES (19, 3, 'No. IPT', 'text', '1', NULL, NULL, 'required');
INSERT INTO `m_layanan_form` VALUES (20, 3, 'Tanggal IPT', 'date', '1', NULL, NULL, 'required');
INSERT INTO `m_layanan_form` VALUES (21, 3, 'Alamat IPT', 'text', '1', NULL, NULL, 'required');
INSERT INTO `m_layanan_form` VALUES (22, 1, 'Alamat IPT', 'text', '1', NULL, NULL, 'required');
INSERT INTO `m_layanan_form` VALUES (23, 2, 'Nama Pemegang IPT', 'text', '1', NULL, NULL, 'required');
INSERT INTO `m_layanan_form` VALUES (24, 2, 'Alamat IPT', 'text', '1', NULL, NULL, 'required');

-- ----------------------------
-- Table structure for m_status
-- ----------------------------
DROP TABLE IF EXISTS `m_status`;
CREATE TABLE `m_status`  (
  `id_status` int NOT NULL AUTO_INCREMENT,
  `nama_status` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  `icon` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NULL DEFAULT NULL,
  `class_color` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NULL DEFAULT NULL,
  PRIMARY KEY (`id_status`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 101 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_0900_ai_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of m_status
-- ----------------------------
INSERT INTO `m_status` VALUES (0, 'BELUM UPLOAD FORMULIR', '2024-10-10 10:56:51', '2024-10-10 10:56:51', 'alert', 'danger');
INSERT INTO `m_status` VALUES (1, 'SUBMIT', '2024-10-07 15:57:24', '2024-10-07 15:57:24', 'check-circle', 'info');
INSERT INTO `m_status` VALUES (2, 'VALIDASI DOKUMEN', '2024-10-07 15:57:27', '2024-10-07 15:57:27', 'check-circle', 'success');
INSERT INTO `m_status` VALUES (3, 'PEMBUATAN FILE BAP', '2024-10-07 15:57:31', '2024-10-07 15:57:31', 'loader', 'info');
INSERT INTO `m_status` VALUES (4, 'PEMBUATAN KONSEP SURAT', '2024-10-07 15:57:35', '2024-10-07 15:57:35', 'loader', 'info');
INSERT INTO `m_status` VALUES (5, 'VALIDASI KETUA ', '2024-10-07 15:57:42', '2024-10-07 15:57:42', 'check-circle', 'success');
INSERT INTO `m_status` VALUES (6, 'VERIFIKASI KABID', '2024-10-07 15:57:44', '2024-10-07 15:57:44', 'check-circle', 'success');
INSERT INTO `m_status` VALUES (7, 'VERIVIKASI SEKRETARIS', '2024-10-07 15:57:47', '2024-10-07 15:57:47', 'check-circle', 'success');
INSERT INTO `m_status` VALUES (8, 'VERIVIKASI KA BPKAD', '2024-10-07 15:57:49', '2024-10-07 15:57:49', 'check-circle', 'success');
INSERT INTO `m_status` VALUES (9, 'PENOMERAN SURAT', '2024-10-07 15:57:54', '2024-10-07 15:57:54', 'loader', 'info');
INSERT INTO `m_status` VALUES (10, 'PENGEMBALIAN DOKUMEN & CETAK SURAT', '2024-10-07 15:57:58', '2024-10-07 15:57:58', 'check-circle', 'info');
INSERT INTO `m_status` VALUES (99, 'REJECT', NULL, NULL, 'alert', 'danger');

-- ----------------------------
-- Table structure for menu
-- ----------------------------
DROP TABLE IF EXISTS `menu`;
CREATE TABLE `menu`  (
  `id_menu` int NOT NULL AUTO_INCREMENT COMMENT 'TRIAL',
  `nm_menu` varchar(50) CHARACTER SET ascii COLLATE ascii_general_ci NULL DEFAULT NULL COMMENT 'TRIAL',
  `parent` int NULL DEFAULT NULL COMMENT 'TRIAL',
  `level` varchar(2) CHARACTER SET ascii COLLATE ascii_general_ci NOT NULL COMMENT 'TRIAL',
  `route` varchar(40) CHARACTER SET ascii COLLATE ascii_general_ci NULL DEFAULT NULL COMMENT 'TRIAL',
  `controller` varchar(50) CHARACTER SET ascii COLLATE ascii_general_ci NULL DEFAULT NULL COMMENT 'TRIAL',
  `tampil` tinyint(1) NULL DEFAULT NULL COMMENT 'TRIAL',
  `created_at` datetime NULL DEFAULT NULL COMMENT 'TRIAL',
  `id_module` int NULL DEFAULT NULL COMMENT 'TRIAL',
  `icon` varchar(40) CHARACTER SET ascii COLLATE ascii_general_ci NULL DEFAULT NULL COMMENT 'TRIAL',
  `temp` int NULL DEFAULT NULL COMMENT 'TRIAL',
  `updated_at` datetime NULL DEFAULT NULL COMMENT 'TRIAL',
  `trial762` char(1) CHARACTER SET ascii COLLATE ascii_general_ci NULL DEFAULT NULL COMMENT 'TRIAL',
  PRIMARY KEY (`id_menu`) USING BTREE,
  INDEX `module`(`id_module`) USING BTREE,
  CONSTRAINT `menu_ibfk_1` FOREIGN KEY (`id_module`) REFERENCES `module` (`id_module`) ON DELETE RESTRICT ON UPDATE CASCADE
) ENGINE = InnoDB AUTO_INCREMENT = 322 CHARACTER SET = ascii COLLATE = ascii_general_ci COMMENT = 'TRIAL' ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of menu
-- ----------------------------
INSERT INTO `menu` VALUES (2, 'master', 0, '1', NULL, NULL, 1, '2020-02-19 05:38:35', 1, 'fa fa-qrcode', 1, '2021-10-25 09:48:04', 'T');
INSERT INTO `menu` VALUES (3, 'menu', 0, '1', 'menus', 'P_menu', 1, '2020-02-19 05:44:53', 1, 'fa fa-beer', 4, '2021-10-25 09:48:04', 'T');
INSERT INTO `menu` VALUES (4, 'module', 0, '1', 'module', 'P_module', 1, '2020-02-19 05:50:35', 1, 'fa fa-podcast', 3, '2021-10-25 09:48:04', 'T');
INSERT INTO `menu` VALUES (5, 'role', 0, '1', 'role', 'P_role', 1, '2020-02-19 05:52:41', 1, 'fa fa-home', 6, '2021-10-25 09:48:04', 'T');
INSERT INTO `menu` VALUES (6, 'role user', 0, '1', 'roleuser', 'P_roleuser', 1, '2020-02-19 05:54:42', 1, 'fa fa-bookmark', 7, '2021-10-25 09:48:04', 'T');
INSERT INTO `menu` VALUES (7, 'users', 0, '1', 'user', 'UserController', 1, '2020-02-19 05:55:54', 1, 'fa fa-users', 5, '2023-03-07 20:33:50', 'T');
INSERT INTO `menu` VALUES (8, 'role menu', 0, '1', 'rolemenu', 'P_rolemenu', 1, '2020-02-19 05:57:04', 1, 'fa fa-lock', 8, '2021-10-25 09:48:04', 'T');
INSERT INTO `menu` VALUES (9, 'wilayah', 2, '2', 'wilayah', 'P_wilayah', 1, '2020-02-19 05:58:25', 1, 'fa fa-map-marker', 2121, '2021-10-25 09:48:04', 'T');
INSERT INTO `menu` VALUES (11, 'dashboard', 0, '1', 'administrator', 'P_dashboard', 1, '2020-03-07 03:25:16', 1, 'fa fa-rss-square', 2, '2021-10-25 09:48:04', 'T');
INSERT INTO `menu` VALUES (13, 'perusahaan', 2, '2', 'perusahaan', 'P_perusahaan', 1, '2020-03-07 03:29:45', 1, 'fa fa-industry', 2122, '2021-10-25 09:48:04', 'T');
INSERT INTO `menu` VALUES (14, 'permohonan', 0, '1', 'permohonan', 'PermohonanController', 1, '2024-10-04 10:14:29', 2, 'fa fa-table', 111, '2024-10-04 10:14:24', NULL);

-- ----------------------------
-- Table structure for migrations
-- ----------------------------
DROP TABLE IF EXISTS `migrations`;
CREATE TABLE `migrations`  (
  `id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `migration` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int NOT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 4 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of migrations
-- ----------------------------
INSERT INTO `migrations` VALUES (1, '2014_10_12_000000_create_users_table', 1);
INSERT INTO `migrations` VALUES (2, '2014_10_12_100000_create_password_resets_table', 2);
INSERT INTO `migrations` VALUES (3, '2019_08_19_000000_create_failed_jobs_table', 2);
INSERT INTO `migrations` VALUES (4, '2024_09_03_132800_user_api', 2);
INSERT INTO `migrations` VALUES (5, '2024_10_16_021512_create_m_layanan_table', 0);
INSERT INTO `migrations` VALUES (6, '2024_10_16_021512_create_m_layanan_doc_table', 0);
INSERT INTO `migrations` VALUES (7, '2024_10_16_021512_create_m_layanan_form_table', 0);
INSERT INTO `migrations` VALUES (8, '2024_10_16_021512_create_m_status_table', 0);
INSERT INTO `migrations` VALUES (9, '2024_10_16_021512_create_menu_table', 0);
INSERT INTO `migrations` VALUES (10, '2024_10_16_021512_create_module_table', 0);
INSERT INTO `migrations` VALUES (11, '2024_10_16_021512_create_role_table', 0);
INSERT INTO `migrations` VALUES (12, '2024_10_16_021512_create_role_menu_table', 0);
INSERT INTO `migrations` VALUES (13, '2024_10_16_021512_create_role_user_table', 0);
INSERT INTO `migrations` VALUES (14, '2024_10_16_021512_create_t_permohonan_table', 0);
INSERT INTO `migrations` VALUES (15, '2024_10_16_021512_create_t_permohonan_bap_table', 0);
INSERT INTO `migrations` VALUES (16, '2024_10_16_021512_create_t_permohonan_document_table', 0);
INSERT INTO `migrations` VALUES (17, '2024_10_16_021512_create_t_permohonan_history_table', 0);
INSERT INTO `migrations` VALUES (18, '2024_10_16_021512_create_t_permohonan_surat_table', 0);
INSERT INTO `migrations` VALUES (19, '2024_10_16_021512_create_users_table', 0);
INSERT INTO `migrations` VALUES (20, '2024_10_16_021515_add_foreign_keys_to_m_layanan_doc_table', 0);
INSERT INTO `migrations` VALUES (21, '2024_10_16_021515_add_foreign_keys_to_m_layanan_form_table', 0);
INSERT INTO `migrations` VALUES (22, '2024_10_16_021515_add_foreign_keys_to_menu_table', 0);
INSERT INTO `migrations` VALUES (23, '2024_10_16_021515_add_foreign_keys_to_role_menu_table', 0);
INSERT INTO `migrations` VALUES (24, '2024_10_16_021515_add_foreign_keys_to_role_user_table', 0);

-- ----------------------------
-- Table structure for module
-- ----------------------------
DROP TABLE IF EXISTS `module`;
CREATE TABLE `module`  (
  `id_module` int NOT NULL COMMENT 'TRIAL',
  `nm_module` varchar(50) CHARACTER SET ascii COLLATE ascii_general_ci NULL DEFAULT NULL COMMENT 'TRIAL',
  `icon` varchar(40) CHARACTER SET ascii COLLATE ascii_general_ci NULL DEFAULT NULL COMMENT 'TRIAL',
  `color` varchar(40) CHARACTER SET ascii COLLATE ascii_general_ci NULL DEFAULT NULL COMMENT 'TRIAL',
  `created_at` datetime NULL DEFAULT NULL COMMENT 'TRIAL',
  `updated_at` datetime NULL DEFAULT NULL COMMENT 'TRIAL',
  `id_creator` varchar(32) CHARACTER SET ascii COLLATE ascii_general_ci NULL DEFAULT NULL COMMENT 'TRIAL',
  `trial759` char(1) CHARACTER SET ascii COLLATE ascii_general_ci NULL DEFAULT NULL COMMENT 'TRIAL',
  PRIMARY KEY (`id_module`) USING BTREE
) ENGINE = InnoDB CHARACTER SET = ascii COLLATE = ascii_general_ci COMMENT = 'TRIAL' ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of module
-- ----------------------------
INSERT INTO `module` VALUES (1, 'Administrator', 'fa fa-list-alt', '#1e42e3', '2020-02-19 05:37:13', '2020-02-20 03:42:59', 'usr5', 'T');
INSERT INTO `module` VALUES (2, 'Operasional', 'fa fa-bar-chart-o', '#000000', '2020-03-07 03:36:06', '2020-03-07 05:38:17', 'usr5', 'T');
INSERT INTO `module` VALUES (3, 'Keuangan', 'fa fa-money', '#000000', '2020-04-07 17:11:53', '2020-04-07 17:13:18', 'usr5', 'T');
INSERT INTO `module` VALUES (4, 'Kepegawaian', 'fa fa-users', '#101cc6', '2020-10-22 10:06:49', '2020-10-22 10:06:49', 'usr5', 'T');
INSERT INTO `module` VALUES (5, 'Busdev', 'fa fa-road', '#2947a3', '2023-02-20 13:58:55', '2023-02-20 14:01:13', NULL, 'T');
INSERT INTO `module` VALUES (6, 'laporan', 'fa fa-file-pdf-o', '#000000', '2023-08-12 23:25:29', '2023-08-12 23:25:29', NULL, 'T');
INSERT INTO `module` VALUES (7, 'Asuransi', 'fa fa-file', '#000000', '2024-07-26 15:44:24', '2024-07-26 15:44:24', NULL, 'T');

-- ----------------------------
-- Table structure for role
-- ----------------------------
DROP TABLE IF EXISTS `role`;
CREATE TABLE `role`  (
  `id_role` int NOT NULL COMMENT 'TRIAL',
  `nm_role` varchar(60) CHARACTER SET ascii COLLATE ascii_general_ci NULL DEFAULT NULL COMMENT 'TRIAL',
  `created_at` datetime NULL DEFAULT NULL COMMENT 'TRIAL',
  `updated_at` datetime NULL DEFAULT NULL COMMENT 'TRIAL',
  `id_creator` varchar(32) CHARACTER SET ascii COLLATE ascii_general_ci NULL DEFAULT NULL COMMENT 'TRIAL',
  `trial778` char(1) CHARACTER SET ascii COLLATE ascii_general_ci NULL DEFAULT NULL COMMENT 'TRIAL',
  PRIMARY KEY (`id_role`) USING BTREE
) ENGINE = InnoDB CHARACTER SET = ascii COLLATE = ascii_general_ci COMMENT = 'TRIAL' ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of role
-- ----------------------------
INSERT INTO `role` VALUES (1, 'Administrator', NULL, NULL, '1', NULL);
INSERT INTO `role` VALUES (2, 'Petugas P3BMD', NULL, NULL, '1', NULL);
INSERT INTO `role` VALUES (3, 'Petugas P2BMD', NULL, NULL, '1', NULL);
INSERT INTO `role` VALUES (4, 'Subkoor', NULL, NULL, '1', NULL);
INSERT INTO `role` VALUES (5, 'Kabid', NULL, NULL, '1', NULL);
INSERT INTO `role` VALUES (6, 'Asuransi', NULL, NULL, '1', NULL);
INSERT INTO `role` VALUES (7, 'Sekretaris', NULL, NULL, '1', NULL);
INSERT INTO `role` VALUES (8, 'Kaban', NULL, NULL, '1', NULL);
INSERT INTO `role` VALUES (99, 'Pemohon', NULL, NULL, '1', NULL);

-- ----------------------------
-- Table structure for role_menu
-- ----------------------------
DROP TABLE IF EXISTS `role_menu`;
CREATE TABLE `role_menu`  (
  `id_rm` int NOT NULL AUTO_INCREMENT,
  `id_role` int NOT NULL,
  `permission` varchar(255) CHARACTER SET ascii COLLATE ascii_general_ci NOT NULL,
  PRIMARY KEY (`id_rm`) USING BTREE,
  INDEX `menu`(`permission`) USING BTREE,
  INDEX `role`(`id_role`) USING BTREE,
  CONSTRAINT `role_menu_ibfk_3` FOREIGN KEY (`id_role`) REFERENCES `role` (`id_role`) ON DELETE RESTRICT ON UPDATE CASCADE
) ENGINE = InnoDB AUTO_INCREMENT = 1098 CHARACTER SET = ascii COLLATE = ascii_general_ci COMMENT = 'TRIAL' ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of role_menu
-- ----------------------------
INSERT INTO `role_menu` VALUES (1092, 1, 'show-dashboard');
INSERT INTO `role_menu` VALUES (1093, 1, 'show-permohonan');
INSERT INTO `role_menu` VALUES (1094, 1, 'show-master');
INSERT INTO `role_menu` VALUES (1095, 99, 'show-permohonan');
INSERT INTO `role_menu` VALUES (1096, 99, 'show-dashboard');
INSERT INTO `role_menu` VALUES (1097, 1, 'show-bap');

-- ----------------------------
-- Table structure for role_user
-- ----------------------------
DROP TABLE IF EXISTS `role_user`;
CREATE TABLE `role_user`  (
  `id_ru` int NOT NULL AUTO_INCREMENT COMMENT 'TRIAL',
  `id_user` int NULL DEFAULT NULL COMMENT 'TRIAL',
  `id_role` int NOT NULL COMMENT 'TRIAL',
  `created_at` datetime NULL DEFAULT NULL COMMENT 'TRIAL',
  `updated_at` datetime NULL DEFAULT NULL COMMENT 'TRIAL',
  PRIMARY KEY (`id_ru`) USING BTREE,
  INDEX `role_1`(`id_role`) USING BTREE,
  CONSTRAINT `role_user_ibfk_1` FOREIGN KEY (`id_role`) REFERENCES `role` (`id_role`) ON DELETE RESTRICT ON UPDATE CASCADE
) ENGINE = InnoDB AUTO_INCREMENT = 790 CHARACTER SET = ascii COLLATE = ascii_general_ci COMMENT = 'TRIAL' ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of role_user
-- ----------------------------
INSERT INTO `role_user` VALUES (29, 6, 1, '2021-11-08 09:27:41', '2021-11-08 09:27:41');
INSERT INTO `role_user` VALUES (787, 27434, 99, '2024-10-04 09:54:37', '2024-10-04 09:54:37');
INSERT INTO `role_user` VALUES (788, 27435, 99, '2024-10-08 11:51:52', '2024-10-08 11:51:52');
INSERT INTO `role_user` VALUES (789, 27436, 99, '2024-10-08 14:21:54', '2024-10-08 14:21:54');

-- ----------------------------
-- Table structure for t_permohonan
-- ----------------------------
DROP TABLE IF EXISTS `t_permohonan`;
CREATE TABLE `t_permohonan`  (
  `id` int NOT NULL AUTO_INCREMENT,
  `no_permohonan` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NULL DEFAULT NULL,
  `id_layanan` int NULL DEFAULT NULL,
  `nama_pemohon` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NULL DEFAULT NULL,
  `alamat_pemohon` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NULL DEFAULT NULL,
  `telepon_pemohon` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NULL DEFAULT NULL,
  `nama_pemegang_ipt` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NULL DEFAULT NULL,
  `no_ipt` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NULL DEFAULT NULL,
  `tanggal_ipt` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NULL DEFAULT NULL,
  `alamat_ipt` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NULL DEFAULT NULL,
  `nomor_kehilangan_dari_kepolisian` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NULL DEFAULT NULL,
  `tanggal_pengajuan` date NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  `id_user` int NULL DEFAULT NULL,
  `id_status` int NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 43 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_0900_ai_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of t_permohonan
-- ----------------------------
INSERT INTO `t_permohonan` VALUES (17, 'BPKAD/PIPT/241007/7672', 1, 'Irsyadul anam', 'Surabaya', '089123123', 'irsyadul anam', '', '', '', '', '2024-10-07', '2024-10-07 14:45:53', '2024-10-07 14:45:53', 6, 1);
INSERT INTO `t_permohonan` VALUES (18, 'BPKAD/PIPT/241007/8286', 1, 'Irsyadul anam', 'Surabaya', '089123123', 'irsyadul anam', '', '', '', '', '2024-10-07', '2024-10-07 14:45:56', '2024-10-07 14:45:56', 6, 1);
INSERT INTO `t_permohonan` VALUES (19, 'BPKAD/PIPT/241007/3350', 1, 'Irsyadul anam', 'Surabaya', '089123123', 'irsyadul anam', '', '', '', '', '2024-10-07', '2024-10-08 10:43:09', '2024-10-08 10:43:09', 6, 3);
INSERT INTO `t_permohonan` VALUES (20, 'BPKAD/PIPT/241007/7072', 1, 'Irsyadul anam', 'Surabaya', '089123123', 'irsyadul anam', '', '', '', '', '2024-10-07', '2024-10-08 08:48:06', '2024-10-08 08:48:06', 6, 2);
INSERT INTO `t_permohonan` VALUES (21, 'BPKAD/PIPT/241008/8283', 1, 'Suherman', 'Surabaya Timur No 23', '089123123123', 'Suherman', '', '', '', '', '2024-10-08', '2024-10-08 14:59:17', '2024-10-08 14:59:17', 27436, 8);
INSERT INTO `t_permohonan` VALUES (22, 'BPKAD/PIPT/241008/6465', 2, 'Irsyadul anam', 'Surabaya Timur No 23', '1231313123', '', '', '', '', '1231231312', '2024-10-08', '2024-10-09 14:21:38', '2024-10-09 14:21:38', 6, 8);
INSERT INTO `t_permohonan` VALUES (23, 'BPKAD/PIPT/241009/5446', 3, 'Irsyadul Anam', 'Surabaya No.12', '12312312312', '', '1231231231231', '', 'Ketabang Surabaya', '', '2024-10-09', '2024-10-09 08:42:50', '2024-10-09 08:42:50', 6, 8);
INSERT INTO `t_permohonan` VALUES (24, 'BPKAD/PIPT/241009/0110', 3, 'Irsyadul Anam', 'Surabaya No.12', '12312312312', '', '1231231231231', '2024-10-09', 'Ketabang Surabaya', '', '2024-10-09', '2024-10-09 15:24:08', '2024-10-09 15:24:08', 6, 8);
INSERT INTO `t_permohonan` VALUES (26, 'BPKAD/PIPT/241010/6259', 1, 'Irsyadul Anam', 'Surabaya No.12', '12312312312', 'Irsyad', '', '', '', '', '2024-10-10', '2024-10-10 08:16:51', '2024-10-10 08:16:51', 6, 8);
INSERT INTO `t_permohonan` VALUES (27, 'BPKAD/PIPT/241010/5580', 1, 'Heru', 'Surabaya No 12', '123123123', 'Heru', '', '', '', '', '2024-10-10', '2024-10-10 08:48:41', '2024-10-10 08:48:41', 27435, 8);
INSERT INTO `t_permohonan` VALUES (29, 'BPKAD/PIPT/241010/6125', 1, 'Irsyadul Anam', 'Surabaya No.12', '12312312312', 'Irsyad', '', '', 'Ketabang Surabaya', '', '2024-10-10', '2024-10-10 09:19:58', '2024-10-10 09:19:58', 6, 1);
INSERT INTO `t_permohonan` VALUES (30, 'BPKAD/PIPT/241010/1441', 2, 'Irsyadul Anam', 'Surabaya No.12', '12312312312', '', '', '', '', '712893.10231312', '2024-10-10', '2024-10-10 10:06:08', '2024-10-10 10:06:08', 6, 1);
INSERT INTO `t_permohonan` VALUES (32, 'BPKAD/PIPT/241010/3849', 1, 'Irsyadul Anam', 'Surabaya No.12', '12312312312', 'Irsyad', '', '', 'Ketabang Surabaya', '', '2024-10-10', '2024-10-11 09:04:11', '2024-10-11 09:04:11', 6, 8);
INSERT INTO `t_permohonan` VALUES (33, 'BPKAD/PIPT/241010/9119', 3, 'Irsyadul Anam', 'Surabaya No.12', '12312312312', '', '1231231231231', '2024-10-10', 'Ketabang Surabaya', '', '2024-10-10', '2024-10-11 14:38:44', '2024-10-11 14:38:44', 6, 10);
INSERT INTO `t_permohonan` VALUES (34, 'BPKAD/PIPT/241011/7802', 2, 'Irsyadul Anam', 'Surabaya No.12', '12312312312', '', '', '', '', '712893.10231312', '2024-10-11', '2024-10-11 10:43:52', '2024-10-11 10:43:52', 6, 4);
INSERT INTO `t_permohonan` VALUES (35, 'BPKAD/PIPT/241014/2081', 1, 'Irsyadul Anam', 'Surabaya No.12', '12312312312', 'Irsyad', '', '', 'Ketabang Surabaya', '', '2024-10-14', '2024-10-14 09:47:10', '2024-10-14 09:47:10', 6, 10);
INSERT INTO `t_permohonan` VALUES (36, 'BPKAD/PIPT/241014/6874', 2, 'Irsyadul Anam', 'Surabaya No.12', '12312312312', 'Irsyad', '', '', 'Ketabang Surabaya', '712893.10231312', '2024-10-14', '2024-10-14 09:52:22', '2024-10-14 09:52:22', 6, 10);
INSERT INTO `t_permohonan` VALUES (37, 'BPKAD/PIPT/241014/5022', 3, 'Irsyadul Anam', 'Surabaya No.12', '12312312312', '', '1231231231231', '2024-10-14', 'Ketabang Surabaya', '', '2024-10-14', '2024-10-14 12:06:12', '2024-10-14 12:06:12', 6, 10);
INSERT INTO `t_permohonan` VALUES (38, 'BPKAD/PIPT/241014/2624', 1, 'Suherman', 'Jl Pemuda Surabaya', '0895123131', 'Suherman', '', '', 'Surabaya Juga', '', '2024-10-14', '2024-10-14 14:12:52', '2024-10-14 14:12:52', 27435, 8);
INSERT INTO `t_permohonan` VALUES (39, 'BPKAD/PIPT/241014/1046', 1, 'Irsyadul Anam', 'Surabaya No.12', '12312312312', 'Irsyad', '', '', 'Ketabang Surabaya', '', '2024-10-14', '2024-10-14 15:24:35', '2024-10-14 15:24:35', 6, 10);
INSERT INTO `t_permohonan` VALUES (40, 'BPKAD/PIPT/241014/4999', 1, 'Irsyadul Anam', 'Surabaya No.12', '12312312312', 'Irsyad', '', '', 'Ketabang Surabaya', '', '2024-10-14', '2024-10-14 15:29:12', '2024-10-14 15:29:12', 6, 2);
INSERT INTO `t_permohonan` VALUES (41, 'BPKAD/PIPT/241015/1336', 1, 'Irsyadul Anam', 'Surabaya No.12', '12312312312', 'Irsyad', '', '', 'Ketabang Surabaya', '', '2024-10-15', '2024-10-15 10:18:39', '2024-10-15 10:18:39', 6, 10);
INSERT INTO `t_permohonan` VALUES (42, 'BPKAD/PIPT/241015/9461', 3, 'Irsyadul Anam', 'Surabaya No.12', '12312312312', '', '1231231231231', '2024-10-15', 'Ketabang Surabaya', '', '2024-10-15', '2024-10-15 10:29:20', '2024-10-15 10:29:20', 6, 10);

-- ----------------------------
-- Table structure for t_permohonan_bap
-- ----------------------------
DROP TABLE IF EXISTS `t_permohonan_bap`;
CREATE TABLE `t_permohonan_bap`  (
  `id` int NOT NULL AUTO_INCREMENT,
  `id_permohonan` int NOT NULL,
  `file` text CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NULL,
  `peruntukan` text CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NULL,
  `penggunaan` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NULL DEFAULT NULL,
  `luas` int NULL DEFAULT NULL,
  `no_ipt` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NULL DEFAULT NULL,
  `tanggal_ipt` date NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 19 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_0900_ai_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of t_permohonan_bap
-- ----------------------------
INSERT INTO `t_permohonan_bap` VALUES (1, 19, 'file_bap/uhBoaI8ibCfTPMuqZpTDQB5Tv8RXJFomvlNhgdjX.pdf', NULL, NULL, NULL, NULL, NULL, '2024-10-08 10:43:09', '2024-10-08 10:43:09');
INSERT INTO `t_permohonan_bap` VALUES (2, 21, 'file_bap/gqhBCdTwnluNwn2C92I1yUAeyBPy5vybD95uyUcG.png', NULL, NULL, NULL, NULL, NULL, '2024-10-08 14:29:05', '2024-10-08 14:29:05');
INSERT INTO `t_permohonan_bap` VALUES (3, 23, 'file_bap/413QvBkUnejQBiIWhNiS0FdorJKiTbIb6g6009iS.png', NULL, NULL, NULL, NULL, NULL, '2024-10-09 08:41:43', '2024-10-09 08:41:43');
INSERT INTO `t_permohonan_bap` VALUES (4, 22, 'file_bap/RRooSRi2CQZ3mSaazroffEZxrRPGV81UYzBIOMkK.png', 'Buka Warung', NULL, 120, NULL, NULL, '2024-10-09 09:33:53', '2024-10-09 09:33:53');
INSERT INTO `t_permohonan_bap` VALUES (5, 24, 'file_bap/EsySXskM6ZXvvHyd6hNkRQaHyCOw1uxwqNpG2mvx.png', 'Buka Warung', NULL, 120, NULL, NULL, '2024-10-09 15:12:12', '2024-10-09 15:12:12');
INSERT INTO `t_permohonan_bap` VALUES (6, 26, 'file_bap/TuJHrm1VNSinAE2sQsAGJQrpHg4ilLF4pYhAS7n8.png', 'Buka Warung', NULL, 120, NULL, NULL, '2024-10-10 08:14:30', '2024-10-10 08:14:30');
INSERT INTO `t_permohonan_bap` VALUES (7, 27, 'file_bap/tANBVEBHmU7YVDJOv2SvHh1hqo9HurFg9YixW7wj.png', 'Buka Warung', NULL, 120, NULL, NULL, '2024-10-10 08:39:43', '2024-10-10 08:39:43');
INSERT INTO `t_permohonan_bap` VALUES (8, 32, 'file_bap/HE8DMEYOgjyaZV04NgyShddZMviVHczEgvvzpM2M.png', 'Buka Warung', 'Usaha', 120, '1231231231231', NULL, '2024-10-10 11:42:10', '2024-10-10 11:42:10');
INSERT INTO `t_permohonan_bap` VALUES (9, 32, 'file_bap/YyiOeG9FUhDJZo8Hv7p0djXxvGY4Z1AFBxNxEZWw.png', 'Buka Warung', 'Usaha', 120, '1231231231231', '2024-10-10', '2024-10-10 11:45:59', '2024-10-10 11:45:59');
INSERT INTO `t_permohonan_bap` VALUES (10, 33, 'file_bap/SGMadPvdyHTWPqPm1vxxIxLKGXc4ydEHlILVnlyX.png', 'Buka Warung', 'Usaha', 120, '1231231231231', '2024-10-10', '2024-10-10 15:17:25', '2024-10-10 15:17:25');
INSERT INTO `t_permohonan_bap` VALUES (11, 34, 'file_bap/Qs7LZvDG5R0RxpMY4fCo20EkMrvjzZArrQC6OHGP.png', 'Buka Warung', 'Usaha', 120, '1231231231231', '2024-10-11', '2024-10-11 09:22:09', '2024-10-11 09:22:09');
INSERT INTO `t_permohonan_bap` VALUES (12, 35, 'file_bap/8PMCFchIxhxHbaQh7PLDqPOTT2DnzRdg7vXsHJeS.png', 'Buka Warung', 'Usaha', 120, '1231231231231', '2024-10-14', '2024-10-14 09:37:56', '2024-10-14 09:37:56');
INSERT INTO `t_permohonan_bap` VALUES (13, 36, 'file_bap/aRExqMmmnvRYEZIA4jn1eo8ojcGZhOVkN7gPWLkD.png', 'Buka Warung', 'Usaha', 120, '1231231231231', '2024-10-14', '2024-10-14 09:51:00', '2024-10-14 09:51:00');
INSERT INTO `t_permohonan_bap` VALUES (14, 37, 'file_bap/utaiXpdYGfiCpYGYeOx7NV1dpLfzgHUV4HBjJg3H.png', 'Buka Warung', 'Usaha', 120, '1231231231231', '2024-10-14', '2024-10-14 09:54:36', '2024-10-14 09:54:36');
INSERT INTO `t_permohonan_bap` VALUES (15, 38, 'file_bap/UIGPHn86rKIfvn0mByMgtgIUN4l7mqD5xwm6X27D.png', 'Buka Warung', 'Usaha', 120, '1231231231231', '2024-10-03', '2024-10-14 14:02:08', '2024-10-14 14:02:08');
INSERT INTO `t_permohonan_bap` VALUES (16, 39, 'file_bap/WUcQfud39UC2a223xuUtY4YxWu7DLNM4mTKgFapH.pdf', 'Buka Warung', 'Usaha', 120, '1231231231231', '2024-10-14', '2024-10-14 15:12:37', '2024-10-14 15:12:37');
INSERT INTO `t_permohonan_bap` VALUES (17, 41, 'file_bap/8fANDF0ytk5R1UbJntP6ZDKhcbwI72M6GK7EhQjp.pdf', 'Buka Warung', 'Usaha', 120, '1231231231231', '2024-10-15', '2024-10-15 10:05:11', '2024-10-15 10:05:11');
INSERT INTO `t_permohonan_bap` VALUES (18, 42, 'file_bap/y1yNkmw6xyOh6fvWrITxdX9gTEs1XhziFNiOFosO.pdf', 'Buka Warung', 'Usaha', 120, '1231231231231', '2024-10-15', '2024-10-15 10:26:56', '2024-10-15 10:26:56');

-- ----------------------------
-- Table structure for t_permohonan_document
-- ----------------------------
DROP TABLE IF EXISTS `t_permohonan_document`;
CREATE TABLE `t_permohonan_document`  (
  `id` int NOT NULL AUTO_INCREMENT,
  `id_permohonan` int NOT NULL,
  `file` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 178 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_0900_ai_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of t_permohonan_document
-- ----------------------------
INSERT INTO `t_permohonan_document` VALUES (25, 17, 'fotocopy_ktp/NLkdYSdaErBQmZ2y1TNasTv2ECL2NVqD8v0UDmpT.png', '2024-10-07 14:45:53', '2024-10-07 14:45:53');
INSERT INTO `t_permohonan_document` VALUES (26, 17, 'fotocopy_kk/96HtjQpOjAJykbAh5L4VUaKUv9MD19o0YoxzHIxn.png', '2024-10-07 14:45:53', '2024-10-07 14:45:53');
INSERT INTO `t_permohonan_document` VALUES (27, 17, 'fotocopy_legalisir_akta_pendirian_jika_badan_hukum/qpdcziZL1SxlEkkcuRzMcLzmmpkQku0NmU09qDTW.png', '2024-10-07 14:45:53', '2024-10-07 14:45:53');
INSERT INTO `t_permohonan_document` VALUES (28, 17, 'fotocopy_dokumen_kepemilikan_jika_ipt_sudah_beralih/MUZcMrHaelDvDx4IDxm3cewQg1qdLnHtnxn2JqOh.png', '2024-10-07 14:45:53', '2024-10-07 14:45:53');
INSERT INTO `t_permohonan_document` VALUES (29, 18, 'fotocopy_ktp/BFQIG7B6Rja7T9I0kjkVVSfY3ZiPkZvBs5KSJL7d.png', '2024-10-07 14:45:56', '2024-10-07 14:45:56');
INSERT INTO `t_permohonan_document` VALUES (30, 18, 'fotocopy_kk/at0JRGjulQvypwRMGOnOoqQSBwNjmwYm4DAaQ6Bv.png', '2024-10-07 14:45:56', '2024-10-07 14:45:56');
INSERT INTO `t_permohonan_document` VALUES (31, 18, 'fotocopy_legalisir_akta_pendirian_jika_badan_hukum/kpt6S9ip4teCESWAwm5NzdELhYxQ9ASC5CAZtMab.png', '2024-10-07 14:45:56', '2024-10-07 14:45:56');
INSERT INTO `t_permohonan_document` VALUES (32, 18, 'fotocopy_dokumen_kepemilikan_jika_ipt_sudah_beralih/kwx5SDu2JdnbCPx7zeukWvud4ro7cYhvOLxCPZqL.png', '2024-10-07 14:45:56', '2024-10-07 14:45:56');
INSERT INTO `t_permohonan_document` VALUES (33, 19, 'fotocopy_ktp/ikGLMDvQofp3EfXH0tdxdbY6TbkXcgbE6i8qTqlL.png', '2024-10-07 14:45:58', '2024-10-07 14:45:58');
INSERT INTO `t_permohonan_document` VALUES (34, 19, 'fotocopy_kk/W8zsBirLjyrCE5cy2Xa3GMPSnNjOa5Yb6gbhiyNZ.png', '2024-10-07 14:45:58', '2024-10-07 14:45:58');
INSERT INTO `t_permohonan_document` VALUES (35, 19, 'fotocopy_legalisir_akta_pendirian_jika_badan_hukum/5BLvsu6HQCQwpjNA6h5cGEhnHqI1Vs6X7IsTho7B.png', '2024-10-07 14:45:58', '2024-10-07 14:45:58');
INSERT INTO `t_permohonan_document` VALUES (36, 19, 'fotocopy_dokumen_kepemilikan_jika_ipt_sudah_beralih/2pKKtC0TS4njuugBA421ML26cbnbMWxCEhtIwj2i.png', '2024-10-07 14:45:58', '2024-10-07 14:45:58');
INSERT INTO `t_permohonan_document` VALUES (41, 20, 'fotocopy_ktp/46o0lyy9I6Ahb36b0ljHPQNCOWTKXTtta9RWvgdd.png', '2024-10-07 15:44:27', '2024-10-07 15:44:27');
INSERT INTO `t_permohonan_document` VALUES (42, 20, 'fotocopy_kk/iq3dnqXrdgIR0ZPsVdpXLgRWp9fkXwwdlmtLAU82.png', '2024-10-07 15:44:27', '2024-10-07 15:44:27');
INSERT INTO `t_permohonan_document` VALUES (43, 20, 'fotocopy_legalisir_akta_pendirian_jika_badan_hukum/B4x89sMkxFaa71OhSQtXPcnuel3LHaI260iUYXpc.png', '2024-10-07 15:44:27', '2024-10-07 15:44:27');
INSERT INTO `t_permohonan_document` VALUES (44, 20, 'fotocopy_dokumen_kepemilikan_jika_ipt_sudah_beralih/J6TMBCdXQRWkZMnbYf0Jyp5g1SftyHo3mzkLr2kn.png', '2024-10-07 15:44:27', '2024-10-07 15:44:27');
INSERT INTO `t_permohonan_document` VALUES (45, 21, 'fotocopy_ktp/SV3kH9OJX4kiVcjIeAgD41Tq5YxOB5Kd3s0tqMWF.png', '2024-10-08 14:24:18', '2024-10-08 14:24:18');
INSERT INTO `t_permohonan_document` VALUES (46, 21, 'fotocopy_kk/xhDOjgd9Ft4R02Ab9ISS1TocfYQWJZV437hiqvVl.png', '2024-10-08 14:24:18', '2024-10-08 14:24:18');
INSERT INTO `t_permohonan_document` VALUES (47, 21, 'fotocopy_legalisir_akta_pendirian_jika_badan_hukum/oFlDIZyrZMKq0POFBjEKGbI5ave6yY1DtlKw3ZhA.png', '2024-10-08 14:24:18', '2024-10-08 14:24:18');
INSERT INTO `t_permohonan_document` VALUES (48, 21, 'fotocopy_dokumen_kepemilikan_jika_ipt_sudah_beralih/M8HHSWrErRO8f23VsOmjDfcAzs9yjNvbD2TkxC57.png', '2024-10-08 14:24:18', '2024-10-08 14:24:18');
INSERT INTO `t_permohonan_document` VALUES (53, 22, 'fotocopy_ktp/NZ2ljCf7KpWGIyH0hRAphk6JS0cXVQmkpRAwtlRW.png', '2024-10-08 16:43:07', '2024-10-08 16:43:07');
INSERT INTO `t_permohonan_document` VALUES (54, 22, 'fotocopy_kk/w6lVFRv1LJC45biZKlmuVPrLtPLzVQaW43KUQNBD.png', '2024-10-08 16:43:07', '2024-10-08 16:43:07');
INSERT INTO `t_permohonan_document` VALUES (55, 22, 'fotocopy_surat_keterangan_kehilangan_dari_kepolisian/9bZ3wPUMsk3OQWsbvFg9AcA1vVSY9AA9aUcPq1Q5.png', '2024-10-08 16:43:07', '2024-10-08 16:43:07');
INSERT INTO `t_permohonan_document` VALUES (56, 22, 'fotocopy_dokumen_kepemilikan_jika_ipt_sudah_beralih/V23QHXeaLlet6sE4m8CPxmYKAZTpeJbQfeAHqrsM.png', '2024-10-08 16:43:07', '2024-10-08 16:43:07');
INSERT INTO `t_permohonan_document` VALUES (57, 23, 'fotocopy_ktp/kl9dJjrGNTg395mNSQDewAUgyHvg4QUREXuHETN9.png', '2024-10-09 08:40:29', '2024-10-09 08:40:29');
INSERT INTO `t_permohonan_document` VALUES (58, 23, 'fotocopy_kk/gZLvOY8X2DBSIndTFDYlZxojHH46fXHhrVuSE9ht.png', '2024-10-09 08:40:29', '2024-10-09 08:40:29');
INSERT INTO `t_permohonan_document` VALUES (59, 23, 'fotocopy_legalisir_akta_pendirian_jika_badan_hukum/88fxB3tUh0I0AkHc1qC1LaaUX1FCV3VrFP6uaPEE.png', '2024-10-09 08:40:29', '2024-10-09 08:40:29');
INSERT INTO `t_permohonan_document` VALUES (60, 23, 'fotocopy_skrk/ro5md9NwYqdBIZF5G64Ql0QwtiqvCm4WQEvH4kfn.png', '2024-10-09 08:40:29', '2024-10-09 08:40:29');
INSERT INTO `t_permohonan_document` VALUES (61, 23, 'fotocopy_ipt/LE7JCm9nZqCuLvm7LLyd3SjtLm8vzTJItWzzzKyl.png', '2024-10-09 08:40:29', '2024-10-09 08:40:29');
INSERT INTO `t_permohonan_document` VALUES (62, 23, 'fotocopy_ssrd_atau_tanda_bukti_lunas_retribusi_ipt/01AiYBVAcCKBt7xQ67xdXZvEXBEmGilB7iGSIdAn.png', '2024-10-09 08:40:29', '2024-10-09 08:40:29');
INSERT INTO `t_permohonan_document` VALUES (63, 23, 'fotocopy_dokumen_peralihan/2h8WauaTJFqjxczMHNMpXHW8G8S9gQ2HBUPZtbyp.png', '2024-10-09 08:40:29', '2024-10-09 08:40:29');
INSERT INTO `t_permohonan_document` VALUES (64, 23, 'alasan_peralihan_ipt_kronologi/mHrIqZ5DZaAO3SRrQ7UWLV9LU1sAHWttfJV0WkBP.png', '2024-10-09 08:40:29', '2024-10-09 08:40:29');
INSERT INTO `t_permohonan_document` VALUES (65, 23, 'pengumuman_di_surat_kabar_iklan/6XofJbQtTb8M0rBpQ0ajjbRzVuDddmkBtcTte7b7.png', '2024-10-09 08:40:29', '2024-10-09 08:40:29');
INSERT INTO `t_permohonan_document` VALUES (66, 24, 'fotocopy_ktp/IXAgIhlWMxBUxtIltAKVvCra3k6BcSJXoVAoljwZ.png', '2024-10-09 14:57:53', '2024-10-09 14:57:53');
INSERT INTO `t_permohonan_document` VALUES (67, 24, 'fotocopy_kk/DMboEbf8qPXaQqJezlDYnDlNbXnO76ort3e7mbcq.png', '2024-10-09 14:57:53', '2024-10-09 14:57:53');
INSERT INTO `t_permohonan_document` VALUES (68, 24, 'fotocopy_legalisir_akta_pendirian_jika_badan_hukum/NClezKpo71Ytef8e45y6kFRwHS8bHtdu316oFxJG.png', '2024-10-09 14:57:53', '2024-10-09 14:57:53');
INSERT INTO `t_permohonan_document` VALUES (69, 24, 'fotocopy_skrk/EMtWvZQGXx86ChNKwsDguTrqw1knNjAD9nMGOvLv.png', '2024-10-09 14:57:53', '2024-10-09 14:57:53');
INSERT INTO `t_permohonan_document` VALUES (70, 24, 'fotocopy_ipt/e6XKe8E4RFmck5zWGo5gavRSwBsprBB2USi4GQ3e.png', '2024-10-09 14:57:53', '2024-10-09 14:57:53');
INSERT INTO `t_permohonan_document` VALUES (71, 24, 'fotocopy_ssrd_atau_tanda_bukti_lunas_retribusi_ipt/dg1PIS6MoVEJD1Xn88cS5IERQp9hRVCiKtJvYAme.png', '2024-10-09 14:57:53', '2024-10-09 14:57:53');
INSERT INTO `t_permohonan_document` VALUES (72, 24, 'fotocopy_dokumen_peralihan/pFlTCm8kDYWiwEu5q7j9YzN7YwO1rBbVX677V3LH.png', '2024-10-09 14:57:53', '2024-10-09 14:57:53');
INSERT INTO `t_permohonan_document` VALUES (73, 24, 'alasan_peralihan_ipt_kronologi/TJy87mlHHTYKLXEFkledYGaVJgDm0cbg62bGtv0f.png', '2024-10-09 14:57:53', '2024-10-09 14:57:53');
INSERT INTO `t_permohonan_document` VALUES (74, 24, 'pengumuman_di_surat_kabar_iklan/cmwN44wnbF1iOVpXyaKWsuFzOdLGUml7Kl32sHmx.png', '2024-10-09 14:57:53', '2024-10-09 14:57:53');
INSERT INTO `t_permohonan_document` VALUES (79, 26, 'fotocopy_ktp/w5cdomYU9qtayR4YiXrPzfpNziw2DHpDqfFnYsls.png', '2024-10-10 08:11:30', '2024-10-10 08:11:30');
INSERT INTO `t_permohonan_document` VALUES (80, 26, 'fotocopy_kk/bDSEHhac5YL95CK7tZwCdoE8ezcq73CyK1jA74pF.png', '2024-10-10 08:11:30', '2024-10-10 08:11:30');
INSERT INTO `t_permohonan_document` VALUES (81, 26, 'fotocopy_legalisir_akta_pendirian_jika_badan_hukum/127GqO3qwrPi2w8rbiLv4BiW3ExxMuz7VInFpNFr.png', '2024-10-10 08:11:30', '2024-10-10 08:11:30');
INSERT INTO `t_permohonan_document` VALUES (82, 26, 'fotocopy_dokumen_kepemilikan_jika_ipt_sudah_beralih/UE4xI2tCpXJSHg2rh4yOuv6HFSrtEMMm4xZwk0Ol.png', '2024-10-10 08:11:30', '2024-10-10 08:11:30');
INSERT INTO `t_permohonan_document` VALUES (83, 27, 'fotocopy_ktp/LTuRmWCm31VanBCuYEeors023n1rU1iNLaKpv4vL.png', '2024-10-10 08:34:20', '2024-10-10 08:34:20');
INSERT INTO `t_permohonan_document` VALUES (84, 27, 'fotocopy_kk/WgyCEi0lPyN7x9GsEODloL8QfWsfs9KAo40Hv97G.png', '2024-10-10 08:34:20', '2024-10-10 08:34:20');
INSERT INTO `t_permohonan_document` VALUES (85, 27, 'fotocopy_legalisir_akta_pendirian_jika_badan_hukum/atOjuNS6LGgh4kZUcrBa2VKL601BHJzDqR85DRMI.png', '2024-10-10 08:34:20', '2024-10-10 08:34:20');
INSERT INTO `t_permohonan_document` VALUES (86, 27, 'fotocopy_dokumen_kepemilikan_jika_ipt_sudah_beralih/Ozs8gVTM8rwzlr7yf5RdbfJvNKobsbCjC1o9l9Pk.png', '2024-10-10 08:34:20', '2024-10-10 08:34:20');
INSERT INTO `t_permohonan_document` VALUES (91, 29, 'fotocopy_ktp/xJCRi9Otx9rvwJiQtj5HspMxlaJ9drQqz9spufMx.png', '2024-10-10 09:19:59', '2024-10-10 09:19:59');
INSERT INTO `t_permohonan_document` VALUES (92, 29, 'fotocopy_kk/cgmFvB5cdUqNbL5hhJEKFOcWu3wgbvCgU8aUPBBb.png', '2024-10-10 09:19:59', '2024-10-10 09:19:59');
INSERT INTO `t_permohonan_document` VALUES (93, 29, 'fotocopy_legalisir_akta_pendirian_jika_badan_hukum/quf1kKeCEmvQig0xnWFKA7nC4ne3WQj1EjGJnLzX.png', '2024-10-10 09:19:59', '2024-10-10 09:19:59');
INSERT INTO `t_permohonan_document` VALUES (94, 29, 'fotocopy_dokumen_kepemilikan_jika_ipt_sudah_beralih/Lw5sB98QLyvXf7FMNx7DGR3RudERzxGoRQcZjM6j.png', '2024-10-10 09:19:59', '2024-10-10 09:19:59');
INSERT INTO `t_permohonan_document` VALUES (95, 30, 'fotocopy_ktp/TCvJf2didzyMXh4pVXOLt2awxHch2LyA6OGBJcO4.png', '2024-10-10 10:06:08', '2024-10-10 10:06:08');
INSERT INTO `t_permohonan_document` VALUES (96, 30, 'fotocopy_kk/cgG7eW8xF8rsZT6XAEBuT6ZW82RW9RydI6c3mIP1.png', '2024-10-10 10:06:08', '2024-10-10 10:06:08');
INSERT INTO `t_permohonan_document` VALUES (97, 30, 'fotocopy_surat_keterangan_kehilangan_dari_kepolisian/9CAjXvjMhO5kCgVtYKHqfRTrR0sJnNj6vF6TOMgO.png', '2024-10-10 10:06:08', '2024-10-10 10:06:08');
INSERT INTO `t_permohonan_document` VALUES (98, 30, 'fotocopy_dokumen_kepemilikan_jika_ipt_sudah_beralih/nbgv5v8MbC71EZfBkiAUHMCviJsi4aILnWPKsIKm.png', '2024-10-10 10:06:08', '2024-10-10 10:06:08');
INSERT INTO `t_permohonan_document` VALUES (99, 29, 'formulir/gbPzwShC8YT1qngKrscPEPIx6FxU8BTZ5zkpKTfs.pdf', '2024-10-10 10:41:50', '2024-10-10 10:41:50');
INSERT INTO `t_permohonan_document` VALUES (104, 32, 'fotocopy_ktp/ZpmreytjpA1GFmsV6829Eg8U4wJUistLzwpD9E47.png', '2024-10-10 11:02:08', '2024-10-10 11:02:08');
INSERT INTO `t_permohonan_document` VALUES (105, 32, 'fotocopy_kk/dEni5IxptpVKdmXycFqsXY22Ts5nSYrSD5sOzQWG.png', '2024-10-10 11:02:08', '2024-10-10 11:02:08');
INSERT INTO `t_permohonan_document` VALUES (106, 32, 'fotocopy_legalisir_akta_pendirian_jika_badan_hukum/Nz5YKv5RLqkoPZgjq5MMLtIhuAPNMIrvglpKysHI.png', '2024-10-10 11:02:08', '2024-10-10 11:02:08');
INSERT INTO `t_permohonan_document` VALUES (107, 32, 'fotocopy_dokumen_kepemilikan_jika_ipt_sudah_beralih/WU8WVoMevEDdtRPXg6ODrFKRmJ9I4d8pmC6e4cy0.png', '2024-10-10 11:02:08', '2024-10-10 11:02:08');
INSERT INTO `t_permohonan_document` VALUES (111, 32, 'formulir/CchWs0P8PwCCyU6EeppqoFKKUnjOcfiv80ni2VzN.pdf', '2024-10-10 11:24:57', '2024-10-10 11:24:57');
INSERT INTO `t_permohonan_document` VALUES (112, 33, 'fotocopy_ktp/w8SSOGiUDbV4d4G0zBcIC5YIUCfQmhWqwoTBPoWK.png', '2024-10-10 15:05:49', '2024-10-10 15:05:49');
INSERT INTO `t_permohonan_document` VALUES (113, 33, 'fotocopy_kk/ToR6K21havdVX3bjdsCleSYqKCXpcvByszJeDycU.png', '2024-10-10 15:05:49', '2024-10-10 15:05:49');
INSERT INTO `t_permohonan_document` VALUES (114, 33, 'fotocopy_legalisir_akta_pendirian_jika_badan_hukum/McplajFfMNROPoN3YOvJwElIk5qG8Pcqr1KjTSKM.png', '2024-10-10 15:05:49', '2024-10-10 15:05:49');
INSERT INTO `t_permohonan_document` VALUES (115, 33, 'fotocopy_skrk/BTW8uq34ZEYUXl9RV3UHYTL7idE4veh7LPmd9vOq.png', '2024-10-10 15:05:49', '2024-10-10 15:05:49');
INSERT INTO `t_permohonan_document` VALUES (116, 33, 'fotocopy_ipt/XhQ4r1uguxCGHU7pEADPY3qWbIxoM18iDzW8qmBG.png', '2024-10-10 15:05:49', '2024-10-10 15:05:49');
INSERT INTO `t_permohonan_document` VALUES (117, 33, 'fotocopy_ssrd_atau_tanda_bukti_lunas_retribusi_ipt/Qe3ASQqlZSnhE2EXWZrHVAz8VaEXGCdBNSqRW8NP.png', '2024-10-10 15:05:49', '2024-10-10 15:05:49');
INSERT INTO `t_permohonan_document` VALUES (118, 33, 'fotocopy_dokumen_peralihan/pX505pvRvb9WD6HIm4R2OEx4rxxnb1JgO1SvpX1M.png', '2024-10-10 15:05:49', '2024-10-10 15:05:49');
INSERT INTO `t_permohonan_document` VALUES (119, 33, 'alasan_peralihan_ipt_kronologi/CPYI0ciVtmbePPN5wBhrBc8s5jsHTYOECjSbApua.png', '2024-10-10 15:05:49', '2024-10-10 15:05:49');
INSERT INTO `t_permohonan_document` VALUES (120, 33, 'pengumuman_di_surat_kabar_iklan/UowcLDCEGlL49rG7PammVor2GhqjCpk0aoAodWXF.png', '2024-10-10 15:05:49', '2024-10-10 15:05:49');
INSERT INTO `t_permohonan_document` VALUES (121, 33, 'formulir/j3vymIKoVMPWnk5WFhYEb9ZnRNI8qMWgDD5V8e9j.pdf', '2024-10-10 15:16:46', '2024-10-10 15:16:46');
INSERT INTO `t_permohonan_document` VALUES (122, 34, 'fotocopy_ktp/YIfzXnGNjJe4HJWIiZvnsaTEuojj7vw94yxBNulX.png', '2024-10-11 09:09:49', '2024-10-11 09:09:49');
INSERT INTO `t_permohonan_document` VALUES (123, 34, 'fotocopy_kk/z9o8QCoGmNr8xBkSmPqvmbzPaGtYuAGF4L9fqCZQ.png', '2024-10-11 09:09:49', '2024-10-11 09:09:49');
INSERT INTO `t_permohonan_document` VALUES (124, 34, 'fotocopy_surat_keterangan_kehilangan_dari_kepolisian/ZFdkLyizPvvzr2fFO1ifDhzbjA1TeZlVitXOzsEq.png', '2024-10-11 09:09:49', '2024-10-11 09:09:49');
INSERT INTO `t_permohonan_document` VALUES (125, 34, 'fotocopy_dokumen_kepemilikan_jika_ipt_sudah_beralih/C3U6zF7VMyCDd90mctYAIwi8N6KF5ryq5vid6Pak.png', '2024-10-11 09:09:49', '2024-10-11 09:09:49');
INSERT INTO `t_permohonan_document` VALUES (126, 34, 'formulir/rPKze5e9muluI2xbYEbcVYPyBrMtwc6it26W1Nip.pdf', '2024-10-11 09:20:59', '2024-10-11 09:20:59');
INSERT INTO `t_permohonan_document` VALUES (127, 35, 'fotocopy_ktp/Pl6XxsgKSANyQjq5H57e4XaV9EJB2DEbCnTSfSmb.png', '2024-10-14 09:30:39', '2024-10-14 09:30:39');
INSERT INTO `t_permohonan_document` VALUES (128, 35, 'fotocopy_kk/mOInaPDiHBIolfDASVZQ78mZcNMLqkq1AIvU3nIK.png', '2024-10-14 09:30:39', '2024-10-14 09:30:39');
INSERT INTO `t_permohonan_document` VALUES (129, 35, 'fotocopy_legalisir_akta_pendirian_jika_badan_hukum/R75ONSRYVZa93mrsNDamDoxIr8RXD7fAvfsnxuMI.png', '2024-10-14 09:30:39', '2024-10-14 09:30:39');
INSERT INTO `t_permohonan_document` VALUES (130, 35, 'fotocopy_dokumen_kepemilikan_jika_ipt_sudah_beralih/YiKhZea1Ub6gqvisCsqolQBmfNEARMCMdZYKF54P.png', '2024-10-14 09:30:39', '2024-10-14 09:30:39');
INSERT INTO `t_permohonan_document` VALUES (131, 35, 'formulir/iwAAeSvTNCXFDhq4jITLENPtUJQ6Dy8zV3SZA24E.pdf', '2024-10-14 09:31:21', '2024-10-14 09:31:21');
INSERT INTO `t_permohonan_document` VALUES (132, 36, 'fotocopy_ktp/89fMV64lWTQJm433E7eyZM5WyYPKRT1bbvrdP5MU.png', '2024-10-14 09:47:58', '2024-10-14 09:47:58');
INSERT INTO `t_permohonan_document` VALUES (133, 36, 'fotocopy_kk/2U7XJueJTe4y3DuUO3Ouu7h91atp8cbtDYVtZOlw.png', '2024-10-14 09:47:58', '2024-10-14 09:47:58');
INSERT INTO `t_permohonan_document` VALUES (134, 36, 'fotocopy_surat_keterangan_kehilangan_dari_kepolisian/o55CY604OnI0V6cMJSDKQ1rbZ20Ehbvol9Bu5UZ2.png', '2024-10-14 09:47:58', '2024-10-14 09:47:58');
INSERT INTO `t_permohonan_document` VALUES (135, 36, 'fotocopy_dokumen_kepemilikan_jika_ipt_sudah_beralih/T38pzmfBzen2RNwaLlNNC9Hm6J1nN9QypNsj4VBr.png', '2024-10-14 09:47:58', '2024-10-14 09:47:58');
INSERT INTO `t_permohonan_document` VALUES (136, 36, 'formulir/G30qeqrdjdBtkVCoPsE7hoiULeh4oYFg5dPZsyyF.pdf', '2024-10-14 09:48:41', '2024-10-14 09:48:41');
INSERT INTO `t_permohonan_document` VALUES (137, 37, 'fotocopy_ktp/TuZSgWvtsCPyJvFgpPgxgMYtcF7PubyBFHol0Abn.png', '2024-10-14 09:53:17', '2024-10-14 09:53:17');
INSERT INTO `t_permohonan_document` VALUES (138, 37, 'fotocopy_kk/Ukv5MPezKOEqs8qi01BFJPyEwcAJz5GO4tPCUKuC.png', '2024-10-14 09:53:17', '2024-10-14 09:53:17');
INSERT INTO `t_permohonan_document` VALUES (139, 37, 'fotocopy_legalisir_akta_pendirian_jika_badan_hukum/meO2Kj1jvtm76b0wlfdXi0KuHiyGoDW2kPPis67q.png', '2024-10-14 09:53:17', '2024-10-14 09:53:17');
INSERT INTO `t_permohonan_document` VALUES (140, 37, 'fotocopy_skrk/rqGV6xBANv3BKo9x95D4MVzdQTy5EMRm2Uki9lgk.png', '2024-10-14 09:53:17', '2024-10-14 09:53:17');
INSERT INTO `t_permohonan_document` VALUES (141, 37, 'fotocopy_ipt/ZJynwONBuyQV0GbkoqJuMxjgLveYhVIKVZwmwqZI.png', '2024-10-14 09:53:17', '2024-10-14 09:53:17');
INSERT INTO `t_permohonan_document` VALUES (142, 37, 'fotocopy_ssrd_atau_tanda_bukti_lunas_retribusi_ipt/WqukD9SPOFuvsi9zV3tsuoBHKLNlIdvTxKQZDsA0.png', '2024-10-14 09:53:17', '2024-10-14 09:53:17');
INSERT INTO `t_permohonan_document` VALUES (143, 37, 'fotocopy_dokumen_peralihan/t6pWltK9jq6P30Nk6Z1BsImxC6fDEvBoZ599nAs3.png', '2024-10-14 09:53:17', '2024-10-14 09:53:17');
INSERT INTO `t_permohonan_document` VALUES (144, 37, 'alasan_peralihan_ipt_kronologi/qClulfBrVuQvahtEyBgYZ39pYX6cKkoTAFUbdQxQ.png', '2024-10-14 09:53:17', '2024-10-14 09:53:17');
INSERT INTO `t_permohonan_document` VALUES (145, 37, 'pengumuman_di_surat_kabar_iklan/MXSc2Fu0bXPhXB1RbYFnpEKsu7ZkqcquJJM3Lcds.png', '2024-10-14 09:53:17', '2024-10-14 09:53:17');
INSERT INTO `t_permohonan_document` VALUES (146, 37, 'formulir/QEg9LWuIPeFCGhxItiNOZN9c442NzTz18XewEUSi.png', '2024-10-14 09:54:00', '2024-10-14 09:54:00');
INSERT INTO `t_permohonan_document` VALUES (147, 38, 'fotocopy_ktp/KtnFd0OPru716XsSmNEOmt5pNCRWopOej5Fdv2G1.png', '2024-10-14 13:58:25', '2024-10-14 13:58:25');
INSERT INTO `t_permohonan_document` VALUES (148, 38, 'fotocopy_kk/R34nObRreuCL4YT3AUXqQb65vdLdm77Zc3Q40e12.png', '2024-10-14 13:58:25', '2024-10-14 13:58:25');
INSERT INTO `t_permohonan_document` VALUES (149, 38, 'fotocopy_legalisir_akta_pendirian_jika_badan_hukum/Orx6C9kilV2pMWaugs9uJFkIanKJ0TsVDXd3fODb.png', '2024-10-14 13:58:25', '2024-10-14 13:58:25');
INSERT INTO `t_permohonan_document` VALUES (150, 38, 'fotocopy_dokumen_kepemilikan_jika_ipt_sudah_beralih/TTFdToKjYyqayxKEgM7HbkGsgmJWoKwDBVTnnGQ6.png', '2024-10-14 13:58:25', '2024-10-14 13:58:25');
INSERT INTO `t_permohonan_document` VALUES (151, 38, 'formulir/TCD5Rb0NOtu17R3gGPxlMwly2RS2CuVa0egpkifB.pdf', '2024-10-14 13:59:54', '2024-10-14 13:59:54');
INSERT INTO `t_permohonan_document` VALUES (152, 39, 'fotocopy_ktp/4y3qFnMcqTvF076W8AIvqxIVJ1eNa5o8BoiDxEGq.png', '2024-10-14 14:49:10', '2024-10-14 14:49:10');
INSERT INTO `t_permohonan_document` VALUES (153, 39, 'fotocopy_kk/OB2Bxns9JK35B5Sjgd7RNXEHLQ7A1QI5dsduqEAV.png', '2024-10-14 14:52:18', '2024-10-14 14:52:18');
INSERT INTO `t_permohonan_document` VALUES (154, 39, 'fotocopy_legalisir_akta_pendirian_jika_badan_hukum/yFiGaX0yNSfsKYMJ5gHLuFi37VNCW4ZSPGhp8Ycj.png', '2024-10-14 14:52:25', '2024-10-14 14:52:25');
INSERT INTO `t_permohonan_document` VALUES (155, 39, 'fotocopy_dokumen_kepemilikan_jika_ipt_sudah_beralih/pviRPwgjpFm58gxtrhZSIwu4TuVakoAkLXDYYRkn.png', '2024-10-14 14:52:32', '2024-10-14 14:52:32');
INSERT INTO `t_permohonan_document` VALUES (156, 39, 'formulir/PkwHGu62X7gHICKMYWCMDVa96j1M9rMVz0o74T5i.png', '2024-10-14 14:52:39', '2024-10-14 14:52:39');
INSERT INTO `t_permohonan_document` VALUES (157, 40, 'fotocopy_ktp/leb1zFu0wGu0nIxXrdffQ8cVXJQhR9d9D25GrpAI.png', '2024-10-14 15:28:01', '2024-10-14 15:28:01');
INSERT INTO `t_permohonan_document` VALUES (158, 40, 'fotocopy_kk/FK97TqKJib0cx7KZ1WeJthoMDbXuVCkyp1NZYv7o.png', '2024-10-14 15:28:09', '2024-10-14 15:28:09');
INSERT INTO `t_permohonan_document` VALUES (159, 40, 'fotocopy_legalisir_akta_pendirian_jika_badan_hukum/mBwyTm9DofF5GYxaT8lxnfNC53ZRpbvPJyF8CkaZ.png', '2024-10-14 15:28:16', '2024-10-14 15:28:16');
INSERT INTO `t_permohonan_document` VALUES (160, 40, 'fotocopy_dokumen_kepemilikan_jika_ipt_sudah_beralih/5Pwn5CoGTkl2K3NJIyueOQBT0TRSlQRqilEZDrbH.png', '2024-10-14 15:28:23', '2024-10-14 15:28:23');
INSERT INTO `t_permohonan_document` VALUES (161, 40, 'formulir/kfXxld9FWW3DAIc4zYvSBYkAvC0xFqOqorfBBY0r.png', '2024-10-14 15:28:29', '2024-10-14 15:28:29');
INSERT INTO `t_permohonan_document` VALUES (162, 41, 'fotocopy_ktp/MpFySGoE7g8SZrv3uLP13qtk0hpe5NmR4A0g4GW1.png', '2024-10-15 09:58:52', '2024-10-15 09:58:52');
INSERT INTO `t_permohonan_document` VALUES (163, 41, 'fotocopy_ktp/hMoxFT1Bo8Ztxy8GSi4aUvn5OWHd5DoToyRKh8So.png', '2024-10-15 10:00:44', '2024-10-15 10:00:44');
INSERT INTO `t_permohonan_document` VALUES (164, 41, 'fotocopy_ktp/cJafsXtvNrFZ0HQpT8FHvU48sTGaklIDvRS7oMb8.png', '2024-10-15 10:03:05', '2024-10-15 10:03:05');
INSERT INTO `t_permohonan_document` VALUES (165, 41, 'fotocopy_kk/FqhfO9H7l0Nv3GdbJcGfpRRSIpPo9thiFjFES8Nk.png', '2024-10-15 10:03:20', '2024-10-15 10:03:20');
INSERT INTO `t_permohonan_document` VALUES (166, 41, 'fotocopy_legalisir_akta_pendirian_jika_badan_hukum/BOOXtJ7Yu8QFsxtr9hISB15Ae97JPXOHfBBATwuM.png', '2024-10-15 10:03:30', '2024-10-15 10:03:30');
INSERT INTO `t_permohonan_document` VALUES (167, 41, 'fotocopy_dokumen_kepemilikan_jika_ipt_sudah_beralih/Ced9N3eDv9sZq1MDS39AFukHCgTwsMIDhk8huEHP.png', '2024-10-15 10:03:46', '2024-10-15 10:03:46');
INSERT INTO `t_permohonan_document` VALUES (168, 41, 'formulir/ICHtor2hevj1YNJdhNAyiQvCYqsLyzlY4nRHgPYz.png', '2024-10-15 10:03:57', '2024-10-15 10:03:57');
INSERT INTO `t_permohonan_document` VALUES (169, 42, 'fotocopy_ktp/tJYKTt02wQNbLEmpIAryqqwv5YQshQjhmc9vgDZ0.png', '2024-10-15 10:23:14', '2024-10-15 10:23:14');
INSERT INTO `t_permohonan_document` VALUES (170, 42, 'fotocopy_kk/lfoqtdeMgsZnU32YlkCROlr3u7KWQh31AwjhxtLN.png', '2024-10-15 10:23:23', '2024-10-15 10:23:23');
INSERT INTO `t_permohonan_document` VALUES (171, 42, 'fotocopy_legalisir_akta_pendirian_jika_badan_hukum/x4KRHW8m6zVPrvsICkCk0BWFjjTRnetWfMyakYSh.png', '2024-10-15 10:23:44', '2024-10-15 10:23:44');
INSERT INTO `t_permohonan_document` VALUES (172, 42, 'fotocopy_skrk/DoW306HCM69FRom8oakG4q97xJo4CbVWbFqdNikh.png', '2024-10-15 10:23:55', '2024-10-15 10:23:55');
INSERT INTO `t_permohonan_document` VALUES (173, 42, 'fotocopy_ipt/xKWw5tWH076dOHN6HIihQFSNmsewznqHQ9BORUyC.png', '2024-10-15 10:24:07', '2024-10-15 10:24:07');
INSERT INTO `t_permohonan_document` VALUES (174, 42, 'fotocopy_ssrd_atau_tanda_bukti_lunas_retribusi_ipt/Z61z9XglCjASmjfhSLmNcpB8ptshrSCqNVglQet8.png', '2024-10-15 10:24:19', '2024-10-15 10:24:19');
INSERT INTO `t_permohonan_document` VALUES (175, 42, 'fotocopy_dokumen_peralihan/qYWferSOxDth3BdsLZvBkKpmzepbAxQXZkkUqeYs.png', '2024-10-15 10:24:32', '2024-10-15 10:24:32');
INSERT INTO `t_permohonan_document` VALUES (176, 42, 'alasan_peralihan_ipt_kronologi/CVgGdwaCdJW3EgzAL0C68Cxd0P2DzP0K33xwg1EY.png', '2024-10-15 10:24:41', '2024-10-15 10:24:41');
INSERT INTO `t_permohonan_document` VALUES (177, 42, 'pengumuman_di_surat_kabar_iklan/VJ52nAvpGQATP2w9Aq2ZsNdoITPOc6JUcRJT3bHI.png', '2024-10-15 10:24:49', '2024-10-15 10:24:49');
INSERT INTO `t_permohonan_document` VALUES (178, 42, 'formulir/1qpKG5sZF6RpBjxJ6lB6k64FfwnyQCA3f1mE73gM.png', '2024-10-15 10:24:58', '2024-10-15 10:24:58');

-- ----------------------------
-- Table structure for t_permohonan_history
-- ----------------------------
DROP TABLE IF EXISTS `t_permohonan_history`;
CREATE TABLE `t_permohonan_history`  (
  `id` int NOT NULL AUTO_INCREMENT,
  `id_permohonan` int NOT NULL,
  `id_status` int NOT NULL,
  `tgl_status` date NULL DEFAULT NULL,
  `nm_status` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NULL DEFAULT NULL,
  `id_verifikator` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NULL DEFAULT NULL,
  `nama_verifikator` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NULL DEFAULT NULL,
  `keterangan` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 170 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_0900_ai_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of t_permohonan_history
-- ----------------------------
INSERT INTO `t_permohonan_history` VALUES (1, 19, 2, '2024-10-08', 'VALIDASI DOKUMEN', '6', 'MOCH IRSYADUL ANAM', 'oke lah', '2024-10-08 09:00:28', '2024-10-08 09:00:28');
INSERT INTO `t_permohonan_history` VALUES (4, 19, 3, '2024-10-08', 'PEMBUATAN FILE BAP', '6', 'MOCH IRSYADUL ANAM', 'tester', '2024-10-08 10:43:09', '2024-10-08 10:43:09');
INSERT INTO `t_permohonan_history` VALUES (5, 21, 2, '2024-10-08', 'VALIDASI DOKUMEN', '6', 'MOCH IRSYADUL ANAM', 'oke, data bers', '2024-10-08 14:26:33', '2024-10-08 14:26:33');
INSERT INTO `t_permohonan_history` VALUES (6, 21, 3, '2024-10-08', 'PEMBUATAN FILE BAP', '6', 'MOCH IRSYADUL ANAM', 'p', '2024-10-08 14:29:05', '2024-10-08 14:29:05');
INSERT INTO `t_permohonan_history` VALUES (8, 21, 4, '2024-10-08', 'PEMBUATAN KONSEP SURAT', '6', 'MOCH IRSYADUL ANAM', 'tester', '2024-10-08 14:52:19', '2024-10-08 14:52:19');
INSERT INTO `t_permohonan_history` VALUES (9, 21, 5, '2024-10-08', 'VALIDASI KETUA ', '6', 'MOCH IRSYADUL ANAM', NULL, '2024-10-08 14:56:33', '2024-10-08 14:56:33');
INSERT INTO `t_permohonan_history` VALUES (10, 21, 6, '2024-10-08', 'VERIFIKASI KABID', '6', 'MOCH IRSYADUL ANAM', 'verif kabid', '2024-10-08 14:58:58', '2024-10-08 14:58:58');
INSERT INTO `t_permohonan_history` VALUES (11, 21, 7, '2024-10-08', 'VERIVIKASI SEKRETARIS', '6', 'MOCH IRSYADUL ANAM', 'verif sekrtaris', '2024-10-08 14:59:09', '2024-10-08 14:59:09');
INSERT INTO `t_permohonan_history` VALUES (12, 21, 8, '2024-10-08', 'VERIVIKASI KA BPKAD', '6', 'MOCH IRSYADUL ANAM', 'tester', '2024-10-08 14:59:17', '2024-10-08 14:59:17');
INSERT INTO `t_permohonan_history` VALUES (13, 23, 2, '2024-10-09', 'VALIDASI DOKUMEN', '6', 'MOCH IRSYADUL ANAM', NULL, '2024-10-09 08:40:47', '2024-10-09 08:40:47');
INSERT INTO `t_permohonan_history` VALUES (14, 23, 3, '2024-10-09', 'PEMBUATAN FILE BAP', '6', 'MOCH IRSYADUL ANAM', NULL, '2024-10-09 08:41:43', '2024-10-09 08:41:43');
INSERT INTO `t_permohonan_history` VALUES (15, 23, 4, '2024-10-09', 'PEMBUATAN KONSEP SURAT', '6', 'MOCH IRSYADUL ANAM', NULL, '2024-10-09 08:42:18', '2024-10-09 08:42:18');
INSERT INTO `t_permohonan_history` VALUES (16, 23, 5, '2024-10-09', 'VALIDASI KETUA ', '6', 'MOCH IRSYADUL ANAM', NULL, '2024-10-09 08:42:27', '2024-10-09 08:42:27');
INSERT INTO `t_permohonan_history` VALUES (17, 23, 6, '2024-10-09', 'VERIFIKASI KABID', '6', 'MOCH IRSYADUL ANAM', NULL, '2024-10-09 08:42:35', '2024-10-09 08:42:35');
INSERT INTO `t_permohonan_history` VALUES (18, 23, 7, '2024-10-09', 'VERIVIKASI SEKRETARIS', '6', 'MOCH IRSYADUL ANAM', NULL, '2024-10-09 08:42:42', '2024-10-09 08:42:42');
INSERT INTO `t_permohonan_history` VALUES (19, 23, 8, '2024-10-09', 'VERIVIKASI KA BPKAD', '6', 'MOCH IRSYADUL ANAM', NULL, '2024-10-09 08:42:50', '2024-10-09 08:42:50');
INSERT INTO `t_permohonan_history` VALUES (20, 22, 2, '2024-10-09', 'VALIDASI DOKUMEN', '6', 'MOCH IRSYADUL ANAM', NULL, '2024-10-09 09:21:46', '2024-10-09 09:21:46');
INSERT INTO `t_permohonan_history` VALUES (21, 22, 3, '2024-10-09', 'PEMBUATAN FILE BAP', '6', 'MOCH IRSYADUL ANAM', 'tester', '2024-10-09 09:33:52', '2024-10-09 09:33:52');
INSERT INTO `t_permohonan_history` VALUES (22, 22, 5, '2024-10-09', 'VALIDASI KETUA ', '6', 'MOCH IRSYADUL ANAM', 'tester', '2024-10-09 14:21:25', '2024-10-09 14:21:25');
INSERT INTO `t_permohonan_history` VALUES (23, 22, 6, '2024-10-09', 'VERIFIKASI KABID', '6', 'MOCH IRSYADUL ANAM', NULL, '2024-10-09 14:21:30', '2024-10-09 14:21:30');
INSERT INTO `t_permohonan_history` VALUES (24, 22, 7, '2024-10-09', 'VERIVIKASI SEKRETARIS', '6', 'MOCH IRSYADUL ANAM', NULL, '2024-10-09 14:21:34', '2024-10-09 14:21:34');
INSERT INTO `t_permohonan_history` VALUES (25, 22, 8, '2024-10-09', 'VERIVIKASI KA BPKAD', '6', 'MOCH IRSYADUL ANAM', NULL, '2024-10-09 14:21:38', '2024-10-09 14:21:38');
INSERT INTO `t_permohonan_history` VALUES (26, 24, 2, '2024-10-09', 'VALIDASI DOKUMEN', '6', 'MOCH IRSYADUL ANAM', 'tester', '2024-10-09 15:01:32', '2024-10-09 15:01:32');
INSERT INTO `t_permohonan_history` VALUES (27, 24, 2, '2024-10-09', 'VALIDASI DOKUMEN', '6', 'MOCH IRSYADUL ANAM', NULL, '2024-10-09 15:08:51', '2024-10-09 15:08:51');
INSERT INTO `t_permohonan_history` VALUES (28, 24, 2, '2024-10-09', 'VALIDASI DOKUMEN', '6', 'MOCH IRSYADUL ANAM', NULL, '2024-10-09 15:10:15', '2024-10-09 15:10:15');
INSERT INTO `t_permohonan_history` VALUES (29, 24, 99, '2024-10-09', 'REJECT', '6', 'MOCH IRSYADUL ANAM', NULL, '2024-10-09 15:11:16', '2024-10-09 15:11:16');
INSERT INTO `t_permohonan_history` VALUES (30, 24, 2, '2024-10-09', 'VALIDASI DOKUMEN', '6', 'MOCH IRSYADUL ANAM', NULL, '2024-10-09 15:11:36', '2024-10-09 15:11:36');
INSERT INTO `t_permohonan_history` VALUES (31, 24, 3, '2024-10-09', 'PEMBUATAN FILE BAP', '6', 'MOCH IRSYADUL ANAM', 'tester', '2024-10-09 15:12:12', '2024-10-09 15:12:12');
INSERT INTO `t_permohonan_history` VALUES (32, 24, 99, '2024-10-09', 'REJECT', '6', 'MOCH IRSYADUL ANAM', 'Surat Ditolak', '2024-10-09 15:14:47', '2024-10-09 15:14:47');
INSERT INTO `t_permohonan_history` VALUES (33, 24, 5, '2024-10-09', 'VALIDASI KETUA ', '6', 'MOCH IRSYADUL ANAM', 'okey', '2024-10-09 15:22:56', '2024-10-09 15:22:56');
INSERT INTO `t_permohonan_history` VALUES (34, 24, 6, '2024-10-09', 'VERIFIKASI KABID', '6', 'MOCH IRSYADUL ANAM', NULL, '2024-10-09 15:23:56', '2024-10-09 15:23:56');
INSERT INTO `t_permohonan_history` VALUES (35, 24, 7, '2024-10-09', 'VERIVIKASI SEKRETARIS', '6', 'MOCH IRSYADUL ANAM', NULL, '2024-10-09 15:24:04', '2024-10-09 15:24:04');
INSERT INTO `t_permohonan_history` VALUES (36, 24, 8, '2024-10-09', 'VERIVIKASI KA BPKAD', '6', 'MOCH IRSYADUL ANAM', NULL, '2024-10-09 15:24:08', '2024-10-09 15:24:08');
INSERT INTO `t_permohonan_history` VALUES (37, 26, 1, '2024-10-10', 'SUBMIT', '6', 'MOCH IRSYADUL ANAM', 'Permohonan Dibuat', '2024-10-10 08:11:31', '2024-10-10 08:11:31');
INSERT INTO `t_permohonan_history` VALUES (38, 26, 2, '2024-10-10', 'VALIDASI DOKUMEN', '6', 'MOCH IRSYADUL ANAM', 'data valid', '2024-10-10 08:13:43', '2024-10-10 08:13:43');
INSERT INTO `t_permohonan_history` VALUES (39, 26, 3, '2024-10-10', 'PEMBUATAN FILE BAP', '6', 'MOCH IRSYADUL ANAM', 'Diperuntukan untuk buka warung', '2024-10-10 08:14:29', '2024-10-10 08:14:29');
INSERT INTO `t_permohonan_history` VALUES (40, 26, 5, '2024-10-10', 'VALIDASI KETUA ', '6', 'MOCH IRSYADUL ANAM', NULL, '2024-10-10 08:16:34', '2024-10-10 08:16:34');
INSERT INTO `t_permohonan_history` VALUES (41, 26, 6, '2024-10-10', 'VERIFIKASI KABID', '6', 'MOCH IRSYADUL ANAM', NULL, '2024-10-10 08:16:41', '2024-10-10 08:16:41');
INSERT INTO `t_permohonan_history` VALUES (42, 26, 7, '2024-10-10', 'VERIVIKASI SEKRETARIS', '6', 'MOCH IRSYADUL ANAM', NULL, '2024-10-10 08:16:47', '2024-10-10 08:16:47');
INSERT INTO `t_permohonan_history` VALUES (43, 26, 8, '2024-10-10', 'VERIVIKASI KA BPKAD', '6', 'MOCH IRSYADUL ANAM', NULL, '2024-10-10 08:16:51', '2024-10-10 08:16:51');
INSERT INTO `t_permohonan_history` VALUES (44, 27, 1, '2024-10-10', 'SUBMIT', '27435', 'pemohon', 'Permohonan Dibuat', '2024-10-10 08:34:20', '2024-10-10 08:34:20');
INSERT INTO `t_permohonan_history` VALUES (45, 27, 2, '2024-10-10', 'VALIDASI DOKUMEN', '6', 'MOCH IRSYADUL ANAM', 'Berkas Lengkap, Lanjut', '2024-10-10 08:37:37', '2024-10-10 08:37:37');
INSERT INTO `t_permohonan_history` VALUES (46, 27, 3, '2024-10-10', 'PEMBUATAN FILE BAP', '6', 'MOCH IRSYADUL ANAM', 'sudah di survery', '2024-10-10 08:39:43', '2024-10-10 08:39:43');
INSERT INTO `t_permohonan_history` VALUES (47, 27, 5, '2024-10-10', 'VALIDASI KETUA ', '6', 'MOCH IRSYADUL ANAM', 'surat oke', '2024-10-10 08:46:10', '2024-10-10 08:46:10');
INSERT INTO `t_permohonan_history` VALUES (48, 27, 99, '2024-10-10', 'REJECT', '6', 'MOCH IRSYADUL ANAM', NULL, '2024-10-10 08:47:27', '2024-10-10 08:47:27');
INSERT INTO `t_permohonan_history` VALUES (49, 27, 99, '2024-10-10', 'REJECT', '6', 'MOCH IRSYADUL ANAM', NULL, '2024-10-10 08:47:37', '2024-10-10 08:47:37');
INSERT INTO `t_permohonan_history` VALUES (50, 27, 5, '2024-10-10', 'VALIDASI KETUA ', '6', 'MOCH IRSYADUL ANAM', NULL, '2024-10-10 08:48:02', '2024-10-10 08:48:02');
INSERT INTO `t_permohonan_history` VALUES (51, 27, 6, '2024-10-10', 'VERIFIKASI KABID', '6', 'MOCH IRSYADUL ANAM', NULL, '2024-10-10 08:48:08', '2024-10-10 08:48:08');
INSERT INTO `t_permohonan_history` VALUES (52, 27, 7, '2024-10-10', 'VERIVIKASI SEKRETARIS', '6', 'MOCH IRSYADUL ANAM', NULL, '2024-10-10 08:48:24', '2024-10-10 08:48:24');
INSERT INTO `t_permohonan_history` VALUES (53, 27, 8, '2024-10-10', 'VERIVIKASI KA BPKAD', '6', 'MOCH IRSYADUL ANAM', NULL, '2024-10-10 08:48:41', '2024-10-10 08:48:41');
INSERT INTO `t_permohonan_history` VALUES (54, 28, 1, '2024-10-10', 'SUBMIT', '6', 'MOCH IRSYADUL ANAM', 'Permohonan Dibuat', '2024-10-10 09:05:17', '2024-10-10 09:05:17');
INSERT INTO `t_permohonan_history` VALUES (55, 29, 1, '2024-10-10', 'SUBMIT', '6', 'MOCH IRSYADUL ANAM', 'Permohonan Dibuat', '2024-10-10 09:19:59', '2024-10-10 09:19:59');
INSERT INTO `t_permohonan_history` VALUES (56, 30, 1, '2024-10-10', 'SUBMIT', '6', 'MOCH IRSYADUL ANAM', 'Permohonan Dibuat', '2024-10-10 10:06:08', '2024-10-10 10:06:08');
INSERT INTO `t_permohonan_history` VALUES (58, 32, 0, '2024-10-10', 'BELUM UPLOAD FORMULIR', '6', 'MOCH IRSYADUL ANAM', 'Permohonan Dibuat', '2024-10-10 11:02:08', '2024-10-10 11:02:08');
INSERT INTO `t_permohonan_history` VALUES (59, 32, 1, NULL, 'SUBMIT', '6', 'MOCH IRSYADUL ANAM', 'Berhasil Upload Formulir', '2024-10-10 11:23:53', '2024-10-10 11:23:53');
INSERT INTO `t_permohonan_history` VALUES (60, 32, 1, '2024-10-10', 'SUBMIT', '6', 'MOCH IRSYADUL ANAM', 'Berhasil Upload Formulir', '2024-10-10 11:24:57', '2024-10-10 11:24:57');
INSERT INTO `t_permohonan_history` VALUES (61, 32, 2, '2024-10-10', 'VALIDASI DOKUMEN', '6', 'MOCH IRSYADUL ANAM', 'berkas oke', '2024-10-10 11:35:27', '2024-10-10 11:35:27');
INSERT INTO `t_permohonan_history` VALUES (62, 32, 3, '2024-10-10', 'PEMBUATAN FILE BAP', '6', 'MOCH IRSYADUL ANAM', 'tester', '2024-10-10 11:42:10', '2024-10-10 11:42:10');
INSERT INTO `t_permohonan_history` VALUES (63, 32, 3, '2024-10-10', 'PEMBUATAN FILE BAP', '6', 'MOCH IRSYADUL ANAM', 'tester', '2024-10-10 11:45:59', '2024-10-10 11:45:59');
INSERT INTO `t_permohonan_history` VALUES (64, 33, 0, '2024-10-10', 'BELUM UPLOAD FORMULIR', '6', 'MOCH IRSYADUL ANAM', 'Permohonan Dibuat', '2024-10-10 15:05:49', '2024-10-10 15:05:49');
INSERT INTO `t_permohonan_history` VALUES (65, 33, 1, '2024-10-10', 'SUBMIT', '6', 'MOCH IRSYADUL ANAM', 'Berhasil Upload Formulir', '2024-10-10 15:16:46', '2024-10-10 15:16:46');
INSERT INTO `t_permohonan_history` VALUES (66, 33, 2, '2024-10-10', 'VALIDASI DOKUMEN', '6', 'MOCH IRSYADUL ANAM', NULL, '2024-10-10 15:17:04', '2024-10-10 15:17:04');
INSERT INTO `t_permohonan_history` VALUES (67, 33, 3, '2024-10-10', 'PEMBUATAN FILE BAP', '6', 'MOCH IRSYADUL ANAM', NULL, '2024-10-10 15:17:25', '2024-10-10 15:17:25');
INSERT INTO `t_permohonan_history` VALUES (68, 32, 5, '2024-10-11', 'VALIDASI KETUA ', '6', 'MOCH IRSYADUL ANAM', NULL, '2024-10-11 08:39:25', '2024-10-11 08:39:25');
INSERT INTO `t_permohonan_history` VALUES (69, 32, 99, '2024-10-11', 'REJECT', '6', 'MOCH IRSYADUL ANAM', NULL, '2024-10-11 08:41:15', '2024-10-11 08:41:15');
INSERT INTO `t_permohonan_history` VALUES (70, 32, 5, '2024-10-11', 'VALIDASI KETUA ', '6', 'MOCH IRSYADUL ANAM', NULL, '2024-10-11 08:42:04', '2024-10-11 08:42:04');
INSERT INTO `t_permohonan_history` VALUES (71, 32, 6, '2024-10-11', 'VERIFIKASI KABID', '6', 'MOCH IRSYADUL ANAM', NULL, '2024-10-11 08:42:18', '2024-10-11 08:42:18');
INSERT INTO `t_permohonan_history` VALUES (72, 32, 7, '2024-10-11', 'VERIVIKASI SEKRETARIS', '6', 'MOCH IRSYADUL ANAM', NULL, '2024-10-11 08:42:24', '2024-10-11 08:42:24');
INSERT INTO `t_permohonan_history` VALUES (82, 32, 8, '2024-10-11', 'VERIVIKASI KA BPKAD', '6', 'MOCH IRSYADUL ANAM', 'bisa diambil', '2024-10-11 09:01:16', '2024-10-11 09:01:16');
INSERT INTO `t_permohonan_history` VALUES (84, 32, 8, '2024-10-11', 'VERIVIKASI KA BPKAD', '6', 'MOCH IRSYADUL ANAM', 'tester', '2024-10-11 09:04:10', '2024-10-11 09:04:10');
INSERT INTO `t_permohonan_history` VALUES (85, 33, 5, '2024-10-11', 'VALIDASI KETUA ', '6', 'MOCH IRSYADUL ANAM', 'ppp', '2024-10-11 09:05:50', '2024-10-11 09:05:50');
INSERT INTO `t_permohonan_history` VALUES (86, 33, 6, '2024-10-11', 'VERIFIKASI KABID', '6', 'MOCH IRSYADUL ANAM', NULL, '2024-10-11 09:05:57', '2024-10-11 09:05:57');
INSERT INTO `t_permohonan_history` VALUES (87, 33, 7, '2024-10-11', 'VERIVIKASI SEKRETARIS', '6', 'MOCH IRSYADUL ANAM', NULL, '2024-10-11 09:06:25', '2024-10-11 09:06:25');
INSERT INTO `t_permohonan_history` VALUES (88, 33, 8, '2024-10-11', 'VERIVIKASI KA BPKAD', '6', 'MOCH IRSYADUL ANAM', NULL, '2024-10-11 09:06:31', '2024-10-11 09:06:31');
INSERT INTO `t_permohonan_history` VALUES (89, 34, 0, '2024-10-11', 'BELUM UPLOAD FORMULIR', '6', 'MOCH IRSYADUL ANAM', 'Permohonan Dibuat', '2024-10-11 09:09:49', '2024-10-11 09:09:49');
INSERT INTO `t_permohonan_history` VALUES (90, 34, 1, '2024-10-11', 'SUBMIT', '6', 'MOCH IRSYADUL ANAM', 'Berhasil Upload Formulir', '2024-10-11 09:20:59', '2024-10-11 09:20:59');
INSERT INTO `t_permohonan_history` VALUES (91, 34, 2, '2024-10-11', 'VALIDASI DOKUMEN', '6', 'MOCH IRSYADUL ANAM', 'tester', '2024-10-11 09:21:48', '2024-10-11 09:21:48');
INSERT INTO `t_permohonan_history` VALUES (92, 34, 2, '2024-10-11', 'VALIDASI DOKUMEN', '6', 'MOCH IRSYADUL ANAM', 'tester', '2024-10-11 09:21:48', '2024-10-11 09:21:48');
INSERT INTO `t_permohonan_history` VALUES (93, 34, 3, '2024-10-11', 'PEMBUATAN FILE BAP', '6', 'MOCH IRSYADUL ANAM', 'tester', '2024-10-11 09:22:09', '2024-10-11 09:22:09');
INSERT INTO `t_permohonan_history` VALUES (94, 34, 99, '2024-10-11', 'REJECT', '6', 'MOCH IRSYADUL ANAM', NULL, '2024-10-11 09:48:31', '2024-10-11 09:48:31');
INSERT INTO `t_permohonan_history` VALUES (95, 33, 10, NULL, 'PENGEMBALIAN DOKUMEN & CETAK SURAT', '6', 'MOCH IRSYADUL ANAM', 'okey selesai', '2024-10-11 14:38:43', '2024-10-11 14:38:43');
INSERT INTO `t_permohonan_history` VALUES (96, 35, 0, '2024-10-14', 'BELUM UPLOAD FORMULIR', '6', 'MOCH IRSYADUL ANAM', 'Permohonan Dibuat', '2024-10-14 09:30:39', '2024-10-14 09:30:39');
INSERT INTO `t_permohonan_history` VALUES (97, 35, 1, '2024-10-14', 'SUBMIT', '6', 'MOCH IRSYADUL ANAM', 'Berhasil Upload Formulir', '2024-10-14 09:31:21', '2024-10-14 09:31:21');
INSERT INTO `t_permohonan_history` VALUES (98, 35, 2, '2024-10-14', 'VALIDASI DOKUMEN', '6', 'MOCH IRSYADUL ANAM', 'tester', '2024-10-14 09:31:41', '2024-10-14 09:31:41');
INSERT INTO `t_permohonan_history` VALUES (99, 35, 3, '2024-10-14', 'PEMBUATAN FILE BAP', '6', 'MOCH IRSYADUL ANAM', 'tester', '2024-10-14 09:31:55', '2024-10-14 09:31:55');
INSERT INTO `t_permohonan_history` VALUES (100, 35, 99, '2024-10-14', 'REJECT', '6', 'MOCH IRSYADUL ANAM', NULL, '2024-10-14 09:36:27', '2024-10-14 09:36:27');
INSERT INTO `t_permohonan_history` VALUES (101, 35, 3, '2024-10-14', 'PEMBUATAN FILE BAP', '6', 'MOCH IRSYADUL ANAM', 'tester', '2024-10-14 09:37:55', '2024-10-14 09:37:55');
INSERT INTO `t_permohonan_history` VALUES (102, 35, 99, '2024-10-14', 'REJECT', '6', 'MOCH IRSYADUL ANAM', NULL, '2024-10-14 09:39:33', '2024-10-14 09:39:33');
INSERT INTO `t_permohonan_history` VALUES (103, 35, 5, '2024-10-14', 'VALIDASI KETUA ', '6', 'MOCH IRSYADUL ANAM', 'p', '2024-10-14 09:40:41', '2024-10-14 09:40:41');
INSERT INTO `t_permohonan_history` VALUES (104, 35, 6, '2024-10-14', 'VERIFIKASI KABID', '6', 'MOCH IRSYADUL ANAM', NULL, '2024-10-14 09:40:46', '2024-10-14 09:40:46');
INSERT INTO `t_permohonan_history` VALUES (105, 35, 7, '2024-10-14', 'VERIVIKASI SEKRETARIS', '6', 'MOCH IRSYADUL ANAM', NULL, '2024-10-14 09:40:50', '2024-10-14 09:40:50');
INSERT INTO `t_permohonan_history` VALUES (106, 35, 8, '2024-10-14', 'VERIVIKASI KA BPKAD', '6', 'MOCH IRSYADUL ANAM', 'oke', '2024-10-14 09:41:30', '2024-10-14 09:41:30');
INSERT INTO `t_permohonan_history` VALUES (107, 35, 10, NULL, 'PENGEMBALIAN DOKUMEN & CETAK SURAT', '6', 'MOCH IRSYADUL ANAM', 'selesau', '2024-10-14 09:47:10', '2024-10-14 09:47:10');
INSERT INTO `t_permohonan_history` VALUES (108, 36, 0, '2024-10-14', 'BELUM UPLOAD FORMULIR', '6', 'MOCH IRSYADUL ANAM', 'Permohonan Dibuat', '2024-10-14 09:47:58', '2024-10-14 09:47:58');
INSERT INTO `t_permohonan_history` VALUES (109, 36, 1, '2024-10-14', 'SUBMIT', '6', 'MOCH IRSYADUL ANAM', 'Berhasil Upload Formulir', '2024-10-14 09:48:41', '2024-10-14 09:48:41');
INSERT INTO `t_permohonan_history` VALUES (110, 36, 2, '2024-10-14', 'VALIDASI DOKUMEN', '6', 'MOCH IRSYADUL ANAM', 'tester', '2024-10-14 09:48:52', '2024-10-14 09:48:52');
INSERT INTO `t_permohonan_history` VALUES (111, 36, 3, '2024-10-14', 'PEMBUATAN FILE BAP', '6', 'MOCH IRSYADUL ANAM', 'tester', '2024-10-14 09:51:00', '2024-10-14 09:51:00');
INSERT INTO `t_permohonan_history` VALUES (112, 36, 5, '2024-10-14', 'VALIDASI KETUA ', '6', 'MOCH IRSYADUL ANAM', NULL, '2024-10-14 09:51:54', '2024-10-14 09:51:54');
INSERT INTO `t_permohonan_history` VALUES (113, 36, 6, '2024-10-14', 'VERIFIKASI KABID', '6', 'MOCH IRSYADUL ANAM', NULL, '2024-10-14 09:52:00', '2024-10-14 09:52:00');
INSERT INTO `t_permohonan_history` VALUES (114, 36, 7, '2024-10-14', 'VERIVIKASI SEKRETARIS', '6', 'MOCH IRSYADUL ANAM', NULL, '2024-10-14 09:52:05', '2024-10-14 09:52:05');
INSERT INTO `t_permohonan_history` VALUES (115, 36, 8, '2024-10-14', 'VERIVIKASI KA BPKAD', '6', 'MOCH IRSYADUL ANAM', NULL, '2024-10-14 09:52:12', '2024-10-14 09:52:12');
INSERT INTO `t_permohonan_history` VALUES (116, 36, 10, NULL, 'PENGEMBALIAN DOKUMEN & CETAK SURAT', '6', 'MOCH IRSYADUL ANAM', NULL, '2024-10-14 09:52:22', '2024-10-14 09:52:22');
INSERT INTO `t_permohonan_history` VALUES (117, 37, 0, '2024-10-14', 'BELUM UPLOAD FORMULIR', '6', 'MOCH IRSYADUL ANAM', 'Permohonan Dibuat', '2024-10-14 09:53:17', '2024-10-14 09:53:17');
INSERT INTO `t_permohonan_history` VALUES (118, 37, 1, '2024-10-14', 'SUBMIT', '6', 'MOCH IRSYADUL ANAM', 'Berhasil Upload Formulir', '2024-10-14 09:54:00', '2024-10-14 09:54:00');
INSERT INTO `t_permohonan_history` VALUES (119, 37, 2, '2024-10-14', 'VALIDASI DOKUMEN', '6', 'MOCH IRSYADUL ANAM', NULL, '2024-10-14 09:54:20', '2024-10-14 09:54:20');
INSERT INTO `t_permohonan_history` VALUES (120, 37, 3, '2024-10-14', 'PEMBUATAN FILE BAP', '6', 'MOCH IRSYADUL ANAM', NULL, '2024-10-14 09:54:36', '2024-10-14 09:54:36');
INSERT INTO `t_permohonan_history` VALUES (121, 37, 5, '2024-10-14', 'VALIDASI KETUA ', '6', 'MOCH IRSYADUL ANAM', NULL, '2024-10-14 09:57:48', '2024-10-14 09:57:48');
INSERT INTO `t_permohonan_history` VALUES (122, 37, 6, '2024-10-14', 'VERIFIKASI KABID', '6', 'MOCH IRSYADUL ANAM', NULL, '2024-10-14 09:57:52', '2024-10-14 09:57:52');
INSERT INTO `t_permohonan_history` VALUES (123, 37, 7, '2024-10-14', 'VERIVIKASI SEKRETARIS', '6', 'MOCH IRSYADUL ANAM', NULL, '2024-10-14 09:57:57', '2024-10-14 09:57:57');
INSERT INTO `t_permohonan_history` VALUES (124, 37, 8, '2024-10-14', 'VERIVIKASI KA BPKAD', '6', 'MOCH IRSYADUL ANAM', NULL, '2024-10-14 09:58:00', '2024-10-14 09:58:00');
INSERT INTO `t_permohonan_history` VALUES (125, 37, 10, NULL, 'PENGEMBALIAN DOKUMEN & CETAK SURAT', '6', 'MOCH IRSYADUL ANAM', NULL, '2024-10-14 12:06:12', '2024-10-14 12:06:12');
INSERT INTO `t_permohonan_history` VALUES (126, 38, 0, '2024-10-14', 'BELUM UPLOAD FORMULIR', '27435', 'pemohon', 'Permohonan Dibuat', '2024-10-14 13:58:25', '2024-10-14 13:58:25');
INSERT INTO `t_permohonan_history` VALUES (127, 38, 1, '2024-10-14', 'SUBMIT', '27435', 'pemohon', 'Berhasil Upload Formulir', '2024-10-14 13:59:54', '2024-10-14 13:59:54');
INSERT INTO `t_permohonan_history` VALUES (128, 38, 2, '2024-10-14', 'VALIDASI DOKUMEN', '6', 'MOCH IRSYADUL ANAM', NULL, '2024-10-14 14:01:21', '2024-10-14 14:01:21');
INSERT INTO `t_permohonan_history` VALUES (129, 38, 3, '2024-10-14', 'PEMBUATAN FILE BAP', '6', 'MOCH IRSYADUL ANAM', NULL, '2024-10-14 14:01:22', '2024-10-14 14:01:22');
INSERT INTO `t_permohonan_history` VALUES (130, 38, 3, '2024-10-14', 'PEMBUATAN FILE BAP', '6', 'MOCH IRSYADUL ANAM', 'oke sudah suervery', '2024-10-14 14:02:08', '2024-10-14 14:02:08');
INSERT INTO `t_permohonan_history` VALUES (131, 38, 5, '2024-10-14', 'VALIDASI KETUA ', '6', 'MOCH IRSYADUL ANAM', NULL, '2024-10-14 14:10:01', '2024-10-14 14:10:01');
INSERT INTO `t_permohonan_history` VALUES (132, 38, 6, '2024-10-14', 'VERIFIKASI KABID', '6', 'MOCH IRSYADUL ANAM', NULL, '2024-10-14 14:10:12', '2024-10-14 14:10:12');
INSERT INTO `t_permohonan_history` VALUES (133, 38, 7, '2024-10-14', 'VERIVIKASI SEKRETARIS', '6', 'MOCH IRSYADUL ANAM', NULL, '2024-10-14 14:10:20', '2024-10-14 14:10:20');
INSERT INTO `t_permohonan_history` VALUES (134, 38, 8, '2024-10-14', 'VERIVIKASI KA BPKAD', '6', 'MOCH IRSYADUL ANAM', NULL, '2024-10-14 14:12:52', '2024-10-14 14:12:52');
INSERT INTO `t_permohonan_history` VALUES (135, 39, 0, '2024-10-14', 'BELUM UPLOAD FORMULIR', '6', 'MOCH IRSYADUL ANAM', 'Permohonan Dibuat', '2024-10-14 14:27:13', '2024-10-14 14:27:13');
INSERT INTO `t_permohonan_history` VALUES (136, 39, 1, '2024-10-14', 'SUBMIT', '6', 'MOCH IRSYADUL ANAM', 'Berhasil Upload Semua Berkas', '2024-10-14 14:52:39', '2024-10-14 14:52:39');
INSERT INTO `t_permohonan_history` VALUES (137, 40, 0, '2024-10-14', 'BELUM UPLOAD FORMULIR', '6', 'MOCH IRSYADUL ANAM', 'Permohonan Dibuat', '2024-10-14 14:53:27', '2024-10-14 14:53:27');
INSERT INTO `t_permohonan_history` VALUES (138, 39, 2, '2024-10-14', 'VALIDASI DOKUMEN', '6', 'MOCH IRSYADUL ANAM', 'tester', '2024-10-14 15:08:00', '2024-10-14 15:08:00');
INSERT INTO `t_permohonan_history` VALUES (139, 39, 3, '2024-10-14', 'PEMBUATAN FILE BAP', '6', 'MOCH IRSYADUL ANAM', 'tester', '2024-10-14 15:12:36', '2024-10-14 15:12:36');
INSERT INTO `t_permohonan_history` VALUES (140, 39, 5, '2024-10-14', 'VALIDASI KETUA ', '6', 'MOCH IRSYADUL ANAM', 'setuju', '2024-10-14 15:19:28', '2024-10-14 15:19:28');
INSERT INTO `t_permohonan_history` VALUES (141, 39, 99, '2024-10-14', 'REJECT', '6', 'MOCH IRSYADUL ANAM', 'tolak', '2024-10-14 15:19:41', '2024-10-14 15:19:41');
INSERT INTO `t_permohonan_history` VALUES (142, 39, 5, '2024-10-14', 'VALIDASI KETUA ', '6', 'MOCH IRSYADUL ANAM', 'tester', '2024-10-14 15:19:51', '2024-10-14 15:19:51');
INSERT INTO `t_permohonan_history` VALUES (143, 39, 6, '2024-10-14', 'VERIFIKASI KABID', '6', 'MOCH IRSYADUL ANAM', 'okey', '2024-10-14 15:20:34', '2024-10-14 15:20:34');
INSERT INTO `t_permohonan_history` VALUES (144, 39, 7, '2024-10-14', 'VERIVIKASI SEKRETARIS', '6', 'MOCH IRSYADUL ANAM', 'okey diverifikasi', '2024-10-14 15:22:05', '2024-10-14 15:22:05');
INSERT INTO `t_permohonan_history` VALUES (145, 39, 8, '2024-10-14', 'VERIVIKASI KA BPKAD', '6', 'MOCH IRSYADUL ANAM', 'tester', '2024-10-14 15:22:53', '2024-10-14 15:22:53');
INSERT INTO `t_permohonan_history` VALUES (146, 39, 10, NULL, 'PENGEMBALIAN DOKUMEN & CETAK SURAT', '6', 'MOCH IRSYADUL ANAM', NULL, '2024-10-14 15:24:35', '2024-10-14 15:24:35');
INSERT INTO `t_permohonan_history` VALUES (147, 39, 10, NULL, 'PENGEMBALIAN DOKUMEN & CETAK SURAT', '6', 'MOCH IRSYADUL ANAM', NULL, '2024-10-14 15:24:35', '2024-10-14 15:24:35');
INSERT INTO `t_permohonan_history` VALUES (148, 39, 10, NULL, 'PENGEMBALIAN DOKUMEN & CETAK SURAT', '6', 'MOCH IRSYADUL ANAM', NULL, '2024-10-14 15:24:35', '2024-10-14 15:24:35');
INSERT INTO `t_permohonan_history` VALUES (149, 39, 10, NULL, 'PENGEMBALIAN DOKUMEN & CETAK SURAT', '6', 'MOCH IRSYADUL ANAM', NULL, '2024-10-14 15:24:35', '2024-10-14 15:24:35');
INSERT INTO `t_permohonan_history` VALUES (150, 40, 1, '2024-10-14', 'SUBMIT', '6', 'MOCH IRSYADUL ANAM', 'Berhasil Upload Semua Berkas', '2024-10-14 15:28:29', '2024-10-14 15:28:29');
INSERT INTO `t_permohonan_history` VALUES (151, 40, 2, '2024-10-14', 'VALIDASI DOKUMEN', '6', 'MOCH IRSYADUL ANAM', 'oke, data valiud', '2024-10-14 15:29:12', '2024-10-14 15:29:12');
INSERT INTO `t_permohonan_history` VALUES (152, 41, 0, '2024-10-15', 'BELUM UPLOAD FORMULIR', '6', 'MOCH IRSYADUL ANAM', 'Permohonan Dibuat', '2024-10-15 09:57:58', '2024-10-15 09:57:58');
INSERT INTO `t_permohonan_history` VALUES (153, 41, 1, '2024-10-15', 'SUBMIT', '6', 'MOCH IRSYADUL ANAM', 'Berhasil Upload Semua Berkas', '2024-10-15 10:03:30', '2024-10-15 10:03:30');
INSERT INTO `t_permohonan_history` VALUES (154, 41, 2, '2024-10-15', 'VALIDASI DOKUMEN', '6', 'MOCH IRSYADUL ANAM', 'tester', '2024-10-15 10:04:26', '2024-10-15 10:04:26');
INSERT INTO `t_permohonan_history` VALUES (155, 41, 3, '2024-10-15', 'PEMBUATAN FILE BAP', '6', 'MOCH IRSYADUL ANAM', 'tester', '2024-10-15 10:05:11', '2024-10-15 10:05:11');
INSERT INTO `t_permohonan_history` VALUES (156, 41, 99, '2024-10-15', 'REJECT', '6', 'MOCH IRSYADUL ANAM', 'tester', '2024-10-15 10:13:53', '2024-10-15 10:13:53');
INSERT INTO `t_permohonan_history` VALUES (157, 41, 5, '2024-10-15', 'VALIDASI KETUA ', '6', 'MOCH IRSYADUL ANAM', 'oke', '2024-10-15 10:14:14', '2024-10-15 10:14:14');
INSERT INTO `t_permohonan_history` VALUES (158, 41, 6, '2024-10-15', 'VERIFIKASI KABID', '6', 'MOCH IRSYADUL ANAM', 'tester', '2024-10-15 10:14:22', '2024-10-15 10:14:22');
INSERT INTO `t_permohonan_history` VALUES (159, 41, 7, '2024-10-15', 'VERIVIKASI SEKRETARIS', '6', 'MOCH IRSYADUL ANAM', 'okey', '2024-10-15 10:14:29', '2024-10-15 10:14:29');
INSERT INTO `t_permohonan_history` VALUES (160, 41, 8, '2024-10-15', 'VERIVIKASI KA BPKAD', '6', 'MOCH IRSYADUL ANAM', 'okey', '2024-10-15 10:14:54', '2024-10-15 10:14:54');
INSERT INTO `t_permohonan_history` VALUES (161, 41, 10, NULL, 'PENGEMBALIAN DOKUMEN & CETAK SURAT', '6', 'MOCH IRSYADUL ANAM', 'tester', '2024-10-15 10:18:39', '2024-10-15 10:18:39');
INSERT INTO `t_permohonan_history` VALUES (162, 42, 0, '2024-10-15', 'BELUM UPLOAD FORMULIR', '6', 'MOCH IRSYADUL ANAM', 'Permohonan Dibuat', '2024-10-15 10:22:49', '2024-10-15 10:22:49');
INSERT INTO `t_permohonan_history` VALUES (163, 42, 1, '2024-10-15', 'SUBMIT', '6', 'MOCH IRSYADUL ANAM', 'Berhasil Upload Semua Berkas', '2024-10-15 10:24:58', '2024-10-15 10:24:58');
INSERT INTO `t_permohonan_history` VALUES (164, 42, 2, '2024-10-15', 'VALIDASI DOKUMEN', '6', 'MOCH IRSYADUL ANAM', 'oke lah', '2024-10-15 10:26:06', '2024-10-15 10:26:06');
INSERT INTO `t_permohonan_history` VALUES (165, 42, 3, '2024-10-15', 'PEMBUATAN FILE BAP', '6', 'MOCH IRSYADUL ANAM', 'okey', '2024-10-15 10:26:56', '2024-10-15 10:26:56');
INSERT INTO `t_permohonan_history` VALUES (166, 42, 5, '2024-10-15', 'VALIDASI KETUA ', '6', 'MOCH IRSYADUL ANAM', 'oke', '2024-10-15 10:28:46', '2024-10-15 10:28:46');
INSERT INTO `t_permohonan_history` VALUES (167, 42, 6, '2024-10-15', 'VERIFIKASI KABID', '6', 'MOCH IRSYADUL ANAM', 'oke', '2024-10-15 10:28:57', '2024-10-15 10:28:57');
INSERT INTO `t_permohonan_history` VALUES (168, 42, 7, '2024-10-15', 'VERIVIKASI SEKRETARIS', '6', 'MOCH IRSYADUL ANAM', 'oke', '2024-10-15 10:29:05', '2024-10-15 10:29:05');
INSERT INTO `t_permohonan_history` VALUES (169, 42, 8, '2024-10-15', 'VERIVIKASI KA BPKAD', '6', 'MOCH IRSYADUL ANAM', 'oke', '2024-10-15 10:29:13', '2024-10-15 10:29:13');
INSERT INTO `t_permohonan_history` VALUES (170, 42, 10, NULL, 'PENGEMBALIAN DOKUMEN & CETAK SURAT', '6', 'MOCH IRSYADUL ANAM', 'ke', '2024-10-15 10:29:20', '2024-10-15 10:29:20');

-- ----------------------------
-- Table structure for t_permohonan_surat
-- ----------------------------
DROP TABLE IF EXISTS `t_permohonan_surat`;
CREATE TABLE `t_permohonan_surat`  (
  `id` int NOT NULL AUTO_INCREMENT,
  `id_permohonan` int NOT NULL,
  `isi` text CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `created_by` int NULL DEFAULT NULL,
  `updated_by` int NULL DEFAULT NULL,
  `list_nama` text CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NULL,
  `nomer_surat` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NULL DEFAULT NULL,
  `type_surat` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 20 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_0900_ai_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of t_permohonan_surat
-- ----------------------------
INSERT INTO `t_permohonan_surat` VALUES (1, 19, 'file_bap/uhBoaI8ibCfTPMuqZpTDQB5Tv8RXJFomvlNhgdjX.pdf', '2024-10-08 10:43:09', '2024-10-08 10:43:09', NULL, NULL, NULL, '0001', NULL);
INSERT INTO `t_permohonan_surat` VALUES (2, 21, 'file_surat/JEDBNvxmVMdyPUa1Hkx5Mw0MvIR5pZxOtanPdjm1.png', '2024-10-08 14:52:19', '2024-10-08 14:52:19', NULL, NULL, NULL, '0003', NULL);
INSERT INTO `t_permohonan_surat` VALUES (3, 23, 'file_surat/46ZpRuAAdJe32dFYMQL75ItwVKsEjObWEwfKhrix.png', '2024-10-09 08:42:18', '2024-10-09 08:42:18', NULL, NULL, NULL, '0002', NULL);
INSERT INTO `t_permohonan_surat` VALUES (4, 22, '<p style=\"margin-left:0in; margin-right:0in; text-align:justify\"><span style=\"font-size:12pt\"><span style=\"font-family:&quot;Times New Roman&quot;,serif\"><span style=\"font-family:&quot;Arial&quot;,sans-serif\">Sehubungan dengan surat permohonan balik nama Izin Pemakaian Tanah Nomor : 188.45/3326P/436.7.11/2018 tanggal 01 Agustus 2018 yang terletak di <strong>Jalan Jagir Sidomukti II / 54</strong> Surabaya dari <strong>Sdr. Yosefa Maria Rustiowati Dewi </strong>tanggal 20 September 2024, maka Badan Pengelolaan Keuangan dan Aset Daerah Kota Surabaya akan menerbitkan Izin Pemakaian Tanah kepada <strong>Sdr. Yosefa Maria Rustiowati Dewi </strong>&nbsp;dengan letak persil tanah <strong>Jalan Jagir Sidomukti II / 54</strong> Surabaya mendasarkan pada : </span></span></span></p>\r\n\r\n<ol style=\"list-style-type:lower-alpha\">\r\n	<li style=\"text-align:justify\"><span style=\"font-size:12pt\"><span style=\"font-family:&quot;Times New Roman&quot;,serif\"><span style=\"font-family:&quot;Arial&quot;,sans-serif\">kwitansi tanggal 01 September 2021 atas nama Indra Richka Nica Pasa;</span></span></span></li>\r\n	<li style=\"text-align:justify\"><span style=\"font-size:12pt\"><span style=\"font-family:&quot;Times New Roman&quot;,serif\"><span style=\"font-family:&quot;Arial&quot;,sans-serif\">kwitansi tanggal 01 September 2021 atas nama Erwantoko Haktoara;</span></span></span></li>\r\n	<li style=\"text-align:justify\"><span style=\"font-size:12pt\"><span style=\"font-family:&quot;Times New Roman&quot;,serif\"><span style=\"font-family:&quot;Arial&quot;,sans-serif\">kwitansi tanggal 01 September 2021 atas nama Listiowati Dewi;</span></span></span></li>\r\n	<li style=\"text-align:justify\"><span style=\"font-size:12pt\"><span style=\"font-family:&quot;Times New Roman&quot;,serif\"><span style=\"font-family:&quot;Arial&quot;,sans-serif\">kwitansi tanggal 01 September 2021 atas nama Hartonoko Yuni Mingkliw;</span></span></span></li>\r\n	<li style=\"text-align:justify\"><span style=\"font-size:12pt\"><span style=\"font-family:&quot;Times New Roman&quot;,serif\"><span style=\"font-family:&quot;Arial&quot;,sans-serif\">Akta Pernyataan Persaksian Nomor 54 tanggal 18 September 2024 yang dibuat dihadapan Notaris R. Lucky Andiyanto, SH. </span></span></span></li>\r\n</ol>\r\n\r\n<p style=\"margin-left:0in; margin-right:0in; text-align:justify\"><span style=\"font-size:12pt\"><span style=\"font-family:&quot;Times New Roman&quot;,serif\"><span style=\"font-family:&quot;Arial&quot;,sans-serif\">Terhadap permohonan penerbitan surat Izin Pemakaian Tanah di persil dimaksud atas nama </span><strong><span style=\"font-family:&quot;Arial&quot;,sans-serif\">Sdr. Yosefa Maria Rustiowati Dewi </span></strong>&nbsp;<span style=\"font-family:&quot;Arial&quot;,sans-serif\">maka a</span><span style=\"font-family:&quot;Arial&quot;,sans-serif\">pabila </span><span style=\"font-family:&quot;Arial&quot;,sans-serif\">terdapat </span><span style=\"font-family:&quot;Arial&quot;,sans-serif\">pihak</span><span style=\"font-family:&quot;Arial&quot;,sans-serif\">-pihak</span><span style=\"font-family:&quot;Arial&quot;,sans-serif\"> yang keberatan </span><span style=\"font-family:&quot;Arial&quot;,sans-serif\">terhadap pengajuan permohonan, agar mengajukan keberatan </span><span style=\"font-family:&quot;Arial&quot;,sans-serif\">ke Badan Pengelolaan Keuangan dan Aset Daerah Kota Surabaya paling lambat 30 (tiga puluh) hari terhitung </span><span style=\"font-family:&quot;Arial&quot;,sans-serif\">sejak</span><span style=\"font-family:&quot;Arial&quot;,sans-serif\"> tanggal pengumuman ini</span><span style=\"font-family:&quot;Arial&quot;,sans-serif\"> diterbitkan.</span></span></span></p>\r\n\r\n<p><span style=\"font-size:12.0pt\"><span style=\"font-family:&quot;Arial&quot;,sans-serif\">Demikian atas perhatiannya disampaikan terima kasih.</span></span></p>', '2024-10-09 13:36:02', '2024-10-09 13:36:02', 6, NULL, NULL, NULL, NULL);
INSERT INTO `t_permohonan_surat` VALUES (5, 24, '<p>Cobalah Mengerti Semua Ini menjadi Satuuuuuuu siji loro telu</p>', '2024-10-09 15:14:16', '2024-10-09 15:22:08', 6, 6, NULL, NULL, NULL);
INSERT INTO `t_permohonan_surat` VALUES (6, 26, '<p>Sehubungan dengan surat permohonan balik nama Izin Pemakaian Tanah Nomor : 188.45/0999B/436.4.22/2005 tanggal 23 November 2005 yang terletak di &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; Jl. Sisingamangaraja XII No. 11 Surabaya dari Sdr. H. Mansur Aras tanggal 27 November 2023, maka Badan Pengelolaan Keuangan dan Aset Daerah Kota Surabaya akan menerbitkan Izin Pemakaian Tanah kepada Sdr. H. Mansur Aras dengan letak persil tanah Jl. Sisingamangaraja XII No. 11 Surabaya mendasarkan pada dokumen sebagai berikut :<br />\r\n&bull;&nbsp;&nbsp; &nbsp;Ikatan Jual Beli tanggal 15 Juni 2006 yang dibuat dibawah tangan ditandatangani antara H. Rugaya, H. Rohaniyah, H. Much. Nasir, Arif, Ir. M. Amir Hasan,H. Faridah, Ny. Nadira, H. Nurhayati, Moch. Hamzah, Sjabir Hasan, Ir. Hartati, Harjani dan Harijanti Hasan dengan H. Aras Hasan.<br />\r\n&bull;&nbsp;&nbsp; &nbsp;Surat Keterangan Ahli Waris H. Aras Hasan tanggal 01 Desember 2010 yang ditandatangani oleh para ahli waris H. Mardijah, H. Mansur Aras, Rachmawati Aras, Rachman Aras, Zubaidah Aras, Muhammad Aswad Aras, Muhammad Fadjar Aras dan (Alm. Marlina, SE).<br />\r\n&bull;&nbsp;&nbsp; &nbsp;Surat Keterangan Ahli Waris Marlina, SE tanggal 29 Nopember 2012 &nbsp;yang ditandatangani oleh para ahli waris Drs. Salsabilah, Nur Assyifa Nabila Salsabilah dan Nadia Zahra Salsabilah.<br />\r\n&bull;&nbsp;&nbsp; &nbsp;Surat Keterangan Ahli Waris H. Mardijah/H. Mardiyah tanggal 24 Agustus 2023 yang ditandatangani oleh para ahli waris Farisah Rahmi, Faris Setiawan dan Rahma Iskandar.<br />\r\n&bull;&nbsp;&nbsp; &nbsp;Akta Pernyataan Persaksian Nomor 23 tanggal 16 Februari 2024 yang dibuat oleh Notaris Audia Erlangga, S.H., M.Kn.<br />\r\nTerhadap permohonan penerbitan surat Izin Pemakaian Tanah di persil dimaksud atas nama H. Mansur Aras, Rachmawati Aras, Rachman Aras, Zubaidah Aras, Muhammad Aswad Aras, Muhammad Fadjar Aras, Farisah Rahmi, Faris Setiawan, Rahma Iskandar, Drs. Salsabilah, Nur Assyifa Nabila Salsabilah dan Nadia Zahra Salsabilah maka apabila terdapat pihak-pihak yang keberatan terhadap pengajuan permohonan, agar mengajukan keberatan ke Badan Pengelolaan Keuangan dan Aset Daerah Kota Surabaya paling lambat 30 (tiga puluh) hari terhitung sejak tanggal pengumuman ini diterbitkan.<br />\r\nDemikian atas perhatiannya disampaikan terima kasih.<br />\r\n&nbsp;</p>', '2024-10-10 08:16:12', '2024-10-10 08:16:12', 6, NULL, NULL, NULL, NULL);
INSERT INTO `t_permohonan_surat` VALUES (7, 27, '<p>Sehubungan dengan surat permohonan balik nama Izin Pemakaian Tanah Nomor : 188.45/0999B/436.4.22/2005 tanggal 23 November 2005 yang terletak di &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; Jl. Sisingamangaraja XII No. 11 Surabaya dari Sdr. H. Mansur Aras tanggal 27 November 2023, maka Badan Pengelolaan Keuangan dan Aset Daerah Kota Surabaya akan menerbitkan Izin Pemakaian Tanah kepada Sdr. H. Mansur Aras dengan letak persil tanah Jl. Sisingamangaraja XII No. 11 Surabaya mendasarkan pada dokumen sebagai berikut :<br />\r\n&bull;&nbsp;&nbsp; &nbsp;Ikatan Jual Beli tanggal 15 Juni 2006 yang dibuat dibawah tangan ditandatangani antara H. Rugaya, H. Rohaniyah, H. Much. Nasir, Arif, Ir. M. Amir Hasan,H. Faridah, Ny. Nadira, H. Nurhayati, Moch. Hamzah, Sjabir Hasan, Ir. Hartati, Harjani dan Harijanti Hasan dengan H. Aras Hasan.<br />\r\n&bull;&nbsp;&nbsp; &nbsp;Surat Keterangan Ahli Waris H. Aras Hasan tanggal 01 Desember 2010 yang ditandatangani oleh para ahli waris H. Mardijah, H. Mansur Aras, Rachmawati Aras, Rachman Aras, Zubaidah Aras, Muhammad Aswad Aras, Muhammad Fadjar Aras dan (Alm. Marlina, SE).<br />\r\n&bull;&nbsp;&nbsp; &nbsp;Surat Keterangan Ahli Waris Marlina, SE tanggal 29 Nopember 2012 &nbsp;yang ditandatangani oleh para ahli waris Drs. Salsabilah, Nur Assyifa Nabila Salsabilah dan Nadia Zahra Salsabilah.<br />\r\n&bull;&nbsp;&nbsp; &nbsp;Surat Keterangan Ahli Waris H. Mardijah/H. Mardiyah tanggal 24 Agustus 2023 yang ditandatangani oleh para ahli waris Farisah Rahmi, Faris Setiawan dan Rahma Iskandar.<br />\r\n&bull;&nbsp;&nbsp; &nbsp;Akta Pernyataan Persaksian Nomor 23 tanggal 16 Februari 2024 yang dibuat oleh Notaris Audia Erlangga, S.H., M.Kn.<br />\r\nTerhadap permohonan penerbitan surat Izin Pemakaian Tanah di persil dimaksud atas nama H. Mansur Aras, Rachmawati Aras, Rachman Aras, Zubaidah Aras, Muhammad Aswad Aras, Muhammad Fadjar Aras, Farisah Rahmi, Faris Setiawan, Rahma Iskandar, Drs. Salsabilah, Nur Assyifa Nabila Salsabilah dan Nadia Zahra Salsabilah maka apabila terdapat pihak-pihak yang keberatan terhadap pengajuan permohonan, agar mengajukan keberatan ke Badan Pengelolaan Keuangan dan Aset Daerah Kota Surabaya paling lambat 30 (tiga puluh) hari terhitung sejak tanggal pengumuman ini diterbitkan.<br />\r\nDemikian atas perhatiannya disampaikan terima kasih.<br />\r\n&nbsp;</p>', '2024-10-10 08:44:11', '2024-10-10 08:47:56', 6, 6, NULL, NULL, NULL);
INSERT INTO `t_permohonan_surat` VALUES (8, 32, '<p>a.&nbsp;&nbsp; &nbsp;kwitansi tanggal 01 September 2021 atas nama Indra Richka Nica Pasa;<br />\r\nb.&nbsp;&nbsp; &nbsp;kwitansi tanggal 01 September 2021 atas nama Erwantoko Haktoara;<br />\r\nc.&nbsp;&nbsp; &nbsp;kwitansi tanggal 01 September 2021 atas nama Listiowati Dewi;<br />\r\nd.&nbsp;&nbsp; &nbsp;kwitansi tanggal 01 September 2021 atas nama Hartonoko Yuni Mingkliw;<br />\r\ne.&nbsp;&nbsp; &nbsp;Akta Pernyataan Persaksian Nomor 54 tanggal 18 September 2024 yang dibuat dihadapan Notaris R. Lucky Andiyanto, SH.</p>', '2024-10-10 13:22:17', '2024-10-11 09:04:11', 6, NULL, NULL, '0004', NULL);
INSERT INTO `t_permohonan_surat` VALUES (11, 33, '<p>&bull;&nbsp;&nbsp; &nbsp;Ikatan Jual Beli tanggal 15 Juni 2006 yang dibuat dibawah tangan ditandatangani antara H. Rugaya, H. Rohaniyah, H. Much. Nasir, Arif, Ir. M. Amir Hasan,H. Faridah, Ny. Nadira, H. Nurhayati, Moch. Hamzah, Sjabir Hasan, Ir. Hartati, Harjani dan Harijanti Hasan dengan H. Aras Hasan.<br />\r\n&bull;&nbsp;&nbsp; &nbsp;Surat Keterangan Ahli Waris H. Aras Hasan tanggal 01 Desember 2010 yang ditandatangani oleh para ahli waris H. Mardijah, H. Mansur Aras, Rachmawati Aras, Rachman Aras, Zubaidah Aras, Muhammad Aswad Aras, Muhammad Fadjar Aras dan (Alm. Marlina, SE).<br />\r\n&bull;&nbsp;&nbsp; &nbsp;Surat Keterangan Ahli Waris Marlina, SE tanggal 29 Nopember 2012 &nbsp;yang ditandatangani oleh para ahli waris Drs. Salsabilah, Nur Assyifa Nabila Salsabilah dan Nadia Zahra Salsabilah.<br />\r\n&bull;&nbsp;&nbsp; &nbsp;Surat Keterangan Ahli Waris H. Mardijah/H. Mardiyah tanggal 24 Agustus 2023 yang ditandatangani oleh para ahli waris Farisah Rahmi, Faris Setiawan dan Rahma Iskandar.<br />\r\n&bull;&nbsp;&nbsp; &nbsp;Akta Pernyataan Persaksian Nomor 23 tanggal 16 Februari 2024 yang dibuat oleh Notaris Audia Erlangga, S.H., M.Kn.<br />\r\n&nbsp;</p>', '2024-10-10 15:45:04', '2024-10-11 09:06:31', 6, NULL, '[\"H. Mansur Aras\",\"Rachmawati Aras\",\"Rachman Aras\",\"Zubaidah Aras\",\"Muhammad Aswad Aras\"]', '0005', NULL);
INSERT INTO `t_permohonan_surat` VALUES (12, 34, NULL, '2024-10-11 09:29:24', '2024-10-11 10:43:52', 6, 6, 'null', NULL, NULL);
INSERT INTO `t_permohonan_surat` VALUES (13, 35, '<p>a.&nbsp;&nbsp; &nbsp;kwitansi tanggal 01 September 2021 atas nama Indra Richka Nica Pasa;<br />\r\nb.&nbsp;&nbsp; &nbsp;kwitansi tanggal 01 September 2021 atas nama Erwantoko Haktoara;<br />\r\nc.&nbsp;&nbsp; &nbsp;kwitansi tanggal 01 September 2021 atas nama Listiowati Dewi;<br />\r\nd.&nbsp;&nbsp; &nbsp;kwitansi tanggal 01 September 2021 atas nama Hartonoko Yuni Mingkliw;<br />\r\ne.&nbsp;&nbsp; &nbsp;Akta Pernyataan Persaksian Nomor 54 tanggal 18 September 2024 yang dibuat dihadapan Notaris R. Lucky Andiyanto, SH.</p>', '2024-10-14 09:33:36', '2024-10-14 09:41:30', 6, 6, 'null', '0006', NULL);
INSERT INTO `t_permohonan_surat` VALUES (14, 36, NULL, '2024-10-14 09:51:37', '2024-10-14 09:52:12', 6, NULL, 'null', '0007', NULL);
INSERT INTO `t_permohonan_surat` VALUES (15, 37, '<p>Ikatan Jual Beli tanggal 15 Juni 2006 yang dibuat dibawah tangan ditandatangani antara H. Rugaya, H. Rohaniyah, H. Much. Nasir, Arif, Ir. M. Amir Hasan,H. Faridah, Ny. Nadira, H. Nurhayati, Moch. Hamzah, Sjabir Hasan, Ir. Hartati, Harjani dan Harijanti Hasan dengan H. Aras Hasan.<br />\r\n&bull;&nbsp;&nbsp; &nbsp;Surat Keterangan Ahli Waris H. Aras Hasan tanggal 01 Desember 2010 yang ditandatangani oleh para ahli waris H. Mardijah, H. Mansur Aras, Rachmawati Aras, Rachman Aras, Zubaidah Aras, Muhammad Aswad Aras, Muhammad Fadjar Aras dan (Alm. Marlina, SE).<br />\r\n&bull;&nbsp;&nbsp; &nbsp;Surat Keterangan Ahli Waris Marlina, SE tanggal 29 Nopember 2012 &nbsp;yang ditandatangani oleh para ahli waris Drs. Salsabilah, Nur Assyifa Nabila Salsabilah dan Nadia Zahra Salsabilah.<br />\r\n&bull;&nbsp;&nbsp; &nbsp;Surat Keterangan Ahli Waris H. Mardijah/H. Mardiyah tanggal 24 Agustus 2023 yang ditandatangani oleh para ahli waris Farisah Rahmi, Faris Setiawan dan Rahma Iskandar.<br />\r\n&bull;&nbsp;&nbsp; &nbsp;Akta Pernyataan Persaksian Nomor 23 tanggal 16 Februari 2024 yang dibuat oleh Notaris Audia Erlangga, S.H., M.Kn.</p>', '2024-10-14 09:56:45', '2024-10-14 09:58:00', 6, NULL, '[\"H. Mansur Aras\",\"Rachmawati Aras\",\"Rachman Aras\"]', '0008', NULL);
INSERT INTO `t_permohonan_surat` VALUES (16, 38, '<p>a.&nbsp;&nbsp; &nbsp;kwitansi tanggal 01 September 2021 atas nama Indra Richka Nica Pasa;<br />\r\nb.&nbsp;&nbsp; &nbsp;kwitansi tanggal 01 September 2021 atas nama Erwantoko Haktoara;<br />\r\nc.&nbsp;&nbsp; &nbsp;kwitansi tanggal 01 September 2021 atas nama Listiowati Dewi;<br />\r\nd.&nbsp;&nbsp; &nbsp;kwitansi tanggal 01 September 2021 atas nama Hartonoko Yuni Mingkliw;<br />\r\ne.&nbsp;&nbsp; &nbsp;Akta Pernyataan Persaksian Nomor 54 tanggal 18 September 2024 yang dibuat dihadapan Notaris R. Lucky Andiyanto, SH.</p>', '2024-10-14 14:06:41', '2024-10-14 14:12:52', 6, NULL, 'null', '0009', NULL);
INSERT INTO `t_permohonan_surat` VALUES (17, 39, '<p>a.&nbsp;&nbsp; &nbsp;kwitansi tanggal 01 September 2021 atas nama Indra Richka Nica Pasa;<br />\r\nb.&nbsp;&nbsp; &nbsp;kwitansi tanggal 01 September 2021 atas nama Erwantoko Haktoara;<br />\r\nc.&nbsp;&nbsp; &nbsp;kwitansi tanggal 01 September 2021 atas nama Listiowati Dewi;<br />\r\nd.&nbsp;&nbsp; &nbsp;kwitansi tanggal 01 September 2021 atas nama Hartonoko Yuni Mingkliw;<br />\r\ne.&nbsp;&nbsp; &nbsp;Akta Pernyataan Persaksian Nomor 54 tanggal 18 September 2024 yang dibuat dihadapan Notaris R. Lucky Andiyanto, SH.</p>', '2024-10-14 15:18:10', '2024-10-14 15:22:53', 6, NULL, 'null', '0010', NULL);
INSERT INTO `t_permohonan_surat` VALUES (18, 41, '<p>a.&nbsp;&nbsp; &nbsp;kwitansi tanggal 01 September 2021 atas nama Indra Richka Nica Pasa;<br />\r\nb.&nbsp;&nbsp; &nbsp;kwitansi tanggal 01 September 2021 atas nama Erwantoko Haktoara;<br />\r\nc.&nbsp;&nbsp; &nbsp;kwitansi tanggal 01 September 2021 atas nama Listiowati Dewi;<br />\r\nd.&nbsp;&nbsp; &nbsp;kwitansi tanggal 01 September 2021 atas nama Hartonoko Yuni Mingkliw;<br />\r\ne.&nbsp;&nbsp; &nbsp;Akta Pernyataan Persaksian Nomor 54 tanggal 18 September 2024 yang dibuat dihadapan Notaris R. Lucky Andiyanto, SH.</p>', '2024-10-15 10:11:15', '2024-10-15 10:14:54', 6, 6, 'null', '0011', NULL);
INSERT INTO `t_permohonan_surat` VALUES (19, 42, '<p>&bull;&nbsp;&nbsp; &nbsp;Ikatan Jual Beli tanggal 15 Juni 2006 yang dibuat dibawah tangan ditandatangani antara H. Rugaya, H. Rohaniyah, H. Much. Nasir, Arif, Ir. M. Amir Hasan,H. Faridah, Ny. Nadira, H. Nurhayati, Moch. Hamzah, Sjabir Hasan, Ir. Hartati, Harjani dan Harijanti Hasan dengan H. Aras Hasan.<br />\r\n&bull;&nbsp;&nbsp; &nbsp;Surat Keterangan Ahli Waris H. Aras Hasan tanggal 01 Desember 2010 yang ditandatangani oleh para ahli waris H. Mardijah, H. Mansur Aras, Rachmawati Aras, Rachman Aras, Zubaidah Aras, Muhammad Aswad Aras, Muhammad Fadjar Aras dan (Alm. Marlina, SE).<br />\r\n&bull;&nbsp;&nbsp; &nbsp;Surat Keterangan Ahli Waris Marlina, SE tanggal 29 Nopember 2012 &nbsp;yang ditandatangani oleh para ahli waris Drs. Salsabilah, Nur Assyifa Nabila Salsabilah dan Nadia Zahra Salsabilah.<br />\r\n&bull;&nbsp;&nbsp; &nbsp;Surat Keterangan Ahli Waris H. Mardijah/H. Mardiyah tanggal 24 Agustus 2023 yang ditandatangani oleh para ahli waris Farisah Rahmi, Faris Setiawan dan Rahma Iskandar.<br />\r\n&bull;&nbsp;&nbsp; &nbsp;Akta Pernyataan Persaksian Nomor 23 tanggal 16 Februari 2024 yang dibuat oleh Notaris Audia Erlangga, S.H., M.Kn.<br />\r\n&nbsp;</p>', '2024-10-15 10:28:00', '2024-10-15 10:29:13', 6, NULL, '[\"H. Mansur Aras\",\"Rachmawati Aras\",\"Rachman Aras\",\"Zubaidah Aras\",\"Muhammad Aswad Aras\"]', '0012', NULL);

-- ----------------------------
-- Table structure for users
-- ----------------------------
DROP TABLE IF EXISTS `users`;
CREATE TABLE `users`  (
  `id_user` int NOT NULL AUTO_INCREMENT,
  `nm_user` varchar(100) CHARACTER SET ascii COLLATE ascii_general_ci NULL DEFAULT NULL,
  `username` varchar(40) CHARACTER SET ascii COLLATE ascii_general_ci NOT NULL,
  `email` varchar(40) CHARACTER SET ascii COLLATE ascii_general_ci NULL DEFAULT NULL,
  `password` varchar(200) CHARACTER SET ascii COLLATE ascii_general_ci NULL DEFAULT NULL,
  `created_at` datetime NULL DEFAULT NULL,
  `updated_at` datetime NULL DEFAULT NULL,
  `telp` varchar(16) CHARACTER SET ascii COLLATE ascii_general_ci NULL DEFAULT NULL,
  `last_id` varchar(10) CHARACTER SET ascii COLLATE ascii_general_ci NULL DEFAULT NULL,
  `token` varchar(256) CHARACTER SET ascii COLLATE ascii_general_ci NULL DEFAULT NULL,
  `token_expired` date NULL DEFAULT NULL,
  PRIMARY KEY (`id_user`) USING BTREE,
  UNIQUE INDEX `username_user`(`username`) USING BTREE,
  UNIQUE INDEX `email_user`(`email`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 27437 CHARACTER SET = ascii COLLATE = ascii_general_ci COMMENT = 'TRIAL' ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of users
-- ----------------------------
INSERT INTO `users` VALUES (5, 'DANY ISWANTO', 'anggit', 'atma.anggit@gmail.com', '$2y$10$CqlxMM6gOAtYGm2XGeq.6un0mlrwT94Qq2mf.pcinxnZAsJmaj9By', NULL, NULL, '085855729452', NULL, NULL, NULL);
INSERT INTO `users` VALUES (6, 'MOCH IRSYADUL ANAM', 'irsyad7798@gmail.com', 'irsyad7798@gmail.com', '$2y$10$wzqj/2.YIrD7bBbCb.ndhuoDktih2.bulhQQmpi6j5mXxQHdeFf.q', NULL, '2024-06-29 08:56:05', '08674514312331', NULL, NULL, NULL);
INSERT INTO `users` VALUES (27434, 'bos muda', 'admin@admin.com', NULL, '$2y$10$0czjgtn86FWWQbFVe3zDS.uPwz7MvUqJJ1FagOVsj2I9fYLVVZ/zS', '2024-10-04 09:54:37', '2024-10-04 09:54:37', NULL, NULL, NULL, NULL);
INSERT INTO `users` VALUES (27435, 'pemohon', 'pemohon@gmail.com', NULL, '$2y$10$3OG5bttw8MLkYB6/RhZSq.JXFBQUk2zcfRcxbMyPbbomf1Y.ca1j.', '2024-10-08 11:51:52', '2024-10-08 11:51:52', NULL, NULL, NULL, NULL);
INSERT INTO `users` VALUES (27436, 'suherman', 'suherman@gmail.com', NULL, '$2y$10$7c1C5as3h1bOXPpTe4QMsOBmT87VPYx.J97jyuXp0ctxayoqTSyaW', '2024-10-08 14:21:54', '2024-10-08 14:21:54', NULL, NULL, NULL, NULL);

SET FOREIGN_KEY_CHECKS = 1;
