<?php
// Caminho: backend/includes/session.php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// 🔗 Configurações globais (URL_BASE etc.)
require_once __DIR__ . '/../config/env.php';

// 🔐 Inicia sessão, se necessário
if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}

// ✅ Verifica se está logado
function usuario_logado(): bool {
    return isset($_SESSION['usuario_id']);
}

// ✅ Retorna o tipo do usuário logado
function tipo_usuario(): ?string {
    return $_SESSION['usuario_tipo'] ?? null;
}

/**
 * ✅ Exige login com verificação de tipo (usuario, admin ou master)
 * - Aceita string ('admin') ou array ['admin', 'master']
 */
if (!function_exists('exigir_login')) {
    function exigir_login(string|array|null $nivel = null): void {
        if (!usuario_logado()) {
            $_SESSION['erro'] = "Você precisa estar logado.";
            header('Location: ' . URL_BASE . 'frontend/login/login.php');
            exit;
        }

        $usuarioTipo = tipo_usuario();

        // Se não for exigido nível específico, apenas exige login
        if ($nivel === null) return;

        // Define permissões padrão
        $mapaPermissoes = [
            'usuario' => ['usuario', 'admin', 'master'],
            'admin'   => ['admin', 'master'],
            'master'  => ['master']
        ];

        // Converte string simples para array de permissões
        $permissoesAceitas = [];

        if (is_string($nivel)) {
            $permissoesAceitas = $mapaPermissoes[$nivel] ?? [];
        } elseif (is_array($nivel)) {
            foreach ($nivel as $tipo) {
                $permissoesAceitas = array_merge($permissoesAceitas, $mapaPermissoes[$tipo] ?? []);
            }
            $permissoesAceitas = array_unique($permissoesAceitas);
        }

        if (!in_array($usuarioTipo, $permissoesAceitas)) {
            $_SESSION['erro'] = "Acesso restrito para este nível.";

            if ($nivel === 'master' || (is_array($nivel) && in_array('master', $nivel))) {
                header('Location: ' . URL_BASE . 'public_html/erro_permissao_master.php');
            } else {
                header('Location: ' . URL_BASE . 'public_html/erro_permissao.php');
            }

            exit;
        }
    }
}
