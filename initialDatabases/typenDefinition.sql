SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Tabellenstruktur für Tabelle `typenDefinition`
--

CREATE TABLE IF NOT EXISTS `typenDefinition` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `reihenfolge` int(3) DEFAULT NULL COMMENT 'Reihenfolge der anzzuzeigenden Spalten',
  `name` varchar(150) DEFAULT NULL COMMENT 'interner Name der Spalte',
  `typ` varchar(100) DEFAULT NULL COMMENT 'Typ der Spalte (zahl, auswahl, auswahlStruktur, dokurl, einstellung, previewPic, datum, text)',
  `spaltenbreiteShow` varchar(100) NOT NULL DEFAULT '6,12' COMMENT 'Spaltenbreite im Anzeigemodus',
  `spaltenbreiteEdit` varchar(100) NOT NULL COMMENT 'Spaltenbreite im Editiermodus',
  `spaltenbreiteSuchFormular` varchar(100) NOT NULL COMMENT 'Spaltenbreite im Suchformular',
  `spaltenbreiteNeuesDokumentFormular` varchar(100) NOT NULL COMMENT 'Spaltenbreite im Eingabeformular fuer neue Dokumente',
  `beschreibung` varchar(100) DEFAULT NULL COMMENT 'Anzuzeigender Name der Spalte',
  `suchwert` varchar(200) NOT NULL COMMENT 'zuletzt gesuchter Wert. Dies Spalte wird nur Programmintern verwendet',
  `eingabewert` varchar(200) NOT NULL COMMENT 'zuletzt eingegebener Wert. Diese Spalte wird nur Programmintern verwendet',
  PRIMARY KEY (`id`),
  UNIQUE KEY `id` (`id`),
  UNIQUE KEY `name` (`name`),
  KEY `reihenfolge` (`reihenfolge`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=24 ;

--
-- Daten für Tabelle `typenDefinition`
--

INSERT INTO `typenDefinition` (`id`, `reihenfolge`, `name`, `typ`, `spaltenbreiteShow`, `spaltenbreiteEdit`, `spaltenbreiteSuchFormular`, `spaltenbreiteNeuesDokumentFormular`, `beschreibung`, `suchwert`, `eingabewert`) VALUES
(1, 99, 'editStatus', 'einstellung', '', '', '', '', 'Einstellungsparameter', '0', ''),
(2, 99, 'startPage', 'einstellung', '', '', '', '', 'Startseite', '0', ''),
(3, 99, 'maxEintraege', 'einstellung', '', '', '', '', 'Maximale Anzahl der Einträge pro Seite', '10', ''),
(4, 99, 'sortierung', 'einstellung', '', '', '', '', 'Sortierung der Ergebnisliste', 'id', ''),
(5, 99, 'sortierfolge', 'einstellung', '', '', '', '', 'Sortierung, aufsteigend ist 1, absteigend 0', '0', ''),
(6, 99, 'fileupload', 'einstellung', '', '', '', '', 'Soll ein file upgeloaded werden?', '0', '0'),
(7, 99, 'datumFormat', 'einstellung', '', '', '', '', 'Format des Datums [d -> Tag] [m -> Monat] [Y -> Jahr] [H -> Stunden] [i -> minuten] [s -> Sekunden]', 'd.m.y', ''),
(8, 99, 'tabellenNameKurz', 'einstellung', '', '', '', '', 'Name der Tabelle der angezeigt werden soll', 'DMS', ''),
(9, 99, 'tabellenNameLang', 'einstellung', '', '', '', '', 'langer Anzeigename der Tabelle', 'Dokumenten Managementsystem', '');

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
