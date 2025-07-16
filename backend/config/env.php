<?php
// backend/config/env.php

// Define a constante do diretório base para evitar problemas de caminho
// Garante que o URL_BASE esteja definido para a raiz do seu projeto web (sgbccni)
// Use __DIR__ para obter o diretório atual (backend/config) e navegue para cima
// até chegar em htdocs/sgbccni/
define('ROOT_PATH', dirname(__DIR__, 2) . DIRECTORY_SEPARATOR); // Sai de /config e /backend

// URL base do projeto (ajuste conforme o seu ambiente, por exemplo, se for subdiretório)
// Usamos o ROOT_PATH para construir o URL_BASE dinamicamente.
// A parte 'http://localhost/sgbccni/' deve refletir o endereço base do seu projeto no navegador.
// Se o seu projeto estiver diretamente em htdocs, seria 'http://localhost/'
// Se estiver em um subdiretório, como '/sgbccni/', inclua-o.
define('URL_BASE', 'http://localhost/sgbccni/'); // 

// Configurações de conexão com o banco de dados
define('DB_HOST', 'localhost'); // Host do banco 
define('DB_NAME', 'sgbccni');   // Nome do banco de dados 
define('DB_USER', 'root');      // Usuário do banco 

// !! IMPORTANTE: Senha do banco de dados. Em produção, NUNCA use uma senha vazia ou padrão.
// Para desenvolvimento XAMPP sem senha, pode deixar vazio, mas considere usar uma senha.
define('DB_PASS', '');          // Senha do banco 

// Caminho para o executável mysqldump
// O caminho deve ser ajustado conforme a sua instalação do XAMPP ou ambiente de servidor.
// No Windows (XAMPP), geralmente é C:\xampp\mysql\bin\mysqldump.exe
// Em Linux/macOS, geralmente é apenas 'mysqldump' se estiver no PATH, ou o caminho completo.
define('MYSQLDUMP_PATH', 'C:\\xampp\\mysql\\bin\\mysqldump.exe'); // 

// Ambiente de desenvolvimento
// Defina como 'true' para ambiente de desenvolvimento (exibe erros, etc.)
// Defina como 'false' para ambiente de produção (oculta erros detalhados)
define('ENV_DEV', true); // 


// Configurações de tempo de vida da sessão (em segundos)
// Por padrão, PHP usa session.gc_maxlifetime, mas definir aqui permite maior controle.
define('SESSION_LIFETIME', 3600); // 1 hora de vida útil da sessão

// Configuração de diretório de logs para erros PHP e outros registros
define('LOG_DIR', ROOT_PATH . 'backend' . DIRECTORY_SEPARATOR . 'logs' . DIRECTORY_SEPARATOR); // 
ini_set('error_log', LOG_DIR . 'php-error.log'); // 


// Configuração para diretório de uploads
define('UPLOAD_DIR', ROOT_PATH . 'uploads' . DIRECTORY_SEPARATOR); // 
define('CAPAS_DIR', UPLOAD_DIR . 'capas' . DIRECTORY_SEPARATOR); // 
define('PERFIS_DIR', UPLOAD_DIR . 'perfis' . DIRECTORY_SEPARATOR); // 
define('QRC_DIR', UPLOAD_DIR . 'qrcodes' . DIRECTORY_SEPARATOR); // 

// Define o fuso horário (importante para funções de data e hora)
date_default_timezone_set('America/Sao_Paulo'); // Fuso horário de Foz do Iguaçu 


if (ENV_DEV) {
    // Para ambiente de desenvolvimento, exibe todos os erros
    error_reporting(E_ALL);
    ini_set('display_errors', 1);
} else {
    // Para ambiente de produção, oculta erros e apenas os registra
    error_reporting(E_ALL);
    ini_set('display_errors', 0);
    ini_set('log_errors', 1);
}