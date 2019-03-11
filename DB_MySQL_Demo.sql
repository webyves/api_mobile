-- phpMyAdmin SQL Dump
-- version 4.7.0
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1
-- Généré le :  lun. 11 mars 2019 à 18:05
-- Version du serveur :  5.7.17
-- Version de PHP :  7.1.3

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données :  `api_mobile`
--
CREATE DATABASE IF NOT EXISTS `api_mobile` DEFAULT CHARACTER SET latin1 COLLATE latin1_swedish_ci;
USE `api_mobile`;

-- --------------------------------------------------------

--
-- Structure de la table `article`
--

CREATE TABLE `article` (
  `id` int(11) NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` longtext COLLATE utf8mb4_unicode_ci,
  `price` double NOT NULL,
  `stock` int(11) NOT NULL,
  `model` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `brand` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `updated_date` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `article`
--

INSERT INTO `article` (`id`, `name`, `description`, `price`, `stock`, `model`, `brand`, `updated_date`) VALUES
(1, 'Galaxy S10 128Go Noir', 'Description du S10 de chez Samsung\r\n128Go\r\nCouleur Noir ', 199.9, 10, 'S10-128-Noir', 'Samsung', '2019-03-11 00:00:00'),
(2, 'Galaxy S9 64Go Bleu', 'Description du S9 de chez Samsung\r\n64Go\r\nCouleur Bleu', 49.9, 25, 'S9-64-Bleu', 'Samsung', '2019-03-11 00:00:00'),
(3, 'Iphone X', 'Description du Iphone X', 399.9, 5, 'iphone-x', 'Apple', '2019-03-11 00:00:00'),
(4, 'Iphone XS Max', 'Description du Iphone XS MAX', 499.9, 2, 'iphone-xs-max', 'Apple', '2019-03-11 00:00:00'),
(5, 'Mate 20 Pro', 'Description du Huawei Mate 20 Pro\r\naucune idée\r\ntrop chinois', 99.9, 12, 'mate-20-pro', 'Huawei', '2019-03-11 00:00:00'),
(6, 'Xperia 10', 'Description du sony Xperia 10\r\nOui sony existe encore sur le marché du mobile', 69.9, 7, 'xperia-10', 'Sony', '2019-03-11 00:00:00'),
(7, 'Mi Mix 3', 'Description du Xiaomi mi mix 3\r\nca fait tres robot du cuisine', 49.9, 3, 'Mi-Mix-3', 'Xiaomi', '2019-03-11 00:00:00'),
(8, 'OnePlus 6T', 'Description du OnePlus 6T\r\nde chez OnePlus\r\nModele 6T\r\nexiste en 2 coloris', 29.9, 4, 'oneplus-6T', 'OnePlus', '2019-03-11 00:00:00'),
(9, 'Nokia 7.1', 'on dirait le nom pour un kit de son\r\nca peu pas etre un telephone\r\nils sont plus a la page chez nokia', 10, 1, 'nokia-7.1', 'Nokia', '2019-03-11 00:00:00'),
(10, 'Honor 8X', 'Description du Honor 8X\r\nun telephone qui vaut la peine\r\nsuffit de le regarder', 30, 3, 'honor-8X', 'Honor', '2019-03-11 00:00:00'),
(11, 'Wiko Sunny 3', 'Le telephone en vogue chez les ados', 1, 59, 'Sunny-3', 'Wiko', '2019-03-11 00:00:00'),
(12, 'Honor View 20', 'Le haut de gamme de chez Honor', 595, 0, 'honor-view-20', 'Honor', '2019-03-11 00:00:00');

-- --------------------------------------------------------

--
-- Structure de la table `migration_versions`
--

CREATE TABLE `migration_versions` (
  `version` varchar(14) COLLATE utf8mb4_unicode_ci NOT NULL,
  `executed_at` datetime NOT NULL COMMENT '(DC2Type:datetime_immutable)'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `migration_versions`
--

INSERT INTO `migration_versions` (`version`, `executed_at`) VALUES
('20190212134233', '2019-02-12 13:42:56'),
('20190213131033', '2019-02-13 13:10:45'),
('20190213131412', '2019-02-13 13:14:24'),
('20190218143358', '2019-02-18 14:34:18'),
('20190222131858', '2019-02-22 13:19:22'),
('20190222132519', '2019-02-22 13:25:26'),
('20190226173457', '2019-02-26 17:37:09'),
('20190306113132', '2019-03-06 11:31:55');

-- --------------------------------------------------------

--
-- Structure de la table `user`
--

CREATE TABLE `user` (
  `id` int(11) NOT NULL,
  `fb_id` varchar(180) COLLATE utf8mb4_unicode_ci NOT NULL,
  `roles` json NOT NULL,
  `fb_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `fb_token` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `adress` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `phone` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `user`
--

INSERT INTO `user` (`id`, `fb_id`, `roles`, `fb_name`, `fb_token`, `email`, `adress`, `phone`) VALUES
(1, '123456', '[]', 'Utilisateur de test', 'FakeTokenFb123456', 'email@test.com', 'adresse de test', '0102030405'),
(2, '789012', '[]', 'Utilisateur de test 2', 'FakeTokenFb789012', 'email2@test.com', 'adresse de test 2', '0607080900');

-- --------------------------------------------------------

--
-- Structure de la table `user_client`
--

CREATE TABLE `user_client` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `address` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `zip_code` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `city` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `phone` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `birth_date` date DEFAULT NULL,
  `created_date` datetime NOT NULL,
  `updated_date` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `user_client`
--

INSERT INTO `user_client` (`id`, `user_id`, `name`, `email`, `address`, `zip_code`, `city`, `phone`, `birth_date`, `created_date`, `updated_date`) VALUES
(1, 1, 'fake client 1', 'fake1@email.com', 'fake1', '00001', 'FakeCity1', NULL, NULL, '2019-03-11 00:00:00', NULL),
(2, 1, 'fake client 2', 'fake2@email.com', 'fake2', '00002', 'FakeCity2', NULL, NULL, '2019-03-11 00:00:00', NULL),
(3, 2, 'fake client 3', 'fake3@email.com', 'fake3', '00003', 'FakeCity3', NULL, NULL, '2019-03-11 00:00:00', NULL),
(4, 2, 'fake client 4', 'fake4@email.com', 'fake4', '00004', 'FakeCity4', NULL, NULL, '2019-03-11 00:00:00', NULL),
(5, 1, 'fake client 5', 'fake5@email.com', 'fake5', '00005', 'FakeCity5', NULL, NULL, '2019-03-11 00:00:00', NULL),
(6, 1, 'fake client 6', 'fake6@email.com', 'fake6', '00006', 'FakeCity6', NULL, NULL, '2019-03-11 00:00:00', NULL),
(7, 1, 'fake client 7', 'fake7@email.com', 'fake7', '00007', 'FakeCity7', NULL, NULL, '2019-03-11 00:00:00', NULL),
(8, 1, 'fake client 8', 'fake8@email.com', 'fake8', '00008', 'FakeCity8', NULL, NULL, '2019-03-11 00:00:00', NULL),
(9, 1, 'fake client 9', 'fake9@email.com', 'fake9', '00009', 'FakeCity9', NULL, NULL, '2019-03-11 00:00:00', NULL);

--
-- Index pour les tables déchargées
--

--
-- Index pour la table `article`
--
ALTER TABLE `article`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `migration_versions`
--
ALTER TABLE `migration_versions`
  ADD PRIMARY KEY (`version`);

--
-- Index pour la table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `UNIQ_8D93D649D87F565A` (`fb_id`),
  ADD UNIQUE KEY `UNIQ_8D93D649B42C79D` (`fb_token`);

--
-- Index pour la table `user_client`
--
ALTER TABLE `user_client`
  ADD PRIMARY KEY (`id`),
  ADD KEY `IDX_A2161F68A76ED395` (`user_id`);

--
-- AUTO_INCREMENT pour les tables déchargées
--

--
-- AUTO_INCREMENT pour la table `article`
--
ALTER TABLE `article`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;
--
-- AUTO_INCREMENT pour la table `user`
--
ALTER TABLE `user`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT pour la table `user_client`
--
ALTER TABLE `user_client`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;
--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `user_client`
--
ALTER TABLE `user_client`
  ADD CONSTRAINT `FK_A2161F68A76ED395` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
