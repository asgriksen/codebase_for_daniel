/*
SQLyog Ultimate v10.00 Beta1
MySQL - 5.6.15 : Database - facebook_vi
*********************************************************************
*/


/*!40101 SET NAMES utf8 */;

/*!40101 SET SQL_MODE=''*/;

/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;
/*Table structure for table `accounts` */

DROP TABLE IF EXISTS `accounts`;

CREATE TABLE `accounts` (
  `id` int(32) NOT NULL AUTO_INCREMENT,
  `user_id` int(32) DEFAULT NULL,
  `name` varchar(256) COLLATE utf8_unicode_ci DEFAULT NULL,
  `fb_account_id` varchar(256) COLLATE utf8_unicode_ci DEFAULT NULL,
  `fb_token` varbinary(1024) DEFAULT NULL,
  `fb_token_expiry` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `is_selected` tinyint(4) DEFAULT '1',
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

/*Data for the table `accounts` */

/*Table structure for table `ads` */

DROP TABLE IF EXISTS `ads`;

CREATE TABLE `ads` (
  `id` int(32) NOT NULL AUTO_INCREMENT,
  `ads_set_id` int(32) DEFAULT NULL,
  `type` enum('NEWS_FEED_AD','RIGHT_HAND_SIDE_AD','MULTIPLE_PRODUCTS_AD') COLLATE utf8_unicode_ci DEFAULT NULL,
  `name` varchar(256) COLLATE utf8_unicode_ci DEFAULT NULL,
  `fb_ad_id` varchar(256) COLLATE utf8_unicode_ci DEFAULT NULL,
  `status` enum('ACTIVE','PAUSED') COLLATE utf8_unicode_ci DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `viral_style_campaign_admin_link` varchar(1024) COLLATE utf8_unicode_ci DEFAULT NULL,
  `viral_style_product_link` varchar(1024) COLLATE utf8_unicode_ci DEFAULT NULL,
  `facebook_fanpage_link` varchar(1024) COLLATE utf8_unicode_ci DEFAULT NULL,
  `facebook_post_link` varchar(1024) COLLATE utf8_unicode_ci DEFAULT NULL,
  `facebook_ad_set_link` varchar(1024) COLLATE utf8_unicode_ci DEFAULT NULL,
  `bid` float DEFAULT '0',
  `budget` float DEFAULT '0',
  `photo` varchar(256) COLLATE utf8_unicode_ci DEFAULT NULL,
  `description` text COLLATE utf8_unicode_ci,
  `target_mobile` tinyint(4) DEFAULT '1',
  `target_desktop` tinyint(4) DEFAULT '1',
  `url` varchar(1024) COLLATE utf8_unicode_ci DEFAULT NULL,
  `distribute` enum('IMAGE','TITLE','DESCRIPTION','URL') COLLATE utf8_unicode_ci DEFAULT NULL,
  `fb_fan_page_id` varchar(1024) COLLATE utf8_unicode_ci DEFAULT NULL,
  `action_type` enum('SHOP_NOW','LEARN_MORE','SIGN_UP','BOOK_NOW','DOWNLOAD') COLLATE utf8_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

/*Data for the table `ads` */

/*Table structure for table `ads_products` */

DROP TABLE IF EXISTS `ad_products`;

CREATE TABLE `ad_products` (
  `id` int(32) NOT NULL AUTO_INCREMENT,
  `ad_id` int(32) DEFAULT NULL,
  `product_id` int(32) DEFAULT NULL,
  `title` varchar(256) COLLATE utf8_unicode_ci DEFAULT NULL,
  `price` float DEFAULT '0',
  `photo` varchar(256) COLLATE utf8_unicode_ci DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

/*Data for the table `ads_products` */

/*Table structure for table `ads_sets` */

DROP TABLE IF EXISTS `ad_sets`;

CREATE TABLE `ad_sets` (
  `id` int(32) NOT NULL AUTO_INCREMENT,
  `campaign_id` int(32) DEFAULT NULL,
  `type` enum('GET_VISITORS_ADS','BOOST_POST_ADS','DYNAMIC_PRODUCT_ADS') COLLATE utf8_unicode_ci DEFAULT NULL,
  `name` varchar(256) COLLATE utf8_unicode_ci DEFAULT NULL,
  `status` enum('ACTIVE','PAUSED') COLLATE utf8_unicode_ci DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

/*Data for the table `ads_sets` */

/*Table structure for table `campaigns` */

DROP TABLE IF EXISTS `campaigns`;

CREATE TABLE `campaigns` (
  `id` int(32) NOT NULL AUTO_INCREMENT,
  `account_id` int(32) DEFAULT NULL,
  `name` varchar(256) COLLATE utf8_unicode_ci DEFAULT NULL,
  `fb_campaign_id` text COLLATE utf8_unicode_ci,
  `conversion_pixel` varchar(32) COLLATE utf8_unicode_ci DEFAULT NULL,
  `adset_prefix` varchar(32) COLLATE utf8_unicode_ci DEFAULT NULL,
  `adset_budget` float DEFAULT '0',
  `adset_budget_type` enum('DAILY','LIFETIME') COLLATE utf8_unicode_ci DEFAULT NULL,
  `schedule_type` enum('CONTINUE','START_END','DAYS_OF_WEEK') COLLATE utf8_unicode_ci DEFAULT NULL,
  `schedule` text COLLATE utf8_unicode_ci,
  `optimize_for` enum('CLICKS_TO_WEBSITE','CLICKS','DAILY_UNIQUE_REACH','IMPRESSIONS') COLLATE utf8_unicode_ci DEFAULT NULL,
  `bidding` float DEFAULT '0',
  `campaign_end` enum('PAUSE','DELETE','NOTHING') COLLATE utf8_unicode_ci DEFAULT NULL,
  `status` enum('ACTIVE','PAUSED') COLLATE utf8_unicode_ci DEFAULT 'ACTIVE',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

/*Data for the table `campaigns` */

/*Table structure for table `metrics` */

DROP TABLE IF EXISTS `metrics`;

CREATE TABLE `metrics` (
  `id` int(32) NOT NULL AUTO_INCREMENT,
  `ad_id` int(32) DEFAULT NULL,
  `clicks` int(11) DEFAULT '0',
  `ctr` float DEFAULT '0',
  `cpc` float DEFAULT '0',
  `cpi` float DEFAULT '0',
  `impressions` int(11) DEFAULT '0',
  `frequency` float DEFAULT '0',
  `spend` float DEFAULT '0',
  `reach` float DEFAULT '0',
  `revenue` float DEFAULT '0',
  `roi` float DEFAULT '0',
  `transactions` float DEFAULT '0',
  `cos` float DEFAULT '0',
  `cpt` float DEFAULT '0',
  `per_click_value` float DEFAULT '0',
  `fb_conversion_rate` float DEFAULT '0',
  `fb_comments` int(11) DEFAULT '0',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT NULL,
  `fb_likes` int(11) DEFAULT '0',
  `fb_shares` int(11) DEFAULT '0',
  `ecommerce_conversion_rate` float DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

/*Data for the table `metrics` */

/*Table structure for table `notifications` */

DROP TABLE IF EXISTS `notifications`;

CREATE TABLE `notifications` (
  `id` bigint(64) NOT NULL AUTO_INCREMENT,
  `user_id` int(32) DEFAULT NULL,
  `type` enum('RULE','TASK','MISC') COLLATE utf8_unicode_ci DEFAULT NULL,
  `title` varchar(256) COLLATE utf8_unicode_ci DEFAULT NULL,
  `data` text COLLATE utf8_unicode_ci,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

/*Data for the table `notifications` */

/*Table structure for table `password_resets` */

DROP TABLE IF EXISTS `password_resets`;

CREATE TABLE `password_resets` (
  `id` int(32) NOT NULL AUTO_INCREMENT,
  `email` varchar(256) COLLATE utf8_unicode_ci DEFAULT NULL,
  `token` varchar(256) COLLATE utf8_unicode_ci DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

/*Data for the table `password_resets` */

/*Table structure for table `products` */

DROP TABLE IF EXISTS `products`;

CREATE TABLE `products` (
  `id` int(32) NOT NULL AUTO_INCREMENT,
  `user_id` int(32) DEFAULT NULL,
  `name` varchar(256) COLLATE utf8_unicode_ci DEFAULT NULL,
  `photo` varchar(512) COLLATE utf8_unicode_ci DEFAULT NULL,
  `price` float DEFAULT NULL,
  `conversions` int(11) DEFAULT NULL,
  `revenue_daily` float DEFAULT NULL,
  `revenue_weekly` float DEFAULT NULL,
  `revenue_monthly` float DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

/*Data for the table `products` */

/*Table structure for table `rules` */

DROP TABLE IF EXISTS `rules`;

CREATE TABLE `rules` (
  `id` int(32) NOT NULL AUTO_INCREMENT,
  `user_id` int(32) DEFAULT NULL,
  `strategy` enum('ECONOMIC','BALANCED','AGGRESIVE') COLLATE utf8_unicode_ci DEFAULT NULL,
  `name` varchar(256) COLLATE utf8_unicode_ci DEFAULT NULL,
  `interval` varchar(32) COLLATE utf8_unicode_ci DEFAULT NULL,
  `condition` text COLLATE utf8_unicode_ci,
  `action` text COLLATE utf8_unicode_ci,
  `report_repeated` varchar(32) COLLATE utf8_unicode_ci DEFAULT NULL,
  `report_email` varchar(256) COLLATE utf8_unicode_ci DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

/*Data for the table `rules` */

/*Table structure for table `rules_applications` */

DROP TABLE IF EXISTS `rules_applications`;

CREATE TABLE `rules_applications` (
  `id` int(32) DEFAULT NULL,
  `rule_id` int(32) DEFAULT NULL,
  `layer` enum('CAMPAIGN','AD','AD_SET') COLLATE utf8_unicode_ci DEFAULT NULL,
  `ref_id` int(32) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

/*Data for the table `rules_applications` */

/*Table structure for table `tasks` */

DROP TABLE IF EXISTS `tasks`;

CREATE TABLE `tasks` (
  `int` int(32) NOT NULL AUTO_INCREMENT,
  `user_id` int(32) DEFAULT NULL,
  `type` enum('AD_CREATE','AD_UPDATE','AD_REMOVE') COLLATE utf8_unicode_ci DEFAULT NULL,
  `ref_id` int(32) DEFAULT NULL,
  `status` enum('CREATED','PROGRESS','FINISHED') COLLATE utf8_unicode_ci DEFAULT NULL,
  `progress` float DEFAULT '0',
  `result_total` int(11) DEFAULT '0',
  `result_success` int(11) DEFAULT '0',
  `result_error` int(11) DEFAULT '0',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`int`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

/*Data for the table `tasks` */

/*Table structure for table `users` */

DROP TABLE IF EXISTS `users`;

CREATE TABLE `users` (
  `id` int(32) NOT NULL AUTO_INCREMENT,
  `email` varchar(32) COLLATE utf8_unicode_ci DEFAULT NULL,
  `password` varchar(256) COLLATE utf8_unicode_ci DEFAULT NULL,
  `name` varchar(64) COLLATE utf8_unicode_ci DEFAULT NULL,
  `type` varchar(32) COLLATE utf8_unicode_ci DEFAULT 'USER',
  `remember_token` varchar(256) COLLATE utf8_unicode_ci DEFAULT NULL,
  `verify_token` varchar(256) COLLATE utf8_unicode_ci DEFAULT NULL,
  `verify_token_expiry` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `online_check_at` timestamp NULL DEFAULT NULL,
  `status` enum('ACTIVE','BLOCKED','UNVERIFIED') COLLATE utf8_unicode_ci DEFAULT 'UNVERIFIED',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

/*Data for the table `users` */

insert  into `users`(`id`,`email`,`password`,`name`,`type`,`remember_token`,`verify_token`,`verify_token_expiry`,`online_check_at`,`status`,`created_at`,`updated_at`,`deleted_at`) values (1,'andriy.polanski@gmail.com','$2y$10$5msU.DDCDfC8idmLiRmf6OLvtjtJxmLWwFpOwrwphpqyjsgev/KyW','Andriy Polanski','USER','k08JiwtqtSavNsHN2nxNgV8iQPJr5uZbDzrnQDy8u4uYbAFZEfYgusuLoKtk',NULL,'2015-07-09 11:33:44','2015-07-09 15:33:44','UNVERIFIED','2015-07-05 05:46:56','2015-07-09 15:33:44',NULL);

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;