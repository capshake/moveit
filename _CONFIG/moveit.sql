-- phpMyAdmin SQL Dump
-- version 3.4.10.1deb1
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Erstellungszeit: 21. Okt 2014 um 10:11
-- Server Version: 5.5.38
-- PHP-Version: 5.3.10-1ubuntu3.14

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Datenbank: `moveit`
--

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `items`
--

CREATE TABLE IF NOT EXISTS `items` (
  `item_id` int(32) NOT NULL AUTO_INCREMENT,
  `description` varchar(128) NOT NULL,
  `room` int(11) NOT NULL,
  `position_x` int(32) DEFAULT NULL,
  `position_y` int(32) DEFAULT NULL,
  `size_x` int(32) DEFAULT NULL,
  `size_y` int(32) DEFAULT NULL,
  `size_z` int(32) DEFAULT NULL,
  `state` int(32) NOT NULL,
  `type` int(32) NOT NULL,
  PRIMARY KEY (`item_id`),
  UNIQUE KEY `type` (`type`),
  UNIQUE KEY `room` (`room`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2 ;

--
-- Daten für Tabelle `items`
--

INSERT INTO `items` (`item_id`, `description`, `room`, `position_x`, `position_y`, `size_x`, `size_y`, `size_z`, `state`, `type`) VALUES
(1, 'Besprechungstisch', 1, NULL, NULL, 156, 60, 72, 0, 1);

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `Item_types`
--

CREATE TABLE IF NOT EXISTS `Item_types` (
  `item_type_id` int(32) NOT NULL AUTO_INCREMENT,
  `name` varchar(32) COLLATE latin1_german1_ci NOT NULL,
  PRIMARY KEY (`item_type_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 COLLATE=latin1_german1_ci AUTO_INCREMENT=2 ;

--
-- Daten für Tabelle `Item_types`
--

INSERT INTO `Item_types` (`item_type_id`, `name`) VALUES
(1, 'Tisch');

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `roles`
--

CREATE TABLE IF NOT EXISTS `roles` (
  `id` int(32) NOT NULL AUTO_INCREMENT,
  `name` varchar(32) COLLATE latin1_german1_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_german1_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `rooms`
--

CREATE TABLE IF NOT EXISTS `rooms` (
  `id` varchar(32) COLLATE latin1_german1_ci NOT NULL,
  `description` varchar(32) COLLATE latin1_german1_ci NOT NULL,
  `position_x` int(32) NOT NULL,
  `position_y` int(32) NOT NULL,
  `size_x` int(32) NOT NULL,
  `size_y` int(32) NOT NULL,
  `owner` int(32) NOT NULL,
  `type` varchar(32) COLLATE latin1_german1_ci NOT NULL,
  `floor` int(32) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `owner` (`owner`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_german1_ci;

--
-- Daten für Tabelle `rooms`
--

INSERT INTO `rooms` (`id`, `description`, `position_x`, `position_y`, `size_x`, `size_y`, `owner`, `type`, `floor`) VALUES
('H 1.17', 'Seminarraum', 12, 32, 11, 15, 1, 'B', 1);

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `users`
--

CREATE TABLE IF NOT EXISTS `users` (
  `id` int(32) NOT NULL AUTO_INCREMENT,
  `name` varchar(32) COLLATE latin1_german1_ci NOT NULL,
  `firstname` varchar(32) COLLATE latin1_german1_ci NOT NULL,
  `login` varchar(32) COLLATE latin1_german1_ci NOT NULL,
  `password` varchar(32) COLLATE latin1_german1_ci NOT NULL,
  `email` varchar(32) COLLATE latin1_german1_ci NOT NULL,
  `role` int(32) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `role` (`role`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_german1_ci AUTO_INCREMENT=1 ;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
