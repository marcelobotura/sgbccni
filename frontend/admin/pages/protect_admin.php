<?php
require_once __DIR__ . '/../../../backend/config/config.php';
require_once __DIR__ . '/../../../backend/includes/session.php';

// 🔐 Protege acesso apenas para administradores
exigir_login('admin');
