-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Tempo de geração: 11/08/2025 às 21:10
-- Versão do servidor: 10.4.32-MariaDB
-- Versão do PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Banco de dados: `casa_do_acai`
--

-- --------------------------------------------------------

--
-- Estrutura para tabela `acai`
--

CREATE TABLE `acai` (
  `id_acai` int(11) NOT NULL,
  `valor` int(11) DEFAULT NULL,
  `nome` varchar(30) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `adiciona`
--

CREATE TABLE `adiciona` (
  `id_acai` int(11) NOT NULL,
  `id_condimentos` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `cliente`
--

CREATE TABLE `cliente` (
  `id_cliente` int(11) NOT NULL,
  `endereco` varchar(255) NOT NULL,
  `cpf` varchar(11) NOT NULL,
  `telefone` varchar(15) NOT NULL,
  `nome` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `senha` varchar(25) NOT NULL,
  `is_admin` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `cliente`
--

INSERT INTO `cliente` (`id_cliente`, `endereco`, `cpf`, `telefone`, `nome`, `email`, `senha`, `is_admin`) VALUES
(1, '', '', '', '', '', '', 0),
(6, 'santo antonio 77', '4442232', '444123', 'tatiane', 'tatinha333@gmail.com', '333', 0),
(7, 'SANTO ANTONIO', '444', '545466', 'LEXI', 'lexi@gmail.com', '123', 0),
(8, 'Rua Taltaltal', '5543566', '3674788', 'Pedro Henrique', 'pedrohenrique@gmail.com', '1234', 0);

-- --------------------------------------------------------

--
-- Estrutura para tabela `condimentos`
--

CREATE TABLE `condimentos` (
  `id_condimentos` int(11) NOT NULL,
  `condimento` varchar(25) DEFAULT NULL,
  `valor` decimal(10,2) DEFAULT 0.00
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `itens_pedido`
--

CREATE TABLE `itens_pedido` (
  `id_pedido` int(11) NOT NULL,
  `id_acai` int(11) NOT NULL,
  `nome_item` varchar(255) NOT NULL,
  `quantidade` int(11) DEFAULT NULL,
  `tamanho` varchar(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `pedidos`
--

CREATE TABLE `pedidos` (
  `id_pedido` int(11) NOT NULL,
  `data_pedido` date DEFAULT NULL,
  `total` decimal(10,2) DEFAULT NULL,
  `forma_pagamento` varchar(50) DEFAULT NULL,
  `id_cliente` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `pedidos`
--

INSERT INTO `pedidos` (`id_pedido`, `data_pedido`, `total`, `forma_pagamento`, `id_cliente`) VALUES
(1, '2025-08-03', 36.00, NULL, 0);

--
-- Índices para tabelas despejadas
--

--
-- Índices de tabela `acai`
--
ALTER TABLE `acai`
  ADD PRIMARY KEY (`id_acai`);

--
-- Índices de tabela `adiciona`
--
ALTER TABLE `adiciona`
  ADD PRIMARY KEY (`id_acai`,`id_condimentos`),
  ADD KEY `id_condimentos` (`id_condimentos`);

--
-- Índices de tabela `cliente`
--
ALTER TABLE `cliente`
  ADD PRIMARY KEY (`id_cliente`),
  ADD UNIQUE KEY `email` (`email`),
  ADD UNIQUE KEY `cpf` (`cpf`,`telefone`);

--
-- Índices de tabela `condimentos`
--
ALTER TABLE `condimentos`
  ADD PRIMARY KEY (`id_condimentos`);

--
-- Índices de tabela `itens_pedido`
--
ALTER TABLE `itens_pedido`
  ADD PRIMARY KEY (`id_pedido`,`id_acai`),
  ADD KEY `id_acai` (`id_acai`);

--
-- Índices de tabela `pedidos`
--
ALTER TABLE `pedidos`
  ADD PRIMARY KEY (`id_pedido`),
  ADD KEY `id_cliente` (`id_cliente`);

--
-- AUTO_INCREMENT para tabelas despejadas
--

--
-- AUTO_INCREMENT de tabela `cliente`
--
ALTER TABLE `cliente`
  MODIFY `id_cliente` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT de tabela `pedidos`
--
ALTER TABLE `pedidos`
  MODIFY `id_pedido` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- Restrições para tabelas despejadas
--

--
-- Restrições para tabelas `adiciona`
--
ALTER TABLE `adiciona`
  ADD CONSTRAINT `adiciona_ibfk_1` FOREIGN KEY (`id_condimentos`) REFERENCES `condimentos` (`id_condimentos`),
  ADD CONSTRAINT `adiciona_ibfk_2` FOREIGN KEY (`id_acai`) REFERENCES `acai` (`id_acai`);

--
-- Restrições para tabelas `itens_pedido`
--
ALTER TABLE `itens_pedido`
  ADD CONSTRAINT `itens_pedido_ibfk_1` FOREIGN KEY (`id_pedido`) REFERENCES `pedidos` (`id_pedido`),
  ADD CONSTRAINT `itens_pedido_ibfk_2` FOREIGN KEY (`id_acai`) REFERENCES `acai` (`id_acai`);

--
-- Restrições para tabelas `pedidos`
--
ALTER TABLE `pedidos`
  ADD CONSTRAINT `pedidos_ibfk_1` FOREIGN KEY (`id_cliente`) REFERENCES `cliente` (`id_cliente`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
