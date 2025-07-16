<?php
session_start();

// ✅ Inclui corretamente a config e sessão
// CORREÇÃO: O caminho foi simplificado para './env.php' pois env.php está na mesma pasta.
require_once __DIR__ . '/env.php'; // Define constantes como URL_BASE, DB_HOST, DB_NAME, etc.
require_once __DIR__ . '/../includes/session.php'; // Garante sessão ativa e funções de sessão
require_once __DIR__ . '/database.php'; // CORREÇÃO: O caminho para database.php também foi ajustado para './database.php' se estiver na mesma pasta, ou '../config/database.php' se estiver em config, ou '../database.php' se estiver na raiz de backend.

// Inclui helper para mensagens de sessão, se você tiver um.
// require_once __DIR__ . '/../includes/session_messages.php';

// Adiciona uma função auxiliar para redirecionar com mensagem de erro/sucesso
function redirectTo($path, $type, $message) {
    $_SESSION[$type] = $message;
    header("Location: " . URL_BASE . $path);
    exit;
}

// Verifica se a requisição é POST e se a ação foi definida
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['acao'])) {
    $acao  = $_POST['acao'];
    $email = trim(filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL));
    $senha = $_POST['senha'];
    $origem = isset($_POST['origem']) ? $_POST['origem'] : 'usuario';
    $loginPage = $origem === 'admin' ? 'login_admin.php' : 'login_user.php';
    $registerPage = $origem === 'admin' ? 'register_admin.php' : 'register_user.php';

    // 📧 Validação de e-mail básica
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
<<<<<<< Updated upstream
        $_SESSION['erro'] = "E-mail inválido.";
        header("Location: " . URL_BASE . "login/" . ($acao === 'register' ? $registerPage : $loginPage));
        exit;
=======
        redirectTo("login/" . ($acao === 'register' ? "register.php" : "login.php"), 'erro', "E-mail inválido.");
>>>>>>> Stashed changes
    }

    // ✅ REGISTRO
    if ($acao === 'register') {
        $nome = trim(filter_input(INPUT_POST, 'nome', FILTER_SANITIZE_STRING));
        $tipo = 'usuario'; // Tipo padrão para novo usuário

        // Validação de campos vazios para registro
        if (empty($nome) || empty($email) || empty($senha)) {
<<<<<<< Updated upstream
            $_SESSION['erro'] = "Preencha todos os campos.";
            header("Location: " . URL_BASE . "login/" . $registerPage);
            exit;
=======
            redirectTo("login/register.php", 'erro', "Preencha todos os campos.");
>>>>>>> Stashed changes
        }

        // Verifica se o e-mail já está cadastrado
        $stmt = $conn->prepare("SELECT id FROM usuarios WHERE email = ?");
        // Verifica se a preparação da query falhou
        if (!$stmt) {
            error_log("Erro ao preparar consulta de verificação de e-mail: " . $conn->error);
            redirectTo("login/register.php", 'erro', "Erro interno ao verificar e-mail. Tente novamente mais tarde.");
        }
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows > 0) {
            $stmt->close();
<<<<<<< Updated upstream
            header("Location: " . URL_BASE . "login/" . $registerPage);
            exit;
=======
            redirectTo("login/register.php", 'erro', "Este e-mail já está cadastrado.");
>>>>>>> Stashed changes
        }
        $stmt->close();

        // Gera o hash da senha
        $senha_hash = password_hash($senha, PASSWORD_DEFAULT);

        // Insere o novo usuário
        $stmt = $conn->prepare("INSERT INTO usuarios (nome, email, senha, tipo) VALUES (?, ?, ?, ?)");
        // Verifica se a preparação da query falhou
        if (!$stmt) {
            error_log("Erro ao preparar consulta de inserção de usuário: " . $conn->error);
            redirectTo("login/register.php", 'erro', "Erro interno ao cadastrar usuário. Tente novamente mais tarde.");
        }
        $stmt->bind_param("ssss", $nome, $email, $senha_hash, $tipo);

        if ($stmt->execute()) {
            session_regenerate_id(true); // Regenera o ID da sessão para prevenir Session Fixation
            $_SESSION['usuario_id']   = $stmt->insert_id;
            $_SESSION['usuario_nome'] = htmlspecialchars($nome); // Sanitize para exibição
            $_SESSION['usuario_tipo'] = $tipo;
            $stmt->close();

            redirectTo("usuario/meus_livros.php", 'sucesso', "Cadastro realizado com sucesso!");
        } else {
<<<<<<< Updated upstream
            $_SESSION['erro'] = "Erro ao cadastrar usuário.";
            header("Location: " . URL_BASE . "login/" . $registerPage);
            exit;
=======
            error_log("Erro ao executar inserção de usuário: " . $stmt->error); // Loga o erro real
            redirectTo("login/register.php", 'erro', "Erro ao cadastrar usuário. Tente novamente mais tarde.");
>>>>>>> Stashed changes
        }
    }

    // ✅ LOGIN
    elseif ($acao === 'login') {
        $stmt = $conn->prepare("SELECT id, nome, senha, tipo FROM usuarios WHERE email = ?");
        // Verifica se a preparação da query falhou
        if (!$stmt) {
            error_log("Erro ao preparar consulta de login: " . $conn->error);
            redirectTo("login/login.php", 'erro', "Erro interno de autenticação. Tente novamente mais tarde.");
        }
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows === 1) {
            $stmt->bind_result($id, $nome, $senha_hash, $tipo);
            $stmt->fetch();

            if (password_verify($senha, $senha_hash)) {
                session_regenerate_id(true); // Regenera o ID da sessão para prevenir Session Fixation
                $_SESSION['usuario_id']   = $id;
                $_SESSION['usuario_nome'] = htmlspecialchars($nome); // Sanitize para exibição
                $_SESSION['usuario_tipo'] = $tipo;
                $stmt->close();

                // Redireciona com base no tipo de usuário
                if ($tipo === 'admin') {
                    redirectTo("frontend/admin/pages/index.php", 'sucesso', "Login de administrador realizado com sucesso!"); //
                } else {
                    redirectTo("frontend/usuario/meus_livros.php", 'sucesso', "Login de usuário realizado com sucesso!"); //
                }
            } else {
                // Mensagem de erro genérica para segurança (evita enumeração de usuários)
                redirectTo("login/login.php", 'erro', "Credenciais inválidas.");
            }
        } else {
            // Mensagem de erro genérica para segurança (evita enumeração de usuários)
            redirectTo("login/login.php", 'erro', "Credenciais inválidas.");
        }
<<<<<<< Updated upstream

        $stmt->close();
        header("Location: " . URL_BASE . "login/" . $loginPage);
        exit;
=======
        $stmt->close(); // Garante que a query seja fechada mesmo se houver erro na senha.
>>>>>>> Stashed changes
    }
} else {
    // Redireciona se a requisição não for POST ou se a ação não for definida (acesso direto)
    header("Location: " . URL_BASE . "public_html/index.php"); //
    exit;
}