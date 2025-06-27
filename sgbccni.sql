-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Tempo de geração: 24-Jun-2025 às 16:45
-- Versão do servidor: 10.4.32-MariaDB
-- versão do PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Banco de dados: `sgbccni`
--

-- --------------------------------------------------------

--
-- Estrutura da tabela `configuracoes`
--

CREATE TABLE `configuracoes` (
  `id` int(11) NOT NULL,
  `chave` varchar(100) NOT NULL,
  `valor` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estrutura da tabela `contatos`
--

CREATE TABLE `contatos` (
  `id` int(11) NOT NULL,
  `nome` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `mensagem` text NOT NULL,
  `ip` varchar(45) NOT NULL,
  `enviado_em` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estrutura da tabela `emprestimos`
--

CREATE TABLE `emprestimos` (
  `id` int(11) NOT NULL,
  `id_usuario` int(11) DEFAULT NULL,
  `id_livro` int(11) DEFAULT NULL,
  `data_emprestimo` date DEFAULT NULL,
  `data_devolucao` date DEFAULT NULL,
  `devolvido` tinyint(1) DEFAULT 0,
  `observacao` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estrutura da tabela `historico_visualizacoes`
--

CREATE TABLE `historico_visualizacoes` (
  `id` int(11) NOT NULL,
  `id_usuario` int(11) DEFAULT NULL,
  `id_livro` int(11) DEFAULT NULL,
  `visualizado_em` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estrutura da tabela `listas`
--

CREATE TABLE `listas` (
  `id` int(11) NOT NULL,
  `id_usuario` int(11) DEFAULT NULL,
  `id_livro` int(11) DEFAULT NULL,
  `tipo` enum('favorito','quero_ler','ja_li') DEFAULT NULL,
  `criado_em` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estrutura da tabela `livros`
--

CREATE TABLE `livros` (
  `id` int(11) NOT NULL,
  `qr_code` varchar(255) DEFAULT NULL,
  `codigo_barras` varchar(100) DEFAULT NULL,
  `numero_interno` varchar(50) DEFAULT NULL,
  `prateleira` varchar(50) DEFAULT NULL,
  `titulo` varchar(255) NOT NULL,
  `volume` varchar(20) DEFAULT NULL,
  `edicao` varchar(20) DEFAULT NULL,
  `ano` int(11) DEFAULT NULL,
  `isbn` varchar(20) DEFAULT NULL,
  `isbn10` varchar(20) DEFAULT NULL,
  `isbn13` varchar(20) DEFAULT NULL,
  `status` enum('disponivel','emprestado') DEFAULT 'disponivel',
  `tipo` enum('físico','digital') DEFAULT 'físico',
  `formato` enum('PDF','EPUB','Online') DEFAULT 'PDF',
  `tamanho` varchar(50) DEFAULT NULL,
  `idioma` varchar(50) DEFAULT NULL,
  `copias_disponiveis` int(11) DEFAULT 1,
  `exemplares` int(11) DEFAULT 1,
  `capa_local` varchar(255) DEFAULT NULL,
  `capa_url` text DEFAULT NULL,
  `link_digital` text DEFAULT NULL,
  `link_download` text DEFAULT NULL,
  `descricao` text DEFAULT NULL,
  `informacoes_adicionais` text DEFAULT NULL,
  `visualizacoes` int(11) DEFAULT 0,
  `criado_em` datetime DEFAULT current_timestamp(),
  `autor_id` int(11) DEFAULT NULL,
  `editora_id` int(11) DEFAULT NULL,
  `categoria_id` int(11) DEFAULT NULL,
  `codigo_interno` varchar(100) NOT NULL,
  `disponivel` tinyint(1) DEFAULT 1,
  `subtitulo` varchar(255) DEFAULT NULL,
  `autor` varchar(255) DEFAULT NULL,
  `editora` varchar(255) DEFAULT NULL,
  `categoria` varchar(255) DEFAULT NULL,
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Extraindo dados da tabela `livros`
--

INSERT INTO `livros` (`id`, `qr_code`, `codigo_barras`, `numero_interno`, `prateleira`, `titulo`, `volume`, `edicao`, `ano`, `isbn`, `isbn10`, `isbn13`, `status`, `tipo`, `formato`, `tamanho`, `idioma`, `copias_disponiveis`, `exemplares`, `capa_local`, `capa_url`, `link_digital`, `link_download`, `descricao`, `informacoes_adicionais`, `visualizacoes`, `criado_em`, `autor_id`, `editora_id`, `categoria_id`, `codigo_interno`, `disponivel`, `subtitulo`, `autor`, `editora`, `categoria`, `updated_at`) VALUES
(2, NULL, NULL, NULL, NULL, 'Bliss and Other Stories', '', '', NULL, '9781409901853', NULL, NULL, 'disponivel', 'físico', 'PDF', NULL, NULL, 1, 1, NULL, NULL, 'https://www.youtube.com/watch?v=aROGxJmUwS4&ab_channel=EXPANS%C3%83ODAMENTE', NULL, 'Kathleen Mansfield Murry (1888-1923) was a prominent New Zealand modernist writer of short fiction. She took on the pen-name Katherine Mansfield upon the publication of her first collection of short stories, In a German Pension, in 1911. She also contracted gonorrhoea around this time, an event that was to plague her with arthritic pain for the rest of her short life, as well as to make her view herself as a \'soiled\' woman. Her life and work were changed forever with the death of her brother, a soldier, during World War I. She was shocked and traumatised by the experience, so much so that her work began to take refuge in the nostalgic reminiscences of their childhood in New Zealand. Miss Brill, the bittersweet story of a fragile woman living an ephemeral life of observation and simple pleasures in Paris, established Mansfield as one of the preeminent writers of the Modernist period, upon its publication in 1920\'s. She followed with the equally praised collection, The Garden Party, published in 1922.', NULL, 0, '2025-06-12 13:52:04', 1, 2, 3, '123', 1, NULL, NULL, NULL, NULL, '2025-06-14 03:38:02'),
(3, NULL, NULL, NULL, NULL, 'Decadas de Tito Livio, príncipe de la historia romana', '', '', NULL, '9783368113469', NULL, NULL, 'disponivel', 'digital', 'EPUB', NULL, NULL, 1, 1, NULL, NULL, 'https://b.cidadenovainforma.com.br/?busca=&status=', NULL, 'Reimpresión del original, primera publicación en 1795.', NULL, 0, '2025-06-13 12:22:10', 1, 2, 3, '12342', 1, NULL, NULL, NULL, NULL, '2025-06-14 03:38:02'),
(5, NULL, NULL, NULL, NULL, 'Galileu e os negadores da ciência', '', '', NULL, '9786555872835', NULL, NULL, 'disponivel', 'físico', 'PDF', NULL, NULL, 1, 1, NULL, 'uploads/capas/capa_684cd007873875.74856668.jpg', '', NULL, 'Em Galileu e os negadores da ciência, Mario Livio, astrofísico e autor best-seller, baseia-se em seu próprio conhecimento científico para conjecturar como Galileu chegou às suas conclusões revolucionárias sobre o cosmo e as leis da natureza. A história de Galileu Galilei é um terrível antecedente do que vivemos hoje, por exemplo no que se refere à crise climática ou ao combate à Covid-19. A ciência, mais uma vez, é equivocadamente questionada e ignorada. Quatrocentos anos atrás, Galileu enfrentou o mesmo problema. Suas descobertas, baseadas em observação cuidadosa e experimentos engenhosos, contradiziam o senso comum e os ensinamentos da Igreja Católica à época. Como retaliação, em um ataque direto à liberdade de pensamento, seus livros foram proibidos pelas autoridades eclesiásticas. Um pensador livre, que seguia as evidências, Galileu foi uma das figuras de maior destaque da Revolução Científica. Acreditava que toda pessoa deveria aprender ciência, assim como literatura, e insistia em buscar o maior público possível para suas descobertas, publicando seus livros em italiano, em vez de latim. Galileu foi julgado por se recusar a renegar suas convicções científicas. Ficou para a história como um herói e uma inspiração para cientistas e para todos aqueles que respeitam a ciência — e que, como Mario Livio nos lembra, seguem ameaçados até hoje.', NULL, 0, '2025-06-13 22:27:35', NULL, NULL, NULL, '1234567', 1, NULL, NULL, NULL, NULL, '2025-06-14 03:38:02'),
(6, NULL, NULL, NULL, NULL, 'Bacon', '', '', NULL, '9783822821985', NULL, NULL, 'disponivel', 'físico', '', NULL, NULL, 1, 1, NULL, NULL, '', NULL, 'This introductory volume shows the best of Francis Bacon\'s work.', NULL, 0, '2025-06-14 00:00:49', NULL, NULL, NULL, 'LIV-682041', 1, NULL, NULL, NULL, NULL, '2025-06-14 03:38:02'),
(7, NULL, NULL, NULL, NULL, 'O Príncipe', '', '', NULL, '9788552100560', NULL, NULL, 'disponivel', 'físico', '', NULL, NULL, 1, 1, NULL, NULL, '', NULL, 'Nesta obra, que é um clássico sobre pensamento político, o grande escritor Maquiavel mostra como funciona a ciência política. Discorre sobre os diferentes tipos de Estado e ensina como um príncipe pode conquistar e manter o domínio sobre um Estado. Trata daquilo que é o seu objetivo principal: as virtudes que o governante deve adquirir e os vícios que deve evitar para manter-se no poder. Maquiavel mostra em O Príncipe que a moralidade e a ciência política são separadas. Ele aponta a contradição entre governar um Estado e, ao mesmo tempo, levar uma vida moral.', NULL, 0, '2025-06-14 00:17:34', NULL, NULL, NULL, 'LIV-745009', 1, NULL, NULL, NULL, NULL, '2025-06-14 03:38:02');

-- --------------------------------------------------------

--
-- Estrutura da tabela `livros_usuarios`
--

CREATE TABLE `livros_usuarios` (
  `id` int(11) NOT NULL,
  `usuario_id` int(11) NOT NULL,
  `livro_id` int(11) NOT NULL,
  `lido` tinyint(1) DEFAULT 0,
  `favorito` tinyint(1) DEFAULT 0,
  `data_leitura` date DEFAULT NULL,
  `observacao` text DEFAULT NULL,
  `atualizado_em` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `criado_em` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Extraindo dados da tabela `livros_usuarios`
--

INSERT INTO `livros_usuarios` (`id`, `usuario_id`, `livro_id`, `lido`, `favorito`, `data_leitura`, `observacao`, `atualizado_em`, `criado_em`) VALUES
(1, 29, 3, 1, 1, NULL, NULL, '2025-06-13 22:23:23', '2025-06-13 22:23:18');

-- --------------------------------------------------------

--
-- Estrutura da tabela `livro_tags`
--

CREATE TABLE `livro_tags` (
  `id_livro` int(11) NOT NULL,
  `id_tag` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estrutura da tabela `log_redefinicao_senha`
--

CREATE TABLE `log_redefinicao_senha` (
  `id` int(11) NOT NULL,
  `usuario_id` int(11) NOT NULL,
  `email` varchar(255) NOT NULL,
  `ip` varchar(50) DEFAULT NULL,
  `navegador` text DEFAULT NULL,
  `data_redefinicao` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estrutura da tabela `mensagens_contato`
--

CREATE TABLE `mensagens_contato` (
  `id` int(11) NOT NULL,
  `nome` varchar(100) NOT NULL,
  `email` varchar(150) NOT NULL,
  `mensagem` text NOT NULL,
  `data_envio` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Extraindo dados da tabela `mensagens_contato`
--

INSERT INTO `mensagens_contato` (`id`, `nome`, `email`, `mensagem`, `data_envio`) VALUES
(1, 'marcelo', 'mbsfoz@gmai.com', 'Olá Mundo!', '2025-06-03 11:48:08'),
(2, 'Marcelo Botura Souza', 'mbsfoz@gmail.com', 'Olá mundão, testando', '2025-06-03 11:51:27'),
(3, 'marcelo', 'marcelo@botura.com', 'marcelo botura', '2025-06-13 12:18:10');

-- --------------------------------------------------------

--
-- Estrutura da tabela `notificacoes`
--

CREATE TABLE `notificacoes` (
  `id` int(11) NOT NULL,
  `id_usuario` int(11) DEFAULT NULL,
  `mensagem` text DEFAULT NULL,
  `lida` tinyint(1) DEFAULT 0,
  `data` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estrutura da tabela `reservas`
--

CREATE TABLE `reservas` (
  `id` int(11) NOT NULL,
  `id_usuario` int(11) DEFAULT NULL,
  `id_livro` int(11) DEFAULT NULL,
  `data_reserva` datetime DEFAULT current_timestamp(),
  `status` enum('pendente','confirmada','cancelada') DEFAULT 'pendente'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estrutura da tabela `tags`
--

CREATE TABLE `tags` (
  `id` int(11) NOT NULL,
  `nome` varchar(255) NOT NULL,
  `tipo` enum('autor','categoria','editora','outro') NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp() COMMENT 'Data de criação',
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp() COMMENT 'Data de atualização'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci COMMENT='Tabela de Tags (Autores, Categorias, Editoras)';

--
-- Extraindo dados da tabela `tags`
--

INSERT INTO `tags` (`id`, `nome`, `tipo`, `created_at`, `updated_at`) VALUES
(1, '', 'autor', '2025-06-14 03:22:13', '2025-06-14 03:22:13'),
(2, '', 'editora', '2025-06-14 03:22:13', '2025-06-14 03:22:13'),
(3, '', 'categoria', '2025-06-14 03:22:13', '2025-06-14 03:22:13');

-- --------------------------------------------------------

--
-- Estrutura da tabela `tokens_recuperacao`
--

CREATE TABLE `tokens_recuperacao` (
  `id` int(11) NOT NULL,
  `usuario_id` int(11) NOT NULL,
  `token` varchar(64) NOT NULL,
  `expira_em` datetime NOT NULL,
  `criado_em` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estrutura da tabela `usuarios`
--

CREATE TABLE `usuarios` (
  `id` int(11) NOT NULL,
  `nome` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `senha` varchar(255) NOT NULL,
  `tipo` enum('admin','usuario') DEFAULT 'usuario',
  `imagem_perfil` varchar(255) DEFAULT NULL,
  `data_nascimento` date DEFAULT NULL,
  `genero` enum('Masculino','Feminino','Outro') DEFAULT NULL,
  `cep` varchar(9) DEFAULT NULL,
  `endereco` varchar(255) DEFAULT NULL,
  `cidade` varchar(100) DEFAULT NULL,
  `estado` varchar(100) DEFAULT NULL,
  `ativo` tinyint(1) DEFAULT 1,
  `criado_em` datetime DEFAULT current_timestamp(),
  `foto` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Extraindo dados da tabela `usuarios`
--

INSERT INTO `usuarios` (`id`, `nome`, `email`, `senha`, `tipo`, `imagem_perfil`, `data_nascimento`, `genero`, `cep`, `endereco`, `cidade`, `estado`, `ativo`, `criado_em`, `foto`) VALUES
(29, 'botura', 'marcelo@botura.com', '$2y$10$y4SkxWOAqIR2Zp3Nfb.WgOzXkNYKoj5SnZ7PrOflAGjT2kuM8fyMm', 'usuario', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, '2025-06-11 23:26:27', NULL),
(30, 'marcelo', 'mbsfoz@gmail.com', '$2y$10$amjwDKCg.5xH3rkke.ncAe8myQAx0I8mQaVjHrSMwBWUzk8XQu45i', 'admin', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, '2025-06-11 23:27:30', NULL),
(31, 'admin', 'admin@admin.com', '$2y$10$q.PlfcbQ8qs//IRyV4zp.OXhfQhquEM3REc/62Jrx2oOXmbuiY4Dq', 'admin', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, '2025-06-12 00:37:30', NULL),
(33, 'Marcelo Botura', 'mbsfoz@admin.com', '$2y$10$Q7sXmaCXzdMiF0IWlB6V6..0cfp4cFjKYfd4pzKl3OTqXUvOFDCbm', 'admin', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, '2025-06-12 09:51:27', NULL);

--
-- Índices para tabelas despejadas
--

--
-- Índices para tabela `configuracoes`
--
ALTER TABLE `configuracoes`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `chave` (`chave`);

--
-- Índices para tabela `contatos`
--
ALTER TABLE `contatos`
  ADD PRIMARY KEY (`id`);

--
-- Índices para tabela `emprestimos`
--
ALTER TABLE `emprestimos`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_usuario` (`id_usuario`),
  ADD KEY `id_livro` (`id_livro`);

--
-- Índices para tabela `historico_visualizacoes`
--
ALTER TABLE `historico_visualizacoes`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_usuario` (`id_usuario`),
  ADD KEY `id_livro` (`id_livro`);

--
-- Índices para tabela `listas`
--
ALTER TABLE `listas`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_usuario` (`id_usuario`),
  ADD KEY `id_livro` (`id_livro`);

--
-- Índices para tabela `livros`
--
ALTER TABLE `livros`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_autor` (`autor_id`),
  ADD KEY `fk_editora` (`editora_id`),
  ADD KEY `fk_categoria` (`categoria_id`);

--
-- Índices para tabela `livros_usuarios`
--
ALTER TABLE `livros_usuarios`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `usuario_id` (`usuario_id`,`livro_id`),
  ADD KEY `livro_id` (`livro_id`);

--
-- Índices para tabela `livro_tags`
--
ALTER TABLE `livro_tags`
  ADD PRIMARY KEY (`id_livro`,`id_tag`),
  ADD KEY `id_tag` (`id_tag`);

--
-- Índices para tabela `log_redefinicao_senha`
--
ALTER TABLE `log_redefinicao_senha`
  ADD PRIMARY KEY (`id`);

--
-- Índices para tabela `mensagens_contato`
--
ALTER TABLE `mensagens_contato`
  ADD PRIMARY KEY (`id`);

--
-- Índices para tabela `notificacoes`
--
ALTER TABLE `notificacoes`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_usuario` (`id_usuario`);

--
-- Índices para tabela `reservas`
--
ALTER TABLE `reservas`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_usuario` (`id_usuario`),
  ADD KEY `id_livro` (`id_livro`);

--
-- Índices para tabela `tags`
--
ALTER TABLE `tags`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `nome` (`nome`,`tipo`),
  ADD UNIQUE KEY `unq_nome_tipo` (`nome`,`tipo`);

--
-- Índices para tabela `tokens_recuperacao`
--
ALTER TABLE `tokens_recuperacao`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `token` (`token`),
  ADD KEY `usuario_id` (`usuario_id`);

--
-- Índices para tabela `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT de tabelas despejadas
--

--
-- AUTO_INCREMENT de tabela `configuracoes`
--
ALTER TABLE `configuracoes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `contatos`
--
ALTER TABLE `contatos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `emprestimos`
--
ALTER TABLE `emprestimos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `historico_visualizacoes`
--
ALTER TABLE `historico_visualizacoes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `listas`
--
ALTER TABLE `listas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `livros`
--
ALTER TABLE `livros`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT de tabela `livros_usuarios`
--
ALTER TABLE `livros_usuarios`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de tabela `log_redefinicao_senha`
--
ALTER TABLE `log_redefinicao_senha`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `mensagens_contato`
--
ALTER TABLE `mensagens_contato`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de tabela `notificacoes`
--
ALTER TABLE `notificacoes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `reservas`
--
ALTER TABLE `reservas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `tags`
--
ALTER TABLE `tags`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de tabela `tokens_recuperacao`
--
ALTER TABLE `tokens_recuperacao`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=34;

--
-- Restrições para despejos de tabelas
--

--
-- Limitadores para a tabela `emprestimos`
--
ALTER TABLE `emprestimos`
  ADD CONSTRAINT `emprestimos_ibfk_1` FOREIGN KEY (`id_usuario`) REFERENCES `usuarios` (`id`),
  ADD CONSTRAINT `emprestimos_ibfk_2` FOREIGN KEY (`id_livro`) REFERENCES `livros` (`id`);

--
-- Limitadores para a tabela `historico_visualizacoes`
--
ALTER TABLE `historico_visualizacoes`
  ADD CONSTRAINT `historico_visualizacoes_ibfk_1` FOREIGN KEY (`id_usuario`) REFERENCES `usuarios` (`id`),
  ADD CONSTRAINT `historico_visualizacoes_ibfk_2` FOREIGN KEY (`id_livro`) REFERENCES `livros` (`id`);

--
-- Limitadores para a tabela `listas`
--
ALTER TABLE `listas`
  ADD CONSTRAINT `listas_ibfk_1` FOREIGN KEY (`id_usuario`) REFERENCES `usuarios` (`id`),
  ADD CONSTRAINT `listas_ibfk_2` FOREIGN KEY (`id_livro`) REFERENCES `livros` (`id`);

--
-- Limitadores para a tabela `livros`
--
ALTER TABLE `livros`
  ADD CONSTRAINT `fk_autor` FOREIGN KEY (`autor_id`) REFERENCES `tags` (`id`),
  ADD CONSTRAINT `fk_categoria` FOREIGN KEY (`categoria_id`) REFERENCES `tags` (`id`),
  ADD CONSTRAINT `fk_editora` FOREIGN KEY (`editora_id`) REFERENCES `tags` (`id`);

--
-- Limitadores para a tabela `livros_usuarios`
--
ALTER TABLE `livros_usuarios`
  ADD CONSTRAINT `livros_usuarios_ibfk_1` FOREIGN KEY (`usuario_id`) REFERENCES `usuarios` (`id`),
  ADD CONSTRAINT `livros_usuarios_ibfk_2` FOREIGN KEY (`livro_id`) REFERENCES `livros` (`id`);

--
-- Limitadores para a tabela `livro_tags`
--
ALTER TABLE `livro_tags`
  ADD CONSTRAINT `livro_tags_ibfk_1` FOREIGN KEY (`id_livro`) REFERENCES `livros` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `livro_tags_ibfk_2` FOREIGN KEY (`id_tag`) REFERENCES `tags` (`id`) ON DELETE CASCADE;

--
-- Limitadores para a tabela `notificacoes`
--
ALTER TABLE `notificacoes`
  ADD CONSTRAINT `notificacoes_ibfk_1` FOREIGN KEY (`id_usuario`) REFERENCES `usuarios` (`id`);

--
-- Limitadores para a tabela `reservas`
--
ALTER TABLE `reservas`
  ADD CONSTRAINT `reservas_ibfk_1` FOREIGN KEY (`id_usuario`) REFERENCES `usuarios` (`id`),
  ADD CONSTRAINT `reservas_ibfk_2` FOREIGN KEY (`id_livro`) REFERENCES `livros` (`id`);

--
-- Limitadores para a tabela `tokens_recuperacao`
--
ALTER TABLE `tokens_recuperacao`
  ADD CONSTRAINT `tokens_recuperacao_ibfk_1` FOREIGN KEY (`usuario_id`) REFERENCES `usuarios` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
