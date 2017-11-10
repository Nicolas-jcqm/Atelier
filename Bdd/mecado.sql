-- phpMyAdmin SQL Dump
-- version 4.7.4
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1:3306
-- Généré le :  ven. 10 nov. 2017 à 14:19
-- Version du serveur :  5.7.19
-- Version de PHP :  5.6.31

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données :  `mecado`
--

-- --------------------------------------------------------

--
-- Structure de la table `activations`
--

DROP TABLE IF EXISTS `activations`;
CREATE TABLE IF NOT EXISTS `activations` (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `code` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `completed` tinyint(4) NOT NULL DEFAULT '0',
  `completed_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `activations_user_id_unique` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `booking`
--

DROP TABLE IF EXISTS `booking`;
CREATE TABLE IF NOT EXISTS `booking` (
  `id` varchar(100) NOT NULL,
  `reserverName` varchar(50) NOT NULL,
  `message` varchar(200) NOT NULL,
  `idItem` varchar(100) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_booking_idItem` (`idItem`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Structure de la table `comment`
--

DROP TABLE IF EXISTS `comment`;
CREATE TABLE IF NOT EXISTS `comment` (
  `id` varchar(100) NOT NULL,
  `senderName` varchar(50) NOT NULL,
  `content` varchar(200) NOT NULL,
  `idList` varchar(100) NOT NULL,
  `updated_at` datetime NOT NULL,
  `created_at` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_comment_idlist` (`idList`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Structure de la table `creator`
--

DROP TABLE IF EXISTS `creator`;
CREATE TABLE IF NOT EXISTS `creator` (
  `id` varchar(100) NOT NULL,
  `name` varchar(50) NOT NULL,
  `firstName` varchar(50) NOT NULL,
  `mail` varchar(60) NOT NULL,
  `password` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Déchargement des données de la table `creator`
--

INSERT INTO `creator` (`id`, `name`, `firstName`, `mail`, `password`) VALUES
('5a047c8e892ef', 'marquant', 'lucas', 'lucas@hotmail.fr', '$2y$12$fsH/.WUa.vO6j40rJWs62uulOJaJiyf4G.dXPnSSNpR.WAb3kfelq');

-- --------------------------------------------------------

--
-- Structure de la table `item`
--

DROP TABLE IF EXISTS `item`;
CREATE TABLE IF NOT EXISTS `item` (
  `id` varchar(100) NOT NULL,
  `title` varchar(50) NOT NULL,
  `description` varchar(200) NOT NULL,
  `price` float NOT NULL,
  `url` varchar(200) NOT NULL,
  `picture` varchar(50) NOT NULL,
  `idGroup` varchar(100) NOT NULL,
  `idList` varchar(100) NOT NULL,
  `nameGroup` varchar(100) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_item_idList` (`idList`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Déchargement des données de la table `item`
--

INSERT INTO `item` (`id`, `title`, `description`, `price`, `url`, `picture`, `idGroup`, `idList`, `nameGroup`) VALUES
('5a047cb59f203', 'Titre', 'desc', 56, 'https://youtu.be/L4KyhhSRoKs', '2017-11-09-16-05-09.jpg', '5a05ac7b3c4d9', '5a047c9f0ca0f', 'test'),
('5a047ccd6b4c6', 'test2', 'nvjfu', 90, 'http://sync.in/s', 'default.jpg', '5a05ac7b3c4d9', '5a047c9f0ca0f', 'test'),
('5a047ce8ee3b1', 'tresd', 'ghkgi', 66, 'https://youtu.be/xfpiacvzACI', '2017-11-09-16-06-00.jpg', '5a05ac7b3c4d9', '5a047c9f0ca0f', 'test'),
('5a05b3e36d2cd', 'Test', '', 14, '', 'default.jpg', '0', '5a047c9f0ca0f', '');

-- --------------------------------------------------------

--
-- Structure de la table `list`
--

DROP TABLE IF EXISTS `list`;
CREATE TABLE IF NOT EXISTS `list` (
  `id` varchar(100) NOT NULL,
  `title` varchar(50) NOT NULL,
  `description` varchar(200) NOT NULL,
  `validityDate` date NOT NULL,
  `token` varchar(50) DEFAULT NULL,
  `isRecipient` tinyint(1) NOT NULL,
  `idCreator` varchar(100) NOT NULL,
  `isValidate` tinyint(1) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_list_idcreator` (`idCreator`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Déchargement des données de la table `list`
--

INSERT INTO `list` (`id`, `title`, `description`, `validityDate`, `token`, `isRecipient`, `idCreator`, `isValidate`) VALUES
('5a047c9f0ca0f', 'test', 'test', '2017-11-30', '88ebbbbfb9a63201cd106a7e93dc84ce', 4, '5a047c8e892ef', NULL);

-- --------------------------------------------------------

--
-- Structure de la table `participant`
--

DROP TABLE IF EXISTS `participant`;
CREATE TABLE IF NOT EXISTS `participant` (
  `id` varchar(100) NOT NULL,
  `name` varchar(50) NOT NULL,
  `amountGiven` float NOT NULL,
  `idPot` varchar(100) NOT NULL,
  `messagePot` varchar(200) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_participant_idPot` (`idPot`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Structure de la table `persistences`
--

DROP TABLE IF EXISTS `persistences`;
CREATE TABLE IF NOT EXISTS `persistences` (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `code` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `persistences_code_unique` (`code`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `pot`
--

DROP TABLE IF EXISTS `pot`;
CREATE TABLE IF NOT EXISTS `pot` (
  `id` varchar(100) NOT NULL,
  `amount` float NOT NULL,
  `currentSum` float NOT NULL,
  `idItem` varchar(100) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_pot_idItem` (`idItem`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Structure de la table `reminders`
--

DROP TABLE IF EXISTS `reminders`;
CREATE TABLE IF NOT EXISTS `reminders` (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `code` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `completed` tinyint(4) NOT NULL DEFAULT '0',
  `completed_at` timestamp NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `roles`
--

DROP TABLE IF EXISTS `roles`;
CREATE TABLE IF NOT EXISTS `roles` (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `slug` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `permissions` text COLLATE utf8_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `roles_slug_unique` (`slug`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `role_users`
--

DROP TABLE IF EXISTS `role_users`;
CREATE TABLE IF NOT EXISTS `role_users` (
  `user_id` int(11) NOT NULL,
  `role_id` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`user_id`,`role_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `throttle`
--

DROP TABLE IF EXISTS `throttle`;
CREATE TABLE IF NOT EXISTS `throttle` (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_id` int(10) UNSIGNED NOT NULL,
  `type` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `ip` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `throttle_user_id_unique` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `item`
--
ALTER TABLE `item`
  ADD CONSTRAINT `fk_item_idList` FOREIGN KEY (`idList`) REFERENCES `list` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
