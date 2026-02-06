-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Tempo de geração: 28/11/2025 às 04:42
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
-- Banco de dados: `portfolio_db`
--

-- --------------------------------------------------------

--
-- Estrutura para tabela `contatos`
--

CREATE TABLE `contatos` (
  `id` int(11) NOT NULL,
  `nome` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `assunto` varchar(200) NOT NULL,
  `mensagem` text NOT NULL,
  `data_envio` timestamp NOT NULL DEFAULT current_timestamp(),
  `ip_address` varchar(45) DEFAULT NULL,
  `status` enum('novo','lido','respondido') DEFAULT 'novo'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Despejando dados para a tabela `contatos`
--

INSERT INTO `contatos` (`id`, `nome`, `email`, `assunto`, `mensagem`, `data_envio`, `ip_address`, `status`) VALUES
(4, 'João Silva', 'joao.silva@email.com', 'Proposta de Trabalho', 'Olá, gostaria de conversar sobre uma oportunidade.', '2025-11-28 03:37:30', NULL, 'novo'),
(5, 'Maria Santos', 'maria.santos@email.com', 'Dúvida sobre Projeto', 'Vi seu portfolio e tenho uma dúvida sobre o projeto X.', '2025-11-28 03:37:30', NULL, 'novo'),
(6, 'Pedro Oliveira', 'pedro.oliveira@email.com', 'Parceria', 'Tenho interesse em uma possível parceria.', '2025-11-28 03:37:30', NULL, 'novo'),
(7, 'Ana Costa', 'ana.costa@email.com', 'Feedback', 'Parabéns pelo portfolio, muito profissional!', '2025-11-28 03:37:30', NULL, 'novo'),
(8, 'Carlos Souza', 'carlos.souza@email.com', 'Orçamento', 'Gostaria de solicitar um orçamento.', '2025-11-28 03:37:30', NULL, 'novo');

--
-- Índices para tabelas despejadas
--

--
-- Índices de tabela `contatos`
--
ALTER TABLE `contatos`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_email` (`email`),
  ADD KEY `idx_data` (`data_envio`);

--
-- AUTO_INCREMENT para tabelas despejadas
--

--
-- AUTO_INCREMENT de tabela `contatos`
--
ALTER TABLE `contatos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
