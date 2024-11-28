-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Erstellungszeit: 03. Mai 2024 um 12:41
-- Server-Version: 10.4.32-MariaDB
-- PHP-Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Datenbank: `movieAPI`
--

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `favmovies`
--

CREATE TABLE `favmovies` (
  `mov_id` int(11) NOT NULL,
  `title` varchar(50) NOT NULL,
  `imdbid` varchar(20) NOT NULL,
  `genre` varchar(30) NOT NULL,
  `img_url` varchar(200) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;



-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `user`
--

CREATE TABLE `user` (
  `user_id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `pw` varchar(128) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;



-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `user_favmovies`
--

CREATE TABLE `user_favmovies` (
  `ID` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `mov_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Daten für Tabelle `user_favmovies`
--

INSERT INTO `user_favmovies` (`ID`, `user_id`, `mov_id`) VALUES
(7, 21, 703);

--
-- Indizes der exportierten Tabellen
--

--
-- Indizes für die Tabelle `favmovies`
--
ALTER TABLE `favmovies`
  ADD PRIMARY KEY (`mov_id`);

--
-- Indizes für die Tabelle `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`user_id`);

--
-- Indizes für die Tabelle `user_favmovies`
--
ALTER TABLE `user_favmovies`
  ADD PRIMARY KEY (`ID`),
  ADD UNIQUE KEY `mov_id` (`mov_id`) USING BTREE,
  ADD KEY `user_id` (`user_id`) USING BTREE;

--
-- AUTO_INCREMENT für exportierte Tabellen
--

--
-- AUTO_INCREMENT für Tabelle `favmovies`
--
ALTER TABLE `favmovies`
  MODIFY `mov_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=708;

--
-- AUTO_INCREMENT für Tabelle `user`
--
ALTER TABLE `user`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=35;

--
-- AUTO_INCREMENT für Tabelle `user_favmovies`
--
ALTER TABLE `user_favmovies`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- Constraints der exportierten Tabellen
--

--
-- Constraints der Tabelle `user_favmovies`
--
ALTER TABLE `user_favmovies`
  ADD CONSTRAINT `mov_id` FOREIGN KEY (`mov_id`) REFERENCES `favmovies` (`mov_id`),
  ADD CONSTRAINT `user_id` FOREIGN KEY (`user_id`) REFERENCES `user` (`user_id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
