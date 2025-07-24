<?php
// Caminho: frontend/admin/pages/gerenciar_arquivos.php

define('BASE_PATH', dirname(__DIR__, 3));
require_once BASE_PATH . '/backend/config/config.php';
require_once BASE_PATH . '/backend/includes/session.php';
require_once BASE_PATH . '/backend/includes/protect_admin.php';
require_once BASE_PATH . '/backend/includes/header.php';

exigir_login('admin');

// 🗂️ Categorias e extensões válidas
$categorias = ['apostilas', 'editais', 'imagens', 'outros'];
$extensoes_permitidas = ['pdf', 'docx', 'xlsx', 'jpg', 'jpeg', 'png', 'mp4', 'mp3', 'zip', 'rar'];

// 🔧 Criação dos diretórios caso não existam
$diretorio_base = BASE_PATH . '/uploads/arquivos';
foreach ($categorias as $cat) {
    $caminho = "$diretorio_base/$cat";
    if (!is_dir($caminho)) mkdir($caminho, 0777, true);
}

// 📌 Filtros GET
$categoria_ativa = $_GET['categoria'] ?? 'todos';
$busca = strtolower(trim($_GET['busca'] ?? ''));

// 📁 Coleta dos arquivos
$arquivos = [];
if ($categoria_ativa === 'todos') {
    foreach ($categorias as $cat) {
        $arquivos = array_merge($arquivos, glob("$diretorio_base/$cat/*"));
    }
} else {
    $arquivos = glob("$diretorio_base/$categoria_ativa/*");
}
sort($arquivos);
?>

<div class="container py-4">
    <h3 class="mb-4"><i class="bi bi-folder2-open"></i> Gerenciar Arquivos</h3>

    <?php if (!empty($_SESSION['sucesso'])): ?>
        <div class="alert alert-success"><?= $_SESSION['sucesso']; unset($_SESSION['sucesso']); ?></div>
    <?php elseif (!empty($_SESSION['erro'])): ?>
        <div class="alert alert-danger"><?= $_SESSION['erro']; unset($_SESSION['erro']); ?></div>
    <?php endif; ?>

    <!-- 🔼 Upload -->
    <form method="POST" action="<?= URL_BASE ?>backend/controllers/arquivos/salvar_arquivo.php" enctype="multipart/form-data" class="row g-2 mb-4">
        <div class="col-md-4">
            <input type="file" name="arquivo" class="form-control" required>
        </div>
        <div class="col-md-4">
            <select name="categoria" class="form-select" required>
                <option value="">Escolha uma categoria</option>
                <?php foreach ($categorias as $cat): ?>
                    <option value="<?= $cat ?>" <?= $categoria_ativa === $cat ? 'selected' : '' ?>><?= ucfirst($cat) ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="col-md-4">
            <button name="upload" class="btn btn-success w-100"><i class="bi bi-upload"></i> Enviar</button>
        </div>
    </form>

    <!-- 🔎 Filtro de busca -->
    <form method="GET" class="row g-2 align-items-center mb-4">
        <div class="col-md-6">
            <input type="text" name="busca" class="form-control" placeholder="🔍 Buscar arquivo por nome..." value="<?= htmlspecialchars($busca) ?>">
        </div>
        <div class="col-md-4">
            <select name="categoria" class="form-select" onchange="this.form.submit()">
                <option value="todos" <?= $categoria_ativa === 'todos' ? 'selected' : '' ?>>Todos os Documentos</option>
                <?php foreach ($categorias as $cat): ?>
                    <option value="<?= $cat ?>" <?= $categoria_ativa === $cat ? 'selected' : '' ?>><?= ucfirst($cat) ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="col-md-2">
            <button class="btn btn-primary w-100">Filtrar</button>
        </div>
    </form>

    <!-- 📄 Tabela de arquivos -->
    <?php if (empty($arquivos)): ?>
        <div class="alert alert-warning">Nenhum arquivo encontrado na seleção atual.</div>
    <?php else: ?>
        <div class="table-responsive">
            <table class="table table-bordered table-hover align-middle">
                <thead class="table-dark">
                    <tr>
                        <th>Arquivo</th>
                        <th>Categoria</th>
                        <th>Tipo</th>
                        <th>Tamanho</th>
                        <th>Data</th>
                        <th class="text-center">Ações</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($arquivos as $arq):
                        $nome = basename($arq);
                        $cat_do_arquivo = basename(dirname($arq));
                        if ($busca && !str_contains(strtolower($nome), $busca)) continue;

                        $ext = strtolower(pathinfo($nome, PATHINFO_EXTENSION));
                       switch ($ext) {
    case 'pdf':
        $icone = 'bi bi-file-earmark-pdf text-danger';
        break;
    case 'docx':
        $icone = 'bi bi-file-earmark-word text-primary';
        break;
    case 'xlsx':
        $icone = 'bi bi-file-earmark-excel text-success';
        break;
    case 'jpg':
    case 'jpeg':
    case 'png':
        $icone = 'bi bi-image text-info';
        break;
    case 'mp3':
        $icone = 'bi bi-music-note';
        break;
    case 'mp4':
        $icone = 'bi bi-camera-reels';
        break;
    default:
        $icone = 'bi bi-file-earmark';
}


                        $link = URL_BASE . "uploads/arquivos/$cat_do_arquivo/" . rawurlencode($nome);
                        $tamanho_kb = round(filesize($arq) / 1024, 1);
                        $data_modif = date('d/m/Y H:i', filemtime($arq));
                    ?>
                    <tr>
                        <td><i class="<?= $icone ?>"></i> <?= htmlspecialchars($nome) ?></td>
                        <td><?= ucfirst($cat_do_arquivo) ?></td>
                        <td><?= strtoupper($ext) ?></td>
                        <td><?= $tamanho_kb ?> KB</td>
                        <td><?= $data_modif ?></td>
                        <td class="text-center">
                            <a href="<?= $link ?>" class="btn btn-sm btn-outline-primary" target="_blank">🔗 Ver</a>
                            <a href="<?= URL_BASE ?>frontend/admin/pages/editar_arquivo.php?id=<?= urlencode($nome) ?>&cat=<?= $cat_do_arquivo ?>" class="btn btn-sm btn-secondary">✏️ Editar</a>
                            <a href="<?= $_SERVER['PHP_SELF'] ?>?delete=<?= urlencode($nome) ?>&cat=<?= $cat_do_arquivo ?>" class="btn btn-sm btn-danger" onclick="return confirm('Excluir este arquivo?')">🗑️</a>
                        </td>
                    </tr>
                    <?php endforeach ?>
                </tbody>
            </table>
        </div>
    <?php endif ?>
</div>

<?php require_once BASE_PATH . '/backend/includes/footer.php'; ?>
