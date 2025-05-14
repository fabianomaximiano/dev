<?php
// Impede acesso direto ao arquivo
if (!defined('ABSPATH')) exit;

/**
 * Inicia a sessão PHP para controle de login do candidato via $_SESSION
 */
add_action('init', function () {
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
});

/**
 * Carrega CSS e JS do plugin
 */
add_action('wp_enqueue_scripts', function () {
    // Bootstrap 4
    wp_enqueue_style('bootstrap-css', 'https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css');
    wp_enqueue_script('bootstrap-js', 'https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js', ['jquery'], null, true);

    // Máscaras
    wp_enqueue_script('jquery-mask', 'https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.16/jquery.mask.min.js', ['jquery'], null, true);

    // JS de validação personalizado
    wp_enqueue_script('validacao-js', plugin_dir_url(__DIR__) . 'assets/js/validacao.js', ['jquery'], null, true);

    // Estilo customizado
    wp_enqueue_style('svc-style', plugin_dir_url(__DIR__) . 'assets/css/style.css');
});

/**
 * Remove páginas internas dos menus automáticos (caso o tema use wp_list_pages)
 */
add_filter('get_pages', function ($pages, $args) {
    $ocultar_slugs = [
        'meu-curriculo',
        'logout-candidato',
        'cadastro-candidato',
        'login-candidato',
        'painel-do-candidato'
    ];

    return array_filter($pages, function ($page) use ($ocultar_slugs) {
        return !in_array($page->post_name, $ocultar_slugs);
    });
}, 10, 2);

/**
 * Registra templates personalizados do plugin
 */
add_filter('theme_page_templates', 'svc_registrar_templates_plugin');
function svc_registrar_templates_plugin($templates) {
    $templates['template-lista-vagas.php'] = 'Lista de Vagas';
    $templates['template-painel-candidato.php'] = 'Painel do Candidato';
    return $templates;
}

/**
 * Carrega o template do plugin se a página estiver usando um dos templates registrados
 */
add_filter('template_include', 'svc_carregar_template_plugin');
function svc_carregar_template_plugin($template) {
    if (is_page()) {
        $modelo = get_page_template_slug();
        $caminho = plugin_dir_path(__FILE__) . '../templates/' . $modelo;

        if ($modelo && file_exists($caminho)) {
            return $caminho;
        }
    }
    return $template;
}

/**
 * Carrega os shortcodes auxiliares do plugin (logout, menu do candidato, etc.)
 */
require_once __DIR__ . '/shortcodes.php';
