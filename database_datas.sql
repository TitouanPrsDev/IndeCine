-- phpMyAdmin SQL Dump
-- version 5.1.1
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1:3306
-- Généré le : dim. 02 jan. 2022 à 18:34
-- Version du serveur : 5.7.31
-- Version de PHP : 8.0.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `indecine`
--

-- --------------------------------------------------------

--
-- Structure de la table `directors`
--

DROP TABLE IF EXISTS `directors`;
CREATE TABLE IF NOT EXISTS `directors` (
  `directorId` int(11) NOT NULL AUTO_INCREMENT,
  `directorName` varchar(255) NOT NULL,
  `directorPicture` varchar(255) NOT NULL,
  PRIMARY KEY (`directorId`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;

--
-- Déchargement des données de la table `directors`
--

INSERT INTO `directors` (`directorId`, `directorName`, `directorPicture`) VALUES
(1, 'Jon Watts', 'https://fr.web.img1.acsta.net/c_310_420/pictures/17/06/29/14/09/430968.jpg'),
(2, 'Cary Joji Fukunaga', 'https://fr.web.img1.acsta.net/c_310_420/pictures/15/10/20/17/28/296972.jpg');

-- --------------------------------------------------------

--
-- Structure de la table `movies`
--

DROP TABLE IF EXISTS `movies`;
CREATE TABLE IF NOT EXISTS `movies` (
  `movieId` int(11) NOT NULL AUTO_INCREMENT,
  `movieTitle` varchar(255) NOT NULL,
  `moviePoster` varchar(255) NOT NULL,
  `movieReleaseDate` date NOT NULL,
  `director_id` int(11) NOT NULL,
  `movieSynopsis` text NOT NULL,
  `movieDuration` smallint(6) NOT NULL,
  `movieFirstScreeningDate` date NOT NULL,
  `movieLastScreeningDate` date NOT NULL,
  `movieClassification` enum('non-classe','tous-publics','moins-12','moins-16','moins-18','moins-18-x') NOT NULL DEFAULT 'non-classe',
  PRIMARY KEY (`movieId`),
  KEY `director_id` (`director_id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;

--
-- Déchargement des données de la table `movies`
--

INSERT INTO `movies` (`movieId`, `movieTitle`, `moviePoster`, `movieReleaseDate`, `director_id`, `movieSynopsis`, `movieDuration`, `movieFirstScreeningDate`, `movieLastScreeningDate`, `movieClassification`) VALUES
(1, 'Spider-Man: No Way Home', 'https://fr.web.img1.acsta.net/c_310_420/pictures/21/11/16/10/01/4860598.jpg', '2021-12-15', 1, 'Pour la première fois dans son histoire cinématographique, Spider-Man, le héros sympa du quartier est démasqué et ne peut désormais plus séparer sa vie normale de ses lourdes responsabilités de super-héros. Quand il demande de l\'aide à Doctor Strange, les enjeux deviennent encore plus dangereux, le forçant à découvrir ce qu\'être Spider-Man signifie véritablement. ', 148, '2021-12-15', '2022-01-15', 'tous-publics'),
(2, 'James Bond 007: Mourir peut attendre', 'https://fr.web.img1.acsta.net/c_310_420/pictures/21/09/09/11/06/5284084.jpg', '2021-10-06', 2, 'Dans MOURIR PEUT ATTENDRE, Bond a quitté les services secrets et coule des jours heureux en Jamaïque. Mais sa tranquillité est de courte durée car son vieil ami Felix Leiter de la CIA débarque pour solliciter son aide : il s\'agit de sauver un scientifique qui vient d\'être kidnappé. Mais la mission se révèle bien plus dangereuse que prévu et Bond se retrouve aux trousses d\'un mystérieux ennemi détenant de redoutables armes technologiques…', 163, '2021-10-06', '2021-11-06', 'tous-publics');

-- --------------------------------------------------------

--
-- Structure de la table `moviesRooms`
--

DROP TABLE IF EXISTS `moviesRooms`;
CREATE TABLE IF NOT EXISTS `moviesRooms` (
  `movieRoomId` int(11) NOT NULL AUTO_INCREMENT,
  `movieRoomName` varchar(255) NOT NULL,
  `movieRoomDescription` text,
  PRIMARY KEY (`movieRoomId`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;

--
-- Déchargement des données de la table `moviesRooms`
--

INSERT INTO `moviesRooms` (`movieRoomId`, `movieRoomName`, `movieRoomDescription`) VALUES
(1, 'Salle 1', 'Salle basique'),
(2, 'Salle 2', 'Salle premium 3D');

-- --------------------------------------------------------

--
-- Structure de la table `pricings`
--

DROP TABLE IF EXISTS `pricings`;
CREATE TABLE IF NOT EXISTS `pricings` (
  `pricingId` int(11) NOT NULL AUTO_INCREMENT,
  `pricingName` varchar(255) NOT NULL,
  `pricingDescription` varchar(255) DEFAULT NULL,
  `pricing` double NOT NULL,
  PRIMARY KEY (`pricingId`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;

--
-- Déchargement des données de la table `pricings`
--

INSERT INTO `pricings` (`pricingId`, `pricingName`, `pricingDescription`, `pricing`) VALUES
(1, 'Plein tarif', 'Plein tarif', 7),
(2, 'Tarif réduit', 'Carte Avantage Senior SNCF, demandeur d\'emploi, carte famille nombreuse et handicapé.\r\nJustificatif obligatoire.', 5.5),
(3, 'Moins de 16 ans', 'Moins de 16 ans.\r\nJustificatif obligatoire.', 4.5);

-- --------------------------------------------------------

--
-- Structure de la table `reservations`
--

DROP TABLE IF EXISTS `reservations`;
CREATE TABLE IF NOT EXISTS `reservations` (
  `reservationId` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `screening_id` int(11) NOT NULL,
  `seat_id` int(11) NOT NULL,
  `pricing_id` int(11) NOT NULL,
  `reservationTimestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`reservationId`),
  KEY `user_id` (`user_id`),
  KEY `screening_id` (`screening_id`),
  KEY `seat_id` (`seat_id`),
  KEY `pricing_id` (`pricing_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Structure de la table `screenings`
--

DROP TABLE IF EXISTS `screenings`;
CREATE TABLE IF NOT EXISTS `screenings` (
  `screeningId` int(11) NOT NULL AUTO_INCREMENT,
  `movie_id` int(11) NOT NULL,
  `movieRoom_id` int(11) NOT NULL,
  `screeningDate` date NOT NULL,
  `screeningTime` time NOT NULL,
  PRIMARY KEY (`screeningId`),
  KEY `movie_id` (`movie_id`),
  KEY `movieRoom_id` (`movieRoom_id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8;

--
-- Déchargement des données de la table `screenings`
--

INSERT INTO `screenings` (`screeningId`, `movie_id`, `movieRoom_id`, `screeningDate`, `screeningTime`) VALUES
(1, 1, 1, '2022-01-05', '14:00:00'),
(2, 1, 1, '2022-01-05', '17:00:00'),
(3, 1, 2, '2022-01-05', '20:00:00'),
(4, 1, 2, '2022-01-06', '21:00:00'),
(5, 1, 2, '2022-01-07', '18:00:00'),
(6, 1, 2, '2022-01-07', '21:00:00');

-- --------------------------------------------------------

--
-- Structure de la table `seats`
--

DROP TABLE IF EXISTS `seats`;
CREATE TABLE IF NOT EXISTS `seats` (
  `seatId` int(11) NOT NULL AUTO_INCREMENT,
  `movieRoom_id` int(11) NOT NULL,
  `seatXPosition` int(11) NOT NULL,
  `seatYPosition` int(11) NOT NULL,
  PRIMARY KEY (`seatId`),
  KEY `movieRoom_id` (`movieRoom_id`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8;

--
-- Déchargement des données de la table `seats`
--

INSERT INTO `seats` (`seatId`, `movieRoom_id`, `seatXPosition`, `seatYPosition`) VALUES
(1, 2, 1, 1),
(2, 2, 1, 2),
(3, 2, 1, 3),
(4, 2, 2, 1),
(5, 2, 2, 2),
(6, 2, 2, 3),
(7, 2, 3, 1),
(8, 2, 3, 2),
(9, 2, 3, 3);

-- --------------------------------------------------------

--
-- Structure de la table `subscriptions`
--

DROP TABLE IF EXISTS `subscriptions`;
CREATE TABLE IF NOT EXISTS `subscriptions` (
  `subscriptionId` int(11) NOT NULL AUTO_INCREMENT,
  `subscriptionName` varchar(255) NOT NULL,
  `subscriptionDescription` text,
  `subscriptionPricing` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`subscriptionId`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;

--
-- Déchargement des données de la table `subscriptions`
--

INSERT INTO `subscriptions` (`subscriptionId`, `subscriptionName`, `subscriptionDescription`, `subscriptionPricing`) VALUES
(1, 'Abonnement annuel', 'Abonnement annuel', 150),
(2, 'Abonnement 10 places', 'Abonnement 10 places', 60),
(3, 'Abonnement 20 places', 'Abonnement 20 places', 110);

-- --------------------------------------------------------

--
-- Structure de la table `users`
--

DROP TABLE IF EXISTS `users`;
CREATE TABLE IF NOT EXISTS `users` (
  `userId` int(11) NOT NULL AUTO_INCREMENT,
  `userFirstName` varchar(30) NOT NULL,
  `userLastName` varchar(30) NOT NULL,
  `userEmail` varchar(255) NOT NULL,
  `userPhoneNumber` varchar(10) DEFAULT NULL,
  `userBirthDate` date DEFAULT NULL,
  `userPassword` varchar(60) NOT NULL,
  `userProfilePicture` varchar(255) DEFAULT NULL,
  `userRole` enum('admin','client') NOT NULL DEFAULT 'client',
  PRIMARY KEY (`userId`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

--
-- Déchargement des données de la table `users`
--

INSERT INTO `users` (`userId`, `userFirstName`, `userLastName`, `userEmail`, `userPhoneNumber`, `userBirthDate`, `userPassword`, `userProfilePicture`, `userRole`) VALUES
(1, 'Titouan', 'Paris', 'root@root.fr', NULL, NULL, '$2y$10$4K4Qlg7yNUzO2rUYXG46yuxmLeuuHxCJXlJiq1cJfOBXObMUpLiqG', NULL, 'admin');

-- --------------------------------------------------------

--
-- Structure de la table `users_subscriptions`
--

DROP TABLE IF EXISTS `users_subscriptions`;
CREATE TABLE IF NOT EXISTS `users_subscriptions` (
  `user_id` int(11) NOT NULL,
  `subscription_id` int(11) NOT NULL,
  `remainingPlaces` int(11) NOT NULL DEFAULT '0',
  `points` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`user_id`,`subscription_id`),
  KEY `subscription_id` (`subscription_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `movies`
--
ALTER TABLE `movies`
  ADD CONSTRAINT `movies_ibfk_1` FOREIGN KEY (`director_id`) REFERENCES `directors` (`directorId`);

--
-- Contraintes pour la table `screenings`
--
ALTER TABLE `screenings`
  ADD CONSTRAINT `screenings_ibfk_1` FOREIGN KEY (`movie_id`) REFERENCES `movies` (`movieId`),
  ADD CONSTRAINT `screenings_ibfk_2` FOREIGN KEY (`movieRoom_id`) REFERENCES `moviesRooms` (`movieRoomId`);

--
-- Contraintes pour la table `seats`
--
ALTER TABLE `seats`
  ADD CONSTRAINT `seats_ibfk_1` FOREIGN KEY (`movieRoom_id`) REFERENCES `moviesRooms` (`movieRoomId`);

--
-- Contraintes pour la table `users_subscriptions`
--
ALTER TABLE `users_subscriptions`
  ADD CONSTRAINT `users_subscriptions_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`userId`),
  ADD CONSTRAINT `users_subscriptions_ibfk_2` FOREIGN KEY (`subscription_id`) REFERENCES `subscriptions` (`subscriptionId`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
