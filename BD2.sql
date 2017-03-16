-- phpMyAdmin SQL Dump
-- version 4.1.14
-- http://www.phpmyadmin.net
--
-- Client :  127.0.0.1
-- Généré le :  Lun 13 Mars 2017 à 11:34
-- Version du serveur :  5.6.17
-- Version de PHP :  5.5.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Base de données :  `taleastory`
--

CREATE DATABASE `taleastory`;
USE `taleastory`;

-- --------------------------------------------------------

--
-- Structure de la table `achievement`
--

CREATE TABLE IF NOT EXISTS `achievement` (
  `IDAchievement` int(11) NOT NULL AUTO_INCREMENT,
  `Name` varchar(64) NOT NULL,
  `ImgPath` varchar(255) NOT NULL,
  `Brief` text NOT NULL,
  PRIMARY KEY (`IDAchievement`),
  UNIQUE KEY `Name` (`Name`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=3 ;

--
-- Contenu de la table `achievement`
--

INSERT INTO `achievement` (`IDAchievement`, `Name`, `ImgPath`, `Brief`) VALUES
(1, 'adventurer', '', 'you past more than 10 vagary'),
(2, 'smart', '', 'you resolved more than 10 enigma');

-- --------------------------------------------------------

--
-- Structure de la table `admin`
--

CREATE TABLE IF NOT EXISTS `admin` (
  `IDAdmin` int(11) NOT NULL AUTO_INCREMENT,
  `IDPlayer` int(11) NOT NULL,
  PRIMARY KEY (`IDAdmin`),
  KEY `FK_Admin_IDPlayer` (`IDPlayer`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=12 ;

--
-- Contenu de la table `admin`
--

INSERT INTO `admin` (`IDAdmin`, `IDPlayer`) VALUES
(10, 3),
(11, 4);

-- --------------------------------------------------------

--
-- Structure de la table `adminwriting`
--

CREATE TABLE IF NOT EXISTS `adminwriting` (
  `WritingDate` date DEFAULT NULL,
  `IDAdmin` int(11) NOT NULL,
  `IDStep` int(11) NOT NULL,
  PRIMARY KEY (`IDAdmin`,`IDStep`),
  KEY `FK_AdminWriting_IDStep` (`IDStep`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Structure de la table `choice`
--

CREATE TABLE IF NOT EXISTS `choice` (
  `IDChoice` int(11) NOT NULL AUTO_INCREMENT,
  `Answer` varchar(128) DEFAULT NULL,
  `IDStep` int(11) NOT NULL,
  `TransitionText` text NOT NULL,
  `IDNextStep` int(11) NOT NULL,
  PRIMARY KEY (`IDChoice`),
  KEY `FK_Choice_IDStep` (`IDStep`),
  KEY `IDNextStep` (`IDNextStep`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=4 ;

--
-- Contenu de la table `choice`
--

INSERT INTO `choice` (`IDChoice`, `Answer`, `IDStep`, `TransitionText`, `IDNextStep`) VALUES
(2, 'Encore quelques minutes', 2, 'Encore quelques minutes… Le jour ne partira pas sans moi… Et puis que pourrait-il arriver ? Le vent continue de me bercer.', 2),
(3, 'J’ouvre les yeux', 2, 'Après quelques hésitations, j’ouvre finalement les yeux. C’est une belle journée. Je souris en regardant autour de moi. Je récupère mes affaires et me prépare à partir. Bizarrement, mon arc est resté armé toute la nuit. Je devrais faire plus attention à mes armes… Je le débande et utilise la branche en canne improvisée. Je noue la corde autour de ma taille à l’aide d’un faux noeud. Je pourrais donc m’armer facilement en cas de besoin.', 3);

-- --------------------------------------------------------

--
-- Structure de la table `earn`
--

CREATE TABLE IF NOT EXISTS `earn` (
  `quantity` decimal(10,0) NOT NULL,
  `IDItem` int(11) NOT NULL,
  `IDChoice` int(11) NOT NULL,
  PRIMARY KEY (`IDItem`,`IDChoice`),
  KEY `FK_Earn_IDChoice` (`IDChoice`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Contenu de la table `earn`
--

INSERT INTO `earn` (`quantity`, `IDItem`, `IDChoice`) VALUES
('1', 3, 3);

-- --------------------------------------------------------

--
-- Structure de la table `inventory`
--

CREATE TABLE IF NOT EXISTS `inventory` (
  `quantity` decimal(10,0) NOT NULL,
  `IDPlayer` int(11) NOT NULL,
  `IDItem` int(11) NOT NULL,
  PRIMARY KEY (`IDPlayer`,`IDItem`),
  KEY `FK_Inventory_IDItem` (`IDItem`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Structure de la table `item`
--

CREATE TABLE IF NOT EXISTS `item` (
  `IDItem` int(11) NOT NULL AUTO_INCREMENT,
  `Name` varchar(64) NOT NULL,
  `ImgPath` varchar(255) DEFAULT NULL,
  `Brief` text,
  PRIMARY KEY (`IDItem`),
  UNIQUE KEY `Name` (`Name`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=5 ;

--
-- Contenu de la table `item`
--

INSERT INTO `item` (`IDItem`, `Name`, `ImgPath`, `Brief`) VALUES
(1, 'ITEM1', 'blahblah', 'ceci est un item'),
(2, 'ITEM2', 'blahblah', 'this is a second item '),
(3, 'arc', '', 'arc tout à fait ordinaire'),
(4, 'appat', '', 'appat attirant de petites bêtes');

-- --------------------------------------------------------

--
-- Structure de la table `itemrequirement`
--

CREATE TABLE IF NOT EXISTS `itemrequirement` (
  `quantity` int(11) NOT NULL,
  `IDItem` int(11) NOT NULL,
  `IDChoice` int(11) NOT NULL,
  PRIMARY KEY (`IDItem`,`IDChoice`),
  KEY `FK_ItemRequirement_IDChoice` (`IDChoice`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Structure de la table `lose`
--

CREATE TABLE IF NOT EXISTS `lose` (
  `quantity` int(11) NOT NULL,
  `IDItem` int(11) NOT NULL,
  `IDChoice` int(11) NOT NULL,
  PRIMARY KEY (`IDItem`,`IDChoice`),
  KEY `FK_Lose_IDChoice` (`IDChoice`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Structure de la table `paststep`
--

CREATE TABLE IF NOT EXISTS `paststep` (
  `EndDate` date DEFAULT NULL,
  `IDPlayer` int(11) NOT NULL,
  `IDStep` int(11) NOT NULL,
  PRIMARY KEY (`IDPlayer`,`IDStep`),
  KEY `FK_PastStep_IDStep` (`IDStep`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Structure de la table `player`
--

CREATE TABLE IF NOT EXISTS `player` (
  `IDPlayer` int(11) NOT NULL AUTO_INCREMENT,
  `ImgPath` varchar(255) NOT NULL,
  `Login` varchar(64) NOT NULL,
  `Pwd` varchar(128) NOT NULL,
  `Pseudo` varchar(64) NOT NULL,
  `Mail` varchar(128) DEFAULT NULL,
  `IDCurrentStep` int(11) DEFAULT NULL,
  PRIMARY KEY (`IDPlayer`),
  UNIQUE KEY `Login` (`Login`),
  KEY `FK_Player_IDStep` (`IDCurrentStep`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=5 ;

--
-- Contenu de la table `player`
--

INSERT INTO `player` (`IDPlayer`, `ImgPath`, `Login`, `Pwd`, `Pseudo`, `Mail`, `IDCurrentStep`) VALUES
(2, 'patulacci_tiny.jpg', 'marcel', 'inconnus', 'marcel patulacci', NULL, NULL),
(3, 'default_tiny.jpg', 'white', 'heisenberg', 'Walter White', NULL, NULL),
(4, 'default_tiny.jpg', 'blow', 'curry', 'Blow', NULL, NULL);

-- --------------------------------------------------------

--
-- Structure de la table `playerachievement`
--

CREATE TABLE IF NOT EXISTS `playerachievement` (
  `isRead` tinyint(1) NOT NULL,
  `IDPlayer` int(11) NOT NULL,
  `IDAchievement` int(11) NOT NULL,
  PRIMARY KEY (`IDPlayer`,`IDAchievement`),
  KEY `FK_PlayerAchievement_IDAchievement` (`IDAchievement`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Structure de la table `playerstat`
--

CREATE TABLE IF NOT EXISTS `playerstat` (
  `Value` float NOT NULL,
  `IDPlayer` int(11) NOT NULL,
  `IDStat` int(11) NOT NULL,
  PRIMARY KEY (`IDPlayer`,`IDStat`),
  KEY `FK_PlayerStat_IDStat` (`IDStat`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Structure de la table `stat`
--

CREATE TABLE IF NOT EXISTS `stat` (
  `IDStat` int(11) NOT NULL AUTO_INCREMENT,
  `Name` varchar(64) NOT NULL,
  PRIMARY KEY (`IDStat`),
  UNIQUE KEY `Name` (`Name`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=5 ;

--
-- Contenu de la table `stat`
--

INSERT INTO `stat` (`IDStat`, `Name`) VALUES
(3, 'faim'),
(4, 'fatigue'),
(2, 'force'),
(1, 'intelligence');

-- --------------------------------------------------------

--
-- Structure de la table `statalteration`
--

CREATE TABLE IF NOT EXISTS `statalteration` (
  `Value` float NOT NULL,
  `IDChoice` int(11) NOT NULL,
  `IDStat` int(11) NOT NULL,
  PRIMARY KEY (`IDChoice`,`IDStat`),
  KEY `FK_StatAlteration_IDStat` (`IDStat`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Structure de la table `statrequirement`
--

CREATE TABLE IF NOT EXISTS `statrequirement` (
  `Value` float NOT NULL,
  `IDChoice` int(11) NOT NULL,
  `IDStat` int(11) NOT NULL,
  PRIMARY KEY (`IDChoice`,`IDStat`),
  KEY `FK_StatRequirement_IDStat` (`IDStat`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Structure de la table `step`
--

CREATE TABLE IF NOT EXISTS `step` (
  `IDStep` int(11) NOT NULL AUTO_INCREMENT,
  `ImgPath` varchar(255) DEFAULT NULL,
  `Body` text NOT NULL,
  `Question` text NOT NULL,
  `IDType` int(11) NOT NULL,
  `Title` text,
  PRIMARY KEY (`IDStep`),
  KEY `FK_Step_IDType` (`IDType`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=5 ;

--
-- Contenu de la table `step`
--

INSERT INTO `step` (`IDStep`, `ImgPath`, `Body`, `Question`, `IDType`, `Title`) VALUES
(1, 'blahblah', 'ceci est une étape step', 'Where is Brayan ?', 1, 'JeSuisUnTitre'),
(2, NULL, 'Mes yeux sont fermés. Je reste là, couché. J’aimerais qu’il soit plus tard, avoir plus de temps pour me reposer. Mais je sens les rayons du soleil contre ma peau. Le vent se lève', '...', 4, NULL),
(3, NULL, 'Il est temps de partir, le vent se lève. Mon voyage me mène vers l’est, contre le vent. Parfait pour chasser, les proies ne me sentiront pas venir.', '...', 4, NULL);

-- --------------------------------------------------------

--
-- Structure de la table `steptype`
--

CREATE TABLE IF NOT EXISTS `steptype` (
  `IDType` int(11) NOT NULL AUTO_INCREMENT,
  `Name` varchar(64) NOT NULL,
  PRIMARY KEY (`IDType`),
  UNIQUE KEY `Name` (`Name`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=6 ;

--
-- Contenu de la table `steptype`
--

INSERT INTO `steptype` (`IDType`, `Name`) VALUES
(4, 'decision'),
(2, 'enigma'),
(5, 'game'),
(3, 'illustrated_enigma'),
(1, 'type de step yolo');

--
-- Contraintes pour les tables exportées
--

--
-- Contraintes pour la table `admin`
--
ALTER TABLE `admin`
  ADD CONSTRAINT `FK_Admin_IDPlayer` FOREIGN KEY (`IDPlayer`) REFERENCES `player` (`IDPlayer`);

--
-- Contraintes pour la table `adminwriting`
--
ALTER TABLE `adminwriting`
  ADD CONSTRAINT `FK_AdminWriting_IDAdmin` FOREIGN KEY (`IDAdmin`) REFERENCES `admin` (`IDAdmin`),
  ADD CONSTRAINT `FK_AdminWriting_IDStep` FOREIGN KEY (`IDStep`) REFERENCES `step` (`IDStep`);

--
-- Contraintes pour la table `choice`
--
ALTER TABLE `choice`
  ADD CONSTRAINT `choice_ibfk_1` FOREIGN KEY (`IDNextStep`) REFERENCES `step` (`IDStep`),
  ADD CONSTRAINT `FK_Choice_IDStep` FOREIGN KEY (`IDStep`) REFERENCES `step` (`IDStep`);

--
-- Contraintes pour la table `earn`
--
ALTER TABLE `earn`
  ADD CONSTRAINT `FK_Earn_IDChoice` FOREIGN KEY (`IDChoice`) REFERENCES `choice` (`IDChoice`),
  ADD CONSTRAINT `FK_Earn_IDItem` FOREIGN KEY (`IDItem`) REFERENCES `item` (`IDItem`);

--
-- Contraintes pour la table `inventory`
--
ALTER TABLE `inventory`
  ADD CONSTRAINT `FK_Inventory_IDItem` FOREIGN KEY (`IDItem`) REFERENCES `item` (`IDItem`),
  ADD CONSTRAINT `FK_Inventory_IDPlayer` FOREIGN KEY (`IDPlayer`) REFERENCES `player` (`IDPlayer`);

--
-- Contraintes pour la table `itemrequirement`
--
ALTER TABLE `itemrequirement`
  ADD CONSTRAINT `FK_ItemRequirement_IDChoice` FOREIGN KEY (`IDChoice`) REFERENCES `choice` (`IDChoice`),
  ADD CONSTRAINT `FK_ItemRequirement_IDItem` FOREIGN KEY (`IDItem`) REFERENCES `item` (`IDItem`);

--
-- Contraintes pour la table `lose`
--
ALTER TABLE `lose`
  ADD CONSTRAINT `FK_Lose_IDChoice` FOREIGN KEY (`IDChoice`) REFERENCES `choice` (`IDChoice`),
  ADD CONSTRAINT `FK_Lose_IDItem` FOREIGN KEY (`IDItem`) REFERENCES `item` (`IDItem`);

--
-- Contraintes pour la table `paststep`
--
ALTER TABLE `paststep`
  ADD CONSTRAINT `FK_PastStep_IDStep` FOREIGN KEY (`IDStep`) REFERENCES `step` (`IDStep`),
  ADD CONSTRAINT `FK_PastStep_IDPlayer` FOREIGN KEY (`IDPlayer`) REFERENCES `player` (`IDPlayer`);

--
-- Contraintes pour la table `playerachievement`
--
ALTER TABLE `playerachievement`
  ADD CONSTRAINT `FK_PlayerAchievement_IDAchievement` FOREIGN KEY (`IDAchievement`) REFERENCES `achievement` (`IDAchievement`),
  ADD CONSTRAINT `FK_PlayerAchievement_IDPlayer` FOREIGN KEY (`IDPlayer`) REFERENCES `player` (`IDPlayer`);

--
-- Contraintes pour la table `playerstat`
--
ALTER TABLE `playerstat`
  ADD CONSTRAINT `FK_PlayerStat_IDStat` FOREIGN KEY (`IDStat`) REFERENCES `stat` (`IDStat`),
  ADD CONSTRAINT `FK_PlayerStat_IDPlayer` FOREIGN KEY (`IDPlayer`) REFERENCES `player` (`IDPlayer`);

--
-- Contraintes pour la table `statalteration`
--
ALTER TABLE `statalteration`
  ADD CONSTRAINT `FK_StatAlteration_IDStat` FOREIGN KEY (`IDStat`) REFERENCES `stat` (`IDStat`),
  ADD CONSTRAINT `FK_StatAlteration_IDChoice` FOREIGN KEY (`IDChoice`) REFERENCES `choice` (`IDChoice`);

--
-- Contraintes pour la table `statrequirement`
--
ALTER TABLE `statrequirement`
  ADD CONSTRAINT `FK_StatRequirement_IDStat` FOREIGN KEY (`IDStat`) REFERENCES `stat` (`IDStat`),
  ADD CONSTRAINT `FK_StatRequirement_IDChoice` FOREIGN KEY (`IDChoice`) REFERENCES `choice` (`IDChoice`);

--
-- Contraintes pour la table `step`
--
ALTER TABLE `step`
  ADD CONSTRAINT `FK_Step_IDType` FOREIGN KEY (`IDType`) REFERENCES `steptype` (`IDType`);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
