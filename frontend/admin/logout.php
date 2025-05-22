<?php
require_once __DIR__ . '/../../backend/config/config.php';
session_start();
session_unset();
session_destroy();

header("Location: ../../public_html/login/login_admin.php");
exit;