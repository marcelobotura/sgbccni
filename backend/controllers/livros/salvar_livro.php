if (isset($_FILES['capa']) && $_FILES['capa']['error'] === 0) {
    $permitidos = ['image/jpeg', 'image/png'];
    $tipo = mime_content_type($_FILES['capa']['tmp_name']);

    if (!in_array($tipo, $permitidos)) {
        die('❌ Formato de imagem inválido.');
    }

    // prossegue com upload...
}
