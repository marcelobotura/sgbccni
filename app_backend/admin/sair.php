<?php
define('BASE_PATH', __DIR__);
require_once BASE_PATH . '/../config/config.php';

session_start();
session_destroy();
header('Location: ' . URL_BASE . 'login/index.php');
exit;
