CREATE DATABASE IF NOT EXISTS `bddExemple` DEFAULT CHARACTER SET latin1 COLLATE latin1_swedish_ci;
USE `bddExemple`;

DROP TABLE IF EXISTS `personne`;
CREATE TABLE IF NOT EXISTS `personne` (  `idpersonne` int(11) NOT NULL AUTO_INCREMENT,   `nompersonne` varchar(50) NOT NULL,   passwd varchar(255) NOT NULL,   PRIMARY KEY (`idpersonne`) ) ENGINE=InnoDB AUTO_INCREMENT=16 DEFAULT CHARSET=latin1;