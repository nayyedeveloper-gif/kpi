
--
-- GTID state at the beginning of the backup 
--


--
-- Table structure for table `cache`
--

DROP TABLE IF EXISTS `cache`;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `cache` (
  `key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `value` mediumtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` int NOT NULL,
  PRIMARY KEY (`key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `cache`
--

LOCK TABLES `cache` WRITE;
/*!40000 ALTER TABLE `cache` DISABLE KEYS */;
/*!40000 ALTER TABLE `cache` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `cache_locks`
--

DROP TABLE IF EXISTS `cache_locks`;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `cache_locks` (
  `key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `owner` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` int NOT NULL,
  PRIMARY KEY (`key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `cache_locks`
--

LOCK TABLES `cache_locks` WRITE;
/*!40000 ALTER TABLE `cache_locks` DISABLE KEYS */;
/*!40000 ALTER TABLE `cache_locks` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `card_transactions`
--

DROP TABLE IF EXISTS `card_transactions`;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `card_transactions` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `card_id` bigint unsigned NOT NULL,
  `transaction_type` enum('load','payment','refund','adjustment') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'payment',
  `amount` decimal(10,2) NOT NULL,
  `balance_before` decimal(10,2) NOT NULL,
  `balance_after` decimal(10,2) NOT NULL,
  `order_id` bigint unsigned DEFAULT NULL,
  `payment_method` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `bonus_amount` decimal(10,2) NOT NULL DEFAULT '0.00',
  `description` text COLLATE utf8mb4_unicode_ci,
  `created_by` bigint unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `card_transactions_created_by_foreign` (`created_by`),
  KEY `card_transactions_card_id_index` (`card_id`),
  KEY `card_transactions_transaction_type_index` (`transaction_type`),
  KEY `card_transactions_order_id_index` (`order_id`),
  KEY `card_transactions_created_at_index` (`created_at`),
  CONSTRAINT `card_transactions_card_id_foreign` FOREIGN KEY (`card_id`) REFERENCES `cards` (`id`) ON DELETE CASCADE,
  CONSTRAINT `card_transactions_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  CONSTRAINT `card_transactions_order_id_foreign` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `card_transactions`
--

LOCK TABLES `card_transactions` WRITE;
/*!40000 ALTER TABLE `card_transactions` DISABLE KEYS */;
INSERT INTO `card_transactions` VALUES (1,1,'load',500000.00,0.00,500000.00,NULL,'initial',0.00,'Loaded 500000 Ks',1,'2025-11-03 19:26:59','2025-11-03 19:26:59');
/*!40000 ALTER TABLE `card_transactions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `cards`
--

DROP TABLE IF EXISTS `cards`;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `cards` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `card_number` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `customer_id` bigint unsigned DEFAULT NULL,
  `balance` decimal(10,2) NOT NULL DEFAULT '0.00',
  `status` enum('active','inactive','blocked') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'active',
  `card_type` enum('virtual','physical') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'virtual',
  `issued_date` date NOT NULL,
  `expiry_date` date DEFAULT NULL,
  `notes` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `cards_card_number_unique` (`card_number`),
  KEY `cards_card_number_index` (`card_number`),
  KEY `cards_customer_id_index` (`customer_id`),
  KEY `cards_status_index` (`status`),
  CONSTRAINT `cards_customer_id_foreign` FOREIGN KEY (`customer_id`) REFERENCES `customers` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `cards`
--

LOCK TABLES `cards` WRITE;
/*!40000 ALTER TABLE `cards` DISABLE KEYS */;
INSERT INTO `cards` VALUES (1,'TC79792369',1,500000.00,'active','virtual','2025-11-04','2026-11-04','','2025-11-03 19:26:59','2025-11-03 19:26:59',NULL);
/*!40000 ALTER TABLE `cards` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `categories`
--

DROP TABLE IF EXISTS `categories`;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `categories` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name_mm` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `printer_type` enum('kitchen','bar','none') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'none',
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `sort_order` int NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=19 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `categories`
--

LOCK TABLES `categories` WRITE;
/*!40000 ALTER TABLE `categories` DISABLE KEYS */;
INSERT INTO `categories` VALUES (1,'Hot Coffee','ကော်ဖီပူ',NULL,'bar',1,1,'2025-11-02 21:06:15','2025-11-10 06:55:14','2025-11-10 06:55:14'),(2,'Iced Coffee','ကော်ဖီအေး',NULL,'bar',1,2,'2025-11-02 21:06:15','2025-11-10 06:54:11','2025-11-10 06:54:11'),(3,'Tea','လက်ဖက်ရည်',NULL,'bar',1,3,'2025-11-02 21:06:15','2025-11-10 09:02:50',NULL),(4,'Milk Tea','နို့လက်ဖက်',NULL,'bar',1,4,'2025-11-02 21:06:15','2025-11-10 06:53:32','2025-11-10 06:53:32'),(5,'Juice','ဖျော်ရည်',NULL,'bar',1,5,'2025-11-02 21:06:15','2025-11-10 06:54:04','2025-11-10 06:54:04'),(6,'Smoothies','ချောမွေ့သောအချိုရည်',NULL,'bar',1,6,'2025-11-02 21:06:15','2025-11-10 06:53:45','2025-11-10 06:53:45'),(7,'Snacks','အစားအစာ',NULL,'bar',1,7,'2025-11-02 21:06:15','2025-11-10 09:04:53',NULL),(8,'Breakfast','မနက်စာ',NULL,'kitchen',1,8,'2025-11-02 21:06:15','2025-11-02 21:06:15',NULL),(9,' Athoke Sone','အသုပ်စုံ',NULL,'kitchen',1,9,'2025-11-02 21:06:15','2025-11-10 13:30:56',NULL),(10,'Rice','ထမင်း',NULL,'kitchen',1,10,'2025-11-02 21:06:15','2025-11-10 08:59:33','2025-11-10 08:59:33'),(11,'Desserts','အချိုပွဲ',NULL,'kitchen',1,11,'2025-11-02 21:06:15','2025-11-10 06:53:54','2025-11-10 06:53:54'),(12,'Food','အစားအစာ','Food items','kitchen',1,1,'2025-11-03 17:28:46','2025-11-03 17:28:46',NULL),(13,'Rice Dishes','ထမင်းဟင်းလျာ','Rice and curry dishes','kitchen',1,2,'2025-11-03 17:28:46','2025-11-03 17:28:46',NULL),(14,'Salads','သုပ်','Myanmar salads','kitchen',1,4,'2025-11-03 17:28:46','2025-11-10 08:57:02','2025-11-10 08:57:02'),(15,'Beverages','သောက်စရာများ','Drinks and beverages','bar',1,6,'2025-11-03 17:28:46','2025-11-10 13:30:33',NULL),(16,'Coffee','ကော်ဖီ','Coffee varieties','bar',1,8,'2025-11-03 17:28:46','2025-11-10 08:58:27',NULL),(17,'Juice & Drinks','ဖျော်ရည်','Fresh juices and drinks','bar',1,9,'2025-11-03 17:28:46','2025-11-10 08:58:10',NULL),(18,'Cigarettes','စီးကရက်','Cigarette brands','none',1,10,'2025-11-03 17:28:46','2025-11-03 17:28:46',NULL);
/*!40000 ALTER TABLE `categories` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `customer_loyalty_transactions`
--

DROP TABLE IF EXISTS `customer_loyalty_transactions`;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `customer_loyalty_transactions` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `customer_id` bigint unsigned NOT NULL,
  `order_id` bigint unsigned DEFAULT NULL,
  `type` enum('earn','redeem','expire','adjust') COLLATE utf8mb4_unicode_ci NOT NULL,
  `points` int NOT NULL,
  `balance_before` int NOT NULL,
  `balance_after` int NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `customer_loyalty_transactions_customer_id_foreign` (`customer_id`),
  KEY `customer_loyalty_transactions_order_id_foreign` (`order_id`),
  CONSTRAINT `customer_loyalty_transactions_customer_id_foreign` FOREIGN KEY (`customer_id`) REFERENCES `customers` (`id`) ON DELETE CASCADE,
  CONSTRAINT `customer_loyalty_transactions_order_id_foreign` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `customer_loyalty_transactions`
--

LOCK TABLES `customer_loyalty_transactions` WRITE;
/*!40000 ALTER TABLE `customer_loyalty_transactions` DISABLE KEYS */;
INSERT INTO `customer_loyalty_transactions` VALUES (1,1,33,'earn',8,0,8,'Earned from Order #202511030026','2025-11-03 16:13:55','2025-11-03 16:13:55'),(2,1,73,'earn',3,8,11,'Earned from Order #202511100021','2025-11-10 11:26:43','2025-11-10 11:26:43');
/*!40000 ALTER TABLE `customer_loyalty_transactions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `customers`
--

DROP TABLE IF EXISTS `customers`;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `customers` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `customer_code` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name_mm` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `phone` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `date_of_birth` date DEFAULT NULL,
  `gender` enum('male','female','other') COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `address` text COLLATE utf8mb4_unicode_ci,
  `loyalty_points` int NOT NULL DEFAULT '0',
  `total_spent` decimal(10,2) NOT NULL DEFAULT '0.00',
  `visit_count` int NOT NULL DEFAULT '0',
  `last_visit_at` timestamp NULL DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `notes` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `customers_customer_code_unique` (`customer_code`),
  UNIQUE KEY `customers_phone_unique` (`phone`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `customers`
--

LOCK TABLES `customers` WRITE;
/*!40000 ALTER TABLE `customers` DISABLE KEYS */;
INSERT INTO `customers` VALUES (1,'CUST00001','Nayye','နေရဲ','09777909062','nayye.share@gmail.com','1993-02-18','male','Ahlone',11,12200.00,2,'2025-11-10 11:26:43',1,'','2025-11-03 16:03:03','2025-11-10 11:26:43');
/*!40000 ALTER TABLE `customers` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `daily_sales_summaries`
--

DROP TABLE IF EXISTS `daily_sales_summaries`;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `daily_sales_summaries` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `date` date NOT NULL,
  `total_orders` int NOT NULL DEFAULT '0',
  `gross_sales` decimal(10,2) NOT NULL DEFAULT '0.00',
  `discounts` decimal(10,2) NOT NULL DEFAULT '0.00',
  `taxes` decimal(10,2) NOT NULL DEFAULT '0.00',
  `service_charges` decimal(10,2) NOT NULL DEFAULT '0.00',
  `net_sales` decimal(10,2) NOT NULL DEFAULT '0.00',
  `dine_in_orders` int NOT NULL DEFAULT '0',
  `takeaway_orders` int NOT NULL DEFAULT '0',
  `cash_payments` decimal(10,2) NOT NULL DEFAULT '0.00',
  `card_payments` decimal(10,2) NOT NULL DEFAULT '0.00',
  `mobile_payments` decimal(10,2) NOT NULL DEFAULT '0.00',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `daily_sales_summaries_date_unique` (`date`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `daily_sales_summaries`
--

LOCK TABLES `daily_sales_summaries` WRITE;
/*!40000 ALTER TABLE `daily_sales_summaries` DISABLE KEYS */;
/*!40000 ALTER TABLE `daily_sales_summaries` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `expenses`
--

DROP TABLE IF EXISTS `expenses`;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `expenses` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint unsigned NOT NULL,
  `category` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `amount` decimal(10,2) NOT NULL,
  `expense_date` date NOT NULL,
  `payment_method` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `receipt_number` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `notes` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `expenses_user_id_foreign` (`user_id`),
  KEY `expenses_expense_date_category_index` (`expense_date`,`category`),
  CONSTRAINT `expenses_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `expenses`
--

LOCK TABLES `expenses` WRITE;
/*!40000 ALTER TABLE `expenses` DISABLE KEYS */;
INSERT INTO `expenses` VALUES (1,1,'food_ingredients','ငါးဖယ်ဝယ်',7000.00,'2025-11-03','cash','','','2025-11-03 04:03:07','2025-11-03 04:03:07'),(2,1,'beverages','နို့ဆီ ၅ဖာ',1560000.00,'2025-11-10','cash','#123','ဆိုင်အတွက်ဝယ်','2025-11-10 07:16:24','2025-11-10 07:16:24'),(3,1,'other','မီးသွေး ဂဲကြီး',23500.00,'2025-11-10','cash','မရှိ','ကိုသာချိုဝယ်သည်','2025-11-10 11:06:14','2025-11-10 11:06:14');
/*!40000 ALTER TABLE `expenses` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `failed_jobs`
--

DROP TABLE IF EXISTS `failed_jobs`;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `failed_jobs` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `uuid` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `connection` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `queue` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `exception` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `failed_jobs`
--

LOCK TABLES `failed_jobs` WRITE;
/*!40000 ALTER TABLE `failed_jobs` DISABLE KEYS */;
/*!40000 ALTER TABLE `failed_jobs` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `item_stock_usage`
--

DROP TABLE IF EXISTS `item_stock_usage`;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `item_stock_usage` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `item_id` bigint unsigned NOT NULL,
  `stock_item_id` bigint unsigned NOT NULL,
  `quantity_used` decimal(10,2) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `item_stock_usage_item_id_foreign` (`item_id`),
  KEY `item_stock_usage_stock_item_id_foreign` (`stock_item_id`),
  CONSTRAINT `item_stock_usage_item_id_foreign` FOREIGN KEY (`item_id`) REFERENCES `items` (`id`) ON DELETE CASCADE,
  CONSTRAINT `item_stock_usage_stock_item_id_foreign` FOREIGN KEY (`stock_item_id`) REFERENCES `stock_items` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `item_stock_usage`
--

LOCK TABLES `item_stock_usage` WRITE;
/*!40000 ALTER TABLE `item_stock_usage` DISABLE KEYS */;
/*!40000 ALTER TABLE `item_stock_usage` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `items`
--

DROP TABLE IF EXISTS `items`;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `items` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `category_id` bigint unsigned NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name_mm` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `name_mm_zawgyi` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `price` decimal(10,2) NOT NULL,
  `image` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `is_available` tinyint(1) NOT NULL DEFAULT '1',
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `sort_order` int NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `items_category_id_foreign` (`category_id`),
  CONSTRAINT `items_category_id_foreign` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=125 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `items`
--

LOCK TABLES `items` WRITE;
/*!40000 ALTER TABLE `items` DISABLE KEYS */;
INSERT INTO `items` VALUES (1,13,'Fried Rice (Chicken)','ထမင်းကြော် (ကြက်)','ထမင်းကြော် (ကြက်)','',4500.00,NULL,1,1,0,'2025-11-03 17:29:31','2025-11-05 09:04:00',NULL),(2,13,'Fried Rice (Pork)','ထမင်းကြော် (ဝက်)','ထမင်းကြော် (ဝက်)','',5000.00,NULL,1,1,0,'2025-11-03 17:29:31','2025-11-10 06:27:03',NULL),(3,13,'Rice + Chicken','ထမင်း + ကြက်','ထမင်း + ကြက်','',4500.00,NULL,1,1,0,'2025-11-03 17:29:31','2025-11-05 09:04:00',NULL),(4,13,'Rice + Pork','ထမင်း+ဝက်သား','ထမင်း+ဝက်သား','',5000.00,NULL,1,1,0,'2025-11-03 17:29:31','2025-11-10 06:27:48',NULL),(5,13,'Oil Rice','ဆီချက်','ဆီချက်','',3000.00,NULL,1,1,0,'2025-11-03 17:29:31','2025-11-05 09:04:00',NULL),(6,13,'Tea Leaf Rice','လဖက်ထမင်း','လဖက်ထမင်း','',3500.00,NULL,1,1,0,'2025-11-03 17:29:31','2025-11-05 09:04:00',NULL),(7,13,'Rice Salad','ထမင်းသုပ်','ထမင်းသုပ်','',3500.00,NULL,1,1,0,'2025-11-03 17:29:31','2025-11-05 09:04:00',NULL),(8,13,'Plain Rice (Pack)','ထမင်းဖြူ (တစ်ထုပ်)','ထမင်းဖြူ (တစ်ထုပ်)','',1500.00,NULL,1,1,0,'2025-11-03 17:29:31','2025-11-10 06:27:15',NULL),(9,13,'Plain Rice (Table)','ထမင်းဖြူ (စားပွဲ)','ထမင်းဖြူ (စားပြဲ)','',1500.00,NULL,1,1,0,'2025-11-03 17:29:31','2025-11-05 09:04:00',NULL),(10,13,'Rice Side','ထမင်း လိုက်ပွဲ','ထမင်း လိုက်ပွဲ','',700.00,NULL,1,1,0,'2025-11-03 17:29:31','2025-11-05 09:04:00',NULL),(11,13,'Butter Rice','ထမင်း ဆီဆမ်း','ထမင်း ဆီဆမ်း','',3500.00,NULL,1,1,0,'2025-11-03 17:29:31','2025-11-05 09:04:00',NULL),(12,9,'Shan Noodles','ရှမ်းခေါက်ဆွဲ','ရှမ်းခေါက်ဆွဲ','',3500.00,NULL,1,1,0,'2025-11-03 17:29:31','2025-11-05 09:04:00',NULL),(13,9,'Noodle Salad','ခေါ်က်ဆွဲသုပ်','ခေါ်က်ဆွဲသုပ်','',1500.00,NULL,1,1,0,'2025-11-03 17:29:31','2025-11-05 09:04:00',NULL),(14,9,'Wheat Noodle Salad','ဂျုံ ခေါက်ဆွဲသုပ်','ဂျုံ ခေါက်ဆွဲသုပ်','',1500.00,NULL,1,1,0,'2025-11-03 17:29:31','2025-11-05 09:04:00',NULL),(15,9,'Mohinga (Plain)','မုန့်ဟင်းခါး အလွတ်','မုနံ့်ဟင်းခါး အလွတ်','',2000.00,NULL,1,1,0,'2025-11-03 17:29:31','2025-11-05 09:04:00',NULL),(16,9,'Mohinga','မုန့်ဟင်းခါး','မုနံ့်ဟင်းခါး','',2000.00,NULL,1,1,0,'2025-11-03 17:29:31','2025-11-05 09:04:00',NULL),(17,9,'Mohinga with Fried Bean','မုန့်ဟင်းခါး ပဲကြော်','မုနံ့်ဟင်းခါး ပဲကြော်','',2500.00,NULL,1,1,0,'2025-11-03 17:29:31','2025-11-05 09:04:00',NULL),(18,12,'Tea Leaf Salad','လက်ဖက်သုပ်','လက်ဖက်သုပ်','',3000.00,'items/c89VIvIUzLVMlQ5hWoQG6yCTqo5kYpQ6B5tf1xuz.jpg',1,1,0,'2025-11-03 17:29:31','2025-11-10 07:07:14',NULL),(19,12,'Tomato Salad','ခရမ်းချဉ်သီးသုပ်','ခရမ်းချဉ်သီးသုပ်','',2000.00,NULL,1,1,0,'2025-11-03 17:29:31','2025-11-10 07:02:42',NULL),(20,12,'Ginger Salad','ကြာဇံသုပ်','ကြာဇံသုပ်','',2000.00,NULL,1,1,0,'2025-11-03 17:29:31','2025-11-10 07:01:19',NULL),(21,12,'Pennywort Salad','ညှပ်ဖက်သုပ်','ညှပ်ဖက်သုပ်','',1500.00,NULL,1,1,0,'2025-11-03 17:29:31','2025-11-10 07:02:20',NULL),(22,12,'Nan Gyi Salad','နန်းကြီးသုပ်','နန်းကြီးသုပ်','',4000.00,NULL,1,1,0,'2025-11-03 17:29:31','2025-11-10 07:01:26',NULL),(23,12,'Nan Pyar Salad (Chicken)','နန်းပြားသုပ် (ကြက်)','နန်းပြားသုပ် (ကြက်)','',4000.00,NULL,1,1,0,'2025-11-03 17:29:31','2025-11-10 07:01:57',NULL),(24,12,'Nan Pyar (Plain)','နန်းပြား အလွတ်','နန်းပြား အလွတ်','',3500.00,NULL,1,1,0,'2025-11-03 17:29:31','2025-11-10 07:01:44',NULL),(25,12,'Nan Pyar Rolled','နန်းပြား လိပ်ခုတ်','နန်းပြား လိပ်ခုတ်','',2000.00,NULL,1,1,0,'2025-11-03 17:29:31','2025-11-10 07:01:51',NULL),(26,12,'Nan Pyar Wrapped Salad','နန်းပြား အုပ်သုပ်','နန်းပြား အုပ်သုပ်','',2000.00,NULL,1,1,0,'2025-11-03 17:29:31','2025-11-10 07:02:12',NULL),(27,12,'Nan Pyar Tomato Salad','နံပြားထောပက်သုပ်','နံပြားထောပက်သုပ်','',1500.00,NULL,1,1,0,'2025-11-03 17:29:31','2025-11-10 07:02:05',NULL),(28,12,'Tomato Salad','နံပြား ထောပက်သုပ်','ထောပက်သုပ်','',1800.00,NULL,1,1,0,'2025-11-03 17:29:31','2025-11-10 07:03:15',NULL),(29,12,'Bread Tomato Salad','ပေါင်မုန့် ထောပက် သုပ်','ပေါင်မုနံ့် ထောပက် သုပ်','',2500.00,NULL,1,1,0,'2025-11-03 17:29:31','2025-11-10 07:01:13',NULL),(30,12,'Grilled Bread Tomato Salad','ပေါင်မုန့် မီးကင် ထောက်ပက်သုပ်','ပေါင်မုနံ့် မီးကင် ထောက်ပက်သုပ်','',2500.00,NULL,1,1,0,'2025-11-03 17:29:31','2025-11-10 07:01:34',NULL),(31,7,'Samosa','စမူဆာ','စမူဆာ','',1000.00,NULL,1,1,0,'2025-11-03 17:29:31','2025-11-05 09:04:00',NULL),(32,7,'Nan Pyar','နံပြား','နံပြား','',1000.00,NULL,1,1,0,'2025-11-03 17:29:31','2025-11-05 09:04:00',NULL),(33,7,'Bean Bread','ပဲပေါင်မုန့်','ပဲပေါင်မုနံ့်','',1300.00,NULL,1,1,0,'2025-11-03 17:29:31','2025-11-05 09:04:00',NULL),(34,7,'Bean Rolled','ပဲလိပ်ခုတ်','ပဲလိပ်ခုတ်','',2000.00,NULL,1,1,0,'2025-11-03 17:29:31','2025-11-05 09:04:00',NULL),(35,7,'Bean Rolled','ပဲ လိပ်ခုတ်','ပဲ လိပ်ခုတ်','',2500.00,NULL,1,1,0,'2025-11-03 17:29:31','2025-11-05 09:04:00',NULL),(36,7,'Fried Bean','ပဲကြော်','ပဲကြော်','',500.00,NULL,1,1,0,'2025-11-03 17:29:31','2025-11-05 09:04:00',NULL),(37,7,'Bean Nan Pyar','ပဲနံပြား','ပဲနံပြား','',1800.00,'items/4vTOmOo1mQJGZgjPgIIzmNcVoepkdshaPYoIwpwE.png',1,1,0,'2025-11-03 17:29:31','2025-11-10 06:24:47',NULL),(38,7,'Fried Bean Leaves','ပဲရွက်ကြော်','ပဲရွက်ကြော်','',2500.00,NULL,1,1,0,'2025-11-03 17:29:31','2025-11-10 07:22:38',NULL),(39,7,'Bean Ikra','ပဲအီကြာ','ပဲအီကြာ','',1800.00,NULL,1,1,0,'2025-11-03 17:29:31','2025-11-10 06:25:46',NULL),(40,7,'Egg Bread','ကြက်ဉပေါင်မုန့်','ကြက်ဉပေါင်မုနံ့်','',1500.00,NULL,1,1,0,'2025-11-03 17:29:31','2025-11-10 06:33:10',NULL),(41,7,'Fried Egg Bread','ပေါင်မုန့်ကြက်ဉကြော်','ပေါင်မုနံ့်ကြက်ဉကြော်','',2500.00,NULL,1,1,0,'2025-11-03 17:29:31','2025-11-05 09:04:00',NULL),(42,7,'Fried Egg','ကြက်ဉ ကြော်','ကြက်ဉ ကြော်','',700.00,NULL,1,1,0,'2025-11-03 17:29:31','2025-11-05 09:04:00',NULL),(43,7,'Boiled Egg','ဆေးဘဲဉ','ဆေးဘဲဉ','',2500.00,NULL,1,1,0,'2025-11-03 17:29:31','2025-11-05 09:04:00',NULL),(44,7,'Bread (Plain)','ပေါင်မုန့် အလွတ်','ပေါင်မုနံ့် အလွတ်','',800.00,NULL,1,1,0,'2025-11-03 17:29:31','2025-11-05 09:04:00',NULL),(45,7,'Pork Bread','ဝက်သား ပေါင်မုန့်','ဝက်သား ပေါင်မုနံ့်','',1300.00,NULL,1,1,0,'2025-11-03 17:29:31','2025-11-05 09:04:00',NULL),(46,7,'Milk Bread','ပေါင်မုန့် နို့ဆမ်း','ပေါင်မုနံ့် နိုံ့ဆမ်း','',2500.00,NULL,1,1,0,'2025-11-03 17:29:31','2025-11-05 09:04:00',NULL),(47,7,'Fried Snack','အကြော်','အကြော်','',500.00,NULL,1,1,0,'2025-11-03 17:29:31','2025-11-05 09:04:00',NULL),(48,7,'Ikra Kwe','အီကြာကွေး','အီကြာကွေး','',1000.00,NULL,1,1,0,'2025-11-03 17:29:31','2025-11-05 09:04:00',NULL),(49,7,'Ikra Kwe','အီကြာကွေး','အီကြာကွေး','',1000.00,NULL,1,1,0,'2025-11-03 17:29:31','2025-11-05 09:04:00',NULL),(50,7,'Pot Bean Egg','အိုးပဲ ဉ','အိုးပဲ ဉ','',1000.00,NULL,1,1,0,'2025-11-03 17:29:31','2025-11-05 09:04:00',NULL),(51,7,'Meat Mix','အသားပေါင်း','အသားပေါင်း','',1800.00,NULL,1,1,0,'2025-11-03 17:29:31','2025-11-10 06:34:12',NULL),(52,7,'Meat Mix','အသားပေါင်း','အသားပေါင်း','',1500.00,NULL,1,1,0,'2025-11-03 17:29:31','2025-11-10 06:34:17','2025-11-10 06:34:17'),(53,7,'Ahara','အဟာရ','အဟာရ','',2500.00,NULL,1,1,0,'2025-11-03 17:29:31','2025-11-05 09:04:00',NULL),(54,7,'Fork Bottle','ဖော့ဗူး','ဖောံ့ဗူး','',200.00,NULL,1,1,0,'2025-11-03 17:29:31','2025-11-05 09:04:00',NULL),(55,12,'Kap Gyi Kaik (Plain)','ကပ်ကြီးကိုက် အလွတ်','ကပ်ကြီးကိုက် အလွတ်','',3000.00,NULL,0,0,0,'2025-11-03 17:29:31','2025-11-10 06:51:46',NULL),(56,12,'Kap Gyi Kaik Seafood (Small)','ကပ်ကြီးကိုက် ပင်လယ်စာ (သေး)','ကပ်ကြီးကိုက် ပင်လယ်စာ (သေး)','',7000.00,NULL,1,1,0,'2025-11-03 17:29:31','2025-11-05 09:04:00',NULL),(57,12,'Kap Gyi Kaik Chicken (Small)','ကပ်ကြီးကိုက် ကြက်သား (သေး)','ကပ်ကြီးကိုက် ကြက်သား (သေး)','',5000.00,NULL,1,1,0,'2025-11-03 17:29:31','2025-11-05 09:04:00',NULL),(58,12,'Kap Gyi Kaik Pork (Small)','ကပ်ကြီးကိုက် ဝက်သား ( သေး)','ကပ်ကြီးကိုက် ဝက်သား ( သေး)','',5000.00,NULL,1,1,0,'2025-11-03 17:29:31','2025-11-05 09:04:00',NULL),(59,12,'Kap Gyi Kaik Set (Large)','ကပ်ကြီးကိုက် အစုံ (ပွဲကြီး)','ကပ်ကြီးကိုက် အစုံ (ပွဲကြီး)','',10000.00,NULL,1,1,0,'2025-11-03 17:29:31','2025-11-05 09:04:00',NULL),(60,12,'Pork Porksee','ဝက်ပေါက်စီ','ဝက်ပေါက်စီ','',2000.00,NULL,1,1,0,'2025-11-03 17:29:31','2025-11-05 09:04:00',NULL),(61,12,'Chicken Porksee','ကြက်ပေါက်စီ','ကြက်ပေါက်စီ','',2000.00,NULL,1,1,0,'2025-11-03 17:29:31','2025-11-05 09:04:00',NULL),(62,12,'Bean Porksee','ပဲပေါက်စီ','ပဲပေါက်စီ','',1500.00,NULL,1,1,0,'2025-11-03 17:29:31','2025-11-10 06:33:53',NULL),(63,12,'Bean Palata','ပဲပလာတာ','ပဲပလာတာ','',2000.00,NULL,1,1,0,'2025-11-03 17:29:31','2025-11-05 09:04:00',NULL),(64,17,'Chicken Lime','ကြက် သံပုရာ','ကြက် သံပုရာ','',2000.00,NULL,1,1,0,'2025-11-03 17:29:31','2025-11-10 06:50:47',NULL),(65,15,'Chicken Lime (Hot)','ကြက် သံပုရာ အပူ','ကြက် သံပုရာ အပူ','',2000.00,NULL,1,1,0,'2025-11-03 17:29:31','2025-11-06 18:48:30',NULL),(66,15,'Chicken Lime (Cold)','ကြက် သံပုရာ အအေး','ကြက် သံပုရာ အအေး','',2500.00,NULL,1,1,0,'2025-11-03 17:29:31','2025-11-06 18:48:30',NULL),(67,12,'Chicken Ka','ကာ ကြက်','ကာ ကြက်','',700.00,NULL,1,1,0,'2025-11-03 17:29:31','2025-11-05 09:04:00',NULL),(68,15,'Kyay Sein','ကျွဲစိမ်း','ကျွဲစိမ်း','',3000.00,NULL,1,1,0,'2025-11-03 17:29:31','2025-11-06 18:48:30',NULL),(69,15,'Kyay Sein','ကျွဲစိမ်း','ကျွဲစိမ်း','',2800.00,NULL,1,1,0,'2025-11-03 17:29:31','2025-11-06 18:48:30',NULL),(70,16,'Aung San','အော်စွန်း','အော်စွန်း','',3500.00,NULL,1,1,0,'2025-11-03 17:29:31','2025-11-10 06:55:05',NULL),(71,3,'Iced Tea','လက်ဖက်ရည်အး','လက္ဖက္ရည္အး','',4000.00,NULL,1,1,0,'2025-11-03 17:29:31','2025-11-05 09:04:00',NULL),(72,3,'Lemon Tea (Cold)','လီမွန်တီး အအေး','လီမွန်တီး အအေး','',2500.00,NULL,1,1,0,'2025-11-03 17:29:31','2025-11-05 09:04:00',NULL),(73,3,'Lemon Tea','လီမွန်တီး','လီမွန်တီး','',1000.00,NULL,1,1,0,'2025-11-03 17:29:31','2025-11-05 09:04:00',NULL),(74,3,'Milk Tea (Cold)','နို့စိမ်းတီး','နိုံ့စိမ်းတီး','',2000.00,NULL,1,1,0,'2025-11-03 17:29:31','2025-11-05 09:04:00',NULL),(75,3,'Ceylon Tea','စီလုံတီး','စီလုံတီး','',3500.00,'items/ohI3NWdje7YBR8vr6zW9DDBXC9tccYcKax3TS1jS.jpg',1,1,0,'2025-11-03 17:29:31','2025-11-10 07:12:10',NULL),(76,16,'Black Coffee','ဘလက်အော','ဘလက်အော','',1800.00,NULL,1,1,0,'2025-11-03 17:29:31','2025-11-05 09:04:00',NULL),(77,16,'Black Coffee','Black Coffee',NULL,'',1800.00,NULL,1,1,0,'2025-11-03 17:29:31','2025-11-03 17:29:31',NULL),(78,16,'Iced Coffee','ကော်ဖီအေး','ေကာ္ဖီအေး','',4000.00,NULL,1,1,0,'2025-11-03 17:29:31','2025-11-05 09:04:00',NULL),(79,16,'Ovaltine','အိုဗာတင်း','အိုဗာတင်း','',1800.00,NULL,1,1,0,'2025-11-03 17:29:31','2025-11-05 09:04:00',NULL),(80,16,'Iced Ovaltine','အိုဗာတင်း အအေး','အိုဗာတင်း အအေး','',4000.00,NULL,1,1,0,'2025-11-03 17:29:31','2025-11-05 09:04:00',NULL),(81,16,'Singapore','စင်္ကာပူ','စင်္ကာပူ','',1800.00,NULL,1,1,0,'2025-11-03 17:29:31','2025-11-05 09:04:00',NULL),(82,16,'Regular Coffee','ပုံမှန် ကျရည်ကဲ','ပုံမှန် ကျရည်ကဲ','',1800.00,NULL,1,1,0,'2025-11-03 17:29:31','2025-11-05 09:04:00',NULL),(83,16,'Regular','ပုံမှန်','ပုံမှန်','',1800.00,NULL,1,1,0,'2025-11-03 17:29:31','2025-11-05 09:04:00',NULL),(84,16,'Sweet Coffee','ချိုကျ','ချိုကျ','',1800.00,NULL,1,1,0,'2025-11-03 17:29:31','2025-11-05 09:04:00',NULL),(85,16,'Light Coffee','ကျရည်ပေါ့','ကျရည်ပေါံ့','',1800.00,NULL,1,1,0,'2025-11-03 17:29:31','2025-11-05 09:04:00',NULL),(86,16,'Sweet Lite','ချိုစိမ့်','ချိုစိမံ့်','',1800.00,NULL,1,1,0,'2025-11-03 17:29:31','2025-11-05 09:04:00',NULL),(87,16,'Lite Coffee','ပေါ့စိမ့်','ပေါံ့စိမံ့်','',1800.00,NULL,1,1,0,'2025-11-03 17:29:31','2025-11-05 09:04:00',NULL),(88,16,'Lite Coffee Pack (Small)','ပေါ့စိမ့် ပါဆယ် (သေး)','ပေါံ့စိမံ့် ပါဆယ် (သေး)','',2000.00,NULL,1,1,0,'2025-11-03 17:29:31','2025-11-05 09:04:00',NULL),(89,16,'Lite Coffee Pack (Large)','ပေါ့စိမ့် ပါဆယ် (ကြီး)','ပေါံ့စိမံ့် ပါဆယ် (ကြီး)','',3300.00,NULL,1,1,0,'2025-11-03 17:29:31','2025-11-05 09:04:00',NULL),(90,16,'Kyay Sein','ကျစိမ့်','ကျစိမံ့်','',1800.00,NULL,1,1,0,'2025-11-03 17:29:31','2025-11-05 09:04:00',NULL),(91,12,'Kaw Pyan Sein','ကော်ပြန့်စိမ်း','ကော်ပြနံ့်စိမ်း','',1200.00,NULL,1,1,0,'2025-11-03 17:29:31','2025-11-10 06:35:08',NULL),(92,16,'Makhoe','မခို့','မခွပ်','မခို့',1800.00,NULL,1,1,0,'2025-11-03 17:29:31','2025-11-10 06:57:25',NULL),(93,16,'Fan Cho','ဖန်ချို','ဖန်ချို','',1800.00,NULL,1,1,0,'2025-11-03 17:29:31','2025-11-05 09:04:00',NULL),(94,17,'Fresh Milk','နွားနို့','နွားနိုံ့','',2000.00,NULL,1,1,0,'2025-11-03 17:29:31','2025-11-05 09:04:00',NULL),(95,17,'Iced Milk','နွားနို့ အေး','နွားနိုံ့ အေး','',2700.00,NULL,1,1,0,'2025-11-03 17:29:31','2025-11-05 09:04:00',NULL),(96,17,'Milk + Egg','နို့ကြက်ဥ','နိုံ့ကြက်ဥ','',3000.00,NULL,1,1,0,'2025-11-03 17:29:31','2025-11-05 09:04:00',NULL),(97,17,'Milk + Egg','နွားနို့ + ကြက်ဉ','နွားနိုံ့ + ကြက်ဉ','',2500.00,NULL,1,1,0,'2025-11-03 17:29:31','2025-11-05 09:04:00',NULL),(98,17,'Sundae','ဆန်းဒေး','ဆန်းဒေး','',1000.00,NULL,1,1,0,'2025-11-03 17:29:31','2025-11-05 09:04:00',NULL),(99,17,'Vitamin Drink','Vitamin drink',NULL,'',1500.00,NULL,1,1,0,'2025-11-03 17:29:31','2025-11-10 06:45:11','2025-11-10 06:45:11'),(100,17,'Vitamin C','ဘီတာမင် (စီ)',NULL,'',1600.00,NULL,1,1,0,'2025-11-03 17:29:31','2025-11-10 06:47:17',NULL),(101,15,'Drinking Water','ရေသန့်','ရေသနံ့်','',1000.00,NULL,1,1,0,'2025-11-03 17:29:31','2025-11-05 09:04:00',NULL),(102,15,'Super','စူပါ','စူပါ','',1000.00,NULL,1,1,0,'2025-11-03 17:29:31','2025-11-05 09:04:00',NULL),(103,15,'Next','နက်စ်','နက်စ်','',1000.00,NULL,1,1,0,'2025-11-03 17:29:31','2025-11-05 09:04:00',NULL),(104,17,'Shark','ရှပ်ခ်',NULL,'',2800.00,NULL,1,1,0,'2025-11-03 17:29:31','2025-11-10 06:39:17',NULL),(105,18,'Shark','Shark',NULL,'',2800.00,NULL,1,1,0,'2025-11-03 17:29:31','2025-11-10 06:38:31','2025-11-10 06:38:31'),(106,17,'Royal D','Royal D',NULL,'',1800.00,NULL,1,1,0,'2025-11-03 17:29:31','2025-11-10 06:48:58',NULL),(107,18,'Mevius','Mevius',NULL,'',700.00,NULL,1,1,0,'2025-11-03 17:29:31','2025-11-03 17:29:31',NULL),(108,18,'Winston','Winston',NULL,'',500.00,NULL,1,1,0,'2025-11-03 17:29:31','2025-11-03 17:29:31',NULL),(109,17,'Blue Mountain','ဘလူး မောင်တိန်',NULL,'',1600.00,NULL,1,1,0,'2025-11-03 17:29:31','2025-11-10 06:46:21',NULL),(110,16,'Premier','ပရီးမီးယား','ပရီးမီးယား','',1000.00,NULL,1,1,0,'2025-11-03 17:29:31','2025-11-10 06:48:49',NULL),(111,17,'String','String',NULL,'',2000.00,NULL,1,1,0,'2025-11-03 17:29:31','2025-11-10 06:46:42',NULL),(112,17,'Honey Gold','ရွေပျား',NULL,'',1800.00,NULL,1,1,0,'2025-11-03 17:29:31','2025-11-10 06:45:45',NULL),(113,17,'Speed','Speed',NULL,'',1800.00,NULL,1,1,0,'2025-11-03 17:29:31','2025-11-10 06:49:12',NULL),(114,12,'Mokefat thoke','မုန့်ဖက်သုပ်',NULL,'မုန့်ဖက်သုပ်',1500.00,NULL,1,1,0,'2025-11-10 06:30:00','2025-11-10 06:30:00',NULL),(115,12,'Hnakfat Tkoke','ညှပ်ဖက်သုပ်',NULL,'ညှပ်ဖက်သုပ်',1500.00,NULL,1,1,0,'2025-11-10 06:30:52','2025-11-10 06:30:52',NULL),(116,12,'Si Thamin','ဆီထမင်း',NULL,'ဆီထမင်း',2000.00,NULL,1,1,0,'2025-11-10 06:31:55','2025-11-10 06:52:19',NULL),(117,12,'Thar Kyay Yine','သာခွေယိုင်',NULL,'သာခွေယိုင်',2000.00,'items/jLaJJuhjS9uLqT8si23V3pBVJH0dqxd8chyR8rh6.jpg',1,1,0,'2025-11-10 06:32:31','2025-11-10 07:08:01',NULL),(118,12,'Bean Pyoke','ပဲပြုတ် အလွတ်',NULL,'ပဲပြုတ်',500.00,NULL,1,1,0,'2025-11-10 06:37:29','2025-11-10 06:37:29',NULL),(119,3,'Percel Gyi','ပါဆယ်ကြီး',NULL,'ပါဆယ်ကြီး',3500.00,NULL,1,1,0,'2025-11-10 06:56:23','2025-11-10 06:56:23',NULL),(120,3,'Chit Kaung','ချစ်ကောင်း',NULL,'ချစ်ကောင်း',1800.00,NULL,1,1,0,'2025-11-10 06:57:44','2025-11-10 06:57:44',NULL),(121,3,'Khauk Padaung','ကျောက်ပန်းတောင်း',NULL,'ကျောက်ပန်းတောင်း',1800.00,NULL,1,1,0,'2025-11-10 06:59:01','2025-11-10 06:59:01',NULL),(122,12,'Nan Pyar Plain','နံပြားအလွတ်',NULL,'နံပြားအလွတ်',1000.00,NULL,1,1,0,'2025-11-10 07:04:19','2025-11-10 07:04:19',NULL),(123,12,'Monkhninkhar Ayay','မုန့်ဟင်းခါး အရည်',NULL,'မုန့်ဟင်းခါး အရည်',500.00,NULL,1,1,0,'2025-11-10 07:04:56','2025-11-10 07:04:56',NULL),(124,12,'Bean LateKhote','ပဲနံ လိပ်ခုပ်',NULL,'ပဲနံလိပ်ခုပ်',2800.00,NULL,1,1,0,'2025-11-10 07:21:02','2025-11-10 07:21:02',NULL);
/*!40000 ALTER TABLE `items` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `job_batches`
--

DROP TABLE IF EXISTS `job_batches`;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `job_batches` (
  `id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `total_jobs` int NOT NULL,
  `pending_jobs` int NOT NULL,
  `failed_jobs` int NOT NULL,
  `failed_job_ids` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `options` mediumtext COLLATE utf8mb4_unicode_ci,
  `cancelled_at` int DEFAULT NULL,
  `created_at` int NOT NULL,
  `finished_at` int DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `job_batches`
--

LOCK TABLES `job_batches` WRITE;
/*!40000 ALTER TABLE `job_batches` DISABLE KEYS */;
/*!40000 ALTER TABLE `job_batches` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `jobs`
--

DROP TABLE IF EXISTS `jobs`;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `jobs` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `queue` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `attempts` tinyint unsigned NOT NULL,
  `reserved_at` int unsigned DEFAULT NULL,
  `available_at` int unsigned NOT NULL,
  `created_at` int unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `jobs_queue_index` (`queue`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `jobs`
--

LOCK TABLES `jobs` WRITE;
/*!40000 ALTER TABLE `jobs` DISABLE KEYS */;
/*!40000 ALTER TABLE `jobs` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `migrations`
--

DROP TABLE IF EXISTS `migrations`;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `migrations` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `migration` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=27 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `migrations`
--

LOCK TABLES `migrations` WRITE;
/*!40000 ALTER TABLE `migrations` DISABLE KEYS */;
INSERT INTO `migrations` VALUES (1,'2024_01_01_000001_create_users_table',1),(2,'2024_01_01_000002_create_cache_table',1),(3,'2024_01_01_000003_create_jobs_table',1),(4,'2024_01_01_000004_create_categories_table',1),(5,'2024_01_01_000005_create_items_table',1),(6,'2024_01_01_000006_create_tables_table',1),(7,'2024_01_01_000007_create_orders_table',1),(8,'2024_01_01_000008_create_order_items_table',1),(9,'2024_01_01_000009_create_printers_table',1),(10,'2024_01_01_000010_create_settings_table',1),(11,'2025_11_03_024417_create_permission_tables',1),(12,'2025_11_03_084620_create_expenses_table',2),(13,'2024_11_03_000001_simplify_order_statuses',3),(14,'2024_11_03_000002_add_foc_quantity_to_order_items',4),(15,'2024_11_03_000003_add_payment_details_to_orders',5),(16,'2025_11_03_161107_create_signage_media_table',6),(17,'2025_11_03_164145_create_signage_stats_table',7),(18,'2025_11_03_202559_create_inventory_tables',8),(19,'2025_11_03_202749_create_customers_table',8),(20,'2025_11_03_203430_create_report_caches_table',8),(21,'2025_11_04_013519_create_cards_table',9),(22,'2025_11_04_013530_create_card_transactions_table',9),(23,'2025_11_05_153301_add_zawgyi_name_to_items_table',10),(24,'2025_11_07_140248_create_products_table',11),(25,'2025_11_10_172104_add_service_charge_percentage_to_orders_table',12),(26,'2025_11_11_133004_create_personal_access_tokens_table',13);
/*!40000 ALTER TABLE `migrations` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `model_has_permissions`
--

DROP TABLE IF EXISTS `model_has_permissions`;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `model_has_permissions` (
  `permission_id` bigint unsigned NOT NULL,
  `model_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `model_id` bigint unsigned NOT NULL,
  PRIMARY KEY (`permission_id`,`model_id`,`model_type`),
  KEY `model_has_permissions_model_id_model_type_index` (`model_id`,`model_type`),
  CONSTRAINT `model_has_permissions_permission_id_foreign` FOREIGN KEY (`permission_id`) REFERENCES `permissions` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `model_has_permissions`
--

LOCK TABLES `model_has_permissions` WRITE;
/*!40000 ALTER TABLE `model_has_permissions` DISABLE KEYS */;
/*!40000 ALTER TABLE `model_has_permissions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `model_has_roles`
--

DROP TABLE IF EXISTS `model_has_roles`;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `model_has_roles` (
  `role_id` bigint unsigned NOT NULL,
  `model_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `model_id` bigint unsigned NOT NULL,
  PRIMARY KEY (`role_id`,`model_id`,`model_type`),
  KEY `model_has_roles_model_id_model_type_index` (`model_id`,`model_type`),
  CONSTRAINT `model_has_roles_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `model_has_roles`
--

LOCK TABLES `model_has_roles` WRITE;
/*!40000 ALTER TABLE `model_has_roles` DISABLE KEYS */;
INSERT INTO `model_has_roles` VALUES (1,'App\\Models\\User',1),(2,'App\\Models\\User',2),(3,'App\\Models\\User',3),(3,'App\\Models\\User',4),(1,'App\\Models\\User',5);
/*!40000 ALTER TABLE `model_has_roles` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `order_items`
--

DROP TABLE IF EXISTS `order_items`;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `order_items` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `order_id` bigint unsigned NOT NULL,
  `item_id` bigint unsigned NOT NULL,
  `quantity` int NOT NULL,
  `foc_quantity` int NOT NULL DEFAULT '0',
  `price` decimal(10,2) NOT NULL,
  `subtotal` decimal(10,2) NOT NULL,
  `is_foc` tinyint(1) NOT NULL DEFAULT '0',
  `notes` text COLLATE utf8mb4_unicode_ci,
  `status` enum('pending','preparing','ready','served') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pending',
  `is_printed` tinyint(1) NOT NULL DEFAULT '0',
  `printed_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `order_items_item_id_foreign` (`item_id`),
  KEY `order_items_order_id_status_index` (`order_id`,`status`),
  CONSTRAINT `order_items_item_id_foreign` FOREIGN KEY (`item_id`) REFERENCES `items` (`id`) ON DELETE CASCADE,
  CONSTRAINT `order_items_order_id_foreign` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=202 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `order_items`
--

LOCK TABLES `order_items` WRITE;
/*!40000 ALTER TABLE `order_items` DISABLE KEYS */;
INSERT INTO `order_items` VALUES (1,1,2,1,0,2500.00,2500.00,0,'','pending',0,NULL,'2025-11-02 21:17:40','2025-11-02 21:17:40'),(2,2,2,1,0,2500.00,2500.00,0,'','pending',0,NULL,'2025-11-02 21:20:15','2025-11-02 21:20:15'),(3,3,3,1,0,3000.00,3000.00,0,'','pending',0,NULL,'2025-11-02 21:35:27','2025-11-02 21:35:27'),(4,4,5,2,0,3500.00,7000.00,0,'','pending',0,NULL,'2025-11-02 21:45:06','2025-11-02 21:45:06'),(5,4,4,1,0,3000.00,3000.00,0,'','pending',0,NULL,'2025-11-02 21:45:06','2025-11-02 21:45:06'),(6,9,1,1,0,2000.00,2000.00,0,'','pending',0,NULL,'2025-11-03 01:55:34','2025-11-03 01:55:34'),(7,11,2,1,0,2500.00,2500.00,0,'','served',0,NULL,'2025-11-03 02:08:51','2025-11-03 02:08:51'),(8,12,1,1,0,2000.00,2000.00,0,'','served',0,NULL,'2025-11-03 02:14:24','2025-11-03 02:14:24'),(9,13,2,1,0,2500.00,2500.00,0,'','pending',0,NULL,'2025-11-03 02:51:23','2025-11-03 02:51:23'),(10,13,5,1,0,3500.00,3500.00,0,'','pending',0,NULL,'2025-11-03 02:51:23','2025-11-03 02:51:23'),(11,13,1,1,0,2000.00,2000.00,0,'','pending',0,NULL,'2025-11-03 02:51:23','2025-11-03 02:51:23'),(15,15,2,1,0,2500.00,2500.00,0,'','pending',0,NULL,'2025-11-03 02:55:31','2025-11-03 02:55:31'),(16,15,3,1,0,3000.00,3000.00,0,'','pending',0,NULL,'2025-11-03 02:55:31','2025-11-03 02:55:31'),(17,15,1,1,0,2000.00,2000.00,0,'','pending',0,NULL,'2025-11-03 02:55:31','2025-11-03 02:55:31'),(18,15,5,1,0,3500.00,3500.00,0,'','pending',0,NULL,'2025-11-03 02:55:31','2025-11-03 02:55:31'),(19,16,3,1,0,3000.00,3000.00,0,'','pending',0,NULL,'2025-11-03 03:23:20','2025-11-03 03:23:20'),(20,16,1,1,0,2000.00,2000.00,0,'','pending',0,NULL,'2025-11-03 03:23:20','2025-11-03 03:23:20'),(21,17,3,3,0,3000.00,9000.00,0,'ပူပူလေး','pending',0,NULL,'2025-11-03 03:24:18','2025-11-03 03:24:18'),(22,18,3,1,0,3000.00,3000.00,0,'','pending',0,NULL,'2025-11-03 03:40:17','2025-11-03 03:40:17'),(23,18,1,1,0,2000.00,2000.00,0,'','pending',0,NULL,'2025-11-03 03:40:17','2025-11-03 03:40:17'),(24,19,3,1,0,3000.00,3000.00,0,'','pending',0,NULL,'2025-11-03 05:33:49','2025-11-03 05:33:49'),(25,19,1,1,0,2000.00,2000.00,0,'','pending',0,NULL,'2025-11-03 05:33:49','2025-11-03 05:33:49'),(26,19,5,1,0,3500.00,3500.00,0,'','pending',0,NULL,'2025-11-03 05:33:49','2025-11-03 05:33:49'),(27,20,2,1,0,2500.00,2500.00,0,'','pending',0,NULL,'2025-11-03 06:48:05','2025-11-03 06:48:05'),(28,20,3,4,1,3000.00,9000.00,0,'','pending',0,NULL,'2025-11-03 06:48:05','2025-11-03 06:48:05'),(29,21,1,1,0,2000.00,2000.00,0,'','served',0,NULL,'2025-11-03 06:53:17','2025-11-03 06:53:17'),(30,22,3,1,0,3000.00,3000.00,0,'','served',0,NULL,'2025-11-03 06:55:54','2025-11-03 06:55:54'),(31,22,1,1,0,2000.00,2000.00,0,'','served',0,NULL,'2025-11-03 06:55:54','2025-11-03 06:55:54'),(32,23,3,1,0,3000.00,3000.00,0,'','pending',0,NULL,'2025-11-03 06:56:49','2025-11-03 06:56:49'),(33,23,1,1,0,2000.00,2000.00,0,'','pending',0,NULL,'2025-11-03 06:56:49','2025-11-03 06:56:49'),(34,23,5,1,0,3500.00,3500.00,0,'','pending',0,NULL,'2025-11-03 06:56:49','2025-11-03 06:56:49'),(35,24,3,1,0,3000.00,3000.00,0,'','served',0,NULL,'2025-11-03 07:12:43','2025-11-03 07:12:43'),(36,24,1,1,0,2000.00,2000.00,0,'','served',0,NULL,'2025-11-03 07:12:43','2025-11-03 07:12:43'),(37,25,1,1,0,2000.00,2000.00,0,'','served',0,NULL,'2025-11-03 07:13:14','2025-11-03 07:13:14'),(38,25,4,1,0,3000.00,3000.00,0,'','served',0,NULL,'2025-11-03 07:13:14','2025-11-03 07:13:14'),(39,26,3,1,0,3000.00,3000.00,0,'','pending',0,NULL,'2025-11-03 07:14:05','2025-11-03 07:14:05'),(40,26,1,1,0,2000.00,2000.00,0,'','pending',0,NULL,'2025-11-03 07:14:05','2025-11-03 07:14:05'),(41,27,3,1,0,3000.00,3000.00,0,'','pending',0,NULL,'2025-11-03 07:16:34','2025-11-03 07:16:34'),(42,27,1,1,0,2000.00,2000.00,0,'','pending',0,NULL,'2025-11-03 07:16:34','2025-11-03 07:16:34'),(43,28,3,1,0,3000.00,3000.00,0,'','pending',0,NULL,'2025-11-03 07:23:52','2025-11-03 07:23:52'),(44,28,1,1,0,2000.00,2000.00,0,'','pending',0,NULL,'2025-11-03 07:23:52','2025-11-03 07:23:52'),(45,28,5,1,0,3500.00,3500.00,0,'','pending',0,NULL,'2025-11-03 07:23:52','2025-11-03 07:23:52'),(46,29,3,1,0,3000.00,3000.00,0,'','pending',0,NULL,'2025-11-03 07:26:49','2025-11-03 07:26:49'),(47,29,5,1,0,3500.00,3500.00,0,'','pending',0,NULL,'2025-11-03 07:26:49','2025-11-03 07:26:49'),(48,30,3,1,0,3000.00,3000.00,0,'','pending',0,NULL,'2025-11-03 07:26:57','2025-11-03 07:26:57'),(49,30,4,1,0,3000.00,3000.00,0,'','pending',0,NULL,'2025-11-03 07:26:57','2025-11-03 07:26:57'),(50,31,3,1,0,3000.00,3000.00,0,'','pending',0,NULL,'2025-11-03 08:49:06','2025-11-03 08:49:06'),(51,31,1,1,0,2000.00,2000.00,0,'','pending',0,NULL,'2025-11-03 08:49:06','2025-11-03 08:49:06'),(52,31,5,1,0,3500.00,3500.00,0,'','pending',0,NULL,'2025-11-03 08:49:06','2025-11-03 08:49:06'),(55,33,1,1,0,2000.00,2000.00,0,'','pending',0,NULL,'2025-11-03 16:13:53','2025-11-03 16:13:53'),(56,33,4,1,0,3000.00,3000.00,0,'','pending',0,NULL,'2025-11-03 16:13:53','2025-11-03 16:13:53'),(57,33,5,1,0,3500.00,3500.00,0,'','pending',0,NULL,'2025-11-03 16:13:53','2025-11-03 16:13:53'),(58,34,62,1,0,1400.00,1400.00,0,'','pending',0,NULL,'2025-11-04 10:13:32','2025-11-04 10:13:32'),(59,34,66,1,0,2500.00,2500.00,0,'','pending',0,NULL,'2025-11-04 10:13:32','2025-11-04 10:13:32'),(60,34,64,1,0,2000.00,2000.00,0,'','pending',0,NULL,'2025-11-04 10:13:32','2025-11-04 10:13:32'),(61,35,63,1,0,2000.00,2000.00,0,'','served',0,NULL,'2025-11-04 14:43:53','2025-11-04 14:43:53'),(62,35,62,1,0,1400.00,1400.00,0,'','served',0,NULL,'2025-11-04 14:43:53','2025-11-04 14:43:53'),(63,35,67,1,0,700.00,700.00,0,'','served',0,NULL,'2025-11-04 14:43:53','2025-11-04 14:43:53'),(64,36,63,1,0,2000.00,2000.00,0,'','pending',0,NULL,'2025-11-04 14:47:57','2025-11-04 14:47:57'),(65,36,62,1,0,1400.00,1400.00,0,'','pending',0,NULL,'2025-11-04 14:47:57','2025-11-04 14:47:57'),(66,36,66,1,0,2500.00,2500.00,0,'','pending',0,NULL,'2025-11-04 14:47:57','2025-11-04 14:47:57'),(67,36,64,1,0,2000.00,2000.00,0,'','pending',0,NULL,'2025-11-04 14:47:57','2025-11-04 14:47:57'),(68,36,61,1,0,2000.00,2000.00,0,'','pending',0,NULL,'2025-11-04 14:47:57','2025-11-04 14:47:57'),(69,37,67,1,0,700.00,700.00,0,'','pending',1,'2025-11-05 07:42:53','2025-11-04 14:48:06','2025-11-05 07:42:53'),(70,37,64,1,0,2000.00,2000.00,0,'','pending',1,'2025-11-05 07:42:53','2025-11-04 14:48:06','2025-11-05 07:42:53'),(71,37,61,1,0,2000.00,2000.00,0,'','pending',1,'2025-11-05 07:42:53','2025-11-04 14:48:06','2025-11-05 07:42:53'),(72,37,55,1,0,3000.00,3000.00,0,'','pending',1,'2025-11-05 07:42:53','2025-11-04 14:48:06','2025-11-05 07:42:53'),(73,38,63,1,0,2000.00,2000.00,0,'','pending',0,NULL,'2025-11-05 10:11:09','2025-11-05 10:11:09'),(74,38,64,1,0,2000.00,2000.00,0,'','pending',0,NULL,'2025-11-05 10:11:09','2025-11-05 10:11:09'),(75,38,66,1,0,2500.00,2500.00,0,'','pending',0,NULL,'2025-11-05 10:11:09','2025-11-05 10:11:09'),(76,38,62,1,0,1400.00,1400.00,0,'','pending',0,NULL,'2025-11-05 10:11:09','2025-11-05 10:11:09'),(77,39,70,1,0,3500.00,3500.00,0,'','pending',0,NULL,'2025-11-05 10:16:54','2025-11-05 10:16:54'),(78,39,63,1,0,2000.00,2000.00,0,'','pending',0,NULL,'2025-11-05 10:16:54','2025-11-05 10:16:54'),(79,39,62,1,0,1400.00,1400.00,0,'','pending',0,NULL,'2025-11-05 10:16:54','2025-11-05 10:16:54'),(80,39,66,1,0,2500.00,2500.00,0,'','pending',0,NULL,'2025-11-05 10:16:54','2025-11-05 10:16:54'),(81,39,64,1,0,2000.00,2000.00,0,'','pending',0,NULL,'2025-11-05 10:16:54','2025-11-05 10:16:54'),(82,40,62,1,0,1400.00,1400.00,0,'','pending',0,NULL,'2025-11-05 10:17:02','2025-11-05 10:17:02'),(83,40,64,1,0,2000.00,2000.00,0,'','pending',0,NULL,'2025-11-05 10:17:02','2025-11-05 10:17:02'),(84,40,66,1,0,2500.00,2500.00,0,'','pending',0,NULL,'2025-11-05 10:17:02','2025-11-05 10:17:02'),(85,41,70,1,0,3500.00,3500.00,0,'','pending',0,NULL,'2025-11-06 07:57:45','2025-11-06 07:57:45'),(86,41,63,1,0,2000.00,2000.00,0,'','pending',0,NULL,'2025-11-06 07:57:45','2025-11-06 07:57:45'),(87,41,64,1,0,2000.00,2000.00,0,'','pending',0,NULL,'2025-11-06 07:57:45','2025-11-06 07:57:45'),(88,42,63,1,0,2000.00,2000.00,0,'','pending',0,NULL,'2025-11-06 07:59:27','2025-11-06 07:59:27'),(89,42,64,1,0,2000.00,2000.00,0,'','pending',0,NULL,'2025-11-06 07:59:27','2025-11-06 07:59:27'),(90,42,61,1,0,2000.00,2000.00,0,'','pending',0,NULL,'2025-11-06 07:59:27','2025-11-06 07:59:27'),(91,43,70,1,0,3500.00,3500.00,0,'','pending',0,NULL,'2025-11-06 17:59:41','2025-11-06 17:59:41'),(92,43,63,1,0,2000.00,2000.00,0,'','pending',0,NULL,'2025-11-06 17:59:41','2025-11-06 17:59:41'),(93,43,62,1,0,1400.00,1400.00,0,'','pending',0,NULL,'2025-11-06 17:59:41','2025-11-06 17:59:41'),(94,44,64,1,0,2000.00,2000.00,0,'','pending',0,NULL,'2025-11-06 17:59:52','2025-11-06 17:59:52'),(95,44,66,1,0,2500.00,2500.00,0,'','pending',0,NULL,'2025-11-06 17:59:52','2025-11-06 17:59:52'),(96,44,62,1,0,1400.00,1400.00,0,'','pending',0,NULL,'2025-11-06 17:59:52','2025-11-06 17:59:52'),(97,45,63,1,0,2000.00,2000.00,0,'','pending',0,NULL,'2025-11-06 18:27:56','2025-11-06 18:27:56'),(98,45,62,1,0,1400.00,1400.00,0,'','pending',0,NULL,'2025-11-06 18:27:56','2025-11-06 18:27:56'),(99,45,67,1,0,700.00,700.00,0,'','pending',0,NULL,'2025-11-06 18:27:56','2025-11-06 18:27:56'),(100,45,64,1,0,2000.00,2000.00,0,'','pending',0,NULL,'2025-11-06 18:27:56','2025-11-06 18:27:56'),(101,45,66,1,0,2500.00,2500.00,0,'','pending',0,NULL,'2025-11-06 18:27:56','2025-11-06 18:27:56'),(102,45,65,1,0,2000.00,2000.00,0,'','pending',0,NULL,'2025-11-06 18:27:56','2025-11-06 18:27:56'),(103,45,61,1,0,2000.00,2000.00,0,'','pending',0,NULL,'2025-11-06 18:27:56','2025-11-06 18:27:56'),(104,45,55,1,0,3000.00,3000.00,0,'','pending',0,NULL,'2025-11-06 18:27:56','2025-11-06 18:27:56'),(105,45,57,1,0,5000.00,5000.00,0,'','pending',0,NULL,'2025-11-06 18:27:56','2025-11-06 18:27:56'),(106,45,69,1,0,2800.00,2800.00,0,'','pending',0,NULL,'2025-11-06 18:27:56','2025-11-06 18:27:56'),(107,46,62,1,0,1400.00,1400.00,0,'','pending',0,NULL,'2025-11-06 18:53:26','2025-11-06 18:53:26'),(108,46,67,1,0,700.00,700.00,0,'','pending',0,NULL,'2025-11-06 18:53:26','2025-11-06 18:53:26'),(109,47,63,1,0,2000.00,2000.00,0,'','pending',0,NULL,'2025-11-10 06:14:43','2025-11-10 06:14:43'),(110,47,62,1,0,1400.00,1400.00,0,'','pending',0,NULL,'2025-11-10 06:14:43','2025-11-10 06:14:43'),(111,47,67,1,0,700.00,700.00,0,'','pending',0,NULL,'2025-11-10 06:14:43','2025-11-10 06:14:43'),(112,48,18,1,0,3000.00,3000.00,0,'','pending',0,NULL,'2025-11-10 07:09:15','2025-11-10 07:09:15'),(113,49,124,1,0,2800.00,2800.00,0,'','pending',1,'2025-11-10 07:54:28','2025-11-10 07:54:28','2025-11-10 07:54:28'),(114,49,63,1,0,2000.00,2000.00,0,'','pending',1,'2025-11-10 07:54:28','2025-11-10 07:54:28','2025-11-10 07:54:28'),(115,49,62,1,0,1500.00,1500.00,0,'','pending',1,'2025-11-10 07:54:28','2025-11-10 07:54:28','2025-11-10 07:54:28'),(116,50,63,1,0,2000.00,2000.00,0,'','pending',1,'2025-11-10 07:59:24','2025-11-10 07:59:23','2025-11-10 07:59:24'),(117,50,124,1,0,2800.00,2800.00,0,'','pending',1,'2025-11-10 07:59:24','2025-11-10 07:59:23','2025-11-10 07:59:24'),(118,50,118,1,0,500.00,500.00,0,'','pending',1,'2025-11-10 07:59:24','2025-11-10 07:59:23','2025-11-10 07:59:24'),(119,57,63,2,0,2000.00,4000.00,0,'','pending',0,'2025-11-10 08:12:30','2025-11-10 08:12:30','2025-11-10 09:13:04'),(120,57,62,2,0,1500.00,3000.00,0,'','pending',0,'2025-11-10 08:12:30','2025-11-10 08:12:30','2025-11-10 09:13:04'),(121,57,67,1,0,700.00,700.00,0,'','pending',0,'2025-11-10 08:12:30','2025-11-10 08:12:30','2025-11-10 09:13:04'),(122,58,124,2,0,2800.00,5600.00,0,'','pending',0,'2025-11-10 08:13:13','2025-11-10 08:13:13','2025-11-10 09:14:45'),(123,58,63,2,0,2000.00,4000.00,0,'','pending',0,'2025-11-10 08:13:13','2025-11-10 08:13:13','2025-11-10 09:14:45'),(124,58,62,2,0,1500.00,3000.00,0,'','pending',0,'2025-11-10 08:13:13','2025-11-10 08:13:13','2025-11-10 09:14:46'),(125,58,67,1,0,700.00,700.00,0,'','pending',0,'2025-11-10 08:13:13','2025-11-10 08:13:13','2025-11-10 09:14:46'),(126,58,29,1,0,2500.00,2500.00,0,'','pending',0,'2025-11-10 08:13:13','2025-11-10 08:13:13','2025-11-10 09:14:46'),(127,59,124,1,0,2800.00,2800.00,0,'','pending',1,'2025-11-10 08:28:05','2025-11-10 08:28:05','2025-11-10 08:28:05'),(128,59,63,1,0,2000.00,2000.00,0,'','pending',1,'2025-11-10 08:28:05','2025-11-10 08:28:05','2025-11-10 08:28:05'),(129,59,62,1,0,1500.00,1500.00,0,'','pending',1,'2025-11-10 08:28:05','2025-11-10 08:28:05','2025-11-10 08:28:05'),(130,59,67,1,0,700.00,700.00,0,'','pending',1,'2025-11-10 08:28:05','2025-11-10 08:28:05','2025-11-10 08:28:05'),(131,60,124,1,0,2800.00,2800.00,0,'','pending',1,'2025-11-10 08:35:07','2025-11-10 08:35:06','2025-11-10 08:35:07'),(132,60,118,1,0,500.00,500.00,0,'','pending',1,'2025-11-10 08:35:07','2025-11-10 08:35:06','2025-11-10 08:35:07'),(133,60,61,1,0,2000.00,2000.00,0,'','pending',1,'2025-11-10 08:35:07','2025-11-10 08:35:06','2025-11-10 08:35:07'),(134,60,20,1,0,2000.00,2000.00,0,'စပ်စပ်လေး လုပ်ပေးပါ','pending',1,'2025-11-10 08:35:07','2025-11-10 08:35:06','2025-11-10 08:35:07'),(135,61,75,1,0,3500.00,3500.00,0,'','pending',1,'2025-11-10 08:41:36','2025-11-10 08:41:36','2025-11-10 08:41:36'),(136,61,120,1,0,1800.00,1800.00,0,'','pending',1,'2025-11-10 08:41:36','2025-11-10 08:41:36','2025-11-10 08:41:36'),(137,61,73,1,0,1000.00,1000.00,0,'','pending',1,'2025-11-10 08:41:36','2025-11-10 08:41:36','2025-11-10 08:41:36'),(138,62,124,1,0,2800.00,2800.00,0,'ကြွပ်ကြွပ်လေး လုပ်ပေးပါ / ႂကြပ္ႂကြပ္ေလးလုပ္ေပးပါ','pending',1,'2025-11-10 08:50:27','2025-11-10 08:50:27','2025-11-10 08:50:27'),(139,62,63,1,0,2000.00,2000.00,0,'','pending',1,'2025-11-10 08:50:27','2025-11-10 08:50:27','2025-11-10 08:50:27'),(140,62,29,1,0,2500.00,2500.00,0,'ႂကြပ္ႂကြပ္ေလးလုပ္ေပးပါ','pending',1,'2025-11-10 08:50:27','2025-11-10 08:50:27','2025-11-10 08:50:27'),(141,62,120,1,0,1800.00,1800.00,0,'','pending',0,NULL,'2025-11-10 08:50:27','2025-11-10 08:50:27'),(142,62,75,1,0,3500.00,3500.00,0,'','pending',0,NULL,'2025-11-10 08:50:27','2025-11-10 08:50:27'),(143,62,71,1,0,4000.00,4000.00,0,'ႂကြပ္ႂကြပ္ေလးလုပ္ေပးပါ','pending',0,NULL,'2025-11-10 08:50:27','2025-11-10 08:50:27'),(144,63,75,1,0,3500.00,3500.00,0,'ပြည့်ပြည့်ဝဝ လုပ်ပေးပါ','pending',1,'2025-11-10 09:02:02','2025-11-10 08:55:52','2025-11-10 09:02:02'),(145,63,120,1,0,1800.00,1800.00,0,'','pending',1,'2025-11-10 09:02:02','2025-11-10 08:55:52','2025-11-10 09:02:02'),(146,63,71,1,0,4000.00,4000.00,0,'','pending',1,'2025-11-10 09:02:02','2025-11-10 08:55:52','2025-11-10 09:02:02'),(147,63,72,1,0,2500.00,2500.00,0,'','pending',1,'2025-11-10 09:02:02','2025-11-10 08:55:52','2025-11-10 09:02:02'),(148,64,121,1,0,1800.00,1800.00,0,'','pending',1,'2025-11-10 09:06:11','2025-11-10 09:06:11','2025-11-10 09:06:11'),(149,64,75,1,0,3500.00,3500.00,0,'','pending',1,'2025-11-10 09:06:11','2025-11-10 09:06:11','2025-11-10 09:06:11'),(150,64,120,1,0,1800.00,1800.00,0,'','pending',1,'2025-11-10 09:06:11','2025-11-10 09:06:11','2025-11-10 09:06:11'),(151,64,71,1,0,4000.00,4000.00,0,'','pending',1,'2025-11-10 09:06:11','2025-11-10 09:06:11','2025-11-10 09:06:11'),(152,65,124,1,0,2800.00,2800.00,0,'','pending',0,'2025-11-10 09:07:51','2025-11-10 09:07:51','2025-11-10 09:10:47'),(153,65,63,1,0,2000.00,2000.00,0,'','pending',0,'2025-11-10 09:07:51','2025-11-10 09:07:51','2025-11-10 09:10:47'),(154,65,62,1,0,1500.00,1500.00,0,'','pending',0,'2025-11-10 09:07:51','2025-11-10 09:07:51','2025-11-10 09:10:47'),(155,65,120,1,0,1800.00,1800.00,0,'','pending',0,'2025-11-10 09:07:52','2025-11-10 09:07:51','2025-11-10 09:10:47'),(156,65,75,1,0,3500.00,3500.00,0,'','pending',0,'2025-11-10 09:07:52','2025-11-10 09:07:51','2025-11-10 09:10:47'),(157,65,73,1,0,1000.00,1000.00,0,'','pending',0,'2025-11-10 09:07:52','2025-11-10 09:07:51','2025-11-10 09:10:47'),(158,65,119,1,0,3500.00,3500.00,0,'','pending',1,'2025-11-10 09:10:47','2025-11-10 09:10:47','2025-11-10 09:10:47'),(159,65,74,1,0,2000.00,2000.00,0,'','pending',1,'2025-11-10 09:10:47','2025-11-10 09:10:47','2025-11-10 09:10:47'),(160,65,72,1,0,2500.00,2500.00,0,'','pending',1,'2025-11-10 09:10:47','2025-11-10 09:10:47','2025-11-10 09:10:47'),(161,65,29,1,0,2500.00,2500.00,0,'','pending',1,'2025-11-10 09:10:47','2025-11-10 09:10:47','2025-11-10 09:10:47'),(162,65,67,1,0,700.00,700.00,0,'','pending',1,'2025-11-10 09:10:47','2025-11-10 09:10:47','2025-11-10 09:10:47'),(163,65,30,1,0,2500.00,2500.00,0,'','pending',1,'2025-11-10 09:10:47','2025-11-10 09:10:47','2025-11-10 09:10:47'),(164,57,124,1,0,2800.00,2800.00,0,'','pending',0,NULL,'2025-11-10 09:13:04','2025-11-10 09:13:04'),(165,57,74,1,0,2000.00,2000.00,0,'','pending',0,NULL,'2025-11-10 09:13:04','2025-11-10 09:13:04'),(166,57,73,1,0,1000.00,1000.00,0,'','pending',0,NULL,'2025-11-10 09:13:04','2025-11-10 09:13:04'),(167,57,120,1,0,1800.00,1800.00,0,'','pending',0,NULL,'2025-11-10 09:13:04','2025-11-10 09:13:04'),(168,58,73,1,0,1000.00,1000.00,0,'','pending',1,'2025-11-10 09:14:46','2025-11-10 09:14:46','2025-11-10 09:14:46'),(169,58,72,1,0,2500.00,2500.00,0,'','pending',1,'2025-11-10 09:14:46','2025-11-10 09:14:46','2025-11-10 09:14:46'),(170,58,71,1,0,4000.00,4000.00,0,'','pending',1,'2025-11-10 09:14:46','2025-11-10 09:14:46','2025-11-10 09:14:46'),(171,66,124,1,0,2800.00,2800.00,0,'','pending',1,'2025-11-10 09:14:59','2025-11-10 09:14:58','2025-11-10 09:14:59'),(172,66,63,1,0,2000.00,2000.00,0,'','pending',1,'2025-11-10 09:14:59','2025-11-10 09:14:58','2025-11-10 09:14:59'),(173,66,62,1,0,1500.00,1500.00,0,'','pending',1,'2025-11-10 09:14:59','2025-11-10 09:14:58','2025-11-10 09:14:59'),(174,66,73,1,0,1000.00,1000.00,0,'','pending',1,'2025-11-10 09:14:59','2025-11-10 09:14:58','2025-11-10 09:14:59'),(175,66,72,1,0,2500.00,2500.00,0,'','pending',1,'2025-11-10 09:14:59','2025-11-10 09:14:58','2025-11-10 09:14:59'),(176,66,71,1,0,4000.00,4000.00,0,'','pending',1,'2025-11-10 09:14:59','2025-11-10 09:14:58','2025-11-10 09:14:59'),(177,67,124,1,0,2800.00,2800.00,0,'','pending',0,'2025-11-10 09:52:41','2025-11-10 09:52:41','2025-11-10 09:53:09'),(178,67,63,1,0,2000.00,2000.00,0,'','pending',0,'2025-11-10 09:52:41','2025-11-10 09:52:41','2025-11-10 09:53:09'),(179,67,62,1,0,1500.00,1500.00,0,'','pending',0,'2025-11-10 09:52:41','2025-11-10 09:52:41','2025-11-10 09:53:09'),(180,67,75,1,0,3500.00,3500.00,0,'','pending',0,'2025-11-10 09:52:41','2025-11-10 09:52:41','2025-11-10 09:53:09'),(181,67,120,1,0,1800.00,1800.00,0,'','pending',0,'2025-11-10 09:52:41','2025-11-10 09:52:41','2025-11-10 09:53:09'),(182,67,71,1,0,4000.00,4000.00,0,'','pending',0,'2025-11-10 09:52:41','2025-11-10 09:52:41','2025-11-10 09:53:09'),(183,67,123,1,0,500.00,500.00,0,'','pending',0,'2025-11-10 09:53:09','2025-11-10 09:53:09','2025-11-10 09:54:18'),(184,67,22,1,0,4000.00,4000.00,0,'','pending',0,'2025-11-10 09:53:09','2025-11-10 09:53:09','2025-11-10 09:54:18'),(185,67,24,1,0,3500.00,3500.00,0,'','pending',0,'2025-11-10 09:53:09','2025-11-10 09:53:09','2025-11-10 09:54:18'),(186,67,18,1,0,3000.00,3000.00,0,'','pending',1,'2025-11-10 09:54:18','2025-11-10 09:54:18','2025-11-10 09:54:18'),(187,67,21,1,0,1500.00,1500.00,0,'','pending',1,'2025-11-10 09:54:18','2025-11-10 09:54:18','2025-11-10 09:54:18'),(188,68,63,1,0,2000.00,2000.00,0,'','pending',1,'2025-11-10 10:34:52','2025-11-10 10:34:51','2025-11-10 10:34:52'),(189,68,62,1,0,1500.00,1500.00,0,'','pending',1,'2025-11-10 10:34:52','2025-11-10 10:34:51','2025-11-10 10:34:52'),(190,68,67,1,0,700.00,700.00,0,'','pending',1,'2025-11-10 10:34:52','2025-11-10 10:34:51','2025-11-10 10:34:52'),(191,69,124,1,0,2800.00,2800.00,0,'','pending',1,'2025-11-10 10:58:13','2025-11-10 10:58:12','2025-11-10 10:58:13'),(192,69,63,1,0,2000.00,2000.00,0,'','pending',1,'2025-11-10 10:58:13','2025-11-10 10:58:12','2025-11-10 10:58:13'),(193,69,62,1,0,1500.00,1500.00,0,'','pending',1,'2025-11-10 10:58:13','2025-11-10 10:58:12','2025-11-10 10:58:13'),(194,70,118,1,0,500.00,500.00,0,'','pending',1,'2025-11-10 11:11:47','2025-11-10 11:11:47','2025-11-10 11:11:47'),(195,70,20,1,0,2000.00,2000.00,0,'','pending',1,'2025-11-10 11:11:47','2025-11-10 11:11:47','2025-11-10 11:11:47'),(196,71,124,1,0,2800.00,2800.00,0,'ကောင်းကောင်းလေးလုပ်ပေးပါ','pending',1,'2025-11-10 11:12:31','2025-11-10 11:12:31','2025-11-10 11:12:31'),(197,72,63,1,0,2000.00,2000.00,0,'','served',0,NULL,'2025-11-10 11:15:20','2025-11-10 11:15:20'),(198,72,62,1,0,1500.00,1500.00,0,'','served',0,NULL,'2025-11-10 11:15:20','2025-11-10 11:15:20'),(199,73,118,1,0,500.00,500.00,0,'','pending',1,'2025-11-10 11:26:43','2025-11-10 11:26:43','2025-11-10 11:26:43'),(200,73,29,1,0,2500.00,2500.00,0,'','pending',1,'2025-11-10 11:26:43','2025-11-10 11:26:43','2025-11-10 11:26:43'),(201,73,67,1,0,700.00,700.00,0,'','pending',1,'2025-11-10 11:26:43','2025-11-10 11:26:43','2025-11-10 11:26:43');
/*!40000 ALTER TABLE `order_items` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `orders`
--

DROP TABLE IF EXISTS `orders`;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `orders` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `order_number` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `table_id` bigint unsigned DEFAULT NULL,
  `waiter_id` bigint unsigned DEFAULT NULL,
  `customer_id` bigint unsigned DEFAULT NULL,
  `cashier_id` bigint unsigned DEFAULT NULL,
  `order_type` enum('dine_in','takeaway') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'dine_in',
  `status` enum('pending','completed','cancelled') COLLATE utf8mb4_unicode_ci DEFAULT 'pending',
  `subtotal` decimal(10,2) NOT NULL DEFAULT '0.00',
  `tax_amount` decimal(10,2) NOT NULL DEFAULT '0.00',
  `tax_percentage` decimal(5,2) NOT NULL DEFAULT '0.00',
  `discount_amount` decimal(10,2) NOT NULL DEFAULT '0.00',
  `discount_percentage` decimal(5,2) NOT NULL DEFAULT '0.00',
  `service_charge` decimal(10,2) NOT NULL DEFAULT '0.00',
  `service_charge_percentage` decimal(5,2) NOT NULL DEFAULT '0.00',
  `total` decimal(10,2) NOT NULL DEFAULT '0.00',
  `payment_method` enum('cash','card','mobile') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'cash',
  `amount_received` decimal(10,2) NOT NULL DEFAULT '0.00',
  `change_amount` decimal(10,2) NOT NULL DEFAULT '0.00',
  `notes` text COLLATE utf8mb4_unicode_ci,
  `completed_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `orders_order_number_unique` (`order_number`),
  KEY `orders_table_id_foreign` (`table_id`),
  KEY `orders_waiter_id_foreign` (`waiter_id`),
  KEY `orders_cashier_id_foreign` (`cashier_id`),
  KEY `orders_status_created_at_index` (`status`,`created_at`),
  KEY `orders_order_number_index` (`order_number`),
  KEY `orders_customer_id_foreign` (`customer_id`),
  CONSTRAINT `orders_cashier_id_foreign` FOREIGN KEY (`cashier_id`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  CONSTRAINT `orders_customer_id_foreign` FOREIGN KEY (`customer_id`) REFERENCES `customers` (`id`) ON DELETE SET NULL,
  CONSTRAINT `orders_table_id_foreign` FOREIGN KEY (`table_id`) REFERENCES `tables` (`id`) ON DELETE SET NULL,
  CONSTRAINT `orders_waiter_id_foreign` FOREIGN KEY (`waiter_id`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=74 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `orders`
--

LOCK TABLES `orders` WRITE;
/*!40000 ALTER TABLE `orders` DISABLE KEYS */;
INSERT INTO `orders` VALUES (1,'202511030001',1,3,NULL,NULL,'dine_in','cancelled',2500.00,0.00,0.00,0.00,0.00,0.00,0.00,2500.00,'cash',0.00,0.00,'',NULL,'2025-11-02 21:17:40','2025-11-03 03:34:10',NULL),(2,'202511030002',1,3,NULL,NULL,'dine_in','cancelled',2500.00,0.00,0.00,0.00,0.00,0.00,0.00,2500.00,'cash',0.00,0.00,'',NULL,'2025-11-02 21:20:15','2025-11-03 03:34:44',NULL),(3,'202511030003',2,3,NULL,NULL,'dine_in','completed',3000.00,0.00,0.00,0.00,0.00,0.00,0.00,3000.00,'cash',0.00,0.00,'',NULL,'2025-11-02 21:35:27','2025-11-03 03:35:14',NULL),(4,'202511030004',3,3,NULL,2,'dine_in','completed',10000.00,0.00,0.00,0.00,0.00,0.00,0.00,10000.00,'cash',0.00,0.00,'','2025-11-03 06:22:01','2025-11-02 21:45:06','2025-11-03 06:22:01',NULL),(9,'202511030005',NULL,3,NULL,NULL,'takeaway','completed',2000.00,0.00,0.00,0.00,0.00,0.00,0.00,2000.00,'cash',0.00,0.00,'',NULL,'2025-11-03 01:55:34','2025-11-03 01:58:39',NULL),(11,'202511030006',6,NULL,NULL,2,'dine_in','completed',2500.00,75.00,3.00,0.00,0.00,0.00,0.00,2575.00,'cash',0.00,0.00,'','2025-11-03 02:08:51','2025-11-03 02:08:51','2025-11-03 02:08:51',NULL),(12,'202511030007',NULL,NULL,NULL,2,'takeaway','completed',2000.00,0.00,0.00,0.00,0.00,0.00,0.00,2000.00,'cash',0.00,0.00,'','2025-11-03 02:14:24','2025-11-03 02:14:24','2025-11-03 02:14:24',NULL),(13,'202511030008',4,3,NULL,2,'dine_in','completed',8000.00,0.00,0.00,2000.00,0.00,0.00,0.00,6000.00,'cash',6000.00,0.00,'','2025-11-03 06:41:33','2025-11-03 02:51:23','2025-11-03 06:41:33',NULL),(15,'202511030009',5,3,NULL,2,'dine_in','completed',11000.00,0.00,0.00,1000.00,0.00,0.00,0.00,10000.00,'cash',10000.00,0.00,'','2025-11-03 06:39:28','2025-11-03 02:55:31','2025-11-03 06:39:28',NULL),(16,'202511030010',6,3,NULL,2,'dine_in','completed',5000.00,0.00,0.00,0.00,0.00,0.00,0.00,5000.00,'cash',6000.00,1000.00,'','2025-11-03 06:36:06','2025-11-03 03:23:20','2025-11-03 06:36:06',NULL),(17,'202511030011',7,3,NULL,NULL,'dine_in','completed',9000.00,0.00,0.00,0.00,0.00,0.00,0.00,9000.00,'cash',0.00,0.00,'',NULL,'2025-11-03 03:24:18','2025-11-03 03:33:33',NULL),(18,'202511030012',8,3,NULL,NULL,'dine_in','completed',5000.00,0.00,0.00,0.00,0.00,0.00,0.00,5000.00,'cash',0.00,0.00,'','2025-11-03 04:50:59','2025-11-03 03:40:17','2025-11-03 04:50:59',NULL),(19,'202511030013',10,3,NULL,2,'dine_in','completed',8500.00,0.00,0.00,0.00,0.00,0.00,0.00,8500.00,'cash',0.00,0.00,'','2025-11-03 06:14:43','2025-11-03 05:33:49','2025-11-03 06:14:43',NULL),(20,'202511030014',1,3,NULL,2,'dine_in','completed',11500.00,0.00,0.00,0.00,0.00,0.00,0.00,11500.00,'cash',12000.00,500.00,'','2025-11-03 06:49:38','2025-11-03 06:48:05','2025-11-03 06:49:38',NULL),(21,'202511030015',NULL,NULL,NULL,2,'takeaway','completed',2000.00,0.00,0.00,0.00,0.00,0.00,0.00,2000.00,'cash',0.00,0.00,'','2025-11-03 06:53:17','2025-11-03 06:53:17','2025-11-03 06:53:17',NULL),(22,'202511030016',3,NULL,NULL,2,'dine_in','completed',5000.00,0.00,0.00,0.00,0.00,0.00,0.00,5000.00,'cash',0.00,0.00,'','2025-11-03 06:55:54','2025-11-03 06:55:54','2025-11-03 06:55:54',NULL),(23,'202511030017',1,3,NULL,2,'dine_in','completed',8500.00,0.00,0.00,0.00,0.00,0.00,0.00,8500.00,'cash',9000.00,500.00,'','2025-11-03 06:58:13','2025-11-03 06:56:49','2025-11-03 06:58:13',NULL),(24,'202511030018',3,NULL,NULL,2,'dine_in','completed',5000.00,0.00,0.00,0.00,0.00,0.00,0.00,5000.00,'cash',0.00,0.00,'','2025-11-03 07:12:43','2025-11-03 07:12:43','2025-11-03 07:12:43',NULL),(25,'202511030019',NULL,NULL,NULL,2,'takeaway','completed',5000.00,0.00,0.00,0.00,0.00,0.00,0.00,5000.00,'cash',0.00,0.00,'','2025-11-03 07:13:14','2025-11-03 07:13:14','2025-11-03 07:13:14',NULL),(26,'202511030020',1,3,NULL,2,'dine_in','completed',5000.00,0.00,0.00,0.00,0.00,0.00,0.00,5000.00,'cash',50000.00,45000.00,'','2025-11-03 07:17:42','2025-11-03 07:14:05','2025-11-03 07:17:42',NULL),(27,'202511030021',2,3,NULL,NULL,'dine_in','cancelled',5000.00,0.00,0.00,0.00,0.00,0.00,0.00,5000.00,'cash',0.00,0.00,'',NULL,'2025-11-03 07:16:34','2025-11-03 08:50:59','2025-11-03 08:50:59'),(28,'202511030022',1,3,NULL,2,'dine_in','completed',8500.00,0.00,0.00,0.00,0.00,0.00,0.00,8500.00,'cash',9000.00,500.00,'','2025-11-03 07:24:34','2025-11-03 07:23:52','2025-11-03 07:24:34',NULL),(29,'202511030023',2,3,NULL,2,'dine_in','completed',6500.00,0.00,0.00,0.00,0.00,0.00,0.00,6500.00,'cash',7000.00,500.00,'','2025-11-03 07:28:32','2025-11-03 07:26:49','2025-11-03 07:28:32',NULL),(30,'202511030024',4,3,NULL,2,'dine_in','completed',6000.00,0.00,0.00,0.00,0.00,0.00,0.00,6000.00,'cash',7000.00,1000.00,'','2025-11-03 07:27:32','2025-11-03 07:26:57','2025-11-03 07:27:32',NULL),(31,'202511030025',2,3,NULL,NULL,'dine_in','completed',8500.00,0.00,0.00,0.00,0.00,0.00,0.00,8500.00,'cash',0.00,0.00,'','2025-11-04 14:39:01','2025-11-03 08:49:06','2025-11-04 14:39:01',NULL),(33,'202511030026',3,3,NULL,NULL,'dine_in','completed',8500.00,0.00,0.00,0.00,0.00,0.00,0.00,8500.00,'cash',0.00,0.00,'','2025-11-04 14:33:14','2025-11-03 16:13:53','2025-11-04 14:33:14',NULL),(34,'202511040001',4,3,NULL,NULL,'dine_in','completed',5900.00,0.00,0.00,0.00,0.00,0.00,0.00,5900.00,'cash',0.00,0.00,'','2025-11-04 14:34:15','2025-11-04 10:13:32','2025-11-04 14:34:15',NULL),(35,'202511040002',4,NULL,NULL,2,'dine_in','completed',4100.00,123.00,3.00,0.00,0.00,0.00,0.00,4223.00,'cash',0.00,0.00,'','2025-11-04 14:43:53','2025-11-04 14:43:53','2025-11-04 14:43:53',NULL),(36,'202511040003',2,3,NULL,NULL,'dine_in','completed',9900.00,0.00,0.00,0.00,0.00,0.00,0.00,9900.00,'cash',10000.00,0.00,'','2025-11-05 10:17:35','2025-11-04 14:47:57','2025-11-05 10:17:35',NULL),(37,'202511040004',3,3,NULL,NULL,'dine_in','completed',7700.00,0.00,0.00,0.00,0.00,0.00,0.00,7700.00,'cash',8000.00,0.00,'','2025-11-04 14:48:39','2025-11-04 14:48:06','2025-11-04 14:48:39',NULL),(38,'202511050001',1,3,NULL,NULL,'dine_in','completed',7900.00,0.00,0.00,0.00,0.00,0.00,0.00,7900.00,'cash',8000.00,0.00,'','2025-11-05 10:12:58','2025-11-05 10:11:09','2025-11-05 10:12:58',NULL),(39,'202511050002',3,3,NULL,NULL,'dine_in','completed',11400.00,0.00,0.00,0.00,0.00,0.00,0.00,11400.00,'cash',20000.00,0.00,'','2025-11-05 10:28:10','2025-11-05 10:16:54','2025-11-05 10:28:10',NULL),(40,'202511050003',4,3,NULL,NULL,'dine_in','completed',5900.00,0.00,0.00,0.00,0.00,0.00,0.00,5900.00,'cash',7000.00,0.00,'','2025-11-05 10:22:25','2025-11-05 10:17:02','2025-11-05 10:22:25',NULL),(41,'202511060001',1,3,NULL,NULL,'dine_in','cancelled',7500.00,0.00,0.00,0.00,0.00,0.00,0.00,7500.00,'cash',0.00,0.00,'',NULL,'2025-11-06 07:57:45','2025-11-10 08:07:53','2025-11-10 08:07:53'),(42,'202511060002',2,3,NULL,NULL,'dine_in','completed',6000.00,0.00,0.00,0.00,0.00,0.00,0.00,6000.00,'cash',7000.00,0.00,'','2025-11-06 08:00:31','2025-11-06 07:59:27','2025-11-06 08:00:31',NULL),(43,'202511070001',2,3,NULL,NULL,'dine_in','cancelled',6900.00,0.00,0.00,0.00,0.00,0.00,0.00,6900.00,'cash',0.00,0.00,'',NULL,'2025-11-06 17:59:41','2025-11-10 08:07:46','2025-11-10 08:07:46'),(44,'202511070002',4,3,NULL,NULL,'dine_in','cancelled',5900.00,0.00,0.00,0.00,0.00,0.00,0.00,5900.00,'cash',0.00,0.00,'',NULL,'2025-11-06 17:59:52','2025-11-10 08:07:17','2025-11-10 08:07:17'),(45,'202511070003',3,3,NULL,NULL,'dine_in','cancelled',23400.00,0.00,0.00,0.00,0.00,0.00,0.00,23400.00,'cash',0.00,0.00,'',NULL,'2025-11-06 18:27:56','2025-11-10 08:07:39','2025-11-10 08:07:39'),(46,'202511070004',4,3,NULL,NULL,'dine_in','cancelled',2100.00,0.00,0.00,0.00,0.00,0.00,0.00,2100.00,'cash',0.00,0.00,'',NULL,'2025-11-06 18:53:26','2025-11-10 08:07:24','2025-11-10 08:07:24'),(47,'202511100001',5,3,NULL,NULL,'dine_in','cancelled',4100.00,0.00,0.00,0.00,0.00,0.00,0.00,4100.00,'cash',0.00,0.00,'',NULL,'2025-11-10 06:14:43','2025-11-10 08:04:52','2025-11-10 08:04:52'),(48,'202511100002',6,3,NULL,NULL,'dine_in','cancelled',3000.00,0.00,0.00,0.00,0.00,0.00,0.00,3000.00,'cash',0.00,0.00,'',NULL,'2025-11-10 07:09:15','2025-11-10 08:04:46','2025-11-10 08:04:46'),(49,'202511100003',7,3,NULL,NULL,'dine_in','cancelled',6300.00,0.00,0.00,0.00,0.00,0.00,0.00,6300.00,'cash',0.00,0.00,'',NULL,'2025-11-10 07:54:28','2025-11-10 08:04:41','2025-11-10 08:04:41'),(50,'202511100004',8,3,NULL,NULL,'dine_in','cancelled',5300.00,0.00,0.00,0.00,0.00,0.00,0.00,5300.00,'cash',0.00,0.00,'',NULL,'2025-11-10 07:59:23','2025-11-10 08:04:30','2025-11-10 08:04:30'),(57,'202511100005',1,3,NULL,NULL,'dine_in','completed',15300.00,0.00,0.00,0.00,0.00,0.00,0.00,15300.00,'cash',15500.00,0.00,'','2025-11-10 09:18:26','2025-11-10 08:12:30','2025-11-10 09:18:26',NULL),(58,'202511100006',2,3,NULL,NULL,'dine_in','pending',23300.00,0.00,0.00,0.00,0.00,0.00,0.00,23300.00,'cash',0.00,0.00,'',NULL,'2025-11-10 08:13:13','2025-11-10 09:14:45',NULL),(59,'202511100007',3,3,NULL,NULL,'dine_in','pending',7000.00,0.00,0.00,0.00,0.00,0.00,0.00,7000.00,'cash',0.00,0.00,'',NULL,'2025-11-10 08:28:05','2025-11-10 08:28:05',NULL),(60,'202511100008',4,3,NULL,NULL,'dine_in','pending',7300.00,0.00,0.00,0.00,0.00,0.00,0.00,7300.00,'cash',0.00,0.00,'',NULL,'2025-11-10 08:35:06','2025-11-10 08:35:06',NULL),(61,'202511100009',5,3,NULL,NULL,'dine_in','pending',6300.00,0.00,0.00,0.00,0.00,0.00,0.00,6300.00,'cash',0.00,0.00,'',NULL,'2025-11-10 08:41:36','2025-11-10 08:41:36',NULL),(62,'202511100010',6,3,NULL,NULL,'dine_in','completed',16600.00,830.00,5.00,0.00,0.00,0.00,10.00,17430.00,'cash',17500.00,0.00,'','2025-11-10 10:56:50','2025-11-10 08:50:27','2025-11-10 10:56:50',NULL),(63,'202511100011',7,3,NULL,NULL,'dine_in','completed',11800.00,590.00,0.00,0.00,0.00,0.00,0.00,12390.00,'cash',12500.00,0.00,'','2025-11-10 10:41:41','2025-11-10 08:55:52','2025-11-10 10:41:41',NULL),(64,'202511100012',8,3,NULL,NULL,'dine_in','cancelled',11100.00,0.00,0.00,0.00,0.00,0.00,0.00,11100.00,'cash',0.00,0.00,'',NULL,'2025-11-10 09:06:11','2025-11-10 09:55:44','2025-11-10 09:55:44'),(65,'202511100013',9,3,NULL,NULL,'dine_in','cancelled',26300.00,0.00,0.00,0.00,0.00,0.00,0.00,26300.00,'cash',0.00,0.00,'',NULL,'2025-11-10 09:07:51','2025-11-10 09:55:46','2025-11-10 09:55:46'),(66,'202511100014',10,3,NULL,NULL,'dine_in','cancelled',13800.00,0.00,0.00,0.00,0.00,0.00,0.00,13800.00,'cash',0.00,0.00,'',NULL,'2025-11-10 09:14:58','2025-11-10 09:55:37','2025-11-10 09:55:37'),(67,'202511100015',8,3,NULL,NULL,'dine_in','cancelled',28100.00,0.00,0.00,0.00,0.00,0.00,0.00,28100.00,'cash',0.00,0.00,'',NULL,'2025-11-10 09:52:41','2025-11-10 09:56:34','2025-11-10 09:56:34'),(68,'202511100016',1,3,NULL,NULL,'dine_in','completed',4200.00,210.00,0.00,0.00,0.00,0.00,0.00,4410.00,'cash',4500.00,0.00,'','2025-11-10 10:35:55','2025-11-10 10:34:51','2025-11-10 10:35:55',NULL),(69,'202511100017',1,3,NULL,NULL,'dine_in','completed',6300.00,0.00,5.00,0.00,0.00,0.00,10.00,6300.00,'cash',6300.00,0.00,'','2025-11-10 10:59:43','2025-11-10 10:58:12','2025-11-10 10:59:43',NULL),(70,'202511100018',1,3,NULL,NULL,'dine_in','pending',2500.00,0.00,0.00,0.00,0.00,0.00,0.00,2500.00,'cash',0.00,0.00,'လမ်းမတော် ရဲစခန်းသို့ ပို့ရန် ဖုန်းနံပါတ် 0912345678',NULL,'2025-11-10 11:11:47','2025-11-10 11:11:47',NULL),(71,'202511100019',6,3,NULL,NULL,'dine_in','pending',2800.00,0.00,0.00,0.00,0.00,0.00,0.00,2800.00,'cash',0.00,0.00,'',NULL,'2025-11-10 11:12:31','2025-11-10 11:12:31',NULL),(72,'202511100020',NULL,NULL,NULL,2,'takeaway','completed',3500.00,175.00,5.00,0.00,0.00,350.00,10.00,4025.00,'cash',0.00,0.00,'','2025-11-10 11:15:20','2025-11-10 11:15:20','2025-11-10 11:15:20',NULL),(73,'202511100021',7,3,NULL,NULL,'dine_in','completed',3700.00,0.00,5.00,700.00,0.00,0.00,10.00,3000.00,'cash',0.00,0.00,'','2025-11-10 11:28:09','2025-11-10 11:26:43','2025-11-10 11:28:09',NULL);
/*!40000 ALTER TABLE `orders` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `password_reset_tokens`
--

DROP TABLE IF EXISTS `password_reset_tokens`;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `password_reset_tokens`
--

LOCK TABLES `password_reset_tokens` WRITE;
/*!40000 ALTER TABLE `password_reset_tokens` DISABLE KEYS */;
/*!40000 ALTER TABLE `password_reset_tokens` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `permissions`
--

DROP TABLE IF EXISTS `permissions`;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `permissions` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `guard_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `permissions_name_guard_name_unique` (`name`,`guard_name`)
) ENGINE=InnoDB AUTO_INCREMENT=25 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `permissions`
--

LOCK TABLES `permissions` WRITE;
/*!40000 ALTER TABLE `permissions` DISABLE KEYS */;
INSERT INTO `permissions` VALUES (1,'view orders','web','2025-11-02 21:06:14','2025-11-02 21:06:14'),(2,'create orders','web','2025-11-02 21:06:14','2025-11-02 21:06:14'),(3,'update orders','web','2025-11-02 21:06:14','2025-11-02 21:06:14'),(4,'delete orders','web','2025-11-02 21:06:14','2025-11-02 21:06:14'),(6,'view items','web','2025-11-02 21:06:14','2025-11-02 21:06:14'),(7,'create items','web','2025-11-02 21:06:14','2025-11-02 21:06:14'),(8,'update items','web','2025-11-02 21:06:14','2025-11-02 21:06:14'),(9,'delete items','web','2025-11-02 21:06:14','2025-11-02 21:06:14'),(10,'view tables','web','2025-11-02 21:06:14','2025-11-02 21:06:14'),(11,'create tables','web','2025-11-02 21:06:14','2025-11-02 21:06:14'),(12,'update tables','web','2025-11-02 21:06:14','2025-11-02 21:06:14'),(13,'delete tables','web','2025-11-02 21:06:14','2025-11-02 21:06:14'),(14,'view users','web','2025-11-02 21:06:14','2025-11-02 21:06:14'),(15,'create users','web','2025-11-02 21:06:14','2025-11-02 21:06:14'),(16,'update users','web','2025-11-02 21:06:14','2025-11-02 21:06:14'),(17,'delete users','web','2025-11-02 21:06:14','2025-11-02 21:06:14'),(18,'view reports','web','2025-11-02 21:06:14','2025-11-02 21:06:14'),(19,'view dashboard','web','2025-11-02 21:06:14','2025-11-02 21:06:14'),(20,'view settings','web','2025-11-02 21:06:14','2025-11-02 21:06:14'),(21,'update settings','web','2025-11-02 21:06:14','2025-11-02 21:06:14'),(22,'view printers','web','2025-11-02 21:06:14','2025-11-02 21:06:14'),(23,'update printers','web','2025-11-02 21:06:14','2025-11-02 21:06:14'),(24,'print receipts','web','2025-11-02 21:06:14','2025-11-02 21:06:14');
/*!40000 ALTER TABLE `permissions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `personal_access_tokens`
--

DROP TABLE IF EXISTS `personal_access_tokens`;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `personal_access_tokens` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `tokenable_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `tokenable_id` bigint unsigned NOT NULL,
  `name` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(64) COLLATE utf8mb4_unicode_ci NOT NULL,
  `abilities` text COLLATE utf8mb4_unicode_ci,
  `last_used_at` timestamp NULL DEFAULT NULL,
  `expires_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `personal_access_tokens_token_unique` (`token`),
  KEY `personal_access_tokens_tokenable_type_tokenable_id_index` (`tokenable_type`,`tokenable_id`),
  KEY `personal_access_tokens_expires_at_index` (`expires_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `personal_access_tokens`
--

LOCK TABLES `personal_access_tokens` WRITE;
/*!40000 ALTER TABLE `personal_access_tokens` DISABLE KEYS */;
/*!40000 ALTER TABLE `personal_access_tokens` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `printers`
--

DROP TABLE IF EXISTS `printers`;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `printers` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `type` enum('kitchen','bar','receipt') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'receipt',
  `ip_address` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `port` int NOT NULL DEFAULT '9100',
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `paper_width` int NOT NULL DEFAULT '80',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `printers`
--

LOCK TABLES `printers` WRITE;
/*!40000 ALTER TABLE `printers` DISABLE KEYS */;
INSERT INTO `printers` VALUES (1,'Kitchen Printer','kitchen','192.168.0.88',9100,1,80,'2025-11-02 21:06:15','2025-11-10 08:32:53'),(2,'Bar Printer','bar','192.168.0.77',9100,1,80,'2025-11-02 21:06:15','2025-11-10 08:33:43'),(3,'Receipt Printer','receipt','192.168.0.66',9100,1,80,'2025-11-02 21:06:15','2025-11-10 08:33:43');
/*!40000 ALTER TABLE `printers` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `products`
--

DROP TABLE IF EXISTS `products`;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `products` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `type` enum('diamond','gold','platinum') COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name_mm` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `image` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `price` decimal(12,2) DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `sort_order` int NOT NULL DEFAULT '0',
  `type_data` json DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `products`
--

LOCK TABLES `products` WRITE;
/*!40000 ALTER TABLE `products` DISABLE KEYS */;
/*!40000 ALTER TABLE `products` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `purchase_order_items`
--

DROP TABLE IF EXISTS `purchase_order_items`;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `purchase_order_items` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `purchase_order_id` bigint unsigned NOT NULL,
  `stock_item_id` bigint unsigned NOT NULL,
  `quantity` decimal(10,2) NOT NULL,
  `unit_cost` decimal(10,2) NOT NULL,
  `total_cost` decimal(10,2) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `purchase_order_items_purchase_order_id_foreign` (`purchase_order_id`),
  KEY `purchase_order_items_stock_item_id_foreign` (`stock_item_id`),
  CONSTRAINT `purchase_order_items_purchase_order_id_foreign` FOREIGN KEY (`purchase_order_id`) REFERENCES `purchase_orders` (`id`) ON DELETE CASCADE,
  CONSTRAINT `purchase_order_items_stock_item_id_foreign` FOREIGN KEY (`stock_item_id`) REFERENCES `stock_items` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `purchase_order_items`
--

LOCK TABLES `purchase_order_items` WRITE;
/*!40000 ALTER TABLE `purchase_order_items` DISABLE KEYS */;
/*!40000 ALTER TABLE `purchase_order_items` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `purchase_orders`
--

DROP TABLE IF EXISTS `purchase_orders`;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `purchase_orders` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `po_number` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `supplier_id` bigint unsigned NOT NULL,
  `user_id` bigint unsigned NOT NULL,
  `order_date` date NOT NULL,
  `expected_delivery_date` date DEFAULT NULL,
  `received_date` date DEFAULT NULL,
  `status` enum('pending','received','cancelled') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pending',
  `total_amount` decimal(10,2) NOT NULL DEFAULT '0.00',
  `notes` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `purchase_orders_po_number_unique` (`po_number`),
  KEY `purchase_orders_supplier_id_foreign` (`supplier_id`),
  KEY `purchase_orders_user_id_foreign` (`user_id`),
  CONSTRAINT `purchase_orders_supplier_id_foreign` FOREIGN KEY (`supplier_id`) REFERENCES `suppliers` (`id`) ON DELETE CASCADE,
  CONSTRAINT `purchase_orders_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `purchase_orders`
--

LOCK TABLES `purchase_orders` WRITE;
/*!40000 ALTER TABLE `purchase_orders` DISABLE KEYS */;
/*!40000 ALTER TABLE `purchase_orders` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `report_caches`
--

DROP TABLE IF EXISTS `report_caches`;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `report_caches` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `report_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `report_date` date NOT NULL,
  `data` json NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `report_caches_report_type_report_date_unique` (`report_type`,`report_date`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `report_caches`
--

LOCK TABLES `report_caches` WRITE;
/*!40000 ALTER TABLE `report_caches` DISABLE KEYS */;
/*!40000 ALTER TABLE `report_caches` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `role_has_permissions`
--

DROP TABLE IF EXISTS `role_has_permissions`;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `role_has_permissions` (
  `permission_id` bigint unsigned NOT NULL,
  `role_id` bigint unsigned NOT NULL,
  PRIMARY KEY (`permission_id`,`role_id`),
  KEY `role_has_permissions_role_id_foreign` (`role_id`),
  CONSTRAINT `role_has_permissions_permission_id_foreign` FOREIGN KEY (`permission_id`) REFERENCES `permissions` (`id`) ON DELETE CASCADE,
  CONSTRAINT `role_has_permissions_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `role_has_permissions`
--

LOCK TABLES `role_has_permissions` WRITE;
/*!40000 ALTER TABLE `role_has_permissions` DISABLE KEYS */;
INSERT INTO `role_has_permissions` VALUES (1,1),(2,1),(3,1),(4,1),(6,1),(7,1),(8,1),(9,1),(10,1),(11,1),(12,1),(13,1),(14,1),(15,1),(16,1),(17,1),(18,1),(19,1),(20,1),(21,1),(22,1),(23,1),(24,1),(1,2),(2,2),(3,2),(6,2),(10,2),(24,2),(1,3),(2,3),(3,3),(4,3),(6,3),(10,3),(1,4),(1,5);
/*!40000 ALTER TABLE `role_has_permissions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `roles`
--

DROP TABLE IF EXISTS `roles`;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `roles` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `guard_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `roles_name_guard_name_unique` (`name`,`guard_name`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `roles`
--

LOCK TABLES `roles` WRITE;
/*!40000 ALTER TABLE `roles` DISABLE KEYS */;
INSERT INTO `roles` VALUES (1,'admin','web','2025-11-02 21:06:14','2025-11-02 21:06:14'),(2,'cashier','web','2025-11-02 21:06:14','2025-11-02 21:06:14'),(3,'waiter','web','2025-11-02 21:06:14','2025-11-02 21:06:14'),(4,'kitchen','web','2025-11-02 21:06:14','2025-11-02 21:06:14'),(5,'bar','web','2025-11-02 21:06:14','2025-11-02 21:06:14');
/*!40000 ALTER TABLE `roles` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `sessions`
--

DROP TABLE IF EXISTS `sessions`;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `sessions` (
  `id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` bigint unsigned DEFAULT NULL,
  `ip_address` varchar(45) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_agent` text COLLATE utf8mb4_unicode_ci,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `last_activity` int NOT NULL,
  PRIMARY KEY (`id`),
  KEY `sessions_user_id_index` (`user_id`),
  KEY `sessions_last_activity_index` (`last_activity`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `sessions`
--

LOCK TABLES `sessions` WRITE;
/*!40000 ALTER TABLE `sessions` DISABLE KEYS */;
INSERT INTO `sessions` VALUES ('sMEl6vYir6X0XlJGv82iaqq47NwG0lJwqpRnndck',1,'127.0.0.1','Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36','YTo0OntzOjY6Il90b2tlbiI7czo0MDoiVVZOcFpVd3oyZVY1bGFEczZ3bU5STlVZRDVQVVh3dUhLWGRrU2pxSiI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6Mzc6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9hZG1pbi9kYXNoYm9hcmQiO3M6NToicm91dGUiO3M6MTU6ImFkbWluLmRhc2hib2FyZCI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fXM6NTA6ImxvZ2luX3dlYl81OWJhMzZhZGRjMmIyZjk0MDE1ODBmMDE0YzdmNThlYTRlMzA5ODlkIjtpOjE7fQ==',1762852342);
/*!40000 ALTER TABLE `sessions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `settings`
--

DROP TABLE IF EXISTS `settings`;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `settings` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `value` text COLLATE utf8mb4_unicode_ci,
  `type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'string',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `settings_key_unique` (`key`)
) ENGINE=InnoDB AUTO_INCREMENT=45 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `settings`
--

LOCK TABLES `settings` WRITE;
/*!40000 ALTER TABLE `settings` DISABLE KEYS */;
INSERT INTO `settings` VALUES (1,'business_name','Thar Cho Cafe','string','2025-11-02 21:06:15','2025-11-02 21:06:15'),(2,'business_name_mm','သာချို ကဖီးနှင့် စားဖွယ်စုံ','string','2025-11-02 21:06:15','2025-11-06 09:05:09'),(3,'business_address','အမှတ်(၆၄)၊ ဘုန်းကြီးလမ်း(၆)ရပ်ကွက်၊ လမ်းမတော်မြို့နယ် ရန်ကုန် မြို့','string','2025-11-02 21:06:15','2025-11-06 09:00:50'),(4,'business_address_mm','ရန်ကုန်မြို့၊ မြန်မာနိုင်ငံ','string','2025-11-02 21:06:15','2025-11-02 21:06:15'),(5,'business_phone','09770707907','string','2025-11-02 21:06:15','2025-11-06 09:00:50'),(6,'business_email','info@tharchocafe.com','string','2025-11-02 21:06:15','2025-11-02 21:06:15'),(7,'tax_enabled','0','boolean','2025-11-02 21:06:15','2025-11-02 21:06:15'),(8,'tax_percentage','0','decimal','2025-11-02 21:06:15','2025-11-02 21:06:15'),(9,'service_charge_enabled','0','boolean','2025-11-02 21:06:15','2025-11-02 21:06:15'),(10,'service_charge_amount','0','decimal','2025-11-02 21:06:15','2025-11-02 21:06:15'),(11,'currency','MMK','string','2025-11-02 21:06:15','2025-11-02 21:06:15'),(12,'currency_symbol','Ks','string','2025-11-02 21:06:15','2025-11-02 21:06:15'),(13,'auto_print_kitchen','1','boolean','2025-11-02 21:06:15','2025-11-10 10:06:14'),(14,'auto_print_bar','1','boolean','2025-11-02 21:06:15','2025-11-10 10:06:14'),(15,'default_tax_percentage','5','float','2025-11-03 02:32:03','2025-11-10 10:46:17'),(16,'default_service_charge','10','float','2025-11-03 02:32:03','2025-11-10 10:06:14'),(17,'receipt_header','','string','2025-11-03 02:32:03','2025-11-03 02:32:03'),(18,'receipt_footer','ကျေးဇူးအထူးပင်တင်ရှိပါသည်','string','2025-11-03 02:32:03','2025-11-03 02:32:03'),(19,'show_logo_on_receipt','1','boolean','2025-11-03 02:32:03','2025-11-10 10:06:14'),(20,'date_format','Y-m-d','string','2025-11-03 02:32:03','2025-11-03 02:32:03'),(21,'time_format','H:i','string','2025-11-03 02:32:03','2025-11-03 02:32:03'),(22,'timezone','Asia/Yangon','string','2025-11-03 02:32:03','2025-11-03 02:32:03'),(23,'app_name','သာချို ကဖေးနှင့်စားဖွယ်စုံ','string','2025-11-03 03:53:36','2025-11-03 08:29:11'),(24,'app_logo','public/logos/tharcho_logo.png','string','2025-11-03 03:53:36','2025-11-10 07:40:40'),(25,'theme_primary_color','#F59E0B','string','2025-11-03 04:23:20','2025-11-03 04:29:31'),(26,'theme_secondary_color','#6366F1','string','2025-11-03 04:23:20','2025-11-03 04:23:20'),(27,'signage_enabled','1','boolean','2025-11-03 09:35:23','2025-11-10 10:06:14'),(28,'promotional_message','တန်ဆောင်တိုင် အထူး ပရိုမိုးရှင်း','string','2025-11-03 09:35:23','2025-11-03 09:39:11'),(29,'signage_rotation_speed','10','integer','2025-11-03 09:35:23','2025-11-03 09:35:23'),(30,'signage_show_prices','1','boolean','2025-11-03 09:35:23','2025-11-10 10:06:14'),(31,'signage_show_descriptions','1','boolean','2025-11-03 09:35:23','2025-11-10 10:06:14'),(32,'signage_show_availability','1','boolean','2025-11-03 09:35:23','2025-11-10 10:06:14'),(33,'signage_theme','dark','string','2025-11-03 09:35:23','2025-11-03 09:35:23'),(34,'signage_auto_refresh','5','integer','2025-11-03 09:35:23','2025-11-03 09:35:23'),(35,'signage_show_media','1','boolean','2025-11-03 10:04:21','2025-11-10 10:06:14'),(36,'auto_print_receipt','0','boolean','2025-11-03 13:54:10','2025-11-10 10:06:14'),(37,'card_system_enabled','0','boolean','2025-11-03 19:15:34','2025-11-10 10:06:14'),(38,'card_bonus_enabled','1','boolean','2025-11-03 19:15:34','2025-11-10 10:06:14'),(39,'card_bonus_percentage','10','float','2025-11-03 19:15:34','2025-11-03 19:15:34'),(40,'card_expiry_enabled','1','boolean','2025-11-03 19:15:34','2025-11-10 10:06:14'),(41,'card_expiry_months','12','integer','2025-11-03 19:15:34','2025-11-03 19:15:34'),(44,'default_service_charge_percentage','10','float','2025-11-10 10:46:17','2025-11-10 10:46:17');
/*!40000 ALTER TABLE `settings` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `signage_media`
--

DROP TABLE IF EXISTS `signage_media`;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `signage_media` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `title_mm` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `type` enum('video','image') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'image',
  `file_path` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `duration` int NOT NULL DEFAULT '10',
  `sort_order` int NOT NULL DEFAULT '0',
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `description` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `signage_media`
--

LOCK TABLES `signage_media` WRITE;
/*!40000 ALTER TABLE `signage_media` DISABLE KEYS */;
INSERT INTO `signage_media` VALUES (1,'စမ်းသပ်မှု','စမ်းသပ်မှု','image','signage-media/n9vyHFGhlougm8HO2PHLPGv0yDgEltdeNujCQ6OG.png',10,1,1,'စမ်းသပ်ခြင်း','2025-11-03 10:00:19','2025-11-04 15:25:52');
/*!40000 ALTER TABLE `signage_media` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `signage_stats`
--

DROP TABLE IF EXISTS `signage_stats`;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `signage_stats` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `date` date NOT NULL,
  `total_views` int NOT NULL DEFAULT '0',
  `category_rotations` int NOT NULL DEFAULT '0',
  `media_displays` int NOT NULL DEFAULT '0',
  `total_uptime_minutes` int NOT NULL DEFAULT '0',
  `popular_categories` json DEFAULT NULL,
  `media_views` json DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `signage_stats_date_unique` (`date`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `signage_stats`
--

LOCK TABLES `signage_stats` WRITE;
/*!40000 ALTER TABLE `signage_stats` DISABLE KEYS */;
/*!40000 ALTER TABLE `signage_stats` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `stock_adjustments`
--

DROP TABLE IF EXISTS `stock_adjustments`;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `stock_adjustments` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `stock_item_id` bigint unsigned NOT NULL,
  `user_id` bigint unsigned NOT NULL,
  `type` enum('add','remove','damage','expired','correction') COLLATE utf8mb4_unicode_ci NOT NULL,
  `quantity` decimal(10,2) NOT NULL,
  `previous_stock` decimal(10,2) NOT NULL,
  `new_stock` decimal(10,2) NOT NULL,
  `reason` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `stock_adjustments_stock_item_id_foreign` (`stock_item_id`),
  KEY `stock_adjustments_user_id_foreign` (`user_id`),
  CONSTRAINT `stock_adjustments_stock_item_id_foreign` FOREIGN KEY (`stock_item_id`) REFERENCES `stock_items` (`id`) ON DELETE CASCADE,
  CONSTRAINT `stock_adjustments_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `stock_adjustments`
--

LOCK TABLES `stock_adjustments` WRITE;
/*!40000 ALTER TABLE `stock_adjustments` DISABLE KEYS */;
/*!40000 ALTER TABLE `stock_adjustments` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `stock_items`
--

DROP TABLE IF EXISTS `stock_items`;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `stock_items` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name_mm` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `sku` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `unit` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `current_stock` decimal(10,2) NOT NULL DEFAULT '0.00',
  `minimum_stock` decimal(10,2) NOT NULL DEFAULT '0.00',
  `unit_cost` decimal(10,2) NOT NULL DEFAULT '0.00',
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `stock_items_sku_unique` (`sku`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `stock_items`
--

LOCK TABLES `stock_items` WRITE;
/*!40000 ALTER TABLE `stock_items` DISABLE KEYS */;
/*!40000 ALTER TABLE `stock_items` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `suppliers`
--

DROP TABLE IF EXISTS `suppliers`;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `suppliers` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name_mm` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `contact_person` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `phone` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `address` text COLLATE utf8mb4_unicode_ci,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `suppliers`
--

LOCK TABLES `suppliers` WRITE;
/*!40000 ALTER TABLE `suppliers` DISABLE KEYS */;
/*!40000 ALTER TABLE `suppliers` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tables`
--

DROP TABLE IF EXISTS `tables`;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `tables` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name_mm` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `capacity` int NOT NULL DEFAULT '4',
  `status` enum('available','occupied','reserved') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'available',
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `sort_order` int NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `tables`
--

LOCK TABLES `tables` WRITE;
/*!40000 ALTER TABLE `tables` DISABLE KEYS */;
INSERT INTO `tables` VALUES (1,'Table 1','စားပွဲ ၁',2,'occupied',1,1,'2025-11-02 21:06:15','2025-11-10 11:11:47',NULL),(2,'Table 2','စားပွဲ ၂',2,'occupied',1,2,'2025-11-02 21:06:15','2025-11-10 08:13:13',NULL),(3,'Table 3','စားပွဲ ၃',4,'occupied',1,3,'2025-11-02 21:06:15','2025-11-10 08:28:05',NULL),(4,'Table 4','စားပွဲ ၄',4,'occupied',1,4,'2025-11-02 21:06:15','2025-11-10 08:35:06',NULL),(5,'Table 5','စားပွဲ ၅',4,'occupied',1,5,'2025-11-02 21:06:15','2025-11-10 08:41:36',NULL),(6,'Table 6','စားပွဲ ၆',4,'occupied',1,6,'2025-11-02 21:06:15','2025-11-10 11:12:31',NULL),(7,'Table 7','စားပွဲ ၇',6,'available',1,7,'2025-11-02 21:06:15','2025-11-10 11:28:09',NULL),(8,'Table 8','စားပွဲ ၈',6,'available',1,8,'2025-11-02 21:06:15','2025-11-10 09:56:20',NULL),(9,'Table 9','စားပွဲ ၉',8,'available',1,9,'2025-11-02 21:06:15','2025-11-10 09:44:39',NULL),(10,'Table 10','စားပွဲ ၁၀',8,'available',1,10,'2025-11-02 21:06:15','2025-11-10 09:44:30',NULL);
/*!40000 ALTER TABLE `tables` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `users` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `phone` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_email_unique` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` VALUES (1,'Admin','admin@tharchocafe.com',NULL,'$2y$12$GbKboMjTfH..UdCbcy.nPOmqa6QuK5KKu8rE21wpgrA8GuJvQsKUW','+95 9 111 111 111',1,NULL,'2025-11-02 21:06:14','2025-11-02 21:06:14',NULL),(2,'Cashier','cashier@tharchocafe.com',NULL,'$2y$12$Yo0ORXvvW9Mjm7F/U6Q70.vMsF80VTn5cHzzWmgwwmNt3fPmZhV3S','+95 9 222 222 222',1,NULL,'2025-11-02 21:06:15','2025-11-02 21:06:15',NULL),(3,'Nay Ye','waiter@tharchocafe.com',NULL,'$2y$12$hGL99/mDmq/q6n9QSbdlaOGM2LRxZn9Q8AGPJZDMUr1yuTiuPiZ4S','+95 9 333 333 333',1,NULL,'2025-11-02 21:06:15','2025-11-03 03:22:58',NULL),(4,'Waiter 2','waiter2@tharchocafe.com',NULL,'$2y$12$25EK5vuIDNL5k1JcJPRIVuOfjF6ybqFD/mrvRu.z7uhnP.q3cQFbW','+95 9 444 444 444',1,NULL,'2025-11-02 21:06:15','2025-11-02 21:06:15',NULL),(5,'Admin User','admin@tharpos.com',NULL,'$2y$12$ms5J2N6ZgZJW3zF0849dEeRfnFHnmFwSL4WEJQ/3...etC2lR0XGK','09123456789',1,NULL,'2025-11-11 01:13:42','2025-11-11 01:13:42',NULL);
/*!40000 ALTER TABLE `users` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2025-11-11 15:43:32
