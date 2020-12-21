-- phpMyAdmin SQL Dump
-- version 4.9.2
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1:3306
-- Généré le :  ven. 04 déc. 2020 à 16:09
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
(40, 'Matthieu', 'FRANCOIS', 'mat@a.fr', '2020-11-27', '$2y$10$7/dAzk6T4PwjG4U0y55gVObc4FWMTj7LYi9IVVS0gkmEfhO8aJbaW');

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
) ENGINE=InnoDB AUTO_INCREMENT=110 DEFAULT CHARSET=latin1;

--
-- Déchargement des données de la table `possibleanswers`
--

INSERT INTO `possibleanswers` (`id`, `question`, `label`, `correct`) VALUES
(98, 37, 'Question1', 0),
(99, 37, 'Question8', 0),
(100, 37, 'Question3', 1),
(101, 37, 'Question4', 0),
(102, 39, 'a', 0),
(103, 39, 'b', 0),
(104, 39, 'c', 1),
(105, 39, 'd', 0),
(106, 40, 's', 1),
(107, 40, 'a', 0),
(108, 40, 's', 0),
(109, 40, 'd', 0);

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
) ENGINE=InnoDB AUTO_INCREMENT=41 DEFAULT CHARSET=latin1;

--
-- Déchargement des données de la table `questions`
--

INSERT INTO `questions` (`id`, `label`, `theme`) VALUES
(37, 'Question', 0),
(39, 'Question', 0),
(40, 'dfdukjqsfq', 0);

-- --------------------------------------------------------

--
-- Structure de la table `quizzes`
--

DROP TABLE IF EXISTS `quizzes`;
CREATE TABLE IF NOT EXISTS `quizzes` (
  `id` int(5) NOT NULL AUTO_INCREMENT,
  `mode` int(5) NOT NULL,
  `questions` int(5) NOT NULL,
  `user1` int(5) NOT NULL,
  `user2` int(5) DEFAULT NULL,
  `startAt` datetime(6) NOT NULL,
  `winner` int(5) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;

--
-- Déchargement des données de la table `quizzes`
--

INSERT INTO `quizzes` (`id`, `mode`, `questions`, `user1`, `user2`, `startAt`, `winner`) VALUES
(1, 0, 1, 32, NULL, '2020-11-29 00:00:00.000000', NULL);

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
(0, 'Histoire'),
(3, 'Oui'),
(4, 'Beaucoup');

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
  `points` int(3) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=33 DEFAULT CHARSET=latin1;

--
-- Déchargement des données de la table `users`
--

INSERT INTO `users` (`id`, `firstName`, `lastName`, `email`, `registerAt`, `password`, `points`) VALUES
(32, 'Miguelo', 'Bernardo', 'a@a.fr', '2020-11-21', '$2y$10$3xlliHHbIfFU/0lyPpLvgeVtcWfQUtsA6NQksSFXVuBE0ynOg1B26', 1);

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
