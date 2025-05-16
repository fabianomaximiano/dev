<?php
/*
Plugin Name: Sistema Vagas e Currículos
*/
error_log('Plugin sistema-vagas-curriculos carregado');
// Arquivos que podem ser carregados direto (não dependem de WP totalmente pronto)
$arquivos_gerais = [
    '/includes/ativacao.php',
    '/includes/functions.php',
    '/includes/formularios.php',
    '/includes/login-candidato.php',
    '/includes/importacao.php',
    '/includes/admin-panel.php',
    '/includes/formulario-curriculo.php',
    '/includes/formulario-vaga.php',
    '/includes/listar-vagas.php',
    '/includes/logout-candidato.php',
    '/includes/ajax-handlers.php', 
];

foreach ($arquivos_gerais as $arquivo) {
    $caminho = __DIR__ . $arquivo;
    if (file_exists($caminho)) {
        require_once $caminho;
    }
}

// Carrega o arquivo painel-candidato.php **somente** quando o WordPress estiver pronto e o usuário logado
add_action('template_redirect', function() {
    if (function_exists('is_user_logged_in') && is_user_logged_in()) {
        $arquivo = __DIR__ . '/includes/painel-candidato.php';
        if (file_exists($arquivo)) {
            require_once $arquivo;
        }
    }
});

// Hook de ativação
register_activation_hook(__FILE__, 'svc_instalacao_completa');

// Funções de mídia para upload, se ainda não existirem
if (!function_exists('media_handle_upload')) {
    require_once ABSPATH . 'wp-admin/includes/file.php';
    require_once ABSPATH . 'wp-admin/includes/media.php';
    require_once ABSPATH . 'wp-admin/includes/image.php';
}
