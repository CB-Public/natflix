-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Erstellungszeit: 27. Jan 2025 um 23:42
-- Server-Version: 10.4.32-MariaDB
-- PHP-Version: 8.0.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Datenbank: `movieapi`
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
  `img_url` varchar(200) NOT NULL,
  `plot` varchar(256) NOT NULL,
  `year` varchar(4) NOT NULL,
  `rating` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Daten für Tabelle `favmovies`
--

INSERT INTO `favmovies` (`mov_id`, `title`, `imdbid`, `genre`, `img_url`, `plot`, `year`, `rating`) VALUES
(709, 'The Office', 'tt0386676', 'Comedy', 'https://m.media-amazon.com/images/M/MV5BZjQwYzBlYzUtZjhhOS00ZDQ0LWE0NzAtYTk4MjgzZTNkZWEzXkEyXkFqcGc@._V1_SX300.jpg', 'A mockumentary on a group of typical office workers, where the workday consists of ego clashes, inappropriate behavior, tedium and romance.', '2005', '9.0'),
(711, 'The Office', 'tt0290978', 'Comedy, Drama', 'https://m.media-amazon.com/images/M/MV5BNTk4MjNjODctNDgyZC00NDhkLWE1OGQtMjA3M2FlMDVjMjkzXkEyXkFqcGc@._V1_SX300.jpg', 'The story of an office that faces closure when the company decides to downsize its branches. A documentary film crew follow staff and the manager David Brent as they continue their daily lives.', '2001', '8.5');

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `user`
--

CREATE TABLE `user` (
  `user_id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `pw` varchar(128) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Daten für Tabelle `user`
--

INSERT INTO `user` (`user_id`, `username`, `pw`) VALUES
(36, 'Testnutzer', '15edc0ca513b5fcd6f69edbdfc03371f90b7097dadc92e60d96b60421fbb3dcb');

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
(9, 36, 709),
(11, 36, 711);

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
  MODIFY `mov_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=712;

--
-- AUTO_INCREMENT für Tabelle `user`
--
ALTER TABLE `user`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=37;

--
-- AUTO_INCREMENT für Tabelle `user_favmovies`
--
ALTER TABLE `user_favmovies`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
