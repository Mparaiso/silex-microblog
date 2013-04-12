-- MySQL dump 10.13  Distrib 5.5.8, for Win32 (x86)
--
-- Host: localhost    Database: megatutorial
-- ------------------------------------------------------
-- Server version	5.5.8

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `blog_account`
--

DROP TABLE IF EXISTS `blog_account`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `blog_account` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) DEFAULT NULL,
  `username` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `bio` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `last_login` datetime NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `UNIQ_91C0EB27F85E0677` (`username`),
  UNIQUE KEY `UNIQ_91C0EB27E7927C74` (`email`),
  UNIQUE KEY `UNIQ_91C0EB27A76ED395` (`user_id`),
  CONSTRAINT `FK_91C0EB27A76ED395` FOREIGN KEY (`user_id`) REFERENCES `blog_users` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `blog_account`
--

LOCK TABLES `blog_account` WRITE;
/*!40000 ALTER TABLE `blog_account` DISABLE KEYS */;
/*!40000 ALTER TABLE `blog_account` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `blog_accounts`
--

DROP TABLE IF EXISTS `blog_accounts`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `blog_accounts` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) DEFAULT NULL,
  `username` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `bio` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `last_login` datetime DEFAULT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `UNIQ_BE95BA8BF85E0677` (`username`),
  UNIQUE KEY `UNIQ_BE95BA8BE7927C74` (`email`),
  UNIQUE KEY `UNIQ_BE95BA8BA76ED395` (`user_id`),
  CONSTRAINT `FK_BE95BA8BA76ED395` FOREIGN KEY (`user_id`) REFERENCES `blog_users` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `blog_accounts`
--

LOCK TABLES `blog_accounts` WRITE;
/*!40000 ALTER TABLE `blog_accounts` DISABLE KEYS */;
INSERT INTO `blog_accounts` VALUES (1,6,'Mat','josephmascis@yahoo.fr',NULL,NULL,'2013-04-05 20:22:28','2013-04-12 14:23:56'),(2,7,'camus','paraiso.marc@gmail.com','I love the sea !',NULL,'2013-04-05 20:36:09','2013-04-12 14:23:07'),(3,8,'SuperAdmin','','I rule the world !',NULL,'2013-04-05 20:36:55','2013-04-11 16:53:27'),(4,9,'aikah','aikah@free.fr','Cheers dude !',NULL,'2013-04-05 21:25:20','2013-04-07 11:43:47'),(5,10,'programnation',NULL,'love music',NULL,'2013-04-07 19:45:33','2013-04-11 19:32:29');
/*!40000 ALTER TABLE `blog_accounts` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `blog_followers`
--

DROP TABLE IF EXISTS `blog_followers`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `blog_followers` (
  `account_id` int(11) NOT NULL,
  `follower_id` int(11) NOT NULL,
  PRIMARY KEY (`account_id`,`follower_id`),
  KEY `IDX_217615E89B6B5FBA` (`account_id`),
  KEY `IDX_217615E8AC24F853` (`follower_id`),
  CONSTRAINT `FK_217615E8AC24F853` FOREIGN KEY (`follower_id`) REFERENCES `blog_accounts` (`id`),
  CONSTRAINT `FK_217615E89B6B5FBA` FOREIGN KEY (`account_id`) REFERENCES `blog_accounts` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `blog_followers`
--

LOCK TABLES `blog_followers` WRITE;
/*!40000 ALTER TABLE `blog_followers` DISABLE KEYS */;
INSERT INTO `blog_followers` VALUES (1,1),(1,3),(1,5),(2,1),(2,2),(2,3),(2,5),(3,2),(3,5),(4,1),(4,2),(4,5);
/*!40000 ALTER TABLE `blog_followers` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `blog_posts`
--

DROP TABLE IF EXISTS `blog_posts`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `blog_posts` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `account_id` int(11) DEFAULT NULL,
  `title` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `body` longtext COLLATE utf8_unicode_ci NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `post_title_index` (`title`),
  KEY `IDX_78B2F9329B6B5FBA` (`account_id`),
  CONSTRAINT `FK_78B2F9329B6B5FBA` FOREIGN KEY (`account_id`) REFERENCES `blog_accounts` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=34 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `blog_posts`
--

LOCK TABLES `blog_posts` WRITE;
/*!40000 ALTER TABLE `blog_posts` DISABLE KEYS */;
INSERT INTO `blog_posts` VALUES (3,1,NULL,'first post !','2013-04-07 11:25:40','2013-04-07 11:25:40'),(4,1,NULL,'this is a second post !','2013-04-07 11:37:42','2013-04-07 11:37:42'),(5,2,NULL,'this is a post by camus !','2013-04-07 11:41:49','2013-04-07 11:41:49'),(6,4,NULL,'this is a post from aikah ! cool !','2013-04-07 11:44:01','2013-04-07 11:44:01'),(7,3,NULL,'Welcome to Microblog !','2013-04-07 13:10:32','2013-04-07 13:10:32'),(8,5,NULL,'this is a first post','2013-04-07 19:45:49','2013-04-07 19:45:49'),(9,5,NULL,'this is a second post','2013-04-07 19:45:59','2013-04-07 19:45:59'),(10,5,NULL,'this is a third post','2013-04-07 19:46:06','2013-04-07 19:46:06'),(11,5,NULL,'this is another post !','2013-04-07 19:46:16','2013-04-07 19:46:16'),(12,5,NULL,'this is a last post for today','2013-04-07 19:46:26','2013-04-07 19:46:26'),(13,5,NULL,'is it ? i dont know ;)','2013-04-07 19:46:34','2013-04-07 19:46:34'),(14,2,NULL,'hi how are you guys ?','2013-04-07 19:58:01','2013-04-07 19:58:01'),(15,2,NULL,'great stuff today on TV !','2013-04-07 19:58:12','2013-04-07 19:58:12'),(16,2,NULL,'ok this is a new message guys !','2013-04-08 11:34:40','2013-04-08 11:34:40'),(17,2,NULL,'awesome i like it !','2013-04-08 11:35:03','2013-04-08 11:35:03'),(18,1,NULL,'I\'m back dudes !','2013-04-08 11:38:20','2013-04-08 11:38:20'),(19,2,NULL,'this site is awesome !','2013-04-08 12:04:57','2013-04-08 12:04:57'),(20,2,NULL,'let\'s make 10 posts dude !','2013-04-08 12:21:26','2013-04-08 12:21:26'),(21,2,NULL,'another one !','2013-04-08 12:21:33','2013-04-08 12:21:33'),(22,2,NULL,'a last one !','2013-04-08 12:21:40','2013-04-08 12:21:40'),(23,2,NULL,'are we there yet ?','2013-04-08 12:21:49','2013-04-08 12:21:49'),(24,2,NULL,'nope !','2013-04-08 12:22:18','2013-04-08 12:22:18'),(25,2,NULL,'i have more than 10 posts now , i can make a longer one and see how it displays on the screen , interesting isnt it ?','2013-04-08 12:23:32','2013-04-08 12:23:32'),(26,1,NULL,'It\'s me ! how are you dudes ?','2013-04-08 15:06:58','2013-04-08 15:06:58'),(27,1,NULL,'this form is working well dudes ! that\'s cool ;)','2013-04-08 15:59:54','2013-04-08 15:59:54'),(28,3,NULL,'This is microblog dude !','2013-04-08 17:19:58','2013-04-08 17:19:58'),(29,2,NULL,'i\'m back baby !','2013-04-08 17:37:00','2013-04-08 17:37:00'),(30,3,NULL,'i followed my first friend ! cool .','2013-04-11 16:34:41','2013-04-11 16:34:41'),(31,5,NULL,'Did i made a post ?','2013-04-11 18:20:18','2013-04-11 18:20:18'),(32,2,NULL,'Another awesome post !','2013-04-12 14:07:34','2013-04-12 14:07:34'),(33,1,NULL,'Voici un nouveau message ! cool !','2013-04-12 14:24:19','2013-04-12 14:24:19');
/*!40000 ALTER TABLE `blog_posts` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `blog_roles`
--

DROP TABLE IF EXISTS `blog_roles`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `blog_roles` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `role` varchar(200) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `blog_roles`
--

LOCK TABLES `blog_roles` WRITE;
/*!40000 ALTER TABLE `blog_roles` DISABLE KEYS */;
INSERT INTO `blog_roles` VALUES (1,'user','ROLE_USER');
/*!40000 ALTER TABLE `blog_roles` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `blog_users`
--

DROP TABLE IF EXISTS `blog_users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `blog_users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `password` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `salt` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `UNIQ_E46CE621F85E0677` (`username`)
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `blog_users`
--

LOCK TABLES `blog_users` WRITE;
/*!40000 ALTER TABLE `blog_users` DISABLE KEYS */;
INSERT INTO `blog_users` VALUES (6,'https://me.yahoo.com/a/AB_Fjbl7mc6bPwieYzP5Oprtl9w2nP8zug--','7lG5HxxD5P/7sJnCmv33uel5V9d4J6tretGbtmgLHz4Qt9wgk0ALBGE+VX2zcJhndgeYslY2wqqxlnUrz/z4JA==','515f16644f486','2013-04-05 20:22:28','2013-04-05 20:22:28'),(7,'https://www.google.com/accounts/o8/id?id=AItOawmVh5SMcaqgu8wxlWEiY46CHirBdNgJad0','hfZzWGv+Q4bFnS+vvPUks5nU7GHvSn4PxHH9GwhriHu9rN+F+ru/uYBG+FzWg0tJjb6hfK0gTN8j9fTgu/Ev9w==','515f199935ec5','2013-04-05 20:36:09','2013-04-05 20:36:09'),(8,'https://camus3.myopenid.com/','nGypboUBxWEAiuph3qfOQStNUuvACBOXjLk4mwa9DIFnch2SwmH6xK0apKJkQPUw21TCQBQuy72liE0YzTKzag==','515f19c7592de','2013-04-05 20:36:55','2013-04-05 20:36:55'),(9,'http://camus2.wordpress.com/','yDx2ktcNfQvxpkWtGGH7/oYf7vLtM2ZtxP5SW49ebCEGp6YeVLhHiQAgbJvjEIWhmz0EC55S90JnEASL8hyyaQ==','515f252098eb4','2013-04-05 21:25:20','2013-04-05 21:25:20'),(10,'http://programnation.blogspot.com/','Wwp7hQjyBNnwSGbyP60OOfHt/vOb2VHO9y5gh0zm+v2x3cVUmhgHGjW+d4G3d0IxIcYDhTa7T57yjQCFs7BdGg==','5161b0bd36313','2013-04-07 19:45:33','2013-04-07 19:45:33');
/*!40000 ALTER TABLE `blog_users` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `user_role`
--

DROP TABLE IF EXISTS `user_role`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `user_role` (
  `user_id` int(11) NOT NULL,
  `role_id` int(11) NOT NULL,
  PRIMARY KEY (`user_id`,`role_id`),
  KEY `IDX_2DE8C6A3A76ED395` (`user_id`),
  KEY `IDX_2DE8C6A3D60322AC` (`role_id`),
  CONSTRAINT `FK_2DE8C6A3D60322AC` FOREIGN KEY (`role_id`) REFERENCES `blog_roles` (`id`) ON DELETE CASCADE,
  CONSTRAINT `FK_2DE8C6A3A76ED395` FOREIGN KEY (`user_id`) REFERENCES `blog_users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `user_role`
--

LOCK TABLES `user_role` WRITE;
/*!40000 ALTER TABLE `user_role` DISABLE KEYS */;
INSERT INTO `user_role` VALUES (6,1),(7,1),(8,1),(9,1),(10,1);
/*!40000 ALTER TABLE `user_role` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2013-04-12 14:40:43
