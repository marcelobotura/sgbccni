<?php
require_once __DIR__ . '/../backend/config/config.php';

function gerarSlug(string $texto): string {
    $texto = html_entity_decode($texto, ENT_QUOTES, 'UTF-8');
    $texto = iconv('UTF-8', 'ASCII//TRANSLIT//IGNORE', $texto);
    $texto = strtolower($texto);
    $texto = preg_replace('/[^a-z0-9]+/', '-', $texto);
    return trim($texto, '-');
}

$dominio = 'http://localhost/sgbccni';
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mapa do Site</title>
    <style>
        body { font-family: Arial, sans-serif; background: #f9f9f9; padding: 20px; }
        h1 { color: #333; }
        h2 { color: #555; margin-top: 30px; }
        ul { list-style: none; padding-left: 0; }
        li { margin-bottom: 6px; }
        a { color: #0066cc; text-decoration: none; }
        a:hover { text-decoration: underline; }
        .container { max-width: 800px; margin: auto; background: #fff; padding: 20px; border-radius: 8px; box-shadow: 0 0 10px rgba(0,0,0,0.1); }
    </style>
</head>
<body>
<div class="container">
    <h1>🗺️ Mapa do Site</h1>

    <h2>📌 Páginas principais</h2>
    <ul>
        <li><a href="<?= $dominio ?>/index.php">🏠 Início</a></li>
        <li><a href="<?= $dominio ?>/catalogo.php">📚 Catálogo</a></li>
        <li><a href="<?= $dominio ?>/contato.php">📞 Contato</a></li>
        <li><a href="<?= $dominio ?>/diagnostico.php">🧪 Diagnóstico</a></li>
        <li><a href="<?= $dominio ?>/sobre.php">💡 Sobre</a></li>
    </ul>

    <h2>📖 Livros</h2>
    <ul>
    <?php
    $stmt = $pdo->query("SELECT id, titulo FROM livros");
    foreach ($stmt as $livro):
        $slug = gerarSlug($livro['titulo']);
        $url = "$dominio/ver_livro.php?id={$livro['id']}&slug=$slug";
        echo "<li><a href='$url'>" . htmlspecialchars($livro['titulo']) . "</a></li>";
    endforeach;
    ?>
    </ul>

    <h2>📂 Arquivos Públicos</h2>
    <ul>
    <?php
    $stmt = $pdo->query("SELECT id, nome FROM arquivos");
    foreach ($stmt as $arquivo):
        $slug = gerarSlug($arquivo['nome']);
        $url = "$dominio/frontend/admin/pages/ver_arquivo.php?id={$arquivo['id']}&slug=$slug";
        echo "<li><a href='$url'>" . htmlspecialchars($arquivo['nome']) . "</a></li>";
    endforeach;
    ?>
    </ul>

    <h2>🎥 Mídias Ativas</h2>
    <ul>
    <?php
    $stmt = $pdo->query("SELECT id, titulo FROM midias");
    foreach ($stmt as $midia):
        $slug = gerarSlug($midia['titulo']);
        $url = "$dominio/frontend/usuario/detalhes.php?id={$midia['id']}&slug=$slug";
        echo "<li><a href='$url'>" . htmlspecialchars($midia['titulo']) . "</a></li>";
    endforeach;
    ?>
    </ul>

    <h2>👤 Perfis Públicos</h2>
    <ul>
    <?php
    try {
        $stmt = $pdo->query("SELECT id, nome FROM usuarios WHERE visibilidade = 'publico'");
        foreach ($stmt as $usuario):
            $slug = gerarSlug($usuario['nome']);
            $url = "$dominio/frontend/usuario/ver_perfil.php?id={$usuario['id']}&slug=$slug";
            echo "<li><a href='$url'>" . htmlspecialchars($usuario['nome']) . "</a></li>";
        endforeach;
    } catch (Exception $e) {
        echo "<li><em>Nenhum perfil público disponível.</em></li>";
    }
    ?>
    </ul>
</div>
</body>
</html>
