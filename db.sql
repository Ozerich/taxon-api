-- phpMyAdmin SQL Dump
-- version 4.0.5
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Sep 18, 2013 at 02:31 AM
-- Server version: 5.1.68-cll-lve
-- PHP Version: 5.3.17

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `ozisby_taxon`
--

-- --------------------------------------------------------

--
-- Table structure for table `drivers`
--

CREATE TABLE IF NOT EXISTS `drivers` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `accepted` tinyint(1) NOT NULL,
  `sleep` tinyint(1) NOT NULL DEFAULT '1',
  `token` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `position` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `surname` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `car` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `car_number` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `car_type` enum('sedan','universal','van') COLLATE utf8_unicode_ci NOT NULL,
  `car_color` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `organization_id` int(11) NOT NULL,
  `phone` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `document_number` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=2 ;

--
-- Dumping data for table `drivers`
--

INSERT INTO `drivers` (`id`, `accepted`, `sleep`, `token`, `position`, `name`, `surname`, `car`, `car_number`, `car_type`, `car_color`, `organization_id`, `phone`, `document_number`) VALUES
(1, 1, 0, '150ee3b4763feb7dbeab162b4ee5be69', '32.34235;-23.235235', 'Виталий', 'Озерский', 'Опель Корса', '1254152', 'sedan', 'Розовый', 1, '+375296704790', '12401904');

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE IF NOT EXISTS `orders` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `status` enum('created','searching','wait_client','success','cancelled','car_no_found') COLLATE utf8_unicode_ci NOT NULL,
  `timestamp` int(11) NOT NULL,
  `car_type` enum('any','sedan','universal','van') COLLATE utf8_unicode_ci NOT NULL,
  `client_phone` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `client_coords` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `driver_id` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=4 ;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`id`, `status`, `timestamp`, `car_type`, `client_phone`, `client_coords`, `driver_id`) VALUES
(2, 'success', 1379456116, 'sedan', '375296110404', '50.0000;60.00000', 1),
(3, 'cancelled', 1379456682, 'sedan', '375296110404', '50.0000;60.00000', 1);

-- --------------------------------------------------------

--
-- Table structure for table `order_drivers`
--

CREATE TABLE IF NOT EXISTS `order_drivers` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `order_id` int(11) NOT NULL,
  `driver_id` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=cp1251 AUTO_INCREMENT=4 ;

--
-- Dumping data for table `order_drivers`
--

INSERT INTO `order_drivers` (`id`, `order_id`, `driver_id`) VALUES
(1, 1, 1),
(2, 2, 1),
(3, 3, 1);

-- --------------------------------------------------------

--
-- Table structure for table `organizations`
--

CREATE TABLE IF NOT EXISTS `organizations` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=7 ;

--
-- Dumping data for table `organizations`
--

INSERT INTO `organizations` (`id`, `name`) VALUES
(1, '7788'),
(2, '107'),
(3, '135'),
(4, '152'),
(5, '202'),
(6, '107');

-- --------------------------------------------------------

--
-- Table structure for table `requests`
--

CREATE TABLE IF NOT EXISTS `requests` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `order_id` int(11) NOT NULL,
  `driver_id` int(11) NOT NULL,
  `time` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=cp1251 AUTO_INCREMENT=8 ;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
