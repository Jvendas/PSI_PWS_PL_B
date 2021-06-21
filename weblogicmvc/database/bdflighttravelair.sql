-- phpMyAdmin SQL Dump
-- version 5.0.2
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Tempo de geração: 21-Jun-2021 às 19:24
-- Versão do servidor: 5.7.31
-- versão do PHP: 7.3.21

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Banco de dados: `bdflighttravelair`
--

-- --------------------------------------------------------

--
-- Estrutura da tabela `airplanes`
--

DROP TABLE IF EXISTS `airplanes`;
CREATE TABLE IF NOT EXISTS `airplanes` (
  `idaviao` int(11) NOT NULL AUTO_INCREMENT,
  `referencia` varchar(45) NOT NULL,
  `lotacaototal` int(11) NOT NULL,
  `tipoaviao` varchar(45) NOT NULL,
  PRIMARY KEY (`idaviao`),
  UNIQUE KEY `idaviao_UNIQUE` (`idaviao`),
  UNIQUE KEY `referencia_UNIQUE` (`referencia`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=latin1;

--
-- Extraindo dados da tabela `airplanes`
--

INSERT INTO `airplanes` (`idaviao`, `referencia`, `lotacaototal`, `tipoaviao`) VALUES
(3, 'A330', 250, 'Airbus A330'),
(4, 'A220', 100, 'Airbus A220-200'),
(5, 'A110', 123, 'Airbus A110-100'),
(6, 'A440', 230, 'Airbus A440-400'),
(7, 'A550', 550, 'Airbus A550-500');

-- --------------------------------------------------------

--
-- Estrutura da tabela `airports`
--

DROP TABLE IF EXISTS `airports`;
CREATE TABLE IF NOT EXISTS `airports` (
  `idaeroporto` int(11) NOT NULL AUTO_INCREMENT,
  `nome` varchar(100) NOT NULL,
  `pais` varchar(100) NOT NULL,
  `cidade` varchar(100) NOT NULL,
  PRIMARY KEY (`idaeroporto`)
) ENGINE=InnoDB AUTO_INCREMENT=15 DEFAULT CHARSET=latin1;

--
-- Extraindo dados da tabela `airports`
--

INSERT INTO `airports` (`idaeroporto`, `nome`, `pais`, `cidade`) VALUES
(6, 'LIS', 'Portugal', 'Lisboa'),
(7, 'OPO', 'Portugal', 'Porto'),
(8, 'PAR', 'FranÃ§a', 'Paris'),
(9, 'LUX', 'Luxemburgo', 'Luxemburgo'),
(10, 'DBX', 'Emirados Ãrabes Unidos', 'Dubai'),
(11, 'HGK', 'China', 'Hongkong'),
(12, 'NYC', 'USA', 'Nova Iorque'),
(13, 'SAO', 'Brasil', 'SÃ£o Paulo');

-- --------------------------------------------------------

--
-- Estrutura da tabela `flights`
--

DROP TABLE IF EXISTS `flights`;
CREATE TABLE IF NOT EXISTS `flights` (
  `idvoo` int(11) NOT NULL AUTO_INCREMENT,
  `descricao` varchar(50) DEFAULT NULL,
  `preco` float NOT NULL,
  PRIMARY KEY (`idvoo`),
  UNIQUE KEY `idvoo_UNIQUE` (`idvoo`)
) ENGINE=InnoDB AUTO_INCREMENT=36 DEFAULT CHARSET=latin1;

--
-- Extraindo dados da tabela `flights`
--

INSERT INTO `flights` (`idvoo`, `descricao`, `preco`) VALUES
(23, 'OPO > PAR', 245),
(25, 'OPO > LUX', 190),
(26, 'OPO > SAO', 1100),
(27, 'PAR > OPO', 123),
(28, 'LUX > OPO', 123),
(29, 'SAO > OPO', 2010),
(30, 'LIS > DUBAI > CHINA', 1000),
(31, 'LUX > DUBAI > USA', 369),
(32, 'PAR > OPO> SAO', 550),
(33, 'CHINA > DUBAI > LIS', 123),
(34, 'USA > DUBAI > LUX', 132),
(35, 'BRASIL >OPO > PAR', 123);

-- --------------------------------------------------------

--
-- Estrutura da tabela `scales`
--

DROP TABLE IF EXISTS `scales`;
CREATE TABLE IF NOT EXISTS `scales` (
  `idescala` int(11) NOT NULL AUTO_INCREMENT,
  `idaeroportoorigem` int(11) NOT NULL,
  `idaeroportodestino` int(11) NOT NULL,
  `horarioorigem` datetime NOT NULL,
  `horariodestino` datetime NOT NULL,
  `distancia` int(11) NOT NULL,
  `idvoo` int(11) NOT NULL,
  PRIMARY KEY (`idescala`),
  UNIQUE KEY `idescala_UNIQUE` (`idescala`),
  KEY `idvoo` (`idvoo`) USING BTREE,
  KEY `idaeroportoDestino` (`idaeroportodestino`) USING BTREE,
  KEY `idaeroportoOrigem` (`idaeroportoorigem`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=39 DEFAULT CHARSET=latin1;

--
-- Extraindo dados da tabela `scales`
--

INSERT INTO `scales` (`idescala`, `idaeroportoorigem`, `idaeroportodestino`, `horarioorigem`, `horariodestino`, `distancia`, `idvoo`) VALUES
(19, 7, 8, '2021-06-21 14:05:00', '2021-06-21 14:05:00', 3000, 23),
(20, 7, 9, '2021-06-21 15:00:00', '2021-06-21 15:00:00', 2010, 25),
(21, 7, 13, '2021-06-21 10:00:00', '2021-06-21 10:00:00', 5500, 26),
(23, 6, 10, '2021-06-21 14:30:00', '2021-06-21 14:30:00', 4545, 30),
(24, 10, 11, '2021-06-21 14:30:00', '2021-06-21 14:30:00', 344, 30),
(25, 9, 10, '2021-06-21 14:31:00', '2021-06-21 14:31:00', 66, 31),
(26, 10, 9, '2021-06-21 14:32:00', '2021-06-21 14:32:00', 63, 31),
(27, 8, 7, '2021-06-21 14:32:00', '2021-06-21 14:32:00', 622, 32),
(28, 7, 13, '2021-06-21 14:33:00', '2021-06-21 14:33:00', 633, 32),
(29, 11, 10, '2021-06-21 14:33:00', '2021-06-21 14:33:00', 253, 33),
(30, 10, 6, '2021-06-21 14:34:00', '2021-06-21 14:34:00', 5445, 33),
(33, 12, 10, '2021-06-21 14:45:00', '2021-06-21 14:45:00', 3, 34),
(34, 10, 9, '2021-06-21 14:45:00', '2021-06-21 14:45:00', 3, 34),
(35, 13, 7, '2021-06-21 14:46:00', '2021-06-21 14:46:00', 100, 35),
(36, 7, 8, '2021-06-21 14:46:00', '2021-06-21 14:46:00', 900, 35),
(37, 8, 7, '2021-06-21 15:06:00', '2021-06-21 15:06:00', 123, 27),
(38, 9, 7, '2021-06-21 15:06:00', '2021-06-21 15:06:00', 233, 28);

-- --------------------------------------------------------

--
-- Estrutura da tabela `scale_airplanes`
--

DROP TABLE IF EXISTS `scale_airplanes`;
CREATE TABLE IF NOT EXISTS `scale_airplanes` (
  `idescalaaviao` int(11) NOT NULL AUTO_INCREMENT,
  `idaviao` int(11) NOT NULL,
  `idescala` int(11) NOT NULL,
  `numpassageiros` int(11) NOT NULL,
  PRIMARY KEY (`idescalaaviao`),
  KEY `idescala` (`idescala`),
  KEY `idaviao` (`idaviao`)
) ENGINE=InnoDB AUTO_INCREMENT=23 DEFAULT CHARSET=latin1;

--
-- Extraindo dados da tabela `scale_airplanes`
--

INSERT INTO `scale_airplanes` (`idescalaaviao`, `idaviao`, `idescala`, `numpassageiros`) VALUES
(3, 3, 19, 0),
(4, 4, 20, 0),
(5, 7, 21, 0),
(7, 3, 23, 0),
(8, 3, 24, 0),
(9, 4, 25, 0),
(10, 6, 26, 0),
(11, 5, 27, 0),
(12, 6, 28, 0),
(13, 7, 29, 0),
(14, 6, 30, 0),
(17, 7, 33, 0),
(18, 7, 34, 0),
(19, 5, 35, 0),
(20, 4, 36, 0),
(21, 5, 37, 0),
(22, 3, 38, 0);

-- --------------------------------------------------------

--
-- Estrutura da tabela `tickets`
--

DROP TABLE IF EXISTS `tickets`;
CREATE TABLE IF NOT EXISTS `tickets` (
  `idpassagem` int(11) NOT NULL AUTO_INCREMENT,
  `idvooida` int(11) NOT NULL,
  `idvoovolta` int(11) NOT NULL,
  `datacompra` datetime NOT NULL,
  `preco` float NOT NULL,
  `checkin` int(1) NOT NULL,
  `idutilizador` int(11) NOT NULL,
  PRIMARY KEY (`idpassagem`),
  UNIQUE KEY `idpassagem_UNIQUE` (`idpassagem`),
  KEY `idvooida` (`idvooida`) USING BTREE,
  KEY `idvoovolta` (`idvoovolta`) USING BTREE,
  KEY `idutilizador` (`idutilizador`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=22 DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estrutura da tabela `users`
--

DROP TABLE IF EXISTS `users`;
CREATE TABLE IF NOT EXISTS `users` (
  `idutilizador` int(11) NOT NULL AUTO_INCREMENT,
  `nome` varchar(100) NOT NULL,
  `datanascimento` date NOT NULL,
  `email` varchar(100) NOT NULL,
  `telefone` int(11) NOT NULL,
  `username` varchar(45) NOT NULL,
  `password` varchar(45) NOT NULL,
  `perfil` enum('passageiro','administrador','gestor de voo','operador de checkin') NOT NULL,
  PRIMARY KEY (`idutilizador`),
  UNIQUE KEY `idutilizador_UNIQUE` (`idutilizador`),
  UNIQUE KEY `username_UNIQUE` (`username`),
  UNIQUE KEY `telefone_UNIQUE` (`telefone`) USING BTREE,
  UNIQUE KEY `email_UNIQUE` (`email`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=15 DEFAULT CHARSET=latin1;

--
-- Extraindo dados da tabela `users`
--

INSERT INTO `users` (`idutilizador`, `nome`, `datanascimento`, `email`, `telefone`, `username`, `password`, `perfil`) VALUES
(1, 'admin', '2021-06-19', 'admin@admin', 123456789, 'admin', '123', 'administrador'),
(10, 'passageiro', '2021-06-28', 'passageiro@mail', 123, 'passageiro', '123', 'passageiro'),
(11, 'gestor', '2021-01-01', 'gestor@gestor', 9876543, 'gestor', '123', 'gestor de voo'),
(12, 'checkin', '2021-01-01', 'checkin@mail', 12345679, 'checkin', '123', 'operador de checkin');

--
-- Restrições para despejos de tabelas
--

--
-- Limitadores para a tabela `scales`
--
ALTER TABLE `scales`
  ADD CONSTRAINT `scales_ibfk_4` FOREIGN KEY (`idvoo`) REFERENCES `flights` (`idvoo`),
  ADD CONSTRAINT `scales_ibfk_5` FOREIGN KEY (`idaeroportoorigem`) REFERENCES `airports` (`idaeroporto`),
  ADD CONSTRAINT `scales_ibfk_6` FOREIGN KEY (`idaeroportodestino`) REFERENCES `airports` (`idaeroporto`);

--
-- Limitadores para a tabela `scale_airplanes`
--
ALTER TABLE `scale_airplanes`
  ADD CONSTRAINT `scale_airplanes_ibfk_1` FOREIGN KEY (`idescala`) REFERENCES `scales` (`idescala`),
  ADD CONSTRAINT `scale_airplanes_ibfk_2` FOREIGN KEY (`idaviao`) REFERENCES `airplanes` (`idaviao`);

--
-- Limitadores para a tabela `tickets`
--
ALTER TABLE `tickets`
  ADD CONSTRAINT `tickets_ibfk_1` FOREIGN KEY (`idvooida`) REFERENCES `flights` (`idvoo`),
  ADD CONSTRAINT `tickets_ibfk_3` FOREIGN KEY (`idvoovolta`) REFERENCES `flights` (`idvoo`),
  ADD CONSTRAINT `tickets_ibfk_4` FOREIGN KEY (`idutilizador`) REFERENCES `users` (`idutilizador`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
