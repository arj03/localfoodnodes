-- phpMyAdmin SQL Dump
-- version 4.6.5.2
-- https://www.phpmyadmin.net/
--
-- Värd: db:3306
-- Tid vid skapande: 14 mars 2017 kl 20:52
-- Serverversion: 5.7.17
-- PHP-version: 5.6.28

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Databas: `localfoodnodes`
--

-- --------------------------------------------------------

--
-- Tabellstruktur `carts`
--

DROP TABLE IF EXISTS `carts`;
CREATE TABLE `carts` (
  `id` int(10) UNSIGNED NOT NULL,
  `user_id` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumpning av Data i tabell `carts`
--

INSERT INTO `carts` (`id`, `user_id`, `created_at`, `updated_at`) VALUES
(2, 1, '2017-03-14 15:03:18', '2017-03-14 15:03:18');

-- --------------------------------------------------------

--
-- Tabellstruktur `cart_items`
--

DROP TABLE IF EXISTS `cart_items`;
CREATE TABLE `cart_items` (
  `id` int(10) UNSIGNED NOT NULL,
  `cart_id` int(11) NOT NULL,
  `quantity` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `product` text COLLATE utf8_unicode_ci NOT NULL,
  `variant_id` int(11) NOT NULL,
  `variant` text COLLATE utf8_unicode_ci NOT NULL,
  `producer_id` int(11) NOT NULL,
  `producer` text COLLATE utf8_unicode_ci NOT NULL,
  `node_id` int(11) NOT NULL,
  `node` text COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumpning av Data i tabell `cart_items`
--

INSERT INTO `cart_items` (`id`, `cart_id`, `quantity`, `product_id`, `product`, `variant_id`, `variant`, `producer_id`, `producer`, `node_id`, `node`) VALUES
(3, 2, 8, 1, '{\"id\":1,\"producer_id\":2,\"name\":\"Produkt 21\",\"info\":\"<p>En produkt av Producent 2.<\\/p>\",\"package_amount\":10,\"package_unit\":\"pieces\",\"price\":9,\"price_unit\":\"package\",\"image\":null,\"ean_codes\":null,\"payment_info\":null,\"hidden\":0,\"deadline\":null,\"deleted_at\":null,\"created_at\":\"2017-03-14 14:56:47\",\"updated_at\":\"2017-03-14 14:56:47\",\"type\":\"weekly\"}', 0, '{\"name\":\"Produkt 21\",\"package_amount\":10,\"price\":9,\"id\":0}', 2, '{\"id\":2,\"name\":\"Producent 2\",\"email\":\"producent2@localfoodnodes.org\",\"address\":\"Gatan\",\"zip\":\"12345\",\"city\":\"Staden\",\"location\":{\"lat\":\"59.3472158\",\"lng\":\"18.0334595\"},\"info\":\"<p>Info om producent 2.<\\/p>\",\"image\":null,\"currency\":\"SEK\",\"payment_bank\":null,\"payment_account\":null,\"payment_swish\":null,\"payment_info\":null,\"link_homepage\":null,\"link_facebook\":null,\"link_instagram\":null,\"link_twitter\":null,\"setting_ean_codes\":0,\"deleted_at\":null,\"created_at\":\"2017-03-14 14:54:41\",\"updated_at\":\"2017-03-14 14:54:41\"}', 2, '{\"id\":2,\"name\":\"Nod 2\",\"email\":\"nod2@localfoodnodes.org\",\"info\":\"<p>Info om nod 2.<\\/p>\",\"address\":\"Gatan\",\"zip\":\"12345\",\"city\":\"Staden\",\"location\":{\"lat\":\"59.3472158\",\"lng\":\"18.0334595\"},\"link_facebook\":null,\"link_facebook_producers\":null,\"season_start\":\"2017-05-01\",\"season_end\":\"2017-08-31\",\"delivery_time\":\"19:00\",\"delivery_weekday\":\"tuesday\",\"deleted_at\":null,\"created_at\":\"2017-03-14 14:55:39\",\"updated_at\":\"2017-03-14 14:55:39\"}');

-- --------------------------------------------------------

--
-- Tabellstruktur `cart_item_dates`
--

DROP TABLE IF EXISTS `cart_item_dates`;
CREATE TABLE `cart_item_dates` (
  `id` int(10) UNSIGNED NOT NULL,
  `cart_item_id` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `date` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumpning av Data i tabell `cart_item_dates`
--

INSERT INTO `cart_item_dates` (`id`, `cart_item_id`, `date`) VALUES
(7, '3', '2017-03-20'),
(8, '3', '2017-03-27');

-- --------------------------------------------------------

--
-- Tabellstruktur `donations`
--

DROP TABLE IF EXISTS `donations`;
CREATE TABLE `donations` (
  `id` int(10) UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `address` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `city` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `zip` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `amount` int(11) DEFAULT NULL,
  `stripe_token` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `uuid` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `info` text COLLATE utf8_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Tabellstruktur `events`
--

DROP TABLE IF EXISTS `events`;
CREATE TABLE `events` (
  `id` int(10) UNSIGNED NOT NULL,
  `owner_id` int(11) NOT NULL,
  `owner_type` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `info` text COLLATE utf8_unicode_ci NOT NULL,
  `start_datetime` datetime NOT NULL,
  `end_datetime` datetime NOT NULL,
  `address` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `zip` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `city` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `location` point NOT NULL,
  `fee` int(11) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumpning av Data i tabell `events`
--

INSERT INTO `events` (`id`, `owner_id`, `owner_type`, `name`, `info`, `start_datetime`, `end_datetime`, `address`, `zip`, `city`, `location`, `fee`, `created_at`, `updated_at`) VALUES
(1, 1, 'producer', 'Producent 1 - Event', '<p>Ett event.</p>', '2017-05-01 12:00:00', '2017-05-01 20:00:00', 'Gatan', '12345', 'Staden', '\0\0\0\0\0\0\0��<�q�M@�=B͐2@', 100, '2017-03-14 15:00:21', '2017-03-14 15:00:21'),
(2, 2, 'node', 'Nod 2 Event', '<p>Info</p>', '2017-06-01 12:00:00', '2017-06-03 12:00:00', 'Gatan', '12345', 'Staden', '\0\0\0\0\0\0\0��<�q�M@�=B͐2@', 50, '2017-03-14 15:01:13', '2017-03-14 15:01:13');

-- --------------------------------------------------------

--
-- Tabellstruktur `event_user_links`
--

DROP TABLE IF EXISTS `event_user_links`;
CREATE TABLE `event_user_links` (
  `id` int(10) UNSIGNED NOT NULL,
  `event_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumpning av Data i tabell `event_user_links`
--

INSERT INTO `event_user_links` (`id`, `event_id`, `user_id`, `created_at`, `updated_at`) VALUES
(1, 2, 1, '2017-03-14 15:03:23', '2017-03-14 15:03:23');

-- --------------------------------------------------------

--
-- Tabellstruktur `images`
--

DROP TABLE IF EXISTS `images`;
CREATE TABLE `images` (
  `id` int(10) UNSIGNED NOT NULL,
  `entity_id` int(11) NOT NULL,
  `entity_type` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `file` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `context` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `sort` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Tabellstruktur `migrations`
--

DROP TABLE IF EXISTS `migrations`;
CREATE TABLE `migrations` (
  `id` int(10) UNSIGNED NOT NULL,
  `migration` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `batch` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumpning av Data i tabell `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(67, '2014_10_12_000000_create_users_table', 1),
(68, '2014_10_12_100000_create_password_resets_table', 1),
(69, '2016_10_01_000000_create_node_delivery_dates_table', 1),
(70, '2016_10_01_000000_create_nodes_table', 1),
(71, '2016_10_01_000000_create_producers_table', 1),
(72, '2016_10_01_000000_create_products_table', 1),
(73, '2016_10_02_000000_create_producer_node_links_table', 1),
(74, '2016_10_14_000000_add_point_to_node_table', 1),
(75, '2016_10_16_000000_add_point_to_producer_table', 1),
(76, '2016_10_25_000000_create_product_productions_table', 1),
(77, '2016_10_31_000000_create_product_node_delivery_links_table', 1),
(78, '2016_11_01_000000_create_cart_item_dates_table', 1),
(79, '2016_11_01_000000_create_cart_items_table', 1),
(80, '2016_11_01_000000_create_carts_table', 1),
(81, '2016_11_01_000000_create_donations_table', 1),
(82, '2016_11_01_000000_create_order_item_dates_table', 1),
(83, '2016_11_01_000000_create_order_items_table', 1),
(84, '2016_11_01_000000_create_orders_table', 1),
(85, '2016_11_01_000000_create_permalinks_table', 1),
(86, '2016_11_01_000000_create_product_variants_table', 1),
(87, '2016_11_01_000000_create_user_membership_payments_table', 1),
(88, '2016_11_01_000000_create_user_node_links_table', 1),
(89, '2017_01_01_000000_add_node_fulltext_index', 1),
(90, '2017_02_01_000000_create_node_admin_links_table', 1),
(91, '2017_02_01_000000_create_node_producer_blacklist_table', 1),
(92, '2017_02_01_000000_create_order_statuses_table', 1),
(93, '2017_02_01_000000_create_product_tags_table', 1),
(94, '2017_02_01_000000_create_user_activations_table', 1),
(95, '2017_03_01_000000_create_event_user_links_table', 1),
(96, '2017_03_01_000000_create_events_table', 1),
(97, '2017_03_01_000000_create_images_table', 1),
(98, '2017_03_01_000000_create_producer_admin_links_table', 1),
(99, '2017_03_01_000001_add_point_to_events_table', 1);

-- --------------------------------------------------------

--
-- Tabellstruktur `nodes`
--

DROP TABLE IF EXISTS `nodes`;
CREATE TABLE `nodes` (
  `id` int(10) UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `info` text COLLATE utf8_unicode_ci,
  `address` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `zip` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `city` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `location` point NOT NULL,
  `link_facebook` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `link_facebook_producers` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `season_start` date DEFAULT NULL,
  `season_end` date DEFAULT NULL,
  `delivery_time` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `delivery_weekday` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumpning av Data i tabell `nodes`
--

INSERT INTO `nodes` (`id`, `name`, `email`, `info`, `address`, `zip`, `city`, `location`, `link_facebook`, `link_facebook_producers`, `season_start`, `season_end`, `delivery_time`, `delivery_weekday`, `deleted_at`, `created_at`, `updated_at`) VALUES
(1, 'Nod 1', 'nod1@localfoodnodes.org', '<p>Information om nod 1.</p>', 'Gatan', '12345', 'Staden', '\0\0\0\0\0\0\0��<�q�M@�=B͐2@', NULL, NULL, '2017-01-01', '2017-12-31', '18:00', 'monday', NULL, '2017-03-14 14:48:17', '2017-03-14 14:48:17'),
(2, 'Nod 2', 'nod2@localfoodnodes.org', '<p>Info om nod 2.</p>', 'Gatan', '12345', 'Staden', '\0\0\0\0\0\0\0��<�q�M@�=B͐2@', NULL, NULL, '2017-05-01', '2017-08-31', '19:00', 'tuesday', NULL, '2017-03-14 14:55:39', '2017-03-14 14:55:39');

-- --------------------------------------------------------

--
-- Tabellstruktur `node_admin_links`
--

DROP TABLE IF EXISTS `node_admin_links`;
CREATE TABLE `node_admin_links` (
  `id` int(10) UNSIGNED NOT NULL,
  `user_id` int(11) NOT NULL,
  `node_id` int(11) NOT NULL,
  `active` tinyint(4) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumpning av Data i tabell `node_admin_links`
--

INSERT INTO `node_admin_links` (`id`, `user_id`, `node_id`, `active`) VALUES
(1, 1, 1, 1),
(2, 2, 2, 1);

-- --------------------------------------------------------

--
-- Tabellstruktur `node_delivery_dates`
--

DROP TABLE IF EXISTS `node_delivery_dates`;
CREATE TABLE `node_delivery_dates` (
  `id` int(10) UNSIGNED NOT NULL,
  `node_id` int(11) NOT NULL,
  `type` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `date` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Tabellstruktur `node_producer_blacklist`
--

DROP TABLE IF EXISTS `node_producer_blacklist`;
CREATE TABLE `node_producer_blacklist` (
  `node_id` int(11) NOT NULL,
  `producer_id` varchar(255) COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Tabellstruktur `orders`
--

DROP TABLE IF EXISTS `orders`;
CREATE TABLE `orders` (
  `id` int(10) UNSIGNED NOT NULL,
  `user_id` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumpning av Data i tabell `orders`
--

INSERT INTO `orders` (`id`, `user_id`, `created_at`, `updated_at`) VALUES
(1, 3, '2017-03-14 15:02:26', '2017-03-14 15:02:26');

-- --------------------------------------------------------

--
-- Tabellstruktur `order_items`
--

DROP TABLE IF EXISTS `order_items`;
CREATE TABLE `order_items` (
  `id` int(10) UNSIGNED NOT NULL,
  `order_id` int(11) NOT NULL,
  `customer_order_id` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `quantity` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `product` text COLLATE utf8_unicode_ci NOT NULL,
  `variant_id` int(11) NOT NULL,
  `variant` text COLLATE utf8_unicode_ci NOT NULL,
  `producer_id` int(11) NOT NULL,
  `producer` text COLLATE utf8_unicode_ci NOT NULL,
  `node_id` int(11) NOT NULL,
  `node` text COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumpning av Data i tabell `order_items`
--

INSERT INTO `order_items` (`id`, `order_id`, `customer_order_id`, `quantity`, `product_id`, `product`, `variant_id`, `variant`, `producer_id`, `producer`, `node_id`, `node`) VALUES
(1, 1, '6foy2ln4', 2, 1, '{\"id\":1,\"producer_id\":2,\"name\":\"Produkt 21\",\"info\":\"<p>En produkt av Producent 2.<\\/p>\",\"package_amount\":10,\"package_unit\":\"pieces\",\"price\":9,\"price_unit\":\"package\",\"image\":null,\"ean_codes\":null,\"payment_info\":null,\"hidden\":0,\"deadline\":null,\"deleted_at\":null,\"created_at\":\"2017-03-14 14:56:47\",\"updated_at\":\"2017-03-14 14:56:47\",\"type\":\"weekly\"}', 0, '{\"name\":\"Produkt 21\",\"package_amount\":10,\"price\":9,\"id\":0}', 2, '{\"id\":2,\"name\":\"Producent 2\",\"email\":\"producent2@localfoodnodes.org\",\"address\":\"Gatan\",\"zip\":\"12345\",\"city\":\"Staden\",\"location\":{\"lat\":\"59.3472158\",\"lng\":\"18.0334595\"},\"info\":\"<p>Info om producent 2.<\\/p>\",\"image\":null,\"currency\":\"SEK\",\"payment_bank\":null,\"payment_account\":null,\"payment_swish\":null,\"payment_info\":null,\"link_homepage\":null,\"link_facebook\":null,\"link_instagram\":null,\"link_twitter\":null,\"setting_ean_codes\":0,\"deleted_at\":null,\"created_at\":\"2017-03-14 14:54:41\",\"updated_at\":\"2017-03-14 14:54:41\"}', 2, '{\"id\":2,\"name\":\"Nod 2\",\"email\":\"nod2@localfoodnodes.org\",\"info\":\"<p>Info om nod 2.<\\/p>\",\"address\":\"Gatan\",\"zip\":\"12345\",\"city\":\"Staden\",\"location\":{\"lat\":\"59.3472158\",\"lng\":\"18.0334595\"},\"link_facebook\":null,\"link_facebook_producers\":null,\"season_start\":\"2017-05-01\",\"season_end\":\"2017-08-31\",\"delivery_time\":\"19:00\",\"delivery_weekday\":\"tuesday\",\"deleted_at\":null,\"created_at\":\"2017-03-14 14:55:39\",\"updated_at\":\"2017-03-14 14:55:39\"}'),
(2, 1, 'xws5uh1m', 1, 2, '{\"id\":2,\"producer_id\":1,\"name\":\"Produkt 11\",\"info\":\"<p>En produkt fr&aring;n Producent 1.<\\/p>\",\"package_amount\":20,\"package_unit\":\"pieces\",\"price\":20,\"price_unit\":\"package\",\"image\":null,\"ean_codes\":null,\"payment_info\":null,\"hidden\":0,\"deadline\":null,\"deleted_at\":null,\"created_at\":\"2017-03-14 14:59:25\",\"updated_at\":\"2017-03-14 14:59:25\",\"type\":\"occasional\"}', 0, '{\"name\":\"Produkt 11\",\"package_amount\":20,\"price\":20,\"id\":0}', 1, '{\"id\":1,\"name\":\"Producent 1\",\"email\":\"producent1@localfoodnodes.org\",\"address\":\"Gatan\",\"zip\":\"12345\",\"city\":\"Staden\",\"location\":{\"lat\":\"59.3472158\",\"lng\":\"18.0334595\"},\"info\":\"<p>Information om producent 1.<\\/p>\",\"image\":null,\"currency\":\"SEK\",\"payment_bank\":null,\"payment_account\":null,\"payment_swish\":null,\"payment_info\":null,\"link_homepage\":null,\"link_facebook\":null,\"link_instagram\":null,\"link_twitter\":null,\"setting_ean_codes\":0,\"deleted_at\":null,\"created_at\":\"2017-03-14 14:47:36\",\"updated_at\":\"2017-03-14 14:47:36\"}', 1, '{\"id\":2,\"name\":\"Nod 2\",\"email\":\"nod2@localfoodnodes.org\",\"info\":\"<p>Info om nod 2.<\\/p>\",\"address\":\"Gatan\",\"zip\":\"12345\",\"city\":\"Staden\",\"location\":{\"lat\":\"59.3472158\",\"lng\":\"18.0334595\"},\"link_facebook\":null,\"link_facebook_producers\":null,\"season_start\":\"2017-05-01\",\"season_end\":\"2017-08-31\",\"delivery_time\":\"19:00\",\"delivery_weekday\":\"tuesday\",\"deleted_at\":null,\"created_at\":\"2017-03-14 14:55:39\",\"updated_at\":\"2017-03-14 14:55:39\"}');

-- --------------------------------------------------------

--
-- Tabellstruktur `order_item_dates`
--

DROP TABLE IF EXISTS `order_item_dates`;
CREATE TABLE `order_item_dates` (
  `id` int(10) UNSIGNED NOT NULL,
  `order_item_id` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `date` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumpning av Data i tabell `order_item_dates`
--

INSERT INTO `order_item_dates` (`id`, `order_item_id`, `date`) VALUES
(1, '1', '2017-03-20'),
(2, '1', '2017-03-27'),
(3, '2', '2017-04-03'),
(4, '2', '2017-04-10'),
(5, '2', '2017-04-17'),
(6, '2', '2017-04-24');

-- --------------------------------------------------------

--
-- Tabellstruktur `order_statuses`
--

DROP TABLE IF EXISTS `order_statuses`;
CREATE TABLE `order_statuses` (
  `id` int(10) UNSIGNED NOT NULL,
  `entity_id` int(11) NOT NULL,
  `entity_type` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `status` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Tabellstruktur `password_resets`
--

DROP TABLE IF EXISTS `password_resets`;
CREATE TABLE `password_resets` (
  `email` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `token` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Tabellstruktur `permalinks`
--

DROP TABLE IF EXISTS `permalinks`;
CREATE TABLE `permalinks` (
  `id` int(10) UNSIGNED NOT NULL,
  `entity_id` int(11) NOT NULL,
  `entity_type` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `slug` varchar(255) COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumpning av Data i tabell `permalinks`
--

INSERT INTO `permalinks` (`id`, `entity_id`, `entity_type`, `slug`) VALUES
(1, 1, 'producer', 'producent-1'),
(2, 1, 'node', 'nod-1'),
(3, 2, 'producer', 'producent-2'),
(4, 2, 'node', 'nod-2'),
(5, 1, 'product', 'produkt-21'),
(6, 2, 'product', 'produkt-11'),
(7, 1, 'event', 'producent-1-event'),
(8, 2, 'event', 'nod-2-event');

-- --------------------------------------------------------

--
-- Tabellstruktur `producers`
--

DROP TABLE IF EXISTS `producers`;
CREATE TABLE `producers` (
  `id` int(10) UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `address` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `zip` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `city` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `location` point NOT NULL,
  `info` text COLLATE utf8_unicode_ci,
  `image` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `currency` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `payment_bank` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `payment_account` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `payment_swish` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `payment_info` text COLLATE utf8_unicode_ci,
  `link_homepage` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `link_facebook` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `link_instagram` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `link_twitter` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `setting_ean_codes` tinyint(4) DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumpning av Data i tabell `producers`
--

INSERT INTO `producers` (`id`, `name`, `email`, `address`, `zip`, `city`, `location`, `info`, `image`, `currency`, `payment_bank`, `payment_account`, `payment_swish`, `payment_info`, `link_homepage`, `link_facebook`, `link_instagram`, `link_twitter`, `setting_ean_codes`, `deleted_at`, `created_at`, `updated_at`) VALUES
(1, 'Producent 1', 'producent1@localfoodnodes.org', 'Gatan', '12345', 'Staden', '\0\0\0\0\0\0\0��<�q�M@�=B͐2@', '<p>Information om producent 1.</p>', NULL, 'SEK', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, '2017-03-14 14:47:36', '2017-03-14 14:47:36'),
(2, 'Producent 2', 'producent2@localfoodnodes.org', 'Gatan', '12345', 'Staden', '\0\0\0\0\0\0\0��<�q�M@�=B͐2@', '<p>Info om producent 2.</p>', NULL, 'SEK', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, '2017-03-14 14:54:41', '2017-03-14 14:54:41');

-- --------------------------------------------------------

--
-- Tabellstruktur `producer_admin_links`
--

DROP TABLE IF EXISTS `producer_admin_links`;
CREATE TABLE `producer_admin_links` (
  `id` int(10) UNSIGNED NOT NULL,
  `user_id` int(11) NOT NULL,
  `producer_id` int(11) NOT NULL,
  `active` tinyint(4) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumpning av Data i tabell `producer_admin_links`
--

INSERT INTO `producer_admin_links` (`id`, `user_id`, `producer_id`, `active`) VALUES
(1, 1, 1, 1),
(2, 2, 2, 1);

-- --------------------------------------------------------

--
-- Tabellstruktur `producer_node_links`
--

DROP TABLE IF EXISTS `producer_node_links`;
CREATE TABLE `producer_node_links` (
  `id` int(10) UNSIGNED NOT NULL,
  `producer_id` int(10) UNSIGNED NOT NULL,
  `node_id` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumpning av Data i tabell `producer_node_links`
--

INSERT INTO `producer_node_links` (`id`, `producer_id`, `node_id`) VALUES
(4, 1, 1),
(3, 1, 2),
(1, 2, 1),
(2, 2, 2);

-- --------------------------------------------------------

--
-- Tabellstruktur `products`
--

DROP TABLE IF EXISTS `products`;
CREATE TABLE `products` (
  `id` int(10) UNSIGNED NOT NULL,
  `producer_id` int(11) NOT NULL,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `info` text COLLATE utf8_unicode_ci,
  `package_amount` int(11) NOT NULL,
  `package_unit` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `price` int(11) NOT NULL,
  `price_unit` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `image` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `ean_codes` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `payment_info` text COLLATE utf8_unicode_ci,
  `hidden` tinyint(1) DEFAULT NULL,
  `deadline` int(11) DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumpning av Data i tabell `products`
--

INSERT INTO `products` (`id`, `producer_id`, `name`, `info`, `package_amount`, `package_unit`, `price`, `price_unit`, `image`, `ean_codes`, `payment_info`, `hidden`, `deadline`, `deleted_at`, `created_at`, `updated_at`) VALUES
(1, 2, 'Produkt 21', '<p>En produkt av Producent 2.</p>', 10, 'pieces', 9, 'package', NULL, NULL, NULL, 0, NULL, NULL, '2017-03-14 14:56:47', '2017-03-14 14:56:47'),
(2, 1, 'Produkt 11', '<p>En produkt fr&aring;n Producent 1.</p>', 20, 'pieces', 20, 'package', NULL, NULL, NULL, 0, NULL, NULL, '2017-03-14 14:59:25', '2017-03-14 14:59:25');

-- --------------------------------------------------------

--
-- Tabellstruktur `product_node_delivery_links`
--

DROP TABLE IF EXISTS `product_node_delivery_links`;
CREATE TABLE `product_node_delivery_links` (
  `id` int(10) UNSIGNED NOT NULL,
  `product_id` int(10) UNSIGNED NOT NULL,
  `node_id` int(10) UNSIGNED NOT NULL,
  `date` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumpning av Data i tabell `product_node_delivery_links`
--

INSERT INTO `product_node_delivery_links` (`id`, `product_id`, `node_id`, `date`) VALUES
(1, 1, 1, '2017-03-20'),
(2, 1, 1, '2017-03-27'),
(3, 1, 1, '2017-04-03'),
(4, 1, 1, '2017-04-10'),
(5, 1, 1, '2017-04-17'),
(6, 1, 1, '2017-04-24'),
(7, 1, 1, '2017-05-01'),
(8, 1, 1, '2017-05-08'),
(9, 1, 1, '2017-05-15'),
(10, 1, 1, '2017-05-22'),
(11, 1, 1, '2017-05-29'),
(12, 1, 1, '2017-06-05'),
(13, 1, 1, '2017-06-12'),
(14, 1, 1, '2017-06-19'),
(15, 1, 1, '2017-06-26'),
(16, 1, 1, '2017-07-03'),
(17, 1, 1, '2017-07-10'),
(18, 1, 1, '2017-07-17'),
(19, 1, 1, '2017-07-24'),
(20, 1, 1, '2017-07-31'),
(21, 1, 1, '2017-08-07'),
(22, 1, 1, '2017-08-14'),
(23, 1, 1, '2017-08-21'),
(24, 1, 1, '2017-08-28'),
(25, 1, 1, '2017-09-04'),
(26, 1, 1, '2017-09-11'),
(27, 1, 1, '2017-09-18'),
(28, 1, 1, '2017-09-25'),
(29, 1, 1, '2017-10-02'),
(30, 1, 1, '2017-10-09'),
(31, 1, 1, '2017-10-16'),
(32, 1, 1, '2017-10-23'),
(33, 1, 1, '2017-10-30'),
(34, 1, 1, '2017-11-06'),
(35, 1, 1, '2017-11-13'),
(36, 1, 1, '2017-11-20'),
(37, 1, 1, '2017-11-27'),
(38, 1, 1, '2017-12-04'),
(39, 1, 1, '2017-12-11'),
(40, 1, 1, '2017-12-18'),
(41, 1, 1, '2017-12-25'),
(42, 1, 2, '2017-05-02'),
(43, 1, 2, '2017-05-09'),
(44, 1, 2, '2017-05-16'),
(45, 1, 2, '2017-05-23'),
(46, 1, 2, '2017-05-30'),
(47, 1, 2, '2017-06-06'),
(48, 1, 2, '2017-06-13'),
(49, 1, 2, '2017-06-20'),
(50, 1, 2, '2017-06-27'),
(51, 1, 2, '2017-07-04'),
(52, 1, 2, '2017-07-11'),
(53, 1, 2, '2017-07-18'),
(54, 1, 2, '2017-07-25'),
(55, 1, 2, '2017-08-01'),
(56, 1, 2, '2017-08-08'),
(57, 1, 2, '2017-08-15'),
(58, 1, 2, '2017-08-22'),
(59, 1, 2, '2017-08-29'),
(60, 2, 1, '2017-03-20'),
(61, 2, 1, '2017-03-27'),
(62, 2, 1, '2017-04-03'),
(63, 2, 1, '2017-04-10'),
(64, 2, 1, '2017-04-17'),
(65, 2, 1, '2017-04-24'),
(66, 2, 1, '2017-05-01'),
(67, 2, 1, '2017-05-08'),
(68, 2, 1, '2017-05-15'),
(69, 2, 1, '2017-05-22'),
(70, 2, 1, '2017-05-29'),
(71, 2, 1, '2017-06-05'),
(72, 2, 1, '2017-06-12'),
(73, 2, 1, '2017-06-19'),
(74, 2, 1, '2017-06-26'),
(75, 2, 1, '2017-07-03'),
(76, 2, 1, '2017-07-10'),
(77, 2, 1, '2017-07-17'),
(78, 2, 1, '2017-07-24'),
(79, 2, 1, '2017-07-31'),
(80, 2, 1, '2017-08-07'),
(81, 2, 1, '2017-08-14'),
(82, 2, 1, '2017-08-21'),
(83, 2, 1, '2017-08-28'),
(84, 2, 1, '2017-09-04'),
(85, 2, 1, '2017-09-11'),
(86, 2, 1, '2017-09-18'),
(87, 2, 1, '2017-09-25'),
(88, 2, 1, '2017-10-02'),
(89, 2, 1, '2017-10-09'),
(90, 2, 1, '2017-10-16'),
(91, 2, 1, '2017-10-23'),
(92, 2, 1, '2017-10-30'),
(93, 2, 1, '2017-11-06'),
(94, 2, 1, '2017-11-13'),
(95, 2, 1, '2017-11-20'),
(96, 2, 1, '2017-11-27'),
(97, 2, 1, '2017-12-04'),
(98, 2, 1, '2017-12-11'),
(99, 2, 1, '2017-12-18'),
(100, 2, 1, '2017-12-25'),
(101, 2, 2, '2017-05-02'),
(102, 2, 2, '2017-05-09'),
(103, 2, 2, '2017-05-16'),
(104, 2, 2, '2017-05-23'),
(105, 2, 2, '2017-05-30'),
(106, 2, 2, '2017-06-06'),
(107, 2, 2, '2017-06-13'),
(108, 2, 2, '2017-06-20'),
(109, 2, 2, '2017-06-27'),
(110, 2, 2, '2017-07-04'),
(111, 2, 2, '2017-07-11'),
(112, 2, 2, '2017-07-18'),
(113, 2, 2, '2017-07-25'),
(114, 2, 2, '2017-08-01'),
(115, 2, 2, '2017-08-08'),
(116, 2, 2, '2017-08-15'),
(117, 2, 2, '2017-08-22'),
(118, 2, 2, '2017-08-29');

-- --------------------------------------------------------

--
-- Tabellstruktur `product_productions`
--

DROP TABLE IF EXISTS `product_productions`;
CREATE TABLE `product_productions` (
  `id` int(10) UNSIGNED NOT NULL,
  `product_id` int(11) NOT NULL,
  `start_date` date DEFAULT NULL,
  `end_date` date DEFAULT NULL,
  `quantity` int(11) NOT NULL,
  `type` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumpning av Data i tabell `product_productions`
--

INSERT INTO `product_productions` (`id`, `product_id`, `start_date`, `end_date`, `quantity`, `type`, `created_at`, `updated_at`) VALUES
(1, 1, NULL, NULL, 10, 'weekly', '2017-03-14 14:56:47', '2017-03-14 14:56:47'),
(2, 2, '2017-04-01', NULL, 20, 'occasional', '2017-03-14 14:59:25', '2017-03-14 14:59:25');

-- --------------------------------------------------------

--
-- Tabellstruktur `product_tags`
--

DROP TABLE IF EXISTS `product_tags`;
CREATE TABLE `product_tags` (
  `id` int(10) UNSIGNED NOT NULL,
  `product_id` int(11) NOT NULL,
  `tag` varchar(255) COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumpning av Data i tabell `product_tags`
--

INSERT INTO `product_tags` (`id`, `product_id`, `tag`) VALUES
(1, 1, 'vegetable');

-- --------------------------------------------------------

--
-- Tabellstruktur `product_variants`
--

DROP TABLE IF EXISTS `product_variants`;
CREATE TABLE `product_variants` (
  `id` int(10) UNSIGNED NOT NULL,
  `product_id` int(11) NOT NULL,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `price` int(11) NOT NULL,
  `package_amount` int(11) NOT NULL,
  `ean_codes` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Tabellstruktur `users`
--

DROP TABLE IF EXISTS `users`;
CREATE TABLE `users` (
  `id` int(10) UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `password` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `address` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `zip` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `city` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `active` tinyint(4) NOT NULL DEFAULT '0',
  `language` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `remember_token` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumpning av Data i tabell `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `password`, `address`, `zip`, `city`, `active`, `language`, `remember_token`, `deleted_at`, `created_at`, `updated_at`) VALUES
(1, 'Användare 1', 'anvandare1@localfoodnodes.org', '$2y$10$mNEOV/tPYJd1afJlSqd7EOFcJBhqv8H3rdD/YEMvAsFKWHFAM41Yi', NULL, NULL, NULL, 1, NULL, 'tDGFVbeyAL7tWmKsWPrcgv1wmne1hQJNInO4ZiQZP7iKJMmEPnZIPQ2n0Voh', NULL, '2017-03-14 14:45:53', '2017-03-14 14:45:53'),
(2, 'Användare 2', 'anvandare2@localfoodnodes.org', '$2y$10$LeI42dwzXRbZ1Xq1H/S5d.N2fGLt6qFLfahYHX1ro1CVoG3o2.tni', NULL, NULL, NULL, 1, NULL, '1X1IC7MTkjmrfQIptXmfLORTQfJBvMYwsnOin3VS5r5lFaHWSPc8WzXlQtS0', NULL, '2017-03-14 14:46:14', '2017-03-14 14:46:14'),
(3, 'Användare 3', 'anvandare3@localfoodnodes.org', '$2y$10$N7YuWk4xGdqFBnXTg8nfHuwhZLsFKyYoSBVesweYNHAtROcARdpAi', NULL, NULL, NULL, 1, NULL, 'fscLP0j75AxfmqrxZ7C7wID80nv1tSqolfStFIznQEaCklz38IP0ULkyXf40', NULL, '2017-03-14 15:01:33', '2017-03-14 15:01:33');

-- --------------------------------------------------------

--
-- Tabellstruktur `user_activations`
--

DROP TABLE IF EXISTS `user_activations`;
CREATE TABLE `user_activations` (
  `user_id` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `token` varchar(255) COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumpning av Data i tabell `user_activations`
--

INSERT INTO `user_activations` (`user_id`, `token`) VALUES
('3', '0c748480dd5ca2c8c9e8afe90b6021a789dec5186390928d0dfe3fb7cfe79fb8'),
('2', '666fbb0a05264d0b4a6d5cc584f7d1d05644ac0312ce30eb341475d3ab4bddad'),
('1', 'eaa8412462f1b0f29d200b932a6babeee120f719e1004da0ca3021542dddd2eb');

-- --------------------------------------------------------

--
-- Tabellstruktur `user_membership_payments`
--

DROP TABLE IF EXISTS `user_membership_payments`;
CREATE TABLE `user_membership_payments` (
  `id` int(10) UNSIGNED NOT NULL,
  `user_id` int(11) NOT NULL,
  `amount` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Tabellstruktur `user_node_links`
--

DROP TABLE IF EXISTS `user_node_links`;
CREATE TABLE `user_node_links` (
  `id` int(10) UNSIGNED NOT NULL,
  `user_id` int(11) NOT NULL,
  `node_id` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Index för dumpade tabeller
--

--
-- Index för tabell `carts`
--
ALTER TABLE `carts`
  ADD PRIMARY KEY (`id`);

--
-- Index för tabell `cart_items`
--
ALTER TABLE `cart_items`
  ADD PRIMARY KEY (`id`);

--
-- Index för tabell `cart_item_dates`
--
ALTER TABLE `cart_item_dates`
  ADD PRIMARY KEY (`id`);

--
-- Index för tabell `donations`
--
ALTER TABLE `donations`
  ADD PRIMARY KEY (`id`);

--
-- Index för tabell `events`
--
ALTER TABLE `events`
  ADD PRIMARY KEY (`id`),
  ADD SPATIAL KEY `location` (`location`);

--
-- Index för tabell `event_user_links`
--
ALTER TABLE `event_user_links`
  ADD PRIMARY KEY (`id`);

--
-- Index för tabell `images`
--
ALTER TABLE `images`
  ADD PRIMARY KEY (`id`);

--
-- Index för tabell `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Index för tabell `nodes`
--
ALTER TABLE `nodes`
  ADD PRIMARY KEY (`id`),
  ADD SPATIAL KEY `location` (`location`);
ALTER TABLE `nodes` ADD FULLTEXT KEY `search_index` (`name`,`address`,`zip`,`city`);

--
-- Index för tabell `node_admin_links`
--
ALTER TABLE `node_admin_links`
  ADD PRIMARY KEY (`id`);

--
-- Index för tabell `node_delivery_dates`
--
ALTER TABLE `node_delivery_dates`
  ADD PRIMARY KEY (`id`);

--
-- Index för tabell `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`id`);

--
-- Index för tabell `order_items`
--
ALTER TABLE `order_items`
  ADD PRIMARY KEY (`id`);

--
-- Index för tabell `order_item_dates`
--
ALTER TABLE `order_item_dates`
  ADD PRIMARY KEY (`id`);

--
-- Index för tabell `order_statuses`
--
ALTER TABLE `order_statuses`
  ADD PRIMARY KEY (`id`);

--
-- Index för tabell `password_resets`
--
ALTER TABLE `password_resets`
  ADD KEY `password_resets_email_index` (`email`),
  ADD KEY `password_resets_token_index` (`token`);

--
-- Index för tabell `permalinks`
--
ALTER TABLE `permalinks`
  ADD PRIMARY KEY (`id`);

--
-- Index för tabell `producers`
--
ALTER TABLE `producers`
  ADD PRIMARY KEY (`id`),
  ADD SPATIAL KEY `location` (`location`);

--
-- Index för tabell `producer_admin_links`
--
ALTER TABLE `producer_admin_links`
  ADD PRIMARY KEY (`id`);

--
-- Index för tabell `producer_node_links`
--
ALTER TABLE `producer_node_links`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `producer_node_links_producer_id_node_id_unique` (`producer_id`,`node_id`);

--
-- Index för tabell `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`);

--
-- Index för tabell `product_node_delivery_links`
--
ALTER TABLE `product_node_delivery_links`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `product_id_node_id_date` (`product_id`,`node_id`,`date`);

--
-- Index för tabell `product_productions`
--
ALTER TABLE `product_productions`
  ADD PRIMARY KEY (`id`);

--
-- Index för tabell `product_tags`
--
ALTER TABLE `product_tags`
  ADD PRIMARY KEY (`id`);

--
-- Index för tabell `product_variants`
--
ALTER TABLE `product_variants`
  ADD PRIMARY KEY (`id`);

--
-- Index för tabell `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_email_unique` (`email`);

--
-- Index för tabell `user_activations`
--
ALTER TABLE `user_activations`
  ADD UNIQUE KEY `user_activations_user_id_unique` (`user_id`),
  ADD UNIQUE KEY `user_activations_token_unique` (`token`);

--
-- Index för tabell `user_membership_payments`
--
ALTER TABLE `user_membership_payments`
  ADD PRIMARY KEY (`id`);

--
-- Index för tabell `user_node_links`
--
ALTER TABLE `user_node_links`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT för dumpade tabeller
--

--
-- AUTO_INCREMENT för tabell `carts`
--
ALTER TABLE `carts`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT för tabell `cart_items`
--
ALTER TABLE `cart_items`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
--
-- AUTO_INCREMENT för tabell `cart_item_dates`
--
ALTER TABLE `cart_item_dates`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;
--
-- AUTO_INCREMENT för tabell `donations`
--
ALTER TABLE `donations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT för tabell `events`
--
ALTER TABLE `events`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT för tabell `event_user_links`
--
ALTER TABLE `event_user_links`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT för tabell `images`
--
ALTER TABLE `images`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT för tabell `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=100;
--
-- AUTO_INCREMENT för tabell `nodes`
--
ALTER TABLE `nodes`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT för tabell `node_admin_links`
--
ALTER TABLE `node_admin_links`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT för tabell `node_delivery_dates`
--
ALTER TABLE `node_delivery_dates`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT för tabell `orders`
--
ALTER TABLE `orders`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT för tabell `order_items`
--
ALTER TABLE `order_items`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT för tabell `order_item_dates`
--
ALTER TABLE `order_item_dates`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;
--
-- AUTO_INCREMENT för tabell `order_statuses`
--
ALTER TABLE `order_statuses`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT för tabell `permalinks`
--
ALTER TABLE `permalinks`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;
--
-- AUTO_INCREMENT för tabell `producers`
--
ALTER TABLE `producers`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT för tabell `producer_admin_links`
--
ALTER TABLE `producer_admin_links`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT för tabell `producer_node_links`
--
ALTER TABLE `producer_node_links`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;
--
-- AUTO_INCREMENT för tabell `products`
--
ALTER TABLE `products`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT för tabell `product_node_delivery_links`
--
ALTER TABLE `product_node_delivery_links`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=119;
--
-- AUTO_INCREMENT för tabell `product_productions`
--
ALTER TABLE `product_productions`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT för tabell `product_tags`
--
ALTER TABLE `product_tags`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT för tabell `product_variants`
--
ALTER TABLE `product_variants`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT för tabell `users`
--
ALTER TABLE `users`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
--
-- AUTO_INCREMENT för tabell `user_membership_payments`
--
ALTER TABLE `user_membership_payments`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT för tabell `user_node_links`
--
ALTER TABLE `user_node_links`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
