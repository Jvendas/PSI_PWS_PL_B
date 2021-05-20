-- phpMyAdmin SQL Dump
-- version 5.0.2
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Tempo de geração: 20-Maio-2021 às 22:00
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
-- Banco de dados: `bdfighttravelair`
--

-- --------------------------------------------------------

--
-- Estrutura da tabela `airplanes`
--

DROP TABLE IF EXISTS `airplanes`;
CREATE TABLE IF NOT EXISTS `airplanes` (
  `idaviao` int(11) NOT NULL AUTO_INCREMENT,
  `referencia` varchar(45) NOT NULL,
  `lotacaoTotal` int(11) NOT NULL,
  `tipoAviao` varchar(45) NOT NULL,
  PRIMARY KEY (`idaviao`),
  UNIQUE KEY `idaviao_UNIQUE` (`idaviao`),
  UNIQUE KEY `referencia_UNIQUE` (`referencia`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estrutura da tabela `airports`
--

DROP TABLE IF EXISTS `airports`;
CREATE TABLE IF NOT EXISTS `airports` (
  `IdAeroporto` int(11) NOT NULL,
  `Nome` varchar(100) NOT NULL,
  `Pais` varchar(100) NOT NULL,
  `Cidade` varchar(100) NOT NULL,
  PRIMARY KEY (`IdAeroporto`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estrutura da tabela `fights`
--

DROP TABLE IF EXISTS `fights`;
CREATE TABLE IF NOT EXISTS `fights` (
  `idvoo` int(11) NOT NULL AUTO_INCREMENT,
  `idaeroporto` int(11) NOT NULL,
  `preco` float NOT NULL,
  PRIMARY KEY (`idvoo`),
  UNIQUE KEY `idvoo_UNIQUE` (`idvoo`),
  UNIQUE KEY `idaeroporto_UNIQUE` (`idaeroporto`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estrutura da tabela `scales`
--

DROP TABLE IF EXISTS `scales`;
CREATE TABLE IF NOT EXISTS `scales` (
  `idescala` int(11) NOT NULL AUTO_INCREMENT,
  `idaeroportoOrigem` int(11) NOT NULL,
  `idaeroportoDestino` int(11) NOT NULL,
  `horarioOrigem` datetime NOT NULL,
  `horarioDestino` datetime NOT NULL,
  `distancia` int(11) NOT NULL,
  `idvoo` int(11) NOT NULL,
  PRIMARY KEY (`idescala`),
  UNIQUE KEY `idescala_UNIQUE` (`idescala`),
  UNIQUE KEY `idaeroportoOrigem_UNIQUE` (`idaeroportoOrigem`),
  UNIQUE KEY `idaeroportoDestino_UNIQUE` (`idaeroportoDestino`),
  UNIQUE KEY `idvoo_UNIQUE` (`idvoo`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

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
  `checkin` tinytext NOT NULL,
  PRIMARY KEY (`idpassagem`),
  UNIQUE KEY `idpassagem_UNIQUE` (`idpassagem`),
  UNIQUE KEY `idvooida_UNIQUE` (`idvooida`),
  UNIQUE KEY `idvoovolta_UNIQUE` (`idvoovolta`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

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
  UNIQUE KEY `username_UNIQUE` (`username`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Restrições para despejos de tabelas
--

--
-- Limitadores para a tabela `fights`
--
ALTER TABLE `fights`
  ADD CONSTRAINT `fights_ibfk_1` FOREIGN KEY (`idaeroporto`) REFERENCES `airports` (`IdAeroporto`);

--
-- Limitadores para a tabela `scales`
--
ALTER TABLE `scales`
  ADD CONSTRAINT `scales_ibfk_1` FOREIGN KEY (`idaeroportoDestino`) REFERENCES `airports` (`IdAeroporto`),
  ADD CONSTRAINT `scales_ibfk_2` FOREIGN KEY (`idaeroportoOrigem`) REFERENCES `airports` (`IdAeroporto`),
  ADD CONSTRAINT `scales_ibfk_3` FOREIGN KEY (`idescala`) REFERENCES `scales` (`idescala`),
  ADD CONSTRAINT `scales_ibfk_4` FOREIGN KEY (`idvoo`) REFERENCES `fights` (`idvoo`);

--
-- Limitadores para a tabela `tickets`
--
ALTER TABLE `tickets`
  ADD CONSTRAINT `tickets_ibfk_1` FOREIGN KEY (`idvooida`) REFERENCES `fights` (`idvoo`),
  ADD CONSTRAINT `tickets_ibfk_2` FOREIGN KEY (`idpassagem`) REFERENCES `tickets` (`idpassagem`),
  ADD CONSTRAINT `tickets_ibfk_3` FOREIGN KEY (`idvoovolta`) REFERENCES `fights` (`idvoo`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
