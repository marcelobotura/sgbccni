-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Tempo de geração: 22-Jul-2025 às 08:06
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
-- Estrutura da tabela `comentarios`
--

CREATE TABLE `comentarios` (
  `id` int(11) NOT NULL,
  `usuario_id` int(11) NOT NULL,
  `livro_id` int(11) NOT NULL,
  `texto` text NOT NULL,
  `criado_em` datetime NOT NULL DEFAULT current_timestamp(),
  `aprovado` tinyint(1) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Extraindo dados da tabela `comentarios`
--

INSERT INTO `comentarios` (`id`, `usuario_id`, `livro_id`, `texto`, `criado_em`, `aprovado`) VALUES
(1, 29, 10, 'Gostei muito desse livro', '2025-07-19 03:03:52', 1),
(2, 29, 11, 'Olá mundo', '2025-07-19 03:06:55', 1),
(4, 29, 12, 'Olá mundo, estou de volta', '2025-07-19 03:31:48', 1),
(5, 41, 3, 'Esta faltando capa', '2025-07-19 03:38:08', 1),
(6, 41, 11, 'Sei lá ta foda', '2025-07-19 03:38:31', 1),
(7, 29, 12, 'oiee', '2025-07-19 19:40:59', 0);

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
  `origem_capa` enum('upload','url') DEFAULT NULL,
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
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `fonte` varchar(100) DEFAULT 'Manual'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Extraindo dados da tabela `livros`
--

INSERT INTO `livros` (`id`, `qr_code`, `codigo_barras`, `prateleira`, `titulo`, `volume`, `edicao`, `ano`, `isbn`, `isbn10`, `isbn13`, `status`, `tipo`, `formato`, `tamanho`, `idioma`, `copias_disponiveis`, `exemplares`, `capa_local`, `capa_url`, `origem_capa`, `link_digital`, `link_download`, `descricao`, `informacoes_adicionais`, `visualizacoes`, `criado_em`, `autor_id`, `editora_id`, `categoria_id`, `codigo_interno`, `disponivel`, `subtitulo`, `updated_at`, `fonte`) VALUES
(2, NULL, NULL, NULL, 'Bliss and Other Stories', '', '', NULL, '9781409901853', NULL, NULL, 'disponivel', 'físico', 'PDF', NULL, NULL, 1, 1, NULL, NULL, NULL, 'https://www.youtube.com/watch?v=aROGxJmUwS4&ab_channel=EXPANS%C3%83ODAMENTE', NULL, 'Kathleen Mansfield Murry (1888-1923) was a prominent New Zealand modernist writer of short fiction. She took on the pen-name Katherine Mansfield upon the publication of her first collection of short stories, In a German Pension, in 1911. She also contracted gonorrhoea around this time, an event that was to plague her with arthritic pain for the rest of her short life, as well as to make her view herself as a \'soiled\' woman. Her life and work were changed forever with the death of her brother, a soldier, during World War I. She was shocked and traumatised by the experience, so much so that her work began to take refuge in the nostalgic reminiscences of their childhood in New Zealand. Miss Brill, the bittersweet story of a fragile woman living an ephemeral life of observation and simple pleasures in Paris, established Mansfield as one of the preeminent writers of the Modernist period, upon its publication in 1920\'s. She followed with the equally praised collection, The Garden Party, published in 1922.', NULL, 0, '2025-06-12 13:52:04', 1, 2, 3, '123', 1, NULL, '2025-06-14 03:38:02', 'Manual'),
(3, NULL, '9783368113469', NULL, 'Decadas De Tito Livio, Principe De La Historia Romana', '1', '', 0, '9783368113469', '1018681817', NULL, 'disponivel', 'digital', 'PDF', NULL, 'Espanhol', 1, 1, 'uploads/capas/capa_687b2947d1e36.jpg', NULL, 'upload', 'https://b.cidadenovainforma.com.br/?busca=&status=', NULL, 'Reimpresión del original, primera publicación en 1795.', NULL, 0, '2025-06-13 12:22:10', 1, 2, 3, 'LIV-12342', 1, '', '2025-07-19 05:16:58', 'Manual'),
(5, NULL, NULL, NULL, 'Galileu e os negadores da ciência', '', '', NULL, '9786555872835', NULL, NULL, 'disponivel', 'físico', 'PDF', NULL, NULL, 1, 1, 'uploads/capas/capa_684cd007873875.74856668.jpg\r\n', 'uploads/capas/capa_684cd007873875.74856668.jpg', NULL, '', NULL, 'Em Galileu e os negadores da ciência, Mario Livio, astrofísico e autor best-seller, baseia-se em seu próprio conhecimento científico para conjecturar como Galileu chegou às suas conclusões revolucionárias sobre o cosmo e as leis da natureza. A história de Galileu Galilei é um terrível antecedente do que vivemos hoje, por exemplo no que se refere à crise climática ou ao combate à Covid-19. A ciência, mais uma vez, é equivocadamente questionada e ignorada. Quatrocentos anos atrás, Galileu enfrentou o mesmo problema. Suas descobertas, baseadas em observação cuidadosa e experimentos engenhosos, contradiziam o senso comum e os ensinamentos da Igreja Católica à época. Como retaliação, em um ataque direto à liberdade de pensamento, seus livros foram proibidos pelas autoridades eclesiásticas. Um pensador livre, que seguia as evidências, Galileu foi uma das figuras de maior destaque da Revolução Científica. Acreditava que toda pessoa deveria aprender ciência, assim como literatura, e insistia em buscar o maior público possível para suas descobertas, publicando seus livros em italiano, em vez de latim. Galileu foi julgado por se recusar a renegar suas convicções científicas. Ficou para a história como um herói e uma inspiração para cientistas e para todos aqueles que respeitam a ciência — e que, como Mario Livio nos lembra, seguem ameaçados até hoje.', NULL, 0, '2025-06-13 22:27:35', NULL, NULL, NULL, '1234567', 1, NULL, '2025-07-19 04:35:02', 'Manual'),
(6, NULL, NULL, NULL, 'Bacon', '', '', NULL, '9783822821985', NULL, NULL, 'disponivel', 'físico', '', NULL, NULL, 1, 1, NULL, NULL, NULL, '', NULL, 'This introductory volume shows the best of Francis Bacon\'s work.', NULL, 0, '2025-06-14 00:00:49', NULL, NULL, NULL, 'LIV-682041', 1, NULL, '2025-06-14 03:38:02', 'Manual'),
(7, NULL, NULL, NULL, 'O Príncipe', '', '', NULL, '9788552100560', NULL, NULL, 'disponivel', 'físico', '', NULL, NULL, 1, 1, NULL, NULL, NULL, '', NULL, 'Nesta obra, que é um clássico sobre pensamento político, o grande escritor Maquiavel mostra como funciona a ciência política. Discorre sobre os diferentes tipos de Estado e ensina como um príncipe pode conquistar e manter o domínio sobre um Estado. Trata daquilo que é o seu objetivo principal: as virtudes que o governante deve adquirir e os vícios que deve evitar para manter-se no poder. Maquiavel mostra em O Príncipe que a moralidade e a ciência política são separadas. Ele aponta a contradição entre governar um Estado e, ao mesmo tempo, levar uma vida moral.', NULL, 0, '2025-06-14 00:17:34', NULL, NULL, NULL, 'LIV-745009', 1, NULL, '2025-06-14 03:38:02', 'Manual'),
(8, NULL, '9788581303079', NULL, 'O Pequeno príncipe', '', '', 2020, '9788581303079', '8581303072', NULL, 'disponivel', 'físico', '', NULL, '', 1, 1, NULL, NULL, NULL, '', NULL, 'Um piloto cai com seu avião no deserto e ali encontra uma criança loura e frágil. Ela diz ter vindo de um pequeno planeta distante. E ali, na convivência com o piloto perdido, os dois repensam os seus valores e encontram o sentido da vida. Com essa história mágica, sensível, comovente, às vezes triste, e só aparentemente infantil, o escritor francês Antoine de Saint-Exupéry criou há 70 anos um dos maiores clássicos da literatura universal. Não há adulto que não se comova ao se lembrar de quando o leu quando criança. Trata-se da maior obra existencialista do século XX, segundo Martin Heidegger. Livro mais traduzido da história, depois do Alcorão e da Bíblia, ele agora chega ao Brasil em nova edição, completa, enriquecida com um caderno ilustrado sobre a obra e a curta e trágica vida do autor.', NULL, 0, '2025-07-17 08:57:14', 1, 6, 7, 'LIV-292724', 1, '', '2025-07-17 11:57:14', 'Google Books'),
(9, NULL, '9786586490619', NULL, 'O pequeno príncipe', '', '', 2022, '9786586490619', '6586490618', NULL, 'disponivel', 'físico', '', NULL, '', 1, 1, NULL, NULL, NULL, 'https://www.google.com.br/books/edition/O_pequeno_pr%C3%ADncipe/N1rgEAAAQBAJ?hl=pt-BR&gbpv=1&pg=PP1&printsec=frontcover', NULL, 'Um dos livros favoritos dos leitores brasileiros chega em nova edição, com reinterpretação das artes originais por Lu Cafaggi e tradução direta do francês. Depois de sofrer um acidente e se perder no deserto, um aviador vê um pequeno príncipe, fato que muda sua vida dali em diante. Em meio a privações e risco de vida, o inusitado encontro com o menino se mostra uma jornada de aprendizado para o adulto — assim como será para o leitor. Por meio de delírios e pérolas de sabedoria, o pequeno príncipe nos mostra como, por vezes, os adultos não entendem nada sozinhos — e como isso pode ser cansativo para as crianças. O aviador também percebe, após ouvir o menino, como os adultos podem se corromper quando cobertos por julgamentos e obrigações. E é esta história sobre pureza que comove leitores de várias gerações desde então. Publicado pela primeira vez em 1943, O pequeno príncipe cativa leitores de todas as idades. A edição da Antofágica une pela primeira vez essa consagrada história a ilustrações inéditas, assinadas por Lu Cafaggi. A publicação conta ainda com tradução de Heloisa Jahn e posfácio da professora doutora em Literatura Comparada pela Universidade de Chicago Rosana Kohl Bines. Adriel Bispo, da página Livros do Drii, faz a apresentação, e o educador parental e podcaster Thiago Queiroz, do Paizinho, Vírgula!, e o cantor e compositor Toquinho escrevem ensaios sensíveis e encantadores.', NULL, 0, '2025-07-17 09:29:01', 8, 9, 7, 'LIV-601909', 1, '', '2025-07-17 12:29:01', 'Google Books'),
(10, NULL, '9788466652339', NULL, 'Educar las emociones', '', '', 2013, '9788466652339', '8466652337', NULL, 'disponivel', 'digital', 'PDF', NULL, '', 1, 1, NULL, NULL, NULL, '', NULL, 'En esta obra, la especialista en neuropsiquiatría infantil, Amanda Céspedes, entrega las claves a padres, profesores y cualquier adulto que se relacione en forma permanente con niños, para contenerlos, guiarlos en su formación emocional y desarrollar toda', NULL, 0, '2025-07-17 10:24:15', NULL, 10, 11, 'LIV-674960', 1, '', '2025-07-17 13:24:15', 'Google Books'),
(11, NULL, '9786599036484', NULL, 'Machado de Assis e o cânone ocidental', '', '', 2019, '9786599036484', '6599036481', NULL, 'disponivel', 'digital', 'PDF', NULL, 'Português', 1, 1, NULL, 'http://books.google.com/books/content?id=Zi3rDwAAQBAJ&printsec=frontcover&img=1&zoom=1&edge=curl&source=gbs_api', 'url', '', NULL, '(1o lugar na categoria Crítica Literária do 59o prêmio Jabuti) A obra, dividida em três partes, observa os mecanismos de diálogo de Machado com o leitor e o crítico, analisa a ironia no tecido retórico da narrativa machadiana e, por fim, revê a relação do escritor carioca com a cultura italiana, aqui representada por exemplos na literatura, história, teatro e ópera. Realizando uma integração de vários processos teóricos e dialogando com a crítica nacional e estrangeira, os capítulos se entrecruzam e dialogam entre si, revisitando os temas principais que contribuíram para a centralidade da obra de Machado de Assis na literatura brasileira.', NULL, 0, '2025-07-19 01:58:05', 19, 20, 21, 'LIV-238633', 1, 'itinerários de leitura', '2025-07-19 04:58:05', 'Google Books'),
(12, NULL, '9781409901853', NULL, 'Bliss and Other Stories', '', '', 2008, '9781409901853', '1409901858', NULL, 'disponivel', 'digital', 'PDF', NULL, '', 1, 1, NULL, 'http://books.google.com/books/content?id=UDyGAQAACAAJ&printsec=frontcover&img=1&zoom=1&source=gbs_api', 'url', '', NULL, 'Kathleen Mansfield Murry (1888-1923) was a prominent New Zealand modernist writer of short fiction. She took on the pen-name Katherine Mansfield upon the publication of her first collection of short stories, In a German Pension, in 1911. She also contracted gonorrhoea around this time, an event that was to plague her with arthritic pain for the rest of her short life, as well as to make her view herself as a \'soiled\' woman. Her life and work were changed forever with the death of her brother, a soldier, during World War I. She was shocked and traumatised by the experience, so much so that her work began to take refuge in the nostalgic reminiscences of their childhood in New Zealand. Miss Brill, the bittersweet story of a fragile woman living an ephemeral life of observation and simple pleasures in Paris, established Mansfield as one of the preeminent writers of the Modernist period, upon its publication in 1920\'s. She followed with the equally praised collection, The Garden Party, published in 1922.', NULL, 0, '2025-07-19 02:05:58', 22, 10, 7, 'LIV-439273', 1, '', '2025-07-19 05:05:58', 'Google Books');

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
  `criado_em` datetime DEFAULT current_timestamp(),
  `status` varchar(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Extraindo dados da tabela `livros_usuarios`
--

INSERT INTO `livros_usuarios` (`id`, `usuario_id`, `livro_id`, `lido`, `favorito`, `data_leitura`, `observacao`, `atualizado_em`, `criado_em`, `status`) VALUES
(1, 29, 3, 1, 1, NULL, 'ola mundo, mues livro lidos', '2025-07-17 19:14:56', '2025-06-13 22:23:18', NULL),
(12, 29, 12, 1, 1, NULL, NULL, '2025-07-19 19:40:49', '2025-07-19 03:03:20', NULL);

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
-- Estrutura da tabela `log_atividade`
--

CREATE TABLE `log_atividade` (
  `id` int(11) NOT NULL,
  `usuario` varchar(255) NOT NULL,
  `acao` text NOT NULL,
  `data_atividade` datetime DEFAULT current_timestamp(),
  `ip` varchar(45) DEFAULT NULL,
  `navegador` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Extraindo dados da tabela `log_atividade`
--

INSERT INTO `log_atividade` (`id`, `usuario`, `acao`, `data_atividade`, `ip`, `navegador`) VALUES
(1, 'admin@cni.com.br', 'Atualizou os dados do livro ID 23', '2025-07-17 03:41:06', '192.168.0.101', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/115.0.0.0 Safari/537.36'),
(2, '30', 'Livro cadastrado: O Pequeno príncipe (ISBN: 9788581303079)', '2025-07-17 08:57:14', NULL, NULL),
(3, '30', 'Livro cadastrado: O pequeno príncipe (ISBN: 9786586490619)', '2025-07-17 09:29:01', NULL, NULL),
(4, '30', 'Livro cadastrado: Educar las emociones (ISBN: 9788466652339)', '2025-07-17 10:24:15', NULL, NULL),
(5, '30', 'Livro cadastrado: Machado de Assis e o cânone ocidental (ISBN: 9786599036484)', '2025-07-19 01:58:05', NULL, NULL),
(6, '30', 'Livro cadastrado: Bliss and Other Stories (ISBN: 9781409901853)', '2025-07-19 02:05:58', NULL, NULL),
(7, '30', 'Atualizou os dados do livro ID 3', '2025-07-19 02:12:39', NULL, NULL),
(8, '30', 'Atualizou os dados do livro ID 3', '2025-07-19 02:16:58', NULL, NULL);

-- --------------------------------------------------------

--
-- Estrutura da tabela `log_login`
--

CREATE TABLE `log_login` (
  `id` int(11) NOT NULL,
  `usuario_id` int(11) NOT NULL,
  `ip` varchar(45) NOT NULL,
  `navegador` text DEFAULT NULL,
  `data_login` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Extraindo dados da tabela `log_login`
--

INSERT INTO `log_login` (`id`, `usuario_id`, `ip`, `navegador`, `data_login`) VALUES
(1, 30, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/137.0.0.0 Safari/537.36', '2025-07-17 01:03:19'),
(2, 30, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/137.0.0.0 Safari/537.36', '2025-07-17 01:04:15'),
(3, 30, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/137.0.0.0 Safari/537.36', '2025-07-17 01:04:28'),
(4, 30, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/137.0.0.0 Safari/537.36', '2025-07-17 01:05:12'),
(5, 30, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/137.0.0.0 Safari/537.36', '2025-07-17 01:17:25'),
(6, 30, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/137.0.0.0 Safari/537.36', '2025-07-17 01:35:01'),
(7, 30, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/137.0.0.0 Safari/537.36', '2025-07-17 01:37:07'),
(8, 30, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/137.0.0.0 Safari/537.36', '2025-07-17 01:38:19'),
(11, 30, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/137.0.0.0 Safari/537.36', '2025-07-17 02:59:40'),
(13, 30, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/137.0.0.0 Safari/537.36', '2025-07-17 03:12:51'),
(14, 30, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/137.0.0.0 Safari/537.36', '2025-07-17 03:33:29'),
(15, 30, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/137.0.0.0 Safari/537.36', '2025-07-17 03:35:35'),
(16, 29, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/137.0.0.0 Safari/537.36', '2025-07-17 18:20:26'),
(17, 29, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/137.0.0.0 Safari/537.36', '2025-07-17 18:30:20'),
(18, 29, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/137.0.0.0 Safari/537.36', '2025-07-17 18:37:09'),
(19, 30, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/137.0.0.0 Safari/537.36', '2025-07-17 18:45:25'),
(20, 29, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/137.0.0.0 Safari/537.36', '2025-07-17 18:46:43'),
(21, 29, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/137.0.0.0 Safari/537.36', '2025-07-17 20:22:09'),
(22, 29, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/137.0.0.0 Safari/537.36', '2025-07-17 20:49:02'),
(23, 30, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/137.0.0.0 Safari/537.36', '2025-07-17 23:39:13'),
(26, 29, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/137.0.0.0 Safari/537.36', '2025-07-18 00:27:04'),
(27, 30, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/137.0.0.0 Safari/537.36', '2025-07-18 00:29:33'),
(29, 29, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/137.0.0.0 Safari/537.36', '2025-07-18 00:33:05'),
(30, 30, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/137.0.0.0 Safari/537.36', '2025-07-18 00:45:35'),
(31, 29, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/137.0.0.0 Safari/537.36', '2025-07-18 00:47:39'),
(32, 29, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/137.0.0.0 Safari/537.36', '2025-07-18 01:02:26'),
(37, 30, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/137.0.0.0 Safari/537.36', '2025-07-18 16:20:49'),
(39, 29, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/137.0.0.0 Safari/537.36', '2025-07-18 16:22:16'),
(40, 30, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/137.0.0.0 Safari/537.36', '2025-07-18 16:27:16'),
(41, 29, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/137.0.0.0 Safari/537.36', '2025-07-18 19:17:26'),
(42, 29, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/137.0.0.0 Safari/537.36', '2025-07-19 01:11:36'),
(43, 30, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/137.0.0.0 Safari/537.36', '2025-07-19 01:44:57'),
(44, 29, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/137.0.0.0 Safari/537.36', '2025-07-19 02:18:33'),
(45, 30, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/137.0.0.0 Safari/537.36', '2025-07-19 02:19:48'),
(46, 29, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/137.0.0.0 Safari/537.36', '2025-07-19 02:24:49'),
(47, 29, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/137.0.0.0 Safari/537.36', '2025-07-19 02:30:37'),
(48, 30, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/137.0.0.0 Safari/537.36', '2025-07-19 02:41:50'),
(49, 29, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/137.0.0.0 Safari/537.36', '2025-07-19 02:50:02'),
(50, 30, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/137.0.0.0 Safari/537.36', '2025-07-19 03:04:15'),
(51, 29, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/137.0.0.0 Safari/537.36', '2025-07-19 03:06:29'),
(52, 30, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/137.0.0.0 Safari/537.36', '2025-07-19 03:08:42'),
(53, 41, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/137.0.0.0 Safari/537.36', '2025-07-19 03:10:24'),
(54, 30, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/137.0.0.0 Safari/537.36', '2025-07-19 03:12:53'),
(55, 29, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36 Edg/138.0.0.0', '2025-07-19 03:31:15'),
(56, 41, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:140.0) Gecko/20100101 Firefox/140.0', '2025-07-19 03:37:32'),
(57, 30, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36', '2025-07-19 04:08:38'),
(58, 30, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36', '2025-07-19 04:09:27'),
(59, 30, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36', '2025-07-19 04:13:59'),
(60, 30, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36', '2025-07-19 04:16:13'),
(61, 30, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36', '2025-07-19 04:19:01'),
(62, 30, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36', '2025-07-19 04:32:04'),
(63, 30, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36', '2025-07-19 04:32:47'),
(64, 30, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36', '2025-07-19 04:33:02'),
(65, 30, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36', '2025-07-19 04:34:17'),
(66, 30, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36', '2025-07-19 17:52:55'),
(67, 41, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:140.0) Gecko/20100101 Firefox/140.0', '2025-07-19 18:01:11'),
(68, 41, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:140.0) Gecko/20100101 Firefox/140.0', '2025-07-19 18:04:42'),
(69, 30, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36', '2025-07-19 18:08:04'),
(70, 30, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36', '2025-07-19 18:12:34'),
(71, 29, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36', '2025-07-19 18:12:58'),
(72, 29, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36', '2025-07-19 18:13:11'),
(73, 29, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36', '2025-07-19 18:14:18'),
(74, 29, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36', '2025-07-19 18:15:08'),
(75, 29, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36', '2025-07-19 18:15:24'),
(76, 29, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36', '2025-07-19 18:21:01'),
(77, 29, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36', '2025-07-19 18:21:18'),
(78, 29, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36', '2025-07-19 18:21:44'),
(79, 29, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36', '2025-07-19 18:27:07'),
(80, 30, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36', '2025-07-19 18:48:49'),
(81, 29, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36', '2025-07-19 18:49:47'),
(82, 30, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36', '2025-07-19 20:13:05'),
(83, 29, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36 Edg/138.0.0.0', '2025-07-19 20:32:41'),
(84, 29, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36 Edg/138.0.0.0', '2025-07-19 20:32:48'),
(85, 30, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36', '2025-07-19 21:02:31'),
(86, 29, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36 Edg/138.0.0.0', '2025-07-19 21:03:08'),
(87, 29, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36', '2025-07-19 21:42:11'),
(88, 30, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36', '2025-07-19 21:52:45'),
(89, 30, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36 Edg/138.0.0.0', '2025-07-19 22:41:03'),
(90, 30, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36', '2025-07-19 23:02:01'),
(91, 29, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36', '2025-07-20 22:44:41'),
(92, 29, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36', '2025-07-20 22:47:18'),
(93, 29, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36', '2025-07-20 22:47:45'),
(94, 30, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/138.0.0.0 Safari/537.36 Edg/138.0.0.0', '2025-07-20 22:48:05');

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
  `token_utilizado` varchar(255) DEFAULT NULL,
  `tipo_usuario` enum('admin','usuario') DEFAULT 'usuario',
  `data_redefinicao` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estrutura da tabela `log_usuarios`
--

CREATE TABLE `log_usuarios` (
  `id` int(11) NOT NULL,
  `usuario_id` int(11) NOT NULL,
  `acao` varchar(100) DEFAULT NULL,
  `data` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estrutura da tabela `log_visualizacoes`
--

CREATE TABLE `log_visualizacoes` (
  `id` int(11) NOT NULL,
  `usuario_id` int(11) NOT NULL,
  `livro_id` int(11) NOT NULL,
  `data_visualizacao` datetime DEFAULT current_timestamp(),
  `ip` varchar(45) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Extraindo dados da tabela `log_visualizacoes`
--

INSERT INTO `log_visualizacoes` (`id`, `usuario_id`, `livro_id`, `data_visualizacao`, `ip`) VALUES
(1, 38, 8, '2025-07-18 00:25:57', '::1'),
(2, 38, 8, '2025-07-18 00:26:10', '::1'),
(3, 38, 8, '2025-07-18 00:26:10', '::1'),
(4, 38, 8, '2025-07-18 00:26:24', '::1'),
(5, 38, 10, '2025-07-18 15:14:42', '::1'),
(6, 29, 5, '2025-07-19 01:18:15', '::1'),
(7, 29, 10, '2025-07-19 01:24:18', '::1'),
(8, 29, 5, '2025-07-19 01:35:25', '::1'),
(9, 29, 3, '2025-07-19 02:40:23', '::1'),
(10, 29, 3, '2025-07-19 02:41:27', '::1'),
(11, 29, 11, '2025-07-19 02:50:10', '::1'),
(12, 29, 12, '2025-07-19 02:57:09', '::1'),
(13, 29, 12, '2025-07-19 02:58:19', '::1'),
(14, 29, 12, '2025-07-19 03:03:10', '::1'),
(15, 29, 12, '2025-07-19 03:03:20', '::1'),
(16, 29, 12, '2025-07-19 03:03:20', '::1'),
(17, 29, 12, '2025-07-19 03:03:23', '::1'),
(18, 29, 12, '2025-07-19 03:03:23', '::1'),
(19, 29, 12, '2025-07-19 03:03:28', '::1'),
(20, 29, 12, '2025-07-19 03:03:28', '::1'),
(21, 29, 12, '2025-07-19 03:03:31', '::1'),
(22, 29, 12, '2025-07-19 03:03:31', '::1'),
(23, 29, 10, '2025-07-19 03:03:37', '::1'),
(24, 29, 10, '2025-07-19 03:03:52', '::1'),
(25, 29, 10, '2025-07-19 03:03:53', '::1'),
(26, 29, 11, '2025-07-19 03:06:37', '::1'),
(27, 29, 11, '2025-07-19 03:06:55', '::1'),
(28, 29, 11, '2025-07-19 03:06:55', '::1'),
(29, 29, 11, '2025-07-19 03:07:00', '::1'),
(30, 29, 11, '2025-07-19 03:07:25', '::1'),
(31, 41, 11, '2025-07-19 03:10:32', '::1'),
(32, 41, 11, '2025-07-19 03:10:43', '::1'),
(33, 41, 11, '2025-07-19 03:10:43', '::1'),
(34, 29, 12, '2025-07-19 03:31:30', '::1'),
(35, 29, 12, '2025-07-19 03:31:48', '::1'),
(36, 29, 12, '2025-07-19 03:31:48', '::1'),
(37, 41, 5, '2025-07-19 03:37:45', '127.0.0.1'),
(38, 41, 3, '2025-07-19 03:37:54', '127.0.0.1'),
(39, 41, 3, '2025-07-19 03:38:08', '127.0.0.1'),
(40, 41, 3, '2025-07-19 03:38:08', '127.0.0.1'),
(41, 41, 11, '2025-07-19 03:38:20', '127.0.0.1'),
(42, 41, 11, '2025-07-19 03:38:31', '127.0.0.1'),
(43, 41, 11, '2025-07-19 03:38:31', '127.0.0.1'),
(44, 29, 12, '2025-07-19 03:39:19', '::1'),
(45, 29, 11, '2025-07-19 03:39:26', '::1'),
(46, 29, 8, '2025-07-19 19:39:29', '::1'),
(47, 29, 12, '2025-07-19 19:40:39', '::1'),
(48, 29, 12, '2025-07-19 19:40:49', '::1'),
(49, 29, 12, '2025-07-19 19:40:49', '::1'),
(50, 29, 12, '2025-07-19 19:40:52', '::1'),
(51, 29, 12, '2025-07-19 19:40:59', '::1'),
(52, 29, 12, '2025-07-19 19:41:00', '::1'),
(53, 29, 12, '2025-07-19 19:41:16', '::1'),
(54, 29, 12, '2025-07-19 20:12:49', '::1'),
(55, 29, 11, '2025-07-19 20:32:20', '::1'),
(56, 45, 11, '2025-07-19 22:52:55', '::1');

-- --------------------------------------------------------

--
-- Estrutura da tabela `mensagens`
--

CREATE TABLE `mensagens` (
  `id` int(11) NOT NULL,
  `nome` varchar(100) DEFAULT NULL,
  `email` varchar(150) DEFAULT NULL,
  `mensagem` text DEFAULT NULL,
  `data_envio` datetime DEFAULT current_timestamp(),
  `lida` tinyint(1) DEFAULT 0,
  `celular` varchar(20) DEFAULT NULL,
  `assunto` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Extraindo dados da tabela `mensagens`
--

INSERT INTO `mensagens` (`id`, `nome`, `email`, `mensagem`, `data_envio`, `lida`, `celular`, `assunto`) VALUES
(1, 'Marcelo', 'mbsfoz@gmail.com', 'Olá, me chama marcelo', '2025-07-18 18:25:15', 1, NULL, NULL),
(2, 'Marcelo', 'mbsfoz@gmail.com', 'olá, quero mudar minha senha', '2025-07-18 18:29:03', 1, NULL, NULL),
(3, 'Marcelo', 'mbsfoz@gmail.com', 'Quero doar livros para biblioteca', '2025-07-18 18:38:20', 0, NULL, NULL),
(5, 'Jessica', 'jessica@jessica.com', 'testando', '2025-07-18 21:46:08', 0, '4599909993555', 'testando');

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
  `tipo` enum('autor','categoria','editora','outro','volume','edicao','tipo','formato') NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp() COMMENT 'Data de criação',
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp() COMMENT 'Data de atualização'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci COMMENT='Tabela de Tags (Autores, Categorias, Editoras)';

--
-- Extraindo dados da tabela `tags`
--

INSERT INTO `tags` (`id`, `nome`, `tipo`, `created_at`, `updated_at`) VALUES
(1, 'Antoine de Saint-Exupéry', 'autor', '2025-06-14 03:22:13', '2025-07-17 06:36:50'),
(2, '', 'editora', '2025-06-14 03:22:13', '2025-06-14 03:22:13'),
(3, '', 'categoria', '2025-06-14 03:22:13', '2025-06-14 03:22:13'),
(4, 'Abril', 'editora', '2025-07-17 05:55:31', '2025-07-17 05:55:31'),
(5, 'Bottura', 'editora', '2025-07-17 05:57:39', '2025-07-17 05:57:50'),
(6, 'Geracao Editorial', 'editora', '2025-07-17 11:55:46', '2025-07-17 11:55:46'),
(7, 'Fiction', 'categoria', '2025-07-17 11:55:46', '2025-07-17 11:55:46'),
(8, 'Antoine Saint Exupery', 'autor', '2025-07-17 12:29:01', '2025-07-17 12:29:01'),
(9, 'Antofágica', 'editora', '2025-07-17 12:29:01', '2025-07-17 12:29:01'),
(10, '2', 'editora', '2025-07-17 13:24:15', '2025-07-17 13:24:15'),
(11, 'Child psychology', 'categoria', '2025-07-17 13:24:15', '2025-07-17 13:24:15'),
(19, 'Sonia Netto Salomão', 'autor', '2025-07-19 04:58:05', '2025-07-19 04:58:05'),
(20, 'SciELO - EDUERJ', 'editora', '2025-07-19 04:58:05', '2025-07-19 04:58:05'),
(21, 'Literary Criticism', 'categoria', '2025-07-19 04:58:05', '2025-07-19 04:58:05'),
(22, 'Katherine Mansfield', 'autor', '2025-07-19 05:05:58', '2025-07-19 05:05:58');

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
(29, 'Bottura', 'marcelo@marcelo.com', '$2y$10$y4SkxWOAqIR2Zp3Nfb.WgOzXkNYKoj5SnZ7PrOflAGjT2kuM8fyMm', 'usuario', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, '2025-06-11 23:26:27', 'perfil_29_1753164217.jpg'),
(30, 'Marcelo Botura', 'mbsfoz@gmail.com', '$2y$10$amjwDKCg.5xH3rkke.ncAe8myQAx0I8mQaVjHrSMwBWUzk8XQu45i', 'admin', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, '2025-06-11 23:27:30', NULL),
(41, 'Marcos', 'marcos@marcos.com', '$2y$10$IiHOZrR8hu14xGlOeKuG2udwpuRSQWiCjwntnyLUwxmtRBsJSUwo6', 'usuario', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, '2025-07-19 03:09:10', NULL),
(42, 'marcelo', 'marcelo@admin.com', '$2y$10$kYnqOrEkQB2cYQ84polqyerJjdgWcafB00WSPWNpMLWKv0YXPfyzm', 'admin', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, '2025-07-19 17:32:08', NULL),
(43, 'mmmmmmmmm', 'mmmmm@mmm.com', '$2y$10$uaRrhyW1VdNs5yjmcScT4uKF1iiGsbADipAi/Lj9hXx4YFCxSg9O6', 'usuario', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, '2025-07-19 22:34:39', NULL),
(44, 'pedrinho', 'pedrinho@pedrinho.com', '$2y$10$O/qJdCYTYgYOFnr7WKONFOxnHltRrjTuEJxIFudXvcHP8LRXMiUoO', 'usuario', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, '2025-07-19 22:41:36', NULL),
(45, 'emanoela', 'emanoela@emanoela.com', '$2y$10$EKWc9ccBYWViEvRq1LYhUuAxh98tmZJ2TF.sX6pinyQcy54Z4v6AC', 'usuario', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, '2025-07-19 22:42:29', NULL),
(46, 'Marcelo Souza', 'm_botura@gmail.com', '$2y$10$qMC.w9oz/LZlqUgs7yFUs.cDLeQNbOmd6usTyPz9CUzMj2KPAoC3y', 'usuario', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, '2025-07-21 01:44:02', NULL),
(47, '1111', 'aa@aa.com', '$2y$10$C0Nmj9PEs6gmyznvRryBpODYKUROl8pTaSyzJXBT5An.TNg79voOO', 'usuario', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, '2025-07-21 23:22:44', NULL),
(48, 'ola', 'ola@ola.com', '$2y$10$zE4AWIcEgqI.C6yIE.PG2O9JBYYyDOqw934pPaobXz0d6p7mxt8GG', 'usuario', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, '2025-07-21 23:24:40', NULL);

--
-- Índices para tabelas despejadas
--

--
-- Índices para tabela `comentarios`
--
ALTER TABLE `comentarios`
  ADD PRIMARY KEY (`id`),
  ADD KEY `usuario_id` (`usuario_id`),
  ADD KEY `livro_id` (`livro_id`);

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
-- Índices para tabela `log_atividade`
--
ALTER TABLE `log_atividade`
  ADD PRIMARY KEY (`id`);

--
-- Índices para tabela `log_login`
--
ALTER TABLE `log_login`
  ADD PRIMARY KEY (`id`),
  ADD KEY `usuario_id` (`usuario_id`);

--
-- Índices para tabela `log_redefinicao_senha`
--
ALTER TABLE `log_redefinicao_senha`
  ADD PRIMARY KEY (`id`);

--
-- Índices para tabela `log_usuarios`
--
ALTER TABLE `log_usuarios`
  ADD PRIMARY KEY (`id`);

--
-- Índices para tabela `log_visualizacoes`
--
ALTER TABLE `log_visualizacoes`
  ADD PRIMARY KEY (`id`);

--
-- Índices para tabela `mensagens`
--
ALTER TABLE `mensagens`
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
-- AUTO_INCREMENT de tabela `comentarios`
--
ALTER TABLE `comentarios`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

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
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT de tabela `livros_usuarios`
--
ALTER TABLE `livros_usuarios`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT de tabela `log_atividade`
--
ALTER TABLE `log_atividade`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT de tabela `log_login`
--
ALTER TABLE `log_login`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=95;

--
-- AUTO_INCREMENT de tabela `log_redefinicao_senha`
--
ALTER TABLE `log_redefinicao_senha`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `log_usuarios`
--
ALTER TABLE `log_usuarios`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `log_visualizacoes`
--
ALTER TABLE `log_visualizacoes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=57;

--
-- AUTO_INCREMENT de tabela `mensagens`
--
ALTER TABLE `mensagens`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

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
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- AUTO_INCREMENT de tabela `tokens_recuperacao`
--
ALTER TABLE `tokens_recuperacao`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=49;

--
-- Restrições para despejos de tabelas
--

--
-- Limitadores para a tabela `comentarios`
--
ALTER TABLE `comentarios`
  ADD CONSTRAINT `comentarios_ibfk_1` FOREIGN KEY (`usuario_id`) REFERENCES `usuarios` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `comentarios_ibfk_2` FOREIGN KEY (`livro_id`) REFERENCES `livros` (`id`) ON DELETE CASCADE;

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
  ADD CONSTRAINT `fk_livros_usuarios_livro` FOREIGN KEY (`livro_id`) REFERENCES `livros` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_livros_usuarios_usuario` FOREIGN KEY (`usuario_id`) REFERENCES `usuarios` (`id`) ON DELETE CASCADE;

--
-- Limitadores para a tabela `livro_tags`
--
ALTER TABLE `livro_tags`
  ADD CONSTRAINT `livro_tags_ibfk_1` FOREIGN KEY (`id_livro`) REFERENCES `livros` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `livro_tags_ibfk_2` FOREIGN KEY (`id_tag`) REFERENCES `tags` (`id`) ON DELETE CASCADE;

--
-- Limitadores para a tabela `log_login`
--
ALTER TABLE `log_login`
  ADD CONSTRAINT `log_login_ibfk_1` FOREIGN KEY (`usuario_id`) REFERENCES `usuarios` (`id`) ON DELETE CASCADE;

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
