<?php
// 🔧 Exibir erros no desenvolvimento
ini_set('display_errors', 1);
error_reporting(E_ALL);

define('BASE_PATH', dirname(__DIR__) . '/../backend');
require_once BASE_PATH . '/config/config.php';
require_once BASE_PATH . '/includes/session.php';
require_once BASE_PATH . '/includes/header.php';
require_once __DIR__ . '/protect_usuario.php';

exigir_login('usuario');

// Dados atuais do usuário
$id = $_SESSION['usuario_id'];
$nome_atual = $_SESSION['usuario_nome'] ?? '';
$email_atual = $_SESSION['usuario_email'] ?? '';
$foto_atual = $_SESSION['usuario_foto'] ?? null;

// Atualização de perfil
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['acao']) && $_POST['acao'] === 'atualizar') {
        $nome = trim($_POST['nome'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $nova_senha = $_POST['nova_senha'] ?? '';

        if (empty($nome) || empty($email)) {
            $_SESSION['erro'] = "Nome e e-mail são obrigatórios.";
        } else {
            // Verificar e-mail duplicado
            $stmt = $pdo->prepare("SELECT id FROM usuarios WHERE email = ? AND id != ?");
            $stmt->execute([$email, $id]);
            if ($stmt->rowCount() > 0) {
                $_SESSION['erro'] = "Este e-mail já está em uso.";
            } else {
                // Upload de nova foto
                $foto_sql = "";
                if (isset($_FILES['foto']) && $_FILES['foto']['error'] === UPLOAD_ERR_OK) {
                    $ext = strtolower(pathinfo($_FILES['foto']['name'], PATHINFO_EXTENSION));
                    $novo_nome = uniqid('foto_', true) . "." . $ext;
                    $destino = BASE_PATH . "/../uploads/perfis/" . $novo_nome;

                    if (move_uploaded_file($_FILES['foto']['tmp_name'], $destino)) {
                        if ($foto_atual && file_exists(BASE_PATH . "/../uploads/perfis/" . $foto_atual)) {
                            unlink(BASE_PATH . "/../uploads/perfis/" . $foto_atual);
                        }
                        $foto_sql = ", foto = ?";
                        $_SESSION['usuario_foto'] = $novo_nome;
                        $foto_param = $novo_nome;
                    } else {
                        $_SESSION['erro'] = "Erro ao fazer upload da nova foto.";
                    }
                }

                // Atualizar dados
                $params = [$nome, $email];
                $sql = "UPDATE usuarios SET nome = ?, email = ?";

                if (!empty($nova_senha)) {
                    $senha_hash = password_hash($nova_senha, PASSWORD_DEFAULT);
                    $sql .= ", senha = ?";
                    $params[] = $senha_hash;
                }

                if (!empty($foto_sql)) {
                    $sql .= $foto_sql;
                    $params[] = $foto_param;
                }

                $sql .= " WHERE id = ?";
                $params[] = $id;

                $stmt = $pdo->prepare($sql);
                if ($stmt->execute($params)) {
                    $_SESSION['usuario_nome'] = $nome;
                    $_SESSION['usuario_email'] = $email;
                    $_SESSION['sucesso'] = "Perfil atualizado com sucesso!";
                } else {
                    $_SESSION['erro'] = "Erro ao atualizar o perfil.";
                }
            }
        }
    }

    // Exclusão de conta
    if (isset($_POST['acao']) && $_POST['acao'] === 'excluir') {
        $senha = $_POST['senha_confirmacao'] ?? '';

        if (empty($senha)) {
            $_SESSION['erro'] = "Digite sua senha para confirmar.";
        } else {
            $stmt = $pdo->prepare("SELECT senha FROM usuarios WHERE id = ?");
            $stmt->execute([$id]);
            $hash = $stmt->fetchColumn();

            if ($hash && password_verify($senha, $hash)) {
                if ($foto_atual && file_exists(BASE_PATH . "/../uploads/perfis/" . $foto_atual)) {
                    unlink(BASE_PATH . "/../uploads/perfis/" . $foto_atual);
                }

                $pdo->prepare("DELETE FROM usuarios WHERE id = ?")->execute([$id]);

                session_destroy();
                header("Location: ../login/login_user.php?msg=conta_excluida");
                exit;
            } else {
                $_SESSION['erro'] = "Senha incorreta para excluir a conta.";
            }
        }
    }

    header("Location: editar_conta.php");
    exit;
}

// Dados atualizados para exibição
$nome = htmlspecialchars($_SESSION['usuario_nome'] ?? '');
$email = htmlspecialchars($_SESSION['usuario_email'] ?? '');
$foto = $_SESSION['usuario_foto'] ?? null;
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
  <meta charset="UTF-8">
  <title>Editar Conta - Biblioteca CNI</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
  <link rel="stylesheet" href="<?= URL_BASE ?>frontend/assets/css/base.css">
  <link rel="stylesheet" href="<?= URL_BASE ?>frontend/assets/css/layout.css">
  <link rel="stylesheet" href="<?= URL_BASE ?>frontend/assets/css/components.css">
  <link rel="stylesheet" href="<?= URL_BASE ?>frontend/assets/css/pages/painel_usuario.css">
</head>
<body>

<main class="painel-usuario container">
  <header class="painel-header d-flex justify-content-between align-items-center mb-4">
    <h2><i class="bi bi-person-lines-fill"></i> Editar Conta</h2>
    <a href="index.php" class="btn btn-secundario"><i class="bi bi-arrow-left"></i> Voltar</a>
  </header>

  <?php if (!empty($_SESSION['sucesso'])): ?>
    <div class="alert alert-success"><?= $_SESSION['sucesso']; unset($_SESSION['sucesso']); ?></div>
  <?php elseif (!empty($_SESSION['erro'])): ?>
    <div class="alert alert-danger"><?= $_SESSION['erro']; unset($_SESSION['erro']); ?></div>
  <?php endif; ?>

  <section class="card p-4 mb-5">
    <form method="POST" enctype="multipart/form-data">
      <input type="hidden" name="acao" value="atualizar">

      <div class="text-center mb-4">
        <img id="previewFoto"
             src="<?= $foto ? URL_BASE . 'uploads/perfis/' . htmlspecialchars($foto) : URL_BASE . 'assets/img/user-default.png' ?>"
             class="rounded-circle shadow"
             style="width: 130px; height: 130px; object-fit: cover; cursor: pointer;"
             alt="Foto de Perfil">
        <div class="mt-2">
          <label for="inputFoto" class="btn btn-outline-primary btn-sm">📤 Alterar Foto</label>
          <input type="file" id="inputFoto" name="foto" class="form-control d-none" accept="image/*">
        </div>
      </div>

      <div class="form-floating mb-3">
        <input type="text" name="nome" id="nome" class="form-control" value="<?= $nome ?>" required>
        <label for="nome">Nome</label>
      </div>

      <div class="form-floating mb-3">
        <input type="email" name="email" id="email" class="form-control" value="<?= $email ?>" required>
        <label for="email">E-mail</label>
      </div>

      <div class="form-floating mb-3">
        <input type="password" name="nova_senha" id="nova_senha" class="form-control">
        <label for="nova_senha">Nova Senha (opcional)</label>
      </div>

      <div class="d-flex justify-content-between">
        <button type="submit" class="btn btn-success"><i class="bi bi-save me-1"></i>Salvar</button>
      </div>
    </form>
  </section>

  <section class="card p-4 border-danger">
    <h5 class="text-danger">Excluir Conta</h5>
    <form method="POST">
      <input type="hidden" name="acao" value="excluir">
      <div class="form-floating mb-3">
        <input type="password" name="senha_confirmacao" id="senha_confirmacao" class="form-control" required>
        <label for="senha_confirmacao">Digite sua senha para confirmar</label>
      </div>
      <button type="submit" class="btn btn-outline-danger" onclick="return confirm('Tem certeza? Esta ação é irreversível.')">
        <i class="bi bi-trash"></i> Excluir Conta
      </button>
    </form>
  </section>
</main>

<script>
  const inputFoto = document.getElementById('inputFoto');
  const previewFoto = document.getElementById('previewFoto');
  inputFoto.addEventListener('change', function () {
    const file = this.files[0];
    if (file && file.type.startsWith('image/')) {
      const reader = new FileReader();
      reader.onload = e => previewFoto.src = e.target.result;
      reader.readAsDataURL(file);
    }
  });
</script>

<?php require_once BASE_PATH . '/includes/footer.php'; ?>
</body>
</html>
