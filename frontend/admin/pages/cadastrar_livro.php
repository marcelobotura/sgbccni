<?php
include_once __DIR__ . '/../../config/config.php';
require_once __DIR__ . '/../../includes/protect.php';
require_once __DIR__ . '/../../includes/header.php';
?>


<?php



$msg = '';

// Buscar tags por tipo
function buscarTags($conn, $tipo) {
    $stmt = $conn->prepare("SELECT id, nome FROM tags WHERE tipo = ? ORDER BY nome ASC");
    $stmt->bind_param("s", $tipo);
    $stmt->execute();
    return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
}

$autores = buscarTags($conn, 'autor');
$categorias = buscarTags($conn, 'categoria');
$editoras = buscarTags($conn, 'editora');

// Tratamento de envio
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $isbn = $_POST['isbn'] ?? '';
    $isbn10 = $_POST['isbn10'] ?? '';
    $isbn13 = $_POST['isbn13'] ?? '';
    $titulo = $_POST['titulo'] ?? '';
    $autor = $_POST['autor'] ?? '';
    $editora = $_POST['editora'] ?? '';
    $ano = $_POST['ano'] ?? '';
    $status = $_POST['status'] ?? '';
    $categoria = $_POST['categoria'] ?? '';
    $tags = $_POST['tags'] ?? '';
    $descricao = $_POST['descricao'] ?? '';
    $tipo = $_POST['tipo'] ?? '';
    $link_digital = $_POST['link_digital'] ?? '';
    $capa_url = $_POST['capa_url'] ?? '';
    $capa = '';
    $criado_em = date("Y-m-d H:i:s");

    // Upload da imagem se não usar URL
    if ($_POST['usar_url_capa'] === 'nao' && !empty($_FILES['capa']['name'])) {
        $nomeArquivo = uniqid() . '_' . basename($_FILES['capa']['name']);
        $destino = __DIR__ . '/../../uploads/capas/' . $nomeArquivo;

        if (!is_dir(dirname($destino))) mkdir(dirname($destino), 0755, true);

        if (move_uploaded_file($_FILES['capa']['tmp_name'], $destino)) {
            $capa = $nomeArquivo;
        } else {
            $msg = "❌ Erro ao mover imagem.";
        }
    }

    // ✅ Verificação de ISBN único
    $verifica = $conn->prepare("SELECT id FROM livros WHERE isbn = ?");
    $verifica->bind_param("s", $isbn);
    $verifica->execute();
    $verifica->store_result();

    if ($verifica->num_rows > 0) {
        $msg = "❌ Este ISBN já está cadastrado!";
    } else {
        // ✅ Inserção somente se for ISBN novo
        $stmt = $conn->prepare("INSERT INTO livros (isbn, isbn10, isbn13, titulo, autor, editora, ano, status, tipo, link_digital, capa_url, capa, descricao, categoria_padrao, tags, criado_em)
                                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("ssssssssssssssss", $isbn, $isbn10, $isbn13, $titulo, $autor, $editora, $ano, $status, $tipo, $link_digital, $capa_url, $capa, $descricao, $categoria, $tags, $criado_em);

        if ($stmt->execute()) {
            $msg = "✅ Livro cadastrado com sucesso!";
        } else {
            $msg = "❌ Erro ao cadastrar: " . $stmt->error;
        }

        $stmt->close();
    }

    $verifica->close();
}
?>

<div class="container mt-5">
  <h2>📘 Cadastro de Livro</h2>

  <?php if (!empty($msg)): ?>
    <div class="alert alert-info"><?= $msg ?></div>
  <?php endif; ?>

  <form method="POST" enctype="multipart/form-data">
    <!-- ISBN -->
    <div class="mb-3">
      <label>ISBN</label>
      <div class="input-group">
        <input type="text" name="isbn" id="isbn" class="form-control" placeholder="Digite o ISBN" required>
        <button type="button" onclick="buscarISBN()" class="btn btn-outline-secondary">Buscar ISBN</button>
      </div>
    </div>

    <div class="row">
      <div class="col-md-6 mb-3">
        <label>ISBN-10</label>
        <input type="text" name="isbn10" id="isbn10" class="form-control">
      </div>
      <div class="col-md-6 mb-3">
        <label>ISBN-13</label>
        <input type="text" name="isbn13" id="isbn13" class="form-control">
      </div>
    </div>

    <div class="mb-3">
      <label>Título</label>
      <input type="text" name="titulo" id="titulo" class="form-control" required>
    </div>

    <div class="mb-3">
      <label>Autor</label>
      <select name="autor" id="autor" class="form-control" required>
        <option value="">-- Selecione um autor --</option>
        <?php foreach ($autores as $a): ?>
          <option value="<?= $a['nome'] ?>"><?= $a['nome'] ?></option>
        <?php endforeach; ?>
      </select>
    </div>

    <div class="mb-3">
      <label>Editora</label>
      <select name="editora" id="editora" class="form-control" required>
        <option value="">-- Selecione uma editora --</option>
        <?php foreach ($editoras as $e): ?>
          <option value="<?= $e['nome'] ?>"><?= $e['nome'] ?></option>
        <?php endforeach; ?>
      </select>
    </div>

    <div class="row">
      <div class="col-md-6 mb-3">
        <label>Ano</label>
        <input type="number" name="ano" id="ano" class="form-control" placeholder="ex: 2023" required>
      </div>
      <div class="col-md-6 mb-3">
        <label>Status</label>
        <select name="status" class="form-control" required>
          <option value="disponível">Disponível</option>
          <option value="emprestado">Emprestado</option>
          <option value="reservado">Reservado</option>
        </select>
      </div>
    </div>

    <div class="mb-3">
      <label>Categoria</label>
      <select name="categoria" id="categoria" class="form-control" required>
        <option value="">-- Selecione uma categoria --</option>
        <?php foreach ($categorias as $c): ?>
          <option value="<?= $c['nome'] ?>"><?= $c['nome'] ?></option>
        <?php endforeach; ?>
      </select>
    </div>

    <div class="mb-3">
      <label>Tags (separadas por vírgula)</label>
      <input type="text" name="tags" id="tags" class="form-control" placeholder="ex: aventura, mistério, clássico">
    </div>

    <div class="mb-3">
      <label>Tipo</label>
      <select name="tipo" id="tipo" class="form-control" onchange="mostrarCamposTipo()" required>
        <option value="físico">Físico</option>
        <option value="digital">Digital</option>
      </select>
    </div>

    <div class="mb-3" id="campo-link" style="display:none;">
      <label>Link do livro digital</label>
      <input type="text" name="link_digital" class="form-control" placeholder="https://...">
    </div>

    <div class="mb-3">
      <label>Descrição</label>
      <textarea name="descricao" id="descricao" class="form-control" rows="4"></textarea>
    </div>

    <div class="mb-3">
      <label>Capa do Livro</label><br>
      <label><input type="radio" name="usar_url_capa" value="sim" checked onchange="alternarCapa()"> Usar URL</label>
      <label class="ms-3"><input type="radio" name="usar_url_capa" value="nao" onchange="alternarCapa()"> Upload</label>
    </div>

    <div class="mb-3" id="campo-capa-url">
      <input type="text" name="capa_url" id="capa_url" class="form-control" placeholder="Link da imagem">
    </div>

    <div class="mb-3" id="campo-capa-upload" style="display:none;">
      <input type="file" name="capa" class="form-control" accept="image/*">
    </div>

    <button type="submit" class="btn btn-success">Salvar Livro</button>
  </form>
