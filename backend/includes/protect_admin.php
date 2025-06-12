<?php
// Apenas carrega a sessão e exige login do tipo 'admin'
require_once __DIR__ . '/session.php';
exigir_login('admin');
