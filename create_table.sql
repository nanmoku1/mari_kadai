-- --------------------------------------------------------
-- ホスト:                          192.168.56.2
-- サーバーのバージョン:                   5.5.54-0ubuntu0.14.04.1 - (Ubuntu)
-- サーバー OS:                      debian-linux-gnu
-- HeidiSQL バージョン:               10.2.0.5599
-- --------------------------------------------------------

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8 */;
/*!50503 SET NAMES utf8mb4 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;


-- get_hatena_data のデータベース構造をダンプしています
CREATE DATABASE IF NOT EXISTS `get_hatena_data` /*!40100 DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci */;
USE `get_hatena_data`;

--  テーブル get_hatena_data.new_hatena_rss の構造をダンプしています
CREATE TABLE IF NOT EXISTS `new_hatena_rss` (
  `nhrs_id` bigint(20) NOT NULL AUTO_INCREMENT,
  `nhrs_title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `nhrs_link` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `nhrs_dsc` longtext COLLATE utf8mb4_unicode_ci,
  `nhrs_h_date` datetime DEFAULT NULL,
  `nhrs_h_date_sort` bigint(20) NOT NULL DEFAULT '0',
  `nhrs_add_date` datetime DEFAULT NULL,
  `nhrs_up_date` datetime DEFAULT NULL,
  `nhrs_del_flg` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`nhrs_id`),
  KEY `nhrs_h_date` (`nhrs_h_date`),
  KEY `nhrs_add_date` (`nhrs_add_date`),
  KEY `nhrs_up_date` (`nhrs_up_date`),
  KEY `nhrs_del_flg` (`nhrs_del_flg`),
  KEY `nhrs_title` (`nhrs_title`(191)),
  KEY `nhrs_link` (`nhrs_link`(191)),
  KEY `nhrs_h_date_sort` (`nhrs_h_date_sort`)
) ENGINE=InnoDB AUTO_INCREMENT=660 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- エクスポートするデータが選択されていません

/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IF(@OLD_FOREIGN_KEY_CHECKS IS NULL, 1, @OLD_FOREIGN_KEY_CHECKS) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
