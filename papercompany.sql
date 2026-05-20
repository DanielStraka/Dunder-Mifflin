-- phpMyAdmin SQL Dump
-- version 4.9.0.1
-- https://www.phpmyadmin.net/
--
-- Počítač: 127.0.0.1
-- Vytvořeno: Čtv 23. čen 2022, 11:18
-- Verze serveru: 10.4.6-MariaDB
-- Verze PHP: 7.3.8

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Databáze: `papercompany`
--

-- --------------------------------------------------------

--
-- Struktura tabulky `employees`
--

CREATE TABLE `employees` (
  `id` int(10) NOT NULL,
  `Name` varchar(50) COLLATE utf8_czech_ci NOT NULL,
  `Surname` varchar(50) COLLATE utf8_czech_ci NOT NULL,
  `email` varchar(20) COLLATE utf8_czech_ci NOT NULL,
  `Birth` date NOT NULL,
  `Position` varchar(50) COLLATE utf8_czech_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_czech_ci;

--
-- Vypisuji data pro tabulku `employees`
--

INSERT INTO `employees` (`id`, `Name`, `Surname`, `email`, `Birth`, `Position`) VALUES
(1, 'Pepa', 'Tester', '', '1922-05-01', 'CEO'),
(2, 'Test', 'TestSurname', '', '1822-06-01', 'CO-Founder');

-- --------------------------------------------------------

--
-- Struktura tabulky `products`
--

CREATE TABLE `products` (
  `id` int(10) NOT NULL,
  `ProductName` varchar(50) COLLATE utf8_czech_ci NOT NULL,
  `Category` varchar(50) COLLATE utf8_czech_ci NOT NULL,
  `Price` int(11) NOT NULL,
  `Description` varchar(200) COLLATE utf8_czech_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_czech_ci;

--
-- Vypisuji data pro tabulku `products`
--

INSERT INTO `products` (`id`, `ProductName`, `Category`, `Price`, `Description`) VALUES
(1, 'Papír', 'Věci', 10, 'To je papír'),
(2, 'tužka', 'Věci', 100, 'Tužka ty troubo'),
(3, 'gu', 'Veci', 50, 'ghhghgh'),
(4, 'sponka', 'Veci', 1, 'f');

-- --------------------------------------------------------

--
-- Struktura tabulky `sales`
--

CREATE TABLE `sales` (
  `id` int(10) NOT NULL,
  `idProduct` int(10) NOT NULL,
  `idEmployee` int(10) NOT NULL,
  `Quantity` int(50) NOT NULL,
  `FinalPrice` int(100) NOT NULL,
  `Date` date NOT NULL,
  `Sale` tinyint(1) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_czech_ci;

--
-- Vypisuji data pro tabulku `sales`
--

INSERT INTO `sales` (`id`, `idProduct`, `idEmployee`, `Quantity`, `FinalPrice`, `Date`, `Sale`) VALUES
(1, 1, 1, 1, 10, '2022-05-30', NULL),
(2, 2, 1, 10, 1000, '2022-05-30', NULL),
(3, 1, 2, 10, 1000, '2022-06-01', NULL);

--
-- Klíče pro exportované tabulky
--

--
-- Klíče pro tabulku `employees`
--
ALTER TABLE `employees`
  ADD PRIMARY KEY (`id`);

--
-- Klíče pro tabulku `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`);

--
-- Klíče pro tabulku `sales`
--
ALTER TABLE `sales`
  ADD PRIMARY KEY (`id`),
  ADD KEY `Fk_Employess` (`idEmployee`),
  ADD KEY `Fk_Product` (`idProduct`);

--
-- AUTO_INCREMENT pro tabulky
--

--
-- AUTO_INCREMENT pro tabulku `employees`
--
ALTER TABLE `employees`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT pro tabulku `products`
--
ALTER TABLE `products`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT pro tabulku `sales`
--
ALTER TABLE `sales`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- Omezení pro exportované tabulky
--

--
-- Omezení pro tabulku `sales`
--
ALTER TABLE `sales`
  ADD CONSTRAINT `Fk_Employess` FOREIGN KEY (`idEmployee`) REFERENCES `employees` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `Fk_Product` FOREIGN KEY (`idProduct`) REFERENCES `products` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
