-- phpMyAdmin SQL Dump
-- version 4.0.2
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Erstellungszeit: 01. Jan 2014 um 10:10
-- Server Version: 5.5.31-0+wheezy1-log
-- PHP-Version: 5.4.4-14+deb7u5

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Datenbank: `xataface_DMS`
--

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `typenDefinition`
--

CREATE TABLE IF NOT EXISTS `typenDefinition` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `reihenfolge` int(3) DEFAULT NULL,
  `name` varchar(150) DEFAULT NULL,
  `typ` varchar(100) DEFAULT NULL,
  `spaltenbreite` varchar(100) NOT NULL DEFAULT '6,12',
  `beschreibung` varchar(100) DEFAULT NULL,
  `eingabeFormular` tinyint(1) DEFAULT NULL,
  `suchwert` varchar(200) NOT NULL,
  `eingabewert` varchar(200) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `id` (`id`),
  UNIQUE KEY `name` (`name`),
  KEY `reihenfolge` (`reihenfolge`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=21 ;

--
-- Daten für Tabelle `typenDefinition`
--

INSERT INTO `typenDefinition` (`id`, `reihenfolge`, `name`, `typ`, `spaltenbreite`, `beschreibung`, `eingabeFormular`, `suchwert`, `eingabewert`) VALUES
(1, 5, 'id', 'zahl', '2,3', 'ID', 0, '%', '269'),
(5, 99, 'editStatus', 'einstellung', '', 'Einstellungsparameter', NULL, '0', ''),
(6, 99, 'startPage', 'einstellung', '', 'Startseite', NULL, '219', ''),
(8, 99, 'maxEintraege', 'einstellung', '', 'Maximale Anzahl der Einträge pro Seite', NULL, '10', ''),
(9, 99, 'sortierung', 'einstellung', '', 'Sortierung der Ergebnisliste', NULL, 'Speicherdatum', ''),
(10, 99, 'sortierfolge', 'einstellung', '', 'Sortierung, aufsteigend ist 1, absteigend 0', NULL, '0', ''),
(12, 99, 'fileupload', 'einstellung', '', 'Soll ein file upgeloaded werden?', NULL, '0', '0'),
(13, 7, 'Speicherdatum', 'datum', '6,12', 'Speicherdatum', 0, '%', '05.12.2013'),
(14, 99, 'datumFormat', 'einstellung', '', 'Format des Datums [d -> Tag] [m -> Monat] [Y -> Jahr] [H -> Stunden] [i -> minuten] [s -> Sekunden]', 0, 'd.m.Y', '');

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
