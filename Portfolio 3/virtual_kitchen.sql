-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Apr 17, 2025 at 02:38 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `virtual_kitchen`
--

-- --------------------------------------------------------

--
-- Table structure for table `recipes`
--



CREATE TABLE `recipes` (
  `rid` int(11) NOT NULL,
  `name` varchar(50) NOT NULL,
  `description` varchar(300) DEFAULT NULL,
  `type` enum('French','Italian','Chinese','Indian','Mexican','Others') NOT NULL,
  `Cookingtime` int(4) DEFAULT NULL,
  `ingredients` varchar(1000) DEFAULT NULL,
  `instructions` varchar(1000) DEFAULT NULL,
  `image` varchar(200) DEFAULT NULL,
  `uid` int(11) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `recipes`
--

INSERT INTO `recipes` (`rid`, `name`, `description`, `type`, `Cookingtime`, `ingredients`, `instructions`, `image`, `uid`) VALUES
(1, 'Spaghetti Bolognese', 'A classic Italian dish.', 'Italian', 45, 'Spaghetti, Ground beef, Tomato sauce', 'Cook spaghetti. Brown beef. Mix sauce.', 'spaghetti_bolognese.jpg', 1),
(2, 'Pizza Margherita', 'A classic Italian pizza with mozzarella and basil.', 'Italian', 20, 'Flour, Water, Yeast, Tomato sauce, Mozzarella, Basil', 'Mix dough, Add toppings, Bake', 'pizza_margherita.jpg', 1),
(3, 'Chicken Curry', 'A spicy Indian dish with chicken and curry sauce.', 'Indian', 60, 'Chicken, Curry powder, Onions, Tomatoes, Garlic', 'Cook chicken, Add spices, Simmer', 'chicken_curry.jpg', 2),
(4, 'Garlic Bread', 'Crunchy, soft, French bread', '', 30, 'Flour, Water, Yeast, garlic cloves, garlic paste, basil', 'Mix dough, leave to rise, mix paste, Add paste to bread, bake', 'Garlic_bread.jpg', 4);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `uid` int(11) UNSIGNED NOT NULL,
  `username` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`uid`, `username`, `password`, `email`) VALUES
(1, 'john_doe', 'hashed_password', 'john@example.com'),
(2, 'alice', 'hashedpassword123', 'alice@example.com'),
(3, 'bob', 'hashedpassword456', 'bob@example.com'),
(4, 'Rumi', '$2y$10$IT6XFLtrgwI5MxFZa28tnupNNFe8dxMgb9UoOroYER4EVkzLmUnEy', 'rumi.kitchen@gmail.com');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `recipes`
--
ALTER TABLE `recipes`
  ADD PRIMARY KEY (`rid`),
  ADD KEY `uid` (`uid`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`uid`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `recipes`
--
ALTER TABLE `recipes`
  MODIFY `rid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `uid` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `recipes`
--
ALTER TABLE `recipes`
  ADD CONSTRAINT `recipes_ibfk_1` FOREIGN KEY (`uid`) REFERENCES `users` (`uid`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
