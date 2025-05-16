<?php
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

    // JS responsável pela validação do formulario.php
    wp_enqueue_script('svc-formularios-js', plugin_dir_url(__DIR__) . 'assets/js/formularios.js', ['jquery'], null, true);

    // Font Awesome
    wp_enqueue_style('font-awesome', 'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css');

    // JS customizado
    wp_enqueue_script('svc-formularios-js', plugin_dir_url(__DIR__) . 'assets/js/formularios.js', ['jquery'], null, true);

    // Estilo customizado geral
    wp_enqueue_style('svc-style', plugin_dir_url(__DIR__) . 'assets/css/style.css');

    // CSS específico do painel candidato - só carrega nas páginas internas do painel
    if (isset($_GET['pagina'])) {
        $paginas_painel = ['meu-curriculo', 'minhas-candidaturas', 'editar-cadastro', 'alterar-senha'];
        if (in_array($_GET['pagina'], $paginas_painel)) {
            wp_enqueue_style(
                'painel-candidato',
                plugin_dir_url(__DIR__) . 'assets/css/painel-candidato.css',
                [],
                '1.0'
            );
        }
    }
});

/**
 * Remove páginas internas dos menus automáticos (caso o tema use wp_list_pages)
 */
add_filter('get_pages', function ($pages) {
    $ocultar_slugs = [
        'meu-curriculo',
        'login-candidato',
        'cadastro-candidato',
        'cadastro-de-vagas',
        'painel-do-candidato',
        'logout-candidato',
        'recuperar-senha',
        'resetar-senha'
    ];

    return array_filter($pages, function ($page) use ($ocultar_slugs) {
        return !in_array($page->post_name, $ocultar_slugs);
    });
});

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

/**
 * Adiciona itens dinâmicos ao menu principal (Painel, Sair, Login, Cadastro)
 */
add_filter('wp_nav_menu_items', 'svc_adicionar_itens_menu_logout', 10, 2);
function svc_adicionar_itens_menu_logout($items, $args) {
    if (session_status() === PHP_SESSION_NONE) session_start();

    // Verifica se é o menu principal
    if ($args->theme_location === 'primary' || $args->menu === 'menu-principal') {
        // Se o candidato estiver logado, adiciona "Painel" e "Sair"
        if (!empty($_SESSION['candidato_id'])) {
            $painel = '<li class="menu-item"><a href="' . site_url('/painel-do-candidato') . '">Painel</a></li>';
            $sair   = '<li class="menu-item"><a href="' . site_url('/logout-candidato') . '">Sair</a></li>';
            $items .= $painel . $sair;
        } else {
            // Caso contrário, exibe Login
            $login = '<li class="menu-item"><a href="' . site_url('/login-candidato') . '">Login</a></li>';
            $items .= $login;
        }
    }

    return $items;
}

add_filter('wp_nav_menu_items', 'svc_itens_menu_dinamico', 10, 2);
function svc_itens_menu_dinamico($items, $args) {
    if (session_status() === PHP_SESSION_NONE) session_start();

    // Apenas no menu principal
    if ($args->theme_location === 'primary') {
        if (!empty($_SESSION['candidato_id'])) {
            // Adiciona itens para usuários logados
            $painel = '<li class="menu-item"><a href="' . site_url('/painel-do-candidato') . '">Painel</a></li>';
            $logout = '<li class="menu-item"><a href="' . site_url('/logout-candidato') . '">Sair</a></li>';
            $items .= $painel . $logout;
        } else {
            // Adiciona itens para visitantes
            $login = '<li class="menu-item"><a href="' . site_url('/login-candidato') . '">Login</a></li>';
            $cadastro = '<li class="menu-item"><a href="' . site_url('/cadastro-candidato') . '">Cadastre-se</a></li>';
            $items .= $login . $cadastro;
        }
    }

    return $items;
}
