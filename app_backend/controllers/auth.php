<?php
// Garante que a sessão seja iniciada apenas uma vez.
// Idealmente, session_start() deveria ser chamado em config.php
// ou em um arquivo de bootstrapping que é o primeiro a ser incluído.
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/../config/config.php';

// Obtém a ação a ser executada (register, login, logout)
$acao = $_POST['acao'] ?? $_GET['acao'] ?? '';

// --- REGISTRO DE USUÁRIO ---
if ($acao === 'register') {
    $nome = trim($_POST['nome'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $senha = $_POST['senha'] ?? '';
    $tipo = $_POST['tipo'] ?? 'usuario'; // Padrão é 'usuario'

    $data_nascimento = $_POST['data_nascimento'] ?? null;
    $genero = $_POST['genero'] ?? null;
    $cep = $_POST['cep'] ?? null;
    $endereco = $_POST['endereco'] ?? null;
    $cidade = $_POST['cidade'] ?? null;
    $estado = $_POST['estado'] ?? null;

    // 1. Validação básica de campos obrigatórios
    if (empty($nome) || empty($email) || empty($senha)) {
        $_SESSION['erro'] = "Por favor, preencha todos os campos obrigatórios (Nome, E-mail, Senha).";
        header("Location: " . URL_BASE . "login/register.php");
        exit;
    }

    // 2. Validação de formato de e-mail
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $_SESSION['erro'] = "O formato do e-mail é inválido.";
        header("Location: " . URL_BASE . "login/register.php");
        exit;
    }

    // 3. Validação de senha (exemplo: mínimo de 6 caracteres)
    if (strlen($senha) < 6) {
        $_SESSION['erro'] = "A senha deve ter no mínimo 6 caracteres.";
        header("Location: " . URL_BASE . "login/register.php");
        exit;
    }

    // 4. Verificar se o e-mail já existe no banco de dados
    $stmt_check_email = $conn->prepare("SELECT id FROM usuarios WHERE email = ?");
    $stmt_check_email->bind_param("s", $email);
    $stmt_check_email->execute();
    $stmt_check_email->store_result();

    if ($stmt_check_email->num_rows > 0) {
        $_SESSION['erro'] = "Este e-mail já está cadastrado. Por favor, use outro e-mail.";
        $stmt_check_email->close();
        header("Location: " . URL_BASE . "login/register.php");
        exit;
    }
    $stmt_check_email->close(); // Fecha o statement de verificação

    // 5. Upload de foto de perfil (opcional)
    $imagem_perfil = '';
    if (!empty($_FILES['foto_perfil']['name'])) {
        $ext = pathinfo($_FILES['foto_perfil']['name'], PATHINFO_EXTENSION);
        $foto_nome = uniqid() . '.' . $ext; // Gera um nome único para a imagem
        $destino = __DIR__ . '/../uploads/perfis/' . $foto_nome;

        // Tenta mover o arquivo e verifica o sucesso
        if (move_uploaded_file($_FILES['foto_perfil']['tmp_name'], $destino)) {
            $imagem_perfil = 'uploads/perfis/' . $foto_nome; // Caminho relativo para salvar no BD
        } else {
            // Se o upload falhar, registra o erro mas permite o cadastro sem a foto
            error_log("Erro ao fazer upload da foto de perfil: " . $_FILES['foto_perfil']['name']);
            $_SESSION['aviso'] = "Não foi possível fazer o upload da foto de perfil. O cadastro será realizado sem ela.";
        }
    }

    // Hash da senha
    $senha_hash = password_hash($senha, PASSWORD_DEFAULT);

    // Inserção no banco de dados
    $stmt = $conn->prepare("INSERT INTO usuarios (
        nome, email, senha, tipo, imagem_perfil,
        data_nascimento, genero, cep, endereco, cidade, estado, ativo
    ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, 1)");

    $stmt->bind_param(
        "sssssssssss",
        $nome, $email, $senha_hash, $tipo, $imagem_perfil,
        $data_nascimento, $genero, $cep, $endereco, $cidade, $estado
    );

    if ($stmt->execute()) {
        $_SESSION['sucesso'] = "Cadastro realizado com sucesso! Agora você pode fazer login.";
        header("Location: " . URL_BASE . "login/"); // Redireciona para a página de login
    } else {
        // Erro na execução da query SQL
        error_log("Erro SQL ao cadastrar usuário: " . $stmt->error); // Loga o erro detalhado
        $_SESSION['erro'] = "Erro interno ao cadastrar. Por favor, tente novamente mais tarde.";
        header("Location: " . URL_BASE . "login/register.php");
    }
    $stmt->close(); // Fecha o statement
    exit;
}

// --- LOGIN DE USUÁRIO ---
if ($acao === 'login') {
    $email = trim($_POST['usuario'] ?? ''); // Nome do campo é 'usuario' no formulário de login
    $senha = $_POST['senha'] ?? '';

    // Validação básica para login
    if (empty($email) || empty($senha)) {
        $_SESSION['erro'] = "Por favor, digite seu e-mail e senha.";
        header("Location: " . URL_BASE . "login/");
        exit;
    }

    $stmt = $conn->prepare("SELECT id, nome, senha, tipo, imagem_perfil FROM usuarios WHERE email = ? AND ativo = 1"); // Adicionado 'ativo = 1'
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result(); // Necessário para usar num_rows

    if ($stmt->num_rows === 1) {
        $stmt->bind_result($id, $nome, $senha_hash, $tipo, $foto);
        $stmt->fetch(); // Obtém os resultados

        if (password_verify($senha, $senha_hash)) {
            // Login bem-sucedido: Armazena dados do usuário na sessão
            $_SESSION['usuario_id'] = $id;
            $_SESSION['usuario_nome'] = $nome;
            $_SESSION['usuario_tipo'] = $tipo;
            $_SESSION['usuario_foto'] = $foto;

            // Redireciona para o painel apropriado (admin ou usuário comum)
            $destino = ($tipo === 'admin') ? URL_BASE . "admin/" : URL_BASE . "usuario/";
            header("Location: $destino");
        } else {
            $_SESSION['erro'] = "Senha incorreta.";
            header("Location: " . URL_BASE . "login/");
        }
    } else {
        $_SESSION['erro'] = "E-mail não encontrado ou conta inativa."; // Mensagem mais genérica por segurança
        header("Location: " . URL_BASE . "login/");
    }
    $stmt->close(); // Fecha o statement
    exit;
}

// --- LOGOUT DE USUÁRIO ---
if ($acao === 'logout') {
    session_destroy(); // Destrói todos os dados da sessão
    $_SESSION = array(); // Limpa o array da sessão (boas práticas)
    setcookie(session_name(), '', time() - 3600, '/'); // Invalida o cookie da sessão
    header("Location: " . URL_BASE . "login/"); // Redireciona para a página de login
    exit;
}

// Se nenhuma ação válida for fornecida, redireciona para a página de login
header("Location: " . URL_BASE . "login/");
exit;

?>