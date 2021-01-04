-- phpMyAdmin SQL Dump
-- version 4.9.2
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1:3306
-- Généré le :  Dim 03 jan. 2021 à 16:16
-- Version du serveur :  10.4.10-MariaDB
-- Version de PHP :  7.4.0

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données :  `duelquizz`
--

-- --------------------------------------------------------

--
-- Structure de la table `admins`
--

DROP TABLE IF EXISTS `admins`;
CREATE TABLE IF NOT EXISTS `admins` (
  `id` int(5) NOT NULL AUTO_INCREMENT,
  `firstName` varchar(100) NOT NULL,
  `lastName` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `registerAt` date NOT NULL,
  `password` varchar(1000) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=41 DEFAULT CHARSET=latin1;

--
-- Déchargement des données de la table `admins`
--

INSERT INTO `admins` (`id`, `firstName`, `lastName`, `email`, `registerAt`, `password`) VALUES
(40, 'Michel', 'FRANCOIS', 'mat@a.fr', '2020-11-27', '$2y$10$cOPXxi9CIhBYZUnA7lT/Z.leot2ks.Ra9r.JlEro4r3dLwW61Rlyy');

-- --------------------------------------------------------

--
-- Structure de la table `possibleanswers`
--

DROP TABLE IF EXISTS `possibleanswers`;
CREATE TABLE IF NOT EXISTS `possibleanswers` (
  `id` int(5) NOT NULL AUTO_INCREMENT,
  `question` int(5) NOT NULL,
  `label` varchar(100) NOT NULL,
  `correct` tinyint(1) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `question` (`question`)
) ENGINE=InnoDB AUTO_INCREMENT=126 DEFAULT CHARSET=latin1;

--
-- Déchargement des données de la table `possibleanswers`
--

INSERT INTO `possibleanswers` (`id`, `question`, `label`, `correct`) VALUES
(110, 1, 'Rep11', 0),
(111, 1, 'Rep12', 0),
(112, 1, 'Rep13', 1),
(113, 1, 'Rep14', 0),
(114, 2, 'Rep21', 0),
(115, 2, 'Rep22', 1),
(116, 2, 'Rep23', 0),
(117, 2, 'Rep24', 0),
(118, 3, 'Rep31', 0),
(119, 3, 'Rep32', 0),
(120, 3, 'Rep33', 1),
(121, 3, 'Rep34', 0),
(122, 4, 'Rep41', 0),
(123, 4, 'Rep42', 0),
(124, 4, 'Rep43', 1),
(125, 4, 'Rep44', 0);

-- --------------------------------------------------------

--
-- Structure de la table `questions`
--

DROP TABLE IF EXISTS `questions`;
CREATE TABLE IF NOT EXISTS `questions` (
  `id` int(5) NOT NULL AUTO_INCREMENT,
  `label` varchar(100) NOT NULL,
  `theme` int(5) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `theme` (`theme`)
) ENGINE=InnoDB AUTO_INCREMENT=395 DEFAULT CHARSET=latin1;

--
-- Déchargement des données de la table `questions`
--

INSERT INTO `questions` (`id`, `label`, `theme`) VALUES
(1, 'Question1', 0),
(2, 'Question2', 0),
(3, 'Question3', 0),
(4, 'Question4', 0);

-- --------------------------------------------------------

--
-- Structure de la table `question_quizz`
--

DROP TABLE IF EXISTS `question_quizz`;
CREATE TABLE IF NOT EXISTS `question_quizz` (
  `question` int(5) NOT NULL,
  `quizz` int(5) NOT NULL,
  `user1` int(5) DEFAULT NULL,
  `user2` int(5) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Déchargement des données de la table `question_quizz`
--

INSERT INTO `question_quizz` (`question`, `quizz`, `user1`, `user2`) VALUES
(1, 104, 1, 1),
(2, 104, NULL, NULL),
(3, 104, NULL, NULL),
(4, 104, NULL, NULL),
(1, 105, NULL, NULL),
(2, 105, NULL, NULL),
(3, 105, NULL, NULL),
(4, 105, NULL, NULL);

-- --------------------------------------------------------

--
-- Structure de la table `quizzes`
--

DROP TABLE IF EXISTS `quizzes`;
CREATE TABLE IF NOT EXISTS `quizzes` (
  `id` int(5) NOT NULL AUTO_INCREMENT,
  `mode` int(5) NOT NULL,
  `user1` int(5) NOT NULL,
  `user2` int(5) DEFAULT NULL,
  `startAt` date NOT NULL,
  `winner` int(5) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=106 DEFAULT CHARSET=latin1;

--
-- Déchargement des données de la table `quizzes`
--

INSERT INTO `quizzes` (`id`, `mode`, `user1`, `user2`, `startAt`, `winner`) VALUES
(104, 0, 0, NULL, '2021-01-03', NULL),
(105, 0, 0, 1, '2021-01-03', NULL);

-- --------------------------------------------------------

--
-- Structure de la table `themes`
--

DROP TABLE IF EXISTS `themes`;
CREATE TABLE IF NOT EXISTS `themes` (
  `id` int(5) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=latin1;

--
-- Déchargement des données de la table `themes`
--

INSERT INTO `themes` (`id`, `name`) VALUES
(0, 'ThemeTest');

-- --------------------------------------------------------

--
-- Structure de la table `users`
--

DROP TABLE IF EXISTS `users`;
CREATE TABLE IF NOT EXISTS `users` (
  `id` int(5) NOT NULL AUTO_INCREMENT,
  `firstName` varchar(100) NOT NULL,
  `lastName` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `registerAt` date NOT NULL,
  `password` varchar(1000) NOT NULL,
  `points` int(3) NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=40 DEFAULT CHARSET=latin1;

--
-- Déchargement des données de la table `users`
--

INSERT INTO `users` (`id`, `firstName`, `lastName`, `email`, `registerAt`, `password`, `points`) VALUES
(1, 'Miguelo', 'Bernardo', 'a@a.fr', '2020-11-21', '$2y$10$3xlliHHbIfFU/0lyPpLvgeVtcWfQUtsA6NQksSFXVuBE0ynOg1B26', 1),
(37, 'Matthieu', 'FRANCOIS', 'matth.fra@gmail.com', '2021-01-03', '$2y$10$ADbj3/LVht02fw4IajtLdeQz51yujKhNVfgSUFmJW/Z0S/xTr3yr6', 0),
(38, 'Matthieu', 'FRANCOIS', 'a.fra@gmail.com', '2021-01-03', '$2y$10$V7Yn7JtX3Gd9IJ6k1/YGjOxKj/RFzTZXDzWyk5yBIMCVlYOJyuBRq', 0),
(39, 'Matthieu', 'FRANCOIS', 'francoislucas57@gmail.com', '2021-01-03', '$2y$10$8YDvFOfmyo4lHPj5RRIcIuYRMJkoz043iMIc5Dh9TAVbeFPP0HoPq', 0);

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `possibleanswers`
--
ALTER TABLE `possibleanswers`
  ADD CONSTRAINT `possibleanswers_ibfk_1` FOREIGN KEY (`question`) REFERENCES `questions` (`id`);

--
-- Contraintes pour la table `questions`
--
ALTER TABLE `questions`
  ADD CONSTRAINT `questions_ibfk_1` FOREIGN KEY (`theme`) REFERENCES `themes` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
