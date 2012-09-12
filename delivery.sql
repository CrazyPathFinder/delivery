-- phpMyAdmin SQL Dump
-- version 3.5.0-rc1
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Sep 12, 2012 at 11:55 AM
-- Server version: 5.1.63-0ubuntu0.11.10.1
-- PHP Version: 5.3.6-13ubuntu3.8

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `delivery`
--

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE IF NOT EXISTS `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `email` varchar(150) NOT NULL,
  `email_canonical` varchar(150) NOT NULL,
  `enabled` tinyint(1) NOT NULL,
  `salt` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `last_login` datetime DEFAULT NULL,
  `confirmation_token` varchar(255) DEFAULT NULL,
  `password_requested_at` datetime DEFAULT NULL,
  `locked` tinyint(1) NOT NULL,
  `expired` tinyint(1) NOT NULL,
  `expires_at` datetime DEFAULT NULL,
  `roles` longtext COMMENT '(DC2Type:array)',
  `credentials_expired` tinyint(1) NOT NULL,
  `credentials_expires_at` datetime DEFAULT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `email_idx` (`email`),
  UNIQUE KEY `email_canonical_idx` (`email_canonical`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=7 ;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `email`, `email_canonical`, `enabled`, `salt`, `password`, `last_login`, `confirmation_token`, `password_requested_at`, `locked`, `expired`, `expires_at`, `roles`, `credentials_expired`, `credentials_expires_at`, `created_at`, `updated_at`) VALUES
(1, 'avenger999@gmail.com', 'avenger999@gmail.com', 1, 'g8lzfdd92zkkw44s048848sos8sw4c8', '69591df2970752554dc8fdbe685a7248dde26ed675a32d271126e29168b61dd193fd3d27f81b1e6a62b9ea010a2f89cdcaa2b0a80c6f72057d2c5f896e74887a', NULL, NULL, NULL, 0, 0, NULL, 'a:1:{i:0;s:10:"ROLE_ADMIN";}', 0, NULL, '2012-09-04 00:00:00', '2012-09-04 00:00:00'),
(2, 's.yaskevich@ssp-soft.by', 's.yaskevich@ssp-soft.by', 1, 'kxukjp5jw6oo0wssks0480cok48w8sw', 'ce3d46614a2fc59fa58adcd99d48edef2571995a0bc1d4ec4742221bf983a8317279507a73dc5754567ea634a596bc4b0faa827d27dfa3a4d66e8335ebe4ec99', NULL, '2fr6wrbpzfokkcc88k8w0w40wos0swk0c8co4kc8gc048ock48', '2012-09-11 18:06:06', 0, 0, NULL, 'a:1:{i:0;s:13:"ROLE_EMPLOYEE";}', 0, NULL, '2012-09-06 13:19:28', '2012-09-12 11:24:51'),
(3, 'ddmreg@gmail.com', 'ddmreg@gmail.com', 1, 'kcwb1lt7kdwcow848kg08g0cokksks8', '63de47e26b0a45fe69a96e29b05f354cdd6545c808498008f740acba42ff371ae19ebc6b38e32825d545343271fcd4e3e574148458b56a40b4d2b090052bc77d', NULL, NULL, NULL, 0, 0, NULL, 'a:1:{i:0;s:13:"ROLE_EMPLOYEE";}', 0, NULL, '2012-09-06 17:12:38', '2012-09-06 17:12:38'),
(4, 'e.marchuk@ssp-soft.by', 'e.marchuk@ssp-soft.by', 1, 'rg4hkidlxmo0og4s84wok4ssw8k04ko', 'a2331dac5468fc4b6d3dc51aaeaae60523e40ae23c2ebb3e79611fcd51481d010b94f9e8249d3b8f55d202786b8b5e987f986669277c3922722e51013f8671ed', NULL, '57o9ucxrau0wc048ok40444okgwsscgow48go8wsccwc844444', '2012-09-11 10:45:47', 0, 0, NULL, 'a:1:{i:0;s:13:"ROLE_EMPLOYEE";}', 0, NULL, '2012-09-11 10:43:21', '2012-09-11 10:45:47'),
(5, 'pqr_web@mail.ru', 'pqr_web@mail.ru', 1, '2uxdjgrvjn28so8ssc0os8g4g0g4og4', 'f75592175b1def38358aa14721b091f054fe592d9b15749cd8dd44bde79a4f31423f87de5b4fcf192ba4c5d7bd710e761d113cd0a60ef4b9aa1b5cd0b17776df', NULL, '4qe15dprq04kwwwwggkg8s0okkw4okoo4k0os4soow8ko4c0k8', '2012-09-11 18:52:19', 0, 0, NULL, 'a:1:{i:0;s:13:"ROLE_EMPLOYEE";}', 0, NULL, '2012-09-11 18:15:03', '2012-09-11 18:52:19'),
(6, 'petr.myazin@mail.ru', 'petr.myazin@mail.ru', 1, 'dkbquftah5sgcgwgcg844osk8w0o4s4', 'f5a9231d8e0ef4a7934a7bad4ad699c059f1548b75927963d166ebc18d45143ffd813d51806b45e670b25d005b3382070c12116444a59ba1cb7632bb2b9ff9d0', NULL, 'ppifj065kpw0k84sc8kcogcc8ccwssosossssk8cwkog4c4w4', '2012-09-11 18:58:21', 0, 0, NULL, 'a:1:{i:0;s:13:"ROLE_EMPLOYEE";}', 0, NULL, '2012-09-11 18:57:06', '2012-09-11 19:39:12');

-- --------------------------------------------------------

--
-- Table structure for table `users_profiles`
--

CREATE TABLE IF NOT EXISTS `users_profiles` (
  `user_id` int(11) NOT NULL,
  `user_name` varchar(150) NOT NULL,
  `user_photo` varchar(150) DEFAULT NULL,
  PRIMARY KEY (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `users_profiles`
--

INSERT INTO `users_profiles` (`user_id`, `user_name`, `user_photo`) VALUES
(1, 'Admin', NULL),
(2, 'Сергей', NULL),
(3, 'sergey', NULL),
(4, 'Катя', NULL),
(5, 'Petr', NULL),
(6, 'Petr 4', NULL);

--
-- Constraints for dumped tables
--

--
-- Constraints for table `users_profiles`
--
ALTER TABLE `users_profiles`
  ADD CONSTRAINT `users_profiles_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
