-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Tempo de geração: 24-Jul-2025 às 19:57
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
-- Estrutura da tabela `arquivos`
--

CREATE TABLE `arquivos` (
  `id` int(11) NOT NULL,
  `nome` varchar(255) NOT NULL,
  `categoria` varchar(100) NOT NULL,
  `caminho` varchar(255) NOT NULL,
  `extensao` varchar(10) NOT NULL,
  `tamanho` int(11) NOT NULL,
  `data_upload` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

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
  `aprovado` tinyint(1) DEFAULT 0,
  `atualizado_em` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Extraindo dados da tabela `comentarios`
--

INSERT INTO `comentarios` (`id`, `usuario_id`, `livro_id`, `texto`, `criado_em`, `aprovado`, `atualizado_em`) VALUES
(1, 29, 10, 'Gostei muito desse livro', '2025-07-19 03:03:52', 1, '2025-07-24 02:37:04'),
(2, 29, 11, 'Olá mundo', '2025-07-19 03:06:55', 1, '2025-07-24 02:37:04'),
(4, 29, 12, 'Olá mundo, estou de volta', '2025-07-19 03:31:48', 1, '2025-07-24 02:37:04'),
(5, 41, 3, 'Esta faltando capa', '2025-07-19 03:38:08', 1, '2025-07-24 02:37:04'),
(6, 41, 11, 'Sei lá ta foda', '2025-07-19 03:38:31', 1, '2025-07-24 02:37:04'),
(7, 29, 12, 'oiee kkkkkkkkkkkkkkkkk', '2025-07-19 19:40:59', 0, '2025-07-24 02:41:32'),
(9, 29, 2, 'Testando meu sistema de cometarios', '2025-07-24 02:47:53', 0, '2025-07-24 02:47:53'),
(10, 29, 14, 'teste', '2025-07-24 14:38:23', 0, '2025-07-24 14:38:23');

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
-- Estrutura da tabela `episodios`
--

CREATE TABLE `episodios` (
  `id` int(11) NOT NULL,
  `titulo` varchar(255) NOT NULL,
  `link` text NOT NULL,
  `capa` varchar(255) DEFAULT NULL,
  `criado_em` datetime DEFAULT current_timestamp()
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
  `fonte` varchar(100) DEFAULT 'Manual',
  `destaque` tinyint(1) DEFAULT 0,
  `autor` varchar(255) DEFAULT NULL,
  `editora` varchar(255) DEFAULT NULL,
  `paginas` int(11) DEFAULT NULL,
  `categoria` varchar(100) DEFAULT NULL,
  `origem_dados` varchar(50) DEFAULT 'importado'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Extraindo dados da tabela `livros`
--

INSERT INTO `livros` (`id`, `qr_code`, `codigo_barras`, `prateleira`, `titulo`, `volume`, `edicao`, `ano`, `isbn`, `isbn10`, `isbn13`, `status`, `tipo`, `formato`, `tamanho`, `idioma`, `copias_disponiveis`, `exemplares`, `capa_local`, `capa_url`, `origem_capa`, `link_digital`, `link_download`, `descricao`, `informacoes_adicionais`, `visualizacoes`, `criado_em`, `autor_id`, `editora_id`, `categoria_id`, `codigo_interno`, `disponivel`, `subtitulo`, `updated_at`, `fonte`, `destaque`, `autor`, `editora`, `paginas`, `categoria`, `origem_dados`) VALUES
(2, NULL, NULL, NULL, 'Bliss and Other Stories', '', '', NULL, '9781409901853', NULL, NULL, 'disponivel', 'físico', 'PDF', NULL, NULL, 1, 1, NULL, NULL, NULL, 'https://www.youtube.com/watch?v=aROGxJmUwS4&ab_channel=EXPANS%C3%83ODAMENTE', NULL, 'Kathleen Mansfield Murry (1888-1923) was a prominent New Zealand modernist writer of short fiction. She took on the pen-name Katherine Mansfield upon the publication of her first collection of short stories, In a German Pension, in 1911. She also contracted gonorrhoea around this time, an event that was to plague her with arthritic pain for the rest of her short life, as well as to make her view herself as a \'soiled\' woman. Her life and work were changed forever with the death of her brother, a soldier, during World War I. She was shocked and traumatised by the experience, so much so that her work began to take refuge in the nostalgic reminiscences of their childhood in New Zealand. Miss Brill, the bittersweet story of a fragile woman living an ephemeral life of observation and simple pleasures in Paris, established Mansfield as one of the preeminent writers of the Modernist period, upon its publication in 1920\'s. She followed with the equally praised collection, The Garden Party, published in 1922.', NULL, 0, '2025-06-12 13:52:04', 1, 2, 3, '123', 1, NULL, '2025-06-14 03:38:02', 'Manual', 0, NULL, NULL, NULL, NULL, 'importado'),
(3, NULL, '9783368113469', NULL, 'Decadas De Tito Livio, Principe De La Historia Romana', '1', '', 0, '9783368113469', '1018681817', NULL, 'disponivel', 'digital', 'PDF', NULL, 'Espanhol', 1, 1, 'uploads/capas/capa_687b2947d1e36.jpg', NULL, 'upload', 'https://b.cidadenovainforma.com.br/?busca=&status=', NULL, 'Reimpresión del original, primera publicación en 1795.', NULL, 0, '2025-06-13 12:22:10', 1, 2, 3, 'LIV-12342', 1, '', '2025-07-19 05:16:58', 'Manual', 0, NULL, NULL, NULL, NULL, 'importado'),
(5, NULL, NULL, NULL, 'Galileu e os negadores da ciência', '', '', NULL, '9786555872835', NULL, NULL, 'disponivel', 'físico', 'PDF', NULL, NULL, 1, 1, 'uploads/capas/capa_684cd007873875.74856668.jpg\r\n', 'uploads/capas/capa_684cd007873875.74856668.jpg', NULL, '', NULL, 'Em Galileu e os negadores da ciência, Mario Livio, astrofísico e autor best-seller, baseia-se em seu próprio conhecimento científico para conjecturar como Galileu chegou às suas conclusões revolucionárias sobre o cosmo e as leis da natureza. A história de Galileu Galilei é um terrível antecedente do que vivemos hoje, por exemplo no que se refere à crise climática ou ao combate à Covid-19. A ciência, mais uma vez, é equivocadamente questionada e ignorada. Quatrocentos anos atrás, Galileu enfrentou o mesmo problema. Suas descobertas, baseadas em observação cuidadosa e experimentos engenhosos, contradiziam o senso comum e os ensinamentos da Igreja Católica à época. Como retaliação, em um ataque direto à liberdade de pensamento, seus livros foram proibidos pelas autoridades eclesiásticas. Um pensador livre, que seguia as evidências, Galileu foi uma das figuras de maior destaque da Revolução Científica. Acreditava que toda pessoa deveria aprender ciência, assim como literatura, e insistia em buscar o maior público possível para suas descobertas, publicando seus livros em italiano, em vez de latim. Galileu foi julgado por se recusar a renegar suas convicções científicas. Ficou para a história como um herói e uma inspiração para cientistas e para todos aqueles que respeitam a ciência — e que, como Mario Livio nos lembra, seguem ameaçados até hoje.', NULL, 0, '2025-06-13 22:27:35', NULL, NULL, NULL, '1234567', 1, NULL, '2025-07-19 04:35:02', 'Manual', 0, NULL, NULL, NULL, NULL, 'importado'),
(6, NULL, NULL, NULL, 'Bacon', '', '', NULL, '9783822821985', NULL, NULL, 'disponivel', 'físico', '', NULL, NULL, 1, 1, NULL, NULL, NULL, '', NULL, 'This introductory volume shows the best of Francis Bacon\'s work.', NULL, 0, '2025-06-14 00:00:49', NULL, NULL, NULL, 'LIV-682041', 1, NULL, '2025-06-14 03:38:02', 'Manual', 0, NULL, NULL, NULL, NULL, 'importado'),
(7, NULL, NULL, NULL, 'O Príncipe', '', '', NULL, '9788552100560', NULL, NULL, 'disponivel', 'físico', '', NULL, NULL, 1, 1, NULL, NULL, NULL, '', NULL, 'Nesta obra, que é um clássico sobre pensamento político, o grande escritor Maquiavel mostra como funciona a ciência política. Discorre sobre os diferentes tipos de Estado e ensina como um príncipe pode conquistar e manter o domínio sobre um Estado. Trata daquilo que é o seu objetivo principal: as virtudes que o governante deve adquirir e os vícios que deve evitar para manter-se no poder. Maquiavel mostra em O Príncipe que a moralidade e a ciência política são separadas. Ele aponta a contradição entre governar um Estado e, ao mesmo tempo, levar uma vida moral.', NULL, 0, '2025-06-14 00:17:34', NULL, NULL, NULL, 'LIV-745009', 1, NULL, '2025-06-14 03:38:02', 'Manual', 0, NULL, NULL, NULL, NULL, 'importado'),
(8, NULL, '9788581303079', NULL, 'O Pequeno príncipe', '', '', 2020, '9788581303079', '8581303072', NULL, 'disponivel', 'físico', '', NULL, '', 1, 1, NULL, NULL, NULL, '', NULL, 'Um piloto cai com seu avião no deserto e ali encontra uma criança loura e frágil. Ela diz ter vindo de um pequeno planeta distante. E ali, na convivência com o piloto perdido, os dois repensam os seus valores e encontram o sentido da vida. Com essa história mágica, sensível, comovente, às vezes triste, e só aparentemente infantil, o escritor francês Antoine de Saint-Exupéry criou há 70 anos um dos maiores clássicos da literatura universal. Não há adulto que não se comova ao se lembrar de quando o leu quando criança. Trata-se da maior obra existencialista do século XX, segundo Martin Heidegger. Livro mais traduzido da história, depois do Alcorão e da Bíblia, ele agora chega ao Brasil em nova edição, completa, enriquecida com um caderno ilustrado sobre a obra e a curta e trágica vida do autor.', NULL, 0, '2025-07-17 08:57:14', 1, 6, 7, 'LIV-292724', 1, '', '2025-07-17 11:57:14', 'Google Books', 0, NULL, NULL, NULL, NULL, 'importado'),
(9, NULL, '9786586490619', NULL, 'O pequeno príncipe', '', '', 2022, '9786586490619', '6586490618', NULL, 'disponivel', 'físico', '', NULL, '', 1, 1, NULL, NULL, NULL, 'https://www.google.com.br/books/edition/O_pequeno_pr%C3%ADncipe/N1rgEAAAQBAJ?hl=pt-BR&gbpv=1&pg=PP1&printsec=frontcover', NULL, 'Um dos livros favoritos dos leitores brasileiros chega em nova edição, com reinterpretação das artes originais por Lu Cafaggi e tradução direta do francês. Depois de sofrer um acidente e se perder no deserto, um aviador vê um pequeno príncipe, fato que muda sua vida dali em diante. Em meio a privações e risco de vida, o inusitado encontro com o menino se mostra uma jornada de aprendizado para o adulto — assim como será para o leitor. Por meio de delírios e pérolas de sabedoria, o pequeno príncipe nos mostra como, por vezes, os adultos não entendem nada sozinhos — e como isso pode ser cansativo para as crianças. O aviador também percebe, após ouvir o menino, como os adultos podem se corromper quando cobertos por julgamentos e obrigações. E é esta história sobre pureza que comove leitores de várias gerações desde então. Publicado pela primeira vez em 1943, O pequeno príncipe cativa leitores de todas as idades. A edição da Antofágica une pela primeira vez essa consagrada história a ilustrações inéditas, assinadas por Lu Cafaggi. A publicação conta ainda com tradução de Heloisa Jahn e posfácio da professora doutora em Literatura Comparada pela Universidade de Chicago Rosana Kohl Bines. Adriel Bispo, da página Livros do Drii, faz a apresentação, e o educador parental e podcaster Thiago Queiroz, do Paizinho, Vírgula!, e o cantor e compositor Toquinho escrevem ensaios sensíveis e encantadores.', NULL, 0, '2025-07-17 09:29:01', 8, 9, 7, 'LIV-601909', 1, '', '2025-07-17 12:29:01', 'Google Books', 0, NULL, NULL, NULL, NULL, 'importado'),
(10, NULL, '9788466652339', NULL, 'Educar las emociones', '', '', 2013, '9788466652339', '8466652337', NULL, 'disponivel', 'digital', 'PDF', NULL, '', 1, 1, NULL, NULL, NULL, '', NULL, 'En esta obra, la especialista en neuropsiquiatría infantil, Amanda Céspedes, entrega las claves a padres, profesores y cualquier adulto que se relacione en forma permanente con niños, para contenerlos, guiarlos en su formación emocional y desarrollar toda', NULL, 0, '2025-07-17 10:24:15', NULL, 10, 11, 'LIV-674960', 1, '', '2025-07-17 13:24:15', 'Google Books', 0, NULL, NULL, NULL, NULL, 'importado'),
(11, NULL, '9786599036484', NULL, 'Machado de Assis e o cânone ocidental', '', '', 2019, '9786599036484', '6599036481', NULL, 'disponivel', 'digital', 'PDF', NULL, 'Português', 1, 1, NULL, 'http://books.google.com/books/content?id=Zi3rDwAAQBAJ&printsec=frontcover&img=1&zoom=1&edge=curl&source=gbs_api', 'url', '', NULL, '(1o lugar na categoria Crítica Literária do 59o prêmio Jabuti) A obra, dividida em três partes, observa os mecanismos de diálogo de Machado com o leitor e o crítico, analisa a ironia no tecido retórico da narrativa machadiana e, por fim, revê a relação do escritor carioca com a cultura italiana, aqui representada por exemplos na literatura, história, teatro e ópera. Realizando uma integração de vários processos teóricos e dialogando com a crítica nacional e estrangeira, os capítulos se entrecruzam e dialogam entre si, revisitando os temas principais que contribuíram para a centralidade da obra de Machado de Assis na literatura brasileira.', NULL, 0, '2025-07-19 01:58:05', 19, 20, 21, 'LIV-238633', 1, 'itinerários de leitura', '2025-07-19 04:58:05', 'Google Books', 0, NULL, NULL, NULL, NULL, 'importado'),
(12, NULL, '9781409901853', NULL, 'Bliss and Other Stories', '', '', 2008, '9781409901853', '1409901858', NULL, 'disponivel', 'digital', 'PDF', NULL, '', 1, 1, NULL, 'http://books.google.com/books/content?id=UDyGAQAACAAJ&printsec=frontcover&img=1&zoom=1&source=gbs_api', 'url', '', NULL, 'Kathleen Mansfield Murry (1888-1923) was a prominent New Zealand modernist writer of short fiction. She took on the pen-name Katherine Mansfield upon the publication of her first collection of short stories, In a German Pension, in 1911. She also contracted gonorrhoea around this time, an event that was to plague her with arthritic pain for the rest of her short life, as well as to make her view herself as a \'soiled\' woman. Her life and work were changed forever with the death of her brother, a soldier, during World War I. She was shocked and traumatised by the experience, so much so that her work began to take refuge in the nostalgic reminiscences of their childhood in New Zealand. Miss Brill, the bittersweet story of a fragile woman living an ephemeral life of observation and simple pleasures in Paris, established Mansfield as one of the preeminent writers of the Modernist period, upon its publication in 1920\'s. She followed with the equally praised collection, The Garden Party, published in 1922.', NULL, 0, '2025-07-19 02:05:58', 22, 10, 7, 'LIV-439273', 1, '', '2025-07-19 05:05:58', 'Google Books', 0, NULL, NULL, NULL, NULL, 'importado'),
(13, NULL, '9788562069505', NULL, 'Casa Velha', '', '', 2015, '9788562069505', '8562069507', NULL, 'disponivel', 'físico', 'PDF', NULL, 'pt-BR', 1, 1, NULL, 'http://books.google.com/books/content?id=kWWg09CkFsUC&printsec=frontcover&img=1&zoom=1&edge=curl&source=gbs_api', NULL, NULL, NULL, '\"Casa Velha\" é um dos últimos romances urbanos de Machado de Assis que, embora escrito em 1906, foi publicado somente em 1944. A narrativa é feita por um padre que precisa se instalar numa casa antiga, cujo patriarca foi um político importante. Novamente, Machado critica os costumes, a igreja, a aristocracia, as vaidades etc. É um livro que levanta muitas discussões.', NULL, 0, '2025-07-24 12:00:25', NULL, NULL, NULL, '', 1, '', '2025-07-24 15:00:25', 'Manual', 0, 'Machado de Assis', 'Simplíssimo', 113, 'Fiction', 'Google Books'),
(14, NULL, '9788574800936', NULL, 'O Otelo brasileiro de Machado de Assis', '', '', 2002, '9788574800936', '8574800937', NULL, 'disponivel', 'físico', 'PDF', NULL, 'pt-BR', 1, 1, NULL, 'http://books.google.com/books/content?id=Q6kETzYptRMC&printsec=frontcover&img=1&zoom=1&edge=curl&source=gbs_api', NULL, NULL, NULL, 'Por muito tempo, prevaleceu nas leituras críticas de Dom Casmurro o tom malicioso sobre a personalidade de Capitu. Helena Caldwell analisa a obra-prima de Machado de Assis afastando-se dessas interpretações machistas e revelando o nexo que o escritor estabelece com Otelo, de Shakespeare.', NULL, 0, '2025-07-24 12:00:25', NULL, NULL, NULL, '', 1, 'um estudo de Dom Casmurro', '2025-07-24 15:00:25', 'Manual', 0, 'Helen Caldwell', 'Atelie Editorial', 232, 'Literary Criticism', 'Google Books'),
(15, NULL, '9788581303079', NULL, 'O Pequeno príncipe', '', 'preview-100', 2020, '9788581303079', '8581303072', NULL, 'disponivel', 'físico', 'PDF', NULL, 'pt-BR', 1, 1, NULL, 'http://books.google.com/books/content?id=AS7U0AEACAAJ&printsec=frontcover&img=1&zoom=1&source=gbs_api', NULL, NULL, NULL, 'Um piloto cai com seu avião no deserto e ali encontra uma criança loura e frágil. Ela diz ter vindo de um pequeno planeta distante. [...] Trata-se da maior obra existencialista do século XX, segundo Martin Heidegger.', NULL, 0, '2025-07-24 12:00:25', NULL, NULL, NULL, '', 1, '', '2025-07-24 15:00:25', 'Manual', 0, 'Antoine de Saint-Exupéry', 'Geracao Editorial', 0, 'Fiction', 'Google Books'),
(16, NULL, '', NULL, 'Nicolas Copernico', '', '', 1999, '9789561315686', '', NULL, 'disponivel', 'físico', 'PDF', NULL, 'es', 1, 1, NULL, 'http://books.google.com/books/content?id=DtsMdwneUnEC&printsec=frontcover&img=1&zoom=1&edge=curl&source=gbs_api', NULL, NULL, NULL, '', NULL, 0, '2025-07-24 12:00:25', NULL, NULL, NULL, '', 1, '', '2025-07-24 15:00:25', 'Manual', 0, 'Sergio de Regules', 'Andres Bello', 128, '', 'Google Books'),
(17, NULL, '9789722354219', NULL, 'EDUCAR AS EMOÇÕES', '', '', 0, '9789722354219', '9722354213', NULL, 'disponivel', 'físico', 'PDF', NULL, 'pt-BR', 1, 1, NULL, 'http://books.google.com/books/content?id=34WkBwAAQBAJ&printsec=frontcover&img=1&zoom=1&edge=curl&source=gbs_api', NULL, NULL, NULL, '', NULL, 0, '2025-07-24 12:00:25', NULL, NULL, NULL, '', 1, '', '2025-07-24 15:00:25', 'Manual', 0, 'AMANDA CÉSPEDES', 'Editorial Presença', 171, '', 'Google Books'),
(18, NULL, '9788525037565', NULL, 'Sítio Do Picapau Amarelo – Caderno De Receitas 2', '', '', 0, '9788525037565', '8525037567', NULL, 'disponivel', 'físico', 'PDF', NULL, 'pt-BR', 1, 1, NULL, 'http://books.google.com/books/content?id=Vu7OuOolz04C&printsec=frontcover&img=1&zoom=1&edge=curl&source=gbs_api', NULL, NULL, NULL, 'Receitas que evitam a utilização de facas ou materiais que podem representar perigo para as crianças compõem o \"Caderno de Receitas do Sítio do Picapau Amarelo 2\".', NULL, 0, '2025-07-24 12:00:25', NULL, NULL, NULL, '', 1, '', '2025-07-24 15:00:25', 'Manual', 0, 'Editora Globo, Monteiro Lobato', 'Globo Livros', 84, '', 'Google Books'),
(19, NULL, '', NULL, 'A Arte da Guerra', '', '', 2008, '9788525409515', '', NULL, 'disponivel', 'físico', 'PDF', NULL, 'pt-BR', 1, 1, NULL, 'http://books.google.com/books/content?id=SR0E1faeJk8C&printsec=frontcover&img=1&zoom=1&edge=curl&source=gbs_api', NULL, NULL, NULL, 'Partindo da ideia de que um povo e uma cidade livres são um povo e uma cidade armados, Maquiavel concebeu um dos mais importantes tratados sobre estratégia militar.', NULL, 0, '2025-07-24 12:00:25', NULL, NULL, NULL, '', 1, '', '2025-07-24 15:00:25', 'Manual', 0, 'Maquiavel', 'L&PM Editores', 149, 'Political Science', 'Google Books'),
(20, NULL, '', NULL, 'O príncipe', '', '', 2013, '9788532645722', '', NULL, 'disponivel', 'físico', 'PDF', NULL, 'pt-BR', 1, 1, NULL, 'http://books.google.com/books/content?id=gd8bBAAAQBAJ&printsec=frontcover&img=1&zoom=1&edge=curl&source=gbs_api', NULL, NULL, NULL, 'O Príncipe foi dedicado ao governante de um Estado ameaçado. [...]', NULL, 0, '2025-07-24 12:00:25', NULL, NULL, NULL, '', 1, '', '2025-07-24 15:00:25', 'Manual', 0, 'Nicolau Maquiavel', 'Editora Vozes Limitada', 89, 'Philosophy', 'Google Books'),
(21, NULL, '9788540205413', NULL, 'Dom Casmurro', '', '', 2016, '9788540205413', '8540205416', NULL, 'disponivel', 'físico', 'PDF', NULL, 'en', 1, 1, NULL, 'http://books.google.com/books/content?id=I-fUDAAAQBAJ&printsec=frontcover&img=1&zoom=1&edge=curl&source=gbs_api', NULL, NULL, NULL, '', NULL, 0, '2025-07-24 12:00:25', NULL, NULL, NULL, '', 1, '', '2025-07-24 15:00:25', 'Manual', 0, 'Machado de Assis, Edições Câmara', 'Edições Câmara', 194, 'Fiction', 'Google Books'),
(22, NULL, '', NULL, 'O Príncipe', '', '', 2019, '9788552100560', '', NULL, 'disponivel', 'físico', 'PDF', NULL, 'pt-BR', 1, 1, NULL, 'http://books.google.com/books/content?id=cKebDwAAQBAJ&printsec=frontcover&img=1&zoom=1&edge=curl&source=gbs_api', NULL, NULL, NULL, 'Nesta obra, que é um clássico sobre pensamento político, o grande escritor Maquiavel mostra como funciona a ciência política.', NULL, 0, '2025-07-24 12:00:25', NULL, NULL, NULL, '', 1, '', '2025-07-24 15:00:25', 'Manual', 0, 'Nicolau Maquiavel', 'EDIPRO', 116, 'Philosophy', 'Google Books');

-- --------------------------------------------------------

--
-- Estrutura da tabela `livros_tags`
--

CREATE TABLE `livros_tags` (
  `id` int(11) NOT NULL,
  `livro_id` int(11) NOT NULL,
  `tag_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

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
  `status` enum('lido','favorito','em_progresso') DEFAULT 'lido',
  `tipo_emprestimo` varchar(50) DEFAULT 'normal',
  `data_emprestimo` datetime DEFAULT current_timestamp(),
  `data_renovacao` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Extraindo dados da tabela `livros_usuarios`
--

INSERT INTO `livros_usuarios` (`id`, `usuario_id`, `livro_id`, `lido`, `favorito`, `data_leitura`, `observacao`, `atualizado_em`, `criado_em`, `status`, `tipo_emprestimo`, `data_emprestimo`, `data_renovacao`) VALUES
(1, 29, 3, 1, 1, NULL, 'ola mundo, mues livro lidos', '2025-07-17 19:14:56', '2025-06-13 22:23:18', NULL, 'normal', '2025-07-24 02:15:50', NULL),
(12, 29, 12, 1, 1, NULL, NULL, '2025-07-19 19:40:49', '2025-07-19 03:03:20', NULL, 'normal', '2025-07-24 02:15:50', NULL),
(16, 29, 2, 1, 0, NULL, NULL, '2025-07-24 01:09:29', '2025-07-24 01:09:29', 'lido', 'normal', '2025-07-24 02:15:50', NULL),
(19, 29, 14, 0, 1, NULL, NULL, '2025-07-24 14:38:03', '2025-07-24 14:38:03', 'lido', 'normal', '2025-07-24 14:38:03', NULL);

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
(56, 45, 11, '2025-07-19 22:52:55', '::1'),
(57, 29, 2, '2025-07-24 01:06:07', '::1'),
(58, 29, 2, '2025-07-24 01:09:25', '::1'),
(59, 29, 2, '2025-07-24 01:09:29', '::1'),
(60, 29, 2, '2025-07-24 01:09:29', '::1'),
(61, 29, 8, '2025-07-24 01:10:05', '::1'),
(62, 29, 12, '2025-07-24 01:18:29', '::1'),
(63, 29, 12, '2025-07-24 01:21:59', '::1'),
(64, 29, 12, '2025-07-24 01:21:59', '::1'),
(65, 29, 3, '2025-07-24 02:02:42', '::1'),
(66, 29, 2, '2025-07-24 02:07:05', '::1'),
(67, 29, 12, '2025-07-24 02:23:17', '::1'),
(68, 29, 11, '2025-07-24 02:29:56', '::1'),
(69, 29, 11, '2025-07-24 02:30:07', '::1'),
(70, 29, 11, '2025-07-24 02:30:07', '::1'),
(71, 29, 11, '2025-07-24 02:32:37', '::1'),
(72, 29, 12, '2025-07-24 02:41:10', '::1'),
(73, 29, 12, '2025-07-24 02:41:16', '::1'),
(74, 29, 12, '2025-07-24 02:41:35', '::1'),
(75, 29, 12, '2025-07-24 02:45:02', '::1'),
(76, 29, 12, '2025-07-24 02:47:10', '::1'),
(77, 29, 12, '2025-07-24 02:47:10', '::1'),
(78, 29, 12, '2025-07-24 02:47:14', '::1'),
(79, 29, 2, '2025-07-24 02:47:37', '::1'),
(80, 29, 2, '2025-07-24 02:47:53', '::1'),
(81, 29, 2, '2025-07-24 02:47:53', '::1'),
(82, 29, 2, '2025-07-24 02:48:35', '::1'),
(83, 29, 14, '2025-07-24 14:38:02', '::1'),
(84, 29, 14, '2025-07-24 14:38:03', '::1'),
(85, 29, 14, '2025-07-24 14:38:03', '::1'),
(86, 29, 14, '2025-07-24 14:38:23', '::1'),
(87, 29, 14, '2025-07-24 14:38:23', '::1'),
(88, 29, 14, '2025-07-24 14:38:25', '::1');

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
-- Estrutura da tabela `midias`
--

CREATE TABLE `midias` (
  `id` int(11) NOT NULL,
  `tipo` enum('podcast','video','audio','outro') NOT NULL,
  `titulo` varchar(255) NOT NULL,
  `descricao` text DEFAULT NULL,
  `autor` varchar(255) DEFAULT NULL,
  `url` text NOT NULL,
  `plataforma` varchar(100) DEFAULT NULL,
  `data_publicacao` date DEFAULT NULL,
  `duracao` varchar(20) DEFAULT NULL,
  `capa_url` text DEFAULT NULL,
  `criado_em` datetime DEFAULT current_timestamp(),
  `capa_local` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Extraindo dados da tabela `midias`
--

INSERT INTO `midias` (`id`, `tipo`, `titulo`, `descricao`, `autor`, `url`, `plataforma`, `data_publicacao`, `duracao`, `capa_url`, `criado_em`, `capa_local`) VALUES
(1, 'video', 'Como funciona a IA da OpenAI (GPT)?', 'Explicação didática sobre como funciona a IA por trás do ChatGPT.', NULL, 'https://www.youtube.com/watch?v=Te5rOTcE5ig', 'YouTube', '2023-09-10', '9min 14s', 'https://img.youtube.com/vi/Te5rOTcE5ig/hqdefault.jpg', '2025-07-24 12:17:03', NULL),
(2, 'video', 'Os Segredos do Universo – BBC Earth', 'Documentário sobre os grandes mistérios do universo.', NULL, 'https://www.youtube.com/watch?v=rEJzOhC5ZtI', 'YouTube', '2022-05-15', '48min 21s', 'https://img.youtube.com/vi/rEJzOhC5ZtI/hqdefault.jpg', '2025-07-24 12:17:03', NULL),
(3, 'video', 'Resumo de Dom Casmurro (Machado de Assis)', 'Resumo da obra clássica com interpretação e contexto.', NULL, 'https://www.youtube.com/watch?v=fmVLG_P7ZUY', 'YouTube', '2021-08-22', '10min 11s', 'https://img.youtube.com/vi/fmVLG_P7ZUY/hqdefault.jpg', '2025-07-24 12:17:03', NULL),
(4, 'video', 'História do Dinheiro – Nerdologia', 'Episódio do Nerdologia explicando a origem do dinheiro.', NULL, 'https://www.youtube.com/watch?v=yIVrdy0zUgg', 'YouTube', '2020-11-30', '11min 40s', 'https://img.youtube.com/vi/yIVrdy0zUgg/hqdefault.jpg', '2025-07-24 12:17:03', NULL),
(5, 'video', 'Como editar vídeos no celular – InShot Tutorial', 'Tutorial completo sobre como usar o app InShot.', NULL, 'https://www.youtube.com/watch?v=MWG7_Q3XYgA', 'YouTube', '2024-03-05', '8min 22s', 'https://img.youtube.com/vi/MWG7_Q3XYgA/hqdefault.jpg', '2025-07-24 12:17:03', NULL),
(6, 'podcast', 'Durma com essa – A polarização no Brasil', 'Reflexão sobre o cenário político e polarização brasileira.', NULL, 'https://open.spotify.com/episode/3n6QU2KkOd0GL9RHYyArVb', 'Spotify', '2023-06-01', '17min 44s', 'https://i.scdn.co/image/ab6765630000ba8a8b9e29b6406c9d6e85a1f7e6', '2025-07-24 12:17:03', NULL),
(7, 'podcast', 'Café da Manhã – O fim do Google tradicional', 'Discussão sobre o impacto das IA no Google e nas buscas.', NULL, 'https://open.spotify.com/episode/6I2p70D4sz5YmiZuLUeRxq', 'Spotify', '2023-10-22', '20min 01s', 'https://i.scdn.co/image/ab6765630000ba8aab4c22e47fc0879df170eb8f', '2025-07-24 12:17:03', NULL),
(8, 'podcast', 'História Preta – Zumbi dos Palmares', 'A história do líder quilombola e sua luta pela liberdade.', NULL, 'https://open.spotify.com/episode/1JqdzOWBhPO6gOnZQ1BCU3', 'Spotify', '2022-11-20', '26min 09s', 'https://i.scdn.co/image/ab6765630000ba8ad33ff3c0d98bb51df5e7bb36', '2025-07-24 12:17:03', NULL),
(9, 'podcast', 'Projeto Humanos – Caso Evandro (Ep. 01)', 'Episódio introdutório de um dos casos mais famosos do Brasil.', NULL, 'https://open.spotify.com/episode/6d1uIb7TxHQ1wI2D4ls2Fw', 'Spotify', '2020-04-14', '42min 13s', 'https://i.scdn.co/image/ab6765630000ba8a6403d558a9ffbc215180ce15', '2025-07-24 12:17:03', NULL),
(10, 'podcast', 'Budejo – Sertão e a cultura do rádio', 'Conversas sobre comunicação popular no Nordeste.', NULL, 'https://open.spotify.com/episode/1DjsghTlwBgoUeMb6dHuSK', 'Spotify', '2021-07-07', '51min 07s', 'https://i.scdn.co/image/ab6765630000ba8a1956d4b71602bb5f8ebc846b', '2025-07-24 12:17:03', NULL),
(11, 'video', 'Vídeo do YouTube', '', NULL, 'https://www.youtube.com/watch?v=fOvcmFN5ly4&ab_channel=Gaveta', 'YouTube', NULL, '', 'https://img.youtube.com/vi/fOvcmFN5ly4/hqdefault.jpg', '2025-07-24 12:30:27', NULL),
(12, 'podcast', 'De braço abertos: Começamos mais um ano Letivo!', 'Podcast Vicentino \"Bem Nosso\" · Episode', NULL, 'https://open.spotify.com/episode/6cRWU6IG8iSxheEhzl4L6l?si=4a76ed07681046c4', 'Spotify', NULL, '', 'https://i.scdn.co/image/ab6765630000ba8aa2efc2cdebe21405d834398d', '2025-07-24 12:38:59', NULL),
(13, 'audio', 'LISTA DE MUSICAS SEM DIREITOS AUTORAIS PRA VC PODER USAR SEM PROBLEMAS NOS SEUS VIDEOS', 'Listen to LISTA DE MUSICAS SEM DIREITOS AUTORAIS PRA VC PODER USAR SEM PROBLEMAS NOS SEUS VIDEOS by HigorPatinho #np on #SoundCloud', NULL, 'https://on.soundcloud.com/5ZRkaMu6XF8fGbRQKg', 'SoundCloud', NULL, '', 'https://i1.sndcdn.com/artworks-000143910091-a0j82l-t500x500.jpg', '2025-07-24 12:44:37', NULL);

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
-- Estrutura da tabela `sugestoes`
--

CREATE TABLE `sugestoes` (
  `id` int(11) NOT NULL,
  `usuario_id` int(11) NOT NULL,
  `mensagem` text NOT NULL,
  `data_envio` datetime DEFAULT current_timestamp(),
  `visualizado` tinyint(1) DEFAULT 0,
  `tipo` varchar(50) DEFAULT NULL,
  `resposta` text DEFAULT NULL,
  `respondido_em` datetime DEFAULT NULL
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
(22, 'Katherine Mansfield', 'autor', '2025-07-19 05:05:58', '2025-07-19 05:05:58'),
(23, 'teste', 'autor', '2025-07-24 17:54:17', '2025-07-24 17:54:17');

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
  `foto` varchar(255) DEFAULT NULL,
  `data_criacao` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Extraindo dados da tabela `usuarios`
--

INSERT INTO `usuarios` (`id`, `nome`, `email`, `senha`, `tipo`, `imagem_perfil`, `data_nascimento`, `genero`, `cep`, `endereco`, `cidade`, `estado`, `ativo`, `criado_em`, `foto`, `data_criacao`) VALUES
(29, 'Bottura', 'marcelo@marcelo.com', '$2y$10$08fu1yJgdQfEq8Lzyk02ZODfBw3hRjF4YfFynod79593ZmMuXH0Y6', 'usuario', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, '2025-06-11 23:26:27', 'perfil_29_1753197337.jpg', '2025-07-22 11:43:32'),
(30, 'Marcelo Botura', 'mbsfoz@gmail.com', '$2y$10$amjwDKCg.5xH3rkke.ncAe8myQAx0I8mQaVjHrSMwBWUzk8XQu45i', 'admin', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, '2025-06-11 23:27:30', NULL, '2025-07-22 11:43:32'),
(41, 'Marcos', 'marcos@marcos.com', '$2y$10$IiHOZrR8hu14xGlOeKuG2udwpuRSQWiCjwntnyLUwxmtRBsJSUwo6', 'usuario', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, '2025-07-19 03:09:10', NULL, '2025-07-22 11:43:32'),
(42, 'marcelo', 'marcelo@admin.com', '$2y$10$kYnqOrEkQB2cYQ84polqyerJjdgWcafB00WSPWNpMLWKv0YXPfyzm', 'admin', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, '2025-07-19 17:32:08', NULL, '2025-07-22 11:43:32'),
(43, 'José Batista', 'josefyfoz@gmail.com', '$2y$10$uaRrhyW1VdNs5yjmcScT4uKF1iiGsbADipAi/Lj9hXx4YFCxSg9O6', 'usuario', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, '2025-07-19 22:34:39', NULL, '2025-07-22 11:43:32'),
(44, 'pedrinho', 'pedrinho@pedrinho.com', '$2y$10$O/qJdCYTYgYOFnr7WKONFOxnHltRrjTuEJxIFudXvcHP8LRXMiUoO', 'usuario', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, '2025-07-19 22:41:36', NULL, '2025-07-22 11:43:32'),
(45, 'Emanoela Tatiane', 'emanoelasouza@gmail.com', '$2y$10$EKWc9ccBYWViEvRq1LYhUuAxh98tmZJ2TF.sX6pinyQcy54Z4v6AC', 'usuario', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, '2025-07-19 22:42:29', NULL, '2025-07-22 11:43:32'),
(46, 'Marcelo Souza', 'm_botura@gmail.com', '$2y$10$qMC.w9oz/LZlqUgs7yFUs.cDLeQNbOmd6usTyPz9CUzMj2KPAoC3y', 'usuario', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, '2025-07-21 01:44:02', NULL, '2025-07-22 11:43:32'),
(47, 'Pedro', 'pedro@pedro.com', '$2y$10$C0Nmj9PEs6gmyznvRryBpODYKUROl8pTaSyzJXBT5An.TNg79voOO', 'usuario', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, '2025-07-21 23:22:44', NULL, '2025-07-22 11:43:32'),
(48, 'ola', 'ola@ola.com', '$2y$10$zE4AWIcEgqI.C6yIE.PG2O9JBYYyDOqw934pPaobXz0d6p7mxt8GG', 'usuario', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, '2025-07-21 23:24:40', NULL, '2025-07-22 11:43:32'),
(49, 'Jéssica Fernandes', 'jessifernandes0@gmail.com', '$2y$10$14HL4jqehFv4lcA16ZfWluNmIUvy.Rt/OtFbVZxwlBEDNb1kdkYTu', 'usuario', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, '2025-07-22 12:59:22', 'perfil_49_1753199996.png', '2025-07-22 12:59:22');

--
-- Índices para tabelas despejadas
--

--
-- Índices para tabela `arquivos`
--
ALTER TABLE `arquivos`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_arquivo` (`caminho`);

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
-- Índices para tabela `episodios`
--
ALTER TABLE `episodios`
  ADD PRIMARY KEY (`id`);

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
-- Índices para tabela `livros_tags`
--
ALTER TABLE `livros_tags`
  ADD PRIMARY KEY (`id`),
  ADD KEY `livro_id` (`livro_id`),
  ADD KEY `tag_id` (`tag_id`);

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
-- Índices para tabela `midias`
--
ALTER TABLE `midias`
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
-- Índices para tabela `sugestoes`
--
ALTER TABLE `sugestoes`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_usuario_sugestao` (`usuario_id`);

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
-- AUTO_INCREMENT de tabela `arquivos`
--
ALTER TABLE `arquivos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `comentarios`
--
ALTER TABLE `comentarios`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

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
-- AUTO_INCREMENT de tabela `episodios`
--
ALTER TABLE `episodios`
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
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- AUTO_INCREMENT de tabela `livros_tags`
--
ALTER TABLE `livros_tags`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `livros_usuarios`
--
ALTER TABLE `livros_usuarios`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

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
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=89;

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
-- AUTO_INCREMENT de tabela `midias`
--
ALTER TABLE `midias`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

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
-- AUTO_INCREMENT de tabela `sugestoes`
--
ALTER TABLE `sugestoes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `tags`
--
ALTER TABLE `tags`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;

--
-- AUTO_INCREMENT de tabela `tokens_recuperacao`
--
ALTER TABLE `tokens_recuperacao`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=50;

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
-- Limitadores para a tabela `livros_tags`
--
ALTER TABLE `livros_tags`
  ADD CONSTRAINT `livros_tags_ibfk_1` FOREIGN KEY (`livro_id`) REFERENCES `livros` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `livros_tags_ibfk_2` FOREIGN KEY (`tag_id`) REFERENCES `tags` (`id`) ON DELETE CASCADE;

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
-- Limitadores para a tabela `sugestoes`
--
ALTER TABLE `sugestoes`
  ADD CONSTRAINT `fk_usuario_sugestao` FOREIGN KEY (`usuario_id`) REFERENCES `usuarios` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `sugestoes_ibfk_1` FOREIGN KEY (`usuario_id`) REFERENCES `usuarios` (`id`) ON DELETE CASCADE;

--
-- Limitadores para a tabela `tokens_recuperacao`
--
ALTER TABLE `tokens_recuperacao`
  ADD CONSTRAINT `tokens_recuperacao_ibfk_1` FOREIGN KEY (`usuario_id`) REFERENCES `usuarios` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
