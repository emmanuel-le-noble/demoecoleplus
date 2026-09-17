-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Hôte : localhost
-- Généré le : sam. 06 sept. 2026 à 00:00
-- Version du serveur : 10.11.16-MariaDB-deb12
-- Version de PHP : 8.2.30
-- Mis à jour pour Ecole Plus v1.2.0 — Portail Parent

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `goodh2642221_59bsri`
--

-- --------------------------------------------------------

--
-- Structure de la table `absences`
--

CREATE TABLE `absences` (
  `ID` int(11) NOT NULL,
  `IDELEVESALLE` int(11) NOT NULL,
  `IDSALLE` int(11) NOT NULL,
  `IDANNEESCOLAIRE` int(11) NOT NULL,
  `IDPOSITION` int(11) NOT NULL,
  `IDMATIERE` int(11) DEFAULT NULL,
  `IDPROF` int(11) DEFAULT NULL,
  `NBREABSENCE` int(11) NOT NULL,
  `DATEENREG` date NOT NULL,
  `IDUSERCREATE` int(11) NOT NULL,
  `TYPEABSENCE` varchar(500) DEFAULT NULL,
  `DATE_DEMANDE` date DEFAULT NULL,
  `DATE_DEBUT` date DEFAULT NULL,
  `DATE_FIN` date DEFAULT NULL,
  `MOTIF_PERMISSION` text DEFAULT NULL,
  `STATUT_PERMISSION` int(11) DEFAULT NULL,
  `FICHIER_ABSENCE` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `anneescolaire`
--

CREATE TABLE `anneescolaire` (
  `ID` int(11) NOT NULL,
  `LIBELLE` text DEFAULT NULL,
  `STATUT` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

--
-- Déchargement des données de la table `anneescolaire`
--

INSERT INTO `anneescolaire` (`ID`, `LIBELLE`, `STATUT`) VALUES
(1, '2025 - 2026', 1),
(2, '2026 - 2027', 1);

-- --------------------------------------------------------

--
-- Structure de la table `annonces_ecole`
--

CREATE TABLE `annonces_ecole` (
  `ID` int(11) NOT NULL,
  `TITRE` varchar(500) NOT NULL,
  `CONTENU` text NOT NULL,
  `DATE_PUBLICATION` datetime NOT NULL DEFAULT current_timestamp(),
  `CIBLE_TYPE` varchar(50) NOT NULL,
  `ID_CIBLE` int(11) DEFAULT NULL,
  `IDUSERCREATE` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `article`
--

CREATE TABLE `article` (
  `ID` int(11) NOT NULL,
  `IDCATEGORIE` int(11) NOT NULL,
  `NOM` varchar(500) DEFAULT NULL,
  `PRIXUNITAIRE` float NOT NULL,
  `QTEDISPO` int(11) NOT NULL,
  `STATUT` int(11) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `articlecategorie`
--

CREATE TABLE `articlecategorie` (
  `ID` int(11) NOT NULL,
  `LIBELLE` varchar(500) DEFAULT NULL,
  `STATUT` int(11) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `articleentree`
--

CREATE TABLE `articleentree` (
  `ID` int(11) NOT NULL,
  `NUM_LIV` varchar(100) DEFAULT NULL,
  `DATE_LIV` date DEFAULT NULL,
  `STATUT` int(11) NOT NULL DEFAULT 1,
  `LIBELLE` text DEFAULT NULL,
  `ID_USER` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `articleentree_article`
--

CREATE TABLE `articleentree_article` (
  `ID` int(11) NOT NULL,
  `IDARTICLE_ENTREE` int(11) DEFAULT NULL,
  `IDARTICLE` int(11) DEFAULT NULL,
  `QTE_LIV` int(11) NOT NULL,
  `MONTANT` float NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `articlesortie`
--

CREATE TABLE `articlesortie` (
  `ID` int(11) NOT NULL,
  `TYPESORTIE` int(11) DEFAULT NULL,
  `NUM_RECU` varchar(500) DEFAULT NULL,
  `DATE_SORTIE` date DEFAULT NULL,
  `ID_ELEVE` int(11) DEFAULT NULL,
  `ID_USER` int(11) DEFAULT NULL,
  `STATUT` int(11) DEFAULT 1,
  `MOTIF_SORTIE` varchar(500) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `articlesortie_article`
--

CREATE TABLE `articlesortie_article` (
  `ID` int(11) NOT NULL,
  `ID_ARTICLESORTIE` int(11) DEFAULT NULL,
  `IDARTICLE` int(11) DEFAULT NULL,
  `QTE_SORTIE` int(11) NOT NULL,
  `MONTANT` float NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `banque`
--

CREATE TABLE `banque` (
  `ID` int(11) NOT NULL,
  `LIBELLE` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `baremeirpp`
--

CREATE TABLE `baremeirpp` (
  `ID` int(11) NOT NULL,
  `VALEUR_1` float DEFAULT NULL,
  `VALEUR_2` float DEFAULT NULL,
  `TAUX` float DEFAULT NULL,
  `STATUT` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

--
-- Déchargement des données de la table `baremeirpp`
--

INSERT INTO `baremeirpp` (`ID`, `VALEUR_1`, `VALEUR_2`, `TAUX`, `STATUT`) VALUES
(1, 0, 900000, 0.5, 1),
(2, 900001, 4000000, 7, 1),
(3, 4000000, 6000000, 15, 1),
(4, 6000000, 10000000, 25, 1),
(5, 10000000, 15000000, 30, 1),
(6, 15000000, 0, 35, 1),
(7, 0, 900000, 0.5, 1);

-- --------------------------------------------------------

--
-- Structure de la table `bulletin`
--

CREATE TABLE `bulletin` (
  `ID` int(11) NOT NULL,
  `IDPOSITION` int(11) NOT NULL,
  `IDELEVE` int(11) NOT NULL,
  `IDANNEESCOLAIRE` int(11) NOT NULL,
  `IDSALLE` int(11) NOT NULL,
  `MOYENNE_GENE` double DEFAULT NULL,
  `RANG` double DEFAULT NULL,
  `MOYEN_ANN` double DEFAULT NULL,
  `RANG_ANN` int(11) DEFAULT NULL,
  `observation` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `bulletincontenu`
--

CREATE TABLE `bulletincontenu` (
  `ID` int(11) NOT NULL,
  `IDBULLETIN` int(11) NOT NULL,
  `IDMATIERE` int(11) NOT NULL,
  `INTE` double DEFAULT NULL,
  `DS` double DEFAULT NULL,
  `DN` double DEFAULT NULL,
  `MOY_CLASSE` double DEFAULT NULL,
  `NOTES_COMP` double DEFAULT NULL,
  `MOY_TRIMES` double DEFAULT NULL,
  `COEF` double DEFAULT NULL,
  `MOY_PONDERE` double DEFAULT NULL,
  `RANG` double DEFAULT NULL,
  `PROFESSEUR` text DEFAULT NULL,
  `APPRECIATION` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `cahier_texte`
--

CREATE TABLE `cahier_texte` (
  `ID` int(11) NOT NULL,
  `IDSALLE` int(11) NOT NULL,
  `IDMATIERE` int(11) NOT NULL,
  `IDPROF` int(11) NOT NULL,
  `IDANNEESCOLAIRE` int(11) NOT NULL,
  `DATE_COURS` date NOT NULL,
  `CONTENU_COURS` text DEFAULT NULL,
  `DEVOIRS_A_FAIRE` text DEFAULT NULL,
  `DATE_ECHEANCE` date DEFAULT NULL,
  `FICHIER_DEVOIR` varchar(500) DEFAULT NULL,
  `DATE_REGISTRE` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `caisse`
--

CREATE TABLE `caisse` (
  `ID` int(11) NOT NULL,
  `DEBUT` date DEFAULT NULL,
  `FIN` date DEFAULT NULL,
  `IDUSER` int(11) NOT NULL,
  `STATUT` int(11) NOT NULL,
  `MONTANT` double DEFAULT NULL,
  `IDANNEESCOLAIRE` int(11) DEFAULT NULL,
  `TYPE` varchar(500) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

--
-- Déchargement des données de la table `caisse`
--

INSERT INTO `caisse` (`ID`, `DEBUT`, `FIN`, `IDUSER`, `STATUT`, `MONTANT`, `IDANNEESCOLAIRE`, `TYPE`) VALUES
(1, '2026-03-23', '2026-03-23', 103, 1, 45000, 1, 'PaiementFraisScolarite');

-- --------------------------------------------------------

--
-- Structure de la table `caissepaiement`
--

CREATE TABLE `caissepaiement` (
  `ID` int(11) NOT NULL,
  `IDPAIEMENTFRAIS` int(11) NOT NULL,
  `IDCAISSE` int(11) NOT NULL,
  `TYPE` varchar(500) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

--
-- Déchargement des données de la table `caissepaiement`
--

INSERT INTO `caissepaiement` (`ID`, `IDPAIEMENTFRAIS`, `IDCAISSE`, `TYPE`) VALUES
(1, 2, 1, 'PaiementFraisScolarite');

-- --------------------------------------------------------

--
-- Structure de la table `classe`
--

CREATE TABLE `classe` (
  `IDCLASSE` int(11) NOT NULL,
  `CODECLASSE` text DEFAULT NULL,
  `NOMCLASSE` text DEFAULT NULL,
  `IDDOMAINE` int(11) DEFAULT NULL,
  `STATUT` int(11) DEFAULT NULL,
  `CREATE_ID` int(11) DEFAULT NULL,
  `PRIORITE` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

--
-- Déchargement des données de la table `classe`
--

INSERT INTO `classe` (`IDCLASSE`, `CODECLASSE`, `NOMCLASSE`, `IDDOMAINE`, `STATUT`, `CREATE_ID`, `PRIORITE`) VALUES
(1, '6eme', '6eme', 3, 1, 102, 1),
(2, '5eme', '5eme', 3, 1, 102, 2),
(3, '4eme', '4eme', 3, 1, 102, 3),
(4, '3eme', '3eme', 3, 1, 102, 4),
(5, 'CM2', 'CM2', 4, 1, 102, 6),
(6, 'CM1', 'CM1', 4, 1, 102, 5),
(7, 'CE2', 'CE2', 4, 1, 102, 4),
(8, 'CE1', 'CE1', 4, 1, 102, 3),
(9, 'CP2', 'CP2', 4, 1, 102, 2),
(10, 'CP1', 'CP1', 4, 1, 102, 1),
(12, 'CI1', 'CI1', 5, 1, 102, 1),
(13, 'CI2', 'CI2', 5, 1, 102, 2),
(14, '2nde S', '2nde S', 6, 1, 102, 1),
(15, '1ere A4', '1ere A4', 6, 1, 102, 2),
(16, '1ere D', '1ere D', 6, 1, 102, 1),
(17, 'Tle C4', 'Tle C4', 6, 1, 102, 1),
(18, 'Tle D', 'Tle D', 6, 1, 102, 1),
(19, '2nde A4', '2nde A4', 6, 1, 102, 1),
(20, '1ere C4', '1ere C4', 6, 1, 102, 1),
(21, 'Tle A4', 'Tle A4', 6, 1, 102, 1);

-- --------------------------------------------------------

--
-- Structure de la table `compte`
--

CREATE TABLE `compte` (
  `ID` int(11) NOT NULL,
  `LIBELLE` text DEFAULT NULL,
  `STATUT` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

--
-- Déchargement des données de la table `compte`
--

INSERT INTO `compte` (`ID`, `LIBELLE`, `STATUT`) VALUES
(1, 'CAISSE', 1),
(2, 'BANQUE', 1);

-- --------------------------------------------------------

--
-- Structure de la table `decision`
--

CREATE TABLE `decision` (
  `ID` int(11) NOT NULL,
  `IDPOSITION` int(11) DEFAULT NULL,
  `IDANNEESCOLAIRE` int(11) DEFAULT NULL,
  `DATEDECISION` date DEFAULT NULL,
  `IDCLASSE` int(11) DEFAULT NULL,
  `MOYENNE` float DEFAULT NULL,
  `FICHIER` text DEFAULT NULL,
  `PASSAGECLASSESUP` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `decisionmoyennereussite`
--

CREATE TABLE `decisionmoyennereussite` (
  `ID` int(11) NOT NULL,
  `IDDECISION` int(11) DEFAULT NULL,
  `IDCLASSE` int(11) NOT NULL,
  `MOYENNECONSEIL` double DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `decisionrapport`
--

CREATE TABLE `decisionrapport` (
  `ID` int(11) NOT NULL,
  `IDDECISION` int(11) DEFAULT NULL,
  `CONTENU` text DEFAULT NULL,
  `FICHIER` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `domaine`
--

CREATE TABLE `domaine` (
  `ID` int(11) NOT NULL,
  `NOM` text DEFAULT NULL,
  `LOGO` text DEFAULT NULL,
  `TEL` text DEFAULT NULL,
  `BP` text DEFAULT NULL,
  `STATUT` int(11) DEFAULT NULL,
  `CREATE_ID` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

--
-- Déchargement des données de la table `domaine`
--

INSERT INTO `domaine` (`ID`, `NOM`, `LOGO`, `TEL`, `BP`, `STATUT`, `CREATE_ID`) VALUES
(3, 'COLLEGE', 'Neutre.jpg', '(228) 00000000', 'LOME-TOGO', 1, 102),
(4, 'PRIMAIRE', 'Neutre.jpg', '(228) 00000000', 'LOME-TOGO', 1, 102),
(5, 'PRESCOLAIRE', 'Neutre.jpg', '(228) 00000000', 'LOME-TOGO', 1, 102),
(6, 'LYCEE', 'Neutre.jpg', '(228) 00000000', 'LOME-TOGO', 1, 102),
(7, 'UNIVERSITE', '', '(228) 00000000', 'LOME-TOGO', 1, 103);

-- --------------------------------------------------------

--
-- Structure de la table `eleve`
--

CREATE TABLE `eleve` (
  `ID_ELEVE` int(11) NOT NULL,
  `NOM_ELEVE` text DEFAULT NULL,
  `PRENOM_ELEVE` text DEFAULT NULL,
  `ETAT_ELEVE` varchar(50) NOT NULL,
  `SEXE_ELEVE` varchar(50) DEFAULT NULL,
  `DATENAISSANCE_ELEVE` date DEFAULT NULL,
  `LIEUNAISSANCE_ELEVE` varchar(100) DEFAULT NULL,
  `TELTUTEUR` text NOT NULL,
  `MAILTUTEUR` text NOT NULL,
  `PHOTO` text DEFAULT NULL,
  `MATRICULE` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

--
-- Déchargement des données de la table `eleve`
--

INSERT INTO `eleve` (`ID_ELEVE`, `NOM_ELEVE`, `PRENOM_ELEVE`, `ETAT_ELEVE`, `SEXE_ELEVE`, `DATENAISSANCE_ELEVE`, `LIEUNAISSANCE_ELEVE`, `TELTUTEUR`, `MAILTUTEUR`, `PHOTO`, `MATRICULE`) VALUES
(1, 'KOWOUVI', 'K Emmanuel Nono', '', 'Masculin', '2004-12-25', 'lomé', '(228) 99680469', 'emmanuenonokowouvi@gmail.com', '', '1139-26'),
(2, 'AHOLOU', 'Jacques', '', 'Masculin', '2017-10-04', 'LOME', '(228) 92336666', 'azkenneth@gmail.com', 'eleve2.jpg', '1140-26');

-- --------------------------------------------------------

--
-- Structure de la table `eleveanneescolaire`
--

CREATE TABLE `eleveanneescolaire` (
  `ID` int(11) NOT NULL,
  `IDELEVE` int(11) NOT NULL,
  `IDANNEESCOLAIRE` int(11) NOT NULL,
  `IDCLASSE` int(11) NOT NULL,
  `STATUT` double NOT NULL,
  `INSCRIT` int(11) NOT NULL,
  `ETAT` int(11) NOT NULL DEFAULT 1,
  `BOURSIER` varchar(100) DEFAULT NULL,
  `ETATREMISE` varchar(100) DEFAULT NULL,
  `DOSSIERINSCRIPTION` text DEFAULT NULL,
  `COMMENTAIRE` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

--
-- Déchargement des données de la table `eleveanneescolaire`
--

INSERT INTO `eleveanneescolaire` (`ID`, `IDELEVE`, `IDANNEESCOLAIRE`, `IDCLASSE`, `STATUT`, `INSCRIT`, `ETAT`, `BOURSIER`, `ETATREMISE`, `DOSSIERINSCRIPTION`, `COMMENTAIRE`) VALUES
(1, 1, 1, 16, 1, 1, 1, '', 'Aucune', '', ''),
(2, 2, 1, 7, 1, 1, 1, '', 'Aucune', '', '');

-- --------------------------------------------------------

--
-- Structure de la table `elevesalle`
--

CREATE TABLE `elevesalle` (
  `IDELEVE` int(11) NOT NULL,
  `IDSALLE` int(11) NOT NULL,
  `ID` int(11) NOT NULL,
  `STATUT` double NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

--
-- Déchargement des données de la table `elevesalle`
--

INSERT INTO `elevesalle` (`IDELEVE`, `IDSALLE`, `ID`, `STATUT`) VALUES
(1, 15, 1, 1),
(2, 7, 2, 1);

-- --------------------------------------------------------

--
-- Structure de la table `elevestatutclasse`
--

CREATE TABLE `elevestatutclasse` (
  `ID` int(11) NOT NULL,
  `CODE` text DEFAULT NULL,
  `LIBELLE` text DEFAULT NULL,
  `STATUT` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

--
-- Déchargement des données de la table `elevestatutclasse`
--

INSERT INTO `elevestatutclasse` (`ID`, `CODE`, `LIBELLE`, `STATUT`) VALUES
(1, 'NC', 'Nouveau', 1),
(2, 'DC', 'Doublant', 1);

-- --------------------------------------------------------

--
-- Structure de la table `elevestatutetablissement`
--

CREATE TABLE `elevestatutetablissement` (
  `ID` int(11) NOT NULL,
  `CODE` text DEFAULT NULL,
  `LIBELLE` text DEFAULT NULL,
  `STATUT` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

--
-- Déchargement des données de la table `elevestatutetablissement`
--

INSERT INTO `elevestatutetablissement` (`ID`, `CODE`, `LIBELLE`, `STATUT`) VALUES
(1, 'NE', 'Nouveau', 1),
(2, 'AE', 'Ancien', 1);

-- --------------------------------------------------------

--
-- Structure de la table `entreesortie`
--

CREATE TABLE `entreesortie` (
  `ID` int(11) NOT NULL,
  `IDSALAIRE` int(11) DEFAULT NULL,
  `IDPRET` int(11) DEFAULT NULL,
  `IDANNEESCOLAIRE` int(11) DEFAULT NULL,
  `MONTANT` double DEFAULT NULL,
  `LIBELLE` text DEFAULT NULL,
  `STATUT` int(11) DEFAULT NULL,
  `IDUSERAJOUT` int(11) DEFAULT NULL,
  `IDUSERDELETE` int(11) DEFAULT NULL,
  `IDUSERAUTO` int(11) DEFAULT NULL,
  `DATEOPERATION` date DEFAULT NULL,
  `COMPTEMOUVEMENT` int(11) DEFAULT NULL,
  `IDTYPEENTREESORTIE` int(11) DEFAULT NULL,
  `IDSOUSTYPEENTREESORTIE` int(11) DEFAULT NULL,
  `MONTANTCOMPTE` float DEFAULT NULL,
  `FICHEATTACHE` text DEFAULT NULL,
  `DATESAISIE` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

--
-- Déchargement des données de la table `entreesortie`
--

INSERT INTO `entreesortie` (`ID`, `IDSALAIRE`, `IDPRET`, `IDANNEESCOLAIRE`, `MONTANT`, `LIBELLE`, `STATUT`, `IDUSERAJOUT`, `IDUSERDELETE`, `IDUSERAUTO`, `DATEOPERATION`, `COMPTEMOUVEMENT`, `IDTYPEENTREESORTIE`, `IDSOUSTYPEENTREESORTIE`, `MONTANTCOMPTE`, `FICHEATTACHE`, `DATESAISIE`) VALUES
(3, NULL, NULL, 1, 20000, 'Paiement CashPower', 1, 103, NULL, 1774264514, '2026-03-23', 1, 2, 5, NULL, 'Capture d\'écran 2026-03-18 075213.png', '2026-03-23 12:58:48');

-- --------------------------------------------------------

--
-- Structure de la table `envoimail`
--

CREATE TABLE `envoimail` (
  `ID` int(11) NOT NULL,
  `TYPENOTIFICATION` text DEFAULT NULL,
  `OBJET` text DEFAULT NULL,
  `DATEENVOI` date DEFAULT NULL,
  `CREATED_ID` int(11) DEFAULT NULL,
  `IDANNEESCOLAIRE` int(11) DEFAULT NULL,
  `IDPOSITION` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `envoimaildetail`
--

CREATE TABLE `envoimaildetail` (
  `ID` int(11) NOT NULL,
  `IDENVOISMS` int(11) DEFAULT NULL,
  `DATEOPERATION` date DEFAULT NULL,
  `IDELEVESALLE` int(11) DEFAULT NULL,
  `NOMPRENOMELEVE` text DEFAULT NULL,
  `VALEUR` int(11) DEFAULT NULL,
  `TELTUTEUR` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `envoisms`
--

CREATE TABLE `envoisms` (
  `ID` int(11) NOT NULL,
  `TYPENOTIFICATION` text DEFAULT NULL,
  `OBJET` text DEFAULT NULL,
  `DATEENVOI` date DEFAULT NULL,
  `CREATED_ID` int(11) DEFAULT NULL,
  `IDANNEESCOLAIRE` int(11) DEFAULT NULL,
  `IDPOSITION` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `envoismsdetail`
--

CREATE TABLE `envoismsdetail` (
  `ID` int(11) NOT NULL,
  `IDENVOISMS` int(11) DEFAULT NULL,
  `DATEOPERATION` date DEFAULT NULL,
  `IDELEVESALLE` int(11) DEFAULT NULL,
  `NOMPRENOMELEVE` text DEFAULT NULL,
  `VALEUR` int(11) DEFAULT NULL,
  `TELTUTEUR` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `etat`
--

CREATE TABLE `etat` (
  `ID` int(11) NOT NULL,
  `NOM` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

--
-- Déchargement des données de la table `etat`
--

INSERT INTO `etat` (`ID`, `NOM`) VALUES
(1, 'Liste des élèves ayant une moyenne supérieur à'),
(2, 'Liste des élèves ayant une moyenne inférieur à'),
(3, 'Evaluation annuelle générale');

-- --------------------------------------------------------

--
-- Structure de la table `horairesaisienote`
--

CREATE TABLE `horairesaisienote` (
  `ID` int(11) NOT NULL,
  `DATEDEBUT` date DEFAULT NULL,
  `DATEFIN` date DEFAULT NULL,
  `STATUT` int(11) NOT NULL DEFAULT 1,
  `CREATED_ID` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `horairesaisienote`
--

INSERT INTO `horairesaisienote` (`ID`, `DATEDEBUT`, `DATEFIN`, `STATUT`, `CREATED_ID`) VALUES
(1, '2026-03-25', '2026-03-26', 1, 103);

-- --------------------------------------------------------

--
-- Structure de la table `jour`
--

CREATE TABLE `jour` (
  `ID` int(11) NOT NULL,
  `CODE` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

--
-- Déchargement des données de la table `jour`
--

INSERT INTO `jour` (`ID`, `CODE`) VALUES
(1, 'LUNDI'),
(2, 'MARDI'),
(3, 'MERCREDI'),
(4, 'JEUDI'),
(5, 'VENDREDI'),
(6, 'SAMEDI');

-- --------------------------------------------------------

--
-- Structure de la table `journalisation`
--

CREATE TABLE `journalisation` (
  `ID` int(11) NOT NULL,
  `IDUSER` int(11) DEFAULT NULL,
  `ACTION` text DEFAULT NULL,
  `VALEUR` text DEFAULT NULL,
  `DATEACTION` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

--
-- Déchargement des données de la table `journalisation`
--

INSERT INTO `journalisation` (`ID`, `IDUSER`, `ACTION`, `VALEUR`, `DATEACTION`) VALUES
(1, 102, 'Connecter', 'Connexion à l\'application', '2026-02-03 00:04:09'),
(2, 103, 'Connecter', 'Connexion à l\'application', '2026-02-03 00:35:59'),
(3, 103, 'Connecter', 'Connexion à l\'application', '2026-02-10 09:54:25'),
(4, 103, 'Connecter', 'Connexion à l\'application', '2026-02-14 17:49:48'),
(5, 103, 'Connecter', 'Connexion à l\'application', '2026-02-16 11:34:17'),
(6, 103, 'Connecter', 'Connexion à l\'application', '2026-02-16 12:04:22'),
(7, 103, 'Connecter', 'Connexion à l\'application', '2026-03-23 12:05:16'),
(8, 103, 'Connecter', 'Connexion à l\'application', '2026-03-23 12:13:55'),
(9, 1774264514, 'Connecter', 'Connexion à l\'application', '2026-03-23 12:15:47'),
(10, 103, 'Connecter', 'Connexion à l\'application', '2026-03-23 18:28:05'),
(11, 103, 'Connecter', 'Connexion à l\'application', '2026-03-24 16:19:21'),
(12, 103, 'Connecter', 'Connexion à l\'application', '2026-03-30 10:52:07'),
(13, 103, 'Connecter', 'Connexion à l\'application', '2026-03-30 11:31:52'),
(14, 103, 'Connecter', 'Connexion à l\'application', '2026-03-31 18:08:38'),
(15, 103, 'Connecter', 'Connexion à l\'application', '2026-04-07 02:25:34'),
(16, 103, 'Connecter', 'Connexion à l\'application', '2026-04-15 11:52:59'),
(17, 103, 'Connecter', 'Connexion à l\'application', '2026-06-01 22:37:59'),
(18, 103, 'Connecter', 'Connexion à l\'application', '2026-06-01 22:41:21'),
(19, 103, 'Connecter', 'Connexion à l\'application', '2026-06-02 00:03:46'),
(20, 103, 'Connecter', 'Connexion à l\'application', '2026-06-02 06:14:39'),
(21, 103, 'Connecter', 'Connexion à l\'application', '2026-06-02 09:47:02'),
(22, 103, 'Connecter', 'Connexion à l\'application', '2026-06-04 19:53:24'),
(23, 103, 'Connecter', 'Connexion à l\'application', '2026-06-25 12:06:42'),
(24, 103, 'Connecter', 'Connexion à l\'application', '2026-06-25 13:30:35'),
(25, 103, 'Connecter', 'Connexion à l\'application', '2026-06-26 01:06:05'),
(26, 103, 'Connecter', 'Connexion à l\'application', '2026-06-26 01:10:25'),
(27, 103, 'Connecter', 'Connexion à l\'application', '2026-07-23 01:06:09'),
(28, 103, 'Connecter', 'Connexion à l\'application', '2026-07-23 21:56:42'),
(29, 103, 'Connecter', 'Connexion à l\'application', '2026-08-17 21:41:05'),
(30, 103, 'Connecter', 'Connexion à l\'application', '2026-08-18 02:00:06'),
(31, 103, 'Connecter', 'Connexion à l\'application', '2026-08-18 06:28:16'),
(32, 103, 'Connecter', 'Connexion à l\'application', '2026-08-18 19:55:55'),
(33, 103, 'Connecter', 'Connexion à l\'application', '2026-09-04 23:29:35'),
(34, 103, 'Connecter', 'Connexion à l\'application', '2026-09-04 23:33:20'),
(35, 103, 'Connecter', 'Connexion à l\'application', '2026-09-05 14:04:00'),
(36, 103, 'Connecter', 'Connexion à l\'application', '2026-09-07 15:48:35');

-- --------------------------------------------------------

--
-- Structure de la table `matiere`
--

CREATE TABLE `matiere` (
  `ID_MATIERE` int(11) NOT NULL,
  `CODE_MATIERE` text DEFAULT NULL,
  `NOM_MATIERE` text DEFAULT NULL,
  `STATUT_MATIERE` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

--
-- Déchargement des données de la table `matiere`
--

INSERT INTO `matiere` (`ID_MATIERE`, `CODE_MATIERE`, `NOM_MATIERE`, `STATUT_MATIERE`) VALUES
(3, 'ANGLAIS', 'ANGLAIS', 1),
(5, 'HISTO-GEO', 'HISTO-GEO', 1),
(6, 'MATHS', 'MATHS', 1),
(7, 'PCT', 'PCT', 1),
(8, 'S.V.T', 'S.V.T', 1),
(9, 'E.P.S', 'E.P.S', 1),
(10, 'DESSIN', 'DESSIN', 1),
(11, 'EM', 'ENSEIGNEMENT MENAGER', 1),
(15, 'ECM', 'ECM', 1),
(16, 'FR', 'FRANCAIS', 1),
(17, 'REDACTION', 'REDACTION', 1),
(20, 'LANGUE NAT.', 'LANGUE NAT.', 1),
(24, 'INFO', 'INFORMATIQUE TECHNOLOGIE', 1),
(27, 'ALL', 'ALLEMAND', 1),
(28, 'PORTUGAIS', 'PORTUGAIS', 1),
(29, 'CHINOIS', 'CHINOIS', 1),
(30, 'INITIATION AU DROIT', 'INITIATION AU DROIT', 1),
(31, 'MUSIQUE', 'MUSIQUE', 1),
(32, 'DANSE', 'DANSE', 1),
(33, 'COURS D\'AUTO-ECOLE ET DE CONDUITE', 'COURS D\'AUTO-ECOLE ET DE CONDUITE', 1),
(34, 'COURS DE STRATEGIE ET D\'ORIENTATION', 'COURS DE STRATEGIE ET D\'ORIENTATION', 1),
(35, 'KARATE', 'KARATE', 1),
(36, 'TAEKWONDO', 'TAEKWONDO', 1),
(37, 'PHILOSOPHIE', 'PHILOSOPHIE', 1),
(38, 'HANDBALL', 'HANDBALL', 1),
(39, 'TENNIIS DE TABLE', 'TENNIIS DE TABLE', 1),
(40, 'IA', 'INITIATION A L\'AGRICULTURE', 1),
(41, 'EWE', 'EWE', 1),
(42, 'DICTEE', 'DICTEE', 1),
(43, 'QUESTIONS', 'QUESTIONS', 1),
(44, 'ETUDE DE TEXTE', 'ETUDE DE TEXTE', 1),
(45, 'CALCUL MENTAL', 'CALCUL MENTAL', 1),
(46, 'PROBLEME', 'PROBLEME', 1),
(47, 'SCIENCE ET TECHNOLOGIES', 'SCIENCE ET TECHNOLOGIES', 1),
(48, 'SCIENCE HUMAINES', 'SCIENCE HUMAINES', 1),
(50, 'ARB', 'ARABE', 1);

-- --------------------------------------------------------

--
-- Structure de la table `matierecoefficient`
--

CREATE TABLE `matierecoefficient` (
  `ID` int(11) NOT NULL,
  `IDMATIERE` int(11) NOT NULL,
  `IDCLASSE` int(11) NOT NULL,
  `COEFFICIENT` float NOT NULL,
  `IDANNEESCOLAIRE` int(11) NOT NULL,
  `STATUT` int(11) NOT NULL DEFAULT 1,
  `ETAT` int(11) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

--
-- Déchargement des données de la table `matierecoefficient`
--

INSERT INTO `matierecoefficient` (`ID`, `IDMATIERE`, `IDCLASSE`, `COEFFICIENT`, `IDANNEESCOLAIRE`, `STATUT`, `ETAT`) VALUES
(1, 6, 1, 1, 1, 1, 1),
(2, 16, 1, 2, 1, 1, 1),
(3, 3, 1, 1, 1, 1, 1),
(4, 5, 1, 1, 1, 1, 1),
(5, 15, 1, 1, 1, 1, 1),
(6, 37, 14, 2, 1, 1, 1),
(7, 37, 16, 2, 1, 1, 1),
(8, 37, 18, 2, 1, 1, 1),
(9, 7, 1, 1, 1, 1, 1),
(10, 8, 1, 1, 1, 1, 1),
(11, 9, 1, 1, 1, 1, 1),
(12, 16, 2, 2, 1, 1, 1),
(13, 16, 3, 3, 1, 1, 1),
(14, 16, 4, 3, 1, 1, 1),
(15, 16, 14, 2, 1, 1, 1),
(16, 16, 16, 2, 1, 1, 1),
(17, 16, 18, 2, 1, 1, 1),
(18, 3, 2, 1, 1, 1, 1),
(19, 3, 3, 2, 1, 1, 1),
(20, 3, 4, 2, 1, 1, 1),
(21, 3, 14, 2, 1, 1, 1),
(22, 3, 16, 2, 1, 1, 1),
(23, 3, 18, 2, 1, 1, 1),
(24, 27, 1, 1, 1, 1, 1),
(25, 27, 2, 1, 1, 1, 1),
(26, 27, 3, 1, 1, 1, 1),
(27, 27, 4, 1, 1, 1, 1),
(28, 27, 14, 1, 1, 1, 1),
(29, 27, 16, 1, 1, 1, 1),
(30, 29, 14, 1, 1, 1, 1),
(31, 29, 16, 1, 1, 1, 1),
(32, 28, 14, 1, 1, 1, 1),
(33, 28, 16, 1, 1, 1, 1),
(34, 5, 2, 1, 1, 1, 1),
(35, 5, 3, 2, 1, 1, 1),
(36, 5, 4, 2, 1, 1, 1),
(37, 5, 14, 2, 1, 1, 1),
(38, 5, 16, 2, 1, 1, 1),
(39, 5, 18, 2, 1, 1, 1),
(40, 15, 2, 1, 1, 1, 1),
(41, 15, 4, 2, 1, 1, 1),
(42, 15, 3, 2, 1, 1, 1),
(43, 15, 14, 2, 1, 1, 1),
(44, 15, 16, 2, 1, 1, 1),
(45, 15, 18, 2, 1, 1, 1),
(46, 6, 2, 1, 1, 1, 1),
(47, 6, 3, 3, 1, 1, 1),
(48, 6, 4, 3, 1, 1, 1),
(49, 6, 16, 3, 1, 1, 1),
(50, 6, 18, 3, 1, 1, 1),
(51, 7, 2, 1, 1, 1, 1),
(52, 7, 3, 3, 1, 1, 1),
(53, 7, 4, 3, 1, 1, 1),
(54, 7, 14, 3, 1, 1, 1),
(55, 7, 16, 3, 1, 1, 1),
(56, 7, 18, 3, 1, 1, 1),
(57, 8, 2, 1, 1, 1, 1),
(58, 8, 3, 2, 1, 1, 1),
(59, 8, 4, 2, 1, 1, 1),
(60, 8, 14, 2, 1, 1, 1),
(61, 8, 16, 4, 1, 1, 1),
(62, 8, 18, 4, 1, 1, 1),
(63, 9, 2, 1, 1, 1, 1),
(64, 9, 3, 1, 1, 1, 1),
(65, 9, 4, 1, 1, 1, 1),
(66, 9, 14, 1, 1, 1, 1),
(67, 9, 16, 1, 1, 1, 1),
(68, 9, 18, 1, 1, 1, 1),
(69, 24, 1, 1, 1, 1, 1),
(70, 24, 2, 1, 1, 1, 1),
(71, 24, 3, 1, 1, 1, 1),
(72, 24, 4, 1, 1, 1, 1),
(73, 24, 14, 1, 1, 1, 1),
(74, 24, 16, 1, 1, 1, 1),
(75, 24, 18, 1, 1, 1, 1),
(76, 30, 1, 1, 1, 1, 1),
(77, 30, 2, 1, 1, 1, 1),
(78, 30, 3, 1, 1, 1, 1),
(79, 30, 14, 1, 1, 1, 1),
(80, 30, 4, 1, 1, 1, 1),
(81, 30, 16, 1, 1, 1, 1),
(82, 31, 18, 1, 1, 1, 1),
(83, 31, 16, 1, 1, 1, 1),
(84, 31, 14, 1, 1, 1, 1),
(85, 31, 4, 1, 1, 1, 1),
(86, 31, 3, 1, 1, 1, 1),
(87, 31, 2, 1, 1, 1, 1),
(88, 31, 1, 1, 1, 1, 1),
(89, 32, 1, 1, 1, 1, 1),
(90, 32, 2, 1, 1, 1, 1),
(91, 32, 3, 1, 1, 1, 1),
(92, 32, 4, 1, 1, 1, 1),
(93, 32, 14, 1, 1, 1, 1),
(94, 32, 16, 1, 1, 1, 1),
(95, 32, 18, 1, 1, 1, 1),
(96, 34, 14, 1, 1, 1, 1),
(97, 34, 16, 1, 1, 1, 1),
(98, 6, 5, 20, 1, 1, 1),
(99, 10, 5, 10, 1, 1, 1),
(100, 15, 5, 10, 1, 1, 1),
(101, 17, 5, 20, 1, 1, 1),
(102, 42, 5, 10, 1, 1, 1),
(103, 43, 5, 10, 1, 1, 1),
(104, 44, 5, 20, 1, 1, 1),
(105, 45, 5, 10, 1, 1, 1),
(106, 46, 5, 10, 1, 1, 1),
(107, 47, 5, 10, 1, 1, 1),
(108, 48, 5, 10, 1, 1, 1),
(109, 41, 1, 1, 1, 1, 1),
(110, 41, 2, 1, 1, 1, 1),
(111, 41, 3, 1, 1, 1, 1),
(112, 41, 4, 1, 1, 1, 1),
(113, 6, 14, 4, 1, 1, 1),
(114, 40, 1, 1, 1, 1, 1),
(115, 40, 2, 1, 1, 1, 1),
(116, 40, 3, 1, 1, 1, 1),
(117, 40, 4, 1, 1, 1, 1);

-- --------------------------------------------------------

--
-- Structure de la table `messagerie_portail`
--

CREATE TABLE `messagerie_portail` (
  `ID` int(11) NOT NULL,
  `EXPEDITEUR_TYPE` varchar(50) NOT NULL,
  `ID_EXPEDITEUR` int(11) NOT NULL,
  `DESTINATAIRE_TYPE` varchar(50) NOT NULL,
  `ID_DESTINATAIRE` int(11) NOT NULL,
  `MESSAGE` text NOT NULL,
  `DATE_ENVOI` datetime NOT NULL DEFAULT current_timestamp(),
  `STATUT_LECTURE` int(11) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `modepaiement`
--

CREATE TABLE `modepaiement` (
  `ID` int(11) NOT NULL,
  `LIBELLE` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

--
-- Déchargement des données de la table `modepaiement`
--

INSERT INTO `modepaiement` (`ID`, `LIBELLE`) VALUES
(1, 'ESPECE'),
(2, 'VIREMENT BANCAIRE'),
(3, 'CHEQUE');

-- --------------------------------------------------------

--
-- Structure de la table `mois`
--

CREATE TABLE `mois` (
  `ID` int(11) NOT NULL,
  `DEBUTMOIS` date DEFAULT NULL,
  `FINMOIS` date DEFAULT NULL,
  `STATUT` int(11) DEFAULT NULL,
  `CORPS` int(11) DEFAULT NULL,
  `CREATE_ID` int(11) DEFAULT NULL,
  `DATECREATE` date DEFAULT NULL,
  `DATEAPPROUV` date DEFAULT NULL,
  `OBSERVATION` text DEFAULT NULL,
  `APPROUV_ID` int(11) DEFAULT NULL,
  `IDCOMPTE` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `moissalaire`
--

CREATE TABLE `moissalaire` (
  `ID` int(11) NOT NULL,
  `IDMOIS` int(11) DEFAULT NULL,
  `IDPERS` int(11) DEFAULT NULL,
  `CORPS` text DEFAULT NULL,
  `PERSONNEACHARGE` text DEFAULT NULL,
  `SALAIREBASE` text DEFAULT NULL,
  `ANCIENNETE` text DEFAULT NULL,
  `SURSALAIRE` text DEFAULT NULL,
  `INDEMNITEFONCTION` text DEFAULT NULL,
  `PRIMESUJETION` text DEFAULT NULL,
  `PRIMEINTERIM` text DEFAULT NULL,
  `INDEMNITELOGEMENT` text DEFAULT NULL,
  `INDEMNITETRANSPORT` text DEFAULT NULL,
  `PRIMECAISSE` text DEFAULT NULL,
  `ALLOCATIONFAMI` text DEFAULT NULL,
  `SALAIREBRUTE` text DEFAULT NULL,
  `CNSS` text DEFAULT NULL,
  `CNSS_EMPLOYEUR` text DEFAULT NULL,
  `INAM` text DEFAULT NULL,
  `CRT` text DEFAULT NULL,
  `TCS` text DEFAULT NULL,
  `IRPP` text DEFAULT NULL,
  `ASSURANCE` text DEFAULT NULL,
  `REMBOURSEMENT` text DEFAULT NULL,
  `AUTRESRETENUES` text DEFAULT NULL,
  `MUTUELLE` text DEFAULT NULL,
  `TOTALRETENUES` text DEFAULT NULL,
  `PRIMEINSTALLATION` text DEFAULT NULL,
  `RAPPEL` text DEFAULT NULL,
  `SALAIRENET` text DEFAULT NULL,
  `STATUT` int(11) NOT NULL DEFAULT 1,
  `VOL_HORAIRE` text DEFAULT NULL,
  `COUT_HORAIRE` text DEFAULT NULL,
  `IDANNEESCOLAIRE` text DEFAULT NULL,
  `CREATE_ID` text DEFAULT NULL,
  `DELETE_ID` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `note`
--

CREATE TABLE `note` (
  `ID` int(11) NOT NULL,
  `NOTEINT` text NOT NULL,
  `NOTEDS` text NOT NULL,
  `NOTEDN` text NOT NULL,
  `MOYCLASS` text NOT NULL,
  `NOTECOMP` text NOT NULL,
  `MOYENTRIMES` float NOT NULL,
  `COEF` float NOT NULL,
  `MOYENPONDERE` float NOT NULL,
  `RANG` text DEFAULT NULL,
  `IDPROFESSEUR` int(11) NOT NULL,
  `OBSERVATION` text NOT NULL,
  `IDELEVE` int(11) NOT NULL,
  `IDMATIERE` int(11) NOT NULL,
  `IDSALLE` int(11) NOT NULL,
  `IDPOSITION` int(11) NOT NULL,
  `IDANNEESCOLAIRE` int(11) NOT NULL,
  `EST_PUBLIE` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `numero_evaluation`
--

CREATE TABLE `numero_evaluation` (
  `ID` int(11) NOT NULL,
  `IDSALLE` int(11) DEFAULT NULL,
  `IDELEVESALLE` int(11) NOT NULL,
  `IDPOSITION` int(11) NOT NULL,
  `IDANNEESCOLAIRE` int(11) NOT NULL,
  `NUMERO_ANONYMAT` varchar(500) DEFAULT NULL,
  `NUMERO_TABLE` varchar(500) DEFAULT NULL,
  `DATECREATE` date DEFAULT NULL,
  `IDUSERCREATE` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `observation`
--

CREATE TABLE `observation` (
  `ID` int(11) NOT NULL,
  `LIBELLE` text NOT NULL,
  `MOY` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `paiementfrais`
--

CREATE TABLE `paiementfrais` (
  `ID` int(11) NOT NULL,
  `IDPAIEMENTTYPECLASSE` int(11) NOT NULL,
  `IDELEVEANNEESCOLAIRE` int(11) NOT NULL,
  `IDANNEESCOLAIRE` int(11) DEFAULT NULL,
  `MONTANT` int(11) NOT NULL,
  `DATE` date NOT NULL,
  `STATUT` int(11) NOT NULL,
  `IDUSERAJOUT` int(11) NOT NULL,
  `IDUSERDELETE` int(11) NOT NULL,
  `NOMPAYEUR` text NOT NULL,
  `TELPAYEUR` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

--
-- Déchargement des données de la table `paiementfrais`
--

INSERT INTO `paiementfrais` (`ID`, `IDPAIEMENTTYPECLASSE`, `IDELEVEANNEESCOLAIRE`, `IDANNEESCOLAIRE`, `MONTANT`, `DATE`, `STATUT`, `IDUSERAJOUT`, `IDUSERDELETE`, `NOMPAYEUR`, `TELPAYEUR`) VALUES
(1, 8, 1, 1, 70000, '2026-02-16', 1, 103, 0, 'KOWOUVI Pascal', '99680469'),
(2, 19, 2, 1, 45000, '2026-03-23', 1, 103, 0, '', '');

-- --------------------------------------------------------

--
-- Structure de la table `paiementtranche`
--

CREATE TABLE `paiementtranche` (
  `ID` int(11) NOT NULL,
  `LIBELLE` varchar(500) DEFAULT NULL,
  `STATUT` int(11) DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

--
-- Déchargement des données de la table `paiementtranche`
--

INSERT INTO `paiementtranche` (`ID`, `LIBELLE`, `STATUT`) VALUES
(1, '1ère Tranche', 1),
(2, '2ème Tranche', 1),
(3, '3ème Tranche', 1),
(4, '4ème Tranche', 1);

-- --------------------------------------------------------

--
-- Structure de la table `paiementtype`
--

CREATE TABLE `paiementtype` (
  `ID` int(11) NOT NULL,
  `LIBELLE` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

--
-- Déchargement des données de la table `paiementtype`
--

INSERT INTO `paiementtype` (`ID`, `LIBELLE`) VALUES
(1, 'SCOLARITE'),
(2, 'EXAMEN'),
(3, 'INSCRIPTION');

-- --------------------------------------------------------

--
-- Structure de la table `paiementtypeclasse`
--

CREATE TABLE `paiementtypeclasse` (
  `ID` int(11) NOT NULL,
  `IDPAIEMENTTYPE` int(11) NOT NULL,
  `IDCLASSE` int(11) NOT NULL,
  `MONTANT` int(11) NOT NULL,
  `IDANNEESCOLAIRE` int(11) NOT NULL DEFAULT 1,
  `IDELEVEANNEESCOLAIRE` int(11) DEFAULT NULL,
  `REMISE` int(11) DEFAULT NULL,
  `STATUT` int(11) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

--
-- Déchargement des données de la table `paiementtypeclasse`
--

INSERT INTO `paiementtypeclasse` (`ID`, `IDPAIEMENTTYPE`, `IDCLASSE`, `MONTANT`, `IDANNEESCOLAIRE`, `IDELEVEANNEESCOLAIRE`, `REMISE`, `STATUT`) VALUES
(1, 1, 1, 204000, 1, NULL, NULL, 1),
(2, 1, 2, 204000, 1, NULL, NULL, 1),
(3, 1, 3, 204000, 1, NULL, NULL, 1),
(4, 1, 4, 204000, 1, NULL, NULL, 1),
(5, 1, 14, 272000, 1, NULL, NULL, 1),
(7, 1, 20, 272000, 1, NULL, NULL, 1),
(8, 1, 16, 272000, 1, NULL, NULL, 1),
(10, 1, 18, 272000, 1, NULL, NULL, 1),
(11, 1, 17, 272000, 1, NULL, NULL, 1),
(13, 1, 10, 185000, 1, NULL, NULL, 1),
(14, 1, 6, 185000, 1, NULL, NULL, 1),
(15, 1, 12, 185000, 1, NULL, NULL, 1),
(16, 1, 13, 185000, 1, NULL, NULL, 1),
(17, 1, 9, 185000, 1, NULL, NULL, 1),
(18, 1, 8, 185000, 1, NULL, NULL, 1),
(19, 1, 7, 185000, 1, NULL, NULL, 1),
(20, 1, 5, 185000, 1, NULL, NULL, 1),
(21, 1, 26, 300000, 1, NULL, NULL, 1),
(22, 1, 29, 300000, 1, NULL, NULL, 1),
(23, 1, 23, 300000, 1, NULL, NULL, 1);

-- --------------------------------------------------------

--
-- Structure de la table `paiementtypeclassetranche`
--

CREATE TABLE `paiementtypeclassetranche` (
  `ID` int(11) NOT NULL,
  `IDPAIEMENTTYPECLASSE` int(11) DEFAULT NULL,
  `IDPAIEMENTTRANCHE` int(11) DEFAULT NULL,
  `MONTANT` float NOT NULL,
  `DATEECHEANCE` date DEFAULT NULL,
  `STATUT` int(11) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

--
-- Déchargement des données de la table `paiementtypeclassetranche`
--

INSERT INTO `paiementtypeclassetranche` (`ID`, `IDPAIEMENTTYPECLASSE`, `IDPAIEMENTTRANCHE`, `MONTANT`, `DATEECHEANCE`, `STATUT`) VALUES
(1, 1, 1, 80000, '2025-09-15', 1),
(2, 1, 2, 60000, '2025-11-30', 1),
(3, 1, 3, 44000, '2026-01-31', 1),
(4, 1, 4, 20000, '2026-02-28', 1),
(5, 2, 1, 80000, '2025-09-15', 1),
(6, 2, 2, 60000, '2025-11-30', 1),
(7, 2, 3, 44000, '2026-01-31', 1),
(8, 2, 4, 20000, '2026-02-28', 1),
(9, 3, 1, 80000, '2025-09-15', 1),
(10, 3, 2, 60000, '2025-11-30', 1),
(11, 3, 3, 44000, '2026-01-31', 1),
(12, 3, 4, 20000, '2026-02-28', 1),
(13, 4, 1, 80000, '2025-09-15', 1),
(14, 4, 2, 60000, '2025-11-30', 1),
(15, 4, 3, 44000, '2026-01-31', 1),
(16, 4, 4, 20000, '2026-02-28', 1),
(17, 5, 1, 100000, '2025-09-15', 1),
(18, 5, 2, 80000, '2025-11-30', 1),
(19, 5, 3, 70000, '2026-01-31', 1),
(20, 5, 4, 22000, '2026-02-28', 1),
(21, 6, 1, 100000, '2025-09-15', 1),
(22, 6, 2, 80000, '2025-11-30', 1),
(23, 6, 3, 70000, '2026-01-31', 1),
(24, 6, 4, 22000, '2026-02-28', 1),
(25, 7, 1, 100000, '2025-09-15', 1),
(26, 7, 2, 80000, '2025-11-30', 1),
(27, 7, 3, 70000, '2026-01-31', 1),
(28, 7, 4, 22000, '2026-02-28', 1),
(29, 8, 1, 100000, '2025-09-15', 1),
(30, 8, 2, 80000, '2025-11-30', 1),
(31, 8, 3, 70000, '2026-01-31', 1),
(32, 8, 4, 22000, '2026-02-28', 1),
(33, 9, 1, 100000, '2025-09-15', 1),
(34, 9, 2, 80000, '2025-11-30', 1),
(35, 9, 3, 70000, '2026-01-31', 1),
(36, 9, 4, 22000, '2026-02-28', 1),
(37, 10, 1, 100000, '2025-09-15', 1),
(38, 10, 2, 80000, '2025-11-30', 1),
(39, 10, 3, 70000, '2026-01-31', 1),
(40, 10, 4, 22000, '2026-02-28', 1),
(41, 11, 1, 100000, '2025-09-15', 1),
(42, 11, 2, 80000, '2025-11-30', 1),
(43, 11, 3, 70000, '2026-01-31', 1),
(44, 11, 4, 22000, '2026-02-28', 1),
(45, 12, 1, 100000, '2025-09-15', 1),
(46, 12, 2, 80000, '2025-11-30', 1),
(47, 12, 3, 70000, '2026-01-31', 1),
(48, 12, 4, 22000, '2026-02-28', 1),
(49, 13, 1, 70000, '2025-09-15', 1),
(50, 13, 2, 55000, '2025-11-30', 1),
(51, 13, 3, 40000, '2026-01-31', 1),
(52, 13, 4, 20000, '2026-02-28', 1),
(53, 14, 1, 70000, '2025-09-15', 1),
(54, 14, 2, 55000, '2025-01-31', 1),
(55, 14, 3, 40000, '2026-01-31', 1),
(56, 14, 4, 20000, '2026-02-28', 1),
(57, 15, 1, 70000, '2025-09-15', 1),
(58, 15, 2, 55000, '2025-11-30', 1),
(59, 15, 3, 40000, '2026-01-31', 1),
(60, 15, 4, 20000, '2026-02-28', 1),
(61, 16, 1, 70000, '2025-09-15', 1),
(62, 16, 2, 55000, '2025-11-30', 1),
(63, 16, 3, 40000, '2026-01-31', 1),
(64, 16, 4, 20000, '2026-02-28', 1),
(65, 17, 1, 70000, '2025-09-15', 1),
(66, 17, 2, 55000, '2025-11-30', 1),
(67, 17, 3, 40000, '2026-01-31', 1),
(68, 17, 4, 20000, '2026-02-28', 1),
(69, 18, 1, 70000, '2025-09-15', 1),
(70, 18, 2, 55000, '2025-11-30', 1),
(71, 18, 3, 40000, '2026-01-31', 1),
(72, 18, 4, 20000, '2026-02-28', 1),
(73, 19, 1, 70000, '2025-09-15', 1),
(74, 19, 2, 55000, '2025-11-30', 1),
(75, 19, 3, 40000, '2026-01-31', 1),
(76, 19, 4, 20000, '2026-02-28', 1),
(77, 20, 1, 70000, '2025-09-15', 1),
(78, 20, 2, 55000, '2025-11-30', 1),
(79, 20, 3, 40000, '2026-01-31', 1),
(80, 20, 4, 20000, '2026-02-28', 1),
(81, 21, 1, 100000, '2025-10-20', 1),
(82, 21, 2, 100000, '2025-12-10', 1),
(83, 21, 3, 50000, '2025-02-10', 1),
(84, 21, 4, 50000, '2025-03-31', 1),
(85, 22, 1, 100000, '2025-10-20', 1),
(86, 22, 2, 100000, '2025-12-10', 1),
(87, 22, 3, 50000, '2025-02-10', 1),
(88, 22, 4, 50000, '2025-03-31', 1),
(89, 23, 1, 100000, '2025-10-20', 1),
(90, 23, 2, 100000, '2025-12-10', 1),
(91, 23, 3, 50000, '2025-02-10', 1),
(92, 23, 4, 50000, '2025-03-31', 1);

-- --------------------------------------------------------

--
-- Structure de la table `parents`
--

CREATE TABLE `parents` (
  `ID_PARENT` int(11) NOT NULL,
  `NOM_PARENT` varchar(255) NOT NULL,
  `PRENOM_PARENT` varchar(255) NOT NULL,
  `SEXE_PARENT` varchar(50) DEFAULT NULL,
  `TEL_PARENT` varchar(50) NOT NULL,
  `MAIL_PARENT` varchar(255) DEFAULT NULL,
  `LOGIN_PARENT` varchar(100) NOT NULL,
  `MTPASS_PARENT` varchar(255) NOT NULL,
  `STATUT_PARENT` int(11) NOT NULL DEFAULT 0 COMMENT '0 = en attente, 1 = actif',
  `GOOGLE_SUB` varchar(255) DEFAULT NULL COMMENT 'Identifiant Google OAuth',
  `DATE_CREATION` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `parent_eleve`
--

CREATE TABLE `parent_eleve` (
  `ID` int(11) NOT NULL,
  `ID_PARENT` int(11) NOT NULL,
  `ID_ELEVE` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `permission`
--

CREATE TABLE `permission` (
  `ID` int(11) NOT NULL,
  `IDPROD` int(11) DEFAULT NULL,
  `DATEDEMANDE` date DEFAULT NULL,
  `DATEDEBUT` date DEFAULT NULL,
  `DATEFIN` date DEFAULT NULL,
  `MOTIF` text DEFAULT NULL,
  `STATUT` varchar(500) DEFAULT NULL,
  `FICHIER` text DEFAULT NULL,
  `IDUSERCREATE` int(11) DEFAULT NULL,
  `DATECREATE` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `piece`
--

CREATE TABLE `piece` (
  `ID` int(11) NOT NULL,
  `NOM` text DEFAULT NULL,
  `STATUT` int(11) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

--
-- Déchargement des données de la table `piece`
--

INSERT INTO `piece` (`ID`, `NOM`, `STATUT`) VALUES
(1, 'Copie simple et copie légalisée de l\'acte de naissance ou toute autre pièce tenant lieu', 1),
(2, 'Livret scolaire et/ou copie simple des relevés de note des deux (02) années précédentes', 1),
(3, 'Attestation de scolarité', 1),
(4, 'Copie légalisée de l\'attestation du CEPD et copie simple du relevé pour l\'entréeau lycée', 1),
(5, 'Copie légalisée de l\'attestation du BEPC et copie simple du relevé pour l\'entrée au Lycée', 1),
(6, 'Copie légalisée de l\'attestation du BAC I et copie simple du relevé pour l\'entrée en Terminale', 1);

-- --------------------------------------------------------

--
-- Structure de la table `pieceeleveanneescolaire`
--

CREATE TABLE `pieceeleveanneescolaire` (
  `ID` int(11) NOT NULL,
  `IDPIECE` int(11) NOT NULL,
  `IDELEVEANNEESCOLAIRE` int(11) NOT NULL,
  `NOMPIECE` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `position`
--

CREATE TABLE `position` (
  `idposition` int(11) NOT NULL,
  `codeposition` varchar(100) DEFAULT NULL,
  `libposition` text NOT NULL,
  `datecreate` date DEFAULT NULL,
  `create_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

--
-- Déchargement des données de la table `position`
--

INSERT INTO `position` (`idposition`, `codeposition`, `libposition`, `datecreate`, `create_id`) VALUES
(1, '1-Trim', 'Premier Trimestre', NULL, NULL),
(2, '2-Trim', 'Deuxième Trimestre', NULL, NULL),
(3, '3-Trim', 'Troisième Trimestre', NULL, NULL),
(6, 'Ex-10-25', 'Examen Blanc Octobre 2025', '2025-10-18', 15),
(7, 'Ex-12-25', 'Examen Blanc Décembre 2025', '2025-12-06', 200),
(8, 'Ex-11-25', 'Examen Blanc Novembre 2025', '2025-12-09', 200),
(9, 'Ex-01-26', 'Examen Blanc Janvier 2026', '2026-01-21', 200);

-- --------------------------------------------------------

--
-- Structure de la table `pret`
--

CREATE TABLE `pret` (
  `ID` int(11) NOT NULL,
  `IDANNEESCOLAIRE` int(11) DEFAULT NULL,
  `IDPERSONNEL` int(11) DEFAULT NULL,
  `LIBELLE` text DEFAULT NULL,
  `MONTANTPRET` float DEFAULT NULL,
  `MONTANTPRELEVE` float DEFAULT NULL,
  `DEBUT` date DEFAULT NULL,
  `FIN` date DEFAULT NULL,
  `DATEOPERATION` date DEFAULT NULL,
  `DATESAISIE` datetime DEFAULT NULL,
  `FICHIER` text DEFAULT NULL,
  `CREATE_ID` int(11) DEFAULT NULL,
  `STATUT` int(11) DEFAULT NULL,
  `IDCOMPTE` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Structure de la table `pretremboursement`
--

CREATE TABLE `pretremboursement` (
  `ID` int(11) NOT NULL,
  `IDPRET` int(11) DEFAULT NULL,
  `MOIS` int(11) DEFAULT NULL,
  `ANNEE` int(11) DEFAULT NULL,
  `MONTANT_PRELEVER` int(11) DEFAULT NULL,
  `MONTANT_REMBOURSER` int(11) DEFAULT NULL,
  `DATEOPERATION` date DEFAULT NULL,
  `IDANNEESCOLAIRE` int(11) DEFAULT NULL,
  `IDCOMPTE` int(11) DEFAULT NULL,
  `FICHIER` text DEFAULT NULL,
  `CREATE_ID` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Structure de la table `professeur`
--

CREATE TABLE `professeur` (
  `ID` int(11) NOT NULL,
  `NOM` text DEFAULT NULL,
  `TITRE` text DEFAULT NULL,
  `CONTACT` text DEFAULT NULL,
  `SIGNATURE` text DEFAULT NULL,
  `STATUT` int(11) NOT NULL DEFAULT 1,
  `IDANNEESCOLAIRE` int(11) NOT NULL DEFAULT 1,
  `CORPS` int(11) NOT NULL DEFAULT 1,
  `DATEEMBAUCHE` date DEFAULT NULL,
  `DATENAISSANCE` date DEFAULT NULL,
  `PERSONNEACHARGE` int(11) DEFAULT NULL,
  `LIEUNAISSANCE` text DEFAULT NULL,
  `NUMCNSS` text DEFAULT NULL,
  `NUMCOMPTEBANCAIRE` text DEFAULT NULL,
  `IDBANQUE` int(11) DEFAULT NULL,
  `DEBUTCONTRAT` date DEFAULT NULL,
  `FINCONTRAT` date DEFAULT NULL,
  `MODEPAIEMENT` int(11) DEFAULT NULL,
  `CREATE_ID` int(11) DEFAULT NULL,
  `DELETE_ID` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

--
-- Déchargement des données de la table `professeur`
--

INSERT INTO `professeur` (`ID`, `NOM`, `TITRE`, `CONTACT`, `SIGNATURE`, `STATUT`, `IDANNEESCOLAIRE`, `CORPS`, `DATEEMBAUCHE`, `DATENAISSANCE`, `PERSONNEACHARGE`, `LIEUNAISSANCE`, `NUMCNSS`, `NUMCOMPTEBANCAIRE`, `IDBANQUE`, `DEBUTCONTRAT`, `FINCONTRAT`, `MODEPAIEMENT`, `CREATE_ID`, `DELETE_ID`) VALUES
(1, 'AGODJI Jacob', '4', '90000025', 'Signature.png', 1, 1, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(2, 'AGBESSI Yao Christian', '4', '90000000', '', 1, 1, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Structure de la table `professeurdonneepaie`
--

CREATE TABLE `professeurdonneepaie` (
  `ID` int(11) NOT NULL,
  `IDPERS` int(11) DEFAULT NULL,
  `SALAIREBASE` text DEFAULT NULL,
  `SURSALAIRE` text DEFAULT NULL,
  `INDEMNITEFONCTION` text DEFAULT NULL,
  `PRIMESUJETION` text DEFAULT NULL,
  `PRIMEINTERIM` text DEFAULT NULL,
  `INDEMNITELOGEMENT` text DEFAULT NULL,
  `INDEMNITETRANSPORT` text DEFAULT NULL,
  `PRIMECAISSE` text DEFAULT NULL,
  `ALLOCATIONFAMI` text DEFAULT NULL,
  `SALAIREBRUTE` text DEFAULT NULL,
  `STATUT` text DEFAULT NULL,
  `VOL_HORAIRE` int(11) DEFAULT NULL,
  `COUT_HONORAIRE` int(11) DEFAULT NULL,
  `IDANNEESCOLAIRE` int(11) DEFAULT NULL,
  `CREATE_ID` int(11) DEFAULT NULL,
  `DELETE_ID` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

--
-- Déchargement des données de la table `professeurdonneepaie`
--

INSERT INTO `professeurdonneepaie` (`ID`, `IDPERS`, `SALAIREBASE`, `SURSALAIRE`, `INDEMNITEFONCTION`, `PRIMESUJETION`, `PRIMEINTERIM`, `INDEMNITELOGEMENT`, `INDEMNITETRANSPORT`, `PRIMECAISSE`, `ALLOCATIONFAMI`, `SALAIREBRUTE`, `STATUT`, `VOL_HORAIRE`, `COUT_HONORAIRE`, `IDANNEESCOLAIRE`, `CREATE_ID`, `DELETE_ID`) VALUES
(1, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '1', NULL, 3000, 1, 1774264514, NULL);

-- --------------------------------------------------------

--
-- Structure de la table `professeursallemat`
--

CREATE TABLE `professeursallemat` (
  `ID` int(11) NOT NULL,
  `IDPROF` int(11) NOT NULL,
  `IDSALLE` int(11) NOT NULL,
  `IDMAT` int(11) NOT NULL,
  `IDANNEESCOLAIRE` int(11) NOT NULL,
  `IDTITRE` int(11) NOT NULL,
  `STATUT` int(11) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

--
-- Déchargement des données de la table `professeursallemat`
--

INSERT INTO `professeursallemat` (`ID`, `IDPROF`, `IDSALLE`, `IDMAT`, `IDANNEESCOLAIRE`, `IDTITRE`, `STATUT`) VALUES
(1, 1, 1, 6, 1, 4, 1),
(2, 1, 2, 7, 1, 4, 1),
(3, 1, 2, 6, 1, 4, 1),
(4, 2, 15, 24, 1, 1, 1);

-- --------------------------------------------------------

--
-- Structure de la table `professeurtitre`
--

CREATE TABLE `professeurtitre` (
  `ID` int(11) NOT NULL,
  `NOM` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

--
-- Déchargement des données de la table `professeurtitre`
--

INSERT INTO `professeurtitre` (`ID`, `NOM`) VALUES
(1, 'Proviseur'),
(2, 'Professeur Titulaire'),
(3, 'Surveillant'),
(4, 'Professeur'),
(5, 'Secretaire'),
(6, 'Autre');

-- --------------------------------------------------------

--
-- Structure de la table `salle`
--

CREATE TABLE `salle` (
  `IDCLASSE` int(11) NOT NULL,
  `ID` int(11) NOT NULL,
  `CODESALLE` text NOT NULL,
  `NOMSALLE` text NOT NULL,
  `PRIORITE` int(11) DEFAULT NULL,
  `STATUT` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

--
-- Déchargement des données de la table `salle`
--

INSERT INTO `salle` (`IDCLASSE`, `ID`, `CODESALLE`, `NOMSALLE`, `PRIORITE`, `STATUT`) VALUES
(1, 1, '6ème', '6ème', 1, 1),
(2, 2, '5ème', '5ème', 1, 1),
(3, 3, '4ème', '4ème', 1, 1),
(4, 4, '3ème', '3ème', 1, 1),
(5, 5, 'CM2', 'CM2', 1, 1),
(6, 6, 'CM1', 'CM1', 1, 1),
(7, 7, 'CE2', 'CE2', 1, 1),
(8, 8, 'CE1', 'CE1', 1, 1),
(9, 9, 'CP2', 'CP2', 1, 1),
(10, 10, 'CP1', 'CP1', 1, 1),
(13, 12, 'CI 2', 'CI 2', 1, 1),
(14, 13, '2nd S2', '2nd S2', 1, 1),
(15, 14, '1ère A4', '1ère A4', 1, 1),
(16, 15, '1ère D', '1ère D', 1, 1),
(17, 16, 'Tle C4', 'Tle C4', 1, 1),
(18, 17, 'Tle D', 'Tle D', 1, 1),
(19, 18, '2nd A4', '2nd A4', 1, 1),
(20, 19, '1ère C4', '1ère C4', 1, 1),
(21, 20, 'Tle A4', 'Tle A4', 1, 1),
(12, 21, 'CI 1', 'CI 1', 1, 1),
(14, 22, '2nde S1', '2nde S1', NULL, 0);

-- --------------------------------------------------------

--
-- Structure de la table `salleemploitemps`
--

CREATE TABLE `salleemploitemps` (
  `ID` int(11) NOT NULL,
  `IDJOUR` int(11) DEFAULT NULL,
  `IDSALLE` int(11) DEFAULT NULL,
  `IDANNEESCOLAIRE` int(11) DEFAULT NULL,
  `HEUREDEBUT` text DEFAULT NULL,
  `HEUREFIN` text DEFAULT NULL,
  `IDMATIERE` int(11) DEFAULT NULL,
  `IDPROF` int(11) DEFAULT NULL,
  `CREATE_ID` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Structure de la table `soustypeoperation`
--

CREATE TABLE `soustypeoperation` (
  `ID` int(11) NOT NULL,
  `LIBELLE` text DEFAULT NULL,
  `IDTYPE` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

--
-- Déchargement des données de la table `soustypeoperation`
--

INSERT INTO `soustypeoperation` (`ID`, `LIBELLE`, `IDTYPE`) VALUES
(1, 'Recette des frais de scolarité', 1),
(2, 'Autres', 1),
(3, 'Salaire', 2),
(4, 'Prêt', 2),
(5, 'Electricité', 2),
(6, 'Autres', 2);

-- --------------------------------------------------------

--
-- Structure de la table `typefrais`
--

CREATE TABLE `typefrais` (
  `ID_TYPEFRAIS` int(11) NOT NULL,
  `NOM_TYPEFRAIS` text DEFAULT NULL,
  `DESCRIPTION_TYPEFRAIS` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `utilisateur`
--

CREATE TABLE `utilisateur` (
  `ID` int(11) NOT NULL,
  `NOM_USER` text NOT NULL,
  `PRENOM_USER` text NOT NULL,
  `LOGIN_USER` text NOT NULL,
  `MTPASS_USER` text NOT NULL,
  `PROFIL` text NOT NULL,
  `STATUT` int(11) NOT NULL DEFAULT 1,
  `TYPE` varchar(5) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

--
-- Déchargement des données de la table `utilisateur`
--

INSERT INTO `utilisateur` (`ID`, `NOM_USER`, `PRENOM_USER`, `LOGIN_USER`, `MTPASS_USER`, `PROFIL`, `STATUT`, `TYPE`) VALUES
(102, 'KOWU', 'Michel', 'mkowu', 'mkowu@ecoleplus.tg', 'Administrateur', 1, 'Non'),
(103, 'Demo', 'Demo', 'demo@ecoleplus.tg', 'Demo@2026', 'Administrateur', 1, 'Non'),
(1774264514, 'AZIAGBE', 'Kenneth', 'kaziagbe', '90372347', 'Directeur', 1, 'Non'),
(1782387349, 'DOGBEDA', 'Yaovi', 'dogbeda@gmail.com', '12345678', 'Administrateur', 1, 'Non');

-- --------------------------------------------------------

--
-- Structure de la table `msg_conversations`
--

CREATE TABLE `msg_conversations` (
  `ID` int(11) NOT NULL,
  `TITRE` varchar(255) DEFAULT NULL,
  `TYPE_CONV` varchar(50) NOT NULL,
  `DATE_CREATION` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `msg_messages`
--

CREATE TABLE `msg_messages` (
  `ID` int(11) NOT NULL,
  `ID_CONVERSATION` int(11) NOT NULL,
  `EXPEDITEUR_TYPE` varchar(50) NOT NULL,
  `ID_EXPEDITEUR` int(11) NOT NULL,
  `CONTENU` text NOT NULL,
  `DATE_ENVOI` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `msg_participants`
--

CREATE TABLE `msg_participants` (
  `ID_CONVERSATION` int(11) NOT NULL,
  `USER_TYPE` varchar(50) NOT NULL,
  `ID_USER` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `msg_statuts_lecture`
--

CREATE TABLE `msg_statuts_lecture` (
  `ID_MESSAGE` int(11) NOT NULL,
  `LECTEUR_TYPE` varchar(50) NOT NULL,
  `ID_LECTEUR` int(11) NOT NULL,
  `DATE_LECTURE` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `msg_pieces_jointes`
--

CREATE TABLE `msg_pieces_jointes` (
  `ID` int(11) NOT NULL,
  `ID_MESSAGE` int(11) NOT NULL,
  `NOM_FICHIER` varchar(255) NOT NULL,
  `CHEMIN_URL` varchar(500) NOT NULL,
  `TYPE_MIME` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `sessions_parents`
--

CREATE TABLE `sessions_parents` (
  `id` varchar(128) NOT NULL,
  `parent_id` int(10) UNSIGNED DEFAULT NULL,
  `ip_address` varchar(45) NOT NULL DEFAULT '',
  `user_agent` text NOT NULL,
  `payload` longtext NOT NULL,
  `last_activity` int(10) UNSIGNED NOT NULL,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `password_resets_parents`
--

CREATE TABLE `password_resets_parents` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `parent_id` int(10) UNSIGNED NOT NULL,
  `token_hash` char(64) NOT NULL,
  `expires_at` datetime NOT NULL,
  `used_at` datetime DEFAULT NULL,
  `requested_at` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `bulletin_calcule`
--

CREATE TABLE `bulletin_calcule` (
  `ID` int(10) UNSIGNED NOT NULL,
  `IDELEVE` int(11) NOT NULL,
  `IDSALLE` int(11) NOT NULL,
  `IDANNEESCOLAIRE` int(11) NOT NULL,
  `IDPOSITION` int(11) NOT NULL,
  `MOYENNE_GENERALE` decimal(5,2) NOT NULL DEFAULT 0.00,
  `RANG_CLASSE` int(10) UNSIGNED NOT NULL DEFAULT 0,
  `TOTAL_POINTS` decimal(10,2) NOT NULL DEFAULT 0.00,
  `TOTAL_COEFS` decimal(5,2) NOT NULL DEFAULT 0.00,
  `NOMBRE_MATIERES` int(10) UNSIGNED NOT NULL DEFAULT 0,
  `DATE_CALCUL` datetime NOT NULL DEFAULT current_timestamp(),
  `EST_GELE` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `otp_codes`
--

CREATE TABLE `otp_codes` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `parent_id` int(11) NOT NULL,
  `type` varchar(20) NOT NULL COMMENT 'login|registration|password_reset',
  `code_hash` char(64) NOT NULL COMMENT 'SHA-256 du code OTP',
  `email` varchar(255) DEFAULT NULL,
  `telephone` varchar(50) DEFAULT NULL,
  `expires_at` datetime NOT NULL,
  `attempts` int(11) NOT NULL DEFAULT 0,
  `used_at` datetime DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `ip_address` varchar(45) NOT NULL DEFAULT ''
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `parent_invitations`
--

CREATE TABLE `parent_invitations` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `eleve_id` int(11) NOT NULL,
  `email` varchar(255) NOT NULL,
  `telephone` varchar(50) NOT NULL,
  `token_hash` char(64) NOT NULL COMMENT 'SHA-256 du token',
  `token_plain` varchar(128) NOT NULL COMMENT 'Token en clair (pour URL)',
  `expires_at` datetime NOT NULL,
  `used_at` datetime DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Index pour les tables déchargées
--

--
-- Index pour la table `absences`
--
ALTER TABLE `absences`
  ADD PRIMARY KEY (`ID`);

--
-- Index pour la table `anneescolaire`
--
ALTER TABLE `anneescolaire`
  ADD PRIMARY KEY (`ID`);

--
-- Index pour la table `annonces_ecole`
--
ALTER TABLE `annonces_ecole`
  ADD PRIMARY KEY (`ID`);

--
-- Index pour la table `article`
--
ALTER TABLE `article`
  ADD PRIMARY KEY (`ID`);

--
-- Index pour la table `articlecategorie`
--
ALTER TABLE `articlecategorie`
  ADD PRIMARY KEY (`ID`);

--
-- Index pour la table `articleentree`
--
ALTER TABLE `articleentree`
  ADD PRIMARY KEY (`ID`);

--
-- Index pour la table `articlesortie`
--
ALTER TABLE `articlesortie`
  ADD PRIMARY KEY (`ID`);

--
-- Index pour la table `articlesortie_article`
--
ALTER TABLE `articlesortie_article`
  ADD PRIMARY KEY (`ID`);

--
-- Index pour la table `banque`
--
ALTER TABLE `banque`
  ADD PRIMARY KEY (`ID`);

--
-- Index pour la table `baremeirpp`
--
ALTER TABLE `baremeirpp`
  ADD PRIMARY KEY (`ID`);

--
-- Index pour la table `bulletin`
--
ALTER TABLE `bulletin`
  ADD PRIMARY KEY (`ID`),
  ADD KEY `IDPOSITION` (`IDPOSITION`,`IDELEVE`,`IDANNEESCOLAIRE`,`IDSALLE`);

--
-- Index pour la table `bulletincontenu`
--
ALTER TABLE `bulletincontenu`
  ADD PRIMARY KEY (`ID`),
  ADD KEY `IDBULLETIN` (`IDBULLETIN`);

--
-- Index pour la table `cahier_texte`
--
ALTER TABLE `cahier_texte`
  ADD PRIMARY KEY (`ID`),
  ADD KEY `FK_CAHIER_SALLE` (`IDSALLE`),
  ADD KEY `FK_CAHIER_MATIERE` (`IDMATIERE`),
  ADD KEY `FK_CAHIER_PROF` (`IDPROF`),
  ADD KEY `FK_CAHIER_ANNEE` (`IDANNEESCOLAIRE`);

--
-- Index pour la table `caisse`
--
ALTER TABLE `caisse`
  ADD PRIMARY KEY (`ID`);

--
-- Index pour la table `caissepaiement`
--
ALTER TABLE `caissepaiement`
  ADD PRIMARY KEY (`ID`);

--
-- Index pour la table `classe`
--
ALTER TABLE `classe`
  ADD PRIMARY KEY (`IDCLASSE`);

--
-- Index pour la table `compte`
--
ALTER TABLE `compte`
  ADD PRIMARY KEY (`ID`);

--
-- Index pour la table `decision`
--
ALTER TABLE `decision`
  ADD PRIMARY KEY (`ID`);

--
-- Index pour la table `decisionmoyennereussite`
--
ALTER TABLE `decisionmoyennereussite`
  ADD PRIMARY KEY (`ID`);

--
-- Index pour la table `decisionrapport`
--
ALTER TABLE `decisionrapport`
  ADD PRIMARY KEY (`ID`);

--
-- Index pour la table `domaine`
--
ALTER TABLE `domaine`
  ADD PRIMARY KEY (`ID`);

--
-- Index pour la table `eleve`
--
ALTER TABLE `eleve`
  ADD PRIMARY KEY (`ID_ELEVE`);

--
-- Index pour la table `eleveanneescolaire`
--
ALTER TABLE `eleveanneescolaire`
  ADD PRIMARY KEY (`ID`),
  ADD KEY `IDELEVE` (`IDELEVE`),
  ADD KEY `IDANNEESCOLAIRE` (`IDANNEESCOLAIRE`),
  ADD KEY `IDCLASSE` (`IDCLASSE`);

--
-- Index pour la table `elevesalle`
--
ALTER TABLE `elevesalle`
  ADD PRIMARY KEY (`ID`),
  ADD KEY `idsalle` (`IDSALLE`),
  ADD KEY `idsalle_2` (`IDSALLE`),
  ADD KEY `IDELEVE` (`IDELEVE`);

--
-- Index pour la table `elevestatutclasse`
--
ALTER TABLE `elevestatutclasse`
  ADD PRIMARY KEY (`ID`);

--
-- Index pour la table `elevestatutetablissement`
--
ALTER TABLE `elevestatutetablissement`
  ADD PRIMARY KEY (`ID`);

--
-- Index pour la table `entreesortie`
--
ALTER TABLE `entreesortie`
  ADD PRIMARY KEY (`ID`);

--
-- Index pour la table `envoimail`
--
ALTER TABLE `envoimail`
  ADD PRIMARY KEY (`ID`);

--
-- Index pour la table `envoimaildetail`
--
ALTER TABLE `envoimaildetail`
  ADD PRIMARY KEY (`ID`);

--
-- Index pour la table `envoisms`
--
ALTER TABLE `envoisms`
  ADD PRIMARY KEY (`ID`);

--
-- Index pour la table `envoismsdetail`
--
ALTER TABLE `envoismsdetail`
  ADD PRIMARY KEY (`ID`);

--
-- Index pour la table `etat`
--
ALTER TABLE `etat`
  ADD PRIMARY KEY (`ID`);

--
-- Index pour la table `horairesaisienote`
--
ALTER TABLE `horairesaisienote`
  ADD PRIMARY KEY (`ID`);

--
-- Index pour la table `jour`
--
ALTER TABLE `jour`
  ADD PRIMARY KEY (`ID`);

--
-- Index pour la table `journalisation`
--
ALTER TABLE `journalisation`
  ADD PRIMARY KEY (`ID`);

--
-- Index pour la table `matiere`
--
ALTER TABLE `matiere`
  ADD PRIMARY KEY (`ID_MATIERE`);

--
-- Index pour la table `matierecoefficient`
--
ALTER TABLE `matierecoefficient`
  ADD PRIMARY KEY (`ID`),
  ADD KEY `idmatiere` (`IDMATIERE`,`IDCLASSE`),
  ADD KEY `IDCLASSE` (`IDCLASSE`);

--
-- Index pour la table `messagerie_portail`
--
ALTER TABLE `messagerie_portail`
  ADD PRIMARY KEY (`ID`);

--
-- Index pour la table `modepaiement`
--
ALTER TABLE `modepaiement`
  ADD PRIMARY KEY (`ID`);

--
-- Index pour la table `mois`
--
ALTER TABLE `mois`
  ADD PRIMARY KEY (`ID`);

--
-- Index pour la table `moissalaire`
--
ALTER TABLE `moissalaire`
  ADD PRIMARY KEY (`ID`);

--
-- Index pour la table `note`
--
ALTER TABLE `note`
  ADD PRIMARY KEY (`ID`),
  ADD KEY `ideleve` (`IDELEVE`,`IDMATIERE`),
  ADD KEY `idevaluation` (`IDMATIERE`),
  ADD KEY `IDELEVE_2` (`IDELEVE`),
  ADD KEY `IDMATIERE` (`IDMATIERE`),
  ADD KEY `IDPOSITION` (`IDPOSITION`);

--
-- Index pour la table `numero_evaluation`
--
ALTER TABLE `numero_evaluation`
  ADD PRIMARY KEY (`ID`),
  ADD KEY `IDELEVESALLE` (`IDELEVESALLE`),
  ADD KEY `IDPOSITION` (`IDPOSITION`),
  ADD KEY `IDANNEESCOLAIRE` (`IDANNEESCOLAIRE`),
  ADD KEY `IDUSERCREATE` (`IDUSERCREATE`),
  ADD KEY `IDSALLE` (`IDSALLE`);

--
-- Index pour la table `observation`
--
ALTER TABLE `observation`
  ADD PRIMARY KEY (`ID`);

--
-- Index pour la table `paiementfrais`
--
ALTER TABLE `paiementfrais`
  ADD PRIMARY KEY (`ID`);

--
-- Index pour la table `paiementtranche`
--
ALTER TABLE `paiementtranche`
  ADD PRIMARY KEY (`ID`);

--
-- Index pour la table `paiementtype`
--
ALTER TABLE `paiementtype`
  ADD PRIMARY KEY (`ID`);

--
-- Index pour la table `paiementtypeclasse`
--
ALTER TABLE `paiementtypeclasse`
  ADD PRIMARY KEY (`ID`);

--
-- Index pour la table `paiementtypeclassetranche`
--
ALTER TABLE `paiementtypeclassetranche`
  ADD PRIMARY KEY (`ID`);

--
-- Index pour la table `parents`
--
ALTER TABLE `parents`
  ADD PRIMARY KEY (`ID_PARENT`),
  ADD UNIQUE KEY `uq_parents_login` (`LOGIN_PARENT`),
  ADD UNIQUE KEY `uq_parents_google_sub` (`GOOGLE_SUB`),
  ADD KEY `idx_parents_statut` (`STATUT_PARENT`);

--
-- Index pour la table `parent_eleve`
--
ALTER TABLE `parent_eleve`
  ADD PRIMARY KEY (`ID`),
  ADD UNIQUE KEY `UNQ_PARENT_ELEVE` (`ID_PARENT`,`ID_ELEVE`),
  ADD KEY `FK_PARENTELEVE_ELEVE` (`ID_ELEVE`);

--
-- Index pour la table `permission`
--
ALTER TABLE `permission`
  ADD PRIMARY KEY (`ID`);

--
-- Index pour la table `piece`
--
ALTER TABLE `piece`
  ADD PRIMARY KEY (`ID`);

--
-- Index pour la table `pieceeleveanneescolaire`
--
ALTER TABLE `pieceeleveanneescolaire`
  ADD PRIMARY KEY (`ID`),
  ADD KEY `IDPIECE` (`IDPIECE`),
  ADD KEY `IDELEVEANNEESCOLAIRE` (`IDELEVEANNEESCOLAIRE`);

--
-- Index pour la table `position`
--
ALTER TABLE `position`
  ADD PRIMARY KEY (`idposition`);

--
-- Index pour la table `pret`
--
ALTER TABLE `pret`
  ADD PRIMARY KEY (`ID`),
  ADD KEY `IDANNEESCOLAIRE` (`IDANNEESCOLAIRE`),
  ADD KEY `IDPERSONNEL` (`IDPERSONNEL`),
  ADD KEY `IDCOMPTE` (`IDCOMPTE`);

--
-- Index pour la table `pretremboursement`
--
ALTER TABLE `pretremboursement`
  ADD PRIMARY KEY (`ID`),
  ADD KEY `IDPRET` (`IDPRET`);

--
-- Index pour la table `professeur`
--
ALTER TABLE `professeur`
  ADD PRIMARY KEY (`ID`);

--
-- Index pour la table `professeurdonneepaie`
--
ALTER TABLE `professeurdonneepaie`
  ADD PRIMARY KEY (`ID`);

--
-- Index pour la table `professeursallemat`
--
ALTER TABLE `professeursallemat`
  ADD PRIMARY KEY (`ID`),
  ADD KEY `IDEXERCICE` (`IDPROF`,`IDSALLE`,`IDMAT`),
  ADD KEY `IDPROF` (`IDPROF`),
  ADD KEY `IDSALLE` (`IDSALLE`),
  ADD KEY `IDMAT` (`IDMAT`);

--
-- Index pour la table `professeurtitre`
--
ALTER TABLE `professeurtitre`
  ADD PRIMARY KEY (`ID`);

--
-- Index pour la table `salle`
--
ALTER TABLE `salle`
  ADD PRIMARY KEY (`ID`),
  ADD KEY `idclasse` (`IDCLASSE`);

--
-- Index pour la table `salleemploitemps`
--
ALTER TABLE `salleemploitemps`
  ADD PRIMARY KEY (`ID`);

--
-- Index pour la table `soustypeoperation`
--
ALTER TABLE `soustypeoperation`
  ADD PRIMARY KEY (`ID`);

--
-- Index pour la table `typefrais`
--
ALTER TABLE `typefrais`
  ADD PRIMARY KEY (`ID_TYPEFRAIS`);

--
-- Index pour la table `utilisateur`
--
ALTER TABLE `utilisateur`
  ADD PRIMARY KEY (`ID`);

--
-- AUTO_INCREMENT pour les tables déchargées
--

--
-- AUTO_INCREMENT pour la table `absences`
--
ALTER TABLE `absences`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT pour la table `anneescolaire`
--
ALTER TABLE `anneescolaire`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT pour la table `annonces_ecole`
--
ALTER TABLE `annonces_ecole`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `article`
--
ALTER TABLE `article`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `articlecategorie`
--
ALTER TABLE `articlecategorie`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `articleentree`
--
ALTER TABLE `articleentree`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `articlesortie`
--
ALTER TABLE `articlesortie`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `articlesortie_article`
--
ALTER TABLE `articlesortie_article`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `banque`
--
ALTER TABLE `banque`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `baremeirpp`
--
ALTER TABLE `baremeirpp`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT pour la table `bulletin`
--
ALTER TABLE `bulletin`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `bulletincontenu`
--
ALTER TABLE `bulletincontenu`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `cahier_texte`
--
ALTER TABLE `cahier_texte`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `caisse`
--
ALTER TABLE `caisse`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT pour la table `caissepaiement`
--
ALTER TABLE `caissepaiement`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT pour la table `classe`
--
ALTER TABLE `classe`
  MODIFY `IDCLASSE` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=32;

--
-- AUTO_INCREMENT pour la table `compte`
--
ALTER TABLE `compte`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT pour la table `decision`
--
ALTER TABLE `decision`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `decisionmoyennereussite`
--
ALTER TABLE `decisionmoyennereussite`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `decisionrapport`
--
ALTER TABLE `decisionrapport`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `domaine`
--
ALTER TABLE `domaine`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT pour la table `eleve`
--
ALTER TABLE `eleve`
  MODIFY `ID_ELEVE` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT pour la table `eleveanneescolaire`
--
ALTER TABLE `eleveanneescolaire`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT pour la table `elevesalle`
--
ALTER TABLE `elevesalle`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT pour la table `elevestatutclasse`
--
ALTER TABLE `elevestatutclasse`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT pour la table `elevestatutetablissement`
--
ALTER TABLE `elevestatutetablissement`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT pour la table `entreesortie`
--
ALTER TABLE `entreesortie`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT pour la table `envoimail`
--
ALTER TABLE `envoimail`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `envoimaildetail`
--
ALTER TABLE `envoimaildetail`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `envoismsdetail`
--
ALTER TABLE `envoismsdetail`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `etat`
--
ALTER TABLE `etat`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT pour la table `horairesaisienote`
--
ALTER TABLE `horairesaisienote`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT pour la table `jour`
--
ALTER TABLE `jour`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT pour la table `journalisation`
--
ALTER TABLE `journalisation`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=37;

--
-- AUTO_INCREMENT pour la table `matiere`
--
ALTER TABLE `matiere`
  MODIFY `ID_MATIERE` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=51;

--
-- AUTO_INCREMENT pour la table `matierecoefficient`
--
ALTER TABLE `matierecoefficient`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=118;

--
-- AUTO_INCREMENT pour la table `messagerie_portail`
--
ALTER TABLE `messagerie_portail`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `modepaiement`
--
ALTER TABLE `modepaiement`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT pour la table `mois`
--
ALTER TABLE `mois`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `moissalaire`
--
ALTER TABLE `moissalaire`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `note`
--
ALTER TABLE `note`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `numero_evaluation`
--
ALTER TABLE `numero_evaluation`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT pour la table `observation`
--
ALTER TABLE `observation`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `paiementfrais`
--
ALTER TABLE `paiementfrais`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT pour la table `paiementtranche`
--
ALTER TABLE `paiementtranche`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT pour la table `paiementtype`
--
ALTER TABLE `paiementtype`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT pour la table `paiementtypeclasse`
--
ALTER TABLE `paiementtypeclasse`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;

--
-- AUTO_INCREMENT pour la table `paiementtypeclassetranche`
--
ALTER TABLE `paiementtypeclassetranche`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=93;

--
-- AUTO_INCREMENT pour la table `parents`
--
ALTER TABLE `parents`
  MODIFY `ID_PARENT` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `parent_eleve`
--
ALTER TABLE `parent_eleve`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `permission`
--
ALTER TABLE `permission`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `piece`
--
ALTER TABLE `piece`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT pour la table `pieceeleveanneescolaire`
--
ALTER TABLE `pieceeleveanneescolaire`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `position`
--
ALTER TABLE `position`
  MODIFY `idposition` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT pour la table `pret`
--
ALTER TABLE `pret`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `pretremboursement`
--
ALTER TABLE `pretremboursement`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `professeur`
--
ALTER TABLE `professeur`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT pour la table `professeurdonneepaie`
--
ALTER TABLE `professeurdonneepaie`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT pour la table `professeursallemat`
--
ALTER TABLE `professeursallemat`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT pour la table `professeurtitre`
--
ALTER TABLE `professeurtitre`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT pour la table `salle`
--
ALTER TABLE `salle`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;

--
-- AUTO_INCREMENT pour la table `salleemploitemps`
--
ALTER TABLE `salleemploitemps`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `soustypeoperation`
--
ALTER TABLE `soustypeoperation`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT pour la table `typefrais`
--
ALTER TABLE `typefrais`
  MODIFY `ID_TYPEFRAIS` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `utilisateur`
--
ALTER TABLE `utilisateur`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1782387350;

--
-- AUTO_INCREMENT pour la table `sessions_parents`
-- (pas d'AUTO_INCREMENT : PK = VARCHAR(128))
--

--
-- Index pour la table `msg_conversations`
--
ALTER TABLE `msg_conversations`
  ADD PRIMARY KEY (`ID`),
  ADD KEY `idx_msg_conv_type` (`TYPE_CONV`);

--
-- AUTO_INCREMENT pour la table `msg_conversations`
--
ALTER TABLE `msg_conversations`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT;

--
-- Index pour la table `msg_messages`
--
ALTER TABLE `msg_messages`
  ADD PRIMARY KEY (`ID`),
  ADD KEY `idx_msg_messages_conversation` (`ID_CONVERSATION`, `DATE_ENVOI`);

--
-- AUTO_INCREMENT pour la table `msg_messages`
--
ALTER TABLE `msg_messages`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT;

--
-- Index pour la table `msg_participants`
--
ALTER TABLE `msg_participants`
  ADD KEY `idx_msg_participants_user` (`USER_TYPE`, `ID_USER`),
  ADD KEY `idx_msg_participants_conv` (`ID_CONVERSATION`);

--
-- Index pour la table `msg_statuts_lecture`
--
ALTER TABLE `msg_statuts_lecture`
  ADD KEY `idx_msg_statuts_lecture_composite` (`LECTEUR_TYPE`, `ID_LECTEUR`, `DATE_LECTURE`),
  ADD KEY `idx_msg_statuts_message` (`ID_MESSAGE`);

--
-- Index pour la table `msg_pieces_jointes`
--
ALTER TABLE `msg_pieces_jointes`
  ADD PRIMARY KEY (`ID`),
  ADD KEY `idx_msg_pj_message` (`ID_MESSAGE`);

--
-- AUTO_INCREMENT pour la table `msg_pieces_jointes`
--
ALTER TABLE `msg_pieces_jointes`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT;

--
-- Index pour la table `sessions_parents`
--
ALTER TABLE `sessions_parents`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_sessions_parent_id` (`parent_id`),
  ADD KEY `idx_sessions_last_activity` (`last_activity`);

--
-- Index pour la table `password_resets_parents`
--
ALTER TABLE `password_resets_parents`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uq_password_resets_token_hash` (`token_hash`),
  ADD KEY `idx_password_resets_parent_id` (`parent_id`),
  ADD KEY `idx_password_resets_expires_at` (`expires_at`);

--
-- AUTO_INCREMENT pour la table `password_resets_parents`
--
ALTER TABLE `password_resets_parents`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- Index pour la table `bulletin_calcule`
--
ALTER TABLE `bulletin_calcule`
  ADD PRIMARY KEY (`ID`),
  ADD UNIQUE KEY `uk_bulletin_calcule` (`IDELEVE`, `IDANNEESCOLAIRE`, `IDPOSITION`),
  ADD KEY `idx_bulletin_calcule_salle` (`IDSALLE`),
  ADD KEY `idx_bulletin_calcule_annee` (`IDANNEESCOLAIRE`);

--
-- AUTO_INCREMENT pour la table `bulletin_calcule`
--
ALTER TABLE `bulletin_calcule`
  MODIFY `ID` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- Index pour la table `otp_codes`
--
ALTER TABLE `otp_codes`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_otp_parent_id` (`parent_id`),
  ADD KEY `idx_otp_expires_at` (`expires_at`),
  ADD KEY `idx_otp_type` (`type`);

--
-- AUTO_INCREMENT pour la table `otp_codes`
--
ALTER TABLE `otp_codes`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- Index pour la table `parent_invitations`
--
ALTER TABLE `parent_invitations`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_invitation_token` (`token_hash`),
  ADD KEY `idx_invitation_eleve` (`eleve_id`);

--
-- AUTO_INCREMENT pour la table `parent_invitations`
--
ALTER TABLE `parent_invitations`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `cahier_texte`
--
ALTER TABLE `cahier_texte`
  ADD CONSTRAINT `FK_CAHIER_ANNEE` FOREIGN KEY (`IDANNEESCOLAIRE`) REFERENCES `anneescolaire` (`ID`),
  ADD CONSTRAINT `FK_CAHIER_MATIERE` FOREIGN KEY (`IDMATIERE`) REFERENCES `matiere` (`ID_MATIERE`),
  ADD CONSTRAINT `FK_CAHIER_PROF` FOREIGN KEY (`IDPROF`) REFERENCES `professeur` (`ID`),
  ADD CONSTRAINT `FK_CAHIER_SALLE` FOREIGN KEY (`IDSALLE`) REFERENCES `salle` (`ID`);

--
-- Contraintes pour la table `note`
--
ALTER TABLE `note`
  ADD CONSTRAINT `note_ibfk_2` FOREIGN KEY (`IDMATIERE`) REFERENCES `matiere` (`ID_MATIERE`),
  ADD CONSTRAINT `note_ibfk_3` FOREIGN KEY (`IDPOSITION`) REFERENCES `position` (`idposition`);

--
-- Contraintes pour la table `parent_eleve`
--
ALTER TABLE `parent_eleve`
  ADD CONSTRAINT `FK_PARENTELEVE_ELEVE` FOREIGN KEY (`ID_ELEVE`) REFERENCES `eleve` (`ID_ELEVE`) ON DELETE CASCADE,
  ADD CONSTRAINT `FK_PARENTELEVE_PARENT` FOREIGN KEY (`ID_PARENT`) REFERENCES `parents` (`ID_PARENT`) ON DELETE CASCADE;

--
-- Contraintes pour la table `msg_messages`
--
ALTER TABLE `msg_messages`
  ADD CONSTRAINT `fk_msg_messages_conv` FOREIGN KEY (`ID_CONVERSATION`) REFERENCES `msg_conversations` (`ID`) ON DELETE CASCADE;

--
-- Contraintes pour la table `msg_participants`
--
ALTER TABLE `msg_participants`
  ADD CONSTRAINT `fk_msg_participants_conv` FOREIGN KEY (`ID_CONVERSATION`) REFERENCES `msg_conversations` (`ID`) ON DELETE CASCADE;

--
-- Contraintes pour la table `msg_statuts_lecture`
--
ALTER TABLE `msg_statuts_lecture`
  ADD CONSTRAINT `fk_msg_statuts_message` FOREIGN KEY (`ID_MESSAGE`) REFERENCES `msg_messages` (`ID`) ON DELETE CASCADE;

--
-- Contraintes pour la table `msg_pieces_jointes`
--
ALTER TABLE `msg_pieces_jointes`
  ADD CONSTRAINT `fk_msg_pj_message` FOREIGN KEY (`ID_MESSAGE`) REFERENCES `msg_messages` (`ID`) ON DELETE CASCADE;

COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
