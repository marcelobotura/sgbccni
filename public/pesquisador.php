<?php
include_once '../includes/config.php';
include_once '../includes/header.php';
?>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="<?= URL_BASE ?>assets/css/style.css" rel="stylesheet">



<!DOCTYPE html>
<html lang="pt-br">
<head>
  <meta charset="UTF-8">
  <title><?= htmlspecialchars(NOME_SISTEMA) ?></title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="<?= URL_BASE ?>assets/css/style.css" rel="stylesheet">
</head>
<body class="<?= $modo === 'light' ? 'light-mode' : '' ?>">
  <button class="toggle-theme-btn" onclick="alternarTema()">🌓 Alternar Tema</button>


  <div class="container search-container">
  <form method="GET" action="busca.php" class="search-box">
    <h2>🔎 Buscar Livro</h2>
    <div class="input-group">
      <input type="text" name="q" placeholder="Digite o título, autor ou palavra-chave...">
      <button type="submit" class="btn btn-primary">Buscar</button>
    </div>
  </form>
</div>


<script>
document.getElementById("busca").addEventListener("input", function () {
  let valor = this.value;
  if (valor.length >= 2) {
    fetch("sugestao.php?q=" + valor)
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


<?php include_once '../includes/footer.php'; ?>