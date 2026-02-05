-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Tempo de geração: 05/02/2026 às 22:18
-- Versão do servidor: 10.4.28-MariaDB
-- Versão do PHP: 8.0.28

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Banco de dados: `db_consulta`
--

-- --------------------------------------------------------

--
-- Estrutura para tabela `consultas`
--

CREATE TABLE `consultas` (
  `id` int(11) NOT NULL,
  `medico_id` int(11) NOT NULL,
  `paciente_id` int(11) NOT NULL,
  `data` date NOT NULL,
  `horario` time NOT NULL,
  `status` enum('Agendada','Confirmada','Check-in','Chamado','Em atendimento','Cancelada','Remarcada','Concluída','Finalizada') DEFAULT 'Agendada',
  `criado_em` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `consultas`
--

INSERT INTO `consultas` (`id`, `medico_id`, `paciente_id`, `data`, `horario`, `status`, `criado_em`, `updated_at`) VALUES
(1, 1, 2, '2026-01-31', '08:00:00', 'Em atendimento', '2026-01-10 19:50:41', '2026-01-31 13:54:47'),
(2, 2, 1, '2026-01-25', '14:00:00', 'Em atendimento', '2026-01-11 20:36:39', '2026-01-25 17:00:45');

-- --------------------------------------------------------

--
-- Estrutura para tabela `especialidades`
--

CREATE TABLE `especialidades` (
  `id` int(11) NOT NULL,
  `nome` varchar(100) DEFAULT NULL,
  `criado_em` date NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `especialidades`
--

INSERT INTO `especialidades` (`id`, `nome`, `criado_em`) VALUES
(1, 'Clínico Geral', '2026-01-01'),
(2, 'Clínica Ortopedista', '2026-01-01'),
(3, 'Clínico Urulogista', '2026-01-01'),
(4, 'Clínico Ginecologista', '2026-01-01');

-- --------------------------------------------------------

--
-- Estrutura para tabela `exames`
--

CREATE TABLE `exames` (
  `id` int(11) NOT NULL,
  `pedido_exame_id` int(11) NOT NULL,
  `paciente_id` int(11) NOT NULL,
  `tipo_exame` varchar(150) NOT NULL,
  `status` enum('Solicitado','Em Analise','Realizado','Cancelado') DEFAULT 'Solicitado',
  `resultado` text DEFAULT NULL,
  `data_solicitacao` datetime DEFAULT current_timestamp(),
  `data_realizacao` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `exames`
--

INSERT INTO `exames` (`id`, `pedido_exame_id`, `paciente_id`, `tipo_exame`, `status`, `resultado`, `data_solicitacao`, `data_realizacao`) VALUES
(1, 1, 2, 'Raio X do Crânio', 'Realizado', NULL, '2026-01-26 18:38:18', NULL),
(2, 1, 2, 'Sangue', 'Realizado', NULL, '2026-01-26 18:38:18', NULL);

-- --------------------------------------------------------

--
-- Estrutura para tabela `horarios_medico`
--

CREATE TABLE `horarios_medico` (
  `id` int(11) NOT NULL,
  `medico_id` int(11) NOT NULL,
  `dia_semana` tinyint(4) NOT NULL,
  `hora_inicio` time NOT NULL,
  `hora_fim` time NOT NULL,
  `intervalo` int(11) NOT NULL,
  `hora_inicio_refeicao` time DEFAULT NULL,
  `hora_fim_refeicao` time DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `horarios_medico`
--

INSERT INTO `horarios_medico` (`id`, `medico_id`, `dia_semana`, `hora_inicio`, `hora_fim`, `intervalo`, `hora_inicio_refeicao`, `hora_fim_refeicao`) VALUES
(2, 2, 1, '08:00:00', '17:00:00', 30, '11:00:00', '12:20:00');

-- --------------------------------------------------------

--
-- Estrutura para tabela `medicos`
--

CREATE TABLE `medicos` (
  `id` int(11) NOT NULL,
  `nome` varchar(100) NOT NULL,
  `crm` varchar(30) NOT NULL,
  `especialidade_id` int(11) NOT NULL,
  `telefone` varchar(20) DEFAULT NULL,
  `ativo` tinyint(4) DEFAULT 1,
  `criado_em` timestamp NOT NULL DEFAULT current_timestamp(),
  `email` varchar(100) DEFAULT NULL,
  `senha` varchar(255) DEFAULT NULL,
  `status` enum('Ativo','Inativo') DEFAULT 'Ativo'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `medicos`
--

INSERT INTO `medicos` (`id`, `nome`, `crm`, `especialidade_id`, `telefone`, `ativo`, `criado_em`, `email`, `senha`, `status`) VALUES
(1, 'Pedro Augusto da Silva', '2345', 1, '(11)94567-9900', 1, '2026-01-01 23:30:30', 'pedro.augusto@gmail.com', '$2y$10$qevMZgP12zN8fDBNgis2h.INPRa5ashqlT7Im2os.gA8NhNw534yS', 'Ativo'),
(2, 'Andréa Silva Andrade', '2344', 4, '((95560-9968', 1, '2026-01-02 23:23:55', 'andreia.silva@gmail.com', '$2y$10$qevMZgP12zN8fDBNgis2h.INPRa5ashqlT7Im2os.gA8NhNw534yS', 'Ativo'),
(3, 'Jorge Haroldo Paiva', '4567', 2, '(11)96709-2255', 1, '2026-01-02 23:40:56', 'jorge.haroldo@gmail.com', '$2y$10$qevMZgP12zN8fDBNgis2h.INPRa5ashqlT7Im2os.gA8NhNw534yS', 'Ativo');

-- --------------------------------------------------------

--
-- Estrutura para tabela `pacientes`
--

CREATE TABLE `pacientes` (
  `id` int(11) NOT NULL,
  `nome` varchar(150) NOT NULL,
  `cpf` varchar(14) NOT NULL,
  `data_nascimento` date DEFAULT NULL,
  `telefone` varchar(20) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `observacoes` text DEFAULT NULL,
  `status` enum('Ativo','Inativo') DEFAULT 'Ativo',
  `criado_em` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `pacientes`
--

INSERT INTO `pacientes` (`id`, `nome`, `cpf`, `data_nascimento`, `telefone`, `email`, `observacoes`, `status`, `criado_em`) VALUES
(1, 'Jackson de Oliveira Queiroz', '11906905860', '1972-12-04', '11940697068', 'jackson.oqueiroz@gmail.com', '', 'Ativo', '2026-01-03 20:51:37'),
(2, 'Elisangela M. Santos Queiroz', '11122233344', '1974-08-21', '11976596840', 'eli.santos.msq@gmail.com', 'Minha Linda', 'Ativo', '2026-01-05 22:58:19'),
(3, 'Vinicius Queiroz', '12345678901', NULL, '11222334455', 'vinicius.queiroz@gmail.com', NULL, 'Ativo', '2026-01-07 21:29:31'),
(4, 'Gabriel Queiroz', '12476834590', NULL, '11930678934', 'gabriel.queiroz@gmail.com', NULL, 'Ativo', '2026-01-07 22:54:14'),
(5, 'Pedro Cassimiro', '2334456789', NULL, '11944666778', 'pedro.piroca@gmail.com', NULL, 'Ativo', '2026-01-07 22:59:11');

-- --------------------------------------------------------

--
-- Estrutura para tabela `pedidos_exames`
--

CREATE TABLE `pedidos_exames` (
  `id` int(11) NOT NULL,
  `consulta_id` int(11) NOT NULL,
  `paciente_id` int(11) NOT NULL,
  `medico_id` int(11) NOT NULL,
  `observacoes` text DEFAULT NULL,
  `status_geral` enum('Aberto','Concluido','Cancelado') DEFAULT 'Aberto',
  `data_pedido` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `pedidos_exames`
--

INSERT INTO `pedidos_exames` (`id`, `consulta_id`, `paciente_id`, `medico_id`, `observacoes`, `status_geral`, `data_pedido`) VALUES
(1, 1, 2, 1, 'Exames para futuros diagnósticos.', 'Aberto', '2026-01-26 18:38:18'),
(2, 1, 2, 1, 'Endoscopia para diagnóstico', 'Aberto', '2026-01-31 14:26:43');

-- --------------------------------------------------------

--
-- Estrutura para tabela `prescricoes`
--

CREATE TABLE `prescricoes` (
  `id` int(11) NOT NULL,
  `consulta_id` int(11) NOT NULL,
  `medico_id` int(11) NOT NULL,
  `paciente_id` int(11) NOT NULL,
  `prescricao` text NOT NULL,
  `orientacoes` text DEFAULT NULL,
  `data_prescricao` date NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `prescricoes`
--

INSERT INTO `prescricoes` (`id`, `consulta_id`, `medico_id`, `paciente_id`, `prescricao`, `orientacoes`, `data_prescricao`, `created_at`) VALUES
(1, 2, 2, 1, 'Dorflex 25mg - tomar a cada 8 horas', 'Tomar durante 30 dias.', '2026-01-25', '2026-01-25 21:26:54'),
(2, 2, 2, 1, 'Dorflex 25mg - tomar a cada 8 horas', 'Tomar durante 30 dias.', '2026-01-25', '2026-01-25 21:29:36'),
(3, 1, 1, 2, 'Dipirona', 'Tomar de 6 em 6 horas', '2026-01-26', '2026-01-26 22:56:55'),
(4, 1, 1, 2, 'Dipirona 500 mg', 'Tomar a cada 6 horas', '2026-01-27', '2026-01-27 22:30:05'),
(5, 1, 1, 2, 'dipirona 500 mg', 'Tomar a cada 6 horas', '2026-01-31', '2026-01-31 16:55:36'),
(6, 1, 1, 2, 'dipirona 500 mg', 'Tomar a cada 6 horas', '2026-01-31', '2026-01-31 17:07:56'),
(7, 1, 1, 2, 'Dipirona 500mg', 'Tomar a cada 6 horas', '2026-01-31', '2026-01-31 17:18:42'),
(8, 1, 1, 2, 'Dipirona 500mg', 'Tomar a cada 6 horas', '2026-01-31', '2026-01-31 17:24:47');

-- --------------------------------------------------------

--
-- Estrutura para tabela `prontuarios`
--

CREATE TABLE `prontuarios` (
  `id` int(11) NOT NULL,
  `consulta_id` int(11) NOT NULL,
  `medico_id` int(11) NOT NULL,
  `paciente_id` int(11) NOT NULL,
  `data_atendimento` date NOT NULL,
  `queixa` text DEFAULT NULL,
  `anamnese` text DEFAULT NULL,
  `exame` text DEFAULT NULL,
  `diagnostico` text DEFAULT NULL,
  `conduta` text DEFAULT NULL,
  `observacoes` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `prontuarios`
--

INSERT INTO `prontuarios` (`id`, `consulta_id`, `medico_id`, `paciente_id`, `data_atendimento`, `queixa`, `anamnese`, `exame`, `diagnostico`, `conduta`, `observacoes`, `created_at`) VALUES
(1, 2, 1, 1, '2026-01-14', 'teste', 'teste', 'teste', 'teste', 'teste', 'teste', '2026-01-14 22:04:24'),
(2, 1, 1, 2, '2026-01-22', 'teste 2', 'teste 2', 'teste 2', 'teste 2', 'teste 2', 'teste 2', '2026-01-22 22:46:04'),
(4, 1, 1, 2, '2026-01-25', 'Dor de Cabeça', 'Cealeia', 'encefalograma', 'Aguardar o resultado do exame', 'Não especificado', 'Não especificado', '2026-01-25 18:16:29'),
(5, 2, 2, 1, '2026-01-25', 'Dores no Pé', 'Esporão', 'Raio X', 'Aguardar exames...', 'NE', 'NE', '2026-01-25 18:32:28');

-- --------------------------------------------------------

--
-- Estrutura para tabela `usuarios`
--

CREATE TABLE `usuarios` (
  `id` int(11) NOT NULL,
  `nome` varchar(220) NOT NULL,
  `usuario` varchar(220) NOT NULL,
  `senha` varchar(220) NOT NULL,
  `imagem` varchar(220) NOT NULL,
  `perfil` enum('Admin','Atendente') NOT NULL DEFAULT 'Atendente'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Despejando dados para a tabela `usuarios`
--

INSERT INTO `usuarios` (`id`, `nome`, `usuario`, `senha`, `imagem`, `perfil`) VALUES
(1, 'Jackson Queiroz', 'jackson.oqueiroz@gmail.com', '$2y$10$f.LrBA08y8t9.ZpVVJVSZOGYWwflwYVBFZ94m5o3DuW/FzAWE9Mle', 'semfoto.png', 'Admin'),
(2, 'Atendente 1', 'atendimento@gmail.com', '$2y$10$f1DjfrNXSupJWdVmYMpjQ.Bx4b6O5jpMoWIi2W9LMCTkxC7W33HgO', 'semfoto.png', 'Atendente');

--
-- Índices para tabelas despejadas
--

--
-- Índices de tabela `consultas`
--
ALTER TABLE `consultas`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `medico_id` (`medico_id`,`data`,`horario`),
  ADD KEY `paciente_id` (`paciente_id`);

--
-- Índices de tabela `especialidades`
--
ALTER TABLE `especialidades`
  ADD PRIMARY KEY (`id`);

--
-- Índices de tabela `exames`
--
ALTER TABLE `exames`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_exames_pedido` (`pedido_exame_id`);

--
-- Índices de tabela `horarios_medico`
--
ALTER TABLE `horarios_medico`
  ADD PRIMARY KEY (`id`),
  ADD KEY `medico_id` (`medico_id`);

--
-- Índices de tabela `medicos`
--
ALTER TABLE `medicos`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`),
  ADD KEY `especialidade_id` (`especialidade_id`);

--
-- Índices de tabela `pacientes`
--
ALTER TABLE `pacientes`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `cpf` (`cpf`);

--
-- Índices de tabela `pedidos_exames`
--
ALTER TABLE `pedidos_exames`
  ADD PRIMARY KEY (`id`);

--
-- Índices de tabela `prescricoes`
--
ALTER TABLE `prescricoes`
  ADD PRIMARY KEY (`id`);

--
-- Índices de tabela `prontuarios`
--
ALTER TABLE `prontuarios`
  ADD PRIMARY KEY (`id`),
  ADD KEY `consulta_id` (`consulta_id`);

--
-- Índices de tabela `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT para tabelas despejadas
--

--
-- AUTO_INCREMENT de tabela `consultas`
--
ALTER TABLE `consultas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de tabela `especialidades`
--
ALTER TABLE `especialidades`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de tabela `exames`
--
ALTER TABLE `exames`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT de tabela `horarios_medico`
--
ALTER TABLE `horarios_medico`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT de tabela `medicos`
--
ALTER TABLE `medicos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de tabela `pacientes`
--
ALTER TABLE `pacientes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT de tabela `pedidos_exames`
--
ALTER TABLE `pedidos_exames`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT de tabela `prescricoes`
--
ALTER TABLE `prescricoes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT de tabela `prontuarios`
--
ALTER TABLE `prontuarios`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT de tabela `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- Restrições para tabelas despejadas
--

--
-- Restrições para tabelas `consultas`
--
ALTER TABLE `consultas`
  ADD CONSTRAINT `consultas_ibfk_1` FOREIGN KEY (`medico_id`) REFERENCES `medicos` (`id`),
  ADD CONSTRAINT `consultas_ibfk_2` FOREIGN KEY (`paciente_id`) REFERENCES `pacientes` (`id`);

--
-- Restrições para tabelas `exames`
--
ALTER TABLE `exames`
  ADD CONSTRAINT `fk_exames_pedido` FOREIGN KEY (`pedido_exame_id`) REFERENCES `pedidos_exames` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Restrições para tabelas `horarios_medico`
--
ALTER TABLE `horarios_medico`
  ADD CONSTRAINT `horarios_medico_ibfk_1` FOREIGN KEY (`medico_id`) REFERENCES `medicos` (`id`);

--
-- Restrições para tabelas `medicos`
--
ALTER TABLE `medicos`
  ADD CONSTRAINT `medicos_ibfk_1` FOREIGN KEY (`especialidade_id`) REFERENCES `especialidades` (`id`);

--
-- Restrições para tabelas `prontuarios`
--
ALTER TABLE `prontuarios`
  ADD CONSTRAINT `prontuarios_ibfk_1` FOREIGN KEY (`consulta_id`) REFERENCES `consultas` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
