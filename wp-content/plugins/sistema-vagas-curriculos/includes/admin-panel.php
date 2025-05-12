<?php
add_action('admin_menu', 'svc_adicionar_menu_admin');
function svc_adicionar_menu_admin() {
    add_menu_page('Gerenciar Vagas', 'Vagas', 'manage_options', 'svc_vagas', '__return_null', 'dashicons-businessman', 6);
    add_submenu_page('svc_vagas', 'Currículos', 'Currículos', 'manage_options', 'svc_curriculos', '__return_null');
    add_submenu_page('svc_vagas', 'Painel do Candidato', 'Painel do Candidato', 'read', 'painel-candidato', 'svc_redirecionar_para_painel');
}
function svc_redirecionar_para_painel() {
    $pagina = get_page_by_path('painel-do-candidato');
    if ($pagina) {
        wp_redirect(get_permalink($pagina));
        exit;
    } else {
        echo '<div class="wrap"><h1>Página não encontrada</h1></div>';
    }
}
