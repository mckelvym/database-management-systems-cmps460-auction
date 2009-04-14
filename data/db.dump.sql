-- MySQL dump 10.11
--
-- Host: localhost    Database: cs4601_i
-- ------------------------------------------------------
-- Server version	5.0.67-0ubuntu6

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
-- Table structure for table `bids_on`
--

DROP TABLE IF EXISTS `bids_on`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `bids_on` (
  `username` varchar(50) NOT NULL default '',
  `item_title` varchar(50) NOT NULL default '',
  `item_seller` varchar(50) NOT NULL default '',
  `item_category` varchar(30) NOT NULL default '',
  `item_end_day` int(5) NOT NULL default '0',
  `item_end_hour` int(2) NOT NULL default '0',
  `item_end_minute` int(2) NOT NULL default '0',
  `bid_day` int(5) NOT NULL default '0',
  `bid_hour` int(2) NOT NULL default '0',
  `bid_minute` int(2) NOT NULL default '0',
  `bid_amount` float(7,2) default NULL,
  `display_notification` char(1) default NULL,
  PRIMARY KEY  (`username`,`item_title`,`item_seller`,`item_category`,`item_end_day`,`item_end_hour`,`item_end_minute`,`bid_day`,`bid_hour`,`bid_minute`),
  KEY `item_title` (`item_title`),
  KEY `item_seller` (`item_seller`),
  KEY `item_category` (`item_category`),
  KEY `item_end_day` (`item_end_day`),
  KEY `item_end_hour` (`item_end_hour`),
  KEY `item_end_minute` (`item_end_minute`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
SET character_set_client = @saved_cs_client;

--
-- Dumping data for table `bids_on`
--

LOCK TABLES `bids_on` WRITE;
/*!40000 ALTER TABLE `bids_on` DISABLE KEYS */;
INSERT INTO `bids_on` VALUES ('sayuj','Baseball Glove','trey','Sporting Goods',3,0,12,0,0,14,35.00,'y'),('dallas','Baseball Glove','trey','Sporting Goods',3,0,12,0,0,15,36.00,'y'),('mark','Baseball Glove','trey','Sporting Goods',3,0,12,0,0,16,37.00,'n'),('mark','Walrus Appreciation 2','dallas','Books',2,0,8,0,0,17,20.00,'n'),('jim','Walrus Appreciation 3','dallas','Books',2,0,9,0,0,18,20.00,'n'),('dallas','Chauncy Lifestyles','mark','Books',2,0,10,0,0,19,29.00,'n'),('trey','Sayuj Rules T-Shirt','sayuj','Clothing',2,0,11,2,0,10,40.00,'n');
/*!40000 ALTER TABLE `bids_on` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `item_listing`
--

DROP TABLE IF EXISTS `item_listing`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `item_listing` (
  `title` varchar(50) NOT NULL default '',
  `seller` varchar(50) NOT NULL default '',
  `category` varchar(30) NOT NULL default '',
  `end_day` int(5) NOT NULL default '0',
  `end_hour` int(2) NOT NULL default '0',
  `end_minute` int(2) NOT NULL default '0',
  `description` varchar(250) default NULL,
  `shipping_cost` float(7,2) default NULL,
  `shipping_method` varchar(100) default NULL,
  `starting_price` float(7,2) default NULL,
  `current_price` float(7,2) default NULL,
  `picture` varchar(100) default NULL,
  `buyer` varchar(50) default NULL,
  `buyerfeedbackforseller_description` varchar(250) default NULL,
  `buyerfeedbackforseller_rating` int(2) default NULL,
  `sellerfeedbackforbuyer_description` varchar(250) default NULL,
  PRIMARY KEY  (`title`,`seller`,`category`,`end_day`,`end_hour`,`end_minute`),
  KEY `seller` (`seller`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
SET character_set_client = @saved_cs_client;

--
-- Dumping data for table `item_listing`
--

LOCK TABLES `item_listing` WRITE;
/*!40000 ALTER TABLE `item_listing` DISABLE KEYS */;
INSERT INTO `item_listing` VALUES ('Walrus Appreciation','dallas','Books',2,0,7,'A book about walruses',6.00,'FedEx',19.00,19.00,'books1.jpg','','',-1,''),('Walrus Appreciation 2','dallas','Books',2,0,8,'A book about walruses',6.00,'FedEx',19.00,19.00,'books2.jpg','','',-1,''),('Walrus Appreciation 3','dallas','Books',2,0,9,'A book about walruses',6.00,'FedEx',19.00,19.00,'books3.jpg','','',-1,''),('Chauncy Lifestyles','mark','Books',2,0,10,'Golden curtain rods blue carpet and white statues',5.00,'UPS',25.00,25.00,'books1.jpg','','',-1,''),('Sayuj Rules T-Shirt','sayuj','Clothing',2,0,11,'A T-Shirt advertising the ruling of Sayuj',4.00,'UPS',7.00,7.00,'clothing1.jpg','','',-1,''),('Baseball Glove','trey','Sporting Goods',3,0,12,'A great baseball glove',8.00,'FedEx',27.00,37.00,'sporting_goods1.jpg','','',-1,''),('Bracelet','trey','Jewelry',2,0,13,'Something I found on the ground',10.00,'UPS',100.00,100.00,'jewelry1.jpg','','',-1,''),('BoomBox','toby','Electronics',4,0,12,'Comes with cardboard for breakdancing',17.00,'FedEx',85.00,85.00,'electronics1.jpg','','',-1,''),('Paint Brushes','toby','Art',4,0,13,'All Sizes',2.00,'UPS',17.00,17.00,'art1.jpg','','',-1,''),('Baseball Cards','toby','Collectibles',4,0,14,'Random grab bag of cards',4.00,'FedEx',1.00,1.00,'collectibles1.jpg','','',-1,''),('Jazz CDs','toby','Toys',4,0,15,'30s and 40s',10.00,'FedEx',7.00,7.00,'entertainment1.jpg','','',-1,''),('He-Man Action Figure','toby','Toys',4,0,16,'Still in box!',4.00,'UPS',75.00,75.00,'toys1.jpg','','',-1,''),('Wind Up Bird Chronicles','jim','Books',4,0,17,'A recommended book',3.00,'UPS',12.50,12.50,'books2.jpg','','',-1,''),('Socks','jim','Clothing',4,0,18,'Old but not too yellow',2.00,'UPS',0.50,0.50,'clothing2.jpg','','',-1,''),('Disc Golf Disc','jim','Sporting Goods',4,0,19,'yellow and round',4.00,'UPS',17.50,17.50,'sports2.jpg','','',-1,''),('Necklace','jim','Jewelry',4,0,20,'diamond pendant',3.00,'UPS',79.00,79.00,'jewelry2.jpg','','',-1,''),('Car Alarm','jim','Electronics',4,0,21,'No more car breakins!',7.00,'UPS',81.00,81.00,'electronics2.jpg','','',-1,''),('A sculpture','jim','Art',4,0,22,'Full-figured and Chauncy',70.00,'UPS',325.00,325.00,'art2.jpg','','',-1,''),('Stamp Collection','jim','Collectibles',4,0,23,'vast',3.00,'FedEx',100.00,100.00,'collectables2.jpg','','',-1,''),('Rap Records','jim','Entertainment',4,0,24,'Ghostface Killah',5.00,'FedEx',20.00,20.00,'entertainment2.jpg','','',-1,''),('Plasic Dinosaur','jim','Toys',4,0,25,'bendable',7.00,'FedEx',100.00,100.00,'toys2.jpg','','',-1,'');
/*!40000 ALTER TABLE `item_listing` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `user`
--

DROP TABLE IF EXISTS `user`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `user` (
  `username` varchar(50) NOT NULL default '',
  `password` varchar(50) default NULL,
  `is_admin` tinyint(1) default NULL,
  `realname` varchar(100) default NULL,
  `birth_date` varchar(10) default NULL,
  `shipping_street` varchar(100) default NULL,
  `shipping_city` varchar(50) default NULL,
  `shipping_state` varchar(25) default NULL,
  `shipping_zip` varchar(10) default NULL,
  `phone` varchar(12) default NULL,
  `email` varchar(50) default NULL,
  `card_type` varchar(20) default NULL,
  `card_number` varchar(16) default NULL,
  `card_expire` varchar(5) default NULL,
  `picture` varchar(100) default NULL,
  `description` varchar(250) default NULL,
  PRIMARY KEY  (`username`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
SET character_set_client = @saved_cs_client;

--
-- Dumping data for table `user`
--

LOCK TABLES `user` WRITE;
/*!40000 ALTER TABLE `user` DISABLE KEYS */;
INSERT INTO `user` VALUES ('jim','foursixty',0,'Jim Etheredge','1975-10-01','151 Street','Lafayette','Louisiana','70503','337-555-5907','jne@louisiana.edu','Visa','5401667807681234','05/12','profile1.jpg','I\'m just a regular user.'),('jim_admin','foursixty',1,'Jim Etheredge!','1970-10-01','152 Street','Lafayette','Louisiana','70504','337-555-5905','jne@ull.edu','Mastercard','5701867809681034','03/11','profile2.jpg','I am the executioner.'),('mark','foursixty',0,'Mark McKelvy','1995-03-21','9851 Street','Lafayette','Louisiana','70505','337-545-5517','mark@louisiana.edu','American Express','8401567887611204','03/12','profile3.jpg','Just your average user.'),('dallas','foursixty',0,'Dallas Griffith','1980-9-24','1205 Lee Ave','Lafayette','Louisiana','70501','337.298-8766','dlg5367@louisiana.edu','Visa','1111111122222222','09/07','profile4.jpg','I am a guy who likes walrus books.'),('trey','foursixty',0,'Trey Alexander','1972-02-03','222 Main St','Lafayette','Louisiana','70506','337-222-5555','','American Express','1111222233334444','07/10','profile5.jpg','My son plays baseball. '),('sayuj','foursixty',0,'sayuj valsan','1984-05-27','119 Stevenson','Lafayette','Louisiana','70502','337-277-7221','sayuj@gmail.com','Mastercard','1234321012343210','09/24','',''),('nully','foursixty',0,'','','','','','','','','','','','',''),('toby','foursixty',0,'Toby McValrus','1982-01-03','721 Dog St','Lafayette','Louisiana','70503','504-227-6666','toby@hotmail.com','Mastercard','2222333344440000','08/14','profile6.jpg','i have nothing to say for myself');
/*!40000 ALTER TABLE `user` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `user_activity`
--

DROP TABLE IF EXISTS `user_activity`;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
CREATE TABLE `user_activity` (
  `username` varchar(50) NOT NULL default '',
  `day` int(5) NOT NULL default '0',
  `hour` int(2) NOT NULL default '0',
  `minute` int(2) NOT NULL default '0',
  `activity` varchar(250) default NULL,
  PRIMARY KEY  (`username`,`day`,`hour`,`minute`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
SET character_set_client = @saved_cs_client;

--
-- Dumping data for table `user_activity`
--

LOCK TABLES `user_activity` WRITE;
/*!40000 ALTER TABLE `user_activity` DISABLE KEYS */;
INSERT INTO `user_activity` VALUES ('jim_admin',0,0,0,'Registered'),('jim',0,0,1,'Registered'),('mark',0,0,2,'Registered'),('dallas',0,0,3,'Registered'),('trey',0,0,4,'Registered'),('trey',0,0,5,'Logged Out'),('sayuj',0,0,5,'Registered'),('sayuj',0,0,6,'Logged Out'),('nully',0,0,6,'Registered'),('dallas',0,0,7,'ListedItem'),('dallas',0,0,8,'ListedItem'),('dallas',0,0,9,'ListedItem'),('mark',0,0,10,'ListedItem'),('sayuj',0,0,11,'ListedItem'),('trey',0,0,12,'ListedItem'),('trey',0,0,13,'ListedItem'),('mark',0,0,14,'BidOnItem'),('sayuj',0,0,15,'BidOnItem'),('mark',0,0,16,'BidOnItem'),('mark',0,0,17,'BidOnItem'),('jim',0,0,18,'BidOnItem'),('dallas',0,0,19,'BidOnItem'),('jim_admin',2,0,9,'Current Time'),('trey',2,0,10,'BidOnItem'),('toby',2,0,11,'Registered'),('toby',2,0,12,'ListedItem'),('toby',2,0,13,'ListedItem'),('toby',2,0,14,'ListedItem'),('toby',2,0,15,'ListedItem'),('toby',2,0,16,'ListedItem'),('jim',2,0,17,'ListedItem'),('jim',2,0,18,'ListedItem'),('jim',2,0,19,'ListedItem'),('jim',2,0,20,'ListedItem'),('jim',2,0,21,'ListedItem'),('jim',2,0,22,'ListedItem'),('jim',2,0,23,'ListedItem'),('jim',2,0,24,'ListedItem'),('jim',2,0,25,'ListedItem');
/*!40000 ALTER TABLE `user_activity` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2009-04-14 18:24:16
