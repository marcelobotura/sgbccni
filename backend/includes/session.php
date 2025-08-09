<?php
// Caminho: backend/includes/session.php
// Versão compatível com PHP 7+ (sem union types / return types)

require_once __DIR__ . '/../config/config.php'; // carrega env/config (URL_BASE etc.)

// ---- Sessão segura ----
if (session_status() !== PHP_SESSION_ACTIVE) {
    $params = session_get_cookie_params();
    session_set_cookie_params([
        'lifetime' => 0,
        'path'     => isset($params['path']) ? $params['path'] : '/',
        'domain'   => isset($params['domain']) ? $params['domain'] : '',
        'secure'   => (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on'),
        'httponly' => true,
        'samesite' => 'Lax',
    ]);
    session_start();
}

// ---- Helpers ----
if (!function_exists('usuario_logado')) {
    function usuario_logado() {
        return isset($_SESSION['usuario_id']);
    }
}

if (!function_exists('tipo_usuario')) {
    function tipo_usuario() {
        return isset($_SESSION['usuario_tipo']) ? $_SESSION['usuario_tipo'] : null;
    }
}

if (!function_exists('redirect_base')) {
    function redirect_base($path = 'login') {
        // Se NÃO usa URL amigável, troque a linha abaixo por:
        // header('Location: ' . URL_BASE . ltrim($path, '/') . '.php');
        header('Location: ' . URL_BASE . ltrim($path, '/'));
        exit;
    }
}

/**
 * Exige login e, opcionalmente, nível de acesso.
 * $nivel pode ser: null | 'usuario' | 'admin' | 'master' | array com esses valores
 */
if (!function_exists('exigir_login')) {
    function exigir_login($nivel = null) {
        // valida tipo do parâmetro (provisório e defensivo)
        if (!is_null($nivel) && !is_string($nivel) && !is_array($nivel)) {
            // silenciosamente ignora tipo inválido
            $nivel = null;
        }

        if (!usuario_logado()) {
            $_SESSION['erro'] = 'Você precisa estar logado.';
            redirect_base('login'); // ou 'login.php' se não usar URLs amigáveis
        }

        if (is_null($nivel)) return;

        $usuarioTipo = tipo_usuario();

        // Mapa de permissões por hierarquia
        $mapaPermissoes = [
            'usuario' => ['usuario', 'admin', 'master'],
            'admin'   => ['admin', 'master'],
            'master'  => ['master'],
        ];

        // Constrói lista final de permissões aceitas
        $permissoesAceitas = [];
        if (is_string($nivel)) {
            $permissoesAceitas = isset($mapaPermissoes[$nivel]) ? $mapaPermissoes[$nivel] : [];
        } elseif (is_array($nivel)) {
            foreach ($nivel as $n) {
                if (isset($mapaPermissoes[$n])) {
                    $permissoesAceitas = array_merge($permissoesAceitas, $mapaPermissoes[$n]);
                }
            }
            $permissoesAceitas = array_values(array_unique($permissoesAceitas));
        }

        if (!in_array($usuarioTipo, $permissoesAceitas, true)) {
            $_SESSION['erro'] = 'Acesso restrito para este nível.';

            // Páginas públicas de erro (crie em public_html/)
            // Se não usa URLs amigáveis, acrescente ".php" abaixo.
            $paginaErro = (is_string($nivel) && $nivel === 'master')
                          || (is_array($nivel) && in_array('master', $nivel, true))
                ? 'erro_permissao_master'
                : 'erro_permissao';

            redirect_base($paginaErro);
        }
    }
}
