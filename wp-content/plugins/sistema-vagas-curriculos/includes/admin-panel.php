<?php
if (!defined('ABSPATH')) exit;

/**
 * Adiciona menu administrativo personalizado
 */
add_action('admin_menu', 'svc_adicionar_menu_admin');

function svc_adicionar_menu_admin() {
    // Menu principal: Vagas
    add_menu_page(
        'Gerenciar Vagas',
        'Vagas',
        'manage_options',
        'svc_vagas',
        '__return_null',
        'dashicons-businessman',
        6
    );

    // Submenu: Currículos (admin)
    add_submenu_page(
        'svc_vagas',
        'Currículos',
        'Currículos',
        'manage_options',
        'svc_curriculos',
        '__return_null'
    );

    // Submenu: Painel do Candidato (acesso externo)
    add_submenu_page(
        'svc_vagas',
        'Painel do Candidato',
        'Painel do Candidato',
        'read',
        'painel-candidato',
        'svc_redirecionar_para_painel'
    );
}

/**
 * Redireciona submenu “Painel do Candidato” para a página externa
 */
function svc_redirecionar_para_painel() {
    $pagina = get_page_by_path('painel-do-candidato');
    if ($pagina) {
        wp_redirect(get_permalink($pagina));
        exit;
    } else {
        echo '<div class="notice notice-error"><p>Página "Painel do Candidato" não encontrada.</p></div>';
    }
}
