-- phpMyAdmin SQL Dump
-- version 4.6.4
-- https://www.phpmyadmin.net/
--
-- Client :  127.0.0.1
-- Généré le :  Mer 22 Mars 2017 à 23:11
-- Version du serveur :  5.7.14
-- Version de PHP :  5.6.25

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données :  `taleastory`
--

-- --------------------------------------------------------

--
-- Structure de la table `achievement`
--

CREATE TABLE `achievement` (
  `IDAchievement` int(11) NOT NULL,
  `Name` varchar(64) NOT NULL,
  `ImgPath` varchar(255) NOT NULL,
  `Brief` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

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

CREATE TABLE `admin` (
  `IDAdmin` int(11) NOT NULL,
  `IDPlayer` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Contenu de la table `admin`
--

INSERT INTO `admin` (`IDAdmin`, `IDPlayer`) VALUES
(10, 3),
(11, 4),
(12, 11);

-- --------------------------------------------------------

--
-- Structure de la table `adminwriting`
--

CREATE TABLE `adminwriting` (
  `WritingDate` date DEFAULT NULL,
  `IDAdmin` int(11) NOT NULL,
  `IDStep` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Structure de la table `choice`
--

CREATE TABLE `choice` (
  `IDChoice` int(11) NOT NULL,
  `Answer` varchar(128) DEFAULT NULL,
  `IDStep` int(11) NOT NULL,
  `TransitionText` text NOT NULL,
  `IDNextStep` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Contenu de la table `choice`
--

INSERT INTO `choice` (`IDChoice`, `Answer`, `IDStep`, `TransitionText`, `IDNextStep`) VALUES
(1, 'Front', 2, 'Vous avez choisi de donner la parole à Steeve ! \r\n\r\nBien joué ! ', 7),
(2, 'Back', 2, 'Vous avez choisi de donner la parole à Lou ! \r\n\r\nBien joué ! ', 8),
(3, 'Point d\'amélioration Front', 7, 'Cette version reste encore incomplète\r\n \r\nOn peut y intégrer un affichage de chargement\r\n\r\nImplémenter un affichage et une meilleure gestion d’erreur\r\n\r\nCorriger le problème de scroll\r\n\r\nÉtoffer l’interface de jeu ainsi que celles du profil et du backoffice', 3),
(4, 'Avancement Back', 7, 'Fonctionnalités du jeu : joueur / stats / choix / étapes / trophées\r\n\r\nFonctionnement du site : routes / session / réponses json / liaison database\r\n\r\nFonctionnalités back office : ajouts en base de données\r\n', 5),
(5, 'Points d\'amélioration Back', 3, 'Homogénéisation du code pour éviter les redondances de fonctions\r\n\r\nMeilleure gestion d’erreurs\r\n\r\nOptimisation de la classe Database\r\n\r\nImplémentations des fonctionnalités Item\r\n', 9),
(6, 'Points d\'amélioration Back', 5, 'Homogénéisation du code pour éviter les redondances de fonctions\r\n\r\nMeilleure gestion d’erreurs\r\n\r\nOptimisation de la classe Database\r\n\r\nImplémentations des fonctionnalités Item\r\n', 9),
(7, 'Avancement Front', 8, 'La version actuelle vous permet d’avancer dans une histoire pré-définie\r\n\r\nElle propose une interface sobre munie d’animations\r\n\r\nInterface avec laquelle vous pouvez gérer votre profil\r\n\r\nEt gérer le contenu du site en tant qu’administrateur', 6),
(8, 'Point d\'amélioration Back', 8, 'Homogénéisation du code pour éviter les redondances de fonctions\r\n\r\nMeilleure gestion d’erreurs\r\n\r\nOptimisation de la classe Database\r\n\r\nImplémentations des fonctionnalités Item\r\n', 4),
(9, 'Points d\'amélioration Front', 6, 'Cette version reste encore incomplète \r\n\r\nOn peut y intégrer un affichage de chargement\r\n\r\nImplémenter un affichage et une meilleure gestion d’erreur\r\n\r\nCorriger le problème de scroll\r\n\r\nÉtoffer l’interface de jeu ainsi que celles du profil et du backoffice', 9),
(10, 'Points d\'amélioration Front', 4, 'Cette version reste encore incomplète \r\n\r\nOn peut y intégrer un affichage de chargement\r\n\r\nImplémenter un affichage et une meilleure gestion d’erreur\r\n\r\nCorriger le problème de scroll\r\n\r\nÉtoffer l’interface de jeu ainsi que celles du profil et du backoffice', 9),
(11, 'C\'était super ! ', 9, 'Ca vous a plu ? \r\nOn y a passé du temps, soyez gentils !\r\n\r\nPassons à la gestion de projet ! ', 10),
(12, 'Répartition des tâches', 10, 'Chef de projet : Anfray Lapeyre\r\nDesigneuse : Caroline Vien\r\nCTO : Steeve Vincent\r\nFront : Vincent Delannoy\r\nBack : Olivier Faugère\r\nAnne-Amélie Laugier\r\nLou Landry-Linet\r\nCécile Poucet\r\nEstelle Guingo', 11),
(13, 'Planning', 10, '-Phase de conception\r\n-Prototypage & modules back basiques\r\n-Front fonctionnel & modules back intermédiaires\r\n-Front  final & requêtes back\r\n-Assemblage ', 12),
(14, 'Continuer', 11, 'BONUS : \r\nDans un des choix suivants, Anfray ne parle plus.', 13),
(15, 'Continuer', 12, 'BONUS : \r\nDans un des choix suivants, Anfray ne parle plus.', 13),
(16, 'Méthodes', 13, 'Ah... Bon bah Anfray va continuer.', 14),
(17, 'GIT', 13, 'Aaah ! Vive Steeve ! ', 15),
(18, 'Outils utilisés', 13, 'Bon... Bah Anfray va continuer alors.\r\nDommage.', 16),
(19, 'GIT', 14, '1 branche = 1 fonctionnalité\r\n\r\nMerge : Pas beaucoup de problèmes, mais quand il y en a, ça fait mal', 17),
(20, 'Outils utilisés', 14, 'Gulp - Automatisation de tâche\r\n\r\nImagick - Traitement d’image\r\n\r\nReact - Framework JS\r\n\r\nGreenSock - Animation', 18),
(21, 'Outils utilisés', 15, 'Gulp - Automatisation de tâche\r\n\r\nImagick - Traitement d’image\r\n\r\nReact - Framework JS\r\n\r\nGreenSock - Animation', 19),
(22, 'Méthodes de travail', 15, 'Méthode Kanban (A faire, prioritaire, en cours, à vérifier, fini)\r\n\r\nXtrem Programming (travail en binôme)\r\n\r\nScrum (rushs d’une semaine)', 17),
(23, 'Méthodes de travail', 16, 'Méthode Kanban (A faire, prioritaire, en cours, à vérifier, fini)\r\n\r\nXtrem Programming (travail en binôme)\r\n\r\nScrum (rushs d’une semaine)', 18),
(24, 'GIT', 16, '1 branche = 1 fonctionnalité\r\n\r\nMerge : Pas beaucoup de problèmes, mais quand il y en a, ça fait mal', 19),
(25, 'Bilans', 17, 'Voilà ! \r\n\r\nNe reste que les bilans ! ', 20),
(26, 'Bilans', 18, 'Voilà ! \r\n\r\nNe reste que les bilans ! ', 20),
(27, 'Bilans', 19, 'Voilà ! \r\n\r\nNe reste que les bilans ! ', 20),
(28, 'Bilan Front', 20, 'Retenu : \r\n\r\nArchitecture souple\r\n\r\nProchaine fois : \r\n\r\nAmeliorer organisation du code (js et scss)\r\nMieux analyser le front', 21),
(29, 'Bilan Back', 20, 'Retenu : \r\n\r\nImportance de la communication de l’entraide\r\nAvantages et inconvénients de la répartition des tâches\r\n\r\nProchaine fois : \r\n\r\nAvoir une meilleure connaissance du projet dans sa globalité\r\nMeilleure communication technique sur les fonctionnalités crées \r\nContinuer les réunions en présence physique', 21),
(30, 'Terminer', 21, 'Et voilà ! C\'est fini ! \r\n\r\nQue dire de plus ? ', 23),
(31, 'Terminer', 22, 'Et voilà ! C\'est fini ! \r\n\r\nQue dire de plus ? ', 23);

-- --------------------------------------------------------

--
-- Structure de la table `earn`
--

CREATE TABLE `earn` (
  `quantity` decimal(10,0) NOT NULL,
  `IDItem` int(11) NOT NULL,
  `IDChoice` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Structure de la table `inventory`
--

CREATE TABLE `inventory` (
  `quantity` decimal(10,0) NOT NULL,
  `IDPlayer` int(11) NOT NULL,
  `IDItem` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Contenu de la table `inventory`
--

INSERT INTO `inventory` (`quantity`, `IDPlayer`, `IDItem`) VALUES
('10', 8, 3),
('1', 9, 3),
('1', 10, 3);

-- --------------------------------------------------------

--
-- Structure de la table `item`
--

CREATE TABLE `item` (
  `IDItem` int(11) NOT NULL,
  `Name` varchar(64) NOT NULL,
  `ImgPath` varchar(255) DEFAULT NULL,
  `Brief` text
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Contenu de la table `item`
--

INSERT INTO `item` (`IDItem`, `Name`, `ImgPath`, `Brief`) VALUES
(1, 'ITEM1', 'blahblah', 'ceci est un Item'),
(2, 'ITEM2', 'blahblah', 'this is a second Item '),
(3, 'arc', '', 'arc tout à fait ordinaire'),
(4, 'appat', '', 'appat attirant de petites bêtes');

-- --------------------------------------------------------

--
-- Structure de la table `itemrequirement`
--

CREATE TABLE `itemrequirement` (
  `quantity` int(11) NOT NULL,
  `IDItem` int(11) NOT NULL,
  `IDChoice` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Structure de la table `lose`
--

CREATE TABLE `lose` (
  `quantity` int(11) NOT NULL,
  `IDItem` int(11) NOT NULL,
  `IDChoice` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Structure de la table `paststep`
--

CREATE TABLE `paststep` (
  `EndDate` date DEFAULT NULL,
  `IDPlayer` int(11) NOT NULL,
  `IDStep` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Structure de la table `player`
--

CREATE TABLE `player` (
  `IDPlayer` int(11) NOT NULL,
  `ImgPath` varchar(255) NOT NULL,
  `Login` varchar(64) NOT NULL,
  `Pwd` varchar(128) NOT NULL,
  `Pseudo` varchar(64) NOT NULL,
  `Mail` varchar(128) DEFAULT NULL,
  `IDCurrentStep` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Contenu de la table `player`
--

INSERT INTO `player` (`IDPlayer`, `ImgPath`, `Login`, `Pwd`, `Pseudo`, `Mail`, `IDCurrentStep`) VALUES
(2, 'patulacci_tiny.jpg', 'marcel', 'inconnus', 'marcel patulacci', NULL, NULL),
(3, 'default_tiny.jpg', 'white', 'heisenberg', 'Walter White', NULL, NULL),
(4, 'default_tiny.jpg', 'blow', 'curry', 'Blow', NULL, NULL),
(5, '../../defaultImg.jpg', 'babar@gmail.com', '$2y$10$xSUwgggEDaP9HjPlUsxJOOheHBRwIFUBwIlJDUChQxdEMuuS1Ggle', 'Babar', 'babar@gmail.com', 0),
(7, '../../defaultImg.jpg', '30680', '$2y$10$X/t.IbS0PgviKInDz5UD6eS3blul85Pet0ODEOJHCJJwm0DHbYtXq', 'Guest', 'fake@mail.com', 0),
(8, 'defaultImg_medium.png', 'popo@popo.com', '$2y$10$ZwADCBRxX2NdK9gzZAE4nOanziK/avD1zIQvrXkos48/ZcAwOgh1O', 'popo', 'popo@popo.com', 2),
(9, 'defaultImg_medium.png', 'caca@caca.com', '$2y$10$5e4QP4QW9Y3RHLP6XfxlBeFRoBzcvKFCjxrqrFRM.WdoqzHXxjbCm', 'caca', 'caca@caca.com', 3),
(10, 'defaultImg_medium.png', 'zizi@zizi.com', '$2y$10$AWKdPucBhZaHgsoMqq6CtObFDVHHhUc.HOg6JkC9udK7wtcELfceO', 'zizi', 'zizi@zizi.com', 3),
(11, 'ea52ab93b5f988634205ea1ca4cd8af5.png', 'bibi@bibi.com', '$2y$10$EO6eE5Uo97DNx2ju/uX11eKKafVHP5W1oJegKUoy7qdEumHg9J2pW', 'bibi', 'bibi@bibi.com', NULL);

-- --------------------------------------------------------

--
-- Structure de la table `playerachievement`
--

CREATE TABLE `playerachievement` (
  `isRead` tinyint(1) NOT NULL,
  `IDPlayer` int(11) NOT NULL,
  `IDAchievement` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Structure de la table `playerstat`
--

CREATE TABLE `playerstat` (
  `Value` float NOT NULL,
  `IDPlayer` int(11) NOT NULL,
  `IDStat` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Contenu de la table `playerstat`
--

INSERT INTO `playerstat` (`Value`, `IDPlayer`, `IDStat`) VALUES
(0, 8, 1),
(0, 8, 3),
(0, 8, 4),
(0, 9, 1),
(0, 9, 3),
(0, 9, 4),
(0, 10, 1),
(0, 10, 3),
(0, 10, 4),
(0, 11, 1),
(0, 11, 3),
(0, 11, 4);

-- --------------------------------------------------------

--
-- Structure de la table `stat`
--

CREATE TABLE `stat` (
  `IDStat` int(11) NOT NULL,
  `Name` varchar(64) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Contenu de la table `stat`
--

INSERT INTO `stat` (`IDStat`, `Name`) VALUES
(4, 'Bilan'),
(1, 'Développement'),
(3, 'Gestion de projet');

-- --------------------------------------------------------

--
-- Structure de la table `statalteration`
--

CREATE TABLE `statalteration` (
  `Value` float NOT NULL,
  `IDChoice` int(11) NOT NULL,
  `IDStat` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Contenu de la table `statalteration`
--

INSERT INTO `statalteration` (`Value`, `IDChoice`, `IDStat`) VALUES
(10, 1, 1),
(10, 2, 1),
(35, 3, 1),
(35, 4, 1),
(35, 5, 1),
(30, 6, 1),
(35, 7, 1),
(35, 8, 1),
(35, 9, 1),
(35, 10, 1),
(15, 11, 3),
(20, 12, 3),
(20, 13, 3),
(20, 14, 3),
(20, 15, 3),
(20, 16, 3),
(20, 17, 3),
(20, 18, 3),
(20, 19, 3),
(20, 20, 3),
(20, 21, 3),
(20, 22, 3),
(20, 23, 3),
(20, 24, 3),
(15, 25, 3),
(30, 25, 4),
(30, 26, 4),
(30, 27, 4),
(20, 28, 1),
(40, 28, 4),
(20, 29, 1),
(40, 29, 4),
(30, 30, 4),
(30, 31, 4);

-- --------------------------------------------------------

--
-- Structure de la table `statrequirement`
--

CREATE TABLE `statrequirement` (
  `Value` float NOT NULL,
  `IDChoice` int(11) NOT NULL,
  `IDStat` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Structure de la table `step`
--

CREATE TABLE `step` (
  `IDStep` int(11) NOT NULL,
  `ImgPath` varchar(255) DEFAULT NULL,
  `Body` text NOT NULL,
  `Question` text NOT NULL,
  `IDType` int(11) NOT NULL,
  `Title` text
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Contenu de la table `step`
--

INSERT INTO `step` (`IDStep`, `ImgPath`, `Body`, `Question`, `IDType`, `Title`) VALUES
(1, NULL, 'Test', 'Test', 1, 'Test'),
(2, NULL, 'Il est maintenant temps de faire votre premier choix...', 'Souhaitez-vous que l\'on parle de l\'avancement actuel Front ou Back ? ', 4, 'Test'),
(3, NULL, 'Fonctionnalités du jeu : joueur / stats / choix / étapes / trophées\r\n\r\nFonctionnement du site : routes / session / réponses json / liaison database\r\n\r\nFonctionnalités back office : ajouts en base de données\r\n', '', 4, 'Avancement Back - Choix front'),
(4, NULL, 'La version actuelle vous permet d’avancer dans une histoire pré-définie\r\n\r\nElle propose une interface sobre munie d’animations\r\n\r\nInterface avec laquelle vous pouvez gérer votre profil\r\n\r\nEt gérer le contenu du site en tant qu’administrateur', '', 4, 'Avancement Front- Choix Back'),
(5, NULL, 'Cette version reste encore incomplète\r\n\r\nOn peut y intégrer un affichage de chargement\r\n\r\nImplémenter un affichage et une meilleure gestion d’erreur\r\n\r\nCorriger le problème de scroll\r\n\r\nÉtoffer l’interface de jeu ainsi que celles du profil et du backoffice', '', 4, 'Progression Front - Choix Front'),
(6, NULL, 'Homogénéisation du code pour éviter les redondances de fonctions\r\n\r\nMeilleure gestion d’erreurs\r\n\r\nOptimisation de la classe Database\r\n\r\nImplémentation des fonctionnalités Item\r\n', '', 4, 'Progression Back - Choix Back'),
(7, NULL, 'La version actuelle vous permet d’avancer dans une histoire pré-définie\r\n\r\nElle propose une interface sobre munie d’animations\r\n\r\nInterface avec laquelle vous pouvez gérer votre profil\r\n\r\nEt gérer le contenu du site en tant qu’administrateur', '', 4, 'Avancement Front'),
(8, NULL, 'Fonctionnalités du jeu : joueur / stats / choix / étapes / trophées\r\n\r\nFonctionnement du site : routes / session / réponses json / liaison database\r\n\r\nFonctionnalités back office : ajouts en base de données\r\n', '', 4, 'Avancement Back'),
(9, NULL, 'C\'est bien beau tout ça.\r\n\r\nMais à quoi ressemble la version en ligne ?', '', 4, NULL),
(10, NULL, 'Gérer un projet aussi complexe demande de s’y connaître très bien autant en front qu’en back, ainsi que demande beaucoup d’organisation.\r\n\r\n\r\nDivision des rôles : Chef de projet !=  CTO\r\n', '', 4, NULL),
(11, NULL, '-Phase de conception\r\n\r\n-Prototypage & modules back basiques\r\n\r\n-Front fonctionnel & modules back intermédiaires\r\n\r\n-Front final & requêtes back\r\n\r\n-Assemblage ', '', 4, 'Planning'),
(12, NULL, 'Chef de projet : Anfray Lapeyre\r\nDesigneuse : Caroline Vien\r\nCTO : Steeve Vincent\r\nFront : Vincent Delannoy\r\nBack : Olivier Faugère\r\nAnne-Amélie Laugier\r\nLou Landry-Linet\r\nCécile Poucet\r\nEstelle Guingo', '', 4, 'Répartition des tâches'),
(13, NULL, 'Que préférez-vous ? ', '', 4, NULL),
(14, NULL, 'Méthode Kanban (A faire, prioritaire, en cours, à vérifier, fini)\r\n\r\nXtrem Programming (travail en binôme)\r\n\r\nScrum (rushs d’une semaine)', '', 4, NULL),
(15, NULL, '1 branche = 1 fonctionnalité\r\n\r\nMerge : Pas beaucoup de problèmes, mais quand il y en a, ça fait mal', '', 4, NULL),
(16, NULL, 'Gulp - Automatisation de tâche\r\n\r\nImagick - Traitement d’image\r\n\r\nReact - Framework JS\r\n\r\nGreenSock - Animation', '', 4, NULL),
(17, NULL, 'Gulp - Automatisation de tâche\r\n\r\nImagick - Traitement d’image\r\n\r\nReact - Framework JS\r\n\r\nGreenSock - Animation', '', 4, NULL),
(18, NULL, '1 branche = 1 fonctionnalité\r\n\r\nMerge : Pas beaucoup de problèmes, mais quand il y en a, ça fait mal', '', 4, NULL),
(19, NULL, 'Méthode Kanban (A faire, prioritaire, en cours, à vérifier, fini)\r\n\r\nXtrem Programming (travail en binôme)\r\n\r\nScrum (rushs d’une semaine)', '', 4, NULL),
(20, NULL, 'Retenu : \r\n\r\nFaire des réunions souvent c\'est bien.\r\nSurtout quand il y a à manger.\r\n\r\nProchaine fois :\r\n \r\nFaire partie de toutes les conversations afin de ne pas prendre de retard.\r\nMieux répartir la charge de travail.', '', 4, 'Bilan'),
(21, NULL, 'Retenu : \r\n\r\nImportance de la communication de l’entraide\r\nAvantages et inconvénients de la répartition des tâches\r\n\r\nProchaine fois : \r\n\r\nAvoir une meilleure connaissance du projet dans sa globalité\r\nMeilleure communication technique sur les fonctionnalités crées \r\nContinuer les réunions en présence physique', '', 4, NULL),
(22, NULL, 'Retenu : \r\n\r\nArchitecture souple\r\n\r\nProchaine fois : \r\n\r\nMieux organiser le code (js et scss) \r\nMieux analyser et concevoir le front', '', 4, NULL),
(23, 'merci_large.png', 'Tale A Story c’est bien.\r\n\r\nOn s’est bien amusés.\r\n\r\nMais on est fatigués. ', '', 4, 'Merci ! ');

-- --------------------------------------------------------

--
-- Structure de la table `steptype`
--

CREATE TABLE `steptype` (
  `IDType` int(11) NOT NULL,
  `Name` varchar(64) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Contenu de la table `steptype`
--

INSERT INTO `steptype` (`IDType`, `Name`) VALUES
(4, 'decision'),
(2, 'enigma'),
(5, 'game'),
(3, 'illustrated_enigma'),
(1, 'type de Step yolo');

--
-- Index pour les tables exportées
--

--
-- Index pour la table `achievement`
--
ALTER TABLE `achievement`
  ADD PRIMARY KEY (`IDAchievement`),
  ADD UNIQUE KEY `Name` (`Name`);

--
-- Index pour la table `admin`
--
ALTER TABLE `admin`
  ADD PRIMARY KEY (`IDAdmin`),
  ADD KEY `FK_Admin_IDPlayer` (`IDPlayer`);

--
-- Index pour la table `adminwriting`
--
ALTER TABLE `adminwriting`
  ADD PRIMARY KEY (`IDAdmin`,`IDStep`),
  ADD KEY `FK_AdminWriting_IDStep` (`IDStep`);

--
-- Index pour la table `choice`
--
ALTER TABLE `choice`
  ADD PRIMARY KEY (`IDChoice`),
  ADD KEY `FK_Choice_IDStep` (`IDStep`),
  ADD KEY `IDNextStep` (`IDNextStep`);

--
-- Index pour la table `earn`
--
ALTER TABLE `earn`
  ADD PRIMARY KEY (`IDItem`,`IDChoice`),
  ADD KEY `FK_Earn_IDChoice` (`IDChoice`);

--
-- Index pour la table `inventory`
--
ALTER TABLE `inventory`
  ADD PRIMARY KEY (`IDPlayer`,`IDItem`),
  ADD KEY `FK_Inventory_IDItem` (`IDItem`);

--
-- Index pour la table `item`
--
ALTER TABLE `item`
  ADD PRIMARY KEY (`IDItem`),
  ADD UNIQUE KEY `Name` (`Name`);

--
-- Index pour la table `itemrequirement`
--
ALTER TABLE `itemrequirement`
  ADD PRIMARY KEY (`IDItem`,`IDChoice`),
  ADD KEY `FK_ItemRequirement_IDChoice` (`IDChoice`);

--
-- Index pour la table `lose`
--
ALTER TABLE `lose`
  ADD PRIMARY KEY (`IDItem`,`IDChoice`),
  ADD KEY `FK_Lose_IDChoice` (`IDChoice`);

--
-- Index pour la table `paststep`
--
ALTER TABLE `paststep`
  ADD PRIMARY KEY (`IDPlayer`,`IDStep`),
  ADD KEY `FK_PastStep_IDStep` (`IDStep`);

--
-- Index pour la table `player`
--
ALTER TABLE `player`
  ADD PRIMARY KEY (`IDPlayer`),
  ADD UNIQUE KEY `Login` (`Login`),
  ADD KEY `FK_Player_IDStep` (`IDCurrentStep`);

--
-- Index pour la table `playerachievement`
--
ALTER TABLE `playerachievement`
  ADD PRIMARY KEY (`IDPlayer`,`IDAchievement`),
  ADD KEY `FK_PlayerAchievement_IDAchievement` (`IDAchievement`);

--
-- Index pour la table `playerstat`
--
ALTER TABLE `playerstat`
  ADD PRIMARY KEY (`IDPlayer`,`IDStat`),
  ADD KEY `FK_PlayerStat_IDStat` (`IDStat`);

--
-- Index pour la table `stat`
--
ALTER TABLE `stat`
  ADD PRIMARY KEY (`IDStat`),
  ADD UNIQUE KEY `Name` (`Name`);

--
-- Index pour la table `statalteration`
--
ALTER TABLE `statalteration`
  ADD PRIMARY KEY (`IDChoice`,`IDStat`),
  ADD KEY `FK_StatAlteration_IDStat` (`IDStat`);

--
-- Index pour la table `statrequirement`
--
ALTER TABLE `statrequirement`
  ADD PRIMARY KEY (`IDChoice`,`IDStat`),
  ADD KEY `FK_StatRequirement_IDStat` (`IDStat`);

--
-- Index pour la table `step`
--
ALTER TABLE `step`
  ADD PRIMARY KEY (`IDStep`),
  ADD KEY `FK_Step_IDType` (`IDType`);

--
-- Index pour la table `steptype`
--
ALTER TABLE `steptype`
  ADD PRIMARY KEY (`IDType`),
  ADD UNIQUE KEY `Name` (`Name`);

--
-- AUTO_INCREMENT pour les tables exportées
--

--
-- AUTO_INCREMENT pour la table `achievement`
--
ALTER TABLE `achievement`
  MODIFY `IDAchievement` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT pour la table `admin`
--
ALTER TABLE `admin`
  MODIFY `IDAdmin` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;
--
-- AUTO_INCREMENT pour la table `choice`
--
ALTER TABLE `choice`
  MODIFY `IDChoice` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=32;
--
-- AUTO_INCREMENT pour la table `item`
--
ALTER TABLE `item`
  MODIFY `IDItem` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;
--
-- AUTO_INCREMENT pour la table `player`
--
ALTER TABLE `player`
  MODIFY `IDPlayer` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;
--
-- AUTO_INCREMENT pour la table `stat`
--
ALTER TABLE `stat`
  MODIFY `IDStat` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;
--
-- AUTO_INCREMENT pour la table `step`
--
ALTER TABLE `step`
  MODIFY `IDStep` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=34;
--
-- AUTO_INCREMENT pour la table `steptype`
--
ALTER TABLE `steptype`
  MODIFY `IDType` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;
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
  ADD CONSTRAINT `FK_Choice_IDStep` FOREIGN KEY (`IDStep`) REFERENCES `step` (`IDStep`),
  ADD CONSTRAINT `choice_ibfk_1` FOREIGN KEY (`IDNextStep`) REFERENCES `step` (`IDStep`);

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
  ADD CONSTRAINT `FK_PastStep_IDPlayer` FOREIGN KEY (`IDPlayer`) REFERENCES `player` (`IDPlayer`),
  ADD CONSTRAINT `FK_PastStep_IDStep` FOREIGN KEY (`IDStep`) REFERENCES `step` (`IDStep`);

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
  ADD CONSTRAINT `FK_PlayerStat_IDPlayer` FOREIGN KEY (`IDPlayer`) REFERENCES `player` (`IDPlayer`),
  ADD CONSTRAINT `FK_PlayerStat_IDStat` FOREIGN KEY (`IDStat`) REFERENCES `stat` (`IDStat`);

--
-- Contraintes pour la table `statalteration`
--
ALTER TABLE `statalteration`
  ADD CONSTRAINT `FK_StatAlteration_IDChoice` FOREIGN KEY (`IDChoice`) REFERENCES `choice` (`IDChoice`),
  ADD CONSTRAINT `FK_StatAlteration_IDStat` FOREIGN KEY (`IDStat`) REFERENCES `stat` (`IDStat`);

--
-- Contraintes pour la table `statrequirement`
--
ALTER TABLE `statrequirement`
  ADD CONSTRAINT `FK_StatRequirement_IDChoice` FOREIGN KEY (`IDChoice`) REFERENCES `choice` (`IDChoice`),
  ADD CONSTRAINT `FK_StatRequirement_IDStat` FOREIGN KEY (`IDStat`) REFERENCES `stat` (`IDStat`);

--
-- Contraintes pour la table `step`
--
ALTER TABLE `step`
  ADD CONSTRAINT `FK_Step_IDType` FOREIGN KEY (`IDType`) REFERENCES `steptype` (`IDType`);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
