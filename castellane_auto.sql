-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Jun 11, 2026 at 05:03 PM
-- Server version: 10.4.28-MariaDB
-- PHP Version: 8.2.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `castellane_auto`
--

-- --------------------------------------------------------

--
-- Table structure for table `CLIENT`
--

DROP TABLE IF EXISTS `CLIENT`;
CREATE TABLE `CLIENT` (
  `id_client` int(11) NOT NULL,
  `nom_client` varchar(50) NOT NULL,
  `prenom_client` varchar(50) NOT NULL,
  `date_naissance` date NOT NULL,
  `adresse_client` varchar(200) DEFAULT NULL,
  `telephone` varchar(15) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `date_inscription` date NOT NULL,
  `date_prevue_code` date DEFAULT NULL,
  `date_prevue_permis` date DEFAULT NULL,
  `est_etudiant` tinyint(1) DEFAULT 0,
  `id_etablissement` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `CLIENT`
--

INSERT INTO `CLIENT` (`id_client`, `nom_client`, `prenom_client`, `date_naissance`, `adresse_client`, `telephone`, `email`, `date_inscription`, `date_prevue_code`, `date_prevue_permis`, `est_etudiant`, `id_etablissement`) VALUES
(1, 'Marin', 'Laure', '2004-03-15', '12 rue de France, 83100 Toulon', '0600000000', 'laure.marin@mail.com', '2025-01-10', '2025-03-01', '2025-06-01', 1, 1),
(2, 'Martin', 'Lucas', '2003-07-22', '5 avenue de France, 83100 Toulon', '0600000000', 'lucas.martin@mail.com', '2025-01-15', '2025-04-01', '2025-07-01', 1, 2),
(3, 'Linier', 'Manon', '2000-11-05', '8 bd de France, 83000 Toulon', '0600000000', 'manon.linier@mail.com', '2025-02-01', '2025-04-15', '2025-08-01', 0, NULL),
(4, 'Tres', 'Antoine', '2005-01-30', '22 rue de France, 83100 Toulon', '0600000000', 'antoine.tres@mail.com', '2025-02-10', '2025-05-01', '2025-09-01', 1, 1),
(5, 'Plain', 'Juliette', '1999-09-12', '15 place de la France, 83000 Toulon', '0600000000', 'juliette.plain@mail.com', '2025-03-01', '2025-05-15', '2025-10-01', 0, NULL),
(6, 'Test', 'Test', '2000-10-05', '23 boulevard de France', '0610101010', 'test.test@mail.fr', '2026-02-05', '2026-05-10', '2026-08-15', 1, 1);

-- --------------------------------------------------------

--
-- Table structure for table `ETABLISSEMENT`
--

DROP TABLE IF EXISTS `ETABLISSEMENT`;
CREATE TABLE `ETABLISSEMENT` (
  `id_etablissement` int(11) NOT NULL,
  `nom_etablissement` varchar(100) NOT NULL,
  `adresse_etablissement` varchar(200) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `ETABLISSEMENT`
--

INSERT INTO `ETABLISSEMENT` (`id_etablissement`, `nom_etablissement`, `adresse_etablissement`) VALUES
(1, 'Université de Toulon', '70 avenue de France, 83000 Toulon'),
(2, 'Lycée', '212 rue de France, 83000 Toulon');

-- --------------------------------------------------------

--
-- Table structure for table `FACTURATION`
--

DROP TABLE IF EXISTS `FACTURATION`;
CREATE TABLE `FACTURATION` (
  `id_facture` int(11) NOT NULL,
  `id_client` int(11) NOT NULL,
  `date_facture` date NOT NULL,
  `mode_facturation` enum('heure','forfait_pack','forfait_global') NOT NULL,
  `montant_total` decimal(8,2) NOT NULL,
  `est_payee` tinyint(1) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `FACTURATION`
--

INSERT INTO `FACTURATION` (`id_facture`, `id_client`, `date_facture`, `mode_facturation`, `montant_total`, `est_payee`) VALUES
(1, 1, '2025-01-10', 'forfait_pack', 790.00, 1),
(2, 2, '2025-01-15', 'forfait_global', 1190.00, 0),
(3, 3, '2025-02-01', 'heure', 180.00, 1),
(4, 4, '2025-02-10', 'forfait_pack', 790.00, 0),
(5, 5, '2025-03-01', 'heure', 135.00, 1);

-- --------------------------------------------------------

--
-- Table structure for table `KM_MENSUEL`
--

DROP TABLE IF EXISTS `KM_MENSUEL`;
CREATE TABLE `KM_MENSUEL` (
  `id_voiture` int(11) NOT NULL,
  `id_mois` int(11) NOT NULL,
  `km_debut_mois` int(11) NOT NULL,
  `km_fin_mois` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `KM_MENSUEL`
--

INSERT INTO `KM_MENSUEL` (`id_voiture`, `id_mois`, `km_debut_mois`, `km_fin_mois`) VALUES
(1, 1, 25000, 25340),
(1, 2, 25340, 25620),
(2, 1, 18000, 18305),
(2, 2, 18305, 18580),
(3, 1, 12000, 12110),
(3, 2, 12110, 12230),
(4, 1, 30000, 30108),
(4, 2, 30108, 30350);

-- --------------------------------------------------------

--
-- Table structure for table `LECON`
--

DROP TABLE IF EXISTS `LECON`;
CREATE TABLE `LECON` (
  `id_lecon` int(11) NOT NULL,
  `date_lecon` date NOT NULL,
  `heure_debut` time NOT NULL,
  `duree_minutes` int(11) NOT NULL CHECK (`duree_minutes` > 0),
  `km_parcourus` int(11) DEFAULT 0,
  `observation` text DEFAULT NULL,
  `id_client` int(11) NOT NULL,
  `id_moniteur` int(11) NOT NULL,
  `id_voiture` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `LECON`
--

INSERT INTO `LECON` (`id_lecon`, `date_lecon`, `heure_debut`, `duree_minutes`, `km_parcourus`, `observation`, `id_client`, `id_moniteur`, `id_voiture`) VALUES
(1, '2025-01-20', '09:00:00', 60, 25, 'Première leçon, prise en main du véhicule', 1, 1, 1),
(2, '2025-01-20', '09:00:00', 60, 20, 'Démarrage et arrêt en côte', 2, 2, 2),
(3, '2025-01-20', '10:30:00', 60, 30, 'Circulation en ville', 1, 2, 2),
(4, '2025-01-20', '14:00:00', 90, 40, 'Conduite sur route départementale', 3, 1, 1),
(5, '2025-01-21', '09:00:00', 60, 22, 'Créneaux et manœuvres', 4, 3, 3),
(6, '2025-01-21', '10:30:00', 60, 28, 'Insertion autoroute', 2, 1, 4),
(7, '2025-01-22', '09:00:00', 90, 45, 'Conduite en agglomération', 5, 2, 2),
(8, '2025-01-22', '14:00:00', 60, 20, 'Stationnement en bataille', 1, 3, 3),
(9, '2025-01-27', '09:00:00', 60, 25, 'Ronds-points et priorités', 3, 1, 1),
(10, '2025-01-27', '10:30:00', 60, 30, 'Conduite de nuit (simulée)', 4, 2, 4),
(11, '2025-01-28', '09:00:00', 90, 50, 'Parcours d\'examen', 2, 3, 2),
(12, '2025-01-28', '14:00:00', 60, 22, 'Perfectionnement créneaux', 5, 1, 3),
(13, '2025-02-03', '09:00:00', 60, 28, 'Conduite sous la pluie', 1, 2, 1),
(14, '2025-02-03', '10:30:00', 60, 25, 'Dépassements', 3, 3, 4),
(15, '2025-02-04', '09:00:00', 90, 55, 'Parcours d\'examen complet', 4, 1, 2),
(16, '2025-02-04', '14:00:00', 60, 20, 'Révision manœuvres', 2, 2, 3);

-- --------------------------------------------------------

--
-- Table structure for table `MODELE_VOITURE`
--

DROP TABLE IF EXISTS `MODELE_VOITURE`;
CREATE TABLE `MODELE_VOITURE` (
  `id_modele` int(11) NOT NULL,
  `marque` varchar(50) NOT NULL,
  `nom_modele` varchar(50) NOT NULL,
  `boite_vitesse` enum('manuelle','automatique') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `MODELE_VOITURE`
--

INSERT INTO `MODELE_VOITURE` (`id_modele`, `marque`, `nom_modele`, `boite_vitesse`) VALUES
(1, 'Renault', 'Clio V', 'manuelle'),
(2, 'Peugeot', '208', 'manuelle'),
(3, 'Peugeot', '308', 'automatique');

-- --------------------------------------------------------

--
-- Table structure for table `MOIS`
--

DROP TABLE IF EXISTS `MOIS`;
CREATE TABLE `MOIS` (
  `id_mois` int(11) NOT NULL,
  `annee` int(11) NOT NULL,
  `mois` int(11) NOT NULL CHECK (`mois` between 1 and 12)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `MOIS`
--

INSERT INTO `MOIS` (`id_mois`, `annee`, `mois`) VALUES
(1, 2025, 1),
(2, 2025, 2),
(3, 2025, 3),
(4, 2025, 4),
(5, 2025, 5),
(6, 2025, 6);

-- --------------------------------------------------------

--
-- Table structure for table `MONITEUR`
--

DROP TABLE IF EXISTS `MONITEUR`;
CREATE TABLE `MONITEUR` (
  `id_moniteur` int(11) NOT NULL,
  `nom_moniteur` varchar(50) NOT NULL,
  `prenom_moniteur` varchar(50) NOT NULL,
  `telephone` varchar(15) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `date_embauche` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `MONITEUR`
--

INSERT INTO `MONITEUR` (`id_moniteur`, `nom_moniteur`, `prenom_moniteur`, `telephone`, `email`, `date_embauche`) VALUES
(1, 'Lague', 'Jordan', '0600000000', 'j.laguecastellane-auto.fr', '2018-09-01'),
(2, 'Roux', 'Marion', '0600000000', 'm.roux@castellane-auto.fr', '2020-01-15'),
(3, 'Bompard', 'Thomas', '0600000000', 't.bompard@castellane-auto.fr', '2022-06-01');

-- --------------------------------------------------------

--
-- Table structure for table `VOITURE`
--

DROP TABLE IF EXISTS `VOITURE`;
CREATE TABLE `VOITURE` (
  `id_voiture` int(11) NOT NULL,
  `immatriculation` varchar(15) NOT NULL,
  `km_actuels` int(11) DEFAULT 0,
  `date_mise_service` date NOT NULL,
  `id_modele` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `VOITURE`
--

INSERT INTO `VOITURE` (`id_voiture`, `immatriculation`, `km_actuels`, `date_mise_service`, `id_modele`) VALUES
(1, 'FG-123-AB', 25000, '2022-03-01', 1),
(2, 'FH-456-CD', 18000, '2023-01-15', 2),
(3, 'FJ-789-EF', 12000, '2023-09-01', 3),
(4, 'FK-012-GH', 30000, '2021-06-01', 1);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `CLIENT`
--
ALTER TABLE `CLIENT`
  ADD PRIMARY KEY (`id_client`),
  ADD KEY `id_etablissement` (`id_etablissement`);

--
-- Indexes for table `ETABLISSEMENT`
--
ALTER TABLE `ETABLISSEMENT`
  ADD PRIMARY KEY (`id_etablissement`);

--
-- Indexes for table `FACTURATION`
--
ALTER TABLE `FACTURATION`
  ADD PRIMARY KEY (`id_facture`),
  ADD KEY `id_client` (`id_client`);

--
-- Indexes for table `KM_MENSUEL`
--
ALTER TABLE `KM_MENSUEL`
  ADD PRIMARY KEY (`id_voiture`,`id_mois`),
  ADD KEY `id_mois` (`id_mois`);

--
-- Indexes for table `LECON`
--
ALTER TABLE `LECON`
  ADD PRIMARY KEY (`id_lecon`),
  ADD KEY `id_client` (`id_client`),
  ADD KEY `id_moniteur` (`id_moniteur`),
  ADD KEY `id_voiture` (`id_voiture`);

--
-- Indexes for table `MODELE_VOITURE`
--
ALTER TABLE `MODELE_VOITURE`
  ADD PRIMARY KEY (`id_modele`);

--
-- Indexes for table `MOIS`
--
ALTER TABLE `MOIS`
  ADD PRIMARY KEY (`id_mois`),
  ADD UNIQUE KEY `annee` (`annee`,`mois`);

--
-- Indexes for table `MONITEUR`
--
ALTER TABLE `MONITEUR`
  ADD PRIMARY KEY (`id_moniteur`);

--
-- Indexes for table `VOITURE`
--
ALTER TABLE `VOITURE`
  ADD PRIMARY KEY (`id_voiture`),
  ADD UNIQUE KEY `immatriculation` (`immatriculation`),
  ADD KEY `id_modele` (`id_modele`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `CLIENT`
--
ALTER TABLE `CLIENT`
  MODIFY `id_client` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `ETABLISSEMENT`
--
ALTER TABLE `ETABLISSEMENT`
  MODIFY `id_etablissement` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `FACTURATION`
--
ALTER TABLE `FACTURATION`
  MODIFY `id_facture` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `LECON`
--
ALTER TABLE `LECON`
  MODIFY `id_lecon` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT for table `MODELE_VOITURE`
--
ALTER TABLE `MODELE_VOITURE`
  MODIFY `id_modele` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `MOIS`
--
ALTER TABLE `MOIS`
  MODIFY `id_mois` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `MONITEUR`
--
ALTER TABLE `MONITEUR`
  MODIFY `id_moniteur` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `VOITURE`
--
ALTER TABLE `VOITURE`
  MODIFY `id_voiture` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `CLIENT`
--
ALTER TABLE `CLIENT`
  ADD CONSTRAINT `client_ibfk_1` FOREIGN KEY (`id_etablissement`) REFERENCES `ETABLISSEMENT` (`id_etablissement`);

--
-- Constraints for table `FACTURATION`
--
ALTER TABLE `FACTURATION`
  ADD CONSTRAINT `facturation_ibfk_1` FOREIGN KEY (`id_client`) REFERENCES `CLIENT` (`id_client`);

--
-- Constraints for table `KM_MENSUEL`
--
ALTER TABLE `KM_MENSUEL`
  ADD CONSTRAINT `km_mensuel_ibfk_1` FOREIGN KEY (`id_voiture`) REFERENCES `VOITURE` (`id_voiture`),
  ADD CONSTRAINT `km_mensuel_ibfk_2` FOREIGN KEY (`id_mois`) REFERENCES `MOIS` (`id_mois`);

--
-- Constraints for table `LECON`
--
ALTER TABLE `LECON`
  ADD CONSTRAINT `lecon_ibfk_1` FOREIGN KEY (`id_client`) REFERENCES `CLIENT` (`id_client`),
  ADD CONSTRAINT `lecon_ibfk_2` FOREIGN KEY (`id_moniteur`) REFERENCES `MONITEUR` (`id_moniteur`),
  ADD CONSTRAINT `lecon_ibfk_3` FOREIGN KEY (`id_voiture`) REFERENCES `VOITURE` (`id_voiture`);

--
-- Constraints for table `VOITURE`
--
ALTER TABLE `VOITURE`
  ADD CONSTRAINT `voiture_ibfk_1` FOREIGN KEY (`id_modele`) REFERENCES `MODELE_VOITURE` (`id_modele`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
