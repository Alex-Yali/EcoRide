CREATE DATABASE  IF NOT EXISTS `ecoride` /*!40100 DEFAULT CHARACTER SET utf8mb3 */ /*!80016 DEFAULT ENCRYPTION='N' */;
USE `ecoride`;
-- MySQL dump 10.13  Distrib 8.0.43, for Win64 (x86_64)
--
-- Host: localhost    Database: ecoride
-- ------------------------------------------------------
-- Server version	8.0.43

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!50503 SET NAMES utf8 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `avis`
--

DROP TABLE IF EXISTS `avis`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `avis` (
  `avis_id` int NOT NULL AUTO_INCREMENT,
  `commentaire` varchar(255) DEFAULT NULL,
  `note` varchar(50) DEFAULT NULL,
  `statut` varchar(50) DEFAULT NULL,
  `covoiturage_id` int DEFAULT NULL,
  `chauffeur_id` int DEFAULT NULL,
  `employe_id` int DEFAULT NULL,
  `etat` varchar(45) DEFAULT NULL,
  PRIMARY KEY (`avis_id`)
) ENGINE=InnoDB AUTO_INCREMENT=20 DEFAULT CHARSET=utf8mb3;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `avis`
--

LOCK TABLES `avis` WRITE;
/*!40000 ALTER TABLE `avis` DISABLE KEYS */;
INSERT INTO `avis` VALUES (1,'Le voyage s\'est  bien passée.','4','valider',17,2,9,'ok'),(2,'Le voyage s\'est pas très bien passée','3','refuser',2,3,9,'ok'),(8,'Le voyage avec Tom s\'est très bien passée. Un long voyage passé dans une ambiance cool et conviviale. Je le recommande.','5','valider',17,2,10,'ok'),(16,'trop long','1','refuser',1,2,10,'nok'),(18,'Impec','5','refuser',20,8,9,'ok');
/*!40000 ALTER TABLE `avis` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `covoiturage`
--

DROP TABLE IF EXISTS `covoiturage`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `covoiturage` (
  `covoiturage_id` int NOT NULL AUTO_INCREMENT,
  `date_depart` date DEFAULT NULL,
  `heure_depart` time DEFAULT NULL,
  `lieu_depart` varchar(50) NOT NULL,
  `date_arrivee` date NOT NULL,
  `heure_arrivee` time DEFAULT NULL,
  `lieu_arrivee` varchar(50) NOT NULL,
  `statut` varchar(50) DEFAULT NULL,
  `nb_place` int NOT NULL,
  `prix_personne` float NOT NULL,
  PRIMARY KEY (`covoiturage_id`)
) ENGINE=InnoDB AUTO_INCREMENT=54 DEFAULT CHARSET=utf8mb3;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `covoiturage`
--

LOCK TABLES `covoiturage` WRITE;
/*!40000 ALTER TABLE `covoiturage` DISABLE KEYS */;
INSERT INTO `covoiturage` VALUES (1,'2027-01-26','08:30:00','Paris','2027-01-26','13:30:00','Lyon','Annuler',3,5),(2,'2027-01-26','10:00:00','Paris','2027-01-26','15:30:00','Lyon','',2,5),(3,'2027-01-26','13:30:00','Paris','2027-01-26','18:45:00','Lyon','',2,4),(4,'2027-01-16','09:30:00','Lyon','2027-01-16','13:00:00','Marseille',NULL,2,3),(5,'2027-01-16','11:00:00','Lyon','2027-01-16','14:00:00','Marseille',NULL,2,4),(6,'2027-01-16','14:00:00','Lyon','2027-01-16','17:15:00','Marseille',NULL,2,5),(7,'2026-10-22','06:00:00','Marseille','2026-10-22','09:00:00','Nice',NULL,2,6),(8,'2026-10-22','08:00:00','Marseille','2026-10-22','12:00:00','Nice','',2,6),(9,'2026-10-22','09:30:00','Marseille','2026-10-22','12:30:00','Nice',NULL,2,5),(10,'2026-10-23','07:45:00','Lille','2026-10-23','11:00:00','Paris',NULL,2,7),(11,'2026-10-23','10:00:00','Bordeaux','2026-10-23','13:00:00','Toulouse',NULL,2,3),(12,'2026-10-24','06:30:00','Nantes','2026-10-24','08:30:00','Rennes',NULL,2,7),(13,'2026-10-24','09:00:00','Strasbourg','2026-10-24','10:15:00','Mulhouse',NULL,2,10),(14,'2026-10-25','07:15:00','Lyon','2026-10-25','09:00:00','Grenoble','',2,12),(15,'2026-10-25','08:30:00','Nice','2026-10-25','09:30:00','Cannes',NULL,2,4),(16,'2026-10-26','06:45:00','Toulouse','2026-10-26','09:00:00','Montpellier',NULL,2,9),(17,'2026-10-26','13:00:00','Paris','2026-10-26','17:00:00','Lille','',2,8),(18,'2026-10-27','08:15:00','Rennes','2026-10-27','10:15:00','Nantes','',2,12),(19,'2026-10-27','14:00:00','Bordeaux','2026-10-27','20:00:00','Paris',NULL,2,9),(20,'2026-10-28','09:00:00','Lyon','2026-10-28','12:00:00','Marseille','Terminer',2,5),(52,'2026-07-16','11:30:00','Paris','2026-07-16','17:15:00','Lyon',NULL,3,6);
/*!40000 ALTER TABLE `covoiturage` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `depose`
--

DROP TABLE IF EXISTS `depose`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `depose` (
  `utilisateur_utilisateur_id` int NOT NULL,
  `avis_avis_id` int NOT NULL,
  PRIMARY KEY (`avis_avis_id`),
  KEY `fk_depose_avis1_idx` (`avis_avis_id`),
  KEY `fk_depose_utilisateur1` (`utilisateur_utilisateur_id`),
  CONSTRAINT `fk_depose_avis1` FOREIGN KEY (`avis_avis_id`) REFERENCES `avis` (`avis_id`),
  CONSTRAINT `fk_depose_utilisateur1` FOREIGN KEY (`utilisateur_utilisateur_id`) REFERENCES `utilisateur` (`utilisateur_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `depose`
--

LOCK TABLES `depose` WRITE;
/*!40000 ALTER TABLE `depose` DISABLE KEYS */;
INSERT INTO `depose` VALUES (1,1),(1,8),(1,18),(3,16),(4,2);
/*!40000 ALTER TABLE `depose` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `detient`
--

DROP TABLE IF EXISTS `detient`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `detient` (
  `voiture_voiture_id` int NOT NULL,
  `marque_marque_id` int NOT NULL,
  PRIMARY KEY (`voiture_voiture_id`),
  KEY `fk_detient_marque1_idx` (`marque_marque_id`),
  CONSTRAINT `fk_detient_voiture1` FOREIGN KEY (`voiture_voiture_id`) REFERENCES `voiture` (`voiture_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `detient`
--

LOCK TABLES `detient` WRITE;
/*!40000 ALTER TABLE `detient` DISABLE KEYS */;
INSERT INTO `detient` VALUES (1,1),(2,1),(3,2),(4,3),(5,4),(6,5),(7,6),(8,7);
/*!40000 ALTER TABLE `detient` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `gere`
--

DROP TABLE IF EXISTS `gere`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `gere` (
  `utilisateur_utilisateur_id` int NOT NULL,
  `voiture_voiture_id` int NOT NULL,
  PRIMARY KEY (`voiture_voiture_id`),
  KEY `fk_gere_voiture1_idx` (`voiture_voiture_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `gere`
--

LOCK TABLES `gere` WRITE;
/*!40000 ALTER TABLE `gere` DISABLE KEYS */;
INSERT INTO `gere` VALUES (2,1),(2,2),(3,3),(3,4),(7,5),(7,6),(8,7),(8,8);
/*!40000 ALTER TABLE `gere` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `marque`
--

DROP TABLE IF EXISTS `marque`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `marque` (
  `marque_id` int NOT NULL AUTO_INCREMENT,
  `libelle` varchar(50) NOT NULL,
  PRIMARY KEY (`marque_id`),
  UNIQUE KEY `marque_id_UNIQUE` (`marque_id`)
) ENGINE=InnoDB AUTO_INCREMENT=18 DEFAULT CHARSET=utf8mb3;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `marque`
--

LOCK TABLES `marque` WRITE;
/*!40000 ALTER TABLE `marque` DISABLE KEYS */;
INSERT INTO `marque` VALUES (1,'Renault'),(2,'Citroen'),(3,'Peugeot'),(4,'Toyota'),(5,'Tesla'),(6,'Kia'),(7,'Mercedes'),(8,'Volkswagen');
/*!40000 ALTER TABLE `marque` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `participe`
--

DROP TABLE IF EXISTS `participe`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `participe` (
  `utilisateur_utilisateur_id` int NOT NULL,
  `covoiturage_covoiturage_id` int NOT NULL,
  `passager` tinyint NOT NULL DEFAULT '1',
  `chauffeur` tinyint NOT NULL DEFAULT '0',
  PRIMARY KEY (`utilisateur_utilisateur_id`,`covoiturage_covoiturage_id`),
  KEY `fk_participe_covoiturage1_idx` (`covoiturage_covoiturage_id`),
  CONSTRAINT `fk_participe_utilisateur1` FOREIGN KEY (`utilisateur_utilisateur_id`) REFERENCES `utilisateur` (`utilisateur_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `participe`
--

LOCK TABLES `participe` WRITE;
/*!40000 ALTER TABLE `participe` DISABLE KEYS */;
INSERT INTO `participe` VALUES (1,8,1,0),(1,11,1,0),(1,14,1,0),(1,17,1,0),(1,20,1,0),(2,1,0,1),(2,5,0,1),(2,9,0,1),(2,13,0,1),(2,17,0,1),(2,52,0,1),(3,2,0,1),(3,6,0,1),(3,10,0,1),(3,14,0,1),(3,18,0,1),(4,2,1,0),(4,5,1,0),(4,12,1,0),(4,15,1,0),(4,18,1,0),(5,3,1,0),(5,6,1,0),(5,9,1,0),(5,16,1,0),(5,19,1,0),(6,4,1,0),(6,7,1,0),(6,10,1,0),(6,13,1,0),(6,20,1,0),(7,3,0,1),(7,7,0,1),(7,11,0,1),(7,15,0,1),(7,19,0,1),(8,4,0,1),(8,8,0,1),(8,12,0,1),(8,16,0,1),(8,20,0,1),(12,1,1,0),(12,2,1,0);
/*!40000 ALTER TABLE `participe` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `possede`
--

DROP TABLE IF EXISTS `possede`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `possede` (
  `utilisateur_utilisateur_id` int NOT NULL,
  `role_role_id` int NOT NULL,
  PRIMARY KEY (`utilisateur_utilisateur_id`,`role_role_id`),
  KEY `fk_possede_role1_idx` (`role_role_id`),
  CONSTRAINT `fk_possede_utilisateur1` FOREIGN KEY (`utilisateur_utilisateur_id`) REFERENCES `utilisateur` (`utilisateur_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `possede`
--

LOCK TABLES `possede` WRITE;
/*!40000 ALTER TABLE `possede` DISABLE KEYS */;
INSERT INTO `possede` VALUES (9,1),(10,1),(11,2),(56,2),(1,3),(2,3),(3,3),(4,3),(5,3),(6,3),(7,3),(8,3),(12,3);
/*!40000 ALTER TABLE `possede` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `role`
--

DROP TABLE IF EXISTS `role`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `role` (
  `role_id` int NOT NULL AUTO_INCREMENT,
  `libelle` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`role_id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb3;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `role`
--

LOCK TABLES `role` WRITE;
/*!40000 ALTER TABLE `role` DISABLE KEYS */;
INSERT INTO `role` VALUES (1,'employe'),(2,'admin'),(3,'utilisateur');
/*!40000 ALTER TABLE `role` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `utilisateur`
--

DROP TABLE IF EXISTS `utilisateur`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `utilisateur` (
  `utilisateur_id` int NOT NULL AUTO_INCREMENT,
  `nom` varchar(50) DEFAULT NULL,
  `prenom` varchar(50) DEFAULT NULL,
  `email` varchar(50) NOT NULL,
  `password` varchar(255) DEFAULT NULL,
  `telephone` varchar(50) DEFAULT NULL,
  `adresse` varchar(50) DEFAULT NULL,
  `date_naissance` varchar(50) DEFAULT NULL,
  `photo` blob,
  `pseudo` varchar(50) NOT NULL,
  `credits` int NOT NULL,
  `passager` tinyint NOT NULL DEFAULT '1',
  `chauffeur` tinyint NOT NULL DEFAULT '0',
  PRIMARY KEY (`utilisateur_id`),
  UNIQUE KEY `utilisateur_id_UNIQUE` (`utilisateur_id`),
  UNIQUE KEY `email_UNIQUE` (`email`),
  UNIQUE KEY `pseudo_UNIQUE` (`pseudo`)
) ENGINE=InnoDB AUTO_INCREMENT=62 DEFAULT CHARSET=utf8mb3;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `utilisateur`
--

LOCK TABLES `utilisateur` WRITE;
/*!40000 ALTER TABLE `utilisateur` DISABLE KEYS */;
INSERT INTO `utilisateur` VALUES (1,'Pierre','Martin','martin.pierre@gmail.com','$2y$10$nd48SFTnj11CBvLtTePu9.dQzPKZ1eJHDIlSrWdXMiAXDUrn3xHD.','0733556677','2 rue des coquelicot','26/11/1985',NULL,'Pierre',20,1,0),(2,'Jones','Tom','tom.jones@gmail.com','$2y$10$OR94xiHV/1U47nG5X2gwF.iqFjqH1xMI7Vk3yD1s9WI.otmWU.h1.',NULL,NULL,NULL,NULL,'Tom',20,0,1),(3,'Eric','Antoine','antoine.eric@gmail.com','$2y$10$WnTG8VkGm7.5uLTkDZPIdOFXvsQXTMYMZXRwMjR7bLE6vycpwGiNW',NULL,NULL,NULL,NULL,'Antoine',20,1,1),(4,'Petitjean','Jean','jean.petitjean@gmail.com','$2y$10$pJaLZQCciZODVEUkQdhcsehR3NGvOYMwtk4yyCcymKdoLxldUr4Ua',NULL,NULL,NULL,NULL,'Jean',20,1,0),(5,'Marchand','Marie','marie.marchand@mail.com','$2y$10$flHM16avn5iSW/UzlfXNguvmFdf5/ClFEHpV3Q.OsebFg2O6KOo4K',NULL,NULL,NULL,NULL,'Marie',20,1,0),(6,'Duval','Stephanie','stephanie.duval@wanadoo.fr','$2y$10$OUz.ieRNXpBLqfvWJq9OtOvGJXXLQqKztDVhrQ5czowyqG6ob9lTy',NULL,NULL,NULL,NULL,'Stephanie',20,1,0),(7,'Rolland','Manon','manon.rolland@gmail.com','$2y$10$IHUr.lXfLXbcWWwvWx8KzOK/jMu8I.QldoW73z1WHvBYW4pOTcEvW',NULL,NULL,NULL,NULL,'Manon',20,0,1),(8,'Bob','Moreau','bob@mail.com','$2y$10$E3oSxjr7tlrI56Mr3RVEIeGKw5CvY8gl4rAg9MUo5BO8.gbQ79BlC',NULL,NULL,NULL,NULL,'Bob',20,0,1),(9,'Dupont','Léa','lea.dupont@mail.com','$2y$10$K6hcbZljh1koSnOOsHTfJOx/oj1kVaZzR0xX7fJutczUreRXw.DeW',NULL,NULL,NULL,NULL,'Léa',20,1,1),(10,'Frank','Arthur','arthur.frank@mail.com','$2y$10$Ww7MsAo8hDiTzqvXZAPULuSJKDM4QP9Ga/JwgD6RtvKDYkUtzhUc.',NULL,NULL,NULL,NULL,'Arthur',20,1,1),(11,'Henry','Mathieu','mathieu.henry@mail.com','$2y$10$NuSzbianxTsccXESlGrWl.wSDB7bgZ3f/StVHpjzhSgx7WBZAWFjm',NULL,NULL,NULL,NULL,'Mathieu',20,1,1),(12,NULL,NULL,'alexandre.yalicheff@gmail.com','$2y$10$tHc7KPn9GnBCqClvklfKVudaFcTlusSAe4b0NiGdbkfMGKJxfoOsi',NULL,NULL,NULL,NULL,'Alex',15,1,0),(56,NULL,NULL,'thomas.bernard@mail.com','$2y$10$8.reAa7pyt9VSwtnNZWnB.iZoaOFV57rbjgET6Mbhi/GrpyxMz6eO',NULL,NULL,NULL,NULL,'Thomas',20,1,0);
/*!40000 ALTER TABLE `utilisateur` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `utilise`
--

DROP TABLE IF EXISTS `utilise`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `utilise` (
  `voiture_voiture_id` int NOT NULL,
  `covoiturage_covoiturage_id` int NOT NULL,
  PRIMARY KEY (`covoiturage_covoiturage_id`),
  KEY `fk_utilise_covoiturage1_idx` (`covoiturage_covoiturage_id`),
  KEY `fk_utilise_voiture1` (`voiture_voiture_id`),
  CONSTRAINT `fk_utilise_voiture1` FOREIGN KEY (`voiture_voiture_id`) REFERENCES `voiture` (`voiture_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `utilise`
--

LOCK TABLES `utilise` WRITE;
/*!40000 ALTER TABLE `utilise` DISABLE KEYS */;
INSERT INTO `utilise` VALUES (1,1),(1,9),(1,17),(2,5),(2,13),(2,52),(3,2),(3,10),(3,18),(4,6),(4,14),(5,3),(5,11),(5,19),(6,7),(6,15),(7,4),(7,12),(7,20),(8,8),(8,16);
/*!40000 ALTER TABLE `utilise` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `voiture`
--

DROP TABLE IF EXISTS `voiture`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `voiture` (
  `voiture_id` int NOT NULL AUTO_INCREMENT,
  `modele` varchar(50) NOT NULL,
  `immatriculation` varchar(50) NOT NULL,
  `energie` varchar(50) NOT NULL,
  `couleur` varchar(50) NOT NULL,
  `date_premiere_immatriculation` varchar(50) NOT NULL,
  `nb_place` int DEFAULT NULL,
  PRIMARY KEY (`voiture_id`),
  UNIQUE KEY `voiture_id_UNIQUE` (`voiture_id`),
  UNIQUE KEY `immatriculation_UNIQUE` (`immatriculation`)
) ENGINE=InnoDB AUTO_INCREMENT=60 DEFAULT CHARSET=utf8mb3;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `voiture`
--

LOCK TABLES `voiture` WRITE;
/*!40000 ALTER TABLE `voiture` DISABLE KEYS */;
INSERT INTO `voiture` VALUES (1,'Megane 4','fr-267-fr','essence','bleue','16 mai 1995',3),(2,'Zoé','pf-950-tm','electrique','blanche','25 octobre 2010',3),(3,'C4','mp-424-mp','essence','rouge','19 janvier 2013',3),(4,'308','gf-535-dp','essence','verte','02 mars 2022',3),(5,'Yaris','pv-898-gt','essence','bleue','22 avril 2017',3),(6,'Model Y','uf-459-kf','electrique','grise','19 juin 2021',3),(7,'EV6','je-589-hd','electrique','maron','05 septembre 2020',3),(8,'CLA','hd-931-ls','electrique','rouge','26 mai 2022',3);
/*!40000 ALTER TABLE `voiture` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2026-01-07 15:02:26
