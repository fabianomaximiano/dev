<?php
        // Impede acesso direto
        if (!defined('ABSPATH')) {
            exit;
        }

        // Inicia sessão para controle de login do candidato
        add_action('init', function () {
            if (session_status() === PHP_SESSION_NONE) {
                session_start();
            }
        });

        // Carrega scripts e estilos (Bootstrap, máscaras, validações)
        add_action('wp_enqueue_scripts', function () {
            // Bootstrap 4
            wp_enqueue_style('bootstrap-css', 'https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css');
            wp_enqueue_script('bootstrap-js', 'https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js', ['jquery'], null, true);

            // jQuery Mask Plugin
            wp_enqueue_script('jquery-mask', 'https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.16/jquery.mask.min.js', ['jquery'], null, true);

            // Validações JS personalizadas
            wp_enqueue_script('validacao-js', plugin_dir_url(__FILE__) . 'assets/js/validacao.js', ['jquery'], null, true);

            // Estilos adicionais do plugin
            wp_enqueue_style('svc-style', plugin_dir_url(__FILE__) . 'assets/css/style.css');
            
            //javascript do formulario.php
            wp_enqueue_script('svc-formularios', plugin_dir_url(__FILE__) . '../assets/js/formularios.js', ['jquery'], null, true);
 
        });

        // Remove páginas internas do menu quando for exibido automaticamente (por temas)
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

        // Importa shortcodes (ex: menu_candidato, logout)
        require_once __DIR__ . '/shortcodes.php';

 /**
 * Registra os templates do plugin como opções no editor de páginas
 */
add_filter('theme_page_templates', 'svc_registrar_templates_plugin');

function svc_registrar_templates_plugin($templates) {
    $templates['template-lista-vagas.php'] = 'Lista de Vagas';
    $templates['template-painel-candidato.php'] = 'Painel do Candidato';
    return $templates;
}

/**
 * Aponta para os templates do plugin quando selecionados
 */
add_filter('template_include', 'svc_carregar_template_plugin');

function svc_carregar_template_plugin($template) {
    if (is_page()) {
        $modelo = get_page_template_slug();
        $caminho_personalizado = plugin_dir_path(__FILE__) . '../templates/' . $modelo;

        if ($modelo && file_exists($caminho_personalizado)) {
            return $caminho_personalizado;
        }
    }

    return $template;
}


/** postype vagas**/
add_action('init', 'svc_registrar_cpt_vagas');

function svc_registrar_cpt_vagas() {
    register_post_type('vaga', [
        'labels' => [
            'name' => 'Vagas',
            'singular_name' => 'Vaga',
            'add_new' => 'Adicionar Nova Vaga',
            'add_new_item' => 'Adicionar Nova Vaga',
            'edit_item' => 'Editar Vaga',
            'new_item' => 'Nova Vaga',
            'view_item' => 'Ver Vaga',
            'search_items' => 'Buscar Vagas',
            'not_found' => 'Nenhuma vaga encontrada',
            'not_found_in_trash' => 'Nenhuma vaga na lixeira',
        ],
        'public' => true,
        'has_archive' => true,
        'rewrite' => ['slug' => 'vagas'],
        'show_in_rest' => true,
        'supports' => ['title', 'editor'],
        'menu_icon' => 'dashicons-businessman',
        'capability_type' => 'post',
        'menu_position' => 5,
    ]);
}
