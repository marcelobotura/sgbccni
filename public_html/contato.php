<?php
// Define o caminho base para a pasta 'backend', um nível acima da 'public_html'
define('BASE_PATH', dirname(__FILE__) . '/../backend');

// Inclui os arquivos de configuração e sessão essenciais
require_once BASE_PATH . '/config/config.php'; // Inclui configurações gerais, como URL_BASE
require_once BASE_PATH . '/includes/session.php'; // Gerencia a sessão (session_start() e funções de sessão)

// Inclui o cabeçalho da página
// Nota: O header.php deve estar na pasta 'backend/includes'
include_once BASE_PATH . '/includes/header.php';
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
  <meta charset="UTF-8">
  <title>Contato - Biblioteca CNI</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  </head>
<body class="bg-light">

<div class="container py-5">
  <div class="row justify-content-center">
    <div class="col-md-8">
      <div class="card shadow border-0">
        <div class="card-header bg-primary text-white text-center">
          <h4 class="mb-0">📬 Entre em Contato</h4>
        </div>
        <div class="card-body">

          <?php 
          // Exibe mensagens de sucesso ou erro da sessão
          if (isset($_SESSION['sucesso'])): 
          ?>
            <div class="alert alert-success text-center" role="alert"><?= htmlspecialchars($_SESSION['sucesso']) ?></div>
            <?php unset($_SESSION['sucesso']); // Limpa a mensagem após exibir ?>
          <?php elseif (isset($_SESSION['erro'])): ?>
            <div class="alert alert-danger text-center" role="alert"><?= htmlspecialchars($_SESSION['erro']) ?></div>
            <?php unset($_SESSION['erro']); // Limpa a mensagem após exibir ?>
          <?php endif; ?>

          <form method="POST" action="enviar_contato.php">
            <div class="mb-3">
              <label for="nome" class="form-label">Nome:</label>
              <input 
                type="text" 
                name="nome" 
                id="nome" 
                class="form-control" 
                required 
                placeholder="Seu nome completo"
                value="<?= htmlspecialchars($_SESSION['form_data']['nome'] ?? '') ?>"
              >
            </div>

            <div class="mb-3">
              <label for="email" class="form-label">E-mail:</label>
              <input 
                type="email" 
                name="email" 
                id="email" 
                class="form-control" 
                required 
                placeholder="seu.email@exemplo.com"
                value="<?= htmlspecialchars($_SESSION['form_data']['email'] ?? '') ?>"
              >
            </div>

            <div class="mb-3">
              <label for="mensagem" class="form-label">Mensagem:</label>
              <textarea 
                name="mensagem" 
                id="mensagem" 
                rows="5" 
                class="form-control" 
                required 
                placeholder="Escreva sua mensagem aqui"
              ><?= htmlspecialchars($_SESSION['form_data']['mensagem'] ?? '') ?></textarea>
            </div>

            <button type="submit" class="btn btn-primary w-100">📤 Enviar Mensagem</button>
          </form>

        </div>
      </div>
    </div>
  </div>
</div>

<?php 
// Inclui o rodapé da página
// Nota: O footer.php deve estar na pasta 'backend/includes'
include_once BASE_PATH . '/includes/footer.php'; 

// Limpa os dados do formulário da sessão após a exibição
unset($_SESSION['form_data']);
?>
</body>
</html>