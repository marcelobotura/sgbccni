<?php
session_start();
define('BASE_PATH', dirname(__DIR__, 3) . '/backend');
require_once BASE_PATH . '/config/config.php';
require_once BASE_PATH . '/includes/session.php';
require_once BASE_PATH . '/includes/protect_admin.php';
require_once BASE_PATH . '/includes/header.php';


exigir_login('admin');

$categorias = ['apostilas', 'editais', 'imagens'];
$diretorio_base = dirname(__DIR__, 3) . '/uploads/arquivos';
$mensagem = '';

// Cria pastas se não existirem
foreach ($categorias as $categoria) {
    $caminho = "$diretorio_base/$categoria";
    if (!is_dir($caminho)) mkdir($caminho, 0777, true);
}

// Excluir arquivo
if (isset($_GET['delete'], $_GET['cat']) && in_array($_GET['cat'], $categorias)) {
    $arquivo = "$diretorio_base/{$_GET['cat']}/" . basename($_GET['delete']);
    if (is_file($arquivo) && unlink($arquivo)) {
        $_SESSION['sucesso'] = "Arquivo excluído com sucesso.";
    } else {
        $_SESSION['erro'] = "Erro ao excluir o arquivo.";
    }
    header("Location: gerenciar_arquivos.php?categoria={$_GET['cat']}");
    exit;
}

// Upload
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['arquivo'])) {
    $categoria = $_POST['categoria'] ?? '';
    $nomeArquivo = basename($_FILES['arquivo']['name']);
    $extensoes_permitidas = ['pdf', 'docx', 'jpg', 'jpeg', 'png'];
    $ext = strtolower(pathinfo($nomeArquivo, PATHINFO_EXTENSION));

    if (!in_array($categoria, $categorias)) {
        $_SESSION['erro'] = "Categoria inválida.";
    } elseif (!in_array($ext, $extensoes_permitidas)) {
        $_SESSION['erro'] = "Tipo de arquivo não permitido.";
    } else {
        $destino = "$diretorio_base/$categoria/$nomeArquivo";
        if (move_uploaded_file($_FILES['arquivo']['tmp_name'], $destino)) {
            $_SESSION['sucesso'] = "Arquivo enviado com sucesso.";
        } else {
            $_SESSION['erro'] = "Erro ao fazer upload do arquivo.";
        }
    }
    header("Location: gerenciar_arquivos.php?categoria=$categoria");
    exit;
}

$categoria_ativa = $_GET['categoria'] ?? $categorias[0];
$arquivos = glob("$diretorio_base/$categoria_ativa/*");
?>

<div class="container py-4">
    <h3 class="mb-4">📁 Gerenciador de Arquivos</h3>

    <?php if (!empty($_SESSION['sucesso'])): ?>
        <div class="alert alert-success"><?= $_SESSION['sucesso']; unset($_SESSION['sucesso']); ?></div>
    <?php elseif (!empty($_SESSION['erro'])): ?>
        <div class="alert alert-danger"><?= $_SESSION['erro']; unset($_SESSION['erro']); ?></div>
    <?php endif; ?>

    <!-- Formulário Upload -->
    <form method="POST" enctype="multipart/form-data" class="row g-2 mb-4">
        <div class="col-md-4">
            <input type="file" name="arquivo" class="form-control" required>
        </div>
        <div class="col-md-4">
            <select name="categoria" class="form-select" required>
                <option value="">Selecione a categoria</option>
                <?php foreach ($categorias as $cat): ?>
                    <option value="<?= $cat ?>" <?= $categoria_ativa === $cat ? 'selected' : '' ?>><?= ucfirst($cat) ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="col-md-4">
            <button class="btn btn-success w-100" type="submit">
                <i class="bi bi-upload"></i> Enviar
            </button>
        </div>
    </form>

    <!-- Filtro de categoria -->
    <form method="GET" class="mb-3">
        <label>Categoria:</label>
        <select name="categoria" class="form-select w-auto d-inline-block" onchange="this.form.submit()">
            <?php foreach ($categorias as $cat): ?>
                <option value="<?= $cat ?>" <?= $categoria_ativa === $cat ? 'selected' : '' ?>><?= ucfirst($cat) ?></option>
            <?php endforeach; ?>
        </select>
    </form>

    <?php if (count($arquivos) === 0): ?>
        <div class="alert alert-warning">Nenhum arquivo encontrado nesta categoria.</div>
    <?php else: ?>
        <div class="table-responsive">
            <table class="table table-striped table-bordered">
                <thead class="table-dark">
                    <tr>
                        <th>Arquivo</th>
                        <th>Tamanho</th>
                        <th>Data</th>
                        <th class="text-center">Ações</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($arquivos as $arq): ?>
                        <tr>
                            <td><?= basename($arq) ?></td>
                            <td><?= round(filesize($arq) / 1024, 2) ?> KB</td>
                            <td><?= date('d/m/Y H:i', filemtime($arq)) ?></td>
                            <td class="text-center">
                                <a href="<?= URL_BASE . 'uploads/arquivos/' . $categoria_ativa . '/' . urlencode(basename($arq)) ?>" target="_blank" class="btn btn-sm btn-primary">🔗 Ver</a>
                                <a href="gerenciar_arquivos.php?delete=<?= urlencode(basename($arq)) ?>&cat=<?= $categoria_ativa ?>" class="btn btn-sm btn-danger" onclick="return confirm('Deseja excluir este arquivo?')">🗑️</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php endif; ?>
</div>

<?php require_once BASE_PATH . '/includes/footer.php'; ?>
