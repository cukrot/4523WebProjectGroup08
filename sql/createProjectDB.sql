-- MySQL dump 10.13  Distrib 8.0.40, for Win64 (x86_64)
--
-- Host: localhost    Database: projectdb
-- ------------------------------------------------------
-- Server version	8.4.3

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
-- Table structure for table `product`
--

DROP TABLE IF EXISTS `product`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `product` (
  `pid` int NOT NULL AUTO_INCREMENT,
  `pname` varchar(255) CHARACTER SET utf8mb3 NOT NULL,
  `pdesc` text CHARACTER SET utf8mb3,
  `pcost` decimal(12,2) NOT NULL,
  `pimage` varchar(255) CHARACTER SET utf8mb3 DEFAULT NULL,
  PRIMARY KEY (`pid`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `product`
--

LOCK TABLES `product` WRITE;
/*!40000 ALTER TABLE `product` DISABLE KEYS */;
INSERT INTO `product` VALUES 
(1,'Cyberpunk Truck C204','Explore the world of imaginative play with our vibrant and durable toy truck. Perfect for little hands, this truck will inspire endless storytelling adventures both indoors and outdoors. Made from high-quality materials, it is built to withstand hours of creative playtime.',19.90,NULL),
(2,'XDD Wooden Plane','Take to the skies with our charming wooden plane toy. Crafted from eco-friendly and child-safe materials, this beautifully designed plane sparks the imagination and encourages interactive play. With smooth edges and a sturdy construction, it\'s a delightful addition to any young aviator\'s toy collection.',9.90,NULL),
(3,'iRobot 3233GG','Introduce your child to the wonders of technology and robotics with our smart robot companion. Packed with interactive features and educational benefits, this futuristic toy engages curious minds and promotes STEM learning in a fun and engaging way. Watch as your child explores coding, problem-solving, and innovation with this cutting-edge robot friend.',249.90,NULL),
(4,'Apex Ball Ball Helicopter M1297','Experience the thrill of flight with our ball helicopter toy. Easy to launch and navigate, this exciting toy provides hours of entertainment for children of all ages. With colorful LED lights and a durable design, it\'s a fantastic outdoor toy that brings joy and excitement to playtime.',30.00,NULL),
(5,'RoboKat AI Cat Robot','Meet our AI Cat Robot – the purr-fect blend of technology and cuddly companionship. This interactive robotic feline offers lifelike movements, sounds, and responses, providing a realistic pet experience without the hassle. With customizable features and playful interactions, this charming cat robot is a delightful addition to your child\'s playroom.',499.00,NULL);
/*!40000 ALTER TABLE `product` ENABLE KEYS */;
UNLOCK TABLES;

/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;
/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2025-01-17 16:57:57