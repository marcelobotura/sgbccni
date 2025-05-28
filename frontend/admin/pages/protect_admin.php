<?php
// 🔐 Inclusão padrão para páginas protegidas do painel administrativo

// Caminho raiz para acesso aos arquivos globais
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/session.php';

// Acesso restrito a administradores
exigir_login('admin');
