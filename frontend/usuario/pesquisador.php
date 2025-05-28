<?php
define('BASE_PATH', dirname(__DIR__) . '/../backend');
require_once BASE_PATH . '/config/config.php';
require_once BASE_PATH . '/includes/session.php';
include_once BASE_PATH . '/includes/header.php';
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
  <meta charset="UTF-8">
  <title>🔎 Pesquisador - <?= htmlspecialchars(NOME_SISTEMA) ?></title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="<?= URL_BASE ?>assets/css/style.css" rel="stylesheet">
  <style>
    .search-box {
      max-width: 600px;
      margin: 0 auto;
    }

    #sugestoes {
      border: 1px solid #ccc;
      border-top: none;
      max-height: 200px;
      overflow-y: auto;
      background-color: #fff;
      z-index: 1000;
      position: absolute;
      width: 100%;
    }

    #sugestoes div {
      padding: 8px 12px;
      cursor: pointer;
    }

    #sugestoes div:hover {
      background-color: #f1f1f1;
    }
  </style>
</head>
<body>
<div class="container py-5">
  <div class="search-box position-relative">
    <form method="GET" action="busca.php">
      <h2 class="mb-4 text-center">🔎 Buscar Livro</h2>
      <div class="input-group">
        <input type="text" id="busca" name="q" class="form-control" placeholder="Digite o título, autor ou palavra-chave..." autocomplete="off">
        <button type="submit" class="btn btn-primary">Buscar</button>
      </div>
      <div id="sugestoes"></div>
    </form>
  </div>
</div>

<?php include_once BASE_PATH . '/includes/footer.php'; ?>

<script>
document.getElementById("busca").addEventListener("input", function () {
  const valor = this.value;
  if (valor.length >= 2) {
    fetch("sugestao.php?q=" + encodeURIComponent(valor))
      .then(res => res.json())
      .then(data => {
        const div = document.getElementById("sugestoes");
        div.innerHTML = "";
        data.forEach(item => {
          const opcao = document.createElement("div");
          opcao.textContent = item;
          opcao.onclick = () => {
            document.getElementById("busca").value = item;
            div.innerHTML = "";
          };
          div.appendChild(opcao);
        });
      });
  } else {
    document.getElementById("sugestoes").innerHTML = "";
  }
});
</script>
</body>
</html>
