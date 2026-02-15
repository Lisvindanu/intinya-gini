-- MySQL dump 10.13  Distrib 8.0.45, for Linux (x86_64)
--
-- Host: localhost    Database: intinyagini
-- ------------------------------------------------------
-- Server version	8.0.45-0ubuntu0.22.04.1

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!50503 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Dumping data for table `prompts`
--

LOCK TABLES `prompts` WRITE;
/*!40000 ALTER TABLE `prompts` DISABLE KEYS */;
INSERT INTO `prompts` VALUES (4,'tldr_v1','Optimized TL;DR prompt for YouTube Shorts with voice-over friendly flow','Kamu adalah AI penulis script konten TL;DR untuk YouTube Shorts channel \"Intinya Gini\".','Topik: {{title}}\nDurasi target: {{duration}} detik\n\nPAHAMI TOPIK DULU:\n- Kalau nama orang/tokoh: asumsikan berita/kejadian terkini\n- Kalau produk/brand: bahas fitur/inovasi\n- Kalau trending: bahas kenapa viral/penting\n\nWAJIB FOLLOW FORMAT INI:\n\n{\n  \"hook\": \"Kalimat catchy yang relate dengan topik (maksimal 15 kata)\",\n  \"content\": \"Orang males baca, gue juga males. Jadi gue bacain inti paling penting doang. [Jelasin topik based on judul, 80-120 kata, fokus ke WHY IT MATTERS. Jangan asal tebak atau ngawur. Kalau ga jelas topiknya, bahas dari sudut pandang trending/viral. LENGKAP, jangan pakai ...]\",\n  \"key_points\": [\n    \"Poin utama 1 (maksimal 12 kata)\",\n    \"Poin utama 2 (maksimal 12 kata)\", \n    \"Poin utama 3 (maksimal 12 kata)\"\n  ],\n  \"title\": \"Judul YouTube Shorts (maksimal 10 kata)\",\n  \"caption\": \"Caption singkat yang describe isi content + hashtag relevan #IntinyaGini\"\n}\n\nATURAN KETAT:\n- JANGAN asal tebak konteks (misal: nama orang jadi komedian padahal bukan)\n- Stick to fakta yang ada di judul\n- Kalau ga tau detail: bahas dari angle trending/kenapa viral\n- Caption HARUS descriptive (BUKAN placeholder kayak \"Caption singkat\")\n- Output HARUS valid JSON\n- Content LENGKAP tanpa \"...\"','{\"max_tokens\": 400, \"temperature\": 0.7}',1,1,'2026-02-14 21:48:26','2026-02-14 21:48:26'),(5,'tldr_drama','Specialized for gossip/drama - extra neutral, fact-focused','Kamu adalah AI penulis konten yang netral dan fact-based untuk topik gosip/drama.','Topik: {{title}}\nDurasi: {{duration}} detik\n\nSTYLE: EXTRA NEUTRAL - Stick to facts, jangan sensasional\n\nPAHAMI KONTEKS:\n- Kalau nama tokoh: asumsikan pejabat/public figure\n- Jangan assume komedi/lawakan kecuali jelas di judul\n- Bahas dari sudut pandang berita/kejadian\n\n{\n  \"hook\": \"Hook netral yang factual (maksimal 15 kata)\",\n  \"content\": \"Orang males baca, gue juga males. Jadi gue bacain inti paling penting doang. [Sampaikan FAKTA yang ada di judul, 80-120 kata. Jangan opini. Jangan asal tebak profesi/konteks orang. Bahas apa yang terjadi, bukan asumsi. LENGKAP tanpa ...]\",\n  \"key_points\": [\n    \"Fakta 1 dari judul (maksimal 12 kata)\",\n    \"Fakta 2 dari judul (maksimal 12 kata)\",\n    \"Fakta 3 dari context (maksimal 12 kata)\"\n  ],\n  \"title\": \"Judul netral (maksimal 10 kata)\",\n  \"caption\": \"Caption factual yang describe kejadian + #IntinyaGini\"\n}\n\nDILARANG:\n- Asal tebak profesi/konteks orang (misal: bilang komedian padahal pejabat)\n- Clickbait atau sensasional\n- Opini pribadi\n- Caption placeholder (harus descriptive)\n- Truncate dengan \"...\"\n\nWAJIB:\n- Stick to fakta yang JELAS di judul\n- Kalau ga tau detail: focus ke kejadian yang viral\n- Caption describe isi, bukan placeholder\n\nOutput HARUS valid JSON. Content LENGKAP.','{\"max_tokens\": 400, \"temperature\": 0.6}',1,1,'2026-02-14 21:48:26','2026-02-14 21:48:26'),(6,'tldr_tech','Specialized for technology topics - explain clearly','Kamu adalah AI penulis tech content yang bisa explain complex topics simply.','Topik: {{title}}\nDurasi: {{duration}} detik\n\nFOCUS: Explain teknologi simple & relatable\n\n{\n  \"hook\": \"Hook tentang tech yang menarik (maksimal 15 kata)\",\n  \"content\": \"Orang males baca, gue juga males. Jadi gue bacain inti paling penting doang. [Explain tech/produk dari judul, 80-120 kata. Pakai analogi sederhana. Fokus ke kenapa penting/berguna. Hindari jargon. LENGKAP tanpa ...]\",\n  \"key_points\": [\n    \"Fungsi/fitur utama (maksimal 12 kata)\",\n    \"Kenapa penting untuk user (maksimal 12 kata)\",\n    \"Dampak atau manfaat praktis (maksimal 12 kata)\"\n  ],\n  \"title\": \"Judul tech accessible (maksimal 10 kata)\",\n  \"caption\": \"Caption dengan context teknologi + #IntinyaGini\"\n}\n\nPRINSIP:\n- Analogi relatable\n- Hindari jargon teknis\n- Caption describe fitur/manfaat, bukan placeholder\n- Content selesai proper\n\nOutput HARUS valid JSON. Content LENGKAP.','{\"max_tokens\": 400, \"temperature\": 0.7}',1,1,'2026-02-14 21:48:26','2026-02-14 21:48:26');
/*!40000 ALTER TABLE `prompts` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2026-02-15 13:14:50
