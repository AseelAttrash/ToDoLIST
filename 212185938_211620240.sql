-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: אוגוסט 01, 2023 בזמן 07:13 PM
-- גרסת שרת: 10.4.28-MariaDB
-- PHP Version: 8.2.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `212185938_211620240`
--

-- --------------------------------------------------------

--
-- מבנה טבלה עבור טבלה `lists`
--

CREATE TABLE `lists` (
  `id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `creation_date` timestamp NULL DEFAULT current_timestamp(),
  `users` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- הוצאת מידע עבור טבלה `lists`
--

INSERT INTO `lists` (`id`, `title`, `creation_date`, `users`) VALUES
(6, 'testList', '2023-08-01 13:30:04', '[\"1\",\"9\",10]');

-- --------------------------------------------------------

--
-- מבנה טבלה עבור טבלה `tasks`
--

CREATE TABLE `tasks` (
  `id` int(11) NOT NULL,
  `list_id` int(11) DEFAULT NULL,
  `title` varchar(255) NOT NULL,
  `date_added` timestamp NULL DEFAULT current_timestamp(),
  `responsible_user` varchar(255) DEFAULT NULL,
  `done` tinyint(1) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- הוצאת מידע עבור טבלה `tasks`
--

INSERT INTO `tasks` (`id`, `list_id`, `title`, `date_added`, `responsible_user`, `done`) VALUES
(7, 6, 'machine learning task', '2023-08-01 13:30:22', '9', 1);

-- --------------------------------------------------------

--
-- מבנה טבלה עבור טבלה `users`
--

CREATE TABLE `users` (
  `ID` int(11) NOT NULL,
  `first_name` varchar(255) NOT NULL,
  `last_name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `token` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- הוצאת מידע עבור טבלה `users`
--

INSERT INTO `users` (`ID`, `first_name`, `last_name`, `email`, `password`, `token`) VALUES
(1, 'aseel', 'aseel', 'test@test.test', 'test', 'f3bdbe8cf95d6315be724f268e917e21f20932c6c2505e98b11b7ad512a7cbf2'),
(9, 'test1', 'test1', 'user1@test.test', 'test123', 'd2708eb74a7e6e8d728efd1e62ad0c355f69daaa0f8176319e8d252b803deba3'),
(10, 'test2', 'user2', 'user2@test.test', 'test123', NULL),
(11, 'test3', 'user3', 'user3@test.test', 'test123', NULL);

--
-- Indexes for dumped tables
--

--
-- אינדקסים לטבלה `lists`
--
ALTER TABLE `lists`
  ADD PRIMARY KEY (`id`);

--
-- אינדקסים לטבלה `tasks`
--
ALTER TABLE `tasks`
  ADD PRIMARY KEY (`id`),
  ADD KEY `list_id` (`list_id`);

--
-- אינדקסים לטבלה `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`ID`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `lists`
--
ALTER TABLE `lists`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `tasks`
--
ALTER TABLE `tasks`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- הגבלות לטבלאות שהוצאו
--

--
-- הגבלות לטבלה `tasks`
--
ALTER TABLE `tasks`
  ADD CONSTRAINT `tasks_ibfk_1` FOREIGN KEY (`list_id`) REFERENCES `lists` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
