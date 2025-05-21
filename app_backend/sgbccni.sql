-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Tempo de geração: 19-Maio-2025 às 16:07
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
-- Estrutura da tabela `emprestimos`
--

CREATE TABLE `emprestimos` (
  `id` int(11) NOT NULL,
  `id_usuario` int(11) DEFAULT NULL,
  `id_livro` int(11) DEFAULT NULL,
  `data_emprestimo` date DEFAULT NULL,
  `data_devolucao` date DEFAULT NULL,
  `devolvido` tinyint(1) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estrutura da tabela `livros`
--

CREATE TABLE `livros` (
  `id` int(11) NOT NULL,
  `titulo` varchar(255) DEFAULT NULL,
  `autor` varchar(255) DEFAULT NULL,
  `ano` int(11) DEFAULT NULL,
  `editora` varchar(100) DEFAULT NULL,
  `isbn` varchar(20) DEFAULT NULL,
  `capa` varchar(255) DEFAULT NULL,
  `status` enum('disponivel','emprestado') DEFAULT 'disponivel',
  `criado_em` datetime DEFAULT current_timestamp(),
  `tipo` enum('físico','digital') DEFAULT 'físico',
  `link_digital` text DEFAULT NULL,
  `capa_url` text DEFAULT NULL,
  `tags` text DEFAULT NULL,
  `categoria_padrao` varchar(100) DEFAULT NULL,
  `isbn10` varchar(20) DEFAULT NULL,
  `isbn13` varchar(20) DEFAULT NULL,
  `descricao` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Extraindo dados da tabela `livros`
--

INSERT INTO `livros` (`id`, `titulo`, `autor`, `ano`, `editora`, `isbn`, `capa`, `status`, `criado_em`, `tipo`, `link_digital`, `capa_url`, `tags`, `categoria_padrao`, `isbn10`, `isbn13`, `descricao`) VALUES
(27, 'Tecnologia indígena em Mato Grosso', 'José Afonso Botura Portocarrero', 2023, 'Entrelinhas Editora', '9786586328950', '', 'disponivel', '2025-05-19 04:51:30', 'físico', '', 'http://books.google.com/books/content?id=NZe4EAAAQBAJ&printsec=frontcover&img=1&zoom=1&edge=curl&source=gbs_api', 'Natureza', 'Architecture', '6586328950', '9786586328950', '[...] Talvez uma das mais importantes contribuições de um intelectual comprometido com o mundo em que vive seja colocar a sua energia criadora à disposição do entendimento entre as pessoas, considerando as mais diferentes culturas e as mais diferentes condições materiais de existência. Esse é o grande desafio que tem invadido de modo intenso as reflexões e a prática profissional do Prof. Dr. José Afonso Botura Portocarrero, ao longo de pelo menos mais de duas décadas [...]. Este trabalho [...] está integrado em um conjunto de propostas que vêm sendo desenvolvidas desde 2002, em torno de um núcleo de pesquisas denominado Tecnoíndia, que é registrado no Conselho Nacional de Pesquisa (CNPq) e certificado pela Universidade Federal de Mato Grosso (UFMT), onde tem uma sede. Em sua tese de doutorado [...] o autor assumiu uma postura etnográfica no levantamento dos desenhos das casas indígenas, buscando suas fontes nos registros bibliográficos e no extenso trabalho de campo entre os Paresí, Bakairi, Myky, Irantxe, Xavante, Bororo, Umutina e os índios do Parque Nacional do Xingu (Yawalapiti e Kamayurá). O levantamento dos desenhos é uma de suas contribuições mais importantes, que se incorpora ao conjunto de trabalhos que são referências consolidadas no campo da própria arquitetura [...]. O material de campo compõe um acervo inédito, contemplando habitações que pela primeira vez são registradas na perspectiva arquitetônica, em suas técnicas construtivas, o que leva também a uma contribuição substancial para os próprios índios, colaborando no registro da sua própria memória. [...] Profa Dra Maria Fátima Roberto Machado Depto de Antropologia/Museu Rondon – UFMT Coordenadora do Núcleo Tecnoíndia'),
(28, 'Educação matemática', 'Ubiratan D\'ambrosio', 1996, 'Papirus Editora', '9788530804107', '', 'disponivel', '2025-05-19 05:13:45', 'físico', '', 'http://books.google.com/books/content?id=NkGnY25OShcC&printsec=frontcover&img=1&zoom=1&edge=curl&source=gbs_api', '', 'Education', '8530804104', '9788530804107', 'A proposta dessa obra é \'a adoção de uma nova postura educacional que substitua o já desgastado ensino-aprendizagem\'. Após fazer considerações de caráter geral, abordando aspectos da cognição, da natureza da matemática e questões teóricas da educação, o autor discute inovações na prática docente, propondo reflexões sobre a matemática.'),
(29, 'Turismo e território no Brasil e na Itália', 'Glaucio José Marafon', 2014, 'SciELO - EDUERJ', '9788575114452', '', '', '2025-05-19 05:23:28', 'físico', '', 'http://books.google.com/books/content?id=A-OvDgAAQBAJ&printsec=frontcover&img=1&zoom=1&edge=curl&source=gbs_api', 'Turismo', 'Travel', '857511445X', '9788575114452', 'Com contribuições dos olhares do turismo, da geografia, da arquitetura e da história, apresenta uma ponte entre estudos brasileiros e italianos sobre o turismo cultural. Analisa a imigração italiana para o Brasil, entre o final do século XIX e início do século XX, e sua implicação no nascimento de uma cultura ítalo-descendente. Mostra as semelhanças e as diferenças das atividades turísticas de um país como a Itália, que é líder nesse setor, e do Brasil, que está a caminho da consolidação.'),
(30, 'Mentalidades Matemáticas', 'Jo Boaler', 2017, 'Penso Editora', '9788584291144', '', 'disponivel', '2025-05-19 14:25:25', 'físico', '', 'http://books.google.com/books/content?id=Auc0DwAAQBAJ&printsec=frontcover&img=1&zoom=1&edge=curl&source=gbs_api', '', 'Mathematics', '8584291148', '9788584291144', 'Embora todo ser humano seja capaz de aprender matemática em altos níveis e apaixonar-se pela disciplina ao longo de seus anos na escola e para toda a vida, todos nós temos ou conhecemos alguém que tem uma história de fracasso, frustração ou pavor relacionada à matemática. Neste livro, Jo Boaler aponta razões pelas quais a disciplina se tornou a grande vilã das experiências escolares dos estudantes. E, com base em sua extensa pesquisa, a autora revela como professores, gestores e pais podem ajudá-los a transformar suas ideias e experiências com a matemática ao desenvolver neles uma mentalidade de crescimento. Com exemplos eficazes, Mentalidades matemáticas é um importante guia de informações técnicas e atividades práticas que podem ser implementadas dentro e fora das salas de aula para tornar a aprendizagem da matemática mais agradável e acessível para todos os alunos.');

-- --------------------------------------------------------

--
-- Estrutura da tabela `tags`
--

CREATE TABLE `tags` (
  `id` int(11) NOT NULL,
  `nome` varchar(255) NOT NULL,
  `tipo` enum('autor','categoria','editora','outro') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Extraindo dados da tabela `tags`
--

INSERT INTO `tags` (`id`, `nome`, `tipo`) VALUES
(4, 'Architecture', 'categoria'),
(18, 'autoajuda', 'outro'),
(9, 'aventura', 'outro'),
(15, 'biografia', 'outro'),
(11, 'clássico', 'outro'),
(7, 'Education', 'categoria'),
(5, 'Entrelinhas Editora', 'editora'),
(14, 'fantasia', 'outro'),
(13, 'ficção científica', 'outro'),
(19, 'Glaucio José Marafon', 'autor'),
(17, 'história', 'outro'),
(16, 'infantil', 'outro'),
(2, 'Jéssica Fernandes Cardoso', 'autor'),
(22, 'Jo Boaler', 'autor'),
(3, 'José Afonso Botura Portocarrero', 'autor'),
(1, 'Marcelo Botura Souza', 'autor'),
(23, 'Mathematics', 'categoria'),
(10, 'mistério', 'outro'),
(8, 'Papirus Editora', 'editora'),
(24, 'Penso Editora', 'editora'),
(12, 'romance', 'outro'),
(21, 'SciELO - EDUERJ', 'editora'),
(20, 'Travel', 'categoria'),
(6, 'Ubiratan D\'ambrosio', 'autor');

-- --------------------------------------------------------

--
-- Estrutura da tabela `usuarios`
--

CREATE TABLE `usuarios` (
  `id` int(11) NOT NULL,
  `nome` varchar(100) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `senha` varchar(255) DEFAULT NULL,
  `tipo` enum('admin','usuario') DEFAULT 'usuario',
  `criado_em` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Índices para tabelas despejadas
--

--
-- Índices para tabela `emprestimos`
--
ALTER TABLE `emprestimos`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_usuario` (`id_usuario`),
  ADD KEY `id_livro` (`id_livro`);

--
-- Índices para tabela `livros`
--
ALTER TABLE `livros`
  ADD PRIMARY KEY (`id`);

--
-- Índices para tabela `tags`
--
ALTER TABLE `tags`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_nome_tipo` (`nome`,`tipo`);

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
-- AUTO_INCREMENT de tabela `emprestimos`
--
ALTER TABLE `emprestimos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `livros`
--
ALTER TABLE `livros`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=31;

--
-- AUTO_INCREMENT de tabela `tags`
--
ALTER TABLE `tags`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

--
-- AUTO_INCREMENT de tabela `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- Restrições para despejos de tabelas
--

--
-- Limitadores para a tabela `emprestimos`
--
ALTER TABLE `emprestimos`
  ADD CONSTRAINT `emprestimos_ibfk_1` FOREIGN KEY (`id_usuario`) REFERENCES `usuarios` (`id`),
  ADD CONSTRAINT `emprestimos_ibfk_2` FOREIGN KEY (`id_livro`) REFERENCES `livros` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