</div>

<?php require_once __DIR__ . '/../../includes/footer.php'; ?>

<script>
function buscarISBN() {
  const isbn = document.getElementById('isbn').value.trim().replace(/[-\s]/g, '');
  if (isbn.length < 10) return alert("ISBN inválido.");

  fetch(`https://www.googleapis.com/books/v1/volumes?q=isbn:${isbn}`)
    .then(res => res.json())
    .then(async data => {
      if (data.totalItems > 0) {
        const info = data.items[0].volumeInfo;
        const titulo = info.title || '';
        const descricao = info.description || '';
        const capa_url = info.imageLinks?.thumbnail || '';
        const autores = info.authors || [];
        const categorias = info.categories || [];
        const editora = info.publisher || '';
        const ano = info.publishedDate ? info.publishedDate.substring(0, 4) : '';

        document.getElementById('titulo').value = titulo;
        document.getElementById('descricao').value = descricao;
        document.getElementById('capa_url').value = capa_url;
        document.getElementById('ano').value = ano;

        (info.industryIdentifiers || []).forEach(i => {
          if (i.type === 'ISBN_10') document.getElementById('isbn10').value = i.identifier;
          if (i.type === 'ISBN_13') document.getElementById('isbn13').value = i.identifier;
        });

        if (autores.length > 0) await salvarETrocarTag(autores[0], 'autor', 'autor');
        if (categorias.length > 0) await salvarETrocarTag(categorias[0], 'categoria', 'categoria');
        if (editora && editora.trim() !== '') await salvarETrocarTag(editora.trim(), 'editora', 'editora');

        alert("📚 Dados preenchidos! Você pode revisar e ajustar antes de salvar.");
      } else {
        alert("Livro não encontrado.");
      }
    })
    .catch(err => {
      console.error("Erro ao buscar ISBN", err);
      alert("Erro ao buscar ISBN.");
    });
}

async function salvarETrocarTag(valor, tipo, campoId) {
  const select = document.getElementById(campoId);
  let existe = false;

  for (let option of select.options) {
    if (option.value.toLowerCase() === valor.toLowerCase()) {
      existe = true;
      break;
    }
  }

  if (!existe) {
    await fetch('../ajax/salvar_tag.php', {
      method: 'POST',
      headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
      body: `nome=${encodeURIComponent(valor)}&tipo=${encodeURIComponent(tipo)}`
    });

    const novaOption = new Option(valor, valor, true, true);
    select.add(novaOption);
  }

  select.value = valor;
  if (window.jQuery && $(select).hasClass("select2-hidden-accessible")) {
    $(select).trigger('change');
  }
}

function mostrarCamposTipo() {
  const tipo = document.getElementById('tipo').value;
  document.getElementById('campo-link').style.display = (tipo === 'digital') ? 'block' : 'none';
}

function alternarCapa() {
  const usarUrl = document.querySelector('input[name=usar_url_capa]:checked').value === 'sim';
  document.getElementById('campo-capa-url').style.display = usarUrl ? 'block' : 'none';
  document.getElementById('campo-capa-upload').style.display = usarUrl ? 'none' : 'block';
}

$(document).ready(function () {
  $('#autor').select2({ placeholder: "Selecione ou digite um autor", allowClear: true });
  $('#categoria').select2({ placeholder: "Selecione uma categoria", allowClear: true });
  $('#editora').select2({ placeholder: "Selecione uma editora", allowClear: true });
});
</script>
