-- phpMyAdmin SQL Dump
-- version 4.9.7
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Jan 17, 2022 at 04:53 AM
-- Server version: 5.6.51
-- PHP Version: 7.2.34

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `tomatoai_tigellas`
--

-- --------------------------------------------------------

--
-- Table structure for table `cc_prodotti_categorie`
--

CREATE TABLE `cc_prodotti_categorie` (
  `id` int(11) NOT NULL,
  `anagrafica_id` int(11) NOT NULL DEFAULT '0',
  `tipo_categoria` tinyint(4) NOT NULL DEFAULT '0',
  `metodo_valorizzazione` tinyint(4) NOT NULL DEFAULT '0',
  `type` varchar(25) NOT NULL DEFAULT 'FOOD',
  `codice` varchar(50) NOT NULL,
  `descrizione` varchar(150) NOT NULL,
  `spazio_cm3` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `cc_prodotti_categorie`
--

INSERT INTO `cc_prodotti_categorie` (`id`, `anagrafica_id`, `tipo_categoria`, `metodo_valorizzazione`, `type`, `codice`, `descrizione`, `spazio_cm3`) VALUES
(9, 0, 0, 0, 'MATERIALE CONSUMO', 'PRODUZIONE', 'PRODUZIONE', '0.00'),
(10, 0, 0, 0, 'MATERIE PRIME', 'SALUMI', 'SALUMI', '0.00'),
(11, 0, 0, 0, 'ATTREZZATURE', 'CUCINA', 'CUCINA', '0.00'),
(12, 0, 0, 1, 'MATERIE PRIME', 'PESCE', 'PESCE', '0.00'),
(23, 0, 0, 1, 'MATERIE PRIME', 'CARNE', 'CARNE', '0.00'),
(24, 0, 0, 1, 'MATERIE PRIME', 'FRUTTAVERDURA', 'FRUTTA E VERDURA', '0.00'),
(25, 0, 0, 1, 'MATERIE PRIME', 'LATTICINI', 'LATTICINI E FORMAGGI', '0.00'),
(26, 0, 0, 0, 'MATERIE PRIME', 'SCATOLAME', 'SCATOLAME', '0.00'),
(27, 0, 0, 0, 'MATERIE PRIME', 'PASTAFARINE', 'PASTA E FARINE', '0.00'),
(28, 0, 0, 0, 'MATERIE PRIME', 'CONDIMENTISPEZIE', 'CONDIMENTI E SPEZIE', '0.00'),
(31, 0, 0, 0, 'MATERIALE CONSUMO', 'DISTRIBUZIONE', 'DISTRIBUZIONE', '0.00'),
(32, 0, 0, 0, 'ATTREZZATURE', 'SALA', 'SALA', '0.00'),
(34, 0, 0, 0, 'MATERIALE CONSUMO', 'PULIZIE', 'PULIZIE', '0.00'),
(35, 0, 0, 0, 'MATERIALE CONSUMO', 'VARIE', 'VARIE', '0.00'),
(36, 0, 0, 0, 'ATTREZZATURE', 'ALTRO', 'ALTRO', '0.00'),
(38, 0, 0, 0, 'FOOD', 'SEMILAVORATI', 'SEMILAVORATI', '0.00'),
(80, 0, 1, 0, 'BEVERAGE', 'Mescita bianchi', 'Mescita bianchi', '0.00'),
(82, 0, 1, 0, 'BEVERAGE', 'Caffetteria', 'Caffetteria', '0.00'),
(83, 0, 1, 0, 'FOOD', 'Antipasti', 'Antipasti', '0.00'),
(84, 0, 1, 0, 'BEVERAGE', 'Alcolici', 'Alcolici', '0.00'),
(85, 0, 1, 0, 'FOOD', 'Taglieri', 'Taglieri', '0.00'),
(86, 0, 1, 0, 'FOOD', 'Varianti +', 'Varianti +', '0.00'),
(87, 0, 1, 0, 'BEVERAGE', 'Acqua e bibite', 'Acqua e bibite', '0.00'),
(88, 0, 1, 0, 'FOOD', 'Plateform', 'Plateform', '0.00'),
(89, 0, 1, 0, 'FOOD', 'Riordini', 'Riordini', '0.00'),
(90, 0, 1, 0, 'BEVERAGE', 'Birre', 'Birre', '0.00'),
(91, 0, 1, 0, 'BEVERAGE', 'Mescita rossi', 'Mescita rossi', '0.00'),
(92, 0, 1, 0, 'BEVERAGE', 'Spumanti', 'Spumanti', '0.00'),
(93, 0, 1, 0, 'FOOD', 'Baby', 'Baby', '0.00'),
(94, 0, 1, 0, 'BEVERAGE', 'Vini bianchi', 'Vini bianchi', '0.00'),
(95, 0, 1, 0, 'FOOD', 'Slot e salse', 'Slot e salse', '0.00'),
(96, 0, 1, 0, 'FOOD', 'Dessert', 'Dessert', '0.00'),
(98, 0, 1, 0, 'BEVERAGE', 'Vini rossi', 'Vini rossi', '0.00'),
(99, 0, 1, 0, 'FOOD', 'Gnocchi Tigelle pz.', 'Gnocchi Tigelle pz.', '0.00'),
(100, 0, 1, 0, 'FOOD', 'Varianti -', 'Varianti -', '0.00'),
(101, 0, 1, 0, 'BEVERAGE', 'Mescita    rossi', 'Mescita    rossi', '0.00'),
(102, 0, 0, 0, 'MATERIALE CONSUMO', 'Delivery e asporto', 'DELIVERY E ASPORTO', '0.00'),
(103, 0, 0, 0, 'MATERIALE CONSUMO', 'CANCELLERIA', 'CANCELLERIA', '0.00');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `cc_prodotti_categorie`
--
ALTER TABLE `cc_prodotti_categorie`
  ADD PRIMARY KEY (`id`),
  ADD KEY `tipo_categoria` (`tipo_categoria`,`metodo_valorizzazione`,`type`),
  ADD KEY `type` (`type`,`codice`,`descrizione`),
  ADD KEY `type_2` (`type`,`codice`,`descrizione`),
  ADD KEY `type_3` (`type`,`codice`,`descrizione`),
  ADD KEY `tipo_categoria_2` (`tipo_categoria`,`metodo_valorizzazione`,`type`);
ALTER TABLE `cc_prodotti_categorie` ADD FULLTEXT KEY `codice` (`codice`,`descrizione`);
ALTER TABLE `cc_prodotti_categorie` ADD FULLTEXT KEY `codice_2` (`codice`,`descrizione`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `cc_prodotti_categorie`
--
ALTER TABLE `cc_prodotti_categorie`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=104;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
