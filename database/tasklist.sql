-- phpMyAdmin SQL Dump
-- version 5.0.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jun 24, 2020 at 02:13 AM
-- Server version: 10.4.11-MariaDB
-- PHP Version: 7.4.3

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `tasklist`
--

-- --------------------------------------------------------

--
-- Table structure for table `project`
--

CREATE TABLE `project` (
  `id_project` int(11) NOT NULL,
  `name_project` varchar(255) COLLATE utf8mb4_spanish_ci NOT NULL,
  `description_project` varchar(255) COLLATE utf8mb4_spanish_ci NOT NULL,
  `date_project` date NOT NULL,
  `id_team` int(11) NOT NULL,
  `complete` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish_ci;

--
-- Dumping data for table `project`
--

INSERT INTO `project` (`id_project`, `name_project`, `description_project`, `date_project`, `id_team`, `complete`) VALUES
(1, 'To Do List Colaborative', 'Crear un to do list colaborativo utilizando tecnologias como php, javascript', '2020-06-19', 1, 0),
(14, 'Landing page', 'Landing page with my portfolio', '2020-07-10', 1, 1);

-- --------------------------------------------------------

--
-- Table structure for table `task`
--

CREATE TABLE `task` (
  `id_task` int(11) NOT NULL,
  `description` varchar(255) COLLATE utf8mb4_spanish_ci NOT NULL,
  `date` date NOT NULL,
  `id_person_assign` int(11) NOT NULL,
  `complete` tinyint(1) NOT NULL,
  `urgency` tinyint(1) NOT NULL,
  `id_team` int(11) NOT NULL,
  `id_project` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish_ci;

--
-- Dumping data for table `task`
--

INSERT INTO `task` (`id_task`, `description`, `date`, `id_person_assign`, `complete`, `urgency`, `id_team`, `id_project`) VALUES
(7, 'Nouveau Task', '2020-07-01', 13, 0, 0, 1, 1),
(9, 'Add photos into the projects page', '2020-06-26', 9, 1, 0, 1, 14),
(13, 'Initial Task', '2020-06-23', 9, 1, 0, 1, 1),
(15, 'Nouveau Task deux', '2020-06-30', 9, 0, 0, 1, 1),
(19, 'Upload project to a host', '2020-06-29', 9, 0, 0, 1, 14);

-- --------------------------------------------------------

--
-- Table structure for table `team`
--

CREATE TABLE `team` (
  `id_team` int(11) NOT NULL,
  `name_team` varchar(255) COLLATE utf8mb4_spanish_ci NOT NULL,
  `code_team` varchar(6) COLLATE utf8mb4_spanish_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish_ci;

--
-- Dumping data for table `team`
--

INSERT INTO `team` (`id_team`, `name_team`, `code_team`) VALUES
(1, 'Mejora Continua', '7x9NG'),
(2, 'IT', 'VkzxF'),
(5, 'Software', 'FLmzE');

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE `user` (
  `id_user` int(11) NOT NULL,
  `username` varchar(50) COLLATE utf8mb4_spanish_ci NOT NULL,
  `password` text COLLATE utf8mb4_spanish_ci NOT NULL,
  `name` varchar(100) COLLATE utf8mb4_spanish_ci NOT NULL,
  `last_name` varchar(100) COLLATE utf8mb4_spanish_ci NOT NULL,
  `email` varchar(50) COLLATE utf8mb4_spanish_ci NOT NULL,
  `id_team` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish_ci;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`id_user`, `username`, `password`, `name`, `last_name`, `email`, `id_team`) VALUES
(9, 'RSmith', '$2y$10$Di6SsJ0qngy787jbGrlcQOjrjZXOc9EHYrB5ikds2sPvQZrj09qLa', 'Ryan', 'Smith', 'rsmith@example.com', 1),
(12, 'jhonnyme', '$2y$10$M12e/7wEZnmnW4RY/.KEi.ELXc8LhIqmtM4mHvU3VW4qvuumBP2wC', 'Jhonnyme', 'lolavo', 'j@mail.com', 1),
(13, 'jgomez', '$2y$10$4JJ8eH7WuK6TROMixH6rWu4Uqa78ebeEtGypE.eUklML7.M7KLhxW', 'Jose', 'Gomez', 'jgomez@example.com', 1),
(26, 'JMay', '$2y$10$xTjZuu2tc9LuowC9P2p0veRMYo4sNVJhbSP/iOeZDbYB7PhnaYppS', 'Jane', 'May', 'JM@example.com', 2),
(27, 'pepito123', '$2y$10$PFpfU62Idz01Lw3x.Vt65uhdj/8l0snmOi/MPpmYLcPCiN/ioSVFa', 'Pepito', 'Valles', '', 5);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `project`
--
ALTER TABLE `project`
  ADD PRIMARY KEY (`id_project`),
  ADD KEY `id_team` (`id_team`);

--
-- Indexes for table `task`
--
ALTER TABLE `task`
  ADD PRIMARY KEY (`id_task`),
  ADD KEY `id_user` (`id_person_assign`),
  ADD KEY `id_project` (`id_project`),
  ADD KEY `id_team2` (`id_team`);

--
-- Indexes for table `team`
--
ALTER TABLE `team`
  ADD PRIMARY KEY (`id_team`);

--
-- Indexes for table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`id_user`),
  ADD UNIQUE KEY `username` (`username`),
  ADD KEY `id_team3` (`id_team`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `project`
--
ALTER TABLE `project`
  MODIFY `id_project` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT for table `task`
--
ALTER TABLE `task`
  MODIFY `id_task` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;

--
-- AUTO_INCREMENT for table `team`
--
ALTER TABLE `team`
  MODIFY `id_team` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `user`
--
ALTER TABLE `user`
  MODIFY `id_user` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=31;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `project`
--
ALTER TABLE `project`
  ADD CONSTRAINT `id_team` FOREIGN KEY (`id_team`) REFERENCES `team` (`id_team`);

--
-- Constraints for table `task`
--
ALTER TABLE `task`
  ADD CONSTRAINT `id_project` FOREIGN KEY (`id_project`) REFERENCES `project` (`id_project`),
  ADD CONSTRAINT `id_team2` FOREIGN KEY (`id_team`) REFERENCES `team` (`id_team`),
  ADD CONSTRAINT `id_user` FOREIGN KEY (`id_person_assign`) REFERENCES `user` (`id_user`);

--
-- Constraints for table `user`
--
ALTER TABLE `user`
  ADD CONSTRAINT `id_team3` FOREIGN KEY (`id_team`) REFERENCES `team` (`id_team`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
