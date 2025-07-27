<?php
// protect_admin.php
require_once __DIR__ . '/session.php';
exigir_login(['admin', 'master']);
